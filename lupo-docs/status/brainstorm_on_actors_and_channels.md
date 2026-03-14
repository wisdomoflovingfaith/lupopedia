---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/status/brainstorm_on_actors_and_channels.md"
  last_modified_utc: "20260311"
  channel_id: 42
  actor_id: 109
  actor_name: "codex-ide"
  delegation_chain: "codex-ide:root"
  artifact_type: "brainstorm"
  artifact_kind: "architecture"
  purpose: "Exploratory architecture reference; canonical definitions defer to lupo-docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md."
  tags: ["exploratory", "brainstorm", "defer_canonical"]
lupopedia.footer:
  last_verified: "20260311"
  last_verified_by: "cursor"
---
# file: Brainstorm — Actors, Channels, Semantic Edges (exploratory) — session: L-LUPO-ROOT-CURSOR — delegation: codex-ide:root — web_path: http://www.lupopedia.com/status/brainstorm_on_actors_and_channels

# Brainstorm: Actors, Channels, Semantic Edges, and Interfaces (v4.0.69)

**Status: Exploratory.** This document is a brainstorm and expanded reference. **Canonical definitions** for actors, channels, session, traits, roles, federation, and edge vocabulary **defer to** **`lupo-docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md`**. In canonical doctrine: **actors** orchestrate; **faucets** execute; **sessions** carry runtime context. IDE surfaces (Cursor, Antigravity, Kiro, Windsurf, etc.) are **faucets**, not actors. For identity (actor vs faucet), session reconciliation, federation scoping, and edge vocabulary, use that document and the linked doctrine files (e.g. ActorFaucetOntology, Identity Layers, Session Reconciliation, Federation Scoping, Edge Vocabulary).

---

## 1. Scope and Corrections

This revision replaces a partial brainstorm with a TOON-backed architecture pass across all current database tables and table documentation.

### Canonical corrections applied

- The database authority for table structure is `lupo-database/lupopedia/toon/*.toon`.
- The project is installed in a subdirectory under web root (for shared hosting): examples include `/users/<account>/public/lupopedia`.
- `lupopedia-config.php` may be above web root, but Lupopedia runtime/public files are in the project subdirectory.
- Actor and IDE actor IDs must come from `lupo-database/lupopedia/actors/actor_id/registry.json`.
- This document uses current registry semantics: `codex-ide` exists as actor `109`.

## 2. Deployment and Path Model (Shared Hosting First)

Lupopedia is intentionally designed for subdirectory deployment.

- Application path: `.../public/lupopedia`
- Public path constant: `LUPOPEDIA_PUBLIC_PATH`
- Filesystem root constant: `LUPOPEDIA_PATH` / `LUPOPEDIA_ABSPATH`
- Config lookup behavior: `index.php` searches for `lupopedia-config.php` above docroot first, then install dir.

### Practical routing implications

- All generated URLs must prepend `LUPOPEDIA_PUBLIC_PATH`.
- Never assume `/` as app root.
- Slug routing resolves relative to the subdirectory-mounted front controller.

## 3. Actor and Agent Identity Model

**Actors** are the orchestration identities; **faucets** are execution surfaces (IDE surfaces are faucets, not actors). `lupo_actors` is the unified identity substrate. `actor_id` is the universal relationship key.

### Core identity tables

- `lupo_actors`: unified actor entity.
- `lupo_auth_users`: human authentication metadata.
- `lupo_agents`: AI/agent metadata.
- `lupo_agent_faucets`: IDE/runtime faucet definitions.
- `lupo_sessions`: deterministic actor session state.

### Related actor graph and operations

- Membership/roles: `lupo_actor_channels`, `lupo_actor_channel_roles`, `lupo_actor_departments`
- Capabilities and behavior: `lupo_actor_capabilities`, `lupo_actor_actions`, `lupo_actor_moods`, `lupo_capability_usage`
- Runtime and provenance: `lupo_actor_history`, `lupo_actor_handshakes`, `lupo_actor_conflicts`
- Reply and collection affinity: `lupo_actor_reply_templates`, `lupo_actor_collections`

## 4. Channel Model as A2A Coordination Layer

Channels are both conversational context and agent collaboration context.

### Channel control plane

- `lupo_channels`: channel identity, lifecycle, federation scope.
- `lupo_channel_state`: channel mutable state.
- `lupo_channel_files`: channel file attachment/indexing.
- `lupo_channel_escalation_rules` and `lupo_channel_escalations`: governance interventions.
- `lupo_channel_boot_lifecycle` and `lupo_channel_boot_detail_lifecycle`: deterministic startup trace.

### Dialog and work ledgers

- `lupo_dialog_channels`
- `lupo_dialog_threads`
- `lupo_dialog_messages`
- `lupo_tasks`
- `lupo_tickets`, `lupo_ticket_messages`
- `lupo_notifications`

These tables provide the A2A substrate: threads, task handoffs, logs, status transitions, and actor-attributed operations by channel.

