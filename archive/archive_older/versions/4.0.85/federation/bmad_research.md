---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: null
  file_path_from_root: "docs/versions/4.0.85/federation/bmad_research.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: research
  artifact_kind: federation_workflow_mapping
  thread_id: 1050
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
# BMAD Method Research

## 1. Overview of BMAD method

BMAD Method organizes AI-assisted work as a staged delivery system rather than a flat list of prompts. The core structure is a four-phase flow documented in `docs/reference/workflow-map.md`:

1. Analysis (optional)
2. Planning
3. Solutioning
4. Implementation

Each phase produces artifacts that become context for the next phase. BMAD is explicit that documents are not just outputs; they are dependency carriers. In practical graph terms, BMAD treats artifacts as state-bearing nodes that constrain downstream work.

Primary source signals:

| BMAD source | What it encodes | Lupopedia relevance |
|---|---|---|
| `docs/reference/workflow-map.md` | phase order, workflow names, artifact outputs | workflow and artifact edges |
| `src/bmm-skills/module.yaml` | artifact directory classes | entity grouping and storage semantics |
| `docs/reference/agents.md` | agent-to-workflow mapping | actor/task handoff edges |
| `src/bmm-skills/4-implementation/bmad-sprint-status/workflow.md` | status routing and next action selection | task lifecycle and recommendation edges |
| `src/bmm-skills/4-implementation/bmad-correct-course/workflow.md` | change propagation and rerouting | impact and feedback-loop edges |
| `src/bmm-skills/4-implementation/bmad-create-story/workflow.md` | story preparation rules and exhaustive context load | prerequisite and readiness edges |

The important BMAD idea for Lupopedia is that workflow structure is encoded in several layers at once:

- phase progression
- artifact production
- agent responsibility
- status-based routing
- correction loops back to the right abstraction layer

## 2. Workflow structure (phases + flow)

BMAD defines a mostly linear top-level workflow with one alternate fast path.

### Main phased flow

| Phase | BMAD workflows | Core outputs | Progression meaning |
|---|---|---|---|
| Analysis | brainstorming, research, create-product-brief | brainstorming report, research findings, product brief | optional discovery and framing |
| Planning | create-prd, create-ux-design | `PRD.md`, `ux-spec.md` | defines what to build |
| Solutioning | create-architecture, create-epics-and-stories, check-implementation-readiness | architecture, epics, stories, readiness decision | defines how work is structured and whether implementation may start |
| Implementation | sprint-planning, create-story, dev-story, code-review, correct-course, sprint-status, retrospective | sprint status, story files, code, reviews, retrospectives | iterated delivery loop |

### Progression rules observed in BMAD

| Rule | BMAD evidence | Edge interpretation |
|---|---|---|
| Documents become context for later phases | workflow map says each document becomes context for the next phase | `artifact_feeds` |
| Implementation is gated by readiness | `bmad-check-implementation-readiness` emits PASS/CONCERNS/FAIL | `gates` or `permits_progress_to` |
| Sprint planning initializes delivery sequence | `bmad-sprint-planning` produces `sprint-status.yaml` | `initializes_tracking_for` |
| Story creation depends on existing planning artifacts | `bmad-create-story` loads epics, PRD, architecture, UX, project context | `requires_context_from` |
| Status determines next workflow recommendation | `bmad-sprint-status` computes `next_workflow_id` and `next_agent` | `routes_to` or `recommends_next_action` |

### Alternate path

`docs/explanation/quick-dev.md` defines `bmad-quick-dev`, which compresses the full flow for small, well-understood changes. It still retains the same structural logic:

- clarify intent first
- route to the smallest safe path
- run with longer autonomous execution
- diagnose failure at the correct layer

This means BMAD has both:

- explicit linear progression edges
- conditional shortcut edges

## 3. Task orchestration model

BMAD task orchestration is role-driven. Agents are attached to workflow families rather than acting as interchangeable workers.

### Agent-role pattern

| Agent | Primary role in BMAD | Orchestration significance |
|---|---|---|
| Mary / Analyst | discovery and research | starts optional upstream context generation |
| John / Product Manager | PRD, epics, readiness, course correction | governs planning, decomposition, and rerouting |
| Winston / Architect | architecture and readiness | turns requirements into technical structure |
| Bob / Scrum Master | sprint planning, story creation, retrospective | manages delivery sequencing and iteration closure |
| Amelia / Developer | implementation and review | executes and validates story work |
| Sally / UX Designer | UX design | injects design constraints into downstream tasks |
| Barry / Quick Flow Solo Dev | compressed workflow | one-node shortcut orchestration |

### Handoff pattern

BMAD uses three orchestration mechanisms repeatedly.

| Pattern | Description | Lupopedia interpretation |
|---|---|---|
| Workflow output handoff | one workflow's artifact becomes another workflow's input | artifact-to-task dependency |
| Agent handoff | responsibility moves from PM to Architect to SM to Dev | actor-to-task assignment flow |
| Status-based routing | sprint status recommends next workflow and next agent | registry-driven next-action graph |

