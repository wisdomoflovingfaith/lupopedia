---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT"
  title: "LUPOPEDIA HEADERS Format"
  delegation_chain: "junie:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  namespace: "governance"
lupopedia.footer:
  last_verified: "20260324"
  last_verified_by: "junie"
  orchestrator: "junie:root"
  next_action:
    - "Integrate verification guide into agent system prompts"
    - "Update footer examples across all doctrine files to use junie"
    - "Point agents to VERIFICATION_GUIDE.md for correctness audits"
---
# file: LUPOPEDIA HEADERS Format — delegation: junie:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT

# LUPOPEDIA HEADERS — File format and version rule

**Version:** 4.0.69+  
**Canonical name:** LUPOPEDIA HEADERS (FLARE = historical logical structure)

---

## 0. Doctrine note (general docs vs active table docs)

Apply this principle:

**Headers declare the artifact. The database declares the world around it.**

- **Ordinary documentation artifacts** (doctrine/spec/foundation/status) should keep handwritten headers **stable and human-authored** (identity + intent). Do not teach dynamic relationship/usage/engagement blocks as universal defaults.
- **Active table documentation** (`lupo-docs/database/lupopedia/tables/active/*.md`) is the special exception: it must include a **verbose `Lupopedia.edges`** mapping surface, grounded in real repository evidence (PHP/Python/schema/seed/install SQL usage).

## 1. Version Semantics Model (4.0.84+)

Lupopedia 4.0.84+ separates **authorship/baseline history** from **freshness** and **validation status**.

### 1.1 Canonical Version Triad

| Field | Meaning | Scope | Rule |
|-------|---------|-------|------|
| **`version_when_written`** | **Historical artifact authorship / baseline era.** Represents the Lupopedia version context when the artifact was originally authored OR baseline-rewritten (e.g. pre-4.0.84 → 4.0.84+). | Authorship / Intent | **STABLE.** MUST NOT change on routine edits. ONLY changes when crossing a doctrinal baseline boundary OR explicit full re-authoring of the artifact. |
| **`last_modified_utc`** | **Latest meaningful modification.** Records the last time any part of the header OR body was changed. | Freshness | **DYNAMIC.** Used for freshness indicators. Optional but recommended. DOES NOT affect `version_when_written`. |
| **`lupopedia.footer.last_verified`** | **Latest human/agent verification.** Records the last time the artifact was audited or verified for correctness. | Trust / Integrity | **CADENCE-DRIVEN.** Independent of authorship and modification. Used for trust and validation cadence. |

### 1.2 What `version_when_written` is NOT

To ensure semantic integrity, remember that `version_when_written` is:

- **NOT** a freshness indicator.
- **NOT** the current system version (which is always in `LUPEDIA_VERSION`).
- **NOT** updated on every save.
- **NOT** tied to git commits or filesystem timestamps.
- **NOT** a replacement for `last_modified_utc`.
- **NOT** a validation signal.

### 1.3 Semantic Integrity Principle (ROSE Alignment)

`version_when_written` preserves **artifact-era meaning**, not system state. This aligns with ROSE doctrine:
- Preserve meaning first.
- Avoid multi-purpose fields.
- Ensure one field = one responsibility.

---

## 2. Markdown file structure

The **first line** of the file MUST be:

```text
---
```

**DO NOT** put the identity line (`# file: ...`) or any heading on line 1. The identity line belongs **after** the closing `---` of the YAML block, as the first line of the body. Putting it before the opening `---` is invalid and will be rejected by validators.

**DO NOT** duplicate the header. There must be **exactly one** YAML front matter block per file: one opening `---`, one set of blocks (lupopedia.headers, etc.), one closing `---`. Never add a second `---` … YAML … `---` block elsewhere in the file. When merging or updating headers, consolidate into a single block and remove any duplicate.

Then the YAML header blocks in **canonical order** (see [LUPOPEDIA_HEADERS_PLAN.md](./LUPOPEDIA_HEADERS_PLAN.md) §4). Use **lupopedia.*** block names in new or modified files (4.0.69+); validators accept legacy flare.*/flame.*:
 
