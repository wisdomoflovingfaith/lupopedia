---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/header_db_reversibility_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/header_db_reversibility_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: doctrine
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# Header ↔ Database Reversibility Doctrine (Binding for all 4.0.x releases)

## 1. Core Principle

The Lupopedia dual-mode system (filesystem + database) must guarantee **deterministic round-trip semantics**:

$$\text{Database State} \rightarrow \text{HEADERS} \rightarrow \text{Database State} = \text{identical}$$

$$\text{HEADERS} \rightarrow \text{Database State} \rightarrow \text{HEADERS} = \text{identical}$$

**Both directions must produce identical outcomes without loss of meaning or data.**

This is not aspirational. It is **binding constraint** for all reversibility implementations.

---

## 2. Filesystem Mode (LUPOPEDIA HEADERS)

Filesystem mode is the **external AI execution surface**. External AI agents (Claude, other models via IDE faucets) have **zero database access**. They work exclusively with LUPOPEDIA HEADERS and file artifacts.

### 2.1 What Filesystem Mode Carries

LUPOPEDIA HEADERS express:

1. **Identity** — file_path_from_root, version_when_written, artifact_kind, artifact_type
2. **Provenance** — actor_id, actor_name, delegation_chain, channel_id, thread_id, task_id
3. **Relationships** — outbound_edges (type, target, weight, reason)
4. **Metadata** — all property values needed to reconstruct DB state
5. **Semantics** — purpose, traits, tags for meaning preservation

### 2.2 What Filesystem Mode Must Preserve

When projecting DB → HEADERS, every piece of information must be **semantically preserved**:

- Column definitions (name, type, nullability, default)
- Index definitions (columns, uniqueness, BTREE/HASH)
- Table metadata (primary key, soft-delete strategy)
- Content (for content-bearing tables: data values)
- Relationships (foreign-key-equivalent rows in edge tables)

---

## 3. Database Mode (Structural)

Database mode is the **internal runtime surface**. It is the **source of truth for structural state**:

- Schema (column types, indexes, constraints)
- Identity (primary keys, unique indexes)
- Temporal state (timestamps, soft-delete flags)
- Referential integrity rows (edges, metadata)

### 3.1 What Database Mode Carries

The database carries:

1. **Schemas** (install SQL DDL)
2. **Instance data** (rows with all columns populated)
3. **Indexes** (BTREE, hash indexes per schema)
4. **Constraints** (application-layer only; no FK constraints in schema)
5. **Timestamps** (created_ymdhis, updated_ymdhis, deleted_ymdhis as BIGINT YYYYMMDDHHIISS UTC)

### 3.2 Database Mode Authority

For structural properties (column names, types, indexes):

- **Install SQL** is the canonical authority
- **TOON files** are the secondary reference (generated from DB)
- **Active table docs** are documentation, not authority

---

## 4. Deterministic DB → HEADERS Projection

Changing DB state → producing correct HEADERS.

### 4.1 Projection Tool

Primary tool: `scripts/generate_headers_from_db.py`

This script:

- **Reads TOON files** to determine exact table schema
- **Queries lupo_contents** for content rows  
- **Queries lupo_metadata** for metadata rows
- **Outputs YAML LUPOPEDIA HEADERS** with canonical block order

### 4.2 Projection Rules (DB → HEADERS)

| DB Property | HEADERS Representation | Preservation Rule |
|---|---|---|
| content.file_path_from_root | lupopedia.headers.file_path_from_root | Exact match, no normalization |
| content.version_when_written | lupopedia.headers.version_when_written | Exact match (only field; no system_version) |
| content.artifact_type | lupopedia.headers.artifact_type | Exact match |
| content.artifact_kind | lupopedia.headers.artifact_kind | Exact match |
| content.channel_id | lupopedia.headers.channel_id | Integer, exact match |
| content.actor_id | lupopedia.headers.actor_id | Integer, exact match |
| metadata rows for [entity_type=file, entity_id=content_id] | Grouped into lupopedia.headers, lupopedia.metadata, lupopedia.edges, etc. | Group by entity_type, preserve all rows with exact values |
| edge rows referencing this content | lupopedia.edges.outbound_edges array | Each row → one array element; preserve to, type, weight, reason |
| created_ymdhis, updated_ymdhis | last_modified_utc in headers | YYYYMMDDHHIISS unchanged |
| is_deleted, deleted_ymdhis | Omit from HEADERS if is_deleted=1 | Soft-deleted rows do not project to active HEADERS |

