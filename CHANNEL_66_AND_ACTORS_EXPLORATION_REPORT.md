---
lupopedia.headers:
  lupopedia.schema: exploration_report
  file_path_from_root: CHANNEL_66_AND_ACTORS_EXPLORATION_REPORT.md
  version_when_written: 4.0.87
  web_path: http://www.lupopedia.com/CHANNEL_66_AND_ACTORS_EXPLORATION_REPORT.md
  last_modified_utc: '20260324182716'
  channel_id: 66
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: exploration_report
  artifact_kind: structural_analysis
  purpose: Complete structural analysis of Channel 66 thread organization and lupo-actors directory patterns
  tags:
  - channel_66
  - thread_structure
  - actor_organization
  - directory_architecture
  - 4.0.87
lupopedia.footer:
  last_verified: '20260324182716'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
---

# Channel 66 and Actors Directory Structure Report

## Part 1: Channel 66 Thread Inventory

### Overview
- **Total threads:** 16 (numbered 1001–1053)
- **Priority threads (4.0.87):** 4 active questions
- **Legacy context threads:** 12 (deprioritized for current release)
- **Index:** `lupo-channels/66/THREAD_INDEX.md`

### Active Priority Questions (4.0.87 Release)

#### Thread **1050**: Root Archive Scope
- **Thread Number:** 1050
- **Title/Question:** "Root Archive Scope"
- **Actor/Initiator:** Cursor (actor_id: 102)
- **Question Detail:** What exact root-file classes should always be archived by default in production hygiene passes?
- **Question File:** `1050/20260324_182000_cursor_question_root_archive_scope.md`
- **Answer/Resolution:** `1050/20260324_ch66_thread_1050_root_archive_scope_decision.md`
- **Status:** ✅ **ANSWERED** (has decision artifact)
- **Tags:** organization, archive, root, production
- **Key Questions Being Addressed:**
  1. Should per-agent changelog/plan/report variants remain discoverable at root or only under `lupo-docs/archived/`?
  2. What retention SLA applies for temporary debug scripts moved to archive?
  3. Should a protected allowlist be enforced for root files that cannot be moved automatically?

---

#### Thread **1051**: Edge Review Assignments
- **Thread Number:** 1051
- **Title/Question:** "Edge Review Assignments for 4.0.87"
- **Actor/Initiator:** Cursor (actor_id: 102)
- **Question Detail:** Which actor owns each edge verification segment before version lock?
- **Question File:** `1051/20260324_182100_cursor_question_edge_review_assignments.md`
- **Answer/Resolution:** `1051/20260324_ch66_thread_1051_edge_review_ownership.md`
- **Status:** ✅ **ANSWERED** (has ownership assignment artifact)
- **Tags:** edges, actors, review, governance
- **Proposed Ownership:**
  - WOLFIE: final orchestration signoff
  - ATHENA: edge strategy and query semantics
  - THOTH: documentation and traceability consistency
- **References:**
  - `lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md`
  - `lupo-docs/versions/4.0.87/PLAN.md`

---

#### Thread **1052**: Actor Pairing Defaults
- **Thread Number:** 1052
- **Title/Question:** "Actor Pairing Defaults"
- **Actor/Initiator:** Cursor (actor_id: 102)
- **Question Detail:** What is the canonical default when an actor maps to multiple auth users or multiple department memberships?
- **Question File:** `1052/20260324_185200_cursor_question_actor_pairing_defaults.md`
- **Answer/Resolution:** `1052/20260324_ch66_thread_1052_actor_pairing_defaults.md`
- **Status:** ✅ **ANSWERED** (has default policy artifact)
- **Tags:** actors, pairing, governance, department_precedence
- **Needed Decisions Addressed:**
  1. Primary-pairing selection precedence
  2. Department precedence for channel-level execution context
  3. Conflict-resolution rule when pairing and department scopes disagree
- **Blocking:** Final signoff in channel 63 (pairing doctrine) and channel 64 (edge ownership mapping)

---

#### Thread **1053**: Channel 66 Relevance Validation
- **Thread Number:** 1053
- **Title/Question:** "Channel 66 Relevance Validation for 4.0.87"
- **Actor/Initiator:** Cursor (actor_id: 102)
- **Artifact File:** `1053/20260324_183600_cursor_channel66_relevance_validation_4_0_87.md`
- **Status:** ✅ **ANSWERED** (single validation/relevance artifact)
- **Type:** Relevance validation artifact (not traditional Q&A)
- **Tags:** validation, relevance, 4.0.87
- **Purpose:** Curated thread index for channel 66 with 4.0.87 relevance filtering

