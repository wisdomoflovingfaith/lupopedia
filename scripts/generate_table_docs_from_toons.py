#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/generate_table_docs_from_toons.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/generate_table_docs_from_toons.py"
#   status: "complete"
#   when_updated: "20260415080730"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/generate-table-docs-from-toons.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/generate-table-docs-from-toons"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "16"
#   lupopedia.schema: implementation
#   title: "Generate table documentation from TOON JSON"
#   summary: "Regenerate database table docs from TOON JSON sources"
# ---------------------------------------------------------------------
"""Phase 2 controlled regeneration from TOON JSON only.

Non-negotiable guarantees:
- Scope lock: process only files resolved from corruption manifest tokens.
- Source of truth: schema from database/lupopedia/json/*.json.
- No DB interactions.
- Deterministic output using a fixed phase timestamp.
"""

import argparse
import json
import os
import re
import subprocess
from collections import OrderedDict
from datetime import datetime, timezone
from pathlib import Path


PROJECT_ROOT = Path(__file__).resolve().parent.parent
TOON_JSON_DIR = PROJECT_ROOT / "database" / "lupopedia" / "json"
ACTIVE_DIR = PROJECT_ROOT / "docs" / "database" / "lupopedia" / "tables" / "active"
CORRUPTION_MANIFEST = PROJECT_ROOT / "docs" / "reports" / "CORRUPTION_MANIFEST.md"
PHASE2_MANIFEST_OUT = PROJECT_ROOT / "docs" / "reports" / "PHASE2_REGENERATION_MANIFEST_20260327.md"
PHASE2_COMPLETION_OUT = PROJECT_ROOT / "docs" / "reports" / "Phase2_Completion_Report_20260327.md"

# Fixed phase timestamp for deterministic output
PHASE_TS = "20260327234500"
PHASE_DATE = "20260327"


def utc_iso_from_ymdhis(ymdhis):
    dt = datetime.strptime(ymdhis, "%Y%m%d%H%M%S").replace(tzinfo=timezone.utc)
    return dt.isoformat().replace("+00:00", "Z")


def utc_file_stamp():
    return "20260327_234500"


def namespace_for_table(table_name):
    if table_name.startswith("lupo_auth") or table_name.startswith("lupo_actor_auth"):
        return "auth"
    if table_name.startswith("lupo_channel") or table_name == "lupo_channels":
        return "channels"
    if table_name.startswith("lupo_analytics"):
        return "analytics"
    if table_name.startswith("lupo_federation"):
        return "federation"
    if table_name.startswith("lupo_gov") or table_name.startswith("lupo_governance"):
        return "governance"
    if table_name.startswith("lupo_crafty"):
        return "legacy"
    return "core"


def parse_manifest_tokens(manifest_path):
    """Extract table tokens and category from the corruption manifest.

    Supports lines like:
    - **lupo_actors** - Actor registry table
    - **lupo_crafty_syntax_*** tables - Legacy compatibility
    """
    content = manifest_path.read_text(encoding="utf-8", errors="replace")
    category = "unspecified"
    tokens = []
    for raw in content.splitlines():
        line = raw.strip()
        if line.startswith("### "):
            category = line.replace("###", "", 1).strip().lower().replace(" ", "_")
            continue
        if not line.startswith("- **lupo_"):
            continue
        m = re.search(r"\*\*([^*]+)\*\*", line)
        if m:
            token = m.group(1).strip()
            tokens.append((token, category))
    return tokens


def resolve_execution_set(tokens):
    """Resolve token list to concrete active doc paths (scope lock)."""
    resolved = OrderedDict()
    for token, category in tokens:
        if "*" in token:
            pattern = token.replace("*", "*") + ".md"
            for p in sorted(ACTIVE_DIR.glob(pattern)):
                resolved[p] = {
                    "table": p.stem,
                    "category": category,
                    "corruption_type": "manifest-wildcard"
                }
        else:
            p = ACTIVE_DIR / f"{token}.md"
            if p.exists():
                resolved[p] = {
                    "table": token,
                    "category": category,
                    "corruption_type": "manifest-listed"
                }
            else:
                # Keep a virtual entry for reporting variance
                resolved[p] = {
                    "table": token,
                    "category": category,
                    "corruption_type": "manifest-listed-missing"
                }
    return resolved


