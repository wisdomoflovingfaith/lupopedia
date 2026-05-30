---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: rules/root/CASTCADE_IDE_RULES.md
  web_path: https://www.lupopedia.com/lupopedia/rules/root/CASTCADE_IDE_RULES.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: rule
  artifact_kind: rule
  channel_key: null
  federation_node_id: 0
  thread_key: castcade-ide-rules
  lupopedia.schema: rule
  prd_cluster: null
  title: null
  summary: null
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
- docs/prd/
- docs/doctrine/
- schema JSON files

## When Stuck

Castcade must ask:

- "Which files should I update?"
- "Which schema JSON defines this table?"
- "Should I update the version folder?"
- "Should I generate SQL or do you want to provide it?"
