---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md"
  title: "LUPOPEDIA HEADERS Plan"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "plan"
---
# file: LUPOPEDIA HEADERS Plan — delegation: cursor:root — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md](http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md)

# LUPOPEDIA HEADERS — Plan (4.0.79)

**Status:** Authoritative design for 4.0.79  
**Canonical name:** LUPOPEDIA HEADERS (replaces FLARE, FLIP, FLP — all **deprecated** as of 4.0.71)  
**Logical structure:** Preserved from FLARE (block model and canonical order)  
**Storage:** `lupo_metadata` table "metadata" (with table prefix of "lupo_"), structured as rows, not a single YAML blob.  
**Optional blocks:** Routing and lists (from FLARE) are documented in [OPTIONAL_BLOCKS.md](./OPTIONAL_BLOCKS.md). See [DEPRECATION_FLARE_FLIP_FLP.md](./DEPRECATION_FLARE_FLIP_FLP.md) for deprecation notice.

---

## 1. Authoritative direction

- **LUPOPEDIA HEADERS** are the canonical metadata system name from **4.0.68** onward.
- **Canonical block names from 4.0.77:** Use `lupopedia.*` in new or modified files. Legacy `flare.*` and `flame.*` remain valid; validators accept both.
- Block naming rule (concept vs on-disk key):
  - `Lupopedia.*` = conceptual/doctrinal block names used in prose and rule descriptions.
  - `lupopedia.*` = the current serialized/validator-compatible YAML keys that appear in Markdown front matter.
- Canonical blocks (preferred in YAML):
  - `lupopedia.init`
  - `lupopedia.routing`
  - `lupopedia.conditional`
  - `lupopedia.headers`
  - `lupopedia.metadata` (optional; snapshot of metadata rows for this artifact — see OPTIONAL_BLOCKS; not table schema)
  - `lupopedia.session`
  - `lupopedia.edges`
  - `lupopedia.engagement` (new in 4.0.74)
  - `lupopedia.footer`
  - `lupopedia.see`
  - `lupopedia.next_actions` (legacy: `lupopedia.close`)

---

## 2. Schema: only three structural additions

`lupo_metadata` stays a **metadata property table**. The only new columns are:

| Column | Type | Purpose |
|--------|------|--------|
| `channel_id` | bigint DEFAULT NULL | Assign headers by channel (channel-level or channel-scoped metadata). |
| `parent_metadata_id` | bigint DEFAULT NULL | Hierarchy: root → blocks → properties → repeated structures. |
| `class_name` | varchar(128) DEFAULT NULL | Classify rows (e.g. lupopedia_header_root, lupopedia_block, lupopedia_property, lupopedia_edge, lupopedia_action, lupopedia_mapping). |

**Do not add** as first-class columns: `object_name`, `title`, `web_path`, `file_path_from_root`, `delegation_chain`, `header_yaml`. Session-related fields (`session_id`, `session_name`, and other session-file fields) are stored under the **`lupopedia.session`** block, not in `lupopedia.headers`. All other metadata are **properties** stored in rows using `property_key` / `property_value` (and optionally `meta_type`).

---

## 3. Storage model: structured rows

Headers are stored as a **graph/tree of metadata rows**, not one row per header with a YAML blob.

### 3.1 Root row

Each LUPOPEDIA header has a root row, e.g.:

- `class_name` = `'lupopedia_header_root'`
- `meta_type` = `'lupopedia_header'`
- `property_key` = `'__root__'`
- `property_value` = `'1'`

Attached by `entity_type` + `entity_id` and/or `channel_id`.

### 3.2 Block rows

Children of the root represent blocks. Use canonical `lupopedia.*` or legacy `flare.*`/`flame.*` in `property_key`:

- `parent_metadata_id` = root `metadata_id`
- `class_name` = `'lupopedia_block'`
- `meta_type` = `'block'`
- `property_key` = `'lupopedia.headers'` | `'lupopedia.session'` | `'lupopedia.edges'` | `'lupopedia.engagement'` | `'lupopedia.footer'` | `'lupopedia.init'` | `'lupopedia.see'` | `'lupopedia.next_actions'` (or legacy `'lupopedia.close'`) | `'lupopedia.conditional'`

### 3.3 Property rows

Under each block row, each field is a metadata row, e.g. under `lupopedia.headers` (or legacy `lupopedia.headers`):

