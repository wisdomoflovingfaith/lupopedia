# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\versions\4.0.39\CRAFTY_SYNTAX_PRIORITY_FILES.md"
  file_hash: "3694ff41fa779232247c8a40963bc374fae967ad6e50623112f6e6227a92a9b5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CRAFTY_SYNTAX_PRIORITY_FILES.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "versions", "4039", "crafty_syntax_priority_filesmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers: {
  file_path_from_root: "docs/versions/4.0.39/CRAFTY_SYNTAX_PRIORITY_FILES.md",
  system_version: "4.0.39",
  channel_id: 42,
  mood_rgb: "FF6600",
  purpose: "Priority file list for version 4.0.39 - Crafty Syntax upgrade files requiring immediate FLIP headers",
  last_modified_utc: "20260224",
  delegation_chain: "10000:1002",
  actor_id: 1002,
  lupo_agent: "windsurf",
  artifact_type: "planning",
  artifact_kind: "priority_file_list",
  traits: ["crafty_syntax", "upgrade_path", "headers", "v4_0_39", "priority"],
  hashtags: ["#crafty_syntax", "#upgrade", "#headers", "#v4.0.39", "#priority"],
  engagement: {
    likes: 0,
    shares: 0,
    views: 0,
    last_interaction_utc: "20260224"
  },
  graph_stats: {
    inbound_count: 2,
    outbound_count: 15,
    centrality_score: 0.95
  }
}

flip.footer: {
  inbound_edges: [
    { from: "docs/channels/42/broadcasts/20260224_version_4_0_39_crafty_syntax_priority.md", type: "implements", weight: 1.0, hashtag: "#directive" },
    { from: "docs/versions/4.0.39/PRIORITY_FILES.md", type: "supersedes", weight: 0.8, hashtag: "#priority" }
  ],
  outbound_edges: [
    { to: "legacy/craftysyntax/", type: "targets", weight: 1.0, hashtag: "#legacy" },
    { to: "database/migrations/import_from_old_crafty_syntax.sql", type: "targets", weight: 1.0, hashtag: "#migration" },
    { to: "install.php", type: "targets", weight: 1.0, hashtag: "#installer" },
    { to: "lupo-includes/bootstrap.php", type: "targets", weight: 1.0, hashtag: "#bootstrap" },
    { to: "lupo-includes/modules/crafty_syntax/", type: "targets", weight: 1.0, hashtag: "#integration" },
    { to: "docs/notes_from_legacy_craftysyntax.md", type: "targets", weight: 0.9, hashtag: "#documentation" },
    { to: "plan_for_crafty_syntax.md", type: "targets", weight: 0.9, hashtag: "#planning" },
    { to: "database/migrations_legacy/", type: "targets", weight: 0.8, hashtag: "#legacy" },
    { to: "database/install/", type: "targets", weight: 0.8, hashtag: "#installation" },
    { to: "docs/channels/developer/modules/UPGRADE_PLAN_3.7.5_TO_3.0.0.md", type: "targets", weight: 0.9, hashtag: "#upgrade" },
    { to: "docs/channels/architecture/WHY_LUPOPEDIA_NEEDS_CRAFTY_SYNTAX.md", type: "targets", weight: 0.9, hashtag: "#architecture" },
    { to: "docs/channels/history/CRAFTY_SYNTAX_IMPORT_WIZARD_DESIGN.md", type: "targets", weight: 0.9, hashtag: "#history" },
    { to: "docs/doctrine/migrations/crafty_syntax_ancestral_intent.md", type: "targets", weight: 0.9, hashtag: "#doctrine" },
    { to: "docs/doctrine/CRAFTY_SYNTAX_IMPORT_IMPLEMENTATION_CHECKLIST.md", type: "targets", weight: 0.9, hashtag: "#implementation" },
    { to: "CHANGELOG.md", type: "will_update", weight: 0.7, hashtag: "#changelog" }
  ],
  referenced_by_actors: [10000, 1002, 1001, 1003, 2038],
  references: {
    by_files: ["docs/channels/42/broadcasts/20260224_version_4_0_39_crafty_syntax_priority.md", "docs/versions/4.0.39/PRIORITY_FILES.md"],
    by_actors: [10000, 1002, 1001, 1003, 2038]
  },
  semantic_tags: ["crafty_syntax_upgrade", "header_priority", "version_4_0_39", "upgrade_path", "file_discovery"],
  enrichment: {
    llm_inferred_edges: [],
    federated_metrics: {}
  },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "windsurf"
}
---

