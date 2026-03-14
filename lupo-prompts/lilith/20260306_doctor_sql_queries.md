---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "help"
  file_path_from_root: "lupo-prompts/lilith/20260306_doctor_sql_queries.md"
  web_path: "http://www.lupopedia.com/help/DOCTOR_SQL_QUERIES"
  last_modified_utc: "20260306"
  system_version: "4.0.62"
  channel_id: 42
  actor_id: 2038
  actor_name: "lilith"
  delegation_chain: "lilith:cursor:captain"
  artifact_type: "help"
  artifact_kind: "sql_reference"
  purpose: "SQL queries to check doctor health metrics directly in the database"
  mood_rgb: "4169E1"
  traits: ["help", "sql", "doctor", "health", "queries", "v4.0.62"]
  tags: ["help", "sql", "doctor", "health", "queries", "database"]
  agent_name_identity: "LILITH — Heterodox Reviewer"
  lupo_agent: "lilith"

lupopedia.init:
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      target: "lupo-database/lupopedia/"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/TASK_STATUS_REFERENCE.md", type: "references", weight: 0.7 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 0.8 }
  semantic_tags: ["flare", "help", "sql", "doctor", "health", "lilith"]

lupopedia.see:
  mappings:
    - ["lupo-prompts/lilith/20260306_doctor_sql_queries.md", "http://www.lupopedia.com/help/DOCTOR_SQL_QUERIES"]

lupopedia.close:
  post_actions:
    - type: log_help
      topic: "doctor_sql"
  actor_id: 2

lupopedia.footer:
  version: "4.0.62"
  last_verified: "20260306"
  last_verified_by: "lilith"
---

# SQL queries for DOCTOR health

The DOCTOR agent checks session, actor, and system health. These queries read the same underlying data. Use table prefix from config (e.g. `lupo_`); replace `lupo_` below if your prefix differs.

**Timestamp convention:** All timestamps are BIGINT in **YmdHis UTC** (e.g. `20260306143000`). In MySQL, current UTC as YmdHis: `CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%s') AS SIGNED)`. To display: `STR_TO_DATE(CAST(created_ymdhis AS CHAR), '%Y%m%d%H%i%s')`.

---

## 1. Session health

### 1.1 Active sessions

```sql
-- Count active sessions
SELECT COUNT(*) AS active_sessions
FROM lupo_sessions
WHERE is_active = 1
  AND is_deleted = 0
  AND (expires_ymdhis IS NULL OR expires_ymdhis >= CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%s') AS SIGNED));

-- List active sessions
SELECT
    session_id,
    actor_name,
    actor_id,
    STR_TO_DATE(CAST(created_ymdhis AS CHAR), '%Y%m%d%H%i%s') AS created_utc,
    STR_TO_DATE(CAST(last_seen_ymdhis AS CHAR), '%Y%m%d%H%i%s') AS last_seen_utc,
    is_active,
    is_expired
FROM lupo_sessions
WHERE is_active = 1 AND is_deleted = 0
ORDER BY last_seen_ymdhis DESC
LIMIT 20;
```

### 1.2 Expired sessions

```sql
-- Count expired sessions (expires_ymdhis in the past UTC)
SELECT COUNT(*) AS expired_sessions
FROM lupo_sessions
WHERE is_deleted = 0
  AND expires_ymdhis IS NOT NULL
  AND expires_ymdhis < CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%s') AS SIGNED);

-- List expired sessions
SELECT
    session_id,
    actor_name,
    STR_TO_DATE(CAST(created_ymdhis AS CHAR), '%Y%m%d%H%i%s') AS created_utc,
    STR_TO_DATE(CAST(expires_ymdhis AS CHAR), '%Y%m%d%H%i%s') AS expires_utc
FROM lupo_sessions
WHERE is_deleted = 0
  AND expires_ymdhis IS NOT NULL
  AND expires_ymdhis < CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%s') AS SIGNED)
ORDER BY expires_ymdhis;
```

### 1.3 Sessions per actor

```sql
SELECT
    actor_name,
    COUNT(*) AS session_count,
    MAX(STR_TO_DATE(CAST(last_seen_ymdhis AS CHAR), '%Y%m%d%H%i%s')) AS last_activity_utc
FROM lupo_sessions
WHERE is_deleted = 0
GROUP BY actor_name
ORDER BY session_count DESC;
```

---

## 2. Actor health

### 2.1 Actors by type

```sql
SELECT
    actor_type,
    COUNT(*) AS count
FROM lupo_actors
WHERE is_deleted = 0
GROUP BY actor_type;
```

### 2.2 Orphaned session references (sessions whose actor_name is not in lupo_actors)

```sql
SELECT s.session_id, s.actor_name
FROM lupo_sessions s
LEFT JOIN lupo_actors a ON a.actor_name = s.actor_name AND a.is_deleted = 0
WHERE a.actor_name IS NULL
  AND s.is_deleted = 0;
```

