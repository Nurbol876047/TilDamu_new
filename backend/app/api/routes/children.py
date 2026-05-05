from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy import select
from sqlalchemy.orm import Session

from app.core.cache import cache
from app.core.database import get_db
from app.models.child import Child
from app.schemas.child import ChildCreate, ChildRead

router = APIRouter(prefix="/children", tags=["children"])


def _clean_child_payload(payload: ChildCreate) -> dict[str, str | int | None]:
    data = payload.model_dump()
    data["full_name"] = data["full_name"].strip()
    data["parent_name"] = data["parent_name"].strip()
    data["gender"] = data["gender"].strip() if data.get("gender") else None
    data["disorder_type"] = data["disorder_type"].strip()
    return data


@router.post("", response_model=ChildRead, status_code=status.HTTP_201_CREATED)
def create_child(payload: ChildCreate, db: Session = Depends(get_db)) -> Child:
    child = Child(**_clean_child_payload(payload))
    db.add(child)
    db.commit()
    db.refresh(child)
    cache.delete(cache.key("dashboard"))
    cache.delete_pattern(cache.key("children", "*"))
    cache.delete_pattern(cache.key("report", "child", "*"))
    return child


@router.post("/resolve", response_model=ChildRead)
def resolve_child(payload: ChildCreate, db: Session = Depends(get_db)) -> Child:
    data = _clean_child_payload(payload)
    existing = db.scalar(
        select(Child)
        .where(
            Child.full_name == data["full_name"],
            Child.age == data["age"],
            Child.parent_name == data["parent_name"],
            Child.gender == data["gender"],
            Child.disorder_type == data["disorder_type"],
        )
        .order_by(Child.created_at.desc())
    )
    if existing:
        return existing

    child = Child(**data)
    db.add(child)
    db.commit()
    db.refresh(child)
    cache.delete(cache.key("dashboard"))
    cache.delete_pattern(cache.key("children", "*"))
    return child


@router.get("", response_model=list[ChildRead])
def list_children(db: Session = Depends(get_db)) -> list[ChildRead]:
    cache_key = cache.key("children", "all")
    cached = cache.get_json(cache_key)
    if cached is not None:
        return [ChildRead.model_validate(item) for item in cached]

    children = [ChildRead.model_validate(child) for child in db.scalars(select(Child).order_by(Child.created_at.desc()))]
    cache.set_json(cache_key, [child.model_dump(mode="json") for child in children])
    return children


@router.get("/{child_id}", response_model=ChildRead)
def get_child(child_id: int, db: Session = Depends(get_db)) -> ChildRead:
    cache_key = cache.key("children", child_id)
    cached = cache.get_json(cache_key)
    if cached is not None:
        return ChildRead.model_validate(cached)

    child = db.get(Child, child_id)
    if not child:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Child not found")
    cache.set_json(cache_key, ChildRead.model_validate(child).model_dump(mode="json"))
    return ChildRead.model_validate(child)
