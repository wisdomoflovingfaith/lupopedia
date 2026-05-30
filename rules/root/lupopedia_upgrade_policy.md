---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: rules/root/lupopedia_upgrade_policy.md
  web_path: https://www.lupopedia.com/lupopedia/rules/root/lupopedia_upgrade_policy.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: rules
  artifact_kind: policy
  channel_key: null
  federation_node_id: null
  thread_key: 4.0.89-upgrade-policy
  lupopedia.schema: rules
  prd_cluster: null
  title: null
  summary: null
---

# Lupopedia Upgrade Policy

## 🚫 NO LUPOPEDIA TO LUPOPEDIA UPGRADES UNTIL 4.1.0

**RULE**: Direct upgrades between Lupopedia 4.0.x versions are FORBIDDEN.

### WHY THIS EXISTS
- Prevents database corruption from partial migrations
- Ensures clean state for each version
- Eliminates upgrade complexity until major version
- Maintains system stability and integrity

### ✅ APPROVED UPGRADE PATHS

#### 1. New Installations
- **Path**: Fresh install via `/install.php`
- **Method**: Complete database creation
- **Result**: Clean 4.0.89 installation

#### 2. Crafty Syntax 3.7.5 → Lupopedia 4.0.x
- **Path**: Upgrade from legacy system
- **Method**: Migration script in `tmp/`
- **Result**: Clean 4.0.x installation with imported data

#### 3. Lupopedia 4.0.x → Lupopedia 4.1.0 (Future)
- **Path**: Major version upgrade (when available)
- **Method**: Dedicated upgrade script
- **Result**: Clean 4.1.0 installation

### 🛡️ MIGRATION POLICY

#### One-Time Changes Only
- All migrations are one-off changes
- Run via `tmp/run_*.php` scripts
- Admin access required
- PHP-based execution only

#### Install SQL Requirements
- ALL SQL changes MUST be applied to `install_new_lupopedia.sql`
- Install SQL represents the complete current schema
- No incremental SQL files for 4.0.x versions
- Single source of truth for database structure

### 📁 FILE ORGANIZATION

#### Migration Scripts
```
tmp/
+-- run_crafty_to_lupopedia_4.0.89.php  # Legacy upgrade
+-- run_*.php                           # One-time migrations
```

#### Install Schema
```
database/lupopedia/mysql/install/
+-- install_new_lupopedia.sql           # Complete 4.0.89 schema
```

#### Seed Data
```
database/lupopedia/mysql/seed/
+-- seed_4.1.0.sql                      # All seeds for 4.1.0+
+-- seed_departments.sql                # Department setup
+-- seed_primary_coordination_personas_4.0.89.sql
```

### 🔄 SEED SIMPLIFICATION

#### Current Problem
- Multiple version-specific seed files
- Complex version tracking in install.php
- Unnecessary complexity for 4.0.x

#### Solution
- Consolidate all seeds into `seed_4.1.0.sql`
- Mark as preparation for 4.1.0
- Simplify install.php logic
- Single seed execution

### ⚠️ VIOLATION CONSEQUENCES

- Database corruption
- System instability
- Data loss
- Support complications

### 🎯 IMPLEMENTATION PLAN

1. **Consolidate Seeds**
   - Merge all seed files into `seed_4.1.0.sql`
   - Update install.php to use consolidated seed
   - Remove version-specific seed logic

2. **Document Policy**
   - Add to root rules
   - Update installation documentation
   - Communicate to all agents

3. **Enforce Policy**
   - Validate no 4.0.x upgrade scripts
   - Ensure all changes in install SQL
   - Monitor compliance

---

**APPROVED BY**: WOLFIE (actor_id 1)
**ENFORCED BY**: ANUBIS (actor_id 19)
**EFFECTIVE**: 2026-03-28

**REMEMBER**: Fresh installs or legacy upgrades only - NO Lupopedia 4.0.x upgrades!
