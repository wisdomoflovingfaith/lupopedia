---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
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
  message: "MANDATORY: Stored procedures and functions are FORBIDDEN. The database is for storage, not computation. All logic belongs in the application layer."
  mood: "FF0000"
tags:
  categories: ["documentation", "doctrine", "database", "architecture", "mandatory"]
  collections: [GLOBAL_COLLECTION_CORE_DOCS, GLOBAL_COLLECTION_DOCTRINE]
  channels: [GLOBAL_CHANNEL_DEV, GLOBAL_CHANNEL_PUBLIC]
in_this_file_we_have:
  - No Stored Procedures/Functions Doctrine (MANDATORY)
  - Why Stored Procedures/Functions Are Forbidden
  - Database Storage vs Computation
  - Data Merging Requirements
  - Federation Requirements
  - ANIBUS Logic Control Requirements
  - IDE Enforcement Rules
  - Summary Doctrine
file:
  title: "No Stored Procedures/Functions Doctrine (MANDATORY)"
  description: "Stored procedures and functions are FORBIDDEN in Lupopedia. The database is for storage, not computation. All logic belongs in the application layer. This is mandatory for data merging, federation, and ANIBUS repair operations."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: GLOBAL_DEFAULT_STATUS
  status: published
  author: "Captain Wolfie"
---

# ðŸš« **NO STORED PROCEDURES / NO STORED FUNCTIONS DOCTRINE (MANDATORY)**  
### *The Database Is for Storage, Not Computation*  
### *This Doctrine Is Absolute and Non-Negotiable*

---

## âš ï¸ **MANDATORY RULE: NO STORED PROCEDURES OR FUNCTIONS**

**ALL IDEs AND AI AGENTS MUST FOLLOW THIS DOCTRINE ABSOLUTELY.**

Lupopedia **NEVER** uses stored procedures or stored functions.  
This is not optional.  
This is not a suggestion.  
This is a **mandatory architectural requirement**.

---

## **1. Do NOT Generate or Suggest Stored Procedures or Functions**

Stored procedures and functions must **NEVER** be created, suggested, or added.

### **Prohibited:**

- `CREATE PROCEDURE ...`
- `CREATE FUNCTION ...`
- `CREATE DEFINER ...`
- `RETURNS ...`
- `BEGIN ... END` blocks in database
- `SELECT ... INTO ...` statements
- `DECLARE ...` statements
- `IF/ELSE` logic inside the database
- MySQL routines
- Computed/generated columns with logic
- Database-side logic of any kind
- Any computation executed inside the database engine

### **Why Stored Procedures/Functions Are Prohibited:**

**Stored procedures and functions interfere with:**
- Data merging operations
- Federation sync across different database engines
- ANIBUS repair operations
- Historical accuracy preservation
- Portability across database engines
- Version control and migration safety
- Debugging and inspection
- Autoinstaller compatibility

**Stored procedures and functions are NOT compatible with Lupopedia's architecture.**

---

## **2. The Database Is for Storage, Not Computation**

Lupopedia's database layer must remain:

- **Portable** â€” Works across MySQL, MariaDB, SQLite, PostgreSQL, and future engines
- **Mergeable** â€” Can merge databases with different histories and structures
- **Repairable** â€” ANIBUS can repair data without database logic interfering
- **Inspectable** â€” All logic is visible in application code, not hidden in database
- **Predictable** â€” No hidden side effects or unexpected mutations
- **Federation-Safe** â€” Works across independent nodes with different database engines
- **Autoinstaller-Friendly** â€” Works in shared hosting and low-privilege environments

**Stored procedures and functions break all of that.**

### **Example: What Belongs Where**

```php
// âœ… CORRECT: Logic in application code (PHP)
function calculateAgentScore($agentId, $db) {
    $stats = $db->query("
        SELECT 
            COUNT(*) as total_calls,
            AVG(response_time_ms) as avg_response,
            success_rate
        FROM agent_tool_calls
        WHERE agent_id = :agent_id
    ", ['agent_id' => $agentId]);
    
    $score = ($stats['total_calls'] * 0.3) + 
             ($stats['avg_response'] * 0.2) + 
             ($stats['success_rate'] * 0.5);
    
    return $score;
}

// Use in application
$score = calculateAgentScore(5, $db);
$db->update('agents', [
    'score' => $score,
    'updated_ymdhis' => date('YmdHis')
], 'agent_id = :id', ['id' => 5]);
```

