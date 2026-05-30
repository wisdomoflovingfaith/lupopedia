---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/12_B-i_TOKEN_GOVERNANCE_SECTION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/12_B-i_TOKEN_GOVERNANCE_SECTION.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/prd/canonical/1026/04/token-governance-section.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/prd/token-governance
  artifact_type: prd
  artifact_kind: specification
  channel_key: prd
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_12_B-i
  title: Token Governance v1.0 ??? Actor Token Usage Monitoring and Quota Enforcement
  summary: Comprehensive token governance for Lupopedia actors including usage monitoring, quota enforcement, cost tracking, and budget management. Extends PRD 12's API integration capabilities.
---
# Token Governance v1.0 ??? Actor Token Usage Monitoring and Quota Enforcement

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. Purpose

Establish comprehensive token governance for Lupopedia actors, including usage monitoring, quota enforcement, cost tracking, and budget management. This section extends PRD 12's API integration capabilities to provide fine-grained control over LLM token consumption across all actors in the ecosystem.

## 2. Scope

**In scope:**
- Actor-level token usage tracking and monitoring
- Daily, weekly, and monthly quota enforcement
- Soft threshold warnings and hard-stop enforcement
- Human-only fallback mode when quotas exceeded
- Token usage daemon for real-time monitoring
- File-based fallback when database is offline
- Cost tracking and budget management per actor
- Integration with install wizard for token governance setup
- API key configuration rules for token governance
- Comprehensive logging and audit trails

**Out of scope:**
- Network-level rate limiting (covered by existing rate limits)
- Authentication mechanisms (covered by existing token system)
- External API integrations (covered by webhook system)

## 3. Definitions

| Term | Definition |
|------|------------|
| **Token** | Unit of LLM usage (input tokens + output tokens) |
| **Quota** | Maximum allowed token usage within a time period |
| **Budget** | Monetary limit for token usage costs |
| **Soft Threshold** | Warning level (e.g., 80% of quota) |
| **Hard Stop** | Absolute limit that blocks further usage |
| **Actor Budget** | Token quota assigned to a specific actor |
| **Usage Daemon** | Background process monitoring token consumption |
| **Fallback Mode** | Human-only operation when automated systems blocked |

## 4. Token Usage Tracking Requirements

### 4.1 Database Schema Additions

**Table: `lupo_token_usage`**
```sql
CREATE TABLE {{prefix}}lupo_token_usage (
    usage_id BIGINT NOT NULL PRIMARY KEY,
    actor_id BIGINT NOT NULL,
    gateway_type VARCHAR(32) NOT NULL,
    provider_name VARCHAR(64) NOT NULL,
    model_name VARCHAR(64) NOT NULL,
    input_tokens INT NOT NULL DEFAULT 0,
    output_tokens INT NOT NULL DEFAULT 0,
    total_tokens INT NOT NULL DEFAULT 0,
    cost_usd DECIMAL(10,6) NOT NULL DEFAULT 0.000000,
    request_ymdhis BIGINT NOT NULL,
    response_ymdhis BIGINT NOT NULL,
    request_duration_ms INT NOT NULL DEFAULT 0,
    status VARCHAR(16) NOT NULL DEFAULT 'success',
    error_message TEXT,
    metadata_json TEXT,
    created_ymdhis BIGINT NOT NULL,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT NULL
);
```

**Table: `lupo_actor_token_budgets`**
```sql
CREATE TABLE {{prefix}}lupo_actor_token_budgets (
    budget_id BIGINT NOT NULL PRIMARY KEY,
    actor_id BIGINT NOT NULL,
    budget_type VARCHAR(16) NOT NULL,
    period_start_ymdhis BIGINT NOT NULL,
    period_end_ymdhis BIGINT NOT NULL,
    daily_quota INT NOT NULL DEFAULT 0,
    weekly_quota INT NOT NULL DEFAULT 0,
    monthly_quota INT NOT NULL DEFAULT 0,
    daily_used INT NOT NULL DEFAULT 0,
    weekly_used INT NOT NULL DEFAULT 0,
    monthly_used INT NOT NULL DEFAULT 0,
    soft_threshold_percent INT NOT NULL DEFAULT 80,
    cost_budget_usd DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    cost_used_usd DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    is_active TINYINT NOT NULL DEFAULT 1,
    is_human_only_fallback TINYINT NOT NULL DEFAULT 0,
    created_ymdhis BIGINT NOT NULL,
    updated_ymdhis BIGINT NOT NULL,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT NULL
);
```

