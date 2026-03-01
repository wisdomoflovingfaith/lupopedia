# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/database/lupopedia/tables/README.md"
  system_version: "4.0.52"
  last_modified_utc: "20260301120000"
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  purpose: "Documentation directory structure and TOON mapping for Lupopedia database tables"
  dialog_message: "Clarified documentation structure - CHANNEL_SYSTEM_TLDR.md is reference guide, not table documentation"
  mood_rgb: "4169E1"
  traits: ["documentation", "structure", "v4.0.52"]
  tags: ["documentation", "database", "tables", "toon_mapping"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "docs/toons/", type: "references", weight: 1.0 }
    - { to: "docs/database/lupopedia/tables/", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/DATABASE_DOCTRINE.md", type: "references", weight: 0.9 }
  semantic_tags: ["documentation", "database", "tables", "toon_mapping"]

flare.footer:
  version: "4.0.52"
  last_verified: "20260301"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\README.md"
  file_hash: "6efa3c090319077177df11150127dd8fe7e6ba7bd69023e66dc3154ba386cc2b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "readmemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
flare.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/README.md",
  file_hash: "8f1fb34e808280430e8ccb0e137529dba20fa82194647b131cda1a0e41b692a5"
  system_version: "4.0.50"
  channel_id: 42,
  actor_id: 1003,
  last_modified_utc: "20260227",
  delegation_chain: "10000:1003",
  artifact_type: "documentation",
  purpose: "Index and overview for Lupopedia database table documentation",
  mood_rgb: "00FF00",
  traits: ["canonical", "documentation", "index", "v4.0.48", "history-update"],
  tags: ["database", "schema", "documentation", "index", "history-update"],
  lupo_agent: "antigravity"
}
flare.edges: {
  file_path_from_root: "docs\database\lupopedia\tables\README.md"
  outbound_edges: [
    { to: "docs/channels/appendix/HISTORY.md", type: "references", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["database_index", "schema_documentation"]
}
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer: {
  last_verified_utc: "20260227",
  last_verified_by: "antigravity"
}
---

# Database Tables Documentation

## Directory Structure

This directory contains comprehensive documentation for all Lupopedia database tables. Each table has corresponding TOON schema files in `docs/toons/` that serve as the authoritative source of truth for table structure.

## Documentation Types

### 1. Table Documentation Files
These files provide detailed documentation for specific database tables:

- **Format**: `lupo_{table_name}.md`
- **Purpose**: Comprehensive table documentation with schema, usage patterns, and integration examples
- **Authority**: References corresponding TOON schema files
- **Examples**: SQL operations, PHP integration, and best practices

### 2. Reference Documentation Files
These files provide cross-cutting documentation and references:

- **README.md**: This file - directory overview and structure guide
- **CHANNEL_SYSTEM_TLDR.md**: Quick reference guide for channel system operations (NOT table documentation)
- **MIGRATION_MAPPING_REFERENCE.md**: Legacy to modern table mapping
- **actor_reply_templates.md**: Actor communication templates

### 3. TOON Schema Files
Located in `docs/toons/` - these are the authoritative schema definitions:

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