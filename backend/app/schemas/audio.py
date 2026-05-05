from datetime import datetime

from pydantic import BaseModel, ConfigDict, Field

from app.schemas.analysis import AnalysisResultRead


class AudioRecordRead(BaseModel):
    id: int
    child_id: int
    word_id: int
    attempt_number: str
    file_path: str
    duration: float | None
    created_at: datetime

    model_config = ConfigDict(from_attributes=True)


class AudioUploadResponse(BaseModel):
    audio: AudioRecordRead
    analysis: AnalysisResultRead
    stt_provider: str = "mock"
    raw_transcript: str | None = None
    dictionary_match: str | None = None
    dictionary_confidence: int | None = None
    recognition_steps: list[str] = Field(default_factory=list)


class UploadError(BaseModel):
    detail: str


class AudioRecordWithAnalysis(AudioRecordRead):
    analysis_result: AnalysisResultRead | None = None


class AudioHistoryItem(BaseModel):
    id: int
    child_id: int
    child_name: str
    child_age: int
    child_external_id: str
    child_gender: str | None = None
    disorder_type: str
    word_id: int
    word: str
    target_sound: str | None
    attempt_number: str
    file_path: str
    audio_url: str
    file_size: int
    duration: float | None
    created_at: datetime
    analysis_result: AnalysisResultRead | None = None


class AudioHistoryTotals(BaseModel):
    children: int
    recordings: int
    bytes: int


class AudioHistoryResponse(BaseModel):
    ok: bool
    total: int
    limit: int
    totals: AudioHistoryTotals
    items: list[AudioHistoryItem]


class AudioDeleteResponse(BaseModel):
    ok: bool
    audio_id: int
    file_deleted: bool
