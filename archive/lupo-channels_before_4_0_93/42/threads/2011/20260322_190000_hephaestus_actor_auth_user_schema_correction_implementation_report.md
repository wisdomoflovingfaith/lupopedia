---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "implementation_report"
  file_path_from_root: "lupo-channels/42/threads/2011/20260322_190000_hephaestus_actor_auth_user_schema_correction_implementation_report.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2011/actor_auth_user_schema_correction_implementation_report"
  questions_toon: null
  channel_id: 42
  thread_id: 2011
  task_id: "task_ch42_th2011"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "implementation_report"
  artifact_kind: "actor_auth_user_schema_correction"
  purpose: "Corrective implementation report for lupo_actor_auth_users compliance fixes after Lilith non-compliant audit"
  tags: ["implementation_report", "schema_correction", "actors", "auth_users", "thread_2011"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "implements", weight: 1.0, reason: "Install SQL corrections applied" }
    - { to: "lupo-docs/versions/4.0.85/actor_auth_user_relationship_model.md", type: "updates", weight: 0.9, reason: "Precedence and enforcement documentation updated" }
    - { to: "lupo-channels/42/threads/2011/20260322_184000_lilith_actor_auth_user_relationship_validation_audit.md", type: "addresses", weight: 1.0, reason: "Corrective action response" }

lupopedia.footer:
  last_updated: "20260322_190000"
  thread_status: "completed"
---

# Thread 2011 Corrective Implementation Report

## Summary

Corrective schema changes were applied to address the non-compliant findings for `lupo_actor_auth_users`.

Scope:
- corrections only
- no removals of legacy column
- no new feature expansion

## 1. Indexes Added

Install SQL updated in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` with:

1. Routing index:
- `lupo_actor_auth_users_idx_actor_routing`
- columns: `(actor_id, status, is_deleted, relationship_role, is_primary, routing_priority, auth_user_id)`

2. Primary invariant support index:
- `lupo_actor_auth_users_unq_actor_role_primary`
- columns: `(actor_id, relationship_role, is_primary)`

3. Status index:
- `lupo_actor_auth_users_idx_status`
- columns: `(status)`

## 2. Invariants and Domain Controls Enforced

Applied as required:

- Primary invariant support: unique index on `(actor_id, relationship_role, is_primary)`
- `is_primary` constraint expectation documented as application-enforced 0/1
- status domain documented as application-enforced values:
  - `active`
  - `inactive`
  - `disabled`
- routing priority safety documented as application-enforced `routing_priority >= 0`

No forbidden DB-level constraints were added.

## 3. Transitional Precedence Rule Updated

Documentation updated in `lupo-docs/versions/4.0.85/actor_auth_user_relationship_model.md`:

- authoritative source: `lupo_actor_auth_users`
- legacy field: `lupo_actors.auth_user_id` is read-only transitional
- explicit conflict warning and deprecation path added

Legacy field retained as required:
- `lupo_actors.auth_user_id` was not removed

## 4. Validation Results

Validation checklist:

- routing query index support: PASS
  - actor routing filter and ordering path now has dedicated index coverage

- ambiguous primary state allowed: PASS (corrected)
  - unique invariant support now prevents multiple primary rows for same actor and role under the enforced model

- doctrine compliance: PASS
  - no foreign keys
  - no triggers
  - no procedures/functions
  - BIGINT UTC lifecycle model preserved
  - deterministic ID model preserved

## 5. Outcome

Corrective requirements were implemented in install SQL and versioned docs.

Target state achieved:
- non-compliant findings addressed at schema/documentation correction layer
- routing implementation can proceed against corrected index and precedence rules
