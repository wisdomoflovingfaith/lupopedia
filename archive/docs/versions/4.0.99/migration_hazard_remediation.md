---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260411213532"
  file_path_from_root: "docs/versions/4.0.99/MIGRATION_HAZARD_REMEDIATION.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.0.99/MIGRATION_HAZARD_REMEDIATION.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/1026/04/migration-hazard-remediation.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: "migration-hazard-remediation"
  content_id: null
  pk_id: null
  pk_slug: "migration-hazard-remediation"
  title: "Migration hazard remediation — import_from_old_crafty_syntax.sql"
  status: "active"
  parent_pk_id: ""
  summary: "SYNAPSE (117): AWAITING IMPLEMENTATION patches #15-20; P-13 blocked until #18; slug, session hash, JSON, timestamps, dept, actor band; constitution-safe."
  module: null
  dialog_transcript: "0/development/migration-hazard-remediation"
---
# Migration hazard remediation: `import_from_old_crafty_syntax.sql`

**Author:** **SYNAPSE** (**actor_id** **117**) — Payload Integrity & Legacy Semantics.  
**Status:** **AWAITING IMPLEMENTATION** — apply patches below to the import SQL (and fixtures) **before** production-class runs.  
**Registry:** **BREAKTHROUGH_REGISTRY.md** **§3.1** edges **#15–#20** (**WOLFIE**-approved **§5** weights).  
**Canonical SQL:** [`database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`](../../database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql).  
**Companion:** [`crafty_import_notes.md`](crafty_import_notes.md) (**P-13**, **CI-1**–**CI-4**).

**Promotion gate (P-13 — proposed “Import timestamp normalization doctrine”):** **P-13** currently describes formalizing the **existing** **`CASE`** / **`LPAD`** logic in the script. **§3.1 edge #18** shows that logic is **unsafe** on **NULL** / non-numeric **`whendone`**. **P-13 must adopt the hardened `CASE` in Hazard #18** (plus numeric guard) **before** **WOLFIE** can promote **P-13** into **§2.1** / scored doctrine.

**Constitutional constraints (Lupopedia DDL):** **No `UNSIGNED`** on **install** integer columns; **no foreign keys** on **`lupo_*`** — orphan **`department_id`** is **application-level** integrity (remap / quarantine / archive dept), not **FK** errors on target tables.

**Portability:** Examples assume **MySQL 8+** / **MariaDB 10.0.5+** (**`REGEXP_REPLACE`**). **PostgreSQL** imports need translated idioms.

---

## Summary of required fixes

| Edge | Hazard | Remediation (directional) |
|------|--------|---------------------------|
| **#15** | Invalid slugs from punctuation (**`livehelp_qa`** → collection tabs / slugs). | **`REGEXP_REPLACE`** + empty → **`CONCAT('tab-', recno)`**. |
| **#16** | **`CRC32(sessionid)`** → merged sessions. | **SHA-256**-derived value; shape depends on **`session_id`** column (**`BIGINT`** vs **`VARCHAR`**). |
| **#17** | **`JSON_OBJECT`** + raw legacy text → **`NULL`** / errors. | **`COALESCE`** + valid **UTF-8** (**edge #9**); **do not** nest **`JSON_QUOTE`** inside **`JSON_OBJECT`** (see below). |
| **#18** | Timestamp **`CASE`** / **`LPAD`** on garbage **`whendone`**. | Numeric guard + multi-branch **`CASE`**; **WOLFIE** sentinel instead of bare **`0`** if doctrine forbids (**edge #10**). |
| **#19** | Orphan **`department`** on legacy rows. | **`COALESCE(NULLIF(department, 0), 1)`** + pre-flight orphan report. |
| **#20** | **`10000 + user_id`** band vs seeds / **CI-3**. | **`BIGINT`** + **`MAX(user_id)`** audit; raise offset if band overlaps. |

## Priority order (SYNAPSE)

1. **#16** — silent session corruption.  
2. **#15** — URL / slug breakage.  
3. **#17** — hard **SQL** / **JSON** failures.  
4. **#18** — invalid **14-digit** timestamps (**P-13** dependency).  
5. **#19** — orphan departments.  
6. **#20** — actor ID band verification.

---

## Hazard #15: Invalid slugs from punctuation

**Refers to:** **`livehelp_qa`** → collection-tab slug generation (e.g. lines using **`LOWER(REPLACE(question, ' ', '-')) AS slug`**).

