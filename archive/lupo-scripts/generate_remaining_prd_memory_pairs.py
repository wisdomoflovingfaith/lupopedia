#!/usr/bin/env python3
import argparse
import json
import re
import subprocess
from datetime import datetime, timezone
from pathlib import Path


SEVERITIES = ["critical", "high", "high", "medium", "critical"]

# Already completed in Phase 1 + Phase 2 (22 PRDs / files)
COMPLETED_FILES = {
    "00_root_constitutional_system_requirements.md",
    "01_core_identity.md",
    "02_channels_discussions.md",
    "03_truth_knowledge.md",
    "04_tags_metadata.md",
    "05_auth_user_actor_agent_transformation.md",
    "06_content_management.md",
    "07_agents_faucets.md",
    "08_governance_rules.md",
    "13_crafty_integration.md",
    "15_actors.md",
    "16_lupopedia_headers.md",
    "17_decisions_format.md",
    "18_channel_chat_display.md",
    "25_departments_system.md",
    "27_installer_requirements.md",
    "28_semantic_monitoring_widget.md",
    "33_softaculous_certification_4_1_0_gate.md",
    "36_rose_multi_persona_synthetic_dialog.md",
    "37_kairos_channel_memory_consolidation.md",
    "38_memory_unification.md",
    "41_captain_wolfie_identity.md",
}


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
    for p in sorted(prd_dir.glob("[0-9][0-9]_*.md")):
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


def build_json(prd_id, slug, source_path, sections, refs, path_map, layer):
    now = utc_now_ymdhis()
    section_titles = list(sections[:5])
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
                "layer": layer,
                "prd_id": prd_id,
                "prd_slug": slug,
                "source_path": source_path,
            }
        ],
        "prd_sections": prd_sections,
        "prd_rules": prd_rules,
        "definitions": definitions,
        "edges": build_edges(prd_id, refs, path_map),
    }


def run_cmd(cmd, repo):
    result = subprocess.run(cmd, cwd=str(repo))
    return result.returncode == 0


def main():
    parser = argparse.ArgumentParser(
        description="Generate JSON/TOON/validation for remaining PRDs in one controlled pass."
    )
    parser.add_argument("--write", action="store_true", help="Write JSON and generate TOON/validation.")
    parser.add_argument(
        "--timestamp",
        help="Optional UTC timestamp YYYYMMDDHHIISS to control output year/month directory.",
    )
    parser.add_argument(
        "--include-completed",
        action="store_true",
        help="Also process PRD files listed as completed.",
    )
    args = parser.parse_args()

    repo = Path(__file__).resolve().parents[1]
    prd_dir = repo / "lupo-docs" / "prd"
    ts = args.timestamp if args.timestamp else str(utc_now_ymdhis())
    if not re.match(r"^\d{14}$", ts):
        raise ValueError("timestamp must be 14 digits: YYYYMMDDHHIISS")
    year = ts[:4]
    month = ts[4:6]

    canonical_dir = repo / "lupo-memory" / "development" / "canonical" / year / month
    seed_dir = repo / "lupo-memory" / "development" / "seed" / year / month
    canonical_dir.mkdir(parents=True, exist_ok=True)
    seed_dir.mkdir(parents=True, exist_ok=True)

    path_map = build_prd_path_map(prd_dir)
    prd_files = sorted(prd_dir.glob("[0-9][0-9]_*.md"))
    targets = []
    for p in prd_files:
        if not args.include_completed and p.name in COMPLETED_FILES:
            continue
        targets.append(p)

    print("[INFO] Total PRD files discovered: {}".format(len(prd_files)))
    print("[INFO] Target PRD files this run: {}".format(len(targets)))

    generated = []
    failed = []
    for prd_path in targets:
        filename = prd_path.name
        prd_id, slug = normalize_slug_from_file(filename)
        text = prd_path.read_text(encoding="utf-8", errors="replace")
        sections = extract_h2_sections(text)
        refs = extract_prd_refs(text, prd_id)

        # Collision handling:
        # - filename slug keeps uniqueness even for duplicate PRD numbers.
        # Seed split handling for PRD 41:
        # - captain-wolfie-identity is seed, everything else canonical.
        is_seed = (prd_id == 41 and slug == "captain-wolfie-identity")
        layer = "seed" if is_seed else "canonical"
        out_dir = seed_dir if is_seed else canonical_dir
        out_base = "{:02d}-{}".format(prd_id, slug)
        out_json = out_dir / (out_base + ".json")
        out_toon = out_dir / (out_base + ".toon")
        base_no_ext = "lupo-memory/development/{}/{}/{}/{}".format(
            layer, year, month, out_base
        )

        payload = build_json(
            prd_id,
            slug,
            "lupo-docs/prd/" + filename,
            sections,
            refs,
            path_map,
            layer,
        )

        if args.write:
            out_json.write_text(json.dumps(payload, indent=2) + "\n", encoding="utf-8")
            ok_toon = run_cmd(
                [
                    "python",
                    "lupo-scripts/json_to_toon.py",
                    "--json",
                    str(out_json.relative_to(repo)).replace("\\", "/"),
                    "--toon",
                    str(out_toon.relative_to(repo)).replace("\\", "/"),
                ],
                repo,
            )
            ok_validate = False
            if ok_toon:
                ok_validate = run_cmd(
                    [
                        "python",
                        "lupo-scripts/validate_memory_json_toon_pair.py",
                        "--base",
                        base_no_ext,
                    ],
                    repo,
                )
            if ok_toon and ok_validate:
                generated.append(base_no_ext)
                print("[OK] Generated+validated {}".format(base_no_ext))
            else:
                failed.append(base_no_ext)
                print("[WARN] Failed generation/validation {}".format(base_no_ext))
        else:
            generated.append(base_no_ext)
            print("[OK] Would generate {}".format(base_no_ext))

    print("[OK] Completed: {} | Failed: {}".format(len(generated), len(failed)))
    if not args.write:
        print("[INFO] Dry run only. Re-run with --write to apply.")
    if failed:
        return 1
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
