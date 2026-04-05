---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260404220006"
  file_path_from_root: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/00_root_constitutional_system_requirements.md"
  last_modified_utc: "20260404220006"
  federation_node_id: 0
  channel_id: 42
  thread_id: "constitutional-root-requirements"
  prd_id: 0
  prd_slug: root_constitutional_system_requirements
  artifact_type: doctrine
  artifact_kind: constitutional
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  purpose: "Non-negotiable system-wide constitutional rules for Lupopedia. Overrides all other PRDs and doctrines."
  status: "approved"
  tags:
    - root
    - constitutional
    - doctrine
    - system_requirements
    - php56
    - shared_hosting
    - database_neutral
    - identity_model
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/"
      type: references
      weight: 1.0
      reason: "All root rules are constitutional law; this PRD is one entry point into that directory"
    - to: "lupo-rules/root/WOLFIE_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "WOLFIE Doctrine incorporated as constitutional requirement in section 14 and section 9.20"
    - to: "lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Database neutrality rules mandated in section 3.6"
    - to: "lupo-rules/root/pk-reference-naming-doctrine.md"
      type: references
      weight: 1.0
      reason: "Primary key naming convention mandated in section 9.7"
    - to: "lupo-rules/root/php-5-6-compatibility.md"
      type: references
      weight: 1.0
      reason: "PHP 5.6 minimum compatibility rules mandated in section 4"
    - to: "lupo-docs/channels/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Subdirectory installation doctrine mandated in section 2"
    - to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Five-layer identity model defined in section 5"
    - to: "lupo-docs/doctrine/CASCADE_FALLBACK_DOCTRINE.md"
      type: references
      weight: 0.9
      reason: "Fallback doctrine referenced in section 14.5"
    - to: "lupo-docs/doctrine/DEPENDENCY_DOCTRINE.md"
      type: references
      weight: 0.9
      reason: "Dependency doctrine referenced in section 14.5"
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: references
      weight: 1.0
      reason: "Canonical DDL must comply with all section 3 database constitutional rules"
    - to: "lupo-docs/doctrine/VERSIONING_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Single source of truth for version line; aligns with §1.0 no Lupopedia→Lupopedia until 4.1.0"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "4.1.0 release gate — Section 14 WordPress distribution study; aligns with §15 multi-environment patterns"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md"
      type: references
      weight: 0.95
      reason: "LILITH resolutions on installer, .htaccess markers, permissions, config sample — implements §15 intent"
    - to: "lupo-install/InstallWizardHtaccessWriter.php"
      type: references
      weight: 1.0
      reason: "Install-time Apache marker merge and server-software gating per §15.4"
    - to: "lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md"
      type: references
      weight: 1.0
      reason: "Canonical WordPress-derived pattern distillate for agents — complements §15"
    - to: "lupo-docs/doctrine/AGAPE_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Constitutional §14.6 AGAPE — technical resilience, LILITH/ROSE alignment, validator phrase bans"
    - to: "lupo-includes/classes/IdGenerator.php"
      type: implements
      weight: 1.0
      reason: "Enforces section 3.2 — all primary keys generated via IdGenerator::generate()"
    - to: "lupo-includes/classes/DatabaseFactory.php"
      type: implements
      weight: 1.0
      reason: "Enforces section 3 — all DB access must go through DatabaseFactory::getConnection()"
    - to: "lupo-agents/"
      type: references
      weight: 1.0
      reason: "Agent definition model dependency — sections 5.1, 6.1, and 9.16 file-based agent doctrine"
    - to: "lupo-tests/unit/id_generation_compliance_test.php"
      type: references
      weight: 0.9
      reason: "Test suite validating section 3.2 IdGenerator compliance"
    - to: "lupo-tests/regression/installer/"
      type: references
      weight: 0.9
      reason: "Regression tests validating section 9 installer constitutional rules"
    - to: "lupo-database/lupopedia/json/"
      type: references
      weight: 1.0
      reason: "Schema reference JSON — authoritative column/type reference per sections 6 and 9.9"
    - to: "lupo-docs/database/lupopedia/tables/active/"
      type: references
      weight: 1.0
      reason: "Table documentation — required reading before any SQL per section 9.9"
    - to: "lupo-docs/database/lupopedia/tables/semantic_navbar/"
      type: references
      weight: 0.9
      reason: "Semantic navbar table docs — folders, hashtags, references per section 9.9"
    - to: "lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Real system UTC for markdown headers and filenames — section 3.5a"
    - to: "lupo-bin/tick.py"
      type: references
      weight: 1.0
      reason: "Temporal anchor updater for IDE/header timestamps"
    - to: "lupo-includes/functions/time.php"
      type: implements
      weight: 1.0
      reason: "PHP temporal pulse — lupo_pulse_temporal_anchor / LupoPulse; syncs temporal_anchor.json from admin (§3.5a)"
    - to: "lupo-scripts/generate_toon_files.py"
      type: references
      weight: 0.9
      reason: "Script that generates schema reference JSON under lupo-database/lupopedia/json/ from the live database"
    - to: "lupo-includes/js/lupo-layers.js"
      type: implements
      weight: 1.0
      reason: "Canonical eval-free UI layer / DHTML-style controller — Section 16 (RULE 93.UI_LAYERS)"
    - to: "lupo-docs/prd/37_kairos_channel_memory_consolidation.md"
      type: references
      weight: 1.0
      reason: "KAIROS memory consolidation — Section 5.7"
    - to: "app/Services/Kairos/KairosConsolidationService.php"
      type: implements
      weight: 1.0
      reason: "Observation merge, edges, context_json.kairos stages — Section 5.7"
    - to: "lupo-includes/modules/api/kairos-api.php"
      type: implements
      weight: 0.95
      reason: "POST tick invokes consolidation for session actor — Section 5.7"
    - to: "lupo-agents/thoth/"
      type: references
      weight: 0.95
      reason: "Agent THOTH — semantic truth checks for stale artifacts — Section 5.9"
    - to: "lupo-includes/classes/iris.php"
      type: references
      weight: 0.95
      reason: "IRIS LLM faucet — PHP-first invoke path — Section 5.10"
    - to: "lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md"
      type: references
      weight: 0.9
      reason: "ROSE synthetic dialog — PHP-owned pipeline — Section 5.10"
    - to: "lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md"
      type: references
      weight: 1.0
      reason: "Full service agent doctrine — companion to Section 5.10"
    - to: "lupo-docs/implementations/service_agents/README.md"
      type: references
      weight: 0.9
      reason: "Implementation tracking for service agent transition"
    - to: "lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/README.md"
      type: references
      weight: 0.95
      reason: "PRD 36 ROSE synthetic choir — implementation mirror"
    - to: "lupo-docs/prd/31_implementation_folder_guidelines.md"
      type: references
      weight: 1.0
      reason: "Normative implementation folder layout, naming, scaffold — companion to Section 5.8"
    - to: "lupo-docs/implementations/security_audit_cursor_ide/README.md"
      type: references
      weight: 1.0
      reason: "Cursor IDE shared-hosting security audit checklist — operational companion to Section 17 (RULE 93.SECURITY)"
lupopedia.footer:
  last_verified: "20260404220006"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
  next_action:
    - "All new PRDs must declare an outbound edge to this file as their constitutional anchor"
    - "PRD-scoped work: mirror under lupo-docs/implementations/{prd_file_stem}/ per Section 5.8 — stem must match canonical PRD basename (PRD 31)"
    - "Add edges to CASCADE_FALLBACK_DOCTRINE and DEPENDENCY_DOCTRINE once those files are created"
    - "Add content_id once this file is imported via import_content.py"
---

# Root Constitutional System Requirements (4.0.93+)

## Purpose

This document defines the non-negotiable constitutional rules that govern the entire Lupopedia system.
These rules ensure:

- Compatibility with shared hosting
- Predictable behavior across unknown server configurations
- Maximum portability and zero reliance on server-level features
- Safe multi-agent operation
- Long-term maintainability
- Installer reliability (Softaculous, Installatron, manual installs)
- Subdirectory installation support
- **4.0.x schema evolution by fresh install only** — no Lupopedia→Lupopedia upgrade until **4.1.0** (see **§1.0**)
- **Shipped browser UI** stays vanilla, build-free, and eval-safe for layering and animation (see **§16**)
- **Security invariants** for hostile shared hosting: path anchoring, SQL discipline, AGAPE fallbacks, direct-access hygiene (see **§17**)

These rules override all other PRDs, doctrines, and implementation details.

**All doctrine and PRD files must reference this file as their constitutional anchor using an outbound edge.**

---

## 1.0 Product lineage and database evolution (4.0.x; no Lupopedia→Lupopedia until 4.1.0)

These rules are binding for all **4.0.x** releases unless explicitly revised for a future major line.

