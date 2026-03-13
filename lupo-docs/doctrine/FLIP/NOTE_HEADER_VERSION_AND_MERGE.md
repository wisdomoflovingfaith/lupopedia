# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\NOTE_HEADER_VERSION_AND_MERGE.md"
  file_hash: "9feff93dab47bf89d360979f1383da41b05a6dc7ae5c80978f815f773f3c63d2"
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
  file_path_from_root: "docs\doctrine\FLIP\NOTE_HEADER_VERSION_AND_MERGE.md"
  file_hash: "eeef1d2430fe314668555561c0afc3dd66d09c44feb48272f8bee9844442ec49"
  file_path_from_root: "docs\doctrine\FLIP\NOTE_HEADER_VERSION_AND_MERGE.md"
  file_hash: "ec9e5918b34205b3dc442ea8c54f82b1eed86a58dc13255adfc37652b1691cd5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for NOTE_HEADER_VERSION_AND_MERGE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "note_header_version_and_mergemd"]
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
file_path_from_root: docs/doctrine/FLIP/NOTE_HEADER_VERSION_AND_MERGE.md
file.last_modified_system_version: "4.0.17"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/FLIP/NOTE_HEADER_VERSION_AND_MERGE.md
---
# NOTE: FLIP Header version and 3.x → 4.0.x merge

**Purpose:** Reminder for Cursor and contributors when editing files or merging legacy header docs.

---

## 1. Set `file.last_modified_system_version` when editing

When you **add or edit** a FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header) in any file, set:

- **`file.last_modified_system_version`** to the **Lupopedia version in which the file’s content was last changed** (not the version when the header was generated). If you edit the file in 4.0.17, use 4.0.17; if the file was last touched in 4.0.16 and you only regenerate the header, keep 4.0.16.

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
