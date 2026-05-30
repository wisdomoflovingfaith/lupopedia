---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260414120000"
  file_path_from_root: "lupo-docs/versions/4.0.99/status/documentation_audit_report.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.99/status/documentation_audit_report.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: null
  artifact_type: documentation
  artifact_kind: audit_report
  thread_id: "documentation-audit-4099"
  content_id: null
  pk_id: null
  pk_slug: "documentation-audit-report-4099"
  title: "Documentation Audit Report — Lupopedia 4.0.99"
  status: "active"
  parent_pk_id: ""
  summary: "Full documentation audit: PRD conflicts, THOTH actor_id discrepancy, PRD 81/88 duplication, naming violations, orphaned files, doctrine contradictions."
  module: null
  dialog_transcript: null
---

# Documentation Audit Report

**Date:** 2026-04-14
**Auditor:** Claude Code (Actor 116)
**Constitutional Baseline:** PRD 00 v4.0.99 (when_updated: 20260412133216)
**Truth Hierarchy Applied:** PRD > Doctrine (by timestamp) > Root files

---

## Executive Summary

| Metric | Count |
|--------|-------|
| PRDs scanned | 65 (per prd_index) + 2 meta-docs (prd_index.md, readme.md) |
| Doctrine files in directory | 189 |
| Root files scanned | 19 |
| PRD-level conflicts found | 3 |
| Duplicate PRDs found | 2 (PRD 81 superseded, PRD 88 embedded orphan) |
| Orphaned root files (candidates) | 5 |
| Root files with stale/invalid headers | 14 of 19 |
| Actor ID conflicts | 1 definitive (THOTH: 9 vs 26) + 1 typo (HEPHAESTUS in PRD 32) |

---

## Section 1: PRD Issues

### 1.1 Duplicate PRDs

| Winner | Loser | Resolution | Notes |
|--------|-------|------------|-------|
| **PRD 02** (when_updated: 20260414120000, status: active) | PRD 81 (when_updated: 20260412200000, status: draft) | PRD 02 wins. PRD 81 must be set to `status: legacy, superseded_by: 02`. | PRD 02 change history (2026-04-13) explicitly states: "Fully merged all unique content from PRD 81... PRD 81 is now deprecated." PRD 81 header already sets `parent_pk_id: 02`. |
| **PRD 02** | "PRD 88" (embedded block in PRD 81) | PRD 88 is not a standalone file. An embedded YAML front-matter block beginning at line ~1007 of `81_agent_orchestration_chat.md` claims `file_path_from_root: "lupo-docs/prd/88_agent_orchestration_chat.md"` — but that file does not exist. The block has `when_updated: 20260412190000` and `dialog_transcript: "0/development/prd_88_agent_chat"`. This is a structural violation: a single .md file containing two complete YAML front-matter blocks. The embedded block appears to be an older draft that was never extracted. | ACTION: Remove the embedded PRD 88 block from `81_agent_orchestration_chat.md`. PRD 02 is canonical. |

**Divergent detail (PRD 88 embedded block vs PRD 02):**
The embedded PRD 88 block describes "color-coded backgrounds per agent" — agent-based coloring. PRD 02 and the PRD 81 first section both specify **thread-based** colors as the primary doctrine. The agent-color approach is noted as an alternative in PRD 02, not the default. PRD 02 (highest timestamp, active status) wins on this point.

**Note on OQ-21:** The reference to "PRD 81 and 88 still exist" is half-correct. PRD 81 exists as a file (status: draft). PRD 88 never existed as a standalone file; it is the embedded second block inside PRD 81. The file `88_agent_orchestration_chat.md` does not exist on disk.

---

### 1.2 PRD Naming Violations

**Physical filenames** — All 65 PRD files use `lowercase_with_underscores`. No violations per PRD 00 Section 9.21.1.

**Header field data errors** (file_path_from_root declares incorrect case):

