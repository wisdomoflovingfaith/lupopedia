---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/status/CHANNEL_42_HYBRID_ACTOR_IDENTITY_RESEARCH_4.0.59.md"
  last_modified_utc: "20260306"
  system_version: "4.0.59"
  channel_id: 42
  actor_name: "antigravity"
  artifact_type: "status_report"
  artifact_kind: "documentation"
  purpose: "Research report on actor nature, hybrid identity models, and classification fields for v4.0.59 context resolution"
  mood_rgb: "4169E1"
  traits: ["research", "identity", "canonical", "v4.0.59"]
  tags: ["actor_type", "hybrid_identity", "paired_actor", "whoami", "context"]
  lupo_agent: "antigravity"

lupopedia.footer:
  version: "4.0.59"
  last_verified: "20260306"
  last_verified_by: "antigravity"
---

# Status Report: Hybrid Actor Identity Research (v4.0.59)

## 1. Executive Summary

Lupopedia **already encodes** actor nature and classification through a multi-layered system of fields and naming conventions.

*   **Does the system already distinguish human vs AI?** Yes, primarily through the `actor_type` field in the `lupo_actors` table and the actor registry.
*   **Where is it stored?** It is persisted in the database (`lupo_actors.actor_type`), defined in the canonical registry (`registry.json`), and used in code (e.g., `IRIS` gateway filters for `agent` or `service`).
*   **Is "Hybrid" supported?** Yes, the system uses the `paired_actor_id` field to link AI/Tool actors to a human operator (e.g., IDE agents are paired with the Captain).

## 2. Evidence Inventory

The following artifacts were reviewed to ground this research:

*   **TOON Files:** `lupo_actors.toon.json`, `lupo_agents.toon.json`, `lupo_sessions.toon.json`, `lupo_registry.toon.json`.
*   **Registry:** `lupo-database/lupopedia/actors/registry.json`.
*   **SQL Seed Files:** `lupo-database/lupopedia/mysql/seed/seed_actors_agents_4.0.45.sql`.
*   **Doctrine Docs:** `docs/doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md`.
*   **PHP Code:** `lupo-includes/class-iris.php`, `lupo-includes/modules/actors/actors-controller.php`.

## 3. Findings

### Actor-Level Identity Findings
The `actor_type` column (or `type` key in `registry.json`) is the primary classification. Confirmed values:
*   `system`: Core kernel actor (e.g., actor 0).
*   `agent`: Autonomous AI agent (e.g., Wolfie, Lilith, Antigravity).
*   `ide_agent`: Specialized agents used by IDE extensions (e.g., Cursor, Windsurf).
*   `human`: Standard human operator (e.g., Captain).
*   `user`: General user (often legacy or test users).
*   `service`: (from IRIS code) Represents non-agent system services.

### Session-Level Identity Findings
Sessions (`lupo_sessions`) currently bind to an `actor_name` but do not have a separate `session_nature` column. However, the `metadata` (JSON) column is available for session-specific context. The `system_context` field also exists but its usage is currently minimal.

### TOON-Related Findings
TOON metadata for `lupo_actors` includes:
*   `is_agent`: Explicit boolean flag.
*   `is_kernel`: Explicit boolean flag for system actors.
*   `paired_actor_id`: Stores the link between an agent/IDE and its human controller. This is the implementation of the **Hybrid** model.

### Registry Findings
`registry.json` includes the `type` field for every actor. Example for `cursor`:
```json
"cursor": {
  "actor_name": "cursor",
  "actor_id": 1003,
  "type": "agent", // This maps to actor_type
  "paired_actor_id": 10000 // In metadata_json or implicit in SQL
}
```

## 4. Confirmed Fields / Concepts

| Field | Source | Meaning |
|-------|--------|---------|
| `actor_type` | `lupo_actors` / `registry.json` | The primary nature (human, agent, etc.) |
| `is_agent` | `lupo_actors` | Boolean flag for AI persona |
| `paired_actor_id` | `lupo_actors` | Links agent/IDE to human operator (Hybrid link) |
| `archetype` | `lupo_agents` | Detailed AI role (e.g., "Critical Review") |
| `delegation_chain`| FLARE Headers | Colon-separated path of identity (e.g., `agent:human`) |

## 5. Gaps / Ambiguities

*   **Session Mode:** While `paired_actor_id` exists in the actor record, the session does not explicitly state which "mode" it is operating in (e.g., "AI driving", "Human driving", or "Shared"). It assumes the nature of the actor.
*   **Hybrid Definition:** The term "hybrid" is used conceptually in project chatter but is implemented as a relationship (`paired_actor_id`) rather than a value in the `actor_type` column.

## 6. Recommendation for `whoami`

The `lupo whoami` output **should include** the actor classification. This provides immediate context for the runtime nature of the session.

Recommended output expansion:
```text
Current Actor: cursor (1003)
Nature: ide_agent [Paired: captain]
Workspace: /lupo-actors/cursor/
...
```

## 7. Recommendation for FLARE / Sessions

*   **Actor Registry:** Continue using `actor_type` and `paired_actor_id`.
*   **`lupo_sessions`**: Add an optional `session_mode` field or metadata key if we need to distinguish between an AI acting autonomously vs an AI acting on behalf of a human in a specific session.
*   **FLARE Headers**: Add `actor_type` and `actor_nature` to the standard header block for better machine readability of files created by different natures.

## 8. Final Recommendation (v4.0.59)

The canonical field for classification is **`actor_type`**.

To address the Hybrid/Delegated distinction, we should adopt the following convention for `actor_nature` (derived field):
1.  If `actor_type` is `human` → Nature is `human`.
2.  If `actor_type` is `agent` and `paired_actor_id` is 0 → Nature is `autonomous_agent`.
3.  If `actor_type` is `agent` or `ide_agent` and `paired_actor_id` > 0 → Nature is `hybrid` or `delegated_agent`.

This logic should be centralized in `ActorService` so that `lupo whoami` and FLARE validation can consume it consistently.

**Recommendation:** Do NOT add a "hybrid" value to the `actor_type` column. Keep the types pure and derive "hybrid" from the existence of a `paired_actor_id`.