Block naming rule (concept vs on-disk key):

- `Lupopedia.*` = conceptual/doctrinal block names used in prose and rule descriptions.
- `lupopedia.*` = the current serialized/validator-compatible YAML keys that appear in Markdown front matter.

Serialization rule:
- YAML front matter MUST use `lupopedia.*` keys.
- `Lupopedia.*` is conceptual only and MUST NOT appear in serialized front matter.
 

- **lupopedia.init** — Optional. Lists **required reading** and **required context** that must be read or understood **before** reading this file (e.g. `required_reading:`, `required_context:`). It is **not** for file metadata; use `lupopedia.headers` or `lupopedia.metadata` for artifact_type, file_identity, namespace, domain. See [LUPO_INITIALIZATION_DOCTRINE.md](../init/LUPO_INITIALIZATION_DOCTRINE.md). Supported forms: simple list of paths, or list of `path`/`reason` objects.
- **lupopedia.routing** — Optional. Routing and approval metadata for planning artifacts and cross-actor workflows. Includes channel, actor, recipient, session, priority, and approval requirements.
- **lupopedia.actor_references** — Optional. Actor IDs from canonical registry (plan/report files). See [OPTIONAL_BLOCKS.md](./OPTIONAL_BLOCKS.md).
- lupopedia.conditional
- lupopedia.headers (required)
- lupopedia.metadata (optional; snapshot of metadata rows for this artifact — see OPTIONAL_BLOCKS; not table schema)
- lupopedia.session (optional; session context)
- lupopedia.edges (optional; required only for certain artifact types — notably active table docs)
- lupopedia.engagement (optional; engagement metrics — rarely used, not a general-doc default)
- lupopedia.footer (optional)
- lupopedia.see (optional)
- **lupopedia.next_actions** (optional) — Suggested next actions after reading or using this file (like **lupopedia.init** but for follow-ups). Use **lupopedia.next_actions** in new files; **lupopedia.close** is the legacy name (validators accept both). See [OPTIONAL_BLOCKS.md](./OPTIONAL_BLOCKS.md) § lupopedia.next_actions.

Then the closing delimiter:

```text
---
```

Then the **identity line** as the first line of the body:

```text
# file: {title} — delegation: {delegation_chain} — web_path: {web_path}
```

**Ordinary (default) identity-line form** uses no `session:` field.

**Verbose session snapshot identity-line form** is used only when a `lupopedia.session` block is present and you are intentionally embedding a verbose session snapshot; in that case `{session_name}` MUST be taken from **`lupopedia.session.session_name`**. Then the rest of the document body.

```text
# file: {title} — session: {session_name} — delegation: {delegation_chain} — web_path: {web_path}
```

---

## 3. Required fields (in lupopedia.headers)

### 3.1 Baseline Rewrite Rule (STRICT)

A rewrite of `lupopedia.headers` (specifically updating `version_when_written`) occurs ONLY when crossing a doctrinal baseline.

**A rewrite is REQUIRED if:**

- **`version_when_written`** is missing, **OR**
- It names a system version **strictly before 4.0.84**, **OR**
- **`lupopedia.headers`** still contains deprecated version keys: `lupopedia.version`, `system_version`, `last_verified_system_version`, or standalone `version`.

**ON REWRITE:**
- Set **`version_when_written`** to the **current** system version (read **`LUPEDIA_VERSION`**).
- Remove all deprecated keys.
- Ensure **`file_path_from_root`** is accurate.

**AFTER REWRITE:**
- **`version_when_written`** becomes **STABLE**.
- **DO NOT** update it on normal edits, even if the system version has since increased.
- Use **`last_modified_utc`** (optional) and **`lupopedia.footer`** for tracking changes and verification.

---

**Minimum (always):**

