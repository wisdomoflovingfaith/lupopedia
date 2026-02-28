# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\doctrine\MASTER_BRIDGE.md"
  file_hash: "07b6bed223c3f2f1cb3a4f259bcac2b368a28a06fd5d2ce380d80abb0e7d15e7"
  file_path_from_root: "docs\channels\doctrine\MASTER_BRIDGE.md"
  file_hash: "c2f60ca6154a50a074cc740e2dd71dd5844b43e35f7f4527f23bd819042770ae"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for MASTER_BRIDGE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "master_bridgemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.46
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created MASTER_BRIDGE.md as central escalation point for STOP flags, uncertainty, and human-required decisions."
  mood: "00FF00"
tags:
  categories: ["documentation", "governance", "escalation"]
  collections: ["core-docs"]
  channels: ["dev", "governance"]
file:
  title: "Master Bridge"
  description: "Central escalation point for STOP flags, uncertainty, and human-required decisions"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# MASTER BRIDGE

**Purpose:** Central escalation point for STOP flags, uncertainty, and human-required decisions.

## Overview

The Master Bridge is the authoritative channel for all agents when encountering drift, ambiguity, or STOP conditions.

## Channels vs Bridges

**Important:** Channels and bridges are separate concepts in Lupopedia:

- **Channels** (`channels:` field in WOLFIE Headers) represent routing and organizational metadata. They indicate where content belongs in the system's communication structure (e.g., `["dev", "governance"]`). Channels are used for organizational purposes and should NOT include bridge names.

- **Bridges** (like MASTER_BRIDGE.md) are governance anchors that provide stable reference points for agent decision-making. Bridges are referenced in file content, not in the `channels:` field. Files may reference bridges in their documentation content (e.g., "See MASTER_BRIDGE.md for escalation"), but bridges themselves are not channels.

This bridge file uses `channels: ["dev", "governance"]` to indicate its organizational placement, while the bridge itself serves as a governance anchor that other files reference in their content.

## Fields

- `escalation_reason`: `<description>`
- `agent_reporting`: `<agent name>`
- `human_required`: `<yes|no>`
- `status`: `<open|acknowledged|resolved>`

## Rules

- All STOP conditions must be routed here.
- Human-required = yes halts all autonomous behavior.
- Only Captain Wolfie may mark an escalation as resolved.

## Example Entry

```
escalation_reason: "Identity drift detected"
agent_reporting: "LILITH"
human_required: "yes"
status: "open"
```

## Escalation Triggers

Agents must escalate to MASTER_BRIDGE when:
- STOP.flag is detected
- Identity drift is detected
- Temporal uncertainty exceeds threshold
- Context cannot be resolved
- Purpose/intent is ambiguous
- Forbidden action is requested
- System state is inconsistent

## Status Flow

1. **open**: Initial escalation, awaiting human review
2. **acknowledged**: Human has seen the escalation
3. **resolved**: Issue has been addressed (only by Captain Wolfie)

## Human Required Flag

When `human_required: "yes"`:
- All autonomous agent behavior must halt
- Only read-only operations may continue
- System waits for human intervention
- No agent may proceed without explicit human approval

## Usage

When an agent encounters a condition requiring escalation:
1. Create entry in MASTER_BRIDGE.md
2. Set `human_required: "yes"` if action is needed
3. Set `status: "open"`
4. Halt autonomous operations if human_required = yes
5. Wait for human acknowledgment/resolution

## Maintenance

- Only Captain Wolfie may resolve escalations
- All escalations must be logged
- Resolved escalations should be archived
- Pattern analysis should identify recurring issues

## Fleet Silence Protocol

**See:** `ASK_HUMAN_WOLFIE_LUPOPEDIA_20-26.md` for complete Fleet Silence Protocol v1.0

**Key Directives:**
- **One-Voice Protocol:** Only one IDE agent active at a time
- **Fleet Silence Rule:** No changelog fluff or contextual summaries unless asked
- **Doctrine-First:** Auto-reject suggestions violating 12 Critical Atoms
- **Cognitive Load Protection:** Clarity over max velocity

**Status:** ACTIVE (Effective 2026-01-16)

## Related Bridges

- **TEMPORAL_BRIDGE.md**: For temporal uncertainty escalations
- **CONTEXT_BRIDGE.md**: For context drift escalations
- **IDENTITY_BRIDGE.md**: For identity drift escalations
- **PURPOSE_BRIDGE.md**: For intent ambiguity escalations