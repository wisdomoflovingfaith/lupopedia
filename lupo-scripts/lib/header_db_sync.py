#!/usr/bin/env python3
"""
Database-first LUPOPEDIA header sync (WOLFIE 4.0.89).

Persists file header state into canonical tables after lupo_contents upsert:
- lupo_metadata (class_name=lupopedia_header_sync), domain_id=1
- lupo_edges (edge_category=lupopedia_header) for lupopedia.edges.outbound_edges
- lupo_contents.revision_history JSON for lupopedia.history (no lupo_history table in schema)

Regeneration reads the same rows to rebuild YAML. Reference targets without a content row
use lupo_reference_objects (object_type=file_path_ref) for stable right_object_id.
"""

from __future__ import annotations

import hashlib
import json
import re
from typing import Any, Dict, List, Optional, Tuple

HDR_PREFIX = "hdr."
FTR_PREFIX = "ftr."
# Custom lupopedia.* blocks (non-headers/footer/edges/history) — written with ext. prefix (PRD 16 / GEMINI namespacing).
EXT_PREFIX = "ext."
# Legacy rows may still use block.; readers accept both.
BLOCK_PREFIX_LEGACY = "block."
SYNC_CLASS = "lupopedia_header_sync"
EDGE_CATEGORY = "lupopedia_header"
REF_OBJECT_TYPE = "file_path_ref"


def _norm_path(p: str) -> str:
    s = str(p).strip().replace("\\", "/")
    s = re.sub(r"/+", "/", s)
    return s.lstrip("/")


def _ref_slug_for_path(path: str) -> str:
    n = _norm_path(path)
    if len(n) <= 240:
        return n
    h = hashlib.md5(n.encode("utf-8")).hexdigest()
    return "md5:" + h


def _serialize_value(val: Any) -> str:
    if val is None:
        return ""
    if isinstance(val, (str, int, float)) and not isinstance(val, bool):
        return str(val)
    if isinstance(val, bool):
        return "1" if val else "0"
    return json.dumps(val, sort_keys=True, ensure_ascii=False)


def _deserialize_value(raw: str) -> Any:
    if raw is None:
        return None
    t = str(raw).strip()
    if not t:
        return ""
    if t.startswith(("{", "[")):
        try:
            return json.loads(t)
        except Exception:
            return raw
    if re.fullmatch(r"-?\d+", t):
        try:
            return int(t)
        except Exception:
            return raw
    return raw


def _sql_ident(name: str) -> str:
    if not re.fullmatch(r"[A-Za-z0-9_]+", name):
        raise ValueError("Unsafe SQL identifier: %r" % (name,))
    return name


def _next_id(cursor, table: str, pk: str) -> int:
    t = _sql_ident(table)
    k = _sql_ident(pk)
    cursor.execute("SELECT COALESCE(MAX(`%s`), 0) + 1 AS n FROM `%s`" % (k, t))
    row = cursor.fetchone()
    if row is None:
        return 1
    return int(row["n"] if isinstance(row, dict) else row[0])


def _delete_sync_metadata(cursor, table_prefix: str, content_id: int) -> None:
    m = _sql_ident(table_prefix + "metadata")
    cursor.execute(
        "DELETE FROM `" + m + "` WHERE entity_type=%s AND entity_id=%s AND domain_id=%s AND class_name=%s",
        ("content", int(content_id), 1, SYNC_CLASS),
    )


def _insert_metadata_row(
    cursor,
    table_prefix: str,
    content_id: int,
    property_key: str,
    property_value: str,
    now: int,
) -> None:
    m = _sql_ident(table_prefix + "metadata")
    mid = _next_id(cursor, m, "metadata_id")
    cursor.execute(
        "INSERT INTO `" + m + "` (metadata_id, entity_type, entity_id, domain_id, meta_type, "
        "property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, channel_id, "
        "parent_metadata_id, class_name, schema_ref) "
        "VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        (
            mid,
            "content",
            int(content_id),
            1,
            "lupopedia_header",
            property_key,
            property_value,
            now,
            now,
            0,
            None,
            None,
            SYNC_CLASS,
            None,
        ),
    )


