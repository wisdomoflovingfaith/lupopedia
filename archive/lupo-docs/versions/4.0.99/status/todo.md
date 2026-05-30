# TODO — Lupopedia v4.0.99
**Last updated:** 2026-04-14 by Auggie (Augment Agent)

---

## Completed (2026-04-14)

- [x] Fix install SQL UTF-8 corruption and box-drawing characters (Claude Code, 16:00–21:00 UTC)
- [x] Restore `lupo_agent_tool_calls` columns (`tokensULT` → `tokens_completion`, `tokens_total`, `cost_usd`) (Claude Code)
- [x] Fix `lupo_truth_answers` corrupt PK column (`bigih_answer_id` → `bigint`) (Claude Code)
- [x] Fix `lupo_projects` duplicate defastrator index (Claude Code)
- [x] Fix `lupo_uploads` missing `created_ymdhis` / `updated_ymdhis` columns (Claude Code)
- [x] Remove `ENGINE=InnoDB COLLATE=` clauses from `lupo_dialog_recent_files`, `lupo_dialog_pending_tasks` (Claude Code)
- [x] Update `lupo_votes`, `lupo_memory_nodes`, `lupo_paths_daily` schemas to match JSON (Claude Code)
- [x] Generate clean DDL from 179 JSON schemas → `install_new_lupopedia_clean.sql` (Claude Code)
- [x] Add `lupo_dialog_read_log` to `install_new_lupopedia.sql` (line 2565) (Claude Code)
- [x] Identify missing table (`lupo_dialog_read_log`) — investigation + report (Auggie)
- [x] Add Task Manager System section to PRD 02 (Auggie)
- [x] Write `missing_table_analysis.md` (Auggie)
- [x] Update `changelog.md` (Claude + Auggie entries) (Auggie)
- [x] Create `todo.md`, `plan.md`, session report (Auggie)

---

## High Priority

- [ ] Create `lupo_dialog_read_log.json` schema file (does not exist on disk — Claude crash may have cut write)
- [ ] Re-install live DB **or** apply `ALTER TABLE` to add `lupo_dialog_read_log` (live DB has 178 tables; install SQL now has 179)
- [ ] Run `generate_toon_files.py` against re-installed DB to regenerate 179 JSON + TOON files

---

## Medium Priority

- [ ] OQ-04: Add `last_read_created_ymdhis BIGINT NULL` to `lupo_dialog_read_log` (per open_questions.md)
- [ ] OQ-32: Fix malformed header in `CHRONOLOGICAL_TRUST_LADDER.md`
- [ ] Add `lupo_contents.json` missing index column names (`idx_has_events`, `idx_has_hashtags`, `idx_has_media`) — skipped by clean DDL generator
- [ ] Append seed INSERT data to `install_new_lupopedia_clean.sql` so it can replace canonical install file

---

## Low Priority

- [x] Clean up old changelog entries (archive) (Gemini)
- [ ] OQ-33: Write normative TOON format spec (dual format currently handled by `toon_bridge.py` without written spec)
- [ ] Generate `memory_key` `.toon` file for `generate_toon_files.py` itself
- [ ] Audit other doctrine files for broken/missing TOON pointers (OQ-34 — flagged by Claude's session)

---

## Blocked

- None currently

---

## Notes

- `install_new_lupopedia_clean.sql` is DDL-only (no seed data). Do not use as drop-in replacement until seed data is appended.
- Live DB was created from pre-fix install SQL. Table count will be 179 only after re-install or ALTER TABLE.
- PyYAML installed by Wolfie for header validation scripts.
. Do not use as drop-in replacement until seed data is appended.
- Live DB was created from pre-fix install SQL. Table count will be 179 only after re-install or ALTER TABLE.
- PyYAML installed by Wolfie for header validation scripts.
