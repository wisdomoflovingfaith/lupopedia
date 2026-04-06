---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260405205506"
  file_path_from_root: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
  last_modified_utc: "20260405205506"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-softaculous-4-1-0-gate"
  prd_id: 33
  prd_slug: softaculous_certification_4_1_0_gate
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: prd
  artifact_kind: release_requirements
  purpose: "Softaculous / shared-hosting certification and Crafty Syntax live-help parity as the explicit 4.1.0 release gate; 4.0.x until requirements satisfied"
  status: "approved"
  tags:
    - prd
    - softaculous
    - hosting
    - live_help
    - crafty_syntax
    - release_gate
    - "4.1.0"
    - emoji
    - lupo_emoji
    - shared_hosting
    - subdirectory_install
    - i18n
    - localization
    - multilingual
    - visitor_session
    - name_key
    - analytics
    - rollup
    - operator_console
    - admin_chat
    - typing_preview
    - lilith_audit_approved
    - lilith_audit_final_20260403
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor — PHP 7.4+, no banned stack patterns, subdirectory install"
    - to: "lupo-docs/prd/13_crafty_integration.md"
      type: references
      weight: 1.0
      reason: "Crafty 3.7.5 import and livehelp→lupo table semantics"
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 1.0
      reason: "Operator multi-chat UI, actor color coding, channel display"
    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: references
      weight: 1.0
      reason: "Root lupopedia_js.php semantic / Eye widget requirements"
    - to: "lupo-docs/prd/11_analytics_tracking.md"
      type: references
      weight: 1.0
      reason: "Paths, referrer, visitor tracking, campaign analytics — paired with §3.9 rollup shapes"
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: references
      weight: 0.95
      reason: "Authoritative lupo_visits, visits_daily, paths, referers_* DDL — §3.9"
    - to: "lupo-docs/prd/27_installer_requirements.md"
      type: references
      weight: 0.95
      reason: "Installer and distribution expectations"
    - to: "lupo-docs/prd/25_departments_system.md"
      type: references
      weight: 0.9
      reason: "Department-scoped chat templates and routing"
    - to: "lupo-docs/doctrine/migrations/livehelp_migrations_readme.md"
      type: references
      weight: 1.0
      reason: "livehelp_* → {{prefix}} mapping narrative"
    - to: "lupo-docs/doctrine/migrations/crafty_syntax_ancestral_intent.md"
      type: references
      weight: 0.9
      reason: "Crafty lineage and migration intent"
    - to: "lupo-includes/modules/crafty_syntax/"
      type: references
      weight: 0.85
      reason: "Runtime module surface for legacy-compatible endpoints (path may vary by tree)"
    - to: "lupo-database/lupopedia/content/lupo-app/Services/CraftySyntax/"
      type: references
      weight: 0.9
      reason: "LegacyAdminChatXmlHttp, LegacyLiveHelpJs, xmlhttp-style service layer in repo"
    - to: "lupo-docs/channels/doctrine/legacy-import/CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md"
      type: references
      weight: 0.95
      reason: "identity(), get_identitystring, detectID fallback chain, IP/session doctrine"
    - to: "lupo-docs/channels/doctrine/legacy-import/CRAFTY_SYNTAX_BUFFER_STREAMING_DOCTRINE.md"
      type: references
      weight: 0.95
      reason: "sendbuffer / buffer streaming vs flush detection and chatmode"
    - to: "craftysyntax-reference/"
      type: references
      weight: 1.0
      reason: "In-repo Crafty Syntax reference tree — data.php, functions.php, image.php, gc.php, javascript/dynapi"
    - to: "craftysyntax-reference/lang/"
      type: references
      weight: 1.0
      reason: "Legacy 14-locale $lang packs (charset + txt keys) — parity reference for §3.7 / §7.9"
    - to: "craftysyntax-reference/user_questions.php"
      type: references
      weight: 1.0
      reason: "Visitor naming (isnamed, username uniqueness loop) — parity reference for §3.8 / §7.6"
    - to: "craftysyntax-reference/live.php"
      type: references
      weight: 1.0
      reason: "Operator frameset shell — parity reference for §3.10 / §7.1 (iframe/div in Lupopedia)"
    - to: "craftysyntax-reference/admin_connect.php"
      type: references
      weight: 0.95
      reason: "Chat transport redirect (chatmode → is_xmlhttp / is_flush / refresh)"
    - to: "craftysyntax-reference/is_xmlhttp.php"
      type: references
      weight: 0.95
      reason: "XMLHTTP probe → admin_chat_xmlhttp vs fallback chain"
    - to: "craftysyntax-reference/admin_chat_bot.php"
      type: references
      weight: 0.95
      reason: "Visitor tabs, channelsplit, compose row — bottom frame of live.php"
    - to: "craftysyntax-reference/admin_chat_xmlhttp.php"
      type: references
      weight: 0.95
      reason: "DynLayer typing preview (UserIsTypingDiv), update_typing — §3.11"
    - to: "craftysyntax-reference/image.php"
      type: references
      weight: 0.9
      reason: "what=startedtyping / donetyping, writediv messages — §3.11"
    - to: "craftysyntax-reference/javascript/dynapi/"
      type: references
      weight: 1.0
      reason: "Reference dynapi/dynlayer sources — parity for DHTML layers (§3.0, §8)"
    - to: "lupo-docs/database/lupopedia/tables/migrations/"
      type: references
      weight: 0.95
      reason: "Per-table livehelp_* → lupo_* migration markdown (paired with livehelp_migrations_readme.md)"
    - to: "lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md"
      type: references
      weight: 0.9
      reason: "Wide livehelp → lupo mapping reference"
    - to: "lupo-emoji/"
      type: references
      weight: 0.85
      reason: "Emoji/image picker asset roots — folder per set (§7.8)"
    - to: "lupo-rules/root/lilith-noninterference-doctrine.md"
      type: references
      weight: 0.75
      reason: "LILITH reviewer role — audit is review-only (LIL001)"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/README.md"
      type: references
      weight: 0.95
      reason: "Implementation workspace — questions/decisions/status vs PRD §12"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md"
      type: references
      weight: 1.0
      reason: "FTP-safe distribution zip/tar — no dotdirs except .htaccess; build_softaculous_package.sh"
    - to: "lupo-scripts/build_softaculous_package.sh"
      type: references
      weight: 0.95
      reason: "Canonical packager (run from checkout; excluded from distribution archive)"
    - to: "lupo-install/InstallWizardHtaccessWriter.php"
      type: references
      weight: 0.95
      reason: "Install-time .htaccess generation (WordPress-style; no dotfiles in zip)"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_study_20260404.md"
      type: references
      weight: 0.9
      reason: "WordPress reference study report (Section 14)"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_061540_QUESTION_wordpress_distribution_patterns_unresolved.md"
      type: references
      weight: 0.9
      reason: "WordPress distribution study question thread (resolved)"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md"
      type: references
      weight: 1.0
      reason: "LILITH answers to Section 14 questions (UTC 20260404061932)"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_pattern_implementation_tasks_20260404.md"
      type: references
      weight: 1.0
      reason: "Implementation backlog from LILITH resolutions"
    - to: "lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md"
      type: references
      weight: 1.0
      reason: "Canonical WordPress-derived pattern distillate — read before re-scanning lupo-archive/legacy/wordpress-reference/"
    - to: "lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "No shared desktop/mobile layouts; mobile web vs native operator app (PRD 35)"
    - to: "lupo-docs/prd/35_mobile_native_app_separation.md"
      type: references
      weight: 1.0
      reason: "Mobile native app and separation; mobile visitor checklist §7.4"
lupopedia.footer:
  last_verified: "20260405205506"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
  next_action:
    - "PRD header status approved — gate text is authoritative; execute §7.4–§7.9 + §10 via lupo-docs/versions/4.0.94/TODO.md (§12)"
    - "LILITH (actor_id 2) final audit §13 — APPROVED for code (100/100); §7.4 = Crafty parity roadmap; §10 = 4.1.0 gate; §12 traceability in TODO.md"
    - "Implementers: pick §7.4 rows (e.g. mobile client chat, real visitor list, typing preview) after TODO.md rows exist; 34 doctrine ghosts are not blocking this track"
    - "Before Softaculous submit: run build_softaculous_package.sh; zip must contain zero dotfiles; install wizard attempts .htaccess when host allows (WordPress pattern per SOFTACULOUS_PACKAGE_BUILD.md); must not fail install if absent — PRD 00 §2, §9.5, §18"
    - "Section 14 WordPress study: LILITH answered all six questions (answers/20260404_061932_*); execute status/wordpress_pattern_implementation_tasks_20260404.md; trace in versions/*/TODO.md per §12"
    - "WordPress patterns: read lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md (§14.5) before scanning lupo-archive/legacy/wordpress-reference/"
---

# PRD 33: Softaculous certification and 4.1.0 release gate

## 1. Purpose

Define **hosting-distribution readiness** (including **Softaculous-style** one-click expectations) and **functional parity** with **Crafty Syntax 3.7.5 live help** so that **Lupopedia 4.1.0** is not tagged until this PRD’s **completion criteria** are met. Until then, work continues on **4.0.x** (single install from Crafty, no Lupopedia→Lupopedia upgrade per constitutional single-install doctrine).

### 1.1 Lineage — why this gate favors simplicity and parity over fashion

**Crafty Syntax** was originally written in **2002** by a **single programmer** who shipped and supported it across **thousands of installs worldwide**—shared hosts, odd PHP builds, hostile traffic, and the full early-web attack surface (**SQL injection**, **remote file inclusion**, **XSS**, **newline/control-character injection**, **buffer/length abuse**, and the rest of the “wild west” era). The system **kept working for well over twenty years** in those conditions. That is **empirical** evidence, not nostalgia: the reference tree under **`craftysyntax-reference/`** encodes **battle-tested** choices (layered transports, conservative defaults, explicit fallbacks) that **earned** their place.

**Why that matters for Lupopedia:** the product owner’s bias is **not** to “modernize for modernizing’s sake” with **heavy frameworks**, **Composer ecosystems**, or **clever database logic** that hides behavior in triggers and procedures. **Lupopedia** stays **plain PHP + PDO_DB**, **application-layer** rules, **dumb storage**, and **in-tree** dependencies—because that alignment is how the original system **survived** real installs. IDE agents and implementers should treat Crafty parity and **§3.0** / **§5** constraints as **intentional continuity** with that history, not as obstacles to replace with whatever stack is trending.

## 2. Release positioning

| Line | Rule |
|------|------|
| **4.0.x** | Current product line: **fresh install** from **`install_new_lupopedia.sql`** (+ seed). **Optional:** load **Crafty Syntax 3.7.5** tables and run **`import_from_old_crafty_syntax.sql`** for legacy data. **No** Lupopedia→Lupopedia upgrade; schema/features evolve without Softaculous/certification completion obligation. |
| **4.1.0** | **First** line where this PRD’s checklist is **complete**, installer/distribution story is **explicit**, certification artifacts (or hoster-equivalent evidence) exist, and **Lupopedia→Lupopedia** / auto-installer expectations **may** apply per product owner release. |

