from fastapi import APIRouter, Depends
from sqlalchemy import func, select
from sqlalchemy.orm import Session

from app.core.cache import cache
from app.core.database import get_db
from app.models.analysis import AnalysisResult
from app.models.audio import AudioRecord
from app.models.child import Child
from app.schemas.dashboard import DashboardLastResult, DashboardRead

router = APIRouter(prefix="/dashboard", tags=["dashboard"])


@router.get("", response_model=DashboardRead)
def get_dashboard(db: Session = Depends(get_db)) -> DashboardRead:
    cache_key = cache.key("dashboard")
    cached = cache.get_json(cache_key)
    if cached is not None:
        return DashboardRead.model_validate(cached)

    total_children = db.scalar(select(func.count(Child.id))) or 0
    total_audio = db.scalar(select(func.count(AudioRecord.id))) or 0
    total_analysis = db.scalar(select(func.count(AnalysisResult.id))) or 0
    avg_accuracy_raw = db.scalar(select(func.avg(AnalysisResult.accuracy)))
    avg_accuracy = round(float(avg_accuracy_raw or 0))

    disorder_rows = db.execute(
        select(Child.disorder_type, func.count(Child.id))
        .group_by(Child.disorder_type)
        .order_by(func.count(Child.id).desc())
    ).all()
    disorders = {name: count for name, count in disorder_rows}

    last_rows = db.execute(
        select(AnalysisResult, Child)
        .join(AudioRecord, AnalysisResult.audio_id == AudioRecord.id)
        .join(Child, AudioRecord.child_id == Child.id)
        .order_by(AnalysisResult.created_at.desc())
        .limit(8)
    ).all()

    last_results = [
        DashboardLastResult(
            id=result.id,
            child_name=child.full_name,
            expected_word=result.expected_word,
            recognized_word=result.recognized_word,
            accuracy=result.accuracy,
            risk_level=result.risk_level,
            created_at=result.created_at,
        )
        for result, child in last_rows
    ]

    dashboard = DashboardRead(
        total_children=total_children,
        total_audio=total_audio,
        total_analysis=total_analysis,
        avg_accuracy=avg_accuracy,
        disorders=disorders,
        last_results=last_results,
    )
    cache.set_json(cache_key, dashboard.model_dump(mode="json"))
    return dashboard
