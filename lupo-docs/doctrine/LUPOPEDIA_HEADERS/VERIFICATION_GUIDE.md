---
lupopedia.headers:
  when_updated: "20260325203444"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md"
  last_modified_utc: "20260325203444"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "guide"
  namespace: "governance"
lupopedia.footer:
  last_verified: "20260325203444"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  orchestrator: "cursor:root"
  next_action:
    - "Integrate this guide into agent system prompts"
    - "Revalidate stale artifacts where last_verified is before 20260301000000"
---
# file: VERIFICATION GUIDE - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md](http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md)

# LUPOPEDIA Verification Guide

## Verification checklist

1. Confirm `lupopedia.headers.when_updated` is present, UTC, and updated for real content change.
2. Confirm `file_path_from_root` matches actual file location.
3. Confirm doctrine/content statements match current repository truth (TOONs, SQL, code, channels, registry).
4. Confirm required edges are present for active table docs and grounded in real references.
5. Update footer verification fields:
   - `last_verified`
  - `verified_by.identity_type`
  - `verified_by.actor_id`
  - `verified_via.type`
  - `verified_via.faucet_slug`

## Stale rule

Any artifact is stale and must be revalidated when:

- `lupopedia.footer` is missing required verification fields, or
- `last_verified` is earlier than `20260301000000` UTC.

## Stale artifact procedure (mandatory)

1. If `last_verified < 20260301000000`, treat the artifact as stale and untrusted.
2. Audit the artifact content against current code, schema, doctrine, and registry truth.
3. Update content first if any mismatch is found.
4. Only after semantic alignment, update footer verification fields (`last_verified`, `verified_by.*`, `verified_via.*`).

## Trust model

`when_updated` tracks artifact edits.
`last_verified` tracks trust recency after audit.
Both are required for high-confidence doctrine and database documentation.

For script tooling (`.py`, `.php`), the same fields may be carried in top-of-file comments and must be validated with the same cutoff rule. Reserve `verified_by.department_id_delta` for future department-scoped verification overlays.