def _soft_delete_header_edges(cursor, table_prefix: str, content_id: int, now: int) -> None:
    e = _sql_ident(table_prefix + "edges")
    cursor.execute(
        "UPDATE `" + e + "` SET is_deleted=1, deleted_ymdhis=%s, updated_ymdhis=%s "
        "WHERE left_object_type=%s AND left_object_id=%s AND edge_category=%s AND is_deleted=0",
        (now, now, "content", int(content_id), EDGE_CATEGORY),
    )


def _get_content_id_by_path(cursor, table_prefix: str, path: str) -> Optional[int]:
    c = _sql_ident(table_prefix + "contents")
    p = _norm_path(path)
    cursor.execute(
        "SELECT content_id FROM `" + c + "` WHERE file_path_from_root=%s AND is_deleted=0 LIMIT 1",
        (p,),
    )
    row = cursor.fetchone()
    if not row:
        return None
    return int(row["content_id"] if isinstance(row, dict) else row[0])


def _get_or_create_reference_object_id(
    cursor, table_prefix: str, file_path: str, now: int
) -> int:
    r = _sql_ident(table_prefix + "reference_objects")
    slug = _ref_slug_for_path(file_path)
    cursor.execute(
        "SELECT reference_object_id FROM `" + r + "` WHERE object_type=%s AND object_slug=%s "
        "AND is_deleted=0 LIMIT 1",
        (REF_OBJECT_TYPE, slug),
    )
    row = cursor.fetchone()
    if row:
        return int(row["reference_object_id"] if isinstance(row, dict) else row[0])
    rid = _next_id(cursor, r, "reference_object_id")
    full = _norm_path(file_path)
    meta = json.dumps({"file_path_from_root": full}, sort_keys=True)
    cursor.execute(
        "INSERT INTO `" + r + "` (reference_object_id, object_type, object_slug, object_label, "
        "meta_json, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis) "
        "VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        (rid, REF_OBJECT_TYPE, slug, full[:255], meta, 0, None, now, now),
    )
    return rid


def _resolve_edge_right(
    cursor, table_prefix: str, to_val: Any, now: int
) -> Tuple[str, int]:
    if to_val is None:
        return "reference_object", _get_or_create_reference_object_id(
            cursor, table_prefix, "", now
        )
    path = str(to_val).strip()
    cid = _get_content_id_by_path(cursor, table_prefix, path)
    if cid is not None:
        return "content", cid
    return "reference_object", _get_or_create_reference_object_id(cursor, table_prefix, path, now)


def _parse_outbound_edges(edges_block: Any) -> List[Dict[str, Any]]:
    if not isinstance(edges_block, dict):
        return []
    raw = edges_block.get("outbound_edges")
    if raw is None:
        return []
    if not isinstance(raw, list):
        return []
    out: List[Dict[str, Any]] = []
    for item in raw:
        if isinstance(item, dict):
            out.append(item)
    return out


def extract_lupopedia_headers_block(yaml_data: Dict[str, Any]) -> Dict[str, Any]:
    """Same rules as import_content._extract_lupopedia_headers_block (no circular import)."""
    if "lupopedia.headers" in yaml_data:
        hdrs = yaml_data.get("lupopedia.headers")
        if not isinstance(hdrs, dict):
            raise ValueError("lupopedia.headers must be a mapping")
        return hdrs
    if "lupopedia" in yaml_data and isinstance(yaml_data["lupopedia"], dict):
        inner = yaml_data["lupopedia"]
        if "headers" in inner:
            hdrs = inner.get("headers")
            if not isinstance(hdrs, dict):
                raise ValueError("lupopedia.headers (nested) must be a mapping")
            return hdrs
    raise ValueError("lupopedia.headers block missing")


