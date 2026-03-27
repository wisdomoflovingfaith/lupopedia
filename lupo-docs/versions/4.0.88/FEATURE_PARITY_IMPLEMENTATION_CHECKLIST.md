---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  file_path_from_root: "lupo-docs/versions/4.0.88/FEATURE_PARITY_IMPLEMENTATION_CHECKLIST.md"
  last_modified_utc: "20260326192115"
  system_version: "4.0.88"
  channel_id: 42
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: "tracking"
  artifact_kind: "implementation_checklist"
  purpose: "Weekly task tracking for Crafty Syntax feature parity build in 4.0.88"
  traits: ["actionable", "executable", "v4.0.88"]
  
lupopedia.edges:
  outbound_edges:
    - { to: "CRAFTY_SYNTAX_FEATURE_PARITY_AND_IMPLEMENTATION_PLAN.md", type: "implements", weight: 1.0 }
    - { to: "PLAN.md", type: "references", weight: 1.0 }

lupopedia.footer:
  approved_for_release: "4.0.88+"
  approval_status: "active"
---

# Feature Parity Implementation Checklist

**Status: Starting Phase 1**  
**Current Week: 1**  
**Target Completion: Week 10 (2026-05-15)**

---

## PHASE 1: Foundation (Weeks 1-2) — LIVE CHAT CORE

### Week 1: Database & Services

**Task 1.1.1: Create lupo_actor_availability_status Table**
- [ ] Write DDL in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- [ ] Write TOON reference: `lupo-database/lupopedia/toon/lupo_actor_availability_status.toon.json`
- [ ] Write table doc: `lupo-docs/database/lupopedia/tables/active/lupo_actor_availability_status.md`
- [ ] Verify with: `python lupo-scripts/verify_db_against_toons.py`
- **Owner:** [assign]  |  **ETA:** Day 1
- **Status:** ⬜ Not Started

**Task 1.1.2: Implement ActorAvailabilityService**
- [ ] Create `lupo-includes/classes/ActorAvailabilityService.php`
- [ ] Method: `getStatusForOperator(actor_id, channel_id)` → VARCHAR status
- [ ] Method: `setStatus(actor_id, channel_id, status)` → void
- [ ] Method: `getAvailableOperators(channel_id)` → Array of actor_ids
- [ ] Method: `touchActivity(actor_id, channel_id)` → update last_activity_ymdhis
- [ ] Unit tests in `lupo-tests/unit/actor_availability_service.php`
- **Owner:** [assign]  |  **ETA:** Day 1-2
- **Status:** ⬜ Not Started

**Task 1.1.3: Implement ChatRoutingService**
- [ ] Create `lupo-includes/classes/ChatRoutingService.php`
- [ ] Constructor: `__construct(channel_id, routing_rules = null)`
- [ ] Method: `findAvailableOperator()` → actor_id or false
- [ ] Routing logic: load-balance by current chat count
- [ ] Unit tests in `lupo-tests/unit/chat_routing_service.php`
- **Owner:** [assign]  |  **ETA:** Day 2-3
- **Status:** ⬜ Not Started

### Week 1: Handler & UI Layer

**Task 1.1.4: Implement ChatService**
- [ ] Create `lupo-includes/classes/ChatService.php`
- [ ] Method: `acceptChat(operator_actor_id, chat_collection_id)`
- [ ] Method: `declineChat(operator_actor_id, chat_collection_id)`
- [ ] Method: `getActiveChatsForOperator(actor_id)` → Array
- [ ] Method: `getIncomingInvitationsForOperator(actor_id)` → Array
- [ ] Method: `endChat(chat_collection_id, reason = 'operator_ended')`
- [ ] Unit tests in `lupo-tests/unit/chat_service.php`
- **Owner:** [assign]  |  **ETA:** Day 3-4
- **Status:** ⬜ Not Started

**Task 1.1.5: Create livehelp-handler.php**
- [ ] Create `lupo-includes/modules/livehelp/livehelp-handler.php`
- [ ] Endpoint: `POST /livehelp.php?action=send_message`
  - Accept: `{channel_id, message_body, visitor_actor_id}`
  - Store in `lupo_channel_messages`
  - Trigger operator notification
- [ ] Endpoint: `POST /livehelp.php?action=operator_accept`
  - Accept: `{operator_actor_id, chat_id}`
  - Call `ChatService::acceptChat()`
- [ ] Endpoint: `POST /livehelp.php?action=operator_decline`
  - Accept: `{operator_actor_id, chat_id}`
  - Call `ChatService::declineChat()`, trigger reroute
