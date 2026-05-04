from fastapi import UploadFile
from sqlalchemy.orm import Session

from app.models.audio import AudioRecord
from app.utils.file_handler import save_audio_upload


class AudioService:
    def create_audio_record(
        self,
        db: Session,
        file: UploadFile,
        child_id: int,
        word_id: int,
        attempt_number: str,
    ) -> AudioRecord:
        file_path, bytes_written = save_audio_upload(file, child_id=child_id, word_id=word_id)
        audio = AudioRecord(
            child_id=child_id,
            word_id=word_id,
            attempt_number=attempt_number,
            file_path=file_path,
            duration=self._mock_duration(bytes_written),
        )
        db.add(audio)
        db.flush()
        return audio

    def _mock_duration(self, bytes_written: int) -> float:
        if bytes_written <= 0:
            return 0.0
        return round(max(1.0, min(bytes_written / 32000, 180.0)), 2)


audio_service = AudioService()

