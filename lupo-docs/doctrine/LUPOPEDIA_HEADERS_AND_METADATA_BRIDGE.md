---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "doctrine"
  system_version: "4.0.74"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS_AND_METADATA_BRIDGE.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS_AND_METADATA_BRIDGE"
  last_modified_utc: "20260314"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "antigravity"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "integration"
  purpose: "Explains how LUPOPEDIA HEADERS bridge the filesystem and database layers via lupo_metadata and lupo_edges snapshots."
  tags: ["headers", "metadata", "database", "snapshot", "v4.0.74", "bridge"]

lupopedia.init:
  orchestrator_actor: "wolfie"
  rule_set_version: "4.0.73+"
  applies_to: ["headers", "database-sync", "metadata"]
  enforcement: strict

lupopedia.edges:
  comment: "Snapshot of outbound edges for metadata bridge documentation."
  outbound_edges:
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema", weight: 0.9 }

lupopedia.footer:
  last_verified: "20260314"
  last_verified_by: "antigravity"
  orchestrator: "wolfie"
  next_action:
    - "Validate snapshot properties in all newly created files."
---
# file: LUPOPEDIA HEADERS and Metadata Bridge — session: L-LUPO-ROOT-ANTIGRAVITY — delegation: wolfie:root (faucet: antigravity) — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS_AND_METADATA_BRIDGE

# LUPOPEDIA HEADERS and Metadata Bridge (v4.0.74)

In Lupopedia, the database holds relational live state (via `lupo_metadata` and `lupo_edges`), while the filesystem holds persistent artifacts (Markdown files). **LUPOPEDIA HEADERS** are the bridge between them.

## 1. The Core Philosophy
A Markdown file with LUPOPEDIA HEADERS is a **portable semantic object**. 
If the database goes offline, or if the file is moved between federation nodes, the header ensures the artifact retains its meaning, identity, relationships, and context. 

## 2. Header Blocks as Database Snapshots
Blocks within the header, such as `lupopedia.metadata` and `lupopedia.edges`, are explicitly designed as **snapshots** of the database state.

### `lupopedia.metadata`
- **What it is:** A snapshot of existing `lupo_metadata` rows relevant to the file/entity.
- **What it is NOT:** It is not a schema definition. It should not list column definitions (e.g., `VARCHAR`, `BIGINT`). 
- **Structure:** It groups rows by `property_key`. If no metadata rows exist, it should contain a fallback comment: `comment: "Snapshot of metadata for this file or entity at artifact creation."`

### `lupopedia.edges`
- **What it is:** A snapshot of semantic relationships, reflecting the contents of `lupo_edges`.
- **Structure:** In 4.0.73+, this supports grouped edges. A single `outbound_edges` object maps category keys (like `code`, `documentation`) to a list of `{ to, type, weight }`. 
- **Portability:** If the file is transferred to another node, the edges can be extracted and reinserted into `lupo_edges` under the corresponding `edge_category`.
- **Requirement:** Must include a `comment` or `meta` property confirming it is a point-in-time snapshot.

### `lupopedia.engagement` (New in 4.0.73+)
- **What it is:** A snapshot of engagement metrics (views, likes) generated from site analytics tables, decoupled from core identity metadata.

## 3. The Rehydration Lifecycle
Because the headers and DB match structurally, they enable a bidirectional lifecycle:
1. **Export:** A tool queries `lupo_metadata` and `lupo_edges` to inject the YAML snapshot into a file.
2. **Offline Usage:** An IDE agent like Cursor or Antigravity reads the file headers and instantly understands its relationships and identity without needing a live SQL connection.
3. **Rehydration:** An ingestion script parses the headers and upserts the records back into `lupo_metadata` and `lupo_edges` (e.g., ensuring `edge_category` is populated correctly).

## 4. Minimum vs Verbose Headers
Lupopedia supports varying degrees of header richness:
- **Minimum:** Used as fallback identity when creating a new file without a DB connection. Just core blocks (`lupopedia.headers`, `lupopedia.footer`).
- **Verbose:** Includes `lupopedia.metadata`, `lupopedia.edges`, `lupopedia.session`, and `lupopedia.engagement` populated fully from the database at generation time.
