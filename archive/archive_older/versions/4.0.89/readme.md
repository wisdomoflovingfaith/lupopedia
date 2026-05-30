---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: plan
  when_updated: "20260329235907"
  file_path_from_root: "docs/versions/4.0.89/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.89/README.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: plan
  artifact_kind: plan
  thread_id: "4-0-89-headers-release"
  content_id: 8425034527711385457
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Lupopedia Version 4.0.89 — LUPOPEDIA HEADERS release

**Version:** 4.0.89  
**Status:** Release verification logged (**`CHANGELOG.md`** — H5, H7, H8 dry-run parity). **Open:** WOLFIE tag decision, legacy DB row reconciliation, **`TODO.md`** H2/H4 items. Next IDE: **`WHAT_TO_DO_NEXT_SESSION.md`** (this folder).  
**Thread:** `4-0-89-headers-release`

**Overview (focus of 4.0.89):** This version **ships the LUPOPEDIA HEADERS** end-to-end story — binding doctrine, validators (Python + PHP), **import / regenerate** (`lupo_contents`, `lupo_metadata`, `lupo_edges`, `revision_history` policy), dual toolchain for **IDE agents** vs **shared-hosting / PHP agents**, and release-gate tests (**`TODO.md`**, **`CHANGELOG.md`**). In parallel, 4.0.89 is the line where **primary-key and allocation practice no longer depends on the removed registry tables** (`lupo_registry`, `lupo_registry_open`): new IDs follow project **application-layer generation** (timestamp-structured and table-specific rules) and **reserved-ID** doctrine where registry-backed entities apply — see **`CHANGELOG.md`** §1 and root database rules. **Database rules** (canonical for this version) remain: schema from **`install_new_lupopedia.sql`** and **`database/lupopedia/json/*.json`** mirrors, **PDO_DB** only, no DB-side logic (no FKs, triggers, auto-timestamps), **BIGINT** UTC **`YYYYMMDDHHIISS`** set in application code, and safe migration discipline. **A major 4.0.89 obligation** is that **all active code paths are updated to match that database reality** — no references to dropped tables, no stale column assumptions, imports and services using the same columns the install defines; drift between code and canonical schema is a **release blocker** alongside header readiness.

**See also:**

- [TODO.md](TODO.md) — Task breakdown and gates
- [CHANGELOG.md](CHANGELOG.md) — Detailed version overview and achievements

### Dual running log

Optional **`lupopedia.history`** in markdown pairs with **`lupo_contents.revision_history`** in the database after import. When the YAML key is **present**, import serializes/replaces the DB column; when **absent**, the DB value is preserved and regenerate emits history from DB. Binding rules and edge cases: [`LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md) (*Dual running log — file and database*). Release verification: **`TODO.md` H7** and **Release scope** criterion **10** below.

---

## Release scope (binding)

**4.0.89** ships when the following are **true**, documented, and tested:

1. **Doctrine & docs** — Binding [`rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md); companions under [`docs/doctrine/LUPOPEDIA_HEADERS/`](../../doctrine/LUPOPEDIA_HEADERS/) (FORMAT, TAXONOMY_REFERENCE, VALIDATORS, OPTIONAL_BLOCKS, VERIFICATION_GUIDE, alias file).
2. **Python validation** — `validate_lupopedia_headers.py` (required keys, `--check-db`), `validate_lupopedia_headers_universal.py` (cross-field, repo-root edges), `lib/header_validation.py`, `lib/header_db_sync.py`.
3. **PHP validation** — **`validate_lupopedia_headers.php`** + **`HeaderDbSync::validateFile`** (CLI parity with `validate_lupopedia_headers.py`); legacy **`LupopediaHeaderValidator.php`** refresh still tracked in **`TODO.md` H2.1**; `lupo.php` wiring as available.
4. **Web / operator surface** — Identify or implement the **admin/operator path** that runs header validation in the browser (today: primarily CLI; **gap** tracked in `TODO.md`).
5. **Import / regenerate** — **IDE agents** use Python: `import_content.py`, `generate_headers_from_db.py`, `ensure_imported.py`. **PHP agents** (shared hosting / LLM APIs) use PHP: `import_content.php`, `generate_headers_from_db.php`. Same deterministic `content_id` and sync semantics; **`TODO.md` H8** defines acceptance tests and the **IDE vs PHP agent** model.
6. **`*` layout** — Every release participant understands the **root `*` map** and doc authority: [`ORGANIZATION.md`](../../../ORGANIZATION.md) **§4** + [`docs/ORGANIZATION.md`](../../ORGANIZATION.md); verify **`TODO.md` H4.4**. Artifacts live in the correct trees per doctrine.
7. **IDE rule packs** — `.cursor/rules`, `.windsurf/rules`, and related prompts **reflect** `rules/root/` doctrines that affect headers (e.g. LUPOPEDIA HEADERS file order, FLIP→HEADERS rename, safe migration, PDO). Run or document `php scripts/propagate_agent_rules.php` when updating.
8. **Database & code alignment** — **`install_new_lupopedia.sql`** + **`database/lupopedia/json/*.json`** are authoritative; **`header_db_sync`** / Python + PHP import must match those columns. **Runtime PHP and tooling** must not assume removed objects (e.g. registry tables) or legacy columns; any feature touching `lupo_*` tables is verified against the current install. Schema changes follow project **migration / database doctrines** (no ad hoc production SQL).
9. **Release-gate testing** — At least **several** markdown imports; **read** headers from DB; **regenerate** YAML headers; compare; document results (see `TODO.md` checklist).
10. **Running log (`lupopedia.history`)** — Binding doctrine documents the **dual** audit trail: optional YAML block in `.md` files and JSON in **`lupo_contents.revision_history`** after import. **Before tag:** complete **`TODO.md` H7** (append event → import → verify DB → regenerate → validate; record outcome in `CHANGELOG.md` **Release verification** or channel 42).
11. **Dual import toolchains** — Python and PHP imports both land in the same tables with the same rules; cross-toolchain checks in **`TODO.md` H8.5–H8.6** satisfied before tag (or explicitly waived with recorded risk).
12. **PHP agent filesystem safety** — **PHP / LLM agents** **only** write under **`rules/`**, **`docs/`**, **`channels/`**, **`content/`**, with extensions **`.md`**, **`.txt`**, **`.yaml`**, **`.yml`**, **`.json`**, **`.csv`**, **`.xml`** — **no** **`.html` / `.htm` / `.js` / `.php`** or other scripts; **agent** context uses content scanning via **`AgentFileWriter`**. **IDE agents** (Cursor, Windsurf, Kiro) have **full repository write access** for development and are **not** gated by **`AgentFileWriter`**. Policy: **`TODO.md` H9**; distinction: **`docs/ORGANIZATION.md` §2.2**; deployment: **[`PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`](PHP_AGENT_FILESYSTEM_DEPLOYMENT.md)**.

