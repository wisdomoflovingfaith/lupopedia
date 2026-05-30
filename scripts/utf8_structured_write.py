#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "scripts/utf8_structured_write.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/utf8_structured_write.py"
#   status: "active"
#   when_updated: "20260418201851"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/utf8-structured-write.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/utf8-structured-write"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: 16
#   content_slug: "utf8-structured-write"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "UTF-8 structured write helper for JSON and TOON outputs"
#   summary: "normalize_known_mojibake, prepare_for_filesystem_write, dumps_json_ready_for_write, write_text_utf8; forward-only; aligned with PHP Utf8StructuredWrite."
# ---------------------------------------------------------------------
"""
Forward-only UTF-8 helpers for JSON / TOON-shaped text before disk write.

Manual fix for a single corrupted file: copy through Windows Notepad (UTF-8),
per operator runbook. Do not add one-off encoding scripts unless systemic.

Import from generators:
    from utf8_structured_write import (
        prepare_for_filesystem_write,
        dumps_json_ready_for_write,
        write_text_utf8,
    )
"""

import json
from pathlib import Path
from typing import Any, Dict, Tuple

# Same three replacements as PHP Utf8StructuredWrite::mojibakeReplacementPairs() (byte-identical UTF-8 runs).
_MOJIBAKE_PAIRS: Tuple[Tuple[str, str], ...] = (
    # Corrupted: smart double-quote (U+201D class) mis-decoded as UTF-8 mojibake. Target: ASCII " (U+0022).
    ("\xc3\xa2\xe2\x82\xac\xc2\x9d", '"'),
    # Corrupted: dash-like mojibake where hyphen-minus was intended. Target: ASCII hyphen-minus.
    ("\xc3\xa2\xe2\x82\xac\xe2\x80\x98", "-"),
    # Corrupted: ellipsis (U+2026 class) mis-decoded chain. Target: remove (empty string).
    ("\xc3\xa2\xc5\x93\xe2\x80\xa6", ""),
)


def normalize_known_mojibake(text: str) -> str:
    if not text:
        return text
    out = text
    for bad, good in _MOJIBAKE_PAIRS:
        out = out.replace(bad, good)
    return out


def coerce_to_utf8(text: str) -> str:
    """
    Apply the small known-mojibake table, then normalize to a UTF-8-round-trippable str.

    This is **not** byte-level recovery equivalent to PHP ``iconv('UTF-8', 'UTF-8//IGNORE', ...)`` on
    arbitrary bytes: callers must pass ``str``. The encode/decode step uses ``errors="replace"`` so
    lone surrogate code points (invalid in UTF-8) become U+FFFD on the round-trip; typical JSON text
    never hits that path. For arbitrary binary repair, use a dedicated binary pipeline, not this helper.
    """
    out = normalize_known_mojibake(text)
    return out.encode("utf-8", errors="replace").decode("utf-8")


def prepare_for_filesystem_write(text: str, file_path: str = "") -> Dict[str, Any]:
    """
    Validate / normalize ``text`` for safe UTF-8 persistence as a single blob.

    * ``file_path``: pass-through only (symmetry with PHP ``prepareForFilesystemWrite`` and for
      outer-layer logging context). Intentionally unused inside this function.
    * **JSONL / framing:** this function does **not** define JSONL line boundaries or trailing
      newline policy beyond whatever ``text`` already contains; it only checks the string can be
      encoded as UTF-8 after mojibake repair. Callers own JSONL assembly (one write per line, etc.).
    """
    if not isinstance(text, str):
        return {"ok": False, "text": "", "reason": "not_string", "changed": False}
    before = text
    after = coerce_to_utf8(before)
    try:
        after.encode("utf-8")
    except UnicodeEncodeError:
        return {
            "ok": False,
            "text": "",
            "reason": "invalid_utf8_after_coerce",
            "changed": before != after,
        }
    return {
        "ok": True,
        "text": after,
        "reason": "",
        "changed": before != after,
    }


def write_text_utf8(path: Path, text: str, *, dry_run: bool = False) -> bool:
    prep = prepare_for_filesystem_write(text, str(path))
    if not prep.get("ok"):
        return False
    if dry_run:
        return True
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(prep["text"], encoding="utf-8", newline="\n")
    return True


def dumps_json_ready_for_write(obj: Any, **kwargs: Any) -> str:
    """
    ``json.dumps`` the object, ensure exactly one trailing newline, then UTF-8 prepare.

    The trailing newline is intentional so callers can append or write one JSON record per line
    without adding separators here. JSONL consumers still split on newlines themselves.
    """
    body = json.dumps(obj, ensure_ascii=False, **kwargs)
    if not body.endswith("\n"):
        body += "\n"
    prep = prepare_for_filesystem_write(body, "")
    if not prep.get("ok"):
        raise ValueError("structured UTF-8 prepare failed: %r" % prep.get("reason"))
    return prep["text"]