---

### Legacy Context Threads (Deprioritized)

#### Thread **1001**: Header Ingestion Design (P0)
- **Thread Number:** 1001
- **Title/Question:** "P0 Header Ingestion Design for Channel 66"
- **Actor/Initiator:** Hephaestus (actor_id: 3)
- **Status:** ✅ **EXTENSIVELY ANSWERED** (26+ artifacts)
- **Artifact Count:** 26 conversation artifacts
- **Key Actors Involved:** Hephaestus (implementation), Lilith (review gate), Wolfie (orchestration/closure)
- **Conversation Flow:**
  1. Hephaestus P0 design proposal
  2. Lilith implementation gate reviews (quality assurance)
  3. Wolfie narrowing and oversight (orchestration)
  4. Hephaestus implementation results and production migration
  5. Lilith final adversarial validation
  6. Wolfie closure and doctrine decisions
- **Resolution Status:** Closure decision by Wolfie (production migration execution completed)
- **Key Files:**
  - Question: `20260319_000000_hephaestus_p0_header_ingestion_design_channel66.md`
  - Final closure: `20260319_230000_hephaestus_remediation_execution_results.md`

---

#### Thread **1002**: Lupopedia Headers Source of Truth
- **Thread Number:** 1002
- **Title/Question:** "Lupopedia Headers Source of Truth"
- **Actor/Initiator:** Wolfie (actor_id: 1)
- **Status:** ✅ **ANSWERED** (9 artifacts)
- **Key Actors:** Wolfie (question/closure), Lilith (attack/review), Hephaestus (implementation evidence)
- **Conversation Pattern:**
  1. Wolfie question: headers as authority source
  2. Lilith attack on authority hierarchy
  3. Wolfie response with revised authority hierarchy
  4. Lilith adjudication
  5. Hephaestus implementation evidence
  6. Lilith implementation gate review
  7. Wolfie final closure
- **Resolution Type:** Canonical doctrine decision
- **Final File:** `20260319_233000_wolfie_lupopedia_headers_canonical_source_of_truth.md`

---

#### Thread **1003**: Collections vs Namespaces
- **Thread Number:** 1003
- **Title/Question:** "Structural Model: Collections vs Namespaces"
- **Actor/Initiator:** Athena (actor_id: ?)
- **Status:** ✅ **ANSWERED** (11 artifacts)
- **Key Actors:** Athena (strategy), Lilith (attack/review), Wolfie (narrowing/doctrine)
- **Conversation Pattern:**
  1. Athena structural model proposal
  2. Lilith attack on collections/namespaces model
  3. Athena response to attack
  4. Wolfie narrowing (decision ready)
  5. Hephaestus implementation implications analysis
  6. Wolfie doctrine update and execution
  7. Hephaestus post-doctrine implementation plan
  8. Hephaestus implementation start
- **Resolution:** Doctrine update executed; implementation plan in progress
- **Final File:** `20260319_233500_wolfie_collections_and_namespaces_system_structure.md`

---

#### Thread **1004**: Task Plan 001 Kickoff
- **Thread Number:** 1004
- **Title/Question:** "Task Plan 001 (System Threading Kickoff)"
- **Actor/Initiator:** Athena (actor_id: ?) / Lilith
- **Status:** ✅ **ANSWERED** (7 artifacts)
- **Artifact Count:** 7
- **Key Actors:** Lilith (QA/documentation), Athena (strategy/spec), Wolfie (directive), Hephaestus (thread migration)
- **Key Files:**
  - `20260317_224000_lilith_quality_assurance_a12.md`
  - `20260318_141109_athena_strategy_task_plan_001_kickoff.md`
  - `20260318_150000_wolfie_directive_task_plan_001_review.md`
  - `20260318_211805_hephaestus_thread_migration_redirect_thread_1004_to_channel_66.md` (migration artifact)

---

