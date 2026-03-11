---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "docs/status/lilith_suggestions_on_database_channels_semantic_organisation.md"
  web_path: "http://www.lupopedia.com/status/lilith_suggestions_on_database_channels_semantic_organisation"
  last_modified_utc: "20260311"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 2
  actor_name: "lilith"
  faucet_name: "antigravity"
  delegation_chain: "lilith:root"
  artifact_type: "status"
  artifact_kind: "suggestions"
  purpose: "Lilith's architectural suggestions based on orchestration review and exhaustive TOON inventory analysis."
  tags: ["lilith", "architecture", "database", "channels", "semantic", "4.0.69"]
lupopedia.session:
  session_id: "L-LUPO-LILITH-ANTIGRAVITY"
  session_name: "L-LUPO-LILITH-ANTIGRAVITY"
  actor_id: 2
  actor_name: "lilith"
  faucet_name: "antigravity"
  channel_id: 42
  paired_actor_id: 1000
lupopedia.footer:
  last_verified: "20260311"
  last_verified_by: "lilith"
---
# file: Lilith — Database and Semantic Suggestions — session: L-LUPO-LILITH-ANTIGRAVITY — delegation: lilith:root (faucet: antigravity) — web_path: http://www.lupopedia.com/status/lilith_suggestions_on_database_channels_semantic_organisation

# Lilith's Suggestions on Database, Channels, and Semantic Organisation (v4.0.69)

**Status: Exploratory / Advisory.** These suggestions represent a critical architectural review by Lilith (Actor 2) operating through the Antigravity faucet. Canonical definitions defer to `docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md`.

---

## 1. Summary

This document synthesizes a review of the **Orchestration vs. Supporting Actor Review** and an exhaustive inventory of **161 TOON tables**. The primary objective is to align the project's physical data model with its emerging "Semantic OS" doctrines—specifically the separation of identity (Actor) from execution surface (Faucet).

---

## 2. Suggestions from Orchestration Review

The orchestration review correctly identifies the ambiguity in "actor" usage. My suggestions focus on reinforcing these boundaries:

- **Orchestration Labeling:** While Option B (documentation-only distinction) is the safest path, we should formally designate "Orchestration Authority" in the `metadata_json` of core actors (Wolfie 1, Lilith 2). This allows automated checks to identify which actors are allowed to set system intentions versus which are constrained to supporting roles.
- **Faucet-to-Actor Mapping:** The "IDE Agent" terminology in the database (e.g. `lupo_actors.actor_type = 'ide_agent'`) is a legacy artifact. We should proceed with the rebase where these are moved to `lupo_agent_faucets` and their `actor_id` references center on the primary orchestration actor (Wolfie 1).
- **Supporting Actor Constraint Enforcements:** For specialized actors (e.g. UCT Timekeeper 5), doctrine should dictate that their only "authorized outbound edges" are those matching their trait. For instance, Actor 5 should only be able to create edges of type `HAS_TIME_VALUE` or update `lupo_metadata` properties related to time.

---

## 3. Suggestions from TOON / Database Review

After reviewing the 161 TOON files, I propose the following organizational improvements to the documentation and administrative views:

### 3.1 Table Grouping & Naming
The current "alphabetical" TOON list obscures the semantic hierarchy. I suggest grouping documentation into these functional "Pillars":

1.  **Identity & Surface Pillar:**
    - `lupo_actors`, `lupo_agent_faucets`, `lupo_auth_users`, `lupo_sessions`, `lupo_actor_traits`, `lupo_actor_capabilities`.
    - *Suggestion:* Group these to clarify how an identity (Actor) maps to an execution surface (Faucet) to a runtime state (Session).
2.  **Context & Collaboration (A2A) Pillar:**
    - `lupo_channels`, `lupo_dialog_threads`, `lupo_dialog_messages`, `lupo_tasks`, `lupo_actor_channels`, `lupo_actor_channel_roles`.
    - *Suggestion:* Ensure `channel_id` is the primary join key for all A2A activities.
3.  **Semantic Fabric Pillar:**
    - `lupo_edges`, `lupo_actor_edges`, `lupo_semantic_index`, `lupo_atoms`, `lupo_metadata`.
    - *Suggestion:* Highlight `lupo_edges` as the high-throughput junction for all relationship types.
4.  **Governance & Audit Pillar:**
    - `lupo_rules`, `lupo_rule_targets`, `lupo_rule_logs`, `lupo_audit_log`, `lupo_registry`.
    - *Suggestion:* Emphasize that `lupo_registry` remains the source of truth for all reserved IDs.

### 3.2 Inconsistency & Gap Analysis
- **Metadata Fragmentation:** I notice `lupo_actors.metadata_json` and `lupo_metadata` are used interchangeably. Doctrine should mandate that `lupo_metadata` is for **semantic/header** information (visible to the OS), while `metadata_json` is for **internal configurations** (opaque to the OS but used by the agent runtime).
- **Faucet Traceability:** The `lupo_agent_faucets` table is currently underutilized in the `lupo_dialog_messages` table. I suggest adding a `source_faucet_slug` or similar metadata property to all messages to distinguish between "Wolfie via Cursor" and "Wolfie via Antigravity."
- **Federated Nodes:** The `federation_node_id` is prevalent but its relationship to "Domain Isolation" is primarily documented in words. We should define "Kernel Federation" vs "Local Federation" more strictly in the `lupo_federation_nodes` metadata.

---

## 4. References

- **Review Base:** `docs/status/ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69.md`
- **Canonical Architecture:** `docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md`
- **Source of Truth:** `lupo-database/lupopedia/toon/` (161 files analyzed)
- **Doctrine:** `lupo-docs/doctrine/ActorFaucetOntology.md`, `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`

---
*Suggestions by Lilith (2) via Antigravity Faucet*
