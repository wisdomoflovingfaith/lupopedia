#!/usr/bin/env python3
"""
Add lupopedia.edges outbound links from doctrine files to governing PRD(s),
and optionally emit classification JSON for implementation edges.md tables.

Usage:
  python lupo-scripts/apply_doctrine_prd_lineage.py --dry-run
  python lupo-scripts/apply_doctrine_prd_lineage.py --apply
  python lupo-scripts/apply_doctrine_prd_lineage.py --apply --emit-json path.json
"""
from __future__ import print_function

import argparse
import json
import re
import sys
from pathlib import Path

try:
    import yaml
except ImportError:
    yaml = None

ROOT = Path(__file__).resolve().parents[1]
DOCTRINE = ROOT / "lupo-docs" / "doctrine"

REASON = "Doctrine PRD lineage; constitutional audit 20260403"

# PRD id -> filename slug under lupo-docs/prd/
PRD_SLUG = {
    0: "00_root_constitutional_system_requirements",
    13: "13_crafty_integration",
    16: "16_lupopedia_headers",
    26: "26_five_layer_documentation_architecture",
    28: "28_semantic_monitoring_widget",
    29: "29_project_structure",
    30: "30_channel_usage_patterns",
    31: "31_implementation_folder_guidelines",
    32: "32_actor_authority_agent_roles",
    33: "33_softaculous_certification_4_1_0_gate",
}


def parse_frontmatter(text):
    """YAML between first --- and closing --- or # file: (see audit script)."""
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
    """Return set of lupo-docs/prd/*.md paths found in outbound_edges (regex)."""
    found = set()
    for m in re.finditer(
        r"lupo-docs/prd/([0-9]+[a-z0-9_]*)\.md", fm_text, flags=re.IGNORECASE
    ):
        found.add("lupo-docs/prd/%s.md" % m.group(1))
    return found


def classify(rel_posix, filename):
    """Return ordered unique PRD ids that govern this doctrine file."""
    p = rel_posix.replace("\\", "/")
    n = filename.lower()
    pl = p.lower()
    ids = []

    def add(i):
        if i not in ids:
            ids.append(i)

    # 16 — LUPOPEDIA HEADERS subtree and header-named files
    if (
        "lupopedia_headers" in pl
        or "/headers/" in pl
        or n
        in (
            "wolfie_headers.md",
            "middle_headers_doctrine.md",
            "required_flare_headers.md",
            "header_structure_doctrine.md",
            "x_lupo_forwarded_header_doctrine.md",
        )
    ):
        add(16)

    # 26 — documentation architecture / five-layer
    if any(
        x in p
        for x in (
            "DOCUMENTATION_ARCHITECTURE",
            "DOCUMENTATION_AS_DATA",
            "5W1H_QUICK_REFERENCE",
            "TOON_DOCTRINE",
            "JSON_SCHEMA_REFERENCE",
            "ws7_documentation_reconciliation_doctrine",
        )
    ):
        add(26)

    # 28 + 33 — DynAPI / semantic monitoring / minimal hosting cluster
    if any(
        x in p
        for x in (
            "DYNAPI_DOCTRINE",
            "MOOD_RGB_DOCTRINE",
            "GC_DOCTRINE",
            "MULTI_AGENT_5W1H_DOCTRINE",
        )
    ):
        add(28)
    if any(
        x in p
        for x in (
            "PHP_COMPATIBILITY_AND_MINIMAL_HOSTING",
            "MINIMAL_HOSTING_REQUIREMENTS",
            "INSTALLATION_PATH_DOCTRINE",
            "SAFE_MIGRATION_DOCTRINE",
            "IMPORT_FROM_CRAFTY",
            "LEXA_GATEWAY_INTEGRATION",
        )
    ):
        add(33)

    # 29 — project / filesystem layout
    if any(
        x in p
        for x in (
            "DIRECTORY_STRUCTURE_DOCTRINE",
            "FILESYSTEM_MIGRATION_GUIDE",
            "FILESYSTEM_OBJECTS_AND_DATABASE_SNAPSHOTS",
            "PROJECT_REGISTRY_DOCTRINE",
            "PROJECT_REGISTRY_WORKFLOW",
            "channels/filesystem_padding",
            "INDEX.md",
        )
    ) or n in ("channels.md",) or "channels/" in pl:
        add(29)

    # 30 — channel usage / coordination (not only structure)
    if any(
        x in p
        for x in (
            "CHANNEL_BASED_COORDINATION",
            "CHANNEL_66_QUESTION_GRAPH",
            "CHANNEL_MODEL_DOCTRINE",
        )
    ) or "channels/" in pl or n == "channels.md":
        add(30)

    # 31 — decisions / threads / implementation scaffolding
    if any(
        x in p
        for x in (
            "DECISION_MODEL.md",
            "THREAD_DIALOG_SYSTEM.md",
            "IDE_AGENT_CONTINUITY_PROTOCOL.md",
        )
    ):
        add(31)

    # 32 — actors / agents / identity authority (not header format)
    if any(
        x in p
        for x in (
            "ACTOR_",
            "AGENT_REGISTRY",
            "ActorFaucet",
            "IDENTITY_MODEL",
            "IDENTITY_AUTHORITY",
            "IDENTITY_LAYERS",
            "DUAL_CONTEXT_IDENTITY",
            "AUTH_USERS_ACTORS",
            "AUTHORIZATION_DOCTRINE",
            "AUTH_DOCTRINE",
            "EFFECTIVE_ACTOR_RESOLUTION",
            "FAUCET_TRACEABILITY",
            "ANUBIS",
            "HUMANACTOR",
            "HYBRID_ACTOR",
            "SUPPORTING_ACTOR",
            "ACTOR_AGENT_DISTINCTION",
        )
    ):
        add(32)

    # 13 — Crafty Syntax migration / integration (product import)
    if any(
        x in p
        for x in (
            "CRAFTY_SYNTAX_INTEGRATION",
            "CRAFTY_SYNTAX_MIGRATION",
            "CRAFTY_SYNTAX_STATE",
            "CRAFTY_SYNTAX_IMPORT",
        )
    ):
        add(13)

    # 33 alone for table ceiling / schema hosting pressure (overlaps 00)
    if any(
        x in p
        for x in (
            "TABLE_CEILING_DEFENSE",
            "TABLE_CONSOLIDATION",
            "CASCADE_TABLE_CEILING",
            "SCHEMA_AND_TOON_ALIGNMENT",
        )
    ):
        add(33)

    if not ids:
        add(0)
    return ids


