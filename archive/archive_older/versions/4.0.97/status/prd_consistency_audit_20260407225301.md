---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260407225301"
  file_path_from_root: "docs/versions/4.0.96/status/PRD_CONSISTENCY_AUDIT_20260407225301.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/status/PRD_CONSISTENCY_AUDIT_20260407225301.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: audit
  thread_id: ""
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
# PRD Consistency Audit — Constitutional Rules Applied

**Audit scope:** All `docs/prd/*.md` files (54 files)  
**Constitutional anchor:** `00_root_constitutional_system_requirements.md`  
**Previous audit:** `PRD_REVIEW_DISCREPANCIES_AND_IMPROVEMENTS_20260407224750.md` (LILITH, actor_id 2)  
**Temporal anchor:** `20260407225301` (from `bin/temporal_anchor.json`; last session end)  
**Note:** Operator should run `python bin/tick.py` to refresh the anchor before next session writes.

---

## Method

Constitutional rules from PRD 00 were applied as a checklist to each PRD file. Findings are organized by rule category. This audit does not duplicate LILITH's prior findings; it cross-references them and adds new findings from a full per-file sweep.

---

## Section 1: file_path_from_root — Leading Slash Violations

**Rule:** PRD 00 §7 / PRD 16 — `file_path_from_root` must be repo-relative with NO leading slash.

```
VIOLATION: 27_installer_requirements.md - file_path_from_root has leading slash
  Value: "/docs/prd/27_installer_requirements.md"
  Fix: Remove the leading "/" → "docs/prd/27_installer_requirements.md"
```

All other 53 files use correct relative paths.

---

## Section 2: Deprecated `version_when_written` Header Field

**Rule:** PRD 16 §435 — `version_when_written` is DEPRECATED; use `when_updated`.

The following 17 PRDs still carry the deprecated field instead of `when_updated`:

```
VIOLATION: 03_truth_knowledge.md - version_when_written in header (deprecated; use when_updated)
VIOLATION: 04_tags_metadata.md - version_when_written in header
VIOLATION: 05_collections_navigation.md - version_when_written in header
VIOLATION: 06_content_management.md - version_when_written in header
VIOLATION: 07_agents_faucets.md - version_when_written in header
VIOLATION: 08_governance_rules.md - version_when_written in header
VIOLATION: 09_federation_sync.md - version_when_written in header
VIOLATION: 10_tasks_workflow.md - version_when_written in header
VIOLATION: 11_analytics_tracking.md - version_when_written in header
VIOLATION: 12_api_integration.md - version_when_written in header
VIOLATION: 13_crafty_integration.md - version_when_written in header
VIOLATION: 14_system_operations.md - version_when_written in header
VIOLATION: 15_actors.md - version_when_written in header
VIOLATION: 15_temporal_system.md - version_when_written in header
VIOLATION: 23_health_check_asclepius_prd.md - version_when_written in header
VIOLATION: 36_rose_multi_persona_synthetic_dialog.md - version_when_written in header
VIOLATION: 37_kairos_channel_memory_consolidation.md - version_when_written in header
```

**Fix:** Replace `version_when_written:` with `when_updated:` in each header.

---

## Section 3: Malformed Timestamp in Header

**Rule:** PRD 00 §3.5 — timestamps must be 14-digit packed UTC `YYYYMMDDHHIISS`.

```
VIOLATION: 08_actors.md - last_modified_utc is 8 digits only ('20260331'), not 14
  Value: last_modified_utc: '20260331'
  Fix: '20260331000000' (or correct 14-digit UTC value)
```

---

## Section 4: DECIMAL Types — Should Be Integer Hundredths

**Rule:** PRD 00 §3.6 / PRD 38 §5.2 — Score/weight columns MUST be portable integer types (e.g., `INT` as hundredths). `DECIMAL` is forbidden in canonical DDL for portability.

PRD 38 establishes the pattern: `weight_hundredths INT NOT NULL DEFAULT 100` (comment: 100 = weight 1.00).

