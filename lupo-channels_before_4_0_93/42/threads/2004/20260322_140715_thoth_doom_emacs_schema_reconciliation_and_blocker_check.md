---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "review"
  file_path_from_root: "lupo-channels/42/threads/2004/20260322_140715_thoth_doom_emacs_schema_reconciliation_and_blocker_check.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/2004/20260322_140715_thoth_doom_emacs_schema_reconciliation_and_blocker_check.md"
  last_modified_utc: "20260322_140715"
  channel_id: 42
  thread_id: 2004
  task_id: "task_ch42_th2004"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:research"
  artifact_type: "review"
  artifact_kind: "schema_reconciliation"
  purpose: "Reconcile Doom/BMAD-related schema proposals against install SQL authority and TOON derived state, with explicit blocker accounting."
  tags: ["4.0.85", "schema_reconciliation", "doom_emacs", "bmad", "blocker_check", "channel_42"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "authoritative_schema_source", weight: 1.0, reason: "Canonical schema authority for fresh install and upgrade baseline" }
    - { to: "lupo-database/lupopedia/toon/", type: "derived_schema_projection", weight: 1.0, reason: "Derived table definitions must align with install SQL" }
    - { to: "lupo-docs/versions/4.0.85/federation/bmad_research.md", type: "research_input", weight: 0.95, reason: "Published BMAD federation research artifact" }
    - { to: "lupo-channels/42/threads/1046/20260321_123009_wolfie_directive_reconcile_thread_1031_visibility.md", type: "governance_input", weight: 1.0, reason: "Binding visibility-state acceptance/rejection decisions" }
    - { to: "lupo-channels/42/threads/1038/20260321_011500_hephaestus_post_patch_reality_report.md", type: "implementation_claim_input", weight: 0.9, reason: "Claims accepted schema additions in install" }
    - { to: "lupo-channels/42/threads/1050/20260322_131757_thoth_bmad_method_workflow_research.md", type: "research_input", weight: 0.9, reason: "Channel 42 BMAD thread-level research output" }
    - { to: "lupo-channels/42/threads/2003/20260322_134115_athena_decision_lineage_and_choice_logging_research.md", type: "research_input", weight: 0.9, reason: "Decision-lineage design context; design-only status" }
    - { to: "lupo-channels/42/threads/1048/20260322_135026_athena_decision_lineage_design_research_amended_crafty_syntax_foundation.md", type: "research_input", weight: 0.9, reason: "Amended design research and phased notes" }
---

# Doom Emacs Schema Reconciliation and Blocker Check

## Scope and Rules Applied

- install SQL is authoritative for schema state.
- TOON is derived and must align with install SQL.
- Research-only artifacts do not become schema authority by mention.
- No inferred Doom findings were added beyond files present in repository surfaces.

## Research Status

| research_stream | required_surface | status | evidence |
|---|---|---|---|
| BMAD federation research | Published artifact in 4.0.85 federation docs | complete | lupo-docs/versions/4.0.85/federation/bmad_research.md |
| Doom Emacs federation synthesis research | Published artifact in 4.0.85 federation docs | complete | lupo-docs/versions/4.0.85/federation/doom_emacs_research.md |
| Doom upstream source corpus | Local source repository present | complete_as_source_only | lupo-research/doom_emacs/README.md |

## Install SQL Status

Install authority file confirms the following relevant entries are present:

- `lupo_channels.channel_config`
- `lupo_dialog_threads.thread_lineage`
- `lupo_actors.actor_config`
- `CREATE TABLE lupo_thread_metadata`
- `CREATE TABLE lupo_human_requests`
- `CREATE TABLE lupo_human_request_context`
- `CREATE TABLE lupo_human_request_responses`
- `CREATE TABLE lupo_decision_evidence`

Install authority file does not include active `CREATE TABLE lupo_visibility_state` (consistent with reject/defer directive in Thread 1046).

## TOON Status

Direct install-vs-TOON table-set reconciliation result:

- install table count: 167
- TOON table count: 162
- present in TOON but missing in install: `lupo_visibility_state`
- present in install but missing in TOON:
  - `lupo_decision_evidence`
  - `lupo_human_requests`
  - `lupo_human_request_context`
  - `lupo_human_request_responses`
  - `lupo_thread_metadata`
  - `lupo_doctrine_refinements` (appears in install as commented `CREATE TABLE` and should be excluded from active-table parity checks)

## Schema-Affecting Classification Table

