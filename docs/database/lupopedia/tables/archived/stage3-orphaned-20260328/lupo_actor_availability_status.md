---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/archived/stage3-orphaned-20260328/lupo_actor_availability_status.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/archived/stage3-orphaned-20260328/lupo_actor_availability_status.md
  status: ''
  when_updated: '20260513053635'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: table_documentation
  artifact_kind: table
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: database_table
  prd_cluster: null
  title: ''
  summary: ''
---
> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# Table: lupo_actor_availability_status

**Purpose:** Tracks real-time availability status of operators in live help system by channel.  
**Type:** database_table  
**Status:** new (Phase 1 implementation)  
**Scope:** Channel-scoped, actor-scoped  
**Access Pattern:** High-read (status polling), medium-write (status updates)

---

## 1. Overview

This table maintains the current operational status of each operator (actor) within each channel of the live help system. It answers the critical question: "Which operators are available right now in channel X?"

- **Key Responsibility:** Real-time operator availability tracking
- **System Role:** Core to chat routing engine — routing decisions depend on accurate, up-to-date availability data
- **Importance:** Blocking table for live help feature parity (Softaculous requirement)

---

## 2. Schema Reference

**Primary Key:** `availability_id`

### All Fields

| Column | Type | Notes |
|--------|------|-------|
| `availability_id` | BIGINT NOT NULL | PK; auto-assigned via DeterministicIdService |
| `actor_id` | BIGINT NOT NULL | FK (lupo_actors); the operator |
| `channel_id` | BIGINT NOT NULL | FK (lupo_channels); scopes this status to a channel |
| `status` | VARCHAR(32) NOT NULL DEFAULT 'offline' | Enum: online, busy, away, offline |
| `last_activity_ymdhis` | BIGINT NOT NULL | UTC timestamp of last activity; used for auto-away timeout |
| `created_ymdhis` | BIGINT NOT NULL DEFAULT 0 | Created timestamp |
| `updated_ymdhis` | BIGINT NOT NULL | Last updated timestamp |
| `is_deleted` | TINYINT NOT NULL DEFAULT 0 | Soft delete flag (0 = active, 1 = deleted) |
| `deleted_ymdhis` | BIGINT NULL | Soft delete timestamp (if is_deleted = 1) |

### Field Semantics

- **status Enum Values:**
  - `online` — Operator is actively working, ready to accept chats
  - `busy` — Operator is at max capacity (concurrent chat limit reached)
  - `away` — Operator manually set away (e.g., break, lunch)
  - `offline` — Operator logged out or inactive >15 min (auto-away)

- **last_activity_ymdhis:** Updated on:
  - Manual status change (admin or operator UI)
  - Chat acceptance/handling
  - Any operator action in the system
  - Used to detect idle operators → auto-away transition

---

## 3. Relationships and Dependencies

**FK Constraints (Application-Enforced):**
- `actor_id` → `lupo_actors.actor_id` (operator must exist)
- `channel_id` → `lupo_channels.channel_id` (channel must exist)

**Referencing Tables:**
- `lupo_channel_messages` — When chat posted, route via availability lookup
- `lupo_collections` — When chat accepted, mark operator as busy

**Unique Constraint:**
- `(actor_id, channel_id)` — Only one availability record per operator per channel

---

## 4. Indexes and Performance

**Primary Index:**
- `availability_id` (PK)

**Performance Indexes:**
- `(actor_id, channel_id)` UNIQUE — Prevents duplicates; enables quick lookup
- `(status, channel_id)` — Find available operators in a channel
- `(channel_id)` — List all operators in a channel
- `(last_activity_ymdhis)` — Find idle operators for auto-away
- `(is_deleted)` — Filter soft-deleted records

**Index Strategy:**
- Cold reads (finding available operators): use `(status, channel_id)`
- Warm writes (status updates): single-row by PK
- Auto-away detection: scan `(is_deleted, last_activity_ymdhis)` with timestamp filter

---

## 5. Usage Patterns

**Common Queries:**

