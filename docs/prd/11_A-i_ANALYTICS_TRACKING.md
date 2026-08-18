---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/11_A-i_ANALYTICS_TRACKING.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/11_A-i_ANALYTICS_TRACKING.md
  status: active
  when_updated: '20260817092400'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/11_analytics_tracking.toon
  atoms_toon: null
  transcript_jsonl: 0/development/11-analytics-tracking
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 11_A-i_00_A-i_FORBIDDEN_AND_WHY_11_A_ANALYTICS_TRACKING
  title: 'PRD: Analytics, Tracking, Visits, and Performance Monitoring'
  summary: null
---
# PRD: Analytics, Tracking, Visits, and Performance Monitoring

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Overview

**Namespace Purpose:** Provides comprehensive analytics, user tracking, visit monitoring, and performance metrics. This namespace enables data-driven decision making and system optimization.

**Primary Actors:** 
- Analytics administrators (via lupo_visits)
- Performance monitors (via lupo_unified_log)
- Campaign managers (via lupo_analytics_campaign_vars)
- Audit loggers (via lupo_audit_log)
- Path analysts (via lupo_paths_summary)

**Constitutional Compliance:** All tables in this namespace follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

**Implementers:** **PRD 33** ????4.1 ???????? read **`docs/database/lupopedia/tables/migrations/`** + **`install_new_lupopedia.sql`** before any SQL/code; **no guessed column names**. Crafty **`'Y'`/`'N'`** toggles for tracking map to Lupopedia **`TINYINT`** (**`1`**/**`0`**) per **PRD 33** ????4.2.

## Crafty Syntax 3.7.5 reference (`craftysyntax-reference/`)

In-repo reference PHP (repository root **`craftysyntax-reference/`**) defines the **behavioral baseline** for analytics parity (see **PRD 33** ????3).

### Operator **data** UI (`data.php`)

| Crafty `tab` | File | Role |
|-------------|------|------|
| 3 | `data_visits.php` | Visit aggregates ???????? **Top Urls** / **Domain Tree**, `livehelp_visits_monthly`, department filter. |
| 4 | `data_paths.php` | Path funnels ???????? **All Visit Paths** / **First Visit Paths**, `livehelp_paths_monthly`, `livehelp_paths_firsts`, drill-down via `visit_recno` / `exit_recno`. Requires `CSLH_Config['tracking']=='Y'`. |
| 2 | `data_referers.php` | Referrer reports. |

### Ingestion

- **`image.php`** (and related **`what=`** commands) writes **per-page** rows to **`livehelp_visit_track`** (`sessionid`, `location`, `page`/`pageid`, `title`, `whendone`, `referrer`), keyed by **`identity['SESSIONID']`** from **`functions.php` `identity()`**.
- Embeds (**`livehelp_js.php`**) drive polling/requests that keep **`SESSIONID`** stable per browser.

**Product Lineage Note:** Crafty Syntax, Sales Syntax, White Label Syntax, and Black Label Syntax are branding forks of the same author and the same underlying system (open-source, commercial, reseller, enterprise). They are one family, not separate products. They converge into Lupopedia OS. `livehelp_js.php` / `image.php` embeds from any of those names belong to this lineage.

### Identity and IP (maps to Lupopedia session/visit rows)

- **`get_ipaddress()`** ???????? Proxy/CDN header chain, public-IP preference, comma-separated **X-Forwarded-For** handling.
- **`detectID()`** ???????? Session id from untrusted input, GET, POST, cookie; optional class-C **`matchip`** check; optional **cookieless** lookup `WHERE identity=??????? AND cookied='N'`.
- **`get_identitystring()`** ???????? Class-C IP + session **cookie name**; not the unique token. **Two browsers on one machine** ???????? **two SESSIONIDs** (separate cookies or explicit ids); **IDENTITY** alone may collide.

### Visitor embed fingerprint ???????? Lupopedia implementation options (design; not required to be live in 4.0.x)

**Why:** On **third-party origins**, browsers often block **cross-site cookies**. A script loaded from Lupopedia cannot assume a stable first-party **`lupo_sessions`** cookie on the **embedder????????s** domain. Crafty mitigated this with **`identity`**, class-C **`matchip`**, **`image.php`**-driven hits, and optional cookieless rows ???????? see **PRD 33** ????3.3 and **`craftysyntax-reference/functions.php`**. The semantic navbar / monitoring embed path (**PRD 21**, **PRD 28**, **`SEMANTIC_MONITORING_DOCTRINE.md`**) needs an explicit **product decision** before code assumes continuity.

**Building blocks (straightforward to implement in application code; schema may use existing columns first):**

1. **Real client IP** ???????? Reuse the same **ordered proxy/CDN header walk** and public-IP preference as Crafty **`get_ipaddress()`** and Lupopedia **`CloudflareRequestHandler`** / **`ajax.php`** client-IP logic (do not trust raw **`REMOTE_ADDR`** behind proxies).
2. **Class-C (IPv4)** ???????? Take the **first three octets** of the resolved public IPv4 (e.g. `203.0.113.*` ???????? `203.0.113`). **IPv6:** class-C has no direct analog; options include: store a **hashed /64 prefix**, full-address **hash only**, or **skip** class-C for v6 and rely on other factors (must be documented per chosen policy).
3. **User-Agent** ???????? Append or hash a **normalized** `User-Agent` (length-capped). Prefer **HMAC or truncate+hash** for storage/display parity with privacy posture; **`lupo_sessions.ua_hash`** already exists for a similar purpose.
4. **Third factor (choose one or combine)** ???????? Examples: normalized **`Accept-Language`**; **`Sec-CH-UA`** / **`Sec-CH-UA-Platform`** when available; **embedder-supplied** stable id (query param from the page template); **client-generated** id in **`localStorage`** passed back on each beacon (renewable, not third-party-cookie); **content slug / `content_id`** from the embed context (ties bar to page, not to human).

**Where to persist (options ???????? no new column required for a spike):**

- **`lupo_sessions`**: use **`ip_hash`**, **`ua_hash`**, and **`metadata` JSON** (e.g. `embed_identity_signature`, `client_fingerprint_version`) until a dedicated column is justified in **`install_new_lupopedia.sql`**.
- **`lupo_visits`**: **`transition_metadata`** JSON can carry `fingerprint_generation` / `source` for audit; avoid duplicating raw PII in clear text.

**Limitations (must be documented to operators):**

- **Collisions:** Many users can share one **class-C** (carrier NAT, corporate egress). **Class-C + UA** is a **coarse** key, not a unique human identifier.
- **VPN / mobile:** IP class changes session-to-session; ???????same visitor??????? becomes probabilistic.
- **Ethics / consent:** Fingerprinting for analytics touches **SILENT_HARVEST** and operator-facing disclosure; align with **PRD 11** / **PRD 34** narrative before enabling on arbitrary external sites.

**Related:** **PRD 33** (Crafty **`image.php`** / **`SESSIONID`**), **PRD 28** (monitoring widget, color identity + lineage indicators), **PRD 21** (semantic navbar API + embed).

### KAIROS, mobile surfaces, and semantic monitoring (cross-links)

- **PRD 37 (KAIROS)** ???????? Channel memory consolidation may **read** visit/path aggregates and related signals; changes to **`lupo_visits`**, **`lupo_paths`**, or ingest contracts should be reviewed for downstream KAIROS consumers.
- **`MOBILE_SEPARATION_DOCTRINE.md`** ???????? Consumer **mobile web** and **desktop** differ in DOM and input (touch vs mouse). Ingestion and dashboards must not assume desktop-only events; use **explicit** event mapping into the same canonical tables where semantics align, or document divergent fields.
- **PRD 28** ???????? Floating / Eye-class monitoring and **PRD 21** semantic navbar embeds share **silent-harvest** and path obligations with this PRD; routing truth in **`SEMANTIC_MONITORING_DOCTRINE.md`**.

**Import mapping:** Crafty **`livehelp_*`** tables align to **`lupo_*`** per **`docs/doctrine/migrations/livehelp_migrations_readme.md`** and per-table files under **`docs/database/lupopedia/tables/migrations/`** (names differ; semantics above must hold post-import).

### Rollup and housekeeping (`gc.php` + `archivefootsteps`)

Raw page hits live in **`livehelp_visit_track`** until **session end** or **age threshold**. **`craftysyntax-reference/gc.php`** (included from **`image.php`**) runs **probabilistic** jobs that:

- Call **`archivefootsteps(sessionid)`** in **`functions.php`**: walk ordered **`visit_track`** rows, update **`livehelp_visits_daily` / `livehelp_visits_monthly`** via **`archivepage()`** (hierarchical URL tree), increment **`livehelp_paths_monthly`** and **`livehelp_paths_firsts`** for transitions between **`livehelp_visits_monthly.recno`** nodes (plus END), then **delete** that session????????s **`visit_track`** rows.
- On **visitor idle** (~5 min), also **`archivepage`** **`camefrom`** into **referrers** daily/monthly when **`reftracking`** is on, then **`archiveuser()`** (which may **`archiveidentity`** / **`archivekeywords`** when configured).

Lupopedia must provide **equivalent rollup** into **`lupo_*`** structures documented in **`livehelp_visit_track_migration.md`**, **`livehelp_paths_firsts_migration.md`**, **`livehelp_paths_monthly.md`**, **`livehelp_visits_daily.md`**, **`livehelp_visits_monthly.md`**, **`livehelp_referers_daily_migration.md`**, etc., with **????7.5** of **PRD 33** as the release gate for parity.

### Install schema: raw path/referrer streams vs aggregates (4.0.99+)

**Crafty Syntax pattern:** raw, append-only telemetry is **not** mixed into the same physical rows as long-lived aggregates. **`install_new_lupopedia.sql`** adds dedicated tables (see TOON):

| Table | Role |
|-------|------|
| **`lupo_paths_raw`** | High-volume **from_url ??? to_url** transitions; **`is_aggregated`** for GC/rollup |
| **`lupo_paths_daily`** | Daily URL-pair counts + **`date_ymd`**; optional **`from_content_id` / `to_content_id`** for Eye / **PRD 28** |
| **`lupo_paths_monthly`** | Monthly URL-pair counts + **`date_ym`** |
| **`lupo_referers_raw`** | Per-hit referrer + landing **`page_url`**; rolls up into **`lupo_referers`** / **`lupo_referers_daily`** |

These sit alongside the existing **`lupo_visits`** (per-hit **`path_url`**, **`is_processed`**) and **`lupo_paths`** (content-id **`entercontentid` / `exitcontentid`** aggregates). **Aggregation and retention** are implemented in **PHP** with **BIGINT UTC `YmdHis`** timestamps and **IdGenerator** PKs ??? not **`AUTO_INCREMENT`**, not Unix epoch storage. Full rationale and GC flow: **PRD 51 ??4.6**.

## Tables in This Namespace

| Table | Purpose | Primary Key | Key Application Relationships |
|-------|---------|-------------|------------------------------|
| `lupo_visits` | Individual visit tracking | `visit_id` | Central to analytics |
| `lupo_paths_raw` | Raw URL-pair transitions (append-only) | `path_raw_id` | Rollup to `lupo_paths_daily` |
| `lupo_paths_daily` | Daily URL-pair aggregates | `path_daily_id` | Eye / dashboards; optional content-id columns |
| `lupo_paths_monthly` | Monthly URL-pair aggregates | `path_monthly_id` | Long-horizon trends |
| `lupo_paths` | Content-id path aggregates | `path_id` | Crafty import + GC from visits |
| `lupo_visits_daily` | Aggregated daily visit statistics | `visits_daily_id` | Daily analytics |
| `lupo_referers_raw` | Raw referrer hits | `referer_raw_id` | Rollup to referer tables |
| `lupo_referers_daily` | Daily referrer statistics | `referers_daily_id` | Referrer analytics |
| `lupo_paths_summary` | Path usage statistics | `summary_id` | Navigation analytics |
| `lupo_analytics_campaign_vars` | Campaign tracking variables | `campaign_var_id` | Campaign analytics |
| `lupo_audit_log` | System audit logging | `audit_log_id` | Security auditing |
| `lupo_unified_log` | Unified system logging | `unified_log_id` | System monitoring |

## Table Details

### `lupo_visits`

**Purpose:** Tracks individual user visits with detailed session information.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| visit_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | YES | NULL | Foreign reference to lupo_actors |
| session_id | VARCHAR(64) | YES | NULL | Session identifier |
| ip_address | VARCHAR(45) | NO |  | Visitor IP address |
| user_agent | TEXT | YES | NULL | Browser user agent |
| referrer | VARCHAR(512) | YES | NULL | Referrer URL |
| landing_page | VARCHAR(512) | NO |  | Initial landing page |
| exit_page | VARCHAR(512) | YES | NULL | Final page visited |
| page_views | INT | NO | 1 | Number of pages viewed |
| duration_seconds | INT | YES | NULL | Visit duration in seconds |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_visits_actor | actor_id, created_ymdhis, is_deleted | Actor's visits |
| idx_visits_date | created_ymdhis, is_deleted | Date-based queries |
| idx_visits_ip | ip_address, created_ymdhis, is_deleted | IP-based analytics |

### `lupo_visits_daily`

**Purpose:** Stores aggregated daily visit statistics for reporting.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| daily_visit_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| visit_date | DATE | NO |  | Date of visit statistics |
| total_visits | INT | NO | 0 | Total visits for day |
| unique_visitors | INT | NO | 0 | Unique visitors for day |
| page_views | INT | NO | 0 | Total page views for day |
| duration_hundredths | INT | NO | 0 | Average visit duration ????? 100 (e.g. seconds with two decimal places scaled) |
| bounce_rate_hundredths | INT | NO | 0 | Bounce rate ????? 100 (e.g. 2350 = 23.50%) |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_visits_daily_date | visit_date, is_deleted | Date-based queries |
| idx_visits_daily_total | total_visits, visit_date, is_deleted | Popular days |

### `lupo_audit_log`

**Purpose:** Logs system audit events for security and compliance.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| audit_log_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| event_type | VARCHAR(64) | NO |  | Type of audit event |
| actor_id | BIGINT | YES | NULL | Foreign reference to lupo_actors |
| target_type | VARCHAR(32) | YES | NULL | Target entity type |
| target_id | BIGINT | YES | NULL | Target entity ID |
| description | TEXT | NO |  | Event description |
| ip_address | VARCHAR(45) | YES | NULL | Actor IP address |
| user_agent | TEXT | YES | NULL | Browser user agent |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| severity | VARCHAR(16) | NO | 'info' | Severity: debug, info, warning, error, critical |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_audit_log_actor | actor_id, event_type, created_ymdhis, is_deleted | Actor's audit trail |
| idx_audit_log_type | event_type, severity, created_ymdhis, is_deleted | Event type queries |
| idx_audit_log_target | target_type, target_id, created_ymdhis, is_deleted | Target-based queries |

### `lupo_unified_log`

**Purpose:** Central logging for all system events and performance metrics.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| unified_log_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| log_level | VARCHAR(16) | NO | 'info' | Level: debug, info, warning, error, critical |
| component | VARCHAR(64) | NO |  | System component generating log |
| actor_id | BIGINT | YES | NULL | Foreign reference to lupo_actors |
| message | TEXT | NO |  | Log message |
| context_json | JSON | YES | NULL | Additional context data |
| performance_ms | INT | YES | NULL | Performance metric in milliseconds |
| memory_usage | BIGINT | YES | NULL | Memory usage in bytes |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_unified_log_level | log_level, component, created_ymdhis, is_deleted | Level-based queries |
| idx_unified_log_component | component, created_ymdhis, is_deleted | Component-based queries |
| idx_unified_log_performance | performance_ms, created_ymdhis, is_deleted | Performance analysis |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 11_analytics_tracking | This ???????? 01_core_identity | Actor analytics | actor_id references |
| 11_analytics_tracking | This ???????? All namespaces | System-wide tracking | Logs events from all areas |
| 11_analytics_tracking | This ???????? 14_system_operations | Performance monitoring | System health metrics |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Normal logging operation | N/A (logs are immutable) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

IP addresses are logged for security but may be anonymized after retention period

User agents are logged for analytics but not PII

Audit logs are immutable and cannot be modified

Soft delete preserves log history for compliance

## Testing Requirements

Unit tests for visit tracking and aggregation

Integration tests for audit logging and unified logging

Performance tests for analytics queries and reporting

Soft delete behavior verification

## Usage Patterns

```php
// Record visit
$visitService = new VisitService();
$visitId = $visitService->recordVisit($actorId, $sessionId, $ipAddress, $userAgent);

// Log audit event
$auditService = new AuditLogService();
$auditId = $auditService->logEvent($eventType, $actorId, $targetType, $targetId, $description);

// Log system event
$unifiedLogService = new UnifiedLogService();
$logId = $unifiedLogService->log($level, $component, $message, $context, $performance);

// Get daily analytics
$analyticsService = new AnalyticsService();
$dailyStats = $analyticsService->getDailyStats($startDate, $endDate);

// Track campaign variable
$campaignService = new AnalyticsCampaignService();
$varId = $campaignService->trackVariable($campaignName, $variableName, $value);
``` 

## Color Identity and Lineage Events (The Eye)

The Eye (PRD 28) and `lupopedia_js.php` (PRD 04) MUST be able to record these events. This PRD update does **not** add tables. Until a dedicated event store exists, implementations MAY log to **`lupo_audit_log`** / **`lupo_unified_log`** / existing visit metadata JSON. Do **not** invent HEX6 in event payloads.

| Event key | When |
|-----------|------|
| `color_identity_viewed` | Visitor or operator opens or is shown the page's color identity (GroupColor, ColorName, HEX6 if known) in The Eye or navbar. |
| `lineage_viewed` | Visitor or operator opens lineage display (parent URL, child URLs, lineage tree). |
| `child_page_created` | A child page is declared (Color Registry submit / Declare Child Page completed). |
| `parent_page_referenced` | A parent URL is attached or opened as the source of a lineage pair. |
| `collection_selected` | Operator or visitor selects a Collection in the Collection Selector (blue dropdown). Payload SHOULD include `collection_name`. |

Event payload SHOULD include (when known, never guessed): current URL, parent URL, child URL, GroupColor, ColorName, collection_name, HEX6 or empty, change type, change intent. Packed timestamps are BIGINT UTC (`gmdate('YmdHis')`).

## Color Groups and Collections (unified)

A Color Group is not only a color identity. It also represents a Collection (named set of webpages, artifacts, or semantic nodes). Color Groups store `color_group`, `color_nickname`, `collection_name`, lineage metadata, and semantic identity metadata.

Collections determine the structure of semantic drop menus. The Collection Selector (blue dropdown) sets the active Collection. Green tabs and sub-menus are populated from **`lupo_collection_tabs`** filtered by that Collection. Collection rows are **`lupo_collections`** (PRD 73).

Selecting a Collection is a first-class analytics event (`collection_selected`). Do not invent collection names.

### Artifact lineage events (PRD 92)

These events belong to the **artifact lineage widget**, not The Eye. Do not invent remix or like counts.

| Event key | When |
|-----------|------|
| `remix_created` | A remix / child artifact is declared. |
| `remix_viewed` | Remix chain or a child remix is shown. |
| `attribution_viewed` | Attribution panel is shown (CC-BY first license). |
| `artifact_engagement` | Like, share, or similar engagement (include type in payload). |
| `artifact_lineage_viewed` | Artifact lineage tree is opened. |

---

## Content & Analytics Ingestion Pipeline (4.0.96+)

### Purpose
Define the canonical workflow, rules, and mappings for importing Crafty Syntax content pages, navigation paths, referrers, and analytics into Lupopedia????????s content, analytics, and memory graph systems.

### Scope
- Applies to all Crafty Syntax 3.7.5 data imported into Lupopedia 4.0.96+
- Covers content pages, navigation paths, referrers, and session analytics
- Defines ingestion into: content/ filesystem, lupo_contents, lupo_memory_nodes, lupo_edges

### Ingestion Workflow
1. **Extract** Crafty Syntax data (content, paths, referrers, analytics) from legacy tables/files
2. **Transform** data to Lupopedia schema and slug rules
3. **Load** into:
   - content/ (file-backed content)
   - lupo_contents (storage_type='file_backed')
   - lupo_memory_nodes (one node per page/referrer)
   - lupo_edges (weighted navigation edges)
4. **Link** navigation paths and referrers as weighted edges in the memory graph
5. **Update** or create new nodes/edges as new data arrives or is consolidated

### Filesystem Rules
- All imported content pages are written to content/{slug}.htm or .md
- Slugs are generated from Crafty URLs/titles, normalized per Lupopedia slug rules
- File-backed content is referenced in lupo_contents (storage_type='file_backed')

### Database Rules
- lupo_contents: Each imported page is a row with storage_type='file_backed', title, slug, and metadata
- lupo_memory_nodes: Each page and referrer becomes a memory node (type: content_page, referrer)
- lupo_edges: Navigation paths become edges with edge_weight from Crafty????????s 'visits' column
- lupo_edges: enter=0 ???????? edge from special session_start node; exit=0 ???????? edge to session_end node
- Monthly aggregation (dateof) is stored as edge metadata (edge_month)

### Memory Node Creation Rules
- One node per unique content page (by slug)
- One node per unique referrer (by URL)
- Nodes include metadata: source (Crafty), import timestamp, original ID
- Nodes are linked to content files and lupo_contents rows

### Edge Creation Rules
- For each navigation path (livehelp_paths_monthly):
  - Create a directed edge from enter_recno to exit_recno
  - Set edge_weight = visits
  - Set edge_month = dateof
  - If enter_recno=0, source is session_start node
  - If exit_recno=0, target is session_end node
- For each referrer:
  - Create an edge from referrer node to landing page node
  - Set edge_weight = count
  - Include timestamp/aggregation as edge metadata

### Slug Rules
- Slugs are generated from Crafty URLs/titles using Lupopedia????????s canonical slugification (lowercase, a-z, 0-9, underscore)
- Duplicates are resolved by appending numeric suffixes
- All slugs are unique within content/ and lupo_contents

### Update Rules
- On re-import or update, existing nodes/edges are updated if the source ID matches
- New content/pages/paths/referrers create new nodes/edges
- Edge weights are incremented if the same path/referrer is seen again

### Examples
**Navigation Path:**
  - Crafty: enter_recno=12, exit_recno=34, visits=5, dateof=202603
  - Lupopedia: Edge from node 12 to node 34, edge_weight=5, edge_month=202603

**Session Start:**
  - Crafty: enter_recno=0, exit_recno=22, visits=3
  - Lupopedia: Edge from session_start node to node 22, edge_weight=3

**Referrer:**
  - Crafty: referrer_url="https://search.example.com", count=7
  - Lupopedia: Node for referrer, edge to landing page, edge_weight=7

### Interactions with KAIROS Consolidation
- KAIROS may consume navigation and analytics edges as evidence for memory consolidation
- Weighted edges inform confidence and recency in consolidated memory
- KAIROS must not overwrite provenance or edge weights from Crafty import

### Interactions with Lossy Abbreviation Dialect
- Redundant or highly similar navigation paths/analytics may be summarized into lossy abbreviation nodes
- Lossy nodes are linked to originals via abbreviates edges (see PRD 37)
- All lossy nodes must be reversible and include metadata for review

### Mapping Crafty Syntax Tables to Lupopedia Structures
| Crafty Table | Lupopedia Target |
|--------------|-----------------|
| livehelp_paths_monthly | lupo_edges (navigation, weighted) |
| livehelp_visits_daily/monthly | lupo_visits_daily, lupo_visits |
| livehelp_referers_daily | lupo_referers_daily, lupo_memory_nodes (referrer) |
| livehelp_visit_track | lupo_visits, lupo_edges (session path) |
| content pages (files/tables) | content/, lupo_contents, lupo_memory_nodes |

---


---

## Context????????Typed, Status????????Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A ???????? B)
  - bidirectional (A ???????? B)
  - restricted-direction (A ???????? B but not B ???????? A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported ???????? supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```
