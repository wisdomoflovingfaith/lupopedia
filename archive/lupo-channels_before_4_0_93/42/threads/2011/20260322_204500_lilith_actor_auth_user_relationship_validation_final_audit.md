---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "audit"
  file_path_from_root: "lupo-channels/42/threads/2011/20260322_204500_lilith_actor_auth_user_relationship_validation_final_audit.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2011/actor_auth_user_relationship_validation_final_audit"
  questions_toon: null
  channel_id: 42
  thread_id: 2011
  task_id: "task_ch42_th2011"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: "audit"
  artifact_kind: "actor_auth_user_relationship_validation_final"
  purpose: "Final schema re-audit for Thread 2011 after primary invariant hard correction"
  tags: ["audit", "final", "lilith", "schema", "actor_auth_user", "thread_2011"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "audits", weight: 1.0, reason: "Schema authority" }
    - { to: "lupo-docs/versions/4.0.85/actor_auth_user_relationship_model.md", type: "cross_checks", weight: 0.95, reason: "Precedence and invariant contract" }
    - { to: "lupo-channels/42/threads/2011/20260322_193000_lilith_actor_auth_user_relationship_validation_reaudit.md", type: "supersedes", weight: 0.95, reason: "Final closure after hard correction" }
    - { to: "lupo-channels/42/threads/2011/20260322_200000_hephaestus_actor_auth_user_schema_primary_invariant_correction_report.md", type: "validates", weight: 1.0, reason: "Validates claimed correction against install authority" }

lupopedia.footer:
  last_updated: "20260322_204500"
  thread_status: "completed"
---

# Actor/Auth User Relationship Final Validation Audit

## Scope

Final re-audit of `lupo_actor_auth_users` against install authority and corrected model documentation.

## Required Field Outputs

- broken_uniqueness_removed: PASS
- many_to_many_pool_status: PASS
- routing_lookup_index_status: PASS
- uniqueness_status: PASS
- transitional_field_risk: ACCEPTABLE
- routing_readiness: PASS
- doctrine_status: PASS

FINAL:
- system_status: COMPLIANT

## Evidence and Findings

### 1. Broken Uniqueness Removed

Verdict: PASS

Verified in install authority:
- old unique index `(actor_id, relationship_role, is_primary)` is absent.

### 2. Many-to-Many Pool Semantics Restored

Verdict: PASS

Schema now allows:
- multiple non-primary rows per `(actor_id, relationship_role)` because no uniqueness constraint exists on `(actor_id, relationship_role, is_primary)`.
- one `auth_user_id` linked to many actors via relationship rows.
- many `auth_user_id` values linked to one actor via relationship rows.

No structural pool-collapse constraint remains.

### 3. Routing Lookup Index Valid

Verdict: PASS

Verified index exists in install authority:
- `lupo_actor_auth_users_idx_actor_role_primary_lookup`
- columns: `(actor_id, relationship_role, status, is_deleted, is_primary, routing_priority, auth_user_id)`

This matches required lookup and deterministic ordering support.

### 4. Uniqueness Still Correct

Verdict: PASS

Verified unique index remains:
- `(actor_id, auth_user_id, relationship_role)`

Effect:
- duplicate support-role rows for same actor/user/role are blocked.
- role-scoped relationship uniqueness is preserved.

### 5. Transitional Field Risk

Verdict: ACCEPTABLE

Documentation reconfirmed:
- authoritative surface: `lupo_actor_auth_users`
- `lupo_actors.auth_user_id` marked transitional/read-only
- explicit precedence contract and conflict rule documented

No ambiguity remains in documented source-of-truth behavior.

### 6. Routing Readiness

Verdict: PASS

Given documented application-layer enforcement of:
- single primary per `(actor_id, relationship_role)`
- allowed `status` values
- `is_primary` domain (`0` or `1`)

the schema is structurally safe for deterministic routing implementation.

### 7. Doctrine Status

Verdict: PASS

Install authority reconfirmed:
- no foreign keys
- no triggers
- no procedures/functions
- deterministic ID model intact

## Final Compliance Conclusion

All required structural checks pass.

- system_status: COMPLIANT
