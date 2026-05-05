from collections import Counter, defaultdict
from datetime import datetime, timezone
from html import escape

from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.responses import HTMLResponse
from sqlalchemy import select
from sqlalchemy.orm import Session

from app.core.cache import cache
from app.core.database import get_db
from app.models.analysis import AnalysisResult
from app.models.audio import AudioRecord
from app.models.child import Child
from app.models.word import Word
from app.schemas.report import (
    ChildGeneralReport,
    ReportChild,
    ReportResultItem,
    ReportRiskDistribution,
    ReportWordSummary,
)

router = APIRouter(prefix="/reports", tags=["reports"])


@router.get("/child/{child_id}", response_model=ChildGeneralReport)
def get_child_general_report(child_id: int, db: Session = Depends(get_db)) -> ChildGeneralReport:
    cache_key = cache.key("report", "child", child_id)
    cached = cache.get_json(cache_key)
    if cached is not None:
        return ChildGeneralReport.model_validate(cached)

    report = _build_child_report(child_id, db)
    cache.set_json(cache_key, report.model_dump(mode="json"))
    return report


@router.get("/child/{child_id}/html", response_class=HTMLResponse)
def get_child_general_report_html(child_id: int, db: Session = Depends(get_db)) -> HTMLResponse:
    report = get_child_general_report(child_id, db)
    return HTMLResponse(_render_report_html(report))


def _build_child_report(child_id: int, db: Session) -> ChildGeneralReport:
    child = db.get(Child, child_id)
    if not child:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Child not found")

    rows = db.execute(
        select(AnalysisResult, AudioRecord, Word)
        .join(AudioRecord, AnalysisResult.audio_id == AudioRecord.id)
        .join(Word, AudioRecord.word_id == Word.id)
        .where(AudioRecord.child_id == child_id)
        .order_by(AnalysisResult.created_at.asc())
    ).all()

    results = [
        ReportResultItem(
            analysis_id=analysis.id,
            audio_id=analysis.audio_id,
            word_id=audio.word_id,
            expected_word=analysis.expected_word,
            recognized_word=analysis.recognized_word,
            accuracy=analysis.accuracy,
            mistake_type=analysis.mistake_type,
            risk_level=analysis.risk_level,
            recommendation=analysis.recommendation,
            attempt_number=audio.attempt_number,
            file_path=audio.file_path,
            created_at=analysis.created_at,
        )
        for analysis, audio, _word in rows
    ]

    accuracies = [item.accuracy for item in results]
    average_accuracy = round(sum(accuracies) / len(accuracies)) if accuracies else 0
    risk_distribution = Counter(item.risk_level for item in results)
    mistake_types = Counter(item.mistake_type for item in results)

    results_by_analysis_id = {item.analysis_id: item for item in results}
    words_by_id: dict[int, list[tuple[ReportResultItem, Word]]] = defaultdict(list)
    for analysis, _audio, word in rows:
        item = results_by_analysis_id[analysis.id]
        words_by_id[word.id].append((item, word))

    word_summaries = []
    for word_id, word_rows in words_by_id.items():
        word_results = [item for item, _word in word_rows]
        word = word_rows[-1][1]
        word_accuracies = [item.accuracy for item in word_results]
        latest = word_results[-1]
        word_summaries.append(
            ReportWordSummary(
                word_id=word_id,
                expected_word=latest.expected_word,
                target_sound=word.target_sound,
                attempts=len(word_results),
                average_accuracy=round(sum(word_accuracies) / len(word_accuracies)),
                best_accuracy=max(word_accuracies),
                latest_accuracy=latest.accuracy,
                latest_recognized_word=latest.recognized_word,
                latest_risk_level=latest.risk_level,
            )
        )

    word_summaries.sort(key=lambda item: (item.average_accuracy, item.expected_word))
    problematic_sounds = _problematic_sounds(word_summaries)
    focus_words = [
        item.expected_word
        for item in word_summaries
        if item.average_accuracy < 85 or item.latest_risk_level == "жоғары"
    ][:5]
    strong_words = [
        item.expected_word
        for item in sorted(word_summaries, key=lambda item: item.best_accuracy, reverse=True)
        if item.best_accuracy >= 90
    ][:5]

    overall_risk = _overall_risk(average_accuracy, risk_distribution)
    next_actions = _next_actions(overall_risk, problematic_sounds, focus_words, average_accuracy, len(results))

    return ChildGeneralReport(
        child=ReportChild(
            id=child.id,
            full_name=child.full_name,
            age=child.age,
            parent_name=child.parent_name,
            gender=child.gender,
            disorder_type=child.disorder_type,
        ),
        total_audio=len(results),
        total_analysis=len(results),
        words_practiced=len(words_by_id),
        average_accuracy=average_accuracy,
        best_accuracy=max(accuracies) if accuracies else None,
        latest_accuracy=results[-1].accuracy if results else None,
        overall_risk_level=overall_risk,
        risk_distribution=ReportRiskDistribution(
            төмен=risk_distribution.get("төмен", 0),
            орташа=risk_distribution.get("орташа", 0),
            жоғары=risk_distribution.get("жоғары", 0),
        ),
        common_mistake_types=dict(mistake_types.most_common()),
        problematic_sounds=problematic_sounds,
        strong_words=strong_words,
        focus_words=focus_words,
        word_summaries=word_summaries,
        last_results=list(reversed(results[-10:])),
        summary=_summary(child.full_name, average_accuracy, overall_risk, len(results), focus_words),
        next_actions=next_actions,
        generated_at=datetime.now(timezone.utc),
    )


