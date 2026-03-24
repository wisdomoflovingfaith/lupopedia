#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/import_filesystem_actors_agents_to_db.py"
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
import_filesystem_actors_agents_to_db.py
-----------------------------------------
Deterministic post-install importer for Lupopedia actors, agents, faucets,
and capabilities.

TRUTH MODEL
  FILESYSTEM IS PRIMARY SOURCE OF TRUTH.
  DATABASE IS RUNTIME STATE.

CONFLICT RULES (critical)
  - Record exists in BOTH filesystem AND DB → filesystem OVERWRITES the DB record.
    No merge. No partial update. Full field replacement.
  - Record exists ONLY IN DB → leave it alone. Log it as "db_only_record".
  - Record exists ONLY IN FILESYSTEM → INSERT into DB.

This script is idempotent — running it multiple times produces the same DB state.

FILESYSTEM SOURCES (scanned in priority order)
  1. lupo-database/lupopedia/actors/actor_id/registry.json
     Canonical actor_id → { id, type, slug, dir } mapping.
  2. lupo-agents/{id}/agent.json         — agent code/name/layer/role/description
  3. lupo-agents/{id}/properties.json    — actor_id, slug, type, role, extra props
  4. lupo-agents/{id}/capabilities.json  — list of capability strings
  5. lupo-agents/{id}/system_prompt.txt  — system prompt text
  6. lupo-actors/{id}/agent.json         — supplementary actor definition
  7. lupo-actors/{id}/properties.json    — supplementary properties
  8. lupo-actors/{id}/capabilities.json  — supplementary capabilities

DB TABLES WRITTEN
  lupo_actors           — one row per actor (PK: actor_name)
  lupo_agents           — one row per non-human actor with agent data (PK: agent_id)
  lupo_agent_faucets    — one row per ide_faucet type actor (PK: agent_faucet_id)
  lupo_actor_capabilities — one row per capability (UNIQUE: actor_id+domain+key)

DOCTRINE CONSTRAINTS
  - No foreign keys / triggers / procedures at DB level
  - All IDs application-generated: actor_id comes from registry json
  - All timestamps are BIGINT YYYYMMDDHHIISS (UTC)
  - No invented data — missing fields are left NULL or use documented defaults
  - INSERT ... ON DUPLICATE KEY UPDATE for all tables (filesystem overwrites DB)

USAGE
  python lupo-scripts/import_filesystem_actors_agents_to_db.py --repo-root .
  python lupo-scripts/import_filesystem_actors_agents_to_db.py --repo-root . --dry-run
  python lupo-scripts/import_filesystem_actors_agents_to_db.py --repo-root . --strict
  python lupo-scripts/import_filesystem_actors_agents_to_db.py --repo-root . --verbose
  python lupo-scripts/import_filesystem_actors_agents_to_db.py --repo-root . --actor-id 1
  python lupo-scripts/import_filesystem_actors_agents_to_db.py --repo-root . --actor-type agent

OUTPUT
  actors_found, actors_inserted, actors_overwritten, actors_skipped
  agents_found, agents_inserted, agents_overwritten
  faucets_inserted, faucets_overwritten
  capabilities_inserted, capabilities_overwritten
  db_only_records (list of actor_name values in DB but not on filesystem)
  errors

