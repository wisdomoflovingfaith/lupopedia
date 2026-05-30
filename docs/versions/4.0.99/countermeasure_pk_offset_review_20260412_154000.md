---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.0.99/countermeasure_pk_offset_review_20260412_154000.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.0.99/countermeasure_pk_offset_review_20260412_154000.md
  status: draft
  when_updated: '20260513033046'
  trust_tier: draft
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: countermeasure
  artifact_kind: review
  channel_key: development
  federation_node_id: 0
  thread_key: pk-offset-review
  lupopedia.schema: countermeasure
  prd_cluster: null
  title: 'Countermeasure: PK Offset Rule Review'
  summary: Adversarial review of deterministic PK offset (calendar year - 1000) as trust encoding mechanism
---

# Countermeasure: PK Offset Rule Review

## The Rule Being Reviewed

Subtract 1000 from the calendar year to create a deterministic PK value ("display year") for canonical (trusted) records. Example: 2026 (calendar) → 1026 (display). Lower PK bands (e.g., 1026) are interpreted as higher trust than higher bands (e.g., 2026). Canonical tier uses display year; staging tier uses real year. KIROS consolidates staging (2026) to canonical (1026) and adds a `consolidated_to` edge.

## Challenge Findings

- **Numeric magnitude ≠ trust:** Using a lower number as a proxy for trust is arbitrary. The system seed (ID 1) is lower than 1026, but not all low numbers are more trusted. Trust is a semantic, not a numeric, property.
- **Deterministic PK from date:** Subtracting 1000 is deterministic, but it is not self-explanatory. Developers must remember the offset rule. Year rollovers (Dec 2026 → Jan 2027) create new canonical bands (1026, 1027), potentially fragmenting trust anchors for long-lived discussions.
- **Parallel PK spaces:** Having both 1026 and 2026 for the same logical entity creates confusion. Queries, migrations, and audits must always check both bands and follow `consolidated_to` edges. This increases complexity and risk of orphaned or divergent records.
- **KIROS consolidation risks:** If consolidation fails, runs twice, or is triggered on an active session, data loss or duplication can occur. There is no built-in safeguard against these scenarios.
- **Query complexity:** All queries must be trust-ladder aware, following edges and checking both PK bands. This is error-prone and increases maintenance burden.
- **Migration burden:** Existing data in 2026 band must be back-consolidated to 1026, requiring careful migration logic and validation.

## Risks Identified
- **CRITICAL:** Query complexity and risk of orphaned/dangling records if consolidation is incomplete or fails.
- **HIGH:** Developer confusion due to parallel PK spaces and non-obvious offset rule.
- **HIGH:** Migration and backfill errors when moving legacy data to canonical band.
- **MEDIUM:** KIROS consolidation race conditions or failures.
- **LOW:** Numeric trust encoding is not semantically meaningful.

## Alternative Evaluation
| Alternative | Description | Pros | Cons |
|-------------|-------------|------|------|
| **A) No offset** | Canonical and staging both use real year, trust encoded in separate `trust_tier` column | Simpler, no math | Doesn't satisfy "lower number = higher trust" aesthetic |
| **B) Prefix trust band** | PK = `[trust_band][timestamp]` where trust_band=1 for canonical, 2 for staging | Clear encoding, no math | Requires string parsing or composite keys |
| **C) Separate tables** | `canonical_memory_nodes` and `staging_memory_nodes` | No PK confusion | Violates normalization, harder queries |
| **D) Keep offset but add safeguards** | Add constraints, validation, and tooling to prevent errors | Preserves design intent | Adds complexity |

## Recommendation

**MODIFY** — If the offset rule is kept, it must be paired with strict validation, migration tooling, and developer documentation. Numeric PK banding alone is not a reliable trust signal. Prefer explicit `trust_tier` fields or trust-band prefixes for clarity. If offset is retained, require:
- Automated validation of PK bands and year offsets
- Migration scripts for legacy data
- Query helpers to abstract band logic
- Documentation and onboarding for developers

## If Keeping Offset: Required Safeguards
- Validator to check all PKs for correct year offset by trust tier
- Migration tool to consolidate and backfill records
- Query abstraction layer to hide band logic from application code
- Documentation for all contributors
- Automated tests for KIROS consolidation edge cases

## Open Questions
- How are cross-year discussions handled? (Dec 2026–Jan 2027)
- What is the rollback plan if consolidation fails?
- How are duplicate or conflicting canonical records resolved?
- Is there a plan for year 3000+ or negative years?

## Verdict
- [ ] APPROVE - offset rule is sound
- [ ] REJECT - offset rule is flawed, use alternative [A/B/C]
- [x] MODIFY - keep offset but add [strict validation, migration, and abstraction]

## Core Question Answer
C) The idea has merit but needs strict validation, migration support, and developer training. Numeric PK banding is not a substitute for explicit trust semantics. Offset is not self-explanatory and increases risk of confusion and error.
