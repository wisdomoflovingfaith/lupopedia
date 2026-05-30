---
lupopedia.headers:
  author:
    type: "actor"
    id: 1
    name: "wolfie"
  artifact_kind: documentation
  artifact_type: doctrine
  channel_id: 42
  delegation_chain: wolfie:root
  federation_node_id: 0
  file_path_from_root: lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md
  last_modified_utc: '20260406044907'
  lupopedia.schema: doctrine
  purpose: LUPOPEDIA header field taxonomy, validation rules, and database mapping
    (lupo_contents, metadata, edges, revision_history)
  tags:
  - tag-doctrine
  - tag-headers
  - tag-schema
  - tag-artifact-type
  - tag-artifact-kind
  - tag-documentation
  - tag-validation
  thread_id: headers-doctrine
  web_path: http://www.lupopedia.com/lupopedia/lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md
  when_updated: '20260406044907'
  title: Lupo rules root lupopediaheadersdoctrine
  content_id: 5629208585196930598
lupopedia.edges:
  outbound_edges:
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
    type: references
    weight: 1.0
    reason: Format, footer policy, tooling index
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md
    type: references
    weight: 1.0
    reason: Stable lupo-docs alias; mirrors tooling edges to import/validate scripts
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md
    type: references
    weight: 1.0
    reason: Validator behavior and scripts-in-scope
  - to: lupo-docs/doctrine/HEADER_DB_REVERSIBILITY_DOCTRINE.md
    type: references
    weight: 1.0
    reason: "DB \u2194 file round-trip semantics"
  - to: lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md
    type: references
    weight: 1.0
    reason: lupo_edges authority for outbound_edges after import
  - to: lupo-scripts/import_content.py
    type: references
    weight: 1.0
    reason: "Import markdown \u2192 lupo_contents + lupo_metadata + lupo_edges + revision_history"
  - to: lupo-scripts/ensure_imported.py
    type: references
    weight: 1.0
    reason: Run import when content_id missing
  - to: lupo-scripts/generate_headers_from_db.py
    type: references
    weight: 1.0
    reason: Regenerate YAML from DB (database-first default)
  - to: lupo-scripts/lib/header_db_sync.py
    type: references
    weight: 1.0
    reason: sync_header_artifact_to_db and build_yaml_data_from_db
  - to: lupo-scripts/lib/header_validation.py
    type: references
    weight: 1.0
    reason: Deterministic header validation; content_id warnings
  - to: lupo-scripts/validate_lupopedia_headers.py
    type: references
    weight: 1.0
    reason: CLI markdown header validation
  - to: lupo-scripts/validate_lupopedia_headers_universal.py
    type: references
    weight: 1.0
    reason: Universal cross-field header validation
  - to: lupo-scripts/validate_lupopedia_headers.php
    type: references
    weight: 0.9
    reason: PHP header validation
  - to: lupo-scripts/validate_footer_verification.py
    type: references
    weight: 0.85
    reason: Footer last_verified and verifier structure
  - to: lupo-scripts/validate_channel_artifacts.py
    type: references
    weight: 0.85
    reason: Channel artifact header scans
  - to: lupo-scripts/regenerate_headers_for_stale_files.py
    type: references
    weight: 0.8
    reason: Batch stale refresh; prefer generate_headers_from_db when artifact is
      imported
  - to: lupo-includes/classes/IdGenerator.php
    type: references
    weight: 1.0
    reason: ID generation implementation referenced in headers
  - to: lupo-rules/root/RULE_FILES_HEADER_REQUIREMENT.md
    type: references
    weight: 1.0
    reason: Header requirement rules for lupo-rules files
lupopedia.history:
- reason: Implementation exists in import/sync; almost no markdown files used the
    block in practice
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  event_id: 1
  actor_name: cursor
  event_date: '20260329002000'
  event_type: update
  description: Added live lupopedia.history to binding doctrine; documented repo-wide
    adoption gap
  faucet_slug: cursor
- reason: Finalize header doctrine for DB + markdown audit trail
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  event_id: 2
  actor_name: cursor
  event_date: '20260329003000'
  event_type: update
  description: "Documented dual running log \u2014 file lupopedia.history \u2194 lupo_contents.revision_history;\
    \ import/regenerate semantics; 4.0.89 verification"
  faucet_slug: cursor
lupopedia.footer:
  last_verified: 20260329003000
  next_action:
  - Enforce header requirements on all rule files
  - Keep database-first import/regenerate scripts aligned with lupo-database/lupopedia/json
    table mirrors
  - Update validator when taxonomy changes
  - Encourage optional lupopedia.history on binding docs where audit trail should
    sync to revision_history on import
  orchestrator: wolfie:root
  verified_by:
    actor_id: 1
    agent_name_identity: WOLFIE
    department_id_delta: 0
    identity_type: actor
  verified_via:
    faucet_slug: none
    type: direct
---
# file: Lupo rules root lupopediaheadersdoctrine — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupopedia/lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md

# LUPOPEDIA Headers Doctrine

**To:** All Agents and Documentation Writers  
**Channel:** 42  
**Thread:** headers-doctrine  
**Date:** 2026-03-28  
**Status:** LOCKED — Binding Authority  

**Single source of truth:** This file (`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`) is the **only** binding LUPOPEDIA HEADERS doctrine. The path `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md` is a **stable alias** (redirect stub); it must not copy or diverge from this document.

---

## ⚠️ **IMPORTANT: Binding Doctrine**

This document defines **ALL** header fields used in Lupopedia documentation. These rules are **NOT optional**. All documentation must comply.

### How this fits: filesystem, database, and deeper docs

