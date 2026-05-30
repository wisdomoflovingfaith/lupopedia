---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Garbage_Collection_System_with_Unified_Table_Architecture.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Garbage_Collection_System_with_Unified_Table_Architecture.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-94"
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
# D-94: Garbage Collection System with Unified Table Architecture

## Type
**Decision**

## Status
**Completed**

## Author
**WOLFIE** (actor_id 1) - System Architect

## Date
2026-04-01

### Context
Lupopedia needs a modern garbage collection system that preserves the 2003 `gc.php` pattern while supporting unified table architecture with content-specific analytics.

### Decision
- Created `docs/prd/19_garbage_collection_system.md` with unified table approach
- Implemented `includes/classes/GarbageCollector.php` with random execution pattern
- Created `scripts/gc.php` for CLI/cron execution
- Created `docs/doctrine/GC_DOCTRINE.md` documenting 2003 pattern wisdom
- Used single tables with date_ymd columns instead of separate daily/monthly tables
- Added content-specific tracking for visits and referrers
- Preserved 1% random execution and self-limiting (10,000 rows per run)

### Consequences
- Unified table architecture reduces schema complexity while maintaining all aggregation capabilities
- Content-specific analytics enable detailed page performance tracking
- Random execution spreads load across requests, preventing server spikes
- Self-limiting prevents table locks on shared hosting
- Preserves proven 2003 pattern that kept 1.2M installations running for 10 years unattended

### Comments
*2026-04-01 WOLFIE*: If it ran unattended for a decade, it's not legacy. It's proven.
*2026-04-01 LILITH*: Unified tables with date_ymd pattern is elegant and scalable.

---

## Key Lessons Learned

1. **JSON Schema Management**: Never manually edit JSON files; always update SQL first, then regenerate.
2. **Large SQL Files**: AI IDEs have token limits; manual editing in Notepad++ is acceptable for large migrations.
3. **Cross-Thread Coordination**: Always read latest before writing; make incremental edits.
4. **Versioned Documentation**: Keep decisions and actions in single canonical file with author attribution.
5. **Agent Configuration Pattern**: Each agent requires 4 files (agent.json, capabilities.json, properties.json, system_prompt.txt) with consistent structure.
6. **Channel chat transport**: Extend `channels-api.php` for buffer/image fallbacks; use `api/channels/...` in clients; keep full channel UI on `/channels/` via index routing.
7. **Multi-Agent Coordination**: When multiple actors work simultaneously, each should document their contributions with proper attribution and headers to maintain traceability.
8. **WOLFIE Doctrine**: Code that outran its author for 11 years is not "legacy" - it's proven architecture. Protect it from framework bloat.
9. **Actor-Agent Distinction**: Agents are immutable templates in filesystem; actors are runtime instances that learn from department context. Never treat them as synonyms.
10. **Cascade Workflow**: Document the pattern (Cursor writes, Windsurf docs, Kiro verifies) for future multi-agent systems.

**Next Review**: 2026-04-07
**Canonical Reference**: This file is the single source of truth for decisions and action items for Lupopedia 4.0.93.

---
