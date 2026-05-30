#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324182200"
#   file_path_from_root: "lupo-scripts/import_filesystem_channels_to_db.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324182200"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
import_filesystem_channels_to_db.py
------------------------------------
Deterministic post-install importer for Lupopedia.

After a clean install (DROP ALL TABLES → run install_new_lupopedia.sql), the filesystem
artifacts under lupo-channels/ still exist but the database contains no channels, threads,
tasks, artifacts, or metadata.  This script scans the filesystem and imports that state
into the database in dependency order.

Import phases (in order):
  1. Discover channels
  2. Discover threads
  3. Discover artifacts/files
  4. Parse LUPOPEDIA HEADERS from each file
  5. Validate required fields per file
  6. Import channels → lupo_channels
  7. Import threads → lupo_dialog_threads
  8. Import tasks → lupo_tasks
  9. Import artifact/content records → lupo_artifacts
 10. Import metadata (header fields) → lupo_metadata + lupo_thread_metadata
 11. Import explicit edges → lupo_edges
 12. Print deterministic summary

Doctrine rules honored:
  - No foreign keys / triggers / procedures at DB level
  - All IDs are application-generated (deterministic hash or directory-derived integers)
  - All timestamps are BIGINT in YYYYMMDDHHIISS format (UTC)
  - Soft deletes: is_deleted=0 on insert
  - No invented data — missing required fields produce skip/error records
  - Re-runnable: INSERT IGNORE semantics (idempotent on repeated execution)

Usage:
  python lupo-scripts/import_filesystem_channels_to_db.py --repo-root .
  python lupo-scripts/import_filesystem_channels_to_db.py --repo-root . --channel 42
  python lupo-scripts/import_filesystem_channels_to_db.py --repo-root . --thread 1047
  python lupo-scripts/import_filesystem_channels_to_db.py --repo-root . --dry-run
  python lupo-scripts/import_filesystem_channels_to_db.py --repo-root . --strict
  python lupo-scripts/import_filesystem_channels_to_db.py --repo-root . --verbose

Requires:
  pip install pymysql PyYAML
  (PyMySQL for MySQL/MariaDB; PyYAML for YAML header parsing)
