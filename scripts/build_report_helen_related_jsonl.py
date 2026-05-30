#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "scripts/build_report_helen_related_jsonl.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/build_report_helen_related_jsonl.py"
#   status: "complete"
#   when_updated: "20260416172203"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/build-report-helen-related-jsonl.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/build-report-helen-related-jsonl"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: 16
#   content_slug: "build-report-helen-related-jsonl"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Build Helen report related-files JSONL inventory"
#   summary: "Generated header"
# ---------------------------------------------------------------------
# -*- coding: utf-8 -*-
"""Regenerate report_helen_20260416_related_files.jsonl (deterministic inventory)."""
from __future__ import print_function

import json
import os
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(
    ROOT,
    "channels",
    "0",
    "development",
    "weekly_report_helen_20260416.jsonl",
    "report_helen_20260416_related_files.jsonl",
)
TS = 20260416231833
REPORT = "REPORT_EMAIL_TO_HELEN_2026_04_16.md"

PATHS = [
    ("report_md", REPORT),
    ("memory_sidecar_json", "memory/development/canonical/1026/04/weekly_report_helen_20260416.json"),
    ("memory_sidecar_toon", "memory/development/canonical/1026/04/weekly_report_helen_20260416.toon"),
    ("atoms_sidecar", "memory/atoms/1026/04/weekly_report_helen_20260416.atoms.toon"),
    ("transcript_helen_jsonl", "channels/0/development/weekly_report_helen_20260416.jsonl/transcript.jsonl"),
    ("thread_manifest_helen", "channels/0/development/weekly_report_helen_20260416.jsonl/THREAD_MANIFEST.md"),
    ("evidence_index_md", "docs/versions/4.1.2/status/weekly_report_evidence_index_20260416.md"),
    ("evidence_memory_json", "memory/development/canonical/1026/04/weekly-report-evidence-20260416.json"),
    ("evidence_memory_toon", "memory/development/canonical/1026/04/weekly-report-evidence-20260416.toon"),
    ("transcript_evidence_jsonl", "channels/0/development/weekly-report-evidence-20260416/transcript.jsonl"),
    ("thread_manifest_evidence", "channels/0/development/weekly-report-evidence-20260416/THREAD_MANIFEST.md"),
    ("translation_model", "docs/doctrine/system/TRANSLATION_MODEL.md"),
    ("continuity_doctrine", "docs/doctrine/CONTINUITY_LAYER_DOCTRINE.md"),
    ("hermes_doctrine", "docs/doctrine/HERMES_DOCTRINE.md"),
    ("hermes_memory_gateway", "docs/doctrine/HERMES_MEMORY_GATEWAY_PROTOCOL.md"),
    ("prd_82", "docs/prd/82_hermes_message_routing_memory_gateway.md"),
    ("crafty_import_sql", "database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql"),
    ("open_questions", "docs/versions/4.1.2/status/open_questions.md"),
    ("handoff_cursor", "memory/development/staging/2026/04/cursor_handoff.toon"),
    ("handoff_antigravity", "memory/development/staging/2026/04/antigravity_handoff.toon"),
    ("handoff_gemini", "memory/development/staging/2026/04/gemini_handoff.toon"),
    ("handoff_claude", "memory/development/staging/2026/04/handoff_claude.toon"),
    ("handoff_claude_alt", "memory/development/staging/2026/04/claude_handoff.toon"),
    ("handoff_vscode_audit", "memory/development/staging/2026/04/handoff_vscode_report_audit.toon"),
    ("translation_concepts_dir", "channels/0/translation/concepts"),
]


def row(role, rel):
    full = os.path.normpath(os.path.join(ROOT, rel.replace("/", os.sep)))
    isfile = os.path.isfile(full)
    isdir = os.path.isdir(full)
    exists = isfile or isdir
    sz = os.path.getsize(full) if isfile else 0
    jl = 0
    if isfile and rel.endswith(".jsonl"):
        with open(full, "r", encoding="utf-8") as f:
            jl = sum(1 for line in f if line.strip())
    return {
        "role": role,
        "path": rel.replace("\\", "/"),
        "exists": exists,
        "is_dir": bool(isdir and not isfile),
        "bytes": sz,
        "jsonl_line_count": jl,
        "verified_ts": TS,
        "report": REPORT,
    }


def main():
    rows = [row(r, p) for r, p in PATHS]
    man_rel = "channels/0/development/weekly_report_helen_20260416.jsonl/report_helen_20260416_related_files.jsonl"
    out_dir = os.path.dirname(OUT)
    if not os.path.isdir(out_dir):
        os.makedirs(out_dir)
    with open(OUT, "w", encoding="utf-8") as wf:
        for r in rows:
            wf.write(json.dumps(r, sort_keys=True, separators=(",", ":")) + "\n")
    n = sum(1 for _ in open(OUT, "r", encoding="utf-8"))
    sz_payload = os.path.getsize(OUT)
    meta = {
        "role": "related_files_manifest_jsonl",
        "path": man_rel.replace("\\", "/"),
        "exists": True,
        "is_dir": False,
        "bytes_payload_before_meta_row": sz_payload,
        "jsonl_line_count_total": n + 1,
        "verified_ts": TS,
        "report": REPORT,
    }
    with open(OUT, "a", encoding="utf-8") as wf:
        wf.write(json.dumps(meta, sort_keys=True, separators=(",", ":")) + "\n")
    meta_final = dict(meta)
    meta_final["bytes_file_total"] = os.path.getsize(OUT)
    lines = open(OUT, "r", encoding="utf-8").read().splitlines()
    lines[-1] = json.dumps(meta_final, sort_keys=True, separators=(",", ":"))
    with open(OUT, "w", encoding="utf-8") as wf:
        wf.write("\n".join(lines) + "\n")
    print("[OK] %s" % (OUT.replace("\\", "/"),))
    return 0


if __name__ == "__main__":
    sys.exit(main())
