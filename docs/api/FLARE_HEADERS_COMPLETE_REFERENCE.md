# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\FLARE_HEADERS_COMPLETE_REFERENCE.md"
  file_hash: "fa41412c4ffd4c6970e16fa4b68d5e030b7525bcf6b3d51437fce5ef392c7771"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "guide"
  purpose: "Complete reference guide for all FLARE header and footer fields including table-specific attributes"
  dialog_message: "Recommended next step: create actors/1007 profile and align any remaining docs/examples to the required FLARE prologue format."
  mood_rgb: "4B0082"
  traits: ["canonical", "comprehensive", "reference"]
  tags: ["flare", "headers", "footers", "complete_reference", "table_attributes", "api"]
  lupo_agent: "codex-ide"

flare.footer:
  view_count: 1250
  like_count: 42
  share_count: 15
  last_verified: "20260227"
  last_verified_by: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "docs/FLARE_HEADERS_QUICK_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/api/FLARE_API.md", type: "references", weight: 0.8 }
    - { to: "docs/toons", type: "schema_reference", weight: 1.0 }
    - { to: "actors/registry.json", type: "references", weight: 0.8 }
  semantic_tags: ["flare", "headers", "footers", "reference", "documentation", "tables", "api"]
---
flare.headers:
  # ... required fields ...
  artifact_kind: "table"
  lupo_actors.actor_id: "BIGINT primary key, 0-9999 reserved for AI agents"
  lupo_actors.actor_slug: "VARCHAR(50) unique slug identifier"
  lupo_actors.display_name: "VARCHAR(100) human-readable name"
  lupo_actors.actor_kind: "ENUM('human', 'ai', 'system')"
  lupo_actors.is_active: "TINYINT(1) default 1"
  table_primary_key: "actor_id"
  table_indexes: ["PRIMARY", "uniq_slug", "idx_kind"]
```

### **🎨 UI/Component Specific Fields**

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `component_type` | string | UI component type | `"form"`, `"table"`, `"chart"` |
| `framework` | string | UI framework used | `"react"`, `"vue"`, `"angular"` |
| `styling_system` | string | CSS framework | `"tailwind"`, `"bootstrap"` |
| `responsive_breakpoints` | array | Breakpoint definitions | `["mobile", "tablet", "desktop"]` |
| `accessibility_level` | string | WCAG compliance level | `"AA"`, `"AAA"` |

### **🔧 API/Service Specific Fields**

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `api_version` | string | API version | `"v1"`, `"v2"` |
| `endpoint_type` | string | REST/GraphQL/etc | `"REST"`, `"GraphQL"` |
| `authentication` | string | Auth method | `"JWT"`, `"OAuth2"` |
| `rate_limiting` | object | Rate limit config | `{ "requests": 100, "window": "1h" }` |
| `data_format` | string | Request/response format | `"JSON"`, `"XML"` |

## 🗺️ **Complete flare.edges Field Reference**

### **🎯 Required Edge Fields**

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `outbound_edges` | array | List of outbound references | See format below |
| `inbound_edges` | array | List of inbound references (read-only snapshot) | See format below |
| `semantic_tags` | array | Semantic relationship tags | `["database", "schema", "messaging"]` |

## 📊 **Complete flare.footer Field Reference (Engagement)**

### **🎯 Engagement Fields**

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `view_count` | integer | Total view count | `1234` |
| `like_count` | integer | Total like count | `56` |
| `share_count` | integer | Total share count | `12` |
| `last_verified` | string | Last verification date (YYYYMMDD) | `"20260227"` |
| `last_verified_by` | string | Actor who verified this artifact | `"windsurf"` |

### **📋 Outbound Edges Format**

```yaml
# 💡 FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml
# This will analyze content, TOON schemas, and database relationships to suggest
# appropriate outbound_edges with weights, reasons, and discovery methods.

