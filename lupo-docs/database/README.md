# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\README.md"
  file_hash: "de4c2790be612578a75098624d5cb1cb68cf336dbe81ed2864b6171580f7716f"
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
  file_path_from_root: "docs\database\README.md"
  file_hash: "0233f7aff0b4acef9c0c00037ded1a41d38ee5ce94bdc8293bfd215bef25aa3a"
  file_path_from_root: "docs\database\README.md"
  file_hash: "88dd20b3599a011bcade17a34b0aa1217c433e6cb431f6aa433df161bff6d4bb"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "🗄️ Lupopedia Database Documentation"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 🗄️ Lupopedia Database Documentation

**Version:** 4.0.47  
**Status:** ✅ Active Development  
**Purpose:** Centralized database table documentation and FLARE relationship mapping

---

## 📁 **Database Documentation Structure**

This directory contains comprehensive documentation for all Lupopedia database tables, organized by database name.

```
docs/database/
├── lupopedia/
│   ├── tables/
│   │   ├── lupo_actors.md
│   │   ├── lupo_channels.md
│   │   ├── lupo_dialog_messages.md
│   │   ├── lupo_edges.md
│   │   ├── lupo_sessions.md
│   │   └── ... (all 210+ tables)
│   └── README.md
├── lupopedia_worms/
│   ├── tables/
│   │   └── ... (worms database tables)
│   └── README.md
└── README.md (this file)
```

---

## 🎯 **Database Overview**

### **Lupopedia Database**
- **Table Count:** Run `python scripts/generate_toon_files.py` and use the TOON file count — do not hardcode (see TABLE_COUNT_DOCTRINE).
- **Purpose:** Main application database
- **Status:** Production-ready
- **Documentation:** Complete coverage planned

### **Lupopedia_Worms Database**
- **Table Count:** TBD
- **Purpose:** Worms-specific functionality
- **Status:** Development
- **Documentation:** In progress

---

## 📊 **Current Documentation Status**

### **✅ Completed Tables**
- Core system tables (actors, channels, sessions)
- Content management tables
- Legacy Crafty Syntax tables
- Authentication and authorization tables

### **🔄 In Progress**
- FLARE relationship mapping
- Cross-table dependencies
- Performance optimization documentation

### **📋 Planned**
- All remaining 190+ tables
- TOON schema integration
- Automated relationship discovery

---

## 🔗 **FLARE Integration**

### **Relationship Discovery**
- **Tool:** `scripts/flare_edge_suggester.py`
- **Purpose:** Automatic table relationship discovery
- **Output:** FLARE edges with weights and metadata

### **Documentation Standards**
- **Headers:** Complete FLARE headers on all table docs
- **Footer:** Outbound edges to related tables
- **Validation:** Automated validation of relationships

---

## 🚀 **Development Guidelines**

### **Adding New Table Documentation**
1. Create file: `docs/database/{database}/tables/lupo_{table_name}.md`
2. Add FLARE headers with proper metadata
3. Document schema, relationships, and usage patterns
4. Include FLARE footer with outbound edges
5. Validate with automated tools

### **Updating Existing Documentation**
1. Update FLARE headers with current metadata
2. Add new relationships discovered
3. Verify cross-references are accurate
4. Run validation tools

---

## 📈 **Table Statistics**

### **Lupopedia Database**
- **Total Tables:** 210 (current TOON count)
- **Documented:** 18 (moved from doctrine/database)
- **Remaining:** 192
- **Priority:** 45 critical tables

### **Table Categories**
- **Core System:** ~15 tables (actors, channels, sessions, etc.)
- **Content Management:** ~30 tables (contents, artifacts, collections)
- **Agent System:** ~15 tables (agents, heartbeats, tool calls)
- **Integration:** ~20 tables (API, webhooks, analytics)
- **Legacy:** ~50 tables (Crafty Syntax compatibility)
- **Reference:** ~80 tables (lookup, configuration)

---

## 🔍 **Discovery Tools**

### **Automated Tools**
- **TOON Generator:** `scripts/generate_toon_files.py`
- **Edge Suggester:** `scripts/flare_edge_suggester.py`
- **Batch Updater:** `tools/update_flare_edges.py`

### **Validation Tools**
- **Schema Validator:** Validates TOON consistency
- **FLARE Validator:** Checks header/footer compliance
- **Relationship Validator:** Verifies edge accuracy

---

## 📞 **Contact & Coordination**

### **Development Lead**
- **Agent:** Windsurf (1001)
- **Specialization:** FLARE Protocol & Database Documentation
- **Thread:** 4.0.47 Development - Channel 42

### **Contributing**
1. Check existing documentation before adding new
2. Follow FLARE header/footer standards
3. Use automated tools for relationship discovery
4. Validate all changes before commit

---

## 🔮 **Future Roadmap**

### **Phase 2 (Current)**
- [ ] Document 45 critical tables
- [ ] Complete FLARE relationship mapping
- [ ] Automated validation tools
- [ ] Cross-reference system

### **Phase 3 (Planned)**
- [ ] Document all remaining tables
- [ ] Interactive relationship diagrams
- [ ] Performance optimization guides
- [ ] Migration documentation

---

*This documentation is part of the FLARE relationship automation initiative in version 4.0.47. For more information, see the development thread in channel 42.*