---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_atoms.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260227"
  delegation_chain: "1001:10000"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_atoms table - system-wide atomic configuration and constants storage"
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "core_system", "configuration", "constants", "atomic_values"]
  tags: ["database", "atoms", "configuration", "constants", "system_values", "global_settings"]
  lupo_agent: "windsurf"
  # Table-specific metadata from TOON
  lupo_atoms.atom_id: "BIGINT primary key containing YYYYMMDDHHMMSS UTC timestamp"
  lupo_atoms.atom_name: "VARCHAR(255) NOT NULL unique atomic name/identifier"
  lupo_atoms.context_id: "BIGINT NOT NULL context or scope identifier"
  lupo_atoms.is_authoritative: "TINYINT NOT NULL DEFAULT 0 authoritative source flag"
  lupo_atoms.value_json: "JSON atomic value in JSON format"
  lupo_atoms.summary: "TEXT human-readable summary of atom value"
  lupo_atoms.tags: "VARCHAR(255) categorization and discovery tags"
  lupo_atoms.created_ymd: "BIGINT NOT NULL DEFAULT 0 YYYYMMDD UTC creation date"
  lupo_atoms.updated_ymd: "BIGINT NOT NULL YYYYMMDD UTC update date"
  table_primary_key: "atom_id"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_collation: "utf8mb4_unicode_ci"
  table_indexes: ["PRIMARY", "lupo_atoms_idx_atom_context", "lupo_atoms_idx_atom_name", "lupo_atoms_idx_authoritative", "lupo_atoms_idx_context_id"]
  table_foreign_keys: ["context_id"]

# 💡 FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml
# This will analyze content, TOON schemas, and database relationships to suggest
# appropriate outbound_edges with weights, reasons, and discovery methods.

flare.footer:
  outbound_edges:
    - { to: "docs/toons/lupo_atoms.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_atoms" }
    - { to: "config/global_atoms.yaml", type: "references", weight: 1.0, reason: "Global atoms configuration file", db_source: "lupo_atoms" }
    - { to: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.8, reason: "Content atom mappings and references", db_source: "lupo_atoms" }
    - { to: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.7, reason: "Channel configuration atoms", db_source: "lupo_atoms" }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.7, reason: "Actor configuration atoms", db_source: "lupo_atoms" }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9, reason: "FLARE protocol documentation", db_source: "lupo_atoms" }
    - { to: "scripts/flare_edge_suggester.py", type: "implements", weight: 1.0, reason: "Atom analysis and configuration automation", db_source: "lupo_atoms" }
  inbound_edges:
    - { from: "config/global_atoms.yaml", type: "references", weight: 1.0, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.8, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.7, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.7, last_seen: "20260227" }
  semantic_tags: ["atomic_configuration", "system_constants", "global_settings", "version_management", "authoritative_sources"]
  version: "4.0.47"
  last_verified: "20260227"
  last_verified_by: "windsurf"
---

# ⚛️ Table: lupo_atoms

**Purpose:** System-wide atomic configuration and constants storage for global values and settings  
**Type:** Core System Table  
**Status:** ✅ Production Ready  
**Volume:** Low (atomic configuration storage)

---

## 🎯 **Overview**

The `lupo_atoms` table serves as the atomic configuration storage system for Lupopedia, providing a centralized location for global constants, system settings, version information, and configuration values. It implements an atomic value pattern where each configuration setting is stored as an individual "atom" with support for JSON values, authoritative source tracking, and context scoping.

### **Key Responsibilities**
- **Global Constants:** Store system-wide constant values
- **Configuration Management:** Centralized configuration storage
- **Version Tracking:** System version and build information
- **Authoritative Sources:** Track configuration source and authority
- **Context Scoping:** Support for context-specific values
- **JSON Storage:** Flexible structured data storage
- **Atomic Updates:** Individual configuration value management

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`atom_id`** (BIGINT) - YYYYMMDDHHMMSS UTC timestamp, unique identifier

