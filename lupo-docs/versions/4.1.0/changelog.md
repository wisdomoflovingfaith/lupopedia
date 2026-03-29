---
lupopedia.headers:
  lupopedia.schema: "release_changelog"
  file_path_from_root: "lupo-docs/versions/4.1.0/changelog.md"
  last_modified_utc: "20260327220000"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "changelog"
  artifact_kind: "release_change_log"
  purpose: "Track accepted changes for 4.1.0"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260327220000"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approved_by_actor_id: 1
  approved_utc: "20260326223600"
  next_action:
    - "Append evidence-backed entries only"
---

# 4.1.0 Changelog

## 20260327

### Auth User to Actor Mapping Implementation

- **AuthSessionManager** - Created comprehensive session management class for auth_user → actor mapping
- **AuthService** - Updated to handle login workflow and actor resolution
- **select_agent.php** - Created agent selection interface for first-time users
- **login.php** - Updated login page with new auth workflow
- **logout.php** - Created logout handler
- **test_auth_workflow.php** - Created test script for workflow verification

### PHP 5.6+ Compatibility & Doctrine Enforcement

- **PHP Version Compatibility Doctrine** - Created comprehensive PHP 5.6+ compatibility rules
- **No Composer Doctrine** - Updated to clarify relationship with external libraries
- **No Framework Doctrine** - Created doctrine forbidding Laravel/Symfony frameworks
- **Shared Hosting Doctrine** - Created shared hosting compatibility requirements
- **External Libraries Doctrine** - Created doctrine permitting self-contained libraries

### PHPMailer Integration & Compliance

- **PHPMailer Compliance Report** - Verified PHPMailer as self-contained and compliant
- **EmailService Wrapper** - Created production-ready email service using PHPMailer
- **PHP 5.6 Polyfills** - Created polyfills for `random_bytes()`, `random_int()`, etc.

### Laravel/Composer Violation Audit & Remediation

- **Violation Detection** - Found 2 Laravel Blade template files requiring conversion
- **Violation Report** - Created comprehensive report of all violations found
- **Doctrine Updates** - Updated all root doctrines to enforce PHP 5.6+ compatibility
- **Template Conversion Plan** - Established plan to convert Blade templates to pure PHP

### Root Rules Documentation Overhaul

- **Root Rules README** - Completely rewrote with comprehensive index of all 29+ rules
- **Main README Updates** - Added development rules section and quick checklist
- **ONBOARDING Updates** - Added comprehensive development constraints section
- **Rules Summary** - Created comprehensive status document with all rules explained

### Database Schema Verification

- **Schema Compliance** - Verified all database tables follow constitutional rules
- **Actor Mapping Tables** - Confirmed `lupo_actor_auth_users` mapping table structure
- **Session Table** - Verified `lupo_sessions` table structure for new workflow
- **ID Allocation** - Confirmed registry-based ID generation system

### Documentation Standards

- **LUPOPEDIA Headers** - Enforced v4.0.84+ header format across all new files
- **Channel-Based Coordination** - All work documented in channel 42 threads
- **Edges Declaration** - Added proper outbound edges for all artifacts
- **Status Documentation** - Created comprehensive status documentation in `lupo-docs/status/`

## 20260326

### Governance Reset

- Created authoritative 4.1.0 PRD directory and required structure.
- Introduced release-approval metadata fields in `lupopedia.footer`:
  - `approved_for_release`
  - `approval_status`
  - `approved_by_actor_id`
  - `approved_utc`
- Established policy: only approved 4.1.0 artifacts are release-binding.

### Initial Approved Artifact Set

- `lupo-docs/versions/4.1.0/prd/README.md`
- `lupo-docs/versions/4.1.0/prd/product_overview.md`
- `lupo-docs/versions/4.1.0/prd/requirements/installer_requirements.md`
- `lupo-docs/versions/4.1.0/prd/constraints/auto_installer_constraints.md`
- `lupo-docs/versions/4.1.0/plan.md`
- `lupo-docs/versions/4.1.0/todo.md`
- `lupo-docs/versions/4.1.0/changelog.md`

### Identity and Context Architecture Additions

- Added `lupo-docs/versions/4.1.0/prd/architecture/identity_actor_faucet_auth_system.md`.
- Added `lupo-docs/versions/4.1.0/prd/architecture/channel_collection_context_model.md`.
- Added `lupo-docs/versions/4.1.0/prd/architecture/federation_content_ingestion_model.md`.
- Updated pending index, plan, and todo to track validation and implementation evidence for these models.

### 4.0.x Artifact Handling

- Performed baseline scan of 4.0.x version docs for signal/noise separation.
- Did not delete 4.0.x artifacts.
- Classified 4.0.x materials as non-binding for 4.1.0 until explicitly approved.

### Softaculous-First External Gate

- Updated 4.1.0 governance language to make Softaculous the primary external approval signal.
- Added `lupo-docs/versions/4.1.0/prd/acceptance/softaculous_checklist.md` as release-gate artifact.
- Updated plan and todo execution flow to run Softaculous review first, then Installatron/Fantastico confirmations.
- Updated pending artifacts index and acceptance checklists to reflect manual external review dependency and sequencing.

### Phase 1 Execution Update

- Promoted Phase 1 artifacts to approved:
  - `lupo-docs/versions/4.1.0/prd/requirements/core_system.md`
  - `lupo-docs/versions/4.1.0/prd/architecture/system_architecture.md`
  - `lupo-docs/versions/4.1.0/prd/architecture/deployment_model.md`
  - `lupo-docs/versions/4.1.0/prd/constraints/hosting_constraints.md`
- Added evidence snapshots and verification matrix entries for phase-1 approvals.

### THOTH Database Remediation Update

- Removed `AUTO_INCREMENT` definitions from `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` for doctrine alignment in 4.1.0 release model.
- Added deterministic ID allocator service: `lupo-includes/classes/DeterministicIdService.php`.
- Re-ran strict doctrine scan on install SQL with pass evidence:
  - `FOREIGN KEY`: 0
  - `CREATE TRIGGER`: 0
  - `AUTO_INCREMENT`: 0
  - `DATETIME`: 0
  - `TIMESTAMP`: 0
  - `UNSIGNED`: 0
- Promoted `lupo-docs/versions/4.1.0/prd/requirements/database_constraints.md` to approved.