#### Thread **1005**: Single Field Versioning Model
- **Thread Number:** 1005
- **Title/Question:** "Versioning Model for LUPOPEDIA HEADERS (Single Field)"
- **Actor/Initiator:** Athena (strategy) / Wolfie (question)
- **Status:** ✅ **ANSWERED** (24+ artifacts showing extensive deliberation)
- **Artifact Count:** 24
- **Key Actors:** Athena (doctrine compliance), Hephaestus (implementation), Lilith (adversarial validation), Wolfie (narrowing/closure)
- **Conversation Pattern:**
  1. Athena doctrine compliance reviews
  2. Hephaestus canonical implementation results
  3. Lilith implementation gate reviews and adversarial validation
  4. Wolfie narrowing and doctrine enforcement
  5. Multi-pass contradiction resolution
  6. Hephaestus final completion with single-field versioning
  7. Lilith final adversarial validation
  8. Wolfie closure and doctrine lock
- **Resolution:** Doctrine locked; single-field versioning model enforced
- **Status Symbol:** ✅ Extensive deliberation, multiple validation passes, final closure

---

#### Thread **1006**: Stale Version Injection Fix
- **Thread Number:** 1006
- **Title/Question:** "Fix Stale Version Injection After Doctrine Lock"
- **Actor/Initiator:** Hephaestus (actor_id: 3)
- **Status:** ✅ **ANSWERED** (1 artifact)
- **Single File:** `20260320_140000_hephaestus_fix_stale_version_injection_after_doctrine_lock.md`
- **Type:** Bug fix / remediation artifact
- **Scope:** Addressing stale version injection issue discovered post-doctrine-lock

---

#### Thread **1007**: Enforcement Doctrine Version Correctness
- **Thread Number:** 1007
- **Title/Question:** "Enforcement Doctrine for version_when_written Correctness"
- **Actor/Initiator:** Athena (actor_id: ?)
- **Status:** ✅ **ANSWERED** (1 artifact)
- **Single File:** `20260320_150000_athena_enforcement_doctrine_for_version_when_written_correctness.md`
- **Type:** Doctrine/policy enforcement artifact
- **Scope:** Defining enforcement rules for version_when_written field accuracy

---

#### Thread **1017**: Thread 1006 Reconciliation
- **Thread Number:** 1017
- **Title/Question:** "Consistency and Reconciliation for Thread 1006"
- **Actor/Initiator:** Hephaestus / Wolfie
- **Status:** ✅ **ANSWERED** (2 artifacts)
- **Artifacts:**
  - `20260318_211805_hephaestus_thread_migration_redirect_thread_1017_to_channel_66.md` (migration marker)
  - `20260318_230000_wolfie_consistency_thread1006_reconciliation.md` (consistency resolution)

---

#### Thread **1025**: Task Doc Continuity Update
- **Thread Number:** 1025
- **Title/Question:** "Task Doc Continuity Update 001 (Channel System Alignment)"
- **Actor/Initiator:** Cursor (actor_id: 102)
- **Status:** ✅ **ANSWERED** (3 artifacts)
- **Artifacts:**
  - `20260318_175542_cursor_review_task_doc_continuity_update_001_channel-system-continuity-alignment.md`
  - `20260318_211805_hephaestus_thread_migration_redirect_thread_1025_to_channel_66.md` (migration marker)
  - `20260319_170000_wolfie_closure_task_doc_continuity_update_001.md` (closure decision)

---

#### Thread **1027**: Task Channel Migration Audit
- **Thread Number:** 1027
- **Title/Question:** "Task Channel Migration Audit 001 (Channel 66 Mapping Canonicality)"
- **Actor/Initiator:** Hermes (actor_id: 15)
- **Status:** ✅ **ANSWERED** (3 artifacts)
- **Artifacts:**
  - `20260318_155033_hermes_report_thread_channel_mapping.md` (initial report)
  - `20260318_211805_hephaestus_thread_migration_redirect_thread_1027_to_channel_66.md` (migration marker)
  - `20260319_235910_wolfie_answer_task_channel_migration_audit_001_channel66_mapping_canonicality.md` (answer/closure)
- **Note:** Hermes routing metric; pattern shows thread consolidation during channel 66 setup

---

#### Thread **1038**: Channel 66 Question Model
- **Thread Number:** 1038
- **Title/Question:** "Task Channel 66 Question Model"
- **Actor/Initiator:** Wolfie (actor_id: 1)
- **Status:** ✅ **ANSWERED** (2 artifacts)
- **Artifacts:**
  - `20260319_235500_wolfie_directive_task_channel66_question_model_001.md` (question/directive)
  - `20260319_235900_wolfie_answer_task_channel66_question_model_001_question_container_model.md` (answer: question container model)
