from datetime import datetime

from pydantic import BaseModel, ConfigDict


class AnalysisResultCreate(BaseModel):
    audio_id: int
    expected_word: str
    recognized_word: str
    accuracy: int
    mistake_type: str
    risk_level: str
    recommendation: str


class AnalysisResultRead(AnalysisResultCreate):
    id: int
    created_at: datetime

    model_config = ConfigDict(from_attributes=True)


class ChildAnalysisItem(AnalysisResultRead):
    word_id: int
    child_id: int
    attempt_number: str
    file_path: str

