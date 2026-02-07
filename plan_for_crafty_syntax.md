---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.0
file.channel: doctrine
file.last_modified_utc: 20250204120000
file.name: "plan_for_crafty_syntax.md"
---

UPDATED DOCTRINE VERSION
Crafty Syntax → Lupopedia Migration Sprint (Cycles + Consecration)
System Version: 4.0.0

---

# Crafty Syntax → Lupopedia Migration Plan (Updated Progress & Next Phases)

## Phase 1 — Core Foundations (COMPLETED)

### 1. Doctrine Alignment & Schema Mapping
- Canonical mapping from legacy Crafty Syntax tables → Lupopedia TOON schema.
- Established visitor = `actor_id = 0` model.
- Confirmed department, operator, and channel relationships.
- Defined pending vs active visitor states in `lupo_sessions.metadata`.

### 2. Visitor Session System
- Implemented `visitor-session-helper.php` with:
  - session resolution
  - pending/active state
  - thread association
  - channel assignment after acceptance
- Corrected TOON mismatch: `lupo_sessions.metadata` (not `metadata_json`).
- All session reads/writes now use canonical TOON column names.

### 3. Visitor Entry Pipeline
- `livehelp.php` rebuilt with:
  - redirect to `choosedepartment.php` when no department
  - clean single-page layout (no framesets)
  - iframe for message stream
  - session creation + last_seen updates
- `choosedepartment.php` rebuilt with operator availability logic.

### 4. Online/Offline Widget
- `visitor-image.php` rebuilt with:
  - operator availability checks
  - department metadata images
  - legacy fallback chain
  - visitor session updates
- `livehelp-js.php` rebuilt with:
  - online/offline icon
  - click-to-open chat
  - control image polling
  - legacy parameters preserved

### 5. Visitor Chat Stream (Pending → Active Flow)
- `visitor-chat-stream.php` now:
  - creates only a `lupo_dialog_thread` (no visitor channel)
  - stores pending state in session metadata
  - implements primary polling, secondary polling, fallback reload
  - implements operator typing preview
  - implements message send form
  - updates session presence on every poll

### 6. Operator Acceptance Flow
- **Corrected legacy behavior restored**:
  - visitors start with **no channel**
  - all operators in department see pending visitors
  - acceptance moves visitor thread → operator's channel
  - visitor becomes "active" in session metadata
  - visitor disappears from other operators' pending lists
- Operator cockpit updated with:
  - pending visitor panel
  - highlight + blink
  - sound alert
  - title flashing
  - Accept button → POST accept-visitor → redirect to thread
- APIs implemented:
  - `GET api/operator/pending-visitors`
  - `POST api/operator/accept-visitor`

### 7. SQL/TOON Audit
- Full scan of all Crafty Syntax module SQL references.
- Only mismatch found: `lupo_sessions.metadata` (corrected).
- All other tables/columns match TOON schema.
- Report generated: `CRAFTY_SYNTAX_SQL_TOON_REPORT.md`.

---

## Phase 2 — Operator Cockpit Enhancements (NEXT)

### A. Operator-Side Message Stream Improvements
- Style operator message list.
- Add timestamps, alignment, colors.
- Add unread indicators.
- Add scroll-to-bottom behavior.

