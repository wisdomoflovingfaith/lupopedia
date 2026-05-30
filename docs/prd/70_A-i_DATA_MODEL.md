---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/70_A-i_DATA_MODEL.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/70_A-i_DATA_MODEL.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/70-data-model.toon
  atoms_toon: null
  transcript_jsonl: 0/development/data-model
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_70_A-i
  title: 'PRD 70: Data Model (The Skeleton) - 4.0.93'
  summary: Data model specification for Lupopedia's memory graph, database schema, and federation authentication.
---
## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## LILITH Audit: Data Model PRD (70_data_model.md)

### What's Correct ???????

| Element | Status |
|---------|--------|
| Core data structures documented | ??????? Good |
| Forbidden constructs list | ??????? Comprehensive |
| Required ID format | ??????? 63-bit signed-safe |
| Timestamp format | ??????? BIGINT UTC YYYYMMDDHHIISS |
| Architectural evolution explained | ??????? Good |
| Migration mapping reference | ??????? Good |
| TOON protection rule | ??????? Good |
| Split table architecture | ??????? Matches Option A |

### Issues Fixed ???????

| Priority | Issue | Status |
|----------|-------|--------|
| **High** | file_path_from_root leading slash | ??????? Fixed - Removed leading `/` |
| **High** | lupo_votes missing fields | ??????? Fixed - Added vote_type, reason_text, reason_code, is_current |
| **High** | lupo_votes field names | ??????? Fixed - Changed to target_type/target_id/cast_by_actor_id |
| **Medium** | Missing truth_evidence definition | ??????? Fixed - Added CREATE TABLE statement |
| **Medium** | Missing truth_followers definition | ??????? Fixed - Added CREATE TABLE statement |
| **Medium** | Missing truth_context_map definition | ??????? Fixed - Added CREATE TABLE statement |

### LILITH Findings

```yaml
findings:
  accuracy_score: 100
  constitutional_violations: []
  security_concerns: []
  bias_detected: no
  better_alternative_exists: No
  counter_proposal: null
  recommendations:
    - "EXPAND target_type values to include all votable entities"
    - "DOCUMENT complete list in PRD"
    - "ALIGN with polymorphic patterns used elsewhere (lupo_comments, lupo_metadata)"
  verdict: approved
```

**LILITH Sign-off**: ??????? **Data Model PRD updated. lupo_votes is now truly polymorphic, supporting votes on ANY entity type including channels, threads, contexts, and collections.**

---

**RULE [93.PROTECT_SCHEMA_JSON]** (formerly PROTECT_TOONS): The IDE is strictly forbidden from writing to `database/lupopedia/json/*.json`. Any schema evolution must be drafted in `database/lupopedia/mysql/` (install/seed) and verified by running `generate_toon_files.py`. See `00_root_constitutional_system_requirements.md` ????6.

**LILITH Verdict**: The "Architect" must look at the "Senses" (JSON) but only write to the "DNA" (Install Scripts).


---

## Database Schema Doctrine - Primary Keys and Timestamps

### Primary Key Naming Convention (NON-NEGOTIABLE)

Every table's primary key MUST follow this exact pattern:

```
<singular_table_name>_id
```

Examples:
- lupo_memory_nodes ??? memory_node_id
- lupo_memory_edges ??? memory_edge_id
- lupo_dialog_pending_tasks ??? dialog_pending_task_id
- lupo_dialog_read_log ??? dialog_read_log_id
- lupo_transcripts ??? transcript_id
- lupo_transcript_messages ??? transcript_message_id

**FORBIDDEN** (never use):
- id
- node_id
- memory_id
- task_id
- message_id
- or any shortened form

This rule applies to:
- Primary key columns
- Referencing columns in child tables
- All soft references (no foreign keys)

The name IS the namespace boundary. Do not alter it.

### Timestamp Fields (ABSOLUTE DOCTRINE)

#### TIMESTAMP DOCTRINE ??? ABSOLUTE RULES

**1. STORAGE FORMAT (NON-NEGOTIABLE)**
All timestamps in Lupopedia MUST be stored as:
```
UTC BIGINT (YYYYMMDDHHIISS)
```

These fields are:
```
created_ymdhis
updated_ymdhis
deleted_ymdhis
```

These represent WHEN an event occurred.
They NEVER contain timezone information.

