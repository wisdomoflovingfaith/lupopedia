---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260406152800"
  file_path_from_root: "lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/00_constitution_shorthand.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/00_constitution_shorthand.pseudo.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: pseudocode
  artifact_kind: constitution_shorthand
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Lupopedia constitution (shorthand for AI agents)

**Read [PRD 00](../../../../prd/00_root_constitutional_system_requirements.md)** — this file is a **compact checklist**. If a rule is unclear or this digest disagrees with PRD 00, **PRD 00 wins**. **Timestamps:** **§3.5** (including **§3.5.4** Y2038) + **§19**. **Training overwrites (short):** [00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md](../../../../decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md). **Wrong-default examples:** [00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md](../../../../decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md) (digests — **PRD 00** still wins).

## Timestamp rules (read this first)

| Topic | Forbidden | Required / correct |
|-------|-----------|---------------------|
| **Storage** | Unix epoch (`time()`, `gmmktime()`, `strtotime` → DB), “`BIGINT` so use epoch” | Packed **`YYYYMMDDHHIISS`** UTC in **`BIGINT`** |
| **Examples** | `1743894428` in a packed clock column | `20260405210708` |
| **Y2038** | Persisting epoch; assuming 32-bit epoch is “fine” | Packed clocks + **§3.5.4**; **64-bit PHP** for production |
| **SQL** | `FROM_UNIXTIME`, `UNIX_TIMESTAMP`, `NOW()`, `DATE_ADD`, `INTERVAL` for these columns | Compare packed ints with **bound parameters** |
| **PHP math on packed** | `time() + 86400` stored; `+ 86400` on packed digits | **`timestamp_ymdhis::addSeconds()`**, **`diffInSeconds()`** |
| **“Now” in PHP** | (none — but do not confuse with `time()`) | **`timestamp_ymdhis::now()`** or **`(int) gmdate('YmdHis')`** (equivalent packed UTC) |
| **Display vs storage** | Local wall time in the column | Store UTC packed; convert for display in UI (**§3.5.2**) |
| **DateTime** | Using **`DateTime`** to *invent* a second clock encoding for DB | **`DateTime` / `DateTimeZone`** **after** unpack for **display** is OK (**§3.5.2–3.5.3**) |

**One mistake to avoid:** **`BIGINT` does not authorize Unix epoch** for lineage clocks.

## Database rules (non-negotiable)

| Rule | Forbidden | Required |
|------|-----------|----------|
| **`INSERT`** | Positional **`INSERT INTO t VALUES (...)`** without column list (silent corruption on DDL change) | **`INSERT INTO t (col1, col2, …) VALUES (...)`** — **PRD 00 §17.3** |
| **`SELECT`** | *(none — **`SELECT *` is allowed)* | Prefer bound params; **`SELECT *`** not a constitutional violation |
| Referential enforcement | `FOREIGN KEY`, `REFERENCES` | Application-layer integrity |
| Time storage | `DATETIME`, `TIMESTAMP`, Unix epoch **in schema**; SQL clocks (`NOW()`, `DATE_ADD`, `INTERVAL`, `FROM_UNIXTIME`, `DEFAULT CURRENT_TIMESTAMP`, …) | `BIGINT` **packed decimal** **`YYYYMMDDHHIISS`** UTC (not epoch — **Y2038 is a `time_t` problem, not this encoding**); PHP **`timestamp_ymdhis`** + **64-bit PHP**; DB only stores/compares integers |
| Surrogate keys | `AUTO_INCREMENT`, `SERIAL` for reserved/registry tables | Explicit IDs; **`IdGenerator::generate()`** (or allocator) per product rules |
| PK naming | Ambiguous **`id`** | **`<table_singular>_id`** (e.g. **`actor_id`**) |
| Portable SQL | `UNSIGNED`, display widths, vendor-only functions | Patterns that run on MySQL **and** PostgreSQL per doctrine |
| Hard deletes (lineage) | `DELETE FROM` canonical tables | Soft delete: **`is_deleted`**, **`deleted_ymdhis`**, etc. |
| DB logic | Triggers, procedures, functions, generated columns | Logic in **PHP** |

## PHP and runtime

