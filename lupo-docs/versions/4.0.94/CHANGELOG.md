---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: changelog
  when_updated: "20260406150859"
  file_path_from_root: "lupo-docs/versions/4.0.94/CHANGELOG.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/CHANGELOG.md"
  last_modified_utc: "20260406150859"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-changelog"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "changelog"
  artifact_kind: "version"
  purpose: "Record of significant changes for Lupopedia 4.0.94"
  tags: ["changelog", "version", "4.0.94", "cursor"]
lupopedia.footer:
  last_verified: "20260406150859"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/CHANGELOG.md — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/CHANGELOG.md

# Changelog - Lupopedia 4.0.94

## [4.0.94] - 2026-04-06

### Packaging gate (pre-Softaculous)

- **`AuthSessionManager`**: When `LUPOPEDIA_DEBUG` is true, `__construct` logs a deprecation reminder to migrate session authority to `App\Auth\Session` (mapping helpers remain until 4.1.0).
- **`ToonSchemaCache`**: When `LUPOPEDIA_DEBUG` is true, `__construct` logs deprecation in favor of canonical JSON under `lupo-database/lupopedia/json/` (removal targeted 4.1.0).
- **`main_layout.php`**: Bulk layout CSS moved to `lupo-includes/css/main-layout.css`; decorative border tile `background` rules stay in a small inline `<style>` block because URLs require `LUPOPEDIA_PUBLIC_PATH`.
- **`main_layout.php`**: Nav/collections JavaScript moved to `lupo-includes/js/main-layout.js` and `main-layout-collections.js`; inline script sets `window.LUPO_MAIN_LAYOUT` (public path + collection context) for API `fetch` targets.
- **`lupo-rules/root/`**: Front matter migrated from flat `actor_id` / `actor_name` to structured `author` block (`type`, `id`, `name`) where those keys appeared at header indent; body examples unchanged.

### Added

#### Constitutional Rules

- **PK Naming Rule (Rule 93.PK_NAMING)** – Added to `00_root_constitutional_system_requirements.md`:
  - Primary keys MUST be named `<singular_table_name>_id` (e.g., `actor_id`, not `id`)
  - Reference keys MUST use the exact same column name as the primary key they reference
  - Applies to both database tables AND file-based identifiers (PRDs, implementations)

- **Absolute-Root Pathing (Rule 93.PATH_PURITY)** – Added to constitutional requirements:
  - All documentation links must start with `/` and never use `../`, `~/`, or relative paths
  - LUPOPEDIA HEADERS `web_path` must always include the `/lupopedia/` subdirectory prefix

- **PHP Tiered Compatibility (Option 4)** – Updated section 4 with tiered approach:
  - Production: PHP 7.4+ 64-bit required for Y2038 safety
  - Legacy: PHP 5.6+ allowed with installer flags (`LUPOPEDIA_LEGACY_INSTALL`, `lupo-install-legacy-php.flag`)
  - 32-bit override: `LUPOPEDIA_ALLOW_32BIT=1` or `lupo-install-allow-32bit.flag` (not recommended)
  - Honest Y2038 warning for 32-bit PHP

#### Documentation

- **PRD 16 (LUPOPEDIA HEADERS)** – Created and approved:
  - Author/verifier distinction in headers
  - `author` structured block (type, id, name) – deprecated flat `actor_id`/`actor_name`
  - Conditional field requirements based on artifact type
  - Header applicability scope

- **PRD 26 (Five-Layer Documentation Architecture)** – Created and approved:
  - Tier 1 (Authored Documentation) vs Tier 2 (Runtime Content)
  - Channel-based thread coordination
  - Implementation mirroring for PRD-scoped work
  - Decision/questions/answers/comments folder structure

- **PRD 30 (PRD Development Guide)** – Created, rejected by COUNTERMEASURE:
  - Needs rewrite as writing guide (not metadata spec)
  - Will be replaced with PRD 30 (Writing Guide) in future iteration

- **PRD 31 (Implementation Folder Guidelines)** – Created, rejected by COUNTERMEASURE:
  - Determined that implementation mirroring belongs in PRD 26 (already covered)

- **COUNTERMEASURE Agent Integration** – Added critical feedback loop:
  - Agent 111 (COUNTERMEASURE) provides adversarial review for PRDs
  - Identified scope creep and structural issues in PRDs 26, 30, 31
  - Established pattern for PRD review before approval

#### Code Changes

- **Session Class (`app/auth/Session.php`)** – Refactored to constitutional standards:
  - Added `createEmbedSession()` for Eye cookie without PHP session rotation
  - Added `getDecodedMetadata()` and `mergeSessionMetadata()` for transient flags
  - Uses `$UNTRUSTED` fingerprinting for IP/UA hashing
  - Documented session token exception (cryptographic, not IdGenerator)

- **AuthSessionManager** – Deprecated, session authority moved to `App\Auth\Session`:
  - `createSession()` now uses `App\Auth\Session::create()`
  - `getActiveActorId()` reads from `lupo_sessions` table
  - `updateActiveActor()` updates only DB, not `$_SESSION`
  - `@deprecated` annotation with migration path

- **AuthService** – Refactored to use `lupo_sessions.metadata`:
  - Transient flags (password change, pending agent, login redirect) stored in session metadata
  - No `$_SESSION` authority
  - Uses `App\Auth\Session::mergeSessionMetadata()`

- **login.php** – Refactored to constitutional standards:
  - Added LUPOPEDIA HEADER
  - `$UNTRUSTED` boundary for `$_SERVER`, `$_GET`, `$_POST`
  - No `session_start()` – relies on bootstrap
  - Reads password change flag from `lupo_sessions.metadata`

- **select_agent.php** – Refactored to constitutional standards:
  - Added LUPOPEDIA HEADER
  - `$UNTRUSTED` boundary for `$_SERVER`, `$_POST`
  - Reads pending state from `lupo_sessions.metadata` (not `$_SESSION`)
  - `lupo_t()` for all UI strings

- **admin.php** – Refactored to constitutional standards:
  - `$UNTRUSTED` boundary for `$_GET`, `$_SERVER`
  - Uses `DatabaseFactory::getConnection()` (no `$GLOBALS['mydatabase']`)
  - Reads password change flag from `lupo_sessions.metadata`

- **install.php & install_wizard_classes.php** – Refactored for portability:
  - Replaced `SHOW TABLES LIKE` with `information_schema.tables` queries
  - `$UNTRUSTED` boundary for all request input
  - `getDbCredentials()` and `validateCsrf()` accept snapshot parameters
  - Complete screen uses `timestamp_ymdhis::now()` (packed UTC)

- **UrlResolver** – Added path anchoring and `$UNTRUSTED` compliance:
  - `$UNTRUSTED` boundary for `$_SERVER['REQUEST_URI']`
  - `pathIsUnderRepo()` realpath validation
  - Cache uses `expires_ymdhis` (no Unix epoch fallback)

- **ToonSchemaCache** – Deprecated, now reads from JSON schema files:
  - Reads from `lupo-database/lupopedia/json/<table_name>.json` (canonical)
  - Removed YAML dependency (`yaml_parse()`)
  - `@deprecated` annotation with migration path

- **DYNAPI_DOCTRINE.md** – Deprecated:
  - `status: deprecated`
  - `deprecated_by: lupo-docs/doctrine/LUPO_LAYERS_DOCTRINE.md`
  - Heritage content retained for context

- **LUPO_LAYERS_DOCTRINE.md** – Created as active doctrine:
  - `status: active`
  - Documents `lupo-layers.js` (LupoLayer) as canonical UI layer controller
  - Supersedes DYNAPI_DOCTRINE.md

- **auth-helpers.php** – Refactored for `$UNTRUSTED` compliance:
  - `require_login()` uses `$UNTRUSTED['server']['REQUEST_URI']`
  - Stores redirect in `lupo_sessions.metadata` (not `$_SESSION`)
  - No `session_start()` in fallback path

- **auth-ui-helpers.php** – Refactored to accept `$user` parameter:
  - No internal auth service reads
  - `$UNTRUSTED` boundary for `$_SERVER['REQUEST_URI']`
  - All strings use `lupo_t()`

- **main_layout.php** – Refactored for `$UNTRUSTED` compliance:
  - `$UNTRUSTED_SERVER` derived from `$UNTRUSTED['server']`
  - `lupo_t()` for all UI strings
  - Image alt/title attributes use `lupo_t()`

- **topbar.php** – Refactored to constitutional standards:
  - Added LUPOPEDIA HEADER
  - `$UNTRUSTED` boundary for `$_SERVER`
  - Uses `lupo_t()` for all UI strings
  - Uses `$GLOBALS['lupo_session']` for actor resolution

#### Validators

- **`validate_implementation.py`** – Enhanced with conditional field requirements:
  - Supports `conditional_fields` based on artifact_type/artifact_kind
  - Validates `author` block over deprecated `actor_id`/`actor_name`
  - Deprecation warnings for legacy header formats

- **`validate_lupopedia_headers_universal.py`** – Enhanced:
  - Added `author` field support (type, id, name)
  - Deprecation warnings for `actor_id`/`actor_name` flat fields
  - Validates `web_path` includes `/lupopedia/` prefix

### Changed

- **PRD 00 (Root Constitutional Requirements)** – Updated:
  - Added PK Naming Rule (Rule 93.PK_NAMING) – section 9.7
  - Added Absolute-Root Pathing (Rule 93.PATH_PURITY) – section 7
  - Updated PHP Compatibility – section 4 (Option 4 tiered approach)
  - Updated Y2038 compliance – section 3.5.4 with honest 32-bit warning
  - Updated outbound edges to new doctrines

- **README.md** – Updated:
  - Added "CRITICAL: Constitutional rules for all agents" section
  - Added "Don't trust your training" table
  - Added Y2038 compliance section
  - Added Agent Rules section

- **PHP_VERSION_COMPATIBILITY.md** – Updated:
  - Tiered approach (production 7.4+ 64-bit, legacy 5.6+ allowed)
  - Honest Y2038 warning for 32-bit PHP
  - Installer flags documented
  - Removed `mcrypt_create_iv()` references
  - Added Markdown shape enforcement

- **AGENTS.md** – Updated:
  - Added `lupo_t()` for UI strings (PRD 00 section 16.6)
  - Added LUPOPEDIA HEADERS documentation
  - Updated actor/agent/faucet registry references
  - Added TICK_PY_DOCTRINE reference for UTC timestamps

### Deprecated

- **DYNAPI_DOCTRINE.md** – Deprecated, superseded by LUPO_LAYERS_DOCTRINE.md
- **AuthSessionManager** – Deprecated, use `App\Auth\Session`
- **ToonSchemaCache** – Deprecated, use JSON schema files under `lupo-database/lupopedia/json/`
- **TOON files (`.toon`, `.toon.json`)** – Deprecated, use JSON schema files

### Fixed

