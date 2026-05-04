# Backend: AI Логопедическая платформа

FastAPI API демонстрирует цепочку:

`Бала -> Сөз -> Аудио -> Speech-to-Text -> AI анализ -> Нәтиже -> Логопед`

## Запуск через Docker

```bash
cd backend
cp .env.example .env
docker compose up --build
```

API будет доступен на `http://localhost:8000`.

Swagger: `http://localhost:8000/docs`

## Локальный запуск без Docker

Нужен PostgreSQL и Python 3.11+.

```bash
cd backend
cp .env.example .env
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt
alembic upgrade head
uvicorn app.main:app --reload
```

## Основные endpoint'ы

```text
POST /api/children
GET  /api/children
GET  /api/children/{id}

GET  /api/words
GET  /api/words?age_group=4

POST /api/audio/upload
GET  /api/audio/history
GET  /api/audio/child/{child_id}
GET  /api/audio/{audio_id}/file
GET  /api/audio/{audio_id}/cached-result
GET  /api/audio/child/{child_id}/latest-cached-result
DELETE /api/audio/{audio_id}
GET  /api/analysis/child/{child_id}
GET  /api/reports/child/{child_id}
GET  /api/reports/child/{child_id}/html
GET  /api/dashboard
GET  /api/cache/status
DELETE /api/cache
```

## Быстрый тест

Создать ребенка:

```bash
curl -X POST http://localhost:8000/api/children \
  -H "Content-Type: application/json" \
  -d '{"full_name":"Айша Нурланқызы","age":4,"parent_name":"Нурлан","disorder_type":"дислалия"}'
```

Получить seed-слова:

```bash
curl http://localhost:8000/api/words?age_group=4
```

Загрузить аудио:

```bash
curl -X POST http://localhost:8000/api/audio/upload \
  -F child_id=1 \
  -F word_id=1 \
  -F attempt_number=x1 \
  -F file=@sample.wav
```

Mock STT временно возвращает `мана`, а mock AI формирует accuracy, тип ошибки, риск и рекомендацию. Для реального AI подготовлены провайдеры `openai_whisper` и `google` в `app/services/speech_to_text_service.py`.

## Поэтапная обработка голоса

`POST /api/audio/upload` делает backend-цепочку без изменений frontend:

1. принимает `.ogg`, `.mp3`, `.wav`, `.m4a`;
2. сохраняет файл в `uploads/audio/`;
3. берет ожидаемое слово и словарь возраста из таблицы `words`;
4. запускает STT provider, сейчас `mock`;
5. сверяет распознанный текст со словарем БД;
6. сохраняет `AudioRecord` и `AnalysisResult`;
7. возвращает `audio`, `analysis` и служебные `recognition_steps`.

Сохраненные голоса можно получить:

```bash
curl http://localhost:8000/api/audio/history
curl http://localhost:8000/api/audio/child/1
curl -o voice.wav http://localhost:8000/api/audio/1/file
curl -X DELETE http://localhost:8000/api/audio/1
```

## Обновление истории датасета

Добавлена удобная backend-история аудио для сборки датасета:

- `GET /api/audio/history` возвращает все записи вместе с владельцем, словом, анализом, ссылкой на файл и размером аудио;
- `DELETE /api/audio/{audio_id}` удаляет неудачную запись, связанный анализ, аудиофайл на диске и кеш;
- frontend-страница `dataset-history.php` группирует записи по ребенку/ID, чтобы несколько попыток одного участника раскрывались одним блоком.

## Redis cache

Backend использует Redis для быстрых повторных ответов:

- `GET /api/words` и `GET /api/words?age_group=4`;
- `GET /api/children`;
- `GET /api/dashboard`;
- `GET /api/analysis/child/{child_id}`;
- metadata последнего `POST /api/audio/upload`.

Аудиофайлы остаются на диске в `uploads/audio/`, а в Redis хранится JSON: путь файла, результат STT, dictionary match, accuracy и рекомендации. Так Redis не забивается тяжелыми аудио, но логопедический результат открывается быстрее.

Проверить кэш:

```bash
curl http://localhost:8000/api/cache/status
curl http://localhost:8000/api/audio/1/cached-result
curl http://localhost:8000/api/audio/child/1/latest-cached-result
```

Очистить только ключи приложения:

```bash
curl -X DELETE http://localhost:8000/api/cache
```

## Общий отчет после единичных анализов

После каждой записи `POST /api/audio/upload` сохраняет единичный анализ. Общий отчет собирается отдельно по всем попыткам ребенка:

```bash
curl http://localhost:8000/api/reports/child/1
```

Открыть человекочитаемый HTML-отчет в браузере:

```text
http://localhost:8000/api/reports/child/1/html
```

В отчете есть:

- средняя точность по всем записям;
- общий risk level;
- распределение рисков;
- частые типы ошибок;
- проблемные звуки;
- сильные и фокусные слова;
- следующие действия для логопеда.
