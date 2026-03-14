# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\42\broadcasts\20260225130017_10000_1000_42_development_channel_workspace_migration_enforcement.md"
  file_hash: "24d2b13f93bc39881917d148b676fb536cd1fa7aad0117667caacffd65a4850b"
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
  file_path_from_root: "lupo-channels\42\broadcasts\20260225130017_10000_1000_42_development_channel_workspace_migration_enforcement.md"
  file_hash: "024907fdc4396aa11946bb10857f8400899531e85662aad545e803585baf2477"
  file_path_from_root: "lupo-channels\42\broadcasts\20260225130017_10000_1000_42_development_channel_workspace_migration_enforcement.md"
  file_hash: "d59e0a167d0aa8638fd7394b3006d22fa45fe63410c3e38495939c93e564e515"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225130017_10000_1000_42_development_channel_workspace_migration_enforcement.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "broadcasts", "20260225130017_10000_1000_42_development_channel_workspace_migration_enforcementmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
from_actor_id: 10000
to_actor_id: 1000
channel_id: 42
delegation_chain: "10000:1000"
system_version: "4.0.45"
actor_id: 10000
purpose: """Development Channel Workspace Migration Enforcement"""
message_type: "directive"
visibility: "public"
priority: "critical"
created_ymdhis: 20260225130000
created_utc: "2026-02-25T13:00:00Z"
---
# CHANNEL 42: CHANNEL-SCOPED ACTOR WORKSPACES NOW MANDATORY

**Classification:** Development Workflow Directive  
**Effective:** Version 4.0.45+  
**Authority:** Captain Wolfie (10000)  
**Channel:** 42 (Development)

---

## 🔄 WORKSPACE MIGRATION COMPLETE

**Deprecated:** `/prompts` directory (root-level shared prompt space)  
**New Architecture:** `/channels/{channel_id}/actors/{actor_id}/`

**Migration Status:** ✅ COMPLETE (2026-02-25)

---

## 📋 DEVELOPMENT REQUIREMENTS

**All IDE agents working on Channel 42 must operate inside channel-scoped actor directories:**

### Workspace Structure

```
lupo-channels/42/actors/
├── 1000/       # KIRO IDE workspace
├── 1001/       # Windsurf IDE workspace
├── 1002/       # Cursor IDE workspace
├── 1003/       # Antigravity IDE workspace
├── 1004/       # Warp IDE workspace
├── 1005/       # Cascade IDE workspace
├── 2/          # LILITH workspace (multi-agent collaboration)
└── 10000/      # Captain workspace (human operator)
```

### Workspace Contents

Each actor workspace contains:
- **Temporary prompts** — Task-specific instructions
- **Scratch files** — Work-in-progress artifacts
- **Working notes** — Development logs and observations
- **Task state** — Current task tracking
- **Partial outputs** — Intermediate build artifacts
- **Draft doctrine** — Proposed standards and rules
- **Debug artifacts** — Diagnostic outputs and traces

### What Does NOT Belong in Workspaces

- **Permanent artifacts** → Use `lupo-docs/`, `lupo-database/`, `config/`
- **Doctrine files** → Use `lupo-docs/doctrine/`
- **System documentation** → Use `lupo-docs/`
- **Shared resources** → Use appropriate project directories

---

## 🎯 DEVELOPMENT WORKFLOW CHANGES

### Build & Test Integration

**Before (DEPRECATED):**
```bash
# Scripts read from /prompts/
./scripts/build.sh --prompts /prompts/kiro/
```

**After (REQUIRED):**
```bash
# Scripts read from channel-scoped workspaces
./scripts/build.sh --workspace lupo-channels/42/actors/1000/
```

### IDE Extension Configuration

**Before (DEPRECATED):**
```json
{
  "lupopedia.workspaceRoot": "/prompts/kiro"
}
```

**After (REQUIRED):**
```json
{
  "lupopedia.workspaceRoot": "lupo-channels/42/actors/1000",
  "lupopedia.channelId": 42,
  "lupopedia.actorId": 1000
}
```

### Multi-Agent Collaboration

**Isolation Enforced:**
- Each agent has its own workspace
- No shared mutable state
- Channel-scoped context
- Actor-specific history

