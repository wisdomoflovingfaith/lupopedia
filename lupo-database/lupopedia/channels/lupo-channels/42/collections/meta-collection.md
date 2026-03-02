# Collection: Meta (Collections)

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/collections/meta-collection.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041400,
  updated_ymdhis: 20260302041400,
  message_type: "collection",
  visibility: "public",
  priority: "normal"
}
---

## Description
Metadata about the collections themselves. Used for orchestrating sync operations and directory scanning.

## Associated Tables
- `lupo_collections` (Inferred/System)

## Optimization & MD Representation
- **MD Mapping**: This collection is represented by the very files within `lupo-channels/*/collections/`.
- **Future Goal**: Automate the generation of these meta-collections from the filesystem structure to ensure the DB remains a faithful reflection of the MD "Source of Truth."
