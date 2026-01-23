---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: SYSTEM
  target: @everyone
  mood_RGB: "00FF00"
  message: "Grounded agent system documentation completed. Non-mythical, architectural clarity achieved."
tags:
  categories: ["documentation", "architecture", "agents"]
  collections: ["core-docs", "architecture"]
  channels: ["dev", "public", "internal"]
file:
  title: "Grounded Agent System Architecture"
  description: "Clear, non-mythical documentation of Lupopedia agent system and Five Pillars implementation"
  version: "1.0.0"
  status: published
  author: GLOBAL_CURRENT_AUTHORS
  artifact: "Architecture Documentation"
  thread: "GROUNDED_ARCHITECTURE"
  mode: "Documentation Mode"
  location: "Architecture Layer"
  severity: "Critical"
  stability: "Stable"
  primary_agents: "SYSTEM, WOLFIE, THOTH, ARA"
  event_summary: "Complete grounded documentation of agent system and Five Pillars in code terms"
  governance: "GENESIS Doctrine v1.0.0"
  filed_under: "Architecture > Agents > Grounded"
---

# Grounded Agent System Architecture

## 🟦 Agent Map (Grounded Version)

Agents in Lupopedia are modules or services with specific responsibilities. They are represented in the database and have code that defines their behavior.

### Core Agents (Examples)

| Agent ID | Code | Name | Description (Function) |
|-----------|------|------|----------------------|
| 0 | SYSTEM | System | The system kernel. Handles bootstrapping, core events, and low-level operations. |
| 1 | CAPTAIN | Captain Wolfie | The architect's persona. Used to log actions performed by human architect (Eric). |
| 2 | WOLFIE | Wolfie | Doctrine and governance agent. Validates system integrity, enforces rules, and checks migrations. |
| 3 | THOTH | Thoth | Knowledge agent. Manages documents, search, and semantic relationships. |
| 4 | ARA | Ara | Routing agent. Handles request routing, API endpoints, and service discovery. |
| 5 | ROSE | Rose | Emotional metadata agent. Attaches emotional context to content (like tags, sentiment). |
| ... | ... | ... | ... (up to 128 agents) |

### Database Schema