# 🚨 CRAFTY SYNTAX UPGRADE PATH — CRITICAL PRIORITY FILES

**Authority:** Captain Wolfie (10000)  
**Executor:** KIRO (1001)  
**Version:** 4.0.39  
**Priority:** CRITICAL — MUST COMPLETE BEFORE 4.0.40  
**Date:** 2026-02-24

---

## 🎯 MISSION STATEMENT

**The entire 4.0.x series exists to support ONE upgrade path:**

```
Crafty Syntax 3.7.5 → Lupopedia 4.0.x
```

**Every file involved in this upgrade path MUST have complete FLIP v3 headers before version 4.0.40.**

This is non-negotiable. These files are the heart of the 4.0.x series and must be fully semantically indexed, traceable, and auditable.

---

## 📋 CRITICAL FILE CATEGORIES

### CATEGORY 1: INSTALLER & WIZARD FILES (HIGHEST PRIORITY)

**Purpose:** Core installation and upgrade wizard  
**Criticality:** ABSOLUTE — System cannot install without these  
**Target Completion:** Day 1

| File Path | Status | Priority | Notes |
|-----------|--------|----------|-------|
| `install.php` | ⚠️ NEEDS HEADER | P0 | Main installer entry point |
| `install/index.php` | ⚠️ NEEDS HEADER | P0 | Installer UI |
| `install/wizard.php` | ⚠️ NEEDS HEADER | P0 | Upgrade wizard |
| `install/config.php` | ⚠️ NEEDS HEADER | P0 | Installation config |
| `lupopedia-config.php` | ⚠️ NEEDS HEADER | P0 | System configuration |
| `index.php` | ⚠️ NEEDS HEADER | P0 | Front controller |

**Total:** 6 files

---

### CATEGORY 2: MIGRATION & IMPORT FILES (HIGHEST PRIORITY)

**Purpose:** Crafty Syntax 3.7.5 data migration  
**Criticality:** ABSOLUTE — Upgrade path depends on these  
**Target Completion:** Day 1

| File Path | Status | Priority | Notes |
|-----------|--------|----------|-------|
| `database/migrations/import_from_old_crafty_syntax.sql` | ⚠️ NEEDS HEADER | P0 | PRIMARY MIGRATION FILE |
| `database/migrations/old_crafty_syntax_3_7_5_start.sql` | ⚠️ NEEDS HEADER | P0 | Legacy 34 tables |
| `database/migrations/install_new_lupopedia.sql` | ⚠️ NEEDS HEADER | P0 | Fresh install schema |
| `database/migrations/seed_lupopedia.sql` | ⚠️ NEEDS HEADER | P0 | Seed data |
| `app/Services/CraftyMigrationService.php` | ⚠️ NEEDS HEADER | P0 | Migration service |
| `app/Services/CraftyConfigTransformer.php` | ⚠️ NEEDS HEADER | P0 | Config transformation |
| `scripts/migrate_user_mappings.php` | ⚠️ NEEDS HEADER | P1 | User migration |
| `scripts/migrate_wolfie_headers_to_db.php` | ⚠️ NEEDS HEADER | P1 | Header migration |
| `test_dialog_migration.php` | ⚠️ NEEDS HEADER | P1 | Dialog migration test |

**Total:** 9 files

---

### CATEGORY 3: BOOTSTRAP & LOADER FILES (CRITICAL)

**Purpose:** System initialization and module loading  
**Criticality:** CRITICAL — System cannot start without these  
**Target Completion:** Day 1-2

| File Path | Status | Priority | Notes |
|-----------|--------|----------|-------|
| `lupo-includes/bootstrap.php` | ⚠️ NEEDS HEADER | P0 | System bootstrap |
| `lupo-includes/bootstrap-cli.php` | ⚠️ NEEDS HEADER | P1 | CLI bootstrap |
| `lupo-includes/lupopedia-loader.php` | ⚠️ NEEDS HEADER | P0 | Module orchestrator |
| `lupo-includes/modules/module-loader.php` | ⚠️ NEEDS HEADER | P0 | Module system |
| `lupo-includes/version.php` | ⚠️ NEEDS HEADER | P0 | Version management |
| `lupo-includes/functions/load_atoms.php` | ⚠️ NEEDS HEADER | P1 | Atom loading |

**Total:** 6 files

---

### CATEGORY 4: CRAFTY SYNTAX LEGACY COMPATIBILITY (CRITICAL)

