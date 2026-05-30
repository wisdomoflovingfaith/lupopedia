---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260324200640"
  file_path_from_root: "docs/versions/4.0.87/TAKEOVER_CHANNEL_MAP_20260324.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.87/TAKEOVER_CHANNEL_MAP_20260324.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: handoff
  artifact_kind: channel_map
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
# 4.0.87 Takeover Channel Map

## Constraint

- Cursor and Junie unavailable through `2026-04-03 00:00:00 UTC`.

## Active Channels and Required Actions

| Channel | Thread(s) | Purpose | Owner |
|---|---|---|---|
| 62 | organization stream thread(s) | Root/archive cleanup closure artifact and manifest | HEPHAESTUS + THOTH |
| 63 | DB docs stream thread(s) | Reconcile docs against TOON/json and close diffs | THOTH |
| 64 | edge governance threads | Close ERQ blockers and publish final evidence | HEPHAESTUS + ATHENA |
| 66 | 1050, 1051, 1052, 1054 | Keep blocker answers + takeover directive current | WOLFIE |
| 66 | 1047 | Resolve unresolved Q1-Q7 governance/implementation set | THEMIS + ROSE + HEPHAESTUS |

## Open Questions Requiring Explicit Answer Artifacts

1. Header reimport safety/determinism.
2. Multi-channel metadata ownership authority.
3. Header immutability vs controlled editability policy.
4. Staleness warning behavior and enforcement mode.
5. Timestamp validation in regeneration scripts.
6. Channel-scoped metadata authority boundaries.
7. Permission model for header reimport operations.
