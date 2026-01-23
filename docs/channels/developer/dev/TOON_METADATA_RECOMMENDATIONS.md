---
wolfie.headers.version: 4.0.2
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @Captain_WOLFIE
  message: "Created TOON_METADATA_RECOMMENDATIONS.md documenting what metadata should be added to TOON files to enable automatic ALTER statement generation without reading the SQL dump file."
  mood: "00FF00"
tags:
  categories: ["documentation", "dev", "toon", "migration"]
  collections: ["dev-docs"]
  channels: ["dev"]
in_this_file_we_have:
  - Current TOON File Structure
  - Missing Metadata for ALTER Statement Generation
  - Recommended TOON File Enhancement
  - Python Script Modifications Needed
  - Benefits of Enhanced TOON Files
sections:
  - title: "Current State"
    anchor: "#current-state"
  - title: "Missing Metadata"
    anchor: "#missing-metadata"
  - title: "Recommended Enhancement"
    anchor: "#recommended-enhancement"
  - title: "Implementation"
    anchor: "#implementation"
file:
  title: "TOON Metadata Recommendations for Migration Support"
  description: "Documentation of what metadata should be added to TOON files to enable automatic ALTER statement generation for UNSIGNED removal and primary key renaming without reading the SQL dump file"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# **TOON Metadata Recommendations for Migration Support**

## **Current TOON File Structure**

Currently, TOON files have:
```json
{
    "table_name": "lupo_agent_registry",
    "fields": [
        "`agent_registry_id` int unsigned NOT NULL auto_increment",
        "`agent_registry_parent_id` bigint COMMENT 'if this is a alias'",
        ...
    ],
    "data": [...]
}
```

**What's Included:**
- ✅ `table_name` — Table name
- ✅ `fields` — Array of column definitions (including UNSIGNED, types, defaults, comments)
- ✅ `data` — Sample data rows

**What's Missing for ALTER Statement Generation:**

---

## **Missing Metadata for ALTER Statement Generation**

To generate ALTER statements automatically from TOON files (without reading the SQL dump), we need:

### **1. Primary Key Identification** ❌ MISSING

**Current State:** Must infer from `auto_increment` in field definition  
**Problem:** Not explicit, and composite PKs aren't identifiable  
**Need:** Explicit primary key column name(s)

**Example from `lupo_anibus_redirects`:**
- Field shows: `"`id` int unsigned NOT NULL auto_increment"`
- Need to know: This is the PRIMARY KEY
- Need to know: Should be `anibus_redirect_id` (singular table name + `_id`)

### **2. Index Definitions** ❌ MISSING

**Current State:** No indexes listed in TOON files  
**Problem:** When renaming a primary key, indexes that reference it may need renaming  
**Need:** List of all indexes with:
- Index name
- Columns in index
- Unique flag
- Index type (BTREE, HASH, etc.)

**Example:**
- Table has index: `idx_anibus_from` on column `from_id`
- If we rename `id` → `anibus_redirect_id`, but `from_id` references the PK, index might need updating

### **3. Unique Constraints** ❌ MISSING

**Current State:** No unique constraints listed  
**Problem:** Unique constraints may reference primary keys or columns that need renaming  
**Need:** List of unique constraints with:
- Constraint name
- Columns in constraint

### **4. Expected Primary Key Name** ❌ MISSING

**Current State:** Must compute from table name manually  
**Problem:** Easy to make mistakes when computing singular + `_id`  
**Need:** Explicit field showing what the PK name SHOULD be per doctrine

**Example:**
- Table: `lupo_agent_registry`
- Current PK: `agent_registry_id` ✓ (correct)
- But: `lupo_anibus_redirects` has PK `id` ✗ (should be `anibus_redirect_id`)

### **5. Foreign Key References** ❌ MISSING (But Not Needed!)

**Current State:** No FK references (Lupopedia doctrine forbids FKs)  
**Status:** ✅ Not needed — Lupopedia doesn't use foreign keys  
**Note:** But we DO need to know which columns in OTHER tables reference this table's PK

---

## **Recommended TOON File Enhancement**

### **Proposed Structure:**

```json
{
    "table_name": "lupo_agent_registry",
    "primary_key": {
        "column_name": "agent_registry_id",
        "expected_name": "agent_registry_id",
        "is_correct": true,
        "needs_rename": false,
        "current_type": "int unsigned",
        "expected_type": "int",
        "needs_unsigned_removal": true
    },
    "indexes": [
        {
            "index_name": "idx_code",
            "columns": ["code"],
            "is_unique": false,
            "index_type": "BTREE"
        }
    ],
    "unique_constraints": [
        {
            "constraint_name": "unique_code",
            "columns": ["code"]
        }
    ],
    "fields": [
        "`agent_registry_id` int unsigned NOT NULL auto_increment",
        ...
    ],
    "unsigned_columns": [
        {
            "column_name": "agent_registry_id",
            "current_type": "int unsigned",
            "expected_type": "int",
            "is_primary_key": true
        },
        {
            "column_name": "created_ymdhis",
            "current_type": "bigint unsigned",
            "expected_type": "bigint",
            "is_primary_key": false
        }
    ],
    "data": [...]
}
```

