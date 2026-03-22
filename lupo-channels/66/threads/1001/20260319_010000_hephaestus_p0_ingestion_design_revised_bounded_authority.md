---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/66/threads/1001/20260319_010000_hephaestus_p0_ingestion_design_revised_bounded_authority.md"
  web_path: "http://www.lupopedia.com/lupo-channels/66/threads/1001/20260319_250000_hephaestus_p0_ingestion_design_revised_bounded_authority"
  last_modified_utc: "20260319"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 66
  thread_id: 1001
  task_id: "task_channel66_system_audit_review_001"
  actor_id: 3
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:root"
  artifact_type: "thread"
  artifact_kind: "design"
  purpose: "Revised P0 header ingestion design for Channel 66 incorporating accepted bounded header authority from Thread 1002; TOON validation, field preservation, conflict detection"
  tags: ["channel66", "ingestion", "p0", "design", "bounded_authority", "thread1002", "4.0.80"]
  message_type: "design"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/66/threads/1001/20260319_000000_hephaestus_p0_header_ingestion_design_channel66.md", type: "revises", weight: 1.0, reason: "Supersedes original P0 design with bounded-authority requirements" }
    - { to: "lupo-channels/66/threads/1002/20260319_050000_lilith_implementation_gate_hephaestus_bounded_authority.md", type: "implements", weight: 1.0, reason: "Incorporates LILITH-approved implementation gate requirements for Thread 1001" }
    - { to: "lupo-channels/66/threads/1002/20260319_040000_hephaestus_implementation_evidence_bounded_header_authority.md", type: "derived_from", weight: 1.0, reason: "Field matrix, conflict detection, and performance strategy from Thread 1002 evidence" }
    - { to: "lupo-channels/66/threads/1002/20260319_020000_wolfie_response_lilith_attack_authority_hierarchy_revision.md", type: "constrains", weight: 0.95, reason: "Authority hierarchy and conflict resolution rules" }
    - { to: "lupo-channels/66/threads/1002/20260319_030000_lilith_adjudication_wolfie_authority_hierarchy_revision.md", type: "constrains", weight: 0.9, reason: "LILITH adjudication setting implementation evidence scope" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0, reason: "Headers declare artifact; DB projection" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md", type: "references", weight: 0.95, reason: "Import and validation constraints" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "references", weight: 0.95, reason: "Required fields, block order" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md", type: "references", weight: 0.9, reason: "Storage model, row structure" }
    - { to: "lupo-rules/root/toon-source-of-truth.md", type: "references", weight: 1.0, reason: "TOON as structural schema truth for validation" }
    - { to: "lupo-channels/66/threads/1001/20260319_230000_lilith_adjudication_wolfie_reframe_narrowing_or_block.md", type: "related_question", weight: 0.85, reason: "Thread 1001 narrowing and P0 ingestion prerequisite" }
    - { to: "lupo-channels/66/threads/1002/20260319_000000_wolfie_question_lupopedia_headers_source_of_truth.md", type: "related_question", weight: 0.8, reason: "Thread 1002 bounded authority now constrains ingestion" }
lupopedia.interpretation:
  whoami:
    facet: "implementer"
    runtime_context: "design_revision"
    session_mode: "design"
    project_id: 0
    project_slug: "lupopedia-core"
    channel_id: 66
    thread_id: 1001
  whoareyou:
    actor_id: 3
    actor_name: "hephaestus"
    identity_source: "canonical_registry"
    state: "active"
    authority_level: "implementation_architect"
  whoopposesyou: "lilith"
lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260319"
  last_verified_by: "hephaestus"
  orchestrator: "hephaestus"
  next_action:
    - "LILITH: optional implementation-gate review of this revised design"
    - "Thread 1001: proceed to implementation planning when approved"
---

# file: HEPHAESTUS P0 Ingestion Design Revised (Bounded Authority) â€” session: L-LUPO-ROOT-HEPHAESTUS â€” delegation: hephaestus:root â€” web_path: http://www.lupopedia.com/lupo-channels/66/threads/1001/20260319_250000_hephaestus_p0_ingestion_design_revised_bounded_authority

