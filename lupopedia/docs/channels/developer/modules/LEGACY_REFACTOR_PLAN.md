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
  mood_RGB: "0088FF"
  message: Created LEGACY_REFACTOR_PLAN.md with comprehensive plan for refactoring legacy Crafty Syntax files from legacy/craftysyntax/ to lupopedia/. Includes file mapping, conversion rules, and step-by-step process.
tags:
  categories: ["documentation", "refactoring", "planning"]
  collections: ["core-docs", "refactoring"]
  channels: ["dev"]
in_this_file_we_have:
  - Overview
  - Source and Destination Structure
  - Refactoring Rules
  - File Mapping Strategy
  - Conversion Process
  - SQL Conversion Rules
  - PHP Conversion Rules
  - File Organization
  - Step-by-Step Refactoring Process
  - Testing and Validation
file:
  title: "Legacy Crafty Syntax Refactoring Plan"
  description: "Comprehensive plan for refactoring legacy Crafty Syntax files from legacy/craftysyntax/ to lupopedia/ following Lupopedia 4.0.0 doctrine"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# â­ **LEGACY CRAFTY SYNTAX REFACTORING PLAN**
### **Converting Files from `legacy/craftysyntax/` to `lupopedia/`**

This document provides a comprehensive plan for refactoring legacy Crafty Syntax files into Lupopedia 4.0.0.

---

## **ðŸŸ¦ 1. Overview**

### **1.1 Purpose**

Refactor all PHP files from `legacy/craftysyntax/` into the Lupopedia 4.0.0 structure in `lupopedia/`, following all Lupopedia doctrines:

- [CURSOR_REFACTOR_DOCTRINE.md](CURSOR_REFACTOR_DOCTRINE.md)
- [SQL_REWRITE_DOCTRINE.md](SQL_REWRITE_DOCTRINE.md)
- [PDO_CONVERSION_DOCTRINE.md](PDO_CONVERSION_DOCTRINE.md)
- [TOON_DOCTRINE.md](../../doctrine/TOON_DOCTRINE.md)

### **1.2 Scope**

**Source:** `legacy/craftysyntax/` (237+ PHP files)  
**Destination:** `lupopedia/` (organized by module/function)

### **1.3 Principles**

- Preserve all legacy behavior exactly
- Modernize syntax, not logic
- Follow Lupopedia doctrine strictly
- Use TOON files as source of truth
- Never guess column names or table mappings

---

## **ðŸŸ¦ 2. Source and Destination Structure**

### **2.1 Source Structure**

```
legacy/craftysyntax/
â”œâ”€â”€ admin_*.php          (Admin interface files)
â”œâ”€â”€ user_*.php           (User-facing files)
â”œâ”€â”€ data_*.php           (Data management files)
â”œâ”€â”€ class/               (Class files)
â”œâ”€â”€ functions/            (Function libraries)
â”œâ”€â”€ lang/                (Language files)
â”œâ”€â”€ themes/              (Theme files)
â”œâ”€â”€ images/              (Image assets)
â”œâ”€â”€ javascript/          (JavaScript files)
â””â”€â”€ ...                  (Other files)
```

### **2.2 Destination Structure**

```
lupopedia/
â”œâ”€â”€ modules/
â”‚   â””â”€â”€ craftysyntax/    (Crafty Syntax module files)
â”‚       â”œâ”€â”€ admin/       (Admin interface)
â”‚       â”œâ”€â”€ user/        (User-facing)
â”‚       â”œâ”€â”€ api/         (API endpoints)
â”‚       â””â”€â”€ includes/    (Shared includes)
â”œâ”€â”€ lupo-includes/       (Shared classes and functions)
â”œâ”€â”€ lupo-content/        (Content and assets)
â””â”€â”€ ...                  (Other Lupopedia files)
```

---

## **ðŸŸ¦ 3. Refactoring Rules**

### **3.1 Mandatory Doctrines**

Cursor must follow:

1. **CURSOR_REFACTOR_DOCTRINE.md** â€” Preserve logic, no frameworks, no modern PHP features
2. **SQL_REWRITE_DOCTRINE.md** â€” Use refactor TOON files, verify against toon data files
3. **SQL_REFACTOR_MAPPING_DOCTRINE.md** â€” Focused mapping rules for SQL refactoring (load TOON file, replace table/column names, apply transforms)
4. **PDO_CONVERSION_DOCTRINE.md** â€” Convert mysqli to custom PDO_DB class
5. **TOON_DOCTRINE.md** â€” Work with TOON format, never convert to JSON
6. **ARCHITECTURE_SYNC.md** â€” Follow Lupopedia architecture and timestamp doctrine

