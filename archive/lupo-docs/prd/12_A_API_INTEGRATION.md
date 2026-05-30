---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/12_A_API_INTEGRATION.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/12_A_API_INTEGRATION.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/12_api_integration.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/api-integration
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_12_A
  title: "PRD: API Tokens, Clients, Rate Limiting, Webhooks, and Integration"
  summary: null
---
# PRD: API Tokens, Clients, Rate Limiting, Webhooks, and Integration

## Overview

**Namespace Purpose:** Provides comprehensive API integration capabilities including authentication, rate limiting, webhooks, and external system connectivity. This namespace enables secure and controlled access to Lupopedia functionality.

**Primary Actors:** 
- API clients (via lupo_api_clients)
- Token managers (via lupo_api_tokens)
- Rate limiters (via lupo_api_rate_limits)
- Webhook handlers (via lupo_api_webhooks)
- Notification senders (via lupo_notifications)

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
| `lupo_api_tokens` | API token management and authentication | `token_id` | Central to API auth |
| `lupo_api_clients` | API client registration and management | `client_id` | Client management |
| `lupo_api_rate_limits` | Rate limiting and quota management | `rate_limit_id` | Rate limiting |
| `lupo_api_webhooks` | Webhook definitions and management | `webhook_id` | Webhook system |
| `lupo_api_token_logs` | API token usage logging | `token_log_id` | Token usage tracking |
| `lupo_notifications` | Notification delivery and tracking | `notification_id` | Notification system |

## Table Details

### `lupo_api_tokens`

**Purpose:** Manages API tokens for authentication and authorization.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| token_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| client_id | BIGINT | NO |  | Foreign reference to lupo_api_clients |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| token_hash | VARCHAR(255) | NO |  | Hashed token value |
| token_type | VARCHAR(32) | NO | 'bearer' | Type: bearer, api_key, oauth |
| scope | VARCHAR(255) | YES | NULL | Token scope/permissions |
| expires_ymdhis | BIGINT | YES | NULL | UTC timestamp when token expires |
| last_used_ymdhis | BIGINT | YES | NULL | UTC timestamp of last use |
| usage_count | INT | NO | 0 | Number of times used |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Token active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_api_tokens_client | client_id, is_active, is_deleted | Client's tokens |
| idx_api_tokens_actor | actor_id, is_active, is_deleted | Actor's tokens |
| idx_api_tokens_expires | expires_ymdhis, is_active, is_deleted | Expiration cleanup |

### `lupo_api_clients`

**Purpose:** Registers and manages API client applications.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|------|---------|-------------|
| client_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| client_name | VARCHAR(255) | NO |  | Unique client name |
| client_secret_hash | VARCHAR(255) | NO |  | Hashed client secret |
| redirect_uri | VARCHAR(512) | YES | NULL | OAuth redirect URI |
| grant_types | VARCHAR(255) | YES | NULL | Allowed grant types |
| scope | VARCHAR(255) | YES | NULL | Default client scope |
| created_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Client active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_api_clients_name | client_name, is_deleted | Unique client lookup |
| idx_api_clients_actor | created_by_actor_id, is_active, is_deleted | Actor's clients |
| idx_api_clients_active | is_active, created_ymdhis, is_deleted | Active clients |

### `lupo_api_rate_limits`

**Purpose:** Implements rate limiting and quota management for API access.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| rate_limit_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| client_id | BIGINT | YES | NULL | Foreign reference to lupo_api_clients |
| actor_id | BIGINT | YES | NULL | Foreign reference to lupo_actors |
| limit_type | VARCHAR(32) | NO | 'requests' | Type: requests, bandwidth, operations |
| limit_window | INT | NO | 3600 | Time window in seconds |
| limit_count | INT | NO | 1000 | Max requests in window |
| current_count | INT | NO | 0 | Current count in window |
| window_start_ymdhis | BIGINT | NO | (application) | UTC timestamp window start |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Rate limit active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_rate_limits_client | client_id, limit_type, is_active, is_deleted | Client rate limits |
| idx_rate_limits_actor | actor_id, limit_type, is_active, is_deleted | Actor rate limits |
| idx_rate_limits_window | window_start_ymdhis, is_active, is_deleted | Window-based queries |

### `lupo_api_webhooks`

**Purpose:** Manages webhook endpoints for event notifications.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| webhook_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| client_id | BIGINT | NO |  | Foreign reference to lupo_api_clients |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| webhook_url | VARCHAR(512) | NO |  | Webhook endpoint URL |
| event_types | VARCHAR(255) | NO |  | Comma-separated event types |
| secret_key | VARCHAR(255) | YES | NULL | Webhook secret for validation |
| active_events | VARCHAR(255) | YES | NULL | Currently active event types |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| last_triggered_ymdhis | BIGINT | YES | NULL | UTC timestamp last triggered |
| trigger_count | INT | NO | 0 | Number of times triggered |
| is_active | TINYINT | NO | 1 | Webhook active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_webhooks_client | client_id, is_active, is_deleted | Client's webhooks |
| idx_webhooks_actor | actor_id, is_active, is_deleted | Actor's webhooks |
| idx_webhooks_events | event_types, is_active, is_deleted | Event type queries |

