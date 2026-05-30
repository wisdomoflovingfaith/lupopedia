---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/00_F-i_SUPREME_CONSTITUTIONAL_WALL_UNIFIED.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/00_F-i_SUPREME_CONSTITUTIONAL_WALL_UNIFIED.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/prd-00-unified-wall.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd-00-unified-wall
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: prd-00-unified-wall
  lupopedia.schema: prd
  prd_cluster: 00_A-i_00_B-i_00_C-i_00_D-i_00_E-i_00_F-i
  title: 'PRD 00 F: Supreme Constitutional Wall (Unified Merge)'
  summary: Single merged index of PRD 00 constitutional doctrine (identity, time, database, architecture, naming, filesystem, framework kapu, UI, survival, AGAPE/WHY, enforcement, development order, hostile input, SQL incl. 13.9 ASCII-only glyph ban, policy). Normative detail remains in 00_A-i through 00_E-i and 00_C-i.
---
# PRD 00 -- Supreme Constitutional Wall (Unified)

**Read FIRST in cluster.** This file merges narrative doctrine from **`00_A-i`**, **`00_B-i`**, **`00_C-i`**, **`00_D-i`**, **`00_E-i`**, and the Theory of Everything / dream rules where they are already mirrored in those files.

**Authority split:** Implementation specifics, rule IDs (**RULE 93.\***), installer paths, and class names remain canonical in **`docs/prd/00_C-i_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md`**. If this merge disagrees with **`00_C-i`**, **`00_C-i`** wins for implementation.

**Time format note:** Packed UTC uses **`YYYYMMDDHHIISS`** (year, month, day, hour, **minute**, second). **`II`** is minutes, not **`MM`** (month is already in positions 5-6).

---

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

## 0. Preamble -- Lupopedia canonical doctrine

Lupopedia is a **semantic operating system**, not a framework or documentation-only product. The PRD grid defines truth, behavior, limits, identity, and survival posture.

This constitution establishes deterministic identity, time, paths, architecture, federation, repair, and governance. This file is the **first PRD in every cluster** and overrides model priors where cluster order places it first.

### 0.1 Semantic operating system (definition)

Lupopedia is not a workflow builder, multi-agent playground, cloud orchestration layer, or dependency-heavy AI framework. It optimizes for **truth**, **governance**, **survivability**, **auditability**, and **constitutional order**.

### 0.2 Two-dimensional PRD grid

- **100** PRD groups (**00** through **99**).
- Each group has merge layers (**`00_A-i`**, **`00_B-i`**, ...).
- **`prd_cluster`** defines **sequential** reading order (a sequence, not a set).
- **`_A`** kills priors; **`_B`** core doctrine; **`_C`** derived; **`_D+`** extensions.

### 0.3 Theory of Everything (temporal interpretation, canonical)

Time does not exist. What we call time is the statistical correlation between independent probabilistic clocks. Packed **`YYYYMMDDHHIISS`** values are **labels for ordering within a reference frame**, not metaphysical time.

Semantic operators (**PONO**, **KULIANA**, **KAPU**, **SHADOW**, **PILAU**) define meaning relationships. The unified theory is expressed as **folder structure and constitutional rules**, not as a single equation.

### 0.4 Database vs filesystem separation (dream rule)

- **Database:** indexes (PKs, paths, relationships, trust tiers, channel keys, actor IDs, thread IDs, theme references).
- **Filesystem:** full content, TOON, memory, transcripts, binaries, and typically anything **larger than 255 characters** (see **`00_A-i`** Recent Canonical Additions and **`00_C-i`** for product-specific limits).

### 0.5 Filename PK doctrine (artifact filenames)

Canonical artifact filename shape (UTC, collision sequence):

`YYYYMMDD_HHIISS_0000.ext` (filename timestamp doctrine) **or** packed forms used in tree layouts -- see **`00_A-i`** Filename Convention and **`TIMESTAMP_DOCTRINE`** for **`YYYYMMDD_HHIISS`** in names.

