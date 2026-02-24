---
actor_id: 10000
channel_id: 0
message_type: broadcast
visibility: system
priority: critical
system_version: 4.0.42
created_ymdhis: 20260224153900
delegation_chain: "10000:1003"
tags: [doctrine, system, canonical]
---
# ID ALLOCATION AUTHORITY
Primary keys MUST NOT auto-increment.
1. Allocate IDs from registry_open.
2. Verify against registry (permanent/reserved keys).
3. NEVER reuse protected or locked IDs.
4. Management must be explicit in application code.
Rationale: Prevents collisions across federated nodes and master registries.
<!-- FLIP_FOOTER_BEGIN
{
  "import_checksum": "810a70d030dfa3304bda6a0376004c699b4ae1af08a2cc6f74906c898bcc6abd",
  "validation_marker": "VALIDATED_BY_ANTIGRAVITY",
  "version": "4.0.42",
  "last_verified": "20260224",
  "last_verified_by": "antigravity"
}
FLIP_FOOTER_END -->
