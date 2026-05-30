---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/versions/4.1.3/TODO.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.3/TODO.md"
  status: "active"
  when_updated: "20260419190851"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/version-4-1-3-todo.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/version-4-1-3-todo"
  artifact_type: "version-doc"
  artifact_kind: "version-specific"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "version-4-1-3-todo"
  default_collection_id: null
  lupopedia.schema: "version-doc"
  title: "Lupopedia 4.1.3 TODO -- Dependency chain A through E (Crafty parity substrate)"
  summary: "PRD-first dependency graph A to E for 4.1.3 Crafty 3.7.5 live-help parity; F reserved for 4.1.4+ orchestration. Layer A ASCII audit via lupo-scripts/sanitize_ascii.py --fix."
---
lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  generated_by: "cascade"
  validation_status: "pending"
  ascii_compliance: "confirmed"
  last_validated: "20260420094300"
---

# TODO -- Lupopedia Dependency Chain (Target: Stable by June 1, 2026)

**Scope of 4.1.3:** Minimal stable Crafty Syntax 3.7.5 live-help parity + clean substrate.  
Multi-AI orchestration starts in 4.1.4+.

## Scheduled for 2026-04-21
- Full installer test cycle
  - Verify clean base install (10000 + 10001 seeded)
  - Verify wizard flow including optional legacy import step
  - Validate JSON-schema column enforcement
  - Validate mapping table creation and sequential ID remap
  - Validate user_id updates in the 4 legacy tables
  - Confirm installer/wizard separation behaves correctly
  - Confirm structured results returned to wizard

## Completed
- Installer updated to run only install_new_lupopedia.sql
- Removed all livehelp_* references from base installer
- Wizard updated with optional "Import legacy Crafty Syntax data" step
- Import wrapper implemented (mapping table, sequential IDs, limit enforcement)
- Import script updated (no FK constraints, 5-table user_id rewrite)
- JSON schema enforcement implemented across installer + import
- Documentation updated with mapping summary and import flow

**Dependency Graph**

A -> B -> C -> D -> E   (4.1.3 ends here)  
          v  
          F   (Multi-AI Orchestration - 4.1.4+)

---

# DATABASE INSTALL / IMPORT STATUS

**Status**: CRITICAL - Import script unsafe to run as-is

## Current State Assessment

| Source | Table Count | Status |
|--------|-------------|--------|
| `install_new_lupopedia.sql` (canonical) | **142** CREATE TABLE statements | Canonical DDL |
| `old_crafty_syntax_3_7_5_start.sql` (legacy) | **34** CREATE TABLE statements | Legacy Crafty 3.7.5 |
| `import_from_old_crafty_syntax.sql` (import) | Claims "199 core tables" | **STALE - actual is 142** |

## Migration Execution Order (Mandatory -- Do Not Deviate)

The three migration scripts MUST run in this exact order.
Running them out of order is the root cause of "tables came back" incidents.

  Step 1  Restore Crafty 3.7.5 source into database (all 34 livehelp_* tables present)
  Step 2  Run install_new_lupopedia.sql (creates 142 target tables)
  Step 3  Verify seed row: modules.module_id = 1 exists
  Step 4  Run import_from_old_crafty_syntax.sql (NO --force, NO --ignore-errors)
  Step 5  Run drop_old_crafty_syntax_tables.sql (LAST -- irreversible)

INVARIANT: import_from_old_crafty_syntax.sql requires all 34 livehelp_* tables
to exist at run time. Every section opens with ALTER TABLE livehelp_xxx, which
fails immediately if the source table is absent. This is intentional: the script
reads FROM livehelp_* and writes INTO Lupopedia tables. The DROP removes the
sources after migration is confirmed complete.

RE-RUN RULE: If you must re-run the import (e.g. data integrity check failed),
you must restore the Crafty 3.7.5 source dump first (return to Step 1).
The import cannot run after the DROP has executed.

BLOCKED TABLES (intentional -- no data migrated):
  - livehelp_channels         -> pending product decision (transient vs persistent chat history)
  - livehelp_operator_channels -> deferred (depends on channels decision above)
  These two tables are ALTERed and COMMENTed in the import but contain no INSERT.
  They will be dropped with the rest. Their data is intentionally not migrated in 4.1.3.

## Critical Blockers

1. **Missing Tables**: Import script INSERTs into `actor_filesystem` and `actor_sync_state` (ejected 4.1.2)
2. **Destructive TRUNCATE**: Core tables cleared (dialog_messages, visits, paths, etc.) - only safe on fresh install
3. **Broken Actor Linkage**: Imported operators lack `auth_user_id` mapping ✅ FIXED
4. **Stale Documentation**: Import header claims 199 tables vs actual 142
5. **Incomplete Mappings**: Some sections only ALTER legacy tables, no INSERT into Lupopedia targets
6. **Doctrine Violations**: MySQL-specific SQL breaks cross-platform compatibility

