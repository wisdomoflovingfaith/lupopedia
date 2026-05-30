#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "lupo-scripts/generate_memory_from_header.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/generate_memory_from_header.py"
#   status: "complete"
#   when_updated: "20260415080730"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/generate-memory-from-header.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/generate-memory-from-header"
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
#   title: "Generate lupo-memory sidecar from embedded LUPOPEDIA HEADERS"
#   summary: "Parse memory_toon via universal validator; write header_metadata .json/.toon; --check --batch --dry-run."
# ---------------------------------------------------------------------
"""
Create or verify **lupo-memory** sidecars (.json master + .toon mirror) for a source file's
``memory_toon`` (PRD 16 pairing discipline).

Does **not** insert **lupo_memory_edges** or **lupo_contents** rows (DB-first graph is separate:
``lib/db_memory_writer.DBMemoryWriter``, ``import_content.py``, runtime import). Sidecar JSON may carry
``transcript_jsonl`` / ``content_parent_id`` metadata and ``edges.outbound`` for mirrors and PRD links.
Legacy ``dialog_transcript`` headers accepted with WARN.

**Design (do not replace with ad-hoc YAML):** headers are parsed only through
``validate_lupopedia_headers_universal.py`` (front matter, Python envelope, PHP hash/star, JS envelope).
Sidecars use the repo ``type: header_metadata`` shape (sorted JSON, ``ensure_ascii=True``), not a
standalone ``version``/``generated`` ISO blob. **No** optional full-body extraction here (keeps mirrors
small; use import/CMS paths for content rows).

Importable: ``ensure_memory_files(..., batch_mode=False)`` — in **batch_mode**, unparseable headers
return **0** (skip) and validator **print** noise is suppressed for Python/PHP/JS envelope checks.

Usage:
  python lupo-scripts/generate_memory_from_header.py lupo-docs/prd/16_lupopedia_headers.md
  python lupo-scripts/generate_memory_from_header.py lupo-scripts/foo.py --force
  python lupo-scripts/generate_memory_from_header.py path/to/file.md --check
  python lupo-scripts/generate_memory_from_header.py --batch --dry-run
  python lupo-scripts/generate_memory_from_header.py --batch --quiet
"""

from __future__ import annotations

import argparse
import contextlib
import importlib.util
import io
import json
import os
import subprocess
import sys
import glob

_SCRIPTS_DIR = os.path.dirname(os.path.abspath(__file__))
_REPO_ROOT = os.path.dirname(_SCRIPTS_DIR)


def _load_validator_module():
    path = os.path.join(_SCRIPTS_DIR, "validate_lupopedia_headers_universal.py")
    spec = importlib.util.spec_from_file_location("_lupo_hdr_validate", path)
    if spec is None or spec.loader is None:
        raise RuntimeError("Cannot load validate_lupopedia_headers_universal.py")
    mod = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(mod)
    return mod


def _extract_headers_dict(file_path: str, content: str, silent=False):
    """Return lupopedia.headers dict or None."""
    try:
        import yaml
    except ImportError:
        if not silent:
            sys.stderr.write("[ERROR] PyYAML is required.\n")
        return None

    vlu = _load_validator_module()
    ext = os.path.splitext(file_path)[1].lower()
    rel_fp = file_path.replace("\\", "/")

    if ext == ".md":
        data, err = vlu.parse_front_matter_yaml(content)
        if err or not isinstance(data, dict):
            if not silent:
                sys.stderr.write("[ERROR] %s: %s\n" % (rel_fp, err or "YAML not a mapping"))
            return None
        hdr = data.get("lupopedia.headers")
        return hdr if isinstance(hdr, dict) else None

    lines = content.replace("\r\n", "\n").split("\n")

    if ext == ".py":
        _out_cm = (
            contextlib.redirect_stdout(io.StringIO()) if silent else contextlib.nullcontext()
        )
        with _out_cm:
            ok, _has_shebang, yaml_inner = vlu.validate_python_header_envelope(
                lines, rel_fp, reject_legacy_envelope=False, suppress_legacy_envelope_warn=True
            )
        if not ok or not yaml_inner:
            if not silent:
                sys.stderr.write("[ERROR] %s: could not read Python header envelope\n" % rel_fp)
            return None
        data = yaml.safe_load(yaml_inner)
    elif ext == ".php":
        mode, yaml_inner, _idx = vlu._php_try_hash_comment_header(
            lines, rel_fp, reject_legacy_envelope=False, suppress_legacy_envelope_warn=True
        )
        if mode == "use_star":
            _out_cm = (
                contextlib.redirect_stdout(io.StringIO()) if silent else contextlib.nullcontext()
            )
            with _out_cm:
                ok_env, yaml_inner, _fidx = vlu.validate_php_header_envelope(
                    lines, rel_fp, reject_legacy_envelope=False, suppress_legacy_envelope_warn=True
                )
            if not ok_env or not yaml_inner:
                if not silent:
                    sys.stderr.write("[ERROR] %s: could not read PHP header\n" % rel_fp)
                return None
        elif mode == "bad":
            if not silent:
                sys.stderr.write("[ERROR] %s: invalid PHP hash header block\n" % rel_fp)
            return None
        elif mode != "ok":
            if not silent:
                sys.stderr.write("[ERROR] %s: unexpected PHP header mode %r\n" % (rel_fp, mode))
            return None
        data = yaml.safe_load(yaml_inner)
    elif ext == ".js":
        _out_cm = (
            contextlib.redirect_stdout(io.StringIO()) if silent else contextlib.nullcontext()
        )
        with _out_cm:
            ok_env, yaml_inner = vlu.validate_js_header_envelope(
                lines,
                rel_fp,
                reject_legacy_envelope=False,
                suppress_legacy_envelope_warn=True,
            )
        if not ok_env or not yaml_inner:
            if not silent:
                sys.stderr.write("[ERROR] %s: could not read JS header envelope\n" % rel_fp)
            return None
        data = yaml.safe_load(yaml_inner)
    else:
        if not silent:
            sys.stderr.write(
                "[ERROR] Unsupported extension %r (use .md, .py, .php, .js)\n" % ext
            )
        return None

    if not isinstance(data, dict):
        return None
    hdr = data.get("lupopedia.headers")
    return hdr if isinstance(hdr, dict) else None


