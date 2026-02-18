---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/CRAFTY_SYNTAX_STATE_BASED_IMPLEMENTATION_PLAN.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
---

# Crafty Syntax → Lupopedia: State-Based Implementation Plan

**Version:** 1.0  
**Date:** 2026-02-04  
**Status:** Initial analysis complete  

**Canonical project brief (rules, objectives, workflow):** `docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md`

**Authority:** TOON files in `docs/toons/` are the schema source of truth. No inference from MySQL or phpMyAdmin. Schema changes only via migration files; TOON files are not edited directly (regenerated after migrations).

**Upgrade wizard:** The upgrade wizard (which runs the full Crafty Syntax 3.7.5 → Lupopedia import) will be implemented **LAST**, after all Crafty Syntax features are rebuilt and validated. Do not start the wizard work until the final phase.

---

## 1. Executive Summary

This plan implements **all** legacy Crafty Syntax 3.7.5 features inside Lupopedia. Lupopedia is **not** a direct port: schema, semantics, and architecture are improved. The plan:

- Uses **TOON files** as the only authoritative schema source.
- Maps every legacy `livehelp_*` table to Lupopedia tables.
- Identifies **already implemented**, **improved**, **to migrate**, and **missing/unclear** mappings.
- Defines **state-based phases** (CLEAR / HOLD / BLOCKED) with completion criteria.
- Requires **migrations** (SQL ALTER only), **Python scripts** for data, and **feature rebuilds** in the new architecture.
- Respects **doctrine**: no FK, no triggers, no stored procedures, explicit IDs where applicable, BIGINT timestamps `YYYYMMDDHHIISS`, installer-safe layout.

---

## 2. Authoritative Sources

| Source | Purpose |
|--------|---------|
| `docs/toons/*.toon.json` | **Only** schema definition. All column names, types, indexes. |
| `database/migrations/import_from_old_crafty_syntax.sql` | **Actual** legacy → lupo mapping (INSERT/SELECT). |
| `docs/channels/doctrine/CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md` | Doctrine and column-level mapping (cross-check with SQL). |
| `docs/doctrine/CRAFTY_SYNTAX_INTEGRATION_PLAN.md` | Feature checklist (operator panel, livehelp_js, multi-channel, API). |
| `legacy/craftysyntax/` | **Read-only** Crafty Syntax 3.7.5 reference (PHP, JS, flows). |

**Not** used for schema: live MySQL introspection, phpMyAdmin, or inferred relationships.

---

## 3. Legacy Table → Lupopedia Mapping (from import SQL)

Extracted from `import_from_old_crafty_syntax.sql`. **34 legacy tables**:

| # | Legacy Table | Target Table(s) | Migration Type | Notes |
|---|--------------|-----------------|----------------|------|
| 1 | livehelp_autoinvite | lupo_crafty_syntax_auto_invite | INSERT | Column mapping + Y/N→1/0 |
| 2 | livehelp_channels | — | DROPPED | No INSERT; deprecated only |
| 3 | livehelp_config | lupo_modules.config_json (module_id=1) | UPDATE | JSON config |
| 4 | livehelp_departments | lupo_departments, lupo_department_metadata | INSERT | Two targets |
| 5 | livehelp_emailque | — | DROPPED | Out of scope (lupo_crm_lead_message_sends) |
| 6 | livehelp_emails | lupo_crm_lead_messages | INSERT | lead_id=1 for all |
| 7 | livehelp_identity_daily | — | DROPPED | Removed in Lupopedia |
| 8 | livehelp_identity_monthly | lupo_actors | INSERT | Anonymous actors (slug anon-{id}) |
| 9 | livehelp_keywords_daily | — | DROPPED | Removed |
| 10 | livehelp_keywords_monthly | — | DROPPED | Removed |
| 11 | livehelp_layerinvites | lupo_crafty_syntax_layer_invites | INSERT | Column mapping |
| 12 | livehelp_leads | lupo_crm_leads | INSERT | Direct |
| 13 | livehelp_leavemessage | lupo_crafty_syntax_leave_message | INSERT | **Bug:** SELECT uses `id AS leave_message_id`; should be `id AS crafty_syntax_leave_message_id` |
| 14 | livehelp_messages | — | DROPPED | Crafty did not persist post-chat messages |
| 15 | livehelp_modules | — | DROPPED | No INSERT |
| 16 | livehelp_modules_dep | — | DROPPED | Explicit: do not map; Lupopedia controls by admin UI |
| 17 | livehelp_operator_channels | — | DROPPED | No INSERT |
| 18 | livehelp_operator_departments | lupo_actor_departments | INSERT | user_id→actor_id (see gap below) |
| 19 | livehelp_operator_history | lupo_audit_log | INSERT | entity_type='actor', JSON payload |
| 20 | livehelp_qa | lupo_truth_questions, lupo_truth_answers, lupo_collections, lupo_collection_tabs | INSERT | Multi-table; folders→collection_tabs |
| 21 | livehelp_questions | lupo_crafty_syntax_chat_questions | INSERT | required Y/N→1/0 |
| 22 | livehelp_quick | lupo_actor_reply_templates | INSERT | user→actor_id |
| 23 | livehelp_smilies | — | ARCHIVE | No import; token-based emoji in Lupopedia |
| 24 | livehelp_sessions | — | DROPPED | No target |
| 25 | livehelp_users | lupo_auth_users | INSERT | Operators first, then rest; Phase 1 SQL then creates lupo_actors (operator only) and lupo_operators, and fixes lupo_actor_departments.actor_id |
| 26 | livehelp_referers_daily | lupo_unified_referers | INSERT | content_id from legacy |
| 27 | livehelp_referers_monthly | lupo_unified_referers | INSERT | Same |
| 28 | livehelp_visit_track | — | ALTER only | No INSERT in snippet; visits go via daily/monthly |
| 29 | livehelp_visits_daily | lupo_unified_visits | INSERT | |
| 30 | livehelp_visits_monthly | lupo_unified_visits | INSERT | |
| 31 | livehelp_paths_firsts | lupo_unified_analytics_paths | INSERT | |
| 32 | livehelp_paths_monthly | lupo_unified_analytics_paths | INSERT | |
| 33 | livehelp_transcripts | lupo_dialog_threads, lupo_dialog_messages | INSERT | recno→thread_id and message_id; transcript→message_body |
| 34 | livehelp_websites | lupo_federation_nodes | INSERT | id→federation_node_id; DELETE node 0 guard |

---

## 4. What Is Already Implemented (Lupopedia)

- **Auth:** Login, MD5→bcrypt upgrade, redirect-back, session upgrade (anonymous→authenticated), avatar dropdown, operator detection via `lupo_operators`.
- **Schema:** 218+ TOON tables; doctrine (no FK/triggers); BIGINT timestamps; actor model in core tables.
- **Migration SQL:** Single file `import_from_old_crafty_syntax.sql` with INSERT/SELECT for all mapped tables; legacy tables only ALTERed/deprecated, not dropped in file.
- **Crafty module surface:** Routes/entry points under `crafty_syntax/`, `livehelp.php`, `livehelp_js.php`; bootstrap/auth in place.
- **Documentation:** Mapping docs (STRUCTURED_MAPPING, ANALYSIS, CRAFTY_SYNTAX_MIGRATION_DOCTRINE), integration plan, import checklist.

---

## 5. What Was Improved (Not Regressions)

- **Users:** livehelp_users → lupo_auth_users (and actor model elsewhere); no lupo_users table.
- **Config:** livehelp_config → lupo_modules.config_json (single JSON blob).
- **Chat:** livehelp_transcripts → lupo_dialog_threads + lupo_dialog_messages (normalized).
- **Analytics:** livehelp_visits/referers/paths → lupo_unified_visits, lupo_unified_referers, lupo_unified_analytics_paths (unified model).
- **Identity:** livehelp_identity_monthly → lupo_actors (anonymous); identity_daily/keywords dropped by design.
- **Operators:** Operator–department link in lupo_actor_departments; operator identity in lupo_operators (auth_user_id + actor_id).

