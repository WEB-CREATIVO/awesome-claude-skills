"""Cálculo de huecos libres. Función pura: sin red, fácil de testear."""
from __future__ import annotations

from datetime import date, datetime, time, timedelta
from zoneinfo import ZoneInfo

WEEKDAYS = ["mon", "tue", "wed", "thu", "fri", "sat", "sun"]


def _hm(s: str) -> time:
    h, m = s.split(":")
    return time(int(h), int(m))


def compute_slots(
    *,
    opening_hours: dict,
    closed_dates: set,
    tz: ZoneInfo,
    day_from: date,
    day_to: date,
    duration: timedelta,
    busy: list[tuple[datetime, datetime]],
    now: datetime,
    step: timedelta = timedelta(minutes=15),
    min_notice: timedelta = timedelta(hours=2),
) -> list[datetime]:
    """Devuelve las horas de inicio (tz-aware) donde cabe una cita de `duration`."""
    slots: list[datetime] = []
    earliest = now + min_notice
    day = day_from
    while day <= day_to:
        if day.isoformat() not in closed_dates:
            for start_s, end_s in opening_hours.get(WEEKDAYS[day.weekday()], []):
                t = datetime.combine(day, _hm(start_s), tzinfo=tz)
                window_end = datetime.combine(day, _hm(end_s), tzinfo=tz)
                while t + duration <= window_end:
                    end = t + duration
                    if t >= earliest and not any(t < b_end and end > b_start for b_start, b_end in busy):
                        slots.append(t)
                    t += step
        day += timedelta(days=1)
    return slots


def spread(items: list, n: int) -> list:
    """Elige hasta n elementos repartidos a lo largo de la lista (mañana y tarde, no solo los primeros)."""
    if len(items) <= n:
        return items
    idx = sorted({round(i * (len(items) - 1) / (n - 1)) for i in range(n)})
    return [items[i] for i in idx]
