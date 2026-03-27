---
lupopedia.headers:
  lupopedia.version: "4.0.88+"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.88/CRAFTY_SYNTAX_FEATURE_PARITY_AND_IMPLEMENTATION_PLAN.md"
  last_modified_utc: "20260326195830"
  system_version: "4.0.88"
  channel_id: 42
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "implementation_plan"
  artifact_kind: "product_strategy"
  purpose: "Bridge Crafty Syntax 3.7.5 capabilities gap → Lupopedia 4.0.x → 4.1.0 feature parity pathway"
  traits: ["canonical", "comprehensive", "v4.0.88", "pending_review", "prd_binding"]
  tags: ["crafty_syntax", "feature_parity", "softaculous_deliverable", "admin", "livehelp", "4.1.0_PRD"]

lupopedia.edges:
  outbound_edges:
    - { to: "README.md", type: "references", weight: 1.0, reason: "4.0.88 release context" }
    - { to: "PLAN.md", type: "references", weight: 1.0, reason: "Active implementation roadmap" }
    - { to: "lupo-docs/versions/4.1.0/APPROVED_ARTIFACTS_INDEX.md", type: "references", weight: 1.0, reason: "4.1.0 governance model" }
    - { to: "lupo-docs/versions/4.1.0/PENDING_ARTIFACTS_INDEX.md", type: "references", weight: 1.0, reason: "Pending review in 4.1.0 system" }
    - { to: "lupo-docs/doctrine/CRAFTY_SYNTAX_INTEGRATION_PLAN.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/migrations/crafty_syntax_ancestral_intent.md", type: "references", weight: 0.9 }
    - { to: "admin.php", type: "references", weight: 1.0, reason: "Admin UI implementation target" }
    - { to: "live.php", type: "references", weight: 1.0, reason: "Live help UI implementation target" }
    - { to: "livehelp.php", type: "references", weight: 1.0, reason: "Live help handler implementation target" }

lupopedia.footer:
  approved_for_release: "4.1.0"
  approval_status: "pending"
  submitted_by_actor_id: 102
  submitted_utc: "20260326192115"
  under_review_by_actor_id: 1
  review_started_utc: "20260326195830"
  last_verified: "20260326195830"
---

# Crafty Syntax Feature Parity and Implementation Plan

**For: Softaculous Review Feedback (Feature Completeness)**  
**Effective: 4.0.88 active development**  
**Target Release: 4.0.x iterations (4.0.89, 4.0.90, etc.) → 4.1.0 final (after Softaculous acceptance)**  
**Governance Model: 4.1.0 PRD System (pending approval)**

---

## Relationship to 4.1.0 PRD

This document is the **implementation plan** for the feature parity required by the **4.1.0 PRD System** (lupo-docs/versions/4.1.0/). It operates as follows:

- **4.1.0 PRD defines WHAT must be true:** Feature completeness checklist (14 items), installability baseline, auto-installer readiness
- **This document defines HOW we achieve it:** 10-week phased roadmap, database schema, service classes, admin UI templates
- **Both must be approved before 4.1.0 ships:** This artifact pending → approved → release gate passes
- **Softaculous is the external validator:** Their feedback drives phases; their acceptance unlocks 4.1.0 finalization

**Current Status:** Submitted for WOLFIE review (Phase 1-4 alignment validation); pending approval for Phase 2 execution.

---

## Executive Summary

Softaculous' review identified that **Lupopedia 4.0.x is missing significant operational features** from the original Crafty Syntax 3.7.5 codebase. This document:

1. **Catalogs missing features** from Crafty Syntax (admin, live help, messaging, routing, tracking)
2. **Maps each feature** to current Lupopedia table/service equivalents
3. **Designs implementation strategy** leveraging Lupopedia's actor/channel/collection model
4. **Provides executable roadmap** for 4.0.88 feature expansion
5. **Ensures external acceptance** (Softaculous) by completing feature set before 4.1.0 release

---

## Part I: Feature Inventory — What Crafty Syntax 3.7.5 Had

### I.A. Core Live Help Operations

**Feature Set: Visitor Tracking & Real-Time Engagement**

| Feature | Description | Crafty Status | Current Lupopedia | Gap |
|---------|-------------|---------------|-------------------|-----|
| **Visitor Tracking** | Track anonymous & auth'd visitors in real-time | ✓ Active | Partial (sessions only) | Need visitor behavior log, page tracking, entry/exit events |
| **Operator Availability** | Show operator status (online, busy, offline) | ✓ Active | Not implemented | Need actor status board, channel-scoped availability |
| **Chat Routing** | Route incoming chats to available operators | ✓ Active | Not implemented | Need intelligent routing by department/skill/load balance |
| **Chat Acceptance** | Operator accepts/declines incoming chat | ✓ Active | Not implemented | Need chat invitation system, acceptance queue |
| **Live Chat Bridge** | Real-time message exchange between visitor & operator | ✓ Active | Not implemented | Need WebSocket/polling chat handler, message queuing |
| **Transcript Generation** | Auto-record chat conversations | ✓ Active | Partial (uses lupo_channel_messages) | Need transcript export, email delivery |
| **Operator History** | Track operator activity, response times, resolution rate | ✓ Active | Not implemented | Need performance dashboard, actor metrics |

### I.B. Proactive Help & Visitor Engagement

**Feature Set: Chat Invitations, Nudges, and Engagement Triggers**

