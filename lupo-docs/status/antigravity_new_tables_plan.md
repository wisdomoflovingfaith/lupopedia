---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "documentation"
  system_version: "4.0.74"
  file_path_from_root: "lupo-docs/status/antigravity_new_tables_plan.md"
  web_path: "http://www.lupopedia.com/status/antigravity_new_tables_plan"
  last_modified_utc: "20260314"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 103
  actor_name: "antigravity"
  faucet_name: "antigravity"
  artifact_type: "plan"
  artifact_kind: "roadmap"
  purpose: "Proposed plan for including new tables in install based on the 199 table limit"

lupopedia.footer:
  last_verified: "20260314"
  last_verified_by: "antigravity"
  orchestrator: "wolfie"
  next_action:
    - "Review proposed install candidates"
---
# file: antigravity_new_tables_plan.md — session: L-LUPO-ROOT-ANTIGRAVITY — delegation: wolfie:root (faucet: antigravity) — web_path: http://www.lupopedia.com/status/antigravity_new_tables_plan

# Planned Tables — Report, Proposed Use, and Install Candidates (4.0.74)

**Generated:** 2026-03-14  
**Context:** Current install has **146** Lupopedia tables. Max total is **199** tables (including 34 legacy Crafty Syntax tables during upgrade). Therefore up to **19** additional tables may be added to the install before hitting the cap.  
**Sources:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (current), `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql` (planned).

---

## 1. Summary

| Metric | Value |
|--------|--------|
| Current install tables | 146 |
| Max tables (with 34 old) | 199 |
| **Slots available for new tables** | **19** |
| Planned tables in future_features (with CREATE TABLE) | 48 |

All 48 planned tables are defined in `future_features_lupopedia.sql`. None are created by the standard install. This report lists each with **proposed use**, **implementation note**, and whether it is a **recommended candidate** for inclusion in the install (up to 19).

---

## 2. Planned Tables: Proposed Use and Implementation

### 2.1 Actor / identity (6 tables)

| Table | Proposed use | Implementation | Candidate? |
|-------|--------------|----------------|------------|
| **lupo_aliases** | Generic slug→alias mapping (semantic, redirects). | Single table for all alias types; `alias_type` discriminator. Complements `lupo_contents` slugs. | **Yes** — small, generic, useful for routing/redirects. |
| **lupo_actor_aliases** | Per-actor display names / nicknames. | Links `actor_id` to `alias_name`; could be merged into `lupo_metadata` or `lupo_actor_*` if we prefer one less table. | Maybe — low priority unless multi-identity feature is needed. |
| **lupo_actor_object_edges** | Actor→generic object links (any table.row). | Polymorphic edges: `target_table` + `target_id` + `edge_type`. Overlaps conceptually with `lupo_edges` / `lupo_actor_edges`. | No — extend `lupo_edges` or `lupo_actor_edges` first. |
| **lupo_actor_persona_relationships** | Relationships between actors and persona profiles. | Depends on `lupo_persona_profiles`. For AI/agent persona linking. | No — defer until persona feature is required. |
| **lupo_actor_relationship_rules** | Rules for how actors relate (source/target, conditions, actions). | Governance/orchestration; JSON for conditions/actions. | No — 4.1.0 or when orchestrator rules expand. |
| **lupo_actor_truth_edges** | Actor↔truth item (QA/knowledge) edges. | Overlaps with `lupo_edges` + entity_type. | No — use `lupo_edges` with type discriminator. |

### 2.2 Content / docs / search (7 tables)

