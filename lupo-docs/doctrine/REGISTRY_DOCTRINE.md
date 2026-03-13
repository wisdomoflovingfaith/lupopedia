# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\REGISTRY_DOCTRINE.md"
  file_hash: "3e27a599dcc5d1fc70aeb2715d6f3bf0a9e50c72238a7b394031b3ecd7802eae"
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
  file_path_from_root: "docs\doctrine\REGISTRY_DOCTRINE.md"
  file_hash: "9eac6bf4964e667679b18b78db0ef807fb3c59261cc9a5d6af51325befa6e668"
  file_path_from_root: "docs\doctrine\REGISTRY_DOCTRINE.md"
  file_hash: "241de3e34136cfdd36a306606666912e86c2e92b7d6fab161867aa4123e36c25"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for REGISTRY_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "registry_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/REGISTRY_DOCTRINE.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260217232500"
channel_id: 42
tags: ["registry", "unified", "doctrine", "flip"]
mood_rgb: "98FB98"
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/REGISTRY_DOCTRINE.md
---

# Registry Doctrine (Identity and Global IDs)

**Status:** Permanent.  
**Purpose:** Define the identity and update rules for the single global registry used by all Lupopedia installations.

---

## 1. REGISTRY — Reserved Index Ledger (NOT Config)

- **lupo_registry** is the **reserved index ledger**. It stores **all reserved IDs** for all entity types (channels, agents, actors, nodes, edges, etc.).
- It contains IDs that are active, soft-deleted, pending cleanup, or reserved but not yet implemented. It **prevents accidental reuse of IDs**.
- It is **NOT** a configuration table. It is **NOT** a runtime lookup table. It is **NOT** where agent or service config lives. Runtime code (e.g. IRIS, LABS) must **never** load agent/service configuration from registry.
- Canonical identity: **entity_type** + **entity_index**; **entity_table** names the table that owns that index. Use only for: ID reservation, ID existence checks, and allocation (e.g. MAX(entity_index)+1). **Never** for runtime config.

## 2. REGISTRY_OPEN — Rolling Free List (NOT Config)

- **lupo_registry_open** is the **rolling free list**. It stores **only** IDs that were recently hard-deleted and are safe to reuse.
- Used by allocation logic (e.g. findpuka) to **pop freed IDs (FIFO)** before allocating new ones. Optional **metadata_json** stores a reference snapshot when the index was freed.
- It is **NOT** a configuration table. It is **NOT** a runtime lookup table. Use only for freed-ID reuse and FIFO popping. **Never** for runtime config.

## 3. Agent & Service Configuration Doctrine

- **All** agent and service configuration comes from the **actors tables**:
  - **lupo_actors**
  - **lupo_actor_properties**
- **actor_type** determines whether the actor is: `agent`, `service`, `human`, `system`.
- IRIS and any other runtime code **must** load agent/service configuration from **lupo_actors** and **lupo_actor_properties**, **not** from registry.

## 4. Allocation Doctrine (findpuka)

- **Step 1:** Check **registry_open** for freed IDs (FIFO).
- **Step 2:** If none exist, compute **MAX(entity_index)+1** from the target entity table (or from registry for that entity_type).
- **Step 3:** Insert the new ID into **registry** immediately to reserve it.
- registry prevents collisions; registry_open provides fast reuse.

## 5. PURPOSE OF THE REGISTRY (Legacy Summary)

- Every row in **lupo_registry** has a **globally-agreed primary key ID** and an **entity_index** (the reserved ID in the table named by **entity_table**).
- All installations share the same reserved IDs so that system roles, channel roles, department roles, and cross-install data exchange remain consistent. **Config and runtime behavior** come from actors (and related) tables, not from the registry.

---

## 6. IDENTITY DOCTRINE

- The primary key ID in **lupo_registry** is a **GLOBAL IDENTITY**.
- It must **never** be auto-generated for system-defined rows.
- It must **never** be renumbered.
- It must **never** be changed after creation.
- All system-defined rows must be inserted with **explicit IDs**.

---

## 7. UPDATE DOCTRINE FOR NEW REGISTRY RECORDS

When an installation updates its registry (e.g., new version introduces new registry rows), the installer/wizard must:

a) **Compare** incoming registry rows to existing rows.  
b) For each new row:

- Check if a row with that **primary key ID** already exists.
- **If it exists** → STOP and show a fatal error:  
  **"Unified registry ID conflict: ID {id} already exists."**
- **If it does not exist** → safe to insert.

- This check applies **ONLY** to new rows added in newer versions.
- **Existing rows must never be modified or replaced.**

---

## 8. PROHIBITIONS

- Do **NOT** infer schema from the live DB.
- Do **NOT** modify **install_new_lupopedia.sql** unless explicitly instructed.
- Do **NOT** modify **seed_lupopedia.sql** unless explicitly instructed.
- Do **NOT** modify migration files unless explicitly instructed.
- Do **NOT** reintroduce **lupo_agent_registry** anywhere.
- PHP 5.3 syntax only (`array()`, no short syntax).

---

*End of unified registry doctrine.*