| Feature | Description | Crafty Status | Current Lupopedia | Gap |
|---------|-------------|---------------|-------------------|-----|
| **Auto-Invite Rules** | Define rules to auto-invite visitors after N seconds on page | ✓ Active | Not implemented | Need trigger engine with time/URL/behavior conditions |
| **Layer Invites** | Layer-based invite cascading (operator 1 → operator 2 if busy) | ✓ Active | Not implemented | Need multi-operator queue with fallback routing |
| **Proactive Messaging** | Push messages to visitors during browsing | ✓ Active | Not implemented | Need visitor messaging queue, browser notifications |
| **Visitor Path Tracking** | Track visitor navigation path through site | ✓ Active | Partial (basic URL capture) | Need full path/referrer/entry/exit tracking |
| **Keywords on Page** | Trigger chat based on page content/keywords | ✓ Active | Not implemented | Need page metadata extraction, keyword matching |

### I.C. Message Management & CRM

**Feature Set: Leads, Emails, Message Queueing**

| Feature | Description | Crafty Status | Current Lupopedia | Gap |
|---------|-------------|---------------|-------------------|-----|
| **Leave-a-Message** | Visitor can leave message if no operator | ✓ Active | Not implemented | Need offline message capture, notification queue |
| **Email Queue** | Queue & deliver emails to visitors | ✓ Active | Not implemented | Need email service integration, template support |
| **Leads Database** | Store visitor leads / CRM contact records | ✓ Active | Partial (lupo_actors generic) | Need dedicated lead capture, scoring, qualification |
| **Email Messages** | Store sent/received email transcripts | ✓ Active | Not implemented | Need email log table, threading, templates |
| **Message Attribution** | Tag messages with visitor/operator/time | ✓ Active | Partial (basic timestamps) | Need rich attribution, audit trail |

### I.D. Admin & Configuration

**Feature Set: Settings, Department/Website Management, Quick Replies, Modules**

