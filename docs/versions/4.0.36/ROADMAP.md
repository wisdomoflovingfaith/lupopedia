---
wolfie.headers:
  file_path_from_root: "docs/versions/4.0.36/ROADMAP.md"
  system_version: "4.0.36"
  channel_id: 42
  mood_rgb: "00FF88"
  purpose: "Roadmap for version 4.0.36 development cycle"
  last_modified: "20260223"
  x_lupo_forwarded: "1001:10000"
  actor_id: 1001
  lupo_agent: "ide|kiro"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "docs/versions/4.0.36/TODO.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1001
    - 10000
  inbound_edges:
    - "version_4_0_36"
    - "roadmap"
  footnotes:
    - "Roadmap for 4.0.36 development cycle"
    - "Focus on testing and validation"
  version: "4.0.36"
  last_verified: "20260223"
  last_verified_by: "kiro"
---

# ROADMAP — VERSION 4.0.36

**Version:** 4.0.36  
**Status:** In Progress  
**Started:** 20260223  
**Theme:** Testing & Validation  

---

## OVERVIEW

Version 4.0.36 focuses on comprehensive testing and validation of all work completed in 4.0.35. This includes end-to-end VSX Extension testing, full Crafty Syntax 3.7.5 → Lupopedia 4.0.36 upgrade validation, and execution of deferred phases from 4.0.35.

---

## PHASE 1: VSX EXTENSION TESTING

**Status:** Not Started  
**Priority:** CRITICAL  
**Lead:** Windsurf IDE (1002) + Antigravity IDE (1003)  
**Dependencies:** VSX Extension MD-only fallback (complete in 4.0.35)  

### Objectives

- Test VSX Extension end-to-end
- Validate all three operational modes (md_only, hybrid, db_online)
- Validate KIRO status query integration
- Verify publisher identity
- Validate version metadata

### Deliverables

- VSX Extension test report
- Mode switching validation
- KIRO integration validation
- Publisher identity verification
- Metadata verification

### Success Criteria

- All modes functional
- Mode switching works correctly
- KIRO integration working
- Publisher identity verified
- All metadata correct

---

## PHASE 2: FULL UPGRADE TEST

**Status:** Not Started  
**Priority:** CRITICAL  
**Lead:** Windsurf IDE (1002)  
**Dependencies:** Phase 1 (VSX Extension testing)  

### Objectives

- Perform full Crafty Syntax 3.7.5 → Lupopedia 4.0.36 upgrade test
- Validate schema migration
- Validate seed data
- Validate all subsystems
- Document all failures and regressions

### Deliverables

- Upgrade test report
- Schema migration validation
- Seed data validation
- Subsystem validation
- Failure/regression documentation

### Success Criteria

- Upgrade completes successfully
- All data migrated correctly
- All subsystems functional
- Zero critical failures
- All regressions documented

---

## PHASE 3: REGISTRY CONSOLIDATION (DATABASE PHASE)

**Status:** Not Started  
**Priority:** HIGH  
**Lead:** KIRO IDE (1001)  
**Dependencies:** Database access, maintenance window  

### Objectives

- Execute registry consolidation migration
- Migrate lupo_unified_registry → lupo_registry
- ANUBIS orphan adoption
- Remove legacy table
- Update all code references

### Deliverables

- Single canonical registry table
- All data migrated with zero loss
- ANUBIS adoption logs
- Updated code and documentation
- Legacy table removed

### Success Criteria

- Zero data loss
- All orphans adopted or quarantined
- All code updated
- All tests passed
- Legacy table dropped

---

## PHASE 4: AGENT DETECTION AUTOMATION

**Status:** Not Started  
**Priority:** MEDIUM  
**Lead:** KIRO IDE (1001)  
**Dependencies:** Phase 3 (Registry Consolidation)  

### Objectives

- Automate IDE agent availability detection
- Add periodic scanning
- Create availability dashboard
- Add status change notifications
- Historical tracking

### Deliverables

- Automated detection service
- Availability dashboard
- Status API endpoint
- Notification system
- Historical data tracking

