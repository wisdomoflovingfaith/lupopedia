---
wolfie.headers: {
  channel_id: 0,
  actor_id: 10000,
  system_version: "4.0.42",
  broadcast_type: "doctrine",
  purpose: "PDO + Database Factory Doctrine"
}
flip.footer: {
  outbound_edges: [],
  semantic_tags: ["doctrine", "database", "pdo"]
}
---

# Doctrine #4: PDO + Database Factory Only

All DB access uses PDO wrapper + DatabaseFactory. No mysqli. No procedural helpers. All code must be OOP.
