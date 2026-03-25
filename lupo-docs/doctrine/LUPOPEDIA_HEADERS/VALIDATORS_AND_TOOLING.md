---
lupopedia.headers:
  when_updated: "20260325204324"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md"
  last_modified_utc: "20260325204324"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  namespace: "governance"
lupopedia.footer:
  last_verified: "20260325204324"
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
    - "Keep validator behavior aligned with doctrine freshness rules"
---
# file: VALIDATORS AND TOOLING - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md](http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md)

# Validators and Tooling

## Required validator behavior

- Validate `lupopedia.headers.when_updated` as required UTC `YYYYMMDDHHIISS`.
- Reject `version_when_written` in `lupopedia.headers`.
- Validate `lupopedia.headers.web_path` uses the install-subdirectory prefix: `http://www.lupopedia.com/lupopedia/`.
- Validate `lupopedia.footer.last_verified`, `verified_by.*`, and `verified_via.*` when footer exists.
- Treat legacy flat verifier name/id fields as deprecated compatibility data, not canonical validator targets.
- Flag stale footer verification when `last_verified < 20260301000000` UTC.
- Require semantic truth review before accepting refreshed footer verification on stale artifacts.

## Backward Compatibility (4.0.88)

- Validators will **warn** but **not reject** `version_when_written` in headers.
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
- `lupo-scripts/validate_lupopedia_headers.php`
- `lupo-scripts/validate_footer_verification.py`
- `lupo-scripts/validate_channel_artifacts.py`
- `lupo-scripts/validate_script_footer_verification.py`
- `lupo-scripts/import_content.py`
- `lupo-scripts/import_filesystem_channels_to_db.py`

## Operational use

- Run channel scans with footer validation enabled.
- Use autofix only for metadata refresh; still require semantic review before claiming verification.
- Run script-comment validation for `lupo-scripts/*.py` and `lupo-scripts/*.php` to prevent stale tooling metadata.
- Do not close stale-artifact findings until semantic content review is complete and footer fields are updated after that review.