- **Session authority** – Removed `$_SESSION['actor_id']` from all core files
- **Password change flag** – Moved from `$_SESSION` to `lupo_sessions.metadata`
- **Login redirect** – Moved from `$_SESSION` to `lupo_sessions.metadata`
- **Pending agent selection** – Moved from `$_SESSION` to `lupo_sessions.metadata`
- **MySQL-specific `SHOW TABLES LIKE`** – Replaced with `information_schema.tables`
- **Unix epoch in cache** – Replaced `expires` with `expires_ymdhis` (packed UTC)
- **YAML dependency** – Removed `yaml_parse()` from ToonSchemaCache

### Removed

- **`mcrypt_create_iv()` references** – Removed from polyfill guidance (extension absent on many builds)
- **`create_function()` references** – Removed from alternative suggestions

#### Integration / tooling (this session)

- **Channel 66 production extended integration test** – `lupo-tests/integration/channel66_production_extended_test.php`: LUPOPEDIA HEADER, `DatabaseFactory::getConnection()`, ingester fixture paths under `lupo-channels/66/threads/1001/`, merged monitoring validation, removed stub/shell paths; **Channel66ProductionIngester::discoverChannelFiles** – `thread_id === null` processes all threads (branch fix).

#### Version documentation and packaging boundary (UTC `20260406043326`)

- **`VERSION_SUMMARY.md`** — rollup of completed 4.0.94 work for packaging handoff.
- **`lupo-docs/versions/4.0.95/`** — planning scaffold created; open and deferred tasks moved to **`4.0.95/TODO.md`** (P3-005+, P4-*, D-001–D-005, Phase 7 test matrix continuation).
- **Phase 7 (4.0.94 `PLAN.md`)** — reframed as **Packaging and testing**: Softaculous packaging test on Linux next; regression / legacy PHP checks remain open in 4.0.94 plan until executed.

---

## [4.0.93] - 2026-03-XX

Frozen baseline: see **`lupo-docs/versions/4.0.93/README.md`** and **`lupo-docs/versions/4.0.93/CHANGELOG.md`** (prior release notes).

---

## Detailed session log (chronological)

# CHANGELOG.md - Lupopedia Version 4.0.94

