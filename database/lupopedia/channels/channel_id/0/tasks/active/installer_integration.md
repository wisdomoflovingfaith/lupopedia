# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "database/lupopedia/channels/channel_id/0/tasks/active/installer_integration.md"
  file_hash: "31660f43c3a5a211848e9ac3054a3b0ec9819ce9a964abefb7b3df8c231111b2"
  last_updated_utc: "20260228155738"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "channels\0\tasks\active\installer_integration.md"
  file_hash: "bd70a4c3c77211455473dba6d3f61ec122cbf788b9801743c2808236e51e05c4"
  file_path_from_root: "channels\0\tasks\active\installer_integration.md"
  file_hash: "84040f35d0f793b2be6127059918155a644ac1f18becb1c171dc9ac79c3c4fe3"
  last_updated_utc: "20260228"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for installer_integration.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["channels", "0", "tasks", "active", "installer_integrationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.73"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
task_id: CH0-20260225-004
channel_id: 0
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
   - Import existing broadcasts from `channels/*/broadcasts/`
   - Validate metadata
   - Report any issues

6. **Create post-install checklist**
   - Document: `docs/installation/post_install_checklist.md`
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
    "database/migrations/install_new_lupopedia.sql",
    "database/migrations/seed_registry_comprehensive_4.0.45.sql",
    "database/migrations/seed_registry_open_4.0.45.sql",
    "database/migrations/seed_actors_agents_4.0.45.sql"
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
