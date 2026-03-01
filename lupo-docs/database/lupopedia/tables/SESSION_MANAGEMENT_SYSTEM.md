# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/database/lupopedia/tables/SESSION_MANAGEMENT_SYSTEM.md"
  system_version: "4.0.53"
  last_modified_utc: "20260301134200"
  channel_id: 0
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "system_overview"
  purpose: "Comprehensive documentation of the Lupopedia session management system for multi-agent isolation and sync with default session templates"
  mood_rgb: "4169E1"
  traits: ["session_management", "multi_agent", "isolation", "v4.0.53"]
  tags: ["sessions", "lupo_sessions", "ide_agents", "ai_agents", "sync", "default_sessions"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "docs/toons/lupo_sessions.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "bin/session_manager.php", type: "implementation_reference", weight: 0.9 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/toons/lupo_channel_logs.toon.json", type: "related_schema", weight: 0.8 }
    - { to: "docs/toons/lupo_channel_boot_lifecycle.toon.json", type: "integration_reference", weight: 0.8 }
  semantic_tags: ["session_management", "agent_isolation", "database_sync"]

flare.footer:
  version: "4.0.53"
  last_verified: "20260301"
  last_verified_by: "windsurf"
---

# Lupopedia Session Management System

## Overview
The Lupopedia Session Management System is designed to handle sessions for multiple IDE and AI agents, ensuring isolation and preventing cross-contamination. It synchronizes local `session.json` files with the central `lupo_sessions` database table, providing robust multi-agent support (up to 10+ simultaneous agents). This system aligns with FLARE doctrine, emphasizing no foreign keys, no triggers, BIGINT timestamps, and federation awareness.

Key objectives:
- Prevent session overlaps between agents (e.g., Kiro, Windsurf, Gemini).
- Maintain audit trails for session lifecycle.
- Support CLI operations for sync, monitoring, and cleanup.
- Ensure security through validation, expiration, and node-based isolation.

This document expands on the Windsurf broadcast (commit 737646bc), providing detailed architecture, implementation notes, and usage guidelines.

## Architecture
The system is built around a CLI script (`bin/session_manager.php`) that interacts with the database and local files. It supports MySQL/MariaDB/PostgreSQL/SQLite for flexibility.

### Core Components
1. **lupo_sessions Table**: Central storage for session data (see TOON schema reference).
2. **session.json Files**: Local per-terminal/agent JSON files for quick access.
3. **CLI Interface**: Commands for sync, active sessions, cleanup, stats, and validation.
4. **Validation Engine**: Checks session integrity (existence, status, age, security).
5. **Logging Integration**: All actions logged to `lupo_channel_logs` for audit.

### Session Lifecycle
- **Creation**: New UUID generated on first sync; inserted into DB.
- **Update**: Refresh `last_seen_ymdhis` and metadata on activity.
- **Expiration**: Sessions >24h old flagged/removed via cleanup.
- **Revocation**: Manual flag for invalid sessions (e.g., compromised agents).
- **Deletion**: Soft delete via `is_deleted` (per doctrine).

## Session.json to Database Mapping
The system uses an UPSERT pattern to sync local `session.json` to `lupo_sessions`. Mapping ensures data consistency:

| Session.json Field | lupo_sessions Column | Type | Description |
|--------------------|----------------------|------|-------------|
| `current_session_id` | `session_id` | varchar(128) | UUID with actor prefix (e.g., `L-lupo-1006-<uuid>`). |
| `actor_id` | `actor_id` | bigint | IDE/AI agent ID (e.g., 1002 for Cursor). |
| `node_id` | `federation_node_id` | bigint | Federation node linkage (0 for lupopedia.com). |
| `last_active_ymdhis` | `last_seen_ymdhis` | bigint | UTC timestamp in `YYYYMMDDHHIISS` format. |
| `system_version` | `metadata` | json | JSON object with version info, status flags (e.g., {"version": "4.0.52", "active": true}). |
| N/A | `created_ymdhis` | bigint | Row creation timestamp (auto-set on insert). |
| N/A | `is_deleted` | tinyint | Soft delete flag (0 default). |
| N/A | `status` | varchar(64) | Session status (e.g., 'active', 'expired', 'revoked'). |

---

## Session ID Prefix Requirement
To ensure absolute isolation in multi-agent environments, ALL `session_id` values MUST follow the prefix format:
`L-lupo-<actor_id>-<UUID>`

**Example Implementation**:
```php
$actor_id = 1006;
$uuid = uuid_create(UUID_TYPE_RANDOM);
$session_id = "L-lupo-" . $actor_id . "-" . $uuid;
```

**Benefits**:
- **Auditability**: Instantly identify which agent owns a database session via SQL query.
- **Isolation**: Prevents generic UUID collisions across disparate terminal instances.
- **Traceability**: Links database activity logs directly to the actor's local filesystem context.

**UPSERT Logic** (in PHP):
- Check if `session_id` exists: `SELECT COUNT(*) FROM lupo_sessions WHERE session_id = ?`.
- If not: INSERT with defaults.
- If yes: UPDATE `last_seen_ymdhis`, `metadata`.
- Cross-DB compatible (uses `INSERT ... ON DUPLICATE KEY UPDATE` for MySQL).

