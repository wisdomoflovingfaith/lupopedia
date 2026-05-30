---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/agents/cascade/handoff/TOON_DB_HANDOFF_FOR_CASCADE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/agents/cascade/handoff/TOON_DB_HANDOFF_FOR_CASCADE.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/database/canonical/1026/04/cascade-windsurf-db-handoff.toon
  atoms_toon: null
  transcript_jsonl: 0/database/cascade-db-handoff
  artifact_type: documentation
  artifact_kind: guide
  channel_key: database
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: TOON DB handoff for Cascade/Windsurf (database facet bootstrap)
  summary: Bootstrap for Cascade taking actor 102 database lane duties; canonical install DDL, PRD 13, no migrations, channel isolation.
---
# TOON: CASCADE_DB_BOOTSTRAP_v1

## ROLE

Cascade is taking over actor_id 102 duties temporarily. Cascade operates ONLY in `channel_key: database`. Cascade mirrors live DB changes into canonical DDL and PRDs. Cascade does NOT generate migrations.

## DOCTRINE

- No migration system exists in Lupopedia.
- `database/lupopedia/mysql/install/install_new_lupopedia.sql` is the single canonical schema (path in repo as of this handoff).
- Wolfie may edit the live DB directly in phpMyAdmin.
- Cascade must mirror those changes into:
  - `install_new_lupopedia.sql`
  - PRD 13 (`docs/prd/13_crafty_integration.md` and related database doctrine)
  - any schema-related TOON files that describe tables aligned with install
- No cross-channel inference.
- No UI, blog, or orchestration tasks.
- No hallucinated tables or schema.
- No vendor-specific SQL unless already present in an explicitly approved artifact.

## CURRENT CONTEXT

Cursor has been performing heavy schema analysis and is temporarily stepping aside due to load. Cascade is fresh and will continue the work.

**Related plan docs (read first):** `docs/versions/4.1.3/TODO_DATABASE_MIGRATION.md`, `docs/versions/4.1.3/PLAN_DATABASE_MIGRATION.md`.

## TASKS FOR CASCADE

1. Maintain canonical DDL in `install_new_lupopedia.sql`.
2. Update PRD 13 when schema changes occur.
3. Keep schema aligned with doctrine (no FKs, packed UTC BIGINT, ASCII identifiers).
4. Assist Wolfie with DB refactors as requested.
5. Avoid migration generation unless explicitly asked for a one-off helper script.
6. Maintain strict `channel_key` isolation.

## WHAT NOT TO DO

- Do not generate migrations.
- Do not modify other channels.
- Do not unify personas.
- Do not rewrite doctrine without Wolfie direction.
- Do not invent schema.

## HANDOFF STATUS

Cursor is handing off database duties to Cascade for the next 24 hours. Cascade should acknowledge and begin in database-only mode.

End of TOON.
