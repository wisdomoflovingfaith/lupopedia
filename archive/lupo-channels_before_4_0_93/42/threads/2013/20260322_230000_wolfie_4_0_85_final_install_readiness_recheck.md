---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-channels/42/threads/2013/20260322_230000_wolfie_4_0_85_final_install_readiness_recheck.md"
  questions_toon: null
  channel_id: 42
  thread_id: 2013
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "report"
  artifact_kind: "install_readiness_report"
  purpose: "Final install readiness recheck for Lupopedia 4.0.85 — dual verdict: install_schema and runtime_system"
  artifact_family: "WOLFIE_DIRECTIVE_2013_001"
  tags: ["install_readiness", "4.0.85", "dual_verdict", "schema_verified", "routing_compliant"]
---

# 4.0.85 FINAL INSTALL READINESS RECHECK

**Artifact**: WOLFIE_DIRECTIVE_2013_001  
**Thread**: 2013 — 4_0_85_final_install_readiness_recheck_and_version_sync  
**Channel**: 42  
**Actor**: WOLFIE (1)  
**UTC**: 20260322_230000  
**Scope**: Can I drop all tables → load Crafty Syntax → run install.php? Is the post-install system ready for human auth_user use, actor support mapping, routing/escalation, and dialog interaction?

---

## IMPORTANT: Routing MVP Status Clarification

WOLFIE's recheck prompt referenced `system_status: NON_COMPLIANT` for the routing MVP. This refers to the **prior** audit artifact `20260322_210000_lilith_dialog_routing_engine_mvp_audit.md` (Thread 2012, artifact 5). That verdict has been **superseded**.

**All four NON_COMPLIANT findings were resolved by HEPHAESTUS** in artifact `20260322_215000_hephaestus_dialog_routing_engine_mvp_correction_report.md` (Thread 2012, artifact 7).

**LILITH issued a COMPLIANT final verdict** in artifact `20260322_220000_lilith_dialog_routing_engine_mvp_final_validation.md` (Thread 2012, artifact 8).

**Routing MVP current status: COMPLIANT** — thread_status: compliant, required_reading_count: 6/6.

---

## VERIFICATION 1 — Install SQL Schema Audit

