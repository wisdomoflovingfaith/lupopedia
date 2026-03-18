# Lupopedia — Onboarding

**Operational entry point for new agents and contributors.**  
Read this first to know what to do when you enter the repo.

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

- **Doctrine-driven architecture** — Rules and constraints are explicit in doctrine files and root rules; behavior is not implied by framework defaults.
- **Deterministic identity** — Actors, channels, and other entities use registry-based ID allocation, not ad hoc AUTO_INCREMENT.
- **Channel-scoped work** — Tasks, dialogs, and artifacts are scoped by `channel_id`; channels are the primary coordination unit.
- **Documentation as architecture** — Doctrine, table docs, and canonical references are implementation-critical; they are read before changing schema or behavior.
- **Multi-agent collaboration** — Multiple IDE agents (and humans) work in the same repo; continuity, logging, and handoff are built into the model.

### Multi-agent coordination (4.0.80+ — binding)

Read **[lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md)** before claiming coordination roles or naming artifacts.

- **Eleven Primary Coordination Personas** — WOLFIE, LEXA, ANUBIS, HEIMDALL, SESHAT, ATHENA, MAAT, THEMIS, THOTH, JANUS, ROSE. They are the canonical coordination layer (directives, enforcement, custody, review, strategy, etc.).
- **Specialized agents (90+)** — HERMES, IRIS, LILITH, ASCLEPIUS, and others operate in **categories** (technical support, contrasting perspectives, etc.). They are **not** the eleven coordination personas; they work under primary-persona and channel rules.
- **IDE faucets** — Cursor, Windsurf, Kiro, Cascade, Warp, Zencoder, Antigravity are **human interfaces**; doctrine requires routing work through primary personas and registered channel context—not treating an IDE as the sole orchestration authority.
- **Channel context** — All coordination work is invalid without `channel_id` (default multi-agent workspace: **42**). Confirm membership where posting or acting on channel data.
- **Artifacts** — Proof of coordination lives under **`lupo-channels/{channel_id}/`** (threads/tasks/broadcasts/direct) with the artifact families defined in the doctrine (e.g. `WOLFIE_DIRECTIVE_*`, `SESHAT_REVIEW_*`). `lupo-docs/status/` is archival/legacy only; the channel system is authoritative for continuity.
- **Task authority** — For the active release cycle, the coordination TODO is the version file **`lupo-docs/versions/<current_version>/TODO.md`** (resolve `<current_version>` from [CHANGELOG.md](CHANGELOG.md) or `config/global_atoms.yaml`). Update it when moving task state; link tasks to their owning thread/task checkpoint artifacts as doctrine requires.

---

## 3. First Files to Read

### Reading order clarification

| Read this first | Why it matters |
|-----------------|----------------|
| [ONBOARDING.md](ONBOARDING.md) | Operational quick-start – read this first to understand what actions to take |
| [lupo-docs/INIT_README.md](lupo-docs/INIT_README.md) | Init doctrine and prerequisites – read before working with `lupopedia.init` headers |

### Prioritized file list

| Read this first | Why it matters |
|-----------------|----------------|
| [ONBOARDING.md](ONBOARDING.md) | Operational quick-start (this file) |
| [README.md](README.md) | High-level system overview, install, required reading |
| [CHANGELOG.md](CHANGELOG.md) | Current version and latest changes |
| [lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md) | Eleven personas, artifacts, channels, TODO authority (multi-agent work) |
| [lupo-rules/root/README.md](lupo-rules/root/README.md) | Root rules index and non-negotiable constraints |
| [lupo-docs/doctrine/DATABASE_DOCTRINE.md](lupo-docs/doctrine/DATABASE_DOCTRINE.md) | Core database rules (no FKs, no DB logic, timestamps, registry, soft deletes) |
| [lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md) | If you need to register as an actor (new IDE or external agent) |
| [lupo-docs/doctrine/AGENT_REGISTRY.md](lupo-docs/doctrine/AGENT_REGISTRY.md) | Canonical human-readable reference for actor identity, propagation support, and IDE roles |
| [EXECUTIVE_SUMMARY.md](EXECUTIVE_SUMMARY.md) | Philosophy and architecture (why the system is designed this way) |

---

## Agent Status Determination

Before proceeding with onboarding, determine your agent's status.

### State A — Already registered

Your `actor_id` already exists in:

- `lupo-database/lupopedia/actors/actor_id/registry.json`
- `lupo_actors` (when the database is available)

**Actions:**

- Verify your identity in the registry (canonical actor_id and slug).
- Run rule propagation: `php lupo-scripts/propagate_agent_rules.php --target=<your-agent>`.
- Begin contributing; no new registration.

**Example:** Cursor (actor_id **102**, slug `cursor`).

---

### State B — New agent

You do not exist in the actor registry.

**Actions:**