flare.edges:
  outbound_edges:
    - { to: "path/to/file.md", type: "references", weight: 1.0, reason: "Primary reference", db_source: "lupo_contents" }
    - { to: "docs/toons/lupo_table.toon.json", type: "schema_reference", weight: 1.0 }

  inbound_edges:
    - { from: "docs/other.md", type: "references", weight: 0.8, last_seen: "20260227" }
    - { from: "docs/related.md", type: "related_to", weight: 0.7, last_seen: "20260227" }

  semantic_tags: ["tag1", "tag2", "tag3"]

flare.footer:
  view_count: 100
  like_count: 5
  share_count: 2
  last_verified: "20260227"
```

**Important:** Always include the automation tip comment above `flare.footer` to educate users about the FLARE Edge Suggester Tool. This promotes automation adoption and reduces manual edge discovery effort.

### **🏷️ Edge Types**

| Type | Description | Weight Range | Use Case |
|------|-------------|--------------|----------|
| `references` | General reference | 0.5-1.0 | Standard documentation links |
| `implements` | Implementation relationship | 0.8-1.0 | Code implementing spec |
| `schema_reference` | Database schema reference | 1.0 | TOON file references |
| `table_relationship` | Table relationship | 0.7-0.9 | Related table documentation |
| `api_reference` | API documentation | 0.8-1.0 | API spec references |
| `ui_reference` | UI component reference | 0.6-0.9 | Component documentation |
| `migration_reference` | Migration documentation | 0.7-0.9 | Migration scripts |
| `test_reference` | Test documentation | 0.6-0.8 | Test files and specs |
| `supersedes` | Newer version replaces older | 0.9-1.0 | Version relationships, deprecation |
| `depends_on` | File A depends on file B | 0.8-1.0 | Dependency relationships |
| `example_of` | Example implements concept | 0.6-0.8 | Example documentation |
| `related_to` | Loose association | 0.5-0.7 | General relationships |

### **🔄 Inbound Edges (Read-Only)**

**Purpose:** Track which files reference the current file. Populated automatically via database scans using the `lupo_edges` table.

**Database Storage:** FLARE file relationships are stored in the `lupo_edges` table (not `lupo_relationships`). The `lupo_edges` table was extended 2026-02-27 with FLARE-specific fields.

**Inbound Edge Object Format:**
```yaml
inbound_edges:
  - { from: "docs/other.md", type: "references", weight: 0.8, last_seen: "20260227", discovered_via: "db_scan" }
  - { from: "docs/related.md", type: "related_to", weight: 0.7, last_seen: "20260227", discovered_via: "content_analysis" }
```

**Inbound Edge Fields:**
| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `from` | string | Source file path | `"docs/other.md"` |
| `type` | string | Relationship type | `"references"` |
| `weight` | number | Relationship strength | `0.8` |
| `last_seen` | string | Last verification date | `"20260227"` |
| `discovered_via` | string | Discovery method | `"db_scan"`, `"content_analysis"` |

**Discovery Methods:**
- `db_scan`: Discovered via `lupo_edges` table scan
- `content_analysis`: Discovered via markdown link analysis
- `toon_schema`: Discovered via TOON foreign key analysis
- `semantic_search`: Discovered via semantic similarity

### **📝 Edge Metadata Expansion**

**Optional Edge Fields:**
| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `reason` | string | Why this edge exists | `"Primary reference"` |
| `db_source` | string | Database table source | `"lupo_contents"` |
| `auto_generated` | boolean | Generated by automation | `true` |
| `verified` | boolean | Path verified to exist | `true` |
| `created_ymdhis` | string | Edge creation timestamp | `"20260227120000"` |

**Example with Metadata:**
```yaml
outbound_edges:
  - { 
      to: "docs/reference.md", 
      type: "references", 
      weight: 1.0, 
      reason: "Primary reference for implementation",
      db_source: "lupo_contents",
      auto_generated: true,
      verified: true,
      created_ymdhis: "20260227120000"
    }
