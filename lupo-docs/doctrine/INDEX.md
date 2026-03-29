---
lupopedia.headers:
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/INDEX.md"
  web_path: "http://www.lupopedia.com/lupopedia/doctrine/INDEX.md"
  last_modified_utc: "20260326"
  channel_id: 42
  actor_id: 1
  actor_name: "WOLFIE"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  # Execution context (optional, for audit)
  executed_by_agent: "wolfie-primary"
  executed_through_faucet: "cursor"
  effective_department: 0
  artifact_type: "doctrine"
  artifact_kind: "index"
  purpose: "Central index for Lupopedia engineering and architectural doctrines"
  tags: ["doctrine", "index", "architecture", "4.0.88"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/LUPOPEDIA_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/IDENTITY_MODEL_QUICKSTART_4.0.88.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/channels/appendix/HISTORY.md", type: "references", weight: 0.8 }

lupopedia.footer:
  last_verified: "20260326"
  last_verified_by_actor_id: 1
  last_verified_by_actor_name: "WOLFIE"
  last_verified_through_faucet: "cursor"
  orchestrator: "wolfie:root"
---

# Lupopedia Doctrine Index

## Purpose
Governs Semantic OS operations via non-negotiable architectural boundaries and engineering standards. All development must align with these rules to ensure multi-agent safety and long-term portability.

## Core Doctrines (4.0.88)

### 🏛️ **Foundational System**
1. **[Lupopedia Master Doctrine](LUPOPEDIA_DOCTRINE.md)** — The foundational rules of the system
2. **[Multi-Agent Coordination Doctrine](MULTI_AGENT_COORDINATION_DOCTRINE.md)** — 11 Primary Coordination Personas and agent ecosystem
3. **[Channel-Based Coordination Doctrine](CHANNEL_BASED_COORDINATION_DOCTRINE.md)** — Channel-based artifact management (replaces status-based coordination)

### 🆔 **Identity & Actor Model**
4. **[Identity Model Quickstart](IDENTITY_MODEL_QUICKSTART_4.0.88.md)** — Canonical guide to identity layers (Login Identity, Actor, Department, Agent, Faucet)
5. **[Actor Agent Login Identity Model](ACTOR_AGENT_AUTH_USER_MODEL.md)** — Detailed identity layer separation and session binding
6. **[Effective Actor Resolution](EFFECTIVE_ACTOR_RESOLUTION.md)** — Runtime actor selection and resolution paths
7. **[Identity Authority Doctrine](IDENTITY_AUTHORITY_DOCTRINE.md)** — Universal actor model and registry hierarchy

### 🗄️ **Database & Schema**
8. **[Database Doctrine](DATABASE_DOCTRINE.md)** — Core database principles and constraints
9. **[Table Ceiling Protocol](CASCADE_TABLE_CEILING_PROTOCOL.md)** — Governance of the 199-table limit
10. **[Schema and TOON Alignment](SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md)** — Database schema synchronization

### 🚰 **Execution & Faucets**
11. **[IDE Agent Continuity Protocol](IDE_AGENT_CONTINUITY_PROTOCOL.md)** — Cross-IDE agent coordination
12. **[Faucet Traceability Doctrine](FAUCET_TRACEABILITY_DOCTRINE.md)** — Execution surface attribution
13. **[Actor Facet Separation Doctrine](ACTOR_FACET_SEPARATION_DOCTRINE.md)** — Actor identity boundaries

### 🛡️ **Security & Governance**
14. **[LEXA Gateway Integration](LEXA_GATEWAY_INTEGRATION.md)** — Security enforcement integration
15. **[Authorization Doctrine](AUTHORIZATION_DOCTRINE.md)** — Permission and access control
16. **[Ethical State Markers Doctrine](ETHICAL_STATE_MARKERS_DOCTRINE.md)** — Ethical framework for operations

### 📋 **Development Standards**
17. **[Development Workflow Doctrine](DEVELOPMENT_WORKFLOW_DOCTRINE.md)** — Engineering processes and standards
18. **[Versioning Doctrine](VERSIONING_DOCTRINE.md)** — Version management and compatibility
19. **[Doctrine File Structure](DOCTRINE_FILE_STRUCTURE.md)** — Documentation organization standards

## Legacy Systems (Deprecated)

### ⚠️ **FLARE/FLIP - Deprecated**
- **FLARE**: File-level metadata system → **Replaced by LUPOPEDIA HEADERS**
- **FLIP**: File-Level Inference Protocol → **Replaced by LUPOPEDIA HEADERS**
- **FLP**: Federated Likeness Protocol → **Replaced by channel-based coordination**

🔗 **Current System**: [LUPOPEDIA_HEADERS](LUPOPEDIA_HEADERS/README.md) (format, tooling)  
🔒 **Binding doctrine (single file):** [`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md) — field matrix + DB mapping; [`LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md`](LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md) is a stable alias only.
📎 **Taxonomy quick reference:** [LUPOPEDIA_HEADERS/TAXONOMY_REFERENCE.md](LUPOPEDIA_HEADERS/TAXONOMY_REFERENCE.md) (schema + cross-field table; binding text stays in root file).
📋 **Deprecation Guide**: [DEPRECATION_FLARE_FLIP_FLP.md](LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md)

## Context & History

> [!TIP]
> To understand the "why" behind these strict doctrines, review the project's evolution from a 2002 open-source live help system to a modern Semantic OS.

*   **[Full Project History](../channels/appendix/HISTORY.md)** — From Crafty Syntax to Lupopedia
*   **[Founder's Note](../channels/appendix/appendix/FOUNDERS_NOTE.md)** — The personal narrative behind the architecture

## Navigation Layers

1. **Identity**: Login Identity → Actor → Department → Agent → Faucet
2. **Coordination**: Multi-agent personas and channel-based workflows
3. **Channels**: Message routing, broadcasts, threads, direct messages
4. **Database**: Schema constraints, TOON synchronization, table limits
5. **Security**: Authorization, boundaries, ethical frameworks
6. **Development**: Workflow, versioning, documentation standards

---

*Last updated: 2026-03-26 (v4.0.88)*  
*Maintained by: WOLFIE (actor_id 1) through cursor faucet*
