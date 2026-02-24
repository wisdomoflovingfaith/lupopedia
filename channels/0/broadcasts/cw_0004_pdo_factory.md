---
actor_id: 10000
channel_id: 0
message_type: broadcast
visibility: system
priority: critical
system_version: 4.0.42
created_ymdhis: 20260224153300
delegation_chain: "10000:1003"
tags: [doctrine, system, canonical]
---
# DATABASE ACCESS STANDARD
All database interactions MUST:
1. Use the PDO wrapper. NO raw mysqli.
2. Use DatabaseFactory::getConnection() or helper lupo_get_db().
3. Use prepared statements with named placeholders.
4. NO inline connects. NO singleton hacks. Logic must be object-oriented.
Rationale: Unified connection management and injection protection.
<!-- FLIP_FOOTER_BEGIN
{
  "import_checksum": "74c75bc30f6f91a4580138d0c5916e29460c761d2c295de0967421d4f06a3653",
  "validation_marker": "VALIDATED_BY_ANTIGRAVITY",
  "version": "4.0.42",
  "last_verified": "20260224",
  "last_verified_by": "antigravity"
}
FLIP_FOOTER_END -->