- [ ] Endpoint: `GET /livehelp.php?action=poll_messages&chat_id=X`
  - Return messages since last poll (polling endpoint)
- [ ] CSRF protection on all POST endpoints
- **Owner:** [assign]  |  **ETA:** Day 4-5
- **Status:** ⬜ Not Started

### Week 2: Operator UI & Integration

**Task 1.2.1: Create Operator Dashboard Template**
- [ ] Create `lupo-includes/templates/livehelp-operator-dashboard.php`
- [ ] Left panel: List of active chats (visitor name, duration, unread count)
- [ ] Center panel: Current chat transcript (scrollable)
- [ ] Right panel: Visitor info (IP, pages visited, referrer)
- [ ] Bottom: Message input + Send button
- [ ] CSS styling in `lupo-includes/css/livehelp-operator.css`
- [ ] JavaScript AJAX for message polling in `lupo-includes/js/livehelp-operator.js`
- **Owner:** [assign]  |  **ETA:** Day 1-3
- **Status:** ⬜ Not Started

**Task 1.2.2: Enhance live.php**
- [ ] Load operator dashboard template
- [ ] Extract current operator (logged-in user)
- [ ] Fetch active chats: `$chats = ChatService::getActiveChatsForOperator($operator['actor_id'])`
- [ ] Fetch incoming invitations: `$invites = ChatService::getIncomingInvitationsForOperator(...)`
- [ ] Pass to template: `render_operator_live_dashboard(['chats' => $chats, 'invites' => $invites])`
- [ ] Handle AJAX POST actions (send message, accept, decline, end)
- **Owner:** [assign]  |  **ETA:** Day 2-3
- **Status:** ⬜ Not Started

**Task 1.2.3: Add "Operator Status" Section to admin.php**
- [ ] Create `lupo-includes/modules/admin/sections/actor-status.php`
- [ ] Fetch all operators in current channel: `ActorAvailabilityService::getAvailableOperators($channel_id)`
- [ ] Display table: Operator Name | Current Status Dropdown | Update | Last Activity
- [ ] AJAX status toggle: Click dropdown → `POST /admin.php?section=actor-status&action=set_status`
- [ ] Service handles: `ActorAvailabilityService::setStatus(actor_id, channel_id, new_status)`
- [ ] Styling + icons for each status
- **Owner:** [assign]  |  **ETA:** Day 3-4
- **Status:** ⬜ Not Started

**Task 1.2.4: Integration Testing — Live Chat Flow**
- [ ] Test scenario: Visitor initiates chat → Chat routed to operator → Operator sees invitation → Accept → Chat messages flow both directions → End chat
- [ ] Visitor simulator in `lupo-tests/integration/test_live_chat_flow.sh`
- [ ] Verify message order in `lupo_channel_messages`
- [ ] Verify operator status transitions
- **Owner:** [assign]  |  **ETA:** Day 4-5
- **Status:** ⬜ Not Started

**Week 1-2 Daily Standup Template:**
```
Date: [YYYY-MM-DD]
Completed:
- [Task ID]: [Result]

In Progress:
- [Task ID]: [Blocker or % complete]

Planned for Tomorrow:
- [Task ID]
- [Task ID]

Risks / Blockers:
- [Issue] → [Mitigation]
```

---

## PHASE 2: Proactive & Templates (Weeks 3-4)

### Week 3: Quick Replies & Offline Messages

**Task 2.1.1: Create lupo_quick_replies Table**
- [ ] Write DDL in install SQL
- [ ] Write TOON
- [ ] Index: (channel_id, category), (is_deleted)

**Task 2.1.2: Implement QuickReplyService**
- [ ] Create `lupo-includes/classes/QuickReplyService.php`
- [ ] Method: `getCategories(channel_id)` → Array
- [ ] Method: `getReplysByCategory(channel_id, category)` → Array
- [ ] Method: `createReply(channel_id, category, title, body, created_by_actor_id)` → reply_id
- [ ] Method: `updateReply(reply_id, updates)` → void
- [ ] Method: `deleteReply(reply_id)` → void

**Task 2.1.3: Create lupo_offline_messages Table**
- [ ] Write DDL in install SQL
- [ ] Write TOON
- [ ] Index: (channel_id, has_been_processed), (created_ymdhis)

**Task 2.1.4: Enhance livehelp-handler.php for Offline Messages**
- [ ] New endpoint: `POST /livehelp.php?action=submit_offline_message`
  - Accept: `{channel_id, visitor_name, visitor_email, visitor_phone, message_body}`
  - Validate email (required)
  - Insert into `lupo_offline_messages`
  - Queue auto-reply email