- **`version_when_written`** — Historical context version (e.g. `"4.0.84"`). This is the **only** canonical version field in `lupopedia.headers`. Do **not** use `lupopedia.version`, `system_version`, `last_verified_system_version`, or a standalone `version` key here.
- **`file_path_from_root`** — Path from repository root (REQUIRED).
- **`web_path`** — Canonical web URL for the artifact (OPTIONAL). When present, it MUST be a fully qualified URL (including protocol and domain). 
  - **Path construction rule:** Because Lupopedia is always installed in a subdirectory (never at the web root), the `web_path` MUST include the `LUPOPEDIA_BASE_URL` (defined in `lupopedia-config.php`).
  - **Example:** If `LUPOPEDIA_BASE_URL` is `/lupopedia/` and the file is `README.md`, the `web_path` would be `http://www.lupopedia.com/lupopedia/README`.
  - **Implicit suffixing:** Do not include `.md` or `.php` extensions in the `web_path` unless the target is a physical file that is not routed through the semantic OS.

**Conditional:**

- **`content_id`** — Present when the file is **actually** imported into **`lupo_content`** (or otherwise database-managed as content). Handwritten doctrine and repo files usually **omit** it; tooling may add it on import.

**Optional / conditional (unchanged):** `lupopedia.schema`, `last_modified_utc`, `channel_id`, `actor_id`, `delegation_chain`, `artifact_type`, `artifact_kind`, `purpose`, `tags`, `namespace`, etc. For **table documentation**, **`namespace`** is **required** (approved taxonomy). Optional: `actor_name`, `mood_rgb`, `traits`, `lupo_agent`, `agent_name_identity`. **Session-related fields** (e.g. `session_id`, `session_name`) belong in **`lupopedia.session`**, not in `lupopedia.headers`.

**After first publication:** Prefer **`last_modified_utc`** (optional) and **`lupopedia.footer`** verification fields for “header freshness” when present. If `last_modified_utc` is absent, tooling may fall back to filesystem timestamps for convenience; **never** treat filesystem timestamps as baseline eligibility.

**Database integration (`content_id`):** When a file is imported into `lupo_content`, a `content_id` metadata field MUST be set for traceability. If `content_id` is absent, treat the file as not yet imported (for handwritten files this is normal).

**Optional channel and thread (human-readable):** In addition to `channel_id`, headers MAY include **`channel_name`** — a human-readable name for the channel (e.g. `"Lupopedia Development (general)"` for channel_id 42). When the artifact is scoped to a specific thread, **`thread_name`** MAY be included (human-readable thread name) alongside `thread_id` (which may appear in `lupopedia.session`). These aid display and context without changing resolution logic.

| Field | Block | Purpose |
|-------|--------|--------|
| `channel_id` | lupopedia.headers or lupopedia.session | Channel identifier (numeric). |
| `channel_name` | lupopedia.headers or lupopedia.session | Human-readable channel name (optional). |
| `thread_id` | lupopedia.session | Thread identifier when artifact is thread-scoped (optional). |
| `thread_name` | lupopedia.headers or lupopedia.session | Human-readable thread name when available (optional). |

### 2.1 Namespace (lupopedia.headers)

**`namespace`** is a first-class field in **`lupopedia.headers`**. It classifies the artifact for logical grouping, discovery, and jurisdiction. Namespace is **node-local by default**; federation-wide namespace mapping is future-facing and not required in 4.0.78.

#### 2.1.1 Distinction from PHP Namespace (MANDATORY)

Do not confuse the `namespace` header with the PHP `namespace` keyword. They are independent and serve different purposes:

- **Header `namespace`:** A metadata string in YAML front matter used to classify the **artifact** (the file) within the Lupopedia semantic graph. It is used for validation policy, access control, and doctrinal grouping.
- **PHP `namespace`:** A language feature in PHP source code used to organize **classes and functions** into logical hierarchies to avoid name collisions.

**Rule:** The presence of a `namespace` in the headers does not imply or require a matching PHP namespace in the code, and vice versa.

| Aspect | Header Namespace | PHP Namespace |
|--------|------------------|---------------|
| **Format** | YAML key in `lupopedia.headers` block | PHP keyword at top of `.php` file |
| **Value** | Single lowercase word from taxonomy | Dotted or backslashed hierarchy |
| **Scope** | Artifact (file-level) metadata | Code (symbol-level) organization |
| **Authority** | LUPOPEDIA HEADERS doctrine | PHP language specification |

#### 2.1.2 Namespace Rules

