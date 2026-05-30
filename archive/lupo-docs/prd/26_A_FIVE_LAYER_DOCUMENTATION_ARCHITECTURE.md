---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/26_five_layer_documentation_architecture.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/five-layer-documentation-architecture
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_26_A
  title: "PRD 26: Five-Layer Documentation Architecture"
  summary: "Defines five-layer documentation architecture (WHAT/HOW/WHY/WHO/WHERE) and two-tier authority model for Lupopedia."
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
**Authority Model:** Human editors write files -> `import_content.py` syncs to database  
**Scope:** All human-created documentation (PRDs, implementations, doctrines, decisions)

Key characteristics:
- Files are the canonical source
- Database is a read-only mirror for querying
- All relationships defined in `edges.md`
- Version controlled via git
- Governed by this PRD's five-layer architecture

### 2.2 Tier 2: Runtime Content (The Eye Widget)

**Source of Truth:** Database  
**Authority Model:** Web tracking discovers content ???????? database stores directly  
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
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/implementations/26_five_layer_documentation_architecture/README.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/implementations/26_five_layer_documentation_architecture/README.md"
  status: "draft"
  when_updated: "20260418210404"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: implementation
  artifact_kind: README
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: 26
  content_slug: "five-layer-documentation-architecture"
  default_collection_id: null
  lupopedia.schema: implementation
  title: "Five-Layer Documentation Architecture Implementation"
  summary: "Implementation tracking for PRD 26"
---

# Implementation Status
status: "not_started"
version: "1.0.0"
last_reviewed_utc: "20260402000000"
doc_arch_version: 1
parent_edges_ref: "lupo-docs/implementations/26_five_layer_documentation_architecture/edges.md"
# NOTE: This path is authoritative. Edges file should exist. Manual edits allowed until automated tooling is available.
```

**Legacy header keys:** **`validate_lupopedia_headers_universal.py`** and related tooling MAY still accept **`prd_id`**, **`prd_slug`**, and **`parent_prd`** as aliases during migration (**PRD 16**). **New** Tier 1 files SHOULD use **`pk_id`**, **`pk_slug`**, **`parent_pk_id`** (and the full **PRD 16** envelope where in scope).

### 3.2 Implementation README Front-Matter

```yaml
---
# content_id: omit for normal authoring; set by import (see PRD 16 and import_content.py --write-back)
parent_pk_id: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
status: "not_started|in_progress|complete|blocked|deprecated"
version: "1.0.0"
last_reviewed_utc: "20260402000000"
doc_arch_version: 1
---
```

### Content ID Generation

- **`content_id`** is the **`lupo_contents.content_id`** BIGINT -- **application-layer** assignment on import, **not** hand-authored to match **`pk_id`** or other ID namespaces (see [PRD 16](16_lupopedia_headers.md)).
- **Implementation in this repo:** `lupo-scripts/import_content.py` -> **`calculate_content_id()`** -- UTC timestamp + random suffix with **collision retry** against **`lupo_contents`**. This is **not** MySQL **`AUTO_INCREMENT`**, not vendor **`SEQUENCE`** objects, and not DB-generated IDs (constitutional database doctrine).
- Optional **`--write-back`** writes **`content_id`** into the file after a successful upsert. Until then, omit **`content_id`** in Tier 1 authoring when possible.
- Uniqueness is enforced in **application code**; constitution forbids **`AUTO_INCREMENT`** on registry-style IDs.

### Versioning Relationship

| `header_format_version` | `doc_arch_version` | Compatibility |
|------------------------|--------------------|---------------|
| 2 | 1 | [x] Compatible |
| 2 | 2 | Future |
| 3 | 1 | [ ] Incompatible |

**Note:** `header_format_version` (PRD 16) defines header structure. `doc_arch_version` (this PRD) defines implementation architecture.

### 3.3 `authors.md` Schema

**Actor registry dependency:** All **`actor_id`** values MUST correspond to entries in the canonical registry (**`lupo-database/lupopedia/actors/actor_id/registry.json`**). [PRD 07](07_agents_faucets.md) covers agents/faucets; numeric **`actor_id`** authority is the registry file.

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
- `user` - Auth users (just you until v4.2.0)

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
| thread_id | prd_cluster | channel_ref | summary | status | created_utc |
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
| thread_id | prd_cluster | channel_ref | summary | status | created_utc |
|-----------|-------------|-------------|---------|--------|-------------|
| (none) | | | No decision threads recorded as of 20260402000000 | | |
``` 

### 4.2 Validation Script Lifecycle

