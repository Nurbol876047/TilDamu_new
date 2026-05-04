from sqlalchemy import select
from sqlalchemy.orm import Session

from app.models.word import Word


SEED_WORDS = [
    {"text": "мама", "age_group": 4, "target_sound": "м"},
    {"text": "ата", "age_group": 4, "target_sound": "т"},
    {"text": "апа", "age_group": 4, "target_sound": "п"},
    {"text": "су", "age_group": 4, "target_sound": "с"},
    {"text": "доп", "age_group": 4, "target_sound": "д"},
    {"text": "кітап", "age_group": 5, "target_sound": "к"},
    {"text": "бала", "age_group": 5, "target_sound": "л"},
    {"text": "мектеп", "age_group": 5, "target_sound": "м"},
    {"text": "алма", "age_group": 5, "target_sound": "л"},
    {"text": "қала", "age_group": 5, "target_sound": "қ"},
    {"text": "дәрігер", "age_group": 6, "target_sound": "р"},
    {"text": "қарындаш", "age_group": 6, "target_sound": "қ"},
    {"text": "ойыншық", "age_group": 6, "target_sound": "ш"},
    {"text": "көбелек", "age_group": 6, "target_sound": "к"},
    {"text": "жаңбыр", "age_group": 6, "target_sound": "ж"},
]


def seed_words(db: Session) -> None:
    for item in SEED_WORDS:
        exists = db.scalar(
            select(Word).where(
                Word.text == item["text"],
                Word.age_group == item["age_group"],
            )
        )
        if exists:
            continue
        db.add(Word(**item))
    db.commit()