| Table | Proposed use | Implementation | Candidate? |
|-------|--------------|----------------|------------|
| **lupo_tldnr** | “Too long; did not read” summaries per slug. | Topic/summary content; versioned. Fits content/semantic layer. | **Yes** — clear use for summaries and TL;DR. |
| **lupo_document_embeddings** | Vector/embedding storage for chunks (e.g. semantic search). | `chunk_id`, `embedding_json`, `embedding_model`. Often used by external search/RAG. | **Yes** — if semantic search or RAG is in scope for 4.0.x. |
| **lupo_documentation_frameworks** | Framework metadata for docs (namespace, channel, collection, orchestrator). | Synthesized docs; links to lupo-agents/channels/collections via app logic. | **Yes** — aligns with LUPOPEDIA HEADERS and doc framework. |
| **lupo_legacy_content_mapping** | Legacy URL → semantic URL mapping (redirects, migration). | One row per legacy path; supports post-Crafty URL cleanup. | **Yes** — useful for upgrade and redirects. |
| **lupo_reference_objects** | Canonical reference entities (citations, bibliography). | `object_type`, `object_slug`, `object_label`; referenced by content. | **Yes** — supports references and citations. |
| **lupo_reference_cited_by** | Which content cites which reference (many-to-many). | Links `reference_object_id` to `content_id` + section. | **Yes** — pairs with `lupo_reference_objects`. |
| **lupo_search_index** | Full-text/search index over entities (domain, entity_type, entity_id). | Title/body/keywords; relevance. Could be external service; if in-DB, one table is enough. | **Yes** — core search if no external engine. |

### 2.3 Governance / gov_* (7 tables)

| Table | Proposed use | Implementation | Candidate? |
|-------|--------------|----------------|------------|
| **lupo_gov_events** | Governance events (directives, versions, canonical path). | Central gov event; links to timeline, refs, valuations. | Maybe — only if governance UI/features are in 4.0.x. |
| **lupo_gov_event_actor_edges** | Actor↔gov_event links (who did what). | Edge table for gov_events. | No — add only with lupo_gov_events. |
| **lupo_gov_event_conflicts** | Conflicts between gov events. | Conflict pairs + type/severity. | No — defer with gov suite. |
| **lupo_gov_event_dependencies** | Dependencies between gov events. | Dependency graph. | No — defer with gov suite. |
| **lupo_gov_event_references** | References/documents per gov event. | Refs, URLs, order. | No — defer with gov suite. |
| **lupo_gov_timeline_nodes** | Timeline nodes for gov events. | Node type, title, timestamp, parent. | No — defer with gov suite. |
| **lupo_gov_valuations** | Valuations/metrics per gov event. | Valuation type, metric, value, currency. | No — defer with gov suite. |

### 2.4 Emotional / persona (6 tables)

| Table | Proposed use | Implementation | Candidate? |
|-------|--------------|----------------|------------|
| **lupo_emotional_constellations** | Emotion frameworks (stars, cultural origin). | Metadata for emotion models; JSON stars. | No — niche; defer unless emotion feature is required. |
| **lupo_emotional_stars** | Individual emotion “stars” (experiences, context). | Fine-grained emotion data. | No — defer. |
| **lupo_emotional_translations** | Cross-framework emotion state mapping. | Source/target framework + state; loss score. | No — defer. |
| **lupo_entity_properties** | Generic entity_type + entity_id key/value properties. | Overlaps with `lupo_metadata` (entity_type, entity_id). | No — use `lupo_metadata`; avoid duplicate pattern. |
| **lupo_persona_profiles** | Persona definitions (name, type, traits, voice, capabilities). | Base table for AI/agent personae. | Maybe — if persona feature is in scope. |
| **lupo_persona_dialogue_patterns** | Dialogue patterns per persona (triggers, responses). | Depends on persona_profiles. | No — add only with persona_profiles. |

### 2.5 Federation / trust (2 tables)

| Table | Proposed use | Implementation | Candidate? |
|-------|--------------|----------------|------------|
| **lupo_federated_trust** | Trust levels and capabilities between federation nodes. | source_node_id, target_node_id, trust_level, capabilities JSON. Complements `lupo_federation_nodes`. | **Yes** — extends existing federation tables. |
| **lupo_federation_discovery** | Discovered federation instances (domain, URL, counts, import flags). | Discovery/crawl metadata; links to federation. | **Yes** — pairs with federated_trust. |

### 2.6 Tasks (2 tables)