"""
from __future__ import annotations

import argparse
import hashlib
import os
import re
import sys
import warnings
from dataclasses import dataclass, field
from pathlib import Path
from typing import Any

_SCRIPTS_DIR = os.path.dirname(os.path.abspath(__file__))
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

from lib.header_validation import validate_header

try:
    import yaml  # type: ignore
    _HAVE_YAML = True
except ImportError:
    _HAVE_YAML = False

try:
    import pymysql  # type: ignore
    import pymysql.cursors
    _HAVE_PYMYSQL = True
except ImportError:
    _HAVE_PYMYSQL = False

# ---------------------------------------------------------------------------
# Constants
# ---------------------------------------------------------------------------

TABLE_PREFIX = "lupo_"   # may be overridden by --table-prefix
FEDERATION_NODE_ID_DEFAULT = 1
SYSTEM_ACTOR_ID_DEFAULT = 1   # WOLFIE — used when actor cannot be resolved

# Artifact types we recognise at the filesystem level.
ARTIFACT_TYPE_MAP = {
    "implementation_report": "implementation_report",
    "thread_index": "thread_index",
    "directive": "directive",
    "broadcast": "broadcast",
    "research": "research",
    "documentation": "documentation",
    "diagnostic": "diagnostic",
    "system_prompt": "system_prompt",
    "registry": "registry",
    "index": "index",
    "validation": "validation",
}

# ---------------------------------------------------------------------------
# Counters / reporting
# ---------------------------------------------------------------------------

@dataclass
class Summary:
    channels_found: int = 0
    channels_imported: int = 0
    threads_found: int = 0
    threads_imported: int = 0
    messages_found: int = 0
    messages_imported: int = 0
    artifacts_found: int = 0
    artifacts_imported: int = 0
    tasks_found: int = 0
    tasks_imported: int = 0
    metadata_rows_imported: int = 0
    edges_imported: int = 0
    skipped_count: int = 0
    error_count: int = 0
    errors: list[str] = field(default_factory=list)

    def record_error(self, path: str, reason: str) -> None:
        self.errors.append(f"{path}: {reason}")
        self.error_count += 1

    def print(self, dry_run: bool = False) -> None:
        prefix = "[DRY-RUN] " if dry_run else ""
        print("")
        print(f"{'=' * 60}")
        print(f"{prefix}IMPORT SUMMARY")
        print(f"{'=' * 60}")
        print(f"  channels_found:           {self.channels_found}")
        print(f"  channels_imported:        {self.channels_imported}")
        print(f"  threads_found:            {self.threads_found}")
        print(f"  threads_imported:         {self.threads_imported}")
        print(f"  messages_found:           {self.messages_found}")
        print(f"  messages_imported:        {self.messages_imported}")
        print(f"  artifacts_found:          {self.artifacts_found}")
        print(f"  artifacts_imported:       {self.artifacts_imported}")
        print(f"  tasks_found:              {self.tasks_found}")
        print(f"  tasks_imported:           {self.tasks_imported}")
        print(f"  metadata_rows_imported:   {self.metadata_rows_imported}")
        print(f"  edges_imported:           {self.edges_imported}")
        print(f"  skipped_count:            {self.skipped_count}")
        print(f"  error_count:              {self.error_count}")
        if self.errors:
            print("")
            print("  ERRORS:")
            for e in self.errors[:50]:
                print(f"    • {e}")
            if len(self.errors) > 50:
                print(f"    ... and {len(self.errors) - 50} more")
        print(f"{'=' * 60}")


# ---------------------------------------------------------------------------
# Deterministic ID generation
#
# All IDs are positive BIGINT.  Channel and thread IDs come from directory
# names.  All other IDs are derived from a SHA-256 hash of a stable key.
# ---------------------------------------------------------------------------

def stable_id(*parts: Any) -> int:
    """Return a stable positive BIGINT derived from the given parts."""
    key = "|".join(str(p) for p in parts).encode("utf-8")
    digest = hashlib.sha256(key).digest()
    # Take first 8 bytes, mask to positive BIGINT range (63 bits)
    val = int.from_bytes(digest[:8], "big") & 0x7FFFFFFFFFFFFFFF
    return val if val > 0 else 1


def artifact_id_for(file_path_from_root: str) -> int:
    return stable_id("artifact", file_path_from_root)


def task_id_for(task_key: str, channel_id: int) -> int:
    return stable_id("task", task_key, channel_id)


def metadata_id_for(entity_type: str, entity_id: int, property_key: str) -> int:
    return stable_id("metadata", entity_type, entity_id, property_key)


def edge_id_for(left_type: str, left_id: int, right_type: str, right_id: int, edge_type: str) -> int:
    return stable_id("edge", left_type, left_id, right_type, right_id, edge_type)


def thread_metadata_id_for(dialog_thread_id: int, metadata_key: str) -> int:
    return stable_id("thread_meta", dialog_thread_id, metadata_key)


# ---------------------------------------------------------------------------
# Timestamp utilities
#
# Lupopedia timestamps are BIGINT YYYYMMDDHHIISS (UTC).
# Header timestamps may be "20260322_143110" or "20260322" etc.
# ---------------------------------------------------------------------------

def parse_ymdhis(raw: Any) -> int:
    """
    Convert a raw timestamp value from a LUPOPEDIA HEADER to a BIGINT YYYYMMDDHHIISS.
    Returns 0 if the value cannot be parsed.
    Valid hours are 00–23 (validated per doctrine).
    """
    if raw is None:
        return 0
    s = str(raw).strip().replace("_", "").replace("-", "").replace(":", "").replace(" ", "")
    # Strip trailing non-digit characters
    s = re.sub(r"[^0-9].*$", "", s)
    if not s.isdigit():
        return 0
    # Pad or truncate to 14 digits
    if len(s) == 8:
        s = s + "000000"
    elif len(s) < 14:
        s = s.ljust(14, "0")
    s = s[:14]
    # Validate hour (HHII in positions 8–11)
    hour = int(s[8:10])
    if hour > 23:
        return 0
    return int(s)


def now_ymdhis() -> int:
    """Return current UTC time as YYYYMMDDHHIISS BIGINT."""
    import datetime
    n = datetime.datetime.now(datetime.timezone.utc)
    return int(n.strftime("%Y%m%d%H%M%S"))


# ---------------------------------------------------------------------------
# LUPOPEDIA HEADER parser
#
# Headers are YAML blocks between the first `---` and its closing `---`.
# The nested structure is:
#
#   lupopedia.headers:
#     field: value
#   lupopedia.edges:
#     outbound_edges:
#       - { to: "path", type: "type", weight: 1.0, reason: "..." }
#   lupopedia.footer:
#     ...
# ---------------------------------------------------------------------------

def parse_front_matter(text: str) -> dict:
    """
    Parse YAML front matter from a Lupopedia markdown file.
    Returns a flat dict of header fields, plus 'outbound_edges' list.
    Returns {} if no front matter is present or parsing fails.
    """
    if not text.startswith("---"):
        return {}
    end = text.find("\n---", 3)
    if end < 0:
        return {}
    block = text[3:end]

    if _HAVE_YAML:
        try:
            raw = yaml.safe_load(block)
        except yaml.YAMLError:
            return {}
        if not isinstance(raw, dict):
            return {}
        headers = dict(raw.get("lupopedia.headers", {}) or {})
        edges_block = raw.get("lupopedia.edges", {}) or {}
        outbound = edges_block.get("outbound_edges", []) or []
        footer = dict(raw.get("lupopedia.footer", {}) or {})
        headers["_outbound_edges"] = outbound if isinstance(outbound, list) else []
        headers["_footer"] = footer
        return headers
    else:
        # Minimal line-by-line fallback (no PyYAML)
        return _parse_front_matter_fallback(block)


def _parse_front_matter_fallback(block: str) -> dict:
    """
    Minimal YAML parser for LUPOPEDIA HEADERS without PyYAML.
    Only extracts flat key:value pairs from the lupopedia.headers section
    and the outbound_edges list.
    """
    out: dict = {}
    edges: list = []
    in_headers = False
    in_edges = False
    in_outbound = False

    for raw_line in block.splitlines():
        line = raw_line.rstrip()

        if line.strip() == "lupopedia.headers:":
            in_headers = True
            in_edges = False
            in_outbound = False
            continue
        if line.strip() in ("lupopedia.edges:", "lupopedia.footer:"):
            in_headers = False
            in_edges = (line.strip() == "lupopedia.edges:")
            in_outbound = False
            continue

        if in_headers:
            # Skip sub-objects beyond depth 1
            if not line.startswith("  "):
                in_headers = False
                continue
            stripped = line.strip()
            if ":" in stripped:
                k, _, v = stripped.partition(":")
                k = k.strip()
                v = v.strip().strip('"').strip("'")
                if k and not k.startswith("-"):
                    try:
                        out[k] = int(v)
                    except ValueError:
                        out[k] = v

        if in_edges:
            if "outbound_edges:" in line:
                in_outbound = True
                continue
            if in_outbound and line.strip().startswith("-"):
                # Parse inline dict notation: { to: "x", type: "y", weight: z, reason: "..." }
                m = re.search(r'\{([^}]+)\}', line)
                if m:
                    edge: dict = {}
                    for pair in m.group(1).split(","):
                        pair = pair.strip()
                        if ":" in pair:
                            ek, _, ev = pair.partition(":")
                            ek = ek.strip()
                            ev = ev.strip().strip('"').strip("'")
                            edge[ek] = ev
                    if edge:
                        edges.append(edge)

    out["_outbound_edges"] = edges
    out["_footer"] = {}
    return out


# ---------------------------------------------------------------------------
# Filesystem discovery
# ---------------------------------------------------------------------------

@dataclass
class ChannelRecord:
    channel_id: int
    path: Path
    index_headers: dict


@dataclass
class ThreadRecord:
    channel_id: int
    thread_id: int
    path: Path
    index_headers: dict


@dataclass
class ArtifactRecord:
    channel_id: int
    thread_id: int
    file_path: Path
    file_path_from_root: str
    headers: dict
    body: str


def discover_channels(repo_root: Path, channel_filter: int | None) -> list[ChannelRecord]:
    """Return list of channel dirs (integer-named) under lupo-channels/."""
    channels_dir = repo_root / "lupo-channels"
    if not channels_dir.is_dir():
        return []
    result = []
    for entry in sorted(channels_dir.iterdir()):
        if not entry.is_dir():
            continue
        try:
            cid = int(entry.name)
        except ValueError:
            continue
        if channel_filter is not None and cid != channel_filter:
            continue
        index_path = entry / "THREAD_INDEX.md"
        hdrs = {}
        if index_path.is_file():
            text = index_path.read_text(encoding="utf-8", errors="replace")
            hdrs = parse_front_matter(text)
        result.append(ChannelRecord(channel_id=cid, path=entry, index_headers=hdrs))
    return result


def discover_threads(
    repo_root: Path,
    channels: list[ChannelRecord],
    thread_filter: int | None,
) -> list[ThreadRecord]:
    """Return list of thread dirs under each channel's threads/ directory."""
    result = []
    for ch in channels:
        th_dir = ch.path / "threads"
        if not th_dir.is_dir():
            continue
        for entry in sorted(th_dir.iterdir()):
            if not entry.is_dir():
                continue
            try:
                tid = int(entry.name)
            except ValueError:
                continue
            if thread_filter is not None and tid != thread_filter:
                continue
            index_path = entry / "THREAD_INDEX.md"
            hdrs = {}
            if index_path.is_file():
                text = index_path.read_text(encoding="utf-8", errors="replace")
                hdrs = parse_front_matter(text)
            result.append(
                ThreadRecord(
                    channel_id=ch.channel_id,
                    thread_id=tid,
                    path=entry,
                    index_headers=hdrs,
                )
            )
    return result