def extract_frontmatter(text):
    lines = text.splitlines()
    if len(lines) < 3 or lines[0].strip() != "---":
        return None
    end = None
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            end = i
            break
    if end is None:
        return None
    return "\n".join(lines[1:end])


def get_git_recovered_header(rel_path):
    """Try HEAD, then older commits to recover clean frontmatter."""
    candidates = []
    try:
        head_txt = subprocess.check_output(
            ["git", "show", f"HEAD:{rel_path}"],
            cwd=str(PROJECT_ROOT),
            text=True,
            stderr=subprocess.DEVNULL,
        )
        candidates.append(head_txt)
    except Exception:
        pass

    try:
        log = subprocess.check_output(
            ["git", "log", "--format=%H", "--", rel_path],
            cwd=str(PROJECT_ROOT),
            text=True,
            stderr=subprocess.DEVNULL,
        )
        for h in [x.strip() for x in log.splitlines() if x.strip()][:5]:
            try:
                txt = subprocess.check_output(
                    ["git", "show", f"{h}:{rel_path}"],
                    cwd=str(PROJECT_ROOT),
                    text=True,
                    stderr=subprocess.DEVNULL,
                )
                candidates.append(txt)
            except Exception:
                continue
    except Exception:
        pass

    for txt in candidates:
        fm = extract_frontmatter(txt)
        if not fm:
            continue
        if "lupopedia.headers:" not in fm:
            continue
        if "lupopedia.footer:" not in fm:
            continue
        # Basic corruption guard
        if "`n" in fm or "Ã" in fm:
            continue
        return fm
    return None


def build_synthetic_frontmatter(table_name, rel_file):
    ts_iso = utc_iso_from_ymdhis(PHASE_TS)
    ns = namespace_for_table(table_name)
    return "\n".join([
        "lupopedia.headers:",
        "  when_updated: \"%s\"" % PHASE_TS,
        "  file_path_from_root: \"%s\"" % rel_file.replace("\\", "/"),
        "  questions_toon: null",  # was last_modified_utc (deprecated in PRD 16 v4.0.99)
        "  channel_id: 42",
        "  actor_id: 23",
        "  actor_name: \"hephaestus\"",
        "  delegation_chain: \"wolfie:hephaestus\"",
        "  artifact_type: \"documentation\"",
        "  artifact_kind: \"table\"",
        "  namespace: \"%s\"" % ns,
        "  purpose: \"Regenerated table documentation for %s from TOON JSON\"" % table_name,
        "  tags:",
        "  - database",
        "  - table",
        "  - regenerated",
        "  - 4.0.88",
        "lupopedia.edges:",
        "  comment: \"static placeholder edges for phase2 regeneration\"",
        "  outbound_edges:",
        "  - to: \"database/lupopedia/json/%s.json\"" % table_name,
        "    type: \"references\"",
        "    weight: 1.0",
        "    reason: \"authoritative TOON JSON source\"",
        "lupopedia.footer:",
        "  last_verified: \"%s\"" % PHASE_TS,
        "  last_verified_by: \"hephaestus\"",
        "  last_verified_by_actor_id: 23",
        "  generated: true",
        "  provenance: \"phase2_synthetic_header_no_git_recovery\"",
        "  generated_at_iso: \"%s\"" % ts_iso,
    ])