The filename is a **canonical PK for artifacts** in the dream layout; do not rename or repurpose casually.

### 0.6 Folder structure doctrine (indices)

Canonical trees (channel, memory, edge, kuliana) are defined in **`00_A-i`** Folder Structure and **`00_B-i`**. They are constitutional for lineage and export layouts.

### 0.7 Themes -- dual use

| Theme | Edge use (two artifacts) | Meaning use (one artifact + actor) |
|-------|--------------------------|--------------------------------------|
| PONO | invariant between | what balance means to this actor |
| KULIANA | change between | what this means to that actor |
| KAPU | forbidden boundary | what is sacred to this actor |
| SHADOW | absence between | what is not present for this actor |
| PILAU | corruption | inverse of pono |

### 0.8 Constitutional purpose

This wall exists to kill priors, prevent hallucination and drift, prevent schema and timestamp corruption, prevent header and cluster errors, prevent framework contamination, and preserve truth and lineage.

---

## 1. Identity and time model

### 1.1 Identity layers (summary)

- **Agents:** file-backed under **`agents/{agent_key}/`**; DB holds runtime metrics, not authoritative definitions.
- **Actors:** **`lupo_actors`**; **`actor_id`** is the orchestration key; relationships use **`actor_id`**, not a universal parallel **`user_id`**.
- **Auth users:** **`lupo_auth_users`**; humans **lease** actors; do not conflate login with orchestration identity.

**Permission sketch:** An **`auth_user`** may use an **`actor`** if they created it, are department 0 (root), or share the actor's department. Resolve **server-side**; never trust client **`actor_id`**.

**Normative detail:** **`docs/prd/15_actors.md`**, **`docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`**, **`database/lupopedia/actors/actor_id/registry.json`**.

### 1.2 Primary keys (IdGenerator, eighteen-digit **order** key)

- Table PKs: bare **`BIGINT`**, from **`IdGenerator::generate()`**, **`YYYYMMDDHHIISS`** + **NNNN** (**0000**-**9999**), **eighteen decimal digits** total. **No** SQL display width such as **`BIGINT(18)`** in DDL.
- Names: **`<singular_table>_id`**. Never bare **`id`**. FK columns reuse referenced PK names.

**Kapu of Time:** PK values are **ORDER**, not clocks. Never parse into calendar parts; never pass to **`DateTime`**; never treat as **`created_ymdhis`** / **`updated_ymdhis`** / **`deleted_ymdhis`**.

### 1.3 Timestamps (fourteen-digit UTC **clocks**)

- Lifecycle clocks: bare **`BIGINT`**, packed UTC **`YYYYMMDDHHIISS`**, **fourteen decimal digits**.
- Canonical names include **`created_ymdhis`**, **`updated_ymdhis`**, **`deleted_ymdhis`**.

**Forbidden:** **`DATETIME`**, **`TIMESTAMP`**, Unix epoch in lineage clocks, **`NOW()`**, **`CURRENT_TIMESTAMP`**, DB auto-update of clocks, embedding timezone in the integer.

**Timezone:** display and API only; storage is **UTC packed WHEN**, not WHERE.

### 1.4 Single interpreter for packed UTC math

All parsing, components, and arithmetic on **fourteen-digit** **`YYYYMMDDHHIISS`** **must** use **`timestamp_ymdhis`** in **`includes/classes/TimestampYmdhis.php`** (see **PUBLIC API** in file header).

**Forbidden:** ad hoc parsers; naive **`+ 86400`** on packed digits.

### 1.5 Runtime and Y2038

- **Production:** **PHP 7.4+**, **64-bit** (`PHP_INT_SIZE === 8`).
- **Legacy / 32-bit:** packed integers may exceed **`PHP_INT_MAX`**; **`timestamp_ymdhis::runtimePackedUtcIntSafe()`** is diagnostic only. Upgrade runtime for correct math.

