---
lupopedia.init:
  required_reading:
    - path: "lupo-docs/INIT_README.md"
      reason: "Prerequisites and init doctrine"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      reason: "Header format and block order"
  required_context:
    - "Canonical schema authority and project model resolution; resolves all LILITH-identified gaps."

lupopedia.metadata:
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "WOLFIE Directive — Canonical Project Model, Schema Authority, and Migration Contract", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260321_071000, updated_ymdhis: 20260321_071000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Canonical system definition resolving project model, schema authority, migration, identity rules, web_path, and actor roles", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260321_071000, updated_ymdhis: 20260321_071000 }
  keywords:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "schema_authority, project_model, migration, identity_rules, web_path, actor_roles, lupo_projects, lupo_actor_projects", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260321_071000, updated_ymdhis: 20260321_071000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "wolfie", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260321_071000, updated_ymdhis: 20260321_071000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cascade", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260321_071000, updated_ymdhis: 20260321_071000 }

lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "lupo-channels/42/threads/1032/20260321_071000_wolfie_directive_canonical_project_model_schema_authority_migration_contract.md"
  last_modified_utc: "20260321"
  channel_id: 42
  thread_id: 1032
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cascade"
  artifact_type: "directive"
  artifact_kind: "system_definition"
  purpose: "Canonical project model, schema authority, and migration contract - resolves all LILITH-identified gaps"
  tags: ["wolfie", "directive", "schema_authority", "project_model", "migration", "identity_rules", "web_path", "actor_roles", "4.0.84", "canonical", "system_definition"]

lupopedia.edges:
  comment: "Schema authority and project model resolution directive."
  meta: "Canonical system definition; resolves LILITH gaps."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "modifies", weight: 1.0, reason: "Schema changes implementation" }
    - { to: "lupo-docs/doctrine/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "references", weight: 0.9, reason: "Coordination doctrine" }
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "updates", weight: 0.8, reason: "Thread registration" }
  semantic_tags: ["schema_authority", "project_model", "migration", "canonical", "system_definition"]

lupopedia.footer:
  version: "4.0.84"
  last_verified: "20260321"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "HEPHAESTUS implements schema changes"
    - "THOTH documents after implementation"
    - "LILITH audits compliance"
    - "Update all project-scoped tables"
---

# WOLFIE Directive — Canonical Project Model, Schema Authority, and Migration Contract (4.0.84)

**Thread ID:** 1032  
**Channel:** 42 (Protocol Development)  
**Actor:** WOLFIE (actor_id 1)  
**Type:** SYSTEM DEFINITION DIRECTIVE  
**Status:** BINDING AUTHORITY

---

## 1. SCHEMA AUTHORITY RULE (MANDATORY)

**CANONICAL AUTHORITY CHAIN:**

1. **WOLFIE ONLY** - Can authorize schema changes
2. **HEPHAESTUS** - Implements schema changes (no authority to modify)
3. **THOTH** - Documents AFTER implementation (no authority to change)
4. **LILITH** - Audits compliance (no authority to alter)

**ENFORCEMENT:**
- Schema changes require WOLFIE directive artifact
- HEPHAESTUS must implement exactly as specified
- THOTH must document actual implementation
- LILITH must verify compliance with directive

**CLOSURE:** This eliminates all authority gaps. Schema becomes authoritative.

---

## 2. PROJECT MODEL (CANONICAL DEFINITION)

### 2.1 Core Project Table

**lupo_projects table EXISTS (canonical):**

```sql
CREATE TABLE lupo_projects (
  project_id bigint NOT NULL,
  project_key varchar(64) NOT NULL,
  project_name varchar(255) NOT NULL,
  project_slug varchar(100) NOT NULL,
  description text,
  owner_actor_id bigint NOT NULL,
  created_by_actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint,
  project_metadata json,
  PRIMARY KEY (project_id)
);
```

### 2.2 Project Scoping Requirements

**project_id REQUIRED on:**
- ✅ lupo_channels
- ✅ lupo_dialog_threads  
- ✅ lupo_tasks
- ✅ lupo_edges
- ✅ lupo_metadata

**MIGRATION RULE:**
- `project_id = 0` → system/default project
- All existing installations default to `project_id = 0`
- No data loss during migration

---

## 3. MIGRATION PATH (DETERMINISTIC)

### 3.1 ALTER TABLE Statements