**Current (brittle):**

```sql
LOWER(REPLACE(question, ' ', '-')) AS slug
```

**Issue:** Legacy **`question`** text includes **`?`**, **`"`**, **`'`**, **`&`**, etc., producing slugs that break routing or uniqueness expectations.

**Remediation (patch):**

```sql
-- Strip everything that is not a-z, 0-9, or hyphen; empty → deterministic fallback (recno exists on livehelp_qa in this import).
COALESCE(
  NULLIF(
    LOWER(REGEXP_REPLACE(REPLACE(IFNULL(question, ''), ' ', '-'), '[^a-z0-9-]', '')),
    ''
  ),
  CONCAT('tab-', recno)
) AS slug
```

**Note:** If multiple rows sanitize to the same slug, add a disambiguation suffix (application policy or **`CONCAT(..., '-', recno)`**) before claiming **UNIQUE** safety.

---

## Hazard #16: Session ID collision (`CRC32`)

**Refers to:** **`livehelp_visit_track`** → **`lupo_visits`** (comment block ~**`CRC32(sessionid) AS session_id`**).

**Cross-link:** **CI-1** assumes importer time shapes are trustworthy; **#16** shows **session** grouping must **not** trust **CRC32** for identity.

**Current (brittle):**

```sql
CRC32(t.sessionid) AS session_id
```

**Issue:** **32-bit** hash → collisions at scale → distinct sessions **merge** → wrong path / visit analytics.

**Schema (install):** **`lupo_visits.session_id`** is **`bigint`** (signed). **`lupo_sessions.session_id`** is **`varchar(128) NOT NULL`** — different targets need different shapes.

**Remediation (patch):**

- **For `lupo_visits.session_id` (BIGINT, signed):** use a **fixed-width hex slice** converted to integer — **not** 16 hex digits (that can exceed **signed** **`BIGINT`**). Prefer **15** hex digits from **SHA-256**:

```sql
CONV(SUBSTRING(SHA2(t.sessionid, 256), 1, 15), 16, 10) AS session_id
```

Document residual collision probability vs **CRC32**; **never** ship **CRC32** for this purpose.

- **For `lupo_sessions.session_id` (VARCHAR(128)):** store a hex digest (length policy is yours), e.g.:

```sql
LOWER(SHA2(t.sessionid, 256)) AS session_id
```

**Architectural note:** Align **every** **`INSERT`** that must join visits to sessions on the **same** derivation rule.

---

## Hazard #17: JSON construction / legacy text

**Refers to:** **`JSON_OBJECT(...)`** over legacy columns (e.g. referers / visit metadata).

**Current (risky):**

```sql
JSON_OBJECT('legacy_pageurl', r.pageurl, ...)
```

**Issue:** **Invalid UTF-8**, **binary** junk, or **control characters** can make **`JSON_OBJECT`** return **`NULL`** or error regardless of quoting.

**Do not (MySQL 8+):** wrap values as **`JSON_OBJECT('k', JSON_QUOTE(r.pageurl))`**. **`JSON_OBJECT`** already emits **JSON strings** with proper escaping; **`JSON_QUOTE`** produces a **quoted SQL string** that then becomes a **double-encoded JSON string** value (wrong shape for consumers expecting a plain string field).

**Remediation (pattern):**

```sql
JSON_OBJECT(
  'legacy_pageurl', COALESCE(r.pageurl, ''),
  'legacy_referrer', COALESCE(t.referrer, '')
  -- ... other keys ...
)
```

