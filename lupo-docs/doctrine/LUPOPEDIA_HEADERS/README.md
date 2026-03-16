---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "doctrine"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
  web_path: "[web_path](http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS)"
  title: "LUPOPEDIA HEADERS"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "index"
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
---
# file: LUPOPEDIA HEADERS — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: "[web_path](http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS)"

# LUPOPEDIA HEADERS

**LUPOPEDIA HEADERS** (which has historical alias names such as **FLARE**, **FLIP**, **WOLFIE**, **FLP**, **FLPH**, **CROP**, and **FLAME**) are the canonical metadata protocol name from **4.0.68** onward. They **replace** the older header systems which are **deprecated**. See [DEPRECATION_FLARE_FLIP_FLP.md](./DEPRECATION_FLARE_FLIP_FLP.md). New and modified files must use LUPOPEDIA HEADERS; validators accept legacy `flare.*` / `flame.*` only for backward compatibility. Logical block structure and doctrinal lineage are preserved.

- **Storage:** `lupo_metadata` table "metadata" (with table prefix of "lupo_"), structured as rows (root → blocks → properties → edges/mappings/actions). No single YAML blob column; no dedicated presentation columns.
- **Schema additions:** Only `channel_id`, `parent_metadata_id`, `class_name`.
- **Format:** First line of file is `---`; then YAML blocks in canonical order; then `---`; then the identity line `# file: ...` as the first line of the body; then document content.

## Docs in this folder

| Document | Purpose |
|----------|---------|
| [LUPOPEDIA_HEADERS_PLAN.md](./LUPOPEDIA_HEADERS_PLAN.md) | Authoritative plan: schema, storage model, block order, channel support, version rule. |
| [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) | Markdown file structure and required header fields. |
| [LUPOPEDIA_HEADERS_MIGRATION.md](./LUPOPEDIA_HEADERS_MIGRATION.md) | Incremental migration from FLARE, validator and tooling expectations. |
| [DEPRECATION_FLARE_FLIP_FLP.md](./DEPRECATION_FLARE_FLIP_FLP.md) | Deprecation notice: FLARE, FLIP, FLP replaced by LUPOPEDIA HEADERS. |
| [OPTIONAL_BLOCKS.md](./OPTIONAL_BLOCKS.md) | Optional blocks: lupopedia.routing, lupopedia.lists, **lupopedia.next_actions** (suggested next actions; legacy: lupopedia.close). |

## Quick reference

- **First line of file:** `---` (nothing else on line 1 — no identity line, no heading). **Identity line** `# file: ...` goes **after** the closing `---`, as the first line of the body. See .cursor/rules/lupopedia-headers-file-order.mdc for mandatory order (all IDE agents).
- **Exactly one front matter block:** Do not duplicate; one opening `---`, one YAML block, one closing `---` per file. No second header block.
- **Then:** YAML blocks (canonical order) → `---` → identity line `# file: {title} — session: {session_name} — delegation: {delegation_chain} — web_path: {web_path}` → body
- **Session:** Session information belongs in **`lupopedia.session`**, not in `lupopedia.headers`. **Headers = artifact metadata**; **session = runtime execution context**. By default, agents read session from the **active runtime** (PHP `$_SESSION[]` or IDE session file in **`lupo-database/sessions/`**), not from the file. Session file naming: `L-LUPO-<ACTOR_NAME>_<ACTOR_FAUCET>_<UUID>.md` (e.g. `L-LUPO-CURSOR_DEV_3F6A9B2A.md`). Normally only `lupopedia.headers` is written into artifact files; when **verbose output** is enabled, `lupopedia.session` may be embedded as a snapshot at artifact creation time (optional flag `embedded_session_snapshot: true`). See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2.1 for full semantics and the canonical session comment.
- **Blocks (canonical from 4.0.69):** Use **lupopedia.*** names in new or modified files:
  - `lupopedia.init`, `lupopedia.conditional`, `lupopedia.headers`, **`lupopedia.session`**, `lupopedia.edges`, **`lupopedia.engagement`**, `lupopedia.footer`, `lupopedia.see`, **`lupopedia.next_actions`** (legacy: `lupopedia.close`)
- **Snapshots:** Blocks like **`lupopedia.edges`** and **`lupopedia.engagement`** MUST include a `comment` or `meta` property stating that they are only a **snapshot** of the values at artifact creation time, and that the database should be queried to get the latest values.
- **Engagement:** The **`lupopedia.engagement`** block (new in 4.0.74) calculates and displays engagement metrics such as `views: x`.
- **Deprecated:** `flare.*` and `flame.*` are accepted by validators for backward compatibility only; do not use for new files. See [DEPRECATION_FLARE_FLIP_FLP.md](./DEPRECATION_FLARE_FLIP_FLP.md).
- **Lookup:** by `entity_type` + `entity_id` and/or `channel_id`
- **Optional human-readable:** **`channel_name`** (with `channel_id`) and **`thread_name`** (with `thread_id` when thread-scoped). Example: channel_id 42 = "Lupopedia Development (general)". See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2 and [LUPOPEDIA_HEADERS_PLAN.md](./LUPOPEDIA_HEADERS_PLAN.md) §4.1.
