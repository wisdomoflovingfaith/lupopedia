# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\channels\0\broadcasts\20260225120012_10000_1000_0_bigint_utc_timestamps_doctrine.md"
  file_hash: "2ce04edb82ea51c8f00382890d4ac2c76fcc060eae4c6162769c8b15d8c2aa62"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\broadcasts\20260225120012_10000_1000_0_bigint_utc_timestamps_doctrine.md"
  file_hash: "2ea860b14379c0d1a638c39fd1f5bc3aed1ee8c1b1ca01c103beae2864cb313e"
  file_path_from_root: "channels\0\broadcasts\20260225120012_10000_1000_0_bigint_utc_timestamps_doctrine.md"
  file_hash: "ebdecccebaae787952ba295282e68ca55bbde68b7b5cabdcab28c987805bf303"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225120012_10000_1000_0_bigint_utc_timestamps_doctrine.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "broadcasts", "20260225120012_10000_1000_0_bigint_utc_timestamps_doctrinemd"]
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
purpose: """BIGINT UTC Timestamps Doctrine"""
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# Doctrine #2: BIGINT UTC Timestamps

All timestamps = BIGINT YYYYMMDDHHIISS format, 24-hour, UTC only. No DATETIME, no TIMESTAMP. Use gmdate('YmdHis') in PHP.


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