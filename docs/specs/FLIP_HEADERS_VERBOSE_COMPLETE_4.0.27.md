---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/specs/FLIP_HEADERS_VERBOSE_COMPLETE_4.0.27.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222162242"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /specs/FLIP_HEADERS_VERBOSE_COMPLETE_4.0.27
  aliases:
    - /docs/FLIP_HEADERS_VERBOSE_COMPLETE_4.0.27
    - /qa/FLIP+HEADERS+VERBOSE+COMPLETE+4.0.27
  slug: FLIP_HEADERS_VERBOSE_COMPLETE_4.0.27
  slug_encoding: underscore
  base_path: /specs
  url_pattern: "/{base}/{slug}"
---

# FLIP Headers - Complete Verbose Mode Specification 4.0.27

## Overview

When the database is unreachable, verbose FLIP headers provide all semantic metadata that would normally be stored in database tables. This enables full offline operation with complete semantic information embedded in files.

## Verbose Mode Headers - Complete List

### Core Identity Headers (Required)
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Content-ID` | `lupo_contents.content_id` | BIGINT | `42001` |
| `X-Lupo-Title` | `lupo_contents.title` | VARCHAR(255) | `FLIP Documentation Complete` |
| `X-Lupo-Slug` | `lupo_contents.slug` | VARCHAR(255) | `flip-documentation-complete` |
| `X-Lupo-File-Path` | `lupo_contents.file_path_from_root` | VARCHAR(500) | `docs/specs/FLIP_HEADERS_COMPLETE_4.0.24.md` |
| `X-Lupo-Custom-Path` | `lupo_contents.custom_path` | VARCHAR(255) | `/doctrine/FLIP` |

### Actor & Authorization Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Actor-ID` | `lupo_contents.actor_id` | BIGINT | `2039` |
| `X-Lupo-Actor-Identity` | `lupo_actors.actor_identity` | VARCHAR(255) | `Warp IDE` |
| `X-Lupo-Actor-Type` | `lupo_actors.actor_type` | ENUM | `system_tool` |
| `X-Lupo-Created-By-Actor-ID` | `lupo_contents.actor_id` (creator) | BIGINT | `10000` |
| `X-Lupo-Department-ID` | `lupo_contents.department_id` | BIGINT | `1` |

### Content Metadata Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Content-Type` | `lupo_contents.content_type` | VARCHAR(50) | `doctrine` |
| `X-Lupo-Format` | `lupo_contents.format` | VARCHAR(20) | `markdown` |
| `X-Lupo-Description` | `lupo_contents.description` | TEXT | `Complete FLIP header specification` |
| `X-Lupo-Content-Parent-ID` | `lupo_contents.content_parent_id` | BIGINT | `42000` |
| `X-Lupo-Status` | `lupo_contents.status` | VARCHAR(64) | `published` |
| `X-Lupo-Visibility` | `lupo_contents.visibility` | VARCHAR(64) | `public` |
| `X-Lupo-Is-Template` | `lupo_contents.is_template` | TINYINT | `0` |
| `X-Lupo-Version-Number` | `lupo_contents.version_number` | INT | `4` |

### Collection & Organization Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Collection-ID` | `lupo_collections.collection_id` | BIGINT | `10` |
| `X-Lupo-Collection-Name` | `lupo_collections.name` | VARCHAR(255) | `Demo Collection - All Q/A Types` |
| `X-Lupo-Default-Collection-ID` | `lupo_contents.default_collection_id` | BIGINT | `10` |

### Channel & Thread Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Channel` | `lupo_channels.channel_id` | BIGINT | `42` |
| `X-Lupo-Channel-Key` | `lupo_channels.channel_key` | VARCHAR(64) | `crafty_dev` |
| `X-Lupo-Thread` | `lupo_dialog_threads.dialog_thread_id` | BIGINT | `1001` |
| `X-Lupo-Thread-Title` | `lupo_dialog_threads.title` | VARCHAR(255) | `FLIP Doctrine Discussion` |