# [2026-04-05] PRD 00 §17.9 — ROSE sandbox (PRD 36) + dialog-only write surface (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — **§17.9** clarified: **ROSE** exception for **sandboxed** multi-persona **dialog** per **PRD 36**; **write surface** = **`lupo_dialog_messages`** (+ **PRD 36**-scoped dialog metadata), **no** content / **lupo_metadata** (non-dialog) / config / actors / channels / auth updates from ROSE pipeline; compliance test split **IDE vs ROSE**; **`00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md`** + **`00_constitution_shorthand.pseudo.md`**; UTC **`20260405224232`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Aligns constitution with **ROSE**’s intended **dialog-only** role vs **IDE** impersonation ban.
- **WHY:** Operator clarification — synthetic choir ≠ tooling impersonation; narrow DB authority.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 00 §17.9 — RULE 93.NO_PROMPT_INJECTION + shared IDE prompt (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — **§17.9** (impersonation, instruction override, secrets, automation boundaries, service vs dialogue personas; optional filters note); **edge** to **`ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md`**; **`lupo-agents/_shared/ide_facet_base_system_prompt.txt`** (prompt-injection block); **`00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md`** + **`00_constitution_shorthand.pseudo.md`**; **§10.2** row **§17.7–§17.9**; UTC **`20260405223937`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Constitutional **prompt injection** rules for IDE/LLM surfaces; **does not** revive banned test-persona nicknames (see adversarial identity doctrine).
- **WHY:** Red-team precedent is real; product agents touch channels/DB/edges — untrusted text must not become authority.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 00 §17.8 — RULE 93.UNTRUSTED_INPUT (`$UNTRUSTED` discipline) (LILITH audit) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — **§17.8** (explicit untrusted boundary, **`$UNTRUSTED`** pattern per legacy **`image.php` / `livehelp_js.php`**, validation + no mass assignment, **`$_REQUEST`** warning, superglobal clearing **not** globally mandated); **§10.2** tooling row notes **§17.7–§17.8**; **`00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md`** + **`00_constitution_shorthand.pseudo.md`**; UTC **`20260405223144`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Codifies Crafty-era input discipline as constitutional **RULE 93.UNTRUSTED_INPUT** without requiring unsafe blanket **`$_COOKIE`** wipes at bootstrap.
- **WHY:** LILITH audit — industry complacency vs explicit validation boundary.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 00 §17.7 — eval, unserialize, session authority, uploads + digest rows (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — new **§17.7** (PHP execution hygiene, JS cross-ref **§16**, **`unserialize`** ban on untrusted data, **`lupo_sessions` / `App\Auth\Session`** vs **`$_SESSION`**, **PRD 33 §5.1** decode/re-encode uploads); **`00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md`** + **`00_constitution_shorthand.pseudo.md`** table rows; UTC **`20260405222700`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Makes constitutional security explicit where common tutorials default to **`eval`**, **`unserialize`**, session superglobals, and raw uploads.
- **WHY:** Operator recommendations — align law, shorthand, and overrides with **RULE 93.SECURITY** and **PRD 33** gate language.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 17 — pseudocode reasoning discipline + optional validator (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/17_decisions_format.md`** (new subsection: zero-guessing, Purpose 1 vs Purpose 2 scope, anchors, option forks); **`lupo-scripts/validate_pseudocode_discipline.py`** (optional warnings for Purpose 2 files under `decisions/pseudocode/`); **`AGENTS.md`** (IDE summary + edge); UTC **`20260405222024`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Binds IDE/LLM behavior for pseudocode as **thinking space** (Purpose 2), not predictive completion; documents exemptions for constitution digests and `00_*` cross-cutting files.
- **WHY:** LILITH-approved alignment — deliberate design artifacts vs code-stub completion.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] `00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md` — IDE overrides router (LILITH naming) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); new **`lupo-docs/decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md`** (Purpose 1 router + overrides table); **`THREAD_INDEX.md`** (both trees); **`00_constitution_shorthand`**, **`lupopedia_quickstart`**, **`00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md`** (expanded digest), **PRD 00** edges/intro; UTC **`20260405220110`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** `00_` sort prefix + **`.pseudo.md`** per PRD 17; searchable name for “non-negotiable / overwrite training” intent.
- **WHY:** LILITH audit — filename clarity + convention alignment.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 00 §17.3 + digests — `SELECT *` allowed; positional `INSERT` forbidden (LILITH) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** (**§17.3** — explicit **`INSERT`** columns; positional **`INSERT`** hazard; **`SELECT *`** reads not forbidden); **`lupo-docs/decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md`** (reorder: **§1 INSERT**; drop **`SELECT *`** as dodo item; summary note); **`00_constitution_shorthand.pseudo.md`** (database **INSERT**/**SELECT** rows; security blurb); **`THREAD_INDEX.md`**; UTC **`20260405215402`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Removes unnecessary friction on **`SELECT *`**; keeps **hard** rule on **`INSERT`** column lists.
- **WHY:** LILITH correction — villain is **`INSERT`**, not **`SELECT`**.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] `00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md` — INSERT vs SELECT * distinction (LILITH) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md`** — reorder (**`INSERT`** without column list = **critical**; **`SELECT *`** = wasteful/low); expanded §2; summary danger column; golden rule; **`00_constitution_shorthand.pseudo.md`** security row (**INSERT** vs **SELECT**); UTC **`20260405214944`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Stops conflating positional **`INSERT`** (silent corruption) with **`SELECT *`** (bandwidth/implicit shape).
- **WHY:** LILITH correction — different failure modes.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 00 §3.5.4 Y2038 + `00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md` digest + shorthand/quickstart links (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — new **§3.5.4** (Y2038 compliance, forbidden/required patterns, industry context); **§19** checklist item + normative link to dodo digest; **`lupo-docs/decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md`** (new) + **`THREAD_INDEX.md`**; **`00_constitution_shorthand.pseudo.md`** (timestamp-first table + edge); **`lupopedia_quickstart.pseudo.md`** (link row); UTC **`20260405214736`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Documents Y2038 stance next to packed-clock doctrine; gives IDE agents a single “wrong defaults” correction file without changing application code.
- **WHY:** LILITH directive — reduce repeated AI assumptions (epoch, FKs, ORM, build chains).

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 00 — timestamp reinforcement (§3.5.1–3.5.3, §19 IDE directive) + README digest (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — **§3.5.1** explicit “`BIGINT` ≠ epoch”; **§3.5.2** storage UTC vs display timezone; **§3.5.3** **`timestamp_ymdhis`** canonical utility (with **`gmdate('YmdHis')`** allowed as equivalent “now”); new **§19** binding IDE/LLM checklist; **`README.md`** (timestamp subsection under mandatory reading); **`00_constitution_shorthand.pseudo.md`** pointer; UTC **`20260405213749`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Removes ambiguity that led agents to propose Unix epoch in **`BIGINT`** clock columns; restates display vs persistence without inventing schema columns.
- **WHY:** LILITH directive — constitutional clarity for external AI.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] `timestamp_ymdhis` — UTC DateTime arithmetic + PRD 00 Y2038 / 64-bit PHP note (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-includes/classes/TimestampYmdhis.php`** (`addSeconds` / `diffInSeconds` / `fromHuman` / `convert_iso8601_to_bigint` — **`DateTime` UTC** instead of **`gmmktime`/`strtotime`** bridge; **`diffInSeconds`** sign preserved via **`$db->diff($da)`**); **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** (**§3.5** — explicit **Y2038**: packed storage ≠ Unix epoch; **64-bit PHP** required); **`00_constitution_shorthand.pseudo.md`** digest row; UTC **`20260405212338`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Makes the implementation match the story “we are not storing Unix time,” and documents why **2038** does not apply to the **BIGINT** encoding while **32-bit PHP** remains out of scope.
- **WHY:** Operator frustration / audit clarity — agents conflated packed clocks with epoch and assumed Y2038 applied to the schema.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 00 §3.5 — packed decimal `BIGINT` clocks; forbid SQL date/time functions (LILITH / Wolfie clarification) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** (expanded **§3.5** — storage semantics, forbidden SQL patterns, PHP/`timestamp_ymdhis` pattern, bound parameters); **§3.6** bullet cross-ref; **`lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/00_constitution_shorthand.pseudo.md`** (digest row + one-liner); UTC **`20260405212034`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Documents that timestamps are **packed decimal `BIGINT` UTC** (`YYYYMMDDHHIISS`), **lexically sortable**, **no database date functions** — all temporal logic in **PHP**; schema does **not** store Unix epoch as the canonical clock.
- **WHY:** Close the “Unix epoch / SQL `NOW()`” confusion for external agents; align constitution text with long-standing Crafty/Lupopedia practice.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] External AI bundle — 8 PRD constitution shorthands + lupopedia_quickstart (LILITH Priority 1–3) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/`** — new **`*_constitution.pseudo.md`** for **PRD 05, 15, 16, 26, 31, 28, 33** plus **`lupopedia_quickstart.pseudo.md`**; refreshed **`THREAD_INDEX.md`**; **`README.md`** (00 mirror); **`lupo-docs/prd/17_decisions_format.md`** (shipped bundle table + edge to quickstart); **`implementations/README.md`** cross-cutting row; UTC **`20260405211127`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Digest-only files (Purpose 1); canonical text remains each **`lupo-docs/prd/*.md`**. Injected: department act-as, **`to_actor_id`** routing, Tier 1/2 split, **`prd_file_stem`**, API dual routing, PRD 33 gate / `.htaccess` optional. **Priority 4** (PRD 36/37) listed as optional in quickstart only.
- **WHY:** LILITH directive — top 8 identity/architecture PRDs for “send to new AI.”

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 17 + PRD 00 mirror — pseudocode dual purpose + constitution shorthand (LILITH audit) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/17_decisions_format.md`** (Pseudocode Directory: Purpose 1 constitution shorthand vs Purpose 2 design notes; naming table; **`THREAD_INDEX`** example; “why two purposes”); new **`lupo-docs/implementations/00_root_constitutional_system_requirements/`** (**`README.md`**, **`decisions/THREAD_INDEX.md`**, **`decisions/pseudocode/00_constitution_shorthand.pseudo.md`**, **`decisions/pseudocode/THREAD_INDEX.md`**); **`lupo-docs/implementations/README.md`** (cross-cutting + index rows); UTC **`20260405210708`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Shorthand digest for external AI (**not** a replacement for **PRD 00**); repo-aligned tables (PDO_DB, soft delete, §18 indexing, optional `.htaccess`).
- **WHY:** LILITH-approved split: same **`decisions/pseudocode/`**, distinct file patterns.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] Implementations — merge `25_departments_systems` into `25_departments_system`; PRD 31 exact stem rule (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); merged **`lupo-docs/implementations/25_departments_systems/`** into **`lupo-docs/implementations/25_departments_system/`** (canonical PRD **`25_departments_system.md`**); updated cross-links (**PRD 25**, **30**, root **README**, **IMPLEMENTATION_QUESTIONS_GUIDE**, **create_implementation_question.py**, templates, **validation_report.json**, **26** edges/spec); **PRD 31** + **implementations/README.md** — non-negotiable **character-for-character** match to PRD basename; **PRD 30** example path → existing question file; removed duplicate **PRD 25** outbound edge; UTC **`20260405205804`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** One implementation folder per PRD stem; typo **`systems`** eliminated.
- **WHY:** Duplicate folder names broke **PRD 31** / **§5.8** mirroring.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] Constitutional PRD — no SEO assumption, optional `.htaccess`, dual API routing (LILITH audit follow-up) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** (**§2** subdirectory routing with/without `mod_rewrite`; **§9.5** `.htaccess` optional; new **§18** `RULE 93.NO_SEARCH_INDEX_ASSUMPTION`; **§17.5** cross-ref when `.htaccess` absent); **`lupo-docs/prd/28_semantic_monitoring_widget.md`** (API Endpoints — clean URL vs query-parameter fallbacks); **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** (**§5.1** table row; **§14.1** / **§14.2** / **§14.4 Q2** aligned with fallbacks; new **§14.6** clarification; footer `next_action`); UTC **`20260405205506`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Documents that Lupopedia does **not** assume search indexing or SEO; **`robots.txt` / noindex** are **SHOULD**; installer must **not** fail solely for missing `.htaccess`; APIs must accept query-param (and optional path) forms.
- **WHY:** LILITH audit — distinguish Lupopedia from “normal public web” projects; shared-hosting rewrite limits.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] Header validators + DB sync — GEMINI/LILITH audit (inject legacy actor, --check-links, history append, ext.*, tick --copy) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-scripts/lib/header_validation.py`** (`inject_legacy_actor_from_author`, called from **`validate_header`**); **`validate_lupopedia_headers_universal.py`** (`--check-links`, **`when_updated` ≥ `last_verified`**, inject before author checks); **`lib/header_db_sync.py`** (**`ext.`** metadata writes, legacy **`block.`** read-back, **`append_history`** on **`sync_header_artifact_to_db`**, transaction note in docstring); **`import_content.py`** (`--append-history`); **`lupo-bin/tick.py`** (`--copy` via optional **`pyperclip`**); **`prd16_headers_lifecycle.pseudo.md`** (sections 1.4, 2.1, 3.2, 4.2, 5.2, diagram, cheat sheet); UTC **`20260405204851`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Auto-fill **`actor_id`/`actor_name`** from **`author`** for import/universal validation; strict filesystem check for edge **`to:`** paths; footer/header timestamp ordering; revision_history **overwrite / preserve / append**; custom blocks stored as **`ext.lupopedia.*`** (read **`block.`** too); **`import_content`** already transactional — documented.
- **WHY:** LILITH audit accepted GEMINI suggestions (reduce mirroring debt, broken links, history ambiguity, namespacing, clipboard UX).

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 16/17 — LUPOPEDIA HEADERS required on decisions/pseudocode/*.pseudo.* (external AI handoff) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/16_lupopedia_headers.md`** (pseudocode row in applicability table; removed optional exception; validator scope + handoff note); **`lupo-docs/prd/17_decisions_format.md`** (rules, validation, example `.pseudo.php`, main validation §9); **`LUPOPEDIA_HEADERS_FORMAT.md`** (Overview); **`AGENTS.md`**; **`sync_header_artifact_to_db.pseudo.php`** (embedded YAML header block); **`prd16_headers_lifecycle.pseudo.md`** + **`pseudocode/THREAD_INDEX.md`**; UTC **`20260405204205`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Pseudocode files **must** carry **`lupopedia.headers`** with **`file_path_from_root`** (Markdown front matter; PHP `/* --- ... --- */` after `<?php`) so external agents can locate files; PRD 17 no longer treats pseudocode headers as optional.
- **WHY:** User handoff to external AI failed without path identity.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 16 pseudocode — LUPOPEDIA HEADERS on prd16_headers_lifecycle.pseudo.md (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/implementations/16_lupopedia_headers/decisions/pseudocode/prd16_headers_lifecycle.pseudo.md`** (full front matter + edges + footer); **`decisions/pseudocode/THREAD_INDEX.md`** (index note); UTC **`20260405203933`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** PRD 17 allows omitting headers on **`*.pseudo.*`**; this file now **includes** headers for traceability and so §1.1 matches a live example; §0 explains optional vs chosen.
- **WHY:** Operator question — “why missing”; resolve contradiction with “line 1 must be ---”.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 16 — Header applicability scope (not every repo file) + PRD 17 carve-out cross-link (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/16_lupopedia_headers.md`** (new **Header applicability and scope**: required extensions vs optional vs not applicable; PRD 17 pseudocode exception; overview + constitutional bullet fixes); **`lupo-docs/prd/17_decisions_format.md`** (pseudocode “no headers” row tied explicitly to PRD 16); **`lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md`** (Overview alignment); **`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`** (enforcement rationale wording); **`AGENTS.md`** (LUPOPEDIA HEADERS bullet scoped to PRD 16); **`implementations/16_lupopedia_headers/.../prd16_headers_lifecycle.pseudo.md`** (§0 scope); UTC **`20260405203735`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Replaces over-broad “every file in the repository” with **authored source/docs** list; excludes binaries, generated exports (TOON, CSV, minified), vendor/lockfiles; validators’ **intent** documented.
- **WHY:** LILITH correction — headers track **our** traceability, not every byte in the tree.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 16 implementation — pseudocode for headers lifecycle (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/implementations/16_lupopedia_headers/decisions/`** — `THREAD_INDEX.md`; **`decisions/pseudocode/`** — `THREAD_INDEX.md`, `prd16_headers_lifecycle.pseudo.md`, `sync_header_artifact_to_db.pseudo.php`; UTC **`20260405202839`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Design notes for authoring LUPOPEDIA HEADERS (blocks, timestamps, author vs legacy `actor_id`), verification commands (`validate_lupopedia_headers_universal.py`, `--check-db`), outbound edges → **`lupo_edges`**, **`lupopedia.history`** → **`revision_history`**, footer → metadata **`ftr.*`**, import + **`sync_header_artifact_to_db`** flow diagram.
- **WHY:** Operator-facing bridge from PRD 16 to actual scripts (`import_content.py`, `header_db_sync.py`).

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] PRD 17 — `decisions/pseudocode/` directory spec (LILITH audit approved) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/17_decisions_format.md`** (new Pseudocode Directory section, canonical folder tree + version `decisions/` tree, THREAD_INDEX rule, validation item 9, PRD 31 cross-ref edge, doc version **1.1**); UTC **`20260405202458`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Documents optional **`decisions/pseudocode/`** for design artifacts between decisions and implementation: naming (`.pseudo.php` / `.pseudo.md` / `.pseudo.txt`), `THREAD_INDEX.md`, limited rules (no production load, no DDL), optional headers, suggested edge types, minimal validator guidance; explicit carve-out from full PRD 31 implementation-folder requirements.
- **WHY:** LILITH audit verdict — bridge decision and code without PRD 31 weight.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] Help content organization — Channel key structure + PRD updates + installation integration (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/30_channel_usage_patterns.md`** (added help_documentation channel); **`lupo-docs/prd/16_lupopedia_headers.md`** (updated file_path_from_root docs); **`lupo-content/0/help_documentation/`** (new structure with 5 guides, 8 questions, 8 answers, 34 edges); **`lupo-database/lupopedia/mysql/seed/seed_online_help_and_content.sql`** (updated with channel_key paths); **`lupo-scripts/build_consolidated_seed_4_1_0.py`** (added help seed); **`install/seed_lupopedia_4_1_0.sql`** (generated consolidated seed); version folder **`lupo-docs/versions/4.0.94/`** — `decisions/20260405172914_DECISION_APPROVED_help_content_organization_channel_key_structure.md`, `questions/20260405172914_QUESTION_what_is_correct_help_content_structure.md`, `answers/20260405172914_ANSWER_channel_key_based_organization.md`, `comments/20260405172914_COMMENT_cursor_session_end_help_content_organization.md`, `CHANGELOG` (this entry), `PLAN`, `TODO`, `edges`, `THREAD_INDEX` files; UTC **`20260405172914`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Replaced numeric `lupo-content/0/0/` structure with semantic `lupo-content/0/help_documentation/` using **channel_key** instead of **channel_id**. **PRD 30:** Added help_documentation channel definition with purpose, content types, and organization guidelines. **PRD 16:** Updated file_path_from_root documentation to specify `lupo-content/{federation_node_id}/{channel_key}/{content_id}_{slug}.md` format. **Content created:** 5 help guides (getting started, actors, channels, content, edges), 8 common questions, 8 answers, 34 relationship edges. **Database integration:** Updated seed SQL with correct file_path_from_root values, added channel_key field to headers. **Installation:** Updated build script, generated consolidated seed (27,607 bytes), integrated with installer workflow. **Removed:** Old `lupo-content/0/0/` structure.
- **WHY:** User identified that numeric channel_id structure was unclear and development-oriented (channel 42). Need for semantic organization using channel_key for better navigation, maintainability, and separation of user-facing vs development content.
- **Co-commit note:** This work establishes the canonical pattern for channel_key-based content organization, scalable to additional content types beyond help documentation.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] Semantic navbar external embed — Admin provisioning + PRD 21 + gate API message (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-includes/classes/SemanticNavbarEmbedContext.php`**; **`lupo-includes/modules/api/semantic-navbar-api.php`**; **`lupo-includes/classes/AdminSemanticWidgetHandler.php`**; **`lupo-includes/lang/lupo-en.php`** (semantic widget strings); **`admin.php`** (semantic-widget section blurb); **`lupo-docs/prd/21_semantic_navbar.md`**; version folder **`lupo-docs/versions/4.0.94/`** — `decisions/20260405104405_DECISION_APPROVED_semantic_navbar_embed_admin_prd21_cursor_thread.md`, `comments/20260405104405_COMMENT_cursor_session_end_semantic_navbar_crafty_handoff.md`, `CHANGELOG` (this entry), `PLAN` Phase **M**, `TODO`, `edges`, `WHAT_TO_WORK_ON_NEXT_SESSION`, `THREAD_INDEX` files; UTC **`20260405104405`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Cross-origin **semantic widget** gate: **`lupo_federation_nodes`** + **`lupo_federated_trust`** (`**semantic_widget**`), **`lupo_federation_discovery`** on denied origins; **403** JSON **`embed_not_trusted`** with **Admin → Semantic widget** guidance. **Admin UI:** register embedder **origin** (normalized), grant hub→target trust, node/trust summary table, **CSRF** POST forms, **`admin.php?section=semantic-widget`** on form action; page order **relative** snippet **before** **absolute** snippet **before** external/federation block. **PRD 21:** allowlist, tracking, discovery, CORS/client params, **slug vs foreign path** rationale, **no SQL** for routine operator provisioning (steps 1–2), content step via **artifacts/headers**; edges to PRD **11**, **34**, **`SILENT_HARVEST_DOCTRINE.md`**, **`SEMANTIC_MONITORING_DOCTRINE`**, **`AdminSemanticWidgetHandler`**. LILITH audit items: PRD **34** + **SILENT_HARVEST** edges; PRD **11** reason refined.
- **WHY:** Operators must not hand-edit SQL for embedder setup; explicit trust and origin; avoid mistaking embedder URL path for **`lupo_contents`** key.
- **Co-commit note:** The same **`git push`** may include **additional** workspace paths (e.g. **login** / **i18n**, **import SQL**, **data hub** handlers, **PRD 00** / **11**, **AGENTS**, images, **`lupo-logs`**) not itemized in this **CHANGELOG** bullet — verify with **`git log -1 --stat`** / **GitHub** diff. **Do not** attribute those paths to this narrative without per-file evidence.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-05] Admin scroll nav — logout clears intro sessionStorage; logo slot; actor strip trim (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`logout.php`**; **`lupo-includes/themes/default/layouts/admin_layout.php`**; **`lupo-includes/css/admin-intro-scroll.css`**; **`lupo-includes/js/admin-intro-scroll.js`** (comment); version folder **`lupo-docs/versions/4.0.94/`** — `decisions/20260405001004_DECISION_APPROVED_admin_nav_logout_intro_cursor_thread.md`, `comments/20260405001004_COMMENT_cursor_session_end_admin_nav_logout_handoff.md`, `CHANGELOG`, `PLAN` Phase **L**, `TODO`, `edges`, `WHAT_TO_WORK_ON_NEXT_SESSION`, `THREAD_INDEX` files; UTC **`20260405001004`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** **`sessionStorage` key `lupo_admin_scroll_intro_v1`** survived logout in the **same tab**, so the admin scroll intro skipped on second login — **fix:** logout returns minimal HTML that **`removeItem`** then redirects to **`login.php`**. **Left nav:** **90×60** logo link (defaults **`lupo-images/logoface.png`** → **`index.php`**; overridable **`$admin_nav_logo_*`**). **Right nav:** removed **`ACTOR:`** prefix; display name **15** chars + **`..`** when longer (**`mb_*`** when present); full name in **`title`**. CSS: **`max-width: 12em`** on actor text.
- **WHY:** Match operator expectation (intro replays per login session in-tab); tighter top bar.
- **WHAT NOT:** Does **not** document PRD **16/26/30/31** validator or **COUNTERMEASURE** work from unrelated templates — **no** evidence in **this** thread. Other logout paths besides **`logout.php`** not audited for the same **`sessionStorage`** clear.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] Version 4.0.94 folder sync — AGAPE / KAIROS temporal / multi-actor routing thread (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/versions/4.0.94/`** — `decisions/THREAD_INDEX.md`, `comments/THREAD_INDEX.md`, `edges.md`, `PLAN.md` Phase **K**, `TODO.md` (completed + open follow-ups), `WHAT_TO_WORK_ON_NEXT_SESSION.md`; UTC **`20260404175352`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Indexed **DECISION** [`decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md`](decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md) (**5W1H** APPROVED receipt); **COMMENT** [`comments/20260404175216_COMMENT_cursor_session_end_agape_kairos_routing_version_sync.md`](comments/20260404175216_COMMENT_cursor_session_end_agape_kairos_routing_version_sync.md); linked **edges** to `AGAPE_DOCTRINE.md`, **PRD 37**, **PRD 18**, `scaffold_implementation.py`; **PLAN** Phase **K** documents doc-only completion and lists runtime gaps (**`to_actor_id`**, KAIROS §10.6, optional §14.6 scanner).
- **WHY:** Preserve **WHO/WHAT/WHERE/WHEN/WHY/HOW** traceability for the same thread as the three substantive **CHANGELOG** entries immediately below (AGAPE, PRD 37/`add-status`, multi-actor routing) — **no** bundled claims for unrelated PRD 16/26/30/31 validator work without evidence.
- **WHAT NOT:** Does not assert **`channels-api`** or chat UI already implement **`to_actor_id`**; does not assert KAIROS code fully matches §10.6.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] Multi-actor channel routing — `to_actor_id` simple pattern (PRD 18, 36, 37, 31, 05) (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/18_channel_chat_display.md`** (*Multi-actor routing*), **`36_rose_multi_persona_synthetic_dialog.md`** §1.3 switchboard, **`37_kairos_channel_memory_consolidation.md`** §10.6 chat context, **`31_implementation_folder_guidelines.md`** cross-reference table, **`05_auth_user_actor_agent_transformation.md`** channel communication model; UTC **`20260404174956`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** **Channel + thread = complete context**; **`to_actor_id`** (directive synonym *said-to* / `said_to_actor_id`) = **routing only**, **not** in-channel visibility; **NULL** = broadcast; **no** `mention_actor_ids` column; ROSE / THOTH / KAIROS read **full threads**; **`parent_dialog_message_id`** documented as **not** in current TOON until DDL.
- **WHY:** LILITH directive — Option 1 simplicity; schema truth aligned to **`lupo_dialog_messages`** TOON.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] PRD 37 temporal discipline + `add-status` + AGAPE cross-ref + PRD 31 (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/37_kairos_channel_memory_consolidation.md`** new **§10** (Temporal discipline / anti-backwards reads), renumber open questions / references; **`lupo-docs/doctrine/AGAPE_DOCTRINE.md`** §1.3 Temporal awareness; **`lupo-scripts/scaffold_implementation.py`** **`add-status`** subcommand (backward-compatible legacy CLI); **`lupo-docs/prd/31_implementation_folder_guidelines.md`** usage block; UTC **`20260404173921`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Normative **index-first** reading, freshness hierarchy (filename UTC → `when_updated` → `last_modified_utc`), **`supersedes`** vs **`references`** for status lineage, KAIROS documentation contract; tooling creates **`YYYYMMDD_HHIISS_STATUS_{slug}.md`**, optional edge to prior artifact, updates **`status/THREAD_INDEX.md`**; **`--impl`** accepts full **`prd_file_stem`** or numeric PRD id when unambiguous.
- **WHY:** LILITH audit — doctrine before tooling; prevents backwards filesystem reads from masquerading as truth.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] AGAPE technical doctrine — constitution §14.6, AGAPE_DOCTRINE.md, LILITH/ROSE/validators, agent packs (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); `lupo-docs/prd/00_root_constitutional_system_requirements.md` **§14.6** + edge, new **`lupo-docs/doctrine/AGAPE_DOCTRINE.md`**, `lupo-rules/root/lilith-noninterference-doctrine.md`, `.cursor/rules/lilith-noninterference-doctrine.mdc`, `lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md`, `lupo-docs/prd/07_agents_faucets.md`, `lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md`, `lupo-agents/agape/*`, `lupo-agents/rose/system_prompt.txt` (+ v1.0.0), scrub **`SEMANTIC_SECURITY_CHECKLIST_4_0_30.md`**, **`WHAT_LUPOPEDIA_IS.md`**, **`VERSION_PLANS_3.0.82_3.0.88.md`**; UTC **`20260404172442`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** **AGAPE** redefined as **Agentic Guidance And Practical Empathy** — **technical** resilience and inter-actor cooperation (not sentiment). **LILITH** review prompts = environment + fallbacks. **ROSE** synthetic = **cooperation metric** in **`metadata_json`** (optional **`agape_cooperation_metric`** / **`agape_cooperation_rationale`**). Validators MUST flag **“made with love,”** **“supportive tone,”** **“emotional validation”** as criteria violations (**§14.6**).
- **WHY:** Codify environmental awareness, KAIROS-class self-archival, and shared-host survival as a named constitutional heuristic.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] WordPress study tree path — lupo-archive/legacy/wordpress-reference; .gitignore note; packager exclude lupo-archive/ (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); `README.md`, `AGENTS.md`, `lupo-docs/prd/00_root_constitutional_system_requirements.md` §15 table + prose, `lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`, `lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md`, `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md`, Q/A/status threads under implementation 33, `lupo-scripts/build_softaculous_package.sh`, `.gitignore` comment; UTC **`20260404165054`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Canonical path **`lupo-archive/legacy/wordpress-reference/`** (replacing repo-root **`wordpress-reference/`**); documented **`.gitignore`** on **`lupo-archive/`**; **`rsync`** **`--exclude='lupo-archive/'`** (retains exclude of legacy root **`wordpress-reference/`**).
- **WHY:** Keep the GPL study tree out of the committed tree and distribution zip; align docs and file citations.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] scaffold_implementation.py — THREAD_INDEX from _template, status/, fix f-string bug, README index row (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); `lupo-scripts/scaffold_implementation.py`, `lupo-docs/prd/31_implementation_folder_guidelines.md` (scaffold behavior paragraph), `lupo-docs/implementations/README.md` (scaffold note); UTC **`20260404164842`** (`python lupo-bin/tick.py` for PRD 31 headers).
- **WHAT:** **`create_thread_indexes`** now copies **`_template/{questions,answers,comments}/THREAD_INDEX.md`** with path/`parent_prd`/`when_updated`/`thread_id` substitution; writes minimal **`decisions/THREAD_INDEX.md`**; adds **`status/`** with **`STATUS.md`** stub + **`THREAD_INDEX`**; removes broken **`{{folder}}`** f-string replace; **`update_implementations_index`** matches current **`| Folder | PRD | Notes |`** table and uses **`prd_file_stem`** for PRD link.
- **WHY:** WOLFIE/CAPTAIN question: agents need **THREAD_INDEX** without hand-rolling; prior script called **`create_thread_indexes`** but placeholders never substituted.
- **WHAT NOT claimed:** No new **`_template/decisions/THREAD_INDEX.md`** (minimal generated table instead).

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] Implementation mirror naming — PRD 31 + §5.8 + AGENTS + implementations README + PRD 29 + scaffold docstring (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); `lupo-docs/prd/00_root_constitutional_system_requirements.md` **§5.8**, `lupo-docs/prd/31_implementation_folder_guidelines.md`, `lupo-docs/implementations/README.md`, `IMPLEMENTATION_QUESTIONS_GUIDE.md`, `lupo-docs/prd/29_project_structure.md`, `AGENTS.md`, `SERVICE_AGENT_ARCHITECTURE.md` §7, `lupo-scripts/scaffold_implementation.py`, `implementations/service_agents/README.md`, `implementations/36_rose_multi_persona_synthetic_dialog/README.md`; UTC **`20260404163615`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Documented **`prd_file_stem`** rule (implementation folder name = canonical PRD **`lupo-docs/prd/{stem}.md`** basename without **`.md`**); forbidden examples; scaffold **`--title`** alignment; cross-links among **PRD 31**, **§5.8**, index, **AGENTS** mandatory literacy + behavior **5**, **PRD 29** table cleanup.
- **WHY:** Prevent misnamed **`lupo-docs/implementations/`** directories and make **decisions/questions/answers** location obvious for IDE agents.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] PRD 36 implementation folder rename — `36_rose_multi_persona_synthetic_dialog/` (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); renamed **`lupo-docs/implementations/prd_36_rose/`** → **`lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/`**; edges and prose in PRD 00, PRD 36, `SERVICE_AGENT_ARCHITECTURE.md`, `implementations/service_agents/README.md`; UTC **`20260404163220`** (`python lupo-bin/tick.py` this batch).
- **WHAT:** Canonical implementation mirror path now matches PRD filename slug; old folder removed.
- **WHAT NOT claimed:** No product rule changes beyond path references.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] ROSE synthetic choir — constitution §5.10.3, PRD 36, SERVICE_AGENT_ARCHITECTURE, `lupo-agents/rose`, implementation `36_rose_multi_persona_synthetic_dialog/` (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); `lupo-docs/prd/00_root_constitutional_system_requirements.md` **§5.10** table + **§5.10.3**, `lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md`, `lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md`, `lupo-docs/implementations/service_agents/README.md`, `lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/` (README, `status/`, `decisions/20260404_162844_DECISION_rose_batch_every_10_messages.md`), `lupo-agents/rose/agent.json` + `versions/v1.0.0/agent.json`; UTC **`20260404162844`** (`python lupo-bin/tick.py` first batch); folder path corrected **`20260404163220`** (see entry above).
- **WHAT (thread-verified only):**
  - **ROSE as Director of the synthetic choir:** PHP-first default **every 10 organic messages** (overridable), operator-selected persona voicing, **`metadata_json`** including **`rose_visibility`** (`actor_only` / `visitor_visible`), **≤ 2000** characters per synthetic line, inserts **`lupo_dialog_messages`** with **`from_actor_id` = voiced persona**; **KAIROS** handoff via **`KairosConsolidationService::recordObservation`** (normative in PRD 36 §7).
  - **Planned service class name:** **`app/Services/Rose/RoseDialogService.php`** (documentation-only until Phase B).
  - **Agent pack:** **`role`** string updated; **`is_internal_only: true`** and **`layer: coordination`** unchanged (verified).
