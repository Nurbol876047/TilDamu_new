# TilDamu.kz — PHP 8.3 MVC (Production)

## AI-платформа диагностики речевых нарушений у детей

Чистый PHP 8.3 MVC-проект с реальной базой данных MariaDB. **Без демо-данных** — все показатели, счётчики и графики работают в реальном времени из БД.

## База данных (MariaDB / MySQL)

Таблицы: `children`, `assessments`, `exercise_templates`, `exercise_progress`, `chat_messages`, `therapists`

## API-эндпоинты

| Метод | URL | Описание |
|-------|-----|----------|
| POST | `/api/diagnosis/start` | Начать сессию диагностики |
| POST | `/api/diagnosis/analyze` | Отправить аудио на анализ |
| POST | `/api/diagnosis/complete` | Завершить и сохранить assessment |
| POST | `/api/exercises/complete` | Отметить упражнение выполненным |
| POST | `/api/parent-assistant/send` | Отправить вопрос AI-ассистенту |
| POST | `/api/children/notes` | Сохранить заметки логопеда |

## Что изменено (убраны ВСЕ демо-данные)

- **Главная**: счётчики `5000+` и `95%` → реальные `totalChildren` и `totalAssessments` из БД
- **Результаты**: hardcoded mock → реальный assessment из БД; пустой стейт если данных нет
- **Панель логопеда**: fake пациенты → `byChildSummary()` из БД; реальные графики `progressSeries()`; заметки сохраняются в `children.notes`; AI Insights на основе реального прогресса
- **Упражнения**: hardcoded → `exercise_templates` из БД; достижения по реальному `exercise_progress`
- **AssessmentBuilder**: фейковый fallback (72 балла) → пустой отчёт с предложением повторить тест
- **Аналитика**: графики Chart.js показывают пустой стейт если данных нет

## Установка

```bash
# 1. Импортируйте БД
mysql -u root -p tildamuk_Enterprise-database < app/Database/schema.sql

# 2. Настройте .env (БД, AI ключ)

# 3. Запустите
php -S localhost:8000
```

## © 2026 TilDamu.kz
