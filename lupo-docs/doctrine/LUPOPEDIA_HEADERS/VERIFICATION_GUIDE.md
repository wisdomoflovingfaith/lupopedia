---
lupopedia.headers:
  when_updated: "20260324190000"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md"
  last_modified_utc: "20260324190000"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "guide"
  namespace: "governance"
lupopedia.footer:
  last_verified: "20260324190000"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "cursor:root"
  next_action:
    - "Integrate this guide into agent system prompts"
    - "Revalidate all core doctrine artifacts after March updates"
---
# file: VERIFICATION GUIDE - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md

# LUPOPEDIA Verification Guide

## Verification checklist

1. Confirm `lupopedia.headers.when_updated` is present, UTC, and updated for real content change.
2. Confirm `file_path_from_root` matches actual file location.
3. Confirm doctrine/content statements match current repository truth (TOONs, SQL, code, channels, registry).
4. Confirm required edges are present for active table docs and grounded in real references.
5. Update footer verification fields:
   - `last_verified`
   - `last_verified_by`
   - `last_verified_by_actor_id`

## Stale rule

Any artifact is stale and must be revalidated when:

- `lupopedia.footer` is missing required verification fields, or
- `last_verified` is earlier than `20260301000000` UTC.

## Trust model

`when_updated` tracks artifact edits.
`last_verified` tracks trust recency after audit.
Both are required for high-confidence doctrine and database documentation.

For script tooling (`.py`, `.php`), the same fields may be carried in top-of-file comments and must be validated with the same cutoff rule.
