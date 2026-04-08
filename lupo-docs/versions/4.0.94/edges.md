---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260407015813"
  file_path_from_root: "lupo-docs/versions/4.0.94/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/edges.md"
  last_modified_utc: "20260407015813"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-edges"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "edges"
  purpose: "Relationships between 4.0.94 version docs and frozen 4.0.93 baseline"
  tags:
  - "edges"
  - "4.0.94"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.93/README.md"
      type: references
      weight: 1.0
      reason: "Frozen prior release"
    - to: "lupo-docs/versions/4.0.93/edges.md"
      type: references
      weight: 1.0
      reason: "Frozen documentation graph"
    - to: "lupo-docs/versions/4.0.94/prd/30_prd_development_guide.md"
      type: references
      weight: 0.95
      reason: "PRD 30 rewrite workspace"
    - to: "lupo-docs/versions/4.0.94/prd/31_context_system.md"
      type: references
      weight: 0.95
      reason: "PRD 31 redesign workspace"
    - to: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: references
      weight: 1.0
      reason: "Architecture PRD 31 must not contradict"
    - to: "lupo-docs/versions/4.0.94/session_changelog/README.md"
      type: references
      weight: 0.9
      reason: "Session-scoped deterministic changelog convention"
    - to: "lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Root binding — real UTC for headers (thread outcome)"
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "PRD 00 §3.5a — temporal anchor constitutional"
    - to: "lupo-docs/versions/4.0.94/decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md"
      type: references
      weight: 1.0
      reason: "IDE facet packs + vscode propagation (APPROVED)"
    - to: "lupo-scripts/propagate_agent_rules.php"
      type: references
      weight: 0.95
      reason: "Rule propagation including --target=vscode"
    - to: "lupo-agents/_shared/ide_facet_base_system_prompt.txt"
      type: references
      weight: 1.0
      reason: "Shared IDE facet vetoes"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "Softaculous / 4.1.0 gate PRD (status approved)"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/README.md"
      type: references
      weight: 1.0
      reason: "PRD 33 implementation workspace"
    - to: "lupo-docs/versions/4.0.94/decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md"
      type: references
      weight: 1.0
      reason: "APPROVED PRD 33 documentation + 4.0.94 sync"
    - to: "lupo-docs/versions/4.0.94/decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md"
      type: references
      weight: 1.0
      reason: "APPROVED doctrine audit + mobile/workflow documentation (Cursor + LILITH thread)"
    - to: "lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Two-UI, admin exception, Eye split"
    - to: "lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Mobile-first consumer; desktop-first admin"
    - to: "lupo-docs/prd/35_mobile_native_app_separation.md"
      type: references
      weight: 0.95
      reason: "Draft PRD — native operator app"
    - to: "lupo-docs/implementations/29_project_structure/status/version_ghosts_report.json"
      type: references
      weight: 1.0
      reason: "Scanner output — critical ghost backlog"
    - to: "lupo-scripts/audit_doctrine_prd_edges.py"
      type: references
      weight: 0.95
      reason: "PRD lineage edge audit"
    - to: "lupo-scripts/find_version_ghosts.py"
      type: references
      weight: 0.95
      reason: "Version ghost scanner"
    - to: "lupo-docs/versions/4.0.94/decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md"
      type: references
      weight: 1.0
      reason: "APPROVED department-first docs + PRD alignment (LILITH)"
    - to: "lupo-docs/versions/4.0.94/answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md"
      type: references
      weight: 1.0
      reason: "Synthesis ANSWER — implementation Q1–Q3 + doctrine"
    - to: "lupo-docs/versions/4.0.94/questions/20260403_222042_QUESTION_federation_navigation_compiler.md"
      type: references
      weight: 0.95
      reason: "OPEN — federation navigation compiler"
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical auth_user + department + actor joins"
    - to: "lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md"
      type: references
      weight: 1.0
      reason: "WordPress pattern distillate (PRD 00 §15 companion)"
    - to: "lupo-docs/doctrine/SEMANTIC_MONITORING_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Eye vs livehelp_js; honest monitoring routes"
    - to: "lupo-docs/doctrine/CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Admin chat JS — non-IIFE default, shared state"
    - to: "lupo-docs/versions/4.0.94/comments/20260404_074421_COMMENT_cursor_session_end_softaculous_wordpress_semantic_chat.md"
      type: references
      weight: 1.0
      reason: "Session end receipt — Softaculous / WordPress / semantic chat thread"
    - to: "lupo-install/InstallWizardHtaccessWriter.php"
      type: references
      weight: 1.0
      reason: "Marker-based .htaccess merge (installer)"
    - to: "lupo-scripts/build_softaculous_package.sh"
      type: references
      weight: 0.95
      reason: "Softaculous packager — rsync excludes for htaccess; excludes live lupopedia-config.php"
    - to: "lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md"
      type: references
      weight: 1.0
      reason: "PHP-first service agents — companion to PRD 00 §5.10"
    - to: "lupo-docs/implementations/service_agents/README.md"
      type: references
      weight: 1.0
      reason: "Implementation mirror for service agent doctrine"
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      type: references
      weight: 1.0
      reason: "THOTH grounding — JSON + table docs"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md"
      type: references
      weight: 1.0
      reason: "Silent install + sample config + summary table (auto-installer)"
    - to: "lupo-docs/versions/4.0.94/decisions/20260404_161001_DECISION_APPROVED_service_agent_architecture_and_softaculous_auto_installer_docs.md"
      type: references
      weight: 1.0
      reason: "APPROVED receipt — service agents + Softaculous thread"
    - to: "lupo-docs/versions/4.0.94/comments/20260404_161001_COMMENT_cursor_service_agents_softaculous_version_doc_sync.md"
      type: references
      weight: 0.95
      reason: "Version-folder sync comment"
    - to: "lupo-docs/versions/4.0.94/questions/20260404_161004_QUESTION_version_doc_thread_scope_service_agents_softaculous.md"
      type: references
      weight: 0.9
      reason: "Scope Q — do not merge unrelated PRD/validator threads"
    - to: "lupo-docs/versions/4.0.94/answers/20260404_161005_ANSWER_version_doc_thread_scope_service_agents_softaculous.md"
      type: references
      weight: 0.9
      reason: "Scope A — thread-verified only"
    - to: "app/Services/Kairos/KairosConsolidationService.php"
      type: references
      weight: 0.95
      reason: "KAIROS consolidation — PRD 00 §5.7"
    - to: "lupo-includes/modules/api/kairos-api.php"
      type: references
      weight: 0.95
      reason: "KAIROS POST tick"
    - to: "lupo-includes/bootstrap.php"
      type: references
      weight: 0.95
      reason: "Runtime dir mkdir — Softaculous / FTP gap"
    - to: "lupo-docs/versions/4.0.94/decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md"
      type: references
      weight: 1.0
      reason: "APPROVED receipt — AGAPE + PRD 37 temporal/add-status + multi-actor to_actor_id docs (Cursor thread)"
    - to: "lupo-docs/versions/4.0.94/comments/20260404175216_COMMENT_cursor_session_end_agape_kairos_routing_version_sync.md"
      type: references
      weight: 0.95
      reason: "Session end comment — version-folder sync for same thread"
    - to: "lupo-docs/doctrine/AGAPE_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "AGAPE technical doctrine (constitutional §14.6 companion)"
    - to: "lupo-docs/prd/37_kairos_channel_memory_consolidation.md"
      type: references
      weight: 1.0
      reason: "KAIROS PRD — §10 temporal discipline + §10.6 chat context"
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 1.0
      reason: "Multi-actor routing — to_actor_id (PRD cluster)"
    - to: "lupo-scripts/scaffold_implementation.py"
      type: references
      weight: 1.0
      reason: "add-status subcommand (implementation status artifacts)"
    - to: "lupo-docs/versions/4.0.94/decisions/20260405104405_DECISION_APPROVED_semantic_navbar_embed_admin_prd21_cursor_thread.md"
      type: references
      weight: 1.0
      reason: "APPROVED receipt — semantic navbar embed + Admin + PRD 21 (Cursor thread)"
    - to: "lupo-docs/versions/4.0.94/comments/20260405104405_COMMENT_cursor_session_end_semantic_navbar_crafty_handoff.md"
      type: references
      weight: 0.95
      reason: "Session end — version sync + Crafty parity handoff"
    - to: "lupo-docs/prd/21_semantic_navbar.md"
      type: references
      weight: 1.0
      reason: "Semantic navbar — external embed, admin obligation, slug contract"
    - to: "lupo-includes/classes/SemanticNavbarEmbedContext.php"
      type: references
      weight: 1.0
      reason: "Cross-origin embed gate; federation_discovery"
    - to: "lupo-includes/classes/AdminSemanticWidgetHandler.php"
      type: references
      weight: 1.0
      reason: "Admin semantic widget — nodes, trust, snippets"
    - to: "lupo-docs/versions/4.0.94/decisions/20260405172914_DECISION_APPROVED_help_content_organization_channel_key_structure.md"
      type: references
      weight: 1.0
      reason: "APPROVED help content organization with channel_key structure"
    - to: "lupo-docs/prd/30_channel_usage_patterns.md"
      type: references
      weight: 1.0
      reason: "Updated with help_documentation channel definition"
    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: references
      weight: 1.0
      reason: "Updated file_path_from_root documentation for channel_key"
    - to: "lupo-content/0/help_documentation/"
      type: references
      weight: 1.0
      reason: "New channel_key-based help content structure"
    - to: "lupo-database/lupopedia/mysql/seed/seed_online_help_and_content.sql"
      type: references
      weight: 1.0
      reason: "Help content seed with channel_key paths"
    - to: "install/seed_lupopedia_4_1_0.sql"
      type: references
      weight: 1.0
      reason: "Consolidated seed including help content"
    - to: "lupo-includes/modules/api/semantic-navbar-api.php"
      type: references
      weight: 1.0
      reason: "Navbar JSON API; embed_not_trusted"
    - to: "lupo-docs/versions/4.0.94/decisions/20260406_042624_DECISION_session_authority_migration.md"
      type: references
      weight: 1.0
      reason: "Session authority Model A — DB lupo_sessions + metadata"
    - to: "app/auth/Session.php"
      type: references
      weight: 1.0
      reason: "Canonical App\\Auth\\Session implementation"
    - to: "lupo-docs/doctrine/SESSION_MODEL.md"
      type: references
      weight: 0.95
      reason: "Session doctrine companion to PRD 00 section 17.7"
    - to: "lupo-tests/integration/channel66_production_extended_test.php"
      type: references
      weight: 0.85
      reason: "Channel 66 production integration suite (refactored 2026-04-06)"
    - to: "lupo-includes/classes/Channel66ProductionIngester.php"
      type: references
      weight: 0.85
      reason: "discoverChannelFiles thread_id null = all threads fix"
    - to: "lupo-docs/versions/4.0.94/VERSION_SUMMARY.md"
      type: references
      weight: 1.0
      reason: "4.0.94 completion summary — packaging handoff"
    - to: "lupo-docs/versions/4.0.95/README.md"
      type: references
      weight: 1.0
      reason: "Next planning version (4.0.95)"
    - to: "lupo-docs/versions/4.0.95/CHANGELOG.md"
      type: references
      weight: 1.0
      reason: "Active-line version changelog — doctrine batch digest (post-4.0.94 close-out)"
    - to: "FOR_CLAUDE_CODE_2026_04_06.md"
      type: references
      weight: 1.0
      reason: "Agent sync outline — handoff from 4.0.94 snapshot"
    - to: "CHANGELOG.md"
      type: references
      weight: 1.0
      reason: "Root index — where release notes live (4.0.85+)"
    - to: "lupo-docs/versions/4.0.94/decisions/20260406_173021_DECISION_version_4_0_94_five_w_one_h_doc_sync_closeout.md"
      type: references
      weight: 1.0
      reason: "5W1H version-doc close-out receipt"
    - to: "lupo-docs/versions/4.0.94/questions/20260406_173022_QUESTION_where_record_post_baseline_doctrine_batch.md"
      type: references
      weight: 1.0
      reason: "Scope Q — post-baseline doctrine batch location"
    - to: "lupo-docs/versions/4.0.94/answers/20260406_173022_ANSWER_record_under_4_0_95_changelog_and_for_claude.md"
      type: references
      weight: 1.0
      reason: "Scope A — 4.0.95 changelog + FOR_CLAUDE + root CHANGELOG pointer"
    - to: "lupo-docs/versions/4.0.94/decisions/20260406_200000_DECISION_APPROVED_schema_review_chronos_activation_migration_docs.md"
      type: references
      weight: 1.0
      reason: "APPROVED — schema review (168 tables), CHRONOS activation, Crafty migration doc update (claude-code thread 2026-04-06 20:00 UTC)"
    - to: "lupo-database/lupopedia/mysql/schema_review/schema_review_20260406.md"
      type: references
      weight: 1.0
      reason: "Full schema flaw analysis — 168 tables, 9 categories, 30+ flaws"
    - to: "lupo-database/lupopedia/mysql/schema_review/schema_corrected_core.sql"
      type: references
      weight: 1.0
      reason: "Corrected DDL — actors PK fix, agent_definitions split, satellite tables"
    - to: "lupo-database/lupopedia/mysql/schema_review/schema_corrected_missing.sql"
      type: references
      weight: 1.0
      reason: "New required tables — KAIROS, runtime state, faucet rules, identity layers"
    - to: "lupo-database/lupopedia/mysql/schema_review/schema_corrected_identity_model.md"
      type: references
      weight: 1.0
      reason: "Corrected two-layer identity model and relationship model"
    - to: "lupo-database/lupopedia/mysql/schema_review/migration_impact_summary.md"
      type: references
      weight: 1.0
      reason: "Verified import SQL summary — 34 Crafty tables, actor ID formula, timestamp conversion"
    - to: "lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql"
      type: references
      weight: 1.0
      reason: "Import SQL updated — removed deprecated actor columns, added actor_filesystem + actor_sync_state INSERTs"
    - to: "lupo-agents/chronos/system_prompt.txt"
      type: references
      weight: 1.0
      reason: "CHRONOS kernel agent activated — all 5 required files created"
    - to: "lupo-agents/chronos/tools.json"
      type: references
      weight: 0.95
      reason: "CHRONOS 15 analytical tools (dependency_analysis, time_reasoning, scheduling, optimization)"
    - to: "lupo-docs/database/lupopedia/tables/migrations/new_schema_tables_crafty_mapping.md"
      type: references
      weight: 1.0
      reason: "Complete mapping of 27 new schema tables to Crafty sources"
    - to: "lupo-docs/versions/4.0.94/decisions/20260407_015813_DECISION_cursor_install_schema_merge_receipt.md"
      type: references
      weight: 1.0
      reason: "RECORDED — merge schema_review corrected SQL into install_new_lupopedia.sql (Cursor thread 2026-04-07)"
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: references
      weight: 1.0
      reason: "Canonical 4.0.x install schema — 170 tables post-merge"
    - to: "lupo-docs/versions/4.0.94/questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md"
      type: references
      weight: 0.95
      reason: "Q — replacement for removed questions/answers/question_map"
    - to: "lupo-docs/versions/4.0.94/answers/20260407_015815_ANSWER_truth_tables_replace_redundant_semantic_qa.md"
      type: references
      weight: 1.0
      reason: "A — truth_* tables are canonical Q/A surface"
