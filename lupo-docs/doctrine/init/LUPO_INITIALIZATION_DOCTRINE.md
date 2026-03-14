---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/init/LUPO_INITIALIZATION_DOCTRINE"
  last_modified_utc: "20260312"
  system_version: "4.0.71"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "Initialization doctrine and prerequisites for understanding lupopedia.init; required reading order and conceptual foundations."
  tags: ["doctrine", "init", "lupopedia.init", "prerequisites", "4.0.71"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/VERSIONING_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/ActorFaucetOntology.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/INIT_README.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
  last_verified_by: "cursor"
  next_action:
    - "Keep prerequisite table paths and rationale current"
    - "Add new doctrine to list when it becomes a dependency for init"
    - "Validate next_action in all init and header doctrine files"
---
# file: LUPO Initialization Doctrine — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/init/LUPO_INITIALIZATION_DOCTRINE

# LUPO Initialization Doctrine

This document defines the **prerequisite doctrine** and conceptual foundations that a reader must understand before working with **`lupopedia.init`** or the initialization process. `lupopedia.init` is not a beginner file; it depends on LUPOPEDIA HEADERS, versioning, directory and agent/faucet semantics, and optional semantic/collections context.

---

## Prerequisite reading list (read in order)

Before reading or modifying any file that uses `lupopedia.init`, or before changing initialization behavior, read and understand the following in this order:

| Order | Doctrine | Path | Why required |
|-------|----------|------|--------------|
| 1 | **LUPOPEDIA HEADERS** | [lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](../LUPOPEDIA_HEADERS/README.md), [LUPOPEDIA_HEADERS_FORMAT.md](../LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) | `lupopedia.init` is a block inside the LUPOPEDIA HEADERS front matter. File structure (first line `---`, block order, identity line after closing `---`), required fields, and storage in `lupo_metadata` are defined here. Misunderstanding headers will break validation and doctrine lineage. |
| 2 | **Versioning doctrine** | [lupo-docs/doctrine/VERSIONING_DOCTRINE.md](../VERSIONING_DOCTRINE.md) | Initialization and artifact lifecycle are version-bound. System version and patch rules must be understood so init blocks and tooling stay aligned with the current release. |
| 3 | **Directory and project structure** | [README.md](../../../README.md) (project root), [lupo-docs/README.md](../../README.md) | Where init-related files live, where doctrine and channels live, and how the repo is organized. Required before adding or moving init-controlled artifacts. |
| 4 | **Agent & faucet doctrine** | [lupo-docs/doctrine/ActorFaucetOntology.md](../ActorFaucetOntology.md), [lupo-docs/doctrine/FAUCET_TRACEABILITY_DOCTRINE.md](../FAUCET_TRACEABILITY_DOCTRINE.md) | Init and close blocks can reference actor_id and faucet; actors orchestrate, faucets execute. Understanding this prevents misattribution and broken delegation chains. |
| 5 | **Semantic graph & collections** (optional but recommended) | [lupo-docs/channels/doctrine/SEMANTIC_GRAPH_DOCTRINE.md](../../channels/doctrine/SEMANTIC_GRAPH_DOCTRINE.md), [lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md](../COLLECTIONS_DOCTRINE.md) | When init applies to content that participates in the semantic graph or collections, these doctrines define how context and scope are resolved. |

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
    - "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
    - "lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md"
  required_context:
    - "LUPOPEDIA HEADERS format and file order"
    - "Actor/faucet distinction"
```

Example (path + reason, recommended for plan/report files):

```yaml
lupopedia.init:
  required_reading:
    - path: "lupo-docs/INIT_README.md"
      reason: "Prerequisites and 'Before You Read This File'"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
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