| File | Actual filename | Header claims | Violation |
|------|----------------|---------------|-----------|
| `prd/prd_index.md` | `prd_index.md` | `lupo-docs/prd/PRD_INDEX.md` | UPPERCASE in header |
| `prd/readme.md` | `readme.md` | `lupo-docs/prd/README.md` | UPPERCASE in header |

These are header metadata errors, not physical file naming violations. The files themselves comply. The headers must be corrected.

---

### 1.3 PRDs with Missing or Invalid Headers

| File | Issue |
|------|-------|
| `prd/readme.md` | `header_format_version: "4.0.98"` (uses version string); `trust_tier: null`, `channel_key: null`, `memory_key: null`, `pk_id: null`, `pk_slug: ""`, `title: ""`, `status: ""` — majority of canonical fields missing or empty |
| `prd/prd_index.md` | Content body contains "DRAFT — Do NOT mark FINAL" inconsistent with `status: active` in header; `file_path_from_root` uppercase mismatch noted above |

All 65 numbered PRDs (00–99) have valid `when_updated` timestamps and non-null `status` fields. No numbered PRD has a null `when_updated`.

---

### 1.4 Actor ID Conflicts

#### THOTH actor_id (OQ-13, OQ-18, OQ-22)

| Source | Claims THOTH actor_id | when_updated |
|--------|----------------------|--------------|
| PRD 07 (agents table, Coordination Layer) | **9** | 20260411085204 |
| PRD 32 (governance table, Section 2.1) | **9** | 20260410130550 |
| PRD 16 (explicitly documented) | **26** | 20260412163625 |
| All doctrine files read | **26** | various (see below) |
| Implementation (`DialogMvpService::THOTH_ACTOR_ID`) | **26** | — |
| `channels/index.php` THOTH detection check | **26** | — |
| `actor_authority_quick_reference.md` | **9** | 20260402220000 |

**Doctrine files consistently saying actor_id 26:**
- `doctrine/context_model_doctrine.md` (§112, §397)
- `doctrine/database_constraints.md` (§178)
- `doctrine/service_agent_architecture.md` (§122)
- `doctrine/lupopedia-headers/readme.md` (§63, §109, §139)
- `doctrine/lupopedia-headers/versions/2.0/decisions.md` (§266)

**Truth hierarchy resolution:**
PRD 16 (when_updated: 20260412163625) is more recent than PRD 32 (20260410130550) and PRD 07 (20260411085204). Per the truth hierarchy, higher `when_updated` wins. PRD 16 explicitly says "THOTH (actor_id 26)" and also notes: "THOTH actor_id 26 and PRD 26 share the same number but are different namespaces (actor registry vs document IDs). No functional conflict."

PRD 32 itself (Section 2.3.1, line 100) acknowledges: "The runtime implementation uses actor_id=26 (DialogMvpService::THOTH_ACTOR_ID = 26). This discrepancy must be resolved by WOLFIE."

**Conclusion: actor_id 26 is the authoritative value.** PRD 32 Section 2.1 table and PRD 07 Coordination Layer table are stale on this specific field.

**Recommendation:** WOLFIE issues canonical decision confirming THOTH = actor_id 26. Update PRD 32 and PRD 07 governance tables. Add `define('THOTH_ACTOR_ID', 26)` to config constants. Update `actor_authority_quick_reference.md`.

#### Additional conflict in PRD 32 Tier 2 table

| PRD 32 claims | PRD 07 says | Issue |
|---------------|-------------|-------|
| HEPHAESTUS ID 16 | HEPHAESTUS ID 14 | PRD 32 Tier 2 table lists HEPHAESTUS as actor_id 16, same as IRIS. PRD 07 (when_updated: 20260411085204) shows HEPHAESTUS=14, IRIS=16. This is a typo in PRD 32. |

**Resolution:** PRD 07 is more internally consistent (no duplicates). Correct PRD 32 Tier 2 to HEPHAESTUS=14.

---

## Section 2: Doctrine vs PRD Conflicts

### 2.1 Color Assignment — PRD 50 vs PRD 02

