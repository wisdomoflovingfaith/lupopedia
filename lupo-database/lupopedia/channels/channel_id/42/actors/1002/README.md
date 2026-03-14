# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\42\actors\1002\README.md"
  file_hash: "7338733f8bb1daabf7610430258561cf706f00c02218f45533ed123cfeba9b0e"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers: 
  system_version: "4.0.50"

---
# Actor Workspace: Cursor IDE (ID: 1002)

**Channel:** 42 (Development)  
**Actor Type:** ide_agent  
**Created:** 20260225000000  
**System Version:** 4.0.45

## Purpose

This is the working directory for Cursor IDE on Channel 42.

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
- Do NOT store doctrine here (use lupo-docs/doctrine/)
- Do NOT store system documentation here
- Files may be cleaned up periodically

## Actor Identity

- **Actor ID:** 1002
- **Type:** ide_agent
- **Channel:** 42 (Development)

---

*This workspace is part of the per-channel actor isolation system introduced in Lupopedia 4.0.45.*
