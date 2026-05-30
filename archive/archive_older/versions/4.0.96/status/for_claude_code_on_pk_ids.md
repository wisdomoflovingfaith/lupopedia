---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: "20260408031925"
  file_path_from_root: "docs/versions/4.0.96/status/FOR_CLAUDE_CODE_ON_PK_IDS.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/status/FOR_CLAUDE_CODE_ON_PK_IDS.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: handoff
  artifact_kind: status
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: FOR_CLAUDE_CODE_ON_PK_IDS — delegation: cursor:root — web_path: [http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/status/FOR_CLAUDE_CODE_ON_PK_IDS.md](http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/status/FOR_CLAUDE_CODE_ON_PK_IDS.md)

# FOR_CLAUDE_CODE_ON_PK_IDS — Chronological Trust Ladder and PK / install handoff

**Audience:** Claude Code (**actor_id 116**), implementers building UI or services that touch trust-ladder PKs, seeds, and install-time records.

**Temporal anchor:** `20260408031925` UTC (`python bin/tick.py` batch for this document).

---

## 5W1H (this handoff)

| Element | Answer |
|--------|--------|
| **WHO** | **cursor** (actor_id **102**) authored this handoff; doctrine and code changes were produced in the same thread for **4.0.96** trust-ladder work. |
| **WHAT** | Explains **Chronological Trust Ladder**, **what changed** in code and doctrine, **what Claude Code must assume** for actors, DB, and install, and **what to build next** (web surface for install records). |
| **WHERE** | Canonical doctrine: `docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`, `TRUST_LADDER_REGISTRY.md`, `RETENTION_POLICY.md`. Runtime: `includes/classes/IdGenerator.php`, `app/Services/Kairos/KairosConsolidationService.php`. Validation: `scripts/validate_trust_ladder_registry.py`. Version bundle: `docs/versions/4.0.96/`. |
| **WHEN** | Handoff timestamp **20260408031925** UTC; see `docs/versions/4.0.96/CHANGELOG.md` for session rollups. |
| **WHY** | Preserve **one coherent model** for PK generation, seed pairing, staging vs canonical rows, and registry/install alignment so **116** does not re-derive rules from scattered comments. |
| **HOW** | Read doctrine first; use **string** canonical IDs in PHP; run registry validator against install SQL when changing participation; future UI reads/writes **install and seed artifacts** and DB state per PRD 00 / PDO / no Laravel. |

---

## What is Chronological Trust Ladder?

**Chronological Trust Ladder** is the normative ruleset for how Lupopedia assigns and validates **monotonic, time-ordered primary keys** (and related staging/canonical promotion) so that:

1. **Identity is deterministic** — PKs encode **UTC time order** in a fixed string form (14-digit packed UTC prefix + suffix where defined); no random UUIDs, no DB `AUTO_INCREMENT` for ladder tables (constitutional rule: application-layer IDs).

2. **Trust tiers are explicit** — Not every table uses the full generator pipeline. **`TRUST_LADDER_REGISTRY.md`** lists which tables are **full** (generator + validation), **generator_staging** only, or **seed_only** (short seed integers in SQL, promoted to canonical form in code when needed).

3. **Staging vs canonical** — “Observations” or pre-promotion rows may use **`IdGenerator::generate()`** (staging). Promoted/canonical rows must pass **`validateTrustLadderPk`** and use **`toCanonicalId` / `toCanonicalIdSafe`** for the target table/column.

4. **PHP and MySQL agree on type shape** — Stored as **`BIGINT`**, but PHP may see **strings** when PDO uses **`ATTR_STRINGIFY_FETCHES`** or when ids exceed 32-bit safe integer range. **Do not cast 18-digit ladder strings to `(int)`** in PHP; compare **padded decimal strings** for range checks.

5. **Seeds vs canonical actors** — SQL seeds for **actors** (and similar) remain **short reserved integers** per **`install`/seed** doctrine. **`seedActorToCanonicalId($actorId)`** maps a seed actor id to the **canonical 18-digit string** for edges and APIs that require ladder form. This is **not** “padding 116 to 18 digits”; it is **structured canonicalization** per **PRD 41**.

6. **Edges and ordering** — When two memory node ids must form an undirected “pair” key (e.g. contradiction), **do not** use `min`/`max` on raw strings if lexicographic order differs from numeric order; use **documented ordering** (e.g. **`orderMemoryNodeIdsForEdge`**) so duplicates do not flip.

7. **Observability and retention** — Staging rows may be soft-deleted per **`RETENTION_POLICY.md`**; canonical rows follow normal soft-delete fields where applicable.

**Further reading (required):**

- `docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md` — full normative text, **`validateTrustLadderPk`**, **`toCanonicalIdSafe`**, migration notes, tests.
- `docs/doctrine/TRUST_LADDER_REGISTRY.md` — which tables participate and how.
- `content/federation_node/0/captains_log/20260408_CHRONOLOGICAL_TRUST_LADDER.md` — narrative summary and links.

---

## What changed in this session (concrete)

### Doctrine and policy

