from pydantic import BaseModel, ConfigDict, Field


class WordBase(BaseModel):
    text: str = Field(min_length=1, max_length=100, examples=["мама"])
    age_group: int = Field(ge=2, le=16, examples=[4])
    target_sound: str | None = Field(default=None, max_length=20, examples=["м"])


class WordCreate(WordBase):
    pass


class WordRead(WordBase):
    id: int

    model_config = ConfigDict(from_attributes=True)
