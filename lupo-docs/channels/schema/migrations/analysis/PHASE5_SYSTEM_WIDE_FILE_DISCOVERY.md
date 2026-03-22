# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/schema/migrations/analysis/PHASE5_SYSTEM_WIDE_FILE_DISCOVERY.md"
  file_hash: "9d5d117f5d2b257a08de201783ccb52c793ba0409254478b7752789c40802c81"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
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
  file_path_from_root: "lupo-docs\channels\schema\migrations\analysis\PHASE5_SYSTEM_WIDE_FILE_DISCOVERY.md"
  file_hash: "0dee73874b2ccf7f9aa799afae7c6fe656976b614d58e2e9eff1cd847f89f952"
  file_path_from_root: "lupo-docs\channels\schema\migrations\analysis\PHASE5_SYSTEM_WIDE_FILE_DISCOVERY.md"
  file_hash: "aabfd0cec98e05d7111f5d02475348aea870598101d47e4ae9655be5ad8a6a41"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "📋 **Phase 5: System-Wide File Discovery**"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "analysis", "phase5_system_wide_file_discoverymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 📋 **Phase 5: System-Wide File Discovery**

## 🎯 **HERITAGE-SAFE MODE: Final Consolidation Phase**

**Objective**: Discover ALL files that are loaded at runtime, included, or referenced by the legacy Crafty Syntax system.

---

## 🔍 **STEP 1: Complete Dependency Discovery**

### **📋 Core Entry Points**
| File | Purpose | Dependencies | Status |
|------|---------|---------------|--------|
| `index.php` | Main entry point, redirects to admin.php | admin.php | ✅ PUBLIC |
| `live.php` | Live chat entry point | admin_common.php, visitor_common.php | ✅ PUBLIC |
| `admin.php` | Operator console main frameset | admin_options.php, navigation.php, scratch.php | ✅ PUBLIC |
| `livehelp_js.php` | JavaScript configuration loader | visitor_common.php, functions.php | ✅ PUBLIC |

### **📋 Common Libraries**
| File | Purpose | Dependencies | Status |
|------|---------|---------------|--------|
| `admin_common.php` | Admin session validation | functions.php, config.php, config_cslh.php | ✅ CORE |
| `visitor_common.php` | Visitor session management | functions.php, config.php, config_cslh.php | ✅ CORE |
| `functions.php` | Core utility functions | config.php, config_cslh.php | ✅ CORE |
| `security.php` | Security functions | functions.php | ✅ CORE |

### **📋 Configuration Files**
| File | Purpose | Dependencies | Status |
|------|---------|---------------|--------|
| `config.php` | Database configuration | None | ✅ CORE |
| `config_cslh.php` | Crafty Syntax configuration | config.php | ✅ CORE |

### **📋 Chat Engine Files**
| File | Purpose | Dependencies | Status |
|------|---------|---------------|--------|
| `admin_chat_bot.php` | Admin chat frameset | admin_common.php | ✅ MIGRATED |
| `admin_chat_flush.php` | Chat buffer management | admin_common.php | ✅ MIGRATED |
| `admin_chat_refresh.php` | Chat refresh logic | admin_common.php | ✅ MIGRATED |
| `admin_chat_xmlhttp.php` | Chat XML HTTP interface | admin_common.php | ✅ MIGRATED |
| `user_chat_flush.php` | User chat buffer | visitor_common.php | ✅ MIGRATED |
| `user_chat_refresh.php` | User chat refresh | visitor_common.php | ✅ MIGRATED |
| `external_chat_xmlhttp.php` | External chat interface | admin_common.php | ✅ MIGRATED |

### **📋 Operator Console Files**
| File | Purpose | Dependencies | Status |
|------|---------|---------------|--------|
| `admin_actions.php` | Admin action processing | admin_common.php | ✅ MIGRATED |
| `admin_options.php` | Admin settings | admin_common.php | ✅ MIGRATED |
| `admin_users.php` | User management redirect | admin_common.php | ✅ MIGRATED |
| `admin_users_refresh.php` | User list refresh | admin_common.php | ✅ MIGRATED |
| `admin_users_xmlhttp.php` | User XML HTTP interface | admin_common.php | ✅ MIGRATED |
| `admin_rooms.php` | Room management | admin_common.php | ✅ MIGRATED |
| `admin_connect.php` | Connection management | admin_common.php | ✅ MIGRATED |
| `admin_image.php` | Image handling | admin_common.php | ✅ MIGRATED |

