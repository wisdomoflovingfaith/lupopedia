---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401010000"
  file_path_from_root: "lupo-docs/doctrine/ACTOR_AGENT_DISTINCTION.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/ACTOR_AGENT_DISTINCTION.md"
  last_modified_utc: "20260401010000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "actor-agent-distinction"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "Canonical distinction between Agents (templates) and Actors (runtime instances)"
  tags:
  - "doctrine"
  - "actors"
  - "agents"
  - "identity"
  - "two-layer-model"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Core identity tables for actors"
    - to: "lupo-docs/prd/07_agents_faucets.md"
      type: references
      weight: 1.0
      reason: "Agent definitions"
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
      reason: "Actor specification"
    - to: "lupo-docs/prd/32_actor_authority_agent_roles.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260401010000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
  next_action:
    - "Read this before writing code that touches agents or actors"
    - "Never treat agents and actors as synonyms"
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
| **Storage** | `lupo-agents/{agent_key}/` | `lupo-actors/YYYY/MM/{actor_id}/` |
| **Versioning** | Yes (versions/ directory) | No (learning is runtime) |
| **Learns** | Never | Always (from department context) |
| **Department** | None | Always has department |
| **User** | None | Linked via lupo_actor_auth_users |
| **Lifecycle** | Permanent | Created, used, archived |

## Agent Directory Structure (Template)

```
lupo-agents/{agent_key}/
├── agent.json           # Core metadata (REQUIRED)
├── capabilities.json    # Agent capabilities (REQUIRED)
├── properties.json      # Agent properties (REQUIRED)
├── system_prompt.txt    # System prompt (REQUIRED)
├── versions/            # Version history (optional)
├── api/                # API endpoints (RECOMMENDED)
├── assets/             # Images, icons (RECOMMENDED)
├── components/         # UI components (RECOMMENDED)
├── context/            # Context providers (RECOMMENDED)
├── data/               # Static data (RECOMMENDED)
├── hooks/              # Reusable logic (RECOMMENDED)
├── includes/           # Shared helpers (RECOMMENDED)
├── pages/              # Page logic (RECOMMENDED)
├── tools/              # Tool definitions (RECOMMENDED)
└── utils/              # Utility functions (RECOMMENDED)
```

## Actor Workspace Structure (Instance)

```
lupo-actors/
├── <actor_id>/ # System actors (actor_id < 2026)
│   ├── agent_link.json # Reference to source agent
│   ├── memory.json # Learned from department context
│   ├── context.json # Department and user context
│   └── preferences.json # User-specific preferences
│
└── YYYY/ # Year (for runtime actors)
    └── MM/ # Month
        └── <actor_id>/ # Actor ID (deterministic)
            ├── agent_link.json # Reference to source agent
            ├── memory.json # Learned from department context
            ├── context.json # Department and user context
            └── preferences.json # User-specific preferences
```

## Actor Creation Flow

1. User selects an Agent (e.g., WOLFIE)
2. User is in a Department (e.g., Sales)
3. System creates an Actor from that Agent for that Department
4. Actor gets deterministic ID: YYYYMMDDHHIISS + 4 random digits
5. If actor_id < 2026: workspace at `lupo-actors/{actor_id}/`
6. If actor_id >= 2026: workspace at `lupo-actors/YYYY/MM/{actor_id}/`
7. Actor inherits all agent capabilities from `lupo-agents/{agent_key}/`
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

- `lupo-docs/prd/01_core_identity.md` — Actor tables and identity
- `lupo-docs/prd/07_agents_faucets.md` — Agent definitions
- `lupo-docs/prd/15_actors.md` — Actor specification
- `lupo-rules/root/WOLFIE_DOCTRINE.md` — WOLFIE engineering philosophy

---

**Last verified**: 2026-04-01
**Next review**: As identity patterns evolve
**Maintainer**: WOLFIE (actor_id 1) - System orchestration