- **WHY:** Align constitution, PRD, doctrine, and implementation mirror with the PHP-first service-agent model for multi-persona synthetic dialog.
- **HOW:** Markdown + YAML headers (`tick.py` UTC); no install SQL or runtime PHP for ROSE orchestration in this batch.
- **WHAT NOT claimed:** No **`RoseDialogService.php`** implementation, no channel API or PRD 18 UI changes in this thread.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] Service agent architecture + PRD 00 §5 (KAIROS / THOTH / roster) + Softaculous auto-installer package docs + runtime mkdir + config sample (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); `lupo-docs/prd/00_root_constitutional_system_requirements.md` §5.1–§5.10, `lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md`, `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md` (THOTH grounding), `lupo-docs/implementations/service_agents/`, `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md`, repo root `lupopedia-config-sample.php` + `README.txt`, `lupo-includes/bootstrap.php`, `install.php`, `index.php`, `lupo-scripts/build_softaculous_package.sh`; version-folder receipt `decisions/20260404_161001_DECISION_APPROVED_service_agent_architecture_and_softaculous_auto_installer_docs.md`, `comments/20260404_161001_COMMENT_cursor_service_agents_softaculous_version_doc_sync.md`; UTC **`20260404161001`** (`python lupo-bin/tick.py` this batch).
- **WHAT (thread-verified only):**
  - **Constitution PRD 00 — Section 5:** Expanded **§5.1–5.2** (agents as blueprint, actors as hybrid instances); **§5.7** KAIROS memory consolidation (invocation, `lupo_actor_memory`, `lupo_edges`); **§5.8** implementation mirroring under `lupo-docs/implementations/<prd_slug>/`; **§5.9** THOTH stale-artifact audit epoch; **§5.10** service agents — **explicit roster** **IRIS**, **ANUBIS**, **ROSE**, **THOTH**, **KAIROS**, table vs **`RuntimeActorLoopService`** / **`runtime_actors.yaml`** conversational path; YAML edges to `SERVICE_AGENT_ARCHITECTURE.md`, `implementations/service_agents/README.md`, KAIROS PHP, `iris.php`, PRD 36/37.
  - **Doctrine:** **`SERVICE_AGENT_ARCHITECTURE.md`** — PHP-first LLM-second, “not default chat targets,” KAIROS flow (PHP trigger → memory → edges), THOTH authority from repo JSON + table docs.
  - **LUPOPEDIA_HEADERS README:** **THOTH** — binding **grounding rule** (evidence from `lupo-database/lupopedia/json/*.json` + `lupo-docs/database/lupopedia/tables/active/`; not unchecked model parametric knowledge); cross-refs to constitution §5.9/5.10 and service agent doctrine.
  - **Implementation mirror:** `lupo-docs/implementations/service_agents/` — README, `status/`, `decisions/` (incl. `20260404_160645_DECISION_php_first_service_agents.md`), Q/A/C THREAD_INDEX stubs.
  - **Softaculous / auto-installer:** **`SOFTACULOUS_PACKAGE_BUILD.md`** — §1b silent install contract, §1c `lupopedia-config-sample.php`, bootstrap self-heal note, summary table (`lupopedia-config-sample`, exclude live `lupopedia-config.php`, `license.txt` / `README.md` / `README.txt`), §10 subdirectory JS; **`lupopedia-config-sample.php`** (`[[softdb*]]`, `define()`-style); **`README.txt`**; **`bootstrap.php`** — `mkdir` for `lupo-cache`, `lupo-logs`, `lupo-uploads`, `lupo-tmp`; **`install.php`** — `LUPO_INSTALLING`; **`index.php`** — comment that config present ⇒ no redirect to wizard; **`build_softaculous_package.sh`** — exclude `lupopedia-config.php` / backup from zip.
