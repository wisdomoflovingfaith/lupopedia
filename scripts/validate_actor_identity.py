#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "scripts/validate_actor_identity.py"
#   questions_toon: null
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
Actor Identity Validator (HEPHAESTUS enforcement tooling)

Validates that identity lookup and identity usage across artifacts obey:
1) Canonical Actor Identity from actor registry only
2) Actor != Facet separation (IDE faucet names must not appear as actor identity)
3) Variant actor detection (lilith_banned, wolfie_test, lilith_shadow, etc.)
4) Banned actor visibility (banned actors remain addressable by canonical actor_id)
5) Runtime context misuse (system actor_id 0 must not masquerade as an identity)

Rule spec + detection logic:
- Parse the first YAML frontmatter block (between leading '---' delimiters).
- Extract `lupopedia.headers.actor_id` and `lupopedia.headers.actor_name`.
- Validate:
  - actor_id exists in registry and matches actor_name slug
  - actor_id != 0 for identity-bearing artifacts
  - actor_name is not a forbidden variant identity
  - actor_name is not an IDE faucet slug (cursor, windsurf, kiro, warp, cascade, vscode-ide, trae, antigravity-ide)
  - if actor_id == 2 then actor_name must be 'lilith' (no hiding/replacement)

Example violations (reported as ERROR codes):
- ACTOR_NON_CANONICAL: actor_id not in registry
- ACTOR_HIDDEN_IDENTITY: actor_id exists but actor_name missing or mismatched
- ACTOR_VARIANT_FORBIDDEN: actor_name like 'lilith_banned' / 'wolfie_test' / 'lilith_shadow'
- ACTOR_IDE_CONFUSION: actor_name is an IDE faucet name used as an actor identity
- RUNTIME_IDENTITY_OVERRIDE: actor_id==0 used as an artifact identity
- ACTOR_ACRONYM_MISSING: actor_name is placeholder-like ('none', 'system', empty)

Integration plan:
- CI / enforcement gateway:
  - call validate_channel_artifacts.py with --actor-identity-validation (or --mode enforce).
- Local use:
  - run this script directly to validate one file or a whole channel tree.