```
VIOLATION: 02_data_model.md - DECIMAL column types in SQL examples
  - lupo_contexts: weight_score decimal(5,2) NOT NULL DEFAULT 0.00 (line 95)
  - lupo_truth_answers: confidence_score decimal(3,2) NOT NULL DEFAULT 0.50 (line 146)
  - lupo_truth_evidence: reliability_score decimal(3,2) NOT NULL DEFAULT 0.50 (line 257)
  - lupo_truth_evidence: relevance_score decimal(3,2) NOT NULL DEFAULT 0.50 (line 258)
  Fix: Convert to INT hundredths (e.g., confidence_hundredths INT NOT NULL DEFAULT 50)

VIOLATION: 03_truth_knowledge.md - DECIMAL column types in table spec
  - confidence_score DECIMAL(3,2) (line 127)
  - credibility_score DECIMAL(3,2) (line 157)
  Fix: Same as above — integer hundredths

VIOLATION: 04_tags_metadata.md - DECIMAL edge weight in lupo_edges spec
  - weight DECIMAL(5,2) YES 1.0 (line 202)
  Fix: weight_hundredths INT NOT NULL DEFAULT 100 (per PRD 38 §5.2 pattern)

VIOLATION: 09_federation_sync.md - DECIMAL trust level
  - trust_level DECIMAL(3,2) (line 130)
  Fix: trust_level_hundredths INT NOT NULL DEFAULT 50

VIOLATION: 11_analytics_tracking.md - DECIMAL metric columns
  - average_duration DECIMAL(8,2) (line 212)
  - bounce_rate DECIMAL(5,2) (line 213)
  Fix: Convert to INT (e.g., duration_hundredths, bounce_rate_hundredths) or documented INT alternatives
```

**Note:** This was flagged in the LILITH audit for PRD 38 itself (now fixed in PRD 38). The violation persists in PRDs 02, 03, 04, 09, 11 which predate the PRD 38 revision.

---

## Section 5: PK Naming Violations — `<singular_table_name>_id` Rule

**Rule:** PRD 00 §9.7 — PKs MUST be named `<singular_table_name>_id`. Never just `id`.

```
VIOLATION: 01_core_identity.md - lupo_actor_memory table uses PK 'memory_id'
  Singular table name is 'actor_memory' → PK should be 'actor_memory_id'
  Location: Table detail section line 240; summary table line 224
  Propagated to: 09_federation_sync.md (line 82), 19_garbage_collection_system.md (line 274),
                 24_cli_interface_prd.md (lines 160, 189, 205, 325, 326, 427),
                 37_kairos_channel_memory_consolidation.md (line 173)
  Fix: Rename PK to 'actor_memory_id' across all referencing PRDs and install SQL
```

**Note:** `lupo_banned_actors` uses `ban_id` — the singular table name is `banned_actor` so `banned_actor_id` would be correct. If the table is intended as `lupo_bans`, then `ban_id` is correct. This needs clarification in PRD 01.

---

## Section 6: Legacy `.toon` Path References (Deprecated)

**Rule:** PRD 00 §6 / §9.9 — Legacy `.toon.json` paths are deprecated. Use `database/lupopedia/json/<table>.json`.

```
VIOLATION: 05_auth_user_actor_agent_transformation.md - Legacy .toon.json reference in edges
  - to: "database/lupopedia/toon/lupo_actor_auth_users.toon.json" (line 132)
  Fix: → "database/lupopedia/json/lupo_actor_auth_users.json"

VIOLATION: 18_channel_chat_display.md - Legacy .toon reference in body
  - "database/lupopedia/toon/lupo_dialog_messages.toon" (line 122)
  Fix: → "database/lupopedia/json/lupo_dialog_messages.json"

VIOLATION: 36_rose_multi_persona_synthetic_dialog.md - Multiple legacy .toon references
  - Edge: to: "database/lupopedia/toon/lupo_dialog_messages.toon" (line 60)
  - Body: "database/lupopedia/toon/lupo_dialog_messages.toon" (line 206)
  - Body: "Schema TOON: database/lupopedia/toon/lupo_dialog_messages.toon" (line 285)
  Fix: Update all three to json/ path

VIOLATION: 37_kairos_channel_memory_consolidation.md - Multiple legacy .toon references
  - Edge: to: "database/lupopedia/toon/lupo_actor_memory.toon" (line 60)
  - Edge: to: "database/lupopedia/toon/lupo_edges.toon" (line 64)
  - Edge: to: "database/lupopedia/toon/lupo_dialog_messages.toon" (line 68)
  - Body: "database/lupopedia/toon/lupo_actor_memory.toon" (line 171)
  - Body: "database/lupopedia/toon/lupo_edges.toon" (line 175)
  Fix: Update all five to json/ path
```

