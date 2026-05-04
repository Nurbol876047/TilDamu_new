from fastapi import APIRouter, Depends, Query, status
from sqlalchemy import select
from sqlalchemy.orm import Session

from app.core.cache import cache
from app.core.database import get_db
from app.models.word import Word
from app.schemas.word import WordCreate, WordRead

router = APIRouter(prefix="/words", tags=["words"])


@router.get("", response_model=list[WordRead])
def list_words(
    age_group: int | None = Query(default=None, ge=2, le=16),
    db: Session = Depends(get_db),
) -> list[WordRead]:
    cache_key = cache.key("words", f"age:{age_group}" if age_group is not None else "all")
    cached = cache.get_json(cache_key)
    if cached is not None:
        return [WordRead.model_validate(item) for item in cached]

    statement = select(Word)
    if age_group is not None:
        statement = statement.where(Word.age_group == age_group)
    statement = statement.order_by(Word.age_group.asc(), Word.id.asc())
    words = [WordRead.model_validate(word) for word in db.scalars(statement)]
    cache.set_json(cache_key, [word.model_dump(mode="json") for word in words])
    return words


@router.post("/ensure", response_model=WordRead, status_code=status.HTTP_201_CREATED)
def ensure_word(payload: WordCreate, db: Session = Depends(get_db)) -> WordRead:
    normalized_text = payload.text.strip().lower()
    existing = db.scalar(
        select(Word).where(
            Word.text == normalized_text,
            Word.age_group == payload.age_group,
        )
    )
    if existing:
        return WordRead.model_validate(existing)

    word = Word(
        text=normalized_text,
        age_group=payload.age_group,
        target_sound=payload.target_sound,
    )
    db.add(word)
    db.commit()
    db.refresh(word)
    cache.delete_pattern(cache.key("words", "*"))
    return WordRead.model_validate(word)
