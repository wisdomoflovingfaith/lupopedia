---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md
  status: active
  when_updated: '20260811171511'
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
  prd_cluster: 00_A-i_99_A-i_34_A-i
  title: 'Constitutional Limits: Everything Has a Maximum'
  summary: 'Constitutional ceilings: RULE 99 color bands; 4.2.4 LUP:FFFFFF-RRRRRR-NN-II-LL-AA; Federation 000001 -> X human compression; RRRRRR is artifact, not color.'
---
# Constitutional Limits: Everything Has a Maximum

## LUP -- Linked Universal Protocol

**LUP** stands for **Linked Universal Protocol**, the universal identity system used by Lupopedia to identify, version, translate, federate, and track provenance for any digital artifact.

LUP -- Linked Universal Protocol (Universal Artifact Identity). Not a song-only ID. Not "Lupopedia ID."

LUP (Linked Universal Protocol) Identity Grammar:

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

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
| Maximum catalog Actors (song color owners) | `max_catalog_actors` (RULE 99) | **144000** |
| Colors per catalog Actor | `colors_per_catalog_actor` (RULE 99) | **100** |
| Artifacts per namespace (NN) | `max_artifacts_per_group` (RULE 99.ARTIFACT_SPACE) | **16777216** (`000000`..`FFFFFF`) |
| Federation nodes (FF, 6 hex) | `max_federation_nodes` (RULE 99.FEDERATION_SPACE) | **16777214** (`000001`..`FFFFFE`; reserved `000000`, `FFFFFF`) |
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

---

## Limit: Actor Color Range (100-Slot HEX) -- RULE 99

**Rule id:** `RULE 99.ACTOR_COLOR_RANGE`  
**Enforcer:** Lilith (OS `actor_id` 2 / Catalog **02**)  
**Guide:** [HOW_TO_LUPOPEDIA_A_SONG.md](../../HOW_TO_LUPOPEDIA_A_SONG.md)  
**Lilith audit rule:** `.lilith/rules/rule-99-actor-color-range.md`

### Actor <-> Catalog alignment (LOCKED)

| Concept | Meaning | Rule |
|---------|---------|------|
| **Catalog Actor Number N** | Song-catalog identity for HEX color ownership | **N MUST equal** OS `lupo_actors.actor_id` for that Actor |
| **OS `lupo_actors.actor_id`** | Runtime orchestration / agent identity | Same integer as Catalog N -- **no mismatch** |

Usable Catalog / Actor indices per Node: **0 .. 143999** (exactly **144000** Actors).  
Anything at or beyond Catalog **144000** is outside the usable chromatic assignment space (reserved zone begins at that color offset).

Lilith (Catalog **02**) **enforces** alignment and the practical ceiling. No Actor may claim past the reserved zone.

### Hard limit

| Property | Value |
|----------|-------|
| Maximum catalog Actors | **144000** indices `0..143999` (not 167772; not full 24-bit occupancy) |
| Colors per Actor | **Exactly 100** |
| Full 24-bit RGB space | **16777216** (`000000`..`FFFFFF`) |
| Colors used at cap | **144000 x 100 = 14400000** (~14.4M practical assignment limit) |
| Reserved buffer | **16777216 - 14400000 = 2377216** colors |
| Global ceiling | **FFFFFF** |

**Forbidden:** Treating **167772** (floor of 16777216/100) as the Actor ceiling. Cap is **144000** usable Actors. Reserved buffer is intentional stability. Claiming Catalog N beyond **143999** is forbidden.

### Range mathematics (normative -- 0-based N)

```text
start_int(N) = N * 100
end_int(N)   = start_int(N) + 0x63    # inclusive; 100 values
start_hex(N) = six-digit uppercase HEX of start_int(N)
end_hex(N)   = six-digit uppercase HEX of end_int(N)
```

Inclusive span `[start_hex] -> [start_hex + 0x63]` equals 100 HEX values.  
**N** is the Catalog / Actor number (`0..143999`).

