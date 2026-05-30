#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/lib/header_validation.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/lib/header_validation.py"
#   status: "complete"
#   when_updated: "20260415080730"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/header-validation.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/header-validation"
#   artifact_type: implementation
#   artifact_kind: library
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "16"
#   lupopedia.schema: implementation
#   title: "Shared LUPOPEDIA header parse and validate (Markdown import pipelines)"
#   summary: "parse_front_matter_header + validate_header; v4.1.0 keys via header_spec_v3_1"
# ---------------------------------------------------------------------
"""
Deterministic LUPOPEDIA header parser and validator for Markdown import pipelines.

``parse_front_matter_header`` returns:
  {"valid": bool, "errors": [...], "header": dict, "body": str}

``validate_header`` returns:
  {"valid": bool, "errors": [...], "warnings": [...]}

Validation aligns with PRD 16 v4.1.0 (22 scalar keys under ``lupopedia.headers``).
Stricter envelope checks (line counts, edges, memory_toon shape) live in
``validate_lupopedia_headers_universal.py``.
"""

from __future__ import annotations

import re
import sys
import os
from typing import Any, Dict, Optional

try:
    import yaml  # type: ignore
except Exception:  # pragma: no cover
    yaml = None

_SCRIPTS_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

from lib.header_spec_v3_1 import (
    V3_HEADER_KEYS_ORDERED,
    V3_HEADER_KEYS,
    VALID_LUPOPEDIA_SCHEMA_V3,
    V3_KEYS_ALLOW_EMPTY_VALUE,
    VALID_TRUST_TIERS,
    normalize_header_dict_for_validation,
)
from lib.lupopedia_markdown_header_peel import peel_leading_lupopedia_yaml_blocks

# Legacy int 3, string "3", or platform family 4.major.minor (parity with universal validator)
HEADER_FORMAT_VERSION_PATTERN = re.compile(r"^4\.\d+\.\d+$")

V3_REQUIRED = V3_HEADER_KEYS_ORDERED

# Non-empty string required when key is present (after normalization)
STRING_FIELDS = (
    "file_path_from_root",
    "web_path",
    "artifact_type",
    "artifact_kind",
)


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
    if len(lines) < 2:
        return {
            "valid": False,
            "errors": ["Malformed header: missing opening '---' delimiter."],
            "header": {},
            "body": text,
        }

    # PRD 16 v4.0.99 / legacy: peel first Lupopedia YAML block (dense or blank 23–24)
    if lines[0].strip() == "---" and "lupopedia.headers:" in text:
        inners, body_peeled = peel_leading_lupopedia_yaml_blocks(text)
        if inners and yaml is not None:
            try:
                parsed = yaml.safe_load(inners[0])
            except Exception:
                parsed = None
            if isinstance(parsed, dict):
                header = extract_lupopedia_headers(parsed)
                if header is not None:
                    body = body_peeled.lstrip("\n")
                    return {"valid": True, "errors": [], "header": header, "body": body}

    # Legacy delimiter-search parser (opening --- … closing ---)
    if lines[0].strip() != "---":
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


def inject_legacy_actor_from_author(header: Dict[str, Any]) -> None:
    """
    Legacy import helper: if a pre-v4 mapping embedded ``author`` and legacy ``actor_id`` /
    ``actor_name`` (not in PRD 16 §4.2 scalar grid), mirror author.id / author.name.

    v4.0.99 headers do not carry ``author`` under ``lupopedia.headers`` (sidecar only).
    """
    if not isinstance(header, dict):
        return
    author = header.get("author")
    if not isinstance(author, dict):
        return
    aid = author.get("id")
    if aid is None or _is_empty_string(aid):
        return
    aname = author.get("name")
    legacy_name = str(aname).strip() if aname is not None else ""
    if not legacy_name:
        legacy_name = str(aid).strip()

    if "actor_id" not in header or header.get("actor_id") is None or _is_empty_string(header.get("actor_id")):
        header["actor_id"] = aid
    if "actor_name" not in header or _is_empty_string(header.get("actor_name")):
        header["actor_name"] = legacy_name


