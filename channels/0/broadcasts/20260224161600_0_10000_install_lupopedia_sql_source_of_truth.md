---
wolfie.headers: {
  channel_id: 0,
  actor_id: 10000,
  to_actor_id: 0,
  system_version: "4.0.42",
  broadcast_type: "doctrine",
  artifact_kind: "doctrine",
  purpose: "install_new_lupopedia.sql Is the Source of Truth"
}
flip.footer: {
  outbound_edges: [
    { to: "database/migrations/install_new_lupopedia.sql", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["doctrine", "schema", "source_of_truth"]
}
---

# Doctrine: install_new_lupopedia.sql Is the Source of Truth

All schema changes must be made in database/migrations/install_new_lupopedia.sql. The DB does not exist during 4.0.x development. install_new_lupopedia.sql is the only authoritative schema. No schema changes may be made directly in a live DB.