This PRD does **not** authorize **Lupopedia→Lupopedia** migrations **during 4.0.x**; it states **what must be true** to call a release **4.1.0** and to ship hosting-grade **Crafty→Lupopedia** (and later in-place) upgrade stories.

### 2.1 Softaculous / auto-installer acceptance (manual)

**4.1.0** appearing in **Softaculous** (or another hoster one-click catalog) is **not** controlled only by tagging Git. The maintainer **submits** the package; the **vendor** **reviews** the code and **manually** adds or updates the entry in **their** auto-installer. Until that acceptance completes, end users do not get one-click installs from that channel regardless of internal version numbers.

## 3. Legacy reference codebase

- **Intent:** Crafty Syntax **3.7.5** behavior is the reference for “what worked.”
- **Canonical in-repo tree:** **`craftysyntax-reference/`** (repository root) — use this for line-level parity (admin **data** UI, **`functions.php`**, **`image.php`**, embed scripts). Older paths such as `lupo-archive/legacy/craftysyntax-3.7.5/` may exist on some checkouts; do **not** hardcode a single filesystem path in runtime code—use config/doctrine resolution.

### 3.0 Modernization rules (Lupopedia implementation, Crafty parity preserved)

These are **binding** for operator/visitor UI and live-help runtime refactors toward **4.1.0**; they do **not** invite speculative rewrites of behavior that still works.

| Topic | Rule |
|--------|------|
| **Frames → iframe / div** | Replace legacy **HTML framesets** with **`iframe`** and/or **div-based** shell layout in Lupopedia (admin and embed shells). Framesets block embedding, **CSP**, and hoster demos; **iframes** or equivalent are the compatibility target. |
| **Database access** | **All** queries go through **`DatabaseFactory::getConnection()`** / **`lupo_get_db()`** and the **PDO_DB** wrapper (**named placeholders**, bound parameters). **No** string-concatenated values in SQL; **no** legacy procedural query calls that interpolate user input for **new or ported** paths. |
| **Bound parameters vs SQL escaping** | User-controlled **values** passed as **bound parameters** (`:name` + params array) are **encoded by the driver** for SQL injection safety—**do not** also run legacy **SQL string** escaping (`filter_sql`, `addslashes`, manual quote doubling) on those values. That causes **double-escaping** (wrong data in the DB, broken search/display). Reserve escaping/sanitization for **output contexts** (HTML, JS, URLs) and for any **remaining** interpolated SQL fragments (should be **none** for user data; if dynamic identifiers like `ORDER BY` exist, use **whitelists**, not user quotes). |
| **Request input** | Keep **one consolidated untrusted bucket** (Crafty: **`$UNTRUSTED`**) for **all** user-supplied request data. Validate type/range/allow-lists in PHP; **do not** treat 2003-era `filter_sql` as the primary defense when using prepared statements—use it **only** where legacy code still builds raw SQL strings until ported. |
| **DHTML layers (dynapi / dynlayer)** | Use the **dynapi** stack for moving layers, invites, and legacy-equivalent UI—reference **`craftysyntax-reference/javascript/dynapi/js/`** (e.g. **dynlayer**); runtime copy under **`lupo-includes/js/dynapi/`** (see **§8**). **Do not** replace working layer/invite behavior with **Canvas**, heavy **CSS-only** animation rewrites, or **npm UI frameworks** for this scope. **If it is not broken, do not fix it.** |
| **Fallbacks** | Preserve **transport and UI fallbacks** documented in **§7.4** (buffer flush, **xmlhttp** / **image** polling legs, etc.). Modernization must **not** remove a fallback leg until an **APPROVED** replacement is tested on shared hosting–class constraints. |

### 3.1 `data.php` analytics tabs (exact Crafty mapping)

`craftysyntax-reference/data.php` loads one include per **`tab`** (see `CTabBox` labels and `if ($UNTRUSTED['tab']==N)` chain):

| `tab` | Include | Operator-facing purpose |
|------|---------|-------------------------|
| **0** | `data_transcripts.php` | Transcripts |
| **1** | `data_messages.php` | Messages |
| **2** | `data_referers.php` | Referrers |
| **3** | `data_visits.php` | **Visits** — “Top Urls” vs “Domain Tree”, month/year filters, department filter; uses **`livehelp_visits_monthly`** (and related visit aggregates). |
| **4** | `data_paths.php` | **Paths** — “All Visit Paths” vs “First Visit Paths”; drill-down from **START of session** via **`parent`** / **`exit_recno`**; queries **`livehelp_paths_monthly`** and **`livehelp_paths_firsts`** (keyed by **`dateof`** YYYYMM + **`visit_recno`**); resolves page labels via **`livehelp_visits_monthly`**. Gated by **`CSLH_Config['tracking']=='Y'`**. |
| **5** | `data_keywords.php` | Keywords |
| **6** | `data_users.php` | Users |
| **7** | `data_clean.php` | Clean-up (admin-only) |

**Parity note:** “Paths” in Crafty is **`tab=4`**, not `tab=3`. **`tab=3`** is the **visits / URL list** surface. Lupopedia must preserve **both** semantics (see **PRD 11**).

### 3.2 Page-level ingestion and `SESSIONID` (embed → `image.php`)

Per **`craftysyntax-reference/image.php`**: visitor page hits are recorded against **`identity['SESSIONID']`** into **`livehelp_visit_track`** (`sessionid`, `location`, `page` / `pageid`, `title`, `whendone`, `referrer`). That row stream feeds monthly path/visit aggregates the **data** tabs above consume. **`livehelp_js.php`** may use a **ghost** session path; **`image.php`** is where full **`identity()`** runs for many **`what=`** commands—see comments there on **image-based** state (e.g. digit-encoded payloads for pre-CORS clients).

### 3.3 Identity, IP, two browsers on one machine, cookieless recovery

**Source:** `craftysyntax-reference/functions.php`.

- **`get_ipaddress()`** — Walks proxy/CDN headers in order (`HTTP_CF_CONNECTING_IP`, `HTTP_TRUE_CLIENT_IP`, `HTTP_CLIENT_IP`, `HTTP_X_FORWARDED_FOR`, `HTTP_X_FORWARDED`, cluster/forwarded variants, `HTTP_X_REAL_IP`, then **`REMOTE_ADDR`**). Picks the first **public** IP when `filter_var` is available (`FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE`); otherwise falls back to the first syntactically valid IP. **`X-Forwarded-For`** (and similar) may contain a comma-separated list; each candidate is trimmed and tested.
- **`get_identitystring($ip, $sessionname)`** — Builds **`IDENTITY`** = first three IPv4 octets (class-C style) + `"-"` + **session cookie name** (e.g. `PHPSESSID`), *not* the unique session token. Docblock states intent: identify users **without** relying on cookies when combined with DB lookup.
- **`detectID($sessionname, $allowhostiplogins, $identitystring)`** — Resolves **`SESSIONID`** from **`$UNTRUSTED`**, then **`$_GET`**, **`$_POST`**, **`$_COOKIE`** (in that order). Optional **`matchip`**: if config requires it, loaded **`SESSIONID`** must match a row in **`livehelp_users`** whose **`ipaddress`** shares the same class-C as the current client (mitigates session leakage via referer links). If **`$allowhostiplogins`** and no ID yet: **`SELECT sessionid FROM livehelp_users WHERE identity='…' AND cookied='N'`** — **cookieless** recovery keyed by **`IDENTITY`** string.
- **`identity(...)`** — After **`detectID`**, if still empty, assigns **`SESSIONID` = md5(uniqid($client_ip . date…))** and may **`setcookie($sessionname, …)`** when cookies are allowed (`usecookies`, operator exceptions). **`USER_AGENT`** and **`REFERER`** are stored on the identity array for display/tracking. Optional separate **`cookieid`** cookie when **`usertracking`** / **`rememberusers`** — distinct from **`SESSIONID`**.
- **Two browsers on one PC:** Each browser has its **own cookie jar** (or passes its own **`SESSIONID`** in URL/body). **`detectID`** therefore yields **different `SESSIONID` values** → **different `livehelp_users` / track rows** even at the same IP. The raw **`IDENTITY`** string can match across browsers (same class-C + same cookie **name**); separation is by **session id**, not by **`IDENTITY` alone**.

### 3.4 Migrated mirrors (Lupopedia services)

When comparing behavior without opening **`craftysyntax-reference/`**:

| Area | Location |
|------|----------|
| **Embed** | `lupo-database/lupopedia/content/lupo-app/Services/CraftySyntax/LegacyLiveHelpJs.php` |
| **Session identity (partial)** | `LegacySessionIdentity.php` |
| **Buffer / flush** | `LegacyBufferStreaming.php`, `LegacyIsFlushDetection.php` |
| **Doctrine (narrative)** | `lupo-docs/channels/doctrine/legacy-import/CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md`, `CRAFTY_SYNTAX_BUFFER_STREAMING_DOCTRINE.md` |

Ports must match **`craftysyntax-reference/`** semantics above, not only migrated copies.

### 3.5 `gc.php` — probabilistic housekeeping and session-end rollups

**Config flags:** Where this section quotes Crafty as **`tracking=='Y'`**, **`reftracking`**, etc., see **§4.2** — Lupopedia uses **`TINYINT`** (**`1`** = on, **`0`** = off), not string **`'Y'`**.

**Source:** `craftysyntax-reference/gc.php` (included from hot paths such as **`image.php`** after **`require_once("gc.php")`**). Crafty uses **random thresholds** so cleanup and analytics aggregation **amortize** across requests instead of one cron.

**A. Deep clean + stale footprint flush (≈ 1 in 501 hits, `rand == 7`)**

- Deletes aged rows from **`livehelp_channels`**, **`livehelp_messages`**, daily/monthly **referrers**, **visits**, **keywords**, **paths** (`livehelp_paths_monthly`, `livehelp_paths_firsts`), **`livehelp_visits_monthly`**, bounded by config **`maxmonths`** / **`maxdays`**.
- If **`tracking=='Y'`**: for each **`livehelp_visit_track`** row with **`whendone`** older than ~12 hours, calls **`archivefootsteps(sessionid)`** (rollup before delete), then deletes old **`livehelp_visit_track`** rows.
- **`OPTIMIZE TABLE`** on analytics tables (non–txt-db-api).

**B. Visitor session timeout + referer/path rollup (≈ 1 in 12 hits, `rand == 4`)**

- **Operators (desktop):** if **`lastaction`** older than **`operatorstimeout`**, set **`isonline='N'`** and clear **`livehelp_operator_channels`**.
- **Operators (mobile):** same using **`sessiontimeout`**.
- **Visitors (`isoperator='N'`):** if **`lastaction`** older than **5 minutes**:
  - **`stopchat(sessionid)`** (ends chat state, transcripts, sets **`askquestions='Y'`** on user row).
  - If **`reftracking=='Y'`** and **`camefrom`** set: **`archivepage`** into **`livehelp_referers_daily`** / **`livehelp_referers_monthly`**.
  - If **`tracking=='Y'`**: **`archivefootsteps(sessionid)`** — rolls **`livehelp_visit_track`** into **visits** trees and **paths** tables, then removes that session’s track rows.
  - **`archiveuser(sessionid)`** — may call **`archivefootsteps`** again if tracking (defensive), **`archiveidentity`** / **`archivekeywords`** when configured, then **`DELETE`** visitor from **`livehelp_users`** and related **`livehelp_channels`**.
