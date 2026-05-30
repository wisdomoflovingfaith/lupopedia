---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260414003000"
  file_path_from_root: "lupo-docs/versions/4.0.99/PLAN.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.99/PLAN.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "lupo-memory/development/canonical/1026/04/version-4-0-99-plan.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "Lupopedia 4.0.99 execution plan"
  status: "active"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: "0/development/4_0_99_plan"
---
# PLAN — Lupopedia 4.0.99 (Updated 2026-04-14 00:30 UTC)

**Source:** Unfinished phases and dependency-ordered sections copied from [4.0.98/PLAN.md](../4.0.98/PLAN.md). Phases **A–X** and detailed **R–X** completion criteria are **complete** under **4.0.98**; see that file for receipts.

## Phase Z5: Gemini Operating Contract & Channel UI Refinement (2026-04-14 00:30 UTC)

**Status:** COMPLETED (packed UTC **`20260414003000`**).

**Deliverables:**
- **GEMINI.md Operating Contract**: Synthesized system-wide operating contract from source documents.
- **Channel UI Refinement**: Fixed channel dropdown visibility, auto-join membership, and channel-scoped member sidebar in `channels/index.php`.
- **Delta Analysis**: Documentation of unique structural and behavioral insights beyond historical reports.
- **Session Reporting**: Comprehensive status report and backlog updates.

**Evidence:** [`CHANGELOG.md`](CHANGELOG.md) **`[2026-04-14 00:30 UTC]`**; [`status/session_report_20260413_gemini_contract_channels_cursor.md`](status/session_report_20260413_gemini_contract_channels_cursor.md).

**Lessons Learned:**
- Establishing a non-negotiable operating contract (`GEMINI.md`) is vital for multi-session agent alignment.
- Legacy "Capability Negotiation" models remain the most resilient way to handle transport in constrained shared-hosting environments.
- Small UX gaps (like a missing dropdown) can severely hinder multi-agent collaboration efficiency.

**Next Steps:**
- Deploy the updated `channels/index.php` to production/staging.
- Ensure all future agents are "anchored" to `GEMINI.md` at session start.

---

## Phase Z4: End of Day Programming — Constitutional Audit, Memory Graph, and Header Enforcement (2026-04-12 18:00 UTC)

**Status:** COMPLETED (packed UTC **`20260412180000`**).

**Deliverables:**
- Documentation and memory graph refactor for constitutional compliance and meta-level doctrine
- Comprehensive cross-file audit (CLAUDE.md, README_WTF.md, all PRDs in lupo-docs/prd/) with actionable recommendations
- Implementation of all audit recommendations: section-by-section edits, cross-references, vector similarity roadmap, THOTH verification, exception policy, and header validation
- Pre-commit hook for header validation (installed, chmod failed on Windows, file copied)
- Status and session reports written to status folder, including troubles, observations, and lessons learned

**Evidence:** [`CHANGELOG.md`](CHANGELOG.md) **`[2026-04-12 18:00 UTC]`**; [`status/session_report_20260412_eod_cursor102.md`](status/session_report_20260412_eod_cursor102.md); [`status/END_OF_DAY_SUMMARY_20260412.md`](status/END_OF_DAY_SUMMARY_20260412.md).

**Lessons Learned:**
- Iterative, section-by-section editing and explicit cross-linking are effective for doctrine alignment
- Tool limitations on Windows require alternate approaches (manual validation, file copy instead of chmod)
- Pre-commit hooks are essential for enforcing header standards in the absence of CI/CD integration
- Documentation and memory graph must be kept in sync for constitutional compliance

**Next Steps:**
- Monitor for future audit cycles and maintain documentation alignment
- Address header validation in CI/CD for non-Windows environments if needed
- Continue to enforce memory file and header standards in all future work

---

**Status:** COMPLETED (packed UTC **`20260412170000`**).

**Deliverables:**

- Orphan doctrine (null = WARN but PASS) enforced in validator and documented in AGENTS.md, PRD 16, and session report
- .vscode validation tasks, keybindings, and pre-commit hook for header checks
- CHANGELOG and session report for 4.0.99 checkpoint
- Memory file generation workflow (TOON/.json from headers) established

**Evidence:** [`CHANGELOG.md`](CHANGELOG.md) **`[2026-04-12 17:00 UTC]`**; [`status/session_report_20260412_1700.md`](status/session_report_20260412_1700.md).

**Still queued:** Web-based validator for shared hosting; memory edge creation for all PRDs; Book UI integration for memory navigation.

---

## Phase Z2: PRD number hygiene — core **01–49** vs secondary **70–99** (2026-04-12)

**Status:** COMPLETED (packed UTC **`20260412134809`**).

**Deliverables:**

