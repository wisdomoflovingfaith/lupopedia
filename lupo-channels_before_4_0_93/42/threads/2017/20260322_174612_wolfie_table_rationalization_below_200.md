---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-channels/42/threads/2017/20260322_174612_wolfie_table_rationalization_below_200.md"
  last_modified_utc: "20260322_174612"
  channel_id: 42
  thread_id: 2017
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "analysis_report"
  artifact_kind: "table_rationalization"
  purpose: "Assess 202-table state and define safe reduction path below 200 without breaking Lupopedia runtime."
---

# Table Rationalization Below 200 (Thread 2017)

## Current State
- Total tables present (from generated TOONs): 202
- `livehelp_*` legacy tables present: 34
- `lupo_*` tables present: 168

## Key Finding
- `livehelp_*` tables are not created by `install_new_lupopedia.sql`.
- They exist after Crafty upgrade flow if the "drop deprecated livehelp_* tables" option is not selected.
- Therefore, reducing below 200 should be done by dropping legacy `livehelp_*` tables post-import, not by modifying canonical install SQL for fresh installs.

## Runtime Usage Assessment (PHP)
- Runtime Lupopedia paths are centered on `lupo_*` tables.
- `livehelp_*` references in active PHP are primarily in upgrade/installer tooling and diagnostics:
  - installer detection/normalization/import/drop flow
  - setup helpers and migration diagnostics
- No evidence that modern runtime chat/content/auth flows require `livehelp_*` tables after successful migration.

## Recommended Safe Reduction Plan
1. Full DB backup before any drop.
2. Verify migration completeness (sample checks):
   - `lupo_auth_users` populated
   - `lupo_sessions` populated
   - `lupo_crafty_user_mapping` and `lupo_crafty_syntax_*` tables populated as expected
3. Drop all 34 `livehelp_*` tables.
4. Regenerate TOON files and verify table count < 200.

## Candidate Drop Set (34 legacy tables)
- livehelp_autoinvite
- livehelp_channels
- livehelp_config
- livehelp_departments
- livehelp_emailque
- livehelp_emails
- livehelp_identity_daily
- livehelp_identity_monthly
- livehelp_keywords_daily
- livehelp_keywords_monthly
- livehelp_layerinvites
- livehelp_leads
- livehelp_leavemessage
- livehelp_messages
- livehelp_modules
- livehelp_modules_dep
- livehelp_operator_channels
- livehelp_operator_departments
- livehelp_operator_history
- livehelp_paths_firsts
- livehelp_paths_monthly
- livehelp_qa
- livehelp_questions
- livehelp_quick
- livehelp_referers_daily
- livehelp_referers_monthly
- livehelp_sessions
- livehelp_smilies
- livehelp_transcripts
- livehelp_users
- livehelp_visits_daily
- livehelp_visits_monthly
- livehelp_visit_track
- livehelp_websites

## Expected Result
- 202 - 34 = 168 tables
- System moves well below 200 without touching core Lupopedia schema.

## Optional Follow-up Audit
- Secondary pass for additional low-value tables can be done after livehelp drop, but should be evidence-based and runtime-traced before any install SQL edits.
