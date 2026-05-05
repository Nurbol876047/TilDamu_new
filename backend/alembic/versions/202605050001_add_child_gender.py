"""add child gender

Revision ID: 202605050001
Revises: 202604300001
Create Date: 2026-05-05 14:30:00.000000
"""
from collections.abc import Sequence

from alembic import op
import sqlalchemy as sa


revision: str = "202605050001"
down_revision: str | None = "202604300001"
branch_labels: str | Sequence[str] | None = None
depends_on: str | Sequence[str] | None = None


def upgrade() -> None:
    op.add_column("children", sa.Column("gender", sa.String(length=20), nullable=True))


def downgrade() -> None:
    op.drop_column("children", "gender")
