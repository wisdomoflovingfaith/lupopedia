#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.4"
#   file_path_from_root: "lupo-scripts/lib/header_spec_v3_1.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/lib/header_spec_v3_1.py"
#   status: "active"
#   when_updated: "20260422030000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/header-spec-v3-1.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/header-spec-v3-1"
#   artifact_type: implementation
#   artifact_kind: library
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "16"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_LUPOPEDIA_HEADERS"
#   title: "PRD 16 header specification (validator ruleset, v4.1.4 / 22 keys)"
#   summary: "PRD 16 v4.1.4: canonical 22-key order and cross-field rules for Lupopedia header validators"
# ---------------------------------------------------------------------
"""
PRD 16 v4.1.4 — single ruleset for LUPOPEDIA HEADERS validators and emitters.

Used by validate_lupopedia_headers_universal.py, lib/header_validation.py,
normalize_lupopedia_md_header_25.py, and related tooling.

``V3_HEADER_KEYS_ORDERED`` remains the import name for historical callers; it is
the **22-key** v4.1.4 order (dense envelope, lines 3–24 under ``lupopedia.headers:``).
"""

from __future__ import annotations

import json
import re
from typing import Any, Dict, FrozenSet, Optional, Tuple

# §4.2 field list order (22 scalar keys under lupopedia.headers; PRD 16 v4.1.0)
# Field 6 was last_modified_utc (redundant timestamp); renamed questions_toon v4.0.99.
# Field 8 was memory_key; renamed memory_toon per PRD 16 v4.1.0 canonical order reset.
# memory_toon: path ending .toon; accepted legacy memory_key alias (WARN).
# Field 9 was module (subsystem label); renamed atoms_toon per PRD 16 v4.0.99 §4.2 update.
# atoms_toon Phase 1 (v4.1.0): null (always valid) or string ending .atoms.toon.
#   Non-null rules: suffix must be .atoms.toon (HDR_ATOMS_TOON_SUFFIX);
#   canonical paths must use trust-ladder year (HDR_ATOMS_TOON_YEAR, e.g. 1026 not 2026);
#   file must exist on disk (HDR_ATOMS_TOON_MISSING; --development downgrades to WARN);
#   path must not equal memory_toon (HDR_ATOMS_TOON_COLLISION).
#   module accepted as legacy alias (HDR_MODULE_DEPRECATED WARN). Phase 1 does NOT read content.
# Field 10 was dialog_transcript; renamed transcript_jsonl per PRD 16 v4.1.0 §4.2 field 10.
# transcript_jsonl: DB lookup slug or null. dialog_transcript accepted as legacy alias (WARN).
V4_HEADER_KEYS_ORDERED: Tuple[str, ...] = (
    "header_format_version",
    "file_path_from_root",
    "web_path",
    "status",
    "when_updated",
    "trust_tier",
    "questions_toon",
    "memory_toon",
    "atoms_toon",
    "transcript_jsonl",
    "artifact_type",
    "artifact_kind",
    "channel_key",
    "federation_node_id",
    "thread_id",
    "content_id",
    "content_parent_id",
    "default_collection_id",
    "lupopedia.schema",
    "prd_cluster",
    "title",
    "summary",
)

# Backward-compatible name for imports (same tuple as v4.0.99)
V3_HEADER_KEYS_ORDERED: Tuple[str, ...] = V4_HEADER_KEYS_ORDERED

V3_HEADER_KEYS: FrozenSet[str] = frozenset(V4_HEADER_KEYS_ORDERED)
V4_HEADER_KEYS: FrozenSet[str] = frozenset(V4_HEADER_KEYS_ORDERED)

# PRD 16 trust_tier doctrine (current): canonical + development.
# Legacy tiers are validator-tolerated with warnings during transition.
VALID_TRUST_TIERS: FrozenSet[str] = frozenset(("canonical", "development"))
LEGACY_TRUST_TIERS: FrozenSet[str] = frozenset(("seed", "staging", "archive"))

