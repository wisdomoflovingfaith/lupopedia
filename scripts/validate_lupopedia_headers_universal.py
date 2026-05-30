#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.4"
#   file_path_from_root: "scripts/validate_lupopedia_headers_universal.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/validate_lupopedia_headers_universal.py"
#   status: "active"
#   when_updated: "20260422232349"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/validate-lupopedia-headers-universal.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/validate-lupopedia-headers-universal"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   default_collection_id: null
#   lupopedia.schema: implementation
#   prd_cluster: 00_A_16_C
#   title: "Universal Lupopedia header validator (PRD 16 / v4.1.x patch family, aligned to 4.1.4)"
#   summary: "PRD 16 v4.1.4-aligned: validate dense 22-key Markdown, Python, PHP, JS headers (format-aware line counts); detect orphan states and support ANUBIS repair workflows."
# ---------------------------------------------------------------------
"""
PRD 16 v4.1.9 — §4.2 / §4.3 universal validator for Markdown and comment-embedded LUPOPEDIA HEADERS (Python, PHP, JS).

Enforced in-tree today (non-exhaustive): header_format_version exactly 4.1.9;
required **22-key** dense grid (PRD 16 v4.1.9); trust_tier; thread_id rules; transcript_jsonl (≥3 segments,
first = federation_node_id; WARN if >3 segments — normative triple; legacy dialog_transcript REJECTED in v4.1.4);
memory_toon .toon suffix and path shape (legacy memory_key REJECTED in v4.1.4);
optional strict JSON↔TOON sibling check (--strict-memory-pair / --strict);
optional on-disk .toon presence for declared memory_toon (--strict-memory-files; WARN by default);
optional §8.1 canonical year segment (--strict-memory-year); HTTPS web_path for http://
URLs unless --development; Markdown line 26 / Python PHP JS first body line after block (HDR_EMPTY_BODY);
lupopedia.footer last_verified length (14-digit UTC preferred over 8-digit day-only).
No v1/v2 header family support. See PRD 16 §12 / §19 for error codes.

ANUBIS integration: Detect orphan states (content_id null/missing/invalid-link) and support
ANUBIS repair flow. Queue-based workflows are non-canonical proposal paths and should only be
used when explicitly enabled/ratified.
"""

from __future__ import annotations

import argparse
import glob
import hashlib
import json
import re
import os
import sys
from datetime import datetime, timezone

# Allow `from db_config import ...` when run as `python scripts/this_script.py`
_SCRIPTS_DIR = os.path.dirname(os.path.abspath(__file__))
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

# PRD 16 v4.1.9 - Import canonical 22-field header specification from shared spec
from lib.header_spec_v3_1 import (
    V4_HEADER_KEYS_ORDERED,
    V3_HEADER_KEYS,
    EXPECTED_HEADER_FORMAT_VERSION,
    EXPECTED_HEADER_FIELD_COUNT,
    REMOVED_HEADER_FIELDS_V419,
    validate_header_v419,
    validate_header_format_version_exact,
    validate_header_field_count_and_order,
    validate_deprecated_header_fields,
    is_exact_header_format_version,
    requires_v419_rules,
    header_format_patch_level,
)

# Backward-compatible import name
# Note: V3_* naming refers to the 4.1.4 canonical set (header family name), not version 3
V3_HEADER_KEYS_ORDERED = V4_HEADER_KEYS_ORDERED
V3_HEADER_KEYS = frozenset(V4_HEADER_KEYS_ORDERED)

# PRD 16 v4.1.4 - Schema validation (current canonical values)
VALID_LUPOPEDIA_SCHEMA_V4 = frozenset((
    "prd", "doctrine", "documentation", "implementation", 
    "version-doc", "status",
))

# PRD 16 v4.1.4 - Legacy schema values (deprecated for new headers)
LEGACY_LUPOPEDIA_SCHEMA_VALUES = frozenset((
    "discussion", "changelog", "architecture", "specification",
))

# Backward compatibility - include legacy values for existing headers
VALID_LUPOPEDIA_SCHEMA_WITH_LEGACY = VALID_LUPOPEDIA_SCHEMA_V4 | LEGACY_LUPOPEDIA_SCHEMA_VALUES  # v4.1.4 canonical + legacy compatibility layer
# Deprecated V3 alias - use VALID_LUPOPEDIA_SCHEMA_WITH_LEGACY instead
VALID_LUPOPEDIA_SCHEMA_V3 = VALID_LUPOPEDIA_SCHEMA_WITH_LEGACY  # DEPRECATED: use WITH_LEGACY

# PRD 16 v4.1.4 - Artifact type validation (current canonical values)
ARTIFACT_TYPE_ALLOWED_KINDS_V4 = {
    "prd": frozenset(("requirements", "architecture", "guide")),  # specification removed per drift correction
    "implementation": frozenset(("README", "documentation", "authors", "edges", "tool", "library", "service")),
    "doctrine": frozenset(("constitutional", "reference", "decisions")),
    "version-doc": frozenset(("version_specific",)),
    "status": frozenset(("report", "debug", "summary")),
}

# PRD 16 v4.1.4 - Legacy artifact types (deprecated for new headers)
ARTIFACT_TYPE_ALLOWED_KINDS_LEGACY = {
    "discussion": frozenset(("thread", "message")),
    "changelog": frozenset(("version_specific",)),
    "documentation": frozenset(("table_schema", "guide")),
    "architecture": frozenset(("system", "data_model")),
    "specification": frozenset(("technical", "api", "protocol")),
}

# Backward compatibility - include legacy types for existing headers
ARTIFACT_TYPE_ALLOWED_KINDS = {**ARTIFACT_TYPE_ALLOWED_KINDS_V4, **ARTIFACT_TYPE_ALLOWED_KINDS_LEGACY}
VALID_ARTIFACT_TYPES = frozenset(ARTIFACT_TYPE_ALLOWED_KINDS.keys())

# PRD 16 v4.1.4 - Field validation rules
V3_KEYS_ALLOW_EMPTY_VALUE = frozenset(("thread_key", "title", "status", "summary"))
VALID_TRUST_TIERS = frozenset(("canonical", "development"))
LEGACY_TRUST_TIERS = frozenset(("seed", "staging", "archive"))

# PRD 16 v4.1.4 - Suffix and year constants
ATOMS_TOON_SUFFIX = ".atoms.toon"
CANONICAL_YEAR = "1026"

# PRD 16 v4.1.4 - Legacy field handling (REMOVED fields map to error states)
LEGACY_FIELD_ALIASES = {
    "thread_id": "thread_key",
    "file_path_from_root": "path_from_lupopedia_root",
    "prd_id": "REMOVED_content_id",
    "prd_slug": "REMOVED_content_slug",
    "parent_prd": "REMOVED_content_parent_id",
    "pk_id": "REMOVED_content_id",
    "pk_slug": "REMOVED_content_slug",
    "content_id": "REMOVED_content_id",
    "content_parent_id": "REMOVED_content_parent_id",
    "default_collection_id": "REMOVED_default_collection_id",
    "content_slug": "REMOVED_content_slug",
    "parent_pk_id": "content_parent_id",
    "last_modified_utc": "questions_toon",
    "module": "atoms_toon",
    "memory_key": "REMOVED_memory_key",       # REMOVED in v4.1.4
    "dialog_transcript": "REMOVED_dialog_transcript",  # REMOVED in v4.1.4
}

def line_key_to_canonical(key: str) -> str:
    """Normalize a YAML key as written in the file (legacy aliases → canonical key)."""
    return LEGACY_FIELD_ALIASES.get(key, key)

def normalize_header_dict_for_validation(hdr: dict):
    """Normalize header dict for validation using v4.1.4 canonical order."""
    from collections import OrderedDict
    
    # Apply legacy aliases (will be rejected if they map to REMOVED_* fields)
    out = dict(hdr)
    for old, new in LEGACY_FIELD_ALIASES.items():
        if old in out:
            if new.startswith("REMOVED_"):
                # Keep the field for validation to reject it properly
                continue
            if new not in out:
                out[new] = out[old]
            del out[old]
    
    # Build OrderedDict in canonical order
    od = OrderedDict()
    for k in V4_HEADER_KEYS_ORDERED:
        if k in out:
            od[k] = out[k]
        elif k == "summary":
            od[k] = ""
        elif k in ("atoms_toon", "memory_toon", "edges_toon", "source_timestamp"):
            od[k] = None
        elif k == "channel_index":
            od[k] = "lupopedia"
        elif k in V3_KEYS_ALLOW_EMPTY_VALUE:
            od[k] = ""
        else:
            od[k] = out.get(k)
    return od

# Legacy fallback removed - fake 20-field slice was not historical
from lib.lupopedia_markdown_header_peel import peel_leading_lupopedia_yaml_blocks

try:
    import yaml
except ImportError:
    yaml = None  # type: ignore

# Canonical staleness cutoff (YYYYMMDD)
CUTOFF_DAY = 20260301  # 2026-03-01
CUTOFF_14 = int(f"{CUTOFF_DAY}000000")

# v4.1.4 required keys = full §4.2 order (22 scalars; alias import name V4_HEADER_KEYS_ORDERED)
REQUIRED_HEADER_KEYS = V3_HEADER_KEYS_ORDERED

# ASCII-safe filename and slug patterns
ASCII_FILENAME_PATTERN = re.compile(r'^[a-z0-9_.-]+$')
ASCII_SLUG_PATTERN = re.compile(r'^[a-z0-9-]+$')

# thread_id: lowercase, hyphens (and digits); e.g. headers-doctrine, 4.0.89-planning
THREAD_ID_PATTERN = re.compile(r"^[a-z0-9][a-z0-9-]*$")

# UTC YmdHis in headers (string or int from YAML)
YMDHIS_PATTERN = re.compile(r"^\d{14}$")
# Allow 4.0.99+ (transitional) and planning docs that declare 4.1.x; still three-part 4.major.minor.
HEADER_FORMAT_VERSION_PATTERN = re.compile(r"^4\.\d+\.\d+$")
# Escapes only: literal box chars in source would self-trigger HDR_UNICODE_BOX on this file.
UNICODE_BOX_RE = re.compile(
    r"[\u250c\u2510\u2514\u2518\u251c\u2524\u252c\u2534\u253c\u2502\u2500]"
)
MOJIBAKE_BOX_RE = re.compile(r"â”œ|â”€|â””|â”‚|â”|â”ƒ|â”|â”|â”•|â”˜|â”Ÿ|â”¥|â”¬|â”´|â”¼")

# PRD 16 §12.1.2 canonical identity resolution states
RESOLUTION_STATE_FILE_FIRST = "file-first"
RESOLUTION_STATE_DATABASE_FIRST = "database-first"
RESOLUTION_STATE_REPAIR = "repair"

# Python comment-embedded header (PRD 16 / tooling): # ----- ... # -----
PY_HEADER_SEP_LINE_RE = re.compile(r"^\s*#\s*-{10,}\s*$")
CANONICAL_PYTHON_SHEBANG = "#!/usr/bin/env python3"
CANONICAL_PHP_SHEBANG = "#!/usr/bin/env php"
# Tooling mistake: ``/**`` then ``lupopedia.headers:`` on the next line without `` * `` leader (pre-v4 smell).
_PHP_LEGACY_INLINE_YAML_RE = re.compile(
    r"/\*\*\s*\r?\n(?!\s*\*)\s*lupopedia\.headers\s*:",
    re.MULTILINE,
)


def get_current_timestamp():
    """Get current UTC timestamp in YYYYMMDDHHIISS format"""
    now = datetime.now(timezone.utc)
    return now.strftime("%Y%m%d%H%M%S")

def get_file_hash(file_path):
    """Compute SHA-256 hash of a file"""
    sha256_hash = hashlib.sha256()
    try:
        with open(file_path, "rb") as f:
            for byte_block in iter(lambda: f.read(4096), b""):
                sha256_hash.update(byte_block)
        return sha256_hash.hexdigest()
    except Exception:
        return None

def validate_schema(schema, file_path, header_version=None, strict_mode=False):
    """PRD 16 §4.2 field 2 — closed lupopedia.schema enum.
    
    Note: Non-strict mode allows legacy schema values with warnings for v4.1.4+ headers
    as transitional behavior to support existing files during migration.
    """
    sch = str(schema or "").strip()
    
    # For v4.1.4+ headers, restrict to current canonical values
    if header_version and header_version >= (4, 1, 4):
        valid_schemas = VALID_LUPOPEDIA_SCHEMA_V4
        if sch in LEGACY_LUPOPEDIA_SCHEMA_VALUES:
            if strict_mode:
                print(
                    "[ERROR] %s: lupopedia.schema %r is legacy value; rejected in strict mode for v4.1.4 headers (HDR_SCHEMA_LEGACY_STRICT)"
                    % (file_path, sch)
                )
                print("   Current v4.1.4 values: %s" % (", ".join(sorted(VALID_LUPOPEDIA_SCHEMA_V4)),))
                return False
            else:
                print(
                    "[WARN] %s: lupopedia.schema %r is legacy value; use current v4.1.4 values for new headers (HDR_SCHEMA_LEGACY)"
                    % (file_path, sch)
                )
                print("   Current v4.1.4 values: %s" % (", ".join(sorted(VALID_LUPOPEDIA_SCHEMA_V4)),))
                return True  # Allow but warn for compatibility
        if sch not in valid_schemas:
            print(
                "[ERROR] %s: lupopedia.schema %r not in PRD 16 v4.1.4 closed set (HDR_SCHEMA_VALUE)"
                % (file_path, sch)
            )
            print("   Expected one of: %s" % (", ".join(sorted(valid_schemas)),))
            return False
    else:
        # Legacy validation for pre-4.1.4 headers
        if sch not in VALID_LUPOPEDIA_SCHEMA_V3:
            print(
                "[ERROR] %s: lupopedia.schema %r not in PRD 16 §4.2 closed set (HDR_SCHEMA_VALUE)"
                % (file_path, sch)
            )
            print("   Expected one of: %s" % (", ".join(sorted(VALID_LUPOPEDIA_SCHEMA_V3)),))
            return False
    return True

def validate_cross_fields(schema, artifact_type, artifact_kind, file_path, header_version=None, strict_mode=False):
    """PRD 16 §12.3 — artifact_type ↔ artifact_kind (schema must match §4.2 closed enum)."""
    sch = str(schema or "").strip()
    at = str(artifact_type or "").strip()
    ak = str(artifact_kind or "").strip()
    
    # Validate schema against appropriate set based on version
    if header_version and header_version >= (4, 1, 4):
        if sch not in VALID_LUPOPEDIA_SCHEMA_V4:
            print(
                "[ERROR] %s: lupopedia.schema %r not in PRD 16 v4.1.4 closed set (HDR_SCHEMA_VALUE)"
                % (file_path, sch)
            )
            print("   Expected one of: %s" % (", ".join(sorted(VALID_LUPOPEDIA_SCHEMA_V4)),))
            return False
        # For v4.1.4+, restrict to current artifact types
        if at not in ARTIFACT_TYPE_ALLOWED_KINDS_V4:
            if strict_mode:
                print(
                    "[ERROR] %s: artifact_type %r is legacy; rejected in strict mode for v4.1.4 headers (HDR_ARTIFACT_TYPE_LEGACY_STRICT)"
                    % (file_path, at)
                )
                print("   Current v4.1.4 types: %s" % (", ".join(sorted(ARTIFACT_TYPE_ALLOWED_KINDS_V4.keys())),))
                return False
            else:
                print(
                    "[WARN] %s: artifact_type %r is legacy; use current v4.1.4 types for new headers (HDR_ARTIFACT_TYPE_LEGACY)"
                    % (file_path, at)
                )
                print("   Current v4.1.4 types: %s" % (", ".join(sorted(ARTIFACT_TYPE_ALLOWED_KINDS_V4.keys())),))
                # Allow but continue with legacy validation for compatibility
    else:
        # Legacy validation for pre-4.1.4 headers
        if sch not in VALID_LUPOPEDIA_SCHEMA_V3:
            print(
                "[ERROR] %s: lupopedia.schema %r not in PRD 16 §4.2 closed set (HDR_SCHEMA_VALUE)"
                % (file_path, sch)
            )
            print("   Expected one of: %s" % (", ".join(sorted(VALID_LUPOPEDIA_SCHEMA_V3)),))
            return False
    
    if at not in VALID_ARTIFACT_TYPES:
        print(
            "[ERROR] %s: artifact_type %r not in PRD 16 cross-field table (HDR_ARTIFACT_TYPE)"
            % (file_path, at)
        )
        print("   Expected one of: %s" % (", ".join(sorted(VALID_ARTIFACT_TYPES)),))
        return False
    
    # Use appropriate allowed kinds based on header version and strict mode
    if header_version and header_version >= (4, 1, 4):
        allowed = ARTIFACT_TYPE_ALLOWED_KINDS_V4.get(at)
    else:
        allowed = ARTIFACT_TYPE_ALLOWED_KINDS.get(at)
    
    if allowed is not None and ak not in allowed:
        print(
            "[ERROR] %s: artifact_kind %r not allowed for artifact_type %r (PRD 16 cross-field)"
            % (file_path, ak, at)
        )
        print("   Allowed kinds: %s" % (", ".join(sorted(allowed)),))
        return False
    
    if sch != at:
        print(
            "[ERROR] %s: lupopedia.schema (%r) MUST equal artifact_type (%r) (HDR_SCHEMA_ARTIFACT_MISMATCH)"
            % (file_path, sch, at)
        )
        return False
    return True


