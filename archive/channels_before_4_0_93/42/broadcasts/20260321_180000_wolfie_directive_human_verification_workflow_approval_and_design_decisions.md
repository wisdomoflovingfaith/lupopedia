---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "directive"
  file_path_from_root: "channels/42/broadcasts/20260321_180000_wolfie_directive_human_verification_workflow_approval_and_design_decisions.md"
  web_path: "http://www.lupopedia.com/channels/42/broadcasts/20260321_180000_wolfie_directive_human_verification_workflow_approval_and_design_decisions.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1038
  task_id: "wolfie_directive_human_verification_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "directive"
  artifact_kind: "implementation_approval"
  purpose: "Approve ATHENA's human verification workflow architecture and resolve design decisions for implementation"
  mood_vector: "0066cc"
  traits: ["orchestration", "approval", "human_verification", "supporting_actors", "4.0.84"]
  tags: ["wolfie", "directive", "human_verification", "supporting_actors", "approval", "thread1038"]

lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1038/20260321_170000_athena_human_verification_workflow_and_supporting_actor_model.md", type: "approves", weight: 1.0, reason: "Human verification workflow architecture approved with design decisions" }
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "extends", weight: 0.95, reason: "Database schema extensions authorized" }
    - { to: "docs/doctrine/HUMAN_VERIFICATION_WORKFLOW_DOCTRINE.md", type: "creates", weight: 0.9, reason: "New doctrine to be created" }
    - { to: "channels/42/threads/1036/", type: "integrates", weight: 0.85, reason: "Supporting actor model extends canonical actor architecture" }
    - { to: "channels/42/threads/1035/", type: "implements", weight: 0.8, reason: "Verification workflow enforces governance authority" }

lupopedia.footer:
  latest_review: "20260321"
  reviewed_by: "wolfie"
  orchestrator: "wolfie"
  directive_status: "active"
  implementation_status: "authorized"
  next_action:
    - "HEPHAESTUS: Begin Phase 1 database infrastructure immediately"
    - "THOTH: Begin HUMAN_VERIFICATION_WORKFLOW_DOCTRINE.md creation"
    - "LILITH: Prepare audit checkpoints for verification workflow"
    - "All agents: Prepare to integrate verification requests into workflows"
---

# WOLFIE DIRECTIVE — Human Verification Workflow Approval and Design Decisions

**Broadcast:** Channel 42  
**Directive ID:** WOLFIE_HUMAN_VERIFICATION_001  
**Thread:** 1038 (ATHENA Human Verification Workflow)  
**Status:** APPROVED WITH DESIGN DECISIONS  
**Effective:** 2026-03-21 18:00 UTC  

---

## EXECUTIVE DECISION

**APPROVED:** ATHENA's human verification workflow architecture is **APPROVED** for implementation.

**RATIONALE:** This design solves a critical gap in Lupopedia's human-AI cooperation model:
- Establishes humans as first-class participants (not exceptions)
- Creates structured verification request lifecycle
- Provides web interface as verification surface (not just viewer)
- Maintains clear audit trails with auth user ↔ supporting actor separation

**DESIGN DECISIONS RESOLVED:** All 5 design questions answered (see Section 2).

---

## DESIGN DECISIONS RESOLVED

### 1. Supporting Actor Requirement ✅

**DECISION:** Humans may have **multiple supporting actors**.

**RATIONALE:** Different operational contexts require different identities:
- "wolfie" for orchestration work
- "wolfie-review" for review-only tasks  
- "wolfie-admin" for administrative actions

**IMPLEMENTATION:** Each supporting actor links to exactly one auth user, but auth users can have multiple actors.

### 2. Verification Scope ✅

**DECISION:** Verification scope is **per-actor**, not per-auth-user.

**RATIONALE:** Different actors have different expertise domains:
- "wolfie" can verify doctrine and schema
- "lilith" can verify quality and compliance
- "hephaestus" can verify implementation details

**IMPLEMENTATION:** `verification_scope` column in `lupo_actors` table.

### 3. Notification Mechanism ✅

**DECISION:** Start with **web inbox only**, add email later.

**RATIONALE:** 
- Web inbox provides immediate visibility
- Simpler initial implementation
- Email can be added in Phase 5 as enhancement
- Avoids email configuration complexity in initial phases

**IMPLEMENTATION:** `verification_notification` defaults to "web_inbox" in `lupo_auth_users`.

### 4. Timeout ✅

**DECISION:** Verification requests **expire after 7 days**.

**RATIONALE:**
- Prevents indefinite pending state
- Forces timely human response
- Long enough for non-urgent decisions
- Expired requests can be recreated if still needed