def _repo_rel_path_silent(path: str):
    """Repo-relative posix path, or None if outside repo."""
    ap = os.path.normpath(os.path.abspath(path))
    root = os.path.normpath(_REPO_ROOT)
    try:
        rel = os.path.relpath(ap, root)
    except ValueError:
        return None
    if rel.startswith(".."):
        return None
    return rel.replace("\\", "/")


def _repo_rel_path(path: str) -> str:
    rel = _repo_rel_path_silent(path)
    if rel is None:
        sys.stderr.write("[ERROR] Path must be inside repository root: %s\n" % _REPO_ROOT)
        sys.exit(2)
    return rel


def _batch_paths_from_git_grep():
    """
    Return absolute paths under _REPO_ROOT for tracked files mentioning memory_toon:.
    Uses git grep so we do not walk ignored trees.
    """
    seen = set()
    paths = []
    for grep_term in ("memory_toon:",):
        cmd = [
            "git",
            "grep",
            "-l",
            grep_term,
            "--",
            "*.md",
            "*.py",
            "*.php",
            "*.js",
        ]
        proc = subprocess.Popen(
            cmd,
            cwd=_REPO_ROOT,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            universal_newlines=True,
        )
        out, err = proc.communicate()
        if proc.returncode not in (0, 1):
            sys.stderr.write(
                "[ERROR] git grep failed (rc=%s): %s\n" % (proc.returncode, (err or "").strip())
            )
            return None
        for line in (out or "").splitlines():
            line = line.strip().replace("\\", "/")
            if not line:
                continue
            ap = os.path.normpath(os.path.join(_REPO_ROOT, line.replace("/", os.sep)))
            if not os.path.isfile(ap) or ap in seen:
                continue
            # Narrow: avoid docs that only mention memory_toon in prose.
            try:
                with open(ap, "r", encoding="utf-8-sig") as fh:
                    probe = fh.read(12000)
            except Exception:
                continue
            if "lupopedia.headers" not in probe:
                continue
            if "memory_toon:" not in probe:
                continue
            seen.add(ap)
            paths.append(ap)
    return paths


def _parse_nullable_int(value):
    if value is None:
        return None
    raw = str(value).strip()
    if raw == "" or raw.lower() == "null":
        return None
    if raw.isdigit():
        return int(raw)
    return None