def _try_extract_v3_md_inner_yaml_block(content: str):
    """
    If content matches PRD 16 dense Markdown envelope (lupopedia.headers + N keys + closing ---),
    return the inner YAML string (``lupopedia.headers:`` + key lines) for safe_load.
    N defaults to len(V4_HEADER_KEYS_ORDERED) (atom-backed 19-field contract).

    Legacy v4.0.0–98: two blank lines before closing --- (20 keys on lines 3–22).
    Otherwise return None (use legacy first-block extraction).
    """
    content = content.replace("\r\n", "\n")
    if not content.startswith("---\n"):
        return None
    lines = content.split("\n")
    n = len(V4_HEADER_KEYS_ORDERED)
    min_dense = n + 3
    if len(lines) < min_dense:
        return None
    if lines[0].strip() != "---" or lines[1].strip() != "lupopedia.headers:":
        return None
    # Dense: N keys on lines 3..(N+2) (indices 2..(N+1)), closing --- at index N+2
    dense_ok = lines[n + 2].strip() == "---"
    if dense_ok:
        for i in range(2, 2 + n):
            if i >= len(lines) or not lines[i].strip():
                dense_ok = False
                break
    if dense_ok:
        return "\n".join(lines[1 : 2 + n])
    # Legacy: keys on lines 3–22, 23–24 blank, 25 = ---
    if len(lines) < 25:
        return None
    for i in range(2, 22):
        if i >= len(lines) or not lines[i].strip():
            return None
    if lines[22].strip() or lines[23].strip() or lines[24].strip() != "---":
        return None
    return "\n".join(lines[1:22])


def parse_front_matter_yaml(content):
    """
    Parse first YAML front matter block (between --- lines).
    Returns (data_dict, None) on success, (None, error_message) on failure.
    """
    if yaml is None:
        return None, "PyYAML is not installed"
    content = content.replace("\r\n", "\n")
    block = _try_extract_v3_md_inner_yaml_block(content)
    if block is None:
        if not content.startswith("---\n"):
            return None, "Front matter must start with ---"
        end = content.find("\n---\n", 4)
        if end < 0:
            return None, "Missing closing --- for YAML header"
        block = content[4:end]
    try:
        data = yaml.safe_load(block)
    except Exception as e:
        return None, "YAML parse failed: %s" % (e,)
    if not isinstance(data, dict):
        return None, "Front matter must be a YAML mapping"
    return data, None


_V3_HEADER_KEY_LINE_RE = re.compile(r"^\s*([a-zA-Z][a-zA-Z0-9_.]*)\s*:")


def _parse_v3_scalar_key_from_md_line(line):
    m = _V3_HEADER_KEY_LINE_RE.match(line)
    return m.group(1) if m else None


_HFV_SCALAR_LINE_RE = re.compile(r"^\s*header_format_version:\s*(.+?)\s*$")


def _parse_header_format_version_tuple_from_line(line):
    """
    Parse first key line (e.g. line 3 Markdown / Python) for 4.0.x → (4, 0, patch).
    Returns None for legacy ``3``, unknown strings, or unparseable lines.
    """
    if line is None or not str(line).strip():
        return None
    m = _HFV_SCALAR_LINE_RE.match(line)
    if not m:
        return None
    raw = m.group(1).strip()
    if (raw.startswith('"') and raw.endswith('"')) or (raw.startswith("'") and raw.endswith("'")):
        raw = raw[1:-1].strip()
    if raw == "3":
        return None
    mm = re.match(r"^4\.(\d+)\.(\d+)$", raw)
    if not mm:
        return None
    return (4, int(mm.group(1)), int(mm.group(2)))


def _hf_tuple_requires_dense_v4099(tup):
    """When True, legacy blank lines 23–24 are invalid (PRD 16 v4.1.4+)."""
    if tup is None:
        return False
    return tup >= (4, 0, 99)


def _print_md_envelope_missing_v3_keys_hint(lines, file_path):
    """
    When Markdown line-count / envelope checks fail, list missing §4.2 keys from lines 3–24.
    """
    if len(lines) < 3:
        return
    keys_found = []
    for i in range(2, min(24, len(lines))):
        ln = lines[i]
        if not ln.strip():
            break
        k = _parse_v3_scalar_key_from_md_line(ln)
        if k is not None:
            keys_found.append(line_key_to_canonical(k))
    found_set = set(keys_found)
    if found_set == V3_HEADER_KEYS:
        return
    missing = sorted(V3_HEADER_KEYS - found_set)
    if not missing:
        return
    print(
        "[HINT] %s: expected 22 scalar keys on lines 3-24 under lupopedia.headers:; missing: %s — "
        "a short key block shifts the line-25 closing --- and triggers HDR_LINE_COUNT / HDR_MISSING_CLOSE "
        "(fix keys first; see HDR_MISSING_KEY)"
        % (file_path, ", ".join(missing))
    )


def _print_py_envelope_missing_v3_keys_hint(header_slice, file_path):
    """
    When Python envelope checks fail, list missing §4.2 keys from the # comment key block.
    """
    if len(header_slice) < 24:
        return
    py_leg = not header_slice[22].strip() and not header_slice[23].strip()
    inner = header_slice[2:22] if py_leg else header_slice[2:24]
    keys_found = []
    for ln in inner:
        rest = _strip_python_hash_comment_line(ln)
        if rest is None:
            return
        k = _parse_v3_scalar_key_from_md_line(rest)
        if k is not None:
            keys_found.append(line_key_to_canonical(k))
    found_set = set(keys_found)
    if found_set == V3_HEADER_KEYS:
        return
    missing = sorted(V3_HEADER_KEYS - found_set)
    if not missing:
        return
    print(
        "[HINT] %s: expected 22 scalar keys after lupopedia.headers: in python header; missing: %s — "
        "short key block triggers HDR_PYTHON_LINE_COUNT / HDR_PYTHON_HEADER"
        % (file_path, ", ".join(missing))
    )


def _markdown_header_physical_keys(lines):
    """Parse scalar key names from Markdown lines 3..(2+N) under lupopedia.headers:."""
    n = EXPECTED_HEADER_FIELD_COUNT
    keys = []
    for i in range(2, 2 + n):
        if i >= len(lines):
            break
        if not lines[i].strip():
            break
        rk = _parse_v3_scalar_key_from_md_line(lines[i])
        if rk is not None:
            keys.append(rk)
    return keys


def validate_markdown_header_key_count(lines, file_path):
    """Mechanical field count on physical YAML lines (22 keys required for 4.1.9)."""
    keys = _markdown_header_physical_keys(lines)
    n = EXPECTED_HEADER_FIELD_COUNT
    if len(keys) == n:
        return True
    print(
        "[ERROR] %s: Header field count mismatch: expected %d."
        % (file_path, n)
    )
    if len(keys) < n:
        missing = []
        expected = list(V4_HEADER_KEYS_ORDERED)
        seen_canon = {line_key_to_canonical(k) for k in keys}
        for ek in expected:
            if ek not in seen_canon:
                missing.append(ek)
        if missing:
            print(
                "   Missing required field(s): %s (HDR_MISSING_KEY)"
                % (", ".join(missing),)
            )
    else:
        print(
            "   Found %d key line(s) in header block (HDR_EXTRA_KEY)"
            % (len(keys),)
        )
    return False


def validate_markdown_mechanical_key_line_order(lines, file_path, legacy_20):
    """Mechanical key order on physical lines (canonical 4.1.9 names only)."""
    n = EXPECTED_HEADER_FIELD_COUNT
    expected_canon = list(V4_HEADER_KEYS_ORDERED)
    idxs = list(range(2, 2 + n))
    for pos, i in enumerate(idxs):
        if i >= len(lines):
            print(
                "[ERROR] %s: line %d missing (HDR_KEY_ORDER)"
                % (file_path, i + 1)
            )
            return False
        rk = _parse_v3_scalar_key_from_md_line(lines[i])
        if rk is None:
            print(
                "[ERROR] %s: line %d: could not parse key (HDR_KEY_ORDER)"
                % (file_path, i + 1)
            )
            return False
        if _mechanical_key_deprecated_error(file_path, rk):
            return False
        ck = line_key_to_canonical(rk)
        if ck == expected_canon[pos]:
            continue
        print("[ERROR] %s: Header fields out of canonical order." % (file_path,))
        print(
            "   line %d: key %r (canonical %r), expected %r (HDR_KEY_ORDER)"
            % (i + 1, rk, ck, expected_canon[pos])
        )
        return False
    return True


def validate_markdown_header_line_count(
    content,
    file_path,
    development_mode=False,
    reject_legacy_envelope=False,
):
    """
    PRD 16 dense Markdown envelope: opening ---, ``lupopedia.headers:``, N key lines,
    closing ---, then body. N = len(V4_HEADER_KEYS_ORDERED) (19-field atom contract).

    Legacy (v4.0.0–98): 20 keys + blank lines before closing --- (HDR_LEGACY_ENVELOPE).
    """
    if not content.startswith("---\n"):
        return True

    lines = content.splitlines()
    n = len(V4_HEADER_KEYS_ORDERED)
    min_lines_dense = n + 4
    if len(lines) < min_lines_dense:
        print(
            "[ERROR] %s: header envelope must include at least %d lines (HDR_LINE_COUNT), got %s"
            % (file_path, min_lines_dense, len(lines))
        )
        _print_md_envelope_missing_v3_keys_hint(lines, file_path)
        return False

    if lines[0].strip() != "---":
        print("[ERROR] %s: line 1 must be opening --- (HDR_LINE_COUNT)" % (file_path,))
        _print_md_envelope_missing_v3_keys_hint(lines, file_path)
        return False

    if lines[1].strip() != "lupopedia.headers:":
        print(
            "[ERROR] %s: line 2 must be lupopedia.headers: (HDR_LINE_COUNT)"
            % (file_path,)
        )
        _print_md_envelope_missing_v3_keys_hint(lines, file_path)
        return False

    if lines[n + 2].strip() != "---":
        print(
            "[ERROR] %s: line %d must be closing --- (HDR_MISSING_CLOSE)"
            % (file_path, n + 3)
        )
        _print_md_envelope_missing_v3_keys_hint(lines, file_path)
        return False

    hf_t = _parse_header_format_version_tuple_from_line(lines[2])
    legacy_20 = (not lines[22].strip() and not lines[23].strip()) if len(lines) > 23 else False

    if legacy_20 and len(lines) >= 25:
        for i in range(2, 22):
            if not lines[i].strip():
                print(
                    "[ERROR] %s: blank line inside key block lines 3-22 (HDR_HEADER_INTERNAL_BLANK)"
                    % (file_path,)
                )
                _print_md_envelope_missing_v3_keys_hint(lines, file_path)
                return False
        if _hf_tuple_requires_dense_v4099(hf_t):
            print(
                "[ERROR] %s: header_format_version >= 4.0.99 requires dense %d-key envelope "
                "(HDR_HEADER_INTERNAL_BLANK)"
                % (file_path, n)
            )
            _print_md_envelope_missing_v3_keys_hint(lines, file_path)
            return False
        if reject_legacy_envelope:
            print(
                "[ERROR] %s: legacy v4.0.0 header envelope (blank lines 23-24) rejected "
                "(HDR_LEGACY_ENVELOPE); use --reject-legacy-envelope off during migration, or "
                "run normalize_lupopedia_md_header_25.py / batch_validate_prd_headers.py --migrate-legacy"
                % (file_path,)
            )
            return False
        print(
            "[WARN]  %s: legacy header envelope (blank lines 23-24); "
            "run normalize_lupopedia_md_header_25.py or batch_validate_prd_headers.py --migrate-legacy "
            "(HDR_LEGACY_ENVELOPE)"
            % (file_path,)
        )
    else:
        for i in range(2, 2 + n):
            if not lines[i].strip():
                print(
                    "[ERROR] %s: blank line inside key block lines 3-%d (HDR_HEADER_INTERNAL_BLANK)"
                    % (file_path, n + 2)
                )
                _print_md_envelope_missing_v3_keys_hint(lines, file_path)
                return False

    if not validate_markdown_header_key_count(lines, file_path):
        return False

    if not validate_markdown_mechanical_key_line_order(lines, file_path, legacy_20):
        return False

    if len(lines) < n + 4:
        if development_mode:
            print(
                "[WARN]  %s: no body after header (HDR_EMPTY_BODY) — allowed under --development"
                % (file_path,)
            )
            return True
        print(
            "[ERROR] %s: no body after header — file must extend past line %d (HDR_EMPTY_BODY)"
            % (file_path, n + 3)
        )
        return False
    if not lines[n + 3].strip():
        if development_mode:
            print(
                "[WARN]  %s: line %d empty or whitespace-only (HDR_EMPTY_BODY) — allowed under --development"
                % (file_path, n + 4)
            )
            return True
        print(
            "[ERROR] %s: line %d must start body content (no blank-only line after closing ---) (HDR_EMPTY_BODY)"
            % (file_path, n + 4)
        )
        return False

    return True


def _strip_python_hash_comment_line(line):
    """Remove leading '#' and one optional following space (LILITH python header style)."""
    if not line.startswith("#"):
        return None
    rest = line[1:]
    if rest.startswith(" "):
        rest = rest[1:]
    return rest


def _python_body_offset_after_header(lines, has_shebang):
    """Byte offset in normalized \\n content where code body starts (first line after dense header)."""
    n = len(V4_HEADER_KEYS_ORDERED)
    bl = n + 3
    if has_shebang:
        n_before_body = 1 + bl
    else:
        n_before_body = bl
    if len(lines) < n_before_body:
        return None
    pos = 0
    for i in range(n_before_body):
        pos += len(lines[i]) + 1
    return pos


def _mechanical_key_deprecated_error(file_path, rk):
    if rk in REMOVED_HEADER_FIELDS_V419 or rk.startswith("pk_"):
        print(
            "[ERROR] %s: Header contains deprecated field %r."
            % (file_path, rk)
        )
        return True
    legacy_target = LEGACY_FIELD_ALIASES.get(rk)
    if legacy_target and str(legacy_target).startswith("REMOVED_"):
        print(
            "[ERROR] %s: Header contains deprecated field %r."
            % (file_path, rk)
        )
        return True
    return False


def validate_python_mechanical_key_line_order(header_slice, file_path, py_legacy_20):
    """Key order: header_slice[1] is lupopedia.headers:; keys start at index 2 (4.1.9)."""
    expected_canon = list(V4_HEADER_KEYS_ORDERED)
    inner_idx = range(2, 2 + EXPECTED_HEADER_FIELD_COUNT)
    for pos, i in enumerate(inner_idx):
        ln = header_slice[i]
        rest = _strip_python_hash_comment_line(ln)
        if rest is None:
            print("[ERROR] %s: invalid python header line at block index %d (HDR_PYTHON_HEADER)" % (file_path, i))
            return False
        rk = _parse_v3_scalar_key_from_md_line(rest)
        if rk is None:
            print(
                "[ERROR] %s: could not parse key in python header block index %d (HDR_KEY_ORDER)"
                % (file_path, i)
            )
            return False
        if _mechanical_key_deprecated_error(file_path, rk):
            return False
        ck = line_key_to_canonical(rk)
        if ck == expected_canon[pos]:
            continue
        print("[ERROR] %s: Header fields out of canonical order." % (file_path,))
        print(
            "   python header key %r (canonical %r), expected %r (HDR_KEY_ORDER)"
            % (rk, ck, expected_canon[pos])
        )
        return False
    return True


def _validate_hash_comment_25line_slice(
    header_slice,
    file_path,
    reject_legacy_envelope=False,
    suppress_legacy_envelope_warn=False,
    label="python",
):
    """
    Shared ``#`` comment grid: open sep, ``lupopedia.headers:`` + N keys, close sep.
    N = len(V4_HEADER_KEYS_ORDERED) (19-field atom contract). Total lines = N + 3.
    Used by Python files and by PHP CLI scripts (after ``<?php``).
    Returns (ok, yaml_inner_str_or_None).
    """
    n = len(V4_HEADER_KEYS_ORDERED)
    bl = n + 3
    if len(header_slice) != bl:
        print(
            "[ERROR] %s: %s header block must be exactly %d lines (open sep + headers + %d keys + close sep), got %d (HDR_PYTHON_HEADER)"
            % (file_path, label, bl, n, len(header_slice))
        )
        _print_py_envelope_missing_v3_keys_hint(header_slice, file_path)
        return False, None

    if not PY_HEADER_SEP_LINE_RE.match(header_slice[0]) or not PY_HEADER_SEP_LINE_RE.match(
        header_slice[n + 2]
    ):
        print(
            "[ERROR] %s: %s header must start (line 1 of block) and end (line %d) with # --- separator lines (HDR_PYTHON_HEADER)"
            % (file_path, label, n + 3)
        )
        _print_py_envelope_missing_v3_keys_hint(header_slice, file_path)
        return False, None

    first_key_rest = _strip_python_hash_comment_line(header_slice[2])
    hf_line = first_key_rest if first_key_rest is not None else ""
    hf_t = _parse_header_format_version_tuple_from_line(hf_line)

    for i in range(1, n + 2):
        ln = header_slice[i]
        if not ln.strip():
            print(
                "[ERROR] %s: blank line inside %s header key block (HDR_PYTHON_LINE_COUNT)"
                % (file_path, label)
            )
            return False, None
        if not ln.lstrip().startswith("#"):
            print(
                "[ERROR] %s: %s header lines must use # comments (HDR_PYTHON_HEADER)"
                % (file_path, label)
            )
            return False, None

    inner = header_slice[1 : n + 2]
    want = n + 1

    if not validate_python_mechanical_key_line_order(header_slice, file_path, False):
        return False, None

    if len(inner) != want:
        print(
            "[ERROR] %s: expected %d inner lines (lupopedia.headers + keys) in %s header (HDR_PYTHON_LINE_COUNT), got %s"
            % (file_path, want, label, len(inner))
        )
        _print_py_envelope_missing_v3_keys_hint(header_slice, file_path)
        return False, None

    stripped_lines = []
    for ln in inner:
        rest = _strip_python_hash_comment_line(ln)
        if rest is None:
            print("[ERROR] %s: invalid %s header line (HDR_PYTHON_HEADER)" % (file_path, label))
            return False, None
        stripped_lines.append(rest)

    if stripped_lines[0].strip() != "lupopedia.headers:":
        print(
            "[ERROR] %s: %s header must have 'lupopedia.headers:' immediately after opening separator (HDR_PYTHON_HEADER)"
            % (file_path, label)
        )
        return False, None

    yaml_blob = "\n".join(stripped_lines)
    return True, yaml_blob


