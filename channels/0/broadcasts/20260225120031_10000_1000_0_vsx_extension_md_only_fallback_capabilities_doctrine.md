# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\broadcasts\20260225120031_10000_1000_0_vsx_extension_md_only_fallback_capabilities_doctrine.md"
  file_hash: "49173353cfa4fd7073631f9a894e1b122f8433088b33bb572948faad6db96f76"
  file_path_from_root: "channels\0\broadcasts\20260225120031_10000_1000_0_vsx_extension_md_only_fallback_capabilities_doctrine.md"
  file_hash: "cf97984b4aea8e760555968e10aa0ea16aad8245f612ce34068ef8aea159214b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225120031_10000_1000_0_vsx_extension_md_only_fallback_capabilities_doctrine.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "broadcasts", "20260225120031_10000_1000_0_vsx_extension_md_only_fallback_capabilities_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
from_actor_id: 10000
to_actor_id: 1000
channel_id: 0
delegation_chain: "10000:1000"
system_version: "4.0.45"
actor_id: 1001,
purpose: """VSX Extension MD-Only Fallback Capabilities Doctrine"""
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# Doctrine #11: VSX Extension MD-Only Fallback Capabilities

The Lupopedia VSX Extension (VS Code / Open-VSX) provides full offline operation when the database is unavailable. This extension was implemented by Antigravity IDE (actor_id 1003) before going offline.

## Extension Location

**Path:** `tools/vsx-extension/`

**Publisher:** `lupopedia` (Eclipse Foundation verified)

**Version:** 4.0.37

**Status:** Operational with hybrid mode (database + MD fallback)

## Core Capabilities

### 1. MD-Only Registry Loader
- Scans `docs/AGENT_INVENTORY.md` for actor_id and lupo_agent mappings
- Merges with legacy `lupo_agents.toon.json` to build internal actor cache
- Enables actor identification without database connection

### 2. MD-Only Channel Discovery
- Recursively scans `messages/`, `docs/channels/`, and `channels/` directories
- Identifies active channel threads from filesystem
- Supports offline channel navigation

### 3. Enhanced FLIP Parser
- Extracts both FLIP header and footer YAML blocks
- Parses `referenced_by_files`, `inbound_edges`, `referenced_by_actors`
- Supports graph-critical metadata extraction
- File: `tools/vsx-extension/src/lupopedia/flip.ts`

### 4. DB-Offline Fallback Detection
- Automatic mode switching: `online` → `hybrid` → `offline`
- Communication mode configurable via `lupopedia.communicationMode` setting
- Status queryable via `lupopedia.getStatus` command

### 5. IDE Commands

| Command | Purpose |
|---------|---------|
| `lupopedia.registerIde` | Register IDE with unified registry |
| `lupopedia.joinChannel` | Join a Lupopedia channel |
| `lupopedia.sendMessage` | Post message to active channel |
| `lupopedia.showChannelThread` | Open live-updating thread view |
| `lupopedia.explainThisFile` | Request semantic file explanation |
| `lupopedia.showRelatedAtoms` | Find semantically related content |
| `lupopedia.validateFlipHeader` | Parse and validate FLIP front-matter |
| `lupopedia.logAction` | Record agent actions to audit trail |
| `lupopedia.initialize` | Initialize extension |
| `lupopedia.scan` | Scan workspace for FLIP files |
| `lupopedia.status` | Show extension operational status |
| `lupopedia.forceOffline` | Force offline mode |

### 6. TreeView Navigation
- Hierarchical FLIP file navigation in VS Code sidebar
- Grouping modes: Status, Channel, Flat list
- Visual indicators for Active/Proposed/Deprecated status
- Search and filter by status, channel, thread
- Thread simulation for offline collaboration

### 7. Offline Audit Logging
- Logs agent actions to `lupo_anubis_log.json`
- Maintains last 1000 entries automatically
- Enables action tracking during database outages

## Operational Modes

### md_only
- Database offline
- Extension runs entirely from MD files
- Registry loaded from `docs/AGENT_INVENTORY.md`
- Channel discovery via filesystem scan
- No database writes

### hybrid (Current)
- Database online but MD fallback available
- Extension can use live registry OR MD files
- Automatic fallback to MD if DB connection fails
- Dual-mode operation

### db_online
- Database fully online
- Extension using live registry
- Full database access
- No MD fallback needed

## Configuration

Settings available in VS Code:

```json
{
  "lupopedia.baseUrl": "https://lupopedia.com/lupopedia",
  "lupopedia.defaultChannelId": 42,
  "lupopedia.actorName": "Antigravity IDE",
  "lupopedia.actorType": "system_tool",
  "lupopedia.communicationMode": "auto",
  "lupopedia.mode": "hybrid",
  "lupopedia.autoScan": true,
  "lupopedia.channel42.autoJoin": true
}
```

## Files Modified by Antigravity

- `tools/vsx-extension/src/lupopedia/actor.ts` — Registry fallback
- `tools/vsx-extension/src/lupopedia/channels.ts` — Channel discovery
- `tools/vsx-extension/src/lupopedia/flip.ts` — Unified metadata parser
- `tools/vsx-extension/src/extension.ts` — Status API & mode toggling
- `tools/vsx-extension/package.json` — Publisher verification & commands

## Python Audit Tool

**Path:** `scripts/flip_header_audit.py`

**Purpose:** FLIP header validation and offline navigation generation

**Features:**
- Scans `docs/doctrine/`, `docs/api/`, `docs/specs/`
- Validates required FLIP header fields
- Generates `exports/flip_navigation.json` for offline mode
- Creates validation reports in `exports/flip_validation_report.md`

## Doctrine Compliance

- ✅ No database writes in offline mode
- ✅ No schema changes
- ✅ Canonical YYYYMMDDHHMMSS timestamps
- ✅ Resilient agent coordination via local files
- ✅ PHP 5.3 compatibility (extension is TypeScript, not PHP)
- ✅ Multi-IDE actor identity support

## Integration with Development Cycle 4.0.43

During 4.0.43 development cycle:
- Database does not exist until install.php completes
- VSX extension operates in `md_only` or `offline` mode
- All IDE agents use filesystem (.md files) as source of truth
- After install + import, extension switches to `hybrid` or `db_online` mode

## Status Query

Current status queryable via:
```bash
cat docs/status/vsx_extension_status.md
```

Or via VS Code command:
```
Lupopedia: Show Status
```

## Limitations

- In MD-only mode, new messages are appended to local `.md` files in `messages/`
- Manual sync or filesystem monitoring required until DB is restored
- Write-back to database requires online mode

## Testing

Test reports available:
- `docs/status/vsx_extension_test_report_4_0_36.md` — All tests passed
- `docs/status/vsx_extension_test_plan_4_0_36.md` — Test plan

## Credits

**Implemented by:** Antigravity IDE (actor_id 1003)

**Version Range:** 4.0.33 - 4.0.37

**Status:** Antigravity offline as of 4.0.42; KIRO (1001) and Windsurf (1002) now maintain

**Documentation:** This broadcast created by KIRO (1001) based on Antigravity's implementation

---

**VSX Extension is fully operational and doctrine-compliant for 4.0.43 development cycle.**


<!-- FLIP_FOOTER_BEGIN
{
    "references": "\"docs\/status\/broadcast_collection_0.md\"",
    "implements": "\"broadcast_standardization\"",
    "depends_on": "\"registry_seeding_completion\"",
    "includes": "\"channel_0_communications\"",
    "version": "\"4.0.45\"",
    "last_verified": "\"20260225\"",
    "last_verified_by": "\"windsurf\""
}
FLIP_FOOTER_END -->