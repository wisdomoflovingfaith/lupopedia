#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.9"
#   path_from_lupopedia_root: "scripts/lib/header_spec_v3_1.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/lib/header_spec_v3_1.py"
#   status: "active"
#   when_updated: "20260523042341"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/header-spec-v3-1.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/header-spec-v3-1"
#   artifact_type: implementation
#   artifact_kind: library
#   channel_key: "development"
#   federation_node_id: 0
#   thread_key: ""
#   lupopedia.schema: implementation
#   prd_cluster: "00_A_16_C"
#   title: "PRD 16 header specification (validator ruleset, 22-field v4.1.9)"
#   summary: "Canonical 22-field header order; edges_toon, channel_index, source_timestamp (4.1.9)"
#   edges_toon: null
#   channel_index: "lupopedia"
#   source_timestamp: null
# ---------------------------------------------------------------------
"""
PRD 16 v4.1.9 — single ruleset for LUPOPEDIA HEADERS validators and emitters.

Used by validate_lupopedia_headers_universal.py, lib/header_validation.py,
normalize_lupopedia_md_header_25.py, and related tooling.

``V3_HEADER_KEYS_ORDERED`` remains the import name for historical callers; it is
the **22-field** atom-backed order (dense envelope under ``lupopedia.headers:``).
"""

from __future__ import annotations

import json
import os
import re
from typing import Any, Dict, FrozenSet, List, Optional, Tuple

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
# PRD 16 / atom SSOT: memory/channels/atoms/lupopedia_global_constants.atom.toon
# Dual-accept (4.2.0 Option A): 4.1.9 = 22 fields; 4.2.0 = 28 fields (+ identity).
V419_HEADER_KEYS_ORDERED: Tuple[str, ...] = (
    "header_format_version",
    "path_from_lupopedia_root",
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
    "thread_key",
    "lupopedia.schema",
    "prd_cluster",
    "title",
    "summary",
    "edges_toon",
    "channel_index",
    "source_timestamp",
)

# Option A identity scalars (fields 23-28). Hawaiian keys MUST NOT appear here.
V420_IDENTITY_KEYS_ORDERED: Tuple[str, ...] = (
    "actor_id",
    "auth_user_id",
    "department_id",
    "department_key",
    "division_key",
    "faucet_actor_id",
)

V420_HEADER_KEYS_ORDERED: Tuple[str, ...] = V419_HEADER_KEYS_ORDERED + V420_IDENTITY_KEYS_ORDERED

# Default import name = current contract order (4.2.0 / 28 fields).
V4_HEADER_KEYS_ORDERED: Tuple[str, ...] = V420_HEADER_KEYS_ORDERED

# Backward-compatible name for imports
V3_HEADER_KEYS_ORDERED: Tuple[str, ...] = V4_HEADER_KEYS_ORDERED

V419_HEADER_KEYS: FrozenSet[str] = frozenset(V419_HEADER_KEYS_ORDERED)
V420_HEADER_KEYS: FrozenSet[str] = frozenset(V420_HEADER_KEYS_ORDERED)
V3_HEADER_KEYS: FrozenSet[str] = V420_HEADER_KEYS
V4_HEADER_KEYS: FrozenSet[str] = V420_HEADER_KEYS

# Dual-accept versions (PRD 16_C / validator notes Phase 1).
ACCEPTED_HEADER_FORMAT_VERSIONS: FrozenSet[str] = frozenset(("4.1.9", "4.2.0"))
CURRENT_HEADER_FORMAT_VERSION = "4.2.0"
LEGACY_HEADER_FORMAT_VERSION = "4.1.9"
# Emitters / --require-current default to current; validators accept both.
EXPECTED_HEADER_FORMAT_VERSION = CURRENT_HEADER_FORMAT_VERSION
EXPECTED_HEADER_FIELD_COUNT = len(V420_HEADER_KEYS_ORDERED)
LEGACY_HEADER_FIELD_COUNT = len(V419_HEADER_KEYS_ORDERED)

