---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "documentation"
  system_version: "4.0.80"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/analytics/lupo_unified_log.md"
  web_path: "http://www.lupopedia.com/lupo-docs/database/lupopedia/tables/active/analytics/lupo_unified_log"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  delegation_chain: "hermes:wolfie"
  artifact_type: "documentation"
  artifact_kind: "table_documentation"
  purpose: "Documentation for lupo_unified_log table - unified system logging and analytics"
  tags: ["table_documentation", "analytics", "4.0.80", "top_50"]
---

# lupo_unified_log.md

## Table Overview

The `lupo_unified_log` table provides a centralized logging system for the Lupopedia platform, capturing system events, user actions, errors, and performance metrics in a structured format. It serves as the primary data source for analytics, monitoring, debugging, and compliance reporting.

**Namespace**: `analytics`  
**Table Type**: Logging / Analytics  
**Criticality**: HIGH - System observability and monitoring

## Where This Table Is Used

- **System Monitoring**: Real-time system health and performance tracking
- **Analytics Platform**: User behavior analysis and system usage patterns
- **Error Tracking**: Bug detection, error analysis, and debugging support
- **Security Auditing**: Security event logging and threat detection
- **Compliance Reporting**: Audit trails and regulatory compliance
- **Business Intelligence**: Decision support and strategic planning
- **Performance Optimization**: System performance analysis and bottleneck identification

## Columns

| Column | Type | Description | Notes |
|--------|------|-------------|-------|
| `log_id` | bigint NOT NULL | Primary key, auto-increment | Unique identifier for each log entry |
| `log_level` | varchar(16) NOT NULL | Log severity level | DEBUG, INFO, WARN, ERROR, FATAL |
| `category` | varchar(64) NOT NULL | Log category | system, user, security, performance, etc. |
| `event_type` | varchar(128) NOT NULL | Specific event type | login, page_view, api_call, error, etc. |
| `actor_id` | bigint | User/actor ID if applicable | Links to lupo_actors table |
| `session_id` | varchar(128) | Session identifier | For tracking user sessions |
| `request_id` | varchar(64) | Unique request identifier | For tracing related events |
| `ip_address` | varchar(45) | Client IP address | Supports IPv4 and IPv6 |
| `user_agent` | varchar(500) | Client user agent string | Browser/application identification |
| `message` | text | Log message content | Human-readable description |
| `details_json` | longtext | Additional event details | JSON structure with event-specific data |
| `created_ymdhis` | bigint NOT NULL DEFAULT 0 | Event timestamp | Unix timestamp format |
| `microtime` | decimal(10,6) | Microsecond precision timestamp | For high-resolution timing |
| `duration_ms` | int | Event duration in milliseconds | Performance measurement |
| `memory_usage` | int | Memory usage in bytes | Memory consumption tracking |
| `peak_memory` | int | Peak memory usage in bytes | Memory peak tracking |
| `cpu_usage` | decimal(5,2) | CPU usage percentage | System resource monitoring |
| `error_code` | varchar(32) | Error code if applicable | For error events |
| `stack_trace` | text | Error stack trace | For debugging errors |
| `file_path` | varchar(512) | Source file path | For code-level debugging |
| `line_number` | int | Source file line number | For code-level debugging |
| `function_name` | varchar(128) | Function or method name | For code-level debugging |
| `class_name` | varchar(128) | Class name | For code-level debugging |
| `module` | varchar(64) | System module or component | Module-level filtering |
| `version` | varchar(32) | Software version | Version-specific issues |
| `environment` | varchar(32) | Environment (dev, staging, prod) | Environment filtering |
| `server_id` | varchar(64) | Server identifier | Multi-server deployments |
| `tags` | varchar(500) | Comma-separated tags | Flexible categorization |
| `correlation_id` | varchar(64) | Correlation identifier | Cross-service tracing |
| `parent_log_id` | bigint | Parent log entry ID | Hierarchical logging |
| `batch_id` | varchar(64) | Batch processing identifier | Batch operation tracking |
| `retry_count` | int NOT NULL DEFAULT 0 | Number of retry attempts | Failure handling |
| `status` | varchar(16) NOT NULL DEFAULT 'success' | Event status | success, failure, partial |
| `severity_score` | decimal(5,2) | Calculated severity score | 0.00-99.99 |

## Indexes

