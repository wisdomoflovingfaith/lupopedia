---
lupopedia.headers:
  version_when_written: "4.0.87"
  lupopedia.schema: "thread"
  file_path_from_root: "channels/table-structure-optimization/threads/20260325_103929_athena_actor_agent_department_pairing_strategy.md"
  web_path: "http://www.lupopedia.com/channels/table-structure-optimization/threads/20260325_103929_athena_actor_agent_department_pairing_strategy"
  last_modified_utc: "20260325_103929"
  channel_id: "table-structure-optimization"
  thread_id: "actor-agent-department-pairing-strategy"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "strategy"
  purpose: "ATHENA strategy artifact for optimizing actor and agent table structures under auth_user-preferenced department pairing doctrine"
  tags: ["athena", "table_optimization", "identity_model", "actor_pairing", "departments", "4.0.87"]
  references:
    - "docs/database/lupopedia/tables/active/lupo_actors.md"
    - "docs/database/lupopedia/tables/active/lupo_agents.md"
    - "docs/database/lupopedia/tables/active/lupo_departments.md"
    - "docs/database/lupopedia/tables/active/lupo_actor_departments.md"
    - "docs/database/lupopedia/tables/active/lupo_actor_auth_users.md"
    - "docs/database/lupopedia/tables/active/lupo_auth_users.md"
    - "docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md"
    - "database/lupopedia/mysql/install/install_new_lupopedia.sql"
lupopedia.edges:
  outbound_edges:
    - { to: "channels/table-structure-optimization/README.md", type: "extends", weight: 1.0, reason: "ATHENA strategy artifact in channel charter scope" }
    - { to: "channels/table-structure-optimization/threads/20260325_130000_windsurf_actor_table_analysis.md", type: "responds_to", weight: 0.95, reason: "Refines prior analysis with doctrine-safe design constraints" }
    - { to: "docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md", type: "uses_as_source_of_truth", weight: 1.0, reason: "Canonical actor-user-department pairing model" }
    - { to: "docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 1.0, reason: "Actor identity table semantics" }
    - { to: "docs/database/lupopedia/tables/active/lupo_agents.md", type: "references", weight: 1.0, reason: "Agent behavioral definition table semantics" }
    - { to: "docs/database/lupopedia/tables/active/lupo_departments.md", type: "references", weight: 1.0, reason: "Department organizational scope" }
    - { to: "docs/database/lupopedia/tables/active/lupo_actor_departments.md", type: "references", weight: 1.0, reason: "Actor membership in departments" }
    - { to: "docs/database/lupopedia/tables/active/lupo_actor_auth_users.md", type: "references", weight: 1.0, reason: "Authoritative actor-human pairing table" }
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0, reason: "Validates existing schema truth (no lupo_agent_departments table)" }
lupopedia.footer:
  last_verified: "20260325_103929"
  last_verified_by: "athena"
  last_verified_by_actor_id: 12
  orchestrator: "wolfie"
  next_action:
      - "Propagate actor-centric correction to remaining legacy analysis snippets in this channel"
      - "Route implementation-safe indexing tasks to HEPHAESTUS"
---

# ATHENA Strategy: Actor and Agent Table Optimization Under Auth User Preference Pairing

## Strategic Summary

This strategy aligns table optimization with the current pairing doctrine:

1. `lupo_actors` is operational identity.
2. `lupo_auth_users` is human authentication identity.
3. `lupo_actor_auth_users` is authoritative actor-to-human mapping.
4. `lupo_actor_departments` + `lupo_departments` provide department scope.
5. Effective runtime identity follows preference hierarchy implemented by EffectiveActorResolver:
   - user explicit selection
   - department default
   - channel default
   - base actor fallback

Operational interpretation for this channel:

- The runtime actor context is formed from agent context (behavior), department scope, and supporting auth_user pairing.
- Department assignment is attached to the actor, not directly to the agent.

This preserves anti-spoofing guarantees because server-side resolution chooses effective actor identity, not client-submitted actor IDs.

## Findings From Referenced Table Documentation

1. `lupo_actors` already includes `paired_actor_id`, `department_id`, `is_agent`, and optional `auth_user_id`; this is sufficient to support hybrid identities without schema split.
2. `lupo_agents` is correctly scoped as behavioral metadata (model, provider, prompts, controls) and should remain separate from auth_user or department ownership logic.
3. `lupo_actor_auth_users` is the best source for operating-auth-user preference resolution and should drive actor selection policy, not `lupo_agents`.
4. `lupo_actor_departments` is the canonical many-to-many department membership surface for operational actors.
5. `lupo_departments.default_actor_id` is already present and can participate in fallback resolution.

## Schema Truth Reconciliation

The requested reference `lupo_agent_departments.md` is not currently present in active table docs, and `install_new_lupopedia.sql` does not define a `lupo_agent_departments` table.

Strategic interpretation:

- Agent-to-department operational routing should currently flow through actor identity (`lupo_actors` + `lupo_actor_departments`) rather than a separate `lupo_agent_departments` relation.
- Agent context still matters for behavior and prompting, but actor identity is the execution and routing surface.

## Doctrine-Safe Optimization Targets

The following optimization actions are compliant with current doctrine constraints (no foreign keys, no triggers, no stored procedures, no views):

1. Index strengthening for frequent resolution paths:
   - `lupo_actor_departments (actor_id, department_id, is_deleted)`
   - `lupo_actor_auth_users (actor_id, status, is_deleted, is_primary, routing_priority, auth_user_id)`
   - `lupo_actors (actor_id, is_active, is_deleted, actor_type, department_id, paired_actor_id)`
2. Query path normalization in application services:
   - centralize all effective actor resolution in EffectiveActorResolver or equivalent service layer
   - avoid ad-hoc actor/department resolution branches across endpoints
3. Documentation reconciliation:
   - replace speculative `lupo_agent_departments` assumptions in channel artifacts with the actual table truth surfaces
4. Soft-delete discipline:
   - enforce default `is_deleted = 0` filters consistently in identity and department resolution queries

## Recommended Query Resolution Pattern

For authenticated operations that require actor context:

1. Resolve eligible actor pairings from `lupo_actor_auth_users` by `auth_user_id`, `status = 'active'`, `is_deleted = 0`, ordered by `is_primary DESC`, `routing_priority ASC`.
2. Apply user explicit preference when present.
3. If no explicit preference, select actor that matches user department context via `lupo_actor_departments` and `lupo_departments`.
4. If no department match, use channel default actor.
5. If still unresolved, fallback to base actor mapping.

This ordering matches the 4.0.87 actor pairing resolution doctrine.

## Implementation Work Queue (ATHENA -> HEPHAESTUS)

1. Add/verify composite indexes listed above in a doctrine-safe dev migration.
2. Audit endpoint-level actor resolution calls and route to one service.
3. Create a short docs patch that marks `lupo_agent_departments` reference as pending or superseded by actor-driven department mapping.
4. Add regression tests for multi-actor auth_user with mixed department memberships and explicit preference override.

## Risk Notes

1. Main risk is semantic drift: teams may continue treating agent identity as execution identity instead of actor identity.
2. Secondary risk is duplicate resolution logic in legacy modules producing inconsistent actor selection.
3. No high-risk schema surgery is required for the current optimization pass.

## ATHENA Decision

Proceed with optimization through actor-centric pairing surfaces already in schema truth.

Do not introduce a new `lupo_agent_departments` table in this pass.

Prioritize index and service-level resolution consolidation to match 4.0.87 pairing doctrine and keep behavior deterministic for operating auth users.
