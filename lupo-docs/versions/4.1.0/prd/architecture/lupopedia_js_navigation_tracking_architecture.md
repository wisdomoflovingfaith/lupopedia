---
lupopedia.headers:
  lupopedia.schema: "architecture"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/architecture/lupopedia_js_navigation_tracking_architecture.md"
  last_modified_utc: "20260327"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "execution_architecture"
  purpose: "Implementation architecture for lupopedia_js.php navigation, tracking, and knowledge system"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/versions/4.1.0/prd/requirements/lupopedia_js_navigation_tracking_requirements.md", type: "references", weight: 1.0 }
    - { to: "livehelp_js.php", type: "references", weight: 1.0 }
    - { to: "lupo-archive/legacy/craftysyntax-3.7.5/livehelp_js.php", type: "references", weight: 1.0 }
    - { to: "image.php", type: "references", weight: 1.0 }
    - { to: "lupo-includes/modules/api/semantic-navbar-api.php", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_contents.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_visits.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_paths.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_edges.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_truth_knowledge.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_truth_answers.toon", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/migrations/", type: "references", weight: 0.9 }

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
    - "Align implementation tasks with requirements and acceptance checklist"
    - "Validate runtime payloads against real table columns before coding"
    - "Add tests for fresh-install and upgrade paths"
---

# lupopedia_js.php Navigation, Tracking, and Knowledge Architecture (4.1.0)

## 1. System Overview

`lupopedia_js.php` is the missing frontend-generation system required to close Softaculous feedback on navigation intelligence, interaction tracking, and knowledge integration.

It extends the Crafty-pattern generator model (`livehelp_js.php`) into a graph-aware and knowledge-aware runtime while preserving embeddability.

## 1.1 Federation Node Model (REQUIRED)

Execution model by node:

1. Federation Node 0:
  - canonical target hub at `lupopedia.com/lupopedia`
  - not fully deployed/live at current state
  - may contain incomplete data and broken references
2. Federation Node 1:
  - active working install where `lupopedia_js.php` runs now
  - primary node for local event capture and graph growth
3. Federation Nodes 2+:
  - additional external Lupopedia domains
  - future participants for cross-node edge and data exchange

Cross-node behavior target:

1. local first, federation later,
2. no hard runtime dependency on node 0 completeness,
3. deterministic node attribution via `federation_node_id`.

## 1.2 Deployment Reality (REQUIRED)

Current operational reality:

1. Node 0 has been deployed through VPS + FTP + manual DB updates.
2. Node 0 is transitional and not fully complete.
3. Broken links or unresolved node 0 references can exist during this phase.
4. This is expected transitional state before 4.1.0 completion, not standalone bug evidence.

Softaculous gating reality:

1. Softaculous approval is manual/external.
2. 4.1.0 should not be treated as release-complete until the 4.0.x approval path is accepted.

## 1.3 System Constraints (REQUIRED)

`lupopedia_js.php` architecture must:

1. function with incomplete federation graph,
2. tolerate unresolved references and partial upstream metadata,
3. keep local tracking and navigation intelligence operational,
4. avoid assumptions of globally synchronized node state,
5. support incremental reconciliation when federation links mature.

## 2. Architecture

### 2.1 PHP to JS Output

1. Endpoint: `lupopedia_js.php` returns JavaScript with `Content-Type: application/javascript`.
2. Script is embeddable on any page, independent of CMS.
3. Endpoint resolves install-safe base paths from runtime constants and does not assume known parent-site structure.

### 2.2 JS to API to DB

1. Runtime JS captures page context (`location`, `referrer`, title, path).
2. JS emits telemetry events to server APIs.
3. Server writes canonical rows using existing DB wrappers and doctrine-safe timestamps.
4. Navbar panel fetches semantic slices via API and renders a floating bottom bar.
5. Applicable write paths must include `federation_node_id` to preserve per-node attribution.

### 2.3 Fresh Install and Upgrade Compatibility