---

## Section 7: Edge Dimensions — Missing Context, Status, Direction

**Rule:** PRD 00 footer / Edge doctrine — Every edge MUST have 4 dimensions: type, context, status, direction.

```
VIOLATION: 04_tags_metadata.md - lupo_edges table schema missing 3 of 4 edge dimensions
  Present:  edge_type_id (via edge_types table)
  Missing:  edge_context, edge_status, edge_direction columns
  Also:     Missing review_reason (required when status = 'needs_review')
  Fix: Add columns per PRD 38 §5.2 pattern:
       edge_context VARCHAR(32) NOT NULL DEFAULT 'system_generated'
       edge_status VARCHAR(32) NOT NULL DEFAULT 'supported'
       edge_direction VARCHAR(16) NOT NULL DEFAULT 'unidirectional'
       review_reason VARCHAR(64) DEFAULT NULL

IMPROVEMENT: 02_data_model.md - lupo_edges not referenced; relies on PRD 04 definition
  The data model PRD should explicitly reference PRD 04 and note 4-dimension requirement
  Priority: Med
```

**Compliant:** PRD 38 `lupo_memory_edges` correctly includes all 4 dimensions: `edge_type`, `edge_context`, `edge_status`, `edge_direction` + `review_reason`. PRD 38 is the model for how edges should be defined.

---

## Section 8: Hard DELETE — Soft Delete Pattern

**Rule:** PRD 00 §9.8 — All soft deletes MUST use `is_deleted` + `deleted_ymdhis`. Never hard `DELETE` on production rows.

```
IMPROVEMENT: 01_core_identity.md - Session cleanup uses hard DELETE (line 631)
  Code: DELETE FROM lupo_sessions WHERE expires_ymdhis < CURRENT_UTC AND is_deleted=0
  Note: Sessions may qualify for a hard-delete exemption (transient data, not lineage-bearing),
        but no explicit exemption is documented in PRD 00 or PRD 14.
  Fix: Either document an explicit session-cleanup exception in PRD 00 §9.8 or PRD 14,
       OR change to soft delete: UPDATE lupo_sessions SET is_deleted=1, deleted_ymdhis=... WHERE ...
  Priority: Med (LILITH flagged this in previous audit; still unresolved)
```

---

## Section 9: SQL Example Using `generate_id()` Instead of `IdGenerator::generate()`

**Rule:** PRD 00 §3.2 — All PKs generated via `IdGenerator::generate()`. No DB-generated IDs.

```
VIOLATION: 02_data_model.md - SQL INSERT example uses generate_id() function (line 232)
  Code: VALUES (generate_id(), 'truth_question', 12345, ...)
  generate_id() is not the canonical pattern; this implies a DB-side function.
  Fix: Remove from SQL example; add PHP code showing IdGenerator::generate() call before INSERT
```

---

## Section 10: Deprecated PRD 08 Not Flagged in Navigation Files

**Rule:** PRD 08 (08_actors.md) is SUPERSEDED by PRD 15 (15_actors.md). Navigation files should reflect this.

```
IMPROVEMENT: PRD_INDEX.md - Lists 08_actors.md as "Actor system and roles" without noting SUPERSEDED status (line 74)
  Fix: Add "(SUPERSEDED — see 15_actors.md)" annotation
  Priority: High (misleads new contributors)

IMPROVEMENT: 29_project_structure.md - Lists 08_actors.md as "Doctrine" without superseded note (line 235)
  Fix: Add "(SUPERSEDED — canonical: 15_actors.md)" annotation
  Priority: Med
```

