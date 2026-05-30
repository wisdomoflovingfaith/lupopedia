#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "lupo-scripts/add_lupopedia_header_to_file.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/add_lupopedia_header_to_file.py"
#   status: "complete"
#   when_updated: "20260415080730"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/add-lupopedia-header-to-file.toon"
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

Timestamps: runs lupo-bin/tick.py then reads lupo-bin/echo_anchor_utc.py (temporal anchor).
Never uses guessed local time for header fields.

Usage:
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/file.py
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/script.php --title "My CLI"
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/file.md --title "My Doc"
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/new.md --create
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/file.md --validate --backup
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/x.py --trust-tier staging --federation-node-id 0
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/x.py --development --validate
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/x.md --thread-slug my-custom-slug
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/x.py --skip-memory-sidecar
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/x.py --no-memory
  python lupo-scripts/add_lupopedia_header_to_file.py path/to/x.py --force-memory

Batch (many .md / .py): python lupo-scripts/add_lupopedia_headers_everywhere.py --dry-run

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
DEFAULT_HEADER_FORMAT_VERSION = "4.1.2"
DEFAULT_PARENT_PRD_FOR_PY = "16"


def _get_anchor_utc_14() -> str:
    tick = os.path.join(_REPO_ROOT, "lupo-bin", "tick.py")
    echo = os.path.join(_REPO_ROOT, "lupo-bin", "echo_anchor_utc.py")
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
            "[ERROR] lupo-bin/tick.py failed (exit %s)\n" % e.returncode
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
            or "[ERROR] echo_anchor_utc.py failed. Run: python lupo-bin/tick.py\n"
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


def _build_md_header(
    utc: str,
    file_path_from_root: str,
    title: str,
    slug: str,
    lupo_schema: str,
    artifact_type: str,
    artifact_kind: str,
    content_parent_id: str,
    channel_key: str = "development",
    trust_tier: str = "canonical",
    federation_node_id: int = 0,
    use_https_web_path: bool = True,
    header_format_version: str = DEFAULT_HEADER_FORMAT_VERSION,
    summary: str = "Generated header",
) -> str:
    y, m = utc[0:4], utc[4:6]
    actual_year = int(y)
    memory_year = _memory_path_year_segment(trust_tier, actual_year)
    scheme = "https" if use_https_web_path else "http"
    web_path = "%s://www.lupopedia.com/lupopedia/%s" % (scheme, file_path_from_root)
    tt = (trust_tier or "canonical").strip().lower()
    memory_toon = "lupo-memory/%s/%s/%s/%s/%s.toon" % (
        channel_key,
        tt,
        memory_year,
        m,
        slug,
    )
    transcript_jsonl = "%s/%s/%s" % (federation_node_id, channel_key, slug)
    esc_hfv = header_format_version.replace('"', '\\"')
    if content_parent_id is None or str(content_parent_id).strip() == "":
        content_parent_id_yaml = "null"
    elif str(content_parent_id).strip().isdigit():
        content_parent_id_yaml = str(int(str(content_parent_id).strip()))
    else:
        content_parent_id_yaml = json.dumps(str(content_parent_id), ensure_ascii=True)
    inner = (
        "lupopedia.headers:\n"
        '  header_format_version: "%s"\n'
        '  file_path_from_root: "%s"\n'
        '  web_path: "%s"\n'
        '  status: "active"\n'
        '  when_updated: "%s"\n'
        '  trust_tier: "%s"\n'
        "  questions_toon: null\n"
        '  memory_toon: "%s"\n'
        "  atoms_toon: null\n"
        '  transcript_jsonl: "%s"\n'
        "  artifact_type: %s\n"
        "  artifact_kind: %s\n"
        '  channel_key: "%s"\n'
        "  federation_node_id: %d\n"
        '  thread_id: ""\n'
        "  content_id: null\n"
        "  content_parent_id: %s\n"
        '  content_slug: "%s"\n'
        "  default_collection_id: null\n"
        "  lupopedia.schema: %s\n"
        '  title: "%s"\n'
        '  summary: %s\n'
        % (
            esc_hfv,
            file_path_from_root,
            web_path,
            utc,
            tt.replace('"', '\\"'),
            # questions_toon is always null for new headers
            memory_toon,
            # atoms_toon is always null for new headers
            transcript_jsonl,
            artifact_type,
            artifact_kind,
            channel_key.replace('"', '\\"'),
            federation_node_id,
            content_parent_id_yaml,
            slug,
            lupo_schema,
            title.replace('"', '\\"'),
            json.dumps(summary, ensure_ascii=True),
        )
    )
    return "---\n" + inner.rstrip("\n") + "\n---\n"


