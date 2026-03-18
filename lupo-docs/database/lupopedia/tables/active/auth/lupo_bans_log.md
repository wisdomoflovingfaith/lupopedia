---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "documentation"
  system_version: "4.0.80"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/auth/lupo_bans_log.md"
  web_path: "http://www.lupopedia.com/lupo-docs/database/lupopedia/tables/active/auth/lupo_bans_log"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  delegation_chain: "hermes:wolfie"
  artifact_type: "documentation"
  artifact_kind: "table_documentation"
  purpose: "Documentation for lupo_bans_log table - ban event logging and history"
  tags: ["table_documentation", "auth", "4.0.80", "top_50"]
---

# lupo_bans_log.md

## Table Overview

The `lupo_bans_log` table maintains a comprehensive audit trail of all ban-related events in the Lupopedia system. It records the creation, modification, and removal of bans, providing complete traceability for security monitoring, compliance requirements, and administrative oversight.

**Namespace**: `auth`  
**Table Type**: Audit / Security  
**Criticality**: HIGH - Security compliance and audit trail

## Where This Table Is Used

- **Security Auditing**: Complete history of ban actions and decisions
- **Compliance Reporting**: Legal and regulatory audit requirements
- **Administrative Review**: Ban pattern analysis and effectiveness assessment
- **Security Analytics**: Trend detection and ban system optimization
- **Appeal Processing**: Historical context for ban appeal decisions
- **Incident Response**: Investigation of security events and patterns

## Columns

| Column | Type | Description | Notes |
|--------|------|-------------|-------|
| `log_id` | bigint NOT NULL | Primary key, auto-increment | Unique identifier for each log entry |
| `ban_id` | bigint NOT NULL | Related ban record ID | Links to lupo_banned_actors.ban_id |
| `log_type` | varchar(32) NOT NULL | Type of log entry | created, modified, lifted, expired, appealed |
| `actor_id` | bigint | Actor who performed the action | Links to lupo_actors table |
| `target_actor_id` | bigint | Target actor (if applicable) | Links to lupo_actors table |
| `previous_status` | varchar(32) | Previous ban status | Before this action |
| `new_status` | varchar(32) | New ban status | After this action |
| `reason` | varchar(255) | Reason for the action | Administrative or system-generated |
| `details_json` | text | Additional action details | JSON structure with context |
| `ip_address` | varchar(45) | IP address if relevant | For IP-based actions |
| `user_agent` | varchar(500) | User agent string | For web-based actions |
| `created_ymdhis` | bigint NOT NULL DEFAULT 0 | Log entry timestamp | Unix timestamp format |
| `session_id` | varchar(128) | Session identifier | For tracking user sessions |
| `request_id` | varchar(64) | Unique request identifier | For tracing related actions |
| `automated` | tinyint NOT NULL DEFAULT 0 | Automated system action | 1 = automated, 0 = manual |
| `detection_method` | varchar(64) | Method of detection | manual, automated, pattern_match |
| `risk_level` | varchar(16) | Risk level of the action | low, medium, high, critical |
| `review_status` | varchar(32) NOT NULL DEFAULT 'none' | Review status | none, pending, reviewed, escalated |
| `reviewed_by_actor_id` | bigint | Actor who reviewed the action | Links to lupo_actors table |
| `review_notes` | text | Review notes and decisions | Administrative review context |

## Indexes

| Index Name | Columns | Type | Purpose |
|------------|--------|------|---------|
| `PRIMARY` | `log_id` | PRIMARY KEY | Unique row identification |
| `idx_ban_id` | `ban_id` | INDEX | Filter by specific ban |
| `idx_log_type` | `log_type` | INDEX | Filter by log type |
| `idx_actor_id` | `actor_id` | INDEX | Filter by actor who performed action |
| `idx_target_actor` | `target_actor_id` | INDEX | Filter by target actor |
| `idx_created` | `created_ymdhis` | INDEX | Time-based queries |
| `idx_automated` | `automated` | INDEX | Separate automated vs manual actions |
| `idx_risk_level` | `risk_level` | INDEX | Filter by risk level |
| `idx_composite_ban_time` | `ban_id`, `created_ymdhis` | INDEX | Ban history timeline |
| `idx_composite_type_time` | `log_type`, `created_ymdhis` | INDEX | Common query pattern |

## Relationships

### Foreign Key Relationships (Logical)
- **ban_id** → `lupo_banned_actors.ban_id` (Related ban record)
- **actor_id** → `lupo_actors.actor_id` (Actor who performed action)
- **target_actor_id** → `lupo_actors.actor_id` (Target of the action)
- **reviewed_by_actor_id** → `lupo_actors.actor_id` (Reviewing actor)

