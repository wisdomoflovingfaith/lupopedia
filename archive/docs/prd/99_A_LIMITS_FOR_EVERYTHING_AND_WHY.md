---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/99_A_LIMITS_FOR_EVERYTHING_AND_WHY.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/99_A_LIMITS_FOR_EVERYTHING_AND_WHY.md"
  status: active
  when_updated: "20260422225147"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/99_limits_for_everything_and_why.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/99-limits-for-everything
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: "29"
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_99_A
  title: "Constitutional Limits: Everything Has a Maximum"
  summary: "Constitutional constraints: PRDs (00-99), database tables (<=199), seeded actors (<=999), channels per department (<=99), fixed trust tiers, 22 header keys; utilization and consolidation doctrine."
---
# Constitutional Limits: Everything Has a Maximum

## Purpose and disambiguation

This specification defines **normative numeric and cardinality ceilings** for Lupopedia 4.0.x so growth remains deliberate. It **supersedes** the former **`99_prd_numbering_constraint.md`** (PRD numbering only) and expands scope to additional system limits.

**Filename and number:** This file is **`99_limits_for_everything_and_why.md`** (**PRD 99**, **`pk_id: 99`**). It is **not** PRD 00 ??? root constitutional requirements live in **`00_root_constitutional_system_requirements.md`**. In prose, cite as **PRD 99**, **"limits PRD"**, or **`99_limits_for_everything_and_why.md`**.

**Authority:** Complements **PRD 00** (root constitutional system requirements), **PRD 16** (headers), **PRD 29** (project structure), **PRD 31** (implementations), **PRD 38** (memory / trust ladder PK bands), and **doctrine** registries.

## Constitutional Limits

All numeric limits are defined in the global constants atom:

**`memory/atoms/lupopedia_global_constants.atom.toon`** -> `constants.constitutional_limits`

| Limit | Atom key | Value |
|-------|----------|-------|
| Maximum PRDs | `max_prd` | 99 |
| Maximum tables | `max_tables` | 199 |
| Maximum seeded actors | `max_seed_actors` | 999 |
| Maximum channels per department | `max_channels_per_department` | 99 |

---

## Limit 1: PRD files (00-99)

| Property | Value |
|----------|-------|
| **Maximum** | **100** PRD slots (**00** through **99** inclusive) |
| **Forbidden** | **PRD 100** and any three-digit PRD file prefix in `docs/prd/` |
| **Bands (allocation discipline)** | **00-49** core platform; **50-69** reserved expansion; **70-79** secondary systems (data, metadata, collections, governance); **80-89** specialized doctrine (database, CLI, install); **90-99** constraints, coordination, and governance caps |

### Why this number?

Two decimal digits bound mental load, tooling (`PRD_INDEX.md`), and cross-links. A hard ceiling forces **consolidation** instead of unbounded specification sprawl.

### Utilization thresholds (PRD count = distinct `pk_id` / filename slots in use)

| Utilization | Action |
|-------------|--------|
| **~80%** (80+ slots used) | **Audit** for redundant or overlapping PRDs; defer non-essential new numbers. |
| **~95%** (95+ slots used) | **Consolidation program** required before allocating new numbers; review 50-69 reserve. |
| **100%** (100/100) | **Freeze** new PRD numbers; only **constitutional amendment** + tooling update may redefine the scheme or migrate to a new major version (e.g. 5.0) with a fresh index. |

### Exceptions

No ad hoc **PRD 100**. Relief paths: **merge** PRDs, **archive** prose to non-PRD docs, or **major version** migration with explicit governance approval (**WOLFIE**).

**Enforcement:** `python scripts/validate_prd_number.py` (repository scan). **`python scripts/generate_prd_index.py`** should surface **collisions** when two files resolve to the same numeric id.

---

## Limit 2: Database tables (<=199)

| Property | Value |
|----------|-------|
| **Maximum** | **199** counted **application** tables in the active schema (see exclusions below) |
| **Rationale** | Same discipline as PRDs: approaching the ceiling forces **schema consolidation** and resists one-off table proliferation. **200** is intentionally unused as a hard stop so operators never ???sneak one more??? without a policy decision. |

