#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "scripts/hermes_scan_threads.py"
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

# -*- coding: utf-8 -*-
"""
Scan channel thread directories; emit Markdown table for HERMES routing inventory.

Usage:
  python scripts/hermes_scan_threads.py --repo-root . --channel 42 --threads 1001,1002
"""
from __future__ import print_function

import argparse
import os
import re
import sys


def _fm_key(block, key):
    m = re.search(
        r"^\s*" + re.escape(key) + r"\s*:\s*[\"']?([^\"'\n]+)[\"']?\s*$",
        block,
        re.MULTILINE | re.IGNORECASE,
    )
    if m:
        return m.group(1).strip()
    m = re.search(r"^\s*" + re.escape(key) + r"\s*:\s*(.+)$", block, re.MULTILINE)
    return m.group(1).strip() if m else ""


def scan_file(path):
    try:
        with open(path, "r", encoding="utf-8", errors="replace") as f:
            raw = f.read()
    except OSError as e:
        return None, str(e)
    fm = ""
    if raw.strip().startswith("---"):
        end = raw.find("\n---", 3)
        if end > 0:
            fm = raw[3:end]
    return {
        "artifact_kind": _fm_key(fm, "artifact_kind"),
        "actor_name": _fm_key(fm, "actor_name"),
        "purpose": _fm_key(fm, "purpose"),
    }, None


def main():
    ap = argparse.ArgumentParser(description="HERMES thread artifact inventory")
    ap.add_argument("--repo-root", default=".")
    ap.add_argument("--channel", type=int, default=42)
    ap.add_argument("--threads", default="1001,1002", help="Comma-separated dialog_thread_id dirs")
    args = ap.parse_args()
    root = os.path.abspath(args.repo_root)
    tids = [x.strip() for x in args.threads.split(",") if x.strip()]

    rows = []
    for tid in tids:
        d = os.path.join(root, "channels", str(args.channel), "threads", tid)
        if not os.path.isdir(d):
            print("# missing: " + d, file=sys.stderr)
            continue
        for name in sorted(os.listdir(d)):
            if not name.endswith(".md") or name == "README.md":
                continue
            p = os.path.join(d, name)
            if not os.path.isfile(p):
                continue
            meta, err = scan_file(p)
            if err:
                rows.append((tid, name, "", "", err))
                continue
            pur = (meta.get("purpose") or "")[:70]
            rows.append(
                (
                    tid,
                    name,
                    meta.get("actor_name") or "",
                    meta.get("artifact_kind") or "",
                    pur,
                )
            )

    print("# HERMES scan — channel %s threads %s\n" % (args.channel, ",".join(tids)))
    print("| thread | filename | actor_name | artifact_kind | purpose (trim) |")
    print("|--------|----------|------------|---------------|----------------|")
    for r in rows:
        cell = r[4].replace("|", "/").encode("ascii", "replace").decode("ascii")
        print("| %s | %s | %s | %s | %s |" % (r[0], r[1], r[2], r[3], cell))


if __name__ == "__main__":
    main()