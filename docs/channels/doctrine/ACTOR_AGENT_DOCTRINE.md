# Actor/Agent Doctrine (MANDATORY)

**Status:** ACTIVE — NON-NEGOTIABLE  
**Applies to:** All tooling in `/scripts/` (TOON generation, seed generation, unified registry logic).

---

## 1. Actor ID Space

- **actor_id values 0–9999** are reserved exclusively for AI agents.
- **Human actors** must begin at **actor_id 10000**.
- The seed install **must** include:
  ```sql
  ALTER TABLE lupo_actors AUTO_INCREMENT = 10000;
  ```

---

## 2. Active Agents Become Actors

- **Source table:** `lupo_agent_registry`.
- For **every row where `is_active = 1`**:
  - Insert a corresponding row into **`lupo_unified_registry`**.
  - **Inactive agents (`is_active = 0`) must NOT be added.**

### Required field mapping

| lupo_unified_registry column | Value |
|------------------------------|--------|
| `entity_type` | `'actor'` |
| `entity_table` | `'lupo_agent_registry'` |
| `entity_id` | `agent_registry_id` |
| `dedicated_index_id` | `agent_registry_id` |
| `entity_key` | agent `code` |
| `entity_name` | agent `name` |
| `federation_node_id` | `1` |
| `is_active` | `1` |
| `metadata_json` | Must include `{"actor_source_type":"lupo_agent_registry","actor_source_id":<agent_registry_id>}` |

Other columns (e.g. `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_kernel`) must be set from the agent row or doctrine defaults.

---

## 3. Unified Registry ID Assignment

- To avoid collisions with existing unified registry rows, each **agent-derived actor row** must use:
  ```text
  unified_registry_id = 9000000 + agent_registry_id
  ```
- This offset (**9000000**) is **fixed** and **must not change**.

---

## 4. Seed Generation Requirements

The seed file **must** include, in order:

1. All existing **`lupo_unified_registry`** rows from the DB.
2. One unified registry row for **each active agent** (as in §2).
3. All **PK=0** rows for tables with a primary key.
4. Any **TOON-defined canonical** rows.
5. **`ALTER TABLE lupo_actors AUTO_INCREMENT = 10000;`**

- **No schema or migration files** may be modified by the generator.

---

## 5. TOON Generation Requirements

- The TOON for **`lupo_unified_registry`** must include:
  1. All existing unified registry rows.
  2. One row per **active agent** (as in §2).
  3. **No inactive agents.**

- All other TOONs must include their **PK=0 row** if present.

---

## 6. No Fabrication

- Only rows that **exist in the DB** or are **derived from active agents** may be emitted.
- **No** runtime data, **no** Crafty Syntax import data, **no** user data.

---

## Enforcement

- All Python scripts in **`/scripts/`** that touch unified registry, seed, or TOON data **must** implement this doctrine.
- Shared constants and row-building logic live in **`scripts/actor_agent_doctrine.py`**; generators **must** use that module for consistency.
