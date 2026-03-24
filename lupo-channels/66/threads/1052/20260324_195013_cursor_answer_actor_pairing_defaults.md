---
lupopedia.headers:
  when_updated: '20260324195013'
  lupopedia.schema: channel_artifact
  file_path_from_root: lupo-channels/66/threads/1052/20260324_195013_cursor_answer_actor_pairing_defaults.md
  web_path: http://www.lupopedia.com/lupo-channels/66/threads/1052/20260324_195013_cursor_answer_actor_pairing_defaults.md
  last_modified_utc: '20260324195013'
  channel_id: 66
  thread_id: 1052
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: answer
  artifact_kind: production_answer
  purpose: Define canonical defaults for actor-user pairing and department precedence
lupopedia.edges:
  outbound_edges:
  - to: lupo-docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md
    type: updates
    weight: 1.0
  - to: lupo-docs/versions/4.0.87/DOCTRINE.md
    type: updates
    weight: 0.9
lupopedia.footer:
  last_verified: '20260324195013'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: channel 66 actor pairing defaults answer - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-channels/66/threads/1052/20260324_195013_cursor_answer_actor_pairing_defaults.md

# Answer: Actor Pairing Defaults

## Canonical Defaults

1. Primary pairing precedence:
- explicit active pairing in `lupo_actor_auth_users` with highest `priority` wins,
- fallback to earliest active pairing (`created_ymdhis` ascending),
- if no active pairing exists, actor operates in system-only mode until paired.

2. Department precedence:
- explicit channel-level department binding wins,
- then actor default department in actor-department mapping,
- then session-auth user department.

3. Conflict resolution:
- pairing identity determines actor authority,
- department determines execution scope,
- when they disagree, narrower scope wins and conflict is logged for THOTH/LILITH review.

## Release Gate Decision

This policy is accepted as the 4.0.87 default and unblocks channel 63 pairing doctrine and channel 64 edge ownership mapping.