def _dense_hash_header_line_list(
    utc: str,
    file_path_from_root: str,
    title: str,
    slug: str,
    lupo_schema: str,
    artifact_type: str,
    artifact_kind: str,
    content_parent_id: str,
    channel_key: str = "development",
    trust_tier: str = "canonical",
    federation_node_id: int = 0,
    use_https_web_path: bool = True,
    header_format_version: str = DEFAULT_HEADER_FORMAT_VERSION,
    summary: str = "Generated header",
) -> list:
    """25-line ``#`` grid (open/close fences + lupopedia.headers + 22 keys). Shared by Python and PHP."""
    y, m = utc[0:4], utc[4:6]
    actual_year = int(y)
    memory_year = _memory_path_year_segment(trust_tier, actual_year)
    scheme = "https" if use_https_web_path else "http"
    web_path = "%s://www.lupopedia.com/lupopedia/%s" % (scheme, file_path_from_root)
    tt = (trust_tier or "canonical").strip().lower()
    memory_toon = "lupo-memory/%s/%s/%s/%s/%s.toon" % (
        channel_key,
        tt,
        memory_year,
        m,
        slug,
    )
    transcript_jsonl = "%s/%s/%s" % (federation_node_id, channel_key, slug)
    esc_title = title.replace('"', '\\"')
    pp_raw = str(content_parent_id or "").strip()
    if not pp_raw:
        pp_line = "#   content_parent_id: null"
    elif pp_raw.isdigit():
        pp_line = "#   content_parent_id: %s" % str(int(pp_raw))
    else:
        pp_line = '#   content_parent_id: "%s"' % pp_raw.replace('"', '\\"')
    esc_slug = slug.replace('"', '\\"')
    return [
        "# ---------------------------------------------------------------------",
        "# lupopedia.headers:",
        '#   header_format_version: "%s"' % header_format_version.replace('"', '\\"'),
        '#   file_path_from_root: "%s"' % file_path_from_root,
        '#   web_path: "%s"' % web_path,
        '#   status: "complete"',
        '#   when_updated: "%s"' % utc,
        '#   trust_tier: "%s"' % tt.replace('"', '\\"'),
        "#   questions_toon: null",
        '#   memory_toon: "%s"' % memory_toon,
        "#   atoms_toon: null",
        '#   transcript_jsonl: "%s"' % transcript_jsonl,
        "#   artifact_type: %s" % artifact_type,
        "#   artifact_kind: %s" % artifact_kind,
        '#   channel_key: "%s"' % channel_key.replace('"', '\\"'),
        "#   federation_node_id: %s" % int(federation_node_id),
        '#   thread_id: ""',
        "#   content_id: null",
        pp_line,
        '#   content_slug: "%s"' % esc_slug,
        "#   default_collection_id: null",
        "#   lupopedia.schema: %s" % lupo_schema,
        '#   title: "%s"' % esc_title,
        "#   summary: %s" % json.dumps(summary, ensure_ascii=True),
        "# ---------------------------------------------------------------------",
    ]


