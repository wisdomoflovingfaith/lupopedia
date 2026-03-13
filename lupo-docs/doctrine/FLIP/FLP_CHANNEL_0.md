# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\FLP_CHANNEL_0.md"
  file_hash: "ed54bcdd0b3f4808f4b14aedc9166aa66528798ff5d1deff5efc40b86d760db5"
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
  file_path_from_root: "docs\doctrine\FLIP\FLP_CHANNEL_0.md"
  file_hash: "f6c620dc4965488d532fc4d3aef8740b837b0b58435b8d71c4e46d3ecd715551"
  file_path_from_root: "docs\doctrine\FLIP\FLP_CHANNEL_0.md"
  file_hash: "b29a426ffd5e1cc170e8ba5c68b0b55f4f9f8ac44c965545ab5e389856449a91"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLP_CHANNEL_0.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "flp_channel_0md"]
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
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLP_CHANNEL_0.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 0
tags: ["channel", "kernel", "system", "flip"]
mood_rgb: "FFFFFF"
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/FLIP/FLP_CHANNEL_0.md
---
# FLP — Channel 0 (System Kernel)

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Context:** Channel 0 is the System Kernel channel. Reserved for bootstrapping, migrations, and OS-level events.

---

## 1. Purpose

Channel 0 (`system/kernel`) is the root channel. All kernel-level content, doctrine, and system identity is associated with this channel. Content on channel 0 is visible to system resolvers and bootstrapping logic.

---

## 2. lupo_contents and lupo_edges

- **lupo_contents:** Doctrine files and kernel content use `file_path_from_root` for path lookup.
- **lupo_edges:** HAS_CONTENT edges link channel 0 to content. `left_object_type='channel'`, `left_object_id=0`, `right_object_type='content'`.

---

## 3. Registry

- **lupo_registry:** `entity_type='channel'`, `entity_index=0`, `entity_key='system/kernel'`.
