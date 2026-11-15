"""Persistencia mínima en SQLite: historial corto de conversación, deduplicado de webhooks y pausas por derivación a humano."""
from __future__ import annotations

import sqlite3
import threading
import time


class Store:
    def __init__(self, path: str = "bot.db") -> None:
        self.db = sqlite3.connect(path, check_same_thread=False)
        self.lock = threading.Lock()
        with self.lock:
            self.db.executescript(
                """
                CREATE TABLE IF NOT EXISTS messages (id INTEGER PRIMARY KEY, phone TEXT, role TEXT, content TEXT, ts REAL);
                CREATE INDEX IF NOT EXISTS idx_messages_phone ON messages(phone, id);
                CREATE TABLE IF NOT EXISTS processed (msg_id TEXT PRIMARY KEY, ts REAL);
                CREATE TABLE IF NOT EXISTS handoff (phone TEXT PRIMARY KEY, until REAL);
                """
            )

    def first_time_seen(self, msg_id: str) -> bool:
        """True si es la primera vez que vemos este id (Meta reintenta webhooks)."""
        with self.lock:
            try:
                self.db.execute("INSERT INTO processed VALUES (?, ?)", (msg_id, time.time()))
                self.db.commit()
                return True
            except sqlite3.IntegrityError:
                return False

    def add_message(self, phone: str, role: str, content: str) -> None:
        with self.lock:
            self.db.execute("INSERT INTO messages (phone, role, content, ts) VALUES (?,?,?,?)", (phone, role, content, time.time()))
            self.db.commit()

    def history(self, phone: str, limit: int = 16, max_age_h: float = 24 * 3) -> list[dict]:
        cutoff = time.time() - max_age_h * 3600
        with self.lock:
            rows = self.db.execute(
                "SELECT role, content FROM messages WHERE phone=? AND ts>=? ORDER BY id DESC LIMIT ?", (phone, cutoff, limit)
            ).fetchall()
        msgs = [{"role": r, "content": c} for r, c in reversed(rows)]
        while msgs and msgs[0]["role"] != "user":   # la API exige empezar por 'user'
            msgs.pop(0)
        return msgs

    def set_handoff(self, phone: str, hours: float = 12) -> None:
        with self.lock:
            self.db.execute("INSERT OR REPLACE INTO handoff VALUES (?, ?)", (phone, time.time() + hours * 3600))
            self.db.commit()

    def in_handoff(self, phone: str) -> bool:
        with self.lock:
            row = self.db.execute("SELECT until FROM handoff WHERE phone=?", (phone,)).fetchone()
        return bool(row and row[0] > time.time())
