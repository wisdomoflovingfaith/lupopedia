---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-docs/versions/4.0.85/IMPLEMENTATION_STATUS.md"
  last_modified_utc: "20260322_184651"
  channel_id: 42
  thread_id: 1047
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "documentation"
  artifact_kind: "derived_view"
  purpose: "Derived implementation status summary for 4.0.85."
---

# 4.0.85 IMPLEMENTATION STATUS

> Derived view. TASK_REGISTRY is authoritative for execution state.

## Implemented in 4.0.85

| item | description | thread |
|---|---|---|
| TASK_REGISTRY single-source model | Demoted THREAD_INDEX and version docs to derived views | 1047 |
| Authority and governance model documented | Version docs now carry final authority rules without requiring thread review | 1047, 2013 |
| TOON parity restoration | 166/166 tables, zero column drift | 2004 |
| lupo_visibility_state removal | Stale TOON projection removed | 2004 |
| 5 new TOON files | thread_metadata, human_requests, human_request_context, human_request_responses, decision_evidence | 2004 |
| 4 corrected TOON files | actors, channels, dialog_threads, tasks column parity | 2004 |
| CONTRADICTIONS.md diagnostic-only structure | Separated violation recording from execution authority | 1047 |
| Actor/auth_user many-to-many model | Canonical support-pool relationship corrected and validated | 2011 |
| Dialog routing MVP | Implemented, corrected, and validated as COMPLIANT | 2012 |
| Install readiness declaration | Dual PASS verdict: install schema and runtime system | 2013 |
| mood_rgb hybrid authority model | Canonical tokens authoritative; continuous values routing-only | 2015 |
| Edge-reference normalization | Transitional edge_ref markers in TASK_REGISTRY | 1047 |
| Channel 66 backfill inventory | 10 rows marked for edge-reference backfill | 1047 |
| Version documentation authority pass | Major thread outcomes normalized into version-scoped domain docs | 2013 |

## Designed â€” Not Yet Implemented

| item | description | thread |
|---|---|---|
| Decision lineage PHP layer | Actor choice traceability using lupo_decisions tables | 1048, 2003 |
| Decision system hook in TASK_REGISTRY | Placeholder registered; no registry effect | TASK_REGISTRY Â§Decision-System Hook |

## Researched â€” Application Deferred

| item | research_artifact | classification | defer_reason |
|---|---|---|---|
| Doom Emacs structural patterns | federation/doom_emacs_research.md | 9/10 patterns task_edge_model_applicable | Out of scope for 4.0.85 patch |
| BMAD method workflow patterns | federation/bmad_research.md | artifact dependency, phase ordering, status routing | Out of scope for 4.0.85 patch |

## Schema Tables â€” Present But Not Yet Used by PHP Layer

| table | status |
|---|---|
| lupo_decisions | schema present, PHP layer deferred |
| lupo_decision_edges | schema present, PHP layer deferred |
| lupo_decision_influences | schema present, PHP layer deferred |
| lupo_decision_evidence | schema present, PHP layer deferred |
| lupo_human_requests | schema present, MVP linkage implemented in routing flow |
| lupo_human_request_context | schema present, linkage surface available; broader PHP usage deferred |
| lupo_human_request_responses | schema present, broader PHP usage deferred |
| lupo_thread_metadata | schema present, broader PHP usage deferred |
