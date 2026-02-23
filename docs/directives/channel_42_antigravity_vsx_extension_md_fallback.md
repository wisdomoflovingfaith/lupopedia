---
wolfie.headers:
  file_path_from_root: "docs/directives/channel_42_antigravity_vsx_extension_md_fallback.md"
  system_version: "4.0.35"
  channel_id: 42
  mood_rgb: "0044FF"
  purpose: "Directive for Antigravity to update the Lupopedia VSX Extension to operate fully from MD files when DB is offline"
  last_modified: "20260223"
  x_lupo_forwarded: "10000:1003"
  actor_id: 10000
  lupo_agent: "human|captain"

lupo.agent.tracking:
  agent_key: "antigravity"
  agent_type: "ide"
  actor_id: 1003
  priority: 2
  speed_rating: "⚡⚡"
  session_id: "antigravity-vsx-md-fallback-20260223"
  timestamp: "20260223"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "docs/status/antigravity_vsx_extension_update_4_0_35.md"
    - "docs/versions/4.0.35/TODO.md"
    - "docs/versions/4.0.35/ROADMAP.md"
  consumed_by_services:
    - "ExtensionService"
    - "MetadataService"
  cited_by_docs:
    - "docs/doctrine/BROADCAST_FORMAT_DOCTRINE.md"
    - "docs/doctrine/EXTENSION_FALLBACK_DOCTRINE.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 10000
    - 1003
    - 1001
    - 1002
  graph_edges_in:
    - "vsx_extension_update -> this"
    - "version_4_0_35_kickoff -> this"
  inbound_edges:
    - "md_fallback_requirement"
    - "db_offline_mode"
  footnotes:
    - "VSX extension must support MD-only operation"
    - "All timestamps use canonical YYYYMMDD format"
    - "Location removed per doctrine"
  version: "4.0.35"
  last_verified: "20260223"
  last_verified_by: "10000"
---

# ANTIGRAVITY DIRECTIVE — UPDATE LUPOPEDIA VSX EXTENSION FOR MD‑ONLY FALLBACK

**From:** Captain Wolfie (actor_id 10000)  
**To:** Antigravity IDE (actor_id 1003)  
**Date:** 20260223  
**Subject:** Update Lupopedia VSX Extension to operate fully from MD files when DB is offline

---

## 🚀 OBJECTIVE

Antigravity, as the **IDE Extensions Lead**, your primary task for **version 4.0.35** is to update the **Lupopedia VSX Extension** so that it can operate **entirely from MD files** when the database is offline.

This is now a **core requirement** for Lupopedia’s resilience and agent‑coordination model.

---

# 1. REQUIRED CAPABILITIES FOR MD‑ONLY FALLBACK MODE

The VSX Extension must be able to:

### ✔ 1. Load agent registry from MD files  
Use:
- `docs/AGENT_INVENTORY.md`
- `docs/doctrine/AGENT_REGISTRY_DOCTRINE.md`
- `docs/status/*registry*.md`

### ✔ 2. Load channel data from MD files  
From:
- `channels/42/`
- `docs/channels/42/`
- `docs/status/`

### ✔ 3. Parse FLIP headers and footers  
Extract:
- `actor_id`
- `lupo_agent`
- `last_modified`
- `referenced_by_files`
- `graph_edges_in`
- `inbound_edges`

### ✔ 4. Detect agent activity from MD files  
Using:
- `lupo.agent.tracking`
- `x_lupo_forwarded`
- `last_modified`
- `referenced_by_actors`

### ✔ 5. Provide fallback responses when DB is offline  
If DB connection fails:
- Switch to MD‑only mode  
- Load registry + channels + metadata from MD  
- Continue operating normally  

### ✔ 6. Expose MD‑only mode to KIRO  
KIRO must be able to query:
`vsx_extension_status: "md_only" | "hybrid" | "db_online"`

---

# 2. IMPLEMENTATION REQUIREMENTS

### ✔ Build MD parsing layer  
- YAML front‑matter parser  
- FLIP header/footer parser  
- Directory scanner  
- Agent registry loader  

### ✔ Add fallback detection  
If DB connection fails → automatically switch to MD‑only mode.

### ✔ Add status reporting  
Generate:
`docs/status/antigravity_vsx_extension_update_4_0_35.md`

Include:
- MD-only mode status  
- Files parsed  
- Capabilities implemented  
- Any limitations  

### ✔ Update CHANGELOG.md  
Append:
`Antigravity — VSX Extension MD Fallback (v4.0.35)`
- `Implemented MD-only fallback mode for VSX extension`
- `Added FLIP header/footer parsing`
- `Added agent activity detection from MD files`
- `Added registry loading from MD files`
- `Added DB-offline detection and automatic fallback`

---

# 3. SAFETY & SCOPE

- ❗ No database writes  
- ❗ No schema changes  
- ✔ VSX extension–only updates  
- ✔ Metadata-only logic  
- ✔ All changes reversible  

---

# 4. COMPLETION MESSAGE

After completing the update, post:
`Antigravity: VSX Extension updated for MD-only fallback. Report generated.`
`Date: 20260223`

---

## END OF DIRECTIVE
