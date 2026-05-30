#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/validate_todo_plan.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Option A registry validator: TODO.md Global Task Registry + plan.md Strategic Roadmap.
Spec: lupo-channels/42/threads/1012/20260318_180800_hephaestus_spec_validator-complete.md

Usage:
  python lupo-scripts/validate_todo_plan.py --repo-root .
  python lupo-scripts/validate_todo_plan.py --repo-root . --warnings-as-errors
"""
from __future__ import annotations

import argparse
import re
import sys
from pathlib import Path

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
TASK_ID_RE = re.compile(r"^[a-z0-9_]+$")
RESERVED_TASK_IDS = frozenset({"todo", "plan", "thread", "prompt"})
OWNER_RE = re.compile(r"^([0-9]+):([a-z0-9_]+)$")
LIFECYCLES = frozenset({"open", "active", "blocked", "resolved", "archived"})
STATUSES = frozenset({"planned", "in_progress", "blocked", "complete", "archived"})
LIFE_TO_STATUS = {
    "open": "planned",
    "active": "in_progress",
    "blocked": "blocked",
    "resolved": "complete",
    "archived": "archived",
}
PRIORITIES = frozenset({"P0", "P1", "P2", "P3"})
TS_RE = re.compile(r"^\d{8}_\d{6}$")
THREAD_NUM = re.compile(r"^[1-9][0-9]{0,17}$")
RE_TASK_PROMPT = re.compile(r"^task_prompt_[0-9]+$")
RE_TASK_DEFERRED = re.compile(r"^task_deferred_[0-9]+$")
PHASE_HEAD = re.compile(r"^##\s+Phase\s+([1-9][0-9]*)\s+\u2014\s+(.+?)\s*$")
PROMPT_QUEUE_START = re.compile(r"^## Prompt queue")
VERSION_HISTORY = re.compile(r"^## Version History\s*$")
REGISTRY_LINK_BULLET = re.compile(
    r"^\s*-\s+task_id:\s+([a-z0-9_]+)"
    r"(?:\s+\(thread\s+([1-9][0-9]*)\))?"
    r"\s+(\u2014| -- )\s+(.+)$"
)
TASK_ID_IN_LINE = re.compile(r"task_id:\s*([a-z0-9_]+)")
STANDALONE_PROMPT_BULLET = re.compile(
    r"^\s*-\s+\*\*[0-9]{6}\*\*"
)
THREAD_ORPHAN = re.compile(r"\(thread\s+[0-9]+\)|thread_id:\s*[0-9]+", re.I)


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


def err(msgs: list[tuple[str, str]], rule: str, msg: str) -> None:
    msgs.append(("ERROR", "[%s] %s" % (rule, msg)))


def warn(msgs: list[tuple[str, str]], rule: str, msg: str) -> None:
    msgs.append(("WARN", "[%s] %s" % (rule, msg)))


def validate_primary_artifact(val: str) -> str | None:
    if val == "-":
        return None
    if not val.endswith(".md"):
        return "must be - or relative path ending in .md"
    if re.match(r"^[A-Za-z]:", val) or val.startswith("/"):
        return "no absolute or drive path"
    if ":" in val.split("/")[0]:
        return "no scheme before path"
    for seg in val.replace("\\", "/").split("/"):
        if seg == "..":
            return "no .. segment"
    return None


def validate_todo(path: Path) -> tuple[list[tuple[str, str]], set[str]]:
    """Returns (messages, S_todo valid task_ids)."""
    out: list[tuple[str, str]] = []
    s_todo: set[str] = set()
    try:
        text = path.read_text(encoding="utf-8", errors="replace")
    except OSError as e:
        err(out, "V-TODO-001", "TODO.md unreadable: %s" % e)
        return (out, s_todo)

    lines = text.splitlines()
    idxs = [i for i, ln in enumerate(lines) if REGISTRY_H2.match(ln)]
    if len(idxs) != 1:
        err(out, "V-TODO-001", "expected exactly one '## Global Task Registry (Option A)', found %d" % len(idxs))
        return (out, s_todo)

    start = idxs[0] + 1
    i = start
    header_cells: list[str] | None = None
    data_rows: list[tuple[int, list[str]]] = []

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
                    err(out, "V-TODO-002", "line %d: header must match canonical 11 columns" % (i + 1))
                    return (out, s_todo)
                header_cells = cells
                i += 1
                continue
            if len(cells) != 11:
                err(out, "V-TODO-003", "line %d: expected 11 columns, got %d" % (i + 1, len(cells)))
                i += 1
                continue
            for j, c in enumerate(cells):
                if "\n" in c or "\r" in c:
                    err(out, "V-TODO-003", "line %d: cell %d contains newline" % (i + 1, j))
            if all(c == "" for c in cells):
                err(out, "V-TODO-003", "line %d: empty cells (use - not blank)" % (i + 1))
            elif all(c.strip() == "-" or c.strip() == "" for c in cells):
                err(out, "V-TODO-003", "line %d: degenerate all-placeholder row not allowed" % (i + 1))
            else:
                for j, c in enumerate(cells):
                    if c == "":
                        err(out, "V-TODO-003", "line %d: empty cell %d (use -)" % (i + 1, j))
                if len(cells) == 11 and all(cells):
                    data_rows.append((i + 1, cells))
        i += 1

    if header_cells is None:
        err(out, "V-TODO-002", "no registry table header found after section heading")
        return (out, s_todo)

    seen_ids: dict[str, int] = {}
    for line_no, cells in data_rows:
        tid = cells[0]
        title = cells[1]
        owner = cells[2]
        life = cells[3]
        status = cells[4]
        thread = cells[5]
        pri = cells[6]
        c_utc = cells[7]
        u_utc = cells[8]
        part = cells[9]
        notes = cells[10]

        if not (1 <= len(title) <= 120) or "|" in title:
            err(out, "V-TODO-003", "line %d: task_title length 1-120, no pipe" % line_no)

        if not TASK_ID_RE.match(tid):
            err(out, "V-TODO-004", "line %d: bad task_id %r" % (line_no, tid))
        elif tid in RESERVED_TASK_IDS:
            err(out, "V-TODO-004", "line %d: reserved task_id %r" % (line_no, tid))
        else:
            s_todo.add(tid)

        if tid in seen_ids:
            err(out, "V-TODO-005", "line %d: duplicate task_id %s (also line %d)" % (line_no, tid, seen_ids[tid]))
        else:
            seen_ids[tid] = line_no

        if owner == "-":
            pass
        elif OWNER_RE.match(owner):
            pass
        else:
            err(out, "V-TODO-006", "line %d: owner_actor must be - or digit:slug" % line_no)

        if life not in LIFECYCLES:
            err(out, "V-TODO-007", "line %d: bad lifecycle_state %r" % (line_no, life))

        if status not in STATUSES:
            err(out, "V-TODO-008", "line %d: bad status %r" % (line_no, status))

        if life in LIFECYCLES and status in STATUSES:
            if LIFE_TO_STATUS.get(life) != status:
                err(out, "V-TODO-009", "line %d: lifecycle %s requires status %s, got %s" % (line_no, life, LIFE_TO_STATUS.get(life), status))

        if life in ("active", "blocked", "resolved", "archived"):
            if not THREAD_NUM.match(thread):
                err(out, "V-TODO-010", "line %d: non-open lifecycle requires numeric thread_id" % line_no)
            if owner == "-":
                err(out, "V-TODO-010", "line %d: non-open lifecycle requires owner_actor" % line_no)

        if life == "open":
            if RE_TASK_PROMPT.match(tid):
                if thread != "-":
                    err(out, "V-TODO-011", "line %d: task_prompt_* requires thread_id -" % line_no)
                if owner == "-" or not OWNER_RE.match(owner):
                    err(out, "V-TODO-011", "line %d: task_prompt_* requires owner_actor N:slug" % line_no)
            elif RE_TASK_DEFERRED.match(tid):
                if thread != "-" or owner != "-":
                    err(out, "V-TODO-011", "line %d: task_deferred_* requires thread_id and owner -" % line_no)
            else:
                if THREAD_NUM.match(thread):
                    if owner == "-":
                        err(out, "V-TODO-011", "line %d: open with numeric thread requires owner" % line_no)
                elif thread == "-":
                    if owner != "-":
                        err(out, "V-TODO-011", "line %d: open unallocated requires owner -" % line_no)
                else:
                    err(out, "V-TODO-011", "line %d: thread_id must be - or numeric" % line_no)

        if pri not in PRIORITIES:
            err(out, "V-TODO-012", "line %d: bad priority %r" % (line_no, pri))

        if not TS_RE.match(c_utc) or not TS_RE.match(u_utc):
            err(out, "V-TODO-013", "line %d: timestamp format YYYYMMDD_HHIISS" % line_no)
        elif u_utc < c_utc:
            err(out, "V-TODO-014", "line %d: updated_utc < created_utc" % line_no)

        pe = validate_primary_artifact(part)
        if pe:
            err(out, "V-TODO-015", "line %d primary_artifact: %s" % (line_no, pe))

        if "\n" in notes or "\r" in notes:
            err(out, "V-TODO-015", "line %d: notes newline forbidden" % line_no)
        elif notes != "-" and len(notes) > 240:
            warn(out, "W-TODO-002", "line %d: notes > 240 codepoints" % line_no)

    parsed_for_order = []
    for line_no, cells in data_rows:
        tid, life, pri = cells[0], cells[3], cells[6]
        if pri in PRIORITIES and life in LIFECYCLES:
            parsed_for_order.append({"pri": pri, "life": life, "tid": tid, "line": line_no})

    pri_r = {"P0": 0, "P1": 1, "P2": 2, "P3": 3}
    life_r = {"active": 0, "blocked": 1, "open": 2, "resolved": 3, "archived": 4}
    for a in range(len(parsed_for_order) - 1):
        r1 = parsed_for_order[a]
        r2 = parsed_for_order[a + 1]
        k1 = (pri_r[r1["pri"]], life_r[r1["life"]], r1["tid"])
        k2 = (pri_r[r2["pri"]], life_r[r2["life"]], r2["tid"])
        if k2 < k1:
            warn(
                out,
                "W-TODO-001",
                "rows out of order (after line %d): expected P0..P3 then lifecycle active..archived then task_id lexicographic"
                % r1["line"],
            )
            break

    return (out, s_todo)


def extract_prompt_queue_block(lines: list[str]) -> tuple[int, int]:
    start = None
    for i, ln in enumerate(lines):
        if PROMPT_QUEUE_START.match(ln):
            start = i
            break
    if start is None:
        return (-1, -1)
    end = len(lines)
    for j in range(start + 1, len(lines)):
        if PHASE_HEAD.match(lines[j]) or VERSION_HISTORY.match(lines[j]):
            end = j
            break
    return (start, end)


def find_phase_ranges(lines: list[str]) -> list[tuple[int, int, str]]:
    ranges = []
    for i, ln in enumerate(lines):
        m = PHASE_HEAD.match(ln)
        if m:
            ranges.append((i, -1, m.group(1)))
    for k in range(len(ranges)):
        start = ranges[k][0]
        if k + 1 < len(ranges):
            end = ranges[k + 1][0]
        else:
            end = len(lines)
            for j in range(start + 1, len(lines)):
                if VERSION_HISTORY.match(lines[j]):
                    end = j
                    break
        ranges[k] = (start, end, ranges[k][2])
    return ranges


def validate_plan(path: Path, s_todo: set[str]) -> list[tuple[str, str]]:
    out: list[tuple[str, str]] = []
    try:
        text = path.read_text(encoding="utf-8", errors="replace")
    except OSError as e:
        err(out, "V-PLAN-001", "plan.md unreadable: %s" % e)
        return out

    lines = text.splitlines()

    for i, ln in enumerate(lines):
        cells = split_table_row(ln)
        if cells and len(cells) >= 11 and cells[:11] == CANONICAL_HEADER:
            err(out, "V-PLAN-009", "line %d: canonical TODO registry header forbidden in plan.md" % (i + 1))

    pr = extract_prompt_queue_block(lines)
    s_plan: set[str] = set()
    if pr[0] >= 0:
        for ln in lines[pr[0] : pr[1]]:
            for m in TASK_ID_IN_LINE.finditer(ln):
                s_plan.add(m.group(1))

    ranges = find_phase_ranges(lines)
    if not ranges:
        err(out, "V-PLAN-001", "no ## Phase N — … (EM DASH U+2014) heading found")
        return out

    for start, end, _pn in ranges:
        body = lines[start + 1 : end]
        dep_i = comp_i = reg_i = None
        for bi, bln in enumerate(body):
            t = bln.strip()
            if t.startswith("**Depends on:**"):
                dep_i = bi
            elif t.startswith("**Completion when:**"):
                comp_i = bi
            elif t.startswith("**Registry links:**"):
                reg_i = bi
        if dep_i is None or comp_i is None or reg_i is None:
            err(out, "V-PLAN-002", "phase starting line %d: missing Depends on / Completion when / Registry links" % (start + 1))
            continue
        if not (dep_i < comp_i < reg_i):
            err(out, "V-PLAN-002", "phase line %d: subsection order must be Depends on → Completion when → Registry links" % (start + 1))
            continue

        dep_ln = body[dep_i].strip()
        dep_rest = dep_ln.split("**Depends on:**", 1)[-1].strip()
        if not dep_rest:
            err(out, "V-PLAN-003", "phase line %d: Depends on empty" % (start + 1 + dep_i))
        else:
            low = dep_rest.lower()
            bad_found = False
            for bad in ("day", "week", "month", "asap", "soon", "later"):
                if bad in low:
                    err(out, "V-PLAN-003", "phase line %d: forbidden term in Depends on" % (start + 1 + dep_i))
                    bad_found = True
                    break
            if not bad_found:
                ok = False
                if dep_rest == "nothing":
                    ok = True
                elif re.match(r"^Phase\s+([1-9][0-9]?)$", dep_rest):
                    ok = True
                else:
                    chunks = [c.strip() for c in re.split(r"\s+\+\s+", dep_rest)]
                    if chunks and all(re.match(r"^Phase\s+([1-9][0-9]?)$", c) for c in chunks):
                        nums = [int(re.search(r"(\d+)", c).group(1)) for c in chunks]
                        if nums == sorted(nums) and len(nums) == len(set(nums)):
                            ok = True
                if not ok:
                    err(out, "V-PLAN-003", "phase line %d: invalid Depends on: %r" % (start + 1 + dep_i, dep_rest))

        chk = 0
        for bi in range(comp_i + 1, reg_i):
            if re.match(r"^\s*-\s+\[[ xX]\]\s+.+$", body[bi]):
                chk += 1
        if chk < 1:
            err(out, "V-PLAN-004", "phase line %d: Completion when needs ≥1 checklist item" % (start + 1))

        reg_lines = []
        for bi in range(reg_i + 1, len(body)):
            b = body[bi].strip()
            if not b:
                break
            if b.startswith("##") or b.startswith("---"):
                break
            if b.startswith("|"):
                break
            reg_lines.append((start + 1 + bi, body[bi]))

        for rline_no, rln in reg_lines:
            if not rln.strip().startswith("-"):
                continue
            m = REGISTRY_LINK_BULLET.match(rln)
            if not m:
                err(out, "V-PLAN-005", "line %d: registry link bullet format invalid" % rline_no)
                continue
            tid = m.group(1)
            if not TASK_ID_RE.match(tid) or tid in RESERVED_TASK_IDS:
                err(out, "V-PLAN-005", "line %d: bad task_id in registry link" % rline_no)
            reason = m.group(4).strip()
            if not reason:
                err(out, "V-PLAN-005", "line %d: reason after dash required" % rline_no)
            s_plan.add(tid)
            if "task_id:" not in rln:
                err(out, "V-PLAN-006", "line %d: registry link must include task_id:" % rline_no)

        phase_text = "\n".join(body)
        for bi, bln in enumerate(body):
            if reg_i is not None and dep_i <= bi <= reg_i:
                if bi == reg_i:
                    continue
            if STANDALONE_PROMPT_BULLET.match(bln) and "task_id:" not in bln:
                err(out, "V-PLAN-006", "phase line %d: standalone prompt bullet without task_id" % (start + 1 + bi))

        for bi, bln in enumerate(body):
            if THREAD_ORPHAN.search(bln) and "task_id:" not in bln.lower():
                err(out, "V-PLAN-007", "line %d: thread reference without task_id on same line" % (start + 1 + bi))

    for pid in sorted(s_plan):
        if pid not in s_todo:
            err(out, "V-PLAN-008", "PLAN_ORPHAN_TASK: %s not in TODO registry" % pid)

    return out


def run(repo: Path, warnings_as_errors: bool) -> int:
    todo_p = repo / "TODO.md"
    plan_p = repo / "plan.md"
    all_msg: list[tuple[str, str]] = []

    if not todo_p.is_file():
        all_msg.append(("ERROR", "[V-TODO-001] TODO.md missing"))
        s_todo = set()
    else:
        t_msg, s_todo = validate_todo(todo_p)
        all_msg.extend(t_msg)

    if plan_p.is_file():
        all_msg.extend(validate_plan(plan_p, s_todo))
    else:
        all_msg.append(("ERROR", "[V-PLAN-001] plan.md missing"))

    errors = [m for m in all_msg if m[0] == "ERROR"]
    warns = [m for m in all_msg if m[0] == "WARN"]

    for level, m in all_msg:
        if level == "WARN":
            print(m, file=sys.stderr)
        else:
            print(m)

    print(
        "validate_todo_plan: %d error(s), %d warn(s)"
        % (len(errors), len(warns)),
        file=sys.stderr,
    )

    if errors:
        return 1
    if warnings_as_errors and warns:
        return 1
    return 0


def main() -> int:
    ap = argparse.ArgumentParser(description="Option A TODO.md + plan.md validator")
    ap.add_argument("--repo-root", default=".", help="Repository root")
    ap.add_argument(
        "--warnings-as-errors",
        action="store_true",
        help="Exit 1 if any WARN",
    )
    args = ap.parse_args()
    root = Path(args.repo_root).resolve()
    return run(root, args.warnings_as_errors)


if __name__ == "__main__":
    sys.exit(main())