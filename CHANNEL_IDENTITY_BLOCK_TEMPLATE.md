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