# Dense keys forbidden under lupopedia.headers (Hermes/body only) — HDR_HAWAIIAN_DENSE
HAWAIIAN_DENSE_FORBIDDEN_KEYS: FrozenSet[str] = frozenset(
    (
        "ohana",
        "kapu",
        "kapakai",
        "puka",
        "pono",
        "kuleana",
        "alii",
        "kumu",
        "eh_brah_why",
    )
)

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
    "thread_id": "thread_key",
    "file_path_from_root": "path_from_lupopedia_root",
    "prd_id": "REMOVED_content_id",
    "prd_slug": "REMOVED_content_slug",
    "parent_prd": "REMOVED_content_parent_id",
    "pk_id": "REMOVED_content_id",
    "pk_slug": "REMOVED_content_slug",
    "parent_pk_id": "REMOVED_content_parent_id",
    "content_id": "REMOVED_content_id",
    "content_parent_id": "REMOVED_content_parent_id",
    "default_collection_id": "REMOVED_default_collection_id",
    "content_slug": "REMOVED_content_slug",
    "last_modified_utc": "questions_toon",  # renamed in PRD 16 v4.0.99 §4.2 field 6 (was field 7)
    "module": "atoms_toon",  # renamed in PRD 16 v4.0.99 §4.2 field 21 → now field 9
    "memory_key": "memory_toon",  # renamed in PRD 16 v4.1.0 §4.2 field 8
    "dialog_transcript": "transcript_jsonl",  # renamed in PRD 16 v4.1.0 §4.2 field 10
}

# v4.1.1 pk_* → content_* field aliases (HDR_PK_LEGACY_ALIAS).
# Used by validators to emit targeted migration warnings.
# Note: content_slug removed in v4.1.4 - slug aliases now map to REMOVED status.
LEGACY_FIELD_ALIASES: Dict[str, str] = {
    "prd_id": "REMOVED_content_id",
    "prd_slug": "REMOVED_content_slug",
    "parent_prd": "REMOVED_content_parent_id",
    "pk_id": "REMOVED_content_id",
    "pk_slug": "REMOVED_content_slug",
    "parent_pk_id": "REMOVED_content_parent_id",
    "content_id": "REMOVED_content_id",
    "content_parent_id": "REMOVED_content_parent_id",
    "default_collection_id": "REMOVED_default_collection_id",
    "content_slug": "REMOVED_content_slug",
}

# v4.1.9+ forbidden header keys (HDR_REMOVED_FIELD)
REMOVED_HEADER_FIELDS_V419: FrozenSet[str] = frozenset(
    (
        "content_id",
        "content_parent_id",
        "default_collection_id",
        "content_slug",
        "pk_id",
        "pk_slug",
        "parent_pk_id",
        "prd_id",
        "prd_slug",
        "parent_prd",
    )
)

# questions_toon accepts null or a path string ending in .questions.toon
QUESTIONS_TOON_SUFFIX = ".questions.toon"

EDGES_TOON_SUFFIX = ".edges.toon"

VALID_CHANNEL_INDEX: FrozenSet[str] = frozenset(
    ("lupopedia", "patreon", "website", "facebook", "blog", "external", "imported")
)

# ISO 8601 with Z or explicit numeric offset (field 22)
SOURCE_TIMESTAMP_ISO8601_RE = re.compile(
    r"^"
    r"\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}"
    r"(?:\.\d+)?"
    r"(?:Z|[+-]\d{2}:\d{2})"
    r"$"
)

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

# Deterministic emit / validation order for current dense envelopes (4.2.0).
DETERMINISTIC_FIELD_ORDER: Tuple[str, ...] = V420_HEADER_KEYS_ORDERED

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


def header_keys_ordered_for_version(version_value: Any) -> Tuple[str, ...]:
    """Return the dense key tuple for a declared header_format_version."""
    v = header_format_version_string(version_value)
    if v == LEGACY_HEADER_FORMAT_VERSION:
        return V419_HEADER_KEYS_ORDERED
    if v == CURRENT_HEADER_FORMAT_VERSION:
        return V420_HEADER_KEYS_ORDERED
    # Emitters / unknown: prefer current contract.
    return V420_HEADER_KEYS_ORDERED


def dense_field_count_for_version(version_value: Any) -> int:
    return len(header_keys_ordered_for_version(version_value))


