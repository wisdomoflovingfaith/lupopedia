---
lupopedia.headers:
  lupopedia.schema: "product_overview"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/product_overview.md"
  last_modified_utc: "20260327"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "product_overview"
  purpose: "Define 4.1.0 as the post-approval production milestone after iterative 4.0.x stabilization"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approved_by_actor_id: 1
  approved_utc: 20260326223100
  next_action:
    - "Keep 4.1.0 positioned after approved 4.0.x baseline"
    - "Keep execution scope aligned with foundation carried from 4.0.x"
---

# Product Overview: 4.1.0

## 4.1.0 Mission

Deliver the post-approval, production-ready Lupopedia release that follows the iterative 4.0.x Softaculous review cycle and is usable by operators on shared hosting.

## Foundation from 4.0.x

4.1.0 depends on the foundation established across 4.0.x iterations:

- workflow model
- architecture boundaries
- federation model
- `lupopedia_js.php` tracking and navigation definition
- approval footer and approval index system

## Preconditions for 4.1.0

Before 4.1.0 becomes active release execution scope, all of the following must be true:

- a Softaculous-approved 4.0.x version exists
- the system is stable enough to implement against
- structure and release-critical documentation are validated

## What 4.1.0 Is

- A post-approval milestone, not the immediate next patch after 4.0.88
- Installable at `example.com/lupopedia/`
- Shared-hosting compatible
- PHP 7.4+ compatible
- Deterministic and schema-stable
- Feature-parity focused for core Crafty Syntax 3.7.5 behaviors
- Operationally boring and predictable

## 4.1.0 Execution Scope

Priority order for this milestone:

1. `lupopedia_js.php` implementation.
2. Channel refactor completion.
3. Validator system.
4. CLI implementation.
5. Context system.

## What 4.1.0 Is Not

- Experimental multi-agent orchestration showcase
- Federation expansion release
- Major architecture rewrite
- Feature-lab for unfinished systems
- The automatic next version after 4.0.88 without intervening 4.0.x review loops

## Release Definition

4.1.0 is defined only by approved artifacts under `lupo-docs/versions/4.1.0/`.

## External Acceptance Objective

Auto-installers must be able to install and initialize Lupopedia without manual intervention, environment-specific hacks, or unstable migrations.
