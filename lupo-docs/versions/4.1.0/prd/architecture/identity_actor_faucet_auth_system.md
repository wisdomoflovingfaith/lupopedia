---
lupopedia.headers:
  lupopedia.schema: "architecture"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/architecture/identity_actor_faucet_auth_system.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "identity_architecture"
  purpose: "Define 4.1.0 identity model across auth_users, actors, agents, departments, and faucets"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/ACTOR_AGENT_AUTH_USER_MODEL.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/EFFECTIVE_ACTOR_RESOLUTION.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agents.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agent_faucets.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actor_departments.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_departments.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actor_channel_roles.md", type: "references", weight: 0.9 }

lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "pending"
  approved_by_actor_id: 0
  approved_utc: 0
  next_action:
    - "Validate model against runtime actor resolution code paths"
---

# Identity System: Actors, Departments, Faucets, Agents, Auth Users

## Purpose

Define how identity and execution context work in 4.1.0 with no ambiguity.

## Canonical Layer Model

1. Auth User: authentication principal and login surface.
2. Actor: operational identity used for attribution and permissions.
3. Department: organizational authority context.
4. Agent: capability/runtime configuration.
5. Faucet: execution interface (IDE/API/web) through which agent actions occur.

## Resolution Rules

1. All operational writes are attributed to actor context.
2. Client-provided actor identifiers are not trusted for write identity.
3. Effective permissions resolve by precedence:
   - channel role
   - department role
   - system role
4. Faucet does not grant authority by itself.
5. Agent configuration does not override actor attribution.

## 4.1.0 Stability Requirements

- Identity layers must stay separated in doctrine and implementation.
- Actor resolution paths must be deterministic and testable.
- Auth user to actor mapping must be explicit and auditable.
- Department context must remain enforceable in permission checks.

## Acceptance Evidence Needed

To move this artifact to approved:

- Runtime tests proving actor attribution cannot be spoofed.
- Permission tests proving precedence resolution is correct.
- Evidence that faucet and agent metadata never replace actor write identity.
