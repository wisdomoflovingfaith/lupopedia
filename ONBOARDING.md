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

---

## 3. First Files to Read

| Read this first | Why it matters |
|-----------------|----------------|
| [ONBOARDING.md](ONBOARDING.md) | Operational quick-start (this file) |
| [README.md](README.md) | High-level system overview, install, required reading |
| [CHANGELOG.md](CHANGELOG.md) | Current version and latest changes |
| [lupo-rules/root/README.md](lupo-rules/root/README.md) | Root rules index and non-negotiable constraints |
| [lupo-docs/doctrine/DATABASE_DOCTRINE.md](lupo-docs/doctrine/DATABASE_DOCTRINE.md) | Core database rules (no FKs, no DB logic, timestamps, registry, soft deletes) |
| [lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md) | If you need to register as an actor (new IDE or external agent) |
| [EXECUTIVE_SUMMARY.md](EXECUTIVE_SUMMARY.md) | Philosophy and architecture (why the system is designed this way) |

---

## 4. Non-Negotiable Rules

Do **not** violate these. They are enforced by doctrine and root rules.

- **No foreign keys** — Do not add database FKs. Referential integrity is in application code.
- **No hidden DB logic** — No triggers, stored procedures, or DB-generated timestamps. All logic and timestamps in application code.
- **Registry / allocator for reserved entities** — For actors, channels, collections, and other registry-backed entities: allocate via registry workflow; insert with explicit ID; do not use `lastInsertId()`.
- **Soft-delete conventions** — Where tables use `is_deleted` / `deleted_ymdhis`, set them in application code; filter active rows with `WHERE is_deleted = 0`.
- **Channel-based work context** — Work and artifacts are scoped by `channel_id`; do not ignore channel boundaries.
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
   Use `cursor`, `kiro`, `idea` (JetBrains), or `all` as needed.
5. **Verify your actor** appears in `lupo-database/lupopedia/actors/actor_id/registry.json` (or the canonical registry path for your setup).
6. **Join channel 42** (Lupopedia Development) to see ongoing work; channel context is in [lupo-docs/doctrine/SESSION_DOCTRINE.md](lupo-docs/doctrine/SESSION_DOCTRINE.md) and root rule CTX001.

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
2. **Review** current task docs, prompts, and status files (e.g. under `lupo-docs/status/`).
3. **Check** structured logs if present (e.g. `lupo-logs/admin/`, `lupo-logs/activity/`) for handoff context, `task_context`, and `handoff_from` / `handoff_to`.
4. **Identify** the current target version and affected files before editing.
5. **Avoid** duplicate or contradictory edits; align with existing conventions and doctrine.

Continuity and handoff rules are detailed in [lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md](lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md) (IACP).

---

## 8. Documentation and Logging Expectations

Agents are expected to leave work in a **resumable state**:

- **Structured documentation** — Use existing doctrine and doc structure; update docs when you change behavior or schema.
- **Status / task artifacts** — Where relevant, leave status files or task summaries under `lupo-docs/status/` so the next agent can see what was done.
- **Structured logs** — Append-only logs under `lupo-logs/` (e.g. admin, activity, agents) with fields such as `timestamp`, `actor_id`, `event_type`, `file_path`, `task_context`; prefer BIGINT UTC `YYYYMMDDHHIISS` for timestamps.
- **CHANGELOG** — Update CHANGELOG when your changes warrant it (new features, doctrine changes, notable fixes).
- **Machine- and human-readable** — Prefer formats that support both audit and handoff (e.g. JSONL for logs, Markdown for status).

### Quick commands

| Task | Command |
|------|---------|
| Check system health | `php lupo-bin/lupo.php doctor` |
| See your identity | `php lupo-bin/lupo.php whoami` |
| Run rule propagation | `php lupo-scripts/propagate_agent_rules.php --target=<agent>` (e.g. `cursor`, `kiro`, `idea`, `all`) |
| Generate TOONs from SQL | `python lupo-scripts/generate_toon_from_sql.py` |
| View recent logs | Inspect `lupo-logs/admin/` or `lupo-logs/activity/` (files may be dated or named per local convention) |

---

## 9. Practical "Do This First" Checklist

- [ ] Read [ONBOARDING.md](ONBOARDING.md)
- [ ] Read [README.md](README.md)
- [ ] Read [CHANGELOG.md](CHANGELOG.md)
- [ ] Confirm current version
- [ ] Review [lupo-rules/root/README.md](lupo-rules/root/README.md) and relevant doctrine
- [ ] Check current task context (status, logs, prompts)
- [ ] Identify affected files before editing
- [ ] Make changes consistently with doctrine
- [ ] Update docs and CHANGELOG when appropriate

---

## Getting help

- **Channel 42** — Primary development channel. Join to see ongoing work; channel context is documented in doctrine and root rules (CTX001).
- **Status files** — Check `lupo-docs/status/` for recent activity and handoff reports.
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
