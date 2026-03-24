---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_auth_audit_log.md
  web_path: http://www.lupopedia.com/lupo-docs/database/lupopedia/tables/active/auth/lupo_auth_audit_log
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 102
  actor_name: hermes
  faucet_name: cascade
  delegation_chain: hermes:wolfie
  artifact_type: documentation
  artifact_kind: table_documentation
  purpose: Documentation for lupo_auth_audit_log table - authentication event auditing
  tags:
  - table_documentation
  - auth
  - 4.0.80
  - top_50
  when_updated: '20260324174654'
lupopedia:
  footer:
    last_verified: '20260324174654'
    last_verified_by: cursor
    last_verified_by_actor_id: 102
    orchestrator: cursor:root
---

# lupo_auth_audit_log.md

## Table Overview

The `lupo_auth_audit_log` table provides comprehensive auditing of all authentication-related events in the Lupopedia system. It tracks login attempts, provider usage, security events, and administrative changes to authentication configurations.

**Namespace**: `auth`  
**Table Type**: Audit / Security  
**Criticality**: HIGH - Security compliance and monitoring

## Where This Table Is Used

- **Security Monitoring**: Real-time monitoring of authentication events
- **Compliance Reporting**: Audit trails for security compliance requirements
- **Security Analysis**: Pattern detection and anomaly identification
- **Troubleshooting**: Debugging authentication issues and provider problems
- **Admin Interface**: Authentication event logs and security dashboard
- **Integration**: Security information and event management (SIEM) systems

## Columns

| Column | Type | Description | Notes |
|--------|------|-------------|-------|
| `audit_log_id` | bigint NOT NULL | Primary key, auto-increment | Unique identifier for each audit event |
| `event_type` | varchar(64) NOT NULL | Type of authentication event | login, logout, failed_login, provider_config, etc. |
| `actor_id` | bigint | User/actor ID if applicable | Links to lupo_actors table, NULL for system events |
| `auth_provider_id` | bigint | Authentication provider used | Links to lupo_auth_providers table |
| `provider_key` | varchar(64) | Provider key for quick lookup | Denormalized for performance |
| `ip_address` | varchar(45) | Client IP address | Supports IPv4 and IPv6 |
| `user_agent` | varchar(500) | Client user agent string | Browser/application identification |
| `session_id` | varchar(128) | Session identifier | Links to session management |
| `success` | tinyint NOT NULL | Event success/failure status | 1 = success, 0 = failure |
| `failure_reason` | varchar(255) | Reason for authentication failure | Invalid credentials, provider unavailable, etc. |
| `details_json` | text | Additional event details | JSON structure with event-specific data |
| `created_ymdhis` | bigint NOT NULL DEFAULT 0 | Event timestamp | Unix timestamp format |
| `request_id` | varchar(64) | Unique request identifier | For tracing related events |
| `actor_type` | varchar(32) | Type of actor (user, admin, system) | Categorizes event source |
| `risk_score` | decimal(5,2) DEFAULT 0.00 | Security risk score (0.00-99.99) | Calculated based on event characteristics |
| `location_country` | varchar(2) | Two-letter country code | ISO 3166-1 alpha-2 |
| `location_city` | varchar(100) | City name | GeoIP location data |
| `device_fingerprint` | varchar(128) | Device fingerprint hash | For device tracking and anomaly detection |

## Indexes

| Index Name | Columns | Type | Purpose |
|------------|--------|------|---------|
| `PRIMARY` | `audit_log_id` | PRIMARY KEY | Unique row identification |
| `idx_event_type` | `event_type` | INDEX | Filter by event type |
| `idx_actor_id` | `actor_id` | INDEX | Filter by specific user |
| `idx_provider_id` | `auth_provider_id` | INDEX | Filter by authentication provider |
| `idx_created` | `created_ymdhis` | INDEX | Time-based queries and cleanup |
| `idx_success` | `success` | INDEX | Separate successful/failed events |
| `idx_ip_address` | `ip_address` | INDEX | IP-based security analysis |
| `idx_risk_score` | `risk_score` | INDEX | High-risk event identification |
| `idx_request_id` | `request_id` | INDEX | Request tracing |
| `idx_composite_event_time` | `event_type`, `created_ymdhis` | INDEX | Common query pattern |

