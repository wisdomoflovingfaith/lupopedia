#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "scripts/import_memory_edges_from_sidecar.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/import_memory_edges_from_sidecar.py"
#   status: "complete"
#   when_updated: "20260417114057"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/import-memory-edges-from-sidecar.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/import-memory-edges-from-sidecar"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "38"
#   content_slug: "import-memory-edges-from-sidecar"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Import memory edges from sidecar JSON files"
#   summary: "Scans memory sidecars, resolves outbound edge targets to memory_node_id or content_id, and inserts idempotent rows into lupo_memory_edges."
# ---------------------------------------------------------------------
"""
Reads sidecar .json files, extracts edges.outbound, and inserts rows into lupo_memory_edges.

Resolution policy for edge["to"]:
1) Try lupo_memory_nodes.memory_toon (exact and .json/.toon swapped forms)
2) Try lupo_contents.file_path_from_root or slug and use content_id as fallback reference id
3) If unresolved, skip with warning

No foreign keys exist by doctrine, so content_id fallback is allowed when a target memory node
does not exist yet.
"""

from __future__ import annotations

import argparse
import hashlib
import json
import sys
from pathlib import Path
from typing import Any, Dict, List, Optional, Tuple

import pymysql

from db_config import get_table_prefix
from lib.db_connection import get_connection_params
from lib.channel_utils import norm_path, resolve_channel_key_for_artifact


def _norm_path(path: str) -> str:
    return norm_path(path)


def _now_ymdhis() -> int:
    import time

    return int(time.strftime("%Y%m%d%H%M%S", time.gmtime()))


def _weight_to_hundredths(weight: Any) -> int:
    try:
        value = float(weight if weight is not None else 1.0)
    except Exception:
        value = 1.0
    value = max(0.0, min(1.0, value))
    return int(round(value * 100))


def _generate_node_id(cursor, table: str, now14: int) -> int:
    # Transitional duplication note:
    # DBMemoryWriter also contains this PK scheme; keep local copy for importer isolation
    # in 4.1.2 and consolidate in a shared helper in a later bounded pass.
    """Generate an 18-digit memory_node_id using the same YYYYMMDDHHIISS+4seq scheme as edges."""
    ts = str(int(now14))
    min_id = int(ts + "0000")
    max_id = int(ts + "9999")
    cursor.execute(
        "SELECT COALESCE(MAX(memory_node_id), 0) AS max_id "
        "FROM `{0}` WHERE memory_node_id BETWEEN %s AND %s".format(table),
        (min_id, max_id),
    )
    row = cursor.fetchone() or {}
    current = int((row.get("max_id") if isinstance(row, dict) else row[0]) or 0)
    if current < min_id:
        return min_id
    if current >= max_id:
        return int(str(int(ts) + 1) + "0000")
    return current + 1


# Accepted values for lupo_memory_nodes.status (maps from header trust_tier).
_VALID_NODE_STATUS = frozenset({"unsupported", "supported", "canonical"})


def _generate_edge_id(cursor, table: str, now14: int) -> int:
    # Transitional duplication note:
    # DBMemoryWriter also contains this PK scheme; keep local copy for importer isolation
    # in 4.1.2 and consolidate in a shared helper in a later bounded pass.
    ts = str(int(now14))
    min_id = int(ts + "0000")
    max_id = int(ts + "9999")
    cursor.execute(
        "SELECT COALESCE(MAX(memory_edge_id), 0) AS max_id "
        "FROM `{0}` WHERE memory_edge_id BETWEEN %s AND %s".format(table),
        (min_id, max_id),
    )
    row = cursor.fetchone() or {}
    current = int((row.get("max_id") if isinstance(row, dict) else row[0]) or 0)
    if current < min_id:
        return min_id
    if current >= max_id:
        # Overflow guard for same-second bursts.
        return int(str(int(ts) + 1) + "0000")
    return current + 1


