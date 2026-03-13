# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\versions\4.0.39\PRIORITY_FILES.md"
  file_hash: "9148da769300da356d4454703daed6c69348f66bce548d035fb24df3818bd3d0"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
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

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\versions\4.0.39\PRIORITY_FILES.md"
  file_hash: "2619758c2464dda3b30f19d41b513db049a00756676444d5f1d858a6e8912c50"
  file_path_from_root: "docs\versions\4.0.39\PRIORITY_FILES.md"
  file_hash: "3a21d989cf96a6768e7138710ac22b4a59c671f309d8365a652d488515c8b8cd"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for PRIORITY_FILES.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "versions", "4039", "priority_filesmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers: {
  file_path_from_root: "docs/versions/4.0.39/PRIORITY_FILES.md",
  system_version: "4.0.39",
  channel_id: 42,
  mood_rgb: "FFD700",
  purpose: "Priority file list for ANUBIS header generation and batch migration in version 4.0.39",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "roadmap",
  artifact_kind: "priority_list",
  traits: ["critical", "v4.0.39", "anubis", "header_migration"],
  hashtags: ["#priority", "#headers", "#anubis", "#v4.0.39", "#migration"],
  engagement: {
    likes: 0,
    shares: 0,
    views: 0,
    last_interaction_utc: "20260224"
  },
  graph_stats: {
    inbound_count: 3,
    outbound_count: 2,
    centrality_score: 0.80
  }
}

flip.footer: {
  inbound_edges: [
    { from: "docs/versions/4.0.39/TODO.md", type: "references", weight: 1.0, hashtag: "#roadmap" },
    { from: "docs/doctrine/ANUBIS_FALLBACK_DOCTRINE.md", type: "implements", weight: 1.0, hashtag: "#doctrine" },
    { from: "lupo-includes/classes/AnubisHeaderFallback.php", type: "processes", weight: 0.9, hashtag: "#automation" }
  ],
  outbound_edges: [
    { to: "docs/status/kiro_header_completion_4_0_39.md", type: "reports_to", weight: 0.9, hashtag: "#status" },
    { to: "CHANGELOG.md", type: "documented_in", weight: 0.8, hashtag: "#versions" }
  ],
  referenced_by_actors: [1001, 19, 10000],
  references: {
    by_files: ["docs/versions/4.0.39/TODO.md", "docs/doctrine/ANUBIS_FALLBACK_DOCTRINE.md"],
    by_actors: [1001, 19, 10000]
  },
  semantic_tags: ["priority_files", "header_migration", "anubis_targets", "batch_processing"],
  enrichment: {
    llm_inferred_edges: [],
    federated_metrics: {}
  },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}
---

# 📋 PRIORITY FILES FOR VERSION 4.0.39 HEADER COMPLETION

**Purpose:** Targeted file list for ANUBIS header generation and batch migration  
**Version:** 4.0.39  
**Created By:** KIRO (1001)  
**Authority:** Captain Wolfie (10000)  
**Date:** 2026-02-24

---

## 🎯 SELECTION CRITERIA

Files are prioritized based on:
1. **Centrality Score** — High inbound/outbound edge count
2. **Critical Path** — Core doctrine, system services, coordination channels
3. **Frequency of Access** — Files referenced by multiple agents
4. **Missing Headers** — Files currently lacking FLIP v3 headers
5. **Version Alignment** — Files needing system_version update to 4.0.39

---

## 📊 PRIORITY BATCHES

### BATCH ALPHA: CRAFTY SYNTAX UPGRADE PATH (Top Priority)
**Priority:** EMERGENCY  
**Target Completion:** Immediate  
**Estimated Time:** 4-5 hours

