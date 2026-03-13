---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/status/CHANNEL_42_WHOAMI_DUAL_IDENTITY_RESEARCH_4.0.59.md"
  last_modified_utc: "20260306"
  system_version: "4.0.59"
  channel_id: 42
  actor_name: "antigravity"
  artifact_type: "status_report"
  artifact_kind: "documentation"
  purpose: "Formalizing the dual-identity context resolution (human vs agent) for whoami command"
  mood_rgb: "4169E1"
  traits: ["research", "identity", "dual-layered", "v4.0.59"]
  tags: ["whoami", "actor_nature", "lupo_agent", "paired_actor_id", "context"]
  lupo_agent: "antigravity"

lupopedia.footer:
  version: "4.0.59"
  last_verified: "20260306"
  last_verified_by: "antigravity"
---

# Status Report: WHOAMI Dual-Identity Research (v4.0.59)

## 1. Executive Summary

Lupopedia sessions involve a dual-layered identity model that distinguishes between the **human operator** (legal/root responsibility) and the **active agent** (the persona/logic layer performing the action).

*   **Do we need both?** Yes. Relying on a single `actor_name` obscures whether an action was performed directly by a human or delegated to an AI.
*   **Does the system already support this?** Partially. The `paired_actor_id` field in `lupo_actors` provides the structural link, but runtime context resolution (`whoami`) does not yet expose it clearly.
*   **What is missing?** Explicit reporting of the **Human Identity** and **Active Agent** in the CLI and a standardized way to derive "Active Agent" when a human is operating multiple personas in one session.

## 2. Evidence Reviewed

*   **TOON Files:** `lupo_actors.toon.json`, `lupo_sessions.toon.json`, `lupo_agents.toon.json`.
*   **SQL Schema:** `install_new_lupopedia.sql`, `seed_actors_agents_4.0.45.sql`.
*   **Doctrine Docs:** `ACTOR_PRIMARY_KEY_DOCTRINE.md`, `lupopedia_whoami_readme.md`.
*   **Code:** `ContextResolver.php`, `ActorLookup.php`, `AnubisHeaderFallback.php`, `IRIS` (class-iris.php).
*   **Session Data:** `lupo-sessions/actor_1003_default.json`.

## 3. Findings

### Human/Operator Identity Findings
*   The canonical field for human identity is **`paired_actor_id`** in the `lupo_actors` table.
*   For all `ide_agent` entries (e.g., Kiro, Cursor), `paired_actor_id` is set to `10000` (Captain).
*   If an actor's `actor_type` is `human`, they are their own human identity.

### `lupo_agent` Findings
*   The `lupo_agent` field is a **runtime persona indicator**. It exists in FLARE headers and is used system-wide to identify which AI agent is "writing" or "thinking."
*   In the `lupo_agents` table, `agent_key`/`agent_name` matches the `actor_name` for agents.
*   Current logic assumes `Active Agent` == `Effective Actor` if the actor is an agent.

### Session Findings
*   `lupo_sessions` tracks the **Effective Actor** (the one currently logged in/active).
*   The `system_context` and `metadata` JSON columns are available to store "Session Mode" or an "Active Agent override" if a human wishes to speak as an agent without switching full actors.

### FLARE / Actor Findings
*   FLARE handles delegation via the `delegation_chain` (e.g., `cursor:captain`).
*   The `actor_name` is now the primary key, making these chains human-readable.

## 4. Confirmed Fields

| Logic Layer | Field Name | Source | Meaning |
|-------------|------------|--------|---------|
| **Effective Actor** | `actor_name` | `lupo_sessions` | The identity currently executing code. |
| **Human Identity** | `human_actor_name` | Derived via `paired_actor_id` | The human ultimately responsible for the session. |
| **Active Agent** | `lupo_agent` | FLARE / `lupo_agents` | The specific AI persona layer currently active. |
| **Session Mode** | `session_nature` | Derived (see report) | Classification: `human_direct`, `autonomous_agent`, or `hybrid`. |

## 5. Gaps / Ambiguities

*   **Runtime Agent Selection:** If a Human Actor (Captain) uses a Tool (Antigravity), the system needs to decide if the session `actor_name` switches to `antigravity` OR if it stays `captain` with `lupo_agent: antigravity`.
*   **Protocol Consistency:** `AnubisHeaderFallback` hardcodes `lupo_agent: anubis` while `delegation_chain` uses `1001:19:10000`. We need to harmonize name-based vs ID-based chains.

## 6. Recommendation for `whoami`

The `whoami` output should be restructured to report the dual-identity clearly.

**Recommended Human-Readable Model:**
```text
Actor: cursor (1003)
Nature: ide_agent
---
Human Identity: captain (10000) [via paired_actor_id]
Active Agent: cursor (1003)      [via effective actor]
Session Mode: hybrid
```

**Recommended JSON Model:**
```json
{
  "actor_name": "cursor",
  "actor_id": 1003,
  "human_identity": {
    "actor_name": "captain",
    "actor_id": 10000
  },
  "active_agent": {
    "agent_name": "cursor",
    "actor_id": 1003
  },
  "session_mode": "hybrid"
}
```

## 7. Recommendation for Storage

*   **Canonical Human Identity:** Always derived from `lupo_actors.paired_actor_id`. Do NOT store a separate `human_id` in sessions if it can be derived, to avoid drift.
*   **Canonical Active Agent:**
    *   If the Effective Actor is an agent → Active Agent = Effective Actor.
    *   If the Effective Actor is a human → Active Agent = `none` (unless `lupo_agent` is set in session metadata).
*   **FLARE Headers:** Always use name-based `delegation_chain` (Agent:Human).

## 8. Final Recommendation

For v4.0.59, `lupo whoami` and `ContextResolver` should be updated to:
1.  Resolve the **Effective Actor** from the session (current behavior).
2.  If the actor has a `paired_actor_id` > 0, resolve that actor as the **Human Identity**.
3.  If the current actor is of type `agent/ide_agent`, report them as the **Active Agent**.
4.  Expose all three layers (Effective, Human, Agent) in the context output.

This formalizes the **Dual-Identity Model** where every agent action is anchored to a human, and every human has the ability to act through an agent.
