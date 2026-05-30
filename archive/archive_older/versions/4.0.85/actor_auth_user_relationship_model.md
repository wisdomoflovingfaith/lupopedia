---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.85/actor_auth_user_relationship_model.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.85/actor_auth_user_relationship_model"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: guide
  thread_id: 2011
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Actor Auth User Relationship Model

## Purpose

This document defines the canonical many-to-many relationship between `auth_user` and `actor` in Lupopedia 4.0.85.

It is the final authoritative 4.0.85 location for the corrected Thread 2011 relationship outcome.

It enables:
- one human auth user supporting multiple actors
- one actor having multiple supporting humans
- current dialog routing MVP through support pools

## Final 4.0.85 State

This model is:

- COMPLIANT
- install-ready
- authoritative for actor-to-auth_user routing relationships

Condensed canonical outcome (Thread 2011):

- many-to-many actor/auth_user model implemented via `lupo_actor_auth_users`
- over-constrained primary invariant corrected
- validation and re-audit completed with COMPLIANT verdict
- legacy `lupo_actors.auth_user_id` retained for compatibility only

## Table

`lupo_actor_auth_users`

This table is the normalized relationship layer between `lupo_auth_users` and `lupo_actors`.

## Core Model

Relationship cardinality:
- `auth_user` -> many `actor`
- `actor` -> many `auth_user`

This is implemented via one row per relationship role.

## Key Fields

- `actor_auth_user_id`: deterministic primary key for the relationship row
- `actor_id`: target actor
- `auth_user_id`: supporting human auth identity
- `relationship_role`: relationship type (default `supporting_human`)
- `is_primary`: marks default human for first-line escalation
- `routing_priority`: integer priority for ordered routing (lower value = higher priority)
- `status`: active/inactive lifecycle state for relationship-level routing eligibility
- `metadata_json`: optional relationship metadata
- `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`: doctrine-compliant lifecycle fields

## Supporting Human Semantics

Supporting humans are modeled as explicit relationship rows.

Example:
- actor 15 has support users 1000, 1001, 1002
- this is represented by three rows in `lupo_actor_auth_users`

No actor-side single-column mapping is required for many-to-many behavior.

Backward compatibility:
- `lupo_actors.auth_user_id` remains available for transitional compatibility
- canonical future mapping lives in `lupo_actor_auth_users`

## Authoritative Vs Legacy Fields

Authoritative for routing:

- `lupo_actor_auth_users.actor_id`
- `lupo_actor_auth_users.auth_user_id`
- `relationship_role`
- `is_primary`
- `routing_priority`
- `status`

Legacy/transitional only:

- `lupo_actors.auth_user_id`

The legacy field may remain readable for compatibility, but it is not the source of truth for the 4.0.85 routing MVP.

## Transitional Precedence Rule (Critical)

Authoritative source of truth:
- `lupo_actor_auth_users` is authoritative for actor to auth_user routing relationships.

Legacy compatibility field:
- `lupo_actors.auth_user_id` is legacy and read-only for transitional compatibility.

Conflict warning:
- if `lupo_actors.auth_user_id` conflicts with `lupo_actor_auth_users`, routing and support-pool logic must use `lupo_actor_auth_users`.

Deprecation path:
1. keep `lupo_actors.auth_user_id` for legacy readers only
2. stop writing new routing relationships to legacy column
3. migrate all routing reads to `lupo_actor_auth_users`
4. remove legacy read dependency only after all callers are upgraded

## Routing Priority and Primary Selection

`is_primary`:
- `1` indicates the preferred first-contact support human for that actor and role
- `0` indicates non-primary support mapping

`routing_priority`:
- default `100`
- lower numbers represent higher routing precedence
- allows deterministic ordering across multiple supporting humans
- must be non-negative (`>= 0`) via application-layer validation

`status`:
- allowed values: `active`, `inactive`, `disabled`
- enforced at application layer for cross-database compatibility (no ENUM)

Primary invariant model (corrected):
- schema does **not** use unique `(actor_id, relationship_role, is_primary)` because it over-constrains non-primary support rows
- schema preserves many-to-many uniqueness on `(actor_id, auth_user_id, relationship_role)`
- application-layer invariant enforces at most one `is_primary=1` row per `(actor_id, relationship_role)`
- schema allows multiple `is_primary=0` rows per `(actor_id, relationship_role)` for support-pool behavior
- `is_primary` must be 0 or 1 via application-layer validation

Routing-support indexing:
- `lupo_actor_auth_users_idx_actor_role_primary_lookup` supports deterministic primary lookup and fallback scans by actor/role/status/deleted/priority/user
- `lupo_actor_auth_users_idx_actor_routing` supports active routing candidate pool scans and ordered selection

Recommended routing order for future dialog systems:
1. active rows only (`status='active'`, `is_deleted=0`)
2. primary rows first (`is_primary=1`)
3. then ascending `routing_priority`
4. deterministic tie-breaker by `actor_auth_user_id`

## Doctrine Compliance Notes

- no foreign keys
- no triggers
- no procedures/functions
- BIGINT UTC lifecycle timestamps
- deterministic IDs supplied by application layer

## Task and Ownership Boundaries

This table models identity/support relationships only.

It does not own:
- task assignment state
- dialog business logic
- routing execution logic

Those behaviors remain in their authoritative subsystem surfaces.

