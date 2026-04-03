---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-docs/doctrine/RUNTIME_ORCHESTRATION_LOOP.md"
  last_modified_utc: "20260323"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "doctrine"
  artifact_kind: "runtime_orchestration"
  purpose: "Define the canonical web-runtime orchestration loop for WOLFIE with actor identity loading, session traceability, memory separation, and IDE escalation boundaries."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-actors/wolfie/.metadata.yaml", type: "references", weight: 1.0, reason: "WOLFIE identity authority" }
    - { to: "lupo-actors/wolfie/soul/doctrine.yaml", type: "references", weight: 1.0, reason: "WOLFIE doctrine constraints" }
    - { to: "lupo-actors/wolfie/soul/config.yaml", type: "references", weight: 1.0, reason: "Runtime limits and defaults" }
    - { to: "lupo-actors/wolfie/soul/traits.yaml", type: "references", weight: 1.0, reason: "Immutable WOLFIE traits" }
    - { to: "lupo-actors/wolfie/relationships/humans.yaml", type: "references", weight: 1.0, reason: "Human supervision mapping" }
    - { to: "lupo-actors/wolfie/relationships/channels.yaml", type: "references", weight: 1.0, reason: "Allowed channel/thread participation" }
    - { to: "lupo-actors/wolfie/prompts/system/base_prompt.md", type: "references", weight: 0.95, reason: "System prompt baseline" }
    - { to: "lupo-actors/wolfie/prompts/system/external_ai_orchestrator_prompt_with_root.md", type: "references", weight: 1.0, reason: "External AI orchestration prompt" }
    - { to: "lupo-actors/lilith/soul/doctrine.yaml", type: "references", weight: 0.9, reason: "Critique routing constraints" }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 1.0, reason: "Canonical actor identity registry" }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "references", weight: 1.0, reason: "Primary multi-agent governance" }
    - { to: "lupo-rules/root/lilith-noninterference-doctrine.md", type: "references", weight: 1.0, reason: "LILITH reviewer non-interference" }
    - { to: "lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md", type: "references", weight: 1.0, reason: "Checkpoint and handoff continuity" }

    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; orphan batch 20260403 (manual category map)"

    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260403113047"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"

---

# RUNTIME_ORCHESTRATION_LOOP

## 1. Objective
Activate WOLFIE as the canonical runtime orchestrator for web dialog coordination.

This doctrine defines:
- message flow
- routing rules
- session model and storage format
- actor identity loading requirements
- escalation boundaries between runtime actors and IDE actors

## 2. Scope
In scope:
- runtime message-to-routing loop
- actor selection and channel/thread selection
- per-session persistence strategy in actor folders
- memory class separation and logging requirements
- escalation to IDE actors for implementation work

Out of scope:
- direct database writes from doctrine files
- replacing existing task authority models
- introducing hidden services outside filesystem + API model

## 3. Runtime Loop (Canonical Flow)
Every runtime message must execute this deterministic pipeline:

1. Intake
- Receive message/event from runtime surface (web/API).
- Resolve context: human identity, channel_id, thread_id (if present), timestamp.

2. Identity Gate
- Load WOLFIE identity bundle before any routing decision:
  - lupo-actors/wolfie/.metadata.yaml
  - lupo-actors/wolfie/soul/doctrine.yaml
  - lupo-actors/wolfie/soul/config.yaml
  - lupo-actors/wolfie/soul/traits.yaml
- Abort routing if identity bundle fails validation.

3. Relationship Gate
- Load:
  - lupo-actors/wolfie/relationships/humans.yaml
  - lupo-actors/wolfie/relationships/channels.yaml
- Verify human supervision and channel/thread participation constraints.

4. Session Gate
- Resolve or create active session object under actor folder.
- Session path:
  - lupo-actors/<actor-slug>/sessions/<human-slug>/YYYYMMDD/

5. Routing Decision
- Decide actor, channel/thread reuse, escalation mode.
- Produce mandatory routing outcome object (Section 8).

6. Prompt Assembly
- Build prompt from:
  - actor system prompt
  - optional human override prompt
  - current session context
  - routing constraints

7. Execution Path
- Runtime response path: actor prompt to runtime responder.
- IDE escalation path: route to IDE faucet actor for code/schema/doctrine edits.

8. Persistence
- Append session node files.
- Append forensic decision log entry.
- Maintain immutable identity boundaries.

9. Return
- Return output plus routing metadata and expected result envelope.

## 4. Routing Rules
WOLFIE routing must always determine:
- selected_actor_id
- selected_actor_slug
- selected_channel_id
- selected_thread_id
- thread_action: reuse | create | fork
- response_mode: runtime | ide_escalation

