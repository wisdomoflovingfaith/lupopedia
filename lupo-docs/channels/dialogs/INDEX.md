# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\dialogs\INDEX.md"
  file_hash: "327afe859270d642925416614598a83c5911b3f6b45df2a3b5edcab8f14fdc80"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\dialogs\INDEX.md"
  file_hash: "89e3cac8d27cfbbe6f8ddda1de947e6ac334bf46636e196c6282d144f0d669d6"
  file_path_from_root: "docs\channels\dialogs\INDEX.md"
  file_hash: "85120878ade1f003fc9c8fec542602d05ed99abb438abfe244387057f2e6d430"
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
  tags: ["docs", "channels", "dialogs", "indexmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
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
  message: "Created channel index for dialogs documentation organization."
tags:
  categories: ["documentation", "index", "channels"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "dialogs Channel Index"
  description: "Index for dialogs channel documentation"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# dialogs Channel Index

Purpose: DialogFS subsystem documentation and dialog workflows.

Files:
- [agents/DIALOG_HISTORY_SPEC.md](agents/DIALOG_HISTORY_SPEC.md)
- [agents/INLINE_DIALOG_SPECIFICATION.md](agents/INLINE_DIALOG_SPECIFICATION.md)
- [agents/THREAD_LEVEL_DIALOG_SPEC.md](agents/THREAD_LEVEL_DIALOG_SPEC.md)
- [architecture/CHANNEL_DIALOG_AGENT_WORKFLOWS.md](architecture/CHANNEL_DIALOG_AGENT_WORKFLOWS.md)
- [architecture/CHANNEL_DIALOG_SCHEMA_REVIEW.md](architecture/CHANNEL_DIALOG_SCHEMA_REVIEW.md)
- [architecture/DIALOGS_AND_CHANNELS.md](architecture/DIALOGS_AND_CHANNELS.md)
- [dev/DIALOG_SYSTEM_FULL_IMPLEMENTATION.md](dev/DIALOG_SYSTEM_FULL_IMPLEMENTATION.md)
- [dev/DIALOG_SYSTEM_IMPLEMENTATION_PLAN.md](dev/DIALOG_SYSTEM_IMPLEMENTATION_PLAN.md)
- [DIALOG_SYSTEM_IMPLEMENTATION_COMPLETE.md](DIALOG_SYSTEM_IMPLEMENTATION_COMPLETE.md)

Related Channels:
- [agents](../agents/README.md)
- [doctrine](../doctrine/README.md)
- [architecture](../architecture/README.md)