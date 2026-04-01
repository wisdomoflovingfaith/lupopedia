---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331190000"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/observations.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/observations.md"
  last_modified_utc: "20260331190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "headers-version-2.0-observations"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "doctrine"
  artifact_kind: "observations"
  purpose: "Design decisions and lessons learned for header version 2.0"
  tags:
  - "headers"
  - "observations"
  - "version-2.0"
---

# LUPOPEDIA HEADERS - Version 2.0 Observations

## Design Decisions

### 1. Single-Field Versioning

**Decision**: Replace `version_when_written` with `when_updated`.

**Rationale**: 
- Multiple version fields caused confusion (which one is authoritative?)
- `when_updated` clearly indicates logical content change
- `last_modified_utc` handles file system modifications separately

**Lesson**: Version fields should have single, unambiguous purpose.

### 2. Federation Node ID

**Decision**: Add explicit `federation_node_id` field.

**Rationale**:
- Web path alone insufficient for cross-node content
- External research nodes need clear identification
- Enables proper routing for federated content

**Lesson**: Explicit identifiers beat implicit inference.

### 3. Structured Verification

**Decision**: Replace flat verifier fields with `verified_by` and `verified_via` objects.

**Rationale**:
- Flat fields (last_verified_by, last_verified_by_actor_id) mixed identity and surface
- Structured objects allow future extension (department scope, agent identification)
- Clear separation between WHO verified and HOW they verified

**Lesson**: Structured data beats flat fields for extensibility.

### 4. Day-Based Verification

**Decision**: Footer `last_verified` uses YYYYMMDD (8 digits) not YYYYMMDDHHIISS.

**Rationale**:
- Verification is a daily trust marker, not a precise event timestamp
- Reduces noise in footers
- Human readability improves
- Staleness detection works at day granularity

**Lesson**: Choose timestamp granularity appropriate to the use case.

## Lessons Learned

### 1. Separation of Concerns

**Problem**: Version 1.0 mixed content timestamps, file timestamps, and verification timestamps.

**Solution**: Three distinct fields:
- `when_updated` - content changed
- `last_modified_utc` - file written
- `last_verified` - content verified

**Takeaway**: Different temporal concepts need different fields.

### 2. Agent vs Actor Ambiguity

**Problem**: Unclear whether verification should be attributed to agents or actors.

**Solution**: Agents are a subset of actors with `actor_type='agent'`. Use `actor_id` universally; `identity_type` disambiguates.

**Takeaway**: Single identifier type with type discriminator beats multiple identifier fields.

### 3. Backward Compatibility Window

**Problem**: Abrupt version changes break tooling.

**Solution**: 
- 4.0.88: Accept both, warn on deprecated
- 4.0.89: Reject deprecated
- 4.0.93: Version 2.0 final

**Takeaway**: Gradual deprecation with warning period reduces disruption.

## What Worked Well

1. **Versioned documentation** - versions directory allows per-version specifications
2. **Structured footers** - clear separation of verification metadata
3. **Federation node ID** - enables cross-node content management
4. **Required actor attribution** - every header identifies its author

## What Could Improve (for 3.0)

1. **Automated version detection** - tooling to detect and suggest version upgrades
2. **Validation profiles** - strict vs. permissive validation modes
3. **Header templates** - version-specific templates for new files
4. **Migration automation** - scripts to automatically migrate 1.0 → 2.0

## Version 2.0 Success Metrics

| Metric | Target | Status |
|--------|--------|--------|
| Adoption rate | 100% by 4.0.95 | In progress |
| Validation errors | < 1% | ✅ Achieved |
| Migration time | < 5 min per file | ✅ Achieved |
| Tooling support | All scripts updated | ✅ Complete |
