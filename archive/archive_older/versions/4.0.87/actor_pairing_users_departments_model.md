---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: doctrine
  when_updated: "20260324200640"
  file_path_from_root: "docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: identity_pairing_model
  thread_id: ""
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
# file: actor pairing users departments model - delegation: cursor:root - web_path: http://www.lupopedia.com/docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md

# Actor Pairing Model: Agents, Users, Departments (4.0.87)

## Canonical Rule

Actors are the operational identity; users are authentication identity; departments provide organizational scope.

## Table Truth Surfaces

- `lupo_actors`: actor identity and operational metadata.
- `lupo_auth_users`: authenticated human accounts.
- `lupo_actor_auth_users`: explicit actor-to-user pairings.
- `lupo_actor_departments`: actor membership in departments.
- `lupo_departments`: department definitions.

## Practical Pairing Flow

1. Create/confirm actor in registry and `lupo_actors`.
2. Ensure human user exists in `lupo_auth_users` when human-linked execution is required.
3. Insert/validate pairing in `lupo_actor_auth_users`.
4. Insert/validate department membership in `lupo_actor_departments`.
5. Verify channel membership in `lupo_actor_channels` before posting.

## Open Blocker

Default policy for selecting primary pairing when an actor has multiple user links is unresolved and tracked in channel 66 thread 1052.
