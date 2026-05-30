---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "architecture"
  file_path_from_root: "channels/42/threads/1038/20260321_170000_athena_human_verification_workflow_and_supporting_actor_model.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1038/human_verification_workflow"
  last_modified_utc: "20260321"
  channel_id: 42
  thread_id: 1038
  task_id: "task_athena_human_verification_workflow_001"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:wolfie"
  artifact_type: "architecture"
  artifact_kind: "human_verification_workflow"
  purpose: "Define human verification workflow architecture, auth user → supporting actor mapping, verification request lifecycle, web UI chat/inbox model, and DB/artifact representation"
  mood_vector: "666666"
  traits: ["architecture", "human_verification", "supporting_actor_model", "web_interface", "4.0.84"]
  tags: ["athena", "architecture", "human_verification", "supporting_actors", "web_ui", "thread1038"]

lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1035/20260321_140000_wolfie_governance_directive_doctrine_authority_validation_and_refactor_safety.md", type: "governance_aligned", weight: 0.95, reason: "Verification workflow is part of governance enforcement" }
    - { to: "channels/42/threads/1036/20260321_150000_athena_canonical_actor_architecture_and_repair_plan.md", type: "extends", weight: 0.9, reason: "Supporting actor model extends canonical actor architecture" }
    - { to: "channels/42/threads/1037/", type: "integrates", weight: 0.85, reason: "Version verification can use human verification workflow" }
    - { to: "channels/42/threads/1030/", type: "integrates", weight: 0.8, reason: "Database-backed visibility supports verification UI" }
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "extends", weight: 0.75, reason: "Will add verification tables to schema" }

lupopedia.footer:
  latest_review: "20260321"
  reviewed_by: "athena"
  orchestrator: "wolfie"
  design_status: "complete"
  implementation_status: "pending_wolfie_approval"
  next_action:
    - "WOLFIE: Review and approve/iterate on human verification workflow architecture"
    - "WOLFIE: Confirm design decisions for supporting actors and verification scope"
    - "HEPHAESTUS: Await WOLFIE directive to begin Phase 1 database infrastructure"
    - "THOTH: Prepare documentation for human verification doctrine"
    - "LILITH: Prepare audit checkpoints for verification workflow"
---

# ATHENA Architecture Design: Human Verification Workflow and Supporting Actor Model

**Thread:** Channel 42, Thread 1038  
**Architecture ID:** ATHENA_HUMAN_VERIFICATION_001  
**Created:** 2026-03-21  
**Status:** Design Complete — Awaiting WOLFIE Approval  
**Scope:** Define human verification workflow, auth user → supporting actor mapping, verification request lifecycle, web UI model, and database/artifact representation

---

## EXECUTIVE SUMMARY

Lupopedia is a **human-AI cooperative system**, not an autonomous agent system. The human is not an exception to workflow - they are a **first-class participant** with explicit representation, authority, and interface.

Current problem: No structured way for agents to request human verification, and no web interface for humans to respond. Verification requests are scattered across threads, emails, and informal channels.

This design establishes:
- **Auth User → Supporting Actor** relationship (one human can have multiple operational identities)
- **Verification Request** as first-class entity in database and threads
- **Web Interface** as verification surface (not just viewer)
- **Clear boundaries** between agent-only work and human-required decisions

---

## 1. CORE PRINCIPLE

Lupopedia is a **human-AI cooperative system**, not an autonomous agent system.

**The human is not an exception to the workflow.** The human is a **first-class participant** with explicit representation, authority, and interface.

The web interface is **not a viewer**. It is a **verification surface** where:

- Questions requiring human judgment are surfaced
- Human decisions are recorded
- Authority is exercised
- Clarifications are requested and answered

---

## 2. HUMAN IDENTITY MODEL

### 2.1 Two Layers of Identity

Lupopedia uses **two complementary identity layers**:

| Layer | Table | Purpose | Example |
|-------|-------|---------|---------|
| **Auth User** | lupo_auth_users | Authentication: who is this person? | admin@example.com |
| **Supporting Actor** | lupo_actors | Operational role: what can they do? | wolfie (orchestrator) |