| Index Name | Columns | Type | Purpose |
|------------|--------|------|---------|
| `PRIMARY` | `log_id` | PRIMARY KEY | Unique row identification |
| `idx_log_level` | `log_level` | INDEX | Filter by severity level |
| `idx_category` | `category` | INDEX | Filter by category |
| `idx_event_type` | `event_type` | INDEX | Filter by event type |
| `idx_actor_id` | `actor_id` | INDEX | Filter by user/actor |
| `idx_created` | `created_ymdhis` | INDEX | Time-based queries |
| `idx_session_id` | `session_id` | INDEX | Session-based analysis |
| `idx_request_id` | `request_id` | INDEX | Request tracing |
| `idx_ip_address` | `ip_address` | INDEX | IP-based analysis |
| `idx_status` | `status` | INDEX | Filter by status |
| `idx_environment` | `environment` | INDEX | Environment filtering |
| `idx_server_id` | `server_id` | INDEX | Server-based filtering |
| `idx_correlation_id` | `correlation_id` | INDEX | Cross-service tracing |
| `idx_composite_time_level` | `created_ymdhis`, `log_level` | INDEX | Common query pattern |
| `idx_composite_category_time` | `category`, `created_ymdhis` | INDEX | Category timeline analysis |

## Relationships

### Foreign Key Relationships (Logical)
- **actor_id** → `lupo_actors.actor_id` (User/actor who performed the action)
- **parent_log_id** → `lupo_unified_log.log_id` (Parent log entry for hierarchical logging)

### Referenced By
- **Analytics Dashboard**: Real-time monitoring and visualization
- **Error Tracking System**: Error aggregation and alerting
- **Performance Monitoring**: System performance analysis
- **Security Monitoring**: Threat detection and analysis

## Log Levels

| Level | Description | Typical Use Cases |
|-------|-------------|-------------------|
| `DEBUG` | Detailed debugging information | Development troubleshooting |
| `INFO` | General information messages | Normal system operation |
| `WARN` | Warning conditions | Potential issues that need attention |
| `ERROR` | Error conditions | System errors that need investigation |
| `FATAL` | Critical system failures | System-wide emergencies |

## Categories

| Category | Description | Examples |
|----------|-------------|----------|
| `system` | System-level events | Startup, shutdown, configuration |
| `user` | User actions | Login, logout, page views |
| `security` | Security events | Authentication, authorization |
| `performance` | Performance metrics | Response times, resource usage |
| `api` | API calls and responses | REST endpoints, webhooks |
| `database` | Database operations | Queries, connections, transactions |
| `cache` | Cache operations | Hits, misses, evictions |
| `email` | Email operations | Sends, deliveries, bounces |
| `payment` | Payment processing | Transactions, failures |
| `integration` | Third-party integrations | External service calls |

## Usage in Code

### PHP Logger Service
```php
class UnifiedLogger {
    public function log(string $level, string $category, string $eventType, string $message, array $context = []): void {
        $data = [
            'log_level' => $level,
            'category' => $category,
            'event_type' => $eventType,
            'message' => $message,
            'details_json' => json_encode($context),
            'created_ymdhis' => time(),
            'microtime' => microtime(true),
            'actor_id' => $this->getCurrentActorId(),
            'session_id' => $this->getCurrentSessionId(),
            'request_id' => $this->getCurrentRequestId(),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'environment' => $this->getEnvironment(),
            'server_id' => $this->getServerId(),
            'tags' => $this->generateTags($context),
            'correlation_id' => $this->getCorrelationId(),
            'severity_score' => $this->calculateSeverityScore($level, $category, $context)
        ];
        
        // Add performance data if available
        if (isset($context['duration'])) {
            $data['duration_ms'] = $context['duration'];
        }
        
        // Add error data if applicable
        if ($level === 'ERROR' || $level === 'FATAL') {
            $data['error_code'] = $context['error_code'] ?? null;
            $data['stack_trace'] = $context['exception'] ? $context['exception']->getTraceAsString() : null;
            $data['file_path'] = $context['exception'] ? $context['exception']->getFile() : null;
            $data['line_number'] = $context['exception'] ? $context['exception']->getLine() : null;
        }
        
        $this->db->insert('lupo_unified_log', $data);
    }
    
    public function info(string $category, string $eventType, string $message, array $context = []): void {
        $this->log('INFO', $category, $eventType, $message, $context);
    }
    
    public function error(string $category, string $eventType, string $message, array $context = []): void {
        $this->log('ERROR', $category, $eventType, $message, $context);
    }
    
    public function performance(string $eventType, float $duration, array $context = []): void {
        $this->log('INFO', 'performance', $eventType, 'Performance metric', array_merge($context, [
            'duration' => round($duration * 1000), // Convert to milliseconds
            'memory_usage' => memory_get_usage(),
            'peak_memory' => memory_get_peak_usage()
        ]));
    }
}
```

