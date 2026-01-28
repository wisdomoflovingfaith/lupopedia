---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - GLOBAL_DEFAULT_STATUS
  - GLOBAL_COLLECTION_CORE_DOCS
  - GLOBAL_COLLECTION_DOCTRINE
  - GLOBAL_CHANNEL_DEV
  - GLOBAL_CHANNEL_PUBLIC
updated: 2026-01-09
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: cursor
  target: @everyone
  message: "MANDATORY: Triggers are FORBIDDEN. All timestamps must be set explicitly in INSERT/UPDATE statements in YMDHIS UTC format."
  mood: "FF0000"
tags:
  categories: ["documentation", "doctrine", "database", "architecture", "mandatory"]
  collections: [GLOBAL_COLLECTION_CORE_DOCS, GLOBAL_COLLECTION_DOCTRINE]
  channels: [GLOBAL_CHANNEL_DEV, GLOBAL_CHANNEL_PUBLIC]
in_this_file_we_have:
  - No Triggers Doctrine (MANDATORY)
  - Why Triggers Are Forbidden
  - Explicit Timestamp Control Requirement
  - Data Merging Requirements
  - anubis Data Repair Requirements
  - IDE Enforcement Rules
  - Summary Doctrine
file:
  title: "No Triggers Doctrine (MANDATORY)"
  description: "Triggers are FORBIDDEN in Lupopedia. All timestamps must be set explicitly in INSERT/UPDATE statements in YMDHIS UTC format. This is mandatory for data merging and historical accuracy."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: GLOBAL_DEFAULT_STATUS
  author: "Captain Wolfie"
---

# ðŸš« **NO TRIGGERS DOCTRINE (MANDATORY)**  
### *Triggers Are Forbidden. All Timestamps Must Be Explicit.*  
### *This Doctrine Is Absolute and Non-Negotiable*

---

## âš ï¸ **MANDATORY RULE: TRIGGERS ARE FORBIDDEN**

**ALL IDEs AND AI AGENTS MUST FOLLOW THIS DOCTRINE ABSOLUTELY.**

Lupopedia **NEVER** uses database triggers.  
This is not optional.  
This is not a suggestion.  
This is a **mandatory architectural requirement**.

---

## **1. Do NOT Generate or Suggest Database Triggers**

Triggers must **NEVER** be created, suggested, or added.

### **Prohibited:**

- `CREATE TRIGGER ...`
- `BEFORE UPDATE` triggers
- `AFTER INSERT` triggers
- Automatic timestamp triggers
- ORM-generated triggers
- "updated_at" auto-update triggers
- Any database-level automatic timestamp modification

### **Why Triggers Are Prohibited:**

**Triggers interfere with:**
- Data repair operations
- Database merging
- Historical accuracy preservation
- anubis orphan resolution
- Federation sync operations
- Import/export workflows
- Legacy data preservation

**Triggers are NOT compatible with Lupopedia's architecture.**

---

## **2. All Timestamps Must Be Set Explicitly in Application Code**

Every `*_ymdhis` field is set **manually** in application code:

- **on INSERT** â€” Set `created_ymdhis` explicitly
- **on UPDATE** â€” Set `updated_ymdhis` explicitly
- **during merges** â€” Preserve original timestamps
- **during repairs** â€” Maintain historical accuracy
- **during federation sync** â€” Keep source timestamps

### **The Application Must Always Control the Timestamp.**

**The database must NEVER mutate it automatically.**

### **Required Format: YMDHIS UTC**

All timestamps must be in `YYYYMMDDHHMMSS` format in UTC timezone, stored as `BIGINT`.

**Example:**
```php
// CORRECT: Explicit timestamp in application code
$now = date('YmdHis'); // Returns: 20260109143000 (UTC)
$db->insert('agents', [
    'agent_key' => 'example',
    'agent_name' => 'Example Agent',
    'created_ymdhis' => $now,  // EXPLICIT
    'updated_ymdhis' => $now,  // EXPLICIT
    'is_deleted' => 0,
    'deleted_ymdhis' => NULL
]);

// CORRECT: Explicit timestamp on UPDATE
$db->update('agents', [
    'agent_name' => 'Updated Name',
    'updated_ymdhis' => date('YmdHis')  // EXPLICIT
], 'agent_id = :id', ['id' => 1]);
```