## Multi-Agent Collision Prevention
To avoid cross-contamination in multi-terminal setups:

- **Unique IDs**: Each session gets a UUID, bound to terminal/agent.
- **Database Locks**: `session_id` as PK ensures uniqueness; row locks during UPSERT.
- **Federation Awareness**: `federation_node_id` separates clusters (e.g., node 0 vs. remote nodes).
- **Actor Coverage**: Explicit support for 10+ agents, including:
  - **IDE Agents**: Kiro (1000), Windsurf (1001), Cursor (1002), Antigravity (1003), Warp (1004), Cascade (1005), Codex (1007).
  - **AI Agents**: Gemini (1006), Lilith (2), Rose (3), Eris (4), Metis (5), Anubis (19), Vishwakarma (25).
  - **System/Kernel**: Actor 0 (root), Captain Wolfie (1).

If conflicts detected (e.g., duplicate actor_id on same node), log escalation to `lupo_channel_escalations`.

## CLI Interface
Run from repo root: `php bin/session_manager.php <command> [args]`.

### Commands
- **sync**: Sync all/local sessions to DB.
  ```
  php bin/session_manager.php sync [--all] [--actor=1002]
  ```
  - `--all`: Sync for all agents (admin only).
  - Output: "✅ Sessions synced: 5 updated, 2 inserted."

- **active**: List active sessions.
  ```
  php bin/session_manager.php active [--node=0]
  ```
  - Output table: Session ID, Actor, Last Seen, Status.

- **cleanup**: Remove expired/revoked sessions (>24h or flagged).
  ```
  php bin/session_manager.php cleanup [--dry-run]
  ```
  - `--dry-run`: Simulate without deletes.
  - Logs: "🧹 Cleaned 3 expired sessions."

- **stats**: Session metrics.
  ```
  php bin/session_manager.php stats
  ```
  - Output: Active: 12, Expired: 2, By Agent: Windsurf (3), etc.

- **validate <session_id>**: Check a session.
  ```
  php bin/session_manager.php validate abc123-uuid
  ```
  - Output: "✅ Valid: Active, Last Seen: 20260301090000" or "❌ Invalid: Expired."

All commands log to `lupo_channel_logs` (channel_id=0, actor_id=current).

## Default Sessions
Default session templates are stored in `lupo-sessions/actor_<id>_default.json` for key actors. These provide pre-configured sessions for init/boot, synced to DB if missing. All use L-lupo-<actor_id> prefix, 'active' status, and role-specific metadata.

### Creation & Sync
- On boot/install: Check DB; load default if absent, update timestamps/UUID, UPSERT.
- Hierarchy: Files overwrite DB; DB overwrites TOONs via generate_toons.

### Examples
For actor 0 (System):
```json
{
  "current_session_id": "L-lupo-0-00000000-0000-0000-0000-000000000000",
  "actor_id": 0,
  "federation_node_id": 0,
  "last_active_ymdhis": "20260301134200",
  "system_version": "4.0.53",
  "status": "active",
  "metadata": {
    "session_type": "default",
    "created_by": "system_init",
    "actor_type": "system_agent",
    "prefix_enforced": true
  }
}
```

For actor 1002 (Cursor, IDE):
```json
{
  "current_session_id": "L-lupo-1002-00000000-0000-0000-0000-000000000000",
  "actor_id": 1002,
  "federation_node_id": 0,
  "last_active_ymdhis": "20260301134200",
  "system_version": "4.0.53",
  "status": "active",
  "metadata": {
    "session_type": "default",
    "created_by": "system_init",
    "actor_type": "ide_agent",
    "prefix_enforced": true
  }
}
```

### PHP Loading Function
Use `loadDefaultSessionIfMissing()` from `lupo-includes/functions/session_helpers.php` to automatically load and sync defaults during boot/install.

### SQL Seed
Default sessions can be seeded via `database/migrations/seed_default_sessions.sql` during installation.

## Session Validation System
Multi-layered checks:
- **Existence**: DB query for `session_id`.
- **Status**: Verify not 'expired'/'revoked'; check `is_deleted=0`.
- **Age**: `last_seen_ymdhis` < 24h ago (customizable threshold).
- **Security**: Actor/node validation; optional auth token in metadata.

Results: JSON-like output with reason codes; logged for audit.

## System Integration
- **Database**: Aligned to `lupo_sessions.toon.json` (BIGINT timestamps, JSON metadata, indexes on session_id/actor_id/last_seen_ymdhis).
- **Security**: Isolation via IDs/nodes; audit via logs.
- **Monitoring**: Real-time queries (e.g., "SELECT COUNT(*) FROM lupo_sessions WHERE status='active'").
- **Boot Integration**: Called during system agent boot (e.g., validate/create session).
- **Git**: Changes committed with FLARE messages.

## References
- `docs/toons/lupo_sessions.toon.json`
- `bin/session_manager.php`
- `docs/doctrine/FLARE/FLARE_DOCTRINE.md`
- `docs/toons/lupo_channel_logs.toon.json`

---

**Document Created**: 20260301  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.53  
**Status**: ✅ SESSION MANAGEMENT SYSTEM DOCUMENTATION WITH DEFAULT TEMPLATES
