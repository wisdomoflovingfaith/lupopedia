#!/usr/bin/env python3
import argparse
import json
import re
from datetime import datetime, timezone
from pathlib import Path


PHASE2_FILES = [
    "71_truth_knowledge.md",
    "72_tags_metadata.md",
    "06_content_management.md",
    "74_governance_rules.md",
    "13_crafty_integration.md",
    "17_decisions_format.md",
    "18_channel_chat_display.md",
    "25_departments_system.md",
    "27_installer_requirements.md",
    "28_semantic_monitoring_widget.md",
]

SEVERITIES = ["critical", "high", "high", "medium", "critical"]


def utc_now_ymdhis():
    return int(datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S"))


def normalize_slug_from_file(filename):
    stem = filename[:-3]
    m = re.match(r"^(\d+)_(.+)$", stem)
    if not m:
        raise ValueError("Unexpected PRD filename format: " + filename)
    prd_id = int(m.group(1))
    slug = m.group(2).replace("_", "-")
    return prd_id, slug


def strip_frontmatter(text):
    if text.startswith("---"):
        parts = text.split("---", 2)
        if len(parts) >= 3:
            return parts[2]
    return text


def extract_h2_sections(text):
    body = strip_frontmatter(text)
    sections = []
    for line in body.splitlines():
        m = re.match(r"^##\s+(.+?)\s*$", line)
        if m:
            title = m.group(1).strip()
            if title:
                sections.append(title)
    return sections


def extract_prd_refs(text, self_prd_id):
    refs = set()
    for m in re.finditer(r"\bPRD\s*0*([0-9]{1,2})\b", text, flags=re.IGNORECASE):
        refs.add(int(m.group(1)))
    for m in re.finditer(r"/([0-9]{2})_[a-z0-9_]+\.md", text, flags=re.IGNORECASE):
        refs.add(int(m.group(1)))
    refs.discard(self_prd_id)
    return sorted(refs)


def build_prd_path_map(prd_dir):
    path_map = {}
    for p in prd_dir.glob("[0-9][0-9]_*.md"):
        name = p.name
        m = re.match(r"^([0-9]{2})_", name)
        if not m:
            continue
        key = int(m.group(1))
        rel = "lupo-docs/prd/" + name
        if key not in path_map:
            path_map[key] = rel
    path_map[0] = "lupo-docs/prd/00_root_constitutional_system_requirements.md"
    path_map[1] = "lupo-docs/prd/01_core_identity.md"
    path_map[38] = "lupo-docs/prd/38_memory_unification.md"
    return path_map


def build_edges(prd_id, ref_ids, path_map):
    ranked = []
    # Always include constitutional/core/memory doctrine anchors first.
    for forced in [0, 1, 38]:
        if forced != prd_id and forced not in ranked:
            ranked.append(forced)
    for rid in ref_ids:
        if rid not in ranked:
            ranked.append(rid)
    ranked = ranked[:5]

    edges = []
    for i, rid in enumerate(ranked, start=1):
        edge_type = "references"
        if rid in [1, 38]:
            edge_type = "depends_on"
        if rid not in path_map:
            print("[WARN] PRD {:02d} references unknown PRD {:02d}".format(prd_id, rid))
        target = path_map.get(rid, "lupo-docs/prd/{:02d}_unknown.md".format(rid))
        weight = max(80, 100 - (i - 1) * 5)
        edges.append(
            {
                "pk": i,
                "edge_type": edge_type,
                "edge_context": "doctrine",
                "edge_status": "supported",
                "edge_direction": "outbound",
                "to": target,
                "weight_hundredths": weight,
            }
        )
    return edges


def build_json(prd_id, slug, source_path, title_sections, ref_ids, path_map):
    now = utc_now_ymdhis()
    section_titles = list(title_sections[:5])
    while len(section_titles) < 5:
        section_titles.append("Section {}".format(len(section_titles) + 1))

    prd_sections = []
    for i, title in enumerate(section_titles, start=1):
        prd_sections.append(
            {
                "pk": i,
                "title": title,
                "summary": "Operational summary for {} section {}.".format(slug, i),
            }
        )

    prd_rules = []
    for i in range(1, 6):
        prd_rules.append(
            {
                "pk": i,
                "section_pk": i,
                "rule_name": "prd_{:02d}_rule_{}".format(prd_id, i),
                "severity": SEVERITIES[i - 1],
                "summary": "Rule {} for PRD {:02d} ({})".format(i, prd_id, slug),
            }
        )

    definitions = [
        {"pk": 1, "term": "scope", "meaning": "Scope boundary for PRD {:02d}.".format(prd_id)},
        {"pk": 2, "term": "actor_id", "meaning": "Canonical actor identity reference key."},
        {"pk": 3, "term": "channel_key", "meaning": "Channel namespace identifier used for routing."},
        {"pk": 4, "term": "timestamp_ymdhis", "meaning": "Packed UTC BIGINT in YYYYMMDDHHIISS format."},
        {"pk": 5, "term": "memory_pair", "meaning": "JSON master with deterministically derived TOON."},
    ]

    return {
        "metadata": [
            {
                "pk": 1,
                "created_ymdhis": now,
                "last_updated": now,
                "layer": "canonical",
                "prd_id": prd_id,
                "prd_slug": slug,
                "source_path": source_path,
            }
        ],
        "prd_sections": prd_sections,
        "prd_rules": prd_rules,
        "definitions": definitions,
        "edges": build_edges(prd_id, ref_ids, path_map),
    }


def main():
    parser = argparse.ArgumentParser(description="Generate Phase-2 PRD memory JSON masters.")
    parser.add_argument("--write", action="store_true", help="Write JSON files to canonical memory path.")
    parser.add_argument(
        "--timestamp",
        help="Optional UTC timestamp YYYYMMDDHHIISS to control output year/month directory.",
    )
    args = parser.parse_args()

    repo = Path(__file__).resolve().parents[1]
    prd_dir = repo / "lupo-docs" / "prd"
    ts = args.timestamp if args.timestamp else str(utc_now_ymdhis())
    if not re.match(r"^\d{14}$", ts):
        raise ValueError("timestamp must be 14 digits: YYYYMMDDHHIISS")
    year = ts[:4]
    month = ts[4:6]
    out_dir = repo / "lupo-memory" / "development" / "canonical" / year / month
    out_dir.mkdir(parents=True, exist_ok=True)

    path_map = build_prd_path_map(prd_dir)
    written = []
    for filename in PHASE2_FILES:
        prd_path = prd_dir / filename
        if not prd_path.exists():
            raise FileNotFoundError("Missing PRD file: " + str(prd_path))

        prd_id, slug = normalize_slug_from_file(filename)
        text = prd_path.read_text(encoding="utf-8", errors="replace")
        sections = extract_h2_sections(text)
        refs = extract_prd_refs(text, prd_id)
        payload = build_json(prd_id, slug, "lupo-docs/prd/" + filename, sections, refs, path_map)
        out_path = out_dir / ("{:02d}-{}.json".format(prd_id, slug))

        if args.write:
            out_path.write_text(json.dumps(payload, indent=2) + "\n", encoding="utf-8")
        written.append(str(out_path.relative_to(repo)))

    for item in written:
        print("[OK] Prepared {}".format(item))
    if not args.write:
        print("[INFO] Dry run only. Re-run with --write to save files.")


if __name__ == "__main__":
    main()