### 1.6 Database generation kapu (IDs and clocks)

No **`AUTO_INCREMENT`** / **`SERIAL`**. No triggers, procedures, functions, FKs, cascades, generated columns, or automatic timestamp mutation. All IDs and **`*_ymdhis`** written by the application. Integrity and cascades in PHP. See **`00_C-i`** **section 3.3** (Kapu of the Database).

### 1.7 Timestamp forbidden patterns (from **`00_A-i`**)

- Never treat fourteen-digit storage as host **`DateTime`** objects for **storage semantics**.
- Never extract components **outside** **`TimestampYmdhis.php`**.
- Never conflate eighteen-digit PKs with fourteen-digit timestamps for lifecycle rules.

### 1.8 User ID space (**`00_A-i`** section 14, **PRD 79** authoritative)

Do not infer auth partition. Canonical bands are defined in **PRD 79** and atoms; do not place reserved IDs into **`lupo_crafty_user_mapping`** incorrectly.

### 1.9 Philosophy (canonical)

"Time does not exist. What we call time is the statistical correlation between independent probabilistic clocks."

**PK IDs from `IdGenerator` are not clocks. They are order.**

---

## 2. Database doctrine (dumb storage)

The database is **vault storage**, not logic. All intelligence lives in PHP.

### 2.1 Forbidden database "thinking"

**Forbidden:** FKs, triggers, procedures, scheduled DB events, **`AUTO_INCREMENT`**, **`ON UPDATE CURRENT_TIMESTAMP`**, generated/virtual columns, and any hidden mutation. (Portable **`CHECK`** is also avoided; see **`00_C-i`** / **`80_database_design_doctrine.md`**.)

### 2.2 Database vs filesystem

Same as **0.4**; large or authoritative content lives on disk; DB points.

### 2.3 Deterministic inserts

Every **`INSERT`** lists **all** columns explicitly. **Forbidden:** positional **`INSERT INTO t VALUES (...)`**.

### 2.4 Prepared statements

All SQL uses **prepared statements** with **bound parameters** (project **`PDO_DB`** pattern). **Forbidden:** string-concatenated values in SQL.

### 2.5 Application-layer integrity

Orphans may exist temporarily; repair and validation in PHP; federation merges must not rely on FK enforcement.

### 2.6 ID generation

Deterministic **`IdGenerator`** pattern only (**section 1.2**).

### 2.7 ASCII filesystem artifacts

Structural / parser-facing artifacts **ASCII-only** per **`AGENTS.md`** and ASCII doctrine.

### 2.8 Path and newline safety in structural fields

No **`../`**, **`./`**, **`~/`**, backslashes, absolute server paths, or URL prefixes in structural coordinates. Many structural header and routing fields must reject embedded newlines. See **`00_C-i`** hostile input / path purity rules.

---

## 3. Architecture doctrine (survival over fashion)

Built for shared hosting: no guaranteed cron, shell, Composer, Node, Redis, or workers.

### 3.1 Dependencies

**Shipped runtime:** no Composer **`vendor/`**, no npm in core request path. Tooling may use package managers **off** the runtime path (see **`00_C-i`** and **TWO_LAYER_SECURITY** doctrine).

### 3.2 Autoload

**Allowed:** Lupopedia **`spl_autoload_register()`** mapping to explicit project paths (**`00_C-i`** **section 8**). **Forbidden:** Composer autoload, PSR-4 "magic" trees for **`App\Services\...`**, framework containers.

### 3.3 No Laravel-style frameworks

No middleware stacks, **`Illuminate\*`**, ORM magic, or framework lifecycle. Plain PHP entrypoints and explicit includes.

### 3.4 Explicit execution

Request paths traceable from bootstrap; no hidden pipelines.

### 3.5 Routing

Plain PHP routing (**`lupo_route_slug`** and friends per **`00_C-i`** / **PRD 28**). No framework routers.

