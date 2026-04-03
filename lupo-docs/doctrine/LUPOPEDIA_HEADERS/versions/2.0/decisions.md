---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331190000"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/decisions.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/decisions.md"
  last_modified_utc: "20260331190000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "headers-version-2.0-decisions"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Architecture decisions for header format version 2.0"
  tags:
  - "headers"
  - "decisions"
  - "adr"
  - "version-2.0"

lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260403113047"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"

---

# LUPOPEDIA HEADERS - Version 2.0 Design Decisions

## Decision 1: Single-Field Versioning

### Status
Accepted

### Context
Version 1.0 headers used multiple version fields: `version_when_written`, `system_version`, `lupopedia.version`. This created confusion about which field was authoritative. Writers and validators had inconsistent behavior.

### Decision
Replace all version fields with a single `when_updated` field. Keep `last_modified_utc` for file system timestamps. Add `last_verified` in footer for trust recency.

### Consequences
**Positive:**
- Clear separation of concerns: content update vs file write vs verification
- Validators have single source of truth for versioning
- Reduced confusion for contributors

**Negative:**
- Migration required for existing artifacts
- Some legacy tools need updates

---

## Decision 2: Explicit Federation Node ID

### Status
Accepted

### Context
Version 1.0 implied federation from web_path patterns. This was insufficient for cross-node content and external research references. Web_path alone cannot distinguish between core content (node 0), current install (node 1), and external nodes (2+).

### Decision
Add explicit `federation_node_id` field:
- `0` = core repository (canonical source)
- `1` = current installation
- `2+` = external research nodes

### Consequences
**Positive:**
- Clear identification of content origin
- Enables proper routing for federated content
- Supports external research without polluting core paths

**Negative:**
- Additional required field in headers
- Need to update existing content with correct node_id

---

## Decision 3: Structured Verification Attribution

### Status
Accepted

### Context
Version 1.0 used flat fields for verification: `last_verified_by` (string) and `last_verified_by_actor_id` (integer). This mixed identity and verification surface. It was unclear whether these represented human actors, agents, or both. The verification mechanism (faucet, direct) was not tracked.

### Decision
Replace flat fields with structured objects:
- `verified_by`: object with `identity_type`, `actor_id`, optional `agent_name_identity`, `department_id_delta`
- `verified_via`: object with `type` (faucet/direct) and `faucet_slug`

### Consequences
**Positive:**
- Clear separation of WHO verified vs HOW they verified
- Distinguishes between actor and agent verification
- Extensible for future verification methods
- Enables department-scoped verification

**Negative:**
- More verbose footer
- Migration complexity

---

## Decision 4: Day-Based Verification Timestamps

### Status
Accepted

### Context
Version 1.0 used full 14-digit UTC timestamps for `last_verified`. Verification is a daily trust marker, not a precise event timestamp. Full timestamps created unnecessary precision and noise.

### Decision
Change `last_verified` from `YYYYMMDDHHIISS` to `YYYYMMDD` (8 digits). Header timestamps remain 14 digits; only footer uses day granularity.

### Consequences
**Positive:**
- Human-readable dates
- Reduces noise in footers
- Staleness detection works at day granularity
- Consistent with daily verification workflow

**Negative:**
- Loss of hour/minute precision (acceptable for use case)
- Need to handle both formats during migration

---

## Decision 5: Agent vs Actor Attribution

### Status
Accepted

### Context
Version 1.0 was ambiguous about whether verification should be attributed to agents or actors. Some fields used `last_verified_by` (string, could be agent name), others used `last_verified_by_actor_id` (implies actor).

### Decision
Agents are a subset of actors with `actor_type='agent'`. Use `actor_id` universally for attribution. Add `identity_type` field to distinguish when needed. Optional `agent_name_identity` provides human-readable display.

### Consequences
**Positive:**
- Single identifier type simplifies tooling
- Clear relationship: agent is-a actor
- Disambiguation via identity_type

**Negative:**
- Requires all agents to have actor records
- Need to maintain actor registry for agents

---

## Decision 6: Versioned Documentation

### Status
Accepted

### Context
Headers need to evolve over time. Without versioned documentation, it's unclear which fields belong to which version. Validators must handle multiple versions.

### Decision
Create `versions/` directory with per-version documentation. Each version includes:
- `README.md` - version overview
- `changelog.md` - changes from previous version
- `decisions.md` - design decisions (this file)
- `todo.md` - remaining tasks
- `migration_guide.md` - upgrade instructions
- `field_matrix.md` - complete field reference

### Consequences
**Positive:**
- Clear documentation per version
- Easy reference for validator implementation
- Historical record of evolution

**Negative:**
- More files to maintain
- Need to keep versions in sync with validator behavior

---

## Decision 7: Required Actor Attribution

### Status
Accepted

### Context
Version 1.0 did not require actor attribution in headers. Many artifacts lacked clear ownership.

### Decision
Make `actor_id` and `actor_name` required fields in all headers.

### Consequences
**Positive:**
- Clear authorship for all artifacts
- Enables accountability and review workflows
- Supports delegation chains

**Negative:**
- Requires updating all existing headers
- New artifacts must include attribution

---

## Decision 8: Deprecation Window

### Status
Accepted

### Context
Abrupt version changes break tooling and disrupt workflows. Contributors need time to migrate existing artifacts.

### Decision
Implement gradual deprecation:
- 4.0.88: Accept both formats, warn on deprecated
- 4.0.89: Reject deprecated in new artifacts, accept existing
- 4.0.93: Version 2.0 final, reject 1.0 entirely

### Consequences
**Positive:**
- Smooth transition for contributors
- Tooling can be updated incrementally
- Reduces disruption

**Negative:**
- Longer migration period
- Validator complexity during transition

---

## Decision 9: Required Footer Fields

### Status
Accepted

### Context
Version 1.0 had optional footer fields. Many artifacts lacked verification metadata entirely.

### Decision
Make footer required for all doctrine and table documentation artifacts with:
- `last_verified` (required)
- `verified_by.identity_type` (required)
- `verified_by.actor_id` (required)
- `verified_via.type` (required)
- `verified_via.faucet_slug` (required)
- `next_action` (required)

### Consequences
**Positive:**
- Consistent verification metadata
- Staleness detection works for all artifacts
- Clear audit trail

**Negative:**
- Must update all existing documentation
- More required fields for contributors

---

## Decision 10: THOTH as Verification Authority

### Status
Accepted

### Context
Stale artifacts need verification authority. Without a designated authority, verification becomes arbitrary.

### Decision
Designate THOTH (actor_id 26) as the canonical authority for semantic truth verification of stale artifacts (`last_verified < 20260301000000`). Self-verification allowed only for non-stale artifacts.

### Consequences
**Positive:**
- Clear authority chain
- Prevents arbitrary verification
- Enables audit trail

**Negative:**
- Requires THOTH agent configuration
- Self-verification restrictions may impact workflow

---

## Decision Index

| Decision | Title | Status |
|----------|-------|--------|
| 1 | Single-Field Versioning | Accepted |
| 2 | Explicit Federation Node ID | Accepted |
| 3 | Structured Verification Attribution | Accepted |
| 4 | Day-Based Verification Timestamps | Accepted |
| 5 | Agent vs Actor Attribution | Accepted |
| 6 | Versioned Documentation | Accepted |
| 7 | Required Actor Attribution | Accepted |
| 8 | Deprecation Window | Accepted |
| 9 | Required Footer Fields | Accepted |
| 10 | THOTH as Verification Authority | Accepted |