## Files Reviewed

- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (canonical DDL)
- `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` (import script)
- `lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql` (legacy schema)
- `lupo-docs/prd/13_crafty_integration.md` (integration specification)

## Immediate Action Required

Import script must be corrected before any data migration attempts.

---

# CRAFTY TO LUPOPEDIA TABLE MAPPING

## Directly Mapped Tables

| Legacy Table | Target Table(s) | Status |
|--------------|----------------|--------|
| `livehelp_autoinvite` | `crafty_syntax_auto_invite` | DIRECTLY MAPPED |
| `livehelp_departments` | `departments`, `department_metadata` | DIRECTLY MAPPED |
| `livehelp_emails` | `crm_lead_messages` | DIRECTLY MAPPED |
| `livehelp_layerinvites` | `crafty_syntax_layer_invites` | DIRECTLY MAPPED |
| `livehelp_leads` | `crm_leads` | DIRECTLY MAPPED |
| `livehelp_leavemessage` | `crafty_syntax_leave_message` | DIRECTLY MAPPED |
| `livehelp_operator_departments` | `actor_departments` | DIRECTLY MAPPED |
| `livehelp_operator_history` | `audit_log` | DIRECTLY MAPPED |
| `livehelp_paths_firsts`, `livehelp_paths_monthly` | `paths` | DIRECTLY MAPPED |
| `livehelp_qa` | `truth_questions`, `truth_answers` | SPLIT ACROSS TABLES |
| `livehelp_questions` | `crafty_syntax_chat_questions` | DIRECTLY MAPPED |
| `livehelp_quick` | `actor_reply_templates` | DIRECTLY MAPPED |
| `livehelp_referers_daily`, `livehelp_referers_monthly` | `referers` | DIRECTLY MAPPED |
| `livehelp_transcripts` | `dialog_threads`, `dialog_messages` | SPLIT ACROSS TABLES |
| `livehelp_users` | `auth_users`, `actors` | SPLIT ACROSS TABLES |
| `livehelp_visits_daily`, `livehelp_visits_monthly`, `livehelp_visit_track` | `visits` | DIRECTLY MAPPED |
| `livehelp_websites` | `federation_nodes` | DIRECTLY MAPPED |

## Tables Needing Decision

| Legacy Table | Target | Issue |
|--------------|--------|-------|
| `livehelp_channels` | `channels` | Only ALTER statements, no INSERT in import |
| `livehelp_config` | `modules` | Only UPDATE to existing row (id=1) |
| `livehelp_messages` | `dialog_messages` | Comment says maps, but no INSERT present |
| `livehelp_modules` | `modules` | Only ALTER statements, no INSERT |
| `livehelp_operator_channels` | `channels` | Only ALTER statements, no INSERT |

## Dropped Tables

| Legacy Table | Reason |
|--------------|--------|
| `livehelp_emailque` | Out of scope per comment |
| `livehelp_identity_daily` | No Lupopedia target |
| `livehelp_identity_monthly` | No Lupopedia target |
| `livehelp_keywords_daily` | No Lupopedia target |
| `livehelp_keywords_monthly` | No Lupopedia target |
| `livehelp_modules_dep` | Explicitly not migrated, UI-driven |
| `livehelp_sessions` | Dropped per comment |
| `livehelp_smilies` | Replaced by token system |

## Critical Mapping Issues

1. **Actor Linkage**: `livehelp_users` → `actors` missing `auth_user_id` mapping 
2. **Module/Channel Dependencies**: Several tables expect pre-existing seed rows
3. **Incomplete Sections**: Some mappings documented but not implemented in SQL

---

# INCOMPLETE MAPPINGS STATUS

**Identified**: 6 legacy tables with incomplete or missing data migration  
**Documentation**: `DATABASE_INCOMPLETE_MAPPINGS.md` created with full analysis

## Incomplete Mapping Summary

| Status | Count | Tables |
|--------|-------|--------|
| PREP ONLY | 1 | livehelp_operator_channels |
| PARTIALLY IMPORTED | 1 | livehelp_config |
| NOT IMPORTED | 1 | livehelp_messages |
| PATCHED IN SQL | 1 | livehelp_modules |
| NEEDS PRODUCT DECISION | 1 | livehelp_channels |
| INTENTIONALLY DROPPED | 1 | livehelp_modules_dep |

## Decision Required

4 tables require decisions on import strategy:
- **Channels**: Import legacy or create via wizard?
- **Operator-Channel Links**: Preserve relationships?
- **Active Chat Messages**: Worth recovery complexity?

