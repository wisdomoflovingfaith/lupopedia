# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/doctrine/CHANNEL_GOVERNANCE_LOG_TABLES.md"
  file_hash: "3983e38a42e1cbea77c29266529dd48bfb2feb84d0ee6b09ac69888c4d54d0b2"
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

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\doctrine\CHANNEL_GOVERNANCE_LOG_TABLES.md"
  file_hash: "c6d466271d557c4495a05009cc8ed457cbfbde35bd5327b9c4a79753b542ebca"
  file_path_from_root: "lupo-docs\channels\doctrine\CHANNEL_GOVERNANCE_LOG_TABLES.md"
  file_hash: "5ca051af8e36b276af2b8fea7dcc44a96b31813c83f5702bda7171ebd556446c"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CHANNEL_GOVERNANCE_LOG_TABLES.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "channel_governance_log_tablesmd"]
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
wolfie.headers: explicit architecture with structured clarity for every file.
file.channel: doctrine
file.name: "CHANNEL_GOVERNANCE_LOG_TABLES.md"
---

# Channel Governance Log Tables

This document describes the three tables used for the channel governance log feature. These tables already exist; no new tables or TOON files are created by this documentation.

---

## lupo_channel_log_types

**Purpose:** Registry of log entry types.

- Contains system defaults and allows channel-specific custom types.
- Used to classify governance log entries.
- Key columns: `log_type_id`, `type_key`, `type_label`, `description`, `is_system`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`.

---

## lupo_channel_roles

**Purpose:** Maps actors to roles within a channel: captain, administrator, monitor.

- Determines who can write to the channel log.
- Every actor has one “my channel” where they are captain.
- Key columns: `channel_role_id`, `channel_id`, `actor_id`, `role_type` (enum: captain, administrator, monitor), `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`.

---

## lupo_channel_logs

**Purpose:** Chronological governance ledger for each channel.

- Records who wrote the entry, their role, the log type, the text, and metadata.
- Used for documentation, moderation, administrative notes, and safety concerns.
- Key columns: `channel_log_id`, `channel_id`, `actor_id`, `role_type`, `log_type_id`, `log_text`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`.
