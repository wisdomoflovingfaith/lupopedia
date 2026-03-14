#!/usr/bin/env python3
"""
One-time script: replace 4.0.N -> 3.0.N and 4_0_N -> 3_0_N (and v4_0_N -> v3_0_N)
in text files. Excludes: 4.0.1 (canonical), 4.1.0 (future), and date-like patterns.
"""
import os
import re

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
EXCLUDE_DIRS = {".git", "vendor", "node_modules", ".idea", ".vscode", "backups"}
EXCLUDE_FILES = {"normalize_version_4_0_x_to_3_0_x.py"}

# Match historical version pattern where N is any number except 1 (N = 0, 2, 3, ...)
RE_4_0_DOT = re.compile(r"4\.0\.(0|[2-9]|[1-9][0-9]+)")
# Match 4_0_N (underscore form), N != 1
RE_4_0_UNDER = re.compile(r"4_0_(0|[2-9]|[1-9][0-9]+)")
# Match v4_0_N
RE_V4_0_UNDER = re.compile(r"v4_0_(0|[2-9]|[1-9][0-9]+)")


def repl_dot(m):
    return "3.0." + m.group(1)


def repl_under(m):
    return "3_0_" + m.group(1)


def repl_v_under(m):
    return "v3_0_" + m.group(1)


def process(path: str) -> bool:
    try:
        with open(path, "r", encoding="utf-8", errors="replace", newline="") as f:
            text = f.read()
    except Exception:
        return False
    new_text = RE_4_0_DOT.sub(repl_dot, text)
    new_text = RE_4_0_UNDER.sub(repl_under, new_text)
    new_text = RE_V4_0_UNDER.sub(repl_v_under, new_text)
    if new_text == text:
        return False
    with open(path, "w", encoding="utf-8", newline="") as f:
        f.write(new_text)
    return True


def main():
    changed = []
    for dirpath, dirnames, filenames in os.walk(ROOT):
        dirnames[:] = [d for d in dirnames if d not in EXCLUDE_DIRS]
        for name in filenames:
            if name in EXCLUDE_FILES:
                continue
            base, ext = os.path.splitext(name)
            if ext.lower() not in {
                ".md", ".php", ".sql", ".json", ".txt", ".yml", ".yaml", ".mdc", ".ps1", ".mdx", ".ini"
            }:
                continue
            path = os.path.join(dirpath, name)
            rel = os.path.relpath(path, ROOT)
            if "backups" in rel.split(os.sep):
                continue
            if process(path):
                changed.append(rel)
    for rel in sorted(changed):
        print(rel)
    print(f"\nUpdated {len(changed)} files.")


if __name__ == "__main__":
    main()
