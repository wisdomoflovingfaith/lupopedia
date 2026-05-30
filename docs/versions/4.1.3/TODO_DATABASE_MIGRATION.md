---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.3/TODO_DATABASE_MIGRATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.3/TODO_DATABASE_MIGRATION.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/database/canonical/1026/04/database-migration-plan.toon
  atoms_toon: null
  transcript_jsonl: 0/database/todo-database-migration
  artifact_type: documentation
  artifact_kind: guide
  channel_key: database
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: TODO -- Database migration plan (Crafty 3.7.5 to Lupopedia)
  summary: Persistent execution plan for import script alignment with install_new_lupopedia.sql; no migration product; blockers, doctrine violations, table mapping, ordered fixes.
---
# DATABASE MIGRATION PLAN -- CRAFTY SYNTAX -> LUPOPEDIA

**Scope:** `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` (dev import) vs `database/lupopedia/mysql/install/install_new_lupopedia.sql` (canonical DDL) vs `database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql` (legacy Crafty 3.7.5 schema).

**Non-goals for this document:** No `.sql` edits here; no Flyway/Liquibase/Laravel chains; canonical schema remains a single full install file per operator doctrine.

> **WARNING:** This import process is development-only, non-idempotent, and not safe for production use without full database reset.

---

## 1. CURRENT STATE ASSESSMENT

| Artifact | Role | Measured fact (repo, as of this plan) |
|----------|------|----------------------------------------|
| `old_crafty_syntax_3_7_5_start.sql` | Legacy Crafty 3.7.5 DDL | **34** `CREATE TABLE` statements (all `livehelp_*`). |
| `install_new_lupopedia.sql` | Canonical Lupopedia 4.0.x DDL | **142** `CREATE TABLE` statements (prefixed `{{prefix}}`). (Current measured count; updated from earlier 140.) |
| `import_from_old_crafty_syntax.sql` | One-time dev import | Header claims **199** "core Lupopedia tables" after migration; **233** = 34 legacy + 199 core. |

**Count mismatch:** **199** in the import header **does not match** the **142** tables in the current canonical install. The import overview is **stale** relative to `install_new_lupopedia.sql` (ejected tables, renamed scope, and schema shrink/grow since that comment was written). Note: import header was corrected from 199 to 142 by cascade (20260419); SQL is updated but plan docs reflect the correction here.

**Operational status:** **NOT SAFE TO RUN** the import against a database built only from current `install_new_lupopedia.sql` without repair, because:

- The script **INSERT**s into `{{prefix}}actor_filesystem` and `{{prefix}}actor_sync_state` (Phases 1b/1c and department hybrid phases), but **`install_new_lupopedia.sql` explicitly removed** those tables (`[EJECTED 4.1.2]` comments; no `CREATE TABLE` for them).
- **TRUNCATE** targets core Lupopedia tables (`dialog_messages`, `dialog_threads`, `visits`, `paths`, `referers`, `audit_log`, `actor_departments`, crafty integration tables, truth tables, etc.). Any partial or wrong-order run risks **data loss** on non-empty installs.
- **Vendor-specific and doctrine-risk constructs** are embedded in the import (see section 3). PostgreSQL portability and constitutional timestamp rules are not satisfied by the script as-is.

---

## 2. CRITICAL BLOCKERS (MUST FIX FIRST)

Each item: **Description** | **Impact** | **Required fix (plan level only)**

### B1. Missing tables: `actor_filesystem`, `actor_sync_state`

- **Description:** Import phases insert into `{{prefix}}actor_filesystem` and `{{prefix}}actor_sync_state`. Canonical install documents ejection at 4.1.2; those `CREATE TABLE` blocks are absent from `install_new_lupopedia.sql`.
- **Impact:** Import **fails at runtime** with "table does not exist", or (if someone reintroduces tables ad hoc) **schema fork** vs canonical install.
- **Required fix:** Choose one path in documentation and code **before** re-enabling import: (a) **permanently remove** import phases and move any needed path/state logic to **runtime services + existing columns**, or (b) **restore** tables in **canonical install first**, then align import -- only if product restores that design. Default recommendation aligned with install: **remove import phases** and update **PRD 13** + `ACTOR_*` doctrine notes accordingly.

### B2. TRUNCATE against canonical tables

- **Description:** Multiple `TRUNCATE {{prefix}}...` statements clear core data areas before repopulating from legacy sources.
- **Impact:** On anything except a **controlled empty-or-throwaway** Lupopedia DB, running the script **destroys** dialog, visits, paths, referers, audit, departments satellite data, etc.
- **Required fix:** Document **hard preconditions** (fresh install + explicit operator ack). Longer term: **Python importer** should use **explicit delete scope** or **transactional batch** per table group with dry-run counts, not blind TRUNCATE, unless Wolfie keeps TRUNCATE as a deliberate dev-only hammer.

