---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260405211127"
  last_modified_utc: "20260405211127"
  file_path_from_root: "lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/26_five_layer_documentation_architecture_constitution.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/26_five_layer_documentation_architecture_constitution.pseudo.md"
  channel_id: 42
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: pseudocode
  artifact_kind: constitution_shorthand
  purpose: "PRD 26 digest — Tier 1 vs Tier 2, five layers, authors/edges (Purpose 1 per PRD 17)"
  tags:
    - pseudocode
    - constitution_shorthand
    - prd_26
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: references
      weight: 0.9
      reason: "Tier 2 governing PRD"
lupopedia.footer:
  last_verified: "20260405211127"
  verified_by:
    actor_id: 102
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
| **WHAT** | What to build? | **`lupo-docs/prd/`** |
| **HOW** | How to build? | **`lupo-docs/implementations/{prd_file_stem}/`** |
| **WHY** | Why this decision? | **`decisions/`**, **`questions/`**, **`answers/`**, **`comments/`** (per context) |
| **WHO** | Who contributed? | **`authors.md`** (numeric **`actor_id`** per registry) |
| **WHERE** | How does it connect? | **`edges.md`**, **`lupopedia.edges`** in headers |

## Implementation folder extras (PRD 26 + 31)

- **`doc_arch_version`** on implementation README front matter (compatibility with **`header_format_version`** — see PRD 26 §3.2).
- **`parent_edges_ref`** on PRDs points at implementation **`edges.md`** when required by that PRD’s template.

## Authors table rule

- **`authors.md`** uses **numeric `actor_id`** only — resolve slugs via **`lupo-database/lupopedia/actors/actor_id/registry.json`**.
