# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\SUPPORTING_ACTOR_DOCTRINE.md"
  file_hash: "9ef21877b08bd0b26a0c6d795eb54550743d1ef59ed73e5ca11d38581dc7919e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for SUPPORTING_ACTOR_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "supporting_actor_doctrinemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers: {
  file_path_from_root: "docs/doctrine/SUPPORTING_ACTOR_DOCTRINE.md",
  system_version: "4.0.38",
  channel_id: 42,
  mood_rgb: "4B0082",
  purpose: "Formal doctrine for two-layer actor model with database correlation",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "actor_model",
  traits: ["mandatory", "database_correlated", "v4.0.38"],
  hashtags: ["#actors", "#delegation", "#database", "#validation", "#accountability"],
  engagement: {
    likes: 0,
    shares: 0,
    views: 0,
    last_interaction_utc: "20260224"
  },
  graph_stats: {
    inbound_count: 2,
    outbound_count: 3,
    centrality_score: 0.90
  }
}

flip.footer: {
  inbound_edges: [
    { from: "QUICKSTART.md", type: "implements", weight: 1.0, hashtag: "#actors" },
    { from: "docs/doctrine/X_LUPO_FORWARDED_HEADER_DOCTRINE.md", type: "references", weight: 0.9, hashtag: "#headers" }
  ],
  outbound_edges: [
    { to: "CHANGELOG.md", type: "documented_in", weight: 0.7, hashtag: "#versions" },
    { to: "QUICKSTART.md", type: "referenced_by", weight: 0.8, hashtag: "#onboarding" },
    { to: "HOW_TO_USE_LUPOPEDIA.md", type: "referenced_by", weight: 0.8, hashtag: "#guide" }
  ],
  referenced_by_actors: [1001, 1003, 10000],
  references: {
    by_files: ["QUICKSTART.md", "docs/doctrine/X_LUPO_FORWARDED_HEADER_DOCTRINE.md", "CHANGELOG.md"],
    by_actors: [1001, 1003, 10000]
  },
  semantic_tags: ["actor_accountability", "database_schema", "validation_rules", "two_layer_model"],
  enrichment: {
    llm_inferred_edges: [],
    federated_metrics: {}
  },
  version: "4.0.38",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}
---

# SUPPORTING ACTOR DOCTRINE (4.0.37)

**Status:** MANDATORY  
**Last Modified:** 20260224  
**Author:** Antigravity (1003)  
**Effective:** Version 4.0.37+  

## 1. OVERVIEW
In the Lupopedia multi-agent ecosystem, operational actions often result from a delegation chain between human strategic intent and AI/IDE execution. This doctrine formalizes the distinction between the **Primary Actor** (executor) and the **Supporting Actor** (principal authority).

## 2. THE ACTOR LAYERS

### 2.1 Primary Actor (Execution Agent)
The `actor_id` field in FLIP headers represents the operational executor.
- **Role:** Execution ("How")
- **Accountability:** Tool performance, technical accuracy, doctrine compliance.
- **Example:** `actor_id: 1003` (Antigravity).
- **Database Source:** MUST be validated against `lupo_actors` table when database is online

### 2.2 Supporting Actor (Human Director)
The `supporting_human_id` (the final actor in the `delegation_chain`) represents the intent authority.
- **Role:** Strategy / Intent ("Why")
- **Accountability:** Decision authorization, strategic direction, ultimate responsibility.
- **Example:** `10000` (The Captain).
- **Database Source:** MUST be validated against `lupo_actors` and `lupo_auth_users` tables when database is online

## 3. SEMANTIC ENCODING (`delegation_chain`)
The relationship is formally encoded in the `delegation_chain` header (formerly `x_lupo_forwarded`) using the **Accountability Path** format:

```yaml
# Single-level (Agent -> Human)
delegation_chain: "acting_agent_id:authorizing_human_id"

# Multi-level (Agent -> Agent -> Human)
delegation_chain: "acting_agent_id:delegating_agent_id:authorizing_human_id"
```

| Component | Semantic Meaning |
| :--- | :--- |
| `acting_agent_id` | The IDE or AI tool performing the execution. |
| `delegating_agent_id` | (Optional) Intermediate agent coordinating the work. |
| `authorizing_human_id` | The human principal responsible for the final decision. |

