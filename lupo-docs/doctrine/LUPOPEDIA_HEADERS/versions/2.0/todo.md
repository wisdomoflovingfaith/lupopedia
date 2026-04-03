---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: todo
  when_updated: "20260331190000"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/todo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/todo.md"
  last_modified_utc: "20260331190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "headers-version-2.0-todo"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "todo"
  artifact_kind: "plan"
  purpose: "Remaining tasks for header version 2.0 and roadmap to 3.0"
  tags:
  - "headers"
  - "todo"
  - "version-2.0"
  - "roadmap"

lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260403113047"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"

---

# LUPOPEDIA HEADERS - Version 2.0 Todo

## Immediate Tasks (2.0 Completion)

### High Priority
- [ ] Update all existing headers to version 2.0 format
- [ ] Run `validate_lupopedia_headers_universal.py` on all markdown files
- [ ] Fix any validation errors
- [ ] Update `when_updated` on all touched files
- [ ] Add `federation_node_id` to all headers (default 0)
- [ ] Add `actor_id` and `actor_name` to all headers
- [ ] Update footers with structured `verified_by` and `verified_via`

### Medium Priority
- [ ] Create `versions/2.0/field_matrix.md` with complete field reference
- [ ] Add version compatibility table to README.md
- [ ] Update `generate_headers_from_db.py` to output version 2.0 format
- [ ] Update `import_content.py` to parse version 2.0 headers

### Low Priority
- [ ] Add header version to `lupo_metadata` for version tracking
- [ ] Create migration script for batch 1.0 → 2.0 conversion

## Version 3.0 Roadmap

### Under Consideration

#### 1. Automated Header Generation
- IDE plugins that generate headers on file creation
- Template system with version-aware fields

#### 2. Enhanced Federation
- Multi-node header propagation
- Synchronization markers for federated content

#### 3. Granular Verification
- Per-section verification (not just whole artifact)
- Multiple verifiers for different content sections

#### 4. Header Compression
- YAML minification for large repositories
- Separate metadata file for frequently changed headers

#### 5. Machine-Readable Schema
- JSON Schema for version 3.0
- OpenAPI-style documentation for headers

### Version 3.0 Timeline

| Phase | Target | Description |
|-------|--------|-------------|
| Planning | 4.0.95 | Requirements gathering |
| Design | 4.0.96 | Schema design and review |
| Implementation | 4.0.97-98 | Tooling updates |
| Migration | 4.0.99 | Migration scripts |
| Release | 4.1.0 | Version 3.0 final |

## Migration Status

| Component | 1.0 | 2.0 | Status |
|-----------|-----|-----|--------|
| `lupo-docs/` headers | ~85% | 15% | In progress |
| `lupo-channels/` headers | ~90% | 10% | In progress |
| `lupo-scripts/` comments | ~70% | 30% | In progress |
| `lupo-includes/` comments | ~60% | 40% | In progress |
| PRD files | 0% | 100% | ✅ Complete |

## Validation Checklist

Before closing version 2.0:

- [ ] All markdown files pass `validate_lupopedia_headers_universal.py --strict`
- [ ] All script comments have valid footers
- [ ] All PRDs use version 2.0 format
- [ ] Migration guide complete
- [ ] Version 1.0 support sunset documented
- [ ] Tooling updated to default to version 2.0
