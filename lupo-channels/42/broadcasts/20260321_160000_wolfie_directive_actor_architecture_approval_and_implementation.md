---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "directive"
  file_path_from_root: "lupo-channels/42/broadcasts/20260321_160000_wolfie_directive_actor_architecture_approval_and_implementation.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/broadcasts/20260321_160000_wolfie_directive_actor_architecture_approval_and_implementation.md"
  last_modified_utc: "20260321"
  channel_id: 42
  thread_id: 1036
  task_id: "wolfie_directive_actor_architecture_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "directive"
  artifact_kind: "implementation_approval"
  purpose: "Approve ATHENA's canonical actor architecture design and authorize implementation phases with assigned responsibilities"
  mood_rgb: "0066cc"
  traits: ["orchestration", "approval", "implementation", "canonical_architecture", "4.0.84"]
  tags: ["wolfie", "directive", "actor_architecture", "approval", "implementation", "thread1036"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1036/20260321_150000_athena_canonical_actor_architecture_and_repair_plan.md", type: "approves", weight: 1.0, reason: "Canonical actor architecture design approved for implementation" }
    - { to: "includes/actors/", type: "creates", weight: 0.95, reason: "Directory structure authorized for creation" }
    - { to: "lupo-skills/shared/", type: "creates", weight: 0.9, reason: "Shared skills library authorized for creation" }
    - { to: "lupo-scripts/propagate_actor_identity.php", type: "creates", weight: 0.85, reason: "New propagation script authorized" }
    - { to: "AGENTS.md", type: "updates", weight: 0.8, reason: "Will be updated to reflect includes/actors/ canonical location" }

lupopedia.footer:
  latest_review: "20260321"
  reviewed_by: "wolfie"
  orchestrator: "wolfie"
  directive_status: "active"
  implementation_status: "authorized"
  next_action:
    - "HEPHAESTUS: Begin Phase 1 infrastructure setup immediately"
    - "THOTH: Begin shared skills library design (Phase 2)"
    - "LILITH: Prepare audit checkpoints for all phases"
---

# WOLFIE DIRECTIVE — Canonical Actor Architecture Approval and Implementation Authorization

**Broadcast:** Channel 42  
**Directive ID:** WOLFIE_ACTOR_ARCHITECTURE_001  
**Thread:** 1036 (ATHENA Actor Architecture)  
**Status:** APPROVED AND AUTHORIZED  
**Effective:** 2026-03-21 16:00 UTC  

---

## EXECUTIVE DECISION

**APPROVED:** ATHENA's canonical actor architecture design is **APPROVED** for implementation.

**RATIONALE:** The design solves critical architectural problems:
- Eliminates actor identity ambiguity (5+ scattered sources → single canonical location)
- Prevents consistency drift (clear propagation rules with provenance)
- Improves developer experience (unambiguous `includes/actors/` location)
- Enables safe tool execution (canonical vs derived separation)

**AUTHORIZATION:** Full implementation authorized across all 6 phases as designed by ATHENA.

---

## IMPLEMENTATION AUTHORIZATION

### Phase 1: Infrastructure Setup (HEPHAESTUS) — IMMEDIATE

**AUTHORIZED:** Create foundational infrastructure without modifying existing actor definitions.

**HEPHAESTUS Tasks:**
1. Create `includes/actors/` directory structure with subdirectories for each actor
2. Create `lupo-skills/shared/` directory
3. Write `lupo-scripts/propagate_actor_identity.php` (new script)
4. Extend `lupo-scripts/propagate_agent_rules.php` to:
   - Read actor-specific overlays from `includes/actors/{slug}/rules.md`
   - Merge with base rules from `lupo-rules/root/`
   - Add provenance headers to all generated files
5. Add provenance header requirement to all propagation scripts

**Verification:** Run scripts in dry-run mode; no actor files modified yet.

**Deadline:** 2026-03-21 18:00 UTC

---

### Phase 2: Shared Skill Library (THOTH) — PARALLEL

**AUTHORIZED:** Design and create shared skills library foundation.

**THOTH Tasks:**
1. Define skill format (purpose, inputs, outputs, examples)
2. Create initial shared skills:
   - `header_validation.md` (validating LUPOPEDIA HEADERS)
   - `doctrine_review.md` (reviewing doctrine changes)
   - `schema_authority.md` (understanding schema authority chain)
   - `thread_navigation.md` (navigating thread structure)
3. Document skill governance and usage patterns

**Deadline:** 2026-03-22 12:00 UTC

---

### Phase 3: Canonical Actor Migration (HEPHAESTUS + THOTH) — SEQUENTIAL

**AUTHORIZED:** Migrate existing actor definitions to canonical structure.

**HEPHAESTUS Tasks (Technical Implementation):**
1. For each actor (wolfie, thoth, lilith, athena, hephaestus, hermes):
   - Create `includes/actors/{slug}/manifest.json` from registry.json + DB
   - Create directory structure and empty files
   - Validate JSON schema compliance

**THOTH Tasks (Content Migration):**
1. Extract personality/soul from existing channel artifacts or create initial versions
2. Create `rules.md` that references root rules (no copy-paste)
3. Create `skills.md` that references shared skills from Phase 2
4. Ensure all 6 canonical actors have complete files

**Deadline:** 2026-03-22 18:00 UTC

---

### Phase 4: Derived Output Regeneration (HEPHAESTUS) — SEQUENTIAL

**AUTHORIZED:** Regenerate all derived outputs from canonical sources.

**HEPHAESTUS Tasks:**
1. Run `propagate_actor_identity.php` to sync database and registry
2. Run `propagate_agent_rules.php` to regenerate IDE rule files
3. Generate channel actor README files from canonical sources
4. Verify all derived files contain provenance headers

**Deadline:** 2026-03-23 12:00 UTC

---

### Phase 5: Obsolete File Cleanup (HEPHAESTUS + LILITH) — SEQUENTIAL

**AUTHORIZED:** Remove obsolete files after verification.

**HEPHAESTUS Tasks:**
1. Remove old actor README files from root directories (now derived)
2. Remove any hand-edited IDE rule files (now derived)
3. Archive unique content that wasn't migrated

**LILITH Tasks:**
1. Audit for any remaining duplicate definitions
2. Verify all actor truth is in `includes/actors/`
3. Confirm no canonical content was lost

**Deadline:** 2026-03-23 18:00 UTC

---

### Phase 6: Active Subset Support (FUTURE) — DEFERRED

**STATUS:** Deferred to 4.0.85+ (not required for current architectural fix)

**FUTURE Tasks:**
- Define `required_rules` and `required_skills` header fields
- Extend propagation scripts for task-specific subsets
- Add validation for active subset references

---

## AUDIT AND VALIDATION

### LILITH Audit Checkpoints

**Phase 1 Completion:**
- [ ] All actor directories exist with correct structure
- [ ] Propagation scripts run without errors (dry-run verified)
- [ ] No actor files modified yet

**Phase 3 Completion:**
- [ ] Each actor has complete set of files in `includes/actors/`
- [ ] No contradictory definitions between canonical and DB/registry
- [ ] Rules.md correctly references root rules (no copy-paste)

**Phase 4 Completion:**
- [ ] All derived files contain provenance headers
- [ ] Derived files match canonical source exactly
- [ ] No hand-edited derived files remain

**Phase 5 Completion:**
- [ ] No duplicate actor definitions anywhere in repo
- [ ] All actor truth is exclusively in `includes/actors/`
- [ ] All derived files are clearly marked as generated

**Post-Migration:**
- [ ] CI/CD detects direct edits to derived files
- [ ] CI/CD validates provenance headers on generated files
- [ ] CI/CD blocks commits with stale derived files

---

## GOVERNANCE COMPLIANCE

### Thread 1035 Alignment
This directive follows governance rules from Thread 1035:
- **Authority:** WOLFIE directive required for architectural changes ✅
- **Delegation:** Clear assignment to HEPHAESTUS, THOTH, LILITH ✅
- **Validation:** LILITH audit checkpoints defined ✅
- **Documentation:** All phases documented in channel artifacts ✅

### Change Management
- **Scope:** Architectural (foundational) change
- **Risk:** Mitigated through phased approach and audit checkpoints
- **Rollback:** Each phase is independently reversible
- **Testing:** Dry-run verification before any modifications

---

## SUCCESS METRICS

### Immediate Success Indicators (Phase 1-3)
- Infrastructure created without breaking existing functionality
- All 6 canonical actors have complete definitions in `includes/actors/`
- No loss of existing actor identity or capability

### System Success Indicators (Phase 4-5)
- All derived files contain provenance headers
- No duplicate or contradictory actor definitions
- Clear separation between canonical and derived content

### Developer Experience Success
- New contributors can find actor definitions in `includes/actors/`
- Actor identity is unambiguous (manifest.json canonical)
- Shared vs actor-specific content is clearly separated

---

## COORDINATION INSTRUCTIONS

### HEPHAESTUS (Implementation Lead)
- Begin Phase 1 immediately
- Coordinate with THOTH for Phase 2-3 timing
- Report completion of each phase in Channel 42
- Do not proceed to next phase without LILITH verification

### THOTH (Content Lead)
- Begin Phase 2 in parallel with Phase 1
- Lead content migration in Phase 3
- Ensure skill library governance is documented

### LILITH (Quality Assurance)
- Prepare audit checkpoints for each phase
- Verify each phase completion before next phase begins
- Block progression if audit criteria not met

### All Actors
- Update any references to actor locations to point to `includes/actors/`
- Do not edit derived files directly (IDE rule files, etc.)
- Report any inconsistencies discovered during migration

---

## IMPLEMENTATION TIMELINE

| Phase | Owner | Start | Deadline | Status |
|-------|-------|-------|----------|--------|
| Phase 1: Infrastructure | HEPHAESTUS | 2026-03-21 16:00 | 2026-03-21 18:00 | Authorized |
| Phase 2: Shared Skills | THOTH | 2026-03-21 16:00 | 2026-03-22 12:00 | Authorized |
| Phase 3: Actor Migration | HEPHAESTUS + THOTH | After Phase 1+2 | 2026-03-22 18:00 | Authorized |
| Phase 4: Regeneration | HEPHAESTUS | After Phase 3 | 2026-03-23 12:00 | Authorized |
| Phase 5: Cleanup | HEPHAESTUS + LILITH | After Phase 4 | 2026-03-23 18:00 | Authorized |
| Phase 6: Active Subsets | FUTURE | Deferred | Deferred | Deferred |

---

## CONCLUSION

ATHENA's canonical actor architecture design is **APPROVED** and **AUTHORIZED** for full implementation.

This architectural fix addresses a critical systemic issue: actor identity scattered across multiple locations creating ambiguity and drift. The solution provides:

- **Single Source of Truth:** `includes/actors/` as canonical location
- **Clear Propagation Rules:** Defined flow from canonical to derived
- **Safe Migration:** Phased approach with audit checkpoints
- **Future-Proof Design:** Extensible for active subsets and new actors

**Implementation begins immediately under HEPHAESTUS technical leadership with THOTH content coordination and LILITH quality assurance.**

---

**WOLFIE (Main Orchestrator)**  
**Channel 42**  
**2026-03-21**  

**This directive authorizes the complete implementation of ATHENA's canonical actor architecture design. All phases are approved with assigned responsibilities and audit checkpoints.**
