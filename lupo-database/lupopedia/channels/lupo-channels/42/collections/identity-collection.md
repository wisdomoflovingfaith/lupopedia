# Collection: Identity (SOT)

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/collections/identity-collection.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041350,
  updated_ymdhis: 20260302041350,
  message_type: "collection",
  visibility: "public",
  priority: "high"
}
---

## Description
The Source of Truth (SOT) for actor identities, authentication records, and system-wide identifiers.

## Associated Tables
- `identity`
(Note: Often linked with `lupo_actors` and `lupo_auth_users`).

## Optimization & MD Representation
- **MD Mapping**: Identity data is managed via `registry.json` and agent-specific documentation files (e.g., `GEMINI.md`).
- **Future Goal**: Flatten identity attributes into a single optimized registry to eliminate multi-join lookups.
