# file: LUPOPEDIA HEADERS Plan — session: L-LUPO-PLAN — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN
---
flare.headers:
  system_version: "4.0.68"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN"
  title: "LUPOPEDIA HEADERS Plan"
  session_name: "L-LUPO-PLAN"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "plan"
---
# LUPOPEDIA HEADERS — Plan (4.0.68)

**Status:** Authoritative design for 4.0.68  
**Canonical name:** LUPOPEDIA HEADERS (replaces FLARE as the system name)  
**Logical structure:** Preserved from FLARE (block model and canonical order)  
**Storage:** `lupo_metadata` table (with table prefix), structured as rows, not a single YAML blob.

---

## 1. Authoritative direction

- **LUPOPEDIA HEADERS** are the canonical metadata system name from **4.0.68** onward.
- **FLARE** remains the historical and doctrinal **logical structure**; we do not invent a different block model.
- Blocks preserved (same as FLARE):
  - `flame.init`
  - `flare.conditional`
  - `flare.headers`
  - `flare.edges`
  - `flare.footer`
  - `flame.see`
  - `flame.close`

---

## 2. Schema: only three structural additions

`lupo_metadata` stays a **metadata property table**. The only new columns are:

| Column | Type | Purpose |
|--------|------|--------|
| `channel_id` | bigint DEFAULT NULL | Assign headers by channel (channel-level or channel-scoped metadata). |
| `parent_metadata_id` | bigint DEFAULT NULL | Hierarchy: root → blocks → properties → repeated structures. |
| `class_name` | varchar(128) DEFAULT NULL | Classify rows (e.g. lupopedia_header_root, lupopedia_block, lupopedia_property, lupopedia_edge, lupopedia_action, lupopedia_mapping). |

**Do not add** as first-class columns: `object_name`, `title`, `web_path`, `file_path_from_root`, `session_name`, `delegation_chain`, `header_yaml`. Those are metadata **properties** and are stored in rows using `property_key` / `property_value` (and optionally `meta_type`).

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

Children of the root represent blocks (FLARE block names):

- `parent_metadata_id` = root `metadata_id`
- `class_name` = `'lupopedia_block'`
- `meta_type` = `'block'`
- `property_key` = `'flare.headers'` | `'flare.edges'` | `'flare.footer'` | `'flame.init'` | `'flame.see'` | `'flame.close'` | `'flare.conditional'`

### 3.3 Property rows

Under each block row, each field is a metadata row, e.g. under `flare.headers`:

- `property_key` = `'flare.version'`, `'file_path_from_root'`, `'web_path'`, `'system_version'`, `'actor_id'`, `'delegation_chain'`, `'purpose'`, `'title'`, `'session_name'`, etc.
- `property_value` = corresponding value

### 3.4 Repeating structures

Edges, mappings, actions (e.g. under `flare.edges`, `flame.see`, `flame.init`, `flame.close`) are child rows with:

- `class_name` = `'lupopedia_edge'` | `'lupopedia_mapping'` | `'lupopedia_action'` (etc.)

---

## 4. Canonical block order

Order must match FLARE doctrine. Validators and YAML exporters MUST emit blocks in this order when present:

1. `flame.init`
2. `flare.conditional`
3. `flare.headers`
4. `flare.edges`
5. `flare.footer`
6. `flame.see`
7. `flame.close`

Optional blocks may be absent; if present, order is fixed.

---

## 5. Channel assignment

Header resolution MUST support:

- **Entity-scoped:** `entity_type` + `entity_id`
- **Channel-scoped:** `channel_id`
- **Combined:** entity + channel when appropriate

Lookup must allow loading metadata by channel, not only by entity.

---

## 6. Markdown file format

The **first visible line** of a LUPOPEDIA-headed Markdown file is the audit/comment line:

```text
# file: {title} — session: {session_name} — delegation: {delegation_chain} — web_path: {web_path}
```

Then:

```text
---
<yaml header blocks in canonical order>
---
<body content>
```

So: **identity line first**, then `---`, then YAML, then `---`, then body. Do not put an opening `---` before the identity line.

---

## 7. Version and migration rule (4.0.68)

- **From 4.0.68+:** New or modified metadata-bearing Markdown MUST use LUPOPEDIA HEADERS rules.
- **Existing FLARE-headed artifacts** remain valid until migrated.
- **Validators** MUST accept both legacy FLARE artifacts and 4.0.68+ LUPOPEDIA-backed artifacts during transition.
- **Canonical storage** is `lupo_metadata`; migration is **incremental**, not an instant cutover.

---

## 8. Required header fields (as properties)

Minimum core fields (stored as property rows under `flare.headers` block) include:

- `flare.version`, `flare.schema`, `file_path_from_root`, `web_path`, `last_modified_utc`, `system_version`, `channel_id`, `actor_id`, `delegation_chain`, `artifact_type`, `artifact_kind`, `purpose`

Optional but supported: `actor_name`, `session_name`, `mood_rgb`, `traits`, `tags`, `lupo_agent`, `agent_name_identity`.

---

## 9. What not to do

- Do **not** add a large `header_yaml` column or many dedicated presentation columns as the primary design.
- Do **not** treat one full header as one single-row YAML blob in the DB.
- Do **not** claim that all files are instantly migrated; document incremental migration.
- Rendered YAML in files is an **export artifact**; the canonical data model is structured metadata rows.

---

## 10. Reference

- FLARE logical structure and block semantics: [FLARE_DOCTRINE.md](../FLARE/FLARE_DOCTRINE.md)
- Format and version rule: [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md)
- Migration path: [LUPOPEDIA_HEADERS_MIGRATION.md](./LUPOPEDIA_HEADERS_MIGRATION.md)
