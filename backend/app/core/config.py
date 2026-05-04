from functools import lru_cache
from pathlib import Path
from typing import Literal

from pydantic import Field
from pydantic_settings import BaseSettings, SettingsConfigDict


class Settings(BaseSettings):
    app_name: str = "AI Logopedic Speech Analysis Platform"
    api_prefix: str = "/api"
    database_url: str = "postgresql+psycopg2://postgres:postgres@localhost:5432/speech_ai"
    redis_url: str = "redis://localhost:6379/0"
    cache_ttl_seconds: int = 300
    audio_cache_ttl_seconds: int = 3600
    cache_key_prefix: str = "ai-logoped"
    upload_dir: Path = Path("uploads/audio")
    max_upload_mb: int = 30
    stt_provider: Literal["mock", "openai_whisper", "google"] = "mock"
    cors_origins: str = Field(
        default="http://localhost:5173,http://127.0.0.1:5173,http://localhost:3000",
        description="Comma-separated frontend origins.",
    )

    model_config = SettingsConfigDict(
        env_file=".env",
        env_file_encoding="utf-8",
        case_sensitive=False,
        extra="ignore",
    )

    @property
    def cors_origin_list(self) -> list[str]:
        return [origin.strip() for origin in self.cors_origins.split(",") if origin.strip()]

    @property
    def max_upload_bytes(self) -> int:
        return self.max_upload_mb * 1024 * 1024


@lru_cache
def get_settings() -> Settings:
    return Settings()


settings = get_settings()
