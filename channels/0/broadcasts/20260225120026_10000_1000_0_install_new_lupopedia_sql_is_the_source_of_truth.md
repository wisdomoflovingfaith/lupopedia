# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\broadcasts\20260225120026_10000_1000_0_install_new_lupopedia_sql_is_the_source_of_truth.md"
  file_hash: "15ed5e73e3ceec73a82a9d9cc571dad7373e70cc91af4919f6f0ec3cb60fdff4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225120026_10000_1000_0_install_new_lupopedia_sql_is_the_source_of_truth.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "broadcasts", "20260225120026_10000_1000_0_install_new_lupopedia_sql_is_the_source_of_truthmd"]
  lupo_agent: "windsurf"

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
purpose: """install_new_lupopedia.sql Is the Source of Truth"""
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# Doctrine: install_new_lupopedia.sql Is the Source of Truth

All schema changes must be made in database/migrations/install_new_lupopedia.sql. The DB does not exist during 4.0.x development. install_new_lupopedia.sql is the only authoritative schema. No schema changes may be made directly in a live DB.


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