from datetime import datetime

from pydantic import BaseModel


class DashboardLastResult(BaseModel):
    id: int
    child_name: str
    expected_word: str
    recognized_word: str
    accuracy: int
    risk_level: str
    created_at: datetime


class DashboardRead(BaseModel):
    total_children: int
    total_audio: int
    total_analysis: int
    avg_accuracy: int
    disorders: dict[str, int]
    last_results: list[DashboardLastResult]

