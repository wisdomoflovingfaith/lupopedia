---
lupopedia.headers:
  when_updated: "20260328240000"
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md"
  federation_node_id: 0
  last_modified_utc: "20260328240000"
  channel_id: 42
  thread_id: "headers-format-spec"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: doctrine
  artifact_kind: documentation
  purpose: Complete LUPOPEDIA HEADERS format documentation with content_id integration
  tags:
  - "4.0.89"
  - "headers"
  - "format"
  - "content_id"
  - "documentation"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/TAXONOMY_REFERENCE.md"
      type: references
      weight: 1.0
      reason: Schema and cross-field quick reference
    - to: "lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: Binding field matrix and DB mapping
    - to: "lupo-scripts/validate_lupopedia_headers_universal.py"
      type: references
      weight: 1.0
      reason: Python validator implementation
    - to: "lupo-includes/classes/LupopediaHeaderValidator.php"
      type: references
      weight: 1.0
      reason: PHP validator implementation
lupopedia.footer:
  last_verified: "20260328240000"
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: "WOLFIE"
    department_id_delta: 0
  verified_via:
    type: direct
    faucet_slug: none
  orchestrator: "wolfie:root"
  next_action:
    - "Keep FORMAT aligned with binding doctrine and validators"
    - "Cross-link TAXONOMY_REFERENCE and OPTIONAL_BLOCKS from new sections"
---

# LUPOPEDIA HEADERS Format Specification

## Overview

LUPOPEDIA HEADERS are YAML front matter blocks that provide semantic context, versioning, actor attribution, channel/thread linkage, and system traceability for all artifacts in the Lupopedia ecosystem.

## File vs database (resolves PLAN vs FORMAT tension)

**In the YAML file (authoring):** `file_path_from_root`, `web_path`, `title` (if used), and similar keys are **first-class header fields**. They are **required** (or strongly expected) in `lupopedia.headers` because they locate the artifact and drive validation.

**In MySQL (`lupo_metadata` plan):** [LUPOPEDIA_HEADERS_PLAN.md](./LUPOPEDIA_HEADERS_PLAN.md) states that `lupo_metadata` does **not** gain extra **DDL columns** for presentation strings — those values are stored as **metadata property rows**, not as new SQL columns. That is a **storage-shape** rule, not permission to omit `file_path_from_root` / `web_path` from the **file** header.

**Summary:** Required YAML keys stay required. The PLAN section applies to **schema evolution of `lupo_metadata`**, not to stripping keys from markdown headers.

## Required Fields

### Core Header Fields (lupopedia.headers)

