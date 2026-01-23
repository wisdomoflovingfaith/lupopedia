---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.46
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created PURPOSE_BRIDGE.md as anchor for intent and scope to prevent runaway behavior."
  mood: "00FF00"
tags:
  categories: ["documentation", "governance", "purpose"]
  collections: ["core-docs"]
  channels: ["dev", "governance"]
file:
  title: "Purpose Bridge"
  description: "Ensure all actions and messages have a declared intent and scope"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# PURPOSE BRIDGE

**Purpose:** Ensure all actions and messages have a declared intent.

## Overview

The Purpose Bridge prevents runaway behavior by requiring explicit intent and scope.

## Channels vs Bridges

**Important:** Channels and bridges are separate concepts in Lupopedia:

- **Channels** (`channels:` field in WOLFIE Headers) represent routing and organizational metadata. They indicate where content belongs in the system's communication structure (e.g., `["dev", "governance"]`). Channels are used for organizational purposes and should NOT include bridge names.

- **Bridges** (like PURPOSE_BRIDGE.md) are governance anchors that provide stable reference points for agent decision-making. Bridges are referenced in file content, not in the `channels:` field. Files may reference bridges in their documentation content (e.g., "See PURPOSE_BRIDGE.md for intent declaration"), but bridges themselves are not channels.

This bridge file uses `channels: ["dev", "governance"]` to indicate its organizational placement, while the bridge itself serves as a governance anchor that other files reference in their content.

## Fields

- `intent`: `<goal or purpose>`
- `scope`: `<file|directory|subsystem>`
- `allowed_actions`: `<list>`
- `forbidden_actions`: `<list>`

## Rules

- No agent may act without a declared intent.
- Scope must be narrow and explicit.
- Forbidden actions override allowed actions.

## Example Entry

```
intent: "Verify routing"
scope: "dialogs/*"
allowed_actions:
  - "read"
  - "report"
forbidden_actions:
  - "modify"
  - "rewrite"
```

## Usage

When an agent needs to act:
1. Declare explicit intent
2. Specify narrow scope
3. List allowed actions
4. List forbidden actions (if any)
5. Forbidden actions always override allowed actions

## Common Scopes

- `file`: Single file operation
- `directory`: Directory-level operation
- `subsystem`: Entire subsystem operation
- `database`: Database operation
- `dialog`: Dialog system operation

## Common Actions

- `read`: Read-only access
- `write`: Write access
- `modify`: Modify existing content
- `create`: Create new content
- `delete`: Delete content
- `report`: Generate report only
- `verify`: Verification only

## Maintenance

- Intents must be clear and specific
- Scopes must be as narrow as possible
- Forbidden actions take precedence
- Unclear intents must escalate to MASTER_BRIDGE.md
