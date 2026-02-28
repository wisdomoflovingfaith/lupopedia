# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\doctrine\CHANNEL_GOVERNANCE_LOG_TABLES.md"
  file_hash: "c6d466271d557c4495a05009cc8ed457cbfbde35bd5327b9c4a79753b542ebca"
  file_path_from_root: "docs\channels\doctrine\CHANNEL_GOVERNANCE_LOG_TABLES.md"
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
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
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