### 4.2 Tracking Requirements

1. **Every API call must log:**
   - Actor ID making the request
   - Token counts (input/output/total)
   - Cost in USD
   - Provider and model used
   - Request/response timestamps
   - Duration in milliseconds

2. **Real-time aggregation:**
   - Update actor budgets immediately after each request
   - Calculate running totals for daily/weekly/monthly periods
   - Track cost against monetary budgets

3. **Metadata capture:**
   - Request context and purpose
   - Response quality metrics
   - Error conditions and retries

## 5. Daily/Weekly/Monthly Quota Rules

### 5.1 Quota Calculation

| Period | Reset Time | Calculation Method |
|--------|------------|-------------------|
| Daily | 00:00 UTC actor timezone | SUM of all tokens used since midnight |
| Weekly | Monday 00:00 UTC | SUM of tokens used in current week |
| Monthly | 1st 00:00 UTC | SUM of tokens used in current month |

### 5.2 Quota Enforcement Logic

```php
function checkTokenQuota($actor_id, $tokens_requested) {
    $budget = getActorTokenBudget($actor_id);
    
    // Check daily quota
    if ($budget['daily_used'] + $tokens_requested > $budget['daily_quota']) {
        return ['allowed' => false, 'reason' => 'daily_quota_exceeded'];
    }
    
    // Check weekly quota
    if ($budget['weekly_used'] + $tokens_requested > $budget['weekly_quota']) {
        return ['allowed' => false, 'reason' => 'weekly_quota_exceeded'];
    }
    
    // Check monthly quota
    if ($budget['monthly_used'] + $tokens_requested > $budget['monthly_quota']) {
        return ['allowed' => false, 'reason' => 'monthly_quota_exceeded'];
    }
    
    return ['allowed' => true];
}
```

### 5.3 Default Quotas

| Actor Type | Daily | Weekly | Monthly |
|------------|-------|--------|---------|
| System Actors | 100,000 | 500,000 | 2,000,000 |
| IDE Agents | 50,000 | 250,000 | 1,000,000 |
| Specialized Agents | 25,000 | 100,000 | 400,000 |
| Human Actors | 10,000 | 50,000 | 200,000 |

## 6. Soft Threshold Warnings

### 6.1 Warning Levels

| Threshold | Action | Notification Method |
|-----------|--------|-------------------|
| 50% | Log warning | System log |
| 70% | Email notification | Email to actor owner |
| 80% | In-app notification | Dashboard alert |
| 90% | SMS notification (critical actors) | SMS to admin |
| 95% | Prepare for hard stop | System preparation |

### 6.2 Warning Implementation

```php
function checkSoftThresholds($actor_id) {
    $budget = getActorTokenBudget($actor_id);
    
    $daily_percent = ($budget['daily_used'] / $budget['daily_quota']) * 100;
    $weekly_percent = ($budget['weekly_used'] / $budget['weekly_quota']) * 100;
    $monthly_percent = ($budget['monthly_used'] / $budget['monthly_quota']) * 100;
    
    foreach ([50, 70, 80, 90, 95] as $threshold) {
        if ($daily_percent >= $threshold && !hasWarningBeenSent($actor_id, 'daily', $threshold)) {
            sendQuotaWarning($actor_id, 'daily', $daily_percent, $threshold);
            markWarningSent($actor_id, 'daily', $threshold);
        }
        // Repeat for weekly and monthly
    }
}
```

## 7. Hard-Stop Enforcement

### 7.1 Hard-Stop Triggers

- Quota exceeded for any period (daily/weekly/monthly)
- Cost budget exceeded
- Manual admin intervention
- System emergency mode

### 7.2 Enforcement Actions

1. **Immediate blocking** of further API requests
2. **Return specific error codes:**
   - `TOKEN_QUOTA_DAILY_EXCEEDED`
   - `TOKEN_QUOTA_WEEKLY_EXCEEDED`
   - `TOKEN_QUOTA_MONTHLY_EXCEEDED`
   - `TOKEN_COST_BUDGET_EXCEEDED`
