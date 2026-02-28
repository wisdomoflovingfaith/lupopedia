# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "CHANNEL_IDENTITY_BLOCK_TEMPLATE.md"
  file_hash: "46f671b1e280ae189dc1e94ec0ee37e35f8eb13765ad020a193fb47df67ed8a6"
  file_path_from_root: "CHANNEL_IDENTITY_BLOCK_TEMPLATE.md"
  file_hash: "274e406c5186279e82755cbe514b4f4baef7c357814c990a61c86b3b50234c35"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "CHANNEL_IDENTITY_BLOCK_TEMPLATE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channel_identity_block_templatemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  file_path_from_root: "CHANNEL_IDENTITY_BLOCK_TEMPLATE.md"
  file_hash: "ca457b0c188d775015b97dc9898a43bb8f44bbddb37a9ba4e74b3be2c23a0273"
  system_version: "4.0.50"
  delegation_chain: null
  needs_review: ["delegation_chain"]
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# CHANNEL_IDENTITY_BLOCK_TEMPLATE.md

## Canonical Template for All Channels in Lupopedia

```json
{
  "channel_key": "<required: unique string identifier>",
  "channel_name": "<required: human-readable name>",
  "channel_description": "<required: purpose and scope of this channel>",

  "federation_node_id": 1,
  "dialog_output_file": "<required: dialogs/<channel_key>_dialog.md>",

  "created_by_actor_id": 1,
  "created_ymdhis": "<YYYY-MM-DD HH:MM:SS>",
  "updated_ymdhis": "<YYYY-MM-DD HH:MM:SS>",

  "default_roles": [
    // optional: roles automatically granted to new actors in this channel
  ],

  "default_agents": [
    // optional: agents automatically subscribed to this channel
  ],

  "actor_membership": {
    "allowed_speakers": [
      // actor_ids or agent codes allowed to speak
    ],
    "allowed_listeners": [
      // actor_ids or agent codes allowed to read
    ],
    "allowed_observers": [
      // actor_ids or agent codes allowed silent access
    ],
    "visibility": "public | private | system | operator | restricted"
  },

  "semantic_scope": {
    "layers_enabled": [
      "interaction",
      "extracted",
      "navigation",
      "ai"
    ],
    "routing_rules": "local | federated | restricted",
    "edge_visibility": "local-only | global"
  },

  "operational_mode": 
    "chat | system | doctrine | project | idea | gov | training | runtime",

  "temporal_behavior": {
    "retention": "ephemeral | rolling | permanent",
    "decay": "none | standard | accelerated",
    "archive": "enabled | disabled"
  },

  "emotional_poles": {
    // optional: emotional geometry for CADUCEUS integration
  },

  "metadata_json": {
    // optional: arbitrary metadata for channel-specific behavior
  },

  "tags": [
    // optional: keywords for search and semantic indexing
  ],

  "bgcolor": "<optional: hex color>",
  "status_flag": "<optional: active | deprecated | experimental>"
}
```

## 🟩 What This Template Fixes

This version finally encodes the truth you realized:

Channels are not just "rooms."
They are:

- semantic boundaries
- actor‑membership containers
- routing firewalls
- operational modes
- meaning scopes
- temporal contexts

This template makes those properties explicit and machine‑readable.

## 🟦 What This Enables

With this template in place, you can now:

- generate channel manifests deterministically
- enforce actor membership rules
- restrict doctrine channels to kernel agents
- allow project channels to have custom semantic layers
- create ephemeral channels with decay
- create runtime channels with accelerated aging
- build cross‑channel routing rules
- integrate semantic layers directly into channel behavior

This is the missing piece that makes your channel system a true semantic OS.

---

**Version:** 1.0  
**Author:** Wolfie  
**Status:** Canonical  
**Scope:** Global Channel Architecture  
**Applies To:** All channel creation, initialization, and management subsystems
