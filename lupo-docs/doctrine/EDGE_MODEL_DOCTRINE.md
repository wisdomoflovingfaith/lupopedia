---
lupopedia.headers:
  lupopedia.schema: doctrine
  file_path_from_root: lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md
  web_path: http://www.lupopedia.com/lupo-docs/doctrine/EDGE_MODEL_DOCTRINE
  last_modified_utc: '20260325200000'
  channel_id: 42
  actor_id: 26
  actor_name: thoth
  delegation_chain: thoth:knowledge
  artifact_type: doctrine
  artifact_kind: canonical
  purpose: Canonical doctrine for the edge model — single table, polymorphic, type-registered
  system_version: "4.0.87"
  traits: [canonical, doctrine, edge_model, v4.0.87]
  tags: [doctrine, edges, consolidation, 4.0.87]
  lupo_agent: cursor

lupopedia.edges:
  outbound_edges:
    - { to: lupo-docs/database/lupopedia/tables/active/lupo_edges.md, type: references, weight: 1.0 }
    - { to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql, type: schema_reference, weight: 1.0 }
    - { to: lupo-docs/database/lupopedia/tables/deprecated/lupo_actor_edges.md, type: supersedes, weight: 1.0 }
    - { to: lupo-docs/database/lupopedia/tables/deprecated/lupo_reference_cited_by.md, type: supersedes, weight: 1.0 }

    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  approved_for_version: "4.1.0"
  approved_for_version_utc: "20260327103238"
  approved_for_version_by: "Cursor IDE Agent (Lead Orchestration)"
  approved_for_version_by_actor_id: 102
  last_verified: '20260325200000'
  last_verified_by: cursor
  orchestrator: wolfie:1
  next_action:
    - Add new edge types to lupo_edge_types seed before using them in code
    - Update this doctrine if object type list changes
  last_verified_by_actor_id: 102
---
# file: EDGE_MODEL_DOCTRINE — delegation: thoth:knowledge — web_path: http://www.lupopedia.com/lupo-docs/doctrine/EDGE_MODEL_DOCTRINE

# Edge Model Doctrine

**Version:** 4.0.87
**Status:** Canonical
**Authority:** WOLFIE (orchestrator), THOTH (documentation)

---

## 1. Single Table Rule

**There is one edge table: `lupo_edges`.**

4.0.88 authority clarification:

- `lupo_edges` in the database is the authoritative edge truth.
- `lupopedia.edges` in files is declaration/snapshot metadata only.
- File edge declarations must be imported/synchronized into `lupo_edges`; files do not create a parallel truth system.

As of 4.0.87, all relationship data lives in `lupo_edges`. No other table may be created to store edges, links, or relationships between system objects without an explicit WOLFIE directive. The following tables were removed and must not be recreated:

| Removed Table | Removed In | Replacement |
|---|---|---|
| `lupo_actor_edges` | 4.0.87 | `lupo_edges` with `left_object_type='actor'`, `right_object_type='actor'` |
| `lupo_reference_cited_by` | 4.0.87 | `lupo_edges` with `edge_type='cites'` |
| `lupo_entity_edges` | Pre-4.0.87 | `lupo_edges` |
| `lupo_gov_event_actor_edges` | Pre-4.0.87 | `lupo_edges` |
| `lupo_gov_event_references` | Pre-4.0.87 | `lupo_edges` |
| `lupo_decision_edges` | 4.0.87 | Decisions live in channel threads; no table replacement |
| `lupo_context_edges` | In plan | `lupo_edges` (pending migration) |

---

## 2. Polymorphism Model

Edges are **polymorphic** — they connect any two object types through a shared structure.

```
left_object_type  VARCHAR(50)   — the type name of the left entity
left_object_id    BIGINT        — the primary key of the left entity
right_object_type VARCHAR(50)   — the type name of the right entity
right_object_id   BIGINT        — the primary key of the right entity
edge_type         VARCHAR(100)  — the named relationship between them
```

Object types are not enforced at the database level. The application layer is responsible for using valid, registered object type names and resolving them to their source tables.

### Registered Object Types

| Value | Source Table | PK Column |
|---|---|---|
| `actor` | `lupo_actors` | `actor_id` |
| `content` | `lupo_contents` | `content_id` |
| `channel` | `lupo_channels` | `channel_id` |
| `reference_object` | `lupo_reference_objects` | `reference_object_id` |
| `collection` | `lupo_collections` | `collection_id` |
| `department` | `lupo_departments` | `department_id` |
| `artifact` | channel filesystem | filename |
| `session` | `lupo_sessions` | `session_id` |
| `domain` | `lupo_federation_nodes` | `federation_node_id` |