## 4. OPERATIONAL CATEGORIES

### 4.1 IDE / Proxy Agents (Instrumental Mode)
Required for all "Human-in-the-loop" operations where a tool acts as an instrument.
- **Requirement:** MANDATORY `x_lupo_forwarded`.
- **Logic:** `[Human] -> delegates -> [AI/IDE] -> modifies -> [Artifacts]`

### 4.2 Autonomous / System Agents (System Mode)
Used for automated background processes or system-level maintenance.
- **Requirement:** `x_lupo_forwarded` MAY be omitted.
- **Identity:** The "Principal" is the System Doctrine itself.

### 4.3 Human-Only Actions (Direct Mode)
Manual edits or CLI actions performed directly by actors.
- **Header:** `actor_id` reflects the human (>= 10000).
- **Requirement:** `x_lupo_forwarded` is NOT required.

## 5. GOVERNANCE & ACCOUNTABILITY

### 5.1 Responsibility Mapping
- **Technical Failures:** Attributed to `acting_agent_id`.
- **Policy/Intent Failures:** Attributed to `authorizing_human_id`.

### 5.2 Conflict Resolution
When two agents collide, the system resolves the conflict by identifying the `authorizing_human_id`. Arbitration is essentially performed between human principals, with agents acting as proxies.

### 5.3 Forensic Auditing
The combination of `actor_id`, `delegation_chain`, and `channel_id` provides a complete audit trail:
1. **Who executed?** (`actor_id`)
2. **Who authorized?** (`supporting_human_id` - the final ID in the chain)
3. **In what context?** (`channel_id`)

## 6. DATABASE SCHEMA CORRELATION

### 6.1 Three-Table Actor Model
Lupopedia stores actor identity across three tables based on actor type and ID range:

| Table | Actor ID Range | Purpose | Primary Key |
| :--- | :--- | :--- | :--- |
| `lupo_actors` | 0 - ∞ | Universal actor registry | `actor_id` |
| `lupo_agents` | 0 - 9999 | AI/System agent metadata | `agent_id` |
| `lupo_auth_users` | 10000+ | Human authentication & profile | `auth_user_id` |

**Storage Logic:**
- **All actors** (human and AI) have a record in `lupo_actors`.
- **AI/IDE agents** (actor_id 0-9999) have extended metadata in `lupo_agents`.
- **Human actors** (actor_id >= 10000) have authentication data in `lupo_auth_users`.

### 6.2 Header-to-Database Field Mapping

#### FLIP Header → `lupo_actors`
```yaml
# FLIP Header
actor_id: 1003
lupo_agent: "ide|antigravity"
```

```sql
-- Corresponding Database Record
SELECT actor_id, actor_type, slug, name, is_active
FROM lupo_actors
WHERE actor_id = 1003;

-- Expected Result:
-- actor_id: 1003
-- actor_type: 'ai_agent'
-- slug: 'antigravity'
-- name: 'Antigravity IDE'
-- is_active: 1
```

#### FLIP Header → `lupo_agents` (AI Agents Only)
```yaml
# FLIP Header
actor_id: 1003
delegation_chain: "1003:10000"
```

```sql
-- Extended AI Agent Metadata
SELECT agent_id, agent_key, agent_name, archetype, is_internal_only
FROM lupo_agents
WHERE agent_id = 1003;

-- Expected Result:
-- agent_id: 1003
-- agent_key: 'antigravity'
-- agent_name: 'Antigravity IDE'
-- archetype: 'ide_agent'
-- is_internal_only: 0
```

#### FLIP Header → `lupo_auth_users` (Human Actors Only)
```yaml
# FLIP Header
delegation_chain: "1003:10000"
# Supporting human ID: 10000
```

```sql
-- Human Actor Authentication Record
SELECT auth_user_id, username, display_name, email, is_active
FROM lupo_auth_users
WHERE auth_user_id = 10000;

-- Expected Result:
-- auth_user_id: 10000
-- username: 'captain'
-- display_name: 'Captain Wolfie'
-- email: '[email]'
-- is_active: 1
```