## 5. Semantic Graph and Edge Fabric

Lupopedia semantic behavior is not one table; it is a coordinated graph fabric.

### Explicit edge table

- `lupo_edges` captures typed, weighted relationships:
  - object pair: `left_object_type/left_object_id`, `right_object_type/right_object_id`
  - semantics: `edge_type`, `edge_category`, `relationship_type`, `semantic_weight`
  - scope: `channel_id`, `domain_id`, `actor_id`, `context_scope`

### Actor-local edge overlay

- `lupo_actor_edges` (actor-centric relation edges)

### Semantic indexing and meaning layers

- `lupo_semantic_index`
- `lupo_contexts`, `lupo_contexts_map`
- `lupo_truth_knowledge`, `lupo_truth_answers`
- `lupo_interpretation_log`
- `lupo_metadata` (header and attribute indexing layer)
- `lupo_contents`, `lupo_collections`, `lupo_collection_tabs`, `lupo_collection_tab_map`, `lupo_collection_tab_paths`

### High-value logical edge keys (no FK doctrine)

By TOON scan, semantic joins are primarily linked through these IDs:

- `actor_id` appears in 46 TOON tables.
- `channel_id` appears in 26 TOON tables.
- `federation_node_id` appears in 13 TOON tables.
- `department_id` appears in 19 TOON tables.
- `session_id` appears in 5 TOON tables.
- `dialog_thread_id` appears in 2 TOON tables.

This is the effective semantic graph spine for analytics, governance, and collaboration flows.

## 6. Federation and Domain Context

Federation context is anchored by:

- `lupo_federation_nodes`
- `lupo_federation_categories`
- `lupo_federation_category_map`
- `lupo_world_registry`

Channel and content tables (`lupo_channels`, `lupo_channel_content`, `lupo_contents`, `lupo_collections`) reference `federation_node_id` for domain scoping.

## 7. Legacy Crafty Syntax Compatibility Layer

The 34 `livehelp_*` tables remain as legacy source structures and compatibility/migration references.

- Upgrade path doctrine remains Crafty Syntax 3.7.5 -> Lupopedia 4.0.x.
- Runtime modernization uses `lupo_*` tables while keeping legacy mappings explicit.
- Canonical mapping reference: `lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md`.

## 8. Interface Surfaces

### Web interface

- Human/operator usage via routed PHP endpoints.
- Must always honor subdirectory deployment paths.

### CLI interface

- Migration, TOON generation, verification, diagnostics scripts.

### IDE faucet interface

- **IDE agents are faucets, not actors.** Identity belongs to **actors** (e.g. Wolfie) who operate **through** those faucets (Cursor, Kiro, Antigravity). Faucets are tracked in `lupo_agent_faucets`; session and attribution use `actor_id` for the identity using the faucet. See **`lupo-docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md`** and **`lupo-docs/doctrine/ActorFaucetOntology.md`** for canonical definitions.
- Channels are the shared task + dialog + governance context for multi-agent work.

## 9. Session Governance

Canonical session files are under `lupo-database/sessions/`.

Required fields for deterministic continuity:

- `session_id`, `session_name`
- `actor_id`, `actor_name`, `faucet_name`
- `channel_id`, `federation_node_id`, `paired_actor_id`

Session state should map to database truth (`lupo_sessions`) and file-level runtime context when applicable.

## 10. Full TOON Inventory Snapshot (161 Tables)

Source: `lupo-database/lupopedia/toon/` on 2026-03-11.

