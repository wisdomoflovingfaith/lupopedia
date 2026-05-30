---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260405211127"
  file_path_from_root: "docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/31_implementation_folder_guidelines_constitution.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/31_implementation_folder_guidelines_constitution.pseudo.md"
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
# PRD 31 shorthand — Implementation folder guidelines

**Canonical:** [PRD 31](../../../../prd/31_implementation_folder_guidelines.md) · **Constitution §5.8**

## Directory name (non-negotiable)

```text
docs/implementations/{prd_file_stem}/
```

- **`prd_file_stem`** = basename of **`docs/prd/{prd_file_stem}.md`** **without** **`.md`**, **character-for-character** (no plural drift, no aliases).

## Scaffold

```bash
python scripts/scaffold_implementation.py --prd <n> --title "<slug>"
```

Choose **`--title`** so **`<n>_<slug>`** equals **`prd_file_stem`**.

## Expected tree (product norm)

| Path | Role |
|------|------|
| **`decisions/`** + **`THREAD_INDEX.md`** | Formal decisions |
| **`decisions/pseudocode/`** | **Purpose 1:** `*_constitution.pseudo.md` + **Purpose 2:** `*_design.pseudo.md`, **`*.pseudo.php`** (**PRD 17**) |
| **`questions/`** | **`critical/`**, **`optimization/`**, **`clarification/`** + **`THREAD_INDEX.md`** |
| **`answers/`**, **`comments/`**, **`status/`** | Each with **`THREAD_INDEX.md`** |
| **`authors.md`**, **`edges.md`**, **`README.md`** | Tier 1 five-layer artifacts |

## Question lifecycle

- **Immutable basenames** — state lives in YAML, not renames (**PRD 17** filename UTC tokens).

## Cross-refs

- Headers / import: **PRD 16**
- Channel routing pattern: **PRD 18**, **PRD 36**, **PRD 37** (see PRD 31 table)
