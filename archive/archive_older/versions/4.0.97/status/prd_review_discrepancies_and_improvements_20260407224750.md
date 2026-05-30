---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: "20260407224750"
  file_path_from_root: "docs/versions/4.0.96/status/PRD_REVIEW_DISCREPANCIES_AND_IMPROVEMENTS_20260407224750.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/status/PRD_REVIEW_DISCREPANCIES_AND_IMPROVEMENTS_20260407224750.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: audit
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
# file: PRD_REVIEW_DISCREPANCIES_AND_IMPROVEMENTS — version 4.0.96 status

This file records findings from a pass over `docs/prd/*.md` (index, README, and cross-file consistency). It is not a full line-by-line audit of every PRD section.

## 1. Index and namespace drift

- **`PRD_INDEX.md`** lists **many more** PRDs than **`README.md`**’s “14 namespace” tree. README still claims “14 files” and “100% (14/14)” coverage while the index enumerates **30+** numbered PRDs plus meta files. **Improvement:** Either retire the “14 namespaces only” narrative in README or split “core namespaces” vs “extended PRDs” explicitly so newcomers are not misled.
- **Duplicate numeric prefixes** appear in the index (two `01_*`, two `02_*`, two `03_*`, two `04_*`, two `05_*`, two `08_*`, two `15_*`, two `20_*`, two `21_*`, two `24_*`). This is intentional grouping by decade ranges but **confusing** without a secondary sort key. **Improvement:** Add non-overlapping IDs (e.g. `02a`/`02b`) or a stable secondary slug in the index table (not only filename).
- **`PRD_INDEX.md`** header says **Version 4.0.89** while work is tracked under **4.0.96**. **Improvement:** Bump index banner version when doing version-folder work, or replace with “tracks `main` / current patch” to reduce stale banners.

## 2. Memory model: three parallel stories (high priority)

These sources **disagree** on PK name, columns, edge table, and slug storage:

| Source | PK / shape | Edges | Slug |
|--------|------------|-------|------|
| **`install_new_lupopedia.sql` (pre–this batch)** | `memory_node_id` + `memory_slug` column only (minimal row) | Relationships via **`lupo_edges`** (per comments) | DB column |
| **`01_core_identity.md`** (`lupo_memory_nodes` section), **`15_actors.md`**, **`24_actor_onboarding_flow.md`** | `memory_node_id`, `owner_type` / `owner_id`, `memory_slug`, etc. | **`lupo_edges`** with `memory_node` object type | DB column |
| **`38_memory_unification.md` (canonical direction)** | Rich row; PK aligned with **`IdGenerator`**; **`created_ymdhis`** = first 14 digits of PK; **no** persisted `memory_slug` | Dedicated **`lupo_memory_edges`** (typed memory graph) | **Derived in PHP** (`MemoryExportService::generateSlug`) for export filename |

**Improvement:** Treat **PRD 38** as the target for **schema + export** after ratification; reconcile **01 / 15 / 24 / 37 / claude.md / memory/README** to one PK name (`memory_node_id` per PK naming doctrine), one edge strategy (either only `lupo_edges` or only `lupo_memory_edges`, or a documented split of responsibilities), and one slug rule (derived vs column).

## 3. PRD 38 internal issues (addressed in this batch)

- **Generated column `memory_slug`** used `FROM_UNIXTIME(created_ymdhis)` — **invalid** because `created_ymdhis` is **packed BIGINT UTC**, not Unix seconds. Removed in favor of **application-side** slug generation.
- Sample **`MemoryExportService`** used `$this->db->fetchOne` for a **full row**; project **`PDO_DB`** uses **`fetchRow`** for that. Corrected in PRD and implementation.
- **`deleteNodeFile`** glob pattern did not match slug-only filenames. Implementation rebuilds path from DB (including soft-deleted row) before unlink.

## 4. Constitutional / portability flags in PRDs

- Several PRDs still show **`DELETE FROM ...`** for cleanup (e.g. **`01_core_identity.md`** session cleanup). Root doctrine prefers **soft delete** for lineage tables; sessions may be exempt — **worth a single explicit exception list** in PRD 00 or 14.
- **`38_memory_unification.md`** previously used **MySQL generated columns** and **`DECIMAL`** on edges; integer-only and portable-SQL rules suggest **INT-scaled weights** and **no generated columns** for portable DDL. Edge weight is documented as integer hundredths in the revised PRD/install comment.

## 5. `IdGenerator` wording

- **`IdGenerator.php`** documents a **4-digit suffix** (CSPRNG-derived), not a monotonic **sequence**. PRDs should say **“suffix”** or **“random 4-digit suffix”** unless the implementation is changed to a true sequence allocator.

## 6. Suggested next actions (dependency order)

1. Freeze **one** memory ER diagram (PRD 38 + install SQL + TOON generation).
2. Update **01 / 15 / 24 / 37 / PRD_AGENT_DEFINITION_MODEL / claude.md** to match the frozen model.
3. Regenerate **TOON** files for `lupo_memory_nodes` / `lupo_memory_edges` after install SQL is stable (`scripts/generate_toon_from_sql.py` or project-standard generator).
4. Add **`REQUIRED_TABLES`** / future-features alignment audit if these tables change required vs optional status.

---

This output complies with Lupopedia Constitutional Root Rules.