def discover_artifacts(
    repo_root: Path,
    threads: list[ThreadRecord],
) -> list[ArtifactRecord]:
    """Return all markdown artifact files inside thread directories (excluding THREAD_INDEX.md)."""
    result = []
    for th in threads:
        for md_file in sorted(th.path.glob("*.md")):
            if md_file.name == "THREAD_INDEX.md":
                continue
            try:
                text = md_file.read_text(encoding="utf-8", errors="replace")
            except OSError:
                continue
            hdrs = parse_front_matter(text)
            # Extract body: text after the closing ---
            body = text
            if text.startswith("---"):
                end = text.find("\n---", 3)
                if end >= 0:
                    body = text[end + 4:].lstrip("\n")
            rel = str(md_file.relative_to(repo_root)).replace("\\", "/")
            result.append(
                ArtifactRecord(
                    channel_id=th.channel_id,
                    thread_id=th.thread_id,
                    file_path=md_file,
                    file_path_from_root=rel,
                    headers=hdrs,
                    body=body,
                )
            )
    return result


# ---------------------------------------------------------------------------
# Validation
# ---------------------------------------------------------------------------

def validate_artifact(
    art: ArtifactRecord,
    strict: bool,
    summary: Summary,
    verbose: bool,
) -> bool:
    """
    Validate a single artifact's headers.
    Returns True if the artifact should be imported, False to skip.
    On strict mode any validation failure causes immediate abort.
    """
    errors = []
    header_gate = validate_header(art.headers if isinstance(art.headers, dict) else {})
    if not header_gate.get("valid"):
        errors.extend(header_gate.get("errors", []))

    # file_path_from_root must match actual path
    declared = art.headers.get("file_path_from_root", "")
    if declared and declared != art.file_path_from_root:
        errors.append(
            f"file_path_from_root mismatch: header='{declared}' actual='{art.file_path_from_root}'"
        )

    # channel_id must match directory
    hdr_ch = art.headers.get("channel_id")
    if hdr_ch is not None:
        try:
            if int(hdr_ch) != art.channel_id:
                errors.append(
                    f"channel_id mismatch: header={hdr_ch} dir={art.channel_id}"
                )
        except (TypeError, ValueError):
            errors.append(f"channel_id not an integer in header: {hdr_ch!r}")

    # thread_id must match directory
    hdr_th = art.headers.get("thread_id")
    if hdr_th is not None:
        try:
            if int(hdr_th) != art.thread_id:
                errors.append(
                    f"thread_id mismatch: header={hdr_th} dir={art.thread_id}"
                )
        except (TypeError, ValueError):
            errors.append(f"thread_id not an integer in header: {hdr_th!r}")

    # when_updated should exist (version_when_written is deprecated)
    if not art.headers.get("when_updated"):
        errors.append("missing when_updated")
    if art.headers.get("version_when_written"):
        errors.append("deprecated field version_when_written present; use when_updated")

    # Validate timestamps from header.
    # last_modified_utc: backward compat for pre-v4.0.99 files (renamed to questions_toon).
    # REMOVE last_modified_utc from this list after Phase 3 corpus sweep (PRD 16 §19.5).
    for ts_key in ("last_modified_utc", "created_ymdhis"):
        raw = art.headers.get(ts_key)
        if raw:
            parsed = parse_ymdhis(raw)
            if parsed == 0:
                errors.append(f"invalid or un-parseable timestamp '{ts_key}': {raw!r}")

    if not errors:
        return True

    msg = f"{art.file_path_from_root}: {'; '.join(errors)}"
    summary.record_error(art.file_path_from_root, "; ".join(errors))
    if verbose:
        print(f"  [VALIDATION] {msg}", file=sys.stderr)
    if strict:
        print(f"[STRICT] Aborting on validation error: {msg}", file=sys.stderr)
        sys.exit(1)
    return False