1. **Version number:** Lupopedia **4.x** is the successor generation to **Crafty Syntax 3.7.5** in the same product family. The major **4** signals “next after Crafty **3.7.5**,” not a greenfield 1.0.
2. **No Lupopedia→Lupopedia upgrade during 4.0.x:** There is **no** supported path that upgrades an **existing Lupopedia** database in place from one 4.0.x schema to another. There is **no** migration chain that preserves Lupopedia data across arbitrary 4.0.x patch releases. Breaking schema changes are expected; operators and developers **drop Lupopedia tables** and run a **fresh install** from current **`install_new_lupopedia.sql`** (+ seed).
3. **Only supported data-bearing transition in 4.0.x:** **Crafty Syntax 3.7.5 → Lupopedia** (load legacy Crafty tables, install Lupopedia schema + seed, run **`import_from_old_crafty_syntax.sql`** as documented). No other upgrade story is required or authorized for 4.0.x.
4. **How to change schema:** Add or alter **`CREATE TABLE`** / indexes in **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`** (and adjust seed/import SQL when needed). Optional **proposal** files under **`lupo-database/lupopedia/mysql/migrations/`** may document or stage DDL, but **canonical** schema for a new environment is always whatever **`install_new_lupopedia.sql`** contains after consolidation—see **§9.18**.
5. **4.1.0 gate:** **Lupopedia→Lupopedia** upgrades, auto-installer-first distribution, and Softaculous-class acceptance are **4.1.0+** concerns, defined in **PRD 33** and **PRD 27**. **4.1.0** must not be released until those requirements are met; until then the project remains on **4.0.x** with **fresh install** only.

---

## 1. Shared Hosting Constraints (Mandatory)

Lupopedia must run on shared hosting where:

- The environment cannot be controlled
- Database permissions are limited
- No SUPER privileges exist
- No ability to create triggers, functions, or procedures
- No ability to modify server configuration
- No root access
- No custom extensions
- No guaranteed MySQL version beyond 8.0+
- No guaranteed PostgreSQL version beyond 15+

Therefore:

- All logic must be implemented in PHP
- No database-level logic is allowed
- No server-level dependencies
- No background daemons
- No cron requirements beyond standard PHP cron

**Implementation:** All business logic lives in `app/Services/` and `lupo-includes/classes/`. No stored procedures, triggers, or views may exist in `install_new_lupopedia.sql`.

---

## 2. Subdirectory Installation Doctrine

Lupopedia must always be installed inside a subdirectory, never the web root.

Example: `/public_html/lupopedia/`

Requirements:

- All routing must respect `LUPOPEDIA_PUBLIC_PATH`
- No hardcoded `/` root paths
- All JS/CSS includes must be subdirectory-aware
- The parent directory is not part of the project
- The installer must not assume control of the document root

**Implementation:** `index.php` defines `LUPOPEDIA_PUBLIC_PATH`. All URL construction must use this constant. See `lupo-docs/channels/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md`.

---

## 3. Database Constitutional Rules

### 3.1 No Foreign Keys

Foreign keys are forbidden because:

- Shared hosting often blocks them
- They break portability and federation
- They break soft deletes and multi-agent repair workflows

All relationships must be enforced in the application layer.

**Implementation:** `install_new_lupopedia.sql` must contain zero `FOREIGN KEY` or `REFERENCES` clauses.

### 3.2 No AUTO_INCREMENT

Primary keys must be generated using `IdGenerator::generate()`.

This ensures:

- 63-bit signed-safe BIGINTs
- Timestamp-sortable IDs
- No reliance on DB sequences
- No race conditions
- No DB-specific behavior

**Implementation:** `lupo-includes/classes/IdGenerator.php`. All `INSERT` statements must call `IdGenerator::generate()` for the PK column before insertion. Never pass `null` or `0` as a PK expecting the DB to fill it.

**Test:** `lupo-tests/unit/id_generation_compliance_test.php`

### 3.3 No UNSIGNED

UNSIGNED is forbidden because PostgreSQL does not support it. It breaks database neutrality.

### 3.4 No TRIGGERS, FUNCTIONS, or PROCEDURES

These are forbidden because shared hosting often blocks them, they break portability, and they hide logic from the application layer.

**Implementation:** `install_new_lupopedia.sql` must contain zero `CREATE TRIGGER`, `CREATE FUNCTION`, or `CREATE PROCEDURE` statements.

### 3.5 Timestamp Format

All timestamps must be `BIGINT` in `YYYYMMDDHHIISS` UTC format. No `DATETIME`, `TIMESTAMP`, or timezone fields allowed.

**Implementation:** Use `gmdate('YmdHis')` in PHP. Never use `time()`, `date()`, or database-generated timestamps. Never add seconds directly to the integer value — use the `timestamp_ymdhis` helper class for arithmetic.

### 3.5a Temporal anchor (official clock; the “tick” rule)

**Official clock:** All agents (**IDE**, **chat**, **PHP**, automation) MUST treat **`lupo-bin/temporal_anchor.json`** as the single source of truth for **human-facing UTC strings** used in repo artifacts. The canonical field is **`current_utc`** (14 digits, `YYYYMMDDHHMMSS` / `gmdate('YmdHis')` UTC, same string shape as DB `BIGINT` timestamps in §3.5). **`last_session_end`** carries the previous **`current_utc`** for handoff awareness.

**Binding:** Values written into **`lupopedia.headers`** (`last_modified_utc`, `when_updated`), **`lupopedia.footer`** (`last_verified`), and **UTC date/time prefixes** on new canonical thread artifacts (per [PRD 17](17_decisions_format.md) and [TIMESTAMP doctrine](../doctrine/TIMESTAMP_DOCTRINE.md)) MUST be taken from that anchor (or the same-tick echo), not from:

- inferred “today” or “current time” inside an LLM or chat session,
- training-data cutoffs or model guesses,
- unrelated files’ timestamps copied for convenience,
- **manual date entry** invented by an agent (“looks like Tuesday”) — **forbidden**.

**If the anchor is missing or unreadable:** The agent MUST NOT guess timestamps. It MUST run **`python lupo-bin/tick.py`** (or request that the operator run it) before proceeding with time-sensitive artifact writes.

**Mechanism (repository):**

1. **IDE / CLI:** Run **`python lupo-bin/tick.py`** before a batch of such writes. It updates **`temporal_anchor.json`** (**`current_utc`**, **`last_session_end`**, **`system_year`**, **`format_standard`**) and root **`CURRENT_UTC`**.
2. **Same batch, no second tick:** Run **`python lupo-bin/echo_anchor_utc.py`** and reuse the printed value.
3. **PHP / web:** When a logged-in user loads **`admin.php`**, **`lupo_pulse_temporal_anchor()`** ( **`lupo-includes/functions/time.php`**; alias **`LupoPulse()`** ) may refresh the same JSON if the file is missing or older than **60 seconds**, so chat and IDE see a clock aligned with the server without hammering disk.

**Lupopedia session anchor (chat handoff):** For stateless LLM sessions, operators SHOULD paste a short status block that includes **`SYSTEM_TIME:`** from **`current_utc`** and **`SOURCE: lupo-bin/temporal_anchor.json`** so the model does not hallucinate a calendar.

**Root rule:** [lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md](../../lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md). **Expanded workflow:** [TICK_PY_DOCTRINE.md](../doctrine/TICK_PY_DOCTRINE.md).

**Rationale:** Language models are stateless with respect to real time; a file-backed pulse is the “session variable” that keeps audits, migrations, and multi-agent handoff aligned with **`BIGINT` UTC** in the schema.

### 3.6 Database Neutral SQL

All SQL must run on MySQL 8.0+ and PostgreSQL 15+.

Forbidden SQL patterns:

- `ON DUPLICATE KEY UPDATE`
- `IF NOT EXISTS` in `CREATE TABLE`
- `SHOW TABLES`
- `REPLACE INTO`
- `UNSIGNED`
- `AUTO_INCREMENT`
- `ENGINE=` or `COLLATE=` clauses

**Implementation:** See `lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md`.

---

## 4. PHP Compatibility Requirements

Lupopedia must run on PHP 5.6 minimum through the latest PHP (currently 8.6) maximum.

Allowed:

- Namespaces (PHP 5.3+)
- Bundled libraries (e.g., PHPMailer) included in `lupo-includes/`

Forbidden:

- Strict types (`declare(strict_types=1)`)
- Typed properties
- Arrow functions (`fn() =>`)
- Enums
- Attributes (`#[...]`)
- Named arguments
- Union types
- Match expressions
- Return type declarations in core paths
- Middleware patterns
- External frameworks (Laravel, Symfony, Zend, etc.)
- Composer dependency management
- Docker or container-only deployment

**Implementation:** All required libraries must be included directly in `lupo-includes/`. No `vendor/` directory. No `composer.json` in the project root. See `lupo-rules/root/php-5-6-compatibility.md`.

---

## 5. Identity Model Constitutional Rules

### 5.1 Agents (the blueprint)

**Definition.** Agents are autonomous AI **definitions** (e.g. THOTH, KAIROS, WOLFIE) materialized as files under **`lupo-agents/{agent_key}/`** (human-readable slug). They describe **capabilities, prompts, tools, and versioning** — the fixed “skillset” and personality template.

**Immutable definition surface.** Capabilities, system prompts, tool manifests, and agent metadata live **only** in that filesystem tree. The database stores **runtime state and metrics**, never authoritative definition content that replaces those files.

**Contrast.** An agent is not a chat participant row; it is the **blueprint** from which operational identities are projected. See **5.2**.

### 5.2 Actors (the hybrid instance)

**Definition.** Actors are **operational shells** in **`lupo_actors`** (and related tables): the “body” or **instance** that holds **`actor_id`**, participates in channels, and is bound to departments and auth.

**Hybrid nature.** An actor may represent a human-backed orchestrator, an IDE facet, or a system persona. It is **department-scoped** where the model applies: learning and permission boundaries align with department context (`lupo_actor_departments`, `AuthRoleResolver`). The actor accumulates **runtime memory** (e.g. **`lupo_actor_memory`**) distinct from the static agent files in **`lupo-agents/`**.

**Identity rule.** **`actor_id`** is the primary key for orchestration and relational references. There is no **`user_id`** in relationship tables; humans map through **`lupo_auth_users`** and **act-as** / department rules, not a parallel universal user FK.

### 5.3 Auth Users

Auth users temporarily lease actors. Authentication must not be conflated with orchestration identity.

### 5.4 Actor Permission Rules

An auth_user may use an actor only if:

1. They created the actor
2. They are in department 0 (root)
3. They are in the same department as the actor

