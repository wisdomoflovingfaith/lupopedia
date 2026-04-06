---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260405224926"
  last_modified_utc: "20260405224926"
  file_path_from_root: "lupo-docs/decisions/pseudocode/00_dodo_bird_corrections.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/decisions/pseudocode/00_dodo_bird_corrections.pseudo.md"
  channel_id: 42
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: pseudocode
  artifact_kind: anti_pattern_digest
  purpose: "Correct recurring AI suggestions vs PRD 00; positional INSERT is the primary write hazard — SELECT * is not listed as a violation"
  tags:
    - pseudocode
    - external_ai
    - prd_00
    - anti_patterns
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Canonical law — this digest must not contradict PRD 00"
    - to: "lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/00_constitution_shorthand.pseudo.md"
      type: references
      weight: 0.95
      reason: "Companion constitution shorthand for IDE agents"
    - to: "lupo-docs/decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md"
      type: references
      weight: 0.93
      reason: "Short overrides table — read before this file for orientation"
    - to: "lupo-includes/classes/TimestampYmdhis.php"
      type: references
      weight: 0.9
      reason: "Packed UTC clock utility (class timestamp_ymdhis)"
lupopedia.footer:
  last_verified: "20260405224926"
  verified_by:
    actor_id: 102
---

# Dodo bird corrections — AI “common practice” that is wrong here

**Read [PRD 00](../../prd/00_root_constitutional_system_requirements.md) first.** This file is a **Purpose 1** digest for IDE agents. It is **not** loaded by the application. If anything here disagrees with **PRD 00**, **PRD 00 wins**.

**Two-file model (not a rename):** This is **not** **`00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES`** under another name. For a **fast** “invert training defaults” table, read **[00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md](./00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md)** first (~1 minute); **this** file is the **expanded** digest with narrative and SQL.

**`SELECT *` is not a “dodo” item in this digest.** It is **allowed**. The **primary write-side villain** is **`INSERT` without an explicit column list** (silent corruption when DDL changes). See **PRD 00 §17.3**.

---

## 1. `INSERT` without column list (critical — silent corruption)

### What models often suggest

```sql
INSERT INTO lupo_users VALUES ('John', 'Doe', 'john@example.com', 20260405120000);

INSERT INTO lupo_users VALUES (:first, :last, :email, :created);
```

### Why it is wrong

- **Schema change → silent mis-mapping:** New or reordered columns can shift **positional** values into the **wrong** columns. The DB may **not** error.
- **Constitutional / root rules:** **All `INSERT` statements must explicitly list all columns** (**PRD 00 §17.3**; root database doctrine).

### Example failure mode

```sql
-- Table once matched: (first_name, last_name, email, created_ymdhis)
INSERT INTO lupo_users VALUES ('John', 'Doe', 'john@example.com', 20260405120000);

-- Later: e.g. middle_name added — positional INSERT misaligns; no hard error.
```

### Correction

```sql
INSERT INTO lupo_users (first_name, last_name, email, created_ymdhis)
VALUES (:first, :last, :email, :created);
```

---

## 2. Timestamps (epoch in `BIGINT`)

### What models often suggest

```php
$db->insert($table, array('created_ymdhis' => time()));
// SQL: … WHERE created_ymdhis > UNIX_TIMESTAMP(NOW() - INTERVAL 7 DAY)
```

### Why it is wrong (Lupopedia)

- **Wrong encoding** for lineage clocks; **Y2038** / portability — **PRD 00 §3.5, §3.5.1, §3.5.4, §19**.

### Correction

```php
$cutoff = timestamp_ymdhis::addSeconds(timestamp_ymdhis::now(), -7 * 86400);
$sql = "SELECT * FROM {$table} WHERE created_ymdhis > :cutoff";
$rows = $db->fetchAll($sql, array('cutoff' => $cutoff));
$db->insert($table, array('created_ymdhis' => timestamp_ymdhis::now()));
```