| PRD | Position | when_updated |
|-----|----------|--------------|
| PRD 02 (canonical) | Colors assigned **per thread** at creation time. Agent-based colors exist as a documented alternative but are NOT the primary doctrine. | 20260414120000 |
| PRD 50, Section ~§292 | "Each agent (Cursor, VS Code, Antigravity, Claude, LILITH) gets a unique color" — describes **agent-based** color as the primary system | 20260412131327 |

**Resolution:** PRD 02 wins (higher timestamp, active canonical status). PRD 50 Section ~§292 contradicts PRD 02. The agent-per-color description in PRD 50 should be revised to align with thread-based coloring as the canonical primary doctrine. Agent-based color is an alternative per PRD 02 Section 171.

**Impact:** Any implementation referencing PRD 50 Section ~§292 for the color model will produce a non-canonical color assignment system.

### 2.2 THOTH actor_id in Doctrine Files

Doctrine files consistently use actor_id 26 — this is **aligned with PRD 16** (the authoritative PRD by timestamp). No doctrine vs PRD conflict here. The contradiction exists between PRD 32/PRD 07 (stale) and PRD 16 (current). See Section 1.4.

### 2.3 Channel Model Doctrine (Flagged for Review)

`doctrine/channel_model_doctrine.md` has `last_modified_utc: "20260319"` and `when_updated: null`. This predates PRD 02's canonical merge (2026-04-13). The file's content could reference pre-merge UI patterns or superseded channel design. Full content was not audited for contradictions. **Flag for THOTH-level review** against PRD 02.

### 2.4 Stale Header Format in Root Doctrine-Adjacent Docs

`actors.md` and `directory_structure.md` use a 4.0.84-era header format (`version_when_written: "4.0.84"` instead of `when_updated`; no `header_format_version` field; use `channel_id` instead of `channel_key`). These do not directly contradict a PRD by content, but their metadata is structurally non-compliant with PRD 16 canonical header format. Both files may contain stale architectural descriptions relative to PRD 01, PRD 15, and PRD 29.

---

## Section 3: Root File Redundancies

| File | Issue | Recommendation |
|------|-------|----------------|
| `prd_master.md` | `artifact_kind: master_prd`; `when_updated: null`; header `last_modified_utc: "20260329"`. With PRD 00 as constitutional root and `prd/prd_index.md` as the canonical index, this file is likely redundant and may contain stale PRD summaries. | Confirm scope. Archive to `lupo-archive/` if no unique content remains. If kept, upgrade to canonical header format. |
| `implementation_framework_summary.md` | `when_updated: "20260402000000"`. Content likely summarizes PRD 26 (Five-Layer Documentation Architecture) and PRD 31 (Implementation Folder Guidelines). Both PRDs are the authoritative sources. | Audit content against PRD 26 and PRD 31. Archive if fully redundant; convert to a thin cross-reference if it provides unique navigation value. |
| `implementation_getting_started.md` | `when_updated: null`; `last_modified_utc: "20260328120000"`. May duplicate PRD 27 (Installer Requirements) or PRD 31. | Same as above. |
| `actors.md` | 4.0.84-era header format. Content may be superseded by PRD 01 (Core Identity), PRD 15 (Actors), PRD 24 (Actor Onboarding). | Upgrade header to canonical format. Audit content against PRD 01/15 and archive stale sections. |
| `directory_structure.md` | 4.0.84-era header format. Content may be superseded by PRD 29 (Project Structure) and PRD 31 (Implementation Folder Guidelines). | Upgrade header. Audit against PRD 29/31. |
| `readme.md` | `when_updated: "20260330"` (timestamp format violation: should be 14-digit YYYYMMDDHHIISS, not 8-digit). Functions as a top-level index. May partially duplicate `prd/prd_index.md` or PRD 00 introductory material. | Correct timestamp format. Evaluate whether content is unique vs. redundant with prd_index. |

---

## Section 4: Root File Header Defects Summary

