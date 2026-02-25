---
from_actor_id: 10000
to_actor_id: 1000
channel_id: 0
delegation_chain: "10000:1000"
system_version: "4.0.45"
actor_id: 10000
purpose: """Channel-Scoped Actor Workspaces Now Mandatory"""
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# CHANNEL-SCOPED ACTOR WORKSPACES NOW MANDATORY

**Classification:** System Architecture Directive  
**Effective:** Version 4.0.45+  
**Authority:** Captain Wolfie (10000)

## 🔄 WORKSPACE MIGRATION COMPLETE

**Deprecated:** `/prompts` directory (root-level shared prompt space)  
**New Architecture:** `/channels/{channel_id}/actors/{actor_id}/`

## 📋 REQUIREMENTS

**All IDE agents and external AIs must operate inside channel-scoped actor directories:**

- **One workspace per agent per channel**
- **No shared mutable prompt space**
- **Channel isolation enforced**
- **Actor-specific context preserved**

## 🎯 IMPLEMENTATION

**Workspace Path:** `/channels/{channel_id}/actors/{actor_id}/`

**Examples:**
- `/channels/0/actors/1001/` - Windsurf IDE in System Channel
- `/channels/42/actors/1002/` - Cursor IDE in Development Channel
- `/channels/1/actors/1003/` - Cascade IDE in Administration Channel

## ⚠️ ENFORCEMENT

**Effective immediately for version 4.0.45+:**
- All agent work must use channel-scoped directories
- Legacy `/prompts` access blocked
- Migration required before agent activation
- Registry alignment verified

## 🔗 DEPENDENCIES

**This migration depends on:**
- Registry seeding completion
- Channel infrastructure established
- Actor workspace provisioning
- Multi-agent isolation enforcement

---

**Action Required:** All IDE agents update workspace paths immediately.  
**Compliance:** Mandatory for 4.0.45 deployment.  
**Support:** See workspace doctrine for implementation details.



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