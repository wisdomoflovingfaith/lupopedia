#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "lupo-scripts/batch_validate_prd_headers.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/batch_validate_prd_headers.py"
#   status: "complete"
#   when_updated: "20260411043037"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/batch-validate-prd-headers.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/batch-validate-prd-headers"
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
#   title: "Batch validate PRD and docs headers (universal validator)"
#   summary: "Batch runner; --migrate-legacy; --strict-header; --format for validator --type"
# ---------------------------------------------------------------------
"""
Batch-run validate_lupopedia_headers_universal.py on numbered PRD markdown files
(lupo-docs/prd/[0-9][0-9]_*.md), on all lupo-docs/**/*.md (--all-md), and optionally
on lupo-scripts/**/*.py (--include-py).

Exits 1 if any file fails. Uses one subprocess per file with optional timeout.
Optional parallel runs (--jobs > 1); --fail-fast forces sequential execution.

Usage (from repo root):
  python lupo-scripts/batch_validate_prd_headers.py
  python lupo-scripts/batch_validate_prd_headers.py --check-links
  python lupo-scripts/batch_validate_prd_headers.py --check-db
  python lupo-scripts/batch_validate_prd_headers.py --all-md --dry-run-list
  python lupo-scripts/batch_validate_prd_headers.py --all-md --verbose --timeout 120
  python lupo-scripts/batch_validate_prd_headers.py --development
  python lupo-scripts/batch_validate_prd_headers.py --strict-memory-pair
  python lupo-scripts/batch_validate_prd_headers.py --strict
  python lupo-scripts/batch_validate_prd_headers.py --include-py --jobs 4
  python lupo-scripts/batch_validate_prd_headers.py --extensions .md,.pseudo.md --all-md
  python lupo-scripts/batch_validate_prd_headers.py --report lupo-logs/header-batch-report.json
  python lupo-scripts/batch_validate_prd_headers.py --all-md --migrate-legacy
  python lupo-scripts/batch_validate_prd_headers.py --all-md --strict-header
  python lupo-scripts/batch_validate_prd_headers.py path/to/file.php --format php
"""
from __future__ import annotations

import argparse
import glob
import json
import os
import subprocess
import sys
import threading
import time
from concurrent.futures import ThreadPoolExecutor, as_completed
from pathlib import Path


def _repo_root() -> Path:
    return Path(__file__).resolve().parent.parent


def _normalize_extensions(spec: str) -> list[str]:
    out: list[str] = []
    for part in spec.split(","):
        part = part.strip().lower()
        if not part:
            continue
        if not part.startswith("."):
            part = "." + part
        out.append(part)
    return out if out else [".md"]


def _collect_files(
    root: Path,
    all_md: bool,
    extensions: list[str],
    include_py: bool,
) -> list[str]:
    root = root.resolve()
    seen: set[str] = set()
    rels: list[str] = []

    def add_paths(paths: list[str]) -> None:
        for p in paths:
            try:
                rel = str(Path(p).resolve().relative_to(root)).replace("\\", "/")
            except ValueError:
                continue
            if rel not in seen:
                seen.add(rel)
                rels.append(rel)

    for ext in extensions:
        if all_md:
            pattern = str(root / "lupo-docs" / "**" / ("*" + ext))
            add_paths(glob.glob(pattern, recursive=True))
        else:
            pattern = str(root / "lupo-docs" / "prd" / ("[0-9][0-9]_*" + ext))
            add_paths(glob.glob(pattern))

    if include_py:
        pattern = str(root / "lupo-scripts" / "**" / "*.py")
        add_paths(glob.glob(pattern, recursive=True))

    return sorted(rels)