Use **explicit `INSERT` columns** in real code (§1). **`SELECT *`** in reads is **fine**.

---

## 3. Foreign keys

### What models often suggest

```sql
ALTER TABLE lupo_orders ADD FOREIGN KEY (user_ref) REFERENCES lupo_users(user_id);
```

### Why it is wrong

- **PRD 00 §3.1** — no **`FOREIGN KEY`** / **`REFERENCES`**.

### Correction

Validate parents in **PHP**; **`PDO_DB`**; **`is_deleted`** in application logic.

---

## 4. Database date functions

### What models often suggest

```sql
SELECT * FROM lupo_logs WHERE created_ymdhis > DATE_ADD(NOW(), INTERVAL -7 DAY);
```

### Why it is wrong

- Vendor-specific; clock bounds belong in **PHP** (**§3.5**).

### Correction

```php
$cutoff = timestamp_ymdhis::addSeconds(timestamp_ymdhis::now(), -7 * 86400);
$sql = "SELECT * FROM {$table} WHERE created_ymdhis > :cutoff";
$rows = $db->fetchAll($sql, array('cutoff' => $cutoff));
```

---

## 5. ORM / framework magic

### What models often suggest

Eloquent, Doctrine, ActiveRecord, lazy loading, implicit migrations.

### Why it is wrong

- **PRD 00 §4** — no Laravel / ORM magic in core; **PDO_DB** + explicit SQL.

### Correction

```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$t = $prefix . 'auth_users';
$sql = "SELECT * FROM {$t} WHERE email = :email LIMIT 1";
$row = $db->fetch($sql, array('email' => $email));
```

---

## 6. npm / Composer as shipped runtime

### What models often suggest

```bash
composer require guzzlehttp/guzzle
npm install axios moment
```

### Why it is wrong

- **PRD 00 §4, §16** — no **`vendor/`** / npm-as-runtime for shipped surfaces.

### Correction

Native PHP; in-tree **`lupo-includes/`**; **`timestamp_ymdhis`** / **`gmdate('YmdHis')`** for clocks.

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

```sql
CREATE TABLE lupo_widgets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255)
);
```

### Why it is wrong

- **PRD 00 §9.7** — **`<table_singular>_id`**; **`AUTO_INCREMENT`** rules in **§3.2** / **install SQL**.

### Correction

**`widget_id BIGINT NOT NULL PRIMARY KEY`** (explicit app-assigned id) — real DDL = **install + TOON**.

---

## Summary table

| Dodo pattern | Danger | Lupopedia correction |
|--------------|--------|----------------------|
| **`INSERT` without column list** | **Critical** | **`INSERT INTO t (a,b,c) VALUES (...)`** |
| Unix epoch in packed clock columns | **Critical** | Packed **`YYYYMMDDHHIISS`**; **`timestamp_ymdhis`** |
| Foreign keys | High | Application-layer integrity |
| SQL `NOW()` / `DATE_ADD` / epoch bridges | High | PHP cutoff + **bound** params |
| ORM / Laravel | High | **PDO_DB**, explicit SQL |
| npm/Composer runtime | Medium | In-tree / native |
| SPA build for shipped UI | Medium | Vanilla JS + **LupoLayer** |
| Mobile = CSS only | Medium | Separate mobile routes when needed |
| PK **`id`**, wrong **`AUTO_INCREMENT`** | Low–medium | **`widget_id`**-style; explicit IDs |
| **`SELECT *`** | *(not listed)* | **Allowed** — not treated as a constitutional violation in this digest |

## Golden rule

**Positional `INSERT` without a column list is the most dangerous pattern here:** schema drift can **silently corrupt** rows. **Always** list columns on **`INSERT`**. **`SELECT *`** is **fine** — do not block it as if it were the same class of bug.

If a suggestion is justified only as **“industry standard”**, **verify it against [PRD 00](../../prd/00_root_constitutional_system_requirements.md)**.

**Not loaded by the application.** For humans and **IDE agents** before proposing refactors.
