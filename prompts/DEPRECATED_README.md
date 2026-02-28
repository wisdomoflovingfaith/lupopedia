# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "prompts\DEPRECATED_README.md"
  file_hash: "182b5f8cf0a9588a963e9a77bffb06d75ce5be80a4ad4af7ea3e0e81cdefb74b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "DEPRECATED: /prompts/ Root Directory"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["prompts", "deprecated_readmemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# DEPRECATED: /prompts/ Root Directory

**Status:** DEPRECATED as of Lupopedia 4.0.45  
**Migration Date:** 2026-02-25  
**Replacement:** `/channels/*/actors/*/` per-channel actor workspaces

## What Happened?

The `/prompts/` root directory has been deprecated in favor of per-channel actor workspaces. All agent working files have been migrated to:

```
channels/{channel_id}/actors/{actor_id}/
```

## Why?

The old `/prompts/` structure caused:
- Prompt collisions between agents
- Context leakage across channels
- Role confusion in multi-agent scenarios
- Federation scaling issues

## New Structure

**Channel 0 (System Kernel):**
- `channels/0/actors/1/` - WOLFIE AI
- `channels/0/actors/3/` - ROSE
- `channels/0/actors/4/` - ERIS
- `channels/0/actors/5/` - METIS

**Channel 42 (Development):**
- `channels/42/actors/1000/` - KIRO IDE
- `channels/42/actors/1001/` - Windsurf IDE
- `channels/42/actors/1002/` - Cursor IDE
- `channels/42/actors/1003/` - Antigravity IDE
- `channels/42/actors/2/` - LILITH
- `channels/42/actors/10000/` - Captain

## Historical Files

The following files remain in `/prompts/` for historical reference:
- `registry.json` - Actor ID registry (snapshot)
- `REORGANIZATION_COMPLETE.md` - Previous reorganization record
- This file (`DEPRECATED_README.md`)

## Migration

All files have been COPIED (not moved) to preserve history. Original files remain here for reference but should NOT be modified.

## For Developers

**DO NOT write new files to `/prompts/`.**

Use the per-channel actor workspace structure:
```bash
channels/{channel_id}/actors/{actor_id}/your_file.md
```

---

*Migration completed by KIRO (Warp IDE Agent 1004) on 2026-02-25 for Lupopedia 4.0.45.*
