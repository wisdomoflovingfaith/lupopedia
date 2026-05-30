---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260405211127"
  file_path_from_root: "docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/26_five_layer_documentation_architecture_constitution.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/26_five_layer_documentation_architecture_constitution.pseudo.md"
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
# PRD 26 shorthand — Five-layer documentation architecture

**Canonical:** [PRD 26](../../../../prd/26_five_layer_documentation_architecture.md)

## Two tiers (do not conflate)

| Tier | Authority | Source | Examples |
|------|-----------|--------|----------|
| **Tier 1 — Authored docs** | Filesystem / git | Humans edit files → optional **`import_content.py`** mirror | PRDs, implementations, doctrines, **`decisions/`** threads |
| **Tier 2 — Runtime content** | Database | Tracking, visits, discovered edges (**PRD 28**) | **`lupo_visits`**, **`lupo_paths`**, Eye-discovered graph |

**Tier 1** prescribes what **should** be built; **Tier 2** describes what **happened** in production data.

## Five layers (Tier 1)

| Layer | Question | Location (typical) |
|-------|----------|-------------------|
| **WHAT** | What to build? | **`docs/prd/`** |
| **HOW** | How to build? | **`docs/implementations/{prd_file_stem}/`** |
| **WHY** | Why this decision? | **`decisions/`**, **`questions/`**, **`answers/`**, **`comments/`** (per context) |
| **WHO** | Who contributed? | **`authors.md`** (numeric **`actor_id`** per registry) |
| **WHERE** | How does it connect? | **`edges.md`**, **`lupopedia.edges`** in headers |

## Implementation folder extras (PRD 26 + 31)

- **`doc_arch_version`** on implementation README front matter (compatibility with **`header_format_version`** — see PRD 26 §3.2).
- **`parent_edges_ref`** on PRDs points at implementation **`edges.md`** when required by that PRD’s template.

## Authors table rule

- **`authors.md`** uses **numeric `actor_id`** only — resolve slugs via **`database/lupopedia/actors/actor_id/registry.json`**.
