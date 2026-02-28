# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\specs\UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md"
  file_hash: "186eacc7c39eddfb93a40da060e9b80f897e9bdd33381f3244d827248674b32c"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\specs\UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md"
  file_hash: "062c687a05e1d5dceffee22e71579e914fd684ee0ec29687ec44635fed917fba"
  file_path_from_root: "docs\specs\UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md"
  file_hash: "f6c7c3dde2c039c9ff2cade3c0a0bbc93000802b5e8de325c4c0554d138677fc"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "specs", "universal_wolfie_header_specificationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/specs/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md
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
  canonical: /specs/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION
  aliases:
    - /docs/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION
    - /qa/UNIVERSAL+WOLFIE+HEADER+SPECIFICATION
  slug: UNIVERSAL_WOLFIE_HEADER_SPECIFICATION
  slug_encoding: underscore
  base_path: /specs
  url_pattern: "/{base}/{slug}"
---

# Universal Wolfie Header Specification (Lupopedia 4.0.23)

## Overview

This specification defines the standard headers used for Wolfie protocol communication, including legacy Crafty Syntax compatibility and modern x-forwarded-for handling.

## Required Headers

### Wolfie Protocol Headers

| Header | Format | Required | Description |
|--------|---------|----------|-------------|
| `X-Wolfie-Actor-ID` | Integer | Yes | Actor ID from unified registry |
| `X-Wolfie-Message-ID` | UUID | Yes | Unique message identifier |
| `X-Wolfie-Timestamp` | YYYYMMDDHHMMSS | Yes | 14-digit UTC timestamp |
| `X-Wolfie-Channel-ID` | Integer | Yes | Target channel ID |
| `X-Wolfie-Thread-ID` | Integer | Yes | Target thread ID |

### Optional Headers

| Header | Format | Required | Description |
|--------|---------|----------|-------------|
| `X-Forwarded-For` | IP Address | No | Original client IP when proxy used |
| `X-Wolfie-Forwarded-For` | IP Address | No | Wolfie-specific forwarded IP |
| `X-Wolfie-Client-ID` | String | No | Client identifier |
| `X-Wolfie-Session-ID` | UUID | No | Session identifier |

## Legacy Crafty Syntax Headers

For backward compatibility with Crafty Syntax installations:

| Header | Legacy Equivalent | Notes |
|--------|------------------|--------|
| `HTTP_X_FORWARDED_FOR` | `X-Forwarded-For` | PHP auto-converts dashes to underscores |
| `HTTP_X_REAL_IP` | `X-Wolfie-Forwarded-For` | Legacy real IP header |
| `REMOTE_ADDR` | Direct IP | Fallback when no headers present |

## Header Processing Order

1. **Primary**: `X-Wolfie-Forwarded-For`
2. **Secondary**: `X-Forwarded-For`
3. **Tertiary**: `HTTP_X_REAL_IP`
4. **Fallback**: `REMOTE_ADDR`

## Implementation Examples

### PHP 5.3 Compatible Header Parsing

```php
<?php
// PHP 5.3 compatible header parsing (no short arrays, no ??)
function getWolfieForwardedIP() {
    $headers = array();
    
    // Check X-Wolfie-Forwarded-For first
    if (isset($_SERVER['HTTP_X_WOLFIE_FORWARDED_FOR'])) {
        $headers['forwarded_for'] = $_SERVER['HTTP_X_WOLFIE_FORWARDED_FOR'];
    }
    // Check standard X-Forwarded-For
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $headers['forwarded_for'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    // Check legacy HTTP_X_REAL_IP
    elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
        $headers['forwarded_for'] = $_SERVER['HTTP_X_REAL_IP'];
    }
    // Fallback to REMOTE_ADDR
    elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $headers['forwarded_for'] = $_SERVER['REMOTE_ADDR'];
    }
    else {
        $headers['forwarded_for'] = '0.0.0.0';
    }
    
    return $headers;
}

// Get all Wolfie headers
function getWolfieHeaders() {
    $headers = array();
    
    $wolfie_headers = array(
        'HTTP_X_WOLFIE_ACTOR_ID' => 'actor_id',
        'HTTP_X_WOLFIE_MESSAGE_ID' => 'message_id',
        'HTTP_X_WOLFIE_TIMESTAMP' => 'timestamp',
        'HTTP_X_WOLFIE_CHANNEL_ID' => 'channel_id',
        'HTTP_X_WOLFIE_THREAD_ID' => 'thread_id',
        'HTTP_X_WOLFIE_FORWARDED_FOR' => 'forwarded_for',
        'HTTP_X_WOLFIE_CLIENT_ID' => 'client_id',
        'HTTP_X_WOLFIE_SESSION_ID' => 'session_id'
    );
    
    foreach ($wolfie_headers as $server_key => $header_name) {
        if (isset($_SERVER[$server_key])) {
            $headers[$header_name] = $_SERVER[$server_key];
        }
    }
    
    return $headers;
}
?>
```

### SQL Storage Format

```sql
-- Store headers in lupo_contents.metadata_json
UPDATE lupo_contents 
SET metadata_json = JSON_OBJECT(
    'wolfie_headers', JSON_OBJECT(
        'actor_id', ?,
        'message_id', ?,
        'timestamp', ?,
        'channel_id', ?,
        'thread_id', ?,
        'forwarded_for', ?
    )
)
WHERE content_id = ?;
```

## Migration Notes

### From Crafty Syntax to Wolfie Protocol

1. **Header Mapping**: Map legacy headers to Wolfie equivalents
2. **IP Handling**: Implement proper x-forwarded-for parsing
3. **Metadata Storage**: Store headers in JSON metadata field
4. **Backward Compatibility**: Maintain support for legacy clients

### ANUBIS Integration

When ANUBIS processes orphan messages:

1. Check for `X-Wolfie-Actor-ID` header
2. If missing, attempt to resolve from forwarded IP
3. Assign actor_id 420 for test cases
4. Log adoption in `lupo_anubis_log`

## Database Mapping Layer (Optional)
The `X-LUPO-{table}.{column}` namespace allows explicit mapping between header
fields and database schema. This layer is optional and must not replace
semantic FLIP fields. It is intended for advanced tooling, migrations, and
schema-aware agents.

## Database Mapping Layer (Optional) - New in 4.0.28

The `X-LUPO-{table}.{column}` namespace allows explicit mapping between header
fields and database schema. This layer is optional and must not replace
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

## Security Considerations

1. **IP Validation**: Validate forwarded IP format
2. **Header Injection**: Sanitize all header values
3. **Rate Limiting**: Apply rate limits based on forwarded IP
4. **Actor Verification**: Verify actor_id exists in unified registry

## Version History

- **4.0.23**: Added X-Wolfie-Forwarded-For header
- **4.0.22**: Initial Wolfie protocol specification
- **Legacy**: Crafty Syntax header handling

## Testing

### Test Cases

1. **Standard Headers**: All required Wolfie headers present
2. **Forwarded IP**: X-Wolfie-Forwarded-For with valid IP
3. **Legacy Headers**: HTTP_X_FORWARDED_FOR compatibility
4. **Missing Headers**: Fallback to REMOTE_ADDR
5. **ANUBIS Adoption**: Orphan message with actor_id 420

### Expected Results

- All headers properly parsed and stored
- Forwarded IP correctly identified
- Legacy compatibility maintained
- ANUBIS can adopt orphan messages