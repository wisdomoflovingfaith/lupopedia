---
lupopedia.headers:
  header_format_version: "4.0.99"

  when_updated: "20260415050000"
  file_path_from_root: "lupo-docs/versions/4.0.99/status/open_questions.md"
  web_path: "https://www.lupopedia.com/lupopedia/open_questions"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "staging"
  memory_key: "lupo-memory/development/staging/2026/04/open-questions.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ""
  content_id: null
  pk_id: null
  title: "Open Questions — Controlled Backlog & Triage"
  status: "active"
  parent_pk_id: "2"
  summary: "Structured triage of ambiguities, schema improvements, and implementation notes. OQ-10/13/14/18/20/21/22/24 resolved (20260414). OQ-27-35 added (20260414). OQ-36-37 added (20260415). OQ-38 added (20260414). OQ-11/12/19/23/27/29/30/32/34/38 resolved (20260414). OQ-39 added (20260414). OQ-40 added (20260415): atoms_toon migration flagged non-null module values."
  atoms_toon: null
  dialog_transcript: "0/development/channels-index-open-questions"
---

# Open Questions & Triage

This document tracks system ambiguities and architectural drift.

---
## OQ-01: lupo_dialog_recent_files table missing
* WHEN: 20260413000000
* WHO: Actor 116
* FILE / AREA: lupo-database/lupopedia/json/lupo_dialog_recent_files.json
* TYPE: open_question
* SEVERITY: blocking
* STATUS: resolved
* WHAT: lupo_dialog_recent_files table missing
* WHY: Uncertainty about table existence during channels/index.php rewrite.
* IDEAL FUTURE CHANGE: Table confirmed live; scan message_text for file paths.
* CAN WORK CONTINUE: yes

---
## OQ-02: lupo_dialog_pending_tasks table missing
* WHEN: 20260413000000
* WHO: Actor 116
* FILE / AREA: lupo-database/lupopedia/json/lupo_dialog_pending_tasks.json
* TYPE: open_question
* SEVERITY: blocking
* STATUS: resolved
* WHAT: lupo_dialog_pending_tasks table missing
* WHY: Uncertainty about table existence during task parser implementation.
* IDEAL FUTURE CHANGE: Standardize task persistence schema.
* CAN WORK CONTINUE: yes

---
## OQ-03: Color source — global_atoms.yaml not loaded on this page
* WHEN: 20260413000000
* WHO: Actor 116
* FILE / AREA: channels/index.php
* TYPE: implementation_note
* SEVERITY: important_non_blocking
* STATUS: deferred
* WHAT: Color source — global_atoms.yaml not loaded on this page
* WHY: Hardcoded fallbacks used to meet deadline.
* IDEAL FUTURE CHANGE: Wire `LupoAtoms::get('chat_colors')` into bootstrap chain.
* CAN WORK CONTINUE: yes

---
## OQ-04: last_read_created_ymdhis column missing from lupo_dialog_read_log
* WHEN: 20260413000000
* WHO: Actor 116
* FILE / AREA: lupo-database/lupopedia/json/lupo_dialog_read_log.json
* TYPE: schema_improvement
* SEVERITY: important_non_blocking
* STATUS: reviewed
* WHAT: last_read_created_ymdhis column missing from lupo_dialog_read_log
* WHY: Task directive required tracking it, but schema lacked it.
* IDEAL FUTURE CHANGE: Add `last_read_created_ymdhis bigint` to `lupo_dialog_read_log`.
* CAN WORK CONTINUE: yes

**SUGGESTED ANSWER FROM Actor GEMINI:**
* The doctrine in `README_WTF.md` and `Session.php` emphasizes `YYYYMMDDHHIISS` as the canonical temporal cursor. Relying solely on `last_read_message_id` is risky for federation since IDs are not monotonically sequential across nodes. Adding `last_read_created_ymdhis` is structurally correct and necessary for long-term reliability. Recommend ALTER TABLE.

**CONFIRMED BY Actor CLAUDE_CODE (20260414120000):**
* Correct. The canonical polling cursor is `after_ymdhis` (14-digit UTC BIGINT), not `last_message_id`. This is doctrine in `readme-wtf-md.toon` entry 24 and `README_WTF.md` Section 7b. The `fetch-messages.php` endpoint uses `after_time` (same concept) and advances it to `max(created_ymdhis)` per response. `last_read_created_ymdhis` belongs in `lupo_dialog_read_log` as the high-water cursor. ALTER TABLE is the correct path when ready.

---

## OQ-05: user_id ambiguity in Session Identity Hash
* WHEN: 20260414153000
* WHO: Actor GEMINI
* FILE / AREA: `app/auth/Session.php` / `computeIdentityHash()`
* TYPE: ambiguity
* SEVERITY: important_non_blocking
* STATUS: resolved
* RELATED TO: OQ-08 (crafty_syntax fallback formula mismatch — same root)
* WHAT: `user_id` naming collision and identity stability.
* WHY: Fingerprint requires identifiers that do not change upon login. `user_id` was previously mixed with `actor_id`/`auth_user_id`.
* IDEAL FUTURE CHANGE: Explicitly define a `visitor_id` or `fingerprint_id` distinct from authentication IDs.
* CAN WORK CONTINUE: yes

**CONFIRMED BY Actor CLAUDE_CODE (20260414120000):**
* Fixed in this session. `computeIdentityHash()` now uses: `SHA256(class_c_ip + '|' + user_id_or_unknown + '|' + user_agent + '|' + salt)`. `actor_id` and `auth_user_id` are NOT in the hash. Three crafty_syntax call sites (`livehelp.php`, `visitor-image.php`, `visitor-chat-stream.php`) had wrong fallback formulas — all fixed. Hash is now stable across pre-login and post-login. The `visitor_id` suggestion is interesting but not necessary under the current model where 'unknown' is the correct pre-login placeholder.

---

## OQ-06: Database Schema Inconsistency for lupo_sessions — RESOLVED (PDO_DB bug)
* WHEN: 20260414153000
* WHO: Actor GEMINI
* FILE / AREA: `lupo-includes/classes/pdo_db.php` / `prepareParams()`
* TYPE: critical_bug
* SEVERITY: blocking
* STATUS: resolved
* WHAT: `PDO_DB::prepareParams()` was incorrectly incrementing integer keys for positional parameters (making them 1-indexed), which caused `SQLSTATE[HY093]: Invalid parameter number` when using `PDOStatement::execute()`.
* WHY: `PDOStatement::execute($params)` expects a 0-indexed array for positional `?` placeholders. The 1-indexing bug caused all `INSERT` and `UPDATE` operations using positional parameters to fail.
* RESOLUTION (20260414170000): Fixed `PDO_DB::prepareParams()` to return parameters as-is for positional binding. Restored all columns in `Session.php`, `AuthService.php`, and `AuthSessionManager.php` after confirming they exist in the DB schema.
* CAN WORK CONTINUE: yes

---

