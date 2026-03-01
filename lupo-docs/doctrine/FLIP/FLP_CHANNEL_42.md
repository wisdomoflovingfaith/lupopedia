# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\FLP_CHANNEL_42.md"
  file_hash: "12b81da7b433734ca825521e14392ef205e63170ed47c3a21b93db95a7792289"
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
  file_path_from_root: "docs\doctrine\FLIP\FLP_CHANNEL_42.md"
  file_hash: "2399cf4b131d9c14234909e9682c62693c3ff985c9a251b857d1b6b904dea60d"
  file_path_from_root: "docs\doctrine\FLIP\FLP_CHANNEL_42.md"
  file_hash: "54dcff3b994e85cc3d48336fe165b057eea69c533dd9559ed88ea48db3591e37"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLP_CHANNEL_42.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "flp_channel_42md"]
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
file_path_from_root: docs/doctrine/FLIP/FLP_CHANNEL_42.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42
tags: ["channel", "lupopedia-development", "anubis", "flip"]
mood_rgb: "A0D6B4"
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/FLIP/FLP_CHANNEL_42.md
---
# FLP — Channel 42 (Lupopedia Development / ANUBIS)

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Context:** Channel 42 is Lupopedia Development. ANUBIS-related content, FLIP doctrine, and kernel agent dialog reside here.

---

## 1. Purpose

Channel 42 (`lupopedia-development`) hosts Crafty Syntax and Lupopedia development content. FLIP/FLP doctrine, path lookup chain (content → lupo_edges HAS_CONTENT → channel_id), and ANUBIS adoption logic are documented and seeded here.

---

## 2. ANUBIS

ANUBIS resolves orphaned dialog messages and adopts them into channel 42 when appropriate. Banned actors (e.g. actor 999) are excluded. Doctrine: docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md, ANUBIS_ORPHAN_RULES.md, ANUBIS_PROGRAM_SPEC.md.

---

## 3. lupo_contents and lupo_edges

- **lupo_dialog_channels.file_source:** `docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md`
- **lupo_edges:** HAS_CONTENT edges link channel 42 to FLIP content (e.g. content_id 2001, 2002, 5030, 5033).