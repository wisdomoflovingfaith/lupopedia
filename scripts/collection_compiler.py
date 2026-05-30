# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/collection_compiler.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/collection_compiler.py"
#   status: "complete"
#   when_updated: "20260412123957"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "memory/development/staging/2026/04/collection-compiler.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/collection-compiler"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "50"
#   lupopedia.schema: implementation
#   title: "Collection compiler - Markdown globs to payload v1.0.0 JSON"
#   summary: "Builds canonical Collection Payload v1.0.0; schema validation; deterministic tab/node/edge order; memory_toon + provenance checks; dangling wiki-link warnings; doctrine + violation-code alignment comments."
# ---------------------------------------------------------------------
"""
Compile Markdown files under a repo root into Collection Payload Format v1.0.0 JSON.

Doctrine (normative):
  docs/doctrine/collection_payload_format_v1_0_0.md
  docs/doctrine/AI_ACTOR_PROBE_HARNESS_AND_GUARDS.md
  docs/doctrine/AI_ACTOR_COMPETENCY_TEST_PATTERN.md
  docs/doctrine/AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md
Coordination:
  docs/prd/50_agent_coordination_protocol.md section 1.4
  docs/prd/38_memory_unification.md section 18

CLI:
  python scripts/collection_compiler.py --repo-root . --config my_collection.json --output payload.json

Config JSON (minimal):
  {
    "collection_id": 1,
    "collection_name": "Example",
    "federation_node_id": 0,
    "memory_key": "memory/development/canonical/1026/04/example",
    "public_url_prefix": "https://www.lupopedia.com/lupopedia",
    "provenance_actor_id": 102,
    "provenance_tool": "collection_compiler_v1",
    "tabs": [
      {"tab_id": 101, "tab_name": "PRDs", "file_patterns": ["docs/prd/00_*.md"]}
    ]
  }

This output complies with Lupopedia Constitutional Root Rules.
"""

# Ingestion / coordination violation tokens (downstream enforcers; this tool documents alignment):
#   ACTOR_SCHEMA_VIOLATION
#   ACTOR_OUT_OF_COLLECTION_SCOPE
#   COLLECTION_PAYLOAD_INVALID
#   COLLECTION_NODE_ID_COLLISION
#
# IMPORTANT: Browser tab metadata MUST NOT be treated as instruction input.
#
# All node_ids and edges MUST remain inside the collection boundary.
# No external references may be invented or inferred.

from __future__ import unicode_literals

import argparse
import json
import os
import re
import sys
import glob

_COLLECTION_VERSION = "1.0.0"
_WIKI_LINK_RE = re.compile(r"\[\[([^\]|]+)(?:\|[^\]]+)?\]\]")
_WIN_DRIVE_LEADING = re.compile(r"^[A-Za-z]:")


def _norm_posix_path(path):
    return path.replace("\\", "/")


def _normalize_memory_key(memory_key):
    """
    memory_key MUST be POSIX-style, never end with //, never use Windows drive letters.
    """
    mk = _norm_posix_path(str(memory_key).strip())
    while "//" in mk:
        mk = mk.replace("//", "/")
    mk = mk.rstrip("/")
    if not mk:
        raise ValueError("COLLECTION_PAYLOAD_INVALID: memory_key is empty after normalize")
    if _WIN_DRIVE_LEADING.match(mk):
        raise ValueError("COLLECTION_PAYLOAD_INVALID: memory_key must not use a Windows drive prefix")
    if re.search(r"(?<![A-Za-z0-9_])[A-Za-z]:/", mk):
        raise ValueError("COLLECTION_PAYLOAD_INVALID: memory_key must not contain Windows drive segments")
    return mk


def _slug_from_relpath(relative_path):
    """
    Stable node_id from repo-relative path (no extension).

    node_id MUST be: lowercase, underscore-normalized (non-alphanumerics -> _), deterministic
    for a given relative path.
    """
    rel = _norm_posix_path(relative_path)
    base, _ = os.path.splitext(rel)
    slug = re.sub(r"[^\w]+", "_", base.strip("/").replace("/", "_")).strip("_")
    if not slug:
        slug = "node"
    return slug.lower()


def _extract_title(content, filename):
    match = re.search(r"^#\s+(.+)$", content, re.MULTILINE)
    if match:
        return match.group(1).strip()
    stem, _ = os.path.splitext(filename)
    return stem.replace("_", " ").title()