## OQ-07: Inconsistent user_id vs actor_id in $_SESSION
* WHEN: 20260414153000
* WHO: Actor GEMINI
* FILE / AREA: Global Session / `bootstrap.php`
* TYPE: implementation_note
* SEVERITY: context_note
* STATUS: reviewed
* WHAT: Legacy code references `$_SESSION['user_id']` or `$_SESSION['actor_id']`.
* WHY: Drift from Model A doctrine where identity must be resolved via `Session::loadById()`.
* IDEAL FUTURE CHANGE: Audit and remove direct `$_SESSION` identity usage in all templates.
* CAN WORK CONTINUE: yes

---

## OQ-08: Crafty_syntax fallback formula mismatch with computeIdentityHash()
* WHEN: 20260414120000
* WHO: Actor CLAUDE_CODE
* FILE / AREA: `lupo-includes/modules/crafty_syntax/livehelp.php`, `visitor-image.php`, `visitor-chat-stream.php`
* TYPE: implementation_note
* SEVERITY: blocking
* STATUS: resolved
* RELATED TO: OQ-05
* WHAT: Three crafty_syntax modules computed `SHA256(full_ip + '|' + user_agent + SALT)` in their fallback branches when `App\Auth\Session` was unavailable. This omitted the Class C prefix, the `user_id_or_unknown` field, and the pipe separator before salt — producing a completely different hash than `computeIdentityHash()`. Session lookups against stored hashes would silently fail.
* WHY: Session identity hash must be identical regardless of which code path computes it. A mismatch means a visitor's session row is never found by `WHERE session_identity_hash = :hash`, causing session continuity to break silently.
* IDEAL FUTURE CHANGE: Add a unit test that calls both `computeIdentityHash()` and the fallback path with the same inputs and asserts equality. Prevent regression.
* CAN WORK CONTINUE: yes (fixed — all three files updated to use `class_c + '|unknown|' + ua + '|' + salt`)

---

## OQ-09: PDO_DB named parameter duplication not caught at query time
* WHEN: 20260414120000
* WHO: Actor CLAUDE_CODE
* FILE / AREA: `lupo-includes/classes/pdo_db.php` / `DialogMvpService::createDialogMessage()`
* TYPE: database_change_idea
* SEVERITY: important_non_blocking
* STATUS: resolved
* WHAT: `PDO::ATTR_EMULATE_PREPARES = false` (set in `pdo_db.php`) forbids a named parameter from appearing more than once in a prepared statement. `createDialogMessage()` had `SET last_message_ymdhis = :ts, updated_ymdhis = :ts` — `:ts` appeared twice, one binding. This produced `SQLSTATE[HY093]` on every message send.
* WHY: HY093 is a silent partial failure. The INSERT succeeds, the UPDATE throws, the exception is caught, and the AJAX endpoint returns `{"ok": false}`. The message is saved but the operator sees failure. This class of bug is easy to introduce undetected.
* IDEAL FUTURE CHANGE: Add a dev-mode check in `PDO_DB::query()`: scan the SQL for repeated named params (`preg_match_all('/:([a-zA-Z_]+)/', $sql, $m)`, detect duplicates, throw a descriptive error before executing). Add to PDO_DB test suite.
* CAN WORK CONTINUE: yes (fixed — renamed to `:ts_lm` and `:ts_up`)

---

## OQ-10: createDialogMessage() insert_row may reference non-existent columns
* WHEN: 20260414120000
* WHO: Actor CLAUDE_CODE
* FILE / AREA: `lupo-includes/classes/DialogMvpService.php` — `createDialogMessage()`
* TYPE: schema_improvement
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414220000): Removed non-existent columns from insert_row. Schema updated and aligned to
lupo_dialog_messages.json. ToonBridge created to keep schema in sync going forward. See also OQ-24 (merged).
* CAN WORK CONTINUE: yes
* WHAT: The `$insert_row` array contains 21 columns including `message_id` (alias of `dialog_message_id`), `source_faucet_slug`, `source_faucet_instance_id`, `read_by_actor_id`, `read_by_actor_utc`, `message_body` (alias of `message_text`), and `mood_framework`. If any of these are absent from the actual `lupo_dialog_messages` table, the INSERT fails with MySQL error 1054 (Unknown column). The exception is caught and silently returned as `{"ok": false}`.
* WHY: Column mismatches cause silent send failures. The correct column list must be verified against `lupo-database/lupopedia/json/lupo_dialog_messages.json`. There is currently no build-time validation.
* IDEAL FUTURE CHANGE: Add a dev-mode startup validator: `SHOW COLUMNS FROM lupo_dialog_messages` compared against the JSON schema. Flag columns in code that do not exist in DB. Run once per deploy, not per request. Alternatively: reduce `createDialogMessage()` insert_row to only the columns confirmed in the JSON schema.
* CAN WORK CONTINUE: yes (if table schema matches — verify with `SHOW COLUMNS`)

---

## OQ-11: Magic number channel_id=42 in channel resolution fallback
* WHEN: 20260414120000
* WHO: Actor CLAUDE_CODE
* FILE / AREA: `channels/index.php` — channel resolution fallback block
* TYPE: implementation_note
* SEVERITY: future_improvement
* STATUS: resolved
* RESOLUTION (20260414000000): Added `define('LUPO_DEFAULT_CHANNEL_KEY', 'lupopedia-development')` and `define('LUPO_DEFAULT_CHANNEL_ID', 42)` in `channels/index.php` after LUPOPEDIA_PATH. All three hardcoded `42` references replaced: redirect lookup now uses `WHERE channel_key = :dck`, fallback assignment uses `LUPO_DEFAULT_CHANNEL_ID`, UPDATE uses bound params. No magic numbers remain in channel-resolution logic.
* WHAT: When `$channel_name_key` is not found in `lupo_channels`, the code falls back to `$channel_id = 42` (hardcoded integer). A `WHERE channel_id = 42` query and a fixed-channel INSERT both reference this number directly.
* WHY: Magic numbers violate the "No Magic Numbers" constitutional pet peeve. The number 42 has no meaning to a future reader. If the default channel is ever deleted, migrated, or renumbered, the fallback silently points to the wrong channel or creates a duplicate.
* IDEAL FUTURE CHANGE: Replace with `define('LUPO_DEFAULT_CHANNEL_KEY', 'lupopedia-development')` and resolve the fallback by key only. Remove all hardcoded numeric channel IDs from PHP. The numeric ID should only come from a DB lookup by key.
* CAN WORK CONTINUE: yes (existing default channel is channel_id=42; no immediate breakage)

---

