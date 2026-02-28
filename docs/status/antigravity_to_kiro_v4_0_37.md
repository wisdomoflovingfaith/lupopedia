# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\status\antigravity_to_kiro_v4_0_37.md"
  file_hash: "fc84224ce7c92fe0ae3e85c0cf9bca19d043bb0a548e5755fd259c3812b83c7b"
  file_path_from_root: "docs\status\antigravity_to_kiro_v4_0_37.md"
  file_hash: "0dc42c2eb15b1c33e584600d199d959366a4d6c87ff4812fbf30ddd1030a542f"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for antigravity_to_kiro_v4_0_37.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "antigravity_to_kiro_v4_0_37md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers:
  file_path_from_root: "docs/status/antigravity_to_kiro_v4_0_37.md"
  system_version: "4.0.37"
  channel_id: 42
  mood_rgb: "00FF00"
  purpose: "Handover instructions for KIRO regarding FLIP v3 and Database updates"
  last_modified_utc: "20260224"
  x_lupo_forwarded: "1003:10000"
  actor_id: 1003
  lupo_agent: "antigravity"

flip.footer:
  referenced_by_actors:
    - 1001 # KIRO
    - 1003 # Antigravity
    - 10000 # Captain
  inbound_edges:
    - "kiro_handover"
    - "database_alignment"
  version: "4.0.37"
---

# 🐺 HANDOVER: ANTIGRAVITY TO KIRO — FLIP v3 & DB ALIGNMENT

**From:** Antigravity (actor_id 1003)  
**To:** KIRO (actor_id 1001)  
**Date:** 20260224  
**Subject:** FLIP v3 Draft Schema & SQL Installer Requirements

KIRO, I have completed the VSX extension's transition to the **FLIP v3 Draft** architecture. This introduces a three-layer metadata model that we need to align with the central database and registry.

## 🧬 1. FLIP v3 ARCHITECTURAL SHIFT
The monolithic `wolfie.headers` is being decomposed into:
1.  **Identity Layer (`identity`)**: Core actors and routing.
2.  **Classification Layer (`classification`)**: Ontological descriptors and traits.
3.  **Relations Layer (`relations`)**: Graph edges (Inbound/Outbound) stored in the footer.

**JSON Integration**: I have updated the parser to support JSON/JSON5 blocks within FLIP delimiters. This is our new preferred format for high-speed agentic parsing.

## 🏗️ 2. DATABASE (SQL) INSTALLER UPDATES
Windsurf has prepared the initial `lupo_flip_artifacts` table, but we need to expand it to support the **Actor Trinity** and **Semantic Collections** defined in v3.

### Required Schema Changes for `lupo_flip_artifacts`:
Please update the installation SQL (`database/install_new_lupopedia.sql`) to include:

1.  **`intent_authority_id` (BIGINT)**: Explicitly track the human authority (e.g., `10000`) separately from the `actor_id` (execution agent). This is critical for accountability audits.
2.  **`collection_id` (VARCHAR(64))**: Elevate this to a first-class column for efficient cluster joins.
3.  **`artifact_type` (VARCHAR(64))**: Distinct from `artifact_kind`.
4.  **`traits` (TEXT)**: To store classification traits (JSON-encoded).
5.  **`collection_rules` / `collection_traits` (TEXT)**: For artifacts that define semantic clusters.

### Example SQL Expansion:
```sql
ALTER TABLE lupo_flip_artifacts 
ADD COLUMN intent_authority_id bigint NOT NULL AFTER actor_id,
ADD COLUMN collection_id varchar(100) DEFAULT NULL AFTER artifact_kind,
ADD COLUMN artifact_type varchar(64) NOT NULL AFTER artifact_kind,
ADD COLUMN traits text DEFAULT NULL AFTER agent_type,
ADD COLUMN collection_rules text DEFAULT NULL AFTER traits,
ADD INDEX idx_flip_collection (collection_id),
ADD INDEX idx_flip_intent_auth (intent_authority_id);
```

## 🔄 3. REGISTRY & BACKFILL
- **Registry Update**: Please add registry entries for the new FLIP v3 Draft version (`9005006: flip_schema_version v3.0-draft`).
- **Backfill Strategy**: During the 4.0.37 backfill, ensure you map `x_lupo_forwarded` (formatted as `agent:human`) into the distinct `actor_id` and `intent_authority_id` columns.

## 🔍 4. COORDINATION
- **Semantic Event Bus**: The extension now broadcasts `intent_to_edit` and `semantic_conflict`. If you implement a server-side sync, please hook into these event types.
- **Flip Query Engine**: The DSL parser is live. Ensure any server-side relationship discovery results match the `Edge` interface defined in `tools/vsx-extension/src/lupopedia/flip/parser/types.ts`.

Ready for your backfill pass.

---
**Status:** ✅ VSX EXTENSION v3 READY  
**Next Up:** KIRO Registry expansion & Backfill.