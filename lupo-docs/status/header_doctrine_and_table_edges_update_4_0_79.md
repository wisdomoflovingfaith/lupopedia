---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  Lupopedia.version_written: "4.0.79"
  lupopedia.schema: "status_report"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/status/header_doctrine_and_table_edges_update_4_0_79.md"
  web_path: "http://www.lupopedia.com/status/header_doctrine_and_table_edges_update_4_0_79"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "status"
  artifact_kind: "report"
  purpose: "Report: narrow handwritten header doctrine for ordinary docs; define active-table-doc exception; begin grounded edge population for active table docs."
  tags: ["headers", "doctrine", "table_docs", "edges", "4.0.79"]
lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Continue grounded edge population across remaining active table docs"
    - "Update header templates/examples to match the general-doc vs table-doc exception model"
    - "Ensure docs no longer imply dynamic relationship blocks are universal handwritten defaults"
---
# file: header_doctrine_and_table_edges_update_4_0_79 — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/status/header_doctrine_and_table_edges_update_4_0_79

## Summary

- Updated LUPOPEDIA HEADERS doctrine/spec to enforce: **Headers declare the artifact; the database declares the world around it.**
- Clarified the **special exception**: active table documentation under `lupo-docs/database/lupopedia/tables/active/*.md` requires **verbose edges** populated from grounded repository evidence (PHP/Python/schema/seed/install SQL usage).
- Began targeted edge enrichment for active table docs (example: `lupo_auth_users`).

## Doctrine/spec files updated

- `README.md` (header section doctrine corrected; table-doc exception documented)
- `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md` (doctrine narrowed; active table-doc exception stated; version normalized to 4.0.79)
- `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md` (added general-doc vs table-doc guidance; clarified edges/engagement are not universal defaults; version normalized to 4.0.79)
- `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md` (status/version normalized to 4.0.79)

## Template/example updates

- Pending: update header templates/examples to reflect the new “general-doc stable header blocks” model and reserve verbose edges for active table docs.

## Active table docs (targeted pass)

Target directory (repo-grounded): `lupo-docs/database/lupopedia/tables/active/*.md`

### Updated

- `lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md`
  - Added `DEFINES_SCHEMA_FOR` edge.
  - Added grounded `USED_IN_PHP` edges (PHP files referencing `lupo_auth_users`).
  - Added grounded `USED_IN_PYTHON` edges (Python files referencing `lupo_auth_users`).

### How edges were discovered

- Repository search for literal table-name usage in `*.php` and `*.py`.
- Only file paths with actual matches were added as `USED_IN_PHP` / `USED_IN_PYTHON`.
- Relationship edges (`DEPENDS_ON_TABLE`, `REFERENCED_BY_TABLE`) were not added unless explicitly grounded (no foreign keys exist by doctrine; relationships require explicit evidence).

## Doctrine corrections applied

- Universal “edges/engagement/lineage in every handwritten header” guidance was removed/narrowed in doctrine/spec.
- Explicit exception created for active table documentation: verbose edges required and grounded.
- Canonical current doctrine version normalized to **4.0.79** where the file is describing current doctrine.

## Files needing manual review / follow-up

- Remaining active table docs need the same grounded edge population pass.
- Any docs/templates that still imply “dynamic header bloat as default” should be corrected (search for guidance that treats edges/engagement/lineage as universal handwritten defaults).

