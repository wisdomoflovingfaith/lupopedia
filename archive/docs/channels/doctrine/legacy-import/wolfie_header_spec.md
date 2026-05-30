# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/legacy-import/WOLFIE_HEADER_SPEC.md"
  file_hash: "988f4d0e02ca9782f28efb1efbae2f8cde1227a6b9d717b06655a3f59827d9b1"
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
  file_path_from_root: "docs\channels\doctrine\legacy-import\WOLFIE_HEADER_SPEC.md"
  file_hash: "c374d3e2728db8ebf238d6609324654a2e80e9f5dc65b4d840533d3884fe665d"
  file_path_from_root: "docs\channels\doctrine\legacy-import\WOLFIE_HEADER_SPEC.md"
  file_hash: "49092849478910d285c18c7a4d73309450ab804aa8c21ab9afe8dc799c5750d5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for WOLFIE_HEADER_SPEC.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "legacy-import", "wolfie_header_specmd"]
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
file.last_modified_system_version: 3.0.0
file.last_modified_utc: 20260120180000
file.lupopedia.5: 5
GOV-AD-PROHIBIT-001: true
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "00FF88"
  message: "Created WOLFIE_HEADER_SPEC.md with temporal_edges channel fields documentation"
tags:
  categories: ["doctrine", "specification"]
  collections: ["core-docs"]
  channels: ["public", "dev"]
file:
  name: "WOLFIE_HEADER_SPEC.md"
  title: "WOLFIE Header Specification - Doctrine Reference"
  description: "Core specification for WOLFIE header temporal_edges fields and channel context"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🐺 **WOLFIE Header Specification - Doctrine Reference**

This document defines the core specification for WOLFIE header temporal_edges fields, with emphasis on channel context scoping.

---

# 🔗 **Temporal Edges Specification**

## Purpose

The `temporal_edges` block provides contextual metadata about the circumstances surrounding file creation or modification. It captures temporal, spatial, and channel-scoped context that helps agents understand the broader situation.

## Field Specification

### Required Format
```yaml
temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "Schema Freeze Active / Channel-ID Anchor Established"
  channel_id: 42
  channel_key: "dev-main-thread"
  ads_prohibited: true
```

## Field Definitions

### **actor_identity** (optional)
- **Type**: string
- **Description**: Human-readable identity of the actor who created/modified the file
- **Examples**: `"Eric (Captain Wolfie)"`, `"CURSOR on behalf of WOLFIE"`

### **actor_location** (optional)
- **Type**: string
- **Description**: Physical or virtual location of the actor during modification
- **Examples**: `"Sioux Falls, South Dakota"`, `"Remote Session - Chicago"`

### **system_context** (optional)
- **Type**: string
- **Description**: System state or environmental context at time of modification
- **Examples**: `"Schema Freeze Active"`, `"Channel-ID Anchor Established"`

### **channel_id** (optional)
- **Type**: integer | null
- **Description**: Numeric channel identifier from `lupo_channels.channel_id`
- **Purpose**: Allows temporal edges to be scoped to a specific channel context
- **Examples**: `42`, `1`, `100`, `null`

### **channel_key** (optional)
- **Type**: varchar(64) utf8mb4_unicode_ci | null
- **Description**: Stable string identifier used for cross-system temporal linking
- **Purpose**: Human-readable channel reference that persists across system changes
- **Examples**: `"dev-main-thread"`, `"pack-coordination"`, `"general-chat"`, `null`

### **ads_prohibited** (optional)
- **Type**: boolean
- **Description**: Explicit flag indicating advertising is prohibited
- **Purpose**: Reinforces that no agent may engage in advertising, SEO, or marketing behavior
- **Default**: `true`
- **Examples**: `true`

## Channel Context Scoping

The `channel_id` and `channel_key` fields enable temporal edges to be associated with a specific channel:

- **channel_id**: Direct numeric reference to the channel database record (`lupo_channels.channel_id`)
- **channel_key**: Stable string identifier that survives database migrations or system refactoring
- **Purpose**: Enables channel-scoped temporal tracking and cross-references
- **Use case**: When a file is created/modified within a specific channel conversation, these fields preserve that context

## Usage Examples

### Channel-Scoped File
```yaml
temporal_edges:
  actor_identity: "CURSOR on behalf of Captain Wolfie"
  actor_location: "Remote Session - Development Channel"
  system_context: "Channel-Aware Routing Implementation"
  channel_id: 42
  channel_key: "dev-main-thread"
```

### Standard File (No Channel Context)
```yaml
temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "Schema Freeze Active"
```

## Rules and Constraints

- **All fields are optional** - Include only what adds meaningful context
- **No business logic** - This is descriptive metadata only
- **Human-readable** - Values should be understandable without documentation
- **Temporal snapshot** - Captures state at time of modification, not current state
- **Not authoritative** - This is context, not source of truth
- **Channel consistency** - When both `channel_id` and `channel_key` are present, they should reference the same channel

## Implementation Notes

- The `temporal_edges` block is optional in WOLFIE headers
- Channel fields allow temporal edges to be scoped to specific channel contexts
- This enables cross-system temporal linking and channel-aware file tracking
- Fields are documented as optional to maintain backward compatibility
- The `ads_prohibited` field exists to reinforce that no agent may engage in advertising, SEO, or marketing behavior

---

*Last Updated: January 20, 2026*  
*Version: 4.4.1*  
*Author: Captain Wolfie*