### Timestamp Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Created-YMDHIS` | `lupo_contents.created_ymdhis` | BIGINT(14) | `20260222160000` |
| `X-Lupo-Updated-YMDHIS` | `lupo_contents.updated_ymdhis` | BIGINT(14) | `20260222170000` |
| `X-Lupo-Timestamp` | System timestamp | BIGINT(14) | `20260222160000` |
| `X-Lupo-UTC-Timestamp` | UTC timestamp | ISO 8601 | `2026-02-22T16:00:00+00:00` |
| `X-Lupo-UTC-Cycle` | `lupo_contents.utc_cycle` | VARCHAR(64) | `2026-Q1` |
| `X-Lupo-File-Last-Modified-UTC` | `lupo_contents.file_last_modified_utc` | BIGINT(14) | `20260222160000` |
| `X-Lupo-File-Last-Modified-Version` | `lupo_contents.file_last_modified_system_version` | VARCHAR(20) | `4.0.27` |

### Federation & Distribution Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Federation-Node-ID` | `lupo_contents.federation_node_id` | BIGINT | `1` |
| `X-Lupo-Federation-Node-Name` | `lupo_federation_nodes.node_name` | VARCHAR(255) | `WOLFIE-Primary` |

### SEO & Discovery Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-SEO-Keywords` | `lupo_contents.seo_keywords` | VARCHAR(500) | `FLIP, headers, doctrine, semantic` |
| `X-Lupo-Source-URL` | `lupo_contents.source_url` | VARCHAR(2000) | `https://original-source.com/article` |
| `X-Lupo-Source-Title` | `lupo_contents.source_title` | VARCHAR(500) | `Original Article Title` |
| `X-Lupo-Content-URL` | `lupo_contents.content_url` | VARCHAR(2000) | `https://lupo.cute/doctrine/FLIP` |

### Engagement & Metrics Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-View-Count` | `lupo_contents.view_count` | INT | `1250` |
| `X-Lupo-Share-Count` | `lupo_contents.share_count` | INT | `45` |
| `X-Lupo-Likes-Total` | `lupo_contents.likes_total` | INT | `89` |
| `X-Lupo-Shares-Total` | `lupo_contents.shares_total` | INT | `34` |

### Triage & Workflow Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Triage-Status` | `lupo_contents.triage_status` | VARCHAR(64) | `triaged` |
| `X-Lupo-Triage-Notes` | `lupo_contents.triage_notes` | TEXT | `Reviewed and approved by LILITH` |

### Semantic Headers (JSON-backed in DB)
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Tags` | `lupo_contents.tags` (JSON) | JSON | `["doctrine","flip","headers"]` |
| `X-Lupo-Hashtags` | `lupo_contents.hashtags` (JSON) | JSON | `["#FLIP","#Doctrine"]` |
| `X-Lupo-Atom-Mappings` | `lupo_contents.atom_mappings` (JSON) | JSON | `[{"atom_id":1,"atom_name":"doctrine"}]` |
| `X-Lupo-Category-Mappings` | `lupo_contents.category_mappings` (JSON) | JSON | `[{"category_id":5,"name":"Technical"}]` |

### Relationship Headers (Edge/Graph Data)
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Related-Content-IDs` | `lupo_edges` (derived) | JSON | `[42000,42002,42003]` |
| `X-Lupo-Parent-Content-IDs` | `lupo_edges` (parent relationships) | JSON | `[42000]` |
| `X-Lupo-Child-Content-IDs` | `lupo_edges` (child relationships) | JSON | `[42010,42011]` |
| `X-Lupo-Semantic-Relationships` | `lupo_semantic_relationships` | JSON | `[{"target_id":42005,"type":"references","strength":0.95}]` |

### Document & Artifact Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Document-ID` | `lupo_documents.document_id` | BIGINT | `5001` |
| `X-Lupo-Document-Name` | `lupo_documents.document_name` | VARCHAR(256) | `FLIP_HEADERS_COMPLETE.md` |
| `X-Lupo-Mime-Type` | `lupo_documents.mime_type` | VARCHAR(128) | `text/markdown` |
| `X-Lupo-File-Size-Bytes` | `lupo_documents.file_size_bytes` | INT | `45120` |
| `X-Lupo-Checksum-SHA256` | `lupo_documents.checksum_sha256` | VARCHAR(64) | `abc123def456...` |

### Semantic Navigation Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Semantic-Category-ID` | `lupo_semantic_categories.category_id` | BIGINT | `10` |
| `X-Lupo-Semantic-Category-Slug` | `lupo_semantic_categories.category_slug` | VARCHAR(255) | `doctrine-specifications` |
| `X-Lupo-Semantic-Tag-IDs` | `lupo_semantic_tags` (multiple) | JSON | `[1,5,10,15]` |

