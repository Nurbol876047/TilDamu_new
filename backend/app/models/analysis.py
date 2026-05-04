from __future__ import annotations

from datetime import datetime

from sqlalchemy import DateTime, ForeignKey, Integer, String, Text, func
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.core.database import Base


class AnalysisResult(Base):
    __tablename__ = "analysis_results"

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True)
    audio_id: Mapped[int] = mapped_column(
        ForeignKey("audio_records.id", ondelete="CASCADE"),
        nullable=False,
        unique=True,
        index=True,
    )
    expected_word: Mapped[str] = mapped_column(String(100), nullable=False)
    recognized_word: Mapped[str] = mapped_column(String(100), nullable=False)
    accuracy: Mapped[int] = mapped_column(Integer, nullable=False, index=True)
    mistake_type: Mapped[str] = mapped_column(String(120), nullable=False)
    risk_level: Mapped[str] = mapped_column(String(40), nullable=False, index=True)
    recommendation: Mapped[str] = mapped_column(Text, nullable=False)
    created_at: Mapped[datetime] = mapped_column(
        DateTime(timezone=True),
        server_default=func.now(),
        nullable=False,
    )

    audio: Mapped["AudioRecord"] = relationship(back_populates="analysis_result")

