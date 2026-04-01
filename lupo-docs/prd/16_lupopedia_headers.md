---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260331190000"
  file_path_from_root: "lupo-docs/prd/16_lupopedia_headers.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/16_lupopedia_headers.md"
  last_modified_utc: "20260331190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-lupopedia-headers"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Canonical specification for Lupopedia file headers, verification, and metadata propagation"
  tags:
  - "prd"
  - "lupopedia_headers"
  - "metadata"
  - "verification"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      type: references
      weight: 1.0
      reason: "Canonical doctrine for Lupopedia headers"
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Header format and validation rules"
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md"
      type: references
      weight: 0.9
      reason: "Header validation and tooling"
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md"
      type: references
      weight: 0.8
      reason: "Header versioning model"
    - to: "lupo-scripts/validate_lupopedia_headers_universal.py"
      type: implements
      weight: 1.0
      reason: "Python validator for Lupopedia headers, including context_id logic"
    - to: "lupo-scripts/regenerate_headers_for_stale_files.py"
      type: implements
      weight: 1.0
      reason: "Regeneration script for headers, now with context_id support"
  
lupopedia.footer:
  last_verified: "20260331190000"
  verified_by:
    identity_type: "agent"
    actor_id: 2
    agent_name_identity: "LILITH"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "none"
  orchestrator: "lilith:audit"
  next_action:
    - "Verify all header fields match doctrine"
    - "Ensure verification authority (THOTH) is documented"
    - "Update stale artifacts with correct header format"
---

# PRD: Lupopedia File Headers and Verification

## Overview

This PRD defines the canonical requirements, structure, and verification process for Lupopedia file headers. All files in the Lupopedia system must include a YAML-formatted `lupopedia.headers` block, which encodes file identity, version, schema, and verification metadata. Verification is performed by agents, not actors, and the `verified_by` field must reference the verifying agent.

## Constitutional Compliance

All header metadata and verification processes must comply with Lupopedia constitutional rules:

- **Verification authority**: Both actors and agents may perform verification
- **Primary authority**: THOTH (actor_id 26) is canonical for stale artifacts (`last_verified < 20260301000000`)
- **Identity tracking**: `verified_by.identity_type` distinguishes actor vs agent
- Header blocks must be present in all canonical files
- Header fields must match format requirements in LUPOPEDIA_HEADERS_DOCTRINE
- All verification actions are logged and auditable via `lupo_actor_actions`
- Timestamps use BIGINT UTC format YYYYMMDDHHIISS (headers) or YYYYMMDD (footer)
## Verification Authority

### Primary Authority: THOTH (actor_id 26)

- **THOTH** is the canonical authority for semantic truth verification of stale artifacts (`last_verified < 20260301000000`).
- Verification includes:
  - Comparing artifact content against current repository sources (TOONs, JSON exports, root rules)
  - Validating table references, edge types, and rule references
  - Confirming statements match repository reality

### Self-Verification Exception

- Self-verification allowed if:
  - The verifying actor (or agent) created or last updated the artifact
  - No semantic changes have occurred since last update
  - The artifact is not stale (`last_verified >= 20260301000000`)

### Verification Evidence

- All footer refreshes must include justification:
  - Commit message: `revalidated: [reason]`
  - Example: `revalidated: table docs match TOON; edge types confirmed`

### Actors and Agents

- Verification may be performed by **either actors or agents**
- Agents are a subset of actors with `actor_type='agent'`
- The `verified_by.identity_type` field distinguishes between actor and agent
- Both share the same verification authority rules

## Header Structure

### Required Fields in `lupopedia.headers`

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `header_format_version` | integer | Yes | Current version of header schema (2) |
| `lupopedia.schema` | string | Yes | PRD, documentation, code, etc. |
| `when_updated` | string (quoted) | Yes | UTC YYYYMMDDHHIISS - logical content update time |
| `file_path_from_root` | string | Yes | Canonical path from repo root |
| `web_path` | string | Yes | Canonical web path with /lupopedia/ prefix |
| `last_modified_utc` | string (quoted) | Yes | UTC YYYYMMDDHHIISS - file write time |
| `federation_node_id` | integer | Yes | 0=core, 1=current install, 2+=external |
| `channel_id` | integer | Yes | Channel ID (e.g., 42) |
| `thread_id` | string | Yes | Lowercase, hyphens, e.g., "prd-lupopedia-headers" |
| `actor_id` | integer | Yes | Actor ID from registry (may be agent) |
| `actor_name` | string | Yes | Human-readable actor name |
| `delegation_chain` | string | Yes | e.g., "lilith:audit" |
| `artifact_type` | string | Yes | Type per taxonomy |
| `artifact_kind` | string | Yes | Kind per taxonomy |
| `purpose` | string | Yes | One-line purpose |
| `tags` | list | Yes | Non-empty list of strings |

### Deprecated Fields

The following fields are deprecated and must not be used in new artifacts:
- `version_when_written` — use `when_updated`
- `system_version` — no replacement
- `lupopedia.version` — no replacement


## Verification Process

- Verification may be performed by **actors or agents**
- `lupopedia.footer.verified_by` must reference the verifying entity
- The `verified_by.identity_type` field indicates whether verifier is actor or agent
- All verification actions are logged in the system audit trail
- Verification includes header format, field presence, and cross-references
- Stale artifacts (`last_verified < 20260301000000`) must be verified by THOTH

## Cross-References

- See [LUPOPEDIA_HEADERS_DOCTRINE.md](../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md) for canonical header requirements
- See [VALIDATORS_AND_TOOLING.md](../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md) for validation tools
- See [VERSIONING_MODEL.md](../doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md) for versioning rules

---

**Status**: DRAFT
**Constitutional Adherence**: FULL
**Next Review**: After next doctrine update
