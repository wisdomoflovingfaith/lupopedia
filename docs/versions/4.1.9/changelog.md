---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: docs/versions/4.1.9/changelog.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.9/changelog.md
  status: active
  when_updated: "20260523043918"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/4_1_9_changelog_buffer
  artifact_type: version-doc
  artifact_kind: version_specific
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: version-doc
  prd_cluster: 00_A_16_C
  title: Lupopedia 4.1.9 changelog (unreleased)
  summary: "Header format 4.1.9 (22 fields); edges_toon, channel_index, source_timestamp; validators and migration script"
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---
# Lupopedia 4.1.9 -- Changelog

## 4.1.9 (unreleased)

### Version and atoms

- Canonical version atoms updated: `LUPOPEDIA_VERSION` and `current_lupopedia_version` set to **4.1.9**.
- `current_header_format_version` set to **4.1.9** in `memory/channels/atoms/lupopedia_global_constants.atom.toon`.
- Version folder `docs/versions/4.1.9/` created; unfinished work carried from `docs/versions/4.1.8/todo.md`.

### LUPOPEDIA HEADERS (PRD 16)

- Header envelope expanded from **19** to **22** canonical fields (`header_format_version: "4.1.9"`).
- **New fields:**
  - **`edges_toon`** (field 20) -- cross-origin graph stitching sidecar (`.edges.toon`); required for external/imported artifacts.
  - **`channel_index`** (field 21) -- origin platform (`lupopedia`, `patreon`, `website`, `facebook`, `blog`, `external`, `imported`).
  - **`source_timestamp`** (field 22) -- immutable ISO 8601 origin time in source system (distinct from `when_updated`).
- **Removed from header YAML (forbidden):** `content_id`, `content_parent_id`, `default_collection_id`, `content_slug`, `pk_*`, `prd_id`, `prd_slug`, `parent_prd`. Identity uses `path_from_lupopedia_root`, `memory_toon`, and import tooling.

### Tooling

- **`scripts/lib/header_spec_v3_1.py`** -- 22-field order, v4.1.9 validation helpers (`validate_edges_toon`, `validate_channel_index`, `validate_source_timestamp`).
- **`scripts/validate_lupopedia_headers_universal.py`** -- enforces removed fields and external-artifact rules.
- **`scripts/add_lupopedia_header_to_file.py`** -- emits all 22 fields; `--external`, `--channel-index`, `--source-timestamp` for imported artifacts.
- **`scripts/migrate_headers_4_1_9.py`** -- migrates existing headers to 4.1.9 (repo-native defaults + external options).

### Documentation

- **`docs/prd/16_C-i_LUPOPEDIA_HEADERS.md`** -- section 4.2 updated for v4.1.9 field order and new field definitions.
- **`docs/doctrine/lupopedia-headers/lupopedia_headers_format.md`** -- 22-field table and examples.
- **`docs/doctrine/lupopedia-headers/templates_new_file.md`** -- copy-paste templates at 4.1.9.
