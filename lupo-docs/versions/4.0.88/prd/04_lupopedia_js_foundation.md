---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "prd"
  file_path_from_root: "lupo-docs/versions/4.0.88/prd/04_lupopedia_js_foundation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/prd/04_lupopedia_js_foundation.md"
  last_modified_utc: "20260327"
  system_version: "4.0.88"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "foundation_spec"
  purpose: "Foundation PRD for missing lupopedia_js.php system required by Softaculous feedback"
  tags: ["4.0.88", "prd", "lupopedia_js", "tracking", "navigation_intelligence", "softaculous"]

lupopedia.edges:
  outbound_edges:
    - { to: "livehelp_js.php", type: "references", weight: 1.0 }
    - { to: "image.php", type: "references", weight: 1.0 }
    - { to: "lupo-archive/legacy/craftysyntax-3.7.5/livehelp_js.php", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_paths.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_visits.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_edges.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_truth_knowledge.toon", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_truth_answers.toon", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_paths.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_visits.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_edges.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_truth_knowledge.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_truth_answers.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/migrations/", type: "references", weight: 0.9 }

lupopedia.footer:
  version: "4.0.88"
  approval_status: "rejected"
  approval_target_version: "4.1.0"
  approval_status_utc: "20260327103238"
  approval_status_by: "Cursor IDE Agent (Lead Orchestration)"
  approval_status_by_actor_id: 102
  last_verified: "20260327"
  last_verified_by: "cursor"
  orchestrator: "wolfie"
  next_action:
    - "Use this document as architecture baseline for 4.1.0 execution artifacts"
    - "Keep table references aligned with install SQL and TOON files"
    - "Do not start implementation until 4.1.0 approval flow promotes execution scope"
---
# PRD 04: lupopedia_js.php Foundation (4.0.88)

Status: Foundation definition only. Not fully implemented in 4.0.88.

## 1. System Overview

Softaculous feedback identifies a missing system: `lupopedia_js.php`.

This endpoint is defined as a JavaScript generator similar to Crafty Syntax `livehelp_js.php`, but extended for:

1. Navigation intelligence from observed transitions.
2. Knowledge graph interaction from existing semantic tables.
3. Behavioral tracking persisted to canonical Lupopedia tables.
4. Social interaction capture (like/share) with doctrine-safe storage.

Difference from Crafty Syntax baseline:

1. Crafty Syntax focused on chat status and operator interaction.
2. Lupopedia must additionally build queryable page-to-page and page-to-knowledge relationships.
3. Lupopedia must work in subdirectory installs without prior host/CMS knowledge.

## 1.1 Federation Node Model (REQUIRED)

Lupopedia federation context for this PRD:

1. Federation Node 0:
   - `lupopedia.com/lupopedia`
   - canonical/intended primary knowledge hub
   - not fully deployed yet
   - link gaps and unresolved references are expected in this phase
2. Federation Node 1:
   - current active installation where runtime tracking occurs
   - can be local dev, staging, or user installation
3. Federation Nodes 2+:
   - other external domains running Lupopedia
   - future federation participants for cross-node edges/data exchange

Cross-node principle:

1. Runtime behavior is local-first at node 1.
2. Cross-node graph integration is incremental and can be incomplete before 4.1.0 stabilization.

## 1.2 Deployment Reality (REQUIRED)

Current deployment state that this PRD must honor:

1. Node 0 (`lupopedia.com/lupopedia`) is not fully live yet.
2. Deployment has used VPS + FTP + manual DB update workflows.
3. Some links, references, and graph pointers to node 0 may be broken or partial.
4. This is an expected transitional state, not a bug classification by itself.

Release implication:

1. 4.0.88 is preparation + structure.
2. 4.1.0 is execution + full deployment hardening.
3. Softaculous approval is manual/external and remains a release gate.

## 1.3 System Constraints (REQUIRED)

During transitional deployment, the system must:

1. operate when global graph coverage is incomplete,
2. tolerate missing cross-node references,
3. continue local tracking and edge formation without node 0 dependency,
4. build knowledge incrementally from observed runtime behavior,
5. avoid assumptions that every referenced path is already resolvable.

## 2. Architecture

Flow A (generation):

1. Browser requests `/lupopedia/lupopedia_js.php`.
2. PHP endpoint reads install context (`LUPOPEDIA_PUBLIC_PATH`) and emits embeddable JS.

Flow B (data write):

1. Generated JS observes page URL/referrer/title plus interactions.
2. JS sends events to existing server endpoints (`image.php` and API surfaces) under the current install path.
3. Server writes to canonical tables via `DatabaseFactory::getConnection()`.
4. All applicable writes must include node context (`federation_node_id`) so data remains local-node correct.

Flow C (data read for UI):

1. Generated JS requests semantic navigation payloads.
2. Backend resolves current content by slug/custom_path/content_id and returns graph/context/QA payloads.

## 3. UI Specification (Floating Bottom Bar)

`lupopedia_js.php` must define a bottom-fixed, embeddable navigation bar with sections:

1. Navigation Intelligence:
   - Most common previous pages.
   - Most common next pages.
2. Graph Relationships:
   - Inbound references to current page.
   - Related pages from semantic edges.
3. Knowledge Layer:
   - Linked questions.
   - Candidate answers and truth snippets.
4. Context Layer:
   - Collections/contexts.
   - Hashtags.
5. Social Layer:
   - Like action (tracked).
   - Share action (tracked).

## 4. Data Collection Model

Foundation storage is defined against existing schema:

1. Raw event visits: `lupo_visits`.
2. Aggregated transitions: `lupo_paths` and optional summary in `lupo_paths_summary`.
3. Page/entity identity: `lupo_contents`.
4. Semantic relations: `lupo_edges` (+ `lupo_edge_type_definitions`; compatibility read via `lupo_edge_map` where used).
5. Knowledge records: `lupo_truth_knowledge` and `lupo_truth_answers`.
6. Context/hashtags: `lupo_collections`, `lupo_collection_map`, `lupo_hashtags`, `lupo_hashtag_map`.
7. Social counters and actor caches: `lupo_contents.like_count`, `lupo_contents.share_count`, `lupo_contents.comment_count`, `like_users`, `share_users`.

No non-canonical table is required for foundation definition.

## 5. Navigation Intelligence

Previous/next computation in foundation model:

1. Resolve current page to `content_id`.
2. Query incoming/outgoing transitions from `lupo_paths` by `entercontentid`/`exitcontentid`.
3. Weight candidates using:
   - `count_num` (frequency)
   - recency (`updated_ymdhis`)
   - optional `transition_type` filtering.
4. Return bounded top-N lists for previous and next sections.

## 6. Knowledge Integration

Knowledge panel uses existing truth model only:

1. `lupo_truth_knowledge` rows mapped by `object_type/object_id` or slug match.
2. `lupo_truth_answers` for direct question-answer support.
3. Collection context and hashtags from mapping tables.
4. Any relation produced from behavior must map into `lupo_edges` and remain reversible/auditable.

## 7. Social Features

Foundation behavior:

1. Like/share actions must be captured as explicit events.
2. Counters must roll up to `lupo_contents` engagement fields.
3. Per-actor attribution is stored in JSON caches (`like_users`, `share_users`) or action logs without introducing non-doctrine foreign-key coupling.

## 8. Constraints

Hard constraints:

1. Lupopedia does not know external site topology in advance.
2. No CMS plugin requirement; embed must remain CMS-independent.
3. All knowledge inference must come from observed runtime behavior and existing DB state.
4. Use subdirectory-safe URL generation; never assume fixed install folder name.
5. Respect timestamp and soft-delete doctrine.

## 8.1 lupopedia_js.php Federation Behavior (REQUIRED)

`lupopedia_js.php` federation-aware behavior in 4.0.88 foundation:

1. collect and persist tracking locally on the active node (typically node 1),
2. create navigation edges locally when transitions are observed,
3. include `federation_node_id` in applicable event/content/edge records,
4. avoid requiring node 0 availability for local operation,
5. allow future merge/share across nodes without blocking local ingestion.

## 9. Non-Goals (4.0.88)

1. No manual host page registry.
2. No CMS-specific integration layer.
3. No speculative crawler that invents unseen site paths.
4. No forced schema invention outside install SQL + TOON authority.

## Crafty and Migration Grounding

Crafty reference baseline:

1. `lupo-archive/legacy/craftysyntax-3.7.5/livehelp_js.php`
2. Crafty visit/path lifecycle documented in migration inputs and `gc.php` lineage.

Migration compatibility requirement:

1. Fresh installs use `install_new_lupopedia.sql` canonical schema.
2. Upgraded installs use `import_from_old_crafty_syntax.sql` mappings and migration doctrine.

Schema authority contract:

1. Install SQL defines the runtime contract.
2. TOON files under `lupo-database/lupopedia/toon/` are structural authority surfaces.
3. Table docs under `lupo-docs/database/lupopedia/tables/` explain behavior and constraints.

## Softaculous Release Constraint

1. 4.1.0 release readiness depends on external manual Softaculous approval flow.
2. Approved 4.0.x baseline is required prior to final 4.1.0 distribution readiness.
3. This 4.0.88 artifact is intentionally a foundation/definition surface for that progression.
