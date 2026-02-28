# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\broadcasts\20260225120023_10000_1000_0_no_lupopedia_to_lupopedia_upgrades_in_4_0_x.md"
  file_hash: "bfbdc65636e6fa99f9e3fd957a510b4c87e1a8e1a635958a846471f9163c5376"
  file_path_from_root: "channels\0\broadcasts\20260225120023_10000_1000_0_no_lupopedia_to_lupopedia_upgrades_in_4_0_x.md"
  file_hash: "7f7843fa88e7d950ad974c39513db6c1a9f594316f6ed118b62ab83f71bbe6cb"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225120023_10000_1000_0_no_lupopedia_to_lupopedia_upgrades_in_4_0_x.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "broadcasts", "20260225120023_10000_1000_0_no_lupopedia_to_lupopedia_upgrades_in_4_0_xmd"]
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