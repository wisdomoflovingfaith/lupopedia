import subprocess, json, re, shutil
from pathlib import Path

root = Path(r"c:/ServBay/www/servbay/lupopedia")
active = root / "lupo-docs/database/lupopedia/tables/active"
json_dir = root / "lupo-database/lupopedia/json"
report_path = root / "lupo-docs/reports/STAGE3_DRIFT_RESOLUTION_REPORT.md"
archive_dir = root / "lupo-docs/database/lupopedia/tables/archived/stage3_orphaned_20260328"
archive_dir.mkdir(parents=True, exist_ok=True)
TS = "20260328013000"


def run(cmd):
    p = subprocess.run(cmd, cwd=str(root), capture_output=True, text=True)
    if p.returncode != 0:
        raise RuntimeError(f"cmd failed {' '.join(cmd)}\\n{p.stderr}")
    return p.stdout


def get_drift_entries():
    out = run(["git", "status", "--porcelain", "--", "lupo-docs/database/lupopedia/tables/active"])
    rows = []
    for line in out.splitlines():
        if line.strip():
            rows.append((line[:2], line[3:].strip().replace('\\\\','/')))
    return rows


def has_corruption_markers(txt):
    return any(m in txt for m in ["ï»¿", "â€", "â†", "ðŸ", "ï¿½", "`n", "# LUPOPEDIA HEADERS (replaces FLARE)"])


def header_shape_ok(txt):
    lines = txt.splitlines()
    if len(lines) < 3 or lines[0].strip() != "---":
        return False
    close_i = None
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            close_i = i
            break
    if close_i is None or close_i + 1 >= len(lines):
        return False
    return lines[close_i + 1].strip().startswith("# file:")


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
    out = []
    for f in fields:
        s = str(f).strip()
        m = re.match(r"`([^`]+)`\\s+(.+)$", s)
        if m:
            out.append((m.group(1), m.group(2)))
        else:
            p = s.split(None, 1)
            out.append((p[0].strip("`"), p[1] if len(p) > 1 else ""))
    return out


def parse_indexes(indexes):
    out = []
    for idx in indexes:
        if not isinstance(idx, dict):
            continue
        cols = idx.get("columns", [])
        if isinstance(cols, str):
            cols = [cols]
        out.append((idx.get("index_name", ""), cols, bool(idx.get("is_unique", False))))
    return out


def build_doc(table, rel, toon):
    ns = namespace_for_table(table)
    fields = parse_fields(toon.get("fields", []))
    indexes = parse_indexes(toon.get("indexes", []))
    pk = ""
    pko = toon.get("primary_key")
    if isinstance(pko, dict):
        pk = pko.get("column", "")
    elif isinstance(pko, str):
        pk = pko

    lines = [
        "---",
        "lupopedia.headers:",
        f'  when_updated: "{TS}"',
        f'  file_path_from_root: "{rel}"',
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
    ]
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
    return "\n".join(lines)


def schema_match(txt, toon):
    for f in toon.get("fields", []):
        m = re.match(r"`([^`]+)`\\s+(.+)$", str(f).strip())
        if not m:
            continue
        if f"`{m.group(1)}`" not in txt or f"`{m.group(2)}`" not in txt:
            return False
    for idx in toon.get("indexes", []):
        if isinstance(idx, dict):
            nm = idx.get("index_name", "")
            if nm and f"`{nm}`" not in txt:
                return False
    return True

entries = get_drift_entries()
stem_map = {}
for _, p in entries:
    stem_map.setdefault(Path(p).stem, []).append(p)
duplicates = {p for _, paths in stem_map.items() if len(paths) > 1 for p in paths}

summary = {"total":0,"valid":0,"corrupted":0,"outdated":0,"orphaned":0,"duplicate":0,
           "regen":0,"archived":0,"consolidated":0,"kept":0}
rows = []

for status, path in sorted(entries, key=lambda x: x[1]):
    summary["total"] += 1
    p = root / path
    table = Path(path).stem
    toon_path = json_dir / f"{table}.json"
    exists = p.exists()
    toon_exists = toon_path.exists()

    cls = "valid"
    reason = []
    if path in duplicates:
        cls = "duplicate"
        reason.append("duplicate stem in drift set")
    elif status.strip().startswith("D") or not exists:
        cls = "orphaned"
        reason.append("deleted in worktree")
    elif not toon_exists:
        cls = "orphaned"
        reason.append("no matching TOON JSON source")
    else:
        txt = p.read_text(encoding="utf-8", errors="replace")
        if has_corruption_markers(txt) or not header_shape_ok(txt):
            cls = "corrupted"
            reason.append("corruption/header-shape issue")
        else:
            toon = json.loads(toon_path.read_text(encoding="utf-8", errors="replace"))
            if not schema_match(txt, toon):
                cls = "outdated"
                reason.append("schema mismatch against TOON JSON")
            else:
                cls = "valid"
                reason.append("clean drift entry")

    summary[cls] += 1
    action = "kept_untouched"

    if cls in ("corrupted", "outdated"):
        toon = json.loads(toon_path.read_text(encoding="utf-8", errors="replace"))
        p.write_text(build_doc(table, path, toon), encoding="utf-8", newline="\n")
        action = "regenerated_from_toon_json"
        summary["regen"] += 1
    elif cls == "orphaned":
        target = archive_dir / Path(path).name
        if target.exists():
            target = archive_dir / f"{Path(path).stem}__{TS}.md"
        if p.exists():
            shutil.move(str(p), str(target))
            action = f"archived_move->{target.relative_to(root).as_posix()}"
        else:
            restored = ""
            try:
                restored = run(["git", "show", f"HEAD:{path}"])
            except Exception:
                restored = f"# archived placeholder\\n\\nNo recoverable HEAD content for {path}.\\n"
            target.write_text(restored, encoding="utf-8", newline="\n")
            action = f"archived_from_head->{target.relative_to(root).as_posix()}"
        summary["archived"] += 1
    elif cls == "duplicate":
        target = archive_dir / f"duplicate__{Path(path).name}"
        if target.exists():
            target = archive_dir / f"duplicate__{Path(path).stem}__{TS}.md"
        if p.exists():
            shutil.move(str(p), str(target))
            action = f"consolidated_archive->{target.relative_to(root).as_posix()}"
        else:
            action = "consolidated_noop_missing"
        summary["consolidated"] += 1
    else:
        summary["kept"] += 1

    rows.append((path, status, cls, "true" if toon_exists else "false", "true" if (root/path).exists() else "false", action, "; ".join(reason)))

lines = [
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
]
for r in rows:
    lines.append(f"| {r[0]} | {r[1]} | {r[2]} | {r[3]} | {r[4]} | {r[5]} | {r[6]} |")

report_path.write_text("\n".join(lines) + "\n", encoding="utf-8", newline="\n")
print("Track C completion pass done")
print(summary)