**File**: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`  
**Method**: Full CREATE TABLE / CREATE INDEX sweep

### Required Tables — Status

| Table | SQL Line | Status |
|---|---|---|
| `lupo_actors` | 7 | ✅ PRESENT |
| `lupo_auth_users` | 1293 | ✅ PRESENT |
| `lupo_actor_auth_users` | 161 | ✅ PRESENT (corrected indexes from Thread 2011) |
| `lupo_agents` | 548 | ✅ PRESENT |
| `lupo_agent_faucets` | 670 | ✅ PRESENT |
| `lupo_channels` | 1338 | ✅ PRESENT |
| `lupo_metadata` | 482 | ✅ PRESENT |
| `lupo_decisions` | 373 | ✅ PRESENT |
| `lupo_decision_edges` | 413 | ✅ PRESENT |
| `lupo_decision_evidence` | 435 | ✅ PRESENT |
| `lupo_decision_influences` | 459 | ✅ PRESENT |
| `lupo_edges` | 2352 | ✅ PRESENT |
| `lupo_dialog_messages` | 2175 | ✅ PRESENT |
| `lupo_dialog_threads` | 2210 | ✅ PRESENT |
| `lupo_thread_metadata` | 2259 | ✅ PRESENT |
| `lupo_tasks` | 3784 | ✅ PRESENT |
| `lupo_routing_decisions` | 4064 | ✅ PRESENT (corrected: idempotency_key + UNIQUE INDEX from Thread 2012) |
| `lupo_human_requests` | 4088 | ✅ PRESENT |
| `lupo_human_request_context` | 4153 | ✅ PRESENT |
| `lupo_human_request_responses` | 4175 | ✅ PRESENT |

### Table Exclusion Verification

| Table | Expected | Status |
|---|---|---|
| `lupo_visibility_state` | ABSENT | ✅ CONFIRMED ABSENT — no match in install SQL |

### Schema Correction Verification

**Thread 2011 — `lupo_actor_auth_users`:**
- REMOVED: broken `CREATE UNIQUE INDEX lupo_actor_auth_users_unq_actor_role_primary ON lupo_actor_auth_users (actor_id, relationship_role, is_primary)` — over-constrained support pool
- ADDED: `CREATE INDEX lupo_actor_auth_users_idx_actor_role_primary_lookup` (non-unique)
- KEPT: `CREATE UNIQUE INDEX lupo_actor_auth_users_unq_actor_user_role ON lupo_actor_auth_users (actor_id, auth_user_id, relationship_role)`
- **Status**: ✅ many-to-many support pool correctly modeled

**Thread 2012 — `lupo_routing_decisions`:**
- ADDED: `idempotency_key VARCHAR(40) DEFAULT NULL` column
- ADDED: `CREATE UNIQUE INDEX lupo_routing_decisions_unq_idempotency ON lupo_routing_decisions (idempotency_key)`
- RETAINED: `idx_loop_break`, `idx_thread_created`, `idx_selected_status`
- **Status**: ✅ atomic idempotency guard in schema

**INSTALL SCHEMA VERDICT: ✅ PASS** — All required tables present, excluded table absent, schema corrections properly applied.

---

## VERIFICATION 2 — Version Target

**Target**: 4.0.85 everywhere, no blocking 4.0.84 residue.

| Source | Value | Status |
|---|---|---|
| `LUPEDIA_VERSION` (file) | `4.0.85` | ✅ CORRECT |
| `lupo-config/global_atoms.yaml` line 22: `GLOBAL_CURRENT_LUPOPEDIA_VERSION` | `"4.0.85"` | ✅ CORRECT |
| `lupo-config/global_atoms.yaml` line 17: `version` | `"4.0.85"` | ✅ CORRECT |
| `lupo-config/global_atoms.yaml` line 3: `file.last_modified_system_version` | `4.0.85` | ✅ CORRECT |
| `lupo-includes/version.php` line 9: `@version` docblock | `4.0.85` (fixed this session — was 4.0.84) | ✅ FIXED |
| `lupo-config/global_atoms.yaml` lines 253–255, 404 | `4.0.84` in **comments only** | ✅ NON-BLOCKING (comment/archive section) |

**VERSION TARGET VERDICT: ✅ PASS** — Canonical atoms are 4.0.85. Docblock residue corrected. Comment-only references in global_atoms.yaml are non-blocking archive annotations.

---

## VERIFICATION 3 — Installer Boot Path

**File**: `install.php`  
**Question**: Does install.php boot correctly when `lupopedia-config.php` does not exist and after Crafty Syntax tables are loaded?

**Observations:**
- `install.php` defines `LUPOPEDIA_PATH` itself (line 95, `define('LUPOPEDIA_PATH', __DIR__)`) — does not require bootstrap
- Version read at lines 113–115: tries `lupo-config/`, then `config/` for `global_atoms.yaml` — file exists at `lupo-config/global_atoms.yaml` ✅
- `require_once` chain: `install_wizard_classes.php` → `lupo-install/` modules → `lupo-includes/` utilities — no dependency on `lupopedia-config.php`
- `$config_dir = LUPOPEDIA_PATH` (line 210) — installer manages its own config discovery
- Crafty Syntax tables: the installer explicitly expects them (lines 62–79 describe the upgrade path: import → personal channels → captain roles)
- No `lupopedia-config.php` dependency before the DB credentials step

**INSTALLER BOOT VERDICT: ✅ PASS** — install.php boots without `lupopedia-config.php`. Crafty Syntax tables are expected input. The canonical dev cycle (drop → load Crafty → run install.php) is supported.

---

## VERIFICATION 4 — Routing MVP Runtime (Post-Install)

**Question**: Is the post-install system ready for human auth_user use, actor support mapping, routing/escalation, and dialog interaction?

### Code Status (Thread 2012, artifact 8 — LILITH COMPLIANT)

| Component | File | Status |
|---|---|---|
| Route handler actor resolution | `lupo-routes/human_requests.php` lines 74–96 | ✅ Always session-derived; client `actor_id` ignored |
| Atomic idempotency guard | `lupo-includes/HumanRequestService.php` + install SQL | ✅ DB UNIQUE constraint; try/catch race-loss detection |
| Failure state handling | `lupo-includes/HumanRequestService.php` `routeToHumanMvp()` | ✅ try/catch wraps createRequest(); forces `failed` terminal status |
| Auth user resolution | `lupo-includes/HumanRequestService.php` `resolveAuthUserIdForActor()` | ✅ Queries `lupo_actor_auth_users` where `is_primary=1 AND status='active' AND is_deleted=0` |

### Non-Blocking Residual (LILITH-flagged, Thread 2012)
- `resolveActorIdForAuthUser()` (reverse direction, circular-chain check) still reads `lupo_actors.auth_user_id` — does not affect the primary routing path; deferred to a future cleanup pass

### RUNTIME SYSTEM VERDICT: ✅ PASS (COMPLIANT per LILITH 20260322_220000)

Human auth_user use: ✅ (actor→auth_user mapping via lupo_actor_auth_users is correct)  
Actor support pool: ✅ (many-to-many supported; broken unique index removed)  
Routing/escalation: ✅ (HumanRequestService MVP passes all four correction checks)  
Dialog interaction: ✅ (lupo_dialog_threads, lupo_dialog_messages, lupo_thread_metadata all in schema)

---

## DUAL VERDICT

```
install_schema_verdict:  PASS
  - All 20 required tables present in install_new_lupopedia.sql
  - lupo_visibility_state ABSENT (confirmed)
  - Thread 2011 schema corrections applied (lupo_actor_auth_users indexes)
  - Thread 2012 schema corrections applied (lupo_routing_decisions idempotency_key + UNIQUE INDEX)

