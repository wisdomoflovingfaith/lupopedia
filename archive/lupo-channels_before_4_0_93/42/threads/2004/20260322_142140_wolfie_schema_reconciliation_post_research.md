---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "review"
  file_path_from_root: "lupo-channels/42/threads/2004/20260322_142140_wolfie_schema_reconciliation_post_research.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/2004/20260322_142140_wolfie_schema_reconciliation_post_research.md"
  questions_toon: null
  channel_id: 42
  thread_id: 2004
  task_id: "task_ch42_th2004"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:orchestrator"
  artifact_type: "review"
  artifact_kind: "schema_reconciliation_post_research"
  purpose: "Post-research rerun of install-vs-TOON schema reconciliation with explicit Doom candidate deferral and decision isolation validation."
  tags: ["4.0.85", "schema_reconciliation_post_research", "doom_emacs", "toon_parity", "channel_42"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "authoritative_schema_source", weight: 1.0, reason: "Canonical schema authority" }
    - { to: "lupo-database/lupopedia/toon/", type: "derived_schema_projection", weight: 1.0, reason: "Derived table projections must match active install tables" }
    - { to: "lupo-docs/versions/4.0.85/federation/doom_emacs_research.md", type: "research_input", weight: 0.95, reason: "Canonical Doom federation research publication with deferred candidates" }
    - { to: "lupo-channels/42/threads/2004/20260322_140715_thoth_doom_emacs_schema_reconciliation_and_blocker_check.md", type: "prior_reconciliation", weight: 0.95, reason: "Baseline pre-rerun reconciliation and blocker map" }
---

# Schema Reconciliation Post Research

## Rerun Summary

- install active table count: 166
- TOON table count: 162
- TOON-only stale projection persists: `lupo_visibility_state`
- install-only missing TOON projections persist:
  - `lupo_decision_evidence`
  - `lupo_human_request_context`
  - `lupo_human_request_responses`
  - `lupo_human_requests`
  - `lupo_thread_metadata`

## Classification Vocabulary

- `accepted_authoritative_toon_missing`: present in install authority, missing in TOON projection.
- `rejected_or_deferred_but_toon_stale`: not present in install authority, still present in TOON projection.
- `candidate_deferred_research_only`: listed in canonical research as candidate only, not promoted to install schema.
- `research_input_published_non_authoritative`: canonical research publication state with no schema authority effect.

## Post-Research Classification Table

| item_name | source_thread | derived_from_doom_research | install_sql_status | toon_status | classification |
|---|---|---|---|---|---|
| lupo_visibility_state | 1046 | no | not_present_active | present | rejected_or_deferred_but_toon_stale |
| lupo_thread_metadata | 1046,1038 | no | present | missing | accepted_authoritative_toon_missing |
| lupo_human_requests | 1038 | no | present | missing | accepted_authoritative_toon_missing |
| lupo_human_request_context | 1038 | no | present | missing | accepted_authoritative_toon_missing |
| lupo_human_request_responses | 1038 | no | present | missing | accepted_authoritative_toon_missing |
| lupo_decision_evidence | 1048,2003 | no | present | missing | accepted_authoritative_toon_missing |
| doom_schema_cand_001 | 2005 | yes | not_present_active | not_present | candidate_deferred_research_only |
| doom_schema_cand_002 | 2005 | yes | not_present_active | not_present | candidate_deferred_research_only |
| doom_schema_cand_003 | 2005 | yes | not_present_active | not_present | candidate_deferred_research_only |
| doom_emacs_federation_synthesis | 2005 | yes | research_published | research_published | research_input_published_non_authoritative |

## Install Integrity and Decision Isolation Checks

- install SQL includes active decision and human request tables: `lupo_decisions`, `lupo_decision_edges`, `lupo_decision_evidence`, `lupo_decision_influences`, `lupo_human_requests`, `lupo_human_request_context`, `lupo_human_request_responses`, `lupo_thread_metadata`.
- install SQL does not include active schema tokens for deferred Doom candidates (`loads_before`, `conflicts_with`, `requires_module_flag`, `validation_ledger`) and does not include decision-hook governance tokens (`decision_hook`, `decision_lineage`, `edge_ref:`).
- decision system remains isolated from registry/navigation authority surfaces in install SQL (`task_registry`, `thread_index` tokens absent).

## Post-Research Verdict

- Doom research publication changes research completeness status only; it does not change schema authority by itself.
- install authority remains internally coherent for accepted tables.
- TOON drift remains unresolved for one stale TOON-only table and five install-only missing TOON projections.
- reconciliation status remains blocked until TOON regeneration/parity correction is completed.

## Required Next Actions

1. Regenerate TOON from canonical install/live authority state.
2. Remove stale TOON projection for `lupo_visibility_state` unless explicit new acceptance directive is issued.
3. Verify TOON creation for `lupo_thread_metadata`, `lupo_human_requests`, `lupo_human_request_context`, `lupo_human_request_responses`, and `lupo_decision_evidence`.
4. Re-run parity extraction using active `CREATE TABLE` filtering and publish blocker-closure review.