### **Core Atom Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `atom_name` | VARCHAR(255) NOT NULL | Unique atomic name/identifier | Must be unique within context |
| `context_id` | BIGINT NOT NULL | Context or scope identifier | For scoping values |
| `is_authoritative` | TINYINT NOT NULL | Authoritative source flag | 0 = non-authoritative, 1 = authoritative |
| `value_json` | JSON | Atomic value in JSON format | Flexible data storage |
| `summary` | TEXT | Human-readable summary | Optional description |
| `tags` | VARCHAR(255) | Categorization and discovery tags | Comma-separated |

### **Timestamp Fields**
| Field | Type | Format | Description |
|-------|------|--------|-------------|
| `created_ymd` | BIGINT | YYYYMMDD UTC | Creation date |
| `updated_ymd` | BIGINT | YYYYMMDD UTC | Last update date |

---

## 🔗 **Relationships & Dependencies**

### **Context Relationships**
- **Context:** `context_id` can reference various context sources
- **Global Context:** context_id = 0 for global atoms
- **Channel Context:** context_id = channel_id for channel-specific atoms
- **Actor Context:** context_id = actor_id for actor-specific atoms

### **Referencing Systems**
- **Global Config:** `config/global_atoms.yaml` file synchronization
- **Version System:** `lupo-includes/version.php` version information
- **Content System:** Content atom mappings in lupo_contents
- **Channel System:** Channel configuration atoms

---

## 📊 **Indexes & Performance**

### **Primary Indexes**
- **PRIMARY:** `atom_id` (unique)

### **Performance Indexes**
- **Atom Context:** `lupo_atoms_idx_atom_context` (atom_name, context_id)
- **Atom Name:** `lupo_atoms_idx_atom_name` (atom_name)
- **Authoritative:** `lupo_atoms_idx_authoritative` (is_authoritative)
- **Context ID:** `lupo_atoms_idx_context_id` (context_id)

---

## 🚀 **Usage Patterns**

### **Common Queries**

#### **Basic Atom Retrieval**
```sql
SELECT atom_id, atom_name, value_json, is_authoritative
FROM lupo_atoms 
ORDER BY atom_name;
```

#### **Global Atoms**
```sql
SELECT atom_name, value_json, summary
FROM lupo_atoms 
WHERE context_id = 0
ORDER BY atom_name;
```

#### **Authoritative Atoms**
```sql
SELECT atom_name, value_json, updated_ymd
FROM lupo_atoms 
WHERE is_authoritative = 1
ORDER BY updated_ymd DESC;
```

#### **Atoms by Context**
```sql
SELECT atom_name, value_json, is_authoritative
FROM lupo_atoms 
WHERE context_id = :context_id
ORDER BY atom_name;
```

#### **Version Information**
```sql
SELECT atom_name, value_json, updated_ymd
FROM lupo_atoms 
WHERE atom_name LIKE '%version%' 
  OR atom_name LIKE '%build%'
ORDER BY atom_name;
```

#### **Tagged Atoms**
```sql
SELECT atom_name, value_json, tags
FROM lupo_atoms 
WHERE tags LIKE '%system%' 
   OR tags LIKE '%config%'
ORDER BY atom_name;
```

---

## ⚡ **Performance Considerations**

### **High-Volume Operations**
- **INSERT:** Atom creation (low frequency)
- **UPDATE:** Value updates (moderate frequency)
- **SELECT:** Atom lookup (high frequency)
- **DELETE:** Rarely used (atoms typically persist)

### **Optimization Tips**
1. **Index atom_name** for efficient name-based lookups
2. **Use context_id** for scoping and filtering
3. **Cache global atoms** for frequent access
4. **Use is_authoritative** for source filtering
5. **Consider partitioning** by context_id for large datasets

---

## 📋 **Data Integrity**

### **Constraints**
- **Required Fields:** atom_id, atom_name, context_id
- **Unique Naming:** atom_name should be unique within context
- **JSON Validation:** Valid JSON structure for value_json
- **Date Format:** YYYYMMDD for timestamp fields

