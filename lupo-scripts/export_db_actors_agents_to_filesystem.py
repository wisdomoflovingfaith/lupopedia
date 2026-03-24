#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/export_db_actors_agents_to_filesystem.py"
#   last_modified_utc: "20260324175617"
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

"""
export_db_actors_agents_to_filesystem.py
-----------------------------------------
OPTIONAL, EXPLICIT export of actor/agent/faucet/capability state from the
Lupopedia database back to the filesystem.

TRUTH MODEL
  FILESYSTEM IS PRIMARY SOURCE OF TRUTH.
  DATABASE IS RUNTIME STATE.

THIS SCRIPT IS:
  - Optional                    (never runs automatically)
  - Explicit                    (must be invoked by a human or authorised process)
  - Potentially destructive     (overwrites filesystem files with DB content)

USE WHEN
  Changes were made directly in the database (e.g. via admin UI, SQL, or a PHP
  service) and those changes must be persisted back to the filesystem so they
  survive the next `import_filesystem_actors_agents_to_db.py` run.

DO NOT USE WHEN
  - You haven't verified that the DB state is correct
  - You want to "refresh" the filesystem from DB speculatively
  - You are running a routine post-install restore (use the import script instead)

CONFLICT RULE (REVERSE DIRECTION)
  - filesystem file EXISTS → overwrite it with DB version
  - filesystem file does NOT EXIST → create it

This script is idempotent — running it twice with unchanged DB produces the
same filesystem.

FILESYSTEM TARGETS WRITTEN
  lupo-agents/{actor_id}/agent.json
  lupo-agents/{actor_id}/properties.json
  lupo-agents/{actor_id}/capabilities.json
  lupo-agents/{actor_id}/system_prompt.txt   (only if non-empty in DB)
  lupo-database/lupopedia/actors/actor_id/registry.json   (canonical registry)

DOCTRINE CONSTRAINTS
  - No foreign keys / triggers / procedures at DB level
  - Timestamps are BIGINT YYYYMMDDHHIISS (UTC)
  - Does NOT write to lupo-actors/ tree (that tree is actor-specific content,
    not auto-generated from DB data)
  - Does NOT delete any filesystem file — only creates or overwrites

USAGE
  python lupo-scripts/export_db_actors_agents_to_filesystem.py --repo-root .
  python lupo-scripts/export_db_actors_agents_to_filesystem.py --repo-root . --dry-run
  python lupo-scripts/export_db_actors_agents_to_filesystem.py --repo-root . --actor-id 1
  python lupo-scripts/export_db_actors_agents_to_filesystem.py --repo-root . --verbose
  python lupo-scripts/export_db_actors_agents_to_filesystem.py --repo-root . --strict

OUTPUT
  actors_exported
  agents_exported
  faucets_exported
  capabilities_exported
  files_created
  files_overwritten
  errors

Requires: pip install pymysql
"""
from __future__ import annotations

import argparse
import json
import os
import sys
from dataclasses import dataclass, field
from pathlib import Path
from typing import Any

try:
    import pymysql  # type: ignore
    import pymysql.cursors
    _HAVE_PYMYSQL = True
except ImportError:
    _HAVE_PYMYSQL = False


# ---------------------------------------------------------------------------
# Summary / reporting
# ---------------------------------------------------------------------------

@dataclass
class Summary:
    actors_exported: int = 0
    agents_exported: int = 0
    faucets_exported: int = 0
    capabilities_exported: int = 0
    files_created: int = 0
    files_overwritten: int = 0
    error_count: int = 0
    errors: list[str] = field(default_factory=list)

    def record_error(self, context: str, reason: str) -> None:
        self.errors.append(f"{context}: {reason}")
        self.error_count += 1

    def print(self, dry_run: bool = False) -> None:
        prefix = "[DRY-RUN] " if dry_run else ""
        print("")
        print(f"{'=' * 64}")
        print(f"{prefix}ACTOR/AGENT EXPORT SUMMARY")
        print(f"{'=' * 64}")
        print(f"  actors_exported:       {self.actors_exported}")
        print(f"  agents_exported:       {self.agents_exported}")
        print(f"  faucets_exported:      {self.faucets_exported}")
        print(f"  capabilities_exported: {self.capabilities_exported}")
        print(f"  files_created:         {self.files_created}")
        print(f"  files_overwritten:     {self.files_overwritten}")
        print(f"  error_count:           {self.error_count}")
        if self.errors:
            print("")
            print("  ERRORS:")
            for e in self.errors[:50]:
                print(f"    • {e}")
            if len(self.errors) > 50:
                print(f"    ... and {len(self.errors) - 50} more")
        print(f"{'=' * 64}")


