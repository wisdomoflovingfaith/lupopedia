# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/legacy-import/TABLE_LIMIT_CONSTRAINT.md"
  file_hash: "04626511599d63414725f0a4f54057792606fc04f549a7c66e380d3d93756fb1"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
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
  file_path_from_root: "docs\channels\doctrine\legacy-import\TABLE_LIMIT_CONSTRAINT.md"
  file_hash: "382807eeaf95c267de6a8a70cddd8138fd90b54ee4c0d39f15abc9497c68b1b4"
  file_path_from_root: "docs\channels\doctrine\legacy-import\TABLE_LIMIT_CONSTRAINT.md"
  file_hash: "7fbc416347d89ba6d58f5091c1257eda4a853080b3a9a6051da2454b33f94de8"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for TABLE_LIMIT_CONSTRAINT.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "legacy-import", "table_limit_constraintmd"]
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
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.0
file.channel: doctrine
---

# DOCTRINE: GLOBAL TABLE CEILING (199 TABLES)

**Filename:** doctrine/TABLE_LIMIT_CONSTRAINT.md  
**Status:** Architectural Hard Limit  
**Authority:** High (below Ethical Foundations, above AAL)  
**Version:** 1.0

**Current table count:** Do not hardcode. Run `python scripts/generate_toon_files.py` and count the TOON files produced; use that number when referencing "current" count in any doc.

## 1. HARD LIMIT: 199 TABLES PER DATABASE

### 1.1 MAX_TABLES_PER_DATABASE = 199  
No database in the Lupopedia ecosystem may exceed 199 total tables at rest.

### 1.2 This limit is absolute and applies to:

- lupopedia (canonical shipping DB)
- lupopedia_worms (experimental/ORM sandbox)
- any future databases

### 1.3 Purpose of the limit:

- Prevent schema explosion
- Maintain human auditability
- Keep migrations sane
- Stop AI‑generated ORM drift
- Preserve long‑term maintainability

## 2. MIGRATION EXCEPTION (TEMPORARY OVERAGE ALLOWED)

### 2.1 Migrations ARE allowed to temporarily exceed 199 tables only if:

- The migration is actively running
- The overage is temporary
- Deprecated tables are dropped before the migration completes
- The final post‑migration table count is ≤ 199

### 2.2 This is called the "Migration Overage Window."

### 2.3 The window closes the moment the migration finishes.
At that moment, the table count must be ≤ 199 or the migration is invalid.

### 2.4 Table Optimization Cycle Trigger
If any database reaches 200 tables or more:

- Review all tables for redundancy
- Identify merge candidates
- Remove deprecated or legacy-artifact tables
- Ensure doctrine alignment
- Re-evaluate schema intent and emotional metadata placement

## 3. DEPRECATED TABLE DROP REQUIREMENT

### 3.1 Any migration that adds tables must include a cleanup step that:

- Drops deprecated tables
- Drops replaced tables
- Drops obsolete tables
- Drops temporary staging tables

### 3.2 The cleanup step is mandatory and must run before the migration is considered successful.

### 3.3 A migration that ends with >199 tables is considered failed and must be rolled back.

## 4. MULTI‑DATABASE ARCHITECTURE

### 4.1 Canonical DB:

- lupopedia
- Doctrine‑aligned
- Human‑authored schema
- No AI‑generated tables

### 4.2 Experimental DB:

- lupopedia_worms
- AI sandbox
- ORM experiments allowed
- Still subject to the 199 table limit

### 4.3 Additional DBs require explicit human directive.

## 5. ORM RESTRICTIONS

### 5.1 ORMs are not permitted in lupopedia.

### 5.2 ORMs are allowed in lupopedia_worms only.

### 5.3 AI must not:

- Generate ORM classes for lupopedia
- Infer ORM mappings from doctrine
- Auto‑create tables in canonical DB
- Expand schema without human approval

## 6. COMPLIANCE

### 6.1 Any violation triggers:

- Immediate halt
- Human escalation
- Schema audit

### 6.2 Agents must load this doctrine before any schema reasoning.
