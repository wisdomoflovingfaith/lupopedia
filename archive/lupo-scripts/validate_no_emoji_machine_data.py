#!/usr/bin/env python3
"""
Validate constitutional rule: no emoji in machine-readable data.

Scans:
- lupo-channels/**/transcript.jsonl
- lupo-channels/**/tasks/*.json
- lupo-channels/**/actions/*.json
"""

from __future__ import annotations

import sys
from pathlib import Path

PROJECT_ROOT = Path(__file__).resolve().parent.parent
CHANNELS_DIR = PROJECT_ROOT / "lupo-channels"

EMOJI_PATTERN = (
    "✅|✔|✓|❌|✗|⚠️|⚠|📬|📋|📝|💬|🔥|💀|🎯|🔄|🔒|🔓|🐛|🔧|🚀|"
    "👤|🤖|🔍|💾|📂|⬆️|⬇️|➕|➖|✨|🆕|⏰|🕐|🚫|❓|💡|📢|🔔"
)


def has_any_emoji(text: str) -> bool:
    # Small explicit set for deterministic constitutional enforcement.
    for ch in [
        "✅", "✔", "✓", "❌", "✗", "⚠️", "⚠", "📬", "📋", "📝", "💬", "🔥", "💀", "🎯", "🔄",
        "🔒", "🔓", "🐛", "🔧", "🚀", "👤", "🤖", "🔍", "💾", "📂", "⬆️", "⬇️", "➕", "➖",
        "✨", "🆕", "⏰", "🕐", "🚫", "❓", "💡", "📢", "🔔",
    ]:
        if ch in text:
            return True
    return False


def iter_machine_files():
    for p in sorted(CHANNELS_DIR.rglob("transcript.jsonl")):
        yield p
    for p in sorted(CHANNELS_DIR.rglob("*.json")):
        parent = p.parent.name.lower()
        if parent in ("tasks", "actions"):
            yield p


def main() -> int:
    violations = []
    for path in iter_machine_files():
        text = path.read_text(encoding="utf-8", errors="replace")
        if has_any_emoji(text):
            violations.append(path)

    if violations:
        print("[FAIL] Emoji detected in machine-readable data:")
        for p in violations:
            print(" - " + str(p.relative_to(PROJECT_ROOT)))
        print("[HINT] Run: python lupo-scripts/clean_emoji_from_transcripts.py")
        return 1

    print("[OK] No emoji detected in transcript/task/action machine-readable data.")
    return 0


if __name__ == "__main__":
    sys.exit(main())

