---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS"
  title: "LUPOPEDIA HEADERS"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "index"
---
# file: LUPOPEDIA HEADERS — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS

Ordinary doctrine artifacts embed only `lupopedia.headers` by default. `lupopedia.session` is an explicit optional verbose exception only when `embedded_session_snapshot: true` is intentionally used.

# LUPOPEDIA HEADERS

**LUPOPEDIA HEADERS** (which has historical alias names such as **FLARE**, **FLIP**, **WOLFIE**, **FLP**, **FLPH**, **CROP**, and **FLAME**) are the canonical metadata protocol name from **4.0.68** onward. They **replace** the older header systems which are **deprecated**. See [DEPRECATION_FLARE_FLIP_FLP.md](./DEPRECATION_FLARE_FLIP_FLP.md). New and modified files must use LUPOPEDIA HEADERS; validators accept legacy `flare.*` / `flame.*` only for backward compatibility. Logical block structure and doctrinal lineage are preserved.

Block naming rule (concept vs on-disk key):

- `Lupopedia.*` = conceptual/doctrinal block names used in prose and rule descriptions.
- `lupopedia.*` = the current serialized/validator-compatible YAML keys that appear in Markdown front matter (lowercase).

Writers should use the lowercase `lupopedia.*` keys in new/modified files unless a specific validator/tool explicitly requires otherwise. Validators/tooling may still accept historical aliases (`flare.*` / `flame.*`) for backward compatibility.

- **Storage:** `lupo_metadata` table "metadata" (with table prefix of "lupo_"), structured as rows (root → blocks → properties → edges/mappings/actions). No single YAML blob column; no dedicated presentation columns.
- **Schema additions:** Only `channel_id`, `parent_metadata_id`, `class_name`.
- **Format:** First line of file is `---`; then YAML blocks in canonical order; then `---`; then the identity line `# file: ...` as the first line of the body; then document content.

## Core doctrine (4.0.79)

Apply this principle:

**Headers declare artifact.  
The database declares the world around it.**

### General rule (ordinary documentation)

For ordinary documentation artifacts (doctrine/spec/foundation/status), **handwritten header** should contain only **human-authored, stable identity and intent**. Do **not** treat DB-derived, computed, relational, inferred, or dynamic “world state” as default handwritten header content.

Ordinary doctrine docs default to **`lupopedia.headers` only** (plus the required `# file: ...` identity line in the body).

All other handwritten blocks are **optional** and only appear when the artifact type requires them:

- `lupopedia.init`, `lupopedia.routing` (when the artifact is a planning/coordination artifact)
- `lupopedia.metadata` (optional snapshot of metadata rows for this artifact/entity)
- `lupopedia.environment` (when environment-related context is intentionally authored)
- `lupopedia.next_actions` and `lupopedia.comments` (when follow-ups/comments are intentionally authored)

What should **not** be taught as default handwritten header content for general docs (DB-derived/synthetic-view concerns):

- `Lupopedia.engagement`
- `Lupopedia.lineage`
- `Lupopedia.actors`
- `Lupopedia.graph`
- `Lupopedia.references`
- `Lupopedia.relationships`
- `Lupopedia.usage`

### Special exception (active table documentation)

For active table documentation files under:

- `lupo-docs/database/lupopedia/tables/active/*.md`

`Lupopedia.edges` is **required** and should be **verbose**, populated from grounded repository evidence:

- `USED_IN_PHP`
- `USED_IN_PYTHON`
- `DEPENDS_ON_TABLE`
- `REFERENCED_BY_TABLE`
- `DEFINES_SCHEMA_FOR`
- `USED_BY_MODULE`
- `USED_BY_SERVICE`
- `USED_BY_AGENT`

This is an explicit exception: **active table documentation is a semantic mapping surface**, so edges are declared in those files.

## Relationship to Collections

Collections and namespaces are **conditionally coupled** dimensions that serve different purposes:

| Aspect | Collections | Namespaces |
|---------|-------------|------------|
| **Purpose** | Membership in named sets for navigation, filtering, and UI grouping | Domain/jurisdiction classification for policy, taxonomy, and validation |
| **Field location** | `collections` array in headers (or DB membership) | `namespace` single string in `lupopedia.headers` |
| **Cardinality** | Many-to-many (artifact can belong to multiple collections) | Many-to-one (artifact has exactly one namespace) |
| **Authority scope** | Navigation, tabs, URLs, breadcrumbs through database structure | Policy, validation, and table-document requirements |
| **Precedence** | For navigation/display decisions: collections win | For policy/validation decisions: namespace wins |
| **Path authority** | Neither collections nor namespaces define filesystem paths | Neither collections nor namespaces define filesystem paths |

**Key principle:** When both fields are present and inform the same decision, resolve by scope - not by treating both as equally authoritative.

## Docs in this folder

| Document | Purpose |
|----------|---------|
| [LUPOPEDIA_HEADERS_PLAN.md](./LUPOPEDIA_HEADERS_PLAN.md) | Authoritative plan: schema, storage model, block order, channel support, version rule. |
| [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) | Markdown file structure and required header fields. |
| [LUPOPEDIA_HEADERS_MIGRATION.md](./LUPOPEDIA_HEADERS_MIGRATION.md) | Incremental migration from FLARE, validator and tooling expectations. |
| [DEPRECATION_FLARE_FLIP_FLP.md](./DEPRECATION_FLARE_FLIP_FLP.md) | Deprecation notice: FLARE, FLIP, FLP replaced by LUPOPEDIA HEADERS. |
| [OPTIONAL_BLOCKS.md](./OPTIONAL_BLOCKS.md) | Optional blocks: lupopedia.routing, lupopedia.lists, **lupopedia.next_actions** (suggested next actions; legacy: lupopedia.close). |
| [VERSIONING_MODEL.md](./VERSIONING_MODEL.md) | **Obsolete stub only** — historical links; canonical rules are in LUPOPEDIA_HEADERS_FORMAT.md §2. |
| [lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md](../../../lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md) | **Mandatory header rewrite** when `version_when_written` &lt; 4.0.84 or deprecated version keys exist — pair with [directives.md](../../../directives.md). |

---

## Edge Cases and Structural Analysis

### LILITH-001: Dual-State Artifacts (Handwritten + Database)

**Issue:** The "Core doctrine" section distinguishes "handwritten header content" from "DB-derived/synthetic-view concerns" but provides no mechanism for reconciling these when a file is both a human-authored artifact and a database record with computed fields. The artifact exists in two states simultaneously.

**Analysis:** This creates a semantic ambiguity where the same file has conflicting authority sources. Doctrine needs resolution rules for when handwritten fields take precedence vs. when database-derived fields override.

---

### LILITH-002: Grounding Evidence Verification

**Issue:** The special exception for active table documentation requires `Lupopedia.edges` to be "verbose" and "populated from grounded repository evidence." The doctrine does not specify how this grounding evidence is verified or updated when the repository changes but the human-authored file does not.

**Analysis:** Creates a maintenance gap where table documentation can become stale without clear update triggers or verification mechanisms.

---

### LILITH-003: Collections Absence Ambiguity

**Issue:** The "Relationship to Collections" section defines precedence rules for navigation vs. policy decisions but omits the case where an artifact has no collections field but is discoverable via collections membership in the database. The doctrine does not specify whether absence implies empty set or database authority.

**Analysis:** Missing rule for implicit vs. explicit collections membership creates potential conflicts between file-level and database-level navigation.

---

### LILITH-004: Case Normalization Gap

**Issue:** Without explicit normalization rules, `Lupopedia.*` vs `lupopedia.*` can be treated as distinct representations.

**Resolution:** Treat block names as case-normalized for semantics:

- Validation and internal comparisons treat `Lupopedia.*` and `lupopedia.*` as the same conceptual block after normalizing case.
- For handwritten YAML keys, use lowercase `lupopedia.*` keys for validator/tooling compatibility.
- If an artifact uses a different casing form, treat it as compatible; do not treat casing drift as semantic drift.

---

### LILITH-005: External Rule Conflict Resolution

**Issue:** Multiple referenced doctrine/rule files can conflict; this must have a deterministic precedence order.