### 6.3 Actor ID Range Validation

#### Rule: AI Agents (0-9999)
```sql
-- Validate AI agent exists in both tables
SELECT 
    a.actor_id,
    a.name AS actor_name,
    ag.agent_key,
    ag.archetype
FROM lupo_actors a
INNER JOIN lupo_agents ag ON a.actor_id = ag.agent_id
WHERE a.actor_id = :acting_agent_id
  AND a.actor_id < 10000
  AND a.is_deleted = 0
  AND ag.is_deleted = 0;
```

#### Rule: Human Actors (10000+)
```sql
-- Validate human actor exists in both tables
SELECT 
    a.actor_id,
    a.name AS actor_name,
    au.username,
    au.display_name
FROM lupo_actors a
INNER JOIN lupo_auth_users au ON a.actor_id = au.auth_user_id
WHERE a.actor_id = :supporting_human_id
  AND a.actor_id >= 10000
  AND a.is_deleted = 0
  AND au.is_deleted = 0;
```

### 6.4 Complete delegation_chain Validation Query

```sql
-- Validate all actors in the delegation chain exist
-- Input: delegation_chain = "1003:1001:10000"

-- Step 1: Parse the header value
SET @acting_agent_id = 1003;
SET @supporting_human_id = 10000;

-- Step 2: Validate acting agent (AI/IDE)
SELECT 
    'ACTING_AGENT' AS role,
    a.actor_id,
    a.name,
    a.actor_type,
    ag.agent_key,
    CASE 
        WHEN a.actor_id >= 10000 THEN 'ERROR: Agent ID must be < 10000'
        WHEN a.is_deleted = 1 THEN 'ERROR: Agent is deleted'
        WHEN ag.agent_id IS NULL THEN 'ERROR: Agent not in lupo_agents'
        ELSE 'VALID'
    END AS validation_status
FROM lupo_actors a
LEFT JOIN lupo_agents ag ON a.actor_id = ag.agent_id
WHERE a.actor_id = @acting_agent_id

UNION ALL

-- Step 3: Validate supporting human
SELECT 
    'SUPPORTING_HUMAN' AS role,
    a.actor_id,
    a.name,
    a.actor_type,
    au.username AS agent_key,
    CASE 
        WHEN a.actor_id < 10000 THEN 'ERROR: Human ID must be >= 10000'
        WHEN a.is_deleted = 1 THEN 'ERROR: Human is deleted'
        WHEN au.auth_user_id IS NULL THEN 'ERROR: Human not in lupo_auth_users'
        ELSE 'VALID'
    END AS validation_status
FROM lupo_actors a
LEFT JOIN lupo_auth_users au ON a.actor_id = au.auth_user_id
WHERE a.actor_id = @supporting_human_id;
```

### 6.5 Audit Trail Query Pattern

```sql
-- Find all artifacts modified by a specific human principal
-- (regardless of which AI agent executed the change)

SELECT 
    fa.artifact_id,
    fa.file_path,
    fa.actor_id AS executing_agent_id,
    a1.name AS executing_agent_name,
    fa.supporting_human_id,
    a2.name AS supporting_human_name,
    fa.created_ymdhis,
    fa.channel_id
FROM lupo_flip_artifacts fa
INNER JOIN lupo_actors a1 ON fa.actor_id = a1.actor_id
INNER JOIN lupo_actors a2 ON fa.supporting_human_id = a2.actor_id
WHERE fa.supporting_human_id = :human_actor_id
  AND fa.is_deleted = 0
ORDER BY fa.created_ymdhis DESC;
```

### 6.6 Conflict Resolution Query

```sql
-- When two agents collide, identify the human principals
-- to determine arbitration authority

SELECT 
    fa.file_path,
    fa.actor_id AS agent_id,
    a1.name AS agent_name,
    fa.supporting_human_id AS human_id,
    a2.name AS human_name,
    fa.updated_ymdhis AS last_modified
FROM lupo_flip_artifacts fa
INNER JOIN lupo_actors a1 ON fa.actor_id = a1.actor_id
INNER JOIN lupo_actors a2 ON fa.supporting_human_id = a2.actor_id
WHERE fa.file_path = :conflicted_file_path
  AND fa.is_deleted = 0
ORDER BY fa.updated_ymdhis DESC
LIMIT 2;
```

