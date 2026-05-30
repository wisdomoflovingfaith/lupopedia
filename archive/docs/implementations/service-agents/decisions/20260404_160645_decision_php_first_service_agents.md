---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260404160851"
  file_path_from_root: "docs/implementations/service_agents/decisions/20260404_160645_DECISION_php_first_service_agents.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: decision
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
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

- **`docs/prd/00_root_constitutional_system_requirements.md`** — §5.10
- **`docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md`**

This output complies with Lupopedia Constitutional Root Rules.