3. **Log enforcement event** with full context
4. **Notify administrators** immediately

### 7.3 Hard-Stop Implementation

```php
function enforceHardStop($actor_id, $reason) {
    // Block actor from making further requests
    blockActorAPIAccess($actor_id, $reason);
    
    // Log the enforcement
    logHardStopEvent($actor_id, $reason);
    
    // Notify administrators
    notifyAdministrators($actor_id, $reason);
    
    // Set human-only fallback if configured
    if (shouldEnableHumanOnlyFallback($actor_id)) {
        enableHumanOnlyFallback($actor_id);
    }
    
    // Return error response
    return [
        'error' => true,
        'code' => $reason,
        'message' => getHardStopMessage($reason),
        'reset_time' => getNextQuotaResetTime($actor_id, $reason)
    ];
}
```

## 8. Human-Only Fallback Mode

### 8.1 Fallback Triggers

- Hard-stop enforcement activated
- Actor configured for human-only fallback
- System emergency mode
- Administrator manual activation

### 8.2 Fallback Behavior

1. **Disable all automated API calls**
2. **Require human approval** for each request
3. **Limit to essential functions only**
4. **Provide clear messaging** about quota status
5. **Offer upgrade options** if applicable

### 8.3 Fallback Implementation

```php
function enableHumanOnlyFallback($actor_id) {
    // Mark actor as human-only mode
    setActorHumanOnlyMode($actor_id, true);
    
    // Disable automated processes
    disableActorAutomatedProcesses($actor_id);
    
    // Update UI to show human-only status
    updateActorUIStatus($actor_id, 'human_only');
    
    // Log fallback activation
    logHumanOnlyFallback($actor_id, 'activated');
}
```

## 9. Metadata_json Schema Additions

### 9.1 Actor Metadata Extensions

```json
{
  "token_governance": {
    "budget_settings": {
      "daily_quota": 50000,
      "weekly_quota": 250000,
      "monthly_quota": 1000000,
      "cost_budget_usd": 100.00,
      "soft_threshold_percent": 80,
      "human_only_fallback": true
    },
    "usage_tracking": {
      "provider_costs": {
        "openai": {"gpt-4": 0.03, "gpt-3.5-turbo": 0.002},
        "anthropic": {"claude-3": 0.015},
        "google": {"gemini-pro": 0.0025}
      },
      "tracking_enabled": true,
      "detailed_logging": true
    },
    "notifications": {
      "email": "admin@example.com",
      "sms": "+1234567890",
      "webhook_url": "https://example.com/webhook"
    }
  }
}
```

### 9.2 Request Metadata Schema

```json
{
  "token_usage": {
    "request_id": "req_123456789",
    "actor_id": 102,
    "gateway_type": "ide_panel",
    "provider": "openai",
    "model": "gpt-4",
    "tokens": {
      "input": 150,
      "output": 300,
      "total": 450
    },
    "cost": {
      "input_cost": 0.0045,
      "output_cost": 0.009,
      "total_cost": 0.0135,
      "currency": "USD"
    },
    "timing": {
      "request_ymdhis": 20260420074000,
      "response_ymdhis": 20260420074015,
      "duration_ms": 15000
    },
    "quota_status": {
      "daily_used": 450,
      "daily_remaining": 49550,
      "weekly_used": 2100,
      "weekly_remaining": 247900,
      "monthly_used": 8500,
      "monthly_remaining": 991500
    }
  }
}
```

## 10. Token Usage Daemon Requirements

### 10.1 Daemon Responsibilities

1. **Real-time monitoring** of token usage
2. **Aggregation** of usage statistics
3. **Threshold checking** and warning generation
4. **Quota enforcement** when limits reached
5. **Cost calculation** and budget tracking
6. **Report generation** for administrators

### 10.2 Daemon Configuration

```yaml
token_usage_daemon:
  enabled: true
  check_interval_seconds: 60
  batch_size: 1000
  max_processing_time: 30
  
  thresholds:
    warning_levels: [50, 70, 80, 90, 95]
    check_frequency: "*/5 * * * *"  # Every 5 minutes
    
  notifications:
    email_enabled: true
    sms_enabled: true
    webhook_enabled: true
    slack_enabled: false
    
  reporting:
    daily_report: true
    weekly_report: true
    monthly_report: true
    real_time_dashboard: true
```