---

## 3. Edge Type Registry

**All `edge_type` values must be registered in `lupo_edge_types` before use.**

Edge types are slugs. Do not use ad-hoc strings in application code without a corresponding registry entry.

### Canonical Edge Types (4.0.87)

| `edge_type` | Bidirectional | Meaning |
|---|---|---|
| `supports` | No | Actor A endorses or delegates to Actor B |
| `cites` | No | Content A cites Reference B |
| `cited_by` | No | Reference A is cited by Content B (inverse index) |
| `references` | No | General documentation link (FLARE) |
| `implements` | No | A implements B (FLARE) |
| `depends_on` | No | A depends on B (FLARE) |
| `supersedes` | No | A replaces B |
| `superseded_by` | No | A is replaced by B (inverse of supersedes) |
| `related_to` | Yes | Loose semantic association |
| `channel_parent` | No | Channel hierarchy (parent → child) |
| `thread_member` | No | Actor is member of channel thread |
| `example_of` | No | A is an example of B |

### Adding a New Edge Type

1. Agree on the slug name (lowercase, underscored, max 64 chars).
2. Add a row to the `lupo_edge_types` seed SQL.
3. Update this doctrine table above.
4. Write new application code using the new type.
5. Do **not** register `edge_type` values that duplicate an existing type under a different name.

---

## 4. Direction Convention

- Edges are **directional by default**: left → right.
- Set `bidirectional = 1` when the relationship has no meaningful direction (e.g. `related_to`).
- Application code querying undirected graphs must check both `left_object_id` and `right_object_id`.
- The `cited_by` type exists as a convenience inverse of `cites`. Both may be stored, or only `cites` — the application decides, but must be consistent within a feature boundary.

---

## 5. Soft Delete

All edges use soft deletes:

```sql
is_deleted      TINYINT NOT NULL DEFAULT 0
deleted_ymdhis  BIGINT NOT NULL DEFAULT 0
```

- All queries **must** include `AND is_deleted = 0` unless explicitly auditing deleted edges.
- Never use hard `DELETE` on edge rows. Set `is_deleted = 1` and `deleted_ymdhis = gmdate('YmdHis')`.

---

## 6. Domain Scoping

The `domain_id` field (default `1`) scopes edges to a federation domain. Multi-domain installations must filter by `domain_id` in all queries that are domain-sensitive (e.g. actor-to-actor support relationships).

---

## 7. Properties Field

The `properties` JSON column stores supplementary metadata that does not warrant a dedicated column. Use it for:
- Migrated metadata from removed tables (e.g. `section_anchor_slug` from `lupo_reference_cited_by`)
- Edge-specific context that is read but not queried

Do **not** store data in `properties` that will be used in SQL `WHERE` clauses — index it as a proper column instead.

---

## 8. What Must NOT Happen

- **No new edge tables.** Do not create `lupo_actor_edges`, `lupo_<anything>_edges`, or any table that replicates this model.
- **No hardcoded `edge_type` strings** in application code without a registry entry.
- **No BOOLEAN columns.** Use `TINYINT` per database doctrine.
- **No foreign keys** on this table. The database is dumb storage; referential integrity is enforced by the application.
- **No re-introduction of CIP-style intersection tables.** The edge model is the approved structure for relationships.

---

## 9. Related Documents

- [lupo_edges table documentation](../database/lupopedia/tables/active/lupo_edges.md)
- [lupo_actor_edges (deprecated)](../database/lupopedia/tables/deprecated/lupo_actor_edges.md)
- [lupo_reference_cited_by (deprecated)](../database/lupopedia/tables/deprecated/lupo_reference_cited_by.md)
- [DECISION_MODEL.md](DECISION_MODEL.md) — decisions live in channels, not edge tables
- [install_new_lupopedia.sql](../../lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql)

## 10. File Declarations and DB Authority

- File-level `lupopedia.edges` blocks are useful for visibility, review, and import workflows.
- Runtime queries, joins, and authoritative relationship decisions must read from `lupo_edges`.
- If file declarations and DB rows diverge, resolve by re-import and reconciliation; do not let both persist as conflicting authorities.