**2. FORBIDDEN STORAGE PATTERNS**
The following are STRICTLY FORBIDDEN in all database schemas, migrations, PRDs, and generated SQL:

- Storing timezone offsets in timestamp fields
- Storing local time in timestamp fields
- Using DATETIME, TIMESTAMP, or any SQL date type
- Auto-converting timestamps based on server timezone
- Mixing local time and UTC in the same column
- Any attempt to "helpfully" normalize or localize timestamps

Mixing timezone with the stored timestamp is FORBIDDEN.
Timezone is WHERE, not WHEN.

**3. DISPLAY & API CONVERSION**
Timezone handling is a DISPLAY concern only.

Conversion rules:
- Use the existing PHP class conversion functions in includes/classes/
- Convert BIGINT UTC ??? string with offset ONLY for output
- API systems may receive localized strings if required
- The database NEVER stores localized time

The database stores WHEN.
The application layer handles WHERE.

**4. DOCTRINE SUMMARY**
- Storage = UTC BIGINT only
- No timezone in storage
- No daylight savings adjustments
- No SQL datetime types
- No automatic conversions
- No mixing apples (UTC) and oranges (local time)

**IMPORTANT RULES:**
- Always use the `_ymdhis` suffix - never `_at`
- Do NOT convert these fields to DATETIME
- Do NOT assume they are datetime
- Do NOT rewrite them
- Do NOT normalize them

If the system ever uses `created_at` / `updated_at` / `deleted_at`, they MUST be treated as aliases for the same BIGINT timestamp format ??? NOT SQL datetime fields.

### Child Table Reference Rule

Any table referencing another table MUST use the full PK name:

```
<singular_table_name>_id
```

Examples:
- transcript_messages.transcript_id
- memory_edges.memory_node_id
- dialog_read_log.actor_id

Never shorten. Never collapse. Never rename.

---

