# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "dialogs_old_replaced_by_channels\operations\2026-01-22_docs_channels_reorg.md"
  file_hash: "2252d6f5b5fef15616c63a9034d2d43155a664877d867a4cb02ae179f6012711"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 2026-01-22_docs_channels_reorg.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["dialogs_old_replaced_by_channels", "operations", "2026-01-22_docs_channels_reorgmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
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
  message: "Documentation channel reorganization completed for docs/ per channel taxonomy."
tags:
  categories: ["documentation", "operations", "reorganization"]
  collections: ["operations"]
  channels: ["operations", "documentation"]
file:
  title: "Docs Channel Reorganization Log"
  description: "Moved documentation into channel folders and added indexes."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Docs Channel Reorganization Log

Date: 2026-01-22

What changed:
- Moved all Markdown documentation under `docs/channels/` using the approved channel taxonomy.
- Split `docs/core/` into architecture, doctrine, developer, and overview channels with legacy-core retention for duplicate doctrine files.
- Consolidated dialog-related documentation into `docs/channels/dialogs/`.
- Added channel-level `README.md` and `INDEX.md` files for each channel.
- Updated internal documentation links to reflect new channel paths.

Why:
- Align documentation organization with channel-based structure defined in `docs/core/DIRECTORY_STRUCTURE.md`.
- Improve discoverability by channel and formalize per-channel indexes.

Files involved:
- `docs/channels/` (all channel folders and moved documentation files)
- `docs/channels/**/README.md`
- `docs/channels/**/INDEX.md`
- `CHANGELOG.md`

Issues noted:
- Existing duplicate doctrine files in `docs/core/` and `docs/doctrine/` retained by placing core copies under `docs/channels/doctrine/legacy-core/`.
