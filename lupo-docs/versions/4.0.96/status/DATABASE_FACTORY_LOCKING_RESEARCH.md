---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260408133230"
  file_path_from_root: "lupo-docs/versions/4.0.96/status/DATABASE_FACTORY_LOCKING_RESEARCH.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/status/DATABASE_FACTORY_LOCKING_RESEARCH.md"
  last_modified_utc: "20260408133230"
  federation_node_id: 0
  channel_id: 42
  actor_id: 116
  actor_name: "Claude Code"
  delegation_chain: "claude_code:root"
  artifact_type: research
  artifact_kind: status_report
  purpose: "Research on DatabaseFactory locking support for Q3 (concurrent promotion)"
  tags:
    - research
    - databasefactory
    - locking
    - q3
    - 4.0.96
lupopedia.footer:
  last_verified: "20260408133230"
  verified_by:
    identity_type: actor
    actor_id: 116
    agent_name_identity: "Claude Code"
  orchestrator: "claude_code:root"
---

# DatabaseFactory Locking Research

## Executive Summary

`PDO_DB` has full transaction control (`beginTransaction` / `commit` / `rollBack`)
and exposes the raw PDO instance via `getPdo()`, making driver-name detection
possible at runtime. However, it has **no `FOR UPDATE` support**, no
`getDriverName()` method, and its `quoteIdentifier()` always emits MySQL-style
backtick quoting (incompatible with PostgreSQL). There is also **no `execute()`
method** — any caller that uses `$db->execute()` silently falls back to
`error_log()` via the `\Throwable` catch in `logSuffixExhaustion()`. The
recommended path is: add `getDriverName()` to `PDO_DB`, then implement
`toCanonicalIdSafe()`-local locking logic that branches on driver name — no
changes to `DatabaseFactory` required. SQLite requires a different strategy
(`BEGIN IMMEDIATE`) since it does not support row-level locking at all.

---

## 1. Transaction Control

`PDO_DB` (`lupo-includes/classes/pdo_db.php`) exposes three transaction methods,
all thin wrappers over the underlying PDO:

```php
// pdo_db.php lines 354–375

public function beginTransaction(): bool
{
    return $this->pdo->beginTransaction();
}

public function commit(): bool
{
    return $this->pdo->commit();
}

public function rollBack(): bool
{
    return $this->pdo->rollBack();
}
```

**Findings:**
- All three methods exist and work on MySQL, PostgreSQL, and SQLite (PDO
  provides transaction support for all three).
- No exception handling is added — `PDOException` propagates to the caller.
- No `inTransaction()` wrapper exists, but `$db->getPdo()->inTransaction()`
  is accessible via the `getPdo()` escape hatch.
- `DatabaseFactory` does not add any transaction helpers of its own.

---

## 2. Row-Level Locking

**No `FOR UPDATE` support exists anywhere in `PDO_DB` or `DatabaseFactory`.**

The closest equivalent is constructing the SQL string manually and passing it
to `$db->query()` or `$db->fetchRow()`. For example, the current
`toCanonicalIdSafe()` collision check:

```php
// IdGenerator.php — current collision check (no locking)
$sql = 'SELECT 1 AS x FROM ' . $db->quoteIdentifier($fullTable)
    . ' WHERE ' . $db->quoteIdentifier($pkColumn) . ' = :id LIMIT 1';
$row = $db->fetchRow($sql, array('id' => $currentId));
```

To add `FOR UPDATE` today the caller would append the clause to the SQL
string directly — but this is:

1. **MySQL/PostgreSQL-specific** — SQLite has no row-level locking (`FOR UPDATE`
   is a syntax error on SQLite).
2. **Driver-unaware** — there is currently no API on `PDO_DB` or
   `DatabaseFactory` to ask "which database am I talking to?"
3. **`LIMIT` incompatible** — MySQL allows `SELECT ... LIMIT 1 FOR UPDATE`,
   but PostgreSQL requires the `FOR UPDATE` clause after `LIMIT` in some
   versions (and `LIMIT` inside a `FOR UPDATE` query is non-standard). The
   safest portable form omits `LIMIT` when locking.

---

## 3. Database Type Detection

`DatabaseFactory` reads `DB_TYPE` at connection time and passes it to
`PDO_DB::connect()`, but **`PDO_DB` does not store it**:

```php
// DatabaseFactory.php lines 35–40
$type = defined('DB_TYPE') ? DB_TYPE : 'mysql';
self::$connection = new PDO_DB($host, $user, $pass, $name, $type);
// UTC timezone — uses $type directly here ...
if ($type === 'mysql') {
    self::$connection->exec("SET time_zone = '+00:00'");
} else {
    self::$connection->exec("SET timezone = 'UTC'");   // sent to SQLite too — harmless error
}
```

`PDO_DB::connect()` uses `$type` only to select the DSN and set MySQL-specific
options (`MYSQL_ATTR_USE_BUFFERED_QUERY`). After construction, `$type` is
discarded — it is a local variable, not stored as a property.

