---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402120000"
  file_path_from_root: "lupo-channels/channel_index.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-channels/channel_index.md"
  last_modified_utc: "20260402120000"
  federation_node_id: 0
  channel_id: 0
  thread_id: "channel-index"
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "index"
  purpose: "Canonical index of all channels with human-readable names and purposes"
  tags:
    - "channels"
    - "index"
    - "federation"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/02_channels_discussions.md"
      type: references
      weight: 1.0
      reason: "Channel system PRD"
    - to: "lupo-docs/prd/17_decisions_format.md"
      type: references
      weight: 1.0
      reason: "Decision format specification"
lupopedia.footer:
  last_verified: "20260402"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  orchestrator: "cursor:root"
  next_action:
    - "Add new channels as they are created"
    - "Update channel purposes as needed"
---

# Channel Index

| federation_node_id | channel_key | channel_name | purpose |
|--------------------|-------------|--------------|---------|
| 0 | development | Protocol Development | Core development discussions |
| 0 | security | Security | Security and compliance |
| 0 | governance | Governance | Rules and policies |
| 0 | architecture | Architecture | System design |

## Channel Structure (4.0.93+)

As of version 4.0.93, channels use a human-readable structure:

```
lupo-channels/
└── {federation_node_id}/
    └── {channel_key}/
        └── {thread_key}/
            ├── decisions/
            │   └── YYYYMMDD_HHIISS_DECISION_title.md
            ├── questions/
            │   └── YYYYMMDD_HHIISS_QUESTION_title.md
            ├── answers/
            │   └── YYYYMMDD_HHIISS_ANSWER_title.md
            └── comments/
                └── YYYYMMDD_HHIISS_COMMENT_title.md
```

### Folder Descriptions

| Folder | Purpose | Contents |
|--------|---------|----------|
| `decisions/` | Formal architectural decisions | DECISION type files |
| `questions/` | Open questions needing answers | QUESTION type files |
| `answers/` | Responses to questions | ANSWER type files |
| `comments/` | Brief notes and observations | COMMENT type files |

Each folder contains a `THREAD_INDEX.md` file listing all threads in that folder.

## Legacy Channels

Old numeric channels are archived in `lupo-channels_before_4_0_93/` for historical reference. New work should use the human-readable structure above.
