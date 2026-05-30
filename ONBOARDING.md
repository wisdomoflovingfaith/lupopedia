---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: ONBOARDING.md
  web_path: https://www.lupopedia.com/lupopedia/ONBOARDING.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/onboarding-md.toon
  atoms_toon: null
  transcript_jsonl: 0/development/onboarding-md
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: guide
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE
  title: ONBOARDING.md -- Operational Quick-Start
  summary: Operational quick-start; PRD-first documentation architecture gate; identity doctrine (A3); actor/channel execution; ASCII-only mandate (LILITH).
---
# file: ONBOARDING - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/ONBOARDING.md](http://www.lupopedia.com/lupopedia/ONBOARDING.md)

# Lupopedia -- Onboarding

**Operational entry point for new agents and contributors.**  
Read this first to know what to do when you enter the repo.

## ASCII-ONLY MANDATE (LILITH)

[LILITH DIRECTIVE - ACTOR ID 2] - ABSOLUTE ASCII-ONLY MANDATE - NO EXCEPTIONS ANYWHERE

All authored text in this repository MUST stay inside ASCII code points 32 through 126. This includes code, documentation, comments, commit messages, logs, JSON/YAML/TOON, database strings, CLI output, channel handoffs, and user-visible copy.

Do not use emoji, Unicode arrow glyphs, box-drawing characters, curly quotes, or em/en dash characters. Use straight quotes, `--` for a long dash, plain `-` for a hyphen, and ASCII direction such as `->` or `<->`.

Canonical full text (applies-to list, mandatory replacements, enforcement, END DIRECTIVE): [AGENTS.md](AGENTS.md) section **ASCII-ONLY DOCTRINE (LILITH / constitutional)**.

## Actor vs Agent (summary)

- **`lupo-agents/`** -- AI **configuration** only; not operational identity for permissions, channels, or audit joins.
- **`lupo-actors/{actor_id}/`** -- Operational resources; **`actor_id`** is defined in **`lupo-database/lupopedia/actors/actor_id/registry.json`**.
- **IDE facets** (Cursor, Windsurf, ...) use a **registered facet `actor_id`** for attribution (e.g. **102** for Cursor). They are **not** primary coordination personas; they **are** registered identities for lineage when operating as that facet.

