---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.84/script_changes/README.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.84/script_changes"
  last_modified_utc: "20260320"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "documentation"
  artifact_kind: "script_changes"
  title: "Script Changes - Version 4.0.84"
  purpose: "Documentation of script and tool changes in version 4.0.84"
  tags: ["version", "4.0.84", "scripts", "tools", "python", "php"]
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Test generate_headers_from_db.py functionality"
    - "Validate import_content.py hardening"
    - "Ensure script integration works"
---

# file: Script Changes - Version 4.0.84

## Summary

Version 4.0.84 introduces **new header generation tools** and enhances existing scripts to support the single-field versioning model and TOON schema integration.

## New Scripts

### Header Generation Script
**File:** `lupo-scripts/generate_headers_from_db.py`

**Purpose:** Generate LUPOPEDIA HEADERS from database metadata using TOON schema as authoritative source

**Key Features:**
- **TOON-grounded schema** - Reads TOON/JSON files for exact database structure
- **Dual resolution modes** - Support for file-path and content-id lookup
- **Deterministic output** - Stable block and key ordering
- **Legacy normalization** - Converts old block names to canonical form
- **Dry-run mode** - Preview changes without writing files

**CLI Interface:**
```bash
# Resolution by file path
python lupo-scripts/generate_headers_from_db.py --file-path path/from/root.md

# Resolution by content ID
python lupo-scripts/generate_headers_from_db.py --content-id 123456789

# Both with verification
python lupo-scripts/generate_headers_from_db.py --file-path path.md --content-id 123456789

# Dry run preview
python lupo-scripts/generate_headers_from_db.py --dry-run --file-path path.md
```

**Core Functions:**
- `resolve_artifact()` - Database resolution logic
- `build_block_tree()` - Metadata block construction
- `normalize_legacy_blocks()` - Legacy block handling
- `generate_identity_line()` - Identity line generation
- `merge_front_matter_with_existing_body()` - File update logic

## Enhanced Scripts

### Content Import Script
**File:** `lupo-scripts/import_content.py`

**Changes Made:**
- **Removed MySQL-specific upsert** - Replaced with application-layer flow
- **Explicit SELECT/UPDATE/INSERT** - Vendor-neutral database operations
- **Tightened timestamp validation** - Strict `last_modified_utc` handling
- **Success output timing** - Only after DB commit + file rewrite
- **Deterministic column updates** - File-derived columns updated on re-import
- **Mismatch warnings** - Alert on header `content_id` conflicts

**New Flow:**
1. `SELECT` by `content_id`
2. `UPDATE` if exists
3. `INSERT` if not
4. File rewrite
5. Success output

## Script Integration

### Database Configuration
**File:** `lupo-scripts/db_config.py`

**Purpose:** Centralized database connection configuration

**Features:**
- Environment-based configuration
- Connection pooling support
- Error handling and logging
- TOON schema integration

## Tooling Enhancements

### Version Management
**Changes:**
- Dynamic version resolution via `LUPEDIA_VERSION`
- Single-field version enforcement
- Baseline rewrite automation
- Version validation tools

### Validation Tools
**Changes:**
- Header format validation
- Version field compliance checking
- Baseline rewrite requirement detection
- TOON schema validation

## Impact Analysis

### Positive Impact
- **Deterministic header generation** - Consistent output across runs
- **TOON schema authority** - Single source of truth for database structure
- **Vendor-neutral database operations** - Improved portability
- **Enhanced validation** - Stricter compliance checking

### Breaking Changes
- **MySQL-specific features removed** - Vendor-neutral operations only
- **Stricter validation** - Less tolerant of malformed headers
- **Baseline rewrite enforcement** - Automatic updates to legacy files

## Usage Examples

### Header Generation
```bash
# Generate headers for a specific file
python lupo-scripts/generate_headers_from_db.py --file-path docs/example.md

# Preview changes without writing
python lupo-scripts/generate_headers_from_db.py --dry-run --file-path docs/example.md

# Resolve by content ID
python lupo-scripts/generate_headers_from_db.py --content-id 123456789
```

### Content Import
```bash
# Import content with validation
python lupo-scripts/import_content.py --file content.json

# Import with dry-run
python lupo-scripts/import_content.py --dry-run --file content.json
```

## Related Files

- [generate_headers_from_db.py](../../../../lupo-scripts/generate_headers_from_db.py)
- [import_content.py](../../../../lupo-scripts/import_content.py)
- [db_config.py](../../../../lupo-scripts/db_config.py)
- TOON Schema Files

## Validation

### Pre-deployment Checks
- [x] Verify new header generation script functionality
- [x] Test content import script enhancements
- [x] Validate database configuration changes

### Post-deployment Checks
- [ ] Test header generation on production data
- [ ] Validate content import with real data
- [ ] Monitor script performance and reliability

---

*Last updated: 2026-03-20*
