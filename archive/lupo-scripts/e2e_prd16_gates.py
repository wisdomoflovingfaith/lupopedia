#!/usr/bin/env python3
"""
PRD 16 §15 — E2E gate helpers: JSONL deltas under lupo-channels/ and offline queue.

Usage:
  python lupo-scripts/e2e_prd16_gates.py snapshot --out baseline.json
  # ... run web / IDE test window ...
  python lupo-scripts/e2e_prd16_gates.py assert --baseline baseline.json

§15.11 sidecar atomicity is manual or CI-specific; this script only checks for
stray *.metadata.json.tmp siblings (optional warning).
"""

from __future__ import annotations

import argparse
import json
import os
import sys
from pathlib import Path
from typing import Any, Dict, List

REPO_ROOT = Path(__file__).resolve().parents[1]
CHANNELS_GLOB_ROOT = REPO_ROOT / "lupo-channels"
OFFLINE_QUEUE = REPO_ROOT / "lupo-config" / "offline_transcript_queue.jsonl"


def _line_count(p: Path) -> int:
    try:
        with p.open("r", encoding="utf-8", errors="replace") as f:
            return sum(1 for _ in f)
    except OSError:
        return -1


def _scan_jsonl_under(root: Path) -> Dict[str, Dict[str, Any]]:
    out: Dict[str, Dict[str, Any]] = {}
    if not root.is_dir():
        return out
    for p in root.rglob("*.jsonl"):
        rel = p.relative_to(REPO_ROOT).as_posix()
        try:
            st = p.stat()
            out[rel] = {
                "size": st.st_size,
                "mtime": st.st_mtime,
                "lines": _line_count(p),
            }
        except OSError as e:
            out[rel] = {"error": str(e)}
    return out


def snapshot_state() -> Dict[str, Any]:
    qrel = OFFLINE_QUEUE.relative_to(REPO_ROOT).as_posix() if OFFLINE_QUEUE.is_file() else None
    qinfo = None
    if OFFLINE_QUEUE.is_file():
        st = OFFLINE_QUEUE.stat()
        qinfo = {
            "size": st.st_size,
            "mtime": st.st_mtime,
            "lines": _line_count(OFFLINE_QUEUE),
        }
    return {
        "channels_jsonl": _scan_jsonl_under(CHANNELS_GLOB_ROOT),
        "offline_queue": {"path": qrel, "stats": qinfo},
    }


def _assert_no_delta(before: Dict[str, Any], after: Dict[str, Any]) -> List[str]:
    errs: List[str] = []
    bch = before.get("channels_jsonl") or {}
    ach = after.get("channels_jsonl") or {}
    new_files = sorted(set(ach) - set(bch))
    if new_files:
        errs.append("§15.9 FAIL: new .jsonl under lupo-channels: %s" % ", ".join(new_files))
    for k in sorted(set(bch) & set(ach)):
        if bch[k].get("lines", -1) >= 0 and ach[k].get("lines", -1) >= 0:
            if ach[k]["lines"] > bch[k]["lines"]:
                errs.append(
                    "§15.9 FAIL: appended lines in %s (%s -> %s)"
                    % (k, bch[k]["lines"], ach[k]["lines"])
                )
        elif bch[k].get("size", -1) >= 0 and ach[k].get("size", -1) >= 0:
            if ach[k]["size"] > bch[k]["size"]:
                errs.append("§15.9 FAIL: file grew %s (%s -> %s bytes)" % (k, bch[k]["size"], ach[k]["size"]))

    bq = (before.get("offline_queue") or {}).get("stats")
    aq = (after.get("offline_queue") or {}).get("stats")
    if bq and aq and bq.get("lines", -1) >= 0 and aq.get("lines", -1) >= 0:
        if aq["lines"] > bq["lines"]:
            errs.append(
                "§15.10 FAIL: offline_transcript_queue.jsonl grew (%s -> %s lines)"
                % (bq["lines"], aq["lines"])
            )
    elif bq and aq and aq.get("size", 0) > bq.get("size", 0):
        errs.append(
            "§15.10 FAIL: offline queue file grew (%s -> %s bytes)"
            % (bq.get("size"), aq.get("size"))
        )
    return errs


def scan_tmp_sidecars() -> List[str]:
    """Optional §15.11 hint: unfinished atomic writes."""
    found: List[str] = []
    mem = REPO_ROOT / "lupo-memory"
    if not mem.is_dir():
        return found
    for p in mem.rglob("*.metadata.json.tmp"):
        found.append(p.relative_to(REPO_ROOT).as_posix())
    return found


def main() -> None:
    os.chdir(REPO_ROOT)
    ap = argparse.ArgumentParser(description="PRD 16 §15 transcript / JSONL gate helpers")
    sub = ap.add_subparsers(dest="cmd", required=True)

    p_snap = sub.add_parser("snapshot", help="Write baseline JSON of jsonl paths + queue stats")
    p_snap.add_argument("--out", required=True, help="Output JSON path")

    p_as = sub.add_parser("assert", help="Compare current tree to baseline; exit 1 on violation")
    p_as.add_argument("--baseline", required=True, help="Baseline JSON from snapshot")

    p_hint = sub.add_parser("sidecar-tmp-scan", help="List lupo-memory/**/*.metadata.json.tmp")

    args = ap.parse_args()
    if args.cmd == "snapshot":
        data = snapshot_state()
        out = Path(args.out)
        out.parent.mkdir(parents=True, exist_ok=True)
        out.write_text(json.dumps(data, indent=2), encoding="utf-8")
        print("[OK] wrote %s (%d channel jsonl files)" % (out, len(data["channels_jsonl"])))
    elif args.cmd == "assert":
        baseline_path = Path(args.baseline)
        before = json.loads(baseline_path.read_text(encoding="utf-8"))
        after = snapshot_state()
        errs = _assert_no_delta(before, after)
        for e in errs:
            print("[ERROR] " + e)
        if errs:
            sys.exit(1)
        print("[OK] §15.9/§15.10 delta gates passed (channels jsonl + offline queue)")
    elif args.cmd == "sidecar-tmp-scan":
        tmp = scan_tmp_sidecars()
        if tmp:
            print("[WARN] §15.11 hint: tmp sidecar files present:")
            for t in tmp:
                print("  ", t)
            sys.exit(2)
        print("[OK] no *.metadata.json.tmp under lupo-memory/")


if __name__ == "__main__":
    main()