def sync_header_artifact_to_db(
    cursor,
    table_prefix: str,
    yaml_data: Dict[str, Any],
    content_id: int,
    now_ymdhis: int,
    append_history: bool = False,
) -> None:
    """
    After lupo_contents row exists, persist headers/footer/optional blocks, edges, history.
    cursor: pymysql connection cursor (DictCursor recommended).

    Caller should run inside a single DB transaction (commit/rollback): import_content.py
    already wraps upsert + this sync in one transaction so partial failure rolls back.

    append_history: if True and file has lupopedia.history (list), append to existing
    revision_history list in DB instead of replacing (default: file replaces when key present).
    """
    headers = extract_lupopedia_headers_block(yaml_data)

    _delete_sync_metadata(cursor, table_prefix, content_id)

    for key, val in headers.items():
        pk = HDR_PREFIX + str(key)
        _insert_metadata_row(cursor, table_prefix, content_id, pk, _serialize_value(val), now_ymdhis)

    footer = yaml_data.get("lupopedia.footer")
    if isinstance(footer, dict):
        for key, val in footer.items():
            pk = FTR_PREFIX + str(key)
            _insert_metadata_row(cursor, table_prefix, content_id, pk, _serialize_value(val), now_ymdhis)

    for block_key in sorted(yaml_data.keys()):
        if block_key in ("lupopedia.headers", "lupopedia.footer", "lupopedia.edges", "lupopedia.history"):
            continue
        if not str(block_key).startswith("lupopedia."):
            continue
        blk = yaml_data.get(block_key)
        if blk is None:
            continue
        pk = EXT_PREFIX + str(block_key)
        _insert_metadata_row(cursor, table_prefix, content_id, pk, _serialize_value(blk), now_ymdhis)

    edges_block = yaml_data.get("lupopedia.edges")
    outbound = _parse_outbound_edges(edges_block)
    _soft_delete_header_edges(cursor, table_prefix, content_id, now_ymdhis)

    e = _sql_ident(table_prefix + "edges")
    actor_id = headers.get("actor_id")
    try:
        actor_id_int = int(actor_id) if actor_id is not None else None
    except (TypeError, ValueError):
        actor_id_int = None

    ch = headers.get("channel_id")
    try:
        ch_int = int(ch) if ch is not None else None
    except (TypeError, ValueError):
        ch_int = None

    for ed in outbound:
        to_v = ed.get("to")
        if to_v is None or (isinstance(to_v, str) and not str(to_v).strip()):
            continue
        etype = str(ed.get("type") or "references")
        weight = ed.get("weight")
        try:
            w_float = float(weight) if weight is not None else 0.5
        except (TypeError, ValueError):
            w_float = 0.5
        reason = ed.get("reason")
        reason_s = str(reason) if reason is not None else None
        if reason_s and len(reason_s) > 255:
            reason_s = reason_s[:252] + "..."

        rt, rid = _resolve_edge_right(cursor, table_prefix, to_v, now_ymdhis)
        eid = _next_id(cursor, e, "edge_id")
        w_score = int(max(0, min(100, round(w_float * 100))))
        cursor.execute(
            "INSERT INTO `" + e + "` (edge_id, left_object_type, left_object_id, right_object_type, "
            "right_object_id, edge_type, edge_category, edge_description, channel_id, channel_key, "
            "domain_id, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, "
            "updated_ymdhis, semantic_weight, relationship_type, bidirectional, context_scope, properties, "
            "flare_weight, flare_reason, flare_db_source, flare_auto_generated, flare_verified, "
            "flare_discovered_via) VALUES ("
            "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            (
                eid,
                "content",
                int(content_id),
                rt,
                int(rid),
                etype,
                EDGE_CATEGORY,
                None,
                ch_int,
                None,
                1,
                w_score,
                0,
                actor_id_int,
                0,
                0,
                now_ymdhis,
                now_ymdhis,
                w_float,
                "semantic",
                0,
                "lupopedia_header_import",
                None,
                min(1.0, max(0.0, w_float)),
                reason_s,
                "header_import",
                1,
                0,
                "filesystem_yaml",
            ),
        )

    c = _sql_ident(table_prefix + "contents")
    if "lupopedia.history" in yaml_data:
        hist = yaml_data.get("lupopedia.history")
        rev_json: Optional[str] = None
        if hist is not None:
            if append_history and isinstance(hist, list):
                cursor.execute(
                    "SELECT revision_history FROM `" + c + "` WHERE content_id=%s AND is_deleted=0 LIMIT 1",
                    (int(content_id),),
                )
                prow = cursor.fetchone()
                prev_raw = None
                if prow:
                    prev_raw = prow.get("revision_history") if isinstance(prow, dict) else prow[0]
                prev_list: List[Any] = []
                if prev_raw:
                    try:
                        if isinstance(prev_raw, (bytes, str)):
                            parsed = json.loads(prev_raw) if isinstance(prev_raw, str) else json.loads(prev_raw.decode("utf-8"))
                        else:
                            parsed = prev_raw
                        if isinstance(parsed, list):
                            prev_list = parsed
                        elif isinstance(parsed, dict):
                            prev_list = [parsed]
                    except Exception:
                        prev_list = []
                merged = prev_list + hist
                rev_json = json.dumps(merged, sort_keys=True, ensure_ascii=False)
            else:
                rev_json = json.dumps(hist, sort_keys=True, ensure_ascii=False)
        cursor.execute(
            "UPDATE `" + c + "` SET revision_history=%s, updated_ymdhis=%s WHERE content_id=%s",
            (rev_json, now_ymdhis, int(content_id)),
        )
    else:
        cursor.execute(
            "UPDATE `" + c + "` SET updated_ymdhis=%s WHERE content_id=%s",
            (now_ymdhis, int(content_id)),
        )