| Aspect | Rule |
|--------|------|
| **Where it belongs** | In `lupopedia.headers` only. Do not move it into a separate block or invent a second namespace model. |
| **Table documentation** | **Required.** Every table documentation file (artifact_type consistent with table docs, e.g. under `lupo-docs/database/lupopedia/tables/`) MUST include `namespace` with a value from the approved taxonomy. |
| **Other artifact types** | **Optional until policy is defined.** For API docs, rule docs, skill docs, planning artifacts, and status reports, namespace is optional until explicit policy per artifact type is documented. Validators SHOULD warn when namespace is present on artifact types where policy is not yet defined; they MUST NOT treat absence as error for those types. |
| **Approved taxonomy (table docs)** | Values MUST be one of: `auth`, `channels`, `core`, `content`, `analytics`, `federation`, `governance`, `integration`, `legacy`. Use lowercase; no spaces. |
| **Naming style** | Single lowercase word (or approved value). No dotted paths in the header field. |
| **Single-value requirement** | Namespace is **single-valued**. Multiple namespace values in headers are drift to normalize, not a second model. Validators MUST reject multi-value namespace for table docs and warn for other types. |
| **Node-scoping** | Namespace is node-local. Cross-node or federation namespace mapping is out of scope for 4.0.78. |
| **Validation** | Validators MUST report missing namespace on table docs as error; invalid namespace value (not in taxonomy) as error; inappropriate placement (e.g. namespace on artifact types where policy says it must not appear) per doctrine. |
| **Precedence note** | When both collections and namespace are present and inform a decision, **policy and validation follow namespace**, while **navigation and display follow collections**. Namespace is *not* a navigation grouping mechanism; collections are. |

> Note on coexistence: artifacts may belong to multiple navigational sets via `collections` (many-to-many), while still having exactly one `namespace` value for jurisdiction/policy (single-valued). Single-value namespace does not contradict multi-set collections.

**Canonical block order:** When validating or exporting, enforce order: lupopedia.init → **lupopedia.routing** → lupopedia.actor_references → lupopedia.conditional → lupopedia.headers → lupopedia.metadata → lupopedia.session → lupopedia.edges → **lupopedia.engagement** → lupopedia.footer → lupopedia.see → **lupopedia.next_actions** (or legacy lupopedia.close) (same order for legacy lupopedia.init, flare.*, lupopedia.see, lupopedia.close). Optional blocks may be absent; if present, order MUST be correct. Session fields (session_id, session_name, etc.) belong in lupopedia.session, not in lupopedia.headers.

### 2.2 Session block (lupopedia.session)

Session information MUST be in a separate block **`lupopedia.session`**, not in `lupopedia.headers`. The block holds **runtime execution context** for the agent that produced or is acting on the artifact. The identity line’s `{session_name}` is resolved from `lupopedia.session.session_name`.

**Fields (when present):** `session_id`, `session_name`, `actor_name`, `actor_id`, `channel_id`, **`channel_name`** (optional), **`thread_id`** (optional), **`thread_name`** (optional), `federation_node_id`, `context_source`, `department_id`, `agent_name`, `actor_type`, `actor_nature`, `human_actor_name`, `paired_actor_id`. Optional: **`embedded_session_snapshot`** (see §2.2.2).

#### 2.2.1 Artifact metadata vs runtime state

| Block | Purpose |
|-------|--------|
| **lupopedia.headers** | Canonical artifact metadata (identity, **`version_when_written`** (historical authorship/baseline era), channel, purpose). |
| **lupopedia.session** | Runtime execution context (session, actor, faucet, environment). |

Agents SHOULD assume: **headers = artifact metadata**; **session = runtime context**. By default, **session state is read from the active runtime**, not from the file. Normally only **lupopedia.headers** is written into artifact files; runtime state comes from the active session or from the database.

#### 2.2.2 Where session state lives

- **Web-based agents (PHP runtime):** Session state is stored in the PHP `$_SESSION[]` array and initialized through the Lupopedia bootstrap.
- **IDE agents (Cursor, Windsurf, Antigravity, etc.):** Session state is stored in a **session file** in **`lupo-database/sessions/`**.

