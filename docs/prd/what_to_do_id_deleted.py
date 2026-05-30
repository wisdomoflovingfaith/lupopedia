#!/usr/bin/env python3
"""
Lupopedia 4.1.6 — Roman Numeral Continuity & Migration Helper
Detects gaps, duplicates, and suggests safe cleanup actions.
Never auto-deletes or renumbers. Human-supervised only.
"""

from lupopedia_4_1_6_prd_cluster_parser import parse_prd_cluster, validate_continuity_and_immutability

from pathlib import Path

def resolve_prd_file(cluster_tokens, prd_dir="docs/prd", backup_dir="docs/prd/prd_backup_1777054781"):
    """
    Given a list of cluster tokens, resolve the PRD file location according to 4.1.6 migration rules.
    Uses glob prefix matching for legacy and new clusters.
    Returns (file_path, found_in_backup, legacy_mode)
    """
    resolved_files = []
    prd_root = Path(prd_dir)
    backup_root = Path(backup_dir)

    for i, token in enumerate(cluster_tokens):
        if i % 2 == 0:
            continue

        number_token = cluster_tokens[i - 1]
        prefix = f"{number_token}_{token}_"

        if "-" not in token:
            legacy_mode = True

            matches = sorted(backup_root.glob(f"{prefix}*.md"))
            if matches:
                resolved_files.append((str(matches[0]), True, legacy_mode))
                continue

            matches = sorted(prd_root.glob(f"{prefix}*.md"))
            if matches:
                resolved_files.append((str(matches[0]), False, legacy_mode))
            else:
                resolved_files.append((None, False, legacy_mode))
        else:
            legacy_mode = False
            matches = sorted(prd_root.glob(f"{prefix}*.md"))
            if matches:
                resolved_files.append((str(matches[0]), False, legacy_mode))
            else:
                resolved_files.append((None, False, legacy_mode))

    return resolved_files


def suggest_migration_actions(cluster: str) -> None:
    print("=== Lupopedia 4.1.6 Continuity Report ===\n")
    result = validate_continuity_and_immutability(cluster)

    if result["gaps_detected"]:
        print("[!] GAPS DETECTED (historical deletions):")
        for gap in result["gaps_detected"]:
            print(f"   - {gap}")
        print("   → Treat as deleted historical entries. Do not renumber.\n")

    if result["duplicates_detected"]:
        print("[!] DUPLICATES DETECTED:")
        for dup in result["duplicates_detected"]:
            print(f"   - {dup}")
        print("   → Read all duplicates. Suggest: 'Copy (not move) to canonical file for later cleanup.'\n")

    if not result["gaps_detected"] and not result["duplicates_detected"]:
        print("[OK] No gaps or duplicates detected. Cluster is clean.\n")

    print("RECOMMENDATION (4.1.6 doctrine):")
    print(result["recommendation"])
    print("\nNever auto-delete, auto-merge, or auto-renumber. Human review required.")

    # --- Legacy fallback demo ---
    print("\n--- PRD Cluster File Resolution Demo ---")
    # Example 1: legacy cluster
    legacy_cluster = "00_A_16_C"
    legacy_tokens = legacy_cluster.split("_")
    legacy_files = resolve_prd_file(legacy_tokens)
    print(f"Legacy cluster: {legacy_cluster}")
    for idx, (path, found_in_backup, legacy_mode) in enumerate(legacy_files):
        print(f"  Token {idx*2+1}: {legacy_tokens[idx*2]}_{legacy_tokens[idx*2+1]} -> ", end="")
        if path:
            where = 'backup' if found_in_backup else 'current docs/prd'
            print(f"FOUND in {where} ({'legacy' if legacy_mode else 'new'}) at {path}")
        else:
            print("NOT FOUND")

    # Example 2: new cluster
    new_cluster = "00_A-i_16_C-i"
    new_tokens = new_cluster.split("_")
    new_files = resolve_prd_file(new_tokens)
    print(f"\nNew cluster: {new_cluster}")
    for idx, (path, found_in_backup, legacy_mode) in enumerate(new_files):
        print(f"  Token {idx*2+1}: {new_tokens[idx*2]}_{new_tokens[idx*2+1]} -> ", end="")
        if path:
            where = 'backup' if found_in_backup else 'current docs/prd'
            print(f"FOUND in {where} ({'legacy' if legacy_mode else 'new'}) at {path}")
        else:
            print("NOT FOUND")


if __name__ == "__main__":
    # Example cluster with intentional gaps and duplicates for testing
    test_cluster = "00_A-i_00_A-i_00_A-iii_16_B-ii_16_B-ii_57_C-i"
    suggest_migration_actions(test_cluster)