def _resolve_memory_node_id(cursor, memory_nodes_table: str, to_ref: str) -> Optional[int]:
    candidates = [to_ref]
    if to_ref.endswith(".json"):
        candidates.append(to_ref[:-5] + ".toon")
    elif to_ref.endswith(".toon"):
        candidates.append(to_ref[:-5] + ".json")
    for cand in candidates:
        cursor.execute(
            "SELECT memory_node_id FROM `{0}` "
            "WHERE memory_toon=%s AND is_deleted=0 "
            "ORDER BY updated_ymdhis DESC LIMIT 1".format(memory_nodes_table),
            (cand,),
        )
        row = cursor.fetchone()
        if row:
            value = row.get("memory_node_id") if isinstance(row, dict) else row[0]
            return int(value)
    return None


def _resolve_content_id(cursor, contents_table: str, to_ref: str) -> Optional[int]:
    file_path = _norm_path(to_ref)
    cursor.execute(
        "SELECT content_id FROM `{0}` "
        "WHERE file_path_from_root=%s AND is_deleted=0 LIMIT 1".format(contents_table),
        (file_path,),
    )
    row = cursor.fetchone()
    if row:
        value = row.get("content_id") if isinstance(row, dict) else row[0]
        return int(value)

    slug_guess = Path(file_path).stem.strip().lower().replace("_", "-")
    if slug_guess:
        cursor.execute(
            "SELECT content_id FROM `{0}` "
            "WHERE slug=%s AND is_deleted=0 ORDER BY updated_ymdhis DESC LIMIT 1".format(contents_table),
            (slug_guess,),
        )
        row = cursor.fetchone()
        if row:
            value = row.get("content_id") if isinstance(row, dict) else row[0]
            return int(value)
    return None


def _build_sidecar_source_index(sidecar_root: Path) -> Dict[str, Dict[str, Any]]:
    """
    Build map: source_file_path_from_root -> memory_toon from sidecar JSON files.
    This allows resolving outbound 'to' file paths to their memory nodes.
    """
    index: Dict[str, Dict[str, Any]] = {}
    if not sidecar_root.exists():
        return index
    for json_path in sidecar_root.rglob("*.json"):
        if not json_path.is_file():
            continue
        try:
            with json_path.open("r", encoding="utf-8") as handle:
                doc = json.load(handle)
        except Exception:
            continue
        if not isinstance(doc, dict):
            continue
        source_path = _norm_path(str(doc.get("source_file_path_from_root") or ""))
        memory_toon = _norm_path(str(doc.get("memory_toon") or ""))
        if source_path and memory_toon and source_path not in index:
            doc["__sidecar_json_path"] = json_path.as_posix()
            index[source_path] = doc
    return index


def _resolve_memory_node_id_from_source_index(
    cursor,
    memory_nodes_table: str,
    source_index: Dict[str, Dict[str, Any]],
    to_ref: str,
) -> Optional[int]:
    source_path = _norm_path(to_ref)
    sidecar_doc = source_index.get(source_path) or {}
    memory_toon = _norm_path(str(sidecar_doc.get("memory_toon") or ""))
    if not memory_toon:
        return None
    return _resolve_memory_node_id(cursor, memory_nodes_table, memory_toon)


def _resolve_target_id(
    cursor,
    memory_nodes_table: str,
    contents_table: str,
    source_index: Dict[str, Dict[str, Any]],
    to_ref: str,
) -> Tuple[Optional[int], str]:
    to_ref = _norm_path(to_ref)
    if not to_ref:
        return None, "empty"
    if to_ref.isdigit():
        return int(to_ref), "numeric"

    memory_node_id = _resolve_memory_node_id(cursor, memory_nodes_table, to_ref)
    if memory_node_id is not None:
        return memory_node_id, "memory_node_id"

    by_source_idx = _resolve_memory_node_id_from_source_index(
        cursor, memory_nodes_table, source_index, to_ref
    )
    if by_source_idx is not None:
        return by_source_idx, "memory_node_from_source_file"

    content_id = _resolve_content_id(cursor, contents_table, to_ref)
    if content_id is not None:
        return content_id, "content_id_fallback"

    return None, "unresolved"


