# Backend changes: dataset audio history

Дата: 2026-05-04

## Что изменилось

- Добавлена общая история аудиозаписей датасета через `GET /api/audio/history`.
- Добавлено удаление неудачной голосовой записи через `DELETE /api/audio/{audio_id}`.
- При удалении backend очищает строку `audio_records`, связанный `analysis_results`, файл из `backend/uploads/audio/` и Redis cache.
- История на frontend сгруппирована по ребенку/ID: один блок владельца раскрывает все его голосовые попытки.
- Старые аудиофайлы очищены; в `backend/uploads/audio/` оставлен только `.gitkeep`.

## Измененные файлы

- `backend/app/api/routes/audio.py` — endpoints истории и удаления аудио.
- `backend/app/schemas/audio.py` — схемы `AudioHistoryResponse`, `AudioHistoryItem`, `AudioDeleteResponse`.
- `backend/README.md` — документация новых endpoints.
- `frontend/app/Views/pages/dataset_history.php` — страница истории с группировкой и кнопкой удаления.
- `frontend/app/Views/pages/dataset.php` — ссылка на историю датасета.
- `frontend/app/Controllers/DiagnosisController.php` — action страницы истории.
- `frontend/routes/web.php` — маршруты `/dataset-history` и `/dataset-history.php`.
- `frontend/app/Views/layouts/header.php` — кнопка истории в верхнем меню.
- `frontend/app/Core/helpers.php` — переводы и активный ключ страницы истории.
