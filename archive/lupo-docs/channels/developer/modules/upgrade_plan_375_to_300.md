# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/developer/modules/UPGRADE_PLAN_3.7.5_TO_3.0.0.md"
  file_hash: "10ecccff5e054e190110f8d28a2684f7973f1a947e6e744b7aa3afc4341fb749"
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
  file_path_from_root: "lupo-docs\channels\developer\modules\UPGRADE_PLAN_3.7.5_TO_3.0.0.md"
  file_hash: "8cd9edc101338fcf6f64a9026ca09615ab204018d0442c01f1d7ba942771ddaa"
  file_path_from_root: "lupo-docs\channels\developer\modules\UPGRADE_PLAN_3.7.5_TO_3.0.0.md"
  file_hash: "ddfe91e3bcc1361d5131459bd9528a282527fc5726c81b064a6fca7203622084"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for UPGRADE_PLAN_3.7.5_TO_3.0.0.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "developer", "modules", "upgrade_plan_375_to_300md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

Absolutely, Eric — here is the **full, final, production‑ready**  
**UPGRADE_PLAN_3.7.5_TO_3.0.0.md**, rewritten to match:

- the **real architecture** of Lupopedia 3.0.0  
- the **Crafty Syntax compatibility layer**  
- the **refactor JSON system**  
- the **importer + toon file workflow**  
- the **133 → 99 table transition**  
- the **requirement that `/livehelp.php` and `/livehelp_js.php` still work exactly like 3.7.5**  
- the **multi‑agent IDE workflow** (Cursor, Windsurf/Cascade, DeepSeek)  
- the **WOLFIE Header doctrine**  

This is the document you will ship with 3.0.0.

---

# ✅ **UPGRADE_PLAN_3.7.5_TO_3.0.0.md**  
*(Complete, polished, Lupopedia‑accurate, ready for lupo-docs/)*

