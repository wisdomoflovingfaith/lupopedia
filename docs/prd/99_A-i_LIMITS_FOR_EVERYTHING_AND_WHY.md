---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/99_limits_for_everything_and_why.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/99-limits-for-everything
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_99_A-i
  title: 'Constitutional Limits: Everything Has a Maximum'
  summary: 'Constitutional constraints: PRDs (00-99), database tables (<=199), seeded actors (<=999), channels per department (<=99), artifact thread leaf dirs (<=2000 files), content federation daily dirs (<=2000 files), fixed trust tiers, 22 header keys; utilization and consolidation doctrine.'
---
# Constitutional Limits: Everything Has a Maximum

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Purpose and disambiguation

This specification defines **normative numeric and cardinality ceilings** for Lupopedia 4.0.x so growth remains deliberate. It **supersedes** the former **`99_prd_numbering_constraint.md`** (PRD numbering only) and expands scope to additional system limits.

**Filename and number:** This file is **`99_limits_for_everything_and_why.md`** (**PRD 99**, **`pk_id: 99`**). It is **not** PRD 00 ??? root constitutional requirements live in **`00_root_constitutional_system_requirements.md`**. In prose, cite as **PRD 99**, **"limits PRD"**, or **`99_limits_for_everything_and_why.md`**.

**Authority:** Complements **PRD 00** (root constitutional system requirements), **PRD 16** (headers), **PRD 29** (project structure), **PRD 31** (implementations), **PRD 38** (memory / trust ladder PK bands), and **doctrine** registries.

## Constitutional Limits

All numeric limits are defined in the global constants atom:

**`memory/channels/atoms/lupopedia_global_constants.atom.toon`** -> `constants.constitutional_limits`

| Limit | Atom key | Value |
|-------|----------|-------|
| Maximum PRDs | `max_prd` | 99 |
| Maximum tables | `max_tables` | 199 |
| Maximum seeded actors | `max_seed_actors` | 999 |
| Maximum channels per department | `max_channels_per_department` | 99 |
| Maximum files per thread artifact leaf directory | `max_files_per_thread_artifact_leaf_directory` | 2000 |
| Maximum files per content federation daily folder | `max_files_per_content_daily_folder` | 2000 |

---

## PRD Domain Space Constraint (00-99)

| Property | Value |
|----------|-------|
| **Maximum** | **100** PRD slots (**00** through **99** inclusive) |
| **Hard wall** | PRD number space is bounded to **00** through **99**. The absolute prohibition is defined in PRD 00. |
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

No runtime allocation beyond **99**. Relief paths: expand within an existing PRD number using signature letters and roman slots, reuse formally retired numbers through documented governance, archive prose to non-PRD docs, or perform a major-version constitutional migration.

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

## Limit 7: PRD Length Limit (Structural Constraint)

PRD files MUST NOT exceed 2500 lines.

When a PRD grows beyond 2500 lines, it MUST be evaluated to determine:

* which sections can be split into new PRD files
* which concepts belong in separate domains
* whether the PRD has become overloaded or cross-domain
* how to re-scope the content into smaller, coherent PRDs

---

### Reason for Limit

* prevents PRDs from becoming unmaintainable
* enforces domain clarity
* reduces cognitive load
* ensures each PRD remains a single doctrinal unit
* supports modular architecture and future expansion
* improves agent navigation and reduces context loss

---

### Enforcement Rules

* PRDs MUST be reviewed when approaching 2500 lines
* PRDs exceeding 2500 lines MUST be split
* new PRDs MUST inherit correct group, priority, and index metadata
* no PRD may bypass this limit
* agents MUST NOT insert large new sections into PRDs already above the limit without first splitting

---

## Limit 8: Thread Message Limit (Structural Constraint)

Threads MUST NOT exceed 100 messages.
When a thread reaches 100 messages, it MUST NOT continue.

A new thread MUST be created.

### Required Action

When message count >= 100:

1. STOP processing the current thread
2. GENERATE a handoff summary
3. CREATE a new thread in the same channel
4. CONTINUE execution in the new thread using the summary

### Handoff Summary Requirements

The summary MUST include:

* current task
* completed work
* remaining work
* relevant decisions
* active constraints (PRDs, rules)
* known issues or risks

### Strict Rules

* NO further replies in the old thread after 100 messages
* NO continuation without a summary
* DO NOT copy full thread context
* ONLY carry forward distilled state

### Optional Warning Threshold

At 75 messages:

* system SHOULD warn: "Thread approaching limit. Prepare handoff."

