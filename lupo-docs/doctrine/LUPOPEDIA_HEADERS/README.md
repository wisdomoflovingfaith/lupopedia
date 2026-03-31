---
lupopedia.headers:
  actor_id: 102
  actor_name: cursor
  artifact_kind: documentation
  artifact_type: doctrine
  channel_id: 42
  delegation_chain: cursor:root
  federation_node_id: 0
  file_path_from_root: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
  last_modified_utc: '20260328163401'
  lupopedia.schema: doctrine
  purpose: Canonical doctrine index for LUPOPEDIA HEADERS and footer verification
    model
  tags:
  - headers
  - doctrine
  - validation
  - footer
  - utc
  thread_id: headers-readme-index
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
  when_updated: '20260328240000'
  title: Lupo docs doctrine lupopediaheaders readme
  content_id: 6398844981624656451
lupopedia.footer:
  last_verified: 20260328
  next_action:
  - Continue migration from version_when_written to when_updated
  - Enforce stale footer revalidation policy for artifacts below 20260301000000
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
# file: Lupo docs doctrine lupopediaheaders readme — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md

# LUPOPEDIA HEADERS

## Single source of truth (binding doctrine)

**One file only:** **[`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md)** — required header keys, `lupopedia.schema` / `artifact_type` / `artifact_kind` taxonomies, federation rules, validation expectations, **database-first mapping** (`lupo_contents`, `lupo_metadata`, `lupo_edges`, `revision_history`), and **`lupo-database/lupopedia/json/*.json`** / install SQL authority.

**Same folder, different role:** [`LUPOPEDIA_HEADERS_DOCTRINE.md`](LUPOPEDIA_HEADERS_DOCTRINE.md) in this directory is a **stable URL alias** (pointer + short reminder). It does **not** duplicate the binding text; edit the **root** file only.

**This directory** otherwise holds **format**, **block order**, **footer semantics**, **validators**, and **tooling** (`LUPOPEDIA_HEADERS_FORMAT.md`, `TAXONOMY_REFERENCE.md`, `VALIDATORS_AND_TOOLING.md`, `OPTIONAL_BLOCKS.md`, etc.) — companion material, not a second copy of the field matrix.

**Required keys in the file (summary):** `lupopedia.schema`, `file_path_from_root`, `web_path`, `federation_node_id`, `when_updated`, `last_modified_utc`, `channel_id`, `thread_id`, `actor_id`, `actor_name`, `delegation_chain`, `artifact_type`, `artifact_kind`, `purpose`, `tags` — full matrix and meanings only in the **root** doctrine; quick cross-field table in **`TAXONOMY_REFERENCE.md`**.

LUPOPEDIA metadata now uses a two-part freshness model:

- `lupopedia.headers.when_updated` is the canonical artifact update timestamp in UTC `YYYYMMDDHHIISS`.
- `lupopedia.footer.last_verified` + verifier identity fields are the trust and validation timestamp.


## Footer Validation Requirements (Canonical)

Every Lupopedia artifact that includes a `lupopedia.footer` block must comply with the following validation rules:

- **Required fields:**
  - `last_verified` (UTC timestamp, format: YYYYMMDDHHIISS)
  - `verified_by` (object with at minimum: `identity_type`, `actor_id`)
  - `verified_via` (object with at minimum: `type`, `faucet_slug`)
- **Recommended fields:**
  - `verified_by.agent_name_identity` (human-readable label)
  - `verified_by.department_id_delta` (default 0)
- **Staleness cutoff:**
  - If `last_verified` is earlier than `20260301000000` UTC, the artifact is considered stale and must be semantically revalidated before updating the footer.
  - Footer refresh without semantic review is invalid for stale artifacts.
- **Semantic truth check:**
  - For stale artifacts, a semantic review must confirm that all statements match repository reality. Authority for this review is THOTH (actor_id 26).
- **Canonical validator behavior:**
  - All required fields must be present and correctly formatted.
  - Legacy flat verifier fields are deprecated and not valid for new artifacts.
  - See also: [VALIDATORS_AND_TOOLING.md](VALIDATORS_AND_TOOLING.md) for implementation details.

---
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
- `TAXONOMY_REFERENCE.md`
- `VALIDATORS_AND_TOOLING.md`
- `VERIFICATION_GUIDE.md`
- `LUPOPEDIA_HEADERS_MIGRATION.md`
- `EDGE_MODEL_DOCTRINE.md`
- `lupo-docs/doctrine/RELEASE_ARTIFACT_APPROVAL_GOVERNANCE_DOCTRINE.md`
- `lupo-rules/root/`

## Footer Timestamp Format (Canonical Rule)

**Effective immediately (4.0.93):**

- `lupopedia.footer.last_verified` MUST use **YYYYMMDD** (UTC, 8 digits only)
- No hours, minutes, or seconds (no HHMMSS)
- No timezone suffix
- Always UTC
- Always 8 digits (validator: ^\d{8}$)
- Never use the 14-digit YYYYMMDDHHMMSS format in the footer
- Header timestamps (`when_updated`, `last_modified_utc`) remain full 14-digit UTC timestamps
- Only the footer uses the simplified 8-digit format

**Validator Rule:**
- If `last_verified` is not exactly 8 digits, the validator must warn
- If it is 14 digits, the validator must warn and suggest truncation to day format

**Reasoning:**
- Footer verification is a daily trust marker, not a precise event timestamp
- Human readability improves
- Staleness detection still works
- IDE behavior becomes deterministic
- Prevents mixing formats across the repo

**Why This Works With Existing Doctrine:**
- `when_updated` → canonical artifact update timestamp (14 digits)
- `last_verified` → trust/validation timestamp (8 digits)
- These are different concepts; this separation is clean, logical, and easy for both humans and tools.

**How This Prevents Future Inconsistency:**
- Footer = 8 digits
- Header = 14 digits
- The IDE and validators will never confuse them again.

**Implementation Note:**
- Any tool (including tick.py or IDE footer writers) must use the current UTC day (YYYYMMDD) for `lupopedia.footer.last_verified`.

## Footer Timestamp Normalization (DE Instruction)

- When reading `lupopedia.footer.last_verified`, accept either format:
  - `YYYYMMDD` (8 digits)
  - `YYYYMMDDHHMMSS` (14 digits, legacy)
- When writing or updating `last_verified`, always write only the first 8 digits of the current UTC date:
  - `YYYYMMDD`
- When performing staleness checks:
  - If the value is 14 digits, use the full 14-digit timestamp for comparison
  - If the value is 8 digits, treat it as `YYYYMMDD000000` for comparison
- When regenerating or rewriting a footer, normalize any 14-digit value to its first 8 digits (e.g., `20260328240000` → `20260328`)
- Never write a 14-digit timestamp into the footer. Always write 8 digits. Always read both formats.

**Example:**
- Legacy: `last_verified: 20260328240000` → Normalize to `last_verified: 20260328`
- Legacy: `last_verified: 20260201091530` → Normalize to `last_verified: 20260201`

**Rationale:**
- Ensures legacy files still work correctly
- Prevents future format drift
- Makes IDE and validator behavior deterministic

## Staleness Cutoff (Validation Policy)

- A document is considered **stale** if:
  - `lupopedia.footer.last_verified` is missing, **or**
  - `last_verified` (in either 8- or 14-digit format) is before `20260301` (March 1, 2026)
- Stale documents **must** be semantically revalidated before updating the footer.
- This cutoff applies regardless of whether the date is 8 digits (YYYYMMDD) or 14 digits (YYYYMMDDHHMMSS).