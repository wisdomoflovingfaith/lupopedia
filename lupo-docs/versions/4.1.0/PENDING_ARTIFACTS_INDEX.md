---
lupopedia.headers:
  lupopedia.schema: "release_authority_index"
  file_path_from_root: "lupo-docs/versions/4.1.0/PENDING_ARTIFACTS_INDEX.md"
  last_modified_utc: "20260327"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "release_authority"
  artifact_kind: "pending_artifacts_index"
  purpose: "Review pipeline for pending 4.1.0 artifacts"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/APPROVED_ARTIFACTS_INDEX.md", type: "promotes_to", weight: 1.0 }
    - { to: "lupo-docs/versions/4.1.0/REJECTED_ARTIFACTS_INDEX.md", type: "related_to", weight: 0.8 }

lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approval_target_version: "4.1.0"
  approval_status_utc: "20260327103238"
  approval_status_by: "Cursor IDE Agent (Lead Orchestration)"
  approval_status_by_actor_id: 102
  approved_by_actor_id: 1
  approved_utc: 20260326224100
  next_action:
    - "Move entries to approved only after evidence-backed validation"
---

# Pending Artifacts (4.1.0)

Canonical inclusion rule:

- `approval_status: pending`
- `approval_target_version: 4.1.0`

| Artifact | Path | Review Status | Blocking Issue | Next Action | Estimated Review | Owner |
|----------|------|---------------|----------------|-------------|------------------|-------|
| Crafty Syntax Feature Parity Plan | lupo-docs/versions/4.0.88/CRAFTY_SYNTAX_FEATURE_PARITY_AND_IMPLEMENTATION_PLAN.md | pending | Needs alignment with 4.1.0 PRD phases; awaiting WOLFIE phase validation | Validate 10-week plan maps correctly to 4.1.0 PRD Phase 2-4; confirm Softaculous gate; log implementation start | Phase 1 | WOLFIE |
| Web Interface Requirements | lupo-docs/versions/4.1.0/prd/requirements/web_interface.md | pending | Needs stable-surface verification evidence | Run UI baseline verification | Phase 2 | WOLFIE |
| Softaculous Checklist | lupo-docs/versions/4.1.0/prd/acceptance/softaculous_checklist.md | pending | External review is manual and is the primary release signal; requires published 4.0.x package plus reviewer response | Run internal preflight, submit package to Softaculous, capture reviewer feedback, then promote | Phase 2 | WOLFIE |
| Installatron Checklist | lupo-docs/versions/4.1.0/prd/acceptance/installatron_checklist.md | pending | External review is manual and requires published 4.0.x package plus reviewer response | Run internal preflight, submit package, capture reviewer feedback, then promote | Phase 3 | WOLFIE |
| Fantastico Checklist | lupo-docs/versions/4.1.0/prd/acceptance/fantastico_checklist.md | pending | External review is manual and requires published 4.0.x package plus reviewer response | Run internal preflight, submit package, capture reviewer feedback, then promote | Phase 3 | WOLFIE |
| Identity Actor Faucet Auth System | lupo-docs/versions/4.1.0/prd/architecture/identity_actor_faucet_auth_system.md | pending | Needs runtime identity validation evidence | Run actor-resolution and permission tests | Phase 2 | WOLFIE |
| Channel Collection Context Model | lupo-docs/versions/4.1.0/prd/architecture/channel_collection_context_model.md | pending | Needs DB/filesystem projection evidence | Validate content_id projection and reconciliation | Phase 2 | WOLFIE |
| Federation Content Ingestion Model | lupo-docs/versions/4.1.0/prd/architecture/federation_content_ingestion_model.md | pending | Needs ingestion precedence and node-scope proof | Validate import + livehelp_js ingestion paths | Phase 2 | WOLFIE |
| lupopedia_js Navigation Tracking Architecture | lupo-docs/versions/4.1.0/prd/architecture/lupopedia_js_navigation_tracking_architecture.md | pending | Needs runtime implementation evidence and payload validation | Validate JS payload contract, API flow, and schema-backed execution evidence | Phase 2 | CURSOR |
| lupopedia_js Navigation Tracking Requirements | lupo-docs/versions/4.1.0/prd/requirements/lupopedia_js_navigation_tracking_requirements.md | pending | Needs execution evidence for telemetry, navigation weighting, and node-aware behavior | Map requirements to runtime evidence and promote only after verification | Phase 2 | CURSOR |
| 4.0.88 TODO | lupo-docs/versions/4.0.88/TODO.md | pending | Still tracks unresolved execution and normalization work | Complete footer/index reconciliation and release-readiness follow-up | Phase 1 | CURSOR |
| 4.0.88 What To Do Next | lupo-docs/versions/4.0.88/WHAT_TO_DO_NEXT.md | pending | Explicit next-step tracker; not a completed carryover artifact | Use as execution guide, then either resolve or retire | Phase 1 | CURSOR |
