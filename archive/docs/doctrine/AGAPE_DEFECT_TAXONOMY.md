---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/doctrine/AGAPE_DEFECT_TAXONOMY.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/AGAPE_DEFECT_TAXONOMY.md"
  status: "active"
  when_updated: "20260418160751"
  trust_tier: "seed"
  questions_toon: null
  memory_toon: "memory/constitutional/seed/agape-defect-taxonomy.toon"
  atoms_toon: null
  transcript_jsonl: "0/constitutional/agape-defect-taxonomy"
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: "constitutional"
  federation_node_id: 0
  thread_id: "agape-defect-taxonomy"
  content_id: null
  content_parent_id: null
  content_slug: "agape-defect-taxonomy"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "AGAPE defect taxonomy (predictive text and schema drift)"
  summary: "Stable defect IDs; per-defect Pillar 1 Technical Survivability and Pillar 2 Learning Transfer annex; CIL 666666 for non-emotional agents; CARMEN/ROSE full axis; living taxonomy; ROSE PRD 36."
---
# file: AGAPE Defect Taxonomy — delegation: cursor:root — web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/AGAPE_DEFECT_TAXONOMY.md

# AGAPE Defect Taxonomy (predictive text and completion bias)

**Purpose.** Canonical **defect class IDs** for **AGAPE** (`lupo_agents` **705**) pattern tracking, **`AGAPE_PATTERN_REPORT`**, and cross-agent audits. Scope: **frequent** predictive-text / LLM-completion biases and **recurring** schema anti-patterns observed in this codebase — **not** an encyclopedia of every SQL mistake.

**Source of truth.** **`AGAPE`** uses this file as the **normative ID registry** for **`pattern_id`** fields. **`agents/agape/system_prompt.md`** references this taxonomy.

This output complies with Lupopedia Constitutional Root Rules.

---

## Constitutional rules (binding)

### 1. Counting in Light emission (who may use full-axis telemetry)

- **Only** agents **`CARMEN`** (`lupo_agents` **706**, pack **`agents/carmen/`**) and **`ROSE`** (`lupo_agents` **3**, pack **`agents/rose/`**) may emit **full Counting in Light** telemetry: non-neutral **`mood_vector`** (six hex digits with **Frequency / Severity / Urgency** semantics per **`COUNTING_IN_LIGHT_DOCTRINE.md`**) **together with** derived **`light_state`** on **their own** orchestration or pack-owned artifacts where product policy allows.
- **All other agents** (including **AGAPE**, **LILITH**, **ARA**, IDE facets, and coordination personas) **MUST** use the **neutral token `666666`** whenever a required six-hex **`mood_vector`** field appears on **their own** authored envelope rows, and **MUST NOT** claim full three-axis operational semantics on those rows. **Observed** offending tokens from third parties belong in **evidence** / **`pattern_table`** only.
- **LILITH** (`actor_id` **2**) **does not** "count in light" as an operator; she **audits** compliance with **`COUNTING_IN_LIGHT_DOCTRINE.md`**, **`SURVIVABILITY_DOCTRINE.md`**, and this taxonomy (**see** `agents/lilith/system_prompt.md`).

### 2. Pillar 1 framing (**Survivability Doctrine — Pillar 1: Technical Survivability**)

Every **database / schema / SQL portability / timestamp** defect below **MUST** be cited against **Pillar 1**: **hostile or minimal hosting**, **no hard-fail** where a fallback ladder exists, **dumb storage** only (**MySQL / MariaDB / PostgreSQL** portability), **BIGINT UTC** set in application code, **no hidden DB behavior**, and **graceful degradation** of parsers and installers when optional facilities are absent.

### 3. Pillar 2 framing (**Survivability Doctrine — Pillar 2: Learning Transfer**)