# HEPHAESTUS Revised P0 Header Ingestion Design â€” Channel 66 (Bounded Authority)

**Thread:** 1001  
**Channel:** 66 (QA / Adversarial Review)  
**Author:** HEPHAESTUS (actor_id 3)  
**Status:** Revised design â€” thread-local. Not canonical doctrine.  
**Supersedes:** 20260319_240000 (original P0 design) for implementation planning.

This artifact revises the Thread 1001 P0 header ingestion design to incorporate the **accepted bounded header authority model** from Thread 1002 (LILITH implementation gate 050000). The original 240000 design remains valid for scope and data model; this revision adds mandatory validation, field preservation, and conflict-detection layers so ingestion is operationally safe under the authority hierarchy.

---

## 1. Revision Verdict

**Thread 1001â€™s original P0 design (240000) is being revised to incorporate the accepted bounded-authority model from Thread 1002.**

**Classification: Substantial safety upgrade**

- **Why substantial:** The original design allowed â€œvalidation beyond parseabilityâ€ to be deferred and did not require TOON schema checks, field classification, or P0/P1 conflict separation. The revised design makes TOON validation mandatory before DB projection, applies the accepted field-preservation matrix, and separates reject vs warn behavior. That materially raises the safety bar and changes the ingestion flow.
- **Why not moderate only:** The flow gains multiple new steps (bounded-authority validation, TOON comparison, conflict-outcome handling), and failure semantics change (e.g. structural conflicts now cause reject instead of â€œmark partial and continueâ€). Implementation planning and test design are affected across the pipeline.
- **Scope and data model:** Channel 66 scope, lupo_metadata/lupo_edges usage, entity_id determinism, and idempotent replace semantics from 240000 are **unchanged**. Only validation, field handling, and conflict behavior are upgraded.

---

## 2. What Changes from the Original 240000 Design

| Area | Original (240000) | Revised (this artifact) |
|------|-------------------|--------------------------|
| **TOON schema validation** | Not required; â€œvalidation beyond parseabilityâ€ deferred. | **Mandatory.** Header fields that imply schema (e.g. references to tables/columns) must be checked against TOON (or install SQLâ€“derived schema). Structural conflicts â†’ **reject** (P0). |
| **Field preservation** | All header key/values stored as property rows without classification. | **Mandatory.** Apply Thread 1002 field matrix: lossless vs semantic-equivalence vs lossy vs never-projected. DB projection and round-trip behavior follow these categories. |
| **P0 vs P1 conflict detection** | Not distinguished; â€œmark partialâ€ and â€œexplicit stateâ€ only. | **Mandatory.** P0 conflicts (header vs TOON, invalid version) â†’ **reject** ingestion for that file. P1 conflicts (header vs DB state, concurrent edit) â†’ **warn** and optionally flag; do not block projection unless policy requires it. |
| **Concurrent edit detection** | Not specified. | **Required.** Before writing to DB, check file mtime (or equivalent) has not changed since read; if changed, abort or mark conflict and do not overwrite without explicit policy. |
| **Version compatibility** | Not specified. | **Required.** Check header version (e.g. lupopedia.version / system_version) against a compatibility matrix; reject incompatible, warn deprecated. |
| **Performance / batching** | No caching or batching specified. | **Required for P0 design.** TOON caching (keyed by TOON path + mtime), batch validation where multiple headers reference same TOON, and incremental validation (e.g. skip unchanged files by hash) are part of the design so that P0 validation remains feasible at scale. |
| **Structural validation** | Minimal (required blocks/fields); missing required did not block. | **Stricter.** Required blocks and required fields in lupopedia.headers cause **reject** if absent for artifact types that require them (per LUPOPEDIA_HEADERS_FORMAT). Partial headers still get explicit state, but â€œcontinue with limited projectionâ€ is only when no P0 conflict. |
| **Failure/fallback** | Malformed YAML â†’ minimal error row; partial â†’ store what is present. | **Layered.** Malformed YAML â†’ reject or minimal error row + no DB projection of content. Partial + P0 conflict â†’ reject. Partial + no P0 conflict â†’ record validation state and optionally limited projection. Invalid namespace / field drift â†’ warn and apply taxonomy where defined; reject only when doctrine says error (e.g. table doc). |

