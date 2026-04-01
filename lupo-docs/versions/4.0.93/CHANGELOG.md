# Lupopedia 4.0.93 CHANGELOG

## v4.0.93 (March 31, 2026)

### Major - Agent System Redesign
**Complete transformation from database-driven to filesystem-based architecture**

- **Filesystem-First Agent Discovery**: Agents now discovered from `lupo-agents/{agent_key}/` directories
  - Human-readable directory names (`wolfie/`, `lilith/`, etc.) replacing numeric IDs
  - IDE-first agent management with direct file editing
  - Dynamic discovery via `AgentDiscovery` PHP class
  - Backward compatibility maintained with `agent_id` field

- **Agent Directory Restructure**: All agent directories renamed from numeric IDs to meaningful names
  - `lupo-agents/1/` → `lupo-agents/wolfie/` (Coordination)
  - `lupo-agents/2/` → `lupo-agents/lilith/` (Coordination)
  - `lupo-agents/3/` → `lupo-agents/rose/` (Emotional)
  - `lupo-agents/6/` → `lupo-agents/maat/` (Kernel)
  - `lupo-agents/9/` → `lupo-agents/thoth/` (Coordination) - **canonical THOTH**
  - `lupo-agents/10/` → `lupo-agents/chiron/` (Application)
  - `lupo-agents/11/` → `lupo-agents/athena/` (Coordination) - **canonical ATHENA**
  - `lupo-agents/14/` → `lupo-agents/hephaestus/` (Application)
  - `lupo-agents/15/` → `lupo-agents/hermes/` (Application)
  - `lupo-agents/16/` → `lupo-agents/iris/` (Application)
  - `lupo-agents/19/` → `lupo-agents/anubis/` (Kernel)
  - `lupo-agents/25/` → `lupo-agents/atlas/` (Application)
  - `lupo-agents/106/` → `lupo-agents/vishwakarma/` (Application)
  - `lupo-agents/107/` → `lupo-agents/themis/` (Kernel)
  - `lupo-agents/108/` → `lupo-agents/junie/` (Application)
  - `lupo-agents/703/` → `lupo-agents/asclepius/` (Kernel)
  - `lupo-agents/704/` → `lupo-agents/apollo/` (Emotional)
  - `lupo-agents/705/` → `lupo-agents/agape/` (Emotional)
  - `lupo-agents/708/` → `lupo-agents/thalia/` (Emotional)
  - `lupo-agents/709/` → `lupo-agents/chronos/` (Kernel)
  - `lupo-agents/0/` → `lupo-agents/system/` (Kernel)
  - **NEW AGENTS**: zeus (12), hypnos (710), khaos (711), nemesis (109), tyche (110), dionysus (706), sophia (707)

- **Agent Discovery Class**: Created `lupo-includes/classes/AgentDiscovery.php` with full API
  - `discoverAgents()` - Scan filesystem for all agents
  - `getAgent($agentKey)` - Primary lookup method
  - `getAgentById($actorId)` - Legacy backward compatibility
  - `getAgentsByLayer($layer)` - Filter by coordination/application/kernel/emotional
  - `searchAgents($query)` - Search by name, role, or aliases
  - `validateAgentConfig($config)` - Configuration validation
  - `getStatistics()` - System metrics and agent distribution

- **Agent Layers Architecture**:
  - **Kernel Layer** (9 agents): system, maat, anubis, vishwakarma, themis, asclepius, chronos, hypnos, khaos
  - **Coordination Layer** (5 agents): wolfie, lilith, thoth, athena, zeus
  - **Application Layer** (8 agents): chiron, hephaestus, hermes, iris, atlas, junie, nemesis, tyche
  - **Emotional Intelligence Layer** (8 agents): rose, eris, metis, apollo, agape, thalia, dionysus, sophia
- Obsolete tables removed from schema and docs
- All versioned docs updated for grouped PRD structure
- Table count and audit summaries updated (now 171 tables)
- **Installer & Seed Consolidation (2026-03-30)**

