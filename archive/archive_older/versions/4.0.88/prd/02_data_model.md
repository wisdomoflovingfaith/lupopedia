---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "prd"
  file_path_from_root: "docs/versions/4.0.88/prd/02_data_model.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.88/prd/02_data_model.md"
  questions_toon: null
  system_version: "4.0.88"
  channel_id: 42
  actor_id: 105
  actor_name: "cascade"
  faucet_name: "cascade"
  delegation_chain: "cascade:root"
  artifact_type: "prd"
  artifact_kind: "data_model"
  purpose: "Database tables, data flow, and storage architecture for the semantic monitoring widget"
  tags: ["4.0.88", "prd", "data_model", "lupo_visits", "lupo_contents", "lupo_edges", "federation"]

lupopedia.footer:
  version: "4.0.88"
   approval_status: "rejected"
   approval_target_version: "4.1.0"
   approval_status_utc: "20260327103238"
   approval_status_by: "Cursor IDE Agent (Lead Orchestration)"
   approval_status_by_actor_id: 102
  last_verified: "20260326"
  last_verified_by: "cascade"
  orchestrator: "wolfie"
  next_action:
    - "Verify column definitions against install SQL"
    - "Create migration SQL for new/modified tables"
    - "Add engagement tracking tables to data model"
    - "Ensure Crafty Syntax table mapping compatibility"
    - "Follow rules/root/ database design principles"
---
# file: PRD 02 — Data Model

# PRD 02: Semantic Monitoring Data Model

**Status:** Draft
**Version:** 4.0.88
**Author:** CASCADE (actor_id 105)

**4.1.0 Scope Status:** Rejected from current 4.1.0 release scope; retained as historical feature planning only.

---

## 1. Database Design Principles (MANDATORY)

All database design MUST follow the rules in `rules/root/`:

### No Foreign Keys Doctrine
- **NO FOREIGN KEY constraints** - All relationships enforced in application code
- **NO REFERENCES clauses** - Manual referential integrity
- **NO TRIGGER definitions** - All logic in PHP code
- **NO stored procedures/functions** - Application-side logic only
- **NO DEFAULT CURRENT_TIMESTAMP** - Explicit timestamps in PHP

### Primary Key Naming Doctrine
- **Primary keys**: `<singular_table_name>_id` format
- **NEVER use `id`** - Always explicit naming
- **Reference keys**: Use exact same name as referenced PK
- **Examples**: `actor_id`, `content_id`, `visit_id` (not `id`)

### Timestamp Doctrine
- **Format**: BIGINT `YYYYMMDDHHIISS` (UTC)
- **Set in PHP**: `gmdate('YmdHis')`
- **No database automation** - All timestamps explicit
- **Timezone**: Always UTC

---

## 2. Data Flow Diagram

```
  Visitor's browser (example.com/products/page-a)
       |
       |  <script src="example.com/lupopedia/livehelp_js.php?department=1">
       |
       ▼
  livehelp_js.php  ------------------------------------------------------
       |  Generates JS with:                                             
       |  - WEBPATH = /lupopedia/                                        
       |  - department config                                            
       |  - visitor session ID (cslhVISITOR)                             
       |  - operator status check logic                                  
       ▼                                                                 
  Generated JavaScript (runs in visitor's browser)                       
       |                                                                 
       |  Captures: document.location, document.title, document.referrer 
       |  Sends to: image.php?what=userstat&page=...&referer=...&title=..
       |                                                                 
       ▼                                                                 
  image.php (in lupopedia/ subfolder)                                    
       |                                                                 
       +--► [EXISTING] Real-time operator status (online/offline image)  
       +--► [EXISTING] Session tracking (lupo_sessions)                  
       |                                                                 
       +--► [NEW] Auto-register page in lupo_contents                    
       +--► [NEW] Record visit in lupo_visits                            
       +--► [NEW] Record navigation edge in lupo_edges                   
       +--► [NEW] Update view counts in lupo_semantic_content_views      
```

---

## 2. Tables Involved

### 2.1 Existing Tables (already in install SQL)

#### lupo_contents
**Role:** Content registry — every monitored page becomes a content entity.

