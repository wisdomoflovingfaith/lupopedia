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
