---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "documentation"
  system_version: "4.0.80"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/auth/lupo_banned_actors.md"
  web_path: "http://www.lupopedia.com/lupo-docs/database/lupopedia/tables/active/auth/lupo_banned_actors"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  delegation_chain: "hermes:wolfie"
  artifact_type: "documentation"
  artifact_kind: "table_documentation"
  purpose: "Documentation for lupo_banned_actors table - banned actor management and security"
  tags: ["table_documentation", "auth", "4.0.80", "top_50"]
---

# lupo_banned_actors.md

## Table Overview

The `lupo_banned_actors` table maintains a comprehensive list of banned actors (users, bots, or automated systems) who are prohibited from accessing the Lupopedia system. This table serves as a central security mechanism for enforcing access restrictions and protecting the platform from malicious actors.

**Namespace**: `auth`  
**Table Type**: Security / Access Control  
**Criticality**: HIGH - Core security enforcement

## Where This Table Is Used

- **Authentication System**: Primary check during login attempts
- **Security Enforcement**: Automatic blocking of banned actors
- **Admin Interface**: Banned actor management and monitoring
- **API Gateways**: Request validation and access control
- **Security Monitoring**: Tracking ban events and patterns
- **Compliance Reporting**: Security incident documentation

## Columns

| Column | Type | Description | Notes |
|--------|------|-------------|-------|
| `ban_id` | bigint NOT NULL | Primary key, auto-increment | Unique identifier for each ban record |
| `actor_id` | bigint | Banned actor's ID | Links to lupo_actors table, NULL for IP-only bans |
| `ban_type` | varchar(32) NOT NULL | Type of ban | permanent, temporary, ip_based, pattern_based |
| `ban_reason` | varchar(255) NOT NULL | Reason for the ban | Spam, abuse, security_violation, etc. |
| `banned_by_actor_id` | bigint NOT NULL | Actor who issued the ban | Links to lupo_actors table |
| `created_ymdhis` | bigint NOT NULL DEFAULT 0 | Ban creation timestamp | Unix timestamp format |
| `expires_ymdhis` | bigint | Ban expiration timestamp | NULL for permanent bans |
| `is_active` | tinyint NOT NULL DEFAULT 1 | Ban active status | 1 = active, 0 = inactive |
| `ip_address` | varchar(45) | Banned IP address | Supports IPv4 and IPv6, NULL for actor-only bans |
| `ip_range_start` | varchar(45) | IP range start (CIDR) | For IP range bans |
| `ip_range_end` | varchar(45) | IP range end (CIDR) | For IP range bans |
| `email_pattern` | varchar(255) | Email pattern for bans | Regex pattern for email-based bans |
| `user_agent_pattern` | varchar(500) | User agent pattern | Regex pattern for UA-based bans |
| `notes` | text | Additional ban notes | Admin notes and context |
| `ban_count` | int NOT NULL DEFAULT 1 | Number of times this actor has been banned | Incremented on repeat offenses |
| `last_violation_ymdhis` | bigint | Timestamp of last violation | For tracking repeat offenses |
| `auto_ban` | tinyint NOT NULL DEFAULT 0 | Automatic system ban | 1 = auto-generated, 0 = manual |
| `detection_method` | varchar(64) | Method used to detect violation | manual, automated, pattern_match, etc. |
| `appeal_status` | varchar(32) NOT NULL DEFAULT 'none' | Ban appeal status | none, pending, approved, rejected |
| `appeal_notes` | text | Notes about ban appeal | Appeal context and decisions |

## Indexes

| Index Name | Columns | Type | Purpose |
|------------|--------|------|---------|
| `PRIMARY` | `ban_id` | PRIMARY KEY | Unique row identification |
| `idx_actor_id` | `actor_id` | INDEX | Quick actor ban lookup |
| `idx_ip_address` | `ip_address` | INDEX | IP-based ban checking |
| `idx_ban_type` | `ban_type` | INDEX | Filter by ban type |
| `idx_active` | `is_active` | INDEX | Active ban filtering |
| `idx_expires` | `expires_ymdhis` | INDEX | Expired ban cleanup |
| `idx_created` | `created_ymdhis` | INDEX | Time-based queries |
| `idx_composite_active_actor` | `is_active`, `actor_id` | INDEX | Active actor ban lookup |
| `idx_composite_ip_active` | `ip_address`, `is_active` | INDEX | Active IP ban lookup |

## Relationships

### Foreign Key Relationships (Logical)
- **actor_id** → `lupo_actors.actor_id` (Banned actor)
- **banned_by_actor_id** → `lupo_actors.actor_id` (Admin who issued ban)

### Referenced By
- **Authentication Service**: Ban checking during login
- **API Middleware**: Request validation
- **Security Dashboard**: Ban management interface

## Ban Types