### **Minimal Enhancement (What's Actually Needed):**

For generating ALTER statements, the MINIMUM needed is:

```json
{
    "table_name": "lupo_anibus_redirects",
    "primary_key": "id",
    "expected_primary_key": "anibus_redirect_id",
    "unsigned_columns": ["id", "from_id", "to_id"],
    "fields": [...],
    "data": [...]
}
```

This minimal enhancement would allow:
1. ✅ Identify which tables need PK renaming (`primary_key` != `expected_primary_key`)
2. ✅ Identify which columns need UNSIGNED removal (`unsigned_columns` array)
3. ✅ Generate ALTER statements directly from TOON files

---

## **Python Script Modifications Needed**

### **1. Add Primary Key Detection**

```python
def get_primary_key(cursor, db_name: str, table_name: str) -> Optional[str]:
    """Get primary key column name from INFORMATION_SCHEMA.KEY_COLUMN_USAGE."""
    sql = """
        SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = %s 
          AND TABLE_NAME = %s
          AND CONSTRAINT_NAME = 'PRIMARY'
        ORDER BY ORDINAL_POSITION
        LIMIT 1
    """
    cursor.execute(sql, (db_name, table_name))
    result = cursor.fetchone()
    return result['COLUMN_NAME'] if result and isinstance(result, dict) else (result[0] if result else None)
```

### **2. Add Expected Primary Key Name Calculation**

```python
def calculate_expected_pk_name(table_name: str) -> str:
    """
    Calculate expected primary key name: singular of table name + _id
    
    Examples:
        lupo_agent_registry → agent_registry_id
        lupo_anibus_redirects → anibus_redirect_id  
        lupo_collection_tabs → collection_tab_id
    """
    # Remove lupo_ prefix if present
    name = table_name.replace('lupo_', '') if table_name.startswith('lupo_') else table_name
    
    # Handle plural to singular (simple cases)
    if name.endswith('s') and not name.endswith('ss'):
        name = name[:-1]
    elif name.endswith('ies'):
        name = name[:-3] + 'y'
    elif name.endswith('es'):
        name = name[:-2]
    
    return f"{name}_id"
```

### **3. Add UNSIGNED Column Detection**

```python
def get_unsigned_columns(cursor, db_name: str, table_name: str) -> List[Dict[str, str]]:
    """Get list of columns with UNSIGNED type."""
    sql = """
        SELECT 
            COLUMN_NAME,
            COLUMN_TYPE,
            CASE 
                WHEN COLUMN_KEY = 'PRI' THEN 1 
                ELSE 0 
            END AS is_primary_key
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = %s 
          AND TABLE_NAME = %s
          AND COLUMN_TYPE LIKE '%unsigned%'
        ORDER BY ORDINAL_POSITION
    """
    cursor.execute(sql, (db_name, table_name))
    results = cursor.fetchall()
    
    unsigned_cols = []
    for row in results:
        if isinstance(row, dict):
            unsigned_cols.append({
                "column_name": row['COLUMN_NAME'],
                "current_type": row['COLUMN_TYPE'],
                "is_primary_key": bool(row['is_primary_key'])
            })
        else:
            unsigned_cols.append({
                "column_name": row[0],
                "current_type": row[1],
                "is_primary_key": bool(row[2])
            })
    
    return unsigned_cols
```

### **4. Add Index Information (Optional but Helpful)**

```python
def get_indexes(cursor, db_name: str, table_name: str) -> List[Dict[str, Any]]:
    """Get list of indexes for the table."""
    sql = """
        SELECT 
            INDEX_NAME,
            GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) AS columns,
            NON_UNIQUE,
            INDEX_TYPE
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = %s 
          AND TABLE_NAME = %s
        GROUP BY INDEX_NAME, NON_UNIQUE, INDEX_TYPE
        ORDER BY INDEX_NAME
    """
    cursor.execute(sql, (db_name, table_name))
    results = cursor.fetchall()
    
    indexes = []
    for row in results:
        if isinstance(row, dict):
            indexes.append({
                "index_name": row['INDEX_NAME'],
                "columns": row['columns'].split(','),
                "is_unique": row['NON_UNIQUE'] == 0,
                "index_type": row['INDEX_TYPE']
            })
        else:
            indexes.append({
                "index_name": row[0],
                "columns": row[1].split(','),
                "is_unique": row[2] == 0,
                "index_type": row[3]
            })
    
    return indexes
```

