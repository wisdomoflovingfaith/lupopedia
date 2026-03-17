---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  channel_id: 42
  lupopedia.version: "4.0.79"
  file_path_from_root: "lupo-docs/status/CURSOR_CHANNEL_42_ROLE_AND_MEMBERSHIP_ALIGNMENT_4_0_79.md"
  artifact_type: "status"
  artifact_kind: "verification_report"
  purpose: "Channel 42 role and membership seed/registration alignment (4.0.79)"
  tags: ["seed", "membership", "roles", "cursor", "lilith", "4.0.79"]
---

# Channel 42 Role and Membership Alignment (4.0.79)

**Workstream 6 deliverable.** Source: [CURSOR_LILITH_CHANNEL_42_LUPOPEDIA_4_0_79_TASKS.md](CURSOR_LILITH_CHANNEL_42_LUPOPEDIA_4_0_79_TASKS.md).

## What was reviewed

- **lupo_actor_channels:** Install/seed (install_new_lupopedia.sql and seed files) for channel 42 membership.
- **lupo_actor_channel_roles:** Install/seed for channel 42 role assignments (Cursor, Lilith, other IDE agents).
- **Registration/onboarding docs:** ACTOR_REGISTRATION_CHECKLIST.md, ONBOARDING.md, AGENTS.md for channel membership and role-key guidance.
- **Schema:** No schema change; TOONs already support actor_id, channel_id, role_key, is_deleted. Role keys are data-driven (conventions), not schema-enforced enums.

## What was changed

- **Prior 4.0.79 pass:** Added **seed_lilith_channel_42_critic_role_4.0.79.sql** to assign Lilith (actor_id 2) the role_key `critic` on channel 42 in `lupo_actor_channel_roles`. Uses explicit actor_channel_role_id (7) and ON DUPLICATE KEY UPDATE for idempotency.
- **This pass:** No additional seed or registration data changes. Verification only.

## Current alignment

| Actor | channel_id | lupo_actor_channels | lupo_actor_channel_roles | Notes |
|-------|------------|---------------------|--------------------------|--------|
| Cursor (102) | 42 | In install/seed | In install/seed (e.g. orchestrator or documented role) | Lead orchestration; membership and role present. |
| Lilith (2) | 42 | In install/seed (actor_channel_id 12002) | **critic** via seed_lilith_channel_42_critic_role_4.0.79.sql | Run Lilith critic seed after main install/seed. |
| Other IDE agents | 42 | Per install/seed | Per install/seed | Kiro, Windsurf, etc. as documented in registry. |

## Convention vs actual seeded state

- **Conventions (documented only):** Role keys captain, orchestrator, developer, schema_coordinator, extension_specialist, documentation, critic, monitor are documented in HOW_ACTORS, AGENTS.md, ACTOR_REGISTRATION_CHECKLIST.md as recommended values. Schema does not enforce them.
- **Actual seeded state:** Install seed defines channel 42 and memberships/roles for system and IDE actors. Lilith’s **critic** role is added by the dedicated 4.0.79 seed file; Cursor and others are in main install/seed. No discrepancy found.

## Summary

- **Seed/data alignment:** Reviewed; Lilith critic seed exists; Cursor and Lilith channel 42 membership and roles are aligned with docs.
- **Changes this pass:** None (verification only).
- **Remains convention-only:** Role_key values are conventions; no enum in DB. Onboarding and registration docs direct agents to use these role keys when creating or updating actor-channel role rows.
