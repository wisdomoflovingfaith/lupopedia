---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: Kiro
  target: @everyone
  message: "Created CLASS_HEADER_COMMENT_DOCTRINE.md defining mandatory comment block format for all AI-generated PHP classes. Follows Captain Wolfie's Crafty Syntax style with comprehensive function lists and usage examples."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "php", "code-standards"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "Class Header Comment Doctrine"
  description: "MANDATORY rules for AI agents when creating PHP class files - comprehensive comment blocks at top of every class"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Class Header Comment Doctrine (MANDATORY)

**Version:** 4.0.15  
**Status:** MANDATORY (NON-NEGOTIABLE)  
**Effective Date:** 2026-01-13

---

## Overview

All AI-generated PHP classes MUST include a comprehensive comment block at the top of the file that documents everything about the class. This follows Captain Wolfie's Crafty Syntax coding style from 2002-2014.

**Critical Principle:** The comment block must tell you everything you need to know about the class without reading the code.

---

## Mandatory Comment Block Structure

Every PHP class file MUST begin with this structure:

```php
<?php
//****************************************************************************************
// Library : [CLASS_NAME]  :  version [X.X.X] ([MM/DD/YYYY])
// Author  : [AUTHOR_NAME] ([WEBSITE/COMPANY])
//======================================================================================
/**
 * [CLASS_DESCRIPTION]
 * 
 * [DETAILED_EXPLANATION]
 * 
 * BASIC USAGE EXAMPLE:
 * <code>
 * [USAGE_EXAMPLE_CODE]
 * </code>
 * 
 * ALTERNATIVE EXAMPLE:
 * <code>
 * [ALTERNATIVE_USAGE_CODE]
 * </code>
 */
//
// CLASS [CLASS_NAME] FUNCTION LIST:
//      function [method1]([params])  - [description]
//      function [method2]([params])  - [description]
//      function [method3]([params])  - [description]
//      [... all public methods listed ...]
//
// PRIVATE/PROTECTED METHODS:
//      function [privateMethod1]([params])  - [description]
//      function [privateMethod2]([params])  - [description]
//
// ORIGINAL CODE:
// ---------------------------------------------------------
// [AUTHOR_NAME]
// [LICENSE]
//
//=====================***  [CLASS_NAME]   ***======================================

class [CLASS_NAME] {
    // Class implementation...
}
?>
```

---

## Section-by-Section Requirements

### 1. Header Banner (MANDATORY)

```php
//****************************************************************************************
// Library : PDO_DB  :  version 1.0.1 (01/13/2026)
// Author  : Captain Wolfie (Lupopedia.com)
//======================================================================================
```

**Rules:**
- Use asterisks for visual separation
- Include class name, version number, date
- Include author name and website/company
- Date format: MM/DD/YYYY
- Use "Library" even for classes (historical consistency)

---

### 2. Class Description Block (MANDATORY)

```php
/**
 * PDO Database Wrapper for Lupopedia
 * 
 * This class provides a thin abstraction layer over PDO that:
 * - Supports multiple database types (MySQL, PostgreSQL, SQLite)
 * - Provides automatic SQL injection protection via parameter binding
 * - Handles errors internally with safe fallback values
 * - Simplifies common database operations (insert, update, delete)
 * - Maintains consistent interface across database types
 * 
 * BASIC USAGE EXAMPLE:
 * <code>
 * $db = new PDO_DB($host, $user, $pass, $dbname);
 * 
 * // Fetch all rows
 * $rows = $db->fetchAll("SELECT * FROM users WHERE status = :status", ['status' => 'active']);
 * foreach ($rows as $row) {
 *     echo $row['username'];
 * }
 * 
 * // Insert with automatic parameter binding
 * $id = $db->insert('users', [
 *     'username' => $username,
 *     'email' => $email,
 *     'created_ymdhis' => gmdate('YmdHis')
 * ]);
 * </code>
 * 
 * ALTERNATIVE EXAMPLE:
 * <code>
 * $db = new PDO_DB($host, $user, $pass, $dbname, 'pgsql');
 * 
 * // Update with WHERE clause
 * $affected = $db->update('users', 
 *     ['status' => 'inactive'],
 *     'last_login_ymdhis < :cutoff',
 *     ['cutoff' => 20250101000000]
 * );
 * 
 * // Delete with parameters
 * $deleted = $db->delete('sessions', 'expires_ymdhis < :now', ['now' => gmdate('YmdHis')]);
 * </code>
 */
```

**Rules:**
- Start with one-line summary
- Explain what the class does
- List key features/capabilities
- Provide at least TWO usage examples
- Use `<code>` tags for examples
- Show realistic, working code
- Include parameter binding examples
- Show different use cases