def validate_python_header_envelope(
    lines,
    file_path,
    reject_legacy_envelope=False,
    suppress_legacy_envelope_warn=False,
):
    """
    Enforce contiguous python comment header: optional shebang, then (N+3)-line ``#`` block
    (open sep, lupopedia + N keys dense, close sep). N = len(V4_HEADER_KEYS_ORDERED).
    Returns (ok, has_shebang, yaml_inner_str_or_None).
    """
    if yaml is None:
        print("[ERROR] %s: PyYAML is required for .py header validation" % (file_path,))
        return False, None, None

    if not lines:
        print("[ERROR] %s: Empty file (HDR_PYTHON_HEADER)" % (file_path,))
        return False, None, None

    n = len(V4_HEADER_KEYS_ORDERED)
    bl = n + 3

    has_shebang = lines[0].startswith("#!")
    if has_shebang:
        if lines[0].strip() != CANONICAL_PYTHON_SHEBANG:
            print(
                "[ERROR] %s: If present, line 1 shebang must be #!/usr/bin/env python3 (HDR_PYTHON_SHEBANG)"
                % (file_path,)
            )
            return False, None, None
        if len(lines) < bl + 2:
            print(
                "[ERROR] %s: Python header with shebang needs >= %d lines before code (HDR_PYTHON_LINE_COUNT), got %s"
                % (file_path, bl + 2, len(lines))
            )
            if len(lines) >= 2:
                _print_py_envelope_missing_v3_keys_hint(
                    lines[1 : 1 + bl] if len(lines) > bl else lines[1:], file_path
                )
            return False, None, None
        header_slice = lines[1 : 1 + bl]
    else:
        if len(lines) < bl + 1:
            print(
                "[ERROR] %s: Python header without shebang needs >= %d lines before code (HDR_PYTHON_LINE_COUNT), got %s"
                % (file_path, bl + 1, len(lines))
            )
            if len(lines) >= 2:
                _print_py_envelope_missing_v3_keys_hint(lines[0:bl] if len(lines) >= bl else lines[0:], file_path)
            return False, None, None
        header_slice = lines[0:bl]

    ok, yaml_blob = _validate_hash_comment_25line_slice(
        header_slice,
        file_path,
        reject_legacy_envelope,
        suppress_legacy_envelope_warn,
        label="python",
    )
    if not ok:
        return False, None, None
    return True, has_shebang, yaml_blob


def _strip_star_doc_line(line):
    """Map ` * lupopedia...` / ` *   key: val` to inner YAML text; None for closing ``*/``."""
    if line is None:
        return None
    s = line.strip()
    if s.startswith("*/"):
        return None
    if not s.startswith("*"):
        return None
    rest = s[1:]
    if rest.startswith(" "):
        rest = rest[1:]
    return rest


def _star_block_legacy_two_blank_keys(header_slice):
    r22 = _strip_star_doc_line(header_slice[22])
    r23 = _strip_star_doc_line(header_slice[23])
    if r22 is None or r23 is None:
        return False
    return (not r22.strip()) and (not r23.strip())


def validate_star_mechanical_key_line_order(header_slice, file_path, star_legacy_20, err_tag):
    """Key order: header_slice[1] is lupopedia.headers:; keys start at index 2 (4.1.9)."""
    expected_canon = list(V4_HEADER_KEYS_ORDERED)
    inner_idx = range(2, 2 + EXPECTED_HEADER_FIELD_COUNT)
    for pos, i in enumerate(inner_idx):
        ln = header_slice[i]
        rest = _strip_star_doc_line(ln)
        if rest is None:
            print(
                "[ERROR] %s: invalid star-comment header line at block index %d (%s)"
                % (file_path, i, err_tag)
            )
            return False
        rk = _parse_v3_scalar_key_from_md_line(rest)
        if rk is None:
            print(
                "[ERROR] %s: could not parse key in star header block index %d (HDR_KEY_ORDER)"
                % (file_path, i)
            )
            return False
        if _mechanical_key_deprecated_error(file_path, rk):
            return False
        ck = line_key_to_canonical(rk)
        if ck == expected_canon[pos]:
            continue
        print("[ERROR] %s: Header fields out of canonical order." % (file_path,))
        print(
            "   star header key %r (canonical %r), expected %r (HDR_KEY_ORDER)"
            % (rk, ck, expected_canon[pos])
        )
        return False
    return True


def validate_star_comment_header_slice(
    header_slice,
    file_path,
    err_tag,
    opening_predicate,
    reject_legacy_envelope=False,
    suppress_legacy_envelope_warn=False,
):
    """
    Exactly 25 lines: opening ``/**`` or ``/*``, `` * lupopedia.headers:``, key lines, `` */``.
    Returns (ok, yaml_inner_str_or_None).
    """
    if yaml is None:
        print("[ERROR] %s: PyYAML is required for %s" % (file_path, err_tag))
        return False, None
    if len(header_slice) != 25:
        print(
            "[ERROR] %s: comment header block must be exactly 25 lines (%s), got %d"
            % (file_path, err_tag, len(header_slice))
        )
        return False, None
    op = header_slice[0].strip()
    if not opening_predicate(op):
        print(
            "[ERROR] %s: comment header opening line invalid for PRD 16 (%s)"
            % (file_path, err_tag)
        )
        return False, None
    if header_slice[24].strip() != "*/":
        print(
            "[ERROR] %s: comment header block must end with */ on line 25 of block (%s)"
            % (file_path, err_tag)
        )
        return False, None

    star_legacy_20 = _star_block_legacy_two_blank_keys(header_slice)
    first_key_rest = _strip_star_doc_line(header_slice[2])
    hf_line = first_key_rest if first_key_rest is not None else ""
    hf_t = _parse_header_format_version_tuple_from_line(hf_line)

    if star_legacy_20:
        for i in range(1, 22):
            ln = header_slice[i]
            if not ln.strip():
                print(
                    "[ERROR] %s: blank line inside star header key block (%s)"
                    % (file_path, err_tag)
                )
                return False, None
            if not ln.strip().startswith("*"):
                print(
                    "[ERROR] %s: star header inner lines must use * prefix (%s)"
                    % (file_path, err_tag)
                )
                return False, None
        if _hf_tuple_requires_dense_v4099(hf_t):
            print(
                "[ERROR] %s: header_format_version >= 4.0.99 requires dense 22-key star header "
                "(HDR_HEADER_INTERNAL_BLANK)"
                % (file_path,)
            )
            return False, None
        if reject_legacy_envelope:
            print(
                "[ERROR] %s: legacy star header envelope rejected (HDR_LEGACY_ENVELOPE)"
                % (file_path,)
            )
            return False, None
        if not suppress_legacy_envelope_warn:
            print(
                "[WARN]  %s: legacy star-comment header (blank * lines before */); migrate to dense v4.0.99 "
                "(HDR_LEGACY_ENVELOPE)"
                % (file_path,)
            )
        inner = header_slice[1:22]
    else:
        for i in range(1, 24):
            ln = header_slice[i]
            if not ln.strip():
                print(
                    "[ERROR] %s: blank line inside star header key block (%s)"
                    % (file_path, err_tag)
                )
                return False, None
            if not ln.strip().startswith("*"):
                print(
                    "[ERROR] %s: star header inner lines must use * prefix (%s)"
                    % (file_path, err_tag)
                )
                return False, None
        inner = header_slice[1:24]

    if not validate_star_mechanical_key_line_order(
        header_slice, file_path, star_legacy_20, err_tag
    ):
        return False, None

    want = 21 if star_legacy_20 else 23
    if len(inner) != want:
        print(
            "[ERROR] %s: expected %d inner lines in star header (%s), got %d"
            % (file_path, want, err_tag, len(inner))
        )
        return False, None

    stripped_lines = []
    for ln in inner:
        rest = _strip_star_doc_line(ln)
        if rest is None:
            print("[ERROR] %s: invalid star header line (%s)" % (file_path, err_tag))
            return False, None
        stripped_lines.append(rest)

    if stripped_lines[0].strip() != "lupopedia.headers:":
        print(
            "[ERROR] %s: star header must have 'lupopedia.headers:' after opening (%s)"
            % (file_path, err_tag)
        )
        return False, None

    yaml_blob = "\n".join(stripped_lines)
    return True, yaml_blob


def _php_find_star_header_slice_after(lines, php_line_index, file_path):
    """
    After the ``<?php`` line at *php_line_index*, locate the first ``/**`` (or ``/*``)
    and take the following 24 lines as the remainder of the 25-line PRD-16 star block.

    Returns ``(start_index, header_slice_25_lines)`` or ``(None, None)``.
    """
    lim = min(len(lines), php_line_index + 80)
    for j in range(php_line_index + 1, lim):
        op = lines[j].strip()
        if op == "/**" or op == "/*":
            if len(lines) < j + 25:
                print(
                    "[ERROR] %s: PHP star header starting at line %d needs 25 lines through body (HDR_PHP_HEADER)"
                    % (file_path, j + 1)
                )
                return None, None
            return j, lines[j : j + 25]
    print(
        "[ERROR] %s: no /** or /* Lupopedia header block found after <?php (HDR_PHP_HEADER)"
        % (file_path,)
    )
    return None, None


def validate_php_header_envelope(
    lines,
    file_path,
    reject_legacy_envelope=False,
    suppress_legacy_envelope_warn=False,
):
    """
    Optional ``#!/usr/bin/env php`` then ``<?php`` then 25-line ``/**`` … ``*/`` docblock,
    or ``<?php`` + docblock (PRD 16 §4.3 rule 9 star-comment path).
    Returns ``(ok, yaml_inner_str_or_None, first_body_line_index_0based_or_None)``.
    """
    if not lines:
        print("[ERROR] %s: Empty file (HDR_PHP_HEADER)" % (file_path,))
        return False, None, None
    php_idx = 0
    if lines[0].startswith("#!"):
        if lines[0].strip() != CANONICAL_PHP_SHEBANG:
            print(
                "[ERROR] %s: If line 1 is a shebang it must be %r (HDR_PHP_HEADER)"
                % (file_path, CANONICAL_PHP_SHEBANG)
            )
            return False, None, None
        if len(lines) < 2 or lines[1].strip() != "<?php":
            print(
                "[ERROR] %s: PHP shebang must be immediately followed by <?php on line 2 (HDR_PHP_HEADER)"
                % (file_path,)
            )
            return False, None, None
        php_idx = 1

    elif lines[0].strip() != "<?php":
        print(
            "[ERROR] %s: PHP PRD 16 header requires line 1 to be <?php or %r (HDR_PHP_HEADER)"
            % (file_path, CANONICAL_PHP_SHEBANG)
        )
        return False, None, None

    j, header_slice = _php_find_star_header_slice_after(lines, php_idx, file_path)
    if j is None:
        return False, None, None
    first_body_idx = j + 25
    if len(lines) <= first_body_idx:
        print(
            "[ERROR] %s: PHP needs a non-header line after the star block (HDR_PHP_HEADER)"
            % (file_path,)
        )
        return False, None, None

    ok, yaml_inner = validate_star_comment_header_slice(
        header_slice,
        file_path,
        "HDR_PHP_HEADER",
        lambda op: op == "/**" or op == "/*",
        reject_legacy_envelope=reject_legacy_envelope,
        suppress_legacy_envelope_warn=suppress_legacy_envelope_warn,
    )
    if not ok:
        return False, None, None
    return True, yaml_inner, first_body_idx


def validate_js_header_envelope(
    lines,
    file_path,
    reject_legacy_envelope=False,
    suppress_legacy_envelope_warn=False,
):
    """25-line ``/*`` … ``*/`` block at file start per PRD 16 §4.3 rule 9."""
    if not lines:
        print("[ERROR] %s: Empty file (HDR_JS_HEADER)" % (file_path,))
        return False, None
    if len(lines) < 26:
        print(
            "[ERROR] %s: JS needs >= 26 lines (25-line header block + body) (HDR_JS_HEADER), got %d"
            % (file_path, len(lines))
        )
        return False, None
    header_slice = lines[0:25]
    return validate_star_comment_header_slice(
        header_slice,
        file_path,
        "HDR_JS_HEADER",
        lambda op: op in ("/*", "/**"),
        reject_legacy_envelope=reject_legacy_envelope,
        suppress_legacy_envelope_warn=suppress_legacy_envelope_warn,
    )


def validate_no_unicode_box_chars(content, file_path):
    """Reject unicode box-drawing and mojibake variants in docs/header text."""
    if UNICODE_BOX_RE.search(content) or MOJIBAKE_BOX_RE.search(content):
        print(
            "[ERROR] %s: unicode or mojibake box-drawing characters found (HDR_UNICODE_BOX)"
            % (file_path,)
        )
        return False
    return True


def _header_value_present(val):
    if val is None:
        return False
    if isinstance(val, str) and not val.strip():
        return False
    if isinstance(val, (list, dict)) and len(val) == 0:
        return False
    return True


def validate_header_scalar_values(hdr, file_path, strict_mode=False):
    """PRD 16 §4.3 — no YAML arrays or multiline scalars under lupopedia.headers."""
    if not isinstance(hdr, dict):
        return True
    for k, v in hdr.items():
        if isinstance(v, (list, dict)):
            print(
                "[ERROR] %s: value for %r must be scalar, not list/dict (HDR_ARRAY)"
                % (file_path, k)
            )
            return False
        if isinstance(v, str) and ("\n" in v or "\r" in v):
            print("[ERROR] %s: multiline value in %r (HDR_MULTILINE)" % (file_path, k))
            return False
    return True


def validate_removed_fields(hdr, file_path, strict_mode=False):
    """Reject fields removed from the header envelope (v4.1.9 identity fields, slugs)."""
    return validate_deprecated_header_fields(hdr, file_path)


def validate_prd_cluster(hdr, file_path):
    """Strict validation of prd_cluster field - shorthand format only."""
    if "prd_cluster" not in hdr:
        print("INVALID_PRD_CLUSTER")
        return False
    
    prd_cluster = hdr["prd_cluster"]
    if prd_cluster is None:
        print("INVALID_PRD_CLUSTER")
        return False
    
    if not isinstance(prd_cluster, str):
        print("INVALID_PRD_CLUSTER")
        return False
    
    # HARD FAIL on leading or trailing whitespace
    if prd_cluster != prd_cluster.strip():
        print("INVALID_PRD_CLUSTER")
        return False
    
    # STRICT single-line enforcement
    if len(prd_cluster.splitlines()) != 1:
        print("INVALID_PRD_CLUSTER")
        return False
    
    # Strict validation - no normalization, no parsing, no tolerance
    # Must match exactly: ^\d{2}_[A-Z](?:_\d{2}_[A-Z])*$
    strict_pattern = r'^\d{2}_[A-Z](?:_\d{2}_[A-Z])*$'
    
    if not re.fullmatch(strict_pattern, prd_cluster):
        print("INVALID_PRD_CLUSTER")
        return False
    
    # Additional constraints: no forbidden characters
    if '\t' in prd_cluster:
        print("INVALID_PRD_CLUSTER")
        return False
    
    if '"' in prd_cluster or "'" in prd_cluster:
        print("INVALID_PRD_CLUSTER")
        return False
    
    return True


def _is_valid_header_format_version(version_value):
    """
    Version policy (PRD 16 v4.1.9): accept only header_format_version exactly 4.1.9.

    Rejects 4.0.x, other 4.1.x patches, 4.2.0+, and legacy families (HDR_VERSION_FAMILY).
    """
    return is_exact_header_format_version(version_value)


def check_version_compliance(version, has_legacy_pk_fields):
    """
    PRD 16 §15.4 — version-aware pk_* policy (normative).

    Returns (status, code) where status is \"PASS\" or \"ERROR\" and code is
    None or \"HDR_VERSION_MISMATCH\".
    """
    if version is None:
        return "PASS", None
    s = str(version).strip()
    if not s.startswith("4.1."):
        return "PASS", None
    parts = s.split(".")
    try:
        patch = int(parts[2]) if len(parts) >= 3 else 0
    except ValueError:
        return "PASS", None
    if patch >= 3 and has_legacy_pk_fields:
        return "ERROR", "HDR_VERSION_MISMATCH"
    return "PASS", None