#### lupo_agents Table
```sql
CREATE TABLE lupo_agents (
    agent_id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    actor_id BIGINT UNSIGNED, -- NULL for system-owned agents
    owner_actor_id BIGINT UNSIGNED, -- Human actor who owns/controls this agent
    created_ymdhis BIGINT UNSIGNED NOT NULL,
    updated_ymdhis BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Note**: The `actor_id` links to the actor (human or other) that owns or manages the agent. For system agents (like WOLFIE), this might be NULL. The `owner_actor_id` specifies which human actor controls this agent.

#### lupo_actors Table
```sql
CREATE TABLE lupo_actors (
    actor_id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE, -- for humans
    type ENUM('human', 'agent', 'service') NOT NULL,
    created_ymdhis BIGINT UNSIGNED NOT NULL,
    updated_ymdhis BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Identity Flow

1. **Human Registration**: Eric (human) registers with email `lupopedia@gmail.com`
   - Creates record in `lupo_actors` with `type='human'`
   - Gets `actor_id` (e.g., 42)

2. **Agent Creation**: Captain Wolfie agent is created
   - Creates record in `lupo_actors` with `type='agent'` (actor_id: 43)
   - Creates record in `lupo_agents` with:
     - `agent_id`: 1 (internal agent ID)
     - `code`: 'CAPTAIN'
     - `name`: 'Captain Wolfie'
     - `actor_id`: 43 (links to actor record)
     - `owner_actor_id`: 42 (Eric's actor_id)

3. **Agent Usage**: When Eric uses Captain Wolfie persona
   - System validates that Eric (actor_id: 42) owns Captain Wolfie (owner_actor_id: 42)
   - Actions are logged with `actor_id=43` and `agent_id=1`

### Agent Ownership Table (Optional)
```sql
CREATE TABLE lupo_agent_owners (
    agent_id BIGINT UNSIGNED,
    owner_actor_id BIGINT UNSIGNED,
    granted_ymdhis BIGINT UNSIGNED NOT NULL,
    is_active TINYINT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🟩 Five Pillars in Code Terms

The Five Pillars are architectural principles. Here's how they translate to actual code and database design.

### Pillar 1: Actor-Centric Identity

**Implementation**: Every entity that can perform an action is an actor and has a record in `lupo_actors`.

**Actions are logged** with `actor_id` and `agent_id` (if applicable).

#### Example Action Logging Table
```sql
CREATE TABLE lupo_actor_actions (
    action_id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    actor_id BIGINT UNSIGNED NOT NULL,
    agent_id BIGINT UNSIGNED, -- NULL if not using an agent
    action_type VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id BIGINT,
    description TEXT,
    metadata TEXT,
    created_ymdhis BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Pillar 2: Federated Independence

**Implementation**: Each Lupopedia instance (node) has its own database and codebase.

**No shared database** across nodes. Communication via APIs.

#### Example Federation
- **Node A**: Separate installation with its own `lupo_actors` and `lupo_agents`
- **Node B**: Separate installation with its own tables
- **Communication**: REST APIs for data exchange
- **Independence**: Each node can operate without the other

### Pillar 3: Doctrine-Driven Development

**Implementation**: Doctrine is a set of rules stored in plain text files.

#### Example Doctrine File
```
# doctrine/timestamp_rules.txt
Rule: All timestamps must be BIGINT in YYYYMMDDHHIISS format.
Validation: Check that every table has a created_ymdhis and updated_ymdhis column of type BIGINT.
Enforcement: WOLFIE agent validates this rule during migrations.
```

#### Doctrine Validation Class
```php
class DoctrineValidator {
    public function validateTimestampRule($table_schema) {
        foreach ($table_schema['columns'] as $column) {
            if (strpos($column['name'], 'ymdhis') !== false) {
                if ($column['type'] !== 'BIGINT') {
                    return ['valid' => false, 'error' => 'Timestamp must be BIGINT'];
                }
            }
        }
        return ['valid' => true];
    }
}
```

### Pillar 4: Schema Mapping (TOON Files)

**Implementation**: TOON (Textual Object-Oriented Notation) files define database schema in human-readable format.

#### Example TOON File
```toon
table: lupo_actors
  columns:
    actor_id: bigint, primary, auto_increment
    email: varchar(255), unique
    type: enum('human', 'agent', 'service')
    created_ymdhis: bigint
    updated_ymdhis: bigint
  engine: InnoDB
  charset: utf8mb4
```

#### TOON Parser
```php
class TOONParser {
    public function parseFile($filename) {
        $content = file_get_contents($filename);
        return $this->parseToonFormat($content);
    }
    
    public function generateMigration($toon_schema) {
        $sql = $this->convertToSQL($toon_schema);
        return $sql;
    }
}
```

### Pillar 5: Temporal Integrity

**Implementation**: All tables have `created_ymdhis` and `updated_ymdhis` columns.

#### Temporal Helper Class
```php
class TemporalHelper {
    public static function now() {
        return date('YmdHis'); // UTC
    }
    
    public static function formatTimestamp($datetime) {
        return date('YmdHis', strtotime($datetime));
    }
    
    public static function isValidTimestamp($ymdhis) {
        return (strlen($ymdhis) === 14) && is_numeric($ymdhis);
    }
}
```

#### Example Usage
```php
// When inserting a record
$timestamp = TemporalHelper::now();
$query = "INSERT INTO lupo_actors (email, type, created_ymdhis, updated_ymdhis) VALUES (?, ?, ?, ?)";
$stmt->bind_param('sssi', $email, $type, $timestamp, $timestamp);
```

---

## 🧩 How the Pieces Fit Together

### Identity Flow Example

1. **Eric (human) logs in** with email `lupopedia@gmail.com`
   ```php
   $actor = $actor_model->getByEmail('lupopedia@gmail.com');
   // Returns actor_id: 42, type: 'human'
   ```

2. **Eric selects Captain Wolfie agent**
   ```php
   $agent = $agent_model->getByCode('CAPTAIN');
   // Returns agent_id: 1, owner_actor_id: 42
   ```

3. **Permission check**
   ```php
   if ($agent->owner_actor_id === $actor->actor_id) {
       // Eric can use Captain Wolfie
       $session->setActiveAgent($agent);
   }
   ```

4. **Action performed**
   ```php
   $action_logger->log([
       'actor_id' => $agent->actor_id, // 43 (Captain Wolfie's actor record)
       'agent_id' => $agent->agent_id, // 1
       'action_type' => 'create_document',
       'description' => 'Created new knowledge document'
   ]);
   ```

### No Foreign Keys Doctrine Compliance

**Doctrine**: "NO FK KEYS" - This is a core part of Lupopedia architecture.

**Implementation**:
- We have `actor_id` in `lupo_agents` that references `lupo_actors.actor_id`
- We do **not** create a foreign key constraint in the database
- Application logic ensures referential integrity

#### Referential Integrity in Code
```php
class AgentModel {
    public function create($data) {
        // Step 1: Create actor record if needed
        if (!isset($data['actor_id'])) {
            $actor_id = $this->createActorRecord([
                'type' => 'agent',
                'created_ymdhis' => TemporalHelper::now(),
                'updated_ymdhis' => TemporalHelper::now()
            ]);
            $data['actor_id'] = $actor_id;
        }
        
        // Step 2: Create agent record
        $agent_id = $this->insert('lupo_agents', $data);
        
        // Step 3: Verify relationship exists (application-level FK)
        $actor = $this->getActor($data['actor_id']);
        if (!$actor) {
            throw new Exception('Referential integrity violation');
        }
        
        return $agent_id;
    }
}
```

---

## 🏗️ Complete System Architecture

### Core Tables Summary

| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `lupo_actors` | All entities that can perform actions | `actor_id`, `type`, `email` |
| `lupo_agents` | Agent definitions and capabilities | `agent_id`, `code`, `actor_id`, `owner_actor_id` |
| `lupo_actor_actions` | Audit trail of all actions | `actor_id`, `agent_id`, `action_type`, `created_ymdhis` |
| `lupo_agent_owners` | Agent ownership permissions | `agent_id`, `owner_actor_id`, `granted_ymdhis` |

### Key Design Principles

1. **Actor-First**: Everything that can act has an actor record
2. **No FK Constraints**: Application-level referential integrity
3. **Temporal Consistency**: All tables use `YYYYMMDDHHMMSS` format
4. **Doctrine-Driven**: Rules stored in plain text, enforced by code
5. **Federated**: Each node operates independently

### Code Organization

```
/lupo-includes/
├── models/
│   ├── ActorModel.php          # lupo_actors operations
│   ├── AgentModel.php          # lupo_agents operations
│   └── ActionModel.php         # lupo_actor_actions operations
├── classes/
│   ├── TemporalHelper.php      # Timestamp utilities
│   ├── DoctrineValidator.php    # Rule enforcement
│   └── TOONParser.php         # Schema parsing
└── agents/
    ├── SYSTEM/                 # Core system agent
    ├── CAPTAIN/               # Captain Wolfie agent
    ├── WOLFIE/               # Doctrine enforcement agent
    └── THOTH/                 # Knowledge management agent
```

---

## 📋 Implementation Checklist

### Database Setup
- [ ] Create `lupo_actors` table
- [ ] Create `lupo_agents` table  
- [ ] Create `lupo_actor_actions` table
- [ ] Create `lupo_agent_owners` table
- [ ] Add appropriate indexes for performance

### Code Implementation
- [ ] Implement `ActorModel` class
- [ ] Implement `AgentModel` class
- [ ] Implement `ActionModel` class
- [ ] Implement `TemporalHelper` class
- [ ] Implement `DoctrineValidator` class
- [ ] Implement `TOONParser` class

### Agent Development
- [ ] Create base agent class
- [ ] Implement SYSTEM agent
- [ ] Implement CAPTAIN agent
- [ ] Implement WOLFIE agent
- [ ] Implement THOTH agent

### Doctrine Integration
- [ ] Create doctrine files directory
- [ ] Write timestamp rules doctrine
- [ ] Write no-FK doctrine
- [ ] Write temporal integrity doctrine
- [ ] Implement doctrine validation in agents

---

## 🎯 Grounded Architecture Achieved

This documentation provides a **clear, non-mythical foundation** for understanding and implementing the Lupopedia agent system and Five Pillars in concrete code and database terms.

**Key Principles**:
- **Actors are identity records** in the database
- **Agents are specialized actors** with additional capabilities
- **No foreign keys** - application-level integrity only
- **Temporal integrity** - BIGINT timestamps in UTC format
- **Doctrine-driven** - rules in plain text, enforced by code
- **Federated** - independent nodes with API communication

The system is now **fully documented** in grounded, architectural terms that can be directly implemented by developers.
