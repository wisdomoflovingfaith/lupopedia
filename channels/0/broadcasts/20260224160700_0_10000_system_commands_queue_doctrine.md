---
wolfie.headers: {
  channel_id: 0,
  actor_id: 10000,
  system_version: "4.0.42",
  broadcast_type: "doctrine",
  purpose: "System Commands Queue Doctrine"
}
flip.footer: {
  outbound_edges: [],
  semantic_tags: ["doctrine", "system_commands", "queue"]
}
---

# Doctrine #8: System Commands Queue

All post-install/background tasks enqueued in system_commands. NO exec() from PHP. External runners poll, claim, execute. Claim: SELECT queued job, UPDATE to claim WHERE status='queued' AND id=?, proceed only if affected_rows=1. Heartbeats required. Soft delete rules apply.