## Context????????Typed, Status????????Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A ???????? B)
  - bidirectional (A ???????? B)
  - restricted-direction (A ???????? B but not B ???????? A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported ??? supported when 
  sufficient supporting edges accumulate.

---

## MEMORY NODE STORAGE DOCTRINE

**1. Memory node content MUST NOT be stored in the database.**
- Database stores metadata only.
- Filesystem stores the actual content.

**2. Using TEXT, MEDIUMTEXT, or LONGTEXT for memory node content is FORBIDDEN.**

**3. Memory node content MUST be written to:**
```
memory/{channel_key}/{ladder_tier}/YYYY/MM/{memory_slug}.json
```

**4. The database stores:**
- memory_node_id
- memory_slug
- created_ymdhis
- updated_ymdhis
- deleted_ymdhis
- metadata fields
- edge references

It does NOT store the content blob.

**5. The filesystem is the canonical source of truth for memory content.**
The database is the index.

---

## TOON JSON MIRROR DOCTRINE ??? CRITICAL RULES

**1. LOCATION**
The JSON files in:
```
database/lupopedia/json/
```
are TOON metadata mirrors generated from the live database.

**2. PURPOSE**
These JSON files describe:
- table structure
- column names
- column types
- indexes
- constraints
- metadata

They exist so agents can READ the schema before editing any files.

**3. READ-ONLY RULE**
These JSON files are STRICTLY READ-ONLY.

They are NOT:
- the primary data store
- a writable content store
- a place to insert rows
- a place to store memory content
- a replacement for the database

Writing data rows to the database based on these JSON files is FORBIDDEN.

**4. DIRECTION OF TRUTH**
Truth flows in ONE direction:
```
Database ??? TOON JSON (structure mirror)
```
NOT the other way around.

JSON ??? Database is FORBIDDEN unless the user explicitly commands a migration.

**5. DO NOT CONFUSE WITH MEMORY JSON FILES**
These TOON JSON files are NOT the same as the memory node content files stored in memory/.

- TOON JSON = structure mirror (read-only)
- Memory JSON = content store (writable)

Mixing these two concepts is FORBIDDEN.

**6. FORBIDDEN ACTIONS**
The following actions are STRICTLY FORBIDDEN:
- Treating TOON JSON as the database
- Writing new rows to the database based on TOON JSON
- Syncing TOON JSON ??? DB automatically
- Storing content in TOON JSON
- Using TOON JSON as a writable data source
 
---

## INSERT STATEMENT DOCTRINE ??? CRITICAL RULES

**1. COLUMN LIST REQUIRED (NON-NEGOTIABLE)**
ALL INSERT statements MUST explicitly list EVERY column being inserted.

Example (correct):
```sql
INSERT INTO table_name (
    col1, col2, col3, col4
) VALUES (
    ?, ?, ?, ?
);
```

Example (FORBIDDEN):
```sql
INSERT INTO table_name VALUES (...);
```

Positional INSERTs are FORBIDDEN.

**2. REASON**
Lupopedia tables have evolved over 20+ years.
Column order is NOT canonical and MUST NOT be relied upon.

Columns:
- have been added in random order
- have been renamed
- have been moved
- are not sequential
- are not predictable

Therefore, INSERT statements MUST specify column names.

**3. TIMESTAMP COLUMNS**
If the table includes:
```
created_ymdhis
updated_ymdhis
deleted_ymdhis
```

Then INSERT statements MUST include these fields when appropriate.

These fields are UTC BIGINT timestamps.
They MUST NOT be auto-converted to DATETIME.

**4. FORBIDDEN PATTERNS**
The following are STRICTLY FORBIDDEN:
- INSERT INTO table VALUES (...)
- INSERT without column list
- INSERT relying on column order
- INSERT omitting timestamp fields when required
- INSERT using DATETIME or TIMESTAMP SQL types
- INSERT using timezone-adjusted values

**5. REQUIRED PRE-FLIGHT CHECK**
Before generating ANY INSERT statement, agents MUST:
- Load the TOON JSON metadata from: database/lupopedia/json/
- Read the actual column names
- Use the exact column names in the INSERT

The TOON JSON is the schema mirror.
It MUST be read before generating SQL.

**6. DIRECTION OF TRUTH**
```
Database schema ??? TOON JSON ??? Agent SQL generation
```

NOT the other way around.

---

## PRD INDEXING DOCTRINE ??? CONTENT + MEMORY SYSTEM

**1. Filesystem is the authoritative store for memory node content.**
Database stores metadata and indexes only.

**2. To support fast search, the following indexing tables MUST be created:**

**a. lupo_memory_keywords**
```
- memory_node_id
- keyword
- weight
- created_ymdhis
```

**b. lupo_memory_embeddings**
```
- memory_node_id
- embedding_vector (stored as JSON or blob)
- model_version
- created_ymdhis
```

**c. lupo_memory_search_index**
```
- memory_node_id
- title
- summary
- keywords
- updated_ymdhis
```

**d. lupo_memory_tags**
```
- memory_node_id
- tag
- created_ymdhis
```

**e. lupo_memory_hash_index**
```
- memory_node_id
- content_hash
- created_ymdhis
```

**3. lupo_contents requires similar indexing tables:**

**a. lupo_content_keywords**
**b. lupo_content_search_index**
**c. lupo_content_tags**
**d. lupo_content_hash_index**

**4. All indexing tables MUST use:**
- auth_user_id (not user_id)
- explicit column lists in INSERT statements
- UTC BIGINT timestamps (ymdhis)
- NO timezone offsets in storage

**5. Indexing tables MUST NOT store content blobs.**
Content remains in the filesystem.
---

## PRD FILESYSTEM LOCATION DOCTRINE

**1. THREE DISTINCT FILESYSTEM DOMAINS (MANDATORY)**

**a. memory/**
- Stores memory node content ONLY.
- Structured by: {channel_key}/{ladder_tier}/{YYYY}/{MM}/{slug}.json
- This is the authoritative store for memory content.

**b. channels/**
- Stores channel artifacts ONLY.
- Includes: manifests, minutes, heterodox reports, protocols,
  emotional geometry, channel metadata, and channel-level artifacts.
- Structured by: {federation_node_id}/{channel_key}/{YYYY}/{MM}/{file}

**c. content/**
- Stores general content files that are NOT memory nodes and NOT
  channel artifacts.
- Structured by: {federation_node_id}/{channel_key}/{YYYY}/{MM}/{file}

**THESE THREE PATHS MUST NEVER BE COLLAPSED OR CONFUSED.**

**2. FORBIDDEN ACTIONS**

The following are STRICTLY FORBIDDEN:
- Storing memory nodes in content/
- Storing channel artifacts in content/
- Storing content files in memory/
- Storing ANY files outside YYYY/MM sharding
- Writing files to unsharded directories
- Dumping multiple content types into the same folder

**3. DATE-SHARDED STORAGE (NON-NEGOTIABLE)**

ALL filesystem-backed content MUST be stored using:
```
{YYYY}/{MM}/
```

Reason:
- Prevents directory explosion
- Ensures stable performance for ls/find
- Enables efficient archival and cleanup
- Supports distributed federation nodes

**4. PRE-FLIGHT REQUIREMENT FOR ALL AGENTS**

Before writing ANY file, agents MUST:
- Determine correct domain (memory, channel, or content)
- Compute correct YYYY/MM shard
- Ensure directories exist
- Write ONLY to the correct domain + shard

Writing to the wrong domain is a PRD violation.

**5. DO NOT ASSUME EVERYTHING BELONGS IN content/**

content/ is NOT a catch-all directory.

- Memory nodes ??? memory/
- Channel artifacts ??? channels/
- General content ??? content/

Agents MUST respect these boundaries.
---

## PRD DOCTRINE ??? COLUMN NAME SOURCE OF TRUTH

**1. JSON SCHEMA FILES ARE THE AUTHORITATIVE SOURCE OF TRUTH**
For ANY update to ANY PHP or Python file that references database columns,
Cascade MUST load and read the JSON schema files located in:
```
database/lupopedia/json/
```

These JSON files define:
- exact column names
- exact column types
- nullability
- defaults
- indexes
- primary keys

Predictive text MUST NOT be used to infer column names.

**2. FORBIDDEN ACTIONS**
The following are STRICTLY FORBIDDEN:
- Guessing column names
- Using predictive text to infer column names
- Shortening or normalizing column names
- Replacing auth_user_id with user_id
- Inventing columns not present in JSON
- Using outdated column names from older code

**3. REQUIRED PRE-FLIGHT CHECK**
Before modifying ANY PHP or Python file that touches the database,
Cascade MUST:
- Load the JSON schema for the relevant table
- Verify the column names
- Use EXACT column names from the JSON
- Reject any code that uses incorrect or hallucinated names

**4. ERROR HANDLING**
If a referenced column does NOT exist in the JSON schema:
- Cascade MUST stop
- Notify the user of the mismatch
- Suggest adding the column to the database if appropriate
- NEVER invent or assume the column

**5. INSTALLER + SEED CONSISTENCY**
All code changes MUST align with:
- install_new_lupopedia.sql
- seed_lupopedia_4_1_0.sql
- JSON schema mirrors

If inconsistencies are found, Cascade MUST report them.

**6. DO NOT RELY ON COLUMN ORDER**
All SQL generated in PHP or Python MUST:
- explicitly list column names
- NEVER rely on positional order
- NEVER use INSERT ... VALUES (...) without column lists

**7. DOCTRINE SUMMARY**
```
JSON schema ??? installer ??? seed ??? PHP/Python code
```

This is the ONLY valid direction of truth.

---

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```

---

## Federation Peer Authentication and Shared Secrets

### Normative Requirements

- Each Lupopedia installation MUST generate a unique LUPO_FEDERATION_SHARED_SECRET during installation. This secret is used exclusively for authenticating peer-to-peer federation requests.

- Federation is decentralized. Node 0 (the canonical server) does not know or track nodes 2+. Each installation maintains its own list of known peers in the lupo_federation_nodes table.

- For any incoming federation request, the receiving node MUST validate:
  (a) the sender's federation_node_id,
  (b) the sender's shared secret stored in lupo_federation_nodes,
  (c) an HMAC signature computed over the request payload,
  (d) a timestamp to prevent replay attacks.

- Outgoing federation requests MUST include:
  - federation_node_id of the sender,
  - timestamp,
  - HMAC(secret_of_sender, payload).

- The shared secret MUST NOT be logged, echoed, or transmitted except during authenticated federation requests.

- The shared secret is NOT an API key and NOT a global key. It is a per-installation trust anchor for peer authentication.

### Database Structure

The lupo_federation_nodes table stores:
- federation_node_id,
- base_url,
- shared_secret,
- last_seen timestamp,
- capabilities flags.

Node 1 always represents the local installation. Node 0 represents the canonical root server. Nodes 2+ are installation-specific and not globally coordinated.

### Rationale

Because Lupopedia is a peer-to-peer federation, no central authority issues keys. Each installation generates its own shared secret and manually or automatically exchanges secrets with trusted peers.
