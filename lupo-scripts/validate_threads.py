#!/usr/bin/env python3
"""
Thread continuity validator: V-THREAD-001..005 (task_val_003 precision).

V-THREAD-002 review: artifact_kind==review OR message_type==review (case-insensitive).
V-THREAD-005: issue markers + resolution terms = body only; edge must hit review path.
Non-enforced threads: skipped in default mode; stderr INFO reports skip count.

Usage:
  python lupo-scripts/validate_threads.py --repo-root . --channel 42
  python lupo-scripts/validate_threads.py --repo-root . --channel 42 --threads 1006
"""
from __future__ import annotations

import argparse
import re
import sys
from pathlib import Path

TS_PREFIX = re.compile(r"^(\d{8}_\d{6})_[a-z0-9_.-]+\.md$", re.I)
NUMERIC_THREAD = re.compile(r"^[1-9][0-9]{0,17}$")
TO_EDGE = re.compile(r"to:\s*[\"']?([^\"'}\n]+)")
ARTIFACT_KIND = re.compile(r"^\s*artifact_kind:\s*[\"']?([a-z0-9_]+)", re.M | re.I)
MESSAGE_TYPE = re.compile(r"^\s*message_type:\s*[\"']?([a-z0-9_]+)", re.M | re.I)
FILE_PATH_FROM_ROOT = re.compile(
    r'^\s*file_path_from_root:\s*["\']?([^"\'\n]+)', re.M
)

# V-THREAD-005: body only, first 12k chars, case-insensitive
VTHREAD005_ISSUE_RE = re.compile(
    r"pass-with-notes|pass with notes|critical gaps|must be fixed before|"
    r"blocking|issues identified|complete with notes|⚠|NOTES:",
    re.I,
)

# V-THREAD-005: body only, full body, case-insensitive
VTHREAD005_RESOLUTION_RE = re.compile(
    r"\bresolution\b|\baddresses\b|\bimplements\b|\bcorrected\b|per spec|spec\s*1012|"
    r"design review resolution|implementation review resolution|task_val_002",
    re.I,
)

LEGACY_THREAD_DIRS = frozenset({"4.0.x", "4.0.68", "4.0.73", "4.0.80"})


def is_review_artifact_v002(fm: str | None) -> bool:
    """V-THREAD-002 / V-THREAD-005: review iff artifact_kind==review OR message_type==review."""
    if not fm:
        return False
    ak = ARTIFACT_KIND.search(fm)
    mt = MESSAGE_TYPE.search(fm)
    k = ak.group(1).lower() if ak else ""
    m = mt.group(1).lower() if mt else ""
    return k == "review" or m == "review"


def review_edge_matches_target(to_val: str, review_fm: str | None, review_rel: str, review_basename: str) -> bool:
    """V-THREAD-005: later artifact to: must reference reviewed artifact path."""
    nt = norm_path(to_val)
    if review_fm:
        fp = FILE_PATH_FROM_ROOT.search(review_fm)
        if fp and norm_path(fp.group(1).strip()) == nt:
            return True
    if norm_path(review_rel) == nt:
        return True
    if nt.endswith("/" + review_basename) or nt.split("/")[-1] == review_basename:
        return True
    return False


def thread_has_continuity_flag(repo: Path, channel: int, tid: str) -> bool:
    base = repo / "lupo-channels" / str(channel) / "threads" / tid
    if not base.is_dir():
        return False
    for f in base.iterdir():
        if not f.is_file() or f.suffix.lower() != ".md":
            continue
        try:
            fm, _ = split_frontmatter(f.read_text(encoding="utf-8", errors="replace"))
        except OSError:
            continue
        if fm and re.search(r"thread_continuity_enforce:\s*true\b", fm, re.I):
            return True
    return False


def split_frontmatter(text: str) -> tuple[str | None, str]:
    if not text.startswith("---"):
        return None, text
    parts = text.split("---", 2)
    if len(parts) < 3:
        return None, text
    return parts[1], parts[2]


def norm_path(p: str) -> str:
    return p.replace("\\", "/").strip().strip('"').strip("'")


def in_thread(to_path: str, channel: int, tid: str) -> bool:
    n = norm_path(to_path)
    needle = "threads/%s/" % tid
    return needle in n or needle.replace("/", "\\") in to_path


def extract_tos(fm: str) -> list[str]:
    if "outbound_edges" in fm:
        start = fm.find("outbound_edges")
        sub = fm[start : start + 8000]
    else:
        sub = fm
    return [norm_path(m.group(1)) for m in TO_EDGE.finditer(sub)]