### Failure Mode

Without this rule:

* looping
* context drift
* prompt inflation
* agent confusion
* loss of determinism

### Relationship to System Doctrine

This rule aligns with:

* PRD length limits
* channel isolation
* actor isolation

Threads are bounded cognitive containers and MUST be reset when full.

---

## Structural Limits and Why

Some constitutional caps bound **document shape** (PRD line count, thread message count). Others bound **filesystem density** so directory enumeration, interpreter scans, semantic indexing, and parallel actor loads stay fast and stable.

Very large directories cause slow listing on Windows and Linux, worse interpreter behavior during artifact and content scans, longer semantic reconstruction, UI lag when listing folders, higher risk of fragmentation and I/O stalls, and weaker stability under concurrent access. Modern filesystems allow enormous entry counts; **practical** performance falls off long before physical limits. Capping leaf density keeps artifact and canonical content lookup predictable and stops any single daily folder from becoming a hotspot.

### Limit 9: Maximum files per thread artifact leaf directory (<=2000)

| Property | Value |
|----------|-------|
| **Maximum** | **2000** regular files in **one** leaf directory |
| **Path pattern (normative)** | Repository-relative: `artifacts/{channel}/{thread}/{department}/{actor}/YYYY/MM/DD/` |
| **Counted scope** | **Regular files** whose **parent directory** is the `DD/` segment for that date bucket (same path pattern). Subdirectories under that leaf are out of scope for this numeric cap; do not use deep trees under `DD/` to bypass the rule -- keep artifacts as files in the `DD/` bucket or shard as below. |
| **Rationale** | Fast artifact lookup, predictable tooling, stable federation node behavior, no runaway hotspot directories. |

### Why 2000?

- Enumeration and globbing stay within tolerable latency on typical dev and CI hosts.
- Scanners and validators that walk artifact trees avoid pathological branches.
- Semantic reconstruction and indexing retain bounded work per directory.
- Parallel actors contend less on a single directory lock / metadata storm.

### Required action when approaching the cap

When a **`DD/`** directory for a thread approaches **2000** files:

1. **Shard by date** -- place new artifacts in the next day bucket (`YYYY/MM/DD/`) when chronology allows; or
2. **Create a new thread key** -- continue the workstream under a new `{thread}` segment per channel lifecycle rules (**PRD 02**, **PRD 77**).

Operators MUST NOT allow a single `DD/` leaf to exceed **2000** files except under **constitutional amendment** with tooling and governance updates.

### Enforcement

| Mechanism | Detail |
|-----------|--------|
| **Normative** | This PRD + `constants.constitutional_limits.max_files_per_thread_artifact_leaf_directory` in **`memory/channels/atoms/lupopedia_global_constants.atom.toon`**. |
| **Operational** | Repository audits and pre-publish checks SHOULD count files per `artifacts/.../YYYY/MM/DD/` leaf; dedicated validator script MAY be added under **`scripts/`** in a later change. |

### Cross-references

- **PRD 29** -- project structure and artifact layout discipline.
- **Limit 7** / **Limit 8** (above) -- other structural caps on prose and thread messages.

### Limit 10: Maximum files per content federation daily folder (<=2000)

Content folders hold **canonical documents**, not an unbounded event stream. A single **daily** directory must not grow without bound: uploads and generation bursts can still drop thousands of files into one `{day}/` folder in one calendar day. That pattern repeats the same filesystem costs as Limit 9.

| Property | Value |
|----------|-------|
| **Maximum** | **2000** regular files in **one** daily content directory |
| **Path pattern (normative)** | Repository-relative: `content/federation_node/{federation_node_id}/{channel_key}/{thread_key}/{year}/{month}/{day}/` |
| **Counted scope** | **Regular files** whose **parent directory** is that `{day}/` segment (`day` is a directory name such as `09`, not a filename). Subdirectories under that leaf are out of scope for this numeric cap; do not nest bulk files under `{day}/` subfolders to bypass the rule. |
| **Example** | `content/federation_node/0/captains_log/symbolics/2026/05/09/202605092215_caduceus_entanglement.md` |

### Why this limit exists

- Slow directory enumeration on Windows and Linux.
- Degraded performance for the semantic interpreter over content trees.
- Increased indexing time for content reconstruction.
- UI lag when listing or loading content for that day.
- Higher risk of filesystem fragmentation.
- Instability when multiple actors access the same folder concurrently.

### Why 2000?

- Fast content lookup and predictable interpreter behavior.
- Stable federation node performance when scanning `content/federation_node/`.
- No single daily folder becomes a bottleneck.

