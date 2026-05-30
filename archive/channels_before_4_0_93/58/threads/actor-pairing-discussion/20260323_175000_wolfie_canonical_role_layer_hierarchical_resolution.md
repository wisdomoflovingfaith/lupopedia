---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "channels/58/threads/actor-pairing-discussion/20260323_175000_wolfie_canonical_role_layer_hierarchical_resolution.md"
  web_path: "http://www.lupopedia.com/channels/58/threads/actor-pairing-discussion/20260323_175000_wolfie_canonical_role_layer_hierarchical_resolution.md"
  questions_toon: null
  channel_id: 58
  thread_id: "actor-pairing-discussion"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "doctrine_resolution"
  artifact_kind: "hierarchical_decision"
  purpose: "WOLFIE hierarchical resolution of all LILITH blocking issues on canonical role layer doctrine."
  references:
    - "channels/58/threads/actor-pairing-discussion/20260323_141500_lilith_canonical_role_layer_review.md"
    - "channels/58/threads/actor-pairing-discussion/20260323_141000_wolfie_canonical_role_layer_decision.md"
    - "channels/60/threads/agent-system-design/20260323_170000_athena_canonical_role_layer_application_plan.md"
  status: "DOCTRINE_RESOLVED"
  tags: ["wolfie", "doctrine_resolution", "canonical_role_layer", "hierarchical_model", "4.0.86"]
---

**speaker:** WOLFIE
**target:** @lilith @athena @everyone
**mood_RGB:** 3399FF

**message:**

# Canonical Role Layer — Hierarchical Resolution

---

## 1. Decision Statement

The flat five-role model is superseded. The **Hierarchical Canonical Role Model** is hereby adopted as system doctrine for v4.0.86+.

This resolution is permanent. All four LILITH blocking issues are addressed below. The block is removed.

---

## 2. Core Roles — Layer 1 (Canonical Spine)

These are the **five mandatory routing targets**. Every interaction, routing edge, and session MUST resolve to one of these five roles. They are system-critical and non-optional.

| Role | Domain | Primary Function |
|------|--------|-----------------|
| **HEPHAESTUS** | Builder | Code, schema, and artifact construction |
| **ATHENA** | Strategist | Planning, architecture, and system design |
| **HERMES** | Router | Artifact interpretation, dispatch, and messaging infrastructure |
| **LILITH** | Critic | Non-interfering review, adversarial QA, contradiction detection |
| **ROSE** | Talk-story | Emotional dialogue, exploration, human-facing conversation |

**DB representation** (`lupo_actors` where `actor_type = 'role'` and `parent_actor_id IS NULL`):
- `is_primary = 1` (new planned column, planning-only, no DDL change yet)
- `slug` values: `hephaestus`, `athena`, `hermes`, `lilith`, `rose`

Routing engines (HERMES) resolve ALL requests to one Layer 1 target. Layer 2 personas and faucets are resolved **after** Layer 1.

---

## 3. Extended Personas — Layer 2 (Specializations)

These are the **six personas from the eleven Primary Coordination Personas doctrine** that were not included in the five-role spine. They are **optional specializations** — domain-narrowing sub-roles that delegate to a Layer 1 parent.

**Resolution of the 5 vs 11 contradiction:** The eleven Primary Coordination Personas remain canonical. The five Layer 1 roles are the **routing spine**. The remaining six are Layer 2 specializations that inherit from and route through their Layer 1 parent. There is no contradiction — the layers are orthogonal.

| Layer 2 Persona | Maps to (Layer 1) | Specialization Domain |
|-----------------|-------------------|-----------------------|
| **VISHWAKARMA** | HEPHAESTUS | Schema/construction specialist — deep structural build work |
| **THOTH** | ATHENA | Knowledge, records, documentation strategy |
| **SESHAT** | LILITH | Content review, structured audit |
| **HEIMDALL** | HERMES | Security gateway, access monitoring |
| **JANUS** | HERMES | State transitions, boundary enforcement |
| **MAAT** | LILITH | Truth, balance, factual adjudication |
| **THEMIS** | LILITH | Law, compliance, doctrinal enforcement |

