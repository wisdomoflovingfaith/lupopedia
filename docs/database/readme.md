---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/readme.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/readme.md
  status: ''
  when_updated: '20260513053635'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: database_documentation
  channel_key: null
  federation_node_id: null
  thread_key: 4.0.89-database-docs
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

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