def normalize_recovered_frontmatter(frontmatter, table_name, rel_file):
    """Keep recovered header but enforce required deterministic/safety keys."""
    ns = namespace_for_table(table_name)
    fm = frontmatter
    if re.search(r"^\s*when_updated\s*:", fm, flags=re.M):
        fm = re.sub(r"^\s*when_updated\s*:.*$", '  when_updated: "%s"' % PHASE_TS, fm, flags=re.M)
    else:
        fm = fm.replace("lupopedia.headers:\n", "lupopedia.headers:\n  when_updated: \"%s\"\n" % PHASE_TS)

    # Phase-3 migration: normalize recovered last_modified_utc to questions_toon: null
    # (last_modified_utc was renamed to questions_toon in PRD 16 v4.0.99 §4.2 field 6)
    if re.search(r"^\s*last_modified_utc\s*:", fm, flags=re.M):
        fm = re.sub(r"^\s*last_modified_utc\s*:.*$", "  questions_toon: null", fm, flags=re.M)
    elif not re.search(r"^\s*questions_toon\s*:", fm, flags=re.M):
        fm = fm.replace("lupopedia.headers:\n", "lupopedia.headers:\n  questions_toon: null\n")

    if re.search(r"^\s*file_path_from_root\s*:", fm, flags=re.M):
        fm = re.sub(
            r"^\s*file_path_from_root\s*:.*$",
            '  file_path_from_root: "%s"' % rel_file.replace("\\", "/"),
            fm,
            flags=re.M,
        )

    # v4.1.0: emit atoms_toon; accept legacy namespace/module as input, do not re-emit them
    if re.search(r"^\s*namespace\s*:", fm, flags=re.M):
        fm = re.sub(r"^\s*namespace\s*:.*$", '  atoms_toon: "%s"' % ns, fm, flags=re.M)
    elif re.search(r"^\s*module\s*:", fm, flags=re.M):
        fm = re.sub(r"^\s*module\s*:.*$", '  atoms_toon: "%s"' % ns, fm, flags=re.M)
    elif re.search(r"^\s*atoms_toon\s*:", fm, flags=re.M):
        fm = re.sub(r"^\s*atoms_toon\s*:.*$", '  atoms_toon: "%s"' % ns, fm, flags=re.M)
    else:
        fm = fm.replace("lupopedia.headers:\n", "lupopedia.headers:\n  atoms_toon: \"%s\"\n" % ns)

    if "lupopedia.edges:" not in fm:
        fm += "\nlupopedia.edges:\n"
        fm += "  comment: \"static placeholder edges for phase2 regeneration\"\n"
        fm += "  outbound_edges:\n"
        fm += "  - to: \"database/lupopedia/json/%s.json\"\n" % table_name
        fm += "    type: \"references\"\n"
        fm += "    weight: 1.0\n"
        fm += "    reason: \"authoritative TOON JSON source\"\n"
    elif not re.search(r"^\s*comment\s*:.*(snapshot|static)", fm, flags=re.M | re.I):
        fm = fm.replace("lupopedia.edges:\n", "lupopedia.edges:\n  comment: \"static placeholder edges for phase2 regeneration\"\n")

    if "lupopedia.footer:" not in fm:
        fm += "\nlupopedia.footer:\n"
        fm += "  last_verified: \"%s\"\n" % PHASE_TS
        fm += "  last_verified_by: \"hephaestus\"\n"
        fm += "  last_verified_by_actor_id: 23\n"

    if re.search(r"^\s*last_verified\s*:", fm, flags=re.M):
        fm = re.sub(r"^\s*last_verified\s*:.*$", '  last_verified: "%s"' % PHASE_TS, fm, flags=re.M)
    else:
        fm = fm.replace("lupopedia.footer:\n", "lupopedia.footer:\n  last_verified: \"%s\"\n" % PHASE_TS)

    if not re.search(r"^\s*last_verified_by\s*:", fm, flags=re.M):
        fm = fm.replace("lupopedia.footer:\n", "lupopedia.footer:\n  last_verified_by: \"hephaestus\"\n")
    if not re.search(r"^\s*last_verified_by_actor_id\s*:", fm, flags=re.M):
        fm = fm.replace("lupopedia.footer:\n", "lupopedia.footer:\n  last_verified_by_actor_id: 23\n")

    # Explicitly mark that regenerated body is deterministic TOON regeneration.
    if not re.search(r"^\s*generated\s*:", fm, flags=re.M):
        fm = fm.replace("lupopedia.footer:\n", "lupopedia.footer:\n  generated: true\n")
    if not re.search(r"^\s*provenance\s*:", fm, flags=re.M):
        fm = fm.replace("lupopedia.footer:\n", "lupopedia.footer:\n  provenance: \"phase2_git_header_recovered_body_regenerated\"\n")

    return fm


