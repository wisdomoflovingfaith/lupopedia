---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "implementation_plan"
  file_path_from_root: "lupo-docs/status/planned_tables_install_plan_codex_4_0_74.md"
  web_path: "http://www.lupopedia.com/status/planned_tables_install_plan_codex_4_0_74"
  last_modified_utc: "20260314"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "jetbrains_codex"
  delegation_chain: "wolfie:root"
  artifact_type: "status_plan"
  artifact_kind: "database_planning"
  purpose: "Plan-only shortlist for future_features tables to move into install without implementation yet"
lupopedia.edges:
  comment: "Snapshot of planning references at artifact creation."
  meta: "4.0.74 planned table selection pass by Codex"
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-rules/root/required-tables-future-features-doctrine.md", type: "references", weight: 0.95 }
  semantic_tags: ["planned_tables", "install_plan", "future_features", "4.0.74"]
lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  last_verified_by: "codex"
  orchestrator: "wolfie"
  next_action:
    - "Approve shortlist before any SQL changes"
    - "Prepare one-time migration files only after approval"
    - "Regenerate TOON and schema docs after implementation"
---
# file: planned_tables_install_plan_codex_4_0_74.md - session: L-LUPO-ROOT-JETBRAINS-CODEX - delegation: wolfie:root

# Planned Tables Install Plan (Codex, 4.0.74)

Status: planning only.  
Scope: select a practical subset (5-12 tables) for future implementation.  
No DDL is implemented in this plan.

## Baseline (validated)

- Current install table count: 146
- `future_features_lupopedia.sql` planned `CREATE TABLE` count: 48
- Cap target from provided context: 199 max including 34 legacy tables during upgrade
- Available slots from provided context: 19

## Recommended Implementation Set (11 tables)

These 11 are the best near-term value with low architectural risk and minimal doctrine conflict.

| Priority | Table | Why now | Dependency risk |
|---|---|---|---|
| P1 | `lupo_aliases` | Needed for alias/slug redirects and semantic routing cleanup. | Low |
| P1 | `lupo_legacy_content_mapping` | Critical for Crafty-to-Lupopedia URL migration continuity. | Low |
| P1 | `lupo_reference_objects` | Canonical citation objects for docs and content references. | Low |
| P1 | `lupo_reference_cited_by` | Completes reference graph from content back to sources. | Low |
| P1 | `lupo_search_index` | Enables in-DB search index without external engine dependency. | Medium |
| P1 | `lupo_documentation_frameworks` | Aligns docs governance and LUPOPEDIA HEADERS workflows. | Low |
| P2 | `lupo_federated_trust` | Extends federation model with trust/capability semantics. | Medium |
| P2 | `lupo_federation_discovery` | Supports controlled federation discovery and node bookkeeping. | Medium |
| P2 | `lupo_unified_log` | Consolidates logging patterns and limits future log-table sprawl. | Medium |
| P2 | `lupo_system_health_snapshots` | Lightweight install/schema health snapshots for diagnostics. | Low |
| P2 | `lupo_hotfix_registry` | Small operational table with high maintenance value. | Low |

## Deferred (explicitly not in this batch)

- `lupo_document_embeddings`: defer until semantic search/RAG runtime path is approved.
- `lupo_interface_translations`: useful, but can be batch 2 if no immediate i18n commitment.
- `lupo_session_recovery`, `lupo_channel_boot_log`, `lupo_registry_import`, `lupo_modules_departments`, `lupo_persona_profiles`: keep as reserved candidates.
- Actor/gov/emotional/task suites from the report remain deferred for 4.0.x unless scope changes.

## Implementation Sequence (when approved)

1. Governance approval
- Approve the 11-table set as 4.0.74/4.0.x target.
- Confirm no conflict with current migration freeze rules.

2. SQL preparation
- Port selected table DDL from `future_features_lupopedia.sql` to `install_new_lupopedia.sql`.
- Ensure doctrine compliance: no FK/trigger/procedure/view, BIGINT UTC `YmdHis`, soft-delete fields where applicable.

3. Migration planning
- Create one-time migration SQL for existing installs (non-destructive, idempotent approach).
- Keep future_features entries only for tables still deferred.

4. Documentation and TOON sync
- Regenerate TOON files.
- Update schema registry/table docs.
- Update changelog and status reports.

5. Validation
- Fresh install test.
- Crafty 3.7.5 upgrade test.
- Basic routing/search/reference smoke checks.

## Decision Gates

- Gate A: approve shortlist exactly as listed (11 tables).
- Gate B: approve phase split (P1 first, P2 second).
- Gate C: approve deferral list unchanged for this cycle.

If any gate fails, halt before SQL edits.

