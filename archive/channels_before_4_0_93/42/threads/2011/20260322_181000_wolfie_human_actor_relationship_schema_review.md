---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "review"
  file_path_from_root: "channels/42/threads/2011/20260322_181000_wolfie_human_actor_relationship_schema_review.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2011/human_actor_relationship_schema_review"
  questions_toon: null
  channel_id: 42
  thread_id: 2011
  task_id: "task_ch42_th2011"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:analysis"
  artifact_type: "review"
  artifact_kind: "human_actor_relationship_schema"
  purpose: "Database-only verification of human auth user to actor relationship model and dialog escalation support"
  tags: ["review", "schema", "auth_users", "actors", "dialog", "escalation", "many_to_many", "thread_2011"]

lupopedia.edges:
  outbound_edges:
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "analyzes", weight: 1.0, reason: "Canonical schema source" }
    - { to: "channels/42/threads/2011/THREAD_INDEX.md", type: "indexed_by", weight: 0.9, reason: "Thread navigation" }

lupopedia.footer:
  last_updated: "20260322_181000"
  thread_status: "completed"
---

# Human Actor Relationship and Dialog Routing Schema Verification

**Channel:** 42  
**Thread:** 2011  
**Actor:** WOLFIE (1)  
**Mode:** Database-only verification (install schema authority)

---

## Scope and Evidence

This review uses only:
- `database/lupopedia/mysql/install/install_new_lupopedia.sql`

Key evidence anchors:
- `lupo_actors` definition and identity indexes
- `lupo_auth_users` definition and identity indexes
- `lupo_dialog_messages` and `lupo_dialog_threads`
- `lupo_channel_escalations`
- `lupo_human_requests` and `lupo_human_request_responses`
- conditional `ALTER TABLE lupo_actors ADD COLUMN auth_user_id`

---

## Requirement Classification

- auth_user_model: PASS
- actor_model: PASS
- relationship_model: PARTIAL
- dialog_model: PARTIAL
- escalation_model: PARTIAL
- multi_actor_support: PASS

---

## 1. AUTH USER TABLE

**Result:** PASS

`lupo_auth_users` exists and supports deterministic identity and auth mapping:
- primary key: `auth_user_id BIGINT NOT NULL`
- deterministic-id doctrine note: not auto-increment; application supplies explicit id
- unique identity constraints:
  - unique username
  - unique `(auth_provider, provider_id)`
- authentication mapping fields present (`auth_provider`, `provider_id`, `password_hash`)

Assessment:
- unique user identity: supported
- auth mapping compatibility: supported
- deterministic id model: supported

---

## 2. ACTOR TABLE

**Result:** PASS

`lupo_actors` exists and supports actor identity independent from auth users:
- actor identity fields: `actor_id`, `actor_name`, `slug`, `actor_type`
- uniqueness:
  - `actor_name` primary key
  - unique `actor_id`
  - unique `slug`
- actor and auth are separate tables (`lupo_actors` vs `lupo_auth_users`)

Assessment:
- actor identity exists and is separate from auth_user identity.

---

## 3. RELATIONSHIP MODEL (auth_user ↔ actor, many-to-many)

**Result:** PARTIAL

Current schema includes a conditional extension to add:
- `lupo_actors.auth_user_id BIGINT DEFAULT NULL`

This supports:
- one auth user linked to many actors (same `auth_user_id` repeated across actor rows)

But it does not support:
- many auth users linked to one actor (no join table; only one `auth_user_id` field on actor)
- role semantics per relationship (primary/supporting/reviewer)
- relationship lifecycle and auditing for actor-support assignments

Conclusion:
- current model is effectively one-to-many (`auth_user -> actors`) with optional single owner per actor
- required many-to-many is not fully modeled

Fail condition check:
- explicit 1:1-only enforcement: not present
- true many-to-many structure: missing

---

## 4. DIALOG PARTICIPATION MODEL

**Result:** PARTIAL

`lupo_dialog_messages` supports actor-based messaging:
- `from_actor_id`
- `to_actor_id`
- `read_by_actor_id`

No direct `auth_user_id` sender/recipient fields exist in dialog messages or dialog threads.

