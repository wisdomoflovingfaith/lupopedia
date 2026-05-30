#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "lupo-scripts/thoth_worker.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/thoth_worker.py"
#   status: "complete"
#   when_updated: "20260415104838"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/thoth-worker.toon"
#   atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/thoth-worker"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "16"
#   content_slug: ""
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "THOTH worker — dialog truth validator"
#   summary: "Polls lupo_dialog_messages, resolves discussed files, validates claims against atoms/memory/header/file truth sources, and posts [ALERT] messages on discrepancy."
# ---------------------------------------------------------------------
"""
THOTH worker — polls lupo_dialog_messages and validates claims against truth sources.

Truth source load order per message:
1) atoms_toon from file header (if present)
2) global constants atom
3) memory_toon from file header
4) full file (last resort)
"""

from __future__ import annotations

import argparse
import json
import re
import sys
import time
from pathlib import Path
from typing import Any, Dict, List, Optional, Tuple

import pymysql

from db_config import get_table_prefix
from generate_memory_from_header import _extract_headers_dict
from lib.db_connection import get_connection_params


THOTH_ACTOR_ID = 26
TAG_ACTORS = "@ANUBIS @WOLFIE"
GLOBAL_ATOM_PATH = "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
FILE_PATH_RE = re.compile(
    r"([A-Za-z0-9_\-./]+?\.(?:md|py|php|json|toon))",
    re.IGNORECASE,
)
VERSION_RE = re.compile(
    r"\b(?:version|header_format_version)\s*[:=]?\s*\"?(\d+\.\d+\.\d+)\"?",
    re.IGNORECASE,
)
CLAIM_KEYS = (
    "header_format_version",
    "content_id",
    "content_parent_id",
    "content_slug",
    "default_collection_id",
    "memory_toon",
    "atoms_toon",
    "transcript_jsonl",
    "channel_key",
    "trust_tier",
)


def _now_ymdhis() -> int:
    import time as _time

    return int(_time.strftime("%Y%m%d%H%M%S", _time.gmtime()))


def _norm_path(path: str) -> str:
    value = str(path or "").strip().replace("\\", "/")
    while "//" in value:
        value = value.replace("//", "/")
    return value.lstrip("/")


def _load_json_file(abs_path: Path) -> Optional[Dict[str, Any]]:
    if not abs_path.exists() or not abs_path.is_file():
        return None
    try:
        with abs_path.open("r", encoding="utf-8") as handle:
            data = json.load(handle)
        if isinstance(data, dict):
            return data
    except Exception:
        return None
    return None


def _extract_file_path_from_metadata(meta: Dict[str, Any]) -> Optional[str]:
    candidates = []
    for key in (
        "file_path_from_root",
        "source_file_path_from_root",
        "source_file",
        "file",
        "path",
    ):
        value = meta.get(key)
        if isinstance(value, str) and value.strip():
            candidates.append(value.strip())
    header = meta.get("header")
    if isinstance(header, dict):
        value = header.get("file_path_from_root")
        if isinstance(value, str) and value.strip():
            candidates.append(value.strip())
    if not candidates:
        return None
    return _norm_path(candidates[0])


def _extract_file_path_from_message_text(message_text: str) -> Optional[str]:
    for match in FILE_PATH_RE.findall(message_text or ""):
        path = _norm_path(match)
        if path.startswith(("lupo-", "README.md", "AGENTS.md", "CLAUDE.md", "TODO.md")):
            return path
    return None


def _extract_claims(message_text: str) -> List[Tuple[str, str]]:
    claims: List[Tuple[str, str]] = []
    text = message_text or ""
    for key in CLAIM_KEYS:
        pattern = re.compile(
            r"\b" + re.escape(key) + r"\b\s*[:=]\s*\"?([^\n\",;]+)\"?",
            re.IGNORECASE,
        )
        for match in pattern.findall(text):
            claims.append((key.lower(), str(match).strip()))
    version_match = VERSION_RE.search(text)
    if version_match:
        claims.append(("version", version_match.group(1).strip()))
    return claims


