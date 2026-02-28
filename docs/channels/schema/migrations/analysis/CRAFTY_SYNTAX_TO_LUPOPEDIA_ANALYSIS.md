# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\schema\migrations\analysis\CRAFTY_SYNTAX_TO_LUPOPEDIA_ANALYSIS.md"
  file_hash: "250ce21cb1d0948bbcfef64018eca359b46e6672f7f2a508722eca62495718fc"
  file_path_from_root: "docs\channels\schema\migrations\analysis\CRAFTY_SYNTAX_TO_LUPOPEDIA_ANALYSIS.md"
  file_hash: "8fa437e259a2939fe909fd345a6418b6773d8be09de6587007692c5bb24d7c2e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "📋 **Crafty Syntax to Lupopedia Migration Analysis**"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "analysis", "crafty_syntax_to_lupopedia_analysismd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 📋 **Crafty Syntax to Lupopedia Migration Analysis**

## 🎯 **Authoritative Mapping Document**

**File**: `craftysyntax_to_lupopedia_mysql.sql`  
**Purpose**: Complete migration from Crafty Syntax Live Help (3.7.5) to Lupopedia (3.0.3)  
**Status**: Authoritative mapping between legacy and new table structures

---

## 📊 **Migration Overview**

### **Scope Statistics**
- **Total Tables Processed**: 145 legacy tables → 111 core Lupopedia tables
- **New Tables Created**: 8 Crafty Syntax module tables for legacy compatibility
- **Deprecated Tables**: 34 legacy tables marked for retention during migration
- **Migration Type**: Complete architectural rewrite (3.7.5 → 3.0.3)

---

## 🏗 **Table Mapping Categories**

### **🔄 Core System Tables**

#### **User Management**
| Legacy Table | New Table | Purpose |
|-------------|----------|---------|
| `livehelp_users` | `lupo_users` | Core user accounts and sessions |
| `livehelp_departments` | `lupo_departments` | Department management |
| `livehelp_operator_channels` | `lupo_actor_departments` | Operator-department assignments |

#### **Communication System**
| Legacy Table | New Table | Purpose |
|-------------|----------|---------|
| `livehelp_messages` | `lupo_dialog_messages` | Chat messages and dialog threads |
| `livehelp_autoinvite` | `lupo_crafty_syntax_auto_invite` | Auto-invitation system |
| `livehelp_channels` | `lupo_channels` | Chat channel management |

#### **Content Management**
| Legacy Table | New Table | Purpose |
|-------------|----------|---------|
| `livehelp_config` | `lupo_modules` (JSON config) | System configuration |
| `livehelp_layerinvites` | `lupo_crafty_syntax_layer_invites` | Layer invitations |
| `livehelp_leavemessage` | `lupo_crafty_syntax_leave_message` | Leave messages |

---

## 📧 **Analytics & Tracking Tables**

#### **Visitor Analytics**
| Legacy Table | New Table | Purpose |
|-------------|----------|---------|
| `livehelp_visits_monthly` | `lupo_analytics_visits_monthly` | Monthly visit aggregation |
| `livehelp_visits_daily` | `lupo_analytics_visits_daily` | Daily visit tracking |
| `livehelp_paths_firsts` | `lupo_analytics_paths_firsts` | First-time path tracking |
| `livehelp_paths_monthly` | `lupo_analytics_paths_monthly` | Monthly path analytics |

#### **Lead Management**
| Legacy Table | New Table | Purpose |
|-------------|----------|---------|
| `livehelp_leads` | `lupo_crm_leads` | Lead generation system |
| `livehelp_emails` | `lupo_crm_lead_messages` | Email campaign system |

---

## 🎭 **Theatrical & UI Tables**

#### **Layer Management**
| Legacy Table | New Table | Purpose |
|-------------|----------|---------|
| `livehelp_layerinvites` | `lupo_crafty_syntax_layer_invites` | Dynamic layer invitations |

---

## 🔧 **Configuration & Modules**

#### **System Configuration**
| Legacy Table | New Table | Purpose |
|-------------|----------|---------|
| `livehelp_config` → `lupo_modules` | JSON-based configuration storage |

#### **Module Dependencies**
| Legacy Table | New Table | Purpose |
|-------------|----------|---------|
| `livehelp_modules_dep` | No direct equivalent | Module-department relationships |

---

## 📝 **Audit & Logging Tables**

#### **System Audit**
| Legacy Table | New Table | Purpose |
|-------------|----------|---------|
| `livehelp_operator_history` | `lupo_audit_log` | Operator action logging |

---

## 🗄️ **Deprecated Tables (Retention)**

### **Tables Marked for Legacy Compatibility**
- `livehelp_identity_daily` - Daily identity tracking (deprecated)
- `livehelp_identity_monthly` - Monthly identity tracking (deprecated)
- `livehelp_keywords_daily` - Daily keyword tracking (deprecated)
- `livehelp_keywords_monthly` - Monthly keyword tracking (deprecated)
- `livehelp_emailque` - Email queue (not migrated - out of scope)
- `livehelp_identity_monthly` - Monthly identity (deprecated)

**Purpose**: Maintain compatibility with legacy Crafty Syntax modules during transition

---

## 🎯 **Key Migration Transformations**

### **1. Timestamp Doctrine Compliance**
- **Legacy**: Mixed timestamp formats
- **Lupopedia**: Strict UTC YYYYMMDDHHIISS format
- **Migration**: All timestamps converted to `DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S')`

### **2. Identity Preservation**
- **Legacy**: Session-based identity with multi-factor authentication
- **Lupopedia**: Enhanced with modern user management while preserving legacy session patterns

### **3. Structural Modernization**
- **Legacy**: Flat table structure with mixed naming conventions
- **Lupopedia**: Normalized structure with proper foreign keys and indexing

### **4. JSON Configuration Migration**
- **Legacy**: Multiple config columns in `livehelp_config`
- **Lupopedia**: Single JSON column in `lupo_modules` with structured configuration object

---

## 🚀 **Critical Migration Rules**

### **Data Integrity**
- ✅ All legacy data preserved through INSERT statements
- ✅ No data loss during transformation
- ✅ Referential integrity maintained through proper JOINs

### **Backward Compatibility**
- ✅ 34 legacy tables retained with DEPRECATED comments
- ✅ Legacy modules can reference old tables during transition
- ✅ Gradual migration path available

### **Forward Compatibility**
- ✅ All new tables follow Lupopedia 3.0.3 schema
- ✅ Proper UTF-8MB4 Unicode collation
- ✅ InnoDB engine for transactional integrity

---

## 📋 **Migration Execution Plan**

### **Phase 1: Schema Migration**
1. Run `craftysyntax_to_lupopedia_mysql.sql`
2. Verify all 145 tables created correctly
3. Test data integrity with sample queries
4. Validate foreign key relationships

### **Phase 2: Data Migration**
1. Migrate live data from legacy to new tables
2. Validate row counts and data consistency
3. Update application code to use new table names
4. Test all legacy functionality with new schema

### **Phase 3: Legacy Cleanup**
1. Verify all legacy applications work with new schema
2. Remove deprecated tables after confirmation period
3. Update documentation to reflect new architecture

---

## 🎖 **Migration Authority**

This SQL file serves as the **authoritative mapping** between Crafty Syntax Live Help 3.7.5 and Lupopedia 3.0.3. All table transformations, column mappings, and data relationships are explicitly defined here.

**No manual table modifications should be made without consulting this document.**

---

**Generated**: 2026-01-22  
**Author**: Lupopedia Migration System  
**Version**: 1.0  
**Status**: Ready for execution