**Resolution:** When conflicts arise, apply this precedence hierarchy (highest to lowest):

1. Constitutional/directives baseline rewrite rule: `directives.md` (baseline rewrite section) and `lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md`
2. This doctrine: `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`
3. Canonical format and field requirements: `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md`
4. Canonical plan/order expectations: `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md`
5. Other `lupo-rules/root/*.md` doctrines, only when they do not contradict the LUPOPEDIA HEADERS baseline and format rules
6. Legacy FLARE/FLIP/FLP docs only for backward compatibility; they never override current LUPOPEDIA HEADERS rules

---

## Quick reference

- **Baseline rewrite on write:** If you edit a file and `version_when_written` is **below 4.0.84** (or deprecated version keys remain in `lupopedia.headers`), **rewrite headers** to current doctrine before save. See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2.0, **[directives.md](../../../directives.md)** (baseline rewrite section), and **[LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md](../../../lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md)**.
- **Versioning in `lupopedia.headers`:** The only canonical version field is **`version_when_written`**. See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2 for required fields and §2.0 for migration-on-write.
- **Minimal `lupopedia.headers` shape (new files):**
  ```yaml
  lupopedia.headers:
    version_when_written: "4.0.84"
    file_path_from_root: "path/from/repo/root.md"
    # optional: lupopedia.schema, web_path, channel_id, actor_id, …
  ```
- **First line of file:** `---` (nothing else on line 1 — no identity line, no heading). **Identity line** `# file: ...` goes **after** the closing `---`, as the first line of the body. See .cursor/rules/lupopedia-headers-file-order.mdc for mandatory order (all IDE agents).
- **Exactly one front matter block:** Do not duplicate; one opening `---`, one YAML block, one closing `---` per file. No second header block.
- **Then:** YAML blocks (canonical order) → `---` → identity line `# file: {title} — delegation: {delegation_chain} — web_path: {web_path}` (include `session: {session_name}` only when a `lupopedia.session` block is present) → body
- **Session:** Session information belongs in **`lupopedia.session`**, not in `lupopedia.headers`. **Headers = artifact metadata**; **session = runtime execution context**. By default, agents read session from the **active runtime** (PHP `$_SESSION[]` or IDE session file in **`lupo-database/sessions/`**), not from the file. Session file naming: `L-LUPO-<ACTOR_NAME>_<ACTOR_FAUCET>_<UUID>.md` (e.g. `L-LUPO-CURSOR_DEV_3F6A9B2A.md`). Normally only `lupopedia.headers` is written into artifact files; when **verbose output** is enabled, `lupopedia.session` may be embedded as a snapshot at artifact creation time **only when** `embedded_session_snapshot: true` is intentionally used. See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2.1 for full semantics and the canonical session comment.
- **Block naming note:** In prose and rule discussions, prefer canonical conceptual names `Lupopedia.*`. In Markdown front matter, use the serialized/validator-compatible keys `lupopedia.*` (lowercase). Validators treat case-normalized names as the same conceptual block; writers should keep using lowercase for validator compatibility unless a specific tool mandates otherwise.
- **Snapshots (dynamic blocks):** When present, dynamic blocks (e.g. `Lupopedia.edges`, `Lupopedia.engagement`) MUST be labeled as a **snapshot** (query the database for latest values). These blocks are **not** default for ordinary docs; they are used when a specific artifact type requires them (notably: active table docs).
- **Deprecated:** `flare.*` and `flame.*` are accepted by validators for backward compatibility only; do not use for new files. See [DEPRECATION_FLARE_FLIP_FLP.md](./DEPRECATION_FLARE_FLIP_FLP.md).
- **Lookup:** by `entity_type` + `entity_id` and/or `channel_id`
- **Optional human-readable:** **`channel_name`** (with `channel_id`) and **`thread_name`** (with `thread_id` when thread-scoped). Example: channel_id 42 = "Lupopedia Development (general)". See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2 and [LUPOPEDIA_HEADERS_PLAN.md](./LUPOPEDIA_HEADERS_PLAN.md) §4.1.
