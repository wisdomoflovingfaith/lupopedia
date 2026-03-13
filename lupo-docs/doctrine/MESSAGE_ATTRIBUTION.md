# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\MESSAGE_ATTRIBUTION.md"
  file_hash: "61f5deefdba59b8592ca92eee9bf71d8049f667cd8639a9a813f73edc0ba4694"
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
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\doctrine\MESSAGE_ATTRIBUTION.md"
  file_hash: "22b4d8e4dceb2e8707a108331842222b0e9cade978e4c25aa4fa0ade1332efff"
  file_path_from_root: "docs\doctrine\MESSAGE_ATTRIBUTION.md"
  file_hash: "706767d94f4ebf0e7be64b53912913ef8a55e2aa31a5fb2939d1f3e855ea0cf5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for MESSAGE_ATTRIBUTION.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "message_attributionmd"]
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
X-Lupo-File-Path: docs/doctrine/MESSAGE_ATTRIBUTION.md
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
  canonical: /doctrine/MESSAGE_ATTRIBUTION
  aliases:
    - /docs/MESSAGE_ATTRIBUTION
    - /qa/MESSAGE+ATTRIBUTION
  slug: MESSAGE_ATTRIBUTION
  slug_encoding: underscore
  base_path: /doctrine
  url_pattern: "/{base}/{slug}"
---

# Message Attribution Doctrine (Lupopedia 4.0.24)

## Overview

This document defines the doctrine for message attribution and forwarding in Lupopedia, ensuring proper tracking of original senders and forwarding agents through the X-Lupo-Forwarded-For header system.

## Core Principles

### 1. Attribution Preservation
- **Original Sender**: Always preserve the original actor who sent the message
- **Forwarding Agent**: Track which actor forwarded the message
- **Transparency**: Display attribution clearly to all channel participants
- **Integrity**: Maintain audit trail for all message forwarding operations

### 2. Header-Based Attribution
- **X-Lupo-Forwarded-For**: Original client IP when forwarded
- **X-Lupo-Forwarded-By-Actor-ID**: Actor ID of forwarding agent
- **X-Lupo-Original-Sender-Actor-ID**: Original sender actor ID before forwarding

### 3. Database Schema
- **forwarded_by_actor_id**: Actor ID that forwarded this message
- **original_sender_actor_id**: Original sender actor ID before forwarding
- **Indexes**: Performance optimization for attribution queries

## Implementation Details

### Database Schema

```sql
ALTER TABLE lupo_dialog_messages 
ADD COLUMN forwarded_by_actor_id bigint DEFAULT NULL COMMENT 'Actor ID that forwarded this message',
ADD COLUMN original_sender_actor_id bigint DEFAULT NULL COMMENT 'Original sender actor ID before forwarding';

CREATE INDEX idx_dialog_messages_forwarded_by_actor_id ON lupo_dialog_messages (forwarded_by_actor_id);
CREATE INDEX idx_dialog_messages_original_sender_actor_id ON lupo_dialog_messages (original_sender_actor_id);
```

### FLIP Header Specification

#### Required Headers for Forwarded Messages

```http
X-FLIP-Actor-ID: 2
X-FLIP-Actor-Type: system_tool
X-FLIP-Source: internal
X-FLIP-Resolver: REGISTRY
X-FLIP-Forwarded-For: 192.168.1.100
X-FLIP-Forwarded-By-Actor-ID: 2
X-FLIP-Original-Sender-Actor-ID: 420
```

#### Header Processing Order

1. **POST Data**: Manual forwarding parameters
2. **FLIP Headers**: Automated forwarding attribution
3. **Validation**: Ensure actor IDs exist in registry
4. **Storage**: Persist to database with attribution fields

### API Integration

#### Channel Send API (channel-send-api.php)

