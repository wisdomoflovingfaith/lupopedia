---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.0.99/status/documentation_alignment_status.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.0.99/status/documentation_alignment_status.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: status
  artifact_kind: alignment_report
  channel_key: development
  federation_node_id: 0
  thread_key: documentation-alignment
  lupopedia.schema: documentation
  prd_cluster: null
  title: Documentation Alignment Status — 4.0.99
  summary: Tracks philosophy alignment across README_WTF.md, PRD 02, doctrine files, and the Crafty Syntax analysis. Records what was corrected, why, and what remains at risk.
---
# Documentation Alignment Status — Lupopedia 4.0.99

**Date:** 20260414
**Author:** Actor 116 (Claude Code)
**Trigger:** Gemini delta consolidation + root philosophy alignment pass

---

## Summary of Alignment — What Was Corrected and Why

### 1. Session Hash Formula (CRITICAL CORRECTION)

**Problem:** README_WTF.md Section 9 and the `.toon` file both stated the base session
identity hash included `actor_name` and `auth_user_name`. This was wrong and contradicted
the already-corrected code in `app/auth/Session.php::computeIdentityHash()`.

**Correction applied:**
- Base hash = SHA256(class_c_ip + user_id_or_unknown + user_agent + salt)
- `actor_id`, `auth_user_id`, `actor_name`, `auth_user_name` explicitly EXCLUDED
- Reason stated: pre-login visitors have no actor/auth identity; the base hash must be
  stable before and after login

**Files corrected:** README_WTF.md, readme-wtf-md.toon, SESSION_DOCTRINE.md, SESSION_MODEL.md

---

### 2. Transport Model (PREVIOUSLY ABSENT)

**Problem:** No top-level documentation stated the startup capability negotiation model,
one-way promotion, session lock-in, or the prohibition on runtime mode bouncing. These
were Crafty Syntax behavioral rules that were not propagated to canonical docs.

**Correction applied:**
- README_WTF.md Section 7b: Transport Model added with all five sub-rules
- PRD 02: "Transport Model Doctrine" section added before Anti-Patterns
- what_i_learned_from_crafty_syntax.md: Section 3.1 rewritten

**Core rules now documented:**
1. Startup capability negotiation (probe before commit)
2. Configured negotiation chain (not hardcoded)
3. One-way promotion (no downgrade after successful probe)
4. Session lock-in via chattype / session record
5. No runtime mode bouncing

---

### 3. Polling Cursor (CORRECTION)

**Problem:** The analysis document suggested `last_message_id` as an acceptable polling
cursor. This is incorrect. IdGenerator IDs have CSPRNG suffixes and are not
monotonically sequential. A `WHERE id > :last_id` query on concurrent inserts would miss
messages.

**Correction applied:**
- `after_ymdhis` (14-digit UTC BIGINT) is the ONLY canonical polling cursor
- PRD 02 Transport Model Doctrine now specifies this explicitly with SQL example
- what_i_learned_from_crafty_syntax.md Section 4.1 corrected
- README_WTF.md Section 7b states this

---

### 4. UI Philosophy (STRENGTHENED)

**Problem:** The command-center constraints existed in PRD 02 but were absent from
README_WTF.md. The `.toon` file had a generic `ui_design_doctrine` entry about
"9-slice scroll aesthetic" with no mention of the command-center prohibitions.

**Correction applied:**
- README_WTF.md Section 7: Full UI Philosophy section with forbidden/required table
- `.toon` entry 22 corrected to include command-center prohibitions
- PRD 02 Transport Model added to reinforce no-framework doctrine

---

### 5. Architecture Philosophy (NEW SECTION)

**Problem:** README_WTF.md had no consolidated "Architecture Philosophy" section. The
no-frameworks, no-ORM, no-middleware, explicit-SQL rules were scattered or implied.

**Correction applied:**
- README_WTF.md Section 6 added: Architecture Philosophy with comparison table
- Explicit statement: "Deterministic behavior is mandatory"

---

### 6. Survival Philosophy (NEW SECTION)

**Problem:** The "survival over fashion," ASCII-only mandate, and no-abstraction rule
were derived from Crafty Syntax analysis but not stated as canonical doctrine in README_WTF.md.

**Correction applied:**
- README_WTF.md Section 11 added: Survival Philosophy
- ASCII-safe data, DB=storage, no-abstraction rule, behavior > trends all stated explicitly
- what_i_learned_from_crafty_syntax.md Section 9.6 added

---

### 7. DHTML / Layer Model (NEW)

**Problem:** The DynAPI ancestry of `layers.js` and the API contract (`moveTo`,
`show`, `hide`) were undocumented.

**Correction applied:**
- what_i_learned_from_crafty_syntax.md Section 8 (DHTML / Layer Model) added
- Not yet in README_WTF.md or PRDs (covered by Crafty Syntax analysis; not needed
  at root philosophy level unless layer system enters active development)

---

## Files Updated

