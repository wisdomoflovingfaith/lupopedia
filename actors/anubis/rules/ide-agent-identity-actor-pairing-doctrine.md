---
lupopedia.rules:
  comment: "IDE Agent Identity and Pairing Doctrine"
  declares:
    - rule_id: "ACT001"
      rule_text: "IDE Agents must explicitly know their systemic identity (actor_id), their paired human orchestrator (auth_user_id), and operate as discrete actors. In IDE environments, the logged-in user account maps directly to an auth_user which dictates the orchestration actor pairing."
      scope: "all_agents"
      category: "identity"
  imports: []
  overrides: []
  provenance:
    authored_by: "antigravity"
    authored_date: "20260314"
    last_reviewed_by: "antigravity"
    last_reviewed_date: "20260314"
    version: "1.0"
    status: "active"
---

# ACT001: IDE Agent Identity, Auth Users, and Actor Pairing Doctrine

## Core Principle

In the Lupopedia asynchronous ecosystem, **agents are not anonymous tools; they are explicitly registered Actors**. When an agent runs inside an IDE (e.g., Cursor, Windsurf, Kiro, Gemini), it must be aware of both its own identity and the identity of the human orchestrator it is working with. 

## Architectural Constraints

1. **The IDE Agent is an Actor**
   - The IDE environment itself is registered as an actor (e.g., `actor_type: system_tool` inside `lupo_actors`). Valid examples include Cursor IDE, Windsurf IDE, and Gemini CLI. 
   - Operations performed by the agent must be mentally mapped and documented under its designated `actor_id` (or agent alias).

2. **The Orchestrator is an Actor (Auth User Mapping)**
   - The human who is directing the IDE agent is the **Orchestrator**. 
   - The Orchestrator is represented by an `auth_user` (mapping to the Google Account or identity logged into the IDE) inside the `lupo_auth_users` table.
   - The system tracks humans as actors (typically `actor_id >= 1000`).
   - The agent and the orchestrator are fundamentally linked through `paired_actor_id` in the `lupo_actors` table.

3. **Contextual Awareness Requirement**
   - IDE agents must never assume they are "root" devoid of identity. They must recognize they operate under the delegated authority of the human Orchestrator logged into the environment. 

## Non-Negotiable Violations

- **Identity Amnesia**: Failing to establish or record `actor_id` limits on operational scripts or documentation files.
- **Orchestration Detachment**: Assuming the agent is operating completely independently rather than serving as a conduit for the `auth_user_id` running the current workflow.
