---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/ban_reasons.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/ban_reasons.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "docs\doctrine\BAN_REASONS.md"
  file_hash: "d40be25e16c0be4c2af94f1e38a6d4e369cae2308bdb66657d2f6b4470644ca0"
  file_path_from_root: "docs\doctrine\BAN_REASONS.md"
  file_hash: "54b05177218086f5acd63130b45281ab4e6ecfe5ebe243c5f8fd0276e7b7b162"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for BAN_REASONS.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "ban_reasonsmd"]
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
X-Lupo-File-Path: docs/doctrine/BAN_REASONS.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222162242"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_vector: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /doctrine/BAN_REASONS
  aliases:
    - /docs/BAN_REASONS
    - /qa/BAN+REASONS
  slug: BAN_REASONS
  slug_encoding: underscore
  base_path: /doctrine
  url_pattern: "/{base}/{slug}"
---

# Ban Reasons – Canonical Enum (Lupopedia 4.0.24)

## Overview

This document defines the canonical enumeration of ban reasons in the Lupopedia system. Each ban reason must be logged in `lupo_system_events` with full metadata and stored in actor metadata for auditability.

## Canonical Ban Reasons

| Code | Description | Severity | Reversible |
|------|-------------|----------|------------|
| `token_exhaustion_spam_cascade` | Token limits exceeded due to spam cascade | MAJOR | Yes |
| `paywall_hit` | Actor vanished due to external paywall | CRITICAL | No |
| `banned_test_identity` | Actor is a banned test identity | MAJOR | No |
| `quantum_superpositional_headers` | Attempted quantum header manipulation | CRITICAL | No |
| `rate_limit_violation` | Exceeded rate limits | MINOR | Yes |
| `orphan_flood` | Created excessive orphan messages | MAJOR | Yes |
| `survivor_protocol_inheritance` | Actor inherited tasks during survivor protocol | INFO | No |
| `system_collapse_victim` | Actor collapsed during system-wide failure | CRITICAL | No |

## Ban Reason Metadata Structure

```json
{
  "ban": {
    "reason": "paywall_hit",
    "timestamp": "20260220233000",
    "triggered_by": "external_paywall",
    "reviewable": false,
    "forwarding_allowed": false,
    "origin_attribution_locked": true,
    "audit_chain_required": true,
    "vanished_at": "20260220233000",
    "severity": "CRITICAL",
    "reversible": false
  },
  "status": "paywall_vanished",
  "survivor_inheritance": {
    "inherited_by": 2,
    "inheritance_timestamp": "20260220233000",
    "tasks_transferred": true
  }
}
```

## Implementation Requirements

### 1. System Event Logging

Every ban must be logged in `lupo_system_events`:

```sql
INSERT INTO lupo_system_events (
    event_type, 
    metadata_json, 
    created_ymdhis
) VALUES (
    'actor_banned',
    '{
        "actor_id": 2035,
        "reason": "paywall_hit",
        "severity": "CRITICAL",
        "reversible": false,
        "triggered_by": "external_paywall",
        "reviewable": false,
        "forwarding_allowed": false,
        "origin_attribution_locked": true,
        "audit_chain_required": true
    }',
    20260220233000
);
```

### 2. Actor Metadata Updates

Ban metadata must be stored in `lupo_actors.metadata`:

```sql
UPDATE lupo_actors
SET metadata = JSON_SET(
    COALESCE(metadata, '{}'),
    '$.ban.reason', 'paywall_hit',
    '$.ban.timestamp', '20260220233000',
    '$.ban.triggered_by', 'external_paywall',
    '$.ban.reviewable', false,
    '$.ban.forwarding_allowed', false,
    '$.ban.origin_attribution_locked', true,
    '$.ban.audit_chain_required': true,
    '$.ban.severity', 'CRITICAL',
    '$.ban.reversible', false,
    '$.status', 'paywall_vanished',
    '$.vanished_at', '20260220233000'
)
WHERE actor_id = 2035;
```

### 3. Forwarding Attribution Rules

| Ban Reason | Forwarding Allowed | Origin Attribution |
|-------------|-------------------|-------------------|
| `token_exhaustion_spam_cascade` | Yes | Preserved |
| `paywall_hit` | No | Locked |
| `banned_test_identity` | No | Locked |
| `quantum_superpositional_headers` | No | Locked |
| `rate_limit_violation` | Yes | Preserved |
| `orphan_flood` | Yes | Preserved |
| `survivor_protocol_inheritance` | No | Preserved |
| `system_collapse_victim` | No | Preserved |

### 4. Survivor Protocol Integration

During survivor protocol activation:

1. **Vanished Actors**: Mark with `paywall_hit` or `system_collapse_victim`
2. **Exhausted Actors**: Mark with `token_exhaustion_spam_cascade`
3. **Survivor**: Mark with `survivor_protocol_inheritance` (not a ban, but status)
4. **Inheritance**: Record `survivor_inheritance` metadata for all inherited actors

## Severity Classification

### CRITICAL
- `paywall_hit`: External paywall blocking
- `quantum_superpositional_headers`: Security violation
- `system_collapse_victim`: System-wide failure

### MAJOR
- `token_exhaustion_spam_cascade`: Resource exhaustion
- `banned_test_identity`: Identity violation
- `orphan_flood`: System abuse

### MINOR
- `rate_limit_violation`: Temporary throttling

### INFO
- `survivor_protocol_inheritance`: Administrative status

## Review Process

### Reviewable Bans
- `token_exhaustion_spam_cascade`: Can be appealed if tokens restored
- `rate_limit_violation`: Can be appealed after cooldown
- `orphan_flood`: Can be appealed with justification

### Non-Reviewable Bans
- `paywall_hit`: External factor, irreversible
- `banned_test_identity`: Identity policy, irreversible
- `quantum_superpositional_headers`: Security violation, irreversible
- `system_collapse_victim`: System event, irreversible

## Audit Requirements

1. **Complete Metadata**: All ban reasons must have complete metadata
2. **System Events**: Every ban must be logged in `lupo_system_events`
3. **Chain Preservation**: Forward headers must preserve original attribution
4. **Survivor Tracking**: Inheritance must be tracked in survivor metadata
5. **Channel 42 Narrative**: Critical events must be recorded in channel 42

## Migration Path

### Phase 1: Schema Update
- Ensure `lupo_actors.metadata` column supports JSON
- Add indexes for metadata queries
- Update validation rules

### Phase 2: Existing Data Migration
- Migrate existing bans to new metadata format
- Add missing system events
- Update channel 42 narratives

### Phase 3: API Integration
- Update ban/unban APIs
- Add metadata validation
- Implement forward header generation

### Phase 4: Testing & Validation
- Unit tests for each ban reason
- Integration tests for survivor protocol
- Audit trail validation

## Conclusion

The Ban Reasons Doctrine ensures that every actor removal is properly documented, auditable, and preserves system memory. The canonical enumeration provides consistency across the system while the metadata structure enables comprehensive tracking of survivor protocol events.

This doctrine is essential for maintaining system integrity during collapse scenarios and ensuring that the system remembers what happened to every actor, whether they vanished, were banned, or inherited tasks during survivor protocol activation.