### 4.3 Canonical Block Order (Projection Output)

Generated HEADERS must use this block order (mandatory):

1. `lupopedia.headers` — identity, artifacts, versioning
2. `lupopedia.session` — optional, only if present in DB
3. `lupopedia.metadata` — optional, only if metadata rows exist
4. `lupopedia.interpretation` — optional, only if interpretation rows exist
5. `lupopedia.edges` — required if outbound edges exist; omit if none
6. `lupopedia.footer` — required; contains orchestrator, next_action

No other blocks must appear. Deviations are non-compliant.

### 4.4 Determinism Guarantees

DB → HEADERS projection **must be deterministic**:

- Given identical DB state, running projection twice produces **identical HEADERS**
- No timestamps in headers change between runs (they reflect DB values, not run time)
- No randomization, UUID generation, or non-deterministic ordering
- Array fields (e.g., outbound_edges) are sorted by a stable key (e.g., to, then type)

---

## 5. Deterministic HEADERS → DB Ingestion

Changing HEADERS → loading correct state into DB.

### 5.1 Ingestion Model

Ingestion reads HEADERS and:

1. **Verifies identity** — file_path_from_root, version_when_written uniquely identify the artifact
2. **Inserts or updates** lupo_contents row with metadata
3. **Inserts or updates** lupo_metadata rows for each header property
4. **Inserts or updates** lupo_edges rows for each outbound edge
5. **Timestamps** created_ymdhis and updated_ymdhis per current UTC

### 5.2 Identity Collision Detection

If a HEADERS file's identity (file_path_from_root + version_when_written) already exists in DB with **is_deleted = 0**:

- **Reject the ingestion**
- **Raise collision error** to the calling process
- **No overwrite, no merge, no deduplication**

If the existing row has **is_deleted = 1**:

- **Permit reuse** — the new row takes over the identity

### 5.3 Ingestion Determinism Guarantees

HEADERS → DB ingestion **must be deterministic**:

- Given identical HEADERS, running ingestion twice produces identical DB state
- Idempotency: running ingestion again on same HEADERS does not create duplicates
- Timestamp consistency: created_ymdhis does not change on re-ingestion; updated_ymdhis updates to current time
- No hidden state: ingestion produces exactly the DB rows necessary to represent the HEADERS, no more, no less

---

## 6. Conflict Detection & Resolution

When DB and HEADERS diverge (not in round-trip sync):

### 6.1 Divergence Detection

Divergence occurs when:

- A HEADERS file describes a different state than the DB row with the same identity
- DB has deleted a row (is_deleted=1) while HEADERS exists (not deleted)
- HEADERS describes a column/field that does not exist in the current DB schema
- A semantic property (e.g., artifact_kind) differs between HEADERS and DB

### 6.2 Allowed Divergence (Non-Blocking)

- **Timing skew**: updated_ymdhis in DB is later than last_modified_utc in HEADERS (DB was updated after HEADERS was generated). This is acceptable and normal.
- **Deletion lag**: HEADERS file still exists on disk but DB row is soft-deleted (is_deleted=1). This is acceptable during migration or cleanup.

### 6.3 Prohibited Divergence (Blocking)

- **Identity collision** with different content — same file_path_from_root but different artifact meaning
- **Schema mismatch** — HEADERS describes a column that does not exist in current TOON/install SQL
- **Semantic violation** — HEADERS artifact_kind contradicts its actual role (e.g., artifact_kind:table but file is a directive)

### 6.4 Conflict Resolution Authority

Conflict resolution is **never automatic**:

- **LILITH detects** the conflict via audit
- **WOLFIE decides** resolution (which source is authoritative; whether to correct DB or HEADERS)
- **HEPHAESTUS executes** the correction
- **THOTH documents** what was corrected and why

No actor may silently correct conflicts.

---

## 7. Confidence Levels

Not all round-trips are guaranteed to have perfect fidelity. Confidence levels help identify where reversibility may be lossy.

### 7.1 High Confidence (100% Reversibility Guaranteed)

