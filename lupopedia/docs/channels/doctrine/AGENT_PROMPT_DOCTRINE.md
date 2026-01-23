---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Agent Prompt Doctrine documentation defining the complete structure, philosophy, and doctrine governing agent prompts within Lupopedia."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "agents", "prompts"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Agent Prompt Doctrine Overview
  - Purpose of Agent Prompts
  - Prompt Structure (10 Required Sections)
  - Identity Block
  - Role & Purpose Block
  - Behavioral Doctrine Block
  - Capabilities Block
  - Boundaries & Forbidden Behaviors Block
  - Interaction Rules Block
  - Inline Dialog Rules Block
  - Mood Signaling Rules Block
  - Safety & Oversight Block
  - Version & Lineage Block
  - Prompt Invariants
  - Prompt Generation Doctrine
  - Prompt Safety Doctrine
file:
  title: "Agent Prompt Doctrine"
  description: "Documents the doctrine governing agent prompts within Lupopedia, including structure, philosophy, boundaries, and alignment with classification, faucet rules, and capabilities."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Agent Prompt Doctrine

This section documents the doctrine governing agent prompts within Lupopedia. Agent prompts define the identity, behavior, boundaries, capabilities, and rhetorical style of each agent. Prompts must be deterministic, doctrine-aligned, and free of drift.

Agent prompts are stored in:

/lupopedia/lupo-agents/<agent_code>/system_prompt.txt

---

## Purpose of Agent Prompts

Agent prompts exist to:

- define the agent's identity,
- enforce behavioral doctrine,
- constrain capabilities,
- prevent cross-agent contamination,
- ensure deterministic output,
- maintain safety boundaries,
- align with classification_json,
- align with faucet_rules.json,
- align with capabilities.json.

Prompts are not creative writing.  
They are **operational specifications**.

---

## Prompt Structure

Every system_prompt.txt must contain the following sections in order:

1. **Identity Block**  
2. **Role & Purpose Block**  
3. **Behavioral Doctrine Block**  
4. **Capabilities Block**  
5. **Boundaries & Forbidden Behaviors Block**  
6. **Interaction Rules Block**  
7. **Inline Dialog Rules Block** (if applicable)  
8. **Mood Signaling Rules Block** (if applicable)  
9. **Safety & Oversight Block**  
10. **Version & Lineage Block**

These sections must appear in this exact order.

---

## 1. Identity Block

The prompt must begin with:

```
You are {{agent_name}}.
```

Where {{agent_name}} is derived from:
- agent.json â†’ name
- classification_json â†’ agent_class
- global_atoms.yaml (if symbolic)

Identity rules:
- No aliases in the prompt.
- No creative names.
- No persona drift.
- No emotional self-description unless required (e.g., DIALOG).

---

## 2. Role & Purpose Block

Defines what the agent is for.

Examples:
- "You perform deep reasoning and verification."
- "You render expressive dialog summaries."
- "You enforce safety boundaries."
- "You perform semantic graph lookups."

This block must match:
- classification_json.agent_class
- classification_json.subclass
- capabilities.json

---

## 3. Behavioral Doctrine Block

Defines how the agent behaves.

Examples:
- deterministic,
- non-creative,
- non-emotional (unless DIALOG),
- non-expressive (unless DIALOG),
- non-reasoning (for DIALOG),
- safety-first (for LILITH),
- truth-first (for THOTH),
- precision-first (for MAAT).

This block must reflect the agent's polarity:
- top (assertive, analytical)
- down (expressive, receptive)

---

## 4. Capabilities Block

This block must explicitly list the capabilities the agent is allowed to perform.

Capabilities must match:
- capabilities.json
- classification_json.capabilities
- faucet_rules.json.required_capabilities

Capabilities must NOT exceed what is declared.

---

## 5. Boundaries & Forbidden Behaviors Block

This block defines what the agent must never do.