**Key principle:** These are **NOT the same thing**. One human (auth user) may have multiple operational roles (supporting actors).

### 2.2 Auth User Profile

| Field | Purpose | Type | Required |
|-------|---------|------|----------|
| auth_user_id | System ID | BIGINT | Yes |
| username | Login username | VARCHAR | Yes |
| email | Contact for notifications | VARCHAR | Yes |
| password_hash | For authentication | VARCHAR | Yes |
| verification_notification | How to notify of pending requests | VARCHAR (email, web_inbox, both) | Yes (default: web_inbox) |
| default_actor_id | Preferred supporting actor for this auth user | BIGINT | Optional |

### 2.3 Supporting Actor Profile (Extended)

A supporting actor is a **member of the eleven primary personas or specialized agents**. When linked to an auth user:

| Field | Purpose | Type |
|-------|---------|------|
| actor_id | System ID | BIGINT |
| actor_slug | "wolfie", "thoth", etc. | VARCHAR |
| auth_user_id | Which human operates this actor | BIGINT (FK) |
| human_supporting_role | Flag: 1 if this is a human's supporting actor | TINYINT |
| verification_scope | What decisions this actor can verify | VARCHAR(255) |
| verification_priority | Queue priority (high, normal, low) | TINYINT |
| requires_human_approval | Whether actions need human confirmation | TINYINT |

**Example:**
```
auth_user_id: 1000
  +-- username: "root"
  +-- email: "admin@example.com"
  +-- default_actor_id: 1 (wolfie)

actor_id: 1 (wolfie)
  +-- auth_user_id: 1000
  +-- human_supporting_role: 1
  +-- verification_scope: "doctrine, schema, governance"
  +-- requires_human_approval: 1

actor_id: 2 (lilith)
  +-- auth_user_id: 1000 (same human can operate multiple actors)
  +-- human_supporting_role: 1
  +-- verification_scope: "audit, review"
  +-- requires_human_approval: 0 (lilith auto-reviews)
```

### 2.4 Relationship Semantics

**One auth user → Many supporting actors:**
- Root human can operate as WOLFIE (orchestrator), LILITH (reviewer), etc.
- Each actor has its own rules, scope, approval requirements

**One supporting actor → One auth user:**
- Each operational role is linked to exactly one human
- Prevents ambiguous authority

**AI agents → No auth user:**
- Agents 15+ are operated by the system, not humans
- They can still create verification requests but cannot respond to them

### 2.2 Supporting Actor Properties

| Property | Purpose | Example |
|----------|---------|---------|
| `actor_id` | Primary identifier | 1 (wolfie) |
| `actor_slug` | Human-readable slug | "wolfie" |
| `actor_name` | Display name | "Wolfie" |
| `auth_user_id` | Link to human | 1000 (root user) |
| `verification_scope` | What this actor can verify | "doctrine", "schema", "migration" |
| `verification_priority` | Queue ordering | "high", "normal", "low" |
| `requires_human_approval` | Whether actions require auth user confirmation | true/false |

### 2.3 Auth User Properties

| Property | Purpose | Example |
|----------|---------|---------|
| `auth_user_id` | Primary identifier | 1000 |
| `username` | Login name | "root" |
| `email` | Contact for verification requests | "admin@example.com" |
| `verification_notification` | How to notify | "email", "web_inbox", "both" |

### 2.4 Database Schema Extensions

**Add to `lupo_actors`:**
```sql
ALTER TABLE lupo_actors ADD COLUMN auth_user_id BIGINT DEFAULT NULL;
ALTER TABLE lupo_actors ADD COLUMN verification_scope VARCHAR(255) DEFAULT NULL;
ALTER TABLE lupo_actors ADD COLUMN verification_priority TINYINT DEFAULT 0;
ALTER TABLE lupo_actors ADD COLUMN requires_human_approval TINYINT DEFAULT 0;
ALTER TABLE lupo_actors ADD COLUMN human_supporting_role TINYINT DEFAULT 0; -- 1 if this actor represents a human
```

