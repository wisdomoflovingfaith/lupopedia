---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "prd"
  file_path_from_root: "lupo-docs/versions/4.0.88/prd/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/prd/README.md"
  last_modified_utc: "20260327"
  system_version: "4.0.88"
  channel_id: 42
  actor_id: 105
  actor_name: "cascade"
  faucet_name: "cascade"
  delegation_chain: "cascade:root"
  artifact_type: "prd"
  artifact_kind: "index"
  purpose: "Product Requirements index for 4.0.88 thread-defined workflow and lupopedia_js system surfaces"
  tags: ["4.0.88", "prd", "semantic_monitoring", "livehelp_js", "visitor_intelligence"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.88/prd/01_semantic_monitoring_widget.md", type: "contains", weight: 1.0 }
    - { to: "lupo-docs/versions/4.0.88/prd/02_data_model.md", type: "contains", weight: 1.0 }
    - { to: "lupo-docs/versions/4.0.88/prd/03_goals_and_success_criteria.md", type: "contains", weight: 1.0 }
    - { to: "lupo-docs/versions/4.0.88/prd/04_lupopedia_js_foundation.md", type: "contains", weight: 1.0 }
    - { to: "lupo-docs/versions/4.0.88/PLAN.md", type: "extends", weight: 0.95 }
    - { to: "livehelp_js.php", type: "references", weight: 1.0 }
    - { to: "lupopedia_js.php", type: "references", weight: 1.0 }
    - { to: "image.php", type: "references", weight: 0.95 }

lupopedia.footer:
  version: "4.0.88"
  approval_status: "rejected"
  approval_target_version: "4.1.0"
  approval_status_utc: "20260327103238"
  approval_status_by: "Cursor IDE Agent (Lead Orchestration)"
  approval_status_by_actor_id: 102
  last_verified: "20260326"
  last_verified_by: "cascade"
  orchestrator: "wolfie"
  next_action:
    - "Use this PRD set as 4.0.88 definition and preparation surface only"
    - "Carry refinements into future 4.0.x review iterations"
    - "Treat 4.1.0 implementation as post-approval work"
---
# file: 4.0.88 PRD Index — delegation: warp:wolfie

# 4.0.88 Product Requirements

**Version:** 4.0.88
**Author:** CASCADE (actor_id 105)
**Orchestrator:** Wolfie (actor_id 1)
**Date:** 2026-03-26

---

## Overview

## 4.1.0 Release Scope Classification

This PRD set remains a 4.0.88 feature-planning surface.

For 4.1.0 release governance in the current thread:

- It is preserved as historical feature planning.
- It is not approved for 4.1.0 release scope.
- It is classified as `rejected` for 4.1.0 indexing because the semantic monitoring widget and related expansion work are not currently part of the installer-acceptance release path.

Promotion rule:

- These artifacts must not be treated as 4.1.0 release-binding unless a later review explicitly reclassifies them and updates their footer state.

This PRD folder records the 4.0.88 system-definition work completed in this thread. It is a preparation and architecture surface, not a claim that the covered systems are already fully implemented.

The main 4.0.88 role of this PRD set is:

1. Define workflow and governance boundaries.
2. Define missing system surfaces such as `lupopedia_js.php`.
3. Document federation, deployment, and hybrid-storage constraints.
4. Prepare future 4.0.x iterations to refine the package under Softaculous feedback.

## Workflow Model

The workflow defined across this thread is:

1. Channel.
2. Questions.
3. Discussion.
4. Prompts.
5. Execution.

This ordering is intentional:

1. Channels hold the governed work context.
2. Questions capture scoped intake and uncertainty.
3. Discussion resolves direction.
4. Prompts become execution material after the question/discussion stage.
5. Execution happens only after the upstream coordination surfaces exist.

## Systems Defined in 4.0.88 PRD Scope

The following systems are defined or clarified by this PRD surface:

1. Channel system.
2. Questions system.
3. Prompts system.
4. CLI system at the workflow-contract level.
5. Hybrid storage model: database as runtime authority, filesystem as communication/documentation projection.
6. Federation model.
7. `lupopedia_js.php` system.

## Version Positioning

1. 4.0.88 is the current structure, doctrine, and preparation iteration.
2. Future 4.0.x versions such as 4.0.89 and 4.0.90 refine the package using Softaculous feedback.
3. 4.1.0 is the later post-approval execution milestone.

Lupopedia is installed in a subfolder, but the install basename is dynamic at runtime and must not be treated as hardcoded. Where PRD examples refer to `/lupopedia/`, they describe the subdirectory model, not a fixed required folder name.

---

## PRD Documents

| # | Document | Purpose |
|---|----------|---------|
| 1 | [01_semantic_monitoring_widget.md](01_semantic_monitoring_widget.md) | Core PRD — what the widget does, how it works, feature requirements |
| 2 | [02_data_model.md](02_data_model.md) | Database tables, data flow, and storage architecture |
| 3 | [03_goals_and_success_criteria.md](03_goals_and_success_criteria.md) | Version goals, phased delivery, and success criteria |
| 4 | [04_lupopedia_js_foundation.md](04_lupopedia_js_foundation.md) | Missing `lupopedia_js.php` system definition baseline for Softaculous feedback closure |

---

## Current State

`livehelp_js.php` already exists and generates functional JavaScript that:
- Captures `document.location`, `document.title`, `document.referrer`
- Pings `image.php` with visitor data (`what=userstat`, `what=getstate`, `what=browse`)
- Manages operator online/offline status icons
- Handles DHTML layer invites for proactive chat
- Uses image pixel-width as a control signal from operators

The system works but is **legacy-patterned** (document.write, image-pixel control signals, NS4/IE4 detection). The semantic data (page paths, referrers, navigation sequences) is collected but not yet stored in a structured, queryable semantic graph.

---

## What 4.0.88 Adds

1. **Structured semantic storage** — visitor paths, referrers, and navigation sequences stored in canonical Lupopedia tables (`lupo_visits`, `lupo_paths`, `lupo_contents`, `lupo_edges`)
2. **Federation node scoping** — each Lupopedia installation is a federation node; visitor data is scoped to `federation_node_id`
3. **Modernized JS generation** — replace legacy patterns while maintaining backward compatibility
4. **Navigation graph** — page-to-page transitions as edges, enabling path analysis
5. **Semantic content registration** — pages discovered by the widget are auto-registered in `lupo_contents`
6. **Engagement tracking** — likes, shares, comments, hashtags, and social interactions
7. **Crafty Syntax parity framing** — tie frontend/tracking closure to the broader feature-parity and installer-acceptance path

## Softaculous Gap Closure in 4.0.88 PRD Surface

This folder now includes an explicit foundation definition for the missing `lupopedia_js.php` endpoint.

Scope note:

1. 4.0.88 documents architecture, data contracts, and constraints.
2. 4.0.88 does not claim full implementation completion for this endpoint.
3. 4.1.0 execution artifacts define implementation-ready behavior and acceptance evidence.
4. Future 4.0.x iterations may refine this PRD set before 4.1.0 execution begins.


---

## Constraints

- **PHP 7.4+ compatibility** — no Composer, no frameworks
- **No foreign keys** — referential integrity in application code only
- **BIGINT UTC timestamps** — `YYYYMMDDHHIISS` format, set in PHP
- **Subfolder install doctrine** — all URLs via `LUPOPEDIA_PUBLIC_PATH`
- **PDO_DB access only** — `DatabaseFactory::getConnection()`
- **Shared hosting assumptions** — no cron guaranteed, no WebSocket, fallback-first
