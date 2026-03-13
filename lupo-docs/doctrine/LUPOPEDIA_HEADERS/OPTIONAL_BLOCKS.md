---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS"
  system_version: "4.0.71"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1003
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "Optional LUPOPEDIA HEADERS blocks: routing and lists (carried over from FLARE)."
  tags: ["lupopedia_headers", "routing", "lists", "optional", "4.0.71"]
lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
  next_action:
    - "Use lupopedia.routing / lupopedia.lists when adding new header blocks"
    - "Validate optional blocks against LUPOPEDIA_HEADERS_FORMAT §5"
---
# file: Optional blocks — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS

# LUPOPEDIA HEADERS — Optional blocks (routing, lists)

Functionality that existed in the deprecated **FLARE** system is fully supported in LUPOPEDIA HEADERS. The following optional blocks may be used when needed; they are not required for every file.

---

## lupopedia.routing (optional)

Tracks lifecycle, delivery, and delegation of artifacts across the multi-agent ecosystem. Use **`lupopedia.routing`** in YAML (legacy name was `flare.routing`).

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| to | array | Primary recipients (actor_ids or slugs) | `["all", "captain"]` |
| from | integer/string | Originating actor_id or slug | `1006` |
| forwarded_from | integer/string | Original sender if rebroadcast | `1004` |
| delegation_chain | array | Sequence of authority (Root → Higher → Executor) | `[1, 10000, 1006]` |
| channel_id | integer | Target channel ID | `42` |
| thread_id | mixed | Discussion thread ID | `"DEVELOPMENT_CYCLE_4_0_55"` |
| read_by | array | Actors who have acknowledged the message | `[1006, 10000]` |
| routing_path | array | Logical or physical directories traversed | `["lupo-channels/42/threads/"]` |

**Canonical order:** If present, place `lupopedia.routing` after `lupopedia.headers` and before `lupopedia.session` (or per LUPOPEDIA_HEADERS_PLAN block order).

---

## lupopedia.lists (optional)

Links to external CSV-based history and discussion records. Use **`lupopedia.lists`** in YAML (legacy name was `flare.lists`).

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| file.dialog | string | Path to discussion CSV | `"thread_dialog.csv"` |
| file.history | string | Path to change history CSV | `"thread_history.csv"` |
| file.actors | string | Path to actors list CSV | `"thread_actors.csv"` |

**Canonical order:** If present, place after `lupopedia.edges` or before `lupopedia.footer` as documented in LUPOPEDIA_HEADERS_PLAN.

---

## lupopedia.metadata — Snapshot of metadata rows (optional)

**`lupopedia.metadata`** is **not** a table-schema block. It is a **snapshot of actual metadata content** — the serialized view of rows stored in the **`lupo_metadata`** table for the current file or entity. The database is the storage layer; the header block is the artifact view for transfer into/out of the database.

**Correct meaning:**
- **lupopedia.metadata** = snapshot of metadata **rows/values** for this artifact or entity.
- Content is **grouped by `property_key`**; each key maps to an array of row-like entries.
- Do **not** list column names or SQL datatypes (that belongs in table docs, TOONs, install SQL).

**Required:** A **`comment`** field so the block is clearly understood as a snapshot (e.g. `"Snapshot of metadata for this file or entity at artifact creation."`).

**When no metadata rows exist yet:** Use only the comment. Do not invent or fake metadata.

**Canonical structure** (when rows exist):

```yaml
lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  <property_key>:
    - {
        domain_id: <value>,
        schema_ref: "lupo_metadata",
        entity_type: "<entity_type>",
        entity_id: <entity_id>,
        meta_type: "<meta_type>",
        property_value: "<property_value>",
        channel_id: <value>,
        parent_metadata_id: <value or null>,
        class_name: "<class_name>",
        created_ymdhis: <value>,
        updated_ymdhis: <value>
      }
  # additional property_key keys with arrays of row-like objects as needed
```

**Transferability:** Rows in `lupo_metadata` can be exported into `lupopedia.metadata`; `lupopedia.metadata` can be re-imported into `lupo_metadata`. The file is a deterministic snapshot; the database remains the authority for current state.