| Ban Type | Description | Duration | Use Cases |
|----------|-------------|----------|-----------|
| `permanent` | Permanent ban with no expiration | Forever | Serious violations, legal issues |
| `temporary` | Time-limited ban | Configurable | Minor violations, cooling periods |
| `ip_based` | IP address or range ban | Configurable | IP-based attacks, geographic restrictions |
| `pattern_based` | Pattern-based ban (email, UA) | Configurable | Bot detection, automated attacks |
| `auto_ban` | System-generated automatic ban | Configurable | Rate limiting, suspicious activity |

## Ban Reasons

| Reason | Description | Typical Duration |
|--------|-------------|------------------|
| `spam` | Spam or unsolicited content | 30 days |
| `abuse` | Abusive behavior or harassment | 90 days |
| `security_violation` | Security policy violation | Permanent |
| `terms_violation` | Terms of service violation | 30 days |
| `bot_activity` | Automated bot activity | Permanent |
| `rate_limiting` | Excessive request rate | 1 hour |
| `suspicious_activity` | Suspicious behavior patterns | 7 days |
| `legal_request` | Legal requirement to ban | Permanent |
| `admin_decision` | Administrative decision | Variable |

## Usage in Code

### PHP Service Example
```php
class BannedActorService {
    public function isActorBanned(int $actorId): bool {
        return $this->db->fetchRow(
            "SELECT 1 FROM lupo_banned_actors 
             WHERE actor_id = ? AND is_active = 1 
               AND (expires_ymdhis IS NULL OR expires_ymdhis > ?)",
            [$actorId, time()]
        ) !== false;
    }
    
    public function isIpBanned(string $ipAddress): bool {
        return $this->db->fetchRow(
            "SELECT 1 FROM lupo_banned_actors 
             WHERE ip_address = ? AND is_active = 1 
               AND (expires_ymdhis IS NULL OR expires_ymdhis > ?)",
            [$ipAddress, time()]
        ) !== false;
    }
    
    public function createBan(array $banData): int {
        $data = [
            'actor_id' => $banData['actor_id'] ?? null,
            'ban_type' => $banData['ban_type'],
            'ban_reason' => $banData['ban_reason'],
            'banned_by_actor_id' => $banData['banned_by_actor_id'],
            'created_ymdhis' => time(),
            'expires_ymdhis' => $banData['expires_ymdhis'] ?? null,
            'is_active' => 1,
            'ip_address' => $banData['ip_address'] ?? null,
            'notes' => $banData['notes'] ?? null,
            'auto_ban' => $banData['auto_ban'] ?? 0,
            'detection_method' => $banData['detection_method'] ?? 'manual'
        ];
        
        // Update ban count if actor already has bans
        if ($data['actor_id']) {
            $existingBan = $this->db->fetchRow(
                "SELECT ban_count FROM lupo_banned_actors 
                 WHERE actor_id = ? ORDER BY ban_id DESC LIMIT 1",
                [$data['actor_id']]
            );
            if ($existingBan) {
                $data['ban_count'] = $existingBan['ban_count'] + 1;
            }
        }
        
        return $this->db->insert('lupo_banned_actors', $data);
    }
    
    public function getActiveBans(): array {
        return $this->db->fetchAll(
            "SELECT ba.*, a.actor_name, a.actor_slug, ba_banned.actor_name as banned_by_name
             FROM lupo_banned_actors ba
             LEFT JOIN lupo_actors a ON ba.actor_id = a.actor_id
             LEFT JOIN lupo_actors ba_banned ON ba.banned_by_actor_id = ba_banned.actor_id
             WHERE ba.is_active = 1 
             ORDER BY ba.created_ymdhis DESC"
        );
    }
}
```

### Ban Checking Middleware
```php
class BanCheckMiddleware {
    public function handle($request, $next) {
        $ipAddress = $request->getClientIp();
        $actorId = $this->getCurrentActorId();
        
        // Check IP ban
        if ($this->bannedActorService->isIpBanned($ipAddress)) {
            return $this->createErrorResponse('IP address is banned', 403);
        }
        
        // Check actor ban
        if ($actorId && $this->bannedActorService->isActorBanned($actorId)) {
            return $this->createErrorResponse('Actor is banned', 403);
        }
        
        return $next($request);
    }
}
```

## Automatic Ban System

### Rate Limiting
```php
class AutoBanService {
    public function checkRateLimit(string $ipAddress, int $maxRequests = 100, int $window = 3600): bool {
        $requestCount = $this->db->fetchOne(
            "SELECT COUNT(*) FROM lupo_auth_audit_log 
             WHERE ip_address = ? AND created_ymdhis > ?",
            [$ipAddress, time() - $window]
        );
        
        if ($requestCount > $maxRequests) {
            $this->createAutoBan([
                'ip_address' => $ipAddress,
                'ban_type' => 'temporary',
                'ban_reason' => 'rate_limiting',
                'expires_ymdhis' => time() + 3600, // 1 hour
                'auto_ban' => 1,
                'detection_method' => 'rate_limiting'
            ]);
            return false;
        }
        
        return true;
    }
}
```

