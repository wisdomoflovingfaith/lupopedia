---
lupopedia.headers:
  version_when_written: "4.0.87"
  file_path_from_root: "lupo-channels/63/threads/database_documentation/20260324_121500_documentation_cleanup_plan.md"
  questions_toon: null
  channel_id: 63
  thread_id: "database_documentation"
  actor_id: 108
  actor_name: "junie"
  artifact_type: "channel_artifact"
  artifact_kind: "planning"
  purpose: "Consolidated plan for documentation cleanup in lupo-docs and database table accuracy."
---

# Documentation Cleanup and Accuracy Plan (Junie)

## Objective
Address the "mess of documentation" in `lupo-docs` by establishing a systematic cleanup process, verifying accuracy against TOON/SQL sources, and formalizing the update channel (Channel 63).

## Current Findings
1. **Messy Structure:** Multiple `README` variants at root (`README_OLD.md`, `README_UPDATED.md`, `README_kiro.md`, etc.).
2. **Outdated Scripts:** `lupo-scripts/check_doc_schema_consistency.py` fails on `lupo_sessions` (claims `faucet_slug` is required, but it's not in TOON).
3. **Status-Based Table Docs:** Table docs are already being moved to `active/`, `in_development/`, and `planning/` directories, but need verification.
4. **Channel Readiness:** Channel 63 exists with a charter for database documentation accuracy.

## Implementation Steps

### 1. Formalize the Cleanup Channel
- All documentation cleanup work will be logged in Channel 63 (Database Documentation).
- Specific folder/directory organization issues will be cross-referenced with Channel 62.

### 2. Identify and Deprecate "Mess"
- List all redundant or deprecated root-level documentation.
- Mark as `@deprecated` or move to a `deprecated/` or `archived/` folder.
- Priority: Root `README` variants and `lupo-docs/` legacy files.

### 3. Verify Database Table Accuracy
- Create a Python script `lupo-scripts/validate_table_docs.py` to:
    - Parse all table `.md` files in `lupo-docs/database/lupopedia/tables/`.
    - Compare listed columns and types against the canonical `.toon` files.
    - Generate a `VALIDATION_REPORT.md`.

### 4. Establish Documentation Status Registry
- Create `lupo-docs/STATUS_REGISTRY.md` to track the verification status of all major documentation files.
- Statuses: `VERIFIED`, `STALE`, `DEPRECATED`, `NEEDS_REVIEW`.

### 5. Execute Pilot Cleanup
- Consolidate root `README` variants into the canonical `README.md`.
- Verify the `Identity Core` table docs (`lupo_actors`, `lupo_agents`, etc.).

## Verification Plan
- Successful run of `lupo-scripts/validate_table_docs.py` with zero mismatches for `active/` tables.
- All identified "mess" files removed or archived.
- Root `README.md` is the single source of truth for the project overview.