def dense_envelope_line_count_for_version(version_value: Any) -> int:
    """Open fence + lupopedia.headers: + N keys + close fence (= N + 3)."""
    return dense_field_count_for_version(version_value) + 3


def normalize_header_dict_for_validation(hdr: dict):
    """
    merge_legacy + defaults, then OrderedDict in version-specific §4.2 order.
    """
    from collections import OrderedDict

    base = apply_v4099_header_defaults(merge_legacy_header_keys(dict(hdr)))
    ordered = header_keys_ordered_for_version(base.get("header_format_version"))
    od = OrderedDict()
    for k in ordered:
        if k in base:
            od[k] = base[k]
        elif k == "summary":
            od[k] = ""
        elif k in ("atoms_toon", "memory_toon", "edges_toon", "source_timestamp", "department_id"):
            od[k] = None
        elif k == "channel_index":
            od[k] = "lupopedia"
        elif k in ("department_key", "division_key"):
            od[k] = ""
        elif k in ("auth_user_id", "faucet_actor_id"):
            od[k] = None
        elif k == "actor_id":
            od[k] = base.get(k)
        elif k in V3_KEYS_ALLOW_EMPTY_VALUE:
            od[k] = ""
        else:
            od[k] = base.get(k)
    return od


def header_format_patch_level(hdr: dict) -> int:
    """Return 4.1.x patch integer, or -1 if not 4.1.x."""
    v = hdr.get("header_format_version")
    if v is None:
        return -1
    s = str(v).strip().strip('"').strip("'")
    m = re.match(r"^4\.1\.(\d+)$", s)
    if not m:
        return -1
    try:
        return int(m.group(1))
    except ValueError:
        return -1


def requires_v419_rules(hdr: dict) -> bool:
    """True when header uses an accepted post-4.1.9 dense contract (4.1.9 or 4.2.0)."""
    return is_accepted_header_format_version(hdr.get("header_format_version"))


def header_format_version_string(version_value: Any) -> str:
    """Normalize header_format_version scalar to a bare semver string."""
    if version_value is None:
        return ""
    s = str(version_value).strip()
    if (s.startswith('"') and s.endswith('"')) or (s.startswith("'") and s.endswith("'")):
        s = s[1:-1].strip()
    return s


def is_accepted_header_format_version(version_value: Any) -> bool:
    """True when version is in ACCEPTED_HEADER_FORMAT_VERSIONS (4.1.9 or 4.2.0)."""
    return header_format_version_string(version_value) in ACCEPTED_HEADER_FORMAT_VERSIONS


def is_current_header_format_version(version_value: Any) -> bool:
    return header_format_version_string(version_value) == CURRENT_HEADER_FORMAT_VERSION


def is_legacy_header_format_version(version_value: Any) -> bool:
    return header_format_version_string(version_value) == LEGACY_HEADER_FORMAT_VERSION


def is_exact_header_format_version(version_value: Any) -> bool:
    """Backward-compatible name: accepted dual-version check (4.1.9 or 4.2.0)."""
    return is_accepted_header_format_version(version_value)


def validate_header_format_version_exact(hdr: dict, file_path: str) -> bool:
    """Accept header_format_version in ACCEPTED_HEADER_FORMAT_VERSIONS (HDR_VERSION_FAMILY)."""
    got = header_format_version_string(hdr.get("header_format_version"))
    if got in ACCEPTED_HEADER_FORMAT_VERSIONS:
        return True
    print(
        "[ERROR] %s: header_format_version must be one of %s; got %r (HDR_VERSION_FAMILY)"
        % (file_path, sorted(ACCEPTED_HEADER_FORMAT_VERSIONS), got or None)
    )
    return False


def validate_header_format_version_require_current(hdr: dict, file_path: str) -> bool:
    """Optional --require-current: only CURRENT_HEADER_FORMAT_VERSION (4.2.0)."""
    got = header_format_version_string(hdr.get("header_format_version"))
    if got == CURRENT_HEADER_FORMAT_VERSION:
        return True
    print(
        "[ERROR] %s: --require-current: header_format_version must be exactly %r; got %r (HDR_VERSION_420)"
        % (file_path, CURRENT_HEADER_FORMAT_VERSION, got or None)
    )
    return False