**Compliant:** `08_actors.md` itself correctly declares `> **SUPERSEDED:** Canonical actor PRD is 15_actors.md` in its body. `15_actors.md` line 380 also notes supersession.

---

## Section 11: Memory Model Consistency

**Rule:** PRD 00 §5.7 / PRD 38 — DB as source of truth; filesystem is read-only export mirror.

**Status after this audit:**

| PRD | Memory model described | Compliant? |
|-----|------------------------|------------|
| 01_core_identity.md | DB (`lupo_memory_nodes`) + export mirror; `memory.json` DEPRECATED | ✅ |
| 07_agents_faucets.md | `memory.json` DEPRECATED note present; points to unified model | ✅ |
| 15_actors.md | DEPRECATED note; `memory/YYYY/MM/` as export mirror | ✅ |
| 24_actor_onboarding_flow.md | DB + export; `memory.json` DEPRECATED | ✅ |
| PRD_AGENT_DEFINITION_MODEL.md | Full section on Root Memory Node (4.0.96+); `memory.json` DEPRECATED | ✅ |
| 37_kairos_channel_memory_consolidation.md | KAIROS writes to DB; export via MemoryExportService | ✅ |
| 38_memory_unification.md | Canonical authority; DB-first, export mirror | ✅ |

```
MISSING: 09_federation_sync.md - Still uses memory_id (old PK) in actor_memory reference (line 82)
  This suggests the federation sync PRD has not been updated to reflect PRD 38 schema.
  Requires: Update memory table PK reference to actor_memory_id (pending §5 fix)
  Priority: Med

MISSING: Several older PRDs (03, 06, 10, 12, 13, 14) make no mention of the memory graph at all.
  These PRDs predate PRD 38 and do not need memory model sections unless they interact
  with memory operations. No action required unless their scope includes memory reads/writes.
```

---

## Section 12: IdGenerator Suffix Wording

**Rule:** PRD 00 §9.7 / LILITH audit finding — `IdGenerator` uses a 4-digit CSPRNG suffix, not a monotonic sequence.

```
IMPROVEMENT: 38_memory_unification.md - Section 4.0 correctly says "4-digit suffix (CSPRNG-derived, not a monotonic sequence)" ✅
IMPROVEMENT: 01_core_identity.md - Actor ID flow description (line 175) says "4 random digits" ✅

IMPROVEMENT: 24_actor_onboarding_flow.md - Section 5 says "4 random digits" ✅
  These are all consistent. No violation here; LILITH's prior finding is resolved in these PRDs.
```

---

## Section 13: PRD_INDEX.md Version Banner

```
IMPROVEMENT: PRD_INDEX.md - Body banner reads "Version: 4.0.89" and "Effective: Version 4.0.89+"
  Header last_modified_utc is 20260406154744, indicating the file was touched at 4.0.95/96 era.
  The banner text was not updated.
  Fix: Update body banner to "Version: 4.0.96" (or use "Tracks: main / current patch")
  Priority: Low
```

---

## Section 14: Agent vs Actor — PRD 07 Filesystem Doctrine

**Rule:** PRD 00 §9.16 — Agents are filesystem-based; DB stores only runtime state.

```
COMPLIANT: 07_agents_faucets.md - Explicitly states filesystem-based agent discovery; DB stores only
  status, version, file_hash, activation_state, etc. ✅

COMPLIANT: 01_core_identity.md - Agent/Actor distinction table clearly separates filesystem (agents)
  from DB (actors) ✅

COMPLIANT: 15_actors.md - Correct department-scoped model; not one-to-one user→actor ✅

IMPROVEMENT: 08_actors.md (SUPERSEDED) - Section 3 still describes "One-Auth_User-at-a-Time Lease Rule"
  which implies one-to-one user→actor ownership — this is the old model.
  Since the file is SUPERSEDED and retained for history only, no fix required;
  but contributors should be warned not to use this as a reference.
  Priority: Low (file is already marked superseded)
```

