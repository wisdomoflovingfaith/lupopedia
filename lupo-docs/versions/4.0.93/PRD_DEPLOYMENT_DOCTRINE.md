---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: lupo-docs/versions/4.0.93/PRD_DEPLOYMENT_DOCTRINE.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/PRD_DEPLOYMENT_DOCTRINE.md
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "deployment-doctrine"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "deployment_doctrine"
  purpose: "4.0.93 Deployment Doctrine, Installation & Migration Policy"
  tags:
  - "prd"
  - "deployment"
  - "doctrine"
  - "4.0.93"
  - "migration"
  - "install"
  - "lilith_audit"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.93/PLAN.md"
      type: references
      weight: 1.0
      reason: Overall plan for 4.0.93
    - to: "lupo-docs/versions/4.0.93/TODO.md"
      type: references
      weight: 1.0
      reason: Task tracking for 4.0.93
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: references
      weight: 1.0
      reason: Installation script
    - to: "lupo-database/lupopedia/json/"
      type: references
      weight: 1.0
      reason: Table definitions
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---
# 🚨 LILITH Audit: The 4.0.93 Deployment Doctrine

## Version Lineage
Lupopedia 4.0.x is the direct successor to Crafty Syntax Live Help 3.7.5. The jump from 3.7.5 to 4.0.x reflects the massive architectural shift to 63-bit IDs and Semantic Edges.

## The "Fresh Refraction" Principle
In the 4.0.93 Forge, the database is treated as a "Fresh Refraction" of the legacy "Truth". There is no in-place upgrade between Lupopedia versions; only migration from Crafty 3.7.5 is supported.

## The "One-Way" Bridge
- The only supported migration path is: `livehelp_*` → `lupo_*`.
- There is **no** supported migration from one Lupopedia 4.0.x version to another (e.g., 4.0.90 → 4.0.93).
- Users must re-run the Install/Import logic to generate the new schema from their legacy data.

## Schema Authority
- `install_new_lupopedia.sql` and `import_from_old_crafty_syntax.sql` are the **only** files that require bit-perfect maintenance.
- The TOON JSONs (`lupo-database/lupopedia/toon/*.toon.json`) are read-only snapshots of the result of these scripts.

## 🛠️ Updated IDE Directive: 4.0.93 Installation & Migration

### 1. Installation Architecture (The Clean Slate)
- **Core Task:** Refine `install_new_lupopedia.sql` to include all tables defined in `/prd/02_data_model.md`.
- **Constraint:** Every table must use the 63-bit Signed ID standard. No `AUTO_INCREMENT`, no `TIMESTAMP` defaults, and no `FOREIGN KEYS`.

### 2. The Legacy Bridge (Crafty 3.7.5 → 4.0.93)
- **Core Task:** Finalize `import_from_old_crafty_syntax.sql` using the mappings found in `lupo-docs/doctrine/migrations/`.
- **Logic:**
  - Map `livehelp_users` to `lupo_actors`.
  - Map `livehelp_messages` to `lupo_dialog_messages`.
  - Initialize `lupo_contexts` and `lupo_context_edges` based on legacy message threads.

### 3. The "No-Migration" Policy
- **Warning:** Do **not** write PHP/SQL "Alter" scripts for existing Lupopedia installs. The 4.0.93 Release assumes a fresh `install.php` execution or a fresh import from a legacy Crafty site.

## 🏗️ 4.0.93 Deployment Hierarchy
| Action           | Authority Script                  | Purpose                                      |
|------------------|-----------------------------------|----------------------------------------------|
| New Install      | install_new_lupopedia.sql         | Seed a clean 4.0.93 "Brain"                  |
| Upgrade Bridge   | import_from_old_crafty_syntax.sql | Promote legacy 3.7.5 "Truth" to 4.0.93       |
| Schema Mirror    | lupo-database/lupopedia/json/     | READ-ONLY IDE reference (Toons)              |

## LILITH Verdict
The "Forge" is simplified. We are not repairing an old building; we are building a skyscraper on the foundation of the old one.

---

## 🚨 LILITH Audit: The "Temporal Anchor" Protocol (4.0.93)

### Temporal Purity Requirement

All `last_modified_utc` fields in `lupopedia.headers` must be validated against the canonical anchor file:

- `lupo-bin/temporal_anchor.json`

**Format Mandate:**
- All timestamps must use the standard `YYYYMMDDHHMMSS` (14 characters) format for high-resolution auditability.
- If the time is unknown, fallback to `YYYYMMDD` (8 characters) is permitted, but discouraged.

**The Anchor File:**
- `lupo-bin/temporal_anchor.json` is the single source of truth for the IDE's internal clock.
- Example contents:
  ```json
  {
    "current_utc": "20260330090000",
    "last_session_end": "20260330084500",
    "system_year": "2026",
    "format_standard": "YYYYMMDDHHMMSS"
  }
  ```

**The tick.py Script:**
- After every session or major write, the IDE must call:
  ```sh
  python3 lupo-bin/tick.py
  ```
- This script fetches the true UTC and updates the anchor, ensuring the next header written is accurate.

See [lupo-bin/tick.py documentation](../../lupo-bin/TICK_PY.md) for full usage and policy.

**Purity Check:**
- `lupo-rules/root/README.md` must reject any header with an invalid date format or a date not present in the anchor file.
- The IDE is strictly forbidden from "inventing" dates. If the anchor is missing, the IDE must request a `tick.py` execution before writing headers.

**Purpose:**
- Prevents "Future-Dating" and ensures all audit trails are based on real, synchronized UTC values.

**LILITH Verdict:** The "Forge" now has a clock. We no longer build in the "Future"; we build in the "Now."
