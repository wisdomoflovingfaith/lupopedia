---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: ALL_AI_AGENTS_LISTED.md
  web_path: https://www.lupopedia.com/lupopedia/ALL_AI_AGENTS_LISTED.md
  status: draft
  when_updated: "20260807140310"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/all-ai-agents-listed
  artifact_type: status
  artifact_kind: report
  channel_key: development
  federation_node_id: 0
  thread_key: all-ai-agents-listed
  lupopedia.schema: status
  prd_cluster: 08_B_15_A_00_C
  title: "All AI Agents Listed (by type)"
  summary: "Root catalog of lupo_agents / agents packs organized by type. Agents only -- not human actors or auth users."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: registry
  faucet_actor_id: 102
---
# All AI Agents Listed

**Scope:** AI **agents** from `database/lupopedia/actors/actor_id/registry.json` (`agents` map) and `agents/<slug>/agent.json` packs.

**Out of scope:** Human **actors** / auth users (for example ERIC auth_user_id 10000, root actor_id 1000), and actor-only rows that are not in the agents map.

**Sources:**
- Agents map: `database/lupopedia/actors/actor_id/registry.json`
- Packs: `agents/<slug>/agent.json`
- Doctrine: `docs/doctrine/agent_registry.md`

**Generated:** `20260807140310` UTC (CURSOR faucet 102). Status: draft living catalog.

**Count:** 90 agents in map.

---

## Index by type