### Exclusions (do not count toward 199)

- **Vendor** or **third-party** schemas not owned by Lupopedia (if ever co-installed).
- **Explicitly named scratch** tables (prefix `tmp_`, suffix `_tmp`, or documented maintenance tables in **`database/migrations_legacy/`** only when those tables are **not** part of **`install_new_lupopedia.sql`**).
- **Ephemeral** session import tables **documented** as non-canonical (must be listed in this PRD or **`REQUIRED_TABLES_4.0.21.md`** when used).

### Monitoring

```sql
-- Example (MySQL): adjust schema name to DB_NAME from lupopedia-config.php
SELECT COUNT(*) FROM information_schema.tables
 WHERE table_schema = DATABASE() AND table_type = 'BASE TABLE';
```

Compare against **199** after exclusions (see **`scripts/validate_table_count.php`**).

### Utilization

| Level | Action |
|-------|--------|
| **80%** (~159) | Architecture review before new tables. |
| **95%** (~189) | Consolidation required; merge logs, telemetry, or edge tables where safe per doctrine. |
| **100%** | Block new canonical tables until count drops or limits are amended. |

### Exceptions

**Constitutional amendment** plus install/seed updates. Never silently raise the ceiling in application code alone.

---

## Limit 3: Seeded actors (<=999)

| Property | Value |
|----------|-------|
| **Maximum** | **999** **seed / registry-reserved** actor ids in the **`1-999`** band (see **PRD 41**, **`database/lupopedia/actors/registry.json`**, **Reserved ID doctrine**) |
| **Runtime** | **`actor_id >= 1000`** ??? dynamic humans and facets; different lifecycle rules |

### Why?

Low numeric ids remain **human-scannable** and align with **trust ladder seed** semantics. **`1000+`** signals **non-seed** actors.

### Utilization

| Level | Action |
|-------|--------|
| **80%** (~799) | Registry audit; retire unused slugs only via documented process. |
| **95%** (~949) | Freeze new seed actors unless amendment approved. |
| **100%** | No new seed ids; expand only via **governance** and registry policy update. |

**Enforcement:** `php scripts/validate_actor_id.php` (CLI checks + callable helper for insert paths).

---

## Limit 4: Channels (<=99 per department)

| Property | Value |
|----------|-------|
| **Maximum** | **99** **`lupo_channels`** rows **per `department_id`** with **`is_deleted = 0`** (normative product target) |
| **Rationale** | Channels are **memory-scoped collaboration contexts**; unbounded channels per department indicate missing **sub-departments** or consolidation. |

### Utilization

| Level | Action |
|-------|--------|
| **80%** (~79) | Review channel taxonomy; merge duplicates. |
| **95%** (~94) | Split department or archive cold channels per policy. |
| **100%** | Block creation; governance decides split vs cap raise. |

**Enforcement:** Application services that create channels **MUST** query current counts by **`department_id`** before insert (see **`check_limit_utilization.php`** for reporting).

---

## Limit 5: Trust tiers (fixed set)

Exactly **four** header **`trust_tier`** values (**PRD 16** ??4.2 field 9): **`seed`**, **`canonical`**, **`staging`**, **`archive`**.

- **No fifth tier** without **PRD 16 revision** + validator bump + migration plan for **`memory_key`** layout.
- **PK offset** rules for ladder PKs and **`memory_key`** year segments remain under **PRD 38**, **PRD 43**, **PRD 51**, **`CHRONOLOGICAL_TRUST_LADDER.md`**, and **`TRUST_LADDER_REGISTRY.md`** (not duplicated here).

**Enforcement:** `python scripts/validate_lupopedia_headers_universal.py` (**`HDR_TRUST_TIER_INVALID`**).

---

## Limit 6: Header fields (22 required keys)

**PRD 16** ??4.2 defines **exactly 22** scalar keys in **fixed order** (plus dual-field **`dialog_transcript`** rules).

| Rule | Detail |
|------|--------|
| **4.0.x** | New keys require **`header_format_version`** / validator family bump and **PRD 16** revision. |
| **5.0+** | Key **removals** or non-backward-compatible reshaping are **major-version** only. |