| File Path | Current Status | Centrality | Notes |
|-----------|---------------|------------|-------|
| `lupo-includes/modules/crafty_syntax/crafty_syntax-controller.php` | ⚠️ Needs header | 0.90 | Migration controller |
| `lupo-includes/modules/crafty_syntax/visitor-session-helper.php` | ⚠️ Needs header | 0.85 | Session migration |
| `app/Services/CraftyMigrationService.php` | ⚠️ Needs header | 0.88 | Core migration logic |
| `app/Services/CraftyConfigTransformer.php` | ⚠️ Needs header | 0.82 | Config transformation |
| `app/Services/CraftySyntax/LegacyTheatricalUIWrapper.php` | ⚠️ Needs header | 0.80 | UI compatibility |
| `docs/channels/doctrine/legacy-import/CRAFTY_SYNTAX_UI_THEATRICAL_DOCTRINE.md` | ⚠️ Needs header | 0.85 | UI doctrine |
| `plan_for_crafty_syntax.md` | ⚠️ Needs header | 0.92 | Migration roadmap |
| `install.php` | ⚠️ Needs header | 0.98 | Main installer |
| `install_wizard_classes.php` | ⚠️ Needs header | 0.95 | Wizard logic |
| `lupo-includes/bootstrap.php` | ⚠️ Needs header | 0.92 | System entry |
| `lupo-includes/lupopedia-setup.php` | ⚠️ Needs header | 0.90 | Post-install setup |
| `database/migrations/install_new_lupopedia.sql` | ⚠️ Needs header | 0.95 | Creation script |
| `scripts/migrate_user_mappings.php` | ⚠️ Needs header | 0.85 | Data migration |
| `lupo-includes/modules/crafty_syntax/CRAFTY_SYNTAX_SQL_TOON_REPORT.md` | ⚠️ Needs header | 0.75 | Conversion data |
| `scripts/generate_install_sql.py` | ⚠️ Needs header | 0.80 | Scripted migration |

### BATCH 1: CORE DOCTRINE (10 files remaining)
**Priority:** HIGHEST  
**Target Completion:** Week 1

| File Path | Current Status | Centrality | Notes |
|-----------|---------------|------------|-------|
| `docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md` | ⚠️ Needs update | 0.95 | Master doctrine |
| `docs/doctrine/FLIP/FLIP_DOCTRINE.md` | ⚠️ Needs update | 0.92 | FLIP v3 spec |
| `docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md` | ⚠️ Needs update | 0.88 | FLIP integration |
| `docs/doctrine/TIMESTAMP_DOCTRINE.md` | ⚠️ Needs update | 0.85 | Time handling |
| `docs/doctrine/MIGRATION_DOCTRINE.md` | ⚠️ Needs update | 0.82 | Schema changes |
| `docs/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md` | ⚠️ Needs update | 0.80 | Path handling |
| `docs/doctrine/PTSD_ADVERTISING_DOCTRINE.md` | ⚠️ Needs update | 0.75 | Boundary protection |
| `docs/doctrine/database/README.md` | ⚠️ Needs update | 0.78 | DB doctrine index |
| `docs/doctrine/database/actors.md` | ⚠️ Needs update | 0.85 | Actor model |
| `docs/doctrine/database/auth_users.md` | ⚠️ Needs update | 0.82 | Authentication |
| `docs/registry/REGISTERED_IDS.md` | ✅ Complete | 0.95 | Master ID Registry |

### BATCH 2: CORE SERVICES (30 files) — HIGH
**Priority:** HIGH  
**Target Completion:** Week 1-2  
**Estimated Time:** 4-5 hours