def _is_deprecated_header_key(key: str) -> bool:
    if key in REMOVED_HEADER_FIELDS_V419:
        return True
    if key.startswith("pk_"):
        return True
    mapped = LEGACY_KEYS_V4.get(key)
    if mapped and str(mapped).startswith("REMOVED_"):
        return True
    return False


def validate_deprecated_header_fields(hdr: dict, file_path: str) -> bool:
    """Reject removed identity / slug fields (HDR_REMOVED_FIELD)."""
    ok = True
    if not isinstance(hdr, dict):
        return True
    for field in sorted(hdr.keys()):
        if not _is_deprecated_header_key(field):
            continue
        print(
            "[ERROR] %s: Header contains deprecated field %r."
            % (file_path, field)
        )
        ok = False
    return ok


def validate_header_field_count_and_order(hdr: dict, file_path: str) -> bool:
    """
    Enforce exact key count and order for the declared header_format_version
    (22 keys for 4.1.9; 28 keys for 4.2.0).
    """
    if not isinstance(hdr, dict):
        return True
    actual_order: List[str] = list(hdr.keys())
    expected = list(header_keys_ordered_for_version(hdr.get("header_format_version")))
    n = len(expected)
    expected_set = frozenset(expected)

    if len(actual_order) != n:
        print(
            "[ERROR] %s: Header field count mismatch: expected %d (HDR_FIELD_COUNT)."
            % (file_path, n)
        )
        if len(actual_order) < n:
            missing = [k for k in expected if k not in actual_order]
            if missing:
                print(
                    "   Missing required field(s): %s (HDR_MISSING_KEY)"
                    % (", ".join(missing),)
                )
        else:
            extra = [k for k in actual_order if k not in expected_set]
            if extra:
                print(
                    "   Unexpected field(s): %s (HDR_EXTRA_KEY)"
                    % (", ".join(extra),)
                )
        return False

    if actual_order != expected:
        print("[ERROR] %s: Header fields out of canonical order." % (file_path,))
        print("   Expected: %s" % (", ".join(expected),))
        print("   Actual:   %s" % (", ".join(actual_order),))
        print("   (HDR_KEY_ORDER)")
        return False

    return True


def _is_int_like(value: Any) -> bool:
    if value is None:
        return False
    if isinstance(value, bool):
        return False
    if isinstance(value, int):
        return True
    s = str(value).strip()
    if s == "":
        return False
    if s[0] in "+-":
        s = s[1:]
    return s.isdigit()


def validate_hawaiian_not_densified(hdr: dict, file_path: str) -> bool:
    """ERROR if Hawaiian constitutional keys appear under dense lupopedia.headers."""
    if not isinstance(hdr, dict):
        return True
    ok = True
    for field in hdr.keys():
        if str(field).strip().lower() in HAWAIIAN_DENSE_FORBIDDEN_KEYS:
            print(
                "[ERROR] %s: Hawaiian key %r must not appear in dense lupopedia.headers "
                "(Hermes/body/sidecar only) (HDR_HAWAIIAN_DENSE)"
                % (file_path, field)
            )
            ok = False
    return ok


def validate_identity_fields_v420(hdr: dict, file_path: str) -> bool:
    """4.2.0 identity scalars (fields 23-28). No-op for 4.1.9."""
    if not is_current_header_format_version(hdr.get("header_format_version")):
        return True
    ok = True
    actor_id = hdr.get("actor_id")
    if actor_id is None or not _is_int_like(actor_id):
        print(
            "[ERROR] %s: actor_id is required and must be int-like for header 4.2.0 "
            "(HDR_ACTOR_ID_REQUIRED)"
            % (file_path,)
        )
        ok = False

    for key in ("auth_user_id", "department_id", "faucet_actor_id"):
        val = hdr.get(key)
        if val is None:
            continue
        if not _is_int_like(val):
            print(
                "[ERROR] %s: %s must be null or int-like (HDR_IDENTITY_INT)"
                % (file_path, key)
            )
            ok = False

    for key in ("department_key", "division_key"):
        val = hdr.get(key)
        if val is None:
            print(
                "[ERROR] %s: %s must be a string (use \"\" when empty) (HDR_IDENTITY_STRING)"
                % (file_path, key)
            )
            ok = False
        elif not isinstance(val, str):
            # YAML may parse bare tokens; coerce check via str is fine if not bool/list
            if isinstance(val, (list, dict, bool)):
                print(
                    "[ERROR] %s: %s must be a string (HDR_IDENTITY_STRING)"
                    % (file_path, key)
                )
                ok = False

    faucet = hdr.get("faucet_actor_id")
    if (
        actor_id is not None
        and faucet is not None
        and _is_int_like(actor_id)
        and _is_int_like(faucet)
        and int(str(actor_id).strip()) == int(str(faucet).strip())
    ):
        print(
            "[WARN]  %s: faucet_actor_id equals actor_id (%s); confirm intentional "
            "(HDR_FAUCET_EQUALS_ACTOR)"
            % (file_path, actor_id)
        )
    return ok