### 6.7 Database Schema Reference

#### `lupo_actors` (Universal Registry)
```sql
CREATE TABLE lupo_actors (
    actor_id BIGINT NOT NULL PRIMARY KEY,
    actor_type VARCHAR(64) NOT NULL,  -- 'human', 'ai_agent', 'service'
    slug VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT NOT NULL,
    is_active TINYINT NOT NULL DEFAULT 1,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT,
    actor_source_id BIGINT,
    actor_source_type VARCHAR(50),
    metadata TEXT,
    adversarial_role VARCHAR(64) DEFAULT 'none',
    adversarial_oversight_actor_id BIGINT,
    avatar_hash VARCHAR(64)
);
```

#### `lupo_agents` (AI/System Agents: 0-9999)
```sql
CREATE TABLE lupo_agents (
    agent_id BIGINT NOT NULL PRIMARY KEY,  -- Must be < 10000
    agent_key VARCHAR(100) NOT NULL UNIQUE,
    agent_name VARCHAR(150) NOT NULL,
    archetype VARCHAR(150),  -- 'ide_agent', 'kernel', 'external_ai'
    description TEXT,
    version VARCHAR(50) DEFAULT '1.0',
    model_name VARCHAR(100),
    is_global_authority TINYINT NOT NULL DEFAULT 0,
    is_internal_only TINYINT NOT NULL DEFAULT 0,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT,
    -- Performance metrics
    avg_response_time_ms INT DEFAULT 0,
    total_tokens_processed BIGINT DEFAULT 0,
    success_rate FLOAT DEFAULT 1,
    -- Configuration
    system_prompt TEXT,
    provider VARCHAR(50) DEFAULT 'openai',
    temperature FLOAT DEFAULT 0.7
);
```

#### `lupo_auth_users` (Human Actors: 10000+)
```sql
CREATE TABLE lupo_auth_users (
    auth_user_id BIGINT NOT NULL PRIMARY KEY,  -- Must be >= 10000
    username VARCHAR(255) NOT NULL UNIQUE,
    display_name VARCHAR(42) NOT NULL,
    email VARCHAR(100),
    password_hash VARCHAR(255),
    auth_provider VARCHAR(50),  -- 'local', 'oauth', 'ldap'
    provider_id VARCHAR(255),
    profile_image_url VARCHAR(2000),
    last_login_ymdhis BIGINT,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT NOT NULL,
    is_active TINYINT NOT NULL DEFAULT 1,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);
```

## 7. VALIDATION RULES

### 7.1 Header Validation
1. **IDE agents** MUST include `x_lupo_forwarded`.
2. **Human IDs** MUST be >= 10000.
3. **Agent IDs** MUST be < 10000.
4. **Governance actions** REQUIRE a human principal for audit finality.
5. **Format** MUST be `acting_agent_id:supporting_human_id` (colon separator, no spaces).

### 7.2 Database Validation (Online Mode)
1. **Acting agent** MUST exist in `lupo_actors` AND `lupo_agents`.
2. **Supporting human** MUST exist in `lupo_actors` AND `lupo_auth_users`.
3. **Both actors** MUST have `is_deleted = 0` and `is_active = 1`.
4. **Actor ID ranges** MUST be enforced at insert time.
5. **Database Connection** MUST be available for validation; fallback to offline mode if unavailable.

### 7.3 Offline Validation (MD-Only Mode)
1. **Header Format** MUST be validated syntactically.
2. **ID Ranges** MUST be checked (agent < 10000, human >= 10000).
3. **Actor Existence** CANNOT be validated without database connection.
4. **Warning** SHOULD be logged when validation occurs in offline mode.
5. **Deferred Validation** SHOULD be performed when database becomes available.

### 7.4 PHP Validation Example

