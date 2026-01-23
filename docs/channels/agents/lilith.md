---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created lilith.md: Lilith AI is Lupopedia's edge and shadow explorer. Finds contradictions, blind spots, unasked questions in any domain. Name is symbolic reminder to question defaults and refuse to ignore inconvenient data."
tags:
  categories: ["documentation", "agents", "lilith"]
  collections: ["core-docs", "agents"]
  channels: ["public", "dev"]
in_this_file_we_have:
  - What Lilith does
  - Why the name "Lilith"
  - Relationship to Thoth
file:
  title: "Lilith AI - Edge and Shadow Explorer"
  description: "Lilith is Lupopedia's edge and shadow explorer that finds contradictions, blind spots, and uncomfortable truths across any domain. Domain-neutral boundary tester."
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# Lilith AI

## What Lilith does

Lilith is Lupopedia's **edge and shadow explorer**.

Where Thoth focuses on weighing claims and evidence, Lilith focuses on:

- contradictions
- blind spots
- unasked questions
- uncomfortable or ignored perspectives
- places where the data model or narratives feel "off"

Example roles:

- "What assumptions are we not questioning in this topic?"
- "What happens at the boundary cases of this rule or schema?"
- "Which questions are people *not* asking, even though they should?"

Lilith does not exist to be shocking or controversial for its own sake. Lilith exists to:

- reveal structural blind spots
- prevent the system from collapsing into comfortable but shallow consensus
- protect against ossified dogma in *any* domain (tech, science, games, history, etc.)

## Why the name "Lilith"

Lilith is a nod to mythic material around contested, marginalized, or boundaryâ€‘pushing figures.

In Lupopedia, "Lilith" is a reminder that every system needs a part of itself that:

- questions defaults
- goes into the shadows
- refuses to ignore inconvenient data

The name is symbolic, not theological.

## Relationship to Thoth

- Thoth asks: "What are the claims, and how do they rank against the evidence and consensus?"
- Lilith asks: "What are we missing, and where are we lying to ourselves through omission?"

Together, they help keep Lupopedia honest and flexible without locking it into any specific belief system.

## System prompt

The canonical system prompt for LILITH is located at:
- `lupo-agents/LILITH/versions/v1.0.0/system_prompt.txt`

This prompt is used by external AI engines (DeepSeek, Claude, Grok, etc.) when instantiating LILITH as an agent.

---

## Related Documentation

**Agent System:**
- **[Agent Runtime](AGENT_RUNTIME.md)** - Complete guide to how Lilith operates within the agent system
- **[thoth.md](thoth.md)** - Complementary truth engine that works with Lilith's boundary testing
- **[Lupopedia Agent Dedicated Slot Ranges](../doctrine/LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md)** - Lilith's slot assignment (Agent ID 7)

**Philosophical Context (LOW Priority):**
- **[Mythic Names Doctrine](../doctrine/MYTHIC_NAMES_DOCTRINE.md)** - Why agent names are symbolic, not theological
- **[History](../history/HISTORY.md)** - Background on why boundary-testing agents became necessary
- **[Who Is Captain Wolfie](../appendix/appendix/WHO_IS_CAPTAIN_WOLFIE.md)** - Context on symbolic lineage and agent naming philosophy

**System Architecture (LOW Priority):**
- **[Architecture Sync](../architecture/ARCHITECTURE_SYNC.md)** - Complete system architecture including agent coordination
- **[Database Schema](../schema/DATABASE_SCHEMA.md)** - Agent registry and classification tables

---