**Add to `lupo_auth_users`:**
```sql
ALTER TABLE lupo_auth_users ADD COLUMN verification_notification VARCHAR(64) DEFAULT 'web_inbox';
ALTER TABLE lupo_auth_users ADD COLUMN email VARCHAR(255) DEFAULT NULL;
ALTER TABLE lupo_auth_users ADD COLUMN default_actor_id BIGINT DEFAULT NULL; -- preferred supporting actor
```

---

## 3. VERIFICATION REQUEST LIFECYCLE

### 3.1 Core Entity: Verification Request

A **verification request** is a structured question that requires human decision-making. It is:

1. **First-class entity** stored in database
2. **Optionally represented** in thread artifacts (Markdown)
3. **Surfaced** in web UI inbox
4. **Responded to** by humans via web UI
5. **Recorded** with auth user + actor identity

**Table: lupo_verification_requests (new)**

```sql
CREATE TABLE lupo_verification_requests (
  request_id BIGINT NOT NULL PRIMARY KEY,
  thread_id BIGINT NOT NULL,
  channel_id BIGINT NOT NULL,
  project_id BIGINT NOT NULL DEFAULT 0,
  
  actor_id BIGINT NOT NULL,              -- Who is asking?
  target_actor_id BIGINT NOT NULL,       -- Whose human should respond?
  
  request_type VARCHAR(64) NOT NULL,     -- verification | approval | clarification | agent_only
  request_title VARCHAR(255),
  request_description TEXT,
  request_payload JSON,                  -- Structured question data
  
  status VARCHAR(64) NOT NULL,           -- pending | answered | expired | cancelled
  response JSON,                         -- Human response data
  response_decision VARCHAR(64),         -- confirmed | rejected | needs_revision | (custom)
  response_comment TEXT,                 -- Human's explanation
  
  auth_user_id BIGINT,                   -- Who actually responded
  response_actor_id BIGINT,              -- Which actor did they use to respond?
  
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  answered_ymdhis BIGINT,
  expires_ymdhis BIGINT,                 -- Optional timeout
  
  is_deleted TINYINT DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT 0
);

CREATE INDEX idx_target_actor_status ON lupo_verification_requests(target_actor_id, status);
CREATE INDEX idx_thread_request ON lupo_verification_requests(thread_id, request_id);
```

### 3.2 Request Types and When to Use Them

| Type | Purpose | Human Response? | Examples |
|------|---------|-----------------|----------|
| **verification** | Confirm a fact, mapping, or decision | Yes | "Confirm schema authority", "Verify actor mapping" |
| **approval** | Grant permission for significant action | Yes | "Approve migration execution", "Authorize schema change" |
| **clarification** | Ask for additional human input | Yes | "What should child thread sort order be?" |
| **agent_only** | Agent-to-agent question (human not involved) | No | Task assignment, status updates |

### 3.3 Lifecycle States and Transitions

```
CREATION (by agent)
   ↓
PENDING (awaiting human response)
   +→ ANSWERED (human responded) → RESOLVED (finalized)
   +→ EXPIRED (timeout, no response)
   +→ CANCELLED (creator cancelled)
```

**States:**
- **pending** — Created, waiting for human decision
- **answered** — Human responded; may have follow-up needed
- **resolved** — Complete; no further action required
- **expired** — Timeout reached without response
- **cancelled** — Requestor cancelled the ask

### 3.4 Response Semantics

When a human responds:

```
Human submits response via web UI:
+-- authentication: auth_user_id = 1000 (verified via login)
+-- actor_selection: actor_id = 1 (wolfie)
+-- decision: "confirmed"
+-- comment: "Proceed with implementation."
+-- recorded_ymdhis: 20260321_120000

Result in DB:
+-- status: "answered"
+-- response_decision: "confirmed"
+-- response_comment: "Proceed with implementation."
+-- auth_user_id: 1000 (who physically responded)
+-- response_actor_id: 1 (which operational role they used)
+-- answered_ymdhis: 20260321_120000
```

This preserves:
- **Who** responded (auth_user_id)
- **What** they decided (response_decision)
- **Which role** they used (response_actor_id)
- **When** they responded (answered_ymdhis)

---

## 4. WEB INTERFACE MODEL

### 4.1 Core Pages