```markdown
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Complete upgrade plan from Crafty Syntax 3.7.5 to Lupopedia + Crafty Syntax 3.0.0, including refactor JSON system and compatibility layer."
tags:
  categories: ["upgrade", "migration", "craftysyntax"]
  collections: ["core-docs"]
  channels: ["public", "dev"]
file:
  title: "Upgrade Plan: Crafty Syntax 3.7.5 → 3.0.0"
  description: "Full migration plan for upgrading legacy Crafty Syntax installations into Lupopedia 3.0.0 with compatibility layer and refactor JSON system."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  architect: Captain Wolfie
  author: "Captain Wolfie"
---

# 🛠️ Upgrade Plan  
## Crafty Syntax 3.7.5 → Lupopedia + Crafty Syntax 3.0.0

This document describes the **official, deterministic, fully backward‑compatible** upgrade path for Crafty Syntax Live Help (CSLH) versions **3.6.1 through 3.7.5** into the new **Lupopedia 3.0.0** platform.

Crafty Syntax 3.0.0 is now a **bundled module** inside Lupopedia and shares the same version number.  
Both systems are **version‑locked** and released together.

---

# 📜 Overview

### Why this upgrade exists
Crafty Syntax development paused for nearly **15 years**.  
Lupopedia 3.0.0 is a **complete architectural rewrite** that:

- modernizes the schema  
- replaces every legacy table  
- introduces a semantic OS  
- adds a multi‑agent AI framework  
- integrates Crafty Syntax as a module  
- preserves historical data through explicit migration  
- maintains full backward compatibility with 3.7.5 endpoints  

### What this upgrade does
- Migrates **34 legacy Crafty Syntax tables**  
- Rebuilds the schema into **99 total tables**  
- Converts all legacy data into the new domain model  
- Rewrites all SQL queries using **refactor JSON**  
- Preserves all old URLs (`livehelp.php`, `livehelp_js.php`, etc.)  
- Drops all legacy tables after successful import  
- Installs Crafty Syntax 3.0.0 as a Lupopedia module  

### What this upgrade does *not* do
- It does **not** preserve active chat sessions  
- It does **not** preserve ephemeral channel state  
- It does **not** preserve legacy PHP code  
- It does **not** attempt to emulate 3.x database structure  

This is a **true modernization**, not a patch.

---

# 🧩 Migration Scope

### Total tables present during migration: **133**

| Category | Count |
|---------|-------|
| Legacy Crafty Syntax tables | **34** |
| Core Lupopedia tables | **91** |
| Crafty Syntax module tables (new) | **8** |
| **Final total after migration** | **99** |

All 34 legacy tables are **dropped** after successful migration.

---

# 🧬 Doctrine Requirements (Non‑Negotiable)

Lupopedia 3.0.0 follows strict database rules:

- **No foreign keys**  
- **No triggers**  
- **No stored procedures**  
- **No cascading deletes**  
- **Soft deletes only**  
- **UTC timestamps (`YYYYMMDDHHMMSS`)**  
- **Polymorphic linking via `edges`**  
- **JSON metadata fields**  
- **Application‑managed integrity**  

All migration scripts follow this doctrine.

---

# 🟦 Backward Compatibility Layer (Critical)

Version 3.0.0 must continue to behave **exactly like Crafty Syntax 3.7.5** when users or websites request:

- `/lupopedia/livehelp.php`
- `/lupopedia/livehelp_js.php`
- `/lupopedia/livehelp_operator.php`
- `/lupopedia/livehelp_admin.php`
- `/lupopedia/livehelp_js.php?cmd=init`
- `/lupopedia/livehelp.php?department=…`
- `/lupopedia/livehelp.php?embed=…`

These URLs are used by:

- embedded chat widgets  
- external websites  
- old JavaScript includes  
- mobile apps  
- bookmarks  
- iframes  
- third‑party integrations  

**They must not break.**

### ✔ Solution: Compatibility Layer (Legacy Runtime Mode)

The Crafty Syntax module includes:

```
modules/craftysyntax/public/livehelp.php
modules/craftysyntax/public/livehelp_js.php
modules/craftysyntax/public/livehelp_operator.php
```

These files:

- preserve all old GET/POST parameters  
- preserve old output formats (HTML, JS, XMLHTTP)  
- call into the new Crafty Syntax 3.0.0 runtime  
- use the new schema under the hood  
- behave exactly like 3.7.5  

This ensures **zero breaking changes** for existing installations.

---

# 🟧 Refactor JSON System (Core to the Upgrade)

Every legacy table has a **refactor JSON file** describing:

- old table name  
- new table name  
- column mappings  
- value transformations  
- dropped columns  
- added columns  
- rewrite rules for Cursor/Windsurf/DeepSeek  

These JSON files allow IDE agents to **rewrite old SQL queries** safely.

### Example (simplified)

```json
{
  "old_table": "livehelp_autoinvite",
  "new_table": "crafty_auto_invite",
  "column_map": {
    "idnum": "autoinvite_id",
    "page": "page_url",
    "typeof": "invite_type"
  },
  "rewrite_rules": {
    "rename_table": true,
    "rename_columns": true
  }
}
```

### Purpose of refactor JSON
- Cursor rewrites old PHP code  
- Windsurf/Cascade rewrites SQL queries  
- DeepSeek rewrites logic blocks  
- All agents stay synchronized  
- No hallucinated schema  
- No accidental FK assumptions  
- No broken queries  

### Location
```
refactors/*.json
```

### Required for all 34 legacy tables.

---

# 🟫 Importer System (Deterministic Migration)

Each legacy table has a dedicated importer that:

- reads from the legacy table  
- transforms rows using refactor JSON  
- inserts into the new schema  
- logs actions  
- writes toon files  
- validates against CSV truth  

Importers run **inside the same database** (`old_craftysyntax`) to avoid cross‑DB confusion.

### Toon Files
Toon files are structured snapshots of importer output used to:

- validate correctness  
- compare against CSV truth  
- ensure deterministic migration  

---

# 🔄 Migration Order (Recommended)

To avoid dependency confusion, migrate in this order:

## **Phase 1 — Simple Tables**
1. `livehelp_departments`  
2. `livehelp_groups`  
3. `livehelp_modules`  
4. `livehelp_channels` (no data migrated)

## **Phase 2 — Medium Tables**
5. `livehelp_users`  
6. `livehelp_messages`  
7. `livehelp_autoinvite`  
8. `livehelp_operator_history`

## **Phase 3 — Complex Tables**
9. `livehelp_sessions`  
10. `livehelp_visits_daily`  
11. `livehelp_visits_monthly`  
12. `livehelp_referers_daily`  
13. `livehelp_referers_monthly`  
14. `livehelp_paths_daily`  
15. `livehelp_paths_monthly`

## **Phase 4 — Semantic Tables**
16. `livehelp_transcripts`  
17. `livehelp_questions`  
18. `livehelp_smilies`  
19. `livehelp_layer_invites`

## **Phase 5 — Special Tables**
20. `livehelp_config` → becomes JSON in `modules.config_json`  
21. `livehelp_websites` → becomes `domains`  
22. `livehelp_operators` → becomes `actors` + `actor_properties`  

## **Phase 6 — Final Cleanup**
- Drop all 34 legacy tables  
- Vacuum/optimize  
- Rebuild search index  
- Clear caches  

---

# 🧪 Validation Checklist

Before dropping legacy tables:

### ✔ All importers ran without errors  
### ✔ Toon files match CSV truth  
### ✔ Row counts match expected values  
### ✔ No NULL timestamps  
### ✔ No orphaned references  
### ✔ No missing domain mappings  
### ✔ All module tables populated  
### ✔ `modules.config_json` updated  
### ✔ Crafty Syntax module enabled  
### ✔ Compatibility layer endpoints tested  

---

# 🧹 Post‑Migration Cleanup

After validation:

1. Drop all `livehelp_*` tables  
2. Remove legacy PHP files (optional)  
3. Rebuild search index  
4. Clear caches  
5. Restart PHP-FPM/Apache  

---

# 🧭 Rollback Strategy

If anything fails:

- Importers are **idempotent**  
- Legacy tables remain untouched until final cleanup  
- You can re-run any importer safely  
- Toon files preserve the migration state  
- Refactor JSON ensures consistent rewrites  

Rollback is simply:

```
DROP DATABASE lupopedia;
REIMPORT legacy DB;
START OVER.
```

---

# 🤖 Multi‑Agent Workflow (Cursor, Windsurf, DeepSeek)

The upgrade is designed for **multi‑agent IDE collaboration**.

Agents use:

- **Refactor JSON** to rewrite SQL  
- **Inline Dialog** to coordinate changes  
- **WOLFIE Headers** to track metadata  
- **Toon files** to validate importer output  

This ensures:

- deterministic rewrites  
- no hallucinated schema  
- no accidental FK assumptions  
- consistent doctrine enforcement  

---

# 🎉 Completion

Once all steps are complete:

- Crafty Syntax 3.7.5 is fully modernized  
- All data lives in the Lupopedia domain model  
- The system now runs on the 3.0.0 semantic OS  
- You gain access to the agent ecosystem  
- You gain semantic navigation  
- You gain federated awareness  
- You gain the 99‑table core  
- All old URLs still work exactly as before  

Your installation is now **future‑proof**.

---

# 🐺 Final Notes from Captain Wolfie

This upgrade is more than a migration —  
it is the resurrection of a legacy system into a modern semantic platform.

Crafty Syntax lives on, not as a relic,  
but as a **first‑class module** inside Lupopedia.

Welcome to the new era.
```

---

If you want, I can now generate:

- the **Crafty Syntax compatibility layer files**  
- the **runtime bootstrap**  
- the **refactor JSON templates for all 34 tables**  
- the **importer skeleton**  
- the **toon file format**  

Just tell me what you want next.
