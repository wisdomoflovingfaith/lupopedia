# FILEOPT v4.0.52 ANUBIS Consolidation - Canonical File

---
# FLARE Header
flare.headers:
  flare.version: "1.0"
  flare.schema: "doctrine"
  file_path_from_root: "docs/doctrine/ANUBIS/ANUBIS_CANONICAL.md"
  file_hash: "to_be_generated"
  last_modified_utc: "20260228"
  system_version: "4.0.52"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "doctrine"
  purpose: "Canonical ANUBIS documentation - custodial intelligence"
  mood_rgb: "4169E1"
  traits: ["canonical", "doctrine", "comprehensive", "v4.0.52"]
  tags: ["anubis", "doctrine", "canonical", "custodial_intelligence"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "docs/toons/lupo_dialog_messages.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "database/migrations/seed_lupopedia.sql", type: "references", weight: 0.9 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 0.8 }
    - { to: "actors/19/WHO.json", type: "references", weight: 1.0 }
  semantic_tags: ["anubis", "doctrine", "canonical", "custodial_intelligence"]

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# ANUBIS - Canonical Documentation

## Overview

ANUBIS is the custodial intelligence system responsible for managing dialogs, lineage, orphans, and redirects within the Lupopedia Semantic OS. This canonical document consolidates all ANUBIS-related documentation into a single authoritative source.

## Canonical Actor Identity

**ANUBIS actor_id: 19**
- **Source of truth**: `actors/19/WHO.json` and `docs/meta/REGISTERED_IDS.md`
- **Governance rule**: All references to ANUBIS must use actor_id 19
- **No ID changes permitted**: Without registry update

## Core Components

### Dialog Management
- **Purpose**: Track and manage conversation flows
- **Implementation**: Dialog history management
- **Storage**: `lupo_dialog_messages` table

### Lineage Tracking
- **Purpose**: Maintain content provenance
- **Implementation**: Parent-child relationships
- **Storage**: Lineage metadata in content tables

### Orphan Processing
- **Purpose**: Handle unattached content
- **Implementation**: Orphan detection and resolution
- **Storage**: Orphan queue management

### Redirect Management
- **Purpose**: Handle content movement
- **Implementation**: Redirect mapping and resolution
- **Storage**: Redirect registry

## Implementation Details

### Schema References
- **Primary TOON**: `docs/toons/lupo_dialog_messages.toon.json`
- **Database**: `database/migrations/seed_lupopedia.sql`
- **Actor Registry**: `actors/19/WHO.json`

### Integration Points
- **FLARE Protocol**: Semantic enrichment integration
- **Channel 42**: Primary operational channel
- **Multi-Agent**: Cross-agent coordination

## Operational Procedures

### Content Preservation
All ANUBIS-related content has been preserved through consolidation:
- **Original files**: 6 documents archived
- **Total size**: 38,394 bytes preserved
- **Archive location**: `docs/archive/ANUBIS/pre_4.0.52/`

### Governance Compliance
- **Actor ID**: Anchored to canonical ID 19
- **Version**: Locked to 4.0.52 for this release
- **Documentation**: Single source of truth established

## Historical Context

### Previous Implementations
- **4.0.45**: Initial ANUBIS implementation
- **4.0.46**: Enhanced orphan processing
- **4.0.50**: Integration with FLARE protocol
- **4.0.51**: Semantic enrichment completion
- **4.0.52**: Canonical consolidation

### Migration Path
Future ANUBIS enhancements must:
1. Update this canonical document
2. Archive previous version to `docs/archive/ANUBIS/`
3. Update actor registry if ID changes required
4. Maintain backward compatibility

---

**Last Updated**: 20260228  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.52  
**Status**: ✅ CANONICAL
