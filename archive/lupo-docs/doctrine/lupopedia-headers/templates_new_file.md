---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/TEMPLATES_NEW_FILE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/TEMPLATES_NEW_FILE.md"
  status: "active"
  when_updated: "20260410164844"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/2026/04/templates-new-file.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/prd_files/templates-new-file"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: "headers-templates-v3"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: "Copy-paste templates for new files (LUPOPEDIA HEADERS v3)"
  summary: ""
---
# Copy-paste templates for new files (LUPOPEDIA HEADERS v3)

**Constitutional rule:** every **new** hand-authored file in scope **MUST** include the full **`lupopedia.headers`** dense envelope (**PRD 16** **§4.2**, **§4.3** — **22** canonical keys, **25-line** Markdown grid). Keys are **never** omitted; use **`''`** or YAML **`null`** only as **sentinels**.

**PRD 16 freeze (4.1.3):** declare **`header_format_version: "4.1.3"`** on new files; evolution resumes **4.1.4+** after the Crafty Syntax human live-help baseline. Copy-paste blocks below may show **legacy** shapes for migration context only — prefer **`python lupo-scripts/add_lupopedia_header_to_file.py`** plus **[lupopedia_headers_format.md](lupopedia_headers_format.md)** for a current **4.1.3** envelope.

**Timestamps:** run `python lupo-bin/tick.py` once per batch, then paste **`current_utc`** (or `python lupo-bin/echo_anchor_utc.py`) into `when_updated` and `last_modified_utc`. Do **not** invent UTC.

**Automation:** `python lupo-scripts/add_lupopedia_header_to_file.py path/to/file.py|md [--create]`

## Markdown (`.md`) shape


**Important:**
Never include `lupopedia.edges` in the header block. If needed, add it as a separate block at the very bottom of the file, after the main content. The header is for metadata only.

Lines **23–24** blank; line **25** closing `---`; body from line **26**.

```markdown
---
lupopedia.headers:
  header_format_version: "4.0.98"
  lupopedia.schema: documentation
  when_updated: "YYYYMMDDHHIISS"
  file_path_from_root: "path/from/repo/root.md"
  web_path: "http://www.lupopedia.com/lupopedia/path/from/repo/root.md"
  last_modified_utc: "YYYYMMDDHHIISS"
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "lupo-memory/development/canonical/YYYY/MM/slug.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ""
  content_id: null
  prd_id: null
  prd_slug: ""
  title: "Human title"
  status: "active"
  parent_prd: ""
  transcript_jsonl: "0/development/prd_files/slug"


---
```

## Python (`.py`) shape

Optional shebang line **1**; then **25** lines: open `# -----`, `lupopedia.headers:` + **20** keys, **two blank lines** (no `#`), close `# -----`; code starts **immediately** after.

```python
#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.0.98"
#   lupopedia.schema: implementation
#   when_updated: "YYYYMMDDHHIISS"
#   file_path_from_root: "lupo-scripts/example.py"
#   web_path: "http://www.lupopedia.com/lupopedia/lupo-scripts/example.py"
#   last_modified_utc: "YYYYMMDDHHIISS"
#   federation_node_id: 0
#   channel_key: "development"
#   trust_tier: "canonical"
#   memory_key: "lupo-memory/development/canonical/YYYY/MM/slug.toon"
#   artifact_type: implementation
#   artifact_kind: tool
#   thread_id: ""
#   content_id: null
#   prd_id: null
#   prd_slug: ""
#   title: "Example"
#   status: "active"
#   parent_prd: "16"
#   transcript_jsonl: "0/development/prd_files/slug"


# ---------------------------------------------------------------------
```

## Out of scope (no YAML header block)

Generated exports, binaries, vendor trees, most `*.json` / `*.toon.json`, lockfiles — see **PRD 16** header applicability.

## Validate

```bash
python lupo-scripts/validate_lupopedia_headers_universal.py path/to/file.md
```