- `property_key` = `'version_when_written'`, `'lupopedia.schema'`, `'file_path_from_root'`, `'web_path'`, `'content_id'` (when content-imported), `'actor_id'`, `'delegation_chain'`, `'purpose'`, `'title'`, **`channel_name`** (optional), **`thread_name`** (optional), etc. **Do not** store deprecated header version keys (`lupopedia.version`, `system_version`, `last_verified_system_version`, standalone `version`) under `lupopedia.headers`. Under **`lupopedia.session`**: `session_id`, `session_name`, `actor_id`, `actor_name`, `channel_id`, **`channel_name`** (optional), **`thread_id`** (optional), **`thread_name`** (optional), **`embedded_session_snapshot`** (optional; true when block was captured at artifact creation time), `federation_node_id`, `context_source`, `department_id`, `agent_name`, `actor_type`, `actor_nature`, `human_actor_name`, `paired_actor_id` (same as session file). Session = runtime context; headers = artifact metadata. Default: read session from active runtime; session in file only when verbose output embeds a snapshot. See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2.1.
- `property_value` = corresponding value

### 3.4 Repeating structures

Edges, mappings, actions (e.g. under `lupopedia.edges`, `lupopedia.see`, `lupopedia.init`, `lupopedia.next_actions` / `lupopedia.close`) are child rows with:

- `class_name` = `'lupopedia_edge'` | `'lupopedia_mapping'` | `'lupopedia_action'` | `'lupopedia_engagement'`

### 3.5 Export/Import Mapping Rules

This section defines the missing transformation layer between:

- serialized YAML front matter (keys like `lupopedia.headers`, `lupopedia.session`, ...)
- canonical structured rows in `lupo_metadata` (root -> blocks -> properties -> repeating child rows)

#### Block mapping (YAML block -> `lupopedia_block` row)

- Each YAML front matter block named `lupopedia.*` maps to a database block row with:
  - `class_name` = `'lupopedia_block'`
  - `meta_type` = `'block'`
  - `property_key` = the serialized YAML block name exactly (e.g. `'lupopedia.headers'`, `'lupopedia.session'`, `'lupopedia.engagement'`).

#### Property mapping (fields inside a block -> `lupopedia_property` rows)

- Each key/value field inside a YAML block maps to a database property row with:
  - `class_name` = `'lupopedia_property'`
  - `meta_type` = `'property'`
  - `property_key` = the YAML field name exactly (e.g. `version_when_written`, `file_path_from_root`, `embedded_session_snapshot`)
  - `property_value` = the YAML value (as represented by the YAML loader).

#### Repeating child-row mapping (edges/mappings/actions)

- YAML `lupopedia.edges` child rows map to `class_name` = `'lupopedia_edge'`.
- YAML `lupopedia.engagement` child rows map to `class_name` = `'lupopedia_engagement'`.
  - Naming transformation: dot -> underscore after the `lupopedia.` prefix:
    - `lupopedia.engagement` -> `lupopedia_engagement`
- YAML `lupopedia.next_actions` (legacy `lupopedia.close`) child rows map to `class_name` = `'lupopedia_action'`.

#### Exporter/importer determinism

- Exporters MUST read from DB rows, group by block (`property_key`), then emit YAML using `lupopedia.*` keys (never `Lupopedia.*` in serialized YAML).
- Importers MUST parse YAML blocks, create the corresponding block row + property rows deterministically, then populate repeating child rows according to the explicit `class_name` rules above.
- Importers MUST strip or ignore deprecated version keys inside `lupopedia.headers` and rely on baseline rewrite logic (`§2.0` / directives) for compliance restoration.

---

## 4. Canonical block order

Validators and YAML exporters MUST emit blocks in this order when present. Use **lupopedia.*** names in new files (4.0.69+):

1. `lupopedia.init`
2. `lupopedia.routing`
3. `lupopedia.actor_references`
4. `lupopedia.conditional`
5. `lupopedia.headers`
6. `lupopedia.metadata`
7. `lupopedia.session`
8. `lupopedia.edges`
9. `lupopedia.engagement`
10. `lupopedia.footer`
11. `lupopedia.see`
12. `lupopedia.next_actions` (legacy: `lupopedia.close`)

Optional blocks may be absent; if present, order is fixed. Validators accept both lupopedia.* and legacy flare.*/flame.*.

---

## 4.1 Possible header fields: channel and thread (optional)

In addition to `channel_id`, headers MAY include human-readable names for display and context:

| Field | Typical block | Purpose |
|-------|----------------|--------|
| `channel_id` | lupopedia.headers or lupopedia.session | Channel identifier (numeric). Required when channel-scoped. |
| `channel_name` | lupopedia.headers or lupopedia.session | Human-readable channel name (e.g. "Lupopedia Development (general)"). Optional. |
| `thread_id` | lupopedia.session | Thread identifier when the artifact is thread-scoped. Optional. |
| `thread_name` | lupopedia.headers or lupopedia.session | Human-readable thread name when available. Optional. |

**Known channel (reference):** channel_id **42** = **Lupopedia Development (general)**. Other names come from `lupo_channels.channel_name` or project seed.

---

## 5. Channel assignment

Header resolution MUST support:

- **Entity-scoped:** `entity_type` + `entity_id`
- **Channel-scoped:** `channel_id`
- **Combined:** entity + channel when appropriate

Lookup must allow loading metadata by channel, not only by entity.

---

## 6. Markdown file format

The **first line** of a LUPOPEDIA-headed Markdown file is `---`. Then YAML header blocks in canonical order, then `---`, then the identity line as the first line of the body, then the rest of the document:

Ordinary identity-line form (default; no `session:`):

```text
---
<yaml header blocks in canonical order>
---
# file: {title} — delegation: {delegation_chain} — web_path: {web_path}

<body content>
```

Verbose/session-snapshot identity-line form (alternative; only when intentionally embedding a verbose session snapshot):

```text
---
<yaml header blocks in canonical order>
---
# file: {title} — session: {session_name} — delegation: {delegation_chain} — web_path: {web_path}

<body content>
```

Use the ordinary identity line by default (no `session:`).

If a `lupopedia.session` block is present and you are intentionally embedding a verbose session snapshot, use the verbose form and take `{session_name}` from **`lupopedia.session.session_name`**. Session = runtime execution context; by default agents read session from active runtime (PHP `$_SESSION[]` or IDE session file in **lupo-database/sessions/**). Session file naming: `L-LUPO-<ACTOR_NAME>_<ACTOR_FAUCET>_<UUID>.md`. Session block in a file = only when verbose output embeds a snapshot (`embedded_session_snapshot: true`). So: **`---` first**, then YAML (including `lupopedia.session` only when intentionally embedding a verbose session snapshot), then `---`, then identity line, then body.

---

## 7. Version and migration rule (4.0.69)

- **From 4.0.69+:** New or modified metadata-bearing Markdown MUST use LUPOPEDIA HEADERS rules.
- **Existing FLARE-headed artifacts** remain valid until migrated.
- **Validators** MUST accept both legacy FLARE artifacts and 4.0.69+ LUPOPEDIA-backed artifacts during transition.
- **Canonical storage** is `lupo_metadata`; migration is **incremental**, not an instant cutover.

---

## 8. Required header fields (as properties)

**Required** under `lupopedia.headers` (minimum):

- **`version_when_written`**
  - Initial write: set to the system version when the header generation is first written.
  - Pre-4.0.84 artifacts: mandatory baseline rewrite on write may restamp `version_when_written` to the current system version.
  - After baseline compliance: treat it as stable for normal edits; do not bump it on every touch.
- **`file_path_from_root`**

**Conditional:** **`content_id`** when the artifact is imported into `lupo_content` or otherwise database-managed as content (usually not handwritten).

**Optional** (examples): `lupopedia.schema`, `web_path`, `last_modified_utc`, `channel_id`, `actor_id`, `delegation_chain`, `artifact_type`, `artifact_kind`, `purpose`, `tags`, and other fields per [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2.

For **table documentation**, **`namespace`** is also required (approved taxonomy: auth, channels, core, content, analytics, federation, governance, integration, legacy). See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2.2.

**Deprecated / do not use in `lupopedia.headers`:** `lupopedia.version`, `system_version`, `last_verified_system_version`, standalone `version`. See [VERSIONING_MODEL.md](./VERSIONING_MODEL.md) (obsolete stub) and LUPOPEDIA_HEADERS_FORMAT.md §2.

Optional but supported: `actor_name`, `mood_rgb`, `traits`, `tags`, `lupo_agent`, `agent_name_identity`, **`channel_name`** (human-readable channel name), **`thread_name`** (human-readable thread name when thread-scoped), **`namespace`** (when not required for artifact type).

Session fields (`session_id`, `session_name`, `channel_id`, `channel_name`, `thread_id`, `thread_name`, and other session-file fields) belong in **`lupopedia.session`** when used for session context. Header/session mixing is not the default doctrine.

---

## 9. What not to do

- Do **not** add a large `header_yaml` column or many dedicated presentation columns as the primary design.
- Do **not** treat one full header as one single-row YAML blob in the DB.
- Do **not** claim that all files are instantly migrated; document incremental migration.
- Rendered YAML in files is an **export artifact**; the canonical data model is structured metadata rows.

---

## 10. Reference

- FLARE logical structure and block semantics: [FLARE_DOCTRINE.md](../FLARE/FLARE_DOCTRINE.md)
- Format and required header fields (including **`version_when_written` only**): [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md)
- Migration path: [LUPOPEDIA_HEADERS_MIGRATION.md](./LUPOPEDIA_HEADERS_MIGRATION.md)
- Historical / obsolete versioning stub (do not treat as active doctrine): [VERSIONING_MODEL.md](./VERSIONING_MODEL.md)
- Baseline rewrite on write (4.0.84+): [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2.0; [lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md](../../../lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md); [directives.md](../../../directives.md)