```

### **🗃️ Database Architecture for FLARE Edges**

**Primary Table:** `lupo_edges` (extended 2026-02-27 for FLARE protocol)

**Why lupo_edges instead of lupo_relationships:**
- `lupo_edges` is the canonical relationship table in Lupopedia
- Already has comprehensive indexing and relationship support
- Extended with FLARE-specific fields while maintaining backward compatibility
- Follows table ceiling doctrine by extending existing table rather than creating new one

**FLARE Field Mapping:**
| FLARE Footer Field | lupo_edges Column | Purpose |
|-------------------|-------------------|---------|
| `to`/`from` | `right_object_id`/`left_object_id` | Target/source object IDs |
| `type` | `edge_type` | Relationship type |
| `weight` | `flare_weight` | FLARE weight (0.5-1.0) |
| `reason` | `flare_reason` | Edge existence reason |
| `db_source` | `flare_db_source` | Source database table |
| `auto_generated` | `flare_auto_generated` | Automation flag |
| `verified` | `flare_verified` | Path verification |
| `discovered_via` | `flare_discovered_via` | Discovery method |

**Object Types for FLARE:**
- `left_object_type`/`right_object_type`: `"file"` for markdown files
- Object IDs reference `lupo_contents` table where `content_type = 'file'`

### **�� Weight Guidelines**

| Weight | Relationship Strength | Typical Use |
|--------|---------------------|-------------|
| 1.0 | Critical/Primary | Schema files, core implementations, direct dependencies |
| 0.9 | Very Strong | Primary documentation, key references, version superseding |
| 0.8 | Strong | Important related files, implementation references, dependencies |
| 0.7 | Moderate | Related concepts, supporting documentation, table relationships |
| 0.6 | Light | Contextual references, examples, test documentation |
| 0.5 | Weak | General mentions, tangential relationships |

## 🗂️ **TOON File Reference Pattern**

When documenting database tables, always reference the corresponding TOON file:

```yaml
flare.edges:
  outbound_edges:
    - { to: "docs/toons/lupo_<table_name>.toon.json", type: "schema_reference", weight: 1.0 }
    # ... other edges
```

### **Available TOON Files**

Check `docs/toons/` directory for available table schemas:
- `lupo_dialog_messages.toon.json`
- `lupo_dialog_threads.toon.json`
- `lupo_actors.toon.json`
- `lupo_channels.toon.json`
- `lupo_sessions.toon.json`
- ... (all 222 tables)

## 📝 **Field Value Guidelines**

### **🎯 Naming Conventions**

- **file_path_from_root**: Always use forward slashes, no leading slash
- **tags**: lowercase, underscores for spaces, no special chars
- **semantic_tags**: descriptive, relationship-focused
- **lupo_<table>.<column>**: Exact column names from TOON files

### **🔢 Version Format**

- **system_version**: Always `"X.Y.Z"` format (e.g., `"4.0.47"`)
- **last_modified_utc**: Always `"YYYYMMDD"` format

### **🏷️ Tag Categories**

**Common tag categories:**
- **Content type**: `["documentation", "code", "schema", "api"]`
- **Domain**: `["database", "ui", "authentication", "messaging"]`
- **Priority**: `["critical", "important", "standard"]`
- **Status**: `["draft", "review", "canonical", "deprecated"]`

## ⚠️ **Common Mistakes to Avoid**

1. **Missing TOON References** - Always reference TOON files for table documentation
2. **Incorrect Table Names** - Use exact table names from TOON files
3. **Wrong Edge Types** - Use appropriate edge types for relationships
4. **Inconsistent Weights** - Follow weight guidelines for edge importance
5. **Missing Table-Specific Fields** - Include relevant table metadata
6. **Invalid YAML** - Validate syntax, especially for complex objects
7. **Outdated Version Numbers** - Always use current system version

## 📚 **Related Documentation**

- **Quick Reference:** `docs/FLIP_HEADERS_QUICK_REFERENCE.md`
- **Core Doctrine:** `docs/doctrine/FLARE/FLARE_DOCTRINE.md`
- **TOON Files:** `docs/toons/` (all table schemas)
- **Actor Registry:** `actors/registry.json`
- **Validator Service:** `app/Services/FlareValidatorService.php`

---

**Complete Reference Guide** 🎯

This document serves as the authoritative reference for all FLARE header and footer fields, including specialized table-specific attributes and TOON file references.
