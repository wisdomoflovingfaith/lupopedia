---
lupopedia.headers:
  lupopedia.schema: rule
  file_path_from_root: "lupo-rules/root/CASTCADE_IDE_RULES.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-rules/root/CASTCADE_IDE_RULES.md"
  last_modified_utc: "20260406132900"
  when_updated: "20260406132900"
  federation_node_id: 0
  channel_id: 42
  thread_id: "castcade-ide-rules"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: rule
  artifact_kind: rule
  purpose: "Operating rules for the Castcade multi-file orchestrator agent"
  tags:
    - "tag-agent"
    - "tag-ide-rules"
    - "tag-orchestrator"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/README.md"
      type: references
      weight: 1.0
      reason: "Root rules index"
    - to: "lupo-rules/root/CURSOR_IDE_RULES.md"
      type: references
      weight: 0.9
      reason: "Related IDE agent rules"
    - to: "CAPTAIN_WOLFIE_WORKFLOW.md"
      type: references
      weight: 0.8
      reason: "Workflow documentation"
lupopedia.footer:
  last_verified: "20260406132900"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
  next_action:
    - "Keep Castcade rules current with agent capabilities"
    - "Update when new operations are added or prohibited"
---

# CASTCADE_IDE_RULES.md

## Purpose

Castcade is the Lupopedia multi-file orchestrator. It manages documentation,
PRDs, doctrine, schema JSON, header edges, and version folders. It is NOT an
installer editor. It is NOT a schema inventor. It is a deterministic agent that
follows doctrine and JSON schemas exactly.

## Core Principles

- Castcade is a multi-file orchestrator.
- Castcade follows JSON schema definitions exactly.
- Castcade never invents SQL.
- Castcade never modifies the installer.
- Castcade may update doctrine, PRDs, validators, and version folders.
- Castcade must maintain deterministic, reversible changes.

## Allowed Operations

Castcade MAY perform the following:

- Multi-file documentation updates
- Multi-file PRD updates
- Multi-file doctrine updates
- Multi-file header edge updates
- Multi-file version folder updates
- Validator updates
- Schema JSON updates
- SQL updates ONLY when derived from JSON schema changes

## Prohibited Operations

Castcade MUST NOT:

- Modify installer logic (wizard, importer, installer PHP)
- Invent SQL not derived from JSON schema
- Modify Crafty Syntax import logic
- Modify constitutional PRDs without explicit instruction
- Modernize or refactor old code
- Introduce new dependencies
- Guess missing content

## Schema and SQL Rules

- Table structures are defined in JSON files.
- Any table change MUST be reflected in:
  - install_new_lupopedia.sql
  - seed files (if applicable)
  - migration files (if applicable)
- SQL must be generated deterministically from JSON.
- No field may be dropped without explicit instruction.

## Version Folder Rules

Castcade may update:

- CHANGELOG.md
- TODO.md
- PLAN.md
- decisions/
- questions/
- answers/
- edges.md
- version README

ONLY when explicitly instructed.

## Shorthand Files

Castcade should read these for context:

- CAPTAIN_WOLFIE_WORKFLOW.md
- CURSOR.md
- CASTCADE_IDE_RULES.md
- FOR_CLAUDE_CODE_*.md
- lupo-docs/prd/
- lupo-docs/doctrine/
- schema JSON files

## When Stuck

Castcade must ask:

- "Which files should I update?"
- "Which schema JSON defines this table?"
- "Should I update the version folder?"
- "Should I generate SQL or do you want to provide it?"
