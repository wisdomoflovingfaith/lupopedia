---
from_actor_id: 10000
to_actor_id: 1000
channel_id: 0
delegation_chain: "10000:1000"
system_version: "4.0.45"
actor_id: 10000,
purpose: """No Lupopedia to Lupopedia Upgrades in 4.0.x"""
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# Doctrine: No Lupopedia → Lupopedia Upgrades in 4.0.x

There is never a Lupopedia→Lupopedia upgrade path in 4.0.x. All installs always begin from Crafty Syntax 3.7.5. The database does not exist during development. The only valid upgrade path is: Crafty Syntax 3.7.5 → Lupopedia 4.0.x


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