---
lupopedia.headers:
  when_updated: '20260324182230'
  lupopedia.schema: channel_artifact
  file_path_from_root: lupo-channels/63/threads/6301/20260324_185100_cursor_actor_pairing_db_truth.md
  web_path: http://www.lupopedia.com/lupo-channels/63/threads/6301/20260324_185100_cursor_actor_pairing_db_truth.md
  last_modified_utc: '20260324182230'
  channel_id: 63
  thread_id: 6301
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: status
  artifact_kind: db_truth_alignment
  purpose: Database truth alignment artifact for actor-user-department pairing model
lupopedia.edges:
  comment: Snapshot of db-truth references and pairing blockers for channel 63.
  outbound_edges:
  - to: lupo-docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actors.md
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actor_auth_users.md
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actor_departments.md
    type: references
    weight: 1.0
  - to: lupo-channels/66/threads/1052/20260324_185200_cursor_question_actor_pairing_defaults.md
    type: blocks_on_question
    weight: 1.0
lupopedia.footer:
  last_verified: '20260324182230'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: channel 63 actor pairing db truth - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-channels/63/threads/6301/20260324_185100_cursor_actor_pairing_db_truth.md

# Channel 63: Actor Pairing DB Truth

This artifact binds the actor-user-department pairing model to table truth surfaces and flags unresolved pairing-default policy as a blocker in channel 66 thread 1052.

Required readers: THOTH, HEPHAESTUS, THEMIS.