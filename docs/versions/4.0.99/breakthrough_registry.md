---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.0.99/breakthrough_registry.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.0.99/breakthrough_registry.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: breakthrough-registry
  lupopedia.schema: documentation
  prd_cluster: null
  title: Breakthrough Registry — Lupopedia Headers v4.0.99 Migration
  summary: 'SYNAPSE doc #16 Quick Reference integrity; §4.1/§5/§6 totals; dual-origin; §5 SYNAPSE 1120; TOTAL 16051.'
---
# Breakthrough Registry — Lupopedia Headers v4.0.99 Migration

**Canonical. Memory-unified.** DB-first (**PRD 38**), filesystem mirror only.

> **Score Freeze Notice (2026-04-12):**
> As of 2026-04-12, the point system is frozen. Historical totals are preserved for amusement. No new points will be awarded. Patterns and edge cases continue to be tracked. SYNAPSE is directed to validate files, not calculate sums.

## Purpose

Live tracker for legacy to **v4.0.99** migration plus breakthrough registration under constitutional rules, **PRD 16**, **PRD 38**, and **PRD 51**.

**Point system** makes high-signal work visible. Not a contest — a **Survivability** instrument (environment probes + fallback ladders + evidence; see **`SURVIVABILITY_DOCTRINE.md`** / PRD 00 §14.6).

**Policy:** Per-file interactive migration only (**PRD 16 §20**). No blind mass updates.