**lupo_channels:**
```sql
ALTER TABLE lupo_channels ADD COLUMN project_id bigint NOT NULL DEFAULT 0;
```

**lupo_dialog_threads:**
```sql
ALTER TABLE lupo_dialog_threads ADD COLUMN project_id bigint NOT NULL DEFAULT 0;
```

**lupo_tasks:**
```sql
ALTER TABLE lupo_tasks ADD COLUMN project_id bigint NOT NULL DEFAULT 0;
```

**lupo_edges:**
```sql
ALTER TABLE lupo_edges ADD COLUMN project_id bigint NOT NULL DEFAULT 0;
```

**lupo_metadata:**
```sql
ALTER TABLE lupo_metadata ADD COLUMN project_id bigint NOT NULL DEFAULT 0;
```

### 3.2 Backfill Rules

**ALL EXISTING DATA → project_id = 0**
- Deterministic backfill only
- No data loss
- No conditional logic
- Single-pass migration

### 3.3 Index Updates

**Add project_id indexes:**
```sql
CREATE INDEX lupo_channels_idx_project_id ON lupo_channels(project_id);
CREATE INDEX lupo_dialog_threads_idx_project_id ON lupo_dialog_threads(project_id);
CREATE INDEX lupo_tasks_idx_project_id ON lupo_tasks(project_id);
CREATE INDEX lupo_edges_idx_project_id ON lupo_edges(project_id);
CREATE INDEX lupo_metadata_idx_project_id ON lupo_metadata(project_id);
```

---

## 4. lupo_atoms IDENTITY RULE (COLLISION RESOLUTION)

### 4.1 Identity Definition

**atom identity = (project_id, namespace, atom_path)**

- Triple-tuple identity is absolute
- No symbolic linking allowed
- No automatic merging
- No overwriting on collision

### 4.2 Collision Resolution

**IF COLLISION DETECTED:**
1. **REJECT WRITE** - Do not allow operation
2. **LOG ERROR** - Record collision attempt
3. **RETURN ERROR** - Explicit rejection to caller
4. **NO MODIFICATION** - Existing data unchanged

**ENFORCEMENT:**
- Application layer validates uniqueness
- Database layer provides unique index on (project_id, namespace, atom_path)
- No automatic resolution strategies

---

## 5. ACTOR PROJECT MEMBERSHIP (FIXED MODEL)

### 5.1 Canonical Membership Table

**lupo_actor_projects table:**

```sql
CREATE TABLE lupo_actor_projects (
  actor_project_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  project_id bigint NOT NULL,
  role varchar(64) NOT NULL DEFAULT 'member',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint,
  PRIMARY KEY (actor_project_id)
);
```

### 5.2 Role Mutability Rules

**role is mutable:**
- Changes must update `updated_ymdhis`
- Audit via `lupo_metadata` or log tables
- No schema complexity for audit trails

**VALID ROLES:**
- 'owner' - Full project control
- 'admin' - Administrative access
- 'member' - Standard participation
- 'viewer' - Read-only access

---

## 6. web_path RULE (RESOLVED)

### 6.1 CHOICE: Root-Domain Only (RECOMMENDED)

**DECISION:** web_path ignores project

**RULE:**
- `web_path = "http://www.lupopedia.com/ENTITY"`
- Projects are internal grouping only
- No project namespace in URLs
- Project context via session/auth

**RATIONALE:**
- Simpler URL structure
- Projects as internal organization
- No URL collision issues
- Better SEO consistency

---

## 7. EDGE SCOPING RULE (CRITICAL)

### 7.1 Required Edge Fields

**lupo_edges MUST include:**
- ✅ project_id (required)
- ✅ federation_node_id (required)

**VALIDATION:**
- Edges CANNOT exist without both fields
- NULL values forbidden
- Application layer enforces requirement

### 7.2 Cross-Project Edges

**PHASE 1: FORBIDDEN**
- No cross-project edges allowed
- All edges must be within same project
- Enforcement via application layer

**FUTURE: EXPLICIT FLAG**
- Cross-project edges require explicit flag
- Phase 2+ feature only
- Not implemented in current scope

---

## 8. INSTALL SQL CHANGES (COMPLETE)

### 8.1 New Tables

