---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260403023656"
  file_path_from_root: "lupo-docs/prd/16_lupopedia_headers.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/16_lupopedia_headers.md"
  last_modified_utc: "20260403023656"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-lupopedia-headers"
  prd_id: 16
  prd_slug: lupopedia_headers
  title: "Lupopedia File Headers and Verification"
  author:
    type: "actor"
    id: 2
    name: "LILITH"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Canonical specification for Lupopedia file headers, verification, and metadata propagation"
  status: "active"
  tags:
  - "prd"
  - "lupopedia_headers"
  - "metadata"
  - "verification"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: references
      weight: 1.0
      reason: "Five-layer documentation architecture"
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
      reason: "Python validator (artifact types, author, optional content_id / context_id, --check-db)"
    - to: "lupo-scripts/import_content.py"
      type: implements
      weight: 1.0
      reason: "Import into lupo_contents; metadata and lupopedia_header edges sync"
    - to: "lupo-scripts/lib/header_db_sync.py"
      type: implements
      weight: 1.0
      reason: "Persists headers to lupo_metadata and lupo_edges"
lupopedia.footer:
  last_verified: "20260403023341"
  verified_by:
    type: "actor"
    id: 2
    name: "LILITH"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "none"
  orchestrator: "lilith:audit"
  next_action:
    - "Keep aligned with import_content.py and header_db_sync behavior"
    - "Ensure verification authority (THOTH) is documented"
    - "Update stale artifacts with correct header format"
---

# PRD: Lupopedia File Headers and Verification

## Overview

This PRD defines the canonical requirements, structure, and verification process for Lupopedia file headers. All files in the Lupopedia system must include a YAML-formatted `lupopedia.headers` block, which encodes file identity, version, schema, and verification metadata. Verification may be attributed to **actors** or **agents**; **`lupopedia.footer.verified_by`** records who performed verification (see Author vs Verifier and verification sections below).

## Constitutional Compliance

All header metadata and verification processes must comply with Lupopedia constitutional rules:

- **Verification authority**: Both actors and agents may perform verification
- **Primary authority**: THOTH (actor_id 26) is canonical for stale artifacts (`last_verified < 20260301000000`)
- **Identity tracking**: **`verified_by.type`** (preferred) or legacy **`verified_by.identity_type`** distinguishes actor vs agent
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
| **Verifier** | `lupopedia.footer.verified_by` | Attribution of content validation | Yes (if footer present) |

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
| `file_path_from_root` | string | Yes | **Repo-relative** path from repository root — **no** leading `/` (matches `import_content._norm_path_repo` and `lib/header_validation._is_valid_relative_path`) |
| `web_path` | string | Yes | Canonical public URL; must include the `/lupopedia/` subdirectory for this install (see [LUPOPEDIA_HEADERS_FORMAT.md](../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)); host is conventionally `www.lupopedia.com` in examples |
| `last_modified_utc` | string (quoted) | Yes | UTC YYYYMMDDHHIISS - file write time |
| `federation_node_id` | integer | Yes | 0=core, 1=current install, 2+=external |
| `channel_id` | integer | Conditional | Required for discussions only |
| `thread_id` | string | Conditional | Required for discussions only |
| `prd_id` | integer | Conditional | Required for PRDs only |
| `prd_slug` | string | Conditional | Required for PRDs only |
| `title` | string | Conditional | Required for PRDs only (enforced by **`validate_lupopedia_headers_universal.py`** for `artifact_type: prd`) |
| `status` | string | Conditional | Required for PRDs and implementations — allowed values include **`draft`**, **`review`**, **`approved`**, **`implemented`**, **`active`** (ratified for operational use after audit), **`deprecated`** (see **`validate_lupopedia_headers_universal.py`**) |
| `version` | string | Conditional | Required for implementation documentation |
| `parent_prd` | string | Conditional | Required for implementations |
| `content_id` | integer | No (authoring) | **Omit** for normal edits. Set by **`import_content.py`** after a successful upsert into **`lupo_contents`**, optionally written into the file with **`--write-back`**. **Never** set `content_id` equal to **`prd_id`** or other IDs. **`validate_lupopedia_headers_universal.py --check-db`** compares file vs DB when `content_id` is present. |
| `author.type` | string | Yes | `actor`, `agent`, `system`, `user` |
| `author.id` | integer | Yes | Numeric ID from registry |
| `author.name` | string | No | Display name (resolved from registry if omitted) |
| `delegation_chain` | string | Yes | e.g., "lilith:audit" |
| `artifact_type` | string | Yes | Type per taxonomy (see above) |
| `artifact_kind` | string | Yes | Kind per taxonomy (see above) |
| `purpose` | string | Yes | One-line purpose |
| `tags` | list | Yes | Non-empty list of strings |
| `context_id` | integer | No | Optional; if present, **18 digits** (validator). Prefer **`lupopedia.edges`** for graph relationships; **`context_id`** is legacy **`lupo-contexts/`** linkage only |

