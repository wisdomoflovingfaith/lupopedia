---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/DOCTRINE_FILE_STRUCTURE.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
---

FILE: docs/doctrine/DOCTRINE_FILE_STRUCTURE.md
TYPE: md

# Doctrine File Structure (MANDATORY)

Lupopedia uses a **canonical doctrine file** plus optional **versioned** and **transitional** revisions. All contributors — including IDE AI tools — must follow these rules.

---

## Canonical Doctrine File (always present)

```
docs/doctrine/LUPOPEDIA_DOCTRINE.md
```

This file is the **single source of truth** for all architectural rules. IDE AI tools must always read this file first for active doctrine.

---

## Versioned Doctrine Files (optional, historical)

```
docs/doctrine/LUPOPEDIA_DOCTRINE_v1.1.md
docs/doctrine/LUPOPEDIA_DOCTRINE_v1.2.md
docs/doctrine/LUPOPEDIA_DOCTRINE_v1.3.md
...
```

These represent **frozen snapshots** of doctrine at specific versions. They are for history and reference only, not active rules.

---

## Updated / Revised Doctrine Files (optional, transitional)

```
docs/doctrine/LUPOPEDIA_DOCTRINE_UPDATED_v1.1.md
docs/doctrine/LUPOPEDIA_DOCTRINE_REVISED_v1.1.md
```

These represent **in-progress updates** before being merged into the canonical file.

---

## Naming Rule (MANDATORY)

Every doctrine file must follow one of these patterns:

| Pattern | Example | Use |
|--------|---------|-----|
| `<root>.md` | `LUPOPEDIA_DOCTRINE.md` | Canonical (single source of truth) |
| `<root>_v<version>.md` | `LUPOPEDIA_DOCTRINE_v1.1.md` | Versioned snapshot |
| `<root>_UPDATED_v<version>.md` | `LUPOPEDIA_DOCTRINE_UPDATED_v1.1.md` | In-progress update |
| `<root>_REVISED_v<version>.md` | `LUPOPEDIA_DOCTRINE_REVISED_v1.1.md` | In-progress revision |

**Root** for the main architectural doctrine is:

```
LUPOPEDIA_DOCTRINE
```

---

## Why This Matters

- **IDE AI tools** must always read the canonical file first for active rules.
- **Versioned files** exist only for history, not active rules.
- **Contributors** must never create doctrine files with arbitrary names.
- **Doctrine** must remain discoverable, stable, and non-fragmented.