### Sharding rule

High-volume channels or threads **shard naturally by date**: the next calendar day uses a new `{year}/{month}/{day}/` path. No separate sharding mechanism is required beyond normal date-segmented layout.

### Enforcement rule

All **write paths** that create files under this pattern (UI, API, automation) MUST count existing **regular files** in the target `{day}/` directory **before** creating a new file.

When the count is already **2000**, the operation MUST be **rejected**. The next eligible file would be **2001**.

**UI (normative copy):** When the UI blocks the save/upload/create action, it MUST display:

- "This daily folder is full."
- "Please delete or merge files, or wait until the next day."

Implementations SHOULD route these strings through **`lupo_t()`** with these English fallbacks (**PRD 00**, UI strings doctrine).

**API / non-UI:** Responses MUST use an explicit error code or message consistent with the same rule (no silent failure); callers surface the same guidance where a human is involved.

### Normative reference

`constants.constitutional_limits.max_files_per_content_daily_folder` in **`memory/channels/atoms/lupopedia_global_constants.atom.toon`**.

### Cross-references

- **PRD 29** -- project structure and content layout.
- **Limit 9** (above) -- parallel cap for **`artifacts/.../DD/`** leaves.

---

## Interaction between limits

| If you hit??? | It tends to stress??? | Because??? |
|-------------|---------------------|----------|
| **PRD ceiling** | Tables and actors | More specs imply more schema and more operational identities. |
| **Table ceiling** | Actors and channels | More tables require more owners, jobs, and channel surfaces. |
| **Seed actor ceiling** | Channels and memory | Seeds anchor automation; crowding forces broader **`actor_id`** use and channel fan-out. |
| **Channel ceiling** | Memory graph | Each channel carries **memory scope** and compaction obligations (**PRD 38**). |
| **Artifact leaf directory ceiling** | Tooling and CI | Dense `artifacts/.../DD/` trees slow scans and federation workloads (**Limit 9**). |
| **Content daily folder ceiling** | UI and content pipeline | Dense `content/federation_node/.../{day}/` trees slow listing, indexing, and multi-actor access (**Limit 10**). |

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
| Thread artifact `DD/` leaf <=2000 files | Operational audit; optional future `scripts/` validator | Breach **SHOULD** block new writes to that leaf until sharding or new thread key (**Limit 9**) |
| Content federation `{day}/` folder <=2000 files | Application write paths + UI | On attempt to add file **2001**: **MUST** reject; UI shows normative messages (**Limit 10**) |

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

When Agent A teaches Agent Z:

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

## PRD Domain Integrity Rule (No Range Balancing)

### Principle

PRD numbers represent domain identity, not distribution balance.

PRD numbers are not buckets for evenly distributing files. They are stable anchors for domains of truth.

Uneven distribution across PRD numbers is expected and correct.

---

### Expansion Rules

Growth MUST occur inside the existing PRD number.

Use:

* signature letters (A, B, C, ...)
* roman slots (i, ii, iii, ...)

Examples:

* 82_A-i
* 82_A-ii
* 82_B-i
* 82_C-i

A PRD number may contain many files. This is not a problem.

---

### Forbidden Actions

DO NOT rebalance domains.

Specifically:

* DO NOT split a large domain into new PRD numbers
* DO NOT merge unrelated domains to "fill gaps"
* DO NOT move content between PRD numbers for symmetry
* DO NOT allocate new PRD numbers just because a domain is large

PRD numbers MUST remain stable once established.

---

### Structural Truth

Uneven distribution is expected.

Uniform distribution is artificial.

Artificial distribution causes doctrine drift, broken lineage, and non-deterministic execution.

---

### Pressure Handling

If a domain grows large:

1. Expand internally using signature letters and roman slots
2. Refactor within the domain before considering new numbers

If PRD number pressure appears:

1. Reuse deprecated numbers only if formally retired
2. Audit domain boundaries before allocating new numbers
3. Treat 100+ as forbidden by PRD 00 unless a future major-version constitutional amendment replaces the numbering system

---

### kapakai

PRD numbers treated as distribution buckets lead to forced restructuring and domain corruption.

---

### pono

PRD numbers remain stable domain anchors.

All growth is absorbed within the domain using letters and roman slots.

---

### Enforcement Rule

When creating or updating a PRD:

* If the topic fits within an existing domain, it MUST be placed in that PRD number
* Creating a new PRD number requires justification that no existing domain can contain the topic

Absence of justification is a violation.
