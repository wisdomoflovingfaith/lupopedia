#!/usr/bin/env python3
"""
Validate lupo-channels/{id}/ tree.

Default: numeric thread dirs (excluding legacy names); canonical .md under numeric threads only.
Use --audit-all to scan broadcasts/content/tasks/rules/direct (many legacy violations expected).
Use --enforce-thread-review-bodies to require substantive body for review-marked thread artifacts.

Usage:
  python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --strict
  python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --enforce-thread-review-bodies
  python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --mode enforce
"""
from __future__ import annotations

import argparse
import re
import sys
from pathlib import Path

CANONICAL_MD = re.compile(r"^[0-9]{8}_[0-9]{6}_[a-z][a-z0-9]*_[a-z][a-z0-9_-]+\.md$")
NUMERIC_THREAD = re.compile(r"^[1-9][0-9]{0,17}$")
SECTION_H2 = re.compile(r"^##\s+", re.MULTILINE)


def _is_review_frontmatter(fm: str) -> bool:
    low = fm.lower()
    return (
        "artifact_kind: review" in low
        or 'artifact_kind: "review"' in low
        or "message_type: review" in low
    )


def _is_help_response_frontmatter(fm: str) -> bool:
    low = fm.lower()
    return (
        "artifact_kind: help_response" in low
        or 'artifact_kind: "help_response"' in low
        or "message_type: help_response" in low
    )


def validate_thread_review_body(path: Path) -> list[str]:
    """Enforce substantive body for thread .md files marked as review."""
    out: list[str] = []
    try:
        text = path.read_text(encoding="utf-8", errors="replace")
    except OSError as e:
        return [f"READ_ERROR: {path} ({e})"]
    if not text.startswith("---"):
        return out
    parts = text.split("---", 2)
    if len(parts) < 3:
        out.append(f"THREAD_FRONTMATTER: incomplete YAML {path}")
        return out
    fm = parts[1]
    body = parts[2].strip()
    name_low = path.name.lower()
    if not _is_review_frontmatter(fm) and "review" not in name_low:
        return out
    if len(body) < 500:
        out.append(f"THREAD_REVIEW_SHORT: {path} (body {len(body)} chars, need 500+ to match API)")
        return out
    n = len(SECTION_H2.findall(body))
    if n < 3:
        out.append(f"THREAD_REVIEW_SECTIONS: {path} (need 3+ ## headings, got {n})")
    return out


def validate_thread_help_response_body(path: Path) -> list[str]:
    """Enforce substantive body for thread .md marked help_response (LILITH ATER001)."""
    out: list[str] = []
    try:
        text = path.read_text(encoding="utf-8", errors="replace")
    except OSError as e:
        return [f"READ_ERROR: {path} ({e})"]
    if not text.startswith("---"):
        return out
    parts = text.split("---", 2)
    if len(parts) < 3:
        out.append(f"THREAD_FRONTMATTER: incomplete YAML {path}")
        return out
    fm = parts[1]
    body = parts[2].strip()
    if not _is_help_response_frontmatter(fm):
        return out
    if len(body) < 200:
        out.append(f"THREAD_HELP_RESPONSE_SHORT: {path} (body {len(body)} chars, need 200+)")
        return out
    if body.count("#") < 3:
        out.append(f"THREAD_HELP_RESPONSE_HASH: {path} (need 3+ # in body)")
        return out
    if not re.search(r"^#\s+\S", body, re.MULTILINE):
        out.append(f"THREAD_HELP_RESPONSE_H1: {path} (need # title line)")
        return out
    n = len(SECTION_H2.findall(body))
    if n < 3:
        out.append(f"THREAD_HELP_RESPONSE_SECTIONS: {path} (need 3+ ## headings, got {n})")
    return out