Requires: pip install pymysql
"""
from __future__ import annotations

import argparse
import hashlib
import json
import os
import sys
import warnings
from dataclasses import dataclass, field
from pathlib import Path
from typing import Any

from lib.header_validation import parse_front_matter_header, validate_header

try:
    import pymysql  # type: ignore
    import pymysql.cursors
    _HAVE_PYMYSQL = True
except ImportError:
    _HAVE_PYMYSQL = False

# ---------------------------------------------------------------------------
# Constants
# ---------------------------------------------------------------------------

TABLE_PREFIX_DEFAULT = "lupo_"
FEDERATION_NODE_ID_DEFAULT = 1
SYSTEM_ACTOR_ID_DEFAULT = 1
DOMAIN_ID_DEFAULT = 1

# ---------------------------------------------------------------------------
# Deterministic ID helpers
# ---------------------------------------------------------------------------

def stable_id(*parts: Any) -> int:
    """Return a stable positive BIGINT for the given parts."""
    key = "|".join(str(p) for p in parts).encode("utf-8")
    digest = hashlib.sha256(key).digest()
    val = int.from_bytes(digest[:8], "big") & 0x7FFFFFFFFFFFFFFF
    return val if val > 0 else 1


def faucet_id_for(actor_id: int, slug: str) -> int:
    return stable_id("agent_faucet", actor_id, slug)


def capability_id_for(actor_id: int, domain_id: int, capability_key: str) -> int:
    return stable_id("actor_capability", actor_id, domain_id, capability_key)


# ---------------------------------------------------------------------------
# Timestamp utilities
# ---------------------------------------------------------------------------

def now_ymdhis() -> int:
    import datetime
    n = datetime.datetime.now(datetime.timezone.utc)
    return int(n.strftime("%Y%m%d%H%M%S"))

# ---------------------------------------------------------------------------
# Summary / reporting
# ---------------------------------------------------------------------------

@dataclass
class Summary:
    actors_found: int = 0
    actors_inserted: int = 0
    actors_overwritten: int = 0
    actors_skipped: int = 0
    agents_found: int = 0
    agents_inserted: int = 0
    agents_overwritten: int = 0
    faucets_found: int = 0
    faucets_inserted: int = 0
    faucets_overwritten: int = 0
    capabilities_found: int = 0
    capabilities_inserted: int = 0
    capabilities_overwritten: int = 0
    db_only_records: list[str] = field(default_factory=list)
    error_count: int = 0
    errors: list[str] = field(default_factory=list)

    def record_error(self, context: str, reason: str) -> None:
        self.errors.append(f"{context}: {reason}")
        self.error_count += 1

    def print(self, dry_run: bool = False) -> None:
        prefix = "[DRY-RUN] " if dry_run else ""
        print("")
        print(f"{'=' * 64}")
        print(f"{prefix}ACTOR/AGENT IMPORT SUMMARY")
        print(f"{'=' * 64}")
        print(f"  actors_found:              {self.actors_found}")
        print(f"  actors_inserted:           {self.actors_inserted}")
        print(f"  actors_overwritten:        {self.actors_overwritten}")
        print(f"  actors_skipped:            {self.actors_skipped}")
        print(f"  agents_found:              {self.agents_found}")
        print(f"  agents_inserted:           {self.agents_inserted}")
        print(f"  agents_overwritten:        {self.agents_overwritten}")
        print(f"  faucets_found:             {self.faucets_found}")
        print(f"  faucets_inserted:          {self.faucets_inserted}")
        print(f"  faucets_overwritten:       {self.faucets_overwritten}")
        print(f"  capabilities_found:        {self.capabilities_found}")
        print(f"  capabilities_inserted:     {self.capabilities_inserted}")
        print(f"  capabilities_overwritten:  {self.capabilities_overwritten}")
        if self.db_only_records:
            print(f"  db_only_records:           {len(self.db_only_records)}")
            for r in self.db_only_records[:20]:
                print(f"    • {r}")
            if len(self.db_only_records) > 20:
                print(f"    ... and {len(self.db_only_records) - 20} more")
        print(f"  error_count:               {self.error_count}")
        if self.errors:
            print("")
            print("  ERRORS:")
            for e in self.errors[:50]:
                print(f"    • {e}")
            if len(self.errors) > 50:
                print(f"    ... and {len(self.errors) - 50} more")
        print(f"{'=' * 64}")


# ---------------------------------------------------------------------------
# Filesystem data model
# ---------------------------------------------------------------------------

@dataclass
class ActorDef:
    """All data collected from filesystem for one actor."""
    actor_id: int
    actor_type: str    # "agent" | "ide_faucet" | "human" | "system"
    slug: str
    actor_name: str    # canonical = slug (normalised)
    name: str          # display name
    code: str          # uppercase code/codename
    role: str
    description: str
    layer: str
    is_kernel: int
    is_required: int
    version: str
    system_prompt: str
    capabilities: list[str]
    properties: dict
    paired_actor_id: int
    primary_federation_node_id: int
    source_dir: str    # path segment for logging

# ---------------------------------------------------------------------------
# JSON loading helpers
# ---------------------------------------------------------------------------

def load_json_file(path: Path, context: str, strict: bool, summary: Summary) -> dict | list | None:
    if not path.is_file():
        return None
    try:
        data = json.loads(path.read_text(encoding="utf-8", errors="replace"))
        return data
    except json.JSONDecodeError as exc:
        msg = f"JSON parse error in {path}: {exc}"
        summary.record_error(context, msg)
        if strict:
            print(f"[STRICT] {msg}", file=sys.stderr)
            sys.exit(1)
        return None


def _str(v: Any, default: str = "") -> str:
    if v is None:
        return default
    return str(v).strip()


def _int(v: Any, default: int = 0) -> int:
    try:
        return int(v)
    except (TypeError, ValueError):
        return default


def _bool_int(v: Any, default: int = 0) -> int:
    if isinstance(v, bool):
        return 1 if v else 0
    try:
        return 1 if int(v) else 0
    except (TypeError, ValueError):
        return default

# ---------------------------------------------------------------------------
# Actor data discovery
# ---------------------------------------------------------------------------

def load_actor_from_agent_dir(
    agent_dir: Path,
    actor_id: int,
    actor_type: str,
    slug: str,
    strict: bool,
    summary: Summary,
) -> ActorDef:
    """
    Load actor definition from lupo-agents/{id}/ directory.
    Returns an ActorDef with all available fields populated.
    """
    context = f"lupo-agents/{agent_dir.name}"

    agent_data = load_json_file(agent_dir / "agent.json", context, strict, summary) or {}
    props_raw = load_json_file(agent_dir / "properties.json", context, strict, summary) or {}
    cap_raw = load_json_file(agent_dir / "capabilities.json", context, strict, summary) or {}

    # properties.json may wrap in {"properties": {...}}
    if isinstance(props_raw, dict) and "properties" in props_raw:
        props = props_raw["properties"] or {}
    else:
        props = props_raw if isinstance(props_raw, dict) else {}

    # capabilities.json may wrap in {"capabilities": [...]}
    if isinstance(cap_raw, dict) and "capabilities" in cap_raw:
        caps = cap_raw["capabilities"] or []
    else:
        caps = cap_raw if isinstance(cap_raw, list) else []
    caps = [str(c) for c in caps if c]

    # system prompt
    sp_path = agent_dir / "system_prompt.txt"
    system_prompt = sp_path.read_text(encoding="utf-8", errors="replace").strip() if sp_path.is_file() else ""

    # Merge: registry → properties.json → agent.json
    # actor_id from registry is authoritative
    resolved_actor_id = actor_id or _int(props.get("actor_id")) or _int(agent_data.get("actor_id"))
    resolved_slug = slug or _str(props.get("slug")) or _str(agent_data.get("slug"))

    name = (
        _str(agent_data.get("name"))
        or _str(props.get("display_name"))
        or _str(props.get("canonical_name"))
        or resolved_slug
    )
    code = _str(agent_data.get("code")) or name.upper()
    role = (
        _str(agent_data.get("role"))
        or _str(props.get("role"))
        or _str(props.get("persona"))
    )
    description = _str(agent_data.get("description")) or _str(props.get("full_name"))
    layer = _str(agent_data.get("layer")) or "standard"
    is_kernel = _bool_int(agent_data.get("is_kernel") or props.get("is_kernel"))
    is_required = _bool_int(agent_data.get("is_required") or props.get("is_required"))
    version = _str(agent_data.get("version")) or "1.0"
    paired_actor_id = _int(agent_data.get("paired_actor_id") or props.get("paired_actor_id"))
    fed_node = _int(props.get("primary_federation_node_id") or FEDERATION_NODE_ID_DEFAULT)

    return ActorDef(
        actor_id=resolved_actor_id,
        actor_type=actor_type,
        slug=resolved_slug,
        actor_name=resolved_slug,  # actor_name = slug (normalised lowercase)
        name=name,
        code=code,
        role=role,
        description=description,
        layer=layer,
        is_kernel=is_kernel,
        is_required=is_required,
        version=version,
        system_prompt=system_prompt,
        capabilities=caps,
        properties=props,
        paired_actor_id=paired_actor_id,
        primary_federation_node_id=fed_node,
        source_dir=str(agent_dir.relative_to(agent_dir.parent.parent)),
    )


def supplement_from_actors_dir(
    actor_def: ActorDef,
    actors_base: Path,
    strict: bool,
    summary: Summary,
) -> None:
    """
    Supplement actor_def with data from lupo-actors/{id}/ or lupo-actors/{slug}/.
    Only fills in fields that are still empty; does NOT overwrite.
    """
    candidates = [
        actors_base / str(actor_def.actor_id),
        actors_base / actor_def.slug,
    ]
    for candidate in candidates:
        if not candidate.is_dir():
            continue
        context = f"lupo-actors/{candidate.name}"
        agent_data = load_json_file(candidate / "agent.json", context, strict, summary) or {}
        props_raw = load_json_file(candidate / "properties.json", context, strict, summary) or {}
        if isinstance(props_raw, dict) and "properties" in props_raw:
            props = props_raw.get("properties") or {}
        else:
            props = props_raw if isinstance(props_raw, dict) else {}
        cap_raw = load_json_file(candidate / "capabilities.json", context, strict, summary) or {}
        if isinstance(cap_raw, dict) and "capabilities" in cap_raw:
            caps = cap_raw.get("capabilities") or []
        else:
            caps = cap_raw if isinstance(cap_raw, list) else []
        new_caps = [str(c) for c in caps if c and c not in actor_def.capabilities]
        actor_def.capabilities.extend(new_caps)

        if not actor_def.role:
            actor_def.role = _str(agent_data.get("role") or props.get("role"))
        if not actor_def.description:
            actor_def.description = _str(agent_data.get("description") or props.get("full_name"))
        if not actor_def.system_prompt:
            sp = candidate / "system_prompt.txt"
            if sp.is_file():
                actor_def.system_prompt = sp.read_text(encoding="utf-8", errors="replace").strip()
        # Merge any new properties
        for k, v in props.items():
            actor_def.properties.setdefault(k, v)
        break  # only supplement from first match


# ---------------------------------------------------------------------------
# Registry loader
# ---------------------------------------------------------------------------

def load_registry(
    repo_root: Path,
    actor_id_filter: int | None,
    actor_type_filter: str | None,
    strict: bool,
    summary: Summary,
) -> list[dict]:
    """
    Load actor entries from the canonical registry JSON.
    Returns list of dicts: {id, type, slug, dir}
    """
    registry_path = repo_root / "lupo-database" / "lupopedia" / "actors" / "actor_id" / "registry.json"
    if not registry_path.is_file():
        summary.record_error("registry.json", "File not found — cannot proceed")
        if strict:
            sys.exit(1)
        return []

    data = load_json_file(registry_path, "registry.json", strict, summary)
    if not data or not isinstance(data, dict):
        summary.record_error("registry.json", "Invalid JSON or unexpected structure")
        return []

    actors = data.get("actors", [])
    if not isinstance(actors, list):
        summary.record_error("registry.json", "actors field is not a list")
        return []

    result = []
    for entry in actors:
        if not isinstance(entry, dict):
            continue
        eid = entry.get("id")
        if eid is None:
            continue
        try:
            eid = int(eid)
        except (TypeError, ValueError):
            continue
        if actor_id_filter is not None and eid != actor_id_filter:
            continue
        etype = _str(entry.get("type")) or "agent"
        if actor_type_filter and etype != actor_type_filter:
            continue
        result.append({
            "id": eid,
            "type": etype,
            "slug": _str(entry.get("slug")) or f"actor_{eid}",
            "dir": _str(entry.get("dir")),
        })
    return result


# ---------------------------------------------------------------------------
# Full actor data discovery
# ---------------------------------------------------------------------------

def discover_actors(
    repo_root: Path,
    actor_id_filter: int | None,
    actor_type_filter: str | None,
    strict: bool,
    summary: Summary,
) -> list[ActorDef]:
    """
    Load all actor definitions from filesystem sources.
    Priority: registry.json → lupo-agents/{id}/ → lupo-actors/{id|slug}/
    """
    registry_entries = load_registry(repo_root, actor_id_filter, actor_type_filter, strict, summary)
    if not registry_entries:
        return []

    agents_base = repo_root / "lupo-agents"
    actors_base = repo_root / "lupo-actors"
    result = []

    for entry in registry_entries:
        actor_id = entry["id"]
        actor_type = entry["type"]
        slug = entry["slug"]
        summary.actors_found += 1

        agent_dir = agents_base / str(actor_id)
        if not agent_dir.is_dir():
            # No agent dir — still produce a minimal ActorDef from registry
            actor_def = ActorDef(
                actor_id=actor_id,
                actor_type=actor_type,
                slug=slug,
                actor_name=slug,
                name=slug,
                code=slug.upper(),
                role="",
                description="",
                layer="standard",
                is_kernel=0,
                is_required=0,
                version="1.0",
                system_prompt="",
                capabilities=[],
                properties={},
                paired_actor_id=0,
                primary_federation_node_id=FEDERATION_NODE_ID_DEFAULT,
                source_dir=f"registry/{actor_id}",
            )
        else:
            actor_def = load_actor_from_agent_dir(
                agent_dir, actor_id, actor_type, slug, strict, summary
            )

        # Supplement from lupo-actors/ tree
        if actors_base.is_dir():
            supplement_from_actors_dir(actor_def, actors_base, strict, summary)

        result.append(actor_def)

    return result


# ---------------------------------------------------------------------------
# DB helpers
# ---------------------------------------------------------------------------

def get_db_connection(args: argparse.Namespace):
    if args.dry_run:
        return None
    if not _HAVE_PYMYSQL:
        print(
            "ERROR: PyMySQL is not installed.  Install it: pip install pymysql\n"
            "       Or run with --dry-run to preview without DB writes.",
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
        autocommit=False,
    )


def db_execute(conn, sql: str, params: tuple, dry_run: bool, verbose: bool) -> int:
    if dry_run:
        if verbose:
            print(f"  [DRY-RUN SQL] {sql[:100].strip()} | params={str(params)[:80]}")
        return 1  # Pretend success in dry-run
    with conn.cursor() as cur:
        cur.execute(sql, params)
        return cur.rowcount


def get_existing_actor_names(conn, table_prefix: str) -> set[str]:
    """Return set of actor_name values currently in lupo_actors."""
    if conn is None:
        return set()
    with conn.cursor() as cur:
        cur.execute(
            f"SELECT actor_name FROM {table_prefix}actors WHERE is_deleted = 0"
        )
        return {row["actor_name"] for row in cur.fetchall()}


def get_existing_agent_ids(conn, table_prefix: str) -> set[int]:
    """Return set of agent_id values currently in lupo_agents."""
    if conn is None:
        return set()
    with conn.cursor() as cur:
        cur.execute(
            f"SELECT agent_id FROM {table_prefix}agents WHERE is_deleted = 0"
        )
        return {row["agent_id"] for row in cur.fetchall()}


def actor_exists_in_db(conn, table_prefix: str, actor_name: str) -> bool:
    if conn is None:
        return False
    with conn.cursor() as cur:
        cur.execute(
            f"SELECT 1 FROM {table_prefix}actors WHERE actor_name = %s AND is_deleted = 0 LIMIT 1",
            (actor_name,),
        )
        return cur.fetchone() is not None


# ---------------------------------------------------------------------------
# Phase: Import actors → lupo_actors
# ---------------------------------------------------------------------------

def import_actor(
    conn,
    actor: ActorDef,
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    now: int,
) -> None:
    """
    Upsert one actor into lupo_actors.
    Filesystem OVERWRITES DB on conflict (INSERT ... ON DUPLICATE KEY UPDATE).
    """
    existing = actor_exists_in_db(conn, table_prefix, actor.actor_name)
    actor_config = json.dumps({
        "code": actor.code,
        "role": actor.role,
        "layer": actor.layer,
        "version": actor.version,
        "is_required": bool(actor.is_required),
    })
    metadata = json.dumps({
        "description": actor.description,
        "actor_type": actor.actor_type,
        "source_dir": actor.source_dir,
        "properties": {k: v for k, v in actor.properties.items() if isinstance(v, (str, int, float, bool, type(None)))},
    })

    # Map actor_type to actor_type column vocabulary
    db_actor_type = {
        "agent": "agent",
        "ide_faucet": "faucet",
        "human": "human",
        "system": "system",
    }.get(actor.actor_type, "agent")

    sql = f"""
