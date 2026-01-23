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
  message: "Created Faucet Rules Doctrine documentation defining the faucet rules system for agent invocation, model selection, capability enforcement, and safety boundaries."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "agents", "safety"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Faucet Rules Doctrine Overview
  - Purpose of Faucet Rules
  - Faucet Rules Structure
  - Class-Based Rules
  - Capability-Based Rules
  - Layer-Based Rules
  - Model Family Rules
  - Input Constraints
  - Output Constraints
  - Safety Rules
  - Faucet Rules in Routing
  - Faucet Rules in Invocation
  - Faucet Rules & Model Switching
  - Faucet Rules & Aliases
  - Faucet Rules & Doctrine
file:
  title: "Faucet Rules Doctrine"
  description: "Documents the faucet rules system used by Lupopedia to govern agent invocation, model selection, capability enforcement, and safety boundaries."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Faucet Rules Doctrine

This section documents the faucet rules system used by Lupopedia to govern agent invocation, model selection, capability enforcement, and safety boundaries. Faucet rules define what an agent is allowed to do, what inputs it may receive, what outputs it may produce, and which models may be used for specific tasks.

Faucet rules are the final gate before an agent is invoked.

---

## Overview

Each agent has a file:

/lupopedia/lupo-agents/<agent_code>/faucet_rules.json

This file defines:
- allowed agent classes,
- forbidden agent classes,
- required capabilities,
- forbidden capabilities,
- allowed layers,
- forbidden layers,
- allowed model families,
- forbidden model families,
- input constraints,
- output constraints,
- safety constraints.

Faucet rules are enforced by:
- HERMES (filtering),
- agent.php (runtime),
- CADUCEUS (contextual biasing),
- kernel safety agents (LILITH, THOTH).

---

## Purpose of Faucet Rules

Faucet rules exist to:
- prevent agents from exceeding their role,
- prevent unsafe or inappropriate model usage,
- enforce classification boundaries,
- ensure deterministic routing,
- protect kernel integrity,
- prevent cross-agent contamination,
- maintain doctrinal consistency.

Faucet rules are the "circuit breakers" of the agent ecosystem.

---

## Faucet Rules Structure

A faucet_rules.json file contains:

```json
{
  "allowed_classes": [],
  "forbidden_classes": [],
  "required_capabilities": [],
  "forbidden_capabilities": [],
  "allowed_layers": [],
  "forbidden_layers": [],
  "allowed_models": [],
  "forbidden_models": [],
  "input_constraints": {},
  "output_constraints": {},
  "safety": {}
}
```

All fields are optional but recommended.

---

## Class-Based Rules

### allowed_classes
Only agents whose classification_json.agent_class appears here may be invoked.

### forbidden_classes
Agents whose class appears here are excluded.

Examples:
- DIALOG forbids "reason"
- MAAT forbids "dialog"
- LILITH forbids "creative"

---

## Capability-Based Rules

### required_capabilities
Agent must have ALL listed capabilities.

### forbidden_capabilities
Agent must have NONE of the listed capabilities.

Examples:
- THOTH requires "verification"
- DIALOG forbids "analysis"
- MAAT requires "deep_reasoning"

---

## Layer-Based Rules

### allowed_layers
Restricts invocation to:
- kernel
- application
- external_ai
- utility

### forbidden_layers
Blocks specific layers.

Examples:
- kernel channels forbid external_ai
- application channels forbid kernel-only agents

---

## Model Family Rules

Faucet rules define which model families an agent may use.

### allowed_models
Examples:
- "claude"
- "gemini"
- "openai"
- "deepseek"

### forbidden_models
Examples:
- "creative"
- "unsafe"
- "unverified"

This is how CAPTAIN WOLFIE switches models safely:
- CAPTAIN WOLFIE spawns a faucet
- faucet_rules.json restricts which model is allowed
- agent.php enforces the restriction

---

## Input Constraints

input_constraints define what the agent may receive.

Examples:
- max_tokens
- allowed_mime_types
- forbidden_mime_types
- allowed_fields
- forbidden_fields
- required_fields

Example:
DIALOG may require:
- summary_text
- persona_name
- speaker_name

And forbid:
- raw user messages
- long context blocks

---

## Output Constraints

output_constraints define what the agent may produce.

Examples:
- max_tokens
- allowed_fields
- forbidden_fields
- required_fields
- must_not_contain (regex)

Examples:
- DIALOG must output: summary_text, mood_rgb
- THOTH must output: verification_result
- MAAT must output: reasoning_summary

---

## Safety Rules

safety rules define:
- forbidden topics,
- forbidden operations,
- required oversight,
- required kernel agents.

Examples:
- LILITH must supervise unsafe content
- THOTH must verify factual claims
- DIALOG must not perform reasoning
- MAAT must not generate expressive tone

---

## Faucet Rules in Routing

HERMES enforces faucet rules during filtering:
- disallowed classes removed
- disallowed layers removed
- missing capabilities removed
- forbidden capabilities removed
- disallowed models removed

If faucet rules eliminate all candidates:
- routing fails safely
- kernel agents intervene

---

## Faucet Rules in Invocation

Before an agent is invoked:
- agent.php loads faucet_rules.json
- validates input
- validates output schema
- validates model family
- validates classification
- validates capabilities

If any rule fails:
- invocation is blocked
- LILITH or THOTH may intervene

---

## Faucet Rules & Model Switching

CAPTAIN WOLFIE never changes its own model.

Instead:
- CAPTAIN WOLFIE spawns a faucet
- faucet_rules.json defines allowed models
- agent.php enforces the restriction

This ensures:
- deterministic model usage
- no accidental cross-contamination
- no unsafe model invocation

---

## Faucet Rules & Aliases

Aliases inherit faucet rules from their parent unless overridden.

Rules:
- parent rules apply first
- alias may add restrictions
- alias may not remove safety restrictions

---

## Faucet Rules & Doctrine

Faucet rules must never:
- contradict classification_json
- contradict capabilities.json
- contradict system_prompt.txt
- contradict agent layer
- contradict doctrine

Faucet rules must always:
- enforce safety
- enforce capability boundaries
- enforce model boundaries
- enforce classification boundaries

---
