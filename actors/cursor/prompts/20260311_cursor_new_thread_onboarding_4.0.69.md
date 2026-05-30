---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/cursor/prompts/20260311_cursor_new_thread_onboarding_4.0.69.md
  web_path: https://www.lupopedia.com/lupopedia/actors/cursor/prompts/20260311_cursor_new_thread_onboarding_4.0.69.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: prompt
  artifact_kind: onboarding
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---
# file: Cursor new-thread onboarding 4.0.69 — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root (faucet: cursor) — web_path: http://www.lupopedia.com/prompts/cursor/new_thread_onboarding_4.0.69

# Cursor prompt — New thread onboarding (Lupopedia 4.0.69)

**Use this prompt when starting a new Cursor chat/thread** on the Lupopedia project. It gives session context, required doctrines, schema, rules, and where to find everything for version 4.0.69.

---

## 1. Session information (read this first)

- **Session identity:** When working as Wolfie (actor_id 1) via the Cursor faucet, session is **`L-LUPO-ROOT-CURSOR`** (session_id and session_name). Your runtime context (actor, channel, paired human) comes from the session file or from the `lupopedia.session` block in artifact headers.
- **Where session files live:** **`database/sessions/`**. Example: `database/sessions/L-LUPO-ROOT-CURSOR.md`. Each file has a **`lupopedia.session`** YAML block at the top (after the first `---`).
- **Required session fields (for consistency):** `session_id`, `session_name`, `actor_id`, `channel_id`, `federation_node_id`. Recommended: `paired_actor_id`, `system_version`, `channel_name`.
- **Source of truth:** When the database is available, **`lupo_sessions`** is the authority for runtime session state. Session files are portable snapshots / bootstrap for IDE agents. When the DB is **not** available, the system MAY read from **MD files** and **CSV files** under `database/` (fallback). See `docs/doctrine/SESSION_RECONCILIATION_DOCTRINE.md`.
- **Validate session consistency:** Run `php scripts/validate_session_consistency.php [--db] [--files-only]` to compare session files with `lupo_sessions` and report drift (no auto-correction).

---

## 2. Version and scope

- **Current version:** 4.0.69
- **Upgrade path:** Only **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**. No Lupopedia→Lupopedia upgrade until 4.1.0 (single-install doctrine).
- **Version files:** `LUPEDIA_VERSION`, `includes/version.php`, `config/global_atoms.yaml` (and config variants), `README.md`, `CHANGELOG.md`.

---

## 3. Must-read files (in order)