- Deletes very old **`livehelp_messages`** (auto-invite noise).

**C. Previous-month cap / prune (≈ 1 in 999, `rand == 7`, tracking on)**

- If referer/visit/keyword **monthly** row counts exceed **`maxreferers`** / **`maxvisits`** / **`topkeywords`**, deletes low-**`levelvisits`** rows and/or **`recursive_delete_pages`** on **`livehelp_referers_monthly`** / **`livehelp_visits_monthly`** (graph-shaped trees).

**D. Current-month bloat trim (≈ 1 in 999, `rand == 17`, tracking on)**

- When daily/monthly table **row counts** exceed **`maxrecords`**, bulk-delete low-**`levelvisits`** slices from referer/visit tables.

**E. “Forgot to log out” operators (≈ 1 in 5, `rand == 3`)**

- Reset stale **`chataction`** for idle operators.
- If **`showedup`** stale: insert **`livehelp_operator_history`** “Stopped Monitoring Traffic”, set **`isonline='N'`**, **`status='offline'`**.
- If **`authenticated='Y'`** but **`lastaction`** older than **`operatorssessionout`** (desktop) or **`sessiontimeout`** (mobile): insert **Logout** history row, set **`authenticated='N'`**, **`isonline='N'`**, **`status='offline'`**.

**F. Chat window abandoned (every include, not random)**

- Visitors in **`status='chat'`** with **`chataction`** older than ~**1.5 minutes**: **`stopchat(sessionid)`**.

**Session-end analytics core (`functions.php`):**

- **`archivefootsteps`**: walks **`livehelp_visit_track`** in time order; **`archivepage`** into **`livehelp_visits_daily`** / **`livehelp_visits_monthly`** (URL tree, once per unique page per session for “first” path stats); increments **`livehelp_paths_monthly`** and **`livehelp_paths_firsts`** for edges **`previousrecno → recno`** ( **`exit_recno`** ); adds synthetic **END** edge (`exit_recno=0`); deletes **`livehelp_visit_track`** for that **`sessionid`**.
- **`archivepage`**: builds/updates hierarchical **pageurl** / **`parentrec`** rows in the target table (daily or monthly), **`directvisits`** / **`levelvisits`**, **department** scope, query-string leaf rules (keep last 5 variants).

Lupopedia must implement **equivalent semantics** on **`lupo_*`** targets named in **`lupo-docs/database/lupopedia/tables/migrations/*_migration.md`** (e.g. **`livehelp_visit_track_migration.md`**, **`livehelp_paths_firsts_migration.md`**, **`livehelp_paths_monthly.md`**, **`livehelp_referers_daily_migration.md`**, **`livehelp_users_migration.md`**, **`livehelp_operator_history_migration.md`**), using **BIGINT UTC** timestamps and **application-layer** logic (no DB triggers). Probabilistic scheduling may use the same hitched include pattern or a documented substitute (e.g. CLI worker) if **behavior and caps** remain equivalent.

### 3.6 Auto-invite and visitor-composed questions (reference)

- **`autoinvite($identity,…)`** (`craftysyntax-reference/functions.php`, invoked from **`image.php`**): for visitors in eligible **`status`**, skips **`ignoreips`**, checks **online operators** with **`auto_invite='Y'`** in department; reads **`livehelp_visit_track`** for **page count** and **time on site**; matches rows in **`livehelp_autoinvite`** (`department`, **`visits`**, **`seconds`**, **`page`** / **`referer`** regex against current page and **`camefrom`**); sends **layer** (`sessiondata` invite) or **request** + **`livehelp_messages`** line. Table mapping: **`livehelp_autoinvite_migration.md`**.
- **Visitor questions:** **`user_questions.php`** (and related Q/A surfaces) use **`livehelp_users.askquestions`**; **`stopchat`** sets **`askquestions='Y'`** when a chat ends so visitors can compose questions. Table mapping: **`livehelp_questions_migration.md`**, **`livehelp_qa_migration.md`**. **Naming / session row semantics** for visitors ( **`username`**, **`isnamed`**, **`sessiondata`** ) are **not** “auth users”—see **§3.8**.

### 3.7 Localization reference — Crafty **`lang/`** packs (14 locales)

Crafty Syntax shipped **~14 operator/visitor string packs** as PHP files under **`craftysyntax-reference/lang/`** (e.g. **`lang-English.php`**, **`lang-Spanish.php`**, **`lang-German.php`**, **`lang-Greek.php`**, **`lang-Portuguese_Brazilian.php`**, …). Each file defines a **`$lang`** array:

- **`$lang['charset']`** — per-locale **HTTP / document charset** (legacy packs differ: e.g. **UTF-8** vs **`ISO-8859-1`** in the reference tree). Setup **`setup.php`** discovers packs by scanning the **`lang/`** directory for **`lang-*.php`** (excluding the empty template **`lang-.php`**).
- **`$lang['txt1']`**, **`$lang['txt2']`**, … — **hundreds of keyed** UI strings (admin labels, visitor chat copy, auto-invite text, etc.) selected by the configured **`speaklanguage`** (or equivalent) at runtime.

**Parity intent for Lupopedia:** end users must be able to **switch visible language** among at least the **same class** of locales Crafty supported (English, Spanish, German, …), with **correct charset / encoding** for output and forms. Full product requirements and storage strategy are in **§7.9**—this subsection is the **historical reference** only.

### 3.8 Visitor sessions and display names — Crafty **`livehelp_users`** vs Lupopedia **`lupo_sessions`**

In Crafty Syntax, a **visiting chat user** is **not** a logged-in account. The **live session** is anchored by **`sessionid`** on a **`livehelp_users`** row (**`isoperator='N'`**) that carries **tracking**, **chat state**, **`username`**, **`isnamed`**, **`sessiondata`** (answers from **`livehelp_questions`** forms), **`askquestions`**, **`lastaction`**, path/analytics via **`livehelp_visit_track`**, etc. Operators see a **name**; that name is a **claimed display handle**, not federation identity.

**`user_questions.php` (`makenamed=Y`)** — When the visitor completes the department question form, a question field with **`fieldtype`** = **`username`** supplies **`$newusername`** (trimmed, length-limited, escaped). **Uniqueness:** Crafty loops: **`SELECT * FROM livehelp_users WHERE username='…'`** ( **`numrows()`** ); while a collision exists, the candidate becomes **`$original . '_' . $countnum`** (incrementing **`$countnum`**) until a free handle is found. Then **`UPDATE livehelp_users … SET isnamed='Y', askquestions='N', username='…', sessiondata='…' WHERE sessionid='…'`** (see **`craftysyntax-reference/user_questions.php`** ~171–200).

**Lupopedia mapping (normative for implementers and AI agents):**

| Crafty (`livehelp_users` visitor row) | Lupopedia |
|--------------------------------------|-----------|
| **`sessionid`** | **`lupo_sessions.session_id`** — same string anchor for embed + tracker + GC |
| **`username`** + **`isnamed`** | **`lupo_sessions.name_key`** + **`is_named`** (`TINYINT` **0/1**) — already in **`install_new_lupopedia.sql`**; **`App\Auth\Session`** exposes **`getNameKey()` / `setNameKey()`** (Model A DB-backed session). |
| **`sessiondata`** (question answers) | **`lupo_sessions.metadata`** (JSON) **or** a doctrine-approved table keyed by **`session_id`** — follow **`livehelp_questions_migration.md`** / **`livehelp_qa_migration.md`**, not ad-hoc columns. |
| **`askquestions`** | Map to **`metadata`** or a **`lupo_*`** column per migration docs; **must** preserve the **stopchat → ask questions → makenamed** flow. |

**Uniqueness of `name_key`:** Enforce in **application PHP** with the **same collision algorithm** as Crafty (probe **existing `name_key`** for **other** active visitor sessions, then **`_` + suffix**). **`Session::setNameKey()`** today writes a **single** value—**must** be extended (or wrapped in a **visitor-name service**) before **`user_questions.php`** parity ships so two visitors cannot steal each other’s handle. **Do not** use DB FKs/triggers; **no** “AI guess” of uniqueness without a **SELECT**-first loop.

**Paths and GC:** **`livehelp_visit_track`** / **`archivefootsteps`** semantics in **§3.5** / **§7.5** remain keyed by **`session_id`** (Crafty **`sessionid`**). Session end rolls **entry/exit pages** and path aggregates **for that session**—the **same** anchor row the operator uses for **display name**.

### 3.9 Analytics rollups — Crafty **many tables** vs Lupopedia **`lupo_*`** column shapes

In **Crafty Syntax**, visits, paths, and referers were spread across **multiple** legacy tables by **purpose** and **grain**—for example **`livehelp_visits_monthly`**, **`livehelp_paths_monthly`**, **`livehelp_paths_firsts`**, **`livehelp_referers_daily`**, **`livehelp_referers_monthly`**, plus **`livehelp_visit_track`** for the raw per-hit stream—each with its own key columns (e.g. **`dateof`** **YYYYMM** for monthly trees, **`visit_recno`** / **`exit_recno`** for path edges).

**Lupopedia** uses a **different physical layout** in **`install_new_lupopedia.sql`** while targeting the **same behavioral** outcomes (raw events → GC → aggregates; **PRD 11**). **Do not** assume a **one-table-for-one** mapping from Crafty when writing **`gc.php`** / **`archivefootsteps`** ports—**read the DDL** and the per-table **`livehelp_*_migration.md`** files first.

| Role | Illustrative **`lupo_*`** shape (prefix = **`LUPO_TABLE_PREFIX`**) |
|------|---------------------------------------------------------------------|
| Raw / per-event navigation rows | **`visits`** — append-style rows; **`is_processed`** for rollup passes |
| Daily visit buckets | **`visits_daily`** — **`visit_ymd`** (BIGINT **YYYYMMDD**), per-actor/day counters |
| Daily referer buckets | **`referers_daily`** — **`referer_domain`**, **`visit_ymd`**, **`visit_count`** |
| Path / flow aggregates | **`paths`** — **`year_num`**, **`month_num`**, **`day_num`**, **`count_num`** on the **same** table (plus enter/exit linkage), **not** only separate “monthly-only” tables as in Crafty |
| Path summary rollups | **`paths_summary`** — ties to **`path_id`** |
| Referer detail rows | **`referers`** — **`date_ymd`**, **`visits`**, content linkage (see DDL) |

**Binding rule:** Implementers must **reconcile grains** (Crafty **`livehelp_paths_monthly`** keyed by **`dateof`** vs **`lupo_paths`** **Y/M/D** + **`count_num`**) and **never** copy-paste Crafty **`INSERT` targets** without mapping to the **actual** **`lupo_*`** columns. When in doubt, trace **`craftysyntax-reference/gc.php`** and **`functions.php`** (`archivefootsteps`, `archivepage`) for **intent**, then implement against **`lupo_*`** per **§4.1**.

### 3.10 Operator console shell — Crafty **`live.php`** (frames) vs Lupopedia **iframe / div**