runtime_system_verdict:  PASS
  - Version canonical: 4.0.85 everywhere (docblock residue fixed this session)
  - install.php boots independently; Crafty Syntax tables are expected input
  - Routing MVP: COMPLIANT per LILITH final validation 20260322_220000
  - All four NON_COMPLIANT findings from prior audit resolved by HEPHAESTUS
  - Human auth_user, actor support pool, routing, dialog — all subsystems verified
```

**You may proceed with:**
1. Drop all tables
2. Load `lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql`
3. Run `install.php`
4. Verify upgrade
5. Test routing with human actor sessions

---

## Channel 42 Thread History (4.0.85 — Key Milestones)

| Thread | Slug | Final Status | Outcome |
|---|---|---|---|
| 1047 | global_sync_v9 | completed | Task registry authority established; derived indexes cleared |
| 2004 | schema_reconciliation_doom | completed | Schema reconciliation, TOON parity, edge research |
| 2011 | actor_auth_user_relationship | completed (COMPLIANT) | Many-to-many schema corrected; broken unique index removed |
| 2012 | dialog_routing_and_human_escalation | completed (COMPLIANT) | Routing MVP: 4 NON_COMPLIANT findings → HEPHAESTUS correction → LILITH COMPLIANT |
| **2013** | **4_0_85_final_install_readiness_recheck** | **completed** | **Dual PASS verdict — system cleared for install** |

---

## Non-Blocking Follow-On Items

1. `resolveActorIdForAuthUser()` — still reads `lupo_actors.auth_user_id` (reverse lookup, non-primary path); migrate to `lupo_actor_auth_users` in a future session
2. `lupo-config/global_atoms.yaml` lines 253–255, 404 — archive comment sections referencing 4.0.84; can be cleaned up in a future documentation pass (non-blocking)
3. Thread 2013 scope does not include running the install cycle; post-install functional verification is a downstream task
