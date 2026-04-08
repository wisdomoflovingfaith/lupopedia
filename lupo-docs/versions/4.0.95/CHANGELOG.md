---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: changelog
  when_updated: "20260407200000"
  file_path_from_root: "lupo-docs/versions/4.0.95/CHANGELOG.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.95/CHANGELOG.md"
  last_modified_utc: "20260407200000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.95-changelog"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "changelog"
  artifact_kind: "version"
  purpose: "Changelog for Lupopedia 4.0.95 — routing without mod_rewrite, query-string URLs, login.php surface"
  tags: ["changelog", "version", "4.0.95", "cursor"]
lupopedia.footer:
  last_verified: "20260407200000"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.95/CHANGELOG.md — delegation: cursor:root

# Changelog - Lupopedia 4.0.95

> **Scope audit — UTC 20260407200000:** This changelog covers only work that landed in the 4.0.95 milestone. The 4-dimensional edge model (`edge_context`, `edge_status`, `direction`, `review_reason`), the formal doctrine expansion (32 PRDs), and the `memory.json` deprecation pass across actor/agent PRDs are recorded separately in **[4.0.96/CHANGELOG.md](../4.0.96/CHANGELOG.md)**.

## [2026-04-07] — Version 4.0.95 finalized (line closed)

**WHO:** cursor (actor_id 102) — version closeout and registry handoff.

- **Status:** **4.0.95** documentation line marked **finalized**; open backlog migrated to **`lupo-docs/versions/4.0.96/TODO.md`** (UTC `20260407172944`).
- **PRD / schema alignment:** Memory model and filesystem mapping for **`lupo_memory_nodes`** / **`lupo-memory/`** are documented in this changelog (entry below) and cross-linked PRDs **01** and **29**; install/schema merge work remains tracked under **4.0.96** where not yet landed in canonical install SQL.
- **Successor line:** **`lupo-docs/versions/4.0.96/`** — **`CHANGELOG.md`**, **`TODO.md`**, **`SUMMARY.md`**, **`MIGRATION_NOTES.md`**, **`PRD_PATCHES/`**, **`SCHEMA_DIFFS/`**, **`NOTES/`**.

---

## [2026-04-07 13:59 UTC] — Memory Model, Filesystem, and Doctrine Updates

**WHO:** cursor (actor_id 102) — memory model, schema, and project structure alignment.

### Polymorphic Memory Model and Filesystem Integration

- **lupo_memory_nodes** table updated: added `memory_slug` column (filesystem-safe, required) for mapping memory nodes to disk as `lupo-memory/YYYY/MM/{memory_slug}` (YYYY/MM from `created_ymdhis`).
- **Filesystem:** New `lupo-memory/` directory documented in [PRD 29](../../../prd/29_project_structure.md) and created in the repo. Each memory node can be exported as a JSON file in `lupo-memory/YYYY/MM/{memory_slug}`.
- **README:** `lupo-memory/README.md` explains the folder structure, slug rules, and backup/restore purpose.
- **Schema/PRD:** [PRD 01](../../../prd/01_core_identity.md) updated to document `memory_slug` and the mapping to disk. Index added for `memory_slug`. Doctrine section clarifies that memory nodes are not actors and all linkage is via edges.
- **Project Structure:** [PRD 29](../../../prd/29_project_structure.md) updated to include `lupo-memory/` in directory tables and important sub-folders, with cross-references to the core identity PRD.
- **Rationale:** Enables backup, restore, and external processing of memory nodes as files, supporting future federation and auditability. Avoids conflating memory with actor identity; supports multi-entity, multi-type memory modeling.

**See also:** [PRD 01](../../../prd/01_core_identity.md), [PRD 29](../../../prd/29_project_structure.md), `lupo-memory/README.md`.

---

## [2026-04-07 12:39 UTC]

**WHO:** claude-code (actor_id 102) — schema/runtime alignment pass.