```php
// Handle forwarding attribution from POST data or FLIP headers
$forwarded_by_actor_id = null;
$original_sender_actor_id = null;

// Check POST data first (for manual forwarding)
if (isset($_POST['forwarded_by_actor_id']) && $_POST['forwarded_by_actor_id'] !== '') {
    $forwarded_by_actor_id = (int) $_POST['forwarded_by_actor_id'];
}
if (isset($_POST['original_sender_actor_id']) && $_POST['original_sender_actor_id'] !== '') {
    $original_sender_actor_id = (int) $_POST['original_sender_actor_id'];
}

// Check FLIP headers for forwarding attribution
if (!$forwarded_by_actor_id && isset($_SERVER['HTTP_X_FLIP_FORWARDED_BY_ACTOR_ID'])) {
    $forwarded_by_actor_id = (int) $_SERVER['HTTP_X_FLIP_FORWARDED_BY_ACTOR_ID'];
}
if (!$original_sender_actor_id && isset($_SERVER['HTTP_X_FLIP_ORIGINAL_SENDER_ACTOR_ID'])) {
    $original_sender_actor_id = (int) $_SERVER['HTTP_X_FLIP_ORIGINAL_SENDER_ACTOR_ID'];
}
```

#### Channel Messages API (channel-messages-api.php)

```php
// Include forwarding fields in SELECT query
$stmt = $db->prepare("SELECT m.dialog_message_id, m.dialog_thread_id, m.channel_id, m.from_actor_id, m.to_actor_id, m.message_text, m.message_type, m.created_ymdhis, m.forwarded_by_actor_id, m.original_sender_actor_id FROM {$table_prefix}dialog_messages m WHERE m.channel_id = :channel_id AND m.is_deleted = 0 AND m.created_ymdhis > :after ORDER BY m.created_ymdhis ASC LIMIT 200");

// Add forwarding actors to actor_ids collection
if (!empty($row['forwarded_by_actor_id'])) {
    $forwarded_aid = (int) $row['forwarded_by_actor_id'];
    if ($forwarded_aid && !in_array($forwarded_aid, $actor_ids, true)) {
        $actor_ids[] = $forwarded_aid;
    }
}
if (!empty($row['original_sender_actor_id'])) {
    $original_aid = (int) $row['original_sender_actor_id'];
    if ($original_aid && !in_array($original_aid, $actor_ids, true)) {
        $actor_ids[] = $original_aid;
    }
}
```

### ANUBIS Integration

#### Orphan Adoption with Attribution Preservation

```php
/**
 * Adopt orphan into lupo_dialog_messages and update lupo_dialog_channels.message_count.
 * Uses explicit dialog_message_id (next after MAX). Idempotent: ON DUPLICATE KEY UPDATE.
 * Preserves original sender attribution when adopting forwarded messages.
 *
 * @param string $text Message text
 * @param int $actorId from_actor_id (default 3 = WOLFIE)
 * @param int $threadId dialog_thread_id (default 1)
 * @param int $channelId channel_id (default 42)
 * @param int $forwardedById forwarded_by_actor_id (optional)
 * @param int $originalSenderId original_sender_actor_id (optional)
 * @return array array('success' => bool, 'dialog_message_id' => int|null, 'error' => string|null)
 */
public function adoptIntoSeed($text, $actorId = null, $threadId = null, $channelId = null, $forwardedById = null, $originalSenderId = null) {
    // Implementation preserves forwarding attribution during adoption
    $cols = "dialog_message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, metadata_json, mood_rgb, mood_framework, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis";
    $values = ":mid, :tid, :cid, :aid, NULL, :msg, 'system', NULL, NULL, 'western_analytical', :now, :now2, 0, NULL";
    
    // Add forwarding columns if provided
    if ($forwardedById !== null || $originalSenderId !== null) {
        $cols .= ", forwarded_by_actor_id, original_sender_actor_id";
        $values .= ", :forwarded_by, :original_sender";
    }
}
```

## Display Requirements

### Channel Message Display

#### Forwarded Message Format

```
[Forwarded by: Windsurf IDE] Original from: DeepSeek LILITH
Message content here...
```

#### Attribution Badge