- `livehelp_autoinvite`
- `livehelp_channels`
- `livehelp_config`
- `livehelp_departments`
- `livehelp_emailque`
- `livehelp_emails`
- `livehelp_identity_daily`
- `livehelp_identity_monthly`
- `livehelp_keywords_daily`
- `livehelp_keywords_monthly`
- `livehelp_layerinvites`
- `livehelp_leads`
- `livehelp_leavemessage`
- `livehelp_messages`
- `livehelp_modules`
- `livehelp_modules_dep`
- `livehelp_operator_channels`
- `livehelp_operator_departments`
- `livehelp_operator_history`
- `livehelp_paths_firsts`
- `livehelp_paths_monthly`
- `livehelp_qa`
- `livehelp_questions`
- `livehelp_quick`
- `livehelp_referers_daily`
- `livehelp_referers_monthly`
- `livehelp_sessions`
- `livehelp_smilies`
- `livehelp_transcripts`
- `livehelp_users`
- `livehelp_visit_track`
- `livehelp_visits_daily`
- `livehelp_visits_monthly`
- `livehelp_websites`
- `lupo_actor_actions`
- `lupo_actor_apps`
- `lupo_actor_capabilities`
- `lupo_actor_channel_roles`
- `lupo_actor_channels`
- `lupo_actor_collections`
- `lupo_actor_conflicts`
- `lupo_actor_departments`
- `lupo_actor_edges`
- `lupo_actor_handshakes`
- `lupo_actor_history`
- `lupo_actor_moods`
- `lupo_actor_reply_templates`
- `lupo_actors`
- `lupo_agent_context_snapshots`
- `lupo_agent_dependencies`
- `lupo_agent_experiences`
- `lupo_agent_external_events`
- `lupo_agent_faucet_credentials`
- `lupo_agent_faucets`
- `lupo_agent_files`
- `lupo_agent_heartbeats`
- `lupo_agent_tool_calls`
- `lupo_agent_versions`
- `lupo_agents`
- `lupo_analytics_campaign_vars`
- `lupo_analytics_paths`
- `lupo_analytics_visits`
- `lupo_analytics_visits_daily`
- `lupo_analytics_visits_monthly`
- `lupo_anubis_events`
- `lupo_anubis_log`
- `lupo_anubis_processing_log`
- `lupo_anubis_quarantine`
- `lupo_anubis_queue`
- `lupo_anubis_recovery_attempts`
- `lupo_anubis_redirects`
- `lupo_api_clients`
- `lupo_api_rate_limits`
- `lupo_api_token_logs`
- `lupo_api_tokens`
- `lupo_api_webhooks`
- `lupo_artifact_chunks`
- `lupo_artifacts`
- `lupo_atoms`
- `lupo_audit_log`
- `lupo_auth_audit_log`
- `lupo_auth_providers`
- `lupo_auth_users`
- `lupo_banned_actors`
- `lupo_bans_log`
- `lupo_calibration_impacts`
- `lupo_capability_usage`
- `lupo_channel_boot_detail`
- `lupo_channel_boot_detail_lifecycle`
- `lupo_channel_boot_lifecycle`
- `lupo_channel_content`
- `lupo_channel_departments`
- `lupo_channel_escalation_rules`
- `lupo_channel_escalations`
- `lupo_channel_files`
- `lupo_channel_state`
- `lupo_channels`
- `lupo_cip_analytics`
- `lupo_cip_propagation_tracking`
- `lupo_cip_trends`
- `lupo_collection_tab_map`
- `lupo_collection_tab_paths`
- `lupo_collection_tabs`
- `lupo_collections`
- `lupo_contents`
- `lupo_contexts`
- `lupo_contexts_map`
- `lupo_crafty_syntax_auto_invite`
- `lupo_crafty_syntax_chat_mod_departments`
- `lupo_crafty_syntax_chat_questions`
- `lupo_crafty_syntax_layer_invites`
- `lupo_crafty_syntax_leave_message`
- `lupo_crafty_user_mapping`
- `lupo_crm_lead_messages`
- `lupo_crm_leads`
- `lupo_department_metadata`
- `lupo_department_roles`
- `lupo_departments`
- `lupo_dialog_channels`
- `lupo_dialog_messages`
- `lupo_dialog_threads`
- `lupo_doctrine_evolution_audit`
- `lupo_edges`
- `lupo_emotional_frameworks`
- `lupo_emotional_geometry_calibrations`
- `lupo_event_metadata`
- `lupo_federation_categories`
- `lupo_federation_category_map`
- `lupo_federation_nodes`
- `lupo_governance_overrides`
- `lupo_help_topics`
- `lupo_help_tree`
- `lupo_interpretation_log`
- `lupo_labs_declarations`
- `lupo_labs_violations`
- `lupo_memory_rollups`
- `lupo_metadata`
- `lupo_modules`
- `lupo_multi_agent_critique_sync`
- `lupo_notifications`
- `lupo_permissions`
- `lupo_referers`
- `lupo_registry`
- `lupo_registry_open`
- `lupo_rule_logs`
- `lupo_rule_targets`
- `lupo_rules`
- `lupo_schema_migrations`
- `lupo_search_rebuild_log`
- `lupo_semantic_index`
- `lupo_sessions`
- `lupo_system_commands`
- `lupo_system_config`
- `lupo_tasks`
- `lupo_ticket_messages`
- `lupo_tickets`
- `lupo_truth_answers`
- `lupo_truth_knowledge`
- `lupo_uploads`
- `lupo_visits`
- `lupo_world_registry`

## 11. Domain Summary by TOON Prefix

- Legacy Crafty (`livehelp_*`): 34 tables
- Lupopedia runtime (`lupo_*`): 127 tables
- Total TOON tables: 161

## 12. Recommended Next Hardening Passes

1. Add generated relationship manifests from TOON `*_id` columns into `relationships` blocks where currently empty.
2. Add a canonical semantic edge dictionary (`edge_type`, `relationship_type`, allowed object pairs).
3. Add per-domain table indexes in lupo-docs/status for actor/channel/semantic/federation/governance slices.
4. Validate all docs that still reference old `lupo-docs/database/...` paths against `lupo-docs/database/...` and current TOON locations.

---

*Brainstorm revised by: Codex IDE (109)*

*Status: Expanded TOON-backed architecture document*
