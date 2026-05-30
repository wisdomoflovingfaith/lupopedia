---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/76_A_VSX_EXTENSION.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/76_A_VSX_EXTENSION.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/76_vsx_extension.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/vsx-extension
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_76_A
  title: "PRD 76: Lupopedia VSX Extension & Metadata Pipeline"
  summary: null
---
# PRD: Lupopedia VSX Extension & Metadata Pipeline

## 1. Introduction
This document outlines the product requirements and technical architecture for the Lupopedia VSX extension, the metadata indexing system, and the delegation engine. It defines the high-performance metadata pipeline used by the extension for multi-agent coordination starting from v4.1.

## 2. Core Systems & Pipelines

### 2.1 The Metadata Pipeline
Starting with v4.1, Lupopedia abandons scanning full file contents for metadata, opting for an **Isolated Block Extraction** strategy combined with an **In-Memory LRU Cache**.

The pipeline executes as follows:
1. **Extraction**: `YamlExtractor` isolates parsing to the beginning or end of the file (first/last few KB) to retrieve exactly the `---` delimiters.
2. **Parsing**: `parseMetadata` is capable of handling both YAML-lite and JSON5-native blocks.
3. **Indexing**: `MetadataService` extracts key semantic fields crucial for routing and relationships, particularly `actorId`, `principalId`, and `relations`.
4. **Persistence**: Extracted metadata is kept in `MetadataIndexCache` for sub-millisecond retrieval.

## 3. Delegation Engine & Identity

### 3.1 Delegation Chain Rules
The legacy `x_lupo_forwarded` header is fully deprecated in the VSX extension logic and replaced with the `delegation_chain` mechanism.

#### Chain Structure
```
"EXECUTOR_ID : PROXY_ID : AUTHORITY_ID"
```

#### Constitutional Delegation Rules
- **Rule 1 (Ranges)**: AI agents MUST use IDs `< 10000`. Human authenticators MUST use IDs `>= 10000`.
- **Rule 2 (Principal)**: The final ID mapped in the chain corresponds directly to the **Principal Authority**.
- **Rule 3 (Executor)**: The first ID in the chain MUST exactly match the `actor_id` declared within the artifact.

### 3.2 Programmatic Retrieval
Components that integrate with the VSX extension access delegation structures deterministically:
```ts
import { DelegationEngine } from './logic/DelegationEngine';

// Example parsing:
const chain = "1003:1001:10000";
const principal = DelegationEngine.getPrincipal(chain); // Resolves to 10000
const executor = DelegationEngine.getExecutor(chain); // Resolves to 1003
```

## 4. UI Integrations & Commands

The VSX extension surfaces several key tools directly into the developer workflow. 

### 4.1 Exploration Tools
- **Delegation Inspector**: Executed via `Lupopedia: Inspect Delegation`. It visualizes the trust and execution paths defined by the delegation chain.
- **Semantic Map**: Executed via `Lupopedia: Show Semantic Map`. It visually maps and resolves the inbound/outbound edges declared in artifacts.

### 4.2 Auto-Repair System
Because the VSX extension manages complex YAML metadata, a dedicated `RepairService` provides commands to fix syntax errors or logic mistakes autonomously:
- `Lupopedia: Normalize Metadata`: Corrects broken formatting in YAML metadata blocks and handles automatic migration of legacy `wolfie.headers` / FLARE variables to strict `lupopedia.headers`.
- `Lupopedia: Repair Delegation Chain`: Analyzes the acting agent context and re-aligns the `delegation_chain` array.

---

This extension documentation guarantees accurate multi-agent tagging and execution tracing for the entire Lupopedia project.
