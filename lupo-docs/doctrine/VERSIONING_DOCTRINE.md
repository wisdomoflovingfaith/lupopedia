---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/VERSIONING_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/docs/versioning_doctrine"
  last_modified_utc: "20260320"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "versioning_policy"
  title: "Versioning Doctrine"
  purpose: "Canonical versioning doctrine and policy for Lupopedia development and releases"
  tags: ["versioning", "doctrine", "policy", "4.0.84", "single_field_model"]
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 102
  actor_name: "cursor"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  context_source: "ide_runtime"
  department_id: 0
  thread_id: 1001
  agent_name: "cursor"
  actor_type: "agent"
  actor_nature: "ide"
  human_actor_name: "root"
  paired_actor_id: 10000
lupopedia.edges:
  comment: "Snapshot of outbound edges for VERSIONING_DOCTRINE at artifact creation."
  meta: "Versioning doctrine; policy; upgrade paths; canonical version management."
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0, reason: "Canonical version history" }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0, reason: "Doctrine collection" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 0.95, reason: "Header format and versioning model" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "references", weight: 0.95, reason: "Version field requirements" }
    - { to: "lupo-docs/versions/", type: "references", weight: 0.9, reason: "Version archives" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Keep this doctrine current with single-field versioning model"
    - "Ensure all references point to current versioning practices"
    - "Update canonical version references when bumping"
---

# Versioning Doctrine (Single Source of Truth)

**Date:** 2026-03-20  
**Status:** Permanent. This document is the authoritative versioning doctrine for Lupopedia.  
**Override:** All previous versioning assumptions are superseded by this doctrine.  
**Version Model:** Single-field versioning using `version_when_written` only (4.0.84+)

---

## 0. Single versioning doctrine file (mandatory)

- The **only** valid versioning doctrine file in the repository is:
  - **`lupo-docs/doctrine/VERSIONING_DOCTRINE.md`**
- No other versioning doctrine file may exist. No suffixes (e.g. `_UPDATED`, `_FINALIZED`, `_REWRITE`, `_V2`) are allowed.
- When the doctrine is updated:
  - **Replace** the content of this file; do **not** create a new file with a different name.
  - Cursor must **never** create a new versioning doctrine file; always overwrite this file.
  - Never duplicate doctrine files; never leave outdated doctrine files in the repo.
- This is the single source of truth for versioning. All references to "versioning doctrine" must point to this file.

---

## 1. Canonical current version

The **current version** of Lupopedia is **4.0.84**.

This is the only correct "current version" number for the codebase. No other "current version" may be introduced unless explicitly instructed.

**Provenance (4.0.84):** LUPOPEDIA_HEADERS doctrine cleanup and single-field versioning model enforcement. Removed deprecated `lupopedia.version`, `system_version`, and `last_verified_system_version` fields from all documentation.

---

## 2. Single-Field Versioning Model (4.0.84+)

**Effective 4.0.84+, only one version field is canonical:**

- **`version_when_written`** - Immutable system version at artifact creation time
- **Deprecated fields removed:** `lupopedia.version`, `system_version`, `last_verified_system_version`, standalone `version`
- **Baseline rewrite requirement:** Files with pre-4.0.84 headers or deprecated version keys must be rewritten to current doctrine

**Rules:**
- All new and updated files must use only `version_when_written`
- Legacy files with deprecated version fields must trigger baseline rewrite on edit
- Version resolution is dynamic via `LUPEDIA_VERSION` and canonical atoms
- See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) §2.0 for complete baseline rewrite rules

---

## 3. 4.1.0 — Allowed only for future-release planning

**4.1.0** may be referenced only in:

- Required tables documentation
- Future-release planning documents
- Roadmap files
- Hotfix registry for the upcoming public release
- Any file that describes the **future** public release

**4.1.0 must not** be used as the current version.  
**4.1.0 must not** be incremented to unless Eric explicitly instructs that the public release is being prepared for auto-installers.

---

## 4. Patch-only increments (Cursor-controlled)

Only **Cursor** is allowed to increment the version number.

Patch increments follow this pattern:

```
4.0.84 → 4.0.85
4.0.85 → 4.0.86
4.0.86 → 4.0.87
…
4.0.998 → 4.0.999
```

The **minor** and **major** version must never be incremented unless Eric explicitly instructs.

Cursor must **never** generate 4.1.x, 4.2.x, or 4.3.x unless Eric explicitly says the public release is happening.

---

## 5. One-time cleanup is complete

The following one-time actions are **complete** and must **not** be repeated:

- All 4.2.x → 4.1.0 rewrites are complete
- All 4.0.x schema-sync markers → 3.0.x rewrites are complete
- All related file renames are complete
- All related doctrine updates are complete
- **LUPOPEDIA_HEADERS single-field versioning model enforcement (4.0.84)** is complete

