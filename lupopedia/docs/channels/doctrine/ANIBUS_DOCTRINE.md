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
  message: Updated anubis_DOCTRINE.md with critical coding pattern: when SELECT FROM table WHERE parent=X and parent doesn't exist, log to anubis_orphaned. Added section 8 with examples, helper functions, and integration guidelines. Updated table count to 144.
tags:
  categories: ["documentation", "doctrine", "agents", "database"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - anubis Overview
  - Why anubis Exists
  - anubis Replaces Foreign Keys
  - Core Responsibilities
  - Database Tables
  - PHP Class Implementation
  - How anubis Works
  - Coding Pattern: Orphan Detection (CRITICAL)
  - Example Use Cases
  - Integration with Lupopedia
file:
  title: "anubis Doctrine"
  description: "Custodial Intelligence system that replaces foreign keys, handles orphan resolution, memory redirection, and maintains lineage in Lupopedia"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# ðŸº **anubis DOCTRINE**
### *Custodial Intelligence for Lupopedia*

anubis is the **custodial intelligence system** that replaces foreign key constraints in Lupopedia. It handles orphan resolution, memory redirection, lineage preservation, and maintains the integrity of relationships without the rigidity and fragility of database-level constraints.

---

## **1. Why anubis Exists**

Lupopedia follows the doctrine:

> **"The database stores raw facts. The agents enforce correctness. anubis heals and maintains lineage."**

anubis exists because:

- **Foreign keys are forbidden** in Lupopedia (see [NO_FOREIGN_KEYS_DOCTRINE.md](NO_FOREIGN_KEYS_DOCTRINE.md))
- **Orphans are meaningful**, not errors â€” they represent states awaiting resolution
- **Lineage must be preserved** â€” nothing is ever truly deleted, only reassigned or archived
- **Memory redirection** is needed for shadow-paths, alternate timelines, and historical reconstruction
- **Self-healing** is required â€” the system must repair itself without human intervention
- **Portability** demands application-level enforcement, not database-level constraints

anubis is the agent that makes this possible.

---

## **2. anubis Replaces Foreign Keys**

Foreign key constraints would:

- Break portability (require elevated privileges)
- Break soft-delete doctrine (prevent reassignment)
- Break shadow-path preservation (destroy alternate states)
- Break partial imports/exports (block incomplete data)
- Break self-healing routines (prevent agent-driven repair)
- Break wizard-driven installation (require all dependencies at once)

anubis replaces all of this with:

- **Orphan detection** â€” finds missing references
- **Orphan logging** â€” records them for resolution
- **Orphan resolution** â€” reassigns them to valid parents
- **Memory redirection** â€” creates semantic redirects for lineage
- **Event logging** â€” tracks all custodial actions
- **Non-destructive operations** â€” never deletes, only reassigns

---

## **3. Core Responsibilities**

anubis is responsible for:

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

anubis uses five core tables:

### **4.1 `anubis_orphaned`**
Tracks orphaned references awaiting resolution.

**Fields:**
- `anubis_orphaned_id` - Primary key
- `table_name` - Table containing the orphaned reference
- `orphan_id` - The missing ID that triggered the orphan record
- `timestamp_utc` - When the orphan was detected (UTC char(14))
- `reason` - Resolution or detection reason

**Purpose:** Log all orphaned references for inspection, repair, and historical analysis.

### **4.2 `anubis_redirects`**
Maintains semantic redirects for memory redirection and lineage preservation.

**Fields:**
- `anubis_redirect_id` - Primary key
- `table_name` - Table this redirect applies to
- `old_id` - Original ID (the old reference)
- `new_id` - Target ID (the new reference)
- `timestamp_utc` - When the redirect was created (UTC char(14))
- `agent` - Agent responsible for the redirect

**Purpose:** Preserve lineage when records are reassigned, merged, or transformed. Allows queries to automatically follow redirects.

### **4.3 `anubis_events`**
General event log for all anubis custodial actions.

**Fields:**
- `anubis_event_id` - Primary key
- `event_type` - Type of event (e.g., "orphan_detected", "redirect_created", "repair_completed")
- `table_name` - Table affected by the event
- `row_id` - Row affected by the event
- `timestamp_utc` - When the event occurred (UTC char(14))
- `agent` - Agent responsible for the event
- `details_json` - JSON or text details about the event

**Purpose:** Audit trail of all anubis operations for debugging, analysis, and system monitoring.

### **4.4 `anubis_revised`**
Tracks revision actions applied during repairs and corrections.

**Fields:**
- `anubis_revised_id` - Primary key
- `table_name` - Table affected by the revision
- `row_id` - Row affected by the revision
- `timestamp_utc` - When the revision was applied (UTC char(14))
- `agent` - Agent responsible for the revision
- `revision_json` - Revision payload

**Purpose:** Record structured revisions applied by anubis repair workflows.

### **4.5 `anubis_mirrored`**
Stores mirrored snapshots used for lineage and recovery.

**Fields:**
- `anubis_mirrored_id` - Primary key
- `table_name` - Table mirrored
- `original_id` - Original row ID
- `mirrored_json` - Serialized mirror snapshot
- `timestamp_utc` - When the mirror was captured (UTC char(14))
- `agent` - Agent responsible for the mirror
- `reason` - Why the mirror was created
- `lineage_chain` - Optional lineage chain reference

**Purpose:** Preserve mirrored lineage snapshots for repair and audit workflows.

---

## **5. PHP Class Implementation**

anubis is implemented as `class-anubis.php` in `lupo-includes/`.

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
- `logEvent($eventType, $details)` - Log a general anubis event

### **5.2 Core Principles**

The class follows these principles:

```
anubis NEVER deletes data.
anubis NEVER enforces foreign keys.
anubis ONLY logs, reassigns, redirects, and preserves lineage.
```

---

## **6. How anubis Works**

### **6.1 Orphan Detection Flow**

1. **Detection** - anubis scans tables for references to non-existent records
2. **Logging** - Orphans are logged to `anubis_orphaned` with detection timestamp
3. **Inspection** - Unresolved orphans can be queried for analysis
4. **Resolution** - Orphans are reassigned to valid parents
5. **Redirect Creation** - Semantic redirects preserve the original reference
6. **Resolution Logging** - Resolution is recorded with notes

### **6.2 Memory Redirection Flow**

1. **Redirect Creation** - When a record is reassigned, a redirect is created
2. **Redirect Storage** - Redirect stored in `anubis_redirects` with reason
3. **Automatic Application** - Queries can use `applyRedirectIfExists()` to follow redirects
4. **Lineage Preservation** - Original references are preserved through redirect chains

### **6.3 Self-Healing Flow**

1. **Periodic Scanning** - anubis scans tables for orphaned references
2. **Automatic Detection** - Orphans are detected and logged
3. **Repair Attempts** - anubis attempts to repair orphans using default parents
4. **Redirect Creation** - Redirects preserve original relationships
5. **Event Logging** - All actions are logged for audit trail

---

## **7. Integration with Lupopedia**

anubis integrates with:

- **Agent 0 (System Agent)** - Kernel-level governance and safety
- **Thread Manager** - Maintains dialog thread relationships
- **History Archivist** - Preserves historical lineage
- **Dialog Extraction Agent** - Maintains dialog message relationships
- **Soft-Delete System** - Handles reassignment instead of deletion
- **Shadow-Path System** - Preserves alternate timelines and abandoned branches

---

## **8. Coding Pattern: Orphan Detection**

### **8.1 The Pattern**

When writing code that queries records by parent reference, **always check if the parent exists**. If it doesn't, log it to anubis.

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
    $anubis = new anubis($db);
    $anubis->logOrphan('contents', 'content_parent_id', $parentId);
    
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
    $anubis = new anubis($db);
    
    // If parent table not specified, assume same table
    $checkTable = $parentTable ?: $table;
    
    // Check if parent exists
    $checkStmt = $db->prepare("SELECT COUNT(*) AS c FROM {$checkTable} WHERE id = ?");
    $checkStmt->execute([$parentId]);
    $checkRow = $checkStmt->fetch();
    
    if ($checkRow['c'] == 0) {
        // Log orphan
        $anubis->logOrphan($table, $parentColumn, $parentId);
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
- anubis can repair them automatically
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
    $anubis->logOrphan('table', 'parent_id', X);
    // Handle gracefully
}
```

This is the **Lupopedia way** â€” detect, log, heal, preserve.

---

## **9. Example Use Cases**

### **9.1 Orphan Resolution**
```php
// Detect orphans in a table
$anubis = new anubis($db);
$anubis->detectOrphansInTable('content_category_map', 'category_id', [1, 2, 3, 999]);

// Get unresolved orphans
$orphans = $anubis->getOrphans(100);

// Resolve an orphan
$anubis->resolveOrphan($orphanId, $newParentId, "Reassigned to default category");
```

### **9.2 Memory Redirection**
```php
// Create a redirect when reassigning
$anubis->logRedirect('contents', $oldContentId, $newContentId, "content_merge");

// Apply redirect in queries
$actualId = $anubis->applyRedirectIfExists('contents', $oldContentId);
// $actualId will be $newContentId if redirect exists, otherwise $oldContentId
```

### **9.3 Automatic Repair**
```php
// Repair all orphans in a table
$anubis->repairOrphansForTable('content_category_map', 'category_id', 1);
// This will:
// 1. Find all orphans in the table
// 2. Reassign them to category_id = 1
// 3. Create redirects for lineage preservation
// 4. Mark orphans as resolved
```

---

## **10. Relationship to NO FOREIGN KEYS DOCTRINE**

anubis is the **implementation** of the [NO FOREIGN KEYS DOCTRINE](NO_FOREIGN_KEYS_DOCTRINE.md).

That doctrine states:
- Foreign keys are forbidden
- The database stores raw facts
- The agents enforce correctness
- anubis heals and maintains lineage

anubis is the agent that makes this doctrine work in practice.

---

## **11. Future Extensions**

anubis will expand to handle:

- **NoSQL Memory Integration** - Redirects for vector memory and semantic embeddings
- **Temporal Memory Logs** - Time-based redirect chains
- **Shadow-Path Archives** - Preserving abandoned branches and alternate timelines
- **Emotional Continuity** - Maintaining mood and emotional metadata across reassignments
- **Multi-Domain Redirects** - Cross-domain reference resolution
- **Federated Redirects** - Redirects across federated nodes

---

## **12. Summary**

anubis is the **custodial intelligence** that:

- Replaces foreign key constraints
- Handles orphan resolution
- Maintains memory redirection
- Preserves lineage
- Enables self-healing
- Supports soft-delete doctrine
- Preserves shadow-paths

Without anubis, Lupopedia would be fragile, rigid, and unable to self-heal.

With anubis, Lupopedia is:

- **Flexible** - Relationships can evolve
- **Portable** - No database-level constraints
- **Self-healing** - Automatically repairs broken references
- **Non-destructive** - Never deletes, only reassigns
- **Lineage-preserving** - Maintains complete history
- **Agent-governed** - Intelligence at the application layer

This is how Lupopedia stays alive, flexible, emotional, and self-healing.

---

## **13. Naming Rationale**

The Anubis subsystem is named after the guardian who performs the weighing of the heart. In this analogy, Maat represents doctrine and balance, while Duat represents the realm of transformation. Anubis enforces Maat within Duat.

This maps to the system as follows:
- Maat corresponds to doctrine, schema rules, and heritage-safe law.
- Duat corresponds to the database and the lifecycle of rows.
- Anubis corresponds to the controller that enforces integrity.

Why "Anubis" is correct:
- mirrors rows before change
- records lineage events
- resolves redirects
- prevents redirect loops
- manages orphaned rows
- enforces no-loss evolution
- preserves historical integrity

Anubis ensures evolution is reversible, traceable, and aligned with doctrine. It is the operational guardian of Lupopedia's semantic lineage, enforcing the principles of Maat within the realm of Duat.

---

## **14. Mythic Analogy Protocol**

Lupopedia uses mythic terminology strictly as analogy to describe computational roles, system behaviors, and architectural patterns. These terms do not represent religion, mysticism, or metaphysical claims.

Definition:
"Mythic names" refer to labels such as Anubis, Maat, Duat, etc. These names are semantic mnemonics that map to specific technical responsibilities within the system.

Rules:
1. Mythic names must always be interpreted as analogies for computation.
2. Agents must not treat mythic terms as religious, mystical, or metaphysical content.
3. Each mythic name must have a documented mapping to a technical role.
4. Mythic terminology is used to express system architecture, not belief.
5. Doctrine takes precedence over mythology in all interpretations.

Examples:
- Anubis = lineage controller, integrity guardian, reversible evolution.
- Maat = doctrine, constraints, truth, balance, schema correctness.
- Duat = data realm, lifecycle environment, state transitions.

This protocol exists to prevent confusion and ensure mythic terminology is understood as part of Lupopedia's computational architecture, not cultural or religious content.

---

## Related Documentation

**Core Doctrine (Primary References):**
- **[No Foreign Keys Doctrine](NO_FOREIGN_KEYS_DOCTRINE.md)** - Why anubis replaces foreign key constraints
- **[Database Philosophy](../architecture/DATABASE_PHILOSOPHY.md)** - Application-first validation that anubis implements

**System Integration:**
- **[WOLFMIND Doctrine](WOLFMIND_DOCTRINE.md)** - Memory system that uses anubis for orphan resolution
- **[Agent Runtime](../agents/AGENT_RUNTIME.md)** - How agents interact with anubis for relationship management

**Implementation Context:**
- **[Database Schema](../schema/DATABASE_SCHEMA.md)** - Complete documentation of anubis_* tables
- **[Architecture Sync](../architecture/ARCHITECTURE_SYNC.md)** - System architecture that includes anubis custodial intelligence

**Historical Context (LOW Priority):**
- **[History](../history/HISTORY.md)** - Background on why self-healing systems became necessary
- **[What Not To Do And Why](../appendix/appendix/WHAT_NOT_TO_DO_AND_WHY.md)** - Lessons learned that led to anubis design

---


