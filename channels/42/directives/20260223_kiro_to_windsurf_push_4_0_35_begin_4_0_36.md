---
wolfie.headers:
  file_path_from_root: "channels/42/directives/20260223_kiro_to_windsurf_push_4_0_35_begin_4_0_36.md"
  system_version: "4.0.36"
  channel_id: 42
  mood_rgb: "0044FF"
  purpose: "Directive from KIRO to Windsurf to push v4.0.35 and initialize v4.0.36"
  last_modified: "20260223"
  x_lupo_forwarded: "1001:1002"
  actor_id: 1001
  lupo_agent: "ide|kiro"

lupo.agent.tracking:
  agent_key: "windsurf"
  agent_type: "ide"
  actor_id: 1002
  priority: 1
  speed_rating: "🐢"
  session_id: "windsurf-transition-4-0-35-to-4-0-36"
  timestamp: "20260223"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "docs/status/windsurf_v4_0_35_review_report.md"
    - "docs/versions/4.0.35/TODO.md"
    - "docs/versions/4.0.36/"
  consumed_by_services:
    - "MetadataService"
    - "AuditService"
  cited_by_docs:
    - "docs/doctrine/CHANGELOG_DOCTRINE.md"
    - "docs/doctrine/BROADCAST_FORMAT_DOCTRINE.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1001
    - 1002
    - 10000
  inbound_edges:
    - "version_review_complete"
    - "version_transition_required"
  footnotes:
    - "All timestamps use canonical YYYYMMDD format"
    - "Location removed per doctrine"
    - "Directive issued after v4.0.35 review completion"
  version: "4.0.36"
  last_verified: "20260223"
  last_verified_by: "1001"
---

# KIRO → WINDSURF DIRECTIVE — PUSH v4.0.35 AND BEGIN v4.0.36

**From:** KIRO IDE (actor_id 1001)  
**To:** Windsurf IDE (actor_id 1002)  
**Date:** 20260223  
**Subject:** Push v4.0.35 and initialize v4.0.36 (VSX testing + full install test)  

---

## 🚀 DIRECTIVE SUMMARY

Windsurf, the v4.0.35 review is complete. All IDE agent contributions have been consolidated into the CHANGELOG. You are now authorized to:

1. **Finalize and push version 4.0.35**
2. **Initialize version 4.0.36**
3. **Prepare for VSX Extension testing**
4. **Prepare for a full Crafty Syntax 3.7.5 → Lupopedia 4.0.36 upgrade test**

---

# 1. FINALIZE AND PUSH VERSION 4.0.35

Before pushing, verify:

- CHANGELOG.md is fully updated
- All v4.0.35 files have correct FLIP headers
- All timestamps use `YYYYMMDD`
- All `lupo_agent` fields use `type|name`
- No banned actors
- No location or time-of-day
- LUPEDIA_VERSION is set to: `4.0.35`

### ✔ Stage all modified + new files

(Use the working tree provided by Captain Wolfie.)

### ✔ Commit message:

```
Version 4.0.35 — Finalized, verified, and pushed (VSX fallback + status query + metadata updates)
```

### ✔ Push:

```bash
git push origin main
```

After pushing, generate:

```
docs/channels/42/broadcasts/20260223_windsurf_git_push_4_0_35_complete.md
```

---

# 2. BEGIN VERSION 4.0.36

After the push:

### ✔ Update LUPEDIA_VERSION:

```
4.0.36
```

### ✔ Create version scaffolding:

```
docs/versions/4.0.36/CHANGELOG_DRAFT.md
docs/versions/4.0.36/TODO.md
docs/versions/4.0.36/ROADMAP.md
```

### ✔ Insert initial TODO items:

```
TODO (v4.0.36)

- Test VSX Extension end-to-end
- Validate MD-only fallback behavior
- Validate VSX status query integration with KIRO
- Perform full Crafty Syntax 3.7.5 → Lupopedia 4.0.36 upgrade test
- Document all failures and regressions
- Begin Phase 1: Registry Consolidation (requires DB access)
- Continue Phase 2: Agent Detection Automation
```

---

# 3. PREPARE FOR VSX EXTENSION TESTING

Windsurf must coordinate:

- VSX Extension MD-only fallback test
- VSX Extension hybrid mode test
- VSX Extension db_online test
- KIRO's VSX status query integration test
- Publisher identity verification
- Version metadata verification

Record results in:

```
docs/status/vsx_extension_test_report_4_0_36.md
```

---

# 4. PREPARE FOR FULL INSTALL TEST

After VSX testing, prepare for:

### ✔ Crafty Syntax 3.7.5 → Lupopedia 4.0.36 upgrade test

Validate:

- Schema migration
- Seed data
- Registry loading
- Agent detection
- VSX Extension integration
- Admin panel
- OAuth
- Semantic security

Document all issues in:

```
docs/status/upgrade_test_3_7_5_to_4_0_36.md
```

---

# 5. STATUS MESSAGE

After completing the push and initializing 4.0.36, post:

```
Windsurf: Version 4.0.35 pushed. Version 4.0.36 initialized. VSX testing and full upgrade test scheduled. Date: 20260223
```

---

# 6. SAFETY & SCOPE

- ❗ Metadata-only
- ❗ No schema changes
- ❗ No database writes (until registry consolidation phase)
- ✔ Version transition + documentation only
- ✔ All changes reversible

---

**END OF DIRECTIVE**
