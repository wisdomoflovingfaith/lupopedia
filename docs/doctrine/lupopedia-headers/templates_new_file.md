---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: docs/doctrine/lupopedia-headers/templates_new_file.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia-headers/templates_new_file.md
  status: active
  when_updated: '20260523042341'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/templates-new-file.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/templates-new-file
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: headers-templates-v4-1-9
  lupopedia.schema: doctrine
  prd_cluster: 00_A_16_C
  title: Copy-paste templates for new files (LUPOPEDIA HEADERS v4.1.9)
  summary: '22-field dense envelope; edges_toon, channel_index, source_timestamp'
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---
# Copy-paste templates for new files (LUPOPEDIA HEADERS v4.1.9)

**Constitutional rule:** every **new** hand-authored file in scope **MUST** include the full **`lupopedia.headers`** dense envelope (**PRD 16** section 4.2 -- **22** canonical scalar keys, **25-line** Markdown grid: opening `---`, `lupopedia.headers:`, **22** indented key lines, closing `---`; no blank lines inside the block). Keys are **never** omitted; use **`''`** or YAML **`null`** only as **sentinels**. Canonical key order matches **`scripts/lib/header_spec_v3_1.py`** (`V4_HEADER_KEYS_ORDERED`).

**Normative version:** declare **`header_format_version: "4.1.9"`** on new and migrated files.

**Timestamps:** run `python bin/tick.py` once per batch, then paste **`current_utc`** (or `python bin/echo_anchor_utc.py`) into `when_updated`. Do **not** invent UTC. Use **`source_timestamp`** only for external/imported origin time (ISO 8601).

**Automation:**

- `python scripts/add_lupopedia_header_to_file.py path/to/file.py|md [--create]`
- `python scripts/migrate_headers_4_1_9.py path/to/file.md` (existing files)
- External/imported: add `--external --channel-index patreon --source-timestamp 2026-01-15T12:00:00Z`

## Markdown (`.md`) shape

Never include `lupopedia.edges` in the header block. If needed, add it as a separate block at the very bottom of the file, after the main content.

Line **25** is the closing `---`. The first body line MUST be line **26** (non-whitespace; validators emit **HDR_EMPTY_BODY** if the body is missing).

```markdown
---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: "path/from/repo/root.md"
  web_path: "https://www.lupopedia.com/lupopedia/path/from/repo/root.md"
  status: "active"
  when_updated: "YYYYMMDDHHIISS"
  trust_tier: canonical
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/slug.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/prd_files/slug"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: "00_A_16_C"
  title: "Human title"
  summary: "One-line summary"
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---
```

## Python (`.py`) shape

Optional shebang line **1**; then the PRD 16 **comment-embedded** envelope: open `# -----`, `# lupopedia.headers:`, then **22** `#   key:` lines in **`V4_HEADER_KEYS_ORDERED`** order, close `# -----`.

```python
#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.9"
#   path_from_lupopedia_root: "scripts/example.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/example.py"
#   status: "complete"
#   when_updated: "YYYYMMDDHHIISS"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/example-py.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/example-py"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_key: ""
#   lupopedia.schema: implementation
#   prd_cluster: "00_A_16_C"
#   title: "Example"
#   summary: "Generated header"
#   edges_toon: null
#   channel_index: "lupopedia"
#   source_timestamp: null
# ---------------------------------------------------------------------
```

## External / imported artifact (required fields 20-22)

When `channel_index` is not `lupopedia`:

- `channel_index` -- platform slug (e.g. `patreon`, `imported`)
- `source_timestamp` -- ISO 8601 origin time (immutable), e.g. `2026-01-15T12:00:00Z`
- `edges_toon` -- path ending in `.edges.toon`, e.g. `edges/development/my-thread/1026/04/slug.edges.toon`

Forbidden in all v4.1.9 headers: `content_id`, `content_parent_id`, `default_collection_id`, `content_slug`, `pk_*`, `prd_id`.
