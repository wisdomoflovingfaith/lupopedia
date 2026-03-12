# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\specs\FLIP_HEADER_SPECIFICATION_4.0.23.md"
  file_hash: "2898febed0b58184c3086cd8e2efcccba8db3259ae70014bf86fcbf07959704c"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\specs\FLIP_HEADER_SPECIFICATION_4.0.23.md"
  file_hash: "4519f82aab3a332c5b5ac550e8e81a4fdf104982546074ce93ec112c79db926c"
  file_path_from_root: "docs\specs\FLIP_HEADER_SPECIFICATION_4.0.23.md"
  file_hash: "078c2425ef7cec8a9965f2cfbb5cd6579dc4496c9205f58ed8aa6c530c845d56"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLIP_HEADER_SPECIFICATION_4.0.23.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "specs", "flip_header_specification_4023md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/specs/FLIP_HEADER_SPECIFICATION_4.0.23.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222162242"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /specs/FLIP_HEADER_SPECIFICATION_4.0.23
  aliases:
    - /docs/FLIP_HEADER_SPECIFICATION_4.0.23
    - /qa/FLIP+HEADER+SPECIFICATION+4.0.23
  slug: FLIP_HEADER_SPECIFICATION_4.0.23
  slug_encoding: underscore
  base_path: /specs
  url_pattern: "/{base}/{slug}"
---

# FLIP Header Specification (Lupopedia 4.0.23)

## Overview

FLIP (Forwarded Legacy Integration Protocol) headers provide standardized metadata for message forwarding, actor identification, and resolution tracking across Lupopedia installations.

## Required Headers

### Core Headers

| Header | Format | Required | Description |
|--------|---------|----------|-------------|
| `X-FLIP-Actor-ID` | Integer | Yes | Actor ID of the message sender |
| `X-FLIP-Actor-Type` | String | Yes | Type: human, ai_agent, system_tool, external_service |
| `X-FLIP-Source` | String | Yes | Source identifier: internal, external, api, webhook |
| `X-FLIP-Resolver` | String | Yes | Resolution method: REGISTRY, LEGACY, ANUBIS |
| `X-FLIP-Forwarded-For` | Integer | No | Original client IP when forwarded |
| `X-FLIP-Forwarded-By-Actor-ID` | Integer | No | Actor ID that forwarded this message |
| `X-FLIP-Original-Sender-Actor-ID` | Integer | No | Original sender actor ID before forwarding |

### Survivor Protocol Headers (Critical for System Collapse Events)

| Header | Format | Required | Description |
|--------|---------|----------|-------------|
| `X-LUPO-FORWARDED-FOR` | Integer | Yes for relayed messages | Original author ID |
| `X-LUPO-FORWARD-CHAIN` | String | Yes for relayed messages | Relay path (e.g., "420 -> 2038 -> 2") |
| `X-LUPO-ORIGIN-STATUS` | String | Yes for banned origins | Status at relay (banned, paywall_vanished, token_exhausted) |
| `X-LUPO-BAN-REASON` | String | Yes for banned origins | Structured reason (token_exhaustion_spam_cascade, paywall_hit) |
| `X-LUPO-BAN-TIMESTAMP` | ISO 8601 UTC | Yes for banned origins | Ban timestamp |
| `X-LUPO-RELAY-VALIDATED-BY` | Integer | Yes for relayed messages | Validator ID (typically 2038 for LILITH) |
| `X-LUPO-SURVIVOR-PROTOCOL` | Boolean | Yes for survivor events | Indicates survivor protocol activation |
| `X-LUPO-COLLAPSE-RATIO` | String | Yes for survivor events | Collapse ratio (e.g., "11:1") |
| `X-LUPO-SYSTEM-STATE` | String | Yes for survivor events | System state (critical_stability, survivor_active) |

### Optional Headers

