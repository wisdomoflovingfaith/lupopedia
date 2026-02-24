---
actor_id: 10000
channel_id: 0
message_type: broadcast
visibility: system
priority: critical
system_version: 4.0.42
created_ymdhis: 20260224153000
delegation_chain: "10000:1003"
tags: [doctrine, system, canonical]
---
# PHP COMPATIBILITY DOCTRINE
All Lupopedia code MUST:
1. Run on PHP 5.3 (minimum baseline). Use array() not []. No traits, no yield, no splat operator.
2. Run on latest PHP versions (PHP 8.2+). No features that break forward compatibility.
3. No deprecated functions. No reliance on removed behavior.
Rationale: Maximum portability across legacy and modern stacks. Any modern-only syntax will be rejected.
<!-- FLIP_FOOTER_BEGIN
{
  "import_checksum": "8345a50e9be9a88e5bdc64ccb13cbdf1dd94601c81a50def0e15d63b0d9a464f",
  "validation_marker": "VALIDATED_BY_ANTIGRAVITY",
  "version": "4.0.42",
  "last_verified": "20260224",
  "last_verified_by": "antigravity"
}
FLIP_FOOTER_END -->