---

## Section 15: Department Model

**Rule:** PRD 05, PRD 15, PRD 25 — Actors belong to departments; act-as is department-intersection, not one-to-one user ownership.

```
COMPLIANT: 15_actors.md - "Actors belong to departments — not to individual users" clearly stated ✅
COMPLIANT: 05_auth_user_actor_agent_transformation.md - Department-first act-as model ✅
COMPLIANT: 25_departments_system.md - Department structure documented ✅

IMPROVEMENT: 24_actor_onboarding_flow.md - "Lease Enforcement" section (line 91) describes exclusive
  lease as "is_primary = 1 in lupo_actor_auth_users" which could be misread as one-to-one ownership.
  The text should clarify this is an active session lease, not department-model ownership.
  Priority: Low
```

---

## Section 16: CLI Interface — PRD 24 as Canonical

**Rule:** PRD 24 (`24_cli_interface_prd.md`) is canonical for CLI. No ad-hoc CLI entrypoints.

```
COMPLIANT: 24_cli_interface_prd.md - Defines canonical CLI surface ✅

IMPROVEMENT: 14_system_operations.md - May reference system-level operations; not verified for
  ad-hoc CLI entrypoints in this sweep. Recommend spot-check against PRD 24.
  Priority: Low
```

---

## Section 17: LUPOPEDIA HEADERS Presence

**Rule:** PRD 16 — `lupopedia.headers:` block required on all `.md`, `.php`, `.js`, `.py`, `.sql`, `.html` files.

All 54 `.md` files in `docs/prd/` have `lupopedia.headers:` frontmatter. ✅

---

## Section 18: No FOREIGN KEY / REFERENCES in SQL

**Rule:** PRD 00 §3.1 — No `FOREIGN KEY` or `REFERENCES` in SQL.

No PRD files contain an actual `FOREIGN KEY` DDL clause. The text "Foreign reference" appears in `04_tags_metadata.md` line 197 as a **prose** label for `edge_type_id`, not a SQL `REFERENCES` clause.

```
IMPROVEMENT: 04_tags_metadata.md - Column description says "Foreign reference to lupo_edge_types"
  This prose is misleading — it implies a FOREIGN KEY constraint, which is constitutionally forbidden.
  Fix: Change to "Application-managed reference to lupo_edge_types" to prevent confusion
  Priority: Low
```

---

## Section 19: No AUTO_INCREMENT or SERIAL

**Rule:** PRD 00 §3.2 — No `AUTO_INCREMENT` or `SERIAL`.

No PRD SQL examples use `AUTO_INCREMENT` or `SERIAL` as actual DDL. All references are in prohibition/documentation context. ✅

---

## Section 20: Cross-Reference with LILITH Audit

The previous audit (`PRD_REVIEW_DISCREPANCIES_AND_IMPROVEMENTS_20260407224750.md`) identified:

| LILITH Finding | Status in This Audit |
|----------------|----------------------|
| PRD_INDEX lists 30+ PRDs but README claims 14 | Confirmed — README stale; IMPROVEMENT (not new finding) |
| Duplicate numeric prefixes in index (02a/02b confusion) | Confirmed — organizational issue; Low priority |
| PRD_INDEX banner says 4.0.89 | Confirmed — IMPROVEMENT §13 above |
| Memory model: 3 parallel stories (install SQL / PRD 01/15/24 / PRD 38) | Partially resolved — PRD 38 now canonical; PRDs 01/15/24 updated with DEPRECATED notes |
| PRD 38 generated column issue | Resolved in PRD 38 revision ✅ |
| PRD 38 `fetchOne` vs `fetchRow` | Resolved in PRD 38 revision ✅ |
| Hard DELETE in session cleanup (01_core_identity.md) | Still unresolved — see §8 above |
| DECIMAL in PRD 38 | Resolved in PRD 38 ✅; persists in PRDs 02, 03, 04, 09, 11 |
| `IdGenerator` suffix wording | Resolved in updated PRDs ✅ |

**New findings in this audit not in LILITH's report:**

