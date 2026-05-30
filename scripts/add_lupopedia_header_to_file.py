#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "scripts/add_lupopedia_header_to_file.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/add_lupopedia_header_to_file.py"
#   status: "complete"
#   when_updated: "20260415080730"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/add-lupopedia-header-to-file.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/add-lupopedia-header-to-file"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "16"
#   content_slug: ""
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Add missing LUPOPEDIA HEADERS to a new or headerless file"
#   summary: "Prepend or create PRD 16 v4.1.0 dense headers; tick.py anchor UTC"
# ---------------------------------------------------------------------
"""
Prepend a PRD 16 header (header_format_version 4.1.2 envelope) to a headerless file,
or create a new file with a valid header.

Timestamps: runs bin/tick.py then reads bin/echo_anchor_utc.py (temporal anchor).
Never uses guessed local time for header fields.

Usage:
  python scripts/add_lupopedia_header_to_file.py path/to/file.py
  python scripts/add_lupopedia_header_to_file.py path/to/script.php --title "My CLI"
  python scripts/add_lupopedia_header_to_file.py path/to/file.md --title "My Doc"
  python scripts/add_lupopedia_header_to_file.py path/to/new.md --create
  python scripts/add_lupopedia_header_to_file.py path/to/file.md --validate --backup
  python scripts/add_lupopedia_header_to_file.py path/to/x.py --trust-tier staging --federation-node-id 0
  python scripts/add_lupopedia_header_to_file.py path/to/x.py --development --validate
  python scripts/add_lupopedia_header_to_file.py path/to/x.md --thread-slug my-custom-slug
  python scripts/add_lupopedia_header_to_file.py path/to/x.py --skip-memory-sidecar
  python scripts/add_lupopedia_header_to_file.py path/to/x.py --no-memory
  python scripts/add_lupopedia_header_to_file.py path/to/x.py --force-memory

Batch (many .md / .py): python scripts/add_lupopedia_headers_everywhere.py --dry-run

**PHP (4.0.99+):** emits ``#!/usr/bin/env php`` (when absent) + ``<?php`` + the same **25-line** ``#`` grid as Python (not ``/**`` YAML). Validate with ``validate_lupopedia_headers_universal.py``.

Auditor note (recurring external reviews, 2026): This script header uses https web_path; builders use
use_https_web_path (False when --development). memory_toon uses _memory_path_year_segment (canonical =>
calendar year - 1000). transcript_jsonl is three segments: federation_node_id/channel_key/slug (not
dialog_middle / four segments). main() passes channel_key, trust_tier, federation_node_id; Python
files with a shebang insert the header after line 1 (include_shebang_line=False). There is no
shebang hard-error path. --trust-tier and --development already exist on the CLI.
"""

from __future__ import annotations

import argparse
import json
import os
import re
import shutil
import subprocess

import sys

_scripts_dir_for_lib = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, os.path.join(_scripts_dir_for_lib, "lib"))
from lupopedia_markdown_header_peel import peel_leading_lupopedia_yaml_blocks

_SCRIPTS_DIR = os.path.dirname(os.path.abspath(__file__))
_REPO_ROOT = os.path.dirname(_SCRIPTS_DIR)

# Platform patch line in YAML (align with config/global_atoms.yaml when bumping).
DEFAULT_HEADER_FORMAT_VERSION = "4.1.9"

from header_spec_v3_1 import (
    V4_HEADER_KEYS_ORDERED,
    emit_markdown_inner_from_header_dict,
    emit_python_header_block_lines_from_header_dict,
    build_edges_toon_path,
)


def _get_anchor_utc_14() -> str:
    tick = os.path.join(_REPO_ROOT, "bin", "tick.py")
    echo = os.path.join(_REPO_ROOT, "bin", "echo_anchor_utc.py")
    for path in (tick, echo):
        if not os.path.isfile(path):
            sys.stderr.write("[ERROR] Required script missing: %s\n" % path)
            sys.exit(1)
    try:
        subprocess.run(
            [sys.executable, tick],
            cwd=_REPO_ROOT,
            check=True,
        )
    except subprocess.CalledProcessError as e:
        sys.stderr.write(
            "[ERROR] bin/tick.py failed (exit %s)\n" % e.returncode
        )
        sys.exit(1)
    proc = subprocess.run(
        [sys.executable, echo],
        cwd=_REPO_ROOT,
        capture_output=True,
        text=True,
    )
    if proc.returncode != 0:
        sys.stderr.write(
            proc.stderr
            or "[ERROR] echo_anchor_utc.py failed. Run: python bin/tick.py\n"
        )
        sys.exit(1)
    u = (proc.stdout or "").strip()
    if len(u) != 14 or not u.isdigit():
        sys.stderr.write("Invalid anchor UTC from echo_anchor_utc.py\n")
        sys.exit(1)
    return u


