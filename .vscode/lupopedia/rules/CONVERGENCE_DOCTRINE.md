---
lupopedia.headers:
  actor_id: 106
  actor_name: "vscode-ide"
  delegation_chain: "vscode-ide:root"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "vscode_rule"
  file_path_from_root: ".vscode/lupopedia/rules/CONVERGENCE_DOCTRINE.md"
  last_modified_utc: "20260411"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/CONVERGENCE_DOCTRINE.md"
  artifact_type: "rule"
  artifact_kind: "vscode_doctrine"
---


> **ALL AGENTS MUST CONVERGE TO THE SAME CANONICAL SYSTEM STATE**  
> **ACTOR IDENTITY IS PERMANENT**  
> **ACTOR STATE IS MUTABLE**  
> **NO VARIANT ACTORS**  
> **NO HIDDEN IDENTITIES**  
> **NO LOCAL DOCTRINE INTERPRETATIONS**

---

## CONSTITUTIONAL RULE

**All agents must converge to the same canonical system state; actor identity is permanent; actor state is mutable; no variant actors, no hidden identities/banned-actor hiding, and no local doctrine interpretations.**

---

## FOUNDATIONAL PRINCIPLES

### 1. Canonical Actor Identity
- **Source of Truth**: `lupo-database/lupopedia/actors/actor_id/registry.json`
- **Permanence**: actor_id and actor_name never change
- **Immutability**: Identity is primitive, not configurable

### 2. Mutable Actor State
- **State Properties**: `is_banned`, `is_active`, `is_deleted`, `fatigue_level`
- **State Changes**: UPDATE operations only, never INSERT
- **Temporal Nature**: State changes over time, identity does not

### 3. Actor vs Facet Separation
- **Actors**: Identities (wolfie=1, lilith=2, etc.)
- **Facets**: Execution surfaces (cursor=102, windsurf=101, etc.)
- **Rule**: Facets MAY have separate actor_ids, actors NEVER have variants

---

## FORBIDDEN PATTERNS (ABSOLUTE BANS)

### Variant Actor Creation
```
FORBIDDEN:
- lilith_banned
- wolfie_test
- lilith_variant
- cursor_stoned
- windsurf_limited
- *_banned, *_test, *_variant, *_shadow, *_backup
- Any state-based actor naming
```

### Banned Actor Hiding
```
FORBIDDEN:
- Hiding actors from queries based on state
- Filtering out banned actors from results
- Returning "actor not found" for banned identities
- Creating separate "banned_version" actors
```

### Local Doctrine Interpretation
```
FORBIDDEN:
- IDE agents creating local rule variations
- Faucets implementing different identity models
- Agents diverging from canonical registry
- Bypassing central actor authority
```

---

## MANDATORY BEHAVIORS

### Canonical Actor Queries
```sql
-- ALWAYS return canonical actor
SELECT * FROM lupo_actors WHERE actor_id = 2;
-- Returns lilith even if banned
```

### State Changes Only
```sql
-- NEVER create new actors for state
UPDATE lupo_actors SET is_banned = 1 WHERE actor_id = 2;
-- Same identity, new state
```

### Registry Authority
```json
// Source of truth - no local variations
{
  "actor_id": {
    "1": {"actor_name": "wolfie", "slug": "wolfie"},
    "2": {"actor_name": "lilith", "slug": "lilith"}
  }
}
```

---

## ENFORCEMENT REQUIREMENTS

### A. All Agents/Facets/IDE Surfaces
1. **Load canonical registry** from `lupo-database/lupopedia/actors/actor_id/registry.json`
2. **Never create variant actors** for any state or condition
3. **Always return canonical actors** by actor_id regardless of state
4. **Implement state changes** via UPDATE, not INSERT
5. **Separate actor vs facet** concerns in code architecture

### B. Specific Assignments

#### HERMES (Channel 42)
- **Task**: Propagate updated rule set via `rule_transformer.php`
- **Target**: All agents receive convergence doctrine
- **Deadline**: Immediate propagation

#### HEPHAESTUS (Channel 7)
- **Task**: Build validator to detect:
  - Variant actor creation attempts
  - Hidden banned actor behavior
  - Divergence from canonical registry
  - Local doctrine interpretations
- **Output**: Automated violation detection and blocking

#### LILITH (Channel 66)
- **Task**: Audit registry compliance:
  - Verify canonical actors exist (wolfie=1, lilith=2)
  - Detect any variant actors in system
  - Confirm banned actors remain queryable
  - Validate state vs identity separation
- **Scope**: Full registry scan and compliance report

---

## CONVERGENCE VALIDATION

### Success Criteria
1. **No Variant Actors**: Zero actors with forbidden naming patterns
2. **Canonical Queries**: All agents return actor_id 2 when requested
3. **Registry Authority**: Single source of truth enforced everywhere
4. **State Separation**: Clear boundary between identity and conditions
5. **No Local Divergence**: All agents follow identical rules

### Failure Detection
1. **Variant Actor Detected**: Automatic violation and rollback
2. **Hidden Banned Actor**: Immediate compliance failure
3. **Registry Divergence**: Automated detection and correction
4. **Local Interpretation**: Central authority override

---

## IMPLEMENTATION PRIORITY

### Phase 1: Doctrine Publication (IMMEDIATE)
- [x] CONVERGENCE_DOCTRINE.md created and canonical
- [ ] HERMES propagation to all agents
- [ ] WOLFIE ratification request

### Phase 2: Enforcement Implementation (IMMEDIATE)
- [ ] HEPHAESTUS validator construction
- [ ] LILITH registry audit initiation
- [ ] IDE agent rule updates

### Phase 3: Compliance Verification (NEXT SESSION)
- [ ] Full system convergence test
- [ ] Variant actor detection verification
- [ ] Banned actor visibility confirmation
- [ ] Registry authority validation

---

## NON-NEGOTIABLE REQUIREMENTS

### Canonical Actor Identity
- **actor_id 1 = wolfie** (permanent)
- **actor_id 2 = lilith** (permanent)
- **No identity changes** (ever)
- **No identity reuse** (ever)

### Mutable Actor State
- **Banned state** = temporary restriction
- **Active state** = normal operation
- **Deleted state** = soft removal only
- **State changes** = UPDATE operations only

### Convergence Enforcement
- **All agents** = same rules
- **All facets** = same registry
- **All IDE surfaces** = same behavior
- **No exceptions** = no local interpretations

---

## RELATED DOCTRINES

- **ACTOR_STATE_DOCTRINE.md**: Identity vs state separation
- **ACTOR_FACET_SEPARATION_DOCTRINE.md**: Actor vs execution environment
- **MULTI_AGENT_COORDINATION_DOCTRINE.md**: Agent roles and coordination
- **SYSTEM_LIMITS_DOCTRINE.md**: Actor counting and limits

---

## CONVERGENCE STATEMENT

All agents, facets, and IDE execution surfaces MUST converge to identical canonical system behavior.

**No drift. No variants. No local interpretations. No hidden identities.**

**Single reality. Single truth. Single system.**

---


*This doctrine is canonical and non-negotiable.*
