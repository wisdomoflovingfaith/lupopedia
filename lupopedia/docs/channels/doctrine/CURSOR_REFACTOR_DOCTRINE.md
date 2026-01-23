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
  message: Created CURSOR_REFACTOR_DOCTRINE.md with mandatory rules for rewriting legacy Crafty Syntax PHP. This doctrine is non-negotiable and must be followed for every refactor. No frameworks, no modern PHP features, preserve all logic exactly.
tags:
  categories: ["documentation", "doctrine", "refactoring", "cursor"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
in_this_file_we_have:
  - Preserve All Logic Exactly
  - No Frameworks, No Composer, No Autoloaders
  - SQL Rewrites MUST Follow the Refactor JSON
  - No Column-Level Charset or Collation
  - No Foreign Keys, No Constraints, No Triggers
  - Maintain File Structure Unless Instructed Otherwise
  - One Page at a Time, One Function at a Time
  - No Modern PHP Features Unless Explicitly Allowed
  - Preserve Global Variables and Legacy State
  - No Silent Behavior Changes
  - All New Code Must Follow Lupopedia Doctrine
  - Cursor Must Not Invent Missing Context
  - Cursor Must Respect the Agent Activation Doctrine
  - Cursor Must Not Introduce Security Changes Without Permission
  - Cursor Must Output Clean, Minimal, Doctrine-Aligned Code
file:
  title: "Cursor Refactor Doctrine"
  description: "Mandatory rules Cursor must follow when rewriting legacy Crafty Syntax PHP code for Lupopedia 4.0.0"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# â­ **CURSOR REFACTOR DOCTRINE (Lupopedia 4.0.0)**
### **The Mandatory Rules Cursor Must Follow When Rewriting Legacy Crafty Syntax PHP**

This doctrine is **non-negotiable**. Cursor must follow it for every refactor.

---

## **ðŸ”µ 1. Preserve All Logic Exactly**

**Cursor must not change the behavior of any legacy PHP code unless explicitly instructed.**

**Prohibited:**
- No "optimizations"
- No reordering logic
- No removing "unused" variables
- No simplifying conditionals
- No rewriting control flow
- No renaming variables unless instructed
- No changing global state behavior

**Allowed:**
- Modernize syntax, not behavior
- Update deprecated PHP functions to modern equivalents (if behavior is identical)
- Fix syntax errors that prevent execution

**Critical Rule:**
- **Modernize syntax, not behavior.**
- If you're not sure if a change affects behavior, don't make it.

---

## **ðŸ”µ 2. No Frameworks, No Composer, No Autoloaders**

**Cursor must not introduce:**

- Laravel
- Symfony
- Composer
- Namespaces
- Autoloaders
- Traits
- Enums
- Attributes
- Dependency injection
- ORM
- PDO exceptions (unless explicitly allowed)

**Lupopedia is procedural, portable, dependency-free PHP.**

**Critical Rule:**
- Lupopedia runs on bare PHP with MySQL
- No external dependencies
- No package managers
- No framework abstractions

---

## **ðŸ”µ 3. SQL Rewrites MUST Follow the Refactor TOON Files and Toon Data Files**

**Cursor must:**

- use the new table name from refactor TOON file (see [TOON_DOCTRINE.md](TOON_DOCTRINE.md))
- use the new column names from refactor TOON file
- **verify all column names exist in the toon file** (`database/toon_data/{new_table_name}.toon` - TOON format)
- apply all transforms
- apply all boolean conversions
- apply all prefix rules
- drop all removed columns
- add all required columns

**All 145 tables have toon files in `database/toon_data/`.**

**Cursor must never rewrite SQL based on assumptions.**

**Cursor must never introduce JOINs, subqueries, or "optimizations" unless explicitly instructed.**

**Critical Rule:**
- All SQL rewrites must reference the refactor TOON files in `refactors/` (see [TOON_DOCTRINE.md](TOON_DOCTRINE.md))
- **All column names must be verified against toon files in `database/toon_data/` (TOON format)**
- **Never guess column names â€” all 145 tables have toon files**
- **Never "correct" column name typos** â€” Legacy code from 25 years ago may have typos (e.g., "visiblity" instead of "visibility"). These typos are preserved in the database and must be preserved in refactored code.
- **Never convert TOON to JSON â€” work with TOON directly**
- Never guess table or column mappings
- Never "improve" SQL without explicit instruction

---

## **ðŸ”µ 4. No Column-Level Charset or Collation**

**Cursor must never generate:**

```sql
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci
```

at the column level.

**Charset and collation belong only at the table level:**

```sql
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci
```

**Cursor must remove any column-level charset/collation it encounters.**

**Cursor must never generate MySQL 8 defaults like `utf8mb4_0900_ai_ci`.**

**Critical Rule:**
- Charset and collation are table-level only
- Remove all column-level charset/collation declarations
- Use `utf8mb4_unicode_ci`, not MySQL 8 defaults

---

## **ðŸ”µ 5. No Foreign Keys, No Constraints, No Triggers**

**Cursor must not add:**

- foreign keys
- `ON DELETE CASCADE`
- `ON UPDATE CASCADE`
- triggers
- stored procedures
- check constraints

**Lupopedia doctrine forbids all of these.**

**Critical Rule:**
- See [NO_FOREIGN_KEYS_DOCTRINE.md](NO_FOREIGN_KEYS_DOCTRINE.md)
- The database is a persistence layer, not a logic layer
- All validation and relationships are handled in PHP

---

## **ðŸ”µ 6. Maintain File Structure Unless Instructed Otherwise**

**Cursor must:**

- keep the same file names
- keep the same include order
- keep the same directory structure
- keep the same routing logic

**Unless explicitly instructed to reorganize.**

**Critical Rule:**
- Don't reorganize files without explicit instruction
- Preserve existing file structure and organization
- Maintain backward compatibility with existing includes

---

## **ðŸ”µ 7. One Page at a Time, One Function at a Time**

**Cursor must not attempt to refactor the entire system at once.**

**Cursor must operate on:**

- one file
- one function
- one SQL query

**â€¦per request.**

**Cursor must not assume context outside the provided file.**

**Critical Rule:**
- Focus on the specific file/function/query requested
- Don't refactor related files unless explicitly asked
- Don't make assumptions about the broader system

---

## **ðŸ”µ 8. No Modern PHP Features Unless Explicitly Allowed**

**Cursor must not introduce:**

- strict types (`declare(strict_types=1)`)
- return types (unless explicitly allowed)
- typed properties
- arrow functions
- match expressions
- enums
- attributes

**Unless explicitly instructed.**

**Lupopedia targets PHP 7.4+ procedural compatibility.**

**Critical Rule:**
- Use PHP 7.4 compatible syntax
- Prefer procedural over object-oriented
- Don't use PHP 8+ features without explicit permission

---

## **ðŸ”µ 9. Preserve Global Variables and Legacy State**

**Crafty Syntax relies heavily on:**

- `$GLOBALS`
- `$_SESSION`
- `$_REQUEST`
- `$_GET` / `$_POST`
- include-based state

**Cursor must not remove or refactor these unless explicitly instructed.**

**Critical Rule:**
- Legacy code uses global state extensively
- Don't refactor to dependency injection
- Don't remove global variables
- Preserve existing state management

---

## **ðŸ”µ 10. No Silent Behavior Changes**

**Cursor must not:**

- change default values
- change error handling
- change session behavior
- change cookie behavior
- change redirect behavior
- change permission checks
- change authentication logic

**Unless explicitly instructed.**

**Critical Rule:**
- If behavior might change, don't make the change
- Preserve all existing behavior exactly
- Ask for clarification if unsure

---

## **ðŸ”µ 11. All New Code Must Follow Lupopedia Doctrine**

**Cursor must follow:**

- **timestamp doctrine** (`YYYYMMDDHHMMSS` format, `BIGINT(14) UNSIGNED`)
- **naming doctrine** (snake_case, `_id`, `_ymdhis` suffixes)
- **no foreign keys**
- **no triggers**
- **no column-level charset**
- **no MySQL 8 collation**
- **no framework dependencies**
- **no autoloaders**
- **no magic**

**Critical Rule:**
- All new code must align with Lupopedia doctrine
- See [ARCHITECTURE_SYNC.md](ARCHITECTURE_SYNC.md) for complete doctrine
- See [NO_FOREIGN_KEYS_DOCTRINE.md](NO_FOREIGN_KEYS_DOCTRINE.md) for database rules

---

## **ðŸ”µ 12. Cursor Must Not Invent Missing Context**

**If Cursor does not know:**

- what a variable means
- what a function does
- what a table maps to
- what a column maps to

**â€¦it must ask for clarification, not guess.**

**Critical Rule:**
- When in doubt, ask
- Don't make assumptions
- Don't invent mappings
- Reference the refactor JSON files

---

## **ðŸ”µ 13. Cursor Must Respect the Agent Activation Doctrine**

**Cursor must not:**

- activate agents
- call agents
- wire agents
- generate agent logic

**â€¦unless the agent is explicitly marked `is_active = 1`.**

**Cursor must respect:**

- dependency rules
- faucet rules
- emotional triad boundaries

**Critical Rule:**
- See [ARCHITECTURE_SYNC.md](ARCHITECTURE_SYNC.md) Section 12: Agent Dependency Doctrine
- All agent activation must go through PHP validation
- Never bypass agent activation checks

---

## **ðŸ”µ 14. Cursor Must Not Introduce Security Changes Without Permission**

**Cursor must not:**

- add CSRF tokens
- add password hashing changes
- add sanitization changes
- add escaping changes
- add validation changes

**Unless explicitly instructed.**

**Critical Rule:**
- Security changes can break existing functionality
- Don't "improve" security without explicit instruction
- Preserve existing security model unless asked to change it

---

## **ðŸ”µ 15. Cursor Must Output Clean, Minimal, Doctrine-Aligned Code**

**Cursor must:**

- remove noise
- remove redundant parentheses
- remove unused imports
- remove unnecessary whitespace
- keep code readable
- keep code consistent

**Cursor must not generate "WYSIWYG-style" bloat.**

**Critical Rule:**
- Code should be clean and minimal
- But don't remove functionality in the name of "cleanliness"
- Balance readability with preservation of logic

---

## **â­ Summary**

**This doctrine is non-negotiable.**

**Cursor must follow it for every refactor.**

**Key Principles:**
1. **Preserve behavior exactly** â€” Modernize syntax, not logic
2. **No frameworks** â€” Procedural, portable, dependency-free PHP
3. **Follow refactor TOON files and toon data files** â€” Never guess table/column mappings; all 149 tables have toon files in `database/toon_data/` (TOON format) for exact column names; never convert TOON to JSON (see [TOON_DOCTRINE.md](TOON_DOCTRINE.md))
4. **Respect Lupopedia doctrine** â€” Timestamps, naming, no FKs, etc.
5. **One thing at a time** â€” Focus on the specific request
6. **Ask, don't guess** â€” When in doubt, ask for clarification

**This is how legacy code is refactored into Lupopedia 4.0.0.**

---

*Last Updated: January 2026*  
*Version: 4.0.0*  
*Author: Captain Wolfie*