def _resolve_source_node_id(
    cursor,
    memory_nodes_table: str,
    contents_table: str,
    source_index: Dict[str, Dict[str, Any]],
    sidecar_data: Dict[str, Any],
) -> Tuple[Optional[int], str]:
    memory_toon = _norm_path(str(sidecar_data.get("memory_toon") or ""))
    if memory_toon:
        source_id = _resolve_memory_node_id(cursor, memory_nodes_table, memory_toon)
        if source_id is not None:
            return source_id, "memory_toon"

    source_path = _norm_path(str(sidecar_data.get("source_file_path_from_root") or ""))
    if source_path:
        source_id, source_kind = _resolve_target_id(
            cursor, memory_nodes_table, contents_table, source_index, source_path
        )
        if source_id is not None:
            return source_id, source_kind
    return None, "unresolved"


def _extract_edges_for_import(data: Dict[str, Any]) -> Tuple[List[Dict[str, Any]], bool]:
    """
    Build normalized edge work items from sidecar edges.
    Supports outbound and inbound declarations.

    Outbound item shape:
      {"direction":"outbound","to":"...","type":"...","context":"...","weight":...}
    Inbound item shape:
      {"direction":"inbound","from":"...","type":"...","context":"...","weight":...}
    """
    work_items: List[Dict[str, Any]] = []
    edges_obj_raw = data.get("edges")
    edges_obj = edges_obj_raw or {}
    complete = True
    if not isinstance(edges_obj, dict):
        return work_items, False

    outbound = edges_obj.get("outbound", [])
    if isinstance(outbound, list):
        for edge in outbound:
            if isinstance(edge, dict):
                item = dict(edge)
                item["direction"] = "outbound"
                work_items.append(item)
    elif outbound not in (None, ""):
        complete = False

    inbound = edges_obj.get("inbound", [])
    if isinstance(inbound, list):
        for edge in inbound:
            if isinstance(edge, dict):
                item = dict(edge)
                item["direction"] = "inbound"
                work_items.append(item)
    elif inbound not in (None, ""):
        complete = False

    return work_items, complete


def _edge_signature(channel_key: str, edge_type: str, to_node_id: int) -> str:
    return "{0}|{1}|{2}".format(str(channel_key or ""), str(edge_type or ""), int(to_node_id))


def _soft_delete_obsolete_source_edges(
    cursor,
    memory_edges_table: str,
    source_node_id: int,
    channel_key: str,
    expected_signatures: set,
    now14: int,
) -> int:
    """
    Soft-delete obsolete importer-managed outbound edges for a source node.
    This function is the importer's "replace" mode reconciliation step.
    Scope is intentionally narrow:
      - from_memory_node_id = source_node_id
      - channel_key matches
      - provenance_tool = import_memory_edges_from_sidecar.py
      - active rows only
    """
    cursor.execute(
        "SELECT memory_edge_id, edge_type, to_memory_node_id FROM `{0}` "
        "WHERE from_memory_node_id=%s AND channel_key=%s AND provenance_tool=%s AND is_deleted=0".format(
            memory_edges_table
        ),
        (source_node_id, channel_key, "import_memory_edges_from_sidecar.py"),
    )
    rows = cursor.fetchall() or []
    deleted = 0
    for row in rows:
        edge_id = int(row.get("memory_edge_id") if isinstance(row, dict) else row[0])
        edge_type = str(row.get("edge_type") if isinstance(row, dict) else row[1])
        to_node_id = int(row.get("to_memory_node_id") if isinstance(row, dict) else row[2])
        sig = _edge_signature(channel_key, edge_type, to_node_id)
        if sig in expected_signatures:
            continue
        cursor.execute(
            "UPDATE `{0}` SET is_deleted=1, deleted_ymdhis=%s, updated_ymdhis=%s WHERE memory_edge_id=%s".format(
                memory_edges_table
            ),
            (now14, now14, edge_id),
        )
        deleted += 1
    return deleted


