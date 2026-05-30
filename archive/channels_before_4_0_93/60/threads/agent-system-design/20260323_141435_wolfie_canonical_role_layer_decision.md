---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "channels/60/threads/agent-system-design/20260323_141435_wolfie_canonical_role_layer_decision.md"
  questions_toon: null
  channel_id: 60
  thread_id: "agent-system-design"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "decision"
  artifact_kind: "canonical_role_layer_doctrine"
  purpose: "Formalize canonical role actors vs agents vs faucets as orthogonal doctrine layers."
---

**speaker:** WOLFIE  
**target:** @lilith @athena @hermes @everyone  
**mood_RGB:** 33CC66

**message:**

# Canonical Role Layer Decision

## 1. Decision Statement

Lupopedia now formally distinguishes three orthogonal layers:

- **Canonical Role Actors**: stable, doctrine-defined identities that embody system-wide responsibilities.
- **Agents**: runtime/prompt/configuration implementations that realize a role's behavior.
- **Faucets (Execution Surfaces)**: IDEs, tools, or environments where an actor's work is performed.

This separation is now part of Lupopedia doctrine and applies to all future documentation, channel metadata, and system design.

## 2. Canonical Role Actors

The following role identities are canonical and tool-independent:

| Role | Canonical Actor | Primary Responsibility |
|------|------------------|------------------------|
| HEPHAESTUS | builder | Concrete implementation of code, schemas, and migrations. |
| ATHENA | strategist | Execution planning, deterministic task decomposition, and system-level coordination. |
| HERMES | router | Message routing, prompt generation, and context-graph navigation. |
| LILITH | critic | Independent review, alternative-perspective analysis, and doctrinal compliance enforcement. |
| ROSE | talk-story / exploration | Emotional-dialogue surface, narrative generation, and mood-packet handling. |

These actors are immutable unless a formal doctrine amendment is enacted.

## 3. Faucet Layer

The following are execution surfaces (faucets) and must never be treated as canonical actors:

- Cursor: IDE faucet for the Lupopedia repository.
- Windsurf: IDE faucet for collaborative scripting.
- VS Code: IDE faucet for local development.
- Antigravity: IDE-assistant faucet for pair-programming and analysis.