def validate_legacy_pk_alias_vs_claimed_version(hdr, file_path):
    """
    PRD 16 §11 / §15.4: If header_format_version is 4.1.3+, legacy pk_* YAML keys
    must not appear (HDR_VERSION_MISMATCH). For 4.1.0–4.1.2, pk_* is WARN-only
    via _warn_legacy_header_yaml_key_names (HDR_PK_LEGACY_ALIAS).

    Call with the raw header dict before normalize_header_dict_for_validation().
    Uses check_version_compliance() for the §15.4 decision table.
    """
    if not isinstance(hdr, dict):
        return True
    fmt_v = hdr.get("header_format_version")
    has_legacy = any(k in hdr for k in ("pk_id", "pk_slug", "parent_pk_id"))
    status, code = check_version_compliance(fmt_v, has_legacy)
    if status != "ERROR":
        return True
    for k in ("pk_id", "pk_slug", "parent_pk_id"):
        if k in hdr:
            print(
                "[ERROR] %s: legacy %r field not allowed when header_format_version is %s "
                "(%s: pk_* aliases removed in 4.1.3)"
                % (file_path, k, str(fmt_v).strip(), code)
            )
            return False
    return True


def _resolve_canonical_field_order(hdr, file_path):
    """
    PRD 16 v4.1.4: canonical 22-field order is default authority unless valid atoms_toon override exists.
    
    The V4_HEADER_KEYS_ORDERED (22-field model) is the default authority for all v4.1.4 headers.
    Only files with valid atoms_toon can override this canonical order; otherwise the canonical
    order always applies regardless of other factors.
    """
    fallback = list(V4_HEADER_KEYS_ORDERED)  # v4.1.4 canonical 22-field model (primary authority)
    raw_at = hdr.get("atoms_toon")
    if raw_at is None:
        return fallback
    at = str(raw_at).strip()
    if not at:
        return fallback
    at_norm = at.replace("\\", "/")
    try:
        repo_root = _find_lupopedia_repo_root(os.path.abspath(file_path))
        atom_abs = os.path.normpath(os.path.join(repo_root, at_norm.replace("/", os.sep)))
        if not os.path.isfile(atom_abs):
            print(
                "[WARN]  %s: atoms_toon file missing/unreadable; using built-in canonical order (HDR_ATOM_MISMATCH): %s"
                % (file_path, at_norm)
            )
            return fallback
        
        with open(atom_abs, "r", encoding="utf-8") as af:
            atom_data = json.load(af)
        
        if not isinstance(atom_data, dict) or not isinstance(atom_data.get("constants", {}), dict) or not isinstance(atom_data.get("constants", {}).get("header_fields", {}), dict):
            print(
                "[WARN]  %s: atoms_toon format invalid; using built-in canonical order (HDR_ATOM_MISMATCH)"
                % (file_path,)
            )
            return fallback
        
        header_fields = atom_data["constants"]["header_fields"]
        order = header_fields.get("order")
        if not isinstance(order, list):
            print(
                "[WARN]  %s: atoms_toon constants.header_fields.order missing or non-list; using built-in canonical order (HDR_ATOM_MISMATCH)"
                % (file_path,)
            )
            return fallback
        
        order_norm = [str(x).strip() for x in order]
        if len(order_norm) != len(V4_HEADER_KEYS_ORDERED) or frozenset(order_norm) != frozenset(
            V4_HEADER_KEYS_ORDERED
        ):
            print(
                "[WARN]  %s: atoms_toon constants.header_fields.order invalid key set/size; using built-in canonical order (HDR_ATOM_MISMATCH)"
                % (file_path,)
            )
            return fallback
        
        return order_norm
    except Exception as exc:
        print(
            "[WARN]  %s: atoms_toon unreadable (%s); using built-in canonical order (HDR_ATOM_MISMATCH)"
            % (file_path, exc)
        )
    return fallback


def validate_field_ordering(hdr, file_path):
    """PRD 16 §4.2 — exact 22-key set and order for header_format_version 4.1.9."""
    if not isinstance(hdr, dict):
        return True

    fmt = hdr.get("header_format_version")
    if not _is_valid_header_format_version(fmt):
        return True

    return validate_header_field_count_and_order(hdr, file_path)


def validate_ascii_safe_names(hdr, file_path):
    """Validate ASCII-safe filenames and slugs"""
    errors = []
    
    # Check path anchor (path_from_lupopedia_root preferred; file_path_from_root legacy)
    file_path_val = hdr.get("path_from_lupopedia_root") or hdr.get("file_path_from_root", "")
    if file_path_val:
        filename = file_path_val.split('/')[-1]
        # Compare lowercase: paths may use README.md while doctrine prefers readme.md
        if not ASCII_FILENAME_PATTERN.match(filename.lower()):
            errors.append("filename '%s' contains non-ASCII or invalid characters" % (filename,))

    thread_key = hdr.get("thread_key", hdr.get("thread_id", ""))
    if thread_key and not ASCII_SLUG_PATTERN.match(str(thread_key)):
        errors.append("thread_key '%s' contains non-ASCII characters" % (thread_key,))
    
    if errors:
        print("[ERROR] %s: ASCII-safe validation failed: %s" % (file_path, "; ".join(errors)))
        return False
    
    return True


def validate_content_id(hdr, file_path, header_format_version=None):
    """
    PRD 16 §4.2 — content_id shape validation only.

    This function validates required presence and NULL-or-numeric format. It does not
    perform DB authority resolution (file-first/database-first/repair-state), which is
    handled by DB-aware validation paths.
    """
    content_id = hdr.get('content_id')

    if "content_id" not in hdr:
        return True

    if content_id is None:
        return True

    content_id_str = str(content_id).strip()
    if not content_id_str:
        print(f"[ERROR] {file_path}: content_id cannot be empty string (HDR_CONTENT_ID_INVALID)")
        return False

    # Check if content_id is a valid BIGINT
    if not content_id_str.isdigit():
        print(f"[ERROR] {file_path}: content_id must be NULL or numeric BIGINT (HDR_CONTENT_ID_INVALID), got '{content_id}'")
        return False
    
    # Check if it's within reasonable BIGINT range
    try:
        cid_int = int(content_id_str)
        if cid_int < 1 or cid_int > 2**63 - 1:
            print(
                f"[ERROR] {file_path}: content_id must be a positive BIGINT when set (HDR_CONTENT_ID_INVALID), got {cid_int}"
            )
            return False
    except OverflowError:
        print(f"[ERROR] {file_path}: content_id {content_id} causes overflow (HDR_CONTENT_ID_INVALID)")
        return False

    return True


def _prd_parent_resolves_on_disk(repo_root, parent_str):
    """
    True if parent_str references a normative PRD under docs/prd/ either by
    filename prefix ({n}_*.md) or by matching lupopedia.headers.content_id in a
    root-level PRD .md (after import_content write-back).
    """
    prd_pattern = os.path.join(repo_root, "docs", "prd", parent_str + "_*.md")
    if glob.glob(prd_pattern):
        return True
    prd_dir = os.path.join(repo_root, "docs", "prd")
    if not os.path.isdir(prd_dir) or yaml is None:
        return False
    ps = str(parent_str).strip()
    if not ps.isdigit():
        return False
    try:
        want = int(ps)
    except ValueError:
        return False
    for fn in sorted(os.listdir(prd_dir)):
        if not fn.endswith(".md"):
            continue
        fp = os.path.join(prd_dir, fn)
        if not os.path.isfile(fp):
            continue
        try:
            with open(fp, "r", encoding="utf-8", errors="replace") as fh:
                chunk = fh.read(65536)
        except OSError:
            continue
        if not chunk.startswith("---"):
            continue
        end = chunk.find("\n---\n", 4)
        if end < 0:
            continue
        try:
            data = yaml.safe_load(chunk[4:end])
        except Exception:
            continue
        if not isinstance(data, dict):
            continue
        hdr = data.get("lupopedia.headers")
        if not isinstance(hdr, dict):
            continue
        cid = hdr.get("content_id")
        if cid is None:
            continue
        try:
            if int(cid) == want:
                return True
        except (TypeError, ValueError):
            if str(cid).strip() == ps:
                return True
    return False


def validate_content_parent_id(hdr, file_path, development_mode=False):
    """
    PRD 16 field 17 — semantic reference to a PRD (not a DB FK).

    - For artifact_type='prd': content_parent_id MUST be null.
    - For version-doc, implementation, status: content_parent_id may be null or non-null.
      Non-null values are acceptable when not yet DB-linked; no validation performed.
    - For all other types: no validation performed.
    """
    parent = hdr.get("content_parent_id")
    artifact_type = str(hdr.get("artifact_type", "")).strip()

    # PRD files MUST have null parent
    if artifact_type == "prd":
        if parent is not None:
            print(
                "[ERROR] %s: PRD files must have content_parent_id: null (HDR_CONTENT_PARENT_PRD_INVALID)"
                % (file_path,)
            )
            return False
        return True

    # For other types, null is fine
    if parent is None:
        return True

    parent_str = str(parent).strip()
    if not parent_str:
        return True

    # Only validate for specific artifact types
    if artifact_type not in ("version-doc", "implementation", "status"):
        # No validation performed for other artifact types
        return True

    # Disk resolution intentionally disabled for development workflows
    # Parent IDs may be null when not yet DB-linked, no validation required
    return True


def validate_actor_folder_alignment(hdr, file_path):
    """Validate actor_id matches folder structure for deterministic placement"""
    actor_id = hdr.get('actor_id')
    if not actor_id:
        return True
    
    try:
        actor_id_int = int(actor_id)
    except (ValueError, TypeError):
        print(f"[ERROR] {file_path}: actor_id must be numeric, got '{actor_id}'")
        return False
    
    # Extract folder path from path anchor
    file_path_val = hdr.get("path_from_lupopedia_root") or hdr.get("file_path_from_root", "")
    
    # Check if this is an actor-related file
    if 'actors' in file_path_val:
        if actor_id_int < 2026:
            # Core actor should be in actors/<actor_id>/
            expected_pattern = f"actors/{actor_id_int}/"
        else:
            # Runtime actor should be in actors/YYYY/MM/<actor_id>/
            # Extract YYYYMM from timestamp ID
            actor_str = str(actor_id_int)
            if len(actor_str) >= 6:
                yyyy = actor_str[:4]
                mm = actor_str[4:6]
                expected_pattern = f"actors/{yyyy}/{mm}/{actor_id_int}/"
            else:
                print(f"[WARN]  {file_path}: Runtime actor_id {actor_id_int} too short to extract date")
                return True
        
        if expected_pattern not in file_path_val:
            print(f"[WARN]  {file_path}: Actor folder misalignment")
            print(f"   actor_id={actor_id_int}, expected pattern '{expected_pattern}' in path '{file_path_val}'")
    
    return True


def validate_required_fields_by_type(hdr, file_path):
    """PRD 16 §4.2 — type-specific required fields."""
    if not isinstance(hdr, dict):
        return True
    
    artifact_type = hdr.get('lupopedia.schema')
    if not artifact_type:
        return True
    
    # Reject legacy alias fields that map to removed content_slug across ALL artifact types
    for legacy_field in ['prd_slug', 'pk_slug']:
        if legacy_field in hdr:
            print(f"[ERROR] {file_path}: legacy field '{legacy_field}' found (HDR_LEGACY_ALIAS_REMOVED): {legacy_field} maps to removed content_slug in v4.1.4")
            return False
    
    # PRD files
    if artifact_type == 'prd':
        required = ['title', 'status']  # content_slug removed in v4.1.4
        for field in required:
            if field not in hdr or not _header_value_present(hdr.get(field)):
                print(f"[ERROR] {file_path}: PRD missing required field '{field}'")
                return False
        # Reject content_slug entirely in v4.1.4
        if 'content_slug' in hdr:
            print(f"[ERROR] {file_path}: content_slug field found (HDR_CONTENT_SLUG_REMOVED): content_slug was removed in v4.1.4")
            return False
        # Validate status values
        valid_statuses = ['draft', 'review', 'approved', 'implemented', 'active', 'deprecated']
        if 'status' in hdr and hdr['status'] not in valid_statuses:
            print(f"[ERROR] {file_path}: Invalid PRD status '{hdr['status']}'. Must be one of: {valid_statuses}")
            return False

    # Discussion files (v3: channel_key is global required; thread_id non-empty per §4.2.1)
    elif artifact_type == 'discussion':
        if 'thread_id' not in hdr or not _header_value_present(hdr.get('thread_id')):
            print(f"[ERROR] {file_path}: Discussion missing required field 'thread_id'")
            return False
        # Validate context_id if present (optional for discussions)
        context_id = hdr.get('context_id')
        if context_id is not None:
            context_id_str = str(context_id)
            if not (context_id_str.isdigit() and len(context_id_str) == 18):
                print(f"[ERROR] {file_path}: context_id must be 18 digits (YYYYMMDDHHIISS + 4 random), got {context_id_str}")
                return False

    # Implementation files
    elif artifact_type == 'implementation':
        required = ['status']  # content_parent_id optional for implementation files
        for field in required:
            if field not in hdr or not _header_value_present(hdr.get(field)):
                print(f"[ERROR] {file_path}: Implementation missing required field '{field}'")
                return False
        # Validate status values
        valid_statuses = ['not_started', 'in_progress', 'complete', 'blocked', 'deprecated', 'active']
        if 'status' in hdr and hdr['status'] not in valid_statuses:
            print(f"[ERROR] {file_path}: Invalid implementation status '{hdr['status']}'. Must be one of: {valid_statuses}")
            return False
    
    # Doctrine files — no additional requirements beyond base
    elif artifact_type == 'doctrine':
        pass  # Minimal requirements
    
    return True


def validate_memory_key(hdr, file_path, strict_mode=False):
    """
    PRD 16 §4.2 field 8 — memory_toon must be present and end with .toon (HDR_MEMORY_KEY_SUFFIX).

    v4.1.4: Legacy memory_key handling:
    - strict_mode=True: ERROR (migration complete)
    - strict_mode=False: WARN (temporary compatibility)
    """
    # Handle legacy memory_key based on strict mode
    if "memory_key" in hdr:
        if strict_mode:
            print(
                "[ERROR] %s: legacy field memory_key found (HDR_MEMORY_KEY_REMOVED): "
                "memory_key was removed in v4.1.4, use memory_toon per PRD 16 §4.2 field 8" % (file_path,)
            )
            return False
        else:
            print(
                "[WARN]  %s: deprecated field memory_key found (HDR_MEMORY_KEY_RENAMED): "
                "migrate to memory_toon per PRD 16 v4.1.0 §4.2 field 8" % (file_path,)
            )
            mk = hdr.get("memory_key")
            # Continue validation with the legacy field value
    else:
        mk = hdr.get("memory_toon")
    if mk is None:
        return False, "[ERROR] %s: memory_toon missing (HDR_MEMORY_KEY)" % (file_path,)
    s = str(mk).strip()
    if not s:
        return False, "[ERROR] %s: memory_toon empty (HDR_MEMORY_KEY)" % (file_path,)
    if not s.endswith(".toon"):
        return (
            False,
            "[ERROR] %s: memory_toon must end with .toon (HDR_MEMORY_KEY_SUFFIX), got %r"
            % (file_path, mk),
        )
    return True, None


def validate_memory_key_path_shape(hdr, file_path, strict_memory_year=False):
    """
    PRD 16 §5.2 / §10.1 — memory_toon must start with memory/{channel_key}/{trust_tier}/…
    Full normative shape includes {year}/{MM}/{slug}.toon; compact legacy paths with
    only a filename after trust_tier are still accepted.

    Accepts legacy memory_key field during migration; new headers MUST use memory_toon.

    PRD 16 §10.1 — channel/path consistency: path segment[1] must equal channel_key.
    Mismatch emits HDR_CHANNEL_PATH_MISMATCH (ERROR, not auto-correctable).

    PRD 16 §8.1 — canonical tier: year segment should be (when_updated calendar year − 1000).
    With strict_memory_year=True, mismatch is ERROR (HDR_MEMORY_YEAR_OFFSET). Default warns
    only when the segment equals the raw calendar year (legacy mistake).
    """
    # Accept legacy memory_key during migration
    raw_mk = hdr.get("memory_toon") if "memory_toon" in hdr else hdr.get("memory_key")
    mk = str(raw_mk or "").strip()
    ck = str(hdr.get("channel_key") or "").strip()
    tt = str(hdr.get("trust_tier") or "").strip()
    when_u = str(hdr.get("when_updated") or "").strip()
    parts = mk.split("/")
    if len(parts) < 4 or parts[0] != "memory":
        print(
            "[ERROR] %s: memory_toon must start with "
            "memory/{channel_key}/{trust_tier}/… (HDR_MEMORY_KEY)"
            % (file_path,)
        )
        return False
    if parts[1] != ck:
        print(
            "[ERROR] %s: memory_toon channel segment %r != header channel_key %r "
            "(HDR_CHANNEL_PATH_MISMATCH)"
            % (file_path, parts[1], ck)
        )
        return False
    if parts[2] != tt:
        print(
            "[ERROR] %s: memory_toon segment %r != header trust_tier %r (HDR_MEMORY_KEY)"
            % (file_path, parts[2], tt)
        )
        return False
    if (
        tt == "canonical"
        and len(parts) >= 6
        and len(when_u) >= 4
        and parts[3].isdigit()
        and len(parts[3]) == 4
    ):
        cal = int(when_u[:4])
        yseg = int(parts[3])
        expected = cal - 1000
        if strict_memory_year:
            if yseg != expected:
                print(
                    "[ERROR] %s: canonical memory_toon year segment %s must be %s "
                    "(when_updated year %s - 1000 per PRD 16 section 8.1) (HDR_MEMORY_YEAR_OFFSET)"
                    % (file_path, yseg, expected, cal)
                )
                return False
        elif 2000 <= yseg <= 2099 and yseg == cal:
            print(
                "[WARN]  %s: canonical memory_toon uses calendar year %s in path; PRD 16 section 8.1 recommends "
                "%s (year - 1000); use --strict-memory-year to fail this (HDR_MEMORY_YEAR_OFFSET)"
                % (file_path, yseg, expected)
            )
    if (
        tt == "staging"
        and len(parts) >= 6
        and len(when_u) >= 4
        and parts[3].isdigit()
        and len(parts[3]) == 4
    ):
        cal = int(when_u[:4])
        yseg = int(parts[3])
        if strict_memory_year:
            if yseg != cal:
                print(
                    "[ERROR] %s: staging memory_toon year segment %s must equal when_updated "
                    "calendar year %s (PRD 16 section 8.1) (HDR_MEMORY_YEAR_OFFSET)"
                    % (file_path, yseg, cal)
                )
                return False
    return True


