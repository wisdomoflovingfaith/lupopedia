# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\broadcasts\20260225120024_10000_1000_0_install_php_creates_all_tables.md"
  file_hash: "db6a21cb0fe0b8674f3abcd84260b770a463f98ffbc4faa5520079c5a44de68a"
  file_path_from_root: "channels\0\broadcasts\20260225120024_10000_1000_0_install_php_creates_all_tables.md"
  file_hash: "92fafcb037de263627a5fc09903af94ce181b05cb66ffa2230d19c83a0d67b14"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225120024_10000_1000_0_install_php_creates_all_tables.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "broadcasts", "20260225120024_10000_1000_0_install_php_creates_all_tablesmd"]
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
purpose: """install.php Creates All Tables"""
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# Doctrine: install.php Creates All Tables

install.php loads install_new_lupopedia.sql. install_new_lupopedia.sql is the canonical schema. No migrations run after install. No schema drift is allowed. All schema changes must be made only in install_new_lupopedia.sql.


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