def fetch_metadata_rows(cursor, table_prefix: str, content_id: int) -> List[Dict[str, Any]]:
    m = _sql_ident(table_prefix + "metadata")
    cursor.execute(
        "SELECT metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, "
        "created_ymdhis, updated_ymdhis, channel_id, parent_metadata_id, class_name "
        "FROM `" + m + "` WHERE entity_type=%s AND entity_id=%s AND class_name=%s AND is_deleted=0 "
        "ORDER BY property_key ASC",
        ("content", int(content_id), SYNC_CLASS),
    )
    return list(cursor.fetchall())


def fetch_header_edges(cursor, table_prefix: str, content_id: int) -> List[Dict[str, Any]]:
    e = _sql_ident(table_prefix + "edges")
    cursor.execute(
        "SELECT edge_id, left_object_type, left_object_id, right_object_type, right_object_id, "
        "edge_type, semantic_weight, flare_weight, flare_reason, channel_id, actor_id, created_ymdhis "
        "FROM `" + e + "` WHERE left_object_type=%s AND left_object_id=%s AND edge_category=%s "
        "AND is_deleted=0 ORDER BY edge_id ASC",
        ("content", int(content_id), EDGE_CATEGORY),
    )
    return list(cursor.fetchall())


def _path_for_right(cursor, table_prefix: str, right_type: str, right_id: int) -> str:
    if right_type == "content":
        c = _sql_ident(table_prefix + "contents")
        cursor.execute(
            "SELECT file_path_from_root FROM `" + c + "` WHERE content_id=%s LIMIT 1", (int(right_id),)
        )
        row = cursor.fetchone()
        if not row:
            return str(int(right_id))
        v = row["file_path_from_root"] if isinstance(row, dict) else row[0]
        return str(v) if v else str(int(right_id))
    if right_type == "reference_object":
        r = _sql_ident(table_prefix + "reference_objects")
        cursor.execute(
            "SELECT object_slug, meta_json, object_label FROM `" + r + "` WHERE reference_object_id=%s LIMIT 1",
            (int(right_id),),
        )
        row = cursor.fetchone()
        if not row:
            return str(int(right_id))
        if isinstance(row, dict):
            mj = row.get("meta_json")
            if mj:
                try:
                    if isinstance(mj, (bytes, str)):
                        parsed = json.loads(mj) if isinstance(mj, str) else json.loads(mj.decode("utf-8"))
                    else:
                        parsed = mj
                    if isinstance(parsed, dict) and parsed.get("file_path_from_root"):
                        return str(parsed["file_path_from_root"])
                except Exception:
                    pass
            lab = row.get("object_label")
            if lab:
                return str(lab)
            return str(row.get("object_slug") or right_id)
        return str(right_id)
    return str(int(right_id))


def outbound_edges_from_db_rows(
    cursor, table_prefix: str, rows: List[Dict[str, Any]]
) -> List[Dict[str, Any]]:
    out: List[Dict[str, Any]] = []
    for row in rows:
        rt = row["right_object_type"] if isinstance(row, dict) else row[3]
        ri = row["right_object_id"] if isinstance(row, dict) else row[4]
        et = row["edge_type"] if isinstance(row, dict) else row[5]
        sw = row.get("semantic_weight") if isinstance(row, dict) else None
        fw = row.get("flare_weight") if isinstance(row, dict) else None
        fr = row.get("flare_reason") if isinstance(row, dict) else None
        try:
            w = float(fw) if fw is not None else (float(sw) if sw is not None else 0.5)
        except (TypeError, ValueError):
            w = 0.5
        to_path = _path_for_right(cursor, table_prefix, str(rt), int(ri))
        item: Dict[str, Any] = {"to": to_path, "type": str(et), "weight": w}
        if fr:
            item["reason"] = str(fr)
        out.append(item)
    return out