def _overall_risk(average_accuracy: int, risk_distribution: Counter[str]) -> str:
    if risk_distribution.get("жоғары", 0) >= 2 or average_accuracy < 60:
        return "жоғары"
    if risk_distribution.get("орташа", 0) >= 2 or average_accuracy < 85:
        return "орташа"
    return "төмен"


def _problematic_sounds(word_summaries: list[ReportWordSummary]) -> list[str]:
    sounds = []
    for item in word_summaries:
        if item.target_sound and (item.average_accuracy < 85 or item.latest_risk_level != "төмен"):
            sounds.append(item.target_sound.upper())
    return sorted(set(sounds))


def _next_actions(
    overall_risk: str,
    problematic_sounds: list[str],
    focus_words: list[str],
    average_accuracy: int,
    total_results: int,
) -> list[str]:
    if total_results == 0:
        return [
            "Записать минимум 3 слова для первичного отчета.",
            "Начать с коротких слов подходящей возрастной группы.",
        ]

    actions = [
        "Провести повторную запись 2-3 слов после короткой артикуляционной разминки.",
        "Сравнить новую попытку с текущим отчетом по accuracy и risk_level.",
    ]
    if focus_words:
        actions.insert(0, "Повторить фокусные слова: " + ", ".join(focus_words) + ".")
    if problematic_sounds:
        actions.insert(0, "Отработать проблемные звуки: " + ", ".join(problematic_sounds) + ".")
    if average_accuracy >= 90 and overall_risk == "төмен":
        actions.insert(0, "Закрепить успешные слова в фразах и коротких предложениях.")
    if overall_risk == "жоғары":
        actions.append("Рекомендована очная проверка логопедом и более длинная серия записей.")
    return actions


def _summary(
    child_name: str,
    average_accuracy: int,
    overall_risk: str,
    total_results: int,
    focus_words: list[str],
) -> str:
    if total_results == 0:
        return f"Для {child_name} пока нет записей. Общий отчет появится после первой серии аудио."
    focus = f" Фокус: {', '.join(focus_words)}." if focus_words else ""
    return (
        f"Общий отчет по {child_name}: обработано попыток {total_results}, "
        f"средняя точность {average_accuracy}%, общий риск {overall_risk}.{focus}"
    )


def _render_report_html(report: ChildGeneralReport) -> str:
    word_rows = "".join(
        f"<tr><td>{escape(item.expected_word)}</td><td>{item.attempts}</td><td>{item.average_accuracy}%</td>"
        f"<td>{item.best_accuracy}%</td><td>{escape(item.latest_risk_level)}</td></tr>"
        for item in report.word_summaries
    )
    actions = "".join(f"<li>{escape(action)}</li>" for action in report.next_actions)
    last_results = "".join(
        f"<li>{escape(item.expected_word)} -> {escape(item.recognized_word)}: {item.accuracy}% ({escape(item.risk_level)})</li>"
        for item in report.last_results
    )
    child_name = escape(report.child.full_name)
    child_disorder = escape(report.child.disorder_type)
    child_parent = escape(report.child.parent_name)
    child_gender = escape(report.child.gender or "-")
    summary = escape(report.summary)
    overall_risk = escape(report.overall_risk_level)
    return f"""
    <!doctype html>
    <html lang="ru">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Общий отчет - {child_name}</title>
      <style>
        body {{ font-family: Arial, sans-serif; margin: 32px; color: #1f2933; background: #f6f8f7; }}
        main {{ max-width: 980px; margin: 0 auto; display: grid; gap: 18px; }}
        section {{ background: white; border: 1px solid #dfe6e3; border-radius: 8px; padding: 20px; }}
        h1, h2 {{ margin: 0 0 10px; }}
        .grid {{ display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }}
        .metric {{ background: #eef7f4; border-radius: 8px; padding: 14px; }}
        .metric strong {{ display: block; font-size: 26px; }}
        table {{ width: 100%; border-collapse: collapse; }}
        th, td {{ text-align: left; border-bottom: 1px solid #e5ece9; padding: 10px; }}
        ul {{ margin: 0; padding-left: 22px; }}
      </style>
    </head>
    <body>
      <main>
        <section>
          <h1>Общий отчет: {child_name}</h1>
          <p>{summary}</p>
          <p>{report.child.age} жас · пол: {child_gender} · {child_disorder} · ID: {child_parent}</p>
        </section>
        <section class="grid">
          <div class="metric"><span>Попытки</span><strong>{report.total_audio}</strong></div>
          <div class="metric"><span>Слова</span><strong>{report.words_practiced}</strong></div>
          <div class="metric"><span>Accuracy</span><strong>{report.average_accuracy}%</strong></div>
          <div class="metric"><span>Risk</span><strong>{overall_risk}</strong></div>
        </section>
        <section>
          <h2>Следующие действия</h2>
          <ul>{actions}</ul>
        </section>
        <section>
          <h2>Слова</h2>
          <table>
            <thead><tr><th>Слово</th><th>Попытки</th><th>Средняя</th><th>Лучшая</th><th>Риск</th></tr></thead>
            <tbody>{word_rows}</tbody>
          </table>
        </section>
        <section>
          <h2>Последние результаты</h2>
          <ul>{last_results}</ul>
        </section>
      </main>
    </body>
    </html>
    """
