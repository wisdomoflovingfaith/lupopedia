---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260402000000"
  file_path_from_root: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/26_five_layer_documentation_architecture.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "five-layer-architecture"
  author:
    type: "actor"
    id: 1
    name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "architecture"
  purpose: "Defines the five-layer documentation architecture for Lupopedia"
  tags:
  - "prd"
  - "documentation"
  - "architecture"
  - "five-layer"
  # Required validation fields
  prd_id: 26
  prd_slug: five_layer_documentation_architecture
  title: "Five-Layer Documentation Architecture"
  status: "approved"
  parent_edges_ref: "lupo-docs/implementations/26_five_layer_documentation_architecture/edges.md"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/implementations/26_five_layer_documentation_architecture/"
      type: implements
      weight: 1.0
      reason: "Implementation of this architecture"
    - to: "lupo-docs/prd/root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: defines
      weight: 0.8
      reason: "Tier 2 runtime content authority"
lupopedia.footer:
  last_verified: "20260402"
  verified_by:
    identity_type: "actor"
    actor_id: 2
    name: "LILITH"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "none"
  orchestrator: "lilith:audit"
  next_action:
    - "Create validation script (validate_implementation.py)"
    - "Add doc_arch_version to existing implementations"
    - "Migrate legacy implementations within 90 days"
---

# PRD 26: Five-Layer Documentation Architecture

## 1. Purpose

Define the canonical documentation architecture for Lupopedia, ensuring complete knowledge provenance across WHAT, HOW, WHY, WHO, and WHERE layers.

## 2. Two-Tier Documentation Authority

Lupopedia maintains two distinct documentation domains with different authority models:

| Tier | Type | Authority | Source | Examples | PRD Scope |
|------|------|-----------|--------|----------|-----------|
| **Tier 1** | Authored Documentation | Filesystem | Human-edited files | PRDs, implementations, doctrines, decisions | **This PRD** |
| **Tier 2** | Runtime Content | Database | Web tracking, user actions | Page visits, navigation paths, discovered edges | PRD 28 (The Eye Widget) |

### 2.1 Tier 1: Authored Documentation (This PRD)

**Source of Truth:** Filesystem  
**Authority Model:** Human editors write files → `import_content.py` syncs to database  
**Scope:** All human-created documentation (PRDs, implementations, doctrines, decisions)

Key characteristics:
- Files are the canonical source
- Database is a read-only mirror for querying
- All relationships defined in `edges.md`
- Version controlled via git
- Governed by this PRD's five-layer architecture

### 2.2 Tier 2: Runtime Content (The Eye Widget)

**Source of Truth:** Database  
**Authority Model:** Web tracking discovers content → database stores directly  
**Scope:** Machine-discovered content from external sources

**Governing PRD:** PRD 28 (Semantic Monitoring Widget)

For table schemas, query patterns, and implementation details, see PRD 28.

Key characteristics:
- Database is the canonical source (no corresponding files)
- Content discovered, not authored
- Relationships discovered from user behavior
- No version control (live data)
- Governed by PRD 28 (Semantic Monitoring Widget)

### 2.3 Critical Separation

**These tiers do not overlap and do not conflict:**

- **Tier 1** documents how to build systems
- **Tier 2** tracks what systems actually do
- **Tier 1** is prescriptive (what should be)
- **Tier 2** is descriptive (what is)

**Example:**
- Tier 1: PRD defines "The Eye should track page navigation"
- Tier 2: The Eye discovers "Users navigate from A to B 100 times"

### 2.4 Cross-Tier Interactions

When Tier 2 content needs to reference Tier 1 documentation:
```sql
-- Link runtime content to its specification
INSERT INTO lupo_edges (
  left_object_type = 'content',  -- Runtime page
  left_object_id = 12345,
  right_object_type = 'content', -- PRD specification
  right_object_id = 67890,
  edge_type = 'implements',
  flare_reason = 'Page implements PRD requirement'
);
```

## 3. The Five Layers

