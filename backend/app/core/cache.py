import json
from typing import Any

from fastapi.encoders import jsonable_encoder
from redis import Redis
from redis.exceptions import RedisError

from app.core.config import settings


class CacheService:
    def __init__(self) -> None:
        self._client: Redis | None = None

    @property
    def client(self) -> Redis:
        if self._client is None:
            self._client = Redis.from_url(settings.redis_url, decode_responses=True)
        return self._client

    def key(self, *parts: object) -> str:
        clean_parts = [str(part).strip(":") for part in parts if part is not None]
        return ":".join([settings.cache_key_prefix, *clean_parts])

    def ping(self) -> bool:
        try:
            return bool(self.client.ping())
        except RedisError:
            return False

    def get_json(self, key: str) -> Any | None:
        try:
            value = self.client.get(key)
        except RedisError:
            return None
        if value is None:
            return None
        try:
            return json.loads(value)
        except json.JSONDecodeError:
            return None

    def set_json(self, key: str, value: Any, ttl_seconds: int | None = None) -> None:
        ttl = ttl_seconds or settings.cache_ttl_seconds
        payload = json.dumps(jsonable_encoder(value), ensure_ascii=False)
        try:
            self.client.setex(key, ttl, payload)
        except RedisError:
            return

    def delete(self, *keys: str) -> None:
        if not keys:
            return
        try:
            self.client.delete(*keys)
        except RedisError:
            return

    def delete_pattern(self, pattern: str) -> int:
        deleted = 0
        try:
            keys = list(self.client.scan_iter(pattern))
            if keys:
                deleted = int(self.client.delete(*keys))
        except RedisError:
            return 0
        return deleted

    def dbsize(self) -> int:
        try:
            return int(self.client.dbsize())
        except RedisError:
            return 0


cache = CacheService()

