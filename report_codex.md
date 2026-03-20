---
lupopedia.headers:
  lupopedia.schema: "audit_report"
  file_path_from_root: "report_codex.md"
  version_when_written: "4.0.84"
  last_modified_utc: "20260314"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "jetbrains_codex"
  delegation_chain: "wolfie:root"
  artifact_type: "report"
  artifact_kind: "findings"
  purpose: "Codex audit findings including concurrency incident and naming policy"
lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  last_verified_by: "codex"
  orchestrator: "wolfie"
  next_action:
    - "Use *_codex file stream until orchestrator approves merge"
---
# Codex Findings Report (2026-03-14)

## Concurrency Incident Note

During this session, concurrent edits from multiple IDE agents on the same machine caused observed post-write drift in shared files.

Resolution for this session:
- Codex now writes to `*_codex.md` files only.
- No direct overwrite of shared canonical files unless explicitly directed.

This rule is now active for this Codex pass to prevent cross-agent collisions.

## Evidence Snapshot

- Repository file count observed: 11,376
- `CREATE TABLE` count in install SQL observed: 140
- Core runtime flow validated from:
  - `index.php`
  - `lupo-includes/bootstrap.php`
  - `lupo-includes/lupopedia-loader.php`
  - `lupo-includes/modules/module-loader.php`

## Key Findings

1. Path drift exists (`lupo-docs/*` references vs `lupo-docs/*` structure).
2. Identity references drift across registry, seed SQL, and doctrine examples.
3. Header doctrine and legacy tooling are not fully converged (`lupopedia.*` vs `flare.*`).

## Codex Outputs (collision-safe)

- `README_codex.md`
- `CHANGELOG_codex.md`
- `plan_codex.md`
- `report_codex.md` (this file)