# PRD 16 §4.2 — 22 scalar keys (v4.1.0); alias name kept for older imports.
REQUIRED_KEYS_V3: Tuple[str, ...] = V4_HEADER_KEYS_ORDERED

# Legacy YAML key names → canonical names (PRD 16 migration table).
# last_modified_utc was field 6 until v4.0.99 rename to questions_toon.
# module was field 21 until v4.0.99 rename to atoms_toon.
# memory_key renamed to memory_toon in v4.1.0 canonical order reset.
# dialog_transcript renamed to transcript_jsonl in v4.1.0 §4.2 field 10.
# pk_id / pk_slug / parent_pk_id renamed to content_id / REMOVED_content_slug / content_parent_id in v4.1.1.
# content_slug removed in v4.1.4 - pk_slug and prd_slug now map to REMOVED status.
LEGACY_KEYS_V4: Dict[str, str] = {
    "prd_id": "content_id",            # pre-v4.0.99 alias (was prd_id → pk_id → content_id)
    "prd_slug": "REMOVED_content_slug",         # pre-v4.0.99 alias (was prd_slug → pk_slug → content_slug)
    "parent_prd": "content_parent_id",  # pre-v4.0.99 alias (was parent_prd → parent_pk_id → content_parent_id)
    "pk_id": "content_id",             # v4.1.1: pk_id → content_id (HDR_PK_LEGACY_ALIAS)
    "pk_slug": "REMOVED_content_slug",          # v4.1.1: pk_slug → content_slug (HDR_PK_LEGACY_ALIAS)
    "parent_pk_id": "content_parent_id",  # v4.1.1: parent_pk_id → content_parent_id (HDR_PK_LEGACY_ALIAS)
    "last_modified_utc": "questions_toon",  # renamed in PRD 16 v4.0.99 §4.2 field 6 (was field 7)
    "module": "atoms_toon",  # renamed in PRD 16 v4.0.99 §4.2 field 21 → now field 9
    "memory_key": "memory_toon",  # renamed in PRD 16 v4.1.0 §4.2 field 8
    "dialog_transcript": "transcript_jsonl",  # renamed in PRD 16 v4.1.0 §4.2 field 10
}

# v4.1.1 pk_* → content_* field aliases (HDR_PK_LEGACY_ALIAS).
# Used by validators to emit targeted migration warnings.
# Note: content_slug removed in v4.1.4 - slug aliases now map to REMOVED status.
LEGACY_FIELD_ALIASES: Dict[str, str] = {
    "prd_id": "content_id",
    "prd_slug": "REMOVED_content_slug",
    "parent_prd": "content_parent_id",
    "pk_id": "content_id",
    "pk_slug": "REMOVED_content_slug",
    "parent_pk_id": "content_parent_id",
}

# questions_toon accepts null or a path string ending in .questions.toon
QUESTIONS_TOON_SUFFIX = ".questions.toon"

# atoms_toon Phase 1 (v4.1.0): null or a path string ending in .atoms.toon
# Non-null: suffix, year, existence and collision checks enforced (see validate_atoms_toon).
ATOMS_TOON_SUFFIX = ".atoms.toon"

# Trust-ladder year for the current generation (calendar_year - 1000).
# Canonical atoms_toon / memory_toon paths must use this segment, not the raw calendar year.
# Example: 2026 → 1026; a path containing /canonical/2026/ is an error.
# Why 1026 exists: Lupopedia canonical memory paths encode the trust-ladder display year
# (calendar year minus 1000) to distinguish canonical tier material from staging-era paths.
# Future risk: this constant is static and must be updated when the canonical year rolls;
# callers that need dynamic year behavior should derive from `when_updated` instead of
# hardcoding this value in new logic.
CANONICAL_YEAR = "1026"

# Deterministic emit / validation order for v4.0.99 dense envelopes.
DETERMINISTIC_FIELD_ORDER: Tuple[str, ...] = V4_HEADER_KEYS_ORDERED

