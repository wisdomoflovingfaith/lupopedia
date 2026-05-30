---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/42/threads/1012/20260318_180800_hephaestus_spec_validator-complete.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1012/20260318_180800_hephaestus_spec_validator-complete.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1012
  task_id: "task_val_002"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "thread"
  artifact_kind: "specification"
  purpose: "Authoritative validator design: TODO.md + plan.md Option A; V-TODO-001–015, V-PLAN-001–009; no implementation"
  tags: ["task_val_002", "validator", "option_a", "design_only", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1011/20260318_180000_wolfie_directive_validator-and-project-alignment.md", type: "implements", weight: 1.0 }
    - { to: "channels/42/threads/1006/20260318_170000_lilith_review_task_val_001_validator-design.md", type: "closes_gaps", weight: 1.0 }
    - { to: "channels/42/threads/1004/20260318_141356_athena_spec_todo_registry.md", type: "aligns_with", weight: 1.0 }
    - { to: "channels/42/threads/1004/20260318_141456_athena_spec_plan_roadmap.md", type: "aligns_with", weight: 1.0 }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
---
# file: HEPHAESTUS specification — complete TODO/plan validators (Option A) — thread 1012

**task_val_002 — DESIGN ONLY — NO CODE.**  
Supersedes informal / partial rule lists. Implements WOLFIE unblock criteria and LILITH review gaps.

---

## 1. Overview

### 1.1 Scope

| File | Role |
|------|------|
| `TODO.md` | **Authoritative** Global Task Registry (single canonical table). |
| `plan.md` | **Strategic Roadmap** only; references `task_id` from TODO; never a registry. |

### 1.2 Exit semantics (normative for implementers)

| Mode | Behavior |
|------|----------|
| **Strict** | Exit code **1** if any **ERROR**-class rule fires. **WARN**-class rules print to stderr (or prefixed stdout line `WARN:`) but do **not** set exit 1 unless `--warnings-as-errors`. |
| **Lenient** (optional flag) | Only ERROR-class subset A fires; WARN-only rules suppressed or downgraded (implementation-defined; MUST document). |

### 1.3 Alignment

- **ATHENA** threads 1004 (TODO spec, plan spec): authoritative field definitions unless this document **explicitly** refines (e.g. V-TODO-011 placeholder semantics).
- **LILITH** 1006: all cited gaps addressed by explicit rules, parser bounds, and ERROR/WARN matrix.

---

## 2. TODO.md — Rule definitions (V-TODO-001 — V-TODO-015)

### V-TODO-001 — Registry section existence

| Field | Value |
|-------|--------|
| **Name** | Single authoritative registry section |
| **Description** | Exactly one `## Global Task Registry (Option A)` heading MUST exist in `TODO.md`. |
| **Validation logic** | Scan lines; count headings matching `^## Global Task Registry \(Option A\)\s*$`. Count MUST equal 1. |
| **Severity** | **ERROR** |
| **Example PASS** | One such `##` heading; no duplicate. |
| **Example FAIL** | Zero headings → ERROR; two headings → ERROR. |

---

### V-TODO-002 — Canonical header row

| Field | Value |
|-------|--------|
| **Name** | Registry table header |
| **Description** | First Markdown table immediately under the registry `##` MUST have header row **exactly** (spacing-normalized): `task_id | task_title | owner_actor | lifecycle_state | status | thread_id | priority | created_utc | updated_utc | primary_artifact | notes` |
| **Validation logic** | After V-TODO-001 anchor, find first line starting with `|` that is not a separator row (`|---|`). Split per §5.1; compare cell texts (trimmed, lower only where specified: **do not** lower column names). Expected 11 header names in order per ATHENA §1. Separator row `|---|---|...` optional; if present, next line begins data rows. |
| **Severity** | **ERROR** |
| **Example PASS** | Header row matches canonical names and order. |
| **Example FAIL** | `Task_ID` typo; wrong column order; only 10 columns → ERROR. |

---

### V-TODO-003 — Row shape and task_title

| Field | Value |
|-------|--------|
| **Name** | Data row structure and title column |
| **Description** | Each non-empty data row under the registry table MUST parse to exactly **11** cells (§5.1). No cell may contain CR or LF. **task_title** (cell index 1, 0-based) MUST be length 1–120, MUST NOT contain `\|` (U+007C). |
| **Validation logic** | For each data row until boundary (§5.2): (1) split → 11 segments; (2) reject if any segment matches `[\r\n]`; (3) validate task_title cell. Rows that are visually “empty” (all cells `-` or whitespace-only) → **ERROR** (duplicate empty identity). Row with only pipes/spaces → **ERROR**. |
| **Severity** | **ERROR** |
| **Example PASS** | `\| task_foo \| Short title \| 1:wolfie \| ... \|` |
| **Example FAIL** | 10 cells; task_title 121 chars; task_title contains `|`; embedded newline in notes → ERROR. |

---

### V-TODO-004 — task_id format and reserved words

| Field | Value |
|-------|--------|
| **Name** | task_id syntax |
| **Description** | Cell `task_id` MUST match `^[a-z0-9_]+$`. MUST NOT equal reserved: `todo`, `plan`, `thread`, `prompt` (exact match, case-sensitive). |
| **Validation logic** | Regex + reserved set check. |
| **Severity** | **ERROR** |
| **Example PASS** | `task_impl_001`, `task_prompt_041000` |
| **Example FAIL** | `Task_001`, `my-task`, `todo` → ERROR. |

---

### V-TODO-005 — task_id uniqueness

| Field | Value |
|-------|--------|
| **Name** | Unique task_id |
| **Description** | Every `task_id` in the registry table MUST appear exactly once. |
| **Validation logic** | Collect all task_ids from data rows; if any duplicate → ERROR. |
| **Severity** | **ERROR** |
| **Example PASS** | All distinct. |
| **Example FAIL** | Two rows with `task_impl_001` → ERROR. |

---

### V-TODO-006 — owner_actor format and cardinality

| Field | Value |
|-------|--------|
| **Name** | owner_actor |
| **Description** | Value MUST be **exactly one** of: (a) literal `-` (U+002D, sole character after trim), or (b) `^\d+:[a-z0-9_]+$` (one or more digits, colon, slug). No commas, no `&`, no second colon-pair, no spaces inside slug. |
| **Validation logic** | If `-`: entire trimmed cell equals `-`. Else full-string regex `^([0-9]+):([a-z0-9_]+)$` with capture 2 non-empty. Any other pattern → ERROR. |
| **Severity** | **ERROR** |
| **Example PASS** | `14:hephaestus`, `-` |
| **Example FAIL** | `14:Hephaestus` (uppercase), `wolfie`, `14:hepha:stus`, `1:wolfie,2:lilith` → ERROR. |

---

### V-TODO-007 — lifecycle_state

| Field | Value |
|-------|--------|
| **Name** | lifecycle_state enum |
| **Description** | MUST be exactly one of: `open`, `active`, `blocked`, `resolved`, `archived`. |
| **Validation logic** | Exact string match after trim. |
| **Severity** | **ERROR** |
| **Example PASS** | `open` |
| **Example FAIL** | `Open`, `wip` → ERROR. |

---

### V-TODO-008 — status enum

| Field | Value |
|-------|--------|
| **Name** | status enum |
| **Description** | MUST be exactly one of: `planned`, `in_progress`, `blocked`, `complete`, `archived`. |
| **Validation logic** | Exact match after trim. |
| **Severity** | **ERROR** |
| **Example PASS** | `planned` |
| **Example FAIL** | `partial`, `done` → ERROR. |

---

### V-TODO-009 — lifecycle_state ↔ status mapping

| Field | Value |
|-------|--------|
| **Name** | Derived status consistency |
| **Description** | Pair `(lifecycle_state, status)` MUST match ATHENA mapping exactly. |
| **Validation logic** | `open→planned`, `active→in_progress`, `blocked→blocked`, `resolved→complete`, `archived→archived`. Any other pair → ERROR. |
| **Severity** | **ERROR** |
| **Example PASS** | `open` / `planned` |
| **Example FAIL** | `open` / `in_progress` → ERROR. |

---

### V-TODO-010 — thread_id and owner for non-open lifecycles

| Field | Value |
|-------|--------|
| **Name** | Allocated work must have thread and owner |
| **Description** | If `lifecycle_state ∈ {active, blocked, resolved, archived}` then: `thread_id` MUST match `^[1-9][0-9]*$` (numeric, no leading zero unless single `0` forbidden — use **positive integer string** `^[1-9][0-9]{0,17}$` to match channel thread id style), and `owner_actor` MUST NOT be `-`. |
| **Validation logic** | Conditional on lifecycle_state. |
| **Severity** | **ERROR** |
| **Example PASS** | `active`, `1005`, `14:hephaestus` |
| **Example FAIL** | `active`, `-`, `1:wolfie` → ERROR; `resolved`, `thread_id=-` → ERROR. |

**Note:** `thread_id` numeric: allow `0` only if registry explicitly documents channel `0`; else **ERROR** if `thread_id` is `0`. Default rule: **`^[1-9][0-9]{0,17}$`** (matches NUMERIC_THREAD style). If product later allows `0`, spec amendment required.

---

### V-TODO-011 — open lifecycle: thread_id, owner_actor, placeholders

| Field | Value |
|-------|--------|
| **Name** | Open-row and placeholder semantics |
| **Description** | Resolves LILITH/ATHENA ambiguity for `thread_id = -` vs owner. |
| **Validation logic** | If `lifecycle_state != open`, skip (covered by V-TODO-010). If `lifecycle_state == open`: **Case A — task_id matches `^task_prompt_[0-9]+$`**: `thread_id` MUST be `-`. `owner_actor` MUST match `^\d+:[a-z0-9_]+$` (prompt-queue tasks: target actor assigned; thread not allocated). **Case B — task_id matches `^task_deferred_[0-9]+$`**: `thread_id` MUST be `-`. `owner_actor` MUST be `-`. **Case C — any other open row**: if `thread_id` is numeric (`^[1-9][0-9]*$`), then `owner_actor` MUST NOT be `-`. If `thread_id` is `-`, then `owner_actor` MUST be `-` (unallocated open bucket). |
| **Severity** | **ERROR** |
| **Example PASS** | `task_prompt_041000`, `open`, `planned`, `-`, `1:wolfie` |
| **Example FAIL** | `task_prompt_041000` with `owner_actor=-` → ERROR; `task_deferred_0001` with `2:lilith` → ERROR; generic `task_misc` open with `thread_id=-` and `1:wolfie` → ERROR. |

---

### V-TODO-012 — priority

| Field | Value |
|-------|--------|
| **Name** | priority enum |
| **Description** | MUST be one of `P0`, `P1`, `P2`, `P3`. |
| **Validation logic** | Exact match. |
| **Severity** | **ERROR** |
| **Example PASS** | `P0` |
| **Example FAIL** | `p0`, `HIGH` → ERROR. |

---

### V-TODO-013 — timestamp format

| Field | Value |
|-------|--------|
| **Name** | created_utc / updated_utc format |
| **Description** | Both MUST match `^\d{8}_\d{6}$` (UTC `YYYYMMDD_HHIISS`). |
| **Validation logic** | Regex per column. |
| **Severity** | **ERROR** |
| **Example PASS** | `20260318_142300` |
| **Example FAIL** | `2026-03-18`, `20260318142300` → ERROR. |

---

### V-TODO-014 — updated_utc ≥ created_utc

| Field | Value |
|-------|--------|
| **Name** | Timestamp ordering |
| **Description** | Lexicographic compare: `updated_utc >= created_utc`. |
| **Validation logic** | String compare after V-TODO-013 passes. |
| **Severity** | **ERROR** |
| **Example PASS** | created `20260318_100000`, updated `20260318_100001` |
| **Example FAIL** | updated earlier than created → ERROR. |

---

### V-TODO-015 — primary_artifact and notes

| Field | Value |
|-------|--------|
| **Name** | Artifact path and notes |
| **Description** | **primary_artifact**: MUST be `-` OR a relative repo path (no `:` before first `/`, no Windows drive pattern `^[A-Za-z]:`, no leading `/`) ending in `.md`. **notes**: MUST be `-` OR non-empty text without CR/LF; length ≤ **240** codepoints (scalar). |
| **Validation logic** | Path: reject `..` as path segment (split on `/`, any segment `..` → ERROR). notes: if length > 240 → **WARN** (W-TODO-002); if contains newline → **ERROR**. |
| **Severity** | **ERROR** for path violations and newline in notes; **WARN** for notes length > 240. |
| **Example PASS** | `channels/42/prompts/foo.md`, notes `-` |
| **Example FAIL** | `C:\x.md`, `http://x`, notes with newline → ERROR. |

---

### W-TODO-001 — Registry row ordering (non-blocking)

| Field | Value |
|-------|--------|
| **Name** | Deterministic sort order |
| **Description** | Rows SHOULD be ordered: priority `P0`<`P1`<`P2`<`P3`; then lifecycle `active`,`blocked`,`open`,`resolved`,`archived`; then `task_id` lexicographic. |
| **Severity** | **WARN** |
| **Example PASS** | Matches order. |
| **Example FAIL** | P1 row before P0 row → WARN only. |

---

## 3. plan.md — Rule definitions (V-PLAN-001 — V-PLAN-009)

### Global: authoritative vs non-authoritative regions

| Region | Detection | Validator use |
|--------|-----------|-----------------|
| **Prompt queue view** | First `## Prompt queue` section through the line before the next `## Phase` OR `## Version History` (whichever first). | V-PLAN-006/008 may scan here for `task_id:` bindings; MUST NOT treat tables here as registry. |
| **Phase sections** | `^## Phase\s+[0-9]+\s+—\s+.+$` (EM DASH U+2014 **or** hyphen-minus U+002D normalized to one canonical form in parser — see §5.3). | Template + Registry links extraction. |
| **Version History** | `## Version History` to EOF (or next same-level `##`). | Narrative only; no task_id extraction for V-PLAN-008 **unless** line matches Registry link bullet pattern (then ERROR: misplaced link). |
| **Legacy rolled-forward tables** | Headings like `### 4.0.81 Active Work`, `### Release Blockers`, `### Deferred Work` inside Prompt queue region. | **IGNORE** for V-PLAN-002–005; still subject to V-PLAN-006/009 (no registry table header; prompt IDs allowed only in labeled view). |

---

### V-PLAN-001 — At least one Phase section

| Field | Value |
|-------|--------|
| **Name** | Phase presence |
| **Description** | At least one heading matching phase pattern (§5.3). |
| **Validation logic** | Count phase headings ≥ 1. |
| **Severity** | **ERROR** (Option A compliant roadmap). |
| **Example PASS** | `## Phase 1 — Stabilization` |
| **Example FAIL** | No `## Phase` → ERROR. |

---

### V-PLAN-002 — Phase template order

| Field | Value |
|-------|--------|
| **Name** | Required phase subsections |
| **Description** | Under each `## Phase N — …`, the following MUST appear in order: (1) `**Depends on:**` line, (2) `**Completion when:**` block, (3) `**Registry links:**` block. No other `**` top-level phase keyword may precede `Depends on`. |
| **Validation logic** | For each phase: find first line starting with `**Depends on:**`; then next `**Completion when:**`; then `**Registry links:**`. If order wrong or missing → ERROR. |
| **Severity** | **ERROR** |
| **Example PASS** | Depends → Completion when (checklist) → Registry links (bullets). |
| **Example FAIL** | Registry links before Completion when → ERROR. |

---

### V-PLAN-003 — Depends on format

| Field | Value |
|-------|--------|
| **Name** | Dependency expression |
| **Description** | After `**Depends on:**`, text MUST be: `nothing` OR `Phase <int>` OR `Phase <int> + Phase <int> + …` (integers 1–99, ascending order in multi-phase case). MUST NOT contain: `day`, `week`, `month`, `ASAP`, `soon`, `later`, digits with `d`/`w` time suffix. |
| **Validation logic** | Regex / token rules. |
| **Severity** | **ERROR** |
| **Example PASS** | `Phase 1`, `Phase 1 + Phase 2` |
| **Example FAIL** | `Phase 2 + Phase 1` (not ascending), `next week` → ERROR. |

---

### V-PLAN-004 — Completion when checklist

| Field | Value |
|-------|--------|
| **Name** | Completion criteria |
| **Description** | After `**Completion when:**`, at least one line matching `^\s*-\s+\[[ xX]\]\s+.+$` before `**Registry links:**`. |
| **Validation logic** | Count checklist items ≥ 1. |
| **Severity** | **ERROR** |
| **Example PASS** | `- [ ] Migrate TODO` |
| **Example FAIL** | Only prose, no `- [ ]` → ERROR. |

---

### V-PLAN-005 — Registry links line format

| Field | Value |
|-------|--------|
| **Name** | Registry link bullets |
| **Description** | Each bullet under `**Registry links:**` MUST match: `^\s*-\s+task_id:\s+([a-z0-9_]+)(\s+\(thread\s+[1-9][0-9]*\))?\s+—\s+.+$` — em dash **U+2014** OR hyphen-minus **U+002D doubled as dash** accepted as `—` alias: implementer normalizes ` - ` separator: require **long dash or ` - ` with spaces**; normative: substring `task_id:` then slug then `—` (U+2014) or ` -- ` (double hyphen surrounded by spaces) then reason. |
| **Validation logic** | Per bullet line: extract `task_id` token; must match V-TODO-004 pattern. Optional `(thread N)` only after task_id. |
| **Severity** | **ERROR** |
| **Example PASS** | `- task_id: task_impl_001 — implement restructuring` |
| **Example FAIL** | `- task_impl_001` without `task_id:` prefix → ERROR. |

---

### V-PLAN-006 — Prompt ID not primary outside view

| Field | Value |
|-------|--------|
| **Name** | Prompt vs task identity |
| **Description** | Outside the **Prompt queue** section boundary: any line that looks like a **standalone prompt reference** as primary work identity is forbidden. **Standalone** means: `**[0-9]{6}**` or `` `[0-9]{6}` `` as the only task key on a line in Registry links or phase body **without** `task_id:` on same line. **Allowed**: prompt IDs inside Prompt queue section; narrative prose “see prompt 041000” in checklist text (non-structured). **Forbidden**: Registry links bullet that references only `041000` without `task_id:`. |
| **Validation logic** | In Phase `Registry links:` bullets: each line MUST contain `task_id:`. In Phase body outside Registry links: ERROR if bullet is `- **041000**` only. Tables in Phase sections: MUST NOT duplicate TODO registry header (see V-PLAN-009). |
| **Severity** | **ERROR** |
| **Example PASS** | Prompt queue row with `task_id: task_prompt_041000` |
| **Example FAIL** | Phase 2 Registry link: `- **041000** (WOLFIE)` without task_id → ERROR. |

---

### V-PLAN-007 — thread_id without task_id

| Field | Value |
|-------|--------|
| **Name** | Thread reference pairing |
| **Description** | Any explicit `(thread N)` or `thread_id:` in plan MUST appear on a line that also contains `task_id:` for the same work item. |
| **Validation logic** | Line-based: if `(thread\s+[0-9]+)` or `thread_id:\s*[0-9]+` matched, same line MUST match `task_id:\s*[a-z0-9_]+`. |
| **Severity** | **ERROR** |
| **Example PASS** | `- task_id: task_doc_001 (thread 1003) — docs` |
| **Example FAIL** | `Thread 1003 only` in Registry links section → ERROR. |

---

### V-PLAN-008 — Cross-file task_id existence

| Field | Value |
|-------|--------|
| **Name** | Plan ⊆ TODO registry |
| **Description** | Let **S_plan** = set of `task_id` values extracted from: (a) every `**Registry links:**` bullet under each Phase (per V-PLAN-005), (b) every line in **Prompt queue** section that contains `task_id:\s*([a-z0-9_]+)`. Let **S_todo** = set of `task_id` from TODO registry table. Then **S_plan ⊆ S_todo**. |
| **Validation logic** | Parse TODO first; build S_todo; parse plan; for each id in S_plan, if id ∉ S_todo → ERROR. |
| **Severity** | **ERROR** |
| **Example PASS** | All referenced task_ids have rows in TODO. |
| **Example FAIL** | `task_id: task_future_999` not in TODO → ERROR. |

---

### V-PLAN-009 — No registry table in plan.md

| Field | Value |
|-------|--------|
| **Name** | Anti-registry |
| **Description** | Full file MUST NOT contain the **canonical TODO registry header substring** as a table header row: the contiguous cell list `task_id | task_title | owner_actor | lifecycle_state | status | thread_id | priority | created_utc | updated_utc | primary_artifact | notes` (allowing optional spaces). Case-sensitive on keywords. |
| **Validation logic** | Sliding window or normalized join of first table row under any `##`. If match → ERROR. **Exception**: None. Prompt queue tables use different columns → allowed. |
| **Severity** | **ERROR** |
| **Example PASS** | Prompt queue table with columns `Phase gap | Resolving prompts`. |
| **Example FAIL** | Copy-paste of full TODO header into plan → ERROR. |

---

## 4. ERROR vs WARN matrix (complete)

| Rule ID | Severity | Reason |
|---------|----------|--------|
| V-TODO-001 | ERROR | Missing/duplicate registry breaks authority. |
| V-TODO-002 | ERROR | Wrong schema → machine parse fails. |
| V-TODO-003 | ERROR | Row shape/title breaks integrity. |
| V-TODO-004 | ERROR | Invalid identity token. |
| V-TODO-005 | ERROR | Duplicate identity. |
| V-TODO-006 | ERROR | Owner format / multi-owner. |
| V-TODO-007 | ERROR | Unknown lifecycle. |
| V-TODO-008 | ERROR | Unknown status. |
| V-TODO-009 | ERROR | lifecycle/status drift. |
| V-TODO-010 | ERROR | Active work without thread/owner. |
| V-TODO-011 | ERROR | Placeholder/open rules violated. |
| V-TODO-012 | ERROR | Invalid priority. |
| V-TODO-013 | ERROR | Invalid timestamp format. |
| V-TODO-014 | ERROR | Time ordering impossible. |
| V-TODO-015 | ERROR | Bad path or newline in notes. |
| V-TODO-015 (notes length >240) | WARN | W-TODO-002; long notes until policy tightens. |
| W-TODO-001 | WARN | Sort order cosmetic / policy. |
| W-TODO-002 | WARN | notes column > 240 codepoints. |
| V-PLAN-001 | ERROR | Not a roadmap. |
| V-PLAN-002 | ERROR | Phase template broken. |
| V-PLAN-003 | ERROR | Non-deterministic deps. |
| V-PLAN-004 | ERROR | No verifiable completion. |
| V-PLAN-005 | ERROR | Registry links not machine-linkable. |
| V-PLAN-006 | ERROR | Prompt-as-primary outside view. |
| V-PLAN-007 | ERROR | Orphan thread reference. |
| V-PLAN-008 | ERROR | Plan references unknown task. |
| V-PLAN-009 | ERROR | plan must not duplicate registry. |

---

## 5. Parser specification (strict)

### 5.1 TODO.md — Section boundary

1. Locate sole `## Global Task Registry (Option A)` (V-TODO-001).
2. **Authoritative table** spans from **first** table header row (line with `task_id` as first cell) through **last** contiguous table row **before**:
   - next line matching `^##\s+` (new H2), OR
   - EOF.
3. Lines matching `^### ` inside that range: **still inside** registry region until H2; **do not** start a new authoritative table (ATHENA allows only one table; if `###` appears between header and rows → **ERROR** V-TODO-003 malformed).
4. **IGNORE for registry parse**: Any line before registry H2; any line after registry table end; sections `## 4.0.81 Active Tasks`, `### Release Blockers`, `### Deferred Work`, `## Version History` — **no** rows from those tables enter S_todo.

### 5.2 TODO.md — Table row parsing

1. **Row**: single line, trim trailing `\r`.
2. **Separator row**: if line matches `^\s*\|[\s\-:|]+\|\s*$` (only dashes, pipes, spaces, colons), skip (header separator).
3. **Cell extraction**: Remove optional leading `|`. Split by `|`. Trim each segment. **Expected count 11**. If after split length is 12 and first and last are empty, **implementation MAY** merge to 11 — **normative**: require **11** trimmed segments; if header row has 11 columns, data rows MUST have 11 **non-header** cells; outer pipes produce empty first/last → strip empties at ends only if inner count is 11.
4. **Whitespace**: trim spaces and tabs per cell; internal multiple spaces preserved in notes.
5. **Malformed row**: column count ≠ 11 → **ERROR** V-TODO-003; do not partially validate row.
6. **Missing cells**: represented as empty string after split → treat as **ERROR** (empty must be `-` per ATHENA; empty string ≠ `-`).

### 5.3 plan.md — Phase detection

1. **Phase heading**: `^##\s+Phase\s+([1-9][0-9]*)\s+[—\-]\s+(.+?)\s*$` — capture phase number and name (name must not contain newline).
2. **Normalize**: Accept EM DASH (U+2014) or ASCII `-` repeated `---` is **not** a phase heading; single hyphen after space: `Phase 1 - Name` → **WARN** W-PLAN-001 “use EM DASH”; treat as phase for parsing **or** **ERROR** — **normative ERROR** for `Phase 1 - Stabilization` (hyphen-minus): require **U+2014** in canonical docs; implementer may auto-normalize with WARN.

**Binding choice for task_val_002:** **ERROR** if phase delimiter is not U+2014 (matches current `plan.md` style).

3. **Prompt queue isolation**: From `^## Prompt queue` to next `^## Phase` or `^## Version History`, parse tables for display only; extract `task_id:` via regex `task_id:\s*([a-z0-9_]+)` for V-PLAN-008.

### 5.4 plan.md — Phase body extraction

For each phase block, text until next `## Phase` or `## Version History` or EOF. Subsections parsed in order per V-PLAN-002.

---

## 6. Cross-file validation

### 6.1 Extract S_todo

- All `task_id` values from authoritative TODO registry data rows (after V-TODO-004 passes per row, or collect pre-validate).

### 6.2 Extract S_plan

- From each Phase: bullets under `**Registry links:**` until next `**` heading or blank break (non-indented line starting with `##`) — extract `task_id` per V-PLAN-005.
- From Prompt queue section: all regex matches `task_id:\s*([a-z0-9_]+)`.

### 6.3 Validate

- **V-PLAN-008**: `S_plan \ S_todo` → each missing id → **ERROR** with message `PLAN_ORPHAN_TASK: <task_id>`.
- **Extra TODO rows not in plan**: **allowed**; no rule fires.
- **Narrative mentions** (“see task_plan_001”) outside structured bullets: **out of scope** for V-PLAN-008 (LILITH gap: no ERROR for prose-only mention).

---

## 7. Edge cases (mandatory behavior)

| Case | Behavior |
|------|----------|
| Empty data row (all `-`) | **ERROR** V-TODO-003 (degenerate row) unless row is explicitly allowed — **not allowed**. |
| Duplicate task_id | **ERROR** V-TODO-005. |
| Malformed table (ragged pipes) | **ERROR** V-TODO-003; report first bad line number. |
| Invalid timestamp | **ERROR** V-TODO-013. |
| Invalid priority | **ERROR** V-TODO-012. |
| Invalid lifecycle transition | **Static file**: no history. **ERROR** only if current row violates V-TODO-009/V-TODO-010/V-TODO-011. Transition **audit** (prev file version vs new) → **out of scope** for v1 validator. |
| Two registry sections | **ERROR** V-TODO-001. |
| TODO file missing | **ERROR** V-TODO-001 (cannot find section). |
| plan missing Phase | **ERROR** V-PLAN-001. |
| Registry link with valid task_id but wrong reason text empty | **ERROR** V-PLAN-005 (`—` and reason required after dash). |

---

## 8. Test cases (explicit)

### 8.1 Valid TODO row

```markdown
| task_impl_001 | Implement Option A | 14:hephaestus | active | in_progress | 1005 | P0 | 20260318_142300 | 20260318_142300 | channels/42/threads/1005/kickoff.md | Binding thread. |
```

### 8.2 Invalid TODO row (failures)

- `| task_BAD | ... |` — V-TODO-004 (uppercase).
- `| task_x | T | - | active | in_progress | 1 | P0 | ... |` — V-TODO-010 (active with `-` owner).
- `| task_prompt_041000 | P | - | open | planned | - | P0 | ... |` — V-TODO-011 Case A fail (owner must be `N:slug`).

### 8.3 Valid plan phase fragment

```markdown
## Phase 2 — Enforcement

**Depends on:** Phase 1

**Completion when:**
- [ ] Registry migrated

**Registry links:**
- task_id: task_impl_001 — restructuring
```

### 8.4 Invalid plan fragment

```markdown
## Phase 2 — Enforcement

**Registry links:**
- task_id: task_missing_xyz — orphan

**Depends on:** Phase 1
```
→ V-PLAN-002 (order) + V-PLAN-008 if task_missing_xyz ∉ TODO.

---

## 9. Final validation model

1. **Parse TODO** with §5.1–5.2 → apply V-TODO-001…015 (+ W-TODO-001).
2. **Parse plan** with §5.3–5.4 → apply V-PLAN-001…009.
3. **Cross-file** §6 → V-PLAN-008.
4. **Exit**: any ERROR → code 1 in strict mode; WARNs listed, code 0 unless `--warnings-as-errors`.

This specification is **complete** for **task_val_001** implementation after LILITH sign-off on **task_val_002**.

---

_HEPHAESTUS (14) — task_val_002 — authoritative design; implementation forbidden in this artifact._
