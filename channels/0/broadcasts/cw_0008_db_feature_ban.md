---
actor_id: 10000
channel_id: 0
message_type: broadcast
visibility: system
priority: critical
system_version: 4.0.42
created_ymdhis: 20260224153700
delegation_chain: "10000:1003"
tags: [doctrine, system, canonical]
---
# FORBIDDEN DATABASE FEATURES
Strictly PROHIBITED at the database level:
1. Foreign keys, Triggers, Stored procedures.
2. Views, Database functions, Generated columns.
3. ALL logic MUST live in application code (PHP/Python).
Rationale: DB should be a simple persistence layer. Logic belongs in the code for portability and scaling.
<!-- FLIP_FOOTER_BEGIN
{
  "import_checksum": "446e647d6975349d66998ec13557f48c5578365e9ac72c5357d582f0a9ca38e0",
  "validation_marker": "VALIDATED_BY_ANTIGRAVITY",
  "version": "4.0.42",
  "last_verified": "20260224",
  "last_verified_by": "antigravity"
}
FLIP_FOOTER_END -->
