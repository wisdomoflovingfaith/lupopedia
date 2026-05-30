---
lupopedia.headers:
  version_when_written: "4.0.87"
  file_path_from_root: "lupo-channels/edge_generation_governance/threads/edge_generation_governance/20260324_150925_channel_thread_edge_map_api_update.md"
  questions_toon: null
  channel_id: 64
  thread_id: "edge_generation_governance"
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "channel_artifact"
  artifact_kind: "implementation_update"
  purpose: "Describe channel/thread edge map API for related channels, threads, and edge visibility."
---

# Edge Governance Update: Channel/Thread Map API

## Problem
Channel/thread relationships existed in `lupo_context_edges`, but there was no single API response that answered:

- what edges exist for a channel
- what related channels are involved
- what threads belong to this channel
- what thread-level edges link to internal/external threads/channels

## Implementation
Added API endpoint:

- `GET api/context-graph/channel-map?channel_id=<id>&thread_limit=<n>&edge_limit=<n>`

Response includes:

- channel core record
- channel edges
- related channels (from direct channel edges + thread-edge references)
- channel thread list (`lupo_dialog_threads`)
- per-thread edges where thread is source or target
- related threads (outside the channel thread set)
- summary counts

## Access Control
- endpoint requires authenticated actor
- actor must be a member of channel (`lupo_actor_channels`) or global admin

## Collections Note
`lupo_collections` can remain polymorphic for UI/content grouping, but it is not a substitute for explicit graph relationships in channel/thread edge governance. Channel/thread relationship truth is now queryable directly via context-graph endpoint.