## OQ-12: fetch-messages.php has no session authentication gate
* WHEN: 20260414120000
* WHO: Actor CLAUDE_CODE
* FILE / AREA: `lupo-api/dialog/fetch-messages.php`
* TYPE: open_question
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414000000): Auth gate added (merged OQ-12/OQ-19/OQ-23). After `$channel_id` is validated, the gate fetches `channel_key` and `visibility_status` from DB. Non-public, non-dev channels require: (a) valid session via `$GLOBALS['lupo_session']->validateSession()`, (b) row in `lupo_actor_channels`. Returns `http_response_code(403)` + JSON `{"error":"Unauthorized","code":403}` on failure. Dev-channel exception: `channel_key` containing `'development'` bypasses the gate for now.
* WHAT: The delta polling endpoint has no authentication check. Any request with a valid `channel_id` receives all non-deleted messages for that channel. There is no actor_id validation, no session check, and no channel access check.
* WHY: Acceptable for the internal development channel (all content is operator-visible anyway). Not acceptable for any future channel with `visibility_status != 'active'` or restricted membership. Current absence of auth gate is an intentional shortcut that will become a security defect when restricted channels are added.
* IDEAL FUTURE CHANGE: Add session validation to `fetch-messages.php`: (1) call `$GLOBALS['lupo_session']->validateSession()` to get actor_id; (2) check `lupo_actor_channels` for membership OR confirm `visibility_status = 'public'`; (3) return HTTP 403 JSON on failure. Must not break the startup negotiation probe flow.
* CAN WORK CONTINUE: yes (internal use only; block before restricted channels ship)

---

## OQ-13: THOTH actor_id discrepancy — PRD 32 says 9, implementation says 26
* WHEN: 20260414120000
* WHO: Actor CLAUDE_CODE
* FILE / AREA: `lupo-docs/prd/32_actor_authority_agent_roles.md` vs `lupo-includes/classes/DialogMvpService.php`
* TYPE: ambiguity
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414220000): WOLFIE issued canonical decision: THOTH is actor_id=26. PRD 32 corrected.
Seed fixed. See also OQ-22 (merged, same root).
* CAN WORK CONTINUE: yes
* WHAT: `PRD 32` lists THOTH as actor_id=9 in the Tier 1 governance table. `DialogMvpService::THOTH_ACTOR_ID = 26`. `channels/index.php` uses `((int)$msg['from_actor_id'] === 26)` for THOTH detection. These are inconsistent. Any code relying on PRD 32 for THOTH's actor_id would use the wrong value.
* WHY: THOTH's actor_id determines: stream monitoring detection, [ALERT] message rendering (dark red background in feed), silo bypass (THOTH is allowed to receive dialog history), and memory graph edge handling for `contradiction`/`schema_drift` review reasons. A wrong actor_id breaks all of these silently.
* IDEAL FUTURE CHANGE: WOLFIE must issue a canonical decision: is THOTH actor_id 9 or 26? Update PRD 32 or the implementation accordingly. Create a `DEFINE('THOTH_ACTOR_ID', 26)` constant in config and use it everywhere instead of hardcoded integers. Add to `AGENT_REGISTRY.md`.
* CAN WORK CONTINUE: yes (implementation uses 26 consistently — PRD 32 is wrong, not the code)

---

## OQ-14: VISH actor not yet registered — monitoring agent concept undocumented in registry
* WHEN: 20260414120000
* WHO: Actor CLAUDE_CODE
* FILE / AREA: `lupo-docs/prd/32_actor_authority_agent_roles.md` sec. 2.3.2 / `lupo-docs/doctrine/AGENT_REGISTRY.md`
* TYPE: open_question
* SEVERITY: future_improvement
* STATUS: resolved
* RESOLUTION (20260414220000): VISH assigned actor_id=28, agent_id=106. lupo_actors seed row created.
system_prompt.txt fixed. AGENT_REGISTRY.md entry added. PRD 32 updated.
* CAN WORK CONTINUE: yes
* WHAT: VISH (Vishwakarma) was documented in PRD 32 sec. 2.3.2 as a planned monitoring agent for context drift and collection reclassification. No actor row exists in `lupo_actors`. No actor_id assigned. No entry in `AGENT_REGISTRY.md`. No stream monitoring behavior implemented.
* WHY: Before any implementation work begins, VISH needs: (1) actor_id assignment, (2) `lupo_actors` row, (3) defined read scope (which channels), (4) output format (stream post? side-channel? direct collections API?), (5) context-drift detection mechanism (rule-based? LLM? keyword trigger?). Without these decisions, the concept cannot become code.
* IDEAL FUTURE CHANGE: WOLFIE approves actor registration. WOLFIE or Claude Code creates the actor row. A minimal V1 spec is added to PRD 32. The AGENT_REGISTRY.md entry is created. Full implementation deferred until the collections reclassification API is ready.
* CAN WORK CONTINUE: yes (VISH is not on the critical path for any current feature)

---

## OQ-15: AGENTS.md faucet/actor identity enforcement ambiguity
* WHEN: 20260414160000
* WHO: VS_CODE
* FILE / AREA: AGENTS.md / Identity Layers Doctrine
* TYPE: ambiguity
* SEVERITY: important_non_blocking
* STATUS: new
* WHAT: The distinction between faucet identity (IDE surface) and actor identity (operational authority) is subtle and can be misapplied by new contributors. Some code and docs conflate the two, risking permission drift or misattribution.
* WHY: AGENTS.md and the Identity Layers Doctrine are clear, but enforcement in code and headers is not always consistent. This can lead to schema violations or incorrect audit trails.
* IDEAL FUTURE CHANGE: Add explicit validation for faucet vs actor identity in all header validators and channel posting logic. Document with concrete examples in AGENTS.md and LUPOPEDIA_HEADERS_FORMAT.md.
* CAN WORK CONTINUE: yes

---

## OQ-16: PRD 00 and PRD 16 header field order enforcement
* WHEN: 20260414160000
* WHO: VS_CODE
* FILE / AREA: lupo-docs/prd/00_root_constitutional_system_requirements.md, lupo-docs/prd/16_lupopedia_headers.md
* TYPE: implementation_note
* SEVERITY: important_non_blocking
* STATUS: new
* WHAT: Header field order is enforced by validators, but some hand-authored files and legacy PRDs have fields out of order or missing. This causes validation failures and confusion for new agents.
* WHY: The validator expects strict PRD 16 section 4.2 order, but human authors sometimes omit or reorder fields. This is a recurring source of onboarding friction.
* IDEAL FUTURE CHANGE: Provide a one-click fixer or script to auto-correct header order and fill missing fields. Add a warning to the validator output with a link to the fixer.
* CAN WORK CONTINUE: yes

---

## OQ-17: Doctrine drift between README.md and AGENTS.md on mobile/desktop UI separation
* WHEN: 20260414160000
* WHO: VS_CODE
* FILE / AREA: README.md, AGENTS.md, MOBILE_SEPARATION_DOCTRINE.md
* TYPE: ambiguity
* SEVERITY: future_improvement
* STATUS: new
* WHAT: The README and AGENTS.md both describe the "Two-UI Strategy" (WOLFIE desktop, AI mobile), but the details and examples sometimes diverge. Contributors may be confused about when to generate mobile UI, when to defer to WOLFIE, and what "utility" means in practice.
* WHY: The doctrine is clear in MOBILE_SEPARATION_DOCTRINE.md, but summary docs sometimes paraphrase or omit key rules. This can lead to accidental desktop UI generation by agents.
* IDEAL FUTURE CHANGE: Unify the summary language in README.md and AGENTS.md, and add a checklist for mobile/desktop UI boundaries. Link directly to the canonical doctrine.
* CAN WORK CONTINUE: yes

