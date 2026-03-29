#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324182200"
#   file_path_from_root: "lupo-scripts/lib/header_validation.py"
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
Deterministic LUPOPEDIA header parser and validator.

Return contract:
{
    "valid": bool,
    "errors": [ ... ],
}
"""

from __future__ import annotations

import re
from typing import Any, Dict, Optional

try:
    import yaml  # type: ignore
except Exception:  # pragma: no cover
    yaml = None


REQUIRED_FIELDS = (
    "when_updated",
    "file_path_from_root",
    "last_modified_utc",
    "channel_id",
    "thread_id",
    "actor_id",
    "actor_name",
    "artifact_type",
    "artifact_kind",
)

STRING_FIELDS = (
    "file_path_from_root",
    "actor_name",
    "artifact_type",
    "artifact_kind",
)

NUMERIC_FIELDS = ("channel_id", "actor_id")


def parse_front_matter_header(text: str) -> Dict[str, Any]:
    """
    Parse markdown frontmatter and return:
    {
      "valid": bool,
      "errors": [...],
      "header": dict,
      "body": str,
    }
    """
    if not isinstance(text, str) or text == "":
        return {
            "valid": False,
            "errors": ["Malformed header: expected non-empty text content."],
            "header": {},
            "body": "",
        }

    lines = text.replace("\r\n", "\n").split("\n")
    if len(lines) < 2 or lines[0].strip() != "---":
        return {
            "valid": False,
            "errors": ["Malformed header: missing opening '---' delimiter."],
            "header": {},
            "body": text,
        }

    close_idx = -1
    i = 1
    while i < len(lines):
        if lines[i].strip() == "---":
            close_idx = i
            break
        i += 1

    if close_idx < 0:
        return {
            "valid": False,
            "errors": ["Malformed header: missing closing '---' delimiter."],
            "header": {},
            "body": text,
        }

    yaml_block = "\n".join(lines[1:close_idx])
    body = "\n".join(lines[close_idx + 1 :]).lstrip("\n")

    if yaml is None:
        return {
            "valid": False,
            "errors": ["Malformed header: PyYAML unavailable for deterministic parsing."],
            "header": {},
            "body": body,
        }

    try:
        parsed = yaml.safe_load(yaml_block)
    except Exception:
        return {
            "valid": False,
            "errors": ["Malformed header: YAML parse failed."],
            "header": {},
            "body": body,
        }

    if not isinstance(parsed, dict):
        return {
            "valid": False,
            "errors": ["Malformed header: YAML frontmatter is not a mapping."],
            "header": {},
            "body": body,
        }

    header = extract_lupopedia_headers(parsed)
    if header is None:
        return {
            "valid": False,
            "errors": ["Malformed header: lupopedia.headers block missing or invalid."],
            "header": {},
            "body": body,
        }

    return {"valid": True, "errors": [], "header": header, "body": body}


def extract_lupopedia_headers(parsed_yaml: Dict[str, Any]) -> Optional[Dict[str, Any]]:
    if "lupopedia.headers" in parsed_yaml:
        hdr = parsed_yaml.get("lupopedia.headers")
        return hdr if isinstance(hdr, dict) else None
    lupopedia = parsed_yaml.get("lupopedia")
    if isinstance(lupopedia, dict):
        hdr = lupopedia.get("headers")
        return hdr if isinstance(hdr, dict) else None
    return None


def validate_header(
    header: Dict[str, Any],
    actor_lookup: Optional[Dict[int, str]] = None,
) -> Dict[str, Any]:
    errors = []
    warnings = []

    if not isinstance(header, dict) or not header:
        return {"valid": False, "errors": ["Malformed header: expected non-empty mapping."], "warnings": []}

    for field in REQUIRED_FIELDS:
        if field not in header:
            errors.append("Missing required field: %s" % field)
        elif _is_empty_string(header.get(field)):
            errors.append("Required field is empty: %s" % field)

    for field in STRING_FIELDS:
        if field in header:
            val = header.get(field)
            if not isinstance(val, str) or val.strip() == "":
                errors.append("String field must be non-empty: %s" % field)

    if "last_modified_utc" in header and not _is_empty_string(header.get("last_modified_utc")):
        if not _is_bigint_compatible(header.get("last_modified_utc")):
            errors.append("Timestamp field must be BIGINT or numeric string: last_modified_utc")
    if "when_updated" in header and not _is_empty_string(header.get("when_updated")):
        if not _is_bigint_compatible(header.get("when_updated")):
            errors.append("Timestamp field must be BIGINT or numeric string: when_updated")

    for field in NUMERIC_FIELDS:
        if field in header and not _is_empty_string(header.get(field)):
            if not _is_numeric_id(header.get(field)):
                errors.append("ID field must be numeric: %s" % field)

    if "thread_id" in header and not _is_empty_string(header.get("thread_id")):
        if not _is_valid_thread_id(header.get("thread_id")):
            errors.append(
                "thread_id must be numeric or match slug pattern ^[a-z0-9][a-z0-9-]*$ (binding doctrine / PHP parity)"
            )

    if "version_when_written" in header:
        errors.append("Deprecated header field present: version_when_written (use when_updated)")

    if "file_path_from_root" in header and isinstance(header.get("file_path_from_root"), str):
        if not _is_valid_relative_path(header.get("file_path_from_root")):
            errors.append("Invalid file_path_from_root format.")

    if actor_lookup is not None:
        if _is_numeric_id(header.get("actor_id")) and isinstance(header.get("actor_name"), str):
            actor_id = int(str(header.get("actor_id")).strip())
            expected = actor_lookup.get(actor_id)
            if expected is not None and expected.strip() != header.get("actor_name").strip():
                errors.append("actor_id does not match actor_name.")

    if "content_id" not in header or header.get("content_id") is None or _is_empty_string(header.get("content_id")):
        warnings.append(
            "Missing content_id: artifact not linked to lupo_contents. "
            "Run: python lupo-scripts/import_content.py <file.md> "
            "then regenerate from DB with lupo-scripts/generate_headers_from_db.py."
        )
    elif not _is_numeric_id(header.get("content_id")):
        warnings.append("content_id must be numeric when present.")

    return {"valid": len(errors) == 0, "errors": errors, "warnings": warnings}


def _is_empty_string(value: Any) -> bool:
    return isinstance(value, str) and value.strip() == ""


def _is_bigint_compatible(value: Any) -> bool:
    if isinstance(value, bool):
        return False
    if isinstance(value, int):
        return value >= 0
    if isinstance(value, str):
        s = value.strip()
        return s.isdigit() and len(s) > 0
    return False


def _is_numeric_id(value: Any) -> bool:
    if isinstance(value, bool):
        return False
    if isinstance(value, int):
        return value >= 0
    if isinstance(value, str):
        s = value.strip()
        return s.isdigit() and len(s) > 0
    return False


def _is_valid_thread_id(value: Any) -> bool:
    """Match PHP HeaderDbSync: numeric thread id or slug ^[a-z0-9][a-z0-9-]*$."""
    if _is_numeric_id(value):
        return True
    if isinstance(value, str):
        s = value.strip()
        return re.match(r"^[a-z0-9][a-z0-9-]*$", s) is not None
    return False


def _is_valid_relative_path(path: str) -> bool:
    p = str(path).strip()
    if p == "":
        return False
    if "\\" in p:
        return False
    if ".." in p:
        return False
    if p.startswith("/"):
        return False
    if re.match(r"^[A-Za-z]:", p):
        return False
    if "//" in p:
        return False
    # Hyphens are common in repo dirs (lupo-rules, lupo-docs, …); align with real paths.
    return re.match(r"^[A-Za-z0-9_.\-/]+$", p) is not None