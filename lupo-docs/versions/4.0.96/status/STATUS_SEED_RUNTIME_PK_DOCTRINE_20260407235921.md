---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/versions/4.0.96/status/STATUS_SEED_RUNTIME_PK_DOCTRINE_20260407235921.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/status/STATUS_SEED_RUNTIME_PK_DOCTRINE_20260407235921.md
  last_modified_utc: "20260407235921"
  when_updated: "20260407235921"
  federation_node_id: 0
  channel_id: 42
  artifact_type: documentation
  artifact_kind: status_report
  purpose: "Global seed vs runtime PK doctrine (PRD 00 §3.2.1) rolled through PRDs 01, 07, 15, 24, 38"
  tags:
    - "4.0.96"
    - status
    - prd
    - idgenerator
    - seed
lupopedia.footer:
  last_verified: "20260407235921"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
---

# file: STATUS — seed vs runtime PK doctrine (4.0.96)

## 1. Intent

Document that **any** table with **install/seed** rows may use **fixed low PKs**, while **runtime** rows use **`IdGenerator::generate()`**. **`created_ymdhis`** (and analogous columns) may be **install UTC**, insert time, or **`0`** (immemorial) for seeds — **independent** of PK shape. Avoid inventing per-table numeric bands in code without **install + registry + PRD** authority.

## 2. Delivered

| Artifact | Change |
|----------|--------|
| **PRD 00** | **§3.2** qualified for runtime vs seed; new **§3.2.1** with dual-strategy table, illustrative per-table column, six rules, memory mirror note (**`lupo-memory/1970/01/`**). |
| **PRD 38** | **§4.0** runtime vs seed; **§4.1** DDL comments; **§4.0** rules 1–2 scoped; **§6.6** export examples. |
| **PRD 01** | **`lupo_actors`** `CREATE TABLE` comment template + seed **`created_ymdhis`** note. |
| **PRD 15** | **Actor ID ranges** (`actor_id` < 2026 vs ≥ 2026). |
| **PRD 07** | **Seed `agent_id` vs runtime** (`lupo_agents` 1–2025 band, **§3.2.1** link). |
| **PRD 24** | Pointer to **PRD 00 §3.2.1**. |
| **CHANGELOG 4.0.96** | Entry **[2026-04-07 23:59 UTC]**. |

## 3. Doctrine corrections vs informal prompt

- **Path:** **`lupo-memory/1970/01/`** (not `lupo-export/…`) — matches **`MemoryExportService`**.
- **`lupo_auth_users`:** States **PRD 01** reserved **`auth_user_id = 0`**; does **not** assert root seed is always **`1`** (follow install + PRD 01).

## 4. References

- [PRD 00 §3.2.1](../../prd/00_root_constitutional_system_requirements.md)
- [PRD 38](../../prd/38_memory_unification.md)
- [STATUS_SEED_PK_CREATED_YMDHIS_MEMORY_EXPORT_20260407235530.md](STATUS_SEED_PK_CREATED_YMDHIS_MEMORY_EXPORT_20260407235530.md) (prior **`MemoryExportService`** + memory focus)

This output complies with Lupopedia Constitutional Root Rules.
