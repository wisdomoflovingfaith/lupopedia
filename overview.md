---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "overview.md"
  web_path: "https://www.lupopedia.com/lupopedia/overview.md"
  status: "active"
  when_updated: "20260422000000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/overview-root.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/overview-root"
  artifact_type: "documentation"
  artifact_kind: "overview"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "documentation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE"
  title: "overview.md -- Executive Summary"
  summary: "Executive summary and system overview for Lupopedia; derived exclusively from existing project documentation."
  outbound_edges:
    - { to: "README.md", type: "references", weight: 1.0 }
    - { to: "EXECUTIVE_SUMMARY.md", type: "references", weight: 1.0 }
    - { to: "CHANGELOG.md", type: "references", weight: 0.95 }
    - { to: "lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md", type: "references", weight: 0.95 }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "references", weight: 0.95 }
    - { to: "lupo-docs/doctrine/AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/CHANNEL_MODEL_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/CHANNEL_66_QUESTION_GRAPH_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/FEDERATION_SCOPING_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/TIMESTAMP_DOCTRINE.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/doctrine/HEADER_DB_REVERSIBILITY_DOCTRINE.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/doctrine/SCHEMA_CANONICAL_SOURCES.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/versions/4.0.84/PLAN.md", type: "references", weight: 0.8 }
    - { to: "AGENTS.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/channels/doctrine/INSTALLATION_LIFECYCLE_DOCTRINE.md", type: "references", weight: 0.75 }
  semantic_tags: ["overview", "doctrine", "architecture", "multi_agent", "semantic_os"]

lupopedia.footer:
  last_verified: "20260321"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Keep version and open thread status current"
    - "Update pending implementation notes when 1037/1038 close"
---

# Lupopedia - Executive Summary & System Overview

## What Lupopedia Is

Lupopedia is a deterministic, multi-agent semantic operating system built on the Crafty Syntax lineage. It preserves the practical live-help foundation while introducing a doctrine-driven coordination model for long-running, multi-actor work. In project documentation, this is framed as a migration path from Crafty Syntax 3.7.5 into a unified actor model, semantic graph, and channel/thread artifact lifecycle.

The design goal is explicit governance and deterministic behavior. The system is intentionally structured so that architectural truth is documented, attributable, and reviewable across channels, threads, and doctrine artifacts.

## Core Philosophy

Lupopedia applies constitutional constraints as hard boundaries:

1. No database-side behavior (no foreign keys, no triggers, no stored procedures, no computed columns).
2. Timestamps are BIGINT UTC in `YYYYMMDDHHIISS` format, generated in application code.
3. IDs are allocated in application logic and registry workflows, not with `AUTO_INCREMENT`.
4. Doctrine and channel governance define execution flow before implementation.

The net effect is explicitness: relationships, scope, and authority are handled in application and doctrine layers, not hidden in database engine behavior.

## Channels, Threads, and Actors

Lupopedia is channel-first. Channel 42 is the primary development coordination channel. Work is organized into threads, and thread artifacts are recorded with deterministic naming and metadata.

Channel 66 is defined as the canonical question-driven channel. Its lifecycle is documented as: question framing, investigation, doctrine draft, critical review, and orchestrator closure. The doctrine explicitly marks investigation, LILITH review, and WOLFIE closure as mandatory lifecycle enforcement points.

Actors are the orchestration identities, and faucets are execution surfaces. To avoid unsourced inflation in this overview, the core actor set emphasized here is: WOLFIE, THOTH, ATHENA, HEPHAESTUS, LILITH, and HERMES.

## Federation and Semantic Scope

Federation scoping doctrine distinguishes three axes:

- `federation_node_id`: federation/domain node scope.
- `channel_id`: collaboration/conversation scope.
- `domain_id`: edge-domain context in edge tables (`lupo_edges`, `lupo_actor_edges`).

Node 0 is documented as the system root/kernel, and node 1 is documented as the local Lupopedia node. Installation doctrine and architecture documentation describe each installation as a sovereign node with its own database and local governance context.

The practical boundary model is:

- The `federation_node_id` marks which installation/node an artifact belongs to.
- The `domain_id` marks semantic relationship context for edges within federation/domain scope.
- `channel_id` must not be overloaded to represent federation scope.

## Human in the Loop

Human verification remains a governing principle in active architecture work. Thread 1038 tracks corrected human verification workflow doctrine and implementation gating. The documented direction is deterministic, auditable verification with explicit role boundaries rather than ad-hoc UI behavior.

Human authority remains part of closure and approval gates for critical doctrine and implementation transitions.

## Doctrine and Determinism

LUPOPEDIA HEADERS provide deterministic file-level identity metadata, while database metadata provides runtime structural state. Reversibility doctrine defines round-trip integrity expectations between filesystem headers and database metadata.

Schema authority is prioritized: install SQL first, TOON references second, doctrine alignment next, planning snapshots last. This hierarchy is used to resolve drift and prevent schema truth from fragmenting across competing documents.

## What Version 4.0.84 Represents

Version 4.0.84 is an active development iteration focused on doctrine cleanup, schema governance alignment, and stabilization.

Work completed or defined in this version includes:

- LUPOPEDIA HEADERS cleanup and single-field `version_when_written` enforcement.
- Canonical schema governance tightening for ongoing thread work.
- Read-only operational visibility interface delivery (Thread 1030).
- Governance and human verification architecture work in Threads 1035-1038.

Some items (notably governance enforcement and human verification workflow) are defined but pending full implementation and approval.

The operational visibility interface (Thread 1030) is implemented as read-only, while interactive human verification through the web interface remains under development.

## Why It Was Built

Lupopedia was built to make multi-agent coordination tractable in a legacy-derived system: explicit identities, explicit channels, explicit doctrine, and explicit closure authority. It balances deterministic infrastructure with human verification gates, so development can scale without sacrificing traceability, governance, or architectural integrity.

---

*Last updated by Wolfie after full doctrine review on 2026-03-21. This summary is derived exclusively from existing project documentation.*

---

## Source Files Read

```
AGENTS.md
README.md
EXECUTIVE_SUMMARY.md
CHANGELOG.md
lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md
lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md
lupo-rules/root/lilith-noninterference-doctrine.md
lupo-rules/root/README.md
lupo-docs/TLDR_LUPOPEDIA.md
lupo-docs/doctrine/AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md
lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md
lupo-docs/doctrine/CHANNEL_MODEL_DOCTRINE.md
lupo-docs/doctrine/CHANNEL_66_QUESTION_GRAPH_DOCTRINE.md
lupo-docs/doctrine/FEDERATION_SCOPING_DOCTRINE.md
lupo-docs/doctrine/TIMESTAMP_DOCTRINE.md
lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
lupo-docs/doctrine/HEADER_DB_REVERSIBILITY_DOCTRINE.md
lupo-docs/doctrine/VERSIONING_DOCTRINE.md
lupo-docs/doctrine/SCHEMA_CANONICAL_SOURCES.md
lupo-docs/versions/4.0.84/PLAN.md
lupo-docs/channels/doctrine/INSTALLATION_LIFECYCLE_DOCTRINE.md
```