# ---------------------------------------------------------------------------
# DB helpers
# ---------------------------------------------------------------------------

def get_db_connection(args: argparse.Namespace):
    """Return a PyMySQL connection or None for dry-run / missing driver."""
    if args.dry_run:
        return None
    if not _HAVE_PYMYSQL:
        print(
            "ERROR: PyMySQL is not installed.  Install it: pip install pymysql\n"
            "       Or run with --dry-run to preview without DB writes.",
            file=sys.stderr,
        )
        sys.exit(1)
    from lib.db_connection import merge_connection_params_with_args

    m = merge_connection_params_with_args(args)
    conn = pymysql.connect(
        host=m["host"],
        port=int(m["port"]),
        user=m["user"],
        password=m["password"],
        database=m["database"],
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor,
        autocommit=False,
    )
    return conn


def execute(conn, sql: str, params: tuple, dry_run: bool, verbose: bool) -> int:
    """
    Execute a single DML statement.
    Returns the number of affected rows (0 on INSERT IGNORE when row exists).
    """
    if dry_run:
        if verbose:
            print(f"  [DRY-RUN SQL] {sql[:120]} | params={str(params)[:80]}")
        return 0
    with conn.cursor() as cur:
        cur.execute(sql, params)
        return cur.rowcount


def table_exists(conn, table_name: str) -> bool:
    if conn is None:
        return True
    try:
        with conn.cursor() as cur:
            cur.execute(f"SELECT 1 FROM {table_name} WHERE 1 = 0")
        return True
    except Exception:
        return False


def actor_exists(conn, table_prefix: str, actor_id: int) -> bool:
    if conn is None:
        return True  # assume OK in dry-run
    with conn.cursor() as cur:
        cur.execute(
            f"SELECT 1 FROM {table_prefix}actors WHERE actor_id = %s AND is_deleted = 0 LIMIT 1",
            (actor_id,),
        )
        return cur.fetchone() is not None


def resolve_actor_id(
    conn,
    table_prefix: str,
    hdr_actor_id: Any,
    hdr_actor_name: Any,
    strict: bool,
    summary: Summary,
    context: str,
) -> int:
    """
    Resolve the actor_id to use for a DB record.
    If the declared actor doesn't exist, falls back to SYSTEM_ACTOR_ID_DEFAULT (1).
    """
    if hdr_actor_id is not None:
        try:
            aid = int(hdr_actor_id)
        except (TypeError, ValueError):
            aid = SYSTEM_ACTOR_ID_DEFAULT
        if actor_exists(conn, table_prefix, aid):
            return aid
        msg = f"actor_id {hdr_actor_id} ({hdr_actor_name!r}) not in DB — using system default"
        summary.record_error(context, msg)
        if strict:
            print(f"[STRICT] {context}: {msg}", file=sys.stderr)
            sys.exit(1)
        return SYSTEM_ACTOR_ID_DEFAULT
    return SYSTEM_ACTOR_ID_DEFAULT


# ---------------------------------------------------------------------------
# Phase 6: Import channels
# ---------------------------------------------------------------------------

def import_channels(
    conn,
    channels: list[ChannelRecord],
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    strict: bool,
    now: int,
) -> None:
    for ch in channels:
        summary.channels_found += 1
        hdrs = ch.index_headers
        channel_key = hdrs.get("channel_key") or f"channel_{ch.channel_id}"
        channel_name = hdrs.get("channel_name") or f"Channel {ch.channel_id}"
        actor_id = int(hdrs.get("actor_id") or hdrs.get("created_by_actor_id") or SYSTEM_ACTOR_ID_DEFAULT)
        created = parse_ymdhis(hdrs.get("when_updated") or hdrs.get("last_modified_utc") or hdrs.get("created_ymdhis"))  # last_modified_utc: backward compat (renamed questions_toon in PRD 16 v4.0.99) or now
        channel_type = hdrs.get("channel_type") or "orchestration"
        description = hdrs.get("purpose") or hdrs.get("description") or ""

        sql = f"""
INSERT IGNORE INTO {table_prefix}channels
  (channel_id, federation_node_id, created_by_actor_id, default_actor_id,
   department_id, channel_key, channel_slug, channel_type, language,
   channel_name, description, status_flag, created_ymdhis, updated_ymdhis,
   is_deleted, visibility_status, owner_actor_id, access_level,
   last_activity_ymdhis, channel_number)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
""".strip()
        params = (
            ch.channel_id,
            FEDERATION_NODE_ID_DEFAULT,
            actor_id,
            SYSTEM_ACTOR_ID_DEFAULT,
            1,                      # department_id default
            channel_key,
            channel_key[:32],       # channel_slug
            channel_type,
            "en",
            channel_name,
            description[:65535] if description else "",
            1,                      # status_flag active
            created,
            created,
            0,                      # is_deleted
            "active",
            actor_id,
            "internal",
            created,
            ch.channel_id,          # channel_number mirrors channel_id
        )
        rows = execute(conn, sql, params, dry_run, verbose)
        if verbose and rows:
            print(f"  [CHANNEL] Imported channel {ch.channel_id}")
        if rows or dry_run:
            summary.channels_imported += 1
        else:
            if verbose:
                print(f"  [CHANNEL] channel {ch.channel_id} already exists — skipped")
            summary.skipped_count += 1


