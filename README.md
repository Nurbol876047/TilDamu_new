# AI Логопедическая платформа анализа речи детей

Готовая demo-система:

`Ребенок говорит слово -> аудио загружается -> AI анализирует -> выводится результат -> логопед видит ошибки`

## Состав

- `backend/` — FastAPI, SQLAlchemy 2.0, PostgreSQL, Alembic, Pydantic v2, mock STT/AI.
- `frontend/` — архивный PHP-фронт TDM2.2 из `TDM2.2 frontend implemented.zip`.
- `backend/docker-compose.yml` — backend, PostgreSQL и Redis cache.

## Запуск backend

```bash
cd backend
cp .env.example .env
docker compose up --build
```

Swagger: `http://localhost:8000/docs`

## Запуск frontend

```bash
cd frontend
php -S 0.0.0.0:5173 router.php
```

Frontend: `http://localhost:5173`

Прямые страницы PHP-фронта:

- `http://localhost:5173/`
- `http://localhost:5173/diagnosis`
- `http://localhost:5173/dataset`
- `http://localhost:5173/results`

Страница `frontend/app/Views/pages/dataset.php` подключена к FastAPI backend:

- создает ребенка через `POST /api/children`;
- создает или находит слово через `POST /api/words/ensure`;
- отправляет запись микрофона через `POST /api/audio/upload`;
- после серии открывает общий отчет `GET /api/reports/child/{id}/html`.

Страница `frontend/app/Views/pages/dataset_history.php` показывает все записи датасета:

- группирует аудио по ребенку/ID;
- раскрывает все попытки внутри блока владельца;
- дает прослушать файл и удалить неудачную запись через `DELETE /api/audio/{audio_id}`.

Подробная отметка по backend-файлам: `BACKEND_CHANGES.md`.

## Seed-данные

4 жас: `мама`, `ата`, `апа`, `су`, `доп`

5 жас: `кітап`, `бала`, `мектеп`, `алма`, `қала`

6 жас: `дәрігер`, `қарындаш`, `ойыншық`, `көбелек`, `жаңбыр`

## Mock AI

Сейчас `speech_to_text_service.py` возвращает `мана`.

`analysis_service.py` сравнивает ожидаемое слово с распознанным, генерирует:

- `recognized_word`
- `accuracy`
- `mistake_type`
- `risk_level`
- `recommendation`

Логика риска:

- `>85` — `төмен`
- `60-85` — `орташа`
- `<60` — `жоғары`
