---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/init/LUPO_INITIALIZATION_DOCTRINE"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: LUPO Initialization Doctrine — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/init/LUPO_INITIALIZATION_DOCTRINE

# LUPO Initialization Doctrine

This document defines the **prerequisite doctrine** and conceptual foundations that a reader must understand before working with **`lupopedia.init`** or the initialization process. `lupopedia.init` is not a beginner file; it depends on LUPOPEDIA HEADERS, versioning, directory and agent/faucet semantics, and optional semantic/collections context.

---

## Prerequisite reading list (read in order)

Before reading or modifying any file that uses `lupopedia.init`, or before changing initialization behavior, read and understand the following in this order:

| Order | Doctrine | Path | Why required |
|-------|----------|------|--------------|
| 1 | **LUPOPEDIA HEADERS** | [docs/doctrine/LUPOPEDIA_HEADERS/README.md](../LUPOPEDIA_HEADERS/README.md), [LUPOPEDIA_HEADERS_FORMAT.md](../LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) | `lupopedia.init` is a block inside the LUPOPEDIA HEADERS front matter. File structure (first line `---`, block order, identity line after closing `---`), required fields, and storage in `lupo_metadata` are defined here. Misunderstanding headers will break validation and doctrine lineage. |
| 2 | **Versioning doctrine** | [docs/doctrine/VERSIONING_DOCTRINE.md](../VERSIONING_DOCTRINE.md) | Initialization and artifact lifecycle are version-bound. System version and patch rules must be understood so init blocks and tooling stay aligned with the current release. |
| 3 | **Directory and project structure** | [README.md](../../../README.md) (project root), [docs/README.md](../../README.md) | Where init-related files live, where doctrine and channels live, and how the repo is organized. Required before adding or moving init-controlled artifacts. |
| 4 | **Agent & faucet doctrine** | [docs/doctrine/ActorFaucetOntology.md](../ActorFaucetOntology.md), [docs/doctrine/FAUCET_TRACEABILITY_DOCTRINE.md](../FAUCET_TRACEABILITY_DOCTRINE.md) | Init and close blocks can reference actor_id and faucet; actors orchestrate, faucets execute. Understanding this prevents misattribution and broken delegation chains. |
| 5 | **Semantic graph & collections** (optional but recommended) | [docs/channels/doctrine/SEMANTIC_GRAPH_DOCTRINE.md](../../channels/doctrine/SEMANTIC_GRAPH_DOCTRINE.md), [docs/doctrine/COLLECTIONS_DOCTRINE.md](../COLLECTIONS_DOCTRINE.md) | When init applies to content that participates in the semantic graph or collections, these doctrines define how context and scope are resolved. |

---

## What is lupopedia.init?

**`lupopedia.init`** is an optional YAML block in the LUPOPEDIA HEADERS front matter. It appears in **canonical block order** before `lupopedia.headers` (see [LUPOPEDIA_HEADERS_FORMAT.md](../LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) §1).

**Purpose:** `lupopedia.init` lists **required reading** and **required context** that a reader must have before reading **this file**. It is **not** for file metadata (artifact_type, file_identity, namespace, etc.); those belong in `lupopedia.headers` or `lupopedia.metadata`.

### Recommended structure

- **required_reading:** List of paths (or objects with **path** and **reason**) that must be read first. Order matters when dependencies are sequential. Simple form: `- "path/to/doc.md"`. Extended form: `- path: "path/to/doc.md"` and `reason: "Why this is required"`.
- **required_context:** Short list of concepts or statements that must be understood before reading this file (e.g. "LUPOPEDIA HEADERS are the bridge between files and database", "Actor/faucet distinction", "Cursor is lead orchestrator").

Example (simple list):

```yaml
lupopedia.init:
  required_reading:
    - "docs/doctrine/LUPOPEDIA_HEADERS/README.md"
    - "docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md"
  required_context:
    - "LUPOPEDIA HEADERS format and file order"
    - "Actor/faucet distinction"
```

Example (path + reason, recommended for plan/report files):

```yaml
lupopedia.init:
  required_reading:
    - path: "docs/INIT_README.md"
      reason: "Prerequisites and 'Before You Read This File'"
    - path: "docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      reason: "Header format and block order"
  required_context:
    - "LUPOPEDIA HEADERS are the bridge between files and database"
    - "Cursor (actor_id 102) is lead orchestrator; faucet-specific plans remain authoritative for their domains"
```

Tooling and agents should interpret `lupopedia.init` as: "Read and understand these before reading the rest of this file." Do not use `lupopedia.init` for document type, namespace, domain, or system_version; use `lupopedia.headers` for those.

### Circular dependency: required reading for lupopedia.init itself

Files that **define** or **document** `lupopedia.init` (e.g. this doctrine file, INIT_README.md, LUPOPEDIA_HEADERS_FORMAT.md) assume the reader already has minimal context: either **INIT_README.md** or **LUPOPEDIA HEADERS README** is the usual first entry point. There is no strict circular dependency: a reader can start with INIT_README or the HEADERS README, then read this doctrine. Do not list "this file" in its own `lupopedia.init`; list the prerequisites that lead **to** understanding this file (e.g. HEADERS README, versioning doctrine).

All files that use LUPOPEDIA HEADERS must have valid LUPOPEDIA HEADERS (first line `---`, single front matter block, identity line after closing `---`). Invalid or duplicate headers will break validators and doctrine lineage.

---

## Pair: lupopedia.init and lupopedia.next_actions

| Block | Purpose |
|-------|---------|
| **lupopedia.init** | **Before:** Required reading and required context that must be read or understood **before** reading this file (`required_reading:`, `required_context:`). |
| **lupopedia.next_actions** | **After:** Suggested next actions to take **after** reading or using this file (`next_actions:` list). Legacy name: **lupopedia.close** (validators accept both). |

See [OPTIONAL_BLOCKS.md](../LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md) for **lupopedia.next_actions** structure and examples.

---

## Warning

**Misunderstanding LUPOPEDIA HEADERS or versioning will break the system.** Do not add or edit `lupopedia.init` blocks—or any LUPOPEDIA HEADERS—without having read the prerequisite doctrine above. Failure to follow the required reading order can result in invalid initialization, broken headers, or corrupted doctrine lineage.
