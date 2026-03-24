---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/TAKEOVER_CHANNEL_MAP_20260324.md
  last_modified_utc: '20260324200640'
  channel_id: 66
  actor_id: 1
  actor_name: wolfie
  artifact_type: handoff
  artifact_kind: channel_map
  purpose: Clear takeover map of channels, threads, and unresolved questions for 4.0.87 continuity.
  when_updated: '20260324200640'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/TAKEOVER_CHANNEL_MAP_20260324.md
  delegation_chain: wolfie:root
lupopedia.footer:
  last_verified: '20260324200640'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
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