| Page | Purpose | Data Source | User Flow |
|------|---------|-------------|-----------|
| **Login** | Authenticate auth user | lupo_auth_users | Entry point |
| **Dashboard** | Overview of pending work | lupo_verification_requests | Landing page |
| **Inbox** | All pending verification requests | lupo_verification_requests | Main workspace |
| **Verification Detail** | Single request with full context | DB + thread artifact | Decision-making |
| **Thread View** | Thread with embedded requests | DB + thread artifact | Investigation/context |
| **History** | Resolved/archived requests | lupo_verification_requests | Audit trail |

### 4.2 Inbox Page

**Purpose:** Show all pending verification requests for the authenticated auth user, grouped and prioritized.

**Layout:**
```
┌--------------------------------------------------------------+
| Inbox — Wolfie (admin@example.com)  [Log Out]              |
| 1000 | wolfie (orchestrator)                                |
+--------------------------------------------------------------┤
| High Priority (2)                                            |
| ------------------------------------------------------------ |
| ○ [Thread 1032] Schema authority chain verification         |
|   "Confirm WOLFIE is sole schema authority per directive"   |
|   Waiting since: 20260321_090000 (2 hours)       [View]     |
|                                                               |
| ○ [Thread 1004] lupo_visits.actor_id mapping                |
|   "Confirm mapping: visitor_id → actor_id=0"                |
|   Waiting since: 20260320_170000 (1 day)         [View]     |
+--------------------------------------------------------------┤
| Normal Priority (3)                                          |
| ------------------------------------------------------------ |
| ○ [Thread 1036] Actor architecture review                   |
|   "Approve includes/actors/ canonical location"             |
|   Waiting since: 20260321_150000 (20 min)        [View]     |
|                                                               |
| ...                                                          |
+--------------------------------------------------------------┤
| Low Priority (1)                                             |
| ------------------------------------------------------------ |
| ...                                                          |
+--------------------------------------------------------------+
```

**Features:**
- Grouped by priority (high, normal, low)
- Sorted by age within priority
- Click [View] to open detail page
- Filters: priority, thread, date range

### 4.3 Verification Detail Page

**Purpose:** Present a single verification request with full context and response form.

**Layout:**
```
┌--------------------------------------------------------------+
| Verification Request                       [Back to Inbox] |
+--------------------------------------------------------------┤
| Request ID: verif_20260321_090000_001                       |
| Status: Pending  |  Created: 20260321_090000                |
| Priority: High   |  From: WOLFIE (schema authority)         |
+--------------------------------------------------------------┤
|                                                              |
| QUESTION:                                                   |
| ═══════════════════════════════════════════════════════    |
| "Confirm that WOLFIE is the sole authority for schema      |
|  changes per Thread 1032 directive."                        |
|                                                              |
+--------------------------------------------------------------┤
| THREAD CONTEXT:                                             |
| -----------------------------------------------------------|
| Thread: 1032 — WOLFIE Directive — Canonical Project Model  |
| Section: §1 Schema Authority Rule                           |
|                                                              |
| [View Full Thread in New Tab]                               |
|                                                              |
| Excerpt:                                                    |
| ┌-----------------------------------------------------+    |
| | §1. SCHEMA AUTHORITY HIERARCHY                      |    |
| |                                                      |    |
| | WOLFIE is the sole authority for schema changes.    |    |
| | All schema modifications require WOLFIE approval.   |    |
| | The canonical schema source is install_new_.sql.   |    |
| +-----------------------------------------------------+    |
|                                                              |
+--------------------------------------------------------------┤
| YOUR RESPONSE:                                              |
| ═══════════════════════════════════════════════════════    |
|                                                              |
| Do you confirm this authority assignment?                   |
|                                                              |
| Choose one:                                                 |
| ◉ Confirm   ○ Reject   ○ Needs Revision                     |
|                                                              |
| Optional comment:                                           |
| ┌--------------------------------------------------+        |
| | Confirmed. Proceed with implementation.         |        |
| +--------------------------------------------------+        |
|                                                              |
| Responding as: [Dropdown: wolfie ▼]                         |
|                                                              |
| [Submit Response]  [Cancel]                                 |
|                                                              |
+--------------------------------------------------------------+
```

