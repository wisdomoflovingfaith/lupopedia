---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1028/20260320_120000_athena_strategy_file_visible_coordination_recovery.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1028/20260320_120000_athena_strategy_file_visible_coordination_recovery.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1028
  task_id: "task_strategy_coordination_repair_001"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:strategy"
  artifact_type: "thread"
  artifact_kind: "strategy_decision"
  purpose: "Strategic operating model repair for file-visible coordination where active actors read artifacts and headers, not live database state"
  tags: ["athena", "strategy", "coordination_repair", "header_first", "channel_42", "task_strategy_coordination_repair_001", "4.0.84"]
  message_type: "strategy"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "updates", weight: 1.0, reason: "New strategic thread must be indexed for delegation" }
    - { to: "TODO.md", type: "governs", weight: 1.0, reason: "Root task registry remains global coordination control surface" }
    - { to: "plan.md", type: "governs", weight: 1.0, reason: "Roadmap phase and dependency visibility must align with this strategy" }
    - { to: "report.md", type: "governs", weight: 0.9, reason: "Execution outcomes and handoff evidence must remain file-visible" }
    - { to: "lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md", type: "aligns_with", weight: 1.0, reason: "Channel and thread workflow discipline" }
    - { to: "lupo-docs/doctrine/HEADER_STRUCTURE_DOCTRINE.md", type: "aligns_with", weight: 1.0, reason: "Header block requirements and consistency" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "aligns_with", weight: 1.0, reason: "Header semantics and storage doctrine" }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "constrained_by", weight: 1.0, reason: "Primary persona and delegation model" }
    - { to: "AGENTS.md", type: "aligns_with", weight: 0.9, reason: "IDE faucet reality and operational constraints for active actors" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "athena"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE: issue directives that instantiate the required artifacts defined in section 4 without schema work"
    - "THOTH: publish canonical templates for required coordination artifact types and edge usage"
    - "HEPHAESTUS: implement deterministic generators that expose selected database truth into file artifacts"
---
# file: ATHENA strategy for file-visible coordination recovery and header-first operational control

This strategy is doctrine-aligned operating model repair. It is not implementation code and does not change schema.

Required reading for this strategy:

1. lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md
2. lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
3. lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md

## 1. Diagnosis: why coordination is failing now

Lupopedia has rich relational structure in the database, but active IDE and AI actors are not querying that database during normal execution. They read repository files. They especially read channel artifacts and verbose headers in lupo-channels.

Resulting failure pattern:

1. Task ownership is not discoverable in one always-read place.
2. Thread ownership is inferred inconsistently from narrative text rather than declared fields.
3. Edge relationships are optional in practice, so lineage breaks.
4. Status is scattered across many artifacts without a mandatory status surface.
5. Documentation handoff and release visibility depend on human memory, not deterministic artifact protocol.

The core truth: database truth that is not read by active actors is non-operational truth for coordination.

## 2. Operational truth model for the current phase

Phase decision is explicit and binding:

1. Files are the current operational source of truth for agent coordination.
2. Database is structural and archival support, plus future integration substrate.
3. Coordination decisions are valid only when file-visible in required artifacts with required headers and edges.
4. No hidden reconciliation, no magical sync, no implicit transitions.

Doctrine split for this phase:

1. File truth governs who owns work, what status is current, what thread is active, and what happens next.
2. Database truth preserves durable relational backbone and can feed generated file artifacts, but does not replace file-visible coordination state until actors actually consume DB directly.

Precedence when file and database diverge during this phase:

1. Coordination state precedence: file artifacts win for active ownership, status, next_action, and thread-task execution lineage.
2. Structural state precedence: database wins for entity existence and structural identity records.
3. Divergence handling: any detected mismatch must be logged in a file-visible drift artifact and routed for explicit correction by directive; no silent correction in either direction.

Divergence detection and enforcement process:

1. Detection authority: thread owner performs first-pass detection during each status change; LILITH validates during audit pass; HERMES may flag routing-level inconsistency.
2. Enforcement authority: WOLFIE is final authority for precedence enforcement and correction directives.
3. Execution path: detection evidence is recorded in a drift artifact, then a WOLFIE directive resolves decision-state versus structural-state corrections.

## 3. Why verbose headers are the practical coordination surface

Verbose headers are currently the only machine-readable and human-readable metadata surface consistently consumed by active actors in workflow.

Therefore headers are not noise in this phase. They are the coordination contract.

Headers solve current constraints because they provide:

1. Deterministic identity keys in every artifact.
2. Explicit edges without hidden graph logic.
3. Portable metadata for IDE agents that only read files.
4. Delegation-ready context for WOLFIE without external query dependency.

## 4. Minimal required coordination artifact model

The following artifacts must exist for stable operation. Names are normative; content is constrained.

1. Channel thread index: lupo-channels/42/THREAD_INDEX.md
Meaning: canonical map of active and historical threads, ownership, and status.
2. Root task registry: TODO.md
Meaning: authoritative task identity, current owner, lifecycle state, and primary thread mapping.
3. Strategic roadmap: plan.md
Meaning: ordered phases, dependency chain, and sequence of task activation.
4. Execution ledger: report.md
Meaning: completion evidence, deviation log, handoff outcomes, and closure references.
5. Thread strategy and directive artifacts: lupo-channels/42/threads/{thread_id}/...
Meaning: task-scoped strategy, directive, status, review, and closure records with explicit lineage.
6. Doctrine answer destination artifacts in Channel 66 when work is question-driven only.
Meaning: Channel 66 stores explicit doctrine answers, not general implementation planning.

Minimal viability rule:

1. No task can move to active unless it appears in TODO.md with owner and thread_id.
2. No thread can be considered authoritative unless indexed in THREAD_INDEX.md.
3. No task can be closed without report.md evidence link and thread closure artifact.

## 5. Coordination repair protocol using read surfaces

### 5.1 Task ownership

1. Each task_id has exactly one current owner actor_id.
2. Ownership change requires explicit directive artifact and updated TODO.md row.
3. Thread artifact must acknowledge ownership transfer in the same work cycle.

### 5.2 Thread ownership

1. Each active thread has one execution owner plus optional reviewers.
2. Ownership must be declared in header fields and repeated in body assignment section.
3. Thread owner is accountable for status transitions and next_action freshness.

### 5.3 next_action discipline

1. Every strategy, directive, status, review, and closure artifact must include next_action in footer.
2. next_action items must be actor-addressed and operationally specific.
3. Any next_action older than one release cycle without follow-up becomes a required audit item.

### 5.4 required_reading discipline

1. Directive and assignment artifacts must include required_reading using the active canonical block order for the file type.
2. If the active template includes lupopedia.init, required_reading must be under lupopedia.init.required_reading.
3. If lupopedia.init is not present in the active canonical template, required_reading must be a dedicated body section titled Required Reading.
4. required_reading must include at least one doctrine file and one local task/thread artifact.
5. Actors cannot claim completion without citing required_reading artifacts in result output.

### 5.5 Outbound edge discipline

1. Every coordination artifact must include outbound_edges.
2. Required edge targets: source directive or strategy, current task registry row surface, and expected output destination.
3. Allowed edge types for coordination artifacts: addresses, implements, governs, updates, reviews, closes, depends_on, aligns_with, constrained_by.

### 5.6 Status visibility

1. Status changes must occur in both thread artifact trail and canonical registry surfaces.
2. Thread-level status is declared in thread artifacts and reflected in THREAD_INDEX.md.
3. Task-level status is declared in TODO.md and must not drift from thread status.

### 5.7 Documentation handoff

1. Handoff requires explicit handoff artifact in thread.
2. Handoff artifact must declare from_actor_id, to_actor_id, transferred scope, unresolved blockers, and next_action.
3. report.md must capture handoff completion or failure.

### 5.8 Release visibility

1. Release-facing tasks must carry release tag in headers and TODO/plan linkage.
2. Release closure must cite all closed thread artifacts in report.md.
3. CHANGELOG visibility is downstream output, not replacement for coordination lineage.

## 6. Header discipline rules (required by artifact type)

### 6.1 Canonical minimum versus coordination profile

Canonical minimum for all artifacts follows LUPOPEDIA_HEADERS_FORMAT section 2:

1. version_when_written
2. file_path_from_root

Coordination profile for coordination artifacts extends the canonical minimum with explicit operational metadata.

### 6.1.1 Coordination profile required fields

1. file_path_from_root
2. last_modified_utc
3. channel_id where applicable
4. thread_id where applicable
5. task_id where applicable
6. actor_id
7. actor_name
8. artifact_type
9. artifact_kind
10. purpose
11. tags
12. lupopedia.edges outbound_edges
13. lupopedia.footer next_action

Deviation note:

1. This is an explicit coordination-profile superset for Channel 42 operational control.
2. It does not replace LUPOPEDIA_HEADERS canonical minimum.
3. Validators should enforce canonical minimum globally and enforce this profile on coordination artifacts only.

last_modified_utc update policy:

1. last_modified_utc is required by this coordination profile for coordination artifacts.
2. It must be updated on semantic changes affecting ownership, status, lineage, scope, dependencies, or delegation.
3. It may remain unchanged for non-semantic edits such as formatting, typo fixes, and purely cosmetic wording.
4. When unchanged on non-semantic edits, an explanatory note must appear in commit message or thread status artifact.

### 6.2 Thread artifacts

Required: channel_id, thread_id, actor_id, artifact_kind, purpose, outbound_edges, footer.next_action.

### 6.3 Plans (plan.md and plan thread artifacts)

Required: task_id linkage, dependency edges as depends_on, phase ordering references, footer.next_action by actor.

### 6.4 Reports (report.md and report thread artifacts)

Required: closes edges to completed tasks or threads, evidence links, verification actor, footer.next_action for any residual risks.

### 6.5 Task assignments and directives

Required: assigner and assignee identity, required_reading section, due-phase or release target, outbound edges to source strategy and target execution thread.

### 6.6 Doctrine answers

Required: explicit question identifier, doctrine target answer, constrained_by edges to governing doctrine, and clear destination channel declaration.

### 6.7 Edge field usage rules

Each edge entry must contain: to, type, weight, reason.

Discipline:

1. to must reference a concrete file path.
2. type must be from approved coordination edge vocabulary.
3. weight must be deterministic numeric string or number from 0.1 to 1.0.
4. reason must explain operational relevance, not generic prose.

## 7. Migration strategy: from coordination mess to stable operation

### 7.1 Immediate stabilization (order is mandatory)

1. Create this strategy thread and index it in Channel 42.
2. Freeze ad hoc coordination claims that are not represented in TODO.md, plan.md, or THREAD_INDEX.md.
3. Require every newly created coordination artifact to include required header and outbound_edges.
4. Force task owner and thread owner declaration for all active tasks in TODO.md and THREAD_INDEX.md.

Ad hoc classification rule (objective):

1. A claim is ad hoc if it changes ownership, status, scope, dependency, or closure and is not recorded in the canonical surfaces defined in section 4.
2. A claim is ad hoc if it appears only in chat text, commit text, or narrative body without matching header fields and outbound_edges.
3. A claim is ad hoc if it lacks task_id-thread_id mapping evidence.
4. Non-ad-hoc claims are those represented in canonical artifacts with required metadata and edges.

Classification authority and challenge process:

1. Primary classifier is the current thread owner.
2. Secondary reviewer is LILITH during audit.
3. Final adjudicator is WOLFIE when classifier and claimant disagree.
4. Challenge process: claimant files a challenge artifact in the same thread citing failed test numbers and evidence; WOLFIE issues final ruling directive.

Dependencies:

1. No cleanup before owner and status baselines are declared.
2. No reassignment before current ownership is explicit.

### 7.2 Short-term cleanup

1. Reconcile active thread status in THREAD_INDEX.md against active tasks in TODO.md.
2. Add missing lineage edges for active work only, not full historical rewrite.
3. Normalize next_action and required_reading in all active coordination threads.
4. Establish deterministic report.md section for handoff and closure evidence.

Dependencies:

1. Stabilization complete.
2. Header template published by THOTH.

### 7.3 Medium-term normalization

1. Introduce deterministic generated summary artifacts that project selected DB relational truth into files.
2. Keep generation explicit, manual or command-invoked, and artifact-visible.
3. Use generated artifacts as read surfaces for actors while preserving database as structural backbone.
4. Extend validator checks to enforce header, edge, and status discipline for coordination artifacts.

Dependencies:

1. Stable artifact model in daily use.
2. Deterministic generator specification approved in Channel 42.

## 8. Database role now (explicit near-term policy)

Near-term policy:

1. Database remains system-of-record for structural entities and archival continuity.
2. Coordination control state is file-visible and header-visible.
3. If database truth must influence active coordination, it must be exported into deterministic artifacts in repository paths that actors read.

Approved pattern:

1. Command-triggered generation writes explicit artifacts under controlled docs paths.
2. Generated artifacts include source timestamp in BIGINT UTC YYYYMMDDHHIISS format where applicable.
3. No background process writes coordination state silently.
4. No hidden merge between database and files.

Generated-versus-human provenance rule:

1. Every coordination artifact must declare authoring mode in a file-visible way.
2. Human-authored artifacts must include tag human_authored.
3. Generated artifacts must include tag generated_artifact and a lupopedia.metadata block with generation_command, generation_timestamp_ymdhis, and source_snapshot_reference.
4. Human and generated artifacts may coexist for the same topic only when linked by outbound_edges that declare source_of_truth_for_decision and source_of_truth_for_structure.
5. Validation posture differs by mode: generated artifacts are validated for deterministic reproducibility; human-authored artifacts are validated for header and lineage completeness.

Generated artifact metadata schema for coordination profile:

1. generation_command: exact deterministic command or script entrypoint.
2. generation_timestamp_ymdhis: BIGINT UTC YYYYMMDDHHIISS.
3. source_snapshot_reference: explicit reference to source dataset snapshot or artifact path.
4. generator_actor_id: actor responsible for generation invocation.
5. generator_run_id: deterministic run identifier composed from timestamp plus task_id.

## 9. Channel and thread discipline

1. Channel 42 remains strategic coordination and operating model control surface.
2. Channel 66 remains question-driven doctrine answer surface only.
3. New strategic coordination repair work belongs in Channel 42 threads with explicit task_id and ownership.
4. Implementation work may be delegated to specialized channels, but must preserve edge lineage back to this strategy thread.

## 10. Immediate delegation sequence for WOLFIE

1. WOLFIE directive: instantiate task_strategy_coordination_repair_001 in TODO.md and plan.md with explicit owner map.
2. THOTH assignment: publish canonical templates for strategy, directive, status, review, closure, and doctrine answer artifacts with required header and edge blocks.
3. HEPHAESTUS assignment: draft deterministic generator spec for db_to_artifact_visibility feed, command-invoked only, no hidden sync.
4. LILITH assignment: perform non-interfering audit of active thread ownership, status drift, and missing edge lineage with fixed cadence, format, and escalation.
5. HERMES assignment: route actionable prompts from this strategy to assigned actors and enforce required_reading references.
6. WOLFIE closure gate: confirm stabilization criteria before opening medium-term normalization tasks.

### 10.1 LILITH audit protocol

1. Coordination cycle is defined as the interval between two consecutive WOLFIE status or directive artifacts in Channel 42 for the same task scope.
2. Frequency: one audit per coordination cycle and one pre-release audit.
2. Output format: one thread artifact per run with sections Findings, Severity, Evidence Paths, Drift Summary, and Required Escalations.
3. Severity levels: low for documentation mismatch, medium for ownership/status drift, high for lineage break affecting delegation, critical for conflicting active ownership or closure falsity.
4. Escalation path: medium and above routes to WOLFIE directive queue in Channel 42; critical requires immediate WOLFIE freeze directive on affected tasks until corrected.

## 11. Decisive operating statement

Operational truth for current coordination is file-visible artifacts with verbose, disciplined headers and explicit edges. Database relational richness is necessary but non-operational for actor coordination until it is deliberately exposed into deterministic repository artifacts that actors actually read.

This strategy is binding for coordination repair in the current phase.

_ATHENA (actor_id 12) — strategic coordination repair doctrine for Channel 42._