**lupo_projects:**
```sql
CREATE TABLE lupo_projects (
  project_id bigint NOT NULL,
  project_key varchar(64) NOT NULL,
  project_name varchar(255) NOT NULL,
  project_slug varchar(100) NOT NULL,
  description text,
  owner_actor_id bigint NOT NULL,
  created_by_actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint,
  project_metadata json,
  PRIMARY KEY (project_id),
  UNIQUE KEY lupo_projects_unq_project_key (project_key),
  UNIQUE KEY lupo_projects_unq_project_slug (project_slug),
  INDEX lupo_projects_idx_owner_actor_id (owner_actor_id),
  INDEX lupo_projects_idx_created_by_actor_id (created_by_actor_id),
  INDEX lupo_projects_idx_created_ymdhis (created_ymdhis),
  INDEX lupo_projects_idx_is_deleted (is_deleted)
);
```

**lupo_actor_projects:**
```sql
CREATE TABLE lupo_actor_projects (
  actor_project_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  project_id bigint NOT NULL,
  role varchar(64) NOT NULL DEFAULT 'member',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint,
  PRIMARY KEY (actor_project_id),
  UNIQUE KEY lupo_actor_projects_unq_actor_project (actor_id, project_id),
  INDEX lupo_actor_projects_idx_actor_id (actor_id),
  INDEX lupo_actor_projects_idx_project_id (project_id),
  INDEX lupo_actor_projects_idx_role (role),
  INDEX lupo_actor_projects_idx_created_ymdhis (created_ymdhis),
  INDEX lupo_actor_projects_idx_updated_ymdhis (updated_ymdhis),
  INDEX lupo_actor_projects_idx_is_deleted (is_deleted)
);
```

### 8.2 Table Alterations

**All ALTER statements included in Section 3.1**

### 8.3 Index Creation

**All index statements included in Section 3.3**

---

## 9. ENFORCEMENT RULES (MANDATORY)

### 9.1 Database Constraints

**FORBIDDEN:**
- ❌ Foreign keys
- ❌ Triggers
- ❌ Stored procedures
- ❌ AUTO_INCREMENT
- ❌ Vendor-specific time types

**REQUIRED:**
- ✅ BIGINT IDs only
- ✅ Application-supplied IDs
- ✅ BIGINT timestamps (YYYYMMDDHHIISS)
- ✅ Application layer enforcement

### 9.2 Deterministic Behavior

**RULES:**
- No automatic conflict resolution
- No implicit data transformations
- No hidden business logic in database
- All constraints explicit in application layer

---

## 10. IMPLEMENTATION SEQUENCE

### 10.1 Phase 1: Schema Implementation
1. HEPHAESTUS implements all schema changes
2. Migration runs with deterministic backfill
3. All existing data → project_id = 0

### 10.2 Phase 2: Application Logic
1. Update application layer for project scoping
2. Implement identity collision detection
3. Add actor project membership logic

### 10.3 Phase 3: Documentation
1. THOTH documents actual implementation
2. Update all table documentation
3. Verify schema authority chain

### 10.4 Phase 4: Audit
1. LILITH audits compliance
2. Verify all rules enforced
3. Report any deviations

---

## 11. SUCCESS CRITERIA

### 11.1 Schema Authority
- ✅ WOLFIE only can authorize changes
- ✅ Clear implementation chain
- ✅ Documentation follows schema

### 11.2 Project Model
- ✅ All tables have project_id
- ✅ Migration complete
- ✅ No data loss

### 11.3 Identity Resolution
- ✅ No collisions allowed
- ✅ Explicit rejection on conflict
- ✅ No symbolic linking

### 11.4 Web Path
- ✅ Root-domain only URLs
- ✅ Projects internal only
- ✅ No ambiguity

### 11.5 Edge Scoping
- ✅ All edges have project_id
- ✅ No cross-project edges
- ✅ Federation node scoping

---

## 12. NEXT ACTIONS

1. **IMMEDIATE:** HEPHAESTUS implements schema changes
2. **FOLLOWING:** THOTH documents implementation
3. **FINAL:** LILITH audits compliance

---

## 13. AUTHORITY DECLARATION

**THIS DIRECTIVE IS BINDING AUTHORITY**

- Schema changes must be implemented exactly as specified
- No deviations allowed without new WOLFIE directive
- All LILITH-identified gaps are resolved
- System becomes enforceable after implementation

**WOLFIE (actor_id 1) - Canonical Orchestrator**
**Channel 42 - Protocol Development**
**Thread 1032 - Project Model Resolution**

---

*End of Directive*
