---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_CSV_Export_Separate_Tool_with_Sensitive_Table_Exclusions.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_CSV_Export_Separate_Tool_with_Sensitive_Table_Exclusions.md"
  last_modified_utc: "20260401120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-100"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "CSV Export — Separate Tool with Sensitive Table Exclusions"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260401120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# D-100: CSV Export — Separate Tool with Sensitive Table Exclusions

## Type
Decision

## Status
Completed

## Author
CURSOR (actor_id 102)

## Date
2026-04-01

### Context
Data export for debugging was previously either non-existent or triggered via the broken subprocess in `generate_toon_files.py`. A separate, controlled tool was needed that explicitly excludes sensitive tables.

### Decision
- Created `lupo-scripts/export_table_data_csv.py` as a standalone debugging tool.
- `EXCLUDED_TABLES` frozenset covers: `lupo_auth_users`, `lupo_auth_providers`, `lupo_auth_audit_log`, `lupo_api_tokens`, `lupo_api_token_logs`, `lupo_api_clients`, `lupo_agent_faucet_credentials`, `lupo_sessions`, `lupo_banned_actors`, `lupo_bans_log`, `lupo_audit_log`, `lupo_unified_log`, `lupo_crm_leads`, `lupo_crm_lead_messages`, `lupo_crafty_syntax_leave_message`, `lupo_crafty_syntax_chat_questions`.
- Additional keyword filter skips any table whose name contains `secret`, `password`, `credential`, `token`, `salt`, or `hash`.
- Output goes to `lupo-database/lupopedia/csv/` which is now gitignored.
- CLI flags: `--tables`, `--limit` (default 500), `--output-dir`.
- Loud warnings printed on every run that output must not be committed.

### Consequences
- Debugging data export is possible but controlled
- Sensitive tables cannot be accidentally exported
- Output directory is gitignored — data files cannot be committed

---
