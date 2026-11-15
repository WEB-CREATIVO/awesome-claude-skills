"""Carga de configuración: clinic.yaml (datos de la clínica) + variables de entorno (secretos)."""
from __future__ import annotations

import os
from dataclasses import dataclass, field
from datetime import timedelta
from pathlib import Path
from zoneinfo import ZoneInfo

import yaml

ROOT = Path(__file__).resolve().parent.parent


@dataclass
class Treatment:
    id: str
    name: str
    duration_min: int
    price: str = ""

    @property
    def duration(self) -> timedelta:
        return timedelta(minutes=self.duration_min)


@dataclass
class Clinic:
    name: str
    tz: ZoneInfo
    address: str
    phone: str
    emergency_phone: str
    website: str
    language: str
    opening_hours: dict
    closed_dates: set
    step: timedelta
    min_notice: timedelta
    max_days_ahead: int
    treatments: dict[str, Treatment]
    faqs: list[dict]
    emergency_keywords: list[str] = field(default_factory=list)


def load_clinic(path: str | Path | None = None) -> Clinic:
    path = Path(path or os.getenv("CLINIC_CONFIG", ROOT / "clinic.yaml"))
    raw = yaml.safe_load(path.read_text(encoding="utf-8"))
    booking = raw.get("booking", {})
    treatments = {t["id"]: Treatment(**t) for t in raw["treatments"]}
    return Clinic(
        name=raw["name"],
        tz=ZoneInfo(raw["timezone"]),
        address=raw.get("address", ""),
        phone=raw.get("phone", ""),
        emergency_phone=raw.get("emergency_phone", ""),
        website=raw.get("website", ""),
        language=raw.get("language", "es"),
        opening_hours=raw["opening_hours"],
        closed_dates={str(d) for d in raw.get("closed_dates", [])},
        step=timedelta(minutes=booking.get("slot_step_minutes", 15)),
        min_notice=timedelta(hours=booking.get("min_notice_hours", 2)),
        max_days_ahead=booking.get("max_days_ahead", 60),
        treatments=treatments,
        faqs=raw.get("faqs", []),
        emergency_keywords=raw.get("emergency_keywords", []),
    )


class Env:
    """Secretos y ajustes de despliegue (se leen en el momento de uso, no al importar)."""

    @staticmethod
    def get(name: str, default: str | None = None) -> str | None:
        return os.getenv(name, default)

    @staticmethod
    def require(name: str) -> str:
        val = os.getenv(name)
        if not val:
            raise RuntimeError(f"Falta la variable de entorno {name}")
        return val
