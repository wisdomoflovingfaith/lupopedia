# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\channels\42\actors\1001\README.md"
  file_hash: "c8cf472ac9a0d1570986bce03d56392f072baac56b5339736a193cd5622e33fa"
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
  file_path_from_root: "channels\42\actors\1001\README.md"
  file_hash: "2adba7fd8cdc90bee811f9c556a4268306c8d61386fc9d8f9be9b1c371656ec8"
  system_version: "4.0.50"
  channel_id: 42
---
# Actor Workspace: Windsurf IDE (ID: 1001)

**Channel:** 42 (Development)  
**Actor Type:** ide_agent  
**Created:** 20260225000000  
**System Version:** 4.0.45

## Purpose

This is the working directory for Windsurf IDE on Channel 42.

## Contents

- Temporary prompts
- Scratch files
- Working notes
- Task state
- Partial outputs
- Draft doctrine
- Debug artifacts

## Rules

- Files here are TEMPORARY and MUTABLE
- Do NOT store permanent artifacts here
- Do NOT store doctrine here (use docs/doctrine/)
- Do NOT store system documentation here
- Files may be cleaned up periodically

## Actor Identity

- **Actor ID:** 1001
- **Type:** ide_agent
- **Channel:** 42 (Development)

---

*This workspace is part of the per-channel actor isolation system introduced in Lupopedia 4.0.45.*