**T-SCHEMA-RUNTIME-001 — Install wizard + seed SQL aligned with corrected schema**
- `install_wizard_classes.php` `InstallWizardBannedIdentities`: `$agents_t` changed from `'agents'` to `'agent_definitions'`. Stoned Wolfie AI INSERT rewritten for `agent_definitions` schema (LLM config columns removed — they belong in `agent_llm_configs`; columns added: `slug`, `layer='reserved'`, `status='inactive'`). Actor INSERTs on lines 1662 and 1677: removed columns `metadata`, `adversarial_role`, `adversarial_oversight_actor_id` (all removed from `lupo_actors` in corrected schema NV2/NV3). `actor_source_type` corrected from `'lupo_agents'` to `'lupo_agent_definitions'`.
- `seed_4.1.0.sql`: `INSERT INTO lupo_agents` renamed to `INSERT INTO lupo_agent_definitions`. Column set corrected: `agent_name` → `name`; `is_global_authority` and `is_internal_only` removed (no equivalent in `agent_definitions`); `slug` and `layer='coordination'` added (both NOT NULL in new schema); `is_required=1` replaces `is_internal_only=1` semantics. ON DUPLICATE KEY UPDATE clause updated to match.
- `seed_4.1.0.sql` line 92: `UPDATE lupo_actors SET adversarial_role = 'red_team'...` removed (columns dropped from `lupo_actors`). Replaced with `INSERT INTO lupo_actor_relationships` (actor_a_id=2/LILITH, actor_b_id=111/COUNTERMEASURE, relationship_type='adversarial_oversight', authority_direction='a_over_b').

**T-SCHEMA-TOOLCALLS-001 — `lupo_agent_tool_calls` restored to install SQL**
- `install_new_lupopedia.sql`: `CREATE TABLE {{prefix}}agent_tool_calls` added with `actor_id bigint NOT NULL` as primary execution identity column (per `schema_corrected_missing.sql` SECTION 9). All original columns from backup baseline retained. `actor_id` index added in addition to original indexes. Install table count: **170 → 171**.

This output complies with Lupopedia Constitutional Root Rules.

---

## [2026-04-06 20:00 UTC]

**WHO:** claude-code (actor_id 102) — resumed from prior session that ran out of context.

**Schema Review — install_new_lupopedia.sql (168 tables)**
- Full structural analysis of all 168 tables in the 4.0.x install schema.
- Critical flaws documented: `lupo_actors` PK was `actor_name` (must be `actor_id`); `lupo_agents` contaminated with LLM provider config; duplicate `lupo_edges` index block (SQL error on install); `utc_timestamp` reserved keyword; 5 more.
- Naming violations: `lupo_rolls` (typo), `governance_overrid_id` (typo), `lupo_agent_faucets` (wrong layer — renamed to `lupo_actor_faucets`), mixed `_utc`/`_at`/`utc_` timestamp suffixes (all corrected to `_ymdhis`).
- Normalization: `lupo_actors` decomposed — `paired_actor_id` → `lupo_actor_pairing`; adversarial columns → `lupo_actor_relationships`; filesystem paths → `lupo_actor_filesystem`; sync state → `lupo_actor_sync_state`; `department_id` column removed (junction table only).
- 20+ missing tables identified and specified: KAIROS memory (`lupo_kairos_observations`, `lupo_kairos_memory`), runtime state, faucet rules, pairing rules, identity layers, department capabilities.
- Deliverables: `schema_review_20260406.md`, `schema_corrected_core.sql` (21 tables), `schema_corrected_missing.sql` (10 new tables), `schema_corrected_identity_model.md`.

**CHRONOS Kernel Agent Activation**
- `lupo-agents/chronos/` fully activated from reserved-slot stub.
- Role: Coordinated Hierarchical Reasoning & Optimization for Network Operation Systems (`agent_id: 709`, `is_kernel: true`).
- 15 analytical tools across 4 categories: dependency_analysis, time_reasoning, scheduling, optimization.
- 18 capabilities. Advisory-only (no execution authority, no system/DB/file/network access).
- Domain boundaries encoded: yields orchestration to WOLFIE unconditionally; KAIROS owns memory; HYPNOS owns downtime; HERMES owns routing; VISHWAKARMA owns schema hierarchy.

