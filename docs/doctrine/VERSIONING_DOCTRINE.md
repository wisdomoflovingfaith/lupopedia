# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\VERSIONING_DOCTRINE.md"
  file_hash: "b1b5630be7ec1cd11c6ffc3b5e238c6cf97096ead8857ecb6c46ffe1c53a152d"
  file_path_from_root: "docs\doctrine\VERSIONING_DOCTRINE.md"
  file_hash: "9e98cd45f4f7398213935db056594926abc79294d585543036cb5cafe6f7cb38"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for VERSIONING_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "versioning_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/VERSIONING_DOCTRINE.md
file.last_modified_system_version: "4.0.21"
file.last_modified_utc: "20260220000000"
# channel_id unresolved — requires lupo_contents lookup by application.
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/VERSIONING_DOCTRINE.md
---
# Versioning Doctrine (Single Source of Truth)

**Date:** 2026-02-12  
**Status:** Permanent. This document is the authoritative versioning doctrine for Lupopedia.  
**Override:** All previous versioning assumptions are superseded by this doctrine.

---

## 0. Single versioning doctrine file (mandatory)

- The **only** valid versioning doctrine file in the repository is:
  - **`docs/doctrine/VERSIONING_DOCTRINE.md`**
- No other versioning doctrine file may exist. No suffixes (e.g. `_UPDATED`, `_FINALIZED`, `_REWRITE`, `_V2`) are allowed.
- When the doctrine is updated:
  - **Replace** the content of this file; do **not** create a new file with a different name.
  - Cursor must **never** create a new versioning doctrine file; always overwrite this file.
  - Never duplicate doctrine files; never leave outdated doctrine files in the repo.
- This is the single source of truth for versioning. All references to “versioning doctrine” must point to this file.

---

## 1. Canonical current version

The **current version** of Lupopedia is **4.0.22**.

This is the only correct “current version” number for the codebase. No other “current version” may be introduced unless explicitly instructed.

**Provenance (4.0.22):** Version bump from 4.0.21; Crafty Syntax 3.7.5 upgrade testing and validation framework.

---

## 2. 4.1.0 — Allowed only for future-release planning

**4.1.0** may be referenced only in:

- Required tables documentation
- Future-release planning documents
- Roadmap files
- Hotfix registry for the upcoming public release
- Any file that describes the **future** public release

**4.1.0 must not** be used as the current version.  
**4.1.0 must not** be incremented to unless Eric explicitly instructs that the public release is being prepared for auto-installers.

---

## 3. Patch-only increments (Cursor-controlled)

Only **Cursor** is allowed to increment the version number.

Patch increments follow this pattern:

```
4.0.5 → 4.0.6
4.0.6 → 4.0.7
4.0.7 → 4.0.8
4.0.8 → 4.0.9
…
4.0.998 → 4.0.999
```

The **minor** and **major** version must never be incremented unless Eric explicitly instructs.

Cursor must **never** generate 4.1.x, 4.2.x, or 4.3.x unless Eric explicitly says the public release is happening.

---

## 4. One-time cleanup is complete

The following one-time actions are **complete** and must **not** be repeated:

- All 4.2.x → 4.1.0 rewrites are complete
- All 4.0.x schema-sync markers → 3.0.x rewrites are complete
- All related file renames are complete
- All related doctrine updates are complete

**No further version rewrites** shall be performed unless explicitly instructed.  
**No further file renames** shall be performed unless explicitly instructed.

---

## 5. Historical version numbers are frozen

Any file that still contains:

- 3.0.x
- 3.0.0
- 4.0.1
- 4.1.0 (as a future-release marker)

shall be treated as **historical and correct**.

Historical version numbers must **not** be “fixed,” “normalized,” or “updated.”

---

## 6. Upgrade path doctrine

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

## 7. Migration doctrine

Only these SQL files remain in the **canonical** migrations folder (`database/migrations/`):

- import_from_old_crafty_syntax.sql
- install_new_lupopedia.sql
- seed_lupopedia.sql
- drop_old_crafty_syntax_tables.sql
- future_features_lupopedia.sql

One-time migration patches (migration_operator_to_actor_channel_roles.sql, migration_drop_lupo_channel_roles.sql, and other one-time or Lupopedia→Lupopedia migrations) live in **database/migrations_legacy/**; the wizard does not run them. New installs use install_new_lupopedia.sql only.

All other migrations belong in **database/migrations_legacy/**.

Cursor must **not** move migration files again unless explicitly instructed.

---

## 8. Patch bump: locations to update

When incrementing the patch version (e.g. 4.0.16 → 4.0.17), update the version string in **all** of these places so the wizard and runtime show the correct version:

| Location | What to update |
|----------|----------------|
| **config/global_atoms.yaml** | `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION`, `file.last_modified_system_version`; `last_updated` (YYYYMMDDHHIISS). |
| **lupo-includes/version.php** | Docblock `@version`; fallback literal `$current_version` (line ~37: `'4.0.16'` → new patch); `LUPOPEDIA_VERSION_DATE` (YYYYMMDDHHIISS). |
| **install.php** | Fallback when `LUPOPEDIA_VERSION` is not defined (line ~40): `'4.0.16'` → new patch. Used when the wizard runs without config (no atom loader). |
| **lupo-includes/functions/load_atoms.php** | Fallback in `get_lupopedia_version()` (line ~46): `'4.0.16'` → new patch. Used when the atom loader is not set (e.g. wizard pre-config). |

The wizard reads the version from **version.php** (which uses atoms when config exists). When there is no **lupopedia-config.php**, the atom loader is not set, so **version.php** gets the version from **load_atoms.php**’s fallback, and **install.php**’s fallback is used only if **LUPOPEDIA_VERSION** is still undefined. Keeping all four locations in sync ensures the wizard and app always display the current patch.

---

## 9. Required tables doctrine

- **Required tables** = those used by the installer + Crafty import.
- Any table that is **not** required must be moved to **future_features_lupopedia.sql**.
- Cursor must **never** remove a table referenced in **import_from_old_crafty_syntax.sql**.

---

## 10. Summary statements

| Rule | Statement |
|------|-----------|
| **Single file** | Only `docs/doctrine/VERSIONING_DOCTRINE.md` exists; no duplicates or suffixed copies. |
| **Cleanup** | The one-time versioning cleanup is complete. |
| **Canonical version** | The canonical current version is **4.0.21**. |
| **4.1.0** | 4.1.0 is allowed **only** for future-release planning (required tables, roadmaps, hotfix registry, etc.); it must not be used as the current version. |
| **Patch increments** | Only Cursor increments patch versions (4.0.6 → 4.0.7 → 4.0.8 → …); minor/major are changed only when Eric explicitly instructs. |
| **Historical versions** | Historical version numbers (3.0.x, 3.0.0, 4.0.1, 4.1.0 as future marker) are frozen and must not be “fixed” or normalized. |
| **No further renames/rewrites** | No further version rewrites, file renames, or schema-sync renames shall occur without explicit instruction. |
| **No 4.2.x / 4.3.x** | 4.2.x and 4.3.x must not appear unless Eric explicitly instructs that the public release is happening. |

---

*End of versioning doctrine.*