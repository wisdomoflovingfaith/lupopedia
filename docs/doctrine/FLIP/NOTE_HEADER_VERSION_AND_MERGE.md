---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/NOTE_HEADER_VERSION_AND_MERGE.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
---
# NOTE: FLIP Header version and 3.x → 4.0.x merge

**Purpose:** Reminder for Cursor and contributors when editing files or merging legacy header docs.

---

## 1. Set `file.last_modified_system_version` when editing

When you **add or edit** a FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header) in any file, set:

- **`file.last_modified_system_version`** to the **current Lupopedia version** at the time of the edit.

**Current version (as of this note):** **4.0.16**

Source of truth for the current version:

- `config/global_atoms.yaml` — `version`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION`
- `lupo-includes/version.php` — fallback and `LUPOPEDIA_VERSION`
- `docs/doctrine/VERSIONING_DOCTRINE.md` — canonical current version

Do not leave `file.last_modified_system_version` as `0000` or an old 3.x value when you have actually edited the file. Use the current patch version (e.g. 4.0.16, then 4.0.17 when we bump).

---

## 2. 3.x vs 4.0.16 — check existing docs when merging

Incoming prompts or legacy material may refer to **version 3.x** (e.g. 3.0.x, 3.1.x). We are on **4.0.16** now. When merging or updating docs:

- **Prefer existing 4.0.x doctrine and docs** in the repo over 3.x descriptions unless explicitly told to restore legacy behavior.
- **Header naming:** Use **FLIP Headers** as the canonical name; **Wolfie Headers**, **CROP Headers**, and **FLIPPING Headers** are aliases. When consolidating or rewriting, align to this (FLIP canonical, others as aliases).
- **Paths and references:** Check `docs/doctrine/FLIP/`, `docs/doctrine/VERSIONING_DOCTRINE.md`, and `docs/doctrine/database/` for current 4.0.x positions before applying 3.x-era text.

---

## 3. Where this note lives

- **This file:** `docs/doctrine/FLIP/NOTE_HEADER_VERSION_AND_MERGE.md`
- **Cursor rule:** `.cursor/rules/flip-doctrine.mdc` reminds to set `file.last_modified_system_version` to current version when editing FLIP Headers.

---

*No schema, no SQL. Documentation and workflow note only.*
