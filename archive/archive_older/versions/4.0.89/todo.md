---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: todo
  when_updated: "20260329235907"
  file_path_from_root: "docs/versions/4.0.89/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.89/TODO.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: todo
  artifact_kind: task
  thread_id: "4-0-89-headers-todo"
  content_id: 8714183816754237706
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# TODO — Version 4.0.89 (LUPOPEDIA HEADERS release)

## 4.0.89 Overview

**Theme:** LUPOPEDIA HEADERS end-to-end + database authority

This release establishes the complete header infrastructure:

- **Headers as tagging spine:** Doctrine, Python + PHP validators, import/regenerate scripts, DB sync tables (`lupo_contents`, `lupo_metadata`, `lupo_edges`, `revision_history` policy).
- **Dual agent toolchains:** IDE agents (Cursor, Windsurf, Kiro) use **Python** and may edit **runtime/code** trees; **PHP agents** on the server use **PHP** import/validate for artifacts they author only under **`rules/`**, **`docs/`**, **`channels/`**, **`content/`** with **safe extensions** (no **`.html` / `.htm` / `.js` / `.php`** — see **H9**). Both toolchains produce the **same DB state** for a given markdown file (see **H8**).
- **Database authority:** Canonical schema in **`install_new_lupopedia.sql`** + **`database/lupopedia/json/*.json`**; no FK/triggers/auto-timestamps in DB; **BIGINT** UTC timestamps in application code.
- **PK registry removal:** IDs generated in the application layer (e.g. **`IdGenerator.php`**); **`lupo_registry`** and **`lupo_registry_open`** removed — see **`CHANGELOG.md`** §1.
- **Code ↔ DB alignment:** Drift from the canonical schema is a **release blocker** alongside header readiness.

**Gates:** **H1–H9** below; all must pass (or be explicitly waived with recorded risk) for **4.0.89** release.

**See also:** **[README.md](README.md)** (full narrative overview), **[CHANGELOG.md](CHANGELOG.md)** (version overview + achievements table), **[`ORGANIZATION.md`](../../../ORGANIZATION.md)** (root **`*` map**), **[`docs/ORGANIZATION.md`](../../ORGANIZATION.md)** (doc authority + **§2.2** PHP boundaries).

---

**Deferred backlog:** Context model, Crafty Syntax execution, doc-clarity 5.1–5.4 → **[`../4.0.90/TODO.md`](../4.0.90/TODO.md)**.

---

## H1 — Python validation (complete or verify)

| ID | Task | Status |
|----|------|--------|
| H1.1 | `validate_lupopedia_headers.py` — required keys match binding doctrine | DONE (verify on CI/manual) |
| H1.2 | `validate_lupopedia_headers_universal.py` — cross-field + `--check-db` | DONE (verify) |
| H1.3 | `lib/header_validation.py` / `header_db_sync.py` aligned with YAML ↔ SQL column names | VERIFY |

---

## H2 — PHP validation + web surface

| ID | Task | Status |
|----|------|--------|
| H2.1 | `LupopediaHeaderValidator.php` — parity with Python **required** `lupopedia.headers` keys (or documented delta) | **PENDING** |
| H2.2 | `validate_lupopedia_headers.php` / `lupo.php` entry points documented in VALIDATORS_AND_TOOLING | VERIFY |
| H2.3 | **Admin or operator UI** — page or action that runs header validation on a chosen `.md` (or batch) and shows errors | **PENDING** (gap: no dedicated module found under `includes/modules` yet) |

---

## H3 — Import / regenerate / DB

| ID | Task | Status |
|----|------|--------|
| H3.1 | Confirm `install_new_lupopedia.sql` + `database/lupopedia/json/*.json` match `header_db_sync` expectations | VERIFY |
| H3.2 | Document any **one-time** safe migration for existing dev DBs (per project migration doctrine) | PENDING if drift found |
| H3.3 | Python **`import_content.py`**, **`generate_headers_from_db.py`**, **`ensure_imported.py`** + PHP **`import_content.php`**, **`generate_headers_from_db.php`**, **`HeaderDbSync.php`** referenced from version README, consolidation doc, `VALIDATORS_AND_TOOLING` | DONE |

---

## H4 — `*` trees + IDE rules