```sql
-- âŒ WRONG: Logic in stored procedure (FORBIDDEN)
CREATE PROCEDURE calculate_agent_score(IN agent_id BIGINT)
BEGIN
    DECLARE total_calls INT;
    DECLARE avg_response INT;
    DECLARE success_rate FLOAT;
    DECLARE score FLOAT;
    
    SELECT COUNT(*), AVG(response_time_ms), success_rate
    INTO total_calls, avg_response, success_rate
    FROM agent_tool_calls
    WHERE agent_id = agent_id;
    
    SET score = (total_calls * 0.3) + (avg_response * 0.2) + (success_rate * 0.5);
    
    UPDATE agents 
    SET score = score, updated_ymdhis = UNIX_TIMESTAMP(NOW())
    WHERE agent_id = agent_id;
END;

-- âŒ WRONG: Logic in stored function (FORBIDDEN)
CREATE FUNCTION get_agent_score(agent_id BIGINT) RETURNS FLOAT
BEGIN
    DECLARE score FLOAT;
    -- ... computation logic ...
    RETURN score;
END;
```

---

## **3. Stored Procedures Make Merging Impossible**

When merging databases, you encounter:

- Duplicate rows with different IDs
- Conflicting IDs from different sources
- Orphaned children with missing parents
- Mismatched parent relationships
- Inconsistent timestamps
- Partial imports with missing data
- Corrupted data requiring repair

**Stored procedures:**

- Fire at the wrong time during merges
- Rewrite data that should be preserved
- Mutate fields that ANIBUS is trying to repair
- Block merges by enforcing constraints
- Break imports by rejecting valid data
- Destroy historical accuracy by overwriting timestamps
- Execute logic that conflicts with merge algorithms

**Example Merge Scenario:**

```php
// Merging two databases with conflicting agent data
$row_from_db1 = [
    'agent_id' => 5,
    'agent_name' => 'WOLFIE',
    'score' => 8.5,  // Calculated in DB1
    'updated_ymdhis' => 20250115120000
];

$row_from_db2 = [
    'agent_id' => 5,
    'agent_name' => 'WOLFIE',
    'score' => 9.2,  // Calculated in DB2 (different calculation logic!)
    'updated_ymdhis' => 20250120180000
];

// CORRECT: Application code compares and merges intelligently
if ($row_from_db2['updated_ymdhis'] > $row_from_db1['updated_ymdhis']) {
    // DB2 has newer data, preserve its calculated score
    $merged_row = $row_from_db2;
} else {
    $merged_row = $row_from_db1;
}

// Insert merged row with PRESERVED calculated values
$db->insert('agents', [
    'agent_id' => 5,
    'agent_name' => $merged_row['agent_name'],
    'score' => $merged_row['score'],  // Preserved from source
    'created_ymdhis' => 20250101000000,
    'updated_ymdhis' => $merged_row['updated_ymdhis']
]);
```

**If stored procedures existed**, they would:

- Recalculate scores during merge (destroying original values)
- Overwrite timestamps with current time
- Execute logic that conflicts with merge algorithms
- Block merges by enforcing constraints
- Make it impossible to preserve original calculated values

**This makes correct merging impossible.**

---

## **4. Stored Functions Hide Logic Where You Can't See It**

Stored procedures and functions become invisible landmines because you can't:

- **Version control them** â€” They're not in your application code repository
- **Diff them** â€” Hard to see what changed between versions
- **Track changes** â€” No clear history of modifications
- **Migrate them safely** â€” Different database engines have different syntax
- **Port them between engines** â€” MySQL procedures don't work in PostgreSQL
- **Debug them easily** â€” No step-through debugging, limited error messages
- **Replicate them across federation nodes** â€” Each node might have different database engines

**Example Problem:**

```sql
-- Stored procedure exists in production database
CREATE PROCEDURE update_agent_stats(IN agent_id BIGINT)
BEGIN
    -- What logic is in here? You have to query the database to find out
    -- It's not in your version control
    -- It's not in your migration files
    -- It's hidden in the database
    UPDATE agents SET ... WHERE agent_id = agent_id;
END;
```

**Application code is visible and version-controlled:**

