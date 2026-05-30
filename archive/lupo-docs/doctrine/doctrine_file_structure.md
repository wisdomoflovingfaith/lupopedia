---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/DOCTRINE_FILE_STRUCTURE.md"
  web_path: null
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\DOCTRINE_FILE_STRUCTURE.md"
  file_hash: "6ab181b553d1c9ccfd95fcb987ee109a2020eb0e0e8172fb9d9abe49c2802a28"
  file_path_from_root: "lupo-docs\doctrine\DOCTRINE_FILE_STRUCTURE.md"
  file_hash: "511affbd6a7c163fcf94a17afbb907e6a392c5a1d978590c37980b8d88d9591f"
  last_updated_utc: "20260228"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for DOCTRINE_FILE_STRUCTURE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "doctrine_file_structuremd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.88"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "cursor"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
lupopedia.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/DOCTRINE_FILE_STRUCTURE.md
file.last_modified_system_version: "4.0.88"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_vector: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/DOCTRINE_FILE_STRUCTURE.md
---

FILE: lupo-docs/doctrine/DOCTRINE_FILE_STRUCTURE.md
TYPE: md

# Doctrine File Structure (MANDATORY)

Lupopedia uses a **canonical doctrine file** plus optional **versioned** and **transitional** revisions. All contributors — including IDE AI tools — must follow these rules.

---

## Canonical Doctrine File (always present)

```
lupo-docs/doctrine/LUPOPEDIA_DOCTRINE.md
```

This file is the **single source of truth** for all architectural rules. IDE AI tools must always read this file first for active doctrine.

---

## Versioned Doctrine Files (optional, historical)

```
lupo-docs/doctrine/LUPOPEDIA_DOCTRINE_v1.1.md
lupo-docs/doctrine/LUPOPEDIA_DOCTRINE_v1.2.md
lupo-docs/doctrine/LUPOPEDIA_DOCTRINE_v1.3.md
...
```

These represent **frozen snapshots** of doctrine at specific versions. They are for history and reference only, not active rules.

---

## Updated / Revised Doctrine Files (optional, transitional)

```
lupo-docs/doctrine/LUPOPEDIA_DOCTRINE_UPDATED_v1.1.md
lupo-docs/doctrine/LUPOPEDIA_DOCTRINE_REVISED_v1.1.md
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