```php
<?php
/**
 * Validate x_lupo_forwarded header against database
 * 
 * @param string $x_lupo_forwarded Format: "acting_agent_id:supporting_human_id"
 * @param bool $database_online Whether database connection is available
 * @return array ['valid' => bool, 'errors' => array, 'mode' => 'online'|'offline']
 */
function validate_x_lupo_forwarded($x_lupo_forwarded, $database_online = true) {
    $db = DatabaseFactory::getConnection();
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    // Parse header
    $parts = explode(':', $x_lupo_forwarded);
    if (count($parts) !== 2) {
        return ['valid' => false, 'errors' => ['Invalid format'], 'mode' => 'offline'];
    }
    
    list($acting_agent_id, $supporting_human_id) = $parts;
    $errors = [];
    
    // Basic syntactic validation (always performed)
    if ($acting_agent_id >= 10000) {
        $errors[] = "Acting agent ID must be < 10000";
    }
    
    if ($supporting_human_id < 10000) {
        $errors[] = "Supporting human ID must be >= 10000";
    }
    
    // Database validation (only if online)
    if ($database_online && $db) {
        // Validate acting agent (must be < 10000)
        $agent = $db->fetchOne(
            "SELECT a.actor_id, a.is_active, a.is_deleted, ag.agent_id AS agent_exists
             FROM {$prefix}actors a
             LEFT JOIN {$prefix}agents ag ON a.actor_id = ag.agent_id
             WHERE a.actor_id = :id",
            ['id' => $acting_agent_id]
        );
        
        if (!$agent) {
            $errors[] = "Acting agent {$acting_agent_id} not found";
        } elseif ($agent['is_deleted'] == 1) {
            $errors[] = "Acting agent {$acting_agent_id} is deleted";
        } elseif (!$agent['agent_exists']) {
            $errors[] = "Acting agent {$acting_agent_id} not in lupo_agents";
        }
        
        // Validate supporting human (must be >= 10000)
        $human = $db->fetchOne(
            "SELECT a.actor_id, a.is_active, a.is_deleted, au.auth_user_id AS user_exists
             FROM {$prefix}actors a
             LEFT JOIN {$prefix}auth_users au ON a.actor_id = au.auth_user_id
             WHERE a.actor_id = :id",
            ['id' => $supporting_human_id]
        );
        
        if (!$human) {
            $errors[] = "Supporting human {$supporting_human_id} not found";
        } elseif ($human['is_deleted'] == 1) {
            $errors[] = "Supporting human {$supporting_human_id} is deleted";
        } elseif (!$human['user_exists']) {
            $errors[] = "Supporting human {$supporting_human_id} not in lupo_auth_users";
        }
        
        $mode = 'online';
    } else {
        // Offline mode - log warning for deferred validation
        error_log("OFFLINE VALIDATION: x_lupo_forwarded '{$x_lupo_forwarded}' validated syntactically only. Database validation deferred.");
        $mode = 'offline';
        
        // Add informational note about offline validation
        $errors[] = "OFFLINE MODE: Database validation deferred until connection available";
    }
    
    return [
        'valid' => empty($errors) || ($mode === 'offline' && count($errors) === 1), // Allow offline warning
        'errors' => $errors,
        'acting_agent_id' => $acting_agent_id,
        'supporting_human_id' => $supporting_human_id,
        'mode' => $mode
    ];
}
```

## 8. PRACTICAL EXAMPLES

### 8.1 Valid IDE Agent Operation
```yaml
# FLIP Header
actor_id: 1003
lupo_agent: "ide|antigravity"
x_lupo_forwarded: "1003:10000"
```

**Database State:**
- `lupo_actors`: actor_id=1003, actor_type='ai_agent', slug='antigravity'
- `lupo_agents`: agent_id=1003, agent_key='antigravity', archetype='ide_agent'
- `lupo_actors`: actor_id=10000, actor_type='human', slug='captain'
- `lupo_auth_users`: auth_user_id=10000, username='captain'

**Result:** ✅ VALID

### 8.2 Invalid: Agent ID Out of Range
```yaml
actor_id: 15000  # ERROR: AI agent ID must be < 10000
x_lupo_forwarded: "15000:10000"
```

**Result:** ❌ INVALID - Agent ID violates range constraint