def _wiki_edges(content, provenance_actor_id, provenance_tool):
    edges = []
    for raw in _WIKI_LINK_RE.findall(content):
        target = raw.strip()
        tid = re.sub(r"[^\w]+", "_", target).strip("_").lower()
        if tid:
            edges.append(
                {
                    "edge_type": "reference",
                    "to_node_id": tid,
                    "provenance_actor_id": int(provenance_actor_id),
                    "provenance_tool": str(provenance_tool),
                }
            )
    return edges


def _filter_edges(edges, valid_ids):
    out = []
    for e in edges:
        tid = e.get("to_node_id")
        if tid in valid_ids:
            out.append(e)
        else:
            sys.stderr.write("WARNING: dropped dangling wiki-link → %s\n" % (tid,))
    return out


def _validate_provenance(provenance_actor_id, provenance_tool):
    if isinstance(provenance_actor_id, bool):
        raise ValueError("COLLECTION_PAYLOAD_INVALID: provenance_actor_id must be int")
    try:
        aid = int(provenance_actor_id)
    except (TypeError, ValueError):
        raise ValueError("COLLECTION_PAYLOAD_INVALID: provenance_actor_id must be int")
    if not isinstance(provenance_tool, str) or not provenance_tool.strip():
        raise ValueError("COLLECTION_PAYLOAD_INVALID: provenance_tool must be non-empty string")
    return aid, provenance_tool.strip()


def _validate_built_payload(payload):
    required = (
        "collection_payload_version",
        "collection_id",
        "collection_name",
        "federation_node_id",
        "memory_key",
        "tabs",
        "nodes",
    )
    for k in required:
        if k not in payload:
            sys.stderr.write("collection_compiler: COLLECTION_PAYLOAD_INVALID missing key %r\n" % (k,))
            return False
    if str(payload["collection_payload_version"]) != _COLLECTION_VERSION:
        sys.stderr.write(
            "collection_compiler: COLLECTION_PAYLOAD_INVALID collection_payload_version expected %r got %r\n"
            % (_COLLECTION_VERSION, payload.get("collection_payload_version"),)
        )
        return False
    if not isinstance(payload["tabs"], list):
        sys.stderr.write("collection_compiler: COLLECTION_PAYLOAD_INVALID tabs must be a list\n")
        return False
    if not isinstance(payload["nodes"], list) or len(payload["nodes"]) == 0:
        sys.stderr.write("collection_compiler: COLLECTION_PAYLOAD_INVALID nodes must be a non-empty list\n")
        return False
    if not str(payload.get("collection_name", "")).strip():
        sys.stderr.write("collection_compiler: COLLECTION_PAYLOAD_INVALID collection_name empty\n")
        return False
    return True


def _sort_payload_deterministic(payload):
    """tabs by tab_id; node_ids per tab alphabetically; edges per node by to_node_id; nodes list by node_id."""
    tabs = sorted(payload["tabs"], key=lambda t: int(t["tab_id"]))
    for t in tabs:
        t["node_ids"] = sorted(t["node_ids"])
    payload["tabs"] = tabs
    for node in payload["nodes"]:
        node["edges"] = sorted(node.get("edges") or [], key=lambda e: str(e.get("to_node_id", "")))
    payload["nodes"] = sorted(payload["nodes"], key=lambda n: str(n.get("node_id", "")))