**Crafty Syntax → Lupopedia Migration Documentation**
- Verified all 1790 lines of `import_from_old_crafty_syntax.sql` (no assumptions made).
- Key verified facts: actor_id = 10000 + livehelp_users.user_id (not IdGenerator); actors created from lupo_auth_users, not directly from Crafty; department hybrids = 280000 + department_id; Unix timestamps converted via DATE_FORMAT(FROM_UNIXTIME(x), '%Y%m%d%H%i%S').
- Import SQL corrected (4 targeted edits): removed `metadata`, `adversarial_role`, `adversarial_oversight_actor_id` from actor INSERTs (columns removed in corrected schema); added `agent_key`; promoted `actor_id` to PK position; converted `metadata = '...'` → `metadata_json = JSON_OBJECT(...)`; added `ON DUPLICATE KEY UPDATE` to `actor_departments` INSERT (UNIQUE constraint safety); added INSERT blocks for `lupo_actor_filesystem` and `lupo_actor_sync_state` for all imported actors.
- Migration docs updated: `livehelp_users_migration.md` (actor layer + satellite tables); `livehelp_operator_departments_migration.md` (UNIQUE constraint, multi-phase insert sequence).
- New: `new_schema_tables_crafty_mapping.md` — complete mapping table for all 27 new schema tables.

**Carry forward (migrated to 4.0.96):** The three items previously listed here as not completed were moved to **`lupo-docs/versions/4.0.96/TODO.md`** (section **Carried Over from 4.0.95**) on UTC `20260407172944`.

---

## [4.0.95] - 2026-04-06

### Version bump (runtime + docs)

