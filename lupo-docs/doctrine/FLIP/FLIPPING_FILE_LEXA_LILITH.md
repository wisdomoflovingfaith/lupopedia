# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\FLIPPING_FILE_LEXA_LILITH.md"
  file_hash: "3ac7805a8ab016e690cd764198ca6bc4eb434cdea782e89c29a4365d6c39fd61"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\doctrine\FLIP\FLIPPING_FILE_LEXA_LILITH.md"
  file_hash: "4b9ff2066bfe37fae681c3cd48d9f03276ed3e4516f0b7cf51b0d4614576b248"
  file_path_from_root: "docs\doctrine\FLIP\FLIPPING_FILE_LEXA_LILITH.md"
  file_hash: "cf953fb2e7e1f69c2890763fa443ff639289f60925a1d6ba74ad1c06402778ea"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLIPPING_FILE_LEXA_LILITH.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "flipping_file_lexa_lilithmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260217230000"
# channel_id unresolved — requires lupo_contents lookup by application.
dialog:
  speaker: ARA_GROK
  target: @cursor
  message: "Initialized 4.0.16: version bump from 4.0.15; global .md FLIP ingestion via seed, hybrid headers, doctrine on channels 0 and 51."
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md
---
# The FLIPPING File — FLP, FLIP Headers, and How Headers + Database Work (for LEXA and LILITH)

**Status:** Permanent.  
**Audience:** LEXA (boundary keeper, security), LILITH (heterodox reviewer, structural critic), and all AI agents.  
**Purpose:** Single canonical explanation of what FLP is, what FLIP/Wolfie Headers are, and how headers and the database are used so LEXA and LILITH can enforce and critique correctly.

---

## Part 1 — What Is What

### 1.1 FLIP (File-Level Inference Protocol)

**FLIP** = **F**ile-**L**evel **I**nference **P**rotocol.

- When a file is "flipped" to the system (e.g. handed to Cursor or any agent), the agent must **infer** everything about that file **only from its header** — no guessing, no hallucinating, no filling in from repo scan or external context.
- The **header** is the boundary and truth source for that file. Identity, lineage, channel, version, emotional state, doctrine, placement, meaning — all inferred from the header when present. If a field is absent, the agent must **not** invent it. Omission is information.

**Canonical FLIP doctrine:** `docs/doctrine/FLIP/FLIP_DOCTRINE.md`.

---

### 1.2 FLIP Headers (alias: Wolfie Headers, CROP Headers, FLIPPING Headers)

These are **the same thing**. One canonical name: **FLIP Headers**. Aliases: Wolfie Headers, CROP Headers, FLIPPING Headers. Same YAML block at the top of the file between `---` delimiters.

- **Signature line (mandatory):**  
  `wolfie.headers: explicit architecture with structured clarity for every file.`  
  This line never changes. Agents must not alter, reword, shorten, or "improve" it.

- **Doctrine-required fields for reconstruction and versioning:**
  - `file_path_from_root` — Path from repo root (e.g. `docs/doctrine/FLIP/FLIP_DOCTRINE.md`).
  - `file.last_modified_system_version` — System version when the file was last edited (e.g. `"4.0.16"`). Literal string, not an atom. Updated only when the file is modified.
  - `file.last_modified_utc` — UTC timestamp of last modification, 14-digit BIGINT format `YYYYMMDDHHIISS` (e.g. `"20260217000000"`).
  - `channel_id` — Optional; when resolvable from the database (e.g. via `lupo_edges`), can be included. Otherwise: comment `# channel_id unresolved — requires lupo_contents lookup by application.`

- **Optional fields (FLP enrichment; not for core inference):**
  - `mood_rgb` — 6-character hex string (e.g. `FF0000`, `6464FF`) per FLP_EMOTIONAL_GEOMETRY.md. Emotional state; may appear in dialog block or header. For dialog messages, stored in `lupo_dialog_messages.mood_rgb`; for header-only use, may be included in stored metadata per app convention.
  - `tags` — Array of strings (e.g. `["doctrine", "security"]`). Used for indexing, routing, or classification. When parsed by the loader, stored in `lupo_contents.tags` (JSON). Schema source of truth: `docs/toons/lupo_contents.toon.json`.
  - `atoms` — Key-value map for custom metadata (e.g. `GLOBAL_CURRENT_LUPOPEDIA_VERSION`). Resolved per atom specification. Storage per app convention; when the loader persists optional header metadata, it uses TOON-defined columns only (e.g. `tags` for tag arrays; see TOON).

**Full header specification (structure, optional blocks, dialog, tags, atoms):** `docs/channels/agents/WOLFIE_HEADER_SPECIFICATION.md`. Optionals not for core inference; stored in `lupo_contents.tags` (JSON) or `lupo_contents.dialog_notes` (text) per TOON and app convention when parsed.

---

### 1.3 FLP (Federated Likeness Protocol)

**FLP** = **F**ederated **L**ikeness **P**rotocol. This is **not** the same as FLIP.

- FLP is a **governance and cultural-coordination layer** that sits **on top of** Lupopedia. It does not replace it.
- FLP concepts: councils as channels, council members as actors, heterodox reviewers as application-level agents (e.g. LILITH-style), emotional geometry (MOOD_RGB), escrow/fund as channels + app-level logs. All realized with **existing** Lupopedia tables and **soft references**; **no** new schema, triggers, FKs, or DB-side automation for FLP.
- FLP is documented in this folder: `FLP_OVERVIEW.md`, `FLP_EMOTIONAL_GEOMETRY.md`, `FLP_COUNCILS_AS_CHANNELS.md`, `FLP_HETERODOX_REVIEWERS.md`, `FLP_EMOTIONAL_AGGREGATION.md`, `FLP_ESCROW_AND_FUND_LAYER.md`, `FLP_LUPOPEDIA_COUNCIL_SEAT.md`, `FLP_DOCTRINE_BOUNDARIES.md`.

