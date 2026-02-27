---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/doctrine/ANUBIS_ORPHAN_RULES.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260223004108"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /doctrine/ANUBIS_ORPHAN_RULES
  aliases:
    - /docs/ANUBIS_ORPHAN_RULES
    - /qa/ANUBIS+ORPHAN+RULES
  slug: ANUBIS_ORPHAN_RULES
  slug_encoding: underscore
  base_path: /doctrine
  url_pattern: "/{base}/{slug}"
---

# ANUBIS Orphan Rules - Unknown Recipient Protocol

**File**: docs/doctrine/ANUBIS_ORPHAN_RULES.md  
**Purpose**: Define adoption and processing rules for unknown-recipient files  
**Version**: 4.0.29  
**Status**: ACTIVE  

---

## Overview

ANUBIS (actor_id 19) serves as the Orphan Resolver for all files with unknown FLIP header recipients. This protocol ensures deterministic handling of orphaned files while maintaining system integrity and auditability.

## Actor 19 Specification

```sql
SELECT * FROM lupo_actors WHERE actor_id = 19;
-- Expected: ANUBIS - Orphan Resolution System
```

- **Actor ID**: 19
- **Actor Type**: `system`
- **Name**: `ANUBIS`
- **Slug**: `anubis`
- **Purpose**: Orphan resolution and file adoption
- **Status**: `active`
- **Security Level**: `elevated`

## Unknown Recipient Triggers

A file is routed to ANUBIS when ANY of the following conditions are met:

### 1. Missing Channel
- `channel_id` not present in FLIP header
- `channel_id` value is null or invalid
- Channel does not exist in `lupo_channels`
- Channel is soft-deleted (`is_deleted = 1`)

### 2. Invalid Actor
- `actor_id` not present in FLIP header
- `actor_id` value is null or invalid
- Actor does not exist in `lupo_actors`
- Actor is soft-deleted (`is_deleted = 1`)
- Actor is not operational (`is_active = 0`)

### 3. Unresolvable via Edges
- File references `edge_id` that cannot be resolved
- Edge points to non-existent target
- Edge is soft-deleted or inactive

### 4. Unknown File Type
- File extension not recognized
- Content type cannot be determined
- File lacks proper FLIP header structure

## ANUBIS Processing Protocol

### Step 1: Intake Logging
```sql
INSERT INTO lupo_anubis_log (
    anubis_log_id,
    event_type,
    file_path_from_root,
    severity,
    status,
    assigned_to_actor_id,
    created_ymdhis
) VALUES (
    :log_id,           -- Unique BIGINT ID
    'ORPHAN_FOUND',    -- event_type
    :file_path,        -- file_path_from_root
    'normal',          -- severity
    'Pending',         -- status
    19,                -- ANUBIS actor_id
    :timestamp         -- YmdHis
);
```

### Step 2: Classification
Classify orphan based on analysis:

- **UNKNOWN_CHANNEL**: Channel not found or invalid
- **UNKNOWN_ACTOR**: Actor not found or invalid
- **UNKNOWN_EDGE**: Edge resolution failed
- **UNKNOWN_TYPE**: File type unrecognized
- **MALFORMED_HEADER**: FLIP header structure invalid

### Step 3: Adoption Rules

#### Default Adoption (Safe Files)
- **Target Channel**: 42 (Protocol Development)
- **Target Thread**: 1 (System Thread)
- **Status**: `adopted`
- **Visibility**: `public`

#### Restricted Adoption (Sensitive Files)
- **Target Channel**: 666 (ANUBIS Quarantine)
- **Target Thread**: 2 (Quarantine Thread)
- **Status**: `quarantined`
- **Visibility**: `private`

#### Rejection (Malicious Files)
- **Target Channel**: 666 (ANUBIS Quarantine)
- **Target Thread**: 3 (Rejection Thread)
- **Status**: `rejected`
- **Visibility**: `hidden`

### Step 4: Record Creation
```sql
INSERT INTO lupo_dialog_messages (
    dialog_message_id,
    dialog_thread_id,
    channel_id,
    from_actor_id,
    message_text,
    message_type,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    :message_id,
    :thread_id,
    :channel_id,
    19,
    CONCAT('ANUBIS: Adopted orphan file - ', :file_path, ' (Reason: ', :reason_code, ')'),
    'adoption',
    :timestamp,
    :timestamp,
    0
);
```

## Security Considerations

### File Validation
Before adoption, ANUBIS must validate:
- File size limits (max 10MB)
- Content type safety
- Header structure integrity
- No malicious payloads

### Rate Limiting
- Max 100 adoptions per hour
- Burst protection for mass orphan events
- Queue overflow protection

### Audit Requirements
- All adoptions logged with full context
- Original FLIP header preserved
- Processing timestamps recorded
- Decision rationale documented

## Router Integration

### determineRecipient() Function
```php
function determineRecipient($file) {
    $header = parseFlipHeader($file);
    
    // 1. Valid channel
    if (isset($header['channel_id']) && channelExists($header['channel_id'])) {
        return ['type' => 'channel', 'id' => $header['channel_id']];
    }
    
    // 2. Valid actor
    if (isset($header['actor_id']) && actorExists($header['actor_id'])) {
        return ['type' => 'actor', 'id' => $header['actor_id']];
    }
    
    // 3. Unknown → ANUBIS
    return [
        'type' => 'actor',
        'id' => 19,
        'reason' => 'unknown_recipient'
    ];
}
```

### Validation Functions
```php
function channelExists($channel_id) {
    $db = DatabaseFactory::getConnection();
    $result = $db->fetch(
        "SELECT channel_id FROM lupo_channels 
         WHERE channel_id = :id AND is_deleted = 0",
        ['id' => $channel_id]
    );
    return !empty($result);
}

function actorExists($actor_id) {
    $db = DatabaseFactory::getConnection();
    $result = $db->fetch(
        "SELECT actor_id FROM lupo_actors 
         WHERE actor_id = :id AND is_deleted = 0 AND is_active = 1",
        ['id' => $actor_id]
    );
    return !empty($result);
}
```

## Protocol Activation

The unknown recipient protocol is activated when:

```php
define('UNKNOWN_RECIPIENT_PROTOCOL_ACTIVE', true);
```

### Activation Checklist
- [x] Actor 19 exists and is operational
- [x] ANUBIS_ORPHAN_RULES.md documented
- [x] Router functions implemented
- [x] Logging schema created
- [x] Security validation in place
- [x] Rate limiting configured
- [x] Audit trails enabled

## Error Handling

### Router Errors
- **Parse Failure**: Log error, route to ANUBIS as MALFORMED_HEADER
- **Database Error**: Retry with exponential backoff
- **Timeout**: Queue for async processing

### ANUBIS Errors
- **Adoption Failure**: Log to quarantine channel
- **Validation Error**: Reject with detailed reason
- **System Overload**: Queue for later processing

## Monitoring

### Key Metrics
- Orphan file intake rate
- Adoption success rate
- Quarantine rate
- Processing latency
- Error rate

### Alerts
- High orphan rate (>50/hour)
- Adoption failures (>10%)
- Quarantine overflow (>100 files)
- System errors (>5/hour)

---

## Version History

- **4.0.29**: Initial protocol definition
- **Future**: Enhanced semantic resolution, machine learning classification

---

**Maintainer**: Captain Wolfie Stoned - Lupopedia LLC 2026  
**Last Updated**: 2026-02-22  
**Next Review**: 4.1.0 development phase