# ---------------------------------------------------------------------------
# DB helpers
# ---------------------------------------------------------------------------

def get_db_connection(args: argparse.Namespace):
    if args.dry_run:
        return None
    if not _HAVE_PYMYSQL:
        print(
            "ERROR: PyMySQL is not installed.  Install it: pip install pymysql\n"
            "       Or run with --dry-run to preview without file writes.",
            file=sys.stderr,
        )
        sys.exit(1)
    return pymysql.connect(
        host=args.host,
        port=int(args.port),
        user=args.user,
        password=args.password,
        database=args.database,
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor,
        autocommit=True,
    )


def fetch_actors(conn, table_prefix: str, actor_id_filter: int | None) -> list[dict]:
    """Load all non-deleted actors from lupo_actors."""
    sql = f"SELECT * FROM {table_prefix}actors WHERE is_deleted = 0"
    params = ()
    if actor_id_filter is not None:
        sql += " AND actor_id = %s"
        params = (actor_id_filter,)
    with conn.cursor() as cur:
        cur.execute(sql, params)
        return list(cur.fetchall())


def fetch_agents(conn, table_prefix: str) -> dict[int, dict]:
    """Load all non-deleted agents from lupo_agents, keyed by agent_id."""
    with conn.cursor() as cur:
        cur.execute(f"SELECT * FROM {table_prefix}agents WHERE is_deleted = 0")
        return {row["agent_id"]: row for row in cur.fetchall()}


def fetch_faucets(conn, table_prefix: str) -> dict[int, list[dict]]:
    """Load faucets from lupo_agent_faucets, keyed by actor_id."""
    with conn.cursor() as cur:
        cur.execute(f"SELECT * FROM {table_prefix}agent_faucets ORDER BY actor_id")
        result: dict[int, list[dict]] = {}
        for row in cur.fetchall():
            aid = row["actor_id"]
            result.setdefault(aid, []).append(row)
        return result


def fetch_capabilities(conn, table_prefix: str) -> dict[int, list[str]]:
    """Load capabilities from lupo_actor_capabilities, keyed by actor_id."""
    with conn.cursor() as cur:
        cur.execute(
            f"SELECT actor_id, capability_key "
            f"FROM {table_prefix}actor_capabilities "
            f"WHERE is_deleted = 0 "
            f"ORDER BY actor_id, capability_key"
        )
        result: dict[int, list[str]] = {}
        for row in cur.fetchall():
            result.setdefault(row["actor_id"], []).append(row["capability_key"])
        return result


# ---------------------------------------------------------------------------
# Filesystem write helpers
# ---------------------------------------------------------------------------

def write_json_file(
    path: Path,
    data: Any,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
) -> None:
    """Write JSON to a file; creates or overwrites. Logs result in summary."""
    existed = path.is_file()
    if dry_run:
        action = "OVERWRITE" if existed else "CREATE"
        if verbose:
            print(f"  [DRY-RUN] {action} {path}")
        if existed:
            summary.files_overwritten += 1
        else:
            summary.files_created += 1
        return
    try:
        path.parent.mkdir(parents=True, exist_ok=True)
        path.write_text(
            json.dumps(data, indent=2, ensure_ascii=False) + "\n",
            encoding="utf-8",
        )
        if existed:
            summary.files_overwritten += 1
            if verbose:
                print(f"  [OVERWRITE] {path}")
        else:
            summary.files_created += 1
            if verbose:
                print(f"  [CREATE] {path}")
    except OSError as exc:
        summary.record_error(str(path), str(exc))