### 10.3 Daemon Implementation

```php
class TokenUsageDaemon {
    public function run() {
        while (true) {
            $this->processPendingUsage();
            $this->updateBudgets();
            $this->checkThresholds();
            $this->enforceQuotas();
            $this->generateReports();
            
            sleep($this->config['check_interval_seconds']);
        }
    }
    
    private function processPendingUsage() {
        $usage = $this->getPendingTokenUsage();
        
        foreach ($usage as $record) {
            $this->updateActorBudget($record);
            $this->calculateCosts($record);
            $this->markProcessed($record['usage_id']);
        }
    }
}
```

## 11. File-Based Fallback if DB Offline

### 11.1 Fallback File Structure

```
data/fallback/token_usage/
????????? 2026/
???   ????????? 04/
???   ???   ????????? 20_usage.jsonl
???   ???   ????????? 20_budgets.json
???   ???   ????????? 20_enforcements.json
```

### 11.2 Fallback Data Format

```json
{"timestamp": "20260420074000", "actor_id": 102, "tokens": 450, "cost": 0.0135, "provider": "openai"}
{"timestamp": "20260420074100", "actor_id": 15, "tokens": 300, "cost": 0.009, "provider": "anthropic"}
```

### 11.3 Fallback Implementation

```php
function logTokenUsageFallback($usage_data) {
    $date = date('Y/m/d');
    $filename = "data/fallback/token_usage/{$date}_usage.jsonl";
    
    $log_entry = [
        'timestamp' => $usage_data['timestamp'],
        'actor_id' => $usage_data['actor_id'],
        'tokens' => $usage_data['total_tokens'],
        'cost' => $usage_data['cost_usd'],
        'provider' => $usage_data['provider_name'],
        'model' => $usage_data['model_name']
    ];
    
    file_put_contents($filename, json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);
}

function syncFallbackToDatabase() {
    $fallback_files = glob('data/fallback/token_usage/*_usage.jsonl');
    
    foreach ($fallback_files as $file) {
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if ($data && !isUsageInDatabase($data)) {
                insertUsageToDatabase($data);
            }
        }
    }
}
```

## 12. Logging Requirements

### 12.1 Log Categories

| Category | Level | Purpose |
|----------|-------|---------|
| TOKEN_USAGE | INFO | Every token consumption event |
| QUOTA_WARNING | WARN | Soft threshold breaches |
| QUOTA_ENFORCEMENT | ERROR | Hard-stop enforcement actions |
| BUDGET_EXCEEDED | CRITICAL | Cost budget overruns |
| DAEMON_STATUS | DEBUG | Daemon operational status |
| FALLBACK_MODE | WARN | Fallback system activation |

### 12.2 Log Format

```json
{
  "timestamp": "20260420074000",
  "level": "INFO",
  "category": "TOKEN_USAGE",
  "actor_id": 102,
  "request_id": "req_123456789",
  "provider": "openai",
  "model": "gpt-4",
  "tokens": {"input": 150, "output": 300, "total": 450},
  "cost": {"total": 0.0135, "currency": "USD"},
  "quota_status": {"daily_percent": 0.9, "weekly_percent": 0.84, "monthly_percent": 0.85},
  "duration_ms": 15000,
  "metadata": {"gateway_type": "ide_panel", "request_type": "code_generation"}
}
```

### 12.3 Log Retention

- **TOKEN_USAGE**: 90 days
- **QUOTA_WARNING**: 1 year
- **QUOTA_ENFORCEMENT**: 3 years
- **BUDGET_EXCEEDED**: 5 years
- **DAEMON_STATUS**: 30 days
- **FALLBACK_MODE**: 1 year

## 13. Security Considerations

### 13.1 Data Protection

- **Encrypt cost information** in database
- **Hash actor IDs** in log files
- **Secure API keys** for cost calculation
- **Audit trail** for all quota modifications

### 13.2 Access Controls

- **Admin-only access** to budget modifications
- **Actor owners** can view their own usage only
- **System auditors** can access all usage data
- **Rate limiting** on usage API endpoints

