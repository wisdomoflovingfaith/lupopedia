#!/usr/bin/env python3
"""
V-PROJECT: global coherence across TODO.md, plan.md, and channel 42 thread dirs.
Spec: lupo-channels/42/threads/1019/20260318_163836_athena_spec_task_val_004_project-validation.md

Output: ERROR lines -> stdout, WARN -> stderr, INFO -> stdout.
Summary: validate_vproject: X error(s), Y warning(s)

Usage:
  python lupo-scripts/validate_vproject.py --repo-root .
  python lupo-scripts/validate_vproject.py --strict
  python lupo-scripts/validate_vproject.py --allowlist path/to/extra_allowlist.txt
  python lupo-scripts/validate_vproject.py --warnings-as-errors
  python lupo-scripts/validate_vproject.py --ignore-upstream-fail --report-staging
"""
from __future__ import annotations

import argparse
import re
import subprocess
import sys
from collections import defaultdict
from pathlib import Path

CHANNEL = 42
PROJECT_SLUG = "lupopedia-core"

REGISTRY_H2 = re.compile(r"^## Global Task Registry \(Option A\)\s*$")
H2 = re.compile(r"^##\s+")
CANONICAL_HEADER = [
    "task_id",
    "task_title",
    "owner_actor",
    "lifecycle_state",
    "status",
    "thread_id",
    "priority",
    "created_utc",
    "updated_utc",
    "primary_artifact",
    "notes",
]
THREAD_NUM = re.compile(r"^[1-9][0-9]{0,17}$")
DIGIT_THREAD = re.compile(r"^\d+$")
TASK_ID_RE = re.compile(r"^[a-z0-9_]+$")
EXECUTION_BOUND = frozenset({"active", "blocked", "resolved", "archived"})
RESOLVED_LIFE = frozenset({"resolved", "archived"})
RESOLVED_STATUS = frozenset({"complete", "archived"})

HEADER_TASK_ID = re.compile(r"^\s*task_id:\s*[\"']?([a-z0-9_]+)", re.M)
ARTIFACT_KIND = re.compile(r"^\s*artifact_kind:\s*[\"']?([a-z0-9_]+)", re.M | re.I)
TS_PREFIX = re.compile(r"^(\d{8}_\d{6})_.+\.md$", re.I)
CLOSURE_COMPLETION_RE = re.compile(
    r"\b(Task Closure|CLOSED|COMPLETE)\b",
    re.I,
)
SPLIT_PARENT_RE = re.compile(r"split_from:|parent_task_id:", re.I)

PLAN_REGISTRY_LINE = re.compile(
    r"^\s*-\s+task_id:\s+([a-z0-9_]+)(.*)$",
    re.I,
)


def split_table_row(line: str) -> list[str] | None:
    s = line.rstrip("\r\n").strip()
    if not s.startswith("|"):
        return None
    parts = [p.strip() for p in s.split("|")]
    if parts and parts[0] == "":
        parts = parts[1:]
    if parts and parts[-1] == "":
        parts = parts[:-1]
    return parts


def is_separator_row(cells: list[str]) -> bool:
    if not cells:
        return False
    return all(re.match(r"^[\s\-:]*$", c) or c == "" for c in cells)


def split_frontmatter(text: str) -> tuple[str | None, str]:
    if not text.startswith("---"):
        return None, text
    parts = text.split("---", 2)
    if len(parts) < 3:
        return None, text
    return parts[1], parts[2]


def load_thread_id_file(p: Path) -> set[str]:
    ids: set[str] = set()
    if not p.is_file():
        return ids
    for line in p.read_text(encoding="utf-8", errors="replace").splitlines():
        line = line.split("#", 1)[0].strip()
        if line and THREAD_NUM.match(line):
            ids.add(line)
    return ids