### Pattern Detection
```php
class PatternDetectionService {
    public function detectSuspiciousPatterns(array $auditEvents): array {
        $patterns = [];
        
        // Multiple failed logins from same IP
        $failedLogins = array_filter($auditEvents, fn($e) => $e['event_type'] === 'failed_login');
        $ipGroups = array_group_by($failedLogins, 'ip_address');
        
        foreach ($ipGroups as $ip => $events) {
            if (count($events) >= 5) { // 5+ failed attempts
                $patterns[] = [
                    'type' => 'brute_force',
                    'ip_address' => $ip,
                    'count' => count($events),
                    'timeframe' => max(array_column($events, 'created_ymdhis')) - min(array_column($events, 'created_ymdhis'))
                ];
            }
        }
        
        return $patterns;
    }
}
```

## Ban Appeal Process

### Appeal Workflow
1. **User submits appeal** through support interface
2. **Admin reviews** ban details and context
3. **Decision made** - approve, reject, or modify ban
4. **Update status** in `appeal_status` field
5. **Notify user** of decision

### Appeal Status Values
| Status | Description | Action |
|--------|-------------|--------|
| `none` | No appeal submitted | - |
| `pending` | Appeal submitted, awaiting review | Admin review required |
| `approved` | Appeal approved, ban lifted | Set `is_active = 0` |
| `rejected` | Appeal rejected, ban maintained | No action needed |

## Data Retention and Cleanup

### Retention Policy
- **Active Bans**: Keep indefinitely while active
- **Expired Bans**: Keep for 90 days after expiration
- **Auto-bans**: Keep for 30 days after expiration
- **Permanent Bans**: Keep indefinitely for legal compliance

### Cleanup Strategy
```sql
-- Delete expired temporary bans older than 90 days
DELETE FROM lupo_banned_actors 
WHERE ban_type = 'temporary' 
  AND expires_ymdhis < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 90 DAY))
  AND is_active = 0;

-- Delete expired auto-bans older than 30 days
DELETE FROM lupo_banned_actors 
WHERE auto_ban = 1 
  AND expires_ymdhis < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY))
  AND is_active = 0;
```

## Performance Considerations

### Indexing Strategy
- **Active Ban Lookup**: Composite index on `is_active, actor_id` and `is_active, ip_address`
- **Time-based Queries**: Index on `created_ymdhis` for cleanup operations
- **Ban Type Filtering**: Index on `ban_type` for admin interfaces

### Caching
- **Active Bans**: Cache active bans for 5 minutes
- **IP Bans**: Cache IP ban list for 10 minutes
- **Ban Patterns**: Cache regex patterns for pattern-based bans

## Security Considerations

### Ban Evasion Prevention
- **IP Range Bans**: Block entire subnets for persistent violators
- **Pattern Matching**: Block based on email patterns and user agents
- **Cross-Reference**: Check multiple ban types simultaneously

### Ban Security
- **Admin Validation**: Only authorized actors can issue bans
- **Audit Trail**: All ban actions logged in audit table
- **Appeal Process**: Formal process for ban review and removal

## Integration with Security Systems

### External Threat Intelligence
```php
class ThreatIntelligenceService {
    public function updateBannedIPs(array $threatIPs): void {
        foreach ($threatIPs as $ip) {
            if (!$this->isIpBanned($ip)) {
                $this->createAutoBan([
                    'ip_address' => $ip,
                    'ban_type' => 'ip_based',
                    'ban_reason' => 'threat_intelligence',
                    'auto_ban' => 1,
                    'detection_method' => 'threat_intelligence_feed'
                ]);
            }
        }
    }
}
```

## Troubleshooting

### Common Issues
1. **False Positives**: Review ban patterns and adjust detection thresholds
2. **Performance Issues**: Optimize queries and implement caching
3. **Missing Bans**: Check ban service integration and middleware
4. **Ban Evasion**: Implement additional detection methods

### Debug Queries
```sql
-- Check active bans by type
SELECT ban_type, COUNT(*) as count 
FROM lupo_banned_actors 
WHERE is_active = 1 
GROUP BY ban_type;

-- Check recent auto-bans
SELECT ip_address, ban_reason, created_ymdhis 
FROM lupo_banned_actors 
WHERE auto_ban = 1 
  AND created_ymdhis > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
ORDER BY created_ymdhis DESC;

-- Check repeat offenders
SELECT actor_id, COUNT(*) as ban_count, MAX(created_ymdhis) as last_ban
FROM lupo_banned_actors 
WHERE actor_id IS NOT NULL 
GROUP BY actor_id 
HAVING ban_count > 1
ORDER BY ban_count DESC;
```

## Compliance and Legal

### Legal Requirements
- **Due Process**: Appeal process for banned actors
- **Documentation**: Clear ban reasons and evidence
- **Data Retention**: Maintain records for legal compliance
- **Right to Appeal**: Formal process for ban review

### Privacy Considerations
- **Data Minimization**: Only collect necessary ban data
- **Access Control**: Restricted access to ban information
- **Audit Trail**: Complete log of ban actions and changes

---

**Last Updated**: 2026-03-17  
**Namespace**: auth  
**Version**: 4.0.80  
**Maintainer**: HERMES (actor_id 102)  
**Review Status**: Ready for LILITH validation