INSERT INTO {table_prefix}actors
  (actor_name, actor_id, actor_type, slug, name,
   created_ymdhis, updated_ymdhis, is_active, is_deleted,
   actor_config, primary_federation_node_id, is_kernel,
   paired_actor_id, is_agent, actor_root_path, metadata)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
ON DUPLICATE KEY UPDATE
  actor_id                  = VALUES(actor_id),
  actor_type                = VALUES(actor_type),
  slug                      = VALUES(slug),
  name                      = VALUES(name),
  updated_ymdhis            = VALUES(updated_ymdhis),
  is_active                 = VALUES(is_active),
  actor_config              = VALUES(actor_config),
  primary_federation_node_id = VALUES(primary_federation_node_id),
  is_kernel                 = VALUES(is_kernel),
  paired_actor_id           = VALUES(paired_actor_id),
  is_agent                  = VALUES(is_agent),
  actor_root_path           = VALUES(actor_root_path),
  metadata                  = VALUES(metadata)
""".strip()

    actor_root_path = f"actors/{actor.actor_id}"
    is_agent = 1 if actor.actor_type in ("agent", "ide_faucet") else 0

    params = (
        actor.actor_name,
        actor.actor_id if actor.actor_id else None,
        db_actor_type,
        actor.slug,
        actor.name or actor.slug,
        now,
        now,
        1,
        0,
        actor_config,
        actor.primary_federation_node_id,
        actor.is_kernel,
        actor.paired_actor_id,
        is_agent,
        actor_root_path,
        metadata,
    )
    rows = db_execute(conn, sql, params, dry_run, verbose)
    if verbose:
        action = "OVERWRITE" if existing else "INSERT"
        print(f"  [ACTOR] {action} actor_id={actor.actor_id} slug={actor.slug}")
    if existing:
        summary.actors_overwritten += 1
    else:
        summary.actors_inserted += 1


# ---------------------------------------------------------------------------
# Phase: Import agents → lupo_agents
# ---------------------------------------------------------------------------

def import_agent(
    conn,
    actor: ActorDef,
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    existing_agent_ids: set[int],
    now: int,
) -> None:
    """
    Upsert one agent row into lupo_agents.
    Only for actors that have an agent_id (non-human, non-system actors).
    """
    if not actor.actor_id or actor.actor_type == "human":
        return
    summary.agents_found += 1
    agent_id = actor.actor_id
    existing = agent_id in existing_agent_ids
    agent_key = actor.slug  # slug is the unique key

    sql = f"""
