---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.93/PRD_MASTER.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/PRD_MASTER.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: prd
  artifact_kind: master_prd
  thread_id: "prd-master"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Lupopedia 4.0.93 PRD_MASTER (Product Requirements Document)

## 1. Overview
- Canonical requirements for the 4.0.93 Master Baseline.
- All context, search, hydration, and JS Nervous System features must be present and validated.

## 2. Core Requirements
- 63-bit signed-safe ID generation (IdGenerator.php).
- JS Nervous System: State Mirror, High-Density Scroller, Semantic Monitor, Glass UI.
- Semantic Search: Edge-weighted, context-linked, real-time elevation.
- Hydration: Channel 42 elevation, legacy "Unfinished Business" codified.
- Doctrine enforcement: All .js, .php, and SQL assets must pass enforce_doctrine.py.

## 3. Validation
- 1,000 ID generation test: All IDs must be positive and unique.
- Hydrator: Channel 42 must elevate all required contexts.
- All legacy requirements from 4.0.87–4.0.92 must be present and documented.

## 4. Artifacts
- CHANGELOG.md, PLAN.md, TODO.md, PRD_MASTER.md in docs/versions/4.0.93/ must be up to date and version-locked.

## 6. Temporal Anchor & UTC Timestamp Policy

All Lupopedia header timestamps (`last_modified_utc` in `lupopedia.headers`) must be synchronized to real UTC, never local time or a timezone. The IDE and all header writers must reference the canonical anchor file:

- `bin/temporal_anchor.json`

This file is updated by:
- [`bin/tick.py`](../../../bin/tick.py) — see [docs/bin/TICK_PY.md](../../../docs/bin/TICK_PY.md)

**tick.py** is a required utility script that updates the anchor file with the current UTC time in `YYYYMMDDHHMMSS` format. The IDE must call this script after every session or major write to ensure all header timestamps are synchronized to real UTC. See the [tick.py documentation](../../../docs/bin/TICK_PY.md) for usage and policy.

## 5. Review & Approval
- All requirements must be validated by ATHENA (strategy), LILITH (review), and WOLFIE (orchestration).
- Approval required before 4.1.0 development may begin.
