# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/IDENTITY_BRIDGE.md"
  file_hash: "ea5cd1f8a873fe6b8a334ec5420d87501aaffe689cd1f3ea08ac155d2d10b303"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "docs\channels\doctrine\IDENTITY_BRIDGE.md"
  file_hash: "43f6213d8959faf474dee55fb21b84213a858bdbeba3ec00f187ae94149c71f0"
  file_path_from_root: "docs\channels\doctrine\IDENTITY_BRIDGE.md"
  file_hash: "365ebc8aa62d80d5594b2bc61b88ad6b5ffb1ad348d53723399c8feb959f373e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for IDENTITY_BRIDGE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "identity_bridgemd"]
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
channel_key: system/kernel
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created IDENTITY_BRIDGE.md as stable anchor for identity resolution across agents and personas."
  mood: "00FF00"
tags:
  categories: ["documentation", "governance", "identity"]
  collections: ["core-docs"]
  channels: ["dev", "governance"]
file:
  title: "Identity Bridge"
  description: "Maintain stable identity resolution across agents and personas"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# IDENTITY BRIDGE

**Purpose:** Maintain stable identity resolution across agents and personas.

## Overview

The Identity Bridge ensures all dialog includes explicit speaker, target, and persona information.

## Channels vs Bridges

**Important:** Channels and bridges are separate concepts in Lupopedia:

- **Channels** (`channels:` field in WOLFIE Headers) represent routing and organizational metadata. They indicate where content belongs in the system's communication structure (e.g., `["dev", "governance"]`). Channels are used for organizational purposes and should NOT include bridge names.

- **Bridges** (like IDENTITY_BRIDGE.md) are governance anchors that provide stable reference points for agent decision-making. Bridges are referenced in file content, not in the `channels:` field. Files may reference bridges in their documentation content (e.g., "See IDENTITY_BRIDGE.md for identity resolution"), but bridges themselves are not channels.

This bridge file uses `channels: ["dev", "governance"]` to indicate its organizational placement, while the bridge itself serves as a governance anchor that other files reference in their content.

## Fields

- `speaker`: `<agent or human>`
- `target`: `<agent or human>`
- `persona_mode`: `<default|shadow|system|wolfie|ara>`
- `authority_level`: `<system|governance|expressive|observer>`

## Rules

- No dialog may be emitted without identity fields.
- Persona mode must be declared explicitly.
- Authority levels determine escalation paths.

## Example Entry

```
speaker: "ROSE"
target: "Captain Wolfie"
persona_mode: "default"
authority_level: "expressive"
```

## Persona Modes

- **default**: Standard operational persona
- **shadow**: Background/observational mode
- **system**: System-level operations
- **wolfie**: Captain Wolfie persona
- **ara**: ARA-specific persona

## Authority Levels

- **system**: System-critical operations
- **governance**: Governance and doctrine enforcement
- **expressive**: Creative/expressive operations
- **observer**: Read-only observation

## Usage

When an agent emits dialog:
1. Explicitly declare speaker identity
2. Explicitly declare target identity
3. Specify persona mode
4. Declare authority level

## Maintenance

- Identity mappings must be verified
- Persona modes must be documented
- Authority levels determine action permissions
- Identity drift must be escalated to MASTER_BRIDGE.md