- **`CHRONOLOGICAL_TRUST_LADDER.md`** — Guardrails for PK handling, seed ratification bound (**2026** + registry), appendix on rejected alternatives, PDO/string notes, cross-links.
- **`TRUST_LADDER_REGISTRY.md`** — Participation tiers; install-validated table names.
- **`RETENTION_POLICY.md`** — Staging GC alignment.

### Runtime code

- **`IdGenerator.php`** — **`toCanonicalId` / `toCanonicalIdSafe`** return **strings**; seed-band check uses **string** comparison; **`seedActorToCanonicalId`**; **`numericStringLessThan`**.
- **`KairosConsolidationService.php`** — Uses **`lupo_memory_nodes`** (not **`lupo_actor_memory`**); staging id via **`generate()`**; canonical via **`toCanonicalIdSafe`** + **`validateTrustLadderPk`**; **`lupo_edges`** `object_type` **`memory_node`**; **`flare_db_source`** **`lupo_memory_nodes`**; contradiction edge ordering fixed for string ids.

### Tooling

- **`validate_trust_ladder_registry.py`** — Matches **`CREATE TABLE`** lines in install SQL (templated `{{prefix}}` and literal `lupo_`); case-insensitive table names; CLI args for paths.

### Narrative

- **Captain's Log** — `content/federation_node/0/captains_log/20260408_CHRONOLOGICAL_TRUST_LADDER.md`.

### Install SQL

- **No new DDL change** was required in this rollup for trust ladder itself; **`lupo_memory_nodes`** / **`lupo_edges`** are already part of the canonical install path. If a site had **only** legacy **`lupo_actor_memory`** rows, that is a **data migration** problem, not covered by this session’s code path (KAIROS now targets **`lupo_memory_nodes`** only).

---

## What Claude Code must know: actors and database

1. **Actors** — Canonical registry in **`database/lupopedia/actors/registry.json`**. Seeds in SQL stay **short**; canonical **18-digit** strings for ladder contexts come from **`IdGenerator::seedActorToCanonicalId()`** when needed.

2. **Tables** — Trust ladder “full” tables and staging tables are listed in **`TRUST_LADDER_REGISTRY.md`**. **KAIROS** consolidation persists canonical memory in **`lupo_memory_nodes`** and links via **`lupo_edges`**.

3. **Validation** — Before relying on a string id, **`validateTrustLadderPk($id, $table, $pkColumn)`** (and **`validateFormat`**) are the gate. **`toCanonicalIdSafe`** returns **`null`** if invalid.

4. **PDO** — Prefer treating ids as **strings** end-to-end in PHP for ladder values; avoid `(int)` casts on 18-digit values.

5. **Install/seed** — Follow **`docs/prd/79_install_seed_doctrine.md`**: seed rows are not reinterpreted as “already canonical ladder” unless doctrine says so.

---

## Future work: web interface for install records (for Claude Code)

**Goal:** A **plain PHP** (no Laravel) admin or operator surface that:

1. **Reads** canonical install/seed **sources** (SQL files under `database/lupopedia/mysql/install/` and seed paths per project layout) and **displays** rows that participate in the trust ladder or that operators must edit (e.g. registry-aligned seeds).

2. **Shows** validation results from **`validate_trust_ladder_registry.py`** (or a PHP port of the same rules) so drift between **`TRUST_LADDER_REGISTRY.md`** and **`install_new_lupopedia.sql`** is visible in the UI.

3. **Updates** — Any write path must use **PDO_DB**, named placeholders, **`LUPO_TABLE_PREFIX`**, and respect **reserved id** / **no lastInsertId** rules for registry tables. Do **not** add DB triggers or FKs.

4. **Attribution** — Session/auth actor context per **`AuthService`**; actions logged per project audit conventions.

5. **Alignment** — **`CHRONOLOGICAL_TRUST_LADDER.md`** §9–§13 and **`PRD 41`** are the specification; UI is a thin, auditable editor over the same truths.

**Non-goals for a first pass:** Full federation sync, automatic migration from **`lupo_actor_memory`** to **`lupo_memory_nodes`** without an explicit migration story.

---

## Decisions / questions / answers (short)

| Type | Content |
|------|--------|
| **Decision** | KAIROS targets **`lupo_memory_nodes`** only; edge **`object_type`** = **`memory_node`**. |
| **Decision** | Canonical ladder ids are **strings** in PHP at trust boundaries. |
| **Question** | How to compare two ladder ids for “edge pair” uniqueness? |
| **Answer** | Use **numeric string ordering** helpers / documented **`orderMemoryNodeIdsForEdge`**, not lexical `min`/`max` alone. |
| **Question** | Where is participation documented? |
| **Answer** | **`TRUST_LADDER_REGISTRY.md`** + **`validate_trust_ladder_registry.py`** against install SQL. |

---

## Related version files

- `docs/versions/4.0.96/CHANGELOG.md` — session changelog entries.
- `docs/versions/4.0.96/SUMMARY.md` — 5W1H rollup.
- `docs/versions/4.0.96/status/THREAD_INDEX.md` — status thread index.

---

*This output complies with Lupopedia Constitutional Root Rules.*
