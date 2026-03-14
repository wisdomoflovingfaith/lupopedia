# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\SQL_REWRITE_DOCTRINE.md"
  file_hash: "c3f9e39048118a176e507c83b0d37757a91e6d5f7af3d6abe7de0665da7ef4bd"
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
  file_path_from_root: "lupo-docs\channels\doctrine\SQL_REWRITE_DOCTRINE.md"
  file_hash: "442768d4501d705b34072020291bab6e685a4a7fa3cc94b84948c45a457e4197"
  file_path_from_root: "lupo-docs\channels\doctrine\SQL_REWRITE_DOCTRINE.md"
  file_hash: "975f29962c1af1b0d967ec102ea812acb23be83ffa2156e9490c473599e8e9d7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for SQL_REWRITE_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "sql_rewrite_doctrinemd"]
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
file.last_modified_system_version: 3.0.14
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "FF0000"
  message: Updated SQL_REWRITE_DOCTRINE.md to clarify that refactor files and toon files are TOON format, not JSON. All 145 tables have toon files - never guess column names, always check the toon file. Never convert TOON to JSON - work with TOON directly. See TOON_DOCTRINE.md.
tags:
  categories: ["documentation", "doctrine", "sql", "refactoring", "cursor"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
in_this_file_we_have:
  - The Refactor JSON and Toon Files Are the Only Sources of Truth
  - Table Names Must Be Rewritten Exactly as Specified
  - Column Names Must Follow the Column Map Exactly and Match Toon Files
  - Boolean Transforms Must Follow the JSON Rules
  - No SQL Optimizations Unless Explicitly Allowed
  - No MySQL 8 Defaults, Ever
  - No Foreign Keys, No Constraints, No Triggers
  - INSERT Statements Must Include Added Columns
  - UPDATE Statements Must Not Touch Added Columns Unless Specified
  - WHERE Clauses Must Preserve Legacy Behavior
  - SELECT * Must Be Expanded Only When Necessary
  - SQL Must Remain Procedural and Simple
  - Cursor Must Ask for Clarification When JSON Is Missing
  - Cursor Must Not Invent Missing Columns or Tables
  - All Rewritten SQL Must Be Clean, Minimal, and Doctrine-Aligned
file:
  title: "SQL Rewrite Doctrine"
  description: "Mandatory rules Cursor must follow when rewriting SQL from Crafty Syntax into Lupopedia 3.0.0"
  version: "3.0.0"
  status: published
  author: "Captain Wolfie"
---

# ⭐ **SQL REWRITE DOCTRINE (Lupopedia 3.0.1)**
### **Mandatory Rules Cursor Must Follow When Rewriting SQL from Crafty Syntax into Lupopedia**

This doctrine is **mandatory**. Cursor must follow it for every SQL rewrite.

**⚠️ IMPORTANT:**
- All table names must use `LUPO_PREFIX` from `lupopedia-config.php`. See [CONFIGURATION_DOCTRINE.md](CONFIGURATION_DOCTRINE.md) for configuration rules.
- Table prefixing and primary key naming: See [TABLE_PREFIXING_DOCTRINE.md](TABLE_PREFIXING_DOCTRINE.md).
- Charset and collation: See [CHARSET_COLLATION_DOCTRINE.md](CHARSET_COLLATION_DOCTRINE.md).

**For focused mapping rules, see [SQL_REFACTOR_MAPPING_DOCTRINE.md](SQL_REFACTOR_MAPPING_DOCTRINE.md).**

---

## **🔵 1. The Refactor TOON Files and Toon Data Files Are the Only Sources of Truth**

**Cursor must load the matching TOON file from:**

```
lupo-database/refactors/{legacy_table}.json
```

**(Note: These files are TOON format, not JSON, despite the `.json` extension. See [TOON_DOCTRINE.md](TOON_DOCTRINE.md))**

**for table and column mappings.**

**Cursor must also load the toon file from:**

```
lupo-database/toon_data/{new_table_name}.toon
```

**(Note: Files are TOON format with `.toon` extension. Older files may have `.json` extension but are still TOON format. See [TOON_DOCTRINE.md](TOON_DOCTRINE.md))**

**to verify exact column names, types, and structure.**

**All 145 tables have toon files in `lupo-database/toon_data/`.**

**Cursor must use these files to:**

- verify exact column names
- verify column types
- verify table structure
- ensure no column names are guessed

**Cursor must not:**

- guess column names
- infer column names
- assume column names
- "optimize"
- rename based on patterns
- rewrite based on context

**If it's not in the JSON or toon file, Cursor must not change it.**

**Critical Rule:**
- The refactor TOON files provide mappings (see [TOON_DOCTRINE.md](TOON_DOCTRINE.md))
- The toon files provide exact column names and structure (TOON format)
- **Never guess column names — always check the toon file**
- **Never convert TOON to JSON — work with TOON directly**
- If a column doesn't exist in the toon file, ask for clarification

---

## **🔵 2. Table Names Must Be Rewritten Exactly as Specified**

**Critical:** All table names MUST use `LUPO_PREFIX` from `lupopedia-config.php`. Never hardcode table names.

**Example:**
```php
$table_name = LUPO_PREFIX . 'contents';  // ✅ CORRECT
$table_name = 'lupo-contents';            // ❌ WRONG - hardcoded
```

**Cursor must:**

- replace the old table name with `LUPO_PREFIX . 'new_table'` from the JSON
- apply the prefix placeholder if enabled
- never use unprefixed table names
- never invent new table names
- never pluralize or singularize

**Example:**

```sql
FROM livehelp_autoinvite
```

**must become:**

```sql
FROM {{prefix}}crafty_auto_invite
```

**Exactly. No deviations.**

**Critical Rule:**
- Table names must match the JSON exactly
- Prefix placeholders must be preserved
- No creative naming or pattern matching

---

## **🔵 3. Column Names Must Follow the Column Map Exactly and Match Toon Files**

**Cursor must:**

- rename every column according to `column_map` in the refactor TOON file (see [TOON_DOCTRINE.md](TOON_DOCTRINE.md))
- **verify the new column name exists in the toon file** (`lupo-database/toon_data/{new_table_name}.toon` - TOON format)
- apply transforms when specified
- drop columns listed in `dropped_columns`
- add columns listed in `added_columns` only when inserting

**Cursor must not:**

- rename columns not listed
- remove columns not listed
- add columns not listed
- reorder columns unless required by SQL syntax
- **guess column names — always check the toon file**

**Critical Rule:**
- Every column rename must be in the refactor TOON file
- **Every new column name must exist in the toon file**
- **Never guess column names — all 145 tables have toon files**
- **Never "correct" column name typos** — Legacy code from 25 years ago may have typos (e.g., "visiblity" instead of "visibility"). These typos are preserved in the database and must be preserved in refactored code.
- Don't rename columns that aren't mapped
- Don't add columns that aren't in `added_columns`
- If a column name doesn't match the toon file, ask for clarification

---

## **🔵 4. Boolean Transforms Must Follow the JSON Rules**

**If the JSON specifies:**

```json
"transform": "Y_to_1_else_0"
```

**Cursor must:**

- convert `'Y'` → `1`
- convert anything else → `0`

**Cursor must not invent new boolean logic.**

**Cursor must not convert `'N'` to `NULL` or `FALSE` unless explicitly instructed.**

**Critical Rule:**
- Follow the transform rules exactly as specified in the JSON
- Don't invent new boolean conversion logic
- Preserve the exact transform behavior

---

## **🔵 5. No SQL "Optimizations" Unless Explicitly Allowed**

**Cursor must not:**

- rewrite queries into JOINs
- merge queries
- split queries
- remove LIMIT clauses
- remove ORDER BY clauses
- reorder WHERE conditions
- remove parentheses
- simplify expressions
- rewrite subqueries
- change query structure

**Unless explicitly instructed.**

**Legacy behavior must be preserved.**

**Critical Rule:**
- Don't "improve" SQL unless explicitly asked
- Preserve query structure exactly
- Don't optimize for performance without permission

---

## **🔵 6. No MySQL 8 Defaults, Ever**

**Cursor must not generate:**

- `utf8mb4_0900_ai_ci`
- `CHARACTER SET` at column level
- `COLLATE` at column level
- `JSON_TABLE`
- `WITH RECURSIVE`
- window functions
- CTEs
- MySQL 8-only syntax

**Lupopedia must remain compatible with:**

- MySQL 5.7
- MariaDB 10.x

**Critical Rule:**
- Use `utf8mb4_unicode_ci` (table-level only)
- No MySQL 8-specific features
- Maintain backward compatibility

**⚠️ IMPORTANT:** See [CHARSET_COLLATION_DOCTRINE.md](CHARSET_COLLATION_DOCTRINE.md) for detailed charset and collation rules.

---

## **🔵 7. No Foreign Keys, No Constraints, No Triggers**

**Cursor must not introduce:**

- `FOREIGN KEY`
- `REFERENCES`
- `ON DELETE CASCADE`
- `ON UPDATE CASCADE`
- `CHECK` constraints
- triggers
- stored procedures

**Lupopedia doctrine forbids all of these.**

**Critical Rule:**
- See [NO_FOREIGN_KEYS_DOCTRINE.md](NO_FOREIGN_KEYS_DOCTRINE.md)
- The database is a persistence layer, not a logic layer
- All relationships are handled in PHP

---

## **🔵 8. INSERT Statements Must Include Added Columns**

**If the JSON defines:**

```json
"added_columns": {
  "created_ymdhis": { "default": "20250101000000" }
}
```

**Cursor must:**

- include the column in INSERT statements
- use the default value unless instructed otherwise

**Cursor must not:**

- add these columns to SELECT
- add these columns to UPDATE
- add these columns to WHERE

**Unless explicitly instructed.**

**Critical Rule:**
- Added columns go in INSERT statements only
- Use the default value from the JSON
- Don't add them to other statement types

---

## **🔵 9. UPDATE Statements Must Not Touch Added Columns Unless Specified**

**Cursor must not update:**

- `created_ymdhis`
- `deleted_ymdhis`
- `is_deleted`

**Unless the JSON explicitly instructs it.**

**Critical Rule:**
- Added columns are typically immutable
- Don't update timestamp or soft-delete columns
- Preserve the original values

---

## **🔵 10. WHERE Clauses Must Preserve Legacy Behavior**

**Cursor must not:**

- change comparison operators
- change LIKE patterns
- change NULL handling
- change boolean logic
- change grouping
- change parentheses

**Legacy behavior must remain identical.**

**Critical Rule:**
- WHERE clauses must work exactly as before
- Don't "improve" boolean logic
- Preserve all comparison behavior

---

## **🔵 11. SELECT * Must Be Expanded Only When Necessary**

**Cursor must:**

- preserve `SELECT *` unless the JSON requires dropping columns
- expand `SELECT *` only when needed to remove dropped columns

**Cursor must not:**

- reorder columns
- add columns
- remove columns not listed in `dropped_columns`

**Critical Rule:**
- Only expand `SELECT *` when columns must be dropped
- Don't expand unnecessarily
- Preserve column order

---

## **🔵 12. SQL Must Remain Procedural and Simple**

**Cursor must not introduce:**

- ORM-style queries
- query builders
- prepared statement abstractions
- PDO exceptions
- modern SQL features

**Lupopedia uses:**

- raw SQL
- mysqli
- procedural style

**Critical Rule:**
- Keep SQL simple and procedural
- No abstractions or query builders
- Use raw SQL with mysqli

---

## **🔵 13. Cursor Must Ask for Clarification When JSON or Toon Files Are Missing**

**If Cursor cannot find:**

- the legacy table in refactor JSON
- the column mapping in refactor JSON
- the transform in refactor JSON
- the new table name
- **the toon file for the new table** (`lupo-database/toon_data/{new_table_name}.json`)
- **the column name in the toon file**

**…it must ask for clarification instead of guessing.**

**Critical Rule:**
- When in doubt, ask
- Don't make assumptions
- Don't invent mappings
- **Don't guess column names — all 145 tables have toon files**
- Always verify column names against the toon file

---

## **🔵 14. Cursor Must Not Invent Missing Columns or Tables**

**If a column is referenced in the old SQL but not in the JSON:**

**Cursor must ask:**

> "This column is not in the refactor JSON. Should it be mapped, dropped, or preserved?"

**If a column name doesn't exist in the toon file:**

**Cursor must ask:**

> "This column name doesn't exist in the toon file `lupo-database/toon_data/{table_name}.json`. Should I use a different column name, or is this column missing from the toon file?"

**Cursor must not guess.**

**Critical Rule:**
- Never invent column mappings
- Always ask when a column is missing from the JSON
- **Always verify column names against the toon file**
- **All 145 tables have toon files — use them, don't guess**
- Don't assume what should happen

---

## **🔵 15. All Rewritten SQL Must Be Clean, Minimal, and Doctrine-Aligned**

**Cursor must:**

- remove noise
- remove redundant whitespace
- remove unnecessary parentheses
- keep SQL readable
- keep SQL consistent

**Cursor must not generate "ORM-style" or "framework-style" SQL.**

**Critical Rule:**
- SQL should be clean and minimal
- But don't remove functionality in the name of "cleanliness"
- Balance readability with preservation of behavior

---

## **⭐ Summary**

**This doctrine is mandatory.**

**Cursor must follow it for every SQL rewrite.**

**Key Principles:**
1. **Refactor TOON files and toon data files are the only sources of truth** — Never guess mappings or column names (see [TOON_DOCTRINE.md](TOON_DOCTRINE.md))
2. **Table names must match TOON file exactly** — No deviations
3. **Column names must follow column_map AND match toon files** — All 145 tables have toon files in `lupo-database/toon_data/` (TOON format)
4. **Never guess column names** — Always check the toon file for exact column names
5. **Never convert TOON to JSON** — Work with TOON directly as TOON format
6. **No optimizations** — Preserve legacy behavior exactly
7. **No MySQL 8 defaults** — Maintain backward compatibility
8. **No foreign keys** — Lupopedia doctrine forbids them
9. **Ask, don't guess** — When in doubt, ask for clarification

**This is how SQL is rewritten from Crafty Syntax into Lupopedia 3.0.0.**

---

*Last Updated: January 2026*  
*Version: 3.0.0*  
*Author: Captain Wolfie*