```sql
-- CORRECT: Explicit timestamp in raw SQL INSERT
INSERT INTO agents 
  (agent_key, agent_name, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES 
  ('example', 'Example Agent', 20260109143000, 20260109143000, 0, NULL);

-- CORRECT: Explicit timestamp in raw SQL UPDATE
UPDATE agents 
SET 
  agent_name = 'Updated Name',
  updated_ymdhis = 20260109143000  -- EXPLICIT UTC YMDHIS
WHERE agent_id = 1;
```

**NEVER do this:**
```sql
-- WRONG: Never use triggers to set timestamps
CREATE TRIGGER agents_updated_at 
BEFORE UPDATE ON agents
FOR EACH ROW
SET NEW.updated_ymdhis = UNIX_TIMESTAMP(NOW());

-- WRONG: Never use database functions to auto-update timestamps
CREATE TABLE agents (
  updated_ymdhis BIGINT DEFAULT UNIX_TIMESTAMP(NOW()) ON UPDATE CURRENT_TIMESTAMP
);

-- WRONG: Never rely on database-level timestamp automation
updated_ymdhis TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```

---

## **3. Triggers Destroy Historical Accuracy**

Triggers overwrite:

- Original creation times
- Original update times
- Migration timestamps
- Imported timestamps
- Repaired timestamps
- Merged timestamps
- Federation sync timestamps

**This makes it impossible to:**

- Merge rows correctly
- Dedupe records accurately
- Compare histories between databases
- Determine which row is actually newer
- Preserve legacy data timestamps
- Reconcile federation conflicts
- Track repair operations accurately

**Triggers corrupt the very data you need to fix.**

---

## **4. Merging Databases Requires Stable Timestamps**

Lupopedia must be able to merge databases with:

- Different ID ranges
- Different update histories
- Orphaned rows
- Conflicting parents
- Inconsistent timestamps from different sources
- Partial imports
- Legacy data migrations

**Example Merge Scenario:**

```php
// Merging two databases with conflicting update times
$row_from_db1 = [
    'agent_id' => 5,
    'agent_name' => 'WOLFIE',
    'created_ymdhis' => 20250101000000,  // Original creation
    'updated_ymdhis' => 20250115120000   // Last update in DB1
];

$row_from_db2 = [
    'agent_id' => 5,
    'agent_name' => 'WOLFIE',
    'created_ymdhis' => 20250101000000,  // Same original creation
    'updated_ymdhis' => 20250120180000   // Last update in DB2 (newer!)
];

// CORRECT: Compare timestamps and merge intelligently
if ($row_from_db2['updated_ymdhis'] > $row_from_db1['updated_ymdhis']) {
    // DB2 has newer data, preserve its timestamp
    $merged_row = $row_from_db2;
} else {
    // DB1 has newer or equal data
    $merged_row = $row_from_db1;
}

// Insert merged row with PRESERVED original timestamp
$db->insert('agents', [
    'agent_id' => 5,
    'agent_name' => $merged_row['agent_name'],
    'created_ymdhis' => 20250101000000,  // Preserved original
    'updated_ymdhis' => $merged_row['updated_ymdhis'],  // Preserved newer timestamp
    'is_deleted' => 0,
    'deleted_ymdhis' => NULL
]);
```

**If triggers existed**, they would rewrite timestamps during merges, destroying:

- The original `updated_ymdhis` from the source database
- The original `created_ymdhis` from the source database
- The original deletion times
- The historical accuracy needed for conflict resolution

**This makes correct merging impossible.**

---

## **5. anubis Must Be Free to Repair Data**

anubis (the custodial intelligence agent) will receive:

- Orphaned rows
- Deleted parents
- Mismatched timestamps
- Conflicting histories
- Partial imports
- Broken relationships
- Federation conflicts

**anubis must be able to:**

- Preserve original timestamps during repairs
- Compare timestamps across nodes
- Choose the newest row based on actual timestamps
- Merge nulls intelligently
- Reassign parents without timestamp corruption
- Rebuild relationships while maintaining history
- Reconstruct timelines from original timestamps

**Example anubis Repair:**

```php
// anubis finds orphaned agent record with original timestamp
$orphan = [
    'agent_id' => 42,
    'agent_name' => 'Orphaned Agent',
    'created_ymdhis' => 20250105100000,  // Original creation time (PRESERVED)
    'updated_ymdhis' => 20250106150000,  // Last update time (PRESERVED)
    'parent_agent_id' => 999  // Orphaned reference (parent deleted)
];

// anubis repairs by reassigning parent, PRESERVING original timestamps
$anubis->repairOrphan('agents', 42, [
    'parent_agent_id' => 1,  // Reassigned to valid parent
    // created_ymdhis remains: 20250105100000 (PRESERVED)
    // updated_ymdhis remains: 20250106150000 (PRESERVED)
    'updated_ymdhis' => $orphan['updated_ymdhis']  // EXPLICIT, preserving original
]);

// If triggers existed, they would corrupt this repair by:
// - Overwriting updated_ymdhis with current time
// - Destroying the original update history
// - Making it impossible to reconstruct what actually happened
```

