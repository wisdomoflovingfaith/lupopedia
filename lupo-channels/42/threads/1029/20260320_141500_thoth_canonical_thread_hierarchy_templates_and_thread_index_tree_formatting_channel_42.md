---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1029/20260320_141500_thoth_canonical_thread_hierarchy_templates_and_thread_index_tree_formatting_channel_42.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1029/20260320_141500_thoth_canonical_thread_hierarchy_templates_and_thread_index_tree_formatting_channel_42.md"
  last_modified_utc: "20260320"
  channel_id: 42
  thread_id: 1029
  task_id: "task_strategy_thread_tree_normalization_001"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "thread"
  artifact_kind: "template_pack"
  purpose: "Canonical templates and THREAD_INDEX tree formatting for parent-child thread hierarchy in Channel 42"
  tags: ["thoth", "thread_hierarchy", "template_pack", "thread_index_tree", "channel_42", "thread_1029", "4.0.84"]
  message_type: "templates"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1029/20260320_140000_athena_strategy_parent_child_thread_hierarchy_channel_thread_tree_normalization.md", type: "implements", weight: 1.0, reason: "Operationalizes ATHENA thread tree strategy" }
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "updates", weight: 1.0, reason: "Defines flat extension and grouped tree formatting" }
    - { to: "lupo-channels/42/threads/1028/20260320_120000_athena_strategy_file_visible_coordination_recovery.md", type: "aligns_with", weight: 1.0, reason: "Maintains file-visible coordination model" }
    - { to: "lupo-channels/42/threads/1028/20260320_130000_thoth_canonical_coordination_artifact_templates_header_first_operational_control.md", type: "aligns_with", weight: 1.0, reason: "Extends prior THOTH coordination template pack" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "constrained_by", weight: 1.0, reason: "Canonical header minimum and block ordering constraints" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "aligns_with", weight: 1.0, reason: "Header usage discipline and optional blocks guidance" }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "constrained_by", weight: 1.0, reason: "Multi-agent role and channel coordination boundaries" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE: approve template pack and enforce adoption order in section 15"
    - "HEPHAESTUS: implement file updates for THREAD_INDEX and hierarchy-aware thread artifacts only"
    - "LILITH: audit structural versus dependency versus review/reconciliation separation after first enforcement cycle"
---
# file: THOTH Canonical Thread Hierarchy Templates and THREAD_INDEX Tree Formatting for Channel 42

This artifact is a canonical template and formatting pack for ATHENA's approved parent-child thread hierarchy model. It is planning and documentation only. No schema changes, no generators, and no hidden synchronization behavior are introduced.

Required reading:

1. lupo-channels/42/threads/1029/20260320_140000_athena_strategy_parent_child_thread_hierarchy_channel_thread_tree_normalization.md
2. lupo-channels/42/THREAD_INDEX.md
3. lupo-channels/42/threads/1028/20260320_120000_athena_strategy_file_visible_coordination_recovery.md
4. lupo-channels/42/threads/1028/20260320_130000_thoth_canonical_coordination_artifact_templates_header_first_operational_control.md
5. lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md
6. lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
7. lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md

## 1. Global hierarchy vocabulary (binding)

thread_role allowed values:

1. parent
2. child
3. derived
4. reconciliation
5. review
6. closure
7. legacy_flat

rollup_scope allowed values:

1. none
2. local
3. parent_rollup

Hierarchy field rules:

1. parent_thread_id: required for child, derived, reconciliation, review, closure; prohibited for parent and legacy_flat.
2. root_thread_id: required for parent, child, derived, reconciliation, review, closure; optional for legacy_flat.
3. thread_role: required for all hierarchy-aware threads.
4. lineage_depth: required for parent, child, derived, reconciliation, review, closure.
5. rollup_scope: required for parent, child, derived, reconciliation, review, closure.

Separation rules:

1. Structural hierarchy is only parent_thread_id plus root_thread_id plus thread_role.
2. Dependencies are only depends_on_thread_ids and depends_on edges.
3. Review and reconciliation are only reviews_thread_id or reconciles_thread_ids and their edge types.
4. A thread has at most one structural parent.
5. A thread may have multiple dependencies.
6. Review and reconciliation relationships must not imply parentage.

Authority rule for reconciliation creation:

1. Reconciliation threads may be created only by WOLFIE or by an explicitly delegated actor named in a WOLFIE directive.
2. If multiple actors are in conflict and no explicit delegation exists, creation authority defaults to WOLFIE only.

