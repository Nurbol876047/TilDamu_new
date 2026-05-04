from __future__ import annotations

from datetime import datetime

from sqlalchemy import DateTime, Float, ForeignKey, Integer, String, func
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.core.database import Base


class AudioRecord(Base):
    __tablename__ = "audio_records"

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True)
    child_id: Mapped[int] = mapped_column(ForeignKey("children.id", ondelete="CASCADE"), nullable=False, index=True)
    word_id: Mapped[int] = mapped_column(ForeignKey("words.id", ondelete="RESTRICT"), nullable=False, index=True)
    attempt_number: Mapped[str] = mapped_column(String(12), nullable=False)
    file_path: Mapped[str] = mapped_column(String(500), nullable=False)
    duration: Mapped[float | None] = mapped_column(Float, nullable=True)
    created_at: Mapped[datetime] = mapped_column(
        DateTime(timezone=True),
        server_default=func.now(),
        nullable=False,
    )

    child: Mapped["Child"] = relationship(back_populates="audio_records")
    word: Mapped["Word"] = relationship(back_populates="audio_records")
    analysis_result: Mapped["AnalysisResult | None"] = relationship(
        back_populates="audio",
        cascade="all, delete-orphan",
        uselist=False,
    )

