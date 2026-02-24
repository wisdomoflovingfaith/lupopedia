---
wolfie.headers: {
  channel_id: 0,
  actor_id: 10000,
  system_version: "4.0.42",
  broadcast_type: "doctrine",
  purpose: "Primary Key Allocation Doctrine"
}
flip.footer: {
  outbound_edges: [],
  semantic_tags: ["doctrine", "primary_keys", "registry"]
}
---

# Doctrine #6: Primary Key Allocation

All PKs allocated from lupo_registry_open. Keys in lupo_registry cannot be reused. No auto-increment for application-managed tables.
