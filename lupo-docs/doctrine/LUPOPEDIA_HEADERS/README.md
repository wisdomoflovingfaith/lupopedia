---
lupopedia.headers:
  when_updated: "20260324190000"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
  last_modified_utc: "20260324190000"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "index"
  purpose: "Canonical doctrine index for LUPOPEDIA HEADERS and footer verification model"
  tags: ["headers", "doctrine", "validation", "footer", "utc"]
  namespace: "governance"
lupopedia.footer:
  last_verified: "20260324190000"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "cursor:root"
  next_action:
    - "Continue migration from version_when_written to when_updated"
    - "Prioritize stale footer revalidation for database and channel artifacts"
---
# file: LUPOPEDIA HEADERS README - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md

# LUPOPEDIA HEADERS

LUPOPEDIA metadata now uses a two-part freshness model:

- `lupopedia.headers.when_updated` is the canonical artifact update timestamp in UTC `YYYYMMDDHHIISS`.
- `lupopedia.footer.last_verified` + verifier identity fields are the trust and validation timestamp.

## Required direction

- Stop writing `version_when_written` in headers.
- Use `when_updated` for all new and updated artifacts.
- Require footer verifier fields when `lupopedia.footer` exists:
  - `last_verified`
  - `last_verified_by`
  - `last_verified_by_actor_id`
- Revalidate any artifact with missing `last_verified` or `last_verified < 20260301000000` UTC.

## Special rule for table docs

Files under `lupo-docs/database/lupopedia/tables/active/*.md` are a mapping surface and must include a grounded `lupopedia.edges` block.

## Canonical references

- `LUPOPEDIA_HEADERS_FORMAT.md`
- `VALIDATORS_AND_TOOLING.md`
- `VERIFICATION_GUIDE.md`
- `LUPOPEDIA_HEADERS_MIGRATION.md`