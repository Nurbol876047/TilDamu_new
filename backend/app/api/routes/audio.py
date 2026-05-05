import mimetypes
from pathlib import Path

from fastapi import APIRouter, Depends, File, Form, HTTPException, Query, UploadFile, status
from fastapi.responses import FileResponse
from sqlalchemy import func, or_, select
from sqlalchemy.orm import joinedload
from sqlalchemy.orm import Session

from app.core.cache import cache
from app.core.config import settings
from app.core.database import get_db
from app.models.analysis import AnalysisResult
from app.models.audio import AudioRecord
from app.models.child import Child
from app.models.word import Word
from app.schemas.audio import (
    AudioDeleteResponse,
    AudioHistoryItem,
    AudioHistoryResponse,
    AudioHistoryTotals,
    AudioRecordWithAnalysis,
    AudioUploadResponse,
)
from app.services.analysis_service import analysis_service
from app.services.audio_service import audio_service
from app.services.speech_to_text_service import speech_to_text_service

router = APIRouter(prefix="/audio", tags=["audio"])
MAX_ATTEMPTS_PER_WORD = 3


def audio_file_size(file_path: str) -> int:
    path = Path(file_path)
    if not path.is_absolute():
        path = Path.cwd() / path
    return path.stat().st_size if path.is_file() else 0


def _attempt_index(attempt_number: str) -> int | None:
    value = attempt_number.strip().lower()
    if value.startswith("x"):
        value = value[1:]
    return int(value) if value.isdigit() else None


def next_word_attempt_number(db: Session, child_id: int, word_id: int) -> str:
    existing_attempts = list(
        db.scalars(
            select(AudioRecord.attempt_number)
            .where(
                AudioRecord.child_id == child_id,
                AudioRecord.word_id == word_id,
            )
            .order_by(AudioRecord.created_at.asc(), AudioRecord.id.asc())
        )
    )
    if len(existing_attempts) >= MAX_ATTEMPTS_PER_WORD:
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail="Для этого ребенка и слова уже сохранены 3 голосовые записи.",
        )

    used = {
        index
        for attempt in existing_attempts
        if (index := _attempt_index(attempt)) is not None
    }
    for attempt_index in range(1, MAX_ATTEMPTS_PER_WORD + 1):
        if attempt_index not in used:
            return f"x{attempt_index}"

    return f"x{len(existing_attempts) + 1}"


@router.post(
    "/upload",
    response_model=AudioUploadResponse,
    status_code=status.HTTP_201_CREATED,
)
def upload_audio(
    child_id: int = Form(...),
    word_id: int = Form(...),
    attempt_number: str = Form(..., examples=["x1"]),
    file: UploadFile = File(...),
    db: Session = Depends(get_db),
) -> AudioUploadResponse:
    child = db.get(Child, child_id)
    if not child:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Child not found")

    word = db.get(Word, word_id)
    if not word:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Word not found")

    attempt_number = next_word_attempt_number(db, child.id, word.id)

    candidate_words = list(
        db.scalars(
            select(Word)
            .where(Word.age_group == word.age_group)
            .order_by(Word.id.asc())
        )
    )

    audio = audio_service.create_audio_record(
        db=db,
        file=file,
        child_id=child.id,
        word_id=word.id,
        attempt_number=attempt_number,
    )
    recognition = speech_to_text_service.transcribe(
        file_path=audio.file_path,
        expected_word=word.text,
        candidate_words=[candidate.text for candidate in candidate_words],
        attempt_number=attempt_number,
    )
    analysis_payload = analysis_service.analyze(
        expected_word=word.text,
        recognized_word=recognition.recognized_word,
    )
    analysis = AnalysisResult(audio_id=audio.id, **analysis_payload)
    db.add(analysis)
    db.commit()
    db.refresh(audio)
    db.refresh(analysis)
    response = AudioUploadResponse(
        audio=audio,
        analysis=analysis,
        stt_provider=recognition.provider,
        raw_transcript=recognition.raw_transcript,
        dictionary_match=recognition.dictionary_match,
        dictionary_confidence=recognition.dictionary_confidence,
        recognition_steps=recognition.steps,
    )
    cache.set_json(
        cache.key("audio", audio.id, "upload_result"),
        response.model_dump(mode="json"),
        ttl_seconds=settings.audio_cache_ttl_seconds,
    )
    cache.set_json(
        cache.key("audio", "latest", "child", child.id),
        response.model_dump(mode="json"),
        ttl_seconds=settings.audio_cache_ttl_seconds,
    )
    cache.delete(cache.key("dashboard"))
    cache.delete(cache.key("analysis", "child", child.id))
    cache.delete(cache.key("audio", "child", child.id))
    cache.delete(cache.key("report", "child", child.id))
    return response


@router.get("/child/{child_id}", response_model=list[AudioRecordWithAnalysis])
def list_child_audio(child_id: int, db: Session = Depends(get_db)) -> list[AudioRecordWithAnalysis]:
    child = db.get(Child, child_id)
    if not child:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Child not found")

    cache_key = cache.key("audio", "child", child_id)
    cached = cache.get_json(cache_key)
    if cached is not None:
        return [AudioRecordWithAnalysis.model_validate(item) for item in cached]

    records = [
        AudioRecordWithAnalysis.model_validate(record)
        for record in db.scalars(
            select(AudioRecord)
            .options(joinedload(AudioRecord.analysis_result))
            .where(AudioRecord.child_id == child_id)
            .order_by(AudioRecord.created_at.desc())
        )
    ]
    cache.set_json(cache_key, [record.model_dump(mode="json") for record in records])
    return records


