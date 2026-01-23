# LupoPedia Database Migration Guide

## Overview
This migration package creates all 183 tables defined in the TOON schema files in `database/toon_data/`. The migration is **HERITAGE-SAFE** - additive only, non-destructive.

## Migration Files

### Core Migration Files (Execute in Order)
1. **migration_part1_core.sql** - Core system tables (auth, actors, sessions)
2. **migration_part2_agents.sql** - Agent & AI tables  
3. **migration_part3_channels.sql** - Channel & communication tables
4. **migration_part4_content.sql** - Content & collections tables
5. **migration_part5_actors_relations.sql** - Actor relationship tables
6. **migration_part6_system_governance.sql** - System & governance tables
7. **migration_part7_remaining.sql** - Analytics, API, migration tables, unified tables

### Optional Enhancement Files
- **migration_alters.sql** - Future-proofing ALTER statements (review before running)

## Execution Instructions

### Step 1: Run Core Tables
```sql
-- Execute in order
SOURCE migration_part1_core.sql;
SOURCE migration_part2_agents.sql;
SOURCE migration_part3_channels.sql;
SOURCE migration_part4_content.sql;
SOURCE migration_part5_actors_relations.sql;
SOURCE migration_part6_system_governance.sql;
SOURCE migration_part7_remaining.sql;
```

### Step 2: Review Optional ALTERs
Open `migration_alters.sql` and:
1. Review all ALTER statements
2. Uncomment only the ones you need
3. Remove foreign key constraints if not wanted
4. Execute the modified file

## Safety Features

✅ **CREATE TABLE IF NOT EXISTS** - Safe to run multiple times  
✅ **No DROP statements** - Won't destroy existing data  
✅ **No MODIFY COLUMN** - Won't change existing columns  
✅ **Based only on TOON schema** - Authoritative source of truth  
✅ **All indexes included** - Performance optimized  

## Table Categories Created

- **13 Core System Tables** - auth, audit, config, sessions
- **15 Agent & AI Tables** - agents, registry, properties, capabilities
- **25 Channel & Communication Tables** - channels, dialogs, messages
- **22 Content & Collections Tables** - contents, collections, tabs
- **20 Actor Relationship Tables** - actions, edges, conflicts, groups
- **15 Governance & Truth Tables** - gov events, truth items, doctrine
- **12 Semantic & Search Tables** - search, semantic, tags
- **12 Analytics & Metrics Tables** - visits, referers, campaigns
- **10 API & Integration Tables** - API clients, federation, webhooks
- **10 Context & Memory Tables** - contexts, memory, events
- **15 Specialized Tables** - modules, permissions, help, CRM
- **9 Migration System Tables** - migration tracking and validation
- **3 Duplicate/Unified Tables** - unified versions of core tables

**Total: 183 tables**

## Verification

After migration, verify:
```sql
-- Count tables
SELECT COUNT(*) FROM information_schema.tables 
WHERE table_schema = 'your_database_name';

-- Check key tables exist
SHOW TABLES LIKE 'lupo_%';
SHOW TABLES LIKE 'auth_%';
SHOW TABLES LIKE 'migration_%';
```

## Notes

- All tables use InnoDB engine with utf8mb4 charset
- Primary keys and indexes match TOON schema exactly
- JSON columns used where specified in TOON files
- Foreign key constraints are optional (commented out)
- Performance indexes are optional (commented out)

## Troubleshooting

If you encounter errors:
1. Check MySQL version (JSON columns require 5.7+)
2. Verify database charset is utf8mb4
3. Ensure sufficient disk space for 183 tables
4. Review specific error messages for table conflicts

## Heritage Compliance

This migration preserves all existing data and adds new capabilities without breaking changes, following LupoPedia's heritage-safe development principles.