**Source:** **`craftysyntax-reference/live.php`** — the **logged-in operator** (admin actor) UI is a **nested HTML frameset** (not mobile **`mobile/live.php`**):

| Region | Crafty file | Role |
|--------|-------------|------|
| **Top** (~52px) | **`admin_options.php?tab=live`** | Toolbar / options |
| **Middle-left, top** (~32px) | **`admin_rooms.php`** | Thin strip (room / context) |
| **Middle-left, main** (*) | **`admin_connect.php`** | **Entry** into the **live chat transport** — **does not** render the transcript itself; **`window.location.replace`** to the active transport (see below) |
| **Middle-right** (317px) | **`admin_users.php`** | **Visitor list** — who is on the site / in queue |
| **Bottom** (~155px) | **`admin_chat_bot.php`** | **Tabs** for **each visitor** (**`channelsplit`**), **compose** row, **`PREVIEW:`** `<select name=previewsetting>` (who sees **typing-in-progress** — operator, visitor, both, or none — **§3.11**), shortcuts to **`admin_chat_{chattype}.php`**, **`chat_color.php`** |

**`admin_connect.php` + `CSLH_Config['chatmode']`** (default **`flush-xmlhttp-refresh`**) — The **first** segment of **`chatmode`** (`xmlhttp` \| `flush` \| other) selects **`is_xmlhttp.php`**, **`is_flush.php`**, or **`admin_chat_refresh.php`**, which then loads **`admin_chat_xmlhttp`**, **`admin_chat_flush`**, or **`admin_chat_refresh`** in the **connection** frame — the **stacked / unified transcript** for **all** active visitor conversations (multi-chat).

**`is_xmlhttp.php`** — **Capability detection:** if **`XMLHTTP_supported`**, **`window.location.replace`** to **`{scriptname}_xmlhttp.php`** (e.g. **`admin_chat_xmlhttp.php?…`** with merged query string); otherwise the **`try`** index advances and **`chatmodes[$try]`** selects **`is_flush`** / **`_refresh`** — **same logical page**, **different** PHP endpoint (**`_xmlhttp`** vs **`_refresh`** vs flush) as **fallback** when **`xmlhttp`** is unavailable. **Do not** drop this **layered** behavior in Lupopedia (**§3.0**, **§7.4** transport fallbacks).

**Per-visitor colors (not chosen by the visitor):** Tab backgrounds and text colors come from **`livehelp_operator_channels`** — **`channelcolor`**, **`txtcolor`**, **`txtcolor_alt`** — keyed to the **operator–visitor channel** row (assigned when the visitor joins / ordering of channel rows; operators may edit via **`chat_color.php?id=`**). Message rendering joins these colors in **`functions.php`** / **`admin_chat_*.php`** for **`saidfrom` / `saidto`**. **Lupopedia** maps to **`lupo_*` operator–channel** (or successor) columns per **`livehelp_operator_channels_migration.md`** — **not** free-form visitor CSS.

**4.1.0 requirement:** Reproduce **this** layout **semantically** using **`iframe`** and/or **CSS grid** / **div** regions (**no** `<frameset>` — **§3.0**): **one** main **connection** area showing **all** active chats with **distinct backgrounds per conversation**, **visitor tabs** + **compose** row below ( **`admin_chat_bot`** behavior), **visitor list** in a **side column** — same **information architecture** as **`live.php`**. See **PRD 18** for chat display details.

### 3.11 Visitor typing preview — floating layer (**`writediv`** + **DynLayer**)

Crafty showed **live keystrokes** to the operator in a **small floating DHTML window** (not a full message until send). This is **not** optional polish for **4.1.0** parity when **`show_typing`** (config) and operator **`typing_alert`** allow it.

**Data path (reference):**

- **`image.php`** — **`what=startedtyping`**: upserts a **`livehelp_messages`** row with **`typeof='writediv'`** holding the **current** draft text for **`channel` + `saidfrom`** (visitor). **`what=donetyping`**: inserts the **final** chat line, then **`DELETE … WHERE typeof='writediv'`** to clear the ephemeral rows.
- **`xmlhttp.php`** — polling returns **`LAYER`**-typed payloads (vs **`HTML`**) so **`admin_chat_xmlhttp.php`** can merge **typing** content separately from committed transcript lines (**`showmessages(…, 'writediv', …)`**).

**Operator UI (reference):** **`admin_chat_xmlhttp.php`** / **`admin_chat_refresh.php`** — **`javascript/dynapi/js/dynlayer.js`**, **`DynLayer('UserIsTypingDiv')`**, **`update_typing()`** builds HTML into the layer, **`starttyping()`** on timer. Lang string **`$lang['istyping']`**. **`CSLH_Config['show_typing']`** gates site-wide behavior.

**Operator control — who sees “typing in progress” (reference: `admin_chat_bot.php`):** Next to the compose area, a **`<select name="previewsetting">`** labeled **`PREVIEW:`** lets the **operator** choose whether **live typing preview** is shown **only on the operator console**, **also to the visitor**, **visitor-side only**, or **disabled**. Crafty encodes this as **integer `1`–`4`** (defaults from **`livehelp_users.showtype`**; if **`show_typing != 'Y'`** the UI forces **`4`** = none). English **`lang`** strings: **`txt221`** “Operator and Visitor”, **`txt222`** “Operator(is typing) and Visitor”, **`txt223`** “Visitor Only”, **`txt224`** “None”. **JavaScript** uses **`document.chatter.previewsetting.value`** when firing **`startedtyping`** to **`admin_image.php`** so **operator keystrokes** can be **mirrored** or **hidden** on the visitor chat per selection (**`includeself`** in **`xmlhttp`** polling when **`previewsetting` is 1 or 2**). **Lupopedia** must preserve this **actor-controlled** visibility matrix (same four modes + persistence in **`lupo_*`** user/operator row per migration), not a single global on/off.

**Lupopedia:** Implement the **same** behavior with **`lupo-includes/js/dynapi/`** ( **§3.0**, **§8** ) or a **positioned div** with identical **show/hide/update** semantics—**no** npm-only widgets. Map **`livehelp_messages`** **`writediv`** → **`lupo_*` dialog/message** table per **`livehelp_messages_migration.md`** (ephemeral row type or equivalent **application** convention). **Prepared statements**, **no** SQL string concat for user text.

## 4. Data and migration doctrine

### 4.1 No guessing schema or mappings (binding for all IDE agents and implementers)

Before writing or changing **any** SQL, PHP that touches the database, importers, or analytics rollups:

1. **Read** the relevant per-table migration markdown under **`lupo-docs/database/lupopedia/tables/migrations/`** (e.g. **`livehelp_users_migration.md`**, **`livehelp_visit_track_migration.md`**) so you know **exact** legacy → **`lupo_*`** table and **column** names.
2. **Read** **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`** (and seed where applicable) for the **authoritative current** DDL for **`lupo_*`** tables—TOON/JSON exports are **derived**; they do **not** replace reading install SQL when in doubt.
3. **Read** **`lupo-docs/doctrine/migrations/`** index and narratives (**`livehelp_migrations_readme.md`**, **`lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md`**) for cross-table intent and naming conventions.

**Forbidden:** Inventing or assuming column/table names from memory, from partial snippets, or from “probably the same as Crafty” reasoning. **TOON files and JSON alone are not sufficient** to skip the steps above—they are helpers after the canonical sources are consulted.

Parity descriptions elsewhere in this PRD that cite Crafty identifiers (**`livehelp_*`**, **`CSLH_Config['tracking']=='Y'`**, etc.) describe **reference behavior** in **`craftysyntax-reference/`**. **Lupopedia code must use the mapped `lupo_*` names and types from the migration + install SQL**, not copy-paste Crafty DDL.

### 4.2 Crafty `Y` / `N` config flags → Lupopedia `TINYINT`

In **`craftysyntax-reference`**, many toggles are **CHAR** comparisons (e.g. **`$CSLH_Config['tracking']=="Y"`**, **`reftracking`**, **`usertracking`**, **`keywordtrack`**, **`usecookies`**, **`rememberusers`**). In Lupopedia these are represented as **`TINYINT`**: **`1`** means **enabled** (Crafty **`'Y'`**), **`0`** means **disabled** (any other Crafty value). Application code must compare **integers** (or strict bool casting from config), **not** string **`'Y'`/`'N'`**, unless a compatibility shim is explicitly documented.

### 4.3 Mapping locations

- **Index:** **`lupo-docs/doctrine/migrations/livehelp_migrations_readme.md`**
- **Per-table mapping files:** **`lupo-docs/database/lupopedia/tables/migrations/`** (e.g. **`livehelp_visit_track_migration.md`**, **`livehelp_paths_firsts_migration.md`**, **`livehelp_paths_monthly.md`**, **`livehelp_autoinvite_migration.md`**, **`livehelp_questions_migration.md`**)
- **Runtime:** Implement GC, rollups, autoinvite, and Q/A against **`lupo_*`** schema using those mappings—**not** raw **`livehelp_*`** at runtime. **Rollup table shapes** differ from Crafty’s split tables; see **§3.9** before coding **`archivefootsteps`**-class logic.
- **Import:** **Crafty Syntax 3.7.5 → Lupopedia** only until 4.1.0 gate is satisfied; no alternate upgrade path is introduced by this PRD.

## 5. Non-negotiable technical constraints

All features in scope must respect root rules, including:

- **PHP 7.4+** through supported 8.x in shared core paths (avoid PHP 8.0+ only syntax in those paths per `php-7-4-compatibility.md`).
- **No new external dependencies** beyond what is already vendored in-tree (e.g. **Composer is not** introduced for this work).
- **Shared hosting:** no reliance on root-only server features; subdirectory install (`LUPOPEDIA_PUBLIC_PATH`) must remain valid.
- **PDO_DB / application logic:** no DB triggers, FKs, stored procedures; **BIGINT UTC** timestamps; explicit column lists on INSERT where applicable.
- **Schema literacy (non-negotiable):** **§4.1** — no guessed table/column names; migration markdown + **`install_new_lupopedia.sql`** before code; TOON/JSON are supplementary only.
- **Implementation modernization:** **§3.0** — framesets → **iframe**/div; **prepared** SQL via **DatabaseFactory**/PDO_DB; **`$UNTRUSTED`** (or equivalent) for all request input; **dynapi/dynlayer** for DHTML layers; keep **fallbacks**.
- **Image uploads and admin visibility:** **§5.1**–**§5.2** — avoid naive **as-is** user binaries; **GD** (etc.) missing → **user image uploads disabled** for **4.1.0** plus **security warnings** in admin, not silent degradation.

### 5.1 Auto-installer deployment and unknown shared-hosting environment

One-click / auto-installers deploy the **entire** project under a **subdirectory** of the customer’s docroot. **Until install-time (and often after), the runtime is unknown:**

| Unknown | Requirement |
|---------|-------------|
| **Host OS** | Code and paths must work on **Windows** and **Linux** hosting. Use **`LUPOPEDIA_PATH`**, **`LUPOPEDIA_PUBLIC_PATH`**, **`LUPO_TABLE_PREFIX`**, and doctrine-resolved paths—**no** hardcoded drive letters, **no** assuming `/` vs `\` without `DIRECTORY_SEPARATOR` or PHP’s portable APIs. |
| **Exact PHP version** | Product floor is **PHP 7.4+** through supported **8.x** on shared core paths (constitutional rule). **Do not** assume a specific minor; optional features must **probe** at runtime (`PHP_VERSION`, `extension_loaded`, `function_exists`) and degrade gracefully. |
| **PHP build / extensions** | Shared hosts omit or compile out extensions. **Never** assume **GD**, **Imagick**, **mbstring**, **fileinfo**, **OpenSSL**, etc. **Probe** with `extension_loaded` / `function_exists`; use **`try` / `catch`** where APIs can throw. **Image uploads (4.1.0 gate):** **Storing user uploads “as-is” without decode/re-encode is a known high-risk pattern** (polyglots, misleading types). **When GD** (or a **product-approved** equivalent image library) **is present:** **decode and re-encode** to a narrow output format and store only that artifact. **When GD is missing:** user image uploads **MUST be disabled entirely** with a **clear** operator-facing message that **GD** (or equivalent) is **required** before uploads can be enabled. **No** “magic-byte only” validation **without** decode/re-encode is permitted for **4.1.0** visitor/operator uploads. **No** silent acceptance of raw user binaries as “safe images.” Hosters **must** enable **GD** (or equivalent) before user image uploads are permitted. **Always** show **§5.2** admin **security warnings** aligned with this rule. Non-image fallbacks (e.g. skip thumbnails only for **trusted** system assets) are product-defined and must not weaken visitor-upload rules. |
| **Dependencies** | **No Composer**, **no Laravel**, **no npm/webpack-required** runtime for certification scope. Use only libraries **already in-tree** under **`lupo-includes/`** (and documented exceptions such as **PHPMailer** in **§8**). **No** pulling framework stacks into the tree for this work. |
| **PHP namespaces** | **`namespace`** is used for **first-party** Lupopedia code (e.g. under **`app/`**, namespaced modules) per project conventions. **Do not** introduce namespaced **third-party** libraries via Composer; there is **no** parallel “vendor PSR-4 tree” for external packages. |
| **`mod_rewrite` / `.htaccess`** | **Optional for correctness.** When **`AllowOverride`** and Apache-style rewrites work, the installer **may** write marker-based **`.htaccess`** for clean URLs. When they do **not**, **all** routes (including chat/API surfaces scoped to this PRD and **PRD 28**) **must** remain reachable via **`index.php`** and **query-parameter** (or **`PATH_INFO`**) fallbacks (**PRD 00 §2**, **§9.5**). Installer **must not** fail solely because **`.htaccess`** cannot be written or applied; **warn** and document fallback URLs. **Search indexing** is **not** a product assumption (**PRD 00 §18**). |

**Preflight / installer UX:** The wizard should **report** PHP version and extension status; missing **security-relevant** extensions must be called out as **warnings** (not buried footnotes). See **§5.2** for ongoing admin visibility.

### 5.2 Admin security warnings (missing extensions and upload posture)

Operators on shared hosting often **do not** read server `phpinfo()`. Lupopedia **must** surface **clear, persistent security-oriented warnings** in **admin** (e.g. dashboard banner, dedicated **System / Security** or **Health** panel) when recommended PHP pieces are absent.

**Minimum expectations**

- **GD** (or the product’s chosen image extension): if missing, show a **warning** that **user image uploads are disabled** per **§5.1** until **GD** (or equivalent) is available, and that enabling **GD** on the host is **required** to turn uploads on—**no** silent “trust raw upload” and **no** magic-byte-only path for **4.1.0**.
- **Other extensions** the product relies on for **hardening or correctness** (e.g. **`fileinfo`** if used for MIME sniffing, **OpenSSL** for TLS-related features): list each missing item with **one-line risk** (what breaks or what attack surface widens), not only “feature unavailable.”
- Warnings remain visible until resolved or **explicitly dismissed** per product policy (dismissal should **log** `actor_id` + **BIGINT UTC** timestamp per constitutional rules if stored).

**Installer + runtime:** Show extension gaps at **end of install** and again on **admin login** (or equivalent) so migrating between hosts surfaces regressions.

## 6. Root script compatibility contract

These **must exist at project web root** (same level as `index.php` / public entry) for **Crafty-compatible** embedding and hoster expectations:

| File | Role |
|------|------|
| **`livehelp_js.php`** | Serves **online/offline** (or equivalent) **image/state** behavior compatible with Crafty Syntax expectations; on click, opens a **department-aware** templated **live help chat** window. Behavior must remain recognizable to existing Crafty integrators. |
| **`lupopedia_js.php`** | **Semantic monitoring / advanced widget** entry (see **PRD 28** and **`04_lupopedia_js_foundation.md`**); coexists with `livehelp_js.php` without breaking legacy embeds. |

Implementation may delegate internally to `lupo-includes` services; **public names and URLs** above are the compatibility surface.

## 7. Operator console and real-time chat (Crafty parity)

### 7.1 Unified operator view

- **Requirement:** Admin UI shows **all active chats** the **logged-in operator actor** participates in, **on one page**, with **distinct visual backgrounds (or borders)** per conversation—matching the spirit of Crafty’s multi-session operator view.
- **Shell parity:** **§3.10** — **`live.php`** = **top bar** + **`admin_rooms`** strip + **`admin_connect`** (transport redirect) + **`admin_users`** visitor column + **`admin_chat_bot`** tabs/compose; Lupopedia uses **iframe/div**, **not** framesets, but **same** regions and **`channelsplit`** semantics.
- **Colors:** Assigned per **operator–visitor channel** ( **`channelcolor` / `txtcolor`** in Crafty **`livehelp_operator_channels`** ), **not** visitor-picked; stable **order-of-arrival** / channel assignment + optional operator edit (**`chat_color.php`** equivalent).
- **Transport:** **`admin_connect`** → **`is_xmlhttp` / `is_flush` / refresh** → **`admin_chat_*`** parallel endpoints; preserve **XMLHTTP-first** with **documented** fallback chain (**§3.10**).
- **Typing preview:** Floating **DynLayer** / **`UserIsTypingDiv`**; **`PREVIEW`** control on **`admin_chat_bot`** sets whether **visitor** and/or **operator** see in-progress typing — **§3.11** (not optional for parity when config enables **`show_typing`**).
- **Reference implementation (legacy):** `admin_chat_xmlhttp` patterns and **`xmlhttp.php`**-style polling/streaming in Crafty 3.7.5; Lupopedia analogs include **`LegacyAdminChatXmlHttp`** and related services under **`lupo-database/lupopedia/content/lupo-app/Services/CraftySyntax/`** (verify paths on branch).

### 7.2 Actor model (hybrid operator)

- **Actors** are **hybrid user/agent** identities: when a **human** is logged in, **chat messages** are attributed to **human operation**; **AI** assists only where product rules allow—no silent substitution of human sends.

### 7.3 Operator shell (`admin.php`)

- **Requirement:** **`admin.php`** (and related admin routing) must be **redesigned** for the **4.1.0** operator experience: it must credibly host **unified multi-chat**, visitor lists, settings, and Crafty-parity tools—not only legacy layouts carried forward from early Lupopedia. Exact UX is product-owned; **readiness for hoster demo** is part of the gate.

### 7.4 Visitor-facing feature parity (checklist)

The following **Crafty Syntax** capabilities must be **present or explicitly superseded** with hoster-approved equivalents:

- [ ] **Visits reporting (`data.php` tab 3 parity)** — **`data_visits.php`**: month/year (and department) views, **Top Urls** vs **Domain Tree**, backed by visit aggregate tables (Crafty: **`livehelp_visits_monthly`**). Lupopedia: **PRD 11** / **`lupo_visits`**, **`lupo_visits_daily`**, and equivalent operator UI.
- [ ] **Path funnel reporting (`data.php` tab 4 parity)** — **`data_paths.php`**: **All Visit Paths** vs **First Visit Paths**, parent/child navigation via **`visit_recno`** / **`exit_recno`**, **`livehelp_paths_monthly`** / **`livehelp_paths_firsts`**, gated by **tracking** config. Lupopedia: **`lupo_paths_summary`** (and related) plus UI that supports **drill-down** from entry pages through subsequent clicks.
- [ ] **Per-hit page stream (embed → tracker endpoint)** — Crafty records each page view in **`livehelp_visit_track`** keyed by **`SESSIONID`** (see **`image.php`** insert/updates with `page`, `location`, `title`, `referrer`). Lupopedia must preserve **per-session page sequences** feeding path/visit aggregates, driven by **`livehelp_js.php` / `lupopedia_js.php`** (or successors) the same way the reference tree does.
- [ ] **Referrer and campaign context** — Align with PRD **11** (`lupo_referers_daily`, campaign vars, etc.) where Crafty showed referrers (**`data.php` tab 2**) and marketing fields.
- [ ] **Real visitor list** (“Live Support” — who is on the site now).
- [ ] **Real client IP (`get_ipaddress()` parity)** — Header-ordered, **XFF list aware**, public-IP-preferring resolution as in **`craftysyntax-reference/functions.php`**; store per doctrine (e.g. visit/session rows). Respect privacy/law; document fields.
- [ ] **Session / visitor identity fallbacks** — Parity with **`identity()`** / **`detectID()`**: **SESSIONID** from **`$UNTRUSTED` → GET → POST → cookie**; optional **`matchip`** class-C check against **`livehelp_users`**; **cookieless** recovery via **`IDENTITY`** + **`cookied='N'`** row when **`allow_ip_host_sessions`**; new id = **md5(uniqid(…))** when none found; optional **`cookieid`** when user-tracking cookies enabled. **Different browsers on the same host** remain **distinct visitors** because each has a **different SESSIONID** (separate cookie jars or explicit id in requests)—not because **`IDENTITY`** alone is unique. **`ghost_session`** paths (e.g. credit image) vs full **`image.php`** identity must remain distinguishable. Optional **reverse DNS** when **`gethostnames`** is on. Removing or simplifying this chain requires an **APPROVED** decision and hoster-visible notes.
- [ ] **Real-time transport fallbacks** — Crafty’s stack is **layered**, not “XHR only”: **buffer stuffing / `sendbuffer()`** (IE-era incremental flush), **`chatmode`** such as **`xmlhttp-refresh`** with **per-try** fallback selection (`is_flush` / flush detection), and **image-based polling** for session/state. **4.1.0** parity means **modern transports must not silently drop** these legs until each is **superseded** with tested replacement and documentation (shared hosting still benefits from image/XHR/buffer paths).
- [ ] **Proactive chat invites** — See **§7.6** (**`autoinvite`** / **`livehelp_autoinvite`** semantics).
- [ ] **Typing preview (floating layer + visibility)** — **Operator** sees visitor keystrokes in **`DynLayer`** / **`writediv`** flow; **operator** also chooses via **`admin_chat_bot`**-style **`PREVIEW`** dropdown whether **their own** typing-in-progress is **shown to the visitor** (four modes: both sides, operator+visitor label variants, visitor-only, none — **`previewsetting` / `showtype`**). **§3.11**; **`show_typing`** / **`typing_alert`**; **dynapi** (**§3.0** / **§8**).
- [ ] **Chat transcripts** and **logging**.
- [ ] **Canned messages** (operator and/or department scoped).
- [ ] **Multilingual operator + visitor UI** — Selectable locale (e.g. English → Spanish → German) with **stable message keys** and **correct charset** for responses; parity with Crafty **`craftysyntax-reference/lang/`** behavior. Full rules: **§7.9**.
- [ ] **Emoji / image icons in chat** — Folder-based assets under **`lupo-emoji/{foldername}/`**, message token **`::img|foldername|imagefile::`**; full validation and rendering rules in **§7.8** (see also **PRD 18** for chat display).
- [ ] **Improved visitor chat templates** (modern HTML/CSS) while preserving **embed contract** (§6).
- [ ] **Mobile client chat** — Visitors using **mobile browsers** can initiate and participate in chat (**PRD 35**, **MOBILE_SEPARATION_DOCTRINE.md**):
  - **Separate mobile pages** — not “responsive only” as the sole strategy.
  - **Device detection** at entry → redirect to **`/mobile/...`** (under **`LUPOPEDIA_PUBLIC_PATH`**), e.g. **`mobile/chat.php`**-class routes.
  - **Preserve** legacy Crafty mobile **client** chat behavior until **APPROVED** supersession.
  - **Operator admin** is **not** required on mobile web; operators target **native app** per **PRD 35** (not webview-only admin as end state).
- [ ] **Departments** — Setup, routing, templates, per-department language/options (see PRD **25** and legacy department helpers under **`CraftySyntax/`**).
- [ ] **Operator / user admin** — Crafty-style **users** (operators), permissions, and admin refresh patterns (`LegacyAdminUsers*`, xmlhttp admin surfaces).
- [ ] **Leads** — Lead tracking present in Crafty admin/data; **import + UI** parity or **APPROVED** replacement workflow.
- [ ] **Questions** — Visitor-composed questions and Q/A (e.g. **`user_questions.php`**, **`askquestions`** after **`stopchat`**); see **§7.6** and **`livehelp_questions_migration.md`** / **`livehelp_qa_migration.md`**. Lupopedia may **improve** structure (threads, channels) but must not **silently remove** capability without an **APPROVED** supersession record.
- [ ] **Campaign / attribution tracking** improvements; **search-term tracking** is currently **broken**—either **fix** or **document deprecation** with replacement metrics (no silent removal).

### 7.5 Probabilistic GC and session-end rollups (Lupopedia requirements)

- [ ] **Hitched GC** — On high-frequency visitor/operator endpoints (Crafty: post-**`image.php`** **`require_once("gc.php")`**), run **probabilistic** housekeeping so rollups and pruning **amortize** across traffic (not only cron). Document the chosen probabilities or equivalent **fair** scheduling.
- [ ] **Rollup targets vs Crafty** — **`gc.php`** / **`archivefootsteps`** ports write to **`lupo_*`** tables whose **columns differ** from Crafty’s **separate** monthly/daily tables (e.g. **`lupo_paths`** uses **`year_num` / `month_num` / `day_num` / `count_num`**; **`lupo_visits_daily`** uses **`visit_ymd`**). Follow **§3.9** + **`install_new_lupopedia.sql`**; **no** assumed 1:1 **`livehelp_*` → `lupo_*`** name mapping without migration review.
- [ ] **Stale visit_track rollup** — When raw per-hit rows exceed a **time threshold**, run **`archivefootsteps` equivalent** (§3.5) into **visits** + **paths** aggregates, then remove rolled-up raw rows—mapped to **`lupo_*`** per **`livehelp_visit_track_migration.md`** and path/visit migration docs.
- [ ] **Visitor idle session end (~5 min)** — **`stopchat`** equivalent, **referer** **`archivepage`** when **`reftracking`**, **`archivefootsteps`**, **`archiveuser`** (identity/keyword archival when enabled)—all on **`lupo_*`** rows that replace **`livehelp_users`**, **`livehelp_channels`**, etc.
- [ ] **Operator stale presence** — Timeouts for **monitoring traffic**, **isonline**, **authenticated** session end with **history** rows (Crafty: **`livehelp_operator_history`**) per **`livehelp_operator_history_migration.md`** / actor-session doctrine.
- [ ] **Table caps** — Configurable **max months/days**, **maxreferers**, **maxvisits**, **maxrecords**, **topkeywords**, and **recursive** graph deletes for overfull monthly trees—no unbounded growth on shared hosting.
- [ ] **Abandoned chat** — **`stopchat`** when visitor **`chataction`** exceeds ~**90s** inactivity in **`status='chat'`** (Crafty reference ~1 minute 30 seconds).

### 7.6 Auto-invite and visitor questions (Lupopedia requirements)

- [ ] **Auto-invite rules engine** — Evaluate rules when **`image.php`-class** requests run (visitor eligible **`status`**, **`ignoreips`**, online operators with **`auto_invite`**, **`livehelp_autoinvite`** row match on **department**, **min visits**, **min seconds on site**, **`page`** / **`referer`** pattern vs current URL and **`camefrom`**), then apply **layer** vs **chat request** / message insert—per **`craftysyntax-reference/functions.php` `autoinvite()`** and **`livehelp_autoinvite_migration.md`**.
- [ ] **Visitor question UX** — After chat end, allow visitors to **compose questions** (Crafty: **`askquestions`**, **`user_questions.php`** flow); persist per **`livehelp_questions_migration.md`** / **`livehelp_qa_migration.md`** (or improved **`lupo_*`** equivalent with **APPROVED** mapping). **Named visitor** flow (**`makenamed`**, **`username`/`isnamed`**) maps to **`lupo_sessions.name_key` / `is_named`** with **Crafty-style uniqueness** (see **§3.8**); **not** **`lupo_auth_users`**.

### 7.7 UI and stack modernization (Lupopedia checklist)

- [ ] **No framesets** — Operator and visitor shells use **`iframe`** and/or **div** layout (**§3.0**).
- [ ] **PDO_DB + prepared statements only** — **DatabaseFactory** / **`lupo_get_db()`**; named placeholders on all new/port work (**§3.0**, root PDO doctrine).
- [ ] **Untrusted input bucket** — All request-derived values flow through **`$UNTRUSTED`** (or a single documented equivalent) plus modern validation (**§3.0**); **no** legacy SQL escaping on values that are **only** ever bound as parameters (**§3.0** bound-parameter row).
- [ ] **Dynapi / dynlayer** — Layer and invite behavior built on **`lupo-includes/js/dynapi/`**, aligned with **`craftysyntax-reference/javascript/dynapi/js/`** (e.g. **dynlayer**); **no** Canvas/framework swap-out without **APPROVED** decision (**§3.0**, **§8**).
- [ ] **Fallbacks retained** — Buffer / XHR / image legs and other legacy fallbacks remain until explicitly superseded (**§3.0**, **§7.4**).
- [ ] **Unknown shared-hosting environment** — **§5.1**: subdirectory install, Windows/Linux portable paths, runtime PHP/extension probing; **no** Composer/Laravel/npm runtime deps; namespaces **first-party only**. Image handling follows **§5.1** / **§5.2** (no naive **as-is** user uploads).
- [ ] **Admin security / extension warnings** — **§5.2**: prominent admin UI when **GD** (and other listed security-relevant extensions) are missing; explain **upload and hardening** risk; installer + ongoing visibility.
- [ ] **Localization layer** — **§7.9**: runtime language selection, keyed strings, charset policy; installer or admin lists available locales like Crafty **`lang/`** discovery.

### 7.8 Emoji / image-picker icons (`lupo-emoji/` and `::img|…::` tokens)

Lupopedia replaces legacy smilie storage with **filesystem-backed** sets and an explicit **chat token** so picker UI and transcripts stay aligned.

**Asset layout**

- Images live under **`lupo-emoji/{foldername}/`** (one subdirectory per icon set / theme). Exact folder list is **discovered at runtime** from the filesystem (or documented allow-list), not hardcoded guesses.

**Chat encoding**

- When a user picks an image in chat, the message body includes a token of the form:

  `::img|foldername|imagefile::`

  where **`foldername`** matches a subdirectory under **`lupo-emoji/`** and **`imagefile`** is a filename **within that folder** (e.g. `wave.gif`). The delimiter grammar is fixed; implementation must **parse strictly** and reject malformed tokens.

**Sanitization and security (mandatory)**

- **Path traversal:** Reject **`..`**, path separators, URL schemes, null bytes, CR/LF, and any segment that would escape **`lupo-emoji/`** after canonical resolution (**realpath** / rooted join under the emoji root).
- **Injection:** Reject or strip patterns that could break out of the token into **HTML** or **JavaScript** (e.g. `<script`, `javascript:`, event-handler-like substrings inside segments). Message bodies must still go through normal **HTML/context escaping** where rendered as text outside approved image substitution.
- **Allow-list:** Restrict **`foldername`** and **`imagefile`** to a **small safe charset** (e.g. letters, digits, `_`, `-`, and a single `.` for extension—product-owned regex). Reject everything else.
- **Existence:** After sanitization, the resolved file must **exist** on disk under **`lupo-emoji/{foldername}/`** and be an **allowed image type** (e.g. gif/png/jpeg/webp—product-defined list).
- **Double validation:** Validate on **write** (when accepting chat input / API) **and** on **read/render** (when building transcript HTML or `src` URLs) so stale or tampered DB rows cannot bypass checks.
- **Write-time validation failure:** The message (or API request body) **MUST be rejected entirely**. An invalid **`::img|…::`** token **MUST NOT** be persisted. The client **MUST** receive an explicit error indicating an invalid image reference. **No** silent stripping, **no** best-effort storage of a “cleaned” token, **no** partial accept.

**Legacy import**

- Crafty **`livehelp_smilies`** semantics and mapping: **`lupo-docs/database/lupopedia/tables/migrations/livehelp_smilies_migration.md`**.

**Checklist**

- [ ] Picker only offers folders/files that pass the rules above.
- [ ] Stored messages contain only validated **`::img|…::`** tokens for emoji rows (no raw HTML from picker).
- [ ] Public **`img` URLs** or static routes for emoji assets do not expose directory listing beyond approved files.

### 7.9 Multilingual UI (Crafty **`$lang`** parity — files vs database)

Crafty proved that **one PHP array per locale** scales to **~14 languages** and **shared hosting** without Composer. Lupopedia must restore **user-visible** language switching (operator and visitor surfaces in certification scope), not English-only hardcoding.

**Non-negotiable behaviors**

- **Selectable locale** — Site or operator setting chooses **one active UI language** at a time (per-site default + overrides where product rules allow, e.g. department or user preference). Switching must change **all** gated strings in scope (admin live-help, visitor chat/embed copy, auto-invite text, major **`data.php`**-class labels)—not a partial mix without an **APPROVED** exception list.
- **Stable keys** — Implementations use **opaque stable keys** (Crafty-style **`txtN`** or **semantic** keys such as `auto_invite.title`) and map them to **per-locale** values. **No** scattering raw English in templates without going through the lookup layer.
- **Charset / encoding** — Legacy Crafty packs used **mixed** charsets (**UTF-8** vs **`ISO-8859-1`**, etc.); reference files live under **`craftysyntax-reference/lang/`**. **Lupopedia default posture:** **UTF-8** end-to-end for **new** packs and HTTP **`Content-Type`** / HTML **`charset`** must match the stored bytes. **Importing** or **wrapping** a legacy pack that is not UTF-8 requires **explicit transcoding** or **documented** legacy charset per locale—**no** silent mojibake.
- **Discovery** — Install wizard and/or admin **lists available locales** from a **defined root** (filesystem glob like **`lang-*.php`**, or table rows, or JSON under an approved path)—same **discoverability** spirit as Crafty **`setup.php`** **`opendir('lang')`**.
- **Constitutional storage** — If strings move to the **database**, schema stays **dumb**: **no** triggers/FKs/procedures; **BIGINT UTC** metadata if rows are versioned; **explicit** column lists on **INSERT**; IDs per **PK naming** / allocator doctrine. **No** “magic” ORM resolution—load in PHP, cache in memory if needed.

**Open product decision (record before 4.1.0 implementation freeze)**

| Approach | Notes |
|----------|--------|
| **Filesystem packs** (Crafty-like) | One file (or one JSON/PHP array) per locale under an in-tree root (e.g. **`lupo-lang/`** or namespaced under **`lupo-includes/`**). **Pros:** diff-friendly, no extra tables, works offline, matches reference. **Cons:** large files, need discipline for merges. Prefer **splitting by domain** (admin vs visitor) over a single unmaintainable monolith if file size hurts. |
| **Database table(s)** | Rows = locale + key + value (+ optional namespace). **Pros:** runtime edits without deploy, hoster customization. **Cons:** seed/migration surface, backup/export story, must not put **logic** in the DB. |

**Neither** approach is mandated in this PRD until an **APPROVED** decision (channel **`decisions/`** or **`lupo-docs/status/`**) picks one (or a **hybrid**: defaults on disk, overrides in DB). **4.1.0 gate:** the **chosen** approach is **implemented**, **English** ships as default, and **at least two** additional locales from the Crafty reference set (e.g. **Spanish**, **German**) are **demonstrably switchable** in the UI—or deferral is **APPROVED** with hoster-visible rationale.

**Checklist**

- [ ] Lookup API (PHP) resolves **`key` + `locale` → string** for all gated UI in certification scope.
- [ ] Charset and **`Content-Type` / HTML** declaration aligned; UTF-8 documented as default for new translations.
- [ ] Decision artifact: filesystem vs DB (or hybrid) + path/table names + seed strategy.
- [ ] Parity spot-check against **`craftysyntax-reference/lang/`** for major flows (install language list, visitor chat shell, one **data** tab).

## 8. Approved in-tree libraries (no new package managers)

| Need | Location (canonical in this repo) |
|------|-------------------------------------|
| **Mail** | **`lupo-includes/PHPMailer/`** (PHP mailer—not under `js/`). |
| **DHTML layers / motion** | **`lupo-includes/js/dynapi/`** — **canonical runtime** copy for invites, floating layers, and Crafty-equivalent UI. **Reference** tree: **`craftysyntax-reference/javascript/dynapi/js/`** (including **dynlayer**). **Not optional** for certification-layer parity: do **not** substitute Canvas, speculative “modern CSS only” rewrites, or npm animation frameworks for this behavior unless an **APPROVED** decision documents testing and fallback equivalence. |
| **Chat emoji / picker images** | **`lupo-emoji/{foldername}/`** — filesystem icon sets; chat token **`::img|foldername|imagefile::`**. Sanitization and rendering: **§7.8**. |
| **UI locales (pending decision §7.9)** | **Filesystem:** proposed in-tree root such as **`lupo-lang/`** (or doctrine-resolved path) with one pack per locale—**or** **`lupo_*`** string table(s) per **§7.9** **APPROVED** decision. Reference packs: **`craftysyntax-reference/lang/`**. |

Do not add npm/webpack requirements for certification scope.

## 9. Softaculous / one-click checklist (product-level)

Exact Softaculous XML/checklists evolve by vendor; **completion** for this PRD means:

1. **Packaging:** Single payload that installs on **subdirectory** docroot with documented **`lupopedia-config.php`** (or successor) placement.
2. **Preflight:** PHP version check (7.4+ floor), **extension** capability check with **§5.1** / **§5.2** **security warnings** for missing **GD** (etc.), writable paths documented; subdirectory / `LUPOPEDIA_PUBLIC_PATH` behavior explicit.
3. **Post-install:** Link to operator login, **livehelp_js.php** test page snippet, and **`lupopedia_js.php`** test snippet.
4. **Uninstall / reset:** Documented behavior (even if “manual DB drop” in 4.1.0—must be explicit).
5. **Evidence:** Stored decision or `lupo-docs/status/` artifact with hoster name, date, and PASS/FAIL matrix.

## 10. Completion criteria (4.1.0 gate)

**4.1.0 may be tagged only when:**

1. §6 root scripts behave per contract on a **clean** install from **Crafty 3.7.5** import path.
2. §7.1 operator unified chat meets **functional** parity with Crafty reference (colors/panels, multi-session).
3. §7.4 checklist is **all checked** or each gap has an **APPROVED** decision recording deferral; **§7.5** (GC / rollups), **§7.6** (autoinvite / visitor questions), **§7.7** (modernization + **§5.1** / **§5.2** unknown-host + admin extension warnings), **§7.8** (emoji / **`lupo-emoji`** tokens), and **§7.9** (multilingual UI + locale decision) are **all checked** or deferrals **APPROVED**.
4. §7.3 **`admin.php`** redesign meets **demo-ready** operator workflow (multi-chat, core Crafty-parity screens)—or an **APPROVED** decision documents intentional deferral with evidence.
5. §5 constraints verified by static/runtime smoke on **PHP 7.4** (or documented minimum aligned with root PRD) and a current 8.x.
6. §9 evidence artifact exists (Softaculous or equivalent hoster certification), and **§2.1** vendor acceptance is documented where one-click listing is claimed.
7. **Constitutional compliance verification:** A **documented audit** for the release candidate (recommended: **LILITH**, **actor_id 2**) confirms, for **new** schema and **new** application code in scope for **4.1.0**, at least: **no** foreign keys, triggers, or stored procedures; stored times use **BIGINT UTC** `YYYYMMDDHHIISS` (no DB `DATETIME`/`TIMESTAMP` automation); **no** `AUTO_INCREMENT` / `SERIAL` on registry-style or doctrine-forbidden tables; **INSERT** lists columns explicitly where doctrine applies; **no** hard deletes on canonical tables (**soft delete** fields per root rules); **no** ORM magic or lazy loading in new paths; agent/tooling outputs that affect certification remain **deterministic** where doctrine requires it.

## 11. Out of scope (for this PRD)

- New **Lupopedia→Lupopedia** upgrade migrations (deferred per constitutional **single install until 4.1.0** framing).
- **Non–live-help** product features unless they block certification (handle via separate PRD).

## 12. Traceability (required)

- **§7.4** / **§7.5** / **§7.6** / **§7.7** / **§7.8** / **§7.9** checklist items and **§10** completion rows **must** be traceable in **`lupo-docs/versions/4.0.94/TODO.md`** (or the **current version** `TODO.md`) via a **dedicated subsection** (e.g. **PRD 33 / Softaculous / 4.1.0 gate**). **No** checklist item may be marked **complete** in release evidence without a matching backlog line marked **done** (or an **APPROVED** deferral decision cited there).
- Each backlog line **must** cite this PRD and the **§** (or numbered checklist row) it closes; **must** name an **owner** **`actor_id`** (or human-orchestrator + faucet pair per ACT001); **must** carry **status** (`planned` / `in_progress` / `complete` / `blocked`). On closure, **must** point to **evidence** (decision path, `CHANGELOG` entry, or channel artifact) stamped with **BIGINT UTC** `YYYYMMDDHHIISS` per constitutional time doctrine.
- Where tasks are also tracked in **database or channel tables**, **task_id** (if used) **must** be a **deterministic BIGINT** per reserved-ID / allocator doctrine—not a random or client-supplied ID.
- Changes to **`install_new_lupopedia.sql`** before 4.1.0 remain **fresh-install only** per migration doctrine.

## 13. Review and audit record

**LILITH** (**actor_id 2**, non-interfering reviewer per **LIL001** / **`lupo-rules/root/lilith-noninterference-doctrine.md`**) reviewed this PRD after revisions to **§5.1**, **§7.8**, **§10** (criterion 7), **§12**, and new reference sections **§3.7–§3.11** / **§7.9**.

| Field | Value |
|-------|--------|
| **Verdict** | **APPROVED** — ready for **WOLFIE** (**actor_id 1**) or product owner **sign-off** when desired |
| **Accuracy (reported)** | ~98/100 |
| **Constitutional violations** | None reported |
| **Closed items (audit)** | **§5.1** GD-missing disables uploads; **§7.8** write-time reject invalid emoji tokens; **§10** constitutional audit criterion; **§12** required traceability + evidence UTC |

**Header `status`:** Set to **`approved`** in **`lupopedia.headers`** (2026-04-03 UTC) — this records **product documentation** approval of the gate text; **§7–§10 checklist execution** toward installer/hoster certification remains tracked in **`lupo-docs/versions/4.0.94/TODO.md`** per **§12**. LILITH’s prior verdict in this section remains **review documentation** for accuracy; orchestrator may add further ratification artifacts under **`implementations/33_.../decisions/`** if needed.

**Implementation workspace:** **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`** — structured folder (from **`implementations/_template`**); LILITH audit import under **`status/`**; formal **`decisions/`** reserved for ratified **`DECISION_*`** files.

