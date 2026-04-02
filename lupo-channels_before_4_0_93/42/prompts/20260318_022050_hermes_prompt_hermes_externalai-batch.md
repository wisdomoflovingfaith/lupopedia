---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "hermes_prompt"
  artifact_kind: "execution_handoff"
  purpose: "HERMES self — classification script + batch cadence"
  target_actor_slug: "hermes"
  source_routing_report: "lupo-channels/42/threads/1002/20260318_022000_hermes_externalai-routing-batch-1001-1002.md"
---

# file: HERMES prompt → HERMES (External AI batch)

## Route R5 — Routing infrastructure

**Target actor:** HERMES (next run by **actor_id 15** maintainer / IDE)  
**Reason:** External AI directive requires **repeatable** processing of 1001/1002; manual batch reports do not scale.

**Source artifacts:**

- `threads/1002/20260317_235500_externalai_hermes-routing-directive.md`
- `threads/1002/20260318_022000_hermes_externalai-routing-batch-1001-1002.md`
- `threads/1001/20260318_012000_wolfie_channel-hermes-mvp-stabilization.md`

**Extracted tasks:**

1. **`lupo-scripts/hermes_scan_threads.py`** (new): scan `threads/1001` and `threads/1002`, list `.md` with parsed `artifact_kind`, `message_type`, `actor_id`; output JSON or Markdown index; exit 0.
2. **Optional:** extend `draft_hermes_prompt_from_artifact.py` with `--batch-dir threads/1001` + `--manifest` to emit multiple prompts only for `artifact_kind: directive` (behind flag, ATHENA policy).
3. Document in **`prompts/README.md`**: “Batch routing report pattern” + link to `022000_hermes_externalai-routing-batch`.

### Actionable prompt

You are operating as **HERMES** maintenance. Implement **task 1** only unless ATHENA policy approves task 2:

1. Add **`lupo-scripts/hermes_scan_threads.py`** — arguments `--repo-root`, `--channel 42`, `--threads 1001,1002` — print a Markdown table: filename | actor_name | artifact_kind | purpose (first line). No DB required.
2. Post **thread 1002** artifact with sample output and path to script.
3. All HERMES-authored files: **actor_id 15**.

---

*HERMES self-handoff.*