| File Path | Current Status | Centrality | Notes |
|-----------|---------------|------------|-------|
| `app/auth/AuthService.php` | ⚠️ Needs header | 0.88 | Core auth |
| `app/auth/AuthManager.php` | ⚠️ Needs header | 0.85 | Auth coordination |
| `app/auth/AuthRoleResolver.php` | ⚠️ Needs header | 0.82 | Role resolution |
| `app/auth/Session.php` | ⚠️ Needs header | 0.90 | Session handling |
| `app/auth/UnifiedSessionHandler.php` | ⚠️ Needs header | 0.85 | Session unification |
| `app/Services/ActorService.php` | ⚠️ Needs header | 0.92 | Actor management |
| `app/Services/CollectionZeroService.php` | ⚠️ Needs header | 0.78 | Collection 0 |
| `app/Services/CollectionTabsService.php` | ⚠️ Needs header | 0.75 | Tab management |
| `app/Services/EdgeService.php` | ⚠️ Needs header | 0.80 | Semantic edges |
| `app/Services/FlipHeaderValidatorService.php` | ⚠️ Needs header | 0.85 | Header validation |
| `app/Services/IDEAgentRegistryService.php` | ⚠️ Needs header | 0.82 | IDE registry |
| `app/Services/AnubisUnknownRecipientService.php` | ⚠️ Needs header | 0.75 | ANUBIS routing |
| `lupo-includes/classes/AnubisHeaderFallback.php` | ✅ Complete | 0.85 | ANUBIS engine |
| `lupo-includes/class-pdo_db.php` | ⚠️ Needs header | 0.95 | DB wrapper |
| `lupo-includes/class-DatabaseFactory.php` | ⚠️ Needs header | 0.92 | DB factory |
| `lupo-includes/classes/ColorProtocol.php` | ⚠️ Needs header | 0.65 | Color handling |
| `lupo-includes/classes/UrlResolver.php` | ⚠️ Needs header | 0.70 | URL resolution |
| `lupo-includes/classes/TOONParser.php` | ⚠️ Needs header | 0.88 | TOON parsing |
| `lupo-includes/classes/DialogHistoryManager.php` | ⚠️ Needs header | 0.80 | Dialog history |
| `lupo-includes/bootstrap.php` | ⚠️ Needs header | 0.90 | System bootstrap |
| `lupo-includes/lupopedia-loader.php` | ⚠️ Needs header | 0.88 | Module loader |
| `lupo-includes/modules/module-loader.php` | ⚠️ Needs header | 0.85 | Module system |
| `lupo-includes/version.php` | ⚠️ Needs header | 0.92 | Version management |
| `app/EmotionalArchaeology.php` | ⚠️ Needs header | 0.60 | Emotional metadata |
| `app/NoteComparisonProtocol.php` | ⚠️ Needs header | 0.58 | Note comparison |
| `app/Services/CraftyMigrationService.php` | ⚠️ Needs header | 0.75 | Crafty migration |
| `app/Services/CraftyConfigTransformer.php` | ⚠️ Needs header | 0.72 | Config transform |
| `app/Services/Pack/PackBehaviorService.php` | ⚠️ Needs header | 0.68 | Pack behavior |
| `app/Services/Pack/PackMemoryService.php` | ⚠️ Needs header | 0.65 | Pack memory |
| `app/Services/Pack/PackSyncService.php` | ⚠️ Needs header | 0.70 | Pack sync |

### BATCH 3: CORE PROMPTS & DIRECTIVES (40 files) — MEDIUM
**Priority:** MEDIUM  
**Target Completion:** Week 2  
**Estimated Time:** 3-4 hours

| Directory | File Count | Status | Notes |
|-----------|------------|--------|-------|
| `prompts/kiro/` | ~15 | ⚠️ Needs headers | KIRO directives |
| `prompts/antigravity/` | ~10 | ⚠️ Needs headers | Antigravity tasks |
| `prompts/windsurf/` | ~8 | ⚠️ Needs headers | Windsurf tasks |
| `prompts/captain/` | ~7 | ⚠️ Needs headers | Captain directives |

### BATCH 4: CHANNEL LOGS & BROADCASTS (50 files) — MEDIUM
**Priority:** MEDIUM  
**Target Completion:** Week 2-3  
**Estimated Time:** 4-5 hours

| Directory | File Count | Status | Notes |
|-----------|------------|--------|-------|
| `channels/42/broadcasts/` | ~30 | ⚠️ Mixed | Dev coordination |
| `channels/42/threads/` | ~10 | ⚠️ Needs headers | Thread logs |
| `channels/666/` | ~5 | ⚠️ Needs headers | Security channel |
| `channels/420/` | ~5 | ⚠️ Needs headers | Legacy channel |

### BATCH 5: STATUS REPORTS & VERSIONS (60 files) — LOW
**Priority:** LOW  
**Target Completion:** Week 3  
**Estimated Time:** 5-6 hours

| Directory | File Count | Status | Notes |
|-----------|------------|--------|-------|
| `docs/status/` | ~40 | ⚠️ Mixed | Agent activity |
| `docs/versions/` | ~20 | ⚠️ Mixed | Version roadmaps |

---

## 📈 PROGRESS TRACKING