### 13.1 LILITH final audit — ready for code (UTC `20260403222833`)

| Field | Value |
|-------|--------|
| **Auditor** | LILITH (**actor_id 2**) |
| **Accuracy** | **100/100** (final read on approved gate text) |
| **Constitutional violations** | None |
| **Security / privacy** | None additional beyond in-PRD requirements (mobile §7.4 aligns **PRD 35** / **MOBILE_SEPARATION_DOCTRINE.md**) |
| **Verdict** | **APPROVED** — PRD **33** is the authoritative **4.1.0** release gate; **§7.4** checklist is the **Crafty parity implementation roadmap**; **§10** rows are the **tagging gate**; **§12** requires matching **`TODO.md`** lines before marking work complete |

**Recommendations (closed in documentation):**

- **§7.4** includes **mobile client chat** (separate mobile pages, device detection → **`/mobile/...`**, legacy Crafty mobile client parity; operator admin not required on mobile web — **PRD 35**).
- **Before coding:** ensure **`lupo-docs/versions/4.0.94/TODO.md`** lists one traceable row per **§7.4** / **§7.5** / **§7.6** / **§7.7** / **§7.8** / **§7.9** item and each **§10** criterion, each with **PRD 33** reference, **§** pointer, **owner `actor_id`**, **`planned` / `in_progress` / `complete` / `blocked`**, and **evidence** (path + BIGINT UTC) on closure.

