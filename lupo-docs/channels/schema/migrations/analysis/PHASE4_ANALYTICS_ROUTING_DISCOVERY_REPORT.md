# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\schema\migrations\analysis\PHASE4_ANALYTICS_ROUTING_DISCOVERY_REPORT.md"
  file_hash: "2ef8731d9e90a28652e40a88c58f4f40c5448f0b3ff9dd99a242a2402d5c2ef3"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\schema\migrations\analysis\PHASE4_ANALYTICS_ROUTING_DISCOVERY_REPORT.md"
  file_hash: "bb83d5c0bbb2184dbff4401c0775be4481d6ae219659efd7f31f22ce322b6a18"
  file_path_from_root: "docs\channels\schema\migrations\analysis\PHASE4_ANALYTICS_ROUTING_DISCOVERY_REPORT.md"
  file_hash: "60284c886a3c160531411b88fe586afd0f5513fa3e4b8d1da3199ede7d977cee"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "📋 **Phase 4: Analytics & Routing Discovery Report**"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "analysis", "phase4_analytics_routing_discovery_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 📋 **Phase 4: Analytics & Routing Discovery Report**

## 🎯 **HERITAGE-SAFE MODE: Analytics & Routing Files Discovered**

**Objective**: Identify all legacy analytics and routing files before migration.

---

## 🔍 **STEP 1: Legacy Analytics Files Discovered**

### **📊 Core Analytics Files**
| File | Purpose | Analytics Type | Status |
|------|---------|----------------|--------|
| `setup.php` | Database setup and analytics table creation | All analytics types | DEPRECATED |
| `gc.php` | Garbage collection and analytics cleanup | All analytics types | DEPRECATED |
| `data_clean.php` | Data cleaning and maintenance | All analytics types | DEPRECATED |
| `graph.php` | Analytics reporting and visualization | Graph generation | DEPRECATED |

### **📈 Data Analytics Files**
| File | Purpose | Analytics Type | Status |
|------|---------|----------------|--------|
| `data_visits.php` | Visit tracking and analytics | visits_daily/monthly | DEPRECATED |
| `data_paths.php` | Path analysis and tracking | paths_daily/monthly | DEPRECATED |
| `data_referers.php` | Referrer tracking and analysis | referers_daily/monthly | DEPRECATED |
| `data_keywords.php` | Keyword extraction and tracking | keywords_daily/monthly | DEPRECATED |
| `data_functions.php` | Analytics helper functions | All analytics types | DEPRECATED |
| `data_users.php` | User analytics and tracking | User behavior | DEPRECATED |
| `data_messages.php` | Message analytics | Chat analytics | DEPRECATED |
| `data_transcripts.php` | Transcript analytics | Chat analytics | DEPRECATED |

### **🔧 Analytics Support Files**
| File | Purpose | Analytics Type | Status |
|------|---------|----------------|--------|
| `functions.php` | Analytics helper functions | All analytics types | DEPRECATED |
| `iphone/functions.php` | Mobile analytics functions | Mobile analytics | DEPRECATED |

---

## 🔍 **STEP 2: Routing Files Discovered**

### **🎯 Core Routing Files**
| File | Purpose | Routing Type | Status |
|------|---------|-------------|--------|
| `index.php` | Main entry point and routing | Initial routing | MIGRATE |
| `live.php` | Live chat entry point | Chat routing | MIGRATE |
| `choosedepartment.php` | Department selection routing | Department routing | MIGRATED (Phase 2) |
| `departments.php` | Department management routing | Department routing | MIGRATED (Phase 2) |
| `channels.php` | Channel management routing | Channel routing | MIGRATED (Phase 2) |

### **🔄 Redirection Logic Files**
| File | Purpose | Routing Type | Status |
|------|---------|-------------|--------|
| `external_frameset.php` | External chat routing | Frame routing | MIGRATE |
| `prefs.php` | User preference routing | User routing | MIGRATE |
| `settings.php` | Settings routing | Configuration routing | MIGRATE |

### **🎪 Landing & Entry Points**
| File | Purpose | Routing Type | Status |
|------|---------|-------------|--------|
| `autoinvite.php` | Auto-invitation routing | Invitation routing | MIGRATE |
| `autolead.php` | Auto-lead routing | Lead routing | MIGRATE |
| `colorchange.php` | Theme selection routing | Theme routing | MIGRATE |

---

## 📋 **Analytics Tables Referenced**

### **🗄️ Deprecated Analytics Tables**
| Table | Purpose | Status |
|-------|---------|--------|
| `livehelp_visits_daily` | Daily visit tracking | DEPRECATED |
| `livehelp_visits_monthly` | Monthly visit tracking | DEPRECATED |
| `livehelp_paths_daily` | Daily path tracking | DEPRECATED |
| `livehelp_paths_monthly` | Monthly path tracking | DEPRECATED |
| `livehelp_referers_daily` | Daily referrer tracking | DEPRECATED |
| `livehelp_referers_monthly` | Monthly referrer tracking | DEPRECATED |
| `livehelp_keywords_daily` | Daily keyword tracking | DEPRECATED |
| `livehelp_keywords_monthly` | Monthly keyword tracking | DEPRECATED |
| `livehelp_identity_daily` | Daily identity tracking | DEPRECATED |
| `livehelp_identity_monthly` | Monthly identity tracking | DEPRECATED |

---

## 🎯 **Analytics Override Applied**

### **✅ DEPRECATED Analytics Files (13 files)**
- **Core Analytics**: `setup.php`, `gc.php`, `data_clean.php`, `graph.php`
- **Data Analytics**: `data_visits.php`, `data_paths.php`, `data_referers.php`, `data_keywords.php`, `data_functions.php`, `data_users.php`, `data_messages.php`, `data_transcripts.php`
- **Support Files**: `functions.php` (analytics functions), `iphone/functions.php`

### **✅ TOON Analytics System Replacement**
All analytics functionality will be replaced with TOON event logging using:
- `lupo_event_log` - Central event logging
- `lupo_event_metadata` - Event metadata
- `lupo_actor_events` - Actor-specific events
- `lupo_content_events` - Content interaction events
- `lupo_session_events` - Session-specific events
- `lupo_tab_events` - Tab interaction events
- `lupo_world_events` - World-level events
- `lupo_campaign_events` - Campaign tracking
- `lupo_campaign_vars_daily/monthly` - Campaign variables

---

## 🚀 **Routing Migration Ready**

### **✅ Routing Files to Migrate (8 files)**
| File | Purpose | Migration Priority |
|------|---------|-------------------|
| `index.php` | Main entry point | HIGH |
| `live.php` | Live chat entry point | HIGH |
| `external_frameset.php` | External chat routing | MEDIUM |
| `prefs.php` | User preference routing | MEDIUM |
| `settings.php` | Settings routing | MEDIUM |
| `autoinvite.php` | Auto-invitation routing | LOW |
| `autolead.php` | Auto-lead routing | LOW |
| `colorchange.php` | Theme selection routing | LOW |

---

## 📋 **Discovery Summary**

### **✅ Total Files Discovered**
- **Analytics Files**: 13 files (all DEPRECATED)
- **Routing Files**: 8 files (to be migrated)
- **Already Migrated**: 4 routing files (Phase 2)

### **✅ Analytics Override Complete**
- All legacy analytics files identified and marked DEPRECATED
- TOON analytics system ready for implementation
- No legacy analytics logic will be preserved

### **✅ Routing Migration Ready**
- All routing files identified and categorized
- Migration priorities established
- Database mapping ready for application

---

**Status**: ✅ **DISCOVERY COMPLETE** - Ready for Phase 4 analytics override and routing migration.