### Overall Statistics
- **Total Priority Files:** ~200
- **Files with Headers:** ~50 (25%)
- **Files Needing Headers:** ~150 (75%)
- **Estimated Total Time:** 18-23 hours
- **Target Completion:** 2-3 weeks

### Batch Completion Status

| Batch | Files | Status | Progress | ETA |
|-------|-------|--------|----------|-----|
| Batch 1 | 20 | 🔄 In Progress | 15% (3/20) | Week 1 |
| Batch 2 | 30 | ⏳ Pending | 3% (1/30) | Week 1-2 |
| Batch 3 | 40 | ⏳ Pending | 0% (0/40) | Week 2 |
| Batch 4 | 50 | ⏳ Pending | 0% (0/50) | Week 2-3 |
| Batch 5 | 60 | ⏳ Pending | 0% (0/60) | Week 3 |

---

## 🔄 BATCH PROCESSING WORKFLOW

### Phase 1: Detection
1. ANUBIS scans batch directory
2. Identifies files missing headers
3. Classifies files by path/content
4. Generates confidence scores

### Phase 2: Generation
1. ANUBIS generates default headers
2. Infers artifact_type and artifact_kind
3. Assigns traits and hashtags
4. Creates engagement metrics
5. Generates graph statistics

### Phase 3: Validation
1. KIRO validates generated headers
2. Checks delegation_chain format
3. Verifies actor_id ranges
4. Validates JSON5 syntax
5. Confirms required fields

### Phase 4: Insertion
1. ANUBIS creates backups
2. Inserts headers into files
3. Validates post-insertion
4. Logs actions to audit trail
5. Updates artifact index

### Phase 5: Review
1. Windsurf reviews batch
2. Checks semantic consistency
3. Verifies edge relationships
4. Flags issues for human review
5. Reports to Channel 42

---

## 🎯 SUCCESS CRITERIA

**Batch 1 Complete When:**
- ✅ All 20 doctrine files have FLIP v3 headers
- ✅ All headers validated with zero errors
- ✅ All system_version updated to 4.0.39
- ✅ All delegation_chain format correct
- ✅ All files indexed in artifact registry

**Batch 2 Complete When:**
- ✅ All 30 service files have FLIP v3 headers
- ✅ All PHP files use proper comment format
- ✅ All headers include engagement metrics
- ✅ All graph statistics calculated
- ✅ All semantic edges mapped

**Overall Success When:**
- ✅ All 200 priority files have headers
- ✅ Zero validation errors
- ✅ All batches reviewed and approved
- ✅ Artifact index fully updated
- ✅ Ready for 4.0.40 compliance gate

---

## 📊 ANUBIS CONFIDENCE THRESHOLDS

| Confidence Score | Action | Review Required |
|------------------|--------|-----------------|
| 0.90 - 1.00 | Auto-approve | No |
| 0.75 - 0.89 | Auto-approve with logging | No |
| 0.60 - 0.74 | Generate + flag for review | Yes (LILITH) |
| 0.40 - 0.59 | Generate + require human approval | Yes (Captain) |
| 0.00 - 0.39 | Route to review queue | Yes (Captain) |

---

## 🤝 AGENT COORDINATION

**KIRO (1001):**
- Primary executor for batch processing
- Validates all generated headers
- Coordinates with ANUBIS for automation
- Reports progress to Channel 42

**ANUBIS (19):**
- Automated header detection
- Default header generation
- File classification
- Audit trail logging

**Windsurf (1002):**
- Batch review and verification
- Semantic consistency checks
- Documentation updates
- Background task coordination

**Antigravity (1003):**
- VSX extension integration
- UI updates for header display
- Extension testing
- Fallback mode validation

**LILITH (2038):**
- Semantic review for flagged files
- Edge relationship validation
- Heterodox review
- Quality assurance

**Captain Wolfie (10000):**
- Final approval for low-confidence files
- Strategic direction
- Conflict resolution
- Authority override

---

**Authority:** Captain Wolfie (10000)  
**Created By:** KIRO (1001)  
**Version:** 4.0.39  
**Status:** 🔄 ACTIVE  
**Last Updated:** 2026-02-24

🐺 **The priority list is set. ANUBIS begins the sweep. Every file will have an identity.**
