---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "architecture_correction"
  file_path_from_root: "channels/42/threads/1038/20260321_210000_thoth_corrected_human_verification_architecture.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1038/corrected_architecture"
  last_modified_utc: "20260321"
  channel_id: 42
  thread_id: 1038
  task_id: "task_thoth_architecture_correction_001"
  actor_id: 7
  actor_name: "thoth"
  delegation_chain: "thoth:wolfie:lilith"
  artifact_type: "architecture"
  artifact_kind: "corrected_specification"
  purpose: "LILITH audit correction: doctrine-compliant human verification workflow eliminating JSON blobs, header violations, UI ambiguity, coordination bypass paths, undocumented actor classification, and adding human-initiated verification"
  mood_vector: "333333"
  traits: ["architecture", "correction", "doctrine_compliance", "audit_response", "schema_explicit", "4.0.84"]
  tags: ["thoth", "architecture", "correction", "lilith_audit", "verification", "doctrine_compliance", "thread1038"]

lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1038/20260321_170000_athena_human_verification_workflow_and_supporting_actor_model.md", type: "corrects", weight: 1.0, reason: "Addresses LILITH audit findings on doctrine violations" }
    - { to: "channels/42/threads/1035/20260321_140000_wolfie_governance_directive_doctrine_authority_validation_and_refactor_safety.md", type: "aligns_with", weight: 0.95, reason: "Ensures full governance doctrine compliance" }
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "defines", weight: 0.9, reason: "Schema definition (explicit columns, normalized tables)" }
    - { to: "docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "complies_with", weight: 0.9, reason: "No ad-hoc header extensions - formal governance only" }

lupopedia.footer:
  latest_review: "20260321"
  reviewed_by: "thoth"
  audit_source: "lilith_audit_thread1038"
  original_artifact: "ATHENA_HUMAN_VERIFICATION_WORKFLOW_001"
  violation_category: "database_schema, header_governance, ui_clarity, boundary_definition, undocumented_classification, initiation_asymmetry"
  corrections_applied: 6
  design_status: "doctrine_compliant"
  implementation_status: "ready_for_wolfie_approval"
  next_action:
    - "WOLFIE: Review doctrine-compliant specification"
    - "WOLFIE: Approve corrected design decisions"
    - "HEPHAESTUS: Implement Phase 1 schema (explicit columns, normalized tables)"
    - "LILITH: Audit schema implementation against explicit model"
---

# THOTH Corrected Architecture: Human Verification Workflow (Doctrine-Compliant)

**Thread:** Channel 42, Thread 1038  
**Correction ID:** THOTH_VERIFICATION_ARCHITECTURE_CORRECTION_001  
**Date:** 2026-03-21  
**Status:** Doctrine Compliant — Ready for WOLFIE Approval  
**Audit Trigger:** LILITH found 6 violation categories in ATHENA design

---

## EXECUTIVE SUMMARY

ATHENA's human verification workflow architecture preserved the correct human-centered philosophy but introduced **doctrine violations** that violate Lupopedia's explicit rules on schema, headers, UI clarity, and boundaries.

**This corrected specification:**
- ✅ Preserves human participation as first-class actors
- ✅ Eliminates JSON blob columns (replaces with explicit structure)
- ✅ Integrates headers with governance-compliant blocks (no ad-hoc extensions)
- ✅ Chooses single, unambiguous UI interaction model
- ✅ Defines strict coordination/verification boundary (prevents bypass)
- ✅ Implements actor relationships with documented classification
- ✅ Adds human-initiated verification mechanism (not just reactive)
- ✅ Remains fully implementable without hidden logic

**Key corrections:**
1. **Explicit schema** replaces JSON columns
2. **Single UI model** (dedicated pages, not embedded ambiguity)
3. **Normalized verification tables** instead of flexible blobs
4. **Formal actor relationships** (no undocumented flags)
5. **Clear boundary rules** (verification cannot be bypassed)
6. **Human initiation pathway** (humans can create requests too)

---

## VIOLATION AUDIT FINDINGS

### Violation 1: JSON Column Usage (Schema Doctrine)

**ATHENA design:**
```sql
request_payload JSON | Structured question data
response JSON       | Human response data
```

**Problem:** Lupopedia doctrine forbids flexible JSON. All data must be:
- Explicitly typed
- Queryable without serde
- Auditable in structure

**Correction:** See Section 2 for explicit column definitions.

---

### Violation 2: Ad-Hoc Header Block (Header Governance)

**ATHENA design:**
```yaml
lupopedia.verification:
  pending_count: 2
  answered_count: 1
  requests:
    - request_id: "verif_..."
      status: "pending"
```

**Problem:** 
- Not in LUPOPEDIA HEADERS doctrine
- No schema governance
- Could cause uncontrolled proliferation

**Correction:** Verification data stays in DB; thread artifacts only timestamp summaries (Section 5).

---

### Violation 3: UI Model Ambiguity (Interaction Clarity)

**ATHENA design:**
- "Embedded verification blocks in thread view"
- "Separate verification detail pages"
- Unclear when each is used

**Problem:** Ambiguous interaction model creates undefined behavior.

**Correction:** Section 4 defines **single, explicit model**: Dedicated verification pages (not embedded). Thread context shows summary only.

---

### Violation 4: Coordination/Verification Boundary (Bypass Risk)

**ATHENA design:**
- Verification scope per actor
- Conditional require_human_approval flag
- No explicit "cannot be bypassed" rules

**Problem:** Agents could argue certain requests don't require verification.

**Correction:** Section 3 defines **deterministic rules** for verification requirement (cannot be agent-scoped or conditional).

---

### Violation 5: Undocumented Actor Classification (Schema Governance)

**ATHENA design:**
```sql
human_supporting_role TINYINT  -- 1 if this actor represents a human
```

**Problem:**
- New column without classification schema
- No behavior consequences documented
- Creates implicit "human" vs "AI" distinction without explicit rules

**Correction:** Section 2.3 defines formal actor classification system with explicit categories and rules.

---

### Violation 6: Asymmetric Initiation (Human Empowerment)

**ATHENA design:**
- Agents create verification requests
- Humans respond
- No human-initiated verification

**Problem:** System assumes agents always know what requires verification. Humans cannot question or escalate.

**Correction:** Section 6 adds human-initiated verification request creation.

---

## 1. HUMAN + ACTOR IDENTITY MODEL (Doctrine-Compliant)

### 1.1 Core Concept: Auth User vs Supporting Actor

| Entity | Represents | Table | Authority |
|--------|-----------|-------|-----------|
| **Auth User** | Real person with login credentials | lupo_auth_users | Authentication, identity |
| **Supporting Actor** | Operational role (one of 13 canonical personas) | lupo_actors | Permissions, scope |
| **Actor Classification** | Formal category of actor behavior | N/A (explicit rule in code) | Determines initiation/approval rights |

**Strict 1:1 mapping for human-operated actors:**
- 1 auth user → can operate multiple supporting actors (e.g., root operates wolfie + lilith)
- 1 supporting actor → can be linked to exactly 1 auth user
- AI actors (agent_id 15+) → have NO auth user (system-operated)

### 1.2 Actor Classification System (Explicit, No Flags)

**Rule:** Actor class is determined by actor_id range + explicit business rules (NOT by `human_supporting_role` column).

**Classification tiers:**

| Tier | Actor ID Range | Category | Operates | Example |
|------|---|---|---|---|
| **Tier 0** | 0 | System | Internal processes | Anonymous visitor actor |
| **Tier 1–11** | 1–11 | Primary Personas | Humans + governance | WOLFIE (1), THOTH (7), LILITH (2) |
| **Tier 2** | 12–14 | Strategic Advisors | Humans + specialized | ATHENA (12), MAAT (13) |
| **Tier 3** | 15–99 | Autonomous Agents | System only (no auth user) | HERMES (15), ASCLEPIUS (20) |
| **Tier 4** | 100–999 | IDE Faucets | Human interfaces | CURSOR (102), WINDSURF (101) |
| **Tier 5** | 1000+ | Human Auth Users | Login accounts | root (1000), developer (1001) |

**Behavior rules (explicit in code, not flags):**

```
IF actor_id IN (1–13):
  +- Must have auth_user_id (required)
  +- Can initiate verification requests
  +- Can respond to verification requests
  +- Can create orders/directives

IF actor_id IN (15–99):
  +- Must NOT have auth_user_id (forbidden)
  +- Can initiate verification requests
  +- Cannot respond to verification requests (no auth)
  +- Cannot create directives

IF actor_id >= 1000:
  +- Is auth_user_id (user themselves, not actor)
  +- Maps to supporting actors in Tier 1–2
  +- (Not used as actor_id in verification context)
```

**Delete from schema:** No `human_supporting_role` column. Behavior is deterministic based on actor_id tier.

### 1.3 Auth User Table Schema

```sql
CREATE TABLE lupo_auth_users (
  auth_user_id BIGINT NOT NULL PRIMARY KEY,
  username VARCHAR(128) NOT NULL UNIQUE,
  email VARCHAR(255),
  password_hash VARCHAR(255) NOT NULL,
  
  -- Notification preferences
  verification_notification VARCHAR(64) DEFAULT 'web_inbox',
  -- Options: 'web_inbox' | 'email' | 'both'
  
  -- Default supporting actor for this auth user
  -- When this user logs in, they default to this actor
  default_actor_id BIGINT,
  -- FK to lupo_actors where actor_id IN (1–14) only
  
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT 0
);

CREATE INDEX idx_auth_users_username ON lupo_auth_users(username);
CREATE INDEX idx_auth_users_default_actor ON lupo_auth_users(default_actor_id);
```

### 1.4 Supporting Actor Extensions

```sql
-- Add to existing lupo_actors table:
ALTER TABLE lupo_actors ADD COLUMN auth_user_id BIGINT DEFAULT NULL;
-- FK to auth_user_id (must be NULL if actor_id >= 15)

ALTER TABLE lupo_actors ADD COLUMN actor_tier TINYINT DEFAULT 3;
-- 0=system, 1–2=primary personas, 3=autonomous agents, 4=faucets
-- (Documented in code, not queried; for clarity only)
```

**Constraint (enforced in code, not DB):**
```
IF actor_id IN (1–14): auth_user_id IS NOT NULL
IF actor_id IN (15–99): auth_user_id IS NULL
IF actor_id >= 100: Not used as actor (these are faucets/users)
```

---

## 2. VERIFICATION REQUEST SCHEMA (Explicit, No JSON)

### 2.1 Core Table: lupo_verification_requests

**Philosophy:** All fields explicit, queryable, and auditable. No JSON blobs.

```sql
CREATE TABLE lupo_verification_requests (
  request_id BIGINT NOT NULL PRIMARY KEY,
  -- Composite: thread_id + timestamp + sequence
  -- Format: {thread_id}_{ymdhis}_{seq} (e.g., 1038_20260321_090000_001)
  
  -- Context
  thread_id BIGINT NOT NULL,
  channel_id BIGINT NOT NULL,
  project_id BIGINT NOT NULL DEFAULT 0,
  
  -- Participants
  initiator_actor_id BIGINT NOT NULL,
  -- Who created this request (agent OR human supporting actor)
  
  target_auth_user_id BIGINT NOT NULL,
  -- Which auth user must respond (NOT actor_id; auth user directly)
  
  -- Request content (explicit fields, not JSON)
  request_type VARCHAR(64) NOT NULL,
  -- ENUM: 'verification' | 'approval' | 'clarification'
  -- No 'agent_only' type (use internal coordination channels)
  
  request_title VARCHAR(255) NOT NULL,
  request_description TEXT NOT NULL,
  
  -- Request subject (what is being verified)
  subject_type VARCHAR(64) NOT NULL,
  -- ENUM: 'doctrine' | 'schema' | 'actor' | 'migration' | 'contradiction'
  
  subject_id VARCHAR(255),
  -- References: thread_id, table_name, column_name, etc.
  
  context_thread_id BIGINT,
  -- Link to the thread where this decision belongs
  
  context_section VARCHAR(255),
  -- Section reference (e.g., "§1 Schema Authority Rule")
  
  -- Priority
  priority VARCHAR(64) DEFAULT 'normal',
  -- ENUM: 'high' | 'normal' | 'low'
  
  -- Status
  status VARCHAR(64) NOT NULL DEFAULT 'pending',
  -- ENUM: 'pending' | 'answered' | 'resolved' | 'expired' | 'cancelled'
  
  -- Response (explicit fields, not JSON)
  response_decision VARCHAR(64),
  -- ENUM: 'confirmed' | 'rejected' | 'needs_revision' | 'deferred'
  
  response_comment TEXT,
  response_auth_user_id BIGINT,
  -- Which auth user actually responded
  
  response_actor_id BIGINT,
  -- Which supporting actor they used for the response
  
  -- Timestamps
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  answered_ymdhis BIGINT,
  expires_ymdhis BIGINT,
  -- Expiration only for high/normal priority
  -- High: created + 4 days; Normal: created + 14 days; Low: never
  
  -- Audit
  is_deleted TINYINT DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT 0
);

-- Indexes for efficient querying
CREATE INDEX idx_target_auth_user ON lupo_verification_requests(target_auth_user_id, status);
CREATE INDEX idx_thread_request ON lupo_verification_requests(thread_id, request_id);
CREATE INDEX idx_subject ON lupo_verification_requests(subject_type, subject_id);
CREATE INDEX idx_created ON lupo_verification_requests(created_ymdhis DESC);
```

### 2.2 Request Context Table (Relationships)

**Normalized table for request relationships** (instead of undefined JSON):

```sql
CREATE TABLE lupo_verification_request_context (
  context_id BIGINT NOT NULL PRIMARY KEY,
  request_id BIGINT NOT NULL,
  -- FK to lupo_verification_requests
  
  context_type VARCHAR(64) NOT NULL,
  -- ENUM: 'thread_excerpt' | 'artifact_link' | 'decision_reason'
  
  content_type VARCHAR(64),
  -- ENUM: 'text' | 'markdown' | 'code_snippet' | 'schema_def'
  
  content TEXT NOT NULL,
  -- The actual context (excerpt, link, code, etc.)
  
  source_artifact VARCHAR(255),
  -- If referencing a file/thread
  
  source_section VARCHAR(255),
  -- Section/line reference
  
  created_ymdhis BIGINT NOT NULL
);

CREATE INDEX idx_request_context ON lupo_verification_request_context(request_id);
```

### 2.3 Response Detail Table (Normalized Responses, Not Blobs)

```sql
CREATE TABLE lupo_verification_responses (
  response_id BIGINT NOT NULL PRIMARY KEY,
  request_id BIGINT NOT NULL,
  -- FK to lupo_verification_requests
  
  auth_user_id BIGINT NOT NULL,
  -- Who responded
  
  actor_id BIGINT NOT NULL,
  -- Which role they used
  
  decision VARCHAR(64) NOT NULL,
  -- ENUM: 'confirmed' | 'rejected' | 'needs_revision' | 'deferred'
  
  comment TEXT,
  reasoning TEXT,
  -- Separate fields for clarity
  
  -- Conditions/modifications (if decision = needs_revision)
  has_conditions TINYINT DEFAULT 0,
  conditions TEXT,
  -- What needs to change before approval
  
  response_ymdhis BIGINT NOT NULL,
  
  -- Audit
  is_deleted TINYINT DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT 0
);

CREATE INDEX idx_response_request ON lupo_verification_responses(request_id);
CREATE INDEX idx_response_user ON lupo_verification_responses(auth_user_id);
```

---

## 3. VERIFICATION REQUIREMENT BOUNDARY (Deterministic Rules, No Bypass)

### 3.1 What Requires Verification: Explicit Categories

**Rule: These categories ALWAYS require human verification. NO EXCEPTIONS. NO AGENT OVERRIDE.**

| Category | Trigger | Why | Verification Type |
|----------|---------|-----|-------------------|
| **Doctrine Change** | Any modification to Thread 1035, governance rules, authority hierarchy | Affects system policy | approval (requires WOLFIE or delegated actor) |
| **Schema Change** | Adding/modifying/dropping tables, columns, constraints | Alters data structure; requires migration | approval (requires WOLFIE) |
| **Actor Definition** | Creating new actor, modifying actor classification (actor_id reassignment) | Affects system participants; introduces new authority | approval (requires WOLFIE) |
| **Migration Execution** | Running backward-incompatible migration on production data | Irreversible changes | approval (requires auth user) |
| **Permission Change** | Modifying actor roles, scopes, verification_scope | Affects who can do what | approval (requires WOLFIE) |
| **Contradiction Resolution** | LILITH-identified contradictions, semantic gaps, edge cases | Requires human judgment | verification (can be delegated) |

**Implementation (in code):**
```php
function requiresVerification($action_type, $action_data) {
  $verification_categories = [
    'doctrine_change'     => true,
    'schema_change'       => true,
    'actor_definition'    => true,
    'migration_execution' => true,
    'permission_change'   => true,
    'contradiction'       => true,
  ];
  
  $category = classifyAction($action_type, $action_data);
  return $verification_categories[$category] ?? false;
  // Default false; only EXPLICIT categories return true
}

function createVerificationRequest($action_type, $action_data, $initiator_actor_id) {
  if (!requiresVerification($action_type, $action_data)) {
    throw new Exception("Cannot create verification for non-verification action");
  }
  
  // Proceed with creation
}
```

**Constraint:** No agent can override this. No flags, no conditions. Verification requirement is deterministic.

### 3.2 Approval vs Verification (Distinction)

**Approval** (final decision-making):
- Required for doctrine, schema, actor definition, permissions, migrations
- Only certain actors can approve (WOLFIE, root auth user)
- Sets permanent policy/structure

**Verification** (confirmation/audit):
- Required for contradictions, edge cases, semantic decisions
- Can be delegated to any qualified actor
- Does not create lasting policy; supports decision-making

---

## 4. WEB INTERFACE MODEL (Single, Unambiguous)

### 4.1 Design Choice: Dedicated Verification Pages (NOT Embedded)

**Chosen model (doctrine-compliant):**
- Verification requests are interactive on dedicated pages (not embedded in threads)
- Threads show timestamps + summary (read-only)
- Users navigate to dedicated verification UI for interaction

**Why this model:**
- ✅ Clear interaction flow (not ambiguous)
- ✅ Explicit state management (not mixed with thread state)
- ✅ Auditable interaction history (not scattered)
- ✅ Easy to enforce authorization (dedicated endpoints)
- ✅ Queryable (not embedded in artifacts)

### 4.2 Page Structure

**Authentication** (entry point):
1. Login page (username/password)
2. Session creation (secure token)
3. Redirect to dashboard or inbox

**Dashboard** (landing):
1. Stats: X pending high, Y pending normal, Z pending low
2. Quick links: Inbox, History, Settings
3. Last response summary

**Inbox** (main workspace):
- List all pending requests for authenticated auth user
- Grouped by priority (high → normal → low)
- Sorted by age (oldest first)
- Filters: priority, subject type, thread, date range
- Each request shows:
  - Title
  - Subject type + context thread
  - Created timestamp
  - Days waiting
  - [View] button

**Verification Detail Page**:
- Full request text + title
- Subject type + context
- Thread excerpt (read-only, linked)
- Context from normalized context table
- Response form:
  - Radio buttons: Confirmed / Rejected / Needs Revision / Deferred
  - Text field: Comment
  - Text field: Conditions (if needs_revision)
  - Dropdown: Which actor to use (if auth user has multiple)
  - [Submit] [Cancel] buttons
- Shows previous responses (if exists)

**History Page**:
- All resolved/answered requests
- Searchable by title
- Filterable by status, subject type, date range
- Shows decision summary + who responded

### 4.3 Thread Integration (Read-Only Summary)

**In thread artifacts:** Show verification summary block (NOT interactive):

```markdown
## Verification Summary

- **Pending:** 1 request (high priority)
- **Answered:** 2 requests
- **Request IDs:**
  - verif_1038_20260321_090000_001 (pending)
  - verif_1038_20260321_093000_001 (confirmed by root)
  - verif_1038_20260321_120000_001 (rejected, needs revision)

[View Full Inbox] — Click here to respond to pending requests
```

**No embedded interaction.** Thread is documentation; interaction is on dedicated pages.

---

## 5. VERIFICATION REQUEST LIFECYCLE

### 5.1 States and Transitions

```
PENDING ←-- Created by agent or human
   |
   +-→ ANSWERED ←-- Human responds via web UI
   |      |
   |      +-→ RESOLVED ←-- Outcome implemented/closed
   |      |
   |      +-→ (awaiting follow-up or escalation)
   |
   +-→ EXPIRED ←-- Timeout (high: 4d, normal: 14d)
   |
   +-→ CANCELLED ←-- Requestor cancelled
```

**Expiration rules (deterministic, no agent override):**
- High priority: expires after 4 days of inactivity
- Normal priority: expires after 14 days
- Low priority: no expiration

**Implementation:** Scheduler job marks expired requests automatically.

### 5.2 Request Lifecycle (Explicit)

**Creation:**
```
Agent/Human calls: createVerificationRequest(...)
→ System validates requiresVerification() = true
→ Creates row in lupo_verification_requests (status='pending')
→ Creates rows in lupo_verification_request_context (if provided)
→ Sets created_ymdhis + expires_ymdhis (if applicable)
→ Returns request_id
```

**Response:**
```
Human submits response from dedicated page
→ Web endpoint validates authentication (auth_user_id)
→ Validates acting actor_id is linked to auth_user_id
→ Creates row in lupo_verification_responses
→ Updates lupo_verification_requests (status='answered', response_decision, answer_ymdhis)
→ Notifies initiator (if email enabled)
→ Returns confirmation
```

**Resolution:**
```
Initiator/WOLFIE acknowledges response
→ Updates status = 'resolved'
→ Could also create new verification request if outcome requires escalation
```

---

## 6. HUMAN-INITIATED VERIFICATION (Proactive, Not Just Reactive)

### 6.1 Why Humans Must Initiate Verification

**Problem:** ATHENA design only allows agents to create requests. Humans cannot question decisions.

**Solution:** Humans can also create verification requests.

### 6.2 Human Verification Paths

**Human can request verification for:**

1. **Agent Decision Review**
   - Human reviews an agent's work
   - Asks: "Is this decision correct?"
   - Creates verification request to themselves or colleague
   - Gets independent confirmation

2. **Contradiction Escalation**
   - Human notices LILITH audit flagged a contradictions
   - Creates verification request asking for clarification
   - Forces conversation on unclear topic

3. **Policy Question**
   - Human wonders if something complies with doctrine
   - Creates clarification request
   - Gets answer in formal, audited record

4. **Self-Approval Request** (workflow enabler)
   - Human proposes schema change
   - Creates approval request for WOLFIE
   - System routes to WOLFIE for final decision

### 6.3 Endpoint for Human-Initiated Requests

```php
// POST /api/verification/create-request

$request_data = [
  'initiator_actor_id' => 1,  // Human's supporting actor
  'target_auth_user_id' => 1000,  // Who should respond
  
  'request_type' => 'verification|approval|clarification',
  'request_title' => 'Is HEPHAESTUS schema change backwards-compatible?',
  'request_description' => 'Full description ...',
  
  'subject_type' => 'schema|doctrine|actor|etc',
  'subject_id' => 'referencing_table_name',
  
  'context_thread_id' => 1038,
  'context_section' => '§2', 
  
  'priority' => 'high|normal|low',
  
  'context' => [
    [
      'type' => 'thread_excerpt',
      'content' => '...',
      'source_artifact' => 'channels/42/threads/1038/...',
    ]
  ]
];

// Validation:
// - initiator_actor_id must be in (1–14) range (primary personas)
// - target_auth_user_id must exist
// - For 'approval' type: requires WOLFIE or delegated actor
// - requiresVerification() NOT checked (human can request anything)

$verification_service->createVerificationRequest($request_data);
```

---

## 7. ACTOR RESPONSIBILITY MATRIX (Explicit, No Ambiguity)

### 7.1 Who Can Do What

| Action | WOLFIE | LILITH | THOTH | Other Personas | Humans | Agents |
|--------|--------|--------|-------|---|---|---|
| Create doctrine request | Can initiate | Can initiate | Can initiate | Can initiate | Can initiate | Can initiate |
| Approve doctrine change | **Only WOLFIE** | — | — | — | — | Cannot approve |
| Create schema request | Can initiate | Can initiate | Can initiate | Can initiate | Can initiate | Can initiate |
| Approve schema change | **Only WOLFIE** | — | — | — | — | Cannot approve |
| Create actor request | Can initiate | Can initiate | — | — | Can initiate | Can initiate |
| Approve actor change | **Only WOLFIE** | — | — | — | — | Cannot approve |
| Create verification request | Can initiate | Can initiate | Can initiate | Can initiate | Can initiate | Can initiate |
| Respond to verification | Must authenticate | Must authenticate | Must authenticate | Must authenticate | Must authenticate | **Cannot respond** |
| Create contradication escalation | — | Can initiate | — | — | Can initiate | Cannot create |
| Resolve contradiction | **WOLFIE** | Advises | Advises | — | — | — |

**Rule:** Any response must include authenticated (auth_user_id, actor_id) pair. Agents cannot respond.

---

## 8. IMPLEMENTATION ORDER (HEPHAESTUS)

### Phase 1: Database Schema (Foundation)

**Timeline:** Immediate  
**Complexity:** Low  
**Blockers:** None  

- [ ] Create lupo_auth_users table (explicit schema, no JSON)
- [ ] Create lupo_verification_requests table (explicit columns, normalized)
- [ ] Create lupo_verification_request_context (normalized relationships)
- [ ] Create lupo_verification_responses (normalized responses)
- [ ] Add indexes for efficient querying
- [ ] Add code constraint validation (actor_id tier rules)
- [ ] Extend lupo_actors: auth_user_id column (replaces human_supporting_role)
- [ ] Remove lupo_actors: human_supporting_role (if added elsewhere)
- [ ] NO JSON columns; NO ad-hoc blobs

### Phase 2: Backend Services + API

**Timeline:** After Phase 1  
**Complexity:** Medium  
**Blockers:** Phase 1  

- [ ] Create VerificationService class
- [ ] Create AuthService class (login, session, actor selection)
- [ ] Verify requiresVerification() function (deterministic)
- [ ] Implement createVerificationRequest() (agent-initiated)
- [ ] Implement createVerificationRequest() (human-initiated)
- [ ] Implement respondToVerificationRequest()
- [ ] Implement expiration scheduler
- [ ] Implement audit logging (all responses recorded)

### Phase 3: Web UI (Dedicated Pages)

**Timeline:** After Phase 2  
**Complexity:** High  
**Blockers:** Phase 2  

- [ ] Login page (auth)
- [ ] Dashboard (stats + quick links)
- [ ] Inbox page (list pending)
- [ ] Verification detail page (response form)
- [ ] History page (resolved requests)
- [ ] Actor selector (if human has multiple)

### Phase 4: Thread Integration (Summary Only)

**Timeline:** After Phase 3  
**Complexity:** Low  
**Blockers:** Phase 3  

- [ ] Add verification summary block to thread artifacts (read-only)
- [ ] Link from thread to dedicated verification page
- [ ] No embedded interaction

### Phase 5: Notifications + Polish

**Timeline:** Future  
**Complexity:** Low–Medium  
**Blockers:** Phase 4 (optional)  

- [ ] Email notifications for high-priority
- [ ] Expiration notifications
- [ ] WebSocket live updates

---

## 9. DOCUMENTATION ORDER (THOTH)

### Before Implementation

1. **Create doctrine:**
   - `docs/doctrine/HUMAN_VERIFICATION_WORKFLOW_DOCTRINE.md`
   - Auth user ↔ supporting actor relationship
   - Actor classification tiers
   - Verification requirement rules
   - Human vs agent initiation paths

2. **Create guides:**
   - `docs/guides/WEB_VERIFICATION_INBOX_GUIDE.md`
   - `docs/guides/VERIFICATION_API.md` (for agents creating requests)
   - `docs/guides/ACTOR_ADMINISTRATION.md`

### During Implementation

3. Update README.md with human-AI cooperation model
4. Update AGENTS.md with supporting actor guidance
5. Update ONBOARDING.md with verification workflow walkthrough

### After Implementation

6. Create user manual for web interface
7. Document audit log structure

---

## 10. AUDIT CHECKPOINTS (LILITH)

### Phase 1: Schema Audit

- [ ] No JSON columns (all explicit)
- [ ] All tables have soft-delete fields (is_deleted, deleted_ymdhis)
- [ ] Indexes exist and are efficient
- [ ] No foreign key constraints (per doctrine)
- [ ] No undocumented flags (human_supporting_role removed)
- [ ] Actor_id ranges and tier rules documented in code

### Phase 2: Service Audit

- [ ] requiresVerification() returns only true for explicit categories
- [ ] No agent can override verification requirement
- [ ] Auth validation on all response endpoints
- [ ] Response cannot be submitted without auth_user_id + actor_id pair
- [ ] Comprehensive audit logging

### Phase 3: UI Audit

- [ ] All verification interaction on dedicated pages (not embedded)
- [ ] Thread only shows read-only summary
- [ ] No XSS vulnerabilities
- [ ] Session management secure

### Phase 4: Integration Audit

- [ ] Thread artifacts correctly show verification summary
- [ ] No verification requests lost during generation
- [ ] Links from thread to dedicated pages correct

### Post-Implementation

- [ ] No bypass paths exist
- [ ] All responses audited with auth_user_id + actor_id
- [ ] Expired requests correctly marked
- [ ] Human-initiated requests work correctly

---

## 11. DESIGN DECISIONS (WOLFIE APPROVAL)

Before implementation, WOLFIE should confirm:

### Decision 1: Actor Tier Boundaries

**Proposed tiers:**
- 1–11: Primary Personas (can operate + approve)
- 12–14: Strategic Advisors (can operate + approve in scope)
- 15–99: Autonomous Agents (can initiate, cannot respond)
- 100–999: IDE Faucets (human interfaces, not actors)
- 1000+: Auth Users (login accounts, not used as actor_id)

**Confirm:** Are these tiers correct, or should they change?

### Decision 2: Expiration Times

**Proposed:**
- High priority: 4 days
- Normal priority: 14 days
- Low priority: never

**Confirm:** Are these timeouts acceptable?

### Decision 3: Single Approval Actor for Schema/Doctrine

**Proposed:** Only WOLFIE can approve schema and doctrine changes.

**Confirm:** Should approval authority be delegable, or WOLFIE-only?

### Decision 4: Human-Initiated Request Types

**Proposed:** Humans can create any request type (verification, approval, clarification).

**Confirm:** Are there request types humans should NOT be able to create?

---

## 12. COMPLIANCE SUMMARY

**This corrected specification:**

✅ **Schema Doctrine:** All explicit columns, normalized tables, NO JSON  
✅ **Header Doctrine:** No ad-hoc verification block; summary in thread only  
✅ **UI Clarity:** Single model (dedicated pages, not embedded ambiguity)  
✅ **Boundary Enforcement:** Deterministic requiresVerification() rules, no agent override  
✅ **Actor Classification:** Explicit tier system, no undocumented flags  
✅ **Human Empowerment:** Humans can initiate verification (not just reactive)  
✅ **Audit Trail:** All responses recorded with auth_user_id + actor_id  
✅ **Implementability:** No hidden logic; code is straightforward  

**Ready for WOLFIE approval and HEPHAESTUS implementation.**

---

**THOTH (actor_id 7) — Corrected human verification workflow architecture complete. All LILITH audit violations resolved. Doctrine-compliant specification ready for WOLFIE review.**