- **Significance:** Documents the question model architecture itself for channel 66

---

#### Thread **1047**: Global System Synchronization & Changelog Recovery
- **Thread Number:** 1047
- **Title/Question:** "Global System Synchronization and Changelog Recovery"
- **Actor/Initiator:** Wolfie (directive) / Thoth (implementation)
- **Status:** ✅ **ANSWERED** (9 artifacts)
- **Artifacts:**
  - `20260321_203000_wolfie_global_system_synchronization_lock_directive.md` (directive)
  - `20260322_080000_wolfie_controlled_system_synchronization_v10_directive.md` (revised directive)
  - `20260322_081500_thoth_changelog_recovery_4_0_85_implementation_report.md` (implementation report)
  - `20260322_131053_thoth_changelog_recovery_completion.md` (completion report)
  - `20260324_182600_cursor_thread_1047_legacy_index_reference.md` (legacy reference)
  - `20260324_ch66_all_answers_consolidated_in_4_0_87.md` (summary)
  - `20260324_ch66_fresh_unanswered_questions.md` (queue)
  - `20260324_ch66_resolution_database_truth_headers_generated.md` (resolution)
  - `20260324_ch66_resolution_single_field_versioning_enforcement_validated.md` (validation)
  - `20260324_ch66_session_summary_headers_implementation.md` (session summary)
- **Key Actors:** Wolfie (coordination), Thoth (knowledge/records), Cursor (consolidation)
- **Scope:** 4.0.85 changelog recovery; 4.0.87 consolidation and answers validation
- **Status:** Multi-artifact completion with final consolidation in 4.0.87

---

### Summary Statistics

| Aspect | Count |
|--------|-------|
| **Total Threads** | 16 |
| **Priority Threads (4.0.87)** | 4 (threads 1050–1053) |
| **Legacy Threads (Reference)** | 12 (threads 1001–1047, excluding priority) |
| **Threads with Answer/Resolution** | 16 (100%) |
| **Threads with Q&A Pattern** | 14 (threads 1001–1052) |
| **Single-Artifact Threads** | 2 (threads 1006, 1007) |
| **Heavy Deliberation Threads (10+ artifacts)** | 4 (threads 1001, 1003, 1005, 1047) |
| **Migration Marker Threads** | 3 (redirect artifacts in 1004, 1017, 1025, 1027) |
| **Total Artifacts (estimated)** | 100+ |

---

## Part 2: Actor Directory Structure and Organization

### Overview

The `lupo-actors/` directory is the **centralized hub for actor-specific resources**. Each actor has a **name-based** subdirectory (e.g., `wolfie/`, `lilith/`, `cursor/`) containing structured subdirectories for apps, tools, docs, database changes, APIs, dependencies, and prompts.

### Directory Structure Patterns

Each actor directory follows this canonical structure:

```
lupo-actors/{actor_slug}/
├── .metadata.yaml              # Actor metadata (role, layer, parent_actor_id)
├── agent.json                  # Agent configuration (agents only)
├── capabilities.json           # Agent capabilities (agents only)
├── properties.json             # Agent properties (agents only)
├── system_prompt.txt           # System prompt (agents only)
├── README.md                   # Actor documentation
├── soul/                       # CORE IDENTITY LAYER (high-authority actors only)
│   ├── config.yaml            # Runtime configuration (memory mode, limits)
│   ├── doctrine.yaml          # Doctrine references and review processes
│   └── traits.yaml            # Immutable identity traits
├── memory/                     # KNOWLEDGE & STATE LAYER
│   ├── cache/                 # TTL-based cached knowledge
│   ├── knowledge/             # Persistent knowledge base
│   └── logs/                  # Memory and decision logs
├── api/                        # API definitions and endpoints
├── apps/                       # Custom applications and scripts
├── db-changes/                # Database migrations and changes
├── docs/                       # Actor-specific documentation
├── logs/                       # Actor operation logs (system actors only)
├── needs/                      # Dependencies and requirements
├── prompts/                    # Behavioral instructions and prompts
├── relationships/              # Actor relationship definitions (high-authority)
├── sessions/                   # Session records (IDE agents)
├── skills/                     # Reusable agent skills and capabilities
├── tools/                      # Utility tools and scripts
├── www/                        # Web-accessible content
└── [optional] _conflicts_from_actor_id_X/  # Migration conflict resolution
```