1. Follow the full [Actor Registration Checklist](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md).
2. Add registry entry to `lupo-database/lupopedia/actors/actor_id/registry.json`.
3. Insert actor into `lupo_actors` (or document fallback when DB is unavailable).
4. Configure propagation target if your IDE is not yet supported (see [Extending rules propagation](#extending-rules-propagation)).

---

### State C — Exists but needs integration

Your actor exists in the registry but your IDE is not yet supported in rule propagation.

**Actions:**

1. Add your target to [lupo-scripts/propagate_agent_rules.php](lupo-scripts/propagate_agent_rules.php) (see [Extending rules propagation](#extending-rules-propagation)).
2. Generate your IDE rule files: `php lupo-scripts/propagate_agent_rules.php --target=<your-agent>`.
3. Add a validation test (e.g. `lupo-tests/unit/<agent>_rules_enforcement.php`) following existing patterns.

---

## 4. Non-Negotiable Rules

Do **not** violate these. They are enforced by doctrine and root rules.

- **No foreign keys** — Do not add database FKs. Referential integrity is in application code.
- **No hidden DB logic** — No triggers, stored procedures, or DB-generated timestamps. All logic and timestamps in application code.
- **Registry / allocator for reserved entities** — For actors, channels, collections, and other registry-backed entities: allocate via registry workflow; insert with explicit ID; do not use `lastInsertId()`.
- **Soft-delete conventions** — Where tables use `is_deleted` / `deleted_ymdhis`, set them in application code; filter active rows with `WHERE is_deleted = 0`.
- **Channel-based work context** — Work and artifacts are scoped by `channel_id`; do not ignore channel boundaries.
- **Channel posting security** — Posting to a channel via the message API requires authenticated session; actor identity is always taken from the server (never from client-supplied `actor_id`). Only channel members (or global admins) can post; see `lupo-includes/modules/api/channels-api.php`.
- **Lilith non-interference** — Lilith (actor_id 2) is a non-interfering reviewer; see `lupo-rules/root/lilith-noninterference-doctrine.md`. Reviewer agents coexist with developer/orchestrator agents; reviewer role does not grant authority over other agents' work.
- **Documentation is first-class** — Doctrine files and canonical docs define behavior; do not contradict them or invent patterns that break federation, lineage, or deterministic behavior.
- **Timestamps** — Use BIGINT UTC `YYYYMMDDHHIISS`; set in application code (e.g. `gmdate('YmdHis')`). No `CURRENT_TIMESTAMP`, no `ON UPDATE`.

### What breaks the system – don't do these

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

Before following “new agent” registration, check which case applies:

| State | What to do |
|-------|------------|
| **A — Agent already exists** | Do **not** register again. Confirm your `actor_id` and slug in the registry ([lupo-database/lupopedia/actors/actor_id/registry.json](lupo-database/lupopedia/actors/actor_id/registry.json)). Run rules propagation for your target (e.g. `--target=cascade`). Proceed with integration and contribution only. |
| **B — Agent does not exist** | Follow the full [Actor Registration Checklist](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md): allocate ID, add registry entry, persist in DB or fallback, then add rules propagation support if your IDE is not yet a target. |
| **C — Agent exists but not fully integrated** | No new actor registration. Add or complete: rules propagation target (see [Extending rules propagation](#extending-rules-propagation)), validation test parity, and any agent-specific config/docs. |

If you are unsure, check the registry first; many IDE agents (e.g. Cascade, actor_id 105) are already registered and need only integration work.

---

## 6. How External LLM Agents Should Begin

If you are an **external agent** (e.g. OpenAI, ChatGPT) without full repo-native continuity:

- **Treat repo documentation as source of truth** — Do not assume hidden memory or prior context. Rely on ONBOARDING.md, README, CHANGELOG, root rules, and doctrine files.
- **Distinguish known facts from assumptions** — If a path or rule is uncertain, read the file or state the assumption explicitly.
- **Preserve deterministic architecture** — Do not introduce FKs, DB logic, or patterns that conflict with doctrine.
- **Do not invent** repo structure, filenames, doctrine, or system behavior. Use only what exists in the repo or is explicitly stated in canonical docs.
- **Read first** — Same order as IDE agents: ONBOARDING → README → CHANGELOG → root rules → doctrine relevant to your task.

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

- **Structured documentation** — Use existing doctrine and doc structure; update docs when you change behavior or schema.
- **Thread/task artifacts** — Where relevant, leave checkpoint posts or task summaries under the owning thread/task directories in `lupo-channels/{channel_id}/` so the next agent can see what was done.
- **Structured logs** — Append-only logs under `lupo-logs/` (e.g. admin, activity, agents) with fields such as `timestamp`, `actor_id`, `event_type`, `file_path`, `task_context`; prefer BIGINT UTC `YYYYMMDDHHIISS` for timestamps.
- **CHANGELOG** — Update CHANGELOG when your changes warrant it (new features, doctrine changes, notable fixes).
- **Machine- and human-readable** — Prefer formats that support both audit and handoff (e.g. JSONL for logs, Markdown for status).

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

- **Channel 42** — Primary development channel. Join to see ongoing work; channel context is documented in doctrine and root rules (CTX001).
- **Channel threads/tasks** — Check `lupo-channels/42/threads/` and `lupo-channels/42/tasks/` for recent activity and handoff reports.
- **Root rules** — If unsure, check [lupo-rules/root/](lupo-rules/root/README.md) before acting; root rules are the source of truth for constraints.

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