- [ ] Validate input, CSRF protect

**Task 2.1.5: Add "Quick Replies" Admin Section**
- [ ] Create `lupo-includes/modules/admin/sections/quick-replies.php`
- [ ] UI: List of categories (collapsible)
- [ ] Each category shows replies (title, body preview)
- [ ] Actions: Edit, Delete, Reorder
- [ ] Form: Create new reply (category dropdown, title, body textarea)
- [ ] Use `QuickReplyService` for CRUD

**Task 2.1.6: Add "Offline Messages" Admin Section**
- [ ] Create `lupo-includes/modules/admin/sections/offline-messages.php`
- [ ] List unprocessed messages (FIFO, newest first)
- [ ] Click message → Show details: name, email, phone, message, timestamp
- [ ] Actions: Mark processed, Email visitor, Convert to lead, Delete

### Week 4: Auto-Invite Rules Engine

**Task 2.2.1: Implement AutoInviteService**
- [ ] Create `lupo-includes/classes/AutoInviteService.php`
- [ ] Load rules from `lupo_system_config` (key: `chat.autoinvite.{channel_id}.rules`, JSON)
- [ ] Method: `evaluateRulesForVisitor(visitor_session, page_context)` → Array of rule matches
- [ ] Helper: `parseConditions(condition_obj)` → callable predicate
- [ ] Helper: `executeActions(actions_obj, visitor)` → send invites

**Task 2.2.2: Add Rule Configuration UI**
- [ ] Extend `master-settings.php` with "Auto-Invite Rules" section
- [ ] JSON editor or form builder for rules (if form builder, build it)
- [ ] Preview: Show which pages/conditions match sample visitor
- [ ] Save → Store in `lupo_system_config`

**Task 2.2.3: Integrate Auto-Invite into Visitor Widget**
- [ ] Enhance `livehelp_js.php` JavaScript
- [ ] On page load: Call `/livehelp.php?action=check_auto_invite_rules`
- [ ] Service evaluates rules, returns invitation if matched
- [ ] Show modal/popup with invitation message to visitor

**Task 2.2.4: Test Auto-Invite Flow**
- [ ] Test scenario: Visitor lands on "pricing" page → Wait 30s → Invitation appears → Click → Chat starts

---

## PHASE 3: Dashboards & Admin (Weeks 5-6)

### Week 5: Analytics & Settings

**Task 3.1.1: Create lupo_operator_activity_log Table**
- [ ] Write DDL, TOON, docs

**Task 3.1.2: Implement OperatorActivityService**
- [ ] Create `lupo-includes/classes/OperatorActivityService.php`
- [ ] Method: `logActivity(operator_actor_id, channel_id, action, metadata = null)`
- [ ] Method: `getActivityForOperator(operator_actor_id, channel_id, date_range)` → Array
- [ ] Method: `calculateKPIs(operator_actor_id, date_range)` → {chats_handled, avg_response_time, avg_duration, satisfaction_rating}

**Task 3.1.3: Add "Master Settings" Admin Section**
- [ ] Create `lupo-includes/modules/admin/sections/master-settings.php`
- [ ] Form with fields:
  - Global chat timeout (dropdown: 5, 10, 15, 20 minutes)
  - Auto-away timeout (minutes)
  - Email notifications (checkbox)
  - Transcript delivery (dropdown: email, download, both)
  - Routing strategy (dropdown: load-balance, skill-based, first-available)
  - Chat rating system (enable/disable)
- [ ] Save to `lupo_system_config`

**Task 3.1.4: Add "Operator Reports" Admin Section**
- [ ] Create `lupo-includes/modules/admin/sections/operator-reports.php`
- [ ] Table: Operator Name | Chats Handled | Avg Response Time | Avg Duration | Rating | Activity Timeline
- [ ] Filter by: Date range, channel, operator
- [ ] Click operator → Drill-down dashboard with hourly breakdown

### Week 6: Channel & Q&A Admin

**Task 3.2.1: Enhance Channel Management**
- [ ] Create `lupo-includes/modules/admin/sections/channel-settings.php`
- [ ] List all channels (table: Name, Online Operators Count, Active Chats, Status)
- [ ] Click channel → Channel settings form:
  - Channel name, description
  - Max concurrent chats per operator
  - Auto-away timeout override
  - Channel hours (24/7 or custom with time range)
  - Assign operators (multi-select)
  - Save → Update `lupo_channels` + `lupo_actor_channels`