---

## **Benefits of Enhanced TOON Files**

### **1. Automatic ALTER Statement Generation**

With enhanced TOON files, we can:
- ✅ Generate all ALTER statements directly from TOON files
- ✅ No need to read the SQL dump file
- ✅ Faster processing (TOON files are smaller and structured)
- ✅ More reliable (single source of truth)

### **2. Migration Validation**

Can validate:
- ✅ All primary keys follow naming convention
- ✅ No UNSIGNED columns remain
- ✅ Indexes reference correct column names
- ✅ All tables have expected structure

### **3. Documentation**

Enhanced TOON files become:
- ✅ Better documentation (includes PK info, indexes)
- ✅ Migration reference (shows what needs fixing)
- ✅ Doctrine compliance checker (expected vs actual PK names)

---

## **Recommended Implementation Priority**

### **Phase 1: Minimum Required (High Priority)**

Add to TOON files:
1. `primary_key` — Current PK column name
2. `expected_primary_key` — What PK name should be (singular + `_id`)
3. `unsigned_columns` — Array of column names with UNSIGNED

**Why:** This is enough to generate all ALTER statements for:
- Removing UNSIGNED from all columns
- Renaming primary keys to follow convention

### **Phase 2: Enhanced Metadata (Medium Priority)**

Add to TOON files:
4. `indexes` — List of indexes with names and columns
5. `unique_constraints` — List of unique constraints

**Why:** Needed for:
- Renaming indexes when PKs are renamed
- Verifying no index names conflict after renaming

### **Phase 3: Full Metadata (Low Priority)**

Add to TOON files:
6. `foreign_key_references` — Which tables/columns reference this table (even though we don't use FKs, other tables might reference PK)
7. `table_comment` — Table comment/description
8. `engine` — Storage engine (InnoDB, MyISAM, etc.)
9. `charset` — Character set
10. `collation` — Collation

**Why:** Complete schema documentation, but not required for ALTER statement generation

---

## **Example: Enhanced TOON File**

```json
{
    "table_name": "lupo_anibus_redirects",
    "primary_key": "id",
    "expected_primary_key": "anibus_redirect_id",
    "primary_key_needs_rename": true,
    "unsigned_columns": [
        {
            "column_name": "id",
            "current_type": "int unsigned",
            "expected_type": "int",
            "is_primary_key": true
        },
        {
            "column_name": "from_id",
            "current_type": "bigint unsigned",
            "expected_type": "bigint",
            "is_primary_key": false
        },
        {
            "column_name": "to_id",
            "current_type": "bigint unsigned",
            "expected_type": "bigint",
            "is_primary_key": false
        }
    ],
    "indexes": [],
    "fields": [
        "`id` int unsigned NOT NULL auto_increment",
        "`from_id` bigint unsigned NOT NULL",
        "`to_id` bigint unsigned NOT NULL",
        "`table_name` varchar(128) NOT NULL",
        "`reason` varchar(128) NOT NULL",
        "`created_ymdhis` bigint NOT NULL"
    ],
    "data": []
}
```

With this structure, generating ALTER statements becomes:

```python
# Pseudo-code for ALTER generation
if toon['primary_key_needs_rename']:
    print(f"ALTER TABLE `{toon['table_name']}`")
    print(f"  CHANGE COLUMN `{toon['primary_key']}` `{toon['expected_primary_key']}` ...")
    print(f"  DROP PRIMARY KEY,")
    print(f"  ADD PRIMARY KEY (`{toon['expected_primary_key']}`);")

for col in toon['unsigned_columns']:
    if not col['is_primary_key']:  # PK handled above
        print(f"ALTER TABLE `{toon['table_name']}`")
        print(f"  MODIFY COLUMN `{col['column_name']}` {col['expected_type']} ...;")
```

---

## **Summary**

**What's Missing in TOON Files:**
1. ❌ Primary key identification (explicit, not inferred)
2. ❌ Expected primary key name (computed from table name)
3. ❌ UNSIGNED column list (for easy ALTER generation)
4. ❌ Index definitions (for index renaming)
5. ❌ Unique constraints (for constraint verification)

**Minimum Needed for ALTER Generation:**
- `primary_key` — Current PK column name
- `expected_primary_key` — What PK should be
- `unsigned_columns` — Array of UNSIGNED columns with their expected types

**Recommended Action:**
Update `generate_toon_files.py` to:
1. Query `INFORMATION_SCHEMA.KEY_COLUMN_USAGE` for primary key
2. Query `INFORMATION_SCHEMA.COLUMNS` for UNSIGNED columns  
3. Calculate expected PK name from table name
4. Add these fields to TOON file output

This would enable automatic ALTER statement generation from TOON files without reading the SQL dump.
