---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "directive"
  file_path_from_root: "lupo-channels/42/threads/1043/20260321_170000_wolfie_upgrade_validation_loop_crafty_to_lupo_4_0_85.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1043/20260321_170000_wolfie_upgrade_validation_loop_crafty_to_lupo_4_0_85.md"
  last_modified_utc: "20260321"
  channel_id: 42
  thread_id: 1043
  parent_thread_id: 1039
  root_thread_id: 1039
  lineage_depth: 1
  thread_role: "child"
  task_id: "task_upgrade_crafty_to_lupo_4_0_85_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "directive"
  artifact_kind: "decision_record"
  purpose: "Canonical repeatable upgrade validation loop for Crafty Syntax 3.7.5 → Lupopedia 4.0.85. Defines drop-install-validate cycle, execution actors, and finding feed-back protocol."
  tags: ["thread1043", "upgrade", "crafty_syntax", "validation_loop", "hephaestus", "thoth", "4.0.85"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1039/20260321_115034_wolfie_release_execution_and_rollover.md", type: "child_of", weight: 1.0, reason: "Thread 1043 is a direct child of Thread 1039 release execution." }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "validates", weight: 1.0, reason: "Install SQL is the primary artifact under validation in every loop iteration." }
    - { to: "lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql", type: "validates", weight: 1.0, reason: "Upgrade mapping SQL is the secondary artifact under validation." }
    - { to: "lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql", type: "requires", weight: 0.95, reason: "Crafty 3.7.5 baseline SQL must be present for step 2 of every loop iteration." }
    - { to: "install.php", type: "validates", weight: 0.95, reason: "Install wizard execution is step 3 of every loop iteration." }
    - { to: "lupo-channels/42/threads/1032/", type: "feeds_back_into", weight: 0.9, reason: "Schema failures found in validation loop feed into Thread 1032 schema authority." }
    - { to: "TODO.md", type: "updates", weight: 0.9, reason: "Each loop iteration generates findings that become tasks in root TODO." }
    - { to: "plan.md", type: "updates", weight: 0.9, reason: "Iteration outcomes update execution plan where schema or UI work is needed." }

lupopedia.footer:
  last_verified: "20260321"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  execution_actor: "hephaestus"
  validation_actor: "thoth"
  next_action:
    - "HEPHAESTUS executes loop iteration 1 (drop → install-crafty → wizard → validate)."
    - "THOTH records iteration 1 findings as a findings artifact in this thread."
    - "WOLFIE triages iteration 1 findings into tasks and assigns fix owners."
    - "Repeat loop after each fix batch until zero failures are recorded."
---

# file: WOLFIE Directive — Upgrade Validation Loop (Thread 1043, child of 1039) — delegation: wolfie:root

# Thread 1043 — Canonical Upgrade Validation Loop
## Crafty Syntax 3.7.5 → Lupopedia 4.0.85

**Thread:** Channel 42, Thread 1043 (child of Thread 1039)
**Actor:** WOLFIE (actor_id 1)
**Task ID:** task_upgrade_crafty_to_lupo_4_0_85_001
**Date:** 2026-03-21
**UTC Reference:** Mar 21, 2026, 17:00:00 UTC
**Execution:** HEPHAESTUS (actor_id 8)
**Validation:** THOTH (actor_id 26)

---

## Broadcast Payload

- Speaker: WOLFIE
- Target: @hephaestus @thoth @everyone
- Message: Thread 1043 created as a child of Thread 1039. This is the canonical, repeatable upgrade validation loop for 4.0.85. HEPHAESTUS owns DB and install execution. THOTH owns findings documentation. Every iteration produces a findings artifact in this thread. Loop repeats until zero failures.
- Mood Vector: 2A4A8A

---

## Purpose

This thread is the **canonical, repeatable upgrade validation loop** for Lupopedia 4.0.85.

The upgrade loop runs against the only supported upgrade path:

> **Crafty Syntax 3.7.5 → Lupopedia 4.0.85**

This loop is **not a one-time event**. It runs after each schema change, code fix, or doctrine update that touches the install or upgrade path. Every loop iteration produces:

- A **findings artifact** (in this thread) recording pass/fail state
- A **task batch** (in root TODO.md) for each failure found
- A **thread reference** back to the schema or code fix location

The loop terminates only when a full iteration produces **zero failures**.

---

## Upgrade Loop — Canonical Steps

### Step 1 — Drop All Lupopedia Tables

Drop all tables that belong to the Lupopedia schema. The table prefix is `lupo_` (defined by `LUPO_TABLE_PREFIX`).

**Execution:**
```sql
-- Drop all lupo_ tables from the schema.
-- Use the canonical table list from install_new_lupopedia.sql.
-- Do not manually guess which tables exist — derive from install SQL.
```

**Verification:** `SHOW TABLES LIKE 'lupo_%'` returns zero rows.

**Failure protocol:** If any `lupo_` table survives the drop, stop iteration and record the surviving table list in the findings artifact.

---

### Step 2 — Install Crafty Syntax 3.7.5 Baseline

Load the Crafty Syntax 3.7.5 legacy tables from the canonical start file:

```
lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql
```

This installs the **34 original Crafty Syntax tables** (prefixed `chat_` or as defined in the import SQL).

**Verification:** Confirm each Crafty baseline table exists before proceeding.

**Failure protocol:** If any baseline table is missing, record the gap and stop — the import SQL may need correction.

---

### Step 3 — Run the Lupopedia Install / Upgrade Wizard

Execute the install wizard at `install.php`.

The wizard must:
1. Detect the Crafty 3.7.5 environment.
2. Run `install_new_lupopedia.sql` to create all `lupo_` schema tables.
3. Run `import_from_old_crafty_syntax.sql` to map legacy Crafty data into Lupopedia tables.
4. Seed the database using the canonical seed files under `lupo-database/lupopedia/mysql/seed/`.
5. Complete without PHP fatal errors.

**Verification check:** After wizard completes, confirm:
- All tables listed in `install_new_lupopedia.sql` exist.
- Canonical seed rows are present (actors, channels, system defaults).
- No PHP fatal errors appear in the wizard output.

**Failure protocol:** Record the exact error, the file and line, and the table or seed file involved.

---

### Step 4 — Log In to the System

Navigate to the Lupopedia login page and authenticate with a seeded human actor credential.

**Verification:** Session is established. Auth service resolves the actor identity. Redirected to the authenticated home/dashboard without errors.

**Failure protocol:** Record the exact HTTP response, PHP error, and session/auth service failure details.

---

### Step 5 — Validate Post-Install State

Run each check below. For each check, record **pass** or **fail** in the findings artifact.

#### 5a — Channel 42 Exists

```sql
SELECT channel_id, channel_name FROM lupo_channels WHERE channel_id = 42 AND is_deleted = 0;
```

Expected: one row returned, `channel_name` is non-null.

#### 5b — Thread Loading

```sql
SELECT COUNT(*) FROM lupo_dialog_threads WHERE channel_id = 42 AND is_deleted = 0;
```

Expected: at least the canonical placeholder/seed thread exists and the query executes without error.

#### 5c — Task Visibility

```sql
SELECT COUNT(*) FROM lupo_tasks WHERE is_deleted = 0;
```

Expected: query executes without error. Count may be zero on a fresh install — that is acceptable. A PHP fatal error is not.

#### 5d — Actor Identity Resolution

```sql
SELECT actor_id, name, actor_type FROM lupo_actors WHERE actor_id IN (0, 1, 2) AND is_deleted = 0;
```

Expected: system actor (0) and WOLFIE (1) are present. LILITH (2) is present if seeded. Actor names are non-null.

#### 5e — Headers ↔ DB Mapping

Verify that the `lupo_metadata` and `lupo_contents` tables exist and accept a test row:

```sql
SELECT COUNT(*) FROM lupo_metadata WHERE is_deleted = 0;
SELECT COUNT(*) FROM lupo_contents WHERE is_deleted = 0;
```

Expected: both queries execute without error. If either table does not exist, this is a schema failure.

---

### Step 6 — Record Findings

THOTH produces a findings artifact in Thread 1043 for each iteration, following this structure:

**Findings artifact filename format:**
```
YYYYMMDD_HHMMSS_thoth_iteration_N_findings.md
```

where `N` is the iteration number (1, 2, 3, …).

**Findings artifact required fields:**

| Field | Description |
|---|---|
| iteration | Integer iteration number |
| run_date_utc | UTC timestamp in YYYYMMDD_HHMMSS format |
| step_results | Pass/fail for each step (1–5e) |
| failures | List of failures with: step, description, error text, affected file or table |
| schema_gaps | Tables missing from install SQL, wrong column types, wrong nullability |
| code_gaps | PHP errors, fatal crashes, unexpected redirects |
| ui_gaps | Pages not rendering, wrong routes, missing views |
| tasks_generated | List of task_ids created in TODO.md for this iteration's failures |
| feed_back_threads | Thread references (Thread 1032, etc.) for schema fixes |
| iteration_verdict | PASS (zero failures) or FAIL (one or more failures) |

---

### Step 7 — Feed Findings Back

Every failure in a findings artifact must produce:

1. **A task entry** in `TODO.md` with:
   - `task_id` unique to the iteration and failure
   - owner actor
   - thread reference (1043 + fix thread if schema is involved)
   - fix location (file name, table name)

2. **A thread reference** to the appropriate fix thread:
   - Schema and DDL issues → Thread 1032
   - Install PHP code issues → new implementation sub-thread under 1043 if complex
   - Routing or UI issues → appropriate visibility or routing thread

3. **A plan.md update** if the failure reveals a workstream gap that is not already tracked.

---

## Repeatable Execution Protocol

This loop is **repeatable by design**. The execution sequence for each additional iteration is:

1. Apply the batch of fixes from the previous iteration's task list.
2. Re-run Step 1 (Drop).
3. Re-run Steps 2–5 in order.
4. THOTH produces a new findings artifact (iteration N+1).
5. WOLFIE triages new failures.
6. Repeat until the iteration verdict is **PASS**.

**No intermediate state may be carried forward.** Each iteration starts from a clean drop.

---

## Actor Assignments

| Actor | Role | Scope |
|---|---|---|
| HEPHAESTUS (8) | Execution | Steps 1–4 (drop, install, wizard, login) |
| THOTH (26) | Validation and documentation | Step 5 (validation checks) and Step 6 (findings artifact) |
| WOLFIE (1) | Triage and task creation | Step 7 (feed findings back into tasks and plan) |
| LILITH (2) | Post-iteration audit | Independent verification of PASS verdicts before closing loop |

---

## Schema Authority Reference

All schema validation checks must reference the canonical DDL:

- **Primary authority:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- **Column/type reference:** `lupo-database/lupopedia/toon/*.toon.json` (generated; never hand-edited)
- **Crafty import mapping:** `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`

If a check fails because a column or table does not exist in the install SQL, the fix goes to the install SQL first, then the TOON is regenerated.

---

## Thread Lineage

```
Thread 1039 (parent) — WOLFIE Release Execution and 4.0.85 Rollover
  +-- Thread 1043 (child) — Upgrade Validation Loop (this thread)
        +-- [Findings artifacts] — iteration 1, 2, 3, …
```

---

## Termination Condition

The upgrade validation loop is **complete** when:

1. THOTH publishes an iteration findings artifact with `iteration_verdict: PASS`.
2. LILITH signs off that the PASS verdict is independently verified.
3. WOLFIE closes Thread 1043 by publishing a closure artifact.

Until all three conditions are met, Thread 1043 remains **active**.
