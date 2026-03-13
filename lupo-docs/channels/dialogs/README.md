# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\dialogs\README.md"
  file_hash: "c4b5aa9287782024e3a3e485d122d78e087c6b4236ff2e461aeee34de3a485b7"
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
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\dialogs\README.md"
  file_hash: "6fe7fa6e045e6e0023ef2a8482842754fd91db4e41ad0b06721c7f536ef41e05"
  file_path_from_root: "docs\channels\dialogs\README.md"
  file_hash: "ee4a8b1989fcf2466a2d7485f645038c13e9d6aaeea4700bd32f503faff208c1"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "dialogs", "readmemd"]
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
  message: "Created channel README for dialogs documentation organization."
tags:
  categories: ["documentation", "index", "channels"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "dialogs Channel README"
  description: "Overview for dialogs channel documentation"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# dialogs Channel README

Purpose: DialogFS subsystem documentation and dialog workflows.

Top-Level Contents:
- agents/
- architecture/
- dev/
- DIALOG_SYSTEM_IMPLEMENTATION_COMPLETE.md

Full file list: [INDEX.md](INDEX.md)

Related Channels:
- [agents](../agents/README.md)
- [doctrine](../doctrine/README.md)
- [architecture](../architecture/README.md)
