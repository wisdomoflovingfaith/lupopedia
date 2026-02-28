# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "plan_for_crafty_syntax.md"
  file_hash: "bfbd8877d0fe0514a766e4212fc05c8beedc337f114fdbbe9a1e651936bffb6e"
  file_path_from_root: "plan_for_crafty_syntax.md"
  file_hash: "432799669a9e901dcdf994a3b5aaca1bbdc68f006d8be2c2190853749b0e2fb6"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for plan_for_crafty_syntax.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["plan_for_crafty_syntaxmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.0
file.channel: doctrine
file.last_modified_utc: 20250204120000
file.name: "plan_for_crafty_syntax.md"
---

UPDATED DOCTRINE VERSION
Crafty Syntax → Lupopedia Migration Sprint (Cycles + Consecration)
System Version: 3.0.0

---

## Project context (current state)

This project assumes we are starting from **one of two entry points**:

1. **New install** — A fresh Lupopedia 4.x installation with no prior Crafty Syntax data.
2. **Upgrade from Crafty Syntax 3.7.5** — An existing Crafty Syntax Live Help site being migrated into Lupopedia.

The **SQL files we are concerned with updating** are those that define or alter the database for these two paths:

- **New install:**  
  - `database/migrations/install_new_lupopedia.sql` — canonical schema (CREATE TABLE, etc.).  
  - `database/migrations/seed_lupopedia.sql` — canonical seed data (unified registry, PK=0 rows, active agents as actors, TOON-defined rows, `ALTER TABLE lupo_actors AUTO_INCREMENT = 10000`). Run after `install_new_lupopedia.sql`.
- **Upgrade from 3.7.5:**  
  - Migration(s) that import or transform legacy Crafty Syntax data into Lupopedia tables (e.g. `craftysyntax_to_lupopedia_mysql.sql` or equivalent in `database/migrations/` or `database/migrations_legacy/`). These are **one-time migrations** that change the database; the **install SQL** (`install_new_lupopedia.sql`) is also updated as we make schema changes so that future new installs get the same structure.

**TOON files** (`docs/toons/*.toon.json`):

- Are **generated from the resulting database** (after install or after migrations), not written by hand.
- Are produced by the canonical script: `python scripts/generate_toon_files.py` (see `docs/channels/dev-teams/governance/GOV-TOON-GENERATION-001.md`).
- Serve as the **reference for column names and tables** for all application code, migrations, and seed generation. Code and SQL must align with TOON; TOON is the schema oracle.

**Migrations and install SQL:**

- **One-time migrations** are used to change the database (new columns, new tables, data transforms). After running a migration, the live database is updated; then TOONs are regenerated from that database.
- **Install SQL** (`install_new_lupopedia.sql`) is updated to reflect the same schema so that a brand‑new install produces the same structure. Seed data is regenerated via `python scripts/generate_seed_from_toons.py` and written to `database/migrations/seed_lupopedia.sql`.

**Themes and design:**

- **Theme support:** `lupo_federation_nodes` includes `active_theme_slug` (e.g. `default`) so each federation node can have a presentation theme. Added via one-time migration `dev_20260204_theme_support.sql` and then folded into the canonical install schema.
- **UI theatrical doctrine:** Crafty Syntax / Lupopedia UI is treated as **theater** (sets, props, layers, scenes) rather than generic layout. See `docs/channels/doctrine/legacy-import/CRAFTY_SYNTAX_UI_THEATRICAL_DOCTRINE.md` for the design philosophy (book UI, scroll templates, emotional UX, human-centered design). Module and operator settings (e.g. theme, colors) can live in metadata (e.g. `lupo_modules.config_json`) rather than fixed columns.

**Other relevant points:**

- **Subdirectory-only installation:** Lupopedia is always installed in a subdirectory (e.g. `/lupopedia/`). Paths and URLs use `LUPOPEDIA_PUBLIC_PATH` from `lupopedia-config.php`; never hardcode `/lupopedia/` or the folder name.
- **No foreign keys, no triggers:** Schema remains soft-reference and doctrine-aligned.
- **Timestamps:** BIGINT in `YYYYMMDDHHIISS` format.
- **Canonical project brief:** `docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md`.

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

## Phase 2 — Operator Cockpit & Unified Stream (NEXT)

### A. Operator-Side Message Stream Improvements
- **Unified Stream UI**: Implement the "interleaved by timestamp" view for all active channels.
- **Message Styling**: Add timestamps, alignment (visitor left/operator right), and colors based on thread context.
- **Unread Indicators**: Visual cues for new messages in non-active tabs.
- **Scroll Behavior**: "Stick to bottom" logic with "new message" floating action button if scrolled up.

### B. Typing Preview System
- **Operator Preview**: Operator sees what visitor is typing in real-time (Visitor → Operator).
- **Bubble UI**: Floating "UserIsTypingDiv" equivalent or inline preview in the specific thread tab.
- **Data Flow**: Hook up `VisitorPresenceAgent` to broadcast typing events to the operator channel.

### C. Sound & Alert System
- **Audio Triggers**:
  - New message (Visitor)
  - New message (Operator - confirmation)
  - Pending visitor arrival
  - Visitor reconnect/active
- **Controls**: Mute/Unmute toggle in Cockpit.
- **Interaction Policy**: Handle browser autoplay restrictions (interaction required).

### D. Multi-Chat Tabs
- **Tab Bar**: Dynamic tabs for each active thread (2–6 simultaneous chats).
- **State Management**: Active tab vs. Background tabs.
- **Notification Badges**: Count unread messages per tab.

---

## Phase 3 — Visitor Widget & Chat Experience

### A. Visitor Widget UI
- **Launcher**: Modernized floating bubble (replacing legacy icon).
- **Chat Window**:
  - Clean, single-page layout (no framesets).
  - Responsive design for mobile/desktop.
  - "Department Offline" state handling.

### B. Visitor Message Features
- **Message Bubbles**: Distinct styling for Operator vs. Visitor vs. System messages.
- **Typing Indicators**: "Operator is typing..." animation.
- **Sound Alerts**: Optional sound on operator reply.
- **Rich Text/Links**: Auto-linkify URLs, handle `[PUSH]` and `[transfer]` legacy tags.

---

## Phase 4 — Presence, Routing & Agents (HEADLESS)

### A. Agent Implementation
- **VisitorPresenceAgent**: Tracks idle time, navigation, and typing status.
- **OperatorPresenceAgent**: Manages availability (online/offline/away), max chat capacity, and active connection monitoring.
- **ChatRoutingAgent**:
  - Implements the "Lobby (Channel 1) → Private Channel" promotion logic.
  - auto-assigns operators based on routing rules.
  - Handles "Department" routing constraints.

### B. Routing Logic
- **Load Balancing**: "Least busy" or "Round Robin" assignment strategies.
- **Department Hours**: Respect open/close hours if defined.
- **Overflow Handling**: Queueing logic when all operators are busy.

---

## Phase 5 — Engagement & Invite Systems

### A. Auto-Invite System
- **SystemEventAgent**: Monitors visitor behavior against invite rules.
- **Rule Engine**: Check:
  - Time on site
  - Page views
  - Specific URL matches
  - Referrer patterns
- **Trigger**: Generates invite event when rules matched.

### B. Layer Invites (Anti-Popup)
- **UI Component**: Non-intrusive "Can we help you?" overlay.
- **Response Handling**:
  - Accept → Opens Chat (promotes to active session).
  - Decline → Suppresses future invites for session.
  - Ignore → Fade out.

---

## Phase 6 — Administration & Governance

### A. Admin Panel UI (`/crafty/admin`)
- **Departments**: Create/Edit/Delete departments, set hours, assign operators.
- **Operators**: Manage accounts, assign departments, set permissions.
- **Settings**: Global configuration (Legacy `config` table equivalent), theme selection.
- **Canned Responses**: Manage global and personal "Quick Notes" (Reply Templates).

### B. Governance & Monitoring
- **Live Board**: Real-time view of all departments and operator statuses.
- **Stewardship**: Tools for "Pono/Pilau" calibration (admin self-reflection UI).

---

## Phase 7 — Transcripts, Data & History

### A. Transcript System
- **TranscriptAgent**: Ensures all message streams are finalized and archived into `lupo_dialog_threads`.
- **Viewer UI**: `/crafty/transcript/{id}` for historical review.
- **Search**: Searchable archive of past chats.
- **Export**: PDF/HTML export and "Email Transcript" functionality.

### B. Legacy Data Integration
- **Import Verification**: Ensure imported transcripts from 3.7.5 render correctly in the new viewer.
- **Quirk Mapping**: Handle legacy bbCode/formatting in old transcripts.

---

## Phase 8 — Cleanup & Doctrine Consolidation

### A. Legacy Artifact Removal
- **Decommission**: Remove `legacy/craftysyntax/` reference files (once fully reimplemented).
- **Route Cleanup**: Remove any temporary bridges or scaffolding.
- **Code Audit**: Verify no raw SQL remains (all via standard models/agents).

### B. Documentation & Blessing
- **Final Docs**: Update `AGENTS.md`, Module README, and User Guides.
- **Consecration**: Final "Day 7" blessing of the integrated system.

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
