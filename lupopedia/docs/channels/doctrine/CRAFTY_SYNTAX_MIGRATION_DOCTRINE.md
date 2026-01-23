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
- **Target**: Lupopedia v4.0.3
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
| `livehelp_paths_firsts` | `lupo_analytics_paths_daily`, `lupo_analytics_paths_monthly` | Content ID resolution (stored as 0) |
| `livehelp_paths_monthly` | `lupo_analytics_paths_monthly` | Content ID resolution (stored as 0) |
| `livehelp_referers_daily` | `lupo_analytics_referers_daily` | URL slug handling (content_id = 0) |

### ❌ **Dropped Tables (Not Migrated)**

| Legacy Table | Reason | Post-Migration Status |
|--------------|--------|----------------------|
| `livehelp_channels` | No target mapping | Dropped |
| `livehelp_emailque` | Out of scope (lupo_crm_lead_message_sends) | Dropped |
| `livehelp_identity_daily` | Removed in Lupopedia 4.0.0 | Dropped |
| `livehelp_identity_monthly` | Removed in Lupopedia 4.0.0 | Dropped |
| `livehelp_keywords_daily` | Removed in Lupopedia 4.0.0 | Dropped |
| `livehelp_keywords_monthly` | Removed in Lupopedia 4.0.0 | Dropped |
| `livehelp_messages` | Crafty didn't store post-chat messages | Dropped |
| `livehelp_modules` | No target mapping | Dropped |
| `livehelp_modules_dep` | No target mapping | Dropped |
| `livehelp_operator_channels` | No target mapping | Dropped |
| `livehelp_sessions` | No target mapping | Dropped |
| `livehelp_smilies` | Replaced by token system | Preserved as archive |
| `livehelp_visits_daily` | No target mapping | Dropped |
| `livehelp_visits_monthly` | No target mapping | Dropped |
| `livehelp_visit_track` | No target mapping | Dropped |
| `livehelp_websites` | No target mapping | Dropped |

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
| *(none)* | `mood_rgb` | '666666' |
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
