# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/database/README.md"
  file_hash: "5230a87d9fdaa012e9b1876e06d6486faec25eca0cdc254f8f37e4070ed40347"
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
  file_path_from_root: "lupo-docs\doctrine\database\README.md"
  file_hash: "08e4b5d0d7642eab74b55c8dcae06fd67f2318e600f9a9cc0223561ca0ca58a2"
  file_path_from_root: "lupo-docs\doctrine\database\README.md"
  file_hash: "430099c1ebb97df48e601476f7414e3012eb45d89d9d141bb694daff925d96b9"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "📁 Database Documentation Moved"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "database", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 📁 Database Documentation Moved

## 🔄 **Migration Notice**

**Date:** 2026-02-27  
**Status:** ✅ Complete  
**New Location:** `lupo-docs/database/lupopedia/tables/`

---

## 📍 **Where to Find Database Documentation**

All database table documentation has been moved to the new centralized location:

```
lupo-docs/database/lupopedia/tables/
├── actor_channel_roles.md
├── actor_departments.md
├── actor_reply_templates.md
├── actors.md
├── actors_old.md
├── audit_log.md
├── auth_users.md
├── channels.md
├── crafty_syntax_auto_invite.md
├── crm_lead_messages.md
├── crm_leads.md
├── departments.md
├── dialog_messages.md
├── federation_nodes.md
├── sessions.md
└── ... (all other table documentation)
```

---

## 🎯 **Why This Change Was Made**

### **1. Database-Centric Organization**
- **Better Structure:** All tables organized by database name
- **Clear Separation:** Lupopedia vs Lupopedia_Worms databases
- **Scalability:** Easy to add new databases in future

### **2. Improved Navigation**
- **Single Location:** All table docs in one place
- **Consistent Naming:** `lupo_{table_name}.md` format
- **Easier Discovery:** No functional categorization needed

### **3. FLARE Integration**
- **Relationship Mapping:** Better support for FLARE edge discovery
- **Cross-Reference:** Easier to document table relationships
- **Automation Ready:** Structure supports automated documentation tools

---

## 🔗 **New Documentation Structure**

```
lupo-docs/database/
├── lupopedia/
│   ├── tables/
│   │   ├── lupo_actor_channel_roles.md
│   │   ├── lupo_actor_departments.md
│   │   ├── lupo_actor_reply_templates.md
│   │   ├── lupo_actors.md
│   │   ├── lupo_actors_old.md
│   │   ├── lupo_audit_log.md
│   │   ├── lupo_auth_users.md
│   │   ├── lupo_channels.md
│   │   ├── lupo_crafty_syntax_auto_invite.md
│   │   ├── lupo_crm_lead_messages.md
│   │   ├── lupo_crm_leads.md
│   │   ├── lupo_departments.md
│   │   ├── lupo_dialog_messages.md
│   │   ├── lupo_federation_nodes.md
│   │   ├── lupo_sessions.md
│   │   └── ... (all other tables)
│   └── README.md
├── lupopedia_worms/
│   └── tables/
│       └── ... (worms database tables)
└── README.md (main index)
```

---

## 📋 **Files Moved**

The following files have been migrated:

| Original Path | New Path |
|---------------|----------|
| `lupo-docs/doctrine/database/actors.md` | `lupo-docs/database/lupopedia/tables/lupo_actors.md` |
| `lupo-docs/doctrine/database/channels.md` | `lupo-docs/database/lupopedia/tables/lupo_channels.md` |
| `lupo-docs/doctrine/database/dialog_messages.md` | `lupo-docs/database/lupopedia/tables/lupo_dialog_messages.md` |
| `lupo-docs/doctrine/database/sessions.md` | `lupo-docs/database/lupopedia/tables/lupo_sessions.md` |
| ... (and all other table documentation) | ... |

---

## 🎯 **Next Steps**

1. **Update References:** Any links to old locations should be updated
2. **FLARE Integration:** Add FLARE headers to all moved files
3. **Relationship Mapping:** Document table relationships using FLARE edges
4. **Complete Coverage:** Document remaining tables from TOON files

---

## 📞 **Contact**

For questions about this migration or database documentation:
- **Lead:** Windsurf (1001) - FLARE Protocol & Database Documentation
- **Thread:** 4.0.47 Development - Channel 42
- **Reference:** See `lupo-docs/database/lupopedia/README.md` for database overview

---

*This directory is retained for backward compatibility. All active development has moved to the new location.*
