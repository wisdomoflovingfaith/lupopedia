# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\FLP_CHANNEL_666.md"
  file_hash: "f7e52d756809d7d0447ae9e1c65dadd01f909bba3b3b379c0e9611fd7e048f7c"
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
  file_path_from_root: "docs\doctrine\FLIP\FLP_CHANNEL_666.md"
  file_hash: "a95fae02b03b51f3b9eb5509d264215d7fef4d9e365644879d9b77f927a9e608"
  file_path_from_root: "docs\doctrine\FLIP\FLP_CHANNEL_666.md"
  file_hash: "0a377d4f9b8553dd14f07a3320f4412459796a1fcfd08305c4036fd6a50b8636"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLP_CHANNEL_666.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "flp_channel_666md"]
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
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLP_CHANNEL_666.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 666
tags: ["channel", "quarantine", "forbidden", "anubis"]
mood_rgb: "000000"
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/FLIP/FLP_CHANNEL_666.md
---
# FLP — Channel 666 (ANUBIS Quarantine)

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Context:** Channel 666 is the ANUBIS Quarantine channel. Banned and rejected messages route here.

---

## 1. Purpose

Channel 666 receives messages from banned actors and content rejected by ANUBIS. References to legacy channel 66 resolve to 666 via lupo_anubis_redirects.

---

## 2. lupo_anubis_redirects

- **Redirect:** table `lupo_channels`, old_id 66 → new_id 666.

---

## 3. lupo_contents and lupo_edges

- Channel 666 content (e.g. FLP_CHANNEL_666.md) has HAS_CONTENT edges to channel 666, 0, and 51 per doctrine.