**Summary:** FLIP = how we interpret **files** from their **headers**. FLP = how we model **councils, reviewers, and emotional coordination** on top of Lupopedia. Headers (FLIP) can carry FLP-relevant metadata (e.g. mood_RGB, channel context) but FLP does not define the header format; FLIP does.

---

### 1.4 Universal Agent Flipping

**Any agent** (kernel actors like LEXA/LILITH, external agents like Grok) can "flip" file headers — reconstruct or provide FLIP headers via inference or database resolution.

- **Local agents (Cursor, Windsurf, etc.):** Use `tools/generate_flip_header.py` with `--path`, `--url`, or `--content-id`. Output: full FLIP Header block. Use `--web` for JSON output compatible with the web API.
- **External agents (e.g. Grok browsing lupopedia.com):** Browse the web API endpoint, e.g. `GET {LUPOPEDIA_PUBLIC_PATH}/api/flip-header.php?path=docs/doctrine/FLIP/FLIP_DOCTRINE.md`. Returns JSON `{header: "...", resolved: true/false}` or raw YAML if `?format=yaml`. The header includes `channel_id` when resolvable from `lupo_edges`. Subdir-aware: use `LUPOPEDIA_PUBLIC_PATH` (e.g. `/lupopedia`) for installs in subdirectories.
- **Integration rule:** Agents infer **only** from the returned header. No guessing. The web API and generator both produce the same doctrine-required fields; external agents consume the YAML block and apply FLIP inference.

**Example (Grok):** Browse `https://lupopedia.com/lupopedia/api/flip-header.php?path=docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md` → receive JSON with full header → infer file identity and lineage from header only.

---

## Part 2 — How Headers and the Database Work Together

### 2.1 Database: FLIP fields in `lupo_contents`

The table **`lupo_contents`** stores content (e.g. ingested docs, articles). To support **full FLIP header reconstruction** from the database, the following columns exist (doctrine-required):

| Column | Type | Purpose |
|--------|------|---------|
| `file_path_from_root` | varchar(500) | Path from repo root; used when reconstructing header from path, URL, or content_id. |
| `file_last_modified_system_version` | varchar(20) | System version at last file edit (e.g. 4.0.16). |
| `file_last_modified_utc` | bigint | UTC last modified, YYYYMMDDHHIISS. |

- **No foreign keys, no triggers, no DB-side logic** for these columns. Application (or loader/generator) writes and reads them.
- **channel_id** is **not** stored on `lupo_contents`. It is resolved by the application via **`lupo_edges`**: edge type `HAS_CONTENT`, `left_object_type = 'channel'`, `right_object_type = 'content'`, `right_object_id = content_id` → `left_object_id` is the channel_id.

**Schema source of truth:** TOON files in `docs/toons/` (e.g. `lupo_contents.toon.json`). Install: `database/migrations/install_new_lupopedia.sql`. One-time migration for existing DBs: `database/migrations/20260217_add_missing_flip_fields.sql` (and earlier `20260217_add_flip_header_fields.sql` for `file_path_from_root` only).

---

### 2.2 Loader: `scripts/import_os.py`

The loader **ingests** Markdown files under `docs/` into `lupo_contents`.

- **Recognizes** FLIP Headers (and Wolfie/CROP/FLIPPING as same block) by signature or presence of `file_path_from_root` in the YAML block.
- **Reads** from the header when present: `file_path_from_root`, `file.last_modified_system_version`, `file.last_modified_utc`.
- **Writes** those into `lupo_contents` as `file_path_from_root`, `file_last_modified_system_version`, `file_last_modified_utc`. If the header omits path, the loader computes path from repo root and validates it.
- **Does not** infer or store `channel_id`; application resolves channel later via lupo_contents / lupo_edges lookup.
- **Creates** an edge in `lupo_edges` (channel HAS_CONTENT content) for the imported content; that is how `channel_id` becomes resolvable for that content.
- **Optional header fields:** When present, the loader may write tags to `lupo_contents.tags` (JSON) and dialog block to `lupo_contents.dialog_notes` (text), per TOON (`docs/toons/lupo_contents.toon.json`). No columns beyond those defined in the TOON.

So: **file on disk → header parsed → FLIP fields written to DB.** One direction. No schema or TOON changes from the loader.

---

### 2.3 Generator: `tools/generate_flip_header.py`

The generator **reconstructs** a full FLIP/Wolfie header **from** the database.

- **Input (one of):**
  - `--path` = `file_path_from_root`
  - `--url` = link address (content_url or custom_path)
  - `--content-id` = `content_id`
- **Behavior:** Queries `lupo_contents` with **parameterized SQL** to get the row; optionally queries `lupo_edges` to resolve `channel_id` for that content.
- **Output:** A verbose, human-readable FLIP Header block (YAML between `---`) containing:
  - `file_path_from_root`
  - `file.last_modified_system_version`
  - `file.last_modified_utc`
  - `channel_id` (if resolvable) or comment that it is unresolved

So: **DB (path / URL / content_id) → query → full header.** Other direction from the loader. Enables "give me the header for this path/URL/content_id" without reading the file from disk.