```php
// âœ… CORRECT: Logic in application code (visible, version-controlled)
// File: lupo-includes/class-agent-stats.php
class AgentStats {
    public function updateStats($agentId) {
        // All logic is visible here
        // Version controlled in Git
        // Can be diffed, reviewed, tested
        // Can be migrated between database engines
    }
}
```

---

## **5. Federation Makes Stored Procedures Impossible**

Lupopedia nodes will run on:

- **MySQL** (various versions)
- **MariaDB** (various versions)
- **SQLite** (for embedded deployments)
- **PostgreSQL** (for advanced deployments)
- **Cloud databases** (AWS RDS, Google Cloud SQL, etc.)
- **Shared hosting** (limited privileges, no procedure creation)
- **Autoinstaller environments** (wizard-driven setup)

**Stored procedures are:**

- **Engine-specific** â€” MySQL procedures don't work in PostgreSQL
- **Version-specific** â€” MySQL 5.6 procedures might not work in MySQL 8.0
- **Incompatible across nodes** â€” Node A (MySQL) can't replicate procedures to Node B (PostgreSQL)
- **Incompatible across hosting environments** â€” Shared hosting often doesn't allow procedure creation
- **Require elevated privileges** â€” Many hosting environments restrict procedure creation

**Example Federation Problem:**

```php
// Node A (MySQL) has stored procedure
CREATE PROCEDURE sync_agent_data(IN node_id BIGINT)
BEGIN
    -- MySQL-specific syntax
    INSERT INTO agents ... ON DUPLICATE KEY UPDATE ...;
END;

// Node B (PostgreSQL) tries to replicate
-- âŒ FAILS: PostgreSQL doesn't support ON DUPLICATE KEY UPDATE
-- âŒ FAILS: PostgreSQL procedure syntax is different
-- âŒ FAILS: Can't replicate procedures across different engines
```

**Application code works everywhere:**

```php
// âœ… CORRECT: Application code works on all database engines
// File: lupo-includes/class-federationsync.php
class FederationSync {
    public function syncAgentData($nodeId, $db) {
        // Works on MySQL, PostgreSQL, SQLite, MariaDB
        // Database-agnostic logic
        // Can be replicated across all nodes
    }
}
```

---

## **6. ANIBUS Must Control All Logic**

ANIBUS (the custodial intelligence agent) is responsible for:

- **Orphan repair** â€” Reassigning orphaned records to valid parents
- **Parent reassignment** â€” Fixing broken relationships
- **Deduplication** â€” Merging duplicate records intelligently
- **Timestamp comparison** â€” Determining which row is newer
- **Conflict resolution** â€” Resolving conflicts between merged databases
- **Federation sync** â€” Synchronizing data across nodes
- **Merge logic** â€” Intelligently merging rows with different histories

**Stored procedures would:**

- **Override ANIBUS** â€” Execute logic that conflicts with ANIBUS decisions
- **Corrupt its decisions** â€” Mutate data that ANIBUS is trying to repair
- **Mutate data behind its back** â€” Change values without ANIBUS knowing
- **Break conflict resolution** â€” Execute logic that prevents proper conflict resolution
- **Destroy historical truth** â€” Overwrite original values with recalculated ones

**Example ANIBUS Repair:**

```php
// ANIBUS finds orphaned agent record
$orphan = [
    'agent_id' => 42,
    'parent_agent_id' => 999,  // Orphaned (parent deleted)
    'score' => 7.8,  // Original calculated score
    'updated_ymdhis' => 20250106150000
];

// ANIBUS repairs by reassigning parent
$anibus->repairOrphan('agents', 42, [
    'parent_agent_id' => 1,  // Reassigned to valid parent
    // Preserves original score and timestamp
    'score' => $orphan['score'],  // PRESERVED
    'updated_ymdhis' => $orphan['updated_ymdhis']  // PRESERVED
]);

// If stored procedures existed, they would:
// - Recalculate score during repair (destroying original)
// - Overwrite updated_ymdhis with current time
// - Execute logic that conflicts with ANIBUS repair
// - Make it impossible to preserve original values
```

**ANIBUS must be the only place where logic lives.**

---

## **7. Computed Columns Are Also Forbidden**

Computed/generated columns that contain logic are also forbidden:

```sql
-- âŒ WRONG: Computed column with logic (FORBIDDEN)
CREATE TABLE agents (
    agent_id BIGINT NOT NULL,
    total_calls INT,
    success_rate FLOAT,
    score BIGINT GENERATED ALWAYS AS (total_calls * success_rate) STORED
);

-- âŒ WRONG: Generated column with function (FORBIDDEN)
CREATE TABLE agents (
    agent_id BIGINT NOT NULL,
    created_ymdhis BIGINT,
    created_year INT GENERATED ALWAYS AS (YEAR(FROM_UNIXTIME(created_ymdhis))) STORED
);
```

**All computation must happen in application code:**

```php
// âœ… CORRECT: Computation in application code
$agent = $db->query("
    SELECT agent_id, total_calls, success_rate
    FROM agents
    WHERE agent_id = :id
", ['id' => 5]);

$score = $agent['total_calls'] * $agent['success_rate'];

// Store computed value explicitly if needed
$db->update('agents', [
    'score' => $score,
    'updated_ymdhis' => date('YmdHis')
], 'agent_id = :id', ['id' => 5]);
```

---

## **8. IDE Enforcement Rules**

**ALL IDEs MUST TREAT THIS DOCTRINE AS ABSOLUTE.**

### **Do NOT:**

- âŒ Suggest stored procedures
- âŒ Auto-generate stored procedures
- âŒ "Optimize" schemas by adding stored procedures
- âŒ Rewrite migrations to include stored procedures
- âŒ Generate stored functions
- âŒ Use ORM features that generate stored procedures
- âŒ Add database-side computation logic
- âŒ Create computed/generated columns with logic
- âŒ Use database-side IF/ELSE logic
- âŒ Put any business logic in the database

### **DO:**

- âœ… Put all logic in application code (PHP, Python, etc.)
- âœ… Use simple SELECT, INSERT, UPDATE, DELETE statements
- âœ… Perform all computation in application layer
- âœ… Keep database as pure storage layer
- âœ… Make all logic visible and version-controlled
- âœ… Ensure all logic is portable across database engines
- âœ… Let ANIBUS control all repair and merge logic

**Lupopedia uses application-level logic, not database-level computation.**

---

## **9. Code Examples**

### **âœ… CORRECT: All Logic in Application Code**

```php
<?php
// File: lupo-includes/class-agent-calculator.php
class AgentCalculator {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // âœ… CORRECT: Calculation logic in application code
    public function calculateAgentScore($agentId) {
        // Fetch raw data from database
        $stats = $this->db->query("
            SELECT 
                COUNT(*) as total_calls,
                AVG(response_time_ms) as avg_response,
                success_rate
            FROM agent_tool_calls
            WHERE agent_id = :agent_id
        ", ['agent_id' => $agentId]);
        
        // Calculate in application code
        $score = ($stats['total_calls'] * 0.3) + 
                 ($stats['avg_response'] * 0.2) + 
                 ($stats['success_rate'] * 0.5);
        
        return $score;
    }
    
    // âœ… CORRECT: Update with explicit calculation
    public function updateAgentScore($agentId) {
        $score = $this->calculateAgentScore($agentId);
        
        $this->db->update('agents', [
            'score' => $score,
            'updated_ymdhis' => date('YmdHis')  // Explicit timestamp
        ], 'agent_id = :id', ['id' => $agentId]);
    }
}
```

### **âœ… CORRECT: Simple Database Queries Only**

```sql
-- âœ… CORRECT: Simple SELECT (no logic)
SELECT agent_id, agent_name, score, updated_ymdhis
FROM agents
WHERE agent_id = 5;

-- âœ… CORRECT: Simple INSERT (no logic)
INSERT INTO agents 
  (agent_id, agent_name, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES 
  (5, 'WOLFIE', 20250101000000, 20250101000000, 0, NULL);

-- âœ… CORRECT: Simple UPDATE (no logic)
UPDATE agents 
SET 
  agent_name = 'Updated Name',
  updated_ymdhis = 20260109143000  -- Explicit timestamp
WHERE agent_id = 5;
```

### **âŒ WRONG: Stored Procedures (FORBIDDEN)**