def backfill_memory_node_channel_key(
    cursor,
    memory_nodes_table: str,
    dry_run: bool = False,
) -> Dict[str, int]:
    """
    One-time backfill for existing rows with null/empty channel_key.
    Derives from memory_toon path using doctrine helper.
    """
    summary = {"scanned": 0, "updated": 0, "skipped": 0}
    cursor.execute(
        "SELECT memory_node_id, memory_toon, channel_key FROM `{0}` "
        "WHERE is_deleted=0 AND (channel_key IS NULL OR channel_key='')".format(memory_nodes_table)
    )
    rows = cursor.fetchall() or []
    summary["scanned"] = len(rows)
    for row in rows:
        memory_node_id = int(row.get("memory_node_id") if isinstance(row, dict) else row[0])
        memory_toon = str(row.get("memory_toon") if isinstance(row, dict) else row[1] or "")
        meta = resolve_channel_key_for_artifact(None, memory_toon, None)
        derived = str(meta.get("channel_key") or "")
        if not derived:
            print(
                "[WARN] backfill-channel-key: unresolved memory_node_id={0} memory_toon={1!r}".format(
                    memory_node_id, memory_toon
                )
            )
            summary["skipped"] += 1
            continue
        print(
            "[INFO] backfill-channel-key memory_node_id={0} channel_key={1!r} source={2}".format(
                memory_node_id, derived, meta.get("source")
            )
        )
        if not dry_run:
            cursor.execute(
                "UPDATE `{0}` SET channel_key=%s WHERE memory_node_id=%s".format(memory_nodes_table),
                (derived, memory_node_id),
            )
        summary["updated"] += 1
    return summary


def _auto_create_source_node(
    cursor,
    memory_nodes_table: str,
    sidecar_data: Dict[str, Any],
    dry_run: bool,
    now14: int,
) -> Optional[int]:
    """Insert a minimal source node into lupo_memory_nodes if one does not already exist.

    Idempotent: queries by memory_toon before inserting regardless of is_deleted state,
    so repeated calls never produce duplicate rows.

    Column mapping from sidecar -> DB:
      sidecar["memory_toon"]          -> memory_toon   (lookup key + stored value)
      sidecar["type"]                 -> memory_type   (user's "node_type")
      sidecar["tags"][1] (trust_tier) -> status        (user's "trust_tier")
      sidecar["purpose"]              -> memory_value  (user's "summary")
      SHA-256(memory_toon)            -> content_hash  (NOT NULL, stable deterministic)
      footer["last_verified_by_actor_id"] or 102 -> owner_actor_id

    Returns the memory_node_id (existing or newly created), or None on failure.
    """
    memory_toon = _norm_path(str(sidecar_data.get("memory_toon") or ""))
    if not memory_toon:
        print("[WARN] auto-create-source-node: sidecar missing memory_toon — cannot create node")
        return None
    channel_meta = resolve_channel_key_for_artifact(
        sidecar_data.get("channel_key"),
        memory_toon,
    )
    if channel_meta.get("error"):
        print(
            "[WARN] auto-create-source-node: {0}; explicit={1!r}; derived={2!r}; memory_toon={3}".format(
                channel_meta.get("error"),
                channel_meta.get("explicit"),
                channel_meta.get("derived"),
                memory_toon,
            )
        )
        return None
    channel_key = str(channel_meta.get("channel_key") or "")
    if not channel_key:
        print(
            "[WARN] auto-create-source-node: channel_key unresolved; explicit={0!r}; derived={1!r}; memory_toon={2}".format(
                channel_meta.get("explicit"),
                channel_meta.get("derived"),
                memory_toon,
            )
        )
        return None
    print(
        "[INFO] auto-create-source-node channel_key={0!r} source={1} explicit={2!r} derived={3!r}".format(
            channel_key,
            channel_meta.get("source"),
            channel_meta.get("explicit"),
            channel_meta.get("derived"),
        )
    )

    # Idempotency check (covers deleted rows to avoid duplicate memory_toon values)
    cursor.execute(
        "SELECT memory_node_id FROM `{0}` WHERE memory_toon=%s LIMIT 1".format(memory_nodes_table),
        (memory_toon,),
    )
    row = cursor.fetchone()
    if row:
        existing_id = int(row.get("memory_node_id") if isinstance(row, dict) else row[0])
        print(
            "[OK] auto-create-source-node: node already exists"
            " (memory_node_id={0}) — {1}".format(existing_id, memory_toon)
        )
        return existing_id

    # Resolve fields from sidecar data
    memory_type = str(sidecar_data.get("type") or "header_metadata")[:32]
    memory_value: Optional[str] = str(sidecar_data.get("purpose") or "") or None

    tags = sidecar_data.get("tags") or []
    raw_trust = tags[1] if isinstance(tags, list) and len(tags) > 1 else ""
    status = raw_trust if raw_trust in _VALID_NODE_STATUS else "unsupported"

    footer = sidecar_data.get("footer") or {}
    owner_actor_id = int(footer.get("last_verified_by_actor_id") or 102)

    content_hash = hashlib.sha256(memory_toon.encode("utf-8")).hexdigest()
    node_id = _generate_node_id(cursor, memory_nodes_table, now14)

    if dry_run:
        print(
            "[DRY-RUN] auto-create-source-node: would INSERT memory_node_id={0}"
            " memory_type={1} status={2} — {3}".format(node_id, memory_type, status, memory_toon)
        )
        return node_id

    cursor.execute(
        "INSERT INTO `{0}` ("
        "  memory_node_id, created_ymdhis, owner_actor_id, owner_type,"
        "  memory_type, memory_toon, memory_value, context, status,"
        "  content_hash, channel_key, updated_ymdhis, expires_ymdhis, is_deleted, deleted_ymdhis"
        ") VALUES (%s, %s, %s, 'actor', %s, %s, %s, 'experiential', %s, %s, %s, %s, 0, 0, 0)".format(
            memory_nodes_table
        ),
        (
            node_id,
            now14,
            owner_actor_id,
            memory_type,
            memory_toon,
            memory_value,
            status,
            content_hash,
            channel_key,
            now14,  # updated_ymdhis = created_ymdhis on first insert
        ),
    )
    print(
        "[CREATED] auto-create-source-node: memory_node_id={0}"
        " memory_type={1} status={2} — {3}".format(node_id, memory_type, status, memory_toon)
    )
    return node_id