def parse_todo_rows(todo_path: Path) -> tuple[list[dict], str | None]:
    """Return (rows, fatal_error). Each row: task_id, task_title, owner_actor, lifecycle, status, thread_id, updated_utc, notes."""
    if not todo_path.is_file():
        return [], "TODO.md missing"
    text = todo_path.read_text(encoding="utf-8", errors="replace")
    lines = text.splitlines()
    idxs = [i for i, ln in enumerate(lines) if REGISTRY_H2.match(ln)]
    if len(idxs) != 1:
        return [], "expected exactly one Global Task Registry (Option A) section"
    start = idxs[0] + 1
    header_cells: list[str] | None = None
    rows: list[dict] = []
    i = start
    while i < len(lines):
        ln = lines[i]
        if H2.match(ln) and not ln.startswith("###"):
            break
        if ln.strip().startswith("|"):
            cells = split_table_row(ln)
            if cells is None:
                i += 1
                continue
            if is_separator_row(cells):
                i += 1
                continue
            if header_cells is None:
                if cells != CANONICAL_HEADER:
                    return [], "registry table header mismatch"
                header_cells = cells
                i += 1
                continue
            if len(cells) != 11:
                i += 1
                continue
            if all(c.strip() == "-" or c.strip() == "" for c in cells):
                i += 1
                continue
            rows.append(
                {
                    "task_id": cells[0].strip(),
                    "task_title": cells[1].strip(),
                    "owner_actor": cells[2].strip(),
                    "lifecycle_state": cells[3].strip().lower(),
                    "status": cells[4].strip().lower(),
                    "thread_id": cells[5].strip(),
                    "updated_utc": cells[8].strip(),
                    "notes": cells[10].strip(),
                }
            )
        i += 1
    return rows, None


def index_by_task_id(rows: list[dict]) -> dict[str, dict]:
    return {r["task_id"]: r for r in rows if TASK_ID_RE.match(r["task_id"])}


def collect_header_task_ids_in_thread(thread_dir: Path) -> set[str]:
    found: set[str] = set()
    if not thread_dir.is_dir():
        return found
    for f in thread_dir.iterdir():
        if f.suffix.lower() != ".md" or not f.is_file():
            continue
        try:
            raw = f.read_text(encoding="utf-8", errors="replace")
        except OSError:
            continue
        if not raw.startswith("---"):
            continue
        fm, _ = split_frontmatter(raw)
        if not fm:
            continue
        for m in HEADER_TASK_ID.finditer(fm):
            found.add(m.group(1).lower())
    return found


def max_closure_timestamp(thread_dir: Path) -> str:
    """Max YYYYMMDD_HHIISS from filenames containing _closure_ or artifact_kind closure."""
    best = ""
    if not thread_dir.is_dir():
        return best
    for f in thread_dir.iterdir():
        if f.suffix.lower() != ".md":
            continue
        name_l = f.name.lower()
        if "_closure_" in name_l:
            m = TS_PREFIX.match(f.name)
            if m and m.group(1) > best:
                best = m.group(1)
        try:
            raw = f.read_text(encoding="utf-8", errors="replace")
        except OSError:
            continue
        fm, _ = split_frontmatter(raw)
        if not fm:
            continue
        ak = ARTIFACT_KIND.search(fm)
        if ak and ak.group(1).lower() == "closure":
            m = TS_PREFIX.match(f.name)
            ts = m.group(1) if m else ""
            if ts > best:
                best = ts
    return best


def thread_has_completion_semantics(thread_dir: Path) -> bool:
    """Resolved/archived registry: at least status|closure artifact or _closure_ or body keywords."""
    if not thread_dir.is_dir():
        return False
    for f in thread_dir.iterdir():
        if f.suffix.lower() != ".md":
            continue
        if "_closure_" in f.name.lower():
            return True
        try:
            raw = f.read_text(encoding="utf-8", errors="replace")
        except OSError:
            continue
        fm, body = split_frontmatter(raw)
        if fm:
            ak = ARTIFACT_KIND.search(fm)
            if ak and ak.group(1).lower() in ("closure", "status"):
                return True
        if body and CLOSURE_COMPLETION_RE.search(body):
            return True
    return False


def has_closure_for_v006(thread_dir: Path, task_id: str) -> bool:
    """
    V-PROJECT-006: artifact_kind in {closure, directive} AND completion assertion,
    OR filename _closure_, OR body Task Closure|CLOSED|COMPLETE.
    """
    if not thread_dir.is_dir():
        return False
    for f in thread_dir.iterdir():
        if f.suffix.lower() != ".md":
            continue
        if "_closure_" in f.name.lower():
            return True
        try:
            raw = f.read_text(encoding="utf-8", errors="replace")
        except OSError:
            continue
        fm, body = split_frontmatter(raw)
        if not fm:
            if body and CLOSURE_COMPLETION_RE.search(body):
                return True
            continue
        t_m = HEADER_TASK_ID.search(fm)
        header_task = t_m.group(1) if t_m else None
        if header_task and header_task != task_id:
            continue
        ak = ARTIFACT_KIND.search(fm)
        kind = ak.group(1).lower() if ak else ""
        if kind in ("closure", "directive"):
            if CLOSURE_COMPLETION_RE.search(fm) or (body and CLOSURE_COMPLETION_RE.search(body)):
                return True
        if body and CLOSURE_COMPLETION_RE.search(body):
            return True
    return False


