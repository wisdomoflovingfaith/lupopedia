---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: prd
  when_updated: null
  file_path_from_root: "docs/versions/4.0.88/prd/03_goals_and_success_criteria.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.88/prd/03_goals_and_success_criteria.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: prd
  artifact_kind: goals
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: PRD 03 — Goals and Success Criteria

# PRD 03: Goals and Success Criteria

**Status:** Draft
**Version:** 4.0.88
**Author:** CASCADE (actor_id 105)

**4.1.0 Scope Status:** Rejected from current 4.1.0 release scope; retained as historical feature planning only.

---

## 1. Version Goals for 4.0.88

### Primary Goal
**Make Lupopedia a semantic monitoring system with engagement tracking** — not just a chat tool. Every page on the monitored domain becomes a known entity in the Lupopedia content graph, with navigation paths forming edges between them, and all engagement signals tracked.

### Specific Goals

**G1: Visitor pages become content entities**
Every unique URL visited by a browser with the monitoring widget installed gets auto-registered in `lupo_contents`. Lupopedia builds a content map of the entire domain without manual data entry.

**G2: Navigation paths become a queryable graph**
Page-to-page transitions are recorded as edges in `lupo_edges`. This enables questions like: "What are the most common paths to the checkout page?" or "Which pages have the highest exit rate?"

**G3: Visit history is preserved**
Individual visits are recorded in `lupo_visits` with session, timestamp, referrer, and federation node scoping. This enables session replay, funnel analysis, and time-based traffic patterns.

**G4: Federation-scoped data**
All semantic data is scoped to `federation_node_id` so multi-site or multi-domain installations keep their data isolated and queryable per node.

**G5: Modernize without breaking**
The JavaScript generation in `livehelp_js.php` is improved (DOM manipulation, feature detection, modern XHR) while maintaining full backward compatibility with existing deployments.

**G6: Engagement tracking system (NEW)**
All engagement signals (likes, shares, comments, hashtags, ratings, bookmarks) are tracked in dedicated tables with user attribution, temporal patterns, and content performance metrics.

**G7: Crafty Syntax 3.7.5 parity (CRITICAL)**
Lupopedia 4.0.88 must support ALL features that Crafty Syntax 3.7.5 provided, including chat functionality, operator management, department routing, file transfers, surveys, and analytics.

**G8: Crafty Syntax Tracking Research (CRITICAL)**
Full research and understanding of how Crafty Syntax 3.7.5 tracking works, specifically:
- Study `gc.php` and `archivefootsteps()` function
- Understand session-based vs real-time tracking
- Map `livehelp_visit_track` to new `lupo_visits` schema
- Replicate navigation path creation logic
- Preserve daily/monthly aggregation capabilities

---

## 2. Phased Delivery

### Phase 0: Crafty Syntax Research (MUST - PREREQUISITE)

**What:** Complete research and understanding of Crafty Syntax 3.7.5 tracking system.

**Files to study:**
- `archive/legacy/craftysyntax-3.7.5/gc.php` - Session processing
- `archive/legacy/craftysyntax-3.7.5/functions.php` - `archivefootsteps()` function
- `archive/legacy/craftysyntax-3.7.5/livehelp_visit_track` table structure
- `archive/legacy/craftysyntax-3.7.5/livehelp_paths_*` tables

**Deliverables:**
- Complete understanding of session-based tracking pattern
- Mapping of Crafty Syntax tables to Lupopedia schema
- Documentation of `archivefootsteps()` logic for navigation paths
- Analysis of daily/monthly aggregation methods

**Why Phase 0:** Cannot implement semantic tracking without understanding how Crafty Syntax worked.

### Phase 1: Backend Semantic Storage (MUST — core deliverable)

**What:** Add semantic data processing to `image.php` so it records visits, auto-registers content, and creates navigation edges.

**Files modified:**
- `image.php` — add semantic processing after existing logic
- `install_new_lupopedia.sql` — ensure `lupo_visits` and `lupo_semantic_content_views` tables exist
- Migration SQL — for existing installs