### 8.3 Invalid: Human ID Out of Range
```yaml
actor_id: 1003
x_lupo_forwarded: "1003:5000"  # ERROR: Human ID must be >= 10000
```

**Result:** ❌ INVALID - Human ID violates range constraint

### 8.4 Autonomous Agent (No Supporting Human)
```yaml
actor_id: 1001
lupo_agent: "kernel|kiro"
# x_lupo_forwarded: OMITTED (system-authorized)
```

**Database State:**
- `lupo_actors`: actor_id=1001, actor_type='ai_agent'
- `lupo_agents`: agent_id=1001, is_global_authority=1

**Result:** ✅ VALID (autonomous system agent)

### 8.5 Offline Mode Validation (Database Unavailable)
```yaml
actor_id: 1003
lupo_agent: "ide|antigravity"
x_lupo_forwarded: "1003:10000"
```

**Validation Result (Offline):**
- ✅ **Syntax Valid**: Format correct, ID ranges valid
- ⚠️ **Database Deferred**: Actor existence cannot be validated
- 📝 **Warning Logged**: "OFFLINE MODE: Database validation deferred until connection available"

**Result:** ✅ ACCEPTABLE (with warning for deferred validation)

---

## 9. OFFLINE MODE CONSIDERATIONS

### 9.1 When Database is Unavailable
1. **Syntactic Validation** MUST still be performed
2. **ID Range Validation** MUST be enforced (agent < 10000, human >= 10000)
3. **Format Validation** MUST be enforced (colon-separated, no spaces)
4. **Warning Logging** SHOULD record deferred validations
5. **Queue for Revalidation** SHOULD track files needing database validation

### 9.2 Deferred Validation Queue
```sql
-- Table to track files needing database validation when offline
CREATE TABLE lupo_deferred_validations (
    deferred_validation_id BIGINT NOT NULL PRIMARY KEY,
    file_path_from_root VARCHAR(500) NOT NULL,
    validation_type VARCHAR(50) NOT NULL, -- 'actor_validation', 'flip_validation'
    header_value TEXT NOT NULL, -- The raw header value
    validation_status VARCHAR(20) DEFAULT 'pending', -- 'pending', 'validated', 'failed'
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    validated_ymdhis BIGINT,
    validation_errors TEXT,
    is_deleted TINYINT NOT NULL DEFAULT 0
);
```

### 9.3 Revalidation Process
```php
<?php
/**
 * Process deferred validations when database becomes available
 */
function process_deferred_validations() {
    $db = DatabaseFactory::getConnection();
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    // Get pending actor validations
    $deferred = $db->fetchAll(
        "SELECT * FROM {$prefix}deferred_validations 
         WHERE validation_type = 'actor_validation' 
         AND validation_status = 'pending' 
         AND is_deleted = 0
         ORDER BY created_ymdhis ASC"
    );
    
    foreach ($deferred as $item) {
        $result = validate_x_lupo_forwarded($item['header_value'], true);
        
        $db->update(
            "{$prefix}deferred_validations",
            [
                'validation_status' => $result['valid'] ? 'validated' : 'failed',
                'validated_ymdhis' => (int) gmdate('YmdHis'),
                'validation_errors' => json_encode($result['errors'])
            ],
            ['deferred_validation_id' => $item['deferred_validation_id']]
        );
    }
}
```

---
### 7.5 Validation Severity Levels
| Level | Status | User Experience | Action Required |
|-------|--------|-----------------|-----------------|
| `INFO` | ✅ Valid | Green indicator | None |
| `WARNING` | ⚠️ Soft-Fail | Yellow indicator | Recommendation provided; operation continues |
| `ERROR` | ❌ Hard-Fail | Red indicator | Operation blocked until remediated |

### 7.6 Remediation Suggestions
Validation results SHOULD include machine-readable remediation codes:
- `ERR_ACTOR_MISSING`: "Create actor record in lupo_actors"
- `ERR_ID_RANGE`: "Adjust ID to correct range (<10k for agents, >=10k for humans)"
- `WARN_OFFLINE`: "Offline Mode: Validate syntactically, defer DB check"

---

## 8. PRACTICAL EXAMPLES