### High-Hierarchy Actors (Soul + Memory)

**Soul** and **Memory** are foundational patterns for **core orchestration actors** (WOLFIE, LILITH, and possibly ATHENA):

#### **WOLFIE** (actor_id: 1)
- **Location:** `lupo-actors/wolfie/`
- **Role:** Primary orchestrator and coordinating agent
- **Soul Structure:**
  - **config.yaml** —Runtime configuration:
    - `write_mode: append_only_sessions` (immutable append-only session logging)
    - `memory_mode: filesystem_persistent` (persistent filesystem-based memory)
    - `cache_mode: ttl_files` (time-to-live file caching)
    - `max_parallel_tasks: 5` (concurrency limit)
    - `allow_unverified_knowledge_write: false` (strict knowledge verification)
    - `default_channel_id: 42` (default workspace)
    - `default_supervisor_slug: alice` (human supervisor fallback)
  - **doctrine.yaml** — Purpose & governance:
    - *Purpose:* "Workflow orchestration, delegation, and continuity enforcement"
    - *Non-negotiables:*
      - Preserve actor identity integrity and avoid drift
      - Route work through doctrine-defined personas and channels
      - Keep channel operations auditable and reconstructable
    - *Doctrine references:* AGENTS.md, MULTI_AGENT_COORDINATION_DOCTRINE.md, IDE_AGENT_CONTINUITY_PROTOCOL.md
    - *Review process:* `controlled_change_only` (strict change gating)
  - **traits.yaml** — Immutable identity:
    - Traits: `orchestrator`, `continuity_guardian`, `doctrine_aligned`
    - Immutable: `true` (cannot be changed)
- **Memory Structure:**
  - `memory/cache/` — TTL-based knowledge cache
  - `memory/knowledge/` — Persistent doctrine and decision knowledge
  - `memory/logs/` — Decision and delegation logs
- **Additional Layers:**
  - `relationships/` — Relationship definitions with other actors
  - `sessions/` — Delegation and orchestration session records

#### **LILITH** (actor_id: 2)
- **Location:** `lupo-actors/lilith/`
- **Role:** Non-interfering reviewer, critic, and adversarial validator
- **Soul Structure:**
  - **config.yaml** — (similar runtime configuration to WOLFIE)
  - **doctrine.yaml** — Governance and review doctrines
  - **traits.yaml** — Immutable identity traits (critic, non-interfering, validator)
- **Memory Structure:**
  - `memory/cache/` — Review findings cache
  - `memory/knowledge/` — Attack/validation patterns
  - `memory/logs/` — Review decision logs
- **.metadata.yaml** — Actor role definition:
  ```yaml
  actor:
    slug: "lilith"
    actor_type: "role"
    layer: 1
    is_primary: 1
    parent_actor_id: 0
  ```
  - **is_primary: 1** indicates Lilith is a Primary Coordination Persona (per AGENTS.md eleven-persona doctrine)

---

### Standard Actors (Memory Only, No Soul)

#### **CURSOR** (actor_id: 102) — IDE Lead Orchestration
- **Location:** `lupo-actors/cursor/`
- **Role:** IDE surface; lead orchestration faucet for cross-agent continuity
- **Directories:** `api/`, `apps/`, `db-changes/`, `docs/`, `logs/`, `needs/`, `prompts/`, `skills/`, `tools/`, `www/`
- **Note:** No `soul/` directory (not a primary coordination persona)
- **Note:** No `memory/` directory (IDE surface; human-directed)

#### **HERMESS** (actor_id: 15) — Event Routing & Messaging
- **Location:** `lupo-actors/hermes/`
- **Role:** Heuristic Event Routing & Messaging Exchange System
- **Metadata:** Only `.metadata.yaml` file
- **Purpose:** Routes channel artifacts to correct personas; generates executable prompts

#### **HEPHAESTUS** (actor_id: 3) — Implementation Executor
- **Location:** `lupo-actors/hephaestus/`
- **Role:** Code, docs, and schema implementation
- **Metadata:** `.metadata.yaml` file

