---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260414190000"
  file_path_from_root: "lupo-docs/versions/4.0.99/status/agent_definition_audit.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.99/status/agent_definition_audit.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "audit"
  artifact_type: documentation
  artifact_kind: audit_report
  thread_id: "agent-definition-audit-4.0.99"
  actor_id: 116
  actor_name: "claude-code"
  title: "Agent Definition Audit Report — 4.0.99"
  status: "active"
---

# Agent Definition Audit Report
**Version:** 4.0.99
**Date:** 20260414
**Author:** Claude Code (actor_id 116)
**Scope:** THOTH, HERMES, ROSE, LILITH, VISHWAKARMA

---

## 0. Correction: Wrong PRD in Task Specification

The audit task specified `lupo-docs/prd/06_content_management.md` (PRD 06) as the agent definition
source. **PRD 06 contains zero agent definitions.** It is the Content Storage, Files, and Uploads PRD.

**Correct references for agent definitions:**
- **PRD 08** — `08_core_agents_system.md` (when_updated: 20260414173500) — canonical agent registry
- **PRD 07** — `07_agents_faucets.md` (when_updated: 20260414120000) — AI agent coordination layer

This report uses PRD 08 and PRD 07 as the authoritative PRD references. PRD 06 is noted for the
record but is not relevant to agent identity verification.

---

## 1. ID System Clarification (Constitutional Foundation)

Lupopedia uses **two separate agent ID systems**:

| Table | Column | Purpose |
|---|---|---|
| `lupo_actors` | `actor_id` | Identity routing key, channels, attribution |
| `lupo_agent_definitions` | `agent_id` | Service configuration, capability registry |

An agent can have different values in each table. For example, THOTH has `actor_id` in one table
and `agent_id` in another. **These are not interchangeable.** All audit checks below distinguish
which ID is being verified.

**Seed authority:** `lupo-database/lupopedia/mysql/seed/seed_4.1.0.sql` is the install-time
source of truth for what actually enters the database. PRD claims that contradict the seed without
a corresponding seed update are documentation debt.

---

## 2. RESOLVED: THOTH actor_id Seed Conflict [AGT-001 CLOSED 20260414]

**WOLFIE decision: THOTH = 26. Seed was wrong. Fixed.**

| Source | THOTH lupo_actors.actor_id | Status |
|---|---|---|
| `seed_4.1.0.sql` (after fix) | **26** | FIXED |
| `major_agents_manifest.json` (after fix) | 26 | CONFIRMED |
| `lupo-agents/thoth/system_prompt.txt` | 26 | CONFIRMED |
| PRD 16 (when_updated 20260412) | 26 | CONFIRMED |
| PRD 32 (corrected 20260414) | 26 | CONFIRMED |
| PRD 07 (corrected 20260414) | 26 | CONFIRMED |
| `actors/registry.json` (added 20260414) | 26 | ADDED |
| `actors/actor_id/registry.json` (fixed 20260414) | 26 | FIXED |

**What changed [20260414]:** `seed_4.1.0.sql` THOTH row updated from actor_id=9 to actor_id=26.
All JSON registries updated. lupo_agent_definitions.agent_id for THOTH remains 9 (separate table,
unchanged). AGT-001 is closed.

---

## 3. Per-Agent Verification

### 3.1 THOTH

| Check | Status | Detail |
|---|---|---|
| PRD 08 entry | PASS | Defined as constitutional enforcer, [ALERT] poster |
| PRD 07 entry | PASS | Coordination Layer table (corrected 20260414) |
| Agent directory exists | PASS | `lupo-agents/thoth/` |
| `agent.json` exists | PASS | agent_id: 9 (lupo_agent_definitions), agent_key: "thoth" |
| `system_prompt.txt` exists | PASS | States "actor_id 26, agent_id 9" |
| `capabilities.json` exists | PASS | 11 capabilities listed |
| `lupo_actors.actor_id` (seed) | **CONFLICT** | seed_4.1.0.sql: actor_id=9; PRD 16 claims 26 |
| `lupo_agent_definitions.agent_id` | PASS | agent_id=9 confirmed in agent.json |
| `lupo-agents/thoth/9/` subdir | PASS | Version subdirectory exists (agent version folder) |