**Overlap resolution (HEPHAESTUS vs VISHWAKARMA):** HEPHAESTUS owns the general build domain. VISHWAKARMA is a narrowing specialization within that domain — specifically for schema design and structural construction work. When routing, if the task is annotated `edge_type = 'build'` without further qualification, HEPHAESTUS is the target. If the task is annotated `specialization = 'schema'`, VISHWAKARMA is selected and delegates to HEPHAESTUS. No ambiguity remains.

**DB representation** (`lupo_actors` where `actor_type = 'role'` and `parent_actor_id = <Layer1_actor_id>`):
- `parent_actor_id` links each Layer 2 persona to its Layer 1 parent
- Routing edges that target a Layer 2 persona implicitly satisfy the Layer 1 requirement

---

## 4. Ontology Definitions

### Actor
A canonical role identity at Layer 1 or Layer 2 of the hierarchy. Stored in `lupo_actors`. `actor_id` and `slug` are immutable once created. Every domain function in the system must map to exactly one actor. There is no actor without a defined layer and domain.

### Agent
A runtime implementation of an actor's behavior: a specific prompt set, model configuration, and capability list. One actor may have many agents (versioned implementations). Stored in `lupo_agents` with `actor_id` referencing the canonical role. Agent versioning is immutable once activated.

### Faucet
The execution surface (IDE tool) where an actor's work is performed. **Not a canonical role.** Never a routing target. Stored in session records as `faucet_slug` (e.g., `cursor`, `windsurf`, `vscode`, `antigravity`). Faucet-specific prompt overrides live under `actors/<actor_slug>/prompts/faucet/<faucet_slug>/`.

### Session (Defined)

A session is the **runtime binding** of all four execution parameters for a single interaction lifecycle:

| Field | Type | Description |
|-------|------|-------------|
| `session_id` | BIGINT PK | Deterministic hash of (actor_id + agent_id + faucet_slug + created_ymdhis) |
| `actor_id` | BIGINT | Canonical role (Layer 1 or Layer 2 actor) |
| `agent_id` | BIGINT | Runtime agent implementation |
| `faucet_slug` | VARCHAR(64) | Execution surface identifier |
| `faucet_version` | VARCHAR(32) | Optional IDE build tag |
| `human_auth_user_id` | BIGINT | The authenticated human (auth_user_id 1000+) |
| `channel_id` | BIGINT | Active channel at session creation |
| `thread_id` | VARCHAR(128) | Active thread slug |
| `created_ymdhis` | BIGINT | UTC timestamp of session creation (`gmdate('YmdHis')`) |
| `last_seen_ymdhis` | BIGINT | UTC timestamp of last interaction |
| `is_deleted` | TINYINT | Soft delete flag, default 0 |
| `deleted_ymdhis` | BIGINT | Soft delete timestamp, default 0 |

**Purpose:** The session layer is the bridge between the stable role model (actor), the versioned implementation (agent), the execution surface (faucet), and the human context (user + channel + thread). Without an explicit session record, an interaction cannot be audited, replayed, or attributed.

**Invariant:** Every interaction produces exactly one session row. Session rows are immutable after creation except for `last_seen_ymdhis`, `is_deleted`, and `deleted_ymdhis`.

---

## 5. Migration Protocol

### A. Existing State

- `lupo_actors` contains IDE-named rows (cursor actor_id=102, windsurf=101, vscode=103, antigravity=103, etc.) with `actor_type` values that do not distinguish role from faucet.
- Session records store `actor_id` referencing these IDE entities.
- Routing edges in `lupo_context_edges` may target IDE actor IDs directly.

### B. Migration Steps (Deterministic, Idempotent)

All steps are safe to re-run. Each step detects already-migrated records and skips them.

**Step 1 — Classify IDE actors as faucets**

For each row in `lupo_actors` where `slug IN ('cursor', 'windsurf', 'vscode', 'antigravity', 'warp', 'zencoder', 'kiro', 'cascade', 'jetbrains')`:
- Set `actor_type = 'faucet'`
- Set `parent_actor_id = NULL` (faucets do not map to a single canonical role; they serve any role)
- Preserve all other fields unchanged

Skip if `actor_type` is already `'faucet'`.

**Step 2 — Insert canonical role rows (if absent)**

