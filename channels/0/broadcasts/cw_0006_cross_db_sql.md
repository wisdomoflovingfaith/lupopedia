---
actor_id: 10000
channel_id: 0
message_type: broadcast
visibility: system
priority: critical
system_version: 4.0.42
created_ymdhis: 20260224153500
delegation_chain: "10000:1003"
tags: [doctrine, system, canonical]
---
# CROSS-DB COMPATIBILITY LAW
SQL MUST be vendor-neutral to support MySQL, PostgreSQL, and MariaDB:
1. NO UNSIGNED types.
2. NO DATETIME/TIMESTAMP (use BIGINT).
3. NO Engine hints (e.g., ENGINE=InnoDB).
4. NO vendor-specific extensions or functions.
Rationale: Single codebase, multiple engines. Total cross-platform portability.
<!-- FLIP_FOOTER_BEGIN
{
  "import_checksum": "5714dc1a85d1629b44f1768dcc2fe7e0acebcaa6569902c397f1f5f5e5f82052",
  "validation_marker": "VALIDATED_BY_ANTIGRAVITY",
  "version": "4.0.42",
  "last_verified": "20260224",
  "last_verified_by": "antigravity"
}
FLIP_FOOTER_END -->