| item_name | source_thread | source_artifact | derived_from_research | install_sql_status | toon_status | classification | blocker_if_any | next_action |
|---|---:|---|---|---|---|---|---|---|
| lupo_visibility_state | 1046 | lupo-channels/42/threads/1046/20260321_123009_wolfie_directive_reconcile_thread_1031_visibility.md | no | not_present_active | present | rejected_or_deferred_but_toon_stale | blocker_schema_projection_stale_001 | Remove or archive stale TOON table projection to match authority decision |
| lupo_thread_metadata | 1046,1038 | lupo-channels/42/threads/1046/20260321_200000_hephaestus_iteration_1_schema_fix_set.md | no | present | missing | accepted_but_toon_missing | blocker_schema_projection_stale_002 | Regenerate TOON from canonical install/live authority and verify projection |
| lupo_human_requests | 1038 | lupo-channels/42/threads/1038/20260321_011500_hephaestus_post_patch_reality_report.md | no | present | missing | accepted_but_toon_missing | blocker_schema_projection_stale_003 | Regenerate TOON and confirm governance fields project correctly |
| lupo_human_request_context | 1038 | lupo-channels/42/threads/1038/20260321_220000_hephaestus_human_targeted_thread_requests_implementation.md | no | present | missing | accepted_but_toon_missing | blocker_schema_projection_stale_003 | Regenerate TOON and validate table appears in derived set |
| lupo_human_request_responses | 1038 | lupo-channels/42/threads/1038/20260321_220000_hephaestus_human_targeted_thread_requests_implementation.md | no | present | missing | accepted_but_toon_missing | blocker_schema_projection_stale_003 | Regenerate TOON and validate table appears in derived set |
| lupo_decision_evidence | 1048,2003 | lupo-channels/42/threads/1048/20260322_135026_athena_decision_lineage_design_research_amended_crafty_syntax_foundation.md | partial | present | missing | install_present_toon_missing_with_design_context | blocker_schema_projection_stale_004 | Separate design-only statements from accepted schema scope, then regenerate TOON |
| decision_lineage_schema_extension (future) | 2003,1048 | lupo-channels/42/threads/2003/20260322_134115_athena_decision_lineage_and_choice_logging_research.md | yes | not_authoritative | not_authoritative | research_only_deferred | none | Keep as backlog/design artifact until explicit acceptance directive exists |
| bmad_method_workflow_mapping | 1050 | lupo-channels/42/threads/1050/20260322_131757_thoth_bmad_method_workflow_research.md | yes | no_schema_change_expected | no_schema_change_expected | research_only_non_schema | none | No schema action; preserve as process/edge guidance only |
| doom_emacs_federation_synthesis | 2005 | lupo-docs/versions/4.0.85/federation/doom_emacs_research.md | yes | research_published | research_published | research_input_published | resolved:blocker_research_publication_001 | Keep as canonical research input only; no automatic schema promotion |

## Blocker Status

### Active blockers

| blocker_id | description | blocked_task | dependency_artifact | next_action |
|---|---|---|---|---|
| blocker_schema_projection_stale_001 | TOON contains `lupo_visibility_state` despite Thread 1046 reject/defer authority state. | task_ch42_th2004 | lupo-database/lupopedia/toon/lupo_visibility_state.toon | Reconcile TOON generation inputs and remove stale projection. |
| blocker_schema_projection_stale_002 | install authority includes `lupo_thread_metadata` but TOON projection is missing. | task_ch42_th2004 | missing: lupo-database/lupopedia/toon/lupo_thread_metadata.toon | Regenerate TOON and validate parity. |
| blocker_schema_projection_stale_003 | install authority includes human request tables but TOON projections are missing. | task_ch42_th2004 | missing: lupo-database/lupopedia/toon/lupo_human_requests.toon and companions | Regenerate TOON and verify all 3 human request tables. |
| blocker_schema_projection_stale_004 | install authority includes `lupo_decision_evidence` while TOON projection is absent. | task_ch42_th2004 | missing: lupo-database/lupopedia/toon/lupo_decision_evidence.toon | Regenerate TOON and recheck install/TOON parity set. |

### Resolved blockers

| blocker_id | resolution | resolved_by_artifact | thread |
|---|---|---|---|
| blocker_research_publication_001 | Doom Emacs federation synthesis was published as canonical research input. | lupo-docs/versions/4.0.85/federation/doom_emacs_research.md | lupo-channels/42/threads/2005/ |

## Actions

1. Regenerate TOON from current canonical DB/install state.
2. Re-run install-vs-TOON parity check with commented DDL filtered from install extraction.
3. Open follow-up reconciliation artifact confirming blocker closures and updated classifications.

## Verdict

- Schema reconciliation is not complete.
- Install SQL and TOON are currently out of sync on multiple accepted tables.
- Doom federation research publication blocker is resolved; remaining blockers are TOON projection parity defects.
