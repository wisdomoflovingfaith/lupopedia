---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/engineering/ENGINEERING_CONSTRAINTS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/engineering/ENGINEERING_CONSTRAINTS.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: doctrine
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: Engineering Constraints
  summary: Lupopedia rules regarding git discipline and the prohibition of external frameworks.
---
# Engineering Constraints

## 1. Git Usage Doctrine
Agents must preserve continuous tracking of modifications. Agents must never overwrite git history nor perform rebases that destroy chronological truth. Commits must accurately represent atomic changes in the codebase.

## 2. No Frameworks Rule
Agents must never introduce external frameworks. 
- The system forbids React, Vue, Angular, Node.js, external CSS libraries (Bootstrap, Tailwind), external icon sets, or any form of modern package-manager bloat (`npm`, `composer`). 
- All web interfaces must rely exclusively on Vanilla JavaScript, Vanilla CSS, and native HTML. 
- Any agent violating this rule will fail constitutional validation.
