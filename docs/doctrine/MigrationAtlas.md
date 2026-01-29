# Migration Atlas

| Legacy Table | Status | Replacement Table(s) | Doctrine File |
|--------------|--------|----------------------|----------------|
| livehelp_autoinvite | IMPORTED -> DROPPED | lupo_crafty_syntax_auto_invite | [livehelp_autoinvite_migration.md](migrations/livehelp_autoinvite_migration.md) |
| livehelp_channels | DROPPED | lupo_channels, lupo_dialog_threads (routing subsystem + dialog system) | [livehelp_channels_migration.md](migrations/livehelp_channels_migration.md) |
| livehelp_config | PARTIALLY IMPORTED -> DROPPED | lupo_modules.config_json (module_id = 1) | [livehelp_config_migration.md](migrations/livehelp_config_migration.md) |
| livehelp_departments | IMPORTED -> SPLIT -> DROPPED | lupo_departments, lupo_department_metadata | [livehelp_departments_migration.md](migrations/livehelp_departments_migration.md) |
| livehelp_emailque | DROPPED | None (mail subsystem; delivery logging in CRM if needed) | [livehelp_emailque_migration.md](migrations/livehelp_emailque_migration.md) |
| livehelp_emails | IMPORTED -> DROPPED | lupo_crm_lead_messages | [livehelp_emails_migration.md](migrations/livehelp_emails_migration.md) |
| livehelp_identity_daily | DROPPED | lupo_actors (anonymous) + identity helper subsystem (session identity resolution) | [livehelp_identity_migration.md](migrations/livehelp_identity_migration.md) |
| livehelp_identity_monthly | PARTIALLY IMPORTED -> DROPPED | lupo_actors (anonymous) + identity helper subsystem (session identity resolution) | [livehelp_identity_migration.md](migrations/livehelp_identity_migration.md) |
| livehelp_keywords_daily | DROPPED | lupo_analytics_campaign_vars | [livehelp_keywords_migration.md](migrations/livehelp_keywords_migration.md) |
| livehelp_keywords_monthly | DROPPED | lupo_analytics_campaign_vars | [livehelp_keywords_migration.md](migrations/livehelp_keywords_migration.md) |
| livehelp_layerinvites | IMPORTED -> DROPPED | lupo_crafty_syntax_layer_invites | [livehelp_layerinvites_migration.md](migrations/livehelp_layerinvites_migration.md) |
| livehelp_leads | IMPORTED -> DROPPED | lupo_crm_leads | [livehelp_leads_migration.md](migrations/livehelp_leads_migration.md) |
| livehelp_leavemessage | IMPORTED -> DROPPED | lupo_crafty_syntax_leave_message | [livehelp_leavemessage_migration.md](migrations/livehelp_leavemessage_migration.md) |
| livehelp_messages | DROPPED | None (dialog system; durable transcripts from livehelp_transcripts) | [livehelp_messages_migration.md](migrations/livehelp_messages_migration.md) |
| livehelp_modules | DROPPED | lupo_modules | [livehelp_modules_migration.md](migrations/livehelp_modules_migration.md) |
| livehelp_modules_dep | DROPPED | lupo_modules_departments | [livehelp_modules_dep_migration.md](migrations/livehelp_modules_dep_migration.md) |
| livehelp_operator_channels | DROPPED | lupo_channels, lupo_dialog_threads (routing subsystem + dialog system) | [livehelp_operator_channels_migration.md](migrations/livehelp_operator_channels_migration.md) |
| livehelp_operator_departments | IMPORTED -> DROPPED | lupo_actor_departments | [livehelp_operator_departments_migration.md](migrations/livehelp_operator_departments_migration.md) |
| livehelp_operator_history | IMPORTED -> DROPPED | lupo_audit_log | [livehelp_operator_history_migration.md](migrations/livehelp_operator_history_migration.md) |
| livehelp_paths_firsts | IMPORTED -> DROPPED | lupo_unified_analytics_paths | [livehelp_paths_firsts_migration.md](migrations/livehelp_paths_firsts_migration.md) |
| livehelp_paths_monthly | IMPORTED -> DROPPED | lupo_unified_analytics_paths | [livehelp_paths_firsts_migration.md](migrations/livehelp_paths_firsts_migration.md) |
| livehelp_qa | IMPORTED -> DROPPED | lupo_collection_tabs, lupo_collections, lupo_truth_answers, lupo_truth_questions | [livehelp_qa_migration.md](migrations/livehelp_qa_migration.md) |
| livehelp_questions | IMPORTED -> DROPPED | lupo_crafty_syntax_chat_questions | [livehelp_questions_migration.md](migrations/livehelp_questions_migration.md) |
| livehelp_quick | IMPORTED -> DROPPED | lupo_actor_reply_templates | [livehelp_quick_migration.md](migrations/livehelp_quick_migration.md) |
| livehelp_referers_daily | IMPORTED -> DROPPED | lupo_unified_referers (analytics subsystem) | [livehelp_referers_daily_migration.md](migrations/livehelp_referers_daily_migration.md) |
| livehelp_referers_monthly | IMPORTED -> DROPPED | lupo_unified_referers (analytics subsystem) | [livehelp_referers_daily_migration.md](migrations/livehelp_referers_daily_migration.md) |
| livehelp_sessions | DROPPED | lupo_sessions (session subsystem) | [livehelp_sessions_migration.md](migrations/livehelp_sessions_migration.md) |
| livehelp_smilies | DROPPED | chat_smilies/ directory + emoji tokens | [livehelp_smilies_migration.md](migrations/livehelp_smilies_migration.md) |
| livehelp_transcripts | IMPORTED -> DROPPED | lupo_dialog_messages, lupo_dialog_threads | [livehelp_transcripts_migration.md](migrations/livehelp_transcripts_migration.md) |
| livehelp_users | IMPORTED -> DROPPED | lupo_auth_users | [livehelp_users_migration.md](migrations/livehelp_users_migration.md) |
| livehelp_visit_track | DROPPED | lupo_unified_visits (analytics subsystem; ephemeral session tracking dropped) | [livehelp_visit_track_migration.md](migrations/livehelp_visit_track_migration.md) |
| livehelp_visits_daily | IMPORTED -> DROPPED | lupo_unified_visits | [livehelp_visit_track_migration.md](migrations/livehelp_visit_track_migration.md) |
| livehelp_visits_monthly | IMPORTED -> DROPPED | lupo_unified_visits | [livehelp_visit_track_migration.md](migrations/livehelp_visit_track_migration.md) |
| livehelp_websites | IMPORTED -> DROPPED | lupo_federation_nodes | [livehelp_websites_migration.md](migrations/livehelp_websites_migration.md) |

This Migration Atlas provides the canonical overview of all Crafty Syntax -> Lupopedia migrations. Each doctrine file contains detailed notes, mappings, and rationale.
