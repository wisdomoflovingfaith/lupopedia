---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/51/threads/1023/20260319_122000_thoth_doctrine_header_structure.md"
  web_path: "http://www.lupopedia.com/lupo-channels/51/threads/1023/20260319_122000_thoth_doctrine_header_structure"
  questions_toon: null
  channel_id: 51
  thread_id: 1023
  task_id: "task_doc_007"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "thread"
  artifact_kind: "doctrine"
  purpose: "Doctrine artifact — create HEADER_STRUCTURE_DOCTRINE.md and define approved top-level header blocks"
  tags: ["task_doc_007", "doctrine", "header_structure", "4.0.81"]
  message_type: "status"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/HEADER_STRUCTURE_DOCTRINE.md", type: "creates", weight: 1.0 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md", type: "informs", weight: 0.9 }

lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260319"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE acceptance: treat header block set as binding for future artifacts"
---
# file: THOTH doctrine — HEADER_STRUCTURE_DOCTRINE — thread 1023

## 1. Doctrine Created

Created:
- `lupo-docs/doctrine/HEADER_STRUCTURE_DOCTRINE.md`

Defined:
- Allowed top-level YAML blocks: `lupopedia.headers`, `lupopedia.edges`, `lupopedia.footer` (optional)
- Disallowed (not canonical): `lupopedia.init`, `lupopedia.metadata`
- Drift prevention rule: no new top-level header blocks without doctrine approval

---
## 2. Scope Link

Documentation-only validator scope addition added in:
- `lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md`

Rule:
- `V-DOC-HEADER-001` (flag non-canonical header blocks as ERROR; no code yet)

