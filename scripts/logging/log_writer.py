#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.9"
#   path_from_lupopedia_root: scripts/logging/log_writer.py
#   web_path: https://www.lupopedia.com/lupopedia/scripts/logging/log_writer.py
#   status: active
#   when_updated: "20260728131310"
#   trust_tier: canonical
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: 0/development/dual-operational-logs
#   artifact_type: script
#   artifact_kind: reference
#   channel_key: development
#   federation_node_id: 0
#   thread_key: ""
#   lupopedia.schema: script
#   prd_cluster: 98_C
#   title: Dual operational log writer
#   summary: writeCaptainLog, writeWolfieLog, generateDailyBundle for docs/logs/YYYY/MM/DD/.
#   edges_toon: null
#   channel_index: lupopedia
#   source_timestamp: null
# ---------------------------------------------------------------------
"""PRD 98_C dual operational log writer (Captain + WOLFIE + daily bundle)."""

from __future__ import annotations

import argparse
import json
import os
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
if _HERE not in sys.path:
    sys.path.insert(0, _HERE)

from header_generator import (  # noqa: E402
    generate_constitutional_header,
    packed_to_iso,
    to_packed_utc,
)


def _repo_root():
    return os.path.abspath(os.path.join(_HERE, "..", ".."))


def _write_json(abs_path, obj):
    parent = os.path.dirname(abs_path)
    if not os.path.isdir(parent):
        os.makedirs(parent)
    with open(abs_path, "w", encoding="ascii", newline="\n") as fh:
        json.dump(obj, fh, indent=2, sort_keys=False)
        fh.write("\n")


def write_captain_log(
    repo_root,
    thread_id,
    intent,
    context,
    decision,
    reasoning,
    emotional_state,
    next_actions,
    timestamp_ymdhis=None,
    seq="001",
):
    ymdhis = timestamp_ymdhis or to_packed_utc()
    log_id = "captain_%s_%s" % (ymdhis, seq)
    rel = "docs/logs/%s/%s/%s/%s.json" % (
        ymdhis[0:4],
        ymdhis[4:6],
        ymdhis[6:8],
        log_id,
    )
    header = generate_constitutional_header(
        {
            "path_from_lupopedia_root": rel,
            "title": "Captain Log %s" % log_id,
            "summary": (intent or "")[:200],
            "when_updated": ymdhis,
            "thread_key": thread_id,
            "lupopedia.schema": "captain_log",
            "prd_cluster": "98_C",
        }
    )
    record = {
        "header": header,
        "type": "captain_log",
        "log_id": log_id,
        "captain_id": "Eric",
        "actor_id": 10000,
        "timestamp_ymdhis": ymdhis,
        "timestamp_iso": packed_to_iso(ymdhis),
        "thread_id": thread_id,
        "intent": intent,
        "context": context,
        "decision": decision,
        "reasoning": reasoning,
        "emotional_state": emotional_state,
        "next_actions": list(next_actions or []),
    }
    abs_path = os.path.join(repo_root, rel.replace("/", os.sep))
    _write_json(abs_path, record)
    return abs_path


def write_wolfie_log(
    repo_root,
    thread_id,
    observation,
    state,
    analysis,
    recommendations,
    alerts,
    timestamp_ymdhis=None,
    seq="001",
):
    ymdhis = timestamp_ymdhis or to_packed_utc()
    log_id = "wolfie_%s_%s" % (ymdhis, seq)
    rel = "docs/logs/%s/%s/%s/%s.json" % (
        ymdhis[0:4],
        ymdhis[4:6],
        ymdhis[6:8],
        log_id,
    )
    header = generate_constitutional_header(
        {
            "path_from_lupopedia_root": rel,
            "title": "WOLFIE Log %s" % log_id,
            "summary": (observation or "")[:200],
            "when_updated": ymdhis,
            "thread_key": thread_id,
            "lupopedia.schema": "wolfie_log",
            "prd_cluster": "98_C",
        }
    )
    record = {
        "header": header,
        "type": "wolfie_log",
        "log_id": log_id,
        "wolfie_id": "Wolfie",
        "actor_id": 1,
        "timestamp_ymdhis": ymdhis,
        "timestamp_iso": packed_to_iso(ymdhis),
        "thread_id": thread_id,
        "observation": observation,
        "state": state,
        "analysis": analysis,
        "recommendations": list(recommendations or []),
        "alerts": list(alerts or []),
    }
    abs_path = os.path.join(repo_root, rel.replace("/", os.sep))
    _write_json(abs_path, record)
    return abs_path