## Relationships

### Foreign Key Relationships (Logical)
- **actor_id** → `lupo_actors.actor_id` (User/actor who performed the action)
- **auth_provider_id** → `lupo_auth_providers.auth_provider_id` (Authentication provider used)

### Referenced By
- **Security Reports**: Authentication event analysis
- **User Activity Logs**: User authentication history
- **Provider Analytics**: Provider usage statistics

## Event Types

| Event Type | Description | Success Possible |
|------------|-------------|------------------|
| `login` | Successful user login | Yes |
| `logout` | User logout | Yes |
| `failed_login` | Failed login attempt | No |
| `provider_config` | Provider configuration change | Yes |
| `provider_disabled` | Provider disabled by admin | Yes |
| `provider_enabled` | Provider enabled by admin | Yes |
| `password_change` | Password changed | Yes |
| `password_reset` | Password reset requested | Yes |
| `account_locked` | Account locked due to security | Yes |
| `account_unlocked` | Account unlocked by admin | Yes |
| `suspicious_activity` | Suspicious authentication pattern | No |
| `api_login` | API-based authentication | Yes |
| `session_expired` | Session expiration | No |
| `concurrent_login` | Multiple concurrent sessions detected | No |

## Security Features

### Risk Scoring
The `risk_score` field calculates security risk based on:
- **IP Reputation**: Known malicious IPs get higher scores
- **Geolocation**: Unusual locations increase risk
- **Time Patterns**: Login outside normal hours
- **Device Recognition**: New devices increase risk
- **Failure Patterns**: Multiple failures increase risk

### Anomaly Detection
- **Brute Force Detection**: Multiple failed attempts from same IP
- **Geographic Anomalies**: Logins from unusual locations
- **Temporal Anomalies**: Login at unusual times
- **Device Anomalies**: New or unrecognized devices

## Usage in Code

### PHP Service Example
```php
class AuthAuditService {
    public function logEvent(array $eventData): void {
        $data = [
            'event_type' => $eventData['event_type'],
            'actor_id' => $eventData['actor_id'] ?? null,
            'auth_provider_id' => $eventData['auth_provider_id'] ?? null,
            'provider_key' => $eventData['provider_key'] ?? null,
            'ip_address' => $eventData['ip_address'],
            'user_agent' => $eventData['user_agent'],
            'session_id' => $eventData['session_id'] ?? null,
            'success' => $eventData['success'] ? 1 : 0,
            'failure_reason' => $eventData['failure_reason'] ?? null,
            'details_json' => json_encode($eventData['details'] ?? []),
            'created_ymdhis' => time(),
            'request_id' => $eventData['request_id'] ?? uniqid(),
            'actor_type' => $eventData['actor_type'] ?? 'user',
            'risk_score' => $this->calculateRiskScore($eventData),
            'location_country' => $eventData['location_country'] ?? null,
            'location_city' => $eventData['location_city'] ?? null,
            'device_fingerprint' => $eventData['device_fingerprint'] ?? null
        ];
        
        $this->db->insert('lupo_auth_audit_log', $data);
    }
    
    public function getRecentFailures(int $hours = 24): array {
        return $this->db->fetchAll(
            "SELECT * FROM lupo_auth_audit_log 
             WHERE event_type = 'failed_login' 
               AND created_ymdhis > ? 
             ORDER BY created_ymdhis DESC",
            [time() - ($hours * 3600)]
        );
    }
    
    public function getHighRiskEvents(float $threshold = 70.0): array {
        return $this->db->fetchAll(
            "SELECT * FROM lupo_auth_audit_log 
             WHERE risk_score >= ? 
             ORDER BY risk_score DESC, created_ymdhis DESC",
            [$threshold]
        );
    }
}
```

### Risk Score Calculation
```php
private function calculateRiskScore(array $eventData): float {
    $score = 0.0;
    
    // Base risk by event type
    $eventRisk = [
        'failed_login' => 20.0,
        'suspicious_activity' => 80.0,
        'account_locked' => 50.0,
        'login' => 5.0,
        'logout' => 0.0
    ];
    
    $score += $eventRisk[$eventData['event_type']] ?? 10.0;
    
    // IP-based risk
    if ($this->isSuspiciousIP($eventData['ip_address'])) {
        $score += 30.0;
    }
    
    // Geographic risk
    if ($this->isUnusualLocation($eventData['actor_id'], $eventData['location_country'])) {
        $score += 25.0;
    }
    
    // Time-based risk
    if ($this->isUnusualTime($eventData['actor_id'])) {
        $score += 15.0;
    }
    
    return min(99.99, $score);
}
```

