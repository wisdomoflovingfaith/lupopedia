---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/43_A_PARENT_CHILD_TRUST_LADDER.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/43_A_PARENT_CHILD_TRUST_LADDER.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/43_parent_child_trust_ladder.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/parent-child-trust-ladder
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_43_A_PARENT_CHILD_TRUST_LADDER
  title: "PRD 43: Parent-Child Trust Ladder"
  summary: "Trust ladder implementation: Parent-child relationships, memory scope inheritance, and trust weight quantification using memory graph edges"
---
# PRD 43: Parent-Child Trust Ladder

## Canonical Year Offset Rule for Trust Ladder PKs (Normative)

All canonical (long-term, merged, or archived) `memory_node_id` values used as trust ladder primary keys **MUST** encode the year as (calendar year ??? 1000) in the first four digits. This offset is required for all high-trust, living canonical, and archived ids, and is enforced by all memory graph validators and trust ladder migration scripts.

**Rationale:**
- The offset (calendar year ??? 1000) creates a distinct, lexicographically sortable band for high-trust, long-term ids (1000???1999), separate from runtime/staging ids (2000???2099).
- This prevents accidental mixing of staging and canonical ids, supports deterministic migration, and enables strict validation of trust ladder integrity.
- Numeric banding is not a substitute for explicit trust semantics, but is a required convention for all canonical ids in trust ladder operations.

**Validation and migration requirements:**
- All trust ladder PKs and memory graph edges **MUST** enforce the offset rule for canonical ids.
- Validators **MUST** reject any canonical or archived id whose year is not in 1000???1999, or whose offset does not match the original runtime year minus 1000.
- Migration scripts **MUST** backfill or repair ids to conform to this rule if legacy data is found.
- Query helpers **MUST** use the offset band to distinguish canonical from staging ids, but **MUST NOT** rely on numeric banding alone for trust semantics.

**See also:** PRD 16 ??8.1 (header/memory_key year encoding), PRD 38 ??8.1 (memory unification), doctrine/TRUST_LADDER_REGISTRY.md (validation), and all trust ladder migration scripts.

## Purpose

Define parent/child trust ladder behavior for entity lineage, memory scope inheritance, and consolidation workflows using memory graph edges.

## Design Rationale: Truth Is Promoted, Not Latest

**The problem this solves:** AI systems are trained to treat recency as correctness ??? latest record wins, newest value overwrites old. Lupopedia explicitly rejects this. Recency does not confer truth.

**The principle (normative):**

> Truth is promoted, not latest. A staging record that contradicts a canonical record is wrong until it is promoted through the full KAIROS consolidation process ??? not merely because it is newer.

**How the trust ladder encodes this directly in the primary key:**

| Year range (first 4 digits of 18-digit ID) | Trust tier | Meaning |
|---------------------------------------------|------------|---------|
| 2026... (calendar year) | Staging | Active ideas, drafts, experiments. Not yet verified. |
| 1026... (calendar year - 1000) | Canonical | Verified truth. Promoted by KAIROS. |

The offset (year - 1000) is not a trick ??? it is the encoding of trust. A canonical memory node is distinguished from a staging node by the number itself, not by a separate flag or column. No ORM magic. No joins. The number tells you whether to trust it.

**Conflict resolution rule (normative):**

When a staging record (2026...) asserts something that contradicts a canonical record (1026...), the canonical record is authoritative. The staging record is treated as a hypothesis, not a correction. Promotion from staging to canonical requires explicit KAIROS consolidation, not just a newer timestamp.

**THOTH's role in real-time contradiction detection:**

THOTH monitors the active dialog stream. When an agent or human proposes something that contradicts a canonical truth (e.g., introducing a column naming convention that violates the canonical schema), THOTH raises an [ALERT] in the stream before the suggestion becomes system behavior. THOTH does this by comparing the proposal against canonical memory nodes. Staging records that conflict with canonical nodes trigger `edge_status = 'needs_review'` with `review_reason = 'contradiction'` per the memory graph doctrine (PRD 32 sec. 10, PRD 38).

## 1. Trust Ladder Edge Predicates

### 1.1 Core Predicates