- **WHY:** Institutional clarity (service vs conversational agents); hoster-grade packaging (no credential leak, empty FTP dirs, auto-installer config template); THOTH reviews grounded in schema exports.
- **HOW:** Markdown + YAML headers (`tick.py` UTC); PHP `mkdir` + constants sample; shell `rsync` excludes; version **`CHANGELOG` / `PLAN` Phase J / `TODO` / `edges.md`** sync this batch.
- **WHAT NOT claimed:** No **PRD 16** / **PRD 26** / **PRD 30** / **PRD 31** rewrites, no **`validate_implementation.py`** / universal header validator edits, no **COUNTERMEASURE** review of those PRDs, no **install SQL** schema changes **in this thread** — those belong to their own evidenced commits.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-04] Softaculous / WordPress patterns — root §15, doctrines, installer htaccess + packager excludes; semantic + chat JS policy (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); `lupo-docs/prd/00_root_constitutional_system_requirements.md` §15, `lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md`, `SEMANTIC_MONITORING_DOCTRINE.md`, `CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md`, `lupo-docs/prd/33_*` / `28_*` / `18_*` edges, `lupo-install/InstallWizardHtaccessWriter.php`, `lupo-scripts/build_softaculous_package.sh`, `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`; UTC **`20260404074421`** (`python lupo-bin/tick.py` this batch).
- **WHAT (thread-verified only):**
  - **Constitution / doctrine:** **PRD 00** §15 WordPress multi-environment patterns; **`LEARNED_FROM_WORDPRESS.md`** distillate; **`SEMANTIC_MONITORING_DOCTRINE.md`** (Eye vs `livehelp_js`, real routes such as **`nav/semantic-navbar-js`**, no invented REST); **`CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md`** (prefer shared namespace / default **non-IIFE** for admin chat); **PRD 28** / **PRD 18** cross-links updated where recorded in those files.
  - **Installer / packager:** **`InstallWizardHtaccessWriter`** — `# BEGIN LUPOPEDIA` / `LUPOPEDIA_DB` marker merge, `insertWithMarkers`, Apache environment gate (`SERVER_SOFTWARE`), legacy unmarked body upgrade path; **`build_softaculous_package.sh`** — explicit **rsync** excludes for `.htaccess` / `.htpasswd` (sanitize already strips dotfiles).
  - **Implementation 33:** WordPress study + packager flow **Q/A** (e.g. **`20260404_065622_*`**), **`wordpress_pattern_implementation_tasks_20260404.md`**, **THREAD_INDEX** rows under **`questions/`** / **`answers/`** as committed in-repo.
  - **Informal Crafty reference:** **IIFE** grep — **2** inline snippets in **`admin_users_refresh.php`** (audio `play()`), **0** in standalone **`.js`** under **`craftysyntax-reference/`** (not a formal validator run).
- **WHY:** Hoster-grade `.htaccess` behavior, honest embed/monitoring semantics for IDE agents, and chat JS that does not hide shared state behind **IIFE**s.
- **HOW:** Markdown with **`lupopedia.headers`**; PHP marker merge + gating; shell **rsync** excludes; version-folder receipt **`comments/20260404_074421_COMMENT_cursor_session_end_softaculous_wordpress_semantic_chat.md`**; **`WHAT_TO_WORK_ON_NEXT_SESSION.md`** / **`PLAN.md`** Phase **I** / **`TODO.md`** / **`edges.md`** updated this batch.
- **WHAT NOT claimed:** No **PRD 16/26/30/31** rewrites, no **`validate_implementation.py`** / universal header validator edits, no **install SQL** schema changes in this thread.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-03] Department-first actor model — APPROVED decision + synthesis ANSWER; federation navigation QUESTION (Cursor + LILITH)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**), LILITH audit (`actor_id` **2**); `lupo-docs/versions/4.0.94/decisions/`, `questions/`, `answers/`; UTC **`20260403222041`** (`python lupo-bin/tick.py` this batch).
- **WHAT (thread-verified only):**
  - **Decision:** `decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md` — APPROVED canonical department-first documentation + PRD alignment (see decision body for PRD list and **WHAT NOT claimed**).
  - **Answer:** `answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md` — links implementation visitor-chat Q1–Q3 to doctrine + PRDs; remaining runtime audit noted.
  - **Question (OPEN):** `questions/20260403_222042_QUESTION_federation_navigation_compiler.md` — product options for navigation hints from aggregates; cites pre-existing **`SILENT_HARVEST_DOCTRINE.md`** (not created in this thread).