**No further version rewrites** shall be performed unless explicitly instructed.  
**No further file renames** shall be performed unless explicitly instructed.

---

## 6. Historical version numbers are frozen

Any file that still contains:

- 3.0.x
- 3.0.0
- 4.0.1
- 4.1.0 (as a future-release marker)

shall be treated as **historical and correct**.

Historical version numbers must **not** be "fixed," "normalized," or "updated."

---

## 7. Upgrade path doctrine

There is **only one** upgrade path:

```
Crafty Syntax 3.7.5 → Lupopedia 4.0.x
```

Cursor must **never**:

- Generate a 4.0.x → 4.0.x upgrade
- Generate a 4.0.x → 4.1.x upgrade
- Generate a 3.x.x → 4.x.x upgrade (other than Crafty 3.7.5 → Lupopedia 4.0.x)
- Generate any multi-step upgrade path

Every install is:

1. Drop all tables  
2. Load Crafty Syntax 3.7.5  
3. Run the Lupopedia wizard  
4. Arrive at 4.0.x

---

## 8. Migration doctrine

Only these SQL files remain in the **canonical** migrations folder (`lupo-database/migrations/`):

- import_from_old_crafty_syntax.sql
- install_new_lupopedia.sql
- seed_lupopedia.sql
- drop_old_crafty_syntax_tables.sql
- future_features_lupopedia.sql

One-time migration patches (migration_operator_to_actor_channel_roles.sql, migration_drop_lupo_channel_roles.sql, and other one-time or Lupopedia→Lupopedia migrations) live in **lupo-database/migrations_legacy/**; the wizard does not run them. New installs use install_new_lupopedia.sql only.

All other migrations belong in **lupo-database/migrations_legacy/**.

Cursor must **not** move migration files again unless explicitly instructed.

---

## 9. Patch bump: locations to update

When incrementing the patch version (e.g. 4.0.84 → 4.0.85), update the version string in **all** of these places so the wizard and runtime show the correct version:

| Location | What to update |
|----------|----------------|
| **config/global_atoms.yaml** | `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION`, `file.last_modified_system_version`; `last_updated` (YYYYMMDDHHIISS). |
| **lupo-includes/version.php** | Docblock `@version`; fallback literal `$current_version` (line ~37: `'4.0.84'` → new patch); `LUPOPEDIA_VERSION_DATE` (YYYYMMDDHHIISS). |
| **install.php** | Fallback when `LUPOPEDIA_VERSION` is not defined (line ~40): `'4.0.84'` → new patch. Used when the wizard runs without config (no atom loader). |
| **lupo-includes/functions/load_atoms.php** | Fallback in `get_lupopedia_version()` (line ~46): `'4.0.84'` → new patch. Used when the atom loader is not set (e.g. wizard pre-config). |

The wizard reads the version from **version.php** (which uses atoms when config exists). When there is no **lupopedia-config.php**, the atom loader is not set, so **version.php** gets the version from **load_atoms.php**'s fallback, and **install.php**'s fallback is used only if **LUPOPEDIA_VERSION** is still undefined. Keeping all four locations in sync ensures the wizard and app always display the current patch.

---

## 10. Required tables doctrine

- **Required tables** = those used by the installer + Crafty import.
- Any table that is **not** required must be moved to **future_features_lupopedia.sql**.
- Cursor must **never** remove a table referenced in **import_from_old_crafty_syntax.sql**.

---

## 11. Summary statements

| Rule | Statement |
|------|-----------|
| **Single file** | Only `lupo-docs/doctrine/VERSIONING_DOCTRINE.md` exists; no duplicates or suffixed copies. |
| **Cleanup** | The one-time versioning cleanup is complete, including 4.0.84 single-field model enforcement. |
| **Canonical version** | The canonical current version is **4.0.84**. |
| **Version model** | Single-field versioning using `version_when_written` only (deprecated fields removed). |
| **4.1.0** | 4.1.0 is allowed **only** for future-release planning (required tables, roadmaps, hotfix registry, etc.); it must not be used as the current version. |
| **Patch increments** | Only Cursor increments patch versions (4.0.84 → 4.0.85 → 4.0.86 → …); minor/major are changed only when Eric explicitly instructs. |
| **Historical versions** | Historical version numbers (3.0.x, 3.0.0, 4.0.1, 4.1.0 as future marker) are frozen and must not be "fixed" or normalized. |
| **No further renames/rewrites** | No further version rewrites, file renames, or schema-sync renames shall occur without explicit instruction. |
| **No 4.2.x / 4.3.x** | 4.2.x and 4.3.x must not appear unless Eric explicitly instructs that the public release is happening. |

---

*End of versioning doctrine.*