**Wrong (do not use):** Listing column names and SQL types under `lupopedia.metadata` (e.g. `metadata_id: "bigint"`, `property_key: "varchar(255)"`). That describes the **table schema**, not metadata **content**.

**Common property_key values:** For repository-core and documentation artifacts, these keys are commonly used so that metadata is consistent and transferable:

| property_key   | Description |
|----------------|-------------|
| title          | Human-readable title of the artifact (e.g. "Lupopedia README", "Lupopedia CHANGELOG"). |
| description    | Short description or purpose (e.g. purpose text, one-line summary). |
| keywords       | Comma-separated or array of keywords (e.g. from tags; used for search and discovery). |
| author         | Primary author or owner (actor name or slug, e.g. "wolfie"). |
| orchestrator   | Actor or faucet that orchestrated the last update (e.g. "cursor", "windsurf"). |

Example with common keys (row-like entries; omit or use placeholders for `entity_id` / timestamps when not yet synced from DB):

```yaml
lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Lupopedia README", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Primary project documentation and onboarding.", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  keywords:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "readme, getting_started, semantic_os, multi_agent, v4.0.73", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "wolfie", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
```

---

## lupopedia.footer — next_action (required) and metadata

When **`lupopedia.footer`** is present, it MUST include **`next_action:`** — a list of 1–3 suggested next actions (contextual, forward-looking). See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §5.

In addition, footer MUST include orchestrator and verification metadata. Engagement fields have been migrated to the specialized **`lupopedia.engagement`** block.

| Field | Type | Description |
|-------|------|-------------|
| next_action | list (required) | 1–3 suggested next actions; contextual and forward-looking |
| orchestrator | string (required) | Actor or delegation chain that orchestrated the last update |
| last_verified | string | Last verification date (YYYYMMDD) |
| last_verified_by | string (required) | Actor or faucet who verified |

---

## lupopedia.engagement — Snapshot and metrics (optional)

New block (4.0.73) for tracking artifact engagement. Like **`lupopedia.edges`**, it MUST include **`comment`** and SHOULD include **`meta`** (same convention as edges: comment = snapshot notice, meta = thread/context string).

| Field | Type | Description |
|-------|------|-------------|
| comment | string (required) | Snapshot notice; describe which agent/thread produced the snapshot (e.g. "Snapshot of files edited during 4.0.73 finalization … by CURSOR IDE Agent"). |
| meta | string (recommended) | Thread or context (e.g. "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"). Same style as `lupopedia.edges.meta`. |
| views | integer | Total view count (calculated from visits). |
| like_count | integer | Total like count. |
| share_count | integer | Total share count. |

---

## lupopedia.edges — edge types, grouped outbound_edges, comment and meta

Outbound/inbound edges and semantic_tags are defined in [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md). Edge types (references, implements, schema_reference, supersedes, depends_on, documents, related_table, etc.) and the use of `lupo_edges` for storage are unchanged from FLARE; use **`lupopedia.edges`** as the block name.

**Snapshot requirement:** **`lupopedia.edges`** MUST include **`comment`** (snapshot notice) and SHOULD include **`meta`** (thread/context string), in the same way as **`lupopedia.engagement`**.

**Grouped outbound_edges (4.0.73+):** **`outbound_edges`** MAY be a single object with **category keys** (e.g. `code`, `documentation`, `schema`, `runtime`), each holding a list of edge objects. This is the preferred form for table docs and collections so that code references are separate from documentation references. When edges are imported into the database, the category key is stored in **`lupo_edges.edge_category`**; export groups by `edge_category` to rehydrate grouped YAML. Flat form **`outbound_edges: [ { to, type, weight }, ... ]`** remains valid for backward compatibility.

See also [lupo-docs/api/FLARE_HEADERS_COMPLETE_REFERENCE.md](../../api/FLARE_HEADERS_COMPLETE_REFERENCE.md) and [lupo-docs/status/EDGE_STRUCTURE_AUDIT_GROUPED_OUTBOUND_EDGES.md](../../status/EDGE_STRUCTURE_AUDIT_GROUPED_OUTBOUND_EDGES.md) for the full field reference and audit.