### Referenced By
- **Security Reports**: Ban history and trend analysis
- **Compliance Audits**: Security action documentation
- **Administrative Interfaces**: Ban management and review

## Log Types

| Log Type | Description | Trigger |
|----------|-------------|---------|
| `created` | New ban created | Manual or automatic ban creation |
| `modified` | Existing ban modified | Ban duration, reason, or status change |
| `lifted` | Ban manually lifted | Administrative decision |
| `expired` | Ban automatically expired | Time-based expiration |
| `appealed` | Ban appeal submitted | User appeal process |
| `appeal_approved` | Appeal approved | Ban lifted due to appeal |
| `appeal_rejected` | Appeal rejected | Ban maintained after appeal |
| `escalated` | Action escalated for review | High-risk or complex cases |
| `reviewed` | Action reviewed by admin | Quality assurance review |

## Risk Levels

| Risk Level | Description | Typical Actions |
|------------|-------------|----------------|
| `low` | Minor violations, routine actions | Temporary bans, warnings |
| `medium` | Moderate violations, repeat offenses | Extended bans, monitoring |
| `high` | Serious violations, security threats | Permanent bans, investigation |
| `critical` | Critical threats, legal issues | Immediate action, escalation |

## Usage in Code

### PHP Service Example
```php
class BansLogService {
    public function logBanAction(array $logData): int {
        $data = [
            'ban_id' => $logData['ban_id'],
            'log_type' => $logData['log_type'],
            'actor_id' => $logData['actor_id'] ?? null,
            'target_actor_id' => $logData['target_actor_id'] ?? null,
            'previous_status' => $logData['previous_status'] ?? null,
            'new_status' => $logData['new_status'] ?? null,
            'reason' => $logData['reason'],
            'details_json' => json_encode($logData['details'] ?? []),
            'ip_address' => $logData['ip_address'] ?? null,
            'user_agent' => $logData['user_agent'] ?? null,
            'created_ymdhis' => time(),
            'session_id' => $logData['session_id'] ?? null,
            'request_id' => $logData['request_id'] ?? uniqid(),
            'automated' => $logData['automated'] ?? 0,
            'detection_method' => $logData['detection_method'] ?? 'manual',
            'risk_level' => $logData['risk_level'] ?? 'medium',
            'review_status' => $logData['review_status'] ?? 'none'
        ];
        
        return $this->db->insert('lupo_bans_log', $data);
    }
    
    public function getBanHistory(int $banId): array {
        return $this->db->fetchAll(
            "SELECT bl.*, a.actor_name as actor_name, target.actor_name as target_name
             FROM lupo_bans_log bl
             LEFT JOIN lupo_actors a ON bl.actor_id = a.actor_id
             LEFT JOIN lupo_actors target ON bl.target_actor_id = target.actor_id
             WHERE bl.ban_id = ?
             ORDER BY bl.created_ymdhis DESC",
            [$banId]
        );
    }
    
    public function getRecentBanActions(int $hours = 24): array {
        return $this->db->fetchAll(
            "SELECT bl.*, a.actor_name, ba.ban_reason
             FROM lupo_bans_log bl
             LEFT JOIN lupo_actors a ON bl.actor_id = a.actor_id
             LEFT JOIN lupo_banned_actors ba ON bl.ban_id = ba.ban_id
             WHERE bl.created_ymdhis > ?
             ORDER BY bl.created_ymdhis DESC",
            [time() - ($hours * 3600)]
        );
    }
    
    public function getBanStatistics(string $timeframe = '7d'): array {
        $interval = $this->getTimeframeInterval($timeframe);
        
        return [
            'total_bans' => $this->db->fetchOne(
                "SELECT COUNT(DISTINCT ban_id) FROM lupo_bans_log 
                 WHERE log_type = 'created' AND created_ymdhis > ?",
                [time() - $interval]
            ),
            'automated_bans' => $this->db->fetchOne(
                "SELECT COUNT(*) FROM lupo_bans_log 
                 WHERE automated = 1 AND created_ymdhis > ?",
                [time() - $interval]
            ),
            'appeals_submitted' => $this->db->fetchOne(
                "SELECT COUNT(*) FROM lupo_bans_log 
                 WHERE log_type = 'appealed' AND created_ymdhis > ?",
                [time() - $interval]
            ),
            'high_risk_actions' => $this->db->fetchOne(
                "SELECT COUNT(*) FROM lupo_bans_log 
                 WHERE risk_level = 'high' AND created_ymdhis > ?",
                [time() - $interval]
            )
        ];
    }
}
```