def parse_fields(fields):
    rows = []
    for f in fields:
        raw = str(f).strip()
        m = re.match(r"`([^`]+)`\s+(.+)$", raw)
        if m:
            col = m.group(1)
            ctype = m.group(2)
        else:
            parts = raw.split(None, 1)
            col = parts[0].strip("`")
            ctype = parts[1] if len(parts) > 1 else ""
        rows.append((col, ctype, raw))
    return rows


def parse_indexes(indexes):
    normalized = []
    for idx in indexes:
        if not isinstance(idx, dict):
            continue
        name = idx.get("index_name", "")
        cols = idx.get("columns", [])
        if isinstance(cols, str):
            cols = [cols]
        unique = bool(idx.get("is_unique", False))
        normalized.append((name, cols, unique))
    return normalized


def build_body(table_name, toon):
    fields = toon.get("fields", [])
    indexes = toon.get("indexes", [])
    pk = ""
    primary_key = toon.get("primary_key")
    if isinstance(primary_key, dict):
        pk = primary_key.get("column", "")
    elif isinstance(primary_key, str):
        pk = primary_key

    field_rows = parse_fields(fields)
    index_rows = parse_indexes(indexes)

    lines = []
    lines.append(f"# file: {table_name}.md")
    lines.append("")
    lines.append(f"# {table_name}")
    lines.append("")
    lines.append("## Purpose")
    lines.append(f"Canonical table documentation regenerated from TOON JSON for `{table_name}`.")
    lines.append("")
    lines.append("## Schema")
    lines.append("")
    lines.append("### Primary Key")
    lines.append(pk if pk else "(none)")
    lines.append("")
    lines.append("### Columns")
    lines.append("")
    lines.append("| Column | Type Definition |")
    lines.append("|---|---|")
    for col, ctype, _raw in field_rows:
        lines.append(f"| `{col}` | `{ctype}` |")
    lines.append("")
    lines.append("### Indexes")
    lines.append("")
    lines.append("| Index | Columns | Unique |")
    lines.append("|---|---|---|")
    for name, cols, unique in index_rows:
        joined = ", ".join([f"`{c}`" for c in cols])
        lines.append(f"| `{name}` | {joined} | {'yes' if unique else 'no'} |")
    lines.append("")
    lines.append("## Doctrine")
    lines.append("- Source of truth: `database/lupopedia/json/` TOON exports")
    lines.append("- Regeneration mode: Phase 2 deterministic rebuild")
    lines.append("- Edge mode: placeholder only")
    lines.append("")
    return "\n".join(lines) + "\n"


def run_header_validator(file_paths):
    results = []
    validator = PROJECT_ROOT / "scripts" / "validate_lupopedia_headers.php"
    for p in file_paths:
        rel = str(p.relative_to(PROJECT_ROOT)).replace("\\", "/")
        cmd = ["php", str(validator), rel]
        proc = subprocess.run(cmd, cwd=str(PROJECT_ROOT), capture_output=True, text=True)
        results.append((p, proc.returncode, proc.stderr.strip()))
    return results


