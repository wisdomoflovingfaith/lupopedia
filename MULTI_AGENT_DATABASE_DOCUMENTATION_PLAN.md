# ⭐ CAPTAIN DIRECTIVE — MULTI-AGENT DATABASE DOCUMENTATION PLAN

**From:** Captain Wolfie (actor_id 10000)
**Agents:**

* KIRO (actor_id 100)
* JetBrains IDE (actor_id 103) 
* Antigravity IDE (actor_id 42)
* Windsurf IDE (actor_id 101)
* Cursor IDE (actor_id 102)

**Mission:**
Document **all Lupopedia database tables (222 TOON files found)** using TOON definitions and generate canonical documentation. Incorporate existing migrations for livehelp_* tables and update existing MD documentation files. Identify and handle documentation for any removed tables by categorizing them under `deprecated/` and noting their status.

Documentation will be produced under:

```
lupo-docs/database/lupopedia/tables/
```

Agents will work **in parallel** with clearly separated domains. All agents must reference the extensive MD documentation in the project for their configurations, including agent_ids from the canonical registry. Do not guess or assume any details—always consult the project MD files, TOON definitions, and existing paths directly.

---

# SYSTEM ARCHITECTURE OF THE TASK

The workflow will follow **four phases**:

1️⃣ Schema ingestion
2️⃣ Domain assignment  
3️⃣ Documentation generation
4️⃣ Global validation

---

# PHASE 1 — GLOBAL SCHEMA INGESTION (KIRO)

⭐ **KIRO is the schema coordinator**

KIRO must read every TOON definition from:

```
lupo-database/lupopedia/toon/
```

**Current TOON Inventory:** 222 files found
- **lupo_* tables:** 180+ core Lupopedia tables
- **livehelp_* tables:** 40+ legacy Crafty Syntax tables

Each TOON describes:

* table name
* columns  
* column types
* constraints
* relationships
* metadata

KIRO must also cross-reference all relevant MD documentation in the project, including existing MD files in `lupo-docs/database/lupopedia/tables/` (268 files found), to ensure accuracy, including any updates to agent configurations, table domains, or identification of removed/deprecated tables.

KIRO must build a **complete schema registry**, noting any discrepancies such as tables present in existing documentation but absent from current TOONs (mark as potentially removed/deprecated).

Output file:

```
lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md
```

The registry must include:

| Table | Domain | Description | Assigned Agent | Status (Active/Deprecated/Removed/Migration) | Existing Doc Path (if any) |

KIRO will then assign tables to agents based on **domain responsibility**, using project MD documentation to guide assignments without assumptions.

---

# PHASE 2 — TABLE DOMAIN ASSIGNMENT

Tables will be split by **system responsibility**, not randomly.

This prevents agents from documenting related tables inconsistently. Use project MD documentation, existing MD files, and TOON files to identify and confirm exact table lists for each domain—do not assume or guess table inclusions. Flag any tables in existing documentation that appear removed (no current TOON) for handling under deprecated/.

---

# 🧠 KIRO — CORE SYSTEM TABLES

KIRO documents **core Lupopedia architecture tables**.

These include (confirm full list from project MD, existing docs, TOON files):

### Actor System

```
lupo_actors
lupo_actor_actions
lupo_actor_aliases  
lupo_actor_capabilities
lupo_actor_channel_roles
lupo_actor_channels
lupo_actor_collections
lupo_actor_conflicts
lupo_actor_departments
lupo_actor_edges
lupo_actor_events
lupo_actor_handshakes
lupo_actor_history
lupo_actor_moods
lupo_actor_object_edges
lupo_actor_persona_relationships
lupo_actor_relationship_rules
lupo_actor_reply_templates
lupo_actor_truth_edges
```

### Channels & Messaging

```
lupo_channels
lupo_dialog_channels
lupo_dialog_messages
lupo_dialog_threads
lupo_channel_boot_detail
lupo_channel_boot_detail_lifecycle
lupo_channel_boot_log
lupo_channel_content
lupo_channel_escalation_rules
lupo_channel_escalations
lupo_channel_files
lupo_channel_log_types
lupo_channel_logs
lupo_channel_state
```

### Metadata & FLARE

```
lupo_metadata
lupo_edges
lupo_file_index
lupo_headers
lupo_registry
lupo_registry_open
lupo_registry_import
```

### Core Governance

```
lupo_permissions
lupo_audit_log
lupo_auth_audit_log
lupo_governance_overrides
lupo_doctrine_evolution_audit
lupo_hotfix_registry
```

KIRO is responsible for documenting the **semantic OS core**.

Estimated tables: **50–60** (verify against project documentation, existing MD files, and TOON files).

---

# 🧠 JETBRAINS IDE — APPLICATION STRUCTURE TABLES

JetBrains documents **organizational and content structure tables**.

These include (confirm full list from project MD, existing docs, TOON files):

### Collections

