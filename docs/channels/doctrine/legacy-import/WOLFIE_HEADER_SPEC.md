---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 2026.1.0.1
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