### 3.6 No "temporary" hacks

Undocumented shortcuts are doctrine debt; prefer PRD-first pipeline (**section 11**).

### 3.7 Semantic OS

Finite PRD grid, clusters, Truth Stack, WHY protocol, deterministic identity/time/path.

### 3.8 AGAPE / WHY causal discipline

Violations yield **`docs/why/`** records and doctrine updates; see **section 9**.

### 3.9 No hidden systems

No undisclosed telemetry or undocumented background behavior required for correctness.

---

## 4. Naming doctrine

### 4.1 Underscores are sacred

Do not eat, merge, or "beautify" underscores in **`prd_cluster`**, PRD stems, or load-bearing identifiers.

### 4.2 PRD number range kapu

PRD groups **00**-**99** only. No **`PRD 100+`** namespaces in **`docs/prd/`** layout. Pressure resolves inside a group via suffix letters / merge files / governance.

### 4.3 Cluster sequence

**`prd_cluster`** order is authoritative; no sorting or compressing identifiers.

### 4.4 File naming

Canonical filenames follow repo patterns such as **`NN_Suffix-i_TITLE.md`** (see existing **`docs/prd/`**); do not invent parallel naming schemes.

### 4.5 Merge and sub-PRD naming

Follow **`00_B-i`** and **PRD 16**; examples in older drafts using `16Aheaders.md` are **non-normative**; this repo uses underscored stems.

### 4.6 Header fields

**PRD 16** twenty-two-field order is frozen; validators enforce shape.

---

## 5. Filesystem and installation doctrine

### 5.1 Coordinates

**`file_path_from_root`** and **`web_path`** are cross-agent coordinates. No filesystem **`../`** escapes in structural fields.

### 5.2 Subdirectory install

Lupopedia installs under a subdirectory, not assumed web root (**`00_C-i`** section 2).

### 5.3 Folder trees

Channel / memory / edge / kuliana trees per **`00_A-i`** and **`00_B-i`**.

### 5.4 Artifact filename PK

Per **0.5** and **`00_A-i`**; never casual renames.

### 5.5 ASCII and path safety

Per **2.7** / **2.8** and **`AGENTS.md`**.

### 5.6 Single separator

Use **`/`** in repo paths in docs; avoid backslashes in structural fields.

---

## 6. Framework and dependency kapu

### 6.1 Frameworks are not the substrate

No Laravel/Symfony-style application frameworks in the runtime.

### 6.2 Absolute kapu (runtime)

No Composer **`vendor/`** in production tree, no ORM, no framework DI/middleware routing. See **`00_E-i`**.

### 6.3 Allowed autoload

**`spl_autoload_register()`** only, per **`00_C-i`** **8.3** (no PSR-4 Composer autoload).

### 6.4 Controlled PHP namespaces (**`00_C-i`** **section 8**)

