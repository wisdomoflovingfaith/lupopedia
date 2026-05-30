---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/filesystem_objects_and_database_snapshots.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/filesystem_objects_and_database_snapshots.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: file-system
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: Filesystem Objects and Database Snapshots Doctrine — session: L-LUPO-ROOT-ANTIGRAVITY — delegation: wolfie:root (faucet: antigravity) — web_path: http://www.lupopedia.com/doctrine/FILESYSTEM_OBJECTS_AND_DATABASE_SNAPSHOTS

# Filesystem Objects & Database Snapshots Doctrine (v4.0.74)

In an environment containing over 200 relational tables and thousands of markdown files, it is vital to know when a file *is* the canonical data versus when a file *represents* canonical data stored elsewhere.

## 1. When an `.md` file is "the file"
A file represents itself when it is the primary source of truth for its content. 
- **Examples:** System `README.md`, standalone guides (`docs/HELP.md`), doctrine documents (`docs/doctrine/`).
- **Header State:** The `lupopedia.init` block sets `artifact_type` and `domain` reflecting its nature. The file's contents are the canonical source; if the DB `lupo_metadata` differs, the file is usually right (or they need a sync).
- **Updates:** Edits are made directly to the file, and later parsed into the DB if necessary.

## 2. When an `.md` file is "a representation of another object"
Often, a markdown file is a projection or snapshot of an object whose authoritative state lives in MySQL.
- **Examples:** Channel thread files (`channels/42/...`), Database table documentation (`docs/database/...`), session exports (`database/sessions/*.md`).
- **Header State:** The headers might declare a specific `namespace` (like `session` or `auth`), or list an `entity_type` like `lupopedia_metadata`. 
- **Snapshot Logic:** Blocks such as `lupopedia.edges`, `lupopedia.metadata`, and `lupopedia.engagement` explicitly declare they are snapshots. The source of truth for the edge relationships or metrics is the database table (e.g., `lupo_edges`).
- **Updates:** If you change an SQL `lupo_edges` representation in the `lupopedia.edges` block manually, you must run an ingestion script to propagate that to the database. Alternatively, the file is simply overwritten the next time a dump is performed from the database.

## 3. Storage Hierarchy
1. **The DB (Canonical Relational State):** `lupo_actors`, `lupo_channels`, `lupo_edges`. Used for application queries and rule enforcement.
2. **The Filesystem (Artifact Memory):** Stored `.md` files that AI agents and faucets (Cursor, Windsurf, Antigravity) use for context, since IDEs operate optimally on file trees.
3. **The Embedded Headers (The Synchronization Layer):** The YAML blocks at the top of the file that allow agents to translate the object back into its database counterpart effortlessly.

## 4. Federated Workflows and Offline Use
This structure exists so that if an agent pulls a thread file from Node 1 to Node 2 (Federation), the file carries the exact semantic context (who said it, when, what edges it has) without requiring direct DB-to-DB replication. 

**Rule of Thumb:** Assume all metadata in a `.md` file is a point-in-time snapshot. To get the live state, query the DB. To transport the state, use the file headers.