Note: Modules have been implemented and removed from decision queue.

## Classification Results

| Bucket | Count | Tables |
|--------|-------|--------|
| Patched in SQL | 1 | livehelp_modules |
| Approved with Precondition | 1 | livehelp_config |
| Needs Product Decision | 1 | livehelp_channels |
| Deferred | 2 | livehelp_operator_channels, livehelp_messages |
| Intentionally Dropped | 1 | livehelp_modules_dep |

**Total Classified**: 6 tables  
**Remaining Work**: 4 tables (excluding intentionally dropped)

## Next Actions

- [x] Implement livehelp_modules import (patchable now) ✅
- [x] Analyze seed dependencies and document decisions ✅
- [ ] Document livehelp_config seed dependency (approved with precondition)
- [ ] Product decision needed for livehelp_channels (transient vs persistent)
- [ ] Evaluate operator-channel relationship importance

## A - PRD & Schema Foundation (must be completed first)

- [ ] PRD 02 family finalized (channels projection, no global feed)
- [ ] install_new_lupopedia.sql and import_from_old_crafty_syntax.sql aligned and verified
- [ ] Crafty tables fully mapped per PRD 13
- [ ] Full repo audit: ASCII-only (run `python lupo-scripts/sanitize_ascii.py --fix`), headers on every touched file, PRD-first compliance
- [ ] Template-first rule strictly enforced (lupo-templates/ before any runtime UI)

Unlocks: B

## B - Channels Engine Core

- [ ] channels/index.php wiring complete using PRD 02 patterns (projection SQL only, visitor sidebar state machine, tab modes, composer with active actor targeting)
- [ ] Session routing via lupo_sessions (from/to actor/session)
- [ ] All new UI elements originate in lupo-templates/

Unlocks: C

## C - Crafty Live-Help Parity Features

- [ ] Visitor chat initiation, operator routing, presence, departments
- [ ] Canned responses (storage + quick insert into chat)
- [ ] Auto-invite, pre-chat questions, layer invites, offline messages, push URL
- [ ] Transcript persistence in dialog tables + basic reporting (visits, referrers, sessions)
- [ ] Widget/embed functional in shared-hosting / subfolder installs

Unlocks: D

## D - Installability & Stability

- [ ] Fresh install from install_new_lupopedia.sql completes cleanly
- [ ] Crafty 3.7.5 import path works with full data integrity
- [ ] Shared-hosting safe (64 MB limit, subdirectory support)
- [ ] Error handling, security baseline, session persistence across pages
- [ ] Non-AI run audit (system installs and runs without orchestration dependencies)
- [ ] Before running import: confirm all 34 livehelp_* source tables present
- [ ] Run migration scripts in documented order (Steps 1-5); verify row counts after Step 4
- [ ] Run drop_old_crafty_syntax_tables.sql as FINAL step only after Step 4 confirmed clean
- [ ] Document livehelp_channels and livehelp_operator_channels as intentionally deferred
      (no data migration in 4.1.3 -- product decision required for 4.1.4+)

Unlocks: E

## E - Stable Substrate (4.1.3 endpoint)

- [ ] Channel projection clean and stable
- [ ] Actor registry and session model locked
- [ ] Doctrine, headers, and PRD alignment fully enforced
- [ ] No drift between docs and code

This completes 4.1.3. Ship when A through E are green.

Unlocks: F

## F - Multi-AI Orchestration (Real Lupopedia Power - 4.1.4+)

- [ ] Prompt-as-artifact lifecycle (draft -> discussion -> saved artifact -> dispatch) per Micro-PRD
- [ ] HERMES routing + target actor selection
- [ ] Multi-agent command UI and cross-channel tasking
- [ ] Semantic memory graph / TOON activation
- [ ] Parallel agent orchestration chrome

**Notes**

- This is a dependency chain, not a timeline. Progress at actual speed using 12 parallel AIs.
- 4.1.3 is deliberately narrow: a stable, installable live-help base.
- The Micro-PRD "Prompt as Artifact (Deferred to 4.1.4+)" is noted and will be the first major item in F.
- High-priority unblocked items right now: Layer A, especially running `python lupo-scripts/sanitize_ascii.py --fix` across the repo and completing the PRD 02 finalization + header audit.

Migration sequencing documented and locked. Next phase: Blog only. No further database import or migration work.

---

## REMINDER: For 4.1.4+ development, you can:

- [ ] Formally remove the "thread_slug" concept from documentation and code
- [ ] Define a helper-only slug generator for filesystem operations
- [ ] Define a naming convention for .toon and .jsonl files
- [ ] Keep all slug logic OUT of the header contract

This keeps the identity model clean.