Relevant columns for widget discovery:
- `content_id` (BIGINT PK) — application-supplied
- `content_type` (VARCHAR 64) — set to `'page'` for widget-discovered pages
- `content_key` (VARCHAR 255) — normalized URL path (e.g., `products/widget-a`)
- `title` (VARCHAR 255) — page title from JS
- `federation_node_id` (BIGINT) — scoped to the current install
- `channel_id` (BIGINT) — 0 for system/auto-discovered
- `created_by_actor_id` (BIGINT) — 0 for system
- `created_ymdhis` (BIGINT) — UTC timestamp
- `updated_ymdhis` (BIGINT) — UTC timestamp
- `is_deleted` (TINYINT) — soft delete
- `metadata_json` (TEXT) — `{"source": "widget_discovery", "first_seen_ymdhis": ...}`

**Auto-registration logic:**
```sql
-- Check if page exists for this federation node
SELECT content_id FROM lupo_contents
WHERE content_key = :path AND federation_node_id = :node_id AND is_deleted = 0
LIMIT 1
```
If not found → INSERT with next available `content_id` from registry or application logic.

#### lupo_edges
**Role:** Navigation graph — page-to-page transitions.

Relevant columns:
- `edge_id` (BIGINT PK)
- `source_type` (VARCHAR 64) — `'content'`
- `source_id` (BIGINT) — content_id of referrer page
- `target_type` (VARCHAR 64) — `'content'`
- `target_id` (BIGINT) — content_id of current page
- `edge_type` (VARCHAR 64) — `'navigated_to'`
- `edge_category` (VARCHAR 100) — `'navigation'`
- `weight` (INT or DECIMAL) — incremented per occurrence
- `federation_node_id` (BIGINT) — scoped
- `created_ymdhis` (BIGINT)
- `updated_ymdhis` (BIGINT)
- `is_deleted` (TINYINT)

**Edge upsert logic:**
```sql
-- Check if edge exists
SELECT edge_id, weight FROM lupo_edges
WHERE source_type = 'content' AND source_id = :ref_content_id
  AND target_type = 'content' AND target_id = :page_content_id
  AND edge_type = 'navigated_to' AND federation_node_id = :node_id
  AND is_deleted = 0
LIMIT 1
```
If found → UPDATE weight = weight + 1, updated_ymdhis = now.
If not found → INSERT with weight = 1.

#### lupo_federation_nodes
**Role:** Node identity — which Lupopedia installation this data belongs to.

Used to resolve `federation_node_id` for scoping. Default = 1 for local install.

#### lupo_sessions
**Role:** Visitor session tracking (already used by current image.php).

The `cslhVISITOR` session ID maps to rows here. The widget leverages existing session logic.

---

### 2.2 Existing Tables (may need columns or new usage)

#### lupo_visits
**Role:** Individual visit records.

This table exists in CSV fallback (`database/lupopedia/csv/lupo_visits.csv`) and may exist in install SQL. If not in install SQL, it needs to be added.

Required columns:
- `visit_id` (BIGINT PK) — application-supplied
- `visitor_session_id` (VARCHAR 64) — cslhVISITOR value
- `content_id` (BIGINT) — resolved lupo_contents row for this page
- `page_path` (VARCHAR 512) — raw path as received
- `page_title` (VARCHAR 255) — title at time of visit
- `referrer_path` (VARCHAR 512) — raw referrer path
- `referrer_content_id` (BIGINT) — resolved lupo_contents row for referrer (0 if external/unknown)
- `federation_node_id` (BIGINT) — scoped
- `department_id` (BIGINT) — department from widget params
- `ip_hash` (VARCHAR 64) — sha256(ip . daily_salt)
- `user_agent_hash` (VARCHAR 64) — sha256(ua)
- `is_first_visit` (TINYINT) — 1 if this is the first recorded visit for this session
- `created_ymdhis` (BIGINT) — UTC timestamp
- `is_deleted` (TINYINT DEFAULT 0) — soft delete

Indexes:
- `lupo_visits_idx_session` on `(visitor_session_id)`
- `lupo_visits_idx_content` on `(content_id, federation_node_id)`
- `lupo_visits_idx_created` on `(created_ymdhis)`
- `lupo_visits_idx_federation` on `(federation_node_id)`

#### lupo_semantic_content_views
**Role:** Aggregated view statistics per content item.

