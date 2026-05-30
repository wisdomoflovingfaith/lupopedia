---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/02_A_CHANNELS_DB_DESIGN.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/02_A_CHANNELS_DB_DESIGN.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/02-channels-db-design.toon
  atoms_toon: null
  transcript_jsonl: 0/development/channels-db-design
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: "0"
  thread_id: 
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_02_A
  title: "PRD 02 -- Channels Database Design"
  summary: "Database schema dialog_messages session endpoints visitors on lupo_sessions no livehelp_users runtime Crafty import mapping colors tasks."
---
# PRD 02 -- Channels Database Design

> **Split navigation (PRD 02 family):** This file is **database DDL and color data** only. Normative routing and projection: **[02_channels_db_overview.md](02_channels_db_overview.md)**. UI mockups, APIs, transport: **[02_channels_mockups_modules.md](02_channels_mockups_modules.md)**.

This extract from the PRD 02 family covers **normative DDL**, **color configuration data**, and **index** guidance for channel dialog surfaces. Routing and UI rules live in sibling files.

---

## Channel and Thread Model

### Channel

* A channel is a collection of threads organized by context
* Channels define the working context (e.g., development, captains_log)
* Channels are NOT conversations themselves
* Channels serve as containers for related work

### Thread

* A thread is a single conversation or task unit within a channel
* Threads contain messages and artifacts
* Threads are scoped to one channel
* Threads represent discrete units of work or discussion

### Relationship

* Channels contain threads
* Threads do NOT contain channels
* Channels are containers; threads are units
* Many threads can exist within one channel

### Cross-Channel Messaging (Conceptual)

* Actors may reference or send messages across channels
* This is a controlled behavior, not implicit routing
* Cross-channel messaging does NOT merge channels
* Channels remain isolated containers
* Cross-channel references are explicit, not automatic

---

## Collections (Data Model)

### Collections

* A collection is a grouping of content items
* Collections are independent of channels and threads
* Collections organize content for retrieval and reuse
* Collections do NOT define behavior

### Relationship to Other Entities

* Collections may include content from multiple threads
* Collections are not tied to a single channel
* Collections do NOT contain channels
* Channels do NOT contain collections

### Scope

* Collections are a structural grouping only
* Collections do NOT define:
  - UI behavior
  - Actor routing
  - Execution logic

---

## Thread Color Assignment Logic & Config

Color sequences are defined in `config/global_atoms.yaml`:

```yaml
chat_colors:
    backgrounds:
        - "fefdcd"
        - "cbcefe"
        - "caedbe"
        - "cccbba"
        - "aecddc"
        - "fafafb"
        - "faacaa"
        - "fbddef"
        - "cfaaef"
        - "aedcbd"
        - "bbffff"
        - "fedabf"
    text_operators:
        - "426446"
        - "224646"
        - "466286"
        - "828468"
        - "866482"
        - "484668"
        - "888286"
        - "224882"
        - "486882"
        - "824864"
        - "668266"
        - "444468"
    text_clients:
        - "040662"
        - "240462"
        - "462040"
        - "404062"
        - "604000"
        - "662640"
        - "242642"
        - "464406"
        - "404060"
        - "442662"
        - "442022"
        - "200220"
```

**Assignment Logic:**
- When a new thread is created, assign colors from the above sequences based on the thread count in the channel (modulo sequence length).
- All messages in a thread use the thread's assigned colors.

### Agent-Specific Color Override (Optional)

Thread-based colors are **primary**. Agent-based colors are an **optional override** that can be applied when per-agent visual distinction is needed within a thread:

| Agent | Color |
|-------|-------|
| CURSOR | Blue (#1E88E5) |
| CLAUDE | Purple (#8E44AD) |
| CASCADE | Green (#2ECC71) |
| WINDSURF | Yellow (#F1C40F) |
| LILITH/DeepSeek | Magenta (#E91E63) |
| COUNTERMEASURE | Orange (#FF9800) |
| CAPTAIN_WOLFIE | Brown/Gold (#8D6E63) |

**Agent Colors Table Schema:**
```sql
CREATE TABLE lupo_agent_colors (
    actor_id BIGINT NOT NULL PRIMARY KEY,
    background_color VARCHAR(7) NOT NULL,    -- Hex color, e.g., '#1E88E5'
    text_color VARCHAR(7) DEFAULT '#FFFFFF',
    last_used_ymdhis BIGINT NOT NULL
);
```

**Usage:**
- Agent colors are stored per `actor_id` and applied to all messages from that agent
- Default colors are assigned automatically when an agent first posts a message
- This system can be used alongside or instead of thread-based colors depending on UI requirements

### Visitor Row Background Color (Per Viewing Actor)

**Normative (extends thread and agent color rules above):** Visitor-party **row background** tint in an **operator's** channel projection is keyed by **(viewing_operator_actor_id, visitor_session_id, channel_id)** -- **not** assigned globally when the visitor first appears, **not** automatically shared across different logged-in operators, and **not** derived only from visitor **`lupo_sessions`** row creation time without an explicit accept.

**Per-operator color queue:** When an **actor** (human operator) **first loads** a channel in a given server session, their **per-channel visitor color allocation queue** for that channel starts **empty** until they take **Accept Chat** for a visitor.

**Accept Chat moment:** When the operator invokes **Accept Chat** for a visitor who has requested chat and may have **zero** prior **`lupo_dialog_messages`** rows for that pair, the product **MUST** insert an **accept** line into **`lupo_dialog_messages`** using **`from_actor_id`** = operator **`actor_id`** and **`to_session_id`** = visitor browser **`session_id`** (nullable columns per **Dual Endpoints** below; other endpoint columns per routing policy). At **successful insert commit** of that row, assign **one** background color for this **(viewing_operator_actor_id, visitor_session_id, channel_id)** binding by consuming the **next** value from that operator's **per-channel visitor color queue** (palette source: same **`config/global_atoms.yaml`** family as thread backgrounds, or a dedicated operator-only sequence documented beside this PRD).

**Reuse:** Subsequent messages in that visitor-involved thread that this operator sees in **their** projection **MUST** reuse the same assigned background for that binding until product policy clears it (for example visitor **`visitor_status`** transitions to **stopped**, operator ends chat, or explicit unbind).

**Precedence vs thread colors:** Where **thread-level** colors from **Assignment Logic** also apply, implementation **MUST** document one deterministic rule; **recommended** default: visitor-operator binding **wins** for visitor-party rows in **operator** view so mixed-participant projection stays legible.

**Persistence (TOON-aligned):** Store **`(viewing_operator_actor_id, channel_id, visitor_session_id) -> color_hex`** in an approved schema surface (examples: structured JSON on the operator's **`lupo_sessions.metadata`**, fields on **`lupo_dialog_threads`** when accept creates or binds a thread, or a small mapping table added only through install / approved migration). Do **not** invent columns in this PRD without reconciling **`install_new_lupopedia.sql`** and TOONs.

## Visitor Model (Crafty Syntax Compatibility + Lupopedia)

Normative product rules for **human live help** visitor rows (Crafty Syntax 3.7.5 heritage). Runtime storage MUST follow **`install_new_lupopedia.sql`** and TOONs; this section states **logical** mapping from legacy import sources (`livehelp_users`, `livehelp_visit_track`, operator department joins in the **Crafty import only**). Lupopedia runtime MUST NOT depend on `livehelp_*` table names after import.

### `lupo_sessions` as the visitor source of truth (no duplicate `livehelp_users`)

**Rejected pattern (Option 1):** Do **not** create or keep a full parallel **`livehelp_users`** table in Lupopedia runtime to satisfy legacy admin PHP. Duplication drifts from **`lupo_sessions`**, doubles writes, and breaks single-source doctrine.

**Recommended pattern (Option 2):** Treat **`{{prefix}}sessions`** as the **authoritative row** for anonymous live-help visitors, keyed by **`session_id`** (`VARCHAR(128)` PK in current install). Join **`{{prefix}}actors`** when the session row carries a real **`actor_id`** for authenticated personas. Legacy Crafty **`livehelp_users`** data is **transformed during import** into **`lupo_sessions`** (plus routing in **`lupo_dialog_messages`**), not kept as a second live table.

**Operator admin code** (patterns named after **`admin_users_xmlhttp.php`**, **`admin_chat_bot.php`**, **`admin_actions.php`**) MUST be updated to **SELECT / UPDATE `lupo_sessions`** (and actors) instead of querying **`livehelp_users`** in Lupopedia.

**Optional compatibility view:** A read-only SQL **VIEW** named like `livehelp_users_compat` that **projects** columns from **`lupo_sessions`** is allowed **only** as a staging bridge during migration; it is **not** storage and MUST be deleted once PHP reads sessions directly.

### Physical columns: `lupo_sessions` visitor migration (reference DDL)

The following **reference `ALTER TABLE`** block is the **operator migration shape** for **MySQL / MariaDB** after base **`install_new_lupopedia.sql`** and Crafty **`import_from_old_crafty_syntax.sql`** have been applied on a working copy. Replace literal **`lupo_`** with **`{{prefix}}`** (or `LUPO_TABLE_PREFIX`) in generators; the examples below keep **`lupo_`** to match common local installs.

**Portability (normative warning):** `ENUM`, **`ADD COLUMN IF NOT EXISTS`**, and **`CREATE INDEX IF NOT EXISTS`** are **MySQL-family** features. **PostgreSQL** (and strict portable DDL) MUST use **`VARCHAR` / `TINYINT` codes** instead of `ENUM`, and idempotent **`ADD COLUMN`** patterns supported on that engine. Reconcile the final canonical column list into **`install_new_lupopedia.sql`** and TOONs after the migration is proven.

**Coexistence with install `lupo_sessions.status`:** The base install already defines **`status` `VARCHAR(32)`** for generic session shell state. **`visitor_status`** below is the **Crafty-style live-help lifecycle** column (**browsing**, **invited**, **chatting**, **stopped**). Do **not** silently overload **`status`** for the same semantics unless a documented merge retires **`visitor_status`**.

```sql
ALTER TABLE lupo_sessions
  ADD COLUMN IF NOT EXISTS visitor_status ENUM('browsing','invited','chatting','stopped')
    NOT NULL DEFAULT 'browsing'
    AFTER is_active,

  ADD COLUMN IF NOT EXISTS livehelp_department BIGINT
    NOT NULL DEFAULT 0
    AFTER visitor_status,

  ADD COLUMN IF NOT EXISTS onchannel BIGINT
    NOT NULL DEFAULT 0
    AFTER livehelp_department,

  ADD COLUMN IF NOT EXISTS user_alert TINYINT
    NOT NULL DEFAULT 0
    AFTER onchannel,

  ADD COLUMN IF NOT EXISTS auto_invite ENUM('Y','N')
    NOT NULL DEFAULT 'N'
    AFTER user_alert,

  ADD COLUMN IF NOT EXISTS istyping INT
    NOT NULL DEFAULT 0
    AFTER auto_invite,

  ADD COLUMN IF NOT EXISTS livehelp_user_id BIGINT
    DEFAULT NULL
    AFTER istyping,

  ADD COLUMN IF NOT EXISTS livehelp_sessionid VARCHAR(128)
    DEFAULT NULL
    AFTER livehelp_user_id;
```

**Secondary indexes (operator discipline, not default install):** Do **not** add secondary indexes "just in case." Add them **only** when (a) the table has **meaningful row count** (operator rule of thumb: **thousands of rows and up**), and (b) **`EXPLAIN`**, traces, or documented **WHERE** / **JOIN** paths prove they pay for write and storage cost. Default **`install_new_lupopedia.sql`** does **not** ship extra visitor-list or polymorphic-session indexes beyond what already existed for **`lupo_sessions`** (for example **`sessions_idx_last_activity`** on **`last_activity_ymdhis`** -- do **not** duplicate that column in another index).

**Example optional shapes** (MySQL; illustrative only; uncomment and name when justified at scale; use `{{prefix}}` in generators):

```sql
-- CREATE INDEX idx_visitor_status_dept ON lupo_sessions (visitor_status, livehelp_department);
-- CREATE INDEX idx_onchannel ON lupo_sessions (onchannel);
-- CREATE INDEX idx_livehelp_sessionid ON lupo_sessions (livehelp_sessionid);
```

**Optional follow-on (not in the block above):** Add **`invited_by_actor_id` `BIGINT NULL`** on **`lupo_sessions`** when product needs explicit operator attribution on the row; importer and admin code must agree on the field.

**Rules (aligned to the migration columns):**

- Every visitor belongs to **exactly one** department at a time (**`livehelp_department`** single-valued; 0 means unscoped until product assigns).
- **Channel binding:** **`onchannel`** holds the current live-help **`channel_id`** when invited / chatting; **0** while not bound. **Browsing** keeps **`visitor_status = browsing`** until invite transitions.
- **Display name:** Prefer **`actor_name`**; if empty, UI shows **Anonymous** or policy fallback from **`ip_hash`**, never a blank label.
- **Lifecycle:** **`visitor_status`** drives the Crafty-style state machine; strings MUST match **02_channels_db_overview.md** Visitor status flow (**stopped** here covers offline / ended).
- **Crafty correlation:** **`livehelp_user_id`** and **`livehelp_sessionid`** preserve legacy keys for import reconciliation and admin parity; **`session_id`** (PK) remains the Lupopedia session authority.
- **`user_alert`**, **`auto_invite`**, **`istyping`** mirror classic Crafty operator / visitor UX flags.
- Active chat participants are tracked via **`lupo_dialog_messages`** **Dual Endpoints** and/or operator channel membership per DDL.
- **Single source:** Visitor **rows** stay on **`lupo_sessions`**; **do not** resurrect **`livehelp_users`** as storage.
- **AI preservation:** additive only (unchanged).

### Right sidebar visitor list scope (department union)

**Normative (Visitors navigation on `channels/index.php`):** The operator **Visitors** list in the **right sidebar** includes **every** **`lupo_sessions`** visitor row whose **`livehelp_department`** equals **any** **`department_id`** (or equivalent join key) present in **`{{prefix}}actor_departments`** for the **currently logged-in operator `actor_id`**. This is the **union** of all departments that actor belongs to -- **not** restricted to the **current channel** row on screen and **not** reducible to **`onchannel`** alone for **inclusion** in the list. (Product MAY still use **`onchannel`** for **emphasis**, sorting, or secondary filters.)

**Join surface:** Operator department membership is authoritative in **`{{prefix}}actor_departments`** (and **`{{prefix}}departments`** for display labels). Visitor department is **`lupo_sessions.livehelp_department`** per migration DDL.

**Cross-ref:** **02_channels_db_overview.md** Visitor Management; **02_channels_mockups_modules.md** Visitors Section.

### Message-count-based visitor intent (UI derivation)

**Normative derivation layer** for **sidebar status and actions**, alongside **`visitor_status`** on **`lupo_sessions`**. Implementation **MUST** document whether **`visitor_status`** is **source of truth** with message counts as **hints**, or counts **override display** until reconciled -- no silent contradiction.

Let **`N`** = count of **`{{prefix}}dialog_messages`** rows where **`from_session_id`** equals the visitor's **`session_id`** **OR** **`to_session_id`** equals that **`session_id`** (string match to **`lupo_sessions.session_id`**, per **Dual Endpoints**).

- **`N = 0`:** **Browsing** for sidebar intent -- no dialog traffic involving that visitor session yet (no chat request from messages).
- **`N = 1`:** **Wants to chat** / waiting for operator accept -- exactly one message row references that **`session_id`** on either endpoint (semantics of that row follow product routing rules; typically first visitor-originated or first operator offer).
- **`N >= 2`:** **Actively chatting** for sidebar intent -- conversation accepted and ongoing from message history.

**Cross-ref:** Projection and visibility unchanged (**02_channels_db_overview.md**); **Accept Chat** insert semantics (**Visitor Row Background Color** above).

**Sidebar UI decomposition (additive):** When **`N = 1`** is shown as **Invited** in a **Browsing** list while a separate **Wants Chat** row carries **Accept Chat**, implementation MUST document disambiguation (message direction, **`visitor_status`**, or thread phase) so auditors can reconcile counts with rows. Normative UI breakdown: **02_channels_mockups_modules.md** **Right sidebar chrome** (**E.a**--**E.c**).

**Crafty `livehelp_users` / visit track -> Lupopedia mapping (normative, column names match migration DDL):**

| Legacy Crafty column / concept | Lupopedia target |
|---|---|
| `user_id` | **`livehelp_user_id`** (plus PK **`session_id`**) |
| `sessionid` | **`livehelp_sessionid`** and / or **`session_id`** |
| `username` / display | **`actor_name`** |
| `ipaddress` | **`ip_hash`** |
| visitor lifecycle | **`visitor_status`** |
| `department` | **`livehelp_department`** |
| `onchannel` | **`onchannel`** |
| `lastaction` | **`last_activity_ymdhis`** |
| `istyping` | **`istyping`** |
| `user_alert` | **`user_alert`** |
| `auto_invite` | **`auto_invite`** (`Y` / `N`) |
| `camefrom` / referer | **`metadata`** or visit tables per import policy |

## Mixed Participant Projection Model (Actors + Visitors + Agents)

**Normative bridge (storage + UI):** This subsection ties **Visitor Model** and **`lupo_dialog_messages` Dual Endpoints** to the **hybrid channel** product shape: **live help** and **AI orchestration** share the **same** channel routing context. Routing, visibility, and feed semantics: **02_channels_db_overview.md** (Projection and Presence Model; **One-column projection feed**). Layout and operator controls: **02_channels_mockups_modules.md** (Unified Chat UI/UX; **Main Channel View (Projection)**; Visitors Section).

**Multi-thread interleaving:** A channel holds **multiple** dialog threads. The **main chat area** is **not** a single-thread-only view. It is a **time-sorted projection** that **merges** messages from **all threads** the viewer is authorized to see, ordered by **`created_ymdhis`** (with deterministic tie-breaks if product-defined). Each rendered row remains bound to its **thread** for **per-thread row colors** (see **Thread Color Assignment Logic & Config** above).

**Participant classes:**

- **Actors / agents (operators, IDE facets):** parties keyed by **`actor_id`** in **`lupo_actors`**. Endpoints use **`from_actor_id` / `to_actor_id`** when that side is actor-backed.
- **Visitors:** parties keyed by **`session_id`** on **`lupo_sessions`** (see Visitor Model; no parallel **`livehelp_users`** runtime table). Endpoints use **`from_session_id` / `to_session_id`** when that side is session-backed.

**Polymorphic routing (per message row):** Each row carries **both** actor and session columns on each half of the route. Per-side, **at most one** of **`{from_actor_id, from_session_id}`** and **at most one** of **`{to_actor_id, to_session_id}`** is authoritative for that party (**Dual Endpoints** below). Together this allows:

- **Pure actor / AI threads** -- orchestration, HERMES, tasks (typical lines keep session columns NULL).
- **Hybrid live-help threads** -- operator **<->** visitor, or operator **+** agent **+** visitor when routing policy places all parties on one thread.
- **Visibility:** each participant sees only rows where they are the **sender or recipient** on their endpoint type (overview **Default visibility rule**).

**Session row fields (recap):** **`visitor_status`**, **`livehelp_department`**, **`onchannel`**, **`user_alert`**, **`auto_invite`**, **`istyping`** -- see **Physical columns: `lupo_sessions` visitor migration** above. Message endpoints **`from_session_id`**, **`to_session_id`** -- see **Dual Endpoints** below.

## `lupo_dialog_messages`: Dual Endpoints (Actors and Visitor Sessions)

**Problem:** `from_actor_id` / `to_actor_id` alone assume every party is a row in **`lupo_actors`**. **Live-help visitors** are keyed off **`lupo_sessions`** (or equivalent) using a **string session id** (length per TOON / install SQL; **128** in this PRD family unless install narrows it).

**Normative shape (no discriminator string column):** Add **nullable** string columns on **`lupo_dialog_messages`** (names MUST match install SQL and TOON after regeneration):

- **`from_session_id`** `VARCHAR(128) NULL` -- placed **after** `from_actor_id` in the canonical CREATE TABLE column order in **`install_new_lupopedia.sql`**.
- **`to_session_id`** `VARCHAR(128) NULL` -- placed **after** `to_actor_id`.

**Endpoint rule (per side, origin and destination):** For each half of the route, **at most one** of `{from_actor_id, from_session_id}` and **at most one** of `{to_actor_id, to_session_id}` is authoritative for that party. Typical rows use **either** the actor column **or** the session column on a given side, not both, unless a later PRD documents an explicit audit / bridge row.

**Operator migration reference (MySQL):** After base install, apply the additive columns with idempotent DDL (same portability caveats as **`lupo_sessions`** above: **`IF NOT EXISTS`** is MySQL-family).

```sql
ALTER TABLE lupo_dialog_messages
  ADD COLUMN IF NOT EXISTS from_session_id VARCHAR(128)
    DEFAULT NULL
    AFTER from_actor_id,

  ADD COLUMN IF NOT EXISTS to_session_id VARCHAR(128)
    DEFAULT NULL
    AFTER to_actor_id;
```

**Indexes on session endpoint columns (same discipline as visitors):** Default install does **not** add dedicated indexes on **`from_session_id` / `to_session_id`**. Add them in a **measured** migration only at proven cardinality and for proven filter or join paths.

**Example optional shapes** (commented; `IF NOT EXISTS` acceptable on MySQL-family one-off migrations):

```sql
-- CREATE INDEX IF NOT EXISTS idx_lupo_dialog_messages_from_session ON lupo_dialog_messages (from_session_id);
-- CREATE INDEX IF NOT EXISTS idx_lupo_dialog_messages_to_session ON lupo_dialog_messages (to_session_id);
-- CREATE INDEX IF NOT EXISTS idx_lupo_dialog_messages_mixed_from ON lupo_dialog_messages (from_actor_id, from_session_id);
-- CREATE INDEX IF NOT EXISTS idx_lupo_dialog_messages_mixed_to ON lupo_dialog_messages (to_actor_id, to_session_id);
```

(Use `{{prefix}}` or `LUPO_TABLE_PREFIX` in shipped SQL generators when uncommenting.)

**Routing matrix (normative examples):**

| Case | `from_actor_id` | `from_session_id` | `to_actor_id` | `to_session_id` |
|---|---|---|---|---|
| Actor to actor | set | NULL | set | NULL |
| Operator to visitor | set | NULL | NULL | set |
| Visitor to operator | NULL | set | set | NULL |
| Visitor to visitor (rare) | NULL | set | NULL | set |

Existing **actor-only** traffic keeps **`from_session_id` / `to_session_id` NULL**; HERMES, tasks, and orchestration layers treat unchanged rows as today.

**Single-install doctrine:** Fold these columns and indexes into **`install_new_lupopedia.sql`** as part of the **`lupo_dialog_messages`** `CREATE TABLE` (or immediate follow-on `CREATE INDEX` in the same install file where the table already exists in your pipeline). **Do not** treat a standalone **`ALTER TABLE`** narrative as the canonical 4.0.x ship story; operators assume **fresh install** per root release doctrine.

**Importer:** **`import_from_old_crafty_syntax.sql`** (and related Crafty import paths) MUST map legacy **`livehelp_messages`** party columns into **`from_actor_id` / `to_actor_id` and/or `from_session_id` / `to_session_id`** so historical operator-visitor lines survive import.

**Runtime PHP:** Operator and visitor surfaces (for example legacy **`admin_users_xmlhttp.php`**, **`admin_chat_bot.php`**, **`admin_actions.php`** class flows) MUST read and write the correct session columns when the remote party is a visitor.

## Recent Files Browser/Table

### Table: lupo_dialog_recent_files

**Purpose**: Tracks what agents and humans have accessed or written to files.

**Description**: 
- Monitors file access patterns across the repository
- Enables a sidebar in the chat UI showing recently accessed files per actor
- Helps users understand what files are being actively worked on
- Provides quick access to files that are part of ongoing conversations

**Key Fields**:
- `file_path_from_root`: Which file was accessed
- `accessed_by_actor_id`: Who accessed the file (agent or human)
- `accessed_ymdhis`: When the file was accessed
- `file_size`: Size of the file at access time

```sql
CREATE TABLE lupo_dialog_recent_files (
    recent_file_id BIGINT NOT NULL PRIMARY KEY,
    file_path_from_root VARCHAR(512) NOT NULL,
    content_id BIGINT NULL,                        -- Links to lupo_contents if imported
    accessed_by_actor_id BIGINT NOT NULL,
    accessed_ymdhis BIGINT NOT NULL,
    file_size BIGINT DEFAULT 0,
    is_deleted TINYINT DEFAULT 0,
    INDEX idx_accessed (accessed_ymdhis DESC),
    INDEX idx_actor (accessed_by_actor_id),
    UNIQUE KEY uk_actor_file (accessed_by_actor_id, file_path_from_root(255))
);
```

**Purpose:**
- Enables a sidebar in the chat UI showing recently accessed files per actor.

### File Tracking Hooks

Files are tracked from multiple sources to populate the recent files sidebar:

```php
// includes/track_file_access.php
// Called whenever a file is accessed or modified

function track_file_access($file_path_from_root, $actor_id) {
    $db = DatabaseFactory::getConnection();
    $now = timestamp_ymdhis::now();
    
    // Upsert into recent_files
    $db->query(
        "INSERT INTO lupo_dialog_recent_files (file_path_from_root, accessed_by_actor_id, accessed_ymdhis, file_size)
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE 
         accessed_ymdhis = VALUES(accessed_ymdhis),
         file_size = VALUES(file_size)",
        [$file_path_from_root, $actor_id, $now, filesize($file_path_from_root)]
    );
}
```

**File Tracking Sources:**
- Agent file writes (via `file_put_contents` wrapper)
- Manual file opens via web interface
- Database imports (`lupo_contents` rows)
- IDE file edits through the agent wrapper
### Database Schema

**Table roles:** **`lupo_dialog_pending_tasks`** is the **runtime queue** (agent polling, SEND TASK handoffs, short-lived operational state). **`{{prefix}}tasks`** is **long-term / workflow tracking** for broader task metadata across agents and humans -- not interchangeable with the pending queue; do not assume one replaces the other.

**Primary keys:** DDL below uses `BIGINT NOT NULL PRIMARY KEY` on id columns. **`task_id` / `recent_file_id` MUST** be assigned via a **deterministic application-layer allocator** (for example **`IdGenerator`** per root doctrine); **do not** rely on database **`AUTO_INCREMENT`**.

**IDE agent task queue** (`lupo_dialog_pending_tasks`):
```sql
CREATE TABLE lupo_dialog_pending_tasks (
    task_id BIGINT NOT NULL PRIMARY KEY,
    assigned_to_actor_id BIGINT NOT NULL,        -- which agent should do this
    assigned_by_actor_id BIGINT NOT NULL,        -- CAPTAIN_WOLFIE (actor_id 1)
    task_description TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    result_summary TEXT NULL,
    created_ymdhis BIGINT NOT NULL,
    started_ymdhis BIGINT NULL,
    completed_ymdhis BIGINT NULL,
    INDEX idx_assigned_to (assigned_to_actor_id, status),
    INDEX idx_created (created_ymdhis)
);
```

**General task tracker** (full workflow, all agents):
```sql
CREATE TABLE {{prefix}}tasks (
    task_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    assigned_to VARCHAR(64) NULL,
    priority ENUM('HIGH', 'MED', 'LOW') NOT NULL DEFAULT 'MED',
    status ENUM('TODO', 'IN_PROGRESS', 'DONE', 'BLOCKED', 'CANCELLED') NOT NULL DEFAULT 'TODO',
    dependencies TEXT NULL,
    created_by VARCHAR(64) NOT NULL,
    created_ymdhis BIGINT NOT NULL,
    started_ymdhis BIGINT NULL,
    completed_ymdhis BIGINT NULL,
    notes TEXT NULL,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT NULL,
    PRIMARY KEY (task_id)
);
```
**Indexes (illustrative; align with measured need):** Prefer the **secondary index discipline** above. The shapes below are **examples** for high-churn message tables at scale; they are **not** a mandate to create every index on small or cold tables.

```sql
-- Messages table (examples only; add when justified)
CREATE INDEX idx_messages_thread_time ON lupo_dialog_messages (dialog_thread_id, created_ymdhis DESC);
CREATE INDEX idx_messages_actor_time ON lupo_dialog_messages (from_actor_id, created_ymdhis DESC);

-- Threads table  
CREATE INDEX idx_threads_channel ON lupo_dialog_threads (channel_id, thread_key);
CREATE INDEX idx_threads_created ON lupo_dialog_threads (created_ymdhis DESC);

-- Recent files table
CREATE INDEX idx_recent_actor_time ON lupo_dialog_recent_files (accessed_by_actor_id, accessed_ymdhis DESC);
CREATE INDEX idx_recent_time ON lupo_dialog_recent_files (accessed_ymdhis DESC);

-- Tasks table
CREATE INDEX idx_tasks_assigned ON lupo_dialog_pending_tasks (assigned_to_actor_id, status);
CREATE INDEX idx_tasks_created ON lupo_dialog_pending_tasks (created_ymdhis DESC);
```

## Common Predictive Text / AI Misconceptions (Memory Guardrails)

Frequent AI hallucinations this PRD counters:
- "Main chat view is a single thread" -> FALSE. It is a time-sorted projection mixing multiple threads (color-coded).
- "All participants live in lupo_actors" -> FALSE. Operators/agents = actors; visitors = lupo_sessions only.
- "said_from/said_to only reference actors" -> FALSE. Messages are polymorphic: from_actor_id + from_session_id, to_actor_id + to_session_id.
- "Visitor management is separate from orchestration" -> FALSE. Visitors share channels/threads with actors and agents.
- "Old Crafty 1:1 model still applies" -> FALSE. New model supports multiple humans + multiple visitors + multiple agents on one channel.
- "Projection = thread" -> FALSE. Projection = filtered time-sorted view across many threads.

These guardrails are binding for all future code and documentation in this scope.

### Strengthened guardrails (additive)

Frequent AI hallucinations this PRD counters (fuller phrasing):
- "Main chat view is a single thread" -> FALSE. It is a time-sorted projection mixing multiple threads with per-thread color coding.
- "All participants live in lupo_actors" -> FALSE. Operators/agents = actors; visitors = lupo_sessions only.
- "said_from/said_to only reference actors" -> FALSE. Messages are polymorphic: from_actor_id + from_session_id, to_actor_id + to_session_id.
- "Visitor management is separate from orchestration" -> FALSE. Visitors share channels/threads with actors and agents.
- "Old Crafty Syntax 1:1 model still applies" -> FALSE. New model supports multiple humans + multiple visitors + multiple agents on one channel.
- "Projection = thread" -> FALSE. Projection = filtered time-sorted view across many threads.

**Endpoint visibility:** Projection visibility = endpoint match only, not full channel membership granting all stored rows.

**Concurrency:** Visitor management and AI orchestration run concurrently on the same channels.

These guardrails are binding for all future code generation and documentation in this PRD scope.
