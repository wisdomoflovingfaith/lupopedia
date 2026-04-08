---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260330163000"
  file_path_from_root: "lupo-docs/prd/15_temporal_system.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/15_temporal_system.md"
  last_modified_utc: "20260331093000"
  channel_id: 42
  thread_id: "temporal-authority-doctrine"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "doctrine"
  purpose: "PRD establishing Canonical UTC Authority and temporal system doctrine"
  tags:
  - "prd"
  - "doctrine"
  - "temporal"
  - "utc"
  - "timestamp"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-bin/tick.py"
      type: implements
      weight: 1.0
      reason: "Canonical UTC timestamp generation"
    - to: "lupo-bin/temporal_anchor.json"
      type: references
      weight: 1.0
      reason: "Structured temporal anchor storage"
    - to: "/CURRENT_UTC"
      type: references
      weight: 1.0
      reason: "Root-level UTC timestamp file"
lupopedia.footer:
  last_verified: "20260331180000"
  verified_by:
    agent_id: 2
    agent_name_identity: "LILITH"
  orchestrator: "lilith:audit"
---

# PRD: Temporal System - Canonical UTC Authority

## Overview

**Namespace Purpose:** Establishes the single source of truth for all temporal operations in Lupopedia, enforcing UTC-only timestamps with a canonical authority.

**Primary Actor:** 
- Temporal Authority (via lupo-bin/tick.py)

**Constitutional Compliance:** All temporal operations must follow the UTC Doctrine defined in the Root Constitutional System Requirements.

## Canonical UTC Authority Doctrine
## Constitutional Compliance

This PRD enforces the following constitutional rules:

| Doctrine | Rule | Implementation |
|----------|------|----------------|
| Database Doctrine #4 | NO DATETIME/TIMESTAMP types | All timestamps stored as BIGINT |
| Time & Planning Doctrine #2 | All time comparisons use BIGINT UTC | Comparisons use 14-digit integers |
| Time & Planning Doctrine #4 | NO human-friendly time parsing | No DATE_FORMAT, FROM_UNIXTIME, etc. |
| Multi-Agent Safety Doctrine #2 | All agents use BIGINT timestamps | tick.py is the ONLY timestamp source |

**Violation:** Any component generating timestamps independently is in violation of constitutional doctrine.

## Database Integration

All database tables across all namespaces MUST use:
- **Column type**: `BIGINT` (never DATETIME, never TIMESTAMP)
- **Format**: `YYYYMMDDHHMMSS` (14-digit UTC)
- **Generation**: From tick.py output, never database functions like `NOW()`, `CURRENT_TIMESTAMP`, or `UNIX_TIMESTAMP()`

**Affected tables include but are not limited to:**
- `lupo_actors.created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis`
- `lupo_auth_users.created_ymdhis`, `updated_ymdhis`, `last_login_ymdhis`
- `lupo_sessions.created_ymdhis`, `updated_ymdhis`, `expires_ymdhis`
- `lupo_actor_memory.created_ymdhis`, `updated_ymdhis`
- `lupo_agent_heartbeats.created_ymdhis`, `updated_ymdhis`
- All timestamp columns in all 166+ tables

**See also:** Database Doctrine #4 in `lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md`

## tick.py Execution Strategy (Optional)

- **On-demand**: Called whenever a timestamp is needed
- **Caching**: Values can be cached for up to 1 second
- **Cron**: Optional cron job can update `/CURRENT_UTC` every second for availability
- **Fallback**: If `/CURRENT_UTC` is stale (> 2 seconds), call tick.py directly

## Affected Tables (from 01_core_identity.md)

All tables with timestamp columns MUST use tick.py-sourced values:
- `lupo_actors.created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis`
- `lupo_auth_users.created_ymdhis`, `updated_ymdhis`, `last_login_ymdhis`
- `lupo_sessions.created_ymdhis`, `updated_ymdhis`, `expires_ymdhis`
- `lupo_actor_memory.created_ymdhis`, `updated_ymdhis`
- And all other timestamp columns across all tables

### Rule 1: Single Source of Truth
Lupopedia does not use system-local time.
Lupopedia does not use file timestamps.
Lupopedia does not infer dates from context.
All timestamps come exclusively from lupo-bin/tick.py.

### Rule 2: tick.py Responsibilities
lupo-bin/tick.py must:
- Generate current UTC timestamp using datetime.utcnow()
- Write it to /CURRENT_UTC (root level)
- Write it to lupo-bin/temporal_anchor.json (structured)
- Use 14-digit UTC format: YYYYMMDDHHMMSS
- Never use Unix epoch, ISO8601, or timezone offsets
- Never use now() or system-local time

### Rule 3: IDE Integration Rule
The IDE must:
- Treat /CURRENT_UTC as the single source of truth
- Treat temporal_anchor.json as a structured mirror
- Never infer dates or generate timestamps independently
- Always call or reference tick.py when timestamps are needed

### Rule 4: Format Standard
All timestamps in Lupopedia use the 14-digit UTC format:
- Format: YYYYMMDDHHMMSS
- Example: 20260331093000
- No timezone offsets
- No ISO8601
- No Unix epoch

## Implementation Details

### tick.py Script
```python
# Path definitions
ANCHOR_PATH = os.path.join(os.path.dirname(__file__), 'temporal_anchor.json')
CURRENT_UTC_PATH = os.path.join(os.path.dirname(os.path.dirname(__file__)), 'CURRENT_UTC')

# UTC generation
now_utc = datetime.now(timezone.utc)
current_utc = now_utc.strftime('%Y%m%d%H%M%S')
```

### File Structure
```
/CURRENT_UTC                    # Root-level timestamp file (plain text)
lupo-bin/temporal_anchor.json   # Structured temporal data
```

### temporal_anchor.json Schema
```json
{
  "current_utc": "YYYYMMDDHHMMSS",
  "last_session_end": "YYYYMMDDHHMMSS",
  "system_year": "YYYY",
  "format_standard": "YYYYMMDDHHMMSS"
}
```

## Usage Guidelines

### For Tools, Agents, and IDE Components
1. **Reading Current Time**: Read from /CURRENT_UTC
2. **Structured Temporal Data**: Read from lupo-bin/temporal_anchor.json
3. **Generating Timestamps**: Call lupo-bin/tick.py
4. **Never Infer**: Do not guess dates from context or system time

### For Developers
1. **All Timestamp Columns**: Use BIGINT with application-layer generation
2. **UTC Only**: Never store local time or timezone offsets
3. **Format Consistency**: Use YYYYMMDDHHMMSS throughout
4. **Reference Authority**: Always source from tick.py output

### For Documentation
When discussing timestamps, migrations, or temporal operations:
- Reference this PRD as the authority
- Remind readers of the Canonical UTC Authority
- Point to lupo-bin/tick.py as the implementation

## Enforcement

### Validation Rules
- Any component generating timestamps independently is in violation
- Any component using local time is in violation
- Any component inferring dates from context is in violation
- Valid timestamps come only from tick.py execution

## Cross-References

- **Implemented by**: lupo-bin/tick.py
- **Enforced by**: All Lupopedia components
- **Documented in**: lupo-docs/prd/00_root_constitutional_system_requirements.md
- **Required by**: All temporal operations in Lupopedia


---

## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A → B)
  - bidirectional (A ↔ B)
  - restricted-direction (A → B but not B → A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported → supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```
