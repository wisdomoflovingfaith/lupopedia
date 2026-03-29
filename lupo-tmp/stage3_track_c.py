import subprocess, json, re, shutil
from pathlib import Path
from datetime import datetime

root = Path(r"c:/ServBay/www/servbay/lupopedia")
active = root / "lupo-docs/database/lupopedia/tables/active"
json_dir = root / "lupo-database/lupopedia/json"
report_path = root / "lupo-docs/reports/STAGE3_DRIFT_RESOLUTION_REPORT.md"
archive_dir = root / "lupo-docs/database/lupopedia/tables/archived/stage3_orphaned_20260328"
archive_dir.mkdir(parents=True, exist_ok=True)

TS = "20260328012000"
DATE = "20260328"


def run(cmd):
    p = subprocess.run(cmd, cwd=str(root), capture_output=True, text=True)
    if p.returncode != 0:
        raise RuntimeError(f"cmd failed {' '.join(cmd)}\\n{p.stderr}")
    return p.stdout


def get_git_status_entries():
    out = run(["git", "status", "--porcelain", "--", "lupo-docs/database/lupopedia/tables/active"])
    rows = []
    for line in out.splitlines():
        if not line.strip():
            continue
        status = line[:2]
        path = line[3:].strip().replace('\\\\','/')
        rows.append((status, path))
    return rows


def has_corruption_markers(txt):
    markers = ["ï»¿", "â€", "â†", "ðŸ", "ï¿½", "`n", "# LUPOPEDIA HEADERS (replaces FLARE)"]
    return any(m in txt for m in markers)


def yaml_header_valid_shape(txt):
    lines = txt.splitlines()
    if len(lines) < 3:
        return False
    if lines[0].strip() != "---":
        return False
    close_idx = None
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            close_idx = i
            break
    if close_idx is None:
        return False
    if close_idx + 1 >= len(lines):
        return False
    if not lines[close_idx+1].strip().startswith("# file:"):
        return False
    return True


def namespace_for_table(name):
    if name.startswith("lupo_auth") or name.startswith("lupo_actor_auth"):
        return "auth"
    if name.startswith("lupo_channel") or name == "lupo_channels":
        return "channels"
    if name.startswith("lupo_analytics"):
        return "analytics"
    if name.startswith("lupo_federation"):
        return "federation"
    if name.startswith("lupo_gov") or name.startswith("lupo_governance"):
        return "governance"
    if name.startswith("lupo_crafty"):
        return "legacy"
    if name.startswith("lupo_api"):
        return "integration"
    return "core"


def parse_fields(fields):
    rows = []
    for f in fields:
        s = str(f).strip()
        m = re.match(r"`([^`]+)`\\s+(.+)$", s)
        if m:
            rows.append((m.group(1), m.group(2)))
        else:
            parts = s.split(None, 1)
            col = parts[0].strip("`")
            ctype = parts[1] if len(parts) > 1 else ""
            rows.append((col, ctype))
    return rows


def parse_indexes(indexes):
    out = []
    for idx in indexes:
        if not isinstance(idx, dict):
            continue
        name = idx.get("index_name", "")
        cols = idx.get("columns", [])
        if isinstance(cols, str):
            cols = [cols]
        out.append((name, cols, bool(idx.get("is_unique", False))))
    return out