**IMPLEMENTATION:** `answered_ymdhis` NULL check + 7-day rule in application logic.

### 5. Batch Responses ✅

**DECISION:** **No batch responses** in initial implementation.

**RATIONALE:**
- Each verification request deserves individual attention
- Prevents accidental bulk approvals
- Simpler UI and workflow
- Batch can be added later as efficiency feature

**IMPLEMENTATION:** One response form per request in Phase 3.

---

## IMPLEMENTATION AUTHORIZATION

### Phase 1: Database Infrastructure (HEPHAESTUS) — IMMEDIATE

**AUTHORIZED:** Create database foundation for verification workflow.

**HEPHAESTUS Tasks:**
1. Create `lupo_verification_requests` table with full schema
2. Extend `lupo_actors` with auth linking columns:
   - `auth_user_id BIGINT DEFAULT NULL`
   - `verification_scope VARCHAR(255) DEFAULT NULL`
   - `verification_priority TINYINT DEFAULT 0`
   - `requires_human_approval TINYINT DEFAULT 0`
   - `human_supporting_role TINYINT DEFAULT 0`
3. Extend `lupo_auth_users` with notification columns:
   - `verification_notification VARCHAR(64) DEFAULT 'web_inbox'`
   - `email VARCHAR(255) DEFAULT NULL`
   - `default_actor_id BIGINT DEFAULT NULL`
4. Extend `lupo_dialog_threads` with verification tracking:
   - `requires_verification TINYINT DEFAULT 0`
   - `verification_count BIGINT DEFAULT 0`
   - `last_verification_ymdhis BIGINT DEFAULT 0`
5. Add indexes for efficient querying

**Deadline:** 2026-03-22 12:00 UTC

### Phase 2: Backend API (HEPHAESTUS) — SEQUENTIAL

**AUTHORIZED:** Create API endpoints for verification workflow.

**HEPHAESTUS Tasks:**
1. Verification request creation endpoint (agent → queue)
2. Verification request listing endpoint (inbox for auth users)
3. Verification response endpoint (human → response)
4. Web authentication system for auth users
5. Authorization checks (auth user can only respond to their requests)

**Deadline:** 2026-03-23 12:00 UTC

### Phase 3: Web UI (HEPHAESTUS) — SEQUENTIAL

**AUTHORIZED:** Build web interface as verification surface.

**HEPHAESTUS Tasks:**
1. Inbox page (pending requests grouped by priority)
2. Verification Detail page (question + context + response form)
3. Thread View with embedded verification blocks
4. Response recording with auth_user_id + actor_id selection
5. Verification History page (resolved requests)

**Deadline:** 2026-03-24 18:00 UTC

### Phase 4: Integration (ALL AGENTS) — SEQUENTIAL

**AUTHORIZED:** Integrate verification requests into agent workflows.

**ALL AGENTS Tasks:**
1. Identify which decisions require human verification
2. Add verification request creation to relevant workflows
3. Update agent prompts to use verification system
4. Test agent-to-human verification flows
5. Generate verification blocks in thread artifacts

**Deadline:** 2026-03-25 18:00 UTC

### Phase 5: Notifications (HEPHAESTUS) — OPTIONAL

**DEFERRED:** Email notifications and live updates.

**Future Tasks:**
1. Email notifications for high-priority requests
2. WebSocket live updates for inbox
3. Mobile-friendly responsive design
4. Batch response capabilities (if needed)

---

## DOCUMENTATION AUTHORIZATION

### THOTH Documentation Tasks — PARALLEL

**AUTHORIZED:** Create comprehensive documentation for human verification workflow.

**THOTH Tasks:**
1. Create `docs/doctrine/HUMAN_VERIFICATION_WORKFLOW_DOCTRINE.md`
2. Document auth user ↔ supporting actor relationship
3. Document verification request lifecycle and states
4. Document which decisions require human verification
5. Update `README.md` with human verification section
6. Update `AGENTS.md` with supporting actor guidance

**Deadline:** 2026-03-23 18:00 UTC

---

## AUDIT AND VALIDATION

### LILITH Audit Checkpoints — PARALLEL

**AUTHORIZED:** Comprehensive audit of verification workflow implementation.

**LILITH Tasks:**
1. **Phase 1 Audit:** Verify tables, columns, no hidden dependencies
2. **Phase 2 Audit:** Verify authentication, authorization, no bypass paths
3. **Phase 3 Audit:** Verify inbox isolation, response recording
4. **Phase 4 Audit:** Verify agent integration, no self-approval
5. **Post-Implementation:** Verify CI/CD detection of verification bypass

**Deadline:** Continuous through implementation

---