# Legacy YAML key names (pre v4.0.99) → canonical names. Validators normalize before checks.
LEGACY_HEADER_KEY_ALIASES: Dict[str, str] = LEGACY_KEYS_V4

def line_key_to_canonical(key: str) -> str:
    """Normalize a YAML key as written in the file (legacy aliases → canonical key)."""
    return LEGACY_HEADER_KEY_ALIASES.get(key, key)


def merge_legacy_header_keys(hdr: dict) -> dict:
    """
    Copy hdr; copy legacy aliases into canonical keys when canonical key absent;
    then remove legacy keys.
    """
    out = dict(hdr)
    for old, new in LEGACY_HEADER_KEY_ALIASES.items():
        if old not in out:
            continue
        old_val = out[old]
        if new not in out:
            out[new] = old_val
        del out[old]
    return out


def apply_v4099_header_defaults(hdr: dict) -> dict:
    """
    After merge_legacy_header_keys: ensure summary, atoms_toon, and memory_toon exist (PRD 16 v4.1.0).
    module is accepted as a legacy alias for atoms_toon (renamed in v4.0.99 field 21).
    memory_key is accepted as a legacy alias for memory_toon (renamed in v4.1.0 field 8).
    dialog_transcript is accepted as a legacy alias for transcript_jsonl (renamed in v4.1.0 field 10).
    Does not invent other keys — missing required keys are validation errors.
    """
    out = dict(hdr)
    if "summary" not in out:
        out["summary"] = ""
    if "atoms_toon" not in out:
        out["atoms_toon"] = None
    if "memory_toon" not in out:
        out["memory_toon"] = None
    return out


def normalize_header_dict_for_validation(hdr: dict):
    """
    merge_legacy + summary/atoms_toon/memory_toon defaults, then **OrderedDict** in §4.2 canonical order.
    Fills nullable / empty-allowed keys when absent so semantic validators match universal.py.
    module is accepted as legacy alias for atoms_toon via LEGACY_KEYS_V4.
    memory_key is accepted as legacy alias for memory_toon via LEGACY_KEYS_V4.
    dialog_transcript is accepted as legacy alias for transcript_jsonl via LEGACY_KEYS_V4.
    pk_id / pk_slug / parent_pk_id accepted as legacy aliases via LEGACY_KEYS_V4 (v4.1.1).
    """
    from collections import OrderedDict

    base = apply_v4099_header_defaults(merge_legacy_header_keys(dict(hdr)))
    od = OrderedDict()
    for k in V4_HEADER_KEYS_ORDERED:
        if k in base:
            od[k] = base[k]
        elif k == "summary":
            od[k] = ""
        elif k in ("atoms_toon", "memory_toon"):
            od[k] = None
        elif k in ("content_id", "content_parent_id", "default_collection_id"):
            od[k] = None
        elif k in V3_KEYS_ALLOW_EMPTY_VALUE:
            od[k] = ""
        else:
            od[k] = base.get(k)
    return od


# §4.2 field 20 — closed enum (lupopedia.schema)
VALID_LUPOPEDIA_SCHEMA_V3: FrozenSet[str] = frozenset(
    (
        "prd",
        "doctrine",
        "documentation",
        "implementation",
        "discussion",
        "changelog",
        "architecture",
        "specification",
        "version-doc",
        "status",
    )
)

# Cross-Field Validation Rules (PRD 16 appendix)
ARTIFACT_TYPE_ALLOWED_KINDS: Dict[str, FrozenSet[str]] = {
    "prd": frozenset(("requirements", "architecture", "specification", "guide")),
    "implementation": frozenset(
        ("README", "documentation", "authors", "edges", "tool", "library", "service")
    ),
    "doctrine": frozenset(("constitutional", "reference", "decisions")),
    "discussion": frozenset(("thread", "message")),
    "changelog": frozenset(("version_specific",)),
    "documentation": frozenset(("table_schema", "guide")),
    "architecture": frozenset(("system", "data_model")),
    "specification": frozenset(("technical", "api", "protocol")),
    # PRD 16 §4.2.1 — version-folder and status tracking (LILITH audit 2026-04-15)
    "version-doc": frozenset(("version_specific", "guide")),
    "status": frozenset(("open_questions", "session", "report", "tracking")),
}

