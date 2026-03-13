# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\MASTER_DOCTRINE.md"
  file_hash: "4e9a746b11713b9e854d0be55efa06f4dac23c27922cabad9e39f6f71a00887d"
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
  file_path_from_root: "docs\doctrine\MASTER_DOCTRINE.md"
  file_hash: "1c9e476d3d93707497e5da34d9cb67ff7fc2e87224a8e4a965ef815e2c8f77df"
  file_path_from_root: "docs\doctrine\MASTER_DOCTRINE.md"
  file_hash: "dce34d237d29d9ffa6660b2edbec578e71a6dad34d2c6f326733a13519cd3e8f"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for MASTER_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "master_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/MASTER_DOCTRINE.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/MASTER_DOCTRINE.md
---

# WOLFIE HEADER DOCTRINE v4.1
### Path-Based • Optional Grep Fields • Database-First Identity

## 1. PURPOSE
Wolfie Headers provide minimal file identification and optional grep-friendly metadata.
All semantic metadata lives exclusively in the database (lupo_contents, lupo_edges).
The filesystem is content storage; the database is the ontology.

v4.1 establishes the path-based header format with optional editor/grep support fields.

**Key changes from v4.0:**
- Removed all semantic metadata from headers
- Single required field: file_path_from_root
- Optional grep-friendly fields for human convenience
- Database as canonical identity layer
- No hierarchy, relationships, or timestamps in headers

## 2. CANONICAL HEADER FORMAT (v4.1)
The canonical Wolfie header format uses minimal path-based structure:

```
/* wolfie_header v4.1
   file_path_from_root: <relative_path_from_repo_root>
   content_sections: [<section_anchor1>, <section_anchor2>, ...]
   version_number: <integer_update_count>
   dialog_notes: <short_text_about_last_agent_action>
   status: <freeform_text>
   tags: [<tag1>, <tag2>, <tag3>, ...]
*/
```

## 3. REQUIRED HEADER FIELD

### 3.1 file_path_from_root
Every file must contain exactly one required field:

```
file_path_from_root: <relative_path_from_repo_root>
```

**Rules:**
- Lowercase only
- Must follow filename doctrine (a–z, 0–9, _)
- Must match actual filesystem path
- Must be updated automatically when files move
- Must be the only required field
- Must be relative from repository root

## 4. OPTIONAL HEADER FIELDS (EDITOR / GREP SUPPORT)

The following fields are optional and exist only for human convenience
(e.g., Notepad++, grep, quick review). They carry no semantic weight and
must not be interpreted as metadata by the database.

### 4.1 Optional Fields

```
content_sections: [<section_anchor1>, <section_anchor2>, ...]
version_number: <integer_update_count>
dialog_notes: <short_text_about_last_agent_action>
status: <freeform_text>
tags: [<tag1>, <tag2>, <tag3>, ...]
```

### 4.2 Optional Field Rules
- Optional fields must not affect database identity
- Optional fields must not be required for import
- Optional fields must not be parsed as semantic metadata
- Optional fields must be ignored by validators except for syntax safety
- Optional fields are for human/editor convenience only

### 4.3 Field Descriptions
- **content_sections**: List of section anchors for navigation
- **version_number**: Simple integer update counter
- **dialog_notes**: Short text about last agent action
- **status**: Freeform status indicator
- **tags**: Grep-friendly tags for human categorization
 

## 5. DATABASE AS THE CANONICAL IDENTITY LAYER

### 5.1 Database Tables for Semantic Metadata
All metadata previously stored in headers must now be stored in:

- **lupo_contents**: Node metadata (identity, timestamps, relationships)
- **lupo_edges**: Relationship metadata (graph edges, dependencies)

### 5.2 Database Supremacy
- The database is the ontology
- The filesystem is content storage
- Headers are minimal file identification only
- No semantic inference from filesystem