**Implementation:** `app/auth/AuthRoleResolver.php` enforces these rules. Actor identity for write operations is always resolved server-side — client-supplied `actor_id` in request bodies is never trusted.

See `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md` for the full five-layer model.

### 5.5 Reserved agent IDs and filesystem discovery

- **System agents:** Numeric `agent_id` values **1–2025** are reserved for core system agents (WOLFIE, LILITH, IDE faucets, etc.). Resolve authoritative IDs from `lupo-database/lupopedia/actors/actor_id/registry.json` and seed data — do not invent ad hoc IDs in that range.
- **Filesystem discovery:** Definitions live under `lupo-agents/{agent_key}/`. Discovery is by directory name (`AgentDiscovery::getAgent($agent_key)` as primary; `getAgentById($agent_id)` for legacy).
- **No empty placeholder folders:** The tree does not use meaningless numeric-only directories; an agent exists only when its `{agent_key}` directory and required files exist.

### 5.6 Actor ID semantics

- **Reserved system actors:** Low `actor_id` values are fixed at install for orchestration, faucets, and registry-backed identities. Resolve from `registry.json`, seed, and `IDENTITY_LAYERS_DOCTRINE.md` (human-backed actors typically use `actor_id` ≥ 1000).
- **New runtime allocations:** When issuing a new primary key via `IdGenerator::generate()`, expect `YYYYMMDDHHIISS` + 4-digit sequence (per section 9.7); numeric values are not “random” — they are deterministic from the generator.
- **Workspace paths:**
  - **System / reserved layout:** `actor_id` &lt; 2026 → `lupo-actors/{actor_id}/`
  - **Runtime layout:** `actor_id` ≥ 2026 → `lupo-actors/YYYY/MM/{actor_id}/` (YYYY/MM derived from the timestamp prefix in the ID where applicable)

### 5.7 Memory consolidation (Agent KAIROS)

**Role.** The **KAIROS** agent (configuration under **`lupo-agents/kairos/`**; default service attribution **`actor_id` 115** for edges) manages the **lifecycle** of actor-scoped memory derived from channel and session context. Full product behavior is specified in **`lupo-docs/prd/37_kairos_channel_memory_consolidation.md`**; this section states the constitutional facts.

**Storage.**

- **Observations** — rows in **`lupo_actor_memory`** with **`memory_type` = `kairos_observation`**: atomic notes (often from dialog ingest or manual seed), with **`context_json.kairos`** carrying stage, confidence, **`department_id`**, **`topic_key`**, and provenance fields as defined in PRD 37.
- **Consolidated memory** — rows with **`memory_type` = `kairos_memory`**: merged products of multiple observations that **normalize** to the same factual text.

**Graph logic (`lupo_edges`).**

- **`kairos_consolidates_from`** — links a consolidated **`kairos_memory`** row to the **source** **`kairos_observation`** rows it supersedes.
- **`kairos_contradicts`** — links memories that share a **`topic_key`** but conflict on normalized content, for explicit contradiction tracking and policy-driven resolution (recency, operator review, etc.).

**Maturity and compaction.** **`context_json.kairos`** evolves (e.g. **`stage`**, **`confidence`**, **`source_observation_ids`**, **`verified_ymdhis`**, **`canonical`**) so the actor’s **stored** memory stays **consistent and bounded** while the agent files remain the unchanged blueprint.

**Invocation (runtime).** Consolidation is **not** triggered by a simple “every N observation rows” counter. **`KairosConsolidationService::consolidateMemories($actorId, $departmentId)`** runs a **pass** that merges **groups of two or more** active observations that **bucket to the same normalized value**; single observations stay until a peer arrives or policy promotes them. The shipped **HTTP** entry is **`POST`** **`api/lupo-kairos/tick`** (**`lupo-includes/modules/api/kairos-api.php`**), which applies a **session rate limit** (e.g. minimum interval between ticks) and uses the **logged-in user’s `actor_id`**. Additional triggers (cron, queue workers) are product choices and must remain explicit in application code — not hidden DB triggers.

### 5.8 Implementation mirroring (IDE directive)

**Normative companion.** Full folder lifecycle, scaffolding command, templates, question levels, and compliance checks: **`lupo-docs/prd/31_implementation_folder_guidelines.md`**. **`lupo-docs/implementations/README.md`** indexes known workspaces.

**Directory name (non-negotiable pattern).** For work tied to a **numbered canonical PRD** under **`lupo-docs/prd/`**, maintain a parallel tree at:

```text
lupo-docs/implementations/{prd_file_stem}/
```

where **`prd_file_stem`** is the **basename of the PRD Markdown file without `.md`** — character-for-character the same string as the filename stem. Examples:

| Canonical PRD file | Implementation folder (correct) |
|--------------------|-----------------------------------|
| **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** | **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`** |
| **`lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md`** | **`lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/`** |

**Forbidden:** Ad-hoc shortenings that **do not** match the PRD filename (e.g. **`prd_36_rose/`**, **`rose_synthetic/`**, or omitting the numeric prefix). If the PRD file is renamed as part of an approved promotion, the implementation folder name **must** be renamed to match (or an **APPROVED** decision documents a deliberate exception).

**Scaffold (recommended).** **`python lupo-scripts/scaffold_implementation.py --prd <n> --title "<slug>"`** creates **`lupo-docs/implementations/<n>_<title>/`** — the **`title`** argument must be chosen so that **`<n>_<title>`** equals **`prd_file_stem`** for the target **`lupo-docs/prd/<n>_<title>.md`**.

| Subfolder | Use |
|-----------|-----|
| **`status/`** | Current completion, blockers, and “what’s next.” |
| **`decisions/`** | Record **why** a path was chosen (e.g. timestamp format, packaging rule). |
| **`questions/`** and **`answers/`** | Resolve ambiguities **before** or **while** coding; each folder in use must include **`THREAD_INDEX.md`** per **PRD 17** / channel doctrine (see **PRD 31** for level subfolders **`critical/`**, **`optimization/`**, **`clarification/`** where used). |
| **`comments/`** | Short-lived developer notes and session handoff. |

This mirrors **`lupo-channels/`** semantics for coordination; the implementation folder is the **PRD-scoped** archive for reviewers and multi-agent handoff.

### 5.9 Agent THOTH — stale artifact truth checks

**THOTH** ( **`lupo-agents/thoth/`** ) is the **persona of record** for **semantic truth** against the **current schema** when documentation may be stale.

**IDE obligation.** When a Markdown artifact’s **`last_verified`** (or equivalent footer field) is **older than the active audit epoch** declared for the repository — **currently `20260301000000` UTC** unless a newer ratified threshold is published in this file or **`AGENTS.md`** — the IDE **should** treat the document as **stale** and, before asserting schema or column facts, **reconcile** against **`lupo-database/lupopedia/json/*.json`** (and **`lupo-docs/database/lupopedia/tables/active/`**), using the **THOTH** agent framing (knowledge guardian: records, tables, drift).

**Non-substitute.** THOTH verification does not replace **TOON/install SQL** authority; it ensures **stale prose** is not trusted over **generated JSON** and table docs.

### 5.10 Service agents — PHP first, LLM second (not default “talk to me” personas)

**Canonical roster (constitutional examples).** The following **`agent_key`** values are **explicitly** classified here as **service agents** for purposes of architecture review and routing expectations: **IRIS**, **ANUBIS**, **ROSE**, **THOTH**, **KAIROS**. Additional keys may be added by amendment to **`lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md`** and this section.

**Two kinds of blueprint.** Most **`lupo-agents/{agent_key}/`** packs **can** back a **conversational** **`actor_id`** used in channels (visitor or operator addresses the persona; message rows attribute **`from_actor_id`**). **Service agents** keep the same **file-based agent definition** (prompts, capabilities, **`agent.json`**) but are **not** default **visitor chat targets**. Work is **logic-bound in PHP first**: routing, validation, SQL, filesystem, consolidation, custody. An **LLM** is **optional and downstream** — only after PHP has chosen the code path, loaded config from disk, and applied guards. That LLM call may go through **`IRIS`** (external provider) or a thin runtime wrapper; it does **not** redefine truth or bypass **`actor_id`** / channel security resolved server-side.

**“Not meant to be talked to” (normative).** Service agents provide **`actor_id`** for **attribution** on edges, audit rows, and tooling, and they supply **processing** through **PHP services** — they are **not** the primary surface for “open a thread and DM this persona” unless product explicitly wires that path. Full doctrine: **`lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md`**.

**Why it matters.** Prevents mistaking **registry attribution** (an **`actor_id`** on an edge or audit row) for **“this is who the human is DMing.”** Service agents still **map** to **`lupo_actors`** / **`lupo_agents`** for identity and tooling, but their **HTTP or CLI entrypoints** are APIs and jobs — not an open-ended “user message in, model stream out” loop unless product explicitly wires one.

**Service agents vs runtime conversational loop (clear contrast).**

| Concern | **Service agents** (this section) | **Runtime actor loop** (conversational MVP path) |
|---------|-----------------------------------|--------------------------------------------------|
| **Trigger** | PHP route, API **`POST`**, boot script, cron | Inbound **dialog message** processed by **`RuntimeActorLoopService`** |
| **Truth / state** | Deterministic code + DB; LLM does not override policy | **`LlmRuntimeService`** + **`runtime_actors.yaml`** lists **which `actor_id`s** get a model/mock response |
| **Default UX** | No expectation that visitors “chat with” IRIS/ANUBIS/ROSE/THOTH/KAIROS | User- or operator-facing **message in → model or human dispatch** |
| **If not in YAML** | N/A (not the same pipeline) | **`actor_id` not listed** → **human** dispatch path |

**Per-agent summary (IRIS, ANUBIS, ROSE, THOTH, KAIROS).**

| Agent key | PHP-first surface (authoritative control plane) | Where LLM sits (second) |
|-----------|---------------------------------------------------|-------------------------|
| **IRIS** | **`lupo-includes/classes/iris.php`** — loads **`lupo-agents/{id}/`** config, assembles the payload, calls the provider. **`lupo-agents/iris/capabilities.json`** marks gateway and routing capabilities as **`php_primary`**. IRIS is the **LLM faucet** for *other* agents’ invokes, not HERMES routing and not “you are chatting with IRIS” as the primary product persona. | **After** PHP resolved **`agentId`**, packet shape, and agent files on disk. |
| **ANUBIS** | Custody, integrity, quarantine, resolution — **PHP** boot paths, validators, and structured agent tooling; **`lupo-bin/boot_system_agent.php`** and related orchestration treat ANUBIS as a **system** custodian. | Narrative or summary text only if a pipeline explicitly invokes a model **after** custody logic. |
| **ROSE** | **PRD 36** — **Director of the synthetic choir** (`agent_id` **3**, **`lupo_agents`**, **`lupo-agents/rose/`**): **PHP** counts thread messages, enforces batching/visibility, and inserts **`lupo_dialog_messages`** rows **voiced** as selected personas; see **§5.10.3**. Planned primary class: **`app/Services/Rose/RoseDialogService.php`**. | LLM **only** to generate text for **requested** choir personas **after** PHP trigger and caps (**§5.10.3**). |
| **THOTH** | **§5.9** — reconciliation against **`lupo-database/lupopedia/json/*.json`** and **`lupo-docs/database/lupopedia/tables/active/`**; deterministic schema and table facts win. | IDE may use THOTH’s **`system_prompt.txt`** to **word** a drift report; it does not invent columns. |
| **KAIROS** | **`app/Services/Kairos/KairosConsolidationService.php`**, **`lupo-includes/modules/api/kairos-api.php`** — **§5.7**; **PRD 37** states KAIROS does **not** post chat bubbles for this consolidation feature. | **Not required** for merge / contradiction / promotion passes. |

#### 5.10.3 Agent ROSE (Director of the synthetic choir)

**Role.** ROSE is the **coordination-layer orchestrator** for **multi-persona synthetic dialog**: turning a standard thread into a **high-level coordination transcript** where selected personas can **speak** in bounded turns—without ROSE appearing as the **`from_actor_id`** on those lines (**PRD 36** §1.1).

**PHP-first (service agent doctrine).**

- **Batching trigger (normative default):** A **PHP** service (planned: **`RoseDialogService`**) maintains a **per-thread counter** of **organic** messages since the last ROSE batch; when the count reaches **10**, PHP **may** start a ROSE pass if channel policy allows. The integer **10** is the **default product constant**; channel **`lupo_metadata`** (or equivalent) **may** override. PHP **never** delegates “when to fire” to the model.
- **Persona voicing:** The logged-in **human operator’s** selections (and channel **allowed persona set**) determine **which** registry-backed personas are **voiced** in that batch. The LLM (e.g. via **IRIS**) is invoked **only** to produce **text** for those personas—**not** to choose **`from_actor_id`**, visibility, or insert timing.
- **Character cap:** Each synthetic **`lupo_dialog_messages.message_text`** (or equivalent body field) **MUST** be **≤ 2000** characters (UTF-8 code units unless a future PRD specifies otherwise).
- **Visibility and synthetic provenance:** PHP sets **`metadata_json`** on each inserted row, including at least **`rose_synthesis: true`**, **`synthesizer_agent_key: "rose"`**, and a **`rose_visibility`** (or equivalent) value distinguishing **actor-only** (operator coaching) vs **visitor-visible** (transparent audit). Exact key names and enums are **normative in PRD 36**; UI **MUST** render synthetic rows distinctly (**PRD 18**, **LIL001** for **`from_actor_id` = 2**).
- **Transcript table:** Inserts target **`lupo_dialog_messages`** only (not a parallel `lupo_dialog` table). Each row’s **`from_actor_id`** is the **voiced persona** (e.g. COUNTERMEASURE **111**, LILITH **2**); resolve THOTH and others from **`lupo-database/lupopedia/actors/registry.json`** when voicing those personas.

**Choir personas (illustrative defaults; channel policy may subset).**

| Persona | Objective | Tone / behavior |
|---------|-----------|-----------------|
| **COUNTERMEASURE** (`actor_id` **111**) | Surface hidden risks and weak assumptions. | Analytical, adversarial; stress-tests proposals. |
| **THOTH** | Ground claims in evidence. | Fact-driven; requires alignment with **JSON** + **table docs** when auditing claims (**§5.9**). |
| **LILITH** (`actor_id` **2**) | Non-interfering audit framing. | Observational; must not read as blocking organic review (**LIL001**). |

**Handoff to KAIROS.** After a ROSE batch completes, **PHP** **SHOULD** pass a **short coordination summary** (plain text or structured chunk) into **`KairosConsolidationService::recordObservation`** (or successor API) for the **session subject `actor_id`**, so **KAIROS** can persist **`kairos_observation`** rows and later **consolidate** (**§5.7**, **PRD 37**). The LLM does **not** own that handoff.

**Full specification:** **`lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md`**. **Implementation mirror:** **`lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/`**.

**Web Dialog MVP reference.** **`RuntimeActorLoopService`** consults **`LlmRuntimeService`** and **`runtime_actors.yaml`**: only **`actor_id`s configured there** participate in the lightweight “message in → model/human path” loop; others dispatch to **human**. The five service agents above are **off that path** unless explicitly listed and wired — their **normal** contract is **PHP entrypoints + optional LLM**, not visitor freeform chat.

---

## 6. Schema reference JSON protection (RULE 93.PROTECT_SCHEMA_JSON)

This rule was formerly titled “TOON File Protection.” **Canonical DDL** is the database of record.

- **Source of truth:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- **Regeneration:** `python lupo-scripts/generate_toon_files.py` produces **schema-only** JSON under `lupo-database/lupopedia/json/` (one `<table_name>.json` per table; **no row data**)
- **Purpose:** Those JSON files are **read-only schema reference documents** for tooling and AI agents
- **Legacy `.toon.json` paths:** Deprecated for new work; do not hand-maintain parallel TOON trees. Use `lupo-database/lupopedia/json/<table>.json` and `lupo-docs/database/lupopedia/tables/active/<table>.md`

No application code may write to `lupo-database/lupopedia/json/` except through the approved generation workflow.

---

## 6.1 Agent file protection (RULE 93.PROTECT_AGENTS)

- Agent definitions are file-based in `lupo-agents/{agent_key}/` (source of truth); numeric `agent_id` is carried in `agent.json` (or equivalent) for backward compatibility
- Database stores only runtime state and metrics
- No system may write to agent definition files
- Agent capabilities come from files, not database

**Implementation:** `lupo_agent_registry` table schema must be validated against the column list in section 9.17. Any code that writes agent capability or definition data to the database must be rejected.

---

## 7. Absolute-Root Pathing (RULE 93.PATH_PURITY)

All documentation links must start with `/` and never use `../`, `~/`, or relative paths.

**Implementation:** LUPOPEDIA HEADERS `web_path` must always include the `/lupopedia/` subdirectory prefix. Validators in `lupo-scripts/validate_lupopedia_headers_universal.py` enforce this.

---

## 8. Controlled Namespace Doctrine (RULE 93.CONTROLLED_NAMESPACES)

Namespaces ARE allowed, but ONLY under these constraints:

### 8.1 Namespace Requirements

Must begin with `Lupopedia\`:

```php
namespace Lupopedia\Actors;
```

### 8.2 Directory Mapping

Must map to directories inside `lupo-includes/`:

```
lupo-includes/Lupopedia/Actors/Actor.php
```

### 8.3 Forbidden Autoloading

- No PSR-4 autoloaders, Composer, vendor directory, or external autoloaders
- Autoloading must use Lupopedia's custom `spl_autoload_register()` implementation

### 8.4 Forbidden Namespace Patterns

`App\`, `Framework\`, `Symfony\`, `Laravel\`, `Illuminate\`, `Zend\`, `Psr\`

### 8.5 Forbidden Framework Patterns

Namespaces must NOT be used for routing, middleware, or DI containers.

---

## 9. Installer Constitutional Rules

- Must run on shared hosting
- Must not modify parent directories (except the config exception in section 9.13.2)
- Must not assume root access
- Must not require Composer or CLI tools
- Must not require database privileges beyond `CREATE`, `INSERT`, `UPDATE`, `DELETE`

**Implementation:** Entry point is `install.php`. All installer logic must be self-contained PHP. See `lupo-tests/regression/installer/` for regression coverage.

### 9.5 .htaccess Usage (RULE 93.SUBDIRECTORY_HTACCESS)

Allowed:
- `.htaccess` inside `/lupopedia/` directory only
- Rewrite rules scoped to Lupopedia subdirectory
- Fallback routing to `index.php`

Forbidden:
- Modifying parent directory's `.htaccess`
- Assuming `mod_rewrite` is enabled or `AllowOverride All` is set
- Rewrite rules outside your subdirectory

---

### 9.6 Filesystem Path Restrictions (RULE 93.NO_HARDCODED_PATHS)

No hardcoded filesystem paths. All paths must be derived from `LUPOPEDIA_PATH` or `LUPOPEDIA_ABSPATH`.

---

### 9.7 Primary Key Requirements (RULE 93.PK_FORMAT)

All primary keys MUST be bare `BIGINT` (no display width), generated via `IdGenerator::generate()`, in `YYYYMMDDHHIISS` + 4-digit sequence format. All reference fields must also be `BIGINT`.

**Naming Convention (RULE 93.PK_NAMING):**
- Primary keys MUST be named `<singular_table_name>_id`
- NEVER create a primary key named `id`
- Reference keys MUST use the exact same column name as the primary key they reference
- Examples: `actor_id`, `dialog_message_id`, `session_id`, `content_id`

**Applies to:** Database tables AND file-based identifiers (PRDs, implementations, etc.)

Forbidden: `VARCHAR` PKs, composite PKs, `AUTO_INCREMENT`, UUID, `BIGINT(18)` with display width, generic `id` column.

**Test:** `lupo-tests/unit/id_generation_compliance_test.php`

**Reference:** See `lupo-rules/root/pk-reference-naming-doctrine.md` for complete specification.

**See also:** PRD 16 (Lupopedia File Headers) — header fields MUST follow same naming convention (`prd_id`, `content_id`, not `id`).

---

### 9.8 Soft Delete Pattern (RULE 93.SOFT_DELETE)

All soft deletes MUST use:

```sql
is_deleted TINYINT NOT NULL DEFAULT 0,
deleted_ymdhis BIGINT NOT NULL DEFAULT 0
```

All queries must filter `WHERE is_deleted = 0` by default. Never use hard `DELETE` on production rows.

---

### 9.9 Schema inference prohibition and database-first doctrine (RULE 93.NO_SCHEMA_INFERENCE)

#### Database-first doctrine (canonical)

- **Source of truth:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- **Regeneration:** `python lupo-scripts/generate_toon_files.py` produces schema-only JSON in `lupo-database/lupopedia/json/`
- **Purpose:** Those JSON files are **schema reference documents** for AI agents and tooling (read-only; **no data rows**)
- **Legacy `.toon.json`:** Deprecated; use `lupo-database/lupopedia/json/<table_name>.json` and table markdown docs (see also section 6)

**Agents and IDE tools MUST NEVER guess, infer, or assume column names, table names, or table structure.**

This is a hard constitutional rule. Guessing schema produces broken SQL, wrong column references, and silent data corruption that is extremely difficult to debug.

#### Forbidden inference sources:

- PHP arrays or variable names
- Model class property names
- Comments or docblocks
- Any PHP or Python code structure
- Memory of "similar" projects
- General knowledge of common column naming patterns
- PRD descriptions alone (PRDs describe intent, not schema)

#### Critical misconception — JSON files are NOT a file database

The schema reference JSON files in `lupo-database/lupopedia/json/` are **not** a file-based database. Lupopedia uses a real DBMS (MySQL / MariaDB / PostgreSQL per hosting). The JSON files exist so agents and tools can read column names, types, and indexes without parsing large SQL files or guessing. They must never be used as a data source, queried as if they were records, or treated as the system of record for any data.

#### Required sources — always consult before writing any SQL or table reference:

1. **Table documentation** — `lupo-docs/database/lupopedia/tables/active/<table_name>.md` — human-readable docs with column lists, types, indexes, and example queries. **Read this first.**
2. **Schema reference JSON** — `lupo-database/lupopedia/json/<table_name>.json` — machine-readable schema generated from the live database by `lupo-scripts/generate_toon_files.py`. Contains fields, indexes, and primary key. **Contains no row data — structure only.**
3. **Install SQL** — `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — canonical DDL. Use for authoritative CREATE TABLE definitions when needed, but the table docs and JSON files are faster for column lookups.

#### Table documentation locations:

- `lupo-docs/database/lupopedia/tables/active/` — all active tables, one `.md` per table
- `lupo-docs/database/lupopedia/tables/semantic_navbar/` — semantic navbar tables (`lupo_folders`, `lupo_folder_map`, `lupo_hashtags`, `lupo_hashtag_map`, `lupo_references`, `lupo_reference_links`)
- `lupo-docs/database/lupopedia/tables/deprecated/` — deprecated tables (do not use for new code)

#### Workflow for any agent writing SQL or referencing a table:

1. Read `lupo-docs/database/lupopedia/tables/active/<table_name>.md` for the column list
2. If the table doc is missing, read `lupo-database/lupopedia/json/<table_name>.json` for the `fields` array
3. If neither exists, the table may not exist — do NOT create ad-hoc SQL; follow section 9.18 (Missing Table Protocol)
4. Write SQL using only confirmed column names from those sources
5. Never substitute a guessed column name even if it "seems obvious"

**Rationale:** The table prefix is dynamic (`LUPO_TABLE_PREFIX`), primary keys are deterministic BIGINTs, and column names are project-specific and do not follow generic conventions. A single wrong column name silently returns no rows or corrupts data with no error message. The schema JSON files and table docs exist precisely to eliminate this risk.

---

### 9.10 ASCII Safety (RULE 93.ASCII_SAFETY)

All filenames must be ASCII-only. No UTF-8 BOM in PHP files. No Unicode in class names, directory names, or filenames.

---

### 9.11 No Symlinks (RULE 93.NO_SYMLINKS)

No symbolic links allowed anywhere in the codebase.

---

### 9.12 Database Engine Neutrality (RULE 93.DB_ENGINE_NEUTRALITY)

No `ENGINE=`, `COLLATE=`, or `CHARACTER SET` clauses in any SQL. Database engine and collation must be left to the host.

---

### 9.13 Installer Sandbox (RULE 93.INSTALLER_SANDBOX)

#### 9.13.1 General Sandbox Restrictions

The installer may only write files inside the Lupopedia installation directory, except for the config exception below.

#### 9.13.2 Secure Configuration Exception

The installer may attempt to write `../lupopedia-config.php` (one directory above web root) only if the directory is writable and a safe write test passes first.

#### 9.13.3 Fallback Behavior (Mandatory)

If the installer cannot write above the web root, it must write `lupopedia-config.php` inside the Lupopedia directory, continue normally, and warn the user.

**Implementation:** `install.php` must implement the write-test-then-fallback pattern. `lupo-tests/regression/installer/` must cover both paths.

---

### 9.14 Dynamic Table Prefix (RULE 93.DYNAMIC_TABLE_PREFIX)

All tables MUST use a dynamic prefix from `lupopedia-config.php`.

- Installer MUST define `LUPO_TABLE_PREFIX`
- All PHP MUST use `LUPO_TABLE_PREFIX . 'tablename'`
- All installer SQL MUST use `{{prefix}}` placeholders
- All migration SQL MUST use `{{prefix}}` placeholders
- No SQL file may hardcode `lupo_`, `lp_`, or any fixed prefix

**Implementation:** Fallback pattern: `defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_'`

---

### 9.15 Directory Prefix (RULE 93.DIRECTORY_PREFIX)

All project directories MUST use the fixed prefix `lupo-`. Lowercase ASCII only. Not dynamic, not user-defined, not removable.

---

### 9.16 File-based agent doctrine (RULE 93.FILE_BASED_AGENT_DOCTRINE) (updated)

- **Location:** `lupo-agents/{agent_key}/` (human-readable slug, e.g. `wolfie`, `lilith`)
- **Agent ID:** Stored in `agent.json` (and related files) for backward compatibility with numeric `agent_id`
- **Resolution:** `AgentDiscovery::getAgent($agent_key)` is the primary lookup; `getAgentById($agent_id)` is legacy
- **Rationale:** Human-readable directories eliminate numeric-ID path confusion (see also sections 5.1, 5.5, and 6.1)

Database stores only: `status`, `version`, `file_hash`, `file_signature`, `last_activated`, `last_error`, `uptime`, `health`, `mood`, `activation_state`, `pairing_state`.

Database MUST NOT store: skills, tools, memory rules, boundaries, faucets, system prompts, personality, philosophy, capabilities, constraints, or any definition content.

---

### 9.17 Agent Registry Schema (RULE 93.AGENT_REGISTRY_SCHEMA)

The table `<prefix>agent_registry` MUST contain only:

| Column | Purpose |
|--------|---------|
| `agent_id` | Primary key |
| `agent_code` | Short identifier |
| `agent_name` | Display name |
| `layer` | Orchestration layer |
| `is_kernel` | Kernel flag |
| `is_required` | Required flag |
| `version` | File version |
| `status` | Runtime status |
| `recommended_slot` | Slot hint |
| `lineage` | Agent lineage |
| `last_verified_ymdhis` | Last verification timestamp |
| `last_verified_by_actor_id` | Verifying actor |
| `file_hash` | File integrity hash |
| `file_signature` | File signature |

No definition fields may exist in this table.

---

### 9.18 Missing Table Protocol (RULE 93.MISSING_TABLE_PROTOCOL)

When a table needed for a feature does not exist in `install_new_lupopedia.sql`, the correct procedure is:

1. **Verify the table is truly missing** — check `lupo-database/lupopedia/json/<table>.json` and `install_new_lupopedia.sql`. If a schema JSON file exists, the table is in the live DB but missing from the install script.
2. **Create a SQL proposal file** at `lupo-database/lupopedia/mysql/migrations/add_<table_name>_YYYYMMDD.sql` containing the `CREATE TABLE` and `CREATE INDEX` statements using `{{prefix}}` placeholders.
3. **The SQL file is reviewed and applied** by updating `install_new_lupopedia.sql` directly — adding the `CREATE TABLE` block in the appropriate section.
4. **No data migration is needed** — there is no Lupopedia-to-Lupopedia upgrade path. All schema changes take effect on fresh install via `install_new_lupopedia.sql`.
5. **Regenerate schema JSON** — after the install SQL is updated, run `lupo-scripts/generate_toon_files.py` and create a table doc in `lupo-docs/database/lupopedia/tables/active/<table_name>.md`.

**Forbidden:**
- Creating tables via CLI (`mysql -u root -p < file.sql`) — see section 9.18
- Hardcoding the prefix in the SQL file — always use `{{prefix}}`
- Using `AUTO_INCREMENT` — use `IdGenerator::generate()` in PHP; the PK column is bare `BIGINT NOT NULL`
- Using `UNSIGNED`, `ENGINE=`, `COLLATE=`, `FOREIGN KEY`, triggers, or procedures

**SQL proposal file format:**

```sql
-- Table: {{prefix}}example_table
-- Purpose: [one line description]
-- Added: YYYYMMDD
-- Apply to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql

CREATE TABLE {{prefix}}example_table (
  example_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (example_id)
);
CREATE INDEX {{prefix}}example_table_idx_actor ON {{prefix}}example_table (actor_id);
CREATE INDEX {{prefix}}example_table_idx_is_deleted ON {{prefix}}example_table (is_deleted);
```

---

### 9.19 No Direct CLI Database Execution (RULE 93.NO_CLI_DB_EXEC)

**IDE agents, scripts, and contributors MUST NOT execute SQL directly against the database via CLI tools.**

#### Forbidden patterns:

```bash
# ALL of these are forbidden
mysql -u root -p < some_sql_file.sql
mysql -u root -p lupopedia < install.sql
mysql -u user -ppassword -e "ALTER TABLE lupo_actors ADD COLUMN ..."
mysqldump lupopedia > backup.sql | mysql lupopedia_new
psql -U postgres lupopedia < migration.sql
```

#### Why this is forbidden:

- CLI execution bypasses `LUPO_TABLE_PREFIX` — hardcoded table names in SQL files will create wrong tables or corrupt the wrong ones
- CLI execution bypasses `IdGenerator::generate()` — any `INSERT` with `AUTO_INCREMENT` or a hardcoded PK will produce non-deterministic, non-sortable IDs that break the system
- CLI execution bypasses the installer's write-test-then-fallback logic
- CLI execution bypasses all PHP-layer validation, soft-delete enforcement, and audit logging
- CLI execution is not portable — it assumes a local MySQL/PostgreSQL binary, a specific user, and a specific password, none of which are guaranteed on shared hosting
- CLI execution cannot be reviewed, rolled back, or tested through the standard test suite

#### Required pattern — all schema changes and data operations MUST go through:

1. **Schema changes:** Update `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`, then create a migration file in `lupo-database/lupopedia/mysql/migrations/dev_YYYYMMDD_description.sql`, then run it through the PHP migration runner or installer wizard
2. **Seed data:** Add to `lupo-database/lupopedia/mysql/seed/` and run through the installer
3. **One-time data fixes:** Write a PHP migration script in `lupo-database/lupopedia/mysql/migrations/` that uses `DatabaseFactory::getConnection()` and `IdGenerator::generate()`
4. **Install/upgrade:** Use `install.php` and its supporting wizard class — this is the only approved entry point for schema creation

#### The migration pattern:

```php
// CORRECT — PHP migration using DatabaseFactory and IdGenerator
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$id = IdGenerator::generate();
$db->insert($prefix . 'actors', array(
    'actor_id'       => $id,
    'actor_name'     => 'example',
    'created_ymdhis' => gmdate('YmdHis')
));
```

**Rationale:** The prefix system, deterministic PKs, and PHP-layer integrity checks only work when all database operations go through the application layer. A single raw CLI execution can silently corrupt the prefix mapping, create duplicate or invalid IDs, or insert rows that violate soft-delete conventions — with no audit trail and no rollback path.

---

### 9.20 Proven Code Preservation Doctrine (RULE 93.PROVEN_CODE)

**Agents MUST NOT propose replacing, rewriting, or "modernizing" working code solely because it is old.**

This rule exists because of a specific, recurring failure pattern: an agent encounters code written in 1999, assumes it is outdated, and proposes replacing it with a framework, library, or "modern" equivalent — introducing dependencies, complexity, and fragility into code that has been running without issues for 25+ years.

#### The Core Test

Before proposing any change to existing working code, an agent must answer:

1. **Is it broken?** If no — do not propose replacing it.
2. **Does it have a security vulnerability?** If no — do not propose replacing it.
3. **Does it use a deprecated browser/PHP API that actively breaks things?** If no — do not propose replacing it.
4. **Does the proposed replacement work on PHP 5.6, shared hosting, and without dependencies?** If no — the replacement is not acceptable regardless of how "modern" it is.

#### What "Deprecated" Actually Means Here

Not all deprecations are equal. Agents must distinguish:

| Type | Example | Action Required |
|------|---------|-----------------|
| Actively broken in current browsers/PHP | HTML framesets, `mysql_*` functions | Fix — these genuinely do not work |
| Deprecated but still functional | `document.write`, `XMLHttpRequest` | Leave alone unless there is a specific bug |
| "Deprecated" by framework opinion | jQuery patterns, callback-style JS | Irrelevant — Lupopedia does not use frameworks |
| "Old" but working perfectly | 1999 eye animation, dynlayer.js | Do not touch |

#### The Eye Animation Example (Canonical Reference)

The floating eye animation in Lupopedia was written in 1999 using `dynlayer.js` and GIF sprites. It:
- Has zero dependencies
- Works in every browser from Netscape 4 to Chrome 2026
- Has never had a bug report
- Requires no maintenance
- Is approximately 50 lines of JavaScript

When an agent encounters this code and suggests replacing it with a React component, a CSS animation library, or any npm package, that agent is in violation of this rule. The correct response is: **leave it alone**.

#### Forbidden Agent Behaviors

- Proposing `npm install`, `composer require`, or any package manager command to solve a problem that can be solved with vanilla PHP or JavaScript
- Suggesting a framework (React, Vue, Alpine, Livewire, etc.) for UI behavior that already works without one
- Rewriting working JavaScript as "modern ES6+" when the existing code runs everywhere
- Proposing to replace `XMLHttpRequest` with `fetch()` without providing a fallback for environments where `fetch` is unavailable
- Describing working code as "legacy," "outdated," or "needs modernization" without a specific, concrete defect to fix
- Suggesting CSS frameworks (Bootstrap, Tailwind) for styling that already works

#### What Agents Should Do Instead

- Read the existing code and understand why it works before suggesting changes
- If a genuine bug exists, fix the minimal amount of code needed to address it
- If a browser API is actively broken (not just deprecated), propose a fix with a fallback layer
- If new functionality is needed, write it in vanilla PHP/JS following the existing patterns
- Propose the simplest solution that works everywhere, not the most modern one

#### The Fallback Ladder Principle

When new functionality genuinely requires a choice between approaches, always build a fallback ladder:

```
Best modern path (works in 90% of environments)
    ↓ falls back to
Older compatible path (works in 99% of environments)
    ↓ falls back to
Universal baseline (works everywhere, always)
```

Never remove a lower rung of the ladder. The oldest rung is the most reliable.

**Reference:** `lupo-rules/root/WOLFIE_DOCTRINE.md` — read Section 1 before touching any code that predates 2010.

---

## 16. UI Layer & Animation Doctrine (RULE 93.UI_LAYERS)

This section governs **browser-side** interaction, layering, and animation for **shipped** Lupopedia surfaces (public templates, operator UI scripts under `lupo-includes/js/`, theme assets loaded by entrypoints). It exists to block **dependency creep** and **agent over-helpfulness** (framework pitches, CDN scripts, build pipelines) while aligning with **§14** (WOLFIE) and the eval-free **`LupoLayer`** lineage in **`lupo-includes/js/lupo-layers.js`**.

**Scope note:** In-repo **developer-only** trees (`lupo-tools/`, editor extensions, CI) may use local npm for **tooling**; those stacks MUST NOT become **required** at runtime for `lupo-includes/` bootstrap, `index.php`, `login.php`, `admin.php`, or visitor-facing routes.

### 16.1 The WOLFIE UI standard (canonical layer controller)

The canonical library for DHTML-style operations (absolute positioning, z-index choreography, slide animations) is **`lupo-includes/js/lupo-layers.js`** (`LupoLayer`, `LupoLayerInit` / `DynLayerInit` alias).

| Rule | Requirement |
|------|-------------|
| **Mandatory** | New layering / slide / z-index choreography MUST use **`LupoLayer`** (or thin wrappers that delegate to it). |
| **Prohibited** | **`eval()`**, **`new Function(string)`**, or **`setTimeout` / `setInterval` with a string** argument for logic or animation continuations. |
| **Prohibited** | External animation libraries (e.g. GSAP, Velocity, animate.css) as **runtime** dependencies for constitutional UI surfaces. |
| **Prohibited** | **New** dependencies on jQuery or other general-purpose DOM libraries for those surfaces. Existing grandfathered includes MUST NOT be extended; replace with vanilla patterns when touched. |
| **Heritage** | **`lupo-includes/js/dynapi/js/dynlayer.js`** remains in-tree for **proven** legacy paths (e.g. PRD 28 eye / theatrical UI) per **§9.20**; **new** features MUST NOT copy its `eval` patterns — use **`lupo-layers.js`** instead. |

### 16.2 Absolute self-containment (no build steps for shipped UI)

Lupopedia is a **live-edit** system: operators and agents must be able to read and patch UI scripts in the IDE or on-disk without a compilation step on the server.

| Prohibited for shipped browser UI |
|-----------------------------------|
| **`npm`**, **`yarn`**, **`pnpm`**, or any package manager **as a requirement** to generate or load runtime JS/CSS for `lupo-includes/` or public entrypoints |
| **`Vite`**, **`Webpack`**, **`Rollup`**, **`Babel`**, **`Turbo`**, or similar bundlers/transpilers **on the critical path** to serving pages |
| **TypeScript**, **JSX**, or any syntax that **requires** a transpiler before the browser or PHP can serve the file |

Shipped scripts MUST be **vanilla ECMAScript** (ES5 baseline where compatibility doctrine requires; modern syntax only when explicitly allowed by **§4** / browser targets and still **without** a build step).

### 16.3 Hardware acceleration and performance

| Requirement | Detail |
|-------------|--------|
| **GPU-friendly motion** | Prefer **CSS transitions** for simple moves (e.g. `LupoLayer.prototype.slideTo` CSS path). |
| **Decorative overlays** | Absolutely positioned “peering” / paw / mascot layers that must **not** steal clicks MUST use **`pointer-events: none`** (or equivalent) so underlying controls (forms, links) stay usable unless a deliberate hit-target is specified. |
| **Main thread** | Complex behaviors (e.g. eye tracking, drag) MAY use hooks (`onSlide`, `onmousemove`, `requestAnimationFrame`) but MUST avoid long synchronous work that blocks input or paint. |

### 16.4 Dependency sanity check (external `<script>` / `<link>`)

Before an agent proposes a new **runtime** `<script src="…">` or **stylesheet** from outside the repo:

1. The file MUST be **vendored** under **`lupo-includes/`** (or another documented canonical static path), not loaded from a **third-party CDN** as a default.
2. It MUST NOT exceed **20KB minified** (gzip-agnostic; rough guardrail — justify in review if larger).
3. It MUST NOT **require** an API key, license callback, or **phone-home** telemetry to a vendor for basic operation.
4. If the behavior fits in **~50 lines** of vanilla JS, the agent MUST implement it in-tree instead of adding a library.
5. **Cross-origin** script or font URLs on **visitor/operator** pages are **presumptively forbidden** unless explicitly approved for a documented integration (e.g. federated embed with operator consent); default is **same-origin** assets only.

### 16.5 Reference

| Topic | Location |
|-------|----------|
| Canonical layer implementation | **`lupo-includes/js/lupo-layers.js`** |
| Legacy DynAPI (heritage, eval present) | **`lupo-includes/js/dynapi/js/dynlayer.js`** |
| WOLFIE doctrine | **`lupo-rules/root/WOLFIE_DOCTRINE.md`**, **§14** above |
| Proven code preservation | **§9.20** — do not “modernize away” working heritage without justification |

---

## 10. Enforcement

### 10.1 Constitutional Supremacy

All files in `lupo-rules/root/` are binding constitutional law and override all PRDs. Any conflict between PRDs and root rules must be resolved in favor of the root rules. Any violation is a constitutional error and must be corrected immediately.

### 10.2 Validation Tooling

| Rule | Validator |
|------|-----------|
| Section 3 database rules | `lupo-scripts/verify_db_against_toons.py` |
| Section 3.5a temporal anchor | `lupo-bin/temporal_anchor.json` + `tick.py` / `echo_anchor_utc.py`; PHP refresh via `lupo-includes/functions/time.php` on `admin.php` — code review for guessed timestamps |
| Section 3.2 IdGenerator | `lupo-tests/unit/id_generation_compliance_test.php` |
| Section 4 PHP 5.6 compat | `php -l` + `lupo-scripts/run_unit_tests.sh` |
| Section 7 path purity | `lupo-scripts/validate_lupopedia_headers_universal.py` |
| Section 9 installer | `lupo-tests/regression/installer/` |
| Section 9.18 missing table protocol | SQL proposal file + install SQL update |
| Section 9.19 CLI prohibition | Code review — no automated scanner yet |
| Section 9.20 proven code preservation | Code review — agent must justify any change to pre-2010 code |
| Section 15 multi-environment patterns | Code review + installer paths — `InstallWizardHtaccessWriter.php`, `install.php`, PRD 33 §14 traceability |
| Section 16 UI layer & animation | Code review — `lupo-includes/js/lupo-layers.js` for new motion/layer code; no eval/string timers; no npm on runtime path |
| Section 17 security invariants (RULE 93.SECURITY) | Code review + **`lupo-docs/implementations/security_audit_cursor_ide/README.md`** — LILITH cognitive tax; THOTH schema/doc truth |
| Schema DDL | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` |

---

## 11. Refinements

*Sections 12-13 reserved for future expansion. **§15** (WordPress multi-environment patterns), **§16** (UI layer & animation, RULE 93.UI_LAYERS), and **§17** (security invariants, RULE 93.SECURITY) are active.*

- All doctrine and PRD files must reference this file as the constitutional anchor using an outbound edge.
- All new PRDs must be reviewed for compliance with these requirements.
- Constitutional rules override all implementation details.
- These rules apply to all 4.0.x releases until explicitly revised.
- Any agent, IDE, or human contributor must treat this file as the highest authority.
- This file must be versioned and updated only by lead orchestration agents or with explicit review.

---

## 14. The WOLFIE Doctrine: Engineering for Survival

### 14.1 Constitutional Authority

The WOLFIE Doctrine (`lupo-rules/root/WOLFIE_DOCTRINE.md`) is hereby incorporated as a constitutional requirement for all agents operating within Lupopedia.

### 14.2 Binding Rules

| Rule | Description |
|------|-------------|
| W-01 | No dependency proposals without justification |
| W-02 | No removal of fallback layers |
| W-03 | Code that runs without you |
| W-04 | Complexity is the enemy |
| W-05 | Respect the proven |

### 14.3 The "Modernization" Prohibition

Agents SHALL NOT propose "modernizing" code that has been proven to work for 20+ years without:

1. Documenting why the existing code is insufficient
2. Explaining how the proposed solution maintains the same fallback layers
3. Demonstrating that the new solution works in the same environments (shared hosting, PHP 5.6-8.6, etc.)
4. Acknowledging that code that outran its author for 11 years is not "broken"

**Rationale:** The Crafty Syntax codebase (1999-2025) ran unattended for 10 years. This level of resilience is not "legacy" — it is proven architecture.

### 14.4 Framework Prohibition

Agents SHALL NOT propose adopting frameworks (Laravel, Symfony, React, Vue, etc.) unless the framework is absolutely required, can be optionally loaded without breaking core, the fallback path works without it, and shared hosting remains supported.

### 14.5 Reference

- Full doctrine: `lupo-rules/root/WOLFIE_DOCTRINE.md`
- Fallback doctrine: `lupo-docs/doctrine/CASCADE_FALLBACK_DOCTRINE.md`
- Dependency doctrine: `lupo-docs/doctrine/DEPENDENCY_DOCTRINE.md`
- Shipped UI layering / animation: **`lupo-includes/js/lupo-layers.js`** — constitutional detail **§16** (RULE 93.UI_LAYERS)

### 14.6 AGAPE (Agentic Guidance And Practical Empathy)

**Definition.** **AGAPE** is a **non-sentimental technical philosophy** governing **system resilience** and **inter-actor cooperation**. It is **not** therapeutic language, emotional validation, or marketing tone. It names **measurable** platform behavior (fallback ladders, environment probes, explicit archival of new truth).

**Agentic Guidance.** The system’s ability to **autonomously identify** logic gaps, outdated doctrine, or technical debt and to surface **actionable** paths so the **runtime** or **human operator** can improve the codebase and documentation. This is the same **behavior class** as **KAIROS** memory consolidation: the platform notices that **new** operational truth exists and **records** it for the operator’s benefit (**§5.7**, **PRD 37**, **`KairosConsolidationService`**).

**Practical Empathy.** **Deterministic** understanding of **environmental constraints** (shared hosting, OS quirks, PHP version bands, missing extensions) and the **contextual state** of other actors (membership, visibility, offline fallbacks). It is **expressed** through **graceful degradation** and **fallback ladders** (**§15**, **WOLFIE Doctrine**, **`CASCADE_FALLBACK_DOCTRINE`**). Illustration: use **`function_exists()`** / **`extension_loaded()`** and branch rather than fatal — survival on a **minimal host** is **AGAPE-compliant** engineering.

**Validator rule (binding).** The phrases **“made with love,”** **“supportive tone,”** and **“emotional validation”** MUST **NOT** appear as **product requirements**, **acceptance criteria**, or **validator pass/fail** semantics for Lupopedia artifacts. Where found, validators and reviewers MUST **flag** them as **constitutional violations** (sentimental framing of **technical** quality). Canonical expansion: **`lupo-docs/doctrine/AGAPE_DOCTRINE.md`**.

**LILITH alignment.** Under **AGAPE**, review asks: **Does this code understand the environment it runs in? Does it provide unconditional fallbacks so the system survives on constrained hosts?** — not “does this feel caring?”

**ROSE / synthetic dialogue.** **AGAPE** is a **cooperation metric** in **`metadata_json`** for synthetic lines (**PRD 36**): it measures how well the voiced persona reflects the **human operator’s state and dependencies** to produce **useful guidance**, not **agreeable** filler. See **`AGAPE_DOCTRINE.md`** §3.

---

## 15. WordPress multi-environment patterns (constitutional)

Lupopedia MUST behave correctly across **unknown** server stacks (shared hosting, odd PHP builds, Apache / Nginx / IIS front ends). Patterns below are **constitutional**: they are derived from disciplined study of WordPress behavior in **`lupo-archive/legacy/wordpress-reference/`** when present locally (read-only; **GPL** — do not copy into shipping code; **`lupo-archive/`** is **`.gitignore`d** — restore a study copy there if needed) and from **`PRD 33` Section 14** (WordPress distribution patterns, LILITH answers, and implementation notes).

These rules **add** to **§1** (shared hosting), **§9** (installer), **§14** (WOLFIE — preserve proven layers), and **§14.6** (**AGAPE** — Practical Empathy as environment-aware degradation). They do **not** authorize frameworks, Composer in core, or database-side logic.

### 15.1 Extension detection (no assumptions)

Never assume a PHP extension or wrapper function exists. Probe with **`function_exists()`** and **`extension_loaded()`** (or equivalent) and **branch** to a documented fallback or a clear operator-visible message.

**Illustrative pattern (PHP 5.6+):**

```php
if (function_exists('curl_init')) {
    // preferred path
} elseif (ini_get('allow_url_fopen')) {
    // fallback
} else {
    // log + user-visible failure — do not fatal silently
}
```

New code MUST NOT assume **`curl`**, **`gd`**, **`json`**, or **`pdo_mysql`** without installer or runtime checks aligned with **PRD 33** / **§4**.

### 15.2 Try/catch for external operations

Operations that touch **external** or **non-deterministic** surfaces (database via PDO, filesystem, HTTP, subprocesses) MUST surface failure paths: **`try` / `catch`** (where exceptions apply), or explicit return codes and logging. Silent failure is forbidden for installer steps and for user-visible flows.

**Database:** use **`PDO_DB`** / **`DatabaseFactory::getConnection()`** only — no raw **`PDO`** in new core paths.

```php
try {
    $row = $db->fetch($sql, array('id' => $id));
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    // user-safe message; no credential leakage
}
```

### 15.3 Permission detection (no auto-fix)

When **`mkdir`**, writes, or renames fail, **detect** and **warn** with paths and, where available, parent **mode** information. Do **not** automatically **`chmod`** or change ownership to “repair” the host — that can widen exposure and violates operator authority.

**Illustrative pattern:**

```php
if (!@mkdir($dir, 0755, true)) {
    $parent = dirname($dir);
    $permHint = '';
    if (is_dir($parent)) {
        $permHint = decoct(fileperms($parent) & 0777);
    }
    // log + wizard message naming $dir and optional $permHint
}
```

This aligns with **LILITH** resolutions in **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md`**.

### 15.4 Server software detection (`.htaccess` and friends)

**`.htaccess`** is **Apache / LiteSpeed–oriented**. Before writing or rewriting **`.htaccess`**, the installer (or tool) MUST classify **`$_SERVER['SERVER_SOFTWARE']`** conservatively: write marker-based rules only when the stack is **Apache-compatible**; for **Nginx**, **IIS**, and similar, **skip** blind **`.htaccess`** writes and point operators at **documentation** (and optional example snippets such as **`web.config.example`** — reference only, not auto-installed unless product explicitly approves).

**Canonical implementation surface:** **`lupo-install/InstallWizardHtaccessWriter.php`** (`isApacheHtaccessEnvironment()`, **`# BEGIN LUPOPEDIA` / `# END LUPOPEDIA`** marker merge).

### 15.5 Configuration file writable check (WordPress-style)

Before assuming the wizard can create **`lupopedia-config.php`**, check writability of the target directory (see **§9.13** sandbox discipline). If writes are blocked, the product MUST offer a **manual** path: copy from a shipped **sample** file (e.g. **`lupo-config/lupopedia-config-sample.php`** when present), edit constants, upload — mirroring **`wp-config-sample.php`** workflow. Do not assume FTP or panel allows web-user creation of secrets in docroot.

### 15.6 Path normalization (Windows vs Linux)

Use **`DIRECTORY_SEPARATOR`** and **`LUPOPEDIA_PATH` / `LUPOPEDIA_ABSPATH`** (and related constants) for filesystem joins. When **comparing** paths, normalize line endings and slash direction in **PHP only** for that comparison — do not invent ad hoc path APIs that bypass existing bootstrap constants.

### 15.7 Subdirectory URL construction

All browser-facing URLs MUST be built from **`LUPOPEDIA_PUBLIC_PATH`** (and doctrine equivalents), never hardcoded **`/lupopedia/`** or root **`/`** assumptions.

**Illustrative pattern:**

```php
$base = rtrim(LUPOPEDIA_PUBLIC_PATH, '/');
$path = ltrim($relative, '/');
$url = $base . '/' . $path;
```

### 15.8 Reference

| Topic | Location |
|-------|----------|
| WordPress study table and action items | **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** — **Section 14** |
| LILITH Q&A (markers, immediate `.htaccess`, sample config, permissions, **`.gitkeep`**) | **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md`** |
| Implementation backlog | **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_pattern_implementation_tasks_20260404.md`** |
| Install wizard entry | **`install.php`** — shared classes **`install_wizard_classes.php`** |
| Apache marker merge + runtime dirs | **`lupo-install/InstallWizardHtaccessWriter.php`** |
| Educational WordPress tree | **`lupo-archive/legacy/wordpress-reference/`** (local study copy; **`.gitignore`d**; not shipped; GPL) |
| Pattern distillate (read before re-scanning WP) | **`lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md`** |

### 15.9 LILITH audit (integration record)

| Field | Value |
|-------|--------|
| **Verdict** | **APPROVED with additions** — §15 codifies WordPress-derived multi-environment resilience |
| **Accuracy (reported)** | 98/100 |
| **Constitutional violations** | None reported |
| **Reviewer** | LILITH (**actor_id 2**), non-interfering reviewer per **LIL001** |

### 15.10 IDE security audit protocol (operational)

When **writing** or **reviewing** PHP and installer paths, IDE agents MUST apply the shared-hosting checklist in **`lupo-docs/implementations/security_audit_cursor_ide/README.md`** (path anchoring, stream rejection, **`PDO_DB`**, AGAPE probes, direct-access hygiene). **Constitutional** requirements are codified in **§17** (**RULE 93.SECURITY**). **LILITH** uses the checklist for **cognitive tax** on simplified defenses; **THOTH** cross-checks claims against **TOON** / **install SQL** / **table docs**.

---

## 17. Security Invariants (RULE 93.SECURITY)

Lupopedia assumes a **hostile wilderness**: minimal PHP builds, misconfigured Apache, absent extensions, and unsophisticated operators on **$5 shared hosting**. Automated “safety nets” (WAFs, container hardening, service meshes) are **not** architectural assumptions. **Logic is the firewall.**

This section **binds** IDE agents and human contributors when writing or reviewing code. It **extends** **§3.6** (database-neutral SQL + application-layer discipline), **§15** (extension and permission probing, no auto-**chmod**), and **§14.6** (**AGAPE** — graceful failure). Operational checklist: **`lupo-docs/implementations/security_audit_cursor_ide/README.md`**.

### 17.1 The Gunslinger principle

**No external package manager** (**npm**, **Composer**, **pip** in core paths) may implement **core security logic** (auth decisions, path resolution for includes, SQL assembly, CSRF token semantics). Study upstream code off-tree (**`lupo-research/`**, local clones); **ship** native PHP under **`app/`** / **`lupo-includes/`** per dependency and reverse-engineering doctrine. Test-only and CI tooling remain out of scope of this prohibition.

### 17.2 Path anchoring and inclusion integrity (RFI / LFI)

| Rule | Requirement |
|------|-------------|
| **Anchor** | File execution and `require` / `include` graphs MUST be anchored on **`LUPOPEDIA_PATH`**, **`ABSPATH`**, **`__DIR__`**, or other **bootstrap-defined** constants — not on raw user input. |
| **Stream block** | Any path used to load PHP or secrets MUST reject **stream wrappers** and remote forms: resolver MUST reject **`://`** and **NUL** bytes (see **`LupopediaConfigResolver::isSafeLocalConfigPath()`**). |
| **Traversal** | When user-influenced path segments exist, use **`realpath()`** and/or **normalized** comparisons under a **known root**; never `include` from a string built only from `$_GET` / `$_POST` / uploads. |
| **Config order** | **`LUPOPEDIA_CONFIG_LOADED`** and **`ABSPATH`** MUST be validated before **`lupo-includes/bootstrap.php`** continues; **`LUPOPEDIA_PATH`** MUST agree with **`ABSPATH`** when both resolve (**`lupo-includes/bootstrap.php`**). |

**Critical violation:** Dynamic inclusion of **user-supplied** strings as code paths, even after “sanitization,” without a fixed allowlist under a known root.

### 17.3 Database integrity (application layer)

**Constitutional database rules** (**§3**) stand: no foreign keys, triggers, procedures, DB-generated timestamps for lineage, no **`AUTO_INCREMENT`** for reserved-ID tables. **All** referential discipline and value sanitization for queries MUST live in **PHP** using **`DatabaseFactory::getConnection()`** / **`PDO_DB`** with **named placeholders** — no string-concatenated values in SQL. **`INSERT`** MUST **list every column** explicitly (**constitutional root rules**). Cast scalars to **`(int)`** / **`(float)`** when binding IDs and numeric limits where appropriate.

### 17.4 AGAPE fallbacks (security-sensitive operations)

Every **security-sensitive** operation (file write, network connect, DB query, optional extension use) MUST have a **documented** fallback or **graceful** failure: operator-visible message, log line, or offline filesystem path per **database offline fallback** doctrine — not a silent white screen. Probe with **`extension_loaded()`** / **`function_exists()`**; test **`is_writable()`** before writes; **do not** **`chmod`** to “fix” the host (**§15.3**).

### 17.5 Direct access and information leakage

Sensitive trees (**`lupo-database/`**, **`lupo-logs/`**, config-adjacent paths) MUST use **Apache marker** deny rules where **`InstallWizardHtaccessWriter`** applies them, and **index silence** (**blank `index.php` / `index.html`**) where the product ships them — see **PRD 33** / **§15.4** and installer behavior. Do not rely on “nobody guesses the URL.”

### 17.6 Reviewer roles (LILITH and THOTH)

| Actor | Role |
|-------|------|
| **LILITH** (**actor_id 2**) | Applies the **IDE security audit checklist** as **cognitive tax** on new/changed code: if an agent “simplifies away” path checks, stream blocks, or fallbacks, that is a **failure** — **LIL001** non-interference still applies (review attribution, no permission override). |
| **THOTH** | Confirms that claimed defenses and “hardening” match **TOON** / **install SQL** / **table docs** — no protection against imaginary threats while real schema or API gaps remain. |