---

## 6. Gaps and Missing Mappings

### 6.1 Schema / SQL vs TOON

| Issue | Location | Resolution |
|-------|----------|------------|
| **lupo_collections INSERT uses user_id** | import SQL | **Fixed:** Column and VALUES now use `actor_id` to match TOON. |
| **lupo_dialog_messages: weight** | import SQL | **Fixed:** `weight` removed from INSERT; TOON has no weight column. |
| **lupo_crafty_syntax_leave_message** | import SQL | **Fixed:** SELECT alias is `id AS crafty_syntax_leave_message_id`. |
| **auto_increment in TOON** | e.g. lupo_actors, lupo_collections | Doctrine says no reliance on auto_increment for identity; channels use explicit ID ranges. For actors/collections, either document exception or plan explicit ID assignment (migration + scripts). |

### 6.2 Post-Migration Data Gaps

| Gap | Description | Required Action |
|-----|-------------|-----------------|
| **Operators not populated** | ~~livehelp_users → lupo_auth_users only~~ | **Addressed:** Phase 1 SQL in import_from_old_crafty_syntax.sql now INSERTs lupo_actors (operator only, actor_type='user') and lupo_operators after lupo_auth_users. |
| **actor_id in lupo_actor_departments** | ~~Import mapped user_id → actor_id (legacy)~~ | **Addressed:** Phase 1 SQL UPDATEs lupo_actor_departments.actor_id via JOIN (username → auth_user_id → actor_id) after creating actors. |
| **lupo_crafty_user_mapping** | TOON exists; not populated by import SQL | Optional: Python script to fill legacy user_id → auth_user_id / actor_id for support/debug. |

### 6.3 Documentation Inconsistencies

- **CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md** lists livehelp_visits_daily/monthly and livehelp_websites as "Dropped / No target mapping"; **import SQL actually migrates** them to lupo_unified_visits and lupo_federation_nodes. Update doctrine to match SQL.
- **CRAFTY_SYNTAX_TO_LUPOPEDIA_STRUCTURED_MAPPING.md** says livehelp_channels → lupo_channels and livehelp_operator_channels → lupo_actor_departments; **import SQL** does not INSERT into lupo_channels or create operator_channel rows. Align doc with SQL or add migration path.

---

## 7. Required Migrations (SQL ALTER Only)

All schema changes **must** be expressed as migration files (SQL ALTER), not ad-hoc DB edits.

| Migration | Purpose | Doctrine |
|-----------|---------|----------|
| **lupo_collections** | If DB still has `user_id`, ALTER to `actor_id` (and update import SQL to use actor_id). | Actor model |
| **lupo_dialog_messages** | If TOON is missing `weight`, add column via ALTER and update TOON; or remove weight from import if not in TOON. | TOON = source of truth |
| **lupo_actors / IDs** | If explicit ID assignment is required (no auto_increment), add migration to support and document which tables use explicit IDs. | No auto_increment for identity where specified |
| **lupo_crafty_syntax_leave_message** | No schema change; fix INSERT alias in import SQL. | — |

No new FKs, triggers, or stored procedures.

---

## 8. Required Python Scripts (Data)

Consistent with existing workflow (e.g. `scripts/`):

| Script | Purpose |
|--------|---------|
| **Post-import operator/actor sync** | After `import_from_old_crafty_syntax.sql`: create lupo_actors for each lupo_auth_users that should be an operator; create lupo_operators rows (auth_user_id, actor_id, department_id) from livehelp_operator_departments or equivalent; optionally backfill lupo_actor_departments.actor_id to new actor_id. |
| **lupo_crafty_user_mapping backfill** (optional) | Map legacy user_id → auth_user_id and actor_id for debugging/support. |
| **Validation** | Compare row counts legacy vs lupo (per table); optionally checksum critical columns. Reuse or extend `scripts/validate_livehelp_import.py`. |

All inserts/updates via scripts; no manual SQL for routine data.

---

## 9. Required Feature Rebuilds (New Architecture)

From CRAFTY_SYNTAX_INTEGRATION_PLAN.md and checklist; implemented in Lupopedia code, not in legacy PHP:

| Feature Area | Status | Deliverable |
|--------------|--------|-------------|
| **Operator dashboard** | Partial (auth, detection) | Full operator dashboard (presence, status, metrics, routing rules). |
| **Operator presence** | Pending | Online/offline/away; auto-away; status endpoint and UI. |
| **Livehelp_js** | Pending | JS icon, visitor session init, tracking, chat request, operator assignment. |
| **Multi-channel operator UI** | Pending | Tabs per chat, send/receive, typing indicators, notifications. |
| **Chat routing & storage** | Schema ready | Route chats to operators; persist in lupo_dialog_threads / lupo_dialog_messages; end-chat and transcript. |
| **REST API (operator)** | Pending | request, availability, assign, status, release; auth and rate limiting. |
| **Department/operator admin** | Schema ready | Department management, operator management, settings (from config_json). |
| **Canned responses** | Data in lupo_actor_reply_templates | UI and API to use templates in chat. |
| **Leave message** | Data in lupo_crafty_syntax_leave_message | Offline form and handling. |
| **Auto-invite** | Data in lupo_crafty_syntax_auto_invite | Proactive invite logic and config. |
| **Layer invites** | Data in lupo_crafty_syntax_layer_invites | Theatrical UI layer behavior. |
| **Q&A / Truth** | Data in lupo_truth_questions, lupo_truth_answers, lupo_collection_tabs | Public Q&A and collection tabs. |
| **CRM (leads/emails)** | Data in lupo_crm_leads, lupo_crm_lead_messages | Lead list and email history. |

No emotional metadata, AI agents, or semantic enhancements in this migration scope.

---

## 10. State-Based Phases

Each phase ends in a **stable system state**. Transitions:

- **CLEAR:** All validations passed; safe to proceed.
- **HOLD:** Major issues (blocks mutation until fixed).
- **BLOCKED:** Critical issues (blocks all operations).

### Phase 0: Documentation and Schema Alignment — **CLEAR**

**Goal:** Single source of truth; no contradictory docs or SQL.

- [x] Align CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md with import SQL (visits, referers, websites, channels). *(Done: visits/referers/websites documented as migrated to lupo_unified_visits, lupo_unified_referers, lupo_federation_nodes.)*
- [x] Align CRAFTY_SYNTAX_TO_LUPOPEDIA_STRUCTURED_MAPPING.md with import SQL (channels, operator_channels). *(Done: livehelp_channels and livehelp_operator_channels documented as dropped with no INSERT.)*
- [x] Fix import SQL: lupo_crafty_syntax_leave_message SELECT alias → `crafty_syntax_leave_message_id`. *(Done.)*
- [x] Fix import SQL: lupo_collections INSERT use `actor_id` (TOON schema); column and VALUES updated to actor_id. *(Done.)*
- [x] lupo_dialog_messages: remove weight from INSERT; TOON has no weight column. *(Done.)*
- [x] Document which tables use explicit ID assignment vs auto_increment (per doctrine). *(Done: section added to CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md; lupo_channels listed; all others auto_increment.)*

**Exit:** **CLEAR** — docs and SQL match TOON and each other. Proceed to Phase 1.

---

### Phase 1: Migration SQL and Post-Import Scripts

**Goal:** One-click import plus deterministic post-import data (actors, operators, actor_departments.actor_id in migration SQL).

- [ ] Apply all migration ALTERs from Phase 0.
- [ ] Run `import_from_old_crafty_syntax.sql` (or equivalent steps) in a test environment.
- [x] Migration SQL extended: INSERT lupo_actors for operators (actor_type='user'), INSERT lupo_operators, UPDATE lupo_actor_departments.actor_id. *(Done in import_from_old_crafty_syntax.sql.)*
- [ ] Optional: lupo_crafty_user_mapping backfill script.
- [ ] Validation script: row counts and critical checks.

**Phase 1 validation plan:**

