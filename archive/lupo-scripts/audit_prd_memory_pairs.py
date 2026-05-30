#!/usr/bin/env python3
import argparse
import json
import re
import subprocess
from pathlib import Path


def checkmark(value):
    return "[OK]" if value else "[FAIL]"


def discover_sidecar(repo, stem):
    candidates = sorted((repo / "lupo-memory" / "headers" / "prd").glob(f"**/{stem}.metadata.json"))
    if not candidates:
        return None
    return candidates[-1]


def edge_count(sidecar_path):
    if sidecar_path is None or not sidecar_path.exists():
        return 0
    try:
        payload = json.loads(sidecar_path.read_text(encoding="utf-8"))
        edges = payload.get("edges", [])
        if isinstance(edges, list):
            return len(edges)
    except Exception:
        return 0
    return 0


def validate_pair(repo, toon_path):
    base = str(toon_path.with_suffix("")).replace("\\", "/")
    result = subprocess.run(
        ["python", "lupo-scripts/validate_memory_json_toon_pair.py", "--base", base],
        cwd=str(repo),
        capture_output=True,
        text=True,
    )
    return result.returncode == 0


def resolve_toon_path(repo, prd_file):
    stem = prd_file.stem
    slug = stem.replace("_", "-")
    prd_num = stem.split("_", 1)[0]

    # Canonical constitutional special case.
    if prd_file.name == "00_root_constitutional_system_requirements.md":
        return repo / "lupo-memory/constitutional/seed/prd-00-constitutional.toon"

    # PRD 41 split: captain identity is seed path, install-seed doctrine is canonical.
    if prd_file.name == "41_captain_wolfie_identity.md":
        matches = sorted((repo / "lupo-memory/development/seed").glob("**/41-captain-wolfie-identity.toon"))
        if matches:
            return matches[-1]
        return repo / "lupo-memory/development/seed/2026/04/41-captain-wolfie-identity.toon"

    # All other PRDs follow canonical NN-slug paths.
    matches = sorted((repo / "lupo-memory/development/canonical").glob(f"**/{slug}.toon"))
    if matches:
        return matches[-1]
    # Fallback for historical slug variants (e.g. shortened slugs).
    prefix_matches = sorted((repo / "lupo-memory/development/canonical").glob(f"**/{prd_num}-*.toon"))
    if len(prefix_matches) == 1:
        return prefix_matches[0]
    return repo / f"lupo-memory/development/canonical/2026/04/{slug}.toon"


def main():
    parser = argparse.ArgumentParser(
        description="Compact audit for PRD memory pairs (JSON/TOON/sidecar/validation/edges)."
    )
    parser.add_argument(
        "--no-validate",
        action="store_true",
        help="Skip per-pair validator runs and only check file presence.",
    )
    parser.add_argument(
        "--out",
        help="Optional output file path for markdown table/report.",
    )
    args = parser.parse_args()

    repo = Path(__file__).resolve().parents[1]
    prd_files = sorted((repo / "lupo-docs" / "prd").glob("[0-9][0-9]_*.md"))

    lines = []
    lines.append("| PRD | File | Slug | JSON | TOON | Sidecar | Valid | Edges |")
    lines.append("|-----|------|------|------|------|---------|-------|-------|")

    total = 0
    pass_count = 0
    fail_count = 0

    for prd_path in prd_files:
        total += 1
        file_name = prd_path.name
        stem = prd_path.stem
        prd_num = stem.split("_", 1)[0]

        toon_path = resolve_toon_path(repo, prd_path)
        json_path = toon_path.with_suffix(".json")
        slug = toon_path.stem

        sidecar_path = discover_sidecar(repo, stem)
        sidecar_exists = sidecar_path is not None and sidecar_path.exists()
        edges = edge_count(sidecar_path)

        json_exists = json_path.exists()
        toon_exists = toon_path.exists()

        valid = False
        if json_exists and toon_exists:
            if args.no_validate:
                valid = True
            else:
                valid = validate_pair(repo, toon_path)

        if valid:
            pass_count += 1
        else:
            fail_count += 1

        lines.append(
            f"| {prd_num} | `{file_name}` | `{slug}` | {checkmark(json_exists)} | {checkmark(toon_exists)} | {checkmark(sidecar_exists)} | {checkmark(valid)} | {edges} |"
        )

    lines.append("")
    lines.append(f"| Metric | Value |")
    lines.append(f"|--------|-------|")
    lines.append(f"| Total PRDs | {total} |")
    lines.append(f"| Validation passes | {pass_count} |")
    lines.append(f"| Failures | {fail_count} |")

    report = "\n".join(lines) + "\n"
    print(report)

    if args.out:
        out_path = Path(args.out)
        if not out_path.is_absolute():
            out_path = repo / out_path
        out_path.parent.mkdir(parents=True, exist_ok=True)
        out_path.write_text(report, encoding="utf-8")
        print(f"[OK] Wrote audit report: {out_path}")

    return 0 if fail_count == 0 else 1


if __name__ == "__main__":
    raise SystemExit(main())