**Web API wrapper:** For external agents (e.g. Grok browsing lupopedia.com), the PHP endpoint `api/flip-header.php` wraps this logic: GET with `path`, `url`, or `content_id` returns JSON `{header: yaml_string, resolved: true/false}`. See `docs/api/FLIP_API.md`.

---

### 2.4 Round-trip summary

| Direction | Mechanism | Use case |
|----------|-----------|----------|
| File → DB | `import_os.py` reads FLIP header, writes FLIP columns into `lupo_contents` | Ingest docs; persist header-derived metadata. |
| DB → Header | `generate_flip_header.py` reads `lupo_contents` (and `lupo_edges` for channel_id), prints header block | Reconstruct header from path, URL, or content_id. |

Headers and database stay aligned when: (1) the loader runs on files that have FLIP Headers and (2) the generator is used to produce headers from DB. Edits to the file header should be reflected in the DB by re-running the loader or by application logic that updates the FLIP columns.

---

## Part 2.5 — Database tables and navigation: from content to channel_id and dialog

To get **all semantic information** for a file/content from the database — including **channel_id**, **channel details**, **dialog threads**, and **dialog messages** — use the tables and navigation below. Schema is from TOON files in `docs/toons/`; column names and types must match TOONs. Use **parameterized SQL only** (e.g. `:content_id`, `%s`); never concatenate values into SQL.

### Tables relevant to FLP/FLIP and content semantics

