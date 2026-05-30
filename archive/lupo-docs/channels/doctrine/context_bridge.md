# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/doctrine/CONTEXT_BRIDGE.md"
  file_hash: "cf43eb5990ba0075302557e6cf77b8d62d20b837e8cff6a6eaac7b0eb21a6dae"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\doctrine\CONTEXT_BRIDGE.md"
  file_hash: "4798fb7c199bdc3e6a25b34de0b7cae707c0701cd72377a6f001ad8ea3c04262"
  file_path_from_root: "lupo-docs\channels\doctrine\CONTEXT_BRIDGE.md"
  file_hash: "5114bbdca03c6245cbdd0fe948092cf9a197f709e6a6e7b2bca00dfa8ad54c12"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CONTEXT_BRIDGE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "context_bridgemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.46
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created CONTEXT_BRIDGE.md as anchor for all dialog and escalations to prevent context drift."
  mood: "00FF00"
tags:
  categories: ["documentation", "governance", "context"]
  collections: ["core-docs"]
  channels: ["dev", "governance"]
file:
  title: "Context Bridge"
  description: "Anchor all dialog and escalations to explicit context to prevent context drift"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# CONTEXT BRIDGE

**Purpose:** Anchor all dialog and escalations to explicit context.

## Overview

The Context Bridge prevents context drift by requiring every message to include its triggering reason.

## Channels vs Bridges

**Important:** Channels and bridges are separate concepts in Lupopedia:

- **Channels** (`channels:` field in WOLFIE Headers) represent routing and organizational metadata. They indicate where content belongs in the system's communication structure (e.g., `["dev", "governance"]`). Channels are used for organizational purposes and should NOT include bridge names.

- **Bridges** (like CONTEXT_BRIDGE.md) are governance anchors that provide stable reference points for agent decision-making. Bridges are referenced in file content, not in the `channels:` field. Files may reference bridges in their documentation content (e.g., "See CONTEXT_BRIDGE.md for context anchoring"), but bridges themselves are not channels.

This bridge file uses `channels: ["dev", "governance"]` to indicate its organizational placement, while the bridge itself serves as a governance anchor that other files reference in their content.

## Fields

- `trigger`: `<event or condition>`
- `context_summary`: `<short explanation>`
- `relevance_window`: `<time or version range>`
- `required_human_input`: `<yes|no>`

## Rules

- No agent may escalate without a declared trigger.
- Context must be concise and human-readable.
- Relevance windows must be explicit.

## Example Entry

```
trigger: "Routing drift detected"
context_summary: "Dialog system produced inconsistent targets"
relevance_window: "v3.0.40–v3.0.45"
required_human_input: "yes"
```

## Usage

When an agent needs to escalate or send dialog:
1. Declare the trigger that caused the action
2. Provide a concise context summary
3. Specify the relevance window (time or version range)
4. Indicate if human input is required

## Maintenance

- Context entries should be linked to specific events
- Relevance windows must be kept current
- Outdated contexts should be archived, not deleted
- Human-required contexts take priority