**Conflict governance (two session sources):**
- An execution context must treat **its active runtime session source** as authoritative.
  - IDE agents: authoritative = session file under `lupo-database/sessions/`.
  - PHP runtime agents: authoritative = `$_SESSION[]`.
- If an IDE agent *inspects* a PHP runtime session (for read-only understanding/debugging), it MUST NOT treat that inspected PHP session as the authoritative IDE session state, and MUST NOT merge/overwrite the IDE session file from it.
- When a session value from any non-active source must be persisted into artifacts, it must be expressed as an **explicit embedded snapshot** (e.g. via `lupopedia.session.embedded_session_snapshot: true`), never as an implicit merge of two live session stores.
- Embedded `lupopedia.session` blocks are read-only historical snapshots for auditing/debugging; they MUST NOT override the active runtime session.

**Session file naming convention:**

```text
L-LUPO-<ACTOR_NAME>_<ACTOR_FAUCET>_<UUID>.md
```

Example: `L-LUPO-CURSOR_DEV_3F6A9B2A.md`.

When an IDE agent starts execution it SHOULD:

1. Locate its session file.
2. Read the session file contents.
3. Load any `lupopedia.init` or runtime context instructions.

The session file MAY include: `lupopedia.init` instructions, environment configuration, federation routing data, actor pairing information, prior execution state.

#### 2.2.3 When the session block appears in a file

The **lupopedia.session** block may appear **inside an artifact file** only when the artifact was produced with **verbose output enabled**. In that case the session block documents the **runtime state of the agent that produced the artifact at the time the file was written**. This supports auditing and replay.

Optional flag to make this explicit:

```yaml
lupopedia.session:
  embedded_session_snapshot: true
```

Meaning: *this session block was captured at artifact creation time*. Use for deterministic auditing and multi-agent run debugging.

#### 2.2.4 Recommended canonical comment (lupopedia.session)

Implementations and documentation MAY use the following as the canonical description of the session block. In YAML, this can appear as a `comment` (or equivalent) to document the block for agents:

```yaml
lupopedia.session:
  comment: >
    Runtime session context for the executing agent.

    For web-based agents (PHP runtime), session state is stored in the
    PHP $_SESSION[] array and initialized through the Lupopedia bootstrap.

    For IDE agents (Cursor, Windsurf, Antigravity, etc.), session state
    is stored in a session file located in:

      lupo-database/sessions/

    The session file naming convention is:

      L-LUPO-<ACTOR_NAME>_<ACTOR_FAUCET>_<UUID>.md

    Example:
      L-LUPO-CURSOR_DEV_3F6A9B2A.md

    When an IDE agent starts execution it SHOULD:

      1. Locate its session file.
      2. Read the session file contents.
      3. Load any lupopedia.init or runtime context instructions.

    The session file may include:
      - lupopedia.init instructions
      - environment configuration
      - federation routing data
      - actor pairing information
      - prior execution state

    Normally only the lupopedia.headers block is written into artifact
    files. Runtime state is obtained from the active session or from the
    database.

    The lupopedia.session block may appear inside a file only when the
    artifact was produced with verbose output enabled. In this case the
    session block documents the runtime state of the agent that produced
    the artifact at the time the file was written.
```

#### 2.2.5 Edges and Engagement Snapshot Requirement (comment and meta)

Both **`lupopedia.edges`** and **`lupopedia.engagement`** MUST include a **`comment`** property stating that they are only a **snapshot** at artifact creation time (query the database for latest values). Both SHOULD include **`meta`** with a short thread/context description (e.g. version transition or workflow step). Use the same **`meta`** value in both blocks when present.

**Doctrinal placement:** For ordinary docs, do not include these blocks unless the artifact type explicitly requires them. For active table docs, `Lupopedia.edges` (often stored as `lupopedia.edges`) is required and should be verbose.

**For lupopedia.edges:** Use a single **`outbound_edges`** object. You may use either a **flat list** (legacy) or **grouped by category** (see §2.2.6).

