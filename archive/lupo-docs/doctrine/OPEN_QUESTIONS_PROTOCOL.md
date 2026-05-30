---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/doctrine/OPEN_QUESTIONS_PROTOCOL.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/OPEN_QUESTIONS_PROTOCOL.md"
  status: "active"
  when_updated: "20260419120000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: protocol
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "open-questions-protocol"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "OPEN_QUESTIONS_PROTOCOL -- extraction and deduplication of uncertainty"
  summary: "Defines how open questions (OQs) are extracted from changelog buffer entries and documented in versioned ledgers."
---
# OPEN_QUESTIONS_PROTOCOL.md

## 1. Purpose
This protocol ensures that any uncertainty, risk, or unresolved question identified by an agent during a task is captured, ledgered, and tracked through the version lifecycle.

## 2. Agent Responsibility
- When an agent encounters a problem or has a question, they MUST include it in the `open_questions` field of their `lupo-changelog-pending` JSON buffer entry.
- **Format**: Concise ASCII string representing the question.
- **Context**: If the question relates to a specific file or section, include that in the question string or summary.

## 3. Extraction during Consolidation
The consolidator script (e.g. `consolidate_changelog_v412.py`) is responsible for extracting these questions and appending them to the version's `open_questions.md` file.

## 4. Deduplication Logic
- **Exact String Match**: If a question string is identical to one already in the `open_questions.md` file, it is skipped.
- **Agent and Thread Cross-Reference**: Each extracted question should be labeled with the `agent_id`, `thread`, and `timestamp` of the source buffer entry.

## 5. Ledger Format in open_questions.md
New questions extracted from the buffer should be appended under a `## New Questions from Consolidation` section in the following format:
```markdown
- **QUESTION:** {question_text}
  - **AGENT:** {agent_id}
  - **THREAD:** {thread}
  - **TIMESTAMP:** {timestamp}
```

## 6. Status Transitions
- Questions remain **OPEN** until a future agent resolves them and updates the ledger with a `STATUS UPDATE: RESOLVED` entry.
- Resolved questions are preserved in the ledger for audit purposes.
