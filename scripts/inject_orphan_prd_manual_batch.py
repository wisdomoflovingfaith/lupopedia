#!/usr/bin/env python3
"""
Inject PRD lineage edges for doctrine orphans using the manual category map
(Battle Report 20260403). Does not scan agents/ (ANUBIS agent doctrine moved out).

Usage:
  python scripts/inject_orphan_prd_manual_batch.py --dry-run
  python scripts/inject_orphan_prd_manual_batch.py --apply
"""
from __future__ import print_function

import argparse
import json
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DOCTRINE = ROOT / "docs" / "doctrine"

ORPHAN_REASON = "Doctrine PRD lineage; orphan batch 20260403 (manual category map)"

# PRD id -> slug under docs/prd/
PRD_SLUG = {
    0: "00_root_constitutional_system_requirements",
    2: "02_data_model",
    11: "11_analytics_tracking",
    13: "13_crafty_integration",
    16: "16_lupopedia_headers",
    18: "18_channel_chat_display",
    26: "26_five_layer_documentation_architecture",
    28: "28_semantic_monitoring_widget",
    29: "29_project_structure",
    30: "30_channel_usage_patterns",
    31: "31_implementation_folder_guidelines",
    32: "32_actor_authority_agent_roles",
    33: "33_softaculous_certification_4_1_0_gate",
}

# Relative path under ROOT -> list of PRD ids to ensure (skip if already in frontmatter)
ORPHAN_MAP = {
    # A — Actor / identity (32)
    "docs/doctrine/ACTOR_AGENT_AUTH_USER_MODEL.md": [32],
    "docs/doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md": [32],
    "docs/doctrine/ACTOR_REQUIREMENTS.md": [32],
    "docs/doctrine/AUTH_DOCTRINE.md": [32],
    "docs/doctrine/IDENTITY_MODEL.md": [32],
    "docs/doctrine/IDENTITY_MODEL_QUICKSTART_4.0.88.md": [32],
    "docs/doctrine/SESSION_MODEL.md": [32],
    # C — Infrastructure (33 / 29)
    "docs/doctrine/CLOUDFLARE_DOCTRINE.md": [33],
    "docs/doctrine/CLOUDFLARE_VS_FLARE.md": [33],
    "docs/doctrine/DIRECTORY_STRUCTURE_DOCTRINE.md": [29],
    "docs/doctrine/EMOJI_AND_SMILIES.md": [33],
    "docs/doctrine/HEALTH_CHECK_DOCTRINE.md": [33],
    "docs/doctrine/MINIMAL_HOSTING_REQUIREMENTS.md": [33],
    # D — Development / CLI / registry
    "docs/doctrine/CLI_DOCTRINE.md": [0],
    "docs/doctrine/CONFIGURATION_DOCTRINE.md": [0],
    "docs/doctrine/CONTEXT_MODEL_DOCTRINE.md": [26],
    "docs/doctrine/DUAL_CONTEXT_IDENTITY_DOCTRINE.md": [26],
    "docs/doctrine/JSON_SCHEMA_REFERENCE_DOCTRINE.md": [26],
    "docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md": [29],
    "docs/doctrine/PROJECT_REGISTRY_WORKFLOW.md": [29],
    "docs/doctrine/SYSTEM_LIMITS_DOCTRINE.md": [0],
    "docs/doctrine/TOON_DOCTRINE.md": [26],
    # E — LUPOPEDIA_HEADERS version subtree (16)
    "docs/doctrine/LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md": [16],
    "docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/changelog.md": [16],
    "docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/decisions.md": [16],
    "docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/observations.md": [16],
    "docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/README.md": [16],
    "docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/todo.md": [16],
    # F — Rose / communication (18)
    "docs/doctrine/COMMUNICATION_DOCTRINE.md": [18],
    "docs/doctrine/HELP_SYSTEM_DOCTRINE.md": [18],
    "docs/doctrine/ROSE_DOCTRINE.md": [18],
    "docs/doctrine/ROSE_PACKET_MAPPING.md": [18],
    "docs/doctrine/RUNTIME_ORCHESTRATION_LOOP.md": [18],
    # G — Collections / hierarchy (26)
    "docs/doctrine/COLLECTIONS_DOCTRINE.md": [26],
    "docs/doctrine/HIERARCHY_DOCTRINE.md": [26],
    # H — Table management (11 per category table; PRD file is analytics — lineage hook)
    "docs/doctrine/TABLE_COUNT_DOCTRINE.md": [11],
    # I — Root rules external (00)
    "docs/doctrine/ROOTRULES_EXTERNAL_ACTOR.md": [0],
}


