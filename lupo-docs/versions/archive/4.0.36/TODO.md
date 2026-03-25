# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.36/TODO.md"
  file_hash: "3b3fce15f368f20af0f62b0862f565592bc3f18507923e01c012672649d4bac0"
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

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\versions\4.0.36\TODO.md"
  file_hash: "b19db5260b8337c3889f4b4e7a2aec2855800c6ba809ed323080f81996bacaf2"
  file_path_from_root: "lupo-docs\versions\4.0.36\TODO.md"
  file_hash: "114ed81ae086b1d207ccf9799983540b4dad834d8f92748311a9608a09bac2ec"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for TODO.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "versions", "4036", "todomd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers:
  file_path_from_root: "lupo-docs/versions/4.0.36/TODO.md"
  system_version: "4.0.36"
  channel_id: 42
  mood_rgb: "FF8800"
  purpose: "TODO list for version 4.0.36 development cycle"
  last_modified: "20260223"
  x_lupo_forwarded: "1001:10000"
  actor_id: 1001
  lupo_agent: "ide|kiro"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "lupo-docs/versions/4.0.36/ROADMAP.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1001
    - 10000
  inbound_edges:
    - "version_4_0_36"
    - "todo_tracking"
  footnotes:
    - "TODO items for 4.0.36 development cycle"
    - "Focus on VSX testing and full upgrade validation"
  version: "4.0.36"
  last_verified: "20260223"
  last_verified_by: "kiro"
---

# TODO — VERSION 4.0.36

**Version:** 4.0.36  
**Status:** In Progress  
**Started:** 20260223  
**Lead:** TBD  

---

## VSX EXTENSION TESTING

### End-to-End Testing

- [ ] Test VSX Extension end-to-end
- [ ] Validate MD-only fallback behavior
- [ ] Validate hybrid mode behavior
- [ ] Validate db_online mode behavior
- [ ] Test mode switching (db_online → hybrid → md_only)

### KIRO Integration Testing

- [ ] Validate VSX status query integration with KIRO
- [ ] Test status file reading
- [ ] Test validation logic
- [ ] Test agent coordination decisions
- [ ] Test fallback hierarchy

### Publisher Identity Verification

- [ ] Verify Eclipse publisher identity
- [ ] Test cross-agent authentication
- [ ] Validate package.json metadata
- [ ] Test marketplace integration

### Version Metadata Verification

- [ ] Verify all version markers (4.0.36)
- [ ] Verify FLIP headers/footers
- [ ] Verify timestamp format (YYYYMMDD)
- [ ] Verify agent identity format (type|name)

---

## FULL UPGRADE TEST

### Crafty Syntax 3.7.5 → Lupopedia 4.0.36

- [ ] Prepare test environment
- [ ] Load Crafty Syntax 3.7.5 schema
- [ ] Load Crafty Syntax 3.7.5 data
- [ ] Run Lupopedia installer/upgrade
- [ ] Validate schema migration
- [ ] Validate seed data
- [ ] Validate registry loading
- [ ] Validate agent detection
- [ ] Validate VSX Extension integration
- [ ] Validate admin panel
- [ ] Validate OAuth (Google/GitHub)
- [ ] Validate semantic security
- [ ] Document all failures and regressions

---

## REGISTRY CONSOLIDATION (PHASE 1)

### Database Migration

- [ ] Schedule database maintenance window
- [ ] Create database backup
- [ ] Execute migration script (dev_20260223_registry_consolidation.sql)
- [ ] Verify data integrity
- [ ] Test ANUBIS orphan adoption
- [ ] Update all code references
- [ ] Drop lupo_unified_registry table
- [ ] Final verification tests

---

## AGENT DETECTION AUTOMATION (PHASE 2)

### Automation Service

- [ ] Create automated detection service
- [ ] Implement periodic scanning (daily/hourly)
- [ ] Add status change notifications
- [ ] Create availability dashboard
- [ ] Add historical tracking

### Integration

- [ ] Integrate with task dispatch system
- [ ] Add fallback logic to IDE_TASK_PRIORITY_DOCTRINE
- [ ] Create agent status API endpoint
- [ ] Add status monitoring alerts

---

## SEMANTIC SECURITY EXPANSION (PHASE 4)

### Coverage Expansion

- [ ] Expand bypass pattern detection
- [ ] Add new semantic signatures
- [ ] Enhance emotional geometry validation
- [ ] Improve X_LUPO_FORWARDED validation

### ANUBIS Integration

- [ ] Enhanced semantic learning
- [ ] Automated bypass pattern detection
- [ ] Threat level classification improvements
- [ ] Quarantine rule enhancements

### Security Dashboard

- [ ] Create security dashboard UI
- [ ] Real-time threat monitoring
- [ ] Security event visualization
- [ ] Compliance metrics

---

## OAUTH STABILITY IMPROVEMENTS (PHASE 5)

### Error Handling

- [ ] Complete callback handling
- [ ] Improve error messages
- [ ] Add detailed error logging
- [ ] Add retry logic

### Token Management

- [ ] Implement token refresh logic
- [ ] Token expiration handling
- [ ] Automatic token renewal
- [ ] Token storage security

### Session Persistence

- [ ] Improve session persistence
- [ ] Handle session timeouts gracefully
- [ ] Remember me functionality
- [ ] Cross-device session management

---

## DOCTRINE CLEANUP (PHASE 6)

### Cleanup

- [ ] Review all doctrine files
- [ ] Consolidate duplicates
- [ ] Remove outdated doctrines
- [ ] Update cross-references

### Consolidation

- [ ] Merge related doctrines
- [ ] Create doctrine index
- [ ] Add doctrine versioning
- [ ] Update documentation

---

## NOTES

### IDE Agent Status

- **KIRO IDE (1001):** Active - Fastest agent
- **Windsurf IDE (1002):** Active - Audit/coordination
- **Antigravity IDE (1003):** Active - Extensions
- **Warp IDE (1004):** Offline - Credit limit
- **Cursor IDE (1005):** Offline - Token limit

### Priority Focus

1. VSX Extension testing (end-to-end validation)
2. Full upgrade test (Crafty Syntax 3.7.5 → Lupopedia 4.0.36)
3. Registry consolidation (database phase)
4. Agent detection automation
5. OAuth stability improvements

---

**STATUS:** Ready to begin 4.0.36 development cycle  
**NEXT:** Begin VSX Extension testing, schedule full upgrade test  
**UPDATED:** 20260223  

**END OF TODO**
