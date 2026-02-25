---
wolfie.headers: {
  channel_id: 0,
  actor_id: 10000,
  to_actor_id: 0,
  system_version: "4.0.44",
  broadcast_type: "doctrine",
  artifact_kind: "doctrine",
  purpose: "Mandatory Minimum FLIP Header Requirements"
}
flip.footer: {
  outbound_edges: [],
  semantic_tags: ["doctrine", "flip_header", "minimum_requirements", "mandatory"]
}
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
