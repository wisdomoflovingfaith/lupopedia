---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "README_codex.md"
  version_when_written: "4.0.84"
  last_modified_utc: "20260314"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "jetbrains_codex"
  delegation_chain: "wolfie:root"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Codex-canonical README refresh without cross-agent overwrite"
lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  last_verified_by: "codex"
  orchestrator: "wolfie"
  next_action:
    - "Merge approved sections into shared README.md"
---
# file: README_codex.md - session: L-LUPO-ROOT-JETBRAINS-CODEX - delegation: wolfie:root

# Lupopedia Semantic OS v4.0.74 (Codex Edition)

This document is the Codex-safe variant created to avoid multi-agent file collisions.

## What Lupopedia Is

Lupopedia is a doctrine-driven semantic operating system built from Crafty Syntax 3.7.5 lineage, running in a subdirectory deployment model.

## Identity Model (Verified)

- `lupo_auth_users`: human login identity
- `lupo_actors`: orchestration identity
- `lupo_agents`: AI/runtime metadata
- `lupo_agent_faucets`: execution surfaces (IDE/LLM)

Rule: actors orchestrate, faucets execute.

## Runtime Entry Flow (Verified)

1. `index.php`
2. `lupopedia-config.php`
3. `lupo-includes/bootstrap.php`
4. `lupo-includes/lupopedia-loader.php`
5. `lupo-includes/modules/module-loader.php`

## Header-Database Bridge

Bridge model:

`filesystem artifact <-> semantic snapshot <-> database rows`

Key doctrine alignment:
- `lupopedia.metadata` is row-snapshot content, not table-schema definition.
- `lupopedia.edges` / `lupopedia.engagement` are snapshots and should say so.
- Database remains authority for latest state.

## Repository Reality Snapshot (2026-03-14)

- File count observed: 11,376
- `CREATE TABLE` count in install SQL: 140

## Paths To Use

- `lupo-docs/`
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- `lupo-database/lupopedia/mysql/seed/`
- `lupo-database/lupopedia/actors/actor_id/registry.json`

## Notes

This file exists as `*_codex` variant because multiple IDE agents were editing concurrently on the same machine.