### 8.1 Valid IDE Agent Operation
```yaml
# FLIP Header
actor_id: 1003
lupo_agent: "ide|antigravity"
x_lupo_forwarded: "1003:10000"
```

**Database State:**
- `lupo_actors`: actor_id=1003, actor_type='ai_agent', slug='antigravity'
- `lupo_agents`: agent_id=1003, agent_key='antigravity', archetype='ide_agent'
- `lupo_actors`: actor_id=10000, actor_type='human', slug='captain'
- `lupo_auth_users`: auth_user_id=10000, username='captain'

**Result:** ✅ VALID

### 8.2 Invalid: Agent ID Out of Range
```yaml
actor_id: 15000  # ERROR: AI agent ID must be < 10000
x_lupo_forwarded: "15000:10000"
```

**Result:** ❌ INVALID - Agent ID violates range constraint

### 8.3 Invalid: Human ID Out of Range
```yaml
actor_id: 1003
x_lupo_forwarded: "1003:5000"  # ERROR: Human ID must be >= 10000
```

**Result:** ❌ INVALID - Human ID violates range constraint

### 8.4 Autonomous Agent (No Supporting Human)
```yaml
actor_id: 1001
lupo_agent: "kernel|kiro"
# x_lupo_forwarded: OMITTED (system-authorized)
```

**Database State:**
- `lupo_actors`: actor_id=1001, actor_type='ai_agent'
- `lupo_agents`: agent_id=1001, is_global_authority=1

**Result:** ✅ VALID (autonomous system agent)

### 8.5 Offline Mode Validation (Database Unavailable)
```yaml
actor_id: 1003
lupo_agent: "ide|antigravity"
x_lupo_forwarded: "1003:10000"
```

**Validation Result (Offline):**
- ✅ **Syntax Valid**: Format correct, ID ranges valid
- ⚠️ **Database Deferred**: Actor existence cannot be validated
- 📝 **Warning Logged**: "OFFLINE MODE: Database validation deferred until connection available"

**Result:** ✅ ACCEPTABLE (with warning for deferred validation)

---

## 9. OFFLINE MODE CONSIDERATIONS

### 9.1 When Database is Unavailable
1. **Syntactic Validation** MUST still be performed
2. **ID Range Validation** MUST be enforced (agent < 10000, human >= 10000)
3. **Format Validation** MUST be enforced (colon-separated, no spaces)
4. **Warning Logging** SHOULD record deferred validations
5. **Queue for Revalidation** SHOULD track files needing database validation

### 9.2 Deferred Validation Queue
```sql
-- Table to track files needing database validation when offline
CREATE TABLE lupo_deferred_validations (
    deferred_validation_id BIGINT NOT NULL PRIMARY KEY,
    file_path_from_root VARCHAR(500) NOT NULL,
    validation_type VARCHAR(50) NOT NULL, -- 'actor_validation', 'flip_validation'
    header_value TEXT NOT NULL, -- The raw header value
    validation_status VARCHAR(20) DEFAULT 'pending', -- 'pending', 'validated', 'failed'
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    validated_ymdhis BIGINT,
    validation_errors TEXT,
    is_deleted TINYINT NOT NULL DEFAULT 0
);
```

### 9.3 Revalidation Process
```php
<?php
/**
 * Process deferred validations when database becomes available
 */
function process_deferred_validations() {
    $db = DatabaseFactory::getConnection();
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    // Get pending actor validations
    $deferred = $db->fetchAll(
        "SELECT * FROM {$prefix}deferred_validations 
         WHERE validation_type = 'actor_validation' 
         AND validation_status = 'pending' 
         AND is_deleted = 0
         ORDER BY created_ymdhis ASC"
    );
    
    foreach ($deferred as $item) {
        $result = validate_x_lupo_forwarded($item['header_value'], true);
        
        $db->update(
            "{$prefix}deferred_validations",
            [
                'validation_status' => $result['valid'] ? 'validated' : 'failed',
                'validated_ymdhis' => (int) gmdate('YmdHis'),
                'validation_errors' => json_encode($result['errors'])
            ],
            ['deferred_validation_id' => $item['deferred_validation_id']]
        );
    }
}
?>
```

---