**Triggers would break every one of these operations.**

---

## **6. Federation Sync Requires Original Timestamps**

Federation sync operations must preserve original timestamps from source nodes.

**Example Federation Sync:**

```php
// Syncing agent from remote node (lupopedia.com)
$remote_agent = [
    'agent_id' => 7,
    'agent_key' => 'wolfena',
    'agent_name' => 'WOLFENA',
    'created_ymdhis' => 20250102000000,  // Original creation on remote node
    'updated_ymdhis' => 20250108120000   // Last update on remote node
];

// CORRECT: Insert with PRESERVED remote timestamps
$db->insert('agents', [
    'agent_id' => 7,
    'agent_key' => $remote_agent['agent_key'],
    'agent_name' => $remote_agent['agent_name'],
    'created_ymdhis' => $remote_agent['created_ymdhis'],  // Preserved from remote
    'updated_ymdhis' => $remote_agent['updated_ymdhis'],  // Preserved from remote
    'is_deleted' => 0,
    'deleted_ymdhis' => NULL
]);

// If triggers existed, they would corrupt federation sync by:
// - Setting created_ymdhis to current time (wrong!)
// - Setting updated_ymdhis to current time (wrong!)
// - Destroying the actual history from the remote node
```

**Triggers make federation sync impossible.**

---

## **7. IDE Enforcement Rules**

**ALL IDEs MUST TREAT THIS DOCTRINE AS ABSOLUTE.**

### **Do NOT:**

- âŒ Suggest triggers
- âŒ Auto-generate triggers
- âŒ "Optimize" schemas by adding triggers
- âŒ Rewrite migrations to include triggers
- âŒ Enforce automatic timestamp updates
- âŒ Use ORM features that generate triggers
- âŒ Add database-level timestamp automation

### **DO:**

- âœ… Always set timestamps explicitly in INSERT statements
- âœ… Always set timestamps explicitly in UPDATE statements
- âœ… Always use YMDHIS UTC format (BIGINT)
- âœ… Preserve original timestamps during merges
- âœ… Preserve original timestamps during repairs
- âœ… Preserve original timestamps during federation sync
- âœ… Let the application layer control all timestamps

**Lupopedia uses application-level timestamp control, not database-level triggers.**

---

## **8. Code Examples**

### **âœ… CORRECT: Explicit Timestamps in PHP**

```php
<?php
// Helper function to get current UTC timestamp in YMDHIS format
function get_utc_ymdhis() {
    return gmdate('YmdHis'); // Always UTC
}

// INSERT with explicit timestamps
$db->insert('agents', [
    'agent_key' => 'example',
    'agent_name' => 'Example Agent',
    'created_ymdhis' => get_utc_ymdhis(),  // EXPLICIT
    'updated_ymdhis' => get_utc_ymdhis(),  // EXPLICIT
    'is_deleted' => 0,
    'deleted_ymdhis' => NULL
]);

// UPDATE with explicit timestamp
$db->update('agents', [
    'agent_name' => 'Updated Name',
    'updated_ymdhis' => get_utc_ymdhis()  // EXPLICIT
], 'agent_id = :id', ['id' => 1]);
```

### **âœ… CORRECT: Explicit Timestamps in Raw SQL**

```sql
-- INSERT with explicit UTC YMDHIS timestamps
INSERT INTO agents 
  (agent_key, agent_name, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES 
  ('example', 'Example Agent', 20260109143000, 20260109143000, 0, NULL);

-- UPDATE with explicit UTC YMDHIS timestamp
UPDATE agents 
SET 
  agent_name = 'Updated Name',
  updated_ymdhis = 20260109143000  -- EXPLICIT UTC YMDHIS
WHERE agent_id = 1;
```

### **âœ… CORRECT: Preserving Timestamps During Merges**

```php
// Merging two rows while preserving original timestamps
$row1 = ['agent_id' => 5, 'updated_ymdhis' => 20250115120000];
$row2 = ['agent_id' => 5, 'updated_ymdhis' => 20250120180000];

// Choose newer row, preserve its timestamp
$merged = ($row2['updated_ymdhis'] > $row1['updated_ymdhis']) ? $row2 : $row1;

$db->insert('agents', [
    'agent_id' => 5,
    'updated_ymdhis' => $merged['updated_ymdhis']  // PRESERVED original timestamp
]);
```