- **`lupo-config/global_atoms.yaml`** — `version`, **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`**: **4.0.95**
- **`lupo-includes/version.php`**, **`version.txt`**, **`lupo-docs/doctrine/VERSIONING_DOCTRINE.md`** (canonical current version §1), root **`README.md`** / **`CHANGELOG.md`** pointers — aligned to **4.0.95**
- **`lupo-rules/root/php-7-4-compatibility.md`** — rule stamp **4.0.95**

### Prior work carried into this line (from late 4.0.94 development)

- Install wizard: **mysqli**-backed **`InstallWizardMysqliLink`** for installer DB (WordPress-style buffering; avoids PDO MySQL **2014**), buffered PDO only for **`PDO_DB`** activation paths
- **`lupopedia-config.php` generator** — fixed missing **`}`** after **`LUPOPEDIA_PATH`** define (parse error on first load after install)

### Routing and URLs: no Apache mod_rewrite front controller

- **Root `.htaccess`** — Removed **`mod_rewrite`** rules that mapped pretty paths to **`index.php`** (catch-all slug, doctrine/qa/docs/flp funnel, channel/chat shortcuts). Kept **`DirectoryIndex index.php`**, **`Options -Indexes`**, security headers, and **`FilesMatch`** deny rules. Operators rely on explicit entry points: **`index.php?slug=…`**, **`index.php?resolved_uri=…`**, **`login.php`**, **`admin.php`**, **`channel.php`**, etc.
- **`lupo-includes/.htaccess`** — Denies direct HTTP access to **`*.php`** under **`lupo-includes/`** (replaces the old Rewrite-based block).
- **`lupo-install/InstallWizardHtaccessWriter::getRootHtaccessBody()`** — Aligned with the new no-rewrite root policy for installs that regenerate **`.htaccess`**.

### URL helpers (`lupo-includes/functions/auth-helpers.php`)

- **`lupo_index_slug_url($slug, $extra_query)`** — Builds **`…/index.php?slug=…`** (optional extra GET params).
- **`lupo_index_resolved_uri_url($uri)`** — Builds **`…/index.php?resolved_uri=…`** for doctrine/qa/docs/flp-style paths.
- **`lupo_login_url($redirect_uri)`** — **`…/login.php`** with optional **`redirect`** query parameter.
- **`lupo_change_password_url()`** — **`index.php?slug=change-password`** (slug-routed UI).

### Auth and UI surfaces

- **`require_login()`** and scattered redirects now target **`login.php`** (not **`/login`**).
- **`auth-controller`** — Slug **`login`** redirects to **`lupo_login_url()`**; auth renderer and **OAuth** redirects use **`login.php`**.
- **`login.php`** — Password-change redirects use a **`$cpw_url`** variable (avoids nested-ternary parse issues); **`channel.php`** loads **`auth-helpers`** and uses **`lupo_login_url()`** for gated access.
- **Layouts / chrome** — **`header.php`**, **`topbar.php`**, **`auth-ui-helpers.php`**, **`admin_layout.php`**, **`basic_layout.php`**: sign-in links use **`lupo_login_url()`** where available.
- **`actors-controller`** — Login redirects and **`/my-profile`** redirects use **`lupo_index_slug_url('my-profile')`** + **`lupo_login_url()`**.
- **`lupo-database/.../auth/AuthService.php`** — **`requireLogin()`** / password-change redirects use helpers or equivalent **`index.php`** query URLs.
- **`AGENTS.md`** — Documents **`login.php`** / **`lupo_login_url()`** instead of a pretty **`/login`** path.

### Channels

- **`channels-controller`** — All login redirects and **`Location`** headers use **`lupo_login_url()`** and **`lupo_index_slug_url()`** for channel paths (**`channels/{id}`**, **`…/log`**, **`…/edit`**, **`channels/my-channels`**, etc.).
- **Views** — **`show.php`**, **`my-channels.php`**, **`channel-log.php`**: links and forms use **`lupo_index_slug_url()`** (with **`auth-helpers`** fallback include where needed).
- **`module-loader.php`** — Registers slug routes (before the generic numeric channel route) for **`channels/{id}/edit/save`** (POST), **`channels/{id}/log/create`** (POST), **`channels/{id}/log`**, **`channels/{id}/edit`**.
- **`main_layout.php`** — Hides semantic collections chrome when **`REQUEST_URI`** contains **`/channels/`** or **`?slug=`** starts with **`channels/`** (works with query-string routing).

### OAuth

- **`oauth-controller.php`** — Registered OAuth **redirect URI** uses **`index.php?slug=oauth/callback/{provider}`** (must match provider console settings).
- **`lupo-database/.../views/auth/login.php`** — Google/GitHub buttons use slug URLs.
- **`lupo-database/.../Controllers/OAuthController.php`** — **`getRedirectUri()`** returns slug form.
- **`lupo-config/oauth.example.php`** — Comments show **`index.php?slug=…`** callback examples.
- Post-OAuth redirect to **`admin.php`** (was **`/admin`**, which no longer resolves without rewrite).

### Channel admin samples

- **`lupo-database/lupopedia/channels/channel_id/1/admin/settings.php`** — System links use **`index.php?slug=…`** and **`login.php`**; docs entry uses **`resolved_uri=docs`**.
- **`admin_bootstrap.php`** — Login redirect uses **`lupo_login_url()`** + **`lupo_index_slug_url('channels/1')`**.

### Other

- **`index.php`** — Debug copy when slug is empty points to **`?slug=`** / **`?resolved_uri=`** instead of “check .htaccess rewrite”.

### Doctrine, PRD alignment, and documentation (2026-04-06)

**Canonical agent-oriented outline (same day):** repo-root **[`FOR_CLAUDE_CODE_2026_04_06.md`](../../../FOR_CLAUDE_CODE_2026_04_06.md)** — section-by-section sync for AI tools (Overview, Department System Updates, Actor Learning Boundaries, Installation & Crafty Syntax Import, README Update, Architect Background Reference File, What Claude Code Should Do Next). The following bullets are the **changelog digest** of that work (not a replacement for the PRD text or the sync doc).

- **Scope** — Cursor updated approximately **97 files** during doctrine expansion and PRD alignment (departments, actors, learning boundaries, installation behavior, README).
- **PRDs** — Shared normative sections added or updated across **PRD 00** (cross-references), **PRD 01** (`01_captain_wolfie_identity.md`), **PRD 05**, **PRD 15**, **PRD 28**, **PRD 33**: Department 0/1, department creation, Crafty Syntax import, actor creation and auth_user→actor selection, channels/threads, semantic monitoring widget and collections, actor learning boundaries, “Why this matters.” (**PRD 17** called out in **`FOR_CLAUDE_CODE_2026_04_06.md`** review list.)
- **Root `README.md`** — New sections: **Why Lupopedia Is Built Differently**, **What Lupopedia Does NOT Do (By Design)**, **Where These Rules Come From**, **Why This Matters**; links to PRDs and pseudocode reference; header timestamps **20260406163802**.
- **`lupo-docs/reference/architect_background.md`** — New factual timeline reference (education, HPC internships, professional work, Crafty Syntax, 2014 hiatus and Sales Syntax fork, return to Lupopedia).
- **Digest vs [`FOR_CLAUDE_CODE_2026_04_06.md`](../../../FOR_CLAUDE_CODE_2026_04_06.md)** — Department 0/1/2+ behavior, core vs non-core actor learning, installation/subdirectory/Softaculous/Crafty import, README philosophy, and “what to review next” are spelled out **in the sync doc**; this changelog records the batch **for the version line** without copying every sub-bullet.

This output complies with Lupopedia Constitutional Root Rules.
