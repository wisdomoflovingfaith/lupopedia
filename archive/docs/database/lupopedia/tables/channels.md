---
lupopedia.headers:
  file_path_from_root: "docs/database/lupopedia/tables/channels.md"
  file_hash: "8404fdb85aef81e30e0e21a57a52df62a8620934ef3a66fa3d17da7cc57a5eef"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Core identity and configuration for communication channels"
  lupo_agent: "gemini-cli"

lupopedia.edges:
  file_path_from_root: "docs\database\lupopedia\tables\channels.md"
  outbound_edges:
- { to: "docs/database/lupopedia/tables/lupo_dialog_threads.md", type: "references", weight: 0.9, reason: "Threads are scoped to channels" }
    - { to: "docs/database/lupopedia/tables/actor_channel_roles.md", type: "references", weight: 0.9, reason: "Access control per channel" }
    - { to: "database/lupopedia/toon/lupo_channels.toon.json", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["channels", "communication", "dialog", "structure"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_channels
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: **Channel identity**: each channel has an id, name, key/slug, description, optional department linkage, and lifecycle fields. Channels are the scope for dialog threads, messages, and **channel-scoped roles** (lupo_actor_channel_roles). Reserved channel IDs (0, 1, 42, 51) are created by the installer; channel 1 is â€œAdministrationâ€ (global admin).

**Schema:** See `database/lupopedia/toon/lupo_channels.toon.json`. Primary key: `channel_id`. Reserved IDs are not AUTO_INCREMENT; they are inserted by seed/wizard.

### 2. Core Workflows

- **Dialog and UI:** Threads (lupo_dialog_threads) and messages (lupo_dialog_messages) are tied to a channel_id. Who can see/manage a channel is determined by lupo_actor_channel_roles (captain, administrator, monitor).
- **Reserved channels:** 0 (system), 1 (Administration), 42 (e.g. dev), 51 (e.g. ai-dev). Created in install_wizard_classes.php (createReservedSystemChannels). Channel 1 is the global admin channel; Crafty admins get captain on channel 1.
- **Personal channels:** On upgrade, the wizard creates one channel per imported Crafty operator (â€œActorâ€™s Channelâ€) and assigns that actor as captain in lupo_actor_channel_roles.
- **No legacy â€œworkspaceâ€ import:** Legacy livehelp_channels and livehelp_operator_channels are DROPPED; their functionality is replaced by this table plus lupo_dialog_threads, lupo_actor_presence, and metadata for UI colors.

### 3. Mapping from Crafty Syntax

**Legacy:** `livehelp_channels` (operator workspace concept), `livehelp_operator_channels` (operatorâ€“channelâ€“visitor routing). Both are **DROPPED**; no direct row-by-row import into lupo_channels from them. New installs get reserved channels from seed/wizard; upgrades get reserved channels plus one new channel per imported operator (personal channel). See `livehelp_channels_migration.md`, `livehelp_operator_channels_migration.md`, and `MIGRATION_MAPPING_REFERENCE.md`.

---
*Maintained by GEMINI (Actor 1006)*

