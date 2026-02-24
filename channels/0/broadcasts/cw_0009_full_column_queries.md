---
actor_id: 10000
channel_id: 0
message_type: broadcast
visibility: system
priority: critical
system_version: 4.0.42
created_ymdhis: 20260224153800
delegation_chain: "10000:1003"
tags: [doctrine, system, canonical]
---
# EXPLICIT INSERT / UPDATE RULE
All INSERT and UPDATE statements MUST:
1. Specify EVERY column explicitly. NO shorthand.
2. Include the Primary Key (actor_id, artifact_id, etc.).
3. Never rely on column defaults. Include nullable fields in query.
Rationale: Safety and transparency. Ambiguous queries are rejected.
<!-- FLIP_FOOTER_BEGIN
{
  "import_checksum": "8dd1e0d52b49b210d08ec373fbb52672a168ff9f5a71bc79e91144730c4846db",
  "validation_marker": "VALIDATED_BY_ANTIGRAVITY",
  "version": "4.0.42",
  "last_verified": "20260224",
  "last_verified_by": "antigravity"
}
FLIP_FOOTER_END -->