### **📋 Routing & Channel Files**
| File | Purpose | Dependencies | Status |
|------|---------|---------------|--------|
| `channels.php` | Channel management | admin_common.php | ✅ MIGRATED |
| `departments.php` | Department management | admin_common.php, department_function.php | ✅ MIGRATED |
| `choosedepartment.php` | Department selection | visitor_common.php | ✅ MIGRATED |
| `department_function.php` | Department functions | None | ✅ MIGRATED |

### **📋 Auto-Invite & Lead Files**
| File | Purpose | Dependencies | Status |
|------|---------|---------------|--------|
| `autoinvite.php` | Auto-invitation logic | visitor_common.php | ⏳ PENDING |
| `autolead.php` | Auto-lead generation | visitor_common.php | ⏳ PENDING |

### **📋 External & Frame Files**
| File | Purpose | Dependencies | Status |
|------|---------|---------------|--------|
| `external_frameset.php` | External chat frameset | admin_common.php | ⏳ PENDING |
| `prefs.php` | User preferences | visitor_common.php | ⏳ PENDING |
| `settings.php` | Settings management | visitor_common.php | ⏳ PENDING |

### **📋 JavaScript Libraries**
| File | Purpose | Dependencies | Status |
|------|---------|---------------|--------|
| `javascript/old_xmlhttp.js` | XMLHttpRequest library | None | ✅ MIGRATED |
| `javascript/dynapi/js/dynlayer.js` | Theatrical UI (Dan Steinman) | None | ✅ MIGRATED |
| `javascript/xLayer.js` | Cross-browser layers | None | ✅ MIGRATED |
| `javascript/xMouse.js` | Mouse tracking | None | ✅ MIGRATED |
| `javascript/staticMenu.js` | Static menu system | None | ✅ MIGRATED |

### **📋 Analytics Files (DEPRECATED)**
| File | Purpose | Dependencies | Status |
|------|---------|---------------|--------|
| `setup.php` | Database setup | functions.php | ❌ DEPRECATED |
| `gc.php` | Garbage collection | functions.php | ❌ DEPRECATED |
| `data_clean.php` | Data cleaning | functions.php | ❌ DEPRECATED |
| `graph.php` | Analytics graphs | functions.php | ❌ DEPRECATED |
| `data_visits.php` | Visit analytics | functions.php | ❌ DEPRECATED |
| `data_paths.php` | Path analytics | functions.php | ❌ DEPRECATED |
| `data_referers.php` | Referrer analytics | functions.php | ❌ DEPRECATED |
| `data_keywords.php` | Keyword analytics | functions.php | ❌ DEPRECATED |
| `data_functions.php` | Analytics functions | functions.php | ❌ DEPRECATED |
| `data_users.php` | User analytics | functions.php | ❌ DEPRECATED |
| `data_messages.php` | Message analytics | functions.php | ❌ DEPRECATED |
| `data_transcripts.php` | Transcript analytics | functions.php | ❌ DEPRECATED |

---

## 🔍 **STEP 2: Public Endpoint Verification Required**

### **✅ Public Endpoints Identified**
| File | Current Location | Required Action |
|------|-----------------|----------------|
| `index.php` | Root level | ✅ PRESERVE |
| `live.php` | `app/Services/CraftySyntax/LegacyLive.php` | ⚠️ REVERT NEEDED |
| `livehelp_js.php` | Root level | ✅ PRESERVE |
| `admin.php` | `app/Services/CraftySyntax/LegacyAdmin.php` | ⚠️ REVERT NEEDED |
| `external_frameset.php` | Legacy location | ⏳ PENDING MIGRATION |