# ---------------------------------------------------------------------------
# Phase 7: Import threads → lupo_dialog_threads
# ---------------------------------------------------------------------------

def import_threads(
    conn,
    threads: list[ThreadRecord],
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    strict: bool,
    now: int,
) -> None:
    for th in threads:
        summary.threads_found += 1
        hdrs = th.index_headers
        footer = hdrs.get("_footer") or {}
        title = hdrs.get("purpose") or hdrs.get("title") or f"Thread {th.thread_id}"
        actor_id = int(hdrs.get("actor_id") or SYSTEM_ACTOR_ID_DEFAULT)
        created = parse_ymdhis(hdrs.get("when_updated") or hdrs.get("last_modified_utc") or hdrs.get("created_ymdhis"))  # last_modified_utc: backward compat (renamed questions_toon in PRD 16 v4.0.99) or now
        thread_status = footer.get("thread_status") or hdrs.get("thread_status") or "Open"
        # Normalise status to DB vocabulary
        status_map = {
            "completed": "Closed",
            "unblocked": "Open",
            "blocked": "Blocked",
            "in-progress": "Open",
            "not-started": "Open",
        }
        db_status = status_map.get(thread_status.lower(), "Open")
        task_name = hdrs.get("task_id") or None

        tslug = title.lower().replace(" ", "-").replace("_", "-")
        if len(tslug) > 200:
            tslug = tslug[:200]
        thread_key = f"{tslug}-{th.thread_id}"[:255] if tslug.strip("-") else f"thread-{th.thread_id}"

        sql = f"""
INSERT IGNORE INTO {table_prefix}dialog_threads
  (dialog_thread_id, title, thread_key, federation_node_id, channel_id,
   task_name, created_by_actor_id, status, created_ymdhis,
   updated_ymdhis, is_deleted, owner_actor_id, assigned_actor_id,
   thread_type, thread_priority, visibility_status)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
""".strip()
        params = (
            th.thread_id,
            title[:255],
            thread_key,
            FEDERATION_NODE_ID_DEFAULT,
            th.channel_id,
            task_name[:255] if task_name else None,
            actor_id,
            db_status,
            created,
            created,
            0,
            actor_id,
            actor_id,
            "task",
            "normal",
            "active",
        )
        rows = execute(conn, sql, params, dry_run, verbose)
        if verbose and rows:
            print(f"  [THREAD] Imported thread {th.channel_id}/{th.thread_id}")
        if rows or dry_run:
            summary.threads_imported += 1
        else:
            if verbose:
                print(f"  [THREAD] thread {th.channel_id}/{th.thread_id} already exists — skipped")
            summary.skipped_count += 1


# ---------------------------------------------------------------------------
# Phase 8: Import tasks → lupo_tasks
# ---------------------------------------------------------------------------

def import_tasks(
    conn,
    threads: list[ThreadRecord],
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    strict: bool,
    now: int,
) -> None:
    """
    Import task records for threads that declare a task_id in their THREAD_INDEX header.
    task_key is derived from the header's task_id field (string).
    task_id (BIGINT) is deterministically generated.
    """
    if not table_exists(conn, f"{table_prefix}tasks"):
        if verbose:
            print(f"  [TASK] {table_prefix}tasks is missing — skipping task import phase")
        return

    seen_task_keys: set[str] = set()

    for th in threads:
        hdrs = th.index_headers
        task_key_raw = hdrs.get("task_id")
        if not task_key_raw:
            continue
        task_key = str(task_key_raw).strip()
        if not task_key or task_key in seen_task_keys:
            continue
        seen_task_keys.add(task_key)
        summary.tasks_found += 1

        actor_id = int(hdrs.get("actor_id") or SYSTEM_ACTOR_ID_DEFAULT)
        created = parse_ymdhis(hdrs.get("when_updated") or hdrs.get("last_modified_utc") or hdrs.get("created_ymdhis"))  # last_modified_utc: backward compat (renamed questions_toon in PRD 16 v4.0.99) or now
        footer = hdrs.get("_footer") or {}
        thread_status = footer.get("thread_status") or "in-progress"
        task_status_map = {
            "completed": "completed",
            "unblocked": "in-progress",
            "blocked": "blocked",
            "in-progress": "in-progress",
            "not-started": "pending",
        }
        task_status = task_status_map.get(str(thread_status).lower(), "in-progress")
        title = hdrs.get("purpose") or task_key
        task_db_id = task_id_for(task_key, th.channel_id)

        sql = f"""
INSERT IGNORE INTO {table_prefix}tasks
  (task_id, task_key, channel_id, owner_actor_id, title, description,
   task_type, task_status, task_priority, created_ymdhis, updated_ymdhis,
   is_deleted, visibility_status)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
""".strip()
        params = (
            task_db_id,
            task_key[:64],
            th.channel_id,
            actor_id,
            title[:255],
            "",
            "coordination",
            task_status,
            "normal",
            created,
            created,
            0,
            "active",
        )
        rows = execute(conn, sql, params, dry_run, verbose)
        if verbose and rows:
            print(f"  [TASK] Imported task {task_key}")
        if rows or dry_run:
            summary.tasks_imported += 1
        else:
            if verbose:
                print(f"  [TASK] task {task_key} already exists — skipped")
            summary.skipped_count += 1


# ---------------------------------------------------------------------------
# Phase 9: Import artifacts → lupo_artifacts
# ---------------------------------------------------------------------------

