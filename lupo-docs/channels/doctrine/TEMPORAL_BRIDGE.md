# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/doctrine/TEMPORAL_BRIDGE.md"
  file_hash: "62ccd41f73f785fdb9a2fa1d2ebbaaa4e00250f8bd84c653b0731d9b0989a962"
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
  file_path_from_root: "lupo-docs\channels\doctrine\TEMPORAL_BRIDGE.md"
  file_hash: "3bd85018eae31d39976699eaceb775e84d9f67a8d48c6474394739ef62b65847"
  file_path_from_root: "lupo-docs\channels\doctrine\TEMPORAL_BRIDGE.md"
  file_hash: "74b977568859c87204775b426f9975179b4af67e83941d13409fc7362c24a254"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for TEMPORAL_BRIDGE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "temporal_bridgemd"]
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
  message: "Created TEMPORAL_BRIDGE.md as stable anchor for all time-related reasoning across agents and subsystems."
  mood: "00FF00"
tags:
  categories: ["documentation", "governance", "temporal"]
  collections: ["core-docs"]
  channels: ["dev", "governance"]
file:
  title: "Temporal Bridge"
  description: "Single source of truth for temporal alignment across agents and subsystems"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# TEMPORAL BRIDGE

**Purpose:** Provide a single source of truth for temporal alignment across agents and subsystems.

## Overview

The Temporal Bridge ensures all events, dialogs, migrations, and system states reference verified timestamps.

## Channels vs Bridges

**Important:** Channels and bridges are separate concepts in Lupopedia:

- **Channels** (`channels:` field in WOLFIE Headers) represent routing and organizational metadata. They indicate where content belongs in the system's communication structure (e.g., `["dev", "governance"]`). Channels are used for organizational purposes and should NOT include bridge names.

- **Bridges** (like TEMPORAL_BRIDGE.md) are governance anchors that provide stable reference points for agent decision-making. Bridges are referenced in file content, not in the `channels:` field. Files may reference bridges in their documentation content (e.g., "See TEMPORAL_BRIDGE.md for temporal alignment"), but bridges themselves are not channels.

This bridge file uses `channels: ["dev", "governance"]` to indicate its organizational placement, while the bridge itself serves as a governance anchor that other files reference in their content.

## Fields

- `event_timestamp`: `<YYYYMMDDHHIISS>`
- `source_of_truth`: `<file or subsystem>`
- `verified_by`: `<human or agent>`
- `temporal_confidence`: `<high|medium|low>`

## Rules

- All agents must reference this bridge when reasoning about time.
- No agent may infer missing timestamps.
- Human verification overrides all automated timestamps.

## Example Entry

```
event_timestamp: 20260116084500
source_of_truth: "CHANGELOG.md"
verified_by: "Captain Wolfie"
temporal_confidence: "high"
```

## Usage

When an agent needs to reason about time:
1. Check this bridge for verified timestamps
2. If no entry exists, escalate to MASTER_BRIDGE.md
3. Never infer or guess timestamps
4. Always cite source_of_truth when using temporal data

## Maintenance

- Only humans may add entries
- Entries must be verified before addition
- Temporal confidence must be explicitly stated
- Outdated entries should be marked, not deleted
