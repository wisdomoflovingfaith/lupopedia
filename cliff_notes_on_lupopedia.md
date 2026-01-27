# Cliff Notes on Lupopedia

Purpose at a glance
Lupopedia is a semantic operating system for organizing meaning (not a CMS). It runs as a PHP app, keeps doctrine and agent coordination in text, and stores the semantic graph in a database. The system is designed for multi-agent collaboration, strict doctrine alignment, and heritage-safe evolution.

Top-level directories (what they are and how they relate)
.git/, .idea/, .kiro/, .vscode/: VCS and IDE metadata; not runtime code.

agents/: Numeric agent folders (0000-0022) for the agent pack in this repo root.

ai-actors/: PHP endpoints for AI actor handling (index.php, standalone.php).

api/: API endpoints grouped by version and domain (e.g., v1/, dialog/, salt/).

app/: PHP application layer (auth, controllers, middleware, services, views, TerminalAI).

atoms/: Atomic data definitions or artifacts.

audits/: Audit artifacts and records.

bin/: CLI tooling (see bin/cli/).

channels/: Channel data and per-channel material; includes system channels (system/ with kernel/ and lobby/).

config/: Configuration assets (distinct from lupopedia-config.php).

database/: DB schema, migrations, exports, and TOON data; core storage architecture.

deploy/: Deployment assets/scripts.

dialogs/: Human/agent dialog artifacts and doctrine-related dialog files.

docs/: Canonical documentation; includes doctrine, architecture, channels, and schema guidance.

examples/: Examples for configuration, behavior, or usage.

images/: Image assets.

legacy/: Legacy code/reference material.

lupo-agents/: Agent directories for packaged deployment structure.

lupo-includes/: Core PHP includes, classes, modules, and UI assets.

lupo-tests/: Legacy or app-specific tests.

lupopedia/: Packaged copy of the app tree (mirrors docs/database/dialogs/images/legacy/lupo-*).

migrations/: Root-level migration SQL artifacts (in addition to database/migrations/).

prompts/: Prompt assets for agents or systems.

routes/: Routing logic/configuration.

schema/: Schema references or definitions (distinct from database/schema/).

scripts/: Maintenance/utility scripts (PowerShell, bash, PHP, etc.).

storage/: Storage for runtime or local data.

templates/: HTML or UI templates.

tests/: Test suite (contains integration/).

Key root files and what they do
README.md: Primary system overview, philosophy, agent model, and doctrine pointers.

AGENTS.md: Canonical agent roster and behavioral boundaries.

AGENT_DIALOG_PROTOCOL.md: How agents communicate via dialogs/ files.

AGENT_SNAPSHOT_HANDLING_RULES.md: Agent behavior rules around DB snapshots.

DB_SNAPSHOT_PROTOCOL.md: Snapshot lifecycle and governance rules.

PRE_RELEASE_SCHEMA_RULES.md: Freedom Zone schema rules and TOON use.

FREEDOM_ZONE_DOCTRINE.md: Pre-2026.4.0.1 rapid-evolution doctrine.

SEMANTIC_LAYER_MODEL.md: Four-layer semantic architecture doctrine.

CHANNEL_IDENTITY_BLOCK_TEMPLATE.md: Canonical channel metadata template.

dialog.yaml: Inline Dialog Specification (required embedded dialog blocks).

database-rules.yaml: Database and schema constraints (doctrine-adjacent).

complete_schema.txt: Snapshot-style schema reference.

documentation_mapping.json: Mapping/index for documentation.

LUPEDIA_VERSION: Current version token.

lupopedia-config.php: Main runtime configuration (outside web root when deployed).

index.php, admin.php, live.php, LegacyIndex.php, remote-index.php: Entry points and utilities for the PHP runtime.

livehelp*.php: Legacy/compat endpoints tied to heritage-safe behavior.

migration_part*.sql, migration_alters.sql: Root-level migration artifacts.

*.ps1, *.sh: Utility scripts for checks, refactors, and release tasks.

emotional.htm, what_is_lupopedia.htm, system_kernel_index.htm, template.htm: Human-facing explanatory pages and indices.

