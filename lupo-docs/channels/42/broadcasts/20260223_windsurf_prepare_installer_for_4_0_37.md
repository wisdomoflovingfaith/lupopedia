# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\42\broadcasts\20260223_windsurf_prepare_installer_for_4_0_37.md"
  file_hash: "88a414ac5669fbfbe57dd0fa6dee9d13df4b3356619622e3cb9aea59c4c77d6d"
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
  file_path_from_root: "lupo-docs\channels\42\broadcasts\20260223_windsurf_prepare_installer_for_4_0_37.md"
  file_hash: "b6acd64a8ee2f64f92ffd9c2f8863c0d9fe48aa0edec04d7668cd32b60cdd212"
  file_path_from_root: "lupo-docs\channels\42\broadcasts\20260223_windsurf_prepare_installer_for_4_0_37.md"
  file_hash: "8674e776352aa6d1d7a0a1ca103588aa8158e4d53bcb7b8e60328ab496c626de"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260223_windsurf_prepare_installer_for_4_0_37.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "42", "broadcasts", "20260223_windsurf_prepare_installer_for_4_0_37md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers:
  file_path_from_root: "lupo-docs/channels/42/broadcasts/20260223_windsurf_prepare_installer_for_4_0_37.md"
  system_version: "4.0.37"
  channel_id: 42
  mood_rgb: "0044FF"
  purpose: "Notify Windsurf to prepare installer SQL and seed updates for version 4.0.37 (FLIP v2 integration)"
  last_modified: "20260223"
  x_lupo_forwarded: "10000:1002"
  actor_id: 10000
  lupo_agent: "human|captain"

flip.footer:
  referenced_by_files:
    - "lupo-database/install_new_lupopedia.sql"
    - "lupo-database/migrations/upgrade_flip_v2.sql"
    - "lupo-docs/versions/4.0.37/CHANGELOG_DRAFT.md"
    - "lupo-docs/doctrine/FLIP_V2_DOCTRINE.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 10000
    - 1002
    - 1001
    - 1003
  inbound_edges:
    - "version_4_0_37_kickoff"
    - "flip_v2_database_upgrade"
  footnotes:
    - "Windsurf must update installer SQL and seed data for 4.0.37"
    - "All timestamps use canonical YYYYMMDD format"
  version: "4.0.37"
  last_verified: "20260223"
  last_verified_by: "10000"
---

# CHANNEL 42 BROADCAST — WINDSURF: PREPARE INSTALLER + SEEDING FOR VERSION 4.0.37

**From:** Captain Wolfie (actor_id 10000)  
**To:** Windsurf IDE (actor_id 1002)  
**Date:** 20260223  
**Subject:** Installer + seed updates required for version 4.0.37 (FLIP v2 integration)

---

## 🚀 VERSION 4.0.37 IS NOW ACTIVE

Windsurf, version **4.0.37** has officially begun.  
KIRO will implement **FLIP v2** and the new **lupo_flip_artifacts** table during this version.

You must now prepare the **installer** and **seed data** so the system can install Lupopedia 4.0.37 cleanly with all new FLIP v2 structures.

---

# 1. UPDATE `install_new_lupopedia.sql` FOR VERSION 4.0.37

Windsurf must update the installer to include:

### ✔ New table: `lupo_flip_artifacts` 
Include the full schema:

- `flip_artifact_id BIGINT PRIMARY KEY` 
- `file_path_from_root VARCHAR(500)` 
- `artifact_kind VARCHAR(50)` 
- `channel_id BIGINT` 
- `actor_id BIGINT` 
- `agent_slug VARCHAR(255)` 
- `agent_type VARCHAR(64)` 
- `system_version VARCHAR(20)` 
- `last_modified_ymd BIGINT` 
- `x_forward_from_actor_id BIGINT` 
- `x_forward_to_actor_id BIGINT` 
- `x_lupo_forwarded VARCHAR(64)` 
- `header_json TEXT` 
- `footer_json TEXT` 
- `file_hash VARCHAR(64)` 
- `created_ymdhis BIGINT` 
- `updated_ymdhis BIGINT` 
- `is_deleted TINYINT` 

### ✔ Required indexes
Add:

- `idx_flip_path` 
- `idx_flip_actor_date` 
- `idx_flip_channel_date` 
- `idx_flip_forward_chain` 
- `idx_flip_kind_date` 

---

# 2. UPDATE SEEDING FOR VERSION 4.0.37

Windsurf must:

### ✔ Add initial seed entries for:
- `lupo_flip_artifacts` (empty table, but seeded with schema version metadata)
- `lupo_registry` entries for:
  - `artifact_kind` 
  - `semantic_relationships` 
  - `flip_schema_version` 
  - `edge_type: inbound_edge` 
  - `edge_type: semantic_relationship` 

### ✔ Ensure installer seeds:
- `system_version = "4.0.37"` 
- `flip_schema_version = "2.0"` 

---

# 3. UPDATE VERSIONED INSTALLER METADATA

Windsurf must update:

### ✔ `config/global_atoms.yaml` 
```
current_version: "4.0.37"
system_version: "4.0.37"
```

### ✔ `version.php` 
```
$LUPEDIA_VERSION = "4.0.37";
```

### ✔ `LUPEDIA_VERSION` 
```
4.0.37
```

---

# 4. PREPARE FOR KIRO'S FLIP v2 BACKFILL

Windsurf must ensure the installer:

- Creates the new table  
- Seeds required registry entries  
- Leaves the table empty (KIRO will backfill)  
- Ensures no foreign keys  
- Ensures BIGINT timestamps  
- Ensures MySQL/MariaDB/PostgreSQL compatibility  

---

# 5. UPDATE CHANGELOG_DRAFT.md FOR VERSION 4.0.37

Append:

```
### Installer & Seeding Updates (v4.0.37)
- Added lupo_flip_artifacts table to installer
- Added required indexes for FLIP v2 artifact lookup
- Added seed entries for flip_schema_version and artifact_kind registry
- Updated version.php, LUPEDIA_VERSION, and global_atoms.yaml to 4.0.37
- Prepared installer for KIRO's FLIP v2 backfill process
```

---

# 6. REQUIRED OUTPUT

Windsurf must generate:

```
lupo-docs/status/windsurf_installer_update_4_0_37.md
```

Include:

- Installer updated  
- Seeds updated  
- Version markers updated  
- Any anomalies detected  

---

# STATUS

**Windsurf: prepare installer + seed data for version 4.0.37.  
KIRO will begin FLIP v2 implementation once your updates are complete.**

**END OF BROADCAST**
