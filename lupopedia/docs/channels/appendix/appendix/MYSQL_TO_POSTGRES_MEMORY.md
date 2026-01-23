---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "0088FF"
  message: Created MYSQL_TO_POSTGRES_MEMORY.md with deterministic migration specification for converting MySQL memory tables to Postgres with pgvector support. Zero ambiguity, zero foreign keys, doctrine-aligned.
tags:
  categories: ["documentation", "migrations", "database", "memory"]
  collections: ["core-docs", "migrations"]
  channels: ["dev"]
in_this_file_we_have:
  - MySQL to Postgres Memory Table Conversion
  - General Migration Rules
  - Type Mapping Table
  - memory_events Conversion
  - memory_debug_log Conversion
  - memory_rollups Conversion
  - Vector Support Tables (Postgres Only)
  - Cursor Migration Instructions
file:
  title: "MySQL to Postgres Memory Table Conversion Specification"
  description: "Deterministic migration specification for converting MySQL memory tables to Postgres with pgvector support - zero ambiguity, zero foreign keys"
  version: "4.0.0"
  status: published
  author: "Captain Wolfie"
---

# ðŸŸ¦ **MYSQL â†’ POSTGRES MEMORY TABLE CONVERSION SPEC**

This is the authoritative conversion document for Cursor.

**Purpose:** Provide a clean, deterministic migration path from MySQL to Postgres + pgvector for the memory subsystem, ensuring zero breakage, zero ambiguity, and full doctrine compliance.

---

## **1. General Rules**

When converting MySQL â†’ Postgres:

### âœ” Keep all existing columns  
### âœ” Keep all existing indexes  
### âœ” Keep all existing table names  
### âœ” DO NOT add foreign keys  
### âœ” DO NOT change column meanings  
### âœ” DO NOT break MySQL compatibility in PHP  
### âœ” DO add Postgresâ€‘only enhancements (vector, JSONB, etc.)  
### âœ” DO add pgvector extension  
### âœ” DO create vector tables  

This ensures:

- MySQL continues working  
- Postgres gains vector memory  
- WOLFMIND stays portable  
- No breaking changes
- Doctrine compliance maintained

---

## **2. MySQL â†’ Postgres Type Mapping**

Cursor must apply these conversions:

| MySQL Type | Postgres Type |
|------------|----------------|
| `INT UNSIGNED` | `BIGINT` |
| `TINYINT UNSIGNED` | `SMALLINT` |
| `BIGINT` | `BIGINT` |
| `TEXT` | `TEXT` |
| `VARCHAR(N)` | `VARCHAR(N)` |
| `ENUM('val1','val2')` | `TEXT CHECK (column IN ('val1','val2'))` |
| `JSON stored as TEXT` | `JSONB` |
| `AUTO_INCREMENT` | `GENERATED ALWAYS AS IDENTITY` |
| `DATETIME` or `BIGINT timestamp` | `BIGINT` (unchanged) |

This keeps everything safe and compatible.

---

## **3. Convert Each Memory Table**

Below are the **exact conversions** Cursor must apply.

---

## **ðŸŸ¦ 3.1 memory_events (MySQL â†’ Postgres)**

### MySQL version (current):

```sql
CREATE TABLE memory_events (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    actor_id INT UNSIGNED NOT NULL,
    event_type VARCHAR(64) NOT NULL,
    content TEXT NOT NULL,
    metadata_json TEXT DEFAULT NULL,
    token_count INT UNSIGNED DEFAULT NULL,
    importance TINYINT UNSIGNED DEFAULT 0,
    embedding_status ENUM('none','pending','ready','failed') DEFAULT 'none',
    created_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_actor_created (actor_id, created_ymdhis),
    INDEX idx_actor_type (actor_id, event_type)
);
```

### Postgres version:

```sql
CREATE TABLE memory_events (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    actor_id BIGINT NOT NULL,
    event_type VARCHAR(64) NOT NULL,
    content TEXT NOT NULL,
    metadata_json JSONB DEFAULT NULL,
    token_count BIGINT DEFAULT NULL,
    importance SMALLINT DEFAULT 0,
    embedding_status TEXT CHECK (embedding_status IN ('none','pending','ready','failed')) DEFAULT 'none',
    created_ymdhis BIGINT NOT NULL
);

CREATE INDEX idx_actor_created ON memory_events (actor_id, created_ymdhis);
CREATE INDEX idx_actor_type ON memory_events (actor_id, event_type);
```