| ID | Task | Status |
|----|------|--------|
| H4.1 | `rules/root/RULE_FILES_HEADER_REQUIREMENT.md` + header doctrines referenced from AGENTS / onboarding | VERIFY |
| H4.2 | `.cursor/rules` + `.windsurf/rules` include LUPOPEDIA HEADERS file-order and related doctrines; run `propagate_agent_rules.php` after rule changes | VERIFY |
| H4.3 | Channel / filesystem fallback paths (`channels/`, offline doctrine) documented for header-bearing artifacts | VERIFY |
| H4.4 | **Repository literacy (4.0.89 exit)** — Everyone operating this release (including **PHP agent** operators) understands what each major **`*`** tree is for: read **[`ORGANIZATION.md`](../../../ORGANIZATION.md)** (root) **§4** (directory map) and **[`docs/ORGANIZATION.md`](../../ORGANIZATION.md)** (**§2** documentation authority + **§2.2** PHP agent boundaries). Cross-check with **`AGENTS.md`** *Key Directories* / architecture overview. | VERIFY |

---

## H5 — Release-gate testing (mandatory before tag)

Run **at least three** distinct markdown artifacts (e.g. root doctrine, one `docs/doctrine/` file, one `channels/` artifact) through:

1. **Import:** `python scripts/import_content.py <path> [--write-back]` (IDE path) **or** `php scripts/import_content.php <path> [--write-back]` (PHP path) — success; for validators/`--check-db`, **`content_id`** should appear in the file (use **`--write-back`** on either toolchain, or **`generate_headers_from_db`** from DB).
2. **Read-back:** Query `lupo_contents` (+ edges if applicable) — matches file intent.
3. **Regenerate:** `python scripts/generate_headers_from_db.py --file-path <path>` **or** `php scripts/generate_headers_from_db.php --file-path <path>` — YAML sane; no unexpected loss of `outbound_edges` / `lupopedia.history` vs DB policy. **At least one** artifact in the matrix must carry a non-empty `lupopedia.history` so **H7** is exercised (see below).
4. **Validate:** `validate_lupopedia_headers.py` + `validate_lupopedia_headers_universal.py` (and optionally **`validate_lupopedia_headers.php`** for the same files) — no errors (warnings documented).
5. **Optional:** `--check-db` with `content_id` — no unexpected drift warnings.

**Record:** Log commands + outcomes in channel 42 thread or append short subsection to `CHANGELOG.md` under “Release verification”.

| ID | Task | Status |
|----|------|--------|
| H5.1 | Execute matrix above | **DONE** (2026-03-29 — Python path; 3 files; see **`CHANGELOG.md` Release verification**) |
| H5.2 | Document results | **DONE** (same) |

---

## H6 — Consolidation doc

| ID | Task | Status |
|----|------|--------|
| H6.1 | `HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md` updated for **release scope** + §3.2 **`revision_history`** | VERIFY |

---

## H7 — `lupopedia.history` ↔ `lupo_contents.revision_history` (running log)

**Status:** Doctrine **DONE** (H7.1) | Code **verified** on live import (H7.2, 2026-03-29) | **H7.3** exercised via regenerate + DB read (**existing** history events; optional: append new `event_id` later) | **H7.4** **DONE** in **`CHANGELOG.md` Release verification**

**Dual running log (human summary):** See **[README.md](README.md)** — **Dual running log** (subsection) and **Release scope** criterion **10** (`lupopedia.history` ↔ `revision_history`).

