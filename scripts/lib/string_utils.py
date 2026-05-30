#!/usr/bin/env python3
"""
String sanitation helpers for transcript/task/memory text.
"""

from __future__ import annotations

import re
from typing import Any

EMOJI_TO_ASCII = {
    "✅": "[OK]",
    "✔": "[OK]",
    "✓": "[OK]",
    "❌": "[FAIL]",
    "✗": "[FAIL]",
    "⚠️": "[WARN]",
    "⚠": "[WARN]",
    "📬": "[TASK]",
    "📋": "[TASK]",
    "📝": "[NOTE]",
    "💬": "[CHAT]",
    "🔥": "[HOT]",
    "💀": "[DEAD]",
    "🎯": "[TARGET]",
    "🔄": "[SYNC]",
    "🔒": "[LOCK]",
    "🔓": "[UNLOCK]",
    "🐛": "[BUG]",
    "🔧": "[FIX]",
    "🚀": "[DEPLOY]",
    "👤": "[USER]",
    "🤖": "[AGENT]",
    "🔍": "[SEARCH]",
    "💾": "[SAVE]",
    "📂": "[FOLDER]",
    "⬆️": "[UP]",
    "⬇️": "[DOWN]",
    "➕": "[ADD]",
    "➖": "[REMOVE]",
    "✨": "[NEW]",
    "🆕": "[NEW]",
    "⏰": "[TIME]",
    "🕐": "[TIME]",
    "🚫": "[BLOCK]",
    "❓": "[Q]",
    "💡": "[IDEA]",
    "📢": "[ALERT]",
    "🔔": "[ALERT]",
}

# Catch likely remaining emoji (not punctuation, accented letters, or symbols like ©®™)
EMOJI_REGEX = re.compile(
    r"[\U0001F300-\U0001F5FF"
    r"\U0001F600-\U0001F64F"
    r"\U0001F680-\U0001F6FF"
    r"\U0001F700-\U0001F77F"
    r"\U0001F780-\U0001F7FF"
    r"\U0001F800-\U0001F8FF"
    r"\U0001F900-\U0001F9FF"
    r"\U0001FA00-\U0001FAFF"
    r"\U00002700-\U000027BF"
    r"\U0000FE0F"
    r"\U0000200D]"
)


def replace_emoji(text: Any) -> str:
    s = str(text)
    for emoji_char, ascii_tag in EMOJI_TO_ASCII.items():
        s = s.replace(emoji_char, ascii_tag)
    s = EMOJI_REGEX.sub("[?]", s)
    return s


def sanitize_text(text: Any) -> str:
    s = replace_emoji(text)
    s = re.sub(r"\s+", " ", s).strip()
    return s