def _build_sidecar_doc(hdr: dict, source_rel: str) -> dict:
    """Minimal header_metadata document (JSON-serializable), aligned with other 1026/04 sidecars."""
    mk = str(hdr.get("memory_toon") or "").strip()
    wu = str(hdr.get("when_updated") or "").strip()
    content_id_val = _parse_nullable_int(hdr.get("content_id"))
    content_parent_id_val = _parse_nullable_int(hdr.get("content_parent_id"))
    default_collection_id_val = _parse_nullable_int(hdr.get("default_collection_id"))
    content_slug = str(hdr.get("content_slug") or "").strip()
    outbound = []
    fpr = str(hdr.get("file_path_from_root") or "").strip()
    if fpr:
        outbound.append(
            {
                "to": fpr,
                "type": "mirrors",
                "weight": 1.0,
                "reason": "Sidecar generated from file header (generate_memory_from_header.py)",
                "source": "generate_memory_from_header",
            }
        )
    if content_id_val is not None:
        outbound.append(
            {
                "to": "lupo-contents/content_id/%d" % content_id_val,
                "type": "references",
                "weight": 0.95,
                "reason": "content_id linkage for source file",
                "source": "generate_memory_from_header",
            }
        )
    if content_parent_id_val is not None:
        pat = os.path.join(
            _REPO_ROOT, "lupo-docs", "prd", "%s_*.md" % str(content_parent_id_val).zfill(2)
        )
        hits = sorted(glob.glob(pat))
        if hits:
            chosen = None
            expected_primary = os.path.join(
                _REPO_ROOT,
                "lupo-docs",
                "prd",
                "%s_lupopedia_headers.md" % str(content_parent_id_val).zfill(2),
            )
            if expected_primary in hits:
                chosen = expected_primary
            else:
                # Fallback: deterministic first match.
                chosen = hits[0]
            prd_rel = _repo_rel_path(chosen)
            outbound.append(
                {
                    "to": prd_rel,
                    "type": "references",
                    "weight": 0.9,
                    "reason": "content_parent_id -> PRD file",
                    "source": "generate_memory_from_header",
                }
            )
    # Accept legacy dialog_transcript during migration; prefer transcript_jsonl
    raw_dt = hdr.get("transcript_jsonl") if "transcript_jsonl" in hdr else hdr.get("dialog_transcript")
    dt = str(raw_dt or "").strip()

    return {
        "type": "header_metadata",
        "memory_toon": mk,
        "source_file_path_from_root": source_rel,
        "purpose": str(hdr.get("summary") or "Memory sidecar").strip(),
        "tags": [
            str(hdr.get("artifact_type") or "documentation").strip(),
            str(hdr.get("trust_tier") or "canonical").strip(),
        ],
        "header_bridge": {
            "content_id": content_id_val,
            "content_parent_id": content_parent_id_val,
            "content_slug": content_slug,
            "default_collection_id": default_collection_id_val,
            "transcript_jsonl": dt,
            "note": "DB lupo_memory_edges / lupo_contents are not written here; use import_content.py or DBMemoryWriter.",
        },
        "edges": {"outbound": outbound},
        "footer": {
            "last_verified": wu if len(wu) == 14 and wu.isdigit() else "",
            "last_verified_by": "generate_memory_from_header",
            "last_verified_by_actor_id": 102,
        },
    }


def _write_json_and_toon(doc: dict, json_abs: str, toon_abs: str, force: bool, quiet: bool) -> bool:
    """Return True if any file was written."""
    os.makedirs(os.path.dirname(json_abs), exist_ok=True)
    text = json.dumps(doc, indent=2, ensure_ascii=True, sort_keys=True) + "\n"
    wrote = False
    if force or not os.path.isfile(json_abs):
        with open(json_abs, "w", encoding="utf-8", newline="\n") as f:
            f.write(text)
        if not quiet:
            print("[OK] Wrote %s" % json_abs.replace("\\", "/"))
        wrote = True
    elif not quiet:
        print("[SKIP] exists: %s" % json_abs.replace("\\", "/"))
    if force or not os.path.isfile(toon_abs):
        with open(toon_abs, "w", encoding="utf-8", newline="\n") as f:
            f.write(text)
        if not quiet:
            print("[OK] Wrote %s" % toon_abs.replace("\\", "/"))
        wrote = True
    elif not quiet:
        print("[SKIP] exists: %s" % toon_abs.replace("\\", "/"))
    return wrote


