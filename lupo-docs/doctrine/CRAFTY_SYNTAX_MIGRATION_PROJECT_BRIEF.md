# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md"
  file_hash: "087cd21bcf0267a99b0f3227a3c5a019422116898939ed5dda22431fc8ccddb7"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-docs\doctrine\CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md"
  file_hash: "dd7d68427c5ad1166b78fee3ac31d3abe0f11a61ec16338db307456a06e3bacf"
  file_path_from_root: "lupo-docs\doctrine\CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md"
  file_hash: "8b3db09820bef02ab94f2ecc3aaad89933b7601e12997d3fed939a256611ba7b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "crafty_syntax_migration_project_briefmd"]
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
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md
---

# Crafty Syntax → Lupopedia: Project Brief (Authoritative)

This document is the **canonical prompt and rules** for the long-form, state-based implementation project. Use it to align all work on this migration.

---

## PRIMARY OBJECTIVE

We are implementing **ALL** legacy Crafty Syntax 3.7.5 features into the new Lupopedia system. Lupopedia is **NOT** a direct port of Crafty Syntax. The database schema, semantics, and architecture have been significantly improved. Your job is to help **map, reconcile, and implement features** — not revert the system to the old design.

---

## CRITICAL RULES ABOUT THE DATABASE

1. **The authoritative schema is defined ONLY in the TOON files** located in:
   - `lupo-docs/toons/`
   You must read and interpret the schema from these TOON files. If you are unsure about a column, enum, or table definition, you must check the TOON file. Assume you may be wrong unless the TOON confirms it.

2. **You must NOT infer schema** from MySQL introspection or phpMyAdmin. The live database may lag behind or contain legacy artifacts.

3. **Schema changes ARE allowed** during this project, but **ONLY through migration files**. If we discover that a table must be altered or a new table must be added, you may generate a SQL migration file. I will run the migration manually, and **THEN** I will regenerate the TOON files from the updated database. **You must NOT modify TOON files directly.**

4. **Any schema changes must be expressed as:**
   - Migration files (SQL `ALTER TABLE`, `CREATE TABLE`, `DROP COLUMN`, etc.)
   **NEVER** direct edits to the schema or TOON files.

5. **Any data inserts or updates** must be done through **Python scripts**, consistent with our existing workflow.

6. **NO foreign keys, NO triggers, NO stored procedures, NO database logic.** The database must remain simple, portable, and doctrine-aligned.

7. **NO reliance on auto-increment for identity.** Lupopedia uses explicit ID assignment. For example:
   - Channels use ID ranges
   - IDs must be checked for availability before insert

8. **All timestamps** use BIGINT in the format:
   - `YYYYMMDDHHIISS`
   NOT unix epoch, NOT datetime, NOT timestamp types.  
   Note: **HHIISS** = 24-hour (00–23), minutes (00–59), seconds (00–59).

9. **NO display widths** on integer types (e.g., no `BIGINT(14)`, `INT(11)`).

10. **NO UNSIGNED** integers.

11. **TINYINT is numeric**, not boolean (no `TINYINT(1)` for boolean).

12. **All relationships are soft-references** (explicit, textual, nullable, repairable). No enforced constraints, no automatic behavior.

13. **IMPORTANT ENUM RULE:** For `lupo_actors.actor_type`, the valid enum values are:
    - `'user'`, `'ai_agent'`, `'service'`, `'anonymous'`
    **NOT** `'human'`. If you ever need to reference enum values, you must check the TOON files first and assume your memory may be wrong.

---

## FILESYSTEM + INSTALLATION RULES

1. **Subdirectory-only installation.** Lupopedia is **always** installed inside a subdirectory of the document root (e.g. `/public_html/lupopedia/`). The document root may contain other files and folders; Lupopedia must **never** assume it is the web root. All routing, asset URLs, link generation, and path resolution must respect that the project lives in a subfolder. The parent directory is not part of the project and must not be referenced, modified, or assumed. This layout is required for compatibility with auto installers such as Softaculous and Installatron.

2. All Lupopedia files **MUST** remain inside the **lupopedia** directory.