### 5.3 Identity Resolution
- file_path_from_root → lupo_contents record
- Database record contains all semantic metadata
- File content hash stored in database
- File moves update database path only

## 6. HEADER SYNC RULES

### 6.1 File Import Process
When a file is imported:
1. Read file_path_from_root from header
2. Use it to locate the DB record in lupo_contents
3. Update DB content hash
4. Ignore all optional fields (no semantic processing)

### 6.2 File Move Process
When a file moves:
- Update file_path_from_root in header
- Update DB record path in lupo_contents
- Preserve all edges in lupo_edges
- No semantic metadata lost

### 6.3 Content Change Process
When file content changes:
- Update content hash in lupo_contents
- Update modified_ymdhis in lupo_contents
- Optional header fields may be updated by humans
- No database schema changes required

## 7. VALIDATION RULES (v4.1)

A header is valid if:

### 7.1 Required Validation
1. Header starts with "/* wolfie_header v4.1"
2. Contains exactly one file_path_from_root field
3. file_path_from_root is lowercase and follows naming rules
4. file_path_from_root matches actual filesystem path

### 7.2 Optional Field Validation
5. All optional fields use correct syntax
6. Lists use bracket notation
7. No forbidden semantic metadata fields present
8. No hierarchy, relationship, or timestamp fields

### 7.3 Format Validation
9. All field names are lowercase
10. No unicode symbols in field names
11. Proper YAML-compatible structure
12. **MASTER_DOCTRINE compliance** (supreme authority)

## 8. DOCTRINE ENFORCEMENT

### 8.1 Required Enforcement
- file_path_from_root must exist and be correct
- Path must be relative from repository root
- Path must follow naming doctrine

### 8.2 Forbidden Enforcement
- No semantic metadata in headers
- No hierarchy or relationship fields
- No timestamps in headers
- No database schema inference from headers

### 8.3 Severity Levels
- **BLOCKED**: Missing file_path_from_root
- **MAJOR**: Incorrect path format or mismatched path
- **HOLD**: Extra semantic metadata fields present
- **CLEAR**: All validation rules satisfied

## 9. IMPORT SCRIPT REQUIREMENTS

### 9.1 Script Behavior
- Read file_path_from_root only
- Locate DB record using path
- Update content hash
- Ignore optional fields completely
- No semantic metadata extraction from headers

### 9.2 Two-Lane Ingestion
- import_os.py → lupo_contents (semantic content)
- import_files.py → lupo_files (file metadata only)
- No cross-table writes
- Path-based identity resolution

### 9.3 Error Handling
- Missing path → BLOCKED state
- Invalid path → MAJOR error
- Extra semantic fields → HOLD state

## 10. CASTCADE ENFORCEMENT

### 10.1 Header Validation
- v4.1 format only
- file_path_from_root required
- No semantic metadata fields
- Path format validation
- Filename doctrine compliance

### 10.2 Database Integration
- Path-based record lookup
- Content hash updates
- Edge preservation during moves
- No header-to-database inference

### 10.3 Optional Field Handling
- Syntax validation only
- No semantic processing
- Human-editor convenience only
- Grep-friendly tag support

## 11. EXAMPLE v4.1 HEADERS

### 11.1 Minimal Header
```
/* wolfie_header v4.1
   file_path_from_root: docs/lupopedia/core/identity/semantic_os.md
*/
```

### 11.2 Header with Optional Fields
```
/* wolfie_header v4.1
   file_path_from_root: docs/lupopedia/core/identity/semantic_os.md
   content_sections: [introduction, purpose, invariants]
   version_number: 12
   dialog_notes: updated section anchors and normalized spacing
   status: draft
   tags: [core, identity, review]
*/
```

### 11.3 Code File Header
```
/* wolfie_header v4.1
   file_path_from_root: api/controllers/user_auth.py
   content_sections: [imports, class, methods, tests]
   version_number: 8
   dialog_notes: refactored authentication logic
   status: production
   tags: [api, auth, controller, production]
*/
```