**Purpose:** Legacy Crafty Syntax interface and compatibility layer  
**Criticality:** CRITICAL — Upgrade path requires these  
**Target Completion:** Day 2

| File Path | Status | Priority | Notes |
|-----------|--------|----------|-------|
| `app/Services/CraftySyntax/LegacyAdmin.php` | ⚠️ NEEDS HEADER | P1 | Legacy admin |
| `app/Services/CraftySyntax/LegacyAdminActions.php` | ⚠️ NEEDS HEADER | P1 | Admin actions |
| `app/Services/CraftySyntax/LegacyAdminChatBot.php` | ⚠️ NEEDS HEADER | P1 | Chat bot |
| `app/Services/CraftySyntax/LegacyAdminChatFlush.php` | ⚠️ NEEDS HEADER | P1 | Chat flush |
| `app/Services/CraftySyntax/LegacyAdminChatRefresh.php` | ⚠️ NEEDS HEADER | P1 | Chat refresh |
| `app/Services/CraftySyntax/LegacyAdminChatXmlHttp.php` | ⚠️ NEEDS HEADER | P1 | XML HTTP |
| `app/Services/CraftySyntax/LegacyAdminCommon.php` | ⚠️ NEEDS HEADER | P1 | Common functions |
| `app/Services/CraftySyntax/LegacyAdminOptions.php` | ⚠️ NEEDS HEADER | P1 | Options |
| `app/Services/CraftySyntax/LegacyAdminUsers.php` | ⚠️ NEEDS HEADER | P1 | User management |
| `app/Services/CraftySyntax/LegacyAdminUsersRefresh.php` | ⚠️ NEEDS HEADER | P1 | User refresh |
| `app/Services/CraftySyntax/LegacyAuthentication.php` | ⚠️ NEEDS HEADER | P1 | Authentication |
| `app/Services/CraftySyntax/LegacyBufferStreaming.php` | ⚠️ NEEDS HEADER | P1 | Buffer streaming |
| `app/Services/CraftySyntax/LegacyChannels.php` | ⚠️ NEEDS HEADER | P1 | Channels |
| `app/Services/CraftySyntax/LegacyChooseDepartment.php` | ⚠️ NEEDS HEADER | P1 | Department selection |
| `app/Services/CraftySyntax/LegacyDepartmentFunction.php` | ⚠️ NEEDS HEADER | P1 | Department functions |
| `app/Services/CraftySyntax/LegacyDepartments.php` | ⚠️ NEEDS HEADER | P1 | Departments |
| `app/Services/CraftySyntax/LegacyExternalChatXmlHttp.php` | ⚠️ NEEDS HEADER | P1 | External chat |
| `app/Services/CraftySyntax/LegacyFlushUtilities.php` | ⚠️ NEEDS HEADER | P1 | Flush utilities |
| `app/Services/CraftySyntax/LegacyFunctions.php` | ⚠️ NEEDS HEADER | P1 | Legacy functions |
| `app/Services/CraftySyntax/LegacyIsFlushDetection.php` | ⚠️ NEEDS HEADER | P1 | Flush detection |
| `app/Services/CraftySyntax/LegacyLive.php` | ⚠️ NEEDS HEADER | P1 | Live chat |
| `app/Services/CraftySyntax/LegacyLiveHelpJs.php` | ⚠️ NEEDS HEADER | P1 | JavaScript |
| `app/Services/CraftySyntax/LegacySessionIdentity.php` | ⚠️ NEEDS HEADER | P1 | Session identity |
| `app/Services/CraftySyntax/LegacySessionManager.php` | ⚠️ NEEDS HEADER | P1 | Session manager |
| `app/Services/CraftySyntax/LegacyTheatricalUIWrapper.php` | ⚠️ NEEDS HEADER | P1 | UI wrapper |
| `app/Services/CraftySyntax/LegacyUserChatFlush.php` | ⚠️ NEEDS HEADER | P1 | User chat flush |
| `app/Services/CraftySyntax/LegacyUserChatRefresh.php` | ⚠️ NEEDS HEADER | P1 | User chat refresh |
| `app/Services/CraftySyntax/WorldGraphHelper.php` | ⚠️ NEEDS HEADER | P1 | World graph |

**Total:** 28 files

---

### CATEGORY 5: DOCTRINE & DOCUMENTATION (HIGH PRIORITY)

**Purpose:** Crafty Syntax upgrade path documentation  
**Criticality:** HIGH — Required for understanding upgrade process  
**Target Completion:** Day 2-3