- **Correct actor row:** Verify that the system uses the actor row with `actor_source_type = 'lupo_auth_users'` (and matching `actor_source_id` = auth_user_id) for operator identity, session, and UI. Login and UI testing (e.g. at localhost/lupopedia) must confirm that operator features resolve to this actor.
- **Legacy actor rows:** Databases may contain older actor rows for the same user with `actor_source_type = 'users'` (or other pre-migration values). These are leftover artifacts from before the new migration SQL was added; they are **not** a migration bug. Validation must **ignore or explicitly flag** such legacy rows and must **not** treat them as failures. Only the row with `actor_source_type = 'lupo_auth_users'` is the canonical operator actor.

**Exit:** CLEAR when import runs without error, Phase 1 validation passes, and the correct actor row is confirmed in use by the system.

---

### Phase 2: Core Crafty Syntax Features (Operator + Visitor)

**Goal:** Operator and visitor flows work on new schema.

- [ ] Operator login and dashboard (presence, status, list).
- [ ] Livehelp_js: icon, session init, tracking, chat request.
- [ ] Chat: create thread/message, route to operator, send/receive, end chat, transcript in lupo_dialog_*.
- [ ] Canned responses (lupo_actor_reply_templates) in UI.
- [ ] Department/operator management using lupo_departments, lupo_actor_departments, lupo_operators.

**Exit:** CLEAR when operator can take a chat and visitor can request and chat.

---

### Phase 3: Multi-Channel and API

**Goal:** Multi-chat operator UI and external API.

- [ ] Multi-channel operator UI (tabs, notifications, typing).
- [ ] REST API: request, availability, assign, status, release; auth and rate limiting.
- [ ] Auto-invite and layer invites (data already migrated; wire logic).

**Exit:** CLEAR when one operator can handle multiple chats and API is usable.

---

### Phase 4: Admin, CRM, Q&A, and Polish

**Goal:** All Crafty features available; installer-safe.

- [ ] Leave message form and handling (lupo_crafty_syntax_leave_message).
- [ ] CRM: leads and messages (lupo_crm_leads, lupo_crm_lead_messages).
- [ ] Q&A and collection tabs (lupo_truth_*, lupo_collection_tabs).
- [ ] Settings from lupo_modules.config_json (SMTP, theme, etc.).
- [ ] All files under lupopedia directory; only lupopedia-config.php outside web root; WordPress-style layout preserved.
- [ ] Documentation updated (mapping, integration plan, checklist).

**Exit:** CLEAR when all features are functional and docs are current.

---

### Phase 5: Legacy Table Drop (Optional)

**Goal:** Remove deprecated tables after confidence period.

- [ ] After validation and rollback plan: DROP legacy livehelp_* tables (or move to archive).
- [ ] Document drop order and backup policy.

**Exit:** CLEAR when legacy tables are removed or archived and system is stable.

---

### Phase 6: Upgrade Wizard (Final)

**Goal:** Implement the upgrade wizard last, after all Crafty Syntax features are rebuilt and validated. The wizard runs the full Crafty Syntax 3.7.5 → Lupopedia import (e.g. `import_from_old_crafty_syntax.sql` or equivalent). It must be implemented **LAST**, after Phases 0–5 and after all Crafty Syntax features (operator panel, livehelp_js, chat, multi-channel, API, admin, CRM, Q&A, etc.) are rebuilt and validated. **Do not begin wizard implementation until this phase.**

**Context:** Crafty Syntax 3.7.5 allows multiple users to share the same email and usernames that do not match emails. Lupopedia requires email uniqueness and uses email-derived usernames/slugs. The wizard must detect and resolve these conflicts **before** running the final import.

---

#### 1. Email Conflict Detection

- [ ] Scan legacy `livehelp_users` for duplicate emails.
- [ ] Build a conflict list grouped by email.
- [ ] Identify all accounts that share the same email (and optionally flag usernames that do not match email-derived slugs).

---

#### 2. Conflict Resolution Workflow

The wizard must present options for each conflicting email group. For each group:

- [ ] **Option A:** Choose a primary account (others merged, archived, or deleted).
- [ ] **Option B:** Assign new unique emails to duplicates (admin-provided or auto-generated).
- [ ] **Option C:** Skip / delete / archive unwanted accounts.

