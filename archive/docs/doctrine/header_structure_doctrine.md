---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/HEADER_STRUCTURE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/HEADER_STRUCTURE_DOCTRINE"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: header_structure
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: Header Structure Doctrine — session: L-LUPO-ROOT-THOTH — delegation: thoth:knowledge — web_path: http://www.lupopedia.com/doctrine/HEADER_STRUCTURE_DOCTRINE

# Header Structure Doctrine

## 1. Allowed Top-Level Blocks

In LUPOPEDIA HEADERS Markdown front-matter YAML, a file MUST use only these allowed top-level blocks:

- `lupopedia.headers`
- `lupopedia.edges`
- `lupopedia.footer` (optional)

## 2. Disallowed Blocks

These blocks are not canonical and MUST NOT be introduced:

- `lupopedia.init`
- `lupopedia.metadata`

## 3. Drift Prevention Rule

No file may introduce new top-level header blocks without doctrine approval.

## 4. Enforcement Intent

This doctrine exists to prevent parsing ambiguity and metadata drift caused by introducing additional root-level LUPOPEDIA HEADERS blocks beyond the approved set.