### **3.2 Legacy Typos Must Be Preserved**

**Critical Rule:**
- Legacy code from 25 years ago may have typos in column names (e.g., "visiblity" instead of "visibility")
- These typos are preserved in the database schema
- **Never "correct" typos** â€” use exact column names from toon files
- The toon files in `database/toon_data/` contain the exact column names as they exist in the database

---

## **ðŸŸ¦ 4. File Mapping Strategy**

### **4.1 Admin Files**

**Source:** `legacy/craftysyntax/admin_*.php`  
**Destination:** `lupopedia/modules/craftysyntax/admin/`

**Examples:**
- `admin.php` â†’ `modules/craftysyntax/admin/index.php`
- `admin_options.php` â†’ `modules/craftysyntax/admin/options.php`
- `admin_users.php` â†’ `modules/craftysyntax/admin/users.php`

### **4.2 User-Facing Files**

**Source:** `legacy/craftysyntax/user_*.php` or public files  
**Destination:** `lupopedia/modules/craftysyntax/user/`

**Examples:**
- `livehelp.php` â†’ `modules/craftysyntax/user/livehelp.php`
- `livehelp_js.php` â†’ `modules/craftysyntax/user/livehelp_js.php`
- `leavemessage.php` â†’ `modules/craftysyntax/user/leavemessage.php`

### **4.3 API Files**

**Source:** `legacy/craftysyntax/*_xmlhttp.php` or `*_refresh.php`  
**Destination:** `lupopedia/modules/craftysyntax/api/`

**Examples:**
- `xmlhttp.php` â†’ `modules/craftysyntax/api/xmlhttp.php`
- `admin_chat_xmlhttp.php` â†’ `modules/craftysyntax/api/admin_chat.php`
- `user_chat_xmlhttp.php` â†’ `modules/craftysyntax/api/user_chat.php`

### **4.4 Class Files**

**Source:** `legacy/craftysyntax/class/*.php`  
**Destination:** `lupopedia/lupo-includes/class-*.php`

**Examples:**
- `class/operator.php` â†’ `lupo-includes/class-operator.php`
- `class/sessionmanager.php` â†’ `lupo-includes/class-session-manager.php`

### **4.5 Function Libraries**

**Source:** `legacy/craftysyntax/functions.php` or `data_*.php`  
**Destination:** `lupopedia/lupo-includes/` or `modules/craftysyntax/includes/`

**Examples:**
- `functions.php` â†’ `lupo-includes/crafty-functions.php`
- `data_functions.php` â†’ `modules/craftysyntax/includes/data-functions.php`

### **4.6 Configuration Files**

**Source:** `legacy/craftysyntax/config.php`  
**Destination:** `lupopedia/lupopedia-config.php` (merged into main config)

---

## **ðŸŸ¦ 5. Conversion Process**

### **5.1 Step 1: Identify File Type**

For each file, determine:
- Is it admin, user, API, class, or function?
- What is its primary purpose?
- What tables does it reference?

### **5.2 Step 2: Load Refactor TOON Files**

For each table referenced:
1. Load `database/refactors/{legacy_table}.json` (TOON format)
2. Load `database/toon_data/{new_table_name}.json` (TOON format)
3. Verify all column names exist in toon files

### **5.3 Step 3: Convert SQL**

For each SQL query:
1. Rewrite table names using refactor TOON file
2. Rewrite column names using refactor TOON file
3. Verify column names against toon data file
4. Convert to parameterized queries
5. Convert mysqli to PDO_DB class

### **5.4 Step 4: Convert PHP**

For each PHP file:
1. Preserve all logic exactly
2. Convert mysqli to PDO_DB
3. Update includes/paths
4. Preserve global variables
5. Preserve session handling
6. Modernize syntax only (not behavior)

### **5.5 Step 5: Update File Structure**

1. Move file to destination directory
2. Update all include paths
3. Update all references to other files
4. Preserve file organization

---

## **ðŸŸ¦ 6. SQL Conversion Rules**

### **6.1 Table Name Conversion**