def build_doc(table, rel_path, toon):
    ns = namespace_for_table(table)
    fields = parse_fields(toon.get("fields", []))
    indexes = parse_indexes(toon.get("indexes", []))
    pk = ""
    pko = toon.get("primary_key")
    if isinstance(pko, dict):
        pk = pko.get("column", "")
    elif isinstance(pko, str):
        pk = pko

    lines = []
    lines.extend([
        "---",
        "lupopedia.headers:",
        f'  when_updated: "{TS}"',
        f'  file_path_from_root: "{rel_path}"',
        f'  last_modified_utc: "{TS}"',
        "  channel_id: 42",
        "  actor_id: 23",
        '  actor_name: "hephaestus"',
        '  delegation_chain: "wolfie:hephaestus"',
        '  artifact_type: "documentation"',
        '  artifact_kind: "table"',
        f'  namespace: "{ns}"',
        f'  purpose: "Normalized table documentation for {table} from TOON JSON"',
        "  tags:",
        "  - database",
        "  - table",
        "  - normalized",
        "  - 4.0.88",
        "lupopedia.edges:",
        '  comment: "static placeholder edges for stage3 normalization"',
        "  outbound_edges:",
        f'  - to: "lupo-database/lupopedia/json/{table}.json"',
        '    type: "references"',
        "    weight: 1.0",
        '    reason: "authoritative TOON JSON source"',
        "lupopedia.footer:",
        f'  last_verified: "{TS}"',
        '  last_verified_by: "hephaestus"',
        "  last_verified_by_actor_id: 23",
        "  generated: true",
        '  provenance: "stage3_track_c_normalization"',
        "---",
        f"# file: {table}.md",
        "",
        f"# {table}",
        "",
        "## Purpose",
        f"Canonical table documentation normalized from TOON JSON for `{table}`.",
        "",
        "## Schema",
        "",
        "### Primary Key",
        pk if pk else "(none)",
        "",
        "### Columns",
        "",
        "| Column | Type Definition |",
        "|---|---|",
    ])
    for c, t in fields:
        lines.append(f"| `{c}` | `{t}` |")
    lines.extend([
        "",
        "### Indexes",
        "",
        "| Index | Columns | Unique |",
        "|---|---|---|",
    ])
    for name, cols, uniq in indexes:
        lines.append(f"| `{name}` | {', '.join([f'`{c}`' for c in cols])} | {'yes' if uniq else 'no'} |")
    lines.extend([
        "",
        "## Doctrine",
        "- Source of truth: `lupo-database/lupopedia/json/` TOON exports",
        "- Regeneration mode: Stage 3 deterministic normalization",
        "- Edge mode: placeholder baseline",
        "",
    ])
    return "\\n".join(lines)


def schema_tokens_match(txt, toon):
    for f in toon.get("fields", []):
        m = re.match(r"`([^`]+)`\\s+(.+)$", str(f).strip())
        if not m:
            continue
        col, ctype = m.group(1), m.group(2)
        if f"`{col}`" not in txt or f"`{ctype}`" not in txt:
            return False
    for idx in toon.get("indexes", []):
        if isinstance(idx, dict):
            nm = idx.get("index_name", "")
            if nm and f"`{nm}`" not in txt:
                return False
    return True

entries = get_git_status_entries()

# duplicate detection on drift set by stem collisions
stem_map = {}
for _, p in entries:
    stem = Path(p).stem
    stem_map.setdefault(stem, []).append(p)
duplicates = set()
for stem, paths in stem_map.items():
    if len(paths) > 1:
        duplicates.update(paths)

rows = []
summary = {"total":0,"valid":0,"corrupted":0,"outdated":0,"orphaned":0,"duplicate":0,
           "regen":0,"archived":0,"kept":0,"consolidated":0}

