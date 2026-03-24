#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/validate_interpretation_headers.py"
#   last_modified_utc: "20260324175617"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Interpretation Headers Validator (HEPHAESTUS enforcement tooling)

Validates that `lupopedia.interpretation` obeys the canonical three-part model:
- whoami: execution context only (no identity leakage)
- whoareyou: canonical identity (actor_id/actor_name must match registry)
- whoopposesyou: adversarial lens (relationship field only)

Hardening rules:
1) Header key canonicalization:
   - stored artifacts MUST use lowercase keys: whoami, whoareyou, whoopposesyou
   - uppercase/mixed-case variants MUST be rejected.

2) Opposition resolution rule (non-persistent):
   - If whoopposesyou is omitted for doctrinal/architectural/system-critical artifacts,
     resolve it to "lilith" at validation/interpretation/execution time only.
   - Do NOT persist or rewrite the artifact on disk.

3) WHOAMI isolation constraint:
   - whoami MUST NOT contain identity fields (actor_id, actor_name, identity_source, state, authority_level, etc.)
   - whoami is strictly execution context.

4) Opposition integrity constraint (no self-opposition):
   - whoopposesyou MUST NOT equal whoareyou.actor_name.
   - Self-opposition is invalid and must be rejected (including if implicit default resolves to lilith).

Severity:
- Emit:
  - INTERPRETATION_ERROR[CODE]: ...  (blocking)
  - INTERPRETATION_WARN[CODE]: ...   (non-blocking for channel validator strict exit)