**Deliverables:**
- `image.php` processes `page`, `title`, `referer` into `lupo_contents`, `lupo_visits`, `lupo_edges`, `lupo_semantic_content_views`
- Deduplication: same session + same pageid → only one visit record
- Federation node scoping on all writes
- No changes to livehelp_js.php in this phase (existing JS already sends the data)

**Why this is Phase 1:** The JS already sends page/title/referrer data. The backend just doesn't store it semantically yet. This phase requires zero client-side changes.

### Phase 2: JavaScript Modernization (SHOULD)

**What:** Modernize the generated JavaScript while maintaining backward compatibility.

**Files modified:**
- `livehelp_js.php` — rewrite generated JS

**Deliverables:**
- Replace `document.write()` with DOM manipulation
- Replace NS4/IE4 detection with feature detection
- Add `fetch()` with image fallback for data transmission
- Send `page_path` (path-only) as a separate parameter alongside existing `page` (full URL)
- Support `data-lupopedia-department` attribute on script tag
- Existing `<div id="craftysyntax_N">` continues to work

**Why Phase 2:** The backend already works with the existing data format. JS modernization is an improvement but not a blocker.

### Phase 3: Semantic API and Query Endpoints (SHOULD)

**What:** Expose the navigation graph and visit data through Lupopedia's API layer.

**Files modified/created:**
- `includes/modules/api/semantic-api.php` — extend or create
- `api/` endpoints

**Deliverables:**
- API endpoint: GET `/api/semantic/content?node_id=1` — list auto-discovered pages
- API endpoint: GET `/api/semantic/navigation?content_id=X` — navigation edges for a page
- API endpoint: GET `/api/semantic/visits?session_id=X` — visit history for a session
- API endpoint: GET `/api/semantic/popular?node_id=1&period=day` — most viewed pages

**Why Phase 3:** The data needs to exist (Phase 1) before it can be queried. API is the consumption layer.

### Phase 4: Engagement Tracking Implementation (MUST for 4.0.88)

**What:** Implement the complete engagement tracking system with likes, shares, comments, hashtags, ratings, and bookmarks.

**Files modified/created:**
- `includes/modules/engagement/engagement-controller.php` — NEW
- `includes/modules/engagement/engagement-model.php` — NEW
- `livehelp_js.php` — add engagement UI generation
- `image.php` — add engagement signal processing
- SQL install scripts — add engagement tracking tables

**Deliverables:**
- JavaScript widget UI for likes, shares, comments, ratings
- Engagement signal processing in image.php
- Hashtag auto-extraction from content
- Comment threading and moderation
- Engagement analytics aggregation
- Social share tracking

**Why Phase 4:** Engagement tracking is a core requirement for 4.0.88 and depends on the content foundation from Phase 1.

### Phase 5: Crafty Syntax Parity Verification (MUST for 4.0.88)

**What:** Ensure complete feature parity with Crafty Syntax 3.7.5.

**Files modified/created:**
- Feature parity audit checklist
- Missing feature implementations
- Migration compatibility testing
- API compatibility layer

**Deliverables:**
- Complete Crafty Syntax feature mapping
- Migration scripts from Crafty Syntax 3.7.5
- Backward compatibility API endpoints
- Feature parity test suite
- Documentation of parity status

**Why Phase 5:** Parity is critical for migration acceptance and must be verified before release.

### Phase 6: Admin Dashboard Widgets (NICE TO HAVE — may defer to 4.0.89+)

**What:** Visual representation of the navigation graph and visitor intelligence in the admin panel.

**Deliverables:**
- Navigation path visualization (simple graph or sankey diagram)
- Top pages / most-visited content list
- Entry/exit page analysis
- Referrer source breakdown (search vs. direct vs. social)

---

## 3. Success Criteria

### Phase 0 Success (Crafty Syntax Research)

- [ ] Complete analysis of `gc.php` session processing logic
- [ ] Full understanding of `archivefootsteps()` function
- [ ] Mapping of all Crafty Syntax tracking tables to Lupopedia schema
- [ ] Documentation of navigation path creation algorithm
- [ ] Understanding of daily/monthly aggregation patterns
- [ ] Research document created with implementation guidance

### Phase 1 Success (minimum for 4.0.88 release)