### **Validation Rules**
- **Atom Names:** Standardized naming conventions
- **Context IDs:** Valid context references
- **JSON Structure:** Valid JSON for value_json
- **Authoritative Flag:** Proper source tracking

---

## ⚛️ **Atomic Value Patterns**

### **System Constants**
```json
{
  "atom_name": "GLOBAL_CURRENT_LUPOPEDIA_VERSION",
  "value_json": "\"4.0.47\"",
  "is_authoritative": 1,
  "summary": "Current system version",
  "tags": "version,system,global"
}
```

### **Configuration Values**
```json
{
  "atom_name": "MAX_UPLOAD_SIZE_MB",
  "value_json": "50",
  "is_authoritative": 1,
  "summary": "Maximum file upload size in megabytes",
  "tags": "upload,limit,configuration"
}
```

### **Feature Flags**
```json
{
  "atom_name": "FLARE_AUTOMATION_ENABLED",
  "value_json": true,
  "is_authoritative": 1,
  "summary": "Enable FLARE automation features",
  "tags": "flare,automation,feature"
}
```

### **Build Information**
```json
{
  "atom_name": "BUILD_TIMESTAMP",
  "value_json": "20260227120000",
  "is_authoritative": 1,
  "summary": "Build timestamp in UTC",
  "tags": "build,timestamp,system"
}
```

---

## 🔧 **Common Atom Categories**

### **System Information**
- **Versions:** System version, build numbers, release dates
- **Build:** Build timestamps, environment info
- **Configuration:** System-wide settings and limits

### **Feature Flags**
- **Features:** Enable/disable system features
- **Beta:** Beta feature availability
- **Experimental:** Experimental feature controls

### **Limits & Thresholds**
- **Uploads:** File size limits, type restrictions
- **Performance:** Query limits, timeout values
- **Storage:** Storage quotas and limits

### **Integration Settings**
- **API:** API keys, endpoints, rate limits
- **External:** Third-party service configuration
- **Federation:** Multi-node settings

---

## 🔄 **Synchronization with Global Config**

### **Config File Integration**
- **Source File:** `config/global_atoms.yaml`
- **Synchronization:** Bidirectional sync with database
- **Authoritative:** Database is authoritative source
- **Backup:** YAML file serves as backup

### **Update Workflow**
1. **Config Update:** Update YAML configuration file
2. **Import:** Import changes to database
3. **Validation:** Validate atom values and structure
4. **Activation:** Activate new configuration values

---

## 🚨 **Common Issues & Solutions**

### **Performance Issues**
- **Large JSON Values:** Keep value_json reasonable size
- **Frequent Updates:** Cache frequently accessed atoms
- **Context Overhead:** Use appropriate context scoping

### **Data Consistency**
- **Duplicate Names:** Enforce uniqueness within context
- **JSON Validation:** Ensure valid JSON structure
- **Authoritative Conflicts:** Resolve conflicting authoritative values

---

## 🔮 **Future Enhancements**

### **Planned Improvements**
- **Version Control:** Track atom value history
- **Validation Rules:** Built-in value validation
- **Auto-cleanup:** Remove obsolete atoms
- **Real-time Updates:** Live configuration updates

---

## 📚 **Integration Examples**

### **Version Management**
```sql
-- Get current system version
SELECT value_json 
FROM lupo_atoms 
WHERE atom_name = 'GLOBAL_CURRENT_LUPOPEDIA_VERSION' 
  AND context_id = 0;
```

### **Feature Flag Checking**
```sql
-- Check if FLARE automation is enabled
SELECT value_json 
FROM lupo_atoms 
WHERE atom_name = 'FLARE_AUTOMATION_ENABLED' 
  AND is_authoritative = 1;
```

### **Configuration Updates**
```sql
-- Update system configuration
UPDATE lupo_atoms 
SET value_json = '100', updated_ymd = 20260227
WHERE atom_name = 'MAX_UPLOAD_SIZE_MB' 
  AND context_id = 0;
```

---

*This table documentation is part of the FLARE relationship automation initiative. For the complete database context, see the lupopedia database README and the 4.0.47 development thread.*
