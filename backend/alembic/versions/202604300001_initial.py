"""initial schema

Revision ID: 202604300001
Revises:
Create Date: 2026-04-30 00:00:00.000000
"""
from collections.abc import Sequence

from alembic import op
import sqlalchemy as sa


revision: str = "202604300001"
down_revision: str | None = None
branch_labels: str | Sequence[str] | None = None
depends_on: str | Sequence[str] | None = None


def upgrade() -> None:
    op.create_table(
        "children",
        sa.Column("id", sa.Integer(), nullable=False),
        sa.Column("full_name", sa.String(length=160), nullable=False),
        sa.Column("age", sa.Integer(), nullable=False),
        sa.Column("parent_name", sa.String(length=160), nullable=False),
        sa.Column("disorder_type", sa.String(length=120), nullable=False),
        sa.Column("created_at", sa.DateTime(timezone=True), server_default=sa.text("now()"), nullable=False),
        sa.PrimaryKeyConstraint("id"),
    )
    op.create_index(op.f("ix_children_age"), "children", ["age"], unique=False)
    op.create_index(op.f("ix_children_disorder_type"), "children", ["disorder_type"], unique=False)
    op.create_index(op.f("ix_children_full_name"), "children", ["full_name"], unique=False)
    op.create_index(op.f("ix_children_id"), "children", ["id"], unique=False)

    op.create_table(
        "words",
        sa.Column("id", sa.Integer(), nullable=False),
        sa.Column("text", sa.String(length=100), nullable=False),
        sa.Column("age_group", sa.Integer(), nullable=False),
        sa.Column("target_sound", sa.String(length=20), nullable=True),
        sa.PrimaryKeyConstraint("id"),
        sa.UniqueConstraint("text", "age_group", name="uq_words_text_age_group"),
    )
    op.create_index(op.f("ix_words_age_group"), "words", ["age_group"], unique=False)
    op.create_index(op.f("ix_words_id"), "words", ["id"], unique=False)
    op.create_index(op.f("ix_words_text"), "words", ["text"], unique=False)

    op.create_table(
        "audio_records",
        sa.Column("id", sa.Integer(), nullable=False),
        sa.Column("child_id", sa.Integer(), nullable=False),
        sa.Column("word_id", sa.Integer(), nullable=False),
        sa.Column("attempt_number", sa.String(length=12), nullable=False),
        sa.Column("file_path", sa.String(length=500), nullable=False),
        sa.Column("duration", sa.Float(), nullable=True),
        sa.Column("created_at", sa.DateTime(timezone=True), server_default=sa.text("now()"), nullable=False),
        sa.ForeignKeyConstraint(["child_id"], ["children.id"], ondelete="CASCADE"),
        sa.ForeignKeyConstraint(["word_id"], ["words.id"], ondelete="RESTRICT"),
        sa.PrimaryKeyConstraint("id"),
    )
    op.create_index(op.f("ix_audio_records_child_id"), "audio_records", ["child_id"], unique=False)
    op.create_index(op.f("ix_audio_records_id"), "audio_records", ["id"], unique=False)
    op.create_index(op.f("ix_audio_records_word_id"), "audio_records", ["word_id"], unique=False)

    op.create_table(
        "analysis_results",
        sa.Column("id", sa.Integer(), nullable=False),
        sa.Column("audio_id", sa.Integer(), nullable=False),
        sa.Column("expected_word", sa.String(length=100), nullable=False),
        sa.Column("recognized_word", sa.String(length=100), nullable=False),
        sa.Column("accuracy", sa.Integer(), nullable=False),
        sa.Column("mistake_type", sa.String(length=120), nullable=False),
        sa.Column("risk_level", sa.String(length=40), nullable=False),
        sa.Column("recommendation", sa.Text(), nullable=False),
        sa.Column("created_at", sa.DateTime(timezone=True), server_default=sa.text("now()"), nullable=False),
        sa.ForeignKeyConstraint(["audio_id"], ["audio_records.id"], ondelete="CASCADE"),
        sa.PrimaryKeyConstraint("id"),
        sa.UniqueConstraint("audio_id"),
    )
    op.create_index(op.f("ix_analysis_results_accuracy"), "analysis_results", ["accuracy"], unique=False)
    op.create_index(op.f("ix_analysis_results_audio_id"), "analysis_results", ["audio_id"], unique=False)
    op.create_index(op.f("ix_analysis_results_id"), "analysis_results", ["id"], unique=False)
    op.create_index(op.f("ix_analysis_results_risk_level"), "analysis_results", ["risk_level"], unique=False)


def downgrade() -> None:
    op.drop_index(op.f("ix_analysis_results_risk_level"), table_name="analysis_results")
    op.drop_index(op.f("ix_analysis_results_id"), table_name="analysis_results")
    op.drop_index(op.f("ix_analysis_results_audio_id"), table_name="analysis_results")
    op.drop_index(op.f("ix_analysis_results_accuracy"), table_name="analysis_results")
    op.drop_table("analysis_results")
    op.drop_index(op.f("ix_audio_records_word_id"), table_name="audio_records")
    op.drop_index(op.f("ix_audio_records_id"), table_name="audio_records")
    op.drop_index(op.f("ix_audio_records_child_id"), table_name="audio_records")
    op.drop_table("audio_records")
    op.drop_index(op.f("ix_words_text"), table_name="words")
    op.drop_index(op.f("ix_words_id"), table_name="words")
    op.drop_index(op.f("ix_words_age_group"), table_name="words")
    op.drop_table("words")
    op.drop_index(op.f("ix_children_id"), table_name="children")
    op.drop_index(op.f("ix_children_full_name"), table_name="children")
    op.drop_index(op.f("ix_children_disorder_type"), table_name="children")
    op.drop_index(op.f("ix_children_age"), table_name="children")
    op.drop_table("children")

