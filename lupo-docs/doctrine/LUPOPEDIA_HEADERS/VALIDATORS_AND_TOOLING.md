---
lupopedia.headers:
  actor_id: 102
  actor_name: cursor
  artifact_kind: documentation
  artifact_type: doctrine
  channel_id: 42
  delegation_chain: cursor:root
  federation_node_id: 0
  file_path_from_root: lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md
  last_modified_utc: '20260328163401'
  lupopedia.schema: doctrine
  purpose: Validator policy, scripts in scope, required-field alignment with binding
    doctrine
  tags:
  - headers
  - validators
  - tooling
  thread_id: headers-validators-tooling
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md
  when_updated: '20260328240000'
  title: Lupo docs doctrine lupopediaheaders validatorsandtooling
  content_id: 1082067641908298713
lupopedia.footer:
  last_verified: 20260328240000
  next_action:
  - Keep validator behavior aligned with doctrine freshness rules
  orchestrator: cursor:root
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent (Lead Orchestration)
    department_id_delta: 0
    identity_type: actor
  verified_via:
    faucet_slug: cursor
    type: faucet
---
# file: Lupo docs doctrine lupopediaheaders validatorsandtooling — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md

# Validators and Tooling

## Required validator behavior

- Validate **all binding required** `lupopedia.headers` keys (see root doctrine): `lupopedia.schema`, `file_path_from_root`, `web_path`, `federation_node_id`, `when_updated`, `last_modified_utc`, `delegation_chain`, `artifact_type`, `artifact_kind`, `purpose`, `tags` (non-empty list).
- Validate **author attribution**: Accept new structured `author.type`/`author.id` format OR legacy `actor_id`/`actor_name` (with deprecation warning until 2026-07-02).
- Validate **conditional fields** based on `artifact_type`/`artifact_kind`:
  - PRD files require: `prd_id`, `prd_slug`, `title`, `status`
  - Implementation files require: `parent_prd`, `status` (and `version` for documentation kind)
  - Discussion files require: `channel_id`, `thread_id`
  - Doctrine files have no additional required fields
- Validate `lupopedia.headers.when_updated` and `last_modified_utc` as required UTC `YYYYMMDDHHIISS` (14-digit strings).
- Validate `thread_id` pattern `^[a-z0-9][a-z0-9-]*$` when present.
- Validate cross-field dependencies between `lupopedia.schema`, `artifact_type`, and `artifact_kind` (see TAXONOMY_REFERENCE.md).
- Treat `version_when_written` as deprecated compatibility data in 4.0.88 and reject it in 4.0.89.
- Validate `lupopedia.headers.web_path` uses the install-subdirectory prefix: `http://www.lupopedia.com/lupopedia/`.
- Validate `lupopedia.footer.last_verified`, `verified_by.*`, and `verified_via.*` when footer exists.
- Treat legacy flat verifier name/id fields as deprecated compatibility data, not canonical validator targets.
- Flag stale footer verification when `last_verified < 20260301000000` UTC.
- Require semantic truth review before accepting refreshed footer verification on stale artifacts.

## Backward Compatibility (4.0.88)

- Validators will **warn** but **not reject** `version_when_written` in headers.
- Exporters and rewrite tools must use `when_updated` and `last_modified_utc` only.
- Enforcement (reject) begins in **4.0.89**.
- New artifacts must use `when_updated` exclusively.

## Semantic Truth Review Authority

- **Primary authority:** THOTH (actor_id 26) for stale artifacts requiring semantic verification.
- **Self-verification allowed:** Only if the updating actor created or last updated the artifact and can certify no semantic changes are needed.
- **Evidence:** Footer refresh must include justification (`revalidated: [reason]`) in commit or artifact update.

See `README.md` section "Semantic Truth Check Authority (THOTH)" for full skillset and workflow.

## Semantic Truth Check Sources

When performing semantic truth review (required for stale artifacts), consult:

| Source | Location |
|--------|----------|
| Table documentation | `lupo-docs/database/lupopedia/tables/active/*.md` |
| TOON exports | `lupo-database/lupopedia/toon/*.toon` and `*.toon.json` |
| JSON table exports | `lupo-database/lupopedia/json/*.json` |
| Root rules | `lupo-rules/root/*.md` |
| Edge model doctrine | `lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md` |
| Version documentation | `lupo-docs/versions/<version>/` |

These sources ensure the artifact matches current repository reality before updating `last_verified`.

## Scripts in scope

- `lupo-scripts/lib/header_validation.py`
- `lupo-scripts/validate_lupopedia_headers.py` — Required header keys, `thread_id` pattern, UTC pair, tags list; optional `--check-db` warns when the file has `outbound_edges` or `lupopedia.history` but MySQL has no matching `lupo_edges` (`edge_category=lupopedia_header`) or empty `lupo_contents.revision_history` for that `content_id` (run `import_content.py` to sync). SQL uses real column names (`semantic_weight`, `flare_reason`, `weight_score` on `lupo_edges` — not `weight` / `reason`).
- `lupo-scripts/validate_lupopedia_headers_universal.py` — Same required keys plus **cross-field** `lupopedia.schema` ↔ `artifact_type` / `artifact_kind` checks, repo-root `outbound_edges` resolution, optional `--check-db` (same drift idea).
- `lupo-scripts/validate_lupopedia_headers.php` — **PHP** validator aligned with `validate_lupopedia_headers.py` (required keys, `thread_id`, UTC pair, tags, optional `--check-db` when `lupopedia-config.php` is loaded). Requires **php-yaml** (`yaml_parse`).
- `lupo-includes/classes/HeaderDbSync.php` — **PHP** shared logic mirroring `lib/header_db_sync.py` (`syncHeaderArtifactToDb`, `buildYamlDataFromDb`, `calculateContentId`, YAML parse/dump helpers). Requires **bcmath** or **gmp** for `content_id` parity with Python.
- `lupo-scripts/import_content.php` — **PHP** import (upsert `lupo_contents` + sync metadata/edges/`revision_history`). **Default does not modify the file** (shared-hosting safe); **`--write-back`** injects `lupopedia.headers.content_id` (Python `import_content.py` always write-backs). Same deterministic `content_id` as `import_content.py`.
- `lupo-scripts/generate_headers_from_db.php` — **PHP** regeneration from DB (`--file-path` or `--content-id`, optional `--dry-run`).
- `lupo-scripts/validate_footer_verification.py`
- `lupo-scripts/validate_channel_artifacts.py`
- `lupo-scripts/validate_script_footer_verification.py`
- `lupo-scripts/import_content.py`
- `lupo-scripts/ensure_imported.py`
- `lupo-scripts/generate_headers_from_db.py` (default: live MySQL; DB-first regeneration)
- `lupo-scripts/lib/header_db_sync.py`
- `lupo-scripts/import_filesystem_channels_to_db.py`

**Binding taxonomy + table mapping (single source of truth):** `lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md` only. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md` is a stable pointer to that file — do not maintain a second copy.

## Operational use

- Run channel scans with footer validation enabled.
- Use autofix only for metadata refresh; still require semantic review before claiming verification.
- Run script-comment validation for `lupo-scripts/*.py` and `lupo-scripts/*.php` to prevent stale tooling metadata.
- Do not close stale-artifact findings until semantic content review is complete and footer fields are updated after that review.