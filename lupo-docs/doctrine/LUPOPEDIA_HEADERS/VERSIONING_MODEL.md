---
lupopedia.headers:
  when_updated: "20260327121457"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md"
  last_modified_utc: "20260327121457"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  title: "VERSIONING_MODEL compatibility notice"
  purpose: "Compatibility notice for deprecated LUPOPEDIA header version fields"
  tags: ["headers", "versioning", "compatibility", "deprecation"]
  namespace: "governance"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260327121457"
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
    - "Keep this file as a compatibility notice only"
---
# file: VERSIONING_MODEL (compatibility notice) — delegation: cursor:root — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md](http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md)

# VERSIONING_MODEL

This document is retained only so historical links resolve to the current rule.

## Current rule

The canonical freshness fields in `lupopedia.headers` are:

- `when_updated`
- `file_path_from_root`
- `last_modified_utc`

Trust and revalidation state belongs in `lupopedia.footer.last_verified` and the verifier identity fields.

## Deprecated compatibility field

`version_when_written` is not a canonical write field.

- In 4.0.88 it may be read for compatibility and should trigger a warning.
- In 4.0.89 it should be rejected inside `lupopedia.headers`.

Also deprecated: `system_version`, `lupopedia.version`, `last_verified_system_version`, and standalone `version` keys inside `lupopedia.headers`.

See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) and [README.md](./README.md) for the active doctrine.