def validate_header(
    header: Dict[str, Any],
    actor_lookup: Optional[Dict[int, str]] = None,
) -> Dict[str, Any]:
    errors = []
    warnings = []

    if not isinstance(header, dict) or not header:
        return {"valid": False, "errors": ["Malformed header: expected non-empty mapping."], "warnings": []}

    version = header.get("header_format_version")
    version_ok = False
    if isinstance(version, int):
        version_ok = version == 3
    elif isinstance(version, str):
        v = version.strip()
        version_ok = v == "3" or HEADER_FORMAT_VERSION_PATTERN.match(v) is not None
    if not version_ok:
        inject_legacy_actor_from_author(header)
        errors.append(
            "header_format_version must be legacy '3' or string 4.major.minor "
            "(HDR_VERSION_FAMILY); examples: 3, 4.0.99, 4.1.0"
        )
        return {"valid": len(errors) == 0, "errors": errors, "warnings": warnings}

    header = normalize_header_dict_for_validation(header)

    # atoms_toon (field 9, v4.1.0): null or path ending in .atoms.toon
    # module is accepted as a legacy alias (normalized to atoms_toon by normalize_header_dict_for_validation)
    if header.get("atoms_toon") == "":
        errors.append("atoms_toon must be YAML null when unused, not empty string (HDR_ATOMS_TOON_SUFFIX)")
    # Legacy compatibility: if module key survived normalization (unusual), warn
    if "module" in header:
        warnings.append("Deprecated field module found (HDR_MODULE_DEPRECATED): rename to atoms_toon per PRD 16 v4.0.99 §4.2 field 9")
    # Legacy compatibility: if dialog_transcript survived normalization, warn
    if "dialog_transcript" in header:
        warnings.append("Deprecated field dialog_transcript found (HDR_DIALOG_TRANSCRIPT_RENAMED): rename to transcript_jsonl per PRD 16 v4.1.0 §4.2 field 10")
    # Legacy compatibility: if memory_key survived normalization, warn
    if "memory_key" in header:
        warnings.append("Deprecated field memory_key found (HDR_MEMORY_KEY_RENAMED): rename to memory_toon per PRD 16 v4.1.0 §4.2 field 8")

    for field in V3_REQUIRED:
        if field not in header:
            errors.append("Missing required field: %s" % field)
            continue
        val = header.get(field)
        if field == "transcript_jsonl":
            if val is None or (isinstance(val, str) and not val.strip()):
                errors.append("transcript_jsonl required non-empty (§4.2 field 10)")
            continue
        if field == "content_id":
            continue
        if field == "pk_id":
            continue
        if field == "questions_toon":
            continue  # PRD 16 field 7: null or .questions.toon path
        if field in ("atoms_toon", "memory_toon"):
            continue
        if field in V3_KEYS_ALLOW_EMPTY_VALUE:
            continue
        if val is None:
            errors.append("Required field is null: %s" % field)
        elif isinstance(val, str) and not val.strip():
            errors.append("Required field is empty: %s" % field)

    actual_keys = list(header.keys())
    if actual_keys != list(V3_HEADER_KEYS_ORDERED):
        if set(actual_keys) != V3_HEADER_KEYS:
            extra = sorted(set(actual_keys) - V3_HEADER_KEYS)
            missing = sorted(V3_HEADER_KEYS - set(actual_keys))
            if extra:
                errors.append("Extra keys under lupopedia.headers (HDR_EXTRA_KEY): %s" % ", ".join(extra))
            if missing:
                errors.append("Missing keys (HDR_MISSING_KEY): %s" % ", ".join(missing))
        else:
            errors.append("Keys out of §4.2 canonical order (HDR_KEY_ORDER)")

    sch = str(header.get("lupopedia.schema", "")).strip()
    if sch not in VALID_LUPOPEDIA_SCHEMA_V3:
        errors.append("Invalid lupopedia.schema for v4 (HDR_SCHEMA_VALUE): %r" % sch)

    atype = str(header.get("artifact_type", "")).strip()
    # PRD 16 §19.6 — schema vs artifact_type mismatch is optional / review signal, not a hard fail here
    if sch and atype and sch != atype:
        warnings.append(
            "lupopedia.schema (%r) differs from artifact_type (%r) (HDR_SCHEMA_ARTIFACT_MISMATCH; optional check)"
            % (sch, atype)
        )

    tier = str(header.get("trust_tier", "")).strip()
    if tier not in VALID_TRUST_TIERS:
        errors.append(
            "trust_tier must be one of %s (HDR_TRUST_TIER_INVALID), got %r"
            % (", ".join(sorted(VALID_TRUST_TIERS)), tier)
        )

    if atype != "prd":
        pid_raw = header.get("pk_id")
        if pid_raw is not None and not (isinstance(pid_raw, str) and pid_raw.strip().lower() == "null"):
            errors.append("non-prd headers must use pk_id null (§4.2), got %r" % pid_raw)
    else:
        # PRD ordinal: legacy pk_id or content_id (v4.1.1+), else derive from PRD filename (e.g. 16_*.md).
        pid = None
        for key in ("pk_id", "content_id"):
            v = header.get(key)
            if v is not None and str(v).strip() != "" and str(v).strip().lower() != "null":
                try:
                    pid = int(v)
                    break
                except (TypeError, ValueError):
                    pass
        if pid is None:
            fpr = str(header.get("file_path_from_root", "")).replace("\\", "/")
            m = re.search(r"/(\d+)[^/]*\.md$", fpr)
            if m:
                try:
                    pid = int(m.group(1))
                except ValueError:
                    pid = None
        if pid is None:
            errors.append(
                "prd headers must use numeric pk_id or content_id (0..n), or a PRD-numbered filename "
                "(HDR_PRD_NUMERIC_ID)"
            )
        elif pid < 0:
            errors.append("prd headers cannot use negative id, got %s" % pid)

    for k, v in header.items():
        if isinstance(v, (list, dict)):
            errors.append("Value for %r must be scalar, not list/dict (HDR_ARRAY)" % k)
        elif isinstance(v, str) and ("\n" in v or "\r" in v):
            errors.append("Multiline value in %r (HDR_MULTILINE)" % k)

    if "version" in header:
        errors.append("Obsolete field 'version' under lupopedia.headers (HDR_VERSION_FIELD_REMOVED)")

    for field in STRING_FIELDS:
        if field in header:
            val = header.get(field)
            if not isinstance(val, str) or val.strip() == "":
                errors.append("String field must be non-empty: %s" % field)

    # Backward compat: validate legacy last_modified_utc if still present.
    # Field was renamed to questions_toon in PRD 16 v4.0.99 §4.2 field 6.
    # REMOVE this block after Phase 3 corpus sweep (HDR_LAST_MODIFIED_RENAMED).
    if "last_modified_utc" in header and not _is_empty_string(header.get("last_modified_utc")):
        if not _is_bigint_compatible(header.get("last_modified_utc")):
            errors.append("Timestamp field must be BIGINT or numeric string: last_modified_utc")
        errors.append(
            "Deprecated field last_modified_utc found (HDR_LAST_MODIFIED_RENAMED): "
            "rename to questions_toon per PRD 16 v4.0.99 §4.2 field 6"
        )

    # atoms_toon (field 21, v4.0.99+): null or path ending in .atoms.toon
    # validate_atoms_toon() is also callable standalone.
    at_errors, at_warnings = validate_atoms_toon(header.get("atoms_toon"))
    errors.extend(at_errors)
    warnings.extend(at_warnings)

    # questions_toon (field 6, v4.0.99+): null or path ending in .questions.toon
    qt = header.get("questions_toon")
    if qt is not None:
        if not isinstance(qt, str):
            errors.append("questions_toon must be a string path or null (HDR_QUESTIONS_TOON_SUFFIX)")
        elif not qt.strip().endswith(".questions.toon"):
            errors.append(
                "questions_toon path must end with .questions.toon (HDR_QUESTIONS_TOON_SUFFIX), "
                "got %r" % qt
            )
        mk = header.get("memory_toon")
        if mk is not None and qt == mk:
            errors.append(
                "questions_toon must not equal memory_toon (HDR_QUESTIONS_TOON_COLLISION)"
            )

    if "when_updated" in header and not _is_empty_string(header.get("when_updated")):
        if not _is_bigint_compatible(header.get("when_updated")):
            errors.append("Timestamp field must be BIGINT or numeric string: when_updated")

    at_thread = str(header.get("artifact_type", "")).strip()
    tid_raw = header.get("thread_id")
    if at_thread == "prd":
        if tid_raw is not None and str(tid_raw).strip() != "":
            errors.append("artifact_type prd requires thread_id '' (§4.2.1)")
    elif at_thread == "discussion":
        if tid_raw is None or str(tid_raw).strip() == "":
            errors.append("discussion requires non-empty thread_id")
        elif not _is_valid_thread_id(tid_raw):
            errors.append("thread_id must match ^[a-z0-9][a-z0-9-]*$ (discussion)")
    elif tid_raw is not None and str(tid_raw).strip() != "" and not _is_valid_thread_id(tid_raw):
        errors.append("thread_id must be '' or match slug pattern ^[a-z0-9][a-z0-9-]*$")

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

    return {"valid": len(errors) == 0, "errors": errors, "warnings": warnings}


