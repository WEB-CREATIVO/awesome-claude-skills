import hashlib
import hmac
import json
from datetime import datetime, timedelta
from types import SimpleNamespace

import pytest
from fastapi.testclient import TestClient

from app import main, whatsapp
from app.agent import Tools, run_agent
from app.calendar_service import FakeCalendar
from app.config import load_clinic
from app.slots import compute_slots
from app.store import Store

CLINIC = load_clinic()
NOW = datetime(2026, 11, 16, 8, 0, tzinfo=CLINIC.tz)   # lunes 08:00


def make_tools(cal=None, notified=None):
    notified = notified if notified is not None else []
    return Tools(CLINIC, cal or FakeCalendar(), Store(":memory:"), notified.append, now=lambda: NOW), notified


# ---------- huecos ----------
def test_slots_respect_hours_notice_and_busy():
    busy = [(datetime(2026, 11, 17, 9, 0, tzinfo=CLINIC.tz), datetime(2026, 11, 17, 10, 0, tzinfo=CLINIC.tz))]
    s = compute_slots(
        opening_hours=CLINIC.opening_hours, closed_dates=set(), tz=CLINIC.tz,
        day_from=NOW.date(), day_to=datetime(2026, 11, 17).date(), duration=timedelta(minutes=30),
        busy=busy, now=NOW, step=timedelta(minutes=30), min_notice=timedelta(hours=4),
    )
    times = {x.strftime("%a %H:%M") for x in s}
    assert "Mon 09:00" not in times          # menos de 4 h de antelación (08:00 + 4h = 12:00)
    assert "Mon 12:00" in times
    assert "Mon 14:00" not in times          # pausa del mediodía
    assert "Mon 16:00" in times
    assert "Mon 19:30" in times and "Mon 20:00" not in times   # la cita debe terminar a las 20:00
    assert "Tue 09:00" not in times and "Tue 09:30" not in times   # ocupado 09–10
    assert "Tue 10:00" in times


def test_closed_dates_and_weekend():
    s = compute_slots(
        opening_hours=CLINIC.opening_hours, closed_dates={"2026-11-17"}, tz=CLINIC.tz,
        day_from=datetime(2026, 11, 17).date(), day_to=datetime(2026, 11, 22).date(), duration=timedelta(minutes=30), busy=[], now=NOW,
    )
    days = {x.date().isoformat() for x in s}
    assert "2026-11-17" not in days          # cerrado
    assert "2026-11-21" not in days and "2026-11-22" not in days   # sáb/dom
    assert "2026-11-18" in days


# ---------- herramientas ----------
def test_book_and_double_booking_rejected():
    tools, _ = make_tools()
    r1 = tools.book_appointment("34611", "limpieza", "2026-11-17T10:00", "Ana Pérez")
    assert r1["ok"]
    r2 = tools.book_appointment("34622", "revision", "2026-11-17T10:15", "Luis Gómez")   # solapa con la limpieza (10:00–10:45)
    assert "error" in r2
    r3 = tools.book_appointment("34622", "revision", "2026-11-17T10:45", "Luis Gómez")
    assert r3["ok"]


def test_cannot_book_invalid_slot():
    tools, _ = make_tools()
    assert "error" in tools.book_appointment("34611", "limpieza", "2026-11-21T10:00", "Ana Pérez")   # sábado
    assert "error" in tools.book_appointment("34611", "limpieza", "2026-11-17T03:00", "Ana Pérez")   # de madrugada
    assert "error" in tools.book_appointment("34611", "inventado", "2026-11-17T10:00", "Ana Pérez")
    assert "error" in tools.book_appointment("34611", "limpieza", "2026-11-17T10:00", "A")           # sin nombre


def test_patient_can_only_cancel_own_appointments():
    tools, _ = make_tools()
    appt = tools.book_appointment("34611", "limpieza", "2026-11-17T10:00", "Ana Pérez")
    assert "error" in tools.cancel_appointment("34999", appt["appointment_id"])      # otro número
    assert tools.list_my_appointments("34999")["appointments"] == []
    assert len(tools.list_my_appointments("34611")["appointments"]) == 1
    assert tools.cancel_appointment("34611", appt["appointment_id"])["ok"]


def test_availability_rejects_bad_input_and_limits_range():
    tools, _ = make_tools()
    assert "error" in tools.check_availability("34611", "nope", "2026-11-17", "2026-11-18")
    assert "error" in tools.check_availability("34611", "limpieza", "mañana", "2026-11-18")
    res = tools.check_availability("34611", "limpieza", "2026-11-17", "2026-11-30", "afternoon")
    assert res["available_days"] and all(int(t[:2]) >= 14 for d in res["available_days"] for t in d["times"])


def test_handoff_pauses_bot_and_notifies():
    tools, notified = make_tools()
    tools.handoff_to_human("34611", "queja", urgent=True, profile_name="Ana")
    assert tools.store.in_handoff("34611") and "URGENTE" in notified[0]


# ---------- bucle del agente con un "Claude" simulado ----------
def fake_client(script):
    """script: lista de respuestas ya construidas, devueltas en orden."""
    calls = []

    def create(**kw):
        calls.append(kw)
        return script[len(calls) - 1]

    return SimpleNamespace(messages=SimpleNamespace(create=create)), calls


