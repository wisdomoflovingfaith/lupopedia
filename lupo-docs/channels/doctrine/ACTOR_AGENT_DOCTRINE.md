# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\ACTOR_AGENT_DOCTRINE.md"
  file_hash: "85351497d52ef0176d9b4016ace0eec79ed4292aba96eff92038202ed79020d4"
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

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\doctrine\ACTOR_AGENT_DOCTRINE.md"
  file_hash: "1b6984c6a33a5ac1e9a0438b3f7457a875f546590f2e9d23e15b8b2768f3c97b"
  file_path_from_root: "docs\channels\doctrine\ACTOR_AGENT_DOCTRINE.md"
  file_hash: "b9c4986ee49a8daca572cfd4f0c402167cf3a35cbc1db81000f469d54853b275"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Actor/Agent Doctrine (MANDATORY)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "actor_agent_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

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
  - Insert a corresponding row into **`lupo_registry`**.
  - **Inactive agents (`is_active = 0`) must NOT be added.**

### Required field mapping

| lupo_registry column | Value |
|------------------------------|--------|
| `entity_type` | `'actor'` |
| `entity_table` | `'lupo_agent_registry'` |
| `entity_index` | agent registry id (reserved index in entity_table) |
| `entity_key` | agent code / lookup key |
| `entity_name` | display name (optional) |
| `federation_node_id` | `1` |
| `is_active` | `1` |
| `metadata_json` | Must include `{"actor_source_type":"lupo_agent_registry","actor_source_id":<agent_registry_id>}` |

Other columns (e.g. `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_kernel`) must be set from the agent row or doctrine defaults.

---

## 3. Unified Registry ID Assignment

- To avoid collisions with existing unified registry rows, each **agent-derived actor row** must use:
  ```text
  registry_id = 9000000 + agent_registry_id
  ```
- This offset (**9000000**) is **fixed** and **must not change**.

---

## 4. Seed Generation Requirements

The seed file **must** include, in order:

1. All existing **`lupo_registry`** rows from the DB.
2. One unified registry row for **each active agent** (as in §2).
3. All **PK=0** rows for tables with a primary key.
4. Any **TOON-defined canonical** rows.
5. **`ALTER TABLE lupo_actors AUTO_INCREMENT = 10000;`**

- **No schema or migration files** may be modified by the generator.

---

## 5. TOON Generation Requirements

- The TOON for **`lupo_registry`** must include:
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
