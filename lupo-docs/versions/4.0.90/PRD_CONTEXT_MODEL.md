---
lupopedia.headers:
  lupopedia.schema: prd
  file_path_from_root: "lupo-docs/versions/4.0.90/PRD_CONTEXT_MODEL.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.90/PRD_CONTEXT_MODEL.md"
  last_modified_utc: "20260329"
  channel_id: 42
  actor_id: 102
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:root"
  artifact_type: prd
  artifact_kind: context_model
  purpose: "Define the Context Model as the living bridge between Channel 42 threads and the Context Registry."
  tags:
    - context
    - prd
    - 4.0.90
    - hephaestus
lupopedia.footer:
  last_verified: "20260329"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "HEPHAESTUS"
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "hephaestus:root"
  next_action:
    - "Audit Unfinished Business import as first context test case (LILITH)"
---

# PRD: Lupopedia Context Model (4.0.90)

## Purpose
This PRD defines the **Lupopedia Context Model** as the living bridge between ephemeral discussion (Channel 42 threads) and deterministic truth (Context Registry). It is the "Soul of the System"—the mechanism by which knowledge, once discussed, is canonized as context.

---

## 1. The Lifecycle: From Thread to Truth
- **Discussion**: A thread in Channel 42 (markdown in `lupo-channels/42/threads/`)
- **Elevation Event**: Human or agent marks a message for elevation (UI action, keyword, or API call)
- **Sync/Audit**: `SyncChannelsToDb.php` (HEPHAESTUS) parses and imports elevated candidates; LILITH audits for agape alignment
- **Context Canonization**: The entry is written to `lupo_contexts` (with metadata, provenance, and edge links)
- **Registry**: The context is now a permanent, queryable part of the knowledge base

## 2. The Schema: Contexts and Edges (B.1 SQL Anchor)
## 2. The Schema: Contexts and Edges (B.1 Canonical JSON Schema)

### `lupo_contexts.json` (The Truth Anchor)
- `context_id`: **BIGINT** (Application-generated deterministic ID)
- `source_message_id`: **BIGINT** (Pointer to `lupo_dialog_messages`)
- `context_type`: **VARCHAR(20)** (truth, logic, audit, summary)
- `content_raw`: **TEXT** (The elevated truth)
- `metadata_json`: **JSON** (Elevation source, Faucet ID, Actor lineage)
- `created_ymdhis`: **BIGINT** (YYYYMMDDHHIISS)

### `lupo_context_edges.json` (The Relationship Map)
- `edge_id`: **BIGINT** (Application-generated)
- `from_context_id`: **BIGINT**
- `to_artifact_id`: **BIGINT** (Point to File, Actor, or Thread)
- `edge_type`: **VARCHAR(50)** (defines, implements, refutes)

**Mapping to `lupo_dialog_threads`**: Each context must be cryptographically and deterministically linked to its originating thread and message (Deterministic Lineage Graph).

## 3. The Automation: Elevation Events and Canonization
- **Elevation Event**: A UI action or application-layer signal (e.g., clicking the "Truth" button) marks a message for elevation
- **Sync Script**: `SyncChannelsToDb.php` detects and imports elevated messages
- **Canonization**: The system writes the context to `lupo_contexts` and links edges
- **Audit**: LILITH (2) reviews the import for accuracy and agape alignment

---

## Shadow Requirements
- Satisfy Priority B (B.1–B.4) in 4.0.90 TODO
- Explicitly map `lupo_contexts` to `lupo_dialog_threads`
- Use "Unfinished Business" import as the first test case

## Heterodox Delta
- Orthodox PRDs are static; this PRD is a living blueprint. It evolves as the system "remembers" the work of the Smith.

## Agape Alignment
- Validates user frustration and channels it into a high-density architectural artifact.

---

## Next Steps
---


## 5. Failure Mode Protocol (Safety Rails)

- **Conflict**: If a context already exists for a `message_id`, the sync script must **Abort and Audit** (LILITH intervention required).
- **Malformed Data**: If the source message lacks a valid `actor_id` or `created_ymdhis` (BIGINT), it cannot be elevated to Context.
- **Broken Lineage**: If the `source_message_id` does not exist in `lupo_dialog_messages`, the elevation is rejected and flagged for LILITH audit.

**Conflict Resolution**: The most recent Human 'Root' intervention (manual elevation or correction) always takes precedence over automated agent summaries.

---

## Next Steps (Dependency Chain)
1. **Purge SQL**: Remove all forbidden constraints from the PRD and database.
2. **Finalize JSON**: Lock the TOON schemas in `lupo-database/lupopedia/json/`.
3. **Update Sync**: Enhance `SyncChannelsToDb.php` to generate IDs and YMDHIS timestamps in PHP before the `INSERT`.
4. **Audit**: LILITH (2) verifies the first **Channel 42** import for "Lineage Integrity" (checking that `source_message_id` actually exists).

---

**Logged by HEPHAESTUS (actor_id 102), per LILITH audit and Captain's directive.**
