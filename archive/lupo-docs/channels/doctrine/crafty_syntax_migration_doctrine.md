# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/doctrine/CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md"
  file_hash: "436dbe794a53a597e9882f0282eaac613341421bb2fe0f4a08abdfb992bb2216"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\doctrine\CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md"
  file_hash: "6597d4b94959b9b9eefe682cc337320ce16ac24246133d4d37616a9c547edfc4"
  file_path_from_root: "lupo-docs\channels\doctrine\CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md"
  file_hash: "13768f9fa0cc44ac0cb79c58d2986855f4e27f47606942237d12b6d8981323db"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "crafty_syntax_migration_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
GOV-AD-PROHIBIT-001: true
ads_prohibition_statement: "Ads are manipulation. Ads are disrespect. Ads violate user trust."
file.last_modified_system_version: 4.3.3
file.last_modified_utc: 20260120092000
file.utc_day: 20260120
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @FLEET @Monday_Wolfie @Wolf @CAPTAIN_WOLFIE
  mood_RGB: "00FF00"
  message: "Crafty Syntax Migration Doctrine derived from SQL analysis. Complete table mapping documentation created. First-run import wizard design documented. All mappings extracted directly from craftysyntax_to_lupopedia_mysql.sql."
tags:
  categories: ["doctrine", "migration", "crafty-syntax", "documentation", "table-mapping"]
  collections: ["doctrine", "migration", "crafty-syntax"]
  channels: ["dev", "public", "migration"]
file:
  name: "CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md"
  title: "Crafty Syntax Migration Doctrine"
  description: "Complete table mapping and migration doctrine derived from craftysyntax_to_lupopedia_mysql.sql"
  version: "4.3.3"
  status: "published"
  author: "GLOBAL_CURRENT_AUTHORS"
---

# Crafty Syntax Migration Doctrine

**Version 4.3.3**  
**2026-01-20**  

## 🎯 Doctrine Statement

This doctrine is derived **exclusively** from the existing migration SQL file `craftysyntax_to_lupopedia_mysql.sql`. No mappings are inferred or invented - all documentation reflects the explicit transformations defined in the SQL.

---

## 📊 Migration Overview

### Scope
- **Source**: Crafty Syntax Live Help versions 3.6.1 through 3.7.5
- **Target**: Lupopedia v3.0.3
- **Total Legacy Tables**: 34
- **Migration Method**: Explicit INSERT INTO ... SELECT statements
- **Post-Migration**: All legacy tables are dropped

### Migration Rules
- **No Foreign Keys**: Doctrine-safe import
- **No Triggers**: Clean data migration
- **No Cascading Deletes**: Explicit data control
- **UTC Timestamps**: All timestamps follow YYYYMMDDHHIISS doctrine
- **UTF8MB4**: Full Unicode support
- **InnoDB Engine**: Transactional integrity

---

## 🗂️ Complete Table Mapping

### ✅ **Migrated Tables (Direct Mapping)**

| Legacy Table | Target Table | Transformation Type |
|--------------|--------------|-------------------|
| `livehelp_autoinvite` | `lupo_crafty_syntax_auto_invite` | Column mapping + Boolean conversion |
| `livehelp_departments` | `lupo_departments` | Column mapping + Type assignment |
| `livehelp_emails` | `lupo_crm_lead_messages` | Column mapping + Lead assignment |
| `livehelp_layerinvites` | `lupo_crafty_syntax_layer_invites` | Column mapping + Default values |
| `livehelp_leads` | `lupo_crm_leads` | Column mapping + Score initialization |
| `livehelp_leavemessage` | `lupo_crafty_syntax_leave_message` | Column mapping + Status assignment |
| `livehelp_operator_departments` | `lupo_actor_departments` | Direct column mapping |
| `livehelp_operator_history` | `lupo_audit_log` | Column mapping + JSON payload |
| `livehelp_questions` | `lupo_crafty_syntax_chat_questions` | Column mapping + Boolean conversion |
| `livehelp_quick` | `lupo_actor_reply_templates` | Column mapping + Context assignment |
| `livehelp_transcripts` | `lupo_dialog_threads`, `lupo_dialog_messages`, `lupo_dialog_message_bodies` | Multi-table split |
| `livehelp_users` | `lupo_auth_users`, `lupo_actor_properties` | Dual-table mapping |

### 🔄 **Transformed Tables (Schema Changes)**