HEX counting examples inside a range (offsets from that Actor's start):

| Offset HEX | Song index in range (0-based) |
|------------|-------------------------------|
| `...009` | 9 |
| `...00A` | 10 |
| `...00F` | 15 |
| `...010` | 16 |
| `...063` | 99 (last slot) |

### Reference Actors

| Catalog / Actor N | Identity | Range start | Range end |
|-------------------|----------|-------------|-----------|
| 0 | System | `000000` | `000063` |
| 1 | Wolfie | `000064` | `0000C7` |
| 2 | Lilith | `0000C8` | `00012B` |
| 3 .. 143998 | intervening | `N*100` | `(N*100)+0x63` |
| 143999 | Last usable catalog slot | `DBB99C` | `DBB9FF` |

**Verified:** `143999 * 100 = 14399900 = 0xDBB99C`; `0xDBB99C + 0x63 = 0xDBB9FF`.  
**Verified:** Wolfie N=1 -> `100 = 0x64` .. `0xC7`. Lilith N=2 -> `200 = 0xC8` .. `0x12B`.

**SUPERSEDED (reject):** Wolfie owning `000000`->`000063`; Lilith owning catalog **#144000** / final band; formula `(N-1)*100` with N in `1..144000`; Catalog Number != OS `actor_id`.

**MATH CORRECTION (still):** Draft prose that claimed `DB9E9C` / `DB9FFF` / reserved `DBA000` was arithmetic error. Last usable band remains **`DBB99C` -> `DBB9FF`**, reserved **`DBBA00` -> `FFFFFF`**.

### Reserved zone

| Zone | HEX |
|------|-----|
| Last catalog color | `DBB9FF` (end of Actor **143999**) |
| Reserved start | `DBBA00` (`144000 * 100`) |
| Reserved end / global ceiling | `FFFFFF` |

Actors MUST NOT allocate song colors in the reserved zone. No Actor may claim Catalog N past the usable set (`0..143999`).

### Artifact identity (unified with Header 4.2.4 -- songs are NOT a special class)

All artifacts use the same LUP (Linked Universal Protocol) object identity (PRD 16_C section 4.2.5):

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

**RRRRRR is the artifact identity block, not color.** **NN replaces GG.** **AA is first-class.** Rule 99 **bands** are unchanged and apply only to metadata `color_hex`. Six-digit `actor_hex` remains metadata.

**Federation Compression Rule (Option A):** Federation `000001` is the canonical root node. In short-form identities, it is compressed to the symbol `X`. Machine storage remains six-hex `000001`. Validators expand `X` -> `000001`. Only `000001` compresses.

| Token | Key | Role |
|-------|-----|------|
| FFFFFF | `federation_id` | 6-digit Node (machine). Human root = `X`. Only mutable identity field. Range `000001`..`FFFFFE`. |
| RRRRRR | `artifact_hex` | Artifact identity block. **Not color.** Native 6 hex, or `originFed:artifactNumber` after cross-federation modification. Colon `:` only. |
| NN | `namespace_id` | Catalog namespace. Replaces GG. Range `01`..`FF`. |
| II | `iteration` | Remix / cover / declared revision. |
| LL | `language` | ISO 639-1, or reserved `ZZ` (multi-language; not ISO). |
| AA | `actor_aa` | Catalog actor token `00`..`FF`. Maps to dense `actor_id`. |

Do **not** treat RRRRRR as `actor_hex`. Do **not** treat RRRRRR as Rule 99 color.

#### Artifact Number Space (RRRRRR)

RRRRRR is a six-digit hex artifact number when native. After a **modification** in another federation, RRRRRR is `originFederation:artifactNumber`. Colon `:` is the only lineage delimiter. No colon means native to the current federation. Split on the first colon. Left = origin federation. Right = artifact number.

| Property | Value |
|----------|-------|
| Range | `000000` -> `FFFFFF` |
| Width | 24-bit |
| Total artifacts per namespace (NN) | **16777216** |

This is the maximum number of artifacts that can exist inside a single catalog namespace.

This limit is intentional and required for:

- deterministic identity
- collision-free catalogs
- stable federation migration
- fixed-width identity parsing
- compatibility with lineage graphs
- compatibility with semantic OS indexing

`artifact_hex` MAY reuse a hex value that also exists in the Rule 99 color universe. Same digits, different meaning. Validators MUST NOT reject an artifact number because it looks like a color or like an `actor_id`.

#### Actor identity (AA) vs actor metadata

- **AA** (`actor_aa`) is first-class identity: two hex digits `00`..`FF`.
- Dense `actor_id` MUST map to AA via the catalog registry for that NN.
- Six-digit `actor_hex` remains **metadata** (optional display of full `actor_id`).
- `actor_id` does **not** determine RRRRRR.
- Wolfie may mint millions of artifacts. They MUST NOT all end in `000001`.

Actor provenance lives in AA. Artifact identity lives in RRRRRR. Color lives in metadata `color_hex`.

#### Color is metadata (songs); bands unchanged

- Songs use `color_hex`. Color is **not** a LUP token.
- Color MUST sit inside the catalog owner's Rule 99 band: `start = owner_actor_id * 100` through `start + 0x63`.
- Federation migration does not rewrite `color_hex`.

```text
machine:  LUP:000001-000000-01-00-EN-01
human:    LUP:X-000000-01-00-EN-01
short:    LUP:X-000000-01
```

| Piece | Example (Wolfie first song) | Role |
|-------|-----------------------------|------|
| `lupopedia_id` | `LUP:000001-000000-01-00-EN-01` | Universal identity (machine) |
| human form | `LUP:X-000000-01-00-EN-01` | Same artifact; FF compressed |
| `artifact_hex` | `000000` | Artifact identity block |
| `actor_aa` | `01` | Identity token; maps to `actor_id` 1 |
| `actor_id` | `1` | Dense metadata; MUST map to AA |
| `color_hex` | `000064` | Metadata; Wolfie first Rule 99 slot |

Legacy compact display `01EN000064` MAY be stored as a **filename or label** derived from catalog + language + iteration + `color_hex`. It is **not** a second identity grammar.

`media_kind: song` is forbidden when the catalog owner `actor_id` > 143999.

#### Namespace Number (NN) owns the catalog block

NN replaces GG. NN is a catalog namespace block inside a federation node.

- NN range: `01`..`FF`. Reserved: `00`.
- NN determines the artifact numbering space (`000000`..`FFFFFF` per namespace).
- AA (not NN) is the actor token.
- NN is not required to equal `actor_id` or AA.

Initial namespace map:

| NN | Namespace |
|----|-----------|
| `01` | Wolfie catalog block |
| `02` | Lilith catalog block |
| `03` | AGAPE catalog block |
| `04` | SYSTEM catalog block |

#### Remix / cover (II only)

Remix does not mint a new artifact number. Remix increments II only.

- II increments (`00` -> `01`)
- RRRRRR stays the same
- NN stays the same
- AA stays the same
- LL stays the same
- FF stays the same (unless the work is also federated)
- `color_hex` stays the same
- Edge: `remix_of` -> prior `lupopedia_id`

#### Federation (FF only when unmodified)

Unmodified federation publish rewrites FF only.

- Only FFFFFF changes (`LUP:000001-000000-01-00-EN-01` -> `LUP:000003-000000-01-00-EN-01`)
- RRRRRR stays the same (no colon added)
- NN stays the same
- AA stays the same
- LL stays the same
- II stays the same
- `color_hex` stays the same
- Edge: `federated_from` -> prior `lupopedia_id`
- Missing FF means Node **`000001`**. `FF=000000` and `FF=FFFFFF` are reserved / forbidden.

Cross-federation **modification** (iterate / remix on another node) encodes origin in RRRRRR:

```text
LUP:000002-123456-01-00-EN-01
->
LUP:000003-000002:123456-01-00-EN-01
->
LUP:000005-000003:123456-01-01-EN-01
```

Left of `:` is the immediate previous federation. Right is the artifact number. Reject any delimiter other than `:`.

#### Federation Space Expansion (Option C -- header 4.2.2; reserved ends in 4.2.3)

| Era | FF width | Node ceiling |
|-----|----------|--------------|
| 4.2.1 and older | 2 hex | 256 nodes (`01`..`FF`; `00` forbidden) |
| 4.2.2 | 6 hex | 16777216 nodes (`000001`..`FFFFFF`; `000000` forbidden) |
| 4.2.3 | 6 hex | **16777214** usable (`000001`..`FFFFFE`); reserved `000000` and `FFFFFF` |

Required for internet-scale indexing. Existing 2-digit FF values map to 6-digit equivalents by zero-padding.

```text
Old: FF = 01
New: FF = 000001
```

```text
Old: LUP:01-01-EN-00-000000
New: LUP:000001-000000-01-00-EN-01
```

Federation migration rewrites only the 6-digit FFFFFF token. NN, AA, LL, II, and RRRRRR stay byte-identical.

#### Translation (LL only)

Translation changes LL only, with a required `translation_of` edge.

- Recommended: same II, same NN, same AA, same RRRRRR, same FF
- LL changes under translation policy only

#### Artifact Exhaustion Policy

When a group reaches `RRRRRR = FFFFFF`, the catalog is full.

- A new `namespace_id` (NN) must be allocated.
- `actor_id` does not change.
- Federation does not change.
- Identity continues in the new namespace (new NN, RRRRRR restarts at `000000`).
- Color bands stay on the same owner actor. Exhaustion of artifact numbers is not exhaustion of song colors.

Artifact exhaustion is resolved by allocating a new namespace_id (NN).

Do not mint a new actor. Do not widen RRRRRR past six hex digits. Do not reuse a spent `artifact_hex` inside the full group.

### Rules Lilith MUST enforce

1. Catalog Actor Number **MUST equal** OS `actor_id` for that Actor (no mismatch).
2. Every catalog Actor gets exactly **100** colors.
3. Actors may only choose colors **inside** their assigned range.
4. Actors may not choose colors **outside** their range.
5. Actors may not **collide** with another Actor's range.
6. New **base** songs use the next available HEX color inside the Actor's range.
7. Manual color selection is allowed only if unclaimed **and** inside the Actor's range.
8. **Remixes** and **covers** inherit the original base color and **increment iteration**.
9. Free users get **one** catalog Actor (100 songs).
10. Paid users may create **more** catalog Actors (100 songs each).
11. Global registry ends at **FFFFFF**.
12. Maximum usable catalog Actors: **144000** (`0..143999`).
13. Reserved zone: **DBBA00** to **FFFFFF**.
14. Remove / refuse old doctrine that used **256**, **0x100**, or **167772** Actors as the song-range ceiling.
15. Reject claims that Wolfie owns the start band (`000000`->`000063`) or that Lilith owns Catalog **144000**.
16. RRRRRR is `artifact_hex` only. Reject any claim that RRRRRR is `actor_hex` or Rule 99 color.
17. When a namespace reaches `FFFFFF`, allocate a new NN. Do not mint a new actor. Do not widen RRRRRR.

### Constitutional block (copy/paste)

```text
## Actor Color Range Rule (100-Slot HEX -- Rule 99)

Every Actor in Lupopedia receives a unique HEX color range containing exactly 100 values.
A 100-color HEX range is defined as: [start_hex] -> [start_hex + 0x63].

Catalog Actor Number N MUST equal OS actor_id (Actor <-> Catalog alignment).
Range formula (0-based): start_int(N) = N * 100; end_int(N) = start_int(N) + 0x63.
Usable N: 0 .. 143999 (144000 Actors). Practical color cap: 14400000.

System  = Actor / Catalog 0  (000000 -> 000063)
Wolfie  = Actor / Catalog 1  (000064 -> 0000C7)
Lilith  = Actor / Catalog 2  (0000C8 -> 00012B)  -- also enforces Rule 99
Last    = Actor / Catalog 143999 (DBB99C -> DBB9FF)

Actors may only choose colors inside their assigned range.
Actors may not choose colors outside their range.
Actors may not collide with another Actor's range.
Actors may not claim past the reserved zone (DBBA00 -> FFFFFF).

New base songs use the next available HEX color.
Manual color selection is allowed only if the HEX value is unclaimed and inside the Actor's range.
Remixes and covers inherit the original base color and increment the iteration number.

Free users receive one Actor (100 songs).
Paid users may create additional Actors (100 songs each).

Global registry ends at FFFFFF.
Maximum usable Actors: 144000.
Reserved zone: DBBA00 to FFFFFF.
```

### Quick reference

- Actor range: exactly 100 colors per catalog Actor
- HEX range: `[start_hex] -> [start_hex + 0x63]` with `start = N * 100`
- Alignment: Catalog N = OS `actor_id`
- System: Catalog **0** -- `000000 -> 000063`
- Wolfie: Catalog **1** -- `000064 -> 0000C7`
- Lilith: Catalog **2** -- `0000C8 -> 00012B` (enforcer)
- Last usable: Catalog **143999** -- `DBB99C -> DBB9FF`
- Max Actors: 144000 (`0..143999`)
- Reserved: `DBBA00 -> FFFFFF`
- Free: one Actor = 100 songs
- Paid: more Actors = more songs
- Global ceiling: `FFFFFF`
- Enforcer: Lilith (OS / Catalog 2)

---

## Limit: Federation Model -- RULE 99.FEDERATION

**Rule id:** `RULE 99.FEDERATION`  
**Companions:** `RULE 99.ACTOR_COLOR_RANGE`, `RULE 99.SONG_ID_FORMAT`, `RULE 99.NODE_LOOKUP`  
**Folder:** `docs/prd/federation/`  
**Related:** [PRD 34 Federation Node Semantic Network](34_A-i_FEDERATION_NODE_SEMANTIC_NETWORK.md)

### 1. Federation Node concept

- Lupopedia is **Federation Node 0**.
- Any decentralized installation of Lupopedia Semantic OS becomes **Federation Node 1, 2, 3, ...**.
- Federation Nodes are independent but follow the same constitutional rules.
- Federation Nodes may host their own **144000**-Actor catalog.
- Federation Nodes may have their own color ranges, their own creators, and their own IDs.
- **Cross-Node color collisions are impossible by design:** each Node has its own full 24-bit HEX universe; collision rules apply **inside** a Node only.

### 2. Federation ID in LUP (Linked Universal Protocol) IDs

- If an artifact **does not** include a Federation ID, it belongs to Node **`000001`** (legacy Node 0 / lupopedia.com).
- If an artifact **does** include a Federation ID, lookup MUST:
  - Resolve the Federation ID at **Node 0** (lupopedia.com federation directory).
  - Obtain the domain that hosts that Node.
  - Look up the song at that Node's domain catalog.
- See `RULE 99.SONG_ID_FORMAT` and `RULE 99.NODE_LOOKUP`.

### 3. Why federation exists

- Decentralization.
- Scalability beyond Node 0.
- Local sovereignty for creators.
- Each Node can host its own 144000 Actors.
- Each Node can define its own catalog identity.
- Lupopedia.com (Node 0) remains the **root registry** / directory (DNS-like for catalogs).

### 4. Actor limits per Node

- Each Federation Node supports **exactly 144000** catalog Actors (hard ceiling).
- This limit is constitutional and MUST be enforced in PRD files and Lilith audits.
- No Node may exceed 144000 catalog Actors.
- No Node may exceed **100** colors per Actor.
- No Node may exceed the global HEX ceiling `FFFFFF` **within that Node's color universe**.

### 5. Song lookup rules (`RULE 99.NODE_LOOKUP`)

- If Federation ID is **missing**, lookup happens at **Node 0**.
- If Federation ID is **present**, lookup path is:
  - Node 0 -> federation directory -> Node domain -> song catalog.
- Node 0 acts as the **directory / DNS** for all decentralized catalogs.

### 6. Federation Node ID rules

- Node 0 = lupopedia.com (root).
- Node 1+ = decentralized installs.
- Node ID MUST be included in the LUP (Linked Universal Protocol) ID if the artifact is not from Node `000001`.
- Node ID determines where the song catalog is hosted.
- Node ID determines which catalog Actor ranges apply (that Node's universe).
- Node ID does not waive PRD 00 / PRD 99 constitutional math.

### 7. Federation and color ranges (per Node)

- Each Node has its own **16777216**-color universe (`000000` .. `FFFFFF`).
- Each Node allocates **144000 x 100 = 14400000** colors for catalog Actors.
- Each Node reserves **2377216** colors for system use.
- Reserved zone begins at `DBBA00` and ends at `FFFFFF`.
- These numbers MUST be identical across all Nodes (no local redefinition of width or ceiling).
- Still forbidden: **167772** Actors, **256**-wide bands, **0x100** stride.

### 8. Integration notes (IDE / agents)

- Music ID system lives under **`/music`** (install-relative; use path doctrine, never hardcode host folder names).
- Federation companion logic lives under **`docs/prd/federation/`**.
- Agents MUST NOT assume Node 0 is the only catalog.
- Agents MUST treat Catalog Actor Number as equal to OS `actor_id` (Actor <-> Catalog alignment).
- Agents MUST treat song object identity as `lupopedia_id` (PRD 16_C 4.2.4, `LUP:FFFFFF-RRRRRR-NN-II-LL-AA`). Human root FF may display as `X` for `000001`. Color is metadata. Rule 99 bands unchanged.
- Agents MUST enforce 100-slot HEX ranges only (`start = N * 100`) on `color_hex`.
- Agents MUST enforce 144000 catalog Actor limit **per Node** (`N` in `0..143999`).
- Agents MUST map dense `federation_node_id` 0 to FF `01`.

### 9. What Lilith MUST enforce (federation addendum)

- Federation ID correctness.
- Node lookup correctness.
- Actor range correctness (per Node).
- HEX range correctness (per Node).
- Reserved zone correctness (`DBBA00` .. `FFFFFF`).
- Global ceiling correctness (`FFFFFF` per Node universe).
- No **intra-Node** color collisions.
- No Node exceeding 144000 catalog Actors.
- No Actor exceeding 100 colors.
- No non-Node-0 song published without a proper Federation / Node ID field.

### 10. Constitutional statement

- Decentralized, federated, color-based music registry.
- Node 0 = root authority and federation directory.
- Node 1+ = sovereign creative universes.
- Each Node: 144000 Actors, 100 HEX colors each, own catalog identity.
- Lupopedia Node 0 = global lookup system for Node domains.

**Bullet companions:** `docs/prd/federation/rule_99_federation.md`, `rule_99_actor_color_range.md`, `rule_99_song_id_format.md`, `rule_99_node_lookup.md`.