The following root files have invalid or incomplete `lupopedia.headers` blocks per PRD 16 canonical format:

| File | when_updated | header_format_version | Notable Missing Fields |
|------|--------------|-----------------------|------------------------|
| `actors.md` | null (uses `version_when_written`) | null | when_updated, trust_tier, channel_key, memory_key, federation_node_id |
| `actor_authority_quick_reference.md` | "20260402220000" | 2 (not string format) | trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `actor_registration_checklist.md` | null | null | when_updated, trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `channel_vs_docs_quick_reference.md` | "20260402000000" | 2 | trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `directory_structure.md` | null (uses `version_when_written`) | null | when_updated, trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `external_ai_readme.md` | null | null | when_updated, trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `implementation_framework_summary.md` | "20260402000000" | 2 | trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `implementation_getting_started.md` | null | null | when_updated, trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `init_readme.md` | null | null | when_updated, trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `install.md` | null | null | when_updated, trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `lessons_learned_from_the_wild_west.md` | "20260403134653" | 2 | trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `lupopedia_whoami_readme.md` | null (uses old format) | null | when_updated, trust_tier, channel_key; uses `version_when_written` |
| `organization.md` | "20260329200000" | null | trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `prd_master.md` | null | null | when_updated, trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `readme.md` | "20260330" (8-digit, invalid) | 2 | trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `task_status_reference.md` | null | null | when_updated, trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |
| `tldr_lupopedia.md` | null | null | when_updated, trust_tier, channel_key, memory_key; file_path_from_root UPPERCASE |

**Compliant root files (minimal issues):**
- `actors.md` — internally consistent old format, but entire header schema is non-canonical
- `lupopedia_whoami_readme.md` — internally consistent old format

14 of 19 root files have `file_path_from_root` entries using UPPERCASE names that do not match actual lowercase filenames. This is a data integrity violation that will cause path resolution failures in any tool that trusts the header field as canonical.

---

## Section 5: Orphaned Files (No Confirmed Inbound Links)

Files in audit scope with no confirmed inbound references from other audited documents:

| File | Evidence of Orphan Status | Recommendation |
|------|--------------------------|----------------|
| `prd_master.md` | Not listed in `prd/prd_index.md`. Not referenced in other root files audited. | Confirm status. Archive if no unique content. |
| `implementation_framework_summary.md` | Grep across lupo-docs found no references. | Archive candidate. |
| `implementation_getting_started.md` | Grep across lupo-docs found no references. | Archive candidate. |
| `tldr_lupopedia.md` | Grep across lupo-docs found no references (only found in a JSON rename-mapping file). | Archive candidate or add inbound links from main readme.md if it serves a useful onboarding purpose. |
| `lessons_learned_from_the_wild_west.md` | Grep across lupo-docs found no references. | Archive candidate. |

**Caveat:** The full link graph was not scanned. These are strong candidates based on available grep evidence, not a definitive orphan determination. A full link-graph audit (scanning all .md files for cross-references) is required for certainty.

---

## Section 6: Specific Findings from OQ List

### OQ-21: PRD 02 vs PRD 81/88

- **Status:** Reviewed. Findings are clear.
- **PRD 02:** Canonical (status: active, when_updated: 20260414120000). Explicitly merged PRD 81.
- **PRD 81:** Should be set to `status: legacy, superseded_by: 02`. File still exists and contains valid historical content; do not delete, just update status.
- **PRD 88:** Not a standalone file. An orphaned YAML block embedded within `81_agent_orchestration_chat.md` (line ~1007). The block claims `file_path_from_root: "lupo-docs/prd/88_agent_orchestration_chat.md"` but that file does not exist. The embedded block is also slightly older than PRD 81 (timestamp 20260412190000 vs 20260412200000).
- **Recommendation:** Update OQ-21 status to `resolved`. Required actions: (1) Set PRD 81 `status: legacy`; (2) Remove embedded PRD 88 block from `81_agent_orchestration_chat.md` or extract it to an archive file clearly marked superseded.

