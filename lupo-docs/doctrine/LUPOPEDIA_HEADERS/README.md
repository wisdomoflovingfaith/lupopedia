---
lupopedia.headers:
  when_updated: "20260325204149"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
  last_modified_utc: "20260325204149"
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
  last_verified: "20260325204149"
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
    - "Continue migration from version_when_written to when_updated"
    - "Enforce stale footer revalidation policy for artifacts below 20260301000000"
---
# file: LUPOPEDIA HEADERS README - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md)

# LUPOPEDIA HEADERS

LUPOPEDIA metadata now uses a two-part freshness model:

- `lupopedia.headers.when_updated` is the canonical artifact update timestamp in UTC `YYYYMMDDHHIISS`.
- `lupopedia.footer.last_verified` + verifier identity fields are the trust and validation timestamp.

## Required direction

- Stop writing `version_when_written` in headers.
- Use `when_updated` for all new and updated artifacts.
- Require footer verifier fields when `lupopedia.footer` exists:
  - `last_verified`
  - `verified_by.identity_type` (`actor` | `agent`)
  - `verified_by.actor_id` (canonical authority actor id)
  - `verified_via.type` (`faucet` | `direct`)
  - `verified_via.faucet_slug` (`none` if not faucet-mediated)
- Recommended footer clarity fields:
  - `verified_by.agent_name_identity` (human-readable label)
  - `verified_by.department_id_delta` (reserved for department override, default `0`)
- Legacy name/id verifier keys are not canonical and should not be used in new artifacts.
- Revalidate any artifact with missing `last_verified` or `last_verified < 20260301000000` UTC.

## Web Path Rule

- `lupopedia.headers.web_path` must include the subdirectory install prefix `/lupopedia/`.
- Canonical pattern: `http://www.lupopedia.com/lupopedia/<file_path_from_root>`.
- Root-relative paths like `http://www.lupopedia.com/lupo-docs/...` are invalid for this project.
- In the body identity line, use a clickable markdown link for web_path.

Example:

```yaml
lupopedia.headers:
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
```

## Forward policy (effective 4.0.88)

- If `lupopedia.footer.last_verified` is earlier than `20260301000000` UTC, the artifact is not considered current.
- Before updating footer metadata, perform a semantic truth check to confirm statements still match repository reality.
- Footer refresh without semantic review is invalid for stale artifacts and must not be treated as verified.

## Semantic Truth Check Authority (THOTH)

**Primary authority for stale artifact verification:** **THOTH** (actor_id 26)

THOTH is the canonical knowledge and records persona. Verification of stale artifacts is a knowledge integrity function, not an execution or audit function.

### THOTH's Verification Skillset

THOTH must reference the following sources when performing semantic truth checks:

| Source Type | Location | Purpose |
|-------------|----------|---------|
| **Table documentation** | `lupo-docs/database/lupopedia/tables/active/*.md` | Current table structure and usage |
| **TOON exports** | `lupo-database/lupopedia/toon/*.toon` (and `.toon.json`) | Canonical column/type reference from live DB |
| **JSON table exports** | `lupo-database/lupopedia/json/*.json` | Individual table structure snapshots |
| **Root rules** | `lupo-rules/root/*.md` | Constitutional constraints and invariants |
| **Edge model doctrine** | `lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md` | Canonical edge model and type registry |
| **Version documentation** | `lupo-docs/versions/<version>/` | Version-specific context and decisions |

### Verification Workflow

1. **Identify stale artifact** — `last_verified < 20260301000000`
2. **THOTH performs semantic truth check:**
  - Compare artifact content against current repository sources
  - Verify table references against TOON/JSON exports
  - Verify edge types against `lupo_edge_types` registry
  - Verify rule references against `lupo-rules/root/`
3. **Outcome:**
  - **If accurate:** Update footer with `last_verified` = current UTC, `verified_by.actor_id = 26`, `verified_by.agent_name_identity = "THOTH"`
  - **If outdated:** Mark artifact as `status: needs_revision` (do not update footer) and create documentation update task in TASK_REGISTRY
  - **If contradictory:** Escalate to LILITH for contradiction resolution

### Self-Verification Exception

If the artifact was created or last updated by the same actor performing the footer refresh:
- THOTH review is not required
- Actor must still perform semantic truth check
- Footer must include `verified_by.actor_id` of the updating actor, not THOTH

### Evidence Requirement

Any footer refresh must include a brief justification in the commit message or artifact update:

`revalidated: [reason]`

Examples:

- `revalidated: table docs match TOON; edge types confirmed`
- `revalidated: content unchanged since 4.0.87; only footer updated`

If semantic truth check cannot be performed, mark artifact as `status: needs_revalidation` without updating `last_verified`.

## Footer identity interpretation

Interpret verifier identity in this order:

1. Authority: `verified_by.actor_id` (canonical authority layer).
2. Identity type: `verified_by.identity_type`.
3. Human label: `verified_by.agent_name_identity` for readable display.
4. Department delta: `verified_by.department_id_delta` (`0` means no department overlay used).
5. Execution surface: `verified_via.type` plus `verified_via.faucet_slug` (`none` for direct verification).

## Special rule for table docs

Files under `lupo-docs/database/lupopedia/tables/active/*.md` are a mapping surface and must include a grounded `lupopedia.edges` block.

## Backward Compatibility (4.0.88)

- `version_when_written` is deprecated for new artifacts but existing headers are not automatically invalid.
- Migrate existing headers to `when_updated` during normal editing workflows.
- Validators will warn but not reject `version_when_written` in 4.0.88; enforcement begins in 4.0.89.

## Canonical references

- `LUPOPEDIA_HEADERS_FORMAT.md`
- `VALIDATORS_AND_TOOLING.md`
- `VERIFICATION_GUIDE.md`
- `LUPOPEDIA_HEADERS_MIGRATION.md`
- `EDGE_MODEL_DOCTRINE.md`
- `lupo-rules/root/`