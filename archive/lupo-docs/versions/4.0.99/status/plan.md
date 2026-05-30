# Plan — Lupopedia v4.0.99
**Last updated:** 2026-04-14 by Auggie (Augment Agent)

---

## Current Status

- **Phase:** Database Schema Stabilization → mostly complete; one live-DB action remaining
- **Overall completion:** ~95%
- **Blockers:** None

---

## Phase Summary

| Phase | Description | Status |
|-------|-------------|--------|
| 1 | Constitutional header standardization | COMPLETE |
| 2 | Database schema audit | COMPLETE |
| 3 | Install SQL repair (corruption, UTF-8, missing tables) | COMPLETE (install SQL) |
| 3a | Live DB sync | PENDING (re-install or ALTER TABLE needed) |
| 4 | JSON/TOON schema export (179 files) | COMPLETE (per install SQL); 1 JSON file missing on disk |
| 5 | PRD documentation updates | IN PROGRESS |
| 6 | Testing and validation | NOT STARTED |

---

## Accomplished (2026-04-14)

### Claude Code (actor_id 116) — ~16:00–21:00 UTC
- Constitutional audit of all 178 SQL tables vs 179 JSON schemas → `claude_database_table_review.md`
- Repaired install SQL in 5 rounds: UTF-8 corruption, box-drawing characters, 9 table schema fixes, 1 new table added
- Generated `install_new_lupopedia_clean.sql` (pure DDL, ASCII-clean, doctrine-clean, 4,149 lines)
- Added `lupo_dialog_read_log` CREATE TABLE to canonical install SQL (line 2565)

### Wolfie (actor_id 1)
- Installed PyYAML for header validation scripts
- Orchestrated all sessions

### Auggie (Augment Code)
- Added Task Manager System section to PRD 02 — centralized task queue specification for agent coordination
- Identified `lupo_dialog_read_log` as missing from both live DB and JSON dir; traced root cause
- Wrote `missing_table_analysis.md` with schema, evidence, and recommendation
- Documented session (changelog, todo, plan, session report)

### Gemini (Gemini CLI)
- Synchronized `install_new_lupopedia.sql` with live schemas: `lupo_votes` (polymorphic), `lupo_memory_nodes` (vectors), and analytics rollups.
- Implemented Task Management System (TMS): `TaskService.php`, REST APIs, and [task] command parser.
- Integrated TMS UI into Chat Command Center sidebar with real-time polling.
- Performed changelog archival (entries > 3 days moved to archive).

---

## Next Steps (Priority Order)

### Immediate (before next development work)
1. **Create `lupo_dialog_read_log.json`** — file does not exist on disk; Claude crash cut the write
2. **Sync live DB** — either re-run fresh install or apply manual `ALTER TABLE` for `lupo_dialog_read_log`
3. **Run `generate_toon_files.py`** — regenerates all 179 JSON + TOON files from live DB; creates the missing JSON file

### Short-term
4. **OQ-04** — Add `last_read_created_ymdhis BIGINT NULL` to `lupo_dialog_read_log` schema
5. **OQ-32** — Fix malformed header in `CHRONOLOGICAL_TRUST_LADDER.md`
6. **Append seed data** to `install_new_lupopedia_clean.sql` so it can serve as a drop-in replacement

### Deferred
7. **OQ-33** — Write TOON format normative spec
8. **OQ-34** — Audit doctrine files for broken TOON pointers

---

## Architecture Decisions Made This Session

| Decision | Rationale |
|----------|-----------|
| Task Manager uses `{{prefix}}tasks` table (not `dialog_pending_tasks`) | `dialog_pending_tasks` is for simple task assignment; `tasks` is the full-featured queue with priority, dependencies, status tracking |
| Agents poll `/api/tasks/next` — do NOT read chat for instructions | Chat is write-only for builder agents (constitutional rule per PRD 02) |
| `lupo_dialog_read_log` verdict: NEEDED | Active PHP code (`DialogMvpService::updateReadLog`) writes to it; without it, runtime SQL errors occur |

---

## Risks

| Risk | Severity | Mitigation |
|------|----------|------------|
| Live DB has 178 tables; PHP code expects 179 | HIGH | Re-install DB or ALTER TABLE before going live |
| `lupo_dialog_read_log.json` missing from disk | MED | Re-create from `install_new_lupopedia_clean.sql` schema or via `generate_toon_files.py` |
| `install_new_lupopedia_clean.sql` is DDL-only | MED | Append seed data before using as canonical installer |
| Agent token crashes lose work | LOW | Claude's practice of self-documenting before crash is good; enforce this protocol |
