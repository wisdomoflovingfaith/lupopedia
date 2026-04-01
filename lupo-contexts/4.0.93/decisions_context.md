---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: context
  when_updated: "20260331190000"
  file_path_from_root: "lupo-contexts/4.0.93/decisions_context.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-contexts/4.0.93/decisions_context.md"
  last_modified_utc: "20260331190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.93-decisions"
  context_id: 1001
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "context"
  artifact_kind: "decisions"
  purpose: "Finalized decisions for Lupopedia 4.0.93"
  tags:
  - "context"
  - "decisions"
  - "version-4.0.93"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.93/decisions.md"
      type: supersedes
      weight: 1.0
      reason: "This context finalizes the discussion decisions"
    - to: "lupo-docs/prd/17_decisions_format.md"
      type: implements
      weight: 1.0
      reason: "Follows PRD format"
---

# Lupopedia 4.0.93 - Finalized Decisions Context

## Overview

This document represents the **finalized, canonical decisions** for Lupopedia 4.0.93. All decisions documented here have been accepted and implemented.

## Summary of Finalized Decisions

| ID | Decision | Author | Status | Implementation |
|----|----------|--------|--------|----------------|
| D-01 | Canonical Header Versioning | LILITH | Completed | Header format 2.0 |
| D-02 | Department-Scoped Actor Model | HEPHAESTUS | Completed | ActorLeaseService |
| D-03 | Temporal System and UTC Authority | HEPHAESTUS | Completed | tick.py |
| D-04 | Agent/Actor Verification Attribution | LILITH | Completed | Footer structure |
| D-05 | Versioned Documentation Structure | LILITH | Completed | versions/ directories |
| D-06 | Consolidated Seed File | HEPHAESTUS | Completed | install/seed_lupopedia_4_1_0.sql |
| D-07 | Dynamic Table Prefix Migration | HEPHAESTUS | Completed | {{prefix}} placeholders |
| D-08 | File-Based Agent Doctrine | WOLFIE | Completed | lupo-agents/{agent_key}/ |
| D-09 | Subdirectory Installation Doctrine | WOLFIE | Completed | /lupopedia/ prefix |
| D-10 | JSON Schema Management Workflow | ANUBIS | Completed | SQL-first workflow |
| D-11 | LEXA Security Enforcement Enhancement | LILITH | Completed | Version 1.0.2 |
| D-12 | ATHENA Wisdom & Strategy Enhancement | LILITH | Completed | Version 1.0.2 |
| D-13 | THOTH Knowledge & Records Enhancement | LILITH | Completed | Version 1.0.2 |
| D-14 | ANUBIS Custodian Enhancement | LILITH | Completed | Version 1.0.2 |
| D-15 | Primary Coordination Personas Priority Order | WOLFIE | Accepted | In progress |
| D-16 | Cross-Thread Coordination Protocol | LILITH | Active | Ongoing enforcement |

## Resolution of Open Questions

| Question | Answer | Resolution |
|----------|--------|------------|
| Q-01: HEIMDALL Actor ID | 108 | Assigned |
| Q-02: MAAT Layer Placement | Kernel | Remains in Kernel |
| Q-03: Semantic Monitoring Widget Integration | Batch + localStorage | Option B selected |

## Context References

This context supersedes and finalizes the discussion in:
- Channel 42, Thread "version-4.0.93-decisions"
- File: `lupo-docs/versions/4.0.93/decisions.md`

---

**Status**: FINALIZED
**Effective**: 2026-04-01
**Canonical Reference**: This context is the source of truth for Lupopedia 4.0.93 decisions.
