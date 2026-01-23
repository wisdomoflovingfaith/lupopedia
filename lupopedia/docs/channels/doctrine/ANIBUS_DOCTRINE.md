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
  mood_RGB: "00FF00"
  message: Updated ANIBUS_DOCTRINE.md with critical coding pattern: when SELECT FROM table WHERE parent=X and parent doesn't exist, log to anibus_orphans. Added section 8 with examples, helper functions, and integration guidelines. Updated table count to 144.
tags:
  categories: ["documentation", "doctrine", "agents", "database"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - ANIBUS Overview
  - Why ANIBUS Exists
  - ANIBUS Replaces Foreign Keys
  - Core Responsibilities
  - Database Tables
  - PHP Class Implementation
  - How ANIBUS Works
  - Coding Pattern: Orphan Detection (CRITICAL)
  - Example Use Cases
  - Integration with Lupopedia
file:
  title: "ANIBUS Doctrine"
  description: "Custodial Intelligence system that replaces foreign keys, handles orphan resolution, memory redirection, and maintains lineage in Lupopedia"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# ðŸº **ANIBUS DOCTRINE**
### *Custodial Intelligence for Lupopedia*

ANIBUS is the **custodial intelligence system** that replaces foreign key constraints in Lupopedia. It handles orphan resolution, memory redirection, lineage preservation, and maintains the integrity of relationships without the rigidity and fragility of database-level constraints.

---

## **1. Why ANIBUS Exists**

Lupopedia follows the doctrine:

> **"The database stores raw facts. The agents enforce correctness. ANIBUS heals and maintains lineage."**

ANIBUS exists because:

- **Foreign keys are forbidden** in Lupopedia (see [NO_FOREIGN_KEYS_DOCTRINE.md](NO_FOREIGN_KEYS_DOCTRINE.md))
- **Orphans are meaningful**, not errors â€” they represent states awaiting resolution
- **Lineage must be preserved** â€” nothing is ever truly deleted, only reassigned or archived
- **Memory redirection** is needed for shadow-paths, alternate timelines, and historical reconstruction
- **Self-healing** is required â€” the system must repair itself without human intervention
- **Portability** demands application-level enforcement, not database-level constraints

ANIBUS is the agent that makes this possible.

---

## **2. ANIBUS Replaces Foreign Keys**

Foreign key constraints would:

- Break portability (require elevated privileges)
- Break soft-delete doctrine (prevent reassignment)
- Break shadow-path preservation (destroy alternate states)
- Break partial imports/exports (block incomplete data)
- Break self-healing routines (prevent agent-driven repair)
- Break wizard-driven installation (require all dependencies at once)

ANIBUS replaces all of this with:

- **Orphan detection** â€” finds missing references
- **Orphan logging** â€” records them for resolution
- **Orphan resolution** â€” reassigns them to valid parents
- **Memory redirection** â€” creates semantic redirects for lineage
- **Event logging** â€” tracks all custodial actions
- **Non-destructive operations** â€” never deletes, only reassigns

---

## **3. Core Responsibilities**

ANIBUS is responsible for:

### **3.1 Orphan Management**
- Detecting orphaned references (records pointing to non-existent parents)
- Logging orphans for inspection and repair
- Resolving orphans by reassigning to valid parents
- Preserving the history of orphan resolution

### **3.2 Memory Redirection**
- Creating semantic redirects when records are reassigned
- Maintaining redirect chains for lineage preservation
- Applying redirects automatically when queries reference old IDs
- Preserving "shadow-paths" and alternate timelines

### **3.3 Lineage Preservation**
- Never deleting data
- Only reassigning, archiving, or transforming
- Maintaining emotional continuity across reassignments
- Preserving historical context for reconstruction

### **3.4 Event Logging**
- Recording all custodial actions
- Tracking orphan detection and resolution
- Logging redirect creation and application
- Maintaining audit trail of system self-healing

### **3.5 Self-Healing**
- Automatically detecting broken references
- Repairing orphaned records
- Reconstructing relationships
- Maintaining system integrity without human intervention

---

## **4. Database Tables**

ANIBUS uses three core tables:

### **4.1 `anibus_orphans`**
Tracks orphaned references awaiting resolution.

**Fields:**
- `id` - Primary key
- `table_name` - Table containing the orphaned reference
- `column_name` - Column containing the missing ID
- `missing_id` - The ID that doesn't exist in the parent table
- `detected_ymdhis` - When the orphan was detected (UTC timestamp)
- `resolved_ymdhis` - When the orphan was resolved (NULL if unresolved)
- `resolution_note` - Notes about how it was resolved

**Purpose:** Log all orphaned references for inspection, repair, and historical analysis.

### **4.2 `anibus_redirects`**
Maintains semantic redirects for memory redirection and lineage preservation.

**Fields:**
- `id` - Primary key
- `from_id` - Original ID (the old reference)
- `to_id` - Target ID (the new reference)
- `table_name` - Table this redirect applies to
- `reason` - Why the redirect was created (e.g., "orphan_repair", "soft_delete", "merge")
- `created_ymdhis` - When the redirect was created (UTC timestamp)

**Purpose:** Preserve lineage when records are reassigned, merged, or transformed. Allows queries to automatically follow redirects.

### **4.3 `anibus_events`**
General event log for all ANIBUS custodial actions.

**Fields:**
- `id` - Primary key
- `event_type` - Type of event (e.g., "orphan_detected", "redirect_created", "repair_completed")
- `details` - JSON or text details about the event
- `created_ymdhis` - When the event occurred (UTC timestamp)

**Purpose:** Audit trail of all ANIBUS operations for debugging, analysis, and system monitoring.

---

## **5. PHP Class Implementation**

ANIBUS is implemented as `class-anibus.php` in `lupo-includes/`.

### **5.1 Core Methods**

#### **Orphan Management**
- `logOrphan($table, $column, $missingId)` - Record an orphaned reference
- `detectOrphansInTable($table, $column, $idList)` - Scan table for orphans
- `getOrphans($limit = 100)` - Get list of unresolved orphans
- `resolveOrphan($orphanId, $newParentId, $note)` - Mark orphan as resolved
- `repairOrphansForTable($table, $column, $defaultParentId)` - Repair all orphans in a table

#### **Memory Redirection**
- `logRedirect($table, $fromId, $toId, $reason)` - Create a semantic redirect
- `getRedirectTarget($table, $id)` - Get redirect target for an ID
- `applyRedirectIfExists($table, $id)` - Apply redirect if one exists

#### **Event Logging**
- `logEvent($eventType, $details)` - Log a general ANIBUS event

### **5.2 Core Principles**

The class follows these principles:

```
ANIBUS NEVER deletes data.
ANIBUS NEVER enforces foreign keys.
ANIBUS ONLY logs, reassigns, redirects, and preserves lineage.
```

---

## **6. How ANIBUS Works**

### **6.1 Orphan Detection Flow**

1. **Detection** - ANIBUS scans tables for references to non-existent records
2. **Logging** - Orphans are logged to `anibus_orphans` with detection timestamp
3. **Inspection** - Unresolved orphans can be queried for analysis
4. **Resolution** - Orphans are reassigned to valid parents
5. **Redirect Creation** - Semantic redirects preserve the original reference
6. **Resolution Logging** - Resolution is recorded with notes

### **6.2 Memory Redirection Flow**

1. **Redirect Creation** - When a record is reassigned, a redirect is created
2. **Redirect Storage** - Redirect stored in `anibus_redirects` with reason
3. **Automatic Application** - Queries can use `applyRedirectIfExists()` to follow redirects
4. **Lineage Preservation** - Original references are preserved through redirect chains

### **6.3 Self-Healing Flow**

1. **Periodic Scanning** - ANIBUS scans tables for orphaned references
2. **Automatic Detection** - Orphans are detected and logged
3. **Repair Attempts** - ANIBUS attempts to repair orphans using default parents
4. **Redirect Creation** - Redirects preserve original relationships
5. **Event Logging** - All actions are logged for audit trail

---

## **7. Integration with Lupopedia**

ANIBUS integrates with:

- **Agent 0 (System Agent)** - Kernel-level governance and safety
- **Thread Manager** - Maintains dialog thread relationships
- **History Archivist** - Preserves historical lineage
- **Dialog Extraction Agent** - Maintains dialog message relationships
- **Soft-Delete System** - Handles reassignment instead of deletion
- **Shadow-Path System** - Preserves alternate timelines and abandoned branches

---

## **8. Coding Pattern: Orphan Detection**

### **8.1 The Pattern**

When writing code that queries records by parent reference, **always check if the parent exists**. If it doesn't, log it to ANIBUS.

**The Pattern:**
```php
// âŒ WRONG: Just query without checking parent
$stmt = $db->prepare("SELECT * FROM contents WHERE content_parent_id = ?");
$stmt->execute([$parentId]);
$children = $stmt->fetchAll();

// âœ… CORRECT: Check parent exists, log orphan if missing
$parentStmt = $db->prepare("SELECT COUNT(*) AS c FROM contents WHERE content_id = ?");
$parentStmt->execute([$parentId]);
$parentRow = $parentStmt->fetch();

if ($parentRow['c'] == 0) {
    // Parent doesn't exist - log orphan
    $anibus = new ANIBUS($db);
    $anibus->logOrphan('contents', 'content_parent_id', $parentId);
    
    // Handle gracefully - maybe return empty array or use default parent
    return [];
}

// Parent exists - proceed normally
$stmt = $db->prepare("SELECT * FROM contents WHERE content_parent_id = ?");
$stmt->execute([$parentId]);
$children = $stmt->fetchAll();
```

### **8.2 When to Use This Pattern**

Use this pattern whenever you:
- Query by a parent/foreign key reference
- Join tables on relationships
- Reference records that might not exist
- Handle soft-deleted parent records
- Import data from external sources
- Process user-submitted data with references

**Examples:**
- `SELECT * FROM collection_tabs WHERE collection_id = ?` â†’ Check `collections` table
- `SELECT * FROM contents WHERE content_parent_id = ?` â†’ Check `contents` table
- `SELECT * FROM dialog_messages WHERE dialog_thread_id = ?` â†’ Check `dialog_threads` table
- `SELECT * FROM memory_events WHERE actor_id = ?` â†’ Check `actors` table

### **8.3 Helper Function Pattern**

You can create a helper function to make this easier:

```php
function queryWithOrphanCheck($db, $table, $parentColumn, $parentId, $parentTable = null) {
    $anibus = new ANIBUS($db);
    
    // If parent table not specified, assume same table
    $checkTable = $parentTable ?: $table;
    
    // Check if parent exists
    $checkStmt = $db->prepare("SELECT COUNT(*) AS c FROM {$checkTable} WHERE id = ?");
    $checkStmt->execute([$parentId]);
    $checkRow = $checkStmt->fetch();
    
    if ($checkRow['c'] == 0) {
        // Log orphan
        $anibus->logOrphan($table, $parentColumn, $parentId);
        return []; // Return empty array
    }
    
    // Parent exists - query normally
    $stmt = $db->prepare("SELECT * FROM {$table} WHERE {$parentColumn} = ?");
    $stmt->execute([$parentId]);
    return $stmt->fetchAll();
}

// Usage:
$children = queryWithOrphanCheck($db, 'contents', 'content_parent_id', $parentId, 'contents');
```

### **8.4 Why This Matters**

Without orphan detection:
- Broken references go unnoticed
- Queries return empty results silently
- Data integrity degrades over time
- System becomes fragile and unpredictable

With orphan detection:
- Broken references are logged immediately
- ANIBUS can repair them automatically
- System maintains self-healing capability
- Data integrity is preserved
- Lineage is maintained through redirects

### **8.5 Integration with Existing Code**

When you encounter code like:
```php
SELECT * FROM table WHERE parent_id = X
```

**Always add:**
```php
// Check parent exists first
if (parent_not_exists) {
    $anibus->logOrphan('table', 'parent_id', X);
    // Handle gracefully
}
```

This is the **Lupopedia way** â€” detect, log, heal, preserve.

---

## **9. Example Use Cases**

### **9.1 Orphan Resolution**
```php
// Detect orphans in a table
$anibus = new ANIBUS($db);
$anibus->detectOrphansInTable('content_category_map', 'category_id', [1, 2, 3, 999]);

// Get unresolved orphans
$orphans = $anibus->getOrphans(100);

// Resolve an orphan
$anibus->resolveOrphan($orphanId, $newParentId, "Reassigned to default category");
```

### **9.2 Memory Redirection**
```php
// Create a redirect when reassigning
$anibus->logRedirect('contents', $oldContentId, $newContentId, "content_merge");

// Apply redirect in queries
$actualId = $anibus->applyRedirectIfExists('contents', $oldContentId);
// $actualId will be $newContentId if redirect exists, otherwise $oldContentId
```

### **9.3 Automatic Repair**
```php
// Repair all orphans in a table
$anibus->repairOrphansForTable('content_category_map', 'category_id', 1);
// This will:
// 1. Find all orphans in the table
// 2. Reassign them to category_id = 1
// 3. Create redirects for lineage preservation
// 4. Mark orphans as resolved
```

---

## **10. Relationship to NO FOREIGN KEYS DOCTRINE**

ANIBUS is the **implementation** of the [NO FOREIGN KEYS DOCTRINE](NO_FOREIGN_KEYS_DOCTRINE.md).

That doctrine states:
- Foreign keys are forbidden
- The database stores raw facts
- The agents enforce correctness
- ANIBUS heals and maintains lineage

ANIBUS is the agent that makes this doctrine work in practice.

---

## **11. Future Extensions**

ANIBUS will expand to handle:

- **NoSQL Memory Integration** - Redirects for vector memory and semantic embeddings
- **Temporal Memory Logs** - Time-based redirect chains
- **Shadow-Path Archives** - Preserving abandoned branches and alternate timelines
- **Emotional Continuity** - Maintaining mood and emotional metadata across reassignments
- **Multi-Domain Redirects** - Cross-domain reference resolution
- **Federated Redirects** - Redirects across federated nodes

---

## **12. Summary**

ANIBUS is the **custodial intelligence** that:

- Replaces foreign key constraints
- Handles orphan resolution
- Maintains memory redirection
- Preserves lineage
- Enables self-healing
- Supports soft-delete doctrine
- Preserves shadow-paths

Without ANIBUS, Lupopedia would be fragile, rigid, and unable to self-heal.

With ANIBUS, Lupopedia is:

- **Flexible** - Relationships can evolve
- **Portable** - No database-level constraints
- **Self-healing** - Automatically repairs broken references
- **Non-destructive** - Never deletes, only reassigns
- **Lineage-preserving** - Maintains complete history
- **Agent-governed** - Intelligence at the application layer

This is how Lupopedia stays alive, flexible, emotional, and self-healing.

---

## Related Documentation

**Core Doctrine (Primary References):**
- **[No Foreign Keys Doctrine](NO_FOREIGN_KEYS_DOCTRINE.md)** - Why ANIBUS replaces foreign key constraints
- **[Database Philosophy](../architecture/DATABASE_PHILOSOPHY.md)** - Application-first validation that ANIBUS implements

**System Integration:**
- **[WOLFMIND Doctrine](WOLFMIND_DOCTRINE.md)** - Memory system that uses ANIBUS for orphan resolution
- **[Agent Runtime](../agents/AGENT_RUNTIME.md)** - How agents interact with ANIBUS for relationship management

**Implementation Context:**
- **[Database Schema](../schema/DATABASE_SCHEMA.md)** - Complete documentation of anibus_* tables
- **[Architecture Sync](../architecture/ARCHITECTURE_SYNC.md)** - System architecture that includes ANIBUS custodial intelligence

**Historical Context (LOW Priority):**
- **[History](../history/HISTORY.md)** - Background on why self-healing systems became necessary
- **[What Not To Do And Why](../appendix/appendix/WHAT_NOT_TO_DO_AND_WHY.md)** - Lessons learned that led to ANIBUS design

---

