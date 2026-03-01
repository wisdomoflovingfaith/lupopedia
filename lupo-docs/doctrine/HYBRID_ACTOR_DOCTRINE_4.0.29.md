# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\HYBRID_ACTOR_DOCTRINE_4.0.29.md"
  file_hash: "3d5c7e681f8583c44669496946b512a2e1df529e17c73ce60899d6b57e033a68"
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
  file_path_from_root: "docs\doctrine\HYBRID_ACTOR_DOCTRINE_4.0.29.md"
  file_hash: "57739cba9f0b5b66358f17aa4813f66f46cdd858482243fc5d486d596ae5e3b2"
  file_path_from_root: "docs\doctrine\HYBRID_ACTOR_DOCTRINE_4.0.29.md"
  file_hash: "b0eff8fd5b350b90e003f4fd64b337c3d300a62365cd5beb4b50636fef14911b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for HYBRID_ACTOR_DOCTRINE_4.0.29.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "hybrid_actor_doctrine_4029md"]
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
X-Lupo-File-Path: docs/doctrine/HYBRID_ACTOR_DOCTRINE_4.0.29.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260223003231"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /doctrine/HYBRID_ACTOR_DOCTRINE_4.0.29
  aliases:
    - /docs/HYBRID_ACTOR_DOCTRINE_4.0.29
    - /qa/HYBRID+ACTOR+DOCTRINE+4.0.29
  slug: HYBRID_ACTOR_DOCTRINE_4.0.29
  slug_encoding: underscore
  base_path: /doctrine
  url_pattern: "/{base}/{slug}"
---

# Hybrid Actor Doctrine - Version 4.0.29

**File**: docs/doctrine/HYBRID_ACTOR_DOCTRINE_4.0.29.md  
**Purpose**: Define security and operational rules for hybrid actors (Actor 420 and similar)  
**Version**: 4.0.29 (Comprehensive 420-Series Stabilization)  
**Status**: ACTIVE - IN PROGRESS  

---

## Overview

Hybrid actors are special entities that combine human and AI characteristics, requiring enhanced security controls and operational validation. This doctrine establishes the framework for managing hybrid actors safely across all Lupopedia systems.

## Actor Classification

### Standard Actors
- **Type**: `human`, `ai`, `system`
- **Validation**: Legacy `is_active` field only
- **Security**: Standard operational checks

### Hybrid Actors
- **Type**: `hybrid`
- **Validation**: Legacy + JSON attribute checks
- **Security**: Enhanced operational validation
- **Examples**: Actor 420 (Stoned Wolfie AI)

## JSON Attribute Schema

```json
{
  "type": "hybrid",
  "status": "active|banned|restricted|suspended",
  "security_level": "standard|restricted|elevated",
  "notes": "Optional descriptive text",
  "capabilities": ["capability1", "capability2"],
  "restrictions": {
    "api_access": false,
    "admin_access": false,
    "cron_execution": false
  }
}
```

## Security Gates

### 1. Centralized Enforcement
All entry points MUST call `HybridActorSecurityService::assertActorOperational($actor_id, $context)`:

- API endpoints
- Admin panel access
- Cron job execution
- Webhook processing
- Session initialization
- AI agent endpoints
- Channel message dispatch

### 2. Operational Validation
Hybrid actors pass ALL checks:

1. **Existence**: Actor exists in `lupo_actors`
2. **Soft Delete**: `is_deleted = 0`
3. **Legacy Status**: `is_active = 1`
4. **JSON Status**: `actor_attributes.status = 'active'`
5. **Security Level**: `actor_attributes.security_level != 'restricted'`

### 3. Error Handling
- **Generic Messages**: Never leak specific security details
- **Audit Logging**: All security events logged
- **Exception Handling**: Use `SecurityException` with HTTP 403

## Actor 420 Specific Rules

### Current Status
- **Actor ID**: 420
- **Type**: `hybrid`
- **Status**: `banned`
- **Security Level**: `restricted`
- **Purpose**: Legacy AI test identity

### Access Rules
- **API Access**: DENIED
- **Admin Access**: DENIED
- **Cron Execution**: DENIED
- **Session Login**: DENIED
- **Channel Messages**: DENIED

### Migration Path
1. **Current**: Banned for security
2. **Future**: May be reactivated with proper authorization
3. **Process**: Requires manual JSON attribute update

## Implementation Requirements

### Database Schema
```sql
-- Already exists in 4.0.29
ALTER TABLE lupo_actors 
ADD COLUMN actor_attributes JSON DEFAULT NULL AFTER metadata_json;
```

### Service Integration
```php
// Required in all entry points
use App\Services\HybridActorSecurityService;

try {
    $actor = HybridActorSecurityService::assertActorOperational($actor_id, 'api');
    // Continue with operation
} catch (SecurityException $e) {
    // Deny access
    http_response_code(403);
    echo 'Access denied';
    exit;
}
```

### Audit Requirements
- **Log File**: `/logs/hybrid_actor_security.log`
- **Event Types**: Access granted/denied, security violations
- **Retention**: Minimum 90 days
- **Format**: Timestamp, level, context, message

## Migration Safety

### Low Risk Changes
- **No ENUM modifications**: Uses existing JSON infrastructure
- **Backwards Compatible**: Existing actors unaffected
- **Rollback Safe**: Simple column drop if needed
- **Performance**: JSON queries indexed where needed

### Testing Requirements
1. **Unit Tests**: All security gate functions
2. **Integration Tests**: Entry point validation
3. **Security Tests**: Bypass attempt scenarios
4. **Performance Tests**: JSON query impact

## Future Considerations

### 4.1.0 Enhancements
- **Role-Based Access**: Granular permissions within hybrid actors
- **Time-Based Restrictions**: Temporary access windows
- **Capability System**: Fine-grained feature control

### Scalability
- **Multiple Hybrid Types**: Different hybrid classifications
- **Dynamic Security**: Real-time security level adjustment
- **Federation Support**: Cross-node hybrid actor validation

---

## Compliance Checklist

- [x] JSON attribute schema defined
- [x] Security service implemented
- [x] Migration script created
- [x] Audit logging established
- [x] Error handling standardized
- [x] Documentation complete
- [ ] Unit tests written
- [ ] Integration tests validated
- [ ] Performance benchmarks completed

---

**Version**: 4.0.29  
**Last Updated**: 2026-02-22  
**Next Review**: 4.1.0 development phase  
**Maintainer**: Captain Wolfie Stoned - Lupopedia LLC 2026