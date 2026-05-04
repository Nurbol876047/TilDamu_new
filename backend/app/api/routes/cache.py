from fastapi import APIRouter

from app.core.cache import cache
from app.core.config import settings

router = APIRouter(prefix="/cache", tags=["cache"])


@router.get("/status")
def get_cache_status() -> dict[str, int | str | bool]:
    return {
        "enabled": True,
        "connected": cache.ping(),
        "backend": "redis",
        "key_prefix": settings.cache_key_prefix,
        "default_ttl_seconds": settings.cache_ttl_seconds,
        "audio_cache_ttl_seconds": settings.audio_cache_ttl_seconds,
        "redis_dbsize": cache.dbsize(),
    }


@router.delete("")
def clear_app_cache() -> dict[str, int | str]:
    deleted = cache.delete_pattern(cache.key("*"))
    return {
        "status": "cleared",
        "deleted_keys": deleted,
        "pattern": cache.key("*"),
    }