**Prior §13 table (~98/100)** remains historical context for the first structured audit pass; **§13.1** records the **final** “ready for code” disposition after **§7.4** mobile item and **TODO.md** mapping discipline were confirmed.

---

## 14. WordPress distribution patterns (study findings)

**Study session UTC:** `20260404061540`  
**Reference tree:** `lupo-archive/legacy/wordpress-reference/` (WordPress **6.9.4** per `wp-includes/version.php` in this checkout)  
**Scope:** Read-only pattern extraction for **Softaculous / FTP / multi-environment** alignment. **Do not** copy WordPress source into Lupopedia (GPL; different product).

### 14.1 Evidence from reference code (summarized)

| Area | WordPress behavior (this tree) | Lupopedia alignment |
|------|-------------------------------|---------------------|
| **`.htaccess`** | Not part of the **core** tree as a default root file; **`save_mod_rewrite_rules()`** in `wp-admin/includes/misc.php` writes **`get_home_path() . '.htaccess'`** when **`got_mod_rewrite()`** and permalinks need rules, using **`insert_with_markers()`** (`# BEGIN WordPress` … `# END WordPress`), **`flock`**, create-if-missing via **`touch`**. Parallel path: **`iis7_save_url_rewrite_rules()`** writes **`web.config`** on IIS. | **Shipped:** `InstallWizardHtaccessWriter` writes full docroot + `lupo-database/.htaccess` after config when the environment allows; **Softaculous zip** strips dotfiles (**`build_softaculous_package.sh`**). **§14.4 (LILITH):** adopt **`# BEGIN LUPOPEDIA` … `# END LUPOPEDIA`** marker merge; **§14.6:** **attempt** install-time write when Apache-compatible and writable — **runtime** chat/API **must** still work **without** rewrites (**PRD 00 §2**, **§9.5**). |
| **Config** | `wp-admin/setup-config.php` loads **`wp-config-sample.php`** lines, substitutes keys, writes **`wp-config.php`**; errors if **`ABSPATH`** not writable. | **Done:** install wizard writes **`lupopedia-config.php`** from form + DB session. **§14.4 (LILITH):** add **`lupo-config/lupopedia-config-sample.php`** and wizard UX when config path is not writable. |
| **Empty dirs / perms** | **`wp_mkdir_p()`** in `wp-includes/functions.php`: **`mkdir( $target, $dir_perms, true )`** where **`$dir_perms`** inherits parent **`stat()['mode'] & 0007777`** or defaults **0777**, then **`chmod`** loop if **umask** altered effective perms. **`wp_upload_dir()`** can create uploads path via **`wp_mkdir_p`**. | **Done:** installer **`mkdir(..., 0755, true)`** for `lupo-cache`, `lupo-logs`, `lupo-uploads`, `lupo-tmp`. **§14.4 (LILITH):** on failure, **detect and warn** with parent path/mode; **no** auto-**`chmod`**. |
| **Paths / server quirks** | **`wp_fix_server_vars()`** normalizes **`REQUEST_URI`** for IIS / CGI; **`load.php`** loads version + extension requirements early. | **Done:** **`LUPOPEDIA_PATH`**, **`LUPOPEDIA_PUBLIC_PATH`**, subdirectory install doctrine. **Open:** IIS **`REQUEST_URI`** parity audit vs Crafty/live help. |
| **PHP / extensions** | **`$required_php_version`** = **7.2.24**; **`$required_php_extensions`** = **`json`**, **`hash`**; **`wp_check_php_mysql_versions()`** emits **HTTP 500** + plain message if version or extension missing; separate **`mysqli`** / **`db.php`** drop-in path. | **Done:** install preflight **PHP 7.4+** / **pdo_mysql** / **json** (constitutional **7.4+** floor). **Open:** explicit **hash** (if needed), **GD** / upload policy per **§5.1** gate rows. |
| **Package / dotfiles** | Upstream distribution excludes VCS; consumer tarball historically has **no** `.git`. This repo’s **`lupo-archive/legacy/wordpress-reference/`** has no root **`.gitignore`** in checkout; plugin may ship **`.htaccess`** (e.g. Akismet). | **Done:** **`SOFTACULOUS_PACKAGE_BUILD.md`** + packager exclude **`lupo-archive/`** (contains **`legacy/wordpress-reference/`** study tree), strip **`.?*`** files and dirs. |