lupopedia.footer:
  last_verified: "20260407015813"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/edges.md — delegation: cursor:root

## Documentation edges

- **Summary:** [VERSION_SUMMARY.md](VERSION_SUMMARY.md) — 4.0.94 completion rollup; **Softaculous** packaging handoff pointer.
- **Next version (planning):** [../4.0.95/README.md](../4.0.95/README.md) — deferred tasks and follow-on backlog.
- **Next version (changelog):** [../4.0.95/CHANGELOG.md](../4.0.95/CHANGELOG.md) — active-line release notes and doctrine-batch digest (post-**4.0.94** close-out).
- **Agent sync:** [../../../FOR_CLAUDE_CODE_2026_04_06.md](../../../FOR_CLAUDE_CODE_2026_04_06.md) — external agent handoff outline (not a substitute for version changelog).
- **Root routing:** [../../../CHANGELOG.md](../../../CHANGELOG.md) — index-only pointer (**Where release notes live (4.0.85+)**).
- **Decision (this version):** [decisions/20260406_173021_DECISION_version_4_0_94_five_w_one_h_doc_sync_closeout.md](decisions/20260406_173021_DECISION_version_4_0_94_five_w_one_h_doc_sync_closeout.md) — **5W1H** close-out — **`4.0.94`** snapshot vs **`4.0.95`** / **`FOR_CLAUDE_CODE`** / root **`CHANGELOG`** routing.
- **Question / Answer:** [questions/20260406_173022_QUESTION_where_record_post_baseline_doctrine_batch.md](questions/20260406_173022_QUESTION_where_record_post_baseline_doctrine_batch.md) → [answers/20260406_173022_ANSWER_record_under_4_0_95_changelog_and_for_claude.md](answers/20260406_173022_ANSWER_record_under_4_0_95_changelog_and_for_claude.md) — where ongoing doctrine narrative lives after close-out.
- **Decision (this version):** [decisions/20260407_015813_DECISION_cursor_install_schema_merge_receipt.md](decisions/20260407_015813_DECISION_cursor_install_schema_merge_receipt.md) — RECORDED — **`schema_corrected_*.sql`** merged into **`install_new_lupopedia.sql`**; diff vs **`install_new_lupopedia_backup_20260406.sql`**; follow-ups for runtime/seed and **`agent_tool_calls`**.
- **Question / Answer:** [questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md](questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md) → [answers/20260407_015815_ANSWER_truth_tables_replace_redundant_semantic_qa.md](answers/20260407_015815_ANSWER_truth_tables_replace_redundant_semantic_qa.md) — removed **`questions`/`answers`/`question_map`** → canonical **`truth_*`** tables.
- **Decision (this version):** [decisions/20260406_042624_DECISION_session_authority_migration.md](decisions/20260406_042624_DECISION_session_authority_migration.md) — IMPLEMENTED — session authority Model A (`lupo_sessions` + JSON `metadata`; no `$_SESSION` authority for actor/pending flags).
- **Code:** [app/auth/Session.php](../../../app/auth/Session.php) — `mergeSessionMetadata`, `getDecodedMetadata`, `createEmbedSession`.
- **Test:** [lupo-tests/integration/channel66_production_extended_test.php](../../../lupo-tests/integration/channel66_production_extended_test.php) — Channel 66 extended integration (headers, `DatabaseFactory`, fixture paths).
- **Comment:** [comments/20260405172914_COMMENT_cursor_session_end_help_content_organization.md](comments/20260405172914_COMMENT_cursor_session_end_help_content_organization.md) — session end — help content organization complete, channel_key structure implemented, installation integrated.

