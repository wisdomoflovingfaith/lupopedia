---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/15_actors.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/15_actors.md"
  last_modified_utc: "20260331180000"
  channel_id: 42
  thread_id: "prd-actors"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Actor identity, inheritance, and personalization model"
  tags:
  - "prd"
  - "actors"
  - "agents"
  - "identity"
  - "department"
  - "personalization"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Actor tables defined in core_identity"
    - to: "lupo-docs/prd/07_agents_faucets.md"
      type: references
      weight: 1.0
      reason: "Actors extend agents"
    - to: "lupo-docs/prd/08_governance_rules.md"
      type: references
      weight: 0.8
      reason: "Department permissions"
lupopedia.footer:
  last_verified: "20260331180000"
  verified_by:
    agent_id: 2
    agent_name_identity: "LILITH"
  orchestrator: "lilith:audit"
---

# PRD: Actor Identity, Inheritance, and Personalization

## Overview

This document defines the canonical model for **actors** in Lupopedia. Actors are department- and persona-specific extensions of agents, providing a personalized, scoped execution and orchestration identity for each user and department context.

**Constitutional Compliance:** All tables referenced in this PRD follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

## Database Tables

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `lupo_actors` | Actor definition and metadata | actor_id, actor_name, actor_type, agent_id |
| `lupo_actor_auth_users` | Actor-auth_user lease and relationship | actor_id, auth_user_id, status, is_primary, routing_priority |
| `lupo_actor_departments` | Department scoping for actors | actor_id, department_id, role_key |
| `lupo_departments` | Department definitions | department_id, name, department_type |
| `lupo_metadata` | Actor personalization data | entity_type='actor', entity_id, property_key, property_value |

## 1. Actor as Department/Persona-Specific Agent Extension

- An **actor** is always created as an extension of a specific agent (referenced via `lupo_actors.agent_id`).
- The actor is aware of the agent it extends (`agent_key`, `agent_id`) and maintains a persistent reference.
- The actor's identity is unique and department/persona-scoped.

## 2. Inheritance and Personalization of Agent Resources

- Actors inherit all modular resources from their agent:
  - `api/`, `assets/`, `components/`, `context/`, `data/`, `hooks/`, `pages/`, `includes/`, `tools/`, `utils/`
- Actors may personalize, override, or extend any inherited resource within their own scope.
- Personalization stored in `lupo_metadata` with `entity_type='actor'` and property-specific keys.
- The actor's resource tree is a superset of the agent's, with actor-specific overrides taking precedence.

## 3. One-Auth_User-at-a-Time Lease Rule

- Only one `auth_user` may extend (lease/control) a given actor at any time.
- The lease is exclusive: no concurrent control or impersonation is permitted.
- Lease state tracked in `lupo_actor_auth_users.status` ('active', 'leased', 'released').

### Lease Enforcement Mechanism

- **Storage**: `lupo_actor_auth_users` table with `status` field
- **Exclusivity**: Application-layer check via `ActorLeaseService::acquire()` before granting lease
- **Audit**: All lease acquisitions/releases logged in `lupo_actor_actions` with `action_type='lease_acquire'` / `'lease_release'`
- **Expiration**: Leases expire after 30 days of inactivity; tracked via `lupo_actor_auth_users.updated_ymdhis`
- **Enforcement**: `ActorLeaseService::acquire()` validates no active lease exists before granting

## 4. Department-Based Personalization and Scoping

- Each actor is further personalized by the department context via `lupo_actor_departments`.
- Department membership determines available features, permissions, and resource overrides.
- Department context is immutable for the lifetime of the actor instance (enforced at application layer).

## 5. Actor Lifecycle

| Stage | Description | Table Actions |
|-------|-------------|---------------|
| Creation | Actor instantiated by auth_user selecting agent and department | INSERT into `lupo_actors`, `lupo_actor_departments` |
| Personalization | Inherit agent resources, apply overrides | INSERT/UPDATE `lupo_metadata` |
| Lease | Actor leased to creating auth_user | INSERT into `lupo_actor_auth_users` with `status='active'` |
| Release | Lease explicitly terminated | UPDATE `lupo_actor_auth_users.status='released'` |
| Termination | Actor archived/deleted | UPDATE `lupo_actors.is_deleted=1`, `deleted_ymdhis` |

## 6. Cross-References

- See also: `01_core_identity.md`, `07_agents_faucets.md`, `08_governance_rules.md`
- Related tables: `lupo_actors`, `lupo_actor_auth_users`, `lupo_actor_departments`, `lupo_metadata`

---

**Status**: DRAFT  
**Constitutional Adherence**: FULL  
**Next Review**: After namespace renumbering