@router.get("/history", response_model=AudioHistoryResponse)
def list_audio_history(
    limit: int = Query(300, ge=1, le=500),
    search: str | None = Query(None),
    db: Session = Depends(get_db),
) -> AudioHistoryResponse:
    query = (
        select(AudioRecord)
        .join(Child, AudioRecord.child_id == Child.id)
        .join(Word, AudioRecord.word_id == Word.id)
        .options(
            joinedload(AudioRecord.child),
            joinedload(AudioRecord.word),
            joinedload(AudioRecord.analysis_result),
        )
        .order_by(AudioRecord.created_at.desc(), AudioRecord.id.desc())
    )

    count_query = select(func.count(AudioRecord.id)).join(Child).join(Word)
    children_query = select(func.count(func.distinct(AudioRecord.child_id))).join(Child).join(Word)

    if search:
        like = f"%{search.strip()}%"
        condition = or_(
            Child.full_name.ilike(like),
            Child.parent_name.ilike(like),
            Child.gender.ilike(like),
            Child.disorder_type.ilike(like),
            Word.text.ilike(like),
            Word.target_sound.ilike(like),
            AudioRecord.attempt_number.ilike(like),
        )
        query = query.where(condition)
        count_query = count_query.where(condition)
        children_query = children_query.where(condition)

    records = list(db.scalars(query.limit(limit)))
    total = int(db.scalar(count_query) or 0)
    children_total = int(db.scalar(children_query) or 0)

    items = [
        AudioHistoryItem(
            id=record.id,
            child_id=record.child_id,
            child_name=record.child.full_name,
            child_age=record.child.age,
            child_external_id=record.child.parent_name,
            child_gender=record.child.gender,
            disorder_type=record.child.disorder_type,
            word_id=record.word_id,
            word=record.word.text,
            target_sound=record.word.target_sound,
            attempt_number=record.attempt_number,
            file_path=record.file_path,
            audio_url=f"/audio/{record.id}/file",
            file_size=audio_file_size(record.file_path),
            duration=record.duration,
            created_at=record.created_at,
            analysis_result=record.analysis_result,
        )
        for record in records
    ]

    return AudioHistoryResponse(
        ok=True,
        total=total,
        limit=limit,
        totals=AudioHistoryTotals(
            children=children_total,
            recordings=total,
            bytes=sum(item.file_size for item in items),
        ),
        items=items,
    )


@router.get("/{audio_id}/cached-result", response_model=AudioUploadResponse)
def get_cached_audio_result(audio_id: int) -> AudioUploadResponse:
    cached = cache.get_json(cache.key("audio", audio_id, "upload_result"))
    if cached is None:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Cached audio result not found")
    return AudioUploadResponse.model_validate(cached)


@router.get("/child/{child_id}/latest-cached-result", response_model=AudioUploadResponse)
def get_latest_cached_audio_result(child_id: int) -> AudioUploadResponse:
    cached = cache.get_json(cache.key("audio", "latest", "child", child_id))
    if cached is None:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Cached child result not found")
    return AudioUploadResponse.model_validate(cached)


@router.delete("/{audio_id}", response_model=AudioDeleteResponse)
def delete_audio(audio_id: int, db: Session = Depends(get_db)) -> AudioDeleteResponse:
    audio = db.get(AudioRecord, audio_id)
    if not audio:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Audio not found")

    child_id = audio.child_id
    file_path = audio.file_path
    path = Path(file_path)
    if not path.is_absolute():
        path = Path.cwd() / path

    resolved_upload_dir = settings.upload_dir.resolve()
    resolved_path = path.resolve()
    if resolved_upload_dir not in resolved_path.parents:
        raise HTTPException(status_code=status.HTTP_403_FORBIDDEN, detail="Audio path is not allowed")

    db.delete(audio)
    db.commit()

    file_deleted = False
    if resolved_path.is_file():
        resolved_path.unlink()
        file_deleted = True

    cache.delete(cache.key("audio", audio_id, "upload_result"))
    cache.delete(cache.key("audio", "latest", "child", child_id))
    cache.delete(cache.key("audio", "child", child_id))
    cache.delete(cache.key("analysis", "child", child_id))
    cache.delete(cache.key("report", "child", child_id))
    cache.delete(cache.key("dashboard"))

    return AudioDeleteResponse(ok=True, audio_id=audio_id, file_deleted=file_deleted)


@router.get("/{audio_id}/file")
def download_audio(audio_id: int, db: Session = Depends(get_db)) -> FileResponse:
    audio = db.get(AudioRecord, audio_id)
    if not audio:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Audio not found")

    path = Path(audio.file_path)
    if not path.is_absolute():
        path = Path.cwd() / path
    if not path.exists() or not path.is_file():
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Audio file not found")

    if settings.upload_dir.resolve() not in path.resolve().parents:
        raise HTTPException(status_code=status.HTTP_403_FORBIDDEN, detail="Audio path is not allowed")

    media_type = mimetypes.guess_type(path.name)[0] or "application/octet-stream"
    return FileResponse(path, media_type=media_type, filename=path.name)
