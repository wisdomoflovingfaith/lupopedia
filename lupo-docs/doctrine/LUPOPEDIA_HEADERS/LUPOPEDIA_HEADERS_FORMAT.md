---
lupopedia.headers:
  when_updated: "20260324190000"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md"
  last_modified_utc: "20260324190000"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  namespace: "governance"
lupopedia.footer:
  last_verified: "20260324190000"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "cursor:root"
  next_action:
    - "Keep all examples on when_updated plus footer verification"
---
# file: LUPOPEDIA HEADERS FORMAT - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md

# LUPOPEDIA HEADERS Format

## Front matter structure

1. First line must be `---`.
2. YAML front matter appears once.
3. Closing delimiter `---`.
4. First body line must be `# file: ...` identity line.

## Canonical freshness fields

In `lupopedia.headers`:

- `when_updated` (required, UTC `YYYYMMDDHHIISS`)
- `file_path_from_root` (required)
- `last_modified_utc` (required)

Deprecated in headers and must be removed:

- `version_when_written`

## Footer validation fields

If `lupopedia.footer` exists, require:

- `last_verified` (UTC `YYYYMMDD` or `YYYYMMDDHHIISS`)
- `last_verified_by`
- `last_verified_by_actor_id`

Revalidation rule:

- Missing `last_verified` or `last_verified < 20260301000000` means stale and must be revalidated.

## Script metadata comments (`.py` / `.php`)

Non-Markdown script artifacts in `lupo-scripts/` can carry LUPOPEDIA metadata in top-of-file comments.

- Python files: place YAML-like comment lines near the top using `#`.
- PHP files: place YAML-like lines inside a leading docblock comment.
- Use the same keys:
  - `lupopedia.headers.when_updated`
  - `lupopedia.footer.last_verified`
  - `lupopedia.footer.last_verified_by`
  - `lupopedia.footer.last_verified_by_actor_id`

Script comment metadata must follow the same stale rule: `last_verified >= 20260301000000`.

## Block guidance

- `lupopedia.headers` is required.
- `lupopedia.footer` is strongly recommended for doctrine, table docs, and channel artifacts.
- `lupopedia.edges` is required for active table documentation under `lupo-docs/database/lupopedia/tables/active/`.
