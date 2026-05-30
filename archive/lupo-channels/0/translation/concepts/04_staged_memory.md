---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-channels/0/translation/concepts/04_staged_memory.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-channels/0/translation/concepts/04_staged_memory.md"
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
  title: "Translation: Staged vs Approved Memory"
  summary: "Translation artifact for the Staged vs Canonical Memory concept."
---
# Concept: Staged vs Approved/Canonical Memory

## Internal Technical Wording (Layer 3)
Memory persistence is split across the trust ladder. Agents write working logs to `lupo-memory/*/staging/`. These documents remain non-binding until a human or auditing actor runs a promotion execution block to move them to `lupo-memory/*/canonical/`, at which point they become binding doctrine.

## Conceptual Model (Layer 2)
Think of "staged memory" as a scratchpad or a rough draft. It's safe for the AI to throw chaotic thoughts here. "Approved memory" is the textbook. The AI can only graduate its scratchpad notes into the textbook if a verified supervisor approves it.

## External Short Wording (Layer 1)
AI ideas start as rough drafts. They only become permanent rules once approved by a human.

## Business Wording
We enforce a strict staging-to-production knowledge pipeline for our AI. Artificial intelligence assertions are quarantined in a staging environment where they cannot affect core business logic until they pass human review and achieve canonical status.

## User-Guide Wording
When the AI learns something new, it puts it in a "draft" state. You have full control over whether to finalize that learning into an official rule.

## Developer Wording
Do not read from `staging/` when enforcing PRD compliance. Staging is for passing state between agents doing dirty work. Only facts promoted to `canonical/` by an authorized action can be trusted as true system specifications.

## Example Analogy
It's like a bill becoming a law. A bill is just staged memory—an idea. Once the governor signs it, it becomes a canonical law that everyone must follow.

## Common Misunderstanding
"The agent wrote an impressive plan, so the system has updated."
*Correction*: The plan is just a draft. The system hasn't updated its rules until that plan is promoted to canonical memory.

## Wording to Avoid
* "The AI's beliefs"
* "Trained data"
* "Hardcoded memories"