### Ban Creation with Logging
```php
class BannedActorService {
    public function createBanWithLogging(array $banData, int $adminActorId): int {
        // Create the ban
        $banId = $this->createBan($banData);
        
        // Log the creation
        $this->bansLogService->logBanAction([
            'ban_id' => $banId,
            'log_type' => 'created',
            'actor_id' => $adminActorId,
            'target_actor_id' => $banData['actor_id'] ?? null,
            'new_status' => 'active',
            'reason' => $banData['ban_reason'],
            'details' => [
                'ban_type' => $banData['ban_type'],
                'expires_ymdhis' => $banData['expires_ymdhis'] ?? null,
                'ip_address' => $banData['ip_address'] ?? null
            ],
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'automated' => $banData['auto_ban'] ?? 0,
            'detection_method' => $banData['detection_method'] ?? 'manual',
            'risk_level' => $this->calculateRiskLevel($banData)
        ]);
        
        return $banId;
    }
    
    public function liftBanWithLogging(int $banId, int $adminActorId, string $reason): bool {
        // Update ban status
        $this->db->update('lupo_banned_actors', 
            ['is_active' => 0], 
            ['ban_id' => $banId]
        );
        
        // Log the lift action
        $this->bansLogService->logBanAction([
            'ban_id' => $banId,
            'log_type' => 'lifted',
            'actor_id' => $adminActorId,
            'previous_status' => 'active',
            'new_status' => 'inactive',
            'reason' => $reason,
            'details' => [
                'action' => 'manual_lift',
                'timestamp' => time()
            ],
            'risk_level' => 'low'
        ]);
        
        return true;
    }
}
```

## Automated Logging

### Automatic Ban Logging
```php
class AutoBanLogger {
    public function logAutoBan(int $banId, array $detectionData): void {
        $this->bansLogService->logBanAction([
            'ban_id' => $banId,
            'log_type' => 'created',
            'actor_id' => null, // System action
            'new_status' => 'active',
            'reason' => $detectionData['reason'],
            'details' => [
                'detection_method' => $detectionData['method'],
                'confidence_score' => $detectionData['confidence'],
                'trigger_events' => $detectionData['events'],
                'pattern_matched' => $detectionData['pattern']
            ],
            'automated' => 1,
            'detection_method' => $detectionData['method'],
            'risk_level' => $this->assessRiskLevel($detectionData),
            'review_status' => 'pending' // Auto-bans require review
        ]);
    }
    
    public function logBanExpiration(int $banId): void {
        $this->bansLogService->logBanAction([
            'ban_id' => $banId,
            'log_type' => 'expired',
            'actor_id' => null, // System action
            'previous_status' => 'active',
            'new_status' => 'expired',
            'reason' => 'Automatic expiration',
            'details' => [
                'expiration_timestamp' => time()
            ],
            'automated' => 1,
            'detection_method' => 'auto_expiration',
            'risk_level' => 'low'
        ]);
    }
}
```

## Appeal Process Logging

### Appeal Workflow
```php
class BanAppealService {
    public function submitAppeal(int $banId, int $actorId, string $appealReason): void {
        // Log appeal submission
        $this->bansLogService->logBanAction([
            'ban_id' => $banId,
            'log_type' => 'appealed',
            'actor_id' => $actorId,
            'target_actor_id' => $actorId,
            'reason' => 'Appeal submitted',
            'details' => [
                'appeal_reason' => $appealReason,
                'submission_timestamp' => time()
            ],
            'risk_level' => 'medium',
            'review_status' => 'pending'
        ]);
    }
    
    public function processAppeal(int $banId, int $reviewerActorId, bool $approved, string $decision): void {
        $logType = $approved ? 'appeal_approved' : 'appeal_rejected';
        
        // Log appeal decision
        $this->bansLogService->logBanAction([
            'ban_id' => $banId,
            'log_type' => $logType,
            'actor_id' => $reviewerActorId,
            'reason' => 'Appeal decision',
            'details' => [
                'decision' => $decision,
                'approved' => $approved,
                'review_timestamp' => time()
            ],
            'reviewed_by_actor_id' => $reviewerActorId,
            'review_notes' => $decision,
            'review_status' => 'reviewed'
        ]);
    }
}
```

## Data Retention and Cleanup

### Retention Policy
- **Active Ban Logs**: Keep for 3 years while ban is active
- **Expired Ban Logs**: Keep for 1 year after ban expiration
- **Low Risk Logs**: Keep for 6 months
- **High Risk Logs**: Keep for 5 years for compliance