```
lupo_collections
lupo_collection_items
lupo_collection_members
lupo_collection_tabs
lupo_collection_tab_map
lupo_collection_tab_paths
```

### Departments

```
lupo_departments
lupo_department_roles
lupo_department_metadata
lupo_modules
lupo_modules_departments
```

### Knowledge System

```
lupo_contents
lupo_topics
lupo_articles
lupo_references
lupo_help_topics
lupo_help_tree
lupo_truth_knowledge
lupo_truth_answers
```

### Artifacts

```
lupo_artifacts
lupo_artifact_versions
lupo_artifact_relationships
lupo_artifact_chunks
lupo_flip_artifacts
lupo_search_index
lupo_search_rebuild_log
lupo_semantic_index
lupo_document_embeddings
```

These represent the **knowledge graph layer**.

Estimated tables: **40–50** (verify against project documentation, existing MD files, and TOON files).

---

# 🧠 ANTIGRAVITY IDE — FEDERATION + FILESYSTEM TABLES

Antigravity handles **filesystem mirroring and federation logic**.

These include (confirm full list from project MD, existing docs, TOON files):

### Federation

```
lupo_federated_trust
lupo_federation_categories
lupo_federation_category_map
lupo_federation_discovery
lupo_federation_nodes
```

### Import/Export

```
lupo_import_queue
lupo_export_queue
lupo_sync_status
lupo_uploads
lupo_legacy_content_mapping
```

### File System Operations

```
lupo_anubis_deletion_log
lupo_anubis_events
lupo_anubis_log
lupo_anubis_mirrored
lupo_anubis_orphaned
lupo_anubis_processing_log
lupo_anubis_quarantine
lupo_anubis_queue
lupo_anubis_recovery_attempts
lupo_anubis_redirects
lupo_anubis_revised
```

### Channel Filesystem

```
lupo-channels/*
artifacts/*
```

These tables relate to the **file-based persistence layer**.

Estimated tables: **40–50** (verify against project documentation, existing MD files, and TOON files).

---

# 🧠 WINDSURF IDE — MIGRATION + LEGACY TABLES

Windsurf handles **all legacy and migration tables**, including livehelp_* tables.

This includes (confirm full list from project MD, existing docs, TOON files):

```
livehelp_autoinvite
livehelp_channels
livehelp_config
livehelp_departments
livehelp_emailque
livehelp_emails
livehelp_identity_daily
livehelp_identity_monthly
livehelp_keywords_daily
livehelp_keywords_monthly
livehelp_layerinvites
livehelp_leads
livehelp_leavemessage
livehelp_messages
livehelp_modules
livehelp_modules_dep
livehelp_operator_channels
livehelp_operator_departments
livehelp_operator_history
livehelp_paths_firsts
livehelp_paths_monthly
livehelp_qa
livehelp_questions
livehelp_quick
livehelp_referers_daily
livehelp_referers_monthly
livehelp_sessions
livehelp_smilies
livehelp_transcripts
livehelp_users
livehelp_visit_track
livehelp_visits_daily
livehelp_visits_monthly
livehelp_websites
```

and all **Crafty Syntax migration tables**.

These must be categorized as:

```
migrations/
```

For any migration-related tables, incorporate details from the existing documentation and migration references.

Estimated tables: **40+** (verify against project documentation, existing MD files, and TOON files).

---

# 🧠 CURSOR IDE — USER MANAGEMENT + AUTHENTICATION TABLES

Cursor handles **user management, authentication, and related access control tables**.

These include (confirm full list from project MD, existing docs, TOON files):

### User Profiles

```
lupo_auth_users
lupo_users
lupo_user_profiles
lupo_user_sessions
lupo_sessions
lupo_session_events
lupo_session_recovery
```

### Authentication

```
lupo_auth_providers
lupo_api_clients
lupo_api_rate_limits
lupo_api_token_logs
lupo_api_tokens
lupo_api_webhooks
```

### Access Control

```
lupo_banned_actors
lupo_bans_log
lupo_capabilities
lupo_capability_usage
```

### Agent System

```
lupo_agents
lupo_agent_context_snapshots
lupo_agent_dependencies
lupo_agent_experiences
lupo_agent_external_events
lupo_agent_faucet_credentials
lupo_agent_faucets
lupo_agent_files
lupo_agent_heartbeats
lupo_agent_tool_calls
lupo_agent_versions
```

These represent the **user and security layer**.

Estimated tables: **40–50** (verify against project documentation, existing MD files, and TOON files; adjust assignments if overlaps or gaps are found in MD files).

---

# PHASE 3 — DOCUMENTATION GENERATION

Each agent must check for existing documentation files in `lupo-docs/database/lupopedia/tables/<category>/<table>.md`. If an existing file is found, update it with current TOON details, incorporating any new information while preserving relevant historical notes. If no file exists, create a new one.