| Layer | Question | Location | Purpose | Tier |
|-------|----------|----------|---------|------|
| **WHAT** | What to build? | `lupo-docs/prd/` | Requirements | Tier 1 |
| **HOW** | How to build? | `lupo-docs/implementations/` | Technical execution | Tier 1 |
| **WHY** | Why these decisions? | `discussions/` threads | Rationale | Tier 1 |
| **WHO** | Who built it? | `authors.md` | Provenance | Tier 1 |
| **WHERE** | Where does it connect? | `edges.md` | System mapping | Tier 1 |

**Note:** This five-layer architecture applies to Tier 1 (authored documentation). Tier 2 (runtime content) uses database-native structures.

## 3. Required Schemas

**Header Optimization:** Only `actor_id` is required in headers. `actor_name` is resolved dynamically from the actor registry at display time to prevent data drift.

### 3.1 PRD Front-Matter

```yaml
---
lupopedia.schema: prd
prd_id: 26
prd_slug: five_layer_documentation_architecture
title: "Five-Layer Documentation Architecture"
status: "draft|review|approved|implemented|deprecated"
parent_edges_ref: "lupo-docs/implementations/26_five_layer_documentation_architecture/edges.md"  # REQUIRED; MUST be generated by tooling; manual edits forbidden
---
```

### 3.2 Implementation README Front-Matter

```yaml
---
content_id: 202604020000000001   # Deterministic: YYYYMMDDHHIISS + 4-digit sequence (assigned on import)
parent_prd: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
status: "not_started|in_progress|complete|blocked|deprecated"
version: "1.0.0"
last_reviewed_utc: "20260402000000"
doc_arch_version: 1
---
```

### Content ID Generation

- `content_id` uses deterministic primary key generation
- Format: `YYYYMMDDHHIISS` + 4-digit sequence (0000-9999)
- Assigned on import via database sequence, not generated beforehand
- Ensures uniqueness without AUTO_INCREMENT

### Versioning Relationship

| `header_format_version` | `doc_arch_version` | Compatibility |
|------------------------|--------------------|---------------|
| 2 | 1 | ✅ Compatible |
| 2 | 2 | Future |
| 3 | 1 | ❌ Incompatible |

**Note:** `header_format_version` (PRD 16) defines header structure. `doc_arch_version` (this PRD) defines implementation architecture.

### 3.3 `authors.md` Schema

**Actor Registry Dependency:** All `actor_id` or `agent_key` values MUST correspond to entries in the central actor registry (defined in PRD 07 and implementation). Until a formal registry is fully deployed, identifiers MUST be stable within the repository and not reused for different identities.

```markdown
| actor_id | actor_type | role | scope | first_contribution_utc | last_contribution_utc |
|----------|------------|------|-------|------------------------|----------------------|
| 1 | system | architect | full | 20260401000000 | 20260402000000 |
| 102 | actor | implementer | code | 20260401000000 | 20260402000000 |
```

**Identifier Rules:**
- **actor_id**: Numeric identifier ONLY (e.g., 1, 2, 102) - REQUIRED
- String agent_keys (e.g., wolfie, lilith) are NOT allowed in authors.md
- Use numeric actor_id from central actor registry

**Actor ID Resolution:**
- `author.id` MUST be numeric from actor registry
- Agent keys resolved via `AgentDiscovery::getActor('wolfie')->actor_id` at runtime
- Validators reject non-numeric actor_id values

**Actor Types:**
- `actor` - Hybrid human/agent (used in web interface)
- `agent` - AI agents (general purpose)
- `system` - Kernel agents (WOLFIE, LILITH, ANUBIS, etc.)
- `user` - Auth users (just you until v4.1.0)

### 3.4 `edges.md` Schema

```markdown
## Database Edges
- TABLE: table_name
- COLUMN: col -> ref_col

## Code Edges
- CLASS: ClassName
- METHOD: ClassName::methodName()

## Documentation Edges
- PRD: 25_departments_system.md

## UI Edges
- ROUTE: /path
- TEMPLATE: template.php

## External Edges
- API: service_name
```

### 3.5 `discussions/THREAD_INDEX.md` Schema