### `lupo_notifications`

**Purpose:** Manages notification delivery and tracking across channels.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| notification_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| notification_type | VARCHAR(32) | NO | 'system' | Type: system, message, task, webhook |
| title | VARCHAR(255) | NO |  | Notification title |
| content | TEXT | NO |  | Notification content |
| target_type | VARCHAR(32) | YES | NULL | Target entity type |
| target_id | BIGINT | YES | NULL | Target entity ID |
| channel | VARCHAR(32) | NO | 'in_app' | Channel: in_app, email, webhook, push |
| status | VARCHAR(32) | NO | 'pending' | Status: pending, sent, delivered, failed |
| priority | VARCHAR(16) | NO | 'normal' | Priority: low, normal, high, urgent |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| sent_ymdhis | BIGINT | YES | NULL | UTC timestamp when sent |
| read_ymdhis | BIGINT | YES | NULL | UTC timestamp when read |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_notifications_actor | actor_id, status, created_ymdhis, is_deleted | Actor's notifications |
| idx_notifications_type | notification_type, status, created_ymdhis, is_deleted | Type-based queries |
| idx_notifications_priority | priority, status, created_ymdhis, is_deleted | Priority-based queries |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 12_api_integration | This ???????? 01_core_identity | API authentication | actor_id references |
| 12_api_integration | This ???????? 08_governance_rules | API governance | Rate limits governed by rules |
| 12_api_integration | This ???????? All namespaces | System integration | API provides access to all features |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Normal API operation | inactive, disabled, deleted (soft) |
| inactive | Temporarily disabled | active, deleted (soft) |
| disabled | Permanently disabled | N/A (requires manual re-enable) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

API tokens are hashed and never stored in plain text

Client secrets are encrypted at rest

Rate limits prevent abuse and ensure fair usage

Webhook deliveries are signed and validated

Soft delete preserves API history for compliance

## Testing Requirements

Unit tests for token generation and validation

Integration tests for rate limiting and webhook delivery

Performance tests for API authentication and authorization

Soft delete behavior verification

## Usage Patterns

```php
// Register API client
$clientService = new ApiClientService();
$clientId = $clientService->registerClient($clientName, $redirectUri, $grantTypes);

// Generate API token
$tokenService = new ApiTokenService();
$tokenId = $tokenService->generateToken($clientId, $actorId, $scope, $expires);

// Check rate limit
$rateLimitService = new ApiRateLimitService();
$allowed = $rateLimitService->checkLimit($clientId, $limitType);

// Create webhook
$webhookService = new ApiWebhookService();
$webhookId = $webhookService->createWebhook($clientId, $actorId, $url, $eventTypes);

// Send notification
$notificationService = new NotificationService();
$notificationId = $notificationService->send($actorId, $type, $title, $content, $channel);
```

---

## Token Governance v1.0 ??? Actor Token Usage Monitoring and Quota Enforcement

### Purpose

Establish comprehensive token governance for Lupopedia actors, including usage monitoring, quota enforcement, cost tracking, and budget management. This section extends PRD 12's API integration capabilities to provide fine-grained control over LLM token consumption across all actors in the ecosystem.

### Scope

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

### Database Schema Additions

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

### Key Features

1. **Token Usage Tracking**: Every API call logs token counts, costs, and metadata
2. **Quota Enforcement**: Daily/weekly/monthly limits with real-time checking
3. **Soft Threshold Warnings**: Progressive alerts at 50%, 70%, 80%, 90%, 95%
4. **Hard-Stop Enforcement**: Immediate blocking when quotas exceeded
5. **Human-Only Fallback**: Manual approval mode when automated access blocked
6. **Token Usage Daemon**: Background process for monitoring and aggregation
7. **File-Based Fallback**: JSONL logging when database offline
8. **Cost Tracking**: Per-actor monetary budgets with USD tracking
9. **Install Wizard Integration**: Setup steps for token governance
10. **Comprehensive Logging**: Audit trails for all governance actions

### Default Quotas

| Actor Type | Daily | Weekly | Monthly |
|------------|-------|--------|---------|
| System Actors | 100,000 | 500,000 | 2,000,000 |
| IDE Agents | 50,000 | 250,000 | 1,000,000 |
| Specialized Agents | 25,000 | 100,000 | 400,000 |
| Human Actors | 10,000 | 50,000 | 200,000 |

### Implementation Priority

1. Database schema and basic tracking
2. Quota enforcement logic
3. Warning system
4. Daemon implementation
5. Fallback mechanisms
6. Install wizard integration
7. Comprehensive testing

### Dependencies

- PRD 12: Base API integration functionality
- PRD 16: Lupopedia Headers for metadata schema
- PRD 80: Database Design Doctrine for schema requirements
