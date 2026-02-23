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
