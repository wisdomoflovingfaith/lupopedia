---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/smilies_implementation.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/smilies_implementation.md
  status: deprecated
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/smilies-implementation.toon
  atoms_toon: null
  transcript_jsonl: 0/development/emoji-icons-implementation
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: emoji-icons-implementation
  lupopedia.schema: doctrine
  prd_cluster: null
  title: Smilies (Legacy Crafty Database Model) — DEPRECATED
  summary: Historical record of Crafty Syntax database-driven lupo_smilies; not the Lupopedia emoji model. Replaced by filesystem tokens per EMOJI_AND_SMILIES.md.
---
> **DEPRECATED — Legacy Crafty Syntax Smilies System**
>
> This document describes the **database-driven smilies** implementation **used in Crafty Syntax**. It is **not** the active Lupopedia model.
>
> **Lupopedia** uses a **filesystem-based emoji** system instead. Canonical specification: **[EMOJI_AND_SMILIES.md](EMOJI_AND_SMILIES.md)**.

# Smilies (Legacy Crafty Database Model) — DEPRECATED

## Overview

**Historical:** Crafty Syntax and early Lupopedia planning documents referred to a **`lupo_smilies`** table and code-based insertion (for example `:smile:`). That **legacy system was replaced** by the **filesystem emoji model** documented in **[EMOJI_AND_SMILIES.md](EMOJI_AND_SMILIES.md)** (`::img|foldername|filename::` tokens, **`emoji/`** storage, render-time replacement).

This file is retained **only** as an archive of how the old model **was described**; implementers **must not** treat it as current product truth.

## Database Table: `lupo_smilies` (historical)

**Was used in:** Crafty-era and transitional docs as the store for smilies/emoji icons.

- **Was described as** holding rows with codes, image URLs, and soft-delete flags.
- **Canonical DDL** for any surviving reference trees **was** in **`install_new_lupopedia.sql`** and JSON TOON exports of that era (do not assume the table remains required in current Lupopedia installs; follow **[EMOJI_AND_SMILIES.md](EMOJI_AND_SMILIES.md)** for shipped behavior).

## Features (legacy description)

- **Was:** Codes in content **were** resolved via database lookups.
- **Was:** Admin UIs **were** planned around CRUD on **`lupo_smilies`**.
- **Replaced by:** Filesystem images under **`emoji/`** and token-based insertion (see canonical doc).

## Implementation Details (historical)

- **Was:** PHP **was** expected to load rows from **`lupo_smilies`** and cache them.
- **Was:** Rendering **was** described as replacing codes with `<img>` or Unicode emoji.
- **Replaced by:** Display-time token expansion only; **no** database emoji registry in the canonical Lupopedia model.

## Channel Discussion (archive)

Channel 42 / thread **`emoji-icons-implementation`** **was** used for early coordination. Current emoji/smilies work **is** governed by **[EMOJI_AND_SMILIES.md](EMOJI_AND_SMILIES.md)**.

---

## Related Files (historical pointers)

- **Legacy table name:** `lupo_smilies` (Crafty-era; **not** the filesystem canonical path).
- **Current model:** **[EMOJI_AND_SMILIES.md](EMOJI_AND_SMILIES.md)** — **`emoji/`**, **`::img|foldername|filename::`**.

---

## Next Steps (for readers)

- **Do not** implement new features from this document.
- **Do** read **[EMOJI_AND_SMILIES.md](EMOJI_AND_SMILIES.md)** for validation, rendering, storage, and anti-patterns aligned with UTF-8 structured write enforcement.
