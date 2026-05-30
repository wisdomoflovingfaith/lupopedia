---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1028/20260320_130000_thoth_canonical_coordination_artifact_templates_header_first_operational_control.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1028/20260320_130000_thoth_canonical_coordination_artifact_templates_header_first_operational_control.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1028
  task_id: "task_strategy_coordination_repair_001"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "thread"
  artifact_kind: "template_pack"
  purpose: "Canonical coordination artifact templates for Header-First Operational Control in active Lupopedia coordination"
  tags: ["thoth", "templates", "coordination_profile", "channel_42", "thread_1028", "task_strategy_coordination_repair_001", "4.0.84"]
  message_type: "templates"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1028/20260320_120000_athena_strategy_file_visible_coordination_recovery.md", type: "implements", weight: 1.0, reason: "Template pack operationalizes ATHENA coordination strategy" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "constrained_by", weight: 1.0, reason: "Canonical minimum header doctrine and block rules" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "aligns_with", weight: 1.0, reason: "LUPOPEDIA HEADERS usage and optional block doctrine" }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "constrained_by", weight: 1.0, reason: "Actor-role coordination and channel discipline" }
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "updates", weight: 0.8, reason: "Template set includes canonical thread index entry format" }
    - { to: "TODO.md", type: "updates", weight: 0.8, reason: "Template set includes canonical TODO task entry format" }
    - { to: "report.md", type: "updates", weight: 0.8, reason: "Template set includes canonical report evidence entry format" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE: enforce template adoption order in section 13 for all new coordination artifacts"
    - "ATHENA: validate strategy-template consistency after first three templates are enforced"
    - "LILITH: audit first enforcement cycle against the rule set in section 14"
---
# file: THOTH canonical coordination artifact templates for Header-First Operational Control

This artifact is a coordination-profile template pack for active operational artifacts. It does not change doctrine, schema, or automation.

Required reading:

1. lupo-channels/42/threads/1028/20260320_120000_athena_strategy_file_visible_coordination_recovery.md
2. lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md
3. lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
4. lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md

## 1. Scope and binding usage

1. These templates apply to active coordination artifacts in Channel 42 and doctrine answer artifacts in Channel 66.
2. Canonical minimum header doctrine remains unchanged: version_when_written and file_path_from_root are globally mandatory.
3. Coordination profile extends the minimum for coordination artifacts; it does not replace global doctrine.
4. All templates are deterministic, file-visible, and explicit; no hidden state transitions are allowed.

## 2. Global rules for this template pack

1. Global required header minimum for all templates:
- version_when_written
- file_path_from_root
2. Coordination-profile required fields for operational coordination artifacts:
- last_modified_utc
- channel_id where applicable
- thread_id where applicable
- task_id where applicable
- actor_id
- actor_name
- artifact_type
- artifact_kind
- purpose
- tags
- lupopedia.edges outbound_edges
- lupopedia.footer next_action
3. Required footer fields for all operational coordination templates:
- last_verified
- last_verified_by
- orchestrator
- next_action
4. Required edge shape for all templates using edges:
- to
- type
- weight
- reason
5. Allowed edge types in this pack:
- addresses
- implements
- governs
- updates
- reviews
- closes
- depends_on
- aligns_with
- constrained_by
- references
6. Channel discipline:
- Channel 42: strategy, directive/assignment, status, review, handoff, closure, thread index entries, TODO integration, report evidence integration.
- Channel 66: doctrine answer artifacts only when work is question-driven.

## 3. Template 1: strategy artifact

Purpose:
- Define binding strategic direction, operating model decisions, and precedence rules.

When it must be used:
- At start of any coordination-repair or operating-model workstream.
- Before directives are issued for new multi-actor coordination mode.

Required header fields:
- Global minimum plus coordination-profile fields.
- artifact_kind must be strategy_decision.

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action with actor-addressed delegation items.

Required edge targets:
- Governing doctrine reference.
- Coordination registry surfaces: TODO.md, plan.md, report.md, THREAD_INDEX.md.
- Parent or source strategic artifact when applicable.

Allowed edge types:
- constrained_by
- governs
- aligns_with
- updates
- references

Required body sections:
- Scope
- Problem diagnosis
- Operational truth and precedence
- Artifact model requirements
- Delegation sequence

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1028
  task_id: "..."
  actor_id: 12
  actor_name: "athena"
  artifact_type: "thread"
  artifact_kind: "strategy_decision"
  purpose: "..."
  tags: ["strategy", "..."]
lupopedia.edges:
  outbound_edges:
    - { to: "TODO.md", type: "governs", weight: 1.0, reason: "..." }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "athena"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE: ..."
---
# file: ...
## Scope
## Diagnosis
## Operational Truth and Precedence
## Delegation
```

## 4. Template 2: directive or assignment artifact

Purpose:
- Assign executable work to a named owner with required reading, scope, and expected outputs.

When it must be used:
- Any time ownership is set, changed, or confirmed.
- Any time work is delegated from WOLFIE or authorized owner.

Required header fields:
- Global minimum plus coordination-profile fields.
- artifact_kind must be directive or assignment.
- task_id and thread_id are required.

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Source strategy artifact.
- Target thread execution container.
- TODO.md or task registry surface.

Allowed edge types:
- addresses
- implements
- depends_on
- governs
- references

Required body sections:
- Assignment summary
- Owner and authority
- Required Reading
- Deliverables
- Acceptance criteria
- Status update expectation

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1028
  task_id: "task_x"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Assign ..."
  tags: ["directive", "task_x", "owner_visible"]
lupopedia.edges:
  outbound_edges:
    - { to: "...athena_strategy...md", type: "implements", weight: 1.0, reason: "..." }
    - { to: "TODO.md", type: "updates", weight: 0.9, reason: "..." }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "assignee: acknowledge ownership"
---
# file: ...
## Assignment Summary
## Owner and Authority
## Required Reading
1. ...
## Deliverables
## Acceptance Criteria
## Status Update Expectation
```

## 5. Template 3: status artifact

Purpose:
- Record explicit lifecycle status with evidence paths and next action.

When it must be used:
- At every lifecycle transition: open, active, blocked, resolved, archived.
- At each meaningful progression checkpoint within active work.

Required header fields:
- Global minimum plus coordination-profile fields.
- artifact_kind must be status.
- task_id and thread_id are required.

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Current directive/assignment.
- TODO.md entry and THREAD_INDEX.md row surface.
- Blocking artifact when state is blocked.

Allowed edge types:
- updates
- depends_on
- addresses
- references

Required body sections:
- Current status
- Previous status
- Evidence paths
- Blockers
- Immediate next action

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1028
  task_id: "task_x"
  actor_id: 14
  actor_name: "hephaestus"
  artifact_type: "thread"
  artifact_kind: "status"
  purpose: "Status update for task_x"
  tags: ["status", "task_x", "thread_1028"]
lupopedia.edges:
  outbound_edges:
    - { to: "TODO.md", type: "updates", weight: 1.0, reason: "status sync" }
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "updates", weight: 0.9, reason: "thread status sync" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
  next_action:
    - "owner: execute next step"
---
# file: ...
## Current Status
## Previous Status
## Evidence Paths
## Blockers
## Immediate Next Action
```

## 6. Template 4: review artifact

Purpose:
- Provide structured assessment findings with severity and required corrective action.

When it must be used:
- At validation checkpoints.
- Before closure for high-risk or high-impact tasks.

Required header fields:
- Global minimum plus coordination-profile fields.
- artifact_kind must be review.

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Reviewed artifact(s).
- Governing doctrine(s) used as evaluation basis.

Allowed edge types:
- reviews
- constrained_by
- addresses
- references

Required body sections:
- Review scope
- Findings by severity
- Evidence paths
- Required corrections
- Acceptance or rejection recommendation

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1028
  task_id: "task_x"
  actor_id: 2
  actor_name: "lilith"
  artifact_type: "thread"
  artifact_kind: "review"
  purpose: "Review for task_x"
  tags: ["review", "task_x", "severity_mapped"]
lupopedia.edges:
  outbound_edges:
    - { to: "...artifact_under_review...md", type: "reviews", weight: 1.0, reason: "primary target" }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "constrained_by", weight: 1.0, reason: "evaluation baseline" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "lilith"
  orchestrator: "wolfie"
  next_action:
    - "owner: address high findings"
---
# file: ...
## Review Scope
## Findings by Severity
## Evidence Paths
## Required Corrections
## Recommendation
```

## 7. Template 5: handoff artifact

Purpose:
- Transfer execution custody from one owner to another with unresolved context and explicit acknowledgment trail.

When it must be used:
- On any owner transfer for active task or thread.
- On planned shift change where continuity risk exists.

Required header fields:
- Global minimum plus coordination-profile fields.
- artifact_kind must be handoff.

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Source ownership directive.
- Target ownership directive or acknowledgment artifact.
- Current status artifact.

Allowed edge types:
- updates
- addresses
- depends_on
- references

Required body sections:
- From owner and to owner
- Scope transferred
- Unresolved blockers
- Evidence and context links
- Receiver acknowledgment requirement

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1028
  task_id: "task_x"
  actor_id: 14
  actor_name: "hephaestus"
  artifact_type: "thread"
  artifact_kind: "handoff"
  purpose: "Handoff task_x from actor_a to actor_b"
  tags: ["handoff", "task_x", "owner_transfer"]
lupopedia.edges:
  outbound_edges:
    - { to: "...source_directive...md", type: "references", weight: 1.0, reason: "transfer authority" }
    - { to: "...latest_status...md", type: "updates", weight: 0.9, reason: "continuity context" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
  next_action:
    - "new_owner: publish acknowledgment status"
---
# file: ...
## From and To Ownership
## Scope Transferred
## Unresolved Blockers
## Evidence and Context Links
## Acknowledgment Requirement
```

## 8. Template 6: closure artifact

Purpose:
- Declare final completion state with closure evidence and residual risk statement.

When it must be used:
- On final task closure.
- On archival transition requiring explicit rationale.

Required header fields:
- Global minimum plus coordination-profile fields.
- artifact_kind must be closure.

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Closing directive.
- Final status artifact.
- report.md evidence entry.

Allowed edge types:
- closes
- references
- updates
- aligns_with

Required body sections:
- Closure decision
- Completion evidence
- Residual risks
- Post-closure next action (if any)

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1028
  task_id: "task_x"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "thread"
  artifact_kind: "closure"
  purpose: "Final closure for task_x"
  tags: ["closure", "task_x", "final_state"]
lupopedia.edges:
  outbound_edges:
    - { to: "report.md", type: "updates", weight: 1.0, reason: "closure evidence publication" }
    - { to: "...final_status...md", type: "closes", weight: 1.0, reason: "task lifecycle termination" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "none"
---
# file: ...
## Closure Decision
## Completion Evidence
## Residual Risks
## Post-Closure Action
```

## 9. Template 7: doctrine answer artifact

Purpose:
- Provide explicit answer to a doctrine question with governing rule references.

When it must be used:
- Question-driven doctrine clarification tasks.
- Routing destination is Channel 66, not generic planning channels.

Required header fields:
- Global minimum plus coordination-profile fields.
- channel_id must be 66 for doctrine answer artifacts.
- artifact_kind must be doctrine_answer.

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Question source artifact.
- Governing doctrine references.
- Any binding downstream directive destination.

Allowed edge types:
- constrained_by
- addresses
- references
- aligns_with

Required body sections:
- Question identifier
- Direct answer
- Doctrine basis
- Operational implication

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "lupo-channels/66/threads/..."
  channel_id: 66
  thread_id: 6601
  task_id: "task_doctrine_answer_x"
  actor_id: 26
  actor_name: "thoth"
  artifact_type: "thread"
  artifact_kind: "doctrine_answer"
  purpose: "Answer doctrine question x"
  tags: ["doctrine_answer", "channel_66", "question_driven"]
lupopedia.edges:
  outbound_edges:
    - { to: "...question_artifact...md", type: "addresses", weight: 1.0, reason: "question target" }
    - { to: "...governing_doctrine...md", type: "constrained_by", weight: 1.0, reason: "binding source" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "requestor: confirm answer acceptance"
---
# file: ...
## Question Identifier
## Direct Answer
## Doctrine Basis
## Operational Implication
```

## 10. Template 8: THREAD_INDEX entry format

Purpose:
- Standardize machine-readable and human-readable entry for thread visibility and ownership.

When it must be used:
- On thread creation.
- On status changes affecting active or resolved lists.

Required fields in entry row:
- thread_id
- task_id
- title
- status
- actor
- created_utc
- updated_utc

Required related edges in associated status artifact:
- to THREAD_INDEX.md using updates.

Allowed edge types:
- updates
- references

Required body support in updating artifact:
- Reason for index update
- Row values before and after for status changes

Minimum viable skeleton:
```markdown
| thread_id | task_id | title | status | actor | created_utc | updated_utc |
|-----------|---------|-------|--------|-------|-------------|-------------|
| 1028 | task_strategy_coordination_repair_001 | THOTH canonical coordination templates | active | thoth | 20260320_130000 | 20260320_130000 |
```

## 11. Template 9: TODO task entry format

Purpose:
- Standardize task identity, single owner visibility, and thread linkage.

When it must be used:
- On task creation.
- On owner, status, or thread reassignment.

Required fields in TODO entry:
- task_id
- title
- owner_actor_id
- owner_actor_name
- status
- primary_thread_id
- channel_id
- dependencies
- updated_utc

Required related edges in associated artifact:
- to TODO.md using updates.
- to source directive or strategy using addresses or implements.

Allowed edge types:
- updates
- addresses
- implements
- depends_on

Required body support in updating artifact:
- Reason for task row change
- prior_owner and new_owner when reassigned
- prior_status and new_status when transitioned

Minimum viable skeleton:
```markdown
- task_id: task_strategy_coordination_repair_001
  title: Coordination profile template pack
  owner_actor_id: 26
  owner_actor_name: thoth
  status: active
  primary_thread_id: 1028
  channel_id: 42
  dependencies: [task_strategy_coordination_repair_001]
  updated_utc: 20260320_130000
```

## 12. Template 10: report.md evidence entry format

Purpose:
- Standardize closure and execution evidence logging for cross-thread traceability.

When it must be used:
- On task milestone completion.
- On final closure.

Required fields in report entry:
- task_id
- thread_id
- artifact_path
- evidence_type
- verification_actor
- outcome
- recorded_utc

Required related edges in associated artifact:
- to report.md using updates.
- to closure or status artifact using references or closes.

Allowed edge types:
- updates
- closes
- references

Required body support in updating artifact:
- Evidence summary
- Verification basis
- Residual risk note

Minimum viable skeleton:
```markdown
- task_id: task_strategy_coordination_repair_001
  thread_id: 1028
  artifact_path: lupo-channels/42/threads/1028/20260320_130000_thoth_canonical_coordination_artifact_templates_header_first_operational_control.md
  evidence_type: template_pack_publication
  verification_actor: thoth
  outcome: published
  recorded_utc: 20260320_130000
```

## 13. Template adoption order

1. Directive or assignment template.
2. Status template.
3. Closure template.
4. Review template.
5. Handoff template.
6. Strategy template.
7. THREAD_INDEX entry template.
8. TODO task entry template.
9. report evidence entry template.
10. Doctrine answer template.

## 14. Minimum first three templates WOLFIE should enforce immediately

1. Directive or assignment template.
2. Status template.
3. Closure template.

Rationale:

1. These three enforce owner visibility, execution state visibility, and closure truth first.
2. These three eliminate hidden ownership changes and undocumented completion claims.

## 15. Validator implications (rule-level only)

1. Validate canonical minimum header fields globally for all new artifacts.
2. Validate coordination-profile required fields for Channel 42 coordination artifact kinds listed in this pack.
3. Validate required edge shape and allowed edge type vocabulary by artifact kind.
4. Validate that directive or assignment artifacts include required reading section in allowed form.
5. Validate that status, TODO, and THREAD_INDEX updates remain consistent on task_id, thread_id, and status values.
6. Validate that closure artifacts include report evidence linkage.
7. Validate that doctrine answer artifacts are routed to Channel 66 and include constrained_by doctrine edges.

_THOTH (actor_id 26) — canonical template source for immediate coordination standardization in Thread 1028._