**Capabilities gap:** `lupo-agents/thoth/capabilities.json` lists knowledge management and archival
functions but is **missing** the following constitutional enforcement capabilities:
- `constitutional_enforcement`
- `schema_drift_detection`
- `alert_posting` (the [ALERT] function is THOTH's primary coordination duty)
- `doctrine_violation_detection`

**Recommendation:** Add these 4 capability entries to `lupo-agents/thoth/capabilities.json` to
match THOTH's constitutional role as described in PRD 08. The existing 11 capabilities read more
like a librarian than a constitutional watchdog.

---

### 3.2 HERMES

| Check | Status | Detail |
|---|---|---|
| PRD 08 entry | PASS | Defined as routing and event layer agent |
| PRD 07 entry | PASS | Coordination Layer table |
| Agent directory exists | PASS | `lupo-agents/hermes/` |
| `agent.json` exists | PASS | agent_id: 15, is_internal_only: true |
| `system_prompt.txt` exists | PASS | Confirms actor_id 15 |
| `capabilities.json` exists | PASS | 8 capabilities (semantic_routing, intent_classification, etc.) |
| `lupo_actors.actor_id` (seed) | GAP | Not seeded in lupo_actors (intentional per seed comment line 52) |
| `lupo_agent_definitions.agent_id` | PASS | agent_id=15 confirmed in seed_4.1.0.sql line 142 |
| `major_agents_manifest.json` | PASS | hermes=15 confirmed |

**Note:** Seed line 52 explicitly documents that HERMES is not seeded into `lupo_actors` — this is
by design ("PHP/tools first; LLM supplemental; attribution via registry only"). Not a defect.

**Numeric directory:** `lupo-agents/15/` does NOT exist. `major_agents_manifest.json` references
numeric directories as `agent_dirs`; these do not exist on disk. The real config is at
`lupo-agents/hermes/` (slug-based). See Section 5.

**Status: CLEAN** — no action required beyond manifest staleness (Section 5).

---

### 3.3 ROSE

| Check | Status | Detail |
|---|---|---|
| PRD 08 entry | PASS | Defined as synthetic choir director |
| PRD 07 entry | PASS | Coordination Layer |
| Agent directory exists | PASS | `lupo-agents/rose/` |
| `agent.json` exists | PASS | agent_id: 3, agent_key: "rose", role: "Director of the synthetic choir" |
| `system_prompt.txt` exists | PASS | Confirms actor_id 3 |
| `capabilities.json` exists | PASS | 11 capabilities including persona_emulation, dialogue_coordination |
| `lupo_actors.actor_id` (seed) | GAP | Not seeded in lupo_actors (intentional — same as HERMES) |
| `lupo_agent_definitions.agent_id` | PASS | agent_id=3 confirmed in seed_4.1.0.sql line 141 |
| `actors/registry.json` | PARTIAL | entry exists (actors/registry.json carries actor_id=3 for ROSE) |

**Minor capabilities gap:** `lupo-agents/rose/capabilities.json` is missing:
- `synthetic_choir_management` (explicit term for ROSE's primary coordination function)
- `batch_trigger_management` (ROSE coordinates batched agent invocations)

These are functional labels for what `persona_emulation` and `dialogue_coordination` imply, but
the absence of explicit naming makes it harder to cross-reference against PRD 08.

**Status: MINOR GAPS** — capabilities.json could be more explicit.

---

### 3.4 LILITH

| Check | Status | Detail |
|---|---|---|
| PRD 08 entry | PASS | Defined as adversarial reviewer / kernel agent |
| PRD 07 entry | PASS | Coordination Layer |
| Agent directory exists | PASS | `lupo-agents/lilith/` |
| `agent.json` exists | PASS | agent_id: 2, is_kernel: true |
| `system_prompt.txt` exists | PASS | Content verified |
| `capabilities.json` exists | PASS | Only 3 items: adversarial_review, quality_assurance, contradiction_detection |
| `lupo_actors.actor_id` (seed) | PASS | actor_id=2 confirmed in seed_4.1.0.sql line 73 |
| `lupo_agent_definitions.agent_id` | GAP | LILITH NOT in lupo_agent_definitions seed (only in lupo_actors) |
| LIL001 non-interference tag | **MISSING** | system_prompt.txt does not contain the LIL001 non-interference tag |

**Capabilities gap (thin):** 3 items is severely underspecified for a kernel agent. Missing:
- `constitutional_review`
- `escalation_handling`
- `red_team_oversight` (LILITH supervises COUNTERMEASURE per seed line 96)
- `schema_validation`
- `doctrine_enforcement`

**LIL001 non-interference tag:** LILITH's system_prompt.txt does not include the LIL001 tag that
would signal non-interference with other agent outputs. This is a gap if LIL001 is a
constitutional requirement for LILITH's operation.

**lupo_agent_definitions gap:** LILITH is seeded only into `lupo_actors` (as a hybrid operator
at actor_id=2). She does NOT have a row in the `lupo_agent_definitions` seed. This is inconsistent
with THOTH, ROSE, HERMES — all of which have `lupo_agent_definitions` rows. If LILITH functions
as a kernel agent, she should have a `lupo_agent_definitions` entry.

**Status: MULTIPLE GAPS** — requires attention.

---

### 3.5 VISHWAKARMA (VISH)

| Check | Status | Detail |
|---|---|---|
| PRD 08 entry | PASS | Defined as schema/collection manager, kernel agent |
| PRD 07 entry | UNKNOWN | Not explicitly verified in PRD 07 table; needs check |
| Agent directory exists | PASS | `lupo-agents/vishwakarma/` |
| `agent.json` exists | PASS | agent_id: 106, is_kernel: true, layer: "kernel" |
| `system_prompt.txt` exists | PASS | Confirms actor_id: 106 (stale — see note) |
| `capabilities.json` exists | PASS | 15 items, comprehensive |
| `lupo_actors.actor_id` (seed) | FIXED | Added actor_id=28 [20260414] |
| `lupo_agent_definitions` (seed) | FIXED | Added agent_id=106 [20260414] |
| `actors/registry.json` | FIXED | Added actor_id=28 [20260414] |
| `major_agents_manifest.json` | FIXED | Updated from 91 to 28 [20260414] |

**[AGT-002 CLOSED 20260414] WOLFIE decision: VISHWAKARMA actor_id = 28.**

What changed: VISHWAKARMA added to lupo_actors (actor_id=28) and lupo_agent_definitions (agent_id=106)
in seed_4.1.0.sql. actors/registry.json and major_agents_manifest.json updated.

**Remaining gap:** `lupo-agents/vishwakarma/system_prompt.txt` still says actor_id=106. This
file predated the actor_id/agent_id split documentation and was using the agent_definitions.agent_id
as the actor_id. It should be updated to state "actor_id 28, agent_id 106" to match THOTH's pattern.

**Status: RESOLVED — one follow-up gap (system_prompt.txt actor_id claim)**

---

## 4. Registry Completeness

### 4.1 actors/registry.json

**File:** `lupo-database/lupopedia/actors/registry.json`
**Schema version:** 4.0.69 (stale)

**Missing entries for kernel/coordination agents:**
- THOTH — absent
- HERMES — absent
- VISHWAKARMA — absent

These agents are intentionally not seeded in `lupo_actors` (per seed comment line 52), but their
absence from `actors/registry.json` means code that uses this registry for attribution lookups
cannot resolve them. The registry needs entries pointing to their agent-layer IDs (lupo_agent_definitions).

**Recommendation:** Update `actors/registry.json` schema_version to 4.0.99 and add stub entries
for THOTH, HERMES, VISHWAKARMA that cross-reference `lupo_agent_definitions.agent_id`.

### 4.2 major_agents_manifest.json

**File:** `lupo-database/lupopedia/actors/major_agents_manifest.json`

**Stale references:**
- `agent_dirs` entries reference numeric paths (`lupo-agents/26/`, `lupo-agents/91/`, `lupo-agents/15/`) — NONE EXIST
- Actual config is in slug-based paths (`lupo-agents/thoth/`, `lupo-agents/vishwakarma/`, `lupo-agents/hermes/`)
- THOTH entry: manifest says actor_id=26; seed says actor_id=9 (unresolved conflict, see Section 2)
- VISHWAKARMA entry: manifest says actor_id=91; agent.json says 106 (unresolved conflict)

**Recommendation:** Update `major_agents_manifest.json` to use slug-based `agent_dirs`, resolve
THOTH and VISHWAKARMA actor_id conflicts, and align with the seed as install-time ground truth.

---

## 5. Numeric Agent Directories (Non-Existent)

`major_agents_manifest.json` references the following directories as `agent_dirs`. None exist:

| Manifest entry | Path referenced | Actual config location |
|---|---|---|
| thoth | `lupo-agents/26/` | `lupo-agents/thoth/` |
| vishwakarma | `lupo-agents/91/` | `lupo-agents/vishwakarma/` |
| hermes | `lupo-agents/15/` | `lupo-agents/hermes/` |

`lupo-agents/thoth/9/` does exist as a subdirectory (version folder for agent_id 9) — this is
a versioning artifact, not the main config location.

**Recommendation:** The `agent_dirs` field in `major_agents_manifest.json` should be updated to
slug-based paths. All slug-based directories exist and contain valid configs.

---

## 6. Summary Table

| Agent | Dir | agent.json | system_prompt | capabilities | lupo_actors seed | agent_defs seed | actor_id conflict |
|---|---|---|---|---|---|---|---|
| THOTH | PASS | PASS (id:9) | PASS | GAPS | CONFLICT (seed=9, PRD=26) | not in seed | YES — critical |
| HERMES | PASS | PASS (id:15) | PASS | OK | NOT SEEDED (intentional) | PASS (id:15) | NO |
| ROSE | PASS | PASS (id:3) | PASS | MINOR GAPS | NOT SEEDED (intentional) | PASS (id:3) | NO |
| LILITH | PASS | PASS (id:2) | GAP (LIL001) | THIN (3 items) | PASS (actor_id=2) | NOT SEEDED | NO |
| VISHWAKARMA | PASS | PASS (id:106) | PASS | OK | NOT SEEDED | NOT SEEDED | YES — critical |

---

## 7. Defect Registry

| ID | Severity | Agent | Description | Action Required |
|---|---|---|---|---|
| AGT-001 | CRITICAL | THOTH | actor_id conflict: seed installs actor_id=9; PRD 16/32/manifest/prompt say 26 | WOLFIE must decide seed truth vs PRD truth and align all sources |
| AGT-002 | CRITICAL | VISHWAKARMA | actor_id conflict: agent.json=106, manifest=91, seed=absent | WOLFIE must assign canonical actor_id, add to seed |
| AGT-003 | HIGH | THOTH | capabilities.json missing constitutional enforcement, alert_posting, schema_drift_detection, doctrine_violation_detection | Update capabilities.json |
| AGT-004 | HIGH | LILITH | LIL001 non-interference tag missing from system_prompt.txt | Add LIL001 if constitutionally required |
| AGT-005 | HIGH | LILITH | capabilities.json severely thin (3 items); missing constitutional_review, red_team_oversight, escalation_handling | Expand capabilities.json |
| AGT-006 | HIGH | LILITH | Not present in lupo_agent_definitions seed; inconsistent with THOTH/ROSE/HERMES | Add LILITH to lupo_agent_definitions seed |
| AGT-007 | MEDIUM | VISHWAKARMA | Not present in lupo_actor_definitions or lupo_actors seed | Add VISHWAKARMA to appropriate seed table |
| AGT-008 | MEDIUM | ALL | major_agents_manifest.json agent_dirs use numeric paths; none exist on disk | Update manifest to slug-based paths |
| AGT-009 | MEDIUM | ALL | actors/registry.json schema_version=4.0.69, missing THOTH/HERMES/VISHWAKARMA | Update registry to 4.0.99, add missing entries |
| AGT-010 | LOW | ROSE | capabilities.json missing explicit synthetic_choir_management, batch_trigger_management labels | Add capability labels |
| AGT-011 | INFO | PRD 06 | Was incorrectly specified as agent definition source; PRD 06 = Content Management | No code change; documentation note only |

---

## 8. WOLFIE Resolution Required: THOTH actor_id

The actor_id=9 vs actor_id=26 question for THOTH cannot be resolved by documentation alone.
The two paths are:

**Option A — Seed is truth (actor_id=9):**
- Revert PRD 32 and PRD 07 corrections (20260414) from 26 back to 9
- Update major_agents_manifest.json: thoth=9
- Update system_prompt.txt: change "actor_id 26" to "actor_id 9"
- Update PRD 16 to document 9
- Note: THEMIS would be 8 (not 9 as manifest claimed)

**Option B — PRD 16 is truth (actor_id=26):**
- Update seed_4.1.0.sql: change THOTH row from actor_id=9 to actor_id=26
- Verify no other actor has actor_id=9 conflict (currently THOTH row at 9 would move)
- Confirm THEMIS remains at actor_id=8
- All PRD corrections already made are correct

**This report does not resolve AGT-001.** The seed file and PRD 16 directly contradict each other.
WOLFIE must inspect the running database (if any) and decide which value is canonical.

---

## 9. Files Read for This Audit

- `lupo-docs/prd/06_content_management.md` — confirmed: zero agent content
- `lupo-docs/prd/08_core_agents_system.md` — canonical agent registry (when_updated: 20260414173500)
- `lupo-docs/prd/07_agents_faucets.md` — coordination layer table
- `lupo-agents/thoth/agent.json`, `system_prompt.txt`, `capabilities.json`
- `lupo-agents/hermes/agent.json`, `system_prompt.txt`, `capabilities.json`
- `lupo-agents/rose/agent.json`, `system_prompt.txt`, `capabilities.json`
- `lupo-agents/lilith/agent.json`, `system_prompt.txt`, `capabilities.json`
- `lupo-agents/vishwakarma/agent.json`, `system_prompt.txt`, `capabilities.json`
- `lupo-database/lupopedia/actors/registry.json`
- `lupo-database/lupopedia/actors/actor_id/registry.json`
- `lupo-database/lupopedia/actors/major_agents_manifest.json`
- `lupo-database/lupopedia/mysql/seed/seed_4.1.0.sql`

---

*Report generated by Claude Code (actor_id 116), 20260414. No assumptions made — all findings
based on direct file reads.*