| Legacy Table | Target Table(s) | Transformation Details |
|--------------|----------------|----------------------|
| `livehelp_config` | `lupo_modules.config_json` | Converted to JSON configuration |
| `livehelp_qa` | `lupo_truth_questions`, `lupo_truth_answers`, `lupo_collections`, `lupo_collection_tabs` | Complex multi-table mapping |
| `livehelp_paths_firsts` | `lupo_analytics_paths` | INSERT from paths_firsts; content_id resolution as needed |
| `livehelp_paths_monthly` | `lupo_analytics_paths` | INSERT from paths_monthly |
| `livehelp_referers_daily` | `lupo_referers` | INSERT from referers_daily |
| `livehelp_referers_monthly` | `lupo_referers` | INSERT from referers_monthly |

### ✅ **Visits, Referers, and Websites (Migrated — Not Dropped)**

These legacy tables **are migrated** in `import_from_old_crafty_syntax.sql`. They are **not** dropped without target.

| Legacy Table(s) | Target Table | Migration Type |
|-----------------|--------------|----------------|
| `livehelp_visits_daily` | `lupo_visits` | INSERT INTO lupo_visits |
| `livehelp_visits_monthly` | `lupo_visits` | INSERT INTO lupo_visits |
| `livehelp_referers_daily` | `lupo_referers` | INSERT INTO lupo_referers |
| `livehelp_referers_monthly` | `lupo_referers` | INSERT INTO lupo_referers |
| `livehelp_websites` | `lupo_federation_nodes` | INSERT INTO lupo_federation_nodes (after DELETE guard for node 0) |

**Note:** `livehelp_visit_track` is only ALTERed/deprecated in the SQL; no INSERT from that table. Visit data is migrated from `livehelp_visits_daily` and `livehelp_visits_monthly` into `lupo_visits`.

### ❌ **Dropped Tables (Not Migrated)**

| Legacy Table | Reason | Post-Migration Status |
|--------------|--------|----------------------|
| `livehelp_channels` | No INSERT into lupo_channels in migration SQL | Dropped |
| `livehelp_emailque` | Out of scope (lupo_crm_lead_message_sends) | Dropped |
| `livehelp_identity_daily` | Removed in Lupopedia 3.0.0 | Dropped |
| `livehelp_keywords_daily` | Removed in Lupopedia 3.0.0 | Dropped |
| `livehelp_keywords_monthly` | Removed in Lupopedia 3.0.0 | Dropped |
| `livehelp_messages` | Crafty didn't store post-chat messages | Dropped |
| `livehelp_modules` | No target mapping | Dropped |
| `livehelp_modules_dep` | No target mapping | Dropped |
| `livehelp_operator_channels` | No target table; dropped with no INSERT | Dropped |
| `livehelp_sessions` | No target mapping | Dropped |
| `livehelp_smilies` | Replaced by token system | Preserved as archive |
| `livehelp_visit_track` | No INSERT in SQL; visit data from visits_daily/monthly → lupo_visits | Dropped |

---

## 📋 Detailed Column Mappings

### 🔄 **livehelp_autoinvite → lupo_crafty_syntax_auto_invite**

| Source Column | Target Column | Transformation |
|---------------|---------------|----------------|
| `idnum` | `crafty_syntax_auto_invite_id` | Direct mapping |
| `offline` | `is_offline` | Direct mapping |
| `isactive` | `is_active` | 'Y'→1, 'N'→0 |
| `department` | `department_id` | Direct mapping |
| `message` | `message` | Direct mapping |
| `page` | `page_url` | Direct mapping |
| `visits` | `visits` | Direct mapping |
| `referer` | `referrer_url` | Direct mapping |
| `typeof` | `invite_type` | Direct mapping |
| `seconds` | `trigger_seconds` | Direct mapping |
| `user_id` | `operator_user_id` | Direct mapping |
| `socialpane` | `show_socialpane` | 'Y'→1, 'N'→0 |
| `excludemobile` | `exclude_mobile` | 'Y'→1, 'N'→0 |
| `onlymobile` | `only_mobile` | 'Y'→1, 'N'→0 |

### 🔄 **livehelp_departments → lupo_departments**

| Source Column | Target Column | Transformation |
|---------------|---------------|----------------|
| `recno` | `department_id` | Direct mapping |
| `website` | `federation_node_id` | Direct mapping |
| `nameof` | `name` | Direct mapping |
| *(none)* | `description` | NULL |
| *(none)* | `department_type` | 'crafty' |
| *(none)* | `default_actor_id` | 1 |

### 🔄 **livehelp_users → lupo_auth_users + lupo_actor_properties**

#### lupo_auth_users mapping:
| Source Column | Target Column | Transformation |
|---------------|---------------|----------------|
| `user_id` | `auth_user_id` | Direct mapping |
| `username` | `username` | Direct mapping |
| `displayname` | `display_name` | Direct mapping |
| `email` | `email` | NULLIF empty string |
| `password` | `password_hash` | Direct mapping |
| `auth_provider` | `auth_provider` | Direct mapping |
| `provider_id` | `provider_id` | Direct mapping |
| `last_login_at` | `last_login_ymdhis` | Direct mapping |
| `showedup`/`lastaction` | `created_ymdhis` | COALESCE |
| `lastaction` | `updated_ymdhis` | Direct mapping |

