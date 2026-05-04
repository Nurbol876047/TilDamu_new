from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy import select
from sqlalchemy.orm import Session

from app.core.cache import cache
from app.core.database import get_db
from app.models.analysis import AnalysisResult
from app.models.audio import AudioRecord
from app.models.child import Child
from app.schemas.analysis import ChildAnalysisItem

router = APIRouter(prefix="/analysis", tags=["analysis"])


@router.get("/child/{child_id}", response_model=list[ChildAnalysisItem])
def get_child_analysis(child_id: int, db: Session = Depends(get_db)) -> list[ChildAnalysisItem]:
    child = db.get(Child, child_id)
    if not child:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Child not found")

    cache_key = cache.key("analysis", "child", child_id)
    cached = cache.get_json(cache_key)
    if cached is not None:
        return [ChildAnalysisItem.model_validate(item) for item in cached]

    rows = db.execute(
        select(AnalysisResult, AudioRecord)
        .join(AudioRecord, AnalysisResult.audio_id == AudioRecord.id)
        .where(AudioRecord.child_id == child_id)
        .order_by(AnalysisResult.created_at.desc())
    ).all()

    results = [
        ChildAnalysisItem(
            id=analysis.id,
            audio_id=analysis.audio_id,
            expected_word=analysis.expected_word,
            recognized_word=analysis.recognized_word,
            accuracy=analysis.accuracy,
            mistake_type=analysis.mistake_type,
            risk_level=analysis.risk_level,
            recommendation=analysis.recommendation,
            created_at=analysis.created_at,
            word_id=audio.word_id,
            child_id=audio.child_id,
            attempt_number=audio.attempt_number,
            file_path=audio.file_path,
        )
        for analysis, audio in rows
    ]
    cache.set_json(cache_key, [item.model_dump(mode="json") for item in results])
    return results