```markdown
| thread_id | thread_slug | channel_ref | summary | status | created_utc |
|-----------|-------------|-------------|---------|--------|-------------|
| 1 | database_schema | 42 | Design decisions for DB schema | active | 20260402000000 |
| 2 | foreign_key_policy | 42 | Constitutional violation resolution | resolved | 20260402000000 |
| 3 | permission_model | 42 | Role-based access control design | active | 20260402000000 |
```

## 4. Validation & Enforcement

### 4.1 Required Files

Every implementation folder MUST contain:
- `README.md` 
- `authors.md` 
- `edges.md` 
- `discussions/THREAD_INDEX.md` 

**If no decisions have yet been recorded,** `discussions/THREAD_INDEX.md` MUST exist with an empty table and a note:

```markdown
| thread_id | thread_slug | channel_ref | summary | status | created_utc |
|-----------|-------------|-------------|---------|--------|-------------|
| (none) | | | No decision threads recorded as of 20260402000000 | | |
``` 

### 4.2 Validation Script Lifecycle

- **Location:** `lupo-scripts/validate_implementation.py` 
- **Versioning:** Script version MUST match `doc_arch_version` 
- **CI Enforcement:** MUST be wired into primary CI pipeline; merges touching `lupo-docs/` MUST fail on non-zero exit codes
- **Compatibility:** Validators MUST treat older `doc_arch_version` values as partial-compliance but MUST NOT silently upgrade schemas

`lupo-scripts/validate_implementation.py` MUST be created as part of this PRD's implementation.

**Dependencies:** Python 3.x, no external packages (uses standard library only)

**Validation Checks:**
1. **Presence**: `authors.md`, `edges.md`, `discussions/THREAD_INDEX.md` exist
2. **Schema**: Required front-matter fields present and correctly formatted
3. **Links**: `parent_prd` points to existing PRD file
4. **Status**: Implementation status is one of the allowed values (`not_started|in_progress|complete|blocked|deprecated`) and, if `status: complete`, required files and schemas pass all other checks. Actual functional completeness is out of scope for this validator and MUST be handled via test coverage and manual review.
5. **Naming**: Thread folders follow `NN_slug` pattern, messages follow `YYYYMMDD_HHIISS_ACTOR_PURPOSE_TITLE.md` 
6. **Path Mismatch**: `file_path_from_root` in YAML MUST match the actual physical location of the file. If the file is moved without updating the header, validation fails with exit code 2. 

### 4.3 Exit Codes

| Code | Meaning |
|------|---------|
| 0 | All validations pass |
| 1 | Missing required files |
| 2 | Schema validation failed |
| 3 | Link integrity failed |

### Multi-PRD Implementations

Shared modules that implement multiple PRDs MUST:
- Declare all related PRD ids in a `related_prds` field in README front-matter
- Use the primary PRD as `parent_prd` 
- Link to other PRDs via `lupopedia.edges` in the implementation README

## 5. Transition Policy

| Date | Milestone |
|------|-----------|
| 2026-04-02 | Architecture defined |
| 2026-04-09 | All new implementations must comply |
| 2026-07-02 | All legacy implementations migrated |

**Legacy implementations** (before 2026-04-02) marked with `doc_compliance: partial`.

### Stub Implementations

For reserved PRD IDs or future implementations not yet started:

- `README.md` status: `not_started` 
- `doc_compliance: stub` 
- `authors.md` MUST exist with at minimum: `| 0 | system | placeholder | stub | 20260402000000 | 20260402000000 |` (numeric actor_id only) 
- `edges.md` MUST exist with all sections present but may contain "PENDING" placeholders
- `discussions/THREAD_INDEX.md` MUST exist with the empty table pattern defined above

## 6. Versioning

`doc_arch_version: 1` — increment when schemas change.

## 7. Dependencies

- PRD 00: Root Constitutional Requirements
- PRD 05: Versioned Documentation Structure
- PRD 16: Lupopedia File Headers and Verification (header structure, author/verifier fields)
- Channel 42: Development coordination

**Note:** All references to constitutional PRDs MUST avoid `00_root` prefixes; use canonical slugs instead (Rule 17).

---

**Status:** APPROVED
**Constitutional Adherence:** FULL
