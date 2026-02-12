#!/usr/bin/env python3
"""
Generate DIRECTORY_TREE.md at project root from scripts/ directory.
Uses only Python standard library. Overwrites output each run.
"""
import os


def build_tree(dirpath: str, prefix: str = "") -> list[str]:
    """Build tree lines for a directory. Sorts dirs then files, each by name."""
    try:
        entries = os.listdir(dirpath)
    except OSError:
        return []
    dirs = sorted(
        [e for e in entries if os.path.isdir(os.path.join(dirpath, e))],
        key=str.lower,
    )
    files = sorted(
        [e for e in entries if os.path.isfile(os.path.join(dirpath, e))],
        key=str.lower,
    )
    all_entries = [(e, True) for e in dirs] + [(e, False) for e in files]
    lines = []
    for i, (name, is_dir) in enumerate(all_entries):
        is_last = i == len(all_entries) - 1
        connector = "└── " if is_last else "├── "
        lines.append(prefix + connector + name + ("/" if is_dir else ""))
        if is_dir:
            subpath = os.path.join(dirpath, name)
            extension = "    " if is_last else "│   "
            lines.extend(build_tree(subpath, prefix + extension))
    return lines


def main() -> None:
    script_dir = os.path.abspath(os.path.dirname(__file__))
    project_root = os.path.abspath(os.path.join(script_dir, ".."))
    lines = ["scripts/"] + build_tree(script_dir, "")
    out_path = os.path.join(project_root, "DIRECTORY_TREE.md")
    with open(out_path, "w", encoding="utf-8") as f:
        f.write("\n".join(lines) + "\n")
    print(f"Wrote {out_path}")


if __name__ == "__main__":
    main()
