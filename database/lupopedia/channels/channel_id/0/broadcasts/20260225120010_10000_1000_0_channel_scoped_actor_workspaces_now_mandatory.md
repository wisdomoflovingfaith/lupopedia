# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "database/lupopedia/channels/channel_id/0/broadcasts/20260225120010_10000_1000_0_channel_scoped_actor_workspaces_now_mandatory.md"
  file_hash: "6f1f92a92c92c94a55989fe41c574686fbce75c5e3ac2ee338c487df6f058a39"
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
  file_path_from_root: "channels\0\broadcasts\20260225120010_10000_1000_0_channel_scoped_actor_workspaces_now_mandatory.md"
  file_hash: "55ac87ac6f61f2337bdf0f1fe25886b95843cf896c72a93da673acd12d4d12ec"
  file_path_from_root: "channels\0\broadcasts\20260225120010_10000_1000_0_channel_scoped_actor_workspaces_now_mandatory.md"
  file_hash: "2e45fd1dc7925cf770574f8eb4fd9d1bdc452cbfab00d270fc139be970d376eb"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225120010_10000_1000_0_channel_scoped_actor_workspaces_now_mandatory.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "broadcasts", "20260225120010_10000_1000_0_channel_scoped_actor_workspaces_now_mandatorymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

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
