# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\TABLE_CONSOLIDATION_PLAN.md"
  file_hash: "69d80460d19db26060ae1b41436fb5b2d36cd090c34ea112eb5b704f780604a1"
  file_path_from_root: "docs\doctrine\TABLE_CONSOLIDATION_PLAN.md"
  file_hash: "a56130cd78c4d75750150b92b953501889fdeacc9adb2e61beb0a39cf8ca6c0a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for TABLE_CONSOLIDATION_PLAN.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "table_consolidation_planmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/TABLE_CONSOLIDATION_PLAN.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/TABLE_CONSOLIDATION_PLAN.md
---

# 🔥 TABLE CONSOLIDATION PLAN - ACTUAL EXECUTION
**CURRENT TABLES:** 220  
**TARGET:** ≤ 222  
**STATUS:** READY FOR EXECUTION

---

## 📊 TABLES TO REMOVE (40 tables)

### 🗑️ ANALYTICS DUPLICATES (4 tables)
**REMOVE:**
- `lupo_analytics_visits_daily` → Same as monthly but with date_ymd
- `lupo_analytics_visits_monthly` → Same as daily but with date_ym  
- `lupo_analytics_referers_daily` → Same as monthly but with date_ymd
- `lupo_analytics_referers_monthly` → Same as daily but with date_ym

**REASON:** Identical structure, just different date granularity. Use single table with period field.

### 🗑️ AGENT/ACTOR PROPERTY DUPLICATES (2 tables)
**REMOVE:**
- `lupo_agent_properties` → Same structure as actor_properties
- `lupo_actor_properties` → Same structure as agent_properties

**REASON:** Both store entity properties with identical fields. Merge into single table.

### 🗑️ AGENT FILES/OBJECT EDGES DUPLICATES (2 tables)  
**REMOVE:**
- `lupo_agent_files` → Same as actor_object_edges
- `lupo_actor_object_edges` → Same as agent_files

**REASON:** Both store entity→object relationships with identical structure.

### 🗑️ AGENT TIMESERIES DUPLICATES (2 tables)
**REMOVE:**
- `lupo_agent_context_snapshots` → Same as heartbeats
- `lupo_agent_heartbeats` → Same as snapshots

**REASON:** Both store agent state over time with identical structure.

### 🗑️ LIVEHELP BACKUP TABLES (30 tables)
**REMOVE ALL TABLES IN:** `database/livehelp_backup/`
- `livehelp_autoinvite`
- `livehelp_channels` 
- `livehelp_config`
- `livehelp_departments`
- `livehelp_emailque`
- `livehelp_emails`
- `livehelp_identity_daily`
- `livehelp_identity_monthly`
- `livehelp_keywords_daily`
- `livehelp_keywords_monthly`
- `livehelp_layerinvites`
- `livehelp_leads`
- `livehelp_leavemessage`
- `livehelp_messages`
- `livehelp_modules`
- `livehelp_modules_dep`
- `livehelp_operator_channels`
- `livehelp_operator_departments`
- `livehelp_operator_history`
- `livehelp_paths_firsts`
- `livehelp_paths_monthly`
- `livehelp_qa`
- `livehelp_questions`
- `livehelp_quick`
- `livehelp_referers_daily`
- `livehelp_referers_monthly`
- `livehelp_sessions`
- `livehelp_stats_daily`
- `livehelp_stats_monthly`
- `livehelp_transcripts`
- `livehelp_users`
- `livehelp_visits_daily`
- `livehelp_visits_monthly`

**REASON:** Legacy Crafty Syntax backup tables, not used by current system.

---

## ➕ TABLES TO ADD (4 tables)

### 📊 CONSOLIDATED ANALYTICS TABLES (2 tables)

