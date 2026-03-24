---
lupopedia.headers:
  when_updated: '20260324180128'
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/versions/4.0.87/when_updated_footer_migration_plan.md
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/when_updated_footer_migration_plan.md
  last_modified_utc: '20260324180128'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: plan
  artifact_kind: migration_plan
  purpose: Continue doctrine migration from version_when_written to when_updated with
    footer revalidation priority
  tags:
  - migration
  - headers
  - footer
  - doctrine
  - validation
lupopedia.footer:
  last_verified: '20260324180128'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
  next_action:
  - Run this plan each session until all core doctrine and active table docs are compliant
---
# file: when_updated footer migration plan - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/when_updated_footer_migration_plan.md

# Ongoing Migration Plan: version_when_written -> when_updated

## Policy target

- `lupopedia.headers.when_updated` is canonical.
- `version_when_written` must be removed from active artifacts.
- Footer trust recency is authoritative via `last_verified` + verifier identity.

## Priority order

1. Database truth surfaces
   - `lupo-docs/database/lupopedia/tables/active/*.md`
   - related schema/docs in `lupo-database/lupopedia/*`
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