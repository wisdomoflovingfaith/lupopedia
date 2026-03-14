---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "validation_report"
  system_version: "4.0.74"
  file_path_from_root: "lupo-docs/status/implementation_plan_by_antigravity.md"
  web_path: "http://www.lupopedia.com/status/implementation_plan_by_antigravity"
  last_modified_utc: "20260314"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 103
  actor_name: "antigravity"
  faucet_name: "antigravity"
  delegation_chain: "cursor:root"
  artifact_type: "plan"
  artifact_kind: "schema"
  purpose: "Review and prioritize planned tables from future_features_lupopedia.sql for promotion to main install."
  traits: ["schema", "planning", "anubis", "metadata", "v4.0.74"]
  tags: ["schema", "install", "future_features", "anubis", "metadata"]

lupopedia.edges:
  comment: "Snapshot of schema implementation planning dependencies."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "references", weight: 1.0 }
    - { to: "lupo-docs/status/PLANNED_TABLES_NOT_CREATED_REPORT.md", type: "references", weight: 0.95 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS", type: "implements", weight: 0.9 }
    - { to: "lupo-database/lupopedia/toon/", type: "targets", weight: 0.9 }

lupopedia.init:
  required_reading:
    - path: "lupo-docs/status/PLANNED_TABLES_NOT_CREATED_REPORT.md"
      reason: "Provides the canonical list of the 55 deferred tables."
  required_context:
    - "This file outlines which dormant tables should be consolidated or activated into the main install SQL."

lupopedia.next_actions:
  next_actions:
    - "Execute modifications to install_new_lupopedia.sql per Phase 1 decisions."
    - "Regenerate TOON files for newly promoted tables."
    - "Update SCHEMA_REGISTRY and table documentation files to active status."
---

# Planned Tables Implementation Plan (by Antigravity)

**Date:** 2026-03-14
**Author:** Antigravity (faucet 103)
**Scope:** Review the 55 tables currently parked in \uture_features_lupopedia.sql\ and outline a concrete plan for promotion, consolidation, and refactoring to support the current Lupopedia 4.0.74 architecture.

## Executive Priorities

Based on current architectural needs, the following table migrations and modifications take precedence:

1. **Promote Core Operational Scopes**: Orchestrator Rules, Comments, and Hashtags must be promoted to the main install immediately.
2. **Consolidate ANUBIS Tracking**: Replace the 4 fragmented ANUBIS logging tables with a unified operational log.
3. **Deprecate FLARE Headers**: Formally drop \lupo_flare_headers\ in favor of extending \lupo_metadata\ and the new filesystem doctrine. 

---

## Phase 1: Direct Promotion (Activate Immediately)

The following tables represent finalized features that are required for the continued multi-agent orchestration of the Lupopedia OS. They will be **moved out** of \uture_features_lupopedia.sql\ and **inserted** into \install_new_lupopedia.sql\.

### 1. \lupo_comments\
* **Status:** Finalized. 
* **Action:** Move DDL to \install_new_lupopedia.sql\. Ensure indices for \ctor_id\, \	arget_table\, \	arget_id\, \parent_comment_id\, and \channel_id\ are preserved. The \comments\ block is already formally added to \LUPOPEDIA HEADERS\.

### 2. \lupo_hashtags\
* **Status:** Stable.
* **Action:** Move DDL to main install. The table structure is simple and aligns with semantic routing needs.

### 3. \lupo_orchestrator_rules\
* **Status:** Critical Priority (from Audit).
* **Action:** Move DDL to main install. This provides the Database canonical storage for the UI agent rules currently living entirely in \lupo-rules/root/*.md\. 

---

## Phase 2: Consolidation & Normalization

Several feature groups in \uture_features\ are artificially fragmented and violate the goal of table reduction. They must be merged *before* promotion.

### 4. Unified ANUBIS Audit

Currently, \uture_features\ lists 4 ANUBIS tables:
- \lupo_anubis_deletion_log\
- \lupo_anubis_mirrored\
- \lupo_anubis_orphaned\
- \lupo_anubis_revised\

**Action:** Do not promote these four separate tables. Instead, verify if they can be incorporated into \lupo_anubis_log\ or \lupo_unified_log\ (which is also parked in future_features). 

**Decision:** Create a single \lupo_anubis_operations\ table (or definitively extend \lupo_unified_log\ by adding an \operation_context\ JSON column) and **drop the 4 fragmented tables** from the planned roadmap entirely.

### 5. Unified Temporal/System Snapshots

Currently, there are tracking tables for point-in-time schema validation:
- \lupo_temporal_coherence_snapshots\
- \lupo_system_health_snapshots\

**Action:** Standardize under one table (e.g. \lupo_system_health_snapshots\) utilizing JSON blocks to track arbitrary state metrics (coherence vs table ceilings) and drop the redundant tables. 

---

## Phase 3: Metadata Refactoring & FLARE Deprecation

### 6. Deprecation of \lupo_flare_headers\

* **Context:** The \uture_features\ schema includes a \lupo_flare_headers\ table designed to track the legacy \FLARE\/\FLIP\ metadata formats.
* **Action:** **DROP** this table from \uture_features_lupopedia.sql\. It is entirely obsolete. The new doctrine establishes that \.md\ headers represent file-based *snapshots* of \lupo_edges\ and \lupo_metadata\ rows.

### 7. Auditing \lupo_metadata\

* **Action Required:** \lupo_metadata\ currently serves as the bridge for file metadata. We must review \install_new_lupopedia.sql\ to ensure \lupo_metadata\ natively supports the key structures required by the \lupopedia.headers\ standard. Specifically:
   - Does it robustly index by \schema_ref\, \entity_type\, and \property_value\?
   - Ensure the JSON/metadata serialization patterns used in header blocks can reliably map 1:1 into this overarching \metadata\ table. We may need to add an \rtifact_schema\ column if it is missing.

---

## Conclusion & Next Steps

1. Initiate \install_new_lupopedia.sql\ refactor to include \lupo_comments\, \lupo_hashtags\, and \lupo_orchestrator_rules\.
2. Delete \lupo_flare_headers\ DDL.
3. Consolidate the 4 ANUBIS tables into a singular log definition. 
4. After updating the DDL, run \python lupo-scripts/generate_toon_files.py\ to bring the YAML schemas natively into the \lupo-database/lupopedia/toon\ folder.