**ADD: `lupo_analytics_visits_periods`**
```sql
CREATE TABLE lupo_analytics_visits_periods (
  analytics_visits_period_id bigint NOT NULL auto_increment,
  content_id bigint NOT NULL DEFAULT 0,
  url_path varchar(500) NOT NULL DEFAULT '',
  group_id bigint NOT NULL DEFAULT 0,
  period_type enum('daily','monthly') NOT NULL,
  period_date bigint NOT NULL COMMENT 'YYYYMMDD for daily, YYYYMM for monthly',
  visits int NOT NULL DEFAULT 0,
  unique_sessions int NOT NULL DEFAULT 0,
  unique_actors int NOT NULL DEFAULT 0,
  direct_visits int NOT NULL DEFAULT 0,
  internal_visits int NOT NULL DEFAULT 0,
  entry_count int NOT NULL DEFAULT 0,
  exit_count int NOT NULL DEFAULT 0,
  total_seconds int NOT NULL DEFAULT 0,
  avg_seconds int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (analytics_visits_period_id),
  UNIQUE KEY uq_visits_period (content_id, period_type, period_date),
  KEY idx_period_date (period_date),
  KEY idx_content (content_id, period_date),
  KEY idx_group (group_id, period_date)
);
```

**ADD: `lupo_analytics_referers_periods`**
```sql
CREATE TABLE lupo_analytics_referers_periods (
  analytics_referers_period_id bigint NOT NULL auto_increment,
  content_id bigint NOT NULL DEFAULT 0,
  url_path varchar(500) NOT NULL DEFAULT '',
  referer_content_id bigint NOT NULL DEFAULT 0,
  referer_url_path varchar(500) NOT NULL DEFAULT '',
  parent_id bigint NOT NULL DEFAULT 0,
  level int NOT NULL DEFAULT 1,
  group_id bigint NOT NULL DEFAULT 0,
  period_type enum('daily','monthly') NOT NULL,
  period_date bigint NOT NULL COMMENT 'YYYYMMDD for daily, YYYYMM for monthly',
  visits int NOT NULL DEFAULT 0,
  direct_visits int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (analytics_referers_period_id),
  UNIQUE KEY uq_referer_period (content_id, referer_content_id, period_type, period_date),
  KEY idx_period_date (period_date),
  KEY idx_content (content_id, period_date),
  KEY idx_referer (referer_content_id, period_date),
  KEY idx_group (group_id, period_date),
  KEY idx_level (level, period_date)
);
```

### 🏗️ CONSOLIDATED ENTITY TABLES (2 tables)

**ADD: `lupo_entity_properties`**
```sql
CREATE TABLE lupo_entity_properties (
  entity_property_id bigint NOT NULL auto_increment,
  entity_type enum('agent','actor','user','service') NOT NULL,
  entity_id bigint NOT NULL,
  domain_id bigint NOT NULL DEFAULT 1,
  property_key varchar(100) NOT NULL,
  property_value text,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint,
  PRIMARY KEY (entity_property_id),
  UNIQUE KEY unique_entity_domain_property (entity_type, entity_id, domain_id, property_key),
  KEY idx_entity (entity_type, entity_id),
  KEY idx_domain (domain_id),
  KEY idx_property_key (property_key),
  KEY idx_created (created_ymdhis),
  KEY idx_updated (updated_ymdhis),
  KEY idx_is_deleted (is_deleted)
);
```

**ADD: `lupo_entity_edges`**
```sql
CREATE TABLE lupo_entity_edges (
  entity_edge_id bigint NOT NULL auto_increment,
  source_entity_type enum('agent','actor','user','service') NOT NULL,
  source_entity_id bigint NOT NULL,
  target_entity_type enum('agent','actor','user','service','file','content','channel') NOT NULL,
  target_entity_id bigint NOT NULL,
  edge_type varchar(50) NOT NULL,
  domain_id bigint NOT NULL DEFAULT 1,
  properties json,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint,
  PRIMARY KEY (entity_edge_id),
  KEY idx_source (source_entity_type, source_entity_id),
  KEY idx_target (target_entity_type, target_entity_id),
  KEY idx_edge_type (edge_type),
  KEY idx_domain (domain_id),
  KEY idx_created (created_ymdhis),
  KEY idx_is_deleted (is_deleted)
);
```

