---
lupopedia.headers:
  when_updated: "20260404160851"
  file_path_from_root: "lupo-docs/implementations/service_agents/decisions/20260404_160645_DECISION_php_first_service_agents.md"
  last_modified_utc: "20260404160851"
  artifact_type: documentation
  artifact_kind: decision
  purpose: "Record choice to bind IRIS/ANUBIS/ROSE/THOTH/KAIROS behavior to PHP services with LLM optional second"
  actor_id: 102
lupopedia.footer:
  last_verified: "20260404"
  verified_by:
    identity_type: actor
    actor_id: 102
  verified_via:
    type: faucet
    faucet_slug: cursor
---

# file: DECISION php_first_service_agents — web_path: (implementation)

# Decision: PHP-first service agents (LLM second)

**UTC:** 20260404160851  
**Scope:** IRIS, ANUBIS, ROSE, THOTH, KAIROS

## Decision

**Behavior and policy** for these agents **must** be implemented in **PHP** (routes, services, validators, SQL). **LLM** prompts and external models are **not** the primary control plane; they may **only** run **after** PHP has enforced auth, **`actor_id`**, channel rules, and schema-backed facts.

## Rationale

1. **Determinism and audit** — Custody (ANUBIS), consolidation (KAIROS), and schema truth (THOTH) require reproducible, reviewable code paths.
2. **Shared hosting** — No dependency on opaque prompt-only behavior for core integrity.
3. **Identity clarity** — **`actor_id`** on edges and messages must not be confused with “user is chatting with this agent” when the agent is a **service**.

## Alternatives rejected

- **Prompt-only** enforcement of custody or memory lifecycle (non-auditable, drift-prone).
- **Default** routing of visitor chat to IRIS/ANUBIS/ROSE/THOTH/KAIROS without explicit product wiring.

## Artifacts

- **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — §5.10
- **`lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md`**

This output complies with Lupopedia Constitutional Root Rules.