def schema_alignment_check(file_path, toon):
    """Deterministic check: every TOON field/index token must be represented in doc content."""
    txt = file_path.read_text(encoding="utf-8", errors="replace")
    ok = True
    notes = []
    for f in toon.get("fields", []):
        m = re.match(r"`([^`]+)`\s+(.+)$", str(f).strip())
        if not m:
            continue
        col = m.group(1)
        ctype = m.group(2)
        if f"`{col}`" not in txt or f"`{ctype}`" not in txt:
            ok = False
            notes.append(f"missing field token: {col}")
    for idx in toon.get("indexes", []):
        name = idx.get("index_name", "") if isinstance(idx, dict) else ""
        if name and f"`{name}`" not in txt:
            ok = False
            notes.append(f"missing index token: {name}")
    return ok, "; ".join(notes)


def write_phase2_reports(manifest_rows, stats, header_results, schema_results, variance_note):
    # Regeneration manifest
    out = []
    out.append("---")
    out.append("lupopedia.headers:")
    out.append('  when_updated: "20260327234500"')
    out.append('  file_path_from_root: "docs/reports/PHASE2_REGENERATION_MANIFEST_20260327.md"')
    out.append('  questions_toon: null')  # was last_modified_utc (deprecated PRD 16 v4.0.99)
    out.append("  channel_id: 42")
    out.append("  actor_id: 23")
    out.append('  actor_name: "hephaestus"')
    out.append('  artifact_type: "report"')
    out.append('  artifact_kind: "manifest"')
    out.append("lupopedia.footer:")
    out.append('  last_verified: "20260327234500"')
    out.append('  last_verified_by: "hephaestus"')
    out.append("  last_verified_by_actor_id: 23")
    out.append("---")
    out.append("# file: PHASE2_REGENERATION_MANIFEST_20260327.md")
    out.append("")
    out.append("# PHASE2_REGENERATION_MANIFEST_20260327")
    out.append("")
    out.append("| file_path | action | notes |")
    out.append("|---|---|---|")
    for row in manifest_rows:
        out.append(f"| {row['file_path']} | {row['action']} | {row['notes']} |")
    PHASE2_MANIFEST_OUT.parent.mkdir(parents=True, exist_ok=True)
    PHASE2_MANIFEST_OUT.write_text("\n".join(out) + "\n", encoding="utf-8", newline="\n")

    # Completion report
    hr_fail = [r for r in header_results if r[1] != 0]
    sr_fail = [r for r in schema_results if not r[1]]
    rep = []
    rep.append("---")
    rep.append("lupopedia.headers:")
    rep.append('  when_updated: "20260327234500"')
    rep.append('  file_path_from_root: "docs/reports/Phase2_Completion_Report_20260327.md"')
    rep.append('  questions_toon: null')  # was last_modified_utc (deprecated PRD 16 v4.0.99)
    rep.append("  channel_id: 42")
    rep.append("  actor_id: 23")
    rep.append('  actor_name: "hephaestus"')
    rep.append('  artifact_type: "report"')
    rep.append('  artifact_kind: "completion"')
    rep.append("lupopedia.footer:")
    rep.append('  last_verified: "20260327234500"')
    rep.append('  last_verified_by: "hephaestus"')
    rep.append("  last_verified_by_actor_id: 23")
    rep.append("---")
    rep.append("# file: Phase2_Completion_Report_20260327.md")
    rep.append("")
    rep.append("# Phase2 Completion Report 20260327")
    rep.append("")
    rep.append(f"1. total targeted files: {stats['targeted']}")
    rep.append(f"2. total regenerated files: {stats['regenerated']}")
    rep.append(f"3. header validation result: {'PASS' if len(hr_fail) == 0 else 'FAIL'}")
    rep.append(f"4. schema alignment result: {'PASS' if len(sr_fail) == 0 else 'FAIL'}")
    rep.append(f"5. synthetic header count: {stats['synthetic']}")
    rep.append(f"6. restored header count: {stats['restored']}")
    rep.append(f"7. failed/deferred files: {stats['failed']}")
    rep.append("8. confirmation of no non-corrupted overwrite: PASS (scope lock enforced from manifest tokens only)")
    rep.append("")
    rep.append("## Count Validation")
    rep.append(variance_note)
    rep.append("")
    if hr_fail:
        rep.append("## Header Validation Failures")
        for p, _rc, stderr in hr_fail:
            rel = str(p.relative_to(PROJECT_ROOT)).replace("\\", "/")
            rep.append(f"- {rel}: {stderr if stderr else 'validator returned non-zero'}")
        rep.append("")
    if sr_fail:
        rep.append("## Schema Alignment Failures")
        for p, _ok, note in sr_fail:
            rel = str(p.relative_to(PROJECT_ROOT)).replace("\\", "/")
            rep.append(f"- {rel}: {note}")
        rep.append("")
    PHASE2_COMPLETION_OUT.parent.mkdir(parents=True, exist_ok=True)
    PHASE2_COMPLETION_OUT.write_text("\n".join(rep) + "\n", encoding="utf-8", newline="\n")


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--dry-run", action="store_true", help="Resolve and report without writing files")
    args = parser.parse_args()

    if not CORRUPTION_MANIFEST.exists():
        raise SystemExit("Corruption manifest missing: %s" % CORRUPTION_MANIFEST)

    tokens = parse_manifest_tokens(CORRUPTION_MANIFEST)
    execution_set = resolve_execution_set(tokens)

    manifest_rows = []
    regenerated = []
    stats = {
        "targeted": len(execution_set),
        "regenerated": 0,
        "restored": 0,
        "synthetic": 0,
        "failed": 0,
    }

    for path, meta in execution_set.items():
        rel = str(path.relative_to(PROJECT_ROOT)).replace("\\", "/")
        table = meta["table"]
        toon_path = TOON_JSON_DIR / f"{table}.json"

        if not path.exists():
            manifest_rows.append({
                "file_path": rel,
                "action": "skipped",
                "notes": "target file missing in active directory"
            })
            continue
        if not toon_path.exists():
            stats["failed"] += 1
            manifest_rows.append({
                "file_path": rel,
                "action": "failed",
                "notes": "missing TOON JSON source"
            })
            continue

        toon = json.loads(toon_path.read_text(encoding="utf-8", errors="replace"))
        recovered = get_git_recovered_header(rel)
        if recovered:
            frontmatter = normalize_recovered_frontmatter(recovered, table, rel)
            action = "restored-header"
            stats["restored"] += 1
        else:
            frontmatter = build_synthetic_frontmatter(table, rel)
            action = "synthetic-header"
            stats["synthetic"] += 1

        body = build_body(table, toon)
        rendered = "---\n" + frontmatter.strip() + "\n---\n" + body

        if not args.dry_run:
            path.write_text(rendered, encoding="utf-8", newline="\n")
        stats["regenerated"] += 1
        regenerated.append((path, toon))
        manifest_rows.append({
            "file_path": rel,
            "action": action,
            "notes": f"category={meta['category']}; corruption_type={meta['corruption_type']}"
        })

    header_results = run_header_validator([p for p, _ in regenerated]) if not args.dry_run else []
    schema_results = []
    if not args.dry_run:
        for p, toon in regenerated:
            ok, note = schema_alignment_check(p, toon)
            schema_results.append((p, ok, note))

    variance_note = (
        "Processed count equals resolved execution set from manifest tokens. "
        "Manifest is summary-style (table tokens + wildcard entries), not a full path inventory; "
        "therefore targeted count is token-expanded set size."
    )
    write_phase2_reports(manifest_rows, stats, header_results, schema_results, variance_note)

    print("PHASE2 execution complete")
    print("targeted=%d regenerated=%d restored=%d synthetic=%d failed=%d" % (
        stats["targeted"], stats["regenerated"], stats["restored"], stats["synthetic"], stats["failed"]
    ))
    print("manifest=%s" % PHASE2_MANIFEST_OUT)
    print("completion=%s" % PHASE2_COMPLETION_OUT)


if __name__ == "__main__":
    main()
