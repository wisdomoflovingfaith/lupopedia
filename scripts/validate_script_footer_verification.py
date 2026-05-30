#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324182000"
#   file_path_from_root: "scripts/validate_script_footer_verification.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "validator"
# lupopedia.footer:
#   last_verified: "20260324182000"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102
"""
Validate Lupopedia script-comment metadata for Python and PHP files.

Rules:
- Script metadata is carried in top-of-file comments.
- Metadata must include lupopedia.headers.when_updated.
- Metadata must include lupopedia.footer.last_verified, last_verified_by, last_verified_by_actor_id.
- last_verified must be >= cutoff UTC.
"""

from __future__ import annotations

import argparse
import re
from pathlib import Path
from typing import Dict, Optional, Tuple

import yaml


CUTOFF_UTC = 20260301000000


def _to_ymdhis(raw: object) -> Optional[int]:
    if raw is None:
        return None
    s = "".join(ch for ch in str(raw) if ch.isdigit())
    if len(s) == 8:
        s += "000000"
    if len(s) != 14:
        return None
    return int(s)


def _extract_python_comment_yaml(text: str) -> Optional[Dict]:
    lines = text.splitlines()
    out = []
    idx = 0
    if lines and lines[0].startswith("#!"):
        idx = 1
    while idx < len(lines):
        line = lines[idx]
        if not line.strip():
            if out:
                break
            idx += 1
            continue
        if not line.lstrip().startswith("#"):
            break
        stripped = re.sub(r"^\s*#\s?", "", line)
        out.append(stripped)
        idx += 1
    if not out:
        return None
    block = "\n".join(out)
    if "lupopedia.headers:" not in block and "lupopedia.footer:" not in block:
        return None
    parsed = yaml.safe_load(block)
    return parsed if isinstance(parsed, dict) else None


def _extract_php_comment_yaml(text: str) -> Optional[Dict]:
    head = "\n".join(text.splitlines()[:220])
    for m in re.finditer(r"/\*\*(.*?)\*/", head, re.DOTALL):
        raw = m.group(1)
        lines = []
        for line in raw.splitlines():
            line = re.sub(r"^\s*\*\s?", "", line)
            lines.append(line)
        block = "\n".join(lines)
        if "lupopedia.headers:" not in block and "lupopedia.footer:" not in block:
            continue
        parsed = yaml.safe_load(block)
        if isinstance(parsed, dict):
            return parsed
    return None


def extract_script_metadata(path: Path) -> Optional[Dict]:
    text = path.read_text(encoding="utf-8", errors="replace")
    if path.suffix == ".py":
        return _extract_python_comment_yaml(text)
    if path.suffix == ".php":
        return _extract_php_comment_yaml(text)
    return None


def validate_script(path: Path, cutoff_utc: int) -> Tuple[bool, str]:
    meta = extract_script_metadata(path)
    if not isinstance(meta, dict):
        return False, "MISSING_SCRIPT_METADATA"
    headers = meta.get("lupopedia.headers")
    footer = meta.get("lupopedia.footer")
    if not isinstance(headers, dict):
        return False, "MISSING_HEADERS_BLOCK"
    if not isinstance(footer, dict):
        return False, "MISSING_FOOTER_BLOCK"
    if _to_ymdhis(headers.get("when_updated")) is None:
        return False, "INVALID_WHEN_UPDATED"
    lv = _to_ymdhis(footer.get("last_verified"))
    if lv is None:
        return False, "INVALID_LAST_VERIFIED"
    if lv < cutoff_utc:
        return False, "STALE_LAST_VERIFIED"
    if not str(footer.get("last_verified_by", "")).strip():
        return False, "MISSING_LAST_VERIFIED_BY"
    if not str(footer.get("last_verified_by_actor_id", "")).strip().isdigit():
        return False, "MISSING_LAST_VERIFIED_BY_ACTOR_ID"
    return True, "OK"


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--repo-root", default=".")
    parser.add_argument("--cutoff-utc", default=str(CUTOFF_UTC))
    parser.add_argument("--strict", action="store_true")
    args = parser.parse_args()

    root = Path(args.repo_root).resolve()
    cutoff = int(str(args.cutoff_utc))
    scripts = sorted((root / "scripts").rglob("*.py")) + sorted(
        (root / "scripts").rglob("*.php")
    )

    issues = []
    for script in scripts:
        ok, reason = validate_script(script, cutoff)
        if not ok:
            issues.append((script, reason))

    for script, reason in issues:
        print("%s: %s" % (script, reason))
    print(
        "validate_script_footer_verification: %d/%d issue(s)"
        % (len(issues), len(scripts))
    )
    return 1 if (args.strict and issues) else 0


if __name__ == "__main__":
    raise SystemExit(main())