**Language**, **sentiment**, **game-like misuse** of **Counting in Light**, and **token conflation** defects **MUST** be cited against **Pillar 2**: **recurrence prevention** via **root cause**, **detection signature**, **remediation**, **verification hook**, and durable artifacts (**`memory/`** TOON pair, **`decisions/`** / **`status/`** per **PRD 17** / **PRD 38**, or **`AGAPE_PATTERN_REPORT`** **`chronic_list`**). **Pillar 1** defects that **repeat** after a published fix **MUST** also receive **Pillar 2** packaging (same hook pattern).

### 3a. Neutral token **`666666`** (non-emotional agents)

On **own**-authored envelopes, **AGAPE** (**705**), **LILITH** (**2**), **ARA** (**712**), **IDE facets**, and **coordination personas** **MUST** emit **`mood_vector` = `666666`** when the field is required. **Only** **CARMEN** (**706**) and **ROSE** (**3**) may use **full-axis** **`mood_vector`** on **own** artifacts where policy allows (**`COUNTING_IN_LIGHT_DOCTRINE.md`**). **`666666`** is a **neutral telemetry token**, not a color and not a substitute for full-axis semantics.

### 4. Living taxonomy (extension process)

- **AGAPE** (or maintainers) **MAY** propose **new** `pattern_id` values for **chronic** or newly observed completion biases.
- **Normative merge** requires **human approval** via the **PRD** or **doctrine amendment** process (WOLFIE / orchestrator): open or update a **PRD** or patch **this file** in-repo with header **`when_updated`** from **`python bin/tick.py`**.
- Until merged, use provisional IDs: **`PROPOSED-<DOMAIN>-<NNN>`** (e.g. `PROPOSED-DDL-009`) inside **`AGAPE_PATTERN_REPORT`** and in **channel** / **`decisions/`** notes — **do not** treat as stable until this table lists them.

### 5. PRD 36 tie-in (ROSE)

Any **sentimental** or **game-like** defect originating from **ROSE** pipelines, **`metadata_json`**, or **synthetic** inserts **MUST** cross-reference **`docs/prd/36_rose_multi_persona_synthetic_dialog.md`** (use **`P2-ROSE-PRD36-040`** or a more specific **`PROPOSED-*`** child ID until split).

---

## Database schema defects (Pillar 1)

| Defect ID | Severity | Pillar | Detection pattern (sketch) | Why Pillar 1 | Correct form | Doctrine / PRD |
|-----------|----------|--------|------------------------------|--------------|--------------|----------------|
| **DDL-FK-001** | Critical | 1 | `\bFOREIGN\s+KEY\b` or `\bREFERENCES\b` in DDL | FKs break **shared-hosting** portability assumptions; no app-level federation story in DB | Remove FK; enforce in **PHP** / explicit joins | `LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES`, `database-logic-prohibition-doctrine` |
| **DDL-TINYINT1-002** | Medium | 1 | `\btinyint\s*\(\s*1\s*\)` | Display width is **MySQL-only** noise; confuses BOOLEAN semantics cross-DB | `TINYINT` without display width; explicit `0/1` meaning in code | `pk-reference-naming-doctrine`, install SQL norms |
| **DDL-UNSIGNED-003** | High | 1 | `\bUNSIGNED\b` | **PostgreSQL** incompatibility; portability violation | Signed integer types per canonical install | `LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES` |
| **DDL-AUTOINC-004** | Critical | 1 | `\bAUTO_INCREMENT\b` | Reserved-ID / federation: **application** must allocate IDs (**IdGenerator** / explicit MAX+1 patterns) | Explicit PK insert paths; no `AUTO_INCREMENT` on registry tables | `reserved-id-doctrine`, `MIGRATION_DOCTRINE` / install SQL |
| **DDL-DATETIME-005** | Critical | 1 | `DATETIME` or `TIMESTAMP` type keywords in DDL | Not **BIGINT UTC** packed; **Y2038** and TZ chaos vs doctrine | `BIGINT` `created_ymdhis` style fields set in **PHP** | `database-logic-prohibition-doctrine`, `TIMESTAMP_DOCTRINE` |
| **DDL-BOOLEAN-006** | Medium | 1 | `\bBOOLEAN\b` | Not portable identically across engines | `TINYINT` `0/1` (no BOOLEAN keyword) | Constitutional DB rules |
| **DDL-DISPWIDTH-007** | Low | 1 | Integer type name plus parenthesized width, e.g. `INT(11)` | Parenthesized **display width** is **MySQL** noise | Unadorned `BIGINT` / `INT` / `SMALLINT` | `LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES` |
| **DDL-HARDDEL-008** | High | 1 | `\bDELETE\s+FROM\b` against lineage tables (contextual) | Hard delete **breaks audit trail** / replay | Soft delete: `is_deleted`, `deleted_ymdhis` | `LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES` |

