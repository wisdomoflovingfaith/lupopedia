---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260411213532"
  file_path_from_root: "docs/versions/4.0.99/crafty_import_notes.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.0.99/crafty_import_notes.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/1026/04/crafty-import-notes.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: "crafty-import-notes"
  content_id: null
  pk_id: null
  pk_slug: "crafty-import-notes"
  title: "Crafty 3.7.5 → Lupopedia import — non-scored proposals & CI hazards"
  status: "active"
  parent_pk_id: ""
  summary: "P-13 promotion blocked until SYNAPSE Hazard #18 adopted per MIGRATION_HAZARD_REMEDIATION.md; P-11–P-13, CI-1–CI-4; §3.1 edges #15–#20 in BREAKTHROUGH_REGISTRY; not §5-scored until WOLFIE promotes."
  module: null
  dialog_transcript: "0/development/crafty-import-notes"
---
# Crafty 3.7.5 → Lupopedia import notes

**Source:** Extracted from **`BREAKTHROUGH_REGISTRY.md`** (**§2.14** / **§3.4**) on **2026-04-11** (**ARA** final consolidation) to keep the registry dense. **Not scored** until promoted to **§2.1** / **§3.1**.

**Canonical SQL:** [`import_from_old_crafty_syntax.sql`](../../database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql), sample dump [`old_crafty_syntax_3_7_5_start.sql`](../../database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql). **Hazard remediation (SYNAPSE #15–#20):** [`MIGRATION_HAZARD_REMEDIATION.md`](MIGRATION_HAZARD_REMEDIATION.md). **Scored edge cases:** [`BREAKTHROUGH_REGISTRY.md`](BREAKTHROUGH_REGISTRY.md) **§3.1**. **Analysis:** [`analysis/wolfie/SUMMARY.md`](analysis/wolfie/SUMMARY.md), [`analysis/wolfie/crafty_data_analysis.md`](analysis/wolfie/crafty_data_analysis.md).

**P-13 gate (SYNAPSE):** **P-13** must **not** be promoted while it only “formalizes” the **existing** timestamp **`CASE`** in the import SQL. **§3.1 edge #18** and **MIGRATION_HAZARD_REMEDIATION.md** **Hazard #18** show that **`ELSE` / `LPAD`** without a **numeric** guard produces **invalid** 14-digit values from **NULL** / garbage **`whendone`**. **Promotion** requires adopting that **hardened** logic (and **WOLFIE** / **edge #10** sentinel policy where **`0`** is forbidden).

---

## Proposed patterns (working titles — AI WOLFIE)

**Not registered in §2.1 — no §5 points until human WOLFIE accepts and rows are promoted.**

| Prop # | Pattern (working title) | Proposed by | Summary |
|--------|-------------------------|-------------|---------|
| **P-11** | **Import-time path aggregation discipline** | AI WOLFIE | Align legacy **`livehelp_paths_*`** → **`lupo_paths`** with downstream **`lupo_paths_daily` / `lupo_paths_monthly`** (if distinct rollups are required beyond current `lupo_paths` import). |
| **P-12** | **Legacy transcript threading policy** | AI WOLFIE | Import maps all threads to **channel_id = 1** and **actor placeholders = 1** — define promotion rules to real channels/actors post-import. |
| **P-13** | **Import timestamp normalization doctrine** | AI WOLFIE | **Blocked until amended:** must incorporate **§3.1 edge #18** / **MIGRATION_HAZARD_REMEDIATION.md** **Hazard #18** — **numeric / NULL guard** before **`LPAD`**, **WOLFIE** sentinel vs bare **`0`** per **edge #10**; then formalize **14 / 13 / 8 / pad** branches as audited, fixture-tested rules (supersedes “copy existing **CASE** only”). |

---

## Proposed import hazards (CI- codes)

**Distinct from scored registry edge cases #1–#7.** Use **CI-** prefix to avoid colliding with **§3.1** numbering.

| Code | Hazard | Handling hint |
|------|--------|---------------|
| **CI-1** | **Legacy timestamp shapes** (`whendone`, 6- vs 8-digit `dateof`) | Pair with **Hazard #18** — shapes **plus** **NULL** / non-numeric garbage defense; add **fixture tests** on edge values **and** invalid strings. See **MIGRATION_HAZARD_REMEDIATION.md**. |
| **CI-2** | **Transcripts without real operator linkage** in Lupo shape | Import uses **placeholder** `from_actor_id` / `channel_id`; reconcile after import. |
| **CI-3** | **Department hybrid actors (`280000 + department_id`)** vs Crafty **`10000 + user_id`** | Document bands; avoid allocator collisions in future features. |
| **CI-4** | **Path identity loops** (`visit_recno` / `exit_recno` patterns) | Validate analytics semantics; filter or mark in rollup if self-edges skew metrics. |

---

This output complies with Lupopedia Constitutional Root Rules.
