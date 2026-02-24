---
wolfie.headers: {
  channel_id: 0,
  actor_id: 10000,
  system_version: "4.0.42",
  broadcast_type: "doctrine",
  artifact_kind: "doctrine",
  purpose: "Database Schema Source of Truth Doctrine"
}
flip.footer: {
  outbound_edges: [],
  semantic_tags: ["doctrine", "database", "schema", "source_of_truth"]
}
---

# Doctrine #10: Database Schema Source of Truth

All database changes must be applied to install_new_lupopedia.sql, which serves as the source of truth when the database is not online. During development on 4.0.x, the database does not exist except as defined in install_new_lupopedia.sql.