| Predicate | Direction | Symmetric | Transitive | Purpose | Weight Range |
|-----------|-----------|-----------|------------|---------|--------------|
| `trusts` | actor ??? actor | false | true | Actor trusts another actor | 0.0-1.0 |
| `delegates_to` | channel ??? actor | false | false | Channel delegates authority to actor | 0.0-1.0 |
| `parent_channel` | channel ??? channel | false | true | Channel hierarchy relationship | 1.0 (structural) |
| `memory_scope_inherits` | child ??? parent | false | true | Child inherits parent memory scope | 1.0 (inheritance) |
| `has_access_to` | entity ??? entity | false | false | Access permission relationship | 0.0-1.0 |

### 1.2 Memory Edge Schema

```json
{
  "edge_type": "trusts",
  "from_memory_node_id": "actor_node_id",
  "to_memory_node_id": "actor_node_id",
  "weight": 0.85,
  "context_json": {
    "trust_type": "trusts|delegates_to|parent_channel|memory_scope_inherits|has_access_to",
    "trust_weight": 0.85,
    "granted_by_actor_id": 1,
    "granted_ymdhis": 20260412140000,
    "expires_ymdhis": null,
    "inheritance_depth": 0,
    "memory_scope_key": "channel_42"
  }
}
```

## 2. Memory Scope Inheritance Rules

### 2.1 Parent-Child Relationships

| Parent Type | Child Type | Inheritance Rule | Memory Scope |
|-------------|------------|------------------|--------------|
| Actor | Dialog Message | Full inheritance | Parent's channel_key |
| Channel | Sub-channel | Hierarchical inheritance | Parent's channel_key |
| Department | Actor | Department scope | dept_{dept_id} |
| Collection | Item | Collection scope | collection_{collection_id} |

### 2.2 Scope Resolution Algorithm

```php
function resolveMemoryScope($entityType, $entityId) {
    // Check direct scope
    $directScope = getDirectMemoryScope($entityType, $entityId);
    if ($directScope) return $directScope;
    
    // Follow memory_scope_inherits edges upward
    $parentEdges = getIncomingEdges($entityId, 'memory_scope_inherits');
    foreach ($parentEdges as $edge) {
        $parentScope = resolveMemoryScope($edge->from_type, $edge->from_id);
        if ($parentScope) return $parentScope;
    }
    
    // Default to channel 0 (system kernel)
    return 'channel_0';
}
```

## 3. Trust Weight Quantification

### 3.0 PK offset rule for trust ladder tables

All trust ladder tables registered in **`lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md`** **MUST** implement the year offset convention on **18-digit** PKs:

| Tier | PK year segment (first 4 digits) | Example |
|------|-----------------------------------|---------|
| **Canonical** / living long-term | **calendar year ??? 1000** | **1026** for calendar **2026** |
| **Staging** / raw generator | **calendar year** | **2026** |
| **Seed-only** | per registry / reserved ranges | **1**, **42** (not 18-digit ladder layout) |

**Validation:** Every INSERT/UPDATE that persists a ladder PK **MUST** call **`IdGenerator::validateTrustLadderPk()`** (and **`IdGenerator::validateFormat()`** where raw generator output is required). For promotion from staging to canonical, **`IdGenerator::toCanonicalIdSafe()`** (or **`MemoryPromotionService`**) is normative.

**Consolidation:** **KAIROS** / **`MemoryPromotionService`** handle staging???canonical consolidation with idempotent edges (**`promoted_to`**, **`consolidated_into`**, **`merged_into`** ??? exact strings per **PRD 37** / install SQL).

### 3.1 Weight Scale

| Range | Trust Level | Description |
|-------|-------------|-------------|
| 0.00-0.20 | No Trust | Explicit distrust or blocked |
| 0.21-0.40 | Low Trust | Limited access, monitoring required |
| 0.41-0.60 | Medium Trust | Standard operational access |
| 0.61-0.80 | High Trust | Elevated privileges |
| 0.81-1.00 | Full Trust | Complete authority |

### 3.2 Trust Propagation Rules

- **Direct trust**: Explicit `trusts` edge weight
- **Inherited trust**: Multiply by 0.9 per inheritance level
- **Delegated trust**: Limited by delegator's trust weight
- **Minimum threshold**: 0.30 required for any access

## 4. Query Patterns for Lineage Traversal

