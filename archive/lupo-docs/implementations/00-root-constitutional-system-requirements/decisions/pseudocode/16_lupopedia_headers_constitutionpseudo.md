---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260405211127"
  file_path_from_root: "lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/16_lupopedia_headers_constitution.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/16_lupopedia_headers_constitution.pseudo.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: pseudocode
  artifact_kind: constitution_shorthand
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# PRD 16 shorthand — LUPOPEDIA HEADERS

**Canonical:** [PRD 16](../../../../prd/16_lupopedia_headers.md) · [LUPOPEDIA_HEADERS/README](../../../../doctrine/LUPOPEDIA_HEADERS/README.md)

## Applicability

- **Required** on **in-scope authored** Markdown/docs/source the project treats as canonical (not on every byte: binaries, generated TOON/CSV, `node_modules`, etc. — see PRD 16 **Header applicability and scope**).

## Minimum expectations (typical Markdown)

| Field / block | Rule |
|---------------|------|
| **`file_path_from_root`** | Repo-relative path to **this** file — **no** leading **`/`**. |
| **`when_updated` / `last_modified_utc`** | **14-digit BIGINT UTC** from **`python lupo-bin/tick.py`** batch — **no** guessed time. |
| **`author`** | Structured **`type`**, **`id`**, **`name`** (numeric **`id`** aligns with actor registry for actors). |
| **`artifact_type` / `artifact_kind` / `purpose` / `tags`** | Declare what the file **is**. |
| **`lupopedia.edges`** | Preferred for cross-links; do not rely on deprecated **`context_id`** as primary. |
| **`lupopedia.footer`** | **`last_verified`**, **`verified_by`** when the file was reviewed. |

## `content_id`

- **`lupo_contents.content_id`** — assigned on **`import_content.py`** (**`calculate_content_id()`**), **not** hand-matched to **`prd_id`**.
- Optional **`--write-back`** persists into file YAML after successful upsert.

## Pseudocode (`decisions/pseudocode/`)

- **`*.pseudo.md`**, **`*.pseudo.php`**, **`*.pseudo.txt`** — **must** carry headers (**PRD 17**) including **`file_path_from_root`** for external AI handoff.

## Tooling

- Validate: **`python lupo-scripts/validate_lupopedia_headers_universal.py`**
- Import + metadata sync: **`import_content.py`**, **`lupo-scripts/lib/header_db_sync.py`**
