---
lupopedia.headers:
  lupopedia.schema: alias
  file_path_from_root: lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md
  federation_node_id: 0
  last_modified_utc: "20260328203000"
  when_updated: "20260328203000"
  channel_id: 42
  thread_id: "headers-doctrine-alias"
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: documentation
  artifact_kind: documentation
  purpose: Stable URL alias to root LUPOPEDIA HEADERS doctrine; edges index validation + DB import tooling
  tags:
    - doctrine
    - headers
    - canonical_pointer
    - tooling_index
lupopedia.edges:
  outbound_edges:
    - to: lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Single binding source of truth — field matrix, DB mapping, validation rules
    - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
      type: references
      weight: 1.0
      reason: Format, footer policy, freshness model
    - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md
      type: references
      weight: 1.0
      reason: Validator behavior and full scripts-in-scope list
    - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md
      type: references
      weight: 0.95
      reason: YAML block order and file structure
    - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/TAXONOMY_REFERENCE.md
      type: references
      weight: 0.95
      reason: Schema and cross-field quick reference
    - to: lupo-docs/doctrine/HEADER_DB_REVERSIBILITY_DOCTRINE.md
      type: references
      weight: 1.0
      reason: DB ↔ file round-trip semantics
    - to: lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md
      type: references
      weight: 1.0
      reason: lupo_edges authority for imported outbound_edges
    - to: lupo-scripts/import_content.py
      type: implements
      weight: 1.0
      reason: Import markdown into lupo_contents + sync metadata, edges, revision_history
    - to: lupo-scripts/ensure_imported.py
      type: implements
      weight: 1.0
      reason: Wrapper — runs import when content_id missing
    - to: lupo-scripts/generate_headers_from_db.py
      type: implements
      weight: 1.0
      reason: Regenerate YAML from DB (default live MySQL)
    - to: lupo-scripts/lib/header_db_sync.py
      type: implements
      weight: 1.0
      reason: Shared sync_header_artifact_to_db and build_yaml_data_from_db
    - to: lupo-scripts/lib/header_validation.py
      type: references
      weight: 1.0
      reason: Deterministic header parse/validate; content_id warnings
    - to: lupo-scripts/validate_lupopedia_headers.py
      type: references
      weight: 1.0
      reason: CLI validation for markdown headers; content_id warn
    - to: lupo-scripts/validate_lupopedia_headers_universal.py
      type: references
      weight: 1.0
      reason: Cross-field / universal header checks
    - to: lupo-scripts/validate_lupopedia_headers.php
      type: references
      weight: 0.9
      reason: PHP-side header validation
    - to: lupo-scripts/validate_footer_verification.py
      type: references
      weight: 0.85
      reason: Footer last_verified / verifier structure
    - to: lupo-scripts/validate_channel_artifacts.py
      type: references
      weight: 0.85
      reason: Channel artifact header scans
    - to: lupo-scripts/regenerate_headers_for_stale_files.py
      type: references
      weight: 0.8
      reason: Batch stale refresh — prefer DB-first regenerate when imported
lupopedia.footer:
  last_verified: "20260328203000"
  verified_by:
    identity_type: actor
    actor_id: 102
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: wolfie:root
  next_action:
    - Keep outbound_edges aligned when adding header import or validation scripts
    - Edit binding doctrine only in lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md
---
# LUPOPEDIA HEADERS doctrine — alias + tooling edges

## Which file is the truth?

| Path | Role |
|------|------|
| **`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`** | **Binding doctrine only.** Edit this file for any rule or matrix change. |
| **`lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md` (this file)** | **Not a second truth.** Stable URL + **tooling graph** (`lupopedia.edges` above) so discoverability and imports stay wired to scripts. |

The **`outbound_edges`** in the YAML front matter above point at the canonical root doc, format docs, and every **import / regenerate / validate** program on the database-first path (plus related validators).

**Header note:** This file uses **`lupopedia.schema: alias`** — it is a **pointer / tooling hub**, not a second copy of binding doctrine text. For taxonomy tables without opening the root file first, see [`TAXONOMY_REFERENCE.md`](TAXONOMY_REFERENCE.md).

## Single source of truth (read this in the root file)

**[`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md)** — full required-field matrix, taxonomies, federation rules, **database-first mapping** (`lupo_contents`, `lupo_metadata`, `lupo_edges`, `revision_history`), and narrative tooling section.

## Tooling quick reference (same targets as YAML edges)

| Script | Purpose |
|--------|---------|
| [`import_content.py`](../../../lupo-scripts/import_content.py) | File → DB (`lupo_contents` + sync) |
| [`ensure_imported.py`](../../../lupo-scripts/ensure_imported.py) | Import if `content_id` missing |
| [`generate_headers_from_db.py`](../../../lupo-scripts/generate_headers_from_db.py) | DB → file YAML |
| [`lib/header_db_sync.py`](../../../lupo-scripts/lib/header_db_sync.py) | Shared DB round-trip |
| [`lib/header_validation.py`](../../../lupo-scripts/lib/header_validation.py) | Parse/validate header dict |
| [`validate_lupopedia_headers.py`](../../../lupo-scripts/validate_lupopedia_headers.py) | CLI check one markdown file |
| [`validate_lupopedia_headers_universal.py`](../../../lupo-scripts/validate_lupopedia_headers_universal.py) | Broader cross-field validation |
| [`validate_lupopedia_headers.php`](../../../lupo-scripts/validate_lupopedia_headers.php) | PHP validator |
| [`validate_footer_verification.py`](../../../lupo-scripts/validate_footer_verification.py) | Footer verification fields |
| [`validate_channel_artifacts.py`](../../../lupo-scripts/validate_channel_artifacts.py) | Channel artifacts |
| [`regenerate_headers_for_stale_files.py`](../../../lupo-scripts/regenerate_headers_for_stale_files.py) | Batch stale refresh |

## Related doctrine files (no duplicate binding text)

| Document | Role |
|----------|------|
| [`README.md`](README.md) | Freshness model, web path, tooling index |
| [`VALIDATORS_AND_TOOLING.md`](VALIDATORS_AND_TOOLING.md) | Validator policy and scripts in scope |
| [`LUPOPEDIA_HEADERS_FORMAT.md`](LUPOPEDIA_HEADERS_FORMAT.md) | Block order |
| [`TAXONOMY_REFERENCE.md`](TAXONOMY_REFERENCE.md) | Schema + cross-field quick reference |
| [`HEADER_DB_REVERSIBILITY_DOCTRINE.md`](../HEADER_DB_REVERSIBILITY_DOCTRINE.md) | Round-trip rules |

## Commands (reminder)

```text
python lupo-scripts/import_content.py <file.md>
python lupo-scripts/ensure_imported.py <file.md>
python lupo-scripts/generate_headers_from_db.py --file-path <repo-relative.md>
python lupo-scripts/validate_lupopedia_headers.py <file.md>
```