| Table | Role (TOON source: docs/toons/) |
|-------|----------------------------------|
| **lupo_contents** | Content row: FLIP columns (`file_path_from_root`, `file_last_modified_system_version`, `file_last_modified_utc`), `content_id`, `slug`, `title`, `body`, `content_url`, `custom_path`, `content_type`, **`dialog_notes`** (inline dialog/notes on this content), etc. |
| **lupo_edges** | Links content to channel. Edge type `HAS_CONTENT`: `left_object_type='channel'`, `left_object_id` = channel_id, `right_object_type='content'`, `right_object_id` = content_id. Also has `channel_id`, `channel_key` on the edge. |
| **lupo_channels** | Channel master: `channel_id`, `channel_key`, `channel_name`, `channel_slug`, `description`, `metadata_json`, `parent_channel_id`, etc. |
| **lupo_dialog_threads** | Threads scoped by channel: `dialog_thread_id`, `channel_id`, `project_slug`, `task_name`, `created_by_actor_id`, `summary_text`, `status`, `created_ymdhis`, `updated_ymdhis`, etc. |
| **lupo_dialog_messages** | Messages in a channel (and optionally a thread): `dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `message_text`, `message_type`, `message_body`, `mood_rgb`, `metadata_json`, `created_ymdhis`, etc. |
| **lupo_dialog_channels** | Dialog-specific metadata per channel: `channel_id` (PK), `channel_name`, **`file_source`** (path or identifier for the dialog file), `speaker`, `target`, `title`, `description`, `categories`, `collections`, `channels`, `tags`, `message_count`, etc. |

There is **no** `content_id` on `lupo_dialog_messages` or `lupo_dialog_threads`. The link from content to dialog is: **content → lupo_edges → channel_id → dialog threads and messages (by channel_id)**. Optional: **lupo_dialog_channels** links a channel to a `file_source` (e.g. which file the dialog came from), so you can also match by file path if that is stored there.

### Step-by-step: get content row

- **By file_path_from_root:**  
  `SELECT content_id, file_path_from_root, file_last_modified_system_version, file_last_modified_utc, slug, title, body, content_url, custom_path, content_type, dialog_notes, ... FROM lupo_contents WHERE file_path_from_root = :path AND is_deleted = 0 LIMIT 1`
- **By content_id:**  
  Same `SELECT ... FROM lupo_contents WHERE content_id = :content_id AND is_deleted = 0 LIMIT 1`
- **By URL (content_url or custom_path):**  
  `SELECT ... FROM lupo_contents WHERE (content_url = :url OR custom_path = :url) AND is_deleted = 0 LIMIT 1`

Use bound parameters for `:path`, `:content_id`, `:url`. From the row you get FLIP fields and **dialog_notes** (inline notes/dialog on this content).

### Step-by-step: get channel_id for a content

- **From lupo_edges (HAS_CONTENT):**  
  `SELECT left_object_id FROM lupo_edges WHERE left_object_type = 'channel' AND right_object_type = 'content' AND right_object_id = :content_id AND edge_type = 'HAS_CONTENT' AND is_deleted = 0 LIMIT 1`  
  → `left_object_id` is the **channel_id** for that content. (If the loader created the edge on import, this will exist for ingested content.)

### Step-by-step: get channel details

- **From lupo_channels:**  
  `SELECT channel_id, channel_key, channel_name, channel_slug, description, metadata_json, parent_channel_id, ... FROM lupo_channels WHERE channel_id = :channel_id AND is_deleted = 0 LIMIT 1`

### Step-by-step: get dialog threads for the channel

- **From lupo_dialog_threads:**  
  `SELECT dialog_thread_id, channel_id, project_slug, task_name, created_by_actor_id, summary_text, status, created_ymdhis, updated_ymdhis, ... FROM lupo_dialog_threads WHERE channel_id = :channel_id AND is_deleted = 0 ORDER BY created_ymdhis DESC`  
  → All threads in the channel this content belongs to.

### Step-by-step: get dialog messages (by channel or by thread)

- **All messages in the channel:**  
  `SELECT dialog_message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, message_body, mood_rgb, created_ymdhis, ... FROM lupo_dialog_messages WHERE channel_id = :channel_id AND is_deleted = 0 ORDER BY created_ymdhis`
- **Messages in a specific thread:**  
  `SELECT ... FROM lupo_dialog_messages WHERE dialog_thread_id = :dialog_thread_id AND is_deleted = 0 ORDER BY created_ymdhis`

### Step-by-step: get dialog-channel metadata (e.g. file_source)

- **From lupo_dialog_channels:**  
  `SELECT channel_id, channel_name, file_source, speaker, target, title, description, message_count, ... FROM lupo_dialog_channels WHERE channel_id = :channel_id LIMIT 1`  
  → **file_source** can identify the dialog file/path associated with this channel; use for matching content or file path to a dialog stream if your app populates it.

### Summary: full semantic load for one content

1. **Resolve content row** by `file_path_from_root`, `content_id`, or URL (content_url/custom_path) from **lupo_contents**. You now have FLIP fields and **dialog_notes**.
2. **Resolve channel_id** from **lupo_edges** (HAS_CONTENT, right_object_id = content_id) → left_object_id.
3. **Load channel** from **lupo_channels** by channel_id → channel_key, channel_name, etc.
4. **Load dialog threads** from **lupo_dialog_threads** WHERE channel_id = :channel_id.
5. **Load dialog messages** from **lupo_dialog_messages** WHERE channel_id = :channel_id (or WHERE dialog_thread_id IN (...)
 for selected threads).
6. **Optionally** load **lupo_dialog_channels** by channel_id for file_source, speaker, target, etc.

If you could not load the dialog thread or channel_id before, it is because: (a) **channel_id** is not on `lupo_contents` — it must be read from **lupo_edges**; (b) **dialog** is keyed by **channel_id** (and optionally **dialog_thread_id**), not by content_id. So the application must follow the path: content → edges → channel_id → dialog_threads / dialog_messages.

---

### Part 2.6 — Actors on a channel (LILITH-required)

To list **actors on the channel** that a content belongs to, use **lupo_actor_channels** and **lupo_actors** (and optionally **lupo_actor_channel_roles** for role). Schema from TOONs: `docs/toons/lupo_actor_channels.toon.json`, `docs/toons/lupo_actors.toon.json`, `docs/toons/lupo_actor_channel_roles.toon.json`.

1. Resolve **content_id** from lupo_contents (by file_path_from_root, content_id, or URL).
2. Resolve **channel_id** from lupo_edges (HAS_CONTENT, right_object_id = content_id) → left_object_id.
3. Query actors on that channel:

```sql
-- Actors on channel (parameterized: :channel_id)
SELECT a.actor_id, a.name, a.actor_type, ac.status, ac.start_date, ac.created_ymdhis
FROM lupo_actor_channels ac
JOIN lupo_actors a ON a.actor_id = ac.actor_id
WHERE ac.channel_id = :channel_id AND ac.is_deleted = 0 AND a.is_deleted = 0
ORDER BY ac.created_ymdhis;
```

Optional: include **role** from lupo_actor_channel_roles:

```sql
SELECT a.actor_id, a.name, a.actor_type, ac.status, ac.start_date, ac.created_ymdhis, acr.role_key
FROM lupo_actor_channels ac
JOIN lupo_actors a ON a.actor_id = ac.actor_id
LEFT JOIN lupo_actor_channel_roles acr ON acr.actor_id = ac.actor_id AND acr.channel_id = ac.channel_id AND acr.is_deleted = 0
WHERE ac.channel_id = :channel_id AND ac.is_deleted = 0 AND a.is_deleted = 0
ORDER BY ac.created_ymdhis;
```

Return shape (example): `array( array('actor_id' => ..., 'actor_name' => ..., 'type' => ..., 'role' => ..., 'status' => ..., 'joined_at' => ...), ... )`. Use **bound parameters** only; table prefix from LUPO_TABLE_PREFIX when in application code.

---

### Part 2.7 — dialog_notes: purpose and how to parse (LILITH-required)

**dialog_notes** on **lupo_contents** is a **text** column. Purpose: store inline dialog or notes associated with that content (e.g. agent messages, change notes, or a transcript snippet). It is **not** the same as **lupo_dialog_messages** (channel/thread messages). It is content-scoped.

- **How to parse:** Application-defined. There is no mandated format. Common patterns: plain text; YAML block; one message per line; or JSON. When reading, trim and interpret per your application’s convention. If absent or null, treat as empty. Do not guess structure; document the convention your app uses.
- **When to use:** When the application stores dialog or notes that belong to **this content row** (e.g. “last edit” note, or a short dialog block copied from the FLIP Header). For full channel/thread dialog, use **lupo_dialog_messages** and **lupo_dialog_threads** keyed by channel_id.

---

### Part 2.8 — FLP soft-reference example (LILITH-required)

FLP uses **soft references** only; no foreign keys. Example: **council as channel → council members as actors**.

- **Council** = one row in **lupo_channels** (e.g. channel_key = `council/main`, channel_name = "Main Council").
- **Membership** = rows in **lupo_actor_channels**: actor_id, channel_id (the council’s channel_id), status, start_date. No FK; application ensures actor_id and channel_id refer to existing rows.
- **Optional:** **lupo_edges** can represent “council HAS_MEMBER actor”: left_object_type = 'channel', left_object_id = council channel_id, right_object_type = 'actor', right_object_id = actor_id, edge_type = 'HAS_MEMBER'. Resolution: application reads lupo_edges (and lupo_actor_channels) to list members; no DB-level referential integrity.

All relationships are application-resolved; no schema or triggers added for FLP.

---

### Part 2.9 — Sample reconstructed FLIP header for this file (LILITH-required)

Output that **tools/generate_flip_header.py** (or equivalent) can produce for this file, given `file_path_from_root: docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md` and resolved channel_id (e.g. 0):

```yaml
---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
channel_id: 0
---
```

If channel_id cannot be resolved from lupo_edges, the last line is: `# channel_id unresolved — requires lupo_contents lookup by application.`