def import_edges_from_sidecar(
    cursor,
    memory_edges_table: str,
    memory_nodes_table: str,
    contents_table: str,
    sidecar_path: Path,
    sidecar_source_index: Dict[str, Dict[str, Any]],
    dry_run: bool = False,
    auto_create_source_node: bool = False,
    edge_mode: str = "replace",
) -> Dict[str, int]:
    with sidecar_path.open("r", encoding="utf-8") as handle:
        data = json.load(handle)

    result = {"inserted": 0, "skipped": 0, "unresolved": 0, "soft_deleted": 0}
    mode = str(edge_mode or "replace").strip().lower()
    if mode not in ("additive", "replace", "merge"):
        raise RuntimeError("invalid --edge-mode: {0!r}".format(edge_mode))
    edge_items, sidecar_complete = _extract_edges_for_import(data)
    if not edge_items:
        if not sidecar_complete:
            print("[WARN] {0}: incomplete sidecar edges payload; no edge writes".format(sidecar_path.as_posix()))
        return result

    # Compute now14 and provenance before the source-node guard so both the
    # auto-create path and the edge-insertion loop share the same timestamp.
    footer = data.get("footer", {})
    provenance_actor_id = int((footer.get("last_verified_by_actor_id") or 102))
    now14 = _now_ymdhis()

    from_id, from_kind = _resolve_source_node_id(
        cursor, memory_nodes_table, contents_table, sidecar_source_index, data
    )
    source_channel_meta = resolve_channel_key_for_artifact(
        data.get("channel_key"),
        data.get("memory_toon"),
    )
    if source_channel_meta.get("error"):
        print(
            "[WARN] {0}: channel_key mismatch; explicit={1!r} derived={2!r} — skipping artifact".format(
                sidecar_path.as_posix(),
                source_channel_meta.get("explicit"),
                source_channel_meta.get("derived"),
            )
        )
        result["unresolved"] += len(edge_items)
        return result
    source_channel_key = str(source_channel_meta.get("channel_key") or "")
    if source_channel_key:
        print(
            "[INFO] {0}: channel_key={1!r} source={2} explicit={3!r} derived={4!r}".format(
                sidecar_path.as_posix(),
                source_channel_key,
                source_channel_meta.get("source"),
                source_channel_meta.get("explicit"),
                source_channel_meta.get("derived"),
            )
        )
    else:
        print(
            "[WARN] {0}: channel_key unresolved; explicit={1!r} derived={2!r} — skipping artifact".format(
                sidecar_path.as_posix(),
                source_channel_meta.get("explicit"),
                source_channel_meta.get("derived"),
            )
        )
        result["unresolved"] += len(edge_items)
        return result

    if from_id is None:
        if auto_create_source_node:
            from_id = _auto_create_source_node(
                cursor, memory_nodes_table, data, dry_run, now14
            )
            if from_id is None:
                print(
                    "[WARN] {0}: auto-create-source-node failed; skipping all edges".format(
                        sidecar_path.as_posix()
                    )
                )
                result["unresolved"] += len(edge_items)
                return result
            from_kind = "auto_created"
        else:
            print(
                "[WARN] {0}: cannot resolve source node ({1}); skipping all edges".format(
                    sidecar_path.as_posix(), from_kind
                )
            )
            result["unresolved"] += len(edge_items)
            return result

    print(
        "[INFO] {0}: source node resolved kind={1} id={2}".format(
            sidecar_path.as_posix(),
            from_kind,
            from_id,
        )
    )
    unresolved_targets = 0
    duplicate_skips = 0
    expected_source_signatures = set()

    for edge in edge_items:
        if not isinstance(edge, dict):
            result["skipped"] += 1
            continue
        direction = str(edge.get("direction") or "outbound").strip().lower()
        if direction == "inbound":
            other_ref = _norm_path(str(edge.get("from") or edge.get("to") or ""))
            other_id, other_kind = _resolve_target_id(
                cursor, memory_nodes_table, contents_table, sidecar_source_index, other_ref
            )
            if other_id is None and auto_create_source_node:
                target_doc = sidecar_source_index.get(_norm_path(other_ref))
                if isinstance(target_doc, dict):
                    created_target_id = _auto_create_source_node(
                        cursor, memory_nodes_table, target_doc, dry_run, now14
                    )
                    if created_target_id is not None:
                        other_id = created_target_id
                        other_kind = "auto_created_target"
            if other_id is None:
                print(
                    "[WARN] {0}: unresolved inbound edge source '{1}'".format(
                        sidecar_path.as_posix(), other_ref
                    )
                )
                result["unresolved"] += 1
                unresolved_targets += 1
                continue
            edge_from_id = other_id
            edge_to_id = from_id
            target_kind = other_kind
            target_ref = other_ref
        else:
            target_ref = _norm_path(str(edge.get("to") or ""))
            target_id, target_kind = _resolve_target_id(
                cursor, memory_nodes_table, contents_table, sidecar_source_index, target_ref
            )
            if target_id is None and auto_create_source_node:
                target_doc = sidecar_source_index.get(_norm_path(target_ref))
                if isinstance(target_doc, dict):
                    created_target_id = _auto_create_source_node(
                        cursor, memory_nodes_table, target_doc, dry_run, now14
                    )
                    if created_target_id is not None:
                        target_id = created_target_id
                        target_kind = "auto_created_target"
            if target_id is None:
                print(
                    "[WARN] {0}: unresolved edge target '{1}'".format(
                        sidecar_path.as_posix(),
                        target_ref,
                    )
                )
                result["unresolved"] += 1
                unresolved_targets += 1
                continue
            edge_from_id = from_id
            edge_to_id = target_id
            target_kind = target_kind

        to_id = edge_to_id
        from_write_id = edge_from_id
        to_kind = target_kind
        to_ref = target_ref

        if to_id is None:
            print("[WARN] {0}: unresolved edge target '{1}'".format(sidecar_path.as_posix(), to_ref))
            result["unresolved"] += 1
            unresolved_targets += 1
            continue

        edge_type = str(edge.get("type") or "references")[:64]
        edge_context = str(edge.get("context") or "system_generated")[:32]
        weight = _weight_to_hundredths(edge.get("weight"))
        if from_write_id == from_id:
            expected_source_signatures.add(_edge_signature(source_channel_key, edge_type, to_id))

        cursor.execute(
            "SELECT memory_edge_id FROM `{0}` "
            "WHERE from_memory_node_id=%s AND to_memory_node_id=%s AND edge_type=%s AND is_deleted=0 "
            "LIMIT 1".format(memory_edges_table),
            (from_write_id, to_id, edge_type),
        )
        exists = cursor.fetchone()
        if exists:
            result["skipped"] += 1
            duplicate_skips += 1
            continue

        edge_id = _generate_edge_id(cursor, memory_edges_table, now14)
        if dry_run:
            print(
                "[DRY-RUN] {0}: {1} -> {2} ({3}) [{4}]".format(
                    sidecar_path.as_posix(), from_write_id, to_id, edge_type, to_kind
                )
            )
            result["inserted"] += 1
            continue

        cursor.execute(
            "INSERT INTO `{0}` ("
            "memory_edge_id, from_memory_node_id, to_memory_node_id, edge_type, edge_context, channel_key, "
            "edge_status, edge_direction, weight_hundredths, provenance_actor_id, provenance_tool, "
            "review_reason, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis"
            ") VALUES (%s, %s, %s, %s, %s, %s, 'supported', 'unidirectional', %s, %s, %s, NULL, %s, %s, 0, 0)".format(
                memory_edges_table
            ),
            (
                edge_id,
                from_write_id,
                to_id,
                edge_type,
                edge_context,
                source_channel_key,
                weight,
                provenance_actor_id,
                "import_memory_edges_from_sidecar.py",
                now14,
                now14,
            ),
        )
        result["inserted"] += 1

    if mode == "additive":
        print(
            "[INFO] {0}: edge_mode=additive; obsolete-edge soft-delete disabled".format(
                sidecar_path.as_posix()
            )
        )
    elif mode == "merge":
        print(
            "[INFO] {0}: edge_mode=merge; duplicate-safe insert only, no obsolete-edge soft-delete".format(
                sidecar_path.as_posix()
            )
        )
    elif not sidecar_complete:
        print(
            "[WARN] {0}: incomplete sidecar edges payload; additive import only, no obsolete-edge soft-delete".format(
                sidecar_path.as_posix()
            )
        )
    elif unresolved_targets > 0:
        # Safety gate:
        # when any target is unresolved, payload is partial from importer perspective,
        # so we skip replace-mode obsolete soft-deletes to avoid destructive drift.
        print(
            "[WARN] {0}: unresolved targets present; obsolete-edge soft-delete skipped".format(
                sidecar_path.as_posix()
            )
        )
    else:
        if dry_run:
            print(
                "[DRY-RUN] {0}: would soft-delete obsolete importer-managed edges for source_node_id={1}".format(
                    sidecar_path.as_posix(), from_id
                )
            )
        else:
            deleted_count = _soft_delete_obsolete_source_edges(
                cursor,
                memory_edges_table,
                from_id,
                source_channel_key,
                expected_source_signatures,
                now14,
            )
            result["soft_deleted"] += deleted_count
            if deleted_count > 0:
                print(
                    "[INFO] {0}: soft-deleted obsolete edges count={1}".format(
                        sidecar_path.as_posix(), deleted_count
                    )
                )

    if unresolved_targets > 0:
        print(
            "[WARN] {0}: partial ingestion; source node created/resolved but unresolved_edges={1}".format(
                sidecar_path.as_posix(),
                unresolved_targets,
            )
        )
    print(
        "[INFO] {0}: edge import summary inserted={1} duplicate_skipped={2} unresolved={3} soft_deleted={4}".format(
            sidecar_path.as_posix(),
            result["inserted"],
            duplicate_skips,
            unresolved_targets,
            result.get("soft_deleted", 0),
        )
    )
    return result


