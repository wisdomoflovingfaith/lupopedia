---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/EDGE_STRUCTURE_AUDIT_GROUPED_OUTBOUND_EDGES.md"
  web_path: "http://www.lupopedia.com/lupo-docs/status/EDGE_STRUCTURE_AUDIT_GROUPED_OUTBOUND_EDGES"
  last_modified_utc: "20260313"
  system_version: "4.0.73"
  channel_id: 42
  actor_id: 1003
  artifact_type: "documentation"
  artifact_kind: "audit_report"
  purpose: "Audit of edge storage and header format for transferable grouped edge categories (code, documentation, etc.)."
  tags: ["edges", "lupopedia_headers", "audit", "grouped_edges", "4.0.73"]

lupopedia.edges:
  comment: "Snapshot at artifact creation. Query codebase for latest edge/validator references."
  meta: "Audit: grouped outbound_edges; DB and header format."
  outbound_edges:
    documentation:
      - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "documents", weight: 0.95 }
      - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md", type: "documents", weight: 0.9 }
    code:
      - { to: "lupo-database/lupopedia/content/lupo-app/Services/FlareValidatorService.php", type: "references", weight: 0.95 }
  semantic_tags: ["edges", "audit", "grouped_edges"]

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "When adding header-to-DB import, map outbound_edges group key to lupo_edges.edge_category"
---
# file: Edge structure audit — grouped outbound edges — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/lupo-docs/status/EDGE_STRUCTURE_AUDIT_GROUPED_OUTBOUND_EDGES

# Edge Structure Audit — Grouped Outbound Edges

**Date:** 2026-03-13  
**Objective:** Support file-level grouped edge metadata (code, documentation, schema, runtime, etc.) that is deterministic, transferable into the database, lineage-safe, and machine-parsable.

---

## 1. Current schema (what exists)

### 1.1 Tables involved

| Table | Role | Relevant columns |
|-------|------|------------------|
| **lupo_edges** | Canonical edge store; one row per edge | `edge_id`, `left_object_type`, `left_object_id`, `right_object_type`, `right_object_id`, `edge_type`, **`edge_category`** (varchar(100) DEFAULT NULL), `weight_score`, `semantic_weight`, `flare_weight`, timestamps, soft delete |
| **lupo_metadata** | Header/metadata store; blocks as rows | `metadata_id`, `entity_type`, `entity_id`, `domain_id`, `property_key` (e.g. `lupopedia.edges`), `property_value`, `channel_id`, `parent_metadata_id`, `class_name` (e.g. `lupopedia_edge`) |

- **lupo_edges** already has **`edge_category`** and index **`lupo_edges_idx_edge_category`**. No schema change required to store a group/bucket (e.g. `code`, `documentation`, `schema`, `runtime`).
- **lupo_metadata** stores block-level data; child rows (e.g. `class_name = 'lupopedia_edge'`) can store individual edges; a separate property or child row can hold the group key when edges are stored there.

### 1.2 Current limitations

- **Header parsing / validation:** `FlareValidatorService::validateRelationships()` expects `outbound_edges` to be a **flat array**. It does not accept `outbound_edges.code`, `outbound_edges.documentation`, etc. So grouped YAML was not previously valid for validation.
- **EdgeService:** `createEdge()` does **not** accept or set `edge_category`. Any import from headers to `lupo_edges` would need to pass the group as `edge_category` (and possibly use a different method or overload).
- **Export/render:** No evidence of DB-to-header export that rehydrates edges; if added later, it should write grouped YAML using `edge_category` as the group key.
- **Doctrine/docs:** Grouped `outbound_edges` format was not documented as canonical.

### 1.3 Can the current schema support grouped edge categories?

**Yes.** The **lupo_edges** table already has **`edge_category`**. Each edge row can store:

- `edge_category` = `'code'` | `'documentation'` | `'schema'` | `'runtime'` | etc.
- `edge_type` = `'references'` | `'documents'` | `'related_table'` | etc.
- `flare_weight` or `semantic_weight` for weight.