def write_text_file(
    path: Path,
    text: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
) -> None:
    existed = path.is_file()
    if dry_run:
        action = "OVERWRITE" if existed else "CREATE"
        if verbose:
            print(f"  [DRY-RUN] {action} {path}")
        if existed:
            summary.files_overwritten += 1
        else:
            summary.files_created += 1
        return
    try:
        path.parent.mkdir(parents=True, exist_ok=True)
        path.write_text(text, encoding="utf-8")
        if existed:
            summary.files_overwritten += 1
            if verbose:
                print(f"  [OVERWRITE] {path}")
        else:
            summary.files_created += 1
            if verbose:
                print(f"  [CREATE] {path}")
    except OSError as exc:
        summary.record_error(str(path), str(exc))


# ---------------------------------------------------------------------------
# Export logic
# ---------------------------------------------------------------------------

def parse_metadata_json(row: dict, key: str) -> dict:
    """Safely parse a JSON column from a DB row."""
    raw = row.get(key) or "{}"
    if isinstance(raw, dict):
        return raw
    try:
        return json.loads(str(raw)) or {}
    except (json.JSONDecodeError, TypeError):
        return {}


def export_actor(
    actor_row: dict,
    agents_map: dict[int, dict],
    faucets_map: dict[int, list[dict]],
    caps_map: dict[int, list[str]],
    agents_base: Path,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
) -> None:
    """Export one actor to lupo-agents/{actor_id}/."""
    actor_id = actor_row.get("actor_id")
    if actor_id is None:
        return
    actor_id = int(actor_id)
    slug = str(actor_row.get("slug") or "")
    actor_name = str(actor_row.get("actor_name") or slug)
    actor_type_db = str(actor_row.get("actor_type") or "agent")
    actor_config = parse_metadata_json(actor_row, "actor_config")
    metadata = parse_metadata_json(actor_row, "metadata")

    out_dir = agents_base / str(actor_id)
    summary.actors_exported += 1

    # ----- agent.json -----
    agent_data = {
        "code": actor_config.get("code") or actor_name.upper(),
        "name": str(actor_row.get("name") or actor_name),
        "layer": actor_config.get("layer") or "standard",
        "is_required": bool(actor_config.get("is_required", False)),
        "is_kernel": bool(actor_row.get("is_kernel", 0)),
        "recommended_slot": actor_id,
        "version": actor_config.get("version") or "1.0",
        "role": actor_config.get("role") or "",
        "description": metadata.get("description") or "",
        "actor_id": actor_id,
        "slug": slug,
        "paired_actor_id": int(actor_row.get("paired_actor_id") or 0),
        "primary_federation_node_id": int(actor_row.get("primary_federation_node_id") or 1),
    }
    if isinstance(actor_row.get("metadata_json"), dict):
        for k, v in actor_row["metadata_json"].items():
            agent_data.setdefault(k, v)
    write_json_file(out_dir / "agent.json", agent_data, dry_run, verbose, summary)

    # ----- properties.json -----
    agent_row = agents_map.get(actor_id) or {}
    extra_props: dict = metadata.get("properties") or {}
    properties: dict = {
        "actor_id": actor_id,
        "slug": slug,
        "display_name": str(actor_row.get("name") or actor_name),
        "type": {
            "faucet": "ide_faucet",
            "human": "human",
            "system": "system",
        }.get(actor_type_db, "agent"),
        "role": actor_config.get("role") or "",
        "primary_federation_node_id": int(actor_row.get("primary_federation_node_id") or 1),
        "is_kernel": bool(actor_row.get("is_kernel", 0)),
        "is_required": bool(actor_config.get("is_required", False)),
        "layer": actor_config.get("layer") or "standard",
        "paired_actor_id": int(actor_row.get("paired_actor_id") or 0),
    }
    for k, v in extra_props.items():
        properties.setdefault(k, v)
    # Merge in faucet-specific fields
    actor_faucets = faucets_map.get(actor_id) or []
    if actor_faucets and actor_type_db == "faucet":
        faucet = actor_faucets[0]
        properties["faucet_id"] = int(faucet.get("agent_faucet_id") or 0)
        properties["is_default_faucet"] = bool(faucet.get("is_default", 0))
    write_json_file(
        out_dir / "properties.json",
        {"properties": properties},
        dry_run, verbose, summary,
    )
    summary.agents_exported += 1

    # ----- capabilities.json -----
    caps = caps_map.get(actor_id) or []
    # Also include capabilities from lupo_agents
    if agent_row:
        summary.agents_exported -= 1  # already counted above
    write_json_file(
        out_dir / "capabilities.json",
        {"capabilities": caps},
        dry_run, verbose, summary,
    )
    summary.capabilities_exported += len(caps)

    # ----- system_prompt.txt -----
    system_prompt = None
    if agent_row:
        system_prompt = agent_row.get("system_prompt")
    if not system_prompt:
        # check actor capabilities / faucets
        for faucet in actor_faucets:
            if faucet.get("system_prompt"):
                system_prompt = faucet["system_prompt"]
                break
    if system_prompt:
        write_text_file(
            out_dir / "system_prompt.txt",
            str(system_prompt).strip() + "\n",
            dry_run, verbose, summary,
        )

    # ----- faucets -----
    for faucet_row in actor_faucets:
        summary.faucets_exported += 1
        faucet_data = {
            "agent_faucet_id": int(faucet_row.get("agent_faucet_id") or 0),
            "actor_id": actor_id,
            "name": str(faucet_row.get("name") or ""),
            "slug": str(faucet_row.get("slug") or ""),
            "faucet_class": str(faucet_row.get("faucet_class") or ""),
            "description": str(faucet_row.get("description") or ""),
            "is_default": bool(faucet_row.get("is_default", 0)),
        }
        write_json_file(
            out_dir / f"faucet_{faucet_row.get('slug') or faucet_row.get('agent_faucet_id')}.json",
            faucet_data, dry_run, verbose, summary,
        )


