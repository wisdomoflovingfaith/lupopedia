# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "channels/42/content/federation_node_id/0/FLARE.md"
  file_hash: "to_be_generated"
  federation_node_id: 0
  web_path: "http://www.lupopedia.com/FLARE"
  last_updated_utc: "20260301"
  system_version: "4.0.52"
  channel_id: 42
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  purpose: "Root FLARE definition for federation node 0"
  mood_rgb: "4169E1"
  traits: ["canonical", "federation", "v4.0.52"]
  tags: ["flare", "federation", "node_0", "canonical"]

flare.edges:
  outbound_edges:
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "docs/api/FLARE_API.md", type: "references", weight: 0.9 }
    - { to: "actors/registry.json", type: "references", weight: 0.8 }
    - { to: "database/migrations/install_lupopedia.sql", type: "references", weight: 0.7 }
  semantic_tags: ["flare", "federation", "canonical", "protocol"]

flare.footer:
  last_verified: "20260301"
  last_verified_by: "windsurf"
---

# FLARE System Documentation

## Overview

FLARE (Federated Learning and Relationship Exchange) is a protocol for file-level attribute and relationship exchange within the Lupopedia Semantic OS. This document serves as the canonical FLARE definition for federation node 0.

## Core Components

### FLARE Header Schema

The FLARE header provides comprehensive metadata about files, including:

- **flare.version**: Protocol version (currently "1.0")
- **flare.schema**: Schema type (e.g., "documentation", "table", "component")
- **file_path_from_root**: Exact file path from repository root
- **file_hash**: SHA256 hash for integrity verification
- **federation_node_id**: Federation node identifier
- **web_path**: Canonical web URL for the resource
- **last_updated_utc**: Last modification timestamp (YYYYMMDD format)
- **system_version**: Lupopedia system version
- **channel_id**: Channel identifier (42 for development)
- **actor_id**: Actor identifier from registry
- **delegation_chain**: Actor delegation chain
- **flare.routing**: Object tracking artifact delivery, authority, channel_id, thread_id, and traversal (v4.0.55)
- **flare.lists**: Object tracking associated CSV lists (added in v4.0.55)
  - **file.dialog**: Path to CSV discussion/transcript history
  - **file.history**: Path to CSV change/commit history
  - **file.actors**: Path to associated actors list CSV
- **artifact_type**: Type of artifact (documentation, code, etc.)
- **purpose**: One-sentence description of file purpose

### FLARE Edges

The edges system defines relationships between files:

- **outbound_edges**: References from this file to others
- **inbound_edges**: References to this file from others (auto-populated)
- **semantic_tags**: Categorization tags for search and discovery
- **Edge types**: references, implements, schema_reference, depends_on, etc.
- **Weight system**: 0.5-1.0 scale for relationship strength

### FLARE Footer

The footer provides engagement and verification metadata:

- **last_verified**: Verification timestamp
- **last_verified_by**: Actor who performed verification
- **Optional engagement metrics**: view_count, like_count, share_count

## Federation Integration

### Federation Node 0

This node serves as the root FLARE definition for the entire federation system:

- **Node ID**: 0
- **Web Path**: http://www.lupopedia.com/FLARE
- **Purpose**: Canonical FLARE protocol definition
- **Authority**: Root source for federation node metadata

### Web Path Mapping

FLARE files map to web paths using the following pattern:
- Repository path: `channels/42/content/federation_node_id/{node_id}/FLARE.md`
- Web path: `http://www.lupopedia.com/FLARE`

### Semantic OS Integration

FLARE integrates with the Semantic OS through:

- **Database Storage**: Content and relationships stored in `lupo_contents` and `lupo_edges` tables
- **Actor Registry**: Actor IDs and metadata managed in `actors/registry.json`
- **Channel System**: Federation nodes organized by channel structure
- **Web Resolution**: Canonical URLs provide persistent access to FLARE definitions

## Implementation Guidelines

### Required Fields

All FLARE headers must include:
- `flare.version`: "1.0"
- `flare.schema`: Appropriate schema type
- `file_path_from_root`: Exact repository path
- `system_version`: Current system version
- `channel_id`: Channel identifier
- `actor_id`: Valid actor ID from registry
- `delegation_chain`: Actor delegation chain
- `flare.routing`: Mandatory for multi-agent communication (v4.0.55+)
- `artifact_type`: Valid artifact type

### Optional Fields

Recommended optional fields:
- `flare.lists`: Object containing associated CSV lists
  - `file.dialog`: Path to CSV discussion logs
  - `file.history`: Path to CSV change history
  - `file.actors`: Path to associated actors list CSV
- `federation_node_id`: For federation node definitions
- `web_path`: Canonical web URL
- `purpose`: File purpose description
- `mood_rgb`: Emotional state indicator
- `traits`: File characteristics
- `tags`: Categorization tags

### Edge Guidelines

- **Weight Range**: 0.5-1.0 (weak to strong)
- **Edge Types**: Use appropriate relationship types
- **TOON References**: Reference schema files for database tables
- **Semantic Tags**: Descriptive, relationship-focused

---

**Last Updated**: 20260301  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.52  
**Status**: ✅ CANONICAL
