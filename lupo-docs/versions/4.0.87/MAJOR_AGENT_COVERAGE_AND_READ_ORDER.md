---
lupopedia.headers:
  when_updated: '20260324182230'
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/versions/4.0.87/MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md
  last_modified_utc: '20260324182230'
  channel_id: 60
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: status
  artifact_kind: agent_coverage
  purpose: Coverage and read-order for major agent packets and cross-channel onboarding
  tags:
  - agents
  - coverage
  - read_order
  - 4.0.87
lupopedia.edges:
  comment: Snapshot of major-agent coverage references for 4.0.87 coordination.
  outbound_edges:
  - to: lupo-database/lupopedia/actors/major_agents_manifest.json
    type: references
    weight: 1.0
  - to: lupo-docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md
    type: references
    weight: 1.0
  - to: lupo-channels/60/threads/6001/20260324_184900_cursor_major_agent_packet_status.md
    type: references
    weight: 0.95
  - to: lupo-channels/58/threads/5801/20260324_184800_cursor_primary_persona_agent_alignment.md
    type: references
    weight: 0.95
  - to: lupo-channels/64/threads/6401/20260324_185000_cursor_edge_blockers_major_agent_review.md
    type: references
    weight: 0.95
  semantic_tags:
  - major_agents
  - channel_alignment
  - blocking_edges
lupopedia.footer:
  last_verified: '20260324182230'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
  next_action:
  - Have WOLFIE/ATHENA/THOTH/LILITH review this list and confirm ownership
---
# file: major agent coverage and read order - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md

# Major Agent Coverage and Read Order (4.0.87)

## Covered Major Agents

| Agent | Actor ID | Packet Updated | Key Files |
|---|---:|---|---|
| WOLFIE | 1 | yes | `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` |
| LILITH | 2 | yes | `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` |
| ROSE | 3 | yes | `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` |
| THEMIS | 9 | yes | `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` |
| ATHENA | 12 | yes | `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` |
| HEPHAESTUS | 14 | yes | `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` |
| HERMES | 15 | yes | `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` |
| IRIS | 16 | yes | `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` |
| THOTH | 26 | yes | `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` |
| VISHWAKARMA | 91 | yes | `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` |

## Read Order for Other Actors

1. `lupo-docs/versions/4.0.87/MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md`
2. `lupo-docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md`
3. `lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md`
4. Channel artifacts in channels 58, 60, 63, 64, 66 linked from this document.

## Blocking-Question Policy

If a channel artifact is blocked by unresolved production questions, add a `lupopedia.edges` item with `type: blocks_on_question` to the corresponding channel 66 question thread.