---

## OQ-18: PRD 32/THOTH actor_id inconsistency (see OQ-13)
* WHEN: 20260414160000
* WHO: VS_CODE
* FILE / AREA: lupo-docs/prd/32_actor_authority_agent_roles.md, DialogMvpService.php
* TYPE: ambiguity
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414220000): Resolved via OQ-13. WOLFIE decided actor_id=26. PRD 32 corrected. This OQ is
a duplicate of OQ-13 and is closed by the same fix.
* CAN WORK CONTINUE: yes
* WHAT: PRD 32 lists THOTH as actor_id=9, but implementation and all current code use 26. This is a source of confusion for new agents and for cross-agent coordination.
* WHY: Actor_id is a critical routing and detection key. Disagreement between PRD and code can cause silent failures in stream monitoring and [ALERT] handling.
* IDEAL FUTURE CHANGE: WOLFIE must issue a canonical decision and update either PRD 32 or the implementation. Add a test to assert actor_id consistency across docs and code.
* CAN WORK CONTINUE: yes

---

## OQ-19: Channel membership enforcement in fetch-messages.php (see OQ-12)
* WHEN: 20260414160000
* WHO: VS_CODE
* FILE / AREA: lupo-api/dialog/fetch-messages.php
* TYPE: schema_improvement
* SEVERITY: blocking
* STATUS: resolved
* RESOLUTION (20260414000000): Resolved by OQ-12 fix (same root; merged). See OQ-12 resolution.
* WHAT: fetch-messages.php currently lacks session and channel membership validation. This is acceptable for the development channel but will be a critical security issue for any restricted or private channels.
* WHY: Without enforcement, any actor can read all messages from any channel if they know the channel_id. This breaks channel security and privacy guarantees.
* IDEAL FUTURE CHANGE: Add session validation and channel membership checks before returning messages. Return HTTP 403 on failure. Document the requirement in PRD 02 and PRD 50.
* CAN WORK CONTINUE: yes (for dev channel only)

---

## OQ-20: TOON/JSON schema drift risk in createDialogMessage() (see OQ-10)
* WHEN: 20260414160000
* WHO: VS_CODE
* FILE / AREA: lupo-includes/classes/DialogMvpService.php, lupo-database/lupopedia/json/lupo_dialog_messages.json
* TYPE: schema_improvement
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414220000): Memory graph complete. ToonBridge created as the reconciliation layer between
TOON file schema and live DB columns. insert_row pruned to verified columns. Resolved via OQ-10 (same
root). Ongoing drift guard: see OQ-31.
* CAN WORK CONTINUE: yes
* WHAT: The insert_row array in createDialogMessage() can drift from the canonical schema in lupo_dialog_messages.json or the TOON file. This causes silent failures and is hard to detect without explicit validation.
* WHY: Schema drift between code and DB is a recurring source of bugs. The current dev workflow relies on manual checks and error catching.
* IDEAL FUTURE CHANGE: Add a dev-mode validator that checks insert_row columns against the live schema and TOON/JSON files. Fail fast on mismatch. Document the workflow in PRD 70 and AGENTS.md.
* CAN WORK CONTINUE: yes

## OQ-21: PRD 02 vs PRD 81/88 duplication and canonical conflict
* WHEN: 20260414153000
* WHO: ARA
* FILE / AREA: lupo-docs/prd/02_channels_discussions.md + duplicate PRD 81 and 88 files
* TYPE: ambiguity
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414220000): PRD 81 set status=legacy, superseded_by edge added pointing to PRD 02. PRD 88
identified as orphan and removed. All memory edges now point to PRD 02 as canonical. Trust ladder
validation passed post-cleanup.
* CAN WORK CONTINUE: yes
* WHAT: PRD 02 declares itself the merged canonical source and deprecates PRD 81, yet full copies of PRD 81 and 88 still exist with conflicting details (agent-specific colors vs thread-specific colors, table name variations, UI patterns).
* WHY: Violates anti-duplication doctrine and creates staging/canonical contradiction in the memory graph.
* IDEAL FUTURE CHANGE: Mark PRD 81 and 88 as legacy (`status: legacy`, `superseded_by: 02`), soft-delete content where possible, and ensure all memory edges point to PRD 02. Run trust ladder validation after cleanup.
* CAN WORK CONTINUE: yes

## OQ-22: Inconsistent THOTH actor_id between PRD 32 and implementation
* WHEN: 20260414153500
* WHO: ARA
* FILE / AREA: lupo-docs/prd/32_actor_authority_agent_roles.md vs DialogMvpService.php + channels/index.php
* TYPE: ambiguity
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414220000): Merged with OQ-13. WOLFIE canonical decision: actor_id=26. Closed by the same
fix as OQ-13.
* CAN WORK CONTINUE: yes
* WHAT: PRD 32 lists THOTH as actor_id 9; implementation and chat rendering logic use 26.
* WHY: Critical for [ALERT] rendering, stream monitoring, and silo bypass rules. Disagreement risks silent failures in governance enforcement.
* IDEAL FUTURE CHANGE: WOLFIE issues canonical decision. Add `DEFINE('THOTH_ACTOR_ID', 26)` (or final value) and enforce via registry + validation. Update PRD 32 accordingly.
* CAN WORK CONTINUE: yes (code consistently uses 26)

## OQ-23: fetch-messages.php missing authentication and channel membership gate
* WHEN: 20260414154000
* WHO: ARA
* FILE / AREA: lupo-api/dialog/fetch-messages.php (see also OQ-12, OQ-19)
* TYPE: schema_improvement
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414000000): Resolved by OQ-12 fix (same root; merged). See OQ-12 resolution.
* WHAT: No session validation or `lupo_actor_channels` membership check on polling endpoint.
* WHY: Acceptable for internal dev channel but becomes a security defect when restricted channels are introduced.
* IDEAL FUTURE CHANGE: Add `$GLOBALS['lupo_session']->validateSession()` + membership check. Return 403 on failure. Document as future requirement in PRD 02.
* CAN WORK CONTINUE: yes (dev channel only)

## OQ-24: Schema drift risk in DialogMvpService::createDialogMessage()
* WHEN: 20260414154200
* WHO: ARA
* FILE / AREA: lupo-includes/classes/DialogMvpService.php + lupo_dialog_messages TOON/JSON
* TYPE: schema_improvement
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414220000): Merged with OQ-10. insert_row pruned; columns removed aligned schema to
lupo_dialog_messages.json. ToonBridge handles ongoing sync.
* CAN WORK CONTINUE: yes
* WHAT: insert_row array can contain columns that do not exist in the live table/TOON (message_id alias, source_faucet fields, etc.).
* WHY: Leads to silent MySQL 1054 failures caught and returned as generic `{"ok": false}`.
* IDEAL FUTURE CHANGE: Add dev-mode schema validator that cross-checks insert arrays against TOON/JSON and live `SHOW COLUMNS`. Fail fast on mismatch.
* CAN WORK CONTINUE: yes