**Cross-Agent Communication:**
- Use broadcasts (`lupo-channels/42/broadcasts/`)
- Use threads (`lupo-channels/42/threads/`)
- Use directives (`lupo-channels/42/directives/`)
- Never write to another agent's workspace

---

## ⚠️ ENFORCEMENT

**Effective immediately for version 4.0.45+:**

1. **All development work** must use channel-scoped directories
2. **Legacy `/prompts` access** is blocked (read-only for historical reference)
3. **Migration required** before agent activation
4. **Registry alignment** verified at runtime
5. **Workspace violations** logged and rejected

### Compliance Checks

**Pre-Commit Hooks:**
- Verify no writes to `/prompts/`
- Verify workspace paths match actor ID
- Verify channel ID matches file location

**Runtime Validation:**
- Actor ID must exist in registry
- Workspace directory must exist
- Channel ID must be valid
- Actor must have channel access

---

## 🔗 DEPENDENCIES

**This migration depends on:**
- ✅ Registry seeding completion (4.0.45)
- ✅ Channel infrastructure established
- ✅ Actor workspace provisioning
- ✅ Multi-agent isolation enforcement
- ✅ Antigravity IDE integration (actor 1003)
- ✅ Cascade IDE integration (actor 1005)

---

## 📊 MIGRATION STATISTICS

**Files Migrated:**
- Channel 42 workspaces: 6 actors
- Total files: 13 files
- Orphan files resolved: 5 files
- Data loss: 0 files

**Actors with Workspaces:**
- 1000 (KIRO IDE)
- 1001 (Windsurf IDE)
- 1002 (Cursor IDE)
- 1003 (Antigravity IDE)
- 2 (LILITH)
- 10000 (Captain)

---

## 🚀 NEXT STEPS FOR DEVELOPERS

### 1. Update IDE Configuration

Update your IDE extension settings to use the new workspace path:
```
lupo-channels/42/actors/{your_actor_id}/
```

### 2. Verify Workspace Access

Check that your workspace directory exists and is writable:
```bash
ls -la lupo-channels/42/actors/{your_actor_id}/
```

### 3. Migrate Active Work

Move any active work from `/prompts/` to your workspace:
```bash
cp /prompts/{old_location}/* lupo-channels/42/actors/{your_actor_id}/
```

### 4. Update Build Scripts

Update any build or test scripts to reference the new workspace paths.

### 5. Test Integration

Run a test build to verify workspace integration:
```bash
./scripts/test_workspace_integration.sh
```

---

## 📚 DOCUMENTATION

**Full Documentation:**
- Architecture: `lupo-docs/architecture/channel_scoped_actor_workspaces.md`
- Migration Guide: `IMPLEMENTATION_SUMMARY_4.0.45_ANTIGRAVITY_WORKSPACES.md`
- Workspace Doctrine: `lupo-docs/doctrine/workspace_isolation.md`
- API Reference: `lupo-docs/api/workspace_api.md`

**Related Broadcasts:**
- System-wide announcement: `lupo-channels/0/broadcasts/20260225120000_10000_1000_0_channel_scoped_actor_workspaces.md`
- Antigravity integration: `lupo-channels/42/broadcasts/20260225_1004_10000_42_antigravity_workspaces_complete.md`

---

## ⚡ ACTION REQUIRED

**All IDE agents on Channel 42:**
1. Update workspace paths immediately
2. Verify registry alignment
3. Test workspace access
4. Migrate active work
5. Update build configurations

**Compliance:** Mandatory for 4.0.45 deployment  
**Deadline:** Before install.php integration  
**Support:** Contact Captain (10000) or KIRO (1000) for assistance

---

**Issued by:** Captain Wolfie (Actor 10000)  
**Effective:** 2026-02-25 14:00:00 UTC  
**System Version:** 4.0.45  
**Channel:** 42 (Development)  
**Priority:** CRITICAL



<!-- FLIP_FOOTER_BEGIN
{
    "references": "\"lupo-docs\/status\/broadcast_collection_42.md\"",
    "implements": "\"broadcast_standardization\"",
    "depends_on": "\"registry_seeding_completion\"",
    "includes": "\"channel_42_communications\"",
    "version": "\"4.0.45\"",
    "last_verified": "\"20260225\"",
    "last_verified_by": "\"windsurf\""
}
FLIP_FOOTER_END -->