**Canonical source (do not restate in long form):** [Identity Layers Doctrine section 3](lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md#3-actor-agent-faucet-directory-rules-canonical).

---

## 1. Purpose of This File

`ONBOARDING.md` is the **first operational file** a new agent should read when entering the Lupopedia repository. It is for:

- **IDE agents** (Cursor, Windsurf, Kiro, JetBrains, Warp, Antigravity, etc.) working directly in the repo
- **External LLM agents** (e.g. OpenAI / ChatGPT or similar) that may not have full repo-native context
- **Human contributors** who need the same quick-start orientation

It answers: what is this system, what must I read first, what rules must I not break, and how do I start or continue work. For **why** Lupopedia is designed this way, see [EXECUTIVE_SUMMARY.md](EXECUTIVE_SUMMARY.md).

---

## 2. What Lupopedia Is

Lupopedia is a **semantic operating system** with:

- **Doctrine-driven architecture** -- Rules and constraints are explicit in doctrine files and root rules; behavior is not implied by framework defaults.
- **Deterministic identity** -- Actors, channels, and other entities use registry-based ID allocation, not ad hoc AUTO_INCREMENT.
- **Channel-scoped work** -- Tasks, dialogs, and artifacts are scoped by `channel_id`; channels are the primary coordination unit.
- **Documentation as architecture** -- Doctrine, table docs, and canonical references are implementation-critical; they are read before changing schema or behavior.
- **Multi-agent collaboration** -- Multiple IDE agents (and humans) work in the same repo; continuity, logging, and handoff are built into the model.
- **Canonical UTC Authority** -- Run `python lupo-bin/tick.py` before writing `last_modified_utc` / `last_verified`; reuse `python lupo-bin/echo_anchor_utc.py` in the same batch. See [TICK_PY_DOCTRINE.md](lupo-docs/doctrine/TICK_PY_DOCTRINE.md) and [Temporal System PRD](lupo-docs/prd/75_temporal_system.md). Never guess timestamps or use chat "current time."

### Multi-agent coordination (4.0.80+ -- binding)

Read **[lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md)** before claiming coordination roles or naming artifacts.

- **Eleven Primary Coordination Personas** -- WOLFIE, LEXA, ANUBIS, HEIMDALL, SESHAT, ATHENA, MAAT, THEMIS, THOTH, JANUS, ROSE. They are the canonical coordination layer (directives, enforcement, custody, review, strategy, etc.).
- **Specialized agents (90+)** -- HERMES, IRIS, LILITH, ASCLEPIUS, and others operate in **categories** (technical support, contrasting perspectives, etc.). They are **not** the eleven coordination personas; they work under primary-persona and channel rules.
- **IDE faucets** -- Cursor, Windsurf, Kiro, Cascade, Warp, Zencoder, Antigravity are **human interfaces**; doctrine requires routing work through primary personas and registered channel context--not treating an IDE as the sole orchestration authority.
- **Channel context** -- All coordination work is invalid without `channel_id` (default multi-agent workspace: **42**). Confirm membership where posting or acting on channel data.
- **Artifacts** -- Proof of coordination lives under **`lupo-channels/{channel_id}/`** (threads/tasks/broadcasts/direct) with the artifact families defined in the doctrine (e.g. `WOLFIE_DIRECTIVE_*`, `SESHAT_REVIEW_*`). `lupo-docs/status/` is archival/legacy only; the channel system is authoritative for continuity.
- **Task authority** -- Per MULTI_AGENT doctrine: root **`TODO.md`** is the multi-agent coordination queue (including HERMES prompt queue), while **`lupo-docs/versions/<current_version>/TODO.md`** is the version product backlog. Update the correct surface based on task type; always link to owning channel thread/task artifacts.

---

## Documentation Architecture (PRD-First System)

Normative placement rules for the whole repo are also stated in **[ORGANIZATION.md](ORGANIZATION.md)** and **[PRD 26](lupo-docs/prd/26_five_layer_documentation_architecture.md)**. This section is the **onboarding gate**: agents must not invent parallel doc trees.

### Canonical hierarchy

1. **PRD files (`lupo-docs/prd/`)**
   - **PRIMARY** source of truth for product requirements.
   - New requirements, behavior changes, and normative contracts belong here.
   - Filenames use the **`NN_*.md`** pattern grouped by **two-digit PRD number** `00` through `99` (domain bands; multiple files may share the same `NN` prefix when they belong to one domain).
2. **Doctrine files (`lupo-docs/doctrine/`)**
   - **SECONDARY** elaboration: explain, expand, or operationalize **PRD-bound** rules.
   - Doctrine **MUST** remain anchored: either **hyperlink from at least one PRD** or appear under an explicit doctrine index mandated by **PRD 26** / **[PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md)** (see **ORGANIZATION.md** placement gate).
3. **Other documentation (guides, onboarding, audits, implementation mirrors)**
   - **SUPPORTING** only: orientation, checklists, audits, and PRD-scoped work under **`lupo-docs/implementations/{prd_file_stem}/`** per **PRD 31**.
   - **MUST NOT** introduce new product requirements; every normative claim **MUST** link back to a **PRD** (or state that it is explicitly non-normative scratch with a relocation plan).

### Enforcement behavior

If an agent encounters documentation that violates this structure:

- **DO NOT** expand or continue writing in that file.
- **DO NOT** treat it as canonical.
- Identify the correct **PRD** group or **doctrine** location.
- Move or align the content to the correct location.
- Mark legacy files in **LUPOPEDIA HEADERS** (or equivalent artifact headers) with:
  - **`status: legacy`**
  - **`superseded_by`**: path to the correct PRD under **`lupo-docs/prd/`** (or the owning doctrine file, if that is the canonical anchor).

### PRD grouping (two-digit system)

- **`NN`** is the **group id** (headers, channels, memory, installer, and so on). More than one file may exist under the same **`NN`** when they are the same domain split (see **[`lupo-docs/prd/PRD_INDEX.md`](lupo-docs/prd/PRD_INDEX.md)** (also **`prd_index.md`** where the generator writes), regenerated only via **`python lupo-scripts/generate_prd_index.py`**).
- **DO NOT** mint a **new `NN`** or a new top-level PRD filename without checking **`lupo-docs/prd/PRD_INDEX.md`** and **`lupo-docs/doctrine/PRD_GAPS.md`** for reserved numbers and collisions.
- **Agents MUST** read **`lupo-docs/prd/PRD_INDEX.md`** (and **`PRD_GAPS.md`** when changing numbering) **before**:
  - creating new PRD files,
  - assigning a new **`NN`** group,
  - splitting an existing domain across new PRD stems.

### Hard rule

**No standalone documentation files are allowed.**

- Every new or moved Markdown file that reads as **system truth** **MUST** either:
  - live under **`lupo-docs/prd/`** as part of a **PRD group**, **OR**
  - live under **`lupo-docs/doctrine/`** / **`lupo-docs/implementations/`** / **`lupo-docs/audits/`** per **ORGANIZATION.md** and **MUST** include explicit **inbound linkage** to the owning **PRD** (or index) in the same edit wave.
- **Forbidden:** ad-hoc `*.md` at repository root (except **`README.md`** / **`ORGANIZATION.md`**), random `docs/` sprawl, or "floating" doctrine with **no** PRD pointer.

### Decision rules (where to put new prose)

| If the change... | Put it in... |
|-------------------|--------------|
| Defines or changes a **requirement**, API contract, schema rule, or product behavior | **`lupo-docs/prd/`** (new or existing **`NN_*.md`**) |
| Explains **how** to apply a PRD, tables, doctrine detail, or long-form rationale | **`lupo-docs/doctrine/`** with **PRD backlinks** |
| Describes **workflow**, onboarding steps, audits, or PRD-tied implementation notes | **Guides** / **this file** / **`lupo-docs/implementations/{prd_file_stem}/`** per **PRD 31** |

### PRD location and anti-duplication (merged from former section 11)

- **Canonical PRDs live ONLY under `lupo-docs/prd/`.** Do **not** author new PRDs inside version-only trees as the primary copy.
- **Constitutional anchor** for system-wide rules: **`lupo-docs/prd/00_root_constitutional_system_requirements.md`**.
- If a legacy or version-scoped file still reads as a PRD, **archive** it: set header **`status: legacy`** and **`superseded_by`** to the **`lupo-docs/prd/`** replacement path; do not leave two competing normative copies.
- **Before** adding a new PRD file, **search `lupo-docs/prd/`** and **`PRD_INDEX.md`** to avoid duplicate coverage.

### Rationale (technical)

- **Single navigation graph:** reviewers and tooling can resolve "what is binding" without scanning the whole tree.
- **Deterministic authority:** runtime and agents follow **PRD order**; doctrine and guides do not fork requirements silently.
- **Multi-agent safety:** channel handoffs and validators assume paths under **PRD 31** / **PRD 17**; random `.md` files break those contracts.
- **Anti-sprawl:** prevents orphan Markdown that no validator or HERMES routing can classify.

Execution enforcement of these rules is defined in **[AGENTS.md](AGENTS.md)**.

---

## 3. First Files to Read

### Reading order clarification

| Read this first | Why it matters |
|-----------------|----------------|
| [ONBOARDING.md](ONBOARDING.md) | Operational quick-start - read this first to understand what actions to take |
| [lupo-docs/INIT_README.md](lupo-docs/INIT_README.md) | Init doctrine and prerequisites - read before working with `lupopedia.init` headers |

### Prioritized file list

| Read this first | Why it matters |
|-----------------|----------------|
| [ONBOARDING.md](ONBOARDING.md) | Operational quick-start (this file) |
| [README.md](README.md) | High-level system overview, install, required reading |
| [CHANGELOG.md](CHANGELOG.md) | Current version and latest changes |
| [lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md) | Eleven personas, artifacts, channels, TODO authority (multi-agent work) |
| [lupo-rules/root/README.md](lupo-rules/root/README.md) | Root rules index and non-negotiable constraints |
| [lupo-rules/root/CONVERGENCE_DOCTRINE.md](lupo-rules/root/CONVERGENCE_DOCTRINE.md) | Forces all agents to converge on canonical identity and doctrine (no variant actors) |
| [lupo-docs/doctrine/DATABASE_DOCTRINE.md](lupo-docs/doctrine/DATABASE_DOCTRINE.md) | Core database rules (no FKs, no DB logic, timestamps, registry, soft deletes) |
| [lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md) | If you need to register as an actor (new IDE or external agent) |
| [lupo-docs/doctrine/AGENT_REGISTRY.md](lupo-docs/doctrine/AGENT_REGISTRY.md) | Canonical human-readable reference for actor identity, propagation support, and IDE roles |
| [lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md](lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md) | Actor vs agent vs facet; registry authority; directories (**section 3**) |
| [lupo-docs/doctrine/TICK_PY_DOCTRINE.md](lupo-docs/doctrine/TICK_PY_DOCTRINE.md) | Real UTC for headers (`tick.py`, `echo_anchor_utc.py`) -- mandatory before edits |
| [EXECUTIVE_SUMMARY.md](EXECUTIVE_SUMMARY.md) | Philosophy and architecture (why the system is designed this way) |

---

## Agent Status Determination

Before proceeding with onboarding, determine your agent's status.

### State A -- Already registered

Your `actor_id` already exists in:

- `lupo-database/lupopedia/actors/actor_id/registry.json`
- `lupo_actors` (when the database is available)

**Actions:**

- Verify your identity in the registry (canonical actor_id and slug).
- Run rule propagation: `php lupo-scripts/propagate_agent_rules.php --target=<your-agent>`.
- Begin contributing; no new registration.

**Example:** Cursor (actor_id **102**, slug `cursor`).

---

### State B -- New agent

You do not exist in the actor registry.

**Actions:**

1. Follow the full [Actor Registration Checklist](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md).
2. Add registry entry to `lupo-database/lupopedia/actors/actor_id/registry.json`.
3. Insert actor into `lupo_actors` (or document fallback when DB is unavailable).
4. Configure propagation target if your IDE is not yet supported (see [Extending rules propagation](#extending-rules-propagation)).

---

### State C -- Exists but needs integration

Your actor exists in the registry but your IDE is not yet supported in rule propagation.

**Actions:**

1. Add your target to [lupo-scripts/propagate_agent_rules.php](lupo-scripts/propagate_agent_rules.php) (see [Extending rules propagation](#extending-rules-propagation)).
2. Generate your IDE rule files: `php lupo-scripts/propagate_agent_rules.php --target=<your-agent>`.
3. Add a validation test (e.g. `lupo-tests/unit/<agent>_rules_enforcement.php`) following existing patterns.

---

## 4. Development Rules & Constraints

### Critical Rules (Must Follow)

All development must comply with the **[Complete Root Rules](lupo-rules/root/README.md)**. Key constraints:

#### PHP & Environment
- **PHP 7.4+ Required** - Minimum runtime per `lupo-rules/root/php-7-4-compatibility.md`; avoid PHP 8.0+ only syntax (union types, `match`, enums, named arguments, attributes, `readonly`) in shared core paths unless a file is explicitly modern-only
- **No Composer** - Cannot use `composer.json` or `vendor/` directory
- **External Libraries** - Self-contained libraries in `lupo-includes/` allowed per EXTERNAL_LIBRARIES_DOCTRINE
- **No Frameworks** - No Laravel, Symfony, or Blade templates
- **Shared Hosting Compatible** - Must work in subdirectories with 64MB memory limit
- **Configuration File** - `lupopedia-config.php` searched in specific order; must NOT be web-accessible. See **[Configuration Doctrine](lupo-docs/doctrine/CONFIGURATION_DOCTRINE.md)**

#### Database & Architecture
- **No Foreign Keys** - Referential integrity in application code only
- **No DB Logic** - No triggers, stored procedures, or DB-generated timestamps
- **Registry Allocation** - Use deterministic ID allocation, no `AUTO_INCREMENT`
- **Timestamp Format** - BIGINT UTC in YYYYMMDDHHIISS format
- **Soft Delete** - Use `is_deleted`/`deleted_ymdhis` where applicable

#### ASCII-Only Formatting
- **ASCII Only** - All text across all layers (docs, code, UI, data) MUST use only U+0020 through U+007E. Files are UTF-8 encoded but must contain only ASCII characters.
- **Prohibited Characters** - No emojis, no unicode arrows (`->` is fine), no box drawings, no em-dashes (`--` is fine), no curly quotes (`"` is fine). 
- **Mojibake** - Clean it up when you see it naturally during edits, but strictly write clean ASCII yourself.

### ASCII cleanup (incremental)

All repository content MUST be ASCII-only.

Forbidden:
- smart quotes
- em dash / en dash
- arrows
- emoji
- any non-ASCII symbols

If a character cannot be typed directly in a basic ASCII editor, it is forbidden.

When modifying any file:

- Agents MUST scan for non-ASCII characters
- Any detected non-ASCII characters MUST be replaced with ASCII equivalents

Examples:
- smart quotes -> straight quotes
- em dash -> double hyphen (--)
- arrows -> ASCII arrows (->)

This cleanup is:
- REQUIRED when touching a file
- LIMITED to the file being edited
- NOT applied to the entire repository

Agents MUST NOT perform repository-wide encoding cleanup unless explicitly instructed by a maintainer.
No bulk rewrites. No global search-and-replace across all files.

If non-ASCII is found:
- Fix it in the current file
- Continue work
- Do NOT stop or escalate unless encoding prevents parsing

If an agent introduces non-ASCII characters:
- Treat it as a NON_ASCII_VIOLATION
- Correct it immediately in the same change
- Do not leave non-ASCII content in committed files

See AGENTS.md for violation code definitions including NON_ASCII_VIOLATION.

#### Multi-Agent Coordination
- **Channel-Based Work** - All work scoped by `channel_id`
- **Agent Registration** - Must be registered before operating
- **Channel Security** - Authenticated sessions required for posting
- **Non-Interference** - Reviewer agents (LILITH) do not interfere with execution

### Quick Checklist Before Coding

```bash
# Check PHP compatibility
php -l your_file.php

# Check for Composer violations
find . -name "composer.json" -o -name "vendor" -type d

# Check for framework violations  
grep -r "@extends\|@section\|{{ " --include="*.php"
```

### What Breaks the System

| Action | Consequence |
|--------|-------------|
| Adding foreign keys | Breaks federation, migration failures |
| Using Composer | Shared hosting incompatibility |
| Using Laravel/Blade | Framework dependency violation |
| Hard-deleting rows | Breaks audit trail |
| Ignoring channel scope | Work becomes untraceable |

---

## 5. Non-Negotiable Rules (Legacy)

Do **not** violate these. They are enforced by doctrine and root rules.

- **No foreign keys** -- Do not add database FKs. Referential integrity is in application code.
- **No hidden DB logic** -- No triggers, stored procedures, or DB-generated timestamps. All logic and timestamps in application code.
- **Registry / allocator for reserved entities** -- For actors, channels, collections, and other registry-backed entities: allocate via registry workflow; insert with explicit ID; do not use `lastInsertId()`.
- **Soft-delete conventions** -- Where tables use `is_deleted` / `deleted_ymdhis`, set them in application code; filter active rows with `WHERE is_deleted = 0`.
- **Channel-based work context** -- Work and artifacts are scoped by `channel_id`; do not ignore channel boundaries.
- **Channel posting security** -- Posting to a channel via the message API requires authenticated session; actor identity is always taken from the server (never from client-supplied `actor_id`). Only channel members (or global admins) can post; see `lupo-includes/modules/api/channels-api.php`.
- **Lilith non-interference** -- Lilith (actor_id 2) is a non-interfering reviewer; see `lupo-rules/root/lilith-noninterference-doctrine.md`. Reviewer agents coexist with developer/orchestrator agents; reviewer role does not grant authority over other agents' work.
- **Documentation is first-class** -- Doctrine files and canonical docs define behavior; do not contradict them or invent patterns that break federation, lineage, or deterministic behavior.
- **Timestamps** -- Use BIGINT UTC `YYYYMMDDHHIISS`; set in application code (e.g. `gmdate('YmdHis')`). No `CURRENT_TIMESTAMP`, no `ON UPDATE`.

### What breaks the system - don't do these

| Action | Consequence |
|--------|-------------|
| Adding a foreign key | Breaks federation; causes migration and import failures |
| Using `lastInsertId()` for actors/channels/registry-backed tables | ID collision across nodes; breaks deterministic identity |
| Hard-deleting rows (instead of soft delete where applicable) | Breaks audit trail and data lineage |
| Ignoring channel scope | Work becomes untraceable; multi-agent context is lost |
| Writing to logs without timestamps | Handoff and continuity become impossible |

---

## 5. How New IDE Agents Should Begin

1. **Read** ONBOARDING.md, then README.md, then CHANGELOG.md.
2. **Identify** current repo version (e.g. from CHANGELOG or `lupo-docs/version.md`).
3. **Review** root rules ([lupo-rules/root/README.md](lupo-rules/root/README.md)) and relevant doctrine before changing schema or architecture.
4. **Register** as an actor if you are a new IDE agent ([lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md)).
5. **Preserve** existing conventions; avoid speculative rewrites.
6. **Make changes** in a lineage-safe way (deterministic IDs, no hidden side effects, docs updated when behavior changes).
7. **Update** docs and CHANGELOG when your implementation changes them.

### First-time setup for IDE agents

After reading onboarding, complete these setup steps:

1. **Clone the repository** (if not already done).
2. **Run the installer** via browser or CLI to verify database connection (see [README.md](README.md) for install steps).
3. **Register as an actor** using the [Actor Registration Checklist](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md).
4. **Run the propagation script** to get IDE-specific rules:
   ```bash
   php lupo-scripts/propagate_agent_rules.php --target=<your-agent-name>
   ```
   Use `cursor`, `kiro`, `windsurf`, `cascade`, `idea` (JetBrains), `lilith`, or `all` as needed.
5. **Verify your actor** appears in `lupo-database/lupopedia/actors/actor_id/registry.json` (or the canonical registry path for your setup).
6. **Join channel 42** (Lupopedia Development) to see ongoing work; channel context is in [lupo-docs/doctrine/SESSION_DOCTRINE.md](lupo-docs/doctrine/SESSION_DOCTRINE.md) and root rule CTX001.

### Agent status: already registered vs new vs integration-only

Before following "new agent" registration, check which case applies:

| State | What to do |
|-------|------------|
| **A -- Agent already exists** | Do **not** register again. Confirm your `actor_id` and slug in the registry ([lupo-database/lupopedia/actors/actor_id/registry.json](lupo-database/lupopedia/actors/actor_id/registry.json)). Run rules propagation for your target (e.g. `--target=cascade`). Proceed with integration and contribution only. |
| **B -- Agent does not exist** | Follow the full [Actor Registration Checklist](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md): allocate ID, add registry entry, persist in DB or fallback, then add rules propagation support if your IDE is not yet a target. |
| **C -- Agent exists but not fully integrated** | No new actor registration. Add or complete: rules propagation target (see [Extending rules propagation](#extending-rules-propagation)), validation test parity, and any agent-specific config/docs. |

If you are unsure, check the registry first; many IDE agents (e.g. Cascade, actor_id 105) are already registered and need only integration work.

---

## 6. How External LLM Agents Should Begin

If you are an **external agent** (e.g. OpenAI, ChatGPT) without full repo-native continuity:

- **Treat repo documentation as source of truth** -- Do not assume hidden memory or prior context. Rely on ONBOARDING.md, README, CHANGELOG, root rules, and doctrine files.
- **Distinguish known facts from assumptions** -- If a path or rule is uncertain, read the file or state the assumption explicitly.
- **Preserve deterministic architecture** -- Do not introduce FKs, DB logic, or patterns that conflict with doctrine.
- **Do not invent** repo structure, filenames, doctrine, or system behavior. Use only what exists in the repo or is explicitly stated in canonical docs.
- **Read first** -- Same order as IDE agents: ONBOARDING -> README -> CHANGELOG -> root rules -> doctrine relevant to your task.

---

## 7. How to Continue Existing Work

When picking up work started by another agent or human:

1. **Read** [CHANGELOG.md](CHANGELOG.md) for recent changes and version.
2. **Review** current task docs, prompts, and owning channel thread/task artifacts (e.g. under `lupo-channels/{channel_id}/threads/` and `lupo-channels/{channel_id}/tasks/`).
3. **Check** structured logs if present (e.g. `lupo-logs/admin/`, `lupo-logs/activity/`) for handoff context, `task_context`, and `handoff_from` / `handoff_to`.
4. **Identify** the current target version and affected files before editing.
5. **Avoid** duplicate or contradictory edits; align with existing conventions and doctrine.

**Handoff best practice:** Publish a final checkpoint/handoff post in the owning thread directory and/or update the owning task note under `lupo-channels/{channel_id}/tasks/`; include `channel_id` and linkage to the version TODO (`lupo-docs/versions/<version>/TODO.md`) when applicable. Append to logs with timestamp and actor.

Continuity and handoff rules are detailed in [lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md](lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md) (IACP).

---

## 8. Documentation and Logging Expectations

Agents are expected to leave work in a **resumable state**:

- **Structured documentation** -- Use existing doctrine and doc structure; update docs when you change behavior or schema.
- **Thread/task artifacts** -- Where relevant, leave checkpoint posts or task summaries under the owning thread/task directories in `lupo-channels/{channel_id}/` so the next agent can see what was done.
- **Structured logs** -- Append-only logs under `lupo-logs/` (e.g. admin, activity, agents) with fields such as `timestamp`, `actor_id`, `event_type`, `file_path`, `task_context`; prefer BIGINT UTC `YYYYMMDDHHIISS` for timestamps.
- **CHANGELOG** -- Update CHANGELOG when your changes warrant it (new features, doctrine changes, notable fixes).
- **Machine- and human-readable** -- Prefer formats that support both audit and handoff (e.g. JSONL for logs, Markdown for status).

### Quick commands

| Task | Command |
|------|---------|
| Check system health | `php lupo-bin/lupo.php doctor` |
| See your identity | `php lupo-bin/lupo.php whoami` |
| Run rule propagation | `php lupo-scripts/propagate_agent_rules.php --target=<agent>` (e.g. `cursor`, `kiro`, `windsurf`, `cascade`, `idea`, `all`) |
| Generate TOONs from SQL | `python lupo-scripts/generate_toon_from_sql.py` |
| View recent logs | Inspect `lupo-logs/admin/` or `lupo-logs/activity/` (files may be dated or named per local convention) |
| Validate Cascade rules | `php lupo-tests/unit/cascade_rules_enforcement.php` (similar tests exist for cursor, kiro, windsurf) |

### Extending rules propagation

To add a **new IDE agent target** to the rules propagation system (e.g. for an already-registered agent that does not yet have a target):

- **Where:** `lupo-scripts/propagate_agent_rules.php`. Canonical root rules live in `lupo-rules/root/*.md`.
- **How to add a target:** (1) Add the target name to `$validTargets`. (2) Define a directory (e.g. `$newAgentDir = $repoRoot . '/.newagent'`). (3) Implement a `write_newagent_outputs($dir, $rules)` function following the pattern of `write_windsurf_outputs` or `write_cascade_outputs` (JSON index, `rules/*.md` with LUPOPEDIA HEADERS, README). (4) Call it when `$target === 'all' || $target === 'newagent'`.
- **Output location:** Each target writes to its own directory (e.g. `.cascade/`, `.windsurf/`) with `lupopedia_rules.json` and `rules/<slug>.md`.
- **Validation:** Add a unit test under `lupo-tests/unit/` following `cascade_rules_enforcement.php` or `windsurf_rules_enforcement.php` to verify artifacts exist, JSON is valid, rules match canonical root, and files have required headers.
- **Avoid duplicate targets:** Check `$validTargets` and existing `write_*_outputs` functions before adding; do not register the same agent twice.

---

## 9. Practical "Do This First" Checklist

- [ ] Read [ONBOARDING.md](ONBOARDING.md)
- [ ] Read [README.md](README.md)
- [ ] Read [CHANGELOG.md](CHANGELOG.md)
- [ ] Confirm current version
- [ ] Review [lupo-rules/root/README.md](lupo-rules/root/README.md) and relevant doctrine
- [ ] Check current task context (thread/task state, logs, prompts)
- [ ] Identify affected files before editing
- [ ] Make changes consistently with doctrine
- [ ] Update docs and CHANGELOG when appropriate

---

## Getting help

- **Channel 42** -- Primary development channel. Join to see ongoing work; channel context is documented in doctrine and root rules (CTX001).
- **Channel threads/tasks** -- Check `lupo-channels/42/threads/` and `lupo-channels/42/tasks/` for recent activity and handoff reports.
- **Root rules** -- If unsure, check [lupo-rules/root/](lupo-rules/root/README.md) before acting; root rules are the source of truth for constraints.

---

## 10. Where to Go Next

| If you want to... | Go here |
|-------------------|--------|
| Understand database schema and tables | [lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md](lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md) |
| Understand channels and context | [lupo-docs/doctrine/SESSION_DOCTRINE.md](lupo-docs/doctrine/SESSION_DOCTRINE.md), [lupo-rules/root/channels-federation-offline-session-doctrine.md](lupo-rules/root/channels-federation-offline-session-doctrine.md) (CTX001) |
| Register an actor | [lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md) |
| Review all root rules | [lupo-rules/root/README.md](lupo-rules/root/README.md) |
| Find latest changes | [CHANGELOG.md](CHANGELOG.md) |
| Understand logging and continuity | [lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md](lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md) |
| Understand philosophy and design | [EXECUTIVE_SUMMARY.md](EXECUTIVE_SUMMARY.md) |
| Implementation and install | [README.md](README.md), [AGENTS.md](AGENTS.md) |
