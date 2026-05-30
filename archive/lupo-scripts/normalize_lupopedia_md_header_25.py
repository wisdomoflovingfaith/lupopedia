#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "lupo-scripts/normalize_lupopedia_md_header_25.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/normalize_lupopedia_md_header_25.py"
#   status: "complete"
#   when_updated: "20260415080730"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/normalize-lupopedia-md-header-25.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/normalize-lupopedia-md-header-25"
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
#   title: "Normalize Lupopedia Markdown header to PRD 16 v4.1.0 dense envelope"
#   summary: "MD dense 22-key; --include-py; --verify-edges KAIROS (node_status warnings vs missing/deleted); strips blanks after # fence"
# ---------------------------------------------------------------------
"""
Rebuild a single leading Lupopedia Markdown header to PRD 16 v4.1.0:

  - line 1: ``---``
  - line 2: ``lupopedia.headers:``
  - lines 3–24: 22 single-line keys in canonical v4.1.0 order (no blank lines between)
  - line 25: ``---``
  - line 26+: body

Maps legacy ``prd_id`` / ``prd_slug`` / ``parent_prd`` to ``pk_*`` in output.
Maps legacy ``memory_key`` to ``memory_toon``, ``dialog_transcript`` to ``transcript_jsonl``,
``module`` to ``atoms_toon`` in output.
Fills ``summary`` / ``atoms_toon`` when missing.

Usage:
  python lupo-scripts/normalize_lupopedia_md_header_25.py [--dry-run] [--check] [--backup] \\
      [--verbose] [--recursive] [--path GLOB]

Workflow: fix_double_headers.py -> this script -> validate_lupopedia_headers_universal.py
"""

from __future__ import annotations

import argparse
import glob
import os
import shutil
import sys

_SCRIPTS_DIR = os.path.dirname(os.path.abspath(__file__))
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

from lib.header_spec_v3_1 import (  # noqa: E402
    V4_MD_INNER_LINE_COUNT,
    apply_v4099_header_defaults,
    emit_markdown_inner_from_header_dict,
    emit_markdown_inner_legacy_v400_from_canonical,
    emit_python_header_block_lines_from_header_dict,
    merge_legacy_header_keys,
    normalize_header_dict_for_validation,
)
from lib.lupopedia_markdown_header_peel import peel_leading_lupopedia_yaml_blocks  # noqa: E402

try:
    from lib.kairos_edge_verification import (  # noqa: E402
        KAIROS_EDGE_VERIFICATION_AVAILABLE,
        verify_edges_for_file as kairos_verify_edges_for_file,
    )
except ImportError:
    KAIROS_EDGE_VERIFICATION_AVAILABLE = False
    kairos_verify_edges_for_file = None  # type: ignore

# V4_MD_INNER_LINE_COUNT is 23: one ``lupopedia.headers:`` row plus 22 scalar key rows (dense v4.1.0).
# It is not 22 — that count is keys only; see ``lib/header_spec_v3_1.py`` and ``emit_markdown_inner_from_header_dict``.

try:
    import yaml
except ImportError:
    yaml = None  # type: ignore


def _trim_body_leading(body: str) -> str:
    b = body.replace("\r\n", "\n").lstrip("\ufeff \t")
    if not b:
        return b
    if b.startswith("\r"):
        b = b.lstrip("\r")
    if b.startswith("\n"):
        b = "\n" + b.lstrip("\n")
    return b


def _normalize_inner_to_target(inner: str, target_version: str) -> tuple[str, str]:
    """
    Returns (inner_yaml_block_without_outer_dashes, mode) where mode is 'dense' or 'legacy'.
    """
    if yaml is None:
        raise ValueError("PyYAML is required")
    inner = inner.replace("\r\n", "\n").strip()
    try:
        data = yaml.safe_load(inner)
    except Exception as e:
        raise ValueError("YAML parse failed: %s" % e)
    if not isinstance(data, dict):
        raise ValueError("inner YAML is not a mapping")
    hdr = data.get("lupopedia.headers")
    if not isinstance(hdr, dict):
        raise ValueError("lupopedia.headers missing or not a mapping")
    hdr_plain = dict(normalize_header_dict_for_validation(hdr))
    tv = (target_version or "4.1.0").strip()
    if tv == "4.0.0":
        new_inner = emit_markdown_inner_legacy_v400_from_canonical(hdr_plain, header_format_version="4.0.0")
        return new_inner, "legacy"
    # 4.1.0 is the canonical target; 4.0.99 is accepted as a legacy alias
    if tv in ("4.1.0", "4.0.99"):
        new_inner = emit_markdown_inner_from_header_dict(hdr_plain)
        if len(new_inner.splitlines()) != V4_MD_INNER_LINE_COUNT:
            raise ValueError(
                "emit produced %d lines, expected %d"
                % (len(new_inner.splitlines()), V4_MD_INNER_LINE_COUNT)
            )
        return new_inner, "dense"
    raise ValueError("unsupported --target-version %r (use 4.1.0 or 4.0.0)" % target_version)