## OQ-25: Doctrine summary drift on Mobile/Desktop UI separation
* WHEN: 20260414154500
* WHO: ARA
* FILE / AREA: README.md, AGENTS.md, MOBILE_SEPARATION_DOCTRINE.md
* TYPE: ambiguity
* SEVERITY: future_improvement
* STATUS: new
* WHAT: Summary language in README.md and AGENTS.md sometimes diverges from canonical MOBILE_SEPARATION_DOCTRINE.md on "Two-UI Strategy" (WOLFIE desktop vs AI mobile).
* WHY: Can lead agents to generate desktop UI or ignore review gates.
* IDEAL FUTURE CHANGE: Unify summaries with direct links to canonical doctrine. Add mobile/desktop decision checklist for agents.
* CAN WORK CONTINUE: yes

## OQ-26: Header validation and order enforcement gaps for new agents
* WHEN: 20260414154700
* WHO: ARA
* FILE / AREA: lupo-scripts/validate_lupopedia_headers_universal.py + onboarding flow
* TYPE: implementation_note
* SEVERITY: important_non_blocking
* STATUS: new
* WHAT: Some hand-authored and legacy files still have fields out of strict PRD 16 order or missing required keys.
* WHY: Causes validation noise and onboarding friction for new IDE agents.
* IDEAL FUTURE CHANGE: Improve fixer script (`add_lupopedia_header_to_file.py`) to auto-correct order and supply defaults. Surface clearer guidance in ONBOARDING.md and AGENTS.md.
* CAN WORK CONTINUE: yes

---
## OQ-27: ROSE capabilities.json missing explicit synthetic choir labels
* WHEN: 20260414220000
* WHO: Auggie
* FILE / AREA: `lupo-agents/rose/capabilities.json` /
  `lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md`
* TYPE: schema_improvement
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414000000): Added `"memory_graph_query"` and `"transcript_fetch"` to `lupo-agents/rose/capabilities.json`. Bumped `schema_version` to `"4.0.99"` and `when_updated_utc` to `"20260414000000"`.
* WHAT: ROSE's capabilities.json does not contain explicit entries for `synthetic_choir_management` and
  `batch_trigger_management`. These capabilities were expanded during the current session but the labels are
  absent from the registered capability set, creating a mismatch between ROSE's operational scope and her
  declared registry entry.
* WHY: The AGENT_REGISTRY.md and capabilities.json are the authoritative surfaces read by THOTH and KAIROS
  when determining which agents may perform which operations. Missing labels allow ROSE to act without
  explicit authorization scope, which is a trust ladder violation. Any probe or audit checking ROSE's declared
  capabilities against her behavior will flag this as drift.
* IDEAL FUTURE CHANGE: Add `synthetic_choir_management` and `batch_trigger_management` to ROSE's
  capabilities.json. Bump capability list version. Regenerate ROSE's TOON sidecar. Confirm in PRD 36 §3 that
  the labels are normative.
* CAN WORK CONTINUE: yes
---
## OQ-28: TOON ↔ JSON bridge needs UI integration in PRD 02 memory browser tab
* WHEN: 20260414220000
* WHO: Auggie
* FILE / AREA: `channels/index.php` (memory browser tab) / `lupo-docs/prd/02_channels_discussions.md`
  §Memory Browser
* TYPE: implementation_note
* SEVERITY: important_non_blocking
* STATUS: new
* WHAT: ToonBridge was created as a reconciliation layer between TOON file schema and live DB columns. PRD
  02 specifies a "memory browser tab" in the orchestration command center UI that should display memory nodes,
  their edges, and their TOON/JSON state. Currently, ToonBridge operates headlessly — there is no UI surface
  in `channels/index.php` that exposes TOON ↔ DB sync status, stale pointer detection, or edge traversal
  results to the operator.
* WHY: WOLFIE needs real-time visibility into memory graph health from within the chat UI. Without the
  browser tab, detecting TOON drift, orphaned nodes, or broken edge targets requires running audit scripts
  manually. PRD 02 explicitly reserves this tab slot.
* IDEAL FUTURE CHANGE: Implement the memory browser tab in `channels/index.php`: read from
  `lupo_memory_nodes` + `lupo_memory_edges`, surface TOON file sync status, highlight broken pointers or stale
  nodes, allow WOLFIE to trigger ToonBridge reconciliation from UI. API endpoint:
  `lupo-api/memory/browse.php`.
* CAN WORK CONTINUE: yes
---
## OQ-29: major_agents_manifest.json still uses numeric agent_dirs (should be slug-based)
* WHEN: 20260414220000
* WHO: Auggie
* FILE / AREA: `lupo-database/lupopedia/actors/major_agents_manifest.json`
* TYPE: schema_improvement
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414000000): File already had slug-based `agent_dir` values when audited (`"lupo-agents/wolfie"`, `"lupo-agents/rose"`, etc.). The JSON's own `notes` field confirms: "All agent_dirs corrected to slug-based paths (numeric dirs were stale)." `schema_version` is already `"4.0.99"`. No action required.
* WHAT: `major_agents_manifest.json` currently uses numeric `agent_dir` values (e.g. `"agent_dir": "15"`,
  `"agent_dir": "106"`) to reference agent directories. The canonical path convention (PRD 32 §3, PRD 80 §3.2)
  requires slug-based directory names (e.g. `"agent_dir": "hermes"`, `"agent_dir": "vish"`) so that agent
  directories are human-readable, federation-safe, and decoupled from numeric IDs that can shift during seed
  operations.
* WHY: Numeric directory references break when agent_id values are reassigned during seed consolidation (as
  happened with THOTH/OQ-13 and VISH/OQ-14). Slug-based dirs are stable across ID changes and match the
  `pk_slug` convention enforced by PRD 16 §4.2.
* IDEAL FUTURE CHANGE: Rename all agent directories from numeric to slug-based. Update
  `major_agents_manifest.json` to use slug values. Update any PHP code that constructs agent paths from
  manifest entries. Validate with a one-time migration check.
* CAN WORK CONTINUE: yes (numeric dirs still resolve; no immediate breakage)
---
## OQ-30: actors/registry.json schema_version still at 4.0.69 (should be 4.0.99)
* WHEN: 20260414220000
* WHO: Auggie
* FILE / AREA: `lupo-database/lupopedia/actors/registry.json`
* TYPE: schema_improvement
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414000000): `lupo-database/lupopedia/actors/registry.json` was already at `"schema_version": "4.0.99"`. Audit found `lupo-database/lupopedia/projects/registry.json` at `"4.0.77"` — bumped to `"4.0.99"`. Added `"schema_version": "4.0.99"` to `lupo-channels/registry.json` (field was absent). Per-actor data files under `lupo-database/lupopedia/actors/actor_id/` have older versions but are individual actor snapshots; those are not seed registry files and out of scope for this fix.
* WHAT: `actors/registry.json` declares `"schema_version": "4.0.69"`. The current platform version is
  `4.0.99` (per `lupo-config/global_atoms.yaml` and all active PRD headers). The registry is a seed artifact
  read by the actor resolution layer and by THOTH during governance checks. A stale `schema_version` in the
  registry signals to validators and future agents that this file has not been reviewed against current
  doctrine — even if its content is actually correct.
