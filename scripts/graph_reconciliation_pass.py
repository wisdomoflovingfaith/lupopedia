#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/graph_reconciliation_pass.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/graph_reconciliation_pass.py"
#   status: "not_started"
#   when_updated: "20260411154305"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/graph-reconciliation-pass.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/graph-reconciliation-pass"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "38"
#   lupopedia.schema: implementation
#   title: "Graph reconciliation pass (Pattern #7 scaffold)"
#   summary: "THOTH Pattern #7 stub: tri-surface drift bitmask; no DB writes yet"
# ---------------------------------------------------------------------
"""
graph_reconciliation_pass.py

**Pattern #7 — Graph-reconciliation pass** (scaffold only).

Spec: ``docs/versions/4.0.99/BREAKTHROUGH_REGISTRY.md`` §2.10 (tri-surface HEADER <-> GRAPH <-> MIRROR,
PRD 51 inference, 4-bit drift signature, authority routing).

This file **does not** connect to MySQL, **does not** rewrite headers or bodies, and **does not** emit
corrections. It walks Markdown (optional ``--under``), prints a **stub** per-file report so tooling and
CI can bind to the CLI shape before implementation.

Drift bits (registry convention):

  - **1** — Header disagrees with graph (non-inference)
  - **2** — Graph disagrees with header (non-inference)
  - **4** — Mirror disagrees with graph
  - **8** — Graph inference (PRD 51) disagrees with header

Usage:

  python scripts/graph_reconciliation_pass.py --under docs/prd --json
  python scripts/graph_reconciliation_pass.py --under docs/versions/4.0.99 --verbose
"""

from __future__ import annotations

import argparse
import json
import os
import sys
from pathlib import Path
from typing import Any, Dict, List

# Drift signature bits (BREAKTHROUGH_REGISTRY §2.10)
DRIFT_HEADER_VS_GRAPH = 1
DRIFT_GRAPH_VS_HEADER = 2
DRIFT_MIRROR_VS_GRAPH = 4
DRIFT_INFERENCE_VS_HEADER = 8

_SCRIPTS_DIR = Path(__file__).resolve().parent
_REPO_ROOT = _SCRIPTS_DIR.parent

SKIP_DIR_NAMES = {
    ".git",
    "node_modules",
    "vendor",
    "archive",
    "__pycache__",
    ".cursor",
}


def _iter_markdown_files(root: Path) -> List[Path]:
    out: List[Path] = []
    for dirpath, dirnames, filenames in os.walk(root):
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIR_NAMES]
        for fn in filenames:
            if fn.lower().endswith(".md"):
                out.append(Path(dirpath) / fn)
    return sorted(out)


def stub_drift_vector_for_file(_path: Path) -> int:
    """
    Placeholder: real implementation loads PRD 16 header, lupo_memory_nodes + edges, mirror stat.
    Returns 0 until wired.
    """
    return 0


def stub_authority_for_drift(_bits: int) -> str:
    """Placeholder authority label; see registry §2.10 routing table."""
    if _bits == 0:
        return "none"
    return "unimplemented_pending_wolfie"


def stub_route_owner(_bits: int) -> str:
    """Placeholder: THOTH / ANUBIS / KAIROS / human."""
    return "unassigned"


def run_pass(scan_root: Path, *, verbose: bool) -> Dict[str, Any]:
    rows: List[Dict[str, Any]] = []
    for fp in _iter_markdown_files(scan_root):
        rel = str(fp.relative_to(_REPO_ROOT)).replace("\\", "/")
        bits = stub_drift_vector_for_file(fp)
        row: Dict[str, Any] = {
            "file": rel,
            "drift_signature": bits,
            "drift_hex": "0x%x" % bits,
            "authoritative_surface": stub_authority_for_drift(bits),
            "route_owner": stub_route_owner(bits),
            "review_reason": None,
            "next_action": "Implement load_surfaces + compare_pairs per BREAKTHROUGH_REGISTRY §2.10",
        }
        rows.append(row)
        if verbose:
            print("[STUB] %s drift=%s" % (rel, row["drift_hex"]))

    return {
        "tool": "graph_reconciliation_pass.py",
        "mode": "scaffold",
        "scan_root": str(scan_root.relative_to(_REPO_ROOT)).replace("\\", "/"),
        "files_scanned": len(rows),
        "rows": rows,
    }


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Pattern #7 graph-reconciliation pass (scaffold — no DB, no writes)."
    )
    parser.add_argument(
        "--under",
        default="",
        help="Restrict scan to this path relative to repo root (default: docs/prd).",
    )
    parser.add_argument("--json", action="store_true", help="Emit JSON report on stdout.")
    parser.add_argument("-v", "--verbose", action="store_true", help="Per-file stub lines to stderr.")
    args = parser.parse_args()

    under = (args.under or "").strip() or "docs/prd"
    scan_root = (_REPO_ROOT / under).resolve()
    if not scan_root.is_dir():
        print("Not a directory: %s" % (scan_root,), file=sys.stderr)
        return 2

    report = run_pass(scan_root, verbose=bool(args.verbose))

    if args.json:
        print(json.dumps(report, indent=2))
    else:
        print(
            "[INFO] scaffold scan_root=%s files=%d (all drift 0x0 until implemented)"
            % (report["scan_root"], report["files_scanned"])
        )

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
