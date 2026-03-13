# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\scripts\README_migration.md"
  file_hash: "bb026afc0451b13f1d59522842132c55783369848744f52f5303b77c64230d60"
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

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "scripts\README_migration.md"
  file_hash: "fe4d5f5b27714dfcc323d804343b86feeef93e3f3c6b34566dae5733d6d97429"
  file_path_from_root: "scripts\README_migration.md"
  file_hash: "0d9a7f9657b777cdeab852d3beb891a7aac08d804078e1900062a6fd84dcadf7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Wolfie Header v2.6 Database Migration"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["scripts", "readme_migrationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Wolfie Header v2.6 Database Migration

## Overview
Migrates Wolfie v2.6 headers from PHP files to `lupo_files` and `lupo_file_edges` database tables.

## Files Created
- `migrate_wolfie_headers_to_db.php` - Main migration script
- `README_migration.md` - This documentation

## Database Schema

### lupo_files Table
| Field | Type | v2.6 Mapping |
|-------|------|--------------|
| file_id | int AUTO_INCREMENT | Primary key |
| file_path | varchar(255) | Relative file path |
| package_name | varchar(100) | pkg: field |
| module_name | varchar(100) | mod: field |
| aspect_name | varchar(100) | asp: field |
| pur | text | pur: field |
| cre_ymdhis | bigint | cre: timestamp (Unix epoch) |
| mod_ymdhis | bigint | mod: timestamp (Unix epoch) |
| upd_by | varchar(50) | agent from upd: field |
| upd_count | int | #N from upd: field |

### lupo_file_edges Table
| Field | Type | Purpose |
|-------|------|---------|
| edge_id | int AUTO_INCREMENT | Primary key |
| source_file_id | int | References lupo_files.file_id |
| target_file_id | int | References lupo_files.file_id |
| rel_type | varchar(50) | supports, supported_by, conflicts_with |
| description | text | Relationship context |
| cre_ymdhis | bigint | Creation timestamp |

## Usage

### Basic Migration (Root Files Only)
```bash
cd scripts
php migrate_wolfie_headers_to_db.php
```

### Full Recursive Migration
```bash
cd scripts
php migrate_wolfie_headers_to_db.php --recursive
```

## Features

### Header Parsing
- Extracts v2.6 headers from PHP files
- Handles reserved field names (package_name, module_name)
- Converts timestamps to Unix epoch format
- Parses update counts from agent#N format

### Taxonomy Inference
- Uses v2.3 taxonomy for pattern matching
- Falls back to defaults (misc/utils/utility)
- Applies directory and file patterns

### Relationship Tracking
- Maps →, ←, ↔ to database relations
- Pattern matching for target file resolution
- Supports multiple targets per relationship

### Error Handling
- Continues on individual file errors
- Reports migration summary
- Detailed error logging

## Output Example
```
=== Migrating Root Directory ===
Migrated: c:\ServBay\www\servbay\lupopedia\index.php
Migrated: c:\ServBay\www\servbay\lupopedia\admin.php
Migrated: c:\ServBay\www\servbay\lupopedia\live.php
Tables created or verified.

=== Migration Summary ===
Files migrated: 7
```

## Requirements
- PHP 8.1+
- PDO MySQL extension
- Valid database configuration in `lupopedia-config.php`
- Wolfie v2.6 headers in PHP files

## Post-Migration Queries

### Find all files in a package
```sql
SELECT file_path, module_name, aspect_name 
FROM lupo_files 
WHERE package_name = 'lupopedia';
```

### Get files modified by specific agent
```sql
SELECT file_path, upd_count, mod_ymdhis 
FROM lupo_files 
WHERE upd_by = 'cascade';
```

### Find relationships for a file
```sql
SELECT 
    lf.file_path as source_file,
    lfe.rel_type,
    target.file_path as target_file
FROM lupo_file_edges lfe
JOIN lupo_files lf ON lfe.source_file_id = lf.file_id
JOIN lupo_files target ON lfe.target_file_id = target.file_id
WHERE lf.file_path = 'index.php';
```

## Notes
- Script creates tables if they don't exist
- Uses ON DUPLICATE KEY UPDATE for idempotent runs
- Timestamps stored as Unix epoch for efficiency
- Reserved SQL words handled with _name suffixes