| File | Change |
|------|--------|
| `README_WTF.md` | Session hash corrected; Arch Philosophy (S6), UI Philosophy (S7), Transport Model (S7b), Survival Philosophy (S11) added; DB Philosophy section strengthened; Identity & Time Model section restructured |
| `memory/development/canonical/1026/04/readme-wtf-md.toon` | Entries 9 (session), 20 (command center), 22 (UI) corrected; entries for transport model, polling cursor, survival doctrine added |
| `docs/prd/02_channels_discussions.md` | Transport Model Doctrine section added (startup negotiation, one-way promotion, session lock-in, polling model with after_ymdhis, no-framework rule) |
| `docs/doctrine/SESSION_DOCTRINE.md` | Formula corrected to include user_id; CRITICAL exclusion block added for actor_id/auth_user_id; GPS future formulas updated |
| `docs/doctrine/SESSION_MODEL.md` | session_identity_hash column entry added with formula and MUST NOT note |
| `docs/versions/4.0.99/status/what_i_learned_from_crafty_syntax.md` | S3.1 rewritten (transport); S4.1 corrected (cursor); S8 added (DHTML); S9.5 added (UI doctrine); S9.6 added (survival doctrine); Delta Integration Notes added |
| `docs/versions/4.0.99/CHANGELOG.md` | Two entries appended (session hash correction + Gemini delta consolidation) |
| `docs/versions/4.0.99/status/documentation_alignment_status.md` | Created (this file) |

---

## Key Philosophy Reinforcements — Now Enforced

1. **Base session hash = class_c_ip + user_id_or_unknown + user_agent + salt ONLY**
   No actor_id. No auth_user_id. Stable for anonymous visitors.

2. **Transport negotiation = startup probe, one-way promotion, session lock-in**
   Not a static file pair. Not a runtime fallback chain.

3. **Polling cursor = after_ymdhis (14-digit UTC BIGINT)**
   Not last_message_id. CSPRNG-suffixed IDs are not monotonic.

4. **UI = single-column interleaved timeline**
   No bubbles. No grouping. No side panels. No tabs-per-agent. THOTH enforces.

5. **DB = storage only**
   No triggers, no FKs, no stored procedures. Logic lives in PHP.

6. **ASCII-safe data files**
   No emoji, no Unicode box-drawing in TOON/JSON/header files. Hostile hosting safety.

7. **No abstraction of proven simple logic**
   30 lines > 300-line service class with events and handlers. Specific > general.

8. **IdGenerator for all PKs. No AUTO_INCREMENT.**
   Federation nodes cannot share auto-increment sequences.

---

## Remaining Misalignment Risks

| Risk | Location | Severity |
|------|----------|----------|
| `channels/index.php` still does full page reload on POST — live feed not yet incremental | Runtime code | HIGH |
| `api/dialog/fetch-messages.php` does not yet exist — polling endpoint missing | Runtime code | HIGH |
| `.toon` file entry 9 (session) not yet updated to reflect corrected hash formula | Memory file | MEDIUM |
| `docs/prd/00_root_constitutional_system_requirements.md` may still reference old session hash formula | PRD | MEDIUM |
| DHTML/layers.js API contract not yet implemented (DynAPI era code still in craftysyntax-reference only) | Future code | LOW |
| Agent write-only rule may not be enforced at API level (any actor can call fetch-messages) | Runtime code | MEDIUM |

---

## Next Execution Targets — Concrete Code-Level Items

### Priority 1 — CRITICAL (blocks live feed)

1. **Create `api/dialog/fetch-messages.php`**
   - Accepts: `channel_id`, `thread_id`, `after_ymdhis`
   - Returns: JSON array of messages with `created_ymdhis > after_ymdhis`
   - Sorted: `ORDER BY created_ymdhis ASC`
   - Auth: same actor auth as post-message.php

2. **Refactor `channels/index.php` polling loop**
   - Remove full page reload on POST submit
   - Implement JS `pollMessages()` with `after_ymdhis` cursor
   - Initialize cursor from highest `created_ymdhis` in initial render
   - Append-only DOM updates; no re-render

### Priority 2 — IMPORTANT (correctness)

3. **Verify `app/auth/Session.php::computeIdentityHash()` call sites**
   - Confirm all callers pass `user_id` (not actor_id, not null when user_id is available)
   - Confirm `createEmbedSession` and `create` pass the correct user_id value

4. **Add `ping` health-check endpoint**
   - `GET /api/dialog/ping.php` returns `{"ok":true}`
   - Client uses to verify server before starting poll loop

### Priority 3 — DOCTRINE (compliance)

5. **Add DOM size guard to channels/index.php polling loop**
   - After 500 appended message lines, trigger page reload
   - Prevents unbounded DOM growth in long sessions
   - Matches Crafty `refreshes > 15` pattern

6. **Verify `lupo_sessions.session_identity_hash` is being written**
   - Check that `createEmbedSession` and `initOrLoad` actually persist the hash
   - Confirm SESSION_HASH_DEBUG logging is confirming correct field values in prod logs