- **WHY:** Version-folder audit trail for approved model and open federation product question.
- **HOW:** New markdown under `4.0.94/`; `PLAN.md` Phase **H**, `TODO.md`, `edges.md`, `WHAT_TO_WORK_ON_NEXT_SESSION.md`, and `THREAD_INDEX.md` files updated in same batch.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-03] LILITH audit — PRD 15 department-first act-as; `ActorService` delegates to `AuthSessionManager` (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`ActorService.php`**, **`AuthSessionManager.php`**, **`lupo-docs/prd/15_actors.md`**, **`lupo-docs/prd/25_departments_system.md`** (`lupo_actor_departments` columns), **`lupo-docs/prd/07_agents_faucets.md`**, **`lupo-docs/prd/32_actor_authority_agent_roles.md`**, **`AGENTS.md`**, **`lupo-docs/versions/4.0.94/CHANGELOG.md`**; UTC **`20260403211538`** (`python lupo-bin/tick.py`).
- **WHAT:** **`App\Services\ActorService::getActorsUserCanActAs`** now **delegates** to **`AuthSessionManager::getActorsUserCanActAs`** (department-scoped join; same as web UI). Removed edge-based **`lupo_edges` `supports`** list from **`ActorService`**. **`AuthSessionManager`:** early path for **`auth_user_id === 10000`** (all active actors, bypass creator restriction) preserved. **PRD 15** rewritten: department-first eligibility, **`lupo_actor_auth_users`** as binding/audit not sole gate, deprecated exclusive lease + edge act-as. **PRD 25:** **`lupo_actor_departments`** table doc aligned to install (**`actor_department_id`**, **`role_key`**). **PRD 07 / 32 / AGENTS:** cross-links and act-as vs authority clarification.
- **WHY:** LILITH audit — single implementation for act-as lists; docs match **PRD 05** / **PRD 25**.

# [2026-04-03] `lupo-actors/` — COUNTERMEASURE hub at `111/` + registry/doctrine alignment (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-actors/111/`** (moved from **`countermeasure/`**), **`lupo-database/lupopedia/actors/registry.json`**, **`lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`**, **`lupo-docs/doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md`**, **`lupo-actors/README.md`**, **`ActorService.php`** (docblock); UTC **`20260403210921`** (`python lupo-bin/tick.py`).
- **WHAT:** Registry **`dir`** for COUNTERMEASURE → **`lupo-actors/111`** (PRD 00 §5.6: reserved **`actor_id` &lt; 2026** use **`lupo-actors/{actor_id}/`**). Docs clarify runtime **`actor_id` ≥ 2026** → **`lupo-actors/YYYY/MM/{actor_id}/`**; slug-only **`lupo-actors/countermeasure/`** removed as incorrect actor hub. **`lupo-agents/countermeasure/`** unchanged (agent_key namespace).
- **WHY:** Actor filesystem hub is keyed by **`actor_id`**, not slug; matches **`SkillService`** numeric-dir probe and registry authority.

# [2026-04-03] Live-DB TOON export — wipe `json/` + `toon/` then regen; fix double-unlink (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-scripts/generate_toon_files.py`**, **`lupo-scripts/generate_toon_from_sql.py`**, **`lupo-docs/versions/4.0.94/CHANGELOG.md`**; UTC **`20260403193256`** (`python lupo-bin/tick.py`).
- **WHAT:** **`clear_toon_files`** now deletes **all regular files** in **`lupo-database/lupopedia/json/`** and **`.../toon/`** before writing live-DB exports (primary workflow: schema mirrors the database, no orphans). Fixes **`FileNotFoundError`** on **`unlink`**: **`toon/*.toon.json`** matched both **`*.json`** and **`*.toon.json`**, so the same path was removed twice. Docstrings: **DB-first** vs **`generate_toon_from_sql.py`** (offline install-SQL + targeted **`lupo_*`** prune only).
- **WHY:** Full directory wipe is simpler and matches “empty then regenerate”; selective globs were redundant and buggy on Windows.

# [2026-04-03] `generate_toon_from_sql.py` — prune stale `lupo_*` json/toon exports (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-scripts/generate_toon_from_sql.py`**, **`lupo-scripts/generate_toon_files.py`**; UTC **`20260403192553`** (`python lupo-bin/tick.py`).
- **WHAT:** After regenerating `*.toon.json` from **`install_new_lupopedia.sql`**, **`prune_stale_table_exports`** deletes **`json/lupo_*.json`**, **`toon/lupo_*.toon.json`**, and **`toon/lupo_*.toon`** whose table name is not in the install DDL (prints removed paths). **`generate_toon_files.py`** — fixed **`IndentationError`** in the export loop; **`clear_toon_files`** (superseded below) had also added **`toon/*.toon.json`** to the glob list.
- **WHY:** Dropped tables must not leave orphan schema files when using the **install-SQL** exporter.

# [2026-04-03] Web act-as restriction + lease-session cleanup + TOON regen from install (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`install_new_lupopedia.sql`**, **`ActorService.php`**, **`AuthSessionManager.php`**, **`AuthService.php`**, **`generate_toon_from_sql.py`**, **`database_audit_fresh.py`**, **`lupo-database/lupopedia/toon/`**, **`lupo-docs/prd/05_auth_user_actor_agent_transformation.md`**, **`lupo-docs/database/.../lupo_actor_auth_users.md`**, **`lupo-docs/versions/4.0.94/CHANGELOG.md`**; batch UTC **`20260403192018`** (real UTC via `python lupo-bin/tick.py`).
- **WHAT (verified):**
  - **Schema** — **`lupo_actors.web_restrict_act_as_creator_or_root`** (default **0**); install comment documents pairing on **`lupo_actor_auth_users`** without an exclusive lease-session table; removed unused **`lupo_actor_*`** auxiliary tables from install (templates / instances / lease_sessions / department_actor_pools) per prior thread.
  - **PHP** — No “other session holds this actor” filtering; **`releaseAllLeasesForUser`** no-op; **`getActorsUserCanActAs`** / **`updateActiveActor`** enforce creator-or-bypass when the flag is **1**; **`ActorService`** own-actor list includes restriction metadata so end-filter is correct (duplicate pre-filter removed).
  - **Tooling** — **`generate_toon_from_sql.py`** replaces **`{{prefix}}`** with **`lupo_`** before parsing so TOON generation is non-zero; **166** TOONs written under **`lupo-database/lupopedia/toon/`**.
  - **Repo hygiene** — Removed stale **`lupo-database/lupopedia/json/`** exports for dropped tables; **`database_audit_fresh.py`** priority table list updated; PRD **05** + **`lupo_actor_auth_users`** table doc aligned with pairing + concurrent sessions.
- **WHY:** Match product intent: configurable web act-as limits (creator or root-department bypass) without single-user exclusive leasing.
- **Artifacts:** As listed in **WHERE**; deleted JSON: `lupo_actor_templates.json`, `lupo_actor_instances.json`, `lupo_actor_lease_sessions.json`, `lupo_department_actor_pools.json`.

# [2026-04-03] `find_edges.py` — suggest `lupopedia.edges` from markdown (LILITH-approved concept; Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-scripts/find_edges.py`**; version doc touch UTC **`20260403143059`** (real UTC via `python lupo-bin/tick.py`).
- **WHAT (verified):**
  - **New script** — **`lupo-scripts/find_edges.py`**: scans markdown for links, PRD references, optional keyword hints, code/tree paths; optional **`--headers`** (`##` match across `lupo-docs/`); prints suggested **`outbound_edges`** with weight + reason; **does not write** by default.
  - **Safety** — **`--apply`** merges into YAML only with **`--yes`** (batch) or **`--interactive`** (per-edge); otherwise exits **2**; writes **`*.bak_find_edges`** before overwrite; requires **PyYAML** for `--apply`.
  - **Handoff** — **`WHAT_TO_WORK_ON_NEXT_SESSION.md`** updated: WOLFIE plans to **debug and exercise** this tool against **`.md`** files on return, among other backlog items.
- **WHY:** Automate edge *discovery*; keep human confirmation for writes (per LILITH audit posture).
- **Artifacts:** `lupo-scripts/find_edges.py`, `lupo-docs/versions/4.0.94/CHANGELOG.md` (this entry), `lupo-docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md`.

# [2026-04-03] Doctrine audit tooling, version ghosts, mobile / workflow doctrines (Cursor + LILITH thread)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**), LILITH audit framing (`actor_id` **2**), orchestrator WOLFIE (`actor_id` **1**); **`lupo-docs/doctrine/`**, **`lupo-docs/prd/`**, **`AGENTS.md`**, **`lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md`**, **`lupo-docs/versions/4.0.94/`**; documentation batch UTC **`20260403140552`** (real UTC via `python lupo-bin/tick.py`).
- **WHAT (thread-verified only):**
  - **`python lupo-scripts/audit_doctrine_prd_edges.py`** — **189** files under `lupo-docs/doctrine/` with **`lupopedia.edges`** including PRD lineage (**0** missing).
  - **`lupo-docs/implementations/29_project_structure/status/version_ghosts_report.json`** — **34** files with **critical** ghost findings (scanner: **`lupo-scripts/find_version_ghosts.py`**).
  - **Repository scripts (existence only — no verified batch counts in this thread):** `audit_doctrine_prd_edges.py`, `find_version_ghosts.py`, `fix_doctrine_headers.py`, `apply_doctrine_prd_lineage.py`, `convert_wolfie_to_lupo.py` under **`lupo-scripts/`**.
  - **Documentation written or materially updated in this thread:** **`MOBILE_SEPARATION_DOCTRINE.md`** (Two-UI, admin desktop-first exception, Eye / PRD 28 split); **`WOLFIE_WORKFLOW_DOCTRINE.md`** (consumer mobile-first / admin desktop-first); **`lupo-docs/prd/35_mobile_native_app_separation.md`** (draft); **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** (§7.4 mobile checklist + edges, where edited in-repo); **`AGENTS.md`** (mobile, Two-UI, workflow, Eye, hand-coding policy); **`lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md`** (hand-coding doctrine, UI-framework note).
  - **Version folder:** **`CHANGELOG.md`** (this entry); **`PLAN.md`** Phase **G**; **`TODO.md`** (ghost backlog + completed coordination lines); **`edges.md`**; **`README.md`** stamps; **`decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md`**; **`questions/20260403_140553_QUESTION_version_ghost_cleanup_policy.md`**; **`answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md`**; **`THREAD_INDEX.md`** updates in **`decisions/`** / **`questions/`** / **`answers/`**.
- **WHY:** Record PRD-lineage completeness and ghost backlog without inventing remediation counts; lock mobile / workflow guidance for IDE agents.
- **WHY NOT in this entry:** No claim that **PRD 34** was created here (draft may exist elsewhere); no claim for **Stoned Wolfie** archive paths or **SILENT_HARVEST** / **TWO_LAYER_SECURITY** / **REVERSE_ENGINEERING** / **ADVERSARIAL_TEST_IDENTITY** file creation dates unless a thread diff proves it — cite those only in their own file headers.
- **Artifacts:** `decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md`, `questions/20260403_140553_QUESTION_version_ghost_cleanup_policy.md`, `answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md`, `comments/20260403_140555_COMMENT_cursor_doctrine_audit_version_sync.md`.

# [2026-04-03] DynAPI doctrine — approved in-tree library + IDE guidance (Cursor thread)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/doctrine/DYNAPI_DOCTRINE.md`**; file header **`last_modified_utc` / `when_updated` → `20260403031423`** (real UTC via `python lupo-bin/tick.py`); **`lupopedia.footer.last_verified` → `20260402`** (LILITH audit date, 8-digit form per universal validator).
- **WHAT (this thread only — verified):**
  - **New doctrine** — **`DYNAPI_DOCTRINE.md`**: DynAPI (Dan Steinman / Bob Clary) as **approved in-tree** DHTML helper; **IDE** must-not / must-do; compact API reference; **WOLFIE Eyes** traceability via **`lupo-docs/prd/28_semantic_monitoring_widget.md`**, **`lupo-includes/js/crafty_syntax_eyes.js`**, canonical **`lupo-includes/js/dynlayer.js`**; **`lupopedia.edges`** include **PRD 33** (Softaculous / gate — §8 in-tree libraries), PRD 28 path, and code paths aligned to files that exist in-repo.
  - **Tooling:** `python lupo-scripts/validate_lupopedia_headers_universal.py lupo-docs/doctrine/DYNAPI_DOCTRINE.md` — exit **0** (optional INFO: no `content_id`).
- **WHY:** Single canonical doc so IDE agents stop “replace DynAPI” churn; matches **PRD 33** approved-library posture and **DynAPI** usage already loaded from **`lupo-includes/ui/ui-loader.php`**.
- **WHY NOT in this entry:** No **PRD 16/26/30/31** text edits, **`validate_implementation.py`** / validator logic changes, **PK** constitutional edits, **install SQL**, **`decisions/`** / **`questions/`** / **`edges.md`** / **`PLAN.md`** / **`TODO.md`** updates in this thread — **not** claimed here.
- **Artifacts:** `lupo-docs/doctrine/DYNAPI_DOCTRINE.md` only.

# [2026-04-03] PRD 31 — LILITH final audit merged + 4.0.94 version sync (Cursor thread)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/31_implementation_folder_guidelines.md`**, **`lupo-docs/versions/4.0.94/`**; PRD 31 doc stamps UTC **`20260403024822`**; version-folder sync UTC **`20260403025155`** (thread filenames **`20260403_025155`** … **`20260403_025158`**).
- **WHAT (this thread only — verified):**
  - **PRD 31** — Expanded **`## LILITH audit record (final)`**: score **98/100**, prior rejection → resolution table, operational note (new implementation folders after **2026-04-03**; **90-day** grace per **PRD 26**); header/footer **`when_updated` / `last_modified_utc` / `last_verified` → `20260403024822`**; **`lupopedia.footer.next_action`** includes grace pointer; **`status: active`** unchanged.
  - **Version folder** — **`CHANGELOG.md`** (this entry); **`PLAN.md`** Phase **C-FW-4**; **`TODO.md`** (next-session + scaffold follow-up); **`edges.md`**; **`README.md`**; **`WHAT_TO_WORK_ON_NEXT_SESSION.md`** (admin UI, fresh install + Crafty import, Crafty parity, **Eye**); **`decisions/`** / **`questions/`** / **`answers/`** / **`comments/`** — **`20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md`**, **`20260403_025156_QUESTION_prd31_version_sync_changelog_scope.md`**, **`20260403_025157_ANSWER_prd31_version_sync_changelog_scope.md`**, **`20260403_025158_COMMENT_cursor_session_end_prd31_next_session.md`**; **`THREAD_INDEX.md`** in each folder updated.
  - **Tooling:** `python lupo-scripts/validate_lupopedia_headers_universal.py lupo-docs/prd/31_implementation_folder_guidelines.md` — exit **0** (existing WARN: **`lupopedia.schema: prd`** not in small schema list).
- **WHY:** Record **LILITH** final approval in the canonical PRD; preserve **5W1H** lineage without inventing unrelated PRD/validator claims.
- **WHY NOT in this entry:** No claim for **PRD 16/26/30** text edits, **`validate_implementation.py`** changes, **PK** constitutional edits, or **install SQL** — not in this thread.
- **Artifacts:** `decisions/20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md`, `questions/20260403_025156_QUESTION_prd31_version_sync_changelog_scope.md`, `answers/20260403_025157_ANSWER_prd31_version_sync_changelog_scope.md`, `comments/20260403_025158_COMMENT_cursor_session_end_prd31_next_session.md`, `WHAT_TO_WORK_ON_NEXT_SESSION.md`.

# [2026-04-03] PRD 33 approved — Softaculous / 4.1.0 gate documentation + 4.0.94 version sync (Cursor thread)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`**, **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`**, **`lupo-docs/versions/4.0.94/`**; version-folder update stamp **UTC `20260403022543`** (thread filenames **`20260403_022543`** … **`20260403_022546`**).
- **WHAT (this thread only — no PRD 16/26/30/31 validator claims unless edited in-repo with evidence):**
  - **PRD 33** — **`lupopedia.headers.status: approved`**; **`when_updated` / `last_modified_utc` → `20260403022543`**; **§13** updated so header approval is explicit while **§7–§10 checklist execution** stays in **`TODO.md`** per **§12**.
  - **Implementation workspace** — **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`** (README, changelog, todo, authors, edges, `status/` including LILITH audit import, typed thread folders) — *already present from prior work; this pass records it in the version graph.*
  - **`lupo-docs/versions/4.0.94/`** — **`PLAN.md`** Phase D (documentation vs execution split); **`TODO.md`** (PRD 33 doc line **done**; execution line **open**); **`edges.md`** (PRD 33 + implementation README + new decision/Q&A); **`decisions/`** / **`questions/`** / **`answers/`** / **`comments/`** — **`20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md`**, **`20260403_022544_QUESTION_prd33_traceability_location.md`**, **`20260403_022545_ANSWER_prd33_traceability_location.md`**, **`20260403_022546_COMMENT_cursor_prd33_version_doc_sync.md`**; **`THREAD_INDEX.md`** in each folder updated; **`README.md`** edges/stamp refreshed.
- **WHY:** Lock **normative gate text** for **4.1.0** / Softaculous direction; preserve **LILITH §13** lineage; keep **traceability** explicit (**§12** → **`TODO.md`** + implementation hub).
- **WHY NOT in this entry:** No claim here for **`validate_implementation.py`**, **`validate_lupopedia_headers_universal.py`**, **PRD 16/26/30/31** text changes, **PK** constitutional edits, or **install SQL** — document those only when a thread actually changes those paths.
- **Artifacts:** `decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md`, `questions/20260403_022544_QUESTION_prd33_traceability_location.md`, `answers/20260403_022545_ANSWER_prd33_traceability_location.md`, `comments/20260403_022546_COMMENT_cursor_prd33_version_doc_sync.md`.

# [2026-04-02] IDE facet packs + VS Code rule propagation (Cursor thread)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` 102), repo-wide docs + tooling; version-folder sync UTC **`20260402234551`**; artifacts **`20260402_234551`** … **`20260402_234554`**.
- **WHAT (thread-verified only):**
  - **`lupo-agents/`** — thin facet packs: `kiro/`, `windsurf/`, `warp/`, `cascade/`, `vscode-ide/`, `trae/` (`agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt`), each `extends_shared` → `_shared/ide_facet_base_system_prompt.txt`; propagation metadata (`rules_propagation_target` **vscode** for `vscode-ide`, **pending** for `warp` / `trae`).
  - **`lupo-actors/`** — hub **`README.md`** for **`100`**, **`101`**, **`104`**, **`105`**, **`106`**, **`107`** (links to registry, shared prompt, propagation or pending note).
  - **`lupo-scripts/propagate_agent_rules.php`** — valid target **`vscode`**; **`write_vscode_outputs()`** → **`.vscode/lupopedia/rules/`**, `lupopedia_rules.json`, `README.md` (does not overwrite root `.vscode/settings.json`).
  - **`lupo-database/lupopedia/actors/registry.json`** / **`actor_id/registry.json`** — facet entries including **`vscode-ide`** (`actor_id` **106**, `agent_id` **113`) and **`trae`** (`actor_id` **107**, `agent_id` **114**); **`agents`** map entries for IDE slugs (aligned in thread).
  - **`AGENTS.md`** — IDE faucet table (all listed facets + `agent_id` column); attribution notes for VS Code / Trae; `agents` map example updated.
  - **`lupo-docs/doctrine/AGENT_REGISTRY.md`** — actor rows **106/107**, propagation matrix **`vscode`**, **`lilith`**/**`lexa`** in valid-targets text; removed stale **zencoder** propagation row; capability matrix dedupe.
  - **`lupo-agents/_shared/README.md`** — facet pack table expanded.
  - **`lupo-scripts/validate_actor_identity.py`** — **`IDE_FAUCETS`** set: **`vscode-ide`**, **`trae`**, **`antigravity-ide`**; **`zencoder`** removed.
- **WHY:** One shared IDE veto/identity file; correct **`actor_id`** per product; VS Code consumers get a dedicated propagated rules tree.
- **WHY NOT in this entry:** No claim here for PRD 16/26/30/31 rewrites, `validate_implementation.py` / universal validator edits, new PK constitutional rule, or install SQL reconciliation — **not** performed in this thread.
- **Artifacts:** `decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md`, `questions/20260402_234552_QUESTION_ide_facet_version_doc_scope.md`, `answers/20260402_234553_ANSWER_ide_facet_version_doc_scope.md`, `comments/20260402_234554_COMMENT_cursor_ide_facet_documentation_pass.md`.
- **Lineage fix (UTC `20260402235141`):** QUESTION `234552` gained explicit **`lupopedia.edges`** **`has_answer`** → ANSWER `234553` (per sibling pattern `225224`/`225225`); ANSWER reverse edge uses relative `../questions/…` and links to decision `234551`; **`comments/20260402_235141_COMMENT_lilith_lineage_audit_question_234552.md`** records LILITH audit receipt (no standalone `edges/` artifact — in-header edges only).

# [2026-04-02] Cursor thread — version `4.0.94` doc sync (identity, temporal anchor, scope)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` 102), `lupo-docs/versions/4.0.94/`; version-folder header sync UTC **`20260402225416`** via `python lupo-bin/tick.py` (thread artifacts filenames **`20260402_225223`** / **`20260402_225226`**).
- **WHAT:** `CHANGELOG` / `PLAN` (Phase C rows restored + Phase E) / `TODO` / `edges` / `THREAD_INDEX` updates; new **DECISION** + **QUESTION** + **ANSWER** + **COMMENT** (thread-verified changelog scope only — **no** speculative PRD16/validator/PK template claims).
- **WHY:** Preserve accurate lineage; directive templates must not invent completed work.
- **Artifacts:** `decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md`, `questions/20260402_225224_QUESTION_version_doc_thread_scope.md`, `answers/20260402_225225_ANSWER_version_doc_thread_scope.md`, `comments/20260402_225226_COMMENT_cursor_thread_version_doc_sync.md`.
- **Cross-repo work summarized in decision:** `IDENTITY_LAYERS_DOCTRINE` §3, `AGENTS`/`ONBOARDING`, `UTC_TEMPORAL_ANCHOR_DOCTRINE`, PRD 00 §3.5a, `LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES` §2.4a, `TICK_PY_DOCTRINE`, `echo_anchor_utc.py`, root `README` thread manifest + temporal workflow, `.cursor/rules/TIMESTAMP_DOCTRINE.mdc`.

