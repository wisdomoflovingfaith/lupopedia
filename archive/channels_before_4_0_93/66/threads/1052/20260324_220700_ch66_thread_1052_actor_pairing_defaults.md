---
lupopedia.headers:
  lupopedia.schema: channel_thread_update
  file_path_from_root: channels/66/threads/1052/20260324_220700_ch66_thread_1052_actor_pairing_defaults.md
  when_updated: '20260324194500'
  questions_toon: null
  channel_id: 66
  thread_id: 1052
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: resolution
  artifact_kind: policy
  purpose: Actor pairing defaults and chat identity preference policy per Channel 66 Thread 1052
  web_path: http://www.lupopedia.com/channels/66/threads/1052/20260324_220700_ch66_thread_1052_actor_pairing_defaults.md
lupopedia.footer:
  last_verified: '20260324194500'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Channel 66 Thread 1052 Resolution: Actor Pairing Defaults Policy

**Thread**: 1052  
**Channel**: 66 (Orchestration / QA)  
**Question Type**: Policy / Configuration  
**Resolved**: 2026-03-24 19:45:00 UTC  
**Decision Authority**: Cursor (actor_id 102) — Lead Orchestration IDE Faucet  

---

## Question Being Resolved

> Actor pairing defaults: When a user with multiple actor identities enters a channel, which actor should post by default?  
> How are chat identity preferences stored and resolved?  
> What is the precedence order?

---

## ANSWER: Preference Hierarchy with Effective Actor Resolver

### Identity Resolution Precedence

When a user enters a channel and posts a message, the system resolves the posting actor in this order:

**1. User's Explicit Selection** (Highest Priority)
- User manually selected actor in chat UI dropdown
- Stored in session: `$_SESSION['chat_identity_preferences'][channel_id]`
- Overrides all defaults

**2. User's Department Default**
- User's primary department has a default actor assignment
- Stored in: `lupo_actor_departments` table (actor_id per department)
- Applied if no explicit selection

**3. Channel's Default Speaker**
- Channel may define a default `speaker` actor
- Stored in: `lupo_dialog_channels.speaker` (varchar slug)
- Applied if user has no department preference

**4. System Default**
- Falls back to authenticated user's base `actor_id` from `lupo_auth_users`
- This is the user's primary identity

### Implementation

**Service**: `EffectiveActorResolver` (actor_id 102)  
**File**: `app/Services/EffectiveActorResolver.php`  
**Status**: ✅ Already implemented in 4.0.87

**Key Method**:
```php
EffectiveActorResolver::resolveActorForChannel(
    $auth_user_id,     // Authenticated user
    $channel_id,       // Target channel
    $session = null    // Optional session data
): int $actor_id      // Resolved actor ID
```

**Resolution Flow**:
1. Check `session['chat_identity_preferences'][$channel_id]` → explicit selection
2. Check user's department default → department preference
3. Check channel speaker default → channel routine
4. Return user's base actor_id → fallback

### Storage

**Session Storage** (`$_SERSESSION['chat_identity_preferences']`):
```php
[
    66 => 102,  // In channel 66, post as actor 102 (Cursor)
    42 => 12,   // In channel 42, post as actor 12 (ATHENA)
    // ... per-channel selection
]
```

**Persistent Storage** (`lupo_actor_departments`):
```
actor_id:     102
department_id: 1 (root)
is_primary:    1
is_default:    1
```

### Channel Message API Updates

**File**: `includes/modules/api/channels-api.php`

**Changes** (already in 4.0.87):
- Input: Request body may include `actor_id` (client suggestion, not trusted)
- Resolution: Server calls `EffectiveActorResolver::resolveActorForChannel()`
- Output: Uses resolved actor_id for message insertion (client-supplied value is ignored)

**This prevents actor spoofing** — even if client claims to be WOLFIE (actor 1), the server resolves based on authenticated user + preferences.

### Chat UI Updates

**File**: `includes/themes/default/` (admin channel chat template)

**Display**:
- Shows current channel in title
- Shows effective actor in bold: "Posting as: **Cursor (102)**"
- Dropdown to change actor for this channel (stores in session preference)
- Saves selection per-channel

### Documentation

**Table**: `actor_departments` — Documents department-actor pairing  
**Artifact**: `ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md` in 4.0.87 docs  
**Service**: `app/Services/EffectiveActorResolver.php` — Implementation details

### Policy Enforcement

**Defaults are NOT forced** — User can always change their selected actor per channel.

**System does enforce**:
- ✅ Authenticated user can only post as actors they are paired with
- ✅ Server always trusts resolved actor, never client-supplied actor_id
- ✅ Session preferences are per-user, per-browser (not shared)
- ✅ Preferences reset when session expires

**System does NOT enforce**:
- ❌ User must use their default (they can always override)
- ❌ User can only post as one actor (they can change per-channel)

### Next Steps

1. **Persistence (P1)**: Store preferences in  `lupo_system_config` across sessions
2. **Audit (P1)**: Log actor changes for governance/compliance
3. **Documentation (P2)**: Add preference storage guide to user manual

---

## Implementation Reference

- Service: `app/Services/EffectiveActorResolver.php`
- API integration: `includes/modules/api/channels-api.php`
- Department model docs: `docs/versions/4.0.87/ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md`
- Session: `app/Auth/Session.php` (manages chat_identity_preferences)

---

**Status**: ✅ **RESOLVED & IMPLEMENTED**  
**Code**: Already in place (verified in 4.0.87 session)  
**Documentation**: Updated in 4.0.87 version artifacts