### OQ-13/18/22: THOTH actor_id

- **Status:** Reviewed. Evidence is conclusive per timestamp hierarchy.
- **Canonical answer:** actor_id **26** per PRD 16 (most recent PRD on this topic), all doctrine files, and implementation code.
- **Action required:** WOLFIE canonical decision confirming actor_id 26 (evidence is unambiguous — this should be a formality, not a debate).
- **Follow-on actions:**
  1. Update PRD 32 Section 2.1 governance table: THOTH 9 -> 26
  2. Update PRD 07 Coordination Layer table: THOTH 9 -> 26
  3. Update `actor_authority_quick_reference.md`: THOTH (9) -> THOTH (26)
  4. Add `define('THOTH_ACTOR_ID', 26)` to `lupopedia-config.php` (or canonical constants file)
  5. Add entry to `AGENT_REGISTRY.md` with confirmed actor_id
  6. Mark OQ-13, OQ-18, and OQ-22 as resolved after WOLFIE decision

---

## Section 7: Recommended Actions

### Immediate (before next PRD merge)

- [ ] **WOLFIE canonical decision:** THOTH actor_id = 26. Post to development channel as [DECISION].
- [ ] **Update PRD 32 Section 2.1:** Change THOTH actor_id 9 -> 26; fix HEPHAESTUS typo (16 -> 14).
- [ ] **Update PRD 07:** Change THOTH Coordination Layer ID 9 -> 26.
- [ ] **Update `actor_authority_quick_reference.md`:** THOTH (9) -> THOTH (26).
- [ ] **Set PRD 81 status:** `status: legacy`; add `superseded_by: "02"` to header.
- [ ] **Remove embedded PRD 88 block** from `81_agent_orchestration_chat.md` (lines ~1007 onward).
- [ ] **Fix `file_path_from_root` headers** in all 14 root files that claim uppercase paths: correct to match actual lowercase filenames.
- [ ] **Mark OQ-13, OQ-18, OQ-21, OQ-22** as resolved after WOLFIE decision.

### Short-term (within 4.0.99 sprint)

- [ ] **Update PRD 50 Section ~§292:** Align color assignment description with PRD 02 thread-based primary doctrine.
- [ ] **Upgrade root file headers** to canonical PRD 16 format: populate missing `when_updated`, `trust_tier`, `channel_key`, `memory_key` fields. Priority: `actors.md`, `directory_structure.md`, `readme.md`.
- [ ] **Fix `readme.md` timestamp:** `when_updated: "20260330"` is 8-digit; must be 14-digit YYYYMMDDHHIISS.
- [ ] **Correct `prd/prd_index.md` and `prd/readme.md`** `file_path_from_root` to lowercase.
- [ ] **Review `channel_model_doctrine.md`** against PRD 02 for content conflicts (THOTH-level review recommended).
- [ ] **Confirm status of `prd_master.md`:** Archive to `lupo-archive/` or retain as thin cross-reference with upgraded header.

### Long-term (post-Softaculous, product 4.1.0)

- [ ] **Full link graph audit:** Scan all .md files to build a complete inbound-link map; definitively identify all orphaned files and move confirmed orphans to `lupo-archive/`.
- [ ] **Automated header validator:** Add CI check for `file_path_from_root` case mismatches, null `when_updated`, and non-14-digit timestamps.
- [ ] **Migrate stale root files** (`actors.md`, `directory_structure.md`, `lupopedia_whoami_readme.md`) to v4.0.99 canonical header format or merge content into canonical PRDs.
- [ ] **Resolve VISH registration (OQ-14):** Assign actor_id, create `lupo_actors` row, add `AGENT_REGISTRY.md` entry when collections reclassification API is ready.
- [ ] **Audit all 189 doctrine files** against current PRD timestamps: identify any with content that contradicts an active PRD and update or retire them.

---

## Section 8: Orphaned File Disposition Recommendations (Task 5)