VALID_ARTIFACT_TYPES: FrozenSet[str] = frozenset(ARTIFACT_TYPE_ALLOWED_KINDS.keys())

# Empty-string vs null rules (PRD 16 §4.2):
# - V3_KEYS_ALLOW_EMPTY_VALUE: these keys may explicitly use "" for compatibility envelopes.
# - content_id/content_parent_id/default_collection_id: identity linkage slots use null to mean
#   "not linked yet" and avoid ambiguous empty-string identity.
# - atoms_toon/memory_toon/questions_toon: null means "no sidecar pointer declared yet".
# This split keeps unresolved identity/state explicit and avoids guessing semantics from "".
V3_KEYS_ALLOW_EMPTY_VALUE: FrozenSet[str] = frozenset(
    ("thread_id", "content_parent_id", "default_collection_id", "title", "status", "summary")
)


def classify_content_id_state(content_id_value: Any) -> str:
    """
    Lightweight state classification with no DB lookup.

    Returns:
      - "file-first" when content_id is null/empty
      - "db-first-candidate" when content_id is present/non-empty
    """
    if content_id_value is None:
        return "file-first"
    s = str(content_id_value).strip()
    if s == "":
        return "file-first"
    return "db-first-candidate"


def validate_channel_key_presence(channel_key_value: Any) -> Tuple[bool, Optional[str]]:
    """
    Presence-only channel_key hook.

    This helper intentionally does not implement path matching; that remains in
    the universal validator (`validate_memory_key_path_shape`).
    """
    if channel_key_value is None:
        return False, "channel_key is None"
    if str(channel_key_value).strip() == "":
        return False, "channel_key is empty"
    return True, None


def warn_trust_tier_status_mismatch(
    trust_tier_value: Any,
    status_value: Any,
    status_marker_text: Optional[str] = None,
) -> Optional[str]:
    """
    Warning hook for trust_tier vs STATUS intent.

    Current non-failing rule:
      STATUS: PROPOSED + trust_tier: canonical -> warn
    """
    tier = str(trust_tier_value or "").strip().lower()
    status = str(status_value or "").strip().lower()
    marker = str(status_marker_text or "").strip().lower()
    is_proposed = status == "proposed" or marker == "proposed"
    if is_proposed and tier == "canonical":
        return (
            "STATUS PROPOSED with trust_tier canonical (expected development) "
            "(HDR_TRUST_TIER_STATUS_MISMATCH)"
        )
    return None

# Inner line count under opening --- (Markdown): lupopedia.headers: + 22 key lines = 23 lines
V4_MD_INNER_LINE_COUNT = 23

# Legacy v3.1 / v4.0.0–98: lupopedia.headers: + 20 keys = 21 inner lines, blank 23–24, close line 25
V3_LEGACY_MD_INNER_LINE_COUNT = 21

# Pre–v4.0.99 twenty-key order (mechanical validation for legacy envelopes only)
V3_LEGACY_20_HEADER_KEYS_ORDERED: Tuple[str, ...] = (
    "header_format_version",
    "lupopedia.schema",
    "when_updated",
    "file_path_from_root",
    "web_path",
    "last_modified_utc",
    "federation_node_id",
    "channel_key",
    "trust_tier",
    "memory_key",
    "artifact_type",
    "artifact_kind",
    "thread_id",
    "content_id",
    "prd_id",
    "prd_slug",
    "title",
    "status",
    "parent_prd",
    "dialog_transcript",
)

# Emit unquoted scalars for simple tokens (PRD examples)
_YAML_UNQUOTED_VALUE_KEYS = frozenset(("lupopedia.schema", "artifact_type", "artifact_kind"))


