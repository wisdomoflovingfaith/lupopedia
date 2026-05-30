#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "scripts/lib/db_memory_writer.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/lib/db_memory_writer.py"
#   status: "complete"
#   when_updated: "20260417113952"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/db-memory-writer.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/db-memory-writer"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "38"
#   content_slug: "db-memory-writer"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Database memory node and edge writer"
#   summary: "Shared DB writer for lupo_memory_nodes and lupo_memory_edges with idempotent insert and channel key derivation support."
# ---------------------------------------------------------------------
"""
DB Memory Writer - shared DB-first writer for memory scripts.

This utility inserts memory nodes/edges into canonical tables:
- lupo_memory_nodes
- lupo_memory_edges

Filesystem mirrors are handled separately by MemoryExportService (PHP).
"""

from __future__ import annotations

import argparse
import hashlib
import json
import os
import re
import sys
from datetime import datetime, timezone
from pathlib import Path
from typing import Any, Dict, Iterable, List, Optional, Tuple

_LIB_DIR = os.path.dirname(os.path.abspath(__file__))
_SCRIPTS_DIR = os.path.dirname(_LIB_DIR)
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

from lib.db_connection import get_connection  # type: ignore
from lib.channel_utils import resolve_channel_key_for_artifact
from lib.string_utils import sanitize_text