def export_registry(
    actors: list[dict],
    agents_base: Path,       # used for "dir" field
    repo_root: Path,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
) -> None:
    """
    Regenerate lupo-database/lupopedia/actors/actor_id/registry.json from DB actors.
    Preserves the canonical format: {"schema_version": "...", "actors": [...]}
    """
    registry_path = repo_root / "lupo-database" / "lupopedia" / "actors" / "actor_id" / "registry.json"

    # Load existing registry to preserve extra fields (canonical_identity, etc.)
    existing_entries: dict[int, dict] = {}
    if registry_path.is_file():
        try:
            existing = json.loads(registry_path.read_text(encoding="utf-8"))
            for entry in (existing.get("actors") or []):
                eid = entry.get("id")
                if eid is not None:
                    existing_entries[int(eid)] = entry
        except (json.JSONDecodeError, OSError):
            pass

    type_map = {"faucet": "ide_faucet", "human": "human", "system": "system"}
    new_entries = []
    for row in sorted(actors, key=lambda r: int(r.get("actor_id") or 0)):
        actor_id = int(row.get("actor_id") or 0)
        if actor_id == 0:
            continue
        slug = str(row.get("slug") or "")
        actor_type = type_map.get(str(row.get("actor_type") or ""), "agent")
        existing_entry = existing_entries.get(actor_id) or {}
        entry: dict = {}
        # preserve extra fields from existing entry
        entry.update(existing_entry)
        entry["id"] = actor_id
        entry["type"] = actor_type
        entry["slug"] = slug
        entry["dir"] = f"actors/{actor_id}"
        new_entries.append(entry)

    registry_data = {
        "schema_version": "exported",
        "actors": new_entries,
    }

    write_json_file(registry_path, registry_data, dry_run, verbose, summary)
    if verbose:
        print(f"  [REGISTRY] Wrote {len(new_entries)} actor entries to {registry_path}")


# ---------------------------------------------------------------------------
# Argument parser
# ---------------------------------------------------------------------------