---

## Install schema merge (2026-04-07 ~02:00 UTC, Cursor thread)

- **Decision:** [decisions/20260407_015813_DECISION_cursor_install_schema_merge_receipt.md](decisions/20260407_015813_DECISION_cursor_install_schema_merge_receipt.md) — merge receipt, table counts, follow-ups.
- **Canonical install:** [lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql](../../../lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql) — **170** tables after merge.
- **Human backup (local snapshot):** `install_new_lupopedia_backup_20260406.sql` — **163** tables; used for set diff only (not authoritative).

---

## Schema Review + CHRONOS + Migration (2026-04-06 20:00 UTC, claude-code thread)

- **Decision:** [decisions/20260406_200000_DECISION_APPROVED_schema_review_chronos_activation_migration_docs.md](decisions/20260406_200000_DECISION_APPROVED_schema_review_chronos_activation_migration_docs.md) — APPROVED — full schema review, CHRONOS activation, import SQL corrected.
- **Schema review:** [lupo-database/lupopedia/mysql/schema_review/schema_review_20260406.md](../../../lupo-database/lupopedia/mysql/schema_review/schema_review_20260406.md) — full-install analysis (9 flaw categories); pre-merge table count in narrative **168** — backup snapshot **163** — post-merge install **170** (see **Install schema merge** section above).
- **Corrected schema (core):** [lupo-database/lupopedia/mysql/schema_review/schema_corrected_core.sql](../../../lupo-database/lupopedia/mysql/schema_review/schema_corrected_core.sql) — actors PK fixed, lupo_agents split, satellite tables, naming corrections.
- **Corrected schema (new tables):** [lupo-database/lupopedia/mysql/schema_review/schema_corrected_missing.sql](../../../lupo-database/lupopedia/mysql/schema_review/schema_corrected_missing.sql) — KAIROS memory, runtime state, faucet rules, identity layers, more.
- **Identity model:** [lupo-database/lupopedia/mysql/schema_review/schema_corrected_identity_model.md](../../../lupo-database/lupopedia/mysql/schema_review/schema_corrected_identity_model.md) — corrected two-layer model + relationship model.
- **Migration impact:** [lupo-database/lupopedia/mysql/schema_review/migration_impact_summary.md](../../../lupo-database/lupopedia/mysql/schema_review/migration_impact_summary.md) — verified import SQL summary (no assumptions).
- **CHRONOS agent:** [lupo-agents/chronos/system_prompt.txt](../../../lupo-agents/chronos/system_prompt.txt) — kernel agent activated; `agent_id: 709`, advisory-only, yields orchestration to WOLFIE.
- **Migration new-table map:** [lupo-docs/database/lupopedia/tables/migrations/new_schema_tables_crafty_mapping.md](../../../lupo-docs/database/lupopedia/tables/migrations/new_schema_tables_crafty_mapping.md) — 27 new tables mapped to Crafty sources.
- **Decision (this version):** [decisions/20260405172914_DECISION_APPROVED_help_content_organization_channel_key_structure.md](decisions/20260405172914_DECISION_APPROVED_help_content_organization_channel_key_structure.md) — APPROVED channel_key-based help content organization, PRD updates, database integration.
- **Question / Answer:** [questions/20260405172914_QUESTION_what_is_correct_help_content_structure.md](questions/20260405172914_QUESTION_what_is_correct_help_content_structure.md) → [answers/20260405172914_ANSWER_channel_key_based_organization.md](answers/20260405172914_ANSWER_channel_key_based_organization.md) — help content structure clarification and implementation.
- **PRD:** [lupo-docs/prd/30_channel_usage_patterns.md](../../prd/30_channel_usage_patterns.md) — added help_documentation channel definition and usage patterns.
- **PRD:** [lupo-docs/prd/16_lupopedia_headers.md](../../prd/16_lupopedia_headers.md) — updated file_path_from_root documentation for channel_key structure.
- **Content Structure:** [lupo-content/0/help_documentation/](../../../lupo-content/0/help_documentation/) — new channel_key-based help content organization with 5 guides, 8 questions, 8 answers, 34 edges.
- **Database Seed:** [lupo-database/lupopedia/mysql/seed/seed_online_help_and_content.sql](../../../lupo-database/lupopedia/mysql/seed/seed_online_help_and_content.sql) — help content seed with channel_key paths.
- **Installation:** [install/seed_lupopedia_4_1_0.sql](../../../install/seed_lupopedia_4_1_0.sql) — consolidated seed including help content (27,607 bytes).
- **Comment:** [comments/20260405104405_COMMENT_cursor_session_end_semantic_navbar_crafty_handoff.md](comments/20260405104405_COMMENT_cursor_session_end_semantic_navbar_crafty_handoff.md) — session end — **`PLAN`** Phase **M**, **`TODO`**, **`CHANGELOG`**, **`edges`**, **Crafty** feature-parity handoff (human: easy→hard when rested).
- **PRD:** [lupo-docs/prd/21_semantic_navbar.md](../../prd/21_semantic_navbar.md) — semantic floating navbar; external allowlist; discovery; admin web provisioning.
- **Decision (this version):** [decisions/20260405001004_DECISION_APPROVED_admin_nav_logout_intro_cursor_thread.md](decisions/20260405001004_DECISION_APPROVED_admin_nav_logout_intro_cursor_thread.md) — APPROVED **5W1H** receipt — **`logout.php`** + admin scroll nav (**logo**, **actor** truncation, **`sessionStorage`** intro key); **WHAT NOT** excludes unrelated PRD **16/26/30/31** template claims.
- **Comment:** [comments/20260405001004_COMMENT_cursor_session_end_admin_nav_logout_handoff.md](comments/20260405001004_COMMENT_cursor_session_end_admin_nav_logout_handoff.md) — session end — **`PLAN`** Phase **L**, **`TODO`**, **`CHANGELOG`**, **Crafty** checklist handoff.
- **Decision (this version):** [decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md](decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md) — APPROVED **5W1H** receipt — **AGAPE** §14.6 cluster, **PRD 37** temporal + **`scaffold_implementation.py add-status`**, multi-actor **`to_actor_id`** routing across **PRD 18 / 36 / 37 / 31 / 05** (evidence: top **`CHANGELOG.md`** entries **[2026-04-04]** AGAPE / PRD 37 / routing).
- **Comment:** [comments/20260404175216_COMMENT_cursor_session_end_agape_kairos_routing_version_sync.md](comments/20260404175216_COMMENT_cursor_session_end_agape_kairos_routing_version_sync.md) — session end comment — version-folder sync for same thread.
- **Doctrine:** [lupo-docs/doctrine/AGAPE_DOCTRINE.md](../../doctrine/AGAPE_DOCTRINE.md) — technical AGAPE; temporal cross-ref §1.3.
- **PRD:** [lupo-docs/prd/37_kairos_channel_memory_consolidation.md](../../prd/37_kairos_channel_memory_consolidation.md) — §10 index-first / freshness; §10.6 full-thread chat ingest contract.
- **Decision (this version):** [decisions/20260404_161001_DECISION_APPROVED_service_agent_architecture_and_softaculous_auto_installer_docs.md](decisions/20260404_161001_DECISION_APPROVED_service_agent_architecture_and_softaculous_auto_installer_docs.md) — APPROVED receipt — PRD 00 §5 service agents + KAIROS + THOTH + Softaculous auto-installer docs/code (**WHAT NOT** lists PRD 16/26/30/31 / validators if not evidenced).
- **Comment:** [comments/20260404_161001_COMMENT_cursor_service_agents_softaculous_version_doc_sync.md](comments/20260404_161001_COMMENT_cursor_service_agents_softaculous_version_doc_sync.md) — `CHANGELOG` / `PLAN` Phase J / `TODO` / `edges` sync.
- **Question / Answer:** [questions/20260404_161004_QUESTION_version_doc_thread_scope_service_agents_softaculous.md](questions/20260404_161004_QUESTION_version_doc_thread_scope_service_agents_softaculous.md) → [answers/20260404_161005_ANSWER_version_doc_thread_scope_service_agents_softaculous.md](answers/20260404_161005_ANSWER_version_doc_thread_scope_service_agents_softaculous.md) — merge directive ≠ one thread; evidence-only `CHANGELOG`.
- **Doctrine:** [lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md](../../doctrine/SERVICE_AGENT_ARCHITECTURE.md) — PHP-first service agents; KAIROS flow; THOTH pointer.
- **Implementation:** [lupo-docs/implementations/service_agents/README.md](../../implementations/service_agents/README.md) — service agent transition mirror.
- **Doctrine:** [lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](../../doctrine/LUPOPEDIA_HEADERS/README.md) — THOTH semantic check grounded in JSON + table docs.
- **Spec:** [lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md](../../implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md) — silent install, sample config, packager notes.
- **PRD:** [lupo-docs/prd/00_root_constitutional_system_requirements.md](../../prd/00_root_constitutional_system_requirements.md) — §5.7–§5.10 (KAIROS, mirroring, THOTH, service roster).
- **Decision (this version):** [decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md](decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md) — APPROVED doctrine audit + mobile/workflow docs (5W1H).
- **Question / Answer:** [questions/20260403_140553_QUESTION_version_ghost_cleanup_policy.md](questions/20260403_140553_QUESTION_version_ghost_cleanup_policy.md) → [answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md](answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md) — ghost cleanup policy (manual per file).
- **Comment:** [comments/20260403_140555_COMMENT_cursor_doctrine_audit_version_sync.md](comments/20260403_140555_COMMENT_cursor_doctrine_audit_version_sync.md) — receipt for this version-folder sync pass.
- **Status:** [lupo-docs/implementations/29_project_structure/status/version_ghosts_report.json](../../implementations/29_project_structure/status/version_ghosts_report.json) — **34** critical files at report generation (scanner: `find_version_ghosts.py`).
- **Doctrine:** [lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md](../../doctrine/MOBILE_SEPARATION_DOCTRINE.md) — desktop vs mobile UI split.
- **Doctrine:** [lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md](../../doctrine/WOLFIE_WORKFLOW_DOCTRINE.md) — build order for consumer vs admin.
- **PRD:** [lupo-docs/prd/35_mobile_native_app_separation.md](../../prd/35_mobile_native_app_separation.md) — draft — native operator app (complements mobile web).
- **PRD:** [lupo-docs/prd/17_decisions_format.md](../../prd/17_decisions_format.md) — thread filename pattern (authoritative).
- **PRD:** [lupo-docs/prd/29_project_structure.md](../../prd/29_project_structure.md) — channel filesystem vs archive.
- **PRD:** [lupo-docs/prd/02_channels_discussions.md](../../prd/02_channels_discussions.md) — channel coordination semantics.
- **PRD:** [lupo-docs/prd/30_channel_usage_patterns.md](../../prd/30_channel_usage_patterns.md) — channel usage patterns (NEW).
- **PRD:** [lupo-docs/prd/31_implementation_folder_guidelines.md](../../prd/31_implementation_folder_guidelines.md) — implementation folder guidelines (NEW).
- **Doctrine:** [lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md](../../doctrine/MOOD_RGB_DOCTRINE.md) — Mood RGB summary (canonical thread under `lupo-channels/`).
- **Thread:** [lupo-channels/0/semantic/mood_rgb_system/README.md](../../../lupo-channels/0/semantic/mood_rgb_system/README.md) — Mood RGB on-disk thread.
- **Quick Reference:** [lupo-docs/CHANNEL_VS_DOCS_QUICK_REFERENCE.md](../../CHANNEL_VS_DOCS_QUICK_REFERENCE.md) — decision tree and usage patterns (NEW).
- **Framework Summary:** [lupo-docs/IMPLEMENTATION_FRAMEWORK_SUMMARY.md](../../IMPLEMENTATION_FRAMEWORK_SUMMARY.md) — complete framework overview (NEW).
- **Decision (this version):** [decisions/20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_rgb.md](decisions/20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_rgb.md) — 5W1H APPROVED outcomes.
- **PRD:** [lupo-docs/prd/32_actor_authority_agent_roles.md](../../prd/32_actor_authority_agent_roles.md) — actor hierarchy and approval authority (NEW).
- **Quick Reference:** [lupo-docs/ACTOR_AUTHORITY_QUICK_REFERENCE.md](../../ACTOR_AUTHORITY_QUICK_REFERENCE.md) — actor authority decision trees (NEW).
- **Decision (this version):** [decisions/20260402_220000_DECISION_actor_authority_prd32.md](decisions/20260402_220000_DECISION_actor_authority_prd32.md) — actor authority framework implementation (NEW).
- **Working PRDs (4.0.94):** [prd/30_prd_development_guide.md](prd/30_prd_development_guide.md), [prd/31_context_system.md](prd/31_context_system.md) — rewrite/redesign; align with [lupo-docs/prd/26_five_layer_documentation_architecture.md](../../prd/26_five_layer_documentation_architecture.md).
- **Session changelog:** [session_changelog/README.md](session_changelog/README.md) — deterministic session logs (`actor_id`, `session_id`, UTC BIGINT); no calendar-day aggregation.
- **Root rule:** [lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md](../../../lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md) — `tick.py` / `echo_anchor_utc.py`; no LLM-guessed UTC for headers.
- **Doctrine:** [lupo-docs/doctrine/TICK_PY_DOCTRINE.md](../../doctrine/TICK_PY_DOCTRINE.md) — operational workflow for anchor files.
- **Doctrine:** [lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md](../../doctrine/IDENTITY_LAYERS_DOCTRINE.md) — §3 actor / agent / facet (thread consolidation).
- **PRD:** [lupo-docs/prd/00_root_constitutional_system_requirements.md](../../prd/00_root_constitutional_system_requirements.md) — §3.5a documentation header UTC.
- **Decision (this version):** [decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md](decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md) — APPROVED 5W1H for this Cursor thread outcomes.
- **Question / Answer:** [questions/20260402_225224_QUESTION_version_doc_thread_scope.md](questions/20260402_225224_QUESTION_version_doc_thread_scope.md) → [answers/20260402_225225_ANSWER_version_doc_thread_scope.md](answers/20260402_225225_ANSWER_version_doc_thread_scope.md) — changelog must be thread-verified only.
- **Comment:** [comments/20260402_225226_COMMENT_cursor_thread_version_doc_sync.md](comments/20260402_225226_COMMENT_cursor_thread_version_doc_sync.md) — receipt for this sync pass.
- **Decision (this version):** [decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md](decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md) — IDE facet packs, `--target=vscode`, registry/doc alignment.
- **Question / Answer:** [questions/20260402_234552_QUESTION_ide_facet_version_doc_scope.md](questions/20260402_234552_QUESTION_ide_facet_version_doc_scope.md) → [answers/20260402_234553_ANSWER_ide_facet_version_doc_scope.md](answers/20260402_234553_ANSWER_ide_facet_version_doc_scope.md) — version doc must not claim template-only work.
- **Comment:** [comments/20260402_234554_COMMENT_cursor_ide_facet_documentation_pass.md](comments/20260402_234554_COMMENT_cursor_ide_facet_documentation_pass.md) — receipt for 4.0.94 tree update (IDE facet thread).
- **Comment:** [comments/20260402_235141_COMMENT_lilith_lineage_audit_question_234552.md](comments/20260402_235141_COMMENT_lilith_lineage_audit_question_234552.md) — LILITH audit: QUESTION→ANSWER `has_answer` / `answers` lineage restored.
- **PRD:** [lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md](../../prd/33_softaculous_certification_4_1_0_gate.md) — Softaculous / **4.1.0** release gate (**`status: approved`**).
- **Implementation:** [lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/README.md](../../implementations/33_softaculous_certification_4_1_0_gate/README.md) — PRD 33 workspace ( **`status/`**, typed threads).
- **PRD:** [lupo-docs/prd/00_root_constitutional_system_requirements.md](../../prd/00_root_constitutional_system_requirements.md) — §15 WordPress multi-environment patterns (thread: Softaculous / hoster resilience).
- **Doctrine:** [lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md](../../doctrine/LEARNED_FROM_WORDPRESS.md) — pattern distillate with line refs into research tree.
- **Doctrine:** [lupo-docs/doctrine/SEMANTIC_MONITORING_DOCTRINE.md](../../doctrine/SEMANTIC_MONITORING_DOCTRINE.md) — semantic monitoring vs **`livehelp_js`**; real routes only.
- **Doctrine:** [lupo-docs/doctrine/CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md](../../doctrine/CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md) — chat UI JS shared state; default **non-IIFE**.
- **PRD:** [lupo-docs/prd/28_semantic_monitoring_widget.md](../../prd/28_semantic_monitoring_widget.md) — Eye / monitoring widget (edges to semantic doctrine).
- **Question / Answer (implementation 33):** [../../implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_065622_QUESTION_softaculous_packager_distribution_flow.md](../../implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_065622_QUESTION_softaculous_packager_distribution_flow.md) → [../../implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_065622_ANSWER_softaculous_packager_distribution_flow_lilith.md](../../implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_065622_ANSWER_softaculous_packager_distribution_flow_lilith.md) — packager output vs dev tree (LILITH).
- **Comment:** [comments/20260404_074421_COMMENT_cursor_session_end_softaculous_wordpress_semantic_chat.md](comments/20260404_074421_COMMENT_cursor_session_end_softaculous_wordpress_semantic_chat.md) — session end **5W1H** receipt (**2026-04-04**).
- **Decision (this version):** [decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md](decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md) — APPROVED gate documentation + version sync.
- **Question / Answer:** [questions/20260403_022544_QUESTION_prd33_traceability_location.md](questions/20260403_022544_QUESTION_prd33_traceability_location.md) → [answers/20260403_022545_ANSWER_prd33_traceability_location.md](answers/20260403_022545_ANSWER_prd33_traceability_location.md) — §12 traceability: **`TODO.md`** + implementation hub.
- **Comment:** [comments/20260403_022546_COMMENT_cursor_prd33_version_doc_sync.md](comments/20260403_022546_COMMENT_cursor_prd33_version_doc_sync.md) — receipt for PRD 33 approval pass.
- **PRD:** [lupo-docs/prd/31_implementation_folder_guidelines.md](../../prd/31_implementation_folder_guidelines.md) — implementation folders (**LILITH** final audit **20260403024822**).
- **Decision (this version):** [decisions/20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md](decisions/20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md) — APPROVED PRD 31 LILITH + 4.0.94 sync.
- **Question / Answer:** [questions/20260403_025156_QUESTION_prd31_version_sync_changelog_scope.md](questions/20260403_025156_QUESTION_prd31_version_sync_changelog_scope.md) → [answers/20260403_025157_ANSWER_prd31_version_sync_changelog_scope.md](answers/20260403_025157_ANSWER_prd31_version_sync_changelog_scope.md) — CHANGELOG must list thread-verified work only.
- **Comment:** [comments/20260403_025158_COMMENT_cursor_session_end_prd31_next_session.md](comments/20260403_025158_COMMENT_cursor_session_end_prd31_next_session.md) — session end observations; next session → **`WHAT_TO_WORK_ON_NEXT_SESSION.md`**.
- **Handoff:** [WHAT_TO_WORK_ON_NEXT_SESSION.md](WHAT_TO_WORK_ON_NEXT_SESSION.md) — admin UI, install + Crafty import, parity, **Eye**.
- **Doctrine:** [lupo-docs/doctrine/AGENT_REGISTRY.md](../../doctrine/AGENT_REGISTRY.md) — IDE faucet table + propagation matrix (updated in thread).
- **AGENTS:** [AGENTS.md](../../../AGENTS.md) — IDE faucet table and `agents` map example.

