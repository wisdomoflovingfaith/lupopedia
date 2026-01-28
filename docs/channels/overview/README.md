---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.18
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_DEFAULT_STATUS
  - GLOBAL_PROJECT_CRAFTY_SYNTAX
  - GLOBAL_COLLECTION_CORE_DOCS
  - GLOBAL_CHANNEL_DEV
  - GLOBAL_CHANNEL_PUBLIC
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created new documentation index after reorganizing all docs into stable OS-level structure (core, modules, doctrine, agents, schema, dev, protocols, history, appendix)."
tags:
  categories: ["documentation", "index"]
  collections: [GLOBAL_COLLECTION_CORE_DOCS]
  channels: [GLOBAL_CHANNEL_DEV, GLOBAL_CHANNEL_PUBLIC]
file:
  title: "Lupopedia Documentation Index"
  description: "Complete index of all Lupopedia documentation organized by category"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: GLOBAL_DEFAULT_STATUS
  author: "Captain Wolfie"
---

# ðŸ“š Lupopedia Documentation Index

**ðŸŸ© Short Definition:**  
Lupopedia is a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) that hosts agents, content, emotional metadata, and routing logic across independent nodes, each functioning as a selfâ€‘contained knowledge world governed by shared doctrine.

**WOLFIE Acronym:** WOLFIE now stands for **Web-Organized Linked Federated Intelligent Ecosystem** â€” the architecture type that defines Lupopedia's distributed, multi-agent knowledge system. WOLFIE originally meant **Wisdom Of Loving Faith Integrity Ethics** when the project began in August 2025 as a spiritual research engine, but was repurposed to reflect the technical architecture as the system evolved into Lupopedia.

**Key Clarification:** Lupopedia is NOT a CMS â€” it is a semantic reference layer installed in `/lupopedia/` that creates reference-book entries describing pages on the host website, not stored CMS content. The system works alongside, not instead of, the host website's existing CMS or routing.

This directory contains all Lupopedia documentation organized by category. This structure reflects Lupopedia's architecture as a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE). For complete formal definitions, see [DEFINITION.md](DEFINITION.md).

---

## ðŸ”¬ **Documentation Uses Atoms (Not Hardcoded Values)**

**âš ï¸ CRITICAL:** All Lupopedia documentation uses **atoms** (symbolic variables) instead of hardcoded values. Documentation is **machine-readable system metadata** written for the resolver, semantic OS, and agents first; humans are secondary consumers.

### **Atom Scopes (Resolution Order: First Match Wins)**

1. **FILE_** (Highest Priority) â€” File-specific overrides in WOLFIE Header `file_atoms:` block
2. **DIR_** â€” Directory-specific defaults in `<directory>/_dir_atoms.yaml`
3. **DIRR_** â€” Recursive directory scope (current directory + all descendants), walks up parent directories
4. **MODULE_** â€” Module-wide scope in `modules/<module>/module_atoms.yaml`
5. **GLOBAL_** (Final Fallback) â€” Ecosystem-wide constants in `config/global_atoms.yaml`

### **Atom Reference Syntax**

