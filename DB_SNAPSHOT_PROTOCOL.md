# DB_SNAPSHOT_PROTOCOL.md  
**Doctrine: Database Snapshot Protocol for Lupopedia Semantic OS**

---

## 1. Purpose and scope

**Goal:**  
Define how database snapshots are created, tagged, documented, and used so that Lupopedia remains:

- resilient  
- reproducible  
- federation‑safe  
- agent‑safe  
- heritage‑safe  

This protocol applies to:

- the primary Lupopedia database (`lupopedia`)  
- all schema changes (new tables, columns, indexes)  
- all agents (Cascade, JetBrains, future IDE agents)  
- all humans working on the system  

Snapshots are **founder‑level events**, not routine noise.

---

## 2. Core principles

**1. No silent drift:**  
Schema and data changes must never "just happen." Every meaningful change is documented and intentional.

**2. Snapshots are explicit, not automatic:**  
No agent may create or commit a snapshot without explicit human instruction.

**3. Snapshots are for recovery and reproducibility, not day‑to‑day work:**  
They are anchors, not logs.

**4. Doctrine first, schema second:**  
Schema changes must be reflected in doctrine and changelog before they are snapshotted.

**5. Agents never commit DB exports by default:**  
`.toon` / `.txt` exports are treated as volatile artifacts unless explicitly promoted.

---

## 3. File types and tracking rules

**Volatile artifacts (ignored by default):**

- `database/toon_data/*.toon`  
- `database/toon_data/*.txt`  

**Rules:**

- These files **must not** be staged, committed, or deleted by agents.  
- They may exist locally as working artifacts (phpMyAdmin exports, TOON regen, etc.).  
- They are only ever committed as part of a **founder‑authorized snapshot event**.

---

## 4. Snapshot event definition

A **DB Snapshot Event** is a coordinated action with all of the following:

1. **Schema stability:**  
   - No in‑flight migrations.  
   - No pending schema changes.  
   - Application code and doctrine are in sync with the DB.

2. **Doctrine alignment:**  
   - `CHANGELOG.md` updated with schema changes.  
   - (Optional but recommended) `SCHEMA_HISTORY.md` entry added.

3. **Export:**  
   - Full DB export via phpMyAdmin or CLI (SQL dump and/or TOON set).  
   - Stored under a dedicated snapshot directory (e.g. `database/snapshots/`).

4. **Tagging:**  
   - Git tag created for the snapshot commit.  
   - Tag format:  
     - `db-snapshot-YYYYMMDD-HHMM`  

5. **Commit discipline:**  
   - Only snapshot files + doctrine updates are included.  
   - No unrelated code or content changes in the same commit.

---

## 5. Roles and permissions

**Founder (Eric):**

- May authorize snapshot creation.  
- May promote DB exports from volatile artifacts to committed snapshot assets.  
- May define new snapshot locations and formats.  
- May approve rollback operations.

**Agents (Cascade, JetBrains, others):**

- **Must not** independently decide to commit DB exports.  
- **Must not** delete or "clean up" DB export files.  
- **May** assist in:
  - updating `CHANGELOG.md` / `SCHEMA_HISTORY.md`  
  - tagging commits (when instructed)  
  - verifying working tree cleanliness  

**Other humans:**

- May request a snapshot.  
- May not commit DB exports without founder approval.

---

## 6. Standard snapshot procedure

When a snapshot is requested and approved:

**Step 1 — Confirm clean state**

- Working tree is clean (no uncommitted code or doctrine changes).  
- No pending migrations or half‑applied schema changes.

**Step 2 — Update doctrine**

- Add an entry to `CHANGELOG.md` describing:
  - schema changes since last snapshot  
  - purpose of the snapshot  
  - any new tables (e.g. `lupo_channel_state`)  
- Optionally add a detailed entry to `SCHEMA_HISTORY.md`.

**Step 3 — Export database**

- Use phpMyAdmin or CLI to export the full DB.  
- Save under a structured path, for example:  
  - `database/snapshots/lupopedia-YYYYMMDD-HHMM.sql`  
  - or `database/snapshots/toon/lupopedia-YYYYMMDD-HHMM/*.toon` 

**Step 4 — Commit snapshot**

- Stage:
  - snapshot files  
  - updated doctrine (`CHANGELOG.md`, `SCHEMA_HISTORY.md` if used)  
- Commit message format:  
  - `DB snapshot: lupopedia at YYYY-MM-DD HH:MM (schema aligned)`  

**Step 5 — Tag snapshot**

- Create a git tag:  
  - `db-snapshot-YYYYMMDD-HHMM`  
- Tag points to the snapshot commit.

---

## 7. Rollback and comparison

**Rollback policy:**

- Rollback is a **founder‑level decision**.  
- Rollback must be documented in `CHANGELOG.md` and/or `SCHEMA_HISTORY.md`.  
- Application code and doctrine must be aligned with the target snapshot tag.

**Rollback steps (high‑level):**

1. Checkout the snapshot tag (or commit).  
2. Restore DB from the corresponding snapshot file.  
3. Confirm schema and doctrine alignment.  
4. Resume development from that point (or branch).

**Comparison:**

- Schema diffs between snapshots should be done via:
  - SQL diff tools  
  - TOON diff tools  
  - or manual inspection guided by `SCHEMA_HISTORY.md`.

---

## 8. Agent behavior rules

**Cascade:**

- May:
  - add changelog entries describing schema changes.  
  - document snapshot events in doctrine.  
- Must not:
  - stage or commit DB exports unless explicitly instructed in a snapshot event.

**JetBrains (and other IDE agents):**

- May:
  - confirm working tree cleanliness.  
  - update `.gitignore` to keep DB exports ignored.  
  - assist with tagging and commit structuring.  
- Must not:
  - auto‑commit `.toon` / `.txt` exports.  
  - delete or revert DB export files.

**Shared rule:**  
If an agent detects new `.toon` / `.txt` exports under `database/toon_data/` or `database/snapshots/`, it should:

- treat them as **expected drift** unless explicitly told:  
  - "This is a snapshot event; promote these files and commit them."

---

## 9. Integration with semantic OS doctrine

DB snapshots are part of the **system's temporal spine**:

- They anchor the evolution of:
  - channel structures  
  - semantic layers  
  - actor models  
  - routing rules  
  - state tables (e.g. `lupo_channel_state`, `lupo_semantic_paths`)  

Snapshots must always be:

- **doctrine‑aligned** (reflected in text)  
- **manifest‑compatible** (no orphaned channels or actors)  
- **agent‑safe** (no surprises for Cascade/JetBrains)  

This protocol exists to prevent:

- Wave‑style backend chaos  
- silent schema drift  
- untraceable failures  
- irreproducible states  

and to ensure Lupopedia's database remains a **heritage‑safe, time‑aware foundation** for the Semantic OS.

---