---

## 3. Revised P0 Ingestion Flow

The following flow **supersedes** the simplified 6-step flow in 240000 for implementation and validation behavior. Discovery and path/entity identity logic are unchanged; steps 2â€“5 are expanded and reordered to include bounded-authority validation and conflict handling.

**Step 1: Discover files**  
(Unchanged.) Recursive enumeration of `lupo-channels/66/**/*.md`; sort paths for determinism; output ordered list of candidate files.

**Step 2: Parse**  
For each file: read content; extract YAML between first `---` and next `---`; parse into structure. On parse failure â†’ hand off to failure path (no DB projection of content; optional minimal error row per Â§7). Preserve all blocks and key/values; no field classification yet.

**Step 3: Structural validation**  
Verify at least one block present; for lupopedia.headers verify required fields per LUPOPEDIA_HEADERS_FORMAT. Verify canonical block order (reorder in memory if needed). If required blocks/fields missing for artifact type â†’ **reject** (P0) for that file unless policy explicitly allows â€œpartial with state only.â€ Output: parsed structure + structural pass/fail.

**Step 4: Bounded-authority validation**  
Resolve header fields that imply schema (e.g. any reference to tables or columns). Load TOON (or schema derived from install SQL) for referenced entities. Run **header vs TOON** checks: field existence, type consistency where applicable. Validate **header version** against compatibility matrix. Validate **actor_id** against registry if present. Any P0 failure here â†’ **reject** (no DB projection of content for that file); log conflict and reason.

**Step 5: TOON/schema comparison**  
Explicit comparison step: header field references vs TOON table/column definitions. Detect structural conflicts (e.g. header claims a column or table that does not exist in TOON). Outcome: pass â†’ continue; fail â†’ **reject** and log.

**Step 6: Field classification**  
Apply Thread 1002 field-preservation matrix to parsed lupopedia.headers (and other blocks as needed). Tag each property as lossless / semantic-equivalence / lossy / never-projected. Prepare metadata row payloads with preservation rules applied (lossless exact; semantic normalized; lossy stored as display-only; never-projected omitted from authoritative projection).

**Step 7: DB projection**  
For files that passed P0 validation: compute entity_id (deterministic from file_path_from_root); replace existing metadata rows for that entity (delete or soft-delete then insert). Write root â†’ block â†’ property (and edge) rows. Apply **concurrent-edit check** immediately before write: if file mtime changed since Step 2 read â†’ **abort** write and mark conflict (or follow policy: flag and optionally skip overwrite). All timestamps and metadata_id allocation as in 240000; no DB-side logic.

**Step 8: Conflict outcome handling**  
Per file: if P0 reject â†’ no projection; log and continue to next file. If P1 conflict (e.g. header vs DB state, or concurrent edit detected) â†’ optionally still project but add conflict flag in metadata and log. Return structured result: ingested / rejected / conflict_flagged.

---

## 4. Conflict Detection Layer

Thread 1001 now incorporates the following in line with Thread 1002 evidence and gate.

**Header vs TOON checks (P0)**  
- **When:** After parse and structural validation, before DB projection.  
- **What:** Compare header field references (e.g. channel_id, actor_id, or any table/column references implied by artifact_type or doctrine) to TOON (or install SQLâ€“derived) schema.  
- **Action:** If any structural conflict (e.g. reference to non-existent column/table) â†’ **reject** ingestion for that file; log conflict type and identifier; do not write content to lupo_metadata for that entity.

**Header version compatibility (P0/P1)**  
- **When:** During bounded-authority validation.  
- **What:** Compare lupopedia.version / system_version to a compatibility matrix (e.g. supported range for current system).  
- **Action:** Incompatible â†’ **reject** (P0). Deprecated but still supported â†’ **warn** (P1); continue.

**Header vs DB divergence (P1)**  
- **When:** Optionally at read time or pre-write when comparing existing metadata to new header.  
- **What:** Compare header timestamps or version to existing DB row state.  
- **Action:** **Warn** and optionally set divergence flag in metadata; do not block projection unless policy says otherwise.

