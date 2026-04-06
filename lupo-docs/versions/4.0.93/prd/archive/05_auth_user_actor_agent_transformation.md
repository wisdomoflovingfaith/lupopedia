---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  file_path_from_root: /lupo-docs/versions/4.0.93/prd/05_auth_user_actor_agent_transformation.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/prd/05_auth_user_actor_agent_transformation.md
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "auth-user-actor-transformation"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "auth_model"
  purpose: "PRD for Auth User → Actor → Agent Transformation v4.0.93"
  tags:
  - "prd"
  - "auth_model"
  - "identity"
  - "transformation"
  - "v4.0.93"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-database/lupopedia/json/lupo_auth_users.json"
      type: references
      weight: 1.0
      reason: Table definition for authentication users
    - to: "lupo-database/lupopedia/json/lupo_actors.json"
      type: references
      weight: 1.0
      reason: Table definition for actors
    - to: "lupo-database/lupopedia/json/lupo_actor_instances.json"
      type: references
      weight: 1.0
      reason: Table definition for actor instances
    - to: "lupo-database/lupopedia/json/lupo_actor_templates.json"
      type: references
      weight: 1.0
      reason: Table definition for actor templates
    - to: "lupo-database/lupopedia/json/lupo_actor_lease_sessions.json"
      type: references
      weight: 1.0
      reason: Table definition for lease sessions
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: Root constitutional system requirements
        actor_id: 102
        actor_name: cursor
        event_date: '20260330'
        description: Initial PRD for agent→actor→auth_user transformation, leasing, and permission rules
        faucet_slug: cursor
    - event_type: update
        actor_id: 102
        actor_name: cursor
        event_date: '20260330'
        description: Added canonical lupopedia.headers, removed header_format_version, and added revision history per doctrine
        faucet_slug: cursor
lupopedia.footer:
    last_verified: '20260330'
    verified_by:
        actor_id: 102
        agent_name_identity: Cursor IDE Agent

## Core Doctrine

## Permission Rule (Canonical)
**ACTOR USAGE PERMISSION RULE**
An auth_user may lease or control an actor only if one of the following is true:
- `auth_user_id == actor.created_by_auth_user_id` (creator always has access)
- `auth_user.department_id == 0` (root department has universal access)
- `auth_user.department_id == actor.department_id` (department-scoped access)
If none of these conditions are met, the actor is not leasable by that user.

## Database Requirements
- **lupo_actor_instances**: Add `created_by_auth_user_id BIGINT` and `department_id BIGINT`.
- **lupo_actor_lease_sessions**: Must include `auth_user_id`, `actor_id`, and `department_id` (copied from actor at lease time).
- All IDs via IdGenerator::generate(), all timestamps BIGINT YYYYMMDDHHIISS, no AUTO_INCREMENT, no FOREIGN KEYS, no UNSIGNED.

## Leasing Logic (PHP 7.4+)
```php
function canUserLeaseActor($authUser, $actor) {
    if ($authUser->auth_user_id == $actor->created_by_auth_user_id) return true;
    if ($authUser->department_id == 0) return true;
    if ($authUser->department_id == $actor->department_id) return true;
    return false;
}
```

## Workflow: Agent → Actor → Auth User (Leased)
1. Agent (template) is defined (autonomous, not tied to actors or users)
2. Actor instance is created from agent (inherits capabilities, department-scoped)
3. Department assigns actor to pool
4. Auth user requests lease; permission rule is checked
5. Lease session is created if permitted
6. Lease session controls operational identity, permissions, and audit
7. Lease ends; actor becomes available for next user

## Why This Model?
- Enables human/AI co-training, multi-user actor evolution, department-scoped access, agent autonomy, actor specialization, clean audit trails, and future multi-agent orchestration.
- Avoids identity drift and legacy mapping issues.

## Explicit Notes for IDE Agents
- Agents = autonomous AI entities
- Actors = hybrid shells derived from agents
- Auth Users = temporary controllers of actors
- Permission rule above is canonical and must be enforced in all leasing logic.

---