## Data Retention and Cleanup

### Retention Policy
- **Standard Events**: Retain for 365 days
- **High-Risk Events**: Retain for 1095 days (3 years)
- **Security Events**: Retain for 1825 days (5 years)
- **Failed Logins**: Retain for 90 days

### Cleanup Strategy
```sql
-- Delete standard events older than 365 days
DELETE FROM lupo_auth_audit_log 
WHERE event_type IN ('login', 'logout') 
  AND created_ymdhis < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 YEAR));

-- Delete failed logins older than 90 days
DELETE FROM lupo_auth_audit_log 
WHERE event_type = 'failed_login' 
  AND created_ymdhis < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 90 DAY));
```

## Performance Considerations

### Indexing Strategy
- **Time-based Queries**: Index on `created_ymdhis` for date range queries
- **Event Filtering**: Index on `event_type` for event-specific queries
- **Security Analysis**: Composite index on `event_type, created_ymdhis`

### Partitioning (Optional)
For high-volume systems, consider partitioning by date:
```sql
-- Monthly partitioning example
PARTITION BY RANGE (UNIX_TIMESTAMP(FROM_UNIXTIME(created_ymdhis))) (
    PARTITION p202603 VALUES LESS THAN (UNIX_TIMESTAMP('2026-04-01')),
    PARTITION p202604 VALUES LESS THAN (UNIX_TIMESTAMP('2026-05-01')),
    PARTITION pmax VALUES LESS THAN MAXVALUE
);
```

## Integration with Security Systems

### SIEM Integration
- **Format**: JSON export for SIEM systems
- **Real-time**: Event streaming for security monitoring
- **Alerting**: High-risk event notifications

### Example Export Format
```json
{
  "timestamp": "2026-03-17T20:00:00Z",
  "event_type": "failed_login",
  "actor_id": 12345,
  "ip_address": "192.168.1.100",
  "user_agent": "Mozilla/5.0...",
  "risk_score": 75.5,
  "location": {
    "country": "US",
    "city": "New York"
  },
  "details": {
    "failure_reason": "invalid_password",
    "attempts": 3
  }
}
```

## Troubleshooting

### Common Issues
1. **Missing Events**: Check audit logging is enabled in auth service
2. **High Disk Usage**: Implement cleanup policies and partitioning
3. **Slow Queries**: Ensure proper indexing on time-based queries
4. **Missing IP Data**: Verify GeoIP database is configured

### Debug Queries
```sql
-- Check recent failed logins
SELECT COUNT(*) as failure_count, ip_address, failure_reason 
FROM lupo_auth_audit_log 
WHERE event_type = 'failed_login' 
  AND created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 HOUR))
GROUP BY ip_address, failure_reason;

-- Check provider usage statistics
SELECT provider_key, COUNT(*) as usage_count,
       SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successes,
       SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failures
FROM lupo_auth_audit_log 
WHERE created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
GROUP BY provider_key;

-- High-risk events in last 24 hours
SELECT event_type, actor_id, ip_address, risk_score, 
       FROM_UNIXTIME(created_ymdhis) as event_time
FROM lupo_auth_audit_log 
WHERE risk_score >= 70.0 
  AND created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 DAY))
ORDER BY risk_score DESC;
```

## Compliance and Legal

### Data Protection
- **PII Handling**: Personal data logged according to privacy policy
- **Data Minimization**: Only collect necessary authentication data
- **Consent**: User consent for data collection and processing

### Regulatory Compliance
- **GDPR**: Right to access and delete authentication logs
- **SOX**: Financial industry audit requirements
- **HIPAA**: Healthcare industry security requirements

---

**Last Updated**: 2026-03-17  
**Namespace**: auth  
**Version**: 4.0.80  
**Maintainer**: HERMES (actor_id 102)  
**Review Status**: Ready for LILITH validation