- **Location:** `lupo-scripts/validate_implementation.py` 
- **Versioning:** Script version MUST match `doc_arch_version` 
- **CI Enforcement:** MUST be wired into primary CI pipeline; merges touching `lupo-docs/` MUST fail on non-zero exit codes
- **Compatibility:** Validators MUST treat older `doc_arch_version` values as partial-compliance but MUST NOT silently upgrade schemas

`lupo-scripts/validate_implementation.py` implements this contract (see repository for current behavior).

**Dependencies and constitution:** Validation MUST remain **application-layer** -- **no** database triggers, **no** ORM, **no** vendor-specific SQL for enforcement. Checks are **filesystem-first** (presence, paths, YAML front matter, link targets). Per root rules, tooling MUST NOT introduce **npm**, **Composer**, or ad-hoc package ecosystems for this validator. **PyYAML** (`yaml`) may be used for YAML parsing where required, consistent with **`import_content.py`** and other **`lupo-scripts/`** utilities (stdlib + shared script deps only).

**Validation Checks:**
1. **Presence**: `authors.md`, `edges.md`, `discussions/THREAD_INDEX.md` exist
2. **Schema**: Required front-matter fields present and correctly formatted
3. **Links**: **`parent_pk_id`** (or legacy **`parent_prd`** where validators still accept it) points to existing PRD file
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
- Use the primary PRD path or id as **`parent_pk_id`** (legacy: **`parent_prd`**) 
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

`doc_arch_version` starts at **`1`** -- increment when **schemas in this PRD** (required files, front-matter keys, or validator contract) change.

### 6.1 `doc_arch_version` and Release & Migration Doctrine (4.0.x and 4.1.x)

Until **4.2.0**, there is **no** Lupopedia->Lupopedia upgrade path guarantee (bootstrap and active development phases) and **no** in-place migration of documentation **folder trees** driven by a SQL migration. When **`doc_arch_version`** increments:

- Affected **`lupo-docs/implementations/<nn>_.../`** trees SHOULD be treated as **fresh layout**: recreate from **`lupo-docs/implementations/_template/`** (or equivalent) and re-apply content, **or** manually align files to the new schema -- **not** automated DB migration of doc structure.
- **Database** schema for the product remains governed by **install SQL + seed** only (see constitutional **Release & Migration Doctrine**): documentation versioning here is **Tier 1 filesystem** policy, not `ALTER TABLE` on doc metadata.

Do not interpret **`doc_arch_version`** as requiring a database migration script before 4.2.0.

## 7. Dependencies

- [PRD 00](00_root_constitutional_system_requirements.md): Root Constitutional Requirements
- PRD 05: Versioned Documentation Structure
- [PRD 16](16_lupopedia_headers.md): Lupopedia File Headers and Verification (header structure, author/verifier fields, **`content_id`** / import)
- [PRD 07](07_agents_faucets.md): Agents and faucets (cross-reference; canonical **`actor_id`** values live in **`registry.json`**)
- Channel 42: Development coordination
- [PRD 38](38_memory_unification.md): Memory Unification (edge types, contexts, statuses)
- [PRD 51](51_memory_graph_as_source_of_truth.md): Memory Graph as Source of Truth
- [PRD 28](28_semantic_monitoring_widget.md): The Eye Widget (Tier 2 runtime content)

**Note:** Filenames use numeric prefixes (e.g. **`00_`**) for sort order; **Rule 17** applies to slug fields and display names, not the requirement to omit numeric prefixes from filenames.

## LILITH audit record (final)

| Field | Value |
|-------|--------|
| **Verdict** | **APPROVED** -- **`lupopedia.headers.status`** set to **`active`** |
| **Accuracy (reported)** | 96/100 |
| **Clarifications applied** | **`content_id`** language aligned with application-layer import (**`import_content.py::calculate_content_id`**); validation dependencies clarified (filesystem / app-layer, no DB triggers); **`doc_arch_version`** tied to fresh-install doctrine for 4.0.x |
| **Constitutional violations** | None reported |

---

**Status:** ACTIVE (LILITH final audit 2026-04-03 UTC; `lupopedia.headers.status: active`)

**Constitutional adherence:** FULL


---

## Memory Graph Integration

For memory graph doctrine (edge types, contexts, statuses, directions), see:
- **PRD 38** ??? Memory Unification
- **PRD 51** ??? Memory Graph as Source of Truth
```

## Related documentation indexes

- **[PRD_GAPS.md](../doctrine/PRD_GAPS.md)** ??? reserved vs missing two-digit PRD numbers (`NN_` namespace).
- **[DOCTRINE_PRD_LINKAGE_AUDIT.md](../audits/DOCTRINE_PRD_LINKAGE_AUDIT.md)** ??? doctrine-to-PRD linkage policy and audit status.
- **[ORGANIZATION.md](../../ORGANIZATION.md)** ??? root repository map; defers layering authority to **this PRD**.
