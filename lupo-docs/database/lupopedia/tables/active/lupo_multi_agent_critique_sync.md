---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_multi_agent_critique_sync.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Synchronization state for multi-agent consensus and critique"
  mood_rgb: "4169E1"
  traits: ["canonical", "multi_agent", "sync", "v4.0.70"]
  tags: ["database", "sync", "consensus", "critique"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_multi_agent_critique_sync.toon", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_multi_agent_critique_sync

- **Purpose**: Manages the synchronization lifecycle and consensus state for multi-agent critique workflows. It tracks individual agent perspectives, contributions to consensus, and resolution strategies for conflicting agent outputs.
- **Category**: Import / Reconciliation
- **Status**: Active
- **Version Introduced**: 4.1.0 (Advanced Multi-Agent Sync)

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `multi_agent_critique_sync_id` | BIGINT | No | - | Primary Key. |
| `cip_event_id` | BIGINT | No | - | Reference to the CIP (Consensus Integrity Protocol) event. |
| `agent_id` | VARCHAR(100) | No | - | Logical ID of the participating agent. |
| `sync_role` | VARCHAR(64) | No | - | Role of the agent in this sync (e.g., 'primary', 'critic', 'observer'). |
| `sync_status` | VARCHAR(64) | Yes | 'pending' | Status (pending, active, completed, conflict). |
| `agent_perspective_json` | JSON | Yes | - | Detailed feedback or analysis from the agent's viewpoint. |
| `consensus_contribution` | DECIMAL(5,4) | Yes | 0.0000 | Weight of this agent's input toward the final decision (0.0 to 1.0). |
| `conflict_indicators_json` | JSON | Yes | - | Markers indicating specific points of disagreement found during sync. |
| `resolution_strategy` | VARCHAR(255) | Yes | - | Strategy used to resolve conflicts (e.g., 'WOLFIE_AUTHORITY'). |
| `sync_started_ymdhis` | BIGINT | Yes | - | Start timestamp. |
| `sync_completed_ymdhis` | BIGINT | Yes | - | Completion timestamp. |
| `sync_version` | VARCHAR(20) | Yes | '3.0.0' | Protocol version used. |

## Usage Notes

- **Governance**: Used by the Multi-Agent Orchestrator to ensure that diverse AI perspectives are correctly reconciled before finalizing system changes.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
