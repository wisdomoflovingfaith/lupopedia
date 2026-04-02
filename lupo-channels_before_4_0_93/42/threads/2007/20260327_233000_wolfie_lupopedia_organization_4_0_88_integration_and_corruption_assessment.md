---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-channels/42/threads/2007/20260327_233000_wolfie_lupopedia_organization_4_0_88_integration_and_corruption_assessment.md
  last_modified_utc: '20260327233000'
  channel_id: 42
  thread_id: 2007
  actor_id: 1
  actor_name: wolfie
  artifact_type: thread
  artifact_kind: discussion
  purpose: Canonical thread integration for 4.0.88 organization pass, contradictions tracking, and corruption incident assessment
  namespace: documentation
  tags:
  - organization
  - corruption
  - mysql
  - filesystem
  - 4.0.88
  - channel_42
  - thread_2007
lupopedia.footer:
  last_verified: '20260327233000'
  verified_by:
    identity_type: actor
    actor_id: 1
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: wolfie:root
  next_action:
  - keep this thread as canonical organization and corruption coordination surface
  - record remediation decisions here before bulk file repair
---

# Lupopedia Organization and Repository Structure (4.0.88)

## 1. Opening (WOLFIE Orchestration)

This artifact declares Thread 2007 as the canonical coordination surface for repository organization work in 4.0.88.

This thread now integrates:

1. Completed 4.0.88 documentation organization findings.
2. Active gap and contradiction tracking.
3. Corruption incident assessment and response planning.

## 2. Imported - 4.0.88 Organization Pass

Imported source:

- lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_PASS_REPORT.md

Research scope completed in that pass:

- full root and lupo-* directory review
- root orientation and docs structure review
- database install/TOON/JSON/session surfaces
- channel and thread artifact surfaces
- runtime and installer path verification
- script-level import/sync behavior review

Files updated/created by pass:

- ORGANIZATION.md
- lupo-docs/ORGANIZATION.md
- README.md
- lupo-docs/versions/4.0.88/README.md
- lupo-docs/versions/4.0.88/CHANGELOG.md
- lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_PASS_REPORT.md
- lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md

Key clarifications imported:

1. lupo-* directory roles are explicitly classified (canonical, operational, generated, coordination, legacy, transitional).
2. MySQL authority is centered on lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql.
3. TOON and JSON artifacts are derived snapshots, not primary schema authority.
4. File-based coordination is active and operational (lupo-channels, lupo-database/sessions, lupo-sessions).
5. DB vs filesystem boundaries are explicit for contributors and IDE agents.

## 3. Imported - Gap and Contradiction Findings

Imported source:

- lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md

Imported contradiction highlights:

- stale directory doctrine still describes pre-lupo-* path model
- stale FLARE-era assumptions in several docs
- channel docs with outdated metadata/header models
- stale thread index/header patterns in channel docs

Imported structural gaps:

1. lupo-database/lupopedia/mysql/manifest lacks explanatory README.
2. lupo-database/lupopedia/postgres is empty and undocumented.
3. lupo-context exists but top subdirs are currently empty/incomplete.
4. channel index and creation doctrine need normalization to current behavior.

## 4. Active Question - ATHENA (Validation Blocker)

ATHENA:
"need your direction before I continue.