def detect_edge_list_base_indent(fm):
    """Whitespace before the first list ``-`` under ``outbound_edges`` (may be 2 or 4 spaces)."""
    m = re.search(r"outbound_edges:\s*\n(\s+)-\s", fm)
    if m:
        return m.group(1)
    return "    "


def build_edge_yaml_lines(prd_ids, base_indent):
    """One outbound edge per PRD id; indentation matches existing list items."""
    lines = []
    cont = base_indent + "  "
    for pid in prd_ids:
        slug = PRD_SLUG.get(pid)
        if not slug:
            continue
        to = "lupo-docs/prd/%s.md" % slug
        lines.append('%s- to: "%s"' % (base_indent, to))
        lines.append("%stype: implements" % cont)
        lines.append("%sweight: 1.0" % cont)
        lines.append('%sreason: "%s"' % (cont, REASON))
    return "\n".join(lines)


def inject_before_first_footer(text, block):
    m = re.search(r"^lupopedia\.footer:", text, re.MULTILINE)
    if not m:
        return None
    pos = m.start()
    return text[:pos] + block + "\n" + text[pos:]


def build_injection_block(fm, needed_ids):
    """YAML fragment to insert before lupopedia.footer (list items or full edges block)."""
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


def process_file(path, apply_changes):
    rel = path.relative_to(ROOT).as_posix()
    text = path.read_text(encoding="utf-8", errors="replace")
    if not text.lstrip().startswith("---"):
        return {"path": rel, "status": "skip", "reason": "no_leading_delimiter"}

    fm, _ = parse_frontmatter(text)
    if fm is None:
        return {"path": rel, "status": "skip", "reason": "no_frontmatter"}

    existing = prd_targets_in_fm(fm)
    prd_ids = classify(rel, path.name)
    needed = []
    for pid in prd_ids:
        to = "lupo-docs/prd/%s.md" % PRD_SLUG[pid]
        if to not in existing:
            needed.append(pid)

    if not needed:
        return {"path": rel, "status": "ok", "reason": "already_linked", "prd_ids": prd_ids}

    block = build_injection_block(fm, needed)
    new_text = inject_before_first_footer(text, block)
    if new_text is None:
        return {"path": rel, "status": "skip", "reason": "no_lupopedia_footer"}

    if apply_changes:
        path.write_text(new_text, encoding="utf-8", newline="\n")

    return {
        "path": rel,
        "status": "updated",
        "added_prd_ids": needed,
        "prd_ids": prd_ids,
    }


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--apply", action="store_true")
    ap.add_argument("--emit-json", metavar="PATH", help="Write classification map JSON")
    ap.add_argument("--dry-run", action="store_true")
    args = ap.parse_args()
    apply_changes = bool(args.apply) and not args.dry_run

    if not yaml:
        print("PyYAML required", file=sys.stderr)
        sys.exit(1)

    files = sorted(DOCTRINE.rglob("*.md"))
    results = []

    for f in files:
        r = process_file(f, apply_changes=apply_changes)
        results.append(r)

    by_prd_sets = {k: set() for k in PRD_SLUG}
    for f in files:
        rel = f.relative_to(ROOT).as_posix()
        for pid in classify(rel, f.name):
            if pid in by_prd_sets:
                by_prd_sets[pid].add(rel)
    by_prd = {k: sorted(v) for k, v in by_prd_sets.items()}

    if args.emit_json:
        Path(args.emit_json).write_text(
            json.dumps({"by_prd": by_prd, "results": results}, indent=2),
            encoding="utf-8",
        )

    updated = sum(1 for r in results if r.get("status") == "updated")
    skipped = sum(1 for r in results if r.get("status") == "skip")
    ok = sum(1 for r in results if r.get("status") == "ok")
    summary = {"total": len(files), "already_ok": ok, "skipped": skipped}
    summary["updated" if apply_changes else "would_update"] = updated
    print(json.dumps(summary, indent=2))


if __name__ == "__main__":
    main()