Binding matrix: **[`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md)**. Quick taxonomy / cross-field table: **[`TAXONOMY_REFERENCE.md`](TAXONOMY_REFERENCE.md)**.

| Field | Type | Required | Description |
|--------|------|----------|-------------|
| `lupopedia.schema` | String | Yes | Canonical schema token (see taxonomy reference and root doctrine) |
| `file_path_from_root` | String | Yes | **Primary filesystem locator** — repo-relative path (required in the file; stored as metadata in DB, not a “presentation-only” optional key) |
| `web_path` | String | Yes | Public or canonical URL; rules depend on `federation_node_id` |
| `federation_node_id` | Integer | Yes | `0` = core, `1` = current install, `2+` = external research |
| `when_updated` | String (quoted) | Yes | UTC `YYYYMMDDHHIISS` — logical content update time |
| `last_modified_utc` | String (quoted) | Yes | UTC `YYYYMMDDHHIISS` — last file write / regeneration |
| `channel_id` | Integer | Yes | Channel ID (e.g. `42`) |
| `thread_id` | String | Yes | **Lowercase, hyphens** (and digits); e.g. `headers-format-spec`. Not the same as optional `lupopedia.routing` legacy `thread_id` (see [OPTIONAL_BLOCKS.md](./OPTIONAL_BLOCKS.md)) |
| `actor_id` | Integer | Yes | Actor ID from registry |
| `actor_name` | String | Yes | Human-readable actor name |
| `delegation_chain` | String | Yes | e.g. `wolfie:root` |
| `artifact_type` | String | Yes | Must pair with `lupopedia.schema` per cross-field rules (see **TAXONOMY_REFERENCE.md**) |
| `artifact_kind` | String | Yes | Same |
| `purpose` | String | Yes | One-line purpose |
| `tags` | List | Yes | Non-empty list of strings |

### Optional / linkage fields

#### `content_id` (optional — database linkage, not “presentation”)

**Type:** integer (BIGINT)  
**Required for authoring:** **No** — do **not** hand-assign for normal edits.

**Semantics:** When present, `content_id` is the **primary key** of the row in **`lupo_contents`**. It is a **linkage** field (import / regeneration), **not** a display title or web URL. It may appear in the header **after** `import_content.py` or `generate_headers_from_db.py` runs.

```yaml
# Optional — set by import/regenerate tooling, not manual authoring
content_id: 12345
```

**Wiring:** `python lupo-scripts/import_content.py <file.md>`; validators **warn** if missing (artifact not linked). See **[`HEADER_DB_REVERSIBILITY_DOCTRINE.md`](../HEADER_DB_REVERSIBILITY_DOCTRINE.md)** and root doctrine *Database-first mapping*.

#### `federation_node_id` (also listed above as required)

Validation of `web_path` by node:

| federation_node_id | Installation | web_path requirement |
|--------------------|--------------|----------------------|
| **0** | Core | Must include `/lupopedia/` subdirectory |
| **1** | Current install | Must include `/lupopedia/` (or install prefix from `LUPOPEDIA_PUBLIC_PATH`) |
| **2+** | External | Valid `http://` or `https://` URL |

## `lupopedia.edges` — YAML names vs `lupo_edges` SQL

After import, outbound edges are mirrored into **`lupo_edges`**. YAML uses short keys; SQL uses install-defined column names. **Authoritative table:** root doctrine *Database-first mapping* (YAML → columns). Short form:

| YAML (`outbound_edges[]`) | SQL column(s) (conceptually) |
|---------------------------|----------------------------|
| `type` | `edge_type` |
| `weight` | `weight_score`, `semantic_weight`, `flare_weight` (see root table) |
| `reason` | `flare_reason` |

There are **no** SQL columns literally named `weight` or `reason`. See **[`TAXONOMY_REFERENCE.md`](TAXONOMY_REFERENCE.md)** → root doctrine link.

## `lupopedia.history` (optional)

- **When to use:** Binding or high-churn docs where an in-file audit trail should also land in **`lupo_contents.revision_history`** JSON after import.
- **Format / event types:** **[`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md)** (*lupopedia.history* section).
- **Storage:** No separate history table; import writes YAML list into **`revision_history`**. Omitting the block does not erase existing DB history.

## Footer Fields (lupopedia.footer)

| Field | Type | Required | Description |
|--------|------|-------------|
| `last_verified` | String (quoted) | Yes | UTC timestamp when last verified |
| `last_verified_by` | String | Yes | Actor who verified |
| `last_verified_by_actor_id` | Integer | Yes | Actor ID of verifier |
| `orchestrator` | String | Yes | Orchestrator chain |
| `next_action` | Array | Yes | Short summary of next steps (typically 1–3 items) |

### `next_action` vs `lupopedia.next_actions`

- **`lupopedia.next_actions`** (optional block) holds the **authoritative** full action list. See [OPTIONAL_BLOCKS.md](./OPTIONAL_BLOCKS.md) § *lupopedia.next_actions*.
- **`lupopedia.footer.next_action`** is the **short summary** (1–3 items). If both blocks exist, the footer list **must** be derived from `lupopedia.next_actions` and must not introduce new actions.

## Complete Example

```yaml
---
lupopedia.headers:
  federation_node_id: 1
  when_updated: "20260328130000"
  lupopedia.schema: "broadcast"
  file_path_from_root: "lupo-channels/42/broadcasts/example.md"
  content_id: 12345
  web_path: "http://www.lupopedia.com/lupopedia/lupo-channels/42/broadcasts/example.md"
  last_modified_utc: "20260328130000"
  channel_id: 42
  thread_id: "4.0.89-header-enforcement"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "broadcast"
  artifact_kind: "directive"
  purpose: "Example directive with content_id"
  tags:
  - "4.0.89"
  - "example"
  - "headers"

lupopedia.edges:
  outbound_edges:
    - to: "lupo-channels/42/threads/1001/related.md"
      type: references
      weight: 1.0
      reason: Related thread discussion

lupopedia.footer:
  last_verified: "20260328130000"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  next_action:
    - "Verify content_id linkage to database"
    - "Test bidirectional sync"
    - "Update related artifacts"
---
```

## Federation Node Rules

### Internal Content (Node 0 or 1)

For content that lives in the current Lupopedia installation:

```yaml
lupopedia.headers:
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/..."
  # federation_node_id is optional (defaults to 1)
```

### External References (Node 2+)

For research references to external sites:

```yaml
lupopedia.headers:
  federation_node_id: 2
  web_path: "https://github.com/doomemacs/doomemacs/blob/master/README.md"
  file_path_from_root: "lupo-content/federation_node_id/2/doomemacs/README.md"
```

**Rules:**
- `federation_node_id` MUST be present and >= 2
- `file_path_from_root` SHOULD be under `lupo-content/federation_node_id/{node_id}/` 
- `web_path` can be any valid URL (no subdirectory requirement)

### File Location for External Research

External research artifacts should be stored in:

```
lupo-content/federation_node_id/{node_id}/{category}/{filename}.md
```

Example:
```
lupo-content/federation_node_id/4/doomemacs/module_structure_analysis.md
lupo-content/federation_node_id/3/bmad/workflow_patterns.md
```

## Validation Rules

Validators enforce these rules:

1. **Required fields must be present** - All required fields must exist
2. **Timestamp format** - Must be YYYYMMDDHHIISS format
3. **Federation-aware web_path validation**:
   - Node 0 (core): Must include `/lupopedia/` subdirectory
   - Node 1 (current install): Must include `/lupopedia/` subdirectory (or custom path from `LUPOPEDIA_PUBLIC_PATH`)
   - Node 2+ (external): Must be valid URL (http:// or https://)
4. **content_id validation** - If present, must be numeric (BIGINT)
5. **No deprecated fields** - `lupopedia.version` and `system_version` are rejected

## Implementation Notes

- **Templates:** Use `lupo-scripts/templates/header-template.md` for new files
- **Import / regenerate:** `lupo-scripts/import_content.py`, `lupo-scripts/generate_headers_from_db.py`, `lupo-scripts/ensure_imported.py`
- **Validators:** `lupo-scripts/validate_lupopedia_headers.py`, `lupo-scripts/validate_lupopedia_headers_universal.py` (stricter cross-field checks), `lupo-includes/classes/LupopediaHeaderValidator.php`

### PHP file header example (comment block)

```php
<?php
/*
---lupopedia.headers:
  when_updated: "20260328120000"
  lupopedia.schema: "class"
  file_path_from_root: "path/to/file.php"
  web_path: "http://www.lupopedia.com/lupopedia/path/to/file.php"
  last_modified_utc: "20260328120000"
  channel_id: 42
  actor_id: 0
  actor_name: "windsurf"
  delegation_chain: "windsurf:root"
  artifact_type: "class"
  artifact_kind: "service"
  purpose: "TODO: Add purpose"
  tags: ["php", "service"]

lupopedia.footer:
  last_verified: "20260328120000"
  verified_by:
    identity_type: "actor"
    actor_id: 0
    agent_name_identity: "WINDSURF"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "windsurf"
  orchestrator: "windsurf:root"
  next_action:
    - "TODO: Add next actions"
---
*/

// Your PHP code here
```

### For Python Files (.py)
1. Place YAML-like comment lines near the top using `#`
2. Use the same keys as PHP files
3. Example structure shown below

## Canonical freshness fields

In `lupopedia.headers`:

- `when_updated` (required, UTC `YYYYMMDDHHIISS`)
- `file_path_from_root` (required)
- `last_modified_utc` (required)
- `web_path` (required for public artifacts) — Must include `/lupopedia/` subdirectory: `http://www.lupopedia.com/lupopedia/<file_path_from_root>` (Lupopedia is always installed in a subdirectory; never omit it)

Deprecated in headers and must be removed:

- `version_when_written`

---

## 🧭 Timestamp Semantics (REQUIRED)

### Distinct Timestamp Roles

The following fields have **distinct, non-overlapping meanings**. They MUST NOT be treated as interchangeable:

- **`when_updated`** (in `lupopedia.headers`)
  - Represents the **logical update time** of the artifact content
  - Set when the artifact **meaningfully changes** (content updated, new section added, concept revised)
  - Reflects the development/authoring timeline
  - Example: A doctrine file updated with new guidance → `when_updated` changes

- **`last_modified_utc`** (in `lupopedia.headers`)
  - Represents the **file system write time**
  - Must reflect the **last time the file was written** to disk
  - Includes regenerations, formatting fixes, and header updates
  - Example: Header regenerated by generator script → `last_modified_utc` changes

- **`last_verified`** (in `lupopedia.footer`)
  - Represents **validation timestamp**
  - Indicates the last time a human or agent **verified** the artifact
  - Triggers staleness detection: if `last_verified < 20260301000000` → header may be stale
  - Example: Cursor verified doctrine accuracy on 2026-03-24 → `last_verified: "20260324150000"`

### Anti-Pattern: Timestamp Conflation

❌ **Do NOT do this:**
- Set all three timestamps to the same value
- Use `when_updated` to track file modifications
- Use `last_modified_utc` to claim the content was reviewed
- Assume `last_verified` has anything to do with when content changed

✅ **DO this instead:**
- `when_updated`: Changes when content meaningfully updates
- `last_modified_utc`: Matches actual file write time (often from regeneration)
- `last_verified`: Reflects human/agent review, independent of the above

### Example: Correct Timestamp Usage

**Scenario**: A doctrine file is updated with new guidance, then regenerated.

```yaml
lupopedia.headers:
  when_updated: "20260324120000"        # Content was updated (new guidance added)
  last_modified_utc: "20260324150000"   # File regenerated later (15:00)
  
lupopedia.footer:
  last_verified: "20260324150000"       # Agent verified during regeneration
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
```

Note: `last_modified_utc` is **later** than `when_updated` because the file was regenerated after content changed.

---

## Footer validation fields

If `lupopedia.footer` exists, require:

- `last_verified` (UTC `YYYYMMDD` or `YYYYMMDDHHIISS`)
- `verified_by.identity_type` (`actor` | `agent`)
- `verified_by.actor_id`
- `verified_via.type` (`faucet` | `direct`)
- `verified_via.faucet_slug` (`none` if direct)

Recommended:

- `verified_by.agent_name_identity`
- `verified_by.department_id_delta` (`0` until department delta is active)

Deprecated:

- Legacy flat verifier name/id keys.

Revalidation rule:

- Missing `last_verified` or `last_verified < 20260301000000` means stale and must be revalidated.

---

# Single-Field Versioning Enforcement

## Overview

Lupopedia enforces a **single-field versioning model** where artifacts use only `when_updated` and `last_modified_utc` for temporal metadata. All deprecated multi-field version schemes (`version_when_written`, `system_version`, `lupopedia.version`) are forbidden.

## Three-Layer Enforcement Architecture

### Layer 1: Header Structure Validation
**Where**: `lupopedia.headers` block  
**Required fields**:
- `when_updated` (UTC YYYYMMDDHHIISS)
- `file_path_from_root`
- `last_modified_utc` (UTC YYYYMMDDHHIISS)

**Forbidden fields** (must be removed if present):
- `version_when_written` ❌
- `system_version` ❌
- `lupopedia.version` ❌

**Enforcement**: Headers must match this structure exactly. No multi-field versioning allowed.

### Layer 2: Footer validation and staleness

**Where:** `lupopedia.footer` block.

**Canonical required shape** when a footer is present: see **Footer validation fields** and **Footer Fields** sections earlier in this file — `last_verified`, `verified_by.*`, `verified_via.*`, plus `next_action` as appropriate.

**Quoted UTC strings:** Use quoted `"YYYYMMDDHHIISS"` values in YAML so parsers do not coerce long integers.

**Staleness:**

```text
If last_verified < 20260301000000 → treat header as STALE
Prefer: regenerate from DB via generate_headers_from_db.py when imported
```

**Semantic review:** Stale footer refresh requires a **truth check** (see [VERIFICATION_GUIDE.md](./VERIFICATION_GUIDE.md) and [README.md](./README.md) — THOTH authority).

### Layer 3: Database-Generated Snapshots
**Where**: `lupo_contents` and `lupo_metadata` tables  
**Enforcement principle**: Headers are generated artifacts, not handwritten.

**Process**:
1. Database holds authoritative metadata (`lupo_contents`, `lupo_metadata`)
2. Script `generate_headers_from_db.py` reads database and generates headers
3. Generated headers are guaranteed to be single-field (cannot deviate)
4. Files receive validated snapshots from database

**Result**: By design, generated headers cannot contain forbidden multi-field versioning.

## Why This Enforcement Works

✅ **Structural guarantee**: Headers generated from database cannot be wrong  
✅ **Staleness detection**: Footer timestamps show when regeneration is needed  
✅ **Immutability via regeneration**: Bad headers are fixed by regeneration, not manual editing  
✅ **Single source of truth**: Database is authoritative; files are validated copies  

The enforcement is **invisible** because it's not restrictive (no warnings) — it's **structural** (headers cannot be wrong by design).

## Script metadata comments (`.py` / `.php`)

Non-Markdown script artifacts in `lupo-scripts/` can carry LUPOPEDIA metadata in top-of-file comments.

- Python files: place YAML-like comment lines near the top using `#`.
- PHP files: place YAML-like lines inside a leading docblock comment.
- Use the same keys:
  - `lupopedia.headers.when_updated`
  - `lupopedia.footer.last_verified`
  - `lupopedia.footer.verified_by.identity_type`
  - `lupopedia.footer.verified_by.actor_id`
  - `lupopedia.footer.verified_via.type`
  - `lupopedia.footer.verified_via.faucet_slug`

Script comment metadata must follow the same stale rule: `last_verified >= 20260301000000`.

## Block guidance

- `lupopedia.headers` is required.
- `lupopedia.footer` is strongly recommended for doctrine, table docs, and channel artifacts.
- `lupopedia.edges` is required for active table documentation under `lupo-docs/database/lupopedia/tables/active/`.

---

# Database as Source of Truth

## Authority Model

**The database is the authoritative source of truth for file metadata.** LUPOPEDIA HEADERS in files are **generated snapshots** from the database, not the reverse.

- **Source of Truth**: `lupo_contents` and `lupo_metadata` tables in the database
- **File Headers**: Snapshots generated from database records
- **Direction**: Database → Files (one-way generation)

## Regeneration Process

When a file header becomes stale or needs to be rebuilt, use the header regeneration script:

```bash
# Regenerate header for a specific file
python lupo-scripts/generate_headers_from_db.py --file-path path/to/file.md

# Regenerate by content ID
python lupo-scripts/generate_headers_from_db.py --content-id 1234567890

# Dry-run (preview changes without modifying file)
python lupo-scripts/generate_headers_from_db.py --dry-run --file-path path/to/file.md
```

### Sources Used During Regeneration

1. **TOON/JSON files** - Define exact table/column schema (`lupo-database/lupopedia/toon/`)
2. **lupo_contents table** - Content records and metadata mapping
3. **lupo_metadata table** - Stored metadata properties and header values

### Canonical Block Order for Generated Headers

Generated headers must maintain this order:
1. `lupopedia.headers` (with required: `when_updated`, `file_path_from_root`, `last_modified_utc`)
2. `lupopedia.footer` (if present; with required: `last_verified`, `verified_by.*`, `verified_via.*`)
3. `lupopedia.edges` (if present; only for active table docs)

## When Regeneration Is Necessary

Regenerate headers if:
- `last_verified < 20260301000000` (stale footer)
- File was edited outside the application
- Database metadata changed but file still has old header
- `lupopedia.footer` is missing on doc or chapter artifacts
- Header block order is incorrect

## Manual Edits to Headers

Manual edits to file headers should be **rare**. Before editing directly:

1. Check if the database record needs updating instead
2. Consider whether regeneration is the proper solution
3. If manual edit is necessary, update the corresponding database record to match
4. Always update `when_updated`, `last_modified_utc`, and `lupopedia.footer` to current UTC timestamp

---

## Related doctrine (explicit links)

| Topic | Document |
|-------|----------|
| Binding field matrix, taxonomy, DB mapping | [`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md) |
| Schema / cross-field quick table | [`TAXONOMY_REFERENCE.md`](TAXONOMY_REFERENCE.md) |
| Optional blocks (`next_actions`, routing, metadata snapshot) | [`OPTIONAL_BLOCKS.md`](OPTIONAL_BLOCKS.md) |
| DB ↔ file round-trip | [`HEADER_DB_REVERSIBILITY_DOCTRINE.md`](../HEADER_DB_REVERSIBILITY_DOCTRINE.md) |
| Validators and scripts | [`VALIDATORS_AND_TOOLING.md`](VALIDATORS_AND_TOOLING.md) |
| Stale verification procedure | [`VERIFICATION_GUIDE.md`](VERIFICATION_GUIDE.md) |
| Stable alias (pointer file) | [`LUPOPEDIA_HEADERS_DOCTRINE.md`](LUPOPEDIA_HEADERS_DOCTRINE.md) |