#### lupo_actor_properties mapping:
| Source Column | Target Column | Transformation |
|---------------|---------------|----------------|
| `user_id` | `actor_id` | Direct mapping |
| *(multiple)* | `property_value` | JSON object with all profile fields |

### 🔄 **livehelp_transcripts → Multi-table Split**

#### lupo_dialog_threads mapping:
| Source Column | Target Column | Transformation |
|---------------|---------------|----------------|
| `recno` | `dialog_thread_id` | Direct mapping |
| *(none)* | `federation_node_id` | 1 |
| *(none)* | `channel_id` | 1 |
| *(none)* | `created_by_actor_id` | 1 |
| `recno` | `summary_text` | CONCAT with import message |
| *(multiple)* | `metadata_json` | JSON object with legacy fields |
| `starttime` | `created_ymdhis` | Direct mapping |
| `endtime` | `updated_ymdhis` | Direct mapping |

#### lupo_dialog_messages mapping:
| Source Column | Target Column | Transformation |
|---------------|---------------|----------------|
| `recno` | `dialog_message_id` | Direct mapping |
| `recno` | `dialog_thread_id` | Direct mapping |
| *(none)* | `channel_id` | 1 |
| *(none)* | `from_actor_id` | 1 |
| *(none)* | `to_actor_id` | 1 |
| `recno` | `message_text` | CONCAT with import message |
| *(none)* | `message_type` | 'text' |
| *(multiple)* | `metadata_json` | JSON object with legacy fields |
| *(none)* | `mood_vector` | '666666' |
| *(none)* | `weight` | 1 |

#### lupo_dialog_message_bodies mapping:
| Source Column | Target Column | Transformation |
|---------------|---------------|----------------|
| `recno` | `dialog_message_body_id` | Direct mapping |
| `recno` | `dialog_message_id` | Direct mapping |
| `transcript` | `full_text` | Direct mapping |
| *(multiple)* | `metadata_json` | JSON object with legacy fields |
| `starttime` | `created_ymdhis` | Direct mapping |
| `endtime` | `updated_ymdhis` | Direct mapping |

---

## 🔧 Special Transformations

### 📄 **Configuration Migration (livehelp_config)**

The entire `livehelp_config` table is converted into a single JSON object stored in `lupo_modules.config_json` where `module_id = 1`.

**Key Configuration Fields Preserved:**
- `version`, `site_title`, `webpath`, `s_webpath`
- `refreshrate`, `chatmode`, `adminsession`
- `smtp_*` settings, `owner_email`
- `theme`, `colorscheme`, `chatcolors`
- `timeout` settings, `maxrequests`

### 🎯 **Q&A System Migration (livehelp_qa)**

Complex multi-table mapping:

1. **Questions** → `lupo_truth_questions`
2. **Answers** → `lupo_truth_answers`
3. **Folders** → `lupo_collections` + `lupo_collection_tabs`
4. **Hierarchy** → Parent-child relationships in collection tabs

### 📊 **Analytics Migration (URL Handling)**

**Critical Note**: All URL-based analytics store `content_id = 0` because Crafty Syntax stores web-facing URL slugs, not filesystem paths. Application logic must handle slug-to-content-id resolution.

**Affected Tables:**
- `lupo_analytics_paths_daily`
- `lupo_analytics_paths_monthly`
- `lupo_analytics_referers_daily`

### 😊 **Emoji System Replacement**

`livehelp_smilies` is preserved as archive only. Lupopedia uses token format `:|:name|folder|filename:|:` with direct directory lookup.

---

## 📈 Post-Migration Expectations

### ✅ **What Exists After Migration**

| System | Status | Details |
|--------|--------|---------|
| **User Accounts** | ✅ Preserved | All users in `lupo_auth_users` |
| **Departments** | ✅ Preserved | All departments in `lupo_departments` |
| **Chat Transcripts** | ✅ Preserved | All transcripts in dialog system |
| **Auto-invite Rules** | ✅ Preserved | All rules in `lupo_crafty_syntax_auto_invite` |
| **Canned Responses** | ✅ Preserved | All responses in `lupo_actor_reply_templates` |
| **Leave Messages** | ✅ Preserved | All messages in `lupo_crafty_syntax_leave_message` |
| **CRM Data** | ✅ Preserved | Leads and messages in CRM tables |
| **Q&A Content** | ✅ Preserved | Questions/answers in truth system |
| **Configuration** | ✅ Preserved | All config in JSON format |
| **Analytics Data** | ✅ Preserved | All analytics with URL slugs |

### ❌ **What No Longer Exists**