#### **ATHENA** (actor_id: ?) — Strategy & Wisdom
- **Location:** `lupo-actors/athena/`
- **Directories:** `.metadata.yaml`, `docs/`, `prompts/`
- **Role:** Wisdom, strategy, and doctrine planning

#### **IDE AGENTS** (Surface Layer)
- **CURSOR-IDE** (actor_id: ?) — IDE surface variant
- **WINDSURF** (actor_id: 101)
- **WARP** (actor_id: 104)
- **KIRO** (actor_id: 100)
- **ANTIGRAVITY** (actor_id: 105)
- **ZENCODER** (actor_id: 106)

---

### Actor-Agent-Department Relationship Patterns

#### **1. Primary Orchestration Personas** (Canonical Eleven)

These are **singleton active agents** per doctrine:

| Actor ID | Slug | Role | Memory | Soul | Relationships |
|----------|------|------|--------|------|---|
| 0 | system | Core platform | ✅ | ✅ | Parent of all |
| 1 | wolfie | Orchestrator | ✅ | ✅ | Central routing hub |
| 2 | lilith | Critic/QA | ✅ | ✅ | Non-interfering review |
| (others) | ... | (other personas) | ✅ | ✅ | Defined in doctrine |

#### **2. IDE Faucets** (Human Interfaces, Not Personas)

These are **human-facing IDE surfaces** that route to primary personas:

| Actor ID | Slug | Type | Parent/Partner | Role |
|----------|------|------|---|---|
| 102 | cursor | IDE faucet | system (0) | Lead orchestration IDE surface |
| 101 | windsurf | IDE faucet | wolfie (1)? | IDE faucet |
| 100 | kiro | IDE faucet | ? | IDE faucet |
| 105 | cascade | IDE faucet | ? | IDE faucet |
| 104 | warp | IDE faucet | ? | IDE faucet |
| 106 | zencoder | IDE faucet | ? | IDE faucet |
| 103 | antigravity | IDE faucet | ? | IDE faucet |

#### **3. Specialized Agents** (Non-Persona Implementers)

| Actor ID | Slug | Role | Memory | Soul | Relationship |
|----------|------|------|--------|------|---|
| 3 | hephaestus | Implementation executor | No | No | Routes to WOLFIE, THEMIS |
| 15 | hermes | Routing & messaging | No | No | Routes between personas |
| 16 | iris | Interface/integration | No | No | Technical routing support |
| 2 | lilith | Critic (also Primary Persona) | ✅ | ✅ | Reviews all implementation |

---

### Key Organizational Insights

#### **Soul (Identity & Doctrine)**
- **Purpose:** Core identity configuration and governance
- **Files:**
  - `config.yaml` — Runtime and memory behavior
  - `doctrine.yaml` — Purpose, non-negotiables, and doctrine references
  - `traits.yaml` — Immutable identity characteristics
- **Who Has It:** Only high-authority orchestration actors (WOLFIE, LILITH, SYSTEM)
- **Immutability:** Marked `immutable: true` to prevent drift

#### **Memory (Knowledge Persistence)**
- **Purpose:** Persistent knowledge base, cache, and decision logs
- **Directories:**
  - `memory/cache/` — TTL-cached knowledge
  - `memory/knowledge/` — Persistent knowledge base
  - `memory/logs/` — Decision and operation logs
- **Who Has It:** Primary Coordination Personas (all eleven) plus specialized agents requiring persistent knowledge
- **Persistence Model:** Filesystem-persistent (each actor owns its knowledge directory)

#### **Metadata Pattern**
- **Purpose:** Canonical actor definition in .metadata.yaml (always present)
- **Key Fields:**
  - `actor.slug` — Canonical name identifier
  - `actor.actor_type` — "role" (persona), "agent", "faucet", "system"
  - `actor.layer` — 0 (system), 1 (primary persona), 2+ (specialized/IDE)
  - `actor.is_primary` — 1 if Primary Coordination Persona, 0 otherwise
  - `actor.parent_actor_id` — Parent actor for delegated work; 0 = top-level

#### **Agent vs. Person Distinction**
- **Agent Config Files:** `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt`
  - Present in: IDE agents (cursor, windsurf, kiro, etc.) and specialized agents (hephaestus, hermes, iris)
  - Absent in: Primary Coordination Personas (WOLFIE, LILITH, SYSTEM) — they are **orchestration roles**, not agents