### B3. `lupo_actors.auth_user_id` linkage for imported humans -- RESOLVED

See RESOLVED BLOCKERS section below. Fixed by cascade (20260419203000).

### B4. Stale "199 tables" / "233 total" narrative

- **Description:** Import header block asserts 199 core tables and repeatability/safety claims that contradict measured **142** `CREATE TABLE` in install and current operator doctrine (no migration product; import is dev-only). Note: import SQL header count was corrected to 142 by cascade (20260419); this blocker tracks PRD 13 prose alignment, not the SQL count itself.
- **Impact:** Agents and humans **mis-size** work, assume missing tables, or trust "safe repeatable" wording.
- **Required fix:** Replace overview counts with **scripted counts** from `install_new_lupopedia.sql` (maintained in PRD or a small validator doc) and mark old numbers **deprecated** in import comments when SQL is next edited (outside this file task).

### B5. Sections with ALTER / comments but no Lupopedia INSERT

- **Description:** `livehelp_operator_channels` block performs **ALTER on legacy tables** only; there is **no** `INSERT INTO {{prefix}}channels` in this file. `livehelp_config` maps to **UPDATE `{{prefix}}modules`** (expects pre-seeded row). Note: `livehelp_modules` INSERT was patched by cascade (20260419211500) and is no longer in this category.
- **Impact:** Import assumes **seed** or manual rows exist; **channels graph** may stay empty or default-only after "migration".
- **Required fix:** Add checklist step: either **extend import** with explicit INSERTs from legacy rows (with IdGenerator rules) or **document** that **seed SQL + wizard** own channels and import only touches integration tables. Align **PRD 13** with whichever is canonical.

### B6. `livehelp_modules_dep` explicitly not mapped

- **Description:** Comment instructs: do not map; dropped; no import; visibility is UI-driven.
- **Impact:** Acceptable if doctrine-approved; must stay **consistent** with runtime (no hidden expectation that per-department module toggles survived).
- **Required fix:** Confirm in **PRD 13** "NOT MIGRATED" row and parity testing on department module behavior post-import.

### RESOLVED BLOCKERS

**B3. `actors.auth_user_id` linkage for imported humans -- FIXED**

- **Fixed by:** cascade (20260419203000)
- **What was done:** `auth_user_id` column added to the `INSERT INTO {{prefix}}actors` statement for imported Crafty operators; imported operators now explicitly reference `auth_users.auth_user_id`.
- **Remaining:** PRD 13 prose update to reflect the fix is deferred to next SQL batch; `ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md` matrix update also deferred.

---

## 3. DOCTRINE VIOLATIONS

Constitutional / PRD 13 alignment issues observed **in the import script** (not necessarily in install DDL). For each: **why** | **correction approach (plan)**

| Issue | Why it violates | Correction approach |
|-------|-----------------|---------------------|
| **`JSON_OBJECT` / nested `JSON_*` in SQL** | Cross-dialect fragility; database-logic-prohibition spirit (heavy transform in SQL); conflicts with "portable SQL" goals in root rules. | Build JSON blobs in **Python** (or PHP CLI) from selected rows; INSERT plain `TEXT`/`JSON` columns via prepared app layer, or emit **CSV** intermediate. |
| **`ROW_NUMBER()` window function** | Not portable to engines without window support; vendor feature. | Replace with **deterministic ordering** in app code or session variable patterns documented per engine -- or accept MySQL-only **dev** script with explicit NON-GOAL flag in PRD 13. |
| **`ON DUPLICATE KEY UPDATE`** | MySQL-specific; relies on UNIQUE semantics. | Use **SELECT then UPDATE or INSERT** with explicit PKs (reserved-ID doctrine) in application importer; or document MySQL-only dev scope. |
| **`REGEXP` in predicates** | MySQL vendor regex. | Move predicate to **Python** normalization or use **application-side** filtering of hybrid actor names. |
| **`TRUNCATE`** | Destructive; not a "migration system" but still operational hazard. | Preconditions doc + eventual Python importer with **scoped deletes** or **full DB drop** policy. |
| **`UTC_TIMESTAMP()` / `DATE_FORMAT(UTC_TIMESTAMP(), ...)` for `created_ymdhis` / `updated_ymdhis`** | Database-generated clock surface; constitutional preference for **application-set** BIGINT UTC (see database-logic-prohibition / timestamp doctrine). | Importer passes **explicit BIGINT** from host clock at run start, or from **tick.py**-style anchor passed into script parameters. |
| **`FROM_UNIXTIME` for legacy last_login** | Reads legacy epoch **into** packed UTC -- acceptable as **read-side transform** if output is BIGINT UTC; still MySQL-specific. | Document as **legacy ingest only**; final importer on Python can use stdlib datetime UTC. |
| **`json` column types on `actors` in install** | PostgreSQL portability concern for canonical DDL (separate from import). | Track under install DDL review: `TEXT` + app validation vs typed `JSON` per engine. |

