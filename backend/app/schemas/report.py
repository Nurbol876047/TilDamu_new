from datetime import datetime

from pydantic import BaseModel


class ReportChild(BaseModel):
    id: int
    full_name: str
    age: int
    parent_name: str
    gender: str | None = None
    disorder_type: str


class ReportRiskDistribution(BaseModel):
    төмен: int = 0
    орташа: int = 0
    жоғары: int = 0


class ReportWordSummary(BaseModel):
    word_id: int
    expected_word: str
    target_sound: str | None = None
    attempts: int
    average_accuracy: int
    best_accuracy: int
    latest_accuracy: int
    latest_recognized_word: str
    latest_risk_level: str


class ReportResultItem(BaseModel):
    analysis_id: int
    audio_id: int
    word_id: int
    expected_word: str
    recognized_word: str
    accuracy: int
    mistake_type: str
    risk_level: str
    recommendation: str
    attempt_number: str
    file_path: str
    created_at: datetime


class ChildGeneralReport(BaseModel):
    child: ReportChild
    total_audio: int
    total_analysis: int
    words_practiced: int
    average_accuracy: int
    best_accuracy: int | None
    latest_accuracy: int | None
    overall_risk_level: str
    risk_distribution: ReportRiskDistribution
    common_mistake_types: dict[str, int]
    problematic_sounds: list[str]
    strong_words: list[str]
    focus_words: list[str]
    word_summaries: list[ReportWordSummary]
    last_results: list[ReportResultItem]
    summary: str
    next_actions: list[str]
    generated_at: datetime
