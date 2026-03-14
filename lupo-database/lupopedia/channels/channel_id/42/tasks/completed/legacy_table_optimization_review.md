# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/completed/legacy_table_optimization_review

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/completed/legacy_table_optimization_review.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:11Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/tasks/completed/legacy_table_optimization_review.md"
  file_hash: "2ddb455bff479c135646d7aa0c60597d33bd24652ce542336d21282060674f2a"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "tasks"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/completed/legacy_table_optimization_review.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/completed/legacy_table_optimization_review"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\42\tasks\completed\legacy_table_optimization_review.md"
  file_hash: "38c8292d99a420bfc877726eea7a05b0adfde1968f8c1861ea8790146149c7cd"
  file_path_from_root: "lupo-channels\42\tasks\completed\legacy_table_optimization_review.md"
  file_hash: "ea1725eb1eea5b6c784d41d31380ebf28f229ee583ebf88afc8295fc7c35227b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for legacy_table_optimization_review.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "tasks", "completed", "legacy_table_optimization_reviewmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
task_id: "CH0-20260226-005"
channel_id: 42
assigned_to: [1001]
status: "completed"
priority: "high"
created_utc: "20260226"
target_version: "4.0.49"
rolled_from: "4.0.48"
task_type: "database_optimization"
---

# 🔍 Legacy Table Optimization Review

**Task ID:** CH0-20260226-005  
**Assigned:** Windsurf (1001)  
**Priority:** High  
**Status:** ✅ Completed  

## Objective
Review all legacy Crafty Syntax tables used in Lupopedia and establish them as READ-ONLY reference tables with proper documentation.

## Scope
1. ✅ Reviewed all 34 legacy tables from Crafty Syntax.
2. ✅ Confirmed tables follow Lupopedia's "Dumb Storage" doctrine (no FKs).
3. ✅ Established READ-ONLY policy for all legacy `livehelp_*` tables.
4. ✅ Added v4.1.1+ deprecation warnings in documentation.

## Key Findings

### Legacy Table Policy
- **Status**: All legacy `livehelp_*` tables are READ-ONLY reference tables
- **Deprecation**: Planned for v4.1.1+ removal
- **Access Pattern**: Only for migration reference and historical data
- **No Modifications**: Schema changes prohibited - preserve as-is

### Tables Reviewed (34 total)
All `livehelp_*` tables confirmed as reference-only:
- livehelp_autoinvite, livehelp_channels, livehelp_config, livehelp_departments
- livehelp_emailque, livehelp_emails, livehelp_identity_daily/monthly
- livehelp_keywords_daily/monthly, livehelp_layerinvites, livehelp_leads
- livehelp_leavemessage, livehelp_messages, livehelp_modules, livehelp_operators
- livehelp_operator_channels/departments/history, livehelp_paths_firsts/monthly
- livehelp_qa, livehelp_questions, livehelp_quick, livehelp_referers_daily/monthly
- livehelp_sessions, livehelp_smilies, livehelp_transcripts, livehelp_users
- livehelp_visit_track, livehelp_visits_daily/monthly, livehelp_websites

### Documentation Updates
- ✅ Added READ-ONLY warnings to all legacy table documentation
- ✅ Updated migration references to point to new `lupo_*` tables
- ✅ Created `livehelp_migrations_readme.md` pointer file
- ✅ Established clear deprecation timeline (v4.1.1+)

## Completion Summary
The legacy table optimization is complete. All legacy tables are properly documented as READ-ONLY reference tables with clear deprecation planning. No schema modifications were made to preserve historical data integrity.

## Next Steps
- Monitor legacy table usage in production
- Plan v4.1.1+ deprecation implementation
- Ensure all new development uses `lupo_*` tables