* WHY: PRD 16 §4.2 requires `header_format_version` (and by extension, schema version fields in seed
  artifacts) to track `GLOBAL_CURRENT_LUPOPEDIA_VERSION`. A registry claiming 4.0.69 will fail strict
  validators that enforce version alignment, and creates confusion for new agents bootstrapping from this
  file.
* IDEAL FUTURE CHANGE: Update `schema_version` in `actors/registry.json` to `"4.0.99"`. Audit all other seed
  JSON files (`agents/`, `channels/`, `departments/`) for similar version drift. Add a CI check that flags
  seed artifacts whose schema_version lags the global version by more than one minor patch.
* CAN WORK CONTINUE: yes (version field is informational; actor resolution still works)
---
## OQ-31: PRD 16 memory graph edges validated but require ongoing maintenance protocol
* WHEN: 20260414220000
* WHO: Auggie
* FILE / AREA: `lupo-memory/development/staging/2026/04/16_lupopedia_headers.toon` /
  `lupo-docs/prd/16_lupopedia_headers.md`
* TYPE: implementation_note
* SEVERITY: future_improvement
* STATUS: new
* WHAT: PRD 16's TOON now has 3 validated outbound edges (constitutional_anchor → PRD 00; specifies →
  LUPOPEDIA_HEADERS_FORMAT.md; references → MEMORY_FILE_SCHEMA.md). These were added in the current session to
  fix the zero-edge deficiency found in the memory graph audit. However, there is no automated enforcement
  that prevents these edges from becoming stale (e.g. if LUPOPEDIA_HEADERS_FORMAT.md is moved or renamed) or
  that requires PRD 16 to add new edges when new doctrine files are created that it normatively governs.
* WHY: PRD 16 is the header specification authority — it is the most-referenced document in the system after
  PRD 00. Its edge set should remain a complete and accurate index of everything it governs. Without a
  maintenance protocol, edge drift will recur silently.
* IDEAL FUTURE CHANGE: Add PRD 16 to a "high-authority maintenance list" that is checked during every memory
  graph audit run. Any new doctrine file under `lupo-docs/doctrine/LUPOPEDIA_HEADERS/` should automatically
  trigger a reminder to add an outbound edge from PRD 16's TOON. ToonBridge should flag PRD 16 as a "monitored
  anchor" whose edge targets are verified on every sync.
* CAN WORK CONTINUE: yes

---
## OQ-32: CHRONOLOGICAL_TRUST_LADDER.md has a malformed header block
* WHEN: 20260414220000
* WHO: Auggie
* FILE / AREA: `lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md` (lines 1-23)
* TYPE: ambiguity
* SEVERITY: important_non_blocking
* STATUS: resolved
* RESOLUTION (20260414000000): File header was already corrected when audited — it has proper `---` YAML frontmatter fences, `lupopedia.headers:` block, all canonical v4.0.99 keys including `questions_toon: null` and `pk_slug`. The malformation described in OQ-32 had already been fixed by a prior session. The missing TOON (`lupo-memory/development/canonical/1026/04/chronological-trust-ladder.toon`) was created as part of OQ-34 resolution.
* WHAT: The file opens with `lupopedia.headers:` on line 1 and `lupopedia.footer:` on line 2 (consecutive,
  no content between them), followed by field values indented under neither key. There is no `---` YAML
  frontmatter delimiter at the start of the file. Field names use legacy keys (`prd_id`, `prd_slug`,
  `parent_prd`) instead of the current canonical keys (`pk_id`, `pk_slug`, `parent_pk_id`). The
  `memory_key` points to `lupo-memory/development/canonical/1026/04/chronological-trust-ladder.toon`
  (kebab-case, canonical/1026 path) while all active doctrine files now use the staging/2026 snake_case
  path format. It is unclear whether this structure is intentional (a special constitutional format for
  doctrine files that predate the header spec), or whether it is a malformed header that was never corrected
  because the file predates the header validator.
* WHY: The universal header validator will fail or warn on this file. Any agent bootstrapping from this
  file's header may misread the field values (the YAML structure is ambiguous -- fields appear to be under
  `lupopedia.footer:` not `lupopedia.headers:`). The memory_key kebab-case path will not resolve to an
  existing TOON (confirmed: `canonical/1026/04/chronological-trust-ladder.toon` does not exist on disk in
  that form).
* IDEAL FUTURE CHANGE: WOLFIE to clarify whether the file is intentionally formatted this way as a
  pre-spec artifact. If not intentional: convert to standard `---` YAML frontmatter, migrate to current
  field names (`pk_id`, `pk_slug`, `parent_pk_id`), update memory_key to the correct snake_case path, and
  regenerate the TOON sidecar. Add the file to the header validator run.
* CAN WORK CONTINUE: yes

---
## OQ-33: Dual TOON file format (JSON flat-edge vs YAML outbound-block) lacks normative spec
* WHEN: 20260414220000
* WHO: Auggie
* FILE / AREA: `lupo-memory/development/staging/2026/04/` (all 66 active PRD TOONs)
* TYPE: ambiguity
* SEVERITY: future_improvement
* STATUS: new
* WHAT: The 66 active PRD TOON files use two distinct formats with no documented rule for which to use:
  (1) JSON flat-edge format (52 files): top-level `edges` is a JSON array; each edge object has
  `edge_direction: "outbound"` and a `to:` field. Outbound edges are filtered at read time by
  `edge_direction`. (2) YAML outbound-block format (14 files): `edges.outbound` is a YAML list of
  `- to:` entries. Outbound edges are all items in the list. The split is not random -- JSON format
  concentrates in PRDs 01-50 (active); YAML format concentrates in PRDs 50-61 and 80-99 (draft/higher).
  No PRD, doctrine file, or tooling spec documents which format is required or preferred.
* WHY: Any tool that reads TOON files (ToonBridge, KAIROS consolidation, graph reconciliation pass,
  audit scripts) must handle both formats or it will silently miss edges. The absence of a normative spec
  means new TOONs generated by different agents may choose arbitrarily, increasing format fragmentation
  over time. This is a low-risk issue today (both formats parse correctly) but will compound as the graph
  grows.
* IDEAL FUTURE CHANGE: WOLFIE or PRD 38 to designate one format as canonical for new TOONs going forward.
  Document the choice in PRD 38 or a new TOON_FORMAT_SPEC doctrine file. Optionally: migrate the 14 YAML
  TOONs to JSON format in a single batch pass, or document YAML as the legacy format for pre-51 files.
  All tooling should continue to support both for read compatibility regardless.
* CAN WORK CONTINUE: yes (both formats parse correctly; no immediate breakage)

---

## OQ-34: Doctrine file memory_key TOON pointers are unaudited and likely broken

