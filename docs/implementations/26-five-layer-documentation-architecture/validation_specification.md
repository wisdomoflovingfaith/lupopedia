---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260402000000"
  file_path_from_root: "docs/implementations/26_five_layer_documentation_architecture/validation_specification.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/26_five_layer_documentation_architecture/validation_specification.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: implementation
  artifact_kind: specification
  thread_id: "26-validation-specification"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: "26_five_layer_documentation_architecture"
  summary: ""
  module: null
  dialog_transcript: null
---
# Validation Script Specification

## Overview

`scripts/validate_implementation.py` enforces compliance with the Five-Layer Documentation Architecture.

## Validation Checks

### 1. Presence Validation

**Required Files:**
- `README.md` - Implementation overview
- `authors.md` - Actor attribution
- `edges.md` - System relationships
- `discussions/THREAD_INDEX.md` - Discussion index

**Required Directories:**
- `discussions/` - Threaded discussions

### 2. Schema Validation

#### PRD Front-Matter
```yaml
---
lupopedia.schema: prd
id: 25
slug: departments_system
title: "Departments System"
status: "draft|review|approved|implemented|deprecated"
parent_edges_ref: "docs/implementations/25_departments_system/edges.md"
---
```

#### Implementation README Front-Matter
```yaml
---
id: 25
parent_prd: "docs/prd/25_departments_system.md"
status: "not_started|in_progress|complete|blocked|deprecated"
version: "1.0.0"
last_reviewed_utc: "20260402000000"
doc_arch_version: 1
---
```

#### authors.md Schema
```markdown
| actor_id | actor_type | role | scope | first_contribution_utc | last_contribution_utc |
|----------|------------|------|-------|------------------------|----------------------|
```

#### edges.md Sections
- `## Database Edges`
- `## Code Edges`
- `## Documentation Edges`
- `## UI Edges`
- `## External Edges`

#### THREAD_INDEX.md Schema
```markdown
| Thread ID | Topic | Status | Last Updated | Participants | Created |
|-----------|-------|--------|--------------|--------------|---------|
```

### 3. Link Validation

- `parent_prd` must point to existing PRD file
- All referenced files must exist
- Cross-references must be valid

### 4. Status Validation

- Implementation status must match actual completion
- Valid statuses: `not_started`, `in_progress`, `complete`, `blocked`, `deprecated`

### 5. Naming Convention Validation

- Thread folders: `{thread_name}` (lowercase_with_underscores)
- Thread messages: `YYYYMMDD_HHIISS_ACTOR_PURPOSE_TITLE.md`
- Implementation folders: `{number}_{name}`

## Exit Codes

| Code | Meaning | Action |
|------|---------|--------|
| 0 | All validations pass | Continue |
| 1 | Missing required files | Block commit |
| 2 | Schema validation failed | Block commit |
| 3 | Link integrity failed | Block commit |
| 4 | Naming convention violation | Block commit |

## Implementation Details

### Python Script Structure
```python
class ImplementationValidator:
    def validate_all_implementations(self)
    def validate_implementation_folder(self, impl_path)
    def validate_readme_frontmatter(self, readme_path, impl_name)
    def validate_authors_schema(self, authors_path, impl_name)
    def validate_edges_sections(self, edges_path, impl_name)
    def generate_report(self)
```

### Error Reporting
- JSON report saved to `validation_report.json`
- Human-readable output to console
- Specific error messages with file locations

### CI Integration
- Pre-commit hook: `scripts/pre_commit_validate.py`
- GitHub Actions workflow integration
- Exit code enforcement

## Usage

```bash
# Validate all implementations
python scripts/validate_implementation.py

# Validate specific repository
python scripts/validate_implementation.py /path/to/repo

# Pre-commit validation
python scripts/pre_commit_validate.py
```

## Migration Support

For legacy implementations (before 2026-04-02):
- Check for `doc_compliance: partial` flag
- Allow missing files with warnings
- Require minimal stub files
- Enforce full compliance after 2026-07-02

---
*This specification ensures all Lupopedia implementations maintain the five-layer documentation architecture standards.*