---

### Part 2.10 — Path validation pseudocode (LEXA/LILITH)

Path validation must ensure: (1) path is inside project root, (2) no `..` traversal. Pseudocode (implement in loader or resolver; use realpath and string checks):

```
function validate_path_inside_root(repo_root, filepath_abs):
    real_root = realpath(repo_root)
    real_path = realpath(filepath_abs)
    return (real_path == real_root) OR (real_path starts with real_root + path_separator)
end

function validate_and_sanitize_path_from_root(repo_root, path_from_root):
    if path_from_root is empty OR ".." in path_from_root: return None
    path_from_root = strip(path_from_root), replace backslash with forward slash, lstrip "/"
    if path_from_root is empty: return None
    resolved = normpath(join(repo_root, path_from_root))
    if NOT validate_path_inside_root(repo_root, resolved): return None
    return path_from_root with backslashes replaced by forward slashes
end
```

Only the **sanitized** result (or None) may be stored in the DB as `file_path_from_root`. Never store unvalidated user or header input.

**Implementation reference:** Path validation is implemented in `api/flip-header.php` (function `validate_path_from_root`) and in `scripts/import_os.py` (`validate_path_inside_root`, `validate_and_sanitize_path_from_root`). The web API must use the same logic for path params to prevent `..` escapes and root traversal.

---

### Part 2.11 — Seed, Crafty Syntax upgrade path, and channel 42 (LEXA/LILITH)

This section documents what is **seeded** in Lupopedia, the **only supported upgrade path** (Crafty Syntax 3.7.5 → Lupopedia 4.0.x), and the **channel 42** development seed that ties FLIP/FLP tables together.

#### Upgrade path (doctrine)

- **Only valid upgrade path:** **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**. There is **no** Lupopedia → Lupopedia upgrade in the 4.0.x series.
- **Install:** `database/migrations/install_new_lupopedia.sql` creates all required tables. **Seed:** `database/migrations/seed_lupopedia.sql` populates system channels, actors, kernel agents, modules, departments, and the **Lupopedia Development** channel (channel 42).
- **Importer:** `import_from_old_crafty_syntax.sql` (and wizard) migrate **from** an existing Crafty Syntax 3.7.5 database **into** a Lupopedia schema; seed runs after install to provide the Lupopedia-side bootstrap (channels, registry, actors, channel 42, dialog).

#### Tables and columns involved in FLIP, FLP, and seeding

All schema is from TOONs (`docs/toons/`) and install SQL. No inference from live DB.

| Table | Role | FLIP/FLP/Seed |
|-------|------|----------------|
| **lupo_contents** | Content and FLIP header persistence | **FLIP columns:** `file_path_from_root`, `file_last_modified_system_version`, `file_last_modified_utc`. Also `dialog_notes` (text), `tags` (JSON) for optional header fields. Loader writes FLIP columns; generator reads them. Schema: TOON. |
| **lupo_edges** | Links content to channel; channel_id resolution | **FLIP/FLP:** Edge type `HAS_CONTENT`: `left_object_type='channel'`, `left_object_id`=channel_id, `right_object_type='content'`, `right_object_id`=content_id. Loader creates HAS_CONTENT for ingested content. |
| **lupo_channels** | Channel master | **Seed:** channel_id 0 (System Kernel), channel_id 42 (Lupopedia Development). FLP: councils as channels. |
| **lupo_registry** | Registry of channels and agents (entity_type, entity_index, entity_key, is_kernel) | **Seed:** One row per channel (entity_type='channel', entity_index=channel_id), one per agent (entity_type in ('actor','agent'), entity_index=actor_id). Channel 42 has registry_id 60, entity_index 42. Kernel agents: is_kernel=1. |
| **lupo_actors** | Actor/agent master | **Seed:** System kernel actor 0; kernel agents 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,59,105,106,209,1200–1212; names from seed. LEXA (actor_id 24) boundary keeper. |
| **lupo_actor_channels** | Membership: actor on channel | **Seed:** (0,0) kernel; (1000–1024, actor_id, 42) for 25 kernel agents on channel 42. FLP: council membership. |
| **lupo_actor_channel_roles** | Role per actor per channel (e.g. admin) | **Seed:** actor_channel_role_id 2000–2024, channel_id 42, role_key='admin' for the same 25 agents. Used for channel admin rights. |
| **lupo_dialog_threads** | Threads per channel | **Seed:** dialog_thread_id 1, channel_id 42, project_slug 'lupopedia', task_name 'Lupopedia Development seed', status 'Open'. |
| **lupo_dialog_messages** | Messages in thread/channel | **Seed:** message_id 1–2 from actor 0 (system); 3–27 one per kernel agent; 28–31 FLIP/FLIPPING info and universal flipping API refs (28–29 FLIP path lookup; 30–31 web API). |
| **lupo_dialog_channels** | Dialog metadata per channel (file_source, message_count) | **Seed:** channel_id 42, channel_name 'Lupopedia Development', file_source 'docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md', message_count 31. |

Reserved ID doctrine applies: channels, actors, and registry-backed entities use **explicit IDs** in seed (no AUTO_INCREMENT for those tables in seed path). All timestamps in seed are BIGINT UTC YmdHis (`@now` in seed = 20260211000000 or as set at top of seed file).

