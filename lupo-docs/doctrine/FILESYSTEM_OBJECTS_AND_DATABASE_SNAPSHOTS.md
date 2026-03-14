---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "doctrine"
  system_version: "4.0.74"
  file_path_from_root: "lupo-docs/doctrine/FILESYSTEM_OBJECTS_AND_DATABASE_SNAPSHOTS.md"
  web_path: "http://www.lupopedia.com/doctrine/FILESYSTEM_OBJECTS_AND_DATABASE_SNAPSHOTS"
  last_modified_utc: "20260314"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "antigravity"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "file-system"
  purpose: "Clarifies when an .md file represents itself versus acting as a proxy for another object in the database."
  tags: ["filesystem", "snapshot", "canonical", "v4.0.74", "doctrine"]

lupopedia.init:
  orchestrator_actor: "wolfie"
  rule_set_version: "4.0.73+"
  applies_to: ["filesystem", "artifacts", "snapshots"]
  enforcement: strict

lupopedia.edges:
  comment: "Snapshot of outbound edges for filesystem object documentation."
  outbound_edges:
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260314"
  last_verified_by: "antigravity"
  orchestrator: "wolfie"
  next_action:
    - "Ensure that agent reports explicitly declare if they are files or object proxies in their headers."
---
# file: Filesystem Objects and Database Snapshots Doctrine — session: L-LUPO-ROOT-ANTIGRAVITY — delegation: wolfie:root (faucet: antigravity) — web_path: http://www.lupopedia.com/doctrine/FILESYSTEM_OBJECTS_AND_DATABASE_SNAPSHOTS

# Filesystem Objects & Database Snapshots Doctrine (v4.0.74)

In an environment containing over 200 relational tables and thousands of markdown files, it is vital to know when a file *is* the canonical data versus when a file *represents* canonical data stored elsewhere.

## 1. When an `.md` file is "the file"
A file represents itself when it is the primary source of truth for its content. 
- **Examples:** System `README.md`, standalone guides (`lupo-docs/HELP.md`), doctrine documents (`lupo-docs/doctrine/`).
- **Header State:** The `lupopedia.init` block sets `artifact_type` and `domain` reflecting its nature. The file's contents are the canonical source; if the DB `lupo_metadata` differs, the file is usually right (or they need a sync).
- **Updates:** Edits are made directly to the file, and later parsed into the DB if necessary.

## 2. When an `.md` file is "a representation of another object"
Often, a markdown file is a projection or snapshot of an object whose authoritative state lives in MySQL.
- **Examples:** Channel thread files (`lupo-channels/42/...`), Database table documentation (`lupo-docs/database/...`), session exports (`lupo-database/sessions/*.md`).
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
