# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\0\broadcasts\20260225120007_10000_1000_0_forbidden_database_features.md"
  file_hash: "a2b55b0cacd69c7b3c0dcab5782d54d7a35769e434f6cebdf451c9802e815f13"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "channels\0\broadcasts\20260225120007_10000_1000_0_forbidden_database_features.md"
  file_hash: "f8dc1788b8919fec16066645f59d6b94fe18b9ace5eff8c920908d5df72ffc3e"
  file_path_from_root: "channels\0\broadcasts\20260225120007_10000_1000_0_forbidden_database_features.md"
  file_hash: "98d7e0e3b8f7430f8a1cf7773e62d959fba7a39fd8fedc6bf5481185fc8130ff"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225120007_10000_1000_0_forbidden_database_features.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "broadcasts", "20260225120007_10000_1000_0_forbidden_database_featuresmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
from_actor_id: 10000
to_actor_id: 1000
channel_id: 0
delegation_chain: "10000:1000"
system_version: "4.0.45"
actor_id: 10000
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# FORBIDDEN DATABASE FEATURES
Strictly PROHIBITED at the database level:
1. Foreign keys, Triggers, Stored procedures.
2. Views, Database functions, Generated columns.
3. ALL logic MUST live in application code (PHP/Python).
Rationale: DB should be a simple persistence layer. Logic belongs in the code for portability and scaling.


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