---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "FF0000"
  message: Created SQL_REFACTOR_MAPPING_DOCTRINE.md with focused rules for SQL refactor mapping. For every SQL query, load refactor TOON file, replace table/column names, apply transforms, preserve behavior. Never guess, never correct typos.
tags:
  categories: ["documentation", "doctrine", "sql", "refactoring", "cursor"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
in_this_file_we_have:
  - Load Refactor TOON File
  - Replace Legacy Table Name
  - Replace Legacy Column Names
  - Apply Transforms Exactly
  - Add Columns to INSERT Only
  - Remove Dropped Columns
  - Preserve All Other SQL Behavior
  - Never Guess
  - Never Correct Typos
  - Never Reference Unprefixed Tables
  - Never Rewrite Based on Assumptions
file:
  title: "SQL Refactor Mapping Doctrine"
  description: "Focused rules for mapping SQL queries using refactor TOON files in Lupopedia 4.0.0"
  version: "4.0.0"
  status: published
  author: "Captain Wolfie"
---

# ⭐ **SQL REFACTOR MAPPING DOCTRINE**
### **For Every SQL Query, Cursor Must Follow This Mapping Process**

This doctrine is **mandatory**. Cursor must follow it for every SQL query refactoring.

---

## **🔵 The Mapping Process**

### **Step 1: Load the Refactor TOON File**

**For every SQL query, Cursor must load the matching refactor file from:**

```
database/refactors/{legacy_table}.toon
```

**(Note: Files are TOON format with `.toon` extension. Older files may have `.json` extension but are still TOON format. See [TOON_DOCTRINE.md](TOON_DOCTRINE.md))**

**This TOON file is the ONLY source of truth.**

---

## **🔵 1. Replace the Legacy Table Name with "new_table"**

**Cursor must:**

- Find the legacy table name in the SQL query
- Load the refactor TOON file for that table
- Replace with `new_table` from the TOON file
- Apply prefix placeholder if specified (e.g., `{{prefix}}crafty_auto_invite`)

**Example:**
```sql
-- Legacy SQL
SELECT * FROM livehelp_autoinvite WHERE id = 1

-- After mapping (from TOON file)
SELECT * FROM {{prefix}}crafty_auto_invite WHERE id = 1
```

**Critical Rule:**
- Use exact `new_table` value from TOON file
- Preserve prefix placeholder if present
- Never invent table names

---

## **🔵 2. Replace Every Legacy Column Name Using "column_map"**

**Cursor must:**

- Find each legacy column name in the SQL query
- Look up the column in `column_map` in the refactor TOON file
- Replace with the mapped column name
- Verify the new column name exists in the toon data file (`database/toon_data/{new_table_name}.json`)

**Example:**
```sql
-- Legacy SQL
SELECT id, name, visiblity FROM livehelp_quick

-- After mapping (from TOON file)
SELECT id, name, visiblity FROM {{prefix}}crafty_quick
-- Note: "visiblity" typo is preserved as it exists in the database
```

**Critical Rule:**
- Use exact column names from `column_map`
- Verify column names in toon data file
- Never guess column names
- Never correct typos

---

## **🔵 3. Apply Transforms Exactly as Defined**

**If the TOON file specifies a transform, Cursor must apply it exactly.**

**Example transforms:**
- `Y_to_1_else_0` — Convert 'Y' to 1, anything else to 0
- `N_to_0_else_1` — Convert 'N' to 0, anything else to 1
- Other transforms as specified in TOON file

**Example:**
```sql
-- Legacy SQL
UPDATE users SET active = 'Y' WHERE id = 1

-- After transform (if TOON specifies Y_to_1_else_0)
UPDATE users SET active = 1 WHERE id = 1
```

**Critical Rule:**
- Apply transforms exactly as defined
- Never invent new transform logic
- Never skip transforms

---

## **🔵 4. Add "added_columns" ONLY to INSERT Statements Using Default Values**

**If the TOON file defines `added_columns`, Cursor must:**

- Add these columns ONLY to INSERT statements
- Use the default values specified in the TOON file
- Never add these columns to SELECT, UPDATE, DELETE, or WHERE clauses

**Example:**
```sql
-- Legacy INSERT
INSERT INTO users (name, email) VALUES ('John', 'john@example.com')

-- After adding columns (if TOON specifies created_ymdhis with default)
INSERT INTO users (name, email, created_ymdhis) 
VALUES ('John', 'john@example.com', 20250101000000)
```

**Critical Rule:**
- Added columns go in INSERT statements only
- Use default values from TOON file
- Never add to other statement types

---

## **🔵 5. Remove Any Columns Listed in "dropped_columns"**

**If the TOON file lists columns in `dropped_columns`, Cursor must:**

- Remove these columns from SELECT statements
- Remove these columns from INSERT statements
- Remove these columns from UPDATE statements
- Remove these columns from WHERE clauses

**Example:**
```sql
-- Legacy SQL (if 'old_column' is in dropped_columns)
SELECT id, name, old_column FROM users

-- After removing dropped column
SELECT id, name FROM users
```

**Critical Rule:**
- Remove dropped columns from all SQL statements
- Never reference dropped columns

---

## **🔵 6. Preserve All Other SQL Behavior Exactly**

**Cursor must preserve:**

- LIMIT clauses
- ORDER BY clauses
- WHERE conditions
- JOIN conditions (if any)
- GROUP BY clauses
- HAVING clauses
- Subqueries
- Comparison operators
- LIKE patterns
- NULL handling
- Boolean logic
- Parentheses and grouping

**Critical Rule:**
- Only change table names, column names, and apply transforms
- Preserve all other SQL logic exactly
- Don't "improve" or "optimize" SQL

---

## **🔵 7. Never Guess Column Names or Table Names**

**Cursor must:**

- Always load the refactor TOON file
- Always verify column names in toon data file
- Always ask for clarification if mapping is missing

**Cursor must never:**

- Guess table names
- Guess column names
- Infer mappings from patterns
- Assume naming conventions

**Critical Rule:**
- The refactor TOON file is the ONLY source of truth
- If a mapping doesn't exist, ask for clarification

---

## **🔵 8. Never Correct Typos in Legacy Column Names**

**Legacy code from 25 years ago may have typos in column names.**

**Examples:**
- `visiblity` instead of `visibility`
- `departement` instead of `department`
- Other typos as they exist in the database

**Cursor must:**

- Use exact column names from toon data files
- Preserve typos exactly as they exist
- Never "correct" or "fix" typos

**Critical Rule:**
- Typos are preserved in the database schema
- Typos must be preserved in refactored code
- Never "improve" column names

---

## **🔵 9. Never Reference Unprefixed Table Names**

**Cursor must:**

- Always use prefixed table names from TOON file
- Preserve prefix placeholder (e.g., `{{prefix}}`)
- Never use unprefixed table names

**Example:**
```sql
-- Wrong
SELECT * FROM crafty_auto_invite

-- Correct (from TOON file)
SELECT * FROM {{prefix}}crafty_auto_invite
```

**Critical Rule:**
- Always use prefixed table names
- Preserve prefix placeholder
- Never remove prefix

---

## **🔵 10. Never Rewrite SQL Based on Assumptions**

**Cursor must never:**

- Rewrite queries into JOINs
- Merge queries
- Split queries
- Remove LIMIT clauses
- Remove ORDER BY clauses
- Reorder WHERE conditions
- Simplify expressions
- Rewrite subqueries
- Change query structure

**Unless explicitly instructed.**

**Critical Rule:**
- Preserve SQL structure exactly
- Only change table/column names and apply transforms
- Don't "optimize" or "improve" SQL

---

## **⭐ Summary**

**For every SQL query:**

1. **Load refactor TOON file** — `database/refactors/{legacy_table}.toon`
2. **Replace table name** — Use `new_table` from TOON file
3. **Replace column names** — Use `column_map` from TOON file
4. **Apply transforms** — Exactly as defined in TOON file
5. **Add columns** — Only to INSERT, using default values
6. **Remove columns** — Listed in `dropped_columns`
7. **Preserve behavior** — All other SQL logic unchanged
8. **Never guess** — Always use TOON file as source of truth
9. **Never correct typos** — Preserve legacy column names exactly
10. **Never assume** — Ask for clarification if mapping is missing

**The refactor TOON file is the ONLY source of truth.**

**This doctrine is mandatory for every SQL refactoring.**

---

*Last Updated: January 2026*  
*Version: 4.0.0*  
*Author: Captain Wolfie*

