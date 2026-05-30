---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: "20260324200640"
  file_path_from_root: "docs/versions/4.0.87/MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.87/MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: status
  artifact_kind: agent_coverage
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
# file: major agent coverage and read order - delegation: cursor:root - web_path: http://www.lupopedia.com/docs/versions/4.0.87/MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md

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

1. `docs/versions/4.0.87/MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md`
2. `docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md`
3. `docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md`
4. Channel artifacts in channels 58, 60, 63, 64, 66 linked from this document.

## Blocking-Question Policy

If a channel artifact is blocked by unresolved production questions, add a `lupopedia.edges` item with `type: blocks_on_question` to the corresponding channel 66 question thread.
