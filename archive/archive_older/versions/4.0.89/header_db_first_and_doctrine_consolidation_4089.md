---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: report
  when_updated: "20260329210000"
  file_path_from_root: "docs/versions/4.0.89/HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.89/HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: report
  artifact_kind: version_specific
  thread_id: "4-0-89-header-db-first"
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
# Header DB-first pipeline and doctrine consolidation — 4.0.89

**Status:** Integration note for work landed **2026-03-28** (WOLFIE directive → HEPHAESTUS / Cursor implementation + doc consolidation).  
**Does not supersede** earlier 4.0.89 artifacts authored by **WOLFIE**, **THOTH**, **ATHENA**, or **LILITH** (README, PLAN, TODO, CHANGELOG, clarity review). This file **adds** a cross-actor summary and pointers.

**Release alignment (2026-03-28):** Version **4.0.89** is **scoped to ship** the **LUPOPEDIA HEADERS** pipeline (validation Python/PHP, import/regenerate, `*` + IDE rules, header-related DB, release-gate tests). Non-header product work is **deferred** to **`docs/versions/4.0.90/`**. **2026-03-29:** PHP scripts mirror Python; version **[README.md](README.md)** keeps a short PHP command reference — full agent matrix lives in **[TODO.md](TODO.md) H8** only (avoid duplicating tables). This consolidation doc remains **central** to what 4.0.89 must prove before tag.

---

## 1. What was already true (preserve prior actor claims)

From **`CHANGELOG.md`** and **`rules/root`** work (WOLFIE et al., earlier in the cycle):

- **LUPOPEDIA HEADERS doctrine** established: schema taxonomy, federation rules, validators (`validate_lupopedia_headers_universal.py`), rule-file requirements.
- **`content_id`** called out in changelog purpose and next steps (validate integration in import scripts).
- **Documentation clarity** threads (LILITH review, WOLFIE/THOTH reports) identified **tooling gaps** and **unclear DB ↔ file sync** (see **`LUPOPEDIA_DOCUMENTATION_CLARITY_REVIEW.md`** sections 4–5).

None of that is removed; this document describes **additional** implementation and doc structure that **addresses** part of those gaps.

---

## 2. WOLFIE directive (database-first)

**Intent:** Database is authoritative for imported artifacts. Flow: **import file → `lupo_contents` + `lupo_metadata` + `lupo_edges` + optional `revision_history`** → **regenerate YAML from DB**. Validators **warn** when `content_id` is missing on disk.

---

## 3. Implementation summary (scripts)

| Script | Role |
|--------|------|
| `scripts/import_content.py` | Upsert `lupo_contents`; then calls **`sync_header_artifact_to_db`** from `header_db_sync.py` (sync is **not** reimplemented inside `import_content.py`) |
| `scripts/lib/header_db_sync.py` | **`sync_header_artifact_to_db`** (file snapshot → DB) and **`build_yaml_data_from_db`** (DB → YAML); metadata (`lupopedia_header_sync`), header edges (`edge_category=lupopedia_header`), `revision_history` when `lupopedia.history` present on import |
| `includes/classes/HeaderDbSync.php` | **PHP** mirror of `header_db_sync.py` + deterministic `content_id` (SHA-256 / BIGINT fit); used by `import_content.php`, `generate_headers_from_db.php`, `validate_lupopedia_headers.php` |
| `scripts/import_content.php` | **PHP** import (same upsert + sync semantics as `import_content.py`; **default** does not modify the file — use **`--write-back`** to set `content_id` in YAML) |
| `scripts/generate_headers_from_db.php` | **PHP** YAML regeneration from DB |
| `scripts/validate_lupopedia_headers.php` | **PHP** header validation + optional `--check-db` |
| `scripts/generate_headers_from_db.py` | Default **live MySQL** rebuild via **`build_yaml_data_from_db`**; `--use-mock-db` stub only |
| `scripts/ensure_imported.py` | Run import if `content_id` absent |
| `scripts/validate_lupopedia_headers.py` | Warns on missing `content_id` (ASCII-safe on Windows); optional **`--check-db`** warns if file has `outbound_edges` or `lupopedia.history` but MySQL has no matching rows for that `content_id` |
| `scripts/validate_lupopedia_headers_universal.py` | Doctrine-aligned **required** `lupopedia.headers` fields, **`thread_id`** pattern, UTC pair **`when_updated` / `last_modified_utc`**, optional **`--check-db`** (same drift idea when `content_id` set), **`outbound_edges`** on-disk checks from **repo root**, **`lupopedia.history`** on parsed YAML |
| `scripts/lib/header_validation.py` | Shared validation; `warnings` include `content_id` |

**Schema mirrors for column lists:** `database/lupopedia/json/*.json` (e.g. `lupo_contents.json`). **DDL authority:** `database/lupopedia/mysql/install/install_new_lupopedia.sql`.