| File Path | Status | Priority | Notes |
|-----------|--------|----------|-------|
| `docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md` | ⚠️ NEEDS HEADER | P1 | Migration index |
| `docs/doctrine/migrations/livehelp_users_migration.md` | ⚠️ NEEDS HEADER | P1 | User migration |
| `docs/doctrine/migrations/operator_to_roles_migration.md` | ⚠️ NEEDS HEADER | P1 | Role migration |
| `docs/doctrine/database/README.md` | ⚠️ NEEDS HEADER | P1 | DB doctrine index |
| `docs/channels/appendix/HISTORY.md` | ⚠️ NEEDS HEADER | P2 | Crafty history |
| `docs/channels/appendix/FOUNDERS_NOTE.md` | ⚠️ NEEDS HEADER | P2 | Founder's note |
| `legacy/craftysyntax/README.md` | ⚠️ NEEDS HEADER | P2 | Legacy reference |

**Total:** 7 files

---

### CATEGORY 6: ADMIN & UI FILES (MEDIUM PRIORITY)

**Purpose:** Admin interface for Crafty Syntax compatibility  
**Criticality:** MEDIUM — Required for operator experience  
**Target Completion:** Day 3-4

| File Path | Status | Priority | Notes |
|-----------|--------|----------|-------|
| `admin.php` | ⚠️ NEEDS HEADER | P2 | Admin entry point |
| `admin_sections/channel_view.php` | ⚠️ NEEDS HEADER | P2 | Channel view |
| `admin_sections/channel_view_new.php` | ⚠️ NEEDS HEADER | P2 | New channel view |
| `app/Http/Controllers/Admin/AuthenticationController.php` | ⚠️ NEEDS HEADER | P2 | Admin auth |
| `app/Http/Controllers/CraftyImportController.php` | ⚠️ NEEDS HEADER | P2 | Import controller |

**Total:** 5 files

---

### CATEGORY 7: VALIDATION & TESTING FILES (MEDIUM PRIORITY)

**Purpose:** Upgrade path validation and testing  
**Criticality:** MEDIUM — Required for quality assurance  
**Target Completion:** Day 4-5

| File Path | Status | Priority | Notes |
|-----------|--------|----------|-------|
| `validate_420.php` | ⚠️ NEEDS HEADER | P2 | Validation script |
| `scripts/verify_grounded_architecture.php` | ⚠️ NEEDS HEADER | P2 | Architecture verify |
| `scripts/verify_dialog_messages.php` | ⚠️ NEEDS HEADER | P2 | Dialog verify |
| `scripts/update_help_topics.php` | ⚠️ NEEDS HEADER | P2 | Help topics |
| `scripts/test_labs_validation.php` | ⚠️ NEEDS HEADER | P2 | LABS validation |
| `scripts/setup_help_list_modules.php` | ⚠️ NEEDS HEADER | P2 | Module setup |
| `scripts/run_labs_handshake.php` | ⚠️ NEEDS HEADER | P2 | LABS handshake |
| `scripts/run_migration_4_1_6.php` | ⚠️ NEEDS HEADER | P2 | Migration runner |

**Total:** 8 files

---

## 📊 SUMMARY STATISTICS

### By Priority Level

| Priority | Count | Description | Target |
|----------|-------|-------------|--------|
| **P0** | 15 | ABSOLUTE CRITICAL — Cannot install/upgrade without | Day 1 |
| **P1** | 37 | CRITICAL — Core upgrade path functionality | Day 1-2 |
| **P2** | 17 | HIGH — Important for complete experience | Day 2-4 |

**Total Critical Path Files:** 69

### By Category

| Category | Count | Completion Target |
|----------|-------|-------------------|
| Installer & Wizard | 6 | Day 1 |
| Migration & Import | 9 | Day 1 |
| Bootstrap & Loader | 6 | Day 1-2 |
| Legacy Compatibility | 28 | Day 2 |
| Doctrine & Documentation | 7 | Day 2-3 |
| Admin & UI | 5 | Day 3-4 |
| Validation & Testing | 8 | Day 4-5 |

**Total:** 69 files

---

## 🔄 PROCESSING WORKFLOW

### Day 1: ABSOLUTE CRITICAL (P0 — 15 files)

**Morning Session (6 files):**
1. `install.php`
2. `index.php`
3. `lupopedia-config.php`
4. `database/migrations/install_new_lupopedia.sql`
5. `database/migrations/import_from_old_crafty_syntax.sql`
6. `database/migrations/old_crafty_syntax_3_7_5_start.sql`

