from collections.abc import AsyncIterator
from contextlib import asynccontextmanager

from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from app import models  # noqa: F401
from app.api.routes import analysis, audio, cache, children, dashboard, reports, words
from app.core.config import settings
from app.core.database import Base, SessionLocal, engine
from app.data.seed_words import seed_words


@asynccontextmanager
async def lifespan(app: FastAPI) -> AsyncIterator[None]:
    settings.upload_dir.mkdir(parents=True, exist_ok=True)
    Base.metadata.create_all(bind=engine)
    with SessionLocal() as db:
        seed_words(db)
    yield


app = FastAPI(
    title=settings.app_name,
    description="Demo API: Бала -> Сөз -> Аудио -> Speech-to-Text -> AI анализ -> Нәтиже -> Логопед",
    version="1.0.0",
    lifespan=lifespan,
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.cors_origin_list,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(children.router, prefix=settings.api_prefix)
app.include_router(words.router, prefix=settings.api_prefix)
app.include_router(audio.router, prefix=settings.api_prefix)
app.include_router(analysis.router, prefix=settings.api_prefix)
app.include_router(dashboard.router, prefix=settings.api_prefix)
app.include_router(cache.router, prefix=settings.api_prefix)
app.include_router(reports.router, prefix=settings.api_prefix)


@app.get("/health", tags=["system"])
def health() -> dict[str, str]:
    return {"status": "ok"}


@app.get("/", tags=["system"])
def root() -> dict[str, str]:
    return {
        "status": "running",
        "frontend": "http://localhost:5173",
        "swagger": "http://localhost:8000/docs",
        "health": "http://localhost:8000/health",
        "cache": "http://localhost:8000/api/cache/status",
    }