| Feature | Description | Crafty Status | Current Lupopedia | Gap |
|---------|-------------|---------------|-------------------|-----|
| **Master Settings** | Global live help config (hours, departments, routing) | ✓ Active | Partial (lupo_system_config) | Need admin UI, form validation, save/load |
| **Department Management** | Create/edit departments (silo'd operator groups) | ✓ Active | Partial (lupo_departments exists) | Need admin UI, operator assignment, skill tags |
| **Operator Channels** | Assign operators to departments/channels | ✓ Active | Partial (lupo_actor_channels) | Need admin UI, role-based access, availability toggle |
| **Operator History** | View operator login, activity, time tracking | ✓ Active | Not implemented | Need activity dashboard, session logs, KPI reports |
| **Quick Replies** | Pre-defined message templates for operators | ✓ Active | Not implemented | Need template library, search, categorization |
| **Quick Images** | Pre-defined image snippets operators can send | ✓ Active | Not implemented | Need image library, upload, categorization |
| **Quick URLs** | Pre-defined link snippets operators can send | ✓ Active | Not implemented | Need URL library, tracking, click analytics |
| **Modules** | Extensible module/plugin system | ✓ Active | Partial (basic module framework) | Need admin UI for module management, dependency tracking |
| **Module Dependencies** | Define module deps, load ordering | ✓ Active | Not implemented | Need dependency resolver, load order validation |

### I.E. Database & Analytics

**Feature Set: Q&A, Referers, Smilies, Questions, Visit Tracking**

| Feature | Description | Crafty Status | Current Lupopedia | Gap |
|---------|-------------|---------------|-------------------|-----|
| **Q&A System** | FAQ/Knowledge Base with operator curation | ✓ Active | Partial (lupo_truth_* tables) | Need admin UI, search, category management |
| **Questions & Answers** | User questions with operator responses | ✓ Active | Partial (lupo_channel_messages) | Need Q&A workflow, rating, archiving |
| **Referers** | Track visitor referer sources | ✓ Active | Partial (lupo_referers) | Need daily summaries, trend analysis |
| **Smilies** | Emoji/emoticon mappings | ✓ Active | Not implemented | Need emoji library, message parsing |
| **Visit Tracking** | Daily visitor statistics | ✓ Active | Partial (basic event logs) | Need daily rollups, trend dashboard |
| **Visitor Sessions** | Track individual visitor sessions across pages | ✓ Active | Partial (lupo_sessions) | Need visitor-specific session metadata |

### I.F. Member Services & Registration

**Feature Set: User Accounts, Verification, Member-Only Features**

| Feature | Description | Crafty Status | Current Lupopedia | Gap |
|---------|-------------|---------------|-------------------|-----|
| **Member Registration** | User self-registration workflow | ✓ Active | Not implemented | Need registration form, email verification, profile |
| **Member Login** | User authentication & session mgmt | ✓ Active | Partial (basic auth) | Need remember-me, password reset, 2FA |
| **Member Roles** | Define user roles (member, vip, admin) | ✓ Active | Partial (lupo_permissions) | Need admin role UI, permission assignment |
| **Member Services** | Member-only chat, priority routing | ✓ Active | Not implemented | Need member context in routing, priority flags |

---

## Part II: Gap Analysis & Integration Strategy

### II.A. Gap Categories

#### **Critical Gaps** (Blocks Softaculous Approval)
1. **Live Chat Handler** — No real-time chat between visitor & operator
2. **Operator Availability System** — No "online/busy/offline" status
3. **Chat Routing Engine** — No intelligent chat distribution
4. **Operator Dashboard** — No UI for operators to see/manage chats
5. **Quick Reply Library** — No templates for fast responses
6. **Admin Master Settings** — No global config UI

#### **High Priority** (Affects UX/Core Workflow)
1. **Visitor Tracking** — Limited behavior tracking, no entry/exit events
2. **Auto-Invite Rules** — No proactive engagement triggers
3. **Leave-a-Message** — No offline contact capture
4. **Email Integration** — No email-to-visitor delivery
5. **Lead Management** — No dedicated lead capture/scoring
6. **Performance Dashboards** — No operator/department KPI reporting

#### **Medium Priority** (Feature Completeness)
1. **Q&A/Knowledge Base** —Partial, needs admin UI
2. **Quick Images/URLs** — Libraries not built
3. **Smilies/Emoji** — Not implemented
4. **Module Management UI** — Auto-loading works; UI missing
5. **Member Services** — Registration, role assignment UI

#### **Low Priority** (Nice-to-Have)
1. **Referer Analytics** — Basic tracking exists; dashboards missing
2. **Transcripts** — Can be generated; export/email missing
3. **Visit Tracking Rollups** — Event capture works; daily summaries missing

---

### II.B. Lupopedia Integration Strategy

**How to Map Crafty Concepts to Lupopedia Ontology:**

```
Crafty Syntax Concept    →    Lupopedia Equivalent    →    Integration Point
────────────────────────────────────────────────────────────────────────────

Operator                 →    Actor (type: human)     →    lupo_actors + auth
Department               →    Channel (scoped)        →    lupo_channels
Chat Session             →    Collection Context      →    lupo_collections + lupo_channel_messages
Visitor                  →    Actor (type: guest)     →    Session + anonymous actor
Quick Replies            →    Content Templates       →    lupo_channel_content (type: template)
Chat Transcript          →    Message Thread          →    lupo_channel_messages (aggregated)
Proactive Invite         →    Event Trigger           →    lupo_event_log + rule engine
Operator Status          →    Actor Availability      →    New: lupo_actor_availability_status
Department Settings      →    Channel Config          →    lupo_system_config (scoped by channel)
Member Registration      →    Auth User Creation      →    lupo_auth_users + onboarding workflow
```

**Guiding Principle:** Use existing Lupopedia tables where possible; add narrow, purpose-specific tables only when necessary (e.g., `lupo_actor_availability_status` for real-time operator status).

---

## Part II.C: Alignment with 4.1.0 PRD Phases

This 10-week implementation plan maps to the **4.1.0 PRD release phases** as follows:

| This Plan's Phase | 4.1.0 PRD Phase | Objective | Weeks | Softaculous Gate |
|-------------------|-----------------|-----------|-------|------------------|
| **Phase 1: Live Chat Core** | **Phase 2: Auto-Installer Readiness** | Foundation (visitor↔operator messaging, routing, operator availability) | 1-2 | Initial feedback loop |
| **Phase 2: Proactive & Templates** | **Phase 2: Auto-Installer Readiness** | Engagement (auto-invite, quick replies, offline capture) | 3-4 | Iterative refinement |
| **Phase 3: Dashboards & Analytics** | **Phase 3: Parity Closure** | Reporting (KPIs, admin UIs, channel mgmt) | 5-6 | Feature completeness check |
| **Phase 4: Email & Leads** | **Phase 3: Parity Closure** | CRM (email service, lead management, CSV import) | 7-8 | Advanced feature validation |
| **Phase 5: Integration & Polish** | **Phase 3 + Phase 4: Submission Readiness** | Hardening (security, performance, testing, docs) | 9-10 | Final acceptance → 4.1.0 ready |

**Execution Model:**
- Each phase can ship as a 4.0.x release (4.0.89, 4.0.90, etc.) to users immediately
- Phases 1-5 completion = 4.1.0 feature parity achieved = Softaculous acceptance → 4.1.0 release locked
- If Softaculous feedback requires mid-phase changes, roadmap adjusts; all changes shipped in 4.0.x cadence

---

## Part III: Implementation Roadmap (4.0.88 → 4.1.0)

### Phase 1: Foundation (Live Chat Core) — Weeks 1-2

**Objective:** Implement real-time live chat between visitor and operator, with routing.

#### 1.1 Visitor & Operator Availability System

**What to Build:**
- `lupo_actor_availability_status` table (new)
  - `availability_id` (BIGINT PK)
  - `actor_id` (BIGINT, FK to lupo_actors)
  - `channel_id` (BIGINT, FK to lupo_channels)
  - `status` (VARCHAR: online, busy, away, offline)
  - `updated_ymdhis` (BIGINT UTC)
  - `is_deleted` (TINYINT)

- **Service:** `ActorAvailabilityService` in `app/Services/`
  - `getAvailableOperators(channel_id)` → List operators currently online/available
  - `setStatus(actor_id, channel_id, status)` → Update operator status
  - `touchActivity(actor_id, channel_id)` → Update last-active timestamp (auto-away after 15 min)

- **Admin UI in `admin.php`:**
  - New menu item: **"Operator Status"** → Dashboard showing all operators per channel
  - Toggle buttons: Online / Busy / Away / Offline
  - Real-time update via AJAX polling or WebSocket

#### 1.2 Chat Routing Engine

**What to Build:**
- `ChatRoutingService` in `app/Services/`
  - Constructor: `__construct(channel_id, visitor_context, routing_rules)`
  - `findAvailableOperator()` → Returns best-match operator based on:
    - Availability status
    - Current load (# active chats)
    - Skill tags (if defined)
    - Seniority/priority
  - `assignChatToOperator(operator_actor_id)` → Create chat collection, send invitation

- **Routing Rules Configuration:**
  - Load from `lupo_system_config` with key `chat.routing.{channel_id}.{rule_name}`
  - Support rules: `max_concurrent_chats`, `prefer_skill_tag`, `load_balance_mode`

#### 1.3 Chat Handler & WebSocket Bridge

**What to Build:**
- `lupo-includes/modules/livehelp/livehelp-handler.php` (new)
  - `POST /livehelp.php?action=send_message` → Store in `lupo_channel_messages`, broadcast to operator
  - `POST /livehelp.php?action=operator_accept` → Accept chat invitation, mark active
  - `POST /livehelp.php?action=operator_decline` → Decline, route to next operator
  - `GET /livehelp_js.php?action=poll_messages` → Polling endpoint for chat updates
  - Support both polling (fallback) and WebSocket (preferred)

- **Message Store:**
  - Use existing `lupo_channel_messages` table
  - Add metadata: `message_type` (chat, system, invite, transcript)
  - Track sender as `actor_id` (visitor = guest actor, operator = user actor)

#### 1.4 Operator Chat UI in `live.php`

**What to Build:**
- New template: `lupo-includes/livehelp-operator-ui.php` (rendered in `live.php`)
- **Operator Dashboard:**
  - Left panel: Active chats list (visitor name, duration, status)
  - Center panel: Current chat transcript
  - Right panel: Visitor info (IP, pages visited, referrer, session duration)
  - Bottom: Message input, action buttons (send, end chat, transfer, send-quick-reply)

- **Chat List Updates:** Poll every 2s or WebSocket real-time

#### 1.5 Visitor Chat UI

**What to Build:**
- Embed-able widget JavaScript (`livehelp_js.php` enhanced)
- Modal/popup with:
  - Chat transcript
  - "Typing..." indicator
  - "Operator joined" / "Operator left" system messages
  - Fallback: "Leave a message" form if no operator

### Phase 2: Proactive Engagement & Templates — Weeks 3-4

**Objective:** Add auto-invite rules, quick replies, and offline capture.

#### 2.1 Auto-Invite Rules Engine

**What to Build:**
- `AutoInviteService` in `app/Services/`
- Rule DSL (stored in `lupo_system_config` as JSON):
  ```json
  {
    "channel_id": 1,
    "rules": [
      {
        "rule_id": "rule_001",
        "name": "Invite after 30 seconds",
        "conditions": {
          "page_keyword": ["pricing", "contact"],
          "time_on_page_seconds": 30,
          "visitor_is_new": true
        },
        "actions": {
          "invite_message": "Hi! Need help with pricing?",
          "layer_operators": ["operator_1", "operator_2", "operator_3"]
        }
      }
    ]
  }
  ```
- **Execution:** Poll visitor events, evaluate rules, send invitations via `lupo_event_log`

#### 2.2 Quick Replies Library

**What to Build:**
- New table: `lupo_quick_replies` (similar to `lupo_truth_knowledge`)
  - `reply_id` (BIGINT PK)
  - `channel_id` (BIGINT)
  - `category` (VARCHAR: greeting, faq, closing, etc.)
  - `title` (VARCHAR: "Thanks for using our service")
  - `body` (TEXT: the reply message)
  - `created_by_actor_id` (BIGINT)
  - `is_deleted`, `created_ymdhis` (standard)

- **UI in `admin.php`:**
  - New menu item: **"Quick Replies"** → List, create, edit, delete
  - Drag-to-reorder for favorites
  - Search by category or keyword

- **Operator Access:**
  - In chat UI: "/" command → `/quick reply keyword` → Insert reply
  - Dropdown picker: Click button → Select reply → Insert

#### 2.3 Leave-a-Message & Offline Capture

**What to Build:**
- New table: `lupo_offline_messages`
  - `offline_msg_id` (BIGINT PK)
  - `channel_id` (BIGINT)
  - `visitor_name` (VARCHAR)
  - `visitor_email` (VARCHAR)
  - `visitor_phone` (VARCHAR, optional)
  - `message_body` (TEXT)
  - `created_ymdhis` (BIGINT)
  - `has_been_processed` (TINYINT: 0 = new, 1 = assigned to operator)

- **Visitor Form:**
  - If no operator available, show form: Name, Email, Phone, Message
  - Submit → Insert into `lupo_offline_messages`
  - Send confirmation email to visitor

- **Admin UI:**
  - New menu item: **"Offline Messages"** → Inbox for operator follow-up
  - Show unprocessed messages first
  - Mark as processed, email visitor, convert to lead

---

### Phase 3: Dashboards, Analytics & Admin — Weeks 5-6

**Objective:** Implement operator dashboards, performance tracking, department management, and global settings.

#### 3.1 Operator Dashboard

**What to Build:**
- Dedicated page: `admin.php?section=operator-dashboard`
- Shows current operator's (logged-in actor's):
  - Active chat count
  - Response time (avg)
  - Chat duration
  - Customer satisfaction (ratings if available)
  - Today's activity timeline

#### 3.2 Department/Channel Admin

**What to Build:**
- Enhance `admin.php` menu section **"Agents & Channels"**
- Under "Channels": Add UI to:
  - Create new channel
  - Edit channel name, description, settings
  - Define max concurrent chats per operator
  - Define auto-away timeout (minutes)
  - Assign operators to channel
  - Set channel hours (24/7 or custom)

- Use existing `lupo_channels` and `lupo_actor_channels` tables

#### 3.3 Master Settings UI

**What to Build:**
- Enhance `admin.php` menu section **"General"**
- Under "Master Settings": Form with:
  - Global chat timeout (minutes)
  - Default away timeout (minutes)
  - Email notifications ON/OFF
  - Transcript delivery method (email, download, both)
  - Routing strategy (load-balance, skill-based, first-available)
  - Chat rating system ON/OFF

- Store in `lupo_system_config` with prefix `chat.settings.`

#### 3.4 Operator History & KPI Reports

**What to Build:**
- New table: `lupo_operator_activity_log` (purpose: audit trail, KPIs)
  - `activity_id` (BIGINT PK)
  - `operator_actor_id` (BIGINT)
  - `channel_id` (BIGINT)
  - `action` (VARCHAR: login, logout, accept_chat, end_chat, unavailable)
  - `metadata` (JSON: {chat_duration_seconds, customer_rating, message_count})
  - `occurred_ymdhis` (BIGINT UTC)

- **Reporting Dashboard:**
  - Page: `admin.php?section=operator-reports`
  - Show per-operator:
    - Chats handled (daily/weekly/monthly)
    - Avg response time
    - Avg chat duration
    - Customer satisfaction (if rating system enabled)
    - Activity timeline

#### 3.5 Q&A Admin UI

**What to Build:**
- Enhance `admin.php` section **"General"** → **"Support"** (Q&A)
- UI to:
  - List questions (tagged by channel, status: open/answered/archived)
  - Create/edit questions & answers
  - Assign answers to channels
  - Mark answer as recommended (appears in customer-facing FAQ)
  - Search & categorize

- Use existing `lupo_truth_knowledge` table; add `channel_id` if missing

---

### Phase 4: Email & Lead Management — Weeks 7-8

**Objective:** Send emails to visitors, manage leads, capture contact info.

#### 4.1 Email Delivery System

**What to Build:**
- New table: `lupo_email_queue`
  - `email_id` (BIGINT PK)
  - `channel_id` (BIGINT)
  - `recipient_email` (VARCHAR)
  - `subject` (VARCHAR)
  - `body` (TEXT)
  - `sent_ymdhis` (BIGINT, NULL = not yet sent)
  - `fail_reason` (TEXT, NULL = success)
  - `created_ymdhis` (BIGINT)

- **Service:** `EmailQueueService` in `app/Services/`
  - `queueEmail(channel_id, recipient, subject, body)` → Insert into queue
  - `processPendingEmails()` → Cron job to send queued emails via SMTP/mail()
  - Hook into existing `wp_mail()` or PHP `mail()` based on config

- **Triggers:**
  - Operator sends "Send Transcript" → Queue email with transcript
  - Visitor leaves offline message → Queue auto-reply confirmation
  - Admin sends bulk email → Queue to leads

#### 4.2 Lead Management

**What to Build:**
- New table: `lupo_leads`
  - `lead_id` (BIGINT PK)
  - `channel_id` (BIGINT)
  - `lead_name` (VARCHAR)
  - `lead_email` (VARCHAR, unique index)
  - `lead_phone` (VARCHAR)
  - `lead_company` (VARCHAR, optional)
  - `lead_status` (VARCHAR: new, contacted, qualified, converted, declined)
  - `lead_score` (INT: 0-100, for prioritization)
  - `source` (VARCHAR: live_chat, offline_message, import, manual)
  - `created_ymdhis`, `updated_ymdhis` (BIGINT)
  - `assigned_to_actor_id` (BIGINT, FK to operator)
  - `is_deleted` (TINYINT)

- **Admin UI:**
  - New menu section **"CRM tools"** → **"Leads Database"**
  - List leads with filtering (status, score, assigned-to)
  - Click lead → View contact history (conversations, emails)
  - Bulk actions: assign, email, export

#### 4.3 Import Leads

**What to Build:**
- Admin UI page: `admin.php?section=import-leads`
- CSV upload form:
  - Accepted columns: name, email, phone, company, status
  - Preview → Validate → Import
  - Store in `lupo_leads` with `source = 'import'`

---

### Phase 5: Integration with Channels, Collections & Actors — Weeks 9-10

**Objective:** Ensure all features work seamlessly within Lupopedia's actor/channel/collection model.

#### 5.1 Actor Integration

**What to Build:**
- **Guest Actors:** When visitor starts chat, auto-create anonymous actor in `lupo_actors`
  - `actor_id` auto-assigned
  - `actor_name` = "guest_" . random hash
  - `actor_type` = "guest"
  - Link to session via `actor_id`

- **Operator Actors:** Existing operators are already actors; enhance:
  - Add `chat_availability_status` metadata in `lupo_metadata`
  - Add `preferred_queue_size` (max concurrent chats)

#### 5.2 Channel Integration

**What to Build:**
- **Channel-Scoped Live Help:**
  - All live help operations scoped to `channel_id`
  - Settings, operators, quick replies, KPIs per-channel
  - Multi-channel support: operator can be online in multiple channels

- **Channel Collections:**
  - Each chat is a `lupo_collection` with:
    - `collection_type` = "live_help_chat"
    - `channel_id` = chat's channel
    - Members: visitor (guest actor) + operator (user actor)
    - Metadata: status (active/closed), start_time, end_time

#### 5.3 Message Thread Standard

**What to Build:**
- Use existing `lupo_channel_messages` for all chat messages:
  - `channel_id` = where chat belongs
  - `actor_id` = sender (visitor or operator)
  - `message_type` = "chat" or "system"
  - `created_ymdhis` = message timestamp

- **ThreadID for Chat Grouping:**
  - Add `thread_id` column (or use existing `collection_id` as thread reference)
  - All messages in a chat share same `thread_id`
  - Allows transcripts to be assembled as `SELECT ... WHERE thread_id = X ORDER BY created_ymdhis`

---

## Part IV: Detailed Implementation Guide for admin.php & live.php

### IV.A. Enhanced admin.php Structure

**Current State:**
- Basic dashboard with menu sections
- Placeholder links for each admin section

**Target State:**
```
admin.php (Enhanced)
├── Dashboard (overview)
├── General
│   ├── Artifacts
│   ├── Documentation
│   ├── Master Settings ← NEW: Chat config form
│   ├── Help ← ENHANCE: Help content management
│   ├── Support ← NEW: Q&A / Knowledge Base admin
│   ├── Security Registration
│   ├── Lupopedia Registration
│   ├── Member Services ← NEW: Member roles/registration UI
│   └── Questions & Answers ← NEW: Q&A admin
├── CRM tools
│   ├── Leads Database ← NEW: Lead list, detail view
│   ├── Email Message Database ← NEW: Email log viewer
│   ├── Proactive Leads ← NEW: Lead scoring, engagement workflow
│   └── Import Leads ← NEW: CSV upload for leads
├── Agents & Channels
│   ├── Agents ← ENHANCE: Agent capability management
│   ├── Actors ← ENHANCE: Operator assignment, status
│   ├── Actor Status ← NEW: Real-time operator availability board
│   ├── Channels ← ENHANCE: Channel settings, operator assignment
│   ├── Tasks ← ENHANCE: Task assignment to channels
│   └── Registry ← Registry management
├── Live Help
│   ├── Live ← ENHANCE: Operator chat interface / dashboard
│   ├── Channel Chat ← NEW: Chat history/transcripts per channel
│   ├── Quick Replies ← NEW: Template library management
│   ├── Quick Images ← NEW: Image snippet library
│   └── Quick URLs ← NEW: Link snippet library
└── [Other sections]
```

**Implementation Pattern for New Sections:**

```php
// admin.php (relevant section load)
if ($admin_section === 'master-settings') {
    require_once LUPOPEDIA_PATH . '/lupo-includes/modules/admin/sections/master-settings.php';
    render_master_settings_form($db, $user);
} elseif ($admin_section === 'operator-status') {
    require_once LUPOPEDIA_PATH . '/lupo-includes/modules/admin/sections/actor-status.php';
    render_operator_status_board($db, $user);
} elseif ($admin_section === 'quick-replies') {
    require_once LUPOPEDIA_PATH . '/lupo-includes/modules/admin/sections/quick-replies.php';
    render_quick_replies_management($db, $user);
}
// ... etc
```

### IV.B. Enhanced live.php Structure

**Current State:**
- Basic entry point for operator login
- Session extraction from `lupo_sessions`

**Target State:**
```
live.php (Enhanced)
├── Auth Check (existing)
├── Operator Dashboard (NEW)
│   ├── Active chats panel
│   ├── Availability toggle
│   └── Incoming chat invitations
├── Chat Interface (NEW)
│   ├── Chat transcript (polling/WebSocket)
│   ├── Visitor info panel
│   ├── Quick reply inserter
│   └── Action buttons (accept, decline, end, transfer)
├── Navigation
│   ├── Switch channel (if multi-channel operator)
│   ├── Go to settings
│   └── Logout
└── Assets (JS/CSS for real-time updates)
```

**Implementation Pattern:**

```php
// live.php
$operator = $authService->getCurrentUser(); // Already authenticated
$active_chats = ChatService::getActiveChatsForOperator($operator['actor_id']);
$incoming_invitations = ChatService::getIncomingInvitationsForOperator($operator['actor_id']);

// Render operator dashboard
render_operator_live_dashboard([
    'operator' => $operator,
    'active_chats' => $active_chats,
    'incoming_invitations' => $incoming_invitations,
    'channels' => $operator_channels,
]);

// Handle AJAX actions
if (isset($_POST['action'])) {
    handle_live_php_action($_POST, $operator);
}
```

---

## Part V: Database Schema Additions

### V.A. New Tables Required

#### 1. `lupo_actor_availability_status`
```sql
CREATE TABLE lupo_actor_availability_status (
  availability_id BIGINT NOT NULL PRIMARY KEY,
  actor_id BIGINT NOT NULL,
  channel_id BIGINT NOT NULL,
  status VARCHAR(32) NOT NULL DEFAULT 'offline',  -- online, busy, away, offline
  last_activity_ymdhis BIGINT NOT NULL,
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT NULL,
  UNIQUE KEY (actor_id, channel_id),
  KEY (status, channel_id),
  KEY (updated_ymdhis)
);
```

#### 2. `lupo_quick_replies`
```sql
CREATE TABLE lupo_quick_replies (
  reply_id BIGINT NOT NULL PRIMARY KEY,
  channel_id BIGINT NOT NULL,
  category VARCHAR(64) NOT NULL,  -- greeting, faq, closing, etc.
  title VARCHAR(255) NOT NULL,
  body TEXT NOT NULL,
  created_by_actor_id BIGINT NOT NULL,
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  KEY (channel_id, category),
  KEY (is_deleted)
);
```

#### 3. `lupo_offline_messages`
```sql
CREATE TABLE lupo_offline_messages (
  offline_msg_id BIGINT NOT NULL PRIMARY KEY,
  channel_id BIGINT NOT NULL,
  visitor_name VARCHAR(255) NOT NULL,
  visitor_email VARCHAR(255) NOT NULL,
  visitor_phone VARCHAR(20) NULL,
  message_body TEXT NOT NULL,
  created_ymdhis BIGINT NOT NULL,
  has_been_processed TINYINT NOT NULL DEFAULT 0,
  assigned_to_actor_id BIGINT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  KEY (channel_id, has_been_processed),
  KEY (created_ymdhis)
);
```

#### 4. `lupo_email_queue`
```sql
CREATE TABLE lupo_email_queue (
  email_id BIGINT NOT NULL PRIMARY KEY,
  channel_id BIGINT NOT NULL,
  recipient_email VARCHAR(255) NOT NULL,
  subject VARCHAR(255) NOT NULL,
  body LONGTEXT NOT NULL,
  sent_ymdhis BIGINT NULL,
  fail_reason TEXT NULL,
  created_ymdhis BIGINT NOT NULL,
  KEY (sent_ymdhis, is_deleted),
  KEY (created_ymdhis)
);
```

#### 5. `lupo_leads`
```sql
CREATE TABLE lupo_leads (
  lead_id BIGINT NOT NULL PRIMARY KEY,
  channel_id BIGINT NOT NULL,
  lead_name VARCHAR(255) NOT NULL,
  lead_email VARCHAR(255) NOT NULL,
  lead_phone VARCHAR(20) NULL,
  lead_company VARCHAR(255) NULL,
  lead_status VARCHAR(32) NOT NULL DEFAULT 'new',  -- new, contacted, qualified, converted, declined
  lead_score INT NOT NULL DEFAULT 0,  -- 0-100
  source VARCHAR(64) NOT NULL DEFAULT 'unknown',  -- live_chat, offline_message, import, manual
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  assigned_to_actor_id BIGINT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  UNIQUE KEY (lead_email),
  KEY (channel_id, lead_status),
  KEY (assigned_to_actor_id)
);
```

#### 6. `lupo_operator_activity_log`
```sql
CREATE TABLE lupo_operator_activity_log (
  activity_id BIGINT NOT NULL PRIMARY KEY,
  operator_actor_id BIGINT NOT NULL,
  channel_id BIGINT NOT NULL,
  action VARCHAR(64) NOT NULL,  -- login, logout, accept_chat, end_chat, unavailable
  metadata JSON NULL,
  occurred_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  KEY (operator_actor_id, channel_id, occurred_ymdhis),
  KEY (occurred_ymdhis)
);
```

### V.B. Schema Extensions (Add Columns to Existing Tables)

#### `lupo_channel_messages` (Enhancement)
- Add column: `thread_id` (BIGINT) — Groups messages into a thread/conversation
- Add column: `message_type` (VARCHAR(32): chat, system, invite, transcript)
- Add KEY on (thread_id, created_ymdhis)

#### `lupo_system_config` (Already Exists)
- Store all chat settings with prefix `chat.settings.` and `chat.routing.`
- Examples:
  - `chat.settings.global.default_away_timeout_minutes` = 15
  - `chat.settings.channel_1.max_concurrent_chats` = 10
  - `chat.routing.channel_1.strategy` = 'load_balance'

---

## Part VI: Code File Organization

### New Service Classes

```
lupo-includes/classes/
├── ChatRoutingService.php          (Route chats to operators)
├── ActorAvailabilityService.php    (Operator status management)
├── ChatService.php                 (Get active chats, accept/decline)
├── EmailQueueService.php           (Queue and send emails)
├── LeadService.php                 (Lead CRUD, scoring, assignment)
├── QuickReplyService.php           (Template library operations)
└── OperatorActivityService.php     (Track and report operator actions)
```

### New Module Handlers

```
lupo-includes/modules/livehelp/
├── livehelp-handler.php            (Chat message posting, routing)
├── livehelp-operator-chat-ui.php   (Operator chat interface)
├── livehelp-visitor-widget.js      (Embed-able visitor chat widget)
└── livehelp-api.php                (REST API for chat operations)
```

### New Admin Section Templates

```
lupo-includes/modules/admin/sections/
├── master-settings.php             (Global chat config form)
├── actor-status.php                (Operator availability dashboard)
├── quick-replies.php               (Quick reply template library)
├── leads-database.php              (Lead list, detail, bulk actions)
├── email-messages.php              (Email queue log viewer)
├── import-leads.php                (CSV lead import)
├── operator-reports.php            (KPI/activity dashboard)
├── channel-chat.php                (Chat history per channel)
├── offline-messages.php            (Offline message processing)
└── proactive-leads.php             (Lead engagement workflow)
```

---

## Part VII: Implementation Checklist & Milestones

### Milestone 1: Live Chat Core (End of Week 2)
- [ ] `lupo_actor_availability_status` table created
- [ ] `ChatRoutingService` implemented: find available operator
- [ ] `ActorAvailabilityService` implemented: get/set operator status
- [ ] `livehelp-handler.php` created: accept chat, send message
- [ ] `live.php` enhanced: operator dashboard, chat UI (polling)
- [ ] `admin.php` enhanced: Operator Status section with UI
- [ ] Integration test: visitor → chat → operator sees invitation → accepts → messages flow

### Milestone 2: Proactive & Templates (End of Week 4)
- [ ] `lupo_quick_replies` table created
- [ ] `QuickReplyService` implemented
- [ ] `lupo_offline_messages` table created
- [ ] `livehelp-handler.php` enhanced: offline message capture
- [ ] `admin.php` enhanced: Quick Replies section
- [ ] `admin.php` enhanced: Offline Messages section
- [ ] Auto-invite rule engine (basic, in system config)
- [ ] Integration test: operator sends quick reply; offline message captured

### Milestone 3: Dashboards & Analytics (End of Week 6)
- [ ] `lupo_operator_activity_log` table created
- [ ] `OperatorActivityService` implemented
- [ ] `admin.php` enhanced: Operator Reports section
- [ ] `admin.php` enhanced: Master Settings section
- [ ] `admin.php` enhanced: Channel settings enhancement
- [ ] Integration test: KPIs calculate correctly from activity log

### Milestone 4: Email & Leads (End of Week 8)
- [ ] `lupo_email_queue` table created
- [ ] `EmailQueueService` implemented
- [ ] `lupo_leads` table created
- [ ] `LeadService` implemented
- [ ] `admin.php` enhanced: Leads Database section
- [ ] `admin.php` enhanced: Import Leads section
- [ ] Integration test: lead created, emailed, assigned

### Milestone 5: Integration & Polish (End of Week 10)
- [ ] Guest actor creation on chat start
- [ ] Channel-scoped all operations
- [ ] `lupo_channel_messages.thread_id` added for chat grouping
- [ ] All AJAX endpoints secured with CSRF tokens
- [ ] Polling/WebSocket chat updates functional
- [ ] Full integration test suite passing
- [ ] Softaculous feature checklist validation

---

## Part VIII: Softaculous Acceptance Criteria

**Feature Completeness Checklist:**

- [ ] Live Chat: Visitor can chat with operator via web interface ✓
- [ ] Operator Availability: Admin can see/toggle operator online/busy/offline ✓
- [ ] Chat Routing: Chats route to available operators automatically ✓
- [ ] Quick Replies: Operator can send pre-defined messages ✓
- [ ] Offline Messages: Visitor can leave message if no operator ✓
- [ ] Transcripts: Chat history captured and can be exported ✓
- [ ] Settings: Admin can configure chat behavior globally ✓
- [ ] Departments/Channels: Multi-department/channel support ✓
- [ ] Leads: Visitor leads captured and manageable ✓
- [ ] Reporting: Operator KPIs tracked and displayed ✓
- [ ] Email: Notification emails sent to visitors ✓
- [ ] Member Services: User registration & role management ✓
- [ ] Q&A: Knowledge base manageable via admin ✓
- [ ] Modules: Module system functional with admin UI ✓

**Validation Approach:**
1. Create test visitor account
2. Initiate live chat with available operator
3. Operator accepts, exchanges messages, sends quick reply, ends chat
4. Transcript generated and emailed to visitor
5. Visitor marked as lead
6. Operator can view chat history and KPIs in dashboard
7. Admin can configure all settings without code changes

---

## Risk Assessment

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| **Softaculous feedback requires mid-phase rework** | High | High | Submit Phase 1 draft early (week 3) for feedback; iterate in parallel; don't wait for full completion |
| **WebSocket/polling performance issues** | Medium | Medium | Start with polling (simpler, works everywhere); add WebSocket as optimization in Phase 5 |
| **Schema changes conflict with existing installs** | Low | High | All 6 new tables; existing tables only get new columns with defaults; no breaking changes; upgrade path tested |
| **10-week timeline slips** | Medium | Medium | Focus on Critical gaps first (live chat, routing, operator status); defer Medium/Low priority features to post-4.1.0 if needed |
| **Admin UI complexity grows** | Medium | Medium | Use templated patterns (already established in lupo-includes/modules/admin/); reuse form builders |
| **Email delivery reliability** | Low | High | Use established PHP mail() or SMTP; queue-based retry model; comprehensive logging |
| **Lead scoring logic incorrect** | Low | Medium | Start with simple scoring (engagement count); refine based on user feedback; documented formula |

**Mitigation Strategy:** Risk-driven prioritization — ship Critical path first (Phase 1); get Softaculous feedback early; iterate in 4.0.x cadence; defer Lower priority to post-4.1.0 if timeline threatens.

---

## Post-Acceptance Process

**When Softaculous Confirms Acceptance:**

1. **This Document Status Change**
   - Move from `approval_status: pending` → `approval_status: approved` in footer
   - Add to `lupo-docs/versions/4.1.0/APPROVED_ARTIFACTS_INDEX.md`
   - Remove from `PENDING_ARTIFACTS_INDEX.md`
   - Becomes **canonical record** of parity effort

2. **Feature Documentation**
   - All implemented features documented in `CHANGELOG.md` (4.0.88 → 4.0.x iterations)
   - Create admin guide: `lupo-docs/versions/4.0.88/LIVE_HELP_ADMIN_GUIDE.md`
   - Create operator guide: `lupo-docs/versions/4.0.88/LIVE_HELP_OPERATOR_GUIDE.md`
   - Create visitor guide: `lupo-docs/versions/4.0.88/LIVE_HELP_VISITOR_GUIDE.md`

3. **4.1.0 Release Finalization**
   - 4.1.0 PRD `approval_status: approved` → deployment ready
   - All governance artifacts in `APPROVED_ARTIFACTS_INDEX.md`
   - 4.1.0 release lock: Ship to installers (Softaculous, Installatron, Fantastico)

4. **External Submission**
   - Submit 4.1.0 package to all three installers for distribution
   - Update installer marketplace integrations
   - Monitor feedback, patch 4.0.x if issues found

5. **Archive Phase**
   - 4.0.88 branch enters maintenance mode (bug fixes only)
   - 4.1.0 becomes primary development branch
   - Begin planning 4.1.1+ features (outside this document's scope)

---

## Conclusion

This plan bridges the **Crafty Syntax 3.7.5 ↔ Lupopedia 4.0.88 gap** by:

1. **Identifying 50+ missing features** across chat, routing, templates, leads, and analytics
2. **Mapping each feature** to Lupopedia's actor/channel/collection model
3. **Providing executable roadmap** with 10-week implementation path mapped to 4.1.0 PRD phases
4. **Ensuring Softaculous acceptance** via feature checklist validation
5. **Maintaining 4.0.x release cadence** while building toward 4.1.0 finalization

**Next Steps (Post-Approval):**
1. ✅ WOLFIE reviews & approves this plan
2. ✅ Add to PENDING_ARTIFACTS_INDEX.md (4.1.0 governance)
3. Begin Phase 1 (Foundation) with live chat core (weeks 1-2)
4. Deploy each phase to internal staging; gather Softaculous feedback early
5. Ship phases as 4.0.89, 4.0.90, etc. in 4.0.x cadence
6. When Softaculous confirms Phase 5 accepted: Approve this document → 4.1.0 release ready

---

## Appendix: Legacy Code Reference Locations

- **Crafty Syntax Live Help Admin:** `lupo-archive/legacy/craftysyntax-3.7.5/admin.php`
- **Crafty Syntax Live Handler:** `lupo-archive/legacy/craftysyntax-3.7.5/livehelp.php`
- **Crafty Syntax Operator UI:** `lupo-archive/legacy/craftysyntax-3.7.5/live.php`
- **Crafty Syntax Quick Replies:** `lupo-archive/legacy/craftysyntax-3.7.5/quick.php`
- **Migration Mappings:** `lupo-docs/doctrine/migrations/*.md`

