# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/dev/metadata_index.md"
  file_hash: "14da786fd15c3290bfab20441afe58bd9b8caddf190ca444e5f34a2ae31e1bbc"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\dev\metadata_index.md"
  file_hash: "9c75b783a8802bae193806def48a07194c6a37eff91decccc104e47bc9c7c8e5"
  file_path_from_root: "lupo-docs\dev\metadata_index.md"
  file_hash: "2cb5b94f9cb12dca7d798cafc64f719596c88f6c946cc9e8a51525e8c1d17844"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for metadata_index.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "dev", "metadata_indexmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers:
  file_path_from_root: "lupo-docs/dev/metadata_index.md"
  system_version: "4.1.0"
  purpose: "Developer documentation for the Metadata Indexing and Delegation Chain system"
  delegation_chain: "1003:10000"
  actor_id: 1003
  artifact_type: "documentation"
  artifact_kind: "developer_guide"
---

# 🧬 METADATA INDEXING & DELEGATION ENGINE (v4.1)

This document describes the high-performance metadata pipeline used by the Lupopedia VSX extension for multi-agent coordination.

## 🚀 OVERVIEW

Starting with v4.1, Lupopedia moves away from scanning full file contents for metadata. Instead, it uses an **Isolated Block Extraction** strategy combined with an **In-Memory LRU Cache**.

## 🏗️ THE METADATA PIPELINE

1.  **Extraction**: `YamlExtractor` reads only the first/last few KB to find `---` delimiters.
2.  **Parsing**: `parseMetadata` handles both YAML-lite and JSON5-native blocks.
3.  **Indexing**: `MetadataService` extracts core fields (`actorId`, `principalId`, `relations`).
4.  **Persistence**: Data is cached in `MetadataIndexCache` for sub-millisecond retrieval.

## 🔗 DELEGATION CHAIN (v4.1 RULES)

The `delegation_chain` header replaces the legacy `x_lupo_forwarded` field.

### Chain Structure
`"EXECUTOR_ID : PROXY_ID : AUTHORITY_ID"`

*   **Rule 1 (Ranges)**: Agents must have IDs `< 10000`. Humans must have IDs `>= 10000`.
*   **Rule 2 (Principal)**: The final ID in the chain is the **Principal Authority**.
*   **Rule 3 (Executor)**: The first ID in the chain must match the `actor_id` of the artifact.

### Utility Usage
```ts
import { DelegationEngine } from './logic/DelegationEngine';

const chain = "1003:1001:10000";
const principal = DelegationEngine.getPrincipal(chain); // 10000
const executor = DelegationEngine.getExecutor(chain); // 1003
```

## 🛠️ UI INTEGRATION

- **Delegation Inspector**: Open with `Lupopedia: Inspect Delegation`. Visualizes the trust path.
- **Semantic Map**: Open with `Lupopedia: Show Semantic Map`. Visualizes graph edges.

## ♻️ AUTO-REPAIR SYSTEM

The `RepairService` provides commands to fix common mistakes:
- `Lupopedia: Normalize Metadata`: Corrects formatting and migrates legacy headers.
- `Lupopedia: Repair Delegation Chain`: Re-aligns the chain with the current acting agent.

---
**Lead Architect:** Antigravity (1003)  
**Strategy:** Isolated Persistence  
**Version:** 4.1.0-STABLE
