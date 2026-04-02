---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/11_analytics_tracking.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/11_analytics_tracking.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for analytics, tracking, visits, and performance monitoring"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "analytics_tracking"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/database/lupopedia/tables/"
      type: references
      weight: 1.0
      reason: "Detailed table documentation"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Analytics track actor behavior"
    - to: "lupo-docs/prd/14_system_operations.md"
      type: references
      weight: 1.0
      reason: "Analytics inform system performance"
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# PRD: Analytics, Tracking, Visits, and Performance Monitoring

## Overview

**Namespace Purpose:** Provides comprehensive analytics, user tracking, visit monitoring, and performance metrics. This namespace enables data-driven decision making and system optimization.

**Primary Actors:** 
- Analytics administrators (via lupo_visits)
- Performance monitors (via lupo_unified_log)
- Campaign managers (via lupo_analytics_campaign_vars)
- Audit loggers (via lupo_audit_log)
- Path analysts (via lupo_paths_summary)

**Constitutional Compliance:** All tables in this namespace follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

## Tables in This Namespace

| Table | Purpose | Primary Key | Key Application Relationships |
|-------|---------|-------------|------------------------------|
| `lupo_visits` | Individual visit tracking | `visit_id` | Central to analytics |
| `lupo_visits_daily` | Aggregated daily visit statistics | `daily_visit_id` | Daily analytics |
| `lupo_referers_daily` | Daily referrer statistics | `daily_referer_id` | Referrer analytics |
| `lupo_paths_summary` | Path usage statistics | `path_summary_id` | Navigation analytics |
| `lupo_analytics_campaign_vars` | Campaign tracking variables | `campaign_var_id` | Campaign analytics |
| `lupo_audit_log` | System audit logging | `audit_log_id` | Security auditing |
| `lupo_unified_log` | Unified system logging | `unified_log_id` | System monitoring |

## Table Details

### `lupo_visits`

**Purpose:** Tracks individual user visits with detailed session information.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| visit_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | YES | NULL | Foreign reference to lupo_actors |
| session_id | VARCHAR(64) | YES | NULL | Session identifier |
| ip_address | VARCHAR(45) | NO |  | Visitor IP address |
| user_agent | TEXT | YES | NULL | Browser user agent |
| referrer | VARCHAR(512) | YES | NULL | Referrer URL |
| landing_page | VARCHAR(512) | NO |  | Initial landing page |
| exit_page | VARCHAR(512) | YES | NULL | Final page visited |
| page_views | INT | NO | 1 | Number of pages viewed |
| duration_seconds | INT | YES | NULL | Visit duration in seconds |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_visits_actor | actor_id, created_ymdhis, is_deleted | Actor's visits |
| idx_visits_date | created_ymdhis, is_deleted | Date-based queries |
| idx_visits_ip | ip_address, created_ymdhis, is_deleted | IP-based analytics |

### `lupo_visits_daily`

**Purpose:** Stores aggregated daily visit statistics for reporting.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| daily_visit_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| visit_date | DATE | NO |  | Date of visit statistics |
| total_visits | INT | NO | 0 | Total visits for day |
| unique_visitors | INT | NO | 0 | Unique visitors for day |
| page_views | INT | NO | 0 | Total page views for day |
| average_duration | DECIMAL(8,2) | YES | NULL | Average visit duration |
| bounce_rate | DECIMAL(5,2) | NO | 0.00 | Bounce rate percentage |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_visits_daily_date | visit_date, is_deleted | Date-based queries |
| idx_visits_daily_total | total_visits, visit_date, is_deleted | Popular days |

### `lupo_audit_log`

**Purpose:** Logs system audit events for security and compliance.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| audit_log_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| event_type | VARCHAR(64) | NO |  | Type of audit event |
| actor_id | BIGINT | YES | NULL | Foreign reference to lupo_actors |
| target_type | VARCHAR(32) | YES | NULL | Target entity type |
| target_id | BIGINT | YES | NULL | Target entity ID |
| description | TEXT | NO |  | Event description |
| ip_address | VARCHAR(45) | YES | NULL | Actor IP address |
| user_agent | TEXT | YES | NULL | Browser user agent |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| severity | VARCHAR(16) | NO | 'info' | Severity: debug, info, warning, error, critical |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_audit_log_actor | actor_id, event_type, created_ymdhis, is_deleted | Actor's audit trail |
| idx_audit_log_type | event_type, severity, created_ymdhis, is_deleted | Event type queries |
| idx_audit_log_target | target_type, target_id, created_ymdhis, is_deleted | Target-based queries |

### `lupo_unified_log`

**Purpose:** Central logging for all system events and performance metrics.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| unified_log_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| log_level | VARCHAR(16) | NO | 'info' | Level: debug, info, warning, error, critical |
| component | VARCHAR(64) | NO |  | System component generating log |
| actor_id | BIGINT | YES | NULL | Foreign reference to lupo_actors |
| message | TEXT | NO |  | Log message |
| context_json | JSON | YES | NULL | Additional context data |
| performance_ms | INT | YES | NULL | Performance metric in milliseconds |
| memory_usage | BIGINT | YES | NULL | Memory usage in bytes |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_unified_log_level | log_level, component, created_ymdhis, is_deleted | Level-based queries |
| idx_unified_log_component | component, created_ymdhis, is_deleted | Component-based queries |
| idx_unified_log_performance | performance_ms, created_ymdhis, is_deleted | Performance analysis |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 11_analytics_tracking | This → 01_core_identity | Actor analytics | actor_id references |
| 11_analytics_tracking | This → All namespaces | System-wide tracking | Logs events from all areas |
| 11_analytics_tracking | This → 14_system_operations | Performance monitoring | System health metrics |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Normal logging operation | N/A (logs are immutable) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

IP addresses are logged for security but may be anonymized after retention period

User agents are logged for analytics but not PII

Audit logs are immutable and cannot be modified

Soft delete preserves log history for compliance

## Testing Requirements

Unit tests for visit tracking and aggregation

Integration tests for audit logging and unified logging

Performance tests for analytics queries and reporting

Soft delete behavior verification

## Usage Patterns

```php
// Record visit
$visitService = new VisitService();
$visitId = $visitService->recordVisit($actorId, $sessionId, $ipAddress, $userAgent);

// Log audit event
$auditService = new AuditLogService();
$auditId = $auditService->logEvent($eventType, $actorId, $targetType, $targetId, $description);

// Log system event
$unifiedLogService = new UnifiedLogService();
$logId = $unifiedLogService->log($level, $component, $message, $context, $performance);

// Get daily analytics
$analyticsService = new AnalyticsService();
$dailyStats = $analyticsService->getDailyStats($startDate, $endDate);

// Track campaign variable
$campaignService = new AnalyticsCampaignService();
$varId = $campaignService->trackVariable($campaignName, $variableName, $value);
```