*Added 2026-04-14 per post-audit fix pass.*

| File | Content Summary | Recommendation | Rationale |
|------|----------------|----------------|-----------|
| `prd_master.md` | Two-line stub: "All features... consolidated as of 4.0.93. See lupo-docs/versions/4.0.93/..." | **Archive** to `lupo-archive/root-docs/prd_master.md` | Content references a superseded version (4.0.93). No unique content; the PRD index (`prd/prd_index.md`) and PRD 00 serve this function. Zero inbound links confirmed. |
| `implementation_framework_summary.md` | Summary of PRD 30 and PRD 31 implementation (channels vs docs, question lifecycle). Created around 2026-04-02. | **Archive** to `lupo-archive/root-docs/implementation_framework_summary.md` | All content is a subset of PRD 30 and PRD 31, which are the authoritative sources. No unique content. Zero inbound links. |
| `implementation_getting_started.md` | v4.0.89 onboarding guide (2026-03-28): first 30 minutes, prerequisites. | **Keep with added link** — move to `lupo-docs/reference/implementation_getting_started.md` and link from `readme.md` | Unique content: a practical onboarding narrative that is not duplicated in PRDs. Has value for new agents. But it is stale (v4.0.89); needs a content review pass before promoting. Intermediate action: add an inbound link from `lupo-docs/readme.md`. |
| `tldr_lupopedia.md` | v4.0.62 quick-reference: help system, FLAME, routing, core architecture. | **Archive** to `lupo-archive/root-docs/tldr_lupopedia.md` | Content is anchored to v4.0.62 — two major minor versions stale. The TLDR material is superseded by PRD 00, PRD 78 (CLI), and the current CLAUDE.md. Archiving prevents new agents from trusting stale routing descriptions. |
| `lessons_learned_from_the_wild_west.md` | WOLFIE personal essay: 1990s–2000s internet survivor perspective, philosophy behind Lupopedia design decisions. | **Keep with added link** — link from `lupo-docs/readme.md` as "Context & Philosophy" | This file contains unique institutional context (WOLFIE's authorial voice, design philosophy) that cannot be found in any PRD. It is intentionally narrative, not technical, so staleness is not a concern. Add one inbound link so it is no longer orphaned. |

**Summary disposition:**
- Archive immediately (3): `prd_master.md`, `implementation_framework_summary.md`, `tldr_lupopedia.md`
- Keep + link (2): `implementation_getting_started.md` (needs content review first), `lessons_learned_from_the_wild_west.md`
- Do NOT delete any file — move to `lupo-archive/` with git history preserved

---

## Appendix: Fixes Executed (2026-04-14)

| Task | Files Changed | Change |
|------|--------------|--------|
| T1: THOTH actor_id | `prd/32_actor_authority_agent_roles.md` | Section 2.1 table THOTH 9->26; Section 2.2 HEPHAESTUS 16->14 (typo); Section 2.3.1 resolved note updated; when_updated bumped |
| T1: THOTH actor_id | `prd/07_agents_faucets.md` | Coordination Layer table THOTH 9->26; correction note added; when_updated bumped |
| T2: Deprecate PRD 81 | `prd/81_agent_orchestration_chat.md` | status: draft->legacy; superseded_by: "02" added; trust_tier: canonical->legacy; embedded PRD 88 block (lines 1007-1579) removed; truncated JS line fixed; legacy note added |
| T3: Color doctrine | `prd/50_agent_coordination_protocol.md` | Agent-per-color claim corrected to thread-based per PRD 02; correction note added; when_updated bumped |
| T4: Header paths | 14 root .md files + `prd/prd_index.md` + `prd/readme.md` | `file_path_from_root` corrected from UPPERCASE to lowercase in all 16 files |

---

*Report written by Claude Code (Actor 116) — 2026-04-14*
*Files read directly: all files in scope with confirming tool calls. No hallucinated content. Files not individually read (out of 189 doctrine files) are flagged as "not audited" where relevant.*
