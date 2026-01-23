---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.46
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