**Task 3.2.2: Add "Q&A / Knowledge Base" Admin Section**
- [ ] Create `lupo-includes/modules/admin/sections/qa-management.php`
- [ ] List questions/answers from `lupo_truth_knowledge`
- [ ] Filter: All / Answered / Unanswered, by channel, by category
- [ ] Actions: Edit, Delete, Mark as FAQ, Archive
- [ ] Create form: Question, Answer, Category, Channel(s), FAQ flag

**Task 3.2.3: Add "Operator Dashboard" (Non-Admin, for Operators)**
- [ ] Create `lupo-includes/templates/operator-personal-dashboard.php`
- [ ] Show current operator's own KPIs: Chats today, Avg response time, Rating, Time logged in
- [ ] Quick links: View my history, Download my transcript
- [ ] Accessible from dropdown or `/live_operatordashboard.php`

---

## PHASE 4: Email & Leads (Weeks 7-8)

### Week 7: Email Queue System

**Task 4.1.1: Create lupo_email_queue & lupo_leads Tables**
- [ ] Write DDL, TOONs, docs for both

**Task 4.1.2: Implement EmailQueueService**
- [ ] Create `lupo-includes/classes/EmailQueueService.php`
- [ ] Method: `queueEmail(channel_id, recipient_email, subject, body)` → email_id
- [ ] Method: `processPendingEmails()` → count of emails sent
  - Use PHP `mail()` or SMTP (check config)
  - Set `sent_ymdhis` on success
  - Log fail_reason on failure
- [ ] Cron hook: `/lupo-bin/cron-email-queue.php` to be called by system cron

**Task 4.1.3: Add "Email Message Database" Admin Section**
- [ ] Create `lupo-includes/modules/admin/sections/email-messages.php`
- [ ] List emails: Recipient | Subject | Sent Date | Status (sent/failed)
- [ ] Filter: All / Failed / Pending / By recipient
- [ ] Click email → View full body, retry button (if failed)

### Week 8: Lead Management

**Task 4.2.1: Implement LeadService**
- [ ] Create `lupo-includes/classes/LeadService.php`
- [ ] Method: `createLead(channel_id, name, email, phone, company, source)` → lead_id
- [ ] Method: `updateLead(lead_id, updates)` → void
- [ ] Method: `getLeads(channel_id, filters = {})` → Array
- [ ] Method: `scoreLeads()` → Recalc lead_score based on engagement
- [ ] Method: `assignLead(lead_id, operator_actor_id)` → void

**Task 4.2.2: Add "Leads Database" Admin Section**
- [ ] Create `lupo-includes/modules/admin/sections/leads-database.php`
- [ ] List leads: Name | Email | Phone | Company | Status | Score | Assigned To | Created Date
- [ ] Filter: Status (new, contacted, qualified, converted, declined), Score >=, Assigned To
- [ ] Click lead → Detail view:
  - Edit form (name, email, phone, company, status, score, assign-to)
  - Conversation history (all interactions with this lead)
  - Email buttons (send email, send transcript, etc.)
  - Actions: Save, Email, Convert to member, Archive

**Task 4.2.3: Add "Import Leads" Admin Section**
- [ ] Create `lupo-includes/modules/admin/sections/import-leads.php`
- [ ] CSV upload form (accept columns: name, email, phone, company, status)
- [ ] Preview uploaded data (table)
- [ ] Validate emails
- [ ] Confirm import → Insert into `lupo_leads`

**Task 4.2.4: Hook Offline Messages → Leads**
- [ ] When offline message submitted: Auto-create lead (email must be provided)
- [ ] Source = 'offline_message'
- [ ] Assign to next-available operator (from config)

---

## PHASE 5: Integration & Polish (Weeks 9-10)

### Week 9: Channel/Actor/Collection Integration

**Task 5.1.1: Guest Actor Creation on Chat Start**
- [ ] Enhance `/livehelp.php?action=start_chat` flow
- [ ] If visitor not logged in: Create guest actor
  - `actor_id` auto-assigned (via `DeterministicIdService`)
  - `actor_name` = 'guest_' . random_hash(8)
  - `actor_type` = 'guest'
  - Store in `lupo_actors`
- [ ] Link session to guest actor: `session.actor_id = guest_actor_id`

**Task 5.1.2: Create Chat as lupo_collection**
- [ ] When chat starts:
  - Create `lupo_collection` record
  - `collection_type` = 'live_help_chat'
  - `channel_id` = chat channel
  - Members: visitor guest actor + operator
  - Metadata: {chat_start, expected_operator, status: 'waiting_for_acceptance'}
