#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324182200"
#   file_path_from_root: "lupo-scripts/validate_footer_verification.py"
#   last_modified_utc: "20260324182200"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324182200"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Footer verification validator (LUPOPEDIA FOOTER hardening).

Rules:
- lupopedia.footer must include validator identity:
  - last_verified_by
  - last_verified_by_actor_id (or validator_actor_id / validated_by_actor_id)
- last_verified must be parseable as UTC date/time.
- Revalidation required when last_verified is missing or before cutoff UTC.
"""

from __future__ import annotations

import datetime as dt
import json
import re
from pathlib import Path
from typing import Dict, List, Optional, Tuple

try:
    import yaml  # type: ignore
except Exception:  # pragma: no cover
    yaml = None


DEFAULT_REGISTRY_PATH = Path("lupo-database/lupopedia/actors/actor_id/registry.json")
DEFAULT_CUTOFF_UTC_YMDHIS = 20260301000000
VALIDATOR_ID_KEYS = ("last_verified_by_actor_id", "validator_actor_id", "validated_by_actor_id")


def load_actor_registry(repo_root: Path) -> Dict[int, str]:
    reg_path = (repo_root / DEFAULT_REGISTRY_PATH).resolve()
    if not reg_path.is_file():
        raise FileNotFoundError("Registry not found: %s" % reg_path)
    with open(reg_path, "r", encoding="utf-8") as f:
        data = json.load(f)
    actors = data.get("actors", [])
    out: Dict[int, str] = {}
    for a in actors:
        try:
            aid = int(a["id"])
        except Exception:
            continue
        slug = str(a.get("slug", "")).strip()
        if slug:
            out[aid] = slug
    return out


def _line_ref(path: Path, line_no: Optional[int]) -> str:
    if line_no is None:
        return str(path)
    return "%s:%d" % (path, line_no)


def _frontmatter_span(lines: List[str]) -> Optional[Tuple[int, int]]:
    if not lines:
        return None
    if lines[0].strip() != "---":
        return None
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            return (0, i)
    return None


def _extract_yaml_frontmatter(text: str) -> Tuple[Optional[dict], Optional[str], Optional[str]]:
    if yaml is None:
        return None, None, "PyYAML unavailable"
    lines = text.splitlines()
    span = _frontmatter_span(lines)
    if not span:
        return None, None, "Missing YAML frontmatter"
    _, end = span
    fm_text = "\n".join(lines[1:end])
    try:
        parsed = yaml.safe_load(fm_text)
    except Exception:
        return None, fm_text, "YAML parse failed"
    if not isinstance(parsed, dict):
        return None, fm_text, "YAML frontmatter is not a mapping"
    return parsed, fm_text, None


def _get_headers_block(parsed: dict) -> dict:
    if isinstance(parsed.get("lupopedia.headers"), dict):
        return parsed.get("lupopedia.headers")
    lup = parsed.get("lupopedia")
    if isinstance(lup, dict) and isinstance(lup.get("headers"), dict):
        return lup.get("headers")
    return {}


def _get_footer_block(parsed: dict) -> Tuple[dict, str]:
    if isinstance(parsed.get("lupopedia.footer"), dict):
        return parsed.get("lupopedia.footer"), "dotted"
    lup = parsed.get("lupopedia")
    if isinstance(lup, dict) and isinstance(lup.get("footer"), dict):
        return lup.get("footer"), "nested"
    return {}, "missing"


def _set_footer_block(parsed: dict, footer: dict, style: str) -> None:
    if style == "dotted":
        parsed["lupopedia.footer"] = footer
        return
    if style == "nested":
        if not isinstance(parsed.get("lupopedia"), dict):
            parsed["lupopedia"] = {}
        parsed["lupopedia"]["footer"] = footer
        return
    # missing style: prefer dotted key for compatibility with existing files in repo.
    parsed["lupopedia.footer"] = footer


def _parse_utc_to_ymdhis(raw: object) -> Optional[int]:
    if raw is None:
        return None
    if isinstance(raw, bool):
        return None
    if isinstance(raw, int):
        s = str(raw)
        if re.fullmatch(r"\d{8}", s):
            return int(s + "000000")
        if re.fullmatch(r"\d{14}", s):
            return int(s)
        return None
    s = str(raw).strip()
    if not s:
        return None
    if re.fullmatch(r"\d{8}", s):
        return int(s + "000000")
    if re.fullmatch(r"\d{14}", s):
        return int(s)
    # ISO-like UTC only.
    iso = s.replace(" ", "T")
    if iso.endswith("Z"):
        iso = iso[:-1] + "+00:00"
    if re.search(r"(?:[+-]\d{2}:\d{2})$", iso) and not iso.endswith("+00:00"):
        return None
    if iso.endswith("UTC"):
        iso = iso[:-3].strip() + "+00:00"
    try:
        d = dt.datetime.fromisoformat(iso)
    except Exception:
        # Try YYYY-MM-DD
        try:
            d = dt.datetime.strptime(s, "%Y-%m-%d")
            d = d.replace(tzinfo=dt.timezone.utc)
        except Exception:
            return None
    if d.tzinfo is None:
        # Numeric/local-like date is treated as UTC per doctrine.
        d = d.replace(tzinfo=dt.timezone.utc)
    d = d.astimezone(dt.timezone.utc)
    return int(d.strftime("%Y%m%d%H%M%S"))


def _first_validator_actor_id(footer: dict) -> Optional[int]:
    for key in VALIDATOR_ID_KEYS:
        val = footer.get(key)
        if isinstance(val, int):
            return val
        if isinstance(val, str) and val.strip().isdigit():
            return int(val.strip())
    return None


def validate_footer_verification_text(
    text: str,
    path: Path,
    actor_registry: Optional[Dict[int, str]] = None,
    cutoff_utc_ymdhis: int = DEFAULT_CUTOFF_UTC_YMDHIS,
) -> List[str]:
    issues: List[str] = []
    parsed, _, err = _extract_yaml_frontmatter(text)
    if err is not None or parsed is None:
        return issues

    footer, _ = _get_footer_block(parsed)
    if not footer:
        issues.append(
            "FOOTER_REVALIDATION_REQUIRED[FOOTER_MISSING]: %s missing lupopedia.footer block"
            % _line_ref(path, None)
        )
        return issues

    last_verified = _parse_utc_to_ymdhis(footer.get("last_verified"))
    if last_verified is None:
        issues.append(
            "FOOTER_REVALIDATION_REQUIRED[LAST_VERIFIED_MISSING_OR_INVALID]: %s lupopedia.footer.last_verified missing/invalid UTC date"
            % _line_ref(path, None)
        )
    elif last_verified < int(cutoff_utc_ymdhis):
        issues.append(
            "FOOTER_REVALIDATION_REQUIRED[LAST_VERIFIED_STALE]: %s last_verified=%s is before cutoff=%s UTC"
            % (_line_ref(path, None), last_verified, cutoff_utc_ymdhis)
        )

    verifier_name = footer.get("last_verified_by")
    if not isinstance(verifier_name, str) or not verifier_name.strip():
        issues.append(
            "FOOTER_REVALIDATION_REQUIRED[VERIFIER_NAME_MISSING]: %s lupopedia.footer.last_verified_by missing"
            % _line_ref(path, None)
        )

    verifier_id = _first_validator_actor_id(footer)
    if verifier_id is None:
        issues.append(
            "FOOTER_REVALIDATION_REQUIRED[VERIFIER_ID_MISSING]: %s add lupopedia.footer.last_verified_by_actor_id"
            % _line_ref(path, None)
        )
    elif actor_registry is not None and isinstance(verifier_name, str) and verifier_name.strip():
        expected = actor_registry.get(verifier_id)
        if expected is not None and verifier_name.strip() != expected.strip():
            issues.append(
                "FOOTER_REVALIDATION_REQUIRED[VERIFIER_ID_NAME_MISMATCH]: %s verifier id=%s resolves to '%s' but footer has '%s'"
                % (_line_ref(path, None), verifier_id, expected, verifier_name.strip())
            )

    # Optional consistency: if header has actor info, ensure they are not placeholders.
    headers = _get_headers_block(parsed)
    if isinstance(headers, dict):
        hid = headers.get("actor_id")
        hname = headers.get("actor_name")
        if hid in (None, "") or hname in (None, ""):
            issues.append(
                "FOOTER_REVALIDATION_REQUIRED[HEADER_ACTOR_CONTEXT_MISSING]: %s lupopedia.headers actor_id/actor_name should be present for verifiable attribution"
                % _line_ref(path, None)
            )

    return issues


def autofix_footer_verification_text(
    text: str,
    validator_actor_id: int,
    validator_actor_name: str,
    now_utc_ymdhis: Optional[int] = None,
) -> Tuple[str, bool, Optional[str]]:
    parsed, _, err = _extract_yaml_frontmatter(text)
    if err is not None or parsed is None:
        return text, False, err

    footer, style = _get_footer_block(parsed)
    if not isinstance(footer, dict):
        footer = {}

    ts = now_utc_ymdhis
    if ts is None:
        ts = int(dt.datetime.now(dt.timezone.utc).strftime("%Y%m%d%H%M%S"))

    footer["last_verified"] = str(ts)
    footer["last_verified_by"] = str(validator_actor_name).strip()
    footer["last_verified_by_actor_id"] = int(validator_actor_id)
    _set_footer_block(parsed, footer, style)

    if yaml is None:
        return text, False, "PyYAML unavailable"
    dumped = yaml.safe_dump(parsed, sort_keys=False, allow_unicode=False)

    # Replace first frontmatter block only.
    lines = text.splitlines()
    span = _frontmatter_span(lines)
    if not span:
        return text, False, "Missing YAML frontmatter"
    _, end = span
    body = "\n".join(lines[end + 1 :])
    if body:
        out = "---\n%s---\n%s" % (dumped, body)
    else:
        out = "---\n%s---\n" % dumped
    return out, True, None