def build_yaml_data_from_db(
    cursor,
    table_prefix: str,
    content_row: Dict[str, Any],
) -> Dict[str, Any]:
    """
    Build a front-matter mapping (lupopedia.* blocks) from DB state.
    content_row: dict from lupo_contents SELECT *.
    """
    cid = int(content_row["content_id"])
    meta_rows = fetch_metadata_rows(cursor, table_prefix, cid)
    edge_rows = fetch_header_edges(cursor, table_prefix, cid)

    headers: Dict[str, Any] = {}
    footer: Dict[str, Any] = {}
    extra_blocks: Dict[str, Any] = {}

    for row in meta_rows:
        pk = row["property_key"] if isinstance(row, dict) else row[5]
        pv = row["property_value"] if isinstance(row, dict) else row[6]
        if isinstance(pk, str) and pk.startswith(HDR_PREFIX):
            headers[pk[len(HDR_PREFIX) :]] = _deserialize_value(str(pv))
        elif isinstance(pk, str) and pk.startswith(FTR_PREFIX):
            footer[pk[len(FTR_PREFIX) :]] = _deserialize_value(str(pv))
        elif isinstance(pk, str) and pk.startswith(EXT_PREFIX):
            extra_blocks[pk[len(EXT_PREFIX) :]] = _deserialize_value(str(pv))
        elif isinstance(pk, str) and pk.startswith(BLOCK_PREFIX_LEGACY):
            extra_blocks[pk[len(BLOCK_PREFIX_LEGACY) :]] = _deserialize_value(str(pv))

    if not headers.get("file_path_from_root") and content_row.get("file_path_from_root"):
        headers["file_path_from_root"] = content_row["file_path_from_root"]
    if content_row.get("title") and "title" not in headers:
        headers["title"] = content_row["title"]
    for col in ("channel_id", "actor_id"):
        if content_row.get(col) is not None and col not in headers:
            try:
                headers[col] = int(content_row[col])
            except (TypeError, ValueError):
                headers[col] = content_row[col]
    if content_row.get("tags") is not None and "tags" not in headers:
        headers["tags"] = content_row["tags"]
    if "web_path" not in headers and headers.get("file_path_from_root"):
        fp = str(headers["file_path_from_root"])
        headers["web_path"] = "http://www.lupopedia.com/lupopedia/" + fp
    if "when_updated" not in headers or not headers.get("when_updated"):
        wu = content_row.get("updated_ymdhis") or content_row.get("created_ymdhis")
        if wu is not None:
            headers["when_updated"] = str(int(wu))
    if "last_modified_utc" not in headers or not headers.get("last_modified_utc"):
        lm = content_row.get("file_last_modified_utc") or content_row.get("updated_ymdhis")
        if lm is not None:
            headers["last_modified_utc"] = str(int(lm))
    headers["content_id"] = cid

    outbound = outbound_edges_from_db_rows(cursor, table_prefix, edge_rows)
    edges_block: Optional[Dict[str, Any]] = None
    if outbound:
        edges_block = {"outbound_edges": outbound}

    hist_block: Any = None
    rh = content_row.get("revision_history")
    if rh:
        try:
            if isinstance(rh, (bytes, str)):
                hist_block = json.loads(rh) if isinstance(rh, str) else json.loads(rh.decode("utf-8"))
            elif isinstance(rh, dict) or isinstance(rh, list):
                hist_block = rh
        except Exception:
            hist_block = None

    ordered: Dict[str, Any] = {}
    ordered["lupopedia.headers"] = headers
    for k in sorted(extra_blocks.keys()):
        ordered[k] = extra_blocks[k]
    if edges_block:
        ordered["lupopedia.edges"] = edges_block
    if hist_block is not None:
        ordered["lupopedia.history"] = hist_block
    if footer:
        ordered["lupopedia.footer"] = footer

    return ordered
