# Minimal Tables for Crafty Syntax + Extended Features

This document lists the **34 legacy Crafty Syntax tables** (source of the migration) and the **additional Lupopedia tables** relevant to knowledgebase, questions, meta, actors, agents, channels, and dialog. The **Full Minimal Set** is the combined, deduplicated list of Lupopedia tables essential for these features.

Source: `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` and `install_new_lupopedia.sql`.

---

## Core Crafty Syntax Tables

The original 34 tables from Crafty Syntax Live Help 3.7.5 that are migrated by `import_from_old_crafty_syntax.sql`. After migration, these legacy tables are dropped; data is written into the Lupopedia target tables listed in the Full Minimal Set.

- **livehelp_autoinvite** — Auto-invite rules: page, visits, referer, department, operator, trigger seconds, mobile flags. → lupo_crafty_syntax_auto_invite
- **livehelp_channels** — Channel/site definitions (deprecated in migration; schema upgrade path).
- **livehelp_config** — Global site config (version, webpath, theme, SMTP, etc.). → JSON in lupo_modules (module_id = 1)
- **livehelp_departments** — Department definitions (recno, name, website). → lupo_departments, lupo_department_metadata
- **livehelp_emailque** — Email queue for lead messages (not migrated in script).
- **livehelp_emails** — Stored emails (from, subject, body, notes). → lupo_crm_lead_messages
- **livehelp_identity_daily** — Daily identity/visitor stats (removed in Lupopedia; no import).
- **livehelp_identity_monthly** — Monthly identity/visitor stats (removed in Lupopedia; no import).
- **livehelp_keywords_daily** — Daily keyword tracking (removed in Lupopedia; no import).
- **livehelp_keywords_monthly** — Monthly keyword tracking (removed in Lupopedia; no import).
- **livehelp_layerinvites** — Layer/popup invite definitions (name, image, department, user). → lupo_crafty_syntax_layer_invites
- **livehelp_leads** — CRM leads (email, phone, name, source, status, data). → lupo_crm_leads
- **livehelp_leavemessage** — Leave-a-message form submissions (email, subject, department, session). → lupo_crafty_syntax_leave_message
- **livehelp_messages** — In-chat messages (Crafty often did not persist after chat end). → conceptually lupo_dialog_messages
- **livehelp_modules** — Module registry (name, path, adminpath). → lupo_modules
- **livehelp_modules_dep** — Module–department associations. → lupo_crafty_syntax_chat_mod_departments
- **livehelp_operator_channels** — Operator–channel assignments. → lupo_channels (channel model)
- **livehelp_operator_departments** — Operator–department membership. → lupo_actor_departments
- **livehelp_operator_history** — Operator action history (login, chat, etc.). → lupo_audit_log
- **livehelp_qa** — QA/knowledge base (questions and answers, parent/child). → lupo_truth_knowledge, lupo_truth_answers, lupo_collections, lupo_collection_tabs
- **livehelp_questions** — Pre-chat form questions (department, header, field type, options). → lupo_crafty_syntax_chat_questions
- **livehelp_quick** — Quick-reply templates per operator (name, message, type). → lupo_actor_reply_templates
- **livehelp_referers_daily** — Daily referer/URL visit stats. → lupo_referers
- **livehelp_referers_monthly** — Monthly referer/URL visit stats. → lupo_referers
- **livehelp_smilies** — Emoji/smiley metadata (replaced by chat_smilies directory; no import).
- **livehelp_sessions** — Visitor sessions (replaced by lupo_sessions; no import).
- **livehelp_visit_track** — Per-session visit tracking. → lupo_visits
- **livehelp_visits_daily** — Daily page visit aggregates. → lupo_analytics_visits_daily
- **livehelp_visits_monthly** — Monthly page visit aggregates. → lupo_analytics_visits_monthly
- **livehelp_paths_firsts** — First-touch path analytics. → lupo_analytics_paths
- **livehelp_paths_monthly** — Monthly path analytics. → lupo_analytics_paths
- **livehelp_transcripts** — Chat transcripts (who, start/end, transcript, session, department). → lupo_dialog_threads, lupo_dialog_messages
- **livehelp_websites** — Website/site definitions. → lupo_federation_nodes
- **livehelp_users** — Operators and users (username, password, department, roles). → lupo_auth_users, lupo_actors, lupo_actor_departments, lupo_department_roles

---

## Additional Tables

Tables from the current Lupopedia schema (200+ tables) that are directly relevant to the listed feature areas. Overlap with import targets is noted where applicable.

### Knowledgebase

- **lupo_collections** — Collections (e.g. site navigation); holds QA/organizational groupings. *(Import target.)*
- **lupo_collection_tabs** — Tabs/entries within collections (e.g. QA hierarchy). *(Import target.)*
- **lupo_collection_tab_map** — Mapping of tabs to content or structure.
- **lupo_collection_tab_paths** — Path/hierarchy for collection tabs.
- **lupo_contents** — Semantic content items (documents, pages).
- **lupo_contexts** — Context definitions for semantic/knowledge use.
- **lupo_contexts_map** — Context-to-entity mappings.
- **lupo_help_topics** — Help/knowledge topics (slug, title, content).
- **lupo_help_tree** — Tree structure for help navigation.
- **lupo_truth_knowledge** — Truth/knowledge base entries (questions, answers, evidence). *(Import target.)*
- **lupo_truth_answers** — Answers linked to truth questions. *(Import target.)*
- **lupo_atoms** — Atomic key-value or semantic atoms.
- **lupo_semantic_index** — Consolidated semantic index (types, slugs, relationships).

### Questions