- [ ] A visitor browsing `example.com/products/page-a` with the widget installed causes a row to appear in `lupo_contents` with `content_key = 'products/page-a'` and `federation_node_id = 1`
- [ ] The visit is recorded in `lupo_visits` with correct session, path, title, referrer, and timestamp
- [ ] If the visitor navigated from `example.com/products/` to `example.com/products/page-a`, an edge exists in `lupo_edges` with `edge_type = 'navigated_to'` and `edge_category = 'navigation'`
- [ ] Repeated navigation on the same path increments the edge weight
- [ ] View counts in `lupo_semantic_content_views` reflect actual visit counts
- [ ] All data is scoped to `federation_node_id = 1` (default)
- [ ] Existing chat functionality (operator status, chat window, layer invites) is unaffected
- [ ] `livehelp_js.php` continues to work with no client-side changes required
- [ ] Performance: `image.php` response time remains under 200ms with semantic processing

### Phase 4 Success (Engagement Tracking)

- [ ] Like buttons appear on monitored pages and increment counts in `lupo_engagement_signals`
- [ ] Share buttons track social media shares with platform metadata
- [ ] Comment forms allow threaded discussions with moderation
- [ ] Hashtags are auto-extracted from content and stored in `lupo_hashtags`
- [ ] Rating system (1-5 stars) stores average ratings per content
- [ ] Bookmark functionality allows users to save content to collections
- [ ] Engagement analytics calculate engagement rates and trending content
- [ ] All engagement data is properly scoped by `federation_node_id`

### Phase 5 Success (Crafty Syntax Parity)

- [ ] All Crafty Syntax 3.7.5 features are mapped to Lupopedia equivalents
- [ ] Migration script successfully imports Crafty Syntax database
- [ ] Existing Crafty Syntax installations can upgrade to 4.0.88 without data loss
- [ ] API compatibility layer supports legacy third-party integrations
- [ ] File transfers work in chat (if supported by underlying tables)
- [ ] Chat surveys/feedback system is functional
- [ ] Multi-language support is verified
- [ ] All chat statistics and analytics are available
- [ ] Custom chat themes and department routing work correctly
- [ ] Proactive chat invites and operator permissions function properly

### Phase 6 Success

- [ ] Generated JavaScript uses DOM manipulation instead of `document.write()`
- [ ] Legacy `<div id="craftysyntax_N">` containers still work
- [ ] JS file size under 15KB
- [ ] `data-lupopedia-department` attribute works as alternative to GET parameter

### Phase 3 Success

- [ ] API returns correct content list for a federation node
- [ ] API returns correct navigation edges for a content item
- [ ] API respects federation node scoping

---

## 4. Risks and Mitigations

**Risk: Database write volume under high traffic**
The widget pings image.php every 10 seconds per visitor. With semantic writes, this could create significant DB load.
**Mitigation:** Deduplicate using `pageid` — only the first ping for a new page load creates a visit record. Subsequent pings are "still here" signals only.

**Risk: Content ID allocation collisions under concurrency**
Multiple visitors discovering new pages simultaneously could cause ID conflicts.
**Mitigation:** Use INSERT with explicit ID and catch duplicate-key errors. Retry with incremented ID. Or pre-allocate ID ranges per federation node.

**Risk: Breaking existing deployments**
Any change to `livehelp_js.php` output could break sites that depend on exact JS behavior.
**Mitigation:** Phase 1 makes zero changes to JS output. Phase 2 maintains backward compatibility. Existing `<div>` containers and image-based control signals continue to work.

**Risk: Privacy compliance**
Recording visitor navigation paths may have privacy implications.
**Mitigation:** No raw IP storage (hash only). No PII beyond session ID. Respect DNT header. Session cookies are first-party only. No cross-domain tracking.

---

## 5. Dependencies

- **Phase 1 depends on:** `lupo_visits` and `lupo_semantic_content_views` tables existing in install SQL
- **Phase 2 depends on:** Phase 1 (backend must accept both legacy and modernized JS payloads)
- **Phase 3 depends on:** Phase 1 (data must exist before API can serve it)
- **All phases depend on:** Lupopedia doctrine compliance (no FKs, BIGINT timestamps, PDO_DB access, explicit IDs)