def validate_channel(
    repo: Path,
    channel_id: int,
    legacy_thread_dirs: frozenset[str] | None = None,
    audit_all: bool = False,
    enforce_thread_review_bodies: bool = False,
    enforce_help_response_bodies: bool = False,
) -> list[str]:
    errors: list[str] = []
    base = repo / "lupo-channels" / str(channel_id)
    if not base.is_dir():
        return [f"missing {base}"]

    legacy = legacy_thread_dirs or frozenset({"4.0.x", "4.0.68", "4.0.73", "4.0.80"})

    th = base / "threads"
    if th.is_dir():
        for sub in th.iterdir():
            if not sub.is_dir():
                continue
            name = sub.name
            is_legacy = name in legacy
            if not NUMERIC_THREAD.match(name) and not is_legacy:
                errors.append(f"NON_NUMERIC_THREAD_DIR: {sub.relative_to(repo)}")
            if is_legacy:
                continue
            for f in sub.rglob("*.md"):
                if f.name == "README.md":
                    continue
                if not CANONICAL_MD.match(f.name):
                    errors.append(f"BAD_FILENAME: {f.relative_to(repo)}")
                else:
                    if enforce_thread_review_bodies:
                        errors.extend(validate_thread_review_body(f))
                    if enforce_help_response_bodies:
                        errors.extend(validate_thread_help_response_body(f))

    if not audit_all:
        return errors

    for subname in ("broadcasts", "content", "tasks", "rules"):
        d = base / subname
        if d.is_dir():
            for f in d.glob("*.md"):
                if f.name == "README.md":
                    continue
                if not CANONICAL_MD.match(f.name):
                    errors.append(f"BAD_FILENAME: {f.relative_to(repo)}")

    dr = base / "direct"
    if dr.is_dir():
        for actor_dir in dr.iterdir():
            if not actor_dir.is_dir():
                continue
            if not NUMERIC_THREAD.match(actor_dir.name):
                errors.append(f"NON_NUMERIC_DIRECT_DIR: {actor_dir.relative_to(repo)}")
                continue
            for f in actor_dir.glob("*.md"):
                if f.name == "README.md":
                    continue
                if not CANONICAL_MD.match(f.name):
                    errors.append(f"BAD_FILENAME: {f.relative_to(repo)}")

    return errors


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--repo-root", default=".")
    ap.add_argument("--channel", type=int, default=42)
    ap.add_argument("--strict", action="store_true")
    ap.add_argument("--legacy-thread-dirs", default="4.0.x,4.0.68,4.0.73,4.0.80")
    ap.add_argument("--no-legacy-skip", action="store_true")
    ap.add_argument(
        "--audit-all",
        action="store_true",
        help="Include broadcasts/content/tasks/rules/direct (legacy-heavy)",
    )
    ap.add_argument(
        "--enforce-thread-review-bodies",
        action="store_true",
        help="Thread .md with review in name or artifact_kind: review need 500+ chars and 3+ ##",
    )
    ap.add_argument(
        "--enforce-help-response-bodies",
        action="store_true",
        help="Thread .md with artifact_kind: help_response need 200+ chars, # title, 3+ ##",
    )
    ap.add_argument(
        "--mode",
        choices=("check", "enforce"),
        default="check",
        help="enforce = --strict + thread review bodies + help_response bodies (CI gateway)",
    )
    args = ap.parse_args()
    enforce_rev = args.enforce_thread_review_bodies
    enforce_help = args.enforce_help_response_bodies
    if args.mode == "enforce":
        args.strict = True
        enforce_rev = True
        enforce_help = True
    root = Path(args.repo_root).resolve()
    leg = (
        frozenset()
        if args.no_legacy_skip
        else frozenset(x.strip() for x in args.legacy_thread_dirs.split(",") if x.strip())
    )
    errs = validate_channel(
        root,
        args.channel,
        leg,
        audit_all=args.audit_all,
        enforce_thread_review_bodies=enforce_rev,
        enforce_help_response_bodies=enforce_help,
    )
    for e in errs:
        print(e)
    print(f"validate_channel_artifacts: {len(errs)} issue(s) for channel {args.channel}")
    if args.strict and errs:
        return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
