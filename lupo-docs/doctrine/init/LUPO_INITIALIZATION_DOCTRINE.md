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

**`lupopedia.init`** is an optional YAML block in the LUPOPEDIA HEADERS front matter. It appears in **canonical block order** before `lupopedia.headers` (see [LUPOPEDIA_HEADERS_FORMAT.md](../LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) §1). It is used to:

- Declare **document type** or **initialization context** (e.g. `document_type: "doctrine"`, `system_version: "4.0.71"`).
- Carry **pre-actions** or **execution mode** (e.g. advisory vs required) for lifecycle hooks, when tooling supports them.
- Provide **runtime or bootstrap hints** for agents and validators.

All files that use LUPOPEDIA HEADERS must have valid LUPOPEDIA HEADERS (first line `---`, single front matter block, identity line after closing `---`). Invalid or duplicate headers will break validators and doctrine lineage.

---

## Warning

**Misunderstanding LUPOPEDIA HEADERS or versioning will break the system.** Do not add or edit `lupopedia.init` blocks—or any LUPOPEDIA HEADERS—without having read the prerequisite doctrine above. Failure to follow the required reading order can result in invalid initialization, broken headers, or corrupted doctrine lineage.
