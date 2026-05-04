from pathlib import Path
from uuid import uuid4

from fastapi import HTTPException, UploadFile, status

from app.core.config import settings


ALLOWED_AUDIO_EXTENSIONS = {".ogg", ".mp3", ".wav", ".m4a", ".webm", ".mp4"}


def validate_audio_file(file: UploadFile) -> str:
    original_name = file.filename or ""
    suffix = Path(original_name).suffix.lower()
    if suffix not in ALLOWED_AUDIO_EXTENSIONS:
        allowed = ", ".join(sorted(ALLOWED_AUDIO_EXTENSIONS))
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail=f"Unsupported audio format. Allowed: {allowed}",
        )
    return suffix


def save_audio_upload(file: UploadFile, child_id: int, word_id: int) -> tuple[str, int]:
    suffix = validate_audio_file(file)
    upload_dir = settings.upload_dir
    upload_dir.mkdir(parents=True, exist_ok=True)

    safe_name = f"child-{child_id}_word-{word_id}_{uuid4().hex}{suffix}"
    target_path = upload_dir / safe_name
    bytes_written = 0

    with target_path.open("wb") as buffer:
        while chunk := file.file.read(1024 * 1024):
            bytes_written += len(chunk)
            if bytes_written > settings.max_upload_bytes:
                target_path.unlink(missing_ok=True)
                raise HTTPException(
                    status_code=status.HTTP_413_REQUEST_ENTITY_TOO_LARGE,
                    detail=f"Audio file is larger than {settings.max_upload_mb} MB",
                )
            buffer.write(chunk)

    return str(target_path), bytes_written
