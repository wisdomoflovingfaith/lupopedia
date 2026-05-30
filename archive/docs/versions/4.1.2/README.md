---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/versions/4.1.2/README.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.2/README.md"
  status: "active"
  when_updated: "20260415223156"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/version-4-1-2-readme.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/4_1_2_readme"
  artifact_type: version-doc
  artifact_kind: version_specific
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: 5164057115986854155
  content_parent_id: 8067324253853516193
  content_slug: "version-4-1-2-readme"
  default_collection_id: null
  lupopedia.schema: version-doc
  title: "Lupopedia 4.1.2 — Active Version"
  summary: "Active working folder for Lupopedia version 4.1.2. Contains consolidated changelog, backlog, and open questions from 4.0.99 through 4.1.1."
---
# Lupopedia 4.1.2

**Status:** Active working version  
**Start date:** 2026-04-15  
**Active version policy:** Patch family — any change is allowed within 4.1.x. Validator accepts any declared `4.1.x` string; legacy `pk_*` YAML keys are WARN for `4.1.0`–`4.1.2` and ERROR for `4.1.3`+ (PRD 16 §11, §15.4).

---

## Folder Contents

| File | Purpose |
|------|---------|
| `README.md` | This file |
| `CHANGELOG.md` | Consolidated changelog from 4.0.99 → 4.1.2 |
| `TODO.md` | Active work items |
| `status/open_questions.md` | Unresolved questions |

---

## Active Work Focus

1. **Transcript capture** — agent stdout → database (`lupo_dialog_messages`)
2. **Memory graph edges** — real relationships between PRDs via sidecar
3. **THOTH worker** — automated DB polling and truth validation
4. **ANUBIS** — orphan file processor

---

## Version Policy

- All changes are patches until 4.2.0
- Validator accepts any `4.1.x` family string; migration rules (e.g. `pk_*`) depend on the patch number
- `pk_*` aliases rejected in headers claiming **4.1.3**+; `dialog_transcript` cutoff per migration guide §3
- `4.2.0` is the first version requiring a formal upgrade path
- See PRD 16 §15 for full version policy

---

## Archived Versions

| Version | Status | Notes |
|---------|--------|-------|
| `../4.0.99/` | Archived (read-only) | Header standardization, constitutional limits |
| `../4.1.0/` | Archived (read-only) | atoms_toon validation, root .md migration |
| `../4.1.1/` | Archived (read-only) | PRD 16 split, stabilization refinements |

---

## Key Files and Tools

- `scripts/validate_lupopedia_headers_universal.py` — canonical validator (v4.1.2)
- `scripts/lib/header_spec_v3_1.py` — 22-field canonical order
- `docs/prd/16_lupopedia_headers.md` — normative spec
- `docs/prd/16_lupopedia_headers_migration.md` — migration rules
- `memory/atoms/lupopedia_global_constants.atom.toon` — global constants