def _build_truth_map(
    header: Dict[str, Any],
    atoms_doc: Optional[Dict[str, Any]],
    memory_doc: Optional[Dict[str, Any]],
) -> Dict[str, str]:
    truth: Dict[str, str] = {}
    for key in CLAIM_KEYS:
        value = header.get(key)
        if value is not None and str(value).strip() != "":
            truth[key.lower()] = str(value).strip()

    if atoms_doc:
        constants = atoms_doc.get("constants")
        if isinstance(constants, dict):
            versioning = constants.get("versioning")
            if isinstance(versioning, dict):
                current = versioning.get("current_lupopedia_version")
                if current is not None:
                    truth["version"] = str(current).strip()

    if memory_doc:
        bridge = memory_doc.get("header_bridge")
        if isinstance(bridge, dict):
            for key in ("content_id", "content_parent_id", "content_slug", "default_collection_id", "transcript_jsonl"):
                if key in bridge and bridge.get(key) is not None:
                    truth[key.lower()] = str(bridge.get(key)).strip()
    return truth


def validate_message(
    message: Dict[str, Any],
    file_path: str,
    repo_root: Path,
) -> Tuple[List[Dict[str, str]], Dict[str, str]]:
    abs_file = (repo_root / file_path).resolve()
    if not abs_file.exists() or not abs_file.is_file():
        return (
            [{"claim": "file_path", "expected": "existing file", "actual": file_path}],
            {"file": file_path},
        )

    try:
        file_content = abs_file.read_text(encoding="utf-8-sig")
    except Exception:
        return (
            [{"claim": "file_read", "expected": "readable file", "actual": file_path}],
            {"file": file_path},
        )

    header = _extract_headers_dict(str(abs_file), file_content, silent=True) or {}
    header_atoms = str(header.get("atoms_toon") or "").strip()
    header_memory = str(header.get("memory_toon") or "").strip()

    atoms_doc = _load_json_file(repo_root / header_atoms) if header_atoms else None
    if atoms_doc is None:
        atoms_doc = _load_json_file(repo_root / GLOBAL_ATOM_PATH)

    memory_doc = _load_json_file(repo_root / header_memory) if header_memory else None
    truth = _build_truth_map(header, atoms_doc, memory_doc)
    truth["file"] = file_path

    claims = _extract_claims(str(message.get("message_text") or ""))
    discrepancies: List[Dict[str, str]] = []
    for claim_key, claim_value in claims:
        expected = truth.get(claim_key)
        if expected is None:
            continue
        if claim_value != expected:
            discrepancies.append(
                {
                    "claim": claim_key,
                    "expected": expected,
                    "actual": claim_value,
                }
            )
    return discrepancies, truth


def _generate_dialog_message_id(cursor, dialog_table: str, now14: int) -> int:
    dialog_message_id = int(now14)
    max_attempts = 1000
    for _ in range(max_attempts):
        cursor.execute(
            "SELECT COUNT(*) AS c FROM `{0}` WHERE dialog_message_id=%s".format(dialog_table),
            (dialog_message_id,),
        )
        row = cursor.fetchone() or {}
        count = int((row.get("c") if isinstance(row, dict) else row[0]) or 0)
        if count == 0:
            return dialog_message_id
        dialog_message_id += 1
    raise RuntimeError("Could not generate unique dialog_message_id")


def post_alert(
    cursor,
    dialog_table: str,
    message: Dict[str, Any],
    alert_message: str,
    metadata: Dict[str, Any],
) -> int:
    now14 = _now_ymdhis()
    dialog_message_id = _generate_dialog_message_id(cursor, dialog_table, now14)
    cursor.execute(
        "INSERT INTO `{0}` ("
        "dialog_message_id, dialog_thread_id, channel_id, channel_key, "
        "from_actor_id, source_faucet_slug, source_faucet_instance_id, to_actor_id, "
        "read_by_actor_id, read_by_actor_utc, message_text, message_type, metadata_json, "
        "mood_vector, mood_framework, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis"
        ") VALUES ("
        "%s, %s, %s, %s, %s, %s, %s, %s, "
        "%s, %s, %s, %s, %s, "
        "%s, %s, %s, %s, %s, %s"
        ")".format(dialog_table),
        (
            dialog_message_id,
            message.get("dialog_thread_id"),
            message.get("channel_id"),
            str(message.get("channel_key") or ""),
            THOTH_ACTOR_ID,
            "thoth-worker",
            "python",
            message.get("from_actor_id"),
            0,
            0,
            alert_message,
            "alert",
            json.dumps(metadata, ensure_ascii=True, sort_keys=True),
            None,
            "western_analytical",
            now14,
            now14,
            0,
            None,
        ),
    )
    return dialog_message_id


