---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/AGENT_EXECUTION_RULES.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.0/prd/AGENT_EXECUTION_RULES.md"
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
  title: "Agent Execution Rules (PRD)"
  summary: "Product Requirements Document governing how agents execute workflows in Lupopedia."
---
# Agent Execution Rules

## 1. PRD-First Workflow
Agents must identify and read the relevant PRD before executing any coding tasks. Agents must update the PRD fully if anything is missing, before updating any toons or implementation files. The PRD is the absolute source of truth.

## 2. Header Enforcement
Every new and modified file must contain a canonical 20-key `lupopedia.headers` block at the top. The headers must follow the precise ordering defined by PRD 16 without exception. IDE agents must enforce these headers automatically.

## 3. Validator Requirements
Validators must treat all constitutional drift as a HARD FAIL. Validators must not emit warnings for architectural breaches. Any component that fails a validation check must block the completion of the task.

## 4. Transcript JSONL Rules
Every execution step performed by an agent must be mapped, categorized, and recorded in the transcript filter JSONL sequence for strict auditability. This ensures proper alignment and adherence to constitutional limitations.

## 5. Channel Routing Rules
Agents participating in the routing pipeline must execute tasks by explicit channel/actor selections. Routed tasks must carry exact context through the payload. Agents must process tasks from the pending list exactly as assigned.