def ensure_memory_files(
    file_path: str,
    force: bool = False,
    check_only: bool = False,
    quiet: bool = False,
    dry_run: bool = False,
    batch_mode: bool = False,
) -> int:
    """
    Create or verify lupo-memory .json + .toon for *file_path*'s embedded header.

    Returns 0 on success, 1 on failure (parse error, missing files in --check mode,
    or path outside repo).
    """
    target = os.path.abspath(file_path)
    if not os.path.isfile(target):
        sys.stderr.write("[ERROR] Not a file: %s\n" % target)
        return 1

    with open(target, "r", encoding="utf-8-sig") as f:
        content = f.read()

    hdr = _extract_headers_dict(target, content, silent=bool(batch_mode))
    if hdr is None:
        return 0 if batch_mode else 1

    mk = str(hdr.get("memory_toon") or "").strip()
    if not mk.endswith(".toon"):
        if not quiet:
            print("[OK] No .toon memory_toon; nothing to do: %r" % mk)
        return 0

    rel_source = _repo_rel_path_silent(target)
    if rel_source is None:
        sys.stderr.write("[ERROR] Path must be inside repository root.\n")
        return 1

    toon_abs = os.path.normpath(os.path.join(_REPO_ROOT, mk.replace("/", os.sep)))
    json_rel = mk[:-5] + ".json"
    json_abs = os.path.normpath(os.path.join(_REPO_ROOT, json_rel.replace("/", os.sep)))

    tt = str(hdr.get("trust_tier") or "").strip().lower()
    need_json = tt in ("seed", "canonical")

    if check_only:
        ok = os.path.isfile(toon_abs)
        if not ok:
            sys.stderr.write(
                "[ERROR] Missing .toon for memory_toon: %s\n  Expected: %s\n"
                % (mk, toon_abs.replace("\\", "/"))
            )
            return 1
        if need_json and not os.path.isfile(json_abs):
            sys.stderr.write(
                "[ERROR] Missing JSON master for trust_tier=%s: %s\n  Expected: %s\n"
                % (tt, mk, json_abs.replace("\\", "/"))
            )
            return 1
        if not quiet:
            print("[OK] memory sidecar present: %s" % mk)
        return 0

    if dry_run:
        if not quiet:
            if need_json:
                print(
                    "[DRY-RUN] Would ensure .json + .toon for %s -> %s"
                    % (rel_source, mk)
                )
            else:
                print(
                    "[DRY-RUN] Would ensure .toon only (trust_tier=%s) for %s -> %s"
                    % (tt, rel_source, mk)
                )
        return 0

    doc = _build_sidecar_doc(hdr, rel_source)
    if not need_json:
        if force or not os.path.isfile(toon_abs):
            os.makedirs(os.path.dirname(toon_abs), exist_ok=True)
            text = json.dumps(doc, indent=2, ensure_ascii=True, sort_keys=True) + "\n"
            with open(toon_abs, "w", encoding="utf-8", newline="\n") as f:
                f.write(text)
            if not quiet:
                print("[OK] Wrote %s (trust_tier=%s: .toon only)" % (toon_abs.replace("\\", "/"), tt))
        elif not quiet:
            print("[SKIP] exists: %s" % toon_abs.replace("\\", "/"))
        return 0

    _write_json_and_toon(doc, json_abs, toon_abs, force, quiet)
    if not quiet:
        print(
            "     Validate: python lupo-scripts/validate_lupopedia_headers_universal.py %s --strict-memory-pair"
            % rel_source
        )
    return 0


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Generate or verify lupo-memory .json + .toon for a file's memory_toon."
    )
    parser.add_argument(
        "path",
        nargs="?",
        default=None,
        help="Repo-relative or absolute path to .md / .py / .php / .js with LUPOPEDIA HEADERS",
    )
    parser.add_argument(
        "--batch",
        action="store_true",
        help="Process all tracked files that contain memory_toon: (git grep -l)",
    )
    parser.add_argument(
        "--check",
        action="store_true",
        help="Exit 1 if .toon or (for seed|canonical) .json is missing; do not write",
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Parse and report what would be written; do not write (no-op with --check)",
    )
    parser.add_argument(
        "--force",
        action="store_true",
        help="Overwrite existing sidecar files",
    )
    parser.add_argument(
        "--quiet",
        action="store_true",
        help="Suppress non-error stdout (batch mode prints failures only)",
    )
    args = parser.parse_args()

    path_nonempty = args.path is not None and str(args.path).strip() != ""
    if args.batch and path_nonempty:
        sys.stderr.write("[ERROR] --batch does not take a path argument.\n")
        parser.print_help()
        return 2
    if not args.batch and not path_nonempty:
        sys.stderr.write("[ERROR] Missing path (or use --batch).\n")
        parser.print_help()
        return 2

    if bool(args.batch):
        paths = _batch_paths_from_git_grep()
        if paths is None:
            return 1
        if not paths:
            if not args.quiet:
                print("[OK] No tracked files matched memory_toon: patterns.")
            return 0
        worst = 0
        for ap in sorted(paths):
            rc = ensure_memory_files(
                ap,
                force=bool(args.force),
                check_only=bool(args.check),
                quiet=bool(args.quiet),
                dry_run=bool(args.dry_run),
                batch_mode=True,
            )
            if rc != 0:
                worst = rc
        if not args.quiet:
            print("[OK] batch: %d file(s), exit %d" % (len(paths), worst))
        return worst

    return ensure_memory_files(
        args.path,
        force=bool(args.force),
        check_only=bool(args.check),
        quiet=bool(args.quiet),
        dry_run=bool(args.dry_run) and not bool(args.check),
    )


if __name__ == "__main__":
    sys.exit(main())
