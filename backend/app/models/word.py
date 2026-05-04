from __future__ import annotations

from sqlalchemy import Integer, String, UniqueConstraint
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.core.database import Base


class Word(Base):
    __tablename__ = "words"
    __table_args__ = (
        UniqueConstraint("text", "age_group", name="uq_words_text_age_group"),
    )

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True)
    text: Mapped[str] = mapped_column(String(100), nullable=False, index=True)
    age_group: Mapped[int] = mapped_column(Integer, nullable=False, index=True)
    target_sound: Mapped[str | None] = mapped_column(String(20), nullable=True)

    audio_records: Mapped[list["AudioRecord"]] = relationship(back_populates="word")