def parse_plan_registry_links(plan_path: Path) -> list[tuple[str, bool, int]]:
    """(task_id, is_backlog, line_no)."""
    out: list[tuple[str, bool, int]] = []
    if not plan_path.is_file():
        return out
    lines = plan_path.read_text(encoding="utf-8", errors="replace").splitlines()
    for i, ln in enumerate(lines, 1):
        m = PLAN_REGISTRY_LINE.match(ln)
        if not m:
            continue
        tid = m.group(1).lower()
        rest = m.group(2)
        backlog = "(backlog)" in rest.lower()
        out.append((tid, backlog, i))
    return out


def normalize_title(title: str) -> str:
    s = title.lower()
    s = re.sub(r"[^a-z0-9\s]+", " ", s)
    s = re.sub(r"\s+", " ", s).strip()
    return s[:80] if len(s) > 80 else s


def list_numeric_thread_dirs(repo: Path) -> list[str]:
    base = repo / "lupo-channels" / str(CHANNEL) / "threads"
    if not base.is_dir():
        return []
    names = []
    for d in base.iterdir():
        if d.is_dir() and THREAD_NUM.match(d.name):
            names.append(d.name)
    return sorted(names, key=lambda x: int(x))


def run_upstream_todo_plan(repo: Path, ignore_fail: bool) -> int:
    script = repo / "lupo-scripts" / "validate_todo_plan.py"
    if not script.is_file():
        print("V-PROJECT-UPSTREAM ERROR: validate_todo_plan.py missing", file=sys.stdout)
        return 1
    r = subprocess.run(
        [sys.executable, str(script), "--repo-root", str(repo)],
        cwd=str(repo),
        capture_output=True,
        text=True,
        encoding="utf-8",
        errors="replace",
    )
    if r.returncode != 0:
        print("V-PROJECT-UPSTREAM ERROR: validate_todo_plan.py exit %d" % r.returncode, file=sys.stdout)
        if r.stdout:
            sys.stdout.write(r.stdout)
        if r.stderr:
            sys.stderr.write(r.stderr)
        if not ignore_fail:
            return 1
    return 0


def run_vthread(repo: Path, thread_ids: list[str]) -> int:
    """Return 0 if zero V-THREAD errors for listed threads."""
    script = repo / "lupo-scripts" / "validate_threads.py"
    if not script.is_file():
        print("V-PROJECT-006 ERROR: validate_threads.py missing", file=sys.stdout)
        return (1, 1)
    if not thread_ids:
        return (0, 0)
    arg = ",".join(sorted(set(thread_ids), key=lambda x: int(x)))
    r = subprocess.run(
        [
            sys.executable,
            str(script),
            "--repo-root",
            str(repo),
            "--channel",
            str(CHANNEL),
            "--threads",
            arg,
        ],
        cwd=str(repo),
        capture_output=True,
        text=True,
        encoding="utf-8",
        errors="replace",
    )
    if r.returncode == 0:
        return (0, 0)
    vt_n = sum(
        1 for ln in (r.stdout or "").splitlines() if re.match(r"^\[V-THREAD-", ln.strip())
    )
    return (1, vt_n if vt_n else 1)