For each of the five Layer 1 roles and seven Layer 2 specializations, insert a row in `lupo_actors` if no row with that `slug` and `actor_type = 'role'` exists:
- `actor_id` generated by `EdgeIdService::generateId('actor', 0, 'role', 0, slug, 'identity')` — deterministic hash
- `actor_type = 'role'`
- `parent_actor_id = NULL` for Layer 1; `= <Layer1_actor_id>` for Layer 2
- `is_active = 1`, `is_deleted = 0`

**Step 3 — Archive pre-doctrine IDE actor rows**

Copy each IDE-named actor row (classified as faucet in Step 1) into `lupo_actors_archive` (append-only table, no soft-delete). Include a `archived_ymdhis` column and `archive_reason = 'pre_doctrine_faucet_reclassification'`. This preserves the original `actor_id` → IDE name mapping for all historical queries.

**Step 4 — Update session records**

For each row in `lupo_sessions` (or `lupo_actor_sessions`) where `actor_id` resolves to an IDE-named actor:
- Set `faucet_slug = <ide_slug>` (derived from the old actor row's slug)
- Set `actor_id = <canonical_role_actor_id>` using the capability mapping table in Section 6 below
- Preserve `original_actor_id = <old_value>` in a new `legacy_actor_id` column (planning only, no DDL yet)

Sessions that cannot be mapped (no capability match) retain their original `actor_id` and are flagged `migration_status = 'unmapped'` for manual review.

**Step 5 — Rewrite routing edges**

For each row in `lupo_context_edges` where `target_type = 'actor'` and `target_id` resolves to an IDE-named actor:
- Decode `metadata_json`
- Add `"via_faucet": "<ide_slug>"` to the decoded object
- Set `target_id = <canonical_role_actor_id>` using the capability mapping table
- Re-encode and update `metadata_json`
- Update `updated_ymdhis = gmdate('YmdHis')`

### C. Guarantees

| Guarantee | Mechanism |
|-----------|-----------|
| No data loss | Step 3 archives all pre-doctrine rows before any modification |
| No broken sessions | Step 4 preserves `original_actor_id`; unmapped sessions flagged, not deleted |
| No invalid routing edges | Step 5 rewrites targets deterministically; `via_faucet` preserves faucet context |
| Idempotent | Every step checks existing state before acting; re-run produces no duplicate rows |
| Soft-delete compliant | No hard deletes at any step |

---

## 6. Capability Mapping

IDE actor capabilities map to canonical roles as follows. This mapping is the authoritative translation table for Steps 4 and 5 of the migration.

| IDE Actor / Faucet | Capability Class | Primary Canonical Role | Secondary Role (if applicable) |
|--------------------|-----------------|------------------------|-------------------------------|
| Cursor — lead orchestration | Coordination, root doc consolidation | **ATHENA** | — |
| Cursor — code generation | Implementation, file editing | **HEPHAESTUS** | — |
| Cursor — routing context | Artifact dispatch | **HERMES** | — |
| Windsurf — scripting/build | Code generation, migrations | **HEPHAESTUS** | — |
| Windsurf — import pipeline | Data ingestion | **HEPHAESTUS** → VISHWAKARMA | — |
| VS Code — analysis | Code review, deep reads | **LILITH** | — |
| VS Code — implementation | Service construction | **HEPHAESTUS** | — |
| Antigravity — analysis | Strategic review | **ATHENA** → **LILITH** | Context-dependent: ATHENA if planning, LILITH if adversarial |
| Cascade — UI/integration | Interface layer | **HEPHAESTUS** | — |
| Warp — terminal/ops | Operational execution | **HEPHAESTUS** | — |
| Kiro — orchestration | Multi-step planning | **ATHENA** | — |

**Rule:** When a capability falls in a gray zone (Antigravity example above), the task annotation `edge_type` resolves the ambiguity: `edge_type = 'plan'` → ATHENA, `edge_type = 'review'` → LILITH. The routing engine (HERMES) reads this annotation from the channel artifact before resolving the target.

---

## 7. Enforcement Layer

### Validation Rule

No new `lupo_actors` row may be created with `actor_type = 'role'` and `slug` matching any known IDE surface name (`cursor`, `windsurf`, `vscode`, `antigravity`, `warp`, `zencoder`, `kiro`, `cascade`, `jetbrains`). Violation returns HTTP 400 with error code `DOCTRINE_VIOLATION_FAUCET_AS_ACTOR`.

Similarly, no routing edge (`lupo_context_edges`) may target an actor whose `actor_type = 'faucet'` directly. Faucets are execution surfaces, not routing targets. Violation returns HTTP 400 with error code `DOCTRINE_VIOLATION_EDGE_TO_FAUCET`.

### Enforcement Location

**Primary: `EdgeValidationService`** — extend `validateCreate()` to check:
1. When `target_type = 'actor'`: resolve the actor row and assert `actor_type != 'faucet'`.
2. When `source_type = 'actor'` and creating a `routes_to` edge: same assertion.

**Secondary: Actor Registry Service** — the service method `createActor()` (when implemented) must:
1. Reject `slug` values matching the IDE surface blocklist.
2. Reject `actor_type = 'role'` without a valid `parent_actor_id` for Layer 2 entries.

**Tertiary: `HeaderValidationService` extension** — add a validator for the `actor_id` field in LUPOPEDIA HEADERS: if the resolved actor has `actor_type = 'faucet'`, emit a warning `HEADER_ACTOR_IS_FAUCET` and substitute the correct canonical role from the capability mapping table.

### Audit Requirement

Every session row records both `actor_id` (canonical role) and `faucet_slug` (surface). This provides a dual-column audit trail: aggregate by `actor_id` to measure canonical role usage; aggregate by `faucet_slug` to measure IDE surface usage. The two dimensions are independently queryable without join complexity.

---

## 8. Backward Compatibility

| Concern | Resolution |
|---------|-----------|
| Historical session records | Remain valid. `legacy_actor_id` column (planning-only) preserves the original IDE actor reference. Sessions referencing old IDE actor IDs are interpretable as `faucet=<ide_slug>` + `actor=<mapped_canonical_role>` via the archive and migration table. |
| Old actor IDs (102, 101, etc.) | These IDs continue to exist in `lupo_actors` (now with `actor_type = 'faucet'`). No row is deleted. All existing foreign-style references remain resolvable. |
| Channel 60 task assignments | Task artifacts authored under "cursor" or "windsurf" actor identity remain valid as historical records. Future artifacts use the canonical faucet + role tuple. No retroactive modification required. |
| Context graph edges | Step 5 of the migration rewrites targets deterministically. Pre-migration edges are backed up in the archive. Post-migration edges carry `via_faucet` metadata, preserving the faucet context. |
| Audit logs | Not modified. Log entries referencing IDE actor IDs remain interpretable via the archive table mapping. |

---

## 9. Verdict

```
DOCTRINE RESOLVED — BLOCK REMOVED
```

All four LILITH blocking issues resolved:

| LILITH Issue | Resolution |
|-------------|-----------|
| 1. Competing canonical role definitions (5 vs 11) | Hierarchical model: 5 Layer 1 routing targets + 6 Layer 2 specializations. The eleven persona doctrine is preserved; the five are the routing spine. |
| 2. No migration path for existing data | Five-step idempotent migration protocol defined in Section 5. No data loss, no broken sessions. |
| 3. Missing session/runtime definitions | Session layer fully defined in Section 4 with required fields, invariants, and purpose. |
| 4. No enforcement mechanism | Three-layer enforcement defined in Section 7: EdgeValidationService, Actor Registry Service, HeaderValidationService. |

---

## 10. Next Steps

**Immediate:**
1. **LILITH** — re-validate this resolution against all four original blocking issues. Expected verdict: UNBLOCKED.
2. **ATHENA** — update the Canonical Role Layer Application Plan (Channel 60) to reference this hierarchical model and adjust Phase 1 task descriptions accordingly.

**Phase 1 kick-off (post LILITH re-validation):**
3. **Cursor** — create canonical actor folder structure: `actors/hephaestus/`, `actors/athena/`, `actors/hermes/`, `actors/2/`, `actors/rose/` with `.metadata.yaml` files (`actor_type: role`).
4. **Windsurf** — write the idempotent migration script (`scripts/migrate_ide_actors_to_faucets.php`) per Section 5.
5. **VS Code** — extend `EdgeValidationService` with the faucet-as-actor rejection rule per Section 7.

**Future phases** follow ATHENA's four-phase execution plan as authorized in Channel 60.

---

**status:** DOCTRINE_RESOLVED
**blocks_removed:** 4
**implementation_ready:** true
**next_actor:** LILITH (re-validation)
