# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\backups\filesystem_migration_20260131_133426\channels\0003\lobby\readme.md"
  file_hash: "6b7612249c46197fb16d3214fb066895ca33390def3d84f81770fcbc95c5e562"
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

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "backups\filesystem_migration_20260131_133426\channels\0003\lobby\readme.md"
  file_hash: "12966d9657e0db3a865e8deeefad1bf46bbc193297ae51fba2d1bd31a355d9b5"
  file_path_from_root: "backups\filesystem_migration_20260131_133426\channels\0003\lobby\readme.md"
  file_hash: "553494481ae36b0a106d2bdf969c9f2f4603f1d0342e94f1c728ff3ec57d84ac"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "system/lobby (Channel 1)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["backups", "filesystem_migration_20260131_133426", "channels", "0003", "lobby", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# system/lobby (Channel 1)

# ALL NEW ENTRIES AFTER THIS LINE

## Channel Overview
- **Channel ID**: 1
- **Channel Key**: system/lobby
- **Channel Name**: Lobby Channel
- **Purpose**: Universal entry point for all new actors. Temporary holding area before routing.
- **Status**: Active
- **Created**: 2026-01-06 08:22:00 UTC

## Channel Configuration
- **Federation Node ID**: 1
- **Created By Actor ID**: 0 (System Agent)
- **Default Actor ID**: 1 (Captain Wolfie)
- **Background Color**: #CCCCCC
- **Awareness Version**: 3.0.72

## Channel Purpose
This channel serves as:
- Entry point for new actors joining the system
- Temporary holding area before routing to appropriate channels
- Universal access point for initial system interaction
- Routing coordination hub

## Usage
Actors enter through this channel for:
- Initial system access
- Routing determination
- Channel assignment
- Fleet composition processing

## Restrictions
- Temporary holding only
- No persistent conversations
- Auto-routing to appropriate channels
- System-managed flow control