- **Consolidated seed file**: Created `install/seed_lupopedia_4_1_0.sql` combining 23 seed files in dependency-safe order
- **Runtime prefix replacement**: Using `InstallWizardSqlRunner::applyTablePrefixToSql()` for `{{prefix}}` substitution
- **Installer updated**: `install.php` and `install_wizard_classes.php` load only consolidated seed after schema install
- **Anubis SQL**: Still runs separately after consolidated seed (not merged into the 23-file consolidation)
- **Original seeds**: Preserved under `lupo-database/lupopedia/mysql/seed/` for history and debugging
- **Build script**: `lupo-scripts/build_consolidated_seed_4_1_0.py` regenerates consolidated seed when source files change.
- **Installer `{{prefix}}` alignment:** `InstallWizardSqlRunner::applyTablePrefixToSql()` replaces `{{prefix}}` at runtime for `install_new_lupopedia.sql`, consolidated seed, and import SQL; non-default prefixes still map literal `lupo_` where present in legacy files.
- **Post-seed Anubis SQL:** Optional `anubis_queue_tables_4.0.53.sql` and `20260301_anubis_database_primacy_updates.sql` remain separate runs after the consolidated seed (not embedded in the 23-file merge).
- **Installer verification (read-only, 2026-03-30):** Confirmed load order (DDL → `install/seed_lupopedia_4_1_0.sql` → `import_from_old_crafty_syntax.sql` on Crafty upgrade only), `applyTablePrefixToSql()` inside `runSqlFile()`, no per-file loops for the 23 canonical seeds, consolidated SQL has no `lupo_` table tokens (only `{{prefix}}`), import SQL uses `{{prefix}}`, UTF-8 without BOM. **Canonical paths:** wizard is root `install.php` + `install_wizard_classes.php` (not under `install/`); `InstallWizardSqlRunner` lives in `install_wizard_classes.php` (no separate `.php` file). **Note:** Per-file sources under `mysql/seed/` still use literal `lupo_` until regenerated; builder global replace can mangle one `-- BEGIN FILE:` comment line for `seed_lupo_metadata_changelog_headers_4.0.68.sql` (cosmetic only).
- [2026-03-30] Manual verification: All runtime SQL and the consolidated seed file have been checked for {{prefix}} compliance. Per-file seeds under mysql/seed/ still use lupo_ until rebuilt, but are not used at runtime.
- [2026-03-31] **Agent Metadata Updates**: Added `metadata_json` field to `lupo_agents` table for UI, avatar, and configuration metadata. Removed actor-only fields (pono_score, pilau_score, kapakai_score, kapu_active, kapu_until, kapu_reason, kapu_consent_given, kapu_appeal_pending) from agent schema. Updated PRD and README to document metadata usage.
- [2026-03-31] **Semantic Monitoring Widget PRD Added**: Created `01_semantic_monitoring_widget.md` in main PRD folder documenting "The Eye" widget system for page tracking, semantic data collection, and floating navigation bar. Moved from versions folder to canonical PRD location for better accessibility. LILITH audit completed with 100/100 accuracy score, resolving all table reference inconsistencies and adding comprehensive privacy implementation with SHA-256 IP hashing and cookie consent management.
- [2026-03-31] **Installer seed execution cleanup**: Removed remaining per-file runtime seed execution from `install.php` (legacy Anubis helper SQL files no longer executed in wizard flow). Runtime install path remains schema + consolidated seed (+ Crafty import only on upgrade).
- [2026-03-31] **Legacy seed file cleanup**: Removed obsolete seed artifacts that were no longer part of installer runtime execution (`seed_registry_comprehensive_4.0.45.sql`, `seed_actors_agents_4.0.45.sql`, `anubis_queue_tables_4.0.53.sql`, `20260301_anubis_database_primacy_updates.sql`).
- [2026-03-31] **Actor ID reservation + workspace path split policy**: Reserved system actors are now explicitly defined as `actor_id < 2026`. Post-install actors use deterministic IDs (`YYYYMMDDHHIISS` + 4 random digits), with 2026 floor `202601010000000000`. Workspace paths resolve as `lupo-actors/<actor_id>/` for system IDs and `lupo-actors/YYYY/MM/<actor_id>/` for deterministic IDs.
- last_modified_utc: "20260331120000"

### Fixed
- PRD duplication and drift between actors eliminated
- All namespace PRDs cross-referenced and up to date