```sql
-- âŒ NEVER DO THIS
CREATE PROCEDURE calculate_agent_score(IN agent_id BIGINT)
BEGIN
    DECLARE total_calls INT;
    DECLARE avg_response INT;
    DECLARE success_rate FLOAT;
    DECLARE score FLOAT;
    
    SELECT COUNT(*), AVG(response_time_ms), success_rate
    INTO total_calls, avg_response, success_rate
    FROM agent_tool_calls
    WHERE agent_id = agent_id;
    
    SET score = (total_calls * 0.3) + (avg_response * 0.2) + (success_rate * 0.5);
    
    UPDATE agents 
    SET score = score, updated_ymdhis = UNIX_TIMESTAMP(NOW())
    WHERE agent_id = agent_id;
END;

-- âŒ NEVER DO THIS
CREATE FUNCTION get_agent_score(agent_id BIGINT) RETURNS FLOAT
BEGIN
    DECLARE score FLOAT;
    -- ... computation logic ...
    RETURN score;
END;

-- âŒ NEVER DO THIS
CREATE DEFINER = 'root'@'localhost' PROCEDURE sync_data()
BEGIN
    -- ... logic ...
END;
```

### **âŒ WRONG: Computed Columns (FORBIDDEN)**

```sql
-- âŒ NEVER DO THIS
CREATE TABLE agents (
    agent_id BIGINT NOT NULL,
    total_calls INT,
    success_rate FLOAT,
    score BIGINT GENERATED ALWAYS AS (total_calls * success_rate) STORED
);

-- âŒ NEVER DO THIS
CREATE TABLE agents (
    agent_id BIGINT NOT NULL,
    created_ymdhis BIGINT,
    created_year INT GENERATED ALWAYS AS (YEAR(FROM_UNIXTIME(created_ymdhis))) STORED
);
```

---

## **10. Summary Doctrine**

```
STORED PROCEDURES AND FUNCTIONS ARE FORBIDDEN IN LUPOPEDIA (MANDATORY).

Reasons:
- Make data merging impossible
- Hide logic where it can't be version-controlled
- Break federation across different database engines
- Interfere with ANIBUS repair operations
- Destroy historical accuracy
- Break portability across hosting environments
- Require elevated privileges
- Make debugging and inspection difficult

ALL logic must be:
- In application code (PHP, Python, etc.)
- Visible and version-controlled
- Portable across database engines
- Controlled by ANIBUS for repairs and merges
- Simple SELECT, INSERT, UPDATE, DELETE only

The database must NEVER execute logic.
The database must NEVER compute values.
The database must ONLY store data.
All computation must happen in the application layer.
Stored procedures must NEVER exist.
Stored functions must NEVER exist.
Computed columns with logic must NEVER exist.
```

---

## **11. Final Note for AI Tools and IDEs**

**This doctrine is MANDATORY and NON-NEGOTIABLE.**

Any AI tool (Cursor, Copilot, DeepSeek, Claude, Gemini, Grok, Windsurf, Cursor Auto, or any other IDE/AI agent) must follow this rule:

> **NEVER add stored procedures.  
> NEVER suggest stored procedures.  
> NEVER generate stored procedures.  
> NEVER add stored functions.  
> NEVER suggest stored functions.  
> NEVER generate stored functions.  
> NEVER add computed columns with logic.  
> NEVER put business logic in the database.  
> ALWAYS put all logic in application code.  
> ALWAYS keep database as pure storage layer.  
> ALWAYS make logic visible and version-controlled.**

**This is not optional.**  
**This is not a suggestion.**  
**This is a mandatory architectural requirement.**

All computation and logic is handled by:

- **Application code** (PHP, Python, etc.)
- **ANIBUS repair operations**
- **Federation sync logic**
- **Merge algorithms**
- **Simple SQL queries only** (SELECT, INSERT, UPDATE, DELETE)

**Never by stored procedures or functions.**

This is how Lupopedia maintains portability, enables data merging, supports federation, and allows ANIBUS to repair data without interference.

---

**See also:**
- [NO_FOREIGN_KEYS_DOCTRINE.md](NO_FOREIGN_KEYS_DOCTRINE.md) â€” Related doctrine on foreign keys
- [NO_TRIGGERS_DOCTRINE.md](NO_TRIGGERS_DOCTRINE.md) â€” Related doctrine on triggers
- [ANIBUS_DOCTRINE.md](ANIBUS_DOCTRINE.md) â€” Custodial intelligence system
- [DATABASE_PHILOSOPHY.md](../architecture/DATABASE_PHILOSOPHY.md) â€” Database design principles
- [ANIBUS_DOCTRINE.md](ANIBUS_DOCTRINE.md) â€” How ANIBUS handles data repair
- [PHILOSOPHY.md](../../PHILOSOPHY.md) â€” Overall database philosophy

---