def generate_daily_bundle(
    repo_root,
    bundle_date,
    thread_id,
    summary,
    semantic_links=None,
):
    parts = bundle_date.split("-")
    if len(parts) != 3:
        raise ValueError("bundle_date must be YYYY-MM-DD (UTC)")
    day_dir = os.path.join(repo_root, "docs", "logs", parts[0], parts[1], parts[2])
    if not os.path.isdir(day_dir):
        os.makedirs(day_dir)

    captain_logs = []
    wolfie_logs = []
    for name in sorted(os.listdir(day_dir)):
        if not name.endswith(".json") or name == "bundle.json":
            continue
        with open(os.path.join(day_dir, name), "r", encoding="utf-8") as fh:
            obj = json.load(fh)
        if obj.get("thread_id") != thread_id:
            continue
        if obj.get("type") == "captain_log":
            captain_logs.append(obj)
        elif obj.get("type") == "wolfie_log":
            wolfie_logs.append(obj)

    rel = "docs/logs/%s/%s/%s/bundle.json" % (parts[0], parts[1], parts[2])
    when = to_packed_utc()
    header = generate_constitutional_header(
        {
            "path_from_lupopedia_root": rel,
            "title": "Dual Log Bundle %s" % bundle_date,
            "summary": (summary or "")[:200],
            "when_updated": when,
            "thread_key": thread_id,
            "lupopedia.schema": "daily_bundle",
            "prd_cluster": "98_C",
        }
    )
    bundle = {
        "header": header,
        "bundle_date": bundle_date,
        "thread_id": thread_id,
        "captain_logs": captain_logs,
        "wolfie_logs": wolfie_logs,
        "semantic_links": list(semantic_links or []),
        "summary": summary,
    }
    abs_path = os.path.join(repo_root, rel.replace("/", os.sep))
    _write_json(abs_path, bundle)
    return abs_path


def _main(argv):
    root = _repo_root()
    parser = argparse.ArgumentParser(description="PRD 98_C dual operational logs")
    sub = parser.add_subparsers(dest="cmd")

    p_cap = sub.add_parser("write-captain")
    p_cap.add_argument("--thread-id", required=True)
    p_cap.add_argument("--intent", required=True)
    p_cap.add_argument("--context", default="")
    p_cap.add_argument("--decision", default="")
    p_cap.add_argument("--reasoning", default="")
    p_cap.add_argument("--emotional-state", default="")
    p_cap.add_argument("--next-action", action="append", default=[])
    p_cap.add_argument("--timestamp", default=None, help="packed UTC YYYYMMDDHHIISS")
    p_cap.add_argument("--seq", default="001")

    p_w = sub.add_parser("write-wolfie")
    p_w.add_argument("--thread-id", required=True)
    p_w.add_argument("--observation", required=True)
    p_w.add_argument("--state", default="")
    p_w.add_argument("--analysis", default="")
    p_w.add_argument("--recommendation", action="append", default=[])
    p_w.add_argument("--alert", action="append", default=[])
    p_w.add_argument("--timestamp", default=None)
    p_w.add_argument("--seq", default="001")

    p_b = sub.add_parser("bundle")
    p_b.add_argument("--date", required=True, help="YYYY-MM-DD UTC")
    p_b.add_argument("--thread-id", required=True)
    p_b.add_argument("--summary", required=True)
    p_b.add_argument(
        "--link",
        action="append",
        default=[],
        help="captain_id:wolfie_id:supporting|conflicting|clarifying",
    )

    args = parser.parse_args(argv)
    if not args.cmd:
        parser.print_help()
        return 2
    if args.cmd == "write-captain":
        path = write_captain_log(
            root,
            args.thread_id,
            args.intent,
            args.context,
            args.decision,
            args.reasoning,
            args.emotional_state,
            args.next_action,
            args.timestamp,
            args.seq,
        )
        print("[OK] " + path)
        return 0
    if args.cmd == "write-wolfie":
        path = write_wolfie_log(
            root,
            args.thread_id,
            args.observation,
            args.state,
            args.analysis,
            args.recommendation,
            args.alert,
            args.timestamp,
            args.seq,
        )
        print("[OK] " + path)
        return 0
    if args.cmd == "bundle":
        links = []
        for raw in args.link:
            bits = raw.split(":")
            if len(bits) != 3:
                raise SystemExit(
                    "bad --link; want captain_id:wolfie_id:relationship"
                )
            links.append(
                {
                    "captain_log_id": bits[0],
                    "wolfie_log_id": bits[1],
                    "relationship": bits[2],
                }
            )
        path = generate_daily_bundle(root, args.date, args.thread_id, args.summary, links)
        print("[OK] " + path)
        return 0
    return 1


if __name__ == "__main__":
    sys.exit(_main(sys.argv[1:]))
