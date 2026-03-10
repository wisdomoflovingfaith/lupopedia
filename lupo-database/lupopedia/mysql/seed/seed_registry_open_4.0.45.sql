-- ============================================================================
-- REGISTRY OPEN (GAPS) SEEDING FOR LUPOPEDIA 4.0.45
-- ============================================================================
-- Purpose: Populate lupo_registry_open with available IDs (gaps between reserved)
-- Run after: seed_registry_comprehensive_4.0.45.sql
-- ============================================================================

SET @now = 20260225000000;

-- ============================================================================
-- ACTOR ID GAPS
-- Reserved: 0, 1-5 (core agents), 1000-1004 (IDE agents), 10000 (root)
-- Available: 6-999, 1005-9999, 10001-10099 (first 100 human slots)
-- ============================================================================

-- Gaps 6-999 (994 IDs)
INSERT INTO lupo_registry_open (entity_type, entity_index_id, reason, created_ymdhis)
SELECT 'actor', n, 'available_for_system_agents', @now
FROM (
  SELECT 6 + a.N + b.N * 10 + c.N * 100 AS n
  FROM 
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c
) numbers
WHERE n <= 999;

-- Gaps 1005-1999 (995 IDs for IDE agents)
INSERT INTO lupo_registry_open (entity_type, entity_index_id, reason, created_ymdhis)
SELECT 'actor', n, 'available_for_ide_agents', @now
FROM (
  SELECT 1005 + a.N + b.N * 10 + c.N * 100 AS n
  FROM 
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c
) numbers
WHERE n <= 1999;

-- Gaps 2000-9999 (8000 IDs for future system use)
INSERT INTO lupo_registry_open (entity_type, entity_index_id, reason, created_ymdhis)
SELECT 'actor', n, 'reserved_for_future_system', @now
FROM (
  SELECT 2000 + a.N + b.N * 10 + c.N * 100 + d.N * 1000 AS n
  FROM 
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7) d
) numbers
WHERE n <= 9999;

-- Gaps 10001-10999 (999 IDs for initial human users)
INSERT INTO lupo_registry_open (entity_type, entity_index_id, reason, created_ymdhis)
SELECT 'actor', n, 'available_for_human_users', @now
FROM (
  SELECT 10001 + a.N + b.N * 10 + c.N * 100 AS n
  FROM 
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c
) numbers
WHERE n <= 10999;

-- ============================================================================
-- CHANNEL ID GAPS
-- Reserved: 0, 1, 42, 51
-- Available: 2-41, 43-50, 52-999
-- ============================================================================

-- Gaps 2-41 (40 IDs)
INSERT INTO lupo_registry_open (entity_type, entity_index_id, reason, created_ymdhis)
SELECT 'channel', n, 'available_for_channels', @now
FROM (
  SELECT 2 + a.N + b.N * 10 AS n
  FROM 
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) b
) numbers
WHERE n <= 41;

-- Gaps 43-50 (8 IDs)
INSERT INTO lupo_registry_open (entity_type, entity_index_id, reason, created_ymdhis)
VALUES
('channel', 43, 'available_for_channels', @now),
('channel', 44, 'available_for_channels', @now),
('channel', 45, 'available_for_channels', @now),
('channel', 46, 'available_for_channels', @now),
('channel', 47, 'available_for_channels', @now),
('channel', 48, 'available_for_channels', @now),
('channel', 49, 'available_for_channels', @now),
('channel', 50, 'available_for_channels', @now);

-- Gaps 52-999 (948 IDs)
INSERT INTO lupo_registry_open (entity_type, entity_index_id, reason, created_ymdhis)
SELECT 'channel', n, 'available_for_channels', @now
FROM (
  SELECT 52 + a.N + b.N * 10 + c.N * 100 AS n
  FROM 
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c
) numbers
WHERE n <= 999;

-- ============================================================================
-- AGENT ID GAPS
-- Reserved: 0, 1-5
-- Available: 6-999
-- ============================================================================

INSERT INTO lupo_registry_open (entity_type, entity_index_id, reason, created_ymdhis)
SELECT 'agent', n, 'available_for_agents', @now
FROM (
  SELECT 6 + a.N + b.N * 10 + c.N * 100 AS n
  FROM 
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c
) numbers
WHERE n <= 999;

-- ============================================================================
-- DEPARTMENT ID GAPS
-- Reserved: 0, 1
-- Available: 2-999
-- ============================================================================

INSERT INTO lupo_registry_open (entity_type, entity_index_id, reason, created_ymdhis)
SELECT 'department', n, 'available_for_departments', @now
FROM (
  SELECT 2 + a.N + b.N * 10 + c.N * 100 AS n
  FROM 
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c
) numbers
WHERE n <= 999;

-- ============================================================================
-- THREAD ID GAPS
-- Reserved: 0
-- Available: 1-9999
-- ============================================================================

INSERT INTO lupo_registry_open (entity_type, entity_index_id, reason, created_ymdhis)
SELECT 'thread', n, 'available_for_threads', @now
FROM (
  SELECT 1 + a.N + b.N * 10 + c.N * 100 + d.N * 1000 AS n
  FROM 
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c,
    (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) d
) numbers
WHERE n <= 9999;

-- ============================================================================
-- END OF REGISTRY OPEN SEEDING
-- ============================================================================