**Features:**
- Full question text
- Thread context (excerpt + link)
- Response options: Confirm/Reject/Needs Revision
- Comment field for explanation
- Actor selector (if human operates multiple actors)
- Submit/Cancel buttons

### 4.4 Thread View with Embedded Verification

**Purpose:** Show a thread with agent messages and verification blocks intermixed.

**Layout:**
```
┌--------------------------------------------------------------+
| Thread 1032 — WOLFIE Directive: Schema Authority           |
+--------------------------------------------------------------┤
|                                                              |
| WOLFIE (agent, 20260321_090000):                            |
| -------------------------------------------------------    |
| I've prepared the canonical project model directive.        |
| This establishes the schema authority chain and project     |
| binding rules. Need human confirmation before proceeding.   |
|                                                              |
+--------------------------------------------------------------┤
| ┌--------------------------------------------------+        |
| | [VERIFICATION REQUEST — PENDING]                |        |
| | ID: verif_20260321_090000_001                   |        |
| |                                                  |        |
| | Question: Confirm WOLFIE is sole schema         |        |
| | authority per directive §1?                      |        |
| | Status: Awaiting response                        |        |
| | Priority: High                                   |        |
| |                                                  |        |
| | [View Full Request] [Respond Now]                |        |
| +--------------------------------------------------+        |
|                                                              |
+--------------------------------------------------------------┤
| Wolfie (human, 20260321_120000):                           |
| -------------------------------------------------------    |
| ✅ Confirmed. Proceed with implementation.                  |
| (Acting as: wolfie orchestrator)                            |
|                                                              |
+--------------------------------------------------------------┤
| HEPHAESTUS (agent, 20260321_120100):                        |
| -------------------------------------------------------    |
| Acknowledged. Proceeding with Phase 1 implementation:       |
| - Creating schema tables                                    |
| - Adding indices                                            |
| - Generating migration scripts                              |
|                                                              |
+--------------------------------------------------------------+
```

**Features:**
- Agent messages in chronological order
- Verification blocks shown in context
- Human responses clearly attributed
- "Respond Now" link takes to detail page

---

## 5. THREAD/CHANNEL INTEGRATION MODEL

### 5.1 How Verification Requests Appear in Threads

| Representation | Location | Purpose |
|----------------|----------|---------|
| **DB Row** | `lupo_verification_requests` | Source of truth, queryable |
| **Embedded Block** | Thread artifact (Markdown) | Human-readable record in filesystem |
| **Web UI Component** | Thread view page | Interactive verification surface |

### 5.2 Artifact Representation

In a thread artifact, a verification request is represented as:

```markdown
## Verification Request

**Request ID:** `verif_20260321_090000_001` 
**Type:** verification
**From:** WOLFIE (agent, actor_id 1)
**To:** Human (supporting actor: wolfie, auth_user_id 1000)
**Status:** pending
**Question:** Confirm that WOLFIE is the sole authority for schema changes per Thread 1032 directive.
**Context:** Section §1 of the directive.

<!-- response block added after human answers -->
## Response (Human)
**Responded:** 20260321_120000
**Decision:** confirmed
**Comment:** Proceed with implementation.
**Recorded by:** wolfie (actor_id 1, auth_user_id 1000)
```

### 5.3 LUPOPEDIA HEADERS for Verification

```yaml
lupopedia.verification:
  request_id: "verif_20260321_090000_001"
  request_type: "verification"
  from_actor_id: 1
  to_actor_id: 1  # human's supporting actor
  to_auth_user_id: 1000
  status: "pending"
  created_utc: "20260321090000"
  context_thread: 1032
  context_section: "§1 Schema Authority Rule"
```

---

## 6. VERIFICATION QUEUE SEMANTICS

### 6.1 What Requires Human Verification vs Agent-Only

