# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\dev-teams\governance\overview.md"
  file_hash: "c23cc227adbe9ac2bdee22485f90d39d1aa897edb258ce3da44dcd587c329c47"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\dev-teams\governance\overview.md"
  file_hash: "0fd5def27c68883ea827426015dc59dd9ec955fd2bf5c11eb739ff4ed033e342"
  file_path_from_root: "docs\channels\dev-teams\governance\overview.md"
  file_hash: "8e1e9fa89a49e63b9f3d5e8bbeaab6db1be80bc1cd56beaff36e39389fc867f4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for overview.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "dev-teams", "governance", "overviewmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "overview.md"
file.last_modified_system_version: 4.2.2
GOV-AD-PROHIBIT-001: true
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Governance overview with Interpretive Metadata (Non-Schema) subsection."
tags:
  categories: ["documentation", "governance"]
  collections: ["core-docs", "governance"]
  channels: ["dev", "public"]
file:
  title: "Governance Overview"
  description: "Overview of Lupopedia governance: structural block model, Dreaming Overlay, and interpretive metadata."
  version: 1.0.0
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Governance Overview

Governance in Lupopedia is split into a **structural (block) layer** and an **interpretive (Dreaming) layer**. The block layer is authoritative for system behavior; the interpretive layer supports meaning-making and narrative without changing GOV data.

- **Block model:** [it_from_gov.md](it_from_gov.md), [GOV-FOUNDATIONS.md](../../doctrine/GOV-FOUNDATIONS.md)
- **Dreaming Overlay:** [GOV-LILITH-0001_dreaming_overlay.md](../../doctrine/GOV-LILITH-0001_dreaming_overlay.md)
- **Registry:** [REGISTRY.md](REGISTRY.md)

---

## Interpretive Metadata (Non-Schema)

The following conceptual fields are used for narrative and coherence interpretation over GOV events. They are **not** database columns and must **not** be added to `lupo_gov_*` or any core schema.

| Field | Symbol | Description |
|-------|--------|-------------|
| `dream_depth` | **d** | Interpretive depth of a narrative reading over one or more GOV events. |
| `coherence_score` | **Î“** | Score of how coherent a narrative thread is with respect to a set of GOV events. |
| `narrative_thread_id` | â€” | Identifier for a narrative thread that groups interpretations across GOV events. |

**Clarification:** These are **not** database columns. They may be used by UI, analytics, or narrative tools in configs, JSON, or separate narrative artifact stores. Authoritative system behavior is determined only by the block-model GOV tables.

See: [GOV-LILITH-0001_dreaming_overlay.md](../../doctrine/GOV-LILITH-0001_dreaming_overlay.md).