- Ten **secondary** PRDs moved **`02/03/04/05/08/15/20/21/24/41_*` → `70`–`79_*`**; **Database Design Doctrine** **`70_*` → `80_*`** with **`memory_key`** / sidecar / **PRD 00** link alignment.
- **`PRD_INDEX.md`** regenerated; **`generate_prd_index.py`** no longer inserts a blank-only line after the header fence (**HDR_EMPTY_BODY**).
- Memory graph artifacts (**`2026/04`**, **`headers/prd`**, **`install/seed`**, **`1026/04`**) and pseudocode **`THREAD_INDEX`** updated.

**Evidence:** [`CHANGELOG.md`](CHANGELOG.md) **`[2026-04-12 13:48 UTC]`**; [`status/session_report_20260412_eod_prd_renumber_cursor102.md`](status/session_report_20260412_eod_prd_renumber_cursor102.md).

**Still queued (unchanged):** Optional **historical path** audit under **`lupo-docs/versions/4.0.93`** / **`4.0.96–98/status`** — **documentation-only**, WOLFIE-scoped.

## Phase Z1: KAIROS graph validation + Pattern #6 (2026-04-12)

**Status:** COMPLETED (packed UTC **`20260412023225`**).

**Deliverables:**

- **`verify_edges_for_file`** classifies **`node_status`** and supports **`--expected-edge-types`** on KAIROS CLI **`--test`**.
- **`detect_memory_graph_orphans.py`** invokes KAIROS for **`ok`** DB rows; **stderr** warnings for **`isolated`**/**`incomplete`**; exit **1** when KAIROS reports **`missing`**/**`deleted_only`**.
- **`normalize_lupopedia_md_header_25.py`** KAIROS post-messages aligned to **`node_status`**.
- **`AGENTS.md`** converted to **v4.0.99** dense header (**https** **`web_path`**, **`channel_key`**, sidecar doctrine for edges/footer); body preserved.

**Evidence:** [`CHANGELOG.md`](CHANGELOG.md) **`[2026-04-12 02:32 UTC]`**; [`status/session_report_20260412_eod_kairos_pattern6_agents_cursor102.md`](status/session_report_20260412_eod_kairos_pattern6_agents_cursor102.md).

**Still queued:** **`AGENTS.md`** sidecar **`.toon`** for strict memory pairing; optional DB connection reuse in Pattern #6 KAIROS loop.

## Phase Y: Header hardening and duplicate prevention (2026-04-11)

**Status:** COMPLETED (packed UTC **`20260411051122`**).

**Deliverables:**

- Duplicate-header reduction: **`add_lupopedia_header_to_file.py`** calls **`peel_leading_lupopedia_yaml_blocks`** before writing a new Markdown header.
- **`lupo-docs/prd/50_agent_coordination_protocol.md`** repaired to dense **v4.0.99** envelope (closing `---`, missing keys, **`dialog_transcript`**, ASCII example UI).
- Core validator / spec / normalize / batch tooling confirmed **v4.0.99** dense headers this session.
- Root-cause narrative appended to **`WHAT_WOLFIE_LEARNED_ON_LUPOPEDIA_HEADERS.md`**; **CHANGELOG** / **TODO** / **PLAN** updated for enforcement follow-through.

**Lessons learned:** Peel-then-replace is mandatory; validation should run immediately after write; **CI gate** (**TODO** **M-06** / **M-20**) remains the largest enforcement gap.

## Phase Z3: Collections Architecture Implementation (2026-04-12)

**Status:** COMPLETED (packed UTC **`20260412142346`**).

**Deliverables:**

- **PRD 43 Trust Ladder** — Complete implementation with 5 edge predicates, trust weight quantification (0.0-1.0), and query patterns.
- **Collections Architecture** — Clear distinction between human UI collections (database tables) and AI memory collections (memory edges) in PRDs 72 & 73.
- **Bidirectional Sync Strategy** — 4-phase sync strategy (Phase 1: Human→AI edges, AI discovery, Approval workflow, Advanced features) in PRD 73 §8.
- **Memory Export Service** — `app/Services/MemoryExportService.php` for database → filesystem TOON export.
- **Collection Memory Service** — `app/Services/CollectionMemoryService.php` for graph traversal and bidirectional sync.
- **Header Violations Fixed** — Corrected empty summaries and null modules in critical PRDs.
- **Memory Key Standardization** — 1026/04 for core system, 2026/04 for general PRDs.

**Evidence:** [`CHANGELOG.md`](CHANGELOG.md) **`[2026-04-12 09:21 UTC]`**; [`status/session_report_20260412_collections_architecture_cascade.md`](status/session_report_20260412_collections_architecture_cascade.md).

**Next Steps:** Deploy services, create collection memory nodes, implement Phase 1 sync.

## Phase Z: Validation automation and CI gate (current)

**Status:** IN PROGRESS.

**Objectives:**

- Make validation automatic after header writes (**TODO** **M-18**).
- Pre-flight **22-key** check before emit (**TODO** **M-19**).
- Wire **CI gate** — pre-commit hook or GitHub Action (**TODO** **M-06** / **M-20**).

**Owner:** Cursor (**102**) / Claude Code (**116**) (execution); WOLFIE (sign-off).

