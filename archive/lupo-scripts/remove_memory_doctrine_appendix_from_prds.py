import argparse
import glob
import os
import re


PATTERN = re.compile(
    r"\n##\s+Context.?Typed, Status.?Aware, Directional Edged Memory Doctrine(?:\s+\([0-9.]+\))?\n.*?(?=\n##\s+|\Z)",
    re.S,
)


def main():
    parser = argparse.ArgumentParser(
        description="Remove memory doctrine appendix from PRDs (excluding PRD 38)."
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Show files that would be modified without writing changes.",
    )
    parser.add_argument(
        "--verbose",
        action="store_true",
        help="Print each file changed (or that would change in dry-run).",
    )
    args = parser.parse_args()

    files = sorted(glob.glob("lupo-docs/prd/[0-9][0-9]_*.md"))
    changed = 0
    for path in files:
        if os.path.basename(path) == "38_memory_unification.md":
            continue
        with open(path, "r", encoding="utf-8", errors="replace") as handle:
            content = handle.read()
        new_content, count = PATTERN.subn("\n", content)
        if count > 0:
            if args.verbose:
                prefix = "[DRY RUN] Would remove appendix from" if args.dry_run else "[REMOVED]"
                print(f"{prefix} {path}")
            if not args.dry_run:
                with open(path, "w", encoding="utf-8") as handle:
                    handle.write(new_content)
            changed += 1

    if args.dry_run:
        print(f"[OK] Dry run complete; {changed} PRD files would be changed")
    else:
        print(f"[OK] Removed memory doctrine appendix from {changed} PRD files")


if __name__ == "__main__":
    main()