- `27_installer_requirements.md` leading slash in `file_path_from_root`
- 17 PRDs with deprecated `version_when_written` field
- `08_actors.md` 8-digit malformed timestamp
- `04_tags_metadata.md` `lupo_edges` missing 3 edge dimensions
- `02_data_model.md` `generate_id()` in SQL example
- Legacy `.toon` path references in PRDs 05, 18, 36, 37
- `PRD_INDEX.md` and `29_project_structure.md` not flagging PRD 08 as superseded

---

## Summary: Three Most Critical Issues

### 1. `lupo_edges` Missing Edge Dimensions (HIGH — Constitutional)

`04_tags_metadata.md` defines `lupo_edges` without `edge_context`, `edge_status`, `edge_direction`. PRD 00's edge doctrine (4.0.96 footer section) requires all four. Since `lupo_edges` is the universal relationship table used across the entire system, this schema gap propagates to all edge-using features. This is the most structurally significant violation because `lupo_memory_edges` (PRD 38) has the correct 4-dimension model, but the general `lupo_edges` table — used by KAIROS, ROSE, federation, and all system relationships — does not.

### 2. `memory_id` PK Naming Violation (HIGH — Constitutional)

`lupo_actor_memory.memory_id` violates §9.7 PK naming. The correct PK name is `actor_memory_id`. This propagates to 5 PRDs (01, 09, 19, 24_cli, 37) and ultimately to `install_new_lupopedia.sql`. Any application code using `memory_id` will need updating when this is corrected.

### 3. DECIMAL Score Columns in 5 PRDs (HIGH — Constitutional)

PRDs 02, 03, 04, 09, 11 define score/weight/trust columns as `DECIMAL`, violating the database-neutral integer doctrine established by PRD 38. These PRDs document the canonical table shapes for `lupo_truth_questions`, `lupo_truth_answers`, `lupo_truth_evidence`, `lupo_edges`, and analytics tables. If these PRD schemas are reflected in `install_new_lupopedia.sql`, the install SQL also contains this violation.

---

## PRDs That Need Immediate Updates

| PRD | Primary issue | Action |
|-----|---------------|--------|
| `04_tags_metadata.md` | `lupo_edges` missing 3 edge dimensions; DECIMAL weight | Add edge_context/status/direction columns; convert weight to INT hundredths |
| `01_core_identity.md` | PK `memory_id` should be `actor_memory_id`; hard DELETE in session cleanup | Rename PK; document or resolve session delete exemption |
| `02_data_model.md` | DECIMAL score columns; `generate_id()` in SQL example | Convert to INT hundredths; fix SQL example |
| `03_truth_knowledge.md` | DECIMAL score columns | Convert to INT hundredths |
| `37_kairos_channel_memory_consolidation.md` | 5 legacy `.toon` path references; deprecated `version_when_written` | Update paths to `json/`; rename header field |
| `36_rose_multi_persona_synthetic_dialog.md` | 3 legacy `.toon` references; deprecated `version_when_written` | Update paths; rename header field |
| `27_installer_requirements.md` | Leading slash in `file_path_from_root` | Remove leading slash |
| `PRD_INDEX.md` | PRD 08 not flagged as superseded; version banner stale | Annotate; update banner |

---

## PRDs That Can Be Marked DEPRECATED

| PRD | Reason |
|-----|--------|
| `08_actors.md` | Already SUPERSEDED by `15_actors.md`; body and header both note this. No content referenced as authoritative. Recommend: add `status: deprecated` to header, move to `docs/prd/deprecated/` if desired |

**No other PRDs are candidates for deprecation** at this time. All numbered PRDs 00–40 describe active or planned system features. Note that `WHAT_TO_DO_NEXT.md` is a working note, not a PRD — it may warrant its own classification but is not a deprecation candidate.

---

*Constitutional rules applied per PRD 00 §3.1, §3.2, §3.5, §3.6, §5.1–5.7, §6, §9.7, §9.8, §9.9, §9.16, §9.17, PRD 16 (headers), PRD 38 (memory unification and integer-scaled weights).*
