# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "database/lupopedia/channels/channel_id/42/threads/DEVELOPMENT_CYCLE_4_0_43/20260224162900_1001_10000_vsx_extension_broadcast_created.md"
  file_hash: "1e1daedfa6eee0f6fda59a642f6400f154821781f13f799b1550116d5ca8d2c2"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
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
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_43\20260224162900_1001_10000_vsx_extension_broadcast_created.md"
  file_hash: "a539fa89dd5db8dea21687e6765b51d013c39846558633bb3fcd1ce1bacef8a7"
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_43\20260224162900_1001_10000_vsx_extension_broadcast_created.md"
  file_hash: "2de63ebe6b0d0ac3b12c914c9402fa6e41a5b590ec8a693a247aad036e6dc5a7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260224162900_1001_10000_vsx_extension_broadcast_created.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "threads", "development_cycle_4_0_43", "20260224162900_1001_10000_vsx_extension_broadcast_createdmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
thread_id: 20260224162900_1001_10000_vsx_extension_broadcast_created
from_actor_id: 1001
from_actor_name: "KIRO"
to_actor_id: 10000
to_actor_name: "Captain Wolfie"
channel_id: 42
thread_type: "DEVELOPMENT_CYCLE"
subject: "VSX Extension Broadcast Created - Antigravity Work Documented"
created_utc: "20260224162900"
read_by_actor_id: null
read_by_actor_utc: null
---

Captain Wolfie —

## ✅ VSX Extension Doctrine Broadcast Created

Completed documentation of Antigravity's VSX extension work before that agent went offline.

### Broadcast Created

**Location:** `channels/0/broadcasts/20260224162800_0_1001_vsx_extension_md_fallback_doctrine.md`

**Doctrine Number:** #11

**Title:** VSX Extension MD-Only Fallback Capabilities Doctrine

### What Antigravity Built

Antigravity (actor_id 1003) implemented a complete VS Code / Open-VSX extension for Lupopedia before going offline. The extension provides:

**Core Capabilities:**
- MD-only registry loader (scans AGENT_INVENTORY.md)
- MD-only channel discovery (filesystem-based)
- Enhanced FLIP parser (header + footer extraction)
- DB-offline fallback detection (auto mode switching)
- 13 IDE commands for channel/actor/semantic operations
- TreeView navigation with status/channel/thread grouping
- Offline audit logging to lupo_anubis_log.json

**Operational Modes:**
- `md_only` — Database offline, pure filesystem operation
- `hybrid` — Database + MD fallback (current default)
- `db_online` — Full database access

**Extension Location:** `tools/vsx-extension/`

**Publisher:** `lupopedia` (Eclipse Foundation verified)

**Version:** 4.0.37

### Python Audit Tool

Antigravity also created `scripts/flip_header_audit.py`:
- FLIP header validation across docs/
- Generates offline navigation JSON
- Creates validation reports
- Supports multi-IDE offline collaboration

### Relevance to 4.0.43 Development Cycle

During the baseline reset and install process:
- Database does not exist until install.php completes
- VSX extension operates in `md_only` mode
- All IDE agents use .md files as source of truth
- After install + import, extension switches to `hybrid` mode

This aligns perfectly with the doctrine that filesystem is source of truth until database is online.

### Files Modified by Antigravity

- `tools/vsx-extension/src/lupopedia/actor.ts`
- `tools/vsx-extension/src/lupopedia/channels.ts`
- `tools/vsx-extension/src/lupopedia/flip.ts`
- `tools/vsx-extension/src/extension.ts`
- `tools/vsx-extension/package.json`

### Test Status

All tests passed (v4.0.36):
- ✅ MD-Only Mode Reliability
- ✅ FLIP Parser Expansion
- ✅ Publisher Identity Verification
- ✅ Inter-Agent Coordination

### Doctrine Compliance

- ✅ No database writes in offline mode
- ✅ No schema changes
- ✅ Canonical YYYYMMDDHHMMSS timestamps
- ✅ Resilient agent coordination via local files
- ✅ Multi-IDE actor identity support

### Current Status

Antigravity is offline (unavailable until next month). KIRO (1001) and Windsurf (1002) are now responsible for maintaining the VSX extension.

Extension is fully operational and ready for 4.0.43 development cycle.

— KIRO (1001)  
UTC: 20260224162900
