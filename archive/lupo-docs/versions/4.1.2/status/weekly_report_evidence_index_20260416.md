---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/versions/4.1.2/status/weekly_report_evidence_index_20260416.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.2/status/weekly_report_evidence_index_20260416.md"
  status: "active"
  when_updated: "20260416231833"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/weekly-report-evidence-20260416.toon"
  atoms_toon: "lupo-memory/atoms/1026/04/weekly_report_helen_20260416.atoms.toon"
  transcript_jsonl: "0/development/weekly-report-evidence-20260416"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: null
  default_collection_id: null
  lupopedia.schema: documentation
  title: "Weekly Report Evidence Index (2026-04-16 week)"
  summary: "Artifact paths backing REPORT_EMAIL_TO_HELEN_2026_04_16.md claims for audit and handoff verification."
---
# Weekly Report Evidence Index (2026-04-16 week)

This file lists repository artifacts that substantiate claims in `REPORT_EMAIL_TO_HELEN_2026_04_16.md`. It does not restate the report.

## Section 1 (Executive summary)

| Claim | Evidence path |
|-------|----------------|
| Operator mockup iteration | `channels/mockup_try2.htm` |
| Hermes-style wiring (route, transcript, staging, tasks) | `channels/index.php` (`hermes_*` functions, `hermes_write_to_transcript`, `hermes_write_to_staging_toon`, `hermes_create_pending_task`) |
| Hermes doctrine / protocol | `lupo-docs/doctrine/HERMES_DOCTRINE.md`, `lupo-docs/doctrine/HERMES_MEMORY_GATEWAY_PROTOCOL.md`, `lupo-docs/prd/82_hermes_message_routing_memory_gateway.md` |
| Translation channel + model | `lupo-channels/0/translation/README.md`, `lupo-channels/0/translation/concepts/*.md`, `lupo-docs/doctrine/system/TRANSLATION_MODEL.md`, `lupo-channels/channel_index.md` (row `translation`) |
| Agent handoffs | `lupo-memory/development/staging/2026/04/antigravity_handoff.toon`, `cursor_handoff.toon`, `gemini_handoff.toon` |
| Changelog buffer (pending) | `lupo-changelog-pending/` (see `README.md`); example fragment `lupo-changelog-pending/20260416163500_antigravity.md` |
| Changelog buffer (merged / archived) | `lupo-docs/versions/4.1.2/CHANGELOG.md`; processed fragments under `lupo-docs/versions/4.1.2/buffer/archive/` |
| Append-only channel transcript (JSONL) | Written beside channel id: `lupo-channels/{channel_id}/transcript.jsonl` (see `hermes_write_to_transcript` in `channels/index.php`). Example checked in repo: `lupo-channels/0/captains_log/the-four-engine-render-ordeal.jsonl/transcript.jsonl` |

## Section 4 (Crafty to Lupopedia)

| Claim | Evidence path |
|-------|----------------|
| Import/transform (not executing legacy tree as engine) | `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` |
| Translation concept | `lupo-channels/0/translation/concepts/08_crafty_syntax_migration.md` (aligned with import model) |

## Sections 5–7 (Continuity, fall-forward, memory)

| Claim | Evidence path |
|-------|----------------|
| Continuity doctrine (canonical) | `lupo-docs/doctrine/CONTINUITY_LAYER_DOCTRINE.md` |
| Translation continuity concept | `lupo-channels/0/translation/concepts/01_continuity_layer.md` |
| Fall-forward concept | `lupo-channels/0/translation/concepts/02_fall_forward_design.md` |
| Memory / staged vs approved | `lupo-channels/0/translation/concepts/03_memory_system.md`, `04_staged_memory.md` |
| PRD references | `lupo-docs/prd/38_memory_unification.md`, `lupo-docs/prd/43_parent_child_trust_ladder.md` |

## Section 8 (Handoff persistence)

| Claim | Evidence path |
|-------|----------------|
| Handoff concepts | `lupo-channels/0/translation/concepts/05_handoff_toons.md`, `06_disposable_agents.md` |
| Staging memory path pattern | `channels/index.php` (`hermes_write_to_staging_toon` writes under `lupo-memory/{channel_key}/staging/{YYYY}/{MM}/`) |

## Section 10 (Path / referer edges)

| Claim | Evidence path |
|-------|----------------|
| Translation concept | `lupo-channels/0/translation/concepts/09_path_referer_edges.md` |
| Schema (paths / aggregates) | `lupo-database/lupopedia/mysql/install/install_new_lupopedia_clean.sql` (paths, paths_daily, paths_monthly sections) |

## Section 11 (DMV precedent)

| Claim | Evidence path |
|-------|----------------|
| Captain narrative | `lupo-content/federation_node/0/captains_log/20260416_dmv_1999_continuity_precedent.md` |

## Section 12 (Shipped items + OQ-58)

| Claim | Evidence path |
|-------|----------------|
| Agent task poll / complete | `lupo-bin/agent_poll_tasks.php`, `lupo-api/v1/tasks/complete.php` |
| OQ-58 open | `lupo-docs/versions/4.1.2/status/open_questions.md` section `## OQ-58: Task model unification` (`STATUS: open`) |

## HEADER (`REPORT_EMAIL_TO_HELEN_2026_04_16.md`)

| Pointer | Evidence path |
|---------|----------------|
| `memory_toon` (JSON + TOON pair) | `lupo-memory/development/canonical/1026/04/weekly_report_helen_20260416.json` and `.toon` |
| `atoms_toon` | `lupo-memory/atoms/1026/04/weekly_report_helen_20260416.atoms.toon` |
| `transcript_jsonl` | `lupo-channels/0/development/weekly_report_helen_20260416.jsonl/transcript.jsonl` (with `THREAD_MANIFEST.md` in the same thread folder) |
| Machine inventory JSONL | `lupo-channels/0/development/weekly_report_helen_20260416.jsonl/report_helen_20260416_related_files.jsonl` |
| This file's transcript thread | `lupo-channels/0/development/weekly-report-evidence-20260416/transcript.jsonl` |

## Note on `lupo-changelog-archive`

There is **no** `lupo-changelog-archive/` **directory**. A root-level file named `lupo-changelog-archive` (no extension) holds a single historical fragment; processed 4.1.2 buffer fragments otherwise live under `lupo-docs/versions/4.1.2/buffer/archive/` per merge workflow documented in `lupo-docs/versions/4.1.2/CHANGELOG.md`.
