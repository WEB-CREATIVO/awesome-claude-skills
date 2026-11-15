"""Chat por terminal para probar el cerebro sin WhatsApp ni Google (agenda en memoria).

    ANTHROPIC_API_KEY=... python -m app.cli
"""
from __future__ import annotations

from datetime import datetime

from .agent import Tools, run_agent
from .calendar_service import FakeCalendar
from .config import load_clinic
from .store import Store


def main() -> None:
    clinic = load_clinic()
    store = Store(":memory:")
    tools = Tools(clinic, FakeCalendar(), store, lambda t: print(f"\n[AVISO A RECEPCIÓN] {t}"), now=lambda: datetime.now(clinic.tz))
    print(f"Chat de prueba con {clinic.name}. Ctrl+C para salir.\n")
    while True:
        text = input("Tú: ").strip()
        if text:
            print("Bot:", run_agent(clinic=clinic, tools=tools, store=store, phone="34600000000", profile_name="Prueba", user_text=text), "\n")


if __name__ == "__main__":
    main()
