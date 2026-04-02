---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260331190000"
  file_path_from_root: "/lupo-docs/prd/16_lupopedia_headers.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/16_lupopedia_headers.md"
  last_modified_utc: "20260331190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-lupopedia-headers"
  prd_id: 16
  prd_slug: lupopedia_headers
  content_id: 16
  author:
    type: "agent"
    id: 2
    name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Canonical specification for Lupopedia file headers, verification, and metadata propagation"
  status: "approved"
  tags:
  - "prd"
  - "lupopedia_headers"
  - "metadata"
  - "verification"
lupopedia.edges:
  outbound_edges:
    - to: "/lupo-docs/prd/root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "/lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: references
      weight: 1.0
      reason: "Five-layer documentation architecture"
    - to: "/lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      type: references
      weight: 1.0
      reason: "Canonical doctrine for Lupopedia headers"
    - to: "/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Header format and validation rules"
    - to: "/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md"
      type: references
      weight: 0.9
      reason: "Header validation and tooling"
    - to: "/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md"
      type: references
      weight: 0.8
      reason: "Header versioning model"
    - to: "/lupo-scripts/validate_lupopedia_headers_universal.py"
      type: implements
      weight: 1.0
      reason: "Python validator for Lupopedia headers, including context_id logic"
    - to: "/lupo-scripts/regenerate_headers_for_stale_files.py"
      type: implements
      weight: 1.0
      reason: "Regeneration script for headers, now with context_id support"
  
lupopedia.footer:
  last_verified: "20260331190000"
  verified_by:
    type: "agent"
    id: 2
    name: "LILITH"
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

**Note:** THOTH actor_id 26 and PRD 26 share the same number but are different namespaces (actor registry vs document IDs). No functional conflict, but be aware when searching/grepping.

### Self-Verification Exception

- Self-verification allowed if:
  - The verifying actor (or agent) created or last updated the artifact
  - No semantic changes have occurred since last update
  - The artifact is not stale (`last_verified >= 20260301000000`)

**Validation Check:** When `verified_via.type: direct` and `author.id == verified_by.id`, the self-verification exception applies. The artifact must not be stale (`last_verified >= 20260301000000`).

### Verification Evidence

- All footer refreshes must include justification:
  - Commit message: `revalidated: [reason]`
  - Example: `revalidated: table docs match TOON; edge types confirmed`

### Actors and Agents

- Verification may be performed by **either actors or agents**
- Agents are a subset of actors with `actor_type='agent'`
- The `verified_by.identity_type` field distinguishes between actor and agent
- Both share the same verification authority rules

## Author vs Verifier Distinction

Lupopedia distinguishes between **who created content** (author) and **who validated it** (verifier).

| Role | Field Location | Purpose | Required |
|------|----------------|---------|----------|
| **Author** | `lupopedia.headers.author` | Attribution of content creation | Yes |
| **Verifier** | `lupoopedia.footer.verified_by` | Attribution of content validation | Yes (if footer present) |

### Author Types

| Type | Meaning | Example |
|------|---------|---------|
| `actor` | Human-directed agent (IDE faucet) | Cursor, Windsurf, Kiro |
| `agent` | Autonomous AI agent | LILITH, WOLFIE, THOTH |
| `system` | Kernel/system agent | SYSTEM, ASCLEPIUS |
| `user` | Authenticated human user | Captain (auth_user) |

### Author Field Structure (Preferred)

```yaml
lupopedia.headers:
  author:
    type: "actor"           # actor | agent | system | user
    id: 102                 # numeric ID from registry
    name: "CURSOR"          # display name (resolved from registry if omitted)
```

### Legacy Format (Deprecated)

The flat `actor_id`/`actor_name` format is deprecated but will be accepted until 2026-07-02.

```yaml
# Deprecated
actor_id: 102
actor_name: "CURSOR"
```

### Migration

- **New files:** MUST use structured `author` field
- **Existing files:** MAY continue using legacy format until 2026-07-02
- **Validators:** WILL warn on legacy format, reject after deadline

**Trust Weights:** Validators MAY apply different validation rules based on `author.type`:
- `system`: Highest trust, minimal validation
- `agent`: Standard validation
- `actor`: Standard validation
- `user`: Full validation (humans make more mistakes)

