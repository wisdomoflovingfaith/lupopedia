# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "AGENT_SNAPSHOT_HANDLING_RULES.md"
  file_hash: "d5408699e853045e66c0845a847b57518466677948ba30dfa511c57cda20b2a7"
  file_path_from_root: "AGENT_SNAPSHOT_HANDLING_RULES.md"
  file_hash: "ac7648cf8767640423791f2eac0fe52e0ffd05666e3529386512611b55889629"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "AGENT_SNAPSHOT_HANDLING_RULES.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["agent_snapshot_handling_rulesmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# AGENT_SNAPSHOT_HANDLING_RULES.md
**Doctrine: Agent Behavior for DB Snapshot Events**

---

## 1. Purpose

These rules define how all Lupopedia agents must behave around:

- database snapshots
- schema changes
- TOON regenerations
- rollback events
- snapshot tagging
- volatile DB artifacts

The goal is to prevent:

- silent drift
- accidental commits
- schema corruption
- Wave‑style backend collapse
- agents stepping on each other's work

Snapshots are founder‑level events.
Agents must treat them as high‑risk, high‑discipline operations.

  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: []
  artifact_type: "documentation"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "AGENT_SNAPSHOT_HANDLING_RULES.md"
  file_hash: "d5408699e853045e66c0845a847b57518466677948ba30dfa511c57cda20b2a7"
  file_path_from_root: "AGENT_SNAPSHOT_HANDLING_RULES.md"
  file_hash: "ac7648cf8767640423791f2eac0fe52e0ffd05666e3529386512611b55889629"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "AGENT_SNAPSHOT_HANDLING_RULES.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["agent_snapshot_handling_rulesmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# AGENT_SNAPSHOT_HANDLING_RULES.md
**Doctrine: Agent Behavior for DB Snapshot Events**

---

## 1. Purpose

These rules define how all Lupopedia agents must behave around:

- database snapshots
- schema changes
- TOON regenerations
- rollback events
- snapshot tagging
- volatile DB artifacts

The goal is to prevent:

- silent drift
- accidental commits
- schema corruption
- Wave‑style backend collapse
- agents stepping on each other's work

Snapshots are founder‑level events.
Agents must treat them as high‑risk, high‑discipline operations.

---

## 2. Universal Rules (All Agents)

### Rule 1 — Agents NEVER commit DB exports by default

Agents must treat the following as volatile artifacts:

- `database/toon_data/*.toon`
- `database/toon_data/*.txt`
- `database/snapshots/*.sql`
- any phpMyAdmin export

Agents must:

- ignore them
- never stage them
- never delete them
- never "clean up" or "fix" them
- never auto‑commit them

Unless explicitly instructed:

> "This is a snapshot event. Promote these files."

### Rule 2 — Agents must detect DB drift but not act on it

If an agent sees untracked .toon or .txt files:

- report the drift
- take no action
- wait for human instruction

This prevents accidental schema commits.

### Rule 3 — Schema changes must be documented BEFORE snapshots

Agents must ensure:

- `CHANGELOG.md` is updated
- (optional) `SCHEMA_HISTORY.md` is updated

Agents must never snapshot a schema that is not documented.

### Rule 4 — Agents must never generate or modify snapshot tags

Only the founder may authorize:

- snapshot creation
- snapshot tagging
- snapshot promotion
- rollback

Agents may assist only when explicitly instructed.

### Rule 5 — Agents must maintain working tree hygiene

Before and after snapshot events, agents must:

- confirm no unrelated files are staged
- confirm no doctrine files are missing
- confirm no schema drift is unaccounted for

Agents must never mix snapshot commits with:

- code changes
- doctrine changes
- content changes
- manifest changes

Snapshots must be pure.

---

## 3. Cascade‑Specific Rules

Cascade is the doctrine + content + changelog agent.

### Cascade Rule 1 — Cascade documents schema changes

Cascade must:

- update `CHANGELOG.md`
- optionally update `SCHEMA_HISTORY.md`
- describe new tables, columns, indexes
- describe TOON regen count

Cascade must not:

- commit DB exports
- modify `.gitignore`
- create snapshot tags

### Cascade Rule 2 — Cascade prepares snapshot text but does not execute snapshot

Cascade may:

- prepare commit messages
- prepare doctrine text
- prepare snapshot descriptions

Cascade must not:

- export the DB
- commit snapshot files
- tag the snapshot

### Cascade Rule 3 — Cascade validates doctrine alignment

Before a snapshot event, Cascade must confirm:

- schema changes are documented
- doctrine is consistent
- no missing entries exist

---

## 4. JetBrains‑Specific Rules

JetBrains is the file system + repo hygiene + manifest agent.

### JetBrains Rule 1 — JetBrains may modify .gitignore

Only when instructed.

JetBrains must:

- add DB export patterns to `.gitignore`
- commit only `.gitignore`

JetBrains must not:

- delete existing DB exports
- commit DB exports
- auto‑clean directories

### JetBrains Rule 2 — JetBrains ensures clean commits

Before snapshot commits, JetBrains must:

- verify no unrelated files are staged
- verify doctrine files are staged correctly
- verify DB exports are ignored

### JetBrains Rule 3 — JetBrains may assist with tagging (when instructed)

JetBrains may:

- create a git tag
- push the tag

Only when explicitly told:

> "Tag this snapshot."

---

## 5. Cursor‑Specific Rules

Cursor is the schema + SQL + migration agent.

### Cursor Rule 1 — Cursor must never apply schema changes without doctrine

Cursor must:

- refuse schema changes unless doctrine exists
- refuse to snapshot
- refuse to commit DB exports

Cursor may:

- generate SQL
- validate schema
- prepare migration scripts

### Cursor Rule 2 — Cursor must validate schema before snapshot

Cursor must confirm:

- schema matches doctrine
- no orphaned tables exist
- no missing indexes exist
- no partial migrations exist

---

## 6. Snapshot Event Protocol (Agent Behavior)

When a snapshot event is declared:

**Step 1 — Cascade updates doctrine**
- `CHANGELOG.md`
- `SCHEMA_HISTORY.md`

**Step 2 — JetBrains ensures working tree is clean**
- no staged drift
- no untracked schema files
- no accidental commits

**Step 3 — Human exports DB**
- Agents do nothing here.

**Step 4 — JetBrains commits snapshot files (when instructed)**
- Only snapshot files + doctrine.

**Step 5 — JetBrains tags snapshot (when instructed)**

**Step 6 — Cursor validates schema after snapshot**
- Ensures no mismatch.

---

## 7. Rollback Rules

Agents must treat rollback as:

- founder‑level
- high‑risk
- doctrine‑synchronized

Agents may:

- assist with checking out tags
- assist with verifying schema alignment
- assist with doctrine updates

Agents must not:

- auto‑rollback
- auto‑restore DB
- auto‑apply migrations

---

## 8. Safety Guarantees

These rules ensure:

- no accidental DB corruption
- no silent schema drift
- no Wave‑style backend collapse
- reproducible system state
- safe multi‑agent collaboration
- heritage‑safe evolution of the Semantic OS