INSERT INTO {table_prefix}agents
  (agent_id, agent_key, agent_name, archetype, description,
   version, is_global_authority, is_internal_only,
   created_ymdhis, updated_ymdhis, is_deleted,
   system_prompt, provider)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
ON DUPLICATE KEY UPDATE
  agent_key         = VALUES(agent_key),
  agent_name        = VALUES(agent_name),
  archetype         = VALUES(archetype),
  description       = VALUES(description),
  version           = VALUES(version),
  updated_ymdhis    = VALUES(updated_ymdhis),
  system_prompt     = VALUES(system_prompt)
""".strip()

    params = (
        agent_id,
        agent_key[:100],
        actor.name[:150],
        actor.role[:150] if actor.role else None,
        actor.description or None,
        actor.version[:50],
        1 if actor.is_kernel else 0,
        0,
        now,
        now,
        0,
        actor.system_prompt or None,
        "lupopedia",
    )
    db_execute(conn, sql, params, dry_run, verbose)
    if verbose:
        action = "OVERWRITE" if existing else "INSERT"
        print(f"  [AGENT] {action} agent_id={agent_id} key={agent_key}")
    if existing:
        summary.agents_overwritten += 1
    else:
        summary.agents_inserted += 1


# ---------------------------------------------------------------------------
# Phase: Import faucets → lupo_agent_faucets
# ---------------------------------------------------------------------------

def import_faucet(
    conn,
    actor: ActorDef,
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    now: int,
) -> None:
    """
    Insert/update a faucet row for ide_faucet type actors.
    """
    if actor.actor_type != "ide_faucet":
        return
    summary.faucets_found += 1
    faucet_db_id = faucet_id_for(actor.actor_id, actor.slug)
    caps_json = json.dumps(actor.capabilities)
    props = actor.properties

    # Check if faucet already exists (by agent_faucet_id)
    existing = False
    if conn is not None:
        with conn.cursor() as cur:
            cur.execute(
                f"SELECT 1 FROM {table_prefix}agent_faucets WHERE agent_faucet_id = %s LIMIT 1",
                (faucet_db_id,),
            )
            existing = cur.fetchone() is not None

    sql = f"""