### 13.3 Compliance Requirements

- **GDPR compliance** for personal data in usage logs
- **SOX compliance** for financial tracking
- **Data retention policies** enforced automatically
- **Right to be forgotten** for usage data

## 14. Install Wizard Updates

### 14.1 New Installation Steps

**Step 11: Token Governance Configuration**
- Enable/disable token governance
- Set default quotas for actor types
- Configure notification preferences
- Set up cost tracking
- Configure daemon settings

**Step 12: Token Budget Initialization**
- Create initial actor budgets
- Set system-wide quotas
- Configure warning thresholds
- Test enforcement mechanisms

### 14.2 Upgrade Migration

```sql
-- Add token governance tables
ALTER TABLE {{prefix}}lupo_actors 
ADD COLUMN token_governance_enabled TINYINT DEFAULT 1,
ADD COLUMN default_daily_quota INT DEFAULT 50000,
ADD COLUMN default_weekly_quota INT DEFAULT 250000,
ADD COLUMN default_monthly_quota INT DEFAULT 1000000;
```

### 14.3 Configuration Templates

```php
// config/token_governance.php
return [
    'enabled' => true,
    'default_quotas' => [
        'system' => ['daily' => 100000, 'weekly' => 500000, 'monthly' => 2000000],
        'ide' => ['daily' => 50000, 'weekly' => 250000, 'monthly' => 1000000],
        'specialized' => ['daily' => 25000, 'weekly' => 100000, 'monthly' => 400000],
        'human' => ['daily' => 10000, 'weekly' => 50000, 'monthly' => 200000]
    ],
    'daemon' => [
        'enabled' => true,
        'check_interval' => 60,
        'batch_size' => 1000
    ],
    'notifications' => [
        'email' => ['enabled' => true, 'from' => 'noreply@lupopedia.com'],
        'sms' => ['enabled' => false],
        'webhook' => ['enabled' => false]
    ]
];
```

## 15. API Key Configuration Rules

### 15.1 Token Governance API Keys

| Key Type | Permissions | Rate Limit | Cost Tracking |
|----------|-------------|------------|---------------|
| governance_admin | Full access | 1000/hour | Required |
| governance_read | Read-only | 5000/hour | Optional |
| governance_webhook | Webhook only | 10000/hour | Required |

### 15.2 Configuration Validation

```php
function validateTokenGovernanceConfig($config) {
    $errors = [];
    
    // Validate quotas are reasonable
    if ($config['daily_quota'] > 1000000) {
        $errors[] = 'Daily quota exceeds maximum allowed';
    }
    
    // Validate cost budget
    if ($config['cost_budget_usd'] > 10000) {
        $errors[] = 'Cost budget exceeds maximum allowed';
    }
    
    // Validate notification settings
    if ($config['notifications']['email']['enabled'] && !filter_var($config['notifications']['email']['address'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address for notifications';
    }
    
    return $errors;
}
```

### 15.3 API Key Usage Tracking

```php
function trackAPIKeyUsage($key_id, $usage_data) {
    // Log usage against API key
    logAPIKeyTokenUsage($key_id, $usage_data);
    
    // Update key quotas if configured
    updateAPIKeyQuotas($key_id, $usage_data['total_tokens']);
    
    // Check if key has exceeded its own limits
    if (hasAPIKeyExceededQuota($key_id)) {
        disableAPIKey($key_id, 'quota_exceeded');
        notifyAPIKeyOwner($key_id, 'quota_exceeded');
    }
}
```

## 16. Actor-Level Token Budgets

### 16.1 Budget Assignment

```php
function assignActorTokenBudget($actor_id, $budget_config) {
    $budget = [
        'actor_id' => $actor_id,
        'daily_quota' => $budget_config['daily_quota'],
        'weekly_quota' => $budget_config['weekly_quota'],
        'monthly_quota' => $budget_config['monthly_quota'],
        'cost_budget_usd' => $budget_config['cost_budget_usd'],
        'soft_threshold_percent' => $budget_config['soft_threshold_percent'] ?? 80,
        'human_only_fallback' => $budget_config['human_only_fallback'] ?? false
    ];
    
    return insertActorTokenBudget($budget);
}
```