**For lupopedia.engagement:**
```yaml
lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.74 finalization and initialization thread by CURSOR IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.74 → Migrate Tasks → Validate Upgrade Path"
  views: 0
  like_count: 0
  share_count: 0
```

#### 2.2.6 Grouped outbound_edges (code, documentation, schema, runtime)

**`outbound_edges`** MAY be structured in **grouped form** so that edges are categorized (e.g. code vs documentation). This is deterministic, machine-parsable, and maps cleanly to the database: each group key becomes **`edge_category`** in `lupo_edges` when edges are imported.

**Valid structure:** One **`outbound_edges`** object with **child keys** as category names; each value is a **list** of edge objects. YAML must not contain two separate `outbound_edges` keys at the same level.

**Standard categories (recommended):**

| Category | Use for |
|----------|--------|
| `code` | PHP, scripts, and other code files that reference the artifact (e.g. table docs → services, API, controllers). |
| `documentation` | Markdown, doctrine, README, how-to guides, related table docs. |
| `schema` | TOONs, install SQL, migration SQL (when added). |
| `runtime` | Config, env, or runtime-related paths (when added). |

**Grouped example:**

```yaml
lupopedia.edges:
  comment: "Snapshot of files related to lupo_collections at artifact creation. Separate runtime/code references from documentation references."
  meta: "Table doc: lupo_collections"

  outbound_edges:
    code:
      - { to: "lupo-database/lupopedia/content/lupo-app/Services/CollectionTabsService.php", type: "references", weight: 1.0 }
      - { to: "lupo-api/list_user_collections.php", type: "references", weight: 0.95 }
    documentation:
      - { to: "README.md", type: "documents", weight: 0.7 }
      - { to: "lupo-docs/doctrine/COLLECTIONS/COLLECTIONS_DOCTRINE.md", type: "documents", weight: 0.95 }
      - { to: "lupo-docs/database/lupopedia/tables/active/lupo_collection_tabs.md", type: "related_table", weight: 0.95 }

  semantic_tags: ["lupo_collections", "database_table", "php_references", "documentation_references", "collections"]
```

**Edge object fields:** `to` (required), `type` (e.g. `references`, `documents`, `related_table`, `schema_reference`), `weight` (0.0–1.0). Optional: `reason`, `db_source` (for FLARE compatibility).

**Backward compatibility:** A **flat** form remains valid: `outbound_edges: [ { to: "...", type: "...", weight: 0.9 }, ... ]` (a single list). Validators and import logic MUST accept both: if `outbound_edges` is an array with numeric keys, treat as flat; if it is an object with string keys (e.g. `code`, `documentation`), treat each key as the edge category and each value as the list of edges for that category. When exporting from the database, group by `lupo_edges.edge_category` to produce grouped YAML.

### 2.3 Routing block (lupopedia.routing)

The **`lupopedia.routing`** block (optional) provides routing and approval metadata for planning artifacts and cross-actor workflows. This is used for planning documents, architecture specifications, and artifacts that require actor coordination or approval workflows.

**Fields:**

| Field | Type | Purpose |
|-------|------|---------|
| `channel_id` | integer | Channel identifier for the routing context. |
| `actor_id` | integer | ID of the actor creating/initiating the artifact. |
| `actor_name` | string | Name of the actor creating/initiating the artifact. |
| `recipient_actor_ids` | array | List of actor IDs that should receive or review this artifact. |
| `recipient_actor_names` | array | Optional informational field. If present, it MUST match the canonical actor registry names for each `recipient_actor_id` (by index/association). Validators may warn and/or ignore mismatches; do not treat names as the authority (IDs are). |
| `session_id` | string | Session identifier for the workflow context. |
| `session_name` | string | Human-readable name for the session/workflow. |
| `priority` | string | Priority level (e.g., "high", "medium", "low"). |
| `requires_approval_from` | array | List of actor names whose approval is required before proceeding. |
| `next_status_on_approve` | string | Status to set when approval is granted. |
| `next_location_on_approve` | string | Target location/path for approved artifacts. |

**Example:**

```yaml
lupopedia.routing:
  channel_id: 42
  actor_id: 103
  actor_name: "antigravity"
  recipient_actor_ids: [1000]
  # Optional: recipient_actor_names is informational; IDs are the authority.
  recipient_actor_names: ["captain"]
  session_id: "L-LUPO-ANTIGRAVITY-PLANNING"
  session_name: "Bayesian Decision Tracking — Planning Phase"
  priority: "high"
  requires_approval_from: ["captain", "lilith"]
  next_status_on_approve: "approved-planning"
  next_location_on_approve: "docs/status/"
```

**Usage contexts:**
- Planning documents requiring multi-actor approval
- Architecture specifications that need review
- Cross-team coordination artifacts
- Workflow-driven documentation

### 2.4 Engagement block (lupopedia.engagement)

The **`lupopedia.engagement`** block (new in 4.0.74) tracks engagement metrics. Like **`lupopedia.edges`**, it is a snapshot and MUST have **`comment`** and SHOULD have **`meta`** (same convention as §2.2.5).

| Field | Type | Purpose |
|-------|------|---------|
| `comment` | string (required) | Snapshot notice; describe which agent/thread produced it (e.g. "Snapshot of files edited during 4.0.74 finalization … by CURSOR IDE Agent"). |
| `meta` | string (recommended) | Thread/context (e.g. "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.74 → …"). Same style as `lupopedia.edges.meta`. |
| `views` | integer | Total view count (calculated). |
| `like_count` | integer | Total like count. |
| `share_count` | integer | Total share count. |

**Example (collection / recent-files style):**

```yaml
lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.74 finalization and initialization thread by CURSOR IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.74 → Migrate Tasks → Validate Upgrade Path"
  views: 0
  like_count: 0
  share_count: 0
```


---

## 4. Version rule (4.0.84+)

- **New or modified** metadata-bearing Markdown from **4.0.84+** onward MUST use LUPOPEDIA HEADERS rules and the Version Semantics Model defined in **§1**.

Threshold summary:
- **Baseline rewrite-on-write enforcement** applies from **4.0.84+**
- **Existing FLARE-headed** files remain valid until migrated; validators MUST accept both during transition.
- Canonical storage is `lupo_metadata`; migration is incremental.

---

## 5. Database and channel resolution

Headers can be attached by:

- `entity_type` + `entity_id` (file- or object-scoped)
- `channel_id` (channel-scoped)
- Both when appropriate

Resolution and validators MUST support channel-aware lookup.

---

## 6. lupopedia.footer and required metadata (required when footer is present)

When a file includes a **`lupopedia.footer`** block, it MUST include **`orchestrator:`**, **`last_verified_by:`**, **`next_action:`**, and **`last_verified:`**. See required fields below.

Verification of an artifact involves auditing it against the **[VERIFICATION_GUIDE.md](./VERIFICATION_GUIDE.md)** to ensure doctrine compliance and ground truth accuracy.

**Required footer fields:**

- **`orchestrator:`** — Actor or delegation chain that orchestrated the last update (e.g. `"junie"`, `"wolfie:root"`).
- **`last_verified_by:`** — Actor or faucet that verified the artifact (e.g., `"junie"`, `"cursor"`). Verification implies a check against the `VERIFICATION_GUIDE.md`.
- **`next_action:`** — YAML list of 1–3 contextual, forward-looking strings; no version jumps beyond current release.
- **`last_verified:`** — REQUIRED when `lupopedia.footer` is present. Format: `YYYYMMDD`.

**Note:** Do not use a **`version`** key in `lupopedia.footer` for system or header schema version; read current system version from **`LUPEDIA_VERSION`** / runtime atoms when needed.

**Example (using Junie Actor 108):**

```yaml
lupopedia.footer:
  last_verified: "20260324"
  last_verified_by: "junie"
  orchestrator: "junie:root"
  next_action:
    - "Review related TOON definitions for schema alignment"
    - "Validate LUPOPEDIA HEADERS consistency across sibling files"
```

**Other optional footer fields:** `view_count`, `like_count`, `share_count` (engagement), `archive_note`. Required when `lupopedia.footer` is present: **`orchestrator:`**, **`last_verified_by:`**, **`next_action:`**, and **`last_verified:`**.