# [2026-04-04] LILITH audit — actor/agent documentation (registry authority, single §3 source)

- **`lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`:** New **§3** canonical actor/agent/facet/directory rules; facet **`actor_id`** model; **no** hardcoded `auth_user`; **registry.json** authority; **IdGenerator** / PRD 01 for generated ids; renumbered sections **4–8**; timestamps **`20260404220000`**; edge to `comments/20260404_220000_COMMENT_…`.
- **`AGENTS.md` / `ONBOARDING.md`:** Short summaries + link to §3 only; **LUPOPEDIA header first** on `ONBOARDING.md`; removed triple duplication and **auth_user 1000** / **0–2025 every-agent** claims.

# [2026-04-04] LILITH audit — version tree clarity (PRD 30/31 authority, Phase C evidence, session changelog)

- **`README.md`:** Version lineage block (`current_version`, `parent_version`, `child_version`, `superseded_by`, `is_deleted`); **Canonical authority** table for PRD 30/31 (canonical under `lupo-docs/prd/` vs working copy under `versions/4.0.94/prd/`); **Thread TYPE** tokens aligned to **PRD 17** (legacy `DIALOG`/`DIRECTIVE` not for new files); pointer to **`session_changelog/`**.
- **`PLAN.md`:** Same authority blurb; **Phase C** split into **C-FW-1..3** (shipped, evidence + UTC **20260402210000**) and **C-1..3** (rewrite/promotion with SHA-256 / approval completion evidence).
- **`TODO.md`:** **Phase C traceability** section; checklist lines map to **C-FW-*** and **C-1..3** with file paths + anchor UTC.
- **`edges.md`:** YAML `outbound_edges` use **repo-relative** paths (no leading `/`); edge to `session_changelog/README.md`.
- **`session_changelog/README.md`:** Convention for **`changelog_<actor_id>_<session_id>_<YYYYMMDD>_<HHIISS>.md`**; body fields **`start_timestamp_utc`** / **`end_timestamp_utc`** as BIGINT UTC; **`is_deleted`** on logs; aggregation by sort/query only.