def migrate_python_file_to_dense(
    path: str,
    *,
    dry_run: bool,
    backup: bool,
    verbose: bool,
) -> str:
    """
    Rewrite leading 25-line Lupopedia Python header to dense v4.1.0 (22 keys, no blank 23–24).
    Returns changed | unchanged | skip_error.
    """
    if yaml is None:
        print("[SKIP] %s: PyYAML is required" % path)
        return "skip_error"
    from validate_lupopedia_headers_universal import validate_python_header_envelope  # noqa: E402

    with open(path, "r", encoding="utf-8-sig") as f:
        content = f.read()
    content = content.replace("\r\n", "\n")
    lines = content.split("\n")
    ok_env, has_shebang, yaml_inner = validate_python_header_envelope(
        lines,
        path,
        reject_legacy_envelope=False,
        suppress_legacy_envelope_warn=True,
    )
    if not ok_env or not yaml_inner:
        if verbose:
            print("[SKIP] %s: python header envelope not parseable" % path)
        return "skip_error"
    try:
        data = yaml.safe_load(yaml_inner)
    except Exception as e:
        print("[SKIP] %s: YAML parse failed: %s" % (path, e))
        return "skip_error"
    if not isinstance(data, dict):
        print("[SKIP] %s: python header YAML not a mapping" % path)
        return "skip_error"
    hdr = data.get("lupopedia.headers")
    if not isinstance(hdr, dict):
        print("[SKIP] %s: lupopedia.headers missing" % path)
        return "skip_error"
    hdr2 = dict(normalize_header_dict_for_validation(hdr))
    hdr2["header_format_version"] = "4.1.0"
    try:
        new_block = emit_python_header_block_lines_from_header_dict(hdr2)
    except Exception as e:
        print("[SKIP] %s: %s" % (path, e))
        return "skip_error"
    body_start = 26 if has_shebang else 25
    body_rest = lines[body_start:]
    while body_rest and not body_rest[0].strip():
        body_rest = body_rest[1:]
    new_lines: list[str] = []
    if has_shebang:
        new_lines.append(lines[0])
    new_lines.extend(new_block)
    new_lines.extend(body_rest)
    new_content = "\n".join(new_lines)
    if not new_content.endswith("\n"):
        new_content += "\n"
    if new_content == content:
        if verbose:
            print("[OK] unchanged: %s" % path)
        return "unchanged"
    if verbose:
        print("[INFO] %s: applying dense v4.1.0 python header" % path)
    if dry_run:
        print("[DRY-RUN] would migrate python header: %s" % path)
        return "changed"
    if backup:
        shutil.copy2(path, path + ".bak")
    with open(path, "w", encoding="utf-8", newline="\n") as f:
        f.write(new_content)
    print("[OK] migrated python header: %s" % path)
    return "changed"


def _kairos_verify_after_normalize(path: str, verbose: bool) -> None:
    """KAIROS (115): optional post-migrate edge check (requires DB + pymysql)."""
    if not KAIROS_EDGE_VERIFICATION_AVAILABLE or kairos_verify_edges_for_file is None:
        print("[KAIROS] Edge verification module not available (import failed)")
        return
    try:
        result = kairos_verify_edges_for_file(path)
    except Exception as exc:
        print("[KAIROS] Edge verification error: %s" % exc)
        return
    status = str(result.get("node_status") or "unknown")
    issues = result.get("issues") or []
    if status in ("missing", "deleted_only"):
        print(
            "[KAIROS] Edge verification node_status=%s issues=%s"
            % (status, issues)
        )
        if verbose and result.get("summary"):
            print("[KAIROS] %s" % result["summary"])
    elif status in ("isolated", "incomplete"):
        print(
            "[KAIROS] WARNING: node_status=%s (outgoing=%s) issues=%s"
            % (status, result.get("outgoing_edges"), issues)
        )
        if verbose and result.get("summary"):
            print("[KAIROS] %s" % result["summary"])
    elif issues and verbose:
        print("[KAIROS] Edge verification note: %s" % issues)
        if result.get("summary"):
            print("[KAIROS] %s" % result["summary"])
    else:
        if verbose:
            print(
                "[KAIROS] Edge verification OK (%s): %s"
                % (status, result.get("summary") or path)
            )