def import_artifacts(
    conn,
    artifacts: list[ArtifactRecord],
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    strict: bool,
    now: int,
) -> None:
    for art in artifacts:
        summary.artifacts_found += 1
        valid = validate_artifact(art, strict, summary, verbose)
        if not valid:
            summary.skipped_count += 1
            continue

        hdrs = art.headers
        actor_id = int(hdrs.get("actor_id") or SYSTEM_ACTOR_ID_DEFAULT)
        created_raw = hdrs.get("when_updated") or hdrs.get("last_modified_utc") or hdrs.get("created_ymdhis")
        created = parse_ymdhis(created_raw) or now
        artifact_type = hdrs.get("artifact_type") or "document"
        artifact_kind = hdrs.get("artifact_kind") or artifact_type
        art_id = artifact_id_for(art.file_path_from_root)

        # Build metadata JSON from headers (excluding internal keys)
        import json
        meta_for_json = {
            k: v for k, v in hdrs.items()
            if not k.startswith("_") and k not in ("artifact_type", "artifact_kind", "file_path_from_root")
        }

        sql = f"""
INSERT IGNORE INTO {table_prefix}artifacts
  (artifact_id, actor_id, federation_node_id, utc_timestamp, entity_type,
   content, metadata, channel_id, artifact_kind, file_path_from_root,
   created_ymdhis, updated_ymdhis, is_deleted)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
""".strip()
        params = (
            art_id,
            actor_id,
            FEDERATION_NODE_ID_DEFAULT,
            created,
            ARTIFACT_TYPE_MAP.get(artifact_type, artifact_type),
            art.body[:65535],
            json.dumps(meta_for_json),
            art.channel_id,
            artifact_kind[:50],
            art.file_path_from_root[:500],
            created,
            created,
            0,
        )
        rows = execute(conn, sql, params, dry_run, verbose)
        if verbose and rows:
            print(f"  [ARTIFACT] Imported {art.file_path_from_root}")
        if rows or dry_run:
            summary.artifacts_imported += 1
        else:
            if verbose:
                print(f"  [ARTIFACT] {art.file_path_from_root} already exists — skipped")
            summary.skipped_count += 1


def import_dialog_messages(
    conn,
    artifacts: list[ArtifactRecord],
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    strict: bool,
    now: int,
) -> None:
    if not table_exists(conn, f"{table_prefix}dialog_messages"):
        if verbose:
            print(f"  [MESSAGE] {table_prefix}dialog_messages is missing — skipping message import phase")
        return

    import json

    for art in artifacts:
        summary.messages_found += 1
        valid = validate_artifact(art, strict, summary, verbose)
        if not valid:
            summary.skipped_count += 1
            continue

        hdrs = art.headers
        actor_id = resolve_actor_id(
            conn,
            table_prefix,
            hdrs.get("actor_id"),
            hdrs.get("actor_name"),
            strict,
            summary,
            art.file_path_from_root,
        )
        created = parse_ymdhis(hdrs.get("when_updated") or hdrs.get("last_modified_utc") or hdrs.get("created_ymdhis"))  # last_modified_utc: backward compat (renamed questions_toon in PRD 16 v4.0.99) or now
        message_type = str(hdrs.get("artifact_type") or "text")[:64]
        body = art.body.strip()
        message_text = re.sub(r"\s+", " ", body)
        if not message_text:
            message_text = hdrs.get("purpose") or art.file_path.stem
        message_text = message_text[:1000]
        metadata_json = json.dumps(
            {
                "file_path_from_root": art.file_path_from_root,
                "artifact_kind": hdrs.get("artifact_kind") or hdrs.get("artifact_type") or "document",
                "when_updated": hdrs.get("when_updated"),
            }
        )
        dialog_message_id = stable_id("dialog_message", art.file_path_from_root)

        sql = f"""
INSERT IGNORE INTO {table_prefix}dialog_messages
  (dialog_message_id, message_id, dialog_thread_id, channel_id,
   from_actor_id, to_actor_id, read_by_actor_id, read_by_actor_utc,
   message_text, message_type, metadata_json, mood_framework,
   created_ymdhis, updated_ymdhis, is_deleted, message_body)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
""".strip()
        params = (
            dialog_message_id,
            dialog_message_id,
            art.thread_id,
            art.channel_id,
            actor_id,
            0,
            0,
            0,
            message_text,
            message_type,
            metadata_json,
            "western_analytical",
            created,
            created,
            0,
            body[:16777215],
        )
        rows = execute(conn, sql, params, dry_run, verbose)
        if verbose and rows:
            print(f"  [MESSAGE] Imported {art.file_path_from_root}")
        if rows or dry_run:
            summary.messages_imported += 1
        else:
            if verbose:
                print(f"  [MESSAGE] {art.file_path_from_root} already exists — skipped")
            summary.skipped_count += 1


# ---------------------------------------------------------------------------
# Phase 10: Import metadata → lupo_metadata + lupo_thread_metadata
# ---------------------------------------------------------------------------

_HEADER_METADATA_KEYS = (
    "when_updated",
    "lupopedia.schema",
    "web_path",
    "task_id",
    "delegation_chain",
    "purpose",
    "tags",
    "artifact_type",
    "artifact_kind",
    "actor_name",
    "project_id",
    "federation_node_id",
)