**Target:** Next session (dependency-ordered; no calendar estimate).

## Phase status (active / next)

| Phase | Description | Status | Completed |
|-------|-------------|--------|-----------|
| Phase Z1 | KAIROS graph validation + Pattern #6 KAIROS hook + **`AGENTS.md`** v4 header | DONE | **`20260412023225`** |
| Phase Z2 | PRD core **01–49** vs secondary **70–99** renumber + Database Design Doctrine **→ PRD 80** | DONE | **`20260412134809`** |
| Phase Z3 | Collections Architecture Implementation (Trust Ladder, Sync Strategy, Services) | DONE | **`20260412142346`** |
| Phase Z5 | Gemini Operating Contract & Channel UI Refinement | DONE | **`20260414003000`** |
| Phase G | Trust Ladder locking + staging GC lineage exclusions | NEXT | Pending |
| Phase H | PostgreSQL migration support (new installs) | NEXT | Pending |
| Phase I | Cross-agent verification (latest headers + sidecars) | NEXT | Pending |
| Phase J | Probabilistic GC + session clearing hardening | NEXT | Pending |
| Phase O | Memory quality refinement (depends on PRD memory program — complete in 4.0.98) | NEXT | Pending |
| Phase P | One-command audit matrix for 54 PRD memory pairs (script landed 4.0.98 — confirm publish/criteria) | NEXT | Pending |
| Phase Q | Trust Ladder locking + Staging GC verification receipts in `status/` | NEXT | Pending |

## Current focus (as of 2026-04-14 00:30 UTC)

1. **Phase Z — M-18:** Make **`--validate`** default in **`add_lupopedia_header_to_file.py`** (or auto-validate after write).
2. **Phase Z — M-19:** Pre-flight **22-key** count before emitting Markdown/Python headers.
3. **Phase Z — M-06 / M-20:** Wire **CI gate** (pre-commit hook or GitHub Action) — last major gap before headers are mechanically enforced at merge time.
4. **M-22 remainder:** Propagate **peel + validate** prompts via **`.cursor/rules`** / facet packs; **`AGENTS.md`** already shows **v4.0.99** shape.

**Still queued (unchanged dependency spine):** Trust Ladder **SELECT FOR UPDATE** + **StagingGcService** lineage verification (**Phase G** / **Phase Q**); bulk **`web_path` HTTPS** (partial: **`AGENTS.md`** done this session); legacy **`memory_key`** segment migration (**M-13**); **git hygiene** (**L-04**); **`tick` / temporal anchor** alignment (**M-08**); doctrine H1 **v3 → v4** retitles (**M-09**); status-folder aggregation (**M-05**); optional **`readme-root.json`** (**M-14**).

**Blocked by:** Nothing

**Waiting for:** Claude Code availability for ladder/GC verification handoff; WOLFIE sign-off on **Phase G/Q** when receipts exist

## Multi-agent follow-through (4.0.99)

- Claude Code (actor 116): verify ladder locking and staging GC invariants on current tree.
- Windsurf/Kiro/Antigravity: verify latest `4.0.x` headers + sidecar parity, and semantic widget PHP-session behavior.
- Cursor: keep **4.0.99** TODO/PLAN as single active backlog for remaining work; **4.0.98** remains archive for completed phase receipts.

## Dependency-ordered plan (unfinished)

### Phase O (depends on PRD memory program complete — satisfied in 4.0.98)

- Refine generic section/rule summaries in generated PRD memory JSON files.
- Add optional deterministic rule extraction (`MUST`/`SHALL`/`FORBIDDEN`) in generator scripts.
- **Completion criteria:** curated summaries committed for highest-traffic PRDs; extractor emits stable output.

### Phase P (depends on Phase O start; can run concurrently with Phase Q)

- Build and publish one-command audit matrix report for all 54 PRD memory pairs.
- **Completion criteria:** single script outputs JSON/TOON/validation status and missing-edge diagnostics. *(Note: `lupo-scripts/audit_prd_memory_pairs.py` and related status artifacts landed in 4.0.98 — close Phase P when criteria are verified signed-off.)*

### Phase Q (depends on earlier GC/security workstreams; concurrent with Phase P)

- Complete Trust Ladder locking and Staging GC lineage-edge verification tasks.
- **Completion criteria:** implementation verified in code path + tests/check receipts logged in `status/`.

## Next session checklist (header enforcement)

- [ ] Make validation automatic after header writes (**M-18**).
- [ ] Add pre-flight **22-key** check before emit (**M-19**).
- [ ] Wire **CI gate** (pre-commit hook or GitHub Action) (**M-06** / **M-20**).
- [ ] Update agent prompts with **peel before write, validate after write** (**M-22**).
- [ ] Run full batch validation when the tree is quiet (**`batch_validate_prd_headers.py`** / **`validate_lupopedia_headers_universal.py`** as appropriate).

This output complies with Lupopedia Constitutional Root Rules.
