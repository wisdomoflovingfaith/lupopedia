---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260406174709"
  file_path_from_root: "lupo-docs/prd/40_versioning_doctrine.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/40_versioning_doctrine.md"
  last_modified_utc: "20260406174709"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-40-versioning-doctrine"
  prd_id: 40
  prd_slug: versioning_doctrine
  author:
    type: "actor"
    id: 102
    name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: prd
  artifact_kind: versioning
  purpose: "Constitutional versioning model for Lupopedia — 4.0.x line, Crafty Syntax 3.7.5 upgrade path, 4.1.0 gate, federation readiness"
  status: "approved"
    - prd
    - versioning
    - 4.0.x
    - 4.1.0
    - crafty_syntax
    - federation
    - constitution
  tags:
    - tag-prd
    - tag-versioning
    - tag-4-0-x
    - tag-4-1-0
    - tag-crafty-syntax
    - tag-federation
    - tag-constitution
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/VERSIONING_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical versioning doctrine file — single source of truth for version numbers and upgrade path narrative"
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional law — release and migration constraints"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "Auto-installer acceptance gate for 4.1.0"
    - to: "lupo-docs/prd/27_installer_requirements.md"
      type: references
      weight: 1.0
      reason: "Installer and fresh-install model for 4.0.x"
lupopedia.footer:
  last_verified: "20260406174709"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/prd/40_versioning_doctrine.md — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/prd/40_versioning_doctrine.md

# PRD 40 — Versioning Doctrine

## Purpose

This PRD defines the constitutional versioning model for Lupopedia, including why the system begins at 4.0.x, why no Lupopedia→Lupopedia upgrades exist before 4.1.0, and how Crafty Syntax 3.7.5 upgrades work.

## Why Lupopedia Starts at 4.0.x

- Lupopedia 4.0.x is NOT a continuation of Lupopedia.
- It is the successor to Crafty Syntax 3.7.5.
- The version jump is intentional: 3.7.5 → 4.0.x.
- There are no versions between them.

## No Lupopedia→Lupopedia Upgrades Before 4.1.0

- 4.0.x is an installer-only era.
- No forward upgrades exist.
- No schema migrations exist.
- No version drift is allowed.
- 4.1.0 is the first version that supports upgrades.

## Auto-Installer Acceptance Gate

- Lupopedia 4.1.0 cannot be released until Softaculous and other auto-installers accept Lupopedia 4.0.x as a stable installable application.
- This is a constitutional requirement.

## Crafty Syntax Upgrade Path

- The ONLY valid upgrade path is:

      Crafty Syntax 3.7.5 → Lupopedia 4.0.x

- During this upgrade:
  - All `livehelp_` tables are imported into `lupo_` tables.
  - All edges, departments, operators, and navigation structures are preserved.
  - This is the moment where Lupopedia learns the node’s entire edge graph.

## Federation Readiness at 4.1.0

- Nodes become discoverable only after 4.1.0.
- 4.1.0 introduces:
  - Lupopedia→Lupopedia upgrades
  - schema migrations
  - federation identity
  - cross-node discovery
  - actor/agent evolution

## Constitutional Rules

- 4.0.x is frozen.
- No forward migrations.
- No breaking changes.
- No schema churn.
- 4.1.0 is the first “living” version.
