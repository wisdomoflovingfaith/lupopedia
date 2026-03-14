# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\0\broadcasts\20260225120032_10000_1000_0_mandatory_minimum_flip_header_requirements.md"
  file_hash: "d1bdf1a64c2dd5b76a8ff4e405f6c88e6510aeee5ee40dd421ab96461bbf295c"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\0\broadcasts\20260225120032_10000_1000_0_mandatory_minimum_flip_header_requirements.md"
  file_hash: "f02c2b0446e7e0cfb35a491a2aa7718974c6289f8a1da16fb04dfd911583038f"
  file_path_from_root: "lupo-channels\0\broadcasts\20260225120032_10000_1000_0_mandatory_minimum_flip_header_requirements.md"
  file_hash: "79707ba57f602d6debe3c0afeaa7e667cf8f59a22ecb4159535ac1bf305f1f17"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225120032_10000_1000_0_mandatory_minimum_flip_header_requirements.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "broadcasts", "20260225120032_10000_1000_0_mandatory_minimum_flip_header_requirementsmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
from_actor_id: 10000
to_actor_id: 1000
channel_id: 0
delegation_chain: "10000:1000"
system_version: "4.0.45"
actor_id: 10000,
purpose: """Mandatory Minimum FLIP Header Requirements"""
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225120000
created_utc: "2026-02-25T12:00:00Z"
---
# Doctrine #12: Mandatory Minimum FLIP Header Requirements

All .md files created by any IDE agent must include the minimum FLIP header block:

```yaml
---
wolfie.headers: {
  file_path_from_root: "<FULL_PATH_FROM_PROJECT_ROOT>",
  system_version: "<CURRENT_LUPOPEDIA_VERSION>",
  channel_id: <CURRENT_CHANNEL_ID>,
  actor_id: <AUTHOR_ACTOR_ID>,
  to_actor_id: <RECIPIENT_ACTOR_ID>,
  created_ymdhis: <UTC_BIGINT_TIMESTAMP>,
  updated_ymdhis: <UTC_BIGINT_TIMESTAMP>
}
---
```

## Rules

- `file_path_from_root` must be the exact path from the project root
- `system_version` must match the active Lupopedia version (currently 4.0.43)
- `channel_id` must reflect the folder the file lives in
- All timestamps must be BIGINT UTC (YYYYMMDDHHIISS)
- No agent may create an .md file without this header
- No agent may add browser-tab metadata, system metadata, or external content into FLIP headers
- The filesystem is the source of truth until the DB is online after install

**Compliance is mandatory for all IDE agents.**


<!-- FLIP_FOOTER_BEGIN
{
    "references": "\"lupo-docs\/status\/broadcast_collection_0.md\"",
    "implements": "\"broadcast_standardization\"",
    "depends_on": "\"registry_seeding_completion\"",
    "includes": "\"channel_0_communications\"",
    "version": "\"4.0.45\"",
    "last_verified": "\"20260225\"",
    "last_verified_by": "\"windsurf\""
}
FLIP_FOOTER_END -->