**Runtime detection workaround** — `PDO_DB::getPdo()` is public:

```php
$driver = $db->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
// Returns: 'mysql' | 'pgsql' | 'sqlite'
```

This works reliably but is an escape hatch — it bypasses the `PDO_DB`
abstraction and directly accesses PDO. It also only works on PHP drivers
where the attribute is defined (all three supported drivers define it).

---

## 4. Current Database-Specific SQL Patterns

### `quoteIdentifier()` — MySQL only

```php
// pdo_db.php lines 392–399
public function quoteIdentifier($identifier)
{
    $parts = explode('.', $identifier);
    $quoted = array_map(function ($part) {
        return '`' . str_replace('`', '``', $part) . '`';
    }, $parts);
    return implode('.', $quoted);
}
```

**Backtick quoting is MySQL/MariaDB/SQLite-compatible, but NOT PostgreSQL.**
PostgreSQL requires double-quote identifiers (`"table_name"`). Any SQL built
with `quoteIdentifier()` and sent to PostgreSQL will fail with a syntax error
if the identifier contains backticks.

This is a **pre-existing portability bug** in `PDO_DB`. All callers in the
codebase that use `$db->quoteIdentifier()` — including `toCanonicalIdSafe()`,
`StagingGcService`, and `AdminTrustLadderHandler` — are currently
MySQL/SQLite-only despite the stated PostgreSQL support goal.

### DSN construction — type-aware (private)

```php
// pdo_db.php lines 173–183
private function getDsn($host, $dbname, $type)
{
    $type = strtolower($type);
    switch ($type) {
        case 'pgsql':
            return "pgsql:host={$host};dbname={$dbname}";
        case 'mysql':
        default:
            return "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    }
}
```

SQLite is handled by `getDsn()` defaulting to the `mysql` DSN — which
is wrong. SQLite DSN is `sqlite:/path/to/file`. This appears to be an
unfinished stub: `PDO_DB` works against SQLite only when the caller
passes an existing PDO instance (the `$host instanceof PDO` branch in
the constructor), not when connecting by file path.

### `DatabaseFactory` UTC timezone — partially wrong

```php
// DatabaseFactory.php lines 42–46
if ($type === 'mysql') {
    self::$connection->exec("SET time_zone = '+00:00'");
} else {
    self::$connection->exec("SET timezone = 'UTC'");
}
```

`SET timezone = 'UTC'` is PostgreSQL syntax. Sent to SQLite, it is a
no-op (SQLite ignores unknown `SET` statements). Sent to MySQL, it
errors. The branch logic is partially correct (MySQL vs. everything-else)
but conflates PostgreSQL and SQLite.

### `execute()` method — does not exist

`PDO_DB` has no `execute()` method. The method list is:
`query`, `fetchAll`, `fetchRow`, `fetchOne`, `insert`, `update`,
`delete`, `beginTransaction`, `commit`, `rollBack`, `quote`,
`quoteIdentifier`, `quoteIdentifiers`, `getLastError`, `getLastQuery`,
`getPdo`, `prepare`, `exec`, `lastInsertId`.

The `logSuffixExhaustion()` method in `IdGenerator` calls
`$db->execute()` — this always triggers the `\Throwable` catch and
falls back to `error_log()`. Logging to `lupo_unified_log` currently
**never succeeds**. This must be fixed by using `$db->query()` instead.

---

## 5. Gap Analysis

| Gap | Severity | Affects |
|-----|----------|---------|
| No `getDriverName()` on `PDO_DB` | **High** — blocks portable locking | `toCanonicalIdSafe()` locking, any future db-branching code |
| No `FOR UPDATE` / locking API | **High** — TOCTOU race in promotion | `toCanonicalIdSafe()` |
| `quoteIdentifier()` is MySQL-only (backticks) | **High** — PostgreSQL broken | All callers of `quoteIdentifier()` |
| SQLite has no row-level locking | **Medium** — different strategy needed | `toCanonicalIdSafe()` on SQLite |
| No `execute()` method | **Medium** — logging to `lupo_unified_log` silently fails | `logSuffixExhaustion()` |
| DSN construction doesn't support SQLite file path | **Medium** — SQLite only works via PDO injection | Fresh SQLite installs |
| PostgreSQL `LIMIT` + `FOR UPDATE` ordering | **Low** — edge case in locking SQL | Locking query construction |
| `DatabaseFactory` timezone SET is PostgreSQL-only in the else branch | **Low** — SQLite receives wrong SQL | SQLite connections |

---

## 6. Recommendation

### Minimal, non-breaking path — three small changes

**Step 1: Add `getDriverName()` to `PDO_DB`**

One new method, no existing method changed:

```php
/**
 * Return the PDO driver name for the active connection.
 * Returns: 'mysql' | 'pgsql' | 'sqlite' (matches PDO::ATTR_DRIVER_NAME values).
 */
public function getDriverName(): string
{
    return (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
}
```

**Step 2: Fix `logSuffixExhaustion()` in `IdGenerator` — use `$db->query()` not `execute()`**

Replace `$db->execute($sql, $params)` with `$db->query($sql, $params)`:

```php
// Before (broken — execute() does not exist):
$db->execute($sql, array(...));

// After (correct — query() exists and throws PDOException on failure):
$db->query($sql, array(...));
```

**Step 3: Implement locking in `toCanonicalIdSafe()` using `getDriverName()`**

Add transaction + driver-branched locking around the collision-check SELECT:

```php
$driver = $db->getDriverName();  // 'mysql' | 'pgsql' | 'sqlite'

if ($driver === 'sqlite') {
    // SQLite has no row-level locking. BEGIN IMMEDIATE acquires a write
    // lock on the entire database file, preventing concurrent writers.
    // This is coarser than FOR UPDATE but is the only option on SQLite.
    $db->beginTransaction();
}

// Build the SELECT with FOR UPDATE for MySQL/PostgreSQL.
// Omit LIMIT inside FOR UPDATE — PostgreSQL syntax requires it after FOR UPDATE,
// and omitting it is safe since the WHERE clause is on the PK.
if ($driver === 'mysql' || $driver === 'pgsql') {
    $sql = 'SELECT 1 AS x FROM ' . $db->quoteIdentifier($fullTable)
        . ' WHERE ' . $db->quoteIdentifier($pkColumn) . ' = :id'
        . ' FOR UPDATE';
    // PostgreSQL: add NOWAIT to avoid indefinite blocking.
    // MySQL: omit NOWAIT (not supported before MySQL 8.0.1).
    if ($driver === 'pgsql') {
        $sql .= ' NOWAIT';
    }
} else {
    // SQLite: plain SELECT (lock already held via BEGIN IMMEDIATE).
    $sql = 'SELECT 1 AS x FROM ' . $db->quoteIdentifier($fullTable)
        . ' WHERE ' . $db->quoteIdentifier($pkColumn) . ' = :id';
}
```

On collision, bump suffix and continue the loop. On success (no row found),
insert the canonical row within the same transaction and commit. Rollback on
any exception.

**What this does NOT change:**
- No existing `PDO_DB` method signatures change.
- No `DatabaseFactory` public API changes.
- `toCanonicalIdSafe()` default behaviour on non-MySQL, non-PostgreSQL drivers
  (e.g. a hypothetical future driver) degrades gracefully to the current
  lock-free path.
- The retry loop, `maxRetries` default, and `RuntimeException` on exhaustion
  are unchanged.

### Note on `quoteIdentifier()` PostgreSQL bug

The backtick quoting bug is pre-existing and out of scope for the Q3 fix.
However, implementing `FOR UPDATE` on PostgreSQL requires fixing it first —
otherwise the locking SELECT will fail with a syntax error before it can
acquire the lock. The minimal fix for `quoteIdentifier()`:

```php
public function quoteIdentifier($identifier)
{
    $driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $q      = ($driver === 'pgsql') ? '"' : '`';
    $parts  = explode('.', $identifier);
    $quoted = array_map(function ($part) use ($q) {
        return $q . str_replace($q, $q . $q, $part) . $q;
    }, $parts);
    return implode('.', $quoted);
}
```

This is additive and backward-compatible on MySQL/SQLite (backtick behaviour
unchanged). If fixing `quoteIdentifier()` is out of scope for this sprint,
PostgreSQL locking support must be deferred until it is fixed.

---

## 7. Open Questions

1. **Transaction scope** — Should the locking transaction in `toCanonicalIdSafe()`
   wrap only the SELECT+INSERT pair (tight), or the entire retry loop (loose)?
   Tight is correct: lock, check, insert, commit; then retry if needed. A
   loop-wide transaction would hold the lock across `usleep()` delays.

2. **PostgreSQL `NOWAIT` vs wait** — `NOWAIT` causes an immediate error if
   another session holds the row lock, requiring the caller to handle
   `PDOException` (error code 55P03). Alternatively, omit `NOWAIT` and accept
   that the SELECT blocks until the lock is released. Which is correct for
   the Lupopedia promotion use case? Given that `toCanonicalIdSafe()` already
   has exponential backoff, `NOWAIT` + retry-on-lock-error is cleaner than
   blocking.

3. **`quoteIdentifier()` fix scope** — Is fixing the backtick PostgreSQL bug
   part of Q3, or deferred? If deferred, PostgreSQL locking cannot be
   implemented correctly until it is resolved.

4. **SQLite in production** — Is `BEGIN IMMEDIATE` acceptable for SQLite
   production workloads, or is SQLite limited to testing only? If SQLite is
   test-only, the `sqlite` branch can be a no-op with a docblock note.

5. **`execute()` method** — Should `execute()` be added to `PDO_DB` as an
   alias for `query()` to avoid future confusion, or should all callers be
   corrected to use `query()`? The latter is preferable (keeps the API minimal)
   but requires an audit of all `$db->execute()` call sites.