# atoms_toon suffix constant (PRD 16 §4.2 field 21)
ATOMS_TOON_SUFFIX = ".atoms.toon"


def validate_atoms_toon(value: Any):
    """
    Validate the atoms_toon field (PRD 16 §4.2 field 21).

    Rules (PRD 16 v4.0.99):
      - null is always valid (field may be absent / not yet set)
      - non-null MUST be a string ending in .atoms.toon
      - empty string '' is forbidden (use null)
      - file existence is NOT checked

    Returns (errors: list, warnings: list).
    """
    errors = []
    warnings = []
    if value is None:
        return errors, warnings
    if not isinstance(value, str):
        errors.append(
            "atoms_toon must be a string path ending in .atoms.toon or null "
            "(HDR_ATOMS_TOON_SUFFIX), got type %s" % type(value).__name__
        )
        return errors, warnings
    if value.strip() == "":
        errors.append(
            "atoms_toon must be YAML null when unused, not empty string (HDR_ATOMS_TOON_SUFFIX)"
        )
        return errors, warnings
    s = value.strip()
    # Global atom singleton uses .atom.toon; scoped atoms use .atoms.toon (PRD 16 / global constants).
    if not (s.endswith(ATOMS_TOON_SUFFIX) or s.endswith(".atom.toon")):
        errors.append(
            "atoms_toon path must end with .atoms.toon or .atom.toon (HDR_ATOMS_TOON_SUFFIX), got %r" % value
        )
    return errors, warnings


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
    return re.match(r"^[A-Za-z0-9_.\-/]+$", p) is not None
