# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/appendix/appendix/WHAT_NOT_TO_DO_AND_WHY.md"
  file_hash: "f4c0acded18cad35cc5e83e9ad0c2488204f45e5f501e90cfbcb1f47f6b00445"
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
  file_path_from_root: "lupo-docs\channels\appendix\appendix\WHAT_NOT_TO_DO_AND_WHY.md"
  file_hash: "45fe9840ad9608d75443f2c1afd159ecd9b4e35e09961cd2a73bffcdbf1d3139"
  file_path_from_root: "lupo-docs\channels\appendix\appendix\WHAT_NOT_TO_DO_AND_WHY.md"
  file_hash: "7bd96f72c49e7d7249081d7d550800380297e3daf79843bff5f3733e49e0875a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for WHAT_NOT_TO_DO_AND_WHY.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "appendix", "what_not_to_do_and_whymd"]
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
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Added WOLFIE Header v3.0.0 for documentation consistency."
tags:
  categories: ["documentation", "lessons-learned"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "What Not To Do and Why"
  description: "Living archive of mistakes, misfires, and never again lessons learned during development"
  version: "3.0.0"
  status: published
  author: "Captain Wolfie"
---

# ðŸ§¨ WHAT_NOT_TO_DO_AND_WHY.md

A living archive of mistakes, misfires, dead ends, and "never again" lessons learned while building Lupopedia, Crafty Syntax 4.x, and the surrounding modules.

This file exists so futureâ€‘me (and future agents) never repeat past chaos.

---

## 2025â€‘08 â€” Letting AI Autoâ€‘Design the Database
**Mistake:** Allowed early LLMs to generate schema structure on the fly.  
**Result:** ~250 tables, recursive FK chains, mixed signed/unsigned BIGINTs, engineâ€‘specific features, and a database that literally crashed.  
**Why Not To Do It:** AI without doctrine produces entropy, not architecture.  
**Lesson:** *I design the schema. AI assists, never leads.*

---

## 2025â€‘09 â€” Using Foreign Keys "Because They Seemed Helpful"
**Mistake:** Added FK constraints during early schema drafts.  
**Result:** Deadlocks, migration failures, portability issues, and broken imports.  
**Why Not To Do It:** FK constraints violate Lupopedia doctrine and destroy portability.  
**Lesson:** *No foreign keys. Ever. Applicationâ€‘managed integrity only.*

---

## 2025â€‘10 â€” Mixing Signed and Unsigned BIGINTs
**Mistake:** Allowed AI to generate inconsistent integer types.  
**Result:** Silent truncation, failed joins, and unpredictable behavior.  
**Why Not To Do It:** Inconsistent types break everything at scale.  
**Lesson:** *All IDs are BIGINT, signed, consistent.*

---

## 2025â€‘11 â€” Letting Modules Create Tables Without Prefixes
**Mistake:** Crafty Syntax tables originally lived in the same namespace as core tables.  
**Result:** Naming collisions, unclear ownership, and table count chaos.  
**Why Not To Do It:** Modules must be isolated and identifiable.  
**Lesson:** *Every module gets a prefix. Core tables have none.*

---

## 2025â€‘12 â€” Allowing Table Count to Grow Without Limits
**Mistake:** No upper bound on schema size.  
**Result:** Explosion of tables, difficulty navigating schema, and unclear boundaries.  
**Why Not To Do It:** Unbounded growth leads to unmaintainable systems.  
**Lesson:** *Core â‰¤ 88 tables. Core installation â‰¤ 99 tables. Modules beyond that must document their footprint.*

---

## 2026â€‘01 â€” Treating Crafty Syntax as a Separate System
**Mistake:** Maintaining Crafty Syntax as a standalone subsystem instead of integrating it.  
**Result:** Duplicate tables (`paths_daily`, `visits_daily`, etc.) and redundant logic.  
**Why Not To Do It:** Duplication increases maintenance cost and schema bloat.  
**Lesson:** *Absorb Crafty tables into core when appropriate. Remove or merge duplicates.*

---

## 2026â€‘01 â€” Not Keeping a Centralized Refactor Log
**Mistake:** Refactoring tables without a single authoritative mapping file.  
**Result:** Confusion across IDEs and LLMs, repeated questions, inconsistent migrations.  
**Why Not To Do It:** Multiâ€‘agent development requires shared memory.  
**Lesson:** *Maintain `refactor.md` as the single source of truth.*

---

## 2026â€‘01 â€” Not Tracking Multiâ€‘Agent Conversations
**Mistake:** Switching between Copilot, Grok, Gemini, DeepSeek, Cursor, Windsurf, and WebStorm with no shared log.  
**Result:** Lost context, repeated work, and contradictory suggestions.  
**Why Not To Do It:** Multiâ€‘agent orchestration needs a timeline.  
**Lesson:** *Maintain `DIALOG.md` for crossâ€‘agent continuity.*

---

## 2026â€‘01 â€” Using Server Timezone Instead of UTC
**Mistake:** Used `date("YmdHis")` which uses server timezone.  
**Result:** Timezone-dependent timestamps, DST issues, and inconsistent data.  
**Why Not To Do It:** Server timezones vary, causing data corruption and migration nightmares.  
**Lesson:** *Always use `gmdate("YmdHis")` or `gmmktime()` for UTC timestamps. Never use `date()` for database timestamps.*

---

## 2026â€‘01 â€” Using Legacy MySQL Functions Instead of PDO
**Mistake:** Used `mysql_query()`, `mysql_fetch_row()`, `mysql_num_rows()` in refactored code.  
**Result:** SQL injection vulnerabilities, deprecated functions, and non-portable code.  
**Why Not To Do It:** Legacy MySQL functions are deprecated and insecure.  
**Lesson:** *Always use PDO prepared statements via `classes/pdo_db.php`. Replace `fetchRow()` with `fetch()`, `numrows()` with `rowCount()`.*

---

## 2026â€‘01 â€” Modifying Legacy Code Reference
**Mistake:** Accidentally edited files in `lupo-legacy/craftysyntax/` during development.  
**Result:** Lost reference to original auto-installer codebase.  
**Why Not To Do It:** Legacy code must remain untouched as exact reference baseline.  
**Lesson:** *Legacy code is read-only. Build new code in `modules/`. Never modify `lupo-legacy/`.*

---

## 2026â€‘01 â€” Adding Git Before First Public Release
**Mistake:** (Prevented by policy) Using Git/GitHub during initial development.  
**Result:** (Avoided) `.git` folders interfering with FTP deployment, premature public exposure.  
**Why Not To Do It:** FTP-based deployment requires clean file structure. First release should be polished before going public.  
**Lesson:** *No Git until first public release. Use ZIP archives for FTP transfers.*

---

## 2026â€‘01 â€” Dragging and Dropping .git Folders in FileZilla
**Mistake:** Used FileZilla's drag-and-drop to upload the entire project directory, which included `.git` folders.  
**Result:** Corrupted file transfers, question mark folders, and a messy server structure.  
**Why Not To Do It:** FileZilla's drag-and-drop doesn't respect `.gitignore` and will upload everything, including version control metadata that can cause issues.  
**Lesson:** *When using FileZilla, manually select only the necessary files/folders to upload, or create a clean deployment package first. Never drag-and-drop the entire project folder.*

---

## 2026â€‘01 â€” Not Documenting Module Table Prefixes
**Mistake:** Created module tables without documenting naming conventions.  
**Result:** Confusion about which tables belong to which module, unclear table ownership.  
**Why Not To Do It:** Multi-module systems need clear boundaries and documentation.  
**Lesson:** *Every module must document its table prefix and table list in `modules/{modulename}/database/README.md`.*

---

## 2026â€‘01 â€” Confusing Core Installation Limit with Total Limit
**Mistake:** Thought 99-table limit applied to all modules combined.  
**Result:** Unnecessary constraints on optional modules.  
**Why Not To Do It:** Core installation (with bundled Crafty Syntax) has the 99-table limit. Optional modules can extend beyond it.  
**Lesson:** *99-table limit applies only to core installation (88 core + Crafty Syntax). Optional modules like Dialog can add tables beyond this limit.*

---

# Ongoing Rule

Whenever I make a mistake, discover a dead end, or learn something the hard way,  
**I add it here.**  
This file is a map of every pitfall I've already fallen into â€” so I never fall into it again.

---

*This document is part of Lupopedia's development documentation. Update it whenever new mistakes are discovered or lessons are learned.*

---

## Related Documentation

**Core Doctrines (Referenced in Lessons):**
- **[No Foreign Keys Doctrine](../../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md)** - Why FK constraints are forbidden (lessons from 2025-09)
- **[Database Philosophy](../../architecture/DATABASE_PHILOSOPHY.md)** - Application-first validation principles (lessons from 2025-08)
- **[WOLFIE Timestamp Doctrine](../../developer/dev/WOLFIE_TIMESTAMP_DOCTRINE.md)** - UTC timestamp requirements (lessons from 2026-01)
- **[Table Prefixing Doctrine](../../doctrine/TABLE_PREFIXING_DOCTRINE.md)** - Module table isolation (lessons from 2025-11)

**Development Process:**
- **[Contributor Training](../../developer/dev/CONTRIBUTOR_TRAINING.md)** - Standards that prevent these mistakes
- **[Multi-IDE Workflow](../../architecture/multi-ide-workflow.md)** - Managing multiple AI agents (lessons from 2026-01)
- **[PDO Conversion Doctrine](../../doctrine/PDO_CONVERSION_DOCTRINE.md)** - Modern database practices (lessons from 2026-01)

---
