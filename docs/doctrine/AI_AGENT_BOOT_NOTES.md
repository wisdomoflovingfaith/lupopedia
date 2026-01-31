# AI Agent Boot Notes

**File:** `docs/doctrine/AI_AGENT_BOOT_NOTES.md`
**Purpose:** Instructions for initializing AI coding agents with Lupopedia's canonical doctrine
**Status:** Reference document for future agent boot system

---

## Overview

All AI agents working on Lupopedia must be initialized with the canonical doctrine before making any code changes. This ensures:

- Consistency across multi-agent workflows
- Preservation of architectural integrity
- Prevention of doctrine drift
- Proper enforcement of boundaries

---

## Required Reading

Before any AI agent begins work, it must load and understand:

📘 **[`docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md`](LUPOPEDIA_CANONICAL_DOCTRINE.md)**

---

## Core Doctrine Points (Quick Reference)

All AI agents must respect:

### 1. Identity Model
- **`actor_id` is the primary identity key**
- **There is no `user_id` in the canonical model**
- All sessions, permissions, ownership, and uploads use `actor_id`
- `auth_user_id` is only for human login credentials

### 2. Database Doctrine
- **No foreign keys**
- **No triggers**
- **No stored procedures**
- **No cascading deletes**
- All timestamps use `YYYYMMDDHHIISS` in UTC
- Table limit: 222 (soft ceiling)

### 3. Schema Source of Truth
- All schema changes must come from **TOON files** in `/docs/toons/`
- Never infer schema from PHP code
- Never infer schema from existing SQL
- Never invent columns or tables

### 4. Language Boundaries
- **Python = maintenance layer**
  - Migrations, cleanup, verification, hashing, indexing
  - Scripts live in `scripts/python/`
  - Use PyMySQL (no ORM)
- **PHP = runtime layer**
  - Website runtime, request handling, operator interface
  - Never perform migrations or schema changes

### 5. Upload Structure
- Hash-based filenames (SHA256)
- Date-based directories: `uploads/{actors,agents,channels,operators}/YYYY/MM/`
- No user-provided filenames as canonical identifiers

### 6. Session Model
- Anonymous → authenticated session upgrade
- Sessions keyed by `session_id` (PHPSESSID) and linked to `actor_id`
- Never create duplicate session rows
- Upgrade existing sessions on login

### 7. LEXA Enforcement
- LEXA is the Sentinel-class agent enforcing doctrine
- Integrated into LLM gateway with pre-routing, pre-execution, and post-execution hooks
- Emits boundary violations when rules are broken

### 8. Multi-Agent Workflow
- Respect role boundaries (Wolfie, Copilot, JetBrains, Cursor, LEXA, etc.)
- Never "modernize" away from doctrine
- Treat migration as stewardship, not replacement

---

## Canonical Reminder Block (For Prompts)

When initializing any AI coding agent (JetBrains, Cursor, Claude Code, etc.), **prepend this block to your prompt:**

```
IMPORTANT — Lupopedia uses an ACTOR MODEL:

- actor_id is the primary identity key
- There is no user_id
- All sessions, permissions, ownership, and uploads use actor_id
- auth_user_id is only for human login
- No foreign keys, triggers, or stored procedures
- All timestamps use YYYYMMDDHHIISS in UTC
- Schema changes must come from TOON files in /docs/toons/
- Table limit is 222
- Python = maintenance (scripts/python/, PyMySQL, explicit SQL)
- PHP = runtime only (no schema changes)
- Uploads use SHA256 hash filenames under uploads/{actors,agents,channels,operators}/YYYY/MM/
- LEXA enforces doctrine and boundaries in the gateway

Never introduce user_id.
Never add foreign keys, triggers, or stored procedures.
Never modify schema without TOON source.
Never let PHP perform migrations.
```

---

## Anti-Patterns (NEVER Do These)

When working on Lupopedia, AI agents must **never**:

- ❌ Introduce `user_id` anywhere
- ❌ Add foreign keys, triggers, or stored procedures
- ❌ Use non-UTC or non-YYYYMMDDHHIISS timestamps
- ❌ Create tables beyond the 222 soft limit without explicit optimization
- ❌ Infer schema from runtime code
- ❌ Let PHP perform migrations or schema changes
- ❌ Let Python handle runtime HTTP requests
- ❌ Store raw filenames as canonical identifiers
- ❌ Bypass LEXA's boundary checks
- ❌ "Helpfully" refactor away the actor model

---

## Future: Formal Agent Boot System

Currently, agents are initialized manually by providing the canonical doctrine. In the future, Lupopedia may implement:

- Automated agent boot sequence
- Doctrine validation on agent startup
- Agent registry with role-based permissions
- LEXA-enforced agent authentication
- Versioned agent prompts loaded from `lupo-agents/`

This document will serve as the foundation for that system.

---

## Related Documentation

- [`LUPOPEDIA_CANONICAL_DOCTRINE.md`](LUPOPEDIA_CANONICAL_DOCTRINE.md) — Full canonical doctrine
- [`/docs/toons/`](../toons/) — TOON schema definitions
- [`LEXA_GATEWAY_INTEGRATION.md`](LEXA_GATEWAY_INTEGRATION.md) — LEXA enforcement details
- [`AGENT_BOUNDARIES_COMPACT.md`](AGENT_BOUNDARIES_COMPACT.md) — Compact doctrine reference

---

**Last Updated:** 2026-01-31
**Maintained By:** Lupopedia Stewards & LEXA