Namespaces **are** allowed **only** under constrained rules: typically **`Lupopedia\...`** mapped under **`includes/`**, no **`App\`**, no framework patterns, no DI/middleware routing via namespaces. **This overrides** any "no namespaces anywhere" draft language.

### 6.5 Migrations

**4.0.x:** schema changes belong in canonical **install** SQL; no Lupopedia-to-Lupopedia upgrade chain until **4.2.0+** per **`00_C-i`** **section 1.0**. **`safe-migrate.php`** is the governed runner when a migration file is explicitly allowed for an environment.

### 6.6 No routing engines / lifecycle ceremonies

Explicit PHP includes and handlers only.

---

## 7. UI doctrine (command center, not social feed)

### 7.1 Chronological log

Primary feed is **one column**, oldest-to-newest, monospace, UTC-oriented display per **PRD 02** / **`00_C-i`** UI layers.

### 7.2 Forbidden social patterns

No engagement-optimized social feeds, bubbles-as-product, or gamified retention UX on constitutional surfaces.

### 7.3 Channels and threads exist

**Threads** and **channels** are **data structures** and indexes, not "social threading UI." Do not confuse **PRD 02** threads with nested comment UX.

### 7.4 Strict display time

Prefer explicit packed UTC or clearly labeled UTC strings; avoid fuzzy relative time in constitutional operator surfaces where PRDs forbid it.

### 7.5 Monospace

Operator surfaces use monospace discipline per **PRD 02** / **`00_C-i`**.

---

## 8. Survival doctrine

### 8.1 Survival over elegance

Prefer portability, determinism, explicit repair paths.

### 8.2 Degraded environment

Assume missing cron, missing extensions, restricted hosts.

### 8.3 Idempotent and retry-safe operations

Where feasible, design for safe retries and partial failure.

### 8.4 Opportunistic maintenance

GC and housekeeping may be triggered by requests/admin actions, not hidden daemons, unless explicitly documented.

### 8.5 Immune posture

Validators and header enforcement reject drift (**PRD 86** aligns with immune metaphors; see **`00_C-i`** **RULE 93** family).

### 8.6 ASCII as transport hygiene

ASCII for structural artifacts reduces encoding failure.

### 8.7 Deterministic repair

Repair procedures must be PRD-grounded and logged, not silent AI guesswork.

### 8.8 Federation safety

No reliance on DB FKs for merge; use deterministic IDs and application merge rules.

### 8.9 Multi-agent safety

Deterministic identity, time, paths, and cluster order reduce cross-agent corruption.

---

## 9. AGAPE and reactive WHY protocol

### 9.1 Purpose

Turn violations into durable **`docs/why/`** artifacts; strengthen doctrine; prevent recurrence.

### 9.2 On validation failure

Reject output; write WHY; log; cite rule; recommend fix.

### 9.3 WHY file shape

Directory **`docs/why/`**; naming **`why_YYYYMMDD_HHMMSS_<prd_cluster>_<slug>.md`** (see **`00_A-i`** **section 11**).

### 9.4 Causal reconstruction

Identify intent, actor, artifact, path, time, and violated rule; load cluster in order.

### 9.5 Living doctrine

Patterns in WHY files inform amendments.

### 9.6 Mandatory documentation

**Forbidden:** silently fixing constitutional violations without a WHY trail when process requires it.

---

## 10. Constitutional enforcement and federation

### 10.1 Truth Stack (order)

Highest to lowest: constitutional doctrines and **PRD 00** cluster; **atoms**; current explicit user instruction (bounded); **`prd_cluster`** files in order; **`memory_toon`**; **`transcript_jsonl`**. See **`00_A-i`** Truth Stack template.

**Atoms vs PRDs:** when they conflict, **atoms** win (per **`00_A-i`**).

### 10.2 PRD primacy and suffix layers

Sequential reading; **`_A`** / **`_B`** / **`_C`** roles per **`00_B-i`**.

### 10.3 Anti-hallucination

No invented clusters, no timestamp conversion, no underscore eating, no missing-doctrine inference as permission.

### 10.4 Federation rules

Deterministic merges without FK/trigger dependence; rely on PK discipline and application merge.

### 10.5 Modal language

This unified wall states **MUST** / **FORBIDDEN** for constitutional boundaries. Individual PRDs may still use **SHOULD** where explicitly scoped; do not "soften" **KAPU** rows without a PRD amendment.

### 10.6 No hidden behavior

Security and lineage logic stay visible in PHP and PRDs.

---

## 11. Development order (PRD to public code)

**Binding pipeline:**

1. **PRD** updated first (behavior documented).
2. **Schema** aligned with **`install_new_lupopedia.sql`** / TOON / JSON mirrors per **`00_C-i`** / **`80`**.
3. **Mockups** where UI changes require them (**`AGENTS.md`** PRD-first).
4. **Public PHP/Python** last.

**Forbidden:** shipping behavior not reflected in governing PRDs.

---

## 12. Hostile input doctrine

### 12.1 All external input is hostile

Treat **`$_GET`**, **`$_POST`**, JSON bodies, headers, cookies, uploads, and model output as untrusted until validated.

### 12.2 SQL

Prepared statements + bound parameters only.

### 12.3 Structural fields

Reject path traversal, URL prefixes, and illegal newlines in newline-sensitive structural fields.

### 12.4 Normalization

Trim, type-check, allow-list where required; ASCII policy for structural syntax.

### 12.5 Agents must validate their own outputs

Before persisting or forwarding, re-validate machine-generated structured values.

---

## 13. Explicit SQL doctrine

### 13.1 Explicit columns

**`INSERT`** / **`UPDATE`** name every written column. **`SELECT`** should list required columns explicitly (avoid **`SELECT *`** in shipped code paths per **`00_C-i`** discipline).

### 13.2 Time in SQL

No **`NOW()`**, **`CURRENT_TIMESTAMP`**, **`FROM_UNIXTIME`**, or vendor datetime types for canonical clocks.

### 13.3 PK vs time

Never use PK columns as **`YYYYMMDDHHIISS`** clocks.

### 13.4 No FK/trigger magic

Per **section 2**.

### 13.5 Deletes

Prefer **soft delete** pattern (**`is_deleted`**, **`deleted_ymdhis`**) for lineage tables per **`00_C-i`**. Hard deletes only where PRDs explicitly allow.

### 13.9 Emoji and non-ASCII glyph ban

**FORBIDDEN:**

- Emoji characters (**U+1F300** through **U+1FAFF**).
- Box drawing, Unicode arrows, smart quotes, typographic dashes, and **any** code point outside **U+0020** through **U+007E** in normative text artifacts.
- Encoded emoji aliases (for example surrogate escapes such as **`\uD83D\uDE00`**) in **JSON** or **TOON** payloads where validators enforce ASCII.

**REQUIRED:**

- All text artifacts are **strictly ASCII** for shipped / constitutional / parser-facing content.
- Validation scripts **must** reject any byte sequence with value **greater than 0x7E** where ASCII-only scope applies (see **`AGENTS.md`** ASCII-only doctrine).
- Replace expressive symbols with plain ASCII equivalents: **`(:)`**, **`->`**, **`--`**, **`#`**, **`[OK]`** / **`[FAIL]`**, and similar.