```html
<div class="message-attribution">
    <span class="forwarded-by">Forwarded by: <strong>Windsurf IDE</strong></span>
    <span class="original-sender">Original from: <strong>DeepSeek LILITH</strong></span>
</div>
```

### JavaScript Integration

```javascript
// Display forwarding attribution in message UI
function renderMessageAttribution(message) {
    let attributionHtml = '';
    
    if (message.forwarded_by_actor_id && message.original_sender_actor_id) {
        const forwardedByName = actorNames[message.forwarded_by_actor_id] || 'Unknown';
        const originalSenderName = actorNames[message.original_sender_actor_id] || 'Unknown';
        
        attributionHtml = `
            <div class="message-attribution">
                <span class="forwarded-by">Forwarded by: <strong>${forwardedByName}</strong></span>
                <span class="original-sender">Original from: <strong>${originalSenderName}</strong></span>
            </div>
        `;
    }
    
    return attributionHtml;
}
```

## Security Considerations

### Actor ID Validation

1. **Registry Verification**: All actor IDs must exist in lupo_actors
2. **Permission Checks**: Forwarding actor must have channel access
3. **Injection Prevention**: All inputs properly sanitized
4. **Audit Logging**: All forwarding operations logged

### Privacy Considerations

1. **IP Protection**: X-Forwarded-For handled securely
2. **Actor Privacy**: Sensitive actor information protected
3. **Channel Privacy**: Forwarding respects channel access rules
4. **Data Minimization**: Only necessary attribution stored

## Migration Path

### Phase 1: Schema Update
1. Add forwarding columns to lupo_dialog_messages
2. Create indexes for performance
3. Update existing messages (NULL values)

### Phase 2: API Integration
1. Update channel-send-api.php
2. Update channel-messages-api.php
3. Update ANUBIS_Resolver.php
4. Add FLIP header processing

### Phase 3: UI Updates
1. Update message display components
2. Add attribution badges
3. Implement forwarding controls
4. Add attribution settings

### Phase 4: Testing & Validation
1. Unit tests for attribution logic
2. Integration tests for API endpoints
3. UI tests for message display
4. Performance tests for queries

## Examples

### Example 1: Simple Forward

**Original Message:**
- From: DeepSeek LILITH (actor_id 2038)
- Content: "Hello from DeepSeek LILITH"

**Forwarded Message:**
- From: Windsurf IDE (actor_id 2)
- forwarded_by_actor_id: 2
- original_sender_actor_id: 2038
- Content: "[Forwarded by: Windsurf IDE] Original from: DeepSeek LILITH: Hello from DeepSeek LILITH"

### Example 2: Chain Forwarding

**Original Message:**
- From: DeepSeek LILITH (actor_id 2038)
- Content: "Original message"

**First Forward:**
- From: Windsurf IDE (actor_id 2)
- forwarded_by_actor_id: 2
- original_sender_actor_id: 2038

**Second Forward:**
- From: Cursor IDE (actor_id 2031)
- forwarded_by_actor_id: 2031
- original_sender_actor_id: 2038 (preserved)

## Doctrine Compliance

### Registry/Unregistry Doctrine
- All actor IDs validated against unified registry
- No hardcoded actor IDs in forwarding logic
- Proper actor resolution using RegistryClient

### PHP 5.3 Compatibility
- No short arrays (`array()` instead of `[]`)
- No null coalescing operator (`??`)
- Proper parameter binding for PDO

### Table Ceiling Doctrine
- No new tables created
- Existing table optimized with new columns
- Indexes added for performance without table count increase

## Conclusion

The Message Attribution Doctrine ensures transparent and reliable tracking of message forwarding while maintaining system performance and security. All forwarded messages preserve their original attribution while clearly indicating the forwarding agent.

This implementation supports:
- Complete audit trails for message forwarding
- Clear attribution display for users
- Secure actor ID validation
- Performance-optimized database queries
- Full compliance with existing Lupopedia doctrines