**Concurrent edit detection (P1)**  
- **When:** Immediately before DB write for a file.  
- **What:** Compare file mtime (or content hash) at write to value at read (Step 2).  
- **Action:** If changed â†’ **abort** write for that file (or mark conflict and skip overwrite per policy); log; do not silent overwrite.

**P0 reject vs P1 warn**  
- **P0 â†’ reject:** Malformed YAML (optional: minimal error row only), structural validation failure, TOON conflict, version incompatible. No content projection.  
- **P1 â†’ warn / flag:** Version deprecated, header vs DB divergence, concurrent edit. Projection may still occur with conflict/divergence flag; audit trail required.

---

## 5. Field Preservation Layer

The accepted matrix from Thread 1002 (040000, approved in 050000) is incorporated as follows.

**Lossless fields**  
`file_path_from_root`, `web_path`, `channel_id`, `thread_id`, `actor_id`, `delegation_chain`, `artifact_type`, `artifact_kind`, `purpose`.  
- **Ingestion:** Stored exactly as in header; no normalization that changes meaning.  
- **Round-trip:** Export must reproduce these exactly when reconstructing header from DB.

**Semantic-equivalence fields**  
`lupopedia.version`, `lupopedia.schema`, `system_version`, `last_modified_utc`, `namespace`, `tags`.  
- **Ingestion:** Normalization allowed (e.g. UTC format, tag sort/dedup, namespace taxonomy case).  
- **Round-trip:** Export must preserve semantic meaning; exact string match not required.

**Lossy / display-only fields**  
`actor_name`, `channel_name`, `thread_name`, `title`, `traits`, `mood_rgb`.  
- **Ingestion:** Stored as display metadata only; may be resolved from IDs on export.  
- **Round-trip:** May be recomputed from registry or other source; not authoritative in header reconstruction.

**Never projected as authoritative**  
YAML comments, whitespace, block ordering beyond canonical, formatting.  
- **Ingestion:** Not stored in DB as authoritative content.  
- **Round-trip:** Not reconstructed from DB as source of truth.

**Effect on ingestion design**  
- Parser and normalizer must classify each header property into one of the above before building metadata rows.  
- DB projection step writes lossless and semantic-equivalence (and optionally lossy) into lupo_metadata; never-projected are omitted from authoritative projection.  
- Any export or round-trip feature (out of P0 scope but anticipated) must use the same matrix so that loss is explicit and documented.

---

## 6. Performance Strategy

**Safe optimizations now part of P0 design**

- **TOON caching:** Load TOON (or schema) once per (TOON path + mtime); reuse in memory for the run. Cache invalidation: TOON file change. Reduces repeated I/O when many headers reference the same table/docs.  
- **Batch validation:** Group files that reference the same TOON(s) and run TOON comparison once per group.  
- **Incremental validation:** For runs that support it, skip files whose content hash (or mtime) is unchanged since last successful validation; optional and must be documented so that â€œfull re-runâ€ remains available.

**Forbidden optimizations**

- **Skip TOON validation:** Would violate P0 safety; structural conflicts could be projected.  
- **Assume registry/schema valid:** actor_id and schema references must be checked.  
- **Cache validation results indefinitely:** Must invalidate when TOON or install SQL changes; cache key must include schema/TOON version or mtime.

---

## 7. Updated Failure / Fallback Logic

| Scenario | Action | Notes |
|----------|--------|------|
| **Malformed YAML** | **Reject** content projection. Optionally write minimal error row (entity_type/entity_id + parse_error flag). Do not write block/property tree. Log message. | No silent skip; state explicit. |
| **Partial headers (missing required blocks/fields)** | If artifact type requires them â†’ **reject**. If policy allows â€œpartial with stateâ€ â†’ record validation_warnings and optionally limited projection (e.g. root + one block with partial flag). | Per LUPOPEDIA_HEADERS_FORMAT and artifact_type. |
| **Missing edge targets** | Store edge anyway; right_object_id from declared path (deterministic). Optionally set â€œtarget not verifiedâ€ flag. **Do not block** ingestion. | Unchanged from 240000. |
| **Invalid namespace / field drift** | If table doc and namespace missing or invalid â†’ **reject** (per doctrine). If nonâ€“table doc â†’ **warn** and optionally normalize to taxonomy if possible; else flag. | Align with Thread 1003 implications when applied. |
| **Structural conflict with TOON** | **Reject** ingestion for that file. Log conflict type and fields. No DB projection of content. | P0. |
| **Version incompatibility** | **Reject** if outside supported range. **Warn** if deprecated. Log version and matrix result. | P0 reject / P1 warn. |
| **Concurrent edit detected** | **Abort** write for that file (or mark conflict and skip overwrite). Log. Do not overwrite. | P1; no silent overwrite. |

