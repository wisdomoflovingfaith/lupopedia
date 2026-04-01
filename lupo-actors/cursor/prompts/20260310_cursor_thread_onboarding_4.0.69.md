---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "lupo-prompts/cursor/20260310_cursor_thread_onboarding_4.0.69.md"
  web_path: "http://www.lupopedia.com/prompts/cursor/thread_onboarding_4.0.69"
  last_modified_utc: "20260310"
  channel_id: 42
  actor_id: 1003
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "prompt"
  artifact_kind: "onboarding"
  purpose: "Everything a new Cursor thread needs to read and know when working on Lupopedia project and version 4.0.69"
  tags: ["cursor", "onboarding", "4.0.69", "doctrine", "context"]
lupopedia.footer:
  last_verified: "20260310"
  last_verified_by: "cursor"
---
# file: Cursor thread onboarding 4.0.69 — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/prompts/cursor/thread_onboarding_4.0.69

# Cursor prompt — New thread onboarding (Lupopedia 4.0.69)

**Use this prompt when starting a new Cursor chat/thread** on the Lupopedia project. It gives required context: version, doctrines, schema, rules, and where to find everything.

---

## 1. Version and scope

- **Current version:** 4.0.69
- **Upgrade path:** Only **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**. No Lupopedia→Lupopedia upgrade until 4.1.0 (single-install doctrine).
- **Version files:** `LUPEDIA_VERSION`, `lupo-includes/version.php`, `lupo-config/global_atoms.yaml` (and config variants), `README.md`, `CHANGELOG.md`.

---

## 2. Must-read files (in order)

