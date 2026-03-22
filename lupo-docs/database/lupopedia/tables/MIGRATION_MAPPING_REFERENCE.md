# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md"
  file_hash: "df289e0f079d8d4ec8273077d48b72ec36ba1dad29a5eb3e115e8b4a3a9461f3"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "legacy"
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
  file_path_from_root: "lupo-docs\database\lupopedia\tables\MIGRATION_MAPPING_REFERENCE.md"
  file_hash: "9122bacd95ac2e33f4c69fc874747f9cbb6db8ee8889eab1cf4b03a543facdd5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for MIGRATION_MAPPING_REFERENCE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "migration_mapping_referencemd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md",
  file_hash: "bc00b6ff7b11c7f786f66b448fb7ba36b714cf925bf0cf338f286284ca641ac3"
  system_version: "4.0.50"
  channel_id: 42,
  actor_id: 1003,
  last_modified_utc: "20260227",
  delegation_chain: "10000:1003",
  artifact_type: "documentation",
  purpose: "Index of legacy to Lupopedia table mappings",
  mood_rgb: "00FF00",
  traits: ["canonical", "documentation", "mapping", "history-update"],
  tags: ["legacy-reference", "history-update", "mapping", "doctrine"],
  lupo_agent: "antigravity"
}
lupopedia.edges: {
  file_path_from_root: "lupo-docs\database\lupopedia\tables\MIGRATION_MAPPING_REFERENCE.md"
  outbound_edges: [
    { to: "lupo-docs/channels/appendix/HISTORY.md", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["migration_index", "legacy_mapping"]
}
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer: {
  last_verified_utc: "20260227",
  last_verified_by: "antigravity"
}
---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Mapping Reference

**Source of truth:** Every file in `lupo-docs/database/lupopedia/tables/`.  
This document is a concise index of legacy → Lupopedia table/behavior mappings. Use it together with `lupo-docs/notes_from_legacy_craftysyntax.md` (behavior) and the individual migration `.md` files (authoritative).

> [!NOTE]
> For historical context on the evolution from Crafty Syntax to Lupopedia and the rationale behind legacy deprecation, see the Full Project History.

---

## Identity & operators

| Legacy | New | Notes |
|--------|-----|--------|
| livehelp_users | lupo_auth_users | Identity/credentials (username, display_name, email, password_hash, auth_provider, provider_id, last_login_ymdhis). Operators imported first, then visitors. |
| livehelp_users | lupo_actors, lupo_actor_properties | Related: presence, device, behavioral metadata in actor_properties. **Operator permissions** are not a table; they use the **3-level role system**: channel roles (lupo_actor_channel_roles: captain, administrator, monitor), department roles (lupo_department_roles), system (department_id = 0). See lupo-docs/database/lupopedia/tables/active/lupo_actor_channel_roles.md and OPERATOR_TO_ROLE_BASED_SWEEP_REPORT. |
| livehelp_operator_departments | lupo_actor_departments | recno→actor_department_id, user_id→actor_id, department→department_id, extra→title. |

---

## Channels, threads, messages

| Legacy | New | Notes |
|--------|-----|--------|
| livehelp_messages | **DROPPED** | Ephemeral buffer only. No import. Durable data from livehelp_transcripts. |
| livehelp_transcripts | lupo_dialog_threads, lupo_dialog_messages | One thread per transcript; one message per thread containing full transcript text. recno→dialog_thread_id/dialog_message_id, transcript→message content, starttime/endtime→created_ymdhis/updated_ymdhis. |
| livehelp_operator_channels | **DROPPED** | Functionality replaced by lupo_channels, lupo_channel_membership / lupo_actor_channels, lupo_dialog_threads, lupo_actor_presence, metadata_json for UI colors. |
| livehelp_channels | **DROPPED** | Operator workspace concept; replaced by UI + lupo_dialog_threads, lupo_channels (real channels). |

**Channel interface:** Use **lupo_dialog_threads** (threads, bg_color in metadata/thread), **lupo_dialog_messages** (messages, created_ymdhis, from_actor_id, to_actor_id, message_text), **lupo_channels**, **lupo_actor_channels** (actor–channel membership), **lupo_actors**, **lupo_actor_channel_roles** (channel-scoped roles: captain, administrator, monitor; replaces legacy operator assignment).

---

## Quick notes / canned responses

| Legacy | New | Notes |
|--------|-----|--------|
| livehelp_quick | lupo_actor_reply_templates | id→actor_reply_template_id, user→actor_id, name→template_key, message→template_text, typeof→usage_context. |

---

## Invites

| Legacy | New | Notes |
|--------|-----|--------|
| livehelp_layerinvites | lupo_crafty_syntax_layer_invites | name→layer_name, imagename→image_name, imagemap→image_map, department→department_name, user→user_id. Compatibility table. |
| livehelp_autoinvite | lupo_crafty_syntax_auto_invite | idnum→crafty_syntax_auto_invite_id, isactive→is_active, department→department_id, message, page→page_url, referer→referrer_url, typeof→invite_type, etc. |

---

## Departments & config

| Legacy | New | Notes |
|--------|-----|--------|
| livehelp_departments | lupo_departments, lupo_department_metadata | Core identity in lupo_departments; UI/behavior/branding in lupo_department_metadata (JSON). |
| livehelp_config | lupo_modules.config_json | module_id = 1 (Crafty Syntax). All legacy keys preserved as JSON. |

---

## Sessions, identity, presence

| Legacy | New | Notes |
|--------|-----|--------|
| livehelp_sessions | **DROPPED** | Replaced by lupo_sessions (deterministic, actor-aware). No import. |
| **{prefix}sessions** | **MERGED & DROPPED** | Logic merged into {prefix}sessions; table removed from install. Single session table is {prefix}sessions. See one_time_sessions_to_sessions.sql. |
| **{prefix}actor_roles** | **DROPPED** | Replaced by **3-level role system**: (1) **lupo_actor_channel_roles** (channel-scoped: captain, administrator, monitor); (2) **lupo_department_roles** (department-scoped); (3) system (department_id = 0 = global admin). Resolution: channel → department → system. See drop_lupo_actor_roles.sql and lupo-docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md. |
| livehelp_identity_daily | **DROPPED** | No import. |
| livehelp_identity_monthly | **DROPPED** (no import) | Anonymous users are not in lupo_actors; they exist in lupo_sessions only. No anonymous actor rows or range. |
| livehelp_operator_channels (presence/colors) | lupo_actor_presence, metadata_json | Operator presence and UI colors; see livehelp_operator_channels_migration.md. |

---

## Pre-chat, leave message, CRM, audit

| Legacy | New | Notes |
|--------|-----|--------|
| livehelp_questions | lupo_crafty_syntax_chat_questions | Pre-chat intake form config. |
| livehelp_leavemessage | lupo_crafty_syntax_leave_message | Offline “leave a message” submissions. |
| livehelp_leads | lupo_crm_leads | Lead capture. |
| livehelp_emails | lupo_crm_lead_messages | Broadcast emails; lead_id=1 (broadcast lead). |
| livehelp_operator_history | lupo_audit_log | entity_type='actor', entity_id=opid, event_type=action, payload_json for session. |

---

## Analytics & referers

| Legacy | New | Notes |
|--------|-----|--------|
| livehelp_referers_daily, livehelp_referers_monthly | lupo_referers | Legacy fields in metadata_json where needed. |
| livehelp_visit_track | **DROPPED** | Ephemeral. |
| livehelp_visits_daily, livehelp_visits_monthly | lupo_visits | |
| livehelp_paths_firsts, livehelp_paths_monthly | lupo_analytics_paths | transition_type 'first' | 'all'. |
| livehelp_keywords_* | **DROPPED** | Replaced by lupo_analytics_campaign_vars (no import). |

---

## Other

| Legacy | New | Notes |
|--------|-----|--------|
| livehelp_websites | lupo_federation_nodes | Multi-site / federation registry. |
| livehelp_qa | lupo_truth_questions, lupo_truth_answers, lupo_collections, lupo_collection_tabs | Questions/answers + folder hierarchy. |
| livehelp_modules | **DROPPED** | lupo_modules is predefined registry. No import. |
| livehelp_modules_dep | lupo_modules_departments | No legacy import; table created, modules enabled by default. |
| livehelp_smilies | **DROPPED** | chat_smilies/ directory + \|:emoji src="..." :\| token format. |
| livehelp_emailque | **DROPPED** | Mail subsystem; no replacement in migration. |

---

## Channel interface checklist (doctrine + legacy notes)

- **Messages:** lupo_dialog_messages (created_ymdhis, from_actor_id, to_actor_id, message_text, dialog_thread_id, channel_id). Order by created_ymdhis ASC. Interleave all threads (legacy §1).
- **Thread colors:** lupo_dialog_threads.bg_color (legacy channelcolor from operator_channels; doctrine: thread-level metadata).
- **Tabs:** One per thread (lupo_dialog_threads) for this channel; selecting tab = composer target only (legacy §5).
- **Quick notes:** lupo_actor_reply_templates (template_key, template_text, usage_context, actor_id). Use in composer (legacy §10).
- **Typing:** Ephemeral (file cache or equivalent); clear all on send (legacy §3).
- **Presence:** lupo_actor_presence / lupo_actor_properties where defined; lastaction/isonline/status from legacy map to new presence or session (livehelp_users_migration, operator_channels_migration).
- **Invites:** lupo_crafty_syntax_layer_invites, lupo_crafty_syntax_auto_invite for layer and auto-invite (legacy §8).
- **Operators/visitors list:** lupo_actors, lupo_actor_channel_roles (who has which role on this channel), lupo_actor_channels (who is on this channel).
- **Paths:** All internal paths use LUPOPEDIA_PUBLIC_PATH; never hardcode folder names.

---

---

## Operator-to-roles (no lupo_operators)

**lupo_operators** and **lupo_operators_*** tables were removed. Permissions use the **3-level role system** (lupo_actor_channel_roles, lupo_department_roles, system department_id=0). See **operator_to_roles_migration.md** in this folder and **lupo-docs/doctrine/database/README.md** for the table index.

---

*Generated from /docs/database/lupopedia/tables/*.md. For full field mappings and rationale, read the corresponding migration file.*