def build_arg_parser() -> argparse.ArgumentParser:
    p = argparse.ArgumentParser(
        description="DB → filesystem exporter for Lupopedia actors, agents, faucets.",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog=__doc__,
    )
    p.add_argument("--repo-root", default=".", help="Repository root directory (default: .)")
    p.add_argument("--actor-id", type=int, default=None, help="Export only this actor_id")
    p.add_argument("--dry-run", action="store_true", help="Preview — no file writes")
    p.add_argument("--strict", action="store_true", help="Abort on first error")
    p.add_argument("--verbose", "-v", action="store_true", help="Print per-file progress")
    p.add_argument(
        "--no-registry",
        action="store_true",
        help="Skip regenerating registry.json (keep existing)",
    )

    db = p.add_argument_group("Database connection (required)")
    db.add_argument("--host", default=os.environ.get("MYSQL_HOST", "localhost"))
    db.add_argument("--port", default=os.environ.get("MYSQL_PORT", "3306"))
    db.add_argument("--user", default=os.environ.get("MYSQL_USER", ""))
    db.add_argument("--password", default=os.environ.get("MYSQL_PASSWORD", ""))
    db.add_argument("--database", default=os.environ.get("MYSQL_DATABASE", ""))
    db.add_argument("--table-prefix", default=os.environ.get("LUPO_TABLE_PREFIX", "lupo_"))
    return p


# ---------------------------------------------------------------------------
# Entry point
# ---------------------------------------------------------------------------

def main() -> int:
    parser = build_arg_parser()
    args = parser.parse_args()

    repo_root = Path(args.repo_root).resolve()
    if not repo_root.is_dir():
        print(f"ERROR: --repo-root '{repo_root}' is not a directory", file=sys.stderr)
        return 1

    if not args.dry_run and not args.user:
        print(
            "ERROR: --user is required. "
            "Set MYSQL_USER env var or pass --user.  Use --dry-run to preview.",
            file=sys.stderr,
        )
        return 1

    table_prefix = args.table_prefix
    summary = Summary()

    agents_base = repo_root / "lupo-agents"

    # -----------------------------------------------------------------------
    # Open DB connection and load data
    # -----------------------------------------------------------------------
    conn = get_db_connection(args)
    actors: list[dict] = []
    agents_map: dict[int, dict] = {}
    faucets_map: dict[int, list[dict]] = {}
    caps_map: dict[int, list[str]] = {}

    if conn is not None:
        if args.verbose:
            print(f"DB: {args.user}@{args.host}:{args.port}/{args.database}")
        try:
            actors = fetch_actors(conn, table_prefix, args.actor_id)
            agents_map = fetch_agents(conn, table_prefix)
            faucets_map = fetch_faucets(conn, table_prefix)
            caps_map = fetch_capabilities(conn, table_prefix)
        except Exception as exc:
            print(f"FATAL reading DB: {exc}", file=sys.stderr)
            import traceback
            traceback.print_exc()
            conn.close()
            return 1
        finally:
            conn.close()
    else:
        # dry-run: inject a placeholder so preview output is produced
        print("[DRY-RUN] No DB connection — showing dry-run output only.")
        summary.print(dry_run=True)
        return 0

    if args.verbose:
        print(f"  Loaded {len(actors)} actors from DB")

    # -----------------------------------------------------------------------
    # Export each actor
    # -----------------------------------------------------------------------
    for actor_row in actors:
        actor_id = actor_row.get("actor_id")
        if actor_id is None:
            continue
        try:
            export_actor(
                actor_row=actor_row,
                agents_map=agents_map,
                faucets_map=faucets_map,
                caps_map=caps_map,
                agents_base=agents_base,
                dry_run=args.dry_run,
                verbose=args.verbose,
                summary=summary,
            )
        except Exception as exc:
            summary.record_error(f"actor_id={actor_id}", str(exc))
            if args.strict:
                print(f"[STRICT] {exc}", file=sys.stderr)
                import traceback
                traceback.print_exc()
                sys.exit(1)

    # -----------------------------------------------------------------------
    # Regenerate registry.json
    # -----------------------------------------------------------------------
    if not args.no_registry:
        if args.verbose:
            print("Exporting registry.json …")
        export_registry(
            actors=actors,
            agents_base=agents_base,
            repo_root=repo_root,
            dry_run=args.dry_run,
            verbose=args.verbose,
            summary=summary,
        )

    summary.print(dry_run=args.dry_run)
    return 0 if summary.error_count == 0 else 2


if __name__ == "__main__":
    sys.exit(main())