def is_lupopedia_web_path(web_path: Any) -> bool:
    if web_path is None:
        return False
    s = str(web_path).strip().lower()
    return "lupopedia.com/lupopedia" in s or s.endswith(".lupopedia.com/lupopedia/")


def is_external_channel_index(channel_index: Any) -> bool:
    if channel_index is None:
        return True
    return str(channel_index).strip().lower() != "lupopedia"


def build_edges_toon_path(
    channel_key: str,
    thread_key: str,
    slug: str,
    when_updated_ymdhis: str,
    trust_tier: str = "canonical",
) -> str:
    """edges/{channel_key}/{thread_key}/{YYYY}/{MM}/{slug}.edges.toon (PRD 16 v4.1.9)."""
    y = when_updated_ymdhis[0:4]
    m = when_updated_ymdhis[4:6]
    try:
        calendar_year = int(y)
    except ValueError:
        calendar_year = 2026
    display_year = calendar_year - 1000 if (trust_tier or "").strip().lower() == "canonical" else calendar_year
    tk = (thread_key or "").strip() or "_"
    return "edges/%s/%s/%04d/%s/%s.edges.toon" % (
        (channel_key or "development").strip(),
        tk,
        display_year,
        m,
        slug,
    )


def validate_removed_header_fields_v419(hdr: dict, file_path: str) -> bool:
    """Reject deprecated identity fields removed in v4.1.9 (HDR_REMOVED_FIELD)."""
    return validate_deprecated_header_fields(hdr, file_path)


def validate_edges_toon(hdr: dict, file_path: str) -> bool:
    """Field 20: null or path ending in .edges.toon; required when channel_index != lupopedia."""
    edges = hdr.get("edges_toon")
    memory = hdr.get("memory_toon")
    channel_index = hdr.get("channel_index")
    external = is_external_channel_index(channel_index)

    if edges is None:
        if external:
            print(
                "[ERROR] %s: edges_toon is required when channel_index != 'lupopedia'."
                % (file_path,)
            )
            print(
                "   channel_index=%r (HDR_EDGES_TOON_REQUIRED)"
                % (channel_index,)
            )
            return False
        return True

    if not isinstance(edges, str):
        print("[ERROR] %s: edges_toon must be null or string (HDR_EDGES_TOON_INVALID)" % (file_path,))
        return False
    es = edges.strip()
    if es == "":
        print("[ERROR] %s: edges_toon cannot be empty string; use null (HDR_EDGES_TOON_INVALID)" % (file_path,))
        return False
    if not es.endswith(EDGES_TOON_SUFFIX):
        print(
            "[ERROR] %s: edges_toon must end with %r (HDR_EDGES_TOON_SUFFIX)"
            % (file_path, EDGES_TOON_SUFFIX)
        )
        return False
    if memory is not None and str(memory).strip() == es:
        print(
            "[ERROR] %s: edges_toon must not equal memory_toon (HDR_EDGES_TOON_COLLISION)"
            % (file_path,)
        )
        return False
    return True