### 14.2 Action items (from study, merged with current repo state)

| Item | Status | Notes |
|------|--------|--------|
| Omit **`.htaccess`** from distribution zip; generate at install | **Done** | **`InstallWizardHtaccessWriter`**, **`build_softaculous_package.sh`** |
| Omit all other dotfiles / IDE dirs from zip | **Done** | Sanitize step + rsync excludes |
| Installer-created runtime directories (no **`.gitkeep`** in zip) | **Done** | **`ensureRuntimeDirectories`** |
| Document packaging | **Done** | **`SOFTACULOUS_PACKAGE_BUILD.md`**, README **WordPress reference** section |
| **`.gitkeep`** removal from **git** tree (not only zip) | **Approved — implement** | **LILITH §14.4 Q6** — remove tracked **`.gitkeep`**; stop generating in maint scripts; see **`wordpress_pattern_implementation_tasks_20260404.md`** |
| Marker-based **`.htaccess`** merge (**`insert_with_markers`**-style) for hand-edited servers | **Approved — implement** | **LILITH §14.4 Q1** — **`# BEGIN LUPOPEDIA` / `# END LUPOPEDIA`** |
| Install-time **`.htaccess`** (not lazy) | **Done + keep** | **LILITH §14.4 Q2** — **attempt** immediate write when host allows; **§14.6** clarifies **no** hard dependency on rewrites for API/chat (**PRD 00 §2**, **§9.5**) |
| **`web.config` / Nginx** snippet generation or verified doc | **Approved — docs** | **LILITH §14.4 Q3** — hosting documentation; optional **`web.config.example`** reference only, not auto-installed |
| **`lupopedia-config-sample.php`** | **Approved — implement** | **LILITH §14.4 Q4** — WordPress-style manual install path |
| **`mkdir`** failures / strict parent perms | **Approved — implement** | **LILITH §14.4 Q5** — detect and warn; no auto-**`chmod`** |
| Extend preflight extension matrix (**GD**, etc.) | **Open** | Map to **PRD 33** checklist rows (unchanged by WordPress study) |

### 14.3 Primary files read (for future deep dives)

| File | Relevance |
|------|-----------|
| `lupo-archive/legacy/wordpress-reference/wp-admin/includes/misc.php` | **`insert_with_markers`**, **`save_mod_rewrite_rules`**, IIS **`web.config`** saver |
| `lupo-archive/legacy/wordpress-reference/wp-includes/load.php` | **`wp_check_php_mysql_versions`**, early bootstrap, **`wp_fix_server_vars`** |
| `lupo-archive/legacy/wordpress-reference/wp-includes/version.php` | **`$required_php_version`**, **`$required_php_extensions`**, **`$wp_version`** |
| `lupo-archive/legacy/wordpress-reference/wp-includes/functions.php` | **`wp_mkdir_p`**, **`wp_upload_dir`** |
| `lupo-archive/legacy/wordpress-reference/wp-admin/setup-config.php` | **Sample → live config** workflow, writability checks |
| `lupo-archive/legacy/wordpress-reference/wp-admin/install.php` | Installer steps / UX (loads **upgrade.php**, translation install) |
| `lupo-archive/legacy/wordpress-reference/wp-admin/includes/class-wp-debug-data.php` | **`SERVER_SOFTWARE`**, **`extension_loaded`**, **`.htaccess`** marker strip, **`wp_is_writable`**-style reporting |
| `lupo-archive/legacy/wordpress-reference/wp-includes/PHPMailer/SMTP.php` | **`stream_socket_client`** vs **`fsockopen`**, scoped **`set_error_handler`** |

### 14.4 LILITH resolutions (WordPress study questions)

**Resolution UTC:** `20260404061932`  
**Auditor:** LILITH (**actor_id 2**). **Canonical answer artifact:** `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md`.  
**Implementation backlog:** `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/wordpress_pattern_implementation_tasks_20260404.md`.

| Q | Decision |
|---|----------|
| **Q1** Marker-based **`.htaccess`** | **YES** — only replace content between **`# BEGIN LUPOPEDIA`** and **`# END LUPOPEDIA`**. |
| **Q2** Lazy vs immediate **`.htaccess`** | **IMMEDIATE attempt** at install (when Apache-compatible path is writable) — do not defer to a “later optional” admin step for the **try**. **Clarified §14.6:** chat/API **must** work **without** `mod_rewrite` via **`index.php`** + query (or **`PATH_INFO`**) fallbacks; installer **must not** fail if **`.htaccess`** is absent or ignored. |
| **Q3** IIS **`web.config`** | **Documentation only** in the shipped product; optional **`web.config.example`** as reference, not auto-installed. |
| **Q4** Config sample | **YES** — add **`lupo-config/lupopedia-config-sample.php`**; wizard guides manual copy when writes are blocked. |
| **Q5** Permissions | **Detect and warn** on **`mkdir`** / parent mode issues; **no** automatic permission “repair.” |
| **Q6** **`.gitkeep`** | **YES** — remove from the **git** repository; installer continues to create **`lupo-cache/`**, **`lupo-logs/`**, **`lupo-uploads/`**, **`lupo-tmp/`**. |

**Question thread** (historical filename): `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_061540_QUESTION_wordpress_distribution_patterns_unresolved.md` — **status:** resolved (edges point to the answer artifact). **Study report:** `status/wordpress_study_20260404.md`.

### 14.5 Canonical pattern distillate (Lupopedia doctrine)

**`lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md`** is the **single** Lupopedia doctrine file for WordPress-derived, multi-environment patterns. It cites **`lupo-archive/legacy/wordpress-reference/`** paths and **line ranges** (WordPress **6.9.4** tree in this checkout) and maps them to Lupopedia surfaces (e.g. **`InstallWizardHtaccessWriter.php`**, installer preflight). **IDE agents should read it first** instead of re-scanning the full WordPress tree. Constitutional pairing: **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** **§15** (multi-environment), **§18** (no search-index assumption), **§2** / **§9.5** (routing without rewrites).

### 14.6 Constitutional clarification — `.htaccess`, rewrites, and search indexing

**UTC:** `20260405205506`. **Does not invalidate** LILITH §14.4 decisions; **binds implementers** to root PRD routing and exposure rules.

- **`.htaccess` / `mod_rewrite`:** **Best-effort** for clean URLs. **Core behavior** (installer success, operator surfaces, **PRD 28**-class APIs consumed by widgets/embeds) **must** function when rewrites are **unavailable** — **`index.php`** + **query parameters** (and/or **`PATH_INFO`**), per **PRD 00 §2** and **§9.5**.
- **§14.4 Q2 “IMMEDIATE”:** Interpret as **“attempt at end of install when environment matches,”** not **“installation blocked until rewrite-protected pretty URLs exist.”**
- **Search engines:** Lupopedia is **not** modeled as a public SEO site; **`robots.txt` / `noindex`** are **SHOULD** per **PRD 00 §18** — orthogonal to `.htaccess`.

---

This file complies with Lupopedia Constitutional Root Rules.
