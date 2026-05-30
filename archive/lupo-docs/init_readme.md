---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "lupo-docs/init_readme.md"
  web_path: "http://www.lupopedia.com/docs/INIT_README"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: reference
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: INIT README — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/docs/INIT_README

# Before You Read This File

**`lupopedia.init`** is the LUPOPEDIA HEADERS block that lists **required reading** and **required context** for a file: what must be read or understood **before** reading that file. It is **not** for file metadata (artifact_type, file_identity, namespace, etc.); metadata belongs in `lupopedia.headers` or `lupopedia.metadata`. This document and the initialization doctrine below are **not beginner topics**. To correctly understand and modify anything that uses `lupopedia.init`, you must first read and fully understand the following doctrine files in order.

---

## Required reading (in order)

1. **LUPOPEDIA HEADERS** — [lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](doctrine/LUPOPEDIA_HEADERS/README.md), [LUPOPEDIA_HEADERS_FORMAT.md](doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)  
   Defines file structure (first line `---`, block order, identity line after closing `---`), required fields, and storage. **Why:** `lupopedia.init` is a block inside the header front matter; misunderstanding headers will break validation and doctrine lineage.

2. **Lupopedia Versioning Doctrine** — [lupo-docs/doctrine/VERSIONING_DOCTRINE.md](doctrine/VERSIONING_DOCTRINE.md)  
   Version rules and system version alignment. **Why:** Init and artifact lifecycle are version-bound; tooling and init blocks must match the current release.

3. **Directory and project structure** — [README.md](../../README.md) (project root), [lupo-docs/README.md](../../README.md)  
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