## Header Structure

### Required Fields in `lupopedia.headers`

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `header_format_version` | integer | Yes | Current version of header schema (2) |
| `lupopedia.schema` | string | Yes | PRD, documentation, code, etc. |
| `when_updated` | string (quoted) | Yes | UTC YYYYMMDDHHIISS - logical content update time |
| `file_path_from_root` | string | Yes | Canonical path from repo root (absolute, starts with /) |
| `web_path` | string | Yes | Canonical web path with /lupopedia/ prefix |
| `last_modified_utc` | string (quoted) | Yes | UTC YYYYMMDDHHIISS - file write time |
| `federation_node_id` | integer | Yes | 0=core, 1=current install, 2+=external |
| `channel_id` | integer | Conditional | Required for discussions only |
| `thread_id` | string | Conditional | Required for discussions only |
| `prd_id` | integer | Conditional | Required for PRDs only |
| `prd_slug` | string | Conditional | Required for PRDs only |
| `title` | string | Conditional | Required for PRDs only |
| `status` | string | Conditional | Required for PRDs and implementations |
| `version` | string | Conditional | Required for implementation documentation |
| `parent_prd` | string | Conditional | Required for implementations |
| `content_id` | integer | Conditional | Required once imported (deterministic format) |
| `author.type` | string | Yes | `actor`, `agent`, `system`, `user` |
| `author.id` | integer | Yes | Numeric ID from registry |
| `author.name` | string | No | Display name (resolved from registry if omitted) |
| `delegation_chain` | string | Yes | e.g., "lilith:audit" |
| `artifact_type` | string | Yes | Type per taxonomy (see above) |
| `artifact_kind` | string | Yes | Kind per taxonomy (see above) |
| `purpose` | string | Yes | One-line purpose |
| `tags` | list | Yes | Non-empty list of strings |
| `context_id` | integer | No | Optional for finalized contexts (18 digits) |

**Legacy format (deprecated until 2026-07-02):**
- `actor_id` (integer) - Use `author.id` instead
- `actor_name` (string) - Use `author.name` instead

**Author Field Optimization:** Use structured `author` field with explicit `type` and `id`. Legacy `actor_id`/`actor_name` format is deprecated until 2026-07-02.

**Rule 17 Clarification:** Rule 17 (from PRD 26) applies to `slug` fields and display names, not physical file paths. File paths MAY retain numeric prefixes for sorting purposes.

**Explicit ID Naming:** All ID fields use explicit prefixes to avoid ambiguity:
- `prd_id`: PRD identifier (1-999)
- `prd_slug`: URL-friendly PRD identifier
- `content_id`: Database content_id (after import)
- `actor_id`: Actor/agent identifier (from registry)
- `dialog_message_id`: Message identifier (in threads)
- Generic `id` is deprecated to prevent confusion

## Artifact Type Taxonomy

The `artifact_type` field categorizes the artifact by its primary purpose in Lupopedia's documentation architecture (see PRD 26: Five-Layer Documentation Architecture).

| `artifact_type` | Description | Examples |
|-----------------|-------------|----------|
| `prd` | Product Requirements Document — defines WHAT to build | `16_lupopedia_headers.md` |
| `implementation` | Implementation documentation — defines HOW it was built | `README.md` in implementation folders |
| `doctrine` | Constitutional or doctrinal document — defines rules | `root_constitutional_system_requirements.md` |
| `discussion` | Discussion thread or message — captures WHY decisions were made | Thread messages in `discussions/` |
| `changelog` | Version-specific change log | `CHANGELOG.md` |
| `documentation` | General documentation (table docs, guides, etc.) | Table schema docs |
| `architecture` | System architecture specification | PRD 26 itself |
| `specification` | Technical specification | API specifications |

## Artifact Kind Taxonomy

The `artifact_kind` field provides finer-grained classification within an `artifact_type`.

### For `artifact_type: prd` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `requirements` | Standard PRD with requirements | `prd_id`, `prd_slug`, `title`, `status` |
| `architecture` | Architecture PRD | `prd_id`, `prd_slug`, `title`, `status` |
| `specification` | Technical specification PRD | `prd_id`, `prd_slug`, `title`, `status` |

