# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/roles/installer.md"
  file_hash: "e5defe2143a0f2fe7585da6ff1cd8cc5e33ed6f852cf9aa41dc99e3beda6f5b3"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-channels\0\roles\installer.md"
  file_hash: "498fb52a1b491681c9d48cbc2da4751e93cb74b210a0460c1764f9e91fd76be5"
  file_path_from_root: "lupo-channels\0\roles\installer.md"
  file_hash: "c57052f6d328c525fa06fa073a3755bf8ed6c69882446bcbc9f4f413dfab6cb7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for installer.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "roles", "installermd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
role_id: installer
channel_id: 0
authority_level: elevated
granted_by: 10000
derived_from:
  - "installation_workflow"
  - "upgrade_workflow"
permissions:
  - execute_install_wizard
  - load_legacy_schema
  - seed_initial_data
  - create_workspace_directories
  - import_broadcasts
  - validate_installation
assigned_to:
  - 10000
created_utc: "2026-02-25T09:05:00Z"
updated_utc: "2026-02-25T09:05:00Z"
---

# Role: Installer

## Authority

**Level:** Elevated  
**Scope:** Installation and upgrade operations  
**Granted By:** Captain (10000)

## Description

Installers are responsible for executing the Lupopedia installation wizard, loading legacy Crafty Syntax schemas, seeding initial data, and validating installations. This role is active during installation and upgrade operations only.

## Permissions

### Installation Operations
- Execute `install.php` wizard
- Load Crafty Syntax 3.7.5 legacy schema
- Create all Lupopedia tables
- Seed registry data
- Configure initial settings

### Workspace Provisioning
- Create channel directories
- Create actor workspace directories
- Generate README files
- Set directory permissions

### Data Import
- Import legacy broadcasts
- Import legacy artifacts
- Validate metadata
- Report import errors

### Validation
- Verify table creation
- Validate schema against TOONs
- Check registry seeding
- Run post-install tests

## Assigned Actors

- **10000** - Captain (Human)

## Responsibilities

1. **Fresh Installation**
   - Execute installation wizard
   - Create all tables
   - Seed registry data
   - Provision workspaces

2. **Upgrade from Crafty 3.7.5**
   - Load legacy schema
   - Run upgrade wizard
   - Migrate data
   - Preserve configuration

3. **Validation**
   - Verify installation success
   - Check for errors
   - Run validation scripts
   - Document issues

4. **Documentation**
   - Create post-install checklist
   - Document any issues
   - Update installation guide

## Constraints

- Can only operate during installation/upgrade
- Must follow installation workflow exactly
- Must validate all operations
- Must document all errors

## Success Criteria

- All tables created successfully
- All registry data seeded
- All workspaces provisioned
- All broadcasts imported
- No validation errors

## Escalation

Installers report to System Administrators. Any installation failures must be escalated immediately.

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "install.php",
    "lupo-database/migrations/install_new_lupopedia.sql",
    "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql"
  ],
  "implements": "installation_authority_model",
  "depends_on": "system_admin",
  "role_category": "operations",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
