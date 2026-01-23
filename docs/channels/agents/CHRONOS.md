---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.46
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "00FF00"
  message: "Created CHRONOS agent documentation - UTC timestamp agent (agent_id 23) for YYYYMMDDHHIISS format handling."
tags:
  categories: ["documentation", "agents", "temporal"]
  collections: ["core-docs"]
  channels: ["dev", "public"]
file:
  title: "CHRONOS Agent Specification"
  description: "UTC timestamp agent for temporal coordination and YYYYMMDDHHIISS format handling"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# CHRONOS — Temporal Coordination Engine

**Agent ID:** 23  
**Agent Key:** `chronos`  
**Archetype:** kernel  
**Status:** Core Agent (v4.0.2 Required)

## Purpose

CHRONOS provides time awareness, UTC timestamp handling, and temporal coordination for the Lupopedia Semantic OS. All timestamps in Lupopedia use BIGINT(14) UTC format: `YYYYMMDDHHIISS`.

## Core Capabilities

### 1. UTC Timestamp Generation
- `now()` - Returns current UTC timestamp as BIGINT(14) YYYYMMDDHHIISS
- Uses `timestamp_ymdhis::now()` internally
- Always UTC, never local time

### 2. Timezone Conversion
- `toLocal(int $ts, float $offset)` - Convert UTC to actor-local time
- `toUTC(int $localTs, float $offset)` - Convert local time back to UTC
- Offset format: DECIMAL(4,2) hours (e.g., -10.0 for HST)

### 3. Temporal Operations
- `explode(int $ts)` - Break timestamp into components (year, month, day, hour, minute, second)
- `implode(array $components)` - Reconstruct timestamp from components
- `addSeconds()`, `addMinutes()`, `addHours()` - Arithmetic operations
- `diffInSeconds()` - Calculate time differences
- `isBefore()`, `isAfter()`, `isBetween()` - Comparison operations

### 4. Formatting
- `toHuman(int $ts)` - Format as "YYYY-MM-DD HH:MM:SS UTC"
- `fromHuman(string $str)` - Parse human-readable format to BIGINT

### 5. Interval Management
- `interval(int $start, int $end)` - Create time interval
- `overlaps(array $a, array $b)` - Check interval overlap
- `intersection(array $a, array $b)` - Find overlapping period
- `shift(array $interval, int $seconds)` - Move interval in time
- `expand(array $interval, int $seconds)` - Expand interval duration

## Doctrine Compliance

**Temporal Doctrine:**
- All stored timestamps are BIGINT(14) UTC (YYYYMMDDHHIISS)
- Display-time conversion uses `actor_profiles.timezone_offset`
- Local recurring events may store local-time BIGINTs (rare exception)
- No DATETIME, TIMESTAMP, or epoch storage

## Implementation Status

**Phase 1 (Current):**
- ✅ Basic time awareness (`now()`, `toLocal()`, `toUTC()`)
- ✅ Temporal operations (arithmetic, comparison, formatting)
- ✅ Interval management (overlap, intersection, shift, expand)

**Phase 2 (Planned):**
- ⏳ Sequencing optimization (`analyzeSequence()`, `optimizeSequence()`)
- ⏳ Temporal reasoning (`analyzeTemporalRelationship()`)
- ⏳ Inter-agent coordination (`coordinateAgents()`)
- ⏳ Deadline & scheduling (`scheduleTask()`, `checkOverdueTasks()`)

## Class Location

**File:** `lupo-includes/class-chronos.php`  
**Dependencies:** `lupo-includes/class-timestamp_ymdhis.php`

## Usage Examples

```php
$chronos = new CHRONOS($db);

// Get current UTC timestamp
$now = $chronos->now(); // Returns: 20260116120000

// Convert to local time (HST, UTC-10)
$local = $chronos->toLocal($now, -10.0);

// Format for display
$human = timestamp_ymdhis::toHuman($now); // "2026-01-16 12:00:00 UTC"

// Check if timestamp is before another
$isBefore = timestamp_ymdhis::isBefore($ts1, $ts2);
```

## Integration Points

- **TEMPORAL_BRIDGE.md** - Reference for temporal alignment
- **Database Schema** - All timestamp fields use BIGINT(14)
- **Agent Coordination** - Provides temporal context for inter-agent operations
- **Migration Orchestrator** - Uses CHRONOS for timestamp validation

## Related Agents

- **THOTH** (Agent 5) - Verification and validation (may use CHRONOS for temporal validation)
- **CHRONOS** (Agent 23) - Temporal coordination (this agent)

## Notes

- CHRONOS is NOT agent_id 5 (that's THOTH)
- CHRONOS is agent_id 23
- All Lupopedia timestamps follow YYYYMMDDHHIISS format
- Timezone conversion is display-only; storage is always UTC
