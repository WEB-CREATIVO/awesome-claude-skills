"""Servidor webhook de WhatsApp. Arranque: uvicorn app.main:app --host 0.0.0.0 --port 8000"""
from __future__ import annotations

import json
import logging
import os
from datetime import datetime
from functools import lru_cache

from fastapi import BackgroundTasks, FastAPI, HTTPException, Request
from fastapi.responses import PlainTextResponse

from . import whatsapp
from .agent import Tools, run_agent
from .calendar_service import make_calendar
from .config import load_clinic
from .store import Store
from .whatsapp import Incoming

logging.basicConfig(level=os.getenv("LOG_LEVEL", "INFO"))
log = logging.getLogger("main")
app = FastAPI(title="Asistente WhatsApp clínica dental")


@lru_cache
def ctx():
    clinic = load_clinic()
    store = Store(os.getenv("DB_PATH", "bot.db"))
    staff = os.getenv("STAFF_WHATSAPP")   # número de recepción, solo dígitos

    def notify_staff(text: str) -> None:
        log.warning("AVISO RECEPCIÓN: %s", text)
        if staff:
            whatsapp.send_text(staff, text)

    tools = Tools(clinic, make_calendar(), store, notify_staff, now=lambda: datetime.now(clinic.tz))
    return clinic, store, tools


def handle_incoming(msg: Incoming) -> None:
    clinic, store, tools = ctx()
    if not store.first_time_seen(msg.msg_id):
        return                                    # reintento de Meta ya procesado
    if store.in_handoff(msg.phone):
        log.info("Conversación derivada a recepción; el bot no responde (%s)", msg.phone[-4:])
        return
    if msg.type != "text" or not msg.text.strip():
        whatsapp.send_text(msg.phone, "Por ahora solo puedo leer mensajes de texto. ¿Me escribes lo que necesitas? "
                                      f"Si prefieres, llama a recepción: {clinic.phone}")
        return

    # Red de seguridad determinista para urgencias: no depende de que el modelo lo detecte.
    lowered = msg.text.lower()
    if any(k in lowered for k in clinic.emergency_keywords):
        res = tools.handoff_to_human(msg.phone, reason=f"Posible urgencia: «{msg.text[:200]}»", urgent=True, profile_name=msg.name)
        whatsapp.send_text(msg.phone, f"Lo que describes puede ser una urgencia. {res['message_for_patient']}. "
                                      "Si hay dificultad para respirar o riesgo vital, llama al 112 ahora.")
        return

    try:
        reply = run_agent(clinic=clinic, tools=tools, store=store, phone=msg.phone, profile_name=msg.name, user_text=msg.text)
        whatsapp.send_text(msg.phone, reply)
    except Exception:
        log.exception("Error procesando mensaje")


@app.get("/health")
def health():
    return {"ok": True}


@app.get("/webhook")
def verify(request: Request):
    q = request.query_params
    if q.get("hub.mode") == "subscribe" and q.get("hub.verify_token") == os.getenv("WHATSAPP_VERIFY_TOKEN") and os.getenv("WHATSAPP_VERIFY_TOKEN"):
        return PlainTextResponse(q.get("hub.challenge", ""))
    raise HTTPException(403, "Verificación fallida")


@app.post("/webhook")
async def receive(request: Request, background: BackgroundTasks):
    raw = await request.body()
    secret = os.getenv("WHATSAPP_APP_SECRET")
    if not secret:
        raise HTTPException(500, "WHATSAPP_APP_SECRET no configurado")
    if not whatsapp.valid_signature(raw, request.headers.get("X-Hub-Signature-256"), secret):
        raise HTTPException(403, "Firma inválida")
    for msg in whatsapp.parse_webhook(json.loads(raw)):
        background.add_task(handle_incoming, msg)     # responder 200 rápido; Meta reintenta si tardamos
    return {"ok": True}