---

### 3. Function List (MANDATORY)

```php
//
// CLASS PDO_DB FUNCTION LIST:
//      function __construct($host, $user, $pass, $dbname, $type)  - Constructor, connects to database
//      function query($sql, $params)                               - Execute query and return PDOStatement
//      function fetchAll($sql, $params)                            - Execute query and return all results
//      function fetchRow($sql, $params)                            - Execute query and return first row
//      function fetchOne($sql, $params)                            - Execute query and return single value
//      function insert($table, $data)                              - Insert data into table
//      function update($table, $data, $where, $whereParams)        - Update records in table
//      function delete($table, $where, $params)                    - Delete records from table
//      function beginTransaction()                                 - Begin a transaction
//      function commit()                                           - Commit a transaction
//      function rollBack()                                         - Rollback a transaction
//      function quote($value)                                      - Quote a string for use in query
//      function quoteIdentifier($identifier)                       - Quote an identifier (table/column name)
//      function getLastError()                                     - Get the last error message
//      function getLastQuery()                                     - Get the last query executed
//      function getPdo()                                           - Get the PDO instance
//
// PRIVATE/PROTECTED METHODS:
//      function connect($host, $user, $pass, $dbname, $type)       - Connect to the database
//      function getDsn($host, $dbname, $type)                      - Get DSN string based on database type
//      function prepareParams($params)                             - Prepare parameters for PDO
//      function quoteIdentifiers($identifiers)                     - Quote multiple identifiers
//
```

**Rules:**
- List ALL public methods first
- Show method signature with parameters
- Include brief description after dash
- Separate private/protected methods in their own section
- Align descriptions for readability
- Use consistent spacing
- List in logical order (constructor first, related methods together)

---

### 4. Footer Block (MANDATORY)

```php
// ORIGINAL CODE:
// ---------------------------------------------------------
// Captain Wolfie (Eric Robin Gerdes)
// Proprietary - All Rights Reserved
//
//=====================***  PDO_DB   ***======================================
```

**Rules:**
- Include author name
- Include license information
- Use asterisks for visual closure
- Match class name in footer

---

## Complete Example

Here's a complete example following all rules:

```php
<?php
//****************************************************************************************
// Library : PDO_DB  :  version 1.0.1 (01/13/2026)
// Author  : Captain Wolfie (Lupopedia.com)
//======================================================================================
/**
 * PDO Database Wrapper for Lupopedia
 * 
 * This class provides a thin abstraction layer over PDO that:
 * - Supports multiple database types (MySQL, PostgreSQL, SQLite)
 * - Provides automatic SQL injection protection via parameter binding
 * - Handles errors internally with safe fallback values
 * - Simplifies common database operations (insert, update, delete)
 * - Maintains consistent interface across database types
 * 
 * BASIC USAGE EXAMPLE:
 * <code>
 * $db = new PDO_DB($host, $user, $pass, $dbname);
 * 
 * // Fetch all rows
 * $rows = $db->fetchAll("SELECT * FROM users WHERE status = :status", ['status' => 'active']);
 * foreach ($rows as $row) {
 *     echo $row['username'];
 * }
 * 
 * // Insert with automatic parameter binding
 * $id = $db->insert('users', [
 *     'username' => $username,
 *     'email' => $email,
 *     'created_ymdhis' => gmdate('YmdHis')
 * ]);
 * </code>
 * 
 * ALTERNATIVE EXAMPLE:
 * <code>
 * $db = new PDO_DB($host, $user, $pass, $dbname, 'pgsql');
 * 
 * // Update with WHERE clause
 * $affected = $db->update('users', 
 *     ['status' => 'inactive'],
 *     'last_login_ymdhis < :cutoff',
 *     ['cutoff' => 20250101000000]
 * );
 * 
 * // Delete with parameters
 * $deleted = $db->delete('sessions', 'expires_ymdhis < :now', ['now' => gmdate('YmdHis')]);
 * </code>
 */
//
// CLASS PDO_DB FUNCTION LIST:
//      function __construct($host, $user, $pass, $dbname, $type)  - Constructor, connects to database
//      function query($sql, $params)                               - Execute query and return PDOStatement
//      function fetchAll($sql, $params)                            - Execute query and return all results
//      function fetchRow($sql, $params)                            - Execute query and return first row
//      function fetchOne($sql, $params)                            - Execute query and return single value
//      function insert($table, $data)                              - Insert data into table
//      function update($table, $data, $where, $whereParams)        - Update records in table
//      function delete($table, $where, $params)                    - Delete records from table
//      function beginTransaction()                                 - Begin a transaction
//      function commit()                                           - Commit a transaction
//      function rollBack()                                         - Rollback a transaction
//      function quote($value)                                      - Quote a string for use in query
//      function quoteIdentifier($identifier)                       - Quote an identifier (table/column name)
//      function getLastError()                                     - Get the last error message
//      function getLastQuery()                                     - Get the last query executed
//      function getPdo()                                           - Get the PDO instance
//
// PRIVATE/PROTECTED METHODS:
//      function connect($host, $user, $pass, $dbname, $type)       - Connect to the database
//      function getDsn($host, $dbname, $type)                      - Get DSN string based on database type
//      function prepareParams($params)                             - Prepare parameters for PDO
//      function quoteIdentifiers($identifiers)                     - Quote multiple identifiers
//
// ORIGINAL CODE:
// ---------------------------------------------------------
// Captain Wolfie (Eric Robin Gerdes)
// Proprietary - All Rights Reserved
//
//=====================***  PDO_DB   ***======================================

class PDO_DB {
    // Class implementation...
}
?>
```

