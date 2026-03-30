---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/database/DATABASE_TABLE_ORGANIZATION_CONTEXT.md"
  web_path: "http://www.lupopedia.com/lupo-docs/database/DATABASE_TABLE_ORGANIZATION_CONTEXT.md"
  last_modified_utc: "20260330"
  channel_id: 42
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "documentation"
  artifact_kind: "database_organization"
  purpose: "Context for database table organization and architectural patterns in Lupopedia."
  tags:
    - database
    - organization
    - architecture
    - table_structure
    - 4.0.93
---

# Database Table Organization Context

## 🎯 **Purpose**

This document provides context for how database tables should be organized in Lupopedia, serving as a reference for architectural decisions and table creation patterns.

## 📋 **Existing Organization Contexts**

### **1. Primary Organization Documents**
- **`lupo-docs/ORGANIZATION.md`** - System-wide organization guide
- **`lupo-docs/status/TOON_DATABASE_STRUCTURE_COLLECTIONS_ACTORS_ORGANIZATION.md`** - Database structure and actors
- **`lupo-rules/root/required-tables-future-features-doctrine.md`** - Table classification rules

### **2. Database Documentation Structure**
```
lupo-docs/database/
├── lupopedia/
│   ├── tables/active/          # Per-table documentation
│   ├── json/                  # Generated TOON files
│   ├── toon/                  # TOON format files
│   └── mysql/
│       ├── install/             # Installation schemas
│       └── seed/               # Seed data
└── archive/                    # Historical table definitions
```

## 🏗️ **Table Organization Principles**

### **Core Doctrine (from required-tables-future-features-doctrine.md)**

1. **Required Tables** (`install_new_lupopedia.sql`)
   - Core system functionality
   - Referenced in import scripts
   - Used by active PHP classes
   - **NEVER** move to `future_features_lupopedia.sql`

2. **Future Features Tables** (`future_features_lupopedia.sql`)
   - Experimental features
   - Optional functionality
   - **CAN** be moved to install when promoted

3. **Archive Tables** (`lupo-database/migrations_legacy/`)
   - Historical definitions
   - Deprecated schemas
   - Reference only

## 📊 **Table Categories & Patterns**

### **Identity & Access**
- `lupo_actors` - Core identity system
- `lupo_auth_users` - Human authentication
- `lupo_sessions` - Session management

### **Context Questions & Answers**
Finalized contexts support:
- **Context Storage**: `lupo_contexts` table (main knowledge storage)
- **Context Cards**: `lupo_context_cards` table (context metadata)
- **Context Mapping**: `lupo_contexts_map` table (context relationships)
- **Truth Context**: `lupo_truth_context_map` table (truth relationships)
- **Questions/Answers**: Can be implemented using `lupo_edges` with `edge_type = 'context_question_answer'`
- **Navigation**: Semantic search through context relationships via `lupo_edges`

### **Relations & Edges**
- `lupo_edges` - **Master polymorphic edge table**
- `lupo_edge_types` - Edge type definitions
- `lupo_edge_type_definitions` - Edge metadata

### **Coordination & Communication**
- `lupo_channels` - Channel definitions (live coordination)
- `lupo_dialog_threads` - Thread management (live coordination)
- `lupo_dialog_messages` - Message storage (live coordination)
- `lupo_channel_content` - Channel content (live coordination)

**Hybrid-Mirror Architecture**:
- **Live System**: Database tables above for real-time coordination
- **Archive System**: Filesystem mirrors (`lupo-channels/42/threads/`, `broadcasts/`, `content/`)
- **Web UI**: Reads **only** from database tables
- **Git Lineage**: Reads **only** from filesystem mirrors
- **Import Status**: Filesystem marked as "pre-canonical" pending database import

### **Audit & Logging**
- `lupo_logs` - System logging
- `lupo_audit_trail` - Change tracking
- `lupo_banned_actors` - Access control

## 🚨 **Architectural Rules**

### **Polymorphic Edge System**
**USE `lupo_edges` FOR ALL EDGE RELATIONSHIPS**:

```sql
-- Context to Message Edge
INSERT INTO lupo_edges (
  left_object_type = 'context',
  left_object_id = [context_id],
  right_object_type = 'message',
  right_object_id = [message_id],
  edge_type = 'context_contains_message',
  channel_id = 42,
  relationship_type = 'semantic'
);

-- Message to Message Edge
INSERT INTO lupo_edges (
  left_object_type = 'message',
  left_object_id = [source_message_id],
  right_object_type = 'message',
  right_object_id = [target_message_id],
  edge_type = 'message_reply_to',
  channel_id = 42,
  relationship_type = 'temporal'
);
```

### **Table Creation Doctrine**
1. **No Foreign Keys** - Application-managed relationships
2. **No Triggers** - Application-level logic only
3. **BIGINT Timestamps** - UTC format: YYYYMMDDHHIISS
4. **Explicit IDs** - No AUTO_INCREMENT, use IdGenerator
5. **Soft Deletes** - `is_deleted` + `deleted_ymdhis`

## 📁 **File Organization Patterns**

### **Class Organization**
```
lupo-includes/classes/
├── AI/                    # AI-related classes
├── ANUBIS/                # Security classes
├── Database/               # Database abstraction
├── UI/                     # User interface classes
├── Sync*                   # Synchronization classes
└── [ClassName].php         # Individual classes
```

### **Script Organization**
```
lupo-scripts/
├── Sync*.php               # Synchronization scripts
├── generate_*.py           # Generation utilities
├── validate_*.py           # Validation tools
└── migrate_*.php            # Migration scripts
```

## 🎯 **Implementation Guidelines**

### **When Creating New Tables**

1. **Check Category** - Does it fit existing patterns?
2. **Use Polymorphic Edges** - Can `lupo_edges` handle the relationship?
3. **Follow Naming** - `lupo_[purpose]_[subtype]` pattern
4. **Document** - Add to `lupo-docs/database/lupopedia/tables/active/`
5. **Update TOON** - Regenerate with `python lupo-scripts/generate_toon_files.py`

### **When Modifying Existing Tables**

1. **Check Install Script** - Is it in `install_new_lupopedia.sql`?
2. **Check Future Features** - Should it be in `future_features_lupopedia.sql`?
3. **Update Documentation** - Reflect changes in table docs
4. **Regenerate TOON** - Keep JSON/TOON in sync
5. **Test Migration** - Verify upgrade path works

## 🔍 **Context Sources**

### **Authoritative References**
- **Table Structure**: `lupo-docs/database/lupopedia/tables/active/[table].md`
- **Install Schema**: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- **Generated TOON**: `lupo-database/lupopedia/json/[table].json`
- **Organization Rules**: `lupo-rules/root/required-tables-future-features-doctrine.md`

### **Decision Framework**
When unsure about table organization:
1. **Check existing patterns** in active tables
2. **Review organization doctrine** documents
3. **Consider polymorphic edges** before new tables
4. **Validate against required vs future** classification
5. **Document the decision** for future reference

---

**This context document should be referenced when making database architectural decisions to ensure consistency with established patterns.**
