---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/channels.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/database/channels.md
---

# lupo_channels

**Purpose:** **Channel identity**: each channel has an id, name, key/slug, description, optional department linkage, and lifecycle fields. Channels are the scope for dialog threads, messages, and **channel-scoped roles** (lupo_actor_channel_roles). Reserved channel IDs (0, 1, 42, 51) are created by the installer; channel 1 is “Administration” (global admin).

**Schema:** See `docs/toons/lupo_channels.toon.json`. Primary key: `channel_id`. Reserved IDs are not AUTO_INCREMENT; they are inserted by seed/wizard.

---

## Use and need

- **Dialog and UI:** Threads (lupo_dialog_threads) and messages (lupo_dialog_messages) are tied to a channel_id. Who can see/manage a channel is determined by lupo_actor_channel_roles (captain, administrator, monitor).
- **Reserved channels:** 0 (system), 1 (Administration), 42 (e.g. dev), 51 (e.g. ai-dev). Created in install_wizard_classes.php (createReservedSystemChannels). Channel 1 is the global admin channel; Crafty admins get captain on channel 1.
- **Personal channels:** On upgrade, the wizard creates one channel per imported Crafty operator (“Actor’s Channel”) and assigns that actor as captain in lupo_actor_channel_roles.
- **No legacy “workspace” import:** Legacy livehelp_channels and livehelp_operator_channels are DROPPED; their functionality is replaced by this table plus lupo_dialog_threads, lupo_actor_presence, and metadata for UI colors.

---

## Mapping from Crafty Syntax

**Legacy:** `livehelp_channels` (operator workspace concept), `livehelp_operator_channels` (operator–channel–visitor routing). Both are **DROPPED**; no direct row-by-row import into lupo_channels from them. New installs get reserved channels from seed/wizard; upgrades get reserved channels plus one new channel per imported operator (personal channel). See `livehelp_channels_migration.md`, `livehelp_operator_channels_migration.md`, and `MIGRATION_MAPPING_REFERENCE.md`.