def validate_dialog_transcript_triple(hdr, file_path, strict_mode=False):
    """
    PRD 16 §4.2 field 10 — normative triple {federation_node_id}/{channel_key}/{thread_slug}.

    Reads from transcript_jsonl (v4.1.4 name). Legacy dialog_transcript handling:
    - strict_mode=True: ERROR (migration complete)
    - strict_mode=False: WARN (temporary compatibility)
    Fewer than three segments: ERROR. More than three: WARN (HDR_DIALOG_EXTRA_SEGMENTS). 
    First segment MUST equal federation_node_id.
    """
    # Handle legacy dialog_transcript based on strict mode
    if "dialog_transcript" in hdr:
        if strict_mode:
            print(
                "[ERROR] %s: legacy field dialog_transcript found (HDR_DIALOG_TRANSCRIPT_REMOVED): "
                "dialog_transcript was removed in v4.1.4, use transcript_jsonl per PRD 16 §4.2 field 10" % (file_path,)
            )
            return False
        else:
            print(
                "[WARN]  %s: deprecated field dialog_transcript found (HDR_DIALOG_TRANSCRIPT_RENAMED): "
                "migrate to transcript_jsonl per PRD 16 v4.1.0 §4.2 field 10" % (file_path,)
            )
            dt = hdr.get("dialog_transcript")
            # Continue validation with the legacy field value
    else:
        dt = hdr.get("transcript_jsonl")
    s = str(dt).strip() if dt is not None else ""
    if not s:
        print(
            "[ERROR] %s: transcript_jsonl empty (HDR_DIALOG_MISSING)"
            % (file_path,)
        )
        return False
    parts = s.split("/")
    if any(not p.strip() for p in parts):
        print(
            "[ERROR] %s: transcript_jsonl must not contain empty '/' segments (HDR_DIALOG_FORMAT)"
            % (file_path,)
        )
        return False
    if len(parts) < 3:
        print(
            "[ERROR] %s: transcript_jsonl must have at least 3 '/'-separated segments "
            "(federation_node_id/channel_key/thread_slug…) (HDR_DIALOG_FORMAT)"
            % (file_path,)
        )
        return False
    if len(parts) > 3:
        print(
            "[WARN]  %s: transcript_jsonl has %d segments; PRD 16 field 10 normative form is "
            "exactly 3 segments (HDR_DIALOG_EXTRA_SEGMENTS), got %r"
            % (file_path, len(parts), s)
        )
    try:
        fed_hdr = int(hdr["federation_node_id"])
    except (KeyError, TypeError, ValueError):
        return True
    if parts[0] != str(fed_hdr):
        print(
            "[ERROR] %s: transcript_jsonl first segment %r must equal federation_node_id %s"
            % (file_path, parts[0], fed_hdr)
        )
        return False
    return True


def validate_required_header_fields(hdr, file_path):
    """PRD 16 §12.1 — v3-only: required keys + trust_tier."""
    if not isinstance(hdr, dict):
        print("[ERROR] %s: lupopedia.headers must be a mapping" % (file_path,))
        return False

    version = hdr.get("header_format_version")
    if not validate_header_format_version_exact(hdr, file_path):
        return False

    # Validate type-specific fields
    if not validate_required_fields_by_type(hdr, file_path):
        return False

    if "version" in hdr:
        print(
            "[ERROR] %s: obsolete header field 'version' under lupopedia.headers (HDR_VERSION_FIELD_REMOVED); use header_format_version, git/CHANGELOG, document title"
            % (file_path,)
        )
        return False
    for key in REQUIRED_HEADER_KEYS:
        if key not in hdr:
            print("[ERROR] %s: missing required header field %r" % (file_path, key))
            return False
        val = hdr.get(key)
        if key == "transcript_jsonl":
            if val is None or (isinstance(val, str) and not val.strip()):
                print(
                    "[ERROR] %s: transcript_jsonl required non-empty (§4.2 field 10)"
                    % (file_path,)
                )
                return False
            continue
        if key == "thread_key":
            if val is None:
                continue
        if key == "atoms_toon":
            # YAML null is valid; empty string forbidden (checked earlier via HDR_ATOMS_TOON_SUFFIX).
            # module is accepted as a legacy alias for atoms_toon via LEGACY_KEYS_V4 normalization.
            continue
        if key == "memory_toon":
            # YAML null is valid during migration; non-null must end .toon (validated by validate_memory_key).
            # memory_key is accepted as a legacy alias via LEGACY_KEYS_V4 normalization.
            continue
        if key == "questions_toon":
            # YAML null is always valid — .questions.toon file may not exist yet.
            # Non-null values are validated separately by validate_questions_toon().
            continue
        if key == "edges_toon":
            # null valid for repo-native; non-null rules in validate_edges_toon (v4.1.9+).
            continue
        if key == "source_timestamp":
            # null valid when channel_index is lupopedia; external rules in validate_source_timestamp.
            continue
        if key in V3_KEYS_ALLOW_EMPTY_VALUE:
            continue
        if val is None:
            print("[ERROR] %s: required header field %r is null" % (file_path, key))
            return False
        if isinstance(val, str) and not val.strip():
            print("[ERROR] %s: required header field %r is empty" % (file_path, key))
            return False
    tier = str(hdr.get("trust_tier", "")).strip()
    if tier in LEGACY_TRUST_TIERS:
        print(
            "[WARN]  %s: trust_tier %r is legacy; use canonical or development "
            "(HDR_TRUST_TIER_LEGACY)"
            % (file_path, tier)
        )
    elif tier not in VALID_TRUST_TIERS:
        print(
            "[ERROR] %s: trust_tier must be one of %s (HDR_TRUST_TIER_INVALID), got %r"
            % (file_path, ", ".join(sorted(VALID_TRUST_TIERS)), tier)
        )
        return False
    # v4.1.1: pk_id / pk_slug / parent_pk_id are legacy aliases for content_* fields.
    # They are normalized before this point via LEGACY_KEYS_V4; direct key checks use content_* names.
    return True


def validate_thread_id_format(thread_key, file_path, artifact_type=None):
    """PRD 16 §4.2.1 — thread_key rules by artifact_type (legacy thread_id accepted via normalization)."""
    at = str(artifact_type or "").strip()
    if at == "prd":
        if thread_key is not None and str(thread_key).strip() != "":
            print(
                "[ERROR] %s: artifact_type prd requires thread_key empty or null (§4.2.1), got %r"
                % (file_path, thread_key)
            )
            return False
        return True
    if at == "discussion":
        if thread_key is None or str(thread_key).strip() == "":
            print("[ERROR] %s: discussion requires non-empty thread_key" % (file_path,))
            return False
        tid = str(thread_key).strip()
        if not THREAD_ID_PATTERN.match(tid):
            print(
                "[ERROR] %s: thread_key must match ^[a-z0-9][a-z0-9-]*$ (got %r)"
                % (file_path, tid)
            )
            return False
        return True
    # all others: empty OK; non-empty must match slug pattern
    if thread_key is None or str(thread_key).strip() == "":
        return True
    tid = str(thread_key).strip()
    if not THREAD_ID_PATTERN.match(tid):
        print(
            "[ERROR] %s: thread_key must be '' or ^[a-z0-9][a-z0-9-]*$ (got %r)"
            % (file_path, tid)
        )
        return False
    return True


def validate_ymdhis_pair(when_updated, _ignored_last_modified_utc, file_path):
    """
    Validate when_updated as a 14-digit UTC string.

    NOTE: last_modified_utc was renamed to questions_toon in PRD 16 v4.0.99 §4.2 field 6.
    The second argument is accepted but ignored for call-site backward compatibility.
    Only when_updated is validated here; questions_toon is validated by
    validate_questions_toon().
    """
    s = str(when_updated).strip() if when_updated is not None else ""
    if not YMDHIS_PATTERN.match(s):
        print(
            "[ERROR] %s: when_updated must be UTC YYYYMMDDHHIISS (14 digits), got %r"
            % (file_path, when_updated)
        )
        return False
    return True


def validate_questions_toon(value, file_path):
    """
    PRD 16 §4.2 field 6 — questions_toon must be null or a path ending in .questions.toon.
    Emits HDR_QUESTIONS_TOON_SUFFIX on bad path; allows null (Q&A system not yet built).
    """
    if value is None or str(value).strip().lower() in ("null", "none", ""):
        return True  # null is always valid — .questions.toon file may not exist yet
    val_str = str(value).strip()
    if not val_str.endswith(".questions.toon"):
        print(
            "[ERROR] %s (HDR_QUESTIONS_TOON_SUFFIX): questions_toon must end with "
            ".questions.toon or be null, got %r" % (file_path, val_str)
        )
        return False
    return True


def validate_atoms_toon(hdr, file_path, development_mode=False):
    """
    PRD 16 §4.2 field 9 — atoms_toon Phase 1 validation (v4.1.0).

    Called after normalize_header_dict_for_validation so the canonical key
    ``atoms_toon`` is always present (legacy ``module`` has been mapped already;
    HDR_MODULE_DEPRECATED is emitted pre-normalization by _warn_legacy_header_yaml_key_names).

    null  → always valid (atoms system not yet attached to this file).
    Non-null rules (all must pass):
      0. Path form → must be repo-relative, not absolute or a URL (HDR_ATOMS_TOON_INVALID)
      1. Suffix    → must end with ATOMS_TOON_SUFFIX (.atoms.toon) (HDR_ATOMS_TOON_SUFFIX)
      2. Year      → canonical paths must use CANONICAL_YEAR (%s),  (HDR_ATOMS_TOON_YEAR)
                     not the raw calendar year (e.g. 2026)
      3. Existence → .atoms.toon file must exist on disk            (HDR_ATOMS_TOON_MISSING)
                     --development mode downgrades to WARN
      4. Collision → path must not equal memory_toon                (HDR_ATOMS_TOON_COLLISION)

    Phase 1 does NOT read or validate the content of the .atoms.toon file.
    """ % CANONICAL_YEAR
    raw_at = hdr.get("atoms_toon")

    # null is always valid
    if raw_at is None:
        return True

    at = str(raw_at).strip()

    # Empty string — must be YAML null when unused
    if not at:
        print(
            "[ERROR] %s: atoms_toon must be YAML null when unused, not empty string "
            "(HDR_ATOMS_TOON_INVALID)" % (file_path,)
        )
        return False

    # Normalise separators once; all subsequent checks use at_norm
    at_norm = at.replace("\\", "/")

    # 0. Relative path guard — reject absolute paths and URLs
    if (
        at_norm.startswith("/")
        or re.match(r"^[a-zA-Z]:[/\\]", at)
        or at_norm.lower().startswith("http://")
        or at_norm.lower().startswith("https://")
    ):
        print(
            "[ERROR] %s: atoms_toon must be a repo-relative path, not an absolute path "
            "or URL (HDR_ATOMS_TOON_INVALID), got %r"
            % (file_path, at)
        )
        return False

    # 1. Suffix check
    allowed_suffixes = (ATOMS_TOON_SUFFIX, ".atom.toon")
    if not at_norm.endswith(allowed_suffixes):
        print(
            "[ERROR] %s: atoms_toon must end with one of %s (HDR_ATOMS_TOON_SUFFIX), got %r"
            % (file_path, ", ".join(allowed_suffixes), at)
        )
        return False

    # 2. Year check — canonical paths must use CANONICAL_YEAR, not the calendar year
    try:
        when_u = str(hdr.get("when_updated") or "").strip()
        tt = str(hdr.get("trust_tier") or "").strip()
        if tt == "canonical" and len(when_u) >= 4 and when_u[:4].isdigit():
            cal_year = int(when_u[:4])
            ladder_year = cal_year - 1000  # e.g. 2026 → 1026 == int(CANONICAL_YEAR)
            m = re.search(r"/canonical/(\d{4})/", at_norm)
            if m:
                yseg = int(m.group(1))
                if 2000 <= yseg <= 2099 and yseg == cal_year:
                    print(
                        "[ERROR] %s: canonical atoms_toon path uses calendar year %s; "
                        "expected trust-ladder year %s (CANONICAL_YEAR=%s) "
                        "(HDR_ATOMS_TOON_YEAR)"
                        % (file_path, cal_year, ladder_year, CANONICAL_YEAR)
                    )
                    return False
    except (TypeError, ValueError):
        pass

    # 3. Existence check
    repo_root = _find_lupopedia_repo_root(os.path.abspath(file_path))
    toon_abs = os.path.normpath(os.path.join(repo_root, at_norm.replace("/", os.sep)))
    if not os.path.isfile(toon_abs):
        if development_mode:
            print(
                "[WARN]  %s: atoms_toon .atoms.toon missing on disk "
                "(HDR_ATOMS_TOON_MISSING): %s"
                % (file_path, at_norm)
            )
        else:
            print(
                "[ERROR] %s: atoms_toon .atoms.toon missing on disk "
                "(HDR_ATOMS_TOON_MISSING): %s\n"
                "   Expected at: %s"
                % (file_path, at_norm, toon_abs.replace("\\", "/"))
            )
            return False

    # 4. Collision check — must not equal memory_toon
    raw_mt = hdr.get("memory_toon") if "memory_toon" in hdr else hdr.get("memory_key")
    if raw_mt is not None:
        mt_norm = str(raw_mt).strip().replace("\\", "/")
        if at_norm == mt_norm:
            print(
                "[ERROR] %s: atoms_toon must not equal memory_toon "
                "(HDR_ATOMS_TOON_COLLISION): %r"
                % (file_path, at_norm)
            )
            return False

    return True


def validate_constants_atom_alignment(hdr, file_path):
    """
    Optional atom-backed constant checks.
    If atoms_toon exists on disk and has global constants shape, validate:
      - trust_tier membership in constants.trust_tiers
      - header field count against constants.header_fields.count
      - header key order against constants.header_fields.order
      - actor_id membership in constants.actors values (only if actor_id exists in header)
    Emits HDR_ATOM_MISMATCH on any mismatch.
    """
    raw_at = hdr.get("atoms_toon")
    if raw_at is None:
        return True

    at = str(raw_at).strip()
    if not at:
        return True

    at_norm = at.replace("\\", "/")
    repo_root = _find_lupopedia_repo_root(os.path.abspath(file_path))
    atom_abs = os.path.normpath(os.path.join(repo_root, at_norm.replace("/", os.sep)))
    if not os.path.isfile(atom_abs):
        return True

    try:
        with open(atom_abs, "r", encoding="utf-8") as af:
            atom_data = json.load(af)
    except Exception as exc:
        print(
            "[WARN]  %s: could not parse atoms_toon JSON (%s); skipping atom checks"
            % (file_path, exc)
        )
        return True

    if not isinstance(atom_data, dict):
        return True

    constants = atom_data.get("constants")
    if not isinstance(constants, dict):
        return True

    trust_tiers = constants.get("trust_tiers")
    if isinstance(trust_tiers, dict):
        tier = str(hdr.get("trust_tier", "")).strip()
        if tier and tier not in trust_tiers:
            print(
                "[WARN]  %s: trust_tier %r not present in atoms_toon constants.trust_tiers "
                "(HDR_ATOM_MISMATCH)"
                % (file_path, tier)
            )
            return True

    header_fields = constants.get("header_fields")
    if isinstance(header_fields, dict):
        atom_count = header_fields.get("count")
        try:
            atom_count_int = int(atom_count)
        except (TypeError, ValueError):
            atom_count_int = None
        if atom_count_int is not None and atom_count_int != len(V3_HEADER_KEYS_ORDERED):
            print(
                "[ERROR] %s: atoms_toon header_fields.count=%s, expected %s "
                "(HDR_ATOM_MISMATCH)"
                % (file_path, atom_count_int, len(V3_HEADER_KEYS_ORDERED))
            )
            return False

        atom_order = header_fields.get("order")
        if isinstance(atom_order, list):
            atom_order_str = [str(x).strip() for x in atom_order]
            expected_order = list(V3_HEADER_KEYS_ORDERED)
            if atom_order_str != expected_order:
                print(
                    "[ERROR] %s: atoms_toon header_fields.order mismatch with validator canonical order "
                    "(HDR_ATOM_MISMATCH)"
                    % (file_path,)
                )
                return False

    actor_map = constants.get("actors")
    if isinstance(actor_map, dict) and "actor_id" in hdr and hdr.get("actor_id") is not None:
        try:
            actor_id = int(str(hdr.get("actor_id")).strip())
        except (TypeError, ValueError):
            actor_id = None
        if actor_id is not None:
            atom_actor_ids = set()
            for _k, _v in actor_map.items():
                try:
                    atom_actor_ids.add(int(_v))
                except (TypeError, ValueError):
                    continue
            if atom_actor_ids and actor_id not in atom_actor_ids:
                print(
                    "[ERROR] %s: actor_id %s not found in atoms_toon constants.actors "
                    "(HDR_ATOM_MISMATCH)"
                    % (file_path, actor_id)
                )
                return False

    return True