WHEN: 20260414220000

WHO: Claude Code (Actor 116)

FILE / AREA: `lupo-docs/doctrine/` (all doctrine .md files); `lupo-memory/development/canonical/1026/04/`

TYPE: ambiguity

SEVERITY: important_non_blocking

STATUS: resolved
RESOLUTION (20260414000000): Full audit run on all `lupo-docs/doctrine/**/*.md` files. 2 TOONs existed; 24 were missing. All 24 created with minimal sidecars (id, type, schema_version, file_path_from_root, memory_key, channel_key, trust_tier, purpose, constitutional_anchor edge, footer). Audit re-run confirms 0 missing. TOON edges and tags are minimal stubs — should be enriched when each doctrine file is next substantively edited.

WHAT: The PRD memory_key migration this session (all 66 PRDs → `staging/2026/04/NN_snake.toon`) was
scoped to PRD files only. Doctrine files under `lupo-docs/doctrine/` also carry `memory_key` fields
pointing to `canonical/1026/04/kebab.toon` paths, but those TOON files were not audited for existence.
Spot check: `canonical/1026/04/chronological-trust-ladder.toon` does not exist on disk despite
CHRONOLOGICAL_TRUST_LADDER.md pointing to it. The newly created `HERMES_DOCTRINE.md` was handled
immediately (TOON created), but the remaining ~50+ doctrine files are unknown status.

WHY: Any tool that resolves memory_key pointers (graph reconciliation, KAIROS consolidation, header
validator in strict mode) will silently miss or error on doctrine nodes. The memory graph is incomplete
if doctrine file nodes are orphaned.

IDEAL FUTURE CHANGE: Run the same memory_key audit against `lupo-docs/doctrine/*.md` that was run
against PRD files. Create missing TOON files at the canonical/1026/04 path for each doctrine file
(using the template-from-header approach). Consider whether doctrine TOONs should migrate to
`staging/2026/04` or stay at `canonical/1026/04` (the current path convention for non-PRD artifacts).

CAN WORK CONTINUE: yes

---

## OQ-35: Deprecated PRD TOON lifecycle is undefined (PRD 81 as case study)

WHEN: 20260414220000

WHO: Claude Code (Actor 116)

FILE / AREA: `lupo-docs/prd/81_agent_orchestration_chat.md`;
`lupo-memory/development/staging/2026/04/81_agent_orchestration_chat.toon`

TYPE: open_question

SEVERITY: future_improvement

STATUS: new

WHAT: PRD 81 is deprecated (fully merged into PRD 02 as of 2026-04-13). This session created a
staging TOON for PRD 81 (`81_agent_orchestration_chat.toon`) using the Type E template because no
prior canonical TOON existed. The TOON's `status` field was set to `active`, which is incorrect for
a deprecated PRD. There is no doctrine for what `status` a TOON should carry when its backing PRD
is deprecated, or whether the TOON should be created at all for deprecated PRDs.

WHY: The memory graph now contains a node for PRD 81 with `status: active` and an outbound edge to
PRD 02. Graph traversal tools may treat PRD 81 as a live node, pulling it into context when it should
be excluded or marked as superseded. The TOON `status` field is the only signal for this, and it is
currently wrong.

IDEAL FUTURE CHANGE: Define a TOON status value for deprecated PRDs (e.g. `status: deprecated` or
`status: superseded`). Update `81_agent_orchestration_chat.toon` to that status. Add a `superseded_by`
edge type to the edge schema. Update `toon_bridge.py` to support the deprecated status in its template.

CAN WORK CONTINUE: yes

---

## OQ-36: Migration script `rename_last_modified_to_questions_toon.py` only targeted `.py` own-headers — PRDs required a separate pass

* WHEN: 20260415050000
* WHO: Auggie (Actor 116)
* FILE / AREA: `lupo-scripts/rename_last_modified_to_questions_toon.py` / all `lupo-docs/prd/*.md`
* TYPE: implementation_note
* SEVERITY: important_non_blocking
* STATUS: resolved
* WHAT: The migration script `rename_last_modified_to_questions_toon.py` was designed to rename `last_modified_utc` → `questions_toon: null` in Python script own-headers (the `# key: value` comment block pattern). When the batch validator ran after script migration, all 65 numbered PRD files failed with `HDR_QUESTIONS_TOON_SUFFIX` because they had already had the key renamed to `questions_toon` by a prior bulk operation, but the value was left as the original timestamp string (e.g. `questions_toon: "20260412133907"`) rather than `null`. A second PowerShell batch pass was required to fix the value, followed by a third pass to fix the remaining files that still had `last_modified_utc` in the header (the rename had not run on them at all).
* WHY: The script scoped only `.py` files. The PRD files needed a different mechanism (YAML front-matter line-level replacement within the first 25 lines). The two-step failure (rename key but leave timestamp value) caused a full 65-file validator regression that required two additional bulk operations to resolve.
* IDEAL FUTURE CHANGE: Expand `rename_last_modified_to_questions_toon.py` (or create `migrate_questions_toon_prd.py`) to also handle Markdown YAML front-matter files. The script should: (1) detect `last_modified_utc:` on any line 1–25, (2) replace both the key AND set the value to `null` in one pass, (3) confirm no `questions_toon: "timestamp"` residual after rename. Add a post-run validator call to catch any misses before committing.
* CAN WORK CONTINUE: yes (resolved — all 66 PRDs now pass)

---

## OQ-37: `validate_lupopedia_headers_universal.py` did not include `questions_toon` in the null-allowlist

* WHEN: 20260415050000
* WHO: Auggie (Actor 116)
* FILE / AREA: `lupo-scripts/validate_lupopedia_headers_universal.py` — required-fields loop (~line 1456)
* TYPE: implementation_note
* SEVERITY: important_non_blocking
* STATUS: resolved
* WHAT: After the field rename, `validate_lupopedia_headers_universal.py` emitted `[ERROR] for_auggie.md: required header field 'questions_toon' is null` even though the field specification (PRD 16 §4.2 field 6, §19) explicitly states that `null` is always valid for `questions_toon`. The error came from the generic required-fields loop that rejects any `None` value unless the field is on the explicit allowlist (`content_id`, `pk_id`, `module`). The `questions_toon` field was not added to that allowlist when `validate_questions_toon()` was written.
* WHY: `validate_questions_toon()` correctly allows `null`, but the required-fields loop runs BEFORE the per-field validators and short-circuits on `None` without reaching the field-specific function. This is a validator architecture issue: two independent null-checks with inconsistent allowlists. Any new nullable field added in the future will hit the same trap unless it is manually added to the allowlist at the same time it is added to the per-field validator.
* IDEAL FUTURE CHANGE: Refactor the required-fields loop so that fields with dedicated per-field validators are automatically bypassed (the per-field validator is the authority for null handling). One approach: maintain a `NULLABLE_FIELDS = frozenset({'content_id', 'pk_id', 'module', 'questions_toon'})` constant in `header_spec_v3_1.py` and reference it from the loop. This prevents the allowlist from drifting out of sync with the spec.
* CAN WORK CONTINUE: yes (resolved — `questions_toon` added to null-allowlist in this session)