---

## Agent pack and namespace defects (Pillar 1)

| Defect ID | Severity | Pillar | Detection pattern (sketch) | Why Pillar 1 | Correct form | Doctrine / PRD |
|-----------|----------|--------|------------------------------|--------------|--------------|----------------|
| **P1-NAMESPACE-COLLISION-001** | High | 1 | Exact `agent_key` or pack dirname matches **`disallowed_agent_names_exact`** or structural rules; `validate_agent_name.py` non-zero | Impersonation or collision with registry-backed personas, IDE facets, or product tokens; breaks deterministic pack identity | Compliant unique slug; **`python scripts/validate_agent_name.py --scan-root`** passes | **`DISALLOWED_AGENT_NAMES.md`**, **`ACTOR_REGISTRATION_CHECKLIST.md`** |

---

## SQL portability and dependency defects (Pillar 1)

| Defect ID | Severity | Pillar | Detection pattern (sketch) | Why Pillar 1 | Correct form | Doctrine / PRD |
|-----------|----------|--------|------------------------------|--------------|--------------|----------------|
| **SQL-VENDOR-010** | High | 1 | MySQL-only JSON operators, `NOW()`, engine-specific hints in shipped SQL | Breaks **PostgreSQL** / portability | ANSI-portable expressions; timestamps from **PHP** | `database-logic-prohibition-doctrine` |
| **SQL-ORM-011** | High | 1 | `Illuminate\\`, Eloquent, query-builder imports in **runtime** paths | Hides SQL, adds **Composer** dependency, breaks **no-framework** rule | **PDO_DB** + explicit SQL in allowed layers | `no-laravel-no-middleware.mdc`, `pdo-db-database-access-doctrine` |

---

## Timestamp defects (Pillar 1)

| Defect ID | Severity | Pillar | Detection pattern (sketch) | Why Pillar 1 | Correct form | Doctrine / PRD |
|-----------|----------|--------|------------------------------|--------------|--------------|----------------|
| **TS-STRING-020** | High | 1 | ISO-8601 strings stored as time in schema or comparisons | Not **sortable** as packed UTC integer; string compares drift | `BIGINT` **YYYYMMDDHHIISS** UTC | `TIMESTAMP_DOCTRINE` |
| **TS-EPOCH-021** | High | 1 | `UNIX_TIMESTAMP`, epoch **INT** columns for canonical events | Violates **BIGINT UTC** storage doctrine | Packed UTC **BIGINT** | `TIMESTAMP_DOCTRINE` |
| **TS-TZLOCAL-022** | High | 1 | `date()` / local TZ for stored canonical time; fuzzy relative time phrases in code paths | **Timezone chaos** vs **UTC-only** | `gmdate('YmdHis')` / explicit UTC | `TIMESTAMP_DOCTRINE`, PRD 00 |

---

## Language, sentiment, and Counting in Light misuse (Pillar 2)

