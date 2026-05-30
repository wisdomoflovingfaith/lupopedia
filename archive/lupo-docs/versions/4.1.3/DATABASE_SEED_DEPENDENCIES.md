---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/versions/4.1.3/DATABASE_SEED_DEPENDENCIES.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.3/DATABASE_SEED_DEPENDENCIES.md"
  status: "development"
  when_updated: "20260419211230"
  trust_tier: "development"
  questions_toon: null
  memory_toon: "lupo-memory/database/development/1026/04/database-seed-dependencies-md.toon"
  atoms_toon: null
  transcript_jsonl: "1/database/seed-dependency-analysis"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "database"
  federation_node_id: 1
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "database-seed-dependencies"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "DATABASE SEED DEPENDENCIES -- Preconditions for seed-dependent import paths"
  summary: "Guards seed-dependent livehelp_config UPDATE and livehelp_channels scope; no execution approval; assumptions labeled unverified unless proven in seed SQL."
---
# DATABASE SEED DEPENDENCIES

## Purpose

This document states **preconditions and risks** for seed-dependent areas of the Crafty import story. It is **guardrail text**, not an execution order. It does **not** approve SQL changes.

**Related (read only; do not re-open scope here):** `DATABASE_MAPPING_CLASSIFICATION.md`, `DATABASE_INCOMPLETE_MAPPINGS.md`, `import_from_old_crafty_syntax.sql`, `install_new_lupopedia.sql`.

## Scope

Only the two seed-sensitive areas from classification are covered:

1. `livehelp_channels` -- legacy operator chat session rows
2. `livehelp_config` -- legacy global config blob mapped into `modules.config_json`

---

## livehelp_channels

### Legacy model (Crafty)

`livehelp_channels` rows describe **per-session / per-operator chat context** tied to `user_id`, `sessionid`, `startdate`, and `statusof`. That is a **transient live-help session** model (operators and visitors in flight), not a durable product object graph.

### Canonical model (Lupopedia)

The `channels` table models **persistent, structured channels** (identity, visibility, department binding, owner metadata, federation fields, soft delete, and long-lived configuration). These serve routing, membership, and doctrine-aligned channel work -- not replay of every historical Crafty chat row as if it were the same kind of entity.

**Explicit non-equivalence:** Legacy rows are **not** 1:1 with canonical `channels` rows without a **designed** mapping contract (types, lifecycle, retention, and what "historical" means). Treating them as interchangeable causes **identity and retention errors**.

### Current importer behavior

**PREP ONLY** -- `ALTER TABLE livehelp_channels` for engine/charset; **no** `INSERT INTO {{prefix}}channels` in the import script at the time of this writing.

### Preconditions before any future INSERT/ETL

1. **Product / doctrine decision:** Whether any legacy session data may enter Lupopedia, and if so **into which tables** (for example a dedicated archive surface vs `channels`).
2. **Type contract:** If anything is written to `channels`, each row needs an explicit **channel_type** / lifecycle story so operators do not see transient Crafty sessions as first-class channels.
3. **Actor mapping:** Any `user_id` reference must map through **`auth_users` / `actors`** rules already used elsewhere; do not invent joins.

### Risks if preconditions are absent

- **Semantic corruption:** Session rows masquerading as persistent channels.
- **Operator confusion:** UI and permissions assume channel doctrine; legacy rows do not.
- **Cleanup cost:** Reverting mistaken imports is expensive.

### Decision status (SQL)

**NOT PATCHABLE IN SQL NOW** -- blocked on product and data-model decision. No agent should add channel INSERTs from this document alone.

---

## livehelp_config

### Legacy table

Wide flat configuration row keyed by Crafty site (`livehelp_id`, `version`, paths, languages, flags, etc.). See legacy DDL in `old_crafty_syntax_3_7_5_start.sql` (not repeated here in full).

### Intended canonical target (conditional)

The import script uses:

```sql
UPDATE {{prefix}}modules m
SET m.config_json = ( ... JSON_OBJECT(...) FROM livehelp_config ... )
WHERE m.module_id = 1;
```

**Unverified unless checked in seed:** The script **assumes** a row exists with **`module_id = 1`** and that this row is the intended recipient for Crafty global config. That is **not proven** by this analysis document.

**Operator verification step (required before trusting the UPDATE):** In `seed_lupopedia.sql` (or whichever seed is authoritative for your install), confirm **whether** a `modules` row with `module_id = 1` exists, which module it represents, and whether `config_json` is the correct sink. If your seed uses a different id for the core/livehelp module, the **WHERE** clause is wrong for your tree until realigned.

### Current importer behavior (fact only)

**UPDATE-only** -- no INSERT of a fallback module row in the import script at the time of this writing. If the target row is missing, the UPDATE affects **zero rows** and configuration is silently not imported.

### Preconditions

1. **Verified seed row:** A `modules` row matching the **WHERE** predicate must exist in the **same** install/seed path you run before import.
2. **Correct semantic target:** Even when a row exists, confirm it is the module meant to hold Crafty global settings (not another module that happens to use id 1 in a test seed).
3. **JSON shape:** `config_json` must tolerate the keys produced by the import; mismatches are application-layer concerns, not silent SQL fixes.

### Risks if preconditions are absent

- **Silent no-op:** UPDATE matches zero rows; operators may believe config migrated.
- **Wrong sink:** Config lands on an unintended module if id 1 is not what you think.
- **Vendor JSON in SQL:** `JSON_OBJECT` in the import is MySQL-oriented; portability and doctrine prefer moving JSON construction to **Python** for the long-term importer (see migration plan docs).

### Decision status (SQL)

**CONDITIONALLY PATCHABLE -- REQUIRES VERIFIED SEED GUARANTEE**

Meaning: the **shape** of the UPDATE can be acceptable **only after** a human verifies the seed contract (row existence, id meaning, and JSON expectations). This label is **not** permission to run the import on production data.

### Do NOT do without Wolfie / seed contract sign-off

- **Do not** add an **INSERT fallback** "if UPDATE affects 0 rows" inside the import SQL to invent `module_id = 1`. That creates **duplicate-id / reserved-id** risk and masks missing seed defects.
- **Do not** treat this section as "approved to execute" -- verify seed, then run in a **controlled** environment only.

---

## Summary table (guardrail only)

| Legacy surface | Status | SQL / ETL |
|----------------|--------|-----------|
| `livehelp_channels` | **BLOCKED** on product + model decision (legacy sessions vs canonical channels) | **No INSERT** path in import today; do not add without design |
| `livehelp_config` | **CONDITIONALLY PATCHABLE -- REQUIRES VERIFIED SEED GUARANTEE** | UPDATE assumes `module_id = 1`; verify seed before any run; no INSERT fallback |

## Implementation notes (non-binding)

### livehelp_config

1. Document in seed README that **`modules.module_id = 1`** (or the real target id) must exist **before** import if config migration is expected.
2. Prefer a **pre-flight check** (script or operator query) counting `WHERE module_id = 1` -- not an undocumented INSERT in SQL.
3. Long term: move JSON build to **Python** per database migration plan doctrine.

### livehelp_channels

1. Wait for explicit product decision.
2. If historical retention is required, design a **separate** store or typed channel subtype with migration spec -- not an implicit map.

## Next actions (human / orchestrator)

1. **Verify** `module_id = 1` (or chosen id) in authoritative seed for your install path.
2. **Record** the verification outcome in a dated note or PRD 13 addendum when schema/seed change -- not here as a substitute for seed truth.
3. **Keep** `livehelp_channels` blocked until a written channel/session retention design exists.

---

*Precision pass (wording / guardrails): 20260419211026 UTC -- cursor (IDE facet, database lane)*  
*Prior analysis pass: cascade (database lane); content superseded where this file conflicts on approval language.*