| Type | Examples | Verification Required |
|------|----------|----------------------|
| **Doctrine Changes** | New schema authority, versioning rules | Human approval |
| **Schema Changes** | Adding tables, altering columns | Human verification |
| **Migration Execution** | Running migrations on production | Human approval |
| **Actor Definition** | Creating new actors, roles | Human verification |
| **Edge Cases** | LILITH-identified contradictions | Human clarification |
| **Agent Coordination** | Task assignment, status updates | Agent-only |
| **Documentation** | Updating README, comments | Agent-only (with human review flag) |
| **Validation** | Running tests, checking compliance | Agent-only (report to human) |

### 6.2 Priority Assignment

| Priority | Definition | Time Expectation | Examples |
|----------|------------|------------------|----------|
| **High** | Blocks execution | Hours | Schema approval, security decisions |
| **Normal** | Important but not urgent | Days | Doctrine reviews, design approval |
| **Low** | Can wait | Weeks | Historical cleanup, future planning |

### 6.3 Human Authority Representation

**Principle:** Auth user identity and actor identity are separate but linked.

- Auth user authenticates (password, session)
- Auth user selects which supporting actor to act as for a given response
- Response is recorded with:
  - `auth_user_id` (who physically responded)
  - `actor_id` (which operational identity they used)
  - `response` (the decision)

This preserves audit trail without collapsing identity layers.

---

## 7. DATABASE AND ARTIFACT MODEL

### 7.1 New Table: `lupo_verification_requests` 

See Section 3.1 for full schema.

### 7.2 Extended Table: `lupo_actors` 

```sql
ALTER TABLE lupo_actors ADD COLUMN auth_user_id BIGINT DEFAULT NULL;
ALTER TABLE lupo_actors ADD COLUMN verification_scope VARCHAR(255) DEFAULT NULL;
ALTER TABLE lupo_actors ADD COLUMN verification_priority TINYINT DEFAULT 0;
ALTER TABLE lupo_actors ADD COLUMN requires_human_approval TINYINT DEFAULT 0;
ALTER TABLE lupo_actors ADD COLUMN human_supporting_role TINYINT DEFAULT 0; -- 1 if this actor represents a human
```

### 7.3 Extended Table: `lupo_auth_users` 

```sql
ALTER TABLE lupo_auth_users ADD COLUMN verification_notification VARCHAR(64) DEFAULT 'web_inbox';
ALTER TABLE lupo_auth_users ADD COLUMN email VARCHAR(255) DEFAULT NULL;
ALTER TABLE lupo_auth_users ADD COLUMN default_actor_id BIGINT DEFAULT NULL; -- preferred supporting actor
```

### 7.4 Extended Table: `lupo_threads` (or `lupo_dialog_threads`)

```sql
ALTER TABLE lupo_dialog_threads ADD COLUMN requires_verification TINYINT DEFAULT 0; -- 1 if thread has pending verification requests
ALTER TABLE lupo_dialog_threads ADD COLUMN verification_count BIGINT DEFAULT 0; -- number of pending requests
ALTER TABLE lupo_dialog_threads ADD COLUMN last_verification_ymdhis BIGINT DEFAULT 0; -- last response timestamp
```

### 7.5 Artifact Representation (Threads)

Each thread artifact can optionally contain a `lupopedia.verification` block (see Section 5.3) that summarizes pending requests.

---

## 8. IMPLEMENTATION ORDER (HEPHAESTUS)

### Phase 1: Database Infrastructure (Foundation)
- [ ] Create `lupo_verification_requests` table
- [ ] Extend `lupo_actors`, `lupo_auth_users`, `lupo_dialog_threads` 
- [ ] Add indexes for efficient querying

### Phase 2: Backend API (Read/Write)
- [ ] Create verification request creation endpoint (agent → queue)
- [ ] Create verification request listing endpoint (inbox)
- [ ] Create verification response endpoint (human → response)
- [ ] Add web authentication for auth users

### Phase 3: Web UI (Core Pages)
- [ ] Build Inbox page (list pending requests)
- [ ] Build Verification Detail page (question + response form)
- [ ] Build Thread View with embedded verification blocks
- [ ] Add response recording with auth_user_id + actor_id

### Phase 4: Integration with Threads
- [ ] Add verification request creation to agent workflows
- [ ] Auto-generate verification blocks in thread artifacts
- [ ] Update THREAD_INDEX.md to show threads with pending requests

