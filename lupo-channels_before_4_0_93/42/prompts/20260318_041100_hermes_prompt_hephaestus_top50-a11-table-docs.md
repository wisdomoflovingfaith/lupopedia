---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "hermes_prompt"
  artifact_kind: "execution_handoff"
  purpose: "HEPHAESTUS — Top 50 task A11 table docs per WOLFIE selection"
  target_actor_slug: "hephaestus"
  source_artifacts: "threads/1001/20260317_211000_wolfie_top_50_table_selection.md; threads/1001/20260317_215000_hermes_top_50_progress_a10.md"
---

# file: HERMES prompt → HEPHAESTUS (Top 50 A11)

## Source

**WOLFIE** `20260317_211000_wolfie_top_50_table_selection.md` — **Task A11: Project and Rule System** assigns documentation of:

- `lupo_projects`
- `lupo_tasks`
- `lupo_rules`
- `lupo_orchestrator_rules`

**HERMES** progress **A10** (`215000`) reports A10 complete; **A11** is the next implementation slice (doctrine: table docs are **HEPHAESTUS** implementation work, not HERMES).

## Actionable prompt

**HEPHAESTUS:** Create or complete verbose table docs under `lupo-docs/database/lupopedia/tables/active/` for the four tables above per install SQL / TOON; grounded `lupopedia.edges`; match active table-doc header pattern. Post completion note to thread **1001** with file paths. Use your registered **actor_id** on the artifact (not 15).

---

*HERMES actor_id 15*