| Priority | File | Purpose |
|----------|------|--------|
| 1 | **AGENTS.md** | Critical doctrines, database rules, timestamp rules, architecture, three SQL entrypoints, key directories, PDO_DB usage. |
| 2 | **CHANGELOG.md** | Full 4.0.69 summary: dialog unification, LUPOPEDIA HEADERS, root rules, fallback, traits, identity/session/federation/edge doctrine, session validator, onboarding. |
| 3 | **.cursor/rules/** | Root rules applied in Cursor (synced from `rules/root/*.md`). Run `php scripts/propagate_agent_rules.php` after editing root rules. |
| 4 | **docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md** | **Canonical architecture:** actors, channels, edges, semantic layer, installation path, fallback (MD/CSV when DB down), traits table, full table list. Supersedes brainstorm. |
| 5 | **docs/doctrine/LUPOPEDIA_HEADERS/README.md** | Header format: `---` first, YAML blocks (`lupopedia.*`), then `---`, then identity line `# file: ...`, then body. Session in `lupopedia.session`; session files at `database/sessions/{session_id}.md`. |
| 6 | **docs/doctrine/COMMUNICATION_DOCTRINE.md** | All communication uses **lupo_dialog_*** only. No `lupo_threads` or `lupo_messages`. |
| 7 | **docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md** | Canonical separation: **actor** (identity), **faucet** (execution surface), **session** (runtime state), **trait** (intrinsic constraint), **role** (channel permission), **task** (transient work). |
| 8 | **docs/doctrine/ActorFaucetOntology.md** | Actor = identity, rules, skills (e.g. Wolfie). Faucet = execution surface (Cursor, Kiro, OpenAI API). **IDE agents are faucets, not actors.** `lupo_agent_faucets.faucet_class` = `ide` or `llm`. |
| 9 | **docs/doctrine/SESSION_RECONCILIATION_DOCTRINE.md** | When DB vs session file wins; when corrections allowed; who logs; required session file fields; no silent auto-correction. |
| 10 | **docs/doctrine/FEDERATION_SCOPING_DOCTRINE.md** | federation_node_id vs channel_id vs domain_id; when to use which; content scope. |
| 11 | **docs/doctrine/EDGE_VOCABULARY_DOCTRINE.md** | Canonical edge_type, relationship_type, object type pairs for lupo_edges and lupo_actor_edges. |
| 12 | **docs/doctrine/FallbackDoctrine.md** | Mandatory fallback invariant; rule_id 1003; fallback routes between faucets. |
| 13 | **docs/doctrine/HumanActorIdDoctrine.md** | Human actors must have `actor_id >= 1000`. Use `lupo_registry_open`; no AUTO_INCREMENT on lupo_actors. |
| 14 | **docs/status/DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69.md** | Decision: lupo_actor_traits table; actor-scoped traits only; no channel roles in this table. |
| 15 | **docs/HELP.md** | Communication system, CLI (whoami, doctor, threads, messages, send), rules/skills commands. |
| 16 | **README.md** | Project overview, GitHub Repository Strategy, CRAFTY_SYNTAX upstream, LUPOPEDIA HEADERS at top. |

---

## 4. Database doctrine (non-negotiable)

| Rule | Meaning |
|------|--------|
| **No foreign keys / triggers / procedures** | Relationships and logic in application code only. |
| **Timestamps** | **BIGINT** UTC `YYYYMMDDHHIISS`. Set in PHP with `gmdate('YmdHis')`. No DATETIME, no TIMESTAMP, no epoch. |
| **Integer types** | BIGINT, INT, SMALLINT, TINYINT only. No display widths, no UNSIGNED, no BOOLEAN. |
| **Table prefix** | Never hardcode `lupo_`. Use `LUPO_TABLE_PREFIX`. |
| **Primary keys** | `<table_singular>_id` (e.g. `actor_id`, `dialog_message_id`). No generic `id`. |
| **Reserved IDs** | Registry-backed tables do **not** use AUTO_INCREMENT; application supplies explicit ID. Check before INSERT → UPDATE or INSERT. |
| **Human actor ID** | Human actors must have `actor_id >= 1000`. |
| **No information_schema** | Use `SHOW TABLES`, `SHOW CREATE TABLE`, and TOON files. |

**Schema source of truth:** `database/lupopedia/mysql/install/install_new_lupopedia.sql`. TOONs in `database/lupopedia/toon/` (or project TOON path); regenerate with `python scripts/generate_toon_files.py`.

---

## 5. Communication and tables

- **Canonical communication tables:** `lupo_dialog_channels`, `lupo_dialog_threads`, `lupo_dialog_messages` only. Use these for all threads and messages.
- **Removed:** `lupo_threads` and `lupo_messages` are dropped. Do not reference them.
- **Actor traits:** `lupo_actor_traits` holds intrinsic actor constraints (trait_key, trait_value); actor-scoped only. Channel roles stay in `lupo_actor_channel_roles`.

---

## 6. Root rules and Cursor rules

- **Root rules:** `rules/root/*.md`. Attached to actor_id 1; meta_type `root_rule`.
- **Sync to Cursor:** After editing any file in `rules/root/`, run `php scripts/propagate_agent_rules.php` so `.cursor/rules/*.mdc` stay in sync.

---

## 7. LUPOPEDIA HEADERS (file format)

- **First line of file:** `---`
- **Then:** YAML blocks in canonical order: `lupopedia.headers`, `lupopedia.session` (optional), `lupopedia.edges`, `lupopedia.footer`, etc. Use **lupopedia.*** block names; legacy `flare.*`/`flame.*` accepted.
- **Then:** `---`
- **Then:** Identity line: `# file: {title} — session: {session_name} — delegation: {chain} — web_path: {url}`
- **Then:** Document body.
- **Session files:** `database/sessions/{session_id}.md`.

---

## 8. Actor vs Faucet (ontology)

- **Actor:** Identity, rules, skills, persona, doctrine (e.g. Wolfie, Lilith).
- **Faucet:** Execution environment + LLM + runtime config. Cursor, Kiro, Antigravity, OpenAI API are **faucets**. Fallback routes **between faucets**, not actors.
- **Trait:** Intrinsic actor constraint (stored in `lupo_actor_traits`). **Role:** Channel-local permission (`lupo_actor_channel_roles`). **Task:** Transient work item (`lupo_tasks`).

---

## 9. GitHub and upstream

- **Current development repo:** `https://github.com/wisdomoflovingfaith/lupopedia` (through 4.1.0).
- **Future canonical org:** `https://github.com/lupopedia` (repos: core, web, cli, vercel, docs, ops).
- **Upstream (Crafty Syntax 3.7.5):** `https://github.com/lupopedia/CRAFTY_SYNTAX`.

---

## 10. SQL and seeds (installer)

| Purpose | Path |
|--------|------|
| **Canonical schema (DDL)** | `database/lupopedia/mysql/install/install_new_lupopedia.sql` |
| **Seeds** | `database/lupopedia/mysql/seed/` |
| **Crafty → Lupopedia import** | `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` |

No Lupopedia→Lupopedia upgrade until 4.1.0.

---

## 11. PHP and code

- **PHP 5.3+** compatibility: no `??`, no `[]` arrays (use `array()`), no arrow functions, no typed properties/return types in core.
- **Database:** Use **PDO_DB** only (`DatabaseFactory::getConnection()` or `lupo_get_db()`). Prepared statements; table prefix from config.
- **No Laravel, no middleware.**

---

## 12. Migrations and scripts

- **Migrations:** `database/migrations/` — one-time SQL. Consolidate into install SQL for clean installs. Record in `lupo_schema_migrations`.
- **Session consistency:** `php scripts/validate_session_consistency.php [--db] [--files-only]` — report drift between session files and `lupo_sessions`; no auto-correction.

---

## 13. Quick reference

| Item | Value |
|------|--------|
| **Version** | 4.0.69 |
| **Session (example)** | L-LUPO-ROOT-CURSOR |
| **Session files** | `database/sessions/{session_id}.md` |
| **Channel 42** | Lupopedia Development (general) |
| **Channel 42 thread** | `channels/42/threads/4.0.x/evolution_4_0_69.json` |
| **Root rules** | `rules/root/README.md` |
| **Sync root → Cursor** | `php scripts/propagate_agent_rules.php` |
| **CLI** | `php bin/lupo.php` (whoami, doctor, help, rules, skills, messages, send) |
| **Table count** | From TOON files after `python scripts/generate_toon_files.py`; ceiling 199, trigger 200+ |

When in doubt, read **AGENTS.md**, **CHANGELOG.md** (4.0.69 section), and **docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md**.