### Atom & Context Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Atom-IDs` | `lupo_atoms` (multiple) | JSON | `[100,101,102]` |
| `X-Lupo-Atom-Names` | `lupo_atoms.atom_name` (multiple) | JSON | `["version","doctrine","flip"]` |
| `X-Lupo-Context-ID` | `lupo_atoms.context_id` | BIGINT | `42` |
| `X-Lupo-Is-Authoritative` | `lupo_atoms.is_authoritative` | TINYINT | `1` |

### Search Index Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Search-Index-ID` | `lupo_search_index.search_index_id` | BIGINT | `8001` |
| `X-Lupo-Search-Keywords` | `lupo_search_index.keywords_text` | TEXT | `FLIP headers specification doctrine` |
| `X-Lupo-Search-Relevance-Score` | `lupo_search_index.relevance_score` | FLOAT | `0.98` |

### Emotional Geometry Headers (Advanced)
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Emotional-Framework` | `lupo_emotional_frameworks.framework_name` | VARCHAR(32) | `western_analytical` |
| `X-Lupo-Emotional-Constellation-ID` | `lupo_emotional_constellations.constellation_id` | CHAR(26) | `01HFNXZ...` |

### CIP (Critique Integration Propagation) Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-CIP-Event-ID` | `lupo_cip_events.cip_event_id` | BIGINT | `7001` |
| `X-Lupo-Defensiveness-Index` | `lupo_cip_analytics.defensiveness_index` | DECIMAL(5,4) | `0.1250` |
| `X-Lupo-Integration-Velocity` | `lupo_cip_analytics.integration_velocity` | DECIMAL(5,4) | `0.8750` |

### State & Flags Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Is-Active` | `lupo_contents.is_active` | TINYINT | `1` |
| `X-Lupo-Is-Deleted` | `lupo_contents.is_deleted` | TINYINT | `0` |
| `X-Lupo-Deleted-YMDHIS` | `lupo_contents.deleted_ymdhis` | BIGINT(14) | `NULL` |

### Location & Geography Headers
| Header | Maps To | Type | Example |
|--------|---------|------|---------|
| `X-Lupo-Location` | Geographic location | VARCHAR(255) | `Sioux Falls, South Dakota, US` |
| `X-Lupo-Location-Latitude` | Latitude | DECIMAL(10,8) | `43.5460` |
| `X-Lupo-Location-Longitude` | Longitude | DECIMAL(11,8) | `-96.7313` |

## Verbose Mode Header Count

**Total Verbose Mode Headers: 79 (existing) + 89 (new verbose headers) = 168 headers**

### Header Categories Summary

1. **Core Identity**: 5 headers
2. **Actor & Authorization**: 5 headers
3. **Content Metadata**: 8 headers
4. **Collections**: 3 headers
5. **Channels & Threads**: 4 headers
6. **Timestamps**: 8 headers
7. **Federation**: 2 headers
8. **SEO & Discovery**: 4 headers
9. **Engagement**: 4 headers
10. **Triage**: 2 headers
11. **Semantic**: 4 headers
12. **Relationships**: 4 headers
13. **Documents**: 5 headers
14. **Navigation**: 3 headers
15. **Atoms**: 4 headers
16. **Search**: 3 headers
17. **Emotional**: 2 headers
18. **CIP**: 3 headers
19. **State**: 3 headers
20. **Location**: 3 headers

**Total New Verbose Headers: 89**

## Usage Modes

### Minimum Mode (Online - Database Available)
- Use only essential headers (Actor ID, Version, Channel, Thread)
- Database provides all other metadata
- ~5-10 headers typical

### Standard Mode (Online with Caching)
- Core identity + timestamps + collection headers
- Most metadata still from database
- ~15-20 headers typical

### Verbose Mode (Offline - Database Unreachable)
- **ALL** semantic metadata in headers
- Complete portability and offline operation
- 100+ headers typical
- Enables full semantic search and navigation without database

## Database Mapping Layer (Optional)
The `X-LUPO-{table}.{column}` namespace allows explicit mapping between header
fields and database schema. This layer is optional and must not replace
semantic FLIP fields. It is intended for advanced tooling, migrations, and
schema-aware agents.

