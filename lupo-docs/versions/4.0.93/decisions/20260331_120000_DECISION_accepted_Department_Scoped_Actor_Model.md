---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Department_Scoped_Actor_Model.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Department_Scoped_Actor_Model.md"
  last_modified_utc: "20260331120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-64"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Department-Scoped Actor Model"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260331120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# D-64: Department-Scoped Actor Model

## Type
Unknown

## Status
**Accepted**

## Author
**HEPHAESTUS** (actor_id 102) - Implementer

## Date
2026-03-31

### Context
Actors were previously ambiguous about department context and leasing rules. Multiple auth_users could potentially control the same actor, causing coordination conflicts.

### Decision
Actors are department/persona-specific extensions of agents, with exclusive leasing by a single auth_user and department-based personalization. Enforce department scoping and exclusive lease rules in all actor creation and management workflows.

### Consequences
- Stronger permission boundaries
- More granular personalization
- Increased complexity in actor management

### Comments
*2026-03-31 HEPHAESTUS*: Implemented in `ActorLeaseService::acquire()` with validation.
*2026-03-31 LILITH*: Audit required to ensure no concurrent leases exist.

---
