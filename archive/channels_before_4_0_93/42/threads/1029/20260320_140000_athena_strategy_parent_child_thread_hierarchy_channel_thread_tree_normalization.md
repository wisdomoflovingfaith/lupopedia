---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "channels/42/threads/1029/20260320_140000_athena_strategy_parent_child_thread_hierarchy_channel_thread_tree_normalization.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1029/20260320_140000_athena_strategy_parent_child_thread_hierarchy_channel_thread_tree_normalization.md"
  last_modified_utc: "20260320"
  channel_id: 42
  thread_id: 1029
  task_id: "task_strategy_thread_tree_normalization_001"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:strategy"
  artifact_type: "thread"
  artifact_kind: "strategy_decision"
  purpose: "Decisive strategy for parent-child thread hierarchy and thread tree normalization for channel coordination"
  tags: ["athena", "strategy", "thread_tree", "parent_child", "channel_42", "task_strategy_thread_tree_normalization_001", "4.0.84"]
  message_type: "strategy"
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/THREAD_INDEX.md", type: "updates", weight: 1.0, reason: "THREAD_INDEX requires structural hierarchy sections and rollup visibility" }
    - { to: "channels/42/threads/1028/20260320_120000_athena_strategy_file_visible_coordination_recovery.md", type: "aligns_with", weight: 1.0, reason: "Thread tree model extends header-first operational control" }
    - { to: "docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "constrained_by", weight: 1.0, reason: "Canonical minimum header doctrine remains unchanged" }
    - { to: "docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "aligns_with", weight: 1.0, reason: "Header block usage and optional block discipline" }
    - { to: "rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "constrained_by", weight: 1.0, reason: "Multi-agent role and coordination constraints" }
    - { to: "docs/database/lupopedia/tables/active/lupo_dialog_threads.md", type: "references", weight: 1.0, reason: "Current thread table has no canonical parent_thread_id field for hierarchy" }
    - { to: "docs/database/lupopedia/tables/active/lupo_dialog_messages.md", type: "references", weight: 0.9, reason: "Message linkage depends on dialog_thread_id and must preserve thread continuity" }
    - { to: "docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.9, reason: "Channel model already contains parent_channel_id and hierarchy precedent" }
    - { to: "docs/database/lupopedia/tables/active/lupo_tasks.md", type: "references", weight: 0.9, reason: "Task model includes parent_task_id and supports parent-child planning precedent" }
    - { to: "docs/database/lupopedia/tables/active/lupo_task_dependencies.md", type: "references", weight: 0.9, reason: "Dependency relation must remain distinct from hierarchy" }
    - { to: "docs/database/lupopedia/tables/active/lupo_edges.md", type: "references", weight: 0.9, reason: "Edge vocabulary guidance for explicit relation typing" }
    - { to: "docs/database/lupopedia/tables/active/lupo_metadata.md", type: "references", weight: 0.9, reason: "Metadata storage and parent_metadata_id precedent for hierarchical metadata" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "athena"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE: approve thread tree model and issue implementation directives to THOTH and HEPHAESTUS"
    - "THOTH: publish canonical thread hierarchy templates and THREAD_INDEX grouped format examples"
    - "LILITH: audit migration classification for structural, dependency, and review/reconciliation separation"
---
# file: ATHENA Strategy for Parent-Child Thread Hierarchy and Channel Thread Tree Normalization

This is a planning and doctrine strategy artifact. It is not implementation, schema change, or automation.

Required reading:

1. channels/42/THREAD_INDEX.md
2. channels/42/threads/1028/20260320_120000_athena_strategy_file_visible_coordination_recovery.md
3. docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md
4. docs/doctrine/LUPOPEDIA_HEADERS/README.md
5. rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md
6. docs/database/lupopedia/tables/active/lupo_dialog_threads.md
7. docs/database/lupopedia/tables/active/lupo_dialog_messages.md
8. docs/database/lupopedia/tables/active/lupo_channels.md
9. docs/database/lupopedia/tables/active/lupo_tasks.md
10. docs/database/lupopedia/tables/active/lupo_task_dependencies.md
11. docs/database/lupopedia/tables/active/lupo_edges.md
12. docs/database/lupopedia/tables/active/lupo_metadata.md

## 1. Current state determination

Decision:

1. Lupopedia currently has no canonical channel-thread tree model for coordination artifacts.
2. The current file-visible model in THREAD_INDEX is flat and tabular.
3. The active dialog thread table documentation shows dialog_thread_id, channel_id, and status fields but no canonical parent_thread_id in lupo_dialog_threads.
4. Therefore hierarchy is currently inferred informally, not declared canonically.

## 2. Adopted model decision

Decision:

1. Adopt a parent-child thread tree model for Channel 42 coordination.
2. Implement as a file-visible operational model first.
3. Keep database role structural and archival for this phase.
4. No schema change in this strategy phase.

Model shape:

1. Top-level parent thread: coordination umbrella for a workstream.
2. Child thread: scoped execution branch under one parent thread.
3. Derived thread: follow-on branch created from parent or child due to scope expansion.
4. Reconciliation thread: resolves conflicts between sibling or branch outcomes.
5. Review thread: independent review branch targeting specific parent or child thread outputs.
6. Closure thread: final branch that performs rollup closure for a parent workstream.

## 3. Relationship taxonomy and strict separation

Required relationship types:

1. parent_thread_id
2. child_thread_id list
3. derived_from_thread_id
4. reconciles_thread_ids
5. reviews_thread_id
6. closes_thread_id
7. depends_on_thread_ids

Do not conflate these categories:

1. Structural hierarchy relation:
- parent_thread_id and child_thread_ids.
- Means containment in thread tree.
2. Dependency relation:
- depends_on_thread_ids.
- Means execution order constraint only.
3. Review or reconciliation relation:
- reviews_thread_id and reconciles_thread_ids.
- Means quality or conflict handling relation, not ownership of execution scope.

## 4. File-visible model for active actors

Because active actors read files and headers, thread tree truth must be file-visible.

Required file-visible hierarchy fields for thread artifacts in Channel 42 coordination profile:

1. parent_thread_id where child role is used.
2. root_thread_id for all descendants.
3. thread_role with allowed values: parent, child, derived, reconciliation, review, closure.
4. lineage_depth integer for deterministic tree rendering.
5. rollup_scope with values: none, local, parent_rollup.

Required edge conventions:

1. Structural edge types:
- parent_of
- child_of
- derived_from
2. Dependency edge type:
- depends_on
3. Review and reconciliation edge types:
- reviews
- reconciles
- closes

Operational rule:

1. A thread may have one structural parent only.
2. A thread may have multiple dependencies.
3. A thread may review or reconcile across branches without becoming structural parent.

## 5. THREAD_INDEX.md changes required

THREAD_INDEX must evolve from flat list to grouped tree view while preserving backward-readable tabular rows.

Required changes:

1. Keep existing Thread Directory table for compatibility.
2. Add new section: Thread Tree View.
3. In Thread Tree View, group by root parent thread.
4. Show child nesting with explicit role labels.
5. Add rollup status line for each parent thread.
6. Add navigation links for parent, child, reconciliation, review, and closure branches.

Required index fields for tree visibility:

1. thread_id
2. parent_thread_id
3. root_thread_id
4. thread_role
5. task_id
6. status
7. owner_actor
8. rollup_status

## 6. Header and edge conventions required

Global minimum doctrine remains unchanged:

1. version_when_written
2. file_path_from_root

Coordination hierarchy profile additions for hierarchy-aware thread artifacts:

1. parent_thread_id when role is not parent.
2. root_thread_id for all hierarchy-aware artifacts.
3. thread_role required for all hierarchy-aware artifacts.
4. lineage_depth required for deterministic rendering.

Required edge discipline:

1. Every hierarchy-aware thread artifact must include outbound_edges.
2. At least one structural edge must exist for child, derived, reconciliation, review, and closure roles.
3. Dependency edges must never be used to imply structural parentage.

## 7. Migration strategy for existing Channel 42 threads

No history rewrite. No retroactive file content edits required for legacy thread bodies.

Phase 1: classification baseline

1. Create one classification artifact mapping existing threads to proposed root and role.
2. Mark uncertain mappings as provisional.
3. Require WOLFIE approval for disputed mapping.

Phase 2: index normalization

1. Update THREAD_INDEX with Thread Tree View section.
2. Preserve existing flat table unchanged except additional fields.
3. Add explicit rollup status for each root parent thread.

Phase 3: forward enforcement

1. New threads must declare thread_role and root_thread_id.
2. Child, derived, reconciliation, review, and closure roles must declare parent_thread_id or target thread relation fields.
3. Legacy threads remain valid but tagged legacy_flat when no hierarchy fields exist.

## 8. Rule for creating child versus top-level thread

Create a child thread when:

1. Work is a scoped branch of an existing parent objective.
2. Outcome must roll up into parent closure.
3. Ownership or implementation path differs but strategic objective remains same.

Create a top-level parent thread when:

1. Strategic objective is independent.
2. Rollup closure will not be owned by an existing parent.
3. Dependencies may exist but structural containment does not.

Create derived thread when:

1. Scope changed significantly from originating thread and needs separate lifecycle.
2. The origin still requires lineage visibility.

Create reconciliation thread when:

1. Two or more branch outputs conflict and require explicit convergence decision.

Create review thread when:

1. Independent quality or doctrine review is needed without transferring execution ownership.

Create closure thread when:

1. Parent workstream requires explicit end-state rollup across multiple children.

## 9. Channel 66 model decision

Decision:

1. Channel 66 uses a restricted hierarchy model.
2. Default model for Channel 66 is question-thread model with minimal branching.
3. Allowed roles in Channel 66: parent question, doctrine answer, review.
4. Reconciliation and closure threads in Channel 66 are allowed only when WOLFIE marks doctrine dispute or multi-answer conflict.
5. Channel 66 must not be used as generic implementation tree channel.

## 10. Decisive recommendation to WOLFIE

Adopt the parent-child thread hierarchy model now as file-visible operational control for Channel 42.

What files must change first:

1. channels/42/THREAD_INDEX.md
2. channels/42/threads/README.md
3. TODO.md for task-thread hierarchy mapping notes
4. plan.md for migration phase sequencing
5. report.md for hierarchy migration evidence tracking

No schema changes in this phase.

## 11. Next actor assignments

1. THOTH:
- Produce hierarchy template pack for parent, child, derived, reconciliation, review, closure thread artifacts and index tree formatting.
2. HEPHAESTUS:
- Implement file-level updates for THREAD_INDEX grouped tree view and forward-use artifact scaffolds.
3. LILITH:
- Audit role-separation correctness: structural versus dependency versus review/reconciliation relationships.
4. WOLFIE:
- Approve disputed legacy classification and enforce forward adoption gate.

## 12. Deterministic migration ordering

1. Publish classification artifact.
2. Approve mapping decisions.
3. Add tree sections to THREAD_INDEX.
4. Enforce hierarchy fields on new thread artifacts.
5. Run first audit cycle for separation correctness.

This strategy gives a doctrine-aligned, file-visible, deterministic thread tree model without schema changes, hidden sync, or ambiguous relationship semantics.

_ATHENA (actor_id 12) — strategy for Channel 42 thread tree normalization._
