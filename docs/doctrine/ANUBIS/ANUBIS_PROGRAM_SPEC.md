---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/ANUBIS/ANUBIS_PROGRAM_SPEC.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260217235900"
# channel_id unresolved — requires lupo_contents lookup by application.
---
# ANUBIS Program Specification

**Status:** Permanent.  
**Purpose:** Define the Python and PHP components of the ANUBIS subsystem and the database tables they use.

---

## 1. Components

### 1.1 Python: Orphan Scanner, Resolver, Adoption Planner

- **Script:** `tools/anubis_orphan_scanner.py`
- **Input:** Dialog text (and optionally channel_id, dialog_thread_id, actor_id).
- **Output:** Classification (orphan / resolved), resolution plan (channel_id, dialog_thread_id, from_actor_id), and adoption plan (explicit dialog_message_id, target channel 42 / thread 1, default actor 3).
- **Steps:**
  1. Try to resolve channel_id (if supplied, check against lupo_dialog_channels / lupo_channels).
  2. Try to resolve dialog_thread_id (if supplied, check against lupo_dialog_threads for the channel).
  3. Try to resolve actor_id (if supplied, check against lupo_actors).
  4. If any remain unresolved → adoption plan: channel_id=42, dialog_thread_id=1, from_actor_id=3 (WOLFIE), message_type='system', next dialog_message_id, @now timestamps.
- **Constraints:** Parameterized SQL only. No schema inference; use TOON-defined table/column names. Read-only resolution; no insert from Python unless explicitly specified (adoption into seed is typically done via seed SQL or PHP runtime).

### 1.2 PHP: Runtime Orphan Handler for Live Dialogs

- **Class:** `lupo-includes/classes/ANUBIS_Resolver.php`
- **Methods:**
  - **classifyOrphan($text)** — Returns classification: whether the input is an orphan (missing or invalid channel/thread/actor). May accept optional context (channel_id, thread_id, actor_id).
  - **resolveParent($text)** — Returns resolved parent: array with channel_id, dialog_thread_id, from_actor_id (or defaults 42, 1, 3).
  - **adoptIntoSeed($text, $actorId, $threadId, $channelId)** — Produces or executes adoption: insert into lupo_dialog_messages with explicit dialog_message_id, given actor/thread/channel, message_type='system', @now timestamps; then update lupo_dialog_channels.message_count.
- **Constraints:** PHP 5.3 compatible (no short arrays `[]`, no null coalescing `??`, no typed properties/return types in core). Uses PDO_DB only; table prefix from LUPO_TABLE_PREFIX. No schema changes.

---

## 2. Database Tables Used

| Table | Use |
|-------|-----|
| lupo_dialog_messages | Resolution: check existing messages; adoption: INSERT with explicit dialog_message_id. |
| lupo_dialog_threads | Resolution: validate dialog_thread_id and channel association. |
| lupo_dialog_channels | Resolution: validate channel_id; adoption: UPDATE message_count after insert. |
| lupo_actor_channels | Optional: verify actor on channel. |
| lupo_actors | Resolution: validate from_actor_id. |
| lupo_edges | HAS_CONTENT for content→channel resolution (optional for ANUBIS; used by FLIP path lookup). |

Schema source of truth: TOONs in `docs/toons/`. No new tables or columns are added by the ANUBIS program.

---

## 3. Integration with Seed

- Adoption into **seed** is done by adding an INSERT (and optionally ON DUPLICATE KEY UPDATE) to `database/migrations/seed_lupopedia.sql`, with explicit dialog_message_id, channel_id=42, dialog_thread_id=1, from_actor_id=3, message_type='system', and updating lupo_dialog_channels.message_count.
- The Python scanner and PHP resolver can **output** the adoption plan (IDs and values); the actual seed file edit is a one-time or tool-assisted change to the SQL file.
