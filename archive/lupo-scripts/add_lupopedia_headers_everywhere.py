#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "lupo-scripts/add_lupopedia_headers_everywhere.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/add_lupopedia_headers_everywhere.py"
#   status: "complete"
#   when_updated: "20260412161106"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/add-lupopedia-headers-everywhere.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/add-lupopedia-headers-everywhere"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "16"
#   lupopedia.schema: implementation
#   title: "Batch-add LUPOPEDIA HEADERS for knowledge and RAG coverage"
#   summary: "Batch prepend v4.1.0 headers via add_lupopedia_header_to_file builders"
# ---------------------------------------------------------------------
"""
Walk the repo and prepend PRD 16 v4.1.0 dense headers to headerless .md / .py / .php files.

Builders live in add_lupopedia_header_to_file.py (_build_md_header / _build_py_header / prepend_dense_header_to_php):
all **22** scalar keys (pk_id / pk_slug / parent_pk_id / summary / atoms_toon), content_id: null,
https web_path (unless --development), section 8.1 memory_toon year, three-segment transcript_jsonl.
Python: shebang line 1 is preserved; the 25-line ``#`` grid follows. PHP: ``#!/usr/bin/env php`` (when present) and
``<?php`` are preserved; the same **25-line** ``#`` grid is inserted immediately after ``<?php``.

Timestamps: one anchor UTC for the whole run via add_lupopedia_header_to_file._get_anchor_utc_14()
(tick.py + echo_anchor_utc.py). Do not use datetime.utcnow() for header fields.

Usage:
  python lupo-scripts/add_lupopedia_headers_everywhere.py --dry-run
  python lupo-scripts/add_lupopedia_headers_everywhere.py --under lupo-docs --under lupo-scripts
  python lupo-scripts/add_lupopedia_headers_everywhere.py --all-repo --dry-run
  python lupo-scripts/add_lupopedia_headers_everywhere.py --channel-key headers --trust-tier staging
  python lupo-scripts/add_lupopedia_headers_everywhere.py --thread-slug my-thread-slug ...
"""

from __future__ import annotations

import argparse
import os
import subprocess
import sys

_SCRIPTS_DIR = os.path.dirname(os.path.abspath(__file__))
_REPO_ROOT = os.path.dirname(_SCRIPTS_DIR)
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)
_LIB_DIR = os.path.join(_SCRIPTS_DIR, "lib")
if _LIB_DIR not in sys.path:
    sys.path.insert(0, _LIB_DIR)

import add_lupopedia_header_to_file as alh  # noqa: E402
from lupopedia_markdown_header_peel import peel_leading_lupopedia_yaml_blocks  # noqa: E402

DEFAULT_UNDER = ("lupo-docs", "lupo-scripts")
DEFAULT_EXCLUDE_DIRS = frozenset(
    {
        ".git",
        ".svn",
        ".hg",
        "node_modules",
        "__pycache__",
        ".tox",
        "venv",
        ".venv",
        "dist",
        "build",
        "vendor",
        ".idea",
        ".vscode",
    }
)
SKIP_BASENAMES = frozenset(
    {
        "package-lock.json",
        "yarn.lock",
        "pnpm-lock.yaml",
        "composer.lock",
    }
)
SKIP_SUFFIXES = (".min.js", ".min.css", ".map")


def _parse_include(raw: str) -> tuple[str, ...]:
    parts = [p.strip().lower() for p in raw.replace(",", " ").split() if p.strip()]
    out = []
    for p in parts:
        if not p.startswith("."):
            p = "." + p
        out.append(p)
    return tuple(out)


def _should_skip_dir(name: str, extra_exclude: frozenset[str]) -> bool:
    if name in DEFAULT_EXCLUDE_DIRS or name in extra_exclude:
        return True
    if name.startswith(".") and name not in (".github",):
        return True
    return False


def _md_has_leading_lupopedia_header(content: str) -> bool:
    chunks, _rest = peel_leading_lupopedia_yaml_blocks(content)
    return len(chunks) > 0


def _py_skip_reason(content: str) -> str | None:
    """
    Skip if a valid top header exists, or if lupopedia.headers appears only in a bad position.

    Top window: first 25 body lines after an optional shebang (PRD 16 Python envelope after #!).
    Shebang alone does not skip — we insert the comment header after line 1.
    """
    text = content.replace("\r\n", "\n")
    lines = text.split("\n")
    if not lines:
        return None
    first = lines[0].lstrip("\ufeff ")
    if first.startswith("#!"):
        window = "\n".join(lines[1:26])
    else:
        window = "\n".join(lines[:25])
    if "lupopedia.headers:" in window:
        return "already_has_header"
    if "lupopedia.headers:" in text:
        return "lupopedia_headers_not_at_top"
    return None


