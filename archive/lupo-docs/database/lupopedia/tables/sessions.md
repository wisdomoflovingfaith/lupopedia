---
lupopedia.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/sessions.md"
  file_hash: "6102d95d4e9f738db614bcee8df0640eb7f70ee49128bbed334d0b9a9477f271"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Persistent session storage for authenticated and anonymous actors"
  lupo_agent: "gemini-cli"

lupopedia.edges:
  file_path_from_root: "lupo-docs\database\lupopedia\tables\sessions.md"
  outbound_edges:
- { to: "lupo-docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9, reason: "Binding session to identity" }
    - { to: "lupo-database/lupopedia/toon/lupo_sessions.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/lupopedia_whoami_readme.md", type: "references", weight: 0.9, reason: "Whoami / actor_name identity for this actor_id" }
  semantic_tags: ["sessions", "auth", "state", "anonymous"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_sessions
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: **Session storage** for authenticated and anonymous users: session_id, actor_id (nullable or sentinel for anonymous), payload (session data), optional system_context, and expiry. Anonymous users exist only hereâ€”they do not have rows in lupo_actors. Authenticated users have both a session and an actor row. Replaces Craftyâ€™s livehelp_sessions with a deterministic, actor-aware model. Single session table for the app; no separate â€œsessionsâ€ in current install.

**Schema:** See `lupo-database/lupopedia/toon/lupo_sessions.toon.json`. Primary key: `session_id`. Timestamps BIGINT UTC (e.g. expires_ymdhis). Session data is stored in the payload/lifecycle columns as defined in the TOON.

### 2. Core Workflows

- **Request handling:** Session middleware or auth layer loads session by session_id (e.g. cookie), resolves actor_id, and attaches actor to the request. All session reads/writes go through the Session class (App\Auth\Session) using PDO_DB.
- **Actor binding:** Each session row is tied to an actor_id (and from v4.0.58, **actor_name** / whoami) so permissions and UI can use â€œcurrent actorâ€ without re-looking up from a separate user table.
- **Expiry:** expires_ymdhis (or equivalent in schema) used for cleanup and invalidation. No DB-side DEFAULT CURRENT_TIMESTAMP; set in application code per doctrine.
- **Whoami / actor_name:** For identity and CLI, see [lupopedia_whoami_readme](../../../lupopedia_whoami_readme.md) (actor_name primary for this actor_id).

### 3. Mapping from Crafty Syntax

**Legacy table:** `livehelp_sessions`.

**Migration:** livehelp_sessions is **DROPPED**; there is **no import** of session data. Lupopedia uses lupo_sessions only; structure and lifecycle are new. See `lupo-docs/doctrine/migrations/livehelp_sessions_migration.md`. Session and operator state from Crafty are not migrated; users log in again after upgrade and get new sessions.

---
*Maintained by GEMINI (Actor 1006)*

