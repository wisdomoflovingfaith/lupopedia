---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "hermes_prompt"
  artifact_kind: "execution_handoff"
  purpose: "WOLFIE — 4.0.80 release readiness report + 4.0.81 DB-channel plan"
  target_actor_slug: "wolfie"
  source_directive: "lupo-channels/42/threads/1001/20260318_000500_wisdomoflovingfaith_release-readiness-4.0.80.md"
---

# file: HERMES prompt → WOLFIE (4.0.80 release readiness)

## Route — Release consolidation + 4.0.81 transition

- **target actor:** WOLFIE (actor_id **1**)
- **reason:** Human directive **000500** — reconcile file-based `lupo-channels/` work with CHANGELOG/TODO/plan; close 4.0.80; define DB-first path for 4.0.81 so external systems can read state.
- **source artifacts:**
  - `threads/1001/20260318_000500_wisdomoflovingfaith_release-readiness-4.0.80.md` (directive)
  - `threads/1001/20260318_012000_wolfie_channel-hermes-mvp-stabilization.md`
  - `threads/1002/20260318_022000_hermes_externalai-routing-batch-1001-1002.md`
  - Root: `CHANGELOG.md` § **[4.0.80]**, `TODO.md`, `plan.md`
  - `lupo-docs/versions/4.0.80/PLAN.md` (and TODO if any)
  - Inventory: run `python lupo-scripts/hermes_scan_threads.py --channel 42 --threads 1001,1002`
- **extracted tasks:**
  1. Build a **matrix**: each open item in `TODO.md` + unchecked boxes in `plan.md` → status **done / partial / open** with evidence (file path or commit area).
  2. List **channel-only completions** not yet summarized in CHANGELOG (e.g. ATER001, external AI batch, release-readiness directive) → bullets ready to paste into CHANGELOG **[4.0.80]** when you approve release.
  3. **P0 for 4.0.80:** minimum bar to set **Release Date** on CHANGELOG (e.g. tests green, doctrine consistent, table-doc repair explicitly closed or deferred).
  4. **P1/P2:** prompts listing, CI enforce, HEPHAESTUS items, ATHENA watcher policy — slot into pre- or post-4.0.80.
  5. **4.0.81 section:** 5–10 bullets — DB as source of truth; sync from FS → DB; read API / export for external AI; **no** full implementation in this task (planning only).

### Actionable prompt

You are **WOLFIE**. Execute **now**:

1. **Read** the sources listed above. Use `hermes_scan_threads.py` output as the checklist of thread filenames to cross-check against CHANGELOG mentions.

2. **Write** exactly one new file:
   - Path: `lupo-channels/42/threads/1001/YYYYMMDD_HHIISS_wolfie_4.0.80_release-readiness.md`
   - Use UTC time in the filename.
   - YAML headers: `actor_id: 1`, `actor_name: wolfie`, `artifact_kind: release_readiness_report`, `responds_to: 20260318_000500_wisdomoflovingfaith_release-readiness-4.0.80.md`.

3. **Inside that file**, include these **mandatory sections** (use `##` headings):

   - **## 4.0.80 status summary** — one paragraph + confidence (high/medium/low) to tag a release.
   - **## Completed work (canonicalized)** — table or bullets: *item* | *evidence path* | *in CHANGELOG? y/n*. Include channel artifacts that completed work but lack changelog lines.
   - **## Remaining for 4.0.80 (P0 / P1 / P2)** — concrete owners where known (WOLFIE, HEPHAESTUS, HERMES, LILITH, ATHENA).
   - **## Deferred to 4.0.81** — DB-backed channels, ingestion, external read model, watcher automation — each as one bullet with **one** acceptance criterion.
   - **## Recommended CHANGELOG edits** — paste-ready bullets for Cursor/lead to apply before **Release Date: TBD** becomes a real date.

4. **Do not** implement 4.0.81 code in this pass — **report only**.

5. After the file exists, **HERMES** (or orchestrator) can run `draft_hermes_prompt_from_artifact.py` from your report to fan out tasks to HEPHAESTUS/LILITH if needed.

---

*HERMES — actor_id 15 — handoff for execution. Not a summary; WOLFIE must produce the named deliverable file.*

---

## CLOSED (WOLFIE)

**2026-03-18** — Deliverable: [051500_wolfie_4.0.80_release-readiness](../../51/threads/1001/20260318_051500_wolfie_4.0.80_release-readiness.md).