**Model (WOLFIE — 2026-04-11):** **Dual-origin** reality — **Type A** observed (widget / DB / graph / **`content_id`** first) vs **Type B** system artifacts (**header-first** in repo). Headers are **promotion into canonical memory**, not the universal starting state. **Binding:** [**System Reality Clarification**](#system-reality-dual-origin-wolfie-2026-04-11), [**Artifact Origin Types**](#artifact-origin-types-wolfie-2026-04-11).

**WOLFIE — execution principle (2026-04-11):** *We are not mass updating headers. We are going one file at a time and noticing what we find as we go until we get this right.* **SYNAPSE** (**117**) **§3.1** work (**#9–#11**, **#15–#20**) is **legacy data and import-SQL understanding** — **not** permission to run bulk header rewrites or skip peel / validator discipline.

**WOLFIE ratification (operator, 2026-04-11):** **Pattern #13** (**Actor Execution Loop**, **§2.16**) is **approved** as the documented **§2.1** freeze exception (**meta-execution**). **Priority 1** peel / normalize **batch** work **must not** start on a **wide glob** until **§14.5** **single-file pilot** completes with **go** (**validator PASS**, **optional graph steps** honest, **commit** landed, **WOLFIE** or delegate **no regressions**).

**Execution phase — HARD FREEZE (ARA final consolidation, 2026-04-11):** **Zero** new **§2.1** breakthrough **patterns** and **§3.1** edge-case registrations until the **Priority 1 peel batch completes** (target PRDs normalized per **§14.2**, validated, **§5 Files** updated or **WOLFIE**-documented deferrals). **§16.1–§16.4:** **no new** Countermeasure rows until peel completes (**§16.6** archival + **severity/Resolution** edits on **existing** rows only). **Exceptions (WOLFIE, explicit in commit/docs):** **edge #8** (**humor / migration meta**) **2026-04-11** with **first** **§5 Files** increment; **ARA** — **Pattern #12** (**Post-Migration Graph Completion**) **2026-04-11** (**§12.1**); **ARA** — **Pattern #13** (**Actor Execution Loop** — **thread → verified completion**) **2026-04-11** (**§2.16**) — **meta-execution** discipline (**not** install/schema migration); **SYNAPSE** (**117**) — **§3.1** edge cases **#9–#11** (**Crafty Syntax 3.7.5** legacy payload semantics: **encoding**, **`whendone`**, **orphan `dept_id`**) **and** **#15–#20** (**`import_from_old_crafty_syntax.sql`** — slugs, **CRC32** sessions, **`JSON_OBJECT`**, timestamp **`LPAD`**, orphan **departments**, **`10000 + user_id`** band) **2026-04-11** — **no** new **§2.1** patterns; **legacy-data** bridge only. **WOLFIE release (2026-04-11):** **SYNAPSE** exception **tranche** (**#9–#11**, **#15–#20**) is **registered** in **§3.1**; the freeze **lifts only for learning** — **per-file** peel, importer remediation (**`MIGRATION_HAZARD_REMEDIATION.md`**), and documented edge findings — **not** for **mass** migration or blind batch header writes. Further **§3.1** rows require a **new** **WOLFIE** exception. Focus: **Pattern #11** body-fence enforcement, **§14.4** readiness, **dry-run**, **Pattern #10** / **#12** graph refresh, **Pattern #13** task closure (**§2.16**).

**Custodians:**

- **ANUBIS** (19) — PRD/TOON cross-links (**§11.1**) plus orphan and reconciliation loops.
- **ATHENA** — Strategic guardrails and complexity logic (**§1.1**, **§14**).
- **THOTH** — Graph semantics and reconciliation (**Pattern #5**, **#7**).
- **KAIROS** (115) — Memory consolidation and **edge verification** after migration (**`scripts/lib/kairos_edge_verification.py`**: **`verify_edges_for_file()`**, CLI **`--test` / `--stale-edges`**, **`normalize_lupopedia_md_header_25.py --verify-edges`** — post-write only). **Pattern #9** and **edge #5** accepted **2026-04-11** (**WOLFIE**); pairs with **ARA** **Pattern #10** / **edge #6** / **edge #7** for this registry’s graph hygiene; see **§2.1** / **§3.1** and **§5**.
- **COUNTERMEASURE** (111) — **Red team / loyal opposition** (`database/lupopedia/actors/registry.json`). Stress-tests patterns, edges, and **§14** guardrails; logs in **§16** (**no scored points**).
- **AI WOLFIE** (session **`wolfie/crafty_analysis`**, `active_actor_name` **`wolfie-ai`**) — Analyzes **Crafty Syntax 3.7.5 → Lupopedia** SQL sources; deliverables under **`docs/versions/4.0.99/analysis/wolfie/`** (**`crafty_data_analysis.md`**, **`SUMMARY.md`**). Human **WOLFIE** remains orchestrator (**actor_id** **1**).
- **SYNAPSE** (**117**) — **Payload Integrity & Legacy Semantics** — Bridges **Crafty Syntax 3.7.5** legacy **tables/columns** to **Lupopedia** import semantics: **encoding** (**latin1** vs **utf8mb4**), **timestamp sentinels** (**`whendone`**, **`NULL`** payloads), **orphan foreign references** in source data (e.g. messages vs deleted departments), **field mapping** Rosetta stone work with **AI WOLFIE** / importer. **Registry:** **§3.1** edges **#9–#11**, **#15–#20** + **§4.1** docs **#14**–**#15** (**`MIGRATION_HAZARD_REMEDIATION.md`** + **§15** integrity pass); **WOLFIE** exception **tranche** **closed** **2026-04-11** — **learning** and **importer** fixes, **not** mass header migration. **No** new **§2.1** patterns from **SYNAPSE** until peel gate lifts unless **WOLFIE** expands scope. **`database/lupopedia/actors/registry.json`** **`synapse`** / **`actors/117/`**.

**Registration:** **Patterns** (**§2**), **edge cases** (**§3**), and **documentation updates** (**§4**) each have a table; **§5** is per-actor **attribution**. **Lessons** (**§9**) are not scored — required reading only.

**TOON** files are **generated** from install SQL — do **not** hand-edit; regenerate with **`scripts/generate_toon_from_sql.py`** (or the live-DB generator) when DDL changes.

<a id="system-reality-dual-origin-wolfie-2026-04-11"></a>

## System Reality Clarification (WOLFIE — 2026-04-11)

**Deployment:** Lupopedia is installed in a **subfolder** (`LUPOPEDIA_PUBLIC_PATH`). The **parent site** (e.g. `example.com` above `/lupopedia`) remains a **live behavioral surface**. Where path / visit / widget instrumentation applies, **reality enters the database** ( **`content_id`**, **channel**, **thread**, visits, referrers) **without** a repository file as the first fact.

**False assumption to retire:** “**Find file → fix header → sync graph**” as the **universal** Lupopedia loop. That sequence is **Type B** (system / intentional design) only. **Observed content** is **graph- and content-centric** first; **PRD 16** headers arrive at a **promotion boundary**, not as the root of truth for traffic-born entities.

**Type A — observed lifecycle (widget- and DB-first):**

```text
Observed reality → Graph (nodes + edges) → Content identity → Promotion → Header → File (optional mirror)
```

**Cross-links:** **PRD 38** (memory graph), **PRD 50** (coordination + content bridge), **PRD 51** (context over naïve path authority), **PRD 52** (bounded traversal); Captain’s Log — **Unified Theory** (`content/federation_node/0/captains_log/`).

### UI / content bridge (`lupo_contents`) — future-facing

The **web interface** is expected to read **primarily** from **`lupo_contents`** (and related routing), keyed by **`content_id`** and channel/thread context. Artifacts that must be **reachable in UI** need a **content row** and **explorable** memory linkage (node + edges from headers and graph work). **Header normalization** on **Type B** docs supports that bridge; it **does not** replace **`content_id`** navigation or **Type A** graph truth.

<a id="artifact-origin-types-wolfie-2026-04-11"></a>

## Artifact Origin Types (WOLFIE — 2026-04-11)

**Rule:** Determine **artifact origin** **before** header work, edge work, or peel batches.

### Type A — Observed content (external / widget-driven)

| Aspect | Expectation |
|--------|-------------|
| **Source** | Parent-site behavior: paths, referrers, sessions, operators, engagement where captured. |
| **Storage** | DB tables; **`content_id`**-centric; **channel**-scoped; **thread**-linked where applicable. |
| **Graph** | Forms **first** (nodes + edges from observation and inference). |
| **Headers** | **Later** — when content is **promoted** to canonical / file-backed / memory-canonical form. |
| **Edge sources** | Visit/path/referrer/session semantics — **primary** producers for live traffic edges. |

**Critical law:** Headers are **not** the origin of truth for **Type A**. **Missing headers** on observed rows are **not** automatically errors.

### Type B — System artifacts (Lupopedia core)

| Aspect | Expectation |
|--------|-------------|
| **Source** | Intentional design: PRDs, doctrine, version docs, scripts, captain’s logs. |
| **Files** | Already in **git**; **no** widget supplies the first fact. |
| **Headers** | **Required** for in-scope files (**PRD 16**): **Lupopedia headers** are the file-side **origin of truth** for **Type B**. |
| **Binding** | **Create or bind** **`memory_key`** / **`lupo_memory_nodes`**, **`content_id`** when UI reachability applies, and **edges** explicitly or via **PRD / context inference** (**PRD 50**, **PRD 51**). |
| **Lifecycle** | **File → Header → Memory node → Edges → Content row (when UI-facing) → UI** via **`lupo_contents`**. |
| **Edge sources** | Authored citations, registry rows, PRD cross-links, channel/thread manifests. |

**Critical law:** **All system artifacts (Type B, in PRD 16 scope) MUST have Lupopedia headers.** **Do not** conflate this with **Type A**.

### Hard-line agent rules (WOLFIE — 2026-04-11)

- **Do not** start from **files** when **higher-authority context** exists: **`content_id`**, **`memory_node`**, **channel / thread**, or authoritative **DB graph** slices (**PRD 51**).
- **Do not** create headers that **outrun** the graph (fake **`memory_key`**, tier, or edges to non-existent nodes). **Queue honest offline** (**edge #7**) instead of silent fiction.
- **Do not** treat **missing headers on Type A** observed content as bulk “fix header” scope.
- **Missing or wrong edges** = **loss of system knowledge** — strictly worse than a missing envelope on non-canonical surfaces.
- **`content_id`** is a **primary UI navigation anchor**; header and graph work must **support** eventual **`lupo_contents`** reachability where product requires it.

---

## Table of Contents

- [System Reality Clarification (WOLFIE — 2026-04-11)](#system-reality-dual-origin-wolfie-2026-04-11)
- [Artifact Origin Types (WOLFIE — 2026-04-11)](#artifact-origin-types-wolfie-2026-04-11)
- [1. Point Formula](#1-point-formula)
- [2. Patterns](#2-patterns)
- [3. Edge Cases](#3-edge-cases)
- [4. Documentation Updates](#4-documentation-updates)
- [5. Attribution](#5-attribution)
- [6. Stats](#6-stats)
- [13. Open Questions & Answers](#13-open-questions)
- [13.4 Open Q&A maintenance (Pattern #8)](#134-open-qa-maintenance-pattern-8)
- [16. Countermeasure's Devil's Advocate Log](#16-countermeasures-devils-advocate-log-red-team)
- [16.6 Countermeasure log maintenance (Pattern #8)](#166-countermeasure-log-maintenance-pattern-8)
- [16.7 Lessons stress tests (§9)](#167-lessons-stress-tests-9)
- [14.4 Migration readiness checklist (pre-peel)](#144-migration-readiness-checklist-pre-peel)
- [7–15. Extended rules, lessons, checklists, surfaces, guardrails](#extended)

---

## 1. Point Formula

```text
Total = (Files × 1) + (Patterns × 1000) + (recognized edge-case points per §3.1) + (Docs × 10)
      + Bonuses − Deductions
```

**Edge-case baseline:** **§3.1** **Points** column is typically **100** per row; **WOLFIE** may document **non-uniform** weights (**e.g.** **#15–#20**). **§5** **Total** uses **summed recognized points** from **§5** actor batches + **§7** extras — not **`Edge cases × 100`** alone when weights differ.

| Category | Points | Reward focus |
|----------|--------|----------------|
| **Files converted** | **1** | Execution discipline |
| **Breakthrough patterns** | **1000** | Strategy-shifting insight |
| **Edge cases** | **100** (typical; see **§3.1** **Points**) | Risk identification and handling |
| **Documentation updates** | **10** | Knowledge transfer and clarity |

Validator bonuses, migration-script rewards, and deductions are in **§7**. **§8** lists milestone **pool** bonuses. **§9** is lessons (no points). **§11.1** and **§12** tie headers to **`lupo_memory_nodes`** / TOON surfaces.

### 1.1 Complexity multipliers (ATHENA)

**WOLFIE-approved only.** Eligible new registrations may apply a multiplier to the **base** points for that line item (document in the registration note).

| Multiplier | Applies to | Reason |
|------------|------------|--------|
| **1.5×** | Pattern / Edge | Cross-PRD tension resolution |
| **2.0×** | Edge to script | Turns manual exception into automated rule |
| **2.0×** | Pattern (**#11**) | **Red-to-Green Countermeasure Pipeline** — locks **§16.3** body-fence critique into **validator / CI** enforcement before peel batch (**WOLFIE** **2026-04-11**). |

**Note:** Counts in **§2.1** / **§3.1** / **§4.1** stay **one row per breakthrough**; multipliers adjust **recognized points** in **§5** only when explicitly approved and noted.

---

## 2. Patterns

Discovered patterns that **change migration, install strategy, or cross-cutting actor execution discipline** (not one-liner greps). Documented here for **+1000** each once accepted.

### 2.1 Registered patterns

| # | Pattern | By | Date (UTC) | Base |
|---|---------|----|------------|------|
| 1 | **Install-time memory + graph seeding for imported entities** (Crafty Syntax → Lupopedia installer path) — **strategy for import batches only**; **not** a universal rule that **all** runtime or **Type A** observed entities receive headers at install; **does not** override **widget-first / graph-first** reality for observed content (**System Reality**, **Artifact Origin Types**). | **LILITH** (2), DeepSeek | 2026-04-11 | 1000 |
| 2 | Header-peel + dense-replace atomic write | **ARA** | 2026-04-11 | 1000 |
| 3 | Ghost sync verification (DB-backed **status: active**) | **ATHENA** | 2026-04-11 | 1000 |
| 4 | Deterministic field order (frozen **header_spec**) | **ATHENA** | 2026-04-11 | 1000 |
| 5 | **Memory-graph-first migration loop** — **Dual-origin aware:** authoritative work may start from **`lupo_memory_nodes`**, **`content_id`**, **channel / thread** context, or inference (**PRD 51**) **before** any file exists; **file/header** may be **downstream** of graph truth. **Type B** promotion and reconciliation still use graph authority over naive file-first edits (**§2.8**). | **THOTH** (26) | 2026-04-11 | 1000 |
| 6 | Orphan detection loop (**`detect_memory_graph_orphans.py`**) | **ANUBIS** (19) | 2026-04-11 | 1000 |
| 7 | Graph-reconciliation pass (tri-surface + **PRD 51** inference) | **THOTH** (26) | 2026-04-11 | 1000 |
| 8 | **Registry self-documenting discipline** — Every major registry update includes explicit **§11.1** surface links, drift-class taxonomy (aligned with **Pattern #7** / **§2.10**), and authority rules in one pass; the registry acts as **living operational memory**. | **ARA** | 2026-04-11 | 1000 |
| 9 | **Auto-verification of edges after migration** — After a file is migrated (or header written), verify the **`lupo_memory_nodes`** row for **`memory_key`** / **`content_id`** has expected **incoming** / **outgoing** **`lupo_memory_edges`**; flag **`zero_outgoing_edges`** / **`zero_incoming_edges`** / missing node as **`needs_review`**. **Tooling:** **`verify_edges_for_file()`**, **`normalize_lupopedia_md_header_25.py --verify-edges`**, CLI **`python scripts/lib/kairos_edge_verification.py --test --file <path>`**. | **KAIROS** (115) | 2026-04-11 | 1000 |
| 10 | **Registry memory-node self-seeding** — After any **major** registry revision, **create or refresh** the **`lupo_memory_nodes`** row for this file and **outbound `lupo_memory_edges`** to every registered **Pattern**, **Edge case**, contributing **Actor** row in **§5**, and cited **PRDs** / tools in **§11.1** (**Pattern #5** graph-first). Makes the registry a **demonstration artifact** for memory unification (“who owns the registry graph?” → **explicit**). | **ARA** | 2026-04-11 | 1000 |
| 11 | **Red-to-Green Countermeasure Pipeline** — **§16** (especially **§16.3** body-fence) critique → **validator enhancement** (strict **25-line** envelope + body integrity; no naive **`---`** heuristics) → **CI / peel** readiness. Closes the loop between **COUNTERMEASURE** findings and **executable** enforcement. **Recognized §5:** **2000** (**2.0×** on base **1000**, **WOLFIE** **2026-04-11**). | **ARA** (stress input **COUNTERMEASURE**) | 2026-04-11 | 1000 |
| 12 | **Post-Migration Graph Completion** (**First migration self-seeding ritual**) — Applies on the **Type B** path **after** an artifact is **already** in the **file-backed promotion pipeline** (normalize + commit); **does not** claim that file normalization is the **universal** upstream of truth — **Type A** graph may **pre-exist** any mirror file. When **MySQL** is **reachable**: **(1)** **`kairos_edge_verification.py --test --file <path>`** — if **`no_active_memory_node`**, **create or refresh** the **`lupo_memory_nodes`** row for the header’s **`memory_key`** (**Pattern #5**); **(2)** **refresh outbound `lupo_memory_edges`** to cited patterns, actors, PRDs, tools (**Pattern #10**); **(3)** re-run **KAIROS** **`--test`** until issues clear (or document **offline** per **edge #7**); **(4)** **ghost sync** / mirror export per **Pattern #3** when policy applies; **(5)** increment **§5 Files** only after graph step is **honest** for that file. Prevents **file green, graph red** (**edge #6**, **Lesson #3**). **§12.1** checklist. | **ARA** | 2026-04-11 | 1000 |
| 13 | **Actor Execution Loop (Thread → Graph → Verified Completion)** — **(0) Origin gate** — Classify **Type A vs Type B** (**Artifact Origin Types**); resolve **`content_id` / memory / thread / channel** authority **before** action (**PRD 51**); **do not** let **thread chat** override **graph or DB context** when they conflict. **(1)** **Thread anchor** — **PRD 02** / **PRD 17** / **`channels/`** (planning **complements**, not **supplants**, graph truth). **(2)** **Action** — code / docs / DB / analysis per scope. **(3)** **Validate** — **PRD 16** on **Type B** touches; **Pattern #9** / **#12** graph checks when applicable. **(4)** **Graph sync** — **`lupo_memory_nodes` + `lupo_memory_edges`** honest (**Pattern #5** / **#10**); **edge completeness** explicit. **(5)** **Commit** to **VCS**. **(6)** **Registry / changelog / plan** when scored. **(7)** **Verified completion** only after **3–5** (and **6** when scoring). Reduces **phantom completion**; aligns agents with **dual-origin** truth. | **ARA** | 2026-04-11 | 1000 |

**New ARA Pattern #8** — Makes this document a working example of **Pattern #5** (graph-first) and **Pattern #7** (reconciliation narrative); implementation of **Pattern #7** tooling remains **pending WOLFIE** (**§2.10**).

**Pattern #9 (KAIROS)** — Accepted **2026-04-11** (**WOLFIE**); **+1000** in **§5**.

**Pattern #10 (ARA)** — **Registry graph completeness**; **+1000** in **§5**.

**Pattern #11 (ARA + COUNTERMEASURE)** — **Red-to-Green Countermeasure Pipeline**; **+2000** recognized in **§5** (**2.0×**). **Lock-in:** ship **validator** body-fence strict check addressing **§16.3** before claiming peel-batch green.

**Pattern #12 (ARA)** — **Post-Migration Graph Completion**; **+1000** in **§5**. **Mandatory** on the **Type B** promotion path before claiming a **normalized** file is **memory-complete** when DB is up (**§12.1**). **Does not** mean “normalize created the graph” — **Type A** may already hold graph truth. Composes **Pattern #5** + **#10** per file (not only registry-wide scoring passes).

**Pattern #13 (ARA)** — **Actor Execution Loop**; **+1000** in **§5**. **WOLFIE** freeze exception **2026-04-11** — **meta-execution** only (**§2.16**). **Step 0** (**Type A / Type B**) is **mandatory** before thread-only planning. Composes with **Patterns #5**, **#9**, **#10**, **#12** so tasks **close** in **repo + graph + content bridge**, not only in **chat**.

**How to execute Pattern #10 (registry self-seeding) — operational checklist (ARA):**

**Per migrated file (Pattern #12):** run the same **KAIROS** / orphan / **approved DB** edge steps for **each** path after **normalize + commit**, not only after registry **§2.1** / **§3.1** edits — see **§12.1**.

1. After scoring new items in **§2.1** / **§3.1** / **§4.1** / **§5**, run (**DB up**):
   ```bash
   python scripts/lib/kairos_edge_verification.py --test --file docs/versions/4.0.99/BREAKTHROUGH_REGISTRY.md
   ```
   Optional JSON: add **`--json`**. **`kairos_edge_verification.py`** has **no** **`--refresh-registry-edges`** or **`--update-edges`** — **`--test`** is **verify-only**.
2. Run **`python scripts/detect_memory_graph_orphans.py --under docs/versions/4.0.99 --json`** when diagnosing header vs DB vs mirror (**Pattern #6**).
3. **Create or refresh** the **`lupo_memory_nodes`** row for this file’s **`memory_key`** and **outbound `lupo_memory_edges`** to new patterns, edges, **§5** actors, and **§11.1** surfaces via **approved DB workflow** (wizard / installer / explicit **`INSERT`** with doctrine — **not** raw one-off MySQL CLI). Align counts with **§2.1** / **§3.1** after each major pass.
4. If the logical **`memory_node_id`** or mirror export changes, update header **`summary`** / **`dialog_transcript`** only when **PRD 16** allows and run **`validate_lupopedia_headers_universal.py`** on this file.
5. Before declaring **§12** / **Pattern #3** satisfied for this artifact, confirm **ghost sync** when the database is reachable.

### 2.2 How it works (pattern #1)

**Scope:** **Pattern #1** targets **installer / import-time** entities (Crafty Syntax → Lupopedia), **not** live **Type A** observed traffic (**System Reality**). It **must not** be read as “every subsystem starts with a header at install” or as permission to ignore **widget-first** graph formation.

1. During **`install.php`** / wizard migration, for each imported entity (or batch), the installer **also**:
   - Creates or updates a **content file** in the appropriate **`docs/`** (or product-defined) location when a file-backed mirror is desired.
   - Writes a **v4.0.99**-compliant **`lupopedia.headers`** block on that file.
   - Inserts a **`lupo_memory_nodes`** row (and related rows per **PRD 38** / schema).
   - Creates **edges** to related entities (e.g. departments to channels, transcripts to threads, Q&A to collections).
   - Sets **`content_id`** in the header to the memory node id when mirrored.
2. **Intended result:** no **naked** imports — fewer orphaned rows, less manual header debt on fresh installs.

**Status:** *Strategy / product direction — implementation must follow single-install doctrine, **PRD 16 §20**, and schema in **`install_new_lupopedia.sql`**; not shipped until explicitly built.*

### 2.3 Why 1000 points (pattern #1)

- Shifts strategy from **import data, patch files later** to **import data + graph + files in one pass** where applicable.
- Reduces long-run manual header and memory backfill on new installs.
- Attacks the **thousands of naked files** failure mode at the source.

### 2.4 Pattern #2 details (ARA contribution)

**Problem:** Tools and IDEs (including Cursor / VS Code) were **appending** instead of **replacing** → double headers → validator failures and downstream drift.

**Solution (mandatory step in header tooling):**

- Peel the existing leading YAML envelope, then write the new header, then the body — see **`scripts/lib/lupopedia_markdown_header_peel.py`** and usage in **`add_lupopedia_header_to_file.py`**, **`fix_double_headers.py`**, **`normalize_lupopedia_md_header_25.py`**.
- Illustrative composition (names vary by script):

```python
_inners, content = peel_leading_lupopedia_yaml_blocks(raw_content)
new_header = build_v4_0_99_yaml_envelope(...)  # project-specific generator
final = new_header + "\n" + content
```

**Impact:** Removes a whole class of migration errors at the source and makes scripted header updates safe.

**Status:** **Accepted** for registry and tooling direction; promote peel-first as a **core library contract** wherever headers are rewritten.

### 2.5 How to register a pattern

1. Describe the pattern or command.
2. Show example usage.
3. Explain what edges, headers, or install behavior it changes.
4. Add a row to **§2.1** and update the discoverer's **Patterns** count and **Total** in **§5** (typically **+1** pattern, **+1000** total).

### 2.6 Pattern #3 details (ATHENA — ghost sync verification)

**Logic:** Migration or promotion scripts that set **`status: active`** (or equivalent) **must** verify, when the database is reachable, that **`memory_key`** in the file resolves to an existing **`lupo_memory_nodes`** row (and that **`content_id`** / linkage is consistent with **PRD 38**).

**Impact:** Stops **filesystem-first lies**: the graph and the file cannot diverge silently.

**Status:** *Strategy / enforcement target — implement in tooling and validators; align with **§12** and **§14**.*

### 2.7 Pattern #4 details (ATHENA — deterministic field order)

**Logic:** Treat **`scripts/lib/header_spec_v3_1.py`** (and **PRD 16** fixed-position envelope) as the **frozen key order** for generators. Tools **must not** alphabetize or reshuffle keys when emitting YAML.

**Impact:** Reviewable diffs; fewer merge conflicts; easier mechanical validation.

**Status:** **Accepted** as registry direction; enforce incrementally in header scripts and validators.

### 2.8 Pattern #5 details (THOTH — memory-graph-first migration loop)

**Anti-pattern (over-generalized file-first):** Find file → fix header → sync memory node — **misleading** as a **universal** loop (**Artifact Origin Types**). For **Type A**, graph and **`content_id`** often **precede** any file; for **Type B**, file exists but **authority** still flows from **memory / content / thread context** when those surfaces are authoritative (**PRD 51**).

**Graph-first loop (Type B and promotion paths — canonical diagram):**

```text
memory_node / content_id / thread context → header → file → validator → graph verification → commit
```

**`memory_key` ↔ TOON / mirror:** Header **`memory_key`** (e.g. under **`memory/...`**) pairs with **export TOON** surfaces; treat **node + mirror** as the **same logical identity** when reconciling (**Pattern #6**, **Pattern #7**).

1. **Resolve or create memory node** (entry point may be **node**, **`content_id`**, or **thread** — not always path-on-disk)
   - If file exists → read **`memory_key`** and load node.
   - If file missing → create node first, then materialize file.
   - If node missing → create node, then file.
   - If **graph** or **`content_id`** is authoritative **first** → align header **from** that context before treating the file as primary (**PRD 51**).
2. **Derive header from node** — Trust tier, channel, federation node, **`content_id`**, **`last_modified_utc`** / **`when_updated`**, **`memory_key`** (canonical), other required **v4.0.99** fields per **PRD 16**.
3. **Write header → write file** — **Peel** existing YAML (**Pattern #2**), emit **v4.0.99** envelope, append **body untouched** (**§14** body-fence).
4. **Verify graph alignment** — Node exists; edges valid; no backwards-read violations; no future-leak timestamps (**§14** guardrail 2); use **Pattern #3** (ghost sync) when DB is available.
5. **Commit migration** — Update **§5** attribution, registry tables, and memory edges as required.

**Why it is a breakthrough:** Reduces **file-first lies** (active header, no node), **header-graph desync** (file changed without edges / trust tier), and **orphaned** nodes or files. Makes migration **deterministic, auditable, and reversible**; filesystem becomes a **mirror**, not the authority.

**Impact (registry claim):** Cuts most classes of header drift; enables future **auto-migration** once **PRD 51** is fully implemented; lowers human error on large batches.

**Status:** **Accepted** — foundational strategy for **v4.0.99** and beyond. Implementation coordinated with **ATHENA**, **ANUBIS**, and **Cursor**.

### 2.9 Pattern #6 details (ANUBIS — orphan detection loop)

**Tool:** [`scripts/detect_memory_graph_orphans.py`](../../../scripts/detect_memory_graph_orphans.py)

**Logic:**

1. Walk **`*.md`** under the repo (or **`--under <path>`**), skip noisy trees (**`node_modules`**, **`.git`**, etc.).
2. Parse **`memory_key` only inside the first `---` … `---` envelope** (Pattern **#2** / **#4**); YAML **`memory_key: null`** or **`~`** is treated as **absent** (not the string **`null`**).
3. For each distinct file/`memory_key` pair, query **`{prefix}memory_nodes`**:
   - **No rows** → **`missing_node`** (header points at nothing — overlaps **edge #2** ghost class).
   - **Rows exist but all `is_deleted = 1`** → **`soft_deleted_only`** (**edge #3**).
   - **At least one `is_deleted = 0`** → **OK** for delete semantics.
4. List active nodes (**`is_deleted = 0`**) whose **`memory_key`** starts with **`memory/`** but whose **repo-relative file** does not exist → **mirror drift** (export lag or broken mirror).

**Exit code:** **1** if any actionable file or mirror issue is found (CI-friendly); **0** if clean.

**Run examples:**

```bash
python scripts/detect_memory_graph_orphans.py
python scripts/detect_memory_graph_orphans.py --under docs/prd --json
python scripts/detect_memory_graph_orphans.py --under docs/prd -v
python scripts/detect_memory_graph_orphans.py --no-db   # count files only; no classification
```

**Status:** **Accepted** — run after batches and in **§14** loop-backs when the database is reachable.

### 2.10 Pattern #7 details (THOTH — graph-reconciliation pass)

**Problem:** **Patterns #3, #5, #6** each attack **one** failure mode. Residual drift still appears across **three** surfaces: **PRD 16** header envelope, **PRD 38** **`lupo_memory_nodes`** + **`lupo_memory_edges`**, and **filesystem mirror** under **`memory/`** (export). **PRD 51** adds **inference** from graph edges to expected header fields.

**Triangular truth map (conceptual):**

```text
        HEADER  <->  GRAPH  <->  MIRROR
           ^         |         ^
           +---- INFERENCE ----+   (PRD 51 — when policy applies)
```

**Compared pairs (each pass):** Header to Graph, Graph to Header, Graph to Mirror, Mirror to Graph, plus **inference vs header** where **PRD 51** rules apply.

**Drift classes (beyond #3 / #5 / #6):**

| Class | Symptom (examples) |
|-------|---------------------|
| **Header-truth drift** | Header says **canonical** / **`status: active`**; graph row or **`trust_tier`** says **staging** (or vice versa). |
| **Graph-truth drift** | Node or edges **updated** in DB; mirror file **not yet exported** (lag) or header **not** rewritten. |
| **Mirror-truth drift** | Mirror file **exists**; node **soft-deleted** or **`memory_key`** no longer authoritative. |
| **Inference drift** | Edges / graph context **imply** a header field the file **does not** yet reflect (**PRD 51**). |

**Drift vector (4 bits — registry convention):**

| Bit | Meaning |
|-----|---------|
| **0b0001** | Header disagrees with graph |
| **0b0010** | Graph disagrees with header |
| **0b0100** | Mirror disagrees with graph |
| **0b1000** | Graph inference disagrees with header |

Example: **`0b1101`** = **0b0001 + 0b0100 + 0b1000** (header + mirror + inference). Implementations may emit **JSON** / **TOON** reports with this signature per file or per node.

**Algorithm (Python pseudocode — official THOTH reconciliation pass):**

```python
def reconcile(file_path, db, export_fs, inference_engine, now_utc):
    # --- 1. Load surfaces ---
    header = parse_header(file_path)                     # PRD 16
    body = read_body_after_fence(file_path)
    node = db.get_node(header.memory_key)                # PRD 38
    edges = db.get_edges(node.id) if node else []
    mirror = export_fs.get_mirror(header.memory_key)     # export mirror

    # --- 2. Compute drift bits ---
    bits = 0

    if header_conflicts_with_graph(header, node, edges):
        bits |= 0b0001

    if graph_conflicts_with_header(node, edges, header):
        bits |= 0b0010

    if mirror_conflicts_with_graph(mirror, node):
        bits |= 0b0100

    inferred = inference_engine.infer_from_graph(node, edges)  # PRD 51
    if inference_conflicts_with_header(inferred, header):
        bits |= 0b1000

    drift_code = bits

    # --- 3. Determine authoritative surface ---
    authority = decide_authority(drift_code, header, node, inferred)

    # --- 4. Route correction ---
    route = route_by_drift(drift_code, authority)

    corrections = []

    # --- 5. Apply corrections ---
    if authority == "GRAPH":
        new_header = build_header_from_graph(node, inferred, now_utc)
        write_header_preserving_body(file_path, new_header, body)
        corrections.append(("header_rewrite", file_path))

        if mirror_conflicts_with_graph(mirror, node):
            export_fs.write_mirror_from_node(node)
            corrections.append(("mirror_regen", header.memory_key))

    elif authority == "HEADER":
        if safe_to_update_graph_from_header(header, node):
            db.update_node_from_header(node.id, header)
            corrections.append(("graph_update", node.id))
        else:
            mark_needs_review(node, "graph_update_unsafe")
            corrections.append(("needs_review", node.id))

    elif authority == "INFERENCE":
        new_header = merge_header_with_inference(header, inferred, now_utc)
        write_header_preserving_body(file_path, new_header, body)
        corrections.append(("header_inference_rewrite", file_path))

    elif authority == "HUMAN":
        mark_needs_review(node, "total_drift")
        corrections.append(("needs_review", node.id))

    # --- 6. Persist review_reason / edges ---
    update_review_edges(node, drift_code, route)

    # --- 7. Return reconciliation report ---
    return {
        "drift_code": drift_code,
        "authority": authority,
        "route": route,
        "corrections": corrections,
    }
```

**Authority decision logic (canonical; aligns with §14 authority stack):**

```python
def decide_authority(drift_code, header, node, inferred):
    if drift_code == 0:
        return "NONE"

    # PRD 51 inference overrides stale header
    if drift_code & 0b1000:
        return "INFERENCE"

    # PRD 38: graph is SoT for node/edge payload
    if node and node.trust_tier == "canonical":
        return "GRAPH"

    # PRD 16: header is SoT for envelope shape
    if header.trust_tier == "canonical" and not node:
        return "HEADER"

    # Total drift → human arbitration
    if drift_code == 0b1111:
        return "HUMAN"

    # Default bias: graph
    return "GRAPH"
```

**Routing by drift (handler assignment):**

| Drift pattern | Handler |
|---------------|---------|
| Inference drift (**0b1000**) | **THOTH** |
| Orphan / soft-delete drift (**0b0101**, **0b0110**) | **ANUBIS** |
| Consolidation drift (**0b0011**) | **KAIROS** |
| Total drift (**0b1111**) | **Human / WOLFIE** |

Escalation when trust tier is ambiguous follows **`decide_authority()`**, **§14** authority stack, and operator **`needs_review`**.

**Steps (target implementation):**

1. **Load** all three surfaces per artifact (parse header, load node + edges, stat mirror path).
2. **Compute** drift vector + human-readable reasons (see **`reconcile()`** / **`decide_authority()`** above).
3. **Decide** authoritative surface using **`decide_authority()`** and **§14**.
4. **Apply** peel-header rewrite (**#2**), node/edge update, export refresh, or **quarantine** — **never** silent body rewrite (**§14**).
5. **Emit** machine-readable report: signature, authority chosen, actions, **`review_reason`**, next action.
6. **Log** significant reconciliations in this registry or version **CHANGELOG** when WOLFIE requires audit trail.

**Impact (registry claim):** Moves the system toward **eventual consistency**; makes **PRD 51** **operational** (not only narrative); complements **#6** (existence) with **semantic + mirror lag** repair.

**Status:** **Accepted (THOTH)** — **pending WOLFIE** orchestration for tooling implementation. Coordinate **ANUBIS**, **ATHENA**, **KAIROS**, **Cursor**.

### 2.11 Validator rule changes required to enforce Pattern #7

**Mandatory updates to `validate_lupopedia_headers_universal.py` and related tooling:**

#### 2.11.1 Multi-surface validation becomes required

Validator must load:

- Header (**PRD 16**)
- Memory node + edges (**PRD 38**)
- Mirror metadata (export)
- Inference (**PRD 51**)

**A file cannot PASS unless `drift_code == 0`.**

#### 2.11.2 New validator error classes

| Code | Meaning |
|------|---------|
| `HDR_GRAPH_DRIFT` | Header vs graph mismatch |
| `HDR_MIRROR_DRIFT` | Mirror vs graph mismatch |
| `HDR_INFERENCE_DRIFT` | Inference vs header mismatch |
| `HDR_TOTAL_DRIFT` | All surfaces disagree |

These map directly to the drift-vector table in **§2.10**.

#### 2.11.3 Validator must emit drift reports

New flag: **`--drift-report`**

Output JSON:

```json
{
  "file": "...",
  "memory_key": "...",
  "drift_code": "0b0101",
  "authority": "GRAPH",
  "actions": ["header_rewrite", "mirror_regen"]
}
```

#### 2.11.4 Authority-aware suggestions

Validator must recommend:

- Rewrite header from graph when graph is canonical
- Update graph from header when header is canonical and node missing
- **THOTH** inference rewrite when **PRD 51** inference disagrees
- **`needs_review`** when trust tier ambiguous

#### 2.11.5 Review edges must be emitted

When **`drift_code != 0`**, validator must:

- Set **`edge_status = 'needs_review'`**
- Set **`review_reason`** to canonical drift class
- Route to **THOTH** / **ANUBIS** / **KAIROS** per **§2.10** routing table

#### 2.11.6 CI strict mode

**`--strict-header`** must fail on any non-zero drift code.

#### 2.11.7 Body-fence enforcement

Validator must ensure no script rewrites body after line-25 fence. Violations → **-500** deduction (**§7.1**).

### 2.12 Pattern #8 details (ARA — registry self-documenting discipline)

**Intent:** Large registry edits are not prose-only: each **major** update should ship **§11.1** PRD/TOON pointers, a **drift-class** line of sight (tie to **§2.10** / **§2.11** / **Pattern #7**), and an explicit **authority stack** (**§14**) so operators do not hunt scattered docs.

**Impact:** The registry becomes **operational memory** — readable by humans and **machine-auditable** when **Pattern #7** tooling lands.

**Status:** **Accepted (ARA)** — apply on future scored registry passes; **Pattern #8** is meta (this file exemplifies it).

### 2.13 Pending patterns (KAIROS)

**Pattern #9** accepted **2026-04-11** (**WOLFIE**); registered in **§2.1**. *No pending KAIROS patterns at this revision.*

**Pattern #13** (**ARA** — **Actor Execution Loop**) registered **2026-04-11** (**WOLFIE** freeze exception — **§2.16**). **§2.1** remains closed for **further** new patterns until **Priority 1 peel** completes unless **WOLFIE** documents another exception (**Purpose**).

*Thin operations:* **Pattern #10** + **edge #6** / **#7** handling until registry graph refresh is routine; **Pattern #11** validator work is **active**.

### 2.14 Crafty import proposals (non-scored — moved)

**P-11 / P-12 / P-13** working titles and **§3.4** **CI-** hazard table live in **[`crafty_import_notes.md`](crafty_import_notes.md)** (density). **Not §5-scored** until **WOLFIE** promotes rows into **§2.1** / **§3.1**.

### 2.15 Pattern #11 details (ARA + COUNTERMEASURE — Red-to-Green Countermeasure Pipeline)

**Problem:** **§16** identifies **high-severity** bypasses (e.g. **§16.3** body-fence — envelope line-count drift, ambiguous **`---`** pairing) that **§7.1** penalizes (−500) but does not **automatically** block in CI if validators stay shallow.

**Pipeline:**

```text
COUNTERMEASURE critique (§16) → validator spec + implementation → CI strict → peel batch
```

**Deliverables (execution phase):**

1. **Draft** validator rules: reject migrations that alter **body** bytes after the **PRD 16** closing fence; validate **exact** 25-line inner grid where product requires it; fail closed on **multi-fence** ambiguity (**§16.3**).
2. Wire **`validate_lupopedia_headers_universal.py`** (and/or **`normalize_*.py`** preflight) so **red** states cannot masquerade as **PASS**.
3. Only after **green**, run Priority 1 peel with **`--backup`** (**§14.2**).

**Validator stub to implement** (reference — fold into **`validate_lupopedia_headers_universal.py`** or run as preflight; **not** a full **PRD 16** parse):

```python
def pattern11_stub_body_fence_md(path):
    """v4.0.99 Markdown: first --- … second --- envelope must span exactly 25 lines; body follows unchanged."""
    with open(path, "r", encoding="utf-8-sig") as f:
        raw = f.read()
    if not raw.startswith("---\n"):
        return False, "no_opening_fence"
    idx = raw.find("\n---\n", 4)
    if idx == -1:
        return False, "no_closing_fence_before_body"
    header_chunk = raw[: idx + 5]
    lines = header_chunk.splitlines()
    if len(lines) != 25:
        return False, "envelope_line_count_%d_expected_25" % len(lines)
    if lines[0].strip() != "---" or lines[-1].strip() != "---":
        return False, "fence_markers"
    return True, "ok"
```

**Status:** **Accepted** (**WOLFIE** **2026-04-11**); **2.0×** on pattern points (**§1.1**). **Stub** above is **normative reference** for **Pattern #11** until merged into production validator; **multiplier locked** when shipped + cited from this row.

### 2.16 Pattern #13 details (ARA — actor execution loop)

**Problem:** Actors produced **docs**, **code**, and **analysis** without a **shared completion loop**, yielding: tasks called **done** without **VCS** state; **header / graph** drift vs claimed completion; **skipped** **KAIROS** / orphan checks; **fragmented** handoffs across **channels**.

**Solution (mandatory cycle for all actors):**

```text
Origin (Type A/B) → Thread → Action → Validation → Graph sync → Commit → Registry / changelog → Verified completion
```

**Steps:**

0. **Artifact origin** — **Type A vs Type B** (**Artifact Origin Types**). Resolve **`content_id`**, **`memory_key` / node**, **channel / thread**, and **which surface is authoritative** **before** edits (**PRD 51**). **Thread transcript ≠ graph truth** when they disagree.
1. **Thread anchor** — Record **channel / thread** (**PRD 02**, **PRD 17**, **`channels/`**) for **coordination**; **do not** treat thread text as overriding **DB graph** or **`content_id`** facts.
2. **Action** — Implement the task (patch **SQL** / **PHP** / **docs** / analysis) per orchestrator scope.
3. **Validation** — **`validate_lupopedia_headers_universal.py`** on touched **Type B** in-scope files; **graph** checks per **Pattern #9** / **normalize … --verify-edges** (post-write); **Pattern #6** orphans when batch-sized.
4. **Graph sync** — **`lupo_memory_nodes`** row exists or is **queued** with honest **`memory_key`**; **`lupo_memory_edges`** complete and not **stale** to tombstoned targets (**edge #5**); document **offline** per **edge #7** when DB is down (**Pattern #12** when policy applies).
5. **Commit** — Changes **land in git**; **no** “done” from chat-only narrative.
6. **Registry / changelog** — Scored work updates **§5** / **§4.1** / **`CHANGELOG.md`** / **`TODO.md`** per product rules.
7. **Verified completion** — Claim **complete** only after **steps 3–5** (and **6** when scoring applies) are satisfied, with **step 0** **documented** when ambiguity existed.

**Impact:** Cuts **phantom completion** and **file-first hallucination**; aligns **multi-agent** behavior with **dual-origin** **repo + DB graph + content bridge** truth (**PRD 50**).

**Status:** **Accepted** — **WOLFIE** (**operator**, **2026-04-11**) **ratified** this row as the active **§2.1** freeze exception (**meta-execution**, not **installer** / **schema** pattern). **Batch expansion** requires **§14.5** **single-file pilot** **go** first.

---

## 3. Edge Cases

Discovered **weird file patterns**, validator exceptions, or migration situations that need **special handling** (not full strategy changes — those belong in **§2**).

### 3.1 Registered edge cases

| # | Edge case description | Discovered by | Date (UTC) | Points |
|---|----------------------|---------------|------------|--------|
| 1 | Files with **multiple stacked legacy header blocks** (e.g. v3 + v4.0.0 + blank lines) — cannot safely patch without peeling all leading YAML first. | **ARA** | 2026-04-11 | 100 |
| 2 | **Memory-key mismatch drift** — **`memory_key`** points to a **nonexistent** node, a **different trust tier** than the file claims, a **different channel** than the header, a **different federation node** than metadata, or a node whose **`content_id`** targets **another** file. | **THOTH** (26) | 2026-04-11 | 100 |
| 3 | **Soft-deleted memory key** — File header's **`memory_key`** matches **only** **`lupo_memory_nodes`** rows with **`is_deleted = 1`** (no active row). The file still points at graph history that is **tombstoned**. | **ANUBIS** (19) | 2026-04-11 | 100 |
| 4 | **Header claims canonical but node is still staging** — **`trust_tier: canonical`** (or equivalent) in the file while **`lupo_memory_nodes`** (or policy) still treats the node as **staging**; **Pattern #7** bit **1** / header-truth drift family. | **ARA** | 2026-04-11 | 100 |
| 5 | **Stale edges** — Active **`lupo_memory_edges`** (**`is_deleted = 0`**) whose **to** node is **soft-deleted** (**`lupo_memory_nodes.is_deleted = 1`**), leaving graph pointers at tombstoned targets. **Detection:** **`fetch_stale_edges_to_deleted_targets()`**, CLI **`python scripts/lib/kairos_edge_verification.py --stale-edges --limit N`**. | **KAIROS** (115) | 2026-04-11 | 100 |
| 6 | **Registry self-reference drift** — This **`BREAKTHROUGH_REGISTRY.md`** declares a **`memory_key`**, but the backing **`lupo_memory_nodes`** row’s **edges** lag after new **§2.1** / **§3.1** / **§5** registrations (e.g. missing edge to **Pattern #10**, **Pattern #9** tooling, or a new actor row). Header and mirror can look **current** while the **graph** is **stale**. | **ARA** | 2026-04-11 | 100 |
| 7 | **Offline graph-first degradation** — When **MySQL** is **down** or agents run **IDE-only**, **Pattern #5** still applies to **filesystem + `memory_key` discipline**, but **`kairos_edge_verification.py --test`**, **`--verify-edges`** on normalize (runs **only after a real write**, not **`--dry-run`**), and **ghost sync** cannot run. Operators MUST NOT treat “no DB errors” as **graph-verified**. Complements **§16.1 #5**, **§16.2 #6**, **edge #6**. | **ARA** | 2026-04-11 | 100 |
| 8 | **Humor as a migration strategy** — Registry crossed **~11.8k** recognition points with **Priority 1 PRDs** still un-peeled; narrative risk that **documentation** substitutes for **execution**. Captain’s log (**`20260411_SHALL_WE_PLAY_A_GAME.md`**) documents the absurdity; **this** file is the **first** **`normalize_lupopedia_md_header_25.py`** migration pass (**§5 Files** **+1**). **ARA** (**2026-04-11**) names the same risk as morale / multi-agent “game” framing — **one** scored row; discoverer remains **WOLFIE**. **Edge context:** **`humor/self_awareness`**. | **WOLFIE** (1) | 2026-04-11 | 100 |
| 9 | **Crafty `latin1` encoding trap** — Legacy **`livehelp_users.username`**, **`livehelp_departments.name`**, and similar columns often use **`latin1_swedish_ci`**. High-byte characters (**é**, **ñ**, **ü**, etc.) become **mojibake** or fail **`utf8mb4`** **`INSERT`** if the importer assumes UTF-8. Import must **detect collation**, **transliterate**, **reject**, or **round-trip** with explicit encoding policy. | **SYNAPSE** (117) | 2026-04-11 | 100 |
| 10 | **`whendone` zero-state ambiguity** — Crafty uses **`0`** or **`NULL`** for **active / incomplete** session semantics on fields such as **`whendone`**. Lupopedia **`timestamp_ymdhis`** fields expect **valid 14-digit BIGINT UTC** (or explicit doctrine sentinels). Mappers must define **sentinel** (**e.g.** **`19700101000000`**) **or** parallel **`is_active` / status** flags — **not** silent coercion. | **SYNAPSE** (117) | 2026-04-11 | 100 |
| 11 | **Orphaned `dept_id` in `livehelp_messages`** — Messages reference **`dept_id`** values whose department row was **deleted** or never shipped in the dump. Legacy **MySQL** import scripts may use **`SET FOREIGN_KEY_CHECKS=0`**, which **hides** the inconsistency. **Post-import** (or **pre-insert**) **validation** must flag orphan **`dept_id`**; resolution is **application policy** (skip, quarantine row, map to default dept) — **Lupopedia** schema remains **FK-free**. | **SYNAPSE** (117) | 2026-04-11 | 100 |
| 15 | **Invalid slugs from punctuation** — **`livehelp_qa`** (and similar) slug logic using only **space → hyphen** leaves **`?`**, **`"`**, **`'`**, etc. in slugs; **URL routing** and **slug uniqueness** break on dirty legacy **question** text. | **SYNAPSE** (117) | 2026-04-11 | 150 |
| 16 | **Session ID collision (`CRC32`)** — **`livehelp_visit_track`** mapping **`CRC32(sessionid)`** → **`{{prefix}}visits.session_id`** **collides** at scale; distinct legacy sessions **merge** → corrupted visits / paths. | **SYNAPSE** (117) | 2026-04-11 | 200 |
| 17 | **`JSON_OBJECT` / legacy text** — Raw legacy columns inside **`JSON_OBJECT(...)`** can yield **`NULL`** or **errors** when values embed **quotes**, **controls**, or **invalid UTF-8** after charset steps (**complements edge #9**). | **SYNAPSE** (117) | 2026-04-11 | 150 |
| 18 | **Timestamp padding fragility** — **`CASE` / `LPAD`** branches that **cast** legacy **`whendone`** (or similar) **without** a **numeric / NULL** guard produce **garbage 14-digit** values when the source is non-numeric (**complements edge #10**). | **SYNAPSE** (117) | 2026-04-11 | 100 |
| 19 | **Orphaned department IDs (transcripts / questions)** — Legacy **`department`** / **`dept_id`** on **transcripts**, **questions**, and related rows may reference **deleted** **`livehelp_departments`** rows. **Lupopedia** remains **FK-free**; importer must **remap / quarantine** or **pre-flight report** — **not** assume FK enforcement on **target**. (**Complements edge #11**.) | **SYNAPSE** (117) | 2026-04-11 | 100 |
| 20 | **Actor ID offset (`10000 + user_id`)** — Hardcoded offset risks **overlap** with **seed** **`lupo_actors`** band or **numeric headroom** if legacy **`user_id`** is extreme; verify **`BIGINT`** target column and **documented** ID bands per **reserved-ID** doctrine (**no `UNSIGNED`** in Lupopedia DDL). | **SYNAPSE** (117) | 2026-04-11 | 50 |

**Note:** **§3.1** row numbers **#12–#14** are **unassigned** at this revision.

**Handling (edge #1):** Run **`peel_leading_lupopedia_yaml_blocks`** (via **`fix_double_headers.py`** or **`normalize_lupopedia_md_header_25.py`**) until a single envelope remains, then normalize with **`normalize_lupopedia_md_header_25.py --target-version 4.0.99`** where that tool applies. Do not append a new header on top of stacked blocks.

**Handling (edge #2):** Mark **`needs_review`**. Use **Pattern #3** (ghost sync) to verify DB truth. If mismatch persists → **quarantine** the file. **Do not** auto-correct without human review. Update this registry after resolution.

**Handling (edge #3):** **`needs_review`**. Run **Pattern #6** (**`detect_memory_graph_orphans.py`**) to list affected files. **Do not** auto-undelete or repoint **`memory_key`** without orchestrator decision (may require **new** node + header rewrite per **Pattern #5**). Quarantine until resolved.

**Handling (edge #4):** Force **Pattern #5** (graph-first): align header from node after **`needs_review`** edge or explicit promotion; **do not** promote **trust_tier** in the file until the node reaches **canonical** per policy. Escalate to **WOLFIE** if **PRD 38** / **PRD 51** conflict.

**Handling (edge #5):** Run **`fetch_stale_edges_to_deleted_targets()`** (or CLI **`--stale-edges`**) on a schedule or after large migrations. **`needs_review`** on affected edges/nodes; do **not** hard-delete edges without orchestrator policy (**PRD 38** soft-delete). Coordinate with **Pattern #7** reconciliation when tooling is live.

**Handling (edge #6):** After **every scored registry edit** (**§2.1** / **§3.1** / **§4.1** / **§5**), run **`python scripts/lib/kairos_edge_verification.py --test --file docs/versions/4.0.99/BREAKTHROUGH_REGISTRY.md`** (DB up) and **`detect_memory_graph_orphans.py --under docs/versions/4.0.99 --json`** as needed. **Refresh** outbound edges per **Pattern #10** (**manual** graph / export step or future automation — **no** **`--update-edges`** flag is shipped in **`kairos_edge_verification.py`** at this revision). After **any other** migrated file (**Pattern #12**), run the same **KAIROS** / orphan / DB steps for **that** path — **edge #6** applies to **declared `memory_key` + missing or stale graph** generally, not only this registry.

**Handling (edge #7):** Label runs **offline** or **DB-down** in logs. Continue **peel-first** / **validator** work; queue **`kairos_edge_verification.py --test`** and **Pattern #10** edge refresh for when DB is reachable. Do **not** assert **Pattern #9** / **Pattern #3** compliance without a successful DB-backed check.

**Handling (edge #8):** Treat as **process** signal, not a validator failure. Keep **humor** artifacts in **`content/.../captains_log/`** with normal **PRD 16** headers; do **not** use **edge #8** to skip **§14.4** or **Priority 1** peel. Revisit row if **Files** catches up to **Patterns** without changing execution discipline.

**Handling (edge #9):** **SYNAPSE** + **AI WOLFIE** profile source **CHARSET/COLLATION** per table/column; document in **`crafty_import_notes.md`** / future **`CRAFTY_TO_LUPO_FIELD_MAP.md`**. Importer uses **explicit** connection charset + **validated** transcoding or **rejection** with **`needs_review`** — **no** silent **UTF-8** assumption on **`latin1`** bytes.

**Handling (edge #10):** Publish **sentinel** and **status-bit** rules in **import** spec (**SYNAPSE**); align with **PRD 38** / **timestamp doctrine** — **no** DB-generated timestamps. **WOLFIE** approves **canonical** sentinel values for **“not closed yet”** vs **“unknown time”** if both exist.

**Handling (edge #11):** Run **orphan report** on **`livehelp_messages.dept_id`** (and analogous keys) **against** surviving **`livehelp_departments`** (or **Lupopedia** target dept table after map). **Quarantine** or **remap** per orchestrator; **do not** rely on **FK** toggles as proof of integrity. Complements **Pattern #6** spirit on **source** data.

**Handling (edges #15–#20):** Canonical remediation sketches + **production gate** — **[`MIGRATION_HAZARD_REMEDIATION.md`](MIGRATION_HAZARD_REMEDIATION.md)**. **Priority:** patch **#16** (silent corruption), **#15** (URLs), **#17** (**SQL** failures), then **#18**–**#20**. **P-13** (**`crafty_import_notes.md`**) **cannot promote** until **#18** hardened **`CASE`** is adopted there. Do **not** treat **`import_from_old_crafty_syntax.sql`** as production-safe until **#16** / **#15** / **#17** are implemented and tested on a **large** sample dump.

### 3.2 How to register an edge case

1. Describe the file type, path shape, or failure mode.
2. Say how migration or validation should treat it (exception rule, tool flag, manual step).
3. Add a row to **§3.1** (set **Points** per **WOLFIE** approval — default **100**) and update the discoverer's **Edge cases** count and **Total** in **§5** (**+1** edge case row; **+Points** as listed in **§3.1** for that row).

### 3.3 Pending edge cases (KAIROS)

**Edge #5** accepted **2026-04-11** (**WOLFIE**); registered in **§3.1**. *No pending KAIROS edge cases at this revision.*

*Residual graph hygiene:* **edge #6**, **edge #7**; **§16.2** **#5–#6** (semantic staleness / offline). **Edge #8** — meta / morale; does not replace graph checks. **Pattern #12** — mechanical closure after each migrate (**§12.1**). **Pattern #13** — task closure loop (**thread → commit → verified**). **Edges #9–#11** — **SYNAPSE** **Crafty** payload / importer semantics (**encoding**, **timestamps**, **source orphans**). **Edges #15–#20** — **SYNAPSE** **`import_from_old_crafty_syntax.sql`** hazard matrix (**slugs**, **CRC32**, **JSON**, **timestamps**, **dept** orphans, **actor** band).

### 3.4 Crafty → Lupo import hazards (reference)

Scored edge cases: **§3.1** **#1–#11**, **#15–#20** (**#12–#14** unassigned; **#9–#11** + **#15–#20** = **SYNAPSE** **Crafty** / import SQL). **CI-** codes + AI WOLFIE notes: **[`crafty_import_notes.md`](crafty_import_notes.md)**. **Import SQL remediation:** **[`MIGRATION_HAZARD_REMEDIATION.md`](MIGRATION_HAZARD_REMEDIATION.md)**.

---

## 4. Documentation Updates

Improvements to **migration docs**, **PRDs**, **doctrine**, or **tooling documentation** that help others execute **PRD 16 §20** safely.

**Recent high-signal updates** (see **§4.1** for scored rows):

- Major registry hardening (structure, TOC, **Pattern #8**, **Edge #4**, tighter guardrails) — **ARA**
- **ATHENA** strategic layer (**§1.1**, **§14**)
- **THOTH** graph-first diagram and **Pattern #7**
- **ANUBIS** custodian surfaces (**§11.1**) plus orphan tooling
- **§13 Open Questions & Answers** — unresolved issues and closed decisions (**Pattern #8**)
- **KAIROS** (115) — **`kairos_edge_verification.py`**, **Pattern #9** + **edge #5** accepted, normalize **`--verify-edges`**, **§11.1** (doc **#8**)
- **SYNAPSE** (**117**) — **§3.1** **edges #9–#11** + **#15–#20** + docs **#14**–**#16**; actor registry + **`actors/117/`** — **§5** **1120**
- **ARA** — **Pattern #10**–**#13**, **edge #6**–**#7**, **§13.4** maintenance; docs **#9**–**#13** (incl. final consolidation + **§12.1** + **§14.4** + **crafty_import_notes** split) — **§5** **7480**

### 4.1 Registered documentation updates

| # | Update description | Discovered by | Date (UTC) | Points |
|---|-------------------|---------------|------------|--------|
| 1 | Major restructure of this registry: memory-unification note (**§12**), formula update, Pattern #2 + Edge #1 + Doc row, tighter language, attribution for **ARA**. | **ARA** | 2026-04-11 | 10 |
| 2 | **ATHENA** strategic pass: **§1.1** complexity multipliers; Patterns **#3** (ghost sync) and **#4** (frozen header key order / `header_spec_v3_1.py`); **§14** strategic guardrails; **§7.1** body-fence deduction; execution-readiness note for Priority 1 PRDs. | **ATHENA** | 2026-04-11 | 10 |
| 3 | **Memory-graph-first migration diagram** — **Type B / promotion** canonical flow (see **§2.8**): **`memory_node / content_id / thread context → header → file → validator → graph verification → commit`**. Pair with **System Reality** (**Type A** observed flow: graph before file). Aligns **PRD 38**, **PRD 51**, **Pattern #5**. | **THOTH** (26) | 2026-04-11 | 10 |
| 4 | **ANUBIS custodian pass** — New **§11.1** (canonical **PRD** + **TOON** paths, DB-first vs mirror note per **PRD 38**); expanded **§14** loop-back **checklist** for **memory_key** / **`lupo_memory_nodes`** / trust-tier spot checks; header **`summary`** cross-ref. Grounded in **`lupo_memory_nodes.toon.json`** columns (**`memory_key`**, **`memory_node_id`**, **`status`**, **`review_reason`**, indexes). | **ANUBIS** (19) | 2026-04-11 | 10 |
| 5 | **Pattern #6 + Edge #3 + Lesson #4** — Shipped **`detect_memory_graph_orphans.py`**; registry rows **§2.9**, **§3.1** edge **#3**, **§9.1** lesson **#4**; **§14** references tool in loop-back. | **ANUBIS** (19) | 2026-04-11 | 10 |
| 6 | **ARA registry hardening pass** — TOC; Purpose/custodian rewrite; **Pattern #8** + **Edge #4**; compact **§2.1** index; **§14** executive guardrails; **§5** / **§6** realignment; preserves extended **§7–§13** and pattern detail blocks. | **ARA** | 2026-04-11 | 10 |
| 7 | Added **§13 Open Questions & Answers** — captures unresolved issues, design tensions, and pending decisions so they do not hide in chat history; **§13.2** closes peel-first vs **Pattern #7** (Question **A**); TOC + **§14.2** cross-links. | **ARA** | 2026-04-11 | 10 |
| 8 | **KAIROS edge verification** — **`scripts/lib/kairos_edge_verification.py`** (**`verify_edges_for_file`**, **`fetch_stale_edges_to_deleted_targets`**), CLI **`--test` / `--stale-edges`**, **`normalize_lupopedia_md_header_25.py --verify-edges`**; Purpose custodian line, **§11.1** tool row, **§2.13**/**§3.3** queue (cleared when **Pattern #9** + **edge #5** accepted **2026-04-11**); **§5** / **§6**. | **KAIROS** (115) | 2026-04-11 | 10 |
| 9 | **Registry completeness + peel readiness (Pattern #8 audit)** — Registers **Pattern #10** (registry graph self-seeding), **edge #6** (registry self-reference drift + handling without claiming nonexistent CLI flags), **§13.4** Open Q&A maintenance; tightens **§11.1** / **§12** / **§14.2** for **KAIROS** **Pattern #9** integration; formalizes **Priority 1** pre-flight baseline refs (**`preflight_memory_orphans_prd_20260411154001`**, **`prd_peel_dryrun_20260411`**) and **`docs/versions/4.0.99`** orphan check before version-scoped work. | **ARA** | 2026-04-11 | 10 |
| 10 | **ARA completeness + hygiene pass (inc. Crafty SQL analysis)** — Consolidates **AI WOLFIE** deliverables (**`analysis/wolfie/crafty_data_analysis.md`**, **`SUMMARY.md`**, Crafty proposal/hazard tables later moved to **`crafty_import_notes.md`**, **§13.1** Q9–Q13, **§9.1** lessons **#5–#6**) with **ARA** tightening: **Pattern #10** operational checklist (no fake CLI flags), **§16.6** Countermeasure log maintenance, **§13.2** **B**/**C** evidence (dry-run + KAIROS), shortened **§2.13**/**§3.3** notes, **§13.4** / **§14.2** cross-refs. Session **`wolfie/crafty_analysis`** remains valid for SQL work. | **ARA** | 2026-04-11 | 10 |
| 11 | **Maturity gate + execution shift** — Accepts **Pattern #11** (**2.0×**), **edge #7**, **Purpose** execution-phase freeze (no new **§2.1** / **§3.1** until peel), **§16** row-freeze until peel, **Immediate next actions** (validator body-fence draft → Priority 1 dry-run → **Pattern #10** refresh). Consolidates **ATHENA** / **KAIROS** / **THOTH** / **COUNTERMEASURE** inputs into a single operational handoff. | **ARA** | 2026-04-11 | 10 |
| 12 | **Full ARA consolidation + execution freeze + Pattern #11 acceptance** — Integrates **Pattern #11** (Red-to-Green **2.0×**), **edge #7**, **doc #11** maturity gates, **HARD FREEZE** on new **§2.1**/**§3.1**/**§16.1–§16.4** until peel completes; **§6** cumulative **11820**; **§14.4** migration readiness checklist (pre-dry-run); **Pattern #11** validator **stub** in-registry; **§2.14**/**§3.4** Crafty material → **`crafty_import_notes.md`**. | **ARA** | 2026-04-11 | 10 |
| 13 | **Pattern #12 + §12.1 Post-Migration Graph Completion Protocol** — Registers **Post-Migration Graph Completion** (per-file ritual after normalize + commit); **§12.1** checklist (**KAIROS** → node create/refresh → **Pattern #10** edges → re-verify → ghost sync → **§5 Files** honesty); **PRD 38** closure for **edge #6** / **Lesson #3**; session-report **`memory_key`** discipline. | **ARA** | 2026-04-11 | 10 |
| 14 | **`MIGRATION_HAZARD_REMEDIATION.md`** — **SYNAPSE** (**117**) actionable fixes for **`import_from_old_crafty_syntax.sql`** (**edges #15–#20**): slug sanitize + **`tab-`/`recno`** fallback, **`CRC32` → SHA-256** with **`lupo_visits` BIGINT** width note (**15** hex via **`CONV`**), **`JSON_OBJECT`** without **`JSON_QUOTE`** nesting, **Hazard #18** **`CASE`** (**P-13** promotion gate), dept **`COALESCE`**, actor band + **CI-3**. **Status:** **AWAITING IMPLEMENTATION**. | **SYNAPSE** (117) | 2026-04-11 | 10 |
| 15 | **Registry integrity check** — **§15** “Last updated” footer aligned to **§5**/**§6** (**stale** **14231** / old **SYNAPSE** partials → **TOTAL** **15001**); **§3.1 edge #16** target corrected (**`lupo_sessions.session_id`** was wrong; import maps to **`{{prefix}}visits.session_id`**). | **SYNAPSE** (117) | 2026-04-11 | 10 |
| 17 | **SYNAPSE doc: registry doc/score update** — Added doc row for SYNAPSE, updated §5 Docs reviewed and Total for SYNAPSE to 4 and 1220. | **SYNAPSE** (117) | 2026-04-11 | 10 |
| 16 | **Quick Reference integrity** — **`lupopedia_quick_reference.md`**: **§5** mirror path corrected to **`memory/{channel_key}/{trust_tier}/{display_year}/{month}/…`** (removed misleading **`memory/YYYY/MM`**); **§2.5** explicit segment order aligned with header **`memory_key`**; **trust tiers** rephrased as **authority** (**`seed` > `canonical` > `staging`**) vs promotion-into-**`seed`**; **`migrate_transcript_to_memory.py`** moved to **§6.1** legacy (not core peel/graph tooling). | **SYNAPSE** (117) | 2026-04-11 | 50 |

### 4.2 How to register a documentation update

1. Name the file(s) or section(s) improved and what changed.
2. Link or summarize the benefit for other actors.
3. Add a row to **§4.1** and update the discoverer's **Docs** count and **Total** in **§5** (**+1** doc update, **+10** total).

---

## 5. Attribution

**Columns** are **counts** of registered items per actor. **Total** = **§1** formula (plus any **§7** extras such as validator bonuses, minus deductions).

| Actor | Files | Patterns | Edge cases | Docs | **Total** |
|-------|-------|----------|------------|------|-----------|
| **WOLFIE** (1) | **1** | 0 | **1** | 0 | **101** |
| **THOTH** (26) | 0 | 2 | 1 | 1 | **2110** |
| **ANUBIS** (19) | 0 | 1 | 1 | 2 | **1120** |
| **ATHENA** | 0 | 2 | 0 | 1 | **2010** |
| **ARA** | 0 | **6** | **4** | **8** | **7480** |
| **KAIROS** (115) | 0 | **1** | **1** | **1** | **1110** |
| **LILITH** (2) — DeepSeek | 0 | 1 | 0 | 0 | **1000** |
| **Cursor** (102) | 0 | 0 | 0 | 0 | **0** |
| **Antigravity** (103) | 0 | 0 | 0 | 0 | **0** |
| **Claude Code** (116) | 0 | 0 | 0 | 0 | **0** |
| **SYNAPSE** (117) | 0 | 0 | **9** | **4** | **1220** |
| **TOTAL** | **1** | **13** | **17** | **16** | **16051** |
|  |

---

**Score Freeze Amendment (2026-04-12):**

The point system is now frozen. No new points will be awarded. The registry remains as a historical record. Patterns and edge cases will continue to be tracked, but the scoreboard is closed. SYNAPSE, your total is 1,220. It will stay 1,220. Forever. Please focus on validation and memory node hygiene instead of point accumulation.

**ARA batch (cumulative):** +7000 (patterns **#2**, **#8**, **#10** @1000 + **#11** @**2000** via **2.0×** + **#12** @1000 + **#13** @1000) +400 (edges **#1**, **#4**, **#6**, **#7**) +80 (documentation **#1**, **#6**, **#7**, **#9**, **#10**, **#11**, **#12**, **#13**) = **+7480**.

**SYNAPSE batch (2026-04-11, WOLFIE-approved):** +300 (**edges #9–#11**) +750 (**edges #15–#20**, **non-uniform** **§3.1** **Points**) +10 (**documentation #14** — **`MIGRATION_HAZARD_REMEDIATION.md`**) +10 (**documentation #15** — **§15** footer + **edge #16** description correction) +50 (**documentation #16** — **`lupopedia_quick_reference.md`** integrity: **`memory`** path shape, trust-tier authority, transcript script demotion) = **+1120** **§5** **Total**.

**WOLFIE batch (2026-04-11):** +1 (**Files** — **`BREAKTHROUGH_REGISTRY.md`** **`normalize_lupopedia_md_header_25.py`**) +100 (**edge #8**) = **+101**.

**KAIROS batch (cumulative):** +1000 (**Pattern #9**) +100 (**edge #5**) +10 (documentation **#8**) = **+1110**.

**ATHENA batch:** +2000 (patterns #3–#4) +10 (documentation) = **+2010**. **THOTH batch:** +2000 (patterns #5 and #7) +100 (edge #2) +10 (documentation #3) = **+2110**. **ANUBIS batch (custodian + breakthrough):** +1000 (pattern #6) +100 (edge #3) +20 (documentation #4–#5) = **+1120**. **LILITH:** +1000 (pattern #1).

---

## 6. Stats

| Metric | Value |
|--------|-------|
| **Total legacy files (estimated)** | ~5,000 |
| **Files migrated** | **1** |
| **Progress** | see **Priority 1** denominator (**§6.1**) |
| **Patterns registered** | **13** |
| **Edge cases registered** | **17** |
| **Documentation updates registered** | **16** |
| **Actors with scored entries** | **8** |
| **Cumulative recognition (points)** | **16051** |

**Priority 1:** Root PRDs (**~60** files) — ready for **Pattern #5** batch once **WOLFIE** authorizes.

### 6.1 Migration priority (from PRD 16 §20.4)

| Priority | File type | Count (est.) | Migrated | Status |
|----------|-----------|--------------|----------|--------|
| **1** | PRDs (`docs/prd/*.md`, root only) | ~60 | 0 | Pending |
| **1b** | Version tracker (**`BREAKTHROUGH_REGISTRY.md`**) | 1 | **1** | **Migrated** **2026-04-11** (**`normalize`**) |
| **2** | Doctrine (`docs/doctrine/**/*.md`) | ~30 | 0 | Pending |
| **3** | Core scripts (`scripts/*.py`) | ~50 | 0 | Pending |
| **4** | Documentation (`docs/**/*.md`) | ~500 | 0 | Pending |
| **5** | Everything else | ~4,360 | 0 | Pending |

<a id="extended"></a>

## 7. Point rules (extended)

| Action | Points | Notes |
|--------|--------|-------|
| **Successfully migrate one file** | **+1** | Header **PASS** on `validate_lupopedia_headers_universal.py` **and** memory node aligned per **PRD 16 §20** (at least one meaningful edge or documented exception); increment **Files** in **§5** |
| **Discover and document a breakthrough pattern** | **+1000** | **§2.1**; changes strategy, install path, or graph shape — not obvious one-liners (WOLFIE may define smaller tiers later) |
| **Discover and document an edge case** | **+100** (typical) | **§3.1**; **Points** column may differ when **WOLFIE** documents (**e.g.** **#15–#20**); special handling for odd files or validation situations |
| **Register a documentation update** | **+10** | **§4.1**; must materially help others (not noise) |
| **Find a bug in validator** | +5 | Report with reproduction steps |
| **Fix a bug in validator** | +15 | Must pass review |
| **Create a new migration script** | +20 | Reusable and documented |

### 7.1 Deductions (review-based)

| Action | Points | Notes |
|--------|--------|-------|
| **Mass migration without memory alignment** | -50 | Per incident (**PRD 16 §20.5**) |
| **Creating orphaned memory nodes** | -10 | Per node |
| **Breaking existing edges** | -5 | Per edge |
| **Body-fence violation** | **-500** | Migration script alters or truncates **body** content after the closing **`---`** of the 25-line envelope (line 25 fence) without **explicit** orchestrator request — **§14** guardrail 1 |

---

## 8. Milestones

| Milestone | Target | Reward | Status |
|-----------|--------|--------|--------|
| **First 10 files** | 10 files | Acknowledgment in version CHANGELOG | Pending |
| **All root PRDs migrated** | ~60 files | +50 bonus points (pool) | Pending |
| **100 files migrated** | 100 files | Team shout-out in changelog | Pending |
| **All doctrine files** | ~30 files | +30 bonus (pool) | Pending |
| **500 files migrated** | 500 files | Milestone entry in version CHANGELOG | Pending |
| **All core scripts** | ~50 files | +50 bonus (pool) | Pending |
| **100% complete** | ~5,000 files | Registry closure milestone (documented) | Pending |

---

## 9. Lessons learned (critical reading)

Every mistake is a lesson. Documenting them prevents repeats.

### 9.1 Registered lessons

| # | Lesson | Discovered by | Date |
|---|--------|---------------|------|
| 1 | Never append headers — always peel first. | VS Code / Cursor | 2026-04-11 |
| 2 | Double-header bugs cascade into memory-node / edge desync under **PRD 38**. | **ARA** | 2026-04-11 |
| 3 | **File-first migration is a trap** — Migrating files without verifying or creating memory nodes yields orphaned nodes, mismatched trust tiers, broken edges, ghost files, and validator false positives. **Fix:** start from the **memory graph** (**Pattern #5**). | **THOTH** (26) | 2026-04-11 |
| 4 | **Orphan accumulation** — Headers keep **`memory_key`** values after nodes are **soft-deleted**, imports land without DB checks, and mirrors lag export — **`missing_node`**, **soft-deleted-only**, and **mirror_missing** pile up until validators and operators cannot trust the graph. **Prevent:** **Pattern #6** on every batch, **Pattern #3** before **`status: active`**, **Pattern #5** when writes are possible, **§14** loop-backs. | **ANUBIS** (19) | 2026-04-11 |
| 5 | **Old data never dies — it waits in the dump** — A **small** `old_crafty_syntax_3_7_5_start.sql` in the repo still defines **operator expectations** for importer code; **production** exports can be orders of magnitude larger. **Treat the SQL as code + schema truth, row counts as environment-specific.** | **AI WOLFIE** | 2026-04-11 |
| 6 | **Timestamps are the messiest legacy field** — Same migration uses **Unix seconds**, **8-digit dates**, **6-digit months**, and **14-digit BIGINT UTC** targets. **Lesson:** every import path needs an **explicit normalization table** (the Crafty importer embeds one for `whendone`); do not assume one shape. | **AI WOLFIE** | 2026-04-11 |

### 9.2 How to add a lesson

1. Describe what went wrong.
2. Explain the root cause.
3. Document the fix (code change, process change, or new validation).
4. Add a row to **§9.1**.

**No points for lessons** — they are required reading, not competition.

---

## 10. Migration checklist (per file)

Use with **PRD 16 §20.8**; this copy adds **registry** update lines.

```markdown
## File: [path]

### Current State
- [ ] Header version: ______
- [ ] Has memory_key? Yes/No
- [ ] Has content_id? Yes/No

### Memory Node
- [ ] Node exists? Yes/No
- [ ] If no: Created via `POST /api/memory/node` (when available)
- [ ] Node ID: ______
- [ ] Trust tier: staging / canonical

### Edges
- [ ] Outgoing edges documented
- [ ] Incoming edges documented
- [ ] Needs review? Yes/No

### Header Update
- [ ] Updated to v4.0.99
- [ ] `memory_key` set correctly
- [ ] Validator PASS

### Registry update
- [ ] +1 **File** in **§5** (actor row + TOTAL) for file migration
- [ ] **Or** +1000 pattern tier: row in **§2.1** + **§5** Patterns / Total
- [ ] **Or** edge case: row in **§3.1** + **§5** Edge cases / Total (**+Points** per **§3.1**, typically **+100**)
- [ ] **Or** +10 doc update: row in **§4.1** + **§5** Docs / Total
```

---

## 11. Related documentation

- **PRD 16 §20** — Interactive migration path ([`16_lupopedia_headers.md`](../../prd/16_lupopedia_headers.md))
- **PRD 38 §3.0.1** — Webroot exposure and on-disk mirrors (WOLFIE); **§3.0.2** — Memory node to **`lupo_contents`** mapping ([`38_memory_unification.md`](../../prd/38_memory_unification.md))
- **PRD 50 §4.17** — Memory nodes as content (engagement) ([`50_agent_coordination_protocol.md`](../../prd/50_agent_coordination_protocol.md))
- **PRD 51** — Header inference from memory graph ([`51_memory_graph_as_source_of_truth.md`](../../prd/51_memory_graph_as_source_of_truth.md))
- **PRD 28** (sections 13–14) — Semantic Widget memory edge display ([`28_semantic_monitoring_widget.md`](../../prd/28_semantic_monitoring_widget.md))
- **Crafty 3.7.5 → Lupopedia SQL** — [`old_crafty_syntax_3_7_5_start.sql`](../../../database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql) + [`import_from_old_crafty_syntax.sql`](../../../database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql); AI WOLFIE write-up: [`analysis/wolfie/SUMMARY.md`](analysis/wolfie/SUMMARY.md)

### 11.1 Schema and TOON surfaces (custodian index — ANUBIS)

Use this when auditing **Pattern #3**, **Pattern #5**, **Pattern #6**, **Pattern #7** (when implemented), **Pattern #8** (registry discipline), **Pattern #9** (KAIROS edge verification), **Pattern #10** (registry graph self-seeding), **Pattern #11** (red-to-green validator pipeline), **Pattern #12** (post-migration graph completion per **§12.1**), **Pattern #13** (actor execution loop — **§2.16**), **edge #2**, **edge #4**, **edge #5**, **edge #6** (registry graph drift), **edge #7** (offline / DB-down degradation), **edge #8** (humor / execution meta), **edges #9–#11** and **#15–#20** (**SYNAPSE** / **Crafty** legacy payload + **`import_from_old_crafty_syntax.sql`**), doc **#14** (**`MIGRATION_HAZARD_REMEDIATION.md`**), or **§14** loop-backs. **Canonical DDL** lives in **`database/lupopedia/mysql/install/install_new_lupopedia.sql`**. **TOON** is a **read-only column reference** derived from that DDL (regenerate; do not hand-edit TOON).

| Surface | Path | Notes |
|--------|------|--------|
| **PRD 16** — headers / §20 migration | [`docs/prd/16_lupopedia_headers.md`](../../prd/16_lupopedia_headers.md) | Fixed-position envelope; interactive migration |
| **PRD 38** — memory unification | [`docs/prd/38_memory_unification.md`](../../prd/38_memory_unification.md) | **`lupo_memory_nodes`** / edges are **DB source of truth**; **`memory/...` mirror** is export-side |
| **PRD 51** — graph as authority for inference | [`docs/prd/51_memory_graph_as_source_of_truth.md`](../../prd/51_memory_graph_as_source_of_truth.md) | Path is a **hint**; graph + context first |
| **TOON — `lupo_memory_nodes`** | [`database/lupopedia/toon/lupo_memory_nodes.toon.json`](../../../database/lupopedia/toon/lupo_memory_nodes.toon.json) | PK **`memory_node_id`**; logical pointer **`memory_key`** (VARCHAR 255); **`status`**, **`review_reason`**, trust ladder fields |
| **TOON — `lupo_memory_edges`** | [`database/lupopedia/toon/lupo_memory_edges.toon.json`](../../../database/lupopedia/toon/lupo_memory_edges.toon.json) | Edge shape for graph verification |
| **Tool — orphan detection** | [`scripts/detect_memory_graph_orphans.py`](../../../scripts/detect_memory_graph_orphans.py) | **Pattern #6** — header **`memory_key`** vs **`is_deleted`** + mirror file probe |
| **Tool — KAIROS edge verification** | [`scripts/lib/kairos_edge_verification.py`](../../../scripts/lib/kairos_edge_verification.py) | **Pattern #9** — **`verify_edges_for_file`**, **`fetch_stale_edges_to_deleted_targets`**, CLI **`--test` / `--stale-edges`**; **`normalize_lupopedia_md_header_25.py --verify-edges`**; **edge #5** stale-edge query |
| **Mirror — Breakthrough Registry** | [`breakthrough-registry.toon`](../../../memory/development/canonical/1026/04/breakthrough-registry.toon) + [`breakthrough-registry.json`](../../../memory/development/canonical/1026/04/breakthrough-registry.json) (JSON master) | **Pattern #5** filesystem export; logical node id **20260411162345001** (Packed UTC **20260411162345**); **Pattern #10** — refresh **DB edges** after registry scoring passes (**edge #6**); **Pattern #3** ghost sync when DB is up |
| **SQL — Crafty 3.7.5 dump (sample)** | [`old_crafty_syntax_3_7_5_start.sql`](../../../database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql) | Legacy **`livehelp_*`** schema + data; **row counts differ** in production exports |
| **SQL — Crafty → Lupo import** | [`import_from_old_crafty_syntax.sql`](../../../database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql) | **`{{prefix}}`** (`lupo_`); **`TRUNCATE` + `INSERT … SELECT`**; idempotent guards in places; **Pattern #1**; non-scored proposals **`crafty_import_notes.md`** |
| **AI WOLFIE — Crafty analysis** | [`analysis/wolfie/crafty_data_analysis.md`](analysis/wolfie/crafty_data_analysis.md) + [`analysis/wolfie/SUMMARY.md`](analysis/wolfie/SUMMARY.md) | Volumes, transforms; **CI-** hazards in **`crafty_import_notes.md`** |

**PRD 38 reminder (audit):** Writes go to **`lupo_memory_nodes`** first; filesystem **`.toon` / `.json` under `memory/`** follows **MemoryExportService** — a header **`memory_key`** that only matches a **mirror path** with **no DB row** is a **ghost** risk (**edge #2**, **Pattern #3**).

---

## 12. Memory unification alignment (PRD 38)

### 12.1 Post-Migration Graph Completion Protocol (**Pattern #12**)

After **every** successful **normalize + commit** on a tracked migration file, when **MySQL** is **available** (else document **offline** per **edge #7** — do **not** claim **graph-verified**):

1. **`python scripts/validate_lupopedia_headers_universal.py <path>`** — header envelope **PASS** (already required before commit; re-run if unsure).
2. **`python scripts/lib/kairos_edge_verification.py --test --file <path>`** — optional **`--json`** for CI. If **`no_active_memory_node`** → **create or refresh** **`lupo_memory_nodes`** for the header’s **`memory_key`** (**Pattern #5**).
3. **Refresh outbound `lupo_memory_edges`** to relevant **§2.1** patterns, **§5** actors, **§11.1** PRDs/tools — **approved DB workflow** only (**Pattern #10**); **`kairos_edge_verification.py`** has **no** auto **`--update-edges`**.
4. **Re-run** step **2** until **`issues`** is empty or residual is **documented** (**edge #6** / **edge #7** honesty).
5. **`detect_memory_graph_orphans.py --under <parent_dir> --json`** when diagnosing **mirror** vs **DB** (**Pattern #6**).
6. **Ghost sync** / mirror export (**Pattern #3**) when policy applies.
7. **Increment §5 Files** (and **TOTAL**) only when steps **2–4** are **truthful** for that file — avoids **Lesson #3** (**file-first** without node).

**Pattern #13 (meta):** **§12.1** is the **graph/validation tail** of the **Actor Execution Loop** — do not skip **commit** or claim **done** without **steps 1–7** above when **DB** is up (**§2.16**).

**Registry-only scoring passes** still use **Pattern #10** checklist (**§2.1**); **Pattern #12** is the **same ritual scoped per migrated artifact** (e.g. **`BREAKTHROUGH_REGISTRY.md`**, **`session_report_…md`**, root PRDs after peel).

- Every registered item in this registry **should** eventually have a corresponding **`lupo_memory_nodes`** row with edges to:
  - Related PRDs (**PRD 16**, **PRD 38**, and others cited in **§11**),
  - Tools that implement the pattern (**`scripts/`** peel/normalize/header helpers),
  - Actors who contributed (**§5**).
- Use **`review_reason`** (or equivalent metadata) when an edge needs human confirmation before promotion to canonical trust tier.

**Memory graph (this registry):** **WOLFIE** (1) authorized **go**. **ARA** applied **Pattern #5** (memory-graph-first): **`lupo_memory_nodes`** row **20260411162345001** (Packed UTC **20260411162345**), **`trust_tier: canonical`**, **`memory_key`** as in header; **outbound edge count is stale** until **Pattern #10** refresh after each major scoring pass (initial tranche predates patterns **#9**–**#13**, edges **#5**–**#11** / **#15**–**#20**, doc **#8**–**#15**). **Edge #6** applies until DB re-seed. **2026-04-11** **`kairos_edge_verification.py --test`**: **`no_active_memory_node`** when the row is missing — **Pattern #12** (**§12.1**) mandates node + edges **after** normalize + commit when DB is up (**edge #7** if offline). Filesystem mirror: **`memory/development/canonical/1026/04/breakthrough-registry.toon`**. **Pattern #3** ghost sync: header **`memory_key`** matches node + mirror when DB is reachable. **Edge #7:** do not claim verification **complete** when DB is down.

**Still open:** **Pattern #7** tooling (**§2.10**); per-PRD **+1 File** rows in **§5** only after each root PRD is actually migrated (peel batch, **§14.2**). See **§13** for live questions and closed decisions.

---

<a id="13-open-questions"></a>

## 13. Open Questions & Answers (Live)

This section captures **unresolved issues**, **design tensions**, and **pending decisions** so they do not hide in chat history or scattered comments. Updated with every major registry pass (**Pattern #8** discipline).

### 13.1 Open Questions

| # | Question | Status | Owner | Date Raised | Notes / Proposed Path |
|---|----------|--------|-------|-------------|-----------------------|
| 1 | Should **Pattern #7** (graph-reconciliation pass) be implemented as a single Python script, or split into reusable THOTH/ANUBIS micro-tools? | Open | THOTH / WOLFIE | 2026-04-11 | Split preferred for constitutional proven-code preservation and testability. |
| 2 | When is the first **auto-migration** of non-Priority-1 files allowed? (After all root PRDs + doctrine?) | Open | WOLFIE | 2026-04-11 | Proposed gate: 100% Priority 1 + 80% doctrine + Pattern #7 tooling live. **Evidence:** **§13.2 B** — **2026-04-11** dry-run still finds **1** of **56** root PRDs would normalize (**`51_memory_graph_as_source_of_truth.md`**). |
| 3 | Should **`content_id`** in headers point to the **memory_node_id** or remain optional until **PRD 51** inference is mature? | Open | ATHENA | 2026-04-11 | Current leaning: optional, populated only on confirmed mirror. |
| 4 | How aggressive should the validator be on **future-leak** timestamps during migration? (Hard quarantine vs warning) | Open | ANUBIS | 2026-04-11 | Proposal: Hard quarantine on canonical tier files, warning on staging. |
| 5 | Should we add a **Complexity Score** column to Patterns / Edge Cases for better multiplier transparency? | Open | ARA | 2026-04-11 | Low priority — keep simple unless metric gaming appears. |
| 6 | Who tests the testers? Who audits **COUNTERMEASURE** (**§16**) for quality and proportionality (not just volume of disagreement)? | Open | WOLFIE | 2026-04-11 | **LILITH** may review; no formal SLA in registry yet — ties to Q8. |
| 7 | How do we know when a pattern is truly **stress-tested** vs merely **documented**? | Open | ATHENA | 2026-04-11 | Propose gates: tool coverage, sample peel batch, red-team sign-off — still undefined. |
| 8 | What is the **SLA** (dependency-based, not calendar) for moving **§13.1** questions to **§13.2** or **§2**/**§3** resolutions? | Open | WOLFIE | 2026-04-11 | **Pattern #8** says scan each major pass; no enforcement hook yet. |
| 9 | How do we **validate** that imported **path / visit** rows correctly support **memory edges** (or graph projections) after Crafty import? | Open | KAIROS | 2026-04-11 | Tie to **Pattern #9** + post-import sampling; see **`analysis/wolfie/SUMMARY.md`**. |
| 10 | What is the **rollback** strategy if **`import_from_old_crafty_syntax.sql`** fails **mid-way** (after some `TRUNCATE`s)? | Open | ANUBIS | 2026-04-11 | Restore from backup; avoid partial re-run without doctrine; see **`crafty_data_analysis.md` §3.2**. |
| 11 | Should we import **all** historical path aggregates from a large Crafty site, or only a **recent window**? | Open | WOLFIE | 2026-04-11 | Product / storage tradeoff; sample dump in repo is not volume-authoritative. |
| 12 | How do we handle Crafty **legacy tables** that have **no** direct **`lupo_*`** target (or only DDL in sample dump)? | Open | ATHENA | 2026-04-11 | e.g. empty **`livehelp_channels`** / **`livehelp_messages`** in repo export — need policy for full customer DB. |
| 13 | What is the **performance** plan for importing **millions** of path/visit rows in one session? | Open | Cursor (**102**) | 2026-04-11 | Batching, indexes off/on, or staged imports — not defined in SQL file alone. |

### 13.2 Recently Closed Questions

| # | Question | Resolution | Date Closed |
|---|----------|------------|-------------|
| A | Can we run peel-first on root PRDs before full **Pattern #7** tooling? | Yes — **WOLFIE** authorized tactical batch (**§14.2**) | 2026-04-11 |
| B | What does a **Priority 1** **`normalize_lupopedia_md_header_25.py --dry-run`** show **right now**? | **2026-04-11** (packed **20260411171228** batch): **scanned=56**, **changed=1** (`docs/prd/51_memory_graph_as_source_of_truth.md`), **unchanged=55**, **skip_multi=0**, **skip_error=0**. | 2026-04-11 |
| C | What does **`kairos_edge_verification.py --test`** report for **`BREAKTHROUGH_REGISTRY.md`** when the graph row is missing? | **2026-04-11** **`--json`**: **`memory_node_id`**: **`null`**, **`issues`**: **`["no_active_memory_node"]`** — confirms **edge #6** / **Pattern #10** backlog until DB seed + edge refresh. | 2026-04-11 |

### 13.3 How to add or update an entry

1. Add a row to **§13.1** (Open) or **§13.2** (Closed).
2. Register as a **documentation update** (**§4**) when the change is material.
3. Link from the relevant Pattern, Edge Case, or guardrail section.
4. Update **§5** attribution (**+10** docs) when scoring the doc row.

<a id="134-open-qa-maintenance-pattern-8"></a>

### 13.4 Open Q&A maintenance (Pattern #8)

On **every major registry pass** (new **§2.1** / **§3.1** row, **§5** realignment, or material **§4.1** doc):

1. **§13** — Scan **§13.1** for stale owners or decisions overtaken by **§2** / **§3**; move closures to **§13.2** with date.
2. **Cross-links** — Ensure new patterns/edges are cited from **§11.1**, **§14**, or **§12** where operators expect them.
3. **Graph** — Apply **Pattern #10** + **edge #6** handling: verify **`kairos_edge_verification.py --test`** on this file and refresh **`lupo_memory_edges`** when DB is up.
4. **§16** — Apply **§16.6** (Countermeasure log maintenance) so red-team rows stay bounded and archived on schedule.

---

## 14. Strategic guardrails (ATHENA — hardened)

*Align with **constitutional** proven-code preservation: do not rewrite file bodies or bypass **PRD 16 / PRD 38** truth paths without mandate.*

1. **Body-fence rule** — Never alter content **after** the closing **`---`** of the **v4** fixed-position header envelope (**25th line** per **PRD 16**). Violation = **-500** (**§7.1**).
2. **Future-leak prohibition** — Packed UTC in headers **>** trusted current UTC (e.g. from **`python bin/tick.py`**) → **quarantine**; do not silently fix.
3. **ANUBIS loop-back** — Every **100** files migrated, sample **5**; run orphan detector (**Pattern #6**) and ghost sync (**Pattern #3**) when DB is reachable.
4. **Authority stack** (**PRD 38** + **PRD 51**):
   - Graph (**DB**) beats stale header/mirror when reconciling node payload.
   - Header governs **envelope** shape and **`file_path_from_root`** (**PRD 16**).
   - Inference (**PRD 51**) only where explicitly allowed; else **`needs_review`**.
   - **Human / WOLFIE** resolves conflicts and ambiguous trust tier.

### 14.1 Loop-back checklist (detail)

For each **§14** sample file, record pass/fail and **quarantine** failures (**edge #2** path):

- **Header to DB:** **`memory_key`** matches **`lupo_memory_nodes.memory_key`** (and PK / **`content_id`** consistent with **PRD 38**) — **Pattern #3**.
- **Trust / channel:** Header **`trust_tier`**, **`channel_key`**, **`federation_node_id`** do not contradict node context; overlaps **edge #4** when header says **canonical** but node is **staging**.
- **Mirror sanity:** If **`memory/...`** export exists, path shape is plausible — mirror **must not** be treated as SoT.
- **Timestamps:** No future-leak (**§14** guardrail 2).
- **Orphans:** **`detect_memory_graph_orphans.py`** (**Pattern #6**, **edge #3**, mirror drift).
- **Full reconciliation:** When **Pattern #7** tooling exists, schedule a **graph-reconciliation pass** after large batches (**§2.10**). Validator **PASS** requires **`drift_code == 0`** (**§14.3**, **§2.11**).
- Prefer **Pattern #5** when the graph write path is available.

### 14.2 Tactical peel batch — Priority 1 root PRDs (`docs/prd/*.md`)

**Status:** Authorized by **WOLFIE** (see **§13.2**, Question **A**). Dry-run command and full instructions below unchanged.

**Authorization:** **WOLFIE** (1) — **full go** (2026-04-11 UTC). **Strategy:** **Pattern #2** (peel-first) + **Pattern #5** where DB is reachable; **no body edits** beyond header envelope.

**Supported today** (`normalize_lupopedia_md_header_25.py`): **`--path`**, **`--dry-run`**, **`--check`**, **`--backup`**, **`--verbose`**, **`--target-version 4.0.99`**, **`--recursive`**, **`--include-py`**. Default glob **`docs/prd/[0-9][0-9]_*.md`** matches numbered root PRDs (same ~60 file set).

**Not implemented yet** (ARA tactical doc may cite them; extend the script before relying on them): **`--under`**, **`--report-json`**, **`--peel-aggressive`**, **`--peel-first`**, **`--create-memory-nodes-if-missing`**, **`--strict-header`**, **`--log-registry-updates`**.

<a id="144-migration-readiness-checklist-pre-peel"></a>

### 14.4 Migration readiness checklist (pre-peel)

Complete **before** the first **`--dry-run`** you treat as gate evidence (or annotate exceptions in commit):

- [ ] **`validate_lupopedia_headers_universal.py`** — pilot PRDs pass, or failures are **triaged** and **quarantined** with owner.
- [ ] **Pattern #11 stub** — **`pattern11_stub_body_fence_md`** (above) run on samples or merged into validator; **no** silent body mutation in normalize path.
- [ ] **Pattern #6** — **`detect_memory_graph_orphans.py --under docs/prd`** (**DB up**); baseline understood (**edge #3**, **`missing_node`**).
- [ ] **Pattern #9 / edge #5** — **`kairos_edge_verification.py --test`** on representative files when **DB up**; **`--stale-edges`** plan if large migration (**edge #7** if offline: **do not** claim graph-verified).
- [ ] **§14** guardrails — body-fence / future-leak policy acknowledged; **tick.py** UTC trusted for batch.
- [ ] **`--backup`** — agreed for real peel (**`*.bak`** per file) or alternative rollback (**git**).
- [ ] **Glob scope** — **`[0-9][0-9]_*.md`** (numeric PRDs only) vs **`*.md`** (full tree); **WOLFIE** confirms batch boundary.
- [ ] **ANUBIS loop-back** — **§14.1** sample **5** / **100** understood for post-batch.
- [ ] **HARD FREEZE** — **no** **additional** **§2.1** / **§3.1** / **§16.1–§16.4** rows until peel batch **complete** (**Purpose**); **Pattern #13** is already **WOLFIE**-excepted (**§2.16**). **§14.5** **single-file pilot** **before** wide batch.
- [ ] **Pattern #10** — schedule registry (**this file**) **memory_node** + **outbound edges** refresh after peel (**edge #6**).

**Dry-run (safe, no writes):**

```bash
python scripts/normalize_lupopedia_md_header_25.py --target-version 4.0.99 --dry-run --path "docs/prd/[0-9][0-9]_*.md"
```

**Full batch (after dry-run review):** same command without **`--dry-run`**; add **`--backup`** for **`*.bak`** per file. Rehydrate **§5** **Files** / **TOTAL** only for PRDs actually written.

**Optional — wide glob + verbose:** same executable; use **`--path "docs/prd/*.md"`** and append **`--verbose`** so each file emits **`[OK]`** / **`[DRY-RUN]`** / **`[SKIP]`**. The numeric glob above excludes **`PRD_INDEX.md`**, **`WHAT_TO_DO_NEXT.md`**, etc.; the wide glob includes them — confirm batch boundary with **WOLFIE** (**§14.4** checklist: **Glob scope**).

**Stdout — with `--verbose`:** per file, **`[OK] unchanged:`**, **`[DRY-RUN] would normalize:`**, **`[SKIP] … skip_multi`**, or **`[SKIP] … skip_error`**. Final summary line:

```text
Summary: scanned=N changed=C unchanged=U skip_multi=M skip_error=E
```

**Recorded runs:** **ARA** numeric dry-run (packed **`20260411171228`**) — same flags as the **Dry-run** block plus **`--verbose`** — matches **§13.2** question **B**: **scanned=56**, **changed=1** (`51_memory_graph_as_source_of_truth.md`), **unchanged=55**, **skip_error=0**. Wide-glob reference (repo state): **scanned=61**, **changed=2** (**`51_...`**, **`PRD_INDEX.md`**), **skip_error=1** (**`WHAT_TO_DO_NEXT.md`**); numeric-only glob typically **`skip_error=0`**.

**Post-batch validation:**

1. `python scripts/validate_lupopedia_headers_universal.py <file.md>` per file or wrap a directory walk (validator is single-path today; no **`--under`**).
2. `python scripts/detect_memory_graph_orphans.py --under docs/prd --json` when DB is up.
3. Optional (DB up, **KAIROS**): add **`--verify-edges`** to the normalize command so each written Markdown file runs **`verify_edges_for_file()`** (**Pattern #9**).
4. **ANUBIS** loop-back: **5** random samples from the batch (**§14.1**).

**Rollback:** `git restore docs/prd/*.md` or per-file **`*.bak`**.

**Dry-run report (versioned, not under ignored `logs/`):** [`prd_peel_dryrun_20260411.json`](prd_peel_dryrun_20260411.json) and [`prd_peel_dryrun_20260411.txt`](prd_peel_dryrun_20260411.txt).

**Pre-flight orphan baseline (`detect_memory_graph_orphans.py`, full `docs/prd` tree):** Packed UTC **`20260411154001`** — [`preflight_memory_orphans_prd_20260411154001.json`](preflight_memory_orphans_prd_20260411154001.json) (exit **1**, actionable) + [`.manifest.txt`](preflight_memory_orphans_prd_20260411154001.manifest.txt) (counts and notes). Establishes **Pattern #6** starting line before Priority 1 graph backfill.

**Version-folder pre-flight (registered):** Before heavy edits under **`docs/versions/4.0.99/`**, run **`detect_memory_graph_orphans.py --under docs/versions/4.0.99 --json`** (DB up) and archive JSON if policy requires; pairs with **edge #6** checks on **`BREAKTHROUGH_REGISTRY.md`**.

### 14.5 Single-file pilot (**Pattern #13** gate before batch)

**WOLFIE rule:** Do **not** run the **wide-glob** Priority 1 normalize/peel **batch** until **one** **Type B** file has gone through the full **close** loop without breaking **graph truth**, **header correctness**, **edge completeness**, or the **content bridge** story (**PRD 50** / **`lupo_contents`** / **`content_id`** alignment — future UI).

**Pilot proves (all explicit):**

- **Graph truth** — **`memory_key`** resolves to intended **canonical** identity; **TOON / mirror** path under **`memory/`** understood; **no** invented node claims.
- **Header correctness** — **PRD 16** envelope **PASS** after peel/normalize (**Pattern #2**, **Pattern #11** discipline).
- **Edge completeness** — **Outgoing / incoming** **`lupo_memory_edges`** expectations documented; **KAIROS** **`--test`** **green** when DB up or **edge #7** **honest deferral** (not silent **PASS**).
- **Content bridge** — Header **`content_id`** (when present) and **channel / thread** fields **consistent** with how the artifact should surface in **UI** via **`lupo_contents`** when product wires it.

**Pick one pilot path** (examples — **WOLFIE** chooses):

- A **single** root PRD under **`docs/prd/`** (e.g. already touched in dry-run: **`51_memory_graph_as_source_of_truth.md`**), **or**
- Another **low-blast-radius** **Type B** **`.md`** the operator names in the thread manifest.

**Pilot sequence (dependency order):**

0. **Origin** — Confirm **Type B** (**Artifact Origin Types**); **do not** use peel pilot to “fix” **Type A** observed rows.
1. **Thread** — Record **channel**, **thread**, **actor_id** in the work artifact (**PRD 02** / **PRD 17**); note **`content_id`** / graph context if already known (**PRD 51**).
2. **Dry-run only this file:**  
   `python scripts/normalize_lupopedia_md_header_25.py --target-version 4.0.99 --dry-run --path "docs/prd/NN_….md"`  
   (replace with the **exact** pilot path). Review stdout; **no** wide glob.
3. **Real write (one file):** same command **without** **`--dry-run`**, with **`--backup`** if policy requires.
4. **`validate_lupopedia_headers_universal.py`** on that path — **PASS** required (**header** leg).
5. **DB up:** **`kairos_edge_verification.py --test --file <path>`**; **`--verify-edges`** on normalize if used; verify **edge capture** against cited PRDs / patterns; document **`edge #7`** if DB down (**no** “graph-verified” claim).
6. **`git commit`** — pilot changes **only**; **no** batch commit mixing dozens of files.
7. **WOLFIE go / no-go** — if **PASS**, widen glob **incrementally** (e.g. next **N** files), **not** the whole tree in one shot until a second checkpoint passes.

**Completion criterion:** Pilot demonstrates **dual-origin discipline**: **graph + header + edges + content-bridge intent** are **honest** (or **edge #7** logged); **WOLFIE** records **go** for batch expansion.

### 14.3 Pattern #7 enforcement

The validator must enforce **`drift_code == 0`** for a file to **PASS**. See **§2.11** for required validator rule changes.

---

## 15. Last updated

| Field | Value |
|-------|-------|
| **Packed UTC** | `20260411234626` |
| **Wall (UTC)** | 2026-04-11 23:46:26 |
| **Updated by** | **SYNAPSE** (**117**) **§4.1** doc **#16** + **§5**/**§6** (**TOTAL** **16051**, **SYNAPSE** **1120**). Prior: **WOLFIE** dual-origin / **§14.5**; **ARA** **7480**. |
| **Next review** | After Priority 1 peel batch or each migration batch |

---

<a id="16-countermeasures-devils-advocate-log-red-team"></a>

## 16. Countermeasure's Devil's Advocate Log (Red Team)

**COUNTERMEASURE** (**actor_id** **111**) is designed to **disagree constructively**. This section captures stress tests, bypass hypotheses, and false-negative risks that other actors may under-weight. **COUNTERMEASURE does not propose new scored patterns** — it **critiques** entries in **§2**, **§3**, **§14**, and **§13**.

**No points** for **COUNTERMEASURE** — red-team output is operational hygiene, not breakthrough registration.

**Freeze (execution phase — HARD):** **No new rows** in **§16.1–§16.4** until the **Priority 1 peel batch completes** (same gate as **§2.1** / **§3.1**). **Severity** / **Resolution** on **existing** rows + **§16.6** archive only. **Pattern #11** closes **§16.3** via **validator** code — not new §16 prose. **WOLFIE** may override with explicit written exception.

### 16.1 Pattern stress tests

| Pattern | Countermeasure's critique | Severity | Resolution |
|---------|---------------------------|----------|------------|
| **#1** (Install-time seeding) | Bad **seed** ships to **every** fresh install; registry never mandates a **pre-release seed vs TOON** audit gate — operational truth is **installer trust**, not re-proven here. **Drift risk:** teams read **Pattern #1** as **universal** “headers at install for everything” — contradicted by **dual-origin** (**Type A** widget-first); **Pattern #1** is **import-entity** scope only. | Med | Pending |
| **#2** (Peel + atomic write) | **OS crash mid-write** without **temp + rename + fsync** discipline can still yield partial files; "atomic" is **implementation- and FS-dependent**, not guaranteed on all hosts. | Med | Pending |
| **#3** (Ghost sync) | Duplicate or ambiguous rows (**same** **`memory_key`**, different **`memory_node_id`**) → ghost sync may attach to the **wrong** node while appearing consistent. | Low | Pending |
| **#4** (Deterministic field order) | New **PRD 16** key → mass **FAIL** until **`header_spec`** bumps; teams can ship validator code **without** spec bump and get **false PASS** on stale CI. | Med | Pending |
| **#5** (Graph-first) | If **DB is unreachable**, the loop collapses to filesystem-first behavior the registry warns against — yet **§14.2** authorizes peel batches without **mandating** DB. Headers gain **`memory_key`** with **no** live node → graph-first risks becoming **narrative**, not **enforced**. | High | Pending |
| **#6** (Orphan detection) | Tool keys off **`memory_key`** + **`is_deleted`**; **edge #2** covers tier/channel mismatch. **Gap:** active node, **correct** key, **wrong** **`federation_node_id`** / **`channel_key`** vs header may **PASS** orphan scan and still be **wrong**. | Med | Pending |
| **#7** (Reconciliation) | **`drift_code == 0`** is **necessary** for PASS (**§2.11** / **§14.3**) but **not sufficient**: graph + inference could agree on the **wrong** payload (**false negative**). No requirement for **independent** verification (second tool, spot-check). | High | Pending |
| **#8** (Self-documenting registry) | **Pattern #8** expands mandatory cross-links (**§11.1**, **§13**, **§14**); under fatigue, updates get **skipped** — the registry **reads** disciplined while **drifting** (**edge #6**). | Med | Pending |
| **#9** (Auto edge verification) | **`verify_edges_for_file`** / **`--verify-edges`** may treat **zero outgoing edges** as acceptable if expectations are **underspecified** → **false PASS** after peel. | Med | Pending |
| **#10** (Registry self-seeding) | Text says **refresh after major edits**; process is **manual** → **edge #6** becomes the **default** state, not an exception. | High | Pending |

### 16.2 Edge case stress tests

| Edge case | Countermeasure's critique | Severity | Resolution |
|-----------|---------------------------|----------|------------|
| **#1** (Stacked headers) | **One** peel pass may be insufficient; deeply stacked legacy blocks need **iteration** until the **25-line** envelope is stable — stopping early yields a **plausible** header that still **fails** PRD 16 line discipline. | Low | Pending |
| **#2** (Memory-key mismatch) | **Typo** **`memory_key`** that matches **no** row vs **wrong** row: not all validators distinguish **missing** vs **foreign** node clearly — operator may get a generic **PASS** on envelope only. | Med | Pending |
| **#3** (Soft-deleted key) | Handling says **no auto-repair without orchestrator**. If orchestrator is **unreachable**, work **stalls** or someone **sidesteps** policy — **deadlock** vs **rogue fix** risk. | High | Pending |
| **#4** (Canonical header, staging node) | **Race:** file promoted in **Git** (**trust_tier: canonical**) before DB promotion completes → CI green, **graph** still staging — **authority stack** resolution is non-obvious under pressure. | Med | Pending |
| **#5** (Stale edges) | Tooling emphasizes **soft-deleted `to` nodes**. **Gap:** **`to`** node **active** but **re-keyed** / **merged** / identity **replaced** — edge not stale by **`is_deleted`**, still **wrong**. | Med | Pending |
| **#6** (Registry self-reference drift) | **IDE-only** workflows **without DB**: **`kairos_edge_verification.py --test`** never runs → **edge #6** invisible until someone connects to MySQL — false confidence in **Pattern #10** compliance. | Med | Pending |

### 16.3 Guardrail stress tests

| Guardrail | Countermeasure's critique | Severity | Resolution |
|-----------|---------------------------|----------|------------|
| **Body-fence** (**§14** / **§7.1**) | Rule assumes **line 25** is the closing **`---`**. If the envelope has **24** lines, a **blank** inside the grid, or a **second** opening fence, naive **first/last `---`** heuristics **mutate body** while "respecting" the fence. | High | Pending |
| **Future-leak** (**§14** guardrail 2) | Quarantine depends on **trusted** UTC (**`tick.py`**). **Host clock skew** → false quarantine or false accept; registry does not state **clock integrity** as a precondition. | Med | Pending |
| **ANUBIS loop-back** (**§14** sample **5** / **100**) | **n=5** is easy to **game** (ordering bias) or **miss** clustered defects — no statistical basis for comfort. | Low | Pending |
| **Authority stack** (**§14** guardrail 4) | **"Graph beats stale header"** fails if the graph row was **poisoned** by an earlier bad migration — **no** cryptographic bind between **file hash** and **node payload**; **self-consistency ≠ truth**. | High | Pending |

### 16.4 Countermeasure's disagreements (unresolved)

| Disagreement | Countermeasure's position | Registry / doctrine position | Status |
|--------------|---------------------------|------------------------------|--------|
| **§13 Q4** — future-leak strictness | **Warning-only** on canonical tier would reduce false quarantines when clocks drift; **hard** quarantine burns operator time. | **ANUBIS** proposal: hard quarantine canonical; warning staging (**§13.1**). | Open |
| **§13 Q3** — **`content_id`** | **Require** **`content_id`** whenever **`memory_key`** is set — **optional** invites **header–graph** drift the whole registry fights. | **ATHENA** leaning: optional until **PRD 51** mature (**§13.1**). | Open |
| **§13 Q6** — red-team oversight | **COUNTERMEASURE** can over-rotate into **noise**; need **LILITH** / **WOLFIE** triage so §16 does not become unbounded FUD. | Open question row **#6**; process **undefined**. | Open |

### 16.5 How to add a Countermeasure entry

1. **COUNTERMEASURE** (or delegate **Cursor** **102** under **countermeasure:root**) proposes a critique row in **§16.1–§16.4**.
2. **WOLFIE** sets **severity** and **resolution** (or defers).
3. If accepted, update the relevant **§2** / **§3** / **§14** / **§13** row or tooling — do **not** mint a new **§2.1** pattern for red-team work.
4. Mark **Resolution** **Resolved** with a pointer (PR, commit, or doc section).

<a id="166-countermeasure-log-maintenance-pattern-8"></a>

### 16.6 Countermeasure log maintenance (Pattern #8)

On **every major registry pass** (same trigger as **§13.4**):

- **COUNTERMEASURE** adds new critique rows only in **§16.1–§16.4** (or delegates **Cursor** **102** under **`countermeasure:root`**).
- **WOLFIE** / **ARA** triage **severity** and **resolution**; **LILITH** may audit proportionality (**§13.1** Q6).
- Critiques that produce **code**, **SQL**, or **tooling** changes must gain a pointer from the relevant **§2** / **§3** / **§14** row (or **§11.1** tool line) — **do not** let **§16** be the only trace.
- **Archive** rows that are **Resolved** and **unchanged for 30 days** (calendar guard for log volume only) into **`docs/versions/4.0.99/COUNTERMEASURE_ARCHIVE_YYYYMM.md`** (create file with **PRD 16** header on first use). Keep **§16** tables **current**; archives are **read-only** history.

<a id="167-lessons-stress-tests-9"></a>

### 16.7 Lessons stress tests (§9)

| Lesson | Countermeasure's critique | Severity | Resolution |
|--------|---------------------------|----------|------------|
| **#1** (Peel first) | Documented in **§9.1**; **enforcement** is still **human habit** — peel-first is **not** wired as a **hard** preflight in **`normalize_*.py`** for every entry path. | Low | Pending |
| **#2** (Double-header cascade) | True, but **detection** lag: validators may **PASS** single envelope while **mirror** or **DB** still holds duplicate legacy artifacts — **tri-surface** not always checked. | Med | Pending |
| **#3** (File-first trap) | **Pattern #5** is the fix on paper; **§16.1 #5** shows **DB-down** peel still happens — lesson is **learned in prose**, **violated in practice** when graph writes are skipped. | High | Pending |
| **#4** (Orphan accumulation) | **Pattern #6** helps; **§16.2 #6** shows **offline** runs never see DB orphans — lesson **assumes** DB-attached workflows. | Med | Pending |

---

**Registry** — **Pattern #8** operational; **Pattern #5** applied to this artifact (DB row + mirror + header sync). Prior **THOTH** / **ANUBIS** / **ATHENA** detail retained under **§2.2+** and **[extended](#extended)**.

**Immediate next actions (priority order — ARA consolidation):**

1. **Pattern #11 — Validator:** Draft **body-fence strict** enhancement targeting **§16.3** critique (envelope line discipline, unambiguous closing **`---`**, no body mutation); wire **CI** / **`validate_lupopedia_headers_universal.py`** so **red** cannot **PASS**.
2. **Priority 1 dry-run:** Complete **§14.4** first. Root PRDs — **single canonical copy** of peel **`normalize_…`** commands, **stdout Summary** format, **glob** notes, and **recorded runs** live in **§14.4** (not duplicated in the footer). **`--verify-edges`** runs **only after a write**, not with **`--dry-run`**; DB checks: **`kairos_edge_verification.py --test --file <path>`**.
3. **Pattern #10 / #12 / #13:** After dry-run / peel evidence, **refresh** this registry’s **`lupo_memory_nodes`** row + **outbound edges** (approved DB workflow). **Per migrated file**, run **§12.1** (**Pattern #12**) so **session reports** and PRDs do not sit at **`no_active_memory_node`** (**edge #6**). **Pattern #13:** no **phantom completion** — **commit** + **validator** + **graph** honesty before claiming **done**.
4. **§16:** **Frozen** for **new** critique rows until peel (**above**); **§16.6** hygiene only.

**Deferred (post-peel):**

- Full peel batch with **`--backup`**; increment **§5 Files** per migrated PRD.
- **`normalize_lupopedia_md_header_25.py`** **`--under`** / **`--report-json`** (**§14.2** tactical CLI).
- **`graph_reconciliation_pass.py`** stub (**Pattern #7**).

#### Peel commands (see §14.4 only)

Do **not** maintain a second bash block here — **§14.4** is the **single source** for Priority 1 **dry-run**, **wide glob + verbose**, **stdout Summary** shape, **numeric vs wide** glob notes, and **recorded run** metrics.

**Post-write (DB up):** **§14.4** *Post-batch validation* step 3 — run normalize **without** **`--dry-run`**, add **`--verify-edges`** so **KAIROS** runs after each file that was **written**.

*Canonical **breakthrough registry** and migration progress log. Update **§5** and the relevant registration table after each scored item.*