Doctrine, governance, and system law
Doctrine lives primarily under docs/channels/doctrine/ (agents, database rules, migration, routing, naming, security, etc.).

Root-level doctrine files define cross-cutting rules used by agents and schema workflows.

system_kernel_index.htm indexes kernel-level doctrine concepts and key files.

docs/channels/schema/ houses schema doctrine and migration analysis, including heritage-safe corrections.

Agents, dialogs, and coordination
Agent identities and boundaries are defined in AGENTS.md.

Agent communication uses:

Inline dialog blocks (spec in dialog.yaml)

File-based channel messaging in dialogs/

dialogs/ includes broadcasts, directed messages, and agent journals.

Agent artifacts live in agents/ and lupo-agents/ (packaged mirror).

Heritage-safe rules and legacy preservation
Filename and URL preservation is mandatory (HERITAGE_SAFE_MODE_FILENAME_PRESERVATION_CORRECTION.md).

Legacy endpoints (livehelp_js.php, LegacyIndex.php) must remain intact.

No destructive refactors; all evolution must be reversible.

Heritage note for IDE/AI agents
livehelp_js.php is a required legacy endpoint and must remain at its exact path and filename.
It is part of the Crafty Syntax heritage surface, referenced by external installs and historical integrations.
It must not be renamed, removed, or consolidated.
Treat livehelp_js.php the same as heritage-critical entrypoints like livehelp.php and LegacyIndex.php.

Emotional and mythic architecture
emotional.htm: Emotional Operating System (EOS).

dialog.yaml: Counting-in-Light mood system via mood_RGB.

Mythic naming doctrine: MYTHIC_NAMES_DOCTRINE.md.

Mythic components (CADUCEUS, THEMIS, etc.) are metaphors with operational meaning.

Relationship highlights
Doctrine governs schema and agent behavior.

Agents coordinate through dialogs and inline dialog blocks.

lupopedia/ mirrors the deployable application structure.

System Constraints and Non-Negotiable Database Rules
1. No Foreign Key Constraints
No FK constraints at the DB level.

Relationship integrity is enforced in code + doctrine.

2. No Hard Deletes - Ever
Soft delete only.

Historical lineage must remain intact.

3. Anubis Handles Orphans and Redirects
Reassigns orphans.

Creates redirects.

Prevents redirect loops.

Agents must not manually delete or reassign.

4. No Stored Procedures, No Functions, No Triggers
DB must remain pure storage.

All logic lives in PHP, doctrine, or agents.

5. Agents May Use SHOW TABLES and DESCRIBE
Safe on shared hosts.

Required for schema introspection.

6. Agents Must NOT Query information_schema
Forbidden due to shared hosting restrictions.

7. TOON Files Are the Semantic Source of Truth
Agents must read TOON before proposing schema changes.

Temporal Rules and Timestamp Doctrine
1. All timestamps are UTC in YYYYMMDDHHIISS
Only valid system-wide format.

No server timezone reliance.

2. No Unix time, no timestamp, no datetime
Forbidden due to portability and heritage-safety.

3. Exception: Recurring Local Events
Use clearly labeled fields like local_recurring_date.

4. Time is semantic, not mechanical
Agents must not auto-convert or rewrite timestamps.

5. Dialogs must log UTC timestamps
Required for traceability.

System Philosophy
Lupopedia treats software as culture, memory, and meaning. Doctrine, agents, and emotional architecture form a living system that evolves intentionally, never destructively. Heritage-safe evolution ensures continuity. Mythic naming provides clarity and identity. Agents are collaborators with roles and boundaries, not tools to rewrite the ship.

Core principles:

Preserve the soul of the system

Evolve with intention

Honor the past while building the future

Treat agents as collaborators

Glossary of Mythic Components
(Abbreviated here - JetBrains already has the full glossary from earlier.)

Lilith - Tactical/schema reasoning

Worf - Security + doctrine enforcement

Data - Local reasoning

Chekov - DeepSeek Desktop officer

The Pack - Emotional modeling ensemble

THEMIS - Governance