def import_metadata(
    conn,
    artifacts: list[ArtifactRecord],
    threads: list[ThreadRecord],
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    now: int,
) -> None:
    import json

    # Per-artifact metadata rows
    for art in artifacts:
        hdrs = art.headers
        actor_id = int(hdrs.get("actor_id") or SYSTEM_ACTOR_ID_DEFAULT)
        created = parse_ymdhis(hdrs.get("when_updated") or hdrs.get("last_modified_utc")) or now  # last_modified_utc: backward compat (renamed questions_toon in PRD 16 v4.0.99)
        art_db_id = artifact_id_for(art.file_path_from_root)

        for key in _HEADER_METADATA_KEYS:
            val = hdrs.get(key)
            if val is None:
                continue
            val_str = json.dumps(val) if isinstance(val, (list, dict)) else str(val)
            meta_id = metadata_id_for("artifact", art_db_id, key)
            # Compute domain_id = channel_id for locality
            sql = f"""
INSERT IGNORE INTO {table_prefix}metadata
  (metadata_id, entity_type, entity_id, domain_id, meta_type,
   property_key, property_value, created_ymdhis, updated_ymdhis,
   is_deleted, channel_id)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
""".strip()
            params = (
                meta_id,
                "artifact",
                art_db_id,
                art.channel_id,
                "lupopedia_header",
                key[:255],
                val_str[:65535],
                created,
                created,
                0,
                art.channel_id,
            )
            rows = execute(conn, sql, params, dry_run, verbose)
            if rows or dry_run:
                summary.metadata_rows_imported += 1

    # Per-thread metadata rows (from THREAD_INDEX footer)
    for th in threads:
        hdrs = th.index_headers
        footer = hdrs.get("_footer") or {}
        actor_id = int(hdrs.get("actor_id") or SYSTEM_ACTOR_ID_DEFAULT)
        created = parse_ymdhis(hdrs.get("when_updated") or hdrs.get("last_modified_utc")) or now  # last_modified_utc: backward compat (renamed questions_toon in PRD 16 v4.0.99)

        # Import task_id and thread_status as thread_metadata
        for mkey, mval in (
            ("task_id", hdrs.get("task_id")),
            ("thread_status", footer.get("thread_status")),
            ("artifact_count", footer.get("artifact_count")),
            ("purpose", hdrs.get("purpose")),
            ("when_updated", hdrs.get("when_updated")),
        ):
            if mval is None:
                continue
            tmeta_id = thread_metadata_id_for(th.thread_id, mkey)
            sql = f"""
INSERT IGNORE INTO {table_prefix}thread_metadata
  (thread_metadata_id, dialog_thread_id, metadata_key, metadata_value,
   metadata_type, created_ymdhis, updated_ymdhis, created_by_actor_id, is_deleted)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
""".strip()
            params = (
                tmeta_id,
                th.thread_id,
                mkey,
                str(mval)[:65535],
                "string",
                created,
                created,
                actor_id,
                0,
            )
            rows = execute(conn, sql, params, dry_run, verbose)
            if rows or dry_run:
                summary.metadata_rows_imported += 1


# ---------------------------------------------------------------------------
# Phase 11: Import explicit edges → lupo_edges
# ---------------------------------------------------------------------------

def import_edges(
    conn,
    artifacts: list[ArtifactRecord],
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    now: int,
) -> None:
    for art in artifacts:
        hdrs = art.headers
        outbound = hdrs.get("_outbound_edges") or []
        if not outbound:
            continue
        actor_id = int(hdrs.get("actor_id") or SYSTEM_ACTOR_ID_DEFAULT)
        created = parse_ymdhis(hdrs.get("when_updated") or hdrs.get("last_modified_utc")) or now  # last_modified_utc: backward compat (renamed questions_toon in PRD 16 v4.0.99)
        left_id = artifact_id_for(art.file_path_from_root)

        for edge_def in outbound:
            if not isinstance(edge_def, dict):
                continue
            to_path = edge_def.get("to", "").strip()
            edge_type = edge_def.get("type", "references").strip()
            weight_raw = edge_def.get("weight", 1.0)
            reason = str(edge_def.get("reason", ""))[:255]

            try:
                weight = float(weight_raw)
            except (TypeError, ValueError):
                weight = 1.0

            if not to_path:
                continue

            # right_object_id: stable hash of the target path
            right_id = stable_id("path", to_path)
            eid = edge_id_for("artifact", left_id, "path", right_id, edge_type)

            sql = f"""
INSERT IGNORE INTO {table_prefix}edges
  (edge_id, left_object_type, left_object_id, right_object_type, right_object_id,
   edge_type, edge_description, channel_id, domain_id, weight_score,
   actor_id, is_deleted, created_ymdhis, updated_ymdhis,
   semantic_weight, relationship_type, flare_weight, flare_reason,
   flare_auto_generated, flare_verified, flare_discovered_via)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
""".strip()
            params = (
                eid,
                "artifact",
                left_id,
                "path",
                right_id,
                edge_type[:100],
                reason,
                art.channel_id,
                FEDERATION_NODE_ID_DEFAULT,
                int(weight * 100),
                actor_id,
                0,
                created,
                created,
                min(weight, 1.0),
                "filesystem_artifact",
                min(weight, 1.0),
                reason,
                1,   # flare_auto_generated — imported by this script
                0,   # flare_verified — path not verified to exist at DB level
                "import_filesystem_channels_to_db",
            )
            rows = execute(conn, sql, params, dry_run, verbose)
            if rows or dry_run:
                summary.edges_imported += 1
            else:
                if verbose:
                    print(f"  [EDGE] edge {edge_type}→{to_path} already exists — skipped")


# ---------------------------------------------------------------------------
# Entry point
# ---------------------------------------------------------------------------

def build_arg_parser() -> argparse.ArgumentParser:
    p = argparse.ArgumentParser(
        description="Post-install filesystem → DB importer for Lupopedia channel/thread/artifact state.",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog=__doc__,
    )
    p.add_argument("--repo-root", default=".", help="Repository root directory (default: .)")
    p.add_argument("--channel", type=int, default=None, help="Import only this channel_id")
    p.add_argument("--thread", type=int, default=None, help="Import only this thread_id")
    p.add_argument("--dry-run", action="store_true", help="Scan and validate only — no DB writes")
    p.add_argument("--strict", action="store_true", help="Abort on first validation error")
    p.add_argument("--verbose", "-v", action="store_true", help="Print per-row progress")
    p.add_argument("--dialog-only", action="store_true", help="Import only channels, threads, and dialog messages")

    # DB connection
    db_group = p.add_argument_group(
        "Database connection (not needed with --dry-run); defaults from lupopedia-config.php"
    )
    db_group.add_argument("--host", default=None, help="Override DB host")
    db_group.add_argument("--port", default=None, type=int, help="Override DB port")
    db_group.add_argument("--user", default=None, help="Override DB user")
    db_group.add_argument("--password", default=None, help="Override DB password")
    db_group.add_argument("--database", default=None, help="Override DB name")
    db_group.add_argument("--table-prefix", default=None, help="Override table prefix")
    return p


