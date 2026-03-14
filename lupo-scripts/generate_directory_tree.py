#!/usr/bin/env python3
"""
Generate DIRECTORY_TREE.md at project root from the entire project tree.
Uses only Python standard library. Overwrites output each run.
Excludes: .git/, vendor/, node_modules/, .idea/, .vscode/, .DS_Store
"""
import os

# Names to exclude from the tree (dirs not descended, files/dirs not listed)
EXCLUDE = frozenset({".git", "vendor", "node_modules", ".idea", ".vscode", ".DS_Store"})


def build_tree(dirpath: str, prefix: str = "") -> list[str]:
    """Build tree lines for a directory. Sorts dirs then files, each by name. Skips EXCLUDE."""
    try:
        entries = os.listdir(dirpath)
    except OSError:
        return []
    filtered = [e for e in entries if e not in EXCLUDE]
    dirs = sorted(
        [e for e in filtered if os.path.isdir(os.path.join(dirpath, e))],
        key=str.lower,
    )
    files = sorted(
        [e for e in filtered if os.path.isfile(os.path.join(dirpath, e))],
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
    root_name = os.path.basename(project_root)
    lines = [root_name + "/"] + build_tree(project_root, "")
    out_path = os.path.join(project_root, "DIRECTORY_TREE.md")
    with open(out_path, "w", encoding="utf-8") as f:
        f.write("\n".join(lines) + "\n")
    print(f"Wrote {out_path}")


if __name__ == "__main__":
    main()