def _repo_rel_path(path: str) -> str:
    ap = os.path.normpath(os.path.abspath(path))
    root = os.path.normpath(_REPO_ROOT)
    try:
        rel = os.path.relpath(ap, root)
    except ValueError:
        rel = path
    if rel.startswith(".."):
        sys.stderr.write(
            "[ERROR] Path must be inside repository root: %s\n" % root
        )
        sys.exit(1)
    return rel.replace("\\", "/")


def _memory_path_year_segment(trust_tier: str, calendar_year: int) -> int:
    """PRD 16 §8.1: canonical (verified) memory paths use YYYY-1000; other tiers use calendar year."""
    tt = (trust_tier or "canonical").strip().lower()
    if tt == "canonical":
        return calendar_year - 1000
    return calendar_year


def _slug_from_stem(stem: str) -> str:
    s = stem.lower().replace("_", "-")
    s = re.sub(r"[^a-z0-9-]+", "-", s)
    s = re.sub(r"-+", "-", s).strip("-")
    return s or "artifact"


def _detect_newline(body: str) -> str:
    """Prefer CRLF when the existing file uses it; else LF."""
    if not body:
        return "\n"
    if "\r\n" in body:
        return "\r\n"
    return "\n"


def _normalize_header_newlines(header: str, newline: str) -> str:
    if newline == "\r\n":
        return header.replace("\n", "\r\n")
    return header


def _build_header_dict(
    utc: str,
    path_from_lupopedia_root: str,
    title: str,
    slug: str,
    lupo_schema: str,
    artifact_type: str,
    artifact_kind: str,
    channel_key: str = "development",
    trust_tier: str = "canonical",
    federation_node_id: int = 0,
    use_https_web_path: bool = True,
    header_format_version: str = DEFAULT_HEADER_FORMAT_VERSION,
    summary: str = "Generated header",
    prd_cluster: str = "",
    thread_key: str = "",
    channel_index: str = "lupopedia",
    source_timestamp: str = "",
    edges_toon: str = "",
    external: bool = False,
) -> dict:
    y, m = utc[0:4], utc[4:6]
    actual_year = int(y)
    memory_year = _memory_path_year_segment(trust_tier, actual_year)
    scheme = "https" if use_https_web_path else "http"
    web_path = "%s://www.lupopedia.com/lupopedia/%s" % (scheme, path_from_lupopedia_root)
    tt = (trust_tier or "canonical").strip().lower()
    memory_toon = "memory/%s/%s/%s/%s/%s.toon" % (
        channel_key,
        tt,
        memory_year,
        m,
        slug,
    )
    transcript_jsonl = "%s/%s/%s" % (federation_node_id, channel_key, slug)
    ci = (channel_index or "lupopedia").strip()
    if external and ci == "lupopedia":
        ci = "external"
    st = (source_timestamp or "").strip() or None
    et = (edges_toon or "").strip() or None
    if ci != "lupopedia":
        if not st:
            sys.stderr.write(
                "[ERROR] external artifact requires --source-timestamp (ISO 8601)\n"
            )
            sys.exit(1)
        if not et:
            et = build_edges_toon_path(channel_key, thread_key, slug, utc, tt)
    hdr = {
        "header_format_version": header_format_version,
        "path_from_lupopedia_root": path_from_lupopedia_root,
        "web_path": web_path,
        "status": "active",
        "when_updated": utc,
        "trust_tier": tt,
        "questions_toon": None,
        "memory_toon": memory_toon,
        "atoms_toon": None,
        "transcript_jsonl": transcript_jsonl,
        "artifact_type": artifact_type,
        "artifact_kind": artifact_kind,
        "channel_key": channel_key,
        "federation_node_id": federation_node_id,
        "thread_key": thread_key or "",
        "lupopedia.schema": lupo_schema,
        "prd_cluster": prd_cluster or "",
        "title": title,
        "summary": summary,
        "edges_toon": et,
        "channel_index": ci,
        "source_timestamp": st,
    }
    for k in V4_HEADER_KEYS_ORDERED:
        if k not in hdr:
            hdr[k] = None
    return hdr