**PRD 13 / AGENTS / ONBOARDING:** This plan defers prose updates to **PRD 13** until SQL edit batches are authorized; required mentions: import is **dev-only**, **not rerunnable as production migration**, canonical count is **`install_new_lupopedia.sql`**, and **no FK/trigger** assumptions.

---

## 4. TABLE MAPPING (CRAFTY -> LUPOPEDIA)

Legacy table -> **Status** -> **Target / notes**

| Legacy table (`livehelp_*`) | Status | Target / notes |
|-----------------------------|--------|----------------|
| `livehelp_autoinvite` | **MAPPED (DEV IMPORT)** | `{{prefix}}crafty_syntax_auto_invite` |
| `livehelp_channels` | **NEEDS DECISION** | Comment says upgrade; **no** `INSERT INTO {{prefix}}channels` in file; may rely on seed/wizard |
| `livehelp_config` | **CONDITIONALLY MIGRATED (SEED DEPENDENT)** | `UPDATE {{prefix}}modules`; requires pre-seeded module row (id 1); cascade status: APPROVED WITH PRECONDITION |
| `livehelp_departments` | **MAPPED (DEV IMPORT)** | `{{prefix}}departments`, `{{prefix}}department_metadata` |
| `livehelp_emailque` | **NOT MIGRATED** | Comment: target out of scope |
| `livehelp_emails` | **MAPPED (DEV IMPORT)** | `{{prefix}}crm_lead_messages` |
| `livehelp_identity_daily` | **DROPPED** | No Lupopedia target |
| `livehelp_identity_monthly` | **DROPPED** | No Lupopedia target |
| `livehelp_keywords_daily` | **DROPPED** | No Lupopedia target |
| `livehelp_keywords_monthly` | **DROPPED** | No Lupopedia target |
| `livehelp_layerinvites` | **MAPPED (DEV IMPORT)** | `{{prefix}}crafty_syntax_layer_invites` |
| `livehelp_leads` | **MAPPED (DEV IMPORT)** | `{{prefix}}crm_leads` |
| `livehelp_leavemessage` | **MAPPED (DEV IMPORT)** | `{{prefix}}crafty_syntax_leave_message` |
| `livehelp_messages` | **NEEDS DECISION** | Comment: maps to `dialog_messages`; **no INSERT** in file; Crafty often empty |
| `livehelp_modules` | **PATCHED IN SQL** | Full INSERT mapping implemented by cascade (20260419211500); `livehelp_modules` -> `{{prefix}}modules` |
| `livehelp_modules_dep` | **NOT MIGRATED** | Explicit drop per comment; `{{prefix}}crafty_syntax_chat_mod_departments` used from other path |
| `livehelp_operator_channels` | **NEEDS DECISION** | **ALTER only**; no `INSERT` into `{{prefix}}channels` |
| `livehelp_operator_departments` | **MAPPED (DEV IMPORT)** | `{{prefix}}actor_departments` (with post-import UPDATE wiring) |
| `livehelp_operator_history` | **MAPPED (DEV IMPORT)** | `{{prefix}}audit_log` |
| `livehelp_paths_firsts` / `livehelp_paths_monthly` | **MAPPED (DEV IMPORT)** | `{{prefix}}paths` (script contains duplicate comment blocks; verify single coherent mapping in SQL review) |
| `livehelp_qa` | **MAPPED (DEV IMPORT -- SPLIT)** | `{{prefix}}truth_questions` / `{{prefix}}truth_answers` |
| `livehelp_questions` | **MAPPED (DEV IMPORT)** | `{{prefix}}crafty_syntax_chat_questions` |
| `livehelp_quick` | **MAPPED (DEV IMPORT)** | `{{prefix}}actor_reply_templates` |
| `livehelp_referers_daily` / `livehelp_referers_monthly` | **MAPPED (DEV IMPORT)** | `{{prefix}}referers` (per script comments) |
| `livehelp_sessions` | **DROPPED** | Per script comment |
| `livehelp_smilies` | **DROPPED** | Replaced by token system per table comment |
| `livehelp_transcripts` | **MAPPED (DEV IMPORT -- SPLIT)** | `{{prefix}}dialog_threads` + `{{prefix}}dialog_messages` |
| `livehelp_users` | **MAPPED (DEV IMPORT -- SPLIT)** | `{{prefix}}auth_users` + `{{prefix}}actors` (auth_user_id linkage repaired by cascade 20260419203000) |
| `livehelp_visits_daily` / `livehelp_visits_monthly` / `livehelp_visit_track` | **MAPPED (DEV IMPORT)** | `{{prefix}}visits` |
| `livehelp_websites` | **MAPPED (DEV IMPORT)** | `{{prefix}}federation_nodes` |

