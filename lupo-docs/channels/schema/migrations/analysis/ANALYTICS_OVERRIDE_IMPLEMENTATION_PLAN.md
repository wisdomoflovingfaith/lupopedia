# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\schema\migrations\analysis\ANALYTICS_OVERRIDE_IMPLEMENTATION_PLAN.md"
  file_hash: "4d498d0b399c9298beae03fda339359766085f813bbfdec34d6654e20504a802"
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
  file_path_from_root: "docs\channels\schema\migrations\analysis\ANALYTICS_OVERRIDE_IMPLEMENTATION_PLAN.md"
  file_hash: "54452901511f403335ecb91e7df7003558643f3af77e4dcf29ce461897888880"
  file_path_from_root: "docs\channels\schema\migrations\analysis\ANALYTICS_OVERRIDE_IMPLEMENTATION_PLAN.md"
  file_hash: "81b6f0fee97ea64eb9ebb0728207fc5d6f118dda12b71b8567332eb034a7bfd0"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "📋 **Analytics Override Implementation Plan**"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "analysis", "analytics_override_implementation_planmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 📋 **Analytics Override Implementation Plan**

## 🎯 **HERITAGE-SAFE MODE: Legacy Analytics Deprecation**

**Objective**: Identify and deprecate legacy Crafty Syntax analytics system and replace with TOON-based analytics.

---

## 🔍 **Legacy Analytics Files Identified**

### **📊 Core Analytics Files**
| File | Purpose | Legacy Tables | Status |
|------|---------|---------------|--------|
| `setup.php` | Database setup and migration | All analytics tables | DEPRECATED |
| `gc.php` | Garbage collection and cleanup | All analytics tables | DEPRECATED |
| `data_clean.php` | Data cleaning and maintenance | All analytics tables | DEPRECATED |
| `functions.php` | Analytics helper functions | All analytics tables | DEPRECATED |
| `graph.php` | Analytics reporting and visualization | All analytics tables | DEPRECATED |

### **🗄️ Deprecated Legacy Tables**
- `livehelp_referers_daily` → **DEPRECATED**
- `livehelp_referers_monthly` → **DEPRECATED**
- `livehelp_paths_daily` → **DEPRECATED**
- `livehelp_paths_monthly` → **DEPRECATED**
- `livehelp_visits_daily` → **DEPRECATED**
- `livehelp_visits_monthly` → **DEPRECATED**
- `livehelp_keywords_daily` → **DEPRECATED**
- `livehelp_keywords_monthly` → **DEPRECATED**

---

## 🚀 **TOON Analytics System**

### **📋 Replacement Tables**
| TOON Table | Purpose | Replaces Legacy |
|------------|---------|-----------------|
| `lupo_event_log` | Central event logging | All legacy analytics |
| `lupo_event_metadata` | Event metadata | Legacy keyword extraction |
| `lupo_actor_events` | Actor-specific events | Legacy user tracking |
| `lupo_content_events` | Content interaction events | Legacy path tracking |
| `lupo_session_events` | Session-specific events | Legacy visit tracking |
| `lupo_tab_events` | Tab interaction events | Legacy referer tracking |
| `lupo_world_events` | World-level events | Legacy global analytics |
| `lupo_campaign_events` | Campaign tracking | Legacy campaign analytics |
| `lupo_campaign_vars_daily` | Daily campaign variables | Legacy daily analytics |
| `lupo_campaign_vars_monthly` | Monthly campaign variables | Legacy monthly analytics |

---

## 🔧 **Implementation Strategy**

### **Phase 1: Deprecation**
1. **Mark all legacy analytics files as DEPRECATED**
2. **Add TOON analytics event system calls**
3. **Preserve legacy tables for historical data only**

### **Phase 2: Event Mapping**
1. **Map legacy analytics events to TOON events**
2. **Create event transformation layer**
3. **Implement TOON analytics integration**

### **Phase 3: Cleanup**
1. **Remove deprecated legacy analytics code**
2. **Migrate historical data to TOON format**
3. **Complete transition to TOON analytics**

---

## 📋 **Event Mapping Strategy**

### **🔄 Legacy → TOON Event Mapping**
| Legacy Event | TOON Event | Transformation |
|--------------|------------|----------------|
| Page visit | `lupo_content_events` | Content interaction event |
| User session | `lupo_session_events` | Session tracking event |
| Keyword search | `lupo_event_metadata` | Search term metadata |
| Referrer tracking | `lupo_tab_events` | Tab referer event |
| Path analysis | `lupo_content_events` | Content path event |

---

## 🚫 **Deprecation Rules**

### **❌ DO NOT Migrate**
- Legacy analytics tables
- Keyword extraction logic
- Query string parsing for search terms
- Legacy analytics functions
- Analytics reporting interfaces

### **✅ DO Preserve**
- Historical data in legacy tables
- Legacy table structure for reference
- Migration scripts for data transfer
- Documentation of legacy analytics patterns

---

## 📋 **Implementation Authority**

This plan provides **step-by-step instructions** for deprecating legacy analytics and implementing TOON-based analytics while preserving historical data.

**Status**: ✅ **PLAN COMPLETE** - Ready for execution with clear deprecation requirements.
