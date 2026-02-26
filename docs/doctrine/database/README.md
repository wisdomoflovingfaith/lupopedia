# 📁 Database Documentation Moved

## 🔄 **Migration Notice**

**Date:** 2026-02-27  
**Status:** ✅ Complete  
**New Location:** `docs/database/lupopedia/tables/`

---

## 📍 **Where to Find Database Documentation**

All database table documentation has been moved to the new centralized location:

```
docs/database/lupopedia/tables/
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
docs/database/
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
| `docs/doctrine/database/actors.md` | `docs/database/lupopedia/tables/lupo_actors.md` |
| `docs/doctrine/database/channels.md` | `docs/database/lupopedia/tables/lupo_channels.md` |
| `docs/doctrine/database/dialog_messages.md` | `docs/database/lupopedia/tables/lupo_dialog_messages.md` |
| `docs/doctrine/database/sessions.md` | `docs/database/lupopedia/tables/lupo_sessions.md` |
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
- **Reference:** See `docs/database/lupopedia/README.md` for database overview

---

*This directory is retained for backward compatibility. All active development has moved to the new location.*