1. Fresh installs depend on `install_new_lupopedia.sql` contract.
2. Upgrade installs depend on `import_from_old_crafty_syntax.sql` mapping continuity.
3. Behavior must work even when pages were not pre-registered in advance.

## 3. UI Specification

Floating bottom bar sections:

1. Previous pages: top inbound paths.
2. Next pages: top outbound paths.
3. Related pages: edge-based relations from `lupo_edges`.
4. Referencing pages: inbound edge view where current page is right object.
5. Knowledge: linked questions and answer previews.
6. Context: collections and hashtags.
7. Social: like and share actions with immediate count updates.

Interaction behavior:

1. Initial collapsed state with explicit user expand action.
2. Lazy-load detail panes to avoid page-load blocking.
3. All requests are non-blocking and safe on pages outside Lupopedia routing.

## 4. Data Collection

Primary write model:

1. `lupo_visits`: append raw page events and transition metadata.
2. `lupo_paths`: aggregate directional transitions (`entercontentid` to `exitcontentid`) with frequency counts.
3. `lupo_contents`: maintain discovered page identity and engagement counters.
4. `lupo_edges`: persist semantic and navigation relationships.

Event taxonomy (initial):

1. `page_view`
2. `nav_transition`
3. `social_like`
4. `social_share`
5. `knowledge_open`

## 5. Navigation Intelligence

Previous/next ranking is produced from `lupo_paths`:

1. Directional filter by current `content_id`.
2. Score by frequency (`count_num`) and recency (`updated_ymdhis`).
3. Tie-break by deterministic ID order to keep responses stable.
4. Optional filtering by transition type if signal quality is low.

## 6. Knowledge Integration

Knowledge surfaces are pulled from:

1. `lupo_truth_knowledge` for question/topic/entity scope.
2. `lupo_truth_answers` for answer snippets and confidence fields.
3. `lupo_collections` plus mapping tables for context grouping.
4. `lupo_hashtags` and `lupo_hashtag_map` for tags.

Mapping policy:

1. Page to question links use existing object identity (`content_id`) and object-type mapping fields.
2. No synthetic question registry outside canonical truth tables.

## 7. Social Features

Like/share requirements for 4.1.0 execution:

1. Like/share interactions must emit auditable events.
2. Counters persist in `lupo_contents` (`like_count`, `share_count`).
3. Optional actor caches use `like_users` and `share_users` JSON fields.
4. No new social table is mandatory for 4.1.0 if event traceability is preserved in existing event paths.

## 8. Constraints

1. No host-site sitemap assumption.
2. No hard dependency on WordPress/Drupal/Joomla internals.
3. Infer navigation graph strictly from observed transitions and stored records.
4. Respect install-path variability (folder name is dynamic).
5. Use TOON + install SQL as schema authority.

## 9. Non-Goals

1. No manual full-site page registry.
2. No crawler that creates non-observed content records.
3. No CMS plugin lock-in.
4. No schema-first redesign outside 4.1.0 release scope.

## 10. Implementation Plan (Execution)

1. Add endpoint skeleton for `lupopedia_js.php` with parity-safe bootstrap behavior.
2. Define JS payload contract and event verbs.
3. Extend ingestion handlers to process new verbs into `lupo_visits` and path aggregation.
4. Wire navbar API queries for previous/next/edges/knowledge/context/social counters.
5. Add acceptance tests for fresh install and Crafty upgrade installs.
6. Run Softaculous checklist evidence capture for this feature.
7. Validate node 0/node 1/node 2+ behavior under partial federation availability.
8. Add deployment-reality tests for broken/missing cross-node references.

## 11. lupopedia_js.php Federation Behavior (REQUIRED)

1. Track page views locally on active node (typically node 1).
2. Build edges locally from observed transitions.
3. Tag applicable records with `federation_node_id`.
4. Resolve knowledge/context from local node first, then optional remote federation links.
5. Degrade gracefully when node 0 links are unavailable.
6. Support future federation merge/share without changing local ingestion contract.