def format_yaml_header_scalar_line(key: str, value) -> str:
    """Single ``  key: value`` line for Markdown / normalized inner block."""
    indent = "  "
    if value is None:
        return "%s%s: null" % (indent, key)
    if isinstance(value, bool):
        return "%s%s: %s" % (indent, key, "true" if value else "false")
    if isinstance(value, int) and not isinstance(value, bool):
        return "%s%s: %d" % (indent, key, value)
    if isinstance(value, str):
        if value == "":
            return '%s%s: ""' % (indent, key)
        if key in _YAML_UNQUOTED_VALUE_KEYS and re.match(r"^[a-z][a-z0-9_]*$", value):
            return "%s%s: %s" % (indent, key, value)
        return "%s%s: %s" % (indent, key, json.dumps(value, ensure_ascii=True))
    return "%s%s: %s" % (indent, key, json.dumps(str(value), ensure_ascii=True))


def _legacy_20_value_source_key(yaml_key: str) -> str:
    """Map a legacy twenty-key YAML name to the canonical header dict key."""
    return LEGACY_HEADER_KEY_ALIASES.get(yaml_key, yaml_key)


def emit_markdown_inner_legacy_v400_from_canonical(hdr: dict, *, header_format_version: str = "4.0.0") -> str:
    """
    Build the 21-line inner block (lupopedia.headers: + 20 keys) for deprecated v4.0.0 layout.
    Caller must append two blank lines and closing --- for the full Markdown envelope.
    """
    h = apply_v4099_header_defaults(merge_legacy_header_keys(dict(hdr)))
    h = dict(h)
    h["header_format_version"] = header_format_version
    lines = ["lupopedia.headers:"]
    for k in V3_LEGACY_20_HEADER_KEYS_ORDERED:
        src = _legacy_20_value_source_key(k)
        if src not in h:
            raise KeyError("emit_markdown_inner_legacy_v400_from_canonical: missing %r (via %r)" % (src, k))
        lines.append(format_yaml_header_scalar_line(k, h[src]))
    inner = "\n".join(lines)
    if len(inner.splitlines()) != V3_LEGACY_MD_INNER_LINE_COUNT:
        raise ValueError("legacy inner line count != %s" % V3_LEGACY_MD_INNER_LINE_COUNT)
    return inner


def emit_python_header_block_lines_from_header_dict(hdr: dict) -> list:
    """
    25-line Python comment block (open fence, lupopedia.headers + 22 keys, close fence).
    Does not include shebang.
    """
    h = apply_v4099_header_defaults(merge_legacy_header_keys(dict(hdr)))
    lines = [
        "# ---------------------------------------------------------------------",
        "# lupopedia.headers:",
    ]
    for k in V4_HEADER_KEYS_ORDERED:
        if k not in h:
            raise KeyError("emit_python_header_block_lines_from_header_dict: missing key %r" % k)
        scalar = format_yaml_header_scalar_line(k, h[k])
        lines.append("# " + scalar)
    lines.append("# ---------------------------------------------------------------------")
    if len(lines) != 25:
        raise ValueError("python header line count != 25")
    return lines


def emit_markdown_inner_from_header_dict(hdr: dict) -> str:
    """
    Build the 23-line inner block (lupopedia.headers: + 22 keys) from a mapping.
    Normalizes legacy keys and applies v4.0.99 defaults for summary/module.
    """
    h = apply_v4099_header_defaults(merge_legacy_header_keys(dict(hdr)))
    lines = ["lupopedia.headers:"]
    for k in V4_HEADER_KEYS_ORDERED:
        if k not in h:
            raise KeyError("emit_markdown_inner_from_header_dict: missing key %r" % k)
        lines.append(format_yaml_header_scalar_line(k, h[k]))
    inner = "\n".join(lines)
    if len(inner.splitlines()) != V4_MD_INNER_LINE_COUNT:
        raise ValueError("inner line count != %s" % V4_MD_INNER_LINE_COUNT)
    return inner