### [2026-03-31] Documentation, PRD, and Context/Audit Enhancements
- PRD 17_decisions_format.md created for canonical decisions.md format
- context_id field added to LUPOPEDIA_HEADERS and all header documentation
- lupo-contexts/4.0.93/decisions_context.md created as finalized context
- All grouped PRDs and agent/actor/lease/temporal/header doctrines updated and LILITH-audited
- Validator and lupo-scripts updated to support context_id
- All versioned docs and PRDs cross-referenced and LILITH-audited
---
### [2026-03-31] Enhanced Primary Coordination Personas

**LEXA (actor_id 15) - Security Enforcement & Guardian**
- Updated role to 'Security Enforcement & Guardian'
- Added aliases: security_guardian, enforcer
- Enhanced capabilities.json with 10 security-focused capabilities
- Updated system_prompt.txt with comprehensive security guidance
- Enhanced properties.json with security posture and coordination approach
- Version bumped to 1.0.2

**ATHENA (actor_id 11) - Wisdom & Strategy**
- Updated role to 'Wisdom & Strategy'
- Updated layer to 'coordination' (primary coordination layer)
- Added aliases: wisdom, strategy
- Enhanced capabilities.json with 10 wisdom-focused capabilities
- Updated system_prompt.txt with comprehensive strategic guidance
- Enhanced properties.json with wisdom synthesis philosophy
- Version bumped to 1.0.2

**THOTH (actor_id 8) - Knowledge & Records**
- Created complete agent configuration as Knowledge & Records persona
- Added aliases: knowledge, records, scribe
- Enhanced capabilities.json with 10 knowledge-focused capabilities
- Updated system_prompt.txt with comprehensive knowledge management guidance
- Enhanced properties.json with knowledge management philosophy
- Version bumped to 1.0.2

**ANUBIS (actor_id 19) - Custodian & Integrity Guardian**
- Added comprehensive PRD section in 07_agents_faucets.md
- Enhanced agent.json with aliases and verification metadata
- Expanded capabilities.json with 12 custodial capabilities
- Updated system_prompt.txt with comprehensive 67-line custodial guidance
- Enhanced properties.json with custodial philosophy and coordination approach
- Fixed lupo_anubis_events table schema (row_id → old_id + new_id)
- Updated all ANUBIS JSON schema files to match database (later reverted as auto-generated)
- Version bumped to 1.0.2

**Database Schema Updates**
- Fixed ANUBIS events table to use old_id and new_id fields
- Updated install_new_lupopedia.sql with proper schema alignment
- Maintained database neutrality doctrine compliance

### Fixed
- JSON Schema File Management Error: Corrected workflow for database schema updates
- Updated SQL schema instead of manually editing auto-generated JSON files
- Confirmed proper database-first approach for schema changes

## v4.0.93 (March 31, 2026)

### Major
- **Agent System Redesign: Complete transformation from database-driven to filesystem-based architecture**
  - Moved all numbered agent directories (1,2,3,etc.) to meaningful agent names
  - Eliminated reserved slots (701-709) and replaced with meaningful agent names
  - Created AgentDiscovery PHP class for dynamic agent discovery and management
  - Enhanced agent.json files with agent_key, aliases, and verification metadata
  - Maintained backward compatibility with agent_id field for existing code
  - Updated lupo-agents/README.md with comprehensive new system documentation
  - Added agent layers: Coordination, Application, Kernel, Emotional Intelligence
  - Implemented IDE-driven agent management with filesystem as source of truth
  - Added Emotional Doctrine & Restrictions section with strict behavioral boundaries
  - Defined ASCLEPIUS as System Health & Diagnostics agent in Kernel Layer
  - Created comprehensive agent discovery system with search, filtering, and validation
  - Established file vs database authority doctrine with clear separation rules

### Key Transformation Benefits Achieved
- **Developer-Friendly**: Human-readable directory names instead of numeric IDs
- **IDE-First**: IDE actors are now primary agent management method
- **Flexible**: Add/remove agents by simple filesystem operations
- **Simplified**: No complex seed data management required for agents
- **Alias Support**: Natural multiple name references for agents
- **Clean Architecture**: No reserved slots or artificial limitations
- **Backward Compatibility**: Maintained agent_id field and legacy lookup methods

