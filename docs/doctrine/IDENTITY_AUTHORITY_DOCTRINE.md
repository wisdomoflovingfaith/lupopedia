# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\IDENTITY_AUTHORITY_DOCTRINE.md"
  file_hash: "e22aeb746afce5830adc67746543e10376b14512c0097202dc431287d2b0f28d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for IDENTITY_AUTHORITY_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "identity_authority_doctrinemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
file_path_from_root: "docs/doctrine/IDENTITY_AUTHORITY_DOCTRINE.md"
system_version: "4.0.46"
channel_id: 0
actor_id: 1000
created_utc: "20260226"
updated_utc: "20260226"
delegation_chain: "1:1000"
artifact_type: "doctrine"
artifact_kind: "identity_governance"
status: "authoritative"
---

# Identity Authority Doctrine

## Purpose

This doctrine establishes the canonical source of truth for actor identity mappings in Lupopedia and defines the immutable authority hierarchy for resolving conflicts between registry representations.

## Authority Hierarchy

When conflicts arise between different registry representations, the following hierarchy MUST be followed:

### 1. Primary Authority: Seed SQL Files
- **Location**: `database/migrations/seed_actors_agents_*.sql`
- **Status**: CANONICAL SOURCE OF TRUTH
- **Reason**: These files define the actual database state and are executed during installation
- **Current Canonical**: `seed_actors_agents_4.0.45.sql`

### 2. Secondary Authority: CSV Data Files
- **Location**: `database/csv_data/lupo_actors.csv`
- **Status**: AUTHORITATIVE (when aligned with seed SQL)
- **Reason**: Used for bulk imports and data validation
- **Requirement**: MUST align with seed SQL; conflicts resolved in favor of seed SQL

### 3. Non-Authoritative: Registry JSON
- **Location**: `actors/registry.json`
- **Status**: REFERENCE ONLY (synchronized from seed SQL)
- **Reason**: Convenience file for quick lookups; not used during installation
- **Requirement**: MUST be regenerated from seed SQL when conflicts detected

## Immutable Identity Anchors

The following actor IDs are PERMANENTLY LOCKED and MUST NEVER be remapped:

| Actor ID | Identity | Status | Authority Level |
|----------|----------|--------|-----------------|
| 0 | System Kernel | Immutable | Kernel |
| 1 | Captain WOLFIE AI | Immutable | Global Authority |
| 1000 | Kiro IDE | Immutable | Execution Agent |
| 10000 | Captain (Human Root) | Immutable | Root Admin |

### Actor ID 1 - Captain WOLFIE AI

**Permanent Assignment**: Captain WOLFIE AI is the ONLY valid identity for actor_id 1.

**Historical Context**: Prior to v4.0.45, `actors/registry.json` incorrectly mapped actor_id 1 to "AUTHENTICATOR" and actor_id 3 to "WOLFIE". This was a legacy artifact from pre-4.0.45 development and has been corrected.

**Enforcement**: Any file, configuration, or code that references actor_id 1 MUST treat it as Captain WOLFIE AI. References to "AUTHENTICATOR" at actor_id 1 are invalid and must be corrected.

## IDE Agent Authority

IDE agents (actor_ids 1000-1005) have NO inherent authority. They execute under the governance of actor_id 1 (Captain WOLFIE AI) and are paired with actor_id 10000 (Captain - Human Root).

### IDE Agent Identity Rules

1. IDE agents MUST declare their operating identity in all outputs
2. IDE agents MUST include `delegation_chain` in all artifacts
3. IDE agents CANNOT override authority mappings
4. IDE agents CANNOT modify immutable identity anchors

## Registry Synchronization Protocol

When registry conflicts are detected:

1. **Identify Conflict**: Compare seed SQL, CSV, and JSON representations
2. **Establish Authority**: Seed SQL is always correct
3. **Archive Legacy**: Move conflicting files to `docs/status/deprecated/`
4. **Regenerate**: Create new registry.json from seed SQL
5. **Document**: Record changes in CHANGELOG.md and create audit report
6. **Verify**: Confirm all representations now align

## Conflict Resolution Example (4.0.46)

**Conflict Detected**: `actors/registry.json` (v4.0.43) mapped actor_id 1 to "AUTHENTICATOR"

**Resolution Applied**:
1. Archived legacy registry to `docs/status/deprecated/registry_legacy_pre_4_0_45.json`
2. Regenerated `actors/registry.json` from `seed_actors_agents_4.0.45.sql`
3. Added actor_id 1 entry to `lupo_actors.csv`
4. Updated CHANGELOG.md with canonicalization record
5. Created this doctrine file

**Result**: All registry representations now correctly map actor_id 1 to Captain WOLFIE AI.

## Enforcement

This doctrine is MANDATORY for all:
- IDE agents (Kiro, Windsurf, Cursor, Warp, Cascade)
- System agents (WOLFIE, LILITH, ROSE, ERIS, METIS, ANUBIS, VISHWAKARMA)
- Human operators
- Installation and migration scripts

Violations of this doctrine constitute a system integrity failure and must be corrected immediately.

## References

- `database/migrations/seed_actors_agents_4.0.45.sql` - Canonical actor definitions
- `database/csv_data/lupo_actors.csv` - CSV representation
- `actors/registry.json` - JSON reference (synchronized)
- `docs/status/REGISTRY_IDENTITY_CANONICALIZATION_4_0_46.md` - Canonicalization report

## Version History

- **4.0.46**: Doctrine created following registry canonicalization
- **Authority**: Captain WOLFIE AI (actor_id 1)
- **Executed By**: Kiro IDE (actor_id 1000)
- **Delegation Chain**: 1:1000

---

**FLIP Footer**:
```json
{
  "inbound_edges": [
    { "from": "CHANGELOG.md", "type": "references", "weight": 0.9 },
    { "from": "docs/status/REGISTRY_IDENTITY_CANONICALIZATION_4_0_46.md", "type": "implements", "weight": 1.0 }
  ],
  "outbound_edges": [
    { "to": "database/migrations/seed_actors_agents_4.0.45.sql", "type": "references", "weight": 1.0 },
    { "to": "actors/registry.json", "type": "governs", "weight": 1.0 },
    { "to": "database/csv_data/lupo_actors.csv", "type": "governs", "weight": 0.9 }
  ],
  "semantic_tags": ["identity", "authority", "governance", "registry", "doctrine"],
  "version": "4.0.46",
  "last_verified_utc": "20260226",
  "last_verified_by": "kiro"
}
```