INSERT INTO {table_prefix}agent_faucets
  (agent_faucet_id, actor_id, name, alias_name, slug, faucet_class,
   description, capabilities_json, is_default, domain_id,
   created_ymdhis, updated_ymdhis)
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
ON DUPLICATE KEY UPDATE
  actor_id           = VALUES(actor_id),
  name               = VALUES(name),
  alias_name         = VALUES(alias_name),
  description        = VALUES(description),
  capabilities_json  = VALUES(capabilities_json),
  updated_ymdhis     = VALUES(updated_ymdhis)
""".strip()

    params = (
        faucet_db_id,
        actor.actor_id,
        actor.name[:100],
        actor.code[:100] if actor.code != actor.name else None,
        actor.slug[:100],
        "ide_faucet",
        actor.description or None,
        caps_json,
        1 if props.get("lead_orchestration") else 0,
        DOMAIN_ID_DEFAULT,
        now,
        now,
    )
    db_execute(conn, sql, params, dry_run, verbose)
    if verbose:
        action = "OVERWRITE" if existing else "INSERT"
        print(f"  [FAUCET] {action} faucet_id={faucet_db_id} slug={actor.slug}")
    if existing:
        summary.faucets_overwritten += 1
    else:
        summary.faucets_inserted += 1


# ---------------------------------------------------------------------------
# Phase: Import capabilities → lupo_actor_capabilities
# ---------------------------------------------------------------------------

def import_capabilities(
    conn,
    actor: ActorDef,
    table_prefix: str,
    dry_run: bool,
    verbose: bool,
    summary: Summary,
    now: int,
) -> None:
    """Import each capability string as a row in lupo_actor_capabilities."""
    if not actor.capabilities or not actor.actor_id:
        return

    for cap_key in actor.capabilities:
        cap_key = str(cap_key).strip()[:100]
        if not cap_key:
            continue
        summary.capabilities_found += 1
        cap_id = capability_id_for(actor.actor_id, DOMAIN_ID_DEFAULT, cap_key)

        # Check pre-existence
        existing = False
        if conn is not None:
            with conn.cursor() as cur:
                cur.execute(
                    f"SELECT 1 FROM {table_prefix}actor_capabilities "
                    f"WHERE actor_id = %s AND domain_id = %s AND capability_key = %s AND is_deleted = 0 LIMIT 1",
                    (actor.actor_id, DOMAIN_ID_DEFAULT, cap_key),
                )
                existing = cur.fetchone() is not None

        sql = f"""