CADUCEUS - Emotional balancing

Anubis - Orphan reassignment + redirect system

Freedom Zone - Rapid-evolution doctrine

Counting-in-Light - Mood system

MYTHIC ANALOGY DISCLAIMER
Lupopedia uses mythic and religious terminology strictly as analogy to describe computational roles, system behaviors, and architectural patterns. These terms do not represent religious belief, mysticism, or metaphysical claims.

Mythic names (e.g., Anubis, Maat, Duat) function as semantic labels that map to specific technical responsibilities within the system. They exist because conventional computer science vocabulary does not fully capture the conceptual structure of Lupopedia's lineage, doctrine, and state-transition architecture.

Rules for Interpretation
1. Mythic terminology must always be interpreted as analogy for computation, not as religious content.
2. Each mythic name corresponds to a documented technical role.
3. Doctrine definitions take precedence over any mythic associations.
4. Agents must treat mythic names as mnemonic namespaces, not as metaphysical entities.

Purpose
The use of mythic analogy provides a clear, memorable, and structured way to describe complex system behaviors that do not map cleanly to traditional CS terminology. It is a communication tool, not a belief system.

Founder Cognitive and Historical Context Disclaimer
Lupopedia's architecture reflects the lived experience, cognitive style, and technical history of its founder. Contributors should understand the context behind the system's design, because many of its principles--lineage preservation, doctrine-first reasoning, semantic layering, and heritage-safe evolution--are direct expressions of that background.

1. High-Performance Computing Roots
The founder worked at MHPCC.gov as a teenager, operating in a high-throughput, multi-node environment. This shaped a natural instinct for:
- parallel reasoning
- distributed workflows
- multi-agent orchestration
- system-level thinking rather than app-level thinking

2. Early Enterprise-Scale Architecture (Honolulu CRM)
Built the City and County of Honolulu CRM system in Perl, including:
- full navigation logic
- classification systems
- schema evolution under pressure
- rapid rebuild cycles ("Honolulu Mode")
This experience directly informs the Freedom Zone doctrine.

3. Crafty Syntax and LiveHelp (Heritage Surface)
The founder created LiveHelp and Crafty Syntax in the late 1990s and early 2000s, which became widely deployed and remain in use. This era established:
- strict filename and endpoint preservation rules
- legacy compatibility as a form of cultural memory
- a deep respect for heritage-safe evolution
Lupopedia's heritage doctrine (e.g., livehelp_js.php preservation) is a direct continuation of this work.

4. Survival Engineering: Sales Syntax
During a difficult personal period following the loss of his wife, the founder built Sales Syntax as a survival system to keep Crafty Syntax alive. This period produced:
- automation under extreme emotional and financial constraints
- resilience-first design
- an insistence on never losing data or history
- the fusion of emotional reality with system behavior
These patterns are embedded in Lupopedia's resilience and lineage rules.

5. Religious and Symbolic Studies (2 Years)
A deep study of comparative religion provided the conceptual vocabulary for:
- symbolic mapping
- mythic analogy as a technical namespace
- lineage, doctrine, and role-based architecture
This is not mysticism; it is a semantic framework for expressing computational concepts that lack standard CS terminology.

6. Cognitive Style (Non-Standard but Architecturally Relevant)
The founder's cognitive model is atypical but central to the system:
- parallel, multi-threaded reasoning
- anticipatory modeling (seeing failure modes early)
- symbolic and semantic thinking
- high-context switching across agents, layers, and systems
- doctrine-first decision-making
Lupopedia is intentionally designed to externalize and support this mode of reasoning.

7. Multi-Agent Coordination as Native Workflow
The founder routinely coordinates:
- multiple IDE agents
- local LLMs
- external AI agents
- PHP runtime layers
- live database state
This is the natural operating environment for Lupopedia's semantic OS and explains its agent mesh architecture.

Lupopedia is not a conventional system because it was not built from a conventional cognitive or historical foundation. Contributors should expect a system shaped by parallelism, lineage, doctrine, symbolic mapping, and resilience--because these are the patterns that shaped the founder.