## Code edges

- **VALIDATOR:** [lupo-scripts/validate_lupopedia_headers_universal.py](../../../lupo-scripts/validate_lupopedia_headers_universal.py) — thread headers (`thread_id`, `author`, tags).
- **VALIDATOR:** [lupo-scripts/validate_implementation.py](../../../lupo-scripts/validate_implementation.py) — implementation threads (when run against those paths).
- **SCAFFOLD:** [lupo-scripts/scaffold_implementation.py](../../../lupo-scripts/scaffold_implementation.py) — implementation folder creation + **`add-status`** (status artifacts + `status/THREAD_INDEX.md`).
- **VALIDATOR:** [lupo-scripts/validate_framework_compliance.py](../../../lupo-scripts/validate_framework_compliance.py) — framework compliance checking (NEW).
- **QUESTION:** [lupo-scripts/create_implementation_question.py](../../../lupo-scripts/create_implementation_question.py) — implementation question creation (enhanced).
- **ANCHOR:** [lupo-bin/tick.py](../../../lupo-bin/tick.py) — updates `temporal_anchor.json` / `CURRENT_UTC` from real system UTC.
- **ANCHOR:** [lupo-bin/echo_anchor_utc.py](../../../lupo-bin/echo_anchor_utc.py) — prints `current_utc` for reuse in same batch.
- **PROPAGATION:** [lupo-scripts/propagate_agent_rules.php](../../../lupo-scripts/propagate_agent_rules.php) — `--target=vscode` writes `.vscode/lupopedia/` (among other targets).
- **TOOLING:** [lupo-scripts/validate_actor_identity.py](../../../lupo-scripts/validate_actor_identity.py) — `IDE_FAUCETS` slug set for facet confusion checks.
- **INSTALL SQL:** [lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql](../../../lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql) — canonical **4.0.x** DDL (**170** tables post **`schema_review`** merge, **2026-04-07**).
- **INSTALLER:** [lupo-install/InstallWizardHtaccessWriter.php](../../../lupo-install/InstallWizardHtaccessWriter.php) — Apache **`.htaccess`** marker merge (`# BEGIN LUPOPEDIA` / `LUPOPEDIA_DB`).
- **PACKAGER:** [lupo-scripts/build_softaculous_package.sh](../../../lupo-scripts/build_softaculous_package.sh) — **Softaculous** tarball build; **rsync** excludes for sensitive dotfiles and **live** `lupopedia-config.php`.
- **CONFIG SAMPLE (root):** [lupopedia-config-sample.php](../../../lupopedia-config-sample.php) — Softaculous `[[softdb*]]` placeholders; not shipped as live config.
- **BOOTSTRAP:** [lupo-includes/bootstrap.php](../../../lupo-includes/bootstrap.php) — runtime writable dirs `mkdir` after config load.
- **KAIROS API:** [lupo-includes/modules/api/kairos-api.php](../../../lupo-includes/modules/api/kairos-api.php) — `POST` tick → consolidation.
- **KAIROS SERVICE:** [app/Services/Kairos/KairosConsolidationService.php](../../../app/Services/Kairos/KairosConsolidationService.php) — `lupo_actor_memory` + `lupo_edges`.
- **LOGOUT:** [logout.php](../../../logout.php) — clears `lupo_admin_scroll_intro_v1` in browser before redirect to `login.php`.
- **ADMIN LAYOUT:** [lupo-includes/themes/default/layouts/admin_layout.php](../../../lupo-includes/themes/default/layouts/admin_layout.php) — scroll nav row: logo lead, actor strip.
- **ADMIN SCROLL CSS:** [lupo-includes/css/admin-intro-scroll.css](../../../lupo-includes/css/admin-intro-scroll.css) — `.lupo-admin-nav-logo` (90×60), actor text width.
- **ADMIN SCROLL JS:** [lupo-includes/js/admin-intro-scroll.js](../../../lupo-includes/js/admin-intro-scroll.js) — intro overlay; `sessionStorage` key `lupo_admin_scroll_intro_v1`.
- **SEMANTIC EMBED GATE:** [lupo-includes/classes/SemanticNavbarEmbedContext.php](../../../lupo-includes/classes/SemanticNavbarEmbedContext.php) — cross-origin federation + trust; `federation_discovery` on deny.
- **SEMANTIC NAVBAR API:** [lupo-includes/modules/api/semantic-navbar-api.php](../../../lupo-includes/modules/api/semantic-navbar-api.php) — JSON sections per slug; `embed_not_trusted`.
- **ADMIN SEMANTIC WIDGET:** [lupo-includes/classes/AdminSemanticWidgetHandler.php](../../../lupo-includes/classes/AdminSemanticWidgetHandler.php) — register `lupo_federation_nodes`, grant `lupo_federated_trust`, snippets.

