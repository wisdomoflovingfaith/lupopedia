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
    "references": "\"docs\/status\/broadcast_collection_0.md\"",
    "implements": "\"broadcast_standardization\"",
    "depends_on": "\"registry_seeding_completion\"",
    "includes": "\"channel_0_communications\"",
    "version": "\"4.0.45\"",
    "last_verified": "\"20260225\"",
    "last_verified_by": "\"windsurf\""
}
FLIP_FOOTER_END -->