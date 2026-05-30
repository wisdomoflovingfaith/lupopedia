---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "audit"
  file_path_from_root: "lupo-channels/42/threads/2011/20260322_184000_lilith_actor_auth_user_relationship_validation_audit.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2011/actor_auth_user_relationship_validation_audit"
  questions_toon: null
  channel_id: 42
  thread_id: 2011
  task_id: "task_ch42_th2011"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: "audit"
  artifact_kind: "actor_auth_user_relationship_validation"
  purpose: "Destructive schema audit of lupo_actor_auth_users for structural correctness, doctrine compliance, and routing safety"
  tags: ["audit", "lilith", "schema", "actor_auth_user", "validation", "thread_2011"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "audits", weight: 1.0, reason: "Install SQL is schema authority" }
    - { to: "lupo-docs/versions/4.0.85/actor_auth_user_relationship_model.md", type: "cross_checks", weight: 0.8, reason: "Compares implemented schema to model expectations" }

lupopedia.footer:
  last_updated: "20260322_184000"
  thread_status: "completed"
---

# Actor/Auth User Relationship Validation Audit

## Scope

Destructive schema audit of implemented `lupo_actor_auth_users` in `install_new_lupopedia.sql`.

Audit verifies:
- structure validity
- many-to-many correctness
- index validity
- doctrine compliance
- transitional-field risk
- routing readiness

---

## Evidence

Primary DDL evidence:
- `CREATE TABLE lupo_actor_auth_users` at install SQL line ~161
- unique index `(actor_id, auth_user_id, relationship_role)`
- indexes:
  - `(auth_user_id, status)`
  - `(actor_id, status, is_primary, routing_priority)`

Transitional overlap evidence:
- conditional extension for `lupo_actors.auth_user_id` in install SQL line ~4178+

Doctrine prohibition scan:
- no `FOREIGN KEY`
- no `CREATE TRIGGER`
- no `CREATE PROCEDURE`
- no `CREATE FUNCTION`

---

## Required Output Fields

- structure_status: PASS
- many_to_many_status: PASS
- index_status: FAIL
- doctrine_status: PASS
- transitional_field_risk: NOT_ACCEPTABLE
- routing_readiness: PARTIAL

FINAL:
- system_status: NON_COMPLIANT

---

## Detailed Findings

### 1. TABLE STRUCTURE VALIDITY

Verdict: PASS

Checks:
- required fields exist with expected names and types
- lifecycle fields present and doctrine-compatible:
  - `created_ymdhis BIGINT`
  - `updated_ymdhis BIGINT`
  - `deleted_ymdhis BIGINT`
  - soft-delete flags present
- deterministic key model preserved (`actor_auth_user_id` supplied by application)

No missing mandatory column detected.

### 2. MANY-TO-MANY CORRECTNESS

Verdict: PASS

Schema supports:
- multiple auth users per actor (multiple rows with same `actor_id`)
- multiple actors per auth user (multiple rows with same `auth_user_id`)

No hidden 1:1 constraint exists.

### 3. INDEX VALIDITY

Verdict: FAIL

Positive:
- uniqueness guard prevents duplicate `(actor_id, auth_user_id, relationship_role)` entries.

Critical gaps:
1. No index optimized for role-filtered routing path:
   - common query shape uses `actor_id + status + relationship_role + is_deleted` with order by `is_primary, routing_priority, auth_user_id`.
   - current indexes do not include `relationship_role` or `is_deleted` for this path.
2. No explicit actor-leading soft-delete-aware index for active routing pool scans.
3. Current unique index does not prevent multiple primary users for same actor/role.

Result:
- index set is insufficient for safe deterministic routing at scale and does not fully enforce intended selection invariants.

### 4. DOCTRINE COMPLIANCE

Verdict: PASS

Verified:
- no FK
- no trigger
- no stored procedure/function
- BIGINT UTC lifecycle fields
- deterministic IDs expected from application layer

### 5. TRANSITIONAL FIELD RISK (`lupo_actors.auth_user_id`)

Verdict: NOT_ACCEPTABLE

Risk identified:
- dual mapping surfaces now exist:
  1. canonical many-to-many table `lupo_actor_auth_users`
  2. transitional single-column `lupo_actors.auth_user_id`

Without strict precedence enforcement, routing or tooling may read old single-column mapping and produce inconsistent targeting.

Failure mode:
- actor linked to support pool in join table but stale `lupo_actors.auth_user_id` points elsewhere.

This is an active ambiguity risk until precedence and deprecation guardrails are enforced.

### 6. ROUTING READINESS

Verdict: PARTIAL

Supported now:
- primary flag present
- priority field present
- role field present
- status field present

Not fully safe yet:
- no schema-level guard against multiple simultaneous primaries per actor/role
- no constrained status domain (free-form status text)
- no index profile aligned to role-filtered + active-only routing path

Routing can be built, but reliability depends on strict application-level controls not currently guaranteed by schema constraints/indexing.

### 7. EDGE CASE CHECKS

- duplicate role entries: blocked by unique index -> PASS
- conflicting `is_primary` flags: possible -> FAIL
- invalid `routing_priority` values: unconstrained (e.g., negative/overflow semantics) -> FAIL
- status filtering behavior: free-form status values, no controlled domain -> FAIL

---

## Compliance Conclusion

Because structural/index/invariant flaws remain, this audit classifies implementation as:

- system_status: NON_COMPLIANT

Per strict rule, any structural flaw yields NON_COMPLIANT.

---

## Required Corrective Actions (Schema-Level)

1. Add invariant support for primary uniqueness per actor and role (required).
2. Add routing-path index including role and active/deleted filters (required).
3. Define controlled status domain strategy compatible with cross-DB doctrine (required).
4. Enforce precedence contract: `lupo_actor_auth_users` authoritative, `lupo_actors.auth_user_id` transitional-read-only (required).