### Responsibility flow

The dominant flow is:

`requirements -> architecture -> epics/stories -> sprint sequencing -> story context -> implementation -> review -> retrospective`

This is not merely chronological. Each transition changes the abstraction layer of the work item:

- planning artifact
- decomposition artifact
- execution artifact
- validation artifact
- reflection artifact

That layered shift is useful for Lupopedia because it suggests that tasks and questions should be linkable across abstraction layers, not only by temporal order.

## 4. Extracted edge patterns

### Edge pattern table

| source entity | target entity | relationship type | directionality | dependency type | optional metadata |
|---|---|---|---|---|---|
| Phase: Analysis | Phase: Planning | progresses_to | forward | soft | analysis may be skipped |
| Phase: Planning | Phase: Solutioning | progresses_to | forward | hard | PRD/UX become solution inputs |
| Phase: Solutioning | Phase: Implementation | gated_progression_to | forward | hard | readiness gate blocks or permits |
| Workflow: create-prd | Artifact: PRD.md | produces | forward | hard | output artifact |
| Artifact: PRD.md | Workflow: create-architecture | informs | forward | hard | requirements constraint input |
| Workflow: create-architecture | Artifact: architecture.md | produces | forward | hard | architecture with ADRs |
| Artifact: architecture.md | Workflow: create-epics-and-stories | constrains | forward | hard | decomposition should reflect architecture |
| Workflow: create-epics-and-stories | Artifact: epics/stories | decomposes_into | forward | hard | epic-to-story hierarchy |
| Workflow: sprint-planning | Artifact: sprint-status.yaml | initializes_tracking_for | forward | hard | delivery sequencing state |
| Artifact: sprint-status.yaml | Workflow: create-story | routes_to | forward | hard | backlog selection and ordering |
| Artifact: sprint-status.yaml | Workflow: dev-story | routes_to | forward | hard | ready-for-dev or in-progress routing |
| Artifact: sprint-status.yaml | Workflow: code-review | routes_to | forward | hard | review state routing |
| Story: backlog | Story: ready-for-dev | transitions_to | forward | hard | file created or context prepared |
| Story: ready-for-dev | Story: in-progress | transitions_to | forward | hard | implementation started |
| Story: in-progress | Story: review | transitions_to | forward | hard | dev complete, review needed |
| Story: review | Story: done | transitions_to | forward | hard | review passed |
| Epic | Story | contains | forward | hard | one-to-many decomposition |
| Story | Epic | belongs_to | reverse | hard | epic ownership |
| Agent: PM | Workflow: create-prd | owns_or_executes | forward | hard | canonical owner |
| Agent: Architect | Workflow: create-architecture | owns_or_executes | forward | hard | canonical owner |
| Agent: Scrum Master | Workflow: sprint-planning | owns_or_executes | forward | hard | canonical owner |
| Agent: Developer | Workflow: dev-story | owns_or_executes | forward | hard | canonical owner |
| Workflow: code-review | Story | validates | forward | hard | approval or changes requested |
| Workflow: retrospective | Next epic or sprint planning | feeds_back_to | forward | soft | lessons learned |
| Change trigger | Workflow: correct-course | invokes | forward | hard | rerouting because assumptions broke |
| Workflow: correct-course | PRD / Architecture / Epics / Story / Code layer | reroutes_to_origin_layer | forward | hard | fix the layer that caused the defect |
| Workflow: quick-dev | Implementation path | short_circuits_to | forward | soft | small-scope path |
| Multi-agent discussion | Decision artifact | co_informs | forward | soft | party mode collaborative reasoning |

### Edge classes implied by BMAD

| Edge class | Meaning in BMAD | Lupopedia usefulness |
|---|---|---|
| progression edge | a later phase depends on an earlier phase | task ordering |
| production edge | a workflow produces an artifact | provenance |
| context edge | an artifact informs a workflow | prerequisite reading |
| decomposition edge | an epic contains stories | hierarchy |
| routing edge | a status or state chooses the next action | next step automation |
| ownership edge | a role owns a workflow | actor-task assignment |
| validation edge | review or readiness approves downstream execution | gating and quality control |
| correction edge | a failure routes back to the origin layer | feedback loop modeling |
| shortcut edge | quick path bypasses larger flow under conditions | conditional execution |

## 5. Mapping to Lupopedia

### 5.1 TASK_REGISTRY improvements

BMAD suggests that Lupopedia task relationships should be first-class rather than mostly descriptive text fields.

#### Implication 1: dependency text should become typed dependency edges

Current registry fields like `dependencies`, `upstream_requirements`, and `downstream_outcomes` behave as compressed graph data. BMAD shows the value of splitting those into typed relationship categories such as:

| BMAD pattern | Lupopedia task implication |
|---|---|
| phase progression | task `progresses_to` task |
| readiness gate | task `blocked_by` gate or validation task |
| status-based next action | task `recommends_next` task |
| artifact context loading | task `requires_reading` artifact |
| correction loop | task `reroutes_to` upstream task or artifact layer |

#### Implication 2: lifecycle should separate readiness from execution

