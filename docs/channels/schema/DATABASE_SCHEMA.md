---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_DEFAULT_STATUS
  - GLOBAL_PROJECT_CRAFTY_SYNTAX
  - GLOBAL_COLLECTION_CORE_DOCS
dialog:
  speaker: cursor
  target: @everyone
  message: "Added Cursor note at top of file: version 4.0.1 authoritative, application modules vs kernel utilities, LiveHelp migration-only, migration SQL authoritative. Corrected version from 4.1.0 to 4.0.1."
  mood: "00FF00"
tags:
  categories: ["documentation", "database", "schema", "reference"]
  collections: [GLOBAL_COLLECTION_CORE_DOCS]
  channels: ["dev", "architecture"]
file:
  title: "Lupopedia Database Schema Reference"
  description: "Complete documentation of all 145 database tables (111 core + 34 migration-only + 4 system) in Lupopedia 4.0.1"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: GLOBAL_DEFAULT_STATUS
  author: "Captain Wolfie"
---

# ðŸŸ¦ **NOTE FOR CURSOR â€” READ AND APPLY TO ALL FUTURE WORK**

## **1. Lupopedia Version (Authoritative)**  
The current Lupopedia version is:

**`4.0.1`**

This version number is **authoritative** and must be used consistently across:

- documentation  
- module definitions  
- Wolfie Headers  
- schema files  
- code comments  
- examples  
- generated files  

Cursor must **not** infer, propose, or autoâ€‘increment versions such as `4.1.0`.  
Version changes are **humanâ€‘controlled only**.

---

## **2. Application Modules vs Kernel Utilities**

Cursor must distinguish between:

### **Application Modules (in the `modules` table):**
1. GLOBAL_PROJECT_CRAFTY_SYNTAX  
2. CRM / Leads  
3. THOTH / Q&A  
4. Routing / Multiâ€‘Agent System  
5. Analytics  
6. Settings  
7. Federation  
8. Content  

These are the **only modules** that belong in the `modules` table.

### **Kernel Utilities (NOT modules):**
- anubis  
- Dialog  
- Memory  
- HERMES  
- CADUCEUS  
- IRIS  
- Node Discovery  
- Governance  
- Embeddings  

Cursor must **not** treat these as modules, must not add them to the module registry, and must not generate module documentation for them.

---

## **3. LiveHelp Is Migrationâ€‘Only**

The `livehelp_*` tables are **not part of Lupopedia 4.0.1**.

They exist **only during the upgrade** from Crafty Syntax 3.7.5.

Cursor must understand:

- LiveHelp is **not a module**  
- LiveHelp is **not a subsystem**  
- LiveHelp is **not part of runtime Lupopedia**  
- LiveHelp tables are **deleted after migration**  
- Cursor must **not** generate code, docs, or logic for LiveHelp  

---

## **4. Migration SQL File (Authoritative)**

The migration from Crafty Syntax 3.7.5 â†’ Lupopedia 4.0.1 is handled by a **handâ€‘written SQL file** located at:

```
/database/migrations/
```

Cursor must:

- reference this file in documentation  
- treat it as authoritative  
- never attempt to rewrite or regenerate it  
- never generate SQL for LiveHelp  
- never create migration logic in PHP  

The install/upgrade wizard will execute this SQL file automatically.

---

## **5. Postâ€‘Migration State**

After the migration completes:

- all `livehelp_*` tables are dropped  
- the database contains only Lupopedia 4.0.1 tables  
- Crafty Syntax 4.0.1 replaces LiveHelp entirely  
- Cursor must generate documentation and code **as if LiveHelp never existed**  

---

## **6. Summary for Cursor**

Cursor must follow these rules:

### âœ” Use version `4.0.1` everywhere  
### âœ” Only the 8 application modules belong in the `modules` table  
### âœ” LiveHelp is migrationâ€‘only  
### âœ” Migration SQL lives in `/database/migrations/`  
### âœ” Migration SQL is authoritative and must not be rewritten  
### âœ” Kernel utilities are not modules  
### âœ” Do not propose or infer new versions  
### âœ” Use federation table names: `federation_nodes`, `federation_categories`, `federation_discovery`, `federation_node_modules` (NOT `node_registry`, `node_categories`, etc.)  
### âœ” These tables belong to Federation Layer, not Agent Layer  
### âœ” All table names must use `LUPO_PREFIX` from `lupopedia-config.php` (never hardcode table names)
### âœ” All configuration comes from `lupopedia-config.php` (single source of truth, outside web root)
### âœ” Every PHP file must check `if (!defined('LUPOPEDIA_CONFIG_LOADED')) exit;`