**Operational:** Fix **charset / encoding** first (**§3.1 edge #9**). Add **spot-check** queries on worst rows (max length, historic paste blobs). If **`JSON_OBJECT`** still returns **`NULL`**, treat the row as **`needs_review`** and quarantine or scrub.

---

## Hazard #18: Timestamp padding logic (**P-13** blocker)

**Refers to:** **`livehelp_visit_track`**, **`livehelp_paths_*`**, and any **`whendone` / `dateof`** **`CASE`** using **`LPAD`** without a **numeric** guard.

**Conflicts with:** **P-13** as currently written in **`crafty_import_notes.md`** — it **codifies** the old branches **without** admitting the **`ELSE`** failure mode (**§3.1 edge #18**).

**Current (brittle):** branches assume **`whendone`** is always a clean integer; **`ELSE LPAD(CAST(whendone AS CHAR), 14, '0')`** turns **non-numeric** garbage into nonsense **14-digit** values.

**Remediation (patch — pattern; tune bounds to match your audited fixtures):**

```sql
CASE
  WHEN t.whendone IS NULL
    OR CAST(t.whendone AS CHAR) NOT REGEXP '^[0-9]+$'
    THEN 0

  WHEN t.whendone BETWEEN 10000000000000 AND 99999999999999
    THEN t.whendone

  WHEN t.whendone BETWEEN 1000000000000 AND 9999999999999
    THEN CAST(CONCAT(t.whendone, '0') AS UNSIGNED)

  WHEN t.whendone BETWEEN 10000000 AND 99999999
    THEN CAST(CONCAT(t.whendone, '120000') AS UNSIGNED)

  WHEN t.whendone > 0 AND t.whendone < 10000000000000
    THEN CAST(LPAD(CAST(t.whendone AS CHAR), 14, '0') AS UNSIGNED)

  ELSE 0
END
```

**Doctrine:** **`0`** is illustrated as a **fallback** only. If **packed UTC** **`0`** is invalid for **“active”** semantics, replace **`0`** with a **WOLFIE**-approved **sentinel** **BIGINT UTC** (**§3.1 edge #10**). **Install DDL** avoids **`UNSIGNED`**; **`CAST(... AS UNSIGNED)`** here is **import-time** width handling — prefer aligning with **timestamp_ymdhis** helpers after import if you port to stricter typing.

**CI-1:** Treat **Hazard #18** as the **hardened** companion to **CI-1** — timestamp shapes **plus** **garbage** / **NULL** defense.

---

## Hazard #19: Orphaned department IDs

**Refers to:** **`livehelp_transcripts`**, **`livehelp_questions`**, and similar **`department`** / **`dept_id`** maps.

**Current (risky):**

```sql
department AS department_id
```

**Issue:** **NULL**, **`0`**, or IDs for **deleted** departments create **orphan** references. **Lupopedia** does **not** rely on **FK** constraints for this — the **UI** and **validators** still break if IDs are nonsense.

**Remediation (patch):**

```sql
COALESCE(NULLIF(department, 0), 1) AS department_id
```

**Requires:** Target **`lupo_departments`** row **1** (or your chosen default) exists **before** dependent inserts. Run a **pre-flight orphan report** (**LEFT JOIN** legacy dept dimension) for IDs that still do not exist after remap.

---

## Hazard #20: Actor ID offset collision

**Refers to:** **`livehelp_users`** → **`(10000 + u.user_id) AS actor_id`** (and similar).

**Cross-link:** **CI-3** (**`280000 + department_id`** hybrid band vs **`10000 + user_id`**) — document **non-overlap** after any offset change.

**Remediation (verification):**

1. Confirm **`lupo_actors.actor_id`** is **`BIGINT`** in **`install_new_lupopedia.sql`** / TOON (**no `UNSIGNED`** on **Lupopedia** DDL).
2. Pre-migration:

```sql
SELECT MAX(user_id) FROM livehelp_users;
```

If **`MAX`** threatens your reserved band (e.g. **`10000 + MAX`** overlaps **seed** or **CI-3** actors), **raise** the additive offset (e.g. **`100000000 + user_id`**) and **document** the band in **`crafty_import_notes.md`** / importer header comment.

3. **Reserved-ID** doctrine: inserts must remain **explicit** — no **`AUTO_INCREMENT`** for registry-backed actors.

---

## Summary for **WOLFIE**

| Item | Action |
|------|--------|
| **P-13** | **Blocked** until **Hazard #18** (numeric guard + **`CASE`**) is the **documented** and **tested** rule, not the legacy **`ELSE`**. |
| **CI-1** | Pair with **#18** — shapes **and** garbage / **NULL** handling. |
| **#16** | Highest silent-integrity risk — patch **before** trusting visit/session analytics. |
| **Next step** | Patch **`import_from_old_crafty_syntax.sql`**, extend **fixture** / **sample-dump** tests, re-run importer on a **large** Crafty export. |

---

## Production gate (SYNAPSE)

Do **not** run **`import_from_old_crafty_syntax.sql`** against **production**-class data until **#16**, **#15**, and **#17** are implemented and **#18** is aligned with **P-13** / **CI-1** as amended above.

---

This output complies with Lupopedia Constitutional Root Rules.