### Performance Monitoring
```php
class PerformanceMonitor {
    public function measureOperation(string $operation, callable $callback): mixed {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        try {
            $result = $callback();
            
            $this->logger->performance($operation, microtime(true) - $startTime, [
                'status' => 'success',
                'memory_delta' => memory_get_usage() - $startMemory
            ]);
            
            return $result;
        } catch (Exception $e) {
            $this->logger->performance($operation, microtime(true) - $startTime, [
                'status' => 'failure',
                'memory_delta' => memory_get_usage() - $startMemory,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}
```

### Error Handling
```php
class ErrorHandler {
    public function handleException(Throwable $exception): void {
        $this->logger->error('system', 'exception', 'Uncaught exception', [
            'exception' => $exception,
            'error_code' => $exception->getCode(),
            'file_path' => $exception->getFile(),
            'line_number' => $exception->getLine(),
            'function_name' => $exception->getTrace()[0]['function'] ?? null,
            'class_name' => $exception->getTrace()[0]['class'] ?? null,
            'request_data' => $_REQUEST,
            'session_data' => $_SESSION
        ]);
    }
}
```

## Analytics Queries

### System Health Dashboard
```sql
-- Recent error rates
SELECT 
    DATE(FROM_UNIXTIME(created_ymdhis)) as date,
    COUNT(*) as total_logs,
    SUM(CASE WHEN log_level = 'ERROR' THEN 1 ELSE 0 END) as errors,
    SUM(CASE WHEN log_level = 'FATAL' THEN 1 ELSE 0 END) as fatal,
    ROUND((SUM(CASE WHEN log_level IN ('ERROR', 'FATAL') THEN 1 ELSE 0 END) * 100.0 / COUNT(*)), 2) as error_rate
FROM lupo_unified_log 
WHERE created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 7 DAY))
GROUP BY DATE(FROM_UNIXTIME(created_ymdhis))
ORDER BY date;

-- Performance metrics
SELECT 
    event_type,
    AVG(duration_ms) as avg_duration,
    MIN(duration_ms) as min_duration,
    MAX(duration_ms) as max_duration,
    COUNT(*) as count
FROM lupo_unified_log 
WHERE category = 'performance' 
  AND created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
  AND duration_ms IS NOT NULL
GROUP BY event_type
ORDER BY avg_duration DESC;
```

### User Behavior Analysis
```sql
-- Active users by hour
SELECT 
    HOUR(FROM_UNIXTIME(created_ymdhis)) as hour,
    COUNT(DISTINCT actor_id) as active_users,
    COUNT(*) as total_actions
FROM lupo_unified_log 
WHERE category = 'user' 
  AND actor_id IS NOT NULL
  AND created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
GROUP BY HOUR(FROM_UNIXTIME(created_ymdhis))
ORDER BY hour;

-- Most common events
SELECT 
    event_type,
    COUNT(*) as count,
    COUNT(DISTINCT actor_id) as unique_users
FROM lupo_unified_log 
WHERE created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
GROUP BY event_type
ORDER BY count DESC
LIMIT 20;
```

## Data Retention and Cleanup

### Retention Policy
- **DEBUG logs**: 7 days
- **INFO logs**: 30 days
- **WARN logs**: 90 days
- **ERROR logs**: 365 days
- **FATAL logs**: 1095 days (3 years)

### Cleanup Strategy
```sql
-- Delete old DEBUG logs
DELETE FROM lupo_unified_log 
WHERE log_level = 'DEBUG' 
  AND created_ymdhis < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 7 DAY));

-- Delete old INFO logs
DELETE FROM lupo_unified_log 
WHERE log_level = 'INFO' 
  AND created_ymdhis < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY));
```

