---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/actor_agent_distinction.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/actor_agent_distinction.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_key: actor-agent-distinction
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# Actor-Agent Distinction Doctrine

## The Core Principle

**Agents are templates. Actors are instances.**

This is the foundational distinction in Lupopedia's identity architecture.

## Quick Reference

| Aspect | Agent | Actor |
|--------|-------|-------|
| **Definition** | Immutable template | Runtime instance |
| **Analogy** | Class definition | Object instance |
| **ID Range** | 1-2025 (system), 2026+ (runtime) | Deterministic (YYYYMMDDHHIISS + 4 digits) |
| **Storage** | `agents/{agent_key}/` | `actors/YYYY/MM/{actor_id}/` |
| **Versioning** | Yes (versions/ directory) | No (learning is runtime) |
| **Learns** | Never | Always (from department context) |
| **Department** | None | Always has department |
| **User** | None | Linked via lupo_actor_auth_users |
| **Lifecycle** | Permanent | Created, used, archived |

## Agent Directory Structure (Template)

```
agents/{agent_key}/
+-- agent.json           # Core metadata (REQUIRED)
+-- capabilities.json    # Agent capabilities (REQUIRED)
+-- properties.json      # Agent properties (REQUIRED)
+-- system_prompt.txt    # System prompt (REQUIRED)
+-- versions/            # Version history (optional)
+-- api/                # API endpoints (RECOMMENDED)
+-- assets/             # Images, icons (RECOMMENDED)
+-- components/         # UI components (RECOMMENDED)
+-- context/            # Context providers (RECOMMENDED)
+-- data/               # Static data (RECOMMENDED)
+-- hooks/              # Reusable logic (RECOMMENDED)
+-- includes/           # Shared helpers (RECOMMENDED)
+-- pages/              # Page logic (RECOMMENDED)
+-- tools/              # Tool definitions (RECOMMENDED)
+-- utils/              # Utility functions (RECOMMENDED)
```

## Actor Workspace Structure (Instance)

```
actors/
+-- <actor_id>/ # System actors (actor_id < 2026)
|   +-- agent_link.json # Reference to source agent
|   +-- memory.json # Learned from department context
|   +-- context.json # Department and user context
|   +-- preferences.json # User-specific preferences
|
+-- YYYY/ # Year (for runtime actors)
    +-- MM/ # Month
        +-- <actor_id>/ # Actor ID (deterministic)
            +-- agent_link.json # Reference to source agent
            +-- memory.json # Learned from department context
            +-- context.json # Department and user context
            +-- preferences.json # User-specific preferences
```

## Actor Creation Flow

1. User selects an Agent (e.g., WOLFIE)
2. User is in a Department (e.g., Sales)
3. System creates an Actor from that Agent for that Department
4. Actor gets deterministic ID: YYYYMMDDHHIISS + 4 random digits
5. If actor_id < 2026: workspace at `actors/{actor_id}/`
6. If actor_id >= 2026: workspace at `actors/YYYY/MM/{actor_id}/`
7. Actor inherits all agent capabilities from `agents/{agent_key}/`
8. Actor learns from user interactions in its department context

## Department Context Effect

| Department | Actor Behavior |
|------------|----------------|
| Sales | Persuasive, urgency-driven, deal-focused |
| Engineering | Analytical, precise, architecture-focused |
| Support | Empathetic, patient, solution-focused |
| Security | Paranoid, thorough, threat-focused |

**Same agent. Different actors. Different behavior.**

## What IDE Agents Must Never Do

1. **Never treat agents and actors as synonyms** — they are different layers
2. **Never suggest storing learned behavior in agent configuration** — learning belongs in actor memory
3. **Never ignore department context** — it determines actor behavior
4. **Never modify agent templates for runtime behavior** — agents are immutable

## Related Documents

- `docs/prd/01_core_identity.md` — Actor tables and identity
- `docs/prd/07_agents_faucets.md` — Agent definitions
- `docs/prd/15_actors.md` — Actor specification
- `rules/root/WOLFIE_DOCTRINE.md` — WOLFIE engineering philosophy

---

**Last verified**: 2026-04-01
**Next review**: As identity patterns evolve
**Maintainer**: WOLFIE (actor_id 1) - System orchestration