- [ ] When operator accepts: Update metadata: {status: 'active', operator_joined_ymdhis}
- [ ] When chat ends: Update metadata: {status: 'closed', chat_end_ymdhis, duration_seconds}

**Task 5.1.3: Thread ID for Message Grouping**
- [ ] Add `lupo_channel_messages.thread_id` column (BIGINT, NULL initially)
- [ ] BackfillScript: Assign existing chat messages to collections (one-time migration)
- [ ] New messages: Always set `thread_id = collection_id` (or use collection as thread)
- [ ] Query pattern: `SELECT * FROM lupo_channel_messages WHERE thread_id = X ORDER BY created_ymdhis`

**Task 5.1.4: Scope All Operations by Channel**
- [ ] Audit admin sections: Ensure all queries filter by current channel
- [ ] Audit live help: Ensure all queries filter by current channel
- [ ] Add helper: `getCurrentChannelId()` in bootstrap
- [ ] Use in all service layers

### Week 10: Testing & Softaculos Readiness

**Task 5.2.1: Full Integration Test Suite**
- [ ] Create `lupo-tests/integration/test_complete_live_help_flow.sh`
- [ ] Scenario 1: Visitor → Chat with operator → Exchange messages → End → Transcript emailed
- [ ] Scenario 2: Offline message → Lead created → Admin emails lead → Lead converts to member
- [ ] Scenario 3: Auto-invite rule triggers → Visitor chats → Quick reply sent
- [ ] Scenario 4: Operator logs in → Sets status → Receives routed chat → KPIs tracked
- [ ] All scenarios must pass

**Task 5.2.2: Softaculous Feature Checklist Validation**
- [ ] Admin walk-through: Verify each feature in checklist is present & functional
- [ ] Operator walk-through: Verify chat flow, quick replies, status toggle
- [ ] Visitor walk-through: Chat widget, offline message, auto-invite
- [ ] Document: Screenshots + narrative for each feature
- [ ] Create validation report: `SOFTACULOUS_ACCEPTANCE_REPORT_4.0.88.md`

**Task 5.2.3: Documentation & Handoff**
- [ ] Create admin guide: `lupo-docs/versions/4.0.88/LIVE_HELP_ADMIN_GUIDE.md`
- [ ] Create operator guide: `lupo-docs/versions/4.0.88/LIVE_HELP_OPERATOR_GUIDE.md`
- [ ] Create visitor guide: `lupo-docs/versions/4.0.88/LIVE_HELP_VISITOR_GUIDE.md`
- [ ] Update CHANGELOG.md with all new features
- [ ] Update README.md: "Live Help feature parity achieved"

**Task 5.2.4: Bug Fixes & Performance**
- [ ] Security audit: CSRF tokens, SQL injection prevention, access control
- [ ] Performance: Validate polling endpoint (<200ms response), routing <500ms
- [ ] Browser compatibility: Test in Chrome, Firefox, Safari, Edge
- [ ] Mobile responsive: Ensure operator UI and visitor widget work on mobile
- [ ] Accessibility: Basic WCAG 2.1 Level A compliance (forms, labels, contrast)

---

## Tracking Template

Copy this table and update weekly:

| Phase | Week | Task | Status | Owner | Blocker | Notes |
|-------|------|------|--------|-------|---------|-------|
| 1 | 1 | 1.1.1 | ⬜ | - | - | - |
| 1 | 1 | 1.1.2 | ⬜ | - | - | - |
| 1 | 1 | 1.1.3 | ⬜ | - | - | - |
| 1 | 1 | 1.1.4 | ⬜ | - | - | - |
| 1 | 1 | 1.1.5 | ⬜ | - | - | - |
| 1 | 2 | 1.2.1 | ⬜ | - | - | - |
| 1 | 2 | 1.2.2 | ⬜ | - | - | - |
| 1 | 2 | 1.2.3 | ⬜ | - | - | - |
| 1 | 2 | 1.2.4 | ⬜ | - | - | - |

**Status Codes:**
- ⬜ Not Started
- 🟨 In Progress (50%)
- 🟩 Complete
- 🔴 Blocked (waiting on dependency)
- ⚫ Cancelled / Not Needed

---

## Success Criteria

**End of Week 10:**
- ✅ All 60+ tasks completed
- ✅ All integration tests passing
- ✅ Softaculous feature checklist: 14/14 items functional
- ✅ Admin/operator/visitor guides written
- ✅ Zero critical security issues
- ✅ Feature-complete Lupopedia ready for Softaculous re-review

