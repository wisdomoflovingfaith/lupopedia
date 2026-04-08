#!/usr/bin/env python3
"""
Validate PRD 17 thread folder structure and filenames.

Checks:
- decisions/, questions/, answers/, comments/ must include THREAD_INDEX.md
- Filenames follow PRD 17 timestamp/type/title conventions
- decisions/ files must include STATUS segment
- questions/answers/comments files must not include STATUS segment
"""

from __future__ import annotations

import argparse
import re
import sys
from pathlib import Path


TARGET_DIRS = {"decisions", "questions", "answers", "comments"}
DECISION_TYPES = {"DECISION", "PROPOSAL", "CLARIFICATION", "RESOLUTION"}
NON_DECISION_TYPE = {
    "questions": "QUESTION",
    "answers": "ANSWER",
    "comments": "COMMENT",
}

TS_RE = re.compile(r"^(?P<ts>(?:\d{8}_\d{6}|\d{14}))_(?P<typ>[A-Z0-9]+)_(?P<rest>.+)\.md$")
TITLE_RE = re.compile(r"^[a-z0-9_]+$")
STATUS_RE = re.compile(r"^[A-Z0-9]+$")


def _norm(path: Path) -> str:
    return str(path).replace("\\", "/")


def _iter_target_dirs(base: Path):
    for p in base.rglob("*"):
        if p.is_dir() and p.name in TARGET_DIRS:
            yield p


def _validate_file(folder: Path, file_path: Path):
    errors = []
    m = TS_RE.match(file_path.name)
    rel = _norm(file_path)
    if not m:
        return ["%s: invalid filename format for PRD 17 thread artifact" % rel]

    typ = m.group("typ")
    rest = m.group("rest")
    folder_name = folder.name

    if folder_name == "decisions":
        if "_" not in rest:
            errors.append("%s: decisions filename must include _STATUS_ segment" % rel)
            return errors
        status, title = rest.split("_", 1)
        if typ not in DECISION_TYPES:
            errors.append(
                "%s: decisions TYPE must be one of %s"
                % (rel, ", ".join(sorted(DECISION_TYPES)))
            )
        if not STATUS_RE.match(status):
            errors.append("%s: invalid STATUS segment '%s'" % (rel, status))
        if not TITLE_RE.match(title):
            errors.append("%s: TITLE must be lowercase underscore format" % rel)
    else:
        expected = NON_DECISION_TYPE[folder_name]
        if typ != expected:
            errors.append("%s: %s TYPE must be %s" % (rel, folder_name, expected))
        if not TITLE_RE.match(rest):
            errors.append(
                "%s: %s files must not include STATUS segment and TITLE must be lowercase underscores"
                % (rel, folder_name)
            )
    return errors


def validate_paths(paths):
    errors = []
    inspected = 0
    found_dirs = 0

    for root in paths:
        base = Path(root)
        if not base.exists():
            errors.append("Path not found: %s" % _norm(base))
            continue

        dirs = list(_iter_target_dirs(base) if base.is_dir() else [])
        if base.is_dir() and base.name in TARGET_DIRS:
            dirs = [base] + dirs
        seen = set()
        unique_dirs = []
        for d in dirs:
            rp = d.resolve()
            if rp not in seen:
                seen.add(rp)
                unique_dirs.append(d)

        for folder in unique_dirs:
            found_dirs += 1
            thread_index = folder / "THREAD_INDEX.md"
            if not thread_index.exists():
                errors.append("%s: missing THREAD_INDEX.md" % _norm(folder))

            for f in folder.iterdir():
                if not f.is_file() or f.suffix.lower() != ".md":
                    continue
                if f.name == "THREAD_INDEX.md":
                    continue
                inspected += 1
                errors.extend(_validate_file(folder, f))

    return found_dirs, inspected, errors


def main():
    parser = argparse.ArgumentParser(description="Validate PRD 17 thread folder structure")
    parser.add_argument("paths", nargs="*", default=["."], help="Files/directories to scan")
    args = parser.parse_args()

    found_dirs, inspected, errors = validate_paths(args.paths)
    if errors:
        print("Thread structure validation errors (%d):" % len(errors))
        for e in errors:
            print("  - %s" % e)
        return 1

    print(
        "Thread structure validation passed. folders=%d files_checked=%d"
        % (found_dirs, inspected)
    )
    return 0


if __name__ == "__main__":
    sys.exit(main())
