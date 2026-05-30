---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/versions/4.1.0/prd/WEB_INTERFACE_MODEL.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.0/prd/WEB_INTERFACE_MODEL.md"
  status: "active"
  when_updated: "20260416182218"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: prd
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "Web Interface Model (PRD)"
  summary: "Product Requirements Document for the observer vs actor display models and interface rules."
---
# Web Interface Model

## 1. Observer vs Active Actor Doctrine
The interface must visually enforce the difference between observers and active actors in the channel.
- Observer tabs must be styled with strict dark/neutral backgrounds (e.g., black).
- Active actor tabs must use vibrant context-specific colors.
- When selecting a tab, the destination target ("SENDING TO:" bar) and visual inputs must react to visually differentiate between pushing instructions to an active worker versus merely viewing.

## 2. IDE Input Rules
Inputs submitted by humans through the IDE integration layer must respect the state bindings.
- Under SEND mode, Enter submits the message and Shift+Enter inserts a newline.
- Under DRAFT mode, Enter inserts a newline and Shift+Enter submits the message.
- Input elements must sync their visual color space with the currently targeted active actor to prevent human error during coordination.