def process_file(path: str, utc: str, args: argparse.Namespace) -> str:
    """
    Returns one of: skipped, added, error
    """
    rel = alh._repo_rel_path(path)
    ext = os.path.splitext(path)[1].lower()
    try:
        with open(path, "r", encoding="utf-8-sig") as f:
            body = f.read()
    except (OSError, UnicodeDecodeError):
        return "error"

    stem = os.path.splitext(os.path.basename(path))[0]
    slug = alh._slug_from_stem(stem)
    ts = (args.thread_slug or "").strip()
    if ts:
        slug = alh._slug_from_stem(ts)
    title = stem.replace("_", " ").replace("-", " ").title()

    ck = (args.channel_key or "development").strip()
    tt = (args.trust_tier or "canonical").strip()
    fnid = int(args.federation_node_id)
    use_https = not args.development
    hfv = (args.header_format_version or alh.DEFAULT_HEADER_FORMAT_VERSION).strip()
    summary = (getattr(args, "summary", None) or "Generated header").strip() or "Generated header"

    if ext == ".md":
        if _md_has_leading_lupopedia_header(body):
            return "skipped"
        lupo_schema = args.lupopedia_schema or "documentation"
        atype = args.artifact_type or "documentation"
        akind = args.artifact_kind or "guide"
        parent_prd = ""
        header = alh._build_md_header(
            utc,
            rel,
            title,
            slug,
            lupo_schema,
            atype,
            akind,
            parent_prd,
            channel_key=ck,
            trust_tier=tt,
            federation_node_id=fnid,
            use_https_web_path=use_https,
            header_format_version=hfv,
            summary=summary,
        )
        rest = body.lstrip("\n\r\ufeff \t")
        new_content = header + rest
    elif ext == ".py":
        reason = _py_skip_reason(body)
        if reason:
            if args.verbose:
                print("[SKIP] %s (%s)" % (rel, reason))
            return "skipped"
        lupo_schema = args.lupopedia_schema or "implementation"
        atype = args.artifact_type or "implementation"
        akind = args.artifact_kind or "tool"
        parent_prd = (args.parent_prd or "").strip() or alh.DEFAULT_PARENT_PRD_FOR_PY
        lines_kept = body.splitlines(True)
        has_shebang = bool(
            lines_kept and lines_kept[0].lstrip("\ufeff ").startswith("#!")
        )
        header = alh._build_py_header(
            utc,
            rel,
            title,
            slug,
            lupo_schema,
            atype,
            akind,
            parent_prd,
            channel_key=ck,
            trust_tier=tt,
            federation_node_id=fnid,
            use_https_web_path=use_https,
            header_format_version=hfv,
            include_shebang_line=not has_shebang,
            summary=summary,
        )
        nl = alh._detect_newline(body)
        header = alh._normalize_header_newlines(header, nl)
        if has_shebang:
            new_content = lines_kept[0] + header + "".join(lines_kept[1:])
        else:
            rest = body.lstrip("\n\r\ufeff \t")
            new_content = header + rest
    elif ext == ".php":
        reason = _py_skip_reason(body)
        if reason:
            if args.verbose:
                print("[SKIP] %s (%s)" % (rel, reason))
            return "skipped"
        lupo_schema = args.lupopedia_schema or "implementation"
        atype = args.artifact_type or "implementation"
        akind = args.artifact_kind or "tool"
        parent_prd = (args.parent_prd or "").strip() or alh.DEFAULT_PARENT_PRD_FOR_PY
        new_content = alh.prepend_dense_header_to_php(
            body,
            utc,
            rel,
            title,
            slug,
            lupo_schema,
            atype,
            akind,
            parent_prd,
            channel_key=ck,
            trust_tier=tt,
            federation_node_id=fnid,
            use_https_web_path=use_https,
            header_format_version=hfv,
            summary=summary,
        )
    else:
        return "skipped"

    if args.dry_run:
        print("[DRY-RUN] would add header: %s" % rel)
        return "added"

    try:
        with open(path, "w", encoding="utf-8", newline="\n") as f:
            f.write(new_content)
    except OSError:
        return "error"
    print("[ADDED] %s" % rel)

    if args.validate:
        vpy = os.path.join(_SCRIPTS_DIR, "validate_lupopedia_headers_universal.py")
        vcmd = [sys.executable, vpy, rel, "--quiet"]
        if args.development:
            vcmd.append("--development")
        vproc = subprocess.run(vcmd, cwd=_REPO_ROOT)
        if vproc.returncode != 0:
            print("[WARN] Validation failed: %s (exit %s)" % (rel, vproc.returncode))

    return "added"


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Batch-add LUPOPEDIA HEADERS (.md / .py / .php) using the canonical v4.0.99 dense envelope."
    )
    parser.add_argument(
        "--under",
        action="append",
        default=[],
        metavar="DIR",
        help="Repo-relative directory to scan (repeatable). Default: lupo-docs and lupo-scripts.",
    )
    parser.add_argument(
        "--all-repo",
        action="store_true",
        help="Scan entire repository (overrides default --under). Use with --dry-run first.",
    )
    parser.add_argument(
        "--include",
        default=".md .py .php",
        help="Space- or comma-separated extensions (default: .md .py .php).",
    )
    parser.add_argument(
        "--exclude-dir",
        action="append",
        default=[],
        metavar="NAME",
        help="Extra directory name to exclude (basename match, repeatable).",
    )
    parser.add_argument(
        "--max-bytes",
        type=int,
        default=1_500_000,
        help="Skip files larger than this (default: 1500000).",
    )
    parser.add_argument(
        "--channel-key",
        default="development",
        help=(
            'channel_key for memory_toon and transcript_jsonl middle segment (default: development). '
            "Set explicitly for batch runs; do not assume the default matches your channel."
        ),
    )
    parser.add_argument(
        "--trust-tier",
        default="canonical",
        choices=("seed", "canonical", "staging", "archive"),
        help="trust_tier for memory_toon path (default: canonical). Canonical uses §8.1 year offset.",
    )
    parser.add_argument(
        "--federation-node-id",
        type=int,
        default=0,
        help="Header federation_node_id and first segment of transcript_jsonl (default: 0).",
    )
    parser.add_argument(
        "--header-format-version",
        default=alh.DEFAULT_HEADER_FORMAT_VERSION,
        help="header_format_version string (default: %s)" % alh.DEFAULT_HEADER_FORMAT_VERSION,
    )
    parser.add_argument(
        "--lupopedia-schema",
        default="",
        help="Override lupopedia.schema for .md / .py / .php (default: by file type).",
    )
    parser.add_argument(
        "--artifact-type",
        default="",
        help="Override artifact_type (default: documentation for .md, implementation for .py/.php).",
    )
    parser.add_argument(
        "--artifact-kind",
        default="",
        help="Override artifact_kind (default: guide for .md, tool for .py/.php).",
    )
    parser.add_argument(
        "--parent-prd",
        "--parent-pk-id",
        dest="parent_prd",
        default=alh.DEFAULT_PARENT_PRD_FOR_PY,
        metavar="ID",
        help=(
            'parent_pk_id for .py / .php (default: "%s"). Ignored for .md. --parent-pk-id is an alias for --parent-prd.'
            % alh.DEFAULT_PARENT_PRD_FOR_PY
        ),
    )
    parser.add_argument(
        "--thread-slug",
        default="",
        metavar="SLUG",
        help=(
            "Override slug for memory_toon file stem and transcript_jsonl third segment "
            "(default: from filename stem)."
        ),
    )
    parser.add_argument(
        "--summary",
        default="Generated header",
        help='lupopedia.headers summary (default: "Generated header")',
    )
    parser.add_argument(
        "--development",
        action="store_true",
        help="Use http:// web_path; pass --development to validator with --validate",
    )
    parser.add_argument(
        "--validate",
        action="store_true",
        help="After each write, run validate_lupopedia_headers_universal.py --quiet on the file",
    )
    parser.add_argument("--dry-run", action="store_true")
    parser.add_argument("-v", "--verbose", action="store_true")
    args = parser.parse_args()

    extra_exclude = frozenset(x.strip() for x in args.exclude_dir if x.strip())
    include_exts = _parse_include(args.include)
    if not include_exts:
        sys.stderr.write("[ERROR] --include must list at least one extension.\n")
        return 1

    repo_abs = os.path.abspath(_REPO_ROOT)
    if args.all_repo:
        roots = [repo_abs]
    elif args.under:
        roots = []
        for u in args.under:
            rel = u.replace("\\", "/").strip().strip("/")
            roots.append(os.path.normpath(os.path.join(repo_abs, rel)))
    else:
        roots = [os.path.join(repo_abs, u) for u in DEFAULT_UNDER]

    for r in roots:
        ap = os.path.abspath(r)
        if not ap.startswith(repo_abs):
            sys.stderr.write("[ERROR] Path outside repo: %s\n" % r)
            return 1
        if not os.path.isdir(ap):
            sys.stderr.write("[WARN] Not a directory, skipping: %s\n" % r)

    utc = alh._get_anchor_utc_14()

    counts = {"added": 0, "skipped": 0, "error": 0}
    for root_abs in roots:
        root_abs = os.path.abspath(root_abs)
        if not os.path.isdir(root_abs):
            continue
        for dirpath, dirnames, filenames in os.walk(root_abs):
            dirnames[:] = [
                d
                for d in dirnames
                if not _should_skip_dir(d, extra_exclude)
            ]
            for fn in filenames:
                if fn in SKIP_BASENAMES:
                    continue
                low = fn.lower()
                if any(low.endswith(suf) for suf in SKIP_SUFFIXES):
                    continue
                ext = os.path.splitext(fn)[1].lower()
                if ext not in include_exts:
                    continue
                path = os.path.join(dirpath, fn)
                try:
                    sz = os.path.getsize(path)
                except OSError:
                    counts["error"] += 1
                    continue
                if sz > args.max_bytes:
                    if args.verbose:
                        print("[SKIP] %s (too large)" % alh._repo_rel_path(path))
                    counts["skipped"] += 1
                    continue
                stat = process_file(path, utc, args)
                counts[stat] = counts.get(stat, 0) + 1

    print(
        "[OK] added=%d skipped=%d error=%d dry_run=%s"
        % (counts["added"], counts["skipped"], counts["error"], args.dry_run)
    )
    return 0 if counts["error"] == 0 else 1


if __name__ == "__main__":
    sys.exit(main())