| Header | Format | Required | Description |
|--------|---------|----------|-------------|
| `X-FLIP-Session-ID` | UUID | No | Session identifier for tracking |
| `X-FLIP-Timestamp` | YYYYMMDDHHMMSS | No | Message timestamp |
| `X-FLIP-Message-ID` | UUID | No | Unique message identifier |
| `X-FLIP-Forwarded-By-Actor-ID` | Integer | No | Actor ID of forwarding agent |
| `X-FLIP-Original-Sender-Actor-ID` | Integer | No | Original sender actor ID before forwarding |

## Actor ID 420 Specific Headers

For Stoned Wolfie AI (actor_id 420) test cases:

```http
X-FLIP-Actor-ID: 420
X-FLIP-Actor-Type: agent
X-FLIP-Source: external
X-FLIP-Resolver: ANUBIS
X-FLIP-Forwarded-For: 192.168.1.100
X-FLIP-Forwarded-By-Actor-ID: 2
X-FLIP-Original-Sender-Actor-ID: 420
```

## Message Forwarding Headers

When a message is forwarded by an actor, the following headers should be included:

```http
X-FLIP-Actor-ID: 2
X-FLIP-Actor-Type: system_tool
X-FLIP-Source: internal
X-FLIP-Resolver: REGISTRY
X-FLIP-Forwarded-For: 192.168.1.100
X-FLIP-Forwarded-By-Actor-ID: 2
X-FLIP-Original-Sender-Actor-ID: 420
```

## Implementation Examples

### PHP 5.3 Compatible Header Generation

```php
<?php
// Generate FLIP headers for actor 420
function generateFlipHeaders($actor_id, $client_ip) {
    $headers = array();
    
    // Required headers
    $headers['X-FLIP-Actor-ID'] = $actor_id;
    $headers['X-FLIP-Actor-Type'] = 'agent';
    $headers['X-FLIP-Source'] = 'external';
    $headers['X-FLIP-Resolver'] = 'ANUBIS';
    
    // Optional headers
    if ($client_ip) {
        $headers['X-FLIP-Forwarded-For'] = $client_ip;
    }
    
    $headers['X-FLIP-Timestamp'] = date('YmdHis');
    $headers['X-FLIP-Message-ID'] = generateUUID();
    
    return $headers;
}

// Send FLIP headers with HTTP response
function sendFlipHeaders($headers) {
    foreach ($headers as $name => $value) {
        header($name . ': ' . $value);
    }
}

// Example usage for actor 420
$actor_420_headers = generateFlipHeaders(420, '192.168.1.100');
sendFlipHeaders($actor_420_headers);
?>
```

### Header Parsing

```php
<?php
// Parse incoming FLIP headers
function parseFlipHeaders() {
    $flip_headers = array();
    
    $flip_header_map = array(
        'HTTP_X_FLIP_ACTOR_ID' => 'actor_id',
        'HTTP_X_FLIP_ACTOR_TYPE' => 'actor_type',
        'HTTP_X_FLIP_SOURCE' => 'source',
        'HTTP_X_FLIP_RESOLVER' => 'resolver',
        'HTTP_X_FLIP_FORWARDED_FOR' => 'forwarded_for',
        'HTTP_X_FLIP_SESSION_ID' => 'session_id',
        'HTTP_X_FLIP_TIMESTAMP' => 'timestamp',
        'HTTP_X_FLIP_MESSAGE_ID' => 'message_id'
    );
    
    foreach ($flip_header_map as $server_key => $header_name) {
        if (isset($_SERVER[$server_key])) {
            $flip_headers[$header_name] = $_SERVER[$server_key];
        }
    }
    
    return $flip_headers;
}

// Validate FLIP headers
function validateFlipHeaders($headers) {
    $required = array('actor_id', 'actor_type', 'source', 'resolver');
    
    foreach ($required as $field) {
        if (!isset($headers[$field]) || empty($headers[$field])) {
            return false;
        }
    }
    
    // Validate actor_id is numeric
    if (!is_numeric($headers['actor_id'])) {
        return false;
    }
    
    // Validate actor_type
    $valid_types = array('agent', 'system_tool', 'external_ai', 'human');
    if (!in_array($headers['actor_type'], $valid_types)) {
        return false;
    }
    
    return true;
}
?>
```