**Process:**
1. Find legacy table name in SQL
2. Load `database/refactors/{legacy_table}.toon` (TOON format with `.toon` extension, see [TOON_DOCTRINE.md](../../doctrine/TOON_DOCTRINE.md))
3. Use `new_table` from TOON file
4. Apply prefix placeholder if specified

**See [SQL_REFACTOR_MAPPING_DOCTRINE.md](SQL_REFACTOR_MAPPING_DOCTRINE.md) for detailed mapping rules.**

**Example:**
```sql
-- Legacy
FROM livehelp_autoinvite

-- Refactored (from TOON file)
FROM {{prefix}}crafty_auto_invite
```

### **6.2 Column Name Conversion**

**Process:**
1. Find column name in SQL
2. Check `column_map` in refactor TOON file
3. Verify new column name exists in toon data file (`database/toon_data/{new_table_name}.toon`)
4. Use exact column name (preserve typos like "visiblity")

**See [SQL_REFACTOR_MAPPING_DOCTRINE.md](SQL_REFACTOR_MAPPING_DOCTRINE.md) for detailed mapping rules.**

**Example:**
```sql
-- Legacy
SELECT visiblity FROM livehelp_quick

-- Refactored (preserve typo, use new table)
SELECT visiblity FROM {{prefix}}crafty_quick
```

### **6.3 Parameterized Queries**

**Process:**
1. Convert all variables to named parameters
2. Use `:param_name` format
3. Pass parameters array to PDO_DB methods

**Example:**
```php
// Legacy
$sql = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($link, $sql);

// Refactored
$sql = "SELECT * FROM users WHERE id = :id";
$row = $db->fetch($sql, ['id' => $id]);
```

---

## **ðŸŸ¦ 7. PHP Conversion Rules**

### **7.1 Database Connection**

**Legacy:**
```php
$link = mysqli_connect($host, $user, $pass, $dbname);
```

**Refactored:**
```php
require_once __DIR__ . '/../../lupo-includes/class-pdo_db.php';
$db = new PDO_DB($host, $user, $pass, $dbname);
```

### **7.2 Query Execution**

**Legacy:**
```php
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
```

**Refactored:**
```php
$row = $db->fetch($sql, $params);
```

### **7.3 Multiple Rows**

**Legacy:**
```php
while ($row = mysqli_fetch_assoc($result)) {
    // process row
}
```

**Refactored:**
```php
$rows = $db->fetchAll($sql, $params);
foreach ($rows as $row) {
    // process row
}
```

### **7.4 INSERT/UPDATE/DELETE**

**Legacy:**
```php
mysqli_query($link, $sql);
$new_id = mysqli_insert_id($link);
```

**Refactored:**
```php
$db->execute($sql, $params);
$new_id = $db->lastInsertId();
```

---

## **ðŸŸ¦ 8. File Organization**

### **8.1 Directory Structure**

```
lupopedia/
â”œâ”€â”€ modules/
â”‚   â””â”€â”€ craftysyntax/
â”‚       â”œâ”€â”€ admin/
â”‚       â”‚   â”œâ”€â”€ index.php
â”‚       â”‚   â”œâ”€â”€ options.php
â”‚       â”‚   â”œâ”€â”€ users.php
â”‚       â”‚   â””â”€â”€ ...
â”‚       â”œâ”€â”€ user/
â”‚       â”‚   â”œâ”€â”€ livehelp.php
â”‚       â”‚   â”œâ”€â”€ livehelp_js.php
â”‚       â”‚   â””â”€â”€ ...
â”‚       â”œâ”€â”€ api/
â”‚       â”‚   â”œâ”€â”€ xmlhttp.php
â”‚       â”‚   â”œâ”€â”€ admin_chat.php
â”‚       â”‚   â””â”€â”€ ...
â”‚       â””â”€â”€ includes/
â”‚           â”œâ”€â”€ data-functions.php
â”‚           â””â”€â”€ ...
â”œâ”€â”€ lupo-includes/
â”‚   â”œâ”€â”€ class-operator.php
â”‚   â”œâ”€â”€ class-session-manager.php
â”‚   â””â”€â”€ ...
â””â”€â”€ lupo-content/
    â””â”€â”€ craftysyntax/
        â”œâ”€â”€ images/
        â”œâ”€â”€ themes/
        â””â”€â”€ ...
```

### **8.2 Include Path Updates**

**Legacy:**
```php
require_once 'config.php';
require_once 'functions.php';
```