**Explicit outcomes**

- **Reject:** No content projection; optional minimal error/conflict row; pipeline continues to next file.  
- **Warn:** Log and optionally set metadata flag; projection may proceed.  
- **Record conflict state:** Store conflict_detected (and type) in metadata or audit; allow downstream to filter or report.  
- **Continue with limited projection:** Only when no P0 failure and policy allows partial (e.g. missing optional blocks).

---

## 8. Thread 1002 Inheritance Summary

Thread 1001 now inherits from Thread 1002 the following:

- **Authority hierarchy:** Headers are P2 declarative artifact truth; TOON/schema are P1; install SQL P0. Conflict resolution: structural (TOON) wins over declarative (header) for schema conflicts.  
- **Conflict precedence:** Header vs TOON â†’ reject. Header vs DB state â†’ warn/flag. Concurrent edit â†’ abort write or flag.  
- **Round-trip expectations:** Semantic equivalence for required and semantic-equivalence fields; lossy and never-projected are explicit; no silent loss of meaning.  
- **Field preservation categories:** Lossless, semantic-equivalence, lossy, never-projected as in Â§5; applied during ingestion and (when implemented) export.  
- **Performance requirements:** TOON caching and batch/incremental validation are part of the design so P0 validation remains safe and feasible at scale.

---

## 9. Updated P0 / P1 / P2 Breakdown

**P0 (must exist before ingestion is considered safe)**

- Parse and structural validation with required blocks/fields.  
- TOON (or schema) comparison; reject on structural conflict.  
- Field classification matrix applied to header properties before projection.  
- Lossless and semantic-equivalence handling in DB projection.  
- Version compatibility check; reject if incompatible.  
- Concurrent-edit check before write; abort or flag, no silent overwrite.  
- Explicit reject vs warn behavior; no content projection on P0 reject.  
- Idempotent replace semantics and deterministic entity_id (unchanged from 240000).

**P1 (should add soon after P0)**

- TOON caching and batch validation.  
- Incremental validation (hash/mtime skip) with full re-run option.  
- P1 conflict detection (header vs DB state) and divergence flagging.  
- Optional lupo_edges projection with strict Channel 66 conventions (as in 240000).  
- Conflict/audit logging and structured result (ingested / rejected / conflict_flagged).

**P2 (future hardening)**

- Export (DB â†’ YAML/file) and round-trip tests against field matrix.  
- Advanced concurrent-edit resolution (e.g. merge strategies).  
- Namespace/collection normalization and validation (Thread 1003) integrated where applicable.

---

## 10. Final Recommendation

- **Thread 1001 is now ready for implementation planning** with this revised design. The P0 scope (Channel 66, lupo_metadata, deterministic entity_id, replace semantics) is unchanged; the validation and safety layers are specified so that implementation can follow the bounded authority model and the Thread 1002 gate requirements.  
- **Another LILITH review** of this revised design is **optional but recommended** as an implementation-gate pass (e.g. confirm TOON scope, reject/warn thresholds, and conflict handling match expectations).  
- **WOLFIE** may close Thread 1002 separately when he judges resolution is documented; Thread 1001 does not depend on that closure for this design to stand.

**Next actor**

- **LILITH** â€” if an implementation-gate review of this revised design is desired before implementation planning.  
- **HEPHAESTUS** â€” if a narrower technical artifact (e.g. TOON comparison spec or validation checklist) is needed next.  
- **WOLFIE** â€” only if this revision surfaces an architectural contradiction (none identified).

---

*End of revised P0 ingestion design â€” Thread 1001. Working material only. Not canonical doctrine.*

