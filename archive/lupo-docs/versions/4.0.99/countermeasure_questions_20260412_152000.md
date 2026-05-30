---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: questions
  when_updated: "20260412152000"
  file_path_from_root: lupo-docs/versions/4.0.99/countermeasure_questions_20260412_152000.md
  web_path: ""
  last_modified_utc: "20260412152000"
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "draft"
  memory_key: "lupo-memory/development/draft/2026/04/countermeasure-questions-20260412-152000.toon"
  artifact_type: questions
  artifact_kind: countermeasure
  thread_id: "countermeasure-20260412"
  content_id: null
  pk_id: null
  pk_slug: "countermeasure-questions-20260412-152000"
  title: "Countermeasure Questions — 2026-04-12 15:20:00"
  status: "draft"
  parent_pk_id: ""
  summary: "Adversarial review of unresolved PRD and implementation gaps."
  module: null
  dialog_transcript: "0/development/countermeasure-20260412"

# Countermeasure Questions — 2026-04-12 15:20:00

This document records adversarially-posed questions, risks, and counterproposals for unresolved issues in Lupopedia's memory, trust, collections, and header PRDs.


## Question 1: Export Service Implementation Risk
Source PRD: PRD 38 §3, §5.2
Issue: Export service for mirroring DB memory nodes to filesystem is specified but not implemented. Immediate export (on-write) is proposed as the default.
Possible Answers:
A) Implement a PHP service that exports all new/updated memory nodes to disk on write (default proposal).
B) Implement a scheduled batch job that periodically syncs DB to disk (counterproposal).
C) Defer export service until after vector search is implemented.
Recommended: B) Scheduled batch sync. Immediate export risks race conditions, partial writes, and file lock contention under concurrent agent activity. Batch jobs allow for error recovery, deduplication, and integrity checks. On-write export is not robust in multi-agent or high-frequency environments.
Blocks: IDE agent filesystem access, memory graph audit, backup workflows.

## Question 2: Trust Ladder Schema Enforcement Ambiguity
Source PRD: PRD 43 §1.2
Issue: Edge predicates and trust weights are defined, but there is no schema or enforcement in DB or app. Proposal is to add schema and validation for all trust ladder edge types.
Possible Answers:
A) Add schema and validation for all trust ladder edge types in `lupo_memory_edges` (default proposal).
B) Only implement `trusts` and `delegates_to` initially, defer others (counterproposal).
C) Leave as documentation only; implement as needed.
Recommended: B) Implement only the minimum viable set (`trusts`, `delegates_to`) and defer others until real use cases emerge. Overengineering the schema risks locking in unused edge types and complicating migrations. Document the rest for future extension.
Blocks: Channel security, memory scope inheritance, trust-based access control.

## Question 3: Memory Key Year Segment Consistency
Source PRD: PRD 16 §4.2, PRD 38 §4
Issue: Inconsistent use of `1026` (display year) vs `2026` (actual year) in memory keys and headers. Proposal is to enforce display year for canonical, actual year for staging/seed.
Possible Answers:
A) Enforce `display_year = actual_year - 1000` for all canonical memory keys (default proposal).
B) Use actual year everywhere for simplicity (counterproposal).
C) Allow both, document which is canonical.
Recommended: C) Allow both, but require explicit documentation and validator warnings for any non-canonical year segment. Strict enforcement risks breaking legacy and cross-agent compatibility. Document intent and flag inconsistencies for review.
Blocks: Header validation, memory graph traversal, cross-agent consistency.

## Question 4: AI vs Human Collection Sync Service
Source PRD: PRD 72/73, PRD 38 §4.4
Issue: No service to sync AI (edge-based) and human (table-based) collections. Proposal is to implement a sync service that creates/updates both edges and tables on change.
Possible Answers:
A) Implement a sync service that creates/updates both edges and tables on change (default proposal).
B) Manual review only; no automatic sync (counterproposal).
C) Defer until after vector search is live.
Recommended: B) Manual review only. Automatic sync risks semantic drift, accidental data loss, and unreviewed propagation of AI errors into human-facing UI. Require human approval for all cross-system syncs.
Blocks: Collection navigation, AI recommendations, UI/graph parity.

## Question 5: Header Inference from Memory Graph
Source PRD: PRD 51 §3
Issue: No implementation for resolving header fields from memory graph and dialog thread context. Proposal is to build a header inference API/service.
Possible Answers:
A) Build a header inference API/service that queries the memory graph and thread context (default proposal).
B) Continue using path-based inference (counterproposal).
C) Hybrid: use path as fallback only.
Recommended: C) Hybrid. Use path-based inference as the default, with optional memory graph enrichment for advanced agents. Full graph-based inference risks circular dependencies, performance issues, and ambiguous provenance. Path remains the only universally available context.
Blocks: Accurate header generation, artifact promotion, memory graph integrity.

## Question 6: Interactive Header Migration Tool
Source PRD: PRD 16 §20
Issue: No interactive tool for per-file legacy header migration; mass migration is forbidden. Proposal is to build an interactive CLI tool for header migration, one file at a time.
Possible Answers:
A) Build an interactive CLI tool for header migration, one file at a time (default proposal).
B) Allow batch migration with review (counterproposal).
C) Defer migration until after all PRDs are updated.
Recommended: B) Allow batch migration with mandatory review checkpoints. Purely interactive migration is too slow for large-scale adoption and risks stalling progress. Batch with review allows for scale and integrity.
Blocks: Header compliance, validator pass, and future upgrades.

---

## Summary

**Total questions:** 6
**Critical blockers:** Export service, trust ladder schema, memory key year segment, collection sync, header inference, header migration
**Suggested resolution order:**
1. Export service (PRD 38)
2. Trust ladder schema/enforcement (PRD 43)
3. Memory key year segment consistency (PRD 16/38)
4. Header inference from memory graph (PRD 51)
5. AI/human collection sync (PRD 72/73)
6. Interactive header migration tool (PRD 16)

Countermeasure complete. 6 questions in lupo-docs/versions/4.0.99/countermeasure_questions_20260412_152000.md