### 16.2 Budget Overrides

| Override Type | Who Can Apply | Approval Required |
|---------------|---------------|-------------------|
| Temporary Increase | Actor Owner | No (up to 2x) |
| Permanent Increase | System Admin | Yes |
| Emergency Override | System Admin | No (with audit) |
| Budget Decrease | Actor Owner | No |
| Cost Budget Change | Finance Admin | Yes |

### 16.3 Budget Monitoring Dashboard

```php
function getActorBudgetDashboard($actor_id) {
    $budget = getActorTokenBudget($actor_id);
    $usage = getActorTokenUsage($actor_id, 'current_period');
    
    return [
        'quotas' => [
            'daily' => [
                'allocated' => $budget['daily_quota'],
                'used' => $usage['daily_used'],
                'remaining' => $budget['daily_quota'] - $usage['daily_used'],
                'percent_used' => ($usage['daily_used'] / $budget['daily_quota']) * 100
            ],
            'weekly' => [...],
            'monthly' => [...]
        ],
        'costs' => [
            'budget_usd' => $budget['cost_budget_usd'],
            'used_usd' => $usage['cost_used_usd'],
            'remaining_usd' => $budget['cost_budget_usd'] - $usage['cost_used_usd']
        ],
        'warnings' => getActiveWarnings($actor_id),
        'enforcements' => getActiveEnforcements($actor_id)
    ];
}
```

## 17. PRD Compliance Tests

### 17.1 Unit Tests

```php
class TokenGovernanceTest extends PHPUnit\Framework\TestCase {
    public function testTokenUsageTracking() {
        // Test that all token usage is properly tracked
    }
    
    public function testQuotaEnforcement() {
        // Test that quotas are enforced correctly
    }
    
    public function testSoftThresholdWarnings() {
        // Test that warnings are sent at correct thresholds
    }
    
    public function testHardStopEnforcement() {
        // Test that hard stops block further usage
    }
    
    public function testHumanOnlyFallback() {
        // Test that fallback mode works correctly
    }
    
    public function testFileBasedFallback() {
        // Test that file-based fallback works when DB is offline
    }
}
```

### 17.2 Integration Tests

```php
class TokenGovernanceIntegrationTest extends PHPUnit\Framework\TestCase {
    public function testEndToEndTokenTracking() {
        // Test complete flow from API call to budget update
    }
    
    public function testDaemonProcessing() {
        // Test that daemon processes usage correctly
    }
    
    public function testInstallWizardIntegration() {
        // Test that install wizard sets up token governance
    }
    
    public function testAPIKeyIntegration() {
        // Test that API keys are properly tracked
    }
}
```

### 17.3 Performance Tests

```php
class TokenGovernancePerformanceTest extends PHPUnit\Framework\TestCase {
    public function testHighVolumeTokenTracking() {
        // Test system can handle 10,000 requests/minute
    }
    
    public function testDaemonPerformance() {
        // Test daemon can process 1M records/hour
    }
    
    public function testFallbackPerformance() {
        // Test fallback system performance under load
    }
}
```

### 17.4 Compliance Validation

```php
function validatePRDCompliance() {
    $checks = [
        'token_usage_tracking' => hasTokenUsageTracking(),
        'quota_enforcement' => hasQuotaEnforcement(),
        'soft_thresholds' => hasSoftThresholdWarnings(),
        'hard_stops' => hasHardStopEnforcement(),
        'human_fallback' => hasHumanOnlyFallback(),
        'daemon' => hasTokenUsageDaemon(),
        'file_fallback' => hasFileBasedFallback(),
        'logging' => hasRequiredLogging(),
        'security' => hasSecurityMeasures(),
        'install_wizard' => hasInstallWizardUpdates()
    ];
    
    return $checks;
}
```

---

**Implementation Priority:**
1. Database schema and basic tracking
2. Quota enforcement logic
3. Warning system
4. Daemon implementation
5. Fallback mechanisms
6. Install wizard integration
7. Comprehensive testing

**Dependencies:**
- PRD 12: API Tokens, Clients, Rate Limiting (base functionality)
- PRD 16: Lupopedia Headers (metadata schema)
- PRD 80: Database Design Doctrine (schema requirements)