**Doctrine intent:** Prevent parser corruption, keep hashing and diffs deterministic, and preserve constitutional readability on all hosts and terminals.

This rule is **not optional**. It supports Lupopedia temporal and structural integrity (no hidden encoding state in lineage artifacts).

**See also:** **`AGENTS.md`** (LILITH / constitutional ASCII-only mandate).

---

## 14. System policy and non-negotiable rules

### 14.1 No hidden systems

Per **3.9**.

### 14.2 No engagement manipulation

Operator truth surfaces are not optimized as attention products.

### 14.3 No invented doctrine

Missing spec means **not allowed**, not "free to invent."

### 14.4 Silent corrections

Constitutional drift fixes require documentation (often WHY + PRD).

### 14.5 AGAPE / validators

Do not bypass governed enforcement surfaces defined in PRDs and scripts.

### 14.6 Truth Stack and pipeline

Do not reorder stack truth or skip PRD/schema/mockup/code ordering without maintainer decision recorded in PRD.

---

## Revision note

| Date | Change |
|------|--------|
| 20260510103209 | Initial **`00_F-i`** unified Supreme Constitutional Wall merge; time format **`YYYYMMDDHHIISS`**; PK/timestamp digit doctrine; namespace rule aligned with **`00_C-i`** **8**; authority clause defers implementation to **`00_C-i`**. |
| 20260510110527 | Added **13.9** Emoji and non-ASCII glyph ban (ASCII **U+0020**-**U+007E** only; validator rejection above **0x7E**; **`AGENTS.md`** cross-ref). |
