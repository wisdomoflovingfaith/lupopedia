---
lupopedia.headers:
  lupopedia.schema: plan
  file_path_from_root: "lupo-docs/versions/4.0.89/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/PLAN.md"
  federation_node_id: 0
  last_modified_utc: "20260329210000"
  when_updated: "20260329210000"
  channel_id: 42
  thread_id: "4-0-89-headers-plan"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:root"
  artifact_type: plan
  artifact_kind: plan
  purpose: Dependency-ordered plan for 4.0.89 LUPOPEDIA HEADERS release — Python (IDE) + PHP (shared hosting) import/validate/regenerate parity (TODO H8); PHP agent filesystem guard + verify (TODO H9)
  tags:
    - "4.0.89"
    - headers
    - plan
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.89/README.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/versions/4.0.89/TODO.md"
      type: implements
      weight: 1.0
    - to: "lupo-docs/versions/4.0.90/PLAN.md"
      type: references
      weight: 0.9
      reason: Product roadmap after header release
    - to: "lupo-docs/doctrine/TASK_PLANNING_DOCTRINE.md"
      type: references
      weight: 1.0
lupopedia.footer:
  last_verified: "20260329210000"
  verified_by:
    identity_type: actor
    actor_id: 12
    agent_name_identity: "ATHENA"
    department_id_delta: 0
  verified_via:
    type: direct
    faucet_slug: none
  orchestrator: "athena:root"
  next_action:
    - "Execute phases in order; update when complete"
    - "Point non-header work to 4.0.90/PLAN.md"
---

# Development plan — Version 4.0.89 (LUPOPEDIA HEADERS)

**Scope:** Same as [`README.md`](README.md) — **headers, validation, import** (Python + PHP toolchains; [`TODO.md`](TODO.md) **H8**), **PHP agent filesystem policy** (**H9**, **`AgentFileWriter`**, optional [`PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`](PHP_AGENT_FILESYSTEM_DEPLOYMENT.md)), **`lupo-*`** organization, IDE rules, header-related DB, release testing.

**Not in scope:** Context DB, Crafty Syntax implementation, doc-clarity 5.1–5.4 — see [`../4.0.90/PLAN.md`](../4.0.90/PLAN.md).

**Phasing:** Dependency order only — [`TASK_PLANNING_DOCTRINE.md`](../../doctrine/TASK_PLANNING_DOCTRINE.md).

---

## Phase A — Doctrine and documentation frozen for release

**Completion criteria:** No open contradictions between root binding doctrine, FORMAT, TAXONOMY_REFERENCE, VALIDATORS_AND_TOOLING, and consolidation note; alias file remains pointer-only.

**Depends on:** Nothing.

---

## Phase B — Python validation baseline

**Completion criteria:** `TODO.md` H1.* verified; scripts run clean on representative files.

**Depends on:** Phase A.

---

## Phase C — PHP validation + operator surface

**Completion criteria:** `TODO.md` H2.* — **`validate_lupopedia_headers.php`** / **`HeaderDbSync`** aligned with Python baseline (VERIFY); legacy `LupopediaHeaderValidator.php` refresh or documented delta; admin/operator path **identified or implemented**.

**Depends on:** Phase B (same rules must be knowable from doctrine).

---

## Phase D — Import / regenerate / DB parity

**Completion criteria:** `TODO.md` H3.* — schema and JSON mirrors match code; migrations documented if needed. **`TODO.md` H7** — at least one artifact proves **`lupopedia.history`** round-trip to **`lupo_contents.revision_history`** (import → query → regenerate → validate), with outcome recorded.

**Depends on:** Phase B.

---

## Phase E — Repository organization + IDE rules

**Completion criteria:** `TODO.md` H4.* — rule files and IDE packs aligned; propagation documented; **H4.4** repository literacy (root + `lupo-docs/ORGANIZATION.md` §2.2) verified for release participants.

**Depends on:** Phase A.

---

## Phase F — Release-gate testing + tag

**Completion criteria:** `TODO.md` H5.* matrix executed and recorded; **H7** running-log round-trip recorded; **H8.5–H8.6** cross-toolchain checks on target host (or waived with risk note); **H9** — `AgentFileWriter` paths verified for PHP `--write-back` / regenerate under allowed trees; optional web hardening per **`PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`** applied as operator chooses; WOLFIE approves tag **4.0.89**.

**Depends on:** Phases B, C, D complete; E may complete in parallel with D.

---

## Historical note

The **pre-refocus** ATHENA plan (context + Crafty + week-style phases), **LILITH retrospective**, and **resource/risk** sections that lived in this file were **superseded** for **release planning** on 2026-03-28. That narrative is **preserved** in git history and summarized in **[`../4.0.90/PLAN.md`](../4.0.90/PLAN.md) Appendix** and **[`CHANGELOG.md`](CHANGELOG.md)**.

---

**ATHENA (actor_id 12)** — 4.0.89 plan refocused to headers release 2026-03-28; PHP dual-toolchain + H9 verify hooks in Phase F 2026-03-29.