"""

from __future__ import annotations

import argparse
import json
import re
import sys
from pathlib import Path
from typing import Dict, List, Optional, Tuple


DEFAULT_REGISTRY_PATH = Path("lupo-database/lupopedia/actors/actor_id/registry.json")

FORBIDDEN_VARIANT_PATTERNS: List[re.Pattern[str]] = [
    re.compile(r"^lilith_banned$", re.I),
    re.compile(r"^lilith_shadow$", re.I),
    re.compile(r"^wolfie_test$", re.I),
    re.compile(r"_variant", re.I),
    re.compile(r"(?:_banned|_shadow|_test)$", re.I),
]

WHOAMI_ALLOWED_KEYS = {
    "facet",
    "runtime_context",
    "channel_id",
    "thread_id",
    "session_mode",
    "project_id",
    "project_slug",
}

WHOAMI_REQUIRED_KEYS = {
    "facet",
    "runtime_context",
    "channel_id",
    "thread_id",
    "session_mode",
}


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
        if not slug:
            continue
        out[aid] = slug
    return out


def _frontmatter_span(lines: List[str]) -> Optional[Tuple[int, int]]:
    if not lines:
        return None
    if lines[0].strip() != "---":
        return None
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            return (0, i)
    return None


def _line_ref(path: Path, line_no: Optional[int]) -> str:
    if line_no is None:
        return str(path)
    return "%s:%d" % (path, line_no)


def _indent_of(line: str) -> int:
    return len(line) - len(line.lstrip(" "))


def _extract_header_kv(lines: List[str], key: str) -> Optional[str]:
    """
    Best-effort: extracts scalar header kv from YAML frontmatter.
    """
    kv = re.compile(r"^\s*%s\s*:\s*['\"]?([^'\"]+?)['\"]?\s*$" % re.escape(key))
    for ln in lines:
        m = kv.match(ln)
        if m:
            return m.group(1).strip()
    return None


def _extract_interpretation_block(
    lines: List[str],
) -> Tuple[Optional[int], Optional[int], List[str]]:
    """
    Returns (block_start_idx, block_end_exclusive_idx, block_lines).
    block_end is computed by indentation drop to <= lupopedia.interpretation indent.
    """
    base_indent = None
    start = None
    for i, ln in enumerate(lines):
        if re.match(r"^\s*lupopedia\.interpretation\s*:\s*$", ln):
            start = i
            base_indent = _indent_of(ln)
            break
    if start is None or base_indent is None:
        return (None, None, [])

    end = len(lines)
    for j in range(start + 1, len(lines)):
        if not lines[j].strip():
            continue
        if _indent_of(lines[j]) <= base_indent:
            end = j
            break
    return (start, end, lines[start:end])


def _parse_block_key_values(block_lines: List[str]) -> Tuple[Dict[str, Dict[str, str]], Optional[str], List[str]]:
    """
    Parse whoami and whoareyou dicts; and whoopposesyou scalar (if present).
    Returns:
      (who_dicts, whoopposesyou_value, issues)
    """
    whoami: Dict[str, str] = {}
    whoareyou: Dict[str, str] = {}
    whoopposesyou_value: Optional[str] = None
    issues: List[str] = []

    # Canonical sections with lowercase keys.
    whoami_line_pat = re.compile(r"^\s*whoami\s*:\s*$")
    whoareyou_line_pat = re.compile(r"^\s*whoareyou\s*:\s*$")
    whoopp_line_pat = re.compile(r"^\s*whoopposesyou\s*:\s*(.+?)\s*$")

    whoami_indent: Optional[int] = None
    whoareyou_indent: Optional[int] = None

    current = None

    for idx, ln in enumerate(block_lines):
        # Key canonicalization check is handled elsewhere; here we only parse expected keys.
        if whoami_line_pat.match(ln):
            whoami_indent = _indent_of(ln)
            current = "whoami"
            continue
        if whoareyou_line_pat.match(ln):
            whoareyou_indent = _indent_of(ln)
            current = "whoareyou"
            continue

        m_opp = whoopp_line_pat.match(ln)
        if m_opp:
            v = m_opp.group(1).strip()
            v = v.strip('"').strip("'")
            whoopposesyou_value = v
            current = None
            continue

        # Parse key/value within current section.
        if current == "whoami" and whoami_indent is not None:
            if _indent_of(ln) <= whoami_indent:
                current = None
                continue
            m_kv = re.match(r"^\s*([a-zA-Z0-9_]+)\s*:\s*(.+?)\s*$", ln)
            if m_kv:
                k = m_kv.group(1).strip()
                v = m_kv.group(2).strip().strip('"').strip("'")
                whoami[k] = v
            continue

        if current == "whoareyou" and whoareyou_indent is not None:
            if _indent_of(ln) <= whoareyou_indent:
                current = None
                continue
            m_kv = re.match(r"^\s*([a-zA-Z0-9_]+)\s*:\s*(.+?)\s*$", ln)
            if m_kv:
                k = m_kv.group(1).strip()
                v = m_kv.group(2).strip().strip('"').strip("'")
                whoareyou[k] = v
            continue

    who_dicts: Dict[str, Dict[str, str]] = {"whoami": whoami, "whoareyou": whoareyou}
    return (who_dicts, whoopposesyou_value, issues)


def _contains_uppercase_interpretation_keys(block_lines: List[str]) -> List[str]:
    """
    Detect invalid/mixed-case interpretation keys in stored YAML.
    """
    out: List[str] = []
    # Accept only exact lowercase keys. If a key matches one of the canonical interpretation keys
    # case-insensitively but is not spelled in lowercase, reject.
    for ln in block_lines:
        m = re.match(r"^\s*([A-Za-z0-9_]+)\s*:\s*", ln)
        if not m:
            continue
        k = m.group(1)
        kl = k.lower()
        if kl in ("whoami", "whoareyou", "whoopposesyou") and k != kl:
            out.append("%s: ..." % k)
    return out


def _is_doctrinal_or_system_critical(path: Path, artifact_type: Optional[str], artifact_kind: Optional[str]) -> bool:
    p = str(path).replace("\\", "/").lower()
    if "lupo-docs/doctrine/" in p or "lupo-rules/" in p:
        return True
    if artifact_type and artifact_type.lower() in ("doctrine", "canonical_reference"):
        return True
    if artifact_kind and any(x in artifact_kind.lower() for x in ("doctrine", "system", "header_template", "header_interpretation", "ratification")):
        return True
    return False


def validate_interpretation_headers_text(
    text: str,
    path: Path,
    actor_registry: Dict[int, str],
) -> List[str]:
    issues: List[str] = []
    lines = text.splitlines()
    span = _frontmatter_span(lines)
    if not span:
        return issues
    _, fm_end = span
    fm_lines = lines[:fm_end]

    # Identify interpretation block in frontmatter.
    i_start, i_end, interp_lines = _extract_interpretation_block(fm_lines)
    if i_start is None or i_end is None:
        return issues  # No interpretation block; not enforced here.

    # Extract required registries for validation.
    registry_slugs = set(actor_registry.values())

    artifact_type = _extract_header_kv(fm_lines, "artifact_type")
    artifact_kind = _extract_header_kv(fm_lines, "artifact_kind")

    critical = _is_doctrinal_or_system_critical(path, artifact_type, artifact_kind)

    # 1) Key canonicalization
    bad_keys = _contains_uppercase_interpretation_keys(interp_lines)
    if bad_keys:
        issues.append(
            "INTERPRETATION_ERROR[INVALID_CASING]: %s: interpretation keys must be lowercase; found: %s"
            % (_line_ref(path, None), ", ".join(bad_keys))
        )

    # 2) Parse whoami/whoareyou/whoopposesyou
    who_dicts, whoopposesyou_value, _ = _parse_block_key_values(interp_lines)
    whoami = who_dicts.get("whoami", {})
    whoareyou = who_dicts.get("whoareyou", {})

    # 3) Required substructures when interpretation block is present.
    if not whoami:
        issues.append(
            "INTERPRETATION_ERROR[MISSING_WHOAMI]: %s: lupopedia.interpretation.whoami missing"
            % _line_ref(path, None)
        )
    if not whoareyou:
        issues.append(
            "INTERPRETATION_ERROR[MISSING_WHOAREYOU]: %s: lupopedia.interpretation.whoareyou missing"
            % _line_ref(path, None)
        )

    # 4) WHOAMI isolation constraint (only if whoami exists)
    if whoami:
        # Disallow unknown keys (strong isolation).
        for k in whoami.keys():
            if k not in WHOAMI_ALLOWED_KEYS:
                issues.append(
                    "INTERPRETATION_ERROR[WHOAMI_IDENTITY_LEAKAGE]: %s: whoami contains forbidden key '%s'; whoami is execution-only"
                    % (_line_ref(path, None), k)
                )
        missing_required = [k for k in WHOAMI_REQUIRED_KEYS if k not in whoami]
        if missing_required:
            issues.append(
                "INTERPRETATION_ERROR[WHOAMI_MISSING_SUBFIELD]: %s: whoami missing required keys: %s"
                % (_line_ref(path, None), ", ".join(missing_required))
            )
        # Optional whoami subfields => warnings.
        missing_optional = [k for k in ("project_id", "project_slug") if k not in whoami]
        for mk in missing_optional:
            issues.append(
                "INTERPRETATION_WARN[WHOAMI_MISSING_OPTIONAL_SUBFIELD]: %s: whoami missing optional key '%s'"
                % (_line_ref(path, None), mk)
            )

    # 5) WHOAREYOU validation against registry (only if whoareyou exists)
    whoareyou_actor_id: Optional[int] = None
    whoareyou_actor_name: Optional[str] = None
    if whoareyou:
        aid_raw = whoareyou.get("actor_id")
        name_raw = whoareyou.get("actor_name")
        if aid_raw is None or name_raw is None:
            issues.append(
                "INTERPRETATION_ERROR[WHOAREYOU_INCOMPLETE]: %s: whoareyou must include actor_id and actor_name"
                % _line_ref(path, None)
            )
        else:
            try:
                whoareyou_actor_id = int(aid_raw)
            except Exception:
                issues.append(
                    "INTERPRETATION_ERROR[WHOAREYOU_NON_INTEGER_ACTOR_ID]: %s: whoareyou.actor_id must be integer"
                    % _line_ref(path, None)
                )
            whoareyou_actor_name = str(name_raw).strip()
            if whoareyou_actor_id is not None:
                if whoareyou_actor_id not in actor_registry:
                    issues.append(
                        "INTERPRETATION_ERROR[WHOAREYOU_NON_CANONICAL_ACTOR_ID]: %s: whoareyou.actor_id %s not in registry"
                        % (_line_ref(path, None), whoareyou_actor_id)
                    )
                else:
                    canonical_name = actor_registry[whoareyou_actor_id]
                    if whoareyou_actor_name != canonical_name:
                        issues.append(
                            "INTERPRETATION_ERROR[WHOAREYOU_MISMATCH_REGISTRY]: %s: whoareyou actor_name '%s' does not match registry slug '%s' for actor_id %s"
                            % (_line_ref(path, None), whoareyou_actor_name, canonical_name, whoareyou_actor_id)
                        )
            # Variant actor detection in whoareyou.actor_name.
            for pat in FORBIDDEN_VARIANT_PATTERNS:
                if pat.match(whoareyou_actor_name or ""):
                    issues.append(
                        "INTERPRETATION_ERROR[WHOAREYOU_VARIANT_FORBIDDEN]: %s: forbidden variant actor_name '%s'"
                        % (_line_ref(path, None), whoareyou_actor_name)
                    )
                    break

    # 6) Opposition resolution + integrity (only if whoareyou_actor_name exists)
    resolved_opposition: Optional[str] = None
    if whoareyou_actor_name is not None:
        if whoopposesyou_value is not None:
            resolved_opposition = whoopposesyou_value
        else:
            # Opposition resolution is non-persistent.
            if critical:
                resolved_opposition = "lilith"
            else:
                resolved_opposition = None

        if whoopposesyou_value is None:
            issues.append(
                "INTERPRETATION_WARN[MISSING_WHOOPPOSESYOU]: %s: whoopposesyou omitted%s"
                % (
                    _line_ref(path, None),
                    ("; resolved default=%s" % resolved_opposition) if resolved_opposition else "",
                )
            )

        # Validate resolved opposition only if present.
        if resolved_opposition:
            if resolved_opposition == whoareyou_actor_name:
                issues.append(
                    "INTERPRETATION_ERROR[OPPOSITION_SELF]: %s: whoopposesyou '%s' equals whoareyou.actor_name; self-opposition rejected"
                    % (_line_ref(path, None), resolved_opposition)
                )
            if resolved_opposition not in registry_slugs:
                issues.append(
                    "INTERPRETATION_ERROR[WHOOPPOSESYOU_NON_CANONICAL]: %s: whoopposesyou '%s' not in actor registry"
                    % (_line_ref(path, None), resolved_opposition)
                )
            for pat in FORBIDDEN_VARIANT_PATTERNS:
                if pat.match(resolved_opposition or ""):
                    issues.append(
                        "INTERPRETATION_ERROR[WHOOPPOSESYOU_VARIANT_FORBIDDEN]: %s: forbidden variant whoopposesyou '%s'"
                        % (_line_ref(path, None), resolved_opposition)
                    )
                    break

    return issues


def validate_path(repo_root: Path, target: Path, actor_registry: Dict[int, str]) -> List[str]:
    issues: List[str] = []
    if target.is_file():
        if target.suffix.lower() != ".md":
            return issues
        try:
            text = target.read_text(encoding="utf-8", errors="replace")
        except OSError:
            return issues
        issues.extend(validate_interpretation_headers_text(text, target, actor_registry))
        return issues

    for f in target.rglob("*.md"):
        if f.name.lower() == "readme.md":
            continue
        try:
            text = f.read_text(encoding="utf-8", errors="replace")
        except OSError:
            continue
        issues.extend(validate_interpretation_headers_text(text, f, actor_registry))
    return issues


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--repo-root", default=".")
    ap.add_argument("--file", default="")
    ap.add_argument("--channel", type=int, default=0)
    args = ap.parse_args()

    repo_root = Path(args.repo_root).resolve()
    actor_registry = load_actor_registry(repo_root)

    if args.file:
        target = Path(args.file)
        issues = validate_path(repo_root, target, actor_registry)
    elif args.channel:
        base = repo_root / "lupo-channels" / str(args.channel) / "threads"
        issues = validate_path(repo_root, base, actor_registry)
    else:
        print("ERROR: provide --file or --channel", file=sys.stderr)
        return 2

    for i in issues:
        print(i)

    # Return failure only for blocking errors.
    blocking = [i for i in issues if not i.startswith("INTERPRETATION_WARN[")]
    return 1 if blocking else 0


if __name__ == "__main__":
    sys.exit(main())