**Legacy format (deprecated until 2026-07-02):**
- `actor_id` (integer) - Use `author.id` instead
- `actor_name` (string) - Use `author.name` instead

**Author Field Optimization:** Use structured `author` field with explicit `type` and `id`. Legacy `actor_id`/`actor_name` format is deprecated until 2026-07-02.

**Rule 17 Clarification:** Rule 17 (from PRD 26) applies to `slug` fields and display names, not physical file paths. File paths MAY retain numeric prefixes for sorting purposes.

**Explicit ID Naming:** All ID fields use explicit prefixes to avoid ambiguity:
- `prd_id`: PRD identifier (1-999)
- `prd_slug`: URL-friendly PRD identifier
- `content_id`: **`lupo_contents.content_id`** — application-assigned BIGINT after import (**not** the same namespace as `prd_id`)
- `actor_id`: Actor identifier (from registry); registry-backed “agents” are still **actors** for ID purposes
- `dialog_message_id`: Message identifier (in **`lupo_dialog_messages`** and related thread tables)
- Generic `id` is deprecated to prevent confusion

### Legacy `actor_id` / `actor_name` alongside `author` (import path)

**`lupo-scripts/lib/header_validation.validate_header`** (used by **`import_content.py`**) still requires **`actor_id`** and **`actor_name`** until that module is extended. For files that use structured **`author`**, also supply **`actor_id`** / **`actor_name`** mirroring **`author.id`** / **`author.name`** so import validation succeeds.

## Header format versioning

| Version | Meaning | Status |
|--------|---------|--------|
| **1** | Legacy flat **`actor_id`** / **`actor_name`** at top level of **`lupopedia.headers`** | Deprecated; migrate to v2 |
| **2** | Structured **`author`** (and **`verified_by`** with **`type`** / **`id`**); **`header_format_version: 2`** in file | Current |

Validators SHOULD accept v1 with warnings until the deprecation deadline. **`validate_lupopedia_headers_universal.py`** enforces **author** structure and type-specific fields (e.g. **`title`** for PRDs).

## Database linkage: `content_id`, import, metadata, edges

### `content_id` and `lupo_contents`

- **Authoring:** Omit **`content_id`** from new markdown unless you are round-tripping from DB.
- **Import:** **`python lupo-scripts/import_content.py <file.md>`** computes a **`content_id`** via **`calculate_content_id()`** (UTC timestamp + random suffix, optional collision retry against **`lupo_contents`** — implementation detail in script, not hand-authored). If the script’s top-of-file docstring disagrees, treat **`calculate_content_id`** as authoritative for this tree. Then **`lib/header_db_sync.sync_header_artifact_to_db`** runs after the **`lupo_contents`** upsert (with **`channel_id`** and **`actor_id`** from header fields).
- **`--write-back`:** After a successful import, writes **`lupopedia.headers.content_id`** into the YAML (insert or replace line). Use this so **`--check-db`** on the universal validator can match file vs database.
- **Namespace:** Never reuse **`prd_id`** or other semantic IDs as **`content_id`**.

### Channel and thread on import