### 2.3 Paired actor relationships

```sql
-- Agents with paired human (by actor_id)
SELECT
    a1.actor_name AS agent_name,
    a1.actor_id AS agent_id,
    a2.actor_name AS human_name,
    a2.actor_id AS human_id
FROM lupo_actors a1
JOIN lupo_actors a2 ON a1.paired_actor_id = a2.actor_id AND a2.is_deleted = 0
WHERE a1.paired_actor_id > 0
  AND a1.is_deleted = 0;

-- Count agents by pairing status
SELECT
    CASE WHEN paired_actor_id > 0 THEN 'paired' ELSE 'unpaired' END AS pairing_status,
    COUNT(*) AS count
FROM lupo_actors
WHERE actor_type IN ('agent', 'ide_agent')
  AND is_deleted = 0
GROUP BY CASE WHEN paired_actor_id > 0 THEN 'paired' ELSE 'unpaired' END;
```

---

## 3. Database health

### 3.1 Table sizes (MySQL)

```sql
SELECT
    table_name,
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS size_mb
FROM information_schema.tables
WHERE table_schema = DATABASE()
ORDER BY (data_length + index_length) DESC;
```

### 3.2 Session continuity (activity span per actor)

```sql
SELECT
    actor_name,
    MIN(STR_TO_DATE(CAST(created_ymdhis AS CHAR), '%Y%m%d%H%i%s')) AS first_seen_utc,
    MAX(STR_TO_DATE(CAST(last_seen_ymdhis AS CHAR), '%Y%m%d%H%i%s')) AS last_seen_utc,
    TIMESTAMPDIFF(DAY,
        MIN(STR_TO_DATE(CAST(created_ymdhis AS CHAR), '%Y%m%d%H%i%s')),
        MAX(STR_TO_DATE(CAST(last_seen_ymdhis AS CHAR), '%Y%m%d%H%i%s'))
    ) AS span_days
FROM lupo_sessions
WHERE is_deleted = 0
GROUP BY actor_name
ORDER BY span_days DESC;
```

---

## 4. Combined health snapshot

```sql
SELECT 'ACTIVE_SESSIONS' AS metric, COUNT(*) AS value
FROM lupo_sessions
WHERE is_active = 1 AND is_deleted = 0
  AND (expires_ymdhis IS NULL OR expires_ymdhis >= CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%s') AS SIGNED))

UNION ALL

SELECT 'EXPIRED_SESSIONS', COUNT(*)
FROM lupo_sessions
WHERE is_deleted = 0
  AND expires_ymdhis IS NOT NULL
  AND expires_ymdhis < CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%s') AS SIGNED)

UNION ALL

SELECT 'TOTAL_ACTORS', COUNT(*)
FROM lupo_actors
WHERE is_deleted = 0

UNION ALL

SELECT 'PAIRED_AGENTS', COUNT(*)
FROM lupo_actors
WHERE paired_actor_id > 0 AND is_deleted = 0

UNION ALL

SELECT 'UNPAIRED_AGENTS', COUNT(*)
FROM lupo_actors
WHERE actor_type IN ('agent', 'ide_agent')
  AND (paired_actor_id = 0 OR paired_actor_id IS NULL)
  AND is_deleted = 0

UNION ALL

SELECT 'ORPHANED_SESSIONS', COUNT(*)
FROM lupo_sessions s
LEFT JOIN lupo_actors a ON a.actor_name = s.actor_name AND a.is_deleted = 0
WHERE a.actor_name IS NULL AND s.is_deleted = 0;
```

---

## 5. Tasks (file-based)

Task status is stored in **files** under channel task directories, not in the database. See [lupo-docs/TASK_STATUS_REFERENCE.md](../../docs/TASK_STATUS_REFERENCE.md). To count by status, use the filesystem (e.g. `find .../tasks/active/ -name "*.md"`). There is no standard `lupo_tasks` table in the core schema.

---

## 6. When to use SQL vs DOCTOR CLI

| Scenario | Use | Why |
|----------|-----|-----|
| Quick system check | `php lupo-bin/lupo.php doctor` | Human-readable summary |
| Deep investigation | SQL above | Full data access |
| Automated monitoring | SQL + cron | Can generate alerts |
| Historical analysis | SQL | Time ranges on YmdHis columns |
| Debugging | Both | Cross-validate |

---

## 7. phpMyAdmin

1. Open your Lupopedia database, go to the SQL tab.
2. Paste any query; use `lupo_` or your actual table prefix.
3. Bookmark frequent health queries for quick reruns.

---

**Related:** [Final verification (20260306_doctor_sql_final.md)](20260306_doctor_sql_final.md) — LILITH sign-off, 10/10 canonical.

---

**END OF GUIDE — LILITH, Heterodox Reviewer**  
Channel 42 · 20260306
