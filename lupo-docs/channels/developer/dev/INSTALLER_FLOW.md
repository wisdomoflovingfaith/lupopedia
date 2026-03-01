# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\developer\dev\INSTALLER_FLOW.md"
  file_hash: "9687dee45a244998c3261372a1cb2994d6bf655b7e861999867ea5a0502a2f1a"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\developer\dev\INSTALLER_FLOW.md"
  file_hash: "00dababcc388d5445c20de38094081f5c616f9b73458c4669f5a95f169d7f1ae"
  file_path_from_root: "docs\channels\developer\dev\INSTALLER_FLOW.md"
  file_hash: "64340041485265d9441bd76bf2fac4280d6d21490a0a28438d33da71c3777a07"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for INSTALLER_FLOW.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "developer", "dev", "installer_flowmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

START
│
├── 1. Detect Environment
│     ├── Determine filesystem paths
│     ├── Determine public path
│     ├── Detect PHP version, extensions, permissions
│     └── Detect database driver (mysql/postgres)
│
├── 2. Check for Existing Config
│     ├── Look for private config: ../lupopedia-config.php (outside web root)
│     ├── If not found, look for local config: ./lupopedia-config.php (fallback)
│     ├── If config exists AND old config.php NOT found → LOAD CONFIG → GO TO STEP 7
│     └── If config exists AND old config.php found → Enter Upgrade Mode (see UPGRADE FLOW below)
│
├── 2a. UPGRADE MODE (if config.php detected)
│     ├── Detect config.php file (Crafty Syntax 3.7.5 config - only exists in old installations)
│     ├── Parse config.php to extract database credentials
│     ├── Create lupopedia-config.php from old config
│     ├── Backup old config.php to config.php.backup
│     ├── Detect livehelp_* tables in database
│     ├── Run upgrade wizard:
│     │     ├── Step 1: Show detected livehelp_* tables, prompt to run migration
│     │     ├── Step 2: Execute /database/migrations/craftysyntax_to_lupopedia_mysql.sql
│     │     │         └── Migration SQL is complete and ready for production (version 3.0.3)
│     │     ├── Step 3: User verifies data migrated correctly
│     │     ├── Step 4: Drop all livehelp_* tables (with user confirmation)
│     │     └── Step 5: Upgrade complete, redirect to Lupopedia
│     └── Migration SQL is hand-written, authoritative, and production-ready (see MIGRATION_DOCTRINE.md)
│
├── 3. Run Setup Wizard (FRESH INSTALL ONLY)
│     ├── Display welcome screen
│     ├── Ask for database credentials
│     ├── Ask for table prefix (version 3.1.0+: user-selected, default 'lupo_')
│     │     └── NOTE: Upgrades (3.7.5→3.0.3) enforce 'lupo_' prefix, no user choice
│     ├── Ask for node configuration (auto-detected from URL, user can override)
│     ├── Ask for admin account details
│     └── Validate inputs
│
├── 4. Write Config File
│     ├── Prefer private directory (one level above public)
│     ├── If not writable → fallback to local config
│     └── Write lupopedia-config.php (includes LUPO_PREFIX definition)
│
├── 5. Initialize Database
│     ├── Connect using provided credentials
│     ├── Create database if user selected "create"
│     ├── Select database
│     ├── Run schema installer:
│     │     ├── /database/install/mysql/schema.sql   (if MySQL)
│     │     └── /database/install/postgres/schema.sql (if PostgreSQL)
│     └── Insert kernel seeds:
│           ├── node_id = 1 (local node)
│           ├── channel_id = 0 (system/kernel)
│           ├── actor_id = 0 (system actor)
│           ├── thread_id = 2 (kernel thread)
│           └── message_id = 2 (kernel boot)
│
├── 6. Create Installation Node
│     ├── Detect node from URL
│     ├── Ask user to confirm node configuration
│     ├── Insert node_id = 1 (local installation node) into federation_nodes (Federation Layer) with domain_name, domain_root, and install_url
│     ├── DO NOT modify node_id 1 (local node)
│     └── DO NOT auto-create additional nodes
│
├── 7. Run Crafty Syntax Migration (Optional - for separate database migration)
│     ├── NOTE: This step is for migrating from a separate Crafty Syntax database
│     ├── For same-database upgrades, use UPGRADE MODE (Step 2a) instead
│     ├── Detect old_craftysyntax database
│     ├── Ask user if they want to migrate
│     ├── If yes:
│     │     ├── Load /database/migrations/craftysyntax_to_lupopedia_mysql.sql
│     │     └── Execute migration SQL (hand-written, authoritative)
│     └── If no → skip
│
├── 8. Create Admin User
│     ├── Insert into actors (actor_type = 'user')
│     ├── Insert into auth_users
│     ├── Link admin to node_id = 1 (local node)
│     └── Link admin to channel_id = 1
│
├── 9. Finalize Installation
│     ├── Write installation lock file
│     ├── Display success screen
│     └── Redirect to Lupopedia home
│
END