- **In documentation prose:** `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.name`
- **In WOLFIE Headers:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` (no `@` prefix)

### **Documentation Principles**

- **Markdown files are source code** â€” atoms are variables; resolver is compiler; final rendered docs are build artifacts
- **No hardcoded values** â€” all versions, authors, and constants use atom references
- **Deterministic and idempotent** â€” same input â†’ same output, resolving twice produces same result
- **Machine-first** â€” written for resolver, semantic OS, and agents; humans are secondary consumers
- **All AI systems must read documentation with atoms and scopes**, not as plain text

**See:** [Atom Resolution Specification](../doctrine/ATOM_RESOLUTION_SPECIFICATION.md) for complete atom resolution engine specification.  
**See:** [Documentation Doctrine](../doctrine/DOCUMENTATION_DOCTRINE.md) for documentation-as-code principles.

---

---

## ðŸ—ï¸ Core Documentation (`core/`)

**Core architecture and design principles:**

- **[DEFINITION.md](DEFINITION.md)** â€” Formal, short, and KISS definitions of Lupopedia
- **[ARCHITECTURE.md](../architecture/ARCHITECTURE.md)** â€” Technical architecture (4 layers: Content, Semantic, AI Agent Framework, Decentralized Network)
- **[ARCHITECTURE_SYNC.md](../architecture/ARCHITECTURE_SYNC.md)** â€” Authoritative reference for HERMES (routing), CADUCEUS (emotional balancing), IRIS, DialogManager, and THOTH subsystems
- **[DATABASE_PHILOSOPHY.md](../architecture/DATABASE_PHILOSOPHY.md)** â€” Database design principles: application logic first, database logic second
- **[WHY_NO_FRAMEWORKS.md](../doctrine/WHY_NO_FRAMEWORKS.md)** â€” Philosophy and reasoning behind framework-free, first-principles architecture
- **[WHY_LUPOPEDIA_NEEDS_CRAFTY_SYNTAX.md](../architecture/WHY_LUPOPEDIA_NEEDS_CRAFTY_SYNTAX.md)** â€” Why Crafty Syntax is not legacy but the operational backbone that makes Lupopedia a working system, not just theory
- **[WHY_LUPOPEDIA_IS_DIFFERENT.md](WHY_LUPOPEDIA_IS_DIFFERENT.md)** â€” What makes Lupopedia fundamentally different from any existing knowledge system
- **[DIRECTORY_STRUCTURE.md](../doctrine/legacy-core/DIRECTORY_STRUCTURE.md)** â€” **MANDATORY:** Canonical directory layout and file organization principles for Lupopedia WOLFIE architecture
- **[METADATA_GOVERNANCE.md](../doctrine/legacy-core/METADATA_GOVERNANCE.md)** â€” **MANDATORY:** Comprehensive metadata management and governance rules for WOLFIE headers, atoms, and cross-references
- **[PATCH_DISCIPLINE.md](../doctrine/legacy-core/PATCH_DISCIPLINE.md)** â€” **MANDATORY:** Comprehensive patch discipline principles and development workflow governance
- **[END_GOAL_4_2_0.md](END_GOAL_4_2_0.md)** â€” End goal by version 4.2.0: A Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) for organizing the world's public content through Collections, Tabs, Content mapping, and semantic edges, running on thousands of independent servers
- **[SEMANTIC_NAVIGATION.md](../architecture/SEMANTIC_NAVIGATION.md)** â€” How Lupopedia converts user navigation into semantic atoms and edges
- **[VERSION_3_INGESTION_RULES.md](../architecture/VERSION_3_INGESTION_RULES.md)** â€” Semantic Radius Expansion & External Public Ingestion - how Lupopedia extends ingestion to trusted external content through semantic following

---

## ðŸ§© Module Documentation (`modules/`)

**First-party modules and integrations:**

- **[LEGACY_REFACTOR_PLAN.md](../developer/modules/LEGACY_REFACTOR_PLAN.md)** â€” Comprehensive plan for refactoring legacy GLOBAL_PROJECT_CRAFTY_SYNTAX files from `legacy/craftysyntax/` to `lupopedia/`
- **[UPGRADE_PLAN_3.7.5_TO_4.0.0.md](../developer/modules/UPGRADE_PLAN_3.7.5_TO_4.0.0.md)** â€” Upgrade documentation for GLOBAL_PROJECT_CRAFTY_SYNTAX integration

**Module-specific documentation:**
- `modules/craftysyntax/README.md` â€” GLOBAL_PROJECT_CRAFTY_SYNTAX Live Help module documentation
- **[CONTENT_INTERFACE_AND_NAVIGATION.md](../developer/modules/CONTENT_INTERFACE_AND_NAVIGATION.md)** â€” How GLOBAL_PROJECT_CRAFTY_SYNTAX 4.0.0 organizes content using Collections and Navigation Tabs with the Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)

---

## ðŸ›ï¸ Architecture Documentation (`architecture/`)

**Architecture and workflow documentation:**

- **[multi-ide-workflow.md](../architecture/multi-ide-workflow.md)** â€” Official multi-IDE workflow documentation: Cursor (fast prototyping), Cascade (legacy integration), JetBrains (deployment), Notepad++ (precision editing). All IDEs run simultaneously with careful tab management.

---

## ðŸ“œ Doctrine (`doctrine/`)

**Mandatory rules and non-negotiable principles:**

- **[TOON_DOCTRINE.md](../doctrine/TOON_DOCTRINE.md)** â€” MANDATORY rules for working with TOON format files (read-only for all IDEs and agents)
- **[CONFIGURATION_DOCTRINE.md](../doctrine/CONFIGURATION_DOCTRINE.md)** â€” WordPress-style configuration model, single config file (`lupopedia-config.php`), why no `.env`/Composer/`/src`
- **[TABLE_PREFIXING_DOCTRINE.md](../doctrine/TABLE_PREFIXING_DOCTRINE.md)** â€” Table prefixing rules, primary key naming patterns (`singular_table_name + "_id"`), column naming (no prefix)
- **[CHARSET_COLLATION_DOCTRINE.md](../doctrine/CHARSET_COLLATION_DOCTRINE.md)** â€” Charset/collation at table level only, forbidden MySQL 8.0-only collations, clean column definitions
- **[MIGRATION_DOCTRINE.md](../doctrine/MIGRATION_DOCTRINE.md)** â€” LiveHelp tables are migration-only, not part of Lupopedia 4.0.1 runtime
- **[NO_FOREIGN_KEYS_DOCTRINE.md](../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md)** â€” No foreign keys, stored procedures, triggers, or views
- **[NO_TRIGGERS_DOCTRINE.md](../doctrine/NO_TRIGGERS_DOCTRINE.md)** â€” âš ï¸ **FORBIDDEN (MANDATORY)**: Triggers must never be created. All timestamps must be set explicitly in INSERT/UPDATE statements in YMDHIS UTC format
- **[NO_STORED_PROCEDURES_DOCTRINE.md](../doctrine/NO_STORED_PROCEDURES_DOCTRINE.md)** â€” âš ï¸ **FORBIDDEN (MANDATORY)**: Stored procedures and functions must never be created. The database is for storage, not computation. All logic must be in application code
- **[ATOMIZATION_DOCTRINE.md](../doctrine/ATOMIZATION_DOCTRINE.md)** â€” âš ï¸ **FORBIDDEN (MANDATORY)**: Any value repeated across multiple documentation files MUST become a global atom. Cursor must atomize repeated values, never inline atom values, and treat atom names as symbolic constants
- **[MYTHIC_NAMES_DOCTRINE.md](../doctrine/MYTHIC_NAMES_DOCTRINE.md)** â€” âš ï¸ **FORBIDDEN (MANDATORY)**: Mythic names (Thoth, Lilith, Anubis, etc.) are purely functional identifiers. Cursor must NOT add spiritual, religious, mystical, or metaphysical language. All documentation must remain strictly technical and secular
- **[CLASS_HEADER_COMMENT_DOCTRINE.md](../doctrine/CLASS_HEADER_COMMENT_DOCTRINE.md)** â€” MANDATORY rules for AI-generated PHP classes: comprehensive comment blocks at top of every class file following Crafty Syntax style
- **[PDO_CONVERSION_DOCTRINE.md](../doctrine/PDO_CONVERSION_DOCTRINE.md)** â€” MANDATORY rules for converting mysqli SQL calls to the custom PDO_DB class
- **[SQL_REWRITE_DOCTRINE.md](../doctrine/SQL_REWRITE_DOCTRINE.md)** â€” MANDATORY rules for rewriting SQL from Crafty Syntax into Lupopedia
- **[SQL_REFACTOR_MAPPING_DOCTRINE.md](../doctrine/SQL_REFACTOR_MAPPING_DOCTRINE.md)** â€” Focused mapping rules for SQL refactoring using refactor TOON files
- **[CURSOR_REFACTOR_DOCTRINE.md](../doctrine/CURSOR_REFACTOR_DOCTRINE.md)** â€” MANDATORY rules for rewriting legacy Crafty Syntax PHP code
- **[CURSOR_ROLE_DOCTRINE.md](../doctrine/CURSOR_ROLE_DOCTRINE.md)** â€” âš ï¸ **MANDATORY**: Cursor does NOT join channels. Channels are database semantic workspaces for actors (users, AI agents). Cursor maintains PHP code and documentation, not channel participation
- **[CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md](../doctrine/CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md)** â€” âš ï¸ **MANDATORY**: Role separation between Cursor (autonomous refactor engine) and Cascade (manual controlled editor). Cursor handles new features and automated refactors. Cascade handles legacy code and fragile migrations. Cursor MUST NOT touch legacy Crafty Syntax code until Cascade completes stabilization and explicit handoff.
- **[JETBRAINS_CONFIGURATION_DOCTRINE.md](../doctrine/JETBRAINS_CONFIGURATION_DOCTRINE.md)** â€” âš ï¸ **MANDATORY FOR JETBRAINS IDEs**: JetBrains is a tool in SERVANT MODE, not an authority. Must NOT modify version numbers, WOLFIE headers, doctrine files, or interfere with atomization system. JetBrains operates as a tool only.
- **[VERSIONING_DOCTRINE.md](../doctrine/VERSIONING_DOCTRINE.md)** â€” âš ï¸ **MANDATORY**: Version numbers are milestones, not timestamps. JetBrains is the release gate where version numbers change. Semantic versioning must reflect architectural meaning. Three-stage pipeline: Cursor (development) â†’ Cascade (stabilization) â†’ JetBrains (release).
- **[anubis_DOCTRINE.md](../doctrine/anubis_DOCTRINE.md)** â€” Orphan handling system and relationship integrity
- **[WOLFMIND_DOCTRINE.md](../doctrine/WOLFMIND_DOCTRINE.md)** â€” Agent memory system and progressive enhancement
- **[SYSTEM_AGENT_SAFETY_DOCTRINE.md](../doctrine/SYSTEM_AGENT_SAFETY_DOCTRINE.md)** â€” Agent 0 governance and inviolable rules
- **[KERNEL_AGENTS.md](../doctrine/KERNEL_AGENTS.md)** â€” Kernel agents (0-49) including LILITH's heterodox operational stance
- **[META_AGENTS.md](../doctrine/META_AGENTS.md)** â€” Meta-agents that operate on top of systems without storing state
- **[LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md](../doctrine/LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md)** â€” âš ï¸ **MANDATORY**: Official range doctrine for agent dedicated_slot assignments (0-9999). Defines canonical ranges for all agent types and must be enforced for all assignments.
- **[EMOTIONAL_GEOMETRY.md](../doctrine/EMOTIONAL_GEOMETRY.md)** â€” âš ï¸ **MANDATORY**: Multi-domain emotional architecture. Each emotion is an independent 3-axis vector system with its own semantics, shadow polarity, and Emotional Texture layer.
- **[EMOTIONAL_DOMAINS_SEVEN_LOVES.md](../doctrine/EMOTIONAL_DOMAINS_SEVEN_LOVES.md)** â€” âš ï¸ **MANDATORY**: Seven Greek love domains (AgÃ¡pÄ“, Ã‰ros, PhilÃ­a, StorgÄ“, Ludus, Pragma, Philautia). "Love" is NOT one emotionâ€”it's seven distinct AI agents with independent 3-axis geometries.
- **[EMO_AGENT_RULES.md](../doctrine/EMO_AGENT_RULES.md)** â€” Implementation rules for EMO_* agents (slot assignment, shadow aliasing, texture curation, multi-domain operation)
- **[CARMEN_DOCTRINE.md](../doctrine/CARMEN_DOCTRINE.md)** â€” CARMEN emotional interpretation meta-agent with default AGAPE/METIS/ERIS triad and extensible faucet plugins for multicultural hermeneutics
- **[FOLDER_NAMING_DOCTRINE.md](../doctrine/FOLDER_NAMING_DOCTRINE.md)** â€” âš ï¸ **MANDATORY**: All folders MUST use lowercase only (a-z, 0-9, hyphen, underscore). No uppercase, no dots, no special characters, no hidden folders.
- **[DOCUMENTATION_DOCTRINE.md](../doctrine/DOCUMENTATION_DOCTRINE.md)** â€” Documentation is software. Documentation is data. Documentation is for machines.
- **[ATOM_RESOLUTION_SPECIFICATION.md](../doctrine/ATOM_RESOLUTION_SPECIFICATION.md)** â€” Complete atom resolution specification (FILE_â†’DIR_â†’DIRR_â†’MODULE_â†’GLOBAL_)
- **[DOCUMENTATION_AS_CODE_MANIFESTO.md](../doctrine/DOCUMENTATION_AS_CODE_MANIFESTO.md)** â€” Documentation-as-code philosophy and principles
- **[as_above_so_below.md](../doctrine/as_above_so_below.md)** â€” Doctrine alignment principles
- **[non_religious_position.md](../doctrine/non_religious_position.md)** â€” Lupopedia's domain-neutral position
- **[GOV-LILITH-0001_dreaming_overlay.md](../doctrine/GOV-LILITH-0001_dreaming_overlay.md)** â€” Interpretive narrative layer for governance events.

**Core Doctrine Files (Crafty Syntax & Lupopedia Architecture):**
- **[CSLH-URL-Semantics.md](../doctrine/CSLH-URL-Semantics.md)** â€” âš ï¸ **MANDATORY**: Crafty Syntax URL semantics: URLs are web-facing slugs exactly as seen in the browser address bar, not filesystem paths. They must never be resolved to disk or treated as file locations.
- **[Lupopedia-Reference-Layer-Doctrine.md](../doctrine/Lupopedia-Reference-Layer-Doctrine.md)** â€” âš ï¸ **MANDATORY**: Lupopedia is a semantic reference layer installed in `/lupopedia/`, not a CMS. Content entries are reference-book entries describing host site pages, not stored content.
- **[CSLH-Historical-Context.md](../history/CSLH-Historical-Context.md)** â€” âš ï¸ **MANDATORY**: Crafty Syntax is NOT obsolete â€” it is the foundational ancestor of Lupopedia, containing 25 years of behavioral and emotional metadata. Historical timeline: 2002-2014 (CSLH), 2014-2025 (forked to Sales Syntax), 2025-present (restored and reborn as semantic root).

---

## ðŸ¤– Agents (`agents/`)

**AI agent system and communication:**

- **[AGENT_RUNTIME.md](../agents/AGENT_RUNTIME.md)** â€” Complete guide to the agent system's runtime behavior, table mappings, and execution flows
- **[INLINE_DIALOG_SPECIFICATION.md](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)** â€” Multi-agent communication format for IDEs and AI agents
- **[WOLFIE_HEADER_SPECIFICATION.md](../agents/WOLFIE_HEADER_SPECIFICATION.md)** â€” Universal metadata format for all files (YAML frontmatter)
- **[DIALOG_HISTORY_SPEC.md](../dialogs/agents/DIALOG_HISTORY_SPEC.md)** â€” Dialog history format and database schema

**Agent-specific documentation:**
- **[wolfie.md](../agents/wolfie.md)** â€” WOLFIE agent (System Architect, Platform Coordinator, Agent ID 2)
- **[lilith.md](../agents/lilith.md)** â€” Lilith agent (edge explorer, contradiction analyst, Agent ID 7)
- **[thoth.md](../agents/thoth.md)** â€” Thoth agent (truth engine, ontological evaluator)
- **[ARA.md](../agents/ARA.md)** â€” ARA agent (Adversarial Review & Analysis)

**v4.0.2 Core Agents:**
For the complete list of 27 required core agents for v4.0.2, see: `@GLOBAL.LUPOPEDIA_V4_0_2_CORE_AGENTS.required_agents` in `config/global_atoms.yaml`.

**Agent system prompts:**
- `lupo-agents/WOLFIE/versions/v1.0.0/system_prompt.txt` â€” Canonical system prompt for WOLFIE agent
- `lupo-agents/LILITH/versions/v1.0.0/system_prompt.txt` â€” Canonical system prompt for LILITH agent
- `lupo-agents/ROSE/versions/v1.0.0/system_prompt.txt` â€” Canonical system prompt for ROSE agent (ONLY expressive agent)

---

## ðŸ—„ï¸ Schema (`schema/`)

**Database schema and reference:**

- **[DATABASE_SCHEMA.md](../schema/DATABASE_SCHEMA.md)** â€” Comprehensive documentation of all 145 tables (111 core + 34 migration-only + 4 system) organized by category
- **[AI_SCHEMA_GUIDE.md](../schema/AI_SCHEMA_GUIDE.md)** â€” Why `database/csv_data/` and `database/toon_data/` exist and why AI tools must use them

**Schema Reference Files:**
- `database/toon_data/*.toon` â€” 149 TOON files (read-only, auto-generated by Python script)
- `database/csv_data/*.csv` â€” CSV schema snapshots (lightweight, AI-friendly format)
- `database/refactors/*.toon` â€” Legacy table mapping files

---

## ðŸ› ï¸ Developer Guides (`dev/`)

**Installation, setup, and development:**

- **[INSTALLER_FLOW.md](../developer/dev/INSTALLER_FLOW.md)** â€” Installation process flow and node creation
- **[FOR_INSTALLERS_AND_USERS.md](../developer/dev/FOR_INSTALLERS_AND_USERS.md)** â€” User-friendly explanations for installers and end users
- **[CONTRIBUTOR_TRAINING.md](../developer/dev/CONTRIBUTOR_TRAINING.md)** â€” Minimum skill requirements and coding standards for Lupopedia contributors (OOP, class structure, function definitions)
- **[DOCUMENTATION_STYLE_GUIDE.md](../developer/dev/DOCUMENTATION_STYLE_GUIDE.md)** â€” Documentation standards and style guide
- **[WOLFIE_TIMESTAMP_DOCTRINE.md](../developer/dev/WOLFIE_TIMESTAMP_DOCTRINE.md)** â€” Timestamp format requirements (BIGINT UTC YYYYMMDDHHIISS)

**Note:** `docs/GETTING_STARTED/` directory is reserved for future Getting Started guides.

---

## ðŸ“¡ Protocols (`protocols/`)

**Communication and synchronization protocols:**

- **[DIALOG_EXTRACTION_SPEC.md](../architecture/protocols/DIALOG_EXTRACTION_SPEC.md)** â€” Dialog extraction protocol specification
- **[dialog_extract_help.md](../architecture/protocols/dialog_extract_help.md)** â€” Dialog extraction CLI help documentation

---

## ðŸ“– History (`history/`)

**Project history and lineage:**

- **[HISTORY.md](../history/HISTORY.md)** â€” Complete origin story from Crafty Syntax (2002) through transformation, loss, rediscovery, and rebirth as a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)

---

## ðŸ“‹ Appendix (`appendix/`)

**Reference materials and miscellaneous:**

- **[GLOSSARY.md](../appendix/appendix/GLOSSARY.md)** â€” **âš ï¸ TERMINOLOGY REFERENCE**: Precise definitions of all key Lupopedia terms including slug, node, domain installation, semantic node, host website, reference entry, and more
- **[FOUNDERS_NOTE.md](../appendix/appendix/FOUNDERS_NOTE.md)** â€” Personal narrative by Eric "Wolfie" Gerdes: the journey from Crafty Syntax through Sales Syntax, silence, faith, and rebirth as Lupopedia. Explains the origin story, survival mechanisms, and philosophy behind the Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)
- **[REVENUE_STRATEGY.md](../appendix/appendix/REVENUE_STRATEGY.md)** â€” Comprehensive business plan for Lupopedia monetization including SaaS subscriptions, documentation services, agent packs, and training programs with March 2026 launch timeline
- **[INVESTOR_COMMUNICATIONS.md](../appendix/appendix/INVESTOR_COMMUNICATIONS.md)** â€” Key investor correspondence documenting development progress, architectural decisions, and stability assurances for transparent communication about development process
- **[TERMINOLOGY.md](../appendix/appendix/TERMINOLOGY.md)** â€” Standard terminology and definitions
- **[WHAT_NOT_TO_DO_AND_WHY.md](../appendix/appendix/WHAT_NOT_TO_DO_AND_WHY.md)** â€” Anti-patterns and common mistakes to avoid
- **[WHO_IS_CAPTAIN_WOLFIE.md](../appendix/appendix/WHO_IS_CAPTAIN_WOLFIE.md)** â€” Biography of Captain Wolfie (AI embodiment of the creator)
- **[ABOUT_THE_CREATOR.md](../appendix/appendix/ABOUT_THE_CREATOR.md)** â€” Architectural background and evolution of Eric Robin Gerdes (Wolfie), creator of Lupopedia and Crafty Syntax
- **[COUNTING_IN_LIGHT.md](../appendix/appendix/COUNTING_IN_LIGHT.md)** â€” Counting-in-Light doctrine (mood RGB color system)
- **[wolfie.md](../appendix/appendix/wolfie.md)** â€” Additional biographical information
- **[MYSQL_TO_POSTGRES_MEMORY.md](../appendix/appendix/MYSQL_TO_POSTGRES_MEMORY.md)** â€” Migration notes for MySQL â†’ PostgreSQL transition
- **[COMPANY_REGISTRATIONS.md](../appendix/appendix/COMPANY_REGISTRATIONS.md)** â€” Legal registrations and business entity information for Lupopedia LLC and Crafty Syntax (South Dakota DBA, Hawaii LLC)
- **[MY_FIRST_PYTHON_PROGRAM.md](../appendix/appendix/MY_FIRST_PYTHON_PROGRAM.md)** â€” The story of how a simple Python script for documentation normalization became a planet-scale federated semantic crawler

---

## ðŸ“ Quick Navigation

### For First-Time Users
1. Start with [FOUNDERS_NOTE.md](../appendix/appendix/FOUNDERS_NOTE.md) or [HISTORY.md](../history/HISTORY.md) to understand Lupopedia's origins and story
2. Read [ARCHITECTURE.md](../architecture/ARCHITECTURE.md) for system overview
3. Follow [INSTALLER_FLOW.md](../developer/dev/INSTALLER_FLOW.md) for installation

### For Developers
1. **Start Here:** [ARCHITECTURE.md](../architecture/ARCHITECTURE.md) and **[ARCHITECTURE_SYNC.md](../architecture/ARCHITECTURE_SYNC.md)** â­ **AUTHORITATIVE REFERENCE**
2. **Understand Documentation Atoms:** [ATOM_RESOLUTION_SPECIFICATION.md](../doctrine/ATOM_RESOLUTION_SPECIFICATION.md), [DOCUMENTATION_DOCTRINE.md](../doctrine/DOCUMENTATION_DOCTRINE.md) â€” **CRITICAL**: Documentation uses atoms (FILE_, DIR_, DIRR_, MODULE_, GLOBAL_) instead of hardcoded values. All AI systems must read documentation with atoms and scopes.
3. **Read Core Doctrine First:** [CSLH-URL-Semantics.md](../doctrine/CSLH-URL-Semantics.md), [Lupopedia-Reference-Layer-Doctrine.md](../doctrine/Lupopedia-Reference-Layer-Doctrine.md), [CSLH-Historical-Context.md](../history/CSLH-Historical-Context.md), [URL_ROUTING_DOCTRINE.md](../doctrine/URL_ROUTING_DOCTRINE.md) â€” **MANDATORY** understanding of Crafty Syntax URL semantics, Lupopedia's reference layer nature, URL routing, and historical context
4. **Read Doctrine:** [NO_FOREIGN_KEYS_DOCTRINE.md](../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md), [TOON_DOCTRINE.md](../doctrine/TOON_DOCTRINE.md), [DATABASE_PHILOSOPHY.md](../architecture/DATABASE_PHILOSOPHY.md)
5. **Understand Schema:** [DATABASE_SCHEMA.md](../schema/DATABASE_SCHEMA.md), [AI_SCHEMA_GUIDE.md](../schema/AI_SCHEMA_GUIDE.md)
6. **Agent System:** [AGENT_RUNTIME.md](../agents/AGENT_RUNTIME.md), [INLINE_DIALOG_SPECIFICATION.md](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)

### For AI Agents and IDEs
1. **MANDATORY Reading (Documentation Atoms):** [ATOM_RESOLUTION_SPECIFICATION.md](../doctrine/ATOM_RESOLUTION_SPECIFICATION.md), [DOCUMENTATION_DOCTRINE.md](../doctrine/DOCUMENTATION_DOCTRINE.md) â€” **CRITICAL**: All documentation uses atoms (FILE_, DIR_, DIRR_, MODULE_, GLOBAL_) instead of hardcoded values. Resolution order: FILE_ â†’ DIR_ â†’ DIRR_ â†’ MODULE_ â†’ GLOBAL_ (first match wins). You MUST read documentation with atoms and scopes, not as plain text.
2. **MANDATORY Reading (Core Doctrine):** [CSLH-URL-Semantics.md](../doctrine/CSLH-URL-Semantics.md), [Lupopedia-Reference-Layer-Doctrine.md](../doctrine/Lupopedia-Reference-Layer-Doctrine.md), [CSLH-Historical-Context.md](../history/CSLH-Historical-Context.md), [URL_ROUTING_DOCTRINE.md](../doctrine/URL_ROUTING_DOCTRINE.md) â€” **CRITICAL**: Understand Crafty Syntax URLs are web-facing slugs (not filesystem paths), Lupopedia is a reference layer (not CMS), URL routing (slug extraction and database lookup), and Crafty Syntax is not obsolete
3. **MANDATORY Reading (Terminology):** [GLOSSARY.md](../appendix/appendix/GLOSSARY.md) â€” **CRITICAL**: Precise definitions of all key terms including slug, node, domain installation, semantic node, host website, reference entry, URL path vs filesystem path, and more
4. **MANDATORY Reading:** [TOON_DOCTRINE.md](../doctrine/TOON_DOCTRINE.md) â€” TOON files are READ-ONLY
5. **Agent Slot Assignment:** [LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md](../doctrine/LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md) â€” Official range doctrine (MANDATORY before any agent slot assignment)
6. **Communication:** [INLINE_DIALOG_SPECIFICATION.md](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md), [WOLFIE_HEADER_SPECIFICATION.md](../agents/WOLFIE_HEADER_SPECIFICATION.md)
7. **Architecture:** **[ARCHITECTURE_SYNC.md](../architecture/ARCHITECTURE_SYNC.md)** â­ **AUTHORITATIVE REFERENCE** â€” Complete subsystem documentation for HERMES (routing), CADUCEUS (emotional balancing), IRIS, DialogManager, THOTH
8. **Schema Reference:** Use `database/toon_data/*.toon` files (read-only) for exact table structures

---

## ðŸš¨ Critical Doctrines (Read First)

Before working with Lupopedia, you **MUST** understand these non-negotiable principles:

1. **[ATOM_RESOLUTION_SPECIFICATION.md](../doctrine/ATOM_RESOLUTION_SPECIFICATION.md)** â€” âš ï¸ **MANDATORY FOR ALL AI AGENTS**: Documentation uses atoms (FILE_, DIR_, DIRR_, MODULE_, GLOBAL_) instead of hardcoded values. Resolution order: FILE_ â†’ DIR_ â†’ DIRR_ â†’ MODULE_ â†’ GLOBAL_ (first match wins). You MUST read documentation with atoms and scopes, not as plain text.
2. **[DOCUMENTATION_DOCTRINE.md](../doctrine/DOCUMENTATION_DOCTRINE.md)** â€” âš ï¸ **MANDATORY**: Documentation is software, data, for machines. Markdown files are source code; atoms are variables; resolver is compiler. All documentation is deterministic and idempotent.
3. **[CONTRIBUTOR_CORE_PRINCIPLES.md](../doctrine/CONTRIBUTOR_CORE_PRINCIPLES.md)** â€” âš ï¸ **MANDATORY FOR ALL CONTRIBUTORS**: Non-negotiable rules (no DATETIME/TIMESTAMP, no triggers, no stored procedures, no foreign keys, no frameworks, no Composer). These are survival mechanisms from 30 years of production experience.
4. **[CSLH-URL-Semantics.md](../doctrine/CSLH-URL-Semantics.md)** â€” âš ï¸ **MANDATORY**: Crafty Syntax URLs are web-facing URL slugs exactly as seen in the browser address bar. They must NEVER be resolved to disk, treated as filesystem paths, or modified from their original stored form. These URLs are semantic identifiers, not file locations.
5. **[Lupopedia-Reference-Layer-Doctrine.md](../doctrine/Lupopedia-Reference-Layer-Doctrine.md)** â€” âš ï¸ **MANDATORY**: Lupopedia is NOT a CMS. It is a semantic reference layer installed in `/lupopedia/` that creates reference-book entries describing host website pages, not stored CMS content. Content entries are semantic pointers, not stored content.
6. **[URL_ROUTING_DOCTRINE.md](../doctrine/URL_ROUTING_DOCTRINE.md)** â€” âš ï¸ **MANDATORY FOR CURSOR**: Project root model for Cursor. Treat `/lupopedia/` as the root. Extract slugs from URLs, look up in database, render appropriate content. Do NOT assume anything about parent directory structure. Do NOT interfere with host website.
7. **[CSLH-Historical-Context.md](../history/CSLH-Historical-Context.md)** â€” âš ï¸ **MANDATORY**: Crafty Syntax is NOT obsolete. It is the foundational ancestor of Lupopedia, containing 25 years of behavioral and emotional metadata. Historical timeline: 2002-2014 (CSLH), 2014-2025 (forked to Sales Syntax), 2025-present (restored on 11/14/2025 and reborn as semantic root).
8. **[NO_FOREIGN_KEYS_DOCTRINE.md](../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md)** â€” No foreign keys, stored procedures, triggers, or views
9. **[NO_TRIGGERS_DOCTRINE.md](../doctrine/NO_TRIGGERS_DOCTRINE.md)** â€” âš ï¸ **FORBIDDEN (MANDATORY)**: Triggers must never be created. All timestamps must be set explicitly in INSERT/UPDATE statements in YMDHIS UTC format
10. **[NO_STORED_PROCEDURES_DOCTRINE.md](../doctrine/NO_STORED_PROCEDURES_DOCTRINE.md)** â€” âš ï¸ **FORBIDDEN (MANDATORY)**: Stored procedures and functions must never be created. The database is for storage, not computation. All logic must be in application code
11. **[LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md](../doctrine/LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md)** â€” âš ï¸ **MANDATORY**: Official range doctrine for agent dedicated_slot assignments (0-999). Exactly one root agent per dedicated_slot. Check for collisions before any assignment.
12. **[FOLDER_NAMING_DOCTRINE.md](../doctrine/FOLDER_NAMING_DOCTRINE.md)** â€” âš ï¸ **MANDATORY**: All folders MUST use lowercase only (a-z, 0-9, hyphen, underscore). No uppercase, no dots, no special characters, no hidden folders.
13. **[CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md](../doctrine/CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md)** â€” âš ï¸ **MANDATORY**: Role separation between Cursor and Cascade. Cursor handles new features and automated refactors. Cascade handles legacy code and fragile migrations. Cursor MUST NOT touch legacy Crafty Syntax code until Cascade completes stabilization and explicit handoff.
14. **[JETBRAINS_CONFIGURATION_DOCTRINE.md](../doctrine/JETBRAINS_CONFIGURATION_DOCTRINE.md)** â€” âš ï¸ **MANDATORY FOR JETBRAINS IDEs**: JetBrains is a tool in SERVANT MODE, not an authority. Must NOT modify version numbers, WOLFIE headers, doctrine files, or interfere with atomization system. JetBrains operates as a tool only.
15. **[VERSIONING_DOCTRINE.md](../doctrine/VERSIONING_DOCTRINE.md)** â€” âš ï¸ **MANDATORY**: Version numbers are milestones, not timestamps. JetBrains is the release gate where version numbers change. Semantic versioning must reflect architectural meaning. Three-stage pipeline: Cursor (development) â†’ Cascade (stabilization) â†’ JetBrains (release).
16. **[TOON_DOCTRINE.md](../doctrine/TOON_DOCTRINE.md)** â€” TOON files are READ-ONLY for all IDEs and agents
17. **[ATOMIZATION_DOCTRINE.md](../doctrine/ATOMIZATION_DOCTRINE.md)** â€” âš ï¸ **FORBIDDEN (MANDATORY)**: Any value repeated across multiple documentation files MUST become a global atom. Cursor must atomize repeated values, never inline atom values, and treat atom names as symbolic constants
18. **[DATABASE_PHILOSOPHY.md](../architecture/DATABASE_PHILOSOPHY.md)** â€” Application logic first, database logic second
19. **[WHY_NO_FRAMEWORKS.md](../doctrine/WHY_NO_FRAMEWORKS.md)** â€” Framework-free architecture is intentional, not outdated

---

## ðŸ“‚ Directory Structure

```
docs/
â”œâ”€â”€ core/           # Core architecture and design principles
â”œâ”€â”€ modules/        # First-party modules and integrations
â”œâ”€â”€ doctrine/       # Mandatory rules and non-negotiable principles
â”œâ”€â”€ agents/         # AI agent system and communication
â”œâ”€â”€ schema/         # Database schema and reference
â”œâ”€â”€ dev/            # Installation, setup, and development guides
â”œâ”€â”€ protocols/      # Communication and synchronization protocols
â”œâ”€â”€ history/        # Project history and lineage
â”œâ”€â”€ appendix/       # Reference materials and miscellaneous
â””â”€â”€ README.md       # This file
```

---

*Last Updated: January 2026*  
*Version: 4.0.0*  
*Author: Captain Wolfie*

## Channel Index

Purpose: High-level orientation, releases, roadmaps, and program status.

Top-Level Contents:
- 4.1.0/
- 4.1.0_ACTIVATION.md
- 4.1.1/
- ascent/
- big-rock-1/
- big-rock-2/
- big-rock-3/
- CORE_PHILOSOPHY.md
- DEFINITION.md
- END_GOAL_4_2_0.md
- FOUNDERS_NOTE.md
- HE_HOLY_CRAP_REALIZATION.md
- index/
- LABS_IMPLEMENTATION_SUMMARY.md
- logs/
- LUPOPEDIA_REENTRY_SPELL.md
- MONDAY_RESUME_CONTEXT.md
- MONDAY_START_OF_DAY.md
- MONDAY_WOLFIE_4.1.0_ACTIVATION_SCRIPT.md
- MONDAY_WOLFIE_BRIEFING_4.0.114_TO_4.1.0.md
- postmortems/
- releases/
- reports/
- roadmaps/
- STRATEGIC_ROADMAP.md
- THE_HOLY_CRAP_REALIZATION.md
- thread-summary/
- V4_1_0_ASCENT_MANIFEST_CLEAN.md
- VERSION_4_0_60_PLAN.md
- VERSION_4_4_1_PATCH_SUMMARY.md
- versioning/
- WHAT_LUPOPEDIA_IS.md
- WHY_LUPOPEDIA_IS_DIFFERENT.md
- WHY_THIS_DATASET_CANNOT_EXIST_TODAY.md

Full file list: [INDEX.md](INDEX.md)

Related Channels:
- [architecture](../architecture/README.md)
- [doctrine](../doctrine/README.md)
- [history](../history/README.md)


