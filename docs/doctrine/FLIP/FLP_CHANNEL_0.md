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