def normalize_file(
    path: str,
    *,
    dry_run: bool,
    backup: bool,
    verbose: bool,
    target_version: str = "4.1.0",
    verify_edges: bool = False,
) -> str:
    """
    Returns one of: changed, unchanged, skip_multi, skip_error
    """
    with open(path, "r", encoding="utf-8-sig") as f:
        content = f.read()
    content = content.replace("\r\n", "\n")
    inners, body = peel_leading_lupopedia_yaml_blocks(content)
    if len(inners) != 1:
        if verbose:
            print(
                "[SKIP] %s: expected exactly one leading YAML block, got %d"
                % (path, len(inners))
            )
        return "skip_multi"

    old_inner = inners[0]
    try:
        new_inner, mode = _normalize_inner_to_target(old_inner, target_version)
    except ValueError as e:
        print("[SKIP] %s: %s" % (path, e))
        return "skip_error"

    body2 = _trim_body_leading(body)
    if mode == "legacy":
        new_content = "---\n" + new_inner.rstrip("\n") + "\n\n\n---\n" + body2
    else:
        new_content = "---\n" + new_inner.rstrip("\n") + "\n---\n" + body2
    old_norm = old_inner.rstrip("\n")

    if new_inner.rstrip("\n") == old_norm and new_content == content:
        if verbose:
            print("[OK] unchanged: %s" % path)
        return "unchanged"

    if verbose:
        print("[INFO] %s: applying v4.1.0 dense header envelope" % path)

    if dry_run:
        print("[DRY-RUN] would normalize: %s" % path)
        return "changed"

    if backup:
        shutil.copy2(path, path + ".bak")

    with open(path, "w", encoding="utf-8", newline="\n") as f:
        f.write(new_content)
    print("[OK] normalized header layout: %s" % path)
    if verify_edges:
        _kairos_verify_after_normalize(path, verbose)
    return "changed"


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Normalize PRD-16 Markdown header to v4.1.0 dense 22-key inner block.",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="Use --recursive when --path contains ** (e.g. lupo-docs/**/*.md).",
    )
    parser.add_argument(
        "--path",
        default="lupo-docs/prd/[0-9][0-9]_*.md",
        help="Glob relative to repo root (default: PRD markdown files)",
    )
    parser.add_argument(
        "--recursive",
        action="store_true",
        help="Enable recursive glob (** in --path)",
    )
    parser.add_argument("--dry-run", action="store_true", help="Print actions only")
    parser.add_argument(
        "--check",
        action="store_true",
        help="Exit 1 if any file would be normalized (CI); implies review pass, no writes",
    )
    parser.add_argument(
        "--backup",
        action="store_true",
        help="Write path.bak before each modified file (no effect with --dry-run/--check)",
    )
    parser.add_argument("--verbose", "-v", action="store_true", help="Per-file detail")
    parser.add_argument(
        "--target-version",
        default="4.1.0",
        choices=("4.1.0", "4.0.99", "4.0.0"),
        help="4.1.0 = dense 22-key v4.1.0 canonical (default); 4.0.99 = legacy alias for 4.1.0; 4.0.0 = legacy 20-key + blank lines 23-24",
    )
    parser.add_argument(
        "--include-py",
        action="store_true",
        help=(
            "For paths ending in .py: run migrate_python_file_to_dense (shebang-aware 25-line # block; "
            "strips blank lines between closing fence and body). Markdown paths use normalize_file."
        ),
    )
    parser.add_argument(
        "--verify-edges",
        action="store_true",
        help=(
            "After each successful Markdown header write (not dry-run/check), run KAIROS "
            "lib/kairos_edge_verification.verify_edges_for_file (needs pymysql + DB). Ignored for .py paths."
        ),
    )
    args = parser.parse_args()

    if yaml is None:
        print("[ERROR] PyYAML is required")
        return 1

    dry_run = bool(args.dry_run or args.check)
    repo_root = os.path.dirname(_SCRIPTS_DIR)
    pattern = os.path.join(repo_root, args.path.replace("/", os.sep))
    paths = sorted(glob.glob(pattern, recursive=args.recursive))
    stats = {
        "scanned": 0,
        "changed": 0,
        "unchanged": 0,
        "skip_multi": 0,
        "skip_error": 0,
    }

    for path in paths:
        if not os.path.isfile(path):
            continue
        stats["scanned"] += 1
        if args.include_py and path.lower().endswith(".py"):
            result = migrate_python_file_to_dense(
                path,
                dry_run=dry_run,
                backup=bool(args.backup) and not args.check,
                verbose=bool(args.verbose),
            )
        else:
            result = normalize_file(
                path,
                dry_run=dry_run,
                backup=bool(args.backup) and not args.check,
                verbose=bool(args.verbose),
                target_version=str(args.target_version),
                verify_edges=bool(args.verify_edges),
            )
        stats[result] += 1

    print(
        "\nSummary: scanned=%d changed=%d unchanged=%d skip_multi=%d skip_error=%d"
        % (
            stats["scanned"],
            stats["changed"],
            stats["unchanged"],
            stats["skip_multi"],
            stats["skip_error"],
        )
    )

    if args.check and stats["changed"] > 0:
        print(
            "[CHECK] FAIL: %d file(s) need normalization (re-run without --check)"
            % stats["changed"]
        )
        return 1
    if args.check:
        print("[CHECK] OK: no files need normalization")
    return 0


if __name__ == "__main__":
    sys.exit(main())
