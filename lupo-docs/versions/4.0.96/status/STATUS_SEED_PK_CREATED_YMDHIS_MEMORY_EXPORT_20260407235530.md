---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/versions/4.0.96/status/STATUS_SEED_PK_CREATED_YMDHIS_MEMORY_EXPORT_20260407235530.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/status/STATUS_SEED_PK_CREATED_YMDHIS_MEMORY_EXPORT_20260407235530.md
  last_modified_utc: "20260407235530"
  when_updated: "20260407235530"
  federation_node_id: 0
  channel_id: 42
  artifact_type: documentation
  artifact_kind: status_report
  purpose: "Seed/reserved PK vs created_ymdhis split; memory mirror lupo-memory/1970/01 for created_ymdhis=0"
  tags:
    - "4.0.96"
    - status
    - prd
    - memory
    - idgenerator
    - seed
lupopedia.footer:
  last_verified: "20260407235530"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
---

# file: STATUS — seed PK, `created_ymdhis`, memory export (4.0.96)

## 1. Problem statement

Reserved **install/seed** rows use **low, deterministic primary keys** (e.g. **`lupo_actors.actor_id` 1 = WOLFIE**) that are **not** shaped like **`IdGenerator::generate()`** outputs. **`created_ymdhis`** must still support **real install time**, **`0`** (“before temporal tracking” / immemorial), or the **14-digit prefix** of a **runtime** PK — **without** forcing PK and clock column to be derivable from each other in every case.

## 2. Decisions (documented + implemented)

| Topic | Rule |
|-------|------|
| **Constitution (PRD 00 §3.2)** | **Registry/seed exception:** install SQL may assign fixed low PKs; **runtime** inserts use **`IdGenerator::generate()`**. **`created_ymdhis`** may be install UTC, insert **`gmdate('YmdHis')`**, or **`0`** where documented. |
| **`lupo_memory_nodes`** | **Runtime:** PK from **`IdGenerator`**, **`created_ymdhis`** = same 14-digit prefix. **Seed:** low **`memory_node_id`**, **`created_ymdhis`** = install UTC or **`0`**. |
| **Filesystem mirror** | **`MemoryExportService`** derives **`lupo-memory/{YYYY}/{MM}/`** from an **effective** packed UTC: if **`created_ymdhis`** is **`0`**, empty, or too short for **`YYYYMM`**, use **`19700101000000`** → **`lupo-memory/1970/01/`**. JSON export still stores **actual** DB **`memory_node_id`** and **`created_ymdhis`**. |
| **Code** | **`lupo-includes/classes/MemoryExportService.php`** — private **`createdYmdhisForExportPath()`**; used by **`exportNode`** and **`removeMirrorFileForNode`**. |

## 3. Files touched (this batch)

| File | Change |
|------|--------|
| `lupo-docs/prd/00_root_constitutional_system_requirements.md` | §3.2 seed/PK/`created_ymdhis`; §5.7 unified memory graph + export |
| `lupo-docs/prd/01_core_identity.md` | **`lupo_actors`**: **`actor_id`**, **`created_ymdhis`**, workspace rules |
| `lupo-docs/prd/38_memory_unification.md` | §4.0 table, §5.1 DDL comments, §6.1, §7 tree |
| `lupo-docs/prd/24_actor_onboarding_flow.md` | Actor ID generation: seed vs runtime |
| `lupo-includes/classes/MemoryExportService.php` | Pre-history path normalization |
| `lupo-docs/versions/4.0.96/CHANGELOG.md` | Entry **[2026-04-07 23:55 UTC]** |
| `lupo-docs/versions/4.0.96/status/THREAD_INDEX.md` | Index row for this status |

## 4. Follow-ups (optional)

- If seed **`lupo_memory_nodes`** rows are added with **`created_ymdhis = 0`**, run **`MemoryExportService::exportNode`** (or **`fullExport`**) once to materialize **`lupo-memory/1970/01/`** mirrors.
- Align any remaining prose that still claims “**`created_ymdhis`** must always equal PK prefix” without the seed exception.

## 5. References

- [PRD 00](../../prd/00_root_constitutional_system_requirements.md) §3.2, §5.7  
- [PRD 38](../../prd/38_memory_unification.md)  
- [PRD 01](../../prd/01_core_identity.md) — `lupo_actors`  
- [PRD 24](../../prd/24_actor_onboarding_flow.md) — actor ID generation  

This output complies with Lupopedia Constitutional Root Rules.
