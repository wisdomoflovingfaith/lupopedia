---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  channel_id: 42
  lupopedia.version: "4.0.79"
  file_path_from_root: "lupo-docs/status/CURSOR_LILITH_CHANNEL_MAPPING_VERIFICATION_4_0_79.md"
  artifact_type: "status"
  artifact_kind: "verification_report"
  purpose: "Channel and actor mapping verification for Cursor + Lilith (4.0.79)"
  tags: ["channel_mapping", "cursor", "lilith", "4.0.79", "verification"]
---

# Cursor + Lilith Channel Mapping Verification (4.0.79)

**Workstream 2 deliverable.** Source: [CURSOR_LILITH_CHANNEL_42_LUPOPEDIA_4_0_79_TASKS.md](CURSOR_LILITH_CHANNEL_42_LUPOPEDIA_4_0_79_TASKS.md).

## Artifacts inspected

- `lupo-database/lupopedia/toon/lupo_channels.toon` — channel_id, channel_key, channel_name, project_id, is_kernel, etc. Unique on channel_id.
- `lupo-database/lupopedia/toon/lupo_actor_channels.toon` — actor_channel_id, actor_id, channel_id, status (e.g. 'A'), is_deleted. Unique on (actor_id, channel_id).
- `lupo-database/lupopedia/toon/lupo_actor_channel_roles.toon` — actor_channel_role_id, actor_id, channel_id, role_key, is_deleted. No unique on (actor_id, channel_id); multiple roles per actor per channel allowed (OR semantics).

## Channel/membership findings

1. **Channel 42** exists in install/seed as the Lupopedia development workspace. Cursor (actor_id 102) and Lilith (actor_id 2) are both present in install seed as members of channel 42 (lupo_actor_channels rows in install_new_lupopedia.sql).
2. **Membership model** is valid for multi-agent coexistence: each actor has a row in lupo_actor_channels per channel; status and is_deleted allow active/inactive and soft delete. Multiple actors can be members of the same channel without conflict.
3. **Role assignment:** lupo_actor_channel_roles allows multiple rows per (actor_id, channel_id). The system treats roles as OR semantics (any valid role in channel is sufficient). Cursor and Lilith can have different role_key values (e.g. orchestrator vs critic) on the same channel.

## Faucet/routing findings

- **lupo_agent_faucets** maps actors to execution surfaces (e.g. cursor, lilith-flame). Faucet slug and channel are independent: a faucet is not bound to a single channel; channel context comes from session and membership. No routing assumption was found that would cause cross-agent interference.
- **Channel routing:** The channels controller and API use channel_id from the URL or route; actor identity comes from session. No header or routing assumption was found that would allow one agent to impersonate another when the security fixes (session-only actor, membership check) are in place.

## Fixes made

- None required in this verification pass. Schema and existing seed support Cursor and Lilith on channel 42; the security fixes in channels-api.php (session actor, membership check) are the enforcement layer. Seed for Lilith critic role was added in a prior 4.0.79 pass (seed_lilith_channel_42_critic_role_4.0.79.sql).

## Doctrinal ambiguity remaining

- None. Role keys (captain, orchestrator, critic, monitor, etc.) are documented as data-driven conventions in lupo_actor_channel_roles.role_key; the schema does not enforce an enum, which is intentional.
