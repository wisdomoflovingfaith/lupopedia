# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\schema\migrations\analysis\PHASE2_CHAT_ENGINE_MIGRATION_PLAN.md"
  file_hash: "1ccff43aaa6bcae49e0035c86456d15b7810259ac39feb29026c89557fa4efe1"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "docs\channels\schema\migrations\analysis\PHASE2_CHAT_ENGINE_MIGRATION_PLAN.md"
  file_hash: "f3bacfbd5faabf7ee86aa4616c60b8e70fb2463b32f589262b4bcb762a5ddb88"
  file_path_from_root: "docs\channels\schema\migrations\analysis\PHASE2_CHAT_ENGINE_MIGRATION_PLAN.md"
  file_hash: "a64145837ba2d37156c9f34bf2bf48328c7e7e574a243c47ccac9b90efca37d7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "📋 **Phase 2: Chat Engine Migration Plan**"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "analysis", "phase2_chat_engine_migration_planmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 📋 **Phase 2: Chat Engine Migration Plan**

## 🎯 **HERITAGE-SAFE MODE: Chat System Migration**

**Objective**: IDENTIFY and COPY (not rewrite) legacy Crafty Syntax chat engine files into Lupopedia folder structure.

---

## 🔍 **Files to Migrate**

### **1. Chat Flush & Refresh System**
- `admin_chat_flush.php` → `app/Services/CraftySyntax/LegacyAdminChatFlush.php`
- `admin_chat_refresh.php` → `app/Services/CraftySyntax/LegacyAdminChatRefresh.php`  
- `user_chat_flush.php` → `app/Services/CraftySyntax/LegacyUserChatFlush.php`
- `user_chat_refresh.php` → `app/Services/CraftySyntax/LegacyUserChatRefresh.php`
- `external_chat_xmlhttp.php` → `app/Services/CraftySyntax/LegacyExternalChatXmlHttp.php`

### **2. XMLHTTP Interfaces**
- `admin_chat_xmlhttp.php` → `app/Services/CraftySyntax/LegacyAdminChatXmlHttp.php`
- `livehelp_js.php` → `app/Services/CraftySyntax/LegacyLiveHelpJs.php`
- `javascript/old_xmlhttp.js` → `lupo-includes/js/legacy_old_xmlhttp.js`

### **3. Chat Routing & Channel Logic**
- `channels.php` → `app/Services/CraftySyntax/LegacyChannels.php`
- `departments.php` → `app/Services/CraftySyntax/LegacyDepartments.php`
- `department_function.php` → `app/Services/CraftySyntax/LegacyDepartmentFunction.php`

---

## 🔧 **Migration Rules**

### **✅ PRESERVE ALL LEGACY BEHAVIOR**
- DO NOT rewrite chat logic
- DO NOT convert buffer streaming to fetch(), SSE, or WebSockets
- DO NOT alter theatrical UI calls (dynlayer, xLayer, xMouse)
- DO NOT change variable names, function names, or behavior
- DO NOT introduce frameworks or abstractions

### **✅ COPY WITH MINIMAL CHANGES**
- Copy files to appropriate Lupopedia service locations
- Add minimal wrapper files ONLY if required for autoloading
- Add comments indicating legacy origin and doctrine references

### **✅ MAINTAIN COMPATIBILITY**
- Preserve all legacy module support during transition
- Keep all existing functionality working with new structure

---

## 📋 **Implementation Strategy**

### **Phase 1: File Structure Setup**
1. Create service directory structure: `app/Services/CraftySyntax/`
2. Copy all chat engine files with legacy preservation
3. Create minimal wrapper for autoloading if needed

### **Phase 2: Integration Testing**
1. Test all copied files work with new structure
2. Verify chat functionality with legacy data
3. Test XMLHTTP interfaces work correctly

### **Phase 3: Legacy Cleanup**
1. Verify all legacy applications work with new schema
2. Remove deprecated tables after confirmation period

---

## 🚀 **Critical Requirements**

### **Data Integrity**
- All legacy data preserved through proper copying
- No data loss during transformation
- Referential integrity maintained

### **Backward Compatibility**
- 34 legacy tables retained with DEPRECATED comments
- Legacy module support maintained during transition

---

## 📋 **Expected Deliverables**

### **Files Created**
- 12+ legacy chat engine files in new locations
- Service directory structure for organized legacy components
- Wrapper files for autoloading and integration

### **Files Copied**
| Legacy File | New Location | Purpose |
|-------------|-------------|---------|
| `admin_chat_flush.php` | `app/Services/CraftySyntax/LegacyAdminChatFlush.php` | Admin chat flush |
| `admin_chat_refresh.php` | `app/Services/CraftySyntax/LegacyAdminChatRefresh.php` | Admin refresh |
| `user_chat_flush.php` | `app/Services/CraftySyntax/LegacyUserChatFlush.php` | User chat flush |
| `external_chat_xmlhttp.php` | `app/Services/CraftySyntax/LegacyExternalChatXmlHttp.php` | External XML HTTP |

---

## 🎯 **Migration Authority**

This plan serves as the **authoritative reference** for Phase 2 of the Crafty Syntax to Lupopedia migration. All file copying, location mapping, and integration requirements are explicitly documented.

**Status**: ✅ **PLAN COMPLETE** - Ready for execution with full preservation requirements.