| Priority | File | Purpose |
|----------|------|--------|
| 1 | **AGENTS.md** | Critical doctrines, database rules, timestamp rules, architecture, three SQL entrypoints, key directories, PDO_DB usage. |
| 2 | **CHANGELOG.md** | Full 4.0.69 summary: dialog unification, LUPOPEDIA HEADERS, root rules, sync script, README/GitHub, Fallback & Actor–Faucet ontology, Human Actor ID. |
| 3 | **.cursor/rules/** | Root rules applied in Cursor (synced from `lupo-rules/root/*.md`). All 16 .mdc files; run `php lupo-scripts/propagate_agent_rules.php` after editing root rules. |
| 4 | **lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md** | Header format: `---` first, YAML blocks (`lupopedia.*`), then `---`, then identity line `# file: ...`, then body. Session block in `lupopedia.session`; session files at `lupo-database/sessions/{session_id}.md`. |
| 5 | **lupo-docs/doctrine/COMMUNICATION_DOCTRINE.md** | All communication uses **lupo_dialog_*** only (`lupo_dialog_channels`, `lupo_dialog_threads`, `lupo_dialog_messages`). No `lupo_threads` or `lupo_messages`. |
| 6 | **lupo-docs/doctrine/ActorFaucetOntology.md** | Actor = identity, rules, skills (e.g. Wolfie). Faucet = execution surface (Cursor, Kiro, OpenAI API). **IDE agents are faucets, not actors.** Fallback routes between faucets. `lupo_agent_faucets.faucet_class` = `ide` or `llm`. |
| 7 | **lupo-docs/doctrine/FallbackDoctrine.md** | Mandatory fallback invariant; rule_id 1003; seed `seed_fallback_rule_4.0.69.sql`. |
| 8 | **lupo-docs/doctrine/HumanActorIdDoctrine.md** | Human actors must have `actor_id >= 1000`. No AUTO_INCREMENT on `lupo_actors`; use `lupo_registry_open`. |
| 9 | **lupo-docs/HELP.md** | Communication system, CLI (whoami, doctor, threads, messages, send), lupo-rules/skills commands. |
| 10 | **README.md** | Project overview, GitHub Repository Strategy (current repo, future org, CRAFTY_SYNTAX upstream), install, LUPOPEDIA HEADERS at top. |

---

## 3. Database doctrine (non-negotiable)

| Rule | Meaning |
|------|--------|
| **No foreign keys / triggers / procedures** | Relationships and logic in application code only. |
| **Timestamps** | **BIGINT** UTC `YYYYMMDDHHIISS` (e.g. `20260310120000`). Set in PHP with `gmdate('YmdHis')`. No DATETIME, no TIMESTAMP, no epoch. |
| **Integer types** | BIGINT, INT, SMALLINT, TINYINT only. No display widths, no UNSIGNED, no BOOLEAN. |
| **Table prefix** | Never hardcode `lupo_`. Use `LUPO_TABLE_PREFIX`. |
| **Primary keys** | `<table_singular>_id` (e.g. `actor_id`, `dialog_message_id`). No generic `id`. |
| **Reserved IDs** | Registry-backed tables (e.g. `lupo_actors`, `lupo_channels`) do **not** use AUTO_INCREMENT; application supplies explicit ID. Check before INSERT → UPDATE or INSERT. |
| **Human actor ID** | Human actors must have `actor_id >= 1000` (HumanActorIdDoctrine.md). |
| **No information_schema** | Use `SHOW TABLES`, `SHOW CREATE TABLE`, and TOON files; shared hosting may not expose information_schema. |

**Schema source of truth:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`. TOONs in `lupo-database/lupopedia/toon/` (or project TOON path); regenerate with `python lupo-scripts/generate_toon_files.py`.

---

## 4. Communication and tables

- **Canonical communication tables:** `lupo_dialog_channels`, `lupo_dialog_threads`, `lupo_dialog_messages` only. Use these for all threads and messages (Channel 42, version threads, live chat).
- **Removed:** `lupo_threads` and `lupo_messages` are dropped from schema (migration `20260310_remove_duplicate_thread_message_tables.sql`). Do not reference them.
- **Code:** `lupo-includes/Dialog/Database/DialogDatabase.php` and channel/CLI code use `lupo_dialog_messages` (not `dialog_doctrine`).

---

## 5. Root rules and Cursor rules

- **Root rules** (all IDE/code-writing agents): `lupo-rules/root/*.md` (17 files including README). Attached to actor_id 1 via `seed_actor_1_cursor_rules_4.0.68.sql` (meta_type `root_rule`, paths `lupo-rules/root/*.md`).
- **Cursor IDE rules:** `.cursor/rules/*.mdc` are synced **from** `lupo-rules/root/*.md`. After editing any file in `lupo-rules/root/`, run:  
  `php lupo-scripts/propagate_agent_rules.php`  
  so Cursor applies the updated text.

---

## 6. LUPOPEDIA HEADERS (file format)

- **First line of file:** `---`
- **Then:** YAML blocks in canonical order: `lupopedia.headers`, `lupopedia.session` (optional), `lupopedia.edges`, `lupopedia.footer`, etc. Use **lupopedia.*** block names (4.0.69+); legacy `flare.*`/`flame.*` accepted.
- **Then:** `---`
- **Then:** Identity line: `# file: {title} — session: {session_name} — delegation: {chain} — web_path: {url}`  
  `{session_name}` from `lupopedia.session.session_name` when present (e.g. `L-LUPO-ROOT-CURSOR`).
- **Then:** Document body (headings and content).
- **Session files:** Stored at `lupo-database/sessions/{session_id}.md` (e.g. `L-LUPO-ROOT-CURSOR.md`).

---

## 7. Actor vs Faucet (ontology)

- **Actor:** Identity, rules, skills, persona, doctrine (e.g. Wolfie, Lilith). Holds rules and skills.
- **Faucet:** Execution environment + LLM + runtime config (temperature, model, context). Cursor, Kiro, Antigravity, Windsurf, OpenAI API, DeepSeek API are **faucets**. They do not have identity; they can be swapped or failed over. Fallback routes **between faucets**, not actors.
- **Schema:** `lupo_agent_faucets` has `faucet_class` = `'ide'` (IDE with filesystem/tools) or `'llm'` (direct API). Migration: `20260310_faucet_class.sql`.

---

## 8. GitHub and upstream

- **Current development repo:** `https://github.com/wisdomoflovingfaith/lupopedia` (through 4.1.0).
- **Future canonical org:** `https://github.com/lupopedia` (after 4.1.0; repos: core, web, cli, vercel, docs, ops).
- **Upstream (Crafty Syntax 3.7.5):** `https://github.com/lupopedia/CRAFTY_SYNTAX` — the code Lupopedia is built from.

---

## 9. SQL and seeds (installer)

| Purpose | Path |
|--------|------|
| **Canonical schema (DDL)** | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` |
| **Seeds** | `lupo-database/lupopedia/mysql/seed/` (registry, lupo-actors/agents, rules, skills, root rules, fallback rule, etc.) |
| **Crafty → Lupopedia import** | `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` |

Fresh install: install SQL then seeds. Upgrade from Crafty: install SQL, seeds, then import. **No** Lupopedia→Lupopedia upgrade until 4.1.0.

---

## 10. PHP and code

- **PHP 5.3+** compatibility required: no `??`, no `[]` arrays (use `array()`), no arrow functions, no typed properties/return types in core paths.
- **Database:** Use **PDO_DB** only (`DatabaseFactory::getConnection()` or `lupo_get_db()`). Prepared statements with named placeholders; table prefix from config.
- **No Laravel, no middleware.** Plain PHP, our Session/Auth, our timestamp doctrine.

---

## 11. Migrations (one-time)

- `lupo-database/migrations/` — one-time SQL for existing DBs (e.g. drop duplicate tables, add `faucet_class`, record Human Actor ID doctrine). Consolidate into install SQL for clean installs; run migrations only when upgrading an existing DB.
- Record in `lupo_schema_migrations` (version, name, applied_ymdhis). Use `INSERT IGNORE` with unique version for idempotency.

---

## 12. Quick reference

- **Version:** 4.0.69
- **Session identity:** L-LUPO-ROOT-CURSOR (example)
- **Channel 42 thread:** `lupo-channels/42/threads/4.0.x/evolution_4_0_69.json`
- **Root rules index:** `lupo-rules/root/README.md`
- **Sync root → Cursor:** `php lupo-scripts/propagate_agent_rules.php`
- **CLI:** `php lupo-bin/lupo.php` (whoami, doctor, help, rules, skills, messages, send)
- **Table count:** From TOON files after `python lupo-scripts/generate_toon_files.py`; doc ceiling 199 tables, optimization trigger 200+.

When in doubt, read **AGENTS.md**, **CHANGELOG.md** (4.0.69 section), and the doctrine files listed in §2.
