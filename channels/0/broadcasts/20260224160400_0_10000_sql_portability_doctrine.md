---
wolfie.headers: {
  channel_id: 0,
  actor_id: 10000,
  system_version: "4.0.42",
  broadcast_type: "doctrine",
  purpose: "SQL Portability Doctrine"
}
flip.footer: {
  outbound_edges: [],
  semantic_tags: ["doctrine", "sql", "portability"]
}
---

# Doctrine #5: SQL Portability

Must work on MySQL, PostgreSQL, MariaDB. NO UNSIGNED, NO DATETIME, NO triggers, NO procedures, NO foreign keys, NO database functions. All INSERT/UPDATE must list every column including PK.
