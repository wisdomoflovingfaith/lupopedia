# Collection: Logging System

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/collections/logging-collection.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041300,
  updated_ymdhis: 20260302041300,
  message_type: "collection",
  visibility: "public",
  priority: "normal"
}
---

## Description
Centralized logging collection for system events, task logs, and actor behaviors. Phase 1 successfully consolidated the majority of these into a unified structure.

## Associated Tables
### Primary (Active)
- `lupo_unified_log`: The consolidated sink for all system and event data.

### Legacy (Merged/Dropped)
- `lupo_system_logs`
- `lupo_system_events`
- `lupo_task_events`
- `lupo_meta_log_events`
- `lupo_session_events`
- `lupo_memory_events`
- `lupo_tab_events`
- `lupo_world_events`
- `lupo_actor_events`
- `lupo_event_log`

## Optimization & MD Representation
- **MD Mapping**: Contents from `lupo_unified_log` are represented in MD as thread summaries and task updates.
- **Future Goal**: Optimize the `payload` JSON column to handle varied event types without schema mutation.