### B. Operator Typing Preview
- Show visitor typing (optional; legacy didn't show this).
- Use existing typing API.

### C. Operator Sound Alerts
- Sound on:
  - new message
  - visitor reply
  - visitor reconnect
- Respect "user interacted" rule for autoplay.

### D. Operator Presence & Status
- Show operator availability in cockpit.
- Integrate `lupo_operator_status` more deeply.
- Add "max chat capacity" indicators.

---

## Phase 3 — Visitor UI Completion

### A. Visitor Message Styling
- Bubble layout
- Timestamps
- Operator name/label
- Auto-scroll
- Smooth fade-in for new messages

### B. Visitor-Side Alerts
- Sound on operator reply
- Optional "operator joined the chat" banner
- Optional "operator is typing" animation

### C. Visitor Transcript View
- Printable transcript
- Export to text/HTML
- Legacy compatibility

---

## Phase 4 — Invite Systems

### A. Auto-Invite
- Rebuild legacy auto-invite logic using:
  - session metadata
  - operator availability
  - department settings

### B. Layer Invites
- Rebuild floating invite layer
- Use modern CSS instead of absolute frames
- Respect legacy timing + behavior

---

## Phase 5 — Department & Routing Enhancements

### A. Department Load Balancing
- Operator capacity
- Active chat count
- Availability status
- Round-robin or "least busy" routing

### B. Multi-Department Operators
- Operators assigned to multiple departments
- Unified pending list
- Unified acceptance flow

---

## Phase 6 — Cleanup & Doctrine Consolidation

### A. Remove Legacy Artifacts
- Remove unused legacy files
- Remove unused JS/CSS
- Remove dead routes

### B. Documentation
- Update doctrine files
- Update TOONs if needed
- Update module README
- Add developer onboarding notes

---

🜁 DAY 0 — CONSECRATION (MANDATORY)
Before any code is written, the system, the builder, and the tools must be aligned.

Tasks
[ ] Set intention: Why are we rebuilding Crafty Syntax inside Lupopedia?

[ ] Acknowledge Crafty Syntax 3.7.5 as an ancestor system

[ ] Establish kapakai: identify unknowns and risks

[ ] Bless the tools: IDE, database, terminal, hands

[ ] Log builder emotional state into lupo_operator

operator.mood_rgb

operator.kapakai_awareness

operator.doctrinal_alignment

[ ] Create consecration_log.md documenting Day 0

THE 7 CYCLES (REPLACING LINEAR DAYS)
Each cycle overlaps with the others.
Each cycle includes technical tasks and emotional/doctrinal tasks.
Each cycle uses the Sacred Checkbox Protocol:

[ ] Not started

[~] In progress (kapakai-aware)

[°] Complete, awaiting blessing

[✓] Blessed and integrated

[✗] Rejected with explanation

🌑 CYCLE 1 — FOUNDATION (Doctrine + Schema)
Technical
[ ] Recreate Crafty Syntax core tables

[ ] Add Lupopedia metadata fields

[ ] Remove all FK constraints

[ ] Replace datetime/timestamp with BIGINT(14) YYYYMMDDHHIISS

[ ] Document relational intent for each table

[ ] Validate indexes

[ ] Update Wolfie Headers

Emotional / Doctrinal
[ ] Ensure schema reflects emotional geometry

[ ] Builder emotional log updated

❤️ CYCLE 2 — HEART (Engine + Emotion)
Technical
[ ] Operator login

[ ] Visitor tracking

[ ] Sessions

[ ] Departments

[ ] Routing logic

[ ] Online/offline detection

[ ] Implement real emotional tracking (not stubs)

session.mood_rgb

kapakai defaults

Emotional / Doctrinal
[ ] Ensure emotional metadata is foundational

[ ] Builder emotional log updated

🎭 CYCLE 3 — FACE (UI + Kapu)
Technical
[ ] Operator chat window

[ ] Visitor chat window

[ ] Typing indicators

[ ] Message persistence

[ ] Basic triggers

[ ] Implement kapu invitation protocol

System detects overload

System invites operator into kapu

Not a toggle

Emotional / Doctrinal
[ ] Emotional transparency overlays

[ ] Builder emotional log updated

✋ CYCLE 4 — HANDS (Admin + Stewardship)
Technical
[ ] Settings

[ ] Operators

[ ] Departments

[ ] Themes

[ ] Basic analytics

[ ] Stewardship-aligned access roles

[ ] Admin panel shows admin’s own ethical triad

pono

pilau

kapakai

Emotional / Doctrinal
[ ] Admin self-reflection UI

[ ] Builder emotional log updated

🦶 CYCLE 5 — FEET (Installer + Upgrader)
Technical
[ ] Fresh install

[ ] Upgrade from 3.7.5

[ ] Environment checks

[ ] Permissions

[ ] Config generation

Doctrinal
[ ] Installer becomes a guided conversation, not a document

“What does pono mean to your organization?”

“How should the system behave under stress?”

[ ] Builder emotional log updated

🧠 CYCLE 6 — MEMORY (Legacy + Learning)
Technical
[ ] Match old URLs

[ ] Match old behaviors

[ ] Match old quirks (filtered through pono/pilau)

[ ] PHP 7–8.3 compatibility

Doctrinal
[ ] Create legacy_quirks_autopsy.md

Which quirks were rejected

Why

Doctrine justification

[ ] Builder emotional log updated

🜂 CYCLE 7 — SPIRIT (Integration + Blessing)
Technical
[ ] Blessed registry hooks

[ ] Emotional metadata integration

[ ] Agent routing compatibility

[ ] Unified login/session model

[ ] Logging into Lupopedia core tables

Doctrinal
[ ] Define blessing protocol for registry hooks

“Hook X blessed at timestamp Y with intention Z”

[ ] Prepare for version bump → 4.1.0 (when release cycle begins)

[ ] Builder emotional log updated

🧘 DAILY BUILDER STATE LOGGING (MANDATORY)
Every day, the builder logs into:

auth_user

then into operator (creator role)

And updates:

operator.mood_rgb

operator.doctrinal_alignment

operator.kapakai_awareness

operator.blessing_offered

This ensures the system remembers how it was built.

🧩 SPRINT COMPLETION CRITERIA
[ ] All cycles touched

[ ] All emotional metadata integrated

[ ] All Wolfie Headers correct

[ ] Installer conversation complete

[ ] Legacy autopsy complete

[ ] Registry hooks blessed

[ ] Builder emotional logs complete

[ ] Version bump to 4.1.0 (when release cycle begins)