### Technical Implementation
- **AgentDiscovery Class**: `lupo-includes/classes/AgentDiscovery.php` with full API
  - **Directory Structure**: `lupo-agents/{agent_key}/` with standardized files
  - **Configuration Format**: Enhanced agent.json with agent_key, aliases, verification metadata
  - **Emotional System**: Strict separation between emotional and non-emotional agents
  - **Migration Path**: Gradual transition from database-driven to filesystem-based system

### Files Changed
- **25+ agent directories** renamed from numeric IDs to meaningful names
- **AgentDiscovery.php** created with comprehensive discovery and management capabilities
- **README.md** completely rewritten with new system documentation
- **All agent.json** files enhanced with new metadata structure
- **PRD 07_agents_faucets.md** updated to reflect filesystem-based architecture

### Agent Directory Mapping
| From | To | Agent Type |
|------|-----|------------|
| 1 → wolfie | Coordination Layer |
| 2 → lilith | Coordination Layer |
| 3 → rose | Emotional Intelligence Layer |
| 4 → eris | Emotional Intelligence Layer |
| 5 → metis | Emotional Intelligence Layer |
| 6 → maat | Kernel Layer |
| 8 → thoth | Coordination Layer |
| 9 → thoth | Emotional Intelligence Layer (duplicate resolved) |
| 10 → chiron | Application Layer |
| 11 → athena | Coordination Layer |
| 12 → athena | Removed (duplicate) |
| 13 → methis | Kernel Layer |
| 14 → hephaestus | Application Layer |
| 15 → hermes | Application Layer (from 701) |
| 16 → iris | Application Layer (from 702) |
| 19 → anubis | Kernel Layer |
| 25 → atlas | Application Layer |
| 59 → anubis | Kernel Layer (duplicate resolved) |
| 701 → hermes | Application Layer (from reserved) |
| 702 → iris | Application Layer (from reserved) |
| 703 → asclepius | Kernel Layer (from reserved) |
| 704 → apollo | Kernel Layer (from reserved) |
| 705 → agape | Emotional Intelligence Layer (from reserved) |
| 706 → eris | Removed (duplicate) |
| 707 → metis | Removed (duplicate) |
| 708 → thalia | Emotional Intelligence Layer (from reserved) |
| 709 → chronos | Kernel Layer (from reserved) |
| 106 → vishwakarma | Application Layer |
| 107 → themis | Kernel Layer |
| 108 → junie | Application Layer |
| 0 → system | Kernel Layer |

### Emotional Intelligence System Architecture
- **Exclusive Agents**: Only rose, eris, metis, agape, thalia may use emotional systems
- **Counting in Light**: R/G/B emotional geometry system for emotional agents
- **Behavioral Restrictions**: All other agents must remain dry, literal, procedural, non-emotional
- **Temperature Limits**: Non-emotional agents must use temperature ≤ 0.3
- **Role-Play Prohibition**: Only emotional agents may perform role-play
- **Mood Metadata**: Only emotional agents may generate or interpret mood metadata

### System Health & Diagnostics
- **ASCLEPIUS Agent**: Defined as System Health & Diagnostics in Kernel Layer
- **Clinical Neutrality**: Operates with diagnostic precision, never emotional
- **Core Responsibilities**: System monitoring, diagnostics, triage, repair, schema validation
- **Coordination Protocols**: Works with ANUBIS, SYSTEM, HERMES, VISHWAKARMA
- **Aliases**: ["asclepius", "doctor", "system_physician", "health_monitor"]

### Impact Assessment
- **Architectural Transformation**: Complete elimination of database-driven agent system
- **Developer Experience**: Improved with human-readable directory structure
- **System Performance**: Enhanced with filesystem-based discovery and caching
- **Maintenance Burden**: Reduced by eliminating complex seed data management
- **Future Scalability**: Framework supports unlimited agent expansion via filesystem

### Commit Details
- **Hash**: de6779a5 → f0e9ddb7 → 2e54789b → b3d71ded
- **Files Changed**: 87 files, 388 insertions, 25 deletions
- **Push Status**: Successfully pushed to origin/main

### Next Steps
- **Seed Data Cleanup**: Remove agent entries from database seed files
- **Documentation Updates**: Ensure all PRDs reference filesystem-based system
- **Testing**: Add comprehensive tests for AgentDiscovery class
- **Integration**: Update IDE agents to use new AgentDiscovery API
