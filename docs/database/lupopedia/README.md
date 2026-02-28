# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\README.md"
  file_hash: "62bda99f82e85ae2e4de621b8bb9fbf092f01950876467344e6261496a98a6e5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "🗄️ Lupopedia Database - Table Documentation"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "readmemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 🗄️ Lupopedia Database - Table Documentation

**Database:** lupopedia  
**Version:** 4.0.47  
**Table Count:** 210+ (based on TOON files)  
**Status:** ✅ Production - Documentation In Progress

---

## 📋 **Table Documentation Index**

This directory contains documentation for all tables in the main `lupopedia` database. Each table has its own comprehensive documentation file with FLARE headers and relationship mapping.

### **🔍 Quick Reference**

| Table | Documentation | Status | Priority |
|-------|----------------|--------|----------|
| [lupo_actors](tables/lupo_actors.md) | Actor registry and identity | ✅ Complete | Critical |
| [lupo_channels](tables/lupo_channels.md) | Channel configuration | ✅ Complete | Critical |
| [lupo_dialog_messages](tables/lupo_dialog_messages.md) | Dialog history | ✅ Complete | Critical |
| [lupo_sessions](tables/lupo_sessions.md) | Session management | ✅ Complete | Critical |
| [lupo_auth_users](tables/lupo_auth_users.md) | User authentication | ✅ Complete | Critical |
| [lupo_edges](tables/lupo_edges.md) | Relationship graph | 📋 Planned | Critical |
| [lupo_contents](tables/lupo_contents.md) | Content management | 📋 Planned | Critical |
| [lupo_atoms](tables/lupo_atoms.md) | System configuration | 📋 Planned | Critical |

---

## 🎯 **Database Architecture**

### **Core System Tables**
- **Identity & Access:** actors, auth_users, sessions
- **Communication:** channels, dialog_messages
- **Configuration:** atoms, settings
- **Relationships:** edges (enhanced for FLARE)

### **Content Management Tables**
- **Storage:** contents, artifacts, collections
- **Metadata:** embeddings, tags, categories
- **Versioning:** revisions, history
- **Search:** indexes, vectors

### **Agent System Tables**
- **Configuration:** agents, agent_capabilities
- **Activity:** heartbeats, tool_calls, operations
- **Learning:** models, training_data, feedback

### **Integration Tables**
- **API:** tokens, endpoints, rate_limits
- **External:** webhooks, federation_nodes
- **Analytics:** visits, events, metrics

### **Legacy Tables**
- **Crafty Syntax:** Auto-invite, CRM, departments
- **Migration:** Temporary tables for upgrade
- **Compatibility:** Bridge tables for old system

---

## 📊 **Table Statistics**

### **By Category**
| Category | Count | Status | Documentation |
|----------|-------|--------|----------------|
| Core System | ~15 | Production | 80% Complete |
| Content Management | ~30 | Production | 20% Complete |
| Agent System | ~15 | Production | 10% Complete |
| Integration | ~20 | Production | 5% Complete |
| Legacy | ~50 | Maintenance | 60% Complete |
| Reference | ~80 | Stable | 30% Complete |

### **By Priority**
| Priority | Count | Target Date | Status |
|----------|-------|-------------|--------|
| Critical (P0) | 15 | 2026-02-28 | 33% Complete |
| High (P1) | 30 | 2026-03-07 | 10% Complete |
| Medium (P2) | 45 | 2026-03-14 | 5% Complete |
| Low (P3) | 120 | 2026-03-31 | 2% Complete |

---

## 🔗 **FLARE Relationship Mapping**

### **Relationship Discovery**
- **Tool:** `scripts/flare_edge_suggester.py`
- **Command:** `python scripts/flare_edge_suggester.py --database lupopedia --include-db`
- **Output:** FLARE edges with weights and discovery methods

### **Key Relationships**
```yaml
# Example: Actor to Channels
lupo_actors → lupo_channels (actor manages channel)
# Example: Channels to Dialog Messages  
lupo_channels → lupo_dialog_messages (channel contains messages)
# Example: Contents to Edges
lupo_contents → lupo_edges (content has relationships)
```

### **Edge Types Used**
- **manages** (0.9-1.0) - Actor manages channel
- **contains** (0.8-1.0) - Channel contains messages
- **references** (0.5-1.0) - General references
- **implements** (0.8-1.0) - Implementation relationships
- **depends_on** (0.8-1.0) - Dependencies

---

## 🚀 **Development Guidelines**

### **Documentation Standards**
1. **FLARE Headers:** Complete metadata on all files
2. **Schema Reference:** TOON file integration
3. **Usage Patterns:** Common queries and operations
4. **Relationship Mapping:** Outbound edges to related tables
5. **Performance Notes:** Indexes and optimization tips

### **File Naming Convention**
- **Format:** `lupo_{table_name}.md`
- **Example:** `lupo_actors.md`, `lupo_dialog_messages.md`
- **Consistency:** All files follow same pattern

### **Content Template**
```markdown
# Table: lupo_{table_name}

## Overview
Purpose and role in system

## Schema
Complete field documentation

## Relationships
FLARE edges to related tables

## Usage Patterns
Common queries and operations

## Performance
Indexes and optimization notes
```

---

## 🔍 **Discovery & Validation**

### **Automated Tools**
```bash
# Generate table relationships
python scripts/flare_edge_suggester.py --database lupopedia --include-db

# Validate FLARE compliance
python tools/validate_flare_headers.py --database lupopedia

# Update relationship edges
python tools/update_flare_edges.py --database lupopedia --scan tables/
```

### **Quality Assurance**
- **Schema Validation:** TOON file consistency
- **FLARE Validation:** Header/footer compliance
- **Relationship Validation:** Edge accuracy verification
- **Cross-Reference Check:** Link validation

---

## 📈 **Performance Considerations**

### **High-Traffic Tables**
- **lupo_sessions:** Frequent reads/writes
- **lupo_dialog_messages:** High volume inserts
- **lupo_edges:** Relationship queries
- **lupo_contents:** Content access

### **Index Strategy**
- **Primary Keys:** All tables have proper PKs
- **Foreign Keys:** Indexed for joins
- **Query Patterns:** Optimized for common access
- **FLARE Queries:** Specialized indexes for edge discovery

---

## 📞 **Contact & Coordination**

### **Development Team**
- **Lead:** Windsurf (1001) - FLARE Protocol & Database Documentation
- **Schema Expert:** TBD - Database architecture and optimization
- **Content Writer:** TBD - Documentation and examples

### **Development Thread**
- **Channel:** 42 (FLARE Protocol Development)
- **Thread:** 4.0.47 Development
- **Status:** Phase 2 - Database Documentation

### **Contributing**
1. Check existing documentation before adding
2. Follow FLARE standards and naming conventions
3. Use automated tools for relationship discovery
4. Validate all changes before commit

---

## 🔮 **Roadmap**

### **Phase 2 (Current - Feb 2026)**
- [x] Move existing documentation from doctrine/database
- [x] Create database-centric structure
- [ ] Document 15 critical tables
- [ ] Complete FLARE relationship mapping
- [ ] Automated validation tools

### **Phase 3 (Mar 2026)**
- [ ] Document all 45 high-priority tables
- [ ] Interactive relationship diagrams
- [ ] Performance optimization guides
- [ ] Migration documentation

### **Phase 4 (Apr 2026)**
- [ ] Document all remaining tables
- [ ] Advanced relationship analytics
- [ ] Automated documentation generation
- [ ] Cross-database relationships

---

*This database documentation is part of the FLARE relationship automation initiative. For the complete development context, see the main database README and the 4.0.47 development thread.*
