---
wolfie.headers: {
  channel_id: 0,
  actor_id: 10000,
  to_actor_id: 0,
  system_version: "4.0.42",
  broadcast_type: "doctrine",
  artifact_kind: "doctrine",
  purpose: "install.php Creates All Tables"
}
flip.footer: {
  outbound_edges: [
    { to: "install.php", type: "references", weight: 1.0 },
    { to: "database/migrations/install_new_lupopedia.sql", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["doctrine", "installation", "schema"]
}
---

# Doctrine: install.php Creates All Tables

install.php loads install_new_lupopedia.sql. install_new_lupopedia.sql is the canonical schema. No migrations run after install. No schema drift is allowed. All schema changes must be made only in install_new_lupopedia.sql.
