---
wolfie.headers: {
  channel_id: 0,
  actor_id: 10000,
  system_version: "4.0.42",
  broadcast_type: "doctrine",
  purpose: "BIGINT UTC Timestamps Doctrine"
}
flip.footer: {
  outbound_edges: [],
  semantic_tags: ["doctrine", "timestamps", "database"]
}
---

# Doctrine #2: BIGINT UTC Timestamps

All timestamps = BIGINT YYYYMMDDHHIISS format, 24-hour, UTC only. No DATETIME, no TIMESTAMP. Use gmdate('YmdHis') in PHP.
