# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\PDO_CONVERSION_DOCTRINE.md"
  file_hash: "987688c49e7d0340aed662f079c52174e04ce8fa5349afc9fba2d41dd4c2e0f4"
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
  file_path_from_root: "docs\channels\doctrine\PDO_CONVERSION_DOCTRINE.md"
  file_hash: "0042cd4873004093580724ee59b9daeff07c36cf3b62788bb13d162edebd5bc7"
  file_path_from_root: "docs\channels\doctrine\PDO_CONVERSION_DOCTRINE.md"
  file_hash: "9c21a834a29ea685408fafcef89482bb2eeea829a68751e8363db1c70234a72b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for PDO_CONVERSION_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "pdo_conversion_doctrinemd"]
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
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "FF0000"
  message: Created PDO_CONVERSION_DOCTRINE.md with mandatory rules for converting mysqli SQL calls to the custom PDO_DB class. Must use custom class methods only, convert all SQL to parameterized queries, preserve legacy behavior exactly. This doctrine is mandatory.
tags:
  categories: ["documentation", "doctrine", "pdo", "refactoring", "cursor"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
in_this_file_we_have:
  - Use Custom PDO_DB Class Only
  - Remove All mysqli Functions
  - Convert All SQL to Parameterized Queries
  - Follow SQL Rewrite Doctrine for Table/Column Names
  - Preserve Legacy Behavior Exactly
  - No Exceptions or Try/Catch Blocks
  - No Fetch Modes or PDO Constants
  - Don't Change How Results Are Used
  - Don't Change INSERT/UPDATE/DELETE Logic
  - Ask for Clarification if PDO_DB Method Is Unknown
file:
  title: "PDO Conversion Doctrine"
  description: "Mandatory rules Cursor must follow when converting mysqli SQL calls to the custom PDO_DB class in Lupopedia 3.0.0"
  version: "3.0.0"
  status: published
  author: "Captain Wolfie"
---

# ⭐ **PDO CONVERSION DOCTRINE (Lupopedia 3.0.1)**
### **Mandatory Rules Cursor Must Follow When Converting mysqli SQL Calls to the Custom PDO Class**

This doctrine is **mandatory**. Cursor must follow it for every mysqli → PDO conversion.

**⚠️ IMPORTANT:** All table names must use `LUPO_PREFIX` from `lupopedia-config.php`. See [CONFIGURATION_DOCTRINE.md](CONFIGURATION_DOCTRINE.md) for configuration rules.

---

## **🔵 1. Cursor Must Use the User's Custom PDO_DB Class — Not Raw PDO**

**Cursor must not generate:**

- `new PDO(...)`
- `$pdo->prepare()`
- `$pdo->query()`
- `$pdo->exec()`
- `$pdo->setAttribute()`
- PDO exceptions
- PDO fetch modes

**Cursor must use only the methods defined in the custom class:**

- ✔ `$db->query($sql, $params = [])`
- ✔ `$db->fetch($sql, $params = [])`
- ✔ `$db->fetchAll($sql, $params = [])`
- ✔ `$db->execute($sql, $params = [])`
- ✔ `$db->lastInsertId()` (if needed)
- ✔ `$db->errorInfo()` (only if needed)

**(If the class uses slightly different names, those will be locked in.)**

**Cursor must not invent new methods.**

**Critical Rule:**
- Use only the custom PDO_DB class methods
- Never use raw PDO
- Never invent new methods

---

## **🔵 2. All mysqli_ Functions Must Be Removed and Replaced**

**Cursor must rewrite:**

| Legacy mysqli | New PDO_DB |
|---------------|------------|
| `mysqli_query($link, $sql)` | `$db->query($sql)` |
| `mysqli_fetch_assoc($result)` | `$db->fetch($sql)` or `$db->fetchAll($sql)` |
| `mysqli_num_rows($result)` | `count($db->fetchAll($sql))` |
| `mysqli_real_escape_string()` | Never used — parameters must be bound |
| `mysqli_insert_id($link)` | `$db->lastInsertId()` |
| `mysqli_error($link)` | `$db->errorInfo()` (only if needed) |

**Cursor must not leave any mysqli calls behind.**

**Critical Rule:**
- Every mysqli function must be replaced
- No mysqli calls should remain
- Use the PDO_DB class methods exclusively

---

## **🔵 3. Cursor Must Convert All SQL to Parameterized Queries**

**Cursor must rewrite:**

```php
$sql = "SELECT * FROM users WHERE id = $id";
```

**into:**

```php
$sql = "SELECT * FROM users WHERE id = :id";
$row = $db->fetch($sql, ['id' => $id]);
```

**Rules:**

- All variables must become named parameters
- No string concatenation inside SQL
- No interpolation inside SQL
- No escaping functions

**Cursor must not guess parameter names — they must match the variable names.**

**Critical Rule:**
- All SQL must use named parameters (`:param_name`)
- Never use string concatenation or interpolation
- Never use `mysqli_real_escape_string()` or similar
- Parameter names must match variable names

---

## **🔵 4. Cursor Must Follow the SQL Rewrite Doctrine for Table/Column Names**

**When converting SQL:**

- table names must be rewritten using the refactor TOON file (see [TOON_DOCTRINE.md](TOON_DOCTRINE.md))
- column names must be rewritten using the refactor TOON file
- **all column names must be verified against toon files** (`lupo-database/lupopedia/toon/{new_table_name}.toon` - TOON format)
- transforms must be applied
- dropped columns must be removed
- added columns must be included only on INSERT

**All 145 tables have toon files in `lupo-database/lupopedia/toon/`.**

**Cursor must not rewrite SQL based on assumptions.**

**Critical Rule:**
- See [SQL_REWRITE_DOCTRINE.md](SQL_REWRITE_DOCTRINE.md)
- See [TOON_DOCTRINE.md](TOON_DOCTRINE.md) for TOON format rules
- All table and column rewrites must follow the refactor TOON files
- **All column names must be verified against toon files (TOON format)**
- **Never guess column names — all 145 tables have toon files**
- **Never convert TOON to JSON — work with TOON directly**
- Never guess mappings

---

## **🔵 5. Cursor Must Preserve Legacy Behavior Exactly**

**Cursor must not:**

- change LIMIT logic
- change ORDER BY logic
- change WHERE logic
- change grouping
- change NULL handling
- change boolean logic
- change comparison operators

**Only the SQL call mechanism changes — not the logic.**

**Critical Rule:**
- Preserve all SQL logic exactly
- Only change how the SQL is executed
- Don't "improve" the SQL logic

---

## **🔵 6. Cursor Must Not Introduce Exceptions or Try/Catch Blocks**

**The PDO wrapper handles errors internally.**

**Cursor must not add:**

- `try {}`
- `catch (PDOException $e)`
- `$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)`

**Unless explicitly instructed.**

**Critical Rule:**
- The PDO_DB class handles errors internally
- Don't add exception handling
- Don't change error handling behavior

---

## **🔵 7. Cursor Must Not Introduce Fetch Modes or PDO Constants**

**Cursor must not generate:**

- `PDO::FETCH_ASSOC`
- `PDO::FETCH_OBJ`
- `PDO::FETCH_COLUMN`

**The wrapper abstracts that away.**

**Critical Rule:**
- The PDO_DB class handles fetch modes internally
- Don't specify fetch modes
- Don't use PDO constants

---

## **🔵 8. Cursor Must Not Change How Results Are Used**

**If legacy code does:**

```php
while ($row = mysqli_fetch_assoc($result)) {
    ...
}
```

**Cursor must rewrite it as:**

```php
$rows = $db->fetchAll($sql, $params);
foreach ($rows as $row) {
    ...
}
```

**Behavior must remain identical.**

**Critical Rule:**
- Convert while loops to foreach loops
- Preserve the exact iteration behavior
- Don't change how results are processed

---

## **🔵 9. Cursor Must Not Change INSERT/UPDATE/DELETE Logic**

**Cursor must rewrite:**

```php
mysqli_query($link, $sql);
```

**into:**

```php
$db->execute($sql, $params);
```

**Cursor must not:**

- add RETURNING clauses
- add `lastInsertId` unless needed
- add transactions
- add error handling

**Unless explicitly instructed.**

**Critical Rule:**
- Use `$db->execute()` for INSERT/UPDATE/DELETE
- Don't add features that weren't in the original code
- Only get `lastInsertId()` if the original code used `mysqli_insert_id()`

---

## **🔵 10. Cursor Must Ask for Clarification if the PDO_DB Method Is Unknown**

**If Cursor encounters a pattern it cannot map to:**

- `query()`
- `fetch()`
- `fetchAll()`
- `execute()`

**…it must ask:**

> "Which PDO_DB method should be used for this query?"

**Cursor must not guess.**

**Critical Rule:**
- When in doubt, ask
- Don't make assumptions about which method to use
- Don't invent new methods

---

## **⭐ Summary**

**This doctrine is mandatory.**

**Cursor must follow it for every mysqli → PDO conversion.**

**Key Principles:**
1. **Use custom PDO_DB class only** — Never use raw PDO
2. **Remove all mysqli functions** — Replace with PDO_DB methods
3. **Convert to parameterized queries** — All variables become named parameters
4. **Follow SQL Rewrite Doctrine** — Table/column names from refactor JSON
5. **Preserve legacy behavior exactly** — Only change execution mechanism
6. **No exceptions or try/catch** — PDO wrapper handles errors
7. **No fetch modes or PDO constants** — Wrapper abstracts that away
8. **Don't change result usage** — Convert while loops to foreach
9. **Don't change INSERT/UPDATE/DELETE logic** — Use `execute()` method
10. **Ask, don't guess** — When in doubt, ask for clarification

**This is how mysqli code is converted to PDO_DB in Lupopedia 3.0.0.**

---

*Last Updated: January 2026*  
*Version: 3.0.0*  
*Author: Captain Wolfie*
