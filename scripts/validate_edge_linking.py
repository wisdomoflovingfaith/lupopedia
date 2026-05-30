#!/usr/bin/env python3
"""
Validate PRD 17 Q/A edge linking.

Checks lupopedia.edges outbound edge types:
- has_answer -> target file exists and points into answers/
- answers    -> target file exists and points into questions/
"""

from __future__ import annotations

import argparse
import re
import sys
from pathlib import Path


EDGE_RE = re.compile(r"(?m)^\s*-\s*to:\s*['\"]?([^'\"\n]+)['\"]?\s*$")
TYPE_RE = re.compile(r"(?m)^\s*type:\s*['\"]?([^'\"\n]+)['\"]?\s*$")


def _norm(path: Path) -> str:
    return str(path).replace("\\", "/")


def _collect_markdown(paths):
    out = []
    for p in paths:
        path = Path(p)
        if not path.exists():
            continue
        if path.is_file() and path.suffix.lower() == ".md":
            out.append(path)
        elif path.is_dir():
            out.extend(path.rglob("*.md"))
    seen = set()
    unique = []
    for f in out:
        rf = f.resolve()
        if rf not in seen:
            seen.add(rf)
            unique.append(f)
    return unique


def _iter_edges(text):
    lines = text.splitlines()
    i = 0
    while i < len(lines):
        line = lines[i]
        m_to = re.match(r"^\s*-\s*to:\s*['\"]?([^'\"\n]+)['\"]?\s*$", line)
        if not m_to:
            i += 1
            continue
        target = m_to.group(1).strip()
        edge_type = None
        j = i + 1
        while j < len(lines):
            nxt = lines[j]
            if re.match(r"^\s*-\s*to:", nxt):
                break
            m_type = re.match(r"^\s*type:\s*['\"]?([^'\"\n]+)['\"]?\s*$", nxt)
            if m_type:
                edge_type = m_type.group(1).strip()
                break
            j += 1
        yield (target, edge_type)
        i = j


def _resolve_target(src: Path, target: str):
    if "://" in target:
        return None
    return (src.parent / target).resolve()


def validate(paths):
    errors = []
    files_checked = 0
    edges_checked = 0

    for f in _collect_markdown(paths):
        try:
            text = f.read_text(encoding="utf-8", errors="replace")
        except OSError as exc:
            errors.append("%s: unreadable (%s)" % (_norm(f), exc))
            continue

        files_checked += 1
        for target, edge_type in _iter_edges(text):
            if edge_type not in ("has_answer", "answers"):
                continue
            edges_checked += 1
            resolved = _resolve_target(f, target)
            rel = _norm(f)
            if resolved is None:
                errors.append(
                    "%s: edge type '%s' has non-local target '%s'"
                    % (rel, edge_type, target)
                )
                continue
            if not resolved.exists():
                errors.append(
                    "%s: edge type '%s' target does not exist -> %s"
                    % (rel, edge_type, target)
                )
                continue
            normalized_target = _norm(resolved)
            if edge_type == "has_answer" and "/answers/" not in normalized_target:
                errors.append(
                    "%s: has_answer edge must target answers/ -> %s"
                    % (rel, target)
                )
            if edge_type == "answers" and "/questions/" not in normalized_target:
                errors.append(
                    "%s: answers edge must target questions/ -> %s"
                    % (rel, target)
                )

    return files_checked, edges_checked, errors


def main():
    parser = argparse.ArgumentParser(description="Validate PRD 17 Q/A edge linking")
    parser.add_argument("paths", nargs="*", default=["."], help="Files/directories to scan")
    args = parser.parse_args()

    files_checked, edges_checked, errors = validate(args.paths)
    if errors:
        print("Edge linking validation errors (%d):" % len(errors))
        for e in errors:
            print("  - %s" % e)
        return 1

    print(
        "Edge linking validation passed. files_checked=%d edges_checked=%d"
        % (files_checked, edges_checked)
    )
    return 0


if __name__ == "__main__":
    sys.exit(main())