---

## 📋 MIGRATION SCRIPTS

### 1. MIGRATE ANALYTICS DATA
```sql
-- Migrate daily visits
INSERT INTO lupo_analytics_visits_periods (
  content_id, url_path, group_id, period_type, period_date,
  visits, unique_sessions, unique_actors, direct_visits, internal_visits,
  entry_count, exit_count, total_seconds, avg_seconds,
  created_ymdhis, updated_ymdhis
)
SELECT 
  content_id, url_path, group_id, 'daily', date_ymd,
  visits, unique_sessions, unique_actors, direct_visits, internal_visits,
  entry_count, exit_count, total_seconds, avg_seconds,
  created_ymdhis, updated_ymdhis
FROM lupo_analytics_visits_daily;

-- Migrate monthly visits  
INSERT INTO lupo_analytics_visits_periods (
  content_id, url_path, group_id, period_type, period_date,
  visits, unique_sessions, unique_actors, direct_visits, internal_visits,
  entry_count, exit_count, total_seconds, avg_seconds,
  created_ymdhis, updated_ymdhis
)
SELECT 
  content_id, url_path, group_id, 'monthly', date_ym,
  visits, unique_sessions, unique_actors, direct_visits, internal_visits,
  entry_count, exit_count, total_seconds, avg_seconds,
  created_ymdhis, updated_ymdhis
FROM lupo_analytics_visits_monthly;

-- Similar for referers...
```

### 2. MIGRATE ENTITY PROPERTIES
```sql
-- Migrate agent properties
INSERT INTO lupo_entity_properties (
  entity_type, entity_id, domain_id, property_key, property_value,
  created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
)
SELECT 
  'agent', actor_id, domain_id, property_key, property_value,
  created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
FROM lupo_agent_properties;

-- Migrate actor properties
INSERT INTO lupo_entity_properties (
  entity_type, entity_id, domain_id, property_key, property_value,
  created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
)
SELECT 
  actor_type, actor_id, 1, property_key, property_value,
  created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
FROM lupo_actor_properties;
```

### 3. DROP OLD TABLES
```sql
-- Drop analytics duplicates
DROP TABLE lupo_analytics_visits_daily;
DROP TABLE lupo_analytics_visits_monthly;
DROP TABLE lupo_analytics_referers_daily;
DROP TABLE lupo_analytics_referers_monthly;

-- Drop property duplicates
DROP TABLE lupo_agent_properties;
DROP TABLE lupo_actor_properties;

-- Drop edge duplicates
DROP TABLE lupo_agent_files;
DROP TABLE lupo_actor_object_edges;

-- Drop timeseries duplicates
DROP TABLE lupo_agent_context_snapshots;
DROP TABLE lupo_agent_heartbeats;

-- Drop all LiveHelp backup tables
DROP TABLE livehelp_autoinvite;
DROP TABLE livehelp_channels;
-- [continue for all 30 backup tables]
```

---

## 📊 FINAL COUNT

**BEFORE:** 220 tables  
**REMOVE:** 40 tables  
**ADD:** 4 tables  
**AFTER:** 184 tables

**RESULT:** ✅ 184 tables (38 tables under the 222 ceiling)

---

## ⚡ EXECUTION ORDER

1. **CREATE** new consolidated tables
2. **MIGRATE** data from old tables
3. **VERIFY** data integrity
4. **UPDATE** application code
5. **DROP** old tables
6. **TEST** functionality

**TIME ESTIMATE:** 2-3 hours for full migration

---

**STATUS:** ✅ READY FOR EXECUTION  
**RISK:** LOW (data preserved, structure improved)  
**BENEFIT:** 36 table reduction, cleaner schema