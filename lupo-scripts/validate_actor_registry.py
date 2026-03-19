#!/usr/bin/env python3
"""
Actor Registry Validator (HEPHAESTUS enforcement tooling)

Purpose:
- Validate the canonical actor registry used by Convergence Doctrine.

Checks:
1) Registry parses and actor IDs/slugs are unique
2) actor_id 2 exists and slug is exactly 'lilith' (canonical, never replaced)
3) No variant actors are present in registry:
   - lilith_banned, lilith_shadow, wolfie_test, *_variant, trailing _test/_shadow/_banned

Notes:
- This validator is offline (no DB). It validates repo sources of truth only.
"""

from __future__ import annotations

import argparse
import json
import re
import sys
from pathlib import Path
from typing import Dict, List, Set, Tuple


DEFAULT_REGISTRY_PATH = Path("lupo-database/lupopedia/actors/actor_id/registry.json")

FORBIDDEN_SLUG_PATTERNS: List[re.Pattern[str]] = [
    re.compile(r"^lilith_banned$", re.I),
    re.compile(r"^lilith_shadow$", re.I),
    re.compile(r"^wolfie_test$", re.I),
    re.compile(r"_variant", re.I),
    re.compile(r"(?:_banned|_shadow|_test)$", re.I),
]


def load_registry(repo_root: Path) -> Tuple[Dict[int, str], List[dict]]:
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
    return out, actors


def validate_registry(repo_root: Path) -> List[str]:
    errors: List[str] = []
    actor_map, actors = load_registry(repo_root)

    if not actors:
        errors.append("ACTOR_REGISTRY_EMPTY: actors list is empty")
        return errors

    ids = list(actor_map.keys())
    if len(ids) != len(set(ids)):
        errors.append("ACTOR_REGISTRY_DUPLICATE_ID: duplicate actor_id detected")

    slugs = list(actor_map.values())
    if len(slugs) != len(set(slugs)):
        errors.append("ACTOR_REGISTRY_DUPLICATE_SLUG: duplicate actor slug detected")

    if 2 not in actor_map:
        errors.append("ACTOR_MISSING_CANONICAL_LILITH: actor_id 2 missing; fix: add Lilith (actor_id 2) to registry")
    else:
        if actor_map[2].strip().lower() != "lilith":
            errors.append(
                "ACTOR_MISMATCH_CANONICAL_LILITH: actor_id 2 slug must be 'lilith'; found '%s'"
                % actor_map[2]
            )

    for aid, slug in actor_map.items():
        for pat in FORBIDDEN_SLUG_PATTERNS:
            if pat.match(slug):
                errors.append(
                    "ACTOR_VARIANT_FORBIDDEN[actor_id=%s]: forbidden registry slug '%s'; fix: do not add variant actors"
                    % (aid, slug)
                )
                break

    return errors


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--repo-root", default=".")
    ap.add_argument("--print-spec", action="store_true")
    args = ap.parse_args()

    repo_root = Path(args.repo_root).resolve()

    if args.print_spec:
        print("Actor Registry Validator spec: registry integrity + no variant actors + canonical Lilith at actor_id 2.")

    errs = validate_registry(repo_root)
    for e in errs:
        print(e)
    return 1 if errs else 0


if __name__ == "__main__":
    sys.exit(main())