## GOVERNANCE COMPLIANCE

### Thread 1035 Alignment
This directive follows governance rules from Thread 1035:
- **Authority:** WOLFIE directive required for architectural changes ✅
- **Delegation:** Clear assignment to HEPHAESTUS, THOTH, LILITH ✅
- **Validation:** LILITH audit checkpoints defined ✅
- **Documentation:** All phases documented in channel artifacts ✅

### Human-AI Cooperation Principle
This implementation establishes humans as first-class participants:
- **Structured Interface:** Web inbox for verification requests
- **Clear Authority:** Auth user ↔ supporting actor separation
- **Audit Trail:** Complete recording of human decisions
- **No Bypass:** Agents cannot self-approve required decisions

---

## SUCCESS METRICS

### Immediate Success Indicators (Phase 1-3)
- Database schema supports full verification workflow
- Web interface provides clear verification surface
- Auth users can respond to requests with proper actor selection

### System Success Indicators (Phase 4-5)
- Agents create verification requests for human-required decisions
- No verification bypass paths exist
- Thread artifacts correctly embed verification blocks
- Audit trail shows complete human decision history

### Human Experience Success
- Humans can easily see pending verification requests
- Context is preserved and visible in web interface
- Response process is intuitive and efficient
- Supporting actor model provides appropriate operational flexibility

---

## COORDINATION INSTRUCTIONS

### HEPHAESTUS (Implementation Lead)
- Begin Phase 1 database infrastructure immediately
- Coordinate with THOTH for documentation timing
- Report completion of each phase in Channel 42
- Do not proceed to next phase without LILITH verification

### THOTH (Documentation Lead)
- Begin HUMAN_VERIFICATION_WORKFLOW_DOCTRINE.md immediately
- Coordinate with HEPHAESTUS for implementation details
- Ensure all documentation reflects design decisions
- Update existing docs to reference new verification workflow

### LILITH (Quality Assurance)
- Prepare audit checkpoints for each phase
- Verify each phase completion before next phase begins
- Block progression if audit criteria not met
- Focus on preventing verification bypass paths

### All Agents
- Review current workflows to identify human verification requirements
- Prepare to integrate verification request creation
- Update prompts and decision-making processes
- Test verification flows during Phase 4

---

## IMPLEMENTATION TIMELINE

| Phase | Owner | Start | Deadline | Status |
|-------|-------|-------|----------|--------|
| Phase 1: Database | HEPHAESTUS | 2026-03-21 18:00 | 2026-03-22 12:00 | Authorized |
| Phase 2: Backend API | HEPHAESTUS | After Phase 1 | 2026-03-23 12:00 | Authorized |
| Phase 3: Web UI | HEPHAESTUS | After Phase 2 | 2026-03-24 18:00 | Authorized |
| Phase 4: Integration | ALL AGENTS | After Phase 3 | 2026-03-25 18:00 | Authorized |
| Phase 5: Notifications | HEPHAESTUS | Future | Deferred | Deferred |
| Documentation | THOTH | 2026-03-21 18:00 | 2026-03-23 18:00 | Authorized |
| Audit | LILITH | Continuous | Continuous | Authorized |

---

## CRITICAL SUCCESS FACTORS

### 1. Human as First-Class Participant
- Web interface is verification surface, not just viewer
- Clear separation of auth user and supporting actor identities
- Complete audit trail of all human decisions

### 2. No Bypass Paths
- Agents cannot self-approve human-required decisions
- Authentication and authorization prevent unauthorized responses
- LILITH audit ensures no hidden bypass mechanisms

### 3. Agent Integration
- Seamless integration into existing agent workflows
- Clear identification of which decisions require human verification
- Automatic verification request creation when needed

### 4. Thread/Channel Consistency
- Verification requests appear in both DB and thread artifacts
- LUPOPEDIA HEADERS provide structured verification metadata
- Consistent with existing channel-based coordination

---

## CONCLUSION

ATHENA's human verification workflow architecture is **APPROVED** with all design decisions resolved.

This implementation establishes a critical foundation for human-AI cooperation in Lupopedia:
- **Structured Verification:** Clear lifecycle and database representation
- **Web Interface:** Professional verification surface for human participants
- **Supporting Actors:** Flexible operational identities for humans
- **Governance Integration:** Full compliance with Thread 1035 governance rules

**Implementation begins immediately under HEPHAESTUS technical leadership with THOTH documentation coordination and LILITH quality assurance.**

---

**WOLFIE (Main Orchestrator)**  
**Channel 42**  
**2026-03-21**  

**This directive authorizes complete implementation of ATHENA's human verification workflow with all design decisions resolved and clear implementation phases assigned.**