### For `artifact_type: implementation` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `README` | Implementation overview | `parent_prd`, `status` |
| `documentation` | Detailed implementation docs | `parent_prd`, `status`, `version` |
| `authors` | Author attribution | (handled separately) |
| `edges` | System mapping | (handled separately) |

### For `artifact_type: doctrine` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `constitutional` | Root constitutional rules | None (minimal) |
| `reference` | Reference doctrine | None |
| `decisions` | Decision records | None |

### For `artifact_type: discussion` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `thread` | Discussion thread index | `channel_id`, `thread_id` |
| `message` | Individual message in thread | `channel_id`, `thread_id` |

### For `artifact_type: changelog` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `version_specific` | Version-specific changelog | None |

### For `artifact_type: documentation` 

| `artifact_kind` | Description | Required Fields |
|-----------------|-------------|-----------------|
| `table_schema` | Database table documentation | None |
| `guide` | User or developer guide | None |

## Cross-Field Validation Rules

Validators MUST enforce the following combinations:

| `artifact_type` | Allowed `artifact_kind` values |
|-----------------|-------------------------------|
| `prd` | `requirements`, `architecture`, `specification` |
| `implementation` | `README`, `documentation`, `authors`, `edges` |
| `doctrine` | `constitutional`, `reference`, `decisions` |
| `discussion` | `thread`, `message` |
| `changelog` | `version_specific` |
| `documentation` | `table_schema`, `guide` |
| `architecture` | `system`, `data_model` |

**Validation Rule:** If `artifact_type` is not in the allowed list, the validator MUST reject with error.
**Validation Rule:** If `artifact_kind` is not allowed for the given `artifact_type`, the validator MUST reject with error.

## Conditional Required Fields by Type/Kind

| `artifact_type` | `artifact_kind` | Additional Required Fields |
|-----------------|-----------------|---------------------------|
| `prd` | any | `prd_id`, `prd_slug`, `title`, `status` |
| `implementation` | `README` | `parent_prd`, `status` |
| `implementation` | `documentation` | `parent_prd`, `status`, `version` |
| `discussion` | `thread` | `channel_id`, `thread_id` |
| `discussion` | `message` | `channel_id`, `thread_id` |
| All others | any | No additional required fields |

### Deprecated Fields

The following fields are deprecated and must not be used in new artifacts:
- `version_when_written` — use `when_updated`
- `system_version` — no replacement
- `lupopedia.version` — no replacement
- `id` — use `content_id` for database ID, `prd_id` for PRD ID
- `slug` — use `prd_slug` for PRDs, explicit prefixes for other types
- `actor_id` — use `author.id` instead
- `actor_name` — use `author.name` instead


## Verification Process

- Verification may be performed by **actors or agents**
- `lupopedia.footer.verified_by` must reference the verifying entity
- The `verified_by` field structure:
  - Preferred: `type` and `id` fields (same as author)
  - Legacy: `identity_type` and `actor_id` (deprecated until 2026-07-02)
- All verification actions are logged in the system audit trail
- Verification includes header format, field presence, and cross-references
- Stale artifacts (`last_verified < 20260301000000`) must be verified by THOTH

### Verifier Field Structure (Preferred)

```yaml
lupopedia.footer:
  verified_by:
    type: "agent"           # actor | agent | system | user
    id: 2                   # numeric ID from registry
    name: "LILITH"          # display name (resolved from registry if omitted)
```

### Legacy verified_by Format (Deprecated)

```yaml
# Deprecated
verified_by:
  identity_type: "agent"
  actor_id: 2
  agent_name_identity: "LILITH"
```

## Cross-References

- See [LUPOPEDIA_HEADERS_DOCTRINE.md](../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md) for canonical header requirements
- See [VALIDATORS_AND_TOOLING.md](../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md) for validation tools
- See [VERSIONING_MODEL.md](../doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md) for versioning rules
- See [PRD 26: Five-Layer Documentation Architecture](26_five_layer_documentation_architecture.md) for actor identifier types and documentation standards

---

**Status**: APPROVED
**Constitutional Adherence**: FULL
**Next Review**: After next doctrine update