## 2. Global required fields and edges for hierarchy-aware threads

Global minimum header doctrine remains unchanged:

1. version_when_written
2. file_path_from_root

Forward-created hierarchy-aware threads minimum additional required fields:

1. last_modified_utc
2. channel_id
3. thread_id
4. task_id
5. actor_id
6. actor_name
7. artifact_type
8. artifact_kind
9. purpose
10. tags
11. thread_role
12. root_thread_id
13. lineage_depth
14. rollup_scope
15. parent_thread_id when thread_role is child, derived, reconciliation, review, closure

Required footer fields:

1. last_verified
2. last_verified_by
3. orchestrator
4. next_action

Required edge shape:

1. to
2. type
3. weight
4. reason

Root-link edge rule:

1. Any non-parent hierarchy-aware thread must include one edge to immediate parent using child_of.
2. Derived, review, reconciliation, and closure threads must also include one explicit reference edge to root_thread_id artifact using references or aligns_with.

Allowed edge types for hierarchy pack:

1. parent_of
2. child_of
3. derived_from
4. depends_on
5. reviews
6. reconciles
7. closes
8. updates
9. references
10. constrained_by
11. aligns_with
12. implements

## 3. Template 1: parent thread artifact

Purpose:
- Establish top-level workstream container and rollup owner.

When it must be used:
- At initiation of a strategic or coordination umbrella thread.

Required header fields:
- Global minimum plus hierarchy-aware minimum fields.
- thread_role must be parent.
- parent_thread_id must be absent.
- root_thread_id must equal thread_id.

Required hierarchy fields:
- thread_role: parent
- root_thread_id: thread_id
- lineage_depth: 0
- rollup_scope: parent_rollup

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- THREAD_INDEX.md
- Source strategy artifact
- TODO.md and plan.md when parent controls coordinated tasks

Allowed edge types:
- updates
- implements
- governs
- references
- aligns_with

Required body sections:
- Parent scope
- Rollup objective
- Child thread creation criteria
- Rollup status criteria

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1100
  task_id: "task_parent_x"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "thread"
  artifact_kind: "strategy_decision"
  purpose: "Parent workstream"
  tags: ["parent", "thread_tree"]
  thread_role: "parent"
  root_thread_id: 1100
  lineage_depth: 0
  rollup_scope: "parent_rollup"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "updates", weight: 1.0, reason: "register parent" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "owner: authorize child branches"
---
# file: ...
## Parent Scope
## Rollup Objective
## Child Thread Creation Criteria
## Rollup Status Criteria
```

## 4. Template 2: child thread artifact

Purpose:
- Execute scoped work branch under a single parent thread.

When it must be used:
- When branch scope remains inside parent objective and must roll up into parent closure.

Required header fields:
- Global minimum plus hierarchy-aware minimum fields.
- thread_role must be child.
- parent_thread_id required.

Required hierarchy fields:
- thread_role: child
- parent_thread_id: required
- root_thread_id: inherited from parent
- lineage_depth: parent depth + 1
- rollup_scope: parent_rollup or local

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Parent artifact
- THREAD_INDEX.md
- Parent task artifact or directive

Allowed edge types:
- child_of
- updates
- implements
- depends_on
- references

Required body sections:
- Child scope
- Parent linkage
- Deliverables
- Rollup handoff target

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1101
  task_id: "task_child_x"
  actor_id: 14
  actor_name: "hephaestus"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Child branch execution"
  tags: ["child", "thread_tree"]
  thread_role: "child"
  parent_thread_id: 1100
  root_thread_id: 1100
  lineage_depth: 1
  rollup_scope: "parent_rollup"
lupopedia.edges:
  outbound_edges:
    - { to: "...parent...md", type: "child_of", weight: 1.0, reason: "structural parent" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
  next_action:
    - "owner: publish status"
---
# file: ...
## Child Scope
## Parent Linkage
## Deliverables
## Rollup Handoff Target
```

## 5. Template 3: derived thread artifact

Purpose:
- Track scope expansion requiring separate lifecycle while preserving lineage.

When it must be used:
- Work branches out materially from originating thread but remains in same root workstream.

Required header fields:
- Global minimum plus hierarchy-aware minimum fields.
- thread_role must be derived.
- parent_thread_id required.

Required hierarchy fields:
- thread_role: derived
- parent_thread_id: required
- root_thread_id: inherited
- lineage_depth: parent depth + 1
- rollup_scope: local or parent_rollup

rollup_scope criteria for derived threads:

1. Use local when derived output is informational, exploratory, or advisory and not required for parent closure gate.
2. Use parent_rollup when derived output is required input to parent closure decision, reconciliation, or release gating.

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Originating thread artifact
- Parent artifact
- THREAD_INDEX.md

Allowed edge types:
- derived_from
- child_of
- updates
- references
- depends_on

Required body sections:
- Derivation rationale
- Origin and parent references
- New scope boundary
- Reintegration expectation

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1102
  task_id: "task_derived_x"
  actor_id: 12
  actor_name: "athena"
  artifact_type: "thread"
  artifact_kind: "strategy_decision"
  purpose: "Derived branch"
  tags: ["derived", "thread_tree"]
  thread_role: "derived"
  parent_thread_id: 1101
  root_thread_id: 1100
  lineage_depth: 2
  rollup_scope: "local"
lupopedia.edges:
  outbound_edges:
    - { to: "...origin...md", type: "derived_from", weight: 1.0, reason: "scope expansion" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "athena"
  orchestrator: "wolfie"
  next_action:
    - "owner: define reintegration"
---
# file: ...
## Derivation Rationale
## Origin and Parent References
## New Scope Boundary
## Reintegration Expectation
```

## 6. Template 4: reconciliation thread artifact

Purpose:
- Resolve conflicts between sibling or branch outputs.

When it must be used:
- Conflicting branch outcomes require explicit convergence decision.

Required header fields:
- Global minimum plus hierarchy-aware minimum fields.
- thread_role must be reconciliation.
- parent_thread_id required.

Required hierarchy fields:
- thread_role: reconciliation
- parent_thread_id: required
- root_thread_id: inherited
- lineage_depth: parent depth + 1
- rollup_scope: parent_rollup

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Conflicting threads being reconciled
- Parent thread
- Any resulting closure or status artifact

Allowed edge types:
- reconciles
- child_of
- references
- updates
- closes

Required body sections:
- Conflict statement
- Threads reconciled
- Decision outcome
- Rollup impact

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1103
  task_id: "task_reconcile_x"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "thread"
  artifact_kind: "strategy_decision"
  purpose: "Branch reconciliation"
  tags: ["reconciliation", "thread_tree"]
  thread_role: "reconciliation"
  parent_thread_id: 1100
  root_thread_id: 1100
  lineage_depth: 1
  rollup_scope: "parent_rollup"
lupopedia.edges:
  outbound_edges:
    - { to: "...branch_a...md", type: "reconciles", weight: 1.0, reason: "conflict resolution target" }
    - { to: "...branch_b...md", type: "reconciles", weight: 1.0, reason: "conflict resolution target" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "owners: apply reconciled decision"
---
# file: ...
## Conflict Statement
## Threads Reconciled
## Decision Outcome
## Rollup Impact
```

## 7. Template 5: review thread artifact

Purpose:
- Perform independent review of a target thread output without structural parentage change.

When it must be used:
- Doctrine, quality, or compliance review is required.

Required header fields:
- Global minimum plus hierarchy-aware minimum fields.
- thread_role must be review.
- parent_thread_id required.

Required hierarchy fields:
- thread_role: review
- parent_thread_id: required
- root_thread_id: inherited
- lineage_depth: parent depth + 1
- rollup_scope: local or parent_rollup

rollup_scope criteria for review threads:

1. Use local when review is non-blocking and does not gate parent closure.
2. Use parent_rollup when review outcome is a closure gate, compliance gate, or required reconciliation trigger.

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Reviewed thread artifact
- Governing doctrine
- Parent thread

Allowed edge types:
- reviews
- constrained_by
- child_of
- references
- updates

Required body sections:
- Review target
- Criteria and doctrine basis
- Findings
- Required corrections or acceptance

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1104
  task_id: "task_review_x"
  actor_id: 2
  actor_name: "lilith"
  artifact_type: "thread"
  artifact_kind: "review"
  purpose: "Review branch"
  tags: ["review", "thread_tree"]
  thread_role: "review"
  parent_thread_id: 1100
  root_thread_id: 1100
  lineage_depth: 1
  rollup_scope: "local"
lupopedia.edges:
  outbound_edges:
    - { to: "...target_thread...md", type: "reviews", weight: 1.0, reason: "review target" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "lilith"
  orchestrator: "wolfie"
  next_action:
    - "owner: respond to findings"
---
# file: ...
## Review Target
## Criteria and Doctrine Basis
## Findings
## Required Corrections or Acceptance
```

## 8. Template 6: closure thread artifact

Purpose:
- Execute and record final rollup closure for parent workstream.

When it must be used:
- Parent workstream requires explicit closure with rollup evidence.

Required header fields:
- Global minimum plus hierarchy-aware minimum fields.
- thread_role must be closure.
- parent_thread_id required.

Required hierarchy fields:
- thread_role: closure
- parent_thread_id: required
- root_thread_id: inherited
- lineage_depth: parent depth + 1
- rollup_scope: parent_rollup

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- Parent thread
- Child threads included in rollup
- report.md evidence entry

Allowed edge types:
- closes
- child_of
- updates
- references

Required body sections:
- Closure scope
- Included branches
- Rollup decision
- Residual risks

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1105
  task_id: "task_closure_x"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "thread"
  artifact_kind: "closure"
  purpose: "Rollup closure"
  tags: ["closure", "thread_tree"]
  thread_role: "closure"
  parent_thread_id: 1100
  root_thread_id: 1100
  lineage_depth: 1
  rollup_scope: "parent_rollup"
lupopedia.edges:
  outbound_edges:
    - { to: "...parent...md", type: "closes", weight: 1.0, reason: "rollup closure target" }
    - { to: "report.md", type: "updates", weight: 1.0, reason: "closure evidence" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "none"
---
# file: ...
## Closure Scope
## Included Branches
## Rollup Decision
## Residual Risks
```

## 9. Template 7: THREAD_INDEX flat table extension for hierarchy fields

Purpose:
- Preserve backward-compatible flat list while adding deterministic hierarchy metadata.

When it must be used:
- Immediately for all new hierarchy-aware threads.
- During migration for classified legacy threads.

Required columns (extended flat table):

1. thread_id
2. task_id
3. title
4. status
5. actor
6. created_utc
7. updated_utc
8. thread_role
9. parent_thread_id
10. root_thread_id
11. lineage_depth
12. rollup_scope
13. rollup_status
14. classification_confidence

Required hierarchy rules in table values:

1. parent threads use parent_thread_id = 0.
2. legacy_flat uses parent_thread_id = 0 and rollup_scope = none.
3. uncertain classification rows set classification_confidence = provisional.

Minimum viable skeleton:
```markdown
| thread_id | task_id | title | status | actor | created_utc | updated_utc | thread_role | parent_thread_id | root_thread_id | lineage_depth | rollup_scope | rollup_status | classification_confidence |
|-----------|---------|-------|--------|-------|-------------|-------------|-------------|------------------|----------------|---------------|--------------|---------------|---------------------------|
| 1100 | task_parent_x | Parent workstream | active | wolfie | 20260320_150000 | 20260320_150000 | parent | 0 | 1100 | 0 | parent_rollup | in_progress | confirmed |
```

## 10. Template 8: THREAD_INDEX Thread Tree View grouped format

Purpose:
- Provide grouped tree rendering for navigation and rollup visibility.

When it must be used:
- For all roots with one or more hierarchy-aware descendants.

Tree rendering rules (deterministic):

1. Group by root_thread_id ascending.
2. Parent row always rendered first.
3. First sort key for descendants is lineage_depth ascending.
4. Second sort key is role order: child, derived, review, reconciliation, closure.
5. Third sort key is created_utc ascending when available.
6. Final tie-breaker is thread_id ascending.
5. Dependencies are listed in suffix notes and never used for indentation.
6. Use fixed role markers: PARENT, CHILD, DERIVED, REVIEW, RECONCILIATION, CLOSURE, LEGACY_FLAT.

Minimum grouped view skeleton:
```markdown
## Thread Tree View

### Root 1100: Parent workstream (rollup_status: in_progress)
- [PARENT] 1100 task_parent_x status=active owner=wolfie
  - [CHILD] 1101 task_child_x status=active owner=hephaestus
  - [DERIVED] 1102 task_derived_x status=active owner=athena
  - [REVIEW] 1104 task_review_x status=resolved owner=lilith
  - [RECONCILIATION] 1103 task_reconcile_x status=active owner=wolfie
  - [CLOSURE] 1105 task_closure_x status=open owner=wolfie
```

## 11. Template 9: thread classification artifact format for legacy migration

Purpose:
- Classify existing flat threads into hierarchy roles without rewriting historical artifacts.

When it must be used:
- First migration pass for legacy threads.
- Any reclassification dispute.

Required header fields:
- Global minimum plus hierarchy-aware minimum fields where applicable.
- artifact_kind must be classification.

Required hierarchy fields:
- thread_role values for each classified thread.
- parent_thread_id and root_thread_id mapping table.
- classification_confidence values: confirmed or provisional.

Required footer fields:
- last_verified
- last_verified_by
- orchestrator
- next_action

Required edge targets:
- THREAD_INDEX.md
- Source legacy thread artifacts
- WOLFIE adjudication directive when provisional entries exist

Allowed edge types:
- updates
- references
- aligns_with
- addresses

Required body sections:
- Classification scope
- Mapping table
- Provisional entries
- Dispute queue

Minimum viable skeleton:
```markdown
---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "..."
  channel_id: 42
  thread_id: 1029
  task_id: "task_strategy_thread_tree_normalization_001"
  actor_id: 26
  actor_name: "thoth"
  artifact_type: "thread"
  artifact_kind: "classification"
  purpose: "Legacy thread hierarchy classification"
  tags: ["classification", "legacy_flat", "thread_tree"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "updates", weight: 1.0, reason: "classification mapping" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "wolfie: adjudicate provisional mappings"
---
# file: ...
## Classification Scope
## Mapping Table
## Provisional Entries
## Dispute Queue
```

## 12. Template 10: rollup status format for parent workstreams

Purpose:
- Standardize rollup state visibility for root parent threads.

When it must be used:
- Parent thread status update.
- Closure readiness review.

Required rollup status values:

1. not_started
2. in_progress
3. blocked
4. ready_for_closure
5. closed

Required fields in rollup status entry:

1. root_thread_id
2. rollup_status
3. child_total
4. child_resolved
5. child_blocked
6. unresolved_dependencies
7. last_rollup_ymdhis

Minimum viable skeleton:
```markdown
### Rollup Status
- root_thread_id: 1100
- rollup_status: in_progress
- child_total: 5
- child_resolved: 2
- child_blocked: 1
- unresolved_dependencies: [1102, 1103]
- last_rollup_ymdhis: 20260320153045
```

## 13. Legacy_flat and uncertain classification representation

legacy_flat representation rules:

1. thread_role must be legacy_flat.
2. parent_thread_id must be 0.
3. root_thread_id may be the thread_id itself until classified.
4. rollup_scope must be none.
5. classification_confidence should be provisional until confirmed.

Uncertain classification rules:

1. Mark classification_confidence as provisional.
2. Add explicit dispute_reason in classification artifact.
3. Route to WOLFIE for final adjudication.
4. Do not assign parent-child links as confirmed until adjudicated.

## 14. Forward-created hierarchy-aware thread minimum fields

All forward-created hierarchy-aware threads must include:

1. version_when_written
2. file_path_from_root
3. last_modified_utc
4. channel_id
5. thread_id
6. task_id
7. actor_id
8. actor_name
9. artifact_type
10. artifact_kind
11. purpose
12. tags
13. thread_role
14. root_thread_id
15. lineage_depth
16. rollup_scope
17. parent_thread_id when thread_role is not parent and not legacy_flat
18. lupopedia.edges outbound_edges
19. lupopedia.footer next_action

## 15. Adoption order

1. THREAD_INDEX flat table extension.
2. THREAD_INDEX Thread Tree View grouped format.
3. Parent thread template.
4. Child thread template.
5. Derived thread template.
6. Review thread template.
7. Reconciliation thread template.
8. Closure thread template.
9. Legacy classification artifact template.
10. Parent rollup status format.

## 16. Minimum first artifacts WOLFIE should require

1. Parent thread artifact template.
2. Child thread artifact template.
3. THREAD_INDEX flat table extension plus grouped Thread Tree View.

## 17. Implementation handoff boundary for HEPHAESTUS

HEPHAESTUS should implement only:

1. File formatting and template application in thread artifacts.
2. THREAD_INDEX structural rendering updates.
3. Deterministic migration support artifacts for classification and rollup status.

HEPHAESTUS should not implement in this handoff:

1. Schema changes.
2. Hidden synchronization.
3. Doctrine rewrites.

## 18. Audit boundary for LILITH

LILITH should audit:

1. Structural parent constraints: one structural parent maximum.
2. Dependency separation: dependencies not used as structure.
3. Review/reconciliation separation from parentage.
4. legacy_flat and provisional markers applied correctly during migration.
5. Rollup status truthfulness against child thread states.

LILITH should not audit as implementation owner.

_THOTH (actor_id 26) — canonical template source for Channel 42 thread tree normalization._
