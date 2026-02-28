# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "status"
  flare.edges: []
  file_path_from_root: "docs\status\vsx_extension_status.md"
  file_hash: "2cd635f9e78bf7e213609185a92c2befe0246a56cf88334d1149c30864f479a8"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for vsx_extension_status.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "vsx_extension_statusmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers:
  file_path_from_root: "docs/status/vsx_extension_status.md"
  system_version: "4.0.36"
  channel_id: 42
  mood_rgb: "00AAFF"
  purpose: "VSX Extension operational mode status (queryable by KIRO)"
  last_modified: "20260223"
  x_lupo_forwarded: "1003:10000"
  actor_id: 1003
  lupo_agent: "ide|antigravity"

flip.footer:
  referenced_by_files:
    - "docs/status/kiro_vsx_status_query_4_0_35.md"
    - "docs/status/antigravity_vsx_extension_update_4_0_35.md"
    - "CHANGELOG.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1001
    - 1003
    - 10000
  inbound_edges:
    - "vsx_status_query"
    - "md_fallback_status"
  footnotes:
    - "Queryable status file for VSX Extension operational mode"
    - "Updated by Antigravity IDE when mode changes"
  version: "4.0.36"
  last_verified: "20260223"
  last_verified_by: "antigravity"
---

# VSX EXTENSION STATUS

**Current Mode:** hybrid  
**Last Updated:** 20260223  
**Source:** antigravity  
**Actor ID:** 1003  

---

## OPERATIONAL MODES

### md_only
- Database offline
- Extension running entirely from MD files
- Registry loaded from `docs/AGENT_INVENTORY.md`
- Channel discovery via file system scan
- No database writes

### hybrid
- Database online but MD fallback available
- Extension can use live registry OR MD files
- Automatic fallback to MD if DB connection fails
- Dual-mode operation

### db_online
- Database fully online
- Extension using live registry
- Full database access
- No MD fallback needed

---

## CURRENT STATUS

**Mode:** hybrid  
**Reason:** Database available, MD fallback implemented  
**Capabilities:**
- ✅ MD-only registry loader
- ✅ MD-only channel discovery
- ✅ Enhanced FLIP parser (header + footer)
- ✅ DB-offline fallback detection
- ✅ Status command for KIRO

**Last Mode Change:** 20260223  
**Last Verified:** 20260223  

---

## QUERY INTERFACE

This file is queryable by KIRO IDE and other agents to determine VSX Extension operational mode.

**Query Fields:**
- `vsx_extension_status` - Current mode (md_only | hybrid | db_online)
- `last_updated` - UTC date (YYYYMMDD)
- `source` - Agent that updated status (antigravity)
- `actor_id` - Actor ID of source agent

**Usage:**
```bash
# Read status
cat docs/status/vsx_extension_status.md

# Extract mode (grep)
grep "Current Mode:" docs/status/vsx_extension_status.md
```

---

## UPDATE PROTOCOL

**When to Update:**
- Database connection status changes
- Extension mode manually toggled
- Fallback triggered
- Capabilities added/removed

**Who Updates:**
- Antigravity IDE (1003) - Primary
- KIRO IDE (1001) - Verification
- System agents - Automated monitoring

---

**STATUS ACTIVE**

Antigravity IDE (actor_id 1003)  
UTC Date: 20260223  

**END OF STATUS**