Resolution choices must be persisted so the next step can normalize identity and run migration safely.

---

#### 3. Identity Normalization

After resolution, before any migration SQL runs:

- [ ] Ensure each remaining user has a **unique email**.
- [ ] Ensure each remaining user has a **username/slug derived from that email** (per Lupopedia rules). For example, legacy username `helen` with email `helen@lupopedia.com` becomes username `helen-at-lupopedia-com` (email-derived slug format) so that the migration SQL inserts Lupopedia-style usernames without conflicts.
- [ ] Update legacy rows (e.g. `livehelp_users`) accordingly so that the migration SQL sees no duplicate emails and no conflicting usernames.

---

#### 4. Migration Safety

- [ ] The wizard must run conflict detection and resolution **before** the migration SQL that inserts into `lupo_auth_users`, `lupo_actors`, and `lupo_operators`.
- [ ] The wizard must **guarantee** that when the migration SQL runs, it will not encounter duplicate emails or conflicting usernames (identity normalization has already been applied to the legacy data or equivalent staging).

---

#### 5. Placement in Plan

- Phase 6 remains the **final phase**.
- Do not begin wizard implementation until all Crafty Syntax features (Phases 0–5) are rebuilt and validated.
- Wizard implementation will be handled when we reach Phase 6.

---

#### Phase 6 Checklist (Summary)

- [ ] Wizard UI and flow (detection, confirmation, progress, completion, error, rollback).
- [ ] Email conflict detection (scan, group by email, list conflicts).
- [ ] Conflict resolution workflow (primary/merge, assign new emails, skip/archive).
- [ ] Identity normalization (unique email, email-derived username/slug; update legacy before import).
- [ ] Migration safety (wizard steps run before migration SQL; guarantee no duplicate email or conflicting username at import).
- [ ] Integration with migration SQL; config transformation if required.
- [ ] Validation and rollback procedures.
- [ ] Documentation for installers and users.

**Exit:** CLEAR when the wizard runs conflict resolution and import successfully, and post-wizard validation passes.

---

## 11. Filesystem and Installer Rules

- All Lupopedia files **inside** the lupopedia directory.
- Only **lupopedia-config.php** allowed outside web root.
- Layout: bootstrap in index.php, modules/plugins under lupopedia/modules/, procedural entry points, clean public root.
- No layout changes that break Softaculous/Installatron compatibility.

---

## 12. Doctrine Checklist (All Phases)

- [ ] No foreign keys, triggers, stored procedures, or DB logic.
- [ ] No inference of schema from MySQL introspection; TOON only.
- [ ] Explicit ID assignment where required (e.g. channels); ID availability check before insert.
- [ ] Timestamps: BIGINT, format YYYYMMDDHHIISS (24h); no unix epoch, no datetime/timestamp types.
- [ ] No display widths on integer types; no UNSIGNED; TINYINT numeric, not boolean.
- [ ] Relationships as soft-references (explicit, nullable, repairable).
- [ ] Schema changes only via migration files (SQL ALTER); data changes via Python (or documented PHP) scripts.

---

## 13. Deliverable Summary

By the end of this implementation:

1. **All Crafty Syntax features** are implemented inside Lupopedia (operator panel, livehelp_js, chat, multi-channel, API, admin, CRM, Q&A, leave message, auto-invite, layer invites).
2. **All old tables** are mapped to new tables (mapping doc and SQL aligned).
3. **Required migrations** are written and applied (ALTER only).
4. **Required Python scripts** exist (post-import operator/actor sync, optional mapping, validation).
5. **Features** are rebuilt in the new architecture (no regression of Lupopedia improvements).
6. **Documentation** is updated (doctrine, mapping, integration plan, checklist).
7. **Crafty Syntax subsystem** is fully functional inside Lupopedia and installer-safe.

---

**Document status:** Initial plan. Update this file as phases complete and new discoveries are integrated.  
**Next step:** Execute Phase 0 (documentation and schema alignment), then Phase 1 (migration SQL + post-import scripts).