### Cleanup Strategy
```sql
-- Delete low-risk logs older than 6 months
DELETE FROM lupo_bans_log 
WHERE risk_level = 'low' 
  AND created_ymdhis < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 6 MONTH));

-- Delete expired ban logs older than 1 year
DELETE FROM lupo_bans_log 
WHERE log_type = 'expired' 
  AND created_ymdhis < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 YEAR));
```

## Performance Considerations

### Indexing Strategy
- **Ban History**: Composite index on `ban_id, created_ymdhis`
- **Time-based Queries**: Index on `created_ymdhis`
- **Log Type Filtering**: Index on `log_type`
- **Risk Level Analysis**: Index on `risk_level`

### Partitioning (Optional)
For high-volume systems, consider partitioning by date:
```sql
PARTITION BY RANGE (UNIX_TIMESTAMP(FROM_UNIXTIME(created_ymdhis))) (
    PARTITION p202603 VALUES LESS THAN (UNIX_TIMESTAMP('2026-04-01')),
    PARTITION p202604 VALUES LESS THAN (UNIX_TIMESTAMP('2026-05-01')),
    PARTITION pmax VALUES LESS THAN MAXVALUE
);
```

## Security and Compliance

### Audit Trail Requirements
- **Complete Logging**: All ban actions must be logged
- **Non-repudiation**: Immutable log records
- **Chain of Custody**: Clear actor attribution
- **Data Integrity**: Tamper-evident logging

### Privacy Considerations
- **Data Minimization**: Only log necessary information
- **Access Control**: Restricted access to sensitive logs
- **Anonymization**: Consider PII anonymization for long-term storage

## Analytics and Reporting

### Ban Effectiveness Metrics
```php
class BanAnalyticsService {
    public function getBanEffectivenessReport(string $timeframe = '30d'): array {
        $interval = $this->getTimeframeInterval($timeframe);
        
        return [
            'ban_recurrence_rate' => $this->calculateRecurrenceRate($interval),
            'appeal_success_rate' => $this->calculateAppealSuccessRate($interval),
            'automated_ban_accuracy' => $this->calculateAutoBanAccuracy($interval),
            'average_ban_duration' => $this->calculateAverageDuration($interval),
            'risk_distribution' => $this->getRiskDistribution($interval)
        ];
    }
    
    private function calculateRecurrenceRate(int $interval): float {
        $totalBanned = $this->db->fetchOne(
            "SELECT COUNT(DISTINCT target_actor_id) FROM lupo_bans_log 
             WHERE log_type = 'created' AND created_ymdhis > ?",
            [$time() - $interval]
        );
        
        $repeatOffenders = $this->db->fetchOne(
            "SELECT COUNT(DISTINCT target_actor_id) FROM lupo_bans_log 
             WHERE log_type = 'created' AND created_ymdhis > ?
             GROUP BY target_actor_id HAVING COUNT(*) > 1",
            [$time() - $interval]
        );
        
        return $totalBanned > 0 ? ($repeatOffenders / $totalBanned) * 100 : 0;
    }
}
```

## Troubleshooting

### Common Issues
1. **Missing Log Entries**: Check ban service integration
2. **Performance Issues**: Optimize queries and implement partitioning
3. **Log Corruption**: Implement data validation and integrity checks
4. **Storage Growth**: Implement proper retention policies

### Debug Queries
```sql
-- Check recent ban activity
SELECT log_type, COUNT(*) as count, risk_level
FROM lupo_bans_log 
WHERE created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
GROUP BY log_type, risk_level
ORDER BY count DESC;

-- Check automated vs manual actions
SELECT automated, COUNT(*) as count
FROM lupo_bans_log 
WHERE created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 7 DAY))
GROUP BY automated;

-- Check high-risk actions requiring review
SELECT ban_id, log_type, reason, created_ymdhis
FROM lupo_bans_log 
WHERE risk_level = 'high' AND review_status = 'pending'
ORDER BY created_ymdhis ASC;
```

## Integration with External Systems

### SIEM Integration
```json
{
  "timestamp": "2026-03-17T20:00:00Z",
  "event_type": "ban_created",
  "ban_id": 12345,
  "actor_id": 67890,
  "reason": "security_violation",
  "risk_level": "high",
  "automated": false,
  "detection_method": "manual",
  "details": {
    "ban_type": "permanent",
    "ip_address": "192.168.1.100"
  }
}
```

---

**Last Updated**: 2026-03-17  
**Namespace**: auth  
**Version**: 4.0.80  
**Maintainer**: HERMES (actor_id 102)  
**Review Status**: Ready for LILITH validation
