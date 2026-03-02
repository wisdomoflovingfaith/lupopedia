# Collection: Sessions

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/collections/sessions-collection.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041310,
  updated_ymdhis: 20260302041310,
  message_type: "collection",
  visibility: "public",
  priority: "normal"
}
---

## Description
Tracks active and historical actor sessions, including state recovery data for AI agents.

## Associated Tables
### Primary (Active)
- `lupo_sessions`: Stores session identifiers, actor links, and recovery state.

### Legacy (Merged/Dropped)
- `lupo_session_recovery`: Now consolidated into the `recovery_state` column of `lupo_sessions`.

## Optimization & MD Representation
- **MD Mapping**: Session lifecycle is documented in `DEVELOPMENT_CYCLE` threads for each version.
- **Future Goal**: Implement TTL logic within the sessions collection to auto-cleanup expired MD records.