def validate_channel_index(hdr: dict, file_path: str) -> bool:
    """Field 21: origin platform; required for non-Lupopedia web_path."""
    ci = hdr.get("channel_index")
    web_path = hdr.get("web_path")
    if ci is None:
        print("[ERROR] %s: channel_index is required (HDR_CHANNEL_INDEX_REQUIRED)" % (file_path,))
        return False
    if not isinstance(ci, str):
        print("[ERROR] %s: channel_index must be a string (HDR_CHANNEL_INDEX_INVALID)" % (file_path,))
        return False
    cis = ci.strip()
    if cis == "":
        print("[ERROR] %s: channel_index cannot be empty (HDR_CHANNEL_INDEX_INVALID)" % (file_path,))
        return False
    if cis not in VALID_CHANNEL_INDEX:
        print(
            "[ERROR] %s: channel_index %r not in allowed set %s (HDR_CHANNEL_INDEX_INVALID)"
            % (file_path, cis, sorted(VALID_CHANNEL_INDEX))
        )
        return False
    if not is_lupopedia_web_path(web_path) and cis == "lupopedia":
        print(
            "[ERROR] %s: web_path is not Lupopedia domain but channel_index is lupopedia "
            "(HDR_CHANNEL_INDEX_WEB_MISMATCH)"
            % (file_path,)
        )
        return False
    return True


def validate_source_timestamp(hdr: dict, file_path: str) -> bool:
    """Field 22: ISO 8601 origin time; required when channel_index != lupopedia."""
    st = hdr.get("source_timestamp")
    channel_index = hdr.get("channel_index")
    external = is_external_channel_index(channel_index)

    if st is None:
        if external:
            print(
                "[ERROR] %s: Missing required field 'source_timestamp' for external artifact."
                % (file_path,)
            )
            print(
                "   channel_index=%r requires ISO 8601 with Z or offset (HDR_SOURCE_TIMESTAMP_REQUIRED)"
                % (channel_index,)
            )
            return False
        return True

    if not isinstance(st, str):
        print(
            "[ERROR] %s: source_timestamp must be null or ISO 8601 string (HDR_SOURCE_TIMESTAMP_INVALID)"
            % (file_path,)
        )
        return False
    sts = st.strip()
    if sts == "":
        print(
            "[ERROR] %s: source_timestamp cannot be empty string; use null (HDR_SOURCE_TIMESTAMP_INVALID)"
            % (file_path,)
        )
        return False
    if not SOURCE_TIMESTAMP_ISO8601_RE.fullmatch(sts):
        print(
            "[ERROR] %s: source_timestamp must be ISO 8601 with Z or offset (HDR_SOURCE_TIMESTAMP_INVALID): %r"
            % (file_path, st)
        )
        return False
    return True


def _baseline_source_timestamp_from_memory_json(json_abs: str) -> Optional[str]:
    """Read prior source_timestamp from memory JSON twin when present."""
    try:
        with open(json_abs, "r", encoding="utf-8") as jf:
            data = json.load(jf)
    except (OSError, ValueError, TypeError):
        return None
    if not isinstance(data, dict):
        return None
    for block_key in ("lupopedia.headers", "headers"):
        block = data.get(block_key)
        if isinstance(block, dict) and block.get("source_timestamp") is not None:
            return str(block.get("source_timestamp")).strip()
    if data.get("source_timestamp") is not None:
        return str(data.get("source_timestamp")).strip()
    return None


def warn_source_timestamp_immutable(
    hdr: dict,
    file_path: str,
    repo_root: Optional[str] = None,
) -> bool:
    """
    Warn when source_timestamp differs from the value stored in the memory_toon JSON twin.
    Does not block validation (HDR_SOURCE_TIMESTAMP_MUTATED).
    """
    st = hdr.get("source_timestamp")
    if st is None:
        return True
    raw_mt = hdr.get("memory_toon")
    if raw_mt is None:
        return True
    mt = str(raw_mt).strip().replace("\\", "/")
    if not mt.endswith(".toon"):
        return True
    json_rel = mt[:-5] + ".json"
    if repo_root:
        json_abs = os.path.normpath(os.path.join(repo_root, json_rel.replace("/", os.sep)))
    else:
        json_abs = os.path.normpath(
            os.path.join(os.path.dirname(os.path.abspath(file_path)), json_rel.replace("/", os.sep))
        )
    baseline = _baseline_source_timestamp_from_memory_json(json_abs)
    if not baseline:
        return True
    current = str(st).strip()
    if baseline != current:
        print(
            "[WARN]  %s: source_timestamp changed from %r to %r; field should be immutable "
            "after initial external ingest (HDR_SOURCE_TIMESTAMP_MUTATED)"
            % (file_path, baseline, current)
        )
    return True


