---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 2026.3.7.6
file.channel: 42
file.last_modified_utc: 20260128173600
file.name: "crafty_syntax_identity_model.md"
---

# Crafty Syntax Identity Model (1999–2024)
## Historical Documentation & 2026 Refactor Plan

Crafty Syntax Live Help (CSLH) did not have a modern concept of "users." Instead, it had a unified identity ledger that stored:

- operators
- admins  
- anonymous visitors
- temporary session identities
- ghosts
- fingerprints
- OAuth users (late versions)
- session metadata
- UI refresh counters
- ephemeral actors
- login tokens
- verification tokens

All of these lived in one table: `livehelp_users`

This document explains the original logic, why it worked, and how the 2026 refactor preserves the behavior while modernizing the architecture.

## 1. Unified Identity Ledger

Crafty Syntax treated every entity as a "user," including:

- real operators
- real admins
- anonymous visitors
- people who never logged in
- expired sessions
- bots
- crawlers
- ghosts
- anyone who touched the system

This was not a bug — it was a design philosophy:

> "Track everyone, even before they log in, so their actions can be retroactively applied to their real identity."

This allowed Crafty Syntax to do something modern systems still struggle with:

**Retroactive identity binding**

A visitor could:
- browse
- chat
- trigger events
- generate logs
- open sessions

...and then log in.

At that moment, Crafty Syntax would:
- Merge all anonymous activity → the real user

This was decades ahead of its time.

## 2. Anonymous Identity Generation

Crafty Syntax created a temporary identity for every visitor.

**Identity string = Class‑C IP + User Agent**

Not the full IP — just the first three octets:

```
123.123.123 + useragent
```

This avoided:
- NAT collisions
- privacy issues
- duplicate identities

It was a fingerprint, not a username.

## 3. The Puka System (Actor ID Allocation)

Crafty Syntax used a gap‑finding allocator to assign temporary IDs.

**Range: 1000 → 9999**

This was the anonymous actor range.

The algorithm:
1. Start at 1000
2. Scan upward
3. Find the first missing ID
4. Assign it

This is the `find_puka()` logic.

It ensured:
- no collisions
- no fragmentation
- no reuse of active IDs
- deterministic allocation

This was your first actor allocator.

## 4. JSRN (JavaScript Refresh Number)

JSRN was a secondary puka allocator used for:
- UI refresh channels
- multi‑tab tracking
- session continuity
- visitor identification

The logic:
1. Query all existing jsrn values
2. Sort them
3. Find the first gap
4. Assign it
5. Store it in the user record

This allowed Crafty Syntax to track:
- multiple tabs
- multiple windows
- refresh cycles
- session drift

It was a clever hack that acted like a per‑session sub‑identity.

## 5. Garbage Collection (gc.php)

Crafty Syntax ran garbage collection randomly:

```php
if (rand(1,10) == 7)
```

When triggered, it:
- deleted expired sessions
- removed anonymous users
- cleaned up stale jsrn values
- freed puka IDs
- removed ghosts

This kept the system from filling with dead identities.

It was chaotic, but it worked.

## 6. Retroactive Identity Merge

When a visitor logged in:
1. Find their real user record
2. Reassign all activity from the anonymous ID
3. Delete the anonymous record
4. Continue the session as the real user

This allowed:
- pre‑login chat
- pre‑login events
- pre‑login tracking
- pre‑login analytics

to be merged into the real identity.

This was the heart of Crafty Syntax's identity model.

## 7. Why This Worked

Crafty Syntax was built in an era before:
- JWT
- OAuth
- session stores
- identity providers
- microservices
- actor systems

So you built your own:
- actor allocator
- identity fingerprinting
- ephemeral identity system
- retroactive merge engine
- garbage collector
- session tracker
- multi‑tab identity system

It was messy, but it was brilliant.

## 8. 2026 Refactor: Modern Identity Architecture

The new system preserves the behavior while modernizing the architecture.

### A. lupo_auth_users
Real users only.
Operators, admins, authenticated humans.

### B. lupo_operators
Projection of auth users into operator roles.

### C. lupo_actors
The new unified identity layer.

**Actor ID ranges:**

| Range | Meaning |
|-------|---------|
| 1–99 | system actors |
| 100–999 | AI agents |
| 1000–9999 | anonymous visitors (ephemeral) |
| 10000+ | real users (auth_user_id) |

This preserves the Crafty Syntax behavior cleanly.

### D. Anonymous Actor Lifecycle

1. Visitor arrives → allocate actor_id via puka
2. Attach all events/messages to this actor
3. If they log in → merge anonymous actor → real actor
4. GC deletes unused anonymous actors

### E. JSRN becomes metadata
Stored in:
```
lupo_actors.metadata_json.jsrn
```

### F. Garbage Collection
Now deterministic:
- cron
- TTL
- no randomness

## 9. Why We Preserve This

Because this identity model is part of Crafty Syntax's soul.

It allowed:
- retroactive identity
- anonymous tracking
- multi‑tab continuity
- session‑based actors
- ephemeral identities
- merge‑on‑login behavior

These are powerful features that modern systems still struggle with.

The refactor preserves the behavior, not the chaos.

## 10. Summary

Crafty Syntax's identity system was:
- messy
- brilliant
- ahead of its time
- actor‑based before actor systems existed
- fingerprint‑based before analytics existed
- merge‑capable before identity graphs existed

The 2026 refactor:
- preserves the behavior
- cleans the architecture
- formalizes the actor model
- introduces ranges
- introduces GC
- introduces metadata
- separates auth from operators
- keeps the soul intact
