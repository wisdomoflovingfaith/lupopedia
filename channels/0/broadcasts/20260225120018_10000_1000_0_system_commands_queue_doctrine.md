# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\broadcasts\20260225120018_10000_1000_0_system_commands_queue_doctrine.md"
  file_hash: "005ccc9faabfe8a18550e0760791c5cd714445049dc17fcf40a5d3faacfb4be2"
  file_path_from_root: "channels\0\broadcasts\20260225120018_10000_1000_0_system_commands_queue_doctrine.md"
  file_hash: "e9f1108a3df6025496a1559cfd0cf4dbbb198882d8e23414defbb8aca6e78b80"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225120018_10000_1000_0_system_commands_queue_doctrine.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "broadcasts", "20260225120018_10000_1000_0_system_commands_queue_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
from_actor_id: 10000
to_actor_id: 1000
channel_id: 0
delegation_chain: "10000:1000"
system_version: "4.0.45"
actor_id: 10000,
purpose: """System Commands Queue Doctrine"""
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# Doctrine #8: System Commands Queue

All post-install/background tasks enqueued in system_commands. NO exec() from PHP. External runners poll, claim, execute. Claim: SELECT queued job, UPDATE to claim WHERE status='queued' AND id=?, proceed only if affected_rows=1. Heartbeats required. Soft delete rules apply.


<!-- FLIP_FOOTER_BEGIN
{
    "references": "\"docs\/status\/broadcast_collection_0.md\"",
    "implements": "\"broadcast_standardization\"",
    "depends_on": "\"registry_seeding_completion\"",
    "includes": "\"channel_0_communications\"",
    "version": "\"4.0.45\"",
    "last_verified": "\"20260225\"",
    "last_verified_by": "\"windsurf\""
}
FLIP_FOOTER_END -->