---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "doctrine"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT"
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

- lupopedia.init
- lupopedia.conditional
- lupopedia.headers (required)
- lupopedia.session (optional; session context)
- lupopedia.edges (optional)
- lupopedia.engagement (optional; engagement metrics — see §2.2)
- lupopedia.footer (optional)
- lupopedia.see (optional)
- lupopedia.close (optional)

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

**Known channels (reference):** channel_id **42** = **Lupopedia Development (general)**. Other channel names come from `lupo_channels.channel_name` or project seed.

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
#### 2.1.5 Edges and Engagement Snapshot Requirement

Blocks like **`lupopedia.edges`** and **`lupopedia.engagement`** MUST include a `comment` or `meta` property stating that they are only a **snapshot** of the values at artifact creation time, and that the database should be queried to get the latest values.

**For lupopedia.edges:**
```yaml
lupopedia.edges:
  comment: "Snapshot of outbound edges at artifact creation. Query database for latest edge relationships and weights."
  outbound_edges: [...]
```

**For lupopedia.engagement:**
```yaml
lupopedia.engagement:
  comment: "Snapshot of engagement metrics at artifact creation. Query database for latest engagement data including views, likes, and shares."
  meta: "Context: Thread execution, file editing session, or specific operation that generated this engagement snapshot"
  views: 0
  like_count: 0
  share_count: 0
```

**Alternative meta property usage:**
```yaml
lupopedia.edges:
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges: [...]
```

### 2.2 Engagement block (lupopedia.engagement)

The **`lupopedia.engagement`** block (new in 4.0.73) tracks and calculates engagement metrics. Like `lupopedia.edges`, it is a snapshot of the database state.

| Field | Type | Purpose |
|-------|------|---------|
| `views` | integer | Total view count (calculated). |
| `likes` | integer | Total like count. |
| `shares` | integer | Total share count. |
| `comment` | string | Mandatory snapshot notice. |

**Example:**

```yaml
lupopedia.engagement:
  comment: "Snapshot of engagement metrics at artifact creation. Query database for latest engagement data including views, likes, and shares."
  meta: "Context: Thread execution, file editing session, or specific operation that generated this engagement snapshot"
  views: 124
  like_count: 12
  share_count: 3
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
