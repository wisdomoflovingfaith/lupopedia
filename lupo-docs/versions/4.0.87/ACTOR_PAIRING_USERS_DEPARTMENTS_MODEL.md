---
lupopedia.headers:
  when_updated: '20260324200640'
  lupopedia.schema: doctrine
  file_path_from_root: lupo-docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md
  last_modified_utc: '20260324200640'
  channel_id: 63
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: doctrine
  artifact_kind: identity_pairing_model
  purpose: How actors are paired with users and departments, with table-level truth
    surfaces
  tags:
  - actors
  - users
  - departments
  - pairing
  - database
lupopedia.edges:
  comment: Snapshot of actor-pairing references tied to table docs and channel coordination.
  outbound_edges:
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actors.md
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actor_auth_users.md
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actor_departments.md
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_departments.md
    type: references
    weight: 0.95
  - to: lupo-channels/63/threads/6301/20260324_185100_cursor_actor_pairing_db_truth.md
    type: references
    weight: 0.95
  - to: lupo-channels/66/threads/1052/20260324_185200_cursor_question_actor_pairing_defaults.md
    type: blocks_on_question
    weight: 1.0
  semantic_tags:
  - identity_model
  - pairing
  - db_truth
lupopedia.footer:
  last_verified: '20260324200640'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
  next_action:
  - Resolve default pairing policy question in channel 66 thread 1052
---
# file: actor pairing users departments model - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md

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