```sql
-- Find available operators in a channel (for routing)
SELECT actor_id FROM lupo_actor_availability_status
WHERE channel_id = :channel_id
  AND status IN ('online', 'busy')
  AND is_deleted = 0;

-- Get operator's current status
SELECT status FROM lupo_actor_availability_status
WHERE actor_id = :operator_id
  AND channel_id = :channel_id
  AND is_deleted = 0;

-- Update operator status
UPDATE lupo_actor_availability_status
SET status = :new_status, updated_ymdhis = :now, last_activity_ymdhis = :now
WHERE actor_id = :operator_id
  AND channel_id = :channel_id
  AND is_deleted = 0;

-- Find idle operators (auto-away candidates)
SELECT actor_id, channel_id
FROM lupo_actor_availability_status
WHERE status IN ('online', 'busy')
  AND last_activity_ymdhis < :idle_threshold_ymdhis
  AND is_deleted = 0;
```

**Best Practices:**
- Always filter `is_deleted = 0` in queries (soft delete doctrine)
- Use indexed columns in WHERE clauses
- Update `last_activity_ymdhis` on every operator action
- Consider caching availability by channel for high-frequency reads

**Anti-Patterns:**
- Avoid full table scans; use indexed columns
- Don't store stale data; ensure `last_activity_ymdhis` is current
- Don't delete records; use soft delete (is_deleted = 1)

---

## 6. Performance Considerations

- **Volume:** Typically 5-100 operators per channel; scales linearly
- **Read Heavy:** Status polling every 2-5 seconds per operator
- **Write Light:** Status updates on manual change, chat acceptance, or auto-away
- **Scaling Tips:**
  - Cache availability by channel in memory (invalidate on update)
  - Use read replicas for status polling (non-critical reads)
  - Batch auto-away scans (run once per minute, not per request)

---

## 7. Data Integrity

**Constraints:**
- PK: `availability_id` — Uniqueness enforced
- UNIQUE: `(actor_id, channel_id)` — Prevents duplicate status records
- NOT NULL: `actor_id`, `channel_id`, `status`, `last_activity_ymdhis`, `updated_ymdhis`
- DEFAULT: `status = 'offline'`, `is_deleted = 0`

**Soft Delete:**
- Use `is_deleted = 1, deleted_ymdhis = :timestamp` for deletions
- Queries must filter `is_deleted = 0`

**Validation Rules (Application-Enforced):**
- `status` must be one of: online, busy, away, offline
- `actor_id` must exist in `lupo_actors`
- `channel_id` must exist in `lupo_channels`
- `last_activity_ymdhis` must be <= current UTC time

---

## 8. Integration Points

**Services Using This Table:**

1. **ActorAvailabilityService** (`includes/classes/ActorAvailabilityService.php`)
   - `getStatusForOperator(actor_id, channel_id)`
   - `setStatus(actor_id, channel_id, status)`
   - `getAvailableOperators(channel_id)`
   - `touchActivity(actor_id, channel_id)`

2. **ChatRoutingService** (`includes/classes/ChatRoutingService.php`)
   - Queries available operators when routing new chats

3. **Admin UI** (`includes/modules/admin/sections/actor-status.php`)
   - Displays operator status board
   - Allows manual status toggle

4. **Live Handler** (`includes/modules/livehelp/livehelp-handler.php`)
   - Updates status on chat acceptance/decline

---

## 9. Lifecycle

**Creation:**
- Record created when operator first goes online in a channel
- Triggered by login or admin assignment to channel

**Updates:**
- `status` changes on operator or admin action
- `last_activity_ymdhis` updated on every activity
- `updated_ymdhis` updated on any field change

**Deletion:**
- Soft delete when operator unassigned from channel
- Hard delete during system cleanup (rare)

---

## 10. Common Issues and Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| High latency in status polling | Unindexed WHERE `(status, channel_id)` queries | Verify index exists; consider caching |
| Operators never auto-away | `last_activity_ymdhis` not updated | Ensure all actions call `touchActivity()` |
| Duplicate status records | Insertion without unique constraint check | Use ActorAvailabilityService (enforces uniqueness) |
| Stale availability data | Polling interval too long | Reduce polling interval or use WebSocket |
| Soft delete not respected | Queries don't filter `is_deleted = 0` | Always include `is_deleted = 0` in WHERE clauses |

---

## 11. TOON Reference

Full schema definition stored in: `database/lupopedia/toon/lupo_actor_availability_status.toon`

Generated/verified with: `python scripts/verify_db_against_toons.py`

