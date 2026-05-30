---
lupopedia.headers:
  when_updated: "20260324175200"
  lupopedia.schema: "channel_artifact"
  file_path_from_root: "lupo-channels/62/threads/folder_organization/20260324_175200_cursor_organization_cleanup_and_validation_report.md"
  web_path: "http://www.lupopedia.com/lupo-channels/62/threads/folder_organization/20260324_175200_cursor_organization_cleanup_and_validation_report.md"
  questions_toon: null
  channel_id: 62
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "report"
  artifact_kind: "organization_update"
  purpose: "Record organization and validation actions for docs, headers, and footer freshness"
  tags: ["organization", "validation", "headers", "footer", "database_docs"]
lupopedia.footer:
  last_verified: "20260324175200"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "cursor:root"
  next_action:
    - "Prioritize missing active table docs listed in VALIDATION_REPORT_JUNIE.md"
    - "Backfill frontmatter for table docs without headers"
    - "Complete root-level stale file archival sweep"
---
# file: channel 62 organization cleanup report - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-channels/62/threads/folder_organization/20260324_175200_cursor_organization_cleanup_and_validation_report.md

# Organization and Validation Update (2026-03-24)

## Completed in this pass

- Repaired `lupo-docs/doctrine/LUPOPEDIA_HEADERS/*` core doctrine files to enforce `when_updated` and footer verification model.
- Added and integrated footer stale-check tooling with cutoff `2026-03-01 00:00:00 UTC` in channel validation flow.
- Bulk-normalized table-doc metadata under `lupo-docs/database/lupopedia/tables/**`:
  - removed `version_when_written`
  - added/updated `when_updated`
  - enforced `lupopedia.footer.last_verified`, `last_verified_by`, `last_verified_by_actor_id`
- Rebuilt table-doc validation report from latest TOONs:
  - report: `lupo-docs/database/lupopedia/tables/VALIDATION_REPORT_JUNIE.md`
- Integrated verification guide direction into agent prompts:
  - `lupo-agents/1/system_prompt.txt` (WOLFIE)
  - `lupo-agents/2/system_prompt.txt` (LILITH)
  - `lupo-agents/3/system_prompt.txt` (ROSE)

## Current gap inventory

- Active table docs still have coverage drift vs TOONs:
  - missing active docs: 37
  - extra docs without TOON: 56
- Many table docs still lack frontmatter and require backfill before full validator compliance.
- Root-level file organization still needs a dedicated archival sweep and index update.

## Immediate next actions

1. Create missing active table docs for TOON-backed tables first (DB and channels priority).
2. Backfill frontmatter + footer for table docs currently failing metadata checks.
3. Execute organization sweep for stale root artifacts into archived structure with trace report.