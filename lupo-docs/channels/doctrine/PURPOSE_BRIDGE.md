# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\PURPOSE_BRIDGE.md"
  file_hash: "eba7951bfb369d2f2f8d307bb7bdf09ffd577178546deaf4954c4126fa84d464"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\doctrine\PURPOSE_BRIDGE.md"
  file_hash: "9bc91c56f5c91f67a24a699b6b8b75e091afb6a9a0e45b124681d17984016cc5"
  file_path_from_root: "docs\channels\doctrine\PURPOSE_BRIDGE.md"
  file_hash: "b521b7b015b7c331f4dfae7709374759129ef8ba6f88bcb2467a40b62d8dfd53"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for PURPOSE_BRIDGE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "purpose_bridgemd"]
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