# [2026-04-04] LILITH directive — 4.0.94 version documentation (5W1H)

- **`decisions/`:** APPROVED decision [20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_rgb.md](decisions/20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_rgb.md) — WHO/WHAT/WHERE/WHEN/WHY/HOW for PRD 17 alignment, channel layout, Mood RGB thread, root README → 4.0.94, archive `42/` link fixes.
- **`questions/` / `answers/`:** Root README “current version” pointer — resolved to **`4.0.94`** working vs **`4.0.93`** frozen.
- **`comments/`:** Receipt comment for LILITH directive scope limits (no speculative PRD 26/30/31 validator history in this pass).
- **`THREAD_INDEX.md`** (decisions, questions, answers, comments): populated; headers **`when_updated`** `20260404200000`.
- **`PLAN.md`**, **`TODO.md`**, **`edges.md`**, **`README.md`**: task status, coordination checkbox, documentation graph, stamps updated.

# [2026-04-02] Actor Authority Framework Implementation

- **`decisions/20260402_220000_DECISION_actor_authority_prd32.md`:** Decision to create comprehensive actor authority and agent roles framework.
- **`questions/20260402_215000_QUESTION_actor_authority.md`:** User question about actor authority and COUNTERMEASURE red team agent.
- **`answers/20260402_220000_ANSWER_actor_authority.md`:** Comprehensive answer with PRD 32 creation and framework implementation.
- **`lupo-docs/prd/32_actor_authority_agent_roles.md`:** PRD defining actor hierarchy, approval authority matrix, and red team agent roles.
- **`lupo-docs/ACTOR_AUTHORITY_QUICK_REFERENCE.md`:** Decision trees and approval chains for fast reference.
- **Framework features:** 4-tier actor hierarchy, COUNTERMEASURE red team agent (analysis only), approval chains, escalation procedures, agent interaction protocols.
- **Key decision:** COUNTERMEASURE can challenge but cannot approve; escalates through LILITH → LEXA/HEIMDALL → WOLFIE.

# [2026-04-02] Channel and Documentation Framework Implementation

- **`decisions/20260402_210000_DECISION_channel_docs_framework.md`:** Decision to implement comprehensive framework for channel usage and documentation clarity.
- **`questions/20260402_200000_QUESTION_channel_docs_clarity.md`:** User question about channel usage patterns and implementation folder guidelines.
- **`answers/20260402_210000_ANSWER_channel_docs_clarity.md`:** Comprehensive answer with complete framework implementation.
- **`lupo-docs/prd/30_channel_usage_patterns.md`:** PRD defining clear boundaries between channels (coordination) and lupo-docs (documentation).
- **`lupo-docs/prd/31_implementation_folder_guidelines.md`:** PRD for implementation folder scaffolding, question lifecycle, and decision logging.
- **`lupo-docs/CHANNEL_VS_DOCS_QUICK_REFERENCE.md`:** Decision tree and usage patterns for quick reference.
- **`lupo-docs/IMPLEMENTATION_FRAMEWORK_SUMMARY.md`:** Complete framework overview and implementation summary.
- **`lupo-scripts/scaffold_implementation.py`:** Automated implementation folder creation with UTF-8 encoding.
- **`lupo-scripts/validate_framework_compliance.py`:** Framework compliance validation tool.
- **Implementation folders:** Scaffolded `30_channel_usage_patterns/` and `31_implementation_folder_guidelines/` with complete structure.
- **Framework features:** 3-level question system (critical, optimization, clarification), cross-linking metadata, channel-docs synchronization, template usage.

# [2026-04-04] Channel `semantic` + thread `mood_rgb_system`

- **`lupo-channels/0/semantic/mood_rgb_system/`** — `THREAD_MANIFEST.md`, `README.md`, `decisions|questions|answers|comments/` + two APPROVED decisions (evidence sources / color definitions).
- **`lupo-channels/channel_index.md`:** Row for **semantic** (Semantic & Knowledge Systems).
- **`lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md`:** Summary doctrine; canonical thread + decisions; archive evidence edges live in the evidence decision file.

# [2026-04-04] Numeric channel path scan — fix broken `lupo-channels/42/` links

- Active tree has **no** `lupo-channels/42/` (numeric exemplars live under **`lupo-channels_before_4_0_93/42/`**).
- **`lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md`:** §1 clarification; §6–8 relative links retargeted to `lupo-channels_before_4_0_93/42/...` (**archive**).
- **`lupo-docs/implementations/25_.../THREAD_INDEX.md`**, **`26_.../DECISION_INDEX.md`:** “Related threads” links point at archive `42/threads/`.

# [2026-04-04] Phase A — `.cursorrules` §30 + channel path wording

- **`.cursorrules`:** New **§30 Channel filesystem paths** — active `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`, legacy numeric `.../{channel_id}/threads/{thread_id}/`, archive `lupo-channels_before_4_0_93/`, REST vs filesystem note.
- **`lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md`:** §1.1 human-readable documentation tree (parallel to numeric API-mirrored tree).
- **`lupo-rules/root/README.md`**, root **`README.md`**, **`AGENTS.md`:** Channel literacy and coordination bullets updated.
- **`lupo-docs/prd/02_channels_discussions.md`**, **`17_decisions_format.md`**, **`21_thread_graduation_doctrine.md`**, **`DOCUMENTATION_ARCHITECTURE.md`**, **`LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md`:** Dual path semantics.
- **`lupo-docs/implementations/25_.../THREAD_INDEX.md`**, **`26_.../DECISION_INDEX.md`:** Legacy vs active links.

# [2026-04-04] PRD 17 thread filenames, PRD 02/29 alignment, org thread + schema

- **`lupo-docs/prd/17_decisions_format.md`:** Authoritative **Thread filename pattern** (per-folder `TYPE`/`STATUS`, `HHIISS`, optional `YYYYMMDDHHIISS` prefix); validator and diagram updates.
- **`lupo-docs/prd/02_channels_discussions.md`**, **`lupo-docs/prd/29_project_structure.md`:** Cross-links to PRD 17; PRD 29 edge to PRD 17.
- **`lupo-docs/versions/4.0.93/README.md`:** Points to PRD 17 for full naming rules; decision example uses `DECISION_APPROVED_…`.
- **`lupo-channels/0/organization/prd_29_project_organization/`:** Cherry-pick review comment and thread indexes (PRD 29 coordination).
- **Schema / tooling:** `install_new_lupopedia.sql`, `add_thread_key_to_dialog_threads.sql`, JSON registry files, `generate_toon_files.py`, **`lupo-docs/doctrine/JSON_SCHEMA_REFERENCE_DOCTRINE.md`**.

# [2026-04-03] 4.0.93 TODO freeze cleanup + PRD 29 channel strategy

- **`lupo-docs/versions/4.0.93/TODO.md`:** Removed all open checkboxes; **Open Work → 4.0.94 Only** pointer; historical `[x]` completions retained.
- **`lupo-docs/versions/4.0.94/TODO.md`:** Merged deduplicated backlog from former 4.0.93 open items (installer, Softaculous, Glass, migration, tooling, etc.).
- **`lupo-docs/prd/29_project_structure.md`:** Channel filesystem strategy table (old archive vs new tree); coordination path `lupo-channels/0/organization/prd_29_project_organization/`.
- **`lupo-channels/channel_index.md`:** Added **organization** channel.
- **`lupo-channels/0/organization/prd_29_project_organization/`:** New thread scaffold (`README.md`, `decisions|questions|answers|comments/THREAD_INDEX.md`).

# [2026-04-02] Bump GLOBAL_CURRENT_LUPOPEDIA_VERSION to 4.0.94

- `lupo-config/global_atoms.yaml` and `lupo-includes/version.php` now report **4.0.94** for the working tree (after tag `v4.0.93`).

# [2026-04-02] Scaffold 4.0.94 version directory

- Added working version folder `lupo-docs/versions/4.0.94/` with `PLAN.md`, `TODO.md`, `CHANGELOG.md`, `edges.md`, `decisions/`, `questions/`, `answers/`, `comments/`, and `prd/`.
- PRD 30 working copy: `prd/30_prd_development_guide.md` (moved from `lupo-docs/prd/`).
- PRD 31 stub: `prd/31_context_system.md` for redesign after 4.0.93 rejection of parallel classification.

# Lupopedia 4.0.94 CHANGELOG

Further entries go below this line as work completes.
