#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "lupo-scripts/validate_prd_number.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/validate_prd_number.py"
#   status: "complete"
#   when_updated: "20260412172857"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/validate-prd-number.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/validate-prd-number"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "99"
#   lupopedia.schema: implementation
#   title: "PRD Number Validator"
#   summary: "Enforces PRD filename prefix (00-99) and pk_id/prd_id ceiling from PRD 99 limits"
# ---------------------------------------------------------------------
# -*- coding: utf-8 -*-
r"""validate_prd_number.py — enforce PRD filename and numeric id ceilings (PRD 99 limits).

Rules:
  - Filenames under lupo-docs/prd/*.md (except PRD_INDEX.md, README, etc.) MUST match ^\d{2}_[^.]+\.md$
  - The two-digit prefix MUST NOT exceed 99.
  - If YAML pk_id or legacy prd_id is an int, it MUST be 0-99.

Exit 1 on violation. See lupo-docs/prd/99_limits_for_everything_and_why.md

Usage:
  python lupo-scripts/validate_prd_number.py
"""

from __future__ import annotations

import re
import sys
from pathlib import Path

try:
    import yaml
except ImportError:
    sys.stderr.write("PyYAML required\n")
    sys.exit(1)

PROJECT_ROOT = Path(__file__).resolve().parent.parent
PRD_DIR = PROJECT_ROOT / "lupo-docs" / "prd"
SKIP = frozenset(
    {
        "PRD_INDEX.md",
        "README.md",
        "WHAT_TO_DO_NEXT.md",
        "PRD_AGENT_DEFINITION_MODEL.md",
    }
)
SKIP_LOWER = frozenset(name.lower() for name in SKIP)


def split_frontmatter(text: str):
    if not text.startswith("---"):
        return None
    lines = text.splitlines()
    for i in range(1, min(len(lines), 400)):
        if lines[i].strip() == "---":
            return "\n".join(lines[1:i])
    return None


def main() -> int:
    errors: list[str] = []
    prd_pk_claims: dict[int, list[tuple[str, int]]] = {}
    pk_owners: dict[int, list[str]] = {}
    name_re = re.compile(r"^(\d{2})_(.+)\.md$")
    for md in sorted(PRD_DIR.glob("*.md")):
        if md.name.lower() in SKIP_LOWER:
            continue
        m = name_re.match(md.name)
        if not m:
            errors.append(f"Invalid PRD filename (need NN_slug.md): {md.name}")
            continue
        n = int(m.group(1))
        if n > 99:
            errors.append(f"PRD prefix > 99 in filename: {md.name}")
        text = md.read_text(encoding="utf-8", errors="replace")
        fm = split_frontmatter(text)
        if fm:
            try:
                data = yaml.safe_load(fm)
            except yaml.YAMLError:
                data = None
            if isinstance(data, dict):
                hdr = data.get("lupopedia.headers") or {}
                if isinstance(hdr, dict):
                    for key in ("pk_id", "prd_id"):
                        v = hdr.get(key)
                        if isinstance(v, int) and (v < 0 or v > 99):
                            errors.append(f"{md.name}: {key}={v} out of 0-99 range")
                    pk_id = hdr.get("pk_id")
                    if isinstance(pk_id, int):
                        prd_pk_claims.setdefault(n, []).append((md.name, pk_id))
                        pk_owners.setdefault(pk_id, []).append(md.name)

    for prd_num, claims in sorted(prd_pk_claims.items()):
        if len(claims) > 1:
            owners = ", ".join(f"{name}(pk_id={pk})" for name, pk in claims)
            errors.append(
                f"PRD {prd_num:02d} collision: multiple files claim non-NULL pk_id ({owners})"
            )

    for pk_id, owners in sorted(pk_owners.items()):
        if len(owners) > 1:
            errors.append(
                f"pk_id collision: pk_id={pk_id} claimed by multiple files ({', '.join(owners)})"
            )

    if errors:
        sys.stderr.write("\n".join(errors) + "\n")
        return 1
    print("[OK] validate_prd_number: PRD filenames and id overrides in 0-99")
    return 0


if __name__ == "__main__":
    sys.exit(main())
