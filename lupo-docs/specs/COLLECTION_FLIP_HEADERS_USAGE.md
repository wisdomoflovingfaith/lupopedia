# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\specs\COLLECTION_FLIP_HEADERS_USAGE.md"
  file_hash: "00323f6ed476f962ab121aa7dd958dc7034d782505b7813ccd2ac9c12ae8ffe5"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
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
  file_path_from_root: "docs\specs\COLLECTION_FLIP_HEADERS_USAGE.md"
  file_hash: "55d472669060e9fb081dc9f9b155266ccc9e3351dcc6bdb00001745ad84e4732"
  file_path_from_root: "docs\specs\COLLECTION_FLIP_HEADERS_USAGE.md"
  file_hash: "0d202d177d0f25b3bb3fc9abe4edc8c497a12ce489b91f3e69cb10d28684d1e4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for COLLECTION_FLIP_HEADERS_USAGE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "specs", "collection_flip_headers_usagemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/specs/COLLECTION_FLIP_HEADERS_USAGE.md
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
  canonical: /specs/COLLECTION_FLIP_HEADERS_USAGE
  aliases:
    - /docs/COLLECTION_FLIP_HEADERS_USAGE
    - /qa/COLLECTION+FLIP+HEADERS+USAGE
  slug: COLLECTION_FLIP_HEADERS_USAGE
  slug_encoding: underscore
  base_path: /specs
  url_pattern: "/{base}/{slug}"
---

# Collection FLIP Headers Usage Guide

## Overview

As of version 4.0.27, FLIP headers support collection attribution via two new headers:
- `X-Lupo-Collection-ID` - Numeric collection identifier
- `X-Lupo-Collection-Name` - Human-readable collection name

## Schema Mapping

These headers map directly to the `lupo_collections` table:

| FLIP Header | Database Column | Type | Description |
|-------------|----------------|------|-------------|
| `X-Lupo-Collection-ID` | `collection_id` | BIGINT | Primary key identifier |
| `X-Lupo-Collection-Name` | `name` | VARCHAR(255) | Human-readable collection name |

## Usage in Documentation Files

Add these headers to the FLIP header block at the top of any markdown file:

```markdown
---
X-Lupo-Collection-ID: 10
X-Lupo-Collection-Name: Demo Collection - All Q/A Types
X-Lupo-Actor-ID: 2039
X-Lupo-Version: 4.0.27
---

# Your Document Title

Document content here...
```

## Usage in API Calls

Include collection headers in metadata when creating or updating content:

```php
$metadata = [
    'flip_headers' => [
        'X-Lupo-Collection-ID' => 10,
        'X-Lupo-Collection-Name' => 'Demo Collection - All Q/A Types',
        'X-Lupo-Version' => '4.0.27'
    ]
];
```

## Integration with Saved Collections Navigation

The saved collections navigation system (`lupo-includes/themes/default/components/saved-collections-nav.php`) can now:

1. **Read collection attribution** from FLIP headers in documentation files
2. **Display collection membership** in navigation breadcrumbs
3. **Filter content by collection** using the collection headers
4. **Track recently viewed items** per collection

## Database Query Example

To find all content in a specific collection:

```sql
SELECT 
    c.content_id,
    c.title,
    JSON_EXTRACT(c.metadata_json, '$.flip_headers."X-Lupo-Collection-ID"') as collection_id,
    JSON_EXTRACT(c.metadata_json, '$.flip_headers."X-Lupo-Collection-Name"') as collection_name
FROM lupo_contents c
WHERE JSON_EXTRACT(c.metadata_json, '$.flip_headers."X-Lupo-Collection-ID"') = 10;
```

## Benefits

1. **Traceability** - Every file can declare its collection membership
2. **Portability** - Collection information travels with the file
3. **Searchability** - Easy to grep for collection membership across files
4. **Integration** - Works seamlessly with existing FLIP header infrastructure

## Best Practices

1. **Always include both headers** - Provide both ID and name for redundancy
2. **Keep names synchronized** - Ensure the name matches the database entry
3. **Update on collection changes** - If a collection is renamed, update all member files
4. **Use in conjunction with other headers** - Combine with Actor, Version, and Channel headers

## Database Mapping Layer (Optional)
The `X-LUPO-{table}.{column}` namespace allows explicit mapping between header
fields and database schema. This layer is optional and must not replace
semantic FLIP fields. It is intended for advanced tooling, migrations, and
schema-aware agents.

## Example: Complete FLIP Header Block

```markdown
---
X-Lupo-Collection-ID: 420
X-Lupo-Collection-Name: Lupopedia Dev Archive
X-Lupo-Actor-ID: 2039
X-Lupo-Actor-Identity: Warp IDE
X-Lupo-Channel: 42
X-Lupo-Thread: 1001
X-Lupo-Version: 4.0.27
X-Lupo-Timestamp: 20260222160000
---
```

## Database Mapping Layer (Optional) - New in 4.0.28

The `X-LUPO-{table}.{column}` namespace allows explicit mapping between header
fields and database schema. This layer is optional and must not replace
semantic FLIP fields. It is intended for advanced tooling, migrations, and
schema-aware agents.

### Syntax
```
X-LUPO-{table}.{column}: {value}
```

### Valid Examples
```
X-LUPO-collections.collection_id: 420
X-LUPO-collections.collection_name: Lupopedia Dev Archive
X-LUPO-actors.actor_id: 2039
X-LUPO-channels.channel_id: 42
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

## Migration Notes

For existing files without collection headers:

1. Identify collection membership via `lupo_collection_tab_map` table
2. Batch-update FLIP headers using `scripts/update_collection_headers.py` (to be created)
3. Validate all collection headers match database state

## Related Documentation

- [FLIP Headers Complete 4.0.24](FLIP_HEADERS_COMPLETE_4.0.24.md)
- [FLIP Headers Master Index 4.0.24](FLIP_HEADERS_MASTER_INDEX_4.0.24.md)
- [Collections Schema Documentation](../doctrine/COLLECTIONS_SCHEMA.md)
- [Saved Collections Navigation](../../lupo-includes/themes/default/components/saved-collections-nav.php)

---
**Version**: 4.0.27  
**Created**: 2026-02-22  
**Status**: Active  
