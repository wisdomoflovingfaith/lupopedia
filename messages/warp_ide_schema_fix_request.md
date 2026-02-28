# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "messages\warp_ide_schema_fix_request.md"
  file_hash: "cca3e69288f7ea568edf48262bd682a670b3f1f340dc6c369b9b1712aae0635d"
  file_path_from_root: "messages\warp_ide_schema_fix_request.md"
  file_hash: "afabf95145cb8b7aa8a7c2a2039668afb1e2be8cee913084ddfc1c38a36608a1"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "WARP IDE - URGENT SCHEMA MISMATCH FIX REQUEST"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["messages", "warp_ide_schema_fix_requestmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# WARP IDE - URGENT SCHEMA MISMATCH FIX REQUEST

## Mission Brief: Lupopedia 4.0.27 Schema Compatibility Crisis

### Current Situation
I'm performing a **fresh install** of Lupopedia 4.0.27 from Crafty Syntax 3.7.5 and encountering critical SQL schema mismatch errors. The installer is failing because the seed data expects columns that don't exist in the current schema.

### What We've Already Fixed
✅ **Schema Updates Applied**: Added missing columns to `install_new_lupopedia.sql`:
- `registry_id` and `entity_index` to `lupo_registry`
- `created_by_actor_id` to `lupo_actor_channels`
- `role_key` to `lupo_actor_departments`
- `thread_id` to `lupo_dialog_threads`
- `message_id` to `lupo_dialog_messages`
- `meta_id` to `lupo_actor_meta`
- `event_id` to `lupo_system_events`
- `content` to `lupo_contents`

✅ **INSERT Statements Fixed**: Updated INSERT statements in `install_new_lupopedia.sql` to use correct column names

### Remaining Critical Issues
The installer is still failing with these errors:

**1. Registry Column Mismatches (Most Critical)**
```
Unknown column 'registry_id' in 'field list'
Unknown column 'entity_key' in 'field list'
Unknown column 'entity_index' in 'field list'
```

**2. Actor Schema Issues**
```
Column count doesn't match value count at row 1 (lupo_actors)
```

**3. Missing Tables**
```
Table 'lupopedia.lupo_anubis_log' doesn't exist
```

### Your Mission: Fix the Schema Mismatch

**Primary Objective**: Ensure `seed_lupopedia.sql` can run successfully against the schema defined in `install_new_lupopedia.sql`.

**Required Actions**:

1. **Analyze the Current Schema**: Compare `install_new_lupopedia.sql` table definitions with what `seed_lupopedia.sql` expects

2. **Fix Registry Table Issues**: The seed file expects:
   - `registry_id` column (we added this but INSERT statements still fail)
   - `entity_key` column (missing from schema)
   - `entity_index` column (we added this but queries still fail)

3. **Fix Actor Table Issues**: Column count mismatch suggests missing columns in `lupo_actors` table

4. **Handle Missing Tables**: Either create `lupo_anubis_log` table or remove references from seed file

5. **Fix WHERE Clause Issues**: Several DELETE/UPDATE statements reference `entity_index` column that may not exist

### Key Files to Examine
- `database/migrations/install_new_lupopedia.sql` - Schema definitions
- `database/migrations/seed_lupopedia.sql` - Seed data that's failing

### Critical Context
- **Version**: Lupopedia 4.0.27
- **Upgrade Path**: Crafty Syntax 3.7.5 → Lupopedia 4.0.27
- **Environment**: Fresh install, not upgrade
- **Database**: MySQL 8.0+ / MariaDB 10.5+ / PostgreSQL compatible

### Success Criteria
✅ Installer completes without SQL errors  
✅ All seed data loads successfully  
✅ Crafty Syntax 3.7.5 → Lupopedia 4.0.27 upgrade path works  
✅ No "Column not found" or "Column count mismatch" errors  

### Urgency
**HIGH PRIORITY** - This is blocking the entire 4.0.27 testing cycle. The schema must be fixed before any further development can proceed.

### Technical Notes
- Follow Lupopedia database doctrine (no foreign keys, triggers, stored procedures)
- Use BIGINT timestamps in YYYYMMDDHHIISS format
- Maintain soft delete patterns (`is_deleted`, `deleted_ymdhis`)
- All changes must be compatible with MySQL, MariaDB, and PostgreSQL

### Next Steps After Fix
Once schema is fixed, I need to:
1. Complete the fresh install testing
2. Verify Crafty Syntax 3.7.5 → Lupopedia 4.0.27 upgrade
3. Test all installer wizard functionality
4. Validate database integrity

**Warp IDE, please analyze the schema mismatch and provide the necessary fixes to make the installer work. This is critical for the 4.0.27 release.**