---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "doctrine"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md"
  web_path: "[web_path](http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT)"
  title: "LUPOPEDIA HEADERS Format"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 1003
  actor_name: "cursor"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  context_source: "default"
  department_id: 0
  thread_id: 0
  agent_name: "cursor"
  actor_type: "agent"
  actor_nature: "ide"
  human_actor_name: "root"
  paired_actor_id: 10000
lupopedia.footer:
  version: "4.0.72"
  last_verified: "20260312"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Apply next_action and orchestrator to all files with lupopedia.footer"
    - "Validate LUPOPEDIA HEADERS consistency across doctrine files"
    - "Update FLARE_HEADERS_COMPLETE_REFERENCE footer example with orchestrator"
---
# file: LUPOPEDIA HEADERS Format — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT

# LUPOPEDIA HEADERS — File format and version rule

**Version:** 4.0.69+  
**Canonical name:** LUPOPEDIA HEADERS (FLARE = historical logical structure)

---

## 1. Markdown file structure

The **first line** of the file MUST be:

```text
---
```

**DO NOT** put the identity line (`# file: ...`) or any heading on line 1. The identity line belongs **after** the closing `---` of the YAML block, as the first line of the body. Putting it before the opening `---` is invalid and will be rejected by validators.

**DO NOT** duplicate the header. There must be **exactly one** YAML front matter block per file: one opening `---`, one set of blocks (lupopedia.headers, etc.), one closing `---`. Never add a second `---` … YAML … `---` block elsewhere in the file. When merging or updating headers, consolidate into a single block and remove any duplicate.

Then the YAML header blocks in **canonical order** (see [LUPOPEDIA_HEADERS_PLAN.md](./LUPOPEDIA_HEADERS_PLAN.md) §4). Use **lupopedia.*** block names in new or modified files (4.0.69+); validators accept legacy flare.*/flame.*:

- **lupopedia.init** — Optional. Lists **required reading** and **required context** that must be read or understood **before** reading this file (e.g. `required_reading:`, `required_context:`). It is **not** for file metadata; use `lupopedia.headers` or `lupopedia.metadata` for artifact_type, file_identity, namespace, domain, system_version. See [LUPO_INITIALIZATION_DOCTRINE.md](../init/LUPO_INITIALIZATION_DOCTRINE.md). Supported forms: simple list of paths, or list of `path`/`reason` objects.
- **lupopedia.routing** — Optional. Routing and approval metadata for planning artifacts and cross-actor workflows. Includes channel, actor, recipient, session, priority, and approval requirements.
- **lupopedia.actor_references** — Optional. Actor IDs from canonical registry (plan/report files). See [OPTIONAL_BLOCKS.md](./OPTIONAL_BLOCKS.md).
- lupopedia.conditional
- lupopedia.headers (required)
- lupopedia.metadata (optional; snapshot of metadata rows for this artifact — see OPTIONAL_BLOCKS; not table schema)
- lupopedia.session (optional; session context)
- lupopedia.edges (optional)
- lupopedia.engagement (optional; engagement metrics — see §2.2)
- lupopedia.footer (optional)
- lupopedia.see (optional)
- **lupopedia.next_actions** (optional) — Suggested next actions after reading or using this file (like **lupopedia.init** but for follow-ups). Use **lupopedia.next_actions** in new files; **lupopedia.close** is the legacy name (validators accept both). See [OPTIONAL_BLOCKS.md](./OPTIONAL_BLOCKS.md) § lupopedia.next_actions.

Then the closing delimiter:

```text
---
```

Then the **identity line** as the first line of the body:

```text
# file: {title} — session: {session_name} — delegation: {delegation_chain} — web_path: {web_path}
```

The `{session_name}` in the identity line MUST be taken from **`lupopedia.session.session_name`** (when a `lupopedia.session` block is present). Then the rest of the document body.

---

## 2. Required header fields (in lupopedia.headers)

Stored as metadata properties (or in YAML when written to file). Use **lupopedia.*** keys in new files; validators accept legacy `lupopedia.version` / `lupopedia.schema`. Minimum: `lupopedia.version`, `lupopedia.schema`, `file_path_from_root`, `web_path`, `last_modified_utc`, `system_version`, `channel_id`, `actor_id`, `delegation_chain`, `artifact_type`, `artifact_kind`, `purpose`. Optional: `actor_name`, `mood_rgb`, `traits`, `tags`, `lupo_agent`, `agent_name_identity`. **Session-related fields** (e.g. `session_id`, `session_name`) belong in **`lupopedia.session`**, not in `lupopedia.headers`.

**Optional channel and thread (human-readable):** In addition to `channel_id`, headers MAY include **`channel_name`** — a human-readable name for the channel (e.g. `"Lupopedia Development (general)"` for channel_id 42). When the artifact is scoped to a specific thread, **`thread_name`** MAY be included (human-readable thread name) alongside `thread_id` (which may appear in `lupopedia.session`). These aid display and context without changing resolution logic.

| Field | Block | Purpose |
|-------|--------|--------|
| `channel_id` | lupopedia.headers or lupopedia.session | Channel identifier (numeric). |
| `channel_name` | lupopedia.headers or lupopedia.session | Human-readable channel name (optional). |
| `thread_id` | lupopedia.session | Thread identifier when artifact is thread-scoped (optional). |
| `thread_name` | lupopedia.headers or lupopedia.session | Human-readable thread name when available (optional). |

