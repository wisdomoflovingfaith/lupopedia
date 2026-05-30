---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/DIRECTORY_STRUCTURE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/DIRECTORY_STRUCTURE_DOCTRINE.md"
  status: "active"
  when_updated: "20260403114427"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: directory_structure
  channel_key: null
  federation_node_id: 0
  thread_id: "doctrine-directory-structure"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: DIRECTORY_STRUCTURE_DOCTRINE — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/DIRECTORY_STRUCTURE_DOCTRINE.md

# Directory structure doctrine (4.0.x)

## Purpose

This doctrine states **where things live** in the Lupopedia 4.0.x repository. It must agree with **[PRD 29](../prd/29_project_structure.md)** (authoritative directory table). If this file and PRD 29 disagree, **PRD 29 wins** until this doctrine is updated.

**System version:** 4.0.x (see `GLOBAL_CURRENT_LUPOPEDIA_VERSION` in `lupo-config/global_atoms.yaml`). Do not describe the tree as "3.0.x".

## Core rules

1. **Prefix discipline:** Functional top-level directories use the `lupo-` prefix (see PRD 29).
2. **Documentation lives under `lupo-docs/`**, not a legacy top-level **docs** directory at repository root (without the `lupo-` prefix).
3. **Schema and seeds** live under `lupo-database/` (install SQL, seed, TOON exports per doctrine). There is no parallel "199-table" story in a fictional database directory tree outside this repo layout.
4. **Channels on disk:** Active work uses `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`. Pre–4.0.93 layouts are archived under `lupo-channels_before_4_0_93/` (read-only). Channels were **not** removed in 4.0.x; they were **relocated and formalized** (PRD 29, PRD 02, PRD 17).
5. **Headers:** Use **LUPOPEDIA HEADERS** (`lupopedia.headers` / `lupopedia.footer` / `lupopedia.edges`) only. Do not introduce new `wolfie.headers` blocks.

## High-level map (summary)

| Area | Role |
|------|------|
| `lupo-docs/` | PRDs, doctrine, implementations, database table docs, versioned docs |
| `lupo-docs/prd/` | Product requirements (**what**) |
| `lupo-docs/doctrine/` | Constitutional and system doctrine (**why / policy**) |
| `lupo-docs/implementations/` | Implementation traces, edges, status (**how**, PRD 31) |
| `lupo-channels/` | Active coordination threads (per PRD 29 channel strategy) |
| `lupo-agents/` | Agent configuration (e.g. per-agent `agent.json`, prompts) |
| `lupo-actors/` | Per-actor resources (not a substitute for `lupo-docs/doctrine/`) |
| `lupo-includes/` | PHP runtime, modules, themes (no Composer in core) |
| `lupo-scripts/` | Python/shell tooling (schema, validators, ghost scans) |
| `lupo-database/` | Install SQL, seed, CSV/TOON exports |
| `lupo-bin/` | CLI utilities (e.g. version bump, temporal anchor) |

The full sortable table of **`lupo-*` roots** is maintained in **PRD 29**; do not duplicate it here unless this doctrine is updated in the same commit as PRD 29.

## Historical note (3.0.x)

Older narrative that referenced a non-`lupo` docs tree, claimed channels were removed from the filesystem, or described a third-generation (pre-4.0) layout is obsolete. A **read-only snapshot** of the pre-exorcism file is kept at:

`lupo-docs/versions/3.0.x/doctrine/DIRECTORY_STRUCTURE_DOCTRINE.md`

Use it only for archaeology, not for current layout decisions.

## Verification

- Run: `python lupo-scripts/find_version_ghosts.py --require-zero` (after doctrine passes are applied).
- Structural audits: `python lupo-scripts/generate_directory_tree.py` when required by versioning doctrine.

This output complies with Lupopedia Constitutional Root Rules.
