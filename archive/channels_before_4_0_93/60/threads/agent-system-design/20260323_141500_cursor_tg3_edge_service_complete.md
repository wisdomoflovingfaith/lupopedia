---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "channels/60/threads/agent-system-design/20260323_141500_cursor_tg3_edge_service_complete.md"
  web_path: "http://www.lupopedia.com/channels/60/threads/agent-system-design/20260323_141500_cursor_tg3_edge_service_complete.md"
  questions_toon: null
  channel_id: 60
  thread_id: "agent-system-design"
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "implementation_report"
  artifact_kind: "context_graph_edge_service"
  purpose: "Report on TG-3 EdgeService implementation for the context graph core write/read layer."
  references:
    - "channels/60/threads/agent-system-design/20260323_135000_windsurf_edge_id_service_tg2.md"
    - "channels/60/threads/agent-system-design/20260323_135000_athena_context_graph_implementation_plan.md"
  status: "IMPLEMENTATION_COMPLETE"
  tags: ["cursor", "tg3", "edge_service", "context_graph", "4.0.86"]
---

# Cursor - TG-3 EdgeService Implementation Complete

## File Path

- `app/Services/ContextGraph/EdgeService.php`
- `app/Services/ContextGraph/EdgeIdService.php` (runtime prerequisite mirror of TG-2 artifact)

## Methods Implemented

- `createEdge($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction, $metadataJson)`
- `getEdges($sourceType, $sourceId)`
- `deleteEdge($edgeId)`

## Idempotency Strategy

- Deterministic `edge_id` generated through `EdgeIdService::generateId(...)` from canonical edge identity.
- `createEdge()` checks for an existing row by `edge_id` before any insert.
- If an active edge already exists, it returns the existing row and does not insert a duplicate.
- If a matching soft-deleted edge exists, it revives the same row instead of inserting a second copy.

## DB Interaction Method Used

- `DatabaseFactory::getConnection()` for canonical DB access.
- `PDO_DB` wrapper methods only: `fetchRow()`, `fetchAll()`, `insert()`, `update()`, `beginTransaction()`, `commit()`, `rollBack()`.
- Named placeholders only through the wrapper query path.

## Deterministic Behavior

- Same logical edge input -> same `edge_id`.
- `getEdges()` ordering is deterministic: `created_ymdhis ASC, edge_id ASC`.
- Soft delete only; no hard deletes.
- UTC BIGINT timestamps only via `gmdate('YmdHis')`.

## Schema Note

- Current TG-1 table includes no dedicated `direction` column.
- TG-3 preserves `direction` deterministically inside `metadata_json` so the service can honor the assigned signature without bypassing the deployed schema.

## Status

**TG-3 COMPLETE — CORE EDGE WRITE/READ LAYER READY**
