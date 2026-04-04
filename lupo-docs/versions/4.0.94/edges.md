---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404161001"
  file_path_from_root: "lupo-docs/versions/4.0.94/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/edges.md"
  last_modified_utc: "20260404161001"
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
lupopedia.footer:
  last_verified: "20260404161001"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/edges.md — delegation: cursor:root

## Documentation edges

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
- **SCAFFOLD:** [lupo-scripts/scaffold_implementation.py](../../../lupo-scripts/scaffold_implementation.py) — automated implementation folder creation (NEW).
- **VALIDATOR:** [lupo-scripts/validate_framework_compliance.py](../../../lupo-scripts/validate_framework_compliance.py) — framework compliance checking (NEW).
- **QUESTION:** [lupo-scripts/create_implementation_question.py](../../../lupo-scripts/create_implementation_question.py) — implementation question creation (enhanced).
- **ANCHOR:** [lupo-bin/tick.py](../../../lupo-bin/tick.py) — updates `temporal_anchor.json` / `CURRENT_UTC` from real system UTC.
- **ANCHOR:** [lupo-bin/echo_anchor_utc.py](../../../lupo-bin/echo_anchor_utc.py) — prints `current_utc` for reuse in same batch.
- **PROPAGATION:** [lupo-scripts/propagate_agent_rules.php](../../../lupo-scripts/propagate_agent_rules.php) — `--target=vscode` writes `.vscode/lupopedia/` (among other targets).
- **TOOLING:** [lupo-scripts/validate_actor_identity.py](../../../lupo-scripts/validate_actor_identity.py) — `IDE_FAUCETS` slug set for facet confusion checks.
- **INSTALLER:** [lupo-install/InstallWizardHtaccessWriter.php](../../../lupo-install/InstallWizardHtaccessWriter.php) — Apache **`.htaccess`** marker merge (`# BEGIN LUPOPEDIA` / `LUPOPEDIA_DB`).
- **PACKAGER:** [lupo-scripts/build_softaculous_package.sh](../../../lupo-scripts/build_softaculous_package.sh) — **Softaculous** tarball build; **rsync** excludes for sensitive dotfiles and **live** `lupopedia-config.php`.
- **CONFIG SAMPLE (root):** [lupopedia-config-sample.php](../../../lupopedia-config-sample.php) — Softaculous `[[softdb*]]` placeholders; not shipped as live config.
- **BOOTSTRAP:** [lupo-includes/bootstrap.php](../../../lupo-includes/bootstrap.php) — runtime writable dirs `mkdir` after config load.
- **KAIROS API:** [lupo-includes/modules/api/kairos-api.php](../../../lupo-includes/modules/api/kairos-api.php) — `POST` tick → consolidation.
- **KAIROS SERVICE:** [app/Services/Kairos/KairosConsolidationService.php](../../../app/Services/Kairos/KairosConsolidationService.php) — `lupo_actor_memory` + `lupo_edges`.

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

Update this file whenever a new thread file or PRD section creates a durable cross-link.