**Canonical block order:** When validating or exporting, enforce order: lupopedia.init → **lupopedia.routing** → lupopedia.conditional → lupopedia.headers → **lupopedia.session** → lupopedia.edges → **lupopedia.engagement** → lupopedia.footer → lupopedia.see → **lupopedia.next_actions** (or legacy lupopedia.close) (same order for legacy lupopedia.init, flare.*, lupopedia.see, lupopedia.close). Optional blocks may be absent; if present, order MUST be correct. Session fields (session_id, session_name, etc.) belong in lupopedia.session, not in lupopedia.headers.

### 2.1 Session block (lupopedia.session)

Session information MUST be in a separate block **`lupopedia.session`**, not in `lupopedia.headers`. The block holds **runtime execution context** for the agent that produced or is acting on the artifact. The identity line’s `{session_name}` is resolved from `lupopedia.session.session_name`.

**Fields (when present):** `session_id`, `session_name`, `actor_name`, `actor_id`, `channel_id`, **`channel_name`** (optional), **`thread_id`** (optional), **`thread_name`** (optional), `federation_node_id`, `context_source`, `department_id`, `agent_name`, `actor_type`, `actor_nature`, `human_actor_name`, `paired_actor_id`. Optional: **`embedded_session_snapshot`** (see §2.1.2).

#### 2.1.1 Artifact metadata vs runtime state

| Block | Purpose |
|-------|--------|
| **lupopedia.headers** | Canonical artifact metadata (identity, version, channel, purpose). |
| **lupopedia.session** | Runtime execution context (session, actor, faucet, environment). |

Agents SHOULD assume: **headers = artifact metadata**; **session = runtime context**. By default, **session state is read from the active runtime**, not from the file. Normally only **lupopedia.headers** is written into artifact files; runtime state comes from the active session or from the database.

#### 2.1.2 Where session state lives

- **Web-based agents (PHP runtime):** Session state is stored in the PHP `$_SESSION[]` array and initialized through the Lupopedia bootstrap.
- **IDE agents (Cursor, Windsurf, Antigravity, etc.):** Session state is stored in a **session file** in **`lupo-database/sessions/`**.

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

#### 2.1.3 When the session block appears in a file

The **lupopedia.session** block may appear **inside an artifact file** only when the artifact was produced with **verbose output enabled**. In that case the session block documents the **runtime state of the agent that produced the artifact at the time the file was written**. This supports auditing and replay.

Optional flag to make this explicit:

```yaml
lupopedia.session:
  embedded_session_snapshot: true
```

Meaning: *this session block was captured at artifact creation time*. Use for deterministic auditing and multi-agent run debugging.

#### 2.1.4 Recommended canonical comment (lupopedia.session)

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
#### 2.1.5 Edges and Engagement Snapshot Requirement (comment and meta)

Both **`lupopedia.edges`** and **`lupopedia.engagement`** MUST include a **`comment`** property stating that they are only a **snapshot** at artifact creation time (query the database for latest values). Both SHOULD include **`meta`** with a short thread/context description (e.g. version transition or workflow step). Use the same **`meta`** value in both blocks when present.

**For lupopedia.edges:** Use a single **`outbound_edges`** object. You may use either a **flat list** (legacy) or **grouped by category** (see §2.1.6).

**For lupopedia.engagement:**
```yaml
lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.74 finalization and initialization thread by CURSOR IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.74 → Migrate Tasks → Validate Upgrade Path"
  views: 0
  like_count: 0
  share_count: 0
```

#### 2.1.6 Grouped outbound_edges (code, documentation, schema, runtime)

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
| `recipient_actor_names` | array | List of actor names corresponding to recipient_actor_ids. |
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

The **`lupopedia.engagement`** block (new in 4.0.74) tracks engagement metrics. Like **`lupopedia.edges`**, it is a snapshot and MUST have **`comment`** and SHOULD have **`meta`** (same convention as §2.1.5).

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

## 3. Version rule (4.0.68)

- **New or modified** metadata-bearing Markdown from 4.0.68 onward MUST use LUPOPEDIA HEADERS rules and this format.
- **Existing FLARE-headed** files remain valid until migrated; validators MUST accept both during transition.
- Canonical storage is `lupo_metadata`; migration is incremental.

---

## 4. Database and channel resolution

Headers can be attached by:

- `entity_type` + `entity_id` (file- or object-scoped)
- `channel_id` (channel-scoped)
- Both when appropriate

Resolution and validators MUST support channel-aware lookup.

---

## 5. lupopedia.footer and required metadata (required when footer is present)

When a file includes a **`lupopedia.footer`** block, it MUST include **`orchestrator:`**, **`last_verified_by:`**, and **`next_action:`**, plus at least one of `version:` or `last_verified:`. See required fields below.

**Required footer fields:**

- **`orchestrator:`** — Actor or delegation chain that orchestrated the last update (e.g. `"cursor"`, `"wolfie:root"`).
- **`last_verified_by:`** — Actor or faucet that verified the artifact.
- **`next_action:`** — YAML list of 1–3 contextual, forward-looking strings; no version jumps beyond current release (e.g. 4.0.72).

**Example:**

```yaml
lupopedia.footer:
  version: "4.0.72"
  last_verified: "20260312"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Review related TOON definitions for schema alignment"
    - "Validate LUPOPEDIA HEADERS consistency across sibling files"
    - "Update documentation to reflect new schema changes"
```

**Other optional footer fields:** `view_count`, `like_count`, `share_count` (engagement), `archive_note`. Required when footer is present: **`orchestrator:`**, **`last_verified_by:`**, **`next_action:`**, plus at least one of `last_verified` or `version`.