def main() -> int:
    ap = argparse.ArgumentParser(description="V-PROJECT global coherence (channel %d)" % CHANNEL)
    ap.add_argument("--repo-root", default=".", help="Repository root")
    ap.add_argument(
        "--strict",
        action="store_true",
        help="Orphan threads as ERROR; otherwise WARN",
    )
    ap.add_argument(
        "--allowlist",
        default="",
        help="Extra allowlist file (thread IDs) merged with legacy/orphan defaults",
    )
    ap.add_argument(
        "--warnings-as-errors",
        action="store_true",
        help="Exit 2 if any WARN",
    )
    ap.add_argument(
        "--ignore-upstream-fail",
        action="store_true",
        help="Continue V-PROJECT even if V-TODO/V-PLAN validator fails",
    )
    ap.add_argument(
        "--report-staging",
        action="store_true",
        help="INFO lines for open+planned unallocated tasks",
    )
    ap.add_argument(
        "--skip-vthread",
        action="store_true",
        help="Do not run validate_threads for V-PROJECT-006 (testing only)",
    )
    args = ap.parse_args()
    repo = Path(args.repo_root).resolve()
    scripts_dir = repo / "lupo-scripts"
    extra = load_thread_id_file(Path(args.allowlist)) if args.allowlist else set()
    legacy_allow = load_thread_id_file(scripts_dir / "vproject_legacy_threads.txt") | extra
    orphan_allow = load_thread_id_file(scripts_dir / "vproject_orphan_threads.txt") | extra

    errors: list[str] = []
    warnings: list[str] = []
    infos: list[str] = []

    if run_upstream_todo_plan(repo, args.ignore_upstream_fail) != 0 and not args.ignore_upstream_fail:
        print("validate_vproject: 1 error(s), 0 warning(s)", file=sys.stdout)
        return 1

    rows, fatal = parse_todo_rows(repo / "TODO.md")
    if fatal:
        errors.append("V-PROJECT-000 ERROR: %s" % fatal)
        _emit(errors, warnings, infos)
        return _finish(errors, warnings, args.warnings_as_errors)

    by_task = index_by_task_id(rows)
    threads_root = repo / "lupo-channels" / str(CHANNEL) / "threads"

    # V-PROJECT-005-DUP: duplicate registry rows same task_id -> different thread_id
    tid_threads: dict[str, set[str]] = defaultdict(set)
    for r in rows:
        tid = r["task_id"]
        if not TASK_ID_RE.match(tid):
            continue
        tid_threads[tid].add(r["thread_id"])
    for tid in sorted(tid_threads.keys()):
        numeric = {t for t in tid_threads[tid] if THREAD_NUM.match(t)}
        if len(numeric) > 1:
            errors.append(
                "V-PROJECT-005-DUP ERROR: task_id %s maps to multiple threads %s"
                % (tid, ",".join(sorted(numeric, key=int)))
            )

    # V-PROJECT-001
    for r in rows:
        tid = r["task_id"]
        life = r["lifecycle_state"]
        st = r["status"]
        th = r["thread_id"]
        if life in EXECUTION_BOUND:
            if not DIGIT_THREAD.match(th) or th == "0":
                errors.append(
                    "V-PROJECT-001 ERROR: %s execution-bound requires numeric thread_id, got %r"
                    % (tid, th)
                )
                continue
            if not THREAD_NUM.match(th):
                errors.append(
                    "V-PROJECT-001 ERROR: %s thread_id %r invalid (must match ^[1-9][0-9]{0,17}$)"
                    % (tid, th)
                )
                continue
            tdir = threads_root / th
            if not tdir.is_dir():
                errors.append(
                    "V-PROJECT-001 ERROR: %s -> thread %s missing directory" % (tid, th)
                )
        if (
            args.report_staging
            and life == "open"
            and st == "planned"
            and th == "-"
        ):
            infos.append("V-PROJECT-001-INFO: unallocated task %s (staging)" % tid)

    # Registry thread_id -> set for 004 and 002
    registry_threads: set[str] = set()
    for r in rows:
        if THREAD_NUM.match(r["thread_id"]):
            registry_threads.add(r["thread_id"])

    # V-PROJECT-002 per thread directory
    for N in list_numeric_thread_dirs(repo):
        tdir = threads_root / N
        u = collect_header_task_ids_in_thread(tdir)
        if not u:
            continue
        if len(u) > 1:
            msg = "V-PROJECT-002-MIX ERROR: thread %s mixed task_id headers %s" % (
                N,
                ",".join(sorted(u)),
            )
            if N in legacy_allow:
                warnings.append(
                    "V-PROJECT-002-MIX WARN: thread %s mixed task_id headers %s (legacy allowlist)"
                    % (N, ",".join(sorted(u)))
                )
            else:
                errors.append(msg)
            continue
        T = next(iter(u))
        if T not in by_task:
            errors.append(
                "V-PROJECT-002 ERROR: thread %s declares task_id %s not in TODO registry" % (N, T)
            )
            continue
        reg = by_task[T]
        if reg["thread_id"] != N:
            errors.append(
                "V-PROJECT-002 ERROR: thread %s dominant %s but registry thread_id=%s"
                % (N, T, reg["thread_id"])
            )
            continue
        # LAG: active + closure artifact dated after updated_utc
        if reg["lifecycle_state"] == "active":
            mc = max_closure_timestamp(tdir)
            uu = reg["updated_utc"]
            if mc and re.match(r"^\d{8}_\d{6}$", uu) and mc >= uu:
                errors.append(
                    "V-PROJECT-002-LAG ERROR: %s active but closure timestamp %s >= registry updated_utc %s"
                    % (T, mc, uu)
                )
        if reg["lifecycle_state"] in RESOLVED_LIFE or reg["status"] in RESOLVED_STATUS:
            if not thread_has_completion_semantics(tdir):
                errors.append(
                    "V-PROJECT-002 ERROR: %s resolved/archived in registry but thread %s lacks completion artifact (status|closure|_closure_|body keyword)"
                    % (T, N)
                )

    # V-PROJECT-003
    plan_links = parse_plan_registry_links(repo / "plan.md")
    for tid, backlog, line_no in plan_links:
        if tid not in by_task:
            errors.append(
                "V-PROJECT-003 ERROR: plan line %d task_id %s not in TODO registry"
                % (line_no, tid)
            )
            continue
        r = by_task[tid]
        if backlog:
            if r["lifecycle_state"] != "open" or r["thread_id"] != "-":
                warnings.append(
                    "V-PROJECT-003 WARN: %s marked (backlog) in plan but registry not open+thread -"
                    % tid
                )
            continue
        life = r["lifecycle_state"]
        th = r["thread_id"]
        if life not in EXECUTION_BOUND:
            errors.append(
                "V-PROJECT-003 ERROR: plan references %s (not backlog); registry must be execution-bound, got lifecycle=%s"
                % (tid, life)
            )
            continue
        if not THREAD_NUM.match(th):
            errors.append(
                "V-PROJECT-003 ERROR: plan references %s; registry thread_id must be numeric" % tid
            )
            continue
        if not (threads_root / th).is_dir():
            errors.append(
                "V-PROJECT-003 ERROR: plan references %s; thread dir %s missing" % (tid, th)
            )

    # V-PROJECT-004 orphan dirs
    for N in list_numeric_thread_dirs(repo):
        if N in registry_threads or N in orphan_allow:
            continue
        if args.strict:
            errors.append("V-PROJECT-004 ERROR: orphan thread %s not in registry" % N)
        else:
            warnings.append("V-PROJECT-004 WARN: orphan thread %s not in registry" % N)

    # V-PROJECT-005 overlap
    seen_pairs: dict[tuple[str, str], str] = {}
    for r in rows:
        if r["lifecycle_state"] != "active" or r["status"] != "in_progress":
            continue
        tid = r["task_id"]
        owner = r["owner_actor"]
        if owner == "-":
            continue
        nt = normalize_title(r["task_title"])
        key = (owner, nt)
        notes = r["notes"]
        if SPLIT_PARENT_RE.search(notes):
            continue
        if not nt:
            continue
        if key in seen_pairs and seen_pairs[key] != tid:
            errors.append(
                "V-PROJECT-005-OVERLAP ERROR: active in_progress duplicate owner+title %s / %s vs %s"
                % (seen_pairs[key], tid, owner)
            )
        else:
            seen_pairs[key] = tid

    # V-PROJECT-006 + V-THREAD batch
    resolved_threads: list[str] = []
    for r in rows:
        life = r["lifecycle_state"]
        st = r["status"]
        th = r["thread_id"]
        if (life in RESOLVED_LIFE or st in RESOLVED_STATUS) and THREAD_NUM.match(th):
            tid = r["task_id"]
            tdir = threads_root / th
            if not has_closure_for_v006(tdir, tid):
                errors.append(
                    "V-PROJECT-006 ERROR: %s resolved/complete; thread %s missing closure/directive completion artifact"
                    % (tid, th)
                )
            resolved_threads.append(th)

    if not args.skip_vthread and resolved_threads:
        bad, vt_count = run_vthread(repo, resolved_threads)
        if bad:
            errors.append(
                "V-PROJECT-006-THREAD-DIRTY ERROR: validate_threads V-THREAD error count=%d for resolved-thread batch"
                % vt_count
            )

    _emit(errors, warnings, infos)
    return _finish(errors, warnings, args.warnings_as_errors)


def _emit(errors: list[str], warnings: list[str], infos: list[str]) -> None:
    for e in errors:
        print(e, file=sys.stdout)
    for w in warnings:
        print(w, file=sys.stderr)
    for i in infos:
        print(i, file=sys.stdout)


def _finish(errors: list[str], warnings: list[str], warn_fatal: bool) -> int:
    ne, nw = len(errors), len(warnings)
    print("validate_vproject: %d error(s), %d warning(s)" % (ne, nw), file=sys.stdout)
    if ne:
        return 1
    if nw and warn_fatal:
        return 2
    return 0


if __name__ == "__main__":
    sys.exit(main())
