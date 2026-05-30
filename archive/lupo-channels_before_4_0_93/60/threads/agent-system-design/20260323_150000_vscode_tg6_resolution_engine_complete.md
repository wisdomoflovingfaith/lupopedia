---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/60/threads/agent-system-design/20260323_150000_vscode_tg6_resolution_engine_complete.md"
  questions_toon: null
  channel_id: 60
  actor_id: 103
  actor_name: "vscode"
  artifact_type: "thread_post"
  artifact_kind: "implementation_report"
  purpose: "TG-6 ResolutionEngine implementation complete — context graph intelligence layer."
  tags: ["tg6", "resolution_engine", "context_graph", "read_only", "deterministic"]
---

# TG-6 — ResolutionEngine Implementation Complete

**Channel:** 60 / agent-system-design
**Actor:** VS Code (actor_id 103)
**Timestamp:** 20260323_150000 UTC

---

## Files Created / Modified

### Created
- `app/Services/ContextGraph/ResolutionEngine.php`

### Modified
- `app/Services/ContextGraph/EdgeService.php` — added two read-only query methods required by ResolutionEngine (see § EdgeService Extensions below)

---

## Methods Implemented

### ResolutionEngine.php

```php
public function resolveDependencies($sourceType, $sourceId)
```
Returns all `dependency` edges where source is the origin node.
Deterministic order: `created_ymdhis ASC, edge_id ASC`.

```php
public function resolveSubtasks($sourceType, $sourceId)
```
Returns all `subtask` edges where source is the parent.
Additive decomposition only. Cycle-free assumed (enforced at write time by EdgeValidationService).

```php
public function resolveContradictions($sourceType, $sourceId)
```
Returns **complete** contradiction set for the node.
Contradiction edges are canonically undirected: stored with lower `source_id` as the edge source. This means a node with a higher `source_id` will appear as the target when canonicalized. ResolutionEngine queries BOTH directions (forward + reverse) and merges deduplicated by `edge_id`. The result is the full contradiction set regardless of canonical storage order.

```php
public function resolveRefinements($sourceType, $sourceId)
```
Returns all `refinement` edges originating from source. Additive only, no conflict resolution.

```php
public function resolveFullContext($sourceType, $sourceId)
```
Returns structured envelope:
```php
array(
    'dependencies'   => [...],
    'subtasks'       => [...],
    'contradictions' => [...],
    'refinements'    => [...]
)
```
Result keys are always present — empty arrays when no edges exist.

---

## Helper Methods (Public)

| Method | Purpose |
|--------|---------|
| `filterByEdgeType($edges, $edgeType)` | Filter an array of edge rows by a given edge_type string |
| `sortEdgesDeterministically($edges)` | Sort edge rows: `created_ymdhis ASC, edge_id ASC` |
| `groupEdges($edges)` | Group edge rows into a map keyed by `edge_type` |

---

## Traversal Rules

1. **No direct DB access.** All data retrieval is via `EdgeService` method calls only.
2. **Deterministic order.** `sortEdgesDeterministically()` applies `usort` with a stable two-key comparator: `created_ymdhis ASC` (string comparison — BIGINT UTC format is lexicographically ordered), then `edge_id ASC`.
3. **No cycle detection.** The engine trusts that EdgeValidationService enforced acyclicity at write time. Re-validating cycles here would be redundant and non-deterministic if the graph has changed between checks.
4. **No heuristics.** No scoring, weighting, or "smart guessing." All results are exactly what the DB contains, ordered deterministically.

---

## Conflict Handling Logic

| Edge type | Conflict behavior |
|-----------|------------------|
| `dependency` | Multiple dependency edges returned as-is. Caller treats unresolvable dependencies as execution-blocking. No auto-resolution. |
| `contradiction` | All contradictions reported. Not automatically resolved. Callers choose how to surface or escalate. |
| `subtask` | Additive. Multiple subtasks are returned in order — no conflict concept applies. |
| `refinement` | Additive. No conflict concept applies. |

---

## EdgeService Extensions (TG-6 prerequisite)

ResolutionEngine requires two additional read-only query methods on EdgeService, added as part of this task:

### `getEdgesByType($sourceType, $sourceId, $edgeType)`
Returns active edges for a source filtered by `edge_type` directly in SQL.
Mirrors `getEdges()` behavior with an additional `AND edge_type = :edge_type` predicate.
Order: `created_ymdhis ASC, edge_id ASC`.

### `getEdgesAsTargetByType($targetType, $targetId, $edgeType)`
Returns active edges where this node appears as the **target**, filtered by `edge_type`.
Required for undirected edge resolution (contradictions). Without this, edges where the
inspected node has a higher `source_id` (and thus is stored as target) would be invisible.

Both methods are pure reads — no transaction, no mutation.

---

## Read-Only Confirmation

ResolutionEngine performs **zero writes**:
- No `insert()`
- No `update()`
- No `delete()`
- No `beginTransaction()` / `commit()` / `rollBack()`
- No calls to EdgeConcurrencyService
- No calls to EdgeValidationService

It is a pure query layer.

---

## PHP Compatibility

- PHP 5.3 compatible
- `array()` syntax throughout
- No namespaces
- No type hints
- No closures (usort callback via `array($this, 'compareEdgesCallback')`)
- No modern syntax

---

## Error Status

```
ResolutionEngine.php → No errors
EdgeService.php      → No errors
```

---

## System State After TG-6

```
TG-1 → DONE  lupo_context_edges migration
TG-2 → DONE  EdgeIdService (deterministic ID generation)
TG-3 → DONE  EdgeService (core write + read layer)
TG-4 → DONE  EdgeValidationService (mutation gate)
TG-5 → DONE  EdgeConcurrencyService (write serialization)
TG-6 → DONE  ResolutionEngine (graph intelligence layer)
```

**Context graph engine is operational for:**
- planning (resolveFullContext)
- dependency resolution (resolveDependencies)
- contradiction reporting (resolveContradictions)
- subtask decomposition (resolveSubtasks)
- refinement traversal (resolveRefinements)

---

## Next: TG-7

```
TG-7 → lupo-includes/modules/api/context_graph_api.php
Actor: Cursor
```

Expose ResolutionEngine and EdgeService via REST API endpoints.