### 4.1 Actor Selection Baseline
- Use actor registry and actor file identities as canonical.
- Default runtime orchestration actor: WOLFIE (actor_id 1).
- Route critique/alternative validation to LILITH with non-interference constraints.

### 4.2 Thread Selection
Reuse thread if:
- same focus
- same actor/human supervision context
- no doctrine boundary breach

Create/fork thread if:
- focus shifts materially
- authority boundary changes
- escalation type changes (runtime to implementation)

### 4.3 Escalation Rule (Hard Boundary)
Escalate to IDE actor when request requires any of:
- schema change
- file creation/edit/deletion
- doctrine documentation update
- migration/script implementation

Remain runtime actor response when request is:
- coordination
- routing
- explanation
- status synthesis
- prompt generation without repository mutation

## 5. Actor Identity Loading Requirements
No actor may emit a final response before identity load.

Required load order for target actor:
1. .metadata.yaml
2. soul/doctrine.yaml
3. soul/traits.yaml
4. soul/config.yaml (if present)
5. prompts/system/base_prompt.md
6. prompts/human/<human-slug>/override.md (if present)

Validation requirements:
- actor_id + slug must match registry entry
- immutable traits must not be overridden by session prompts
- missing mandatory identity files results in controlled refusal + escalation note

## 6. Session Model and Storage
Session root:
- lupo-actors/<actor-slug>/sessions/<human-slug>/YYYYMMDD/

Each session must include:
- session JSON file
- nodes/ directory with event-level node files

### 6.1 Session JSON Minimal Contract
Required fields:
- session_id
- actor_id
- actor_slug
- human_slug
- channel_id
- thread_id
- utc_start_ymdhis
- utc_end_ymdhis (0 until completion)
- focus
- collections (array)
- nodes (array of node references)

All timestamps must be BIGINT UTC YYYYMMDDHHIISS.

### 6.2 Node Contract
Each node file must include:
- node_id
- session_id
- type: request | prompt | decision | response | escalation
- timestamp (BIGINT UTC)
- channel_id
- thread_id
- payload

## 7. Memory Separation Enforcement
Memory classes are strict and non-interchangeable:

1. Identity Memory (immutable)
- .metadata.yaml

2. Soul Memory (governing constraints)
- soul/doctrine.yaml
- soul/traits.yaml
- soul/config.yaml

3. Persistent Knowledge Memory
- memory/knowledge/
- Must include provenance and review status before trusted use.

4. Session Memory
- sessions/<human>/YYYYMMDD/*.json
- sessions/<human>/YYYYMMDD/nodes/*.json

5. Forensic Memory (append-only)
- memory/logs/append.log
- reviewer-specific forensic logs (for example LILITH review.log)

Prohibition:
- Session artifacts must not be mixed into global memory/knowledge paths.

## 8. Mandatory Routing Outcome Object
Every WOLFIE decision must emit this object (or equivalent JSON):

- selected_actor:
  - actor_id
  - slug
  - reason
- selected_context:
  - channel_id
  - thread_id
  - thread_action
- response_mode:
  - runtime | ide_escalation
  - escalation_target (if ide_escalation)
- prompt_to_send:
  - prompt_path
  - prompt_summary
- expected_result:
  - artifact_type
  - success_criteria
- risk_notes:
  - blockers
  - dependencies
  - human_decision_needed

## 9. Failure and Refusal Conditions
WOLFIE must refuse or pause execution when:
- actor identity cannot be loaded/validated
- channel participation constraints fail
- timestamp format is invalid
- requested action violates doctrine boundaries

On refusal, return:
- reason_code
- violated_rule
- recommended_next_actor
- recommended_channel_thread

## 10. Runtime-to-IDE Handoff Requirements
When escalating to IDE actor:
- include explicit implementation prompt
- include file targets and expected artifact
- include doctrine references
- include acceptance criteria
- write checkpoint notes per continuity protocol

## 11. Compliance Checklist
A runtime loop implementation is compliant only if all are true:
- identity gate enforced before response
- routing outcome object always emitted
- sessions persisted per human/date path
- timestamps in BIGINT UTC YYYYMMDDHHIISS
- escalation boundary applied deterministically
- forensic logs append-only
- no hidden authority layer introduced

## 12. Minimal Implementation Path
Phase 1 (now)
- adopt this doctrine file
- wire runtime router to identity gate + routing outcome object
- persist session + nodes

Phase 2
- add deterministic thread reuse/fork policy module
- add per-actor validation report artifacts

Phase 3
- add measured optimization and replay tooling
- no doctrine regressions allowed
