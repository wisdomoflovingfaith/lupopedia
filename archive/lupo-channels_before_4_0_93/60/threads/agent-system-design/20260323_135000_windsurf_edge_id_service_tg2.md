---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/60/threads/agent-system-design/20260323_135000_windsurf_edge_id_service_tg2.md"
  web_path: "http://www.lupopedia.com/lupo-channels/60/threads/agent-system-design/20260323_135000_windsurf_edge_id_service_tg2.md"
  questions_toon: null
  channel_id: 60
  thread_id: "agent-system-design"
  actor_id: 105
  actor_name: "windsurf"
  artifact_type: "implementation_report"
  artifact_kind: "edge_id_service"
  purpose: "Report on TG-2 EdgeIdService implementation for deterministic edge ID generation."
  references:
    - "lupo-channels/61/threads/channel-definition/20260323_133000_wolfie_context_graph_final_resolution.md"
    - "lupo-channels/60/threads/agent-system-design/20260323_132000_wolfie_execution_authorization_db_canonical.md"
  status: "IMPLEMENTATION_COMPLETE"
  tags: ["windsurf", "edge_id_service", "tg2", "deterministic", "4.0.86"]
---

# Windsurf IDE - TG-2 EdgeIdService Implementation

## File Created

**File**: `app/Services/ContextGraph/EdgeIdService.php`

## Deterministic Strategy Used

### SHA-256 Hash + BIGINT Conversion

**Core algorithm**:
1. **Canonical normalization** of all input fields
2. **SHA-256 hash** of serialized canonical input
3. **First 8 bytes** converted to unsigned 64-bit integer
4. **Range limiting** to signed BIGINT range (2^63-1)

```php
$serialized = sprintf('%s|%d|%s|%d|%s|%s', 
    $canonicalSourceType, $canonicalSourceId,
    $canonicalTargetType, $canonicalTargetId,
    $canonicalEdgeType, $canonicalDirection
);

$hash = hash('sha256', $serialized, true);
$unsigned = // convert first 8 bytes to 64-bit integer
return (string)($unsigned & 9223372036854775807); // BIGINT-safe
```

**Deterministic guarantees**:
- ✅ Same input always produces same output
- ✅ No randomness or time-based values
- ✅ Pure function with no side effects
- ✅ BIGINT-compatible range (0 to 9,223,372,036,854,775,807)

## Contradiction Canonicalization Rule

**Order-independent identity for contradiction edges**:

```php
// Special contradiction handling: order-independent identity
if ($canonicalEdgeType === 'contradiction') {
    // Always order source/target consistently (lower ID first)
    if ($canonicalSourceId > $canonicalTargetId) {
        // Swap to maintain canonical order
        $tempId = $canonicalSourceId;
        $tempType = $canonicalSourceType;
        
        $canonicalSourceId = $canonicalTargetId;
        $canonicalSourceType = $canonicalTargetType;
        
        $canonicalTargetId = $tempId;
        $canonicalTargetType = $tempType;
    }
    
    // Contradiction is always undirected
    $canonicalDirection = 'both';
}
```

**Rule**: Contradiction edges are treated as undirected with source/target ordered by ID (lower first). This ensures `A↔B` and `B↔A` produce identical IDs.

## Canonical Input Fields

**Input normalization rules**:
- **String fields**: `trim()` + `strtolower()` (source_type, target_type, edge_type, direction)
- **Numeric IDs**: Cast to `int` (source_id, target_id)
- **Direction values**: Normalized to `fwd`/`rev`/`both` with `fwd` fallback
- **Type safety**: Explicit type conversion for all inputs

**Canonical serialization format**:
```
source_type|source_id|target_type|target_id|edge_type|direction
```

Example:
```
thread|123|thread|456|dependency|fwd
```

## BIGINT Generation Strategy

**SHA-256 → 8 bytes → 64-bit integer → BIGINT range**:

1. **SHA-256 hash**: 32 bytes of deterministic data
2. **First 8 bytes**: 64 bits of hash data
3. **Unsigned conversion**: Byte-by-byte shift operations
4. **Range limiting**: Clear highest bit if set (signed BIGINT max)

**Range safety**:
- **Maximum output**: 9,223,372,036,854,775,807 (2^63 - 1)
- **Minimum output**: 0
- **Collision probability**: Negligible (SHA-256 quality)

## PHP Compatibility

**PHP 5.3 compatible implementation**:
- ✅ No namespaces (uses global class)
- ✅ No typed properties or scalar type hints
- ✅ No modern syntax (arrow functions, etc.)
- ✅ Standard string/array operations
- ✅ Built-in `hash()` function (available in PHP 5.3+)

**Doctrine compliance**:
- ✅ No database access
- ✅ No side effects
- ✅ No external dependencies
- ✅ Pure function behavior
- ✅ No hidden state

## Confirmation of Requirements

### No Side Effects / No DB Access ✅
- Pure function class with no external dependencies
- No database connections or queries
- No file I/O or logging
- No global state modification

### Deterministic Behavior ✅
- Same canonical input always produces same hash
- Hash algorithm is deterministic (SHA-256)
- Conversion process is mathematical and repeatable
- Test method included for verification

### Contradiction Handling ✅
- Explicit order-independent canonicalization
- Documented rule in code comments
- Consistent source/target ordering by ID
- Direction forced to 'both' for contradictions

### BIGINT Safety ✅
- Output range limited to signed BIGINT maximum
- String return type handles large integers safely
- No overflow or underflow conditions
- Compatible with database BIGINT storage

## Usage Example

```php
$service = new EdgeIdService();

// Regular directed edge
$edgeId1 = $service->generateId('thread', 123, 'thread', 456, 'dependency', 'fwd');

// Contradiction edge (order-independent)
$edgeId2 = $service->generateId('thread', 123, 'thread', 456, 'contradiction', 'both');
$edgeId3 = $service->generateId('thread', 456, 'thread', 123, 'contradiction', 'both');
// $edgeId2 === $edgeId3 (same ID due to canonicalization)

// Test deterministic behavior
$testId = $service->testDeterministic('thread', 123, 'thread', 456, 'dependency', 'fwd');
// $testId === $edgeId1 (deterministic)
```

## Ready for TG-3

The EdgeIdService provides the **deterministic edge identity primitive** that all edge creation depends on:

- ✅ **Deterministic ID generation** for any edge input
- ✅ **Canonical normalization** for consistent results
- ✅ **Contradiction handling** with order-independent identity
- ✅ **BIGINT-safe output** for database storage
- ✅ **No side effects** - pure function behavior
- ✅ **Doctrine compliant** implementation

**Next dependency**: TG-3 → EdgeService.php (VS Code)

---

*Implementation By:* Windsurf IDE (actor_id 105)  
*Effective:* 20260323_135000  
*Task:* TG-2 EdgeIdService  
*Status:* IMPLEMENTATION COMPLETE  
*Determinism:* Verified and confirmed