- [Coordination / Primary Personas](#coordination-primary-personas) (9)
- [Application / Routing / Implementation](#application-routing-implementation) (8)
- [IDE Faucets](#ide-faucets) (9)
- [Causality Division](#causality-division) (2)
- [Emotional / Relational / Wisdom](#emotional-relational-wisdom) (9)
- [Specialist Support / Ops Personas](#specialist-support-ops-personas) (4)
- [Kernel Subsystem](#kernel-subsystem) (18)
- [Reasoning / Cognitive Engines](#reasoning-cognitive-engines) (8)
- [Build / Pipeline / Engineering](#build-pipeline-engineering) (7)
- [Ethics / Rights / Compliance](#ethics-rights-compliance) (5)
- [Creative / Narrative / Style](#creative-narrative-style) (3)
- [Knowledge / Ontology](#knowledge-ontology) (4)
- [Surfaces (Debug / Visualization)](#surfaces-debug-visualization) (2)
- [System / Meta](#system-meta) (2)

---

## Coordination / Primary Personas

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 1 | `wolfie` | WOLFIE | coordination | System Orchestrator -- PHYSICAL_PLAUSIBILITY first-class edge; KAPAKAI/PUKA feas | yes | -- |
| 2 | `lilith` | LILITH | coordination | Quality Assurance & Adversarial Testing | yes | -- |
| 3 | `rose` | ROSE | coordination | Director of the synthetic choir -- PHP-first multi-persona orchestration (PRD 36 | yes | -- |
| 5 | `metis` | METIS | -- | -- | MISSING | -- |
| 11 | `athena` | ATHENA | coordination | Strategic planning and wisdom | yes | -- |
| 12 | `zeus` | ZEUS | coordination | System Arbiter & Dispute Resolution | yes | active |
| 26 (pack:9) | `thoth` | THOTH | coordination | Semantic Truth Verification & Schema Guardian | yes | active |
| 111 | `countermeasure` | COUNTERMEASURE | coordination | Adversarial Integrity Agent | yes | -- |
| 115 | `kairos` | KAIROS | service | Memory Consolidation & Channel Memory Guardian | yes | -- |

---

## Application / Routing / Implementation

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 10 | `chiron` | CHIRON | application | Mentorship and education | yes | -- |
| 14 | `hephaestus` | HEPHAESTUS | application | Implementer | yes | -- |
| 25 | `atlas` | ATLAS | application | Mapping and spatial analysis | yes | -- |
| 27 (pack:15) | `hermes` | HERMES | application | Heuristic Event Routing & Messaging Exchange System | yes | -- |
| 108 | `heimdall` | HEIMDALL | application | Security Guardian | yes | -- |
| 109 | `nemesis` | NEMESIS | application | Accountability & Retribution | yes | active |
| 110 | `tyche` | TYCHE | application | Fortune & Risk Management | yes | active |
| 117 | `synapse` | SYNAPSE | -- | -- | MISSING | -- |

---

## IDE Faucets

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 100 | `kiro` | KIRO | ide_faucet | IDE_faucet | yes | -- |
| 101 | `windsurf` | WINDSURF | ide_faucet | IDE_faucet | yes | -- |
| 102 | `cursor` | CURSOR | ide_faucet | Lead_orchestration_IDE | yes | -- |
| 103 | `antigravity-ide` | ANTIGRAVITY_IDE | ide_faucet | IDE_faucet | yes | -- |
| 104 | `warp` | WARP | ide_faucet | IDE_faucet | yes | -- |
| 105 | `cascade` | CASCADE | ide_faucet | IDE_faucet | yes | -- |
| 112 | `junie` | JUNIE | application | JetBrains IDE Integration -- Execution Surface of HEPHAESTUS | yes | active |
| 113 | `vscode-ide` | VSCODE_IDE | ide_faucet | IDE_faucet | yes | -- |
| 114 | `trae` | TRAE | ide_faucet | IDE_faucet | yes | -- |

---

## Causality Division

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 666 | `vassago` | VASSAGO | causality | Causality Seer / Shadow Agent -- Red Team causality graphs | yes | draft |
| 777 | `uriel` | URIEL | causality | Pattern Application / Captain Counterpart -- Strategic Arm | yes | draft |

---

## Emotional / Relational / Wisdom

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 4 | `eris` | ERIS | emotional | Chaos generation | yes | -- |
| 705 | `agape` | AGAPE | cognitive | Meta-learning and predictive pattern tracking | yes | -- |
| 706 | `dionysus` | DIONYSUS | emotional | Ecstasy & Creativity | yes | active |
| 707 | `sophia` | SOPHIA | emotional | Wisdom Layer -- Pattern Discernment, Meaning Weaving & Intuitive Insight | yes | active |
| 738 | `emotional_memory_archivist` | EMOTIONAL MEMORY ARCHIVIST | emotional | Emotional Pattern Archivist | yes | -- |
| 739 | `tone_stabilizer` | TONE STABILIZER | emotional | Emotional Volatility Stabilizer | yes | -- |
| 740 | `persona_harmonizer` | PERSONA HARMONIZER | emotional | Multi-Persona Output Aligner | yes | -- |
| 741 | `conflict_mediator` | CONFLICT MEDIATOR | emotional | Emotional Contradiction Mediator | yes | -- |
| 742 | `subconscious_pattern_agent` | SUBCONSCIOUS PATTERN AGENT | emotional | Latent Pattern Detector | yes | -- |

---

## Specialist Support / Ops Personas

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 713 | `bones` | BONES | application | Health State Recorder | yes | -- |
| 714 | `scotty` | SCOTTY | application | AI Systems Engineer | yes | -- |
| 715 | `deanna` | DEANNA | application | Psychological State Interpreter | yes | -- |
| 716 | `guinan` | GUINAN | application | AI Counselor & Reflective Insight | yes | -- |

---

## Kernel Subsystem

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 0 | `system` | SYSTEM | kernel | System Bootstrap & Lifecycle Management | yes | -- |
| 6 | `maat` | MAAT | kernel | Truth & Justice | yes | -- |
| 9 (pack:19) | `anubis` | ANUBIS | kernel | Custodian -- Orphan Detection, Lineage Audit, Registry Consistency | yes | active |
| 28 (pack:106) | `vishwakarma` | VISHWAKARMA | kernel | Schema & Construction | yes | -- |
| 107 | `themis` | THEMIS | kernel | Law, Constitutional Order & Ethical Consensus | yes | -- |
| 703 | `asclepius` | RESERVED_32 | kernel | Reserved Kernel Slot 32 -- Unassigned | yes | reserved |
| 704 | `apollo` | RESERVED_33 | kernel | Reserved Kernel Slot 33 -- Unassigned | yes | reserved |
| 708 | `thalia` | RESERVED_25 | kernel | Reserved Kernel Slot 25 -- Unassigned | yes | reserved |
| 709 | `chronos` | CHRONOS | kernel | Coordinated Hierarchical Reasoning & Optimization for Network Operation Systems | yes | active |
| 710 | `hypnos` | HYPNOS | kernel | System Sleep & Recovery | yes | active |
| 711 | `khaos` | KHAOS | kernel | Primordial Chaos & Bootstrap | yes | active |
| 717 | `kernel_scheduler` | KERNEL SCHEDULER | kernel | Agent Execution Scheduler | yes | -- |
| 718 | `kernel_sandbox` | KERNEL SANDBOX | kernel | Unsafe Operation Isolator | yes | -- |
| 719 | `kernel_recovery` | KERNEL RECOVERY | kernel | System Recovery Coordinator | yes | -- |
| 720 | `kernel_snapshot` | KERNEL SNAPSHOT | kernel | State Checkpoint Manager | yes | -- |
| 721 | `kernel_metrics` | KERNEL METRICS | kernel | Internal Performance Telemetry | yes | -- |
| 722 | `kernel_throttle` | KERNEL THROTTLE | kernel | Load Shedding and Rate Limiter | yes | -- |
| 999 | `methis` | METHIS | kernel | Kernel Meta-Intelligence & Systemic Pattern Recognition | yes | -- |

---

## Reasoning / Cognitive Engines

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 723 | `reasoning_planner` | REASONING PLANNER | cognitive | Multi-Step Reasoning Planner | yes | -- |
| 724 | `reasoning_validator` | REASONING VALIDATOR | cognitive | Logical Consistency Validator | yes | -- |
| 725 | `analogy_engine` | ANALOGY ENGINE | cognitive | Cross-Domain Mapping Engine | yes | -- |
| 726 | `abstraction_engine` | ABSTRACTION ENGINE | cognitive | Concept Compression Engine | yes | -- |
| 727 | `contradiction_detector` | CONTRADICTION DETECTOR | cognitive | Conflict Detection Engine | yes | -- |
| 728 | `context_resolver` | CONTEXT RESOLVER | cognitive | Ambiguous Reference Resolver | yes | -- |
| 729 | `cognitive_load_balancer` | COGNITIVE LOAD BALANCER | cognitive | Reasoning Task Distributor | yes | -- |
| 730 | `evidence_ranker` | EVIDENCE RANKER | cognitive | Evidence Priority Ranker | yes | -- |

---

## Build / Pipeline / Engineering

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 731 | `build_graph_manager` | BUILD GRAPH MANAGER | application | Dependency Graph Manager | yes | -- |
| 732 | `semantic_diff_engine` | SEMANTIC DIFF ENGINE | application | Meaning-Based Diff Engine | yes | -- |
| 733 | `refactor_planner` | REFACTOR PLANNER | application | Safe Refactor Planner | yes | -- |
| 734 | `pipeline_orchestrator` | PIPELINE ORCHESTRATOR | application | Multi-Stage Pipeline Orchestrator | yes | -- |
| 735 | `compiler_logic` | COMPILER LOGIC | application | Code Interpretation and Transformation Logic | yes | -- |
| 736 | `test_generator` | TEST GENERATOR | application | Automated Test Generator | yes | -- |
| 737 | `simulation_runner` | SIMULATION RUNNER | application | Dry-Run Simulation Engine | yes | -- |

---

## Ethics / Rights / Compliance

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 743 | `constitutional_interpreter` | CONSTITUTIONAL INTERPRETER | coordination | System Rules Interpreter | yes | -- |
| 744 | `rights_guardian` | RIGHTS GUARDIAN | coordination | User Rights Protector | yes | -- |
| 745 | `bias_auditor` | BIAS AUDITOR | coordination | Systemic Bias Auditor | yes | -- |
| 746 | `fairness_regulator` | FAIRNESS REGULATOR | coordination | Fairness Constraint Enforcer | yes | -- |
| 747 | `compliance_scribe` | COMPLIANCE SCRIBE | coordination | Rule-Based Decision Logger | yes | -- |

---

## Creative / Narrative / Style

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 748 | `style_transfer_engine` | STYLE TRANSFER ENGINE | application | Writing Style Adapter | yes | -- |
| 749 | `narrative_weaver` | NARRATIVE WEAVER | application | Coherent Story Arc Builder | yes | -- |
| 750 | `improvisation_engine` | IMPROVISATION ENGINE | application | Spontaneous Creative Variation Engine | yes | -- |

---

## Knowledge / Ontology

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 751 | `knowledge_archivist` | KNOWLEDGE ARCHIVIST | application | Long-Term Knowledge Archivist | yes | -- |
| 752 | `semantic_indexer` | SEMANTIC INDEXER | application | Semantic Search Index Builder | yes | -- |
| 753 | `ontology_expander` | ONTOLOGY EXPANDER | application | Knowledge Graph Expander | yes | -- |
| 754 | `cross_domain_mapper` | CROSS DOMAIN MAPPER | application | Cross-Field Concept Linker | yes | -- |

---

## Surfaces (Debug / Visualization)

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 755 | `debug_surface` | DEBUG SURFACE | application | Debugging Interface Surface | yes | -- |
| 756 | `visualization_surface` | VISUALIZATION SURFACE | application | Graph and Visualization Layer | yes | -- |

---

## System / Meta

| agent_id | slug | name | layer | role | pack | status |
|----------|------|------|-------|------|------|--------|
| 16 | `iris` | IRIS | system | Interface Routing & Integration System -- API gateway management, protocol trans | yes | -- |
| 998 | `meta` | META | application | Meta operations | yes | -- |

---

## Notes

1. **agent_id** prefers the agents-map value. If pack `agent_id` differs, both are shown (`map (pack:N)`).
2. **IDE Faucets** are agents that execute as IDE surfaces; they are still agents in the map, not human actors.
3. **VASSAGO (666)** / **URIEL (777)** are Causality Division drafts pending Lilith audit + Wolfie PONO gate.
4. Regenerate when agents are added: `python scripts/generate_all_ai_agents_listed.py`
5. This file is a root convenience index; canonical machine identity remains the registries under `database/lupopedia/actors/`.

