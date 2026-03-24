---
lupopedia.headers:
  when_updated: '20260324180128'
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/versions/4.0.87/footer_revalidation_priority_plan.md
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/footer_revalidation_priority_plan.md
  title: Footer Revalidation Priority Plan 4.0.87
  delegation_chain: cursor:root
  artifact_type: plan
  artifact_kind: validation_priority_plan
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  last_modified_utc: '20260324180128'
lupopedia.footer:
  last_verified: '20260324180128'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
  next_action:
  - Run channel 42 validation with footer checks
  - Execute tier 0 and tier 1 revalidation first
  - Track completion per thread in TODO registry
---
# file: Footer Revalidation Priority Plan 4.0.87 - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/footer_revalidation_priority_plan.md

# Footer Revalidation Priority Plan (Database + Channels First)

## Goal

Prioritize revalidation for artifacts where stale trust metadata can cause incorrect operational decisions in channels, actor routing, and DB-backed workflow.

## Stale definition

An artifact is stale when:

1. `lupopedia.footer` is missing.
2. `lupopedia.footer.last_verified` is missing or invalid UTC format.
3. `lupopedia.footer.last_verified` is before `2026-03-01 00:00:00 UTC`.
4. `last_verified_by` or `last_verified_by_actor_id` is missing.

## Priority tiers

1. Tier 0 (Critical truth surfaces):
   - `lupo-docs/database/lupopedia/tables/active/*.md`
   - `lupo-database/lupopedia/**/*.sql`
   - `lupo-database/lupopedia/toon/*.toon.json` companion docs
   - Channel security/auth doctrines and channel API governance docs
2. Tier 1 (Operational routing and workflow):
   - `lupo-channels/42/threads/*/*.md` with actor/channel/workflow directives
   - Root coordination docs: `TODO.md`, `plan.md`, `report.md`, `README.md`
   - Agent registry and actor identity doctrine docs
3. Tier 2 (Secondary but active):
   - Other active version docs under `lupo-docs/versions/4.0.87/`
   - Non-channel doctrine docs that influence implementation
4. Tier 3 (Archive/legacy):
   - Legacy and historical artifacts not currently driving execution

## Execution sequence

1. Scan and inventory stale artifacts.
2. Revalidate Tier 0 completely before Tier 1.
3. Revalidate Tier 1 channel documents by thread priority:
   - security/auth/channel-membership first
   - actor identity and workflow second
   - general status and commentary third
4. Update footer fields for each validated artifact.
5. Record completion in the task registry and thread status artifacts.

## Required footer update on validation

Set:

1. `last_verified` (UTC date/time)
2. `last_verified_by` (validator actor slug)
3. `last_verified_by_actor_id` (canonical registry id)

## Suggested commands

```bash
python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --no-footer-autofix
python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42
```

Use `--no-footer-autofix` for audit-only runs and default mode for guided remediation.
