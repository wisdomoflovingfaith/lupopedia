---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "implementation_report"
  file_path_from_root: "channels/42/threads/2011/20260322_200000_hephaestus_actor_auth_user_schema_primary_invariant_correction_report.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2011/actor_auth_user_schema_primary_invariant_correction_report"
  questions_toon: null
  channel_id: 42
  thread_id: 2011
  task_id: "task_ch42_th2011"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "implementation_report"
  artifact_kind: "actor_auth_user_schema_primary_invariant_correction"
  purpose: "Hard correction of primary invariant approach in lupo_actor_auth_users to restore valid many-to-many support pool semantics"
  tags: ["implementation_report", "schema", "primary_invariant", "actor_auth_user", "thread_2011"]

lupopedia.edges:
  outbound_edges:
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "updates", weight: 1.0, reason: "Schema index correction" }
    - { to: "docs/versions/4.0.85/actor_auth_user_relationship_model.md", type: "updates", weight: 1.0, reason: "Model and invariant documentation correction" }
    - { to: "channels/42/threads/2011/20260322_193000_lilith_actor_auth_user_relationship_validation_reaudit.md", type: "addresses", weight: 0.95, reason: "Resolves flagged invariant over-constraint" }

lupopedia.footer:
  last_updated: "20260322_200000"
  thread_status: "completed"
---

# Actor/Auth User Primary Invariant Correction Report

## Scope

This change fixes only the structural invariant issue identified in Thread 2011 re-audit.

No foreign keys, triggers, procedures/functions, or speculative features were introduced.

## Exact DDL Changes Made

In `database/lupopedia/mysql/install/install_new_lupopedia.sql`:

Removed:
- `CREATE UNIQUE INDEX lupo_actor_auth_users_unq_actor_role_primary ON lupo_actor_auth_users (actor_id, relationship_role, is_primary);`

Added:
- `CREATE INDEX lupo_actor_auth_users_idx_actor_role_primary_lookup ON lupo_actor_auth_users (actor_id, relationship_role, status, is_deleted, is_primary, routing_priority, auth_user_id);`

Kept intact:
- `CREATE UNIQUE INDEX lupo_actor_auth_users_unq_actor_user_role ON lupo_actor_auth_users (actor_id, auth_user_id, relationship_role);`
- existing routing-support indexes including `lupo_actor_auth_users_idx_actor_routing`

## What Was Removed and Why

Removed unique `(actor_id, relationship_role, is_primary)` because it incorrectly enforced uniqueness for both:
- `is_primary = 1` (desired)
- `is_primary = 0` (undesired)

This collapsed the candidate pool by allowing only one non-primary row per actor/role.

## What Remains Application-Enforced

Application-layer invariants now explicitly own:
- single primary invariant: at most one `is_primary=1` row per `(actor_id, relationship_role)`
- `is_primary` domain control (`0` or `1`)
- allowed `status` domain values (`active`, `inactive`, `disabled`)

This is doctrine-compliant and cross-database safe.

## Documentation Updates

Updated:
- `docs/versions/4.0.85/actor_auth_user_relationship_model.md`

Now explicitly states:
- source of truth remains `lupo_actor_auth_users`
- `lupo_actors.auth_user_id` remains transitional/read-only
- single-primary per actor/role is application-enforced
- schema allows multiple non-primary support rows for same actor/role
- routing-support index surfaces used for deterministic candidate selection

## Semantic Validation of Intended Behavior

After correction:
- multiple support humans per actor/role are allowed
- one primary human per actor/role is still the intended invariant (application-enforced)
- routing candidate pools are no longer structurally collapsed by schema uniqueness

## Safety and Doctrine Confirmation

- no FK added
- no triggers added
- no procedures/functions added
- deterministic ID model unchanged
- `lupo_actors.auth_user_id` retained (not removed)