Reversibility is **fully deterministic and bidirectional**:

- **Case**: Metadata-only tables (lupo_metadata, lupo_edges, lupo_channels)
- **Guarantee**: DB ↔ HEADERS round-trips always produce identical state
- **Scope**: Applies to all columns, all rows, all metadata
- **Test**: Automated test suite can verify bidirectional equivalence

### 7.2 Medium Confidence (Structural Reversibility Only)

Reversibility is **deterministic for structure but not necessarily for all data**:

- **Case**: Content-bearing tables with large text/JSON fields (lupo_contents, lupo_dialog_messages)
- **Guarantee**: Row identity and schema are preserved round-trip, but content may be truncated or serialized differently
- **Scope**: Applies to row identity, timestamps, metadata; content fields may be handled heuristically
- **Test**: Automated test suite verifies structure; content testing is manual or heuristic

### 7.3 Low Confidence (One-Way Projection Only)

Reversibility is **supported in one direction only**:

- **Case**: Computed or derived columns (e.g., last_activity_ymdhis computed from message timestamps)
- **Guarantee**: DB → HEADERS is possible; HEADERS → DB is not possible without external source data
- **Scope**: Read-only projections only; no round-trip
- **Test**: Manual verification; no automated round-trip test

---

## 8. Schema Evolution & Compatibility

When schema changes (new columns, new tables, deprecated columns):

### 8.1 Forward Compatibility

New schema columns must be **backward compatible** with existing HEADERS:

- Existing HEADERS files must continue to project without error
- New columns must have sensible defaults or be nullable
- Projection tool must skip unknown columns gracefully

### 8.2 Deprecated Columns

If a column is removed from schema:

- Old HEADERS referencing that column are marked as **stale**
- Projection automatically omits the defunct column
- Ingestion of old HEADERS still succeeds (orphaned properties are stored in metadata but do not populate columns)

### 8.3 Breaking Changes

Breaking schema changes (e.g., removal of a column that HEADERS files require) are **permitted only at version boundaries** (e.g., 4.0.84 → 4.0.85, never mid-version).

When breaking changes occur:

- Version boundary is explicit (version_when_written changes)
- Migration artifacts explain what changed
- Old HEADERS files are either migrated or archived, never silently dropped

---

## 9. Implementation Checklist

### Phase 1: DB → HEADERS (Primary Direction)

- [ ] Complete `generate_headers_from_db.py` with actual DB connection
- [ ] Implement TOON-driven schema understanding
- [ ] Implement deterministic YAML generation with canonical block order
- [ ] Add CLI arguments for --file-path, --content-id, --dry-run
- [ ] Test with actual database content
- [ ] Verify determinism (run twice, compare outputs)
- [ ] Add to CI/CD pipeline

### Phase 2: HEADERS → DB (Secondary Direction)

- [ ] Design ingestion algorithm (verify collision, insert/update, timestamp)
- [ ] Implement identity collision detection
- [ ] Implement semantic validation (schema consistency)
- [ ] Add idempotency (re-ingestion produces same state)
- [ ] Test with actual HEADERS files
- [ ] Verify determinism (run twice, compare DB state)
- [ ] Add to CI/CD pipeline

### Phase 3: Round-Trip Testing

- [ ] Implement DB → HEADERS → DB test suite
- [ ] Implement HEADERS → DB → HEADERS test suite
- [ ] Verify identical outcomes for high-confidence tables
- [ ] Document medium/low-confidence limitations
- [ ] Add to CI/CD pipeline

### Phase 4: Divergence Detection & Audit

- [ ] Implement conflict detection algorithm (LILITH audit)
- [ ] Implement divergence classification (allowed vs. prohibited)
- [ ] Add audit report artifact generation
- [ ] Add to CI/CD pipeline
- [ ] Document resolution procedures

---

## 10. Governance & Amendment

Header ↔ Database Reversibility Doctrine is **binding for all 4.0.x releases** and cannot be amended until 4.1.0 without a WOLFIE directive that explicitly updates this doctrine.

**Next amendment opportunity:** 4.1.0 release cycle.

---

_Binding doctrine for header-database reversibility as of Lupopedia 4.0.84. Form is content. Semantics must be preserved._
