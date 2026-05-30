---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260402210000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260402_210000_DECISION_prd31_rejection_parallel_classification.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260402_210000_DECISION_prd31_rejection_parallel_classification.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decision
  thread_id: "20260402-prd31-rejection"
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
# Decision: PRD 31 Rejection - Parallel Classification System

## Type
**Decision**

## Status
**Completed**

## Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

## Date
2026-04-02

## Context

COUNTERMEASURE identified that PRD 31 (Context System Framework) creates a parallel classification system that conflicts with PRD 26's five-layer architecture, specifically the WHERE layer (edges.md).

## Decision

**REJECT PRD 31** as currently written. The parallel classification system will fragment documentation architecture and create maintenance issues.

## Rationale

### COUNTERMEASURE's Valid Objections

1. **Parallel Classification to edges.md**
   - PRD 26 defines WHERE layer via `edges.md` with structured YAML
   - PRD 31 creates `contexts/` folder hierarchy
   - Two competing systems will never stay in sync

2. **Unbounded Taxonomy**
   - PRD 31 allows unlimited arbitrary context types
   - No schema or validation
   - Leads to fragmentation and drift

3. **No Machine Navigation**
   - `edges.md` is machine-navigable YAML
   - `contexts/` uses human-readable README.md files
   - Cannot be processed programmatically

4. **Conflicts with Five-Layer Architecture**
   - PRD 26 already provides complete framework:
     - WHAT: `docs/prd/`
     - HOW: `docs/implementations/`
     - WHY: `discussions/` folders
     - WHO: `authors.md`
     - WHERE: `edges.md`

### Constitutional Violations

1. **Rule 17**: `00_root` prefix in outbound edge (fixed)
2. **Path violation**: Missing leading slash in file_path_from_root (fixed)
3. **Architecture conflict**: Creates second classification system

## Alternative Approaches

### Option A: Integrate Context into edges.md (Recommended)

Add context taxonomy to existing WHERE layer:

```yaml
## Documentation Edges
- PRD: `docs/versions/4.0.94/prd/31_context_system.md`
  contexts: [decisions, decisions.prd]
```

### Option B: Create CONTEXT_TAXONOMY.md Doctrine

Define controlled vocabulary without directory structure:

```
docs/doctrine/CONTEXT_TAXONOMY.md
```

## Impact

### Positive
- Prevents architecture fragmentation
- Maintains single source of truth
- Preserves PRD 26's five-layer architecture

### Negative
- Context taxonomy must be integrated elsewhere
- Directory-based organization lost (but this was problematic)

## Implementation

1. **Removed `contexts/` directory** - Deleted entire folder structure
2. **PRD 31 workspace** - Working copy under `docs/versions/4.0.94/prd/31_context_system.md` for redesign only
3. **Removed CONTEXT_TAXONOMY.md** - Deleted doctrine file
4. **Database Cleanup**:
   - Removed `{{prefix}}contexts` table from install_new_lupopedia.sql
   - Removed `{{prefix}}contexts_map` table from install_new_lupopedia.sql
   - Removed duplicate `{{prefix}}edges` table that referenced contexts
   - Removed `import_contexts` flag from import_settings table
5. **Decision**: Use existing `tags` system for categorization

## Final Resolution

**Simplest approach adopted**: No context system needed. Use existing `tags` in headers for categorization. Avoids confusion with `lupo_contexts` database table and maintains architectural simplicity.

## Success Criteria

1. No parallel classification systems exist
2. Context handled via edges.md or tags
3. Five-layer architecture preserved
4. Single source of truth maintained

## Related Documentation

- **PRD 26**: Five-layer documentation architecture
- **COUNTERMEASURE analysis**: Identified the conflict
- **PRD 31**: Rejected parallel classification system

---

*Decision implemented 2026-04-02 21:00 UTC*