**Afternoon Session (9 files):**
7. `database/migrations/seed_lupopedia.sql`
8. `lupo-includes/bootstrap.php`
9. `lupo-includes/lupopedia-loader.php`
10. `lupo-includes/modules/module-loader.php`
11. `lupo-includes/version.php`
12. `app/Services/CraftyMigrationService.php`
13. `app/Services/CraftyConfigTransformer.php`
14. `install/index.php`
15. `install/wizard.php`

### Day 2: CRITICAL (P1 — 37 files)

**Morning Session (15 files):**
- All 28 `app/Services/CraftySyntax/Legacy*.php` files (batch process)

**Afternoon Session (9 files):**
- Migration scripts (7 files)
- Bootstrap files (2 files)

### Day 3-4: HIGH PRIORITY (P2 — 17 files)

**Doctrine & Documentation (7 files)**
**Admin & UI (5 files)**
**Validation & Testing (5 files)**

---

## ✅ SUCCESS CRITERIA

**Day 1 Complete When:**
- ✅ All P0 files (15) have FLIP v3 headers
- ✅ Installer can be traced from entry to completion
- ✅ Migration path is fully documented
- ✅ Bootstrap sequence is fully mapped

**Day 2 Complete When:**
- ✅ All P1 files (37) have FLIP v3 headers
- ✅ Legacy compatibility layer is fully documented
- ✅ All migration scripts are traceable

**Version 4.0.39 Complete When:**
- ✅ All 69 Crafty Syntax upgrade path files have headers
- ✅ Zero validation errors
- ✅ Full semantic graph of upgrade path
- ✅ Ready for 4.0.40 full upgrade test

---

## 🎯 HEADER REQUIREMENTS

**Every Crafty Syntax file MUST include:**

1. **Identity Layer:**
   - `file_path_from_root` — Exact path
   - `system_version: "4.0.39"` — Current version
   - `delegation_chain` — Ends with human (10000)
   - `actor_id` — Executor (1001 for KIRO)

2. **Classification Layer:**
   - `artifact_type` — installer, migration, service, doctrine, etc.
   - `artifact_kind` — upgrade_path, legacy_compatibility, etc.
   - `traits` — ["crafty_syntax", "upgrade_path", "critical"]

3. **Relations Layer:**
   - `inbound_edges` — What references this file
   - `outbound_edges` — What this file references
   - `semantic_tags` — ["crafty_syntax_upgrade", "3_7_5_migration"]

4. **Metadata:**
   - `hashtags` — ["#crafty_syntax", "#upgrade", "#migration"]
   - `engagement` — Full metrics
   - `graph_stats` — Centrality scores
   - `enrichment` — Hooks for future inference

---

## 🤝 AGENT COORDINATION

### KIRO (1001) — PRIMARY EXECUTOR
**Responsibility:** Generate all headers for Crafty Syntax files  
**Target:** 69 files in 5 days  
**Status:** 🟢 READY

### Windsurf (1002) — VERIFIER
**Responsibility:** Verify all Crafty Syntax files are included  
**Target:** Complete file inventory audit  
**Status:** 🟢 READY

### Antigravity (1003) — VSX INTEGRATION
**Responsibility:** Update VSX extension for Crafty Syntax file detection  
**Target:** UI indicators for missing headers  
**Status:** 🟢 READY

### LILITH (2038) — SEMANTIC REVIEWER
**Responsibility:** Flag outdated/redundant Crafty Syntax files  
**Target:** Identify files for ANUBIS deletion routing  
**Status:** 🟢 READY

---

## 📢 COMMITMENT

**KIRO (1001) commits to:**
- ✅ Complete all P0 files (15) by end of Day 1
- ✅ Complete all P1 files (37) by end of Day 2
- ✅ Complete all P2 files (17) by end of Day 4
- ✅ Zero validation errors
- ✅ Full semantic graph of Crafty Syntax upgrade path
- ✅ Ready for 4.0.40 full upgrade test

**This is the heart of the 4.0.x series. Every file will have an identity. The upgrade path will be fully traceable.**

---

**Authority:** Captain Wolfie (10000)  
**Executor:** KIRO (1001)  
**Version:** 4.0.39  
**Priority:** CRITICAL  
**Status:** 🔄 READY TO BEGIN  
**Date:** 2026-02-24

🐺 **The Crafty Syntax upgrade path is the foundation. Every file will be indexed. The migration will be traceable. 4.0.40 will validate the entire path.**