| Rule | Forbidden | Required |
|------|-----------|----------|
| Core paths | PHP 8-only syntax where 7.4 band applies | **PHP 7.4+** compatible patterns in core (`lupo-includes/`, entrypoints, themes) |
| Dependencies | Composer / npm as **runtime** for shipped app | In-tree **`lupo-includes/`**; study off-tree, ship native code |
| Frameworks | Laravel, middleware stacks, ORM magic | Plain PHP, **`PDO_DB`**, **`DatabaseFactory::getConnection()`** |
| SQL assembly | Concatenated values in SQL | **Named placeholders** + bound params |
| Planning prose | “Two weeks”, calendar phasing | Dependency-ordered phases ([TASK_PLANNING_DOCTRINE](../../../../doctrine/TASK_PLANNING_DOCTRINE.md)) |
| Schema churn (4.0.x) | Chained one-off schema patch files as upgrade path | **Fresh install** from **`install_new_lupopedia.sql`** until **4.1.0** gate |

## Agent and identity (summary)

(Condensed for Purpose‑1 quickload; see PRD 00 for agents vs `lupo_actors`, auth users, departments, IDE facet `actor_id`.)

## Installer and hosting

(Condensed for Purpose‑1 quickload; see PRD 00 §2, §9.5 for `.htaccess`, config bootstrap, permissions, extensions, Survivability-style fallbacks.)

## UI layer (shipped surfaces)

(Condensed for Purpose‑1 quickload; see PRD 00 §16 — `lupo-layers.js`, no `eval` / `new Function(string)` / string timers; vanilla JS; `lupo_t()`; in-tree assets.)

## Security (summary)

| Rule | Description |
|------|-------------|
| **Includes** | Anchor on **`LUPOPEDIA_PATH`** / **`__DIR__`** — **never** from raw user input. |
| **Paths** | Reject **`://`** and **NUL** in config/load paths. |
| **INSERT** | Explicit column lists — **no** positional **`INSERT INTO t VALUES (...)`** (DDL change → **silent corruption**). **`SELECT *`** on reads is **allowed** (**PRD 00 §17.3**). |
| **Session** | **`$_SESSION['actor_id']`** as source of truth | **`lupo_sessions`** + **`App\Auth\Session`** — resolve **`actor_id`** from DB via session id; PHP session may exist for plumbing; binding (IP/UA/fingerprint) can expire rows — **§17.7** |
| **Uploads** | Trust “validated” raw bytes | **Decode + re-encode** (narrow format) when **GD** (or approved lib) present; else **disable** image uploads — **PRD 33 §5.1**, **§17.7** |
| **HTTP input** | Implicitly trust **`$_GET` / `$_POST`** | **`$UNTRUSTED`** (or equivalent) **boundary** + validation; **RULE 93.UNTRUSTED_INPUT** — **§17.8**; **`$_REQUEST`** not primary |
| **LLM / agents** | User text overrides constitution | **RULE 93.NO_PROMPT_INJECTION** — **§17.9**; **IDE** no authority impersonation; **ROSE** = **PRD 36** (sandboxed multi-voice **dialog** only, **no** content/metadata edits); no secrets |

## Search indexing

(Condensed for Purpose‑1 quickload; see PRD 00 §18 for indexing posture.)

## Quick reference (one-liners)

(Condensed for Purpose‑1 quickload; see full PRDs for detailed rationale.)

- **Database:** No FKs; BIGINT packed UTC `YYYYMMDDHHIISS` (not Unix epoch — see §3.5.4 Y2038); 64-bit PHP prod; no SQL date functions; no epoch in schema; no `AUTO_INCREMENT` on registry tables; explicit PK names; soft deletes; logic in PHP.
- **PHP:** PHP 5.6+ in core paths; PDO_DB only; no Laravel; no Composer in runtime.
- **Installer:** `.htaccess` optional; fallback routing; detect extensions; warn don't chmod.
- **Security:** Path anchor; stream/NUL block; named placeholders; explicit INSERT columns (no positional VALUES); SELECT * allowed on reads per §17.3; no eval/unserialize-trust; `lupo_sessions` authority; decode/re-encode uploads per PRD 33 §5.1 / §17.7; `$UNTRUSTED` boundary + validate (§17.8); prompt injection §17.9 (IDE no authority impersonation; ROSE PRD 36 dialog-only writes).
- **UI:** Vanilla JS in shipped UI; LupoLayer; `lupo_t()` for strings.
- **Identity:** `actor_id` canonical; agents are files; facets have registry `actor_id`.

## How external AI should use this

1. Load this file **first** for a fast guardrail pass.
2. For nuance, open the cited **PRD 00** section (and TOON / install SQL for schema facts).