| Table | Proposed use | Implementation | Candidate? |
|-------|--------------|----------------|------------|
| **lupo_task_assignments** | Task↔actor assignments (assigned_by, assignment_type). | Requires a `lupo_tasks` (or equivalent) in install. | Maybe — only if task feature is in 4.0.x and tasks table exists. |
| **lupo_task_dependencies** | Task dependency graph (blocks, etc.). | depends_on_task_id, dependency_type. | No — add with task_assignments if tasks are in scope. |

*Note:* Install has no `lupo_tasks` in the current list; task_* tables are only useful once a tasks feature is added.

### 2.7 Logging / sessions / ANUBIS (4 tables)

| Table | Proposed use | Implementation | Candidate? |
|-------|--------------|----------------|------------|
| **lupo_unified_log** | Single log table for multiple log types (anubis, audit, event, etc.). | Enum log_type, log_level, message, context JSON. Complements or supersedes multiple log tables. | **Yes** — consolidates logging; reduces future table sprawl. |
| **lupo_session_recovery** | Session recovery state (session_data, state_snapshot, recovery_attempts). | For crash/recovery flows. | Maybe — if session recovery feature is required. |
| **lupo_anubis_operations** | Unified ANUBIS audit (mirrored, orphaned, revised, deleted). | operation_type, target_type, target_id, details_json. Replaces multiple anubis_* log tables. | **Yes** — aligns with Antigravity consolidation; single audit table. |
| **lupo_system_health_snapshots** | System health/temporal snapshots (table_count, schema_hash, utc_anchor). | Monitoring/sanity checks. | **Yes** — lightweight; useful for install verification and audits. |

### 2.8 Channel / boot / analytics (2 tables)

| Table | Proposed use | Implementation | Candidate? |
|-------|--------------|----------------|------------|
| **lupo_channel_boot_log** | Boot lifecycle log (actor, session, timing, status, channels_loaded). | Complements channel_boot_detail / lifecycle. | Maybe — if boot diagnostics are needed in install. |
| **lupo_analytics_referers_periods** | Referer analytics by period (content_id, referer, visits, period_type). | Analytics/reporting. | No — defer to analytics feature. |

### 2.9 Misc / ops (12 tables)

| Table | Proposed use | Implementation | Candidate? |
|-------|--------------|----------------|------------|
| **lupo_interface_translations** | i18n: language_code, translation_key, translation_text. | Standard i18n table. | **Yes** — useful for multi-language UI. |
| **lupo_hotfix_registry** | Registry of applied hotfixes (version, applied_ymdhis, description). | Ops/deployment tracking. | **Yes** — small, clear ops value. |
| **lupo_llm_performance** | Per-actor/LLM performance (tokens, latency, cost, quality). | Optional telemetry for AI features. | No — defer until LLM features need it. |
| **lupo_metrics_archive_legacy** | Legacy metric key/value archive. | Simple archive. | No — defer or merge into unified_log. |
| **lupo_modules_departments** | Module↔department association (is_enabled, sort_order). | Links lupo_modules to lupo_departments. | Maybe — if module-department assignment is in use. |
| **lupo_mood_assignments** | Mood id assigned to (table_name, row_id). | Generic mood tagging. Overlaps with `lupo_actor_moods`. | No — consolidate with actor_moods or metadata. |
| **lupo_mood_registry** | Mood definitions (type, variant, rgb, description). | Lookup for mood_assignments. | No — defer with mood_assignments. |
| **lupo_pack_role_registry** | Agent role definitions (role_key, discovery_method, behavior). | Pack/agent roles. | No — defer to agent/pack feature. |
| **lupo_registry_import** | Federation import registry (entity_type, source node, resolved local id). | Tracks imported entities from other nodes. | Maybe — if federation import is in 4.0.x. |
| **lupo_kapu_events** | Kapu events (restrictions, restoration, agent). | Domain-specific. | No — defer. |
| **lupo_kapu_restoration_paths** | Restoration paths for kapu. | Domain-specific. | No — defer. |
| **lupo_human_history_meta** | Meta for “human history” (event_key, tensor_mapping, philosophical_reference). | Niche. | No — defer. |

---

## 3. Recommended 19 Candidates for Install