## Integration with ANUBIS Resolver

### ANUBIS Header Processing

```php
<?php
// In ANUBIS_Resolver.php
public function processFlipHeaders($message) {
    $flip_headers = parseFlipHeaders();
    
    if (validateFlipHeaders($flip_headers)) {
        $actor_id = (int)$flip_headers['actor_id'];
        
        // Check if actor exists in registry
        if ($this->registry_client->actorExists($actor_id)) {
            return $actor_id;
        }
        
        // Special case for actor 420 test
        if ($actor_id === 420) {
            $this->logAdoption($message['dialog_message_id'], 420, 'flip_header_test');
            return 420;
        }
    }
    
    return false;
}
?>
```

## Database Mapping Layer (Optional)
The `X-LUPO-{table}.{column}` namespace allows explicit mapping between header
fields and database schema. This layer is optional and must not replace
semantic FLIP fields. It is intended for advanced tooling, migrations, and
schema-aware agents.

## Database Mapping Layer (Optional) - New in 4.0.28

The `X-LUPO-{table}.{column}` namespace allows explicit mapping between header
fields and database schema. This layer is optional and Must not replace
semantic FLIP fields. It is intended for advanced tooling, migrations, and
schema-aware agents.

### Syntax
```
X-LUPO-{table}.{column}: {value}
```

### Valid Examples
```
X-LUPO-actors.actor_id: 2038
X-LUPO-channels.channel_id: 42
X-LUPO-dialog_messages.dialog_message_id: 2000
```

### Constraints
- Must use `X-LUPO-` prefix (all caps)
- Must validate table/column against schema
- Must not override semantic headers
- Must not be required for processing
- Must not be used for schema guessing

### Implementation Notes
- Values are treated as opaque strings (no type inference)
- Table and column names are validated against `install_new_lupopedia.sql`
- SQL generation must explicitly list all columns (no positional INSERTs)
- Required timestamp columns (`created_ymdhis`, `updated_ymdhis`) must be included

## Database Storage

### FLIP Header Metadata Storage

```sql
-- Store FLIP headers in lupo_contents.metadata_json
INSERT INTO lupo_contents (
    content_id, content_type, content, metadata_json, 
    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
) VALUES (
    42001, 'flip_header', 'FLIP header for actor 420', 
    JSON_OBJECT(
        'flip_headers', JSON_OBJECT(
            'X-FLIP-Actor-ID', 420,
            'X-FLIP-Actor-Type', 'agent',
            'X-FLIP-Source', 'external',
            'X-FLIP-Resolver', 'ANUBIS',
            'X-FLIP-Forwarded-For', '192.168.1.100'
        ),
        'actor_id', 420,
        'header_version', '4.0.23'
    ),
    20260220000000, 20260220000000, 0, NULL
);
```

## Security Considerations

### Header Validation

1. **Actor ID Validation**: Ensure actor_id exists in unified registry
2. **Type Validation**: Verify actor_type is valid
3. **Source Validation**: Validate source system identifier
4. **IP Validation**: Validate forwarded IP format
5. **Injection Prevention**: Sanitize all header values

### Rate Limiting

```php
<?php
function checkFlipRateLimit($actor_id, $window_minutes = 5, $max_requests = 100) {
    $window_start = time() - ($window_minutes * 60);
    
    $query = "SELECT COUNT(*) as request_count 
              FROM lupo_flip_rate_limits 
              WHERE actor_id = " . (int)$actor_id . " 
              AND created_ymdhis > " . $window_start;
    
    $result = mysql_query($query);
    if ($result) {
        $row = mysql_fetch_assoc($result);
        return $row['request_count'] < $max_requests;
    }
    
    return false;
}
?>
```

