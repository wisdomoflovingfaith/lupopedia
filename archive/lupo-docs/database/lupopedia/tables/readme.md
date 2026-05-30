# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/README.md"
  system_version: "4.0.52"
  last_modified_utc: "20260301120000"
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  purpose: "Index and overview for Lupopedia database table documentation"
  namespace: "core"
  dialog_message: "Consolidated redundant headers and merged outbound edges for canonical documentation index."
  mood_vector: "4169E1"
  traits: ["canonical", "documentation", "index", "structure", "v4.0.52"]
  tags: ["database", "schema", "documentation", "index", "tables", "toon_mapping"]
  lupo_agent: "gemini-cli"
  actor_id: 1006

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/DATABASE_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/channels/appendix/HISTORY.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.8 }
  semantic_tags: ["database_index", "schema_documentation", "toon_mapping"]

lupopedia.footer:
  version: "4.0.52"
  approved_for_version: "4.1.0"
  approved_for_version_utc: "20260327103238"
  approved_for_version_by: "Cursor IDE Agent (Lead Orchestration)"
  approved_for_version_by_actor_id: 102
  last_verified: "20260301"
  last_verified_by: "gemini-cli"
---


# Database Tables Documentation

## Directory Structure

This directory contains comprehensive documentation for all Lupopedia database tables. Each table has corresponding TOON schema files in `lupo-database/lupopedia/toon/` that serve as the authoritative source of truth for table structure.

## Documentation Types

### 1. Table Documentation Files
These files provide detailed documentation for specific database tables:

- **Format**: `lupo_{table_name}.md`
- **Purpose**: Comprehensive table documentation with schema, usage patterns, and integration examples
- **Authority**: References corresponding TOON schema files
- **Examples**: SQL operations, PHP integration, and best practices

### 1.1 Required Section Standard (4.0.88)

Each table documentation file MUST include these sections:

- Table Purpose
- Column Definitions (name, type, nullable, default)
- Relationships (including application-managed relationships)
- Usage Examples
- Edge Interaction Explanation (how `lupo_edges` participates, if applicable)

Rules:

- TOON remains schema authority for field truth.
- Table docs are mandatory interpretation and usage surface for agents.
- Missing required sections is a documentation gap and must be tracked for remediation.

### 2. Reference Documentation Files
These files provide cross-cutting documentation and references:

- **README.md**: This file - directory overview and structure guide
- **lupopedia_actors_collections_organization_reference.md**: Canonical cross-table reference for actor, collection, and organization schema structure and doctrine constraints
- **CHANNEL_SYSTEM_TLDR.md**: Quick reference guide for channel system operations (NOT table documentation)
- **MIGRATION_MAPPING_REFERENCE.md**: Legacy to modern table mapping
- **SESSION_MANAGEMENT_SYSTEM.md**: Multi-agent isolation and sync guide
- **actor_reply_templates.md**: Actor communication templates

### 3. TOON Schema Files
Located in `lupo-database/lupopedia/toon/` - these are the authoritative schema definitions:

- **Format**: `{table_name}.toon.json`
- **Purpose**: JSON schema definitions with field types, indexes, and constraints
- **Authority**: Single source of truth for table structure
- **Validation**: Used by root boot agent for schema compliance

## Current Table Coverage

### Channel Tables (7 documented)
- `lupo_channels.md` - Primary channel definitions
- `lupo_channel_content.md` - Federation node content management
- `lupo_channel_state.md` - Channel state tracking
- `lupo_channel_logs.md` - Comprehensive event logging
- `lupo_channel_files.md` - File management and tracking
- `lupo_channel_escalations.md` - Governance and rule enforcement
- `lupo_channel_boot_lifecycle.md` - Modern channel initialization
- `lupo_channel_tables_overview.md` - Comprehensive overview

[Additional channel-related documentation exists but corresponds to reference guides, not individual table documentation]

---
*Maintained by Antigravity (Actor 1003)*