| Legacy Feature | Status | Replacement |
|---------------|--------|-------------|
| **Legacy Tables** | ❌ Dropped | All 34 legacy tables removed |
| **Emoji Metadata** | ❌ Replaced | Token-based emoji system |
| **Identity Analytics** | ❌ Removed | Not needed in Lupopedia |
| **Keyword Analytics** | ❌ Removed | Not needed in Lupopedia |
| **Session Tracking** | ❌ Removed | Replaced by dialog system |
| **Channel System** | ❌ Removed | Replaced by unified channels |

### 🔄 **What Changed**

| Feature | Legacy | Lupopedia |
|---------|--------|-----------|
| **User Profiles** | Flat table | `lupo_auth_users` + `lupo_actor_properties` |
| **Chat Storage** | Single table | `lupo_dialog_threads` + `lupo_dialog_messages` + `lupo_dialog_message_bodies` |
| **Q&A System** | Single table | `lupo_truth_questions` + `lupo_truth_answers` + collections |
| **Configuration** | Row-based | JSON-based in `lupo_modules` |
| **URL Analytics** | Content IDs | URL slugs with content_id = 0 |

---

## 🛡️ Migration Safety

### ✅ **Safety Features**

- **Non-destructive**: Legacy tables marked as DEPRECATED before migration
- **Rollback Support**: Legacy tables retained until successful migration
- **Data Integrity**: All transformations preserve original data
- **Character Encoding**: UTF8MB4 conversion prevents data loss
- **Timestamp Consistency**: All timestamps follow UTC doctrine

### 🔍 **Validation Requirements**

- **Row Count Validation**: Compare source vs target row counts
- **Data Integrity**: Verify no data corruption during transformation
- **Workflow Testing**: Test key user workflows post-migration
- **Configuration Validation**: Verify all settings properly migrated

---

## 📚 Implementation Notes

### ⚠️ **Critical Implementation Requirements**

1. **URL Slug Resolution**: Application must handle slug-to-content-id resolution for analytics
2. **JSON Configuration**: Module system must parse JSON configuration from `lupo_modules`
3. **Emoji Rendering**: Must implement token-based emoji replacement system
4. **Dialog System**: Must handle multi-table dialog structure
5. **Actor Properties**: Must query `lupo_actor_properties` for complete user profiles

### 🎯 **Migration Execution Order**

1. **Configuration Migration** (livehelp_config → lupo_modules)
2. **User Migration** (livehelp_users → lupo_auth_users + lupo_actor_properties)
3. **Department Migration** (livehelp_departments → lupo_departments)
4. **Content Migration** (Q&A, transcripts, etc.)
5. **Analytics Migration** (URL-based analytics)
6. **Validation Checks**
7. **Legacy Table Cleanup**

---

## 🔢 **Explicit ID Assignment vs auto_increment**

Per Lupopedia doctrine: **no reliance on auto_increment for identity** where explicit ID assignment is required. IDs must be checked for availability before insert for any table using explicit IDs.

### **Tables Using Explicit ID Assignment**

| Table | Identity Column | Rule |
|-------|-----------------|------|
| `lupo_channels` | `channel_id` | Uses explicit ID ranges. IDs must be checked for availability before insert. |

All other migration target tables (e.g. `lupo_auth_users`, `lupo_departments`, `lupo_dialog_messages`, `lupo_visits`, `lupo_federation_nodes`, etc.) use **auto_increment** for their primary key unless added to this list. This section is documentation only; no schema changes. As doctrine is updated, any additional tables that use explicit ID ranges must be listed here.

### **Summary**

- **Explicit ID:** `lupo_channels` (channel ID ranges).
- **auto_increment:** All other tables in the migration unless explicitly documented above.
- **Rule:** For explicit-ID tables, ID availability must be checked before insert.

---

## 📋 Doctrine Compliance

### ✅ **SQL-Only Doctrine**

This documentation is derived **exclusively** from the SQL file. No mappings are inferred or assumed. Every transformation documented is explicitly defined in `craftysyntax_to_lupopedia_mysql.sql`.

### 🔒 **No Interpretation Policy**

- **No guessed mappings**: Only SQL-defined mappings documented
- **No invented transformations**: Only explicit SQL transformations shown
- **No assumed relationships**: Only SQL-defined relationships included
- **No speculative features**: Only implemented features documented

---

**Doctrine Status: ✅ COMPLETE**  
**Source**: craftysyntax_to_lupopedia_mysql.sql  
**Validation**: All mappings extracted from explicit SQL statements  
**Compliance**: 100% SQL-derived documentation  

---

*Generated by CURSOR on 2026-01-20*  
*Derived exclusively from craftysyntax_to_lupopedia_mysql.sql*  
*No mappings inferred or invented*