This table exists in CSV fallback. Required columns:
- `semantic_content_view_id` (BIGINT PK)
- `content_id` (BIGINT) — references lupo_contents
- `federation_node_id` (BIGINT)
- `view_count` (BIGINT DEFAULT 0)
- `unique_visitor_count` (BIGINT DEFAULT 0)
- `last_viewed_ymdhis` (BIGINT)
- `period_start_ymdhis` (BIGINT) — for daily aggregation (optional)
- `period_end_ymdhis` (BIGINT) — for daily aggregation (optional)
- `created_ymdhis` (BIGINT)
- `updated_ymdhis` (BIGINT)
- `is_deleted` (TINYINT DEFAULT 0)

Indexes:
- `lupo_scv_idx_content_node` on `(content_id, federation_node_id)`

---

### 2.3 Engagement Tracking Tables (NEW for 4.0.88)

#### lupo_engagement_signals
**Role:** Track all engagement interactions (likes, shares, ratings, etc.).

Required columns:
- `engagement_signal_id` (BIGINT PK) — application-supplied
- `content_id` (BIGINT) — references lupo_contents
- `actor_id` (BIGINT) — user who performed the action (0 for anonymous)
- `signal_type` (VARCHAR 32) — 'like', 'share', 'rating', 'bookmark', etc.
- `signal_value` (DECIMAL 5,2) — numeric value for ratings (1-5) or counts
- `metadata_json` (JSON) — additional signal data (share platform, etc.)
- `visitor_session_id` (VARCHAR 64) — for anonymous engagement tracking
- `federation_node_id` (BIGINT) — scoped
- `created_ymdhis` (BIGINT) — UTC timestamp
- `is_deleted` (TINYINT DEFAULT 0) — soft delete

Indexes:
- `lupo_es_idx_content_type` on `(content_id, signal_type)`
- `lupo_es_idx_actor` on `(actor_id)`
- `lupo_es_idx_created` on `(created_ymdhis)`

#### lupo_comments
**Role:** Threaded comment system for content engagement.

Required columns:
- `comment_id` (BIGINT PK) — application-supplied
- `content_id` (BIGINT) — references lupo_contents
- `parent_comment_id` (BIGINT) — for threaded replies (0 for top-level)
- `actor_id` (BIGINT) — comment author (0 for anonymous)
- `visitor_session_id` (VARCHAR 64) — for anonymous comments
- `comment_text` (TEXT) — the comment content
- `comment_html` (TEXT) — sanitized HTML version
- `status` (VARCHAR 16) — 'published', 'pending', 'spam', 'deleted'
- `like_count` (INT DEFAULT 0) — cached like count
- `federation_node_id` (BIGINT) — scoped
- `created_ymdhis` (BIGINT) — UTC timestamp
- `updated_ymdhis` (BIGINT) — UTC timestamp
- `is_deleted` (TINYINT DEFAULT 0) — soft delete

Indexes:
- `lupo_comments_idx_content` on `(content_id, status, created_ymdhis)`
- `lupo_comments_idx_parent` on `(parent_comment_id)`
- `lupo_comments_idx_actor` on `(actor_id)`

#### lupo_hashtags
**Role:** Hashtag registry and usage tracking.

Required columns:
- `hashtag_id` (BIGINT PK) — application-supplied
- `hashtag_text` (VARCHAR 100) — the hashtag without # symbol
- `usage_count` (BIGINT DEFAULT 0) — total usage across all content
- `federation_node_id` (BIGINT) — scoped
- `created_ymdhis` (BIGINT) — UTC timestamp
- `is_deleted` (TINYINT DEFAULT 0) — soft delete

Indexes:
- `lupo_hashtags_idx_text` on `(hashtag_text, federation_node_id)`
- `lupo_hashtags_idx_usage` on `(usage_count DESC)`

#### lupo_content_tags
**Role:** Many-to-many relationship between content and hashtags.

Required columns:
- `content_tag_id` (BIGINT PK) — application-supplied
- `content_id` (BIGINT) — references lupo_contents
- `hashtag_id` (BIGINT) — references lupo_hashtags
- `tag_context` (VARCHAR 32) — 'auto', 'manual', 'imported'
- `relevance_score` (DECIMAL 3,2) — 0-1 relevance score
- `federation_node_id` (BIGINT) — scoped
- `created_ymdhis` (BIGINT) — UTC timestamp
- `is_deleted` (TINYINT DEFAULT 0) — soft delete

Indexes:
- `lupo_ct_idx_content` on `(content_id)`
- `lupo_ct_idx_hashtag` on `(hashtag_id)`

#### lupo_user_bookmarks
**Role:** User-specific content bookmarking/favorites.