def parse_frontmatter(text):
    if not text.startswith("---"):
        return None, text
    rest = text[3:]
    m = re.search(r"\n---\s*\n", rest)
    if m:
        return rest[: m.start()], rest[m.end() :]
    m2 = re.search(r"\n# file:", rest)
    if m2:
        return rest[: m2.start()], rest[m2.start() :]
    return None, text


def prd_targets_in_fm(fm_text):
    found = set()
    for m in re.finditer(
        r"docs/prd/([0-9]+[a-z0-9_]*)\.md", fm_text, flags=re.IGNORECASE
    ):
        found.add("docs/prd/%s.md" % m.group(1))
    return found


def detect_edge_list_base_indent(fm):
    m = re.search(r"outbound_edges:\s*\n(\s+)-\s", fm)
    if m:
        return m.group(1)
    return "    "


def build_edge_yaml_lines(prd_ids, base_indent):
    lines = []
    cont = base_indent + "  "
    for pid in prd_ids:
        slug = PRD_SLUG.get(pid)
        if not slug:
            continue
        to = "docs/prd/%s.md" % slug
        lines.append('%s- to: "%s"' % (base_indent, to))
        lines.append("%stype: implements" % cont)
        lines.append("%sweight: 1.0" % cont)
        lines.append('%sreason: "%s"' % (cont, ORPHAN_REASON))
    return "\n".join(lines)


def inject_before_first_footer(text, block):
    m = re.search(r"^lupopedia\.footer:", text, re.MULTILINE)
    if not m:
        return None
    pos = m.start()
    return text[:pos] + block + "\n" + text[pos:]


def build_injection_block(fm, needed_ids):
    base = detect_edge_list_base_indent(fm)
    lines = build_edge_yaml_lines(needed_ids, base)
    if not lines:
        return ""
    if not re.search(r"^lupopedia\.edges:", fm, re.MULTILINE):
        return (
            "lupopedia.edges:\n  outbound_edges:\n"
            + build_edge_yaml_lines(needed_ids, "    ")
            + "\n"
        )
    if not re.search(r"outbound_edges:", fm):
        return (
            "lupopedia.edges:\n  outbound_edges:\n"
            + build_edge_yaml_lines(needed_ids, "    ")
            + "\n"
        )
    return lines + "\n"


def process_one(rel_posix, apply_changes):
    path = ROOT.joinpath(*rel_posix.split("/"))
    if not path.is_file():
        return {"path": rel_posix, "status": "missing", "reason": "file not found"}

    prd_ids = ORPHAN_MAP[rel_posix]
    text = path.read_text(encoding="utf-8", errors="replace")
    if not text.lstrip().startswith("---"):
        return {"path": rel_posix, "status": "skip", "reason": "no_leading_delimiter"}

    fm, _ = parse_frontmatter(text)
    if fm is None:
        return {"path": rel_posix, "status": "skip", "reason": "no_frontmatter"}

    existing = prd_targets_in_fm(fm)
    needed = []
    for pid in prd_ids:
        to = "docs/prd/%s.md" % PRD_SLUG[pid]
        if to not in existing:
            needed.append(pid)

    if not needed:
        return {
            "path": rel_posix,
            "status": "ok",
            "reason": "already_linked",
            "prd_ids": prd_ids,
        }

    block = build_injection_block(fm, needed)
    new_text = inject_before_first_footer(text, block)
    if new_text is None:
        return {"path": rel_posix, "status": "skip", "reason": "no_lupopedia_footer"}

    if apply_changes:
        path.write_text(new_text, encoding="utf-8", newline="\n")

    return {
        "path": rel_posix,
        "status": "updated",
        "added_prd_ids": needed,
        "prd_ids": prd_ids,
    }


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--apply", action="store_true")
    ap.add_argument("--dry-run", action="store_true")
    args = ap.parse_args()
    apply_changes = bool(args.apply) and not args.dry_run

    results = []
    for rel in sorted(ORPHAN_MAP.keys()):
        results.append(process_one(rel, apply_changes=apply_changes))

    updated = sum(1 for r in results if r.get("status") == "updated")
    ok = sum(1 for r in results if r.get("status") == "ok")
    skipped = sum(1 for r in results if r.get("status") == "skip")
    missing = sum(1 for r in results if r.get("status") == "missing")
    print(
        json.dumps(
            {
                "total": len(ORPHAN_MAP),
                "updated" if apply_changes else "would_update": updated,
                "already_ok": ok,
                "skipped": skipped,
                "missing": missing,
                "results": results,
            },
            indent=2,
        )
    )
    if missing:
        sys.exit(1)


if __name__ == "__main__":
    main()