---

## Deferred to 4.0.90

**Not** in 4.0.89 release criteria:

- Context model **database** work (`lupo_contexts`, automation, Channel 66, TASK_REGISTRY).
- **Crafty Syntax** feature **implementation** (backlog execution).
- Documentation clarity tasks **5.1–5.4** (nav, FLARE cleanup, IMPLEMENTATION bridge) as **release** blockers.

Those items live in **[`docs/versions/4.0.90/`](../4.0.90/README.md)** with carryover note **[`SCOPE_CARRYOVER_FROM_4_0_89.md`](../4.0.90/SCOPE_CARRYOVER_FROM_4_0_89.md)**.

**Historical changelog** (registry removal, WSL, manifesto, etc.) remains in **`CHANGELOG.md`** — it describes the **repo** state on this line; the **tagging decision** for 4.0.89 is **headers pipeline readiness** above.

---

## Files in this folder (headers release)

| File | Role |
|------|------|
| `README.md` | This scope + criteria |
| `PLAN.md` | Dependency-ordered **header release** plan |
| `TODO.md` | Header release tasks + test matrix |
| `CHANGELOG.md` | Repo changes (includes pre-refocus history) |
| `HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md` | DB-first header pipeline narrative |
| `PHP_AGENT_FILESYSTEM_DEPLOYMENT.md` | **H9.3** — optional Apache/nginx hardening for content directories |
| `crafty_syntax_backlog.md`, `legacy_research/`, etc. | **Reference** for 4.0.90 — not 4.0.89 exit criteria |

---

## Quick links

- Binding doctrine: [`rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md)  
- Doctrine index: [`docs/doctrine/LUPOPEDIA_HEADERS/README.md`](../../doctrine/LUPOPEDIA_HEADERS/README.md)  
- Validators: [`VALIDATORS_AND_TOOLING.md`](../../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md)  
- Doc tree / agent toolchains: [`docs/ORGANIZATION.md`](../../ORGANIZATION.md) (**§2.1** toolchains, **§2.2** IDE vs PHP filesystem boundaries)  
- 4.0.90 backlog: [`../4.0.90/README.md`](../4.0.90/README.md)

---

## PHP import quick reference (shared hosting + PHP agents)

**Canonical detail** (agent-type table, diagrams, acceptance tests **H8.5–H8.6**, dependencies): **[`TODO.md`](TODO.md) H8**; **PHP agent write boundaries** (content-only, no runtime trees): **H9** — not duplicated here.

**Requires:** `lupopedia-config.php`, **php-yaml** (`yaml_parse`), **bcmath** or **gmp**. Library: [`HeaderDbSync.php`](../../../includes/classes/HeaderDbSync.php).

```bash
# DB import only (default — neither toolchain modifies the .md file)
python scripts/import_content.py path/to/file.md
php scripts/import_content.php path/to/file.md

# Persist content_id in YAML (one-time migration / explicit sync — either toolchain)
python scripts/import_content.py --write-back path/to/file.md
php scripts/import_content.php path/to/file.md --write-back

php scripts/validate_lupopedia_headers.php path/to/file.md
php scripts/validate_lupopedia_headers.php path/to/file.md --check-db
php scripts/generate_headers_from_db.php --file-path path/to/file.md
```

---

**WOLFIE (actor_id 1)** — 4.0.89 scoped to LUPOPEDIA HEADERS release; broader roadmap → 4.0.90.
