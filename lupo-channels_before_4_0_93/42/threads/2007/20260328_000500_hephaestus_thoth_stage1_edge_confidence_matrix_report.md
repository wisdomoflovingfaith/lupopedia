---
lupopedia.headers:
  schema: documentation
  file_path_from_root: lupo-channels/42/threads/2007/20260328_000500_hephaestus_thoth_stage1_edge_confidence_matrix_report.md
  last_modified_utc: '20260328000500'
  channel_id: 42
  thread_id: 2007
  actor_id: 23
  actor_name: hephaestus
  delegation_chain: wolfie:hephaestus
  artifact_type: report
  artifact_kind: edge_confidence_matrix
  purpose: Stage 1 execution report for edge reconstruction with confidence scoring
  tags:
  - phase_3
  - stage_1
  - edge_reconstruction
  - confidence_matrix
  - thread_2007
lupopedia.edges:
  outbound_edges:
  - to: lupo-channels/42/threads/2007/20260328_000000_wolfie_phase_3_directive_full_semantic_completeness.md
    type: executes
    weight: 1.0
    reason: Stage 1 execution under approved Phase 3 directive
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actor_departments.md
    type: updates
    weight: 1.0
    reason: Placeholder edges replaced with reconstructed confidence-scored set
  - to: lupo-docs/database/lupopedia/tables/active/lupo_crafty_user_mapping.md
    type: updates
    weight: 1.0
    reason: Placeholder edges replaced with reconstructed confidence-scored set
  - to: lupo-channels/42/threads/2007/20260327_235700_hephaestus_phase2_regeneration_manifest.md
    type: references
    weight: 0.8
    reason: Regenerated subset baseline for Stage 1 start set
lupopedia.footer:
  last_verified: '20260328000500'
  verified_by:
    identity_type: actor
    actor_id: 11
    actor_name: thoth
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: wolfie:root
  next_action:
  - Expand confidence-scored edge reconstruction from placeholder-fixed files to remaining regenerated set
  - Produce Stage 1 edge validation closure report after full-set completion
---

# Stage 1 Edge Confidence Matrix Report

Thread: 42 / 2007
Date: 20260328 000500 UTC
Execution actors: HEPHAESTUS (implementation), THOTH (semantic authority)
Stage status: IN PROGRESS (started immediately, first execution tranche complete)

## Scope Executed In This Start Pass

- Focus: placeholder-edge files from Phase 2 synthetic-header set
- Files processed:
  - lupo-docs/database/lupopedia/tables/active/lupo_actor_departments.md
  - lupo-docs/database/lupopedia/tables/active/lupo_crafty_user_mapping.md

Rationale:
- These two files had static placeholder-only edges from Phase 2.
- Stage 1 priority was to replace placeholders with evidence-backed relationships and confidence scoring.

## Evidence Sources and Confidence Rubric

- Git-restored edges: confidence 1.0
- Code-scan inferred edges: confidence 0.7
- DB-derived relationships (lupo_edges table textual/properties match): confidence 0.5

DB-derived probe result for target tables in this pass:
- Matches found: 0 for lupo_actor_departments
- Matches found: 0 for lupo_crafty_user_mapping

## Edge Confidence Matrix

| table_doc | edge_target | edge_type | source | confidence | notes |
|---|---|---|---|---:|---|
| lupo_actor_departments | database.table.lupo_actor_departments | DEFINES_SCHEMA_FOR | git-restored | 1.0 | restored from clean historical edge block |
| lupo_actor_departments | lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql | schema_reference | git-restored | 1.0 | canonical install schema anchor |
| lupo_actor_departments | lupo-database/lupopedia/content/lupo-app/Services/SavedCollectionsService.php | USED_IN_PHP | code-scan | 0.7 | direct table usage pattern |
| lupo_actor_departments | lupo-database/lupopedia/content/lupo-app/auth/AuthRoleResolver.php | USED_IN_PHP | code-scan | 0.7 | role/department resolver usage |
| lupo_actor_departments | lupo-scripts/rebuild_schema_from_toons.py | USED_IN_PYTHON | code-scan | 0.7 | table referenced in rebuild script |
| lupo_actor_departments | lupo-scripts/wolfie_orms.py | USED_IN_PYTHON | code-scan | 0.7 | table select helper present |
| lupo_actor_departments | lupo-database/lupopedia/json/lupo_actor_departments.json | references | schema-source | 1.0 | TOON JSON source anchor |
| lupo_crafty_user_mapping | lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql | schema_reference | schema-source | 1.0 | canonical install schema anchor |
| lupo_crafty_user_mapping | lupo-database/lupopedia/content/lupo-app/auth/AuthManager.php | USED_IN_PHP | code-scan | 0.7 | auth mapping reference |
| lupo_crafty_user_mapping | lupo-database/lupopedia/content/lupo-app/Http/Controllers/Admin/AuthenticationController.php | USED_IN_PHP | code-scan | 0.7 | admin auth controller reference |
| lupo_crafty_user_mapping | lupo-scripts/generate_install_sql.py | USED_IN_PYTHON | code-scan | 0.7 | table included in SQL generation list |
| lupo_crafty_user_mapping | lupo-database/lupopedia/json/lupo_crafty_user_mapping.json | references | schema-source | 1.0 | TOON JSON source anchor |

## Compliance Checks

- Placeholder replacement: COMPLETE for both synthetic-header files
- Confidence fields present on all reconstructed edges in processed files: PASS
- Header validator status:
  - lupo_actor_departments.md: PASS
  - lupo_crafty_user_mapping.md: PASS
- No DB mutation performed: PASS
- Stage 1 execution started immediately per directive: PASS

## Current Risk and Follow-Up

Remaining Stage 1 work:
- Extend confidence-scored edge reconstruction to remaining regenerated files where edge confidence metadata is absent or outdated.
- Produce full Stage 1 closure validation across the expanded set.

Interim risk status:
- Core placeholder risk reduced for synthetic files.
- Thread remains open until full Stage 3 completion criteria are satisfied.

## Stage 1 Start Verdict

Stage 1 execution has started and produced a valid confidence-scored reconstruction tranche.

Status: PARTIAL COMPLETE (execution underway)