**Enforcement:** `python scripts/validate_lupopedia_headers_universal.py`.

---

## Interaction between limits

| If you hit??? | It tends to stress??? | Because??? |
|-------------|---------------------|----------|
| **PRD ceiling** | Tables and actors | More specs imply more schema and more operational identities. |
| **Table ceiling** | Actors and channels | More tables require more owners, jobs, and channel surfaces. |
| **Seed actor ceiling** | Channels and memory | Seeds anchor automation; crowding forces broader **`actor_id`** use and channel fan-out. |
| **Channel ceiling** | Memory graph | Each channel carries **memory scope** and compaction obligations (**PRD 38**). |

---

## Consolidation cascade

When **any** limit reaches **~90%** utilization:

1. **Audit all limits** in one pass (`php scripts/check_limit_utilization.php`).
2. **Identify redundant entities** (duplicate PRDs, overlapping tables, inactive channels, registry cruft).
3. **Consolidate before adding** new PRDs, tables, seed actors, or channels.
4. If **multiple** limits exceed **90%** simultaneously, trigger **architecture review** (dependency-ordered plan per **TASK_PLANNING_DOCTRINE** ??? no calendar estimates).

---

## Enforcement mechanisms

| Limit | Enforced by | Failure behavior |
|-------|-------------|------------------|
| PRDs 00-99 | `scripts/validate_prd_number.py` | CI / pre-commit **fail** on illegal prefix or >99 |
| Tables <=199 | `scripts/validate_table_count.php` | Exit **non-zero**; blocks ???quiet??? schema creep when wired into CI |
| Seed actors <=999 | `scripts/validate_actor_id.php` | Exit **non-zero** on duplicate **`actor_id`** or malformed **`actors`** list; **facet** rows **>999** in **`registry.json`** are expected and not errors |
| Channels <=99 / dept | Application insert paths + utilization report | Creation **rejected** when over cap |
| Trust tiers (4) | `validate_lupopedia_headers_universal.py` | **`HDR_TRUST_TIER_INVALID`** |
| Header fields (22) | `validate_lupopedia_headers_universal.py` | **`HDR_*`** failures |

---

## Monitoring dashboard

Run:

```bash
php scripts/check_limit_utilization.php
```

Optional write-through report (UTC-stamped filename):

```bash
php scripts/check_limit_utilization.php --write-report
```

Reports land under **`docs/reports/`** (created on demand). **Weekly** scheduling: see **`scripts/cron_limit_utilization.sample`** for a host cron entry (operators copy to their environment; the repo does not install cron automatically).

---

## Cross-references

- **PRD 00** ??? `00_root_constitutional_system_requirements.md` (root constitutional requirements).
- **PRD 16** ??? Headers and **`trust_tier`** enum.
- **PRD 29** ??? Project structure and PRD layout.
- **PRD 31** ??? Implementation folder guidelines.
- **PRD 38** ??? Memory graph, PK bands, consolidation edges.
- **`docs/doctrine/TRUST_LADDER_REGISTRY.md`**, **`CHRONOLOGICAL_TRUST_LADDER.md`**.

---

## Teaching Loop Limits (AGAPE Self-Correction)

When Agent A teaches Agent B:

1. Maximum iterations: 7
2. Each iteration SHALL generate a WHY file documenting the violation and correction attempt.
3. If iteration 7 fails:
   - System SHALL stop automatic correction
   - System SHALL escalate to human actor WOLFIE (actor_id 1)
   - System SHALL NOT proceed without human intervention

Rationale:
- Prevents infinite loops
- Prevents token waste
- Prevents compounding errors
- Ensures human oversight when AI cannot self-correct after reasonable attempts

Exception:
- Wolfie may override the limit manually
- Override requires explicit command and logging in a WHY file

This limit aligns with debugging requirements and prevents infinite loops during AGAPE testing.

---

**STATUS:** ACTIVE  
**EFFECTIVE:** Immediate for 4.0.x  

This output complies with Lupopedia Constitutional Root Rules.