- **`channel_id`:** Stored on **`lupo_contents.channel_id`** when present in the header.
- **`thread_id`:** There is **no** dedicated **`thread_id`** column on **`lupo_contents`**. The full header mapping is persisted under **`lupo_metadata`** with **`class_name=lupopedia_header_sync`**, **`entity_type=content`**, **`entity_id=content_id`** — so **`thread_id`** (and other header keys) remain queryable as metadata properties, not as a first-class DDL column on the content row.
- **Coordination messages:** **`lupo_dialog_messages`** (and related thread tables) hold **questions, answers, comments, and decision-class messages** in the **channel/thread** runtime; those are **not** the same rows as **`lupo_contents`**, but **`channel_id`** / **`thread_id`** in headers align the **document** artifact with the same coordination context.

### `lupopedia.edges` vs `context_id`

- **Preferred:** **`lupopedia.edges.outbound_edges`** with **`to:`** repo-relative paths (**no** leading `/`). After import, **`header_db_sync`** creates **`lupo_edges`** rows with **`edge_category=lupopedia_header`** linking the content entity to targets (resolved to **`content`** or **`file_path_ref`**).
- **`context_id`:** Optional; validated as **18 digits** when present in **`validate_lupopedia_headers_universal.py`**. Treat as **legacy** linkage to **`lupo-contexts/`** where still used — **do not** use it instead of edges for cross-document references.

## LILITH audit record

### Path and ID hygiene (resolved)

| Finding | Resolution |
|--------|------------|
| Leading `/` on **`file_path_from_root`** | **Fixed:** repo-relative only |
| **`content_id` colliding with `prd_id` semantics** | **Fixed:** no manual **`content_id`** in the example header; separate namespaces documented |
| **`web_path`** host | **Documented:** examples use **`http://www.lupopedia.com/lupopedia/...`**; subdirectory is mandatory |
| **`lupopedia.edges.to`** leading slashes | **Fixed:** repo-relative targets |
| **`author.type`** for LILITH | **`author.type: actor`** (**`actor_id` 2** in registry) |
| Missing **`header_format_version`** narrative | **Added:** versioning table above |
| **`context_id`** vs edges | **Documented:** prefer **`lupopedia.edges`**; **`context_id`** legacy only |

### Final review (2026-04-03 UTC)

| Field | Value |
|-------|--------|
| **Verdict** | **APPROVED** — ready for operational use; **`lupopedia.headers.status`** set to **`active`** |
| **Accuracy (reported)** | 99/100 |
| **Constitutional violations** | None reported |
| **Remaining debt** | **`lib/header_validation.validate_header`** still requires legacy **`actor_id`** / **`actor_name`** (mirror **`author`** until validator accepts **`author`** alone); optional docstring fix on **`import_content.py`** |

This PRD is the canonical specification for Lupopedia headers; follow **`next_action`** in the header footer for maintenance tasks.

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
    type: "actor"           # actor | agent | system | user — use actor for registry actors (e.g. LILITH actor_id 2)
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

- See [LUPOPEDIA_HEADERS_FORMAT.md](../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) for **`file_path_from_root`**, **`web_path`**, **`content_id`**, and edges vs **`context_id`**
- See [LUPOPEDIA_HEADERS_DOCTRINE.md](../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md) for canonical header requirements
- See [VALIDATORS_AND_TOOLING.md](../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md) for validation tools
- See [VERSIONING_MODEL.md](../doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md) for versioning rules
- See [PRD 26: Five-Layer Documentation Architecture](26_five_layer_documentation_architecture.md) for actor identifier types and documentation standards
- **`lupo-scripts/validate_lupopedia_headers_universal.py`** — type-specific rules, optional **`content_id`** / **`context_id`**, **`--check-db`**
- **`lupo-scripts/import_content.py`** — **`lupo_contents`** upsert; optional **`--write-back`** for **`content_id`**
- **`lupo-scripts/lib/header_db_sync.py`** — **`lupo_metadata`** + **`lupo_edges`** sync

---

**Status**: ACTIVE (LILITH final audit 2026-04-03 UTC; `lupopedia.headers.status: active`)

**Constitutional adherence**: FULL

**Next review**: When **`import_content.py`**, **`header_db_sync`**, or validators change materially; or when legacy **`actor_id`** / **`actor_name`** import path is retired (see deprecation deadline in this PRD).