def artifact_paths(
    repo: Path, channel: int, tid: str
) -> list[tuple[str, Path, str]]:
    base = repo / "lupo-channels" / str(channel) / "threads" / tid
    if not base.is_dir():
        return []
    out = []
    for f in base.iterdir():
        if not f.is_file() or f.suffix.lower() != ".md":
            continue
        if f.name.lower() == "readme.md":
            continue
        m = TS_PREFIX.match(f.name)
        if not m:
            continue
        rel = f.relative_to(repo).as_posix()
        out.append((m.group(1), f, rel))
    out.sort(key=lambda x: (x[0], x[1].name))
    return out


def parse_resolved_thread_ids(todo_path: Path) -> set[str]:
    ids: set[str] = set()
    if not todo_path.is_file():
        return ids
    try:
        text = todo_path.read_text(encoding="utf-8", errors="replace")
    except OSError:
        return ids
    in_reg = False
    for line in text.splitlines():
        if "## Global Task Registry" in line:
            in_reg = True
            continue
        if in_reg and line.startswith("## ") and "Global Task Registry" not in line:
            break
        if not in_reg or not line.strip().startswith("|"):
            continue
        cells = [c.strip() for c in line.split("|")]
        if len(cells) >= 12:
            cells = cells[1:-1] if cells[0] == "" else cells
        if len(cells) < 11:
            continue
        life = cells[3].lower()
        thread = cells[5].strip()
        if life in ("resolved", "archived") and NUMERIC_THREAD.match(thread):
            ids.add(thread)
    return ids


def classify_lifecycle(art: tuple[str, Path, str], fm: str | None, body: str) -> dict:
    name = art[1].name.lower()
    kind = ""
    if fm:
        km = ARTIFACT_KIND.search(fm)
        if km:
            kind = km.group(1).lower()
    return {
        "is_kickoff": kind in ("directive", "kickoff") or "kickoff" in name,
        "is_closure": kind == "closure" or "closure" in name,
        "is_review": is_review_artifact_v002(fm),
        "is_impl": kind
        in (
            "implementation_plan",
            "implementation",
            "specification",
            "design",
            "status",
        )
        or "impl" in name
        or "result" in name
        or "validator-run" in name,
    }


def validate_thread(
    repo: Path, channel: int, tid: str, resolved_threads: set[str], lifecycle_required: bool
) -> list[tuple[str, str]]:
    msgs: list[tuple[str, str]] = []
    arts = artifact_paths(repo, channel, tid)
    if not arts:
        return msgs

    n = len(arts)
    rels = [a[2] for a in arts]
    names = [a[1].name for a in arts]
    timestamps = [a[0] for a in arts]

    seen_ts = set()
    for i, ts in enumerate(timestamps):
        if ts in seen_ts:
            msgs.append(("ERROR", "[V-THREAD-004] thread %s duplicate timestamp %s" % (tid, ts)))
        seen_ts.add(ts)
        if i and ts < timestamps[i - 1]:
            msgs.append(
                ("ERROR", "[V-THREAD-004] thread %s timestamp out of order %s after %s" % (tid, ts, timestamps[i - 1]))
            )

    parsed = []
    for art in arts:
        fm, body = split_frontmatter(art[1].read_text(encoding="utf-8", errors="replace"))
        tos = extract_tos(fm) if fm else []
        cls = classify_lifecycle(art, fm, body)
        parsed.append({"art": art, "fm": fm, "body": body, "tos": tos, **cls})

    def idx_of_target(t: str) -> int | None:
        t = norm_path(t)
        for i, r in enumerate(rels):
            if r == t or r.endswith("/" + t.split("/")[-1]) or names[i] in t:
                return i
        for i, r in enumerate(rels):
            if names[i] in t or t.endswith(names[i]):
                return i
        return None

    # V-THREAD-001: first forward only; last backward only; middle both
    if n >= 2:
        for i, p in enumerate(parsed):
            forward = backward = False
            for t in p["tos"]:
                if not in_thread(t, channel, tid):
                    continue
                j = idx_of_target(t)
                if j is None:
                    continue
                if j > i:
                    forward = True
                if j < i:
                    backward = True

            if i == 0:
                if not forward:
                    msgs.append(
                        (
                            "ERROR",
                            "[V-THREAD-001] thread %s %s: first artifact requires forward in-thread edge only"
                            % (tid, names[i]),
                        )
                    )
            elif i == n - 1:
                if not backward:
                    msgs.append(
                        (
                            "ERROR",
                            "[V-THREAD-001] thread %s %s: last artifact requires backward in-thread edge only"
                            % (tid, names[i]),
                        )
                    )
            else:
                if not forward or not backward:
                    msgs.append(
                        (
                            "ERROR",
                            "[V-THREAD-001] thread %s %s: middle artifact requires forward and backward in-thread edges"
                            % (tid, names[i]),
                        )
                    )

    # V-THREAD-003
    if n >= 2:
        adj = {j: set() for j in range(n)}
        for i, p in enumerate(parsed):
            for t in p["tos"]:
                if not in_thread(t, channel, tid):
                    continue
                j = idx_of_target(t)
                if j is not None and j != i:
                    adj[i].add(j)
                    adj[j].add(i)
        vis = set()
        stack = [0]
        while stack:
            u = stack.pop()
            if u in vis:
                continue
            vis.add(u)
            for v in adj[u]:
                if v not in vis:
                    stack.append(v)
        if len(vis) != n:
            msgs.append(
                ("ERROR", "[V-THREAD-003] thread %s: edges do not connect all artifacts" % tid)
            )
        for i in range(n):
            if len(adj[i]) == 0:
                msgs.append(
                    ("ERROR", "[V-THREAD-003] thread %s %s: no in-thread edge to sibling" % (tid, names[i]))
                )

    if lifecycle_required and n >= 1:
        any_k = any(p["is_kickoff"] for p in parsed)
        any_i = any(p["is_impl"] for p in parsed)
        any_r = any(p["is_review"] for p in parsed)
        any_c = any(p["is_closure"] for p in parsed)
        if not any_k:
            msgs.append(("ERROR", "[V-THREAD-002] thread %s: missing directive/kickoff" % tid))
        if not any_i:
            msgs.append(("ERROR", "[V-THREAD-002] thread %s: missing design/implementation artifact" % tid))
        if not any_r:
            msgs.append(("ERROR", "[V-THREAD-002] thread %s: missing review (artifact_kind or message_type review)" % tid))
        if not any_c:
            msgs.append(("ERROR", "[V-THREAD-002] thread %s: missing closure" % tid))

    for i, p in enumerate(parsed):
        if not p["is_review"]:
            continue
        snippet = p["body"][:12000]
        if not VTHREAD005_ISSUE_RE.search(snippet):
            continue
        rel = rels[i]
        bn = names[i]
        fm = p["fm"]
        found = False
        for j in range(i + 1, n):
            later = parsed[j]
            edge_ok = any(
                review_edge_matches_target(t, fm, rel, bn) for t in later["tos"]
            )
            if edge_ok and VTHREAD005_RESOLUTION_RE.search(later["body"]):
                found = True
                break
        if not found:
            msgs.append(
                (
                    "ERROR",
                    "[V-THREAD-005] thread %s: review %s has issue markers; later artifact needs edge to this file + resolution body text"
                    % (tid, bn),
                )
            )

    return msgs


