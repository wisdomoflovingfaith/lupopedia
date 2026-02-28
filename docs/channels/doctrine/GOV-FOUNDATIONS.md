# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\doctrine\GOV-FOUNDATIONS.md"
  file_hash: "406a48a7af4cc1b4c0fb8bfd07c27fa4770b9fd6f34f6f26a1b756e68b7fdf5d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for GOV-FOUNDATIONS.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "gov-foundationsmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "GOV-FOUNDATIONS.md"
file.last_modified_system_version: 4.2.2
GOV-AD-PROHIBIT-001: true
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "GOV-FOUNDATIONS: Block model vs Dreaming model; structural vs interpretive layers."
tags:
  categories: ["doctrine", "governance", "foundations"]
  collections: ["core-docs", "governance"]
  channels: ["dev", "public"]
file:
  title: "Governance Foundations"
  description: "Block model (structural) vs Dreaming model (interpretive); authoritative layer for system behavior."
  version: 1.0.0
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Governance Foundations

---

## Block Model (Structural Layer) vs Dreaming Model (Interpretive Layer)

- **Block model** = structural truth. It comprises GOV events (`lupo_gov_events`), dependencies (`lupo_gov_event_dependencies`), conflicts (`lupo_gov_event_conflicts`), and logs (`lupo_migration_log`). This layer is append-only and immutable for historical rows. It is the source of truth for migrations, controller behavior, and system correctness.

- **Dreaming model** = meaning-making truth. It comprises interpretation, coherence, and narrative over GOV events. It does not change GOV rows. It is stored in separate narrative artifacts and may use conceptual metadata (`dream_depth`, `coherence_score`, `narrative_thread_id`) as defined in GOV-LILITH-0001.

**Both layers coexist.** Only the **block layer** is authoritative for system behavior. The Dreaming layer is advisory for UI, analytics, and narrative tools.

---

## Three-Layer Model (Block, Dreaming, Witness)

The Lupopedia governance system operates with three distinct layers:

- **Block Layer**: Authoritative structural layer that defines system behavior, schema, migrations, and execution rules. This layer affects system behavior and is the source of truth for all operations.

- **Dreaming Layer**: Interpretive narrative layer that provides meaning-making, coherence interpretation, and contextual understanding over immutable governance events without modifying structural truth.

- **Witness Layer**: Meta-layer that observes both Block and Dreaming layers, providing meta-awareness and integration without modification or execution authority.

Only the Block layer affects system behavior. Dreaming and Witness layers provide interpretation and meta-awareness respectively.

See: [GOV-LILITH-0001_dreaming_overlay.md](GOV-LILITH-0001_dreaming_overlay.md), [GOV-INTEGRATION-0001_witness_layer.md](GOV-INTEGRATION-0001_witness_layer.md), [it_from_gov.md](../dev-teams/governance/it_from_gov.md).