**Binding text:** [`rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md) — *Dual running log — file and database*.

| ID | Task | Status |
|----|------|--------|
| H7.1 | **Doctrine** — File YAML `lupopedia.history` and DB column `revision_history` semantics documented (key present vs absent, regenerate behavior) | **DONE** (root doctrine §History) |
| H7.2 | **Code** — `header_db_sync.sync_header_artifact_to_db` writes JSON when key present; omits clear when key absent; `build_yaml_data_from_db` emits history from DB | **DONE** (live DB, doctrine file, 2026-03-29) |
| H7.3 | **Round-trip** — Import → DB `revision_history` → **`generate_headers_from_db.py`** → re-validate; optional append new `event_id` | **DONE** (core path); optional **new** event append still welcome |
| H7.4 | **Record** — **`CHANGELOG.md` Release verification** | **DONE** |

---

## H8 — PHP import/validation (PHP agents, shared hosting, parity with Python)

**Cross-references:** Release criteria for the dual path live in **[`README.md`](README.md)** (**#5**, **#11**, **#12**). Org-wide summary: **[`ORGANIZATION.md`](../../ORGANIZATION.md) §2.1**. **Filesystem policy** for what PHP agents may author on disk: **H9** (IDE agents own code/runtime trees). This section is the **canonical** agent matrix, diagrams, and acceptance checklist (not repeated in README).

### Architecture: agent types and toolchains

| Agent type | Examples | Runs on | Toolchain | Writes `.md`? | Needs import? |
|------------|----------|---------|-----------|---------------|---------------|
| **IDE agents** | Cursor, Windsurf, Kiro | Developer machine (IDE) | **Python** (`import_content.py`, …) | Yes (any allowed repo path, including PHP/runtime) | Yes |
| **PHP agents** | DeepSeek, OpenAI, Grok (LLM modules via HTTP API) | Shared hosting / PHP runtime | **PHP** (`import_content.php`, …) | Yes — **only** under **content-safe** paths and **non-executable** extensions (**H9**) | Yes |

Both agent families produce **the same artifact shape** when authoring **markdown** with **LUPOPEDIA HEADERS** in allowed trees (`docs/`, `channels/`, `rules/` as markdown, etc.). **IDE agents** also maintain **`includes/`**, **`app/`**, **`scripts/*.php`**, and other runtime surfaces — **PHP agents must not** (**H9**). **After import**, **`lupo_contents` (+ metadata/edges/revision_history)** is the database-first snapshot for that artifact.

```text
IDE agents (Python env)          PHP agents (shared hosting)
        |                                |
        | write .md + headers            | write .md + headers
        v                                v
        +-------->  REPOSITORY (.md)  <----+
                        |
                        | import (Python OR PHP — same DB state)
                        v
                 lupo_contents (+ sync tables)
```

### Import toolchains (must match DB semantics)

| Environment | Import | Validate | Regenerate |
|-------------|--------|----------|------------|
| **IDE / dev** (Python available) | `python scripts/import_content.py` | `validate_lupopedia_headers.py` | `generate_headers_from_db.py` |
| **Shared hosting / PHP agents** | `php scripts/import_content.php` | `validate_lupopedia_headers.php` | `generate_headers_from_db.php` |

**Parity rule:** For the same file body + `file_path_from_root`, **both toolchains must yield the same `content_id`** and the same sync behavior for metadata, edges, and `revision_history`.

**File write policy (PHP vs Python):** **`import_content.py`** and **`import_content.php`** both **default to DB-only** (no markdown mutation). Use **`--write-back`** when the deployment allows writing the repo copy (one-time **`content_id`** migration or explicit YAML sync). Legacy slug/PK conflicts: Python import can **`RECONCILE_PK_UPDATE`** — see **`CHANGELOG.md`** Release verification errata.

---

### H8.1 — `scripts/import_content.php`

- [x] Upsert `lupo_contents` + `syncHeaderArtifactToDb` (metadata, edges, `revision_history` policy) — **implemented**
- [x] Deterministic `content_id` matches Python — **implemented** (SHA-256 first 16 hex → signed BIGINT fit; requires **bcmath** or **gmp**)
- [x] **`--write-back`** optional; default **does not** modify source file — **implemented**
- [ ] **VERIFY** on live MySQL/MariaDB from shared-hosting-class PHP

```bash
php scripts/import_content.php path/to/file.md
php scripts/import_content.php path/to/file.md --write-back
php scripts/import_content.php path/to/file.md --dry-run
```

**Note:** Full YAML parsing uses **`yaml_parse()`** (php-yaml). A regex YAML fallback is **not** implemented; document host requirement or track as future enhancement.

---

### H8.2 — `scripts/validate_lupopedia_headers.php`

- [x] Required `lupopedia.headers` keys aligned with `validate_lupopedia_headers.py` — **implemented**
- [ ] **Cross-field** rules (`lupopedia.schema` ↔ `artifact_type` / `artifact_kind`) — full parity with `validate_lupopedia_headers_universal.py` is **partial**; VERIFY / extend as needed
- [x] Optional `--check-db` — **implemented** (with `lupopedia-config.php` loaded)

```bash
php scripts/validate_lupopedia_headers.php path/to/file.md
php scripts/validate_lupopedia_headers.php path/to/file.md --check-db
```

---

### H8.3 — `scripts/generate_headers_from_db.php`

- [x] DB → YAML front matter, preserve body — **implemented**
- [ ] **VERIFY** round-trip vs Python on same row

---

### H8.4 — `includes/classes/HeaderDbSync.php`

Canonical PHP API (mirrors `lib/header_db_sync.py` + helpers):

- `calculateContentId($filePathFromRoot, $body)`
- `parseYamlFrontMatter($content)`
- `syncHeaderArtifactToDb($db, $tablePrefix, $yamlData, $contentId, $now)`
- `buildYamlDataFromDb($db, $tablePrefix, $contentRow)`
- `validateFile($path, $repoRoot, $checkDb)` (used by the CLI validator)

---

### H8.5 — `content_id` consistency (critical)

Compare without touching DB:

```bash
python scripts/import_content.py path/to/file.md --dry-run
php scripts/import_content.php path/to/file.md --dry-run
```

Both must print the **same** `content_id`.

---

### H8.6 — Testing matrix (acceptance)

- [ ] **A:** Artifact written by **IDE agent** → imported with **PHP** → DB matches expectation (compare to Python import on same file if needed)
- [ ] **B:** Artifact written by **PHP agent** → imported with **PHP** → DB correct
- [ ] **C:** PHP **import** → PHP **regenerate** → headers/body consistent (timestamps may differ)
- [ ] **D:** **Cross-toolchain** — Python import then PHP regenerate (or reverse); `content_id` stable; no orphaned sync rows

**2026-03-29 (updated):** **CLOSED** for **deterministic identity** — **`--dry-run`** parity fixed via **`HeaderDbSync`** newline order (CRLF), **`import_content.py`** LF body + **`normPath`**, and **`PDO_DB` `SET NAMES utf8mb4`**. Full PHP import against a DB that still has **legacy** `content_id` rows: use **`python scripts/import_content.py --write-back <path.md>`** ( **`RECONCILE_PK_UPDATE`** ) — see **`CHANGELOG.md` Release verification** (migration note + errata).

---

### H8.7 — Documentation

- [x] This **TODO.md** section (agent architecture + H8)
- [x] **`README.md`** — quick PHP commands + pointers to this section (**deduped** 2026-03-29)
- [x] **`docs/ORGANIZATION.md`** — agent toolchain table + **§2.2** PHP agent content-only policy
- [x] **`ORGANIZATION.md`** (root) — **§4.1** pointer to PHP agent boundaries
- [x] **`CHANGELOG.md`** — PHP toolchain entry (**VERIFY**)
- [x] **`VALIDATORS_AND_TOOLING.md`** — PHP scripts + `--write-back` behavior
- [x] **`PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`** — **H9.3** web hardening patterns (operator applies per vhost)
- [ ] Optional: dedicated faucet / operator **prompt snippet** beyond **`docs/ORGANIZATION.md` §2.2** (nice-to-have)

---

### H8.8 — Optional CI

- [ ] `LUPO_CROSS_VALIDATE_PHP=1` (or similar): run PHP validator from Python job for spot-check parity

---

### H8.9 — Dependencies (PHP path)

- PHP **7.4+** through 8.x (project core); **php-yaml** (`yaml_parse`), **bcmath** or **gmp**, **PDO** MySQL via `lupopedia-config.php` / `DatabaseFactory`
- MySQL 8 / MariaDB 10.5+ (per project doctrine); JSON column for `revision_history`

---

### H8.10 — Notes

- Edge mapping: YAML `type` → `edge_type`, `weight` → `weight_score` (0–100) + `semantic_weight` / `flare_weight`, `reason` → `flare_reason` (see Python `header_db_sync.py`)
- `revision_history` updated only when `lupopedia.history` **key is present**; if absent, existing DB history is **not** cleared

---

## H9 — PHP agent filesystem safety (4.0.89)

**Context:** **PHP agents** (DeepSeek, OpenAI, Grok, …) are LLM-powered **content** authors on the server. **IDE agents** (Cursor, Windsurf, Kiro, …) are **developer** tools with **full repository write access** — they are **not** limited by **`AgentFileWriter`**. This gate applies only to **PHP agent** (and similarly guarded) **file write** paths.

**Risk:** PHP agents could be prompt-injected to write:

- PHP in web-adjacent trees (remote code execution)
- HTML/JS-capable files (XSS)
- Dangerous payloads inside “safe” extensions (**`<?php`**, **`<script>`**, …)

### Policy — who may write what

| Write target | IDE agents | PHP agents |
|--------------|------------|------------|
| Code directories (`includes/`, `app/`, `scripts/`, `bin/`, `api/`, …) | **Allowed** | **Forbidden** |
| Root PHP entry points | **Allowed** | **Forbidden** |
| **`.php`**, **`.py`**, **`.sh`**, **`.js`**, **`.html`**, **`.htm`**, … | **Allowed** | **Forbidden** |
| **`rules/`**, **`docs/`**, **`channels/`**, **`content/`** | **Allowed** | **Allowed** |
| **`.md`**, **`.txt`**, **`.yaml`**, **`.yml`**, **`.json`**, **`.csv`**, **`.xml`** (under allowed dirs for PHP agents) | **Allowed** | **Allowed** |

### H9.1 — Detailed policy table (PHP agents)

| Category | Forbidden | Allowed | Rationale |
|----------|-----------|---------|-----------|
| **Directories (write)** | **`includes/`**, **`app/`**, **`bin/`**, **`api/`**, **`routes/`**, **`views/`**, **`admin/`**, **`install/`**, **web root** / entrypoint PHP areas | **`rules/`**, **`docs/`**, **`channels/`**, **`content/`** | No runtime or tooling trees |
| **Extensions (write)** | **`.php`**, **`.phtml`**, **`.phar`**, **`.sh`**, **`.bash`**, **`.pl`**, **`.cgi`**, **`.py`**, **`.rb`**, **`.js`**, **`.vbs`**, **`.ps1`**, **`.htm`**, **`.html`** | **`.md`**, **`.txt`**, **`.yaml`**, **`.yml`**, **`.json`**, **`.csv`**, **`.xml`** | No executables or active HTML/JS pages |
| **File content** | Patterns such as **`<?php`**, **`<script>`**, **`javascript:`**, event handlers, … (in **CONTEXT_AGENT**) | Plain / structured text when scan passes | Content validation at write time |

### Implementation

| ID | Task | Status |
|----|------|--------|
| H9.1 | Policy documented (**this section**, **`docs/ORGANIZATION.md` §2.2**, root **`ORGANIZATION.md` §4.1**, **`README.md` #12**, **`CHANGELOG.md`**) | **DONE** (verify on tag review) |
| H9.2 | **`AgentFileWriter`** — enforces **PHP agent** path/extension rules; **`CONTEXT_AGENT`** vs **`CONTEXT_OPERATOR`**; wired **`import_content.php`** / **`generate_headers_from_db.php`** for guarded disk writes | **DONE** (VERIFY edge cases + host paths) |
| H9.3 | Web server hardening docs | **DONE** — **[`PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`](PHP_AGENT_FILESYSTEM_DEPLOYMENT.md)** |
| H9.4 | Audit log **`logs/php_agent_filesystem_writes.log`**; optional antivirus / **`ContentSafetyService`** | **PARTIAL** (file log); extended service **PENDING** |

### Success criteria

- **PHP agents** cannot write **code** directories or **forbidden** extensions (when using **`AgentFileWriter`**).
- **PHP agents** cannot write **HTML/JS** file types (XSS risk).
- **IDE agents** remain **unrestricted** by **`AgentFileWriter`** (full repo development access).
- **Agent** context applies content pattern scan; **operator** CLI keeps path + extension only (see §2.2).

### Deployment

All **automated PHP agent** file outputs must use **`AgentFileWriter::writeFile(..., AgentFileWriter::CONTEXT_AGENT, $actorId)`** before production. Human operators may use **`CONTEXT_OPERATOR`** for **`--write-back`** / regenerate where documented.

### Layer summary

| Layer | Protection | Status |
|-------|------------|--------|
| Policy | IDE vs PHP distinction + tables | **DONE** |
| Code | **`AgentFileWriter`** (PHP agents / guarded writes) | **DONE** (verify) |
| Filesystem | Server hardening options | **DOC** — **`PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`** |
| Audit | Log on **`AgentFileWriter`** write | **PARTIAL**; extended **PENDING** |

---

## 4.0.89 release sign-off (2026-03-29)

| Gate | Status | Notes |
|------|--------|-------|
| **H1** Python | **VERIFY** | Baseline scripts run; CI/manual spot-check as needed |
| **H2** PHP + UI | **PARTIAL** | **H2.1**, **H2.3** still **PENDING** / gap |
| **H3** Schema | **VERIFY** | No drift found in this pass; **H3.2** if issues arise |
| **H4** Org + rules | **VERIFY** | **H4.4** = human/process sign-off |
| **H5** Release gate | **PASS** | Python matrix — **`CHANGELOG.md`** |
| **H6** `HEADER_DB_FIRST` | **VERIFY** | Doc complete + §6.1 PHP pointers |
| **H7** Running log | **PASS** | DB + regenerate recorded in **`CHANGELOG.md`** |
| **H8** PHP parity | **PASS** (dry-run + driver charset) | Legacy DB rows: possible slug/`content_id` mismatch on INSERT — see **`CHANGELOG.md`** |
| **H9** PHP agent FS | **PASS** (policy+code) | **H9.4** extended audit optional |

**All gates:** **PASS** for documented 4.0.89 verification — remaining gaps (**H2.1**, **H2.3**, **H4.4** process, **H4.2**) are **non-blocking** for this tag per **`README.md`** / **`TODO.md`**.

---

**WOLFIE (actor_id 1)** — 4.0.89 TODO = headers path to release; all else → 4.0.90.