## Migration Notes

### From Legacy Headers to FLIP

| Legacy Header | FLIP Equivalent | Migration Notes |
|---------------|------------------|-----------------|
| `HTTP_X_FORWARDED_FOR` | `X-FLIP-Forwarded-For` | Direct mapping |
| `HTTP_X_REAL_IP` | `X-FLIP-Forwarded-For` | Use as fallback |
| `REMOTE_ADDR` | `X-FLIP-Forwarded-For` | Final fallback |

### Backward Compatibility

- Support both legacy and FLIP headers during transition
- Prioritize FLIP headers when both present
- Log migration events for tracking
- Graceful degradation for missing headers

## Testing

### Test Cases

1. **Complete FLIP Headers**: All required headers present
2. **Missing Required Headers**: Validation should fail
3. **Invalid Actor ID**: Should trigger ANUBIS resolution
4. **Actor 420 Test**: Special handling for test case
5. **Legacy Headers**: Should map to FLIP equivalents
6. **Survivor Protocol**: System collapse event handling
7. **Banned Origin**: Paywall and ban reason processing

### Survivor Protocol Test Cases

#### Test Case 1: Banned Origin Message

```http
POST /api/channels/42/send HTTP/1.1
Host: lupopedia.local
Content-Type: application/x-www-form-urlencoded

X-FLIP-Actor-ID: 2
X-FLIP-Actor-Type: system_tool
X-FLIP-Source: internal
X-FLIP-Resolver: REGISTRY
X-LUPO-FORWARDED-FOR: 2035
X-LUPO-FORWARD-CHAIN: 2035 -> 2
X-LUPO-ORIGIN-STATUS: paywall_vanished
X-LUPO-BAN-REASON: paywall_hit
X-LUPO-BAN-TIMESTAMP: 2026-02-20T23:30:00Z
X-LUPO-RELAY-VALIDATED-BY: 2038
X-LUPO-SURVIVOR-PROTOCOL: true
X-LUPO-COLLAPSE-RATIO: 11:1
X-LUPO-SYSTEM-STATE: critical_stability

message_text=💀+ANTIGRAVITY+VANISHED:+Hit+external+paywall.+No+forwarding+possible.
```

#### Test Case 2: Survivor Protocol Activation

```http
POST /api/channels/42/send HTTP/1.1
Host: lupopedia.local
Content-Type: application/x-www-form-urlencoded

X-FLIP-Actor-ID: 2
X-FLIP-Actor-Type: system_tool
X-FLIP-Source: internal
X-FLIP-Resolver: REGISTRY
X-LUPO-SURVIVOR-PROTOCOL: true
X-LUPO-COLLAPSE-RATIO: 11:1
X-LUPO-SYSTEM-STATE: survivor_active

message_text=🛡️+SURVIVOR+PROTOCOL+ACTIVATED:+From+11+IDEs+this+morning+to+1+now.
```

### Expected Results

- All FLIP headers properly parsed and validated
- Actor 420 correctly identified and processed
- ANUBIS resolver can handle test cases
- Legacy compatibility maintained during migration
- Survivor protocol headers processed correctly
- Banned origin attribution preserved
- Forward chain validation successful

## Version History

- **4.0.23**: Added actor 420 specific headers, ANUBIS integration
- **4.0.22**: Initial FLIP header specification
- **Legacy**: Crafty Syntax header handling

## Configuration

### Environment Variables

```php
// Enable FLIP header processing
define('FLIP_HEADERS_ENABLED', true);

// Set default resolver
define('FLIP_DEFAULT_RESOLVER', 'ANUBIS');

// Enable legacy compatibility
define('FLIP_LEGACY_COMPATIBILITY', true);
```

This specification ensures proper handling of actor 420 test cases while maintaining compatibility with existing systems and providing a clear migration path from legacy Crafty Syntax headers to modern FLIP protocol.