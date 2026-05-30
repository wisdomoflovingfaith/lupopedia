---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/MigrationAtlas.md"
  web_path: null
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\MigrationAtlas.md"
  file_hash: "e2b6c78930ea93aa5b91a405ee76e74eb130511d9c24b3a894ce6dc2cf388675"
  file_path_from_root: "lupo-docs\doctrine\MigrationAtlas.md"
  file_hash: "97f4adfd6298af2ebde0cdcd6fea8d1ea5904bb13e98b74f18c8466c26a50700"
  last_updated_utc: "20260228"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for MigrationAtlas.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "migrationatlasmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.88"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "cursor"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
lupopedia.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/MigrationAtlas.md
file.last_modified_system_version: "4.0.88"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_vector: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/MigrationAtlas.md
---

# Migration Atlas

| Legacy Table | Status | Replacement Table(s) | Doctrine File |
|--------------|--------|----------------------|----------------|
| livehelp_autoinvite | IMPORTED -> DROPPED | lupo_crafty_syntax_auto_invite | [livehelp_autoinvite_migration.md](../database/lupopedia/tables/migrations/livehelp_autoinvite_migration.md) |
| livehelp_channels | DROPPED | lupo_channels, lupo_dialog_threads (routing subsystem + dialog system) | [livehelp_channels_migration.md](../database/lupopedia/tables/migrations/livehelp_channels_migration.md) |
| livehelp_config | PARTIALLY IMPORTED -> DROPPED | lupo_modules.config_json (module_id = 1) | [livehelp_config_migration.md](../database/lupopedia/tables/migrations/livehelp_config_migration.md) |
| livehelp_departments | IMPORTED -> SPLIT -> DROPPED | lupo_departments, lupo_department_metadata | [livehelp_departments_migration.md](../database/lupopedia/tables/migrations/livehelp_departments_migration.md) |
| livehelp_emailque | DROPPED | None (mail subsystem; delivery logging in CRM if needed) | [livehelp_emailque_migration.md](../database/lupopedia/tables/migrations/livehelp_emailque_migration.md) |
| livehelp_emails | IMPORTED -> DROPPED | lupo_crm_lead_messages | [livehelp_emails_migration.md](../database/lupopedia/tables/migrations/livehelp_emails_migration.md) |
| livehelp_identity_daily | DROPPED (no import) | Anonymous users in lupo_sessions only; no lupo_actors rows | [livehelp_identity_migration.md](../database/lupopedia/tables/migrations/livehelp_identity_migration.md) |
| livehelp_identity_monthly | DROPPED (no import) | Anonymous users in lupo_sessions only; no lupo_actors rows | [livehelp_identity_migration.md](../database/lupopedia/tables/migrations/livehelp_identity_migration.md) |
| livehelp_keywords_daily | DROPPED | lupo_analytics_campaign_vars | [livehelp_keywords_migration.md](../database/lupopedia/tables/migrations/livehelp_keywords_migration.md) |
| livehelp_keywords_monthly | DROPPED | lupo_analytics_campaign_vars | [livehelp_keywords_migration.md](../database/lupopedia/tables/migrations/livehelp_keywords_migration.md) |
| livehelp_layerinvites | IMPORTED -> DROPPED | lupo_crafty_syntax_layer_invites | [livehelp_layerinvites_migration.md](../database/lupopedia/tables/migrations/livehelp_layerinvites_migration.md) |
| livehelp_leads | IMPORTED -> DROPPED | lupo_crm_leads | [livehelp_leads_migration.md](../database/lupopedia/tables/migrations/livehelp_leads_migration.md) |
| livehelp_leavemessage | IMPORTED -> DROPPED | lupo_crafty_syntax_leave_message | [livehelp_leavemessage_migration.md](../database/lupopedia/tables/migrations/livehelp_leavemessage_migration.md) |
| livehelp_messages | DROPPED | None (dialog system; durable transcripts from livehelp_transcripts) | [livehelp_messages_migration.md](../database/lupopedia/tables/migrations/livehelp_messages_migration.md) |
| livehelp_modules | DROPPED | lupo_modules | [livehelp_modules_migration.md](../database/lupopedia/tables/migrations/livehelp_modules_migration.md) |
| livehelp_modules_dep | DROPPED | lupo_modules_departments | [livehelp_modules_dep_migration.md](../database/lupopedia/tables/migrations/livehelp_modules_dep_migration.md) |
| livehelp_operator_channels | DROPPED | lupo_channels, lupo_dialog_threads (routing subsystem + dialog system) | [livehelp_operator_channels_migration.md](../database/lupopedia/tables/migrations/livehelp_operator_channels_migration.md) |
| livehelp_operator_departments | IMPORTED -> DROPPED | lupo_actor_departments | [livehelp_operator_departments_migration.md](../database/lupopedia/tables/migrations/livehelp_operator_departments_migration.md) |
| livehelp_operator_history | IMPORTED -> DROPPED | lupo_audit_log | [livehelp_operator_history_migration.md](../database/lupopedia/tables/migrations/livehelp_operator_history_migration.md) |
| livehelp_paths_firsts | IMPORTED -> DROPPED | lupo_analytics_paths | [livehelp_paths_firsts_migration.md](../database/lupopedia/tables/migrations/livehelp_paths_firsts_migration.md) |
| livehelp_paths_monthly | IMPORTED -> DROPPED | lupo_analytics_paths | [livehelp_paths_firsts_migration.md](../database/lupopedia/tables/migrations/livehelp_paths_firsts_migration.md) |
| livehelp_qa | IMPORTED -> DROPPED | lupo_collection_tabs, lupo_collections, lupo_truth_answers, lupo_truth_questions | [livehelp_qa_migration.md](../database/lupopedia/tables/migrations/livehelp_qa_migration.md) |
| livehelp_questions | IMPORTED -> DROPPED | lupo_crafty_syntax_chat_questions | [livehelp_questions_migration.md](../database/lupopedia/tables/migrations/livehelp_questions_migration.md) |
| livehelp_quick | IMPORTED -> DROPPED | lupo_actor_reply_templates | [livehelp_quick_migration.md](../database/lupopedia/tables/migrations/livehelp_quick_migration.md) |
| livehelp_referers_daily | IMPORTED -> DROPPED | lupo_referers (analytics subsystem) | [livehelp_referers_daily_migration.md](../database/lupopedia/tables/migrations/livehelp_referers_daily_migration.md) |
| livehelp_referers_monthly | IMPORTED -> DROPPED | lupo_referers (analytics subsystem) | [livehelp_referers_daily_migration.md](../database/lupopedia/tables/migrations/livehelp_referers_daily_migration.md) |
| livehelp_sessions | DROPPED | lupo_sessions (session subsystem) | [livehelp_sessions_migration.md](../database/lupopedia/tables/migrations/livehelp_sessions_migration.md) |
| livehelp_smilies | DROPPED | chat_smilies/ directory + emoji tokens | [livehelp_smilies_migration.md](../database/lupopedia/tables/migrations/livehelp_smilies_migration.md) |
| livehelp_transcripts | IMPORTED -> DROPPED | lupo_dialog_messages, lupo_dialog_threads | [livehelp_transcripts_migration.md](../database/lupopedia/tables/migrations/livehelp_transcripts_migration.md) |
| livehelp_users | IMPORTED -> DROPPED | lupo_auth_users | [livehelp_users_migration.md](../database/lupopedia/tables/migrations/livehelp_users_migration.md) |
| livehelp_visit_track | DROPPED | lupo_visits (analytics subsystem; ephemeral session tracking dropped) | [livehelp_visit_track_migration.md](../database/lupopedia/tables/migrations/livehelp_visit_track_migration.md) |
| livehelp_visits_daily | IMPORTED -> DROPPED | lupo_visits | [livehelp_visit_track_migration.md](../database/lupopedia/tables/migrations/livehelp_visit_track_migration.md) |
| livehelp_visits_monthly | IMPORTED -> DROPPED | lupo_visits | [livehelp_visit_track_migration.md](../database/lupopedia/tables/migrations/livehelp_visit_track_migration.md) |
| livehelp_websites | IMPORTED -> DROPPED | lupo_federation_nodes | [livehelp_websites_migration.md](../database/lupopedia/tables/migrations/livehelp_websites_migration.md) |
| Demo Operators | APPLIED | lupo_auth_users, lupo_actors, lupo_operators, lupo_operator_status | 2026_01_30_demo_operators.md |

This Migration Atlas provides the canonical overview of all Crafty Syntax -> Lupopedia migrations. Each doctrine file contains detailed notes, mappings, and rationale.
