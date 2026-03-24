---
lupopedia.headers:
  file_path_from_root: "CHANGELOG.md"
  last_modified_utc: "20260324"
  when_updated: "20260324"
  channel_id: 42
  thread_id: 1047
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "changelog"
  artifact_kind: "index_and_routing"
  purpose: "Root changelog index routing updates to version-specific directories (4.0.85+) and CHANGELOG_ARCHIVE.md (pre-4.0.85)"
  delegation_chain: "wolfie:root"
  web_path: "http://www.lupopedia.com/lupopedia/CHANGELOG.md"
lupopedia.footer:
  last_verified: "20260324"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
---

# CHANGELOG

## 📍 IMPORTANT: Version-Specific Documentation Structure

**As of version 4.0.85, Lupopedia uses a distributed changelog model:**

### 🔹 For Changes AFTER Version 4.0.85 (Current & Future)
All changes for version **4.0.85 and above** are documented in version-specific directories:
- **Current version documentation**: [`lupo-docs/versions/<version>/CHANGELOG.md`](lupo-docs/versions/4.0.87/CHANGELOG.md)
- **All structured metadata** (task registry, doctrine, contradictions, database changes): Located within each version directory
- **Example**: Version 4.0.87 changes are in [`lupo-docs/versions/4.0.87/`](lupo-docs/versions/4.0.87/)

### 🔹 For Changes BEFORE Version 4.0.85 (Historical)
All changes prior to version **4.0.85** are archived in:
- **Historical archive**: [`CHANGELOG_ARCHIVE.md`](CHANGELOG_ARCHIVE.md)
- Contains complete version history from v0.0 through v4.0.84
- This file is now read-only; all new changes follow the version-directory model

### 📖 How to Read This Document

1. **For the latest changes** → Check `lupo-docs/versions/4.0.87/CHANGELOG.md` (current version)
2. **For historical context** → Consult `CHANGELOG_ARCHIVE.md` (all versions before 4.0.85)
3. **For complete version details** → Browse the version-specific directory containing: PLAN.md, TODO.md, TASK_REGISTRY.md, CONTRADICTIONS.md, DOCTRINE.md

---

## Version 4.0.85+

For versions 4.0.85 and above, the canonical record of changes is located under:

`lupo-docs/versions/<version>/`

Structured changes are documented in `lupo-docs/versions/4.0.85/`.

See lupo-docs/versions/4.0.85/ for structured changes.

### Why this model changed
- Lupopedia change tracking is now multi-dimensional (database, doctrine, organization, structure, research, contradictions, and task state).
- System complexity increased beyond what a flat chronological changelog can represent safely.
- A single root list is no longer sufficient to preserve authority without omissions or contradictions.
- Version directories provide a structured, authoritative breakdown by artifact type and governance surface.

### How to read changes
- Start at `lupo-docs/versions/<version>/` for the version you are reviewing.
- Read category-specific documents (for example: task registry state, contradictions, database/doc updates, doctrine updates, research artifacts).
- Use this root `CHANGELOG.md` as a historical index and transition marker, not as the complete source of 4.0.85+ implementation detail.

### Authority statement
- For 4.0.85+, version-folder documentation is authoritative.
- Root-level summaries must not duplicate or override version-folder records.

## [4.0.87] - 20260324

For version 4.0.87, canonical multi-agent details are in:

- `lupo-docs/versions/4.0.87/CHANGELOG.md`
- `lupo-docs/versions/4.0.87/PLAN.md`
- `lupo-docs/versions/4.0.87/TODO.md`
- `lupo-docs/versions/4.0.87/TASK_REGISTRY.md`

### Summary
- **Junie Registration (Actor 108)**: Registered Junie as a canonical agent with root user/department.
- **LUPOPEDIA HEADERS Refactor**: Implemented the Version Semantics Triad Model and namespace distinction.
- **Single-Field Versioning Enforcement**: Validated that single-field model is enforced via three-layer validation (header structure + footer validation + database-generated snapshots). Resolved LILITH vs WOLFIE dispute (Thread 1047 Channel 66).
- **Edge Graph Activation**: Activated relationship layer (Tracks 1-3) for channels and threads.
- **Config Consolidation**: Unified all settings into root `lupopedia-config.php`.
- **Admin Chat UI**: Implemented `admin.php?section=channel-chat` with Effective Actor resolution.
- **Documentation Audit**: Reorganized 169 table artifacts and populated 22+ agents in `lupo-agents/`.

## [4.0.86] - 20260324

### Summary
- Version 4.0.86 marks the completion of the **Phase 1 Identity Model and Documentation Baseline**.
- Authoritative multi-agent history and detailed changes are documented in `lupo-docs/versions/4.0.86/`.

### Added
- **Junie Registration**: Registered **Junie** (Actor 108) as a canonical agent with root department (1) and root user (0) mapping.
- **Edge Graph Activation**: Activated the `lupo_edges` relationship layer for channels and threads (ATHENA_STRATEGY).
  - Seeded `lupo_edge_types` and `lupo_edge_type_definitions` with channel/thread vocabulary.
  - Backfilled hierarchical parent edges from `lupo_channels.parent_channel_id`.
  - Created `lupo-scripts/migrate_dialog_channel_edges.php` for JSON-to-Relational migration.
- **Verification Doctrine**: Created `lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md` for formal audit procedures.
- **Header Standards**: Implemented **Version Semantics Model** and **namespace** distinction in LUPOPEDIA HEADERS.

### Changed
- **Config Consolidation**: Unified redundant configs into a single root `lupopedia-config.php`.
- **Identity Normalization**: Standardized root `auth_user_id` to `0` and locked the Unified Identity Model doctrine.
- **Root Documentation**: Reorganized `README.md` and `AGENTS.md` to reflect the Semantic OS layer and v4.0.86 orchestration.
- **Table Documentation**: Audited and reorganized 169 table artifacts into status-based directories.

### Fixed
- Resolved contradictions in Channels 58-61 regarding agent identities and version scope.
- Corrected `web_path` construction to be subdirectory-aware across all documentation.

### Notes
- **22-Agent Requirement**: Initialized complete documentation structure for 22+ agents in `lupo-agents/`.
- Canonical history for 4.0.86 is in `lupo-docs/versions/4.0.86/CHANGELOG.md` and `TASK_REGISTRY.md`.

## [4.0.85] - 20260322
- Transition point: 4.0.85 introduces the version-directory governance model.
- Canonical details for 4.0.85 are maintained under `lupo-docs/versions/4.0.85/`.
- 4.0.85 final state: INSTALL READY + SYSTEM COMPLIANT.