def run(repo: Path, channel: int, threads: list[str] | None) -> int:
    base = repo / "lupo-channels" / str(channel) / "threads"
    if not base.is_dir():
        print("ERROR: missing %s" % base, file=sys.stderr)
        return 1
    resolved = parse_resolved_thread_ids(repo / "TODO.md")
    all_msgs: list[tuple[str, str]] = []

    candidates = [
        d.name
        for d in base.iterdir()
        if d.is_dir() and NUMERIC_THREAD.match(d.name) and d.name not in LEGACY_THREAD_DIRS
    ]

    if threads:
        tids = threads
        print(
            "validate_threads: INFO mode=explicit --threads count=%d (full V-THREAD on listed IDs)"
            % len(tids),
            file=sys.stderr,
        )
    else:
        tids = sorted(t for t in candidates if thread_has_continuity_flag(repo, channel, t))
        skipped = len(candidates) - len(tids)
        print(
            "validate_threads: INFO skipped=%d thread dirs (no thread_continuity_enforce); validated=%d"
            % (skipped, len(tids)),
            file=sys.stderr,
        )
        if not tids:
            print(
                "validate_threads: INFO no threads in scope; pass --threads ID to validate explicitly",
                file=sys.stderr,
            )

    explicit = threads is not None
    for tid in tids:
        if explicit:
            life = True
        else:
            life = tid in resolved or thread_has_continuity_flag(repo, channel, tid)
        all_msgs.extend(validate_thread(repo, channel, tid, resolved, life))

    err_n = sum(1 for x in all_msgs if x[0] == "ERROR")
    for level, m in all_msgs:
        print(m)
    print("validate_threads: %d error(s) across thread validation" % err_n, file=sys.stderr)
    return 1 if err_n else 0


def main() -> int:
    ap = argparse.ArgumentParser(description="V-THREAD-001..005 thread continuity")
    ap.add_argument("--repo-root", default=".")
    ap.add_argument("--channel", type=int, default=42)
    ap.add_argument(
        "--threads",
        default="",
        help="Comma-separated thread IDs (bypasses continuity_enforce filter)",
    )
    args = ap.parse_args()
    root = Path(args.repo_root).resolve()
    th = [t.strip() for t in args.threads.split(",") if t.strip()] or None
    return run(root, args.channel, th)


if __name__ == "__main__":
    sys.exit(main())