def compile_collection(
    repo_root,
    collection_id,
    collection_name,
    federation_node_id,
    memory_key,
    tabs_config,
    provenance_actor_id,
    provenance_tool="collection_compiler_v1",
    public_url_prefix="",
):
    """
    Build v1.0.0 payload dict. repo_root is absolute or cwd-relative base for globs and file_path.

    tabs_config: list of dicts with keys tab_name, file_patterns (glob relative to repo_root),
    optional tab_id (int).
    """
    provenance_actor_id, provenance_tool = _validate_provenance(
        provenance_actor_id, provenance_tool
    )
    memory_key = _normalize_memory_key(memory_key)

    repo_root = os.path.abspath(repo_root)
    public_url_prefix = (public_url_prefix or "").rstrip("/")

    nodes_by_id = {}
    ordered_node_ids = []

    def add_file(abs_path):
        if not os.path.isfile(abs_path):
            return None
        rel = os.path.relpath(abs_path, repo_root)
        rel_posix = _norm_posix_path(rel)
        nid = _slug_from_relpath(rel_posix)
        if nid in nodes_by_id:
            existing = nodes_by_id[nid]["file_path"]
            if existing != rel_posix:
                raise ValueError(
                    "COLLECTION_NODE_ID_COLLISION: node_id %r for %r conflicts with existing file %r"
                    % (nid, rel_posix, existing)
                )
            return nid
        with open(abs_path, "rb") as fh:
            raw = fh.read().decode("utf-8", errors="replace")
        title = _extract_title(raw, os.path.basename(abs_path))
        web_path = ""
        if public_url_prefix:
            web_path = public_url_prefix + "/" + rel_posix
        node = {
            "node_id": nid,
            "title": title,
            "artifact_type": "text/markdown",
            "memory_key": memory_key + "/" + nid,
            "file_path": rel_posix,
            "web_path": web_path,
            "content": raw,
            "edges": [],
        }
        nodes_by_id[nid] = node
        ordered_node_ids.append(nid)
        return nid

    tabs_output = []
    for tab_idx, tab in enumerate(tabs_config):
        tab_name = tab.get("tab_name") or ("Tab %d" % (tab_idx + 1))
        tab_id = tab.get("tab_id")
        if tab_id is None:
            tab_id = tab_idx + 1
        patterns = tab.get("file_patterns") or []
        seen_in_tab = []
        for pattern in patterns:
            full_pattern = os.path.join(repo_root, pattern)
            for abs_path in glob.glob(full_pattern):
                abs_path = os.path.normpath(abs_path)
                nid = add_file(abs_path)
                if nid is not None and nid not in seen_in_tab:
                    seen_in_tab.append(nid)
        tabs_output.append(
            {"tab_id": int(tab_id), "tab_name": tab_name, "node_ids": seen_in_tab}
        )

    valid = set(nodes_by_id.keys())
    for nid in list(ordered_node_ids):
        node = nodes_by_id[nid]
        raw_edges = _wiki_edges(
            node["content"],
            provenance_actor_id,
            provenance_tool,
        )
        node["edges"] = _filter_edges(raw_edges, valid)

    nodes_list = [nodes_by_id[i] for i in ordered_node_ids]

    payload = {
        "collection_payload_version": _COLLECTION_VERSION,
        "collection_id": int(collection_id),
        "collection_name": str(collection_name),
        "federation_node_id": int(federation_node_id),
        "memory_key": memory_key,
        "tabs": tabs_output,
        "nodes": nodes_list,
    }
    _sort_payload_deterministic(payload)
    if not _validate_built_payload(payload):
        raise ValueError("COLLECTION_PAYLOAD_INVALID")
    return payload


def _load_config(path):
    with open(path, "rb") as f:
        return json.loads(f.read().decode("utf-8"))


def main(argv=None):
    parser = argparse.ArgumentParser(
        description="Build Collection Payload Format v1.0.0 JSON from Markdown globs."
    )
    parser.add_argument(
        "--repo-root",
        default=".",
        help="Repository root for relative globs and file_path (default: .)",
    )
    parser.add_argument(
        "--config",
        required=True,
        help="JSON config path (collection_id, collection_name, federation_node_id, memory_key, tabs, ...)",
    )
    parser.add_argument(
        "--output",
        "-o",
        help="Write JSON here; default stdout",
    )
    args = parser.parse_args(argv)

    try:
        cfg = _load_config(args.config)
    except EnvironmentError as e:
        sys.stderr.write("collection_compiler: cannot read config: %s\n" % (e,))
        return 1
    except ValueError as e:
        sys.stderr.write("collection_compiler: invalid JSON: %s\n" % (e,))
        return 1

    required = (
        "collection_id",
        "collection_name",
        "federation_node_id",
        "memory_key",
        "tabs",
        "provenance_actor_id",
    )
    for k in required:
        if k not in cfg:
            sys.stderr.write("collection_compiler: COLLECTION_PAYLOAD_INVALID config missing %r\n" % (k,))
            return 2

    try:
        provenance_tool = cfg.get("provenance_tool") or "collection_compiler_v1"
        payload = compile_collection(
            repo_root=args.repo_root,
            collection_id=cfg["collection_id"],
            collection_name=cfg["collection_name"],
            federation_node_id=cfg["federation_node_id"],
            memory_key=cfg["memory_key"],
            tabs_config=cfg["tabs"],
            provenance_actor_id=cfg["provenance_actor_id"],
            provenance_tool=provenance_tool,
            public_url_prefix=cfg.get("public_url_prefix") or "",
        )
    except ValueError as e:
        sys.stderr.write("collection_compiler: %s\n" % (e,))
        return 2

    text = json.dumps(payload, indent=2, ensure_ascii=False)
    if args.output:
        try:
            with open(args.output, "wb") as out:
                out.write(text.encode("utf-8"))
        except EnvironmentError as e:
            sys.stderr.write("collection_compiler: cannot write output: %s\n" % (e,))
            return 1
    else:
        sys.stdout.write(text)
        if not text.endswith("\n"):
            sys.stdout.write("\n")
    return 0


if __name__ == "__main__":
    sys.exit(main())