Implication:
- actor-generated messages: supported
- human-generated messages: supported only if human acts through an actor identity
- explicit differentiation between raw auth_user sender and actor sender: not supported in core dialog tables

---

## 5. DIALOG ESCALATION POSSIBILITY (Actor -> Human)

**Result:** PARTIAL

Escalation-relevant structures exist:
- `lupo_channel_escalations` supports actor->actor escalation (`actor_id`, `escalated_to_actor_id`)
- `lupo_human_requests` supports actor-initiated requests to a target auth user (`initiator_actor_id`, `target_auth_user_id`)
- `lupo_human_request_responses` captures both auth user and actor context (`auth_user_id`, `actor_id`)

Strength:
- explicit actor-to-auth-user request pathway exists

Gap:
- no canonical many-to-many support table linking actors to all supporting auth users
- therefore routing can target a specific auth user, but cannot reliably resolve "all supporting humans for actor X" from a normalized relationship model

---

## 6. MULTI-ACTOR HUMAN SUPPORT

**Result:** PASS

A single auth user can support multiple actors via current schema pattern:
- same `auth_user_id` can appear on multiple `lupo_actors` rows

Limitation:
- this pass condition is directional only (human -> many actors)
- reverse direction (actor -> many supporting humans) is not supported by current core relationship design

---

## 7. Doctrine Safety Check

**Result:** PASS

Within install schema authority reviewed:
- no foreign keys detected
- no triggers detected
- no stored procedures/functions detected
- BIGINT UTC timestamp pattern is used across reviewed tables
- deterministic id pattern is explicit for `auth_user_id` and `actor_id` doctrines

---

## 8. Required Schema Changes (analysis only, no implementation)

### Gap A: true auth_user <-> actor many-to-many mapping

Classification: required_now

Required table:
- `lupo_actor_auth_users`

Suggested fields:
- `actor_auth_user_id BIGINT PRIMARY KEY`
- `actor_id BIGINT NOT NULL`
- `auth_user_id BIGINT NOT NULL`
- `relationship_role VARCHAR(64) NOT NULL DEFAULT 'supporting_human'`
- `is_primary TINYINT NOT NULL DEFAULT 0`
- `routing_priority SMALLINT NOT NULL DEFAULT 100`
- `status VARCHAR(32) NOT NULL DEFAULT 'active'`
- `metadata_json JSON DEFAULT NULL`
- `created_ymdhis BIGINT NOT NULL DEFAULT 0`
- `updated_ymdhis BIGINT NOT NULL`
- `is_deleted TINYINT NOT NULL DEFAULT 0`
- `deleted_ymdhis BIGINT DEFAULT 0`

Suggested indexes:
- unique `(actor_id, auth_user_id, relationship_role)`
- index `(auth_user_id, status)`
- index `(actor_id, status, is_primary, routing_priority)`

Rationale:
- required to satisfy true many-to-many requirement and multi-supporter routing use cases.

### Gap B: escalation routing to support pools

Classification: can_defer

Suggested enhancement:
- extend `lupo_human_requests` with optional `target_mode` and `target_actor_id` semantics for support-pool resolution
- or add `resolver_rule_json` to route to eligible supporting users linked in `lupo_actor_auth_users`

Rationale:
- current schema can target a specific auth user, but support-pool resolution is not normalized.

### Gap C: direct dialog sender typing (actor vs auth_user)

Classification: can_defer

Suggested enhancement:
- optional `sender_type` and `sender_auth_user_id` columns in `lupo_dialog_messages`
- keep actor fields for compatibility while enabling explicit auth-user attribution

Rationale:
- current actor-only message identity works, but explicit sender typing would remove ambiguity.

---

## Final Verdict

system_support_status:
- PARTIALLY_SUPPORTED

Answer to objective question:

Can the current database support a human user acting as a supporting user for multiple AI actors, and allow dialog to escalate from actors to humans?

- Yes, partially.
- It supports one human supporting multiple actors and supports actor-initiated human request escalation.
- It does not fully support true many-to-many actor<->human support relationships because there is no canonical join table for multiple supporting humans per actor.
