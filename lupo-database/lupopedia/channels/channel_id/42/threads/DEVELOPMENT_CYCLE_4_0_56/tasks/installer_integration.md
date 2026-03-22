# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/installer_integration

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
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/installer_integration.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:31Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/installer_integration.md"
  file_hash: "fb6b1c8a33b7688882b536b56df00232a0f8e9d2e6a821a5376bd17c665cc0b6"
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
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "threads"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/installer_integration.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/installer_integration"]

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
  file_path_from_root: "lupo-channels\0\tasks\active\installer_integration.md"
  file_hash: "bd70a4c3c77211455473dba6d3f61ec122cbf788b9801743c2808236e51e05c4"
  file_path_from_root: "lupo-channels\0\tasks\active\installer_integration.md"
  file_hash: "84040f35d0f793b2be6127059918155a644ac1f18becb1c171dc9ac79c3c4fe3"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 1003
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for installer_integration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "tasks", "active", "installer_integrationmd"]
  lupo_agent: "cursor"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
task_id: CH0-20260225-004
channel_id: 42
owner_actor_id: 10000
assigned_to:
  - 10000
status: active
priority: medium
created_utc: "2026-02-25T08:45:00Z"
depends_on:
  - CH0-20260225-001
  - CH0-20260225-002
  - CH0-20260225-003
blocks: []
task_type: integration
estimated_duration: "3 hours"
---

# Task: Installer Integration and Testing

## Objective

Ensure `install.php` correctly creates all tables, seeds registry data, and integrates with the new channel-scoped actor workspace architecture.

## Context

The installer is the primary entry point for new Lupopedia installations and Crafty Syntax 3.7.5 upgrades. It must:
1. Create all tables from `install_new_lupopedia.sql`
2. Seed registry data from the three seeding SQL files
3. Create channel directories and actor workspaces
4. Import legacy broadcasts and artifacts

## Steps

1. **Review installer code**
   - File: `install.php`
   - Verify SQL execution order
   - Check error handling
   - Review post-install hooks

2. **Test fresh install workflow**
   - Drop all tables
   - Run `install.php`
   - Verify table creation
   - Verify registry seeding
   - Check for errors

3. **Test upgrade workflow**
   - Load Crafty 3.7.5 schema
   - Load Crafty config
   - Run `install.php` in upgrade mode
   - Verify migration
   - Check data preservation

4. **Add workspace provisioning**
   - Create channel directories during install
   - Create actor workspace directories
   - Create README files for each actor
   - Set proper permissions

5. **Add broadcast import**
   - Import existing broadcasts from `lupo-channels/*/broadcasts/`
   - Validate metadata
   - Report any issues

6. **Create post-install checklist**
   - Document: `lupo-docs/installation/post_install_checklist.md`
   - List verification steps
   - Include troubleshooting guide

## Success Criteria

- ✅ Fresh install creates all tables
- ✅ Fresh install seeds all registry data
- ✅ Upgrade preserves Crafty data
- ✅ Upgrade creates new Lupopedia tables
- ✅ Workspace directories created automatically
- ✅ Broadcasts imported and validated
- ✅ Post-install checklist documented

## Risks

- **Install failure:** SQL errors may prevent installation
- **Data loss:** Upgrade may fail to preserve Crafty data
- **Permission issues:** Workspace directories may not be writable
- **Broadcast corruption:** Invalid metadata may break import

## Notes

This task integrates all previous work into the installer. It's the final step before 4.0.45 release.

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "install.php",
    "lupo-database/migrations/install_new_lupopedia.sql",
    "lupo-database/migrations/seed_registry_comprehensive_4.0.45.sql",
    "lupo-database/migrations/seed_registry_open_4.0.45.sql",
    "lupo-database/migrations/seed_actors_agents_4.0.45.sql"
  ],
  "implements": "installer_workspace_integration",
  "depends_on": [
    "CH0-20260225-001",
    "CH0-20260225-002",
    "CH0-20260225-003"
  ],
  "blocks": [],
  "task_category": "integration",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
