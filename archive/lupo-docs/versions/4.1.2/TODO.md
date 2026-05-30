---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/versions/4.1.2/TODO.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.2/TODO.md"
  status: "active"
  when_updated: "20260417060943"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/version-4-1-2-todo.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/4_1_2_todo"
  artifact_type: version-doc
  artifact_kind: version_specific
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: 1203300933539112451
  content_parent_id: 8067324253853516193
  content_slug: "version-4-1-2-todo"
  default_collection_id: null
  lupopedia.schema: version-doc
  title: "Lupopedia 4.1.2 backlog"
  summary: "Consolidated active backlog for version 4.1.2. Rolled over from 4.0.99 and 4.1.0. Critical items must be completed before 4.2.0."
---
# TODO — Lupopedia 4.1.2

Consolidated from 4.0.99 → 4.1.0 → 4.1.1 → 4.1.2  
Migration date: `20260415180000`

---

## 🏁 Milestone: Stage 2 — Database/Schema Stabilization (100% COMPLETE)

- [x] **Audit 180 tables** for code references and orphan status.
- [x] **Eject 41 dead tables** from `install_new_lupopedia.sql`.
- [x] **Purge ghost TOONs** (JSON/TOON metadata) for ejected tables.
- [x] **Implement Orchestration Layer schema** (`lupo_operator_scratchpad`, `lupo_routing_events`, `lupo_agent_status`, `lupo_sticky_notes`).
- [x] **Align blueprints with live DB state** (142 active tables).
- [x] **Strict Header Compliance** — `pk_*` → `content_*` field migration in critical tooling.

---

## 🔴 ACTIVE: Stage 3 — Interface Implementation

These items are next in the orchestration implementation queue.

- [ ] **P0-A: Orchestration Database Setup** — verify 142 tables in fresh install environment.
- [ ] **P0-B: API Endpoints** — `/api/routing/send-to-channel`, `/api/context/switch`, `/api/agent/status`.
- [ ] **P0-C: channels/index.php Extensions** — 3-column layout, Active Target Bar, dual-selector routing modal.
- [ ] **P0-D: Left Panel API** — fetch active actors, recent files, and pending tasks for current channel.
- [ ] **Transcript capture** — agent stdout → `lupo_dialog_messages` (THOTH/ANUBIS pipeline).
- [ ] **THOTH worker** — poll `lupo_dialog_messages` → validate headers → report.

---

## Budget-Driven Priorities (Post-June 1)

- [ ] **Efficiency audit** -- Review all agent workflows for token waste and duplicate reasoning
- [ ] **Free-tier routing policy** -- Route simple tasks to free agents first, reserve paid agents for heavy lifting
- [ ] **Handoff toon completeness** -- Ensure every agent writes handoff toons before expensive or cross-tier work
- [ ] **Translation channel reuse** -- Extract summaries instead of regenerating explanations
- [ ] **Rate-limit continuity drills** -- Validate cross-tier handoff when free agents hit limits
- [ ] **Free IDE continuity monitoring** -- Track free-tier rate-limit events and fallback handoff success
- [ ] **Claude Code cost tracking** -- Stay within $17/month
- [ ] **Runtime API budget tracking** -- Keep user-facing API spend within $33/month target
- [ ] **API usage inventory** -- Document every tool that requires paid API calls and expected call volume
- [ ] **API model downgrade policy** -- Default to mini-tier API models unless premium is justified
- [ ] **API response caching** -- Add cache layer for repeated prompts and deterministic utility outputs
- [ ] **API alternatives benchmark** -- Compare OpenAI API costs against DeepSeek/Groq/Together/local baselines
- [ ] **Runtime provider chain** -- Implement provider priority chain (free-tier -> cheap paid -> premium fallback)
- [ ] **BYOK support** -- Add user-provided API key configuration for self-hosted runtime
- [ ] **Runtime spend telemetry** -- Track per-provider call counts and estimated monthly cost
- [ ] **Fallback kill-switch** -- Disable premium provider fallback when monthly budget threshold is reached

---

## 🟠 High Priority

- [x] **OQ-44** — Fix PRD 16 validator contradiction — version-aware logic added
- [x] **OQ-45** — Fix `when_updated` paradox — objective rule implemented
- [x] **OQ-46** — Add `version-doc` and `status` artifact types — resolved
- [ ] **Memory graph edges** — build real edges between active PRDs
- [ ] **.toon generation** — batch regenerate for active corpus
- [ ] **Agent wrapper scripts** — standard script for each registered agent
- [ ] **H-01** — Trust Ladder: SELECT FOR UPDATE locking

---

## 🟡 Medium Priority

- [x] **Update pk_* warnings in scripts** — corpus-wide cleanup DONE 20260415
- [ ] **Archive deprecated PRDs** — move stale docs to `lupo-docs/prd/archive/`
- [ ] **M-13** — Bulk migrate `memory_key` paths `2026/` → `1026/` year segment
- [ ] **M-21** — Optional `--auto-fix` / repair mode in universal validator

---

## 🟢 Low Priority

- [ ] **Clean up old version folders** — review and archive 4.0.93–4.0.98 status files
- [ ] **L-03** — Comment-embedded header builders for `.php`, `.sql`, `.html`

---

## ❄️ GLOBAL CONTROLLED FREEZE NOTE (2026-04-19)

**What was in progress:**
- **Changelog Consolidation:** Successfully merged 35 pending Markdown fragments (2026-04-16 to 2026-04-17) into `lupo-docs/versions/4.1.2/CHANGELOG.md` using a new 10-minute temporal merge rule.
- **Protocol Documentation:** Created `CHANGELOG_BUFFER_ARCHITECTURE.md` and `OPEN_QUESTIONS_PROTOCOL.md` to formalize the non-blocking agent communication model.
- **Tooling Implementation:** Developed `convert_md_buffer_to_json.py` and `consolidate_changelog_v412.py` to support the updated buffer protocol.

**What remains / Should resume after 4/20/2026:**
- **Empirical Verification (Live DB):** The implementation of `channel_key` derivation and edge migration logic in `db_memory_writer.py` and `import_memory_edges_from_sidecar.py` is complete but requires verification against the live database state (see OQ-66, OQ-74, OQ-75).
- **Open Questions Audit:** A fresh audit of the extracted open questions in `open_questions.md` to ensure no semantic duplication occurred during the batch merge.

**Blockers or Dependencies:**
- **Database Access:** Live DB verification requires the environment to be unfrozen and accessible for write/query operations.
- **Temporal Anchor:** Ensure `tick.py` is run immediately upon unfreezing to synchronize the 14-digit UTC anchor.

**Status:** ALL LANES FROZEN. Only `captains_log` active.
**Unfreeze Date:** 2026-04-20
