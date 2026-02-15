# Unified Registry Doctrine (Identity and Global IDs)

**Status:** Permanent.  
**Purpose:** Define the identity and update rules for the single global registry used by all Lupopedia installations.

---

## 1. PURPOSE OF THE UNIFIED REGISTRY

- The table **lupo_unified_registry** is the single global registry for all system-defined channels, agents, and actors.
- Every row has a **globally-agreed primary key ID**.
- All installations across the world must share the same IDs so that:
  - system roles,
  - channel roles,
  - department roles,
  - agent behaviors,
  - and cross-install data exchange  
  remain consistent and interoperable.

---

## 2. IDENTITY DOCTRINE

- The primary key ID in **lupo_unified_registry** is a **GLOBAL IDENTITY**.
- It must **never** be auto-generated for system-defined rows.
- It must **never** be renumbered.
- It must **never** be changed after creation.
- All system-defined rows must be inserted with **explicit IDs**.

---

## 3. UPDATE DOCTRINE FOR NEW REGISTRY RECORDS

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

## 4. PROHIBITIONS

- Do **NOT** infer schema from the live DB.
- Do **NOT** modify **install_new_lupopedia.sql** unless explicitly instructed.
- Do **NOT** modify **seed_lupopedia.sql** unless explicitly instructed.
- Do **NOT** modify migration files unless explicitly instructed.
- Do **NOT** reintroduce **lupo_agent_registry** anywhere.
- PHP 5.3 syntax only (`array()`, no short syntax).

---

*End of unified registry doctrine.*