def tool_use(id_, name, **inp):
    return SimpleNamespace(stop_reason="tool_use", content=[SimpleNamespace(type="tool_use", id=id_, name=name, input=inp)])


def text(t):
    return SimpleNamespace(stop_reason="end_turn", content=[SimpleNamespace(type="text", text=t)])


def test_agent_loop_books_with_server_side_phone():
    tools, _ = make_tools()
    client, calls = fake_client([
        tool_use("t1", "check_availability", treatment_id="limpieza", date_from="2026-11-17", date_to="2026-11-17"),
        tool_use("t2", "book_appointment", treatment_id="limpieza", start="2026-11-17T10:00", patient_name="Ana Pérez"),
        text("¡Listo, Ana! Martes 17/11 a las 10:00."),
    ])
    reply = run_agent(clinic=CLINIC, tools=tools, store=tools.store, phone="34611", profile_name="", user_text="Quiero limpieza el martes", client=client)
    assert "Martes" in reply
    appts = tools.list_my_appointments("34611")["appointments"]
    assert len(appts) == 1
    # el resultado de la herramienta se devolvió al modelo
    assert calls[1]["messages"][-1]["content"][0]["type"] == "tool_result"
    assert "Clínica Dental Ejemplo" in calls[0]["system"]


def test_agent_falls_back_when_llm_fails():
    tools, _ = make_tools()
    client = SimpleNamespace(messages=SimpleNamespace(create=lambda **kw: (_ for _ in ()).throw(RuntimeError("API caída"))))
    reply = run_agent(clinic=CLINIC, tools=tools, store=tools.store, phone="34611", profile_name="", user_text="hola", client=client)
    assert CLINIC.phone in reply


def test_agent_stops_after_max_tool_rounds():
    tools, _ = make_tools()
    loop = [tool_use(f"t{i}", "list_my_appointments") for i in range(20)]
    client, calls = fake_client(loop)
    reply = run_agent(clinic=CLINIC, tools=tools, store=tools.store, phone="34611", profile_name="", user_text="hola", client=client)
    assert len(calls) == 6 and CLINIC.phone in reply


# ---------- webhook ----------
SECRET = "s3cret"


def signed(body: dict):
    raw = json.dumps(body).encode()
    return raw, "sha256=" + hmac.new(SECRET.encode(), raw, hashlib.sha256).hexdigest()


PAYLOAD = {"entry": [{"changes": [{"value": {
    "contacts": [{"wa_id": "34611", "profile": {"name": "Ana"}}],
    "messages": [{"id": "wamid.1", "from": "34611", "type": "text", "text": {"body": "hola"}}]}}]}]}


@pytest.fixture
def client(monkeypatch):
    monkeypatch.setenv("WHATSAPP_APP_SECRET", SECRET)
    monkeypatch.setenv("WHATSAPP_VERIFY_TOKEN", "tok")
    got = []
    monkeypatch.setattr(main, "handle_incoming", lambda m: got.append(m))
    c = TestClient(main.app)
    c.got = got
    return c


def test_webhook_verification(client):
    ok = client.get("/webhook", params={"hub.mode": "subscribe", "hub.verify_token": "tok", "hub.challenge": "123"})
    assert ok.status_code == 200 and ok.text == "123"
    assert client.get("/webhook", params={"hub.mode": "subscribe", "hub.verify_token": "mal", "hub.challenge": "1"}).status_code == 403


def test_webhook_rejects_bad_signature_and_accepts_good(client):
    raw, sig = signed(PAYLOAD)
    assert client.post("/webhook", content=raw, headers={"X-Hub-Signature-256": "sha256=00"}).status_code == 403
    assert client.post("/webhook", content=raw).status_code == 403
    assert client.got == []
    assert client.post("/webhook", content=raw, headers={"X-Hub-Signature-256": sig}).status_code == 200
    assert client.got[0].phone == "34611" and client.got[0].name == "Ana" and client.got[0].text == "hola"


def test_status_updates_are_ignored():
    assert whatsapp.parse_webhook({"entry": [{"changes": [{"value": {"statuses": [{"id": "x", "status": "read"}]}}]}]}) == []


# ---------- handle_incoming: dedupe + urgencias deterministas ----------
def test_handle_incoming_dedupe_and_emergency(monkeypatch):
    sent = []
    tools, notified = make_tools()
    monkeypatch.setattr(main, "ctx", lambda: (CLINIC, tools.store, tools))
    monkeypatch.setattr(whatsapp, "send_text", lambda to, body: sent.append((to, body)))
    msg = whatsapp.Incoming("wamid.9", "34611", "Ana", "text", "me sangra la encía y no para de sangrar")
    main.handle_incoming(msg)
    main.handle_incoming(msg)                                   # reintento de Meta
    assert len(sent) == 1 and "112" in sent[0][1] and CLINIC.emergency_phone in sent[0][1]
    assert len(notified) == 1 and tools.store.in_handoff("34611")
    main.handle_incoming(whatsapp.Incoming("wamid.10", "34611", "Ana", "text", "hola"))
    assert len(sent) == 1                                       # derivada: el bot calla
