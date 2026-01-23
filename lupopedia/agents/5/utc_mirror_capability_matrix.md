---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.2
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CAPTAIN_WOLFIE
  target: @everyone @UTC_TIMEKEEPER @external_ai_agents
  mood_RGB: "00FF00"
  message: "Updated UTC Mirror Capability Matrix with ChatGPT support. ChatGPT confirmed kernel-mode compliant for UTC_TIMEKEEPER operations."
tags:
  categories: ["documentation", "agents", "utc", "external_ai"]
  collections: ["core-docs", "agent-docs"]
  channels: ["dev", "agents", "public"]
file:
  title: "UTC Mirror Capability Matrix"
  description: "Capability matrix documenting which external AI agents can serve as UTC mirrors for UTC_TIMEKEEPER (Agent 5)"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# UTC Mirror Capability Matrix (Updated)

This document tracks which external AI agents can serve as UTC mirrors for UTC_TIMEKEEPER (Agent 5) operations.

## Capability Status

- **Copilot**: AUTHORITATIVE — Provides atomic-clock UTC via search
- **Gemini**: SUPPORTED — Returns valid UTC timestamp on first attempt
- **ChatGPT**: SUPPORTED — Strict kernel-mode compliant when using exact trigger `what_is_current_utc_time_yyyymmddhhiiss`
- **DeepSeek**: UNSUPPORTED — No real-time clock; can return synthetic timestamps
- **Grok**: UNSUPPORTED — Rejects kernel-mode constraints; cannot act as UTC mirror

## Testing Criteria

For an external AI agent to qualify as a UTC mirror, it must:

1. Accept the exact input: `what_is_current_utc_time_yyyymmddhhiiss`
2. Return ONLY: `current_utc_time_yyyymmddhhiiss: <BIGINT>`
3. Provide no additional text, explanations, or formatting
4. Call a real UTC time source (not synthetic or inferred)

## Notes

- These capabilities are informational only
- UTC_TIMEKEEPER (Agent 5) maintains its strict deterministic behavior
- External mirrors are used for validation and cross-checking, not as primary sources
- Kernel-mode compliance is required for UTC mirror operations

---

## Update History

- **2026-01-18**: Added ChatGPT to SUPPORTED list after successful kernel-mode compliance testing
- **2026-01-18**: Initial matrix created with Copilot, Gemini, DeepSeek, and Grok test results