def _build_py_header(
    utc: str,
    file_path_from_root: str,
    title: str,
    slug: str,
    lupo_schema: str,
    artifact_type: str,
    artifact_kind: str,
    content_parent_id: str,
    channel_key: str = "development",
    trust_tier: str = "canonical",
    federation_node_id: int = 0,
    use_https_web_path: bool = True,
    header_format_version: str = DEFAULT_HEADER_FORMAT_VERSION,
    include_shebang_line: bool = True,
    summary: str = "Generated header",
) -> str:
    parts = []
    if include_shebang_line:
        parts.append("#!/usr/bin/env python3")
    parts.extend(
        _dense_hash_header_line_list(
            utc,
            file_path_from_root,
            title,
            slug,
            lupo_schema,
            artifact_type,
            artifact_kind,
            content_parent_id,
            channel_key=channel_key,
            trust_tier=trust_tier,
            federation_node_id=federation_node_id,
            use_https_web_path=use_https_web_path,
            header_format_version=header_format_version,
            summary=summary,
        )
    )
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
    utc: str,
    rel: str,
    title: str,
    slug: str,
    lupo_schema: str,
    artifact_type: str,
    artifact_kind: str,
    content_parent_id: str,
    channel_key: str = "development",
    trust_tier: str = "canonical",
    federation_node_id: int = 0,
    use_https_web_path: bool = True,
    header_format_version: str = DEFAULT_HEADER_FORMAT_VERSION,
    summary: str = "Generated header",
) -> str:
    """
    Insert the v4.1.2 **25-line** ``#`` grid immediately after ``<?php`` (and after an existing
    ``#!/usr/bin/env php`` line when present). Used by ``main()`` and ``add_lupopedia_headers_everywhere.py``.
    """
    out_nl = _detect_newline(body) if body else "\n"
    grid_lines = _dense_hash_header_line_list(
        utc,
        rel,
        title,
        slug,
        lupo_schema,
        artifact_type,
        artifact_kind,
        content_parent_id,
        channel_key=channel_key,
        trust_tier=trust_tier,
        federation_node_id=federation_node_id,
        use_https_web_path=use_https_web_path,
        header_format_version=header_format_version,
        summary=summary,
    )
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
        "--parent-prd",
        "--parent-pk-id",
        dest="content_parent_id",
        default="",
        help='content_parent_id (default: "" for md, "%s" for py/php); --parent-pk-id is a legacy alias'
        % DEFAULT_PARENT_PRD_FOR_PY,
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
            "(--no-memory is an alias). Default: create/update lupo-memory .json/.toon for memory_toon."
        ),
    )
    parser.add_argument(
        "--force-memory",
        action="store_true",
        help="Overwrite existing lupo-memory sidecars when ensure_memory_files runs (passes force=True)",
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

    if ext == ".md":
        lupo_schema = args.lupopedia_schema or "documentation"
        atype = args.artifact_type or "documentation"
        akind = args.artifact_kind or "guide"
        content_parent_id = args.content_parent_id if args.content_parent_id != "" else ""
        header = _build_md_header(
            utc,
            rel,
            title,
            slug,
            lupo_schema,
            atype,
            akind,
            content_parent_id,
            channel_key=ck,
            trust_tier=tt,
            federation_node_id=fnid,
            use_https_web_path=use_https,
            header_format_version=hfv,
            summary=(args.summary or "Generated header").strip() or "Generated header",
        )
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
        lupo_schema = args.lupopedia_schema or "implementation"
        atype = args.artifact_type or "implementation"
        akind = args.artifact_kind or "tool"
        content_parent_id = (
            args.content_parent_id if args.content_parent_id != "" else DEFAULT_PARENT_PRD_FOR_PY
        )
        new_content = prepend_dense_header_to_php(
            body,
            utc,
            rel,
            title,
            slug,
            lupo_schema,
            atype,
            akind,
            content_parent_id,
            channel_key=ck,
            trust_tier=tt,
            federation_node_id=fnid,
            use_https_web_path=use_https,
            header_format_version=hfv,
            summary=(args.summary or "Generated header").strip() or "Generated header",
        )
    else:
        lupo_schema = args.lupopedia_schema or "implementation"
        atype = args.artifact_type or "implementation"
        akind = args.artifact_kind or "tool"
        content_parent_id = (
            args.content_parent_id if args.content_parent_id != "" else DEFAULT_PARENT_PRD_FOR_PY
        )
        lines_kept = body.splitlines(True)
        has_shebang = bool(
            lines_kept and lines_kept[0].lstrip("\ufeff ").startswith("#!")
        )
        header = _build_py_header(
            utc,
            rel,
            title,
            slug,
            lupo_schema,
            atype,
            akind,
            content_parent_id,
            channel_key=ck,
            trust_tier=tt,
            federation_node_id=fnid,
            use_https_web_path=use_https,
            header_format_version=hfv,
            include_shebang_line=not has_shebang,
            summary=(args.summary or "Generated header").strip() or "Generated header",
        )
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
        "     Validate: python lupo-scripts/validate_lupopedia_headers_universal.py %s"
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
                "     Memory: sidecar ensured (lupo-memory path from header); "
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