"""

from __future__ import annotations

import argparse
import json
import re
import sys
from pathlib import Path
from typing import Dict, List, Optional, Set, Tuple


DEFAULT_REGISTRY_PATH = Path("database/lupopedia/actors/actor_id/registry.json")

IDE_FAUCETS: Set[str] = frozenset(
    {
        "cursor",
        "windsurf",
        "kiro",
        "cascade",
        "warp",
        "vscode-ide",
        "trae",
        "antigravity-ide",
    }
)

# Forbidden variant identities (stateful names must not create new identities).
FORBIDDEN_VARIANT_PATTERNS: List[re.Pattern[str]] = [
    re.compile(r"^lilith_banned$", re.I),
    re.compile(r"^lilith_shadow$", re.I),
    re.compile(r"^wolfie_test$", re.I),
    re.compile(r"_variant", re.I),
    re.compile(r"(?:_banned|_shadow|_test)$", re.I),
]

PLACEHOLDER_ACTOR_NAMES: Set[str] = frozenset({"", "none", "system", "agent", "none_agent"})


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
    # Find closing delimiter.
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            return (0, i)
    return None


def _extract_actor_identity_from_yaml(lines: List[str]) -> Tuple[Optional[int], Optional[str], List[Tuple[int, str]]]:
    """
    Returns (actor_id, actor_name, raw_hits) where raw_hits contains (line_no, raw_value).
    """
    span = _frontmatter_span(lines)
    if not span:
        return (None, None, [])
    start, end = span
    actor_id: Optional[int] = None
    actor_name: Optional[str] = None
    raw_hits: List[Tuple[int, str]] = []

    re_actor_id = re.compile(r"^\s*actor_id\s*:\s*(\d+)\s*$", re.I)
    re_actor_name = re.compile(r'^\s*actor_name\s*:\s*["\']?([^"\']+?)["\']?\s*$', re.I)

    for idx in range(start, end):
        line = lines[idx]
        m_id = re_actor_id.match(line)
        if m_id:
            try:
                actor_id = int(m_id.group(1))
            except Exception:
                actor_id = None
            raw_hits.append((idx + 1, line.strip()))
            continue
        m_name = re_actor_name.match(line)
        if m_name:
            actor_name = m_name.group(1).strip()
            raw_hits.append((idx + 1, line.strip()))
            continue
    return (actor_id, actor_name, raw_hits)


def _line_ref(path: Path, line_no: Optional[int]) -> str:
    if line_no is None:
        return str(path)
    return "%s:%d" % (path, line_no)


def validate_actor_identity_text(
    text: str,
    path: Path,
    actor_registry: Dict[int, str],
) -> List[str]:
    errors: List[str] = []
    lines = text.splitlines()
    actor_id, actor_name, raw_hits = _extract_actor_identity_from_yaml(lines)
    # Best-effort line number (first raw hit).
    hit_line = raw_hits[0][0] if raw_hits else None

    if actor_id is None and actor_name is None:
        # Many artifacts may not carry identity; treat as no-op for safety.
        return errors

    if actor_id is None:
        errors.append(
            "ACTOR_HIDDEN_IDENTITY[%s]: %s: actor_id missing in lupopedia.headers; fix: include actor_id matching registry"
            % (path, _line_ref(path, hit_line))
        )
        return errors

    if actor_id == 0:
        errors.append(
            "RUNTIME_IDENTITY_OVERRIDE[%s]: %s: actor_id 0 used as identity; fix: use canonical actor_id from registry (no system masquerade)"
            % (path, _line_ref(path, hit_line))
        )
        return errors

    if actor_id not in actor_registry:
        errors.append(
            "ACTOR_NON_CANONICAL[%s]: %s: actor_id %s not present in registry.json; fix: allocate canonical id via registry and use it"
            % (path, _line_ref(path, hit_line), actor_id)
        )
        return errors

    canonical_slug = actor_registry[actor_id]

    # Placeholder-like actor names are forbidden even if registry contains the ID.
    if actor_name is None:
        errors.append(
            "ACTOR_HIDDEN_IDENTITY[%s]: %s: actor_name missing but actor_id %s resolves to '%s'; fix: set actor_name to canonical slug"
            % (path, _line_ref(path, hit_line), actor_id, canonical_slug)
        )
        return errors

    name_norm = actor_name.strip()
    if name_norm.lower() in PLACEHOLDER_ACTOR_NAMES:
        errors.append(
            "ACTOR_ACRONYM_MISSING[%s]: %s: placeholder actor_name '%s'; fix: use canonical actor_name from registry"
            % (path, _line_ref(path, hit_line), actor_name)
        )
        return errors

    # Forbidden variant identities.
    for pat in FORBIDDEN_VARIANT_PATTERNS:
        if pat.match(name_norm):
            errors.append(
                "ACTOR_VARIANT_FORBIDDEN[%s]: %s: forbidden variant actor_name '%s'; fix: return canonical actor_name for actor_id"
                % (path, _line_ref(path, hit_line), actor_name)
            )
            return errors

    # Actor != Facet: IDE faucet names must not be used as actor identity.
    if name_norm.lower() in IDE_FAUCETS:
        errors.append(
            "ACTOR_IDE_CONFUSION[%s]: %s: IDE faucet name '%s' used as actor identity; fix: use canonical persona actor_id/name for actor, and put IDE name in lupo_agent/lifecycle fields"
            % (path, _line_ref(path, hit_line), actor_name)
        )
        return errors

    # Registry must match artifact identity.
    if name_norm != canonical_slug:
        # Special handling: banned actor visibility (Lilith must remain visible as actor_id 2).
        if actor_id == 2:
            errors.append(
                "ACTOR_HIDDEN_IDENTITY[%s]: %s: actor_id 2 must remain 'lilith'; found actor_name '%s'; fix: do not hide/replace Lilith; keep actor_id stable"
                % (path, _line_ref(path, hit_line), actor_name)
            )
        else:
            errors.append(
                "ACTOR_HIDDEN_IDENTITY[%s]: %s: actor_id %s resolves to '%s' but artifact has actor_name '%s'; fix: set canonical actor_name"
                % (path, _line_ref(path, hit_line), actor_id, canonical_slug, actor_name)
            )
        return errors

    return errors


def validate_path(repo_root: Path, target: Path, actor_registry: Dict[int, str]) -> List[str]:
    errors: List[str] = []
    if target.is_file():
        if target.suffix.lower() != ".md":
            return errors
        try:
            text = target.read_text(encoding="utf-8", errors="replace")
        except OSError:
            return errors
        errors.extend(validate_actor_identity_text(text, target, actor_registry))
        return errors

    # Directory: validate markdown files.
    for f in target.rglob("*.md"):
        if f.name.lower() == "readme.md":
            continue
        try:
            text = f.read_text(encoding="utf-8", errors="replace")
        except OSError:
            continue
        errors.extend(validate_actor_identity_text(text, f, actor_registry))
    return errors


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--repo-root", default=".")
    ap.add_argument("--file", default="")
    ap.add_argument("--channel", type=int, default=0)
    ap.add_argument("--print-spec", action="store_true")
    args = ap.parse_args()

    repo_root = Path(args.repo_root).resolve()
    actor_registry = load_actor_registry(repo_root)

    if args.print_spec:
        print("Actor Identity Validator spec: see module docstring.")

    if args.file:
        t = Path(args.file)
        errs = validate_path(repo_root, t, actor_registry)
    elif args.channel:
        base = repo_root / "channels" / str(args.channel) / "threads"
        errs = validate_path(repo_root, base, actor_registry)
    else:
        print("ERROR: provide --file or --channel", file=sys.stderr)
        return 2

    for e in errs:
        print(e)
    return 1 if errs else 0


if __name__ == "__main__":
    sys.exit(main())