def _build_md_header(hdr: dict) -> str:
    inner = emit_markdown_inner_from_header_dict(hdr)
    return "---\n" + inner + "---\n"


def _dense_hash_header_line_list(hdr: dict) -> list:
    """``#`` grid (open/close fences + lupopedia.headers + 22 keys). Shared by Python and PHP."""
    return emit_python_header_block_lines_from_header_dict(hdr)


def _build_py_header(
    hdr: dict,
    include_shebang_line: bool = True,
) -> str:
    parts = []
    if include_shebang_line:
        parts.append("#!/usr/bin/env python3")
    parts.extend(_dense_hash_header_line_list(hdr))
    return "\n".join(parts) + "\n"


def _strip_legacy_php_lupopedia_docblock(lines_kept, php_line_index):
    """
    After ``<?php``, remove the first ``/*`` … ``*/`` block that contains ``lupopedia.headers:``
    (legacy star docblock or broken ``/**`` + bare YAML). Leaves files whose first comment is
    unrelated (no lupopedia.headers) unchanged.
    """
    if php_line_index < 0 or php_line_index + 1 >= len(lines_kept):
        return lines_kept
    out = list(lines_kept)
    start = php_line_index + 1
    while start < len(out) and out[start].strip() == "":
        start += 1
    if start >= len(out):
        return out
    if not out[start].lstrip().startswith("/*"):
        return out
    end = start
    blob_parts = []
    while end < len(out):
        blob_parts.append(out[end])
        if "*/" in out[end]:
            blob = "".join(blob_parts)
            if "lupopedia.headers:" in blob:
                del out[start : end + 1]
            break
        end += 1
    return out


