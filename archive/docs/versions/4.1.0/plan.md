---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/versions/4.1.0/plan.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.0/plan.md"
  status: "active"
  when_updated: "20260415094313"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/version-4-1-0-plan.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/4_1_0_plan"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "Version 4.1.0 implementation plan"
  summary: "Dependency-driven implementation plan for Lupopedia 4.1.0 alignment."
---
# file: Version 4.1.0 implementation plan — session: 4.1.0 — delegation: wolf:root — web_path: [https://www.lupopedia.com/lupopedia/docs/versions/4.1.0/plan.md](https://www.lupopedia.com/lupopedia/docs/versions/4.1.0/plan.md)

# Version 4.1.0 Implementation Plan

## Active Objective
Bring all Lupopedia headers, validators, memory system references, database schema, and runtime code into full 4.1.0 alignment (memory_toon + atoms_toon + transcript_jsonl).

## Current Workstreams

### 1. Header Doctrine & Examples
**Status:** in_progress  
**Owner:** WOLF  
**Goal:** Ensure all active PRDs use exact v4.1.0 22-field dense header

### 2. Python Validators & Tooling
**Status:** in_progress  
**Owner:** AUGGIE  
**Goal:** Complete atoms_toon Phase 1 enforcement + legacy cleanup

### 3. Database Schema Alignment
**Status:** in_progress  
**Owner:** CURSOR  
**Goal:** Ensure all DB reads use `memory_toon` column (post-rename)

### 4. PHP Runtime Updates
**Status:** pending  
**Owner:** CURSOR  
**Goal:** Align all PHP DB access and message handling with new field names

### 5. Memory Artifacts & TOON Pairing
**Status:** in_progress  
**Owner:** AUGGIE / WOLF  
**Goal:** Update .toon / .json pairs for migrated headers

### 6. Full Validation Sweep & Migration
**Status:** pending  
**Owner:** WOLF  
**Goal:** Run batch validator across all in-scope files

## Dependency Order

1. PRD 16 finalized (done)
2. atoms_toon Phase 1 validator (done)
3. Active PRD header upgrades (in progress)
4. Python tooling alignment (in progress)
5. DB read migration (in progress)
6. PHP runtime alignment
7. Full corpus validation sweep
8. Documentation & session files (done)
9. Web UI / runtime testing

## Notes
- This file is the single active dependency-driven plan for 4.1.0.
- Work is tracked hourly via CHANGELOG.md.
- All meaningful changes must update changelog + open questions if needed.