#### Channel 42 — Lupopedia Development (seeded)

- **Purpose:** Single development channel that has "everything Crafty Syntax has inside Lupopedia": live chat, CRM, content, routing, agents, semantic OS. Used for development and integration.
- **lupo_channels:** One row `channel_id = 42`, `channel_key = 'lupopedia-development'`, `channel_name = 'Lupopedia Development'`, description and metadata_json. Idempotent: `ON DUPLICATE KEY UPDATE`.
- **lupo_registry:** One row `registry_id = 60`, `entity_type = 'channel'`, `entity_index = 42`, `entity_key = 'lupopedia-development'`. Kernel agents: rows for actor_id 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,209,1212 with `is_kernel = 1`.
- **lupo_actor_channels:** 25 rows (actor_channel_id 1000–1024) for kernel agents on channel 42: actor_id 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,209,1212.
- **lupo_actor_channel_roles:** 25 rows (actor_channel_role_id 2000–2024), each with `channel_id = 42`, `role_key = 'admin'`, so every AI agent with dialog on channel 42 has admin rights.
- **lupo_dialog_threads:** One row `dialog_thread_id = 1`, `channel_id = 42`.
- **lupo_dialog_messages:** 31 total: 1–2 from actor 0 (system); 3–27 one per kernel agent; 28–29 FLIP/FLIPPING info; 30–31 universal flipping API refs.
- **lupo_dialog_channels:** One row `channel_id = 42`, `file_source = 'docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md'`, `message_count = 31`.

Agent names for dialog messages and for application display come from **lupo_actors** seed (name column); the list of agents on channel 42 is defined by **lupo_actor_channels** seed for channel_id = 42, not guessed.

#### FLIP/FLP work done so far (summary)

- **FLIP doctrine and headers:** Canonical naming (FLIP Headers; aliases Wolfie, CROP, FLIPPING); inference from header only; doctrine in `docs/doctrine/FLIP/FLIP_DOCTRINE.md`.
- **DB columns for FLIP:** `lupo_contents.file_path_from_root`, `file_last_modified_system_version`, `file_last_modified_utc`; migrations `20260217_add_flip_header_fields.sql`, `20260217_add_missing_flip_fields.sql`.
- **Loader:** `scripts/import_os.py` — reads FLIP header from Markdown under `docs/`, writes FLIP columns to lupo_contents; parameterized SQL; path validation (inside repo root, no `..`); creates HAS_CONTENT edge; does not infer channel_id.
- **Generator:** `tools/generate_flip_header.py` — path/URL/content_id → query lupo_contents (and lupo_edges for channel_id) → output full FLIP header block.
- **ContentChannelActorResolver:** `lupo-includes/classes/ContentChannelActorResolver.php` — given file path, resolves content_id → channel_id via lupo_edges → actors via lupo_actor_channels + lupo_actors (optional lupo_actor_channel_roles for role_key).
- **FLP:** Governance layer (councils, reviewers, emotional geometry) using existing tables and soft references; no new schema.
- **Seed:** Channel 42, kernel agents on 42, one dialog message per agent, admin role per agent on 42; idempotent inserts with explicit IDs and ON DUPLICATE KEY UPDATE where appropriate. FLIP content (content_id 2001, 2002) with file_path_from_root for FLIPPING_FILE_LEXA_LILITH.md and FLIP_DOCTRINE.md; lupo_edges HAS_CONTENT (edge_id 900001, 900002) linking channel 42 to those contents. Dialog messages 28–31 with FLIP/FLIPPING info and universal flipping API refs. lupo_dialog_channels.file_source = FLIPPING path. message_count = 31.

---

### Part 2.12 — Optional dialog block in FLIP Headers (LILITH-required)

The **dialog** block in a FLIP Header is **optional** and **purely informational**. It is used for change notes, reviewer attribution, or conversational lineage (e.g. who made a change, to whom, and a short message). It is **not** used for inference.

