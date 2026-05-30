---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402000000"
  file_path_from_root: "docs/implementations/VALIDATION_GUIDE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/VALIDATION_GUIDE.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: guide
  thread_id: "validation-guide"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Five-Layer Documentation Validation Guide

## Overview

The Lupopedia Five-Layer Documentation Architecture includes automated validation to ensure all implementations comply with the required schemas and standards.

## Validation Script

### Running Validation

```bash
# Validate all implementations
python scripts/validate_implementation.py

# Validate from any directory
python scripts/validate_implementation.py /path/to/repo
```

### What It Validates

1. **Required Files**: Checks for `authors.md`, `edges.md`, `discussions/THREAD_INDEX.md`
2. **Schema Compliance**: Validates required front-matter fields
3. **Link Validation**: Ensures `parent_prd` points to existing files
4. **Table Formats**: Validates `authors.md` and `THREAD_INDEX.md` table schemas
5. **Section Requirements**: Checks `edges.md` has all required sections

## Required Schemas

### PRD Front-Matter

```yaml
---
lupopedia.schema: prd
id: 25
slug: departments_system
title: "Departments System"
status: "draft|review|approved|implemented|deprecated"
parent_edges_ref: "/docs/implementations/26_five_layer_documentation_architecture/edges.md"  # REQUIRED; MAY be auto-populated by tooling but MUST be present in committed PRDs
---
```

### Implementation README Front-Matter

```yaml
---
lupopedia.headers:
  # ... other fields
  content_id: 202604020000001234   # Deterministic: YYYYMMDDHHIISS + 4 random digits
  parent_prd: "/docs/prd/26_five_layer_documentation_architecture.md"
  status: "not_started|in_progress|complete|blocked|deprecated"
  version: "1.0.0"
  last_reviewed_utc: "20260402000000"
  doc_arch_version: 1
---
```

### authors.md Required Table

```markdown
| actor_id | actor_type | role | scope | first_contribution_utc | last_contribution_utc |
|----------|------------|------|-------|------------------------|----------------------|
| 102 | actor | architect | full | 20260402000000 | 20260402000000 |
| 2 | agent | reviewer | constitutional | 20260402000000 | 20260402000000 |
```

**Identifier Types:**
- **actor_id**: Numeric identifier (e.g., 1, 2, 102)
- **agent_key**: String identifier (e.g., wolfie, lilith, cursor)
- Use whichever is the primary identifier for the actor

**Actor Types:**
- `actor` - Hybrid human/agent (used in web interface)
- `agent` - AI agents (general purpose)
- `system` - Kernel agents (WOLFIE, LILITH, ANUBIS, etc.)
- `user` - Auth users (just you until v4.1.0)

### edges.md Required Sections

- `## Database Edges`
- `## Code Edges`
- `## Documentation Edges`
- `## UI Edges`
- `## External Edges`

### THREAD_INDEX.md Required Table

```markdown
| Thread ID | Topic | Status | Last Updated | Participants | Created |
|-----------|-------|--------|--------------|--------------|---------|
```

## CI Integration

### Pre-commit Hook

The validation can be integrated as a pre-commit hook:

```bash
# Install pre-commit hook
cp scripts/pre_commit_validate.py .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit
```

### GitHub Actions (Future)

```yaml
name: Validate Documentation
on: [push, pull_request]
jobs:
  validate:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Validate Implementations
        run: python scripts/validate_implementation.py
```

## Error Resolution

### Common Errors

1. **Missing required field**
   - Add the missing field to the appropriate front-matter

2. **File not found**
   - Create the missing file or update the path

3. **Invalid status**
   - Use one of the allowed status values

4. **Table format error**
   - Ensure tables have the correct column headers

### Transition Policy

For legacy implementations (before 2026-04-02):

1. Mark with `doc_compliance: partial` in README
2. Create minimal files with placeholder data
3. Gradually migrate during maintenance

## Validation Report

The script generates `validation_report.json` with:
- Timestamp
- All errors and warnings
- Summary statistics
- Pass/fail status

## Best Practices

1. **Run validation locally** before pushing
2. **Fix errors immediately** - don't accumulate
3. **Keep schemas updated** when architecture changes
4. **Document exceptions** for legacy code

---
*This guide ensures all Lupopedia documentation maintains the five-layer architecture standards.*