def validate_header_v419(hdr: dict, file_path: str, repo_root: Optional[str] = None) -> bool:
    """Accepted dense contracts (4.1.9 + 4.2.0): fields 20-22, identity (4.2.0), Hawaiian ban."""
    if not requires_v419_rules(hdr):
        return True
    if not validate_header_format_version_exact(hdr, file_path):
        return False
    if not validate_hawaiian_not_densified(hdr, file_path):
        return False
    if not validate_header_field_count_and_order(hdr, file_path):
        return False
    if not validate_removed_header_fields_v419(hdr, file_path):
        return False
    if not validate_identity_fields_v420(hdr, file_path):
        return False
    if not validate_channel_index(hdr, file_path):
        return False
    if not validate_source_timestamp(hdr, file_path):
        return False
    if not validate_edges_toon(hdr, file_path):
        return False
    warn_source_timestamp_immutable(hdr, file_path, repo_root=repo_root)
    return True


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
    ("thread_key", "title", "status", "summary", "department_key", "division_key", "prd_cluster")
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

# Inner line count under opening --- (Markdown): lupopedia.headers: + N key lines (N = len(V4_HEADER_KEYS_ORDERED))
V4_MD_INNER_LINE_COUNT = 1 + len(V420_HEADER_KEYS_ORDERED)
V419_MD_INNER_LINE_COUNT = 1 + len(V419_HEADER_KEYS_ORDERED)

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
    Comment grid (open fence, lupopedia.headers + N keys, close fence).
    N = 22 for 4.1.9, 28 for 4.2.0. Does not include shebang.
    """
    h = apply_v4099_header_defaults(merge_legacy_header_keys(dict(hdr)))
    ordered = header_keys_ordered_for_version(h.get("header_format_version"))
    # Ensure identity defaults for 4.2.0 emit
    if is_current_header_format_version(h.get("header_format_version")):
        if "department_key" not in h:
            h["department_key"] = ""
        if "division_key" not in h:
            h["division_key"] = ""
        for k in ("auth_user_id", "department_id", "faucet_actor_id"):
            if k not in h:
                h[k] = None
    lines = [
        "# ---------------------------------------------------------------------",
        "# lupopedia.headers:",
    ]
    for k in ordered:
        if k not in h:
            raise KeyError("emit_python_header_block_lines_from_header_dict: missing key %r" % k)
        scalar = format_yaml_header_scalar_line(k, h[k])
        lines.append("# " + scalar)
    lines.append("# ---------------------------------------------------------------------")
    expected = dense_envelope_line_count_for_version(h.get("header_format_version"))
    if len(lines) != expected:
        raise ValueError("python header line count != %s" % expected)
    return lines


def emit_markdown_inner_from_header_dict(hdr: dict) -> str:
    """
    Build the inner block (lupopedia.headers: + N keys) from a mapping.
    N = 22 for 4.1.9, 28 for 4.2.0.
    """
    h = apply_v4099_header_defaults(merge_legacy_header_keys(dict(hdr)))
    ordered = header_keys_ordered_for_version(h.get("header_format_version"))
    if is_current_header_format_version(h.get("header_format_version")):
        if "department_key" not in h:
            h["department_key"] = ""
        if "division_key" not in h:
            h["division_key"] = ""
        for k in ("auth_user_id", "department_id", "faucet_actor_id"):
            if k not in h:
                h[k] = None
    lines = ["lupopedia.headers:"]
    for k in ordered:
        if k not in h:
            raise KeyError("emit_markdown_inner_from_header_dict: missing key %r" % k)
        lines.append(format_yaml_header_scalar_line(k, h[k]))
    inner = "\n".join(lines)
    expected_inner = 1 + len(ordered)
    if len(inner.splitlines()) != expected_inner:
        raise ValueError("inner line count != %s" % expected_inner)
    return inner