### 11.4 Database Migration Header
```
/* wolfie_header v4.1
   file_path_from_root: database/migrations/001_create_users.sql
   content_sections: [up, down]
   version_number: 1
   dialog_notes: initial user table
   status: deployed
   tags: [database, migration, users]
*/
```

## 12. DATABASE SCHEMA IMPLICATIONS

### 12.1 lupo_contents Table
Contains all semantic metadata:
- file_path_from_root (primary identifier)
- created_ymdhis, modified_ymdhis
- updated_by
- version_hash
- All semantic relationships
- All documentation fields

### 12.2 lupo_edges Table
Contains all relationship metadata:
- from_path, to_path
- relationship_type
- created_ymdhis
- metadata

### 12.3 Filesystem Independence
- Database is source of truth for identity
- Filesystem is content storage only
- Headers provide minimal path identification
- No semantic coupling between filesystem and database

## 13. MIGRATION FROM v4.0

### 13.1 Header Migration
1. Remove all semantic metadata from headers
2. Add file_path_from_root field
3. Move semantic metadata to database
4. Add optional grep fields as needed

### 13.2 Database Migration
1. Create lupo_contents records for all files
2. Create lupo_edges for all relationships
3. Migrate header metadata to database
4. Update import scripts for v4.1

### 13.3 Validation Migration
1. Update validators for v4.1 format
2. Remove semantic metadata validation
3. Add path-based validation
4. Update Castcade enforcement rules

## 14. FILENAME DOCTRINE (PRESERVED)

### 14.1 Allowed Characters
All filenames must use only:
- a–z (lowercase letters only)
- 0–9 (digits)
- _ (underscore)
- - (hyphen)

### 14.2 Forbidden Characters
- Uppercase letters (A–Z)
- Spaces
- Unicode symbols
- MixedCase, camelCase, PascalCase
- Multiple dots

### 14.3 Path Requirements
- file_path_from_root must follow naming rules
- Path separators are forward slashes (/)
- No trailing slashes
- Relative from repository root

## 15. STATE MACHINE

CLEAR/HOLD/BLOCKED states based on validation compliance. No time-based enforcement.

### 15.1 State Transitions
- **CLEAR** → **HOLD**: Extra semantic metadata detected
- **CLEAR** → **BLOCKED**: Missing or invalid file_path_from_root
- **HOLD** → **CLEAR**: Semantic metadata removed
- **BLOCKED** → **CLEAR**: Path field corrected

## 16. CANONICAL DOCTRINE WORKFLOW

### 16.1 Revision Process
1. Create revision files during development
2. Revision files must follow naming rules
3. Revision files are never referenced by production headers
4. Revision files ARE allowed but NOT canonical

### 16.2 Finalization Process
1. When revision is approved, overwrite wolfie_header_doctrine.md
2. Archive previous version
3. Update all system references
4. Validate all headers reference canonical doctrine

### 16.3 Authority Chain
1. MASTER_DOCTRINE (supreme authority)
2. wolfie_header_doctrine (canonical header doctrine)
3. Revision files (development only, non-canonical)

## 17. VERSION HISTORY
- v4.1 (2026-02-02): Path-based headers, optional grep fields, database-first identity
- v4.0 (2026-02-02): Hierarchy-based format, YAML compatibility, YYYYMMDDHHIISS timestamps
- v3.4 (2026-02-02): ASCII-only format, full-name fields, canonical_doctrine requirement
- v3.3 (2026-02-02): Resolved structural contradictions, separated ingestion architecture
- v3.2 (2026-02-02): Added optional channel and thread metadata
- v3.1 (2026-02-02): State-based enforcement, Master Doctrine compliance
- v3.0 and earlier: Legacy formats

---

**END OF WOLFIE HEADER DOCTRINE v4.1 REVISION**
