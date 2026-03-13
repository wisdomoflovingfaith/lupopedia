# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\backups\filesystem_migration_20260131_133426\channels\0002\WHY_LUPOPEDIA_USES_PURE_DATA_STORE.md"
  file_hash: "2a37649104c5461ba308969e0515c6bf3eaa650489bde86db0ff22f4778b2037"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "backups\filesystem_migration_20260131_133426\channels\0002\WHY_LUPOPEDIA_USES_PURE_DATA_STORE.md"
  file_hash: "d9f4d8076a681e78e0f85192154f805bef28ac8abcdaa24eb3a6ef0a51f5dd50"
  file_path_from_root: "backups\filesystem_migration_20260131_133426\channels\0002\WHY_LUPOPEDIA_USES_PURE_DATA_STORE.md"
  file_hash: "4b5d13f957ff1c590be00aeb3e47abdd99ad20f0c9adeec0e3cc8148c41d1d6e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Why Lupopedia Uses a Pure Data Store"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["backups", "filesystem_migration_20260131_133426", "channels", "0002", "why_lupopedia_uses_pure_data_storemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Why Lupopedia Uses a Pure Data Store
(No triggers, no foreign keys, no stored procedures, no database logic)

Lupopedia is a federated, merge‑heavy, doctrine‑driven semantic OS, not an app.
Because of that, the database must behave as passive storage, not an active logic engine.
Any database‑level automation corrupts the system's ability to merge, repair, and evolve data safely.

Below is the canonical reasoning.

## 🟩 1. Triggers Destroy Data Merging
Triggers rewrite timestamps, mutate fields, and fire at unpredictable times during:

- imports
- merges
- repairs
- deduplication
- federation sync
- historical reconstruction

Lupopedia relies on original timestamps and original values to determine:

- which row is newer
- which row is authoritative
- which row should overwrite which
- whether a row is a duplicate or a fork

A trigger firing at the wrong moment destroys that history.

Once history is gone, merge becomes impossible.

## 🟩 2. Foreign Keys Break Orphan Handling
Lupopedia intentionally allows:

- orphaned rows
- dangling references
- partial imports
- incomplete migrations
- temporarily invalid states

Why?

Because your orphan system and probabilistic cleanup crew fix these states later.

Foreign keys would:

- block inserts
- block merges
- block repairs
- block federation
- block historical imports
- block soft deletes
- block re‑parenting

FK constraints assume a perfect world.
Lupopedia is designed for the real one.

## 🟩 3. Soft Delete Requires Freedom to Break Relationships Temporarily
Your system uses:

- deleted_ymdhis
- soft delete
- lineage preservation
- probabilistic cleanup
- re‑pointing of old records to new ones

This requires the ability to:

- nullify parents
- reassign parents
- temporarily break relationships
- merge rows with conflicting references
- redirect old IDs to new canonical ones

Any database constraint would block these operations.

## 🟩 4. The Probabilistic Cleanup Crew Needs Full Control
Your cleanup system:

- scans for orphans
- identifies lineage
- finds "newest surviving record"
- re‑points old references
- merges historical forks
- resolves duplicates
- repairs broken relationships

This is only possible because the database is passive.

If the database enforced relationships, the cleanup crew would be unable to:

- reassign
- redirect
- merge
- repair
- collapse
- dedupe

The entire repair system would collapse.

## 🟩 5. Federation Requires Schema Without Constraints
Lupopedia merges data from:

- legacy systems
- old databases
- partial exports
- remote nodes
- future nodes
- experimental agents

Federation requires:

- mismatched IDs
- conflicting timestamps
- missing parents
- partial rows
- inconsistent histories

Database‑level logic cannot handle this.
Application‑level logic can.

## 🟩 6. Doctrine Requires Deterministic Behavior
Triggers and FK constraints introduce:

- nondeterminism
- hidden side effects
- invisible mutations
- unpredictable ordering
- race conditions
- inconsistent behavior across environments

Lupopedia doctrine requires:

- explicit behavior
- deterministic merges
- predictable repairs
- transparent lineage
- reproducible state

Database logic violates all of these.

## 🟦 The Core Principle
The database stores facts.
The application interprets them.

Lupopedia is built on:

- explicit logic
- explicit merges
- explicit repairs
- explicit lineage
- explicit doctrine

Nothing happens behind your back.

## 🟪 Canonical Summary (for doctrine files)
Lupopedia uses a pure data store because:

- Triggers corrupt history
- Foreign keys block repairs
- Stored logic breaks federation
- Soft delete requires temporary invalid states
- Probabilistic cleanup requires full freedom
- Merges require original timestamps
- Doctrine requires deterministic behavior

Therefore:

**NO triggers.**
**NO foreign keys.**
**NO stored procedures.**
**NO database logic.**
**EVER.**
