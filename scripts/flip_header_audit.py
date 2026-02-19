#!/usr/bin/env python3
"""
FLIP Header Audit: add missing FLIP headers to doctrine .md files and generate seed SQL.
Run from repo root. Output: headers added to files; SQL block for seed_lupopedia.sql
"""
import os
import re
from pathlib import Path

SIGNATURE = "wolfie.headers: explicit architecture with structured clarity for every file."

def path_to_web(path):
    """Derive web block (canonical, slug, base_path) from file_path_from_root. 4.0.17 Web Path Header Extension."""
    p = path.replace("\\", "/").strip()
    if p.startswith("docs/"):
        p = p[5:]
    if p.endswith(".md"):
        p = p[:-3]
    if not p:
        return "/", "", "/"
    canonical = "/" + p
    parts = p.split("/")
    slug = parts[-1] if parts else p
    base_path = "/" + "/".join(parts[:-1]) if len(parts) > 1 else "/"
    return canonical, slug, base_path

FLIP_HEADER_TEMPLATE = """---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: {path}
file.last_modified_system_version: "4.0.17"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: {web_canonical}
  aliases:
    - /docs/{web_slug}
    - /qa/{web_slug_plus}
  slug: {web_slug}
  slug_encoding: underscore
  base_path: {web_base_path}
  url_pattern: "/{{base}}/{{slug}}"
---

"""

def has_flip_header(content):
    return SIGNATURE in content

def add_flip_header(filepath, content):
    if content.startswith("---"):
        return content
    path = filepath.replace("\\", "/")
    canonical, slug, base_path = path_to_web(path)
    slug_plus = slug.replace("_", "+")
    header = FLIP_HEADER_TEMPLATE.format(
        path=path,
        web_canonical=canonical,
        web_slug=slug,
        web_slug_plus=slug_plus,
        web_base_path=base_path,
    )
    return header + content

def main():
    base = Path(__file__).resolve().parent.parent
    scan_dirs = [
        base / "docs" / "doctrine",
        base / "docs" / "api",
    ]
    all_md = []
    for d in scan_dirs:
        if d.exists():
            all_md.extend(d.rglob("*.md"))

    missing = []
    has_header = []
    for p in sorted(all_md):
        rel = str(p.relative_to(base)).replace("\\", "/")
        try:
            content = p.read_text(encoding="utf-8")
        except Exception:
            continue
        if has_flip_header(content):
            has_header.append(rel)
        else:
            missing.append(rel)

    print("Total .md files scanned:", len(all_md))
    print("With valid FLIP header:", len(has_header))
    print("Missing FLIP header:", len(missing))
    for m in missing:
        print("  -", m)

    # Add headers to missing files
    for rel in missing:
        fp = base / rel.replace("/", os.sep)
        content = fp.read_text(encoding="utf-8")
        new_content = add_flip_header(rel, content)
        fp.write_text(new_content, encoding="utf-8")
        print("Added FLIP header:", rel)

    # Next content_id after 5032
    next_cid = 5033
    next_eid = 910068
    next_rid = 9050033

    # Files already in seed (5000-5032) - we only added headers, no new content
    # Files NOT in seed need new content entries
    in_seed = [
        "docs/doctrine/AGENT_BOUNDARIES_COMPACT.md",
        "docs/doctrine/AI_AGENT_BOOT_NOTES.md",
        "docs/doctrine/ANUBIS/ANUBIS_IMPLEMENTATION_SUMMARY.md",
        "docs/doctrine/ANUBIS/ANUBIS_ORPHAN_RULES.md",
        "docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md",
        "docs/doctrine/ANUBIS/ANUBIS_PROGRAM_SPEC.md",
        "docs/doctrine/CASCADE_TABLE_CEILING_PROTOCOL.md",
        "docs/doctrine/CLASS_CONVERSION_DOCTRINE.md",
        "docs/doctrine/COMPATIBILITY_MATRIX.md",
        "docs/doctrine/CONSOLIDATION_VALIDATION_REQUIREMENTS.md",
        "docs/doctrine/CRAFTY_SYNTAX_IMPORT_IMPLEMENTATION_CHECKLIST.md",
        "docs/doctrine/CRAFTY_SYNTAX_INTEGRATION_PLAN.md",
        "docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md",
        "docs/doctrine/CRAFTY_SYNTAX_STATE_BASED_IMPLEMENTATION_PLAN.md",
        "docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md",
        "docs/doctrine/DOCTRINE_FILE_STRUCTURE.md",
        "docs/doctrine/DOCTRINE_TABLES_TRANSITION_NOTE.md",
        "docs/doctrine/ETHICAL_STATE_MARKERS_DOCTRINE.md",
        "docs/doctrine/FILESYSTEM_MIGRATION_GUIDE.md",
        "docs/doctrine/FLIP/FLP_COUNCILS_AS_CHANNELS.md",
        "docs/doctrine/FLIP/FLP_DOCTRINE_BOUNDARIES.md",
        "docs/doctrine/FLIP/FLP_EMOTIONAL_AGGREGATION.md",
        "docs/doctrine/FLIP/FLP_EMOTIONAL_GEOMETRY.md",
        "docs/doctrine/FLIP/FLP_ESCROW_AND_FUND_LAYER.md",
        "docs/doctrine/FLIP/FLP_HETERODOX_REVIEWERS.md",
        "docs/doctrine/FLIP/FLP_LUPOPEDIA_COUNCIL_SEAT.md",
        "docs/doctrine/FLIP/FLP_OVERVIEW.md",
        "docs/doctrine/FLIP/NOTE_HEADER_VERSION_AND_MERGE.md",
        "docs/doctrine/FLIP/README.md",
        "docs/doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md",
        "docs/doctrine/ANUBIS/LILITH_ANUBIS_GUIDANCE.md",
        "docs/doctrine/ANUBIS/LILITH_ANUBIS_GUIDANCE_FLIP.md",
    ]

    need_new_content = [m for m in missing if m not in in_seed]
    return next_cid, next_eid, next_rid, need_new_content, missing

if __name__ == "__main__":
    main()
