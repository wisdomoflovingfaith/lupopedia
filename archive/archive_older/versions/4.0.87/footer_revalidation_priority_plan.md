---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: "20260324200640"
  file_path_from_root: "docs/versions/4.0.87/footer_revalidation_priority_plan.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.87/footer_revalidation_priority_plan.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: plan
  artifact_kind: validation_priority_plan
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "Footer Revalidation Priority Plan 4.0.87"
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: Footer Revalidation Priority Plan 4.0.87 - delegation: cursor:root - web_path: http://www.lupopedia.com/docs/versions/4.0.87/footer_revalidation_priority_plan.md

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
   - `docs/database/lupopedia/tables/active/*.md`
   - `database/lupopedia/**/*.sql`
   - `database/lupopedia/toon/*.toon.json` companion docs
   - Channel security/auth doctrines and channel API governance docs
2. Tier 1 (Operational routing and workflow):
   - `channels/42/threads/*/*.md` with actor/channel/workflow directives
   - Root coordination docs: `TODO.md`, `plan.md`, `report.md`, `README.md`
   - Agent registry and actor identity doctrine docs
3. Tier 2 (Secondary but active):
   - Other active version docs under `docs/versions/4.0.87/`
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
python scripts/validate_channel_artifacts.py --repo-root . --channel 42 --no-footer-autofix
python scripts/validate_channel_artifacts.py --repo-root . --channel 42
```

Use `--no-footer-autofix` for audit-only runs and default mode for guided remediation.