BMAD distinguishes preparation from active execution. Its story states suggest a more expressive Lupopedia lifecycle vocabulary even if the table remains unchanged.

Useful conceptual states:

| BMAD lifecycle signal | Lupopedia interpretation |
|---|---|
| backlog | known but not prepared |
| ready-for-dev | context-complete and executable |
| in-progress | active execution |
| review | awaiting validation |
| done | completed and accepted |

This is useful because many Lupopedia tasks currently collapse "exists", "ready", and "active" into `in-progress`.

#### Implication 3: next-action logic should be derivable from edges

BMAD's sprint-status workflow computes `next_workflow_id` and `next_agent` from state. Lupopedia could derive next actions from graph relationships rather than manual narration.

### 5.2 lupo_edges model improvements

The BMAD structures translate cleanly into `lupo_edges` style entities.

| BMAD source pattern | entity_type | relationship_type | edge_type | semantic_weight |
|---|---|---|---|---|
| phase to phase progression | workflow_phase | progresses_to | sequencing | 0.95 |
| workflow produces artifact | workflow | produces | provenance | 0.95 |
| artifact informs downstream workflow | artifact | informs | prerequisite | 0.9 |
| epic contains story | task_group | contains | hierarchy | 0.95 |
| story belongs to epic | task | belongs_to | hierarchy | 0.95 |
| role owns workflow | actor_role | owns_or_executes | responsibility | 0.85 |
| readiness check gates implementation | validation_task | gates | gating | 1.0 |
| sprint status recommends next workflow | state_node | recommends_next | routing | 0.9 |
| course correction routes back to source layer | issue_or_change | reroutes_to_origin_layer | correction | 0.9 |
| retrospective informs next cycle | retrospective | feeds_back_to | feedback | 0.8 |
| quick flow bypasses full planning | workflow | short_circuits_to | conditional_path | 0.65 |

#### Candidate Lupopedia entity decomposition

| BMAD concept | Lupopedia entity_type | Example entity_id strategy |
|---|---|---|
| phase | workflow_phase | `analysis`, `planning`, `solutioning`, `implementation` |
| workflow | workflow | `bmad_create_prd`, `bmad_dev_story` |
| artifact | content or artifact | document slug or content id |
| epic | task_group or task | thread/task group id |
| story | task | task id |
| state | workflow_state or task_state | `ready_for_dev`, `review` |
| role/agent | actor or actor_role | actor id or role key |
| change trigger | issue, contradiction, or question | contradiction id or question thread id |

### 5.3 Channel 66 graph implications

BMAD is highly applicable to Channel 66 because its workflow depends on explicit prerequisite and next-action routing.

#### Question graph opportunities

| BMAD pattern | Channel 66 implication |
|---|---|
| upstream artifact prerequisites | question can depend on prerequisite questions |
| next workflow recommendation | question can recommend next question or next execution task |
| correction loop | question can route back to the unresolved upstream assumption |
| review and readiness gate | question can block downstream action until answered |
| retrospective feedback | answered question can influence future planning threads |

#### Useful Channel 66 edge candidates

| source | target | relationship |
|---|---|---|
| question | prerequisite question | depends_on |
| question | execution task | informs |
| question | next question | recommends_next |
| contradiction | upstream question | traces_to |
| answer artifact | downstream task | unlocks |
| review finding | origin question | reroutes_to |

BMAD's main lesson here is that a question graph should not be a flat queue. It should be able to express:

- prerequisite chain
- readiness chain
- recommendation chain
- correction chain

## 6. Opportunities for Lupopedia

1. Treat tasks, questions, workflows, and artifacts as separate node classes.
2. Move free-text dependency fields toward typed edge relationships.
3. Distinguish preparation states from execution states.
4. Add graphable next-action recommendations instead of relying only on prose.
5. Model rerouting explicitly when failures originate upstream.
6. Represent retrospectives and review findings as feedback nodes, not just terminal notes.
7. Allow conditional shortcut paths for small tasks while keeping explicit provenance.

## 7. Unknowns / gaps

1. BMAD is documentation- and skill-driven, so some relationships are encoded in human-readable workflow steps rather than a single machine-readable graph file.
2. The repository exposes role-to-workflow ownership clearly, but not a normalized dependency schema for every workflow artifact.
3. The quick-dev path intentionally compresses structure, so its exact edge set is more conditional than the main four-phase path.
4. Party mode shows collaborative reasoning, but it is a conversational orchestration pattern rather than a strongly structured task model.
5. This research does not propose a final Lupopedia schema or doctrine change; it only identifies mapping candidates.

## Research conclusion

BMAD represents work as a chain of context-bearing transformations. Its strongest transferable idea for Lupopedia is not the specific file names or agent personas. It is the disciplined use of typed relationships:

- what produced this artifact
- what must be read before this task starts
- what state recommends the next action
- what validation gate permits progress
- what upstream layer must be revisited when downstream work fails

That logic maps directly onto Lupopedia's task and edge ambitions, especially for Channel 42 execution threads and Channel 66 question lineage.