INSERT INTO {table_prefix}actor_capabilities
  (actor_capability_id, actor_id, domain_id, capability_key,
   created_ymdhis, updated_ymdhis, is_deleted)
VALUES (%s, %s, %s, %s, %s, %s, %s)
ON DUPLICATE KEY UPDATE
  actor_capability_id = VALUES(actor_capability_id),
  updated_ymdhis      = VALUES(updated_ymdhis),
  is_deleted          = 0
""".strip()

        params = (
            cap_id,
            actor.actor_id,
            DOMAIN_ID_DEFAULT,
            cap_key,
            now,
            now,
            0,
        )
        db_execute(conn, sql, params, dry_run, verbose)
        if existing:
            summary.capabilities_overwritten += 1
        else:
            summary.capabilities_inserted += 1


# ---------------------------------------------------------------------------
# Phase: Identify DB-only actors
# ---------------------------------------------------------------------------

def find_db_only_actors(
    conn,
    table_prefix: str,
    filesystem_actor_names: set[str],
    summary: Summary,
) -> None:
    """Find actors in DB that have no corresponding filesystem record."""
    if conn is None:
        return
    with conn.cursor() as cur:
        cur.execute(
            f"SELECT actor_name FROM {table_prefix}actors WHERE is_deleted = 0"
        )
        db_names = {row["actor_name"] for row in cur.fetchall()}
    db_only = db_names - filesystem_actor_names
    summary.db_only_records = sorted(db_only)


# ---------------------------------------------------------------------------
# Argument parser
# ---------------------------------------------------------------------------

def build_arg_parser() -> argparse.ArgumentParser:
    p = argparse.ArgumentParser(
        description="Filesystem → DB importer for Lupopedia actors, agents, faucets.",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog=__doc__,
    )
    p.add_argument("--repo-root", default=".", help="Repository root directory (default: .)")
    p.add_argument("--actor-id", type=int, default=None, help="Import only this actor_id")
    p.add_argument("--actor-type", default=None,
                   choices=["agent", "ide_faucet", "human", "system"],
                   help="Filter by actor type")
    p.add_argument("--dry-run", action="store_true", help="Scan + validate — no DB writes")
    p.add_argument("--strict", action="store_true", help="Abort on first error")
    p.add_argument("--verbose", "-v", action="store_true", help="Print per-row progress")

    db = p.add_argument_group("Database connection (not needed with --dry-run)")
    db.add_argument("--host", default=os.environ.get("MYSQL_HOST", "localhost"))
    db.add_argument("--port", default=os.environ.get("MYSQL_PORT", "3306"))
    db.add_argument("--user", default=os.environ.get("MYSQL_USER", ""))
    db.add_argument("--password", default=os.environ.get("MYSQL_PASSWORD", ""))
    db.add_argument("--database", default=os.environ.get("MYSQL_DATABASE", ""))
    db.add_argument("--table-prefix", default=os.environ.get("LUPO_TABLE_PREFIX", "lupo_"))
    return p


# ---------------------------------------------------------------------------
# Optional markdown header gate for actor trees
# ---------------------------------------------------------------------------

def validate_actor_tree_markdown_headers(repo_root: Path, strict: bool, summary: Summary) -> None:
    """
    Validate LUPOPEDIA headers for markdown files that contain frontmatter
    under actor/agent trees before DB writes begin.
    """
    scan_roots = (
        repo_root / "lupo-actors",
        repo_root / "lupo-agents",
    )
    for root in scan_roots:
        if not root.is_dir():
            continue
        for md in root.rglob("*.md"):
            try:
                text = md.read_text(encoding="utf-8", errors="replace")
            except Exception:
                continue
            if not text.startswith("---"):
                continue
            parsed = parse_front_matter_header(text)
            if not parsed.get("valid"):
                summary.record_error(str(md), "; ".join(parsed.get("errors", [])))
                if strict:
                    print("[STRICT] Invalid header in %s: %s" % (md, parsed.get("errors", [])), file=sys.stderr)
                    sys.exit(1)
                continue
            validation = validate_header(parsed.get("header") or {})
            if not validation.get("valid"):
                summary.record_error(str(md), "; ".join(validation.get("errors", [])))
                if strict:
                    print("[STRICT] Invalid header in %s: %s" % (md, validation.get("errors", [])), file=sys.stderr)
                    sys.exit(1)


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
            "ERROR: --user is required for DB import. "
            "Set MYSQL_USER env var or pass --user.  Use --dry-run to skip DB writes.",
            file=sys.stderr,
        )
        return 1

    table_prefix = args.table_prefix
    summary = Summary()
    now = now_ymdhis()

    validate_actor_tree_markdown_headers(repo_root, args.strict, summary)

    # -----------------------------------------------------------------------
    # Phase 1: Discover actors from filesystem
    # -----------------------------------------------------------------------
    if args.verbose:
        print("Phase 1: Discovering actors from filesystem …")
    actors = discover_actors(
        repo_root, args.actor_id, args.actor_type, args.strict, summary
    )
    if args.verbose:
        print(f"  Discovered {len(actors)} actor(s)")

    if not actors:
        print("No actors found. Check registry.json and --repo-root.", file=sys.stderr)
        summary.print(dry_run=args.dry_run)
        return 1

    # -----------------------------------------------------------------------
    # Open DB connection
    # -----------------------------------------------------------------------
    conn = get_db_connection(args)
    if conn is not None and args.verbose:
        print(f"DB: {args.user}@{args.host}:{args.port}/{args.database}")

    try:
        # Pre-load existing state for insert vs overwrite tracking
        existing_agent_ids = get_existing_agent_ids(conn, table_prefix)
        filesystem_actor_names: set[str] = set()

        # -----------------------------------------------------------------------
        # Phase 2: Import each actor
        # -----------------------------------------------------------------------
        if args.verbose:
            print("Phase 2: Importing actors …")
        for actor in actors:
            if not actor.actor_name:
                summary.record_error(actor.source_dir, "actor_name is empty — skipping")
                summary.actors_skipped += 1
                continue
            filesystem_actor_names.add(actor.actor_name)
            import_actor(conn, actor, table_prefix, args.dry_run, args.verbose, summary, now)

        # -----------------------------------------------------------------------
        # Phase 3: Import agents
        # -----------------------------------------------------------------------
        if args.verbose:
            print("Phase 3: Importing agents …")
        for actor in actors:
            import_agent(conn, actor, table_prefix, args.dry_run, args.verbose, summary, existing_agent_ids, now)

        # -----------------------------------------------------------------------
        # Phase 4: Import faucets
        # -----------------------------------------------------------------------
        if args.verbose:
            print("Phase 4: Importing faucets …")
        for actor in actors:
            import_faucet(conn, actor, table_prefix, args.dry_run, args.verbose, summary, now)

        # -----------------------------------------------------------------------
        # Phase 5: Import capabilities
        # -----------------------------------------------------------------------
        if args.verbose:
            print("Phase 5: Importing capabilities …")
        for actor in actors:
            import_capabilities(conn, actor, table_prefix, args.dry_run, args.verbose, summary, now)

        # -----------------------------------------------------------------------
        # Phase 6: Identify DB-only actors
        # -----------------------------------------------------------------------
        if args.verbose:
            print("Phase 6: Identifying DB-only actors …")
        find_db_only_actors(conn, table_prefix, filesystem_actor_names, summary)
        if args.verbose and summary.db_only_records:
            print(f"  DB-only records: {summary.db_only_records}")

        # -----------------------------------------------------------------------
        # Commit
        # -----------------------------------------------------------------------
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

    summary.print(dry_run=args.dry_run)
    return 0 if summary.error_count == 0 else 2


if __name__ == "__main__":
    sys.exit(main())