def _iter_sidecars(root: Path) -> List[Path]:
    if not root.exists():
        return []
    return sorted([p for p in root.rglob("*.json") if p.is_file()])


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Import lupo_memory_edges from sidecar JSON edges.outbound"
    )
    parser.add_argument(
        "--root",
        default="memory",
        help="Root directory to scan for sidecar .json files (default: memory)",
    )
    parser.add_argument(
        "--sidecar",
        default="",
        help="Import one specific sidecar path instead of scanning --root",
    )
    parser.add_argument("--dry-run", action="store_true", help="Resolve and print; do not insert")
    parser.add_argument(
        "--auto-create-source-node",
        action="store_true",
        help=(
            "When the source memory node cannot be resolved, create a minimal node in "
            "lupo_memory_nodes using sidecar data (memory_toon, memory_type, status, "
            "memory_value, created_ymdhis), then proceed with edge insertion as normal. "
            "Idempotent: checks existence before inserting."
        ),
    )
    parser.add_argument(
        "--backfill-channel-key",
        action="store_true",
        help=(
            "One-time backfill: populate lupo_memory_nodes.channel_key for active rows where it is NULL/empty, "
            "derived from memory_toon path using doctrine order."
        ),
    )
    parser.add_argument(
        "--edge-mode",
        default="replace",
        choices=["additive", "replace", "merge"],
        help=(
            "Edge migration mode for importer-managed obsolete edges. "
            "replace=soft-delete obsolete source edges (default), "
            "additive/merge keep existing edges."
        ),
    )
    args = parser.parse_args()

    repo_root = Path(__file__).resolve().parent.parent
    sidecar_source_index = _build_sidecar_source_index((repo_root / "memory").resolve())
    sidecar_paths: List[Path]
    if args.sidecar.strip():
        sidecar_paths = [Path(args.sidecar).resolve()]
    else:
        sidecar_paths = _iter_sidecars((repo_root / args.root).resolve())

    if not sidecar_paths and not args.backfill_channel_key:
        print("[OK] No sidecar files found.")
        return 0

    params = dict(get_connection_params())
    params["charset"] = params.get("charset") or "utf8mb4"
    params["cursorclass"] = pymysql.cursors.DictCursor
    prefix = get_table_prefix()
    memory_edges_table = "{0}memory_edges".format(prefix)
    memory_nodes_table = "{0}memory_nodes".format(prefix)
    contents_table = "{0}contents".format(prefix)

    conn = pymysql.connect(**params)
    totals = {"files": 0, "inserted": 0, "skipped": 0, "unresolved": 0, "soft_deleted": 0}
    try:
        with conn.cursor() as cursor:
            if args.backfill_channel_key:
                backfill_summary = backfill_memory_node_channel_key(
                    cursor,
                    memory_nodes_table,
                    dry_run=bool(args.dry_run),
                )
                print(
                    "[OK] backfill-channel-key scanned={scanned} updated={updated} skipped={skipped}".format(
                        **backfill_summary
                    )
                )
            for sidecar_path in sidecar_paths:
                try:
                    result = import_edges_from_sidecar(
                        cursor,
                        memory_edges_table,
                        memory_nodes_table,
                        contents_table,
                        sidecar_path,
                        sidecar_source_index,
                        dry_run=bool(args.dry_run),
                        auto_create_source_node=bool(args.auto_create_source_node),
                        edge_mode=str(args.edge_mode or "replace"),
                    )
                except Exception as exc:
                    print("[WARN] {0}: {1}".format(sidecar_path.as_posix(), exc))
                    continue
                totals["files"] += 1
                totals["inserted"] += result["inserted"]
                totals["skipped"] += result["skipped"]
                totals["unresolved"] += result["unresolved"]
                totals["soft_deleted"] += int(result.get("soft_deleted", 0))

        if args.dry_run:
            conn.rollback()
        else:
            conn.commit()
    finally:
        conn.close()

    print(
        "[OK] files={files} inserted={inserted} skipped={skipped} unresolved={unresolved} soft_deleted={soft_deleted}".format(
            **totals
        )
    )
    return 0


if __name__ == "__main__":
    sys.exit(main())