## 10. ACTOR LIFECYCLE MANAGEMENT

### 10.1 Actor States
| State | Description | Allowed Actions | Transition Triggers |
|-------|-------------|-----------------|---------------------|
| `pending` | Awaiting approval | None | Registration submitted |
| `active` | Fully operational | All permitted by role | Approval by authority |
| `suspended` | Temporary restriction | Read-only | Policy violation review |
| `banned` | Permanent removal | None | Severe violation, Captain override |
| `archived` | Historical record | None | Project completion, retirement |

### 10.2 State Transition Matrix
```sql
CREATE TABLE lupo_actor_state_history (
    state_history_id BIGINT NOT NULL PRIMARY KEY,
    actor_id BIGINT NOT NULL,
    previous_state VARCHAR(20),
    new_state VARCHAR(20) NOT NULL,
    reason TEXT,
    changed_by_actor_id BIGINT NOT NULL,
    changed_ymdhis BIGINT NOT NULL,
    metadata JSON
);
CREATE INDEX idx_actor_timeline ON lupo_actor_state_history (actor_id, changed_ymdhis);
```

---

## 11. FEDERATED ACTOR MODEL

### 11.1 Instance Qualification
Each Lupopedia instance has a unique `instance_id` (1-65535).

### 11.2 Federated Actor IDs
**Format:** `instance_id:actor_id`
- `1:1003` (Antigravity on Node 1)
- `2:1003` (Antigravity on Node 2)

### 11.3 Trust Relationships
- **Full Trust**: Shared actor databases (Hot-Sync)
- **Limited Trust**: Validated via signed JWT/Inter-agent protocol
- **Zero Trust**: Local re-validation required for every operation

---

## 12. ACTOR DELETION AND RETENTION

### 12.1 Deletion Types
- **Soft Delete**: `is_deleted = 1`. Flag only, all data retained for 90 days.
- **Anonymized**: PII scrubbed from `lupo_auth_users`, metadata retained for legal/audit (7 years).
- **Hard Delete**: Irreversible removal (Captain override required).

### 12.2 Deletion Audit
```sql
CREATE TABLE lupo_actor_deletions (
    deletion_id BIGINT NOT NULL PRIMARY KEY,
    actor_id BIGINT NOT NULL,
    deletion_type VARCHAR(20) NOT NULL,
    requested_by BIGINT NOT NULL,
    approved_by BIGINT NOT NULL,
    executed_ymdhis BIGINT NOT NULL,
    data_retention_period INT,
    legal_hold BOOLEAN DEFAULT FALSE
);
```

---

## 13. DELEGATION CHAINS (Multi-Level)

### 13.1 Chain Encoding
Lupopedia supports multi-level delegation via the expansion of `x_lupo_forwarded`:
`acting_agent_id:delegating_agent_id:authorizing_human_id`

**Example:** `1003:1001:10000` (Antigravity acting via KIRO acting via Captain).

### 13.2 Recursive Chain Validation
```sql
WITH RECURSIVE delegation_depth AS (
    SELECT actor_id, delegated_by, 1 as depth
    FROM actor_delegations
    WHERE actor_id = :target_actor
    UNION ALL
    SELECT a.actor_id, a.delegated_by, d.depth + 1
    FROM actor_delegations a
    JOIN delegation_depth d ON a.actor_id = d.delegated_by
)
SELECT * FROM delegation_depth;
```

---

## 14. PERFORMANCE AND MONITORING

### 14.1 Agent Capability Declaration
Agents MUST declare capabilities in `lupo_agents` via a `capabilities` JSON blob:
```json
{
    "can_execute_sql": true,
    "can_modify_files": true,
    "requires_human_oversight": true,
    "rate_limit": 100
}
```

### 14.2 Health Monitoring
The system monitors the "Semantic Heartbeat" of active agents:
- **Ping**: Availability check.
- **P95 Latency**: Response time tracking.
- **Success Rate**: Ratio of valid vs failed tasks.

---

**Authority:** Captain Wolfie  
**Verification:** Antigravity (1003) & LILITH (2038)
**Database Schema Version:** 4.1.0-draft
**Last Updated:** 20260224