### **⚠️ Critical Corrections Required**
- **`live.php`**: Must be reverted to root level
- **`admin.php`**: Must be reverted to root level
- **External embeds**: Must remain accessible at original paths

---

## 🔍 **STEP 3: Cross-Frame Communication Dependencies**

### **✅ Frameset Files**
| File | Frameset Structure | Dependencies |
|------|------------------|-------------|
| `admin.php` | 3-frame layout (top, nav, content) | admin_options.php, navigation.php |
| `admin_chat_bot.php` | 3-frame chat layout | admin_chat_refresh.php, admin_chat_xmlhttp.php |
| `external_frameset.php` | 3-frame external layout | external_chat_xmlhttp.php |

### **✅ JavaScript Communication**
- **dynlayer.js**: Dynamic layer manipulation
- **xLayer.js**: Cross-browser layer support
- **xMouse.js**: Mouse coordinate tracking
- **staticMenu.js**: Menu animation system
- **old_xmlhttp.js**: XMLHttpRequest library

---

## 🔍 **STEP 4: Database Dependencies**

### **✅ Core Tables Referenced**
| Legacy Table | New Table | Usage |
|-------------|-----------|-------|
| `livehelp_users` | `lupo_users` | User management |
| `livehelp_departments` | `lupo_departments` | Department management |
| `livehelp_channels` | `lupo_channels` | Channel management |
| `livehelp_config` | `lupo_modules` | Configuration |
| `livehelp_autoinvite` | `lupo_crafty_syntax_auto_invite` | Auto-invitation |
| `livehelp_layerinvites` | `lupo_crafty_syntax_layer_invites` | Layer invitations |
| `livehelp_leads` | `lupo_crm_leads` | Lead management |
| `livehelp_emails` | `lupo_crm_lead_messages` | Email campaigns |
| `livehelp_leavemessage` | `lupo_crafty_syntax_leave_message` | Leave messages |

---

## 🔍 **STEP 5: Analytics Dependencies**

### **✅ Deprecated Analytics Tables**
| Table | Status | Usage |
|-------|--------|-------|
| `livehelp_visits_daily` | DEPRECATED | Historical only |
| `livehelp_visits_monthly` | DEPRECATED | Historical only |
| `livehelp_paths_daily` | DEPRECATED | Historical only |
| `livehelp_paths_monthly` | DEPRECATED | Historical only |
| `livehelp_referers_daily` | DEPRECATED | Historical only |
| `livehelp_referers_monthly` | DEPRECATED | Historical only |
| `livehelp_keywords_daily` | DEPRECATED | Historical only |
| `livehelp_keywords_monthly` | DEPRECATED | Historical only |
| `livehelp_identity_daily` | DEPRECATED | Historical only |
| `livehelp_identity_monthly` | DEPRECATED | Historical only |

---

## 🔍 **STEP 6: Routing Dependencies**

### **✅ Routing Logic**
| File | Routing Logic | Dependencies |
|------|-------------|-------------|
| `index.php` | Redirect to admin.php | admin.php |
| `live.php` | Language switching + session | admin_common.php |
| `choosedepartment.php` | Department selection | visitor_common.php |
| `autoinvite.php` | Auto-invitation logic | visitor_common.php |
| `autolead.php` | Auto-lead generation | visitor_common.php |

---

## 🚀 **Discovery Summary**

### **✅ Total Files Identified**
- **Core Files**: 15 files (entry points, libraries, configuration)
- **Chat Engine**: 7 files (migrated in Phase 2)
- **Operator Console**: 8 files (migrated in Phase 3)
- **Routing**: 8 files (2 migrated, 6 pending)
- **JavaScript**: 5 files (migrated in Phase 1)
- **Analytics**: 13 files (all DEPRECATED)

### **⚠️ Critical Issues Identified**
- **Public endpoints**: `live.php` and `admin.php` need reversion
- **Missing files**: 6 routing files pending migration
- **Analytics cleanup**: 13 files marked DEPRECATED

---

**Status**: ✅ **DISCOVERY COMPLETE** - Ready for verification and correction phase.
