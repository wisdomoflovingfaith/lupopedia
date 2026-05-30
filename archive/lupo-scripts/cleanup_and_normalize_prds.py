import argparse
import os
import subprocess
import sys

CLEANUP_SCRIPT = "lupo-scripts/remove_memory_doctrine_appendix_from_prds.py"
NORMALIZE_SCRIPT = "lupo-scripts/normalize_prd_headers_4098.py"


def run_step(command):
    result = subprocess.run(command)
    return result.returncode


def main():
    parser = argparse.ArgumentParser(
        description="Run PRD cleanup (appendix removal) then header normalization."
    )
    parser.add_argument("timestamp", help="UTC timestamp YYYYMMDDHHIISS")
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Preview both cleanup and normalization without writing files.",
    )
    parser.add_argument(
        "--verbose",
        action="store_true",
        help="Verbose output for cleanup step.",
    )
    parser.add_argument(
        "--force",
        action="store_true",
        help="Force header rewrites even if already v4.0.98.",
    )
    parser.add_argument(
        "--write-sidecars",
        action="store_true",
        help="Generate/update PRD sidecar metadata during normalization.",
    )
    args = parser.parse_args()

    if not os.path.exists(CLEANUP_SCRIPT):
        print(f"[ERROR] Cleanup script not found: {CLEANUP_SCRIPT}")
        return 1
    if not os.path.exists(NORMALIZE_SCRIPT):
        print(f"[ERROR] Normalize script not found: {NORMALIZE_SCRIPT}")
        return 1

    cleanup_cmd = ["python", CLEANUP_SCRIPT]
    if args.dry_run:
        cleanup_cmd.append("--dry-run")
    if args.verbose:
        cleanup_cmd.append("--verbose")

    normalize_cmd = ["python", NORMALIZE_SCRIPT, args.timestamp]
    if args.dry_run:
        normalize_cmd.append("--dry-run")
    if args.force:
        normalize_cmd.append("--force")
    if args.write_sidecars:
        normalize_cmd.append("--write-sidecars")

    print("[STEP] Cleanup memory doctrine appendixes")
    code = run_step(cleanup_cmd)
    if code != 0:
        return code

    print("[STEP] Normalize PRD headers")
    code = run_step(normalize_cmd)
    if code != 0:
        return code

    print("[OK] PRD cleanup + normalization completed")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