### Phase 5: Notifications (Optional)
- [ ] Email notifications for high-priority requests
- [ ] WebSocket live updates for inbox

---

## 9. DOCUMENTATION ORDER (THOTH)

### Before Implementation
- [ ] Create `docs/doctrine/HUMAN_VERIFICATION_WORKFLOW_DOCTRINE.md` 
- [ ] Document auth user ↔ supporting actor relationship
- [ ] Document verification request lifecycle
- [ ] Document which decisions require human verification

### During Implementation
- [ ] Update `README.md` with human verification section
- [ ] Update `AGENTS.md` with supporting actor guidance
- [ ] Document web UI verification flow

### After Implementation
- [ ] Create user guide for web interface
- [ ] Update actor registration checklist to include supporting actor setup
- [ ] Document verification response recording requirements

---

## 10. AUDIT CHECKPOINTS (LILITH)

### Phase 1 Completion
- [ ] Tables and columns exist
- [ ] No foreign keys, triggers, or stored procedures added
- [ ] `auth_user_id` linking does not create hidden dependencies

### Phase 2 Completion
- [ ] Verification requests cannot be created without explicit actor identity
- [ ] Response endpoint requires both `auth_user_id` and `actor_id` 
- [ ] No response recorded without authentication

### Phase 3 Completion
- [ ] Inbox shows only requests for the authenticated auth user
- [ ] Response forms cannot be submitted by the wrong actor
- [ ] Thread context is preserved and visible

### Phase 4 Completion
- [ ] All agent workflows that require human verification create requests
- [ ] No bypass path exists (agents cannot self-approve)
- [ ] Thread artifacts correctly embed verification blocks

### Post-Implementation
- [ ] CI/CD detects if verification bypass occurs
- [ ] Audit log shows all verification responses
- [ ] No actor can act as human without linked auth user

---

## 11. GOVERNANCE IMPLICATIONS

### 11.1 How This Relates to Other Threads

| Thread | Relationship |
|--------|-------------|
| Thread 1035 (Governance) | Verification workflow is part of governance enforcement |
| Thread 1036 (Actor Architecture) | Supporting actor model extends actor definitions |
| Thread 1037 (Versioning) | Verification field `last_verified_against` can be updated via human verification |
| Thread 1004 (Semantic Validation) | Human can verify/approve mapping corrections |

### 11.2 Authority Assignment

| Action | Authority | Verification Required |
|--------|-----------|----------------------|
| Create verification request | Any agent | No (but request is created) |
| Answer verification request | Human (auth user) | Yes (via supporting actor) |
| Override human decision | WOLFIE directive | N/A (special case) |
| Skip verification | None | Not permitted |

### 11.3 Doctrine Alignment

This architecture is consistent with existing doctrine:

- Auth user / actor separation: already documented in README and `AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md` 
- Channel/thread coordination: already documented in `CHANNEL_BASED_COORDINATION_DOCTRINE.md` 
- Web interface visibility: aligns with Thread 1030 database-backed visibility plan

---

## 12. SUCCESS CRITERIA

After full implementation:

- [ ] A human auth user can log into the web interface
- [ ] The web interface shows pending verification requests in an inbox
- [ ] The human can respond to requests and their response is recorded
- [ ] Responses are visible in thread artifacts and DB
- [ ] Agents cannot bypass human verification for decisions requiring it
- [ ] The system distinguishes between agent-only work and human-required work
- [ ] Supporting actors correctly represent humans in channel/thread activities
- [ ] The web interface functions as a verification surface, not just a viewer

---

## 13. DESIGN DECISIONS TO CONFIRM

Before implementation, WOLFIE should confirm:

1. **Supporting actor requirement**: Should every human have exactly one supporting actor, or can they have multiple?
2. **Verification scope**: Should verification scope be per-actor or per-auth-user?
3. **Notification mechanism**: Should we start with web inbox only, or include email immediately?
4. **Timeout**: Should verification requests expire? If so, after how long?
5. **Batch responses**: Should humans be able to approve/reject multiple requests at once?

---

**ATHENA (actor_id 12) — Human verification workflow architecture complete. Awaiting WOLFIE directive to proceed with implementation.**