## External edges

- **NONE** (version-scoped graph only).

## Version graph (summary table)

| From | To | Type |
|------|-----|------|
| This version | `4.0.93/README.md`, `4.0.93/edges.md` | baseline |
| `decisions/20260403_222041_…` | `ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`, PRDs 02/05/07/13/15/18/25/32, `implementations/13/…/THREAD_INDEX.md` | references |
| `answers/20260403_222043_…` | `implementations/13` Q1–Q3, `decisions/20260403_222041_…`, doctrine | answers / references |
| `questions/20260403_222042_…` | `SILENT_HARVEST_DOCTRINE.md`, PRD 34 | references (OPEN) |
| `decisions/20260404_200000_…` | PRD 17, PRD 29, Mood RGB thread, `MOOD_RGB_DOCTRINE.md` | references |
| `decisions/20260402_210000_…` | PRD 30, PRD 31, Quick Reference, Framework Summary | framework implementation |
| `decisions/20260402_220000_…` | PRD 32, Actor Authority Quick Reference | actor authority framework |
| `prd/30` | PRD 16, 17, 26, `5W1H_QUICK_REFERENCE.md` | references (update as rewrite proceeds) |
| `prd/31` | PRD 26, `DOCUMENTATION_ARCHITECTURE.md` | must align |
| `prd/32` | AGENTS.md, actor registry, PRD 17 | actor hierarchy and approval |
| Framework scripts | Implementation folders, validation tools | automated tooling |
| `decisions/20260402_225223_…` | Identity §3, UTC root doctrine, PRD 00 §3.5a, tick/echo scripts | Cursor thread APPROVED |
| `questions/20260402_225224_…` | `answers/20260402_225225_…` | changelog scope Q&A |
| `decisions/20260402_234551_…` | `lupo-agents/*`, `propagate_agent_rules.php`, `AGENT_REGISTRY.md`, `AGENTS.md` | IDE facet + vscode propagation |
| `questions/20260402_234552_…` | `answers/20260402_234553_…` | template vs thread-verified changelog scope |
| `decisions/20260403_022543_…` | PRD 33, `implementations/33_…/README.md`, `TODO.md` | Softaculous gate doc APPROVED |
| `questions/20260403_022544_…` | `answers/20260403_022545_…` | PRD §12 traceability Q&A |
| `decisions/20260403_025155_…` | PRD 31, `WHAT_TO_WORK_ON_NEXT_SESSION.md` | LILITH final audit + handoff |
| `questions/20260403_025156_…` | `answers/20260403_025157_…` | PRD 31 CHANGELOG scope Q&A |
| `decisions/20260403_140552_…` | `MOBILE_SEPARATION_DOCTRINE.md`, `WOLFIE_WORKFLOW_DOCTRINE.md`, PRD 35, PRD 33 (where linked) | Doctrine audit + mobile/workflow APPROVED |
| `questions/20260403_140553_…` | `answers/20260403_140554_…`, `version_ghosts_report.json` | Ghost cleanup policy Q&A |
| `CHANGELOG` [2026-04-04] top entry | PRD 00 §15, `LEARNED_FROM_WORDPRESS.md`, semantic + chat JS doctrines, `InstallWizardHtaccessWriter.php`, `build_softaculous_package.sh`, `implementations/33_…` | Softaculous / WordPress thread (Cursor) |
| `comments/20260404_074421_…` | `WHAT_TO_WORK_ON_NEXT_SESSION.md`, `PLAN.md` Phase I, `SEMANTIC_MONITORING_DOCTRINE.md`, `CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md` | Session end receipt |
| `implementations/33_…/questions/20260404_065622_…` | `answers/20260404_065622_…` (LILITH) | Packager distribution flow Q&A |
| `decisions/20260404_161001_…` | PRD 00 §5, `SERVICE_AGENT_ARCHITECTURE.md`, `implementations/service_agents/`, `LUPOPEDIA_HEADERS/README`, Softaculous spec + sample config + packager + bootstrap | Service agents + auto-installer doc receipt |
| `questions/20260404_161004_…` | `answers/20260404_161005_…` | Version doc scope — no bundled PRD16/26/30/31 without evidence |
| `CHANGELOG` [2026-04-04] (service agents entry) | Same as `161001` decision | Thread-verified only |
| `decisions/20260404175216_…` | PRD 00 §14.6, `AGAPE_DOCTRINE.md`, PRDs 18/36/37/31/05, `scaffold_implementation.py` | AGAPE + temporal + routing doc receipt |
| `comments/20260404175216_…` | `PLAN` Phase K, `TODO`, `WHAT_TO_WORK_ON_NEXT_SESSION`, `edges` | Session end version-folder sync |
| `CHANGELOG` [2026-04-04] (version-folder sync entry) | `175216` decision + comment + indexes | Evidence UTC `20260404175352` |
| `decisions/20260405001004_…` | `logout.php`, `admin_layout.php`, `admin-intro-scroll.css`, `admin-intro-scroll.js` | Admin nav + logout intro thread |
| `comments/20260405001004_…` | `PLAN` L, `TODO`, `WHAT_TO_WORK_ON_NEXT_SESSION` | Session end + Crafty handoff |
| `CHANGELOG` [2026-04-05] | `501004` decision + comment | Evidence UTC `20260405001004` |
| `decisions/20260405104405_…` | `SemanticNavbarEmbedContext.php`, `semantic-navbar-api.php`, `AdminSemanticWidgetHandler.php`, PRD 21, `lupo-en.php` semantic keys | Semantic navbar embed + Admin + PRD 21 thread |
| `comments/20260405104405_…` | `PLAN` M, `TODO`, `WHAT_TO_WORK_ON_NEXT_SESSION`, Crafty handoff | Session end UTC `20260405104405` |
| `CHANGELOG` [2026-04-05] (semantic navbar entry) | `104405` decision + comment + indexes | Evidence UTC `20260405104405` |
| `CHANGELOG` [2026-04-06 17:30 UTC] prepend, `decisions/20260406_173021_…`, `questions/20260406_173022_…`, `answers/20260406_173022_…` | `4.0.95/CHANGELOG.md`, `FOR_CLAUDE_CODE_2026_04_06.md`, root `CHANGELOG.md`, `PLAN.md` Phase 6b | 5W1H close-out — **4.0.94** snapshot vs active line (UTC `20260406173021`) |
| `decisions/20260407_015813_…` | `mysql/install/install_new_lupopedia.sql`, `schema_review/schema_corrected_core.sql`, `schema_review/schema_corrected_missing.sql`, `CHANGELOG` [2026-04-07 02:00 UTC], `PLAN` Phase 8b, `questions/20260407_015814_…`, `answers/20260407_015815_…` | Install merge receipt + **`truth_*`** Q/A (UTC `20260407015813`) |

Update this file whenever a new thread file or PRD section creates a durable cross-link.
