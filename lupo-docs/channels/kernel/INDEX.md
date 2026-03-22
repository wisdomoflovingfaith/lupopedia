# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/kernel/INDEX.md"
  file_hash: "5d8434c082a0c9030f5cb759324872acae800b44b68fc7036e635b8894fc28a9"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\kernel\INDEX.md"
  file_hash: "de2c30e79bb16ab6c86f5dffbef600255e14f434a1c7827ac538a1399b752c50"
  file_path_from_root: "lupo-docs\channels\kernel\INDEX.md"
  file_hash: "acf2976d5913666529ea20bf419ba750d266731c6df82952c3d574658fed4f3e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for INDEX.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "kernel", "indexmd"]
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
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created channel index for kernel documentation organization."
tags:
  categories: ["documentation", "index", "channels"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "kernel Channel Index"
  description: "Index for kernel channel documentation"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# kernel Channel Index

Purpose: Core runtime systems, services, components, and registries.

Files:
- [components/ContinuityValidator.md](components/ContinuityValidator.md)
- [registries/MOOD_AXIS_REGISTRY.md](registries/MOOD_AXIS_REGISTRY.md)
- [services/ACTOR_MOOD_SERVICE.md](services/ACTOR_MOOD_SERVICE.md)
- [services/MOOD_SERVICES_INTEGRATION.md](services/MOOD_SERVICES_INTEGRATION.md)
- [services/MOOD_SERVICES_OVERVIEW.md](services/MOOD_SERVICES_OVERVIEW.md)
- [systems/AFFECTIVE_DISCREPANCY_ENGINE.md](systems/AFFECTIVE_DISCREPANCY_ENGINE.md)
- [systems/CRF_SPECIFICATION.md](systems/CRF_SPECIFICATION.md)
- [systems/EXPERIENCE_LEDGER.md](systems/EXPERIENCE_LEDGER.md)
- [systems/HETERODOX_ENGINE.md](systems/HETERODOX_ENGINE.md)

Related Channels:
- [architecture](../architecture/README.md)
- [schema](../schema/README.md)
- [developer](../developer/README.md)