So:

- **Database:** No structural change. Use existing `edge_category` for the group.
- **Mapping:** When importing from header to DB: for each `outbound_edges.<group>[].to`, create one row with `edge_category = <group>`, `edge_type` from the edge object, weight from the edge object, and left/right object from source artifact and target path/entity.
- **Export:** When exporting DB to header: group rows by `edge_category` and emit `outbound_edges.<edge_category>: [ { to: ..., type: ..., weight: ... } ]`.

---

## 2. Recommended design (doctrine-aligned)

### 2.1 Header format (canonical)

- **Single `outbound_edges` object** with **grouped child keys**. No duplicate `outbound_edges` keys.
- Each group key (e.g. `code`, `documentation`) has a **list** of edge objects.
- Each edge object: `{ to: "path", type: "references"|"documents"|..., weight: 0.0–1.0 }`.

```yaml
lupopedia.edges:
  comment: "Human-readable snapshot summary."
  meta: "Short machine-oriented context."

  outbound_edges:
    code:
      - { to: "path/to/file.php", type: "references", weight: 1.0 }
    documentation:
      - { to: "README.md", type: "documents", weight: 0.8 }
      - { to: "lupo-docs/doctrine/COLLECTIONS/COLLECTIONS_DOCTRINE.md", type: "documents", weight: 0.95 }

  semantic_tags: ["tag1", "tag2"]
```

### 2.2 Backward compatibility

- **Flat format** remains valid: `outbound_edges: [ { to: "...", type: "...", weight: 0.9 } ]` (single list). Validators and import logic treat a purely numeric-keyed list as “all edges in default group” (e.g. no category or a single default like `general`).
- **Grouped format:** if `outbound_edges` is an object with string keys (e.g. `code`, `documentation`), each key is the **edge_group** / **edge_category**; each value is an array of edge items.

### 2.3 Database mapping

| Header | Database (lupo_edges) |
|--------|------------------------|
| `outbound_edges.code[].to` | `right_object_type` + `right_object_id` or path stored per project convention |
| `outbound_edges.code[].type` | `edge_type` |
| `outbound_edges.code[].weight` | `flare_weight` or `semantic_weight` |
| Group key `code` | **`edge_category`** = `'code'` |
| Source artifact | `left_object_type` + `left_object_id` (e.g. file path or content_id) |

Same for `documentation`, `schema`, `runtime`, or any future group.

### 2.4 Field naming

- **Database:** Keep **`edge_category`** (already in schema and indexed). Name aligns with “category of edge” and is short and queryable.
- **Header:** Use the **group key** as the category name (e.g. `code`, `documentation`). No extra field name in the edge object; the key under `outbound_edges` is the category.

---

## 3. What was implemented (post-audit)

- **Doctrine/documentation:** LUPOPEDIA_HEADERS_FORMAT.md and OPTIONAL_BLOCKS.md updated to define grouped `outbound_edges` and backward compatibility with flat list.
- **Validation:** FlareValidatorService updated to accept both formats: flat `outbound_edges` array or grouped `outbound_edges.<group>` arrays; validation runs on the normalized flat list.
- **Canonical example:** `lupo-docs/database/lupopedia/tables/active/lupo_collections.md` updated to use grouped `outbound_edges` (code, documentation) with comment and meta.
- **Changelog:** Entry added for grouped edge support and use of `edge_category` for transfer to DB.
- **No schema change:** No new columns or tables; `lupo_edges.edge_category` is the store for the group.

---

## 4. Future work (not in scope)

- **Import pipeline:** Script or service that reads Markdown headers and inserts/updates `lupo_edges` rows with `edge_category` set from the group key. EdgeService could be extended to accept an optional `edge_category` parameter.
- **Export pipeline:** Query `lupo_edges` by source, group by `edge_category`, and write back to file as grouped YAML.
- **lupo_metadata child rows:** If edges are stored under `lupo_metadata` as child rows, each child should store the group (e.g. in `property_key` or a dedicated column) so export can reconstruct grouped structure.
