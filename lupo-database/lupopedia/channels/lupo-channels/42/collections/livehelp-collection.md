# Collection: LiveHelp (Network)

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/collections/livehelp-collection.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041330,
  updated_ymdhis: 20260302041330,
  message_type: "collection",
  visibility: "public",
  priority: "normal"
}
---

## Description
Handles communication channels, operator assignments, and live interaction transcripts.

## Associated Tables
- `livehelp_channels`
- `livehelp_agents`
- `conversation`
- `operator`
- `transcript`
- `invite`

## Optimization & MD Representation
- **MD Mapping**: Conversations and transcripts are mirrored as `threads/*.md` files within the channel structure.
- **Future Goal**: Consolidate `operator` and `livehelp_agents` into a unified `agent_registry` pattern to reduce table count.