For tables identified as removed (present in existing docs but absent from current TOONs), document them under `deprecated/` and include notes on removal status, last known version, and any migration impacts.

Categories:

```
active/
deprecated/  
migrations/
```

Before generating or updating, cross-reference project MD documentation, existing MD files, TOONs, and migration references to ensure no assumptions about table details, categories, or relationships.

---

# REQUIRED DOCUMENT FORMAT

Each table doc must contain:

## 1. FLARE HEADER

Derived from:

* `file_path_from_root` 
* `web_path` 
* `federation_node_id` 

Example:

```yaml
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md"
  system_version: "4.0.69"
  channel_id: 42
  actor_id: <agent>  # Use your documented actor_id from registry
  artifact_type: "documentation"
  artifact_kind: "database_table"
```

Update with any existing header details if updating a file.

---

## 2. Table Overview

Include:

* purpose
* category
* version introduced
* version deprecated (if applicable)
* removal notes (if removed)
* references to existing migrations

All details sourced directly from TOON, project MD files, existing docs, and migrations.

---

## 3. Column Documentation

For each column:

| Column | Type | Null | Default | Description |

Sourced directly from TOON, project MD files, existing docs, and migrations. Note any changes from previous versions.

---

## 4. Relationships

Document:

* foreign keys
* inbound references  
* join patterns

Sourced directly from TOON, project MD files, existing docs, and migrations. Note any deprecated relationships.

---

## 5. Usage Notes

Include:

* migration notes
* compatibility
* warnings
* future plans
* historical changes (if updating existing doc)

Sourced directly from project MD files, existing docs, and migrations.

---

# PHASE 4 — GLOBAL VALIDATION (KIRO)

After all agents complete their work, **KIRO performs the validation pass**.

KIRO must verify:

✔ every table (including from migrations and existing docs) has documentation
✔ every file contains a valid FLARE header
✔ folder placement is correct (active/, deprecated/, migrations/)
✔ categories are correct, with removed tables under deprecated/
✔ no table is missing, and no orphaned docs (docs without corresponding TOON or migration)
✔ consistency with existing migrations

Cross-reference against project MD documentation, existing MD files, TOONs, and migrations for completeness. Flag any discrepancies, such as docs for removed tables.

Output:

```
lupo-docs/database/lupopedia/tables/VALIDATION_REPORT.md
```

---

# MASTER INDEX

Generated by **JetBrains IDE**:

```
lupo-docs/database/lupopedia/tables/TABLE_INDEX.md
```

Format:

| Table | Category | Agent | Description | Status (Active/Deprecated/Removed/Migration) | Existing Migration Ref (if any) |

Populate using verified data from schema registry, project MD files, existing docs, and migrations.

---

# FINAL CONFIRMATION FORMAT

Each agent must respond when finished, using their confirmed actor_id from project registry.

### KIRO

```
KIRO: Core Lupopedia tables documented.
Schema registry created.
Validation ready.
```

### JetBrains

```
JETBRAINS: Knowledge and organizational tables documented.
Master index generated.
```

### Antigravity

```
ANTIGRAVITY: Federation and filesystem tables documented.
Artifact storage schema verified.
```

### Windsurf

```
WINDSURF: Migration and legacy tables documented, including livehelp_* tables.
Crafty Syntax mappings preserved.
```

### Cursor

```
CURSOR: User management and authentication tables documented.
Security layer verified.
```

---

# Expected Outcome

After completion the repository will contain:

```
lupo-docs/database/lupopedia/tables/

active/
deprecated/
migrations/

TABLE_INDEX.md
SCHEMA_REGISTRY.md
VALIDATION_REPORT.md
```

Every table in Lupopedia, including from existing migrations and docs, will have **complete canonical documentation**, aligned with project MD files. Documentation for removed tables will be preserved under deprecated/ with appropriate notes.

---

✅ This allows **5 agents to document 222+ tables in parallel safely**, incorporating existing migrations and documentation.

---

# CURRENT SYSTEM STATUS

**Version:** 4.0.69 (from global_atoms.yaml)
**TOON Files:** 222 total (found in lupo-database/lupopedia/toon/)
**Existing Documentation:** 268 files (found in lupo-docs/database/lupopedia/tables/)
**Table Ceiling:** 222 tables maximum (per doctrine)

---

# AGENT ID REFERENCES (from canonical registry)

- KIRO: actor_id 100
- JetBrains IDE: actor_id 103  
- Antigravity IDE: actor_id 42
- Windsurf IDE: actor_id 101
- Cursor IDE: actor_id 102
- Captain Wolfie: actor_id 10000

---

# ALTERNATIVE: AUTOMATIC PREFIX-BASED ASSIGNMENT

If you want, I can also show you a **much better way to split the work automatically** (so agents **self-assign tables based on prefixes like `lupo_`, `livehelp_`, `artifact_`, etc.**) which reduces coordination overhead dramatically.
