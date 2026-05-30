#!/usr/bin/env python3
"""
One-time cleanup: replace emoji with ASCII tags in transcript/task/action data.
"""

from __future__ import annotations

import json
import os
import sys
from pathlib import Path
from typing import Any, Tuple

_SCRIPTS_DIR = os.path.dirname(os.path.abspath(__file__))
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

from lib.string_utils import replace_emoji

PROJECT_ROOT = Path(__file__).resolve().parent.parent
CHANNELS_DIR = PROJECT_ROOT / "channels"


def sanitize_value(value: Any) -> Tuple[Any, bool]:
    changed = False
    if isinstance(value, str):
        new_value = replace_emoji(value)
        return new_value, new_value != value
    if isinstance(value, list):
        out = []
        for item in value:
            new_item, item_changed = sanitize_value(item)
            out.append(new_item)
            changed = changed or item_changed
        return out, changed
    if isinstance(value, dict):
        out = {}
        for key, item in value.items():
            new_item, item_changed = sanitize_value(item)
            out[key] = new_item
            changed = changed or item_changed
        return out, changed
    return value, False


def clean_transcript_file(path: Path) -> bool:
    lines = path.read_text(encoding="utf-8", errors="replace").splitlines()
    changed = False
    new_lines = []
    for line in lines:
        if not line.strip():
            new_lines.append(line)
            continue
        try:
            obj = json.loads(line)
            new_obj, line_changed = sanitize_value(obj)
            new_lines.append(json.dumps(new_obj, ensure_ascii=False))
            changed = changed or line_changed
        except Exception:
            new_line = replace_emoji(line)
            new_lines.append(new_line)
            changed = changed or (new_line != line)
    if changed:
        path.write_text("\n".join(new_lines) + "\n", encoding="utf-8")
    return changed


def clean_json_file(path: Path) -> bool:
    raw = path.read_text(encoding="utf-8", errors="replace")
    try:
        obj = json.loads(raw)
    except Exception:
        new_raw = replace_emoji(raw)
        if new_raw != raw:
            path.write_text(new_raw, encoding="utf-8")
            return True
        return False

    new_obj, changed = sanitize_value(obj)
    if changed:
        path.write_text(json.dumps(new_obj, indent=2, ensure_ascii=False), encoding="utf-8")
    return changed


def main() -> None:
    transcript_files = sorted(CHANNELS_DIR.rglob("transcript.jsonl"))
    json_files = []
    for p in CHANNELS_DIR.rglob("*.json"):
        parent_name = p.parent.name.lower()
        if parent_name in ("tasks", "actions"):
            json_files.append(p)

    cleaned = 0
    scanned = 0

    for path in transcript_files:
        scanned += 1
        did_change = clean_transcript_file(path)
        status = "[FIX]" if did_change else "[OK]"
        print("{0} {1}".format(status, path.relative_to(PROJECT_ROOT)))
        if did_change:
            cleaned += 1

    for path in sorted(json_files):
        scanned += 1
        did_change = clean_json_file(path)
        status = "[FIX]" if did_change else "[OK]"
        print("{0} {1}".format(status, path.relative_to(PROJECT_ROOT)))
        if did_change:
            cleaned += 1

    print("[DONE] scanned={0} cleaned={1}".format(scanned, cleaned))


if __name__ == "__main__":
    main()