def _build_cmd(validator: Path, rel: str, args: argparse.Namespace) -> list[str]:
    cmd = [sys.executable, str(validator), rel, "--quiet"]
    if getattr(args, "validator_type", "auto") != "auto":
        cmd.extend(["--type", args.validator_type])
    if args.check_links:
        cmd.append("--check-links")
    if args.check_db:
        cmd.append("--check-db")
    if args.development:
        cmd.append("--development")
    if args.strict_memory_pair:
        cmd.append("--strict-memory-pair")
    if getattr(args, "strict_memory_year", False):
        cmd.append("--strict-memory-year")
    if getattr(args, "reject_legacy_envelope", False):
        cmd.append("--reject-legacy-envelope")
    return cmd


def _migrate_legacy_one(root: Path, rel: str, backup: bool) -> tuple[bool, str]:
    """
    Rewrite v4.0.0 envelopes to dense v4.0.99. Returns (ok, message).
    """
    path = root / rel.replace("/", os.sep)
    if not path.is_file():
        return False, "not a file"
    scripts_dir = root / "lupo-scripts"
    if str(scripts_dir) not in sys.path:
        sys.path.insert(0, str(scripts_dir))

    if rel.lower().endswith(".md"):
        import normalize_lupopedia_md_header_25 as norm  # noqa: WPS433

        r = norm.normalize_file(
            str(path),
            dry_run=False,
            backup=backup,
            verbose=False,
            target_version="4.0.99",
        )
        if r == "skip_error":
            return False, "markdown migrate skip_error"
        # skip_multi: multiple leading YAML blocks — not auto-migrated here
        return True, r

    if rel.lower().endswith(".py"):
        import normalize_lupopedia_md_header_25 as norm  # noqa: WPS433

        r = norm.migrate_python_file_to_dense(
            str(path),
            dry_run=False,
            backup=backup,
            verbose=False,
        )
        if r == "skip_error":
            return False, "python migrate skip_error"
        return True, r

    return True, "skip (not .md/.py)"


def _run_one(
    validator: Path,
    rel: str,
    args: argparse.Namespace,
    root: Path,
) -> tuple[str, bool, str, int | None]:
    cmd = _build_cmd(validator, rel, args)
    try:
        r = subprocess.run(
            cmd,
            capture_output=True,
            text=True,
            timeout=args.timeout,
            cwd=str(root),
        )
    except subprocess.TimeoutExpired:
        return rel, False, "TIMEOUT after %ds" % args.timeout, None
    out = (r.stdout or "") + (r.stderr or "")
    return rel, r.returncode == 0, out, r.returncode


