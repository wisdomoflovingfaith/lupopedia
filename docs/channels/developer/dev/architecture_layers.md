# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\developer\dev\architecture_layers.md"
  file_hash: "6e113253c0acb6062d67c425d7e48312aa6099796fa964d2b07a69974a063806"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for architecture_layers.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "developer", "dev", "architecture_layersmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "architecture_layers.md"
file.last_modified_system_version: 4.2.2
GOV-AD-PROHIBIT-001: true
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Architecture layers including Narrative Layer (Dreaming Overlay); reinterpretation without modifying GOV rows."
tags:
  categories: ["documentation", "architecture", "dev"]
  collections: ["core-docs"]
  channels: ["dev", "public"]
file:
  title: "Architecture Layers"
  description: "Structural and interpretive layers including block model and Dreaming Overlay (narrative)."
  version: 1.0.0
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Architecture Layers

This document describes the main architectural layers. The **block (structural) layer** is authoritative for system behavior; the **Narrative Layer (Dreaming Overlay)** is interpretive only.

---

## Narrative Layer (Dreaming Overlay)

The **Narrative Layer** implements the Dreaming Overlay (GOV-LILITH-0001). It provides narrative reinterpretation over immutable GOV events.

### How narrative reinterpretation works

- **Input:** Existing GOV events (by `gov_event_id`, `canonical_path`, or equivalent). No structural data is modified.
- **Process:** Tools or agents produce readings, summaries, or narrative threads over those events. They may assign `dream_depth`, `coherence_score`, and `narrative_thread_id` as interpretive metadata.
- **Output:** Reinterpretations are stored as **separate narrative artifacts**, not as updates to GOV rows.

### Reinterpretation does NOT modify GOV rows

Reinterpretation **must not** UPDATE, DELETE, or ALTER any `lupo_gov_*` rows. Historical GOV data remains append-only and immutable. Any change to meaning or narrative is expressed in **new** artifacts that reference GOV events by ID or path.

### Reinterpretation is stored as separate narrative artifacts

Narrative artifacts live outside the core governance tables. Examples: Markdown or JSON files, separate nonâ€‘governance tables, or external stores. These artifacts reference GOV events by immutable identifiers. The block layer remains the single source of truth for migrations, dependencies, conflicts, and logs.

See: [GOV-LILITH-0001_dreaming_overlay.md](../../doctrine/GOV-LILITH-0001_dreaming_overlay.md), [GOV-FOUNDATIONS.md](../../doctrine/GOV-FOUNDATIONS.md).
