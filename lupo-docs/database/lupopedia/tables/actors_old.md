---
lupopedia.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/actors_old.md"
  file_hash: "e0f955efce940e6db039228e1b039ca827362242cabc3b53fc472d8042dcd009"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "documentation"
  purpose: "Legacy/Historical doctrine for lupo_actors"
  lupo_agent: "gemini-cli"

lupopedia.edges:
  file_path_from_root: "lupo-docs\database\lupopedia\tables\actors_old.md"
  outbound_edges:
- { to: "lupo-docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 1.0, reason: "Canonical table documentation" }
  semantic_tags: ["actors", "legacy", "doctrine"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# lupo_actors (Legacy Reference)

**Purpose:** **Unified identity layer** for authenticated humans, AI agents, services, and system users only. Anonymous users do not have rows in lupo_actors; they exist in **lupo_sessions** only. Every authenticated or system entity that can send messages, hold roles, or be referenced in dialogs has one row in `lupo_actors`. Identity is separated from credentials (lupo_auth_users) and from permissions (3-level role system). No dedicated ID range for anonymous users.

**Schema:** See `lupo-database/lupopedia/toon/lupo_actors.toon.json`. Primary key: `actor_id`. Columns include `actor_type` (e.g. agent, service, human, system), `slug`, `name`, `actor_source_id`, `actor_source_type`, `metadata`, lifecycle fields.

---

## Use and need

- **Single identity table:** Channels, threads, messages, roles, and presence refer to `actor_id`. No separate â€œoperatorâ€ or â€œvisitorâ€ table; type and source distinguish them.
- **Human actors:** For imported Crafty users, `actor_id = auth_user_id` and `actor_source_type = 'lupo_auth_users'`. Only operators (isoperator = 'Y') get a lupo_actors row at import; visitors may be created on demand or via other flows.
- **Agents/services:** System and AI agents have `actor_type` = 'agent' or 'service', `actor_source_type` = 'system' or registry; reserved IDs from REGISTRY.
- **Anonymous:** Anonymous visitors do not get rows in lupo_actors. They exist only in lupo_sessions. No anonymous actor range.

---

## Mapping from Crafty Syntax

**Legacy:** `livehelp_users` (operators only get lupo_actors rows; visitors are in lupo_auth_users only and do not get lupo_actors at import). `livehelp_identity_monthly` is not imported into lupo_actors.

**Migration:** See `livehelp_users_migration.md`, `livehelp_identity_migration.md`, `import_from_old_crafty_syntax.sql`.

- **Operators:** Import creates lupo_actors only for rows with `isoperator = 'Y'`. `actor_id = user_id`, `actor_source_id = auth_user_id`, `actor_source_type = 'lupo_auth_users'`. Name/slug from username/displayname.
- **Anonymous:** Not in lupo_actors. Anonymous visitors exist in lupo_sessions only. livehelp_identity_monthly / livehelp_identity_daily are DROPPED with no import into actors.
- **Permissions:** There is no lupo_operators. Staff permissions use **lupo_actor_channel_roles** (captain, administrator, monitor) and **lupo_department_roles**; see `operator_to_roles_migration.md` and `actor_channel_roles.md`.

---
*Maintained by GEMINI (Actor 1006)*

