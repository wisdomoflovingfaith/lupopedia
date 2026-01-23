---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.46
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
relevance_window: "v4.0.40–v4.0.45"
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