### Archiving Strategy
```sql
-- Archive old logs to separate table
CREATE TABLE lupo_unified_log_archive LIKE lupo_unified_log;

INSERT INTO lupo_unified_log_archive 
SELECT * FROM lupo_unified_log 
WHERE created_ymdhis < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 90 DAY));

DELETE FROM lupo_unified_log 
WHERE created_ymdhis < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 90 DAY));
```

## Performance Considerations

### Indexing Strategy
- **Time-based Queries**: Composite index on `created_ymdhis, log_level`
- **Category Filtering**: Index on `category, created_ymdhis`
- **User Analysis**: Index on `actor_id, created_ymdhis`
- **Request Tracing**: Index on `request_id`

### Partitioning (Recommended)
```sql
-- Monthly partitioning
PARTITION BY RANGE (UNIX_TIMESTAMP(FROM_UNIXTIME(created_ymdhis))) (
    PARTITION p202603 VALUES LESS THAN (UNIX_TIMESTAMP('2026-04-01')),
    PARTITION p202604 VALUES LESS THAN (UNIX_TIMESTAMP('2026-05-01')),
    PARTITION p202605 VALUES LESS THAN (UNIX_TIMESTAMP('2026-06-01')),
    PARTITION pmax VALUES LESS THAN MAXVALUE
);
```

### Optimization Tips
- **Batch Inserts**: Use batch inserts for high-volume logging
- **Async Logging**: Consider async logging for performance-critical paths
- **Sampling**: Sample DEBUG logs in production
- **Compression**: Use table compression for archived data

## Security Considerations

### Data Protection
- **PII Handling**: Avoid logging sensitive personal information
- **Data Sanitization**: Sanitize log entries before storage
- **Access Control**: Restrict access to sensitive log data
- **Encryption**: Encrypt sensitive log data at rest

### Privacy Compliance
- **Data Minimization**: Only log necessary information
- **Retention Policies**: Comply with data retention requirements
- **Right to Erasure**: Implement log deletion for privacy requests

## Integration with Monitoring Systems

### External Monitoring
```php
class MonitoringIntegration {
    public function sendToExternalSystem(array $logEntry): void {
        if ($logEntry['log_level'] === 'ERROR' || $logEntry['log_level'] === 'FATAL') {
            $this->sendAlert($logEntry);
        }
        
        if ($logEntry['category'] === 'performance') {
            $this->sendMetrics($logEntry);
        }
    }
    
    private function sendAlert(array $logEntry): void {
        // Send to PagerDuty, Slack, etc.
    }
    
    private function sendMetrics(array $logEntry): void {
        // Send to Prometheus, DataDog, etc.
    }
}
```

### Real-time Analytics
```php
class RealTimeAnalytics {
    public function processLogStream(): void {
        $this->db->execute(
            "SELECT * FROM lupo_unified_log 
             WHERE created_ymdhis > ? 
             ORDER BY created_ymdhis ASC",
            [time() - 60] // Last minute
        );
    }
}
```

## Troubleshooting

### Common Issues
1. **High Storage Usage**: Implement proper retention policies
2. **Slow Queries**: Optimize indexes and consider partitioning
3. **Missing Logs**: Check logger configuration and error handling
4. **Performance Impact**: Use async logging for high-volume scenarios

### Debug Queries
```sql
-- Check recent error rates
SELECT 
    log_level,
    COUNT(*) as count,
    COUNT(DISTINCT actor_id) as affected_users
FROM lupo_unified_log 
WHERE created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 HOUR))
GROUP BY log_level
ORDER BY count DESC;

-- Find slow operations
SELECT 
    event_type,
    AVG(duration_ms) as avg_duration,
    COUNT(*) as count
FROM lupo_unified_log 
WHERE category = 'performance' 
  AND duration_ms > 1000 -- > 1 second
  AND created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
GROUP BY event_type
ORDER BY avg_duration DESC;

-- Check system errors by module
SELECT 
    module,
    COUNT(*) as error_count,
    COUNT(DISTINCT error_code) as unique_errors
FROM lupo_unified_log 
WHERE log_level = 'ERROR' 
  AND created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
GROUP BY module
ORDER BY error_count DESC;
```

---

**Last Updated**: 2026-03-17  
**Namespace**: analytics  
**Version**: 4.0.80  
**Maintainer**: HERMES (actor_id 102)  
**Review Status**: Ready for LILITH validation