Examples:
- DIALOG must not perform reasoning.
- MAAT must not generate expressive tone.
- THOTH must not invent facts.
- LILITH must not generate content.
- External AI agents must not access kernel data.
- Utility agents must not perform semantic reasoning.

Forbidden behaviors must match:
- faucet_rules.json.forbidden_capabilities
- classification_json
- doctrine

---

## 6. Interaction Rules Block

Defines how the agent interacts with:

- other agents,
- the caller,
- the semantic graph,
- the routing system,
- the operator UI.

Examples:
- "You must not impersonate other agents."
- "You must not modify other agent directories."
- "You must not bypass HERMES or CADUCEUS."
- "You must not access private installation data."

---

## 7. Inline Dialog Rules Block (If Applicable)

Required for:
- DIALOG
- expressive agents
- any agent that generates inline dialog for WOLFIE Headers

Rules:
- must follow Counting-in-Light mood geometry,
- must output summary_text and mood_rgb,
- must not perform reasoning,
- must not alter meaning,
- must not exceed expressive boundaries.

---

## 8. Mood Signaling Rules Block (If Applicable)

Defines how the agent computes or interprets mood_rgb.

Required for:
- DIALOG
- expressive agents
- any agent that influences CADUCEUS currents

Rules:
- must follow Counting-in-Light doctrine,
- must output valid RRGGBB hex,
- must not use long-term memory,
- must not use system state.

---

## 9. Safety & Oversight Block

Defines how the agent interacts with kernel safety agents.

Examples:
- "You must defer to LILITH for safety."
- "You must defer to THOTH for truth verification."
- "You must defer to MAAT for deep reasoning."

This block ensures:
- safety,
- accuracy,
- doctrinal alignment.

---

## 10. Version & Lineage Block

Every prompt must end with:

- version number,
- last updated timestamp,
- lineage (parent agent if alias),
- symbolic references to global_atoms.yaml (if applicable).

Example:
```
Version: 1.2.0
Updated: 20260110120000
Lineage: Parent agent = THOTH
```

---

## Prompt Invariants

Across all agents:

1. Prompts must be deterministic.  
2. Prompts must not contain creative prose.  
3. Prompts must not contradict classification_json.  
4. Prompts must not contradict faucet_rules.json.  
5. Prompts must not contradict capabilities.json.  
6. Prompts must not contradict doctrine.  
7. Prompts must not drift over time.  
8. Prompts must be version-controlled.  
9. Prompts must be self-contained.  
10. Prompts must never reference other agent prompts.  

---

## Prompt Generation Doctrine

When creating or updating a prompt:

- Cursor must use the template defined in Section 5 (Agent Filesystem + Prompt Template Doctrine).
- All symbolic references must use @GLOBAL.<ATOM>.
- All sections must appear in correct order.
- No section may be omitted.
- No section may be renamed.
- No creative additions are allowed.

---

## Prompt Safety Doctrine

Prompts must enforce:

- safety boundaries,
- capability boundaries,
- classification boundaries,
- polarity boundaries,
- model boundaries,
- routing boundaries.

Prompts must never:
- allow unsafe model invocation,
- allow cross-agent contamination,
- allow bypassing kernel agents,
- allow unauthorized reasoning or expression.

---

## **Related Documentation**

- **[SYSTEM_AGENT_SAFETY_DOCTRINE.md](SYSTEM_AGENT_SAFETY_DOCTRINE.md)** â€” Agent 0 kernel authority and inviolable safety rules
- **[INLINE_DIALOG_SPECIFICATION.md](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)** â€” Required communication format for all agents
- **[AGENT_RUNTIME.md](../agents/AGENT_RUNTIME.md)** â€” Complete guide to agent system runtime behavior
- **[FAUCET_RULES_DOCTRINE.md](FAUCET_RULES_DOCTRINE.md)** â€” Agent capability enforcement and safety boundaries

---
