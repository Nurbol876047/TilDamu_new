from datetime import datetime

from pydantic import BaseModel, ConfigDict, Field


class ChildBase(BaseModel):
    full_name: str = Field(min_length=2, max_length=160, examples=["Айша Нурланқызы"])
    age: int = Field(ge=2, le=16, examples=[5])
    parent_name: str = Field(min_length=1, max_length=160, examples=["101"])
    gender: str | None = Field(default=None, max_length=20, examples=["female"])
    disorder_type: str = Field(min_length=2, max_length=120, examples=["дислалия"])


class ChildCreate(ChildBase):
    pass


class ChildRead(ChildBase):
    id: int
    created_at: datetime

    model_config = ConfigDict(from_attributes=True)