for status, path in sorted(entries, key=lambda x: x[1]):
    summary["total"] += 1
    abs_p = root / path
    table = Path(path).stem
    toon_path = json_dir / f"{table}.json"
    exists = abs_p.exists()
    toon_exists = toon_path.exists()

    cls = None
    reason = []
    action = None

    if path in duplicates:
        cls = "duplicate"
        reason.append("stem collision in drift set")
    elif status.strip().startswith("D") or not exists:
        cls = "orphaned"
        reason.append("deleted in worktree")
    else:
        txt = abs_p.read_text(encoding="utf-8", errors="replace")
        if has_corruption_markers(txt) or not yaml_header_valid_shape(txt):
            cls = "corrupted"
            if has_corruption_markers(txt):
                reason.append("corruption markers detected")
            if not yaml_header_valid_shape(txt):
                reason.append("invalid header shape")
        elif not toon_exists:
            cls = "orphaned"
            reason.append("no matching TOON JSON source")
        else:
            toon = json.loads(toon_path.read_text(encoding="utf-8", errors="replace"))
            if not schema_tokens_match(txt, toon):
                cls = "outdated"
                reason.append("schema tokens do not fully match TOON JSON")
            else:
                cls = "valid"
                reason.append("clean drift entry")

    summary[cls] += 1

    if cls in ("corrupted", "outdated") and toon_exists:
        toon = json.loads(toon_path.read_text(encoding="utf-8", errors="replace"))
        rel_path = path.replace('\\\\','/')
        rendered = build_doc(table, rel_path, toon)
        abs_p.write_text(rendered, encoding="utf-8", newline="\n")
        action = "regenerated_from_toon_json"
        summary["regen"] += 1
    elif cls == "orphaned":
        target = archive_dir / Path(path).name
        if exists:
            if target.exists():
                target = archive_dir / f"{Path(path).stem}__{TS}.md"
            target.parent.mkdir(parents=True, exist_ok=True)
            shutil.move(str(abs_p), str(target))
            action = f"archived_move->{target.relative_to(root).as_posix()}"
        else:
            # restore historical content into archive for traceability
            restored = ""
            try:
                restored = run(["git", "show", f"HEAD:{path}"])
            except Exception:
                restored = f"# archived placeholder\\n\\nOriginal file was deleted in worktree and no HEAD content was recovered for {path}.\\n"
            if target.exists():
                target = archive_dir / f"{Path(path).stem}__{TS}.md"
            target.write_text(restored, encoding="utf-8", newline="\n")
            action = f"archived_from_head->{target.relative_to(root).as_posix()}"
        summary["archived"] += 1
    elif cls == "duplicate":
        target = archive_dir / f"duplicate__{Path(path).name}"
        if exists:
            if target.exists():
                target = archive_dir / f"duplicate__{Path(path).stem}__{TS}.md"
            shutil.move(str(abs_p), str(target))
            action = f"consolidated_archive->{target.relative_to(root).as_posix()}"
        else:
            action = "consolidated_noop_missing"
        summary["consolidated"] += 1
    else:
        action = "kept_untouched"
        summary["kept"] += 1

    rows.append({
        "path": path,
        "class": cls,
        "reason": "; ".join(reason),
        "action": action,
        "status": status,
        "toon_exists": "true" if toon_exists else "false",
        "exists": "true" if abs_p.exists() else "false",
    })

report = []
report.extend([
    "---",
    "lupopedia.headers:",
    f'  when_updated: "{TS}"',
    '  file_path_from_root: "lupo-docs/reports/STAGE3_DRIFT_RESOLUTION_REPORT.md"',
    f'  last_modified_utc: "{TS}"',
    "  channel_id: 42",
    "  actor_id: 23",
    '  actor_name: "hephaestus"',
    '  artifact_type: "report"',
    '  artifact_kind: "drift_resolution"',
    "lupopedia.footer:",
    f'  last_verified: "{TS}"',
    '  last_verified_by: "hephaestus"',
    "  last_verified_by_actor_id: 23",
    "---",
    "# file: STAGE3_DRIFT_RESOLUTION_REPORT.md",
    "",
    "# STAGE3 Drift Resolution Report",
    "",
    "## Summary",
    f"- Total entries: {summary['total']}",
    f"- Valid: {summary['valid']}",
    f"- Corrupted: {summary['corrupted']}",
    f"- Outdated: {summary['outdated']}",
    f"- Orphaned: {summary['orphaned']}",
    f"- Duplicate: {summary['duplicate']}",
    "",
    "## Actions Taken",
    f"- Regenerated from TOON JSON: {summary['regen']}",
    f"- Archived moved/restored: {summary['archived']}",
    f"- Consolidated duplicates: {summary['consolidated']}",
    f"- Kept untouched: {summary['kept']}",
    "",
    "## Full Resolution Matrix",
    "| path | git_status | class | toon_exists | exists_after | action | reason |",
    "|---|---|---|---|---|---|---|",
])
for r in rows:
    report.append(f"| {r['path']} | {r['status']} | {r['class']} | {r['toon_exists']} | {r['exists']} | {r['action']} | {r['reason']} |")

report_path.write_text("\\n".join(report) + "\\n", encoding="utf-8", newline="\n")
print(f"Wrote {report_path}")
print(json.dumps(summary, indent=2))
