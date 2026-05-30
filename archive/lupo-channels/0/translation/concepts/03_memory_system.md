---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-channels/0/translation/concepts/03_memory_system.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-channels/0/translation/concepts/03_memory_system.md"
  status: "active"
  when_updated: "20260416182218"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "translation"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "Translation: Memory System"
  summary: "Translation artifact for the Memory System concept."
---
# Concept: Memory System

## Internal Technical Wording (Layer 3)
Agent memory is externalized into staging toons and canonical artifacts governed by constitutional headers. Agents do not retain LLM context between executions. Knowledge updates occur via atomic edge assertions (graph relationships) and verified JSONL blob promotion. Silent overwrites are forbidden.

## Conceptual Model (Layer 2)
The system separates "work in progress" thinking from "final approved" knowledge. When the system learns something, it doesn't just quietly replace old facts. It drafts what it learned, waits for approval, and then stores the verified information with full historical tracking so nothing is ever lost.

## External Short Wording (Layer 1)
Our AI systems don't have hidden memories. Everything they know and learn is tracked securely as readable files that humans can verify and approve.

## Business Wording
We maintain an auditable, transparent AI memory architecture. The AI's knowledge base is immutable—new learnings are drafted, reviewed, and promoted to canonical status without silently destroying historical records. This ensures high regulatory compliance and clear audit trails.

## User-Guide Wording
When you teach the system a new rule, it records it securely. It won't quietly overwrite its previous instructions, meaning you can always see exactly what it knows.

## Developer Wording
Never use an LLM's internal context window to store permanent facts. All learned rules, findings, and decisions must be explicitly written out to a file, reviewed, and persisted properly before they are considered "remembered" by the codebase.

## Example Analogy
It's like a corporate wiki. An employee can't just delete a major policy document in secret. They have to submit a tracked change (a draft), and once approved by management, the new policy becomes official, while the old version is kept in the archives.

## Common Misunderstanding
"The AI will remember this because I told it in the chat."
*Correction*: The AI only remembers what is explicitly saved to a documented memory file; chat memory is discarded on reset.

## Wording to Avoid
* "LLM memory"
* "Internal context"
* "The AI learned" (without context of how it was saved)