**Additional mapping (non-`livehelp_` name in script):** `livehelp_operator_departments` / `livehelp_users` drive **`lupo_crafty_user_mapping`** style data indirectly via actors and departments; confirm **`lupo_crafty_user_mapping`** population in a later SQL audit (not expanded here to avoid guessing).

---

## 5. REQUIRED FIX PLAN (ORDERED)

Atomic, actionable steps for future **`channel_key: database`** work. **No SQL in this list.**

1. **Freeze counting:** Run automated `CREATE TABLE` count on `install_new_lupopedia.sql`; publish **142** as the official core table count in **PRD 13** and in the import header comment when SQL is next edited. (Import SQL header was corrected from 199 to 142 by cascade 20260419; PRD 13 prose update still pending.)
2. **Resolve ejected tables:** Decision record: `actor_filesystem` / `actor_sync_state` **stay absent** from canonical install. Plan import phase **removal** or guard behind **feature flag** removed before ship.
3. **Fix actor linkage -- RESOLVED:** `auth_user_id` column added to `INSERT INTO {{prefix}}actors` by cascade (20260419203000). Remaining: PRD 13 + `ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md` prose update deferred to next SQL batch.
4. **TRUNCATE policy:** Write operator checklist: **only** empty throwaway DB or **full drop** of Lupopedia prefix; document **order dependency** (truncate before insert per foreign-free but logical FK order).
5. **Complete or defer channels:** `livehelp_modules` patched by cascade (20260419211500). Remaining: either add INSERT plans from `livehelp_operator_channels` or document that **seed SQL + wizard** own channels. `livehelp_config` approved with precondition (seed row required).
6. **Vendor SQL elimination (phased):** Phase A: catalog every `JSON_OBJECT`, `ROW_NUMBER`, `ON DUPLICATE KEY`, `REGEXP`. Phase Z: move transforms to **Python importer** per table group. Phase C: leave a **minimal MySQL-only** dev script only if explicitly approved.
7. **Timestamp source:** Replace DB clock calls in import design with **single run anchor** passed from operator tooling (packed UTC BIGINT).
8. **Truth / dialog / analytics validation:** After any import run, define **row-count spot checks** and **sample joins** (`auth_users` to `actors`, `actor_departments` to `departments`, transcript thread counts).
9. **TOON and table docs:** Regenerate or hand-update TOON exports from install after DDL changes; update **`docs/database/lupopedia/tables/`** companions per **PRD 13** implementer obligation.
10. **Remove `schema_migrations` confusion (optional):** If the table remains in install, document that it is **not** Flyway -- name collision audit for humans only.

---

## 6. EXECUTION STRATEGY

**Final import path (target state):**

- **Primary:** **Fresh install** from `install_new_lupopedia.sql` + **seed** where required, then **one-shot** Crafty data ingest via **Python** (replacement for long SQL transforms).
- **Interim:** The existing `.sql` import remains a **dev-only** tool; treat as **NOT idempotent** until TRUNCATE + INSERT pairs are replaced or wrapped with guards.

**Preconditions:**

- Legacy **34** tables loaded from `old_crafty_syntax_3_7_5_start.sql` (or equivalent dump) into the same server.
- Lupopedia schema from **`install_new_lupopedia.sql`** applied with known prefix.
- **Seed rows** present where import uses `UPDATE` (e.g. `modules` id **1**) or extend import with INSERT.

**Post-run validation (minimum):**

- Row counts: `auth_users`, `actors`, `actor_departments`, `dialog_threads`, `dialog_messages`, `visits`, `paths`, `referers`.
- Spot checks: every imported **`actors.actor_id`** in human range has matching **`auth_users`** row; **`auth_user_id`** column set if policy requires.
- Doctrine: **no new FKs/triggers**; timestamps are **BIGINT** packed UTC; soft-delete columns present.

---

## 7. OPEN QUESTIONS

- Should **`actor_filesystem` / `actor_sync_state`** ever return to canonical install, or is **runtime-only** path resolution final?
- Should **`json`** columns on `actors` (install DDL) become **`TEXT`** for Postgres parity, or is MySQL-only acceptable until 4.1.0+?
- **`livehelp_messages`**: import empty data only, or **skip** entirely?
- **Non-operator users:** second `auth_users` INSERT exists; do **all** users get **`actors`** rows, or only operators + admins?
- **Analytics fidelity:** full preservation of paths/referers/visits vs **sampling** for large sites?
- **Transcript fidelity:** acceptable lossiness when Crafty granularity does not match `dialog_threads` model?
- **`schema_migrations` table** in install: keep, rename, or remove to avoid confusion with migration products?