While validating this pass, I found a very large set of unrelated, unexpected modifications already present in the working tree, mostly under active, with signs of encoding/header corruption (for example injected literal `n sequences, BOM insertions, and mojibake characters). I did not make those changes.

How would you like to proceed?"

This question is now formally tracked in this thread as an active blocker item.

## 5. Corruption / Unexpected Modification Assessment

### A. Scope

Primary affected directory:

- lupo-docs/database/lupopedia/tables/active/

Observed scope from repository evidence:

- approximately 76 files currently modified in that directory and showing corruption signatures
- impact appears batch-like and concentrated in active table documentation files

### B. Types of Corruption Observed

1. Literal `n sequences injected into header/footer blocks (for example: lupopedia.footer:`n ...).
2. UTF-8 BOM prefix insertion at file start in many markdown files.
3. Mojibake artifacts (for example: â€", â†’, ðŸ, ï¿½, âœ…).
4. Header boundary breakage and merged header/footer text patterns.

### C. Specific Examples

- lupo-docs/database/lupopedia/tables/active/lupo_actor_events.md
- lupo-docs/database/lupopedia/tables/active/lupo_actor_handshakes.md
- lupo-docs/database/lupopedia/tables/active/lupo_edges.md
- lupo-docs/database/lupopedia/tables/active/lupo_decisions.md
- lupo-docs/database/lupopedia/tables/active/lupo_decision_evidence.md
- lupo-docs/database/lupopedia/tables/active/lupo_departments.md
- lupo-docs/database/lupopedia/tables/active/lupo_projects.md
- lupo-docs/database/lupopedia/tables/active/lupo_metadata.md
- lupo-docs/database/lupopedia/tables/active/lupo_dialog_channels.md
- lupo-docs/database/lupopedia/tables/active/lupo_dialog_threads.md
- lupo-docs/database/lupopedia/tables/active/lupo_api_tokens.md
- lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md

### D. Impact Analysis

1. Header integrity risk: YAML parsing may fail where header/footer boundaries are malformed.
2. Validator breakage risk: LUPOPEDIA_HEADERS and metadata ingest tooling can fail or misread fields.
3. Documentation readability impact: mojibake degrades table docs and semantic clarity.
4. DB-to-file mismatch risk: corrupted table docs can diverge from installer SQL and TOON-derived truth.

### E. Likely Origin (Evidence-Limited)

Evidence supports a bulk automated write pattern, but source process attribution is not proven.

Origin status:

- UNKNOWN

### F. Severity Classification

Severity:

- CRITICAL

Reason:

- corruption affects doctrinal headers and table documentation integrity at scale
- this can break validation, automation, and reader trust in canonical docs

## 6. System Impact

Corruption affects:

1. Table docs: canonical readability and trust of active table documentation.
2. Header doctrine: malformed header/footer shapes can violate LUPOPEDIA_HEADERS expectations.
3. Regeneration logic: tooling that expects parseable headers can fail or produce bad metadata.
4. IDE agent file consumption: automated agents may ingest damaged metadata and propagate errors.

## 7. Relationship to Organization Work

The 4.0.88 organization pass surfaced this issue during validation.

This incident is directly tied to stabilization goals because:

- organization clarity depends on trustworthy documentation surfaces
- gap closure requires clean doctrine-compliant headers
- future organization updates need repeatable validation to prevent recurrence

## 8. lupo-context (New Area)

Current status from gap analysis:

- lupo-context exists as a structural surface but remains incomplete/not implemented as a mature operational subsystem

Follow-up requirement:

- define ownership, intended data model, and activation criteria in a dedicated follow-up artifact

## 9. Open Questions

1. Should corrupted files be restored from last known good state or regenerated from canonical sources?
2. Should LUPOPEDIA headers for affected files be regenerated from database metadata tooling?
3. Which process/tool introduced the corruption batch?
4. Should header/doc integrity validation be mandatory in pre-commit or CI?

## 10. Next Actions (Combined)

1. Rewrite stale database docs identified in the gap report.
2. Normalize channel index and thread index documents.
3. Document mysql/manifest intent with a README.
4. Perform full corrupted-file inventory with deterministic remediation plan.
5. Decide restore vs regenerate strategy before any mass edits.
6. Add automated validation checks for BOM/literal-escape/mojibake/header-shape failures.

## 11. Linked Artifacts

- ORGANIZATION.md
- lupo-docs/ORGANIZATION.md
- README.md
- lupo-docs/versions/4.0.88/README.md
- lupo-docs/versions/4.0.88/CHANGELOG.md
- lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_PASS_REPORT.md
- lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md

## 12. Canonical Thread Decision

Thread 2007 is now the canonical organization thread for:

1. 4.0.88 organization clarity work.
2. Documentation gap and contradiction tracking.
3. Corruption incident response coordination.
