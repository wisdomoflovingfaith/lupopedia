---
lupopedia.headers:
  lupopedia.schema: "requirements"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/requirements/lupopedia_js_navigation_tracking_requirements.md"
  last_modified_utc: "20260327"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "feature_requirements"
  purpose: "Normative requirements for lupopedia_js.php navigation intelligence and tracking"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/versions/4.1.0/prd/architecture/lupopedia_js_navigation_tracking_architecture.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/versions/4.1.0/prd/acceptance/softaculous_checklist.md", type: "implements", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_contents.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_visits.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_paths.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_edges.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_truth_knowledge.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_truth_answers.toon", type: "references", weight: 1.0 }

lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260327"
  last_verified_by: "cursor"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "pending"
  approved_by_actor_id: 0
  approved_utc: 0
  next_action:
    - "Use these requirements as implementation acceptance criteria"
    - "Map each requirement to test evidence in changelog and checklist"
    - "Promote to approved only after runtime verification"
---

# Requirements: lupopedia_js.php Navigation and Tracking (4.1.0)

## 1. System Overview Requirements

1. The system MUST provide `lupopedia_js.php` as an embeddable JS generator endpoint.
2. The endpoint MUST preserve Crafty-style embeddability while extending navigation, knowledge, and social tracking.
3. The system MUST operate without preloaded host page inventory.

## 2. Architecture Requirements

1. The generation path MUST be `PHP -> JavaScript payload`.
2. The telemetry path MUST be `JavaScript -> API/image endpoint -> database writes`.
3. All writes MUST use canonical DB wrappers and doctrine-compliant timestamps.

## 3. UI Requirements

1. A floating bottom navigation bar MUST be injectable from generated JS.
2. The bar MUST expose previous, next, related, knowledge, context, and social controls.
3. UI behavior MUST remain non-blocking and degrade gracefully if APIs are unavailable.

## 4. Data Collection Requirements

1. Page visits MUST be captured in `lupo_visits`.
2. Page transitions MUST be aggregated in `lupo_paths`.
3. Content identity MUST resolve through `lupo_contents`.
4. Behavior-derived relationships MUST persist to `lupo_edges` with clear edge type/category.

## 5. Navigation Intelligence Requirements

1. Previous pages MUST be computed from inbound transitions.
2. Next pages MUST be computed from outbound transitions.
3. Ranking MUST include frequency and recency weighting.
4. Ranking tie-breaks MUST be deterministic.

## 6. Knowledge Integration Requirements

1. Questions and knowledge entities MUST come from `lupo_truth_knowledge`.
2. Answers MUST come from `lupo_truth_answers`.
3. Contexts MUST be derived from collections and mapping tables.
4. Hashtags MUST come from canonical hashtag tables or content hashtag JSON when normalized rows are absent.

## 7. Social Features Requirements

1. Like events MUST be tracked and reflected in `lupo_contents.like_count`.
2. Share events MUST be tracked and reflected in `lupo_contents.share_count`.
3. Event attribution MUST be preserved through existing actor/session identity paths.

## 8. Constraints

1. No CMS-specific integration requirement is allowed.
2. No static site map import is required for operation.
3. All intelligence MUST be inferred from observed behavior and persisted data.
4. Subdirectory install behavior MUST remain correct for dynamic install folder names.

## 8.1 Federation Node Model Requirements (REQUIRED)

1. The system MUST explicitly support node 0, node 1, and node 2+ deployment roles.
2. The system MUST treat node 0 incompleteness as tolerated transitional state.
3. The system MUST continue local-node operation even when node 0 references are unresolved.
4. Cross-node behavior MUST be optional and additive, not a prerequisite for local tracking.

## 8.2 Deployment Reality Requirements (REQUIRED)

1. The PRD baseline MUST acknowledge VPS/FTP/manual-update transitional deployment workflows.
2. Missing or broken node 0 links MUST be handled as expected transitional condition.
3. `lupopedia_js.php` MUST not fail hard solely due to missing cross-node endpoints.

## 8.3 federation_node_id Requirements (REQUIRED)

1. Applicable content, visit, path, and edge writes MUST include node attribution via `federation_node_id`.
2. Read queries for navigation intelligence MUST remain node-aware by default.
3. Cross-node aggregation MUST be explicit and never implicit.

## 9. Non-Goals

1. No manual page registry workflow as a prerequisite.
2. No host CMS deep integration requirement.
3. No speculative graph construction from unobserved URLs.

## 10. Execution Evidence Required

1. Payload example from `lupopedia_js.php` proving embeddable output.
2. Telemetry trace showing visit and transition writes.
3. Navigation API response example with weighted previous/next links.
4. Knowledge panel response example linked to truth tables.
5. Like/share event trace with counter update evidence.
6. Evidence for node 0 incomplete-state tolerance (no hard failure).
7. Evidence for node-local tracking correctness on node 1.
8. Evidence for future-ready node 2+ compatibility boundaries.
