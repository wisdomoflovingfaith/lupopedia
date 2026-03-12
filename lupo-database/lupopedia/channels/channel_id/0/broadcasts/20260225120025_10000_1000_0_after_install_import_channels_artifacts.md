# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\0\broadcasts\20260225120025_10000_1000_0_after_install_import_channels_artifacts.md"
  file_hash: "5b1b88688a23b9d36f88a992d4e8e73fb366e9390a48879554793e424a33b043"
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
  file_path_from_root: "channels\0\broadcasts\20260225120025_10000_1000_0_after_install_import_channels_artifacts.md"
  file_hash: "f7013debe275553997d8b6da6cb1c619e7d0aebdfa1ba51bccf9b5f79202d6a7"
  file_path_from_root: "channels\0\broadcasts\20260225120025_10000_1000_0_after_install_import_channels_artifacts.md"
  file_hash: "86b5209755f82d5e7ce710daa5d6c4a455f98cd9837cb329681a07d38ae9dd58"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225120025_10000_1000_0_after_install_import_channels_artifacts.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "broadcasts", "20260225120025_10000_1000_0_after_install_import_channels_artifactsmd"]
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
actor_id: 10000,
purpose: """After Install, Import Channels + Artifacts"""
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# Doctrine: After Install, Import Channels + Artifacts

After install.php creates the DB, Lupopedia must import /channels and /artifacts. These contain all offline messages created while the DB was unreachable. Import is done via the system_commands queue. The importer script runs outside PHP.


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