### Success Criteria

- Automatic detection runs daily
- Dashboard shows real-time status
- Notifications sent on status changes
- Historical data available

---

## PHASE 5: SEMANTIC SECURITY EXPANSION

**Status:** Not Started  
**Priority:** MEDIUM  
**Lead:** TBD  
**Dependencies:** None  

### Objectives

- Expand bypass pattern detection
- Enhanced ANUBIS integration
- Security dashboard
- Threat monitoring

### Deliverables

- Expanded pattern database
- Enhanced ANUBIS capabilities
- Security dashboard UI
- Monitoring tools

### Success Criteria

- Pattern database expanded by 50%
- ANUBIS integration complete
- Dashboard operational
- Monitoring active

---

## PHASE 6: OAUTH STABILITY IMPROVEMENTS

**Status:** Not Started  
**Priority:** LOW  
**Lead:** TBD  
**Dependencies:** None  

### Objectives

- Complete callback handling
- Token refresh logic
- Session persistence
- Comprehensive testing

### Deliverables

- Improved OAuth error handling
- Automatic token refresh
- Better session management
- Test coverage

### Success Criteria

- Error handling improved
- Token refresh working
- Session persistence stable
- Tests passing

---

## PHASE 7: DOCTRINE CLEANUP

**Status:** Not Started  
**Priority:** LOW  
**Lead:** TBD  
**Dependencies:** None  

### Objectives

- Review all doctrine files
- Consolidate duplicates
- Remove outdated doctrines
- Create doctrine index

### Deliverables

- Consolidated doctrines
- Doctrine index
- Updated documentation
- Cross-references updated

### Success Criteria

- All doctrines reviewed
- Duplicates consolidated
- Index created
- Documentation updated

---

## TIMELINE

### Week 1 (20260223-20260301)
- Phase 1: VSX Extension Testing
- Phase 2: Full Upgrade Test

### Week 2 (20260302-20260308)
- Phase 3: Registry Consolidation (database phase)
- Phase 4: Agent Detection Automation (planning)

### Week 3 (20260309-20260315)
- Phase 4: Agent Detection Automation (implementation)
- Phase 5: Semantic Security Expansion (planning)

### Week 4 (20260316-20260322)
- Phase 5: Semantic Security Expansion (implementation)
- Phase 6: OAuth Stability Improvements
- Phase 7: Doctrine Cleanup

---

## DEPENDENCIES

### External Dependencies
- Database access (for Phase 3)
- Maintenance window (for Phase 3)
- Test environment (for Phase 2)

### Internal Dependencies
- Phase 2 depends on Phase 1 (VSX testing)
- Phase 4 depends on Phase 3 (registry consolidation)

---

## RISKS

### High Risk
- **Upgrade Test Failures:** Critical bugs discovered during upgrade test
- **Mitigation:** Comprehensive testing, rollback plan

### Medium Risk
- **Registry Migration:** Data loss, application downtime
- **Mitigation:** Transaction-based, backup, rollback plan

### Low Risk
- **VSX Extension:** Mode switching issues
- **Mitigation:** Comprehensive testing, fallback modes

---

## SUCCESS METRICS

### Technical Metrics
- VSX Extension: All modes functional
- Upgrade test: Zero critical failures
- Registry migration: Zero data loss
- Agent detection: 100% accuracy

### Quality Metrics
- All tests passing
- Zero critical bugs
- Documentation complete
- Code review approved

### Performance Metrics
- VSX Extension: Mode switching <1 second
- Upgrade test: Completes in <10 minutes
- Registry queries: <100ms
- Agent detection: <5 seconds

---

## NEXT STEPS

1. Begin Phase 1 (VSX Extension testing)
2. Schedule Phase 2 (full upgrade test)
3. Schedule database maintenance window for Phase 3
4. Assign Phase 4 to KIRO IDE

---

**ROADMAP INITIALIZED**

KIRO IDE (actor_id 1001)  
UTC Date: 20260223  

**END OF ROADMAP**