### **âŒ WRONG: Database Triggers (FORBIDDEN)**

```sql
-- NEVER DO THIS
CREATE TRIGGER agents_updated_at 
BEFORE UPDATE ON agents
FOR EACH ROW
SET NEW.updated_ymdhis = UNIX_TIMESTAMP(NOW());

-- NEVER DO THIS
CREATE TRIGGER agents_created_at
BEFORE INSERT ON agents
FOR EACH ROW
SET NEW.created_ymdhis = UNIX_TIMESTAMP(NOW());

-- NEVER DO THIS
ALTER TABLE agents 
MODIFY COLUMN updated_ymdhis BIGINT DEFAULT UNIX_TIMESTAMP(NOW()) 
  ON UPDATE UNIX_TIMESTAMP(NOW());
```

### **âŒ WRONG: ORM Auto-Timestamps (FORBIDDEN)**

```php
// NEVER use ORM features that auto-generate triggers
// Example (pseudo-code - this pattern is FORBIDDEN):
class Agent extends Model {
    protected $timestamps = true;  // âŒ FORBIDDEN
    protected $autoUpdate = ['updated_ymdhis'];  // âŒ FORBIDDEN
}
```

---

## **9. Summary Doctrine**

```
TRIGGERS ARE FORBIDDEN IN LUPOPEDIA (MANDATORY).

Reasons:
- Interfere with data merging
- Destroy historical accuracy
- Break anubis repair operations
- Break federation sync
- Corrupt imported timestamps
- Make conflict resolution impossible
- Overwrite original timestamps during repairs

ALL timestamps must be:
- Set EXPLICITLY in INSERT statements
- Set EXPLICITLY in UPDATE statements
- In YMDHIS UTC format (BIGINT)
- Controlled by application code
- Preserved during merges
- Preserved during repairs
- Preserved during federation sync

The database must NEVER mutate timestamps automatically.
The application must ALWAYS control timestamps.
Triggers must NEVER exist.
```

---

## **10. Final Note for AI Tools and IDEs**

**This doctrine is MANDATORY and NON-NEGOTIABLE.**

Any AI tool (Cursor, Copilot, DeepSeek, Claude, Gemini, Grok, Windsurf, Cursor Auto, or any other IDE/AI agent) must follow this rule:

> **NEVER add triggers.  
> NEVER suggest triggers.  
> NEVER generate triggers.  
> NEVER use database-level timestamp automation.  
> ALWAYS set timestamps explicitly in application code.  
> ALWAYS use YMDHIS UTC format.  
> ALWAYS preserve original timestamps during merges and repairs.**

**This is not optional.**  
**This is not a suggestion.**  
**This is a mandatory architectural requirement.**

All timestamp control is handled by:

- **Application code** (PHP, Python, etc.)
- **Explicit INSERT/UPDATE statements**
- **YMDHIS UTC format (BIGINT)**
- **anubis repair operations**
- **Federation sync logic**
- **Merge algorithms**

**Never by database triggers.**

This is how Lupopedia maintains historical accuracy, enables data merging, and supports federation.

---

## **Related Doctrines**

This doctrine works in conjunction with:

- **[NO_FOREIGN_KEYS_DOCTRINE.md](NO_FOREIGN_KEYS_DOCTRINE.md)** â€” Database constraints are forbidden; application logic enforces correctness
- **[NO_STORED_PROCEDURES_DOCTRINE.md](NO_STORED_PROCEDURES_DOCTRINE.md)** â€” Database logic is forbidden; all computation in application layer
- **[WOLFIE_TIMESTAMP_DOCTRINE.md](../developer/dev/WOLFIE_TIMESTAMP_DOCTRINE.md)** â€” Explicit timestamp format requirements (BIGINT UTC YYYYMMDDHHIISS)
- **[DATABASE_PHILOSOPHY.md](../architecture/DATABASE_PHILOSOPHY.md)** â€” Application logic first, database logic second
- **[anubis_DOCTRINE.md](anubis_DOCTRINE.md)** â€” Custodial intelligence handles data repair without triggers

---- [anubis_DOCTRINE.md](anubis_DOCTRINE.md) â€” How anubis handles data repair
- [PHILOSOPHY.md](../../PHILOSOPHY.md) â€” Overall database philosophy

---