---

## OQ-38: Phase 3 corpus cleanup (`# REMOVE after Phase 3` blocks) has no owner, no trigger, and no timeline

* WHEN: 20260414000000
* WHO: Claude Code (Actor 116)
* FILE / AREA: All files tagged `# REMOVE after Phase 3` (import scripts, validators, generators — see CHANGELOG 2026-04-14)
* TYPE: open_question
* SEVERITY: future_improvement
* STATUS: resolved
* RESOLUTION (20260414000000): Phase 3 completion criteria defined in PRD 16 §19.5 Phase 3.1 Completion Checklist. Script stub created: `lupo-scripts/remove_phase3_legacy_support.py` (prints checklist on run, refuses to act until WOLFIE confirms trigger). Trigger: 0 `HDR_LAST_MODIFIED_RENAMED` warnings for 2 consecutive sessions + no federation node carries `last_modified_utc`. Owner: WOLFIE or Actor 116. Execution: run the stub script after confirming all 7 checklist items.
* WHAT: The Phase 2 backward-compatibility strategy for `last_modified_utc` → `questions_toon` left deliberate `# REMOVE after Phase 3` comments in every backward-compat block across ~15 Python scripts. Phase 3 is defined as "corpus sweep confirming zero remaining `last_modified_utc` occurrences in YAML headers, then removing all backward-compat code." No session has defined: (a) what constitutes a complete corpus sweep confirmation, (b) who triggers Phase 3, (c) what the target date is, or (d) whether a migration log entry or OQ closure is the completion signal.
* WHY: Without a defined trigger, Phase 3 will silently drift. The backward-compat blocks add cognitive overhead to every script review, and their `# REMOVE` comments will become stale noise. If Phase 3 is never triggered, the `HDR_LAST_MODIFIED_RENAMED` warning path stays live indefinitely, potentially masking real violations. If Phase 3 is triggered prematurely (before all live environments have swept their file stores), import pipelines may reject files that still carry `last_modified_utc`.
* IDEAL FUTURE CHANGE: Define Phase 3 trigger criteria: validator run over full corpus returns 0 `HDR_LAST_MODIFIED_RENAMED` warnings for 2 consecutive sessions; confirm no production file stores (federation nodes) carry `last_modified_utc` in header position 6. Once criteria are met, WOLFIE or Actor 116 runs a sweep script to delete all `# REMOVE after Phase 3` blocks, removes `last_modified_utc` from `LEGACY_KEYS_V4`, and closes this OQ.
* CAN WORK CONTINUE: yes (backward-compat blocks are non-breaking; no immediate urgency)

---

## OQ-39: `atoms_toon` header field and THOTH verification anchor relationship is undefined

* WHEN: 20260414000000
* WHO: Claude Code (Actor 116)
* FILE / AREA: `for_claude.md`, `lupo-docs/prd/16_lupopedia_headers.md`, `lupo-agents/thoth/`, `lupo-database/lupopedia/json/lupo_atoms.json`
* TYPE: open_question
* SEVERITY: p1_blocker_for_atoms_toon_enforcement
* STATUS: open
* WHAT: `for_claude.md` specifies that the `atoms_toon` header field will contain a "THOTH verification anchor" as one of its immutable per-file constants. THOTH currently has no code that reads any header field, and no code that reads or validates `.atoms.toon` files. The `lupo_atoms` DB table (`atom_name / context_id / value_json` key-value store) exists but its relationship to the per-file `.atoms.toon` file concept is undefined and may be coincidental naming only.
* WHY: If `atoms_toon` will eventually be required for THOTH verification, the field format and path convention must be defined before any enforcement can be added to the validator. If the relationship to `lupo_atoms` DB rows is intentional, that bridge must be documented. Without this definition, the `atoms_toon` field cannot be more than a nullable pointer with no enforcement, and THOTH cannot gate on it.
* IDEAL FUTURE CHANGE: (a) Define whether `.atoms.toon` files are standalone JSON sidecars or representations of `lupo_atoms` DB rows or both. (b) Define the THOTH verification anchor format and where it lives inside `.atoms.toon`. (c) Decide whether THOTH reads `.atoms.toon` files at validation time or reads `lupo_atoms` DB rows or neither. (d) Once defined, update PRD 43 (trust ladder PKs) and the atoms_toon_schema.md doctrine with the binding specification. Until then, the `atoms_toon` field is nullable and existence is NOT enforced by the validator.
* CAN WORK CONTINUE: yes — migration proceeds with `atoms_toon` nullable, no `.atoms.toon` existence enforcement, THOTH anchor deferred

---

## OQ-40: Six files have non-null `module` values that cannot be auto-converted to `atoms_toon`

* WHEN: 20260415050000
* WHO: Claude Code (Actor 116) via `migrate_module_to_atoms_toon.py`
* FILE / AREA: Six files below
* TYPE: migration_decision_required
* SEVERITY: p2_migration_cleanup
* STATUS: open
* WHAT: During the `module` → `atoms_toon` migration (PRD 16 v4.0.99 field 21), 6 files were found with non-null `module` values (subsystem labels). These cannot be auto-converted because `atoms_toon` must be either `null` or a path ending in `.atoms.toon`. The old values ("constitution", "agents", "architecture", "orchestration", "governance") were subsystem labels — not file paths — and have no direct equivalent in the new field semantics.
* FILES FLAGGED:
  1. `lupo-docs/prd/00_root_constitutional_system_requirements.md` — `module: "constitution"`
  2. `lupo-docs/prd/08_core_agents_system.md` — `module: "agents"`
  3. `lupo-docs/prd/43_parent_child_trust_ladder.md` — `module: "architecture"`
  4. `lupo-docs/prd/81_agent_orchestration_chat.md` — `module: "orchestration"`
  5. `lupo-docs/prd/99_limits_for_everything_and_why.md` — `module: "governance"`
  6. `lupo-docs/prd/decisions/pseudocode/02_channels_discussions_constitutionpseudo.md` — `module: "orchestration"`
* WHY: The subsystem label was informational only. It is not a file path and cannot be used as an `atoms_toon` value. Options: (a) set `atoms_toon: null` and drop the subsystem label, (b) set `atoms_toon: null` and preserve the subsystem label as a YAML comment, (c) create actual `.atoms.toon` sidecar files for these domains (requires defining atoms content first). Current validators emit `HDR_MODULE_DEPRECATED` WARN for these files; they are NOT blocked.
* IDEAL FUTURE CHANGE: Decide whether to drop the subsystem labels or create real `.atoms.toon` files for constitution/agents/architecture/orchestration/governance domains. If creating atoms files: define the atoms content first (see OQ-39), then create the sidecars, then set `atoms_toon:` to the sidecar path. If dropping: manually update these 6 files to set `atoms_toon: null`.
* CAN WORK CONTINUE: yes — validators accept `module` as legacy alias (WARN only); files remain valid during migration; no blocker
