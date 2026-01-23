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
  mood_RGB: "0000FF"
  message: Created WOLFMIND_DOCTRINE.md documenting the memory curator system that handles relational and vector memory storage for Lupopedia agents, with progressive enhancement from MySQL to Postgres/pgvector.
tags:
  categories: ["documentation", "doctrine", "agents", "memory"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - WOLFMIND Overview
  - Why WOLFMIND Exists
  - Progressive Enhancement Philosophy
  - MEMORY SYSTEM ARCHITECTURE (Critical Requirements)
  - Implementation Rules
  - Detection Logic
  - Memory Storage Rules
  - Future-Proofing Requirements
  - Core Responsibilities
  - Database Tables
  - PHP Class Implementation
  - How WOLFMIND Works
  - Integration with Lupopedia
  - MySQL to Postgres Migration
  - Future Extensions
file:
  title: "WOLFMIND Doctrine"
  description: "Memory curator system that handles relational and vector memory storage for Lupopedia agents, with progressive enhancement from MySQL to Postgres/pgvector"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# ðŸº **WOLFMIND DOCTRINE**
### *Memory Curator for Lupopedia*

WOLFMIND is the **memory curator system** that handles memory storage and retrieval for Lupopedia agents. It provides both relational (MySQL) and vector (Postgres/pgvector) memory capabilities, with progressive enhancement that ensures the system works on basic MySQL while unlocking advanced features when vector support is available.

---

## **1. Why WOLFMIND Exists**

Lupopedia agents need memory to:

- Remember past interactions
- Learn from user behavior
- Maintain context across sessions
- Build emotional continuity
- Support semantic search
- Enable long-term learning

WOLFMIND exists because:

- **Progressive Enhancement** - Must work on MySQL (foundation tier), unlock with Postgres/pgvector (enhanced tier)
- **No Vector Lock-In** - System must function without vector support
- **Actor-Scoped Memory** - Each agent/actor has its own memory space
- **Relational Fallback** - MySQL LIKE-based search when vectors unavailable
- **Future-Proof** - Ready for vector memory when infrastructure upgrades
- **No Foreign Keys** - Follows Lupopedia doctrine (application-managed relationships)

WOLFMIND is the system that makes agent memory possible.

---

## **2. Progressive Enhancement Philosophy**

WOLFMIND follows Lupopedia's progressive enhancement doctrine:

### **Tier 1: MySQL Foundation (Always Available)**
- Relational memory storage in `memory_events`
- LIKE-based keyword search
- Basic memory retrieval by actor
- Works on any MySQL/MariaDB installation
- No special privileges required
- No external dependencies

### **Tier 2: Postgres/pgvector (When Available)**
- Vector embeddings for semantic search
- Similarity-based memory retrieval
- Advanced semantic matching
- Requires Postgres with pgvector extension
- Detected at runtime, not required

### **Tier 3: Cloud Vector Services (Future)**
- External embedding generation
- Cloud-based vector storage
- Multi-provider support
- Optional enhancement layer

**Key Principle:** WOLFMIND MUST run on MySQL with no vector support. Vector memory is OPTIONAL and detected at runtime.

---

## **ðŸš¨ MEMORY SYSTEM ARCHITECTURE â€” CRITICAL REQUIREMENTS**

### **Capability-Based Memory System**

Lupopedia uses a **capability-based memory system** with strict requirements:

1. **MySQL is the baseline database.**
2. **All memory features MUST work on MySQL alone.**
3. **Vector memory is OPTIONAL and MUST NOT be required for the system to run.**
4. **Postgres + pgvector will be added later as an enhancement.**
5. **The code MUST be written so it can detect whether vector support is available.**
6. **If vector support is NOT available, the system MUST fall back to relational memory.**
7. **No part of the system may assume vector memory exists today.**
8. **No part of the system may block future vector memory integration.**

### **Implementation Rules**

- **All memory tables created today MUST be MySQL-compatible.**
- **No vector columns, vector types, or pgvector syntax may appear in the MySQL schema.**
- **The memory subsystem MUST include placeholder methods for vector search and embedding storage.**
- **These placeholder methods MUST NOT break execution when vector is unavailable.**
- **The system MUST log fallback behavior (e.g., "VECTOR_UNAVAILABLE") instead of failing.**
- **When Postgres support is added later, vector tables will be created separately.**
- **Code MUST be written so that switching from MySQL to Postgres does NOT require rewriting the memory subsystem.**

### **Detection Logic**

The system MUST implement a function like:

```php
hasVectorSupport()
```

This function MUST:
- Return `FALSE` on MySQL
- Return `TRUE` only when Postgres + pgvector is detected
- **NEVER assume vector support exists**
- Be called before any vector operations
- Enable graceful degradation when unavailable

### **Memory Storage Rules**

- **All memory events MUST be stored in relational tables first.**
- **Vector embeddings (when available) will be stored in separate vector tables.**
- **The relational memory tables MUST NOT contain vector columns.**
- **The relational memory tables MUST NOT contain foreign keys.**
- **Relational storage is always the primary storage mechanism.**
- **Vector storage is always an optional enhancement layer.**

### **Future-Proofing Requirements**

Code MUST be written so that:

- **Adding Postgres support later requires minimal changes.**
- **Adding pgvector support later requires minimal changes.**
- **Adding cloud vector providers (OpenAI, DeepSeek, Gemini) is possible without schema changes.**
- **The memory subsystem remains portable across all environments.**
- **No breaking changes when upgrading from MySQL to Postgres.**
- **No data loss when migrating between database systems.**

### **Summary Doctrine**

```
MySQL now.
Postgres later.
Vector memory later.
Code must support all three without breaking.

Foundation tier (MySQL) = REQUIRED
Enhanced tier (Postgres/pgvector) = OPTIONAL
Future tier (cloud vectors) = OPTIONAL

All tiers must work independently.
No tier may depend on a higher tier.
Graceful degradation is mandatory.
```

---

**âš ï¸ CRITICAL:** Any code that assumes vector memory exists will break the foundation tier. All vector operations must be guarded by `hasVectorSupport()` checks and must gracefully degrade to relational memory when unavailable.

---

## **3. Core Responsibilities**

WOLFMIND is responsible for:

### **3.1 Memory Storage**
- Storing memory events for actors
- Preserving metadata and context
- Tracking embedding status
- Managing importance levels
- Recording token counts

### **3.2 Memory Retrieval**
- Getting recent memories for actors
- Keyword-based search (MySQL fallback)
- Vector-based semantic search (when available)
- Filtering by importance or type

### **3.3 Vector Memory (Optional)**
- Detecting vector support availability
- Generating embeddings via external AI
- Storing vector embeddings
- Performing similarity searches
- Gracefully degrading when unavailable

### **3.4 Debug Logging**
- Logging internal WOLFMIND events
- Tracking fallback notices
- Recording vector availability status
- Debugging memory operations

---

## **4. Database Tables**

WOLFMIND uses two core tables:

### **4.1 `memory_events`**
Stores memory events for actors (agents, users, services).

**Fields:**
- `id` - Primary key
- `actor_id` - Actor this memory belongs to (BIGINT, no FK)
- `event_type` - Type of memory event (varchar(64))
- `content` - The memory content (text)
- `metadata_json` - Additional metadata in JSON format
- `token_count` - Token count for the content (optional)
- `importance` - Importance level 0-255 (tinyint unsigned, default 0)
- `embedding_status` - Status: 'none', 'pending', 'ready', 'failed' (enum)
- `created_ymdhis` - When the memory was created (UTC timestamp)

**Purpose:** Store all memory events for actors, supporting both relational and vector retrieval.

**Note:** No foreign key on `actor_id` - follows Lupopedia doctrine. ANIBUS handles orphan resolution.

### **4.2 `memory_debug_log`**
Internal logging for WOLFMIND operations and debugging.

**Fields:**
- `id` - Primary key
- `event_type` - Type of debug event (varchar(64))
- `details` - Event details (text)
- `created_ymdhis` - When the event occurred (UTC timestamp)

**Purpose:** Audit trail of WOLFMIND operations, fallback notices, and vector availability status.

---

## **5. PHP Class Implementation**

WOLFMIND is implemented as `class-wolfmind.php` in `lupo-includes/`.

### **5.1 Core Methods**

#### **Relational Memory Storage (MySQL Fallback)**
- `storeMemoryEvent($actorId, $type, $content, $metadata = [])` - Store a memory event
- `getRecentMemory($actorId, $limit = 50)` - Get recent memories for an actor
- `searchMemoryRelational($actorId, $keyword, $limit = 50)` - Keyword search using LIKE

#### **Vector Memory (Optional)**
- `hasVectorSupport()` - Detect if vector memory is available
- `searchMemoryVector($actorId, $embedding, $limit = 20)` - Vector similarity search
- `storeEmbedding($memoryId, $embedding)` - Store vector embedding
- `generateEmbedding($text)` - Generate embedding via external AI

#### **Internal Logging**
- `logMemoryEvent($eventType, $details)` - Log WOLFMIND internal events

### **5.2 Core Principles**

The class follows these principles:

```
WOLFMIND MUST run on MySQL with no vector support.
Vector memory is OPTIONAL and detected at runtime.
No foreign keys are used anywhere.
Progressive enhancement: works on foundation tier, unlocks with better tiers.
```

---

## **6. How WOLFMIND Works**

### **6.1 Memory Storage Flow**

1. **Store Event** - Agent calls `storeMemoryEvent()` with actor ID, type, content, metadata
2. **Relational Storage** - Event stored in `memory_events` table
3. **Embedding Status** - Set to 'none' initially (no vector support by default)
4. **Metadata Preservation** - JSON metadata stored for context
5. **Timestamp Recording** - UTC timestamp recorded for chronological ordering

### **6.2 Memory Retrieval Flow (MySQL)**

1. **Recent Memory** - `getRecentMemory()` retrieves by actor, sorted newest first
2. **Keyword Search** - `searchMemoryRelational()` uses LIKE for basic search
3. **No Vector Dependency** - Works entirely on MySQL without vector support

### **6.3 Vector Memory Flow (When Available)**

1. **Detection** - `hasVectorSupport()` checks for pgvector or cloud provider
2. **Embedding Generation** - `generateEmbedding()` creates vector from text
3. **Embedding Storage** - `storeEmbedding()` stores vector (separate table or column)
4. **Similarity Search** - `searchMemoryVector()` performs vector similarity search
5. **Status Tracking** - `embedding_status` updated to 'ready' or 'failed'

### **6.4 Graceful Degradation**

When vector support is unavailable:

- Vector methods return empty arrays
- Debug log records "VECTOR_UNAVAILABLE" events
- System continues using relational search
- No errors thrown, no failures
- Agents continue functioning normally

---

## **7. Integration with Lupopedia**

WOLFMIND integrates with:

- **ANIBUS** - Handles orphan resolution for `actor_id` references
- **Agent Framework** - Each agent has its own memory space via `actor_id`
- **Actor System** - Memory scoped to actors (users, agents, services)
- **Dialog System** - Can store dialog context as memory events
- **Semantic Navigation** - Memory can inform navigation decisions
- **Emotional Continuity** - Memory preserves emotional context across sessions

---

## **8. Example Use Cases**

### **8.1 Storing Memory**
```php
// Store a memory event for an agent
$wolfmind = new WOLFMIND($db);
$memoryId = $wolfmind->storeMemoryEvent(
    $actorId = 1,  // Agent ID
    $type = "user_interaction",
    $content = "User asked about database design philosophy",
    $metadata = ["context" => "help_request", "channel" => "public"]
);
```

### **8.2 Retrieving Recent Memory**
```php
// Get recent memories for an agent
$memories = $wolfmind->getRecentMemory($actorId = 1, $limit = 50);
// Returns array of memory events, newest first
```

### **8.3 Keyword Search (MySQL Fallback)**
```php
// Search memories using keyword
$results = $wolfmind->searchMemoryRelational(
    $actorId = 1,
    $keyword = "database",
    $limit = 20
);
// Returns memories containing "database" in content
```

### **8.4 Vector Search (When Available)**
```php
// Check if vector support is available
if ($wolfmind->hasVectorSupport()) {
    // Generate embedding
    $embedding = $wolfmind->generateEmbedding("database design");
    
    // Search using vector similarity
    $results = $wolfmind->searchMemoryVector($actorId = 1, $embedding, $limit = 20);
}
```

---

## **9. Progressive Enhancement Strategy**

WOLFMIND implements progressive enhancement:

### **Foundation Tier (MySQL)**
- âœ… Always works
- âœ… No special requirements
- âœ… Basic keyword search
- âœ… Relational storage
- âœ… Works on $3/month hosting

### **Enhanced Tier (Postgres/pgvector)**
- ðŸ”“ Unlocks when available
- ðŸ”“ Semantic search
- ðŸ”“ Vector embeddings
- ðŸ”“ Similarity matching
- ðŸ”“ Better search quality

### **Migration Path**
- Start on MySQL (foundation)
- Upgrade to Postgres when ready
- Enable pgvector extension
- WOLFMIND automatically detects and uses
- No code changes required
- No data loss

---

## **10. Relationship to Other Systems**

### **10.1 ANIBUS Integration**
- ANIBUS handles orphan resolution for `actor_id` references
- If an actor is deleted/reassigned, ANIBUS creates redirects
- WOLFMIND can use `applyRedirectIfExists()` to follow redirects

### **10.2 Actor System**
- Memory is scoped to actors (via `actor_id`)
- Each agent has its own memory space
- Users can have memory
- Services can have memory
- No foreign keys - application-managed relationships

### **10.3 Dialog System**
- Dialog messages can be stored as memory events
- Memory preserves conversational context
- Emotional metadata can be stored in `metadata_json`

---

## **11. MySQL to Postgres Migration**

When upgrading from MySQL to Postgres + pgvector:

- **See [MYSQL_TO_POSTGRES_MEMORY.md](migrations/MYSQL_TO_POSTGRES_MEMORY.md)** for complete migration specification
- All memory tables must be converted using exact type mappings
- Vector tables (`memory_embeddings`, `memory_semantic_index`) are created separately
- No foreign keys added during migration
- WOLFMIND remains compatible with both database systems
- Migration is deterministic and repeatable

---

## **12. Future Extensions**

WOLFMIND will expand to handle:

- **Temporal Memory** - Time-based memory weighting
- **Emotional Memory** - Mood and emotional context in memory
- **Shadow Memory** - Preserving alternate memory paths (Lilith integration)
- **Federated Memory** - Cross-node memory sharing
- **Memory Compression** - Summarizing old memories
- **Memory Forgetting** - Intelligent memory pruning
- **Multi-Modal Memory** - Images, audio, structured data

---

## **12. Summary**

WOLFMIND is the **memory curator** that:

- Stores memory events for actors
- Provides relational search (MySQL fallback)
- Supports vector search (when available)
- Follows progressive enhancement doctrine
- Works on foundation tier, unlocks with better tiers
- Maintains no foreign keys (application-managed)
- Integrates with ANIBUS for orphan resolution
- Enables long-term agent learning

Without WOLFMIND, agents would have no memory.

With WOLFMIND, agents can:

- **Remember** - Past interactions and context
- **Learn** - From user behavior and patterns
- **Adapt** - Based on accumulated knowledge
- **Maintain Continuity** - Across sessions and time
- **Search Semantically** - When vector support available
- **Work Everywhere** - Foundation tier always works

This is how Lupopedia agents become truly intelligent over time.

---

## Related Documentation

- **[Architecture Sync](../architecture/ARCHITECTURE_SYNC.md)** - Complete WOLFMIND integration with DialogManager and agent system
- **[Database Philosophy](../architecture/DATABASE_PHILOSOPHY.md)** - Application-first validation principles that WOLFMIND follows
- **[No Foreign Keys Doctrine](NO_FOREIGN_KEYS_DOCTRINE.md)** - Why WOLFMIND uses application-managed relationships
- **[Agent Runtime](../agents/AGENT_RUNTIME.md)** - How agents interact with WOLFMIND memory system
- **[MySQL to Postgres Memory](../appendix/appendix/MYSQL_TO_POSTGRES_MEMORY.md)** - Complete migration specification for memory tables

---