- **lupo_crafty_syntax_chat_questions** — Pre-chat form questions (department, header, field type, options). *(Import target.)*
- **lupo_truth_knowledge** — QA and question records. *(Import target; overlaps Knowledgebase.)*
- **lupo_truth_answers** — Answers for truth/QA. *(Import target; overlaps Knowledgebase.)*

### Meta

- **lupo_metadata** — Consolidated entity metadata (actor/agent properties, status, etc.); replaces legacy actor_meta-style tables.
- **lupo_system_config** — System-wide config key-value store.
- **lupo_department_metadata** — Department-level metadata. *(Import target.)*
- **lupo_modules** — Module registry and config (Crafty config JSON stored here). *(Import target.)*
- **lupo_audit_log** — Audit trail (operator history, etc.). *(Import target.)*
- **lupo_registry** — Registry of entity types, artifact kinds, etc.
- **lupo_event_metadata** — Event-related metadata.

### Actors

- **lupo_actors** — Unified actor definitions (users, agents, system). *(Import target.)*
- **lupo_actor_departments** — Actor–department membership. *(Import target.)*
- **lupo_actor_channel_roles** — Actor roles per channel (e.g. captain, administrator).
- **lupo_actor_channels** — Actor–channel association and status (e.g. active).
- **lupo_actor_reply_templates** — Quick-reply templates per actor. *(Import target.)*
- **lupo_auth_users** — Human user login and auth metadata. *(Import target.)*
- **lupo_banned_actors** — Banned actor list.
- **lupo_metadata** — Actor status, online_status, status_message, etc. *(Overlaps Meta.)*
- **lupo_department_roles** — Department-level roles (e.g. administrator). *(Import target.)*

### Agents

- **lupo_agents** — Agent definitions and config.
- **lupo_agent_faucets** — Agent “faucet” capabilities (e.g. per-channel).
- **lupo_agent_faucet_credentials** — Credentials for agent faucets.
- **lupo_agent_heartbeats** — Agent heartbeat/availability.
- **lupo_agent_context_snapshots** — Context snapshots for agents.
- **lupo_agent_versions** — Agent version tracking.
- **lupo_agent_tool_calls** — Tool-call log for agents.
- **lupo_agent_dependencies** — Agent dependency definitions.
- **lupo_agent_experiences** — Agent experience/learning data.
- **lupo_agent_external_events** — External events for agents.
- **lupo_agent_files** — Files associated with agents.

### Channels

- **lupo_channels** — Channel definitions (chat channels, integrations). *(Conceptually from livehelp_operator_channels.)*
- **lupo_channel_state** — Current state per channel.
- **lupo_channel_content** — Content attached to channels.
- **lupo_channel_files** — Files per channel.
- **lupo_channel_escalations** — Escalation rules for channels.
- **lupo_channel_escalation_rules** — Rules for channel escalation.
- **lupo_dialog_channels** — Dialog/conversation scope per channel.
- **lupo_federation_nodes** — Sites/websites (Crafty “websites”). *(Import target.)*
- **lupo_federation_categories** — Federation category definitions.
- **lupo_federation_category_map** — Node–category mapping.

### Dialog

- **lupo_dialog_threads** — Conversation threads (e.g. chat sessions). *(Import target.)*
- **lupo_dialog_messages** — Messages within threads. *(Import target.)*
- **lupo_dialog_channels** — Dialog–channel association. *(Overlaps Channels.)*
- **lupo_sessions** — User/visitor sessions (replaces livehelp_sessions conceptually).

---

## Full Minimal Set

Combined, sorted list of all unique Lupopedia tables from (1) import targets for the 34 Crafty tables and (2) additional tables in the categories above. No duplicates.

- lupo_actor_channel_roles
- lupo_actor_channels
- lupo_actor_departments
- lupo_actor_reply_templates
- lupo_actors
- lupo_agent_context_snapshots
- lupo_agent_dependencies
- lupo_agent_experiences
- lupo_agent_external_events
- lupo_agent_faucet_credentials
- lupo_agent_faucets
- lupo_agent_files
- lupo_agent_heartbeats
- lupo_agent_tool_calls
- lupo_agent_versions
- lupo_agents
- lupo_analytics_paths
- lupo_analytics_visits_daily
- lupo_analytics_visits_monthly
- lupo_atoms
- lupo_audit_log
- lupo_auth_users
- lupo_banned_actors
- lupo_channel_content
- lupo_channel_escalation_rules
- lupo_channel_escalations
- lupo_channel_files
- lupo_channel_state
- lupo_channels
- lupo_collection_tab_map
- lupo_collection_tab_paths
- lupo_collection_tabs
- lupo_collections
- lupo_contents
- lupo_contexts
- lupo_contexts_map
- lupo_crafty_syntax_auto_invite
- lupo_crafty_syntax_chat_mod_departments
- lupo_crafty_syntax_chat_questions
- lupo_crafty_syntax_layer_invites
- lupo_crafty_syntax_leave_message
- lupo_crm_lead_messages
- lupo_crm_leads
- lupo_department_metadata
- lupo_department_roles
- lupo_departments
- lupo_dialog_channels
- lupo_dialog_messages
- lupo_dialog_threads
- lupo_event_metadata
- lupo_federation_categories
- lupo_federation_category_map
- lupo_federation_nodes
- lupo_help_topics
- lupo_help_tree
- lupo_metadata
- lupo_modules
- lupo_registry
- lupo_referers
- lupo_semantic_index
- lupo_sessions
- lupo_system_config
- lupo_truth_answers
- lupo_truth_knowledge
- lupo_visits

Total: **66 tables** in the full minimal set (core import targets + additional feature-area tables, deduplicated).