**Changes:**
- `id`: `INT UNSIGNED AUTO_INCREMENT` â†’ `BIGINT GENERATED ALWAYS AS IDENTITY`
- `actor_id`: `INT UNSIGNED` â†’ `BIGINT`
- `metadata_json`: `TEXT` â†’ `JSONB`
- `token_count`: `INT UNSIGNED` â†’ `BIGINT`
- `importance`: `TINYINT UNSIGNED` â†’ `SMALLINT`
- `embedding_status`: `ENUM` â†’ `TEXT CHECK`
- Indexes created separately (Postgres syntax)

---

## **ðŸŸ¦ 3.2 memory_debug_log**

### MySQL:

```sql
CREATE TABLE memory_debug_log (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    event_type VARCHAR(64) NOT NULL,
    details TEXT NOT NULL,
    created_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_type_created (event_type, created_ymdhis)
);
```

### Postgres:

```sql
CREATE TABLE memory_debug_log (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    event_type VARCHAR(64) NOT NULL,
    details TEXT NOT NULL,
    created_ymdhis BIGINT NOT NULL
);

CREATE INDEX idx_type_created ON memory_debug_log (event_type, created_ymdhis);
```

**Changes:**
- `id`: `INT UNSIGNED AUTO_INCREMENT` â†’ `BIGINT GENERATED ALWAYS AS IDENTITY`
- Index created separately

---

## **ðŸŸ¦ 3.3 memory_rollups**

### MySQL:

```sql
CREATE TABLE memory_rollups (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    actor_id INT UNSIGNED NOT NULL,
    summary TEXT NOT NULL,
    source_event_ids TEXT NOT NULL,
    created_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_actor_created (actor_id, created_ymdhis)
);
```

### Postgres:

```sql
CREATE TABLE memory_rollups (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    actor_id BIGINT NOT NULL,
    summary TEXT NOT NULL,
    source_event_ids TEXT NOT NULL,
    created_ymdhis BIGINT NOT NULL
);

CREATE INDEX idx_actor_created ON memory_rollups (actor_id, created_ymdhis);
```

**Changes:**
- `id`: `INT UNSIGNED AUTO_INCREMENT` â†’ `BIGINT GENERATED ALWAYS AS IDENTITY`
- `actor_id`: `INT UNSIGNED` â†’ `BIGINT`
- Index created separately

---

## **4. Add Vector Support (Postgres Only)**

Cursor must add:

```sql
CREATE EXTENSION IF NOT EXISTS vector;
```

Then create the vector tables:

---

## **ðŸŸ¦ 4.1 memory_embeddings**

```sql
CREATE TABLE memory_embeddings (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    memory_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    embedding vector(1536) NOT NULL,
    created_ymdhis BIGINT NOT NULL
);

CREATE INDEX idx_memory_embeddings_memory ON memory_embeddings (memory_id);
CREATE INDEX idx_memory_embeddings_actor ON memory_embeddings (actor_id);
```

**Note:** 
- This table is **Postgres-only** and does not exist in MySQL
- `memory_id` references `memory_events.id` but **NO FOREIGN KEY** (doctrine compliance)
- Vector dimension (1536) matches common embedding models (OpenAI, etc.)

---

## **ðŸŸ¦ 4.2 memory_semantic_index**

```sql
CREATE TABLE memory_semantic_index (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    memory_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    embedding vector(1536) NOT NULL,
    importance SMALLINT DEFAULT 0,
    created_ymdhis BIGINT NOT NULL
);

CREATE INDEX idx_semantic_cosine
ON memory_semantic_index
USING ivfflat (embedding vector_cosine_ops)
WITH (lists = 100);

CREATE INDEX idx_semantic_memory ON memory_semantic_index (memory_id);
CREATE INDEX idx_semantic_actor ON memory_semantic_index (actor_id);
```