Required columns:
- `bookmark_id` (BIGINT PK) — application-supplied
- `content_id` (BIGINT) — references lupo_contents
- `actor_id` (BIGINT) — user who bookmarked (0 for anonymous)
- `visitor_session_id` (VARCHAR 64) — for anonymous bookmarks
- `bookmark_folder` (VARCHAR 100) — optional folder/category
- `notes` (TEXT) — optional user notes
- `federation_node_id` (BIGINT) — scoped
- `created_ymdhis` (BIGINT) — UTC timestamp
- `is_deleted` (TINYINT DEFAULT 0) — soft delete

Indexes:
- `lupo_ub_idx_user` on `(actor_id, created_ymdhis)`
- `lupo_ub_idx_content` on `(content_id)`

---

## 3. Data Processing in image.php

### 3.1 Semantic Processing Flow (new code path)

When `image.php` receives a request with `what=userstat` or `what=getstate`:

```
1. Parse and sanitize: page, title, referer from GET params
2. Decode dot encoding: replace "--dot--" with "."
3. Determine federation_node_id (from config, default 1)
4. Extract page_path from page URL (strip domain, normalize)
5. Extract referrer_path from referer URL

6. AUTO-REGISTER PAGE:
   a. SELECT content_id FROM lupo_contents WHERE content_key = :page_path
      AND federation_node_id = :node AND is_deleted = 0
   b. If not found → INSERT into lupo_contents
   c. Store page_content_id

7. AUTO-REGISTER REFERRER (if same domain):
   a. Same as step 6 but for referrer_path
   b. Store referrer_content_id

8. RECORD VISIT:
   a. INSERT into lupo_visits with all fields

9. RECORD NAVIGATION EDGE (if referrer is same domain):
   a. SELECT edge from lupo_edges matching source→target
   b. If found → UPDATE weight++
   c. If not found → INSERT new edge

10. UPDATE VIEW COUNTS:
    a. SELECT from lupo_semantic_content_views for page_content_id
    b. If found → UPDATE view_count++, last_viewed_ymdhis = now
    c. If not found → INSERT with view_count = 1

11. Continue with existing image.php logic (operator status, control signals)
```

### 3.2 Performance Considerations

- Steps 6-10 add database writes to every page view ping
- Use a single transaction for steps 6-10 where PDO supports it
- Consider rate limiting: only process semantic data once per session per page per 60 seconds (deduplicate rapid pings)
- The existing `csTimeout` ping (every 10s) should NOT re-record the same page visit — only the first ping for a new page should create a visit record
- Distinguish "new page view" from "still here" pings using `pageid` param (already exists — random per page load)

### 3.3 Content ID Allocation

`lupo_contents.content_id` is not AUTO_INCREMENT (per reserved-id doctrine). For widget-discovered content:
- Use a content_id allocator: `SELECT MAX(content_id) + 1 FROM lupo_contents WHERE federation_node_id = :node`
- Or maintain a sequence in `lupo_registry` for the `content` entity type
- The allocator must handle concurrent writes safely (use INSERT with explicit ID and catch duplicate errors)

---

## 4. Domain Scoping Logic

The widget JS sends the full page URL. The referrer may be from the same domain or external. Semantic data should distinguish:

- **Same-domain navigation:** Both page and referrer share the same domain → record navigation edge
- **External entry:** Referrer domain differs → record visit but no internal navigation edge; optionally record referrer as external source
- **Direct entry:** No referrer → record visit with referrer_content_id = 0
- **Lupopedia subfolder:** Pages within `/lupopedia/` are the application itself, not monitored content. Optionally exclude from content registry or tag as `content_type = 'application'`

Domain detection: compare the domain portion of page URL and referrer URL. The current install domain is known from `$_SERVER['HTTP_HOST']` or configuration.

---

## 5. Migration Requirements

If `lupo_visits` or `lupo_semantic_content_views` do not exist in `install_new_lupopedia.sql`:

1. Add CREATE TABLE statements to install SQL (canonical schema source)
2. Create a one-time migration: `database/lupopedia/mysql/migrations/migration_20260326_semantic_monitoring_v4_0_88.sql`
3. Run through `php scripts/safe-migrate.php` (DB009 doctrine)
4. Generate TOON files: `python scripts/generate_toon_from_sql.py`

If tables exist but need new columns, use ALTER TABLE in the migration with idempotent error handling (skip "Duplicate column" errors per existing doctrine).
