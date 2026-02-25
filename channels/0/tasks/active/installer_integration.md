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