def _build_alert_text(
    source_message_id: int,
    file_path: str,
    discrepancies: List[Dict[str, str]],
) -> str:
    lines = [
        "[ALERT] THOTH truth discrepancy detected.",
        "Source message: {0}".format(source_message_id),
        "File: {0}".format(file_path),
        "",
    ]
    for item in discrepancies:
        lines.append(
            "- claim `{0}` is `{1}` but truth is `{2}`".format(
                item["claim"], item["actual"], item["expected"]
            )
        )
    lines.extend(
        [
            "",
            "Why: message claim diverges from header/atoms/memory truth sources.",
            "How to fix: update the message or file metadata so claims match canonical truth.",
            TAG_ACTORS,
        ]
    )
    return "\n".join(lines)


def poll_messages(
    conn,
    dialog_table: str,
    last_id: int,
    repo_root: Path,
    limit: int = 200,
) -> int:
    with conn.cursor() as cursor:
        cursor.execute(
            "SELECT dialog_message_id, dialog_thread_id, channel_id, channel_key, "
            "from_actor_id, to_actor_id, message_text, message_type, metadata_json "
            "FROM `{0}` "
            "WHERE is_deleted=0 AND dialog_message_id > %s "
            "ORDER BY dialog_message_id ASC "
            "LIMIT %s".format(dialog_table),
            (int(last_id), int(limit)),
        )
        rows = cursor.fetchall() or []

    max_seen = int(last_id)
    for row in rows:
        msg_id = int(row.get("dialog_message_id") or 0)
        if msg_id > max_seen:
            max_seen = msg_id
        if int(row.get("from_actor_id") or 0) == THOTH_ACTOR_ID:
            continue
        message_type = str(row.get("message_type") or "").strip().lower()
        if message_type in ("alert", "system"):
            continue

        meta: Dict[str, Any] = {}
        raw_meta = row.get("metadata_json")
        if isinstance(raw_meta, str) and raw_meta.strip():
            try:
                parsed = json.loads(raw_meta)
                if isinstance(parsed, dict):
                    meta = parsed
            except Exception:
                meta = {}

        file_path = _extract_file_path_from_metadata(meta)
        if not file_path:
            file_path = _extract_file_path_from_message_text(str(row.get("message_text") or ""))
        if not file_path:
            continue

        discrepancies, truth = validate_message(row, file_path, repo_root)
        if not discrepancies:
            continue

        alert_text = _build_alert_text(msg_id, file_path, discrepancies)
        alert_meta = {
            "source_message_id": msg_id,
            "file_path_from_root": file_path,
            "discrepancies": discrepancies,
            "truth_snapshot": truth,
            "worker": "thoth_worker.py",
        }
        with conn.cursor() as cursor:
            post_alert(cursor, dialog_table, row, alert_text, alert_meta)
        conn.commit()
        print("[ALERT] posted for message {0} ({1})".format(msg_id, file_path))

    return max_seen


def _initial_last_id(conn, dialog_table: str) -> int:
    with conn.cursor() as cursor:
        cursor.execute(
            "SELECT COALESCE(MAX(dialog_message_id), 0) AS max_id FROM `{0}`".format(dialog_table)
        )
        row = cursor.fetchone() or {}
    value = row.get("max_id") if isinstance(row, dict) else row[0]
    return int(value or 0)


def main() -> int:
    parser = argparse.ArgumentParser(
        description="THOTH worker: poll lupo_dialog_messages and post [ALERT] on truth discrepancies."
    )
    parser.add_argument("--interval", type=float, default=2.0, help="Poll interval seconds (default: 2.0)")
    parser.add_argument("--limit", type=int, default=200, help="Max messages per poll (default: 200)")
    parser.add_argument("--once", action="store_true", help="Run one poll cycle and exit")
    parser.add_argument(
        "--from-id",
        type=int,
        default=-1,
        help="Start processing strictly after this dialog_message_id (default: current max, i.e. tail mode)",
    )
    args = parser.parse_args()

    repo_root = Path(__file__).resolve().parent.parent
    params = dict(get_connection_params())
    params["charset"] = params.get("charset") or "utf8mb4"
    params["cursorclass"] = pymysql.cursors.DictCursor
    table_prefix = get_table_prefix()
    dialog_table = "{0}dialog_messages".format(table_prefix)

    conn = pymysql.connect(**params)
    try:
        if int(args.from_id) >= 0:
            last_id = int(args.from_id)
        else:
            last_id = _initial_last_id(conn, dialog_table)

        if args.once:
            poll_messages(conn, dialog_table, last_id, repo_root, limit=int(args.limit))
            return 0

        while True:
            last_id = poll_messages(
                conn,
                dialog_table,
                last_id,
                repo_root,
                limit=int(args.limit),
            )
            time.sleep(max(0.2, float(args.interval)))
    finally:
        conn.close()


if __name__ == "__main__":
    sys.exit(main())
