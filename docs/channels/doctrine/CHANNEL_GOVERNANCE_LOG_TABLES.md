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
