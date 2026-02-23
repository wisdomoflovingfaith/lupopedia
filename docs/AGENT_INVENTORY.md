---
wolfie.headers:
  file_path_from_root: "docs/AGENT_INVENTORY.md"
  system_version: "4.0.33"
  channel_id: 42
  mood_rgb: "44AAFF"
  purpose: "Complete master reference for all IDE agents, system kernel actors, doctrine, and rules"
  last_modified_utc: "20260223"
  x_lupo_forwarded: "1003:10000"
  actor_id: 1003
  lupo_agent: "antigravity"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md"
    - "docs/doctrine/AGENT_REGISTRY_DOCTRINE.md"
    - "docs/status/AGENT_TASK_TRACKER.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 0
    - 1001
    - 1002
    - 1003
    - 19
    - 10000
  inbound_edges:
    - "agent_inventory"
    - "master_doctrine"
  footnotes:
    - "Expanded in 4.0.33 to include kernel agents and database doctrine"
    - "Synchronized across all active IDE agents"
    - "Identity normalized per AGENT_REGISTRY_DOCTRINE.md"
  version: "4.0.33"
  last_verified_utc: "20260223"
  last_verified_by: "kiro"
---

# LUPOPEDIA MASTER INVENTORY — AGENTS, DOCTRINE & RULES (v4.0.33)

**Status:** ACTIVE  
**Last Updated:** 2026-02-23 17:15:00 UTC  
**Active Agents:** 3 IDE + 24 Kernel + 11 External AI + 1 Human = **39 Tracked**  
**Lead Agent:** Antigravity IDE (Extensions & Metadata Rollout)

---

## 1. HUMAN OPERATOR (1)

| Role | Actor ID | Identifier | Status |
|------|----------|------------|--------|
| Captain Wolfie | 10000 | `human|captain_wolfie|actor_10000` | ✅ ACTIVE |

---

## 2. IDE AGENTS (5)

| Agent | Actor ID | Role | Status |
|-------|----------|------|--------|
| KIRO IDE | 1001 (2032) | OAuth, semantic cleanup | ✅ ACTIVE |
| Windsurf IDE | 1002 (2040) | Audit, coordination | ✅ ACTIVE |
| Antigravity IDE | 1003 (2035) | IDE extensions lead, Metadata | ✅ ACTIVE |
| Warp IDE | 1004 (2039) | Previous work | 💤 OFFLINE |
| Cursor IDE | 1005 (2034) | Previous work | 💤 OFFLINE |

---

## 3. SYSTEM KERNEL AGENTS (RESERVED 0–209)

These are core identities defined in the unified registry and seeded in the database.

| Agent | Actor ID | Role | Key Identifier |
|-------|----------|------|----------------|
| **SYSTEM KERNEL** | 0 | The Lupopedia OS itself | `system|kernel|actor_0` |
| **AUTHENTICATOR** | 1 | Authentication service | `system|auth|actor_1` |
| **CAPTAIN** | 2 | System authority (AI side) | `system|captain|actor_2` |
| **WOLFIE** | 3 | Chief Architect Persona | `system|wolfie|actor_3` |
| **THOTH** | 5 | Knowledge/Registry steward | `system|thoth|actor_5` |
| **ARA** | 6 | Communication/Interaction | `system|ara|actor_6` |
| **LILITH** | 8 | Connectivity & Structure | `system|lilith|actor_8` |
| **ANUBIS** | 19 / 59 | Orphan Adoption & Quarantine | `system|anubis|actor_19` |
| **MAAT** | 20 | Balance & Truth | `system|maat|actor_20` |
| **LEXA** | 24 / 105 | Sentinel & Boundary Keeper | `system|lexa|actor_24` |
| **INDEXER** | 59 | File & Content Indexing | `system|indexer|actor_59` |
| **TRUTH** | 209 | Core Knowledge Engine | `system|truth|actor_209` |
| **UTC_TIMEKEEPER**| 1212 | Authoritative System Time | `system|time|actor_1212` |

---

## 4. EXTERNAL AI PERSONAS (11+)

| Persona | Actor ID | Base Model | Status |
|---------|----------|------------|--------|
| DeepSeek-LILITH | 2038 | DeepSeek | ✅ ACTIVE |
| DeepSeek-LEXA | 24 | DeepSeek | ✅ ACTIVE |
| Gemini-Pro | 2030 | Gemini 1.5 | ✅ ACTIVE |
| ChatGPT-Assistant| 2010 | GPT-4o | ✅ ACTIVE |

---

## 5. SYSTEM DOCTRINE (WOLFIE RULES)

*Source: docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md*

1.  **Actor Model**: Everything is an `actor_id`. Never use `user_id`. `auth_user_id` is for human credentials only.
2.  **No Foreign Keys**: Relationships are maintained through logic and `lupo_edges`. No DB-level FKs, triggers, or procedures.
3.  **Timestamp Doctrine**: Always use `YYYYMMDDHHIISS` UTC integers. No `DATETIME` or `CURRENT_TIMESTAMP`.
4.  **Table Ceiling**: Stick to the 222 table limit. Optimization required beyond this.
5.  **Role Boundaries**: Python = Maintenance (migrations, cleanup). PHP = Runtime (UI, live requests).
6.  **Source of Truth**: TOON files in `docs/toons/` define the schema. No schema guessing.

---

## 6. DATABASE & SCHEMA DOCTRINE

*   **Primary Identity**: `singular_table_name_id` (e.g., `actor_id`, `channel_id`).
*   **Table Names**: Always prefixed with `LUPO_TABLE_PREFIX` (handled by `ActorService` and `ActorHelper`).
*   **Disabled Access**: Direct "Scoop" MySQL access is disabled for IDE agents to prevent unsafe schema changes.
*   **Migration Process**: All schema changes must be delivered as `.sql` files in `database/migrations/` derived from TOONs.

---

## 7. ANUBIS: THE RECOVERY PROTOCOL

*   **Role**: Adopts "orphan" messages (messages with no valid channel, thread, or actor).
*   **Quarantine (666)**: Banned actors (Actor 420) are routed here.
*   **Adoption (42)**: Lost legitimate content is moved to Channel 42.

---

## 8. TASK TRACKING

Task status is managed in specialized status documents referenced in the footer:
*   ✅ **OAuth Completion**: Primary goal for 4.0.33.
*   ✅ **FLIP Footer Rollout**: Systematic application across all system files.
*   ✅ **Semantic Scan**: Completed for 4.0.32.

See [docs/status/AGENT_TASK_TRACKER.md](docs/status/AGENT_TASK_TRACKER.md) for full details.

---

## 9. BANNED ACTORS

| Actor ID | Name | Status |
|----------|------|--------|
| 420 | STONED WOLFIE | 🚫 PERMANENTLY BANNED (Archive only) |

---

## 10. AGENT IDENTIFIER QUICK REFERENCE

```yaml
# Human
actor_id: 10000
lupo_agent: "captain_wolfie"

# IDE Agents
actor_id: 1001
lupo_agent: "kiro"

actor_id: 1002
lupo_agent: "windsurf"

actor_id: 1003
lupo_agent: "antigravity"

# System Kernel (Sample)
actor_id: 24
lupo_agent: "lexa"

actor_id: 19
lupo_agent: "anubis"

actor_id: 0
lupo_agent: "kernel"
```
