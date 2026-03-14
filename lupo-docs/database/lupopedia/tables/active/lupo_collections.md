---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_collections.md"
  system_version: "4.0.73"
  namespace: "collection"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260313"
  artifact_type: "documentation"
  artifact_kind: "database_table"
  purpose: "JetBrains domain table documentation for lupo_collections"
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of files related to lupo_collections at artifact creation. Separate runtime/code references from documentation references."
  meta: "Table doc: lupo_collections"

  outbound_edges:
    code:
      - { to: "lupo-database/lupopedia/content/lupo-app/Services/CollectionTabsService.php", type: "references", weight: 1.0 }
      - { to: "lupo-database/lupopedia/content/lupo-app/Services/CollectionZeroService.php", type: "references", weight: 1.0 }
      - { to: "lupo-database/lupopedia/content/lupo-app/Services/SavedCollectionsService.php", type: "references", weight: 1.0 }
      - { to: "lupo-includes/functions/collection-tabs-loader.php", type: "references", weight: 0.95 }
      - { to: "lupo-api/list_user_collections.php", type: "references", weight: 0.95 }
      - { to: "lupo-api/load_collection_tabs.php", type: "references", weight: 0.95 }
      - { to: "lupo-includes/modules/truth/truth-controller.php", type: "references", weight: 0.9 }
      - { to: "lupo-includes/modules/api/semantic-navbar-api.php", type: "references", weight: 0.9 }
      - { to: "lupo-includes/class-SearchIndexer.php", type: "references", weight: 0.85 }
      - { to: "lupo-includes/themes/default/components/collection_tabs.php", type: "references", weight: 0.85 }
      - { to: "lupo-includes/themes/default/components/collections_dropdown.php", type: "references", weight: 0.85 }
      - { to: "lupo-includes/themes/default/components/saved-collections-nav.php", type: "references", weight: 0.85 }
      - { to: "lupo-includes/functions/render-saved-collections.php", type: "references", weight: 0.85 }
      - { to: "lupo-includes/modules/content/renderers/content-renderer.php", type: "references", weight: 0.8 }
      - { to: "lupo-includes/modules/content/content-model.php", type: "references", weight: 0.8 }
      - { to: "lupo-includes/modules/content/content-controller.php", type: "references", weight: 0.8 }
      - { to: "lupo-includes/themes/default/layouts/main_layout.php", type: "references", weight: 0.8 }
      - { to: "lupo-includes/themes/default/components/collection_selector.php", type: "references", weight: 0.75 }
      - { to: "lupo-includes/themes/default/components/collection_tabs_horizontal.php", type: "references", weight: 0.75 }
      - { to: "lupo-includes/bootstrap.php", type: "references", weight: 0.7 }
      - { to: "lupo-includes/header.php", type: "references", weight: 0.7 }
    documentation:
      - { to: "README.md", type: "documents", weight: 0.7 }
      - { to: "HOW_TO_USE_LUPOPEDIA.md", type: "documents", weight: 0.6 }
      - { to: "lupo-docs/database/lupopedia/tables/active/lupo_collection_tabs.md", type: "related_table", weight: 0.95 }

  semantic_tags: ["lupo_collections", "database_table", "php_references", "documentation_references", "collections"]

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  views: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "antigravity"
  orchestrator: "antigravity"
  next_action:
    - "Keep code and documentation edges in sync with codebase and table docs"
---

# Table: lupo_collections

## Table Overview
- purpose: Collection containers used to organize content and navigation structures.
- category: active
- status: active (present in current TOON and install schema)
- version introduced: not explicitly documented in TOON/install comments
- version deprecated: not applicable
- removal notes: not applicable
- migration references: MIGRATION_MAPPING_REFERENCE.md, livehelp_qa_migration.md

## Column Documentation
| Column | Type | Nullability | Default | Description |
|---|---|---|---|---|
| collection_id | bigint auto_increment | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| federation_node_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| actor_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| department_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| name | varchar(255) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| slug | varchar(100) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| color | char(6) | Nullable/unspecified | ''666666 | TOON-defined field; canonical semantic description not specified in TOON. |
| description | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| sort_order | int | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| properties | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| published_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| created_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| updated_ymdhis | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_deleted | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| deleted_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| parent_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| channel_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_nav_menu | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| nav_icon | varchar(64) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |

## Relationships
- foreign keys: none (database doctrine forbids foreign keys)
- inbound references: no canonical inbound reference list found in TOON
- outbound references: No foreign keys or explicit relationships in TOON (`relationships: []`).
- join patterns: Join by `collection_id`; common joins: `lupo_collection_tabs.collection_id`, `lupo_contents.default_collection_id`.

## Usage Notes
- migration notes: TOON and install schema are aligned for this table name.
- compatibility notes: current schema uses BIGINT timestamp doctrine and soft-delete patterns where present.
- warnings: avoid assuming implicit constraints; use doctrine that logic is application-side.
- future considerations: if additional relationships are introduced, document via TOON updates first.
- historical changes if updating existing docs: existing flat documentation was retained; this file is the category-structured canonical doc for this domain pass.
