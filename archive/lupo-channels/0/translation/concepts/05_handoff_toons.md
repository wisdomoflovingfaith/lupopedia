---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-channels/0/translation/concepts/05_handoff_toons.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-channels/0/translation/concepts/05_handoff_toons.md"
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
  title: "Translation: Handoff Toons"
  summary: "Translation artifact for the Handoff Toons concept."
---
# Concept: Handoff Toons

## Internal Technical Wording (Layer 3)
Handoff toons are strictly formatted JSON/Markdown state files generated when an execution node terminates. They contain exact completion status, open blockers, files changed, and the downstream execution pointers needed for the subsequent agent's context initialization.

## Conceptual Model (Layer 2)
A handoff toon is the equivalent of a shift-change summary in a factory. When one worker clocks out, they leave a clipboard on the desk detailing exactly where they stopped, what broke, and what the next worker needs to build. 

## External Short Wording (Layer 1)
When an AI finishes its shift, it leaves a clear status summary for the next AI or human taking over the task.

## Business Wording
We ensure project continuity through automated, standardized shift-change artifacts called "Handoff Toons." These eliminate the knowledge loss that typically occurs when transferring complex tasks between different intelligent agents or teams.

## User-Guide Wording
If one AI assistant stops working on your project, the next assistant will read its "handoff notes" to pick up exactly where the last one left off without you having to re-explain anything.

## Developer Wording
Before an agent completes its lifecycle, it must dump its working state into a `.toon` file. Do not expect the next agent in the relay to inherit your context window. It only knows what you write down in the toon.

## Example Analogy
It's the baton in a relay race. Without it, the next runner can't start. The handoff toon is the baton that securely passes momentum from one mind to another.

## Common Misunderstanding
"It's just a funny name for a text log."
*Correction*: It is an actively parsed JSON execution blueprint necessary to initialize the next agent's session, not just a historical log.

## Wording to Avoid
* "Cartoon variables"
* "Agent chat histories"
* "System dumps"