def main() -> int:
    parser = argparse.ArgumentParser(
        description=(
            "Run universal header validator on numbered PRD markdown files "
            "(default), or on all lupo-docs/**/* with matching extensions (--all-md), "
            "and optionally lupo-scripts/**/*.py (--include-py)."
        )
    )
    parser.add_argument(
        "--check-links",
        action="store_true",
        help="Pass through to validate_lupopedia_headers_universal.py (edge targets)",
    )
    parser.add_argument(
        "--check-db",
        action="store_true",
        help="Pass through to validate_lupopedia_headers_universal.py (DB sync when configured)",
    )
    parser.add_argument(
        "--development",
        action="store_true",
        help="Pass through: allow http:// web_path; relax HDR_EMPTY_BODY / JSON-TOON pairing per validator",
    )
    parser.add_argument(
        "--strict-memory-pair",
        "--strict",
        dest="strict_memory_pair",
        action="store_true",
        help=(
            "Pass through: require JSON master next to on-disk .toon for seed/canonical "
            "(--strict is an alias)"
        ),
    )
    parser.add_argument(
        "--strict-memory-year",
        action="store_true",
        help="Pass through: enforce PRD 16 §8.1 canonical memory_key year segment (when_updated year − 1000)",
    )
    parser.add_argument(
        "--strict-header",
        dest="reject_legacy_envelope",
        action="store_true",
        help=(
            "Pass --reject-legacy-envelope to the universal validator (fail legacy v4.0.0 blank 23–24). "
            "Does not change --strict-memory-pair (--strict)."
        ),
    )
    parser.add_argument(
        "--migrate-legacy",
        action="store_true",
        help=(
            "Before validation: rewrite Markdown/Python headers to dense v4.1.0 (22 keys, pk_* names, "
            "header_format_version 4.1.0). Uses normalize_lupopedia_md_header_25 (md) and python migrator (py)."
        ),
    )
    parser.add_argument(
        "--migrate-backup",
        action="store_true",
        help="With --migrate-legacy: write path.bak before each modified file",
    )
    parser.add_argument(
        "--all-md",
        action="store_true",
        help="Validate under lupo-docs/ recursively (extensions from --extensions), not only numbered PRDs",
    )
    parser.add_argument(
        "--include-py",
        action="store_true",
        help="Also validate every *.py under lupo-scripts/ (recursive)",
    )
    parser.add_argument(
        "--format",
        dest="validator_type",
        choices=("auto", "md", "yaml", "py", "php", "js"),
        default="auto",
        metavar="TYPE",
        help=(
            "Pass through to universal validator --type when not auto (override extension detection; "
            "use if a path is mis-detected)"
        ),
    )
    parser.add_argument(
        "--extensions",
        default=".md",
        metavar="LIST",
        help="Comma-separated suffixes for PRD or --all-md globs (default: .md). Example: .md,.pseudo.md",
    )
    parser.add_argument(
        "--jobs",
        type=int,
        default=1,
        metavar="N",
        help="Parallel worker threads (default: 1). Ignored when --fail-fast is set.",
    )
    parser.add_argument(
        "--fail-fast",
        action="store_true",
        help="Stop after the first failure (forces sequential execution)",
    )
    parser.add_argument(
        "--no-progress",
        action="store_true",
        help="Do not print [i/total] progress lines during the run",
    )
    parser.add_argument(
        "--report",
        metavar="PATH",
        help="Write JSON summary (paths, pass/fail counts, failures snippet) to this file",
    )
    parser.add_argument(
        "--dry-run-list",
        action="store_true",
        help="Print paths that would be validated, then exit 0 (no subprocess)",
    )
    parser.add_argument(
        "-v",
        "--verbose",
        action="store_true",
        help="Print [OK] for each file that passes",
    )
    parser.add_argument(
        "--timeout",
        type=int,
        default=120,
        metavar="SEC",
        help="Per-file subprocess timeout in seconds (default: 120)",
    )
    parser.add_argument(
        "--max-error-lines",
        type=int,
        default=30,
        metavar="N",
        help="Max lines of combined stdout/stderr to print per failure (default: 30)",
    )
    args = parser.parse_args()

    root = _repo_root()
    os.chdir(root)

    validator = root / "lupo-scripts" / "validate_lupopedia_headers_universal.py"
    if not validator.is_file():
        sys.stderr.write("[ERROR] Validator script not found: %s\n" % validator)
        return 1

    extensions = _normalize_extensions(args.extensions)
    files = _collect_files(root, args.all_md, extensions, args.include_py)
    if args.dry_run_list:
        for p in files:
            print(p)
        print("[DRY-RUN] %d path(s) would be validated" % len(files))
        return 0

    if not files:
        print("No files matched; nothing to do.")
        return 0

    if args.migrate_legacy:
        mig_ok = 0
        mig_fail: list[str] = []
        for rel in files:
            if not (
                rel.lower().endswith(".md")
                or (args.include_py and rel.lower().endswith(".py"))
            ):
                continue
            ok_m, msg = _migrate_legacy_one(root, rel, backup=bool(args.migrate_backup))
            if ok_m:
                mig_ok += 1
            else:
                mig_fail.append("%s (%s)" % (rel, msg))
        print(
            "[MIGRATE] legacy->v4.0.99: processed %d path(s), failures %d"
            % (mig_ok, len(mig_fail))
        )
        if mig_fail:
            cap = 25
            for line in mig_fail[:cap]:
                print("[MIGRATE-FAIL] %s" % line)
            if len(mig_fail) > cap:
                print("... [%d more migrate failures omitted]" % (len(mig_fail) - cap))

    failed: list[tuple[str, str, int | None]] = []
    t0 = time.perf_counter()
    total = len(files)
    jobs = 1 if args.fail_fast else max(1, args.jobs)
    show_progress = not args.no_progress and not args.verbose and total > 1

    if args.fail_fast:
        for i, rel in enumerate(files, 1):
            if show_progress:
                print("[%d/%d] %s" % (i, total, rel))
            rel_out, ok, out, code = _run_one(validator, rel, args, root)
            if not ok:
                failed.append((rel_out, out, code))
                ec = "?" if code is None else str(code)
                print("[FAIL] %s (exit %s)" % (rel_out, ec))
                break
            if args.verbose:
                print("[OK]   %s" % rel_out)
    elif jobs == 1:
        for i, rel in enumerate(files, 1):
            if show_progress:
                print("[%d/%d] %s" % (i, total, rel))
            rel_out, ok, out, code = _run_one(validator, rel, args, root)
            if not ok:
                failed.append((rel_out, out, code))
                ec = "?" if code is None else str(code)
                print("[FAIL] %s (exit %s)" % (rel_out, ec))
            elif args.verbose:
                print("[OK]   %s" % rel_out)
    else:
        done_lock = threading.Lock()
        done_count = 0

        def worker(rel: str) -> tuple[str, bool, str, int | None]:
            return _run_one(validator, rel, args, root)

        with ThreadPoolExecutor(max_workers=jobs) as ex:
            futures = {ex.submit(worker, rel): rel for rel in files}
            for fut in as_completed(futures):
                rel_out, ok, out, code = fut.result()
                with done_lock:
                    done_count += 1
                    if show_progress:
                        print("[%d/%d] done (last: %s)" % (done_count, total, rel_out))
                if not ok:
                    failed.append((rel_out, out, code))
                    ec = "?" if code is None else str(code)
                    print("[FAIL] %s (exit %s)" % (rel_out, ec))
                elif args.verbose:
                    print("[OK]   %s" % rel_out)

    elapsed = time.perf_counter() - t0
    ok_count = total - len(failed)
    scope_parts: list[str] = []
    if args.all_md:
        scope_parts.append("all lupo-docs *%s" % ",".join(extensions))
    else:
        scope_parts.append("numbered PRDs *%s" % ",".join(extensions))
    if args.include_py:
        scope_parts.append("lupo-scripts *.py")
    label = "Header validator (%s)" % " + ".join(scope_parts)
    print(
        "\n%s: %d files, %d OK, %d FAIL, %.2fs (jobs=%d)"
        % (label, total, ok_count, len(failed), elapsed, jobs)
    )

    for item in failed:
        rel, out, code = item
        print("\n[FAIL] %s" % rel)
        if code is None:
            print("(subprocess error or timeout)")
        else:
            print("(exit %d)" % code)
        text = out.strip()
        if not text:
            print("(no output)")
            continue
        lines = text.splitlines()
        cap = max(1, args.max_error_lines)
        shown = lines[:cap]
        print("\n".join(shown))
        if len(lines) > cap:
            print("... [%d more lines truncated]" % (len(lines) - cap))

    if args.report:
        report_path = Path(args.report)
        try:
            report_path.parent.mkdir(parents=True, exist_ok=True)
        except OSError:
            pass
        payload = {
            "timestamp_ymdhis": time.strftime("%Y%m%d%H%M%S", time.gmtime()),
            "total": total,
            "passed": ok_count,
            "failed": len(failed),
            "elapsed_seconds": round(elapsed, 3),
            "jobs": jobs,
            "failures": [
                {
                    "file": rel,
                    "exit_code": code,
                    "output_excerpt": (out or "")[:2000],
                }
                for rel, out, code in failed
            ],
        }
        with open(report_path, "w", encoding="utf-8") as f:
            json.dump(payload, f, indent=2)
        print("\n[REPORT] wrote %s" % report_path)

    return 1 if failed else 0


if __name__ == "__main__":
    sys.exit(main())
