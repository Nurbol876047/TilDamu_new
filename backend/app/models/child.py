from __future__ import annotations

from datetime import datetime

from sqlalchemy import DateTime, Integer, String, func
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.core.database import Base


class Child(Base):
    __tablename__ = "children"

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True)
    full_name: Mapped[str] = mapped_column(String(160), nullable=False, index=True)
    age: Mapped[int] = mapped_column(Integer, nullable=False, index=True)
    parent_name: Mapped[str] = mapped_column(String(160), nullable=False)
    gender: Mapped[str | None] = mapped_column(String(20), nullable=True)
    disorder_type: Mapped[str] = mapped_column(String(120), nullable=False, index=True)
    created_at: Mapped[datetime] = mapped_column(
        DateTime(timezone=True),
        server_default=func.now(),
        nullable=False,
    )

    audio_records: Mapped[list["AudioRecord"]] = relationship(
        back_populates="child",
        cascade="all, delete-orphan",
    )