To stay at or under the 199-table cap, the following **19** planned tables are recommended for inclusion in `install_new_lupopedia.sql`, with brief rationale.

| # | Table | Rationale |
|---|--------|-----------|
| 1 | **lupo_aliases** | Generic slug/alias mapping; small, supports routing and redirects. |
| 2 | **lupo_tldnr** | TL;DR/summaries per slug; clear content feature. |
| 3 | **lupo_document_embeddings** | Embeddings for semantic search/RAG if in scope. |
| 4 | **lupo_documentation_frameworks** | Aligns with LUPOPEDIA HEADERS and doc framework. |
| 5 | **lupo_legacy_content_mapping** | Legacy→semantic URL mapping for upgrade and redirects. |
| 6 | **lupo_reference_objects** | Canonical references for citations. |
| 7 | **lupo_reference_cited_by** | Content↔reference linkage. |
| 8 | **lupo_search_index** | In-DB search index if no external engine. |
| 9 | **lupo_federated_trust** | Extends federation with trust/capabilities. |
| 10 | **lupo_federation_discovery** | Discovery of federation instances. |
| 11 | **lupo_unified_log** | Single consolidated log table. |
| 12 | **lupo_anubis_operations** | Unified ANUBIS audit (Antigravity-style). |
| 13 | **lupo_system_health_snapshots** | Lightweight health/schema snapshots. |
| 14 | **lupo_interface_translations** | i18n for UI. |
| 15 | **lupo_hotfix_registry** | Ops hotfix tracking. |
| 16 | *(reserved)* | Slot for lupo_tasks if task feature is added; else use for one of the “Maybe” candidates below. |
| 17 | *(reserved)* | e.g. lupo_session_recovery or lupo_channel_boot_log. |
| 18 | *(reserved)* | e.g. lupo_actor_aliases or lupo_registry_import. |
| 19 | *(reserved)* | e.g. lupo_modules_departments or lupo_persona_profiles. |

**Reserved slots (16–19)** can be assigned to: `lupo_session_recovery`, `lupo_channel_boot_log`, `lupo_actor_aliases`, `lupo_registry_import`, `lupo_modules_departments`, or `lupo_persona_profiles` once product priorities are set.

---

## 4. Implementation Notes

1. **Doctrine:** Any table added to the install must follow project doctrine: no FKs/triggers; BIGINT UTC YmdHis timestamps; PK `<table_singular>_id`; reserved-ID handling where applicable. Review and fix `future_features_lupopedia.sql` DDL before copying into `install_new_lupopedia.sql`.
2. **Order of work:** Add chosen tables to `install_new_lupopedia.sql`; remove or leave them in `future_features_lupopedia.sql` as “optional” only if they remain optional. Regenerate TOONs; update REQUIRED_TABLES and any docs that list install tables.
3. **References:** Existing reports: [PLANNED_TABLES_NOT_CREATED_REPORT.md](PLANNED_TABLES_NOT_CREATED_REPORT.md), [PLANNED_TABLES_IMPLEMENTATION_PLAN.md](PLANNED_TABLES_IMPLEMENTATION_PLAN.md). Doctrine: `lupo-rules/root/required-tables-future-features-doctrine.md`.

---

## 5. Tables Not Recommended for Install (Stay in future_features)

- **Actor:** actor_object_edges, actor_persona_relationships, actor_relationship_rules, actor_truth_edges (extend existing edge tables or defer).
- **Gov:** Full gov_* suite (7 tables) — defer until governance feature is required.
- **Emotional/persona:** emotional_constellations, emotional_stars, emotional_translations, entity_properties, persona_dialogue_patterns (and optionally persona_profiles) — defer or use metadata.
- **Tasks:** task_assignments, task_dependencies — add only when lupo_tasks exists and task feature is in scope.
- **Misc:** kapu_*, mood_assignments, mood_registry, pack_role_registry, human_history_meta, metrics_archive_legacy, llm_performance, analytics_referers_periods — defer unless specifically required.

---

**Document version:** 1.0  
**Last updated:** 2026-03-14