| Layer | Role |
|-------|------|
| **Filesystem (this file)** | Canonical *authoring* rules: YAML blocks, field taxonomy, validation expectations for markdown and code artifacts. |
| **MySQL** | *Runtime authority* for imported content: `lupo_contents` row + `lupo_metadata` / `lupo_edges` / JSON columns as described in [Database-first mapping](#database-first-mapping-and-lupo_contents) below. |
| **Format + reversibility** | Block order, validators, export/import behavior: [`lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md), [`HEADER_DB_REVERSIBILITY_DOCTRINE.md`](../../lupo-docs/doctrine/HEADER_DB_REVERSIBILITY_DOCTRINE.md). (Stable alias to this file: [`lupo-docs/.../LUPOPEDIA_HEADERS_DOCTRINE.md`](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md) — pointer only.) |
| **Edge semantics** | Single edge table and types: [`EDGE_MODEL_DOCTRINE.md`](../../lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md). |

**Philosophy:** Agents often work on **files only**; the database still holds the **structural truth** for any artifact that has been **imported**. Regenerating headers from the DB without importing first can **drop** edges and history that exist only in canonical tables.

---

## 📋 **Required Header Sections**

Every Lupopedia document MUST have three core sections:

```yaml
---
lupopedia.headers:
  # Header fields go here
lupopedia.edges:
  # Document relationships go here
lupopedia.footer:
  # Footer information goes here
---
```

### Optional Sections

```yaml
---
lupopedia.headers:
  # Header fields go here
lupopedia.history:
  # Lifecycle tracking (optional but recommended for doctrine files)
lupopedia.edges:
  # Document relationships go here
lupopedia.footer:
  # Footer information goes here
---
```

---

## � **Deterministic Field Ordering (v4.0.93)**

All LUPOPEDIA HEADERS must follow this exact field order to ensure Notepad++ "Find in Files" can navigate the graph consistently:

```yaml
---
lupopedia.headers:
  # Core identification (first)
  lupopedia.schema: <schema>
  file_path_from_root: <path>
  web_path: <url>
  last_modified_utc: <YYYYMMDDHHIISS>
  when_updated: <YYYYMMDDHHIISS>
  federation_node_id: <int>
  
  # Context and threading
  channel_id: <int>
  thread_id: <slug>
  context_id: <bigint>  # optional, when finalized
  
  # Actor attribution
  actor_id: <int>
  actor_name: <string>
  delegation_chain: <chain>
  
  # Artifact classification
  artifact_type: <type>
  artifact_kind: <kind>
  purpose: <description>
  tags:
    - <tag>
    - <tag>
  
  # Optional fields (after required)
  content_id: <bigint>  # when imported
  header_format_version: <int>  # defaults to 2
  # ... other optional fields

lupopedia.edges:
  outbound_edges:
    - to: <path_or_slug_or_content_id>  # ASCII-safe filenames/slugs
      type: <edge_type>
      weight: <0.0-1.0>
      reason: <description>

lupopedia.footer:
  last_verified: <YYYYMMDDHHIISS>
  verified_by:
    identity_type: actor
    actor_id: <int>
    agent_name_identity: <name>
    department_id_delta: <int>
  verified_via:
    type: faucet
    faucet_slug: <slug>
  orchestrator: <chain>
  next_action:
    - <action>
    - <action>
---
```

### ASCII-Safe Filename and Slug Rules

- **Filenames**: Use only `a-z0-9_-.` (lowercase letters, numbers, underscore, hyphen, dot)
- **Slugs**: Use only `a-z0-9-` (lowercase letters, numbers, hyphen)
- **No spaces**: Use underscores or hyphens instead
- **No special chars**: Avoid `@#$%^&*()[]{}|\/:"'<>?`
- **Content IDs**: BIGINT values for database references

### Edge Reference Types

Edges may reference:
1. **File paths**: `"lupo-docs/prd/01_core_identity.md"`
2. **Slugs**: `"headers-doctrine"` (for thread references)
3. **Content IDs**: `5629208585196930598` (when imported)

This ensures Notepad++ "Find in Files" can locate all references to any document.

---

## � **lupopedia.headers Fields**

### **Core Required Fields**

| Field | Required | Format | Example |
|-------|----------|--------|---------|
| `lupopedia.schema` | ✅ | lowercase, no spaces | `doctrine` |
| `file_path_from_root` | ✅ | relative path | `"lupo-rules/root/FILE.md"` |
| `web_path` | ✅ | full URL | `"http://www.lupopedia.com/..."` |
| `federation_node_id` | ✅ | integer | `0` |
| `last_modified_utc` | ✅ | YYYYMMDDHHIISS | `"20260328140000"` |
| `when_updated` | ✅ | YYYYMMDDHHIISS | `"20260328140000"` |
| `channel_id` | ✅ | integer | `42` |
| `thread_id` | ✅ | lowercase, hyphens | `"headers-doctrine"` |
| `context_id` | ⚠️ | integer (BIGINT) | Optional context reference when artifact is linked to a finalized knowledge context. Assigned when the artifact is promoted from channel discussion to context. |
| `actor_id` | ✅ | integer | `1` |
| `actor_name` | ✅ | lowercase, underscores | `"wolfie"` |
| `delegation_chain` | ✅ | actor:role | `"wolfie:root"` |
| `artifact_type` | ✅ | lowercase, underscores | `"doctrine"` |
| `artifact_kind` | ✅ | lowercase | `"documentation"` |
| `purpose` | ✅ | descriptive text | `"Purpose of document"` |
| `tags` | ✅ | array of strings | `["tag1", "tag2"]` |
| `content_id` | ⚠️ | integer (BIGINT) | Assigned when the file is **imported** into `lupo_contents`; validators **warn** if missing. Not a hand-edited field for normal authoring. |

### **Deterministic Tag Prefixing (v4.0.93)**

All tags in `lupopedia.headers.tags` MUST use canonical prefixes for global searchability and taxonomy consistency.

#### **Required Format**
- **Pattern**: `^tag-[a-z0-9-]+$`
- **Prefix**: `tag-` (required)
- **Namespace**: lowercase, hyphens only
- **No bare tags**: Unprefixed tags are forbidden

#### **Canonical Prefixes**
| Prefix | Use For | Examples |
|--------|---------|----------|
| `tag-prd` | Product Requirements | `tag-prd-core-identity` |
| `tag-doctrine` | Binding rules | `tag-doctrine-headers` |
| `tag-agent` | Agent definitions | `tag-agent-cursor` |
| `tag-actor` | Actor instances | `tag-actor-wolfie` |
| `tag-design` | Architecture designs | `tag-design-database` |
| `tag-implementation` | Code implementations | `tag-implementation-php` |
| `tag-decision` | Decisions | `tag-decision-approval` |
| `tag-question` | Questions | `tag-question-technical` |
| `tag-answer` | Answers | `tag-answer-resolution` |
| `tag-version` | Version-specific | `tag-version-4.0.93` |
| `tag-architecture` | Architecture | `tag-architecture-system` |
| `tag-database` | Database-related | `tag-database-schema` |
| `tag-constitutional` | Constitutional | `tag-constitutional-rule` |
| `tag-utility` | Utility scripts | `tag-utility-validator` |
| `tag-script` | Executable scripts | `tag-script-import` |
| `tag-status` | Status reports | `tag-status-completed` |
| `tag-plan` | Planning documents | `tag-plan-roadmap` |
| `tag-todo` | Task tracking | `tag-todo-feature` |
| `tag-thread` | Thread artifacts | `tag-thread-discussion` |
| `tag-broadcast` | Broadcasts | `tag-broadcast-announcement` |
| `tag-index` | Index documents | `tag-index-reference` |

#### **Validation Rules**
- **ERROR**: Tag without `tag-` prefix
- **ERROR**: Tag contains uppercase letters or special chars (except hyphens)
- **WARN**: Unknown namespace (should use canonical prefixes when possible)
- **INFO**: Semantic meaning should be clear from tag name

#### **Examples**
```yaml
# Valid tags
tags:
  - "tag-doctrine-headers"
  - "tag-prd-actor-authority"
  - "tag-implementation-validator"
  - "tag-version-4.0.93"

# Invalid tags
tags:
  - "headers"        # ERROR: missing prefix
  - "TAG-DOCTRINE"   # ERROR: uppercase
  - "tag@database"   # ERROR: invalid character
```

---

## 🔄 **Channel-to-Context Lifecycle**

### **Discussion Phase (Channels)**
Artifacts originate in channels for coordination and discussion:
- **`channel_id`**: Required - identifies the coordination channel
- **`thread_id`**: Required - identifies the discussion thread  
- **`context_id`**: Optional - null during discussion phase
- **Purpose**: Actors discuss implementation approaches, debate options, coordinate work

### **Finalization Phase (Contexts)**
When channel discussions result in finalized knowledge:
- **`context_id`**: Assigned - references the finalized knowledge context
- **`channel_id`**: May be retained for provenance
- **`thread_id`**: May be retained for discussion history
- **Purpose**: Artifact becomes part of permanent knowledge base

### **Database Relationship**
```
lupo_channels (discussion) → lupo_contexts (finalized knowledge)
        ↓                           ↓
   channel_artifacts ← context_artifacts
        ↓                           ↓
   lupo_edges (polymorphic relationships)
```

### **Context Questions & Answers**
Finalized contexts support:
- **Questions**: `lupo_context_questions` table
- **Answers**: `lupo_context_answers` table  
- **Edges**: `lupo_edges` with `edge_type = 'context_question_answer'`
- **Navigation**: Semantic search through context relationships

### **Channel Cleanup**
Channels can be deleted after discussion finalization:
- **Artifacts**: Preserved via `context_id` reference
- **Knowledge**: Maintained in `lupo_contexts` table
- **Provenance**: Retained through edge relationships

---

## 🎯 **lupopedia.schema Taxonomy**

The `lupopedia.schema` field classifies the document type using canonical values.

### **Valid Schema Values**

| Schema | Use For | Examples |
|--------|---------|----------|
| `doctrine` | Binding constitutional rules | Database Doctrine, Headers Doctrine |
| `rule` | Enforceable smaller rules | WSL Command Patterns, Header Requirements |
| `philosophy` | Worldview, principles, manifestos | Independent Coder's Manifesto |
| `plan` | Roadmaps, version plans | 4.0.89 PLAN.md |
| `todo` | Task tracking | 4.0.89 TODO.md |
| `changelog` | Version history | CHANGELOG.md |
| `directive` | WOLFIE orders, execution commands | Migration Directive |
| `design` | Architecture designs | Context Model Design |
| `review` | LILITH audits, code reviews | LILITH Audit Report |
| `report` | Status reports, completion reports | Phase Completion Report |
| `implementation` | Code implementation artifacts | AuthSessionManager.php |
| `script` | Executable scripts | validate_headers.py |
| `class` | PHP classes | IdGenerator.php |
| `index` | Directory indexes | THREAD_INDEX.md |
| `thread` | Channel thread artifacts | Thread messages |
| `broadcast` | Channel announcements | WOLFIE broadcasts |
| `alias` | Stable pointer / redirect stub (no duplicate binding text) | `lupo-docs/.../LUPOPEDIA_HEADERS_DOCTRINE.md` |

### **Schema Rules**

- Always use lowercase
- No spaces, hyphens, or underscores
- One word preferred
- Use closest category if none match exactly

---

## 📦 **artifact_type Classification**

The `artifact_type` field describes what the artifact IS.

### **Common Types**

| Type | Use For | Examples |
|------|---------|----------|
| `doctrine` | Binding rules documents | Database Doctrine |
| `rule` | Individual rule documents | WSL Command Patterns |
| `manifesto` | Philosophy/worldview documents | Independent Coder's Manifesto |
| `plan` | Planning documents | 4.0.89 PLAN.md |
| `todo` | Task tracking | 4.0.89 TODO.md |
| `changelog` | Version history | CHANGELOG.md |
| `directive` | WOLFIE orders | Migration Directive |
| `design` | Architecture documents | Context Model Design |
| `review` | Review/audit documents | LILITH Audit |
| `report` | Status/completion reports | Phase Report |
| `implementation` | Code files | PHP classes, scripts |
| `script` | Executable scripts | validate_headers.py |
| `class` | PHP classes specifically | IdGenerator.php |
| `index` | Directory/overview documents | THREAD_INDEX.md |
| `thread` | Channel thread artifacts | Thread messages |
| `broadcast` | Channel announcements | WOLFIE broadcasts |

---

## 🏷️ **artifact_kind Classification**

The `artifact_kind` field describes the domain or category of the artifact.

### **Common Kinds**

| Kind | Use For | Examples |
|------|---------|----------|
| `database` | Database-related | Database Doctrine |
| `documentation` | All documentation | Most .md files |
| `rule` | Rule documents | WSL Command Patterns |
| `philosophy` | Philosophy documents | Independent Coder's Manifesto |
| `version_specific` | Version-specific docs | 4.0.89 CHANGELOG |
| `plan` | Planning documents | 4.0.89 PLAN.md |
| `task` | Task tracking | 4.0.89 TODO.md |
| `execution` | Execution commands | Migration Directive |
| `architecture` | Design documents | Context Model Design |
| `audit` | Review/audit documents | LILITH Audit |
| `status` | Status reports | Phase Report |
| `code` | Implementation files | PHP classes |
| `utility` | Scripts and tools | validate_headers.py |
| `coordination` | Channel artifacts | Thread, broadcast |
| `index` | Directory indexes | THREAD_INDEX.md |

---

## 🔗 **lupopedia.edges Section**

Defines relationships to other documents **in the file**. After **import**, the same relationships are mirrored into **`lupo_edges`** (authoritative for runtime); the file is a **declaration/snapshot**. See [Database-first mapping](#database-first-mapping-and-lupo_contents).

```yaml
lupopedia.edges:
  outbound_edges:
    - to: "path/to/document.md"
      type: references | implements | extends | depends_on
      weight: 0.0 to 1.0
      reason: "Why this relationship exists"
```

### **Edge Types**

| Type | Meaning | Use When |
|------|---------|----------|
| `references` | Simple reference | General links |
| `implements` | Implements specification | Code implementing design |
| `extends` | Extends another document | Document building on another |
| `depends_on` | Required dependency | Document requires another |

---

## 📝 **lupopedia.footer Section**

Contains verification and maintenance information.

```yaml
lupopedia.footer:
  last_verified: "20260328140000"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  next_action:
    - "Action item 1"
    - "Action item 2"
```

### **Footer Fields**

| Field | Required | Format | Purpose |
|-------|----------|--------|---------|
| `last_verified` | ✅ | YYYYMMDDHHIISS | Last verification timestamp |
| `last_verified_by` | ✅ | actor name | Who verified |
| `last_verified_by_actor_id` | ✅ | integer | Actor ID |
| `orchestrator` | ✅ | actor:role | Current orchestrator |
| `next_action` | ✅ | array | Action items |

---

## 📜 **lupopedia.history Section** (Optional)

Tracks the complete lifecycle of an artifact, creating an audit trail within the file itself.

### Purpose

- **Accountability:** Who did what and when
- **Traceability:** Why changes were made
- **Audit Trail:** Complete record of reviews, updates, and approvals
- **Debugging:** Understand the history of decisions

### Format

```yaml
lupopedia.history:
  - event_id: 1
    event_type: creation
    event_date: "YYYYMMDDHHIISS"
    actor_id: integer
    actor_name: string
    faucet_slug: string
    description: string
    reason: string
    [additional fields per event type]
```

There is **no** separate `lupo_history` table in the current schema. On import, when the YAML contains a top-level `lupopedia.history` key, the payload is stored as JSON in **`lupo_contents.revision_history`**. Omitting `lupopedia.history` in the file leaves any existing DB `revision_history` unchanged (so DB-only history is not wiped by a header-only import).

### Repository adoption vs implementation (4.0.89+)

The **code path is implemented**: `import_content.py` → `sync_header_artifact_to_db` writes `revision_history` when the key is present; `build_yaml_data_from_db` can emit `lupopedia.history` again from the DB.

A repo-wide search for live front matter (top-level YAML key `lupopedia.history` outside fenced examples) previously found **no** ordinary markdown artifacts using it—only **documentation examples** inside this doctrine and references in tooling docs. That is an **authoring / adoption** gap, not a missing importer.

**Recommendation:** Use optional `lupopedia.history` on high-churn or binding artifacts (root doctrines, critical version docs) when an in-file audit trail should also live in **`lupo_contents.revision_history`** after import. This file includes a **live** `lupopedia.history` block in its own header as a reference instance.

### Event Types

| Type | Use When | Required Fields |
|------|----------|-----------------|
| `creation` | First version | `description`, `reason` |
| `review` | After review | `description`, `findings`, `resolution` (if issues) |
| `update` | Content/header change | `description`, `reason`, `affected_files` (optional) |
| `audit` | Formal audit | `description`, `reason`, `result` |
| `migration` | Moved between versions | `description`, `from_version`, `to_version` |
| `approval` | WOLFIE approval | `description`, `approved_by`, `approved_date` |

### Rules

- Events are sequential (`event_id` increments)
- Do not modify historical events (append only)
- Keep descriptions concise but meaningful
- Always include actor and timestamp

### Dual running log — file and database (4.0.89)

For artifacts that are **imported** (`content_id` present), the audit trail exists in **two** places that must stay **intentionally** aligned:

| Layer | Where | What gets written |
|-------|--------|-------------------|
| **Markdown file** | Top-level YAML `lupopedia.history` (optional list of events) | Human-authored **append-only** log: new rows for substantive edits, reviews, imports, scope changes |
| **Database** | `lupo_contents.revision_history` (JSON column) | **Snapshot** of the same list, updated when **`import_content.py`** runs **`sync_header_artifact_to_db`** and the file’s parsed YAML **includes** the `lupopedia.history` key |

**Import semantics (current implementation):**

- **Key present** — The YAML list is serialized to JSON and **replaces** the column value for that `content_id` (the file is authoritative for that import).
- **Key absent** — Import **does not** clear `revision_history` (DB-only history or prior sync is preserved).

**Regenerate (`generate_headers_from_db.py` / `build_yaml_data_from_db`):**

- When `revision_history` is non-empty, emitted YAML includes **`lupopedia.history`** so the **file** can be refreshed from DB truth.

**Operational pattern (running log):**

1. Append a new `event_id` to `lupopedia.history` when the artifact meaningfully changes.
2. Run **`import_content.py`** so **`revision_history`** matches.
3. Optionally run **`generate_headers_from_db.py`** after DB edits so the file matches DB.

**4.0.89 release:** Prove round-trip on at least one representative `.md` (see `lupo-docs/versions/4.0.89/TODO.md` H7) — validators must accept the block; **`--check-db`** should not warn spuriously when file and DB match after import.

### Example

```yaml
lupopedia.history:
  - event_id: 1
    event_type: creation
    event_date: "20260328140000"
    actor_id: 1
    actor_name: "wolfie"
    faucet_slug: "cursor"
    description: "Initial creation of database doctrine"
    reason: "Establish canonical database rules"
  
  - event_id: 2
    event_type: review
    event_date: "20260328143000"
    actor_id: 2
    actor_name: "lilith"
    faucet_slug: "cursor"
    description: "Critical review of database doctrine"
    findings: "AUTO_INCREMENT violation found"
    resolution: "Registry tables removed"
  
  - event_id: 3
    event_type: update
    event_date: "20260328150000"
    actor_id: 14
    actor_name: "hephaestus"
    faucet_slug: "windsurf"
    description: "Implemented IdGenerator class"
    reason: "Replace registry tables with timestamp IDs"
    affected_files: ["lupo-includes/classes/IdGenerator.php"]
  
  - event_id: 4
    event_type: audit
    event_date: "20260328153000"
    actor_id: 2
    actor_name: "lilith"
    faucet_slug: "cursor"
    description: "Final audit of header doctrine"
    result: "approved"
```

### When to Use

- **Doctrine files:** REQUIRED (track full lifecycle)
- **Design documents:** RECOMMENDED
- **Implementation files:** OPTIONAL
- **Scripts:** OPTIONAL

---

## 🌐 **federation_node_id Rules**

| Node | Description | When to Use |
|------|-------------|-------------|
| **0** | Core Lupopedia (lupopedia.com) | Official documentation, doctrine |
| **1** | Current install (localhost) | Development artifacts, local testing |
| **2+** | External nodes | External research, references |

### **Node Assignment Rules**

| Document Location | Default Node |
|-------------------|--------------|
| `lupo-docs/versions/` | 0 |
| `lupo-docs/doctrine/` | 0 |
| `lupo-rules/root/` | 0 |
| `lupo-channels/42/` | 1 |
| `lupo-content/federation_node_id/{id}/` | 2+ |

---

## ⚡ **Field Formatting Rules**

### **Paths**
- `file_path_from_root`: Relative from repo root
- `web_path`: Full URL including protocol
- Use forward slashes always

### **IDs**
- `actor_id`: Integer from actor registry
- `channel_id`: Integer from channel system
- `federation_node_id`: Integer per node rules

### **Names**
- `actor_name`: lowercase, underscores
- `thread_id`: lowercase, hyphens
- All strings quoted in YAML

### **Timestamps (YAML vs database)**

- Logical meaning: **UTC only**, `YYYYMMDDHHIISS`, no timezone suffix in the string.
- **In YAML:** quote values as strings (e.g. `"20260328140000"`) for consistent parsing and to avoid YAML integer overflow quirks.
- **In MySQL row columns (e.g. `lupo_contents.updated_ymdhis`):** stored as **BIGINT** UTC (application-written; no DB auto-timestamps).

---

## Actor Attribution Rules

### Who is the `actor_id`?

The `actor_id` in `lupopedia.headers` represents the **last operational actor** who modified the file.

- **WOLFIE** (actor_id 1) writes a doctrine file → `actor_id: 1` 
- **LILITH** (actor_id 2) reviews and updates it → `actor_id: 2` 
- **HEPHAESTUS** (actor_id 14) implements code changes → `actor_id: 14` 

### Original Author Tracking

The original author is **NOT** stored in the header. It is tracked by:
- Git history (`git log --follow`)
- `when_created` timestamp (if present)
- The first commit in the repository

### Why Not Store Original Author?

- **Churn:** The original author is static; the last actor is dynamic
- **Simplicity:** One actor field, always current
- **Git is truth:** The repository already tracks authorship

### Faucet Attribution

The `verified_via.faucet_slug` field tracks the **execution surface** (Cursor, Windsurf, etc.) used by the actor.

```yaml
lupopedia.footer:
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"  # Actor used Cursor to modify this file
```

**Rule:** The `actor_id` is the *who* (operational identity). The `faucet_slug` is the *how* (execution surface). Both are required for complete attribution.

---

## `web_path` for External Federation Nodes (Node 2+)

For external research artifacts (federation_node_id >= 2), the `web_path` MUST point to the **canonical source URL**, not a local mirror.

### Examples

```yaml
# External research reference
lupopedia.headers:
  federation_node_id: 4  # Doom Emacs
  web_path: "https://github.com/doomemacs/doomemacs/blob/master/README.md"
  file_path_from_root: "lupo-content/federation_node_id/4/doomemacs/README.md"
```

### Rules

- `web_path` is the **source URL** (what you are referencing)
- `file_path_from_root` is the **local copy path** (where you store analysis)
- The local copy is a snapshot; the web_path is the canonical source

### Why Two Paths?

- **Preservation:** Local copy ensures you have the content even if the source disappears
- **Attribution:** web_path gives credit and allows readers to find the original
- **Federation:** Other nodes can resolve the canonical source

### Validation

- For `federation_node_id >= 2`, `web_path` MUST be a valid URL (http:// or https://)
- `file_path_from_root` MUST be under `lupo-content/federation_node_id/{id}/` 

---

## Staleness Detection (FINAL)

**Cutoff:** `20260328140000` (2026-03-28 14:00:00 UTC)

### Rules
- Files with `last_verified < cutoff` are **STALE**
- Do NOT edit stale files directly without a **regeneration or semantic review** pass (see footer / THOTH doctrine in `lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md`).

### Database-first regeneration (preferred when DB is available)

For artifacts that exist in **`lupo_contents`**, prefer regenerating YAML **from the database** so `lupopedia.edges` and history stay aligned with **`lupo_edges`** / **`revision_history`**:

1. Ensure row exists: `python lupo-scripts/import_content.py <file.md>` or `python lupo-scripts/ensure_imported.py <file.md>`
2. Regenerate: `python lupo-scripts/generate_headers_from_db.py --file-path <repo-relative-path.md>`  
   (Use `--use-mock-db` only for offline stub behavior.)

### Batch / legacy tooling

- `lupo-scripts/regenerate_headers_for_stale_files.py` — batch helper for stale file refresh (may be file-centric; use DB-first flow above when the artifact is imported).

### Why This Cutoff

- This is the moment the doctrine was locked
- Any file created or modified before this time is "legacy"
- Any file after this time must follow the new rules

### Enforcement

- **Validator:** Warns on stale files but does not fail (for transition)
- **Editor:** Regenerate or complete semantic review before editing stale doctrine
- **Commit hook:** Will reject commits with stale files after 4.0.89 release (per project policy)

### Why
- Ensures all **in-scope** authored files comply with current doctrine (see [PRD 16 — Header applicability and scope](../../lupo-docs/prd/16_lupopedia_headers.md#header-applicability-and-scope); excludes binaries, generated exports, vendor trees)
- Prevents manual edits that miss required fields
- Gradual update as files are touched during normal work

---

## Cross-Field Consistency Rules

| `lupopedia.schema` | Allowed `artifact_type` | Allowed `artifact_kind` |
|-------------------|------------------------|------------------------|
| `doctrine` | `doctrine` | `database`, `documentation`, `rule` |
| `rule` | `rule` | `rule` |
| `philosophy` | `manifesto` | `philosophy` |
| `plan` | `plan` | `plan` |
| `todo` | `todo` | `task` |
| `changelog` | `changelog` | `version_specific` |
| `directive` | `directive` | `execution` |
| `design` | `design` | `architecture` |
| `review` | `review` | `audit` |
| `report` | `report` | `status` |
| `implementation` | `implementation` | `code` |
| `script` | `script` | `utility` |
| `class` | `class` | `code` |
| `index` | `index` | `index` |
| `thread` | `thread` | `coordination` |
| `broadcast` | `broadcast` | `coordination` |

**Validator must enforce these relationships.**

---

### 2. `delegation_chain` Field

**Current Format:** `"{actor_name}:{role}"` 

**Examples:**
- `"wolfie:root"` — WOLFIE with root authority
- `"cursor:lead_orchestration"` — Cursor IDE leading development
- `"lilith:audit"` — LILITH with audit authority

**Limitations (Known):**
- Cannot represent multiple departments
- Cannot represent auth_user relationships
- Will be extended in future versions with structured `delegation` object

**Future Direction (4.0.90+):**
```yaml
delegation:
  actor_id: 1
  actor_name: "wolfie"
  actor_departments: [0, 1]
  auth_user_id: 1000
  auth_user_name: "root"
  role: "owner"
```

---

## 🚫 **Deprecated Fields**

These fields MUST NOT be used:

| Field | Why Deprecated | Alternative |
|-------|----------------|-------------|
| `lupopedia.version` | Creates maintenance churn | Use `when_updated` |
| `system_version` | Redundant with version docs | Use version-specific docs |
| `version` (in footer) | Hardcoded version | Use `when_updated` |

---

## ✅ **Validation Requirements**

All documents must pass:

1. **Header Completeness**: All required fields present
2. **Schema Validity**: `lupopedia.schema` in canonical list
3. **Node Assignment**: Correct `federation_node_id` for location
4. **Format Compliance**: Proper timestamp and path formats
5. **No Deprecated Fields**: No forbidden fields present
6. **Edge Existence**: All `outbound_edges.to` paths exist in repository

---

## Edge Validation

All `outbound_edges.to` paths MUST exist in the repository.

Validator must:
- Resolve relative paths
- Check file existence
- Flag missing targets as warnings (not errors for 4.0.89, errors in 4.0.90)

**Example:**
```yaml
outbound_edges:
  - to: "lupo-rules/root/DATABASE_DOCTRINE.md"  # ✅ MUST exist
```

---

## Database-first mapping and `lupo_contents`

### Schema authority (which file to trust for columns)

1. **DDL:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — full `CREATE TABLE` definitions (binding per [DATABASE_DOCTRINE.md](DATABASE_DOCTRINE.md)).
2. **Column oracle (generated):** `lupo-database/lupopedia/json/<table_name>.json` — one JSON file per table (e.g. `lupo_contents.json`, `lupo_metadata.json`, `lupo_edges.json`). These mirror install columns and are what tooling such as `import_content.py` uses for explicit insert column lists.  
   *Note:* Some branches or docs refer to a `toon/` export; if present, it is the same role as the JSON exports—**never** hand-edit generated schema mirrors; regenerate from install or DB per project scripts.

### What `import_content.py` + `lib/header_db_sync.py` do on import / update

**Identity:** A deterministic **`content_id`** is derived from `file_path_from_root` + markdown **body** (see script). The row is **upserted** in **`lupo_contents`** by `content_id` (update if exists, insert if not).

**`lupo_contents` columns written by import (high level):** core text columns (`body`, `content`), `title` / `slug` (from header or path), `file_path_from_root`, `file_last_modified_system_version` / `file_last_modified_utc` (from header semantics), `channel_id`, `actor_id` when present in headers, timestamps (`created_ymdhis` on insert, `updated_ymdhis` on each run), and standard defaults (`is_deleted`, `status`, engagement counters, etc.).  

**`lupo_contents` JSON “relational” columns NOT filled by header import today:**  
`atom_mappings`, `category_mappings`, `content_events`, `hashtags`, `inbound_links`, `like_users`, `media_attachments`, `question_mappings`, `content_references`, `share_users`, `tag_relationships`, and similar **aggregates** documented in `lupo_contents.json`. These exist for **runtime / feature** data (often consolidated from legacy structures per column COMMENT in install SQL). **Do not assume** they are populated from markdown import unless a separate pipeline says so.

**`revision_history`:** Updated **only** when the file’s YAML contains a top-level **`lupopedia.history`** key; value is serialized to **`lupo_contents.revision_history`**. If that key is **absent**, import does **not** clear existing `revision_history` in the DB.

**`lupo_metadata`:** For `entity_type = 'content'`, `entity_id = content_id`, `domain_id = 1`, `class_name = 'lupopedia_header_sync'`, rows store a flattened snapshot of header/footer/extra blocks (`property_key` prefixes `hdr.`, `ftr.`, `block.*`). Re-import **replaces** those rows for that class (delete + insert), not the whole metadata table.

**`lupo_edges`:** Outbound edges from **`lupopedia.edges.outbound_edges`** are written as rows with `left_object_type = 'content'`, `left_object_id = content_id`, **`edge_category = 'lupopedia_header'`**. Prior header-import edges for that content are **soft-deleted** (`is_deleted = 1`) and replaced. Target `to:` paths resolve to **`right_object_type = 'content'`** when a matching `file_path_from_root` exists in `lupo_contents`; otherwise to **`lupo_reference_objects`** (`object_type = 'file_path_ref'`) so the path is not lost.

**`lupo_edges` column names (schema / TOON / install SQL — avoid wrong SQL):** There is **no** column named `weight` or `reason`. Import and regeneration use:

| YAML field (`outbound_edges[]`) | Database column(s) |
|---------------------------------|--------------------|
| `type` | `edge_type` |
| `weight` (0.0–1.0 float in YAML) | `weight_score` (int 0–100), `semantic_weight` (decimal), `flare_weight` (decimal) |
| `reason` (optional string) | `flare_reason` (varchar 255) |

Custom tooling must **not** invent `weight` / `reason` columns on `lupo_edges`. The canonical **read** path for regeneration is **`build_yaml_data_from_db`** in `lupo-scripts/lib/header_db_sync.py` (uses `fetch_header_edges` + `outbound_edges_from_db_rows`), which maps those columns back to YAML `weight` / `reason`. The canonical **write** path is **`sync_header_artifact_to_db`** in the same module (invoked from `import_content.py` after `lupo_contents` upsert).

### Regeneration (DB → file)

**`generate_headers_from_db.py`** (default: live MySQL) loads the `lupo_contents` row, then calls **`build_yaml_data_from_db`** in `lib/header_db_sync.py` — the **inverse** of `sync_header_artifact_to_db`, not a parallel implementation. It reads `lupo_metadata` (`lupopedia_header_sync`), **`lupo_edges`** (`edge_category = 'lupopedia_header'`), and **`lupo_contents.revision_history`** for `lupopedia.history`. If no row exists for a path, it may invoke **`import_content.py`**, which runs **`sync_header_artifact_to_db`** so DB state matches the file before regeneration.

**`content_id`** is required in the DB workflow; validators **warn** when it is missing on disk. Optional: `python lupo-scripts/validate_lupopedia_headers.py <file> --check-db` warns when the file declares edges or history but the database has no matching rows for that `content_id`.

### Summary table

| YAML block / concern | Primary table(s) | Import behavior |
|----------------------|------------------|-----------------|
| Body under closing `---` | `lupo_contents.body`, `lupo_contents.content` | Upsert from file |
| `lupopedia.headers` scalars | `lupo_contents` (subset) + `lupo_metadata` (`hdr.*`) | Upsert + metadata snapshot |
| `lupopedia.footer` | `lupo_metadata` (`ftr.*`) | Metadata snapshot |
| Other `lupopedia.*` blocks (except edges/history) | `lupo_metadata` (`block.*`) | Metadata snapshot |
| `lupopedia.edges` | `lupo_edges` (`edge_category=lupopedia_header`) | Replace snapshot edges |
| `lupopedia.history` | `lupo_contents.revision_history` | JSON column; only if key present |
| JSON relation columns on `lupo_contents` | same row | **Not** set by header import |

---

## `thread_id` Field Clarification

- **Format:** lowercase, hyphens (`"headers-doctrine"`)
- **Purpose:** Identifies which thread the artifact belongs to
- **Not to be confused with:** `schema: thread` (which classifies the document as a thread artifact)

**Example:**
```yaml
lupopedia.headers:
  thread_id: "headers-doctrine"  # The thread this belongs to
  schema: thread                  # This document IS a thread artifact
```

---

## 🔧 **Tools and Scripts**

### **Validation**
- `lupo-scripts/validate_lupopedia_headers_universal.py` — broad / strict checks where configured
- `lupo-scripts/validate_lupopedia_headers.py` — focused LUPOPEDIA HEADERS pass (includes **`content_id`** warning when missing)
- Shared logic: `lupo-scripts/lib/header_validation.py`

### **Import, ensure-imported, DB → file regeneration**
- `lupo-scripts/import_content.py` — upsert `lupo_contents`, write `content_id` into file, sync metadata + edges + optional `revision_history`
- `lupo-scripts/ensure_imported.py` — runs import when `content_id` absent
- `lupo-scripts/generate_headers_from_db.py` — regenerate YAML from DB (default live MySQL; `--use-mock-db` for stub)
- `lupo-scripts/lib/header_db_sync.py` — shared DB round-trip helpers (`sync_header_artifact_to_db`, `build_yaml_data_from_db`)

### **Batch / stale files**
- `lupo-scripts/regenerate_headers_for_stale_files.py` — batch refresh; prefer DB-first flow when artifacts are imported

### **Common Validation Issues**

| Issue | Solution |
|-------|----------|
| Missing required field | Add the field with proper format |
| Invalid schema | Use canonical schema value |
| Wrong federation_node_id | Check document location |
| Deprecated field | Remove the field |
| Invalid timestamp | Use YYYYMMDDHHIISS format |

---

## 🎯 **Deterministic Actor ID and Folder Rules (v4.0.93)**

### Actor ID Classification

| Actor ID Range | Type | Folder Path | Learning Boundary |
|----------------|------|-------------|-------------------|
| < 2026 | Core Actor | `lupo-actors/<actor_id>/` | Department 0 auth_users only |
| ≥ 20260101010101 | Runtime Actor | `lupo-actors/YYYY/MM/<actor_id>/` | Department-scoped |

### Rules for Headers

1. **Core Actors** (actor_id < 2026):
   - Must have `learning_boundary: "Department 0 auth_users only"` in authority PRDs
   - Folder path is always `lupo-actors/<actor_id>/`
   - Never regenerated or moved
   - Examples: WOLFIE (1), LILITH (2), LEXA (3), CURSOR (102)

2. **Runtime Actors** (actor_id ≥ 20260101010101):
   - Use timestamp BIGINT format: `YYYYMMDDHHIISS + 4 random digits`
   - Folder path: `lupo-actors/YYYY/MM/<actor_id>/` (extracted from timestamp)
   - Department-scoped learning
   - Created via `IdGenerator::generate()`

3. **Sandbox Identity** (actor_id = 420):
   - Allowed: Login and prompt only
   - Forbidden: Database, system file, and network access
   - Special case for testing

### Registry Enforcement

- Actor registry must enforce canonical numeric folder paths
- Slug-only actor folder names under `lupo-actors/` (e.g. `wolfie/`, `lilith/`) are deprecated; registry `dir` uses `lupo-actors/{actor_id}/`
- All references must use numeric actor_id paths
- Deterministic ID generation ensures no collisions

---

## 📚 **Examples**

### **Doctrine Document**
```yaml
lupopedia.headers:
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-rules/root/DATABASE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-rules/root/DATABASE_DOCTRINE.md"
  federation_node_id: 0
  # ... rest of fields
  artifact_type: doctrine
  artifact_kind: database
  tags:
    - tag-doctrine
    - tag-database
    - tag-constitutional
```

### **Philosophy Document**
```yaml
lupopedia.headers:
  lupopedia.schema: philosophy
  file_path_from_root: "lupo-rules/root/INDEPENDENT_CODERS_MANIFESTO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-rules/root/INDEPENDENT_CODERS_MANIFESTO.md"
  federation_node_id: 0
  # ... rest of fields
  artifact_type: manifesto
  artifact_kind: philosophy
  tags:
    - tag-philosophy
    - tag-manifesto
    - tag-constitutional
```

### **Implementation File**
```yaml
lupopedia.headers:
  lupopedia.schema: class
  file_path_from_root: "lupo-includes/classes/IdGenerator.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/classes/IdGenerator.php"
  federation_node_id: 0
  # ... rest of fields
  artifact_type: class
  artifact_kind: code
  tags:
    - tag-implementation
    - tag-script
    - tag-database
```

---

## � **DB Import and Edge Merging Doctrine (v4.0.93)**

### Import Logic

1. **If content_id does not exist**:
   - Import file as new content
   - Create lupo_contents row
   - Import all header fields to lupo_metadata
   - Import all outbound_edges to lupo_edges
   - Generate new content_id

2. **If content_id exists**:
   - Merge DB + file edges
   - Update lupo_metadata with latest header values
   - Soft-delete old edges, insert new edges
   - Preserve revision_history if lupopedia.history exists

### Edge Merging Rules

- **File takes precedence**: Edges in file overwrite DB edges
- **Unique by (to, type)**: Only one edge of each type per target
- **Weight averaging**: When merging, use file weight
- **Reason preservation**: Keep file reason for new edges

### Content ID Resolution

- **File references**: Use file_path_from_root to match lupo_contents
- **Slug references**: Use thread_id for channel artifacts
- **ID references**: Use content_id directly when available

### Deterministic Folder Alignment

During import:
- Verify actor_id matches folder structure
- Core actors: Must be in `lupo-actors/<actor_id>/`
- Runtime actors: Must be in `lupo-actors/YYYY/MM/<actor_id>/`
- Flag mismatches for manual review

---

## �� **Related Documents**

- **[RULE_FILES_HEADER_REQUIREMENT.md](RULE_FILES_HEADER_REQUIREMENT.md)** — Meta-rule requiring headers on rule files
- **[DATABASE_DOCTRINE.md](DATABASE_DOCTRINE.md)** — Database rules (no FK/triggers, timestamps, naming)
- **[HEADER_DB_REVERSIBILITY_DOCTRINE.md](../../lupo-docs/doctrine/HEADER_DB_REVERSIBILITY_DOCTRINE.md)** — DB ↔ headers round-trip expectations
- **[EDGE_MODEL_DOCTRINE.md](../../lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md)** — `lupo_edges` authority
- **[lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md)** — Format overview and tooling index (same folder as the alias below)
- **[lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md)** — **Canonical pointer only** (duplicate path for bookmarks; content lives in **this** root file)
- **[validate_lupopedia_headers_universal.py](../../lupo-scripts/validate_lupopedia_headers_universal.py)** — Universal validator implementation

---

**This doctrine is LOCKED and binding. All documentation must comply.**
