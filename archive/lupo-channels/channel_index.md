---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-channels/channel_index.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-channels/channel_index.md"
  status: "active"
  when_updated: "20260418142000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/channels/canonical/1026/04/channel-index.toon"
  atoms_toon: null
  transcript_jsonl: "0/organization/channel-index"
  artifact_type: documentation
  artifact_kind: index
  channel_key: "organization"
  federation_node_id: 0
  thread_id: "channel-index"
  content_id: null
  content_parent_id: null
  content_slug: "channel-index"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "Channel Index — Lupopedia"
  summary: "Canonical index of all channels with human-readable names, purposes, and structure."
---

# Channel Index

| federation_node_id | channel_key | channel_name | purpose |
|--------------------|-------------|--------------|---------|
| 0 | development | Protocol Development | Core development discussions |
| 0 | security | Security | Security and compliance |
| 0 | governance | Governance | Rules and policies |
| 0 | architecture | Architecture | System design |
| 0 | organization | Organization | Repo layout, docs system structure, PRD 29 coordination. **Key status file:** [documentation_gaps.md](../lupo-docs/versions/4.1.2/status/documentation_gaps.md) |
| 0 | semantic | Semantic & Knowledge Systems | Mood Vector, truth/knowledge graph, semantic engine, TOON schema, context systems |
| 0 | translation | Translation & Communications | Explain internal doctrine clearly to external non-technical/business audiences |

## Channel Structure (4.0.93+)

As of version 4.0.93, channels use a human-readable structure:

```
lupo-channels/
+-- {federation_node_id}/
    +-- {channel_key}/
        +-- {thread_key}/
            +-- decisions/
            |   +-- YYYYMMDD_HHIISS_DECISION_title.md
            +-- questions/
            |   +-- YYYYMMDD_HHIISS_QUESTION_title.md
            +-- answers/
            |   +-- YYYYMMDD_HHIISS_ANSWER_title.md
            +-- comments/
                +-- YYYYMMDD_HHIISS_COMMENT_title.md
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
