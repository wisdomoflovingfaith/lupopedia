---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/x_lupo_forwarded_header_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/x_lupo_forwarded_header_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# Message Forwarding and Attribution Header Doctrine

**Version:** 4.0.89  
**Effective:** Version 4.0.89+  
**Status:** ACTIVE  
**Authority:** WOLFIE (actor_id: 1)  
**Updated:** 2026-04-05  

---

## Overview

This doctrine documents the current message forwarding and attribution headers used in Lupopedia. The original `X-LUPO-FORWARDED` header has been deprecated and replaced with more specific headers for better tracking of message attribution and forwarding chains.

---

## Current Header Format

### Active Headers

**1. X-Lupo-Forwarded-For**
- Purpose: Tracks the original client IP when forwarded
- Format: IP address string
- Used in: ANUBIS_Resolver.php, DialogMessageVerifier.php

**2. X-Lupo-Forward-Chain**
- Purpose: Tracks the chain of actor IDs involved in forwarding
- Format: "actor_id -> actor_id -> actor_id"
- Used in: ANUBIS_Resolver.php, VSX extension

**3. X-Lupo-Forwarded-By-Actor-ID**
- Purpose: Actor ID of the forwarding agent
- Format: Numeric actor ID
- Documented in: MESSAGE_ATTRIBUTION.md

**4. X-Lupo-Original-Sender-Actor-ID**
- Purpose: Original sender actor ID before forwarding
- Format: Numeric actor ID
- Documented in: MESSAGE_ATTRIBUTION.md

---

## Implementation Status

### Deprecated Header

**X-LUPO-FORWARDED** (deprecated)
- Original format: "computer_agent_id:supporting_human_id"
- Status: **DEPRECATED** - No longer used in active code
- Replaced by: More specific headers above
- Reason: Ambiguous naming, limited functionality

### Current Implementation

**Files using current headers:**
1. **ANUBIS_Resolver.php** - Implements X-Lupo-Forwarded-For and X-Lupo-Forward-Chain
2. **DialogMessageVerifier.php** - Verifies X-Lupo-Forwarded-For headers
3. **VSX Extension (headers.ts)** - Maps headers for IDE integration
4. **MESSAGE_ATTRIBUTION.md** - Documents complete attribution system

---

## Header Usage Examples

### ANUBIS_Resolver.php Implementation

```php
$headers = array(
    'X-LUPO-FORWARDED-FOR' => $originalActorId,
    'X-LUPO-FORWARD-CHAIN' => $originalActorId . ' -> ' . $adopterActorId,
    // ...
);
```

### DialogMessageVerifier.php Usage

```php
if (isset($data['forward_headers']['X-Lupo-Forwarded-For'])) {
    return $data['forward_headers']['X-Lupo-Forwarded-For'];
}
```

### VSX Extension Mapping

```typescript
'x-forwarded-for': 'lupo_forwarded_for',
'x-forward-chain': 'lupo_forward_chain',
```

---

## Migration from Deprecated Header

### What Changed

- **Old:** `X-LUPO-FORWARDED: computer_agent_id:supporting_human_id`
- **New:** Specific headers for different attribution needs
- **Benefit:** Clearer semantics, better tracking capabilities

### Migration Steps

1. **✅ Completed:** Updated ANUBIS_Resolver.php to use new headers
2. **✅ Completed:** Updated DialogMessageVerifier.php for new format
3. **✅ Completed:** Updated VSX extension header mapping
4. **✅ Completed:** Documented new system in MESSAGE_ATTRIBUTION.md
5. **📋 This Update:** Mark old doctrine as deprecated

---

## Current Actor ID Assignments

### IDE Agents (Computer Agents)

Based on current registry:
- **Cursor IDE:** actor_id 102
- **Windsurf IDE:** actor_id 103
- **KIRO IDE:** actor_id 100
- **Warp IDE:** actor_id 101
- **Cascade IDE:** actor_id 104
- **Zencoder IDE:** actor_id 105
- **Antigravity IDE:** actor_id 106

### Human Operators

- **Root User:** actor_id 10000
- **Additional Humans:** actor_id 10001+

---

## Validation and Verification

### Current Validation

**DialogMessageVerifier.php** includes:
- Validation of X-Lupo-Forwarded-For headers
- Reporting of messages with forwarding headers
- Verification of header format and content

### Manual Validation

```bash
# Check for current header usage
grep -r "X-Lupo-Forwarded-For" . --include="*.php"
grep -r "X-Lupo-Forward-Chain" . --include="*.php"

# Check for deprecated header usage
grep -r "X-LUPO-FORWARDED" . --include="*.php"
```

---

## Related Documentation

**Current (Active):**
- `docs/doctrine/MESSAGE_ATTRIBUTION.md` - Complete attribution system
- `includes/classes/ANUBIS_Resolver.php` - Implementation
- `includes/classes/DialogMessageVerifier.php` - Verification
- `tools/vsx-extension/src/lupopedia/headers.ts` - IDE integration

**Historical (Deprecated):**
- This document (original X-LUPO-FORWARDED doctrine)
- Original actor ID assignments (updated in current registry)

---

## Conclusion

The message forwarding and attribution system has evolved from the single `X-LUPO-FORWARDED` header to a more sophisticated set of headers that provide better tracking and attribution capabilities.

**Key Points:**
1. `X-LUPO-FORWARDED` is **DEPRECATED** and no longer used
2. Current headers provide specific attribution and forwarding tracking
3. Implementation is spread across ANUBIS_Resolver, DialogMessageVerifier, and VSX extension
4. MESSAGE_ATTRIBUTION.md is the authoritative documentation for the current system
5. Actor IDs are now managed through the comprehensive actor registry

---

**Doctrine Status:** UPDATED (deprecated old format, documented current)  
**Effective Version:** 4.0.89  
**Authority:** WOLFIE (actor_id: 1)  
**Maintained By:** System Architecture  
**Last Updated:** 2026-04-05  

---

## Migration Notice

This document has been updated to reflect the current header system. The original `X-LUPO-FORWARDED` header doctrine is preserved here for historical reference, but the headers described in the "Current Header Format" section should be used for all new implementations.

For current implementations, refer to `docs/doctrine/MESSAGE_ATTRIBUTION.md` as the authoritative source.