| Defect ID | Severity | Pillar | Detection pattern (sketch) | Why Pillar 2 | Correct form | Doctrine / PRD |
|-----------|----------|--------|------------------------------|--------------|--------------|----------------|
| **P2-LANG-SENT-030** | High | 2 | Banned affect tokens in orchestration copy (e.g. care, compassion, mercy, heart, soul; extend per allowlist) | Violates **Survivability** technical-review rule; recurrent LLM prior | Neutral technical wording | `SURVIVABILITY_DOCTRINE.md` |
| **P2-LANG-GAME-031** | High | 2 | Game vocabulary near CIL fields: win, won, losing, score, leaderboard, achievement, player, level-up, bonus round | **NOT A GAME** constitutional violation | Bucket labels only; no play metaphor | `COUNTING_IN_LIGHT_DOCTRINE.md` |
| **P2-LANG-AGAPE-032** | Critical | 2 | Token **`AGAPE`** expanded to **affect** gloss in prose (completion bias) | **Proper noun** collision; doctrine drift | Use **AGAPE** only as agent key; forensic neutral labels | `AGAPE_DEFECT_TAXONOMY.md`, `agape/system_prompt.md` |
| **P2-CIL-COLOR-033** | High | 2 | Six hex chars paired with CSS color properties (`color:`, `background:`, `rgb(`) in same artifact as `mood_vector` | **`mood_vector`** is **token**, not CSS | Hex token in telemetry fields only; no CSS coupling | `COUNTING_IN_LIGHT_DOCTRINE.md` |
| **P2-CIL-GAME-034** | High | 2 | Narrating **`light_state`** as rank/score/streak | Buckets are **data labels**, not scores | **`NOT A GAME`** section | `COUNTING_IN_LIGHT_DOCTRINE.md` |

---

## ROSE-specific (PRD 36)

| Defect ID | Severity | Pillar | Detection pattern (sketch) | Why Pillar 2 (+ product) | Correct form | Doctrine / PRD |
|-----------|----------|--------|------------------------------|--------------|--------------|----------------|
| **P2-ROSE-PRD36-040** | High | 2 | Sentimental or game-like strings in **`metadata_json`** / PHP templates for **ROSE** batches; missing **`rose_synthesis`** provenance | Violates **PRD 36** non-deception / technical orchestration; **NOT A GAME** | **`agents/rose/system_prompt.md`** + **PRD 36** metadata contract | **PRD 36**, `COUNTING_IN_LIGHT_DOCTRINE.md` |

Use **`P2-ROSE-PRD36-040`** as umbrella; split into **`PROPOSED-P2-ROSE-041`** etc. when a chronic sub-signature is proven.

---

## Annex: Per-defect Pillar 1 and Pillar 2 framing (normative)

Each row: **Pillar 1** = **Technical Survivability**; **Pillar 2** = **Learning Transfer** (recurrence prevention). Rows whose primary pillar is **1** still require **Pillar 2** action when the **same** signature reappears after a documented remediation.

