import subprocess, json, re
from pathlib import Path

root = Path(r"c:/ServBay/www/servbay/lupopedia")
active = root / "lupo-docs/database/lupopedia/tables/active"
json_dir = root / "lupo-database/lupopedia/json"
validator = root / "lupo-scripts/validate_lupopedia_headers.php"
report = root / "lupo-docs/reports/STAGE3_VALIDATION_REPORT.md"
TS = "20260328014500"
files = sorted(active.glob("*.md"))

header_fail=[]; schema_fail=[]; encoding_fail=[]; structural_fail=[]; edge_fail=[]


def run(cmd):
    p = subprocess.run(cmd, cwd=str(root), capture_output=True, text=True)
    return p.returncode, p.stdout, p.stderr

for f in files:
    rel = f.relative_to(root).as_posix()
    txt = f.read_text(encoding="utf-8", errors="replace")

    rc, _o, e = run(["php", str(validator), rel])
    if rc != 0:
        header_fail.append((rel, e.strip() or "header validator failed"))

    lines = txt.splitlines()
    if len(lines) < 3 or lines[0].strip() != "---":
        structural_fail.append((rel, "first line not ---"))
    else:
        close_i = next((i for i in range(1, len(lines)) if lines[i].strip()=="---"), None)
        if close_i is None:
            structural_fail.append((rel, "missing closing ---"))
        elif close_i + 1 >= len(lines) or not lines[close_i+1].strip().startswith("# file:"):
            structural_fail.append((rel, "missing identity line after header"))

    # encoding gate: BOM + mojibake + literal backtick-n header/footer splice patterns
    encoding_hits = []
    if "\ufeff" in txt or txt.startswith("ï»¿"):
        encoding_hits.append("bom")
    for t in ["â€", "â†", "ðŸ", "ï¿½"]:
        if t in txt:
            encoding_hits.append(t)
    if re.search(r"`n\s*(lupopedia\.|---)", txt):
        encoding_hits.append("literal_backtick_n_artifact")
    if encoding_hits:
        encoding_fail.append((rel, "contains: " + ", ".join(sorted(set(encoding_hits)))))

    if "lupopedia.edges:" not in txt:
        edge_fail.append((rel, "missing lupopedia.edges block"))
    elif "outbound_edges:" not in txt:
        edge_fail.append((rel, "missing outbound_edges"))
    elif not re.search(r"^\s*-\s+to:\s+", txt, flags=re.M):
        edge_fail.append((rel, "no outbound edge entries"))

    table = f.stem
    toon_path = json_dir / f"{table}.json"
    if not toon_path.exists():
        schema_fail.append((rel, f"missing TOON JSON source: {toon_path.relative_to(root).as_posix()}"))
    else:
        toon = json.loads(toon_path.read_text(encoding="utf-8", errors="replace"))
        miss=[]
        for field in toon.get("fields", []):
            m = re.match(r"`([^`]+)`\s+(.+)$", str(field).strip())
            if not m:
                continue
            col, ctype = m.group(1), m.group(2)
            if f"`{col}`" not in txt or f"`{ctype}`" not in txt:
                miss.append(f"field:{col}")
        for idx in toon.get("indexes", []):
            if isinstance(idx, dict):
                nm = idx.get("index_name", "")
                if nm and f"`{nm}`" not in txt:
                    miss.append(f"index:{nm}")
        if miss:
            schema_fail.append((rel, ", ".join(miss[:10])))

all_ok = not any([header_fail, schema_fail, encoding_fail, structural_fail, edge_fail])

def sec(title, rows):
    if not rows:
        return ["", f"## {title}", "- none"]
    out=["", f"## {title}"]
    out.extend([f"- {r}: {m}" for r,m in rows])
    return out

lines=[
"---",
"lupopedia.headers:",
f'  when_updated: "{TS}"',
'  file_path_from_root: "lupo-docs/reports/STAGE3_VALIDATION_REPORT.md"',
f'  last_modified_utc: "{TS}"',
"  channel_id: 42",
"  actor_id: 11",
'  actor_name: "thoth"',
'  artifact_type: "report"',
'  artifact_kind: "validation"',
"lupopedia.footer:",
f'  last_verified: "{TS}"',
'  last_verified_by: "thoth"',
"  last_verified_by_actor_id: 11",
"---",
"# file: STAGE3_VALIDATION_REPORT.md",
"",
"# STAGE3 Validation Report",
"",
"## Scope",
f"- Active table docs validated: {len(files)}",
"",
"## Gate Results",
f"- Header validation: {'PASS' if not header_fail else 'FAIL'} ({len(files)-len(header_fail)}/{len(files)} passing)",
f"- Schema validation: {'PASS' if not schema_fail else 'FAIL'} ({len(files)-len(schema_fail)}/{len(files)} passing)",
f"- Encoding validation: {'PASS' if not encoding_fail else 'FAIL'} ({len(files)-len(encoding_fail)}/{len(files)} passing)",
f"- Structural validation: {'PASS' if not structural_fail else 'FAIL'} ({len(files)-len(structural_fail)}/{len(files)} passing)",
f"- Edge baseline validation: {'PASS' if not edge_fail else 'FAIL'} ({len(files)-len(edge_fail)}/{len(files)} passing)",
"",
"## Final System Status",
f"- Stage 3 validation status: {'PASS' if all_ok else 'FAIL'}",
"- Notes: placeholder edges are accepted for baseline structural validity; semantic enrichment remains optional follow-up.",
]
lines.extend(sec("Header Anomalies", header_fail))
lines.extend(sec("Schema Anomalies", schema_fail))
lines.extend(sec("Encoding Anomalies", encoding_fail))
lines.extend(sec("Structural Anomalies", structural_fail))
lines.extend(sec("Edge Anomalies", edge_fail))
report.write_text("\n".join(lines)+"\n", encoding="utf-8", newline="\n")
print({"files":len(files),"header_fail":len(header_fail),"schema_fail":len(schema_fail),"encoding_fail":len(encoding_fail),"structural_fail":len(structural_fail),"edge_fail":len(edge_fail),"all_ok":all_ok})
