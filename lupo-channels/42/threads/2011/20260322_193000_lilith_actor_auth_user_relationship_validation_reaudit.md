---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "audit"
  file_path_from_root: "lupo-channels/42/threads/2011/20260322_193000_lilith_actor_auth_user_relationship_validation_reaudit.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2011/actor_auth_user_relationship_validation_reaudit"
  last_modified_utc: "20260322_193000"
  channel_id: 42
  thread_id: 2011
  task_id: "task_ch42_th2011"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: "audit"
  artifact_kind: "actor_auth_user_relationship_validation"
  purpose: "Re-audit of corrective schema changes for lupo_actor_auth_users to verify prior NON_COMPLIANT findings are fully resolved"
  tags: ["audit", "reaudit", "lilith", "schema", "actor_auth_user", "thread_2011"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "audits", weight: 1.0, reason: "Schema authority" }
    - { to: "lupo-docs/versions/4.0.85/actor_auth_user_relationship_model.md", type: "cross_checks", weight: 0.9, reason: "Precedence and routing model documentation" }
    - { to: "lupo-channels/42/threads/2011/20260322_184000_lilith_actor_auth_user_relationship_validation_audit.md", type: "follows", weight: 0.9, reason: "Prior NON_COMPLIANT audit baseline" }

lupopedia.footer:
  last_updated: "20260322_193000"
  thread_status: "completed"
---

# Actor/Auth User Relationship Validation Re-Audit

## Scope

Re-audit of `lupo_actor_auth_users` corrective implementation in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`.

## Required Field Outputs

- index_status: PASS
- primary_invariant_status: FAIL
- transitional_field_risk: ACCEPTABLE
- routing_readiness: FAIL
- doctrine_status: PASS

FINAL:
- system_status: NON_COMPLIANT

## Evidence and Findings

### 1. Index Validity

Verdict: PASS

Evidence:
- Routing index exists:
  - `lupo_actor_auth_users_idx_actor_routing`
  - `(actor_id, status, is_deleted, relationship_role, is_primary, routing_priority, auth_user_id)`

This satisfies required filter and ordering coverage:
- filters: `actor_id`, `status`, `is_deleted`, `relationship_role`
- order support columns included: `is_primary`, `routing_priority`, `auth_user_id`

### 2. Primary Invariant

Verdict: FAIL

Evidence:
- Unique constraint defined as:
  - `lupo_actor_auth_users_unq_actor_role_primary`
  - `(actor_id, relationship_role, is_primary)`

Audit determination:
- this constraint does prevent duplicate `is_primary=1` rows for one `(actor_id, relationship_role)`
- but it also prevents multiple `is_primary=0` rows for that same `(actor_id, relationship_role)`
- result: relationship pool is incorrectly restricted and many-to-many support rows become structurally over-constrained

Therefore the invariant implementation is not safe for intended support-pool semantics and leaves correctness risk for routing populations.

### 3. Transitional Field Risk

Verdict: ACCEPTABLE

Verified in model documentation:
- precedence rule is explicit: `lupo_actor_auth_users` is authoritative
- `lupo_actors.auth_user_id` is documented as transitional/read-only legacy field
- conflict handling rule is explicitly documented

No unresolved source-of-truth ambiguity remains in documentation contract.

### 4. Routing Readiness

Verdict: FAIL

Reason:
- deterministic routing requires a valid candidate pool per `(actor_id, relationship_role)`
- current unique primary constraint collapses non-primary pool cardinality
- this is a structural limitation, not only an optimization concern

Conclusion:
- routing structure is not yet safe for implementation despite improved indexing

### 5. Doctrine Compliance

Verdict: PASS

Reconfirmed in install authority:
- no foreign keys
- no triggers
- no procedures/functions
- deterministic ID model intact (`actor_auth_user_id` is application supplied)

## Compliance Conclusion

Because at least one required check failed, strict rule applies:

- system_status: NON_COMPLIANT

## Required Next Correction

Replace the current primary uniqueness approach so schema/application rules enforce:
- at most one primary (`is_primary=1`) per `(actor_id, relationship_role)`
- unlimited non-primary support rows for same `(actor_id, relationship_role)` subject to `(actor_id, auth_user_id, relationship_role)` uniqueness