**Note:**
- This table is **Postgres-only** and does not exist in MySQL
- Uses `ivfflat` index for fast vector similarity search
- `memory_id` references `memory_events.id` but **NO FOREIGN KEY** (doctrine compliance)
- Vector dimension (1536) matches common embedding models

---

## **5. Cursor Migration Instructions**

When converting the database from MySQL to Postgres:

1. **Cursor MUST convert all memory tables** using the type mappings defined in this document.
2. **Cursor MUST preserve all table names and column names.**
3. **Cursor MUST NOT add foreign key constraints.**
4. **Cursor MUST convert `metadata_json` from TEXT to JSONB.**
5. **Cursor MUST convert ENUM columns to TEXT with CHECK constraints.**
6. **Cursor MUST convert AUTO_INCREMENT to GENERATED ALWAYS AS IDENTITY.**
7. **Cursor MUST create the pgvector extension:**
   ```sql
   CREATE EXTENSION IF NOT EXISTS vector;
   ```
8. **Cursor MUST create the vector tables:**
   - `memory_embeddings`
   - `memory_semantic_index`
9. **Cursor MUST NOT modify the relational memory tables to include vector columns.**
10. **Cursor MUST ensure WOLFMIND remains compatible with both MySQL and Postgres.**

### **Migration Checklist**

- [ ] Convert `memory_events` table
- [ ] Convert `memory_debug_log` table
- [ ] Convert `memory_rollups` table (if exists)
- [ ] Create pgvector extension
- [ ] Create `memory_embeddings` table (Postgres only)
- [ ] Create `memory_semantic_index` table (Postgres only)
- [ ] Verify no foreign keys added
- [ ] Verify all indexes created
- [ ] Test WOLFMIND compatibility
- [ ] Verify graceful degradation when vector unavailable

---

## **6. Data Migration**

When migrating data from MySQL to Postgres:

1. **Export data** from MySQL tables
2. **Convert data types** as needed:
   - JSON strings â†’ JSONB (Postgres will validate)
   - ENUM values â†’ TEXT (values already valid)
3. **Import data** into Postgres tables
4. **Verify data integrity** (row counts, sample data)
5. **Test WOLFMIND operations** on migrated data

**Note:** Vector embeddings will be generated after migration, not migrated from MySQL (MySQL doesn't have vectors).

---

## **7. WOLFMIND Compatibility**

After migration, WOLFMIND must:

- **Detect Postgres** via `hasVectorSupport()`
- **Use relational memory** (works on both MySQL and Postgres)
- **Use vector memory** (only when pgvector available)
- **Gracefully degrade** when vector unavailable
- **Maintain PHP compatibility** (PDO works with both)

The `class-wolfmind.php` implementation must remain compatible with both database systems.

---

## **8. Summary**

This migration specification provides:

- âœ… A clean MySQL baseline (foundation tier)
- âœ… A clean Postgres rewrite (enhanced tier)
- âœ… A clean vector memory layer (Postgres only)
- âœ… Zero foreign keys (doctrine compliance)
- âœ… Zero breakage (backward compatible)
- âœ… Zero ambiguity for Cursor (exact specifications)
- âœ… Zero surprises for future you (deterministic path)

**MySQL now. Postgres later. Vector memory later. Code supports all three without breaking.**

This is exactly how a semantic OS should evolve.

---

## Related Documentation

**Core Memory System:**
- **[WOLFMIND Doctrine](../../doctrine/WOLFMIND_DOCTRINE.md)** - Complete memory system architecture that this migration supports
- **[Database Schema](../../schema/DATABASE_SCHEMA.md)** - Current MySQL memory table structure being migrated

**Migration Context:**
- **[Database Philosophy](../../architecture/DATABASE_PHILOSOPHY.md)** - Application-first principles that guide this migration approach
- **[No Foreign Keys Doctrine](../../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md)** - Why migration preserves application-managed relationships

**Development Process (LOW Priority):**
- **[Architecture Sync](../../architecture/ARCHITECTURE_SYNC.md)** - System architecture including progressive enhancement philosophy
- **[End Goal 4.2.0](../../overview/END_GOAL_4_2_0.md)** - Vision for enhanced memory capabilities in federated system
- **[History](../../history/HISTORY.md)** - Background on why progressive enhancement became necessary

---

