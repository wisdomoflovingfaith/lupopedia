import re
from pathlib import Path

ROOT = Path(r"C:/ServBay/www/servbay/lupopedia")
manifest = ROOT / "lupo-docs/reports/PHASE2_REGENERATION_MANIFEST_20260327.md"

text = manifest.read_text(encoding="utf-8", errors="replace").splitlines()
paths = []
for ln in text:
    m = re.match(r"^\|\s*(lupo-docs/database/lupopedia/tables/active/[^|]+)\s*\|\s*(restored-header|synthetic-header)\s*\|", ln)
    if m:
        paths.append(ROOT / m.group(1).strip())


def edge_policy(edge_type: str):
    et = (edge_type or "").strip()
    if et.startswith("USED_IN_"):
        return "0.7", "code-scan"
    if et in ("DEFINES_SCHEMA_FOR", "schema_reference", "references"):
        return "1.0", "git-restored"
    return "0.7", "code-scan"


def process_file(p: Path):
    raw = p.read_text(encoding="utf-8", errors="replace")
    lines = raw.splitlines()
    if len(lines) < 3 or lines[0].strip() != "---":
        return False, 0

    end = None
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            end = i
            break
    if end is None:
        return False, 0

    fm_lines = lines[1:end]
    body = "\n".join(lines[end + 1 :])

    changed = False

    # Ensure comment in edges includes snapshot/static and rubric note.
    for i, ln in enumerate(fm_lines):
        if re.match(r"^\s*comment\s*:", ln) and i > 0 and "lupopedia.edges:" in fm_lines[i - 1]:
            desired = '  comment: "Snapshot stage1 confidence-scored edges (git=1.0, code-scan=0.7, db=0.5)."'
            if ln != desired:
                fm_lines[i] = desired
                changed = True
            break

    # Locate outbound_edges block
    idx = None
    for i, ln in enumerate(fm_lines):
        if re.match(r"^\s*outbound_edges\s*:\s*$", ln):
            idx = i
            break
    if idx is None:
        return changed, 0

    i = idx + 1
    edge_count = 0
    while i < len(fm_lines):
        ln = fm_lines[i]
        # end when next top-level section starts
        if re.match(r"^[A-Za-z0-9_.-]+\s*:\s*$", ln):
            break

        if re.match(r"^\s*-\s+to\s*:", ln):
            edge_count += 1
            start = i
            j = i + 1
            while j < len(fm_lines):
                n = fm_lines[j]
                if re.match(r"^[A-Za-z0-9_.-]+\s*:\s*$", n):
                    break
                if re.match(r"^\s*-\s+to\s*:", n):
                    break
                j += 1

            block = fm_lines[start:j]
            edge_type = ""
            has_conf = False
            has_source = False
            weight_idx = -1
            insert_idx = len(block)
            for k, b in enumerate(block):
                m_type = re.match(r"^\s*type\s*:\s*(.+)$", b)
                if m_type:
                    edge_type = m_type.group(1).strip().strip('"\'')
                if re.match(r"^\s*weight\s*:\s*", b):
                    weight_idx = k
                    insert_idx = k + 1
                if re.match(r"^\s*confidence\s*:\s*", b):
                    has_conf = True
                if re.match(r"^\s*source\s*:\s*", b):
                    has_source = True

            conf, source = edge_policy(edge_type)
            inserts = []
            if not has_conf:
                inserts.append(f"    confidence: {conf}")
            if not has_source:
                inserts.append(f"    source: \"{source}\"")

            if inserts:
                block = block[:insert_idx] + inserts + block[insert_idx:]
                fm_lines[start:j] = block
                delta = len(inserts)
                j += delta
                changed = True

            i = j
            continue

        i += 1

    if changed:
        out = "---\n" + "\n".join(fm_lines) + "\n---\n" + body
        p.write_text(out, encoding="utf-8", newline="\n")

    return changed, edge_count


changed_files = 0
edge_total = 0
for p in paths:
    ok, cnt = process_file(p)
    if ok:
        changed_files += 1
    edge_total += cnt

print(f"files_targeted={len(paths)}")
print(f"files_changed={changed_files}")
print(f"edges_seen={edge_total}")