def main() -> int:
    parser = build_arg_parser()
    args = parser.parse_args()

    repo_root = Path(args.repo_root).resolve()
    if not repo_root.is_dir():
        print(f"ERROR: --repo-root '{repo_root}' is not a directory", file=sys.stderr)
        return 1

    if not _HAVE_YAML:
        warnings.warn(
            "PyYAML not installed — using minimal YAML fallback parser. "
            "Install: pip install PyYAML",
            RuntimeWarning,
            stacklevel=1,
        )

    if args.table_prefix is not None:
        table_prefix = args.table_prefix
    elif not args.dry_run:
        from db_config import get_table_prefix

        table_prefix = get_table_prefix()
    else:
        table_prefix = "lupo_"
    summary = Summary()
    now = now_ymdhis()

    # -----------------------------------------------------------------------
    # Phase 1: Discover channels
    # -----------------------------------------------------------------------
    if args.verbose:
        print("Phase 1: Discovering channels …")
    channels = discover_channels(repo_root, args.channel)
    if args.verbose:
        print(f"  Found {len(channels)} channel(s): {[c.channel_id for c in channels]}")

    # -----------------------------------------------------------------------
    # Phase 2: Discover threads
    # -----------------------------------------------------------------------
    if args.verbose:
        print("Phase 2: Discovering threads …")
    threads = discover_threads(repo_root, channels, args.thread)
    if args.verbose:
        print(f"  Found {len(threads)} thread(s)")

    # -----------------------------------------------------------------------
    # Phase 3–4: Discover + parse artifacts
    # -----------------------------------------------------------------------
    if args.verbose:
        print("Phase 3–4: Discovering and parsing artifacts …")
    artifacts = discover_artifacts(repo_root, threads)
    if args.verbose:
        print(f"  Found {len(artifacts)} artifact file(s)")

    # -----------------------------------------------------------------------
    # Open DB connection
    # -----------------------------------------------------------------------
    try:
        conn = get_db_connection(args)
    except Exception as exc:
        print("ERROR: %s" % exc, file=sys.stderr)
        return 1
    if not args.dry_run and args.verbose:
        from lib.db_connection import merge_connection_params_with_args

        m = merge_connection_params_with_args(args)
        print("DB connection: %s@%s:%s/%s" % (m["user"], m["host"], m["port"], m["database"]))

    try:
        # -------------------------------------------------------------------
        # Phase 6: Import channels
        # -------------------------------------------------------------------
        if args.verbose:
            print("Phase 6: Importing channels …")
        import_channels(conn, channels, table_prefix, args.dry_run, args.verbose, summary, args.strict, now)

        # -------------------------------------------------------------------
        # Phase 7: Import threads
        # -------------------------------------------------------------------
        if args.verbose:
            print("Phase 7: Importing threads …")
        import_threads(conn, threads, table_prefix, args.dry_run, args.verbose, summary, args.strict, now)

        # -------------------------------------------------------------------
        # Phase 8: Import tasks
        # -------------------------------------------------------------------
        if args.verbose:
            print("Phase 8: Importing tasks …")
        import_tasks(conn, threads, table_prefix, args.dry_run, args.verbose, summary, args.strict, now)

        # -------------------------------------------------------------------
        # Phase 9b: Import dialog messages for thread artifacts
        # -------------------------------------------------------------------
        if args.verbose:
            print("Phase 9b: Importing dialog messages …")
        import_dialog_messages(conn, artifacts, table_prefix, args.dry_run, args.verbose, summary, args.strict, now)

        if args.dialog_only:
            if conn is not None:
                conn.commit()
                if args.verbose:
                    print("  Committed dialog-only import.")
            summary.print(dry_run=args.dry_run)
            return 0 if summary.error_count == 0 else 2

        # -------------------------------------------------------------------
        # Phase 9: Import artifacts
        # -------------------------------------------------------------------
        if args.verbose:
            print("Phase 9: Importing artifacts …")
        import_artifacts(conn, artifacts, table_prefix, args.dry_run, args.verbose, summary, args.strict, now)

        # -------------------------------------------------------------------
        # Phase 9: Import artifacts
        # -------------------------------------------------------------------
        if args.verbose:
            print("Phase 9: Importing artifacts …")
        import_artifacts(conn, artifacts, table_prefix, args.dry_run, args.verbose, summary, args.strict, now)

        # -------------------------------------------------------------------
        # Phase 10: Import metadata
        # -------------------------------------------------------------------
        if args.verbose:
            print("Phase 10: Importing metadata …")
        import_metadata(conn, artifacts, threads, table_prefix, args.dry_run, args.verbose, summary, now)

        # -------------------------------------------------------------------
        # Phase 11: Import edges
        # -------------------------------------------------------------------
        if args.verbose:
            print("Phase 11: Importing edges …")
        import_edges(conn, artifacts, table_prefix, args.dry_run, args.verbose, summary, now)

        # -------------------------------------------------------------------
        # Commit
        # -------------------------------------------------------------------
        if conn is not None:
            conn.commit()
            if args.verbose:
                print("  Committed.")

    except Exception as exc:
        if conn is not None:
            try:
                conn.rollback()
            except Exception:
                pass
        print(f"FATAL: {exc}", file=sys.stderr)
        import traceback
        traceback.print_exc()
        return 1
    finally:
        if conn is not None:
            conn.close()

    # Phase 12: Summary
    summary.print(dry_run=args.dry_run)
    return 0 if summary.error_count == 0 else 2


if __name__ == "__main__":
    sys.exit(main())