def warn_legacy_last_modified_utc(hdr, file_path):
    """
    Emit HDR_LAST_MODIFIED_RENAMED if the header still contains the old
    last_modified_utc field. This was renamed to questions_toon in PRD 16 v4.0.99.
    """
    if "last_modified_utc" in hdr:
        print(
            "[WARN] %s (HDR_LAST_MODIFIED_RENAMED): last_modified_utc is deprecated "
            "and was renamed to questions_toon in PRD 16 v4.0.99 §4.2 field 6. "
            "Run normalize_lupopedia_md_header_25.py to migrate." % file_path
        )
    return True


def queue_orphan_in_anubis(file_path, conn, prefix):
    """Add orphan file to anubis_queue table if not already queued"""
    from import_content import _safe_sql_identifier
    queue_table = _safe_sql_identifier(prefix + "anubis_queue")
    
    file_hash = get_file_hash(file_path)
    detected_utc = get_current_timestamp()
    
    try:
        with conn.cursor() as cur:
            # Idempotency check
            cur.execute(
                "SELECT queue_id FROM `%s` WHERE file_path=%%s AND status IN ('pending', 'processing') LIMIT 1"
                % queue_table,
                (file_path,)
            )
            if cur.fetchone():
                print("[ANUBIS] File already in queue: %s" % file_path)
                return

            cur.execute(
                "INSERT INTO `%s` (file_path, file_hash, detected_utc, priority, status) VALUES (%%s, %%s, %%s, %%s, %%s)"
                % queue_table,
                (file_path, file_hash, detected_utc, 10, 'pending')
            )
            conn.commit()
            print("[ANUBIS] Added orphan to queue: %s" % file_path)
    except Exception as e:
        print("[ANUBIS] Failed to queue orphan %s: %s" % (file_path, e))


def check_db_sync_universal(file_path, header_data, check_db_flag=False, queue_orphans=False):
    """
    Enforce PRD 16 §12.1.2 resolution model when --check-db is enabled:
      1) content_id NULL/missing     -> file-first discovery mode
      2) content_id non-null + valid -> database-first reconciliation mode
      3) content_id non-null invalid -> repair state (not trusted authority)

    Also warns if file declares outbound_edges or lupopedia.history but DB has no matching rows.
    """
    if not check_db_flag:
        return
    if not isinstance(header_data, dict):
        print("[WARN] %s: --check-db skipped: could not use parsed header" % (file_path,))
        return
    headers = header_data.get("lupopedia.headers")
    if not isinstance(headers, dict):
        print("[WARN] %s: --check-db skipped: no lupopedia.headers mapping" % (file_path,))
        return
    
    cid = headers.get("content_id")
    is_orphan = False
    resolution_state = None
    
    if cid is None or str(cid).strip() == "":
        is_orphan = True
        cid_int = None
        resolution_state = RESOLUTION_STATE_FILE_FIRST
    else:
        try:
            cid_int = int(cid)
        except (TypeError, ValueError):
            print(
                "[ERROR] %s: content_id present but non-numeric (%r) -> repair state "
                "(HDR_CONTENT_ID_INVALID_REPAIR_STATE)"
                % (file_path, cid)
            )
            print(
                "[STATE] %s: %s (content_id present but invalid)"
                % (file_path, RESOLUTION_STATE_REPAIR)
            )
            return

    try:
        import pymysql
    except ImportError:
        print("[WARN] %s: --check-db skipped: pymysql not installed" % (file_path,))
        return

    try:
        from lib.db_connection import get_connection_params, get_connection
        from import_content import _load_table_prefix_from_config, _safe_sql_identifier
    except Exception as e:
        print("[WARN] %s: --check-db skipped: config import failed (%s)" % (file_path, e))
        return

    def _norm_path(p):
        if p is None:
            return ""
        return str(p).strip().replace("\\", "/").lstrip("/")

    def _row_get(row_obj, key, idx=None):
        if row_obj is None:
            return None
        if isinstance(row_obj, dict):
            return row_obj.get(key)
        if idx is not None:
            try:
                return row_obj[idx]
            except Exception:
                return None
        return None

    prefix = _load_table_prefix_from_config()
    edges_table = _safe_sql_identifier(prefix + "edges")
    contents_table = _safe_sql_identifier(prefix + "contents")
    conn = None
    try:
        conn = get_connection()
        
        if not is_orphan:
            with conn.cursor() as cur:
                cur.execute(
                    "SELECT file_path_from_root, revision_history FROM `%s` WHERE content_id=%%s AND is_deleted=0 LIMIT 1"
                    % contents_table,
                    (cid_int,),
                )
                row = cur.fetchone()
                if not row:
                    print(
                        "[ERROR] %s: content_id %s present but not found in DB (is_deleted=0) -> repair state "
                        "(HDR_CONTENT_ID_DB_MISSING)"
                        % (file_path, cid_int)
                    )
                    is_orphan = True
                    resolution_state = RESOLUTION_STATE_REPAIR
                else:
                    db_path = _norm_path(_row_get(row, "file_path_from_root", 0))
                    header_path = _norm_path(headers.get("file_path_from_root"))
                    if db_path and header_path and db_path != header_path:
                        print(
                            "[ERROR] %s: file_path_from_root in file (%r) != DB lupo_contents (%r) for content_id %s "
                            "-> repair state (HDR_CONTENT_ID_DB_PATH_MISMATCH)"
                            % (file_path, header_path, db_path, cid_int)
                        )
                        is_orphan = True
                        resolution_state = RESOLUTION_STATE_REPAIR
                    else:
                        resolution_state = RESOLUTION_STATE_DATABASE_FIRST
                    db_rh = _row_get(row, "revision_history", 1)
                    cur.execute(
                        "SELECT COUNT(*) AS c FROM `%s` WHERE left_object_type=%%s AND left_object_id=%%s "
                        "AND edge_category=%%s AND is_deleted=0" % edges_table,
                        ("content", cid_int, "lupopedia_header"),
                    )
                    erow = cur.fetchone()
                    edge_count_raw = _row_get(erow, "c", 0)
                    edge_count = int(edge_count_raw) if edge_count_raw is not None else 0

                    file_edges = header_data.get("lupopedia.edges")
                    outbound = []
                    if isinstance(file_edges, dict):
                        oe = file_edges.get("outbound_edges")
                        if isinstance(oe, list):
                            outbound = oe
                    file_hist = header_data.get("lupopedia.history")

                    if len(outbound) > 0 and edge_count == 0:
                        print(
                            "[WARN] %s: File has outbound_edges but DB has 0 lupo_edges "
                            "(edge_category=lupopedia_header). Run: python scripts/import_content.py %r"
                            % (file_path, file_path)
                        )
                    if file_hist is not None:
                        empty_db_hist = db_rh is None
                        if isinstance(db_rh, str) and not str(db_rh).strip():
                            empty_db_hist = True
                        if isinstance(db_rh, (dict, list)) and not db_rh:
                            empty_db_hist = True
                        if empty_db_hist:
                            print(
                                "[WARN] %s: File has lupopedia.history but lupo_contents.revision_history is empty. "
                                "Run: python scripts/import_content.py %r"
                                % (file_path, file_path)
                            )
        
        if resolution_state is None and is_orphan:
            resolution_state = RESOLUTION_STATE_FILE_FIRST

        if resolution_state is not None:
            print("[STATE] %s: %s" % (file_path, resolution_state))

        if is_orphan and queue_orphans:
            queue_orphan_in_anubis(file_path, conn, prefix)
            
    except Exception as e:
        print("[WARN] %s: --check-db failed: %s" % (file_path, e))
    finally:
        if conn is not None:
            try:
                conn.close()
            except Exception:
                pass


_REPO_ROOT_BY_DIR: dict[str, str] = {}


def _find_lupopedia_repo_root(start_file):
    """
    Walk upward from the markdown file until a directory contains both
    scripts and includes (repo root). Edge ``to`` paths are relative to that root.
    """
    d0 = os.path.dirname(os.path.abspath(start_file))
    if d0 in _REPO_ROOT_BY_DIR:
        return _REPO_ROOT_BY_DIR[d0]
    d = d0
    markers = ("scripts", "includes")
    for _ in range(40):
        if all(os.path.isdir(os.path.join(d, m)) for m in markers):
            _REPO_ROOT_BY_DIR[d0] = d
            return d
        parent = os.path.dirname(d)
        if parent == d:
            break
        d = parent
    fallback = os.path.dirname(os.path.abspath(start_file))
    _REPO_ROOT_BY_DIR[d0] = fallback
    return fallback


def _is_external_edge_target(tp: str) -> bool:
    t = str(tp).strip()
    return t.startswith("http://") or t.startswith("https://")


def validate_edge_targets(file_path, edges, strict=False):
    """
    Validate edge file targets on disk.
    ``edges`` may be the parsed ``lupopedia.edges`` dict (preferred) or a raw YAML
    fragment string (legacy) under ``lupopedia.edges``.
    If strict=True (--check-links), missing repo-relative targets fail validation.
    External http(s) ``to:`` values are skipped (not checked as local paths).
    """
    if not edges:
        return True

    if yaml is None:
        print("[WARN] %s: Skipping edge YAML parse (PyYAML unavailable)" % (file_path,))
        return True

    outbound = []
    try:
        if isinstance(edges, dict):
            ob = edges.get("outbound_edges")
            if isinstance(ob, list):
                outbound = ob
        else:
            edge_data = yaml.safe_load("lupopedia.edges:\n" + str(edges))
            if isinstance(edge_data, dict):
                root = edge_data.get("lupopedia.edges")
                if isinstance(root, dict):
                    ob = root.get("outbound_edges")
                    if isinstance(ob, list):
                        outbound = ob
    except Exception as e:
        print("[WARN] %s: Could not parse edges: %s" % (file_path, e))
        return False if strict else True

    repo_root = _find_lupopedia_repo_root(file_path)
    missing = []
    for edge in outbound:
        if not isinstance(edge, dict):
            continue
        target_path = edge.get("to")
        if not target_path:
            continue
        tp = str(target_path).strip().replace("\\", "/")
        if _is_external_edge_target(tp):
            continue
        full_path = os.path.normpath(os.path.join(repo_root, tp))
        if not os.path.exists(full_path):
            if strict:
                print("[ERROR] %s: Broken edge — target not found: %s" % (file_path, tp))
                print("   Expected at: %s" % (full_path,))
                missing.append(tp)
            else:
                print("[WARN] %s: Edge target not found: %s" % (file_path, tp))
                print("   Expected at: %s" % (full_path,))

    if strict and missing:
        return False
    return True


def _ymdhis_compare_int(val):
    """Normalize header/footer UTC to 14-digit int for comparison; None if not comparable."""
    if val is None:
        return None
    s = str(val).strip().strip('"').strip("'")
    if not s.isdigit():
        return None
    if len(s) == 8:
        s = s + "000000"
    if len(s) != 14:
        return None
    return int(s)


def validate_when_updated_ge_last_verified(parsed, file_path):
    """
    Logical check: lupopedia.headers.when_updated must not be older than lupopedia.footer.last_verified
    (same-timestamp batch updates allowed).
    """
    hdr = parsed.get("lupopedia.headers")
    ftr = parsed.get("lupopedia.footer")
    if not isinstance(hdr, dict) or not isinstance(ftr, dict):
        return True
    wu = _ymdhis_compare_int(hdr.get("when_updated"))
    lv = _ymdhis_compare_int(ftr.get("last_verified"))
    if wu is None or lv is None:
        return True
    if wu < lv:
        print(
            "[ERROR] %s: when_updated (%s) < last_verified (%s) — impossible ordering"
            % (file_path, hdr.get("when_updated"), ftr.get("last_verified"))
        )
        return False
    return True

def check_staleness(file_path, content):
    """Optional YAML body scan for lupopedia.footer.last_verified (informative only). PRD 16 §19.2: 14-digit UTC."""
    match = re.search(r'last_verified:\s*"?(\d{8,14})"?', content)
    if match:
        lv = match.group(1)
        if len(lv) == 8:
            print(
                "[WARN]  %s: last_verified should be 14-digit UTC (YYYYMMDDHHIISS), got 8-digit (legacy day-only)"
                % (file_path,)
            )
            lv_cmp = int(lv + "000000")
        elif len(lv) == 14:
            lv_cmp = int(lv)
        else:
            print("[WARN]  %s: last_verified has invalid length: %s" % (file_path, lv))
            return True
        if lv_cmp < CUTOFF_14:
            print("[WARN]  %s: Stale footer (last_verified < %s)" % (file_path, CUTOFF_DAY))
            print("   Run regenerate_headers_for_stale_files.py to update")
    return True

def validate_federation_node_id(file_path, fed_node):
    """Core repo paths under docs/ and rules/ must not claim external federation_node_id >= 2."""
    if file_path.startswith("docs/") or file_path.startswith("rules/"):
        if fed_node >= 2:
            print(
                "[ERROR] %s: federation_node_id must be 0 or 1 for docs/ and rules/ paths, got %s"
                % (file_path, fed_node)
            )
            return False

    if file_path.startswith("content/federation_node_id/"):
        parts = file_path.split("/")
        try:
            expected_node = int(parts[2])
        except (IndexError, ValueError):
            return True
        if fed_node != expected_node:
            print(
                "[ERROR] %s: federation_node_id %s does not match path segment %s"
                % (file_path, fed_node, expected_node)
            )
            return False

    return True

def validate_web_path(web_path, fed_node, file_path):
    """Validate web_path based on federation node"""
    path_rules = file_path.replace("\\", "/")
    # For external nodes (2+), web_path must be a valid URL pointing to canonical source
    if fed_node >= 2:
        if not web_path.startswith(("http://", "https://")):
            print(
                "[ERROR] %s: External federation node %s requires web_path to be a valid URL (http:// or https://)"
                % (file_path, fed_node)
            )
            print("   Found: %s" % (web_path,))
            return False, "External web_path must be a URL"

        prefix = "content/federation_node_id/%s/" % fed_node
        if not path_rules.startswith(prefix):
            print(
                "[WARN]  %s: External federation node %s should be in content/federation_node_id/%s/"
                % (file_path, fed_node, fed_node)
            )
            return False, "Wrong federation directory"
    
    # For internal nodes (0, 1), web_path can be relative or absolute
    return True, "Valid web_path"


def validate_web_path_https(web_path, file_path, development_mode):
    """
    PRD 16 §4.2 field 5 — absolute URLs MUST use https:// unless --development.
    Relative web_path values (no scheme) are unchanged.
    """
    wp = str(web_path).strip()
    if not wp.startswith("http://"):
        return True
    if development_mode:
        return True
    print(
        "[ERROR] %s: web_path MUST use https:// (HDR_WEB_PATH_HTTP); got %r"
        % (file_path, wp)
    )
    print(
        "   Hint: rewrite as https://... or pass --development for local policy."
    )
    return False


def validate_memory_key_toon_file_exists(
    hdr, file_path, strict_memory_files=False, development_mode=False
):
    """
    When memory_toon ends with .toon, the path under repo root should exist (PRD 16 sidecar discipline).

    Accepts legacy memory_key during migration. Default: WARN if missing (HDR_MEMORY_TOON_MISSING).
    With strict_memory_files=True: ERROR. Skipped under --development.
    """
    if development_mode:
        return True
    raw = hdr.get("memory_toon") if "memory_toon" in hdr else hdr.get("memory_key")
    mk = str(raw or "").strip()
    if not mk.endswith(".toon"):
        return True
    repo_root = _find_lupopedia_repo_root(os.path.abspath(file_path))
    toon_abs = os.path.normpath(os.path.join(repo_root, mk.replace("/", os.sep)))
    if os.path.isfile(toon_abs):
        return True
    rel_mk = mk.replace("\\", "/")
    hint = "python scripts/generate_memory_from_header.py %s" % (
        file_path.replace("\\", "/"),
    )
    msg_tail = (
        "[ERROR] %s: memory_toon .toon missing on disk (HDR_MEMORY_TOON_MISSING): %s\n"
        "   Expected at: %s\n"
        "   Run: %s"
        % (file_path, rel_mk, toon_abs.replace("\\", "/"), hint)
    )
    if strict_memory_files:
        print(msg_tail)
        return False
    print(
        "[WARN]  %s: memory_toon .toon missing on disk (HDR_MEMORY_TOON_MISSING): %s\n"
        "   Expected at: %s\n"
        "   Run: %s"
        % (file_path, rel_mk, toon_abs.replace("\\", "/"), hint)
    )
    return True


def validate_memory_key_json_master_pair(
    hdr, file_path, strict_memory_pair, development_mode=False
):
    """
    For trust_tier seed|canonical: when the declared .toon exists on disk, expect a
    sibling .json master (same basename). Missing JSON → WARN, or ERROR with --strict-memory-pair.
    Skipped entirely when development_mode (PRD 16 §12.4).

    Accepts legacy memory_key during migration; new headers MUST use memory_toon.
    """
    if development_mode:
        return True
    tt = str(hdr.get("trust_tier") or "").strip()
    if tt not in ("seed", "canonical"):
        return True
    raw = hdr.get("memory_toon") if "memory_toon" in hdr else hdr.get("memory_key")
    mk = str(raw or "").strip()
    if not mk.endswith(".toon"):
        return True
    repo_root = _find_lupopedia_repo_root(os.path.abspath(file_path))
    toon_abs = os.path.normpath(os.path.join(repo_root, mk.replace("/", os.sep)))
    if not os.path.isfile(toon_abs):
        return True
    json_rel = mk[:-5] + ".json"
    json_abs = os.path.normpath(os.path.join(repo_root, json_rel.replace("/", os.sep)))
    if os.path.isfile(json_abs):
        return True
    hint = (
        "python scripts/json_to_toon.py --json %r --toon %r"
        % (json_rel.replace("\\", "/"), mk.replace("\\", "/"))
    )
    if strict_memory_pair:
        print(
            "[ERROR] %s: JSON master missing for existing .toon (trust_tier=%s) "
            "(HDR_MEMORY_JSON_MASTER / SIDECAR_JSON_MASTER_MISSING)"
            % (file_path, tt)
        )
        print("   Expected: %s" % json_rel.replace("\\", "/"))
        print("   Run: %s" % hint)
        return False
    print(
        "[WARN]  %s: JSON master not found for memory_toon (HDR_MEMORY_JSON_MASTER / "
        "SIDECAR_JSON_MASTER_MISSING); expected %s"
        % (file_path, json_rel.replace("\\", "/"))
    )
    print("   Run: %s" % hint)
    return True


def _warn_legacy_header_yaml_key_names(file_path, hdr):
    """
    Emit HDR_LEGACY_FIELD_NAME / HDR_PK_LEGACY_ALIAS / HDR_*_RENAMED / HDR_MODULE_DEPRECATED
    when source YAML still uses pre-4.1.0 or pre-4.1.1 field names.
    Must be called BEFORE normalize_header_dict_for_validation so that legacy keys are still
    present in the raw dict.
    """
    # Pre-v4.0.99 aliases (prd_* → content_* via LEGACY_KEYS_V4)
    legacy = [k for k in ("prd_id", "prd_slug", "parent_prd") if k in hdr]
    if legacy:
        print(
            "[WARN]  %s: legacy YAML keys %s — migrate to content_id, content_slug, content_parent_id "
            "(HDR_LEGACY_FIELD_NAME)"
            % (file_path, ", ".join(legacy))
        )
    # v4.1.1 pk_* → content_* aliases (HDR_PK_LEGACY_ALIAS)
    for pk_key, canonical in LEGACY_FIELD_ALIASES.items():
        if pk_key in hdr:
            print(
                "[WARN] %s: legacy %s field found. Migrate to %s. (HDR_PK_LEGACY_ALIAS)"
                % (file_path, pk_key, canonical)
            )
    if "memory_key" in hdr and "memory_toon" not in hdr:
        print(
            "[WARN]  %s: deprecated field memory_key found (HDR_MEMORY_KEY_RENAMED): "
            "rename to memory_toon per PRD 16 v4.1.0 §4.2 field 8" % (file_path,)
        )
    if "dialog_transcript" in hdr and "transcript_jsonl" not in hdr:
        print(
            "[WARN]  %s: deprecated field dialog_transcript found (HDR_DIALOG_TRANSCRIPT_RENAMED): "
            "rename to transcript_jsonl per PRD 16 v4.1.0 §4.2 field 10" % (file_path,)
        )
    if "module" in hdr and "atoms_toon" not in hdr:
        print(
            "[WARN]  %s: deprecated field module found (HDR_MODULE_DEPRECATED): "
            "rename to atoms_toon per PRD 16 v4.0.99 §4.2 field 9" % (file_path,)
        )


def warn_trust_tier_status_alignment(hdr, content, file_path):
    """
    Doctrine warning-only guardrail:
    trust_tier must not contradict status intent.
    """
    tier = str(hdr.get("trust_tier") or "").strip().lower()
    status = str(hdr.get("status") or "").strip().lower()
    proposed_marker = bool(
        re.search(r"(?im)^\s*(?:\*\*)?status\s*:\s*proposed\b", content or "")
    )

    if proposed_marker and tier != "development":
        print(
            "[WARN]  %s: STATUS indicates proposed/non-canonical but trust_tier=%r; "
            "expected development (HDR_TRUST_TIER_STATUS_MISMATCH)"
            % (file_path, tier)
        )
        return True

    canonical_statuses = set(["active", "approved", "implemented"])
    development_statuses = set(["draft", "review", "not_started", "in_progress", "blocked"])

    if status in canonical_statuses and tier == "development":
        print(
            "[WARN]  %s: status=%r usually implies canonical trust_tier; got development "
            "(HDR_TRUST_TIER_STATUS_MISMATCH)"
            % (file_path, status)
        )
    elif status in development_statuses and tier == "canonical":
        print(
            "[WARN]  %s: status=%r usually implies development trust_tier; got canonical "
            "(HDR_TRUST_TIER_STATUS_MISMATCH)"
            % (file_path, status)
        )
    return True


def _validate_lupopedia_headers_payload(
    file_path,
    hdr,
    parsed,
    content,
    strict_edge_links,
    tail_start_offset=None,
    development_mode=False,
    strict_memory_pair=False,
    strict_memory_year=False,
    strict_memory_files=False,
    strict_mode=False,
):
    """
    Shared semantic validation for lupopedia.headers (Markdown or Python).

    tail_start_offset: if set, scan for hardcoded version only in content[offset:];
    if None, use Markdown front-matter tail after first '---' block.
    """
    if any(k in hdr for k in ("note", "namespace")):
        print(
            "[ERROR] %s: forbidden header key note/namespace (use summary/atoms_toon per PRD 16 v4.0.99) (HDR_FORBIDDEN_KEY)"
            % (file_path,)
        )
        return False, None

    if not validate_deprecated_header_fields(hdr, file_path):
        return False, None

    if not validate_header_format_version_exact(hdr, file_path):
        return False, None

    if not validate_legacy_pk_alias_vs_claimed_version(hdr, file_path):
        return False, None

    _warn_legacy_header_yaml_key_names(file_path, hdr)

    hdr = normalize_header_dict_for_validation(hdr)
    if isinstance(parsed, dict):
        parsed["lupopedia.headers"] = hdr

    # atoms_toon empty-string guard: catch it before required-fields so the error
    # message is clear; full Phase 1 validation runs below via validate_atoms_toon.
    if hdr.get("atoms_toon") == "":
        print(
            "[ERROR] %s: atoms_toon must be YAML null when unused, not empty string "
            "(HDR_ATOMS_TOON_INVALID)" % (file_path,)
        )
        return False, None

    if not validate_required_header_fields(hdr, file_path):
        return False, None

    fmt_v = hdr.get("header_format_version")
    if _is_valid_header_format_version(fmt_v):
        ok_mem, msg_mem = validate_memory_key(hdr, file_path, strict_mode=strict_mode)
        if not ok_mem:
            print(msg_mem)
            return False, None
        if not validate_memory_key_path_shape(
            hdr, file_path, strict_memory_year=strict_memory_year
        ):
            return False, None
        if not validate_memory_key_toon_file_exists(
            hdr, file_path, strict_memory_files=strict_memory_files, development_mode=development_mode
        ):
            return False, None
        if not validate_memory_key_json_master_pair(
            hdr, file_path, strict_memory_pair, development_mode
        ):
            return False, None

    if not validate_header_scalar_values(hdr, file_path, strict_mode=strict_mode):
        return False, None

    if not validate_prd_cluster(hdr, file_path):
        return False, None

    if not validate_thread_id_format(
        hdr.get("thread_key", hdr.get("thread_id")), file_path, hdr.get("artifact_type")
    ):
        return False, None
    if not validate_dialog_transcript_triple(hdr, file_path, strict_mode=strict_mode):
        return False, None
    # Validate when_updated timestamp; questions_toon (field 7) validated separately below.
    if not validate_ymdhis_pair(hdr.get("when_updated"), None, file_path):
        return False, None
    # questions_toon: null or path ending .questions.toon (PRD 16 §4.2 field 7)
    if not validate_questions_toon(hdr.get("questions_toon"), file_path):
        return False, None
    # atoms_toon: null or path ending .atoms.toon — Phase 1 (PRD 16 §4.2 field 9)
    if not validate_atoms_toon(hdr, file_path, development_mode=development_mode):
        return False, None
    # Optional atom-backed consistency checks (trust tiers, field order/count, actor_id where present)
    if not validate_constants_atom_alignment(hdr, file_path):
        return False, None
    warn_trust_tier_status_alignment(hdr, content, file_path)
    # Warn if legacy last_modified_utc still present (renamed in v4.0.99)
    warn_legacy_last_modified_utc(hdr, file_path)

    # New deterministic validations (v4.0.93)
    if not validate_field_ordering(hdr, file_path):
        return False, None
    if not validate_ascii_safe_names(hdr, file_path):
        return False, None
    if not requires_v419_rules(hdr):
        if not validate_content_id(hdr, file_path, fmt_v):
            return False, None
    repo_root = _find_lupopedia_repo_root(os.path.abspath(file_path))
    if not validate_header_v419(hdr, file_path, repo_root=repo_root):
        return False, None
    if not validate_actor_folder_alignment(hdr, file_path):
        return False, None

    header_version = None
    if fmt_v is not None:
        fv = str(fmt_v).strip()
        if (fv.startswith('"') and fv.endswith('"')) or (fv.startswith("'") and fv.endswith("'")):
            fv = fv[1:-1].strip()
        mm = re.match(r"^4\.(\d+)\.(\d+)$", fv)
        if mm:
            header_version = (4, int(mm.group(1)), int(mm.group(2)))

    schema = str(hdr.get("lupopedia.schema", "")).strip()
    if not validate_schema(schema, file_path, header_version=header_version, strict_mode=strict_mode):
        return False, None

    artifact_type = str(hdr.get("artifact_type", "")).strip()
    artifact_kind = str(hdr.get("artifact_kind", "")).strip()
    if not validate_cross_fields(schema, artifact_type, artifact_kind, file_path, header_version=header_version, strict_mode=strict_mode):
        return False, None

    # Validate removed fields (content_slug, pk_slug, prd_slug)
    if not validate_removed_fields(hdr, file_path, strict_mode=strict_mode):
        return False, None

    if not requires_v419_rules(hdr):
        if not validate_content_parent_id(
            hdr, file_path, development_mode=development_mode
        ):
            return False, None

    check_staleness(file_path, content)

    web_path = hdr.get("web_path")
    if web_path is None or (isinstance(web_path, str) and not str(web_path).strip()):
        print("[ERROR] %s: Missing or empty web_path" % (file_path,))
        return False, None
    web_path = str(web_path).strip()

    if not validate_web_path_https(web_path, file_path, development_mode):
        return False, None

    try:
        fed_node = int(hdr["federation_node_id"])
    except (KeyError, TypeError, ValueError):
        print("[ERROR] %s: federation_node_id must be an integer" % (file_path,))
        return False, None

    is_external = web_path.startswith(("http://", "https://"))
    is_core_site = is_external and "lupopedia.com" in web_path

    is_internal = False
    if fed_node == 0 and is_core_site:
        is_internal = True
    elif fed_node == 1 and not is_external:
        is_internal = True
    elif fed_node >= 2:
        is_internal = False
    else:
        if is_core_site:
            is_internal = True
        elif not is_external:
            is_internal = True
        else:
            is_internal = False

    if is_internal:
        valid, error = validate_web_path(web_path, fed_node, file_path)
        if not valid:
            print("[ERROR] %s: %s" % (file_path, error))
            return False, None
    else:
        if fed_node < 2:
            print(
                "[ERROR] %s: federation_node_id must be >= 2 for external web_path (got %s)"
                % (file_path, fed_node)
            )
            return False, None
        valid, error = validate_web_path(web_path, fed_node, file_path)
        if not valid:
            print("[ERROR] %s: %s" % (file_path, error))
            return False, None
        expected_path = "content/federation_node_id/%s/" % fed_node
        path_rules = file_path.replace("\\", "/")
        if not path_rules.startswith(expected_path):
            print("[WARN] %s: External research files should be in %s" % (file_path, expected_path))

    path_rules = file_path.replace("\\", "/")
    if not validate_federation_node_id(path_rules, fed_node):
        return False, None

    deprecated_fields = ["lupopedia.version", "system_version"]
    for field in deprecated_fields:
        if re.search(re.escape(field) + r":\s*", content):
            print("[ERROR] %s: Deprecated field %r found. Remove it." % (file_path, field))
            return False, None

    if tail_start_offset is not None:
        content_to_check = content[int(tail_start_offset) :]
    else:
        init_section_end = content.find("---\n")
        if init_section_end > 0:
            content_to_check = content[init_section_end + 4 :]
        else:
            content_to_check = content
    if re.search(r'^\s*version:\s*"4\.', content_to_check, re.MULTILINE):
        print(
            "[ERROR] %s: Hardcoded version string found. Use when_updated instead." % (file_path,)
        )
        return False, None

    if "content_id" in hdr:
        cid = hdr.get("content_id")
        if cid is None:
            print("[OK] %s: content_id present (null)" % (file_path,))
        else:
            print("[OK] %s: content_id present (%s)" % (file_path, str(cid).strip()))

    eb = parsed.get("lupopedia.edges")
    if strict_edge_links and eb is not None:
        if not isinstance(eb, dict):
            print("[ERROR] %s: lupopedia.edges must be a mapping" % (file_path,))
            return False, None
        if not validate_edge_targets(file_path, eb, strict=True):
            return False, None

    if not validate_when_updated_ge_last_verified(parsed, file_path):
        return False, None

    return True, parsed


def _print_pass_line(file_path: str, quiet: bool) -> None:
    if not quiet:
        print("[PASS] %s - valid PRD 16 header (v4 envelope)" % (file_path,))


def _python_header_text_for_unicode_scan(lines):
    """First 25 header lines (26 with shebang); box chars in code body are not scanned."""
    if not lines:
        return ""
    if lines[0].startswith("#!"):
        n = 26
    else:
        n = 25
    return "\n".join(lines[:n])


def validate_python_file(
    file_path,
    content,
    strict_edge_links=False,
    quiet=False,
    development_mode=False,
    strict_memory_pair=False,
    strict_memory_year=False,
    strict_memory_files=False,
    reject_legacy_envelope=False,
    strict_mode=False,
):
    """
    Validate LUPOPEDIA HEADERS in a Python file (# comment block).

    Returns (success, parsed_dict_or_None).
    """
    if yaml is None:
        print("[ERROR] %s: PyYAML is required for .py header validation" % (file_path,))
        return False, None

    lines = content.split("\n")
    if not validate_no_unicode_box_chars(_python_header_text_for_unicode_scan(lines), file_path):
        return False, None

    ok_env, has_shebang, yaml_inner = validate_python_header_envelope(
        lines,
        file_path,
        reject_legacy_envelope=reject_legacy_envelope,
    )
    if not ok_env:
        return False, None

    # First line after the 25-line # block (1-based: 27 with shebang, 26 without) must be body (PRD 16 §4.3).
    first_body_idx = 26 if has_shebang else 25
    body_line_1based = first_body_idx + 1
    if len(lines) <= first_body_idx:
        if development_mode:
            print(
                "[WARN]  %s: no body line after header block (HDR_EMPTY_BODY) — allowed under --development"
                % (file_path,)
            )
        else:
            print(
                "[ERROR] %s: file must extend past header — body must start on line %d (HDR_EMPTY_BODY)"
                % (file_path, body_line_1based)
            )
            return False, None
    elif not lines[first_body_idx].strip():
        if development_mode:
            print(
                "[WARN]  %s: line %d empty after header block (HDR_EMPTY_BODY) — allowed under --development"
                % (file_path, body_line_1based)
            )
        else:
            print(
                "[ERROR] %s: line %d must start body (no blank-only line after closing # fence) (HDR_EMPTY_BODY)"
                % (file_path, body_line_1based)
            )
            return False, None

    try:
        data = yaml.safe_load(yaml_inner)
    except Exception as e:
        print("[ERROR] %s: YAML parse failed in python header: %s" % (file_path, e))
        return False, None

    if not isinstance(data, dict):
        print("[ERROR] %s: python header YAML must be a mapping" % (file_path,))
        return False, None

    hdr = data.get("lupopedia.headers")
    if not isinstance(hdr, dict):
        print("[ERROR] %s: lupopedia.headers must be a mapping" % (file_path,))
        return False, None

    parsed = data
    off = _python_body_offset_after_header(lines, has_shebang)
    if off is None:
        print("[ERROR] %s: could not compute python body offset (HDR_PYTHON_HEADER)" % (file_path,))
        return False, None

    ok, out = _validate_lupopedia_headers_payload(
        file_path,
        hdr,
        parsed,
        content,
        strict_edge_links,
        tail_start_offset=off,
        development_mode=development_mode,
        strict_memory_pair=strict_memory_pair,
        strict_memory_year=strict_memory_year,
        strict_memory_files=strict_memory_files,
        strict_mode=strict_mode,
    )
    if ok:
        _print_pass_line(file_path, quiet)
    return ok, out


def _php_try_hash_comment_header(
    lines,
    file_path,
    reject_legacy_envelope=False,
    suppress_legacy_envelope_warn=False,
):
    """
    Optional PHP path: ``#!/usr/bin/env php`` + ``<?php`` + 25-line ``#`` grid, or ``<?php`` + ``#`` grid.

    Returns:
    - ``('use_star', None, None)`` — use the ``/**`` … ``*/`` envelope parser.
    - ``('bad', None, None)`` — opening ``#`` grid matched but inner validation failed (fatal).
    - ``('ok', yaml_inner, first_body_idx)`` — hash header validated.
    """
    if not lines:
        return ("use_star", None, None)
    header_slice = None
    first_body_idx = None
    if lines[0].startswith("#!"):
        if lines[0].strip() != CANONICAL_PHP_SHEBANG:
            return ("use_star", None, None)
        if len(lines) < 27:
            return ("use_star", None, None)
        if lines[1].strip() != "<?php":
            print(
                "[ERROR] %s: PHP shebang must be followed by <?php on line 2 (HDR_PHP_HEADER)"
                % (file_path,)
            )
            return ("bad", None, None)
        if not PY_HEADER_SEP_LINE_RE.match(lines[2]):
            return ("use_star", None, None)
        header_slice = lines[2:27]
        first_body_idx = 27
    elif lines[0].strip() == "<?php":
        if len(lines) < 26:
            return ("use_star", None, None)
        if not PY_HEADER_SEP_LINE_RE.match(lines[1]):
            return ("use_star", None, None)
        header_slice = lines[1:26]
        first_body_idx = 26
    else:
        return ("use_star", None, None)
    ok, yaml_blob = _validate_hash_comment_25line_slice(
        header_slice,
        file_path,
        reject_legacy_envelope,
        suppress_legacy_envelope_warn,
        label="php",
    )
    if not ok:
        return ("bad", None, None)
    return ("ok", yaml_blob, first_body_idx)


def _lines_byte_offset_to_index(lines, first_body_idx):
    pos = 0
    for i in range(int(first_body_idx)):
        pos += len(lines[i]) + 1
    return pos


def _php_header_text_for_unicode_scan(lines, first_body_idx):
    if not lines:
        return ""
    n = min(len(lines), int(first_body_idx) + 1)
    return "\n".join(lines[:n])


def validate_php_file(
    file_path,
    content,
    strict_edge_links=False,
    quiet=False,
    development_mode=False,
    strict_memory_pair=False,
    strict_memory_year=False,
    strict_memory_files=False,
    reject_legacy_envelope=False,
    suppress_legacy_envelope_warn=False,
    strict_mode=False,
):
    """
    Validate LUPOPEDIA HEADERS in PHP: ``<?php`` + 25-line ``/**`` … ``*/`` **or**
    ``#!/usr/bin/env php`` + ``<?php`` + 25-line ``#`` comment grid (PRD 16 §4.3 rule 9).

    Returns (success, parsed_dict_or_None).
    """
    if yaml is None:
        print("[ERROR] %s: PyYAML is required for .php header validation" % (file_path,))
        return False, None

    if _PHP_LEGACY_INLINE_YAML_RE.search((content or "")[:8000]):
        print(
            "[ERROR] %s: legacy PHP header pattern detected: ``/**`` followed by bare ``lupopedia.headers:`` "
            "(missing `` * `` line leaders). New work MUST use v4: optional ``#!/usr/bin/env php``, then ``<?php``, "
            "then the **25-line** ``#`` comment grid (same mechanical layout as Python). See PRD 16 section 4.3 rule 9 "
            "and ``python scripts/add_lupopedia_header_to_file.py``. (HDR_PHP_LEGACY_INLINE_V3)"
            % (file_path,)
        )
        return False, None

    lines = content.split("\n")
    mode, yaml_inner, first_body_idx = _php_try_hash_comment_header(
        lines,
        file_path,
        reject_legacy_envelope=reject_legacy_envelope,
        suppress_legacy_envelope_warn=suppress_legacy_envelope_warn,
    )
    if mode == "bad":
        return False, None
    if mode == "use_star":
        ok_env, yaml_inner, first_body_idx = validate_php_header_envelope(
            lines,
            file_path,
            reject_legacy_envelope=reject_legacy_envelope,
            suppress_legacy_envelope_warn=suppress_legacy_envelope_warn,
        )
        if not ok_env or yaml_inner is None or first_body_idx is None:
            return False, None

    if not validate_no_unicode_box_chars(
        _php_header_text_for_unicode_scan(lines, first_body_idx), file_path
    ):
        return False, None

    body_line_1based = int(first_body_idx) + 1
    if len(lines) <= int(first_body_idx):
        if development_mode:
            print(
                "[WARN]  %s: no body line after PHP header block (HDR_EMPTY_BODY) — allowed under --development"
                % (file_path,)
            )
        else:
            print(
                "[ERROR] %s: body must start on line %d (HDR_EMPTY_BODY)"
                % (file_path, body_line_1based)
            )
            return False, None
    elif not lines[int(first_body_idx)].strip():
        if development_mode:
            print(
                "[WARN]  %s: line %d empty after PHP header block (HDR_EMPTY_BODY) — allowed under --development"
                % (file_path, body_line_1based)
            )
        else:
            print(
                "[ERROR] %s: line %d must start body (HDR_EMPTY_BODY)"
                % (file_path, body_line_1based)
            )
            return False, None

    try:
        data = yaml.safe_load(yaml_inner)
    except Exception as e:
        print("[ERROR] %s: YAML parse failed in PHP header: %s" % (file_path, e))
        return False, None

    if not isinstance(data, dict):
        print("[ERROR] %s: PHP header YAML must be a mapping" % (file_path,))
        return False, None

    hdr = data.get("lupopedia.headers")
    if not isinstance(hdr, dict):
        print("[ERROR] %s: lupopedia.headers must be a mapping" % (file_path,))
        return False, None

    parsed = data
    off = _lines_byte_offset_to_index(lines, first_body_idx)

    ok, out = _validate_lupopedia_headers_payload(
        file_path,
        hdr,
        parsed,
        content,
        strict_edge_links,
        tail_start_offset=off,
        development_mode=development_mode,
        strict_memory_pair=strict_memory_pair,
        strict_memory_year=strict_memory_year,
        strict_memory_files=strict_memory_files,
        strict_mode=strict_mode,
    )
    if ok:
        _print_pass_line(file_path, quiet)
    return ok, out


def _js_header_text_for_unicode_scan(lines):
    if not lines:
        return ""
    return "\n".join(lines[: min(25, len(lines))])


def _js_body_offset_after_header(lines):
    if len(lines) < 25:
        return None
    pos = 0
    for i in range(25):
        pos += len(lines[i]) + 1
    return pos


def validate_js_file(
    file_path,
    content,
    strict_edge_links=False,
    quiet=False,
    development_mode=False,
    strict_memory_pair=False,
    strict_memory_year=False,
    strict_memory_files=False,
    reject_legacy_envelope=False,
    strict_mode=False,
):
    """
    Validate LUPOPEDIA HEADERS in JavaScript (leading ``/*`` … ``*/`` block, PRD 16 §4.3 rule 9).

    Returns (success, parsed_dict_or_None).
    """
    if yaml is None:
        print("[ERROR] %s: PyYAML is required for .js header validation" % (file_path,))
        return False, None

    lines = content.split("\n")
    if not validate_no_unicode_box_chars(_js_header_text_for_unicode_scan(lines), file_path):
        return False, None

    ok_env, yaml_inner = validate_js_header_envelope(
        lines,
        file_path,
        reject_legacy_envelope=reject_legacy_envelope,
    )
    if not ok_env or yaml_inner is None:
        return False, None

    first_body_idx = 25
    body_line_1based = 26
    if len(lines) <= first_body_idx:
        if development_mode:
            print(
                "[WARN]  %s: no body line after JS header block (HDR_EMPTY_BODY) — allowed under --development"
                % (file_path,)
            )
        else:
            print(
                "[ERROR] %s: body must start on line %d (first line after */) (HDR_EMPTY_BODY)"
                % (file_path, body_line_1based)
            )
            return False, None
    elif not lines[first_body_idx].strip():
        if development_mode:
            print(
                "[WARN]  %s: line %d empty after JS header block (HDR_EMPTY_BODY) — allowed under --development"
                % (file_path, body_line_1based)
            )
        else:
            print(
                "[ERROR] %s: line %d must start body (no blank-only line after */) (HDR_EMPTY_BODY)"
                % (file_path, body_line_1based)
            )
            return False, None

    try:
        data = yaml.safe_load(yaml_inner)
    except Exception as e:
        print("[ERROR] %s: YAML parse failed in JS header: %s" % (file_path, e))
        return False, None

    if not isinstance(data, dict):
        print("[ERROR] %s: JS header YAML must be a mapping" % (file_path,))
        return False, None

    hdr = data.get("lupopedia.headers")
    if not isinstance(hdr, dict):
        print("[ERROR] %s: lupopedia.headers must be a mapping" % (file_path,))
        return False, None

    parsed = data
    off = _js_body_offset_after_header(lines)
    if off is None:
        print("[ERROR] %s: could not compute JS body offset (HDR_JS_HEADER)" % (file_path,))
        return False, None

    ok, out = _validate_lupopedia_headers_payload(
        file_path,
        hdr,
        parsed,
        content,
        strict_edge_links,
        tail_start_offset=off,
        development_mode=development_mode,
        strict_memory_pair=strict_memory_pair,
        strict_memory_year=strict_memory_year,
        strict_memory_files=strict_memory_files,
        strict_mode=strict_mode,
    )
    if ok:
        _print_pass_line(file_path, quiet)
    return ok, out


def validate_yaml_file(
    file_path,
    content,
    strict_edge_links=False,
    quiet=False,
    development_mode=False,
    strict_memory_pair=False,
    strict_memory_year=False,
    strict_memory_files=False,
    reject_legacy_envelope=False,
    strict_mode=False,
):
    """
    Validate YAML front matter for LUPOPEDIA HEADERS.

    Returns (success, parsed_front_matter_dict_or_None).
    """
    lines = content.split("\n")
    non_empty_lines = [line for line in lines if line.strip()]
    if not non_empty_lines:
        print("[ERROR] %s: Empty file" % (file_path,))
        return False, None

    first_line = non_empty_lines[0].strip()
    if first_line != "---":
        print(
            "[ERROR] %s: header must start at top of file with '---' (HDR_NOT_AT_TOP)"
            % (file_path,)
        )
        return False, None

    if not validate_no_unicode_box_chars(content, file_path):
        return False, None

    _header_inners, _ = peel_leading_lupopedia_yaml_blocks(content)
    if len(_header_inners) > 1:
        print(
            "[ERROR] %s: multiple lupopedia.headers YAML blocks at file start (HDR_MULTIPLE_HEADERS)"
            % (file_path,)
        )
        return False, None

    if "lupopedia.headers:" not in content:
        print("[ERROR] %s: Missing lupopedia.headers block" % (file_path,))
        return False, None

    if "\n\n---\n" not in content and "\n---\n" not in content:
        print("[ERROR] %s: Missing YAML closing delimiter '---'" % (file_path,))
        return False, None

    if not validate_markdown_header_line_count(
        content,
        file_path,
        development_mode=development_mode,
        reject_legacy_envelope=reject_legacy_envelope,
    ):
        return False, None

    parsed, perr = parse_front_matter_yaml(content)
    if perr:
        print("[ERROR] %s: %s" % (file_path, perr))
        return False, None

    hdr = parsed.get("lupopedia.headers")
    if not isinstance(hdr, dict):
        print("[ERROR] %s: lupopedia.headers must be a mapping" % (file_path,))
        return False, None

    ok, out = _validate_lupopedia_headers_payload(
        file_path,
        hdr,
        parsed,
        content,
        strict_edge_links,
        tail_start_offset=None,
        development_mode=development_mode,
        strict_memory_pair=strict_memory_pair,
        strict_memory_year=strict_memory_year,
        strict_memory_files=strict_memory_files,
        strict_mode=strict_mode,
    )
    if ok:
        _print_pass_line(file_path, quiet)
    return ok, out


if __name__ == "__main__":
    parser = argparse.ArgumentParser(
        description=(
            "Validate LUPOPEDIA HEADERS (PRD 16 §4.2 / §4.3, header_format_version 4.1.x patch family). "
            "Optional MySQL drift checks with --check-db (imports pymysql only when used)."
        ),
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog=(
            "Examples:\n"
            "  python scripts/validate_lupopedia_headers_universal.py docs/prd/16_lupopedia_headers.md\n"
            "  python scripts/validate_lupopedia_headers_universal.py scripts/add_lupopedia_header_to_file.py\n"
            "  python scripts/validate_lupopedia_headers_universal.py file.md --check-links --quiet\n"
            "  python scripts/validate_lupopedia_headers_universal.py docs/prd/16_lupopedia_headers.md --strict-memory-year\n"
            "  python scripts/validate_lupopedia_headers_universal.py path/to/file.md --strict-memory-files\n"
        ),
    )
    parser.add_argument(
        "file_path",
        help="Path to .md, .py, .php (<?php + # grid or /** docblock), or .js (/* block) per PRD 16 §4.3",
    )
    parser.add_argument(
        "--type",
        choices=("auto", "md", "yaml", "py", "php", "js"),
        default="auto",
        help="Validation mode (default: .py/.php/.js by extension, else Markdown/YAML front matter)",
    )
    parser.add_argument(
        "--check-db",
        action="store_true",
        help="After validation, warn if outbound_edges/history disagree with MySQL for content_id",
    )
    parser.add_argument(
        "--queue-orphans",
        action="store_true",
        default=None,
        help="If --check-db used, add orphan files to anubis_queue table (default True with --check-db)",
    )
    parser.add_argument(
        "--no-queue-orphans",
        action="store_false",
        dest="queue_orphans",
        help="Disable adding orphans to anubis_queue even if --check-db is used",
    )
    parser.add_argument(
        "--check-links",
        action="store_true",
        help="If YAML still has lupopedia.edges, fail when any repo-relative outbound 'to:' path is missing (http/https skipped); edges belong in header_metadata sidecar per PRD 16",
    )
    parser.add_argument(
        "--quiet",
        action="store_true",
        help="Suppress banner and final [PASS] line",
    )
    parser.add_argument(
        "--development",
        action="store_true",
        help=(
            "Relax production checks: allow http:// web_path; skip JSON<->TOON master pairing; "
            "Markdown line 26 / Python PHP JS first body line after header -> HDR_EMPTY_BODY as WARN only "
            "(still exit 0 if no other errors)"
        ),
    )
    parser.add_argument(
        "--strict-memory-pair",
        "--strict",
        dest="strict_memory_pair",
        action="store_true",
        help=(
            "For seed|canonical: fail if .toon exists on disk but sibling .json master is missing "
            "(--strict is an alias)"
        ),
    )
    parser.add_argument(
        "--strict-memory-year",
        action="store_true",
        help=(
            "For trust_tier canonical: memory_toon path year segment must be "
            "(when_updated calendar year - 1000) per PRD 16 section 8.1 (HDR_MEMORY_YEAR_OFFSET)"
        ),
    )
    parser.add_argument(
        "--strict-memory-files",
        action="store_true",
        help=(
            "Fail when memory_toon ends with .toon but that path is not a file under repo root "
            "(HDR_MEMORY_TOON_MISSING); default is WARN only"
        ),
    )
    parser.add_argument(
        "--reject-legacy-envelope",
        action="store_true",
        help=(
            "Reject Markdown/Python v4.0.0 envelopes (blank lines 23-24 before closing fence); "
            "default is warn only (HDR_LEGACY_ENVELOPE). Use after repo migration to dense v4.0.99."
        ),
    )
    parser.add_argument(
        "--reject-legacy-fields",
        action="store_true",
        help="Reject legacy fields (memory_key, dialog_transcript, content_slug) with errors instead of warnings",
    )
    parser.add_argument(
        "--version",
        action="version",
        version="validate_lupopedia_headers_universal.py (PRD 16 / 4.1.9 envelope)",
    )
    args = parser.parse_args()
    file_path = args.file_path

    # Handle default for queue_orphans
    if args.queue_orphans is None:
        args.queue_orphans = True if args.check_db else False

    if not args.quiet:
        print(
            "validate_lupopedia_headers_universal.py - PRD 16 / header_format_version 4.1.9"
        )

    if not os.path.exists(file_path):
        print("[ERROR] File not found: %s" % (file_path,))
        sys.exit(1)

    try:
        with open(file_path, "r", encoding="utf-8-sig") as f:
            content = f.read()
    except Exception as e:
        print("[ERROR] Error reading file %s: %s" % (file_path, e))
        sys.exit(1)

    content = content.replace("\r\n", "\n")

    norm = file_path.replace("\\", "/").lower()
    ft = args.type
    if ft == "auto":
        mode = "py" if norm.endswith(".py") else "php" if norm.endswith(".php") else "js" if norm.endswith(".js") else "md"
    elif ft == "py":
        mode = "py"
    elif ft == "php":
        mode = "php"
    elif ft == "js":
        mode = "js"
    else:
        mode = "md"

    vkw = dict(
        strict_edge_links=args.check_links,
        quiet=args.quiet,
        development_mode=args.development,
        strict_memory_pair=args.strict_memory_pair,
        strict_memory_year=args.strict_memory_year,
        strict_memory_files=args.strict_memory_files,
        reject_legacy_envelope=args.reject_legacy_envelope,
        strict_mode=args.reject_legacy_fields,
    )
    if mode == "py":
        ok, parsed = validate_python_file(file_path, content, **vkw)
    elif mode == "php":
        ok, parsed = validate_php_file(file_path, content, **vkw)
    elif mode == "js":
        ok, parsed = validate_js_file(file_path, content, **vkw)
    else:
        ok, parsed = validate_yaml_file(file_path, content, **vkw)
    
    if ok and parsed is not None:
        if args.check_db:
            check_db_sync_universal(file_path, parsed, check_db_flag=True, queue_orphans=args.queue_orphans)
        else:
            print(
                "[WARN] %s: content_id resolution state not DB-verified. "
                "Run with --check-db for deterministic file-first/database-first/repair classification "
                "(HDR_CONTENT_ID_STATE_UNVERIFIED)."
                % (file_path,)
            )

    sys.exit(0 if ok else 1)