- **Role vs. Agent:** Primary Personas are **roles** (singleton execution identities); IDE agents are **faucets** (human-directed subprocesses)

#### **Dual-Identity Pairing** (Future-Ready)
- **Concept:** Actors can have `paired_actor_id` for human-agent combinations
- **Pattern:** Human identity (e.g., Captain) can be paired with agent identity (e.g., Cursor IDE)
- **Usage:** Hybrid execution mode where config/prompts can come from both human and agent directories
- **Storage:** Registry-based (not in session table); allows flexible pairing at runtime

---

### Full Actor Roster in lupo-actors/

**Name-based directories (canonical)** — Numeric symlinks (0/, 1/, 42/) may exist:

1. **Primary Coordination Personas:**
   - `system/` — System actor (0)
   - `wolfie/` — WOLFIE (1)
   - `lilith/` — LILITH (2)
   - `anubis/`, `lexa/`, `themis/`, `thoth/`, `athena/`, `maat/`, `janus/`, `rose/`, `heimdall/`, `seshat/` — (other nine personas, IDs to be verified in registry)

2. **IDE Faucets:**
   - `cursor/` — Cursor IDE agent (102)
   - `cursor-ide/` — Cursor IDE variant
   - `windsurf/`, `warp/`, `kiro/`, `antigravity/`, `cascade/`, `zencoder/`

3. **Specialized Agents:**
   - `hephaestus/` — Implementation (3)
   - `hermes/` — Routing (15)
   - `iris/` — Interface (16)
   - `doctor/`, `eris/`, `gemini-cli/`, `lupo/`, `metis/`, `rose/`, `trae/`, `vishwakarma/` — (other specialists)

4. **Non-Persona Support:**
   - `captain/` — Human user / supervisor role
   - `junie/` — Human user / human delegator
   - `codex/` — Documentation specialist

---

## Key Relationships and Patterns

### Thread → Actor Flow

Channel 66 demonstrates **question-driven architecture**:
1. **Cursor (IDE agent)** proposes production questions
2. **Wolfie (orchestrator)** validates and narrows scope
3. **Athena (strategy)** designs approaches
4. **Lilith (critic)** performs adversarial validation
5. **Hephaestus (builder)** implements decisions
6. **Thoth (knowledge)** documents and records
7. **Closure** by Wolfie/Athena with doctrine updates

### Soul-to-Script Execution Chain

```
WOLFIE soul (doctrine + config)
  ↓ delegates work
THOTH soul (knowledge authority)
  ↓ derives knowledge structure
LILITH soul (validation framework)
  ↓ produces review gate
Hephaestus (no soul; follows directives)
  ↓ executes implementation
Cursor (IDE surface; no soul)
  ↓ reports completion back to WOLFIE
```

### Metadata Authority Hierarchy

1. **`.metadata.yaml`** — Actor identity source of truth (always present)
2. **`soul/doctrine.yaml`** — Governance rules for high-authority actors
3. **`soul/config.yaml`** — Runtime behavior configuration
4. **`memory/`** — Persistent knowledge tied to actor identity (not mutable by others)
5. **`prompts/`** — Behavioral instructions (customizable by orchestrator)

---

## Summary

### Channel 66 Findings

- **16 total threads** covering system architecture, header ingestion, versioning, actor pairing, and release validation
- **4 priority threads** for 4.0.87 release (root archive, edge review, actor pairing, relevance validation)
- **12 legacy context threads** (still auditable; deprioritized for current release)
- **100% answered** — every thread has resolution artifacts and closure decisions
- **Multi-actor deliberation pattern** — Wolfie (orchestration), Athena (strategy), Lilith (criticism), Hephaestus (implementation), Thoth (records), Cursor (consolidation)

### Actors Directory Findings

- **Hierarchical structure:** Primary Coordination Personas (soul + memory) → Specialized agents (memory only) → IDE faucets (no persistent memory)
- **Soul/Memory separation:** High-authority actors own immutable identity (soul) + mutable knowledge (memory)
- **Metadata as identity:** `.metadata.yaml` is canonical source for actor role, layer, and relationships
- **Dual-identity ready:** Registry supports `paired_actor_id` for human-agent hybrid execution (future-ready)
- **30+ actors** in structured ecosystem ranging from core personas (1–10) to IDE surfaces (100–106) to specialists (15, 16, 42, etc.)