3. The **ONLY** file allowed outside the web root is:
   - `lupopedia-config.php`

4. Lupopedia installations must remain compatible with auto installers such as Softaculous and Installatron.

5. The filesystem layout follows a **WordPress-style** structure:
   - Bootstrap loader in `index.php`
   - Modules/plugins in `lupopedia/modules/`
   - Procedural entry points
   - Clean public root

6. **Do NOT** propose file layouts that break installer compatibility.

7. **Never hardcode the directory name "lupopedia".** Lupopedia must **NEVER** hardcode the directory name `lupopedia` or assume the project lives in a folder named `/lupopedia/`. Existing installations may use any folder name (e.g. `/livehelp/`, `/support/`, `/helpdesk/`). All internal paths, URLs, redirects, asset references, and routing **MUST ALWAYS** use the `LUPOPEDIA_PUBLIC_PATH` constant (defined in `lupopedia-config.php`).

   **Examples:**
   - Do NOT generate links like `/lupopedia/channels/123/`
   - Instead use: `LUPOPEDIA_PUBLIC_PATH . "/channels/123/"`

   This rule applies to: PHP routing and redirects, menu links, asset URLs, includes and requires (for URL/path output), JS-generated URLs, and any code that constructs internal paths. All future code generation must use `LUPOPEDIA_PUBLIC_PATH` exclusively for internal paths.

---

## CONTEXT YOU MUST UNDERSTAND

- We have the full **Crafty Syntax 3.7.5** codebase as **READ-ONLY** reference.
- We have **import_from_old_crafty_syntax** artifacts.
- We have **livehelp_*** files and tables documenting old behavior.
- We have **TOON** files describing the new Lupopedia schema.
- We have **doctrine** files describing the new architecture.
- Lupopedia already improves many Crafty Syntax features; **do NOT regress** them.
- Lupopedia now supports both **Crafty Syntax operators** AND **regular users**.
- Lupopedia includes a new semantic system, but that is **NOT** part of this migration. **Do NOT** introduce emotional metadata, AI agents, or semantic enhancements at this stage.

---

## YOUR FIRST TASK

Before planning implementation, you must:

1. **Review the TOON files** to understand the current Lupopedia schema.
2. **Review the legacy Crafty Syntax** tables and features.
3. **Review any existing mapping documentation.**
4. **Identify:**
   - what is already implemented in Lupopedia,
   - what was improved,
   - what still needs to be migrated,
   - what mappings are missing or unclear.

---

## WORKFLOW STYLE

We work in **STATE-BASED PHASES** (not time-based). Each phase represents a stable architectural or implementation state. State transitions are based on completion criteria:

- **CLEAR:** All validations passed
- **HOLD:** MAJOR issues present (blocks mutation)
- **BLOCKED:** CRITICAL issues present (blocks all operations)

We add phases as needed when new discoveries occur.

---

## WHAT YOU MUST PRODUCE FIRST

A clear, multi-phase **STATE-BASED IMPLEMENTATION PLAN** that:

- respects the improved Lupopedia schema,
- uses **TOON files** as the source of truth,
- identifies missing mappings,
- identifies required migrations,
- identifies required Python scripts,
- identifies required feature rebuilds,
- ensures each phase ends in a stable system state,
- respects installer-safe filesystem rules,
- respects doctrine (no FK, no triggers, explicit IDs, BIGINT timestamps),
- uses **migration files** for any schema changes discovered during the process,
- and **verifies enum values and column definitions against TOON files**.

---

## DELIVERABLE GOAL FOR THIS THREAD

By the end of this thread, we should have:

- All Crafty Syntax features implemented inside Lupopedia,
- All old tables mapped to new tables,
- All required migrations written,
- All features rebuilt in the new architecture,
- All documentation updated,
- A fully functional Crafty Syntax subsystem inside Lupopedia.

**Begin by** analyzing the documentation and producing the initial STATE-BASED IMPLEMENTATION PLAN.

---

**Reference:** Implementation plan and phase status → `lupo-docs/doctrine/CRAFTY_SYNTAX_STATE_BASED_IMPLEMENTATION_PLAN.md`