### Doctrine Requirements:
- **Optional**: Never required for system inference.
- **Namespaced**: Must use the `X-LUPO-` prefix.
- **Non-Invasive**: Must never override semantic fields like `actor_id` or `channel_id`.
- **Stateless**: Never used for schema guessing or write-back unless explicitly invoked.

## Implementation Notes

### When to Use Verbose Mode

1. **Offline Documentation** - Git repository browsing without database
2. **Emergency Fallback** - Database connection lost
3. **Distribution** - Sharing files with complete metadata
4. **Archive** - Long-term storage with full semantic context
5. **Migration** - Moving content between systems

### Header Generation

```php
// Generate verbose headers for content
function generate_verbose_flip_headers($content_id) {
    $db = get_database_connection();
    
    // Query all related tables
    $content = fetch_content_with_all_joins($db, $content_id);
    $edges = fetch_all_edges($db, $content_id);
    $semantic = fetch_semantic_data($db, $content_id);
    $atoms = fetch_atom_mappings($db, $content_id);
    $collection = fetch_collection_data($db, $content_id);
    
    // Build comprehensive header array
    $headers = [
        'X-Lupo-Content-ID' => $content['content_id'],
        'X-Lupo-Title' => $content['title'],
        // ... all 168 headers
    ];
    
    return $headers;
}
```

### Header Consumption

```php
// Parse verbose headers and reconstruct database state
function parse_verbose_flip_headers($file_path) {
    $headers = extract_flip_headers_from_file($file_path);
    
    // Reconstruct lupo_contents row
    $content = [
        'content_id' => $headers['X-Lupo-Content-ID'],
        'title' => $headers['X-Lupo-Title'],
        // ... all fields
    ];
    
    // Reconstruct lupo_edges relationships
    $edges = json_decode($headers['X-Lupo-Related-Content-IDs']);
    
    // Return complete semantic model
    return [
        'content' => $content,
        'edges' => $edges,
        'semantic' => $semantic,
        'atoms' => $atoms
    ];
}
```

## Best Practices

1. **Always generate verbose headers for doctrine files** - Core specifications should be fully portable
2. **Use JSON for array/object values** - Enables proper parsing and reconstruction
3. **Include NULL values explicitly** - Use `NULL` string to distinguish from empty string
4. **Maintain header order** - Group by category for readability
5. **Validate on generation** - Ensure all database references resolve
6. **Test offline operation** - Verify files work without database connection

## Future Extensions

- **X-Lupo-Embeddings**: Document embedding vectors for semantic search
- **X-Lupo-Permissions**: Complete permission matrix in headers
- **X-Lupo-Workflow-State**: Complete workflow state machine data
- **X-Lupo-Audit-Trail**: Complete audit log as JSON array

---

## Database Mapping Layer (Optional) - New in 4.0.28

### Overview
The `X-LUPO-{table}.{column}` namespace provides explicit mapping between
FLIP headers and database schema. This layer is **optional** and **must not**
replace semantic FLIP fields.

### When to Use
- Advanced migration tooling
- Schema-aware agents
- Explicit database write-back
- Debugging and validation

### When NOT to Use
- Standard file attribution (use semantic headers)
- Inference-based processing
- Schema-agnostic operations

### Syntax
```
X-LUPO-{table}.{column}: {value}
```

### Example
```markdown
X-Lupo-Actor-ID: 2040
X-Lupo-Channel-ID: 42
X-LUPO-actors.actor_type: system_tool
X-LUPO-actors.slug: windsurf-ide
X-LUPO-channels.channel_key: windsurf-dev
```

### Constraints
- Must use `X-LUPO-` prefix (all caps)
- Must validate table/column against schema
- Must not override semantic headers
- Must not be required for processing
- Must not be used for schema guessing

### Implementation Notes
- Values are treated as opaque strings (no type inference)
- Table and column names are validated against `install_new_lupopedia.sql`
- SQL generation must explicitly list all columns (no positional INSERTs)
- Required timestamp columns (`created_ymdhis`, `updated_ymdhis`) must be included

---

**Version**: 4.0.28  
**Created**: 2026-02-22  
**Status**: Active  
**Header Count**: 168 total (79 existing + 89 new verbose + database mapping layer)