### 4.1 Find All Descendants

```sql
-- Find all channels inheriting from parent channel
WITH RECURSIVE descendants AS (
    SELECT memory_node_id, memory_key, 0 as depth
    FROM lupo_memory_nodes 
    WHERE memory_key = 'channel_42'
    
    UNION ALL
    
    SELECT n.memory_node_id, n.memory_key, d.depth + 1
    FROM lupo_memory_edges e
    JOIN lupo_memory_nodes n ON e.to_memory_node_id = n.memory_node_id
    JOIN descendants d ON e.from_memory_node_id = d.memory_node_id
    WHERE e.edge_type = 'memory_scope_inherits'
    AND d.depth < 10  -- Prevent infinite loops
)
SELECT * FROM descendants WHERE depth > 0;
```

### 4.2 Calculate Effective Trust

```sql
-- Calculate effective trust from actor to target
SELECT 
    e.from_memory_node_id,
    e.to_memory_node_id,
    e.weight * POWER(0.9, COALESCE(inheritance.depth, 0)) as effective_trust
FROM lupo_memory_edges e
LEFT JOIN (
    SELECT 
        edge_id,
        COUNT(*) as depth
    FROM lupo_memory_edges path
    WHERE edge_type = 'memory_scope_inherits'
    GROUP BY edge_id
) inheritance ON e.edge_id = inheritance.edge_id
WHERE e.edge_type = 'trusts'
AND e.weight * POWER(0.9, COALESCE(inheritance.depth, 0)) >= 0.30;
```

### 4.3 Check Access Rights

```php
function hasAccess($actorId, $targetId, $requiredTrust = 0.30) {
    // Check direct trust
    $directTrust = getTrustWeight($actorId, $targetId);
    if ($directTrust >= $requiredTrust) return true;
    
    // Check delegated authority
    $delegations = getOutgoingEdges($targetId, 'delegates_to');
    foreach ($delegations as $delegation) {
        $delegateTrust = getTrustWeight($actorId, $delegation->to_id);
        if ($delegateTrust >= $requiredTrust) return true;
    }
    
    // Check inherited trust through parent channels
    $parentChain = getParentChain($targetId);
    foreach ($parentChain as $parentId) {
        $inheritedTrust = getTrustWeight($actorId, $parentId) * 0.9;
        if ($inheritedTrust >= $requiredTrust) return true;
    }
    
    return false;
}
```

## 5. Integration with Memory System

### 5.1 Memory Edge Creation

When trust relationships are created:

1. **Create memory edge** with appropriate predicate
2. **Set trust weight** in edge weight field
3. **Record context** including granted_by and timestamp
4. **Update memory scope** if inheritance relationship

### 5.2 Sync with Collections

Collections can use trust ladder for access control:

```json
{
  "edge_type": "has_access_to",
  "from_memory_node_id": "actor_123",
  "to_memory_node_id": "collection_456",
  "weight": 0.75,
  "context_json": {
    "access_level": "read_write",
    "granted_by": "collection_owner",
    "trust_basis": "trusts_edge_weight_0.8"
  }
}
```

## 6. Implementation Requirements

### 6.1 Database Schema

```sql
-- Trust weight column already exists in lupo_memory_edges
-- No additional tables needed - use memory graph

-- Indexes for performance
CREATE INDEX idx_memory_edges_trust ON lupo_memory_edges(edge_type, weight);
CREATE INDEX idx_memory_edges_lineage ON lupo_memory_edges(edge_type, from_memory_node_id, to_memory_node_id);
```

### 6.2 Service Classes

- `TrustLadderService` - Manage trust relationships
- `MemoryScopeResolver` - Resolve memory scope inheritance
- `AccessControlService` - Check access based on trust weights
- `LineageTraverser` - Navigate parent/child relationships

## 7. Security Considerations

### 7.1 Trust Decay

- Trust weights decay 5% per month without renewal
- Critical relationships (weight > 0.8) require annual review
- Automatic expiration for temporary relationships

### 7.2 Audit Trail

- All trust changes create memory edges with full context
- Trust weight history preserved in memory graph
- Access decisions logged with trust calculation

## Status

**Status:** IMPLEMENTATION READY - Complete specification with edge predicates, schema, and query patterns
