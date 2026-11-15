"""WhatsApp Cloud API (Meta): verificación de firma, parseo de webhooks y envío de mensajes."""
from __future__ import annotations

import hashlib
import hmac
import os
from dataclasses import dataclass

import httpx


@dataclass
class Incoming:
    msg_id: str
    phone: str          # wa_id del paciente, solo dígitos (p. ej. 34600111222)
    name: str           # nombre del perfil de WhatsApp (puede ser vacío o no fiable)
    type: str           # text, audio, image, ...
    text: str


def valid_signature(raw_body: bytes, header: str | None, app_secret: str) -> bool:
    """Meta firma el cuerpo con HMAC-SHA256 usando el App Secret: cabecera 'X-Hub-Signature-256: sha256=...'."""
    if not header or not header.startswith("sha256="):
        return False
    expected = hmac.new(app_secret.encode(), raw_body, hashlib.sha256).hexdigest()
    return hmac.compare_digest(expected, header.removeprefix("sha256="))


def parse_webhook(payload: dict) -> list[Incoming]:
    out: list[Incoming] = []
    for entry in payload.get("entry", []):
        for change in entry.get("changes", []):
            value = change.get("value", {})
            names = {c.get("wa_id"): c.get("profile", {}).get("name", "") for c in value.get("contacts", [])}
            for m in value.get("messages", []):   # los 'statuses' (entregado/leído) no llevan 'messages' y se ignoran
                text = m.get("text", {}).get("body", "") if m.get("type") == "text" else ""
                out.append(Incoming(m["id"], m["from"], names.get(m["from"], ""), m.get("type", ""), text))
    return out


def send_text(to: str, body: str) -> None:
    version = os.getenv("GRAPH_API_VERSION", "v21.0")
    url = f"https://graph.facebook.com/{version}/{os.environ['WHATSAPP_PHONE_NUMBER_ID']}/messages"
    r = httpx.post(
        url,
        headers={"Authorization": f"Bearer {os.environ['WHATSAPP_TOKEN']}"},
        json={"messaging_product": "whatsapp", "to": to, "type": "text", "text": {"body": body[:4000]}},
        timeout=15,
    )
    r.raise_for_status()