---

## Why This Format

### 1. **Immediate Understanding**
You can read the comment block and know:
- What the class does
- How to use it
- What methods are available
- What parameters they take

### 2. **Notepad++ Friendly**
When editing in Notepad++:
- Easy to scan with Function List
- Visual separators help navigation
- No need to scroll through code
- Quick reference at top

### 3. **Historical Consistency**
Matches your Crafty Syntax style from 2002-2014:
- Same visual format
- Same function list style
- Same example structure
- Familiar and comfortable

### 4. **Self-Documenting**
The comment block IS the documentation:
- No separate docs needed
- Always up-to-date with code
- Lives with the code
- Easy to maintain

### 5. **AI-Friendly**
AI agents can:
- Generate complete blocks
- Update function lists automatically
- Add new examples
- Maintain consistency

---

## Agent Requirements

### When Creating New Classes

AI agents MUST:
1. âœ… Generate complete comment block before class definition
2. âœ… Include all four sections (header, description, function list, footer)
3. âœ… Provide at least TWO usage examples
4. âœ… List ALL public methods in function list
5. âœ… List private/protected methods separately
6. âœ… Use consistent formatting and spacing
7. âœ… Include realistic, working code examples
8. âœ… Show parameter binding in examples

AI agents MUST NOT:
1. âŒ Skip the comment block
2. âŒ Use minimal PHPDoc-only comments
3. âŒ Omit usage examples
4. âŒ Omit function list
5. âŒ Use different formatting
6. âŒ Provide incomplete examples

### When Updating Existing Classes

AI agents MUST:
1. âœ… Update function list when adding/removing methods
2. âœ… Update version number and date
3. âœ… Add new examples if functionality changes significantly
4. âœ… Maintain existing formatting style
5. âœ… Preserve historical information

---

## Validation Checklist

Before submitting a class file, verify:

- [ ] Header banner present with class name, version, date, author
- [ ] Description block explains what class does
- [ ] At least TWO usage examples provided
- [ ] Examples use `<code>` tags
- [ ] Examples show realistic, working code
- [ ] Function list includes ALL public methods
- [ ] Function list shows method signatures
- [ ] Function list includes brief descriptions
- [ ] Private/protected methods listed separately
- [ ] Footer block present with author and license
- [ ] Visual separators (asterisks, equals signs) present
- [ ] Consistent spacing and alignment

---

## Integration with Other Doctrines

This doctrine works with:
- **[PDO_CONVERSION_DOCTRINE.md](PDO_CONVERSION_DOCTRINE.md)** - When converting mysqli to PDO_DB
- **[CURSOR_REFACTOR_DOCTRINE.md](CURSOR_REFACTOR_DOCTRINE.md)** - When refactoring legacy code
- **[CONFIGURATION_DOCTRINE.md](CONFIGURATION_DOCTRINE.md)** - For class file organization
- **[CONTRIBUTOR_TRAINING.md](../developer/dev/CONTRIBUTOR_TRAINING.md)** - For coding standards

---

## Summary

**All AI-generated PHP classes MUST include a comprehensive comment block at the top that documents:**
1. Class name, version, date, author (header banner)
2. What the class does and how to use it (description + examples)
3. Complete list of all methods with signatures and descriptions (function list)
4. Author and license information (footer)

**This is MANDATORY and NON-NEGOTIABLE.**

The comment block must tell you everything you need to know about the class without reading the code - just like your old Crafty Syntax style.

---

**This doctrine is absolute and binding for all AI agents.**

