---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/cursor/prompts/20260314_cursor_pass3_toon_seed_cleanup_4_0_74.md
  web_path: https://www.lupopedia.com/lupopedia/actors/cursor/prompts/20260314_cursor_pass3_toon_seed_cleanup_4_0_74.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: implementation-directive
  artifact_kind: execution
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: directive
  prd_cluster: null
  title: null
  summary: null
---
# Cursor Implementation Directive — Pass 3 TOON alignment, seed integration, and canonical cleanup (v4.0.74)

Cursor, Pass 2 completed the root documentation alignment successfully.

This next pass is for the remaining implementation items that are still materially important for 4.0.74:

1. **TOON generation/output path truth**
2. **Installer seed integration decision**
3. **Broader canonical cleanup in touched files**
4. **Schema/documentation consistency evidence**
5. **P1 merge-process and inventory groundwork where directly implementable**

Do not produce another planning-only response.
Implement verified corrections and then document exactly what changed.

---

## Primary goals

### Goal 1 — Resolve TOON path/output truth
Verify TOON-related scripts, output paths, and align documentation to verified truth.

### Goal 2 — Resolve `seed_projects.sql` installer status
Either wire `seed_projects.sql` into installer seed execution or document manual status clearly.

### Goal 3 — Build a single schema inventory artifact or section
Create or update a canonical comparison: install SQL tables, TOON coverage, registry counts (truthful; distinguish authority vs derived vs gaps).

### Goal 4 — Continue canonical cleanup in touched files only
Prefer `lupopedia.*`; correct misused `lupopedia.init`; prefer `lupopedia.next_actions` where needed.

### Goal 5 — Strengthen merge-process clarity
Document merge rules: when faucet-specific files remain authoritative vs when root canon absorbs; practical rules only.

---

## Execution order

1. Verify TOON scripts, path references, and output locations
2. Verify installer seed execution flow and `seed_projects.sql` status
3. Implement whichever verified fixes are safe
4. Create/update schema inventory comparison artifact or section
5. Apply any directly related canonical cleanup in touched files
6. Update `CHANGELOG.md`
7. Update `docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md`

---

*Cursor (actor_id 102) — Pass 3 directive 2026-03-14*