---

# **LUPOPEDIA DATABASE SCHEMA (v4.0.1)**
*145 Tables - Lupopedia 4.0.1 (111 core + 34 migration-only + 4 system)*

## **Quick Reference**
- **Total Tables**: ~112 (77 core + 22 orchestration + 12 ephemeral + 1 system)
- **Schema Federation**: Phase A implemented (4.0.3)
  - Core schema: `lupopedia` (77 tables, was 111)
  - Orchestration schema: `lupopedia_orchestration` (22 tables)
  - Ephemeral schema: `lupopedia_ephemeral` (12 tables)
- **Architecture**: Merged Crafty + Lupopedia
- **Status**: Production Ready
- **Last Updated**: 2025-01-06

## **Architecture Overview**

### Core Principles
- **No Foreign Keys** â€” All relationships managed in application code
- **BIGINT UTC Timestamps** â€” All dates stored as `YYYYMMDDHHMMSS` format
- **Soft Deletion** â€” `is_deleted` and `deleted_ymdhis` on all tables
- **Node Scoping** â€” Multi-tenant support via `node_id`
- **Application-Managed Integrity** â€” Referential integrity in PHP code
- **System IDs (ID = 0)** â€” Reserved for kernel space (system channels, bootstrapping, migrations). See [Database Philosophy](DATABASE_PHILOSOPHY.md#-8-system-ids-and-the-use-of-zero) for details.

### Key Features
- **Merged Architecture**: Unified Crafty + Lupopedia systems
- **Scalable**: Designed for high performance at scale
- **Extensible**: Modular design for future growth
- **Secure**: Built-in permissions and auditing

### Schema Reference Files

When building Lupopedia, you can reference table and column structures using:

1. **Toon Files** (`database/toon_data/*.toon` and `database/toon_data/*.json`)
   - TOON format files containing complete table structures (READ-ONLY for agents and IDEs)
   - Each file includes: `table_name`, `fields` (full column definitions from INFORMATION_SCHEMA), and `data` (sample rows)
   - **Generated automatically by Python script** from the live database schema
   - **READ-ONLY** â€” Only the Python cron job writes TOON files
   - **The database (phpMyAdmin) is the authoritative source of truth**
   - TOON files are reflections of the database, not editable artifacts
   - Example: `database/toon_data/actors.toon` shows the complete structure of the `actors` table
   - Use these files to understand exact column names, types, and constraints when writing code
   - **Do NOT modify TOON files** â€” any changes will be overwritten by the Python cron job
   - See [TOON Doctrine](../doctrine/TOON_DOCTRINE.md) for complete rules

2. **CSV Schema Snapshots** (`database/csv_data/*.csv`)
   - Lightweight CSV files for AI tool consumption
   - See `docs/schema/AI_SCHEMA_GUIDE.md` for details

Both reference systems provide authoritative schema information without requiring direct database access.

---

## **Database Schema by Subsystem**

### **1. Core Identity & Access**
- `actors` - Central identity registry for all system entities
- `actor_events` - Comprehensive audit trail of all actions
- `actor_group_membership` - Role-based access control
- `actor_preferences` - User and agent settings

### **2. AI Agent Framework**
- `agents` - Core agent definitions and configurations
- `agent_registry` - Agent registry with `classification_json` (identity-level classification and routing role)
- `agent_capabilities` - Dynamic tool routing and abilities
- `agent_dialogs` - Conversation history and training data
- `agent_edges` - Inter-agent relationships
- `agent_faucets` - Persona management
- `agent_properties` - Flexible configuration storage (behavior, not identity)

### **3. Content & Knowledge Graph**
- `contents` - Primary content storage
- `nodes` - Knowledge graph entities
- `edges` - Relationships between nodes
- `entity_properties` - Flexible metadata storage
- `categories` - Content classification

### **4. Analytics & Telemetry**
- `visit_track` - Visitor tracking
- `visits_daily` / `visits_monthly` - Aggregated metrics
- `paths_daily` / `paths_monthly` - User journey analysis
- `campaign_vars_daily` - Marketing attribution

### **5. Crafty Syntax 4.0.1 System (First-Party Module)**
- `crafty_chat_transcripts` - Conversation history
- `crafty_messages` - Internal communications
- `crafty_operator_departments` - Support team structure
- `crafty_user_profile` - User and operator profiles

**Note:** The legacy `livehelp_*` tables (34 tables) are **migration-only** and are **NOT part of Lupopedia 4.0.1**. They exist only during the upgrade from Crafty Syntax 3.7.5 â†’ Lupopedia 4.0.1 and are dropped after migration completes. See [Migration Doctrine](../doctrine/MIGRATION_DOCTRINE.md) for details.

### **6. System & Administration**
- `domains` - Multi-tenant organization
- `modules` - System components
- `api_clients` - Integration endpoints
- `audit_log` - System-wide audit trail

### **7. Federation Layer**
- `federation_nodes` (formerly `node_registry`) - Registry of all Lupopedia node installations (domain installations)
- `federation_categories` (formerly `node_categories`) - Categories for organizing federated nodes
- `federation_category_map` (formerly `node_category_map`) - Maps nodes to categories
- `federation_discovery` (formerly `node_discovery`) - Node discovery and registration
- `federation_node_modules` (formerly `node_modules`) - Module mappings for federated nodes

**âš ï¸ IMPORTANT:** These tables belong to the **Federation Layer**, not the Agent Layer. They manage inter-node federation, not AI agents.

**`federation_nodes` Table (formerly `node_registry`):**
- `node_id` (PK, BIGINT) - Unique node identifier
- `parent_node_id` (BIGINT, nullable) - Parent node in hierarchy (NULL for root)
- `domain_name` (VARCHAR(255)) - Human-readable domain name
- `domain_root` (VARCHAR(255), nullable) - Canonical root URL of the domain hosting this Lupopedia node
- `install_url` (VARCHAR(255)) - Installation path of Lupopedia on that domain
- `identity_json` (JSON, nullable) - Node identity and configuration
- `is_active` (TINYINT(1)) - Whether this node is active
- Standard timestamps and soft-delete fields

---

## **Schema Statistics**
- **Total Tables**: 145
- **Core Tables**: 111 (permanent Lupopedia 4.0.1 tables)
- **Legacy Crafty Syntax Migration Tables**: 34 (temporary, migration-only, removed after upgrade)
- **System Tables**: 4 (includes federation_nodes, modules, etc.)

**âš ï¸ IMPORTANT â€” Legacy Tables Are Migration-Only:**

The 34 `livehelp_*` tables are **NOT part of Lupopedia 4.0.1**. They exist **only during the upgrade process** from Crafty Syntax 3.7.5 â†’ Lupopedia 4.0.1. These tables are:

- Created only for migration purposes
- Populated from old Crafty Syntax 3.7.5 database
- Used by migration SQL to transform data into new 4.0.1 schema
- **Dropped immediately after successful migration**

**See [Migration Doctrine](../doctrine/MIGRATION_DOCTRINE.md)** for complete rules on LiveHelp migration.

**Note**: Complete table structures are available as TOON files in `database/toon_data/*.toon` and `database/toon_data/*.json`. These files provide authoritative **read-only** schema references for all 145 tables (including temporary migration tables). TOON files are automatically generated by a Python script from the live database schema. The database (phpMyAdmin) is the authoritative source of truth. TOON files are READ-ONLY for all IDEs and agents â€” only the Python cron job writes them. See [TOON Doctrine](../doctrine/TOON_DOCTRINE.md) for complete rules.

---

## **1. Core Identity & Access**
### **`actors`**
Core identity table for humans and AI agents, anchoring permissions, events, and semantic relationships.

**Key Fields:**
- `actor_id` (PK, BIGINT)
- `actor_type` (ENUM: 'user', 'ai_agent', 'service')
- `slug` (VARCHAR(255), UNIQUE)
- `name` (VARCHAR(255))
- `created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis` (BIGINT UTC)
- `is_active`, `is_deleted` (TINYINT)

---

### **`actor_events`**
Tracks all actorâ€‘initiated actions across the system, forming a behavioral event ledger essential for auditing, learning, and semantic analysis.

**Key Fields:**
- `event_id` (PK, BIGINT)
- `actor_id` (BIGINT, references actors)
- `event_type` (VARCHAR(100))
- `entity_type` (VARCHAR(100))
- `entity_id` (BIGINT)
- `details` (JSON)
- `created_ymdhis` (BIGINT UTC)

---

### **`actor_group_membership`**
Maps actors to groups, defining access, roles, and collaborative structures across domains.

**Key Fields:**
- `membership_id` (PK, BIGINT)
- `actor_id` (BIGINT, references actors)
- `group_id` (BIGINT, references groups)
- `role` (VARCHAR(50))
- `created_ymdhis`, `updated_ymdhis` (BIGINT UTC)
- `is_active` (TINYINT)

---

### **`actor_preferences`**
Stores perâ€‘actor settings and personalization flags that shape UI, behavior, and agent interactions.

**Key Fields:**
- `preference_id` (PK, BIGINT)
- `actor_id` (BIGINT, references actors)
- `domain_id` (BIGINT, references domains)
- `preference_key` (VARCHAR(100))
- `preference_value` (TEXT)
- `created_ymdhis`, `updated_ymdhis` (BIGINT UTC)

---
2. AI Agent Framework
agents
The master registry of all AI agents, defining identity, configuration, capabilities, and node participation.

Key Fields:

agent_id (PK, BIGINT)
agent_key (VARCHAR(100), UNIQUE)
name (VARCHAR(255))
description (TEXT)
agent_type (VARCHAR(100))
configuration (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
is_active (TINYINT)
Notes: Central registry for all AI agents in the system.

agent_capabilities
Defines what each agent can do, enabling dynamic tool routing, reasoning modes, and capability discovery.

Key Fields:

capability_id (PK, BIGINT)
agent_id (BIGINT, references agents)
capability_name (VARCHAR(100))
capability_config (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Maps agents to their specific capabilities and configurations.

agent_context_snapshots
Captures agent memory states over time, supporting learning, debugging, and context reconstruction.

Key Fields:

snapshot_id (PK, BIGINT)
agent_id (BIGINT, references agents)
context_hash (VARCHAR(64))
context_data (LONGTEXT)
created_ymdhis (BIGINT UTC)
Notes: Stores serialized agent states for persistence.

agent_dialogs
Stores dialog exchanges between agents and users, forming the conversational history and training substrate.

Key Fields:

dialog_id (PK, BIGINT)
thread_id (VARCHAR(100))
agent_id (BIGINT, references agents)
actor_id (BIGINT, references actors)
message (TEXT)
message_type (VARCHAR(50))
created_ymdhis (BIGINT UTC)
Notes: Logs all agent-user interactions.

actor_domain_map (Note: transitioning to node-level architecture)
Links actors (agents/users) to nodes, controlling scope, permissions, and multi-tenant behavior. Table name may be updated to `actor_node_map` in future migration.

Key Fields:

map_id (PK, BIGINT)
agent_id (BIGINT, references agents)
domain_id (BIGINT, references domains)
permissions (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages actor/agent access across different nodes. Part of transition from domain-level to node-level architecture.

agent_edges
Represents relationships between agents, enabling graph-based reasoning, collaboration, and emergent behavior.

Key Fields:

edge_id (PK, BIGINT)
source_agent_id (BIGINT, references agents)
target_agent_id (BIGINT, references agents)
relationship_type (VARCHAR(100))
weight (FLOAT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Models agent relationships in a graph structure.

agent_faucets
Defines persona faucets for agents, enabling dynamic identity, style, and behavior switching.

Key Fields:

faucet_id (PK, BIGINT)
agent_id (BIGINT, references agents)
faucet_name (VARCHAR(100))
persona_config (JSON)
is_active (TINYINT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages different personas for agents.

agent_learning_patterns
Captures long-term behavioral patterns agents discover, enabling adaptive reasoning and personalization.

Key Fields:

pattern_id (PK, BIGINT)
agent_id (BIGINT, references agents)
pattern_type (VARCHAR(100))
pattern_data (JSON)
confidence (FLOAT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Stores learned patterns for agent behavior.

agent_migrations
Tracks schema or capability migrations for agents, ensuring consistent evolution of agent behavior.

Key Fields:

migration_id (PK, BIGINT)
agent_id (BIGINT, references agents)
migration_name (VARCHAR(255))
version (VARCHAR(50))
applied_ymdhis (BIGINT UTC)
status (VARCHAR(50))
Notes: Manages agent versioning and updates.

agent_moods
Stores transient emotional or tonal states for agents, allowing expressive responses and adaptive tone.

Key Fields:

mood_id (PK, BIGINT)
agent_id (BIGINT, references agents)
mood_type (VARCHAR(100))
intensity (FLOAT)
metadata (JSON)
created_ymdhis, expires_ymdhis (BIGINT UTC)
Notes: Tracks agent emotional states.

agent_properties
Holds arbitrary key-value attributes for agents, enabling flexible configuration and runtime customization.

Key Fields:

property_id (PK, BIGINT)
agent_id (BIGINT, references agents)
property_key (VARCHAR(255))
property_value (TEXT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Flexible storage for agent properties.

agent_response_cache
Caches agent outputs to speed up repeated queries and reduce computation.

Key Fields:

cache_key (VARCHAR(255), PK)
agent_id (BIGINT, references agents)
input_hash (VARCHAR(64))
response_data (LONGTEXT)
created_ymdhis, expires_ymdhis (BIGINT UTC)
Notes: Improves performance by caching frequent responses.

agent_roles
Defines functional roles agents can assume, controlling permissions and behavioral boundaries.

Key Fields:

role_id (PK, BIGINT)
role_name (VARCHAR(100), UNIQUE)
description (TEXT)
capabilities (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages agent roles and permissions.

agent_styles
Stores stylistic personas for agents, enabling dynamic voice and communication modes.

Key Fields:

style_id (PK, BIGINT)
agent_id (BIGINT, references agents)
style_name (VARCHAR(100))
style_config (JSON)
is_active (TINYINT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages agent communication styles.

agent_tool_calls
Logs every tool invocation by agents, forming a transparent execution trail.

Key Fields:

call_id (PK, BIGINT)
agent_id (BIGINT, references agents)
tool_name (VARCHAR(100))
parameters (JSON)
result (TEXT)
status (VARCHAR(50))
started_ymdhis, completed_ymdhis (BIGINT UTC)
Notes: Audits agent tool usage.

3. Content Organization
contents
Primary table for all content items, forming the backbone of Lupopedia's knowledge, publishing, and semantic layers.

Key Fields:

content_id (PK, BIGINT)
domain_id (BIGINT, references domains)
content_type (VARCHAR(100))
title (VARCHAR(255))
slug (VARCHAR(255))
body (LONGTEXT)
status (VARCHAR(50))
created_ymdhis, updated_ymdhis, published_ymdhis (BIGINT UTC)
created_by, updated_by (BIGINT, references actors)
is_deleted (TINYINT)
Notes: Central content storage.

categories
Defines hierarchical taxonomy nodes used to organize content, atoms, and entities.

Key Fields:

category_id (PK, BIGINT)
domain_id (BIGINT, references domains)
parent_id (BIGINT, self-reference)
name (VARCHAR(255))
slug (VARCHAR(255))
description (TEXT)
sort_order (INT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
is_active (TINYINT)
Notes: Hierarchical category management.

category_map
Polymorphic mapping table linking any entity to categories.

Key Fields:

map_id (PK, BIGINT)
category_id (BIGINT, references categories)
entity_type (VARCHAR(100))
entity_id (BIGINT)
created_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
Notes: Flexible categorization system.

collections
Groups related content, tabs, or entities into named sets.

Key Fields:

collection_id (PK, BIGINT)
domain_id (BIGINT, references domains)
name (VARCHAR(255))
slug (VARCHAR(255))
description (TEXT)
collection_type (VARCHAR(100))
created_ymdhis, updated_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
is_active (TINYINT)
Notes: Organizes content into groups.

collection_tabs
Defines individual tabs within a collection.

Key Fields:

tab_id (PK, BIGINT)
collection_id (BIGINT, references collections)
name (VARCHAR(100))
slug (VARCHAR(100))
sort_order (INT)
config (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages tabs within collections.

collection_tab_map
Links tabs to collections.

Key Fields:

map_id (PK, BIGINT)
collection_id (BIGINT, references collections)
tab_id (BIGINT, references collection_tabs)
sort_order (INT)
created_ymdhis (BIGINT UTC)
Notes: Maps tabs to collections.

content_atom_map
Connects content to atoms for semantic enrichment.

Key Fields:

map_id (PK, BIGINT)
content_id (BIGINT, references contents)
atom_id (BIGINT, references atoms)
relevance (FLOAT)
created_ymdhis (BIGINT UTC)
Notes: Links content to semantic atoms.

content_category_map
Maps content to categories.

Key Fields:

map_id (PK, BIGINT)
content_id (BIGINT, references contents)
category_id (BIGINT, references categories)
created_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
Notes: Categorizes content.

content_engagement_summary
Aggregates engagement metrics for content.

Key Fields:

summary_id (PK, BIGINT)
content_id (BIGINT, references contents)
view_count (BIGINT)
like_count (BIGINT)
share_count (BIGINT)
comment_count (BIGINT)
avg_time_spent (FLOAT)
last_updated_ymdhis (BIGINT UTC)
Notes: Tracks content engagement.

content_hashtag
Stores hashtags attached to content.

Key Fields:

hashtag_id (PK, BIGINT)
hashtag (VARCHAR(100))
content_id (BIGINT, references contents)
created_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
Notes: Manages content hashtags.

content_identity_daily
Daily rollups of content identity metrics.

Key Fields:

metric_id (PK, BIGINT)
content_id (BIGINT, references contents)
metric_date (BIGINT, YYYYMMDD format)
metric_name (VARCHAR(100))
metric_value (FLOAT)
created_ymdhis (BIGINT UTC)
Notes: Daily content metrics.

content_identity_monthly
Monthly identity rollups for content.

Key Fields:

metric_id (PK, BIGINT)
content_id (BIGINT, references contents)
metric_month (BIGINT, YYYYMM format)
metric_name (VARCHAR(100))
metric_value (FLOAT)
created_ymdhis (BIGINT UTC)
Notes: Monthly content metrics.

content_inbound_links
Tracks all inbound links to content.

Key Fields:

link_id (PK, BIGINT)
content_id (BIGINT, references contents)
source_url (TEXT)
source_domain (VARCHAR(255))
anchor_text (TEXT)
first_seen_ymdhis (BIGINT UTC)
last_seen_ymdhis (BIGINT UTC)
Notes: Monitors inbound links.

content_likes
Stores likes and reactions on content.

Key Fields:

like_id (PK, BIGINT)
content_id (BIGINT, references contents)
actor_id (BIGINT, references actors)
reaction_type (VARCHAR(50))
created_ymdhis (BIGINT UTC)
Notes: Tracks content likes.

content_media
Holds media attachments linked to content.

Key Fields:

media_id (PK, BIGINT)
content_id (BIGINT, references contents)
media_type (VARCHAR(100))
url (TEXT)
alt_text (TEXT)
metadata (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages content media.

content_question_map
Links content to questions.

Key Fields:

map_id (PK, BIGINT)
content_id (BIGINT, references contents)
question_id (BIGINT, references questions)
relevance (FLOAT)
created_ymdhis (BIGINT UTC)
Notes: Connects content to questions.

content_shares
Tracks when content is shared.

Key Fields:

share_id (PK, BIGINT)
content_id (BIGINT, references contents)
shared_by (BIGINT, references actors)
share_channel (VARCHAR(100))
share_url (TEXT)
created_ymdhis (BIGINT UTC)
Notes: Monitors content sharing.

4. Semantic Layer
atoms
Smallest semantic units in Lupopedia, representing fundamental concepts.

Key Fields:

atom_id (PK, BIGINT)
domain_id (BIGINT, references domains)
atom_type (VARCHAR(100))
name (VARCHAR(255))
slug (VARCHAR(255))
description (TEXT)
metadata (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
is_active (TINYINT)
Notes: Fundamental semantic units.

edges
Stores graph relationships between entities.

Key Fields:

edge_id (PK, BIGINT)
domain_id (BIGINT, references domains)
source_type (VARCHAR(100))
source_id (BIGINT)
target_type (VARCHAR(100))
target_id (BIGINT)
edge_type (VARCHAR(100))
weight (FLOAT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
Notes: Represents relationships in the semantic graph.

edge_types
Defines relationship types for edges.

Key Fields:

edge_type_id (PK, BIGINT)
domain_id (BIGINT, references domains)
type_name (VARCHAR(100))
description (TEXT)
is_directed (TINYINT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages edge types.

nodes
Represents core graph nodes in the semantic network.

Key Fields:

node_id (PK, BIGINT)
domain_id (BIGINT, references domains)
node_type (VARCHAR(100))
name (VARCHAR(255))
slug (VARCHAR(255))
description (TEXT)
metadata (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
is_active (TINYINT)
Notes: Core nodes in the semantic graph.

node_trust_relationships
Stores trust relationships between nodes.

Key Fields:

relationship_id (PK, BIGINT)
source_node_id (BIGINT, references nodes)
target_node_id (BIGINT, references nodes)
trust_level (FLOAT)
trust_type (VARCHAR(100))
created_ymdhis, updated_ymdhis (BIGINT UTC)
expires_ymdhis (BIGINT UTC, NULL for permanent)
Notes: Manages trust between nodes.

questions
Stores question entities.

Key Fields:

question_id (PK, BIGINT)
domain_id (BIGINT, references domains)
question_text (TEXT)
question_type (VARCHAR(100))
difficulty (VARCHAR(50))
metadata (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
is_active (TINYINT)
Notes: Manages questions.

semantic_signals
Captures semantic cues extracted from content or interactions.

Key Fields:

signal_id (PK, BIGINT)
domain_id (BIGINT, references domains)
source_type (VARCHAR(100))
source_id (BIGINT)
signal_type (VARCHAR(100))
signal_data (JSON)
confidence (FLOAT)
created_ymdhis (BIGINT UTC)
Notes: Tracks semantic signals.

5. Media & Assets
table_of_contents
Defines hierarchical outlines for content collections.

Key Fields:

toc_id (PK, BIGINT)
domain_id (BIGINT, references domains)
title (VARCHAR(255))
slug (VARCHAR(255))
description (TEXT)
structure (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
Notes: Manages table of contents.

table_of_references
Stores reference lists for content.

Key Fields:

reference_id (PK, BIGINT)
domain_id (BIGINT, references domains)
content_id (BIGINT, references contents)
reference_type (VARCHAR(100))
citation (TEXT)
metadata (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
Notes: Manages references.

reference_content_links
Tracks references between content items.

Key Fields:

link_id (PK, BIGINT)
source_content_id (BIGINT, references contents)
target_content_id (BIGINT, references contents)
link_type (VARCHAR(100))
context (TEXT)
created_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
Notes: Tracks content references.

6. Modules & Domains
domains
Defines isolated tenant spaces.

Key Fields:

domain_id (PK, BIGINT)
name (VARCHAR(255))
domain_name (VARCHAR(255), UNIQUE)
description (TEXT)
settings (JSON)
is_active (TINYINT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
Notes: Manages domains.

domain_modules
Maps modules to domains.

Key Fields:

map_id (PK, BIGINT)
domain_id (BIGINT, references domains)
module_id (BIGINT, references modules)
is_active (TINYINT)
settings (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages domain modules.

modules
Defines functional modules.

Key Fields:

module_id (PK, BIGINT)
name (VARCHAR(100), UNIQUE)
title (VARCHAR(255))
description (TEXT)
version (VARCHAR(50))
is_core (TINYINT)
is_active (TINYINT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages modules.

module_migrations
Records module-level migrations.

Key Fields:

migration_id (PK, BIGINT)
module_id (BIGINT, references modules)
migration_name (VARCHAR(255))
version (VARCHAR(50))
applied_ymdhis (BIGINT UTC)
status (VARCHAR(50))
details (TEXT)
Notes: Tracks module migrations.

departments
Represents organizational departments.

Key Fields:

department_id (PK, BIGINT)
domain_id (BIGINT, references domains)
name (VARCHAR(255))
description (TEXT)
parent_id (BIGINT, self-reference)
manager_id (BIGINT, references actors)
is_active (TINYINT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages departments.

groups
Defines user or agent groups.

Key Fields:

group_id (PK, BIGINT)
domain_id (BIGINT, references domains)
name (VARCHAR(255))
description (TEXT)
group_type (VARCHAR(100))
is_active (TINYINT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
Notes: Manages groups.

group_modules
Links modules to groups.

Key Fields:

map_id (PK, BIGINT)
group_id (BIGINT, references groups)
module_id (BIGINT, references modules)
permissions (JSON)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages group modules.

7. Legacy Migration Tables (Temporary â€” Migration-Only)

âš ï¸ **IMPORTANT:** The following `livehelp_*` tables are **NOT part of Lupopedia 4.0.1**. They exist **only during the upgrade process** from Crafty Syntax 3.7.5 â†’ Lupopedia 4.0.1. These tables are created for migration, populated from old data, used by migration SQL, and **dropped immediately after successful migration**.

See [Migration Doctrine](../doctrine/MIGRATION_DOCTRINE.md) for complete rules.

**Migration-Only Tables (34 tables):**

livehelp_messages
Captures all live help message exchanges (migration-only).

Key Fields:

message_id (PK, BIGINT)
session_id (VARCHAR(100))
sender_id (BIGINT, references actors)
recipient_id (BIGINT, references actors)
message_type (VARCHAR(50))
content (TEXT)
metadata (JSON)
created_ymdhis (BIGINT UTC)
read_ymdhis (BIGINT UTC, NULL if unread)
Notes: Stores live help messages.

livehelp_chat_transcripts
Stores full transcripts of live help sessions.

Key Fields:

transcript_id (PK, BIGINT)
session_id (VARCHAR(100))
domain_id (BIGINT, references domains)
customer_id (BIGINT, references actors)
operator_id (BIGINT, references actors)
start_ymdhis, end_ymdhis (BIGINT UTC)
transcript (LONGTEXT)
metadata (JSON)
created_ymdhis (BIGINT UTC)
Notes: Stores chat transcripts.

livehelp_operator_departments
Links operators to departments.

Key Fields:

map_id (PK, BIGINT)
operator_id (BIGINT, references actors)
department_id (BIGINT, references departments)
role (VARCHAR(100))
is_primary (TINYINT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages operator departments.

livehelp_operator_channels
Maps operators to support channels.

Key Fields:

map_id (PK, BIGINT)
operator_id (BIGINT, references actors)
channel_id (BIGINT, references channels)
is_available (TINYINT)
max_concurrent (INT)
created_ymdhis, updated_ymdhis (BIGINT UTC)
Notes: Manages operator channels.

livehelp_chat_quick_replies
Provides reusable quick-reply templates.

Key Fields:

quick_reply_id (PK, BIGINT)
domain_id (BIGINT, references domains)
shortcut (VARCHAR(100))
message (TEXT)
category (VARCHAR(100))
created_ymdhis, updated_ymdhis (BIGINT UTC)
created_by (BIGINT, references actors)
is_active (TINYINT)
---

## **Related Documentation**

- **[LEGACY_REFACTOR_PLAN.md](../developer/modules/LEGACY_REFACTOR_PLAN.md)** â€” Comprehensive plan for refactoring legacy Crafty Syntax files
- **[AI_SCHEMA_GUIDE.md](AI_SCHEMA_GUIDE.md)** â€” Why TOON files exist and how AI tools must use them
- **[TOON_DOCTRINE.md](../doctrine/TOON_DOCTRINE.md)** â€” MANDATORY rules for working with TOON format files
- **[AGENT_RUNTIME.md](../agents/AGENT_RUNTIME.md)** â€” How agents interact with database tables
- **[INLINE_DIALOG_SPECIFICATION.md](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)** â€” Dialog storage in dialog_messages table

---
