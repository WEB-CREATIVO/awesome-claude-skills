"""Agenda. Dos implementaciones con la misma interfaz:
- GoogleCalendar: la real (cuenta de servicio + calendario compartido con ella).
- FakeCalendar: en memoria, para pruebas y modo DEMO sin credenciales.
"""
from __future__ import annotations

import json
import os
import uuid
from dataclasses import dataclass
from datetime import datetime
from typing import Protocol

SOURCE_TAG = "whatsapp-bot"


@dataclass
class Appointment:
    id: str
    start: datetime
    end: datetime
    treatment: str
    patient: str


class Calendar(Protocol):
    def busy(self, start: datetime, end: datetime) -> list[tuple[datetime, datetime]]: ...
    def create(self, *, start: datetime, end: datetime, treatment: str, patient: str, phone: str) -> Appointment: ...
    def list_for_phone(self, phone: str, since: datetime) -> list[Appointment]: ...
    def cancel(self, event_id: str, phone: str) -> bool: ...


class FakeCalendar:
    def __init__(self) -> None:
        self.events: dict[str, dict] = {}

    def busy(self, start, end):
        return [(e["start"], e["end"]) for e in self.events.values() if e["start"] < end and e["end"] > start]

    def create(self, *, start, end, treatment, patient, phone):
        eid = uuid.uuid4().hex[:12]
        self.events[eid] = dict(start=start, end=end, treatment=treatment, patient=patient, phone=phone)
        return Appointment(eid, start, end, treatment, patient)

    def list_for_phone(self, phone, since):
        return sorted(
            (Appointment(i, e["start"], e["end"], e["treatment"], e["patient"])
             for i, e in self.events.items() if e["phone"] == phone and e["start"] >= since),
            key=lambda a: a.start,
        )

    def cancel(self, event_id, phone):
        e = self.events.get(event_id)
        if not e or e["phone"] != phone:   # un paciente solo puede cancelar SUS citas
            return False
        del self.events[event_id]
        return True


class GoogleCalendar:
    """Requiere: cuenta de servicio de Google Cloud con la API de Calendar activada y el
    calendario de la clínica compartido con el email de esa cuenta (permiso 'Hacer cambios en eventos')."""

    SCOPES = ["https://www.googleapis.com/auth/calendar"]

    def __init__(self, calendar_id: str, credentials_json: str) -> None:
        from google.oauth2 import service_account
        from googleapiclient.discovery import build

        info = json.loads(credentials_json) if credentials_json.lstrip().startswith("{") else json.load(open(credentials_json))
        creds = service_account.Credentials.from_service_account_info(info, scopes=self.SCOPES)
        self.svc = build("calendar", "v3", credentials=creds, cache_discovery=False)
        self.calendar_id = calendar_id

    @classmethod
    def from_env(cls) -> "GoogleCalendar":
        return cls(os.environ["GOOGLE_CALENDAR_ID"], os.environ["GOOGLE_SERVICE_ACCOUNT_JSON"])

    @staticmethod
    def _dt(s: str) -> datetime:
        return datetime.fromisoformat(s.replace("Z", "+00:00"))

    def busy(self, start, end):
        body = {"timeMin": start.isoformat(), "timeMax": end.isoformat(), "items": [{"id": self.calendar_id}]}
        res = self.svc.freebusy().query(body=body).execute()
        return [(self._dt(b["start"]), self._dt(b["end"])) for b in res["calendars"][self.calendar_id]["busy"]]

    def create(self, *, start, end, treatment, patient, phone):
        body = {
            "summary": f"{treatment} – {patient} (WhatsApp)",
            "description": f"Paciente: {patient}\nTeléfono: +{phone}\nTratamiento: {treatment}\nReservada por el asistente de WhatsApp.",
            "start": {"dateTime": start.isoformat()},
            "end": {"dateTime": end.isoformat()},
            "extendedProperties": {"private": {"phone": phone, "source": SOURCE_TAG, "treatment": treatment, "patient": patient}},
        }
        ev = self.svc.events().insert(calendarId=self.calendar_id, body=body).execute()
        return Appointment(ev["id"], start, end, treatment, patient)

    def list_for_phone(self, phone, since):
        res = self.svc.events().list(
            calendarId=self.calendar_id, timeMin=since.isoformat(), singleEvents=True, orderBy="startTime",
            privateExtraProperty=f"phone={phone}", maxResults=20,
        ).execute()
        out = []
        for ev in res.get("items", []):
            p = ev.get("extendedProperties", {}).get("private", {})
            out.append(Appointment(ev["id"], self._dt(ev["start"]["dateTime"]), self._dt(ev["end"]["dateTime"]),
                                   p.get("treatment", ev.get("summary", "")), p.get("patient", "")))
        return out

    def cancel(self, event_id, phone):
        ev = self.svc.events().get(calendarId=self.calendar_id, eventId=event_id).execute()
        if ev.get("extendedProperties", {}).get("private", {}).get("phone") != phone:
            return False
        self.svc.events().delete(calendarId=self.calendar_id, eventId=event_id).execute()
        return True


def make_calendar() -> Calendar:
    if os.getenv("DEMO_MODE") == "1":
        return FakeCalendar()
    return GoogleCalendar.from_env()