class DBMemoryWriter:
    def __init__(
        self,
        table_prefix: Optional[str] = None,
        dry_run: bool = False,
        fallback_to_filesystem: bool = True,
    ):
        self.conn = None
        self.table_prefix = table_prefix or self._detect_table_prefix()
        self.dry_run = bool(dry_run)
        self.fallback_to_filesystem = bool(fallback_to_filesystem)
        self._filesystem_fallback_active = False
        self.is_db_available = False
        # Repository root: scripts/lib -> scripts -> repo
        self._repo_root = str(Path(__file__).resolve().parent.parent.parent)
        self._connect()

    # ------------------------------------------------------------------
    # Public API


    # ------------------------------------------------------------------

    def create_memory_node(
        self,
        toon_data: Dict[str, Any],
        source_key: Optional[str] = None,
        owner_actor_id: Optional[int] = None,
        filesystem_path: Optional[str] = None,
    ) -> int:
        """
        Insert/return one memory node from toon-style data.

        Idempotency:
        - content_hash + owner_actor_id + memory_toon + memory_type
        - if matched and row is active, returns existing memory_node_id.
        """
        if not self.is_db_available:
            if self.fallback_to_filesystem:
                return self._write_toon_file_fallback(toon_data, filesystem_path)
            raise RuntimeError("DB unavailable and fallback disabled")

        toon_data = self._sanitize_toon_data(toon_data)
        now14 = self._utc14()
        node_id = self._generate_pk("memory_nodes", "memory_node_id", now14)

        memory_type = str(toon_data.get("type") or "transcript_memory")[:32]
        owner_id = self._to_int(
            owner_actor_id if owner_actor_id is not None else toon_data.get("actor_id"), 0
        )
        summary = str(toon_data.get("summary") or "")
        content_obj = toon_data.get("content")
        if content_obj is None:
            content_obj = {}
        payload_obj = {
            "id": toon_data.get("id"),
            "ts": toon_data.get("ts"),
            "summary": summary,
            "content": content_obj,
            "schema_version": toon_data.get("schema_version"),
            "status": toon_data.get("status"),
        }
        memory_value = json.dumps(payload_obj, ensure_ascii=False, sort_keys=True)
        content_hash = hashlib.sha256(memory_value.encode("utf-8")).hexdigest()

        memory_key = str(
            source_key
            or toon_data.get("id")
            or toon_data.get("memory_toon")  # v4.1.0 field name
            or self._fallback_memory_key(toon_data, now14)
        )[:255]
        channel_meta = resolve_channel_key_for_artifact(
            toon_data.get("channel_key"),
            toon_data.get("memory_toon") or memory_key,
            toon_data.get("file_path_from_root"),
        )
        if channel_meta.get("error"):
            raise RuntimeError(
                "create_memory_node: {0}; explicit={1!r}; derived={2!r}; memory_toon={3!r}".format(
                    channel_meta.get("error"),
                    channel_meta.get("explicit"),
                    channel_meta.get("derived"),
                    toon_data.get("memory_toon") or memory_key,
                )
            )
        channel_key = str(channel_meta.get("channel_key") or "")
        sys.stderr.write(
            "[INFO] create_memory_node channel_key={0!r} source={1} explicit={2!r} derived={3!r}\n".format(
                channel_key,
                channel_meta.get("source"),
                channel_meta.get("explicit"),
                channel_meta.get("derived"),
            )
        )

        row = {
            "memory_node_id": node_id,
            "created_ymdhis": self._to_int(now14, 0),
            "owner_actor_id": owner_id,
            "owner_type": "actor",
            "memory_type": memory_type,
            "memory_toon": memory_key,
            "memory_value": memory_value,
            "context": str(toon_data.get("context") or "experiential")[:32],
            "status": str(toon_data.get("status") or "supported")[:32],
            "review_reason": str(toon_data.get("review_reason") or "")[:64] or None,
            "content_hash": content_hash,
            "context_json": self._encode_json_or_none({"source": "python_db_writer"}),
            "channel_key": channel_key,
            "updated_ymdhis": self._to_int(now14, 0),
            "expires_ymdhis": 0,
        }

        if self.dry_run:
            return node_id

        with self.conn.cursor() as cur:
            # Idempotency lookup first.
            sql_find = (
                "SELECT memory_node_id FROM `{t}` "
                "WHERE content_hash=%s AND owner_actor_id=%s AND memory_toon=%s "
                "AND memory_type=%s AND is_deleted=0 LIMIT 1"
            ).format(t=self._table("memory_nodes"))
            cur.execute(
                sql_find,
                (
                    row["content_hash"],
                    row["owner_actor_id"],
                    row["memory_toon"],
                    row["memory_type"],
                ),
            )
            found = cur.fetchone()
            if found:
                existing = found["memory_node_id"] if isinstance(found, dict) else found[0]
                self.conn.commit()
                return int(existing)

            sql_insert = (
                "INSERT INTO `{t}` "
                "(memory_node_id, created_ymdhis, owner_actor_id, owner_type, memory_type, "
                "memory_toon, memory_value, context, status, review_reason, content_hash, context_json, channel_key, "
                "updated_ymdhis, expires_ymdhis, is_deleted, deleted_ymdhis) "
                "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 0, 0)"
            ).format(t=self._table("memory_nodes"))
            cur.execute(
                sql_insert,
                (
                    row["memory_node_id"],
                    row["created_ymdhis"],
                    row["owner_actor_id"],
                    row["owner_type"],
                    row["memory_type"],
                    row["memory_toon"],
                    row["memory_value"],
                    row["context"],
                    row["status"],
                    row["review_reason"],
                    row["content_hash"],
                    row["context_json"],
                    row["channel_key"],
                    row["updated_ymdhis"],
                    row["expires_ymdhis"],
                ),
            )
        self.conn.commit()
        return node_id

    def create_memory_edges(
        self,
        from_node_id: int,
        edges: Iterable[Dict[str, Any]],
        provenance_actor_id: Optional[int] = None,
        provenance_tool: str = "python_db_writer",
        channel_context: Optional[Dict[str, Any]] = None,
        edge_migration_mode: str = "additive",
    ) -> int:
        """
        Insert memory edges from toon-style edge entries.
        Default migration mode is "additive".
        Returns number of inserted edges.
        """
        if not self.is_db_available:
            return 0

        inserted = 0
        now14 = self._utc14()
        actor_id = self._to_int(provenance_actor_id, 0)
        context = channel_context or {}
        migration_mode = str(edge_migration_mode or "additive").strip().lower()
        if migration_mode not in ("additive", "replace", "merge"):
            raise RuntimeError(
                "create_memory_edges: unsupported edge_migration_mode={0!r}".format(edge_migration_mode)
            )
        source_memory_toon = str(context.get("memory_toon") or "").strip()
        channel_meta = resolve_channel_key_for_artifact(
            context.get("channel_key"),
            source_memory_toon,
            context.get("file_path_from_root"),
        )
        if channel_meta.get("error"):
            raise RuntimeError(
                "create_memory_edges: {0}; explicit={1!r}; derived={2!r}; memory_toon={3!r}".format(
                    channel_meta.get("error"),
                    channel_meta.get("explicit"),
                    channel_meta.get("derived"),
                    source_memory_toon,
                )
            )
        channel_key = str(channel_meta.get("channel_key") or "")
        if not channel_key:
            channel_key = str(self._lookup_channel_key_by_node_id(from_node_id) or "")
            if channel_key:
                channel_meta["source"] = "source_node"
        if not channel_key:
            raise RuntimeError(
                "create_memory_edges: channel_key unresolved for from_node_id={0}".format(from_node_id)
            )
        sys.stderr.write(
            "[INFO] create_memory_edges channel_key={0!r} source={1} explicit={2!r} derived={3!r}\n".format(
                channel_key,
                channel_meta.get("source"),
                channel_meta.get("explicit"),
                channel_meta.get("derived"),
            )
        )

        planned_edges: List[Tuple[int, str, str, int]] = []
        for edge in edges or []:
            if not isinstance(edge, dict):
                continue
            to_ref = sanitize_text(edge.get("to"))
            if not to_ref:
                continue
            to_node_id = self.resolve_symbolic_ref(str(to_ref), context)
            if not to_node_id:
                continue

            edge_type = sanitize_text(edge.get("type") or "references")[:64]
            edge_context = sanitize_text(edge.get("context") or "system_generated")[:32]
            weight_hundredths = self._weight_to_hundredths(edge.get("weight"))
            planned_edges.append((to_node_id, edge_type, edge_context, weight_hundredths))

        if not planned_edges:
            return 0
        if self.dry_run:
            return len(planned_edges)

        if migration_mode == "replace":
            deleted_count = self._soft_delete_edges_for_replace_mode(
                from_node_id,
                actor_id,
                sanitize_text(provenance_tool)[:64],
                self._to_int(now14, 0),
            )
            if deleted_count > 0:
                sys.stderr.write(
                    "[INFO] create_memory_edges replace-mode soft-deleted={0} from_node_id={1}\n".format(
                        deleted_count, from_node_id
                    )
                )

        for to_node_id, edge_type, edge_context, weight_hundredths in planned_edges:
            edge_id = self._generate_pk("memory_edges", "memory_edge_id", now14)

            with self.conn.cursor() as cur:
                if migration_mode == "merge":
                    merge_deleted = self._soft_delete_exact_duplicate_edges(
                        cur,
                        from_node_id,
                        to_node_id,
                        edge_type,
                        actor_id,
                        sanitize_text(provenance_tool)[:64],
                        self._to_int(now14, 0),
                    )
                    if merge_deleted > 0:
                        sys.stderr.write(
                            "[INFO] create_memory_edges merge-mode duplicate-soft-deleted={0} from={1} to={2} edge_type={3}\n".format(
                                merge_deleted, from_node_id, to_node_id, edge_type
                            )
                        )
                # Idempotency: skip if same from/to/type already active.
                sql_find = (
                    "SELECT memory_edge_id FROM `{t}` "
                    "WHERE from_memory_node_id=%s AND to_memory_node_id=%s "
                    "AND edge_type=%s AND is_deleted=0 LIMIT 1"
                ).format(t=self._table("memory_edges"))
                cur.execute(sql_find, (from_node_id, to_node_id, edge_type))
                found = cur.fetchone()
                if found:
                    continue

                sql_insert = (
                    "INSERT INTO `{t}` "
                    "(memory_edge_id, from_memory_node_id, to_memory_node_id, edge_type, edge_context, "
                    "edge_status, edge_direction, channel_key, weight_hundredths, provenance_actor_id, provenance_tool, "
                    "review_reason, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) "
                    "VALUES (%s, %s, %s, %s, %s, 'supported', 'unidirectional', %s, %s, %s, %s, NULL, %s, %s, 0, 0)"
                ).format(t=self._table("memory_edges"))
                cur.execute(
                    sql_insert,
                    (
                        edge_id,
                        from_node_id,
                        to_node_id,
                        edge_type,
                        edge_context,
                        channel_key,
                        weight_hundredths,
                        actor_id,
                        sanitize_text(provenance_tool)[:64],
                        self._to_int(now14, 0),
                        self._to_int(now14, 0),
                    ),
                )
                inserted += 1

        if not self.dry_run:
            self.conn.commit()
        return inserted

    def resolve_symbolic_ref(self, ref: str, channel_context: Optional[Dict[str, Any]] = None) -> Optional[int]:
        """
        Resolve references used in toon edges to memory_node_id.

        Supported:
        - FILE:{path} -> memory_toon exact match
        - CHANNEL:{key}/{slug} -> memory_type='channel' and memory_toon
        - TASK:{id} -> memory_toon exact match
        - PRD-{n} -> memory_toon exact match
        - bare numeric -> memory_node_id
        - fallback -> memory_toon exact match
        """
        if not ref or not self.is_db_available:
            return None
        text = ref.strip()

        if text.isdigit():
            return int(text)

        if text.startswith("FILE:"):
            key = text[5:]
            return self._lookup_node_by_key(key)

        if text.startswith("CHANNEL:"):
            key = text[8:]
            return self._lookup_node_by_key_and_type(key, "channel")

        if text.startswith("TASK:"):
            key = text[5:]
            return self._lookup_node_by_key(key)

        if re.match(r"^PRD-\d+$", text):
            return self._lookup_node_by_key(text)

        # Bare memory key / toon id fallback.
        return self._lookup_node_by_key(text)

    def close(self) -> None:
        try:
            if self.conn is not None:
                self.conn.close()
        except Exception:
            pass

    # ------------------------------------------------------------------
    # Private helpers
    # ------------------------------------------------------------------

    def _table(self, suffix: str) -> str:
        return "{0}{1}".format(self.table_prefix, suffix)

    def _connect(self) -> None:
        try:
            from db_config import LupopediaConfigError

            self.conn = get_connection()
            self.is_db_available = True
            self._filesystem_fallback_active = False
        except LupopediaConfigError:
            raise
        except Exception as exc:
            self.conn = None
            self.is_db_available = False
            if self.fallback_to_filesystem:
                self._filesystem_fallback_active = True
                sys.stderr.write("[WARN] DB unavailable; filesystem fallback activated: {0}\n".format(exc))
            else:
                raise

    def _detect_table_prefix(self) -> str:
        from db_config import get_table_prefix

        return get_table_prefix()

    def _utc14(self) -> str:
        return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")

    def _generate_pk(self, table_suffix: str, pk_col: str, now14: Optional[str] = None) -> int:
        """
        Generate 18-digit id: YYYYMMDDHHIISS + 4-digit sequence bucket.
        Uses current active max for same 14-digit prefix for deterministic monotonic IDs.
        """
        ts = now14 or self._utc14()
        table_name = self._table(table_suffix)
        min_id = int(ts + "0000")
        max_id = int(ts + "9999")

        with self.conn.cursor() as cur:
            sql = "SELECT COALESCE(MAX({pk}), 0) AS max_id FROM `{tbl}` WHERE {pk} BETWEEN %s AND %s".format(
                pk=pk_col, tbl=table_name
            )
            cur.execute(sql, (min_id, max_id))
            row = cur.fetchone()
            cur_max = row["max_id"] if isinstance(row, dict) else row[0]
            cur_max = int(cur_max or 0)
            if cur_max < min_id:
                return min_id
            if cur_max >= max_id:
                # Overflow guard for very high same-second volume.
                return int(str(int(ts) + 1) + "0000")
            return cur_max + 1

    def _write_toon_file_fallback(
        self,
        toon_data: Dict[str, Any],
        filesystem_path: Optional[str] = None,
    ):
        if not filesystem_path:
            toon_id = toon_data.get('id')
            if not toon_id:
                toon_id = "M-{0}".format(self._utc14())
            # Fallback writer dumps JSON payload to disk; use .json extension.
            filesystem_path = "memory/fallback/{0}.json".format(toon_id)

        out_path = Path(filesystem_path)
        if not out_path.is_absolute():
            out_path = Path(self._repo_root) / out_path

        out_path.parent.mkdir(parents=True, exist_ok=True)
        with open(str(out_path), "w", encoding="utf-8") as handle:
            json.dump(toon_data, handle, indent=2, ensure_ascii=False)

        sys.stderr.write("[WARN] DB unavailable. Wrote filesystem fallback: {0}\n".format(str(out_path)))
        return toon_data.get('id', str(filesystem_path))

    def _lookup_node_by_key(self, memory_key: str) -> Optional[int]:
        if not memory_key:
            return None
        with self.conn.cursor() as cur:
            sql = (
                "SELECT memory_node_id FROM `{t}` "
                "WHERE memory_toon=%s AND is_deleted=0 ORDER BY updated_ymdhis DESC LIMIT 1"
            ).format(t=self._table("memory_nodes"))
            cur.execute(sql, (memory_key,))
            row = cur.fetchone()
            if not row:
                return None
            return int(row["memory_node_id"] if isinstance(row, dict) else row[0])

    def _lookup_channel_key_by_node_id(self, memory_node_id: int) -> Optional[str]:
        with self.conn.cursor() as cur:
            sql = (
                "SELECT channel_key FROM `{t}` "
                "WHERE memory_node_id=%s AND is_deleted=0 LIMIT 1"
            ).format(t=self._table("memory_nodes"))
            cur.execute(sql, (memory_node_id,))
            row = cur.fetchone()
            if not row:
                return None
            value = row["channel_key"] if isinstance(row, dict) else row[0]
            text = str(value or "").strip()
            return text or None

    def _soft_delete_edges_for_replace_mode(
        self,
        from_node_id: int,
        provenance_actor_id: int,
        provenance_tool: str,
        now14: int,
    ) -> int:
        """
        Soft-delete all active edges from this source node for the same provenance scope.
        Scope rule:
          - match provenance_tool when non-empty
          - otherwise match provenance_actor_id
        """
        deleted = 0
        with self.conn.cursor() as cur:
            if provenance_tool:
                sql = (
                    "UPDATE `{t}` SET is_deleted=1, deleted_ymdhis=%s, updated_ymdhis=%s "
                    "WHERE from_memory_node_id=%s AND provenance_tool=%s AND is_deleted=0"
                ).format(t=self._table("memory_edges"))
                cur.execute(sql, (now14, now14, from_node_id, provenance_tool))
            else:
                sql = (
                    "UPDATE `{t}` SET is_deleted=1, deleted_ymdhis=%s, updated_ymdhis=%s "
                    "WHERE from_memory_node_id=%s AND provenance_actor_id=%s AND is_deleted=0"
                ).format(t=self._table("memory_edges"))
                cur.execute(sql, (now14, now14, from_node_id, provenance_actor_id))
            deleted = int(getattr(cur, "rowcount", 0) or 0)
        return deleted

    def _soft_delete_exact_duplicate_edges(
        self,
        cur,
        from_node_id: int,
        to_node_id: int,
        edge_type: str,
        provenance_actor_id: int,
        provenance_tool: str,
        now14: int,
    ) -> int:
        """
        Merge mode housekeeping: keep newest active edge for same from/to/type/provenance scope,
        soft-delete any additional active duplicates.
        """
        if provenance_tool:
            sql = (
                "SELECT memory_edge_id FROM `{t}` "
                "WHERE from_memory_node_id=%s AND to_memory_node_id=%s AND edge_type=%s "
                "AND provenance_tool=%s AND is_deleted=0 "
                "ORDER BY created_ymdhis DESC, memory_edge_id DESC"
            ).format(t=self._table("memory_edges"))
            cur.execute(sql, (from_node_id, to_node_id, edge_type, provenance_tool))
        else:
            sql = (
                "SELECT memory_edge_id FROM `{t}` "
                "WHERE from_memory_node_id=%s AND to_memory_node_id=%s AND edge_type=%s "
                "AND provenance_actor_id=%s AND is_deleted=0 "
                "ORDER BY created_ymdhis DESC, memory_edge_id DESC"
            ).format(t=self._table("memory_edges"))
            cur.execute(sql, (from_node_id, to_node_id, edge_type, provenance_actor_id))
        rows = cur.fetchall() or []
        if len(rows) <= 1:
            return 0

        delete_ids: List[int] = []
        for row in rows[1:]:
            delete_ids.append(int(row["memory_edge_id"] if isinstance(row, dict) else row[0]))
        if not delete_ids:
            return 0

        placeholders = ",".join(["%s"] * len(delete_ids))
        sql_del = (
            "UPDATE `{t}` SET is_deleted=1, deleted_ymdhis=%s, updated_ymdhis=%s "
            "WHERE memory_edge_id IN ({ids}) AND is_deleted=0"
        ).format(t=self._table("memory_edges"), ids=placeholders)
        cur.execute(sql_del, tuple([now14, now14] + delete_ids))
        return int(getattr(cur, "rowcount", 0) or 0)

    def backfill_channel_keys(self, dry_run: bool = True) -> Dict[str, int]:
        """
        One-time maintenance helper:
        Fill lupo_memory_nodes.channel_key where null/empty using doctrine-derived memory_toon path.
        """
        summary = {"scanned": 0, "updated": 0, "skipped": 0}
        if not self.is_db_available:
            raise RuntimeError("backfill_channel_keys: DB unavailable")

        with self.conn.cursor() as cur:
            sql = (
                "SELECT memory_node_id, memory_toon, channel_key FROM `{t}` "
                "WHERE is_deleted=0 AND (channel_key IS NULL OR channel_key='')"
            ).format(t=self._table("memory_nodes"))
            cur.execute(sql)
            rows = cur.fetchall() or []
            summary["scanned"] = len(rows)
            for row in rows:
                memory_node_id = int(row["memory_node_id"] if isinstance(row, dict) else row[0])
                memory_toon = str((row["memory_toon"] if isinstance(row, dict) else row[1]) or "")
                meta = resolve_channel_key_for_artifact(None, memory_toon, None)
                derived = str(meta.get("channel_key") or "")
                if not derived:
                    sys.stderr.write(
                        "[WARN] backfill_channel_keys unresolved memory_node_id={0} memory_toon={1!r}\n".format(
                            memory_node_id, memory_toon
                        )
                    )
                    summary["skipped"] += 1
                    continue
                sys.stderr.write(
                    "[INFO] backfill_channel_keys memory_node_id={0} channel_key={1!r} source={2}\n".format(
                        memory_node_id, derived, meta.get("source")
                    )
                )
                if not dry_run:
                    sql_up = "UPDATE `{t}` SET channel_key=%s WHERE memory_node_id=%s".format(
                        t=self._table("memory_nodes")
                    )
                    cur.execute(sql_up, (derived, memory_node_id))
                summary["updated"] += 1
        if not dry_run:
            self.conn.commit()
        return summary

    def _lookup_node_by_key_and_type(self, memory_key: str, memory_type: str) -> Optional[int]:
        if not memory_key:
            return None
        with self.conn.cursor() as cur:
            sql = (
                "SELECT memory_node_id FROM `{t}` "
                "WHERE memory_toon=%s AND memory_type=%s AND is_deleted=0 "
                "ORDER BY updated_ymdhis DESC LIMIT 1"
            ).format(t=self._table("memory_nodes"))
            cur.execute(sql, (memory_key, memory_type))
            row = cur.fetchone()
            if not row:
                return None
            return int(row["memory_node_id"] if isinstance(row, dict) else row[0])

    def _weight_to_hundredths(self, weight: Any) -> int:
        try:
            value = float(weight if weight is not None else 1.0)
        except Exception:
            value = 1.0
        value = max(0.0, min(1.0, value))
        return int(round(value * 100))

    def _to_int(self, value: Any, default: int) -> int:
        try:
            return int(value)
        except Exception:
            return int(default)

    def _encode_json_or_none(self, obj: Any) -> Optional[str]:
        if obj is None:
            return None
        try:
            return json.dumps(obj, ensure_ascii=False, sort_keys=True)
        except Exception:
            return None

    def _fallback_memory_key(self, toon_data: Dict[str, Any], now14: str) -> str:
        node_type = str(toon_data.get("type") or "memory")
        actor = str(toon_data.get("actor_id") or "0")
        return "{0}:{1}:{2}".format(node_type, actor, now14)

    def _sanitize_toon_data(self, toon_data: Dict[str, Any]) -> Dict[str, Any]:
        def _walk(value: Any) -> Any:
            if isinstance(value, str):
                return sanitize_text(value)
            if isinstance(value, list):
                return [_walk(v) for v in value]
            if isinstance(value, dict):
                return {k: _walk(v) for k, v in value.items()}
            return value

        return _walk(toon_data)


def _run_cli() -> int:
    parser = argparse.ArgumentParser(description="DB memory writer maintenance utilities.")
    parser.add_argument(
        "--backfill",
        action="store_true",
        help="Backfill missing channel_key values in memory nodes (commit changes).",
    )
    parser.add_argument(
        "--backfill-dry-run",
        action="store_true",
        help="Backfill preview only (default behavior).",
    )
    args = parser.parse_args()
    if not args.backfill and not args.backfill_dry_run:
        parser.print_help()
        return 0

    writer = DBMemoryWriter(dry_run=False)
    try:
        dry_run = True
        if args.backfill:
            dry_run = False
        summary = writer.backfill_channel_keys(dry_run=dry_run)
        sys.stderr.write(
            "[INFO] backfill_channel_keys summary scanned={0} updated={1} skipped={2} dry_run={3}\n".format(
                summary.get("scanned", 0),
                summary.get("updated", 0),
                summary.get("skipped", 0),
                "yes" if dry_run else "no",
            )
        )
        return 0
    finally:
        writer.close()


if __name__ == "__main__":
    raise SystemExit(_run_cli())