def prepend_dense_header_to_php(
    body: str,
    hdr: dict,
) -> str:
    """
    Insert the v4.1.9 **25-line** ``#`` grid immediately after ``<?php`` (and after an existing
    ``#!/usr/bin/env php`` line when present). Used by ``main()`` and ``add_lupopedia_headers_everywhere.py``.
    """
    out_nl = _detect_newline(body) if body else "\n"
    grid_lines = _dense_hash_header_line_list(hdr)
    grid = _normalize_header_newlines("\n".join(grid_lines) + "\n", out_nl)
    lines_kept = body.splitlines(True)
    has_shebang = bool(
        lines_kept
        and lines_kept[0].lstrip("\ufeff ").startswith("#!")
        and "php" in lines_kept[0].lower()
    )
    if has_shebang:
        first = lines_kept[0]
        if len(lines_kept) > 1 and lines_kept[1].strip() == "<?php":
            lines_kept = _strip_legacy_php_lupopedia_docblock(lines_kept, 1)
            return first + lines_kept[1] + grid + "".join(lines_kept[2:])
        return first + "<?php" + out_nl + grid + "".join(lines_kept[1:])
    if lines_kept and lines_kept[0].lstrip("\ufeff ").strip().startswith("<?php"):
        lines_kept = _strip_legacy_php_lupopedia_docblock(lines_kept, 0)
        return lines_kept[0] + grid + "".join(lines_kept[1:])
    rest = body.lstrip("\n\r\ufeff \t")
    stub_open = "<?php" + out_nl + grid
    tail = rest if rest else ("// " + title + out_nl)
    return stub_open + tail


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Prepend PRD 16 header (header_format_version 4.1.2) or create a file (.md / .py / .php)."
    )
    parser.add_argument("path", help="File path (under repo root)")
    parser.add_argument(
        "--create",
        action="store_true",
        help="Create file if missing (default: error if missing)",
    )
    parser.add_argument("--title", default="", help="Header title (default: from filename)")
    parser.add_argument(
        "--summary",
        default="Generated header",
        help='lupopedia.headers summary (default: "Generated header")',
    )
    parser.add_argument(
        "--header-format-version",
        default=DEFAULT_HEADER_FORMAT_VERSION,
        help='YAML header_format_version string (default: %s)' % DEFAULT_HEADER_FORMAT_VERSION,
    )
    parser.add_argument(
        "--channel-key",
        default="development",
        help='channel_key for memory_toon / transcript_jsonl (default: development)',
    )
    parser.add_argument(
        "--trust-tier",
        default="canonical",
        choices=("seed", "canonical", "staging", "archive"),
        help="trust_tier in memory_toon (default: canonical); canonical uses PRD 16 section 8.1 year offset in path",
    )
    parser.add_argument(
        "--federation-node-id",
        type=int,
        default=0,
        help="federation_node_id in header and first segment of transcript_jsonl (default: 0)",
    )
    parser.add_argument(
        "--development",
        action="store_true",
        help="Use http:// for web_path; with --validate, pass --development to the universal validator",
    )
    parser.add_argument(
        "--lupopedia-schema",
        default="",
        help="lupopedia.schema value (default: by --kind)",
    )
    parser.add_argument(
        "--artifact-type",
        default="",
        help="artifact_type (defaults: md=documentation, py=implementation)",
    )
    parser.add_argument(
        "--artifact-kind",
        default="",
        help="artifact_kind (defaults: md=guide, py=tool)",
    )
    parser.add_argument(
        "--external",
        action="store_true",
        help="External/imported artifact: require --channel-index and --source-timestamp; generate edges_toon",
    )
    parser.add_argument(
        "--channel-index",
        default="lupopedia",
        help='channel_index (field 21): lupopedia|patreon|website|facebook|blog|external|imported',
    )
    parser.add_argument(
        "--source-timestamp",
        default="",
        help="source_timestamp ISO 8601 with Z or offset (required when --external or channel_index != lupopedia)",
    )
    parser.add_argument(
        "--edges-toon",
        default="",
        help="edges_toon path override (default: generated for external artifacts)",
    )
    parser.add_argument(
        "--prd-cluster",
        default="",
        help="prd_cluster string (field 17)",
    )
    parser.add_argument(
        "--thread-slug",
        default="",
        metavar="SLUG",
        help=(
            "Override slug for memory_toon stem and transcript_jsonl third segment "
            "(default: from filename stem, via _slug_from_stem)"
        ),
    )
    parser.add_argument(
        "--dry-run", action="store_true", help="Print header only; do not write"
    )
    parser.add_argument(
        "--backup",
        action="store_true",
        help="Before overwrite, copy existing file to <path>.bak",
    )
    parser.add_argument(
        "--validate",
        action="store_true",
        help="After write, run validate_lupopedia_headers_universal.py on the file",
    )
    parser.add_argument(
        "--skip-memory-sidecar",
        "--no-memory",
        dest="skip_memory_sidecar",
        action="store_true",
        help=(
            "Do not run generate_memory_from_header.ensure_memory_files after write "
            "(--no-memory is an alias). Default: create/update memory .json/.toon for memory_toon."
        ),
    )
    parser.add_argument(
        "--force-memory",
        action="store_true",
        help="Overwrite existing memory sidecars when ensure_memory_files runs (passes force=True)",
    )
    args = parser.parse_args()

    target = os.path.abspath(args.path)
    ext = os.path.splitext(target)[1].lower()

    if ext not in (".py", ".md", ".php"):
        sys.stderr.write(
            "[ERROR] Only .py, .md, and .php are supported in this tool (got %r).\n" % ext
        )
        return 1

    exists = os.path.isfile(target)
    if not exists and not args.create:
        sys.stderr.write(
            "[ERROR] File not found. Use --create to create it: %s\n" % target
        )
        return 1

    body = ""
    if exists:
        with open(target, "r", encoding="utf-8-sig") as f:
            body = f.read()

    utc = _get_anchor_utc_14()
    rel = _repo_rel_path(target)
    stem = os.path.splitext(os.path.basename(target))[0]
    slug = _slug_from_stem(stem)
    ts = (args.thread_slug or "").strip()
    if ts:
        slug = _slug_from_stem(ts)
    title = (args.title or "").strip() or stem.replace("_", " ").replace("-", " ").title()
    out_nl = _detect_newline(body) if exists else "\n"
    hfv = (args.header_format_version or DEFAULT_HEADER_FORMAT_VERSION).strip()
    ck = (args.channel_key or "development").strip()
    tt = (args.trust_tier or "canonical").strip()
    fnid = int(args.federation_node_id)
    use_https = not args.development

    hdr = _build_header_dict(
        utc,
        rel,
        title,
        slug,
        args.lupopedia_schema or ("documentation" if ext == ".md" else "implementation"),
        args.artifact_type or ("documentation" if ext == ".md" else "implementation"),
        args.artifact_kind or ("guide" if ext == ".md" else "tool"),
        channel_key=ck,
        trust_tier=tt,
        federation_node_id=fnid,
        use_https_web_path=use_https,
        header_format_version=hfv,
        summary=(args.summary or "Generated header").strip() or "Generated header",
        prd_cluster=(args.prd_cluster or "").strip(),
        thread_key="",
        channel_index=(args.channel_index or "lupopedia").strip(),
        source_timestamp=(args.source_timestamp or "").strip(),
        edges_toon=(args.edges_toon or "").strip(),
        external=bool(args.external),
    )

    if ext == ".md":
        header = _build_md_header(hdr)
        header = _normalize_header_newlines(header, out_nl)
        # Always peel all leading lupopedia.headers blocks before writing new header
        _, peeled_body = peel_leading_lupopedia_yaml_blocks(body)
        rest_md = peeled_body.lstrip("\n\r\ufeff \t")
        if rest_md:
            new_content = header + rest_md
        else:
            suffix = "# %s\n\n" % title
            if out_nl == "\r\n":
                suffix = suffix.replace("\n", "\r\n")
            new_content = header + suffix
    elif ext == ".php":
        new_content = prepend_dense_header_to_php(body, hdr)
    else:
        lines_kept = body.splitlines(True)
        has_shebang = bool(
            lines_kept and lines_kept[0].lstrip("\ufeff ").startswith("#!")
        )
        header = _build_py_header(hdr, include_shebang_line=not has_shebang)
        header = _normalize_header_newlines(header, out_nl)
        rest = body.lstrip("\n\r\ufeff \t")
        if has_shebang:
            new_content = lines_kept[0] + header + "".join(lines_kept[1:])
        else:
            stub = '"""\n%s\n"""\n' % title
            if out_nl == "\r\n":
                stub = stub.replace("\n", "\r\n")
            new_content = header + (rest if rest else stub)

    if args.dry_run:
        print(new_content[:4000])
        if len(new_content) > 4000:
            print("\n... [truncated]")
        return 0

    if args.backup and exists:
        bak = target + ".bak"
        shutil.copy2(target, bak)
        print("[BACKUP] %s" % bak)

    os.makedirs(os.path.dirname(target) or ".", exist_ok=True)
    with open(target, "w", encoding="utf-8", newline="") as f:
        f.write(new_content)
    print("[OK] Wrote LUPOPEDIA HEADERS: %s" % target)
    print(
        "     Validate: python scripts/validate_lupopedia_headers_universal.py %s"
        % rel
    )
    if not args.skip_memory_sidecar:
        try:
            from generate_memory_from_header import ensure_memory_files
        except ImportError:
            gpy = os.path.join(_SCRIPTS_DIR, "generate_memory_from_header.py")
            gcmd = [sys.executable, gpy, target]
            if args.force_memory:
                gcmd.append("--force")
            gproc = subprocess.run(gcmd, cwd=_REPO_ROOT)
            if gproc.returncode != 0:
                return gproc.returncode
        else:
            mem_rc = ensure_memory_files(
                target,
                force=bool(args.force_memory),
                check_only=False,
                quiet=True,
            )
            if mem_rc != 0:
                return mem_rc
            print(
                "     Memory: sidecar ensured (memory path from header); "
                "content_id stays null until import_content.py / CMS import"
            )
    if args.validate:
        vpy = os.path.join(_SCRIPTS_DIR, "validate_lupopedia_headers_universal.py")
        vcmd = [sys.executable, vpy, rel]
        if args.development:
            vcmd.append("--development")
        vproc = subprocess.run(vcmd, cwd=_REPO_ROOT)
        if vproc.returncode != 0:
            return vproc.returncode
    return 0


if __name__ == "__main__":
    sys.exit(main())
