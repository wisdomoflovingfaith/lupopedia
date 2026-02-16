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

The **current version** of Lupopedia is **4.0.9**.

This is the only correct “current version” number for the codebase. No other “current version” may be introduced unless explicitly instructed.

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

One-time migration patches (migration_operator_to_actor_channel_roles.sql, migration_drop_lupo_channel_roles.sql) exist for existing databases; the wizard does not run them. New installs use install_new_lupopedia.sql only.

All other migrations belong in **database/migrations_legacy/**.

Cursor must **not** move migration files again unless explicitly instructed.

---

## 8. Required tables doctrine

- **Required tables** = those used by the installer + Crafty import.
- Any table that is **not** required must be moved to **future_features_lupopedia.sql**.
- Cursor must **never** remove a table referenced in **import_from_old_crafty_syntax.sql**.

---

## 9. Summary statements

| Rule | Statement |
|------|-----------|
| **Single file** | Only `docs/doctrine/VERSIONING_DOCTRINE.md` exists; no duplicates or suffixed copies. |
| **Cleanup** | The one-time versioning cleanup is complete. |
| **Canonical version** | The canonical current version is **4.0.9**. |
| **4.1.0** | 4.1.0 is allowed **only** for future-release planning (required tables, roadmaps, hotfix registry, etc.); it must not be used as the current version. |
| **Patch increments** | Only Cursor increments patch versions (4.0.6 → 4.0.7 → 4.0.8 → …); minor/major are changed only when Eric explicitly instructs. |
| **Historical versions** | Historical version numbers (3.0.x, 3.0.0, 4.0.1, 4.1.0 as future marker) are frozen and must not be “fixed” or normalized. |
| **No further renames/rewrites** | No further version rewrites, file renames, or schema-sync renames shall occur without explicit instruction. |
| **No 4.2.x / 4.3.x** | 4.2.x and 4.3.x must not appear unless Eric explicitly instructs that the public release is happening. |

---

*End of versioning doctrine.*
