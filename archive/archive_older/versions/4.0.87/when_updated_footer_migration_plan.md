---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: "20260324200640"
  file_path_from_root: "docs/versions/4.0.87/when_updated_footer_migration_plan.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.87/when_updated_footer_migration_plan.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: plan
  artifact_kind: migration_plan
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: when_updated footer migration plan - delegation: cursor:root - web_path: http://www.lupopedia.com/docs/versions/4.0.87/when_updated_footer_migration_plan.md

# Ongoing Migration Plan: version_when_written -> when_updated

## Policy target

- `lupopedia.headers.when_updated` is canonical.
- `version_when_written` must be removed from active artifacts.
- Footer trust recency is authoritative via `last_verified` + verifier identity.

## Priority order

1. Database truth surfaces
   - `docs/database/lupopedia/tables/active/*.md`
   - related schema/docs in `database/lupopedia/*`
2. Channel truth surfaces
   - channel 42, 62, 63, 64 thread artifacts with operational guidance
3. Core doctrines
   - channels, actors, agents, edges, LUPOPEDIA_HEADERS doctrine set
4. Secondary and legacy docs
   - historical docs, archive stubs, old status artifacts

## Execution loop

1. Scan for deprecated `version_when_written` and stale/missing footer verification.
2. Normalize headers and footers.
3. Validate against TOONs and doctrine rules.
4. Record completion in channel artifacts and version status docs.
5. Repeat until zero critical stale artifacts remain.

## Success metrics

- Zero `version_when_written` in active doctrine and active table docs.
- Zero critical artifacts with missing footer verification fields.
- Zero critical artifacts with `last_verified < 20260301000000`.
