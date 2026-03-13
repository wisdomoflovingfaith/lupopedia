---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  system_version: "4.0.73"
  file_path_from_root: "lupo-docs/prompts/20260313_create_planning_toon_files.md"
  last_modified_utc: "20260313"
  channel_id: 42
  actor_id: 1003
  actor_name: "antigravity"
  artifact_type: "prompt"
  artifact_kind: "directive"
  purpose: "Directive for Antigravity to generate planning TOON files for future database tables"
  mood_rgb: "FFD700"
  traits: ["prompt", "directive", "database", "planning"]
  tags: ["prompt", "antigravity", "database", "planning"]
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of outbound edges for files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/planning/", type: "targets", weight: 1.0 }
  semantic_tags: ["prompt", "directive", "database", "planning"]

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  views: 0
  like_count: 0
  share_count: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "antigravity"
  orchestrator: "antigravity"
  next_action:
    - "Use this prompt to regenerate planning docs if the source SQL changes"
    - "Use scripts/generate_planning_toons.py for automated generation"
    - "Expand the script to include relationship detection"
---

# ANTIGRAVITY IMPLEMENTATION PROMPT: Create Planning TOON Files for Future Database Tables

## Task Overview

Antigravity, we need to convert the future Lupopedia database tables defined in:

`lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

into TOON documentation files and place them inside a new planning directory.

These represent planned database tables that are not yet implemented but must be documented in Lupopedia’s canonical schema documentation system. The canonical generation script is located at `scripts/generate_planning_toons.py`.

## 1️⃣ Create the Planning Directory

Create the directory:

`lupo-docs/database/lupopedia/tables/active/planning`

This directory will contain TOON schema documentation files representing planned tables.

Directory structure must become:

```
lupo-docs/
  database/
    lupopedia/
      tables/
        active/
          planning/
```

## 2️⃣ Source SQL File

Parse the SQL file:

`lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

For every `CREATE TABLE` statement, generate a corresponding TOON documentation file.

## 3️⃣ File Naming Convention

Each table must produce one TOON file named:

`table_{table_name}.toon.md`

Example:
- `table_lupo_agent_tasks.toon.md`
- `table_lupo_ai_sessions.toon.md`
- `table_lupo_semantic_cache.toon.md`

Files must be saved in:
`lupo-docs/database/lupopedia/tables/active/planning/`

## 4️⃣ TOON File Structure

Each file must follow the Lupopedia documentation schema.

### Example template:

```markdown
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  system_version: "4.0.73"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/planning/table_{table_name}.toon.md"
  last_modified_utc: "{YYYYMMDD}"
  channel_id: 42
  actor_id: 1003
  actor_name: "antigravity"
  artifact_type: "database_schema"
  artifact_kind: "planning"
  purpose: "Planned Lupopedia database table: {table_name}"
  mood_rgb: "4169E1"
  traits: ["planning","database","table","future_feature"]
  tags: ["database","table","planning","lupopedia"]
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of outbound edges for files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["planning", "database", "table", "future_feature"]

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  views: 0
  like_count: 0
  share_count: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "{YYYYMMDD}"
  last_verified_by: "antigravity"
  orchestrator: "antigravity"
  next_action:
    - "Monitor this table for implementation readiness"
    - "Review schema for doctrine compliance"
---

# Planned Table: `{table_name}`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
CREATE TABLE ... (The exact SQL definition from the file)
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
(Populate using the SQL definition)

## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
```

## 5️⃣ SQL Extraction Rules

Antigravity must:
1. Parse the SQL file
2. Identify all: `CREATE TABLE table_name (`
3. Extract the **full SQL definition**
4. Place it in the TOON file
5. Do **not modify SQL syntax**.

## 6️⃣ Documentation Goal

These planning TOON files allow:
- Database planning
- Schema discussion
- Agent awareness
- Future migration design
- Deterministic schema evolution

They must **accurately mirror the SQL definitions**.

## 7️⃣ Validation Requirements

After generation, verify that:
- Every `CREATE TABLE` in `future_features_lupopedia.sql` has a TOON file
- Filenames match table names
- SQL blocks are preserved exactly
- Headers include correct `file_path_from_root`

## 8️⃣ Update CHANGELOG

Add a new entry to `CHANGELOG.md`:

### Documentation
- Added planning database schema documentation: `lupo-docs/database/lupopedia/tables/active/planning/`
- Generated TOON planning files for all tables defined in `future_features_lupopedia.sql`

## 9️⃣ Commit

Commit message: `docs(database): add planning TOON files for future Lupopedia tables`

## Final Goal

This creates a **planning layer for database evolution** so that:
- Future schema exists as documentation
- Agents can reason about upcoming tables
- Migrations remain deterministic
- Lupopedia documentation stays lineage-safe.
