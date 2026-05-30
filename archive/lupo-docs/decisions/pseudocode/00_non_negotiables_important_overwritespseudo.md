---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260406152800"
  file_path_from_root: "lupo-docs/decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: pseudocode
  artifact_kind: anti_pattern_digest
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
# Dodo bird corrections — AI “common practice” that is wrong here

**Read [PRD 00](../../prd/00_root_constitutional_system_requirements.md) first.** This file is a **Purpose 1** digest for IDE agents. It is **not** loaded by the application. If anything here disagrees with **PRD 00**, **PRD 00 wins**.

**Two-file model (not a rename):** This is **not** **`00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND`** under another name. For a **fast** “invert training defaults” table, read **[00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md](./00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md)** first (~1 minute); **this** file is the **expanded** digest with narrative.

**`SELECT *` is not a “dodo” item in this digest.** It is **allowed**. The **primary write-side villain** is **`INSERT` without an explicit column list** (silent corruption when DDL changes). See **PRD 00 §17.3**.

---

## 1. `INSERT` without column list (critical — silent corruption)

### What models often suggest

Positional inserts: values-only form with no column list; sometimes with bound parameters but still no column names.

### Why it is wrong

- **Schema change → silent mis-mapping:** New or reordered columns can shift **positional** values into the **wrong** columns. The DB may **not** error.
- **Constitutional / root rules:** **All `INSERT` statements must explicitly list all columns** (**PRD 00 §17.3**; root database doctrine).

### Example failure mode

After a column is added or reordered, positional inserts can misalign values with no hard error.

### Correction

Use an explicit column list on every insert; bound parameters with named placeholders. (Removed SQL example per Purpose‑1 constitutional rules; see PRD 00 §17.3.)

---

## 2. Timestamps (epoch in `BIGINT`)

### What models often suggest

Storing Unix epoch in packed-clock columns; SQL using Unix time or interval helpers for bounds.

### Why it is wrong (Lupopedia)

- **Wrong encoding** for lineage clocks; **Y2038** / portability — **PRD 00 §3.5, §3.5.1, §3.5.4, §19**.

### Correction

(Removed PHP example per Purpose‑1 constitutional rules; see PRD 00 §3.5, §19, and `lupo-includes/classes/TimestampYmdhis.php`.) Use **explicit `INSERT` columns** in real code (§1). **`SELECT *`** in reads is **fine**.

---

## 3. Foreign keys

### What models often suggest

(Removed DDL per Purpose‑1 constitutional rules; see PRD 00 and DATABASE_DOCTRINE.md for canonical schema.)

### Why it is wrong

- **PRD 00 §3.1** — no **`FOREIGN KEY`** / **`REFERENCES`**.

### Correction

Validate parents in **PHP**; **`PDO_DB`**; **`is_deleted`** in application logic.

---

## 4. Database date functions

### What models often suggest

Vendor date/time functions and intervals in `WHERE` clauses for packed-clock columns.

### Why it is wrong

- Vendor-specific; clock bounds belong in **PHP** (**§3.5**).

### Correction

(Removed PHP example per Purpose‑1 constitutional rules; see PRD 00 §3.5.)

---

## 5. ORM / framework magic

### What models often suggest

Eloquent, Doctrine, ActiveRecord, lazy loading, implicit migrations.

### Why it is wrong

- **PRD 00 §4** — no Laravel / ORM magic in core; **PDO_DB** + explicit SQL.

### Correction

(Removed PHP example per Purpose‑1 constitutional rules; see PRD 00 §4.) Use **PDO_DB**, **`DatabaseFactory::getConnection()`**, named placeholders.

---

## 6. npm / Composer as shipped runtime

### What models often suggest

Adding Composer or npm packages as dependencies of the shipped app tree.

### Why it is wrong

- **PRD 00 §4, §16** — no **`vendor/`** / npm-as-runtime for shipped surfaces.

### Correction

(Removed shell example per Purpose‑1 constitutional rules; see PRD 00 §4, §16.) Native PHP; in-tree **`lupo-includes/`**; **`timestamp_ymdhis`** / **`gmdate('YmdHis')`** for clocks.

---

## 7. React / Vue / build-step front ends (shipped UI)

### What models often suggest

Webpack, Vite, SPA stacks for **`lupo-includes`**.

### Why it is wrong

- **§16** — vanilla JS; **LupoLayer** for new layered UI.

### Correction

Vanilla JS; **`lupo-layers.js`**; **`lupo_t()`** for copy.

---

## 8. “Responsive CSS only” for every surface

### What models often suggest

One DOM; media queries only; same interaction for mouse and touch.

### Why it is wrong

- **MOBILE_SEPARATION_DOCTRINE** / **AGENTS.md** — separate **mobile routes** when behavior diverges.

### Correction

Entry detection; **`/mobile/...`** (or documented equivalent) under **`LUPOPEDIA_PUBLIC_PATH`**; **same backend**.

---

## 9. Primary key named `id` + careless `AUTO_INCREMENT`

### What models often suggest

Integer surrogate key with auto-increment and a generic `id` column name.

(Removed DDL per Purpose‑1 constitutional rules; see PRD 00 and DATABASE_DOCTRINE.md for canonical schema.)

### Why it is wrong

- **PRD 00 §9.7** — **`<table_singular>_id`**; **`AUTO_INCREMENT`** rules in **§3.2** / **install SQL**.

### Correction

Explicit app-assigned primary keys and PK naming per **PRD 00** and **install + TOON** (not repeated here).

---

## Summary table

| Dodo pattern | Danger | Lupopedia correction |
|--------------|--------|----------------------|
| **`INSERT` without column list** | **Critical** | Explicit column list on every **`INSERT`** |
| Unix epoch in packed clock columns | **Critical** | Packed **`YYYYMMDDHHIISS`**; **`timestamp_ymdhis`** |
| Foreign keys | High | Application-layer integrity |
| SQL `NOW()` / `DATE_ADD` / epoch bridges | High | PHP cutoff + **bound** params |
| ORM / Laravel | High | **PDO_DB**, explicit SQL |
| npm/Composer runtime | Medium | In-tree / native |
| SPA build for shipped UI | Medium | Vanilla JS + **LupoLayer** |
| Mobile = CSS only | Medium | Separate mobile routes when needed |
| PK **`id`**, wrong **`AUTO_INCREMENT`** | Low–medium | **`<table_singular>_id`**; explicit IDs |
| **`SELECT *`** | *(not listed)* | **Allowed** — not treated as a constitutional violation in this digest |

## Golden rule

**Positional `INSERT` without a column list is the most dangerous pattern here:** schema drift can **silently corrupt** rows. **Always** list columns on **`INSERT`**. **`SELECT *`** is **fine** — do not block it as if it were the same class of bug.

If a suggestion is justified only as **“industry standard”**, **verify it against [PRD 00](../../prd/00_root_constitutional_system_requirements.md)**.

**Not loaded by the application.** For humans and **IDE agents** before proposing refactors.