**Refactored:**
```php
require_once __DIR__ . '/../../lupopedia-config.php';
require_once __DIR__ . '/../../lupo-includes/crafty-functions.php';
```

---

## **ðŸŸ¦ 9. Step-by-Step Refactoring Process**

### **9.1 Phase 1: Core Classes**

**Priority:** High  
**Files:**
- `class/operator.php`
- `class/sessionmanager.php`
- `class/browser_info.php`
- Other class files

**Process:**
1. Read legacy class file
2. Convert to `lupo-includes/class-*.php` format
3. Convert mysqli to PDO_DB
4. Preserve all methods and logic
5. Test class functionality

### **9.2 Phase 2: Function Libraries**

**Priority:** High  
**Files:**
- `functions.php`
- `data_functions.php`
- `security_functions.php`

**Process:**
1. Read legacy function file
2. Convert SQL using refactor TOON files
3. Convert mysqli to PDO_DB
4. Preserve all function signatures
5. Test functions

### **9.3 Phase 3: Admin Interface**

**Priority:** Medium  
**Files:**
- `admin.php`
- `admin_*.php`

**Process:**
1. Read legacy admin file
2. Convert SQL using refactor TOON files
3. Convert mysqli to PDO_DB
4. Update include paths
5. Move to `modules/craftysyntax/admin/`
6. Test admin functionality

### **9.4 Phase 4: User Interface**

**Priority:** Medium  
**Files:**
- `livehelp.php`
- `livehelp_js.php`
- `user_*.php`

**Process:**
1. Read legacy user file
2. Convert SQL using refactor TOON files
3. Convert mysqli to PDO_DB
4. Update include paths
5. Move to `modules/craftysyntax/user/`
6. Test user functionality

### **9.5 Phase 5: API Endpoints**

**Priority:** Medium  
**Files:**
- `*_xmlhttp.php`
- `*_refresh.php`

**Process:**
1. Read legacy API file
2. Convert SQL using refactor TOON files
3. Convert mysqli to PDO_DB
4. Update include paths
5. Move to `modules/craftysyntax/api/`
6. Test API endpoints

### **9.6 Phase 6: Supporting Files**

**Priority:** Low  
**Files:**
- Configuration files
- Language files
- Utility files

**Process:**
1. Read legacy file
2. Convert as needed
3. Move to appropriate location
4. Test functionality

---

## **ðŸŸ¦ 10. Testing and Validation**

### **10.1 SQL Validation**

For each converted SQL query:
- Verify table name matches refactor TOON file
- Verify column names match toon data file
- Verify parameters are bound correctly
- Test query execution

### **10.2 PHP Validation**

For each converted PHP file:
- Verify all mysqli calls are replaced
- Verify PDO_DB class is used correctly
- Verify include paths are updated
- Verify logic is preserved
- Test file functionality

### **10.3 Integration Testing**

After refactoring:
- Test admin interface
- Test user interface
- Test API endpoints
- Test database operations
- Verify no behavior changes

---

## **ðŸŸ¦ 11. Critical Reminders**

### **11.1 Never Guess**

- Never guess table names â€” use refactor TOON files
- Never guess column names â€” use toon data files
- Never "correct" typos â€” preserve legacy column names exactly
- Never assume mappings â€” verify against TOON files

### **11.2 Preserve Behavior**

- Preserve all logic exactly
- Preserve all SQL behavior
- Preserve all PHP behavior
- Only modernize syntax, not behavior

### **11.3 Follow Doctrine**

- Follow CURSOR_REFACTOR_DOCTRINE.md
- Follow SQL_REWRITE_DOCTRINE.md
- Follow PDO_CONVERSION_DOCTRINE.md
- Follow TOON_DOCTRINE.md
- Follow ARCHITECTURE_SYNC.md

---

## **â­ Summary**

This plan provides a structured approach to refactoring legacy Crafty Syntax files into Lupopedia 4.0.0.

**Key Principles:**
1. Use TOON files as source of truth
2. Preserve legacy behavior exactly
3. Preserve legacy typos in column names
4. Convert mysqli to PDO_DB
5. Follow all Lupopedia doctrines
6. Test thoroughly after each conversion

**This is how legacy code becomes Lupopedia 4.0.0.**

---

*Last Updated: January 2026*  
*Version: 4.0.0*  
*Author: Captain Wolfie*

