---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/INIT_README.md"
  web_path: "http://www.lupopedia.com/docs/INIT_README"
  last_modified_utc: "20260312"
  system_version: "4.0.71"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "reference"
  purpose: "Prerequisites and required reading before working with lupopedia.init; points to initialization doctrine and LUPOPEDIA HEADERS."
  tags: ["init", "lupopedia.init", "prerequisites", "doctrine", "4.0.71"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
  last_verified_by: "cursor"
  next_action:
    - "Sync prerequisite list with LUPO_INITIALIZATION_DOCTRINE.md"
    - "Add new required doctrine to reading order when created"
    - "Validate LUPOPEDIA HEADERS and next_action on init-related files"
---
# file: INIT README — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/docs/INIT_README

# Before You Read This File

**`lupopedia.init`** and the initialization process are **not beginner topics**. To correctly understand and modify anything that uses `lupopedia.init`, you must first read and fully understand the following doctrine files in order.

---

## Required reading (in order)

1. **LUPOPEDIA HEADERS** — [lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](doctrine/LUPOPEDIA_HEADERS/README.md), [LUPOPEDIA_HEADERS_FORMAT.md](doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)  
   Defines file structure (first line `---`, block order, identity line after closing `---`), required fields, and storage. **Why:** `lupopedia.init` is a block inside the header front matter; misunderstanding headers will break validation and doctrine lineage.

2. **Lupopedia Versioning Doctrine** — [lupo-docs/doctrine/VERSIONING_DOCTRINE.md](doctrine/VERSIONING_DOCTRINE.md)  
   Version rules and system version alignment. **Why:** Init and artifact lifecycle are version-bound; tooling and init blocks must match the current release.

3. **Directory and project structure** — [README.md](../README.md) (project root), [lupo-docs/README.md](README.md)  
   Where init-related and doctrine files live. **Why:** Required before adding or moving init-controlled artifacts.

4. **Agent & Faucet Doctrine** — [lupo-docs/doctrine/ActorFaucetOntology.md](doctrine/ActorFaucetOntology.md), [lupo-docs/doctrine/FAUCET_TRACEABILITY_DOCTRINE.md](doctrine/FAUCET_TRACEABILITY_DOCTRINE.md)  
   Actors orchestrate; faucets execute; sessions carry context. **Why:** Init/close blocks can reference actor_id and faucet; misunderstanding causes misattribution and broken delegation.

5. **Semantic graph & collections** (recommended) — [lupo-docs/channels/doctrine/SEMANTIC_GRAPH_DOCTRINE.md](channels/doctrine/SEMANTIC_GRAPH_DOCTRINE.md), [lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md](doctrine/COLLECTIONS_DOCTRINE.md)  
   How content participates in the semantic graph and collections. **Why:** When init applies to such content, scope and context resolution depend on these doctrines.

---

## Warning

**Failure to understand these prerequisites can result in invalid initialization, broken headers, or corrupted doctrine lineage.** All files that use LUPOPEDIA HEADERS must contain valid LUPOPEDIA HEADERS (first line `---`, exactly one front matter block, identity line immediately after the closing `---`). Do not add or edit `lupopedia.init` or other header blocks without having read the doctrine above.

---

## Full prerequisite doctrine

For the authoritative list, rationale, and definition of `lupopedia.init`, see **[lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md](doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md)**.