### 3.1 `lupo_edges` column names (avoid wrong SQL)

Install SQL / JSON mirrors define **`edge_type`**, **`edge_category`**, **`semantic_weight`**, **`weight_score`**, **`flare_reason`**, etc. There are **no** columns named `weight` or `reason`. YAML `outbound_edges[].weight` / `.reason` map to those fields on import; **`build_yaml_data_from_db`** maps them back for regeneration. Full table is in **`rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`** (*Database-first mapping* section).

### 3.2 `revision_history` — database side of the running log

| Topic | Behavior |
|-------|-----------|
| **Column** | `lupo_contents.revision_history` — JSON array of history events (same shape as YAML `lupopedia.history` list items). |
| **Import** | If parsed front matter **includes** the key `lupopedia.history`, **`sync_header_artifact_to_db`** serializes it into **`revision_history`** for that row’s `content_id`. If the key is **absent**, the column is **not** cleared (prior DB value kept). |
| **Regenerate** | **`build_yaml_data_from_db`** reads non-empty **`revision_history`** and emits **`lupopedia.history`** in the rebuilt YAML. |
| **Doctrine** | Append-only events, `event_id` sequence, actor + UTC — see root binding doctrine **Dual running log — file and database**. |
| **4.0.89 exit** | **`TODO.md` H7** — prove round-trip on at least one imported artifact and record under **Release verification** in **`CHANGELOG.md`** (or channel 42). |

---

## 4. Doctrine single source of truth (no duplicate binding text)

| Path | Role |
|------|------|
| **`rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`** | **Only** binding doctrine (field matrix, validation, **database-first mapping** for `lupo_contents` JSON columns import does / does not touch). |
| **`docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md`** | **Alias + tooling graph** (`outbound_edges` to scripts and companion docs). **Do not** edit for rule changes — edit the root file. |

**Also updated:** repo `README.md`, `rules/root/README.md`, `docs/doctrine/LUPOPEDIA_HEADERS/README.md`, `VALIDATORS_AND_TOOLING.md`, `docs/doctrine/INDEX.md` — each states the split clearly.

**Root doctrine YAML** `lupopedia.edges` expanded to reference the same import/validate stack and companion docs (alongside existing IdGenerator / RULE_FILES edges).

---

## 5. Relation to documentation clarity review

**`LUPOPEDIA_DOCUMENTATION_CLARITY_REVIEW.md`** §4 (tooling gaps) and §5 (sync process): partially addressed by naming scripts, documenting import vs regenerate, and stating DB authority after import. **Remaining:** broader automation, FLARE cleanup in legacy READMEs, enforcement dates — still tracked in **TODO.md** / **PLAN.md** Phase 5.

**`PLAN.md` (same cycle):** **LILITH** retrospective documents execution order vs ATHENA’s phased plan and sets **4.0.90** expectations (living plan file, dependency ordering). Orthogonal to header DB-first work but part of the same version-folder narrative (`CHANGELOG.md` Major Changes §7).

---

## 6. Files touched (reference checklist)

- `rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md` — SST + DB mapping section + edges
- `docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md` — alias + edges
- `docs/doctrine/LUPOPEDIA_HEADERS/README.md`, `VALIDATORS_AND_TOOLING.md` (`--check-db` documented)
- `docs/doctrine/INDEX.md`
- `README.md`, `rules/root/README.md`
- `scripts/import_content.py`, `generate_headers_from_db.py`, `ensure_imported.py`, `validate_lupopedia_headers.py`, `validate_lupopedia_headers_universal.py`, `lib/header_db_sync.py`, `lib/header_validation.py`
- `scripts/import_content.php`, `generate_headers_from_db.php`, `validate_lupopedia_headers.php`; `includes/classes/HeaderDbSync.php`, `AgentFileWriter.php`; `PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`
- **This file** — version-folder integration narrative
- **`PLAN.md`** — LILITH retrospective + 4.0.90 planning guidance (see §7 in `CHANGELOG.md`)

---

## 6.1 PHP import path + PHP agent writes (pointers only)

**Dual toolchain:** **`scripts/import_content.php`**, **`generate_headers_from_db.php`**, **`validate_lupopedia_headers.php`** and **`includes/classes/HeaderDbSync.php`** mirror the Python pipeline for shared hosting — full matrix in **`TODO.md` H8**.

**Filesystem:** **`includes/classes/AgentFileWriter.php`** enforces **PHP agent** write policy (**`TODO.md` H9**); **IDE agents** are not gated by it. Deployment options: **`PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`**. Doctrine summary: **`docs/ORGANIZATION.md` §2.2**.

---

**Cursor (actor_id 102)** — consolidation note for 4.0.89. Prior content in this folder remains authoritative for its stated actors unless explicitly superseded in a **newer** dated artifact.