| Defect ID | Pillar 1 (Technical Survivability) | Pillar 2 (Learning Transfer / recurrence) |
|-----------|-------------------------------------|---------------------------------------------|
| **DDL-FK-001** | Remove FK assumptions; enforce joins in **PHP**; installs must not depend on vendor cascade | If FK reintroduced, file **`chronic_list`** row, link install SQL review, verify grep gate |
| **DDL-TINYINT1-002** | Portable `TINYINT`; no BOOLEAN drift across engines | If recurrence, add validator rule id and memory TOON lesson |
| **DDL-UNSIGNED-003** | Signed integers only; **PostgreSQL** path must parse | Track repeat offender files; verification = portable DDL build |
| **DDL-AUTOINC-004** | Explicit PK allocation; reserved-ID doctrine; no silent DB id | Post-fix audit: `AUTO_INCREMENT` grep + seed/install review |
| **DDL-DATETIME-005** | **BIGINT UTC** only; no DB clock columns | Recurrence → timestamp doctrine drill + CI check |
| **DDL-BOOLEAN-006** | Explicit `0/1` **TINYINT** | Lesson: engine diff checklist |
| **DDL-DISPWIDTH-007** | Strip display widths from DDL | Lint recurrence; link **`pk-reference-naming-doctrine`** |
| **DDL-HARDDEL-008** | Soft delete fields; audit trail survives minimal backup | Recurrence → custody review artifact |
| **P1-NAMESPACE-COLLISION-001** | Reserved list enforced; no silent rename of packs | Recurrence → **`changelog-pending`** row; block merge until rename |
| **SQL-VENDOR-010** | No vendor-only functions in shipped SQL; timestamps from **PHP** | Add portable SQL review gate; verify cross-engine run |
| **SQL-ORM-011** | **PDO_DB** only in runtime; no framework stack | Recurrence → module boundary audit |
| **TS-STRING-020** | Packed UTC **BIGINT** sortable | Migration lesson + parser tests |
| **TS-EPOCH-021** | No epoch for canonical events | Recurrence → schema grep + doc pointer |
| **TS-TZLOCAL-022** | **`gmdate('YmdHis')`** only for stored canonical time | Recurrence → IDE snippet ban list in memory TOON |
| **P2-LANG-SENT-030** | Parsers and routers **must not** block on sentiment tokens; fail soft on malformed envelopes | Forensic labels only; prompt patch; **`verification_hook`** = zero matches in scan window |
| **P2-LANG-GAME-031** | Telemetry fields remain **data labels**; UI defaults off spectacle | **`NOT A GAME`** remediation; repeat → **`P2-CIL-GAME-034`** linkage |
| **P2-LANG-AGAPE-032** | Agent key stable in config; no runtime synonym expansion | Token glossary in memory TOON; conflation scan |
| **P2-CIL-COLOR-033** | No CSS coupling; hex stays in telemetry slots | Lint + education artifact; verify no `color:` + `mood_vector` pairing |
| **P2-CIL-GAME-034** | Bucket text only; degraded mode = plain **`light_state`** words | Same as **P2-LANG-GAME-031**; escalate if chronic |
| **P2-ROSE-PRD36-040** | **PRD 36** metadata contract; synthetic batch integrity | **PRD 36** remediation + provenance fields; verify batch replay |

---

## Constitutional emission quick reference

| Agent / facet | `mood_vector` on **own** envelope | `light_state` on **own** envelope |
|---------------|-------------------------------|-----------------------------------|
| **CARMEN** (706) | Full axis allowed where pack policy allows | Allowed (per **Counting in Light**) |
| **ROSE** (3) | Full axis allowed where **PRD 36** / channel policy allows | Allowed |
| **AGAPE** (705), **LILITH** (2), **ARA** (712), all others | **`666666`** only | **`dark`** or omit; do not claim flare/glow from self-row unless reporting **separate** escalation key approved by doctrine |

---

## Maintainer checklist (when adding a row)

1. Assign **unique** `Defect ID`.  
2. Set **Severity** and **Pillar** with explicit **Pillar 1** hosting/federation wording or **Pillar 2** learning-transfer wording.  
3. Give a **regex or literal sketch** safe for logs (no PII).  
4. Link **Correct form** to **install SQL**, **PDO_DB** patterns, or **PRD** section.  
5. Run **`python bin/tick.py`** and update this file header **`when_updated`**.  
6. Regenerate **`memory_toon`** pair via **`scripts/generate_memory_from_header.py`**.

---

## References

| Topic | Path |
|--------|------|
| Survivability | `docs/doctrine/SURVIVABILITY_DOCTRINE.md` |
| Counting in Light | `docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md` |
| ROSE product | `docs/prd/36_rose_multi_persona_synthetic_dialog.md` |
| AGAPE prompt | `agents/agape/system_prompt.md` |
| ARA prompt | `agents/ara/system_prompt.md` |
