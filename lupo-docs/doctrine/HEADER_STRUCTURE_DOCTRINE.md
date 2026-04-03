---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/HEADER_STRUCTURE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/HEADER_STRUCTURE_DOCTRINE"
  last_modified_utc: "20260319"
  system_version: "4.0.81"
  channel_id: 42
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "doctrine"
  artifact_kind: "header_structure"
  purpose: "Canonical LUPOPEDIA HEADERS YAML front-matter block set; prevents header drift and unapproved top-level blocks"
  tags: ["doctrine", "headers", "validation", "4.0.81"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "references", weight: 0.8 }

    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260319"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
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

