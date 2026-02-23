---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/ANUBIS/ANUBIS_ORPHAN_RULES.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260217235900"
# channel_id unresolved — requires lupo_contents lookup by application.
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/ANUBIS/ANUBIS_ORPHAN_RULES.md
---
# ANUBIS Orphan Rules

**Status:** Permanent.  
**Purpose:** Define what counts as an orphan, resolution order, and adoption rules for the ANUBIS subsystem.

---

## 1. Definition of an Orphan

A **dialog fragment or message** is treated as an **orphan** when:

- It has **no valid parent** in the sense of at least one of:
  - **channel_id** — Missing, null, or not present in lupo_dialog_channels / lupo_channels.
  - **dialog_thread_id** — Missing, null, or not present in lupo_dialog_threads for the given channel.
  - **from_actor_id** — Missing, null, or not present in lupo_actors (or not on the target channel per lupo_actor_channels).

- **Valid** means: the ID exists in the corresponding table and (where applicable) is_deleted = 0. Schema and column names come from TOONs only; no live-DB inference.

---

## 2. Resolution Order

When processing an orphan, ANUBIS must attempt resolution in this order:

1. **Resolve channel_id**
   - If channel_id is supplied and exists in lupo_dialog_channels (or lupo_channels) and is_deleted = 0, use it.
   - Otherwise, channel_id is unresolved.

2. **Resolve dialog_thread_id**
   - If dialog_thread_id is supplied and exists in lupo_dialog_threads for the resolved (or default) channel_id and is_deleted = 0, use it.
   - Otherwise, dialog_thread_id is unresolved.

3. **Resolve actor_id**
   - If from_actor_id (or actor_id) is supplied and exists in lupo_actors and is_deleted = 0, use it as from_actor_id.
   - Optionally verify the actor is on the target channel via lupo_actor_channels; if not required by policy, default actor may still be used.
   - Otherwise, actor_id is unresolved.

4. **If unresolved after the above**
   - **Adopt into canonical seed target:**
     - **channel_id** = 42 (Lupopedia Development).
     - **dialog_thread_id** = 1 (the Lupopedia Development seed thread).
     - **from_actor_id** = 3 (WOLFIE) unless a different default is specified by doctrine or config.

---

## 3. Adoption Rules

When adopting an orphan into the seed (or runtime):

| Rule | Requirement |
|------|-------------|
| Explicit dialog_message_id | Use the next ID after the highest existing seeded dialog_message_id. No reliance on AUTO_INCREMENT for seed path. |
| Timestamps | created_ymdhis = @now, updated_ymdhis = @now (BIGINT UTC YmdHis). Set in application or seed SQL. |
| message_type | Use `system` for adopted orphans unless doctrine specifies otherwise. |
| from_actor_id | Default 3 (WOLFIE) unless resolution supplied a valid actor_id. |
| to_actor_id | NULL unless specified. |
| is_deleted | 0. |
| deleted_ymdhis | NULL. |
| Idempotent insert | Use `INSERT ... ON DUPLICATE KEY UPDATE` in seed SQL so re-runs do not duplicate. |
| message_count | After inserting a new message into a channel, update lupo_dialog_channels.message_count for that channel_id (e.g. 31 → 32). |

---

## 4. Tables Used (No Schema Changes)

- **lupo_dialog_messages** — Target of adoption insert; also used to determine next dialog_message_id (e.g. MAX(dialog_message_id) + 1).
- **lupo_dialog_threads** — To validate dialog_thread_id and channel association.
- **lupo_dialog_channels** — To validate channel_id and to update message_count.
- **lupo_actor_channels** — Optional: verify actor on channel.
- **lupo_actors** — To validate from_actor_id.
- **lupo_banned_actors** — Banned actor_ids; ANUBIS does not adopt orphans from these actors.
- **lupo_edges** — Not required for orphan adoption; used elsewhere for HAS_CONTENT resolution.

All column and table names must match TOONs. No new columns or tables are added by ANUBIS.

---

## 5. Banned Actors

ANUBIS **must not adopt** orphans whose `from_actor_id` is on the **banned actor list**. Deprecated experimental personas that promoted forbidden doctrine (per `.cursor/rules/`) are on this list.

| Rule | Behavior |
|------|----------|
| Source of truth | `lupo_banned_actors` table. Columns: banned_actor_id, actor_id, ip_address (optional), reason, banned_ymdhis, banned_by_actor_id, is_deleted. |
| Lookup | ANUBIS reads `SELECT actor_id FROM lupo_banned_actors WHERE is_deleted = 0`. Fallback: `BANNED_ACTOR_IDS_FALLBACK` (e.g. 999) if table missing. |
| Classification | When `from_actor_id` is in banned list, ANUBIS returns `is_rejected => true`, `rejected_reason => 'banned_actor'`. |
| Adoption | `adoptIntoSeed()` rejects adoption when `actorId` is in banned list; returns `success => false`, `error => 'Banned actor; adoption rejected'`. |
| Orphan messages | Orphan fragments attributed to banned actors are **not read or adopted**. They remain unadopted. |
| IP | Optional `ip_address` (varchar 45) for future IP-based ban correlation; not required for ANUBIS adoption logic. |

Messages from banned actors may exist in the database (e.g. from historical import); ANUBIS does not adopt **new** orphans from those actors.