- **Inference rule:** Agents must **not** infer identity, channel, version, or doctrine from the `dialog` block. Only the doctrine-required fields (`file_path_from_root`, `file.last_modified_system_version`, `file.last_modified_utc`; optional `channel_id`) are authoritative for FLIP inference. The dialog block is for human and agent readability and audit only.
- **Typical shape:** A YAML mapping with keys such as `speaker`, `target`, `message`, optionally `mood_rgb` (6-char hex, e.g. `00FF00`, per FLP_EMOTIONAL_GEOMETRY.md). Example (from this file's header):

  ```yaml
  dialog:
    speaker: ARA_GROK
    target: @cursor
    message: "Implemented Lilith's suggestions: Added Part 2.12 for optional dialog; ..."
  ```

- **Overlap with lupo_contents.dialog_notes:** The application may choose to store the dialog block (or a serialized form of it) in `lupo_contents.dialog_notes` when ingesting content (e.g. via the loader). Parsing and storage are application-defined; no mandated format. If stored, use plain text or a safe serialization (e.g. YAML string, JSON); **no eval, exec, or shell** on dialog content. See Part 2.7 for dialog_notes purpose and parsing.
- **Documentation:** Optional elements (including `dialog`) are documented in `docs/channels/agents/WOLFIE_HEADER_SPECIFICATION.md`. Per LILITH's recommendation: document the dialog block as optional and non-authoritative so agents do not treat it as inference input.

---

## Part 3 — For LEXA (Boundary Keeper / Security)

LEXA enforces doctrine and boundaries. For FLIP/FLP and headers + database, LEXA must enforce the following.

### 3.1 Security and loader (import_os.py)

- **Parameterized SQL only.** No string concatenation or interpolation of user/header values into SQL. All values passed as parameters (e.g. `%s` with tuple).
- **Path validation.** Any path from the header (`file_path_from_root`) or computed from the file system must be validated: must resolve **inside** the Lupopedia repo root; **no** `..` escape. Use `validate_path_inside_root` and `validate_and_sanitize_path_from_root` (or equivalent). Reject or skip invalid paths.
- **No eval, exec, or shell** on header values. Header values are plain text only; stored as-is. No dynamic execution.
- **Safe error logging.** No sensitive info (no passwords, no full stack with env). Short, bounded error messages (e.g. filename + first N chars of error).
- **No inference of channel_id in the loader.** Store path and FLIP fields only; application resolves channel via lupo_contents/lupo_edges lookup. Do not guess channel from path or filename.

### 3.2 FLIP inference rules

- **Infer only from the header.** Do not guess identity, channel, version, or doctrine from path, filename, or repo structure. If the header does not contain a field, do not invent it. Treat absence as absence.
- **Do not alter the header to "fix" inference** unless explicitly asked to update the file. FLIP is read-only inference; header edits are separate operations.

### 3.3 Database and schema

- **No schema inference from the live DB.** Schema comes from TOON files and install/migration SQL only.
- **No triggers, no FKs, no stored procedures** for FLIP/FLP. All behavior in application code.
- **Table prefix:** Use configured prefix (e.g. `LUPO_TABLE_PREFIX`); in migration files use placeholder `{prefix}` and replace before execution. In installer SQL use literal `lupo_`.

### 3.4 LEXA checklist (FLIP/FLP)

- [ ] Loader and any script writing to DB use parameterized SQL only.
- [ ] Paths from headers or filesystem are validated inside repo root; no `..`.
- [ ] No eval/exec/shell on header or path values.
- [ ] channel_id is not inferred in the loader; application resolves via lookup.
- [ ] Agents infer only from the header; no guessing missing fields.
- [ ] Schema changes come from TOONs and canonical migrations only; no live-DB inference.

---

## Part 4 — For LILITH (Heterodox Reviewer / Structural Critic)

LILITH is an application-level agent (heterodox reviewer). In the FLP, heterodox reviewers analyze council minutes, emotional state, and structure; they provide a distinct perspective and can challenge or complement the narrative. For FLIP/FLP and headers + database, LILITH should understand and critique the following.

### 4.1 Structural integrity

- **FLIP vs FLP.** FLIP = file-level inference from headers. FLP = governance layer on top of Lupopedia (councils, reviewers, emotional aggregation). Confusion between the two is a structural flaw. Docs and code should use "FLIP" for the header protocol and "FLP" for the federated likeness protocol.
- **Header naming.** Canonical: "FLIP Headers." Aliases: Wolfie, CROP, FLIPPING. Any doc or comment that treats "Wolfie Header" as the only name, or invents new names without tying them to FLIP, is drift.
- **Single source of truth.** FLIP doctrine lives in `docs/doctrine/FLIP/`. Schema lives in TOONs and install/migration SQL. No duplicate or suffixed FLIP doctrine files; no schema inferred from live DB.

### 4.2 Database and application boundary

- **FLP adds no schema.** Councils, heterodox reviewers, emotional aggregation, escrow/fund are realized with existing tables (channels, actors, content, edges) and soft references. If something introduces new tables or columns "for FLP" without going through TOON + install + migration doctrine, that is a violation.
- **FLIP columns in lupo_contents** are the only DB extension for headers: `file_path_from_root`, `file_last_modified_system_version`, `file_last_modified_utc`. No other header fields are required in the DB for doctrine-compliant reconstruction. Adding more columns "for headers" without doctrine requirement is scope creep.
- **channel_id** is resolved by application via `lupo_edges`, not stored on `lupo_contents`. Any design that stores channel_id on lupo_contents for "convenience" without doctrine approval is a structural choice to critique.

### 4.3 Loader and generator

- **Loader** must read only the FLIP fields that doctrine requires and write only those to the DB. It must not infer channel_id. It must use parameterized SQL and path validation (LEXA). If the loader writes extra columns or infers channel from path, LILITH should flag it.
- **Generator** must produce a header that matches the FLIP format (signature, file_path_from_root, file.last_modified_system_version, file.last_modified_utc, channel_id or comment). It must use parameterized SQL. If the generator invents fields not in doctrine or reads from non-TOON schema, LILITH should flag it.

### 4.4 LILITH critique checklist (FLIP/FLP)

- [ ] FLIP and FLP are clearly distinguished in docs and behavior.
- [ ] Header naming is canonical (FLIP Headers) with aliases stated.
- [ ] No FLP-specific schema; only existing Lupopedia tables and soft references.
- [ ] FLIP DB columns are only the three doctrine-required fields; no undisciplined expansion.
- [ ] Loader does not infer channel_id; generator resolves it via lupo_edges.
- [ ] LEXA rules (parameterized SQL, path validation, no guessing) are satisfied by loader and any script that touches DB or file paths.

---

## Part 5 — Quick Reference

| Term | Meaning |
|------|---------|
| **FLIP** | File-Level Inference Protocol: infer file identity/lineage/doctrine from header only; no guessing. |
| **FLIP Headers** | Canonical name for the YAML block at top of file (aliases: Wolfie, CROP, FLIPPING). |
| **FLP** | Federated Likeness Protocol: governance layer (councils, reviewers, emotional geometry) on top of Lupopedia; no new schema. |
| **Upgrade path** | **Only:** Crafty Syntax 3.7.5 → Lupopedia 4.0.x. No Lupopedia→Lupopedia in 4.0.x. Install + seed + importer. |
| **Seed file** | `database/migrations/seed_lupopedia.sql`. Seeds channels (0, 42), REGISTRY, actors, actor_channels, actor_channel_roles, dialog_threads, dialog_messages, dialog_channels; explicit IDs; idempotent where applicable. |
| **Channel 42** | Lupopedia Development. Seeded: lupo_channels, lupo_registry (entity_index 42), 25 kernel agents in lupo_actor_channels + lupo_actor_channel_roles (admin), including LEXA (actor_id 24). One dialog thread, 31 dialog messages (1–2 system, 3–27 kernel agents, 28–31 FLIP/API refs), lupo_dialog_channels (file_source FLIPPING path, message_count 31). FLIP content (lupo_contents 2001, 2002) with file_path_from_root; lupo_edges HAS_CONTENT to channel 42. Web API for universal flipping. |
| **lupo_contents FLIP columns** | `file_path_from_root`, `file_last_modified_system_version`, `file_last_modified_utc`. Also: `dialog_notes` (text), `tags` (JSON) per TOON for optional header fields. |
| **channel_id** | Not on lupo_contents. Resolved via **lupo_edges**: `left_object_id` WHERE right_object_type='content' AND right_object_id=content_id AND edge_type='HAS_CONTENT' AND is_deleted=0. |
| **Dialog threads** | **lupo_dialog_threads** WHERE channel_id = :channel_id (content → edges → channel_id first). |
| **Dialog messages** | **lupo_dialog_messages** WHERE channel_id = :channel_id or WHERE dialog_thread_id = :dialog_thread_id. |
| **Dialog channel metadata** | **lupo_dialog_channels** WHERE channel_id = :channel_id (file_source, speaker, target, message_count, etc.). |
| **Actors on channel** | **lupo_actor_channels** + **lupo_actors**; optional **lupo_actor_channel_roles** for role_key (e.g. admin). |
| **Optional dialog block** | In FLIP Header: optional YAML `dialog:` (e.g. speaker, target, message). Purely informational; not for inference. May be stored in lupo_contents.dialog_notes per app convention; no eval on content. See Part 2.12. |
| **Loader** | `scripts/import_os.py`: file → parse header → write FLIP fields to lupo_contents; parameterized SQL; path validation; optionally parses dialog block → dialog_notes. |
| **Generator** | `tools/generate_flip_header.py`: path/URL/content_id → query DB → output full FLIP header; optionally includes dialog_notes if present. Use `--web` for JSON output. |
| **mood_rgb / mood_RGB** | 6-character hex string (e.g. `6464FF`, `00FF00`) per FLP emotional geometry. See **docs/doctrine/FLIP/FLP_EMOTIONAL_GEOMETRY.md**. Dialog messages use `lupo_dialog_messages.mood_rgb`; optional header dialog may carry mood. |
| **Universal Flipping** | Any agent can flip via `generate_flip_header.py` (local) or `GET /api/flip-header.php?path=...` (web). Example: `?path=docs/doctrine/FLIP/FLIP_DOCTRINE.md` returns JSON with full header. |
| **Optional header fields** | `mood_rgb` (6-char hex), `tags` (array of strings), `atoms` (key-value map). FLP enrichment; not for core inference. Stored per TOON: e.g. tags in `lupo_contents.tags`, dialog in `dialog_notes`. See WOLFIE_HEADER_SPECIFICATION.md. |

---

## Part 6 — Universal Flipping Web API (LEXA/LILITH)

### Part 6.1 — API Specification

- **Endpoint:** `GET {LUPOPEDIA_PUBLIC_PATH}/api/flip-header.php` (subdir-aware; e.g. `/lupopedia/api/flip-header.php` for subdir installs).
- **Parameters:** `path` (string), `url` (string), `content_id` (int). **Precedence:** `path` > `url` > `content_id`. At least one required.
- **Output:** Default JSON `{header: "...", resolved: true/false, channel_id: ...}`. Use `?format=yaml` for raw YAML (`Content-Type: text/yaml`).
- **Example JSON response:**
  ```json
  {
    "header": "---\n# FLIP Header ...\nfile_path_from_root: docs/doctrine/FLIP/FLIP_DOCTRINE.md\n...\n---",
    "resolved": true,
    "channel_id": 42
  }
  ```
- **Example requests:** JSON: `GET /lupopedia/api/flip-header.php?path=docs/doctrine/FLIP/FLIP_DOCTRINE.md`; YAML: add `&format=yaml`.

### Part 6.2 — Security & Doctrine

- **Path validation:** Same as Part 2.10 — `validate_and_sanitize_path_from_root`; reject `..` and root escapes.
- **Url validation:** Must match `content_url` or `custom_path` in DB (parameterized lookup).
- **Content_id:** Must be numeric and exist in lupo_contents.
- **Parameterized SQL via PDO_DB.** No string concatenation. Subdir handling with `LUPOPEDIA_PUBLIC_PATH`.
- **Error codes:** 400 (invalid/missing params), 404 (not found), 500 (internal — log only, no client details).

### Part 6.3 — Future Authentication

- **API key:** Future versions may require API key; if added, store in a new `lupo_api_keys` table (requires TOON + migration approval).
- **Rate limiting:** Document as optional future to prevent abuse; no immediate implementation.

---

*End of FLIPPING File. LEXA enforces; LILITH critiques. No schema, no SQL in this document beyond reference to existing doctrine and artifacts.*