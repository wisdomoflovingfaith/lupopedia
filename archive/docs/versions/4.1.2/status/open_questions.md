---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/versions/4.1.2/status/open_questions.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.2/status/open_questions.md"
  status: "active"
  when_updated: "20260417114334"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/version-4-1-2-open-questions.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/4_1_2_open_questions"
  artifact_type: status
  artifact_kind: open_questions
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: 2500616418408662071
  content_parent_id: 8067324253853516193
  content_slug: "version-4-1-2-open-questions"
  default_collection_id: null
  lupopedia.schema: status
  title: "Lupopedia 4.1.2 open questions"
  summary: "Consolidated open questions for Lupopedia 4.1.2. OQ-01 through OQ-40 rolled over from 4.1.0. OQ-41+ are new to 4.1.2. OQ-44–OQ-46 record LILITH PRD 16 spec-gap resolutions (20260415223156). OQ-47–OQ-55 added by AUGGIE sticky-note channel gap review (20260415224500). OQ-56–OQ-57 resolved (20260416180000). OQ-58 OPEN: task model unification (lupo_tasks vs lupo_dialog_pending_tasks). OQ-59–OQ-60 added post–Helen weekly report acceptance (20260416234237): translation layer completeness; report preparation checklist. OQ-61 resolved with free-tier strategy and paid-heavy-lift boundary (20260417055316). OQ-62 resolved with runtime API separation and free-tier-first policy (20260417060348). OQ-63 resolved with single-paid-agent profile and free-IDE chaining policy (20260417060943). OQ-64 added for post-June maintenance paid-tool policy (20260417060943). OQ-65–OQ-73 added by Claude Code (actor_id 116) memory channel separation doctrine pass (20260417110000): channel_key derivation safety, generate_toon_files.py conflation, MemoryExportService implementation status, PRD 38 trust_tier promotion, allowed_cross_channel_memory enforcer, PRD 16 content_id queue gap, validator error code audit, channel_key type-coercion, open_questions.md trust_tier semantics."
---
# Open Questions — Lupopedia 4.1.2

Rolled over from `docs/versions/4.1.0/open_questions.md` on `20260415180000`.  
Source file contains OQ-01 through OQ-40. That file is now archived (read-only).

## Rules (READ FIRST)

- APPEND ONLY. Do NOT rewrite or delete prior entries.
- OQ-01 to OQ-06: legacy format — WHEN / FILE / TYPE / WHAT / WHY / IDEAL / BLOCKING
- OQ-07 to OQ-40: current format — WHEN / WHO / AREA / STATUS / QUESTION / WHY THIS MATTERS / OPTIONS / CAN WORK CONTINUE
- OQ-41+: same current format as OQ-07+, added during 4.1.2 work

Clarification:
- This file is canonical as a record of uncertainty and unresolved questions.
- Open-question entries are not authoritative truth statements; they capture known unknowns and risk tracking.
- Treat OQ existence/status as canonical ledger state, but never treat open-question prose as settled doctrine.

---

## Rolled-Over Questions (OQ-01 through OQ-40)

> **Reference:** See `docs/versions/4.1.0/open_questions.md` for full text of OQ-01 through OQ-40.
> These questions are carried forward as-is. Only new 4.1.2 questions are added below.

**Summary of rolled-over questions:**
- OQ-01: DialogMvpService SQLSTATE[HY093] fix (resolved — was blocking)
- OQ-02: `lupo_dialog_messages` schema column mismatches
- OQ-03: Thread-per-day model — undocumented; thread boundary is UTC midnight
- OQ-04: `fetch-messages.php` — no authentication gate
- OQ-05: DOM reload threshold; 200 initial load vs 500 reload trigger
- OQ-06: Hardcoded channel_id 42 fallback (magic number)
- OQ-07: atoms_toon canonical namespace root
- OQ-08: Should active plan also exist at project root?
- OQ-09: Polling endpoint URL mismatch — PRD 02 vs actual code
- OQ-10: Poll query scope — channel-wide vs thread-scoped
- OQ-11: `tasks-list` element missing from HTML
- OQ-12: HERMES `[task]` syntax — not implemented
- OQ-13: THOTH hardcoded color exception undocumented
- OQ-14: `DialogMvpService` vs global functions — which is canonical?
- OQ-15: `message_type = 'text'` conflicts with PRD 02 enum
- OQ-16: Hardcoded `'666666'` color (superseded by OQ-35)
- OQ-17: Initial message load limit 200 vs PRD 02 says 1000
- OQ-18: `layers.js` dependency — undocumented
- OQ-19: Direct message (`to_actor_id != 0`) rendering not specified
- OQ-20: Thread = today only; no historical thread navigation
- OQ-21: `lupo_agent_colors` table specified but never queried
- OQ-22: `channel_id` on `lupo_dialog_messages` — denormalization
- OQ-23: `?memory_key=` URL param uses deprecated field name
- OQ-24: Duplicate slug-lookup functions
- OQ-25: Two parallel content-show paths
- OQ-26: `lupo_collection_tab_map` schema missing from PRD 73
- OQ-27: `default_collection_id` cross-namespace dependency undocumented
- OQ-28: `lupo_resolve_collection_tabs_for_chrome(0)` undefined behavior
- OQ-29: PRD 73 §8 aspirational section unmarked
- OQ-30: `lupo_rolls` table — no schema or usage
- OQ-31: `item_count` cached column — no maintenance logic
- OQ-32: `lupo_paths` table — routing table or navigation chrome?
- OQ-33: `content/index.php` header is v2 format
- OQ-34: `error_reporting(E_ALL)` hardcoded in content/index.php (security)
- OQ-35: Correction of OQ-16 — `'666666'` is mood_vector neutral, not display color
- OQ-36: PRD 02 missing mood_vector / mood_label parameter docs
- OQ-37: How does `mood_vector` surface in channel message rendering?
- OQ-38: ROSE cannot do DB writes but produces mood-bearing output — who stores it?
- OQ-39: Fallback `'666666'` vs explicit neutral — indistinguishable in storage
- OQ-40: Canonical mood_vector token enforcement — no validator

---

## New Questions (OQ-41+)

---

## OQ-41: Three tooling scripts still emit legacy pk_* field names

**WHEN:** 20260415180000
**WHO:** AUGGIE
**AREA:** scripts/add_lupopedia_header_to_file.py, scripts/generate_memory_from_header.py, scripts/lib/lupopedia_markdown_header_peel.py
**STATUS:** open

**QUESTION:**
The tooling audit (2026-04-15) found 3 of 4 scripts still on v4.1.0 with legacy pk_* fields:
- `add_lupopedia_header_to_file.py` emits `pk_id`/`pk_slug`/`parent_pk_id` in every generated header
- `generate_memory_from_header.py` reads `parent_pk_id` but not `content_parent_id`; error messages reference `memory_key` instead of `memory_toon`
- `lib/lupopedia_markdown_header_peel.py` checks aliases `prd_id`/`prd_slug`/`parent_prd` (pre-v4 names) in `validate_lupopedia_header_inner_keys_present`

**WHY THIS MATTERS:**
`add_lupopedia_header_to_file.py` is actively generating non-compliant headers for every new file. Validator fires `HDR_PK_LEGACY_ALIAS` on all generated files.

**OPTIONS:**
1. Fix all three scripts in a single session (highest priority — stops new violations from being generated)
2. Fix `add_lupopedia_header_to_file.py` first, then the other two
3. Generate a wrapper script that post-processes output to rename fields

**CAN WORK CONTINUE:** yes (validation passes with warnings, but new files are non-compliant)

---

## OQ-41 — STATUS UPDATE: RESOLVED 20260415183500

All three scripts fixed in session `20260415183500`–`20260415200000`:
- `add_lupopedia_header_to_file.py` — generates `content_parent_id`, `content_slug`, `default_collection_id: null`; `pk_id: null` line removed
- `generate_memory_from_header.py` — reads `content_parent_id` (fallback `parent_pk_id`), `content_slug` (fallback `pk_slug`); `header_bridge` key updated; error messages updated
- `lib/lupopedia_markdown_header_peel.py` — alias block expanded to accept `pk_id/pk_slug/parent_pk_id` and `prd_id/prd_slug/parent_prd` as fallbacks for canonical `content_*` keys; `default_collection_id` has no alias (required)
- `migrate_pk_to_content_fields.py` created as corpus migration helper (`--dry-run` / `--commit` / `--all`)

See CHANGELOG entry `[2026-04-15 18:35–19:00 UTC]` for full detail.

---

## OQ-42: Missing .toon sidecars for v4.1.2 version docs and new migrate script

**WHEN:** 20260415200000
**WHO:** AUGGIE
**AREA:** memory/development/canonical/1026/04/
**STATUS:** open

**QUESTION:**
Four files created or modified today have `memory_toon:` fields pointing to `.toon` sidecars that do not exist on disk. The validator reports `HDR_MEMORY_TOON_MISSING` for all four:

| File | Referenced .toon | On disk? |
|---|---|---|
| `docs/versions/4.1.2/README.md` | `version-4-1-2-readme.toon` | ❌ |
| `docs/versions/4.1.2/TODO.md` | `version-4-1-2-todo.toon` | ❌ |
| `docs/versions/4.1.2/status/open_questions.md` | `version-4-1-2-open-questions.toon` | ❌ |
| `scripts/migrate_pk_to_content_fields.py` | `migrate-pk-to-content-fields.toon` | ❌ |

Note: `version-4-1-2-changelog.toon` already exists ✅ (created in a prior session).

**WHY THIS MATTERS:**
Without `.toon` sidecars, `import_memory_edges_from_sidecar.py` cannot resolve these files as source nodes. Any edge import touching them will fail with `[WARN] cannot resolve source node` unless `--auto-create-source-node` is used as a stopgap.

**OPTIONS:**
1. Run `generate_memory_from_header.py` on each file individually (preferred — generates proper sidecar JSON with edges)
2. Run `generate_memory_from_header.py --batch` to catch all missing sidecars at once
3. Use `--auto-create-source-node` as a temporary bridge until generation is complete

**CAN WORK CONTINUE:** yes — edges simply won't import for these four files until sidecars are generated

---

## OQ-43: v4.1.2 version docs have content_parent_id: null — no outbound graph edges will be generated

**WHEN:** 20260415200000
**WHO:** AUGGIE
**AREA:** docs/versions/4.1.2/ (all four version docs)
**STATUS:** open

**QUESTION:**
All four v4.1.2 version docs (`README.md`, `CHANGELOG.md`, `TODO.md`, `status/open_questions.md`) have `content_parent_id: null`. When `generate_memory_from_header.py` runs on them, the `edges.outbound` block in each generated sidecar will be empty — no relationships to PRD 16 (header spec), PRD 40 (versioning doctrine), or each other will be recorded.

By contrast, `scripts/migrate_pk_to_content_fields.py` has `content_parent_id: "16"` and will correctly produce an edge to PRD 16 when its sidecar is generated.

**WHY THIS MATTERS:**
The memory graph will have four isolated nodes for the v4.1.2 version docs. Querying "what references PRD 16?" will not surface the changelog or open questions that directly document header-related work.

**OPTIONS:**
1. Set `content_parent_id: "16"` on all four version docs (PRD 16 = header spec, the closest normative parent)
2. Leave null and add explicit `edges.outbound` entries in the sidecar JSON after generation (manual edge authoring)
3. Accept isolated nodes — version-folder docs are administrative, not PRD-family docs

**CAN WORK CONTINUE:** yes — graph isolation is a quality gap, not a blocking error

---

## OQ-42 — STATUS UPDATE: RESOLVED 20260415190639

Resolution completed in-session:
- Generated sidecars for all four missing targets:
  - `docs/versions/4.1.2/README.md`
  - `docs/versions/4.1.2/TODO.md`
  - `docs/versions/4.1.2/status/open_questions.md`
  - `scripts/migrate_pk_to_content_fields.py`
- Ran strict memory-pair validation successfully for each.
- Imported sidecar edges with `--auto-create-source-node`; initial unresolved path-target warnings were removed by importer hardening (source-file path target resolution + target auto-create).

Result: `HDR_MEMORY_TOON_MISSING` condition for these files is closed.

---

## OQ-43 — STATUS UPDATE: RESOLVED 20260415190822

Resolution completed in-session:
- Updated `content_parent_id` from `null` to `16` in:
  - `docs/versions/4.1.2/README.md`
  - `docs/versions/4.1.2/TODO.md`
  - `docs/versions/4.1.2/status/open_questions.md`
  - `docs/versions/4.1.2/CHANGELOG.md`
- Regenerated sidecars with `--force`.
- Confirmed sidecars now include outbound references to:
  - `docs/prd/16_lupopedia_headers.md` (`type: references`, `reason: content_parent_id -> PRD file`)
- Imported those edges into DB successfully (unresolved=0 on rerun).

Result: version docs are no longer isolated nodes in the memory graph.

---

## OQ-44: PRD 16 §15.4 accepted any 4.1.x while §11 said pk_* removed at 4.1.3

**WHEN:** 20260415213745  
**WHO:** LILITH (audit) / Cursor (actor_id 102) execution  
**AREA:** `docs/prd/16_lupopedia_headers.md` §11 vs §15.4; `validate_lupopedia_headers_universal.py`  
**STATUS:** resolved  

**QUESTION:** Validator pseudo-code treated all `4.1.x` identically, so a file could claim `4.1.99` with legacy `pk_*` keys and still pass.  

**RESOLUTION:** §15.4 replaced with version/patch-aware policy; §11 adds explicit table; code adds `validate_legacy_pk_alias_vs_claimed_version()` (ERROR for `4.1.3+` when `pk_*` present).  

**CAN WORK CONTINUE:** yes  

---

## OQ-45: Migration guide said not to bump `when_updated` on header-only migration (liar field)

**WHEN:** 20260415213745  
**WHO:** LILITH (audit) / Cursor (actor_id 102) execution  
**AREA:** `16_lupopedia_headers_migration.md` §5  
**STATUS:** resolved  

**QUESTION:** Preserving `when_updated` during header edits made the timestamp disagree with real file mutation.  

**RESOLUTION:** §5 is now an objective rule: every byte change sets `when_updated` from `tick.py` `current_utc`; subjective “meaningful body change” language removed.  

**CAN WORK CONTINUE:** yes  

---

## OQ-46: Version-folder docs forced `artifact_type: documentation` masquerading as PRD 16 implementation

**WHEN:** 20260415213745
**WHO:** LILITH (audit) / Cursor (actor_id 102) execution
**AREA:** PRD 16 §4.2 field 11; `docs/versions/4.1.2/` headers
**STATUS:** resolved

**QUESTION:** README/CHANGELOG/TODO/status files are neither normative PRDs nor implementation mirrors; `documentation` was a poor fit for graph parentage.

**RESOLUTION:** New `artifact_type` values `version-doc` and `status` in PRD 16 §4.2.1 and `header_spec_v3_1.py`; migration §4.4 parentage table; v4.1.2 version docs updated (`version-doc` for README/CHANGELOG/TODO; `status` for open_questions and session closeout).

**CAN WORK CONTINUE:** yes

---

## Open Questions — Cursor — 20260417102228

Format note: This section uses an extended uncertainty-sweep format (Question/Why/Evidence/Risk/Next action) and is append-only like the main OQ ledger.

**Faucet / IDE surface:** Cursor IDE (actor_id 102)  
**Area reviewed:** PRD 16, PRD 38, `validate_lupopedia_headers_universal.py`, `header_spec_v3_1.py`, ANUBIS queue proposal metadata, install SQL memory tables, TOON generation scripts and outputs.

### 1. channel_key population in reconstruction/import path
- **Question:** Is `channel_key` actually populated during artifact reconstruction/import, or only present as schema metadata?
- **Why it is open:** Verified `generate_toon_files.py` is schema-export only and does not perform row reconstruction or channel derivation.
- **Observed evidence:** `scripts/generate_toon_files.py` only runs `DESCRIBE`/`SHOW INDEX` and writes JSON/TOON schema files; no INSERT/UPDATE logic exists.
- **Risk if unresolved:** Memory rows may remain null/incorrect for `channel_key` even though schema and validator expectations exist.
- **Suggested next action:** Identify the actual reconstruction/import writer(s) and verify/set deterministic derivation order there (header -> path -> skip+log).

### 2. content_id authority model enforcement completeness
- **Question:** Is file-first/database-first/repair-state fully enforced in runtime paths, or only in validator DB-check mode?
- **Why it is open:** Added deterministic state handling in validator with `--check-db`, but this does not guarantee runtime equivalence everywhere.
- **Observed evidence:** `validate_lupopedia_headers_universal.py` prints `[STATE]` classifications in DB-check path; no proof yet that all runtime import/repair paths share identical logic.
- **Risk if unresolved:** Divergent behavior between validator and runtime could create contradictory identity authority decisions.
- **Suggested next action:** Trace ANUBIS/runtime repair entrypoints and confirm they apply the same 3-state model semantics.

### 3. trust_tier doctrine migration drift (legacy tiers in active corpus)
- **Question:** After trust_tier clarification to canonical/development, what is the migration policy for existing seed/staging/archive headers?
- **Why it is open:** Current validator tolerates legacy tiers with warnings to avoid breakage; corpus still includes legacy usage.
- **Observed evidence:** `header_spec_v3_1.py` defines `LEGACY_TRUST_TIERS`; validator emits warning-level compatibility behavior.
- **Risk if unresolved:** Long-lived mixed semantics can cause agent confusion about authority and lifecycle.
- **Suggested next action:** Define a concrete transition gate (date/version) for warn->error promotion on legacy trust tiers.

### 4. PRD wording vs validator behavior (content_id DB check optionality)
- **Question:** Should DB-backed content_id authority validation be mandatory in CI, or remain optional (`--check-db`)?
- **Why it is open:** PRD now states deterministic authority model, but default validator run warns state is not DB-verified.
- **Observed evidence:** `HDR_CONTENT_ID_STATE_UNVERIFIED` warning appears when `--check-db` is not supplied.
- **Risk if unresolved:** Teams may read PASS as full authority compliance when DB state was not checked.
- **Suggested next action:** Decide CI profile requirement for DB-backed runs on authoritative doc sets.

### 5. install SQL / live DB / generated metadata alignment confidence
- **Question:** Are install SQL, live DB, and generated TOON/JSON metadata now fully aligned beyond the specific memory table channel_key updates?
- **Why it is open:** We verified and patched memory table alignment only; a full cross-table reconciliation was not performed in this pass.
- **Observed evidence:** Added `channel_key` in install SQL memory tables; TOON generator output confirms presence for those tables.
- **Risk if unresolved:** Hidden schema drift in other tables can persist undetected and surface later in import/runtime failures.
- **Suggested next action:** Run a focused full-schema drift report against canonical install SQL and regenerated metadata.

### 6. proposed queue execution still present in runtime docs
- **Question:** Are any files still presenting queue execution as canonical outside the explicitly non-canonical proposal doc?
- **Why it is open:** We corrected known wording in validator and retained proposal status in ANUBIS queue doc, but did not perform an exhaustive repo-wide semantic audit.
- **Observed evidence:** `ANUBIS_QUEUE_EXECUTION_PROPOSAL.md` is now trust_tier development and marked proposed/non-canonical.
- **Risk if unresolved:** One stale canonical-sounding mention can reintroduce implementation drift.
- **Suggested next action:** Run targeted search for "add to queue" / "queue-authoritative" language in canonical artifacts and reconcile wording.

### 7. string vs integer handling for ID fields
- **Question:** Should header validators reject numeric strings for integer fields (`content_parent_id`, `content_id`) or continue permissive parsing?
- **Why it is open:** Current behavior accepts some stringified numerics in broader flow; doctrine text expects integer-or-null semantics.
- **Observed evidence:** Recent review flagged string-vs-integer drift risk; current system mixes strictness and normalization depending on path.
- **Risk if unresolved:** Silent coercion can hide producer-side defects and produce inconsistent downstream typing.
- **Suggested next action:** Define a strictness policy per field (accept+warn vs reject) and apply consistently.

### 8. memory path authority interpretation risk
- **Question:** Are agents/runtime uniformly treating header as authority and memory_toon path as derived/validated, not primary truth?
- **Why it is open:** Added PRD clarification, but broad runtime behavior was not fully audited in this pass.
- **Observed evidence:** PRD 16 now states memory_toon path is derived from header metadata; validator enforces path/header consistency.
- **Risk if unresolved:** Some codepaths may still treat path as primary and override header intent.
- **Suggested next action:** Inspect import/repair/generator codepaths for path-first assumptions and log any remaining violations.

---

## OQ-62 -- STATUS UPDATE: RESOLVED 20260417060348

Resolution completed in-session:
- Budget policy now distinguishes **development spend** (through June 1) from **runtime API spend** (post-June).
- Runtime policy adopts **free-tier-first provider chaining** with premium paid APIs as last resort.
- OpenAI API remains allowed only under constrained fallback conditions due to paid-only model.

Result: minimum viable paid API usage is now a controlled fallback posture, not the default runtime path.

---

## OQ-63: Runtime provider-chain architecture and BYOK enforcement

**WHEN:** 20260417060348
**WHO:** LILITH (actor_id 2) audit guidance, Cursor implementation
**AREA:** Runtime API orchestration / self-hosted cost model
**STATUS:** open

**QUESTION:**
What is the canonical runtime implementation pattern for provider chaining and user-supplied API keys so self-hosted deployments avoid subsidized inference costs?

**WHY THIS MATTERS:**
Without a canonical provider-chain and BYOK contract, runtime costs can drift to premium vendors and break post-June budget assumptions.

**OPTIONS:**
1. Config-driven chain with fixed priority order (free-tier -> cheap paid -> premium fallback), plus global budget threshold guards
2. Per-route chain policy (different providers by endpoint/task class), with explicit per-route cost caps
3. User-selectable provider policies in admin UI, constrained by safe defaults and hard spend limits

**CAN WORK CONTINUE:** yes

---

## OQ-63 -- STATUS UPDATE: RESOLVED 20260417060943

Resolution completed in-session:
- Budget policy now supports a **single paid agent mode**: Claude Code ($17) as paid reasoning anchor.
- IDE execution shifts to **free-tier chaining** (Cursor free, Castcade, Antigravity, Windsurf) with mandatory handoff toons.
- Remaining budget target is reserved for runtime API calls (~$33) under cost-first provider chaining.

Result: paid-tool spend is minimized while preserving capability through free-tier orchestration.

---

## OQ-64: Post-June maintenance paid-tool threshold

**WHEN:** 20260417060943
**WHO:** LILITH (actor_id 2) audit guidance, Cursor implementation
**AREA:** Budget doctrine / maintenance operations
**STATUS:** open

**QUESTION:**
After June 1, under what maintenance conditions should paid IDE/tool subscriptions be temporarily re-enabled beyond the single-paid-agent baseline?

**WHY THIS MATTERS:**
The single-paid-agent strategy maximizes runtime API budget, but maintenance incidents may require temporary throughput spikes. A threshold policy prevents ad hoc spend drift.

**OPTIONS:**
1. Keep single-paid-agent mode by default; permit temporary paid IDE activation only during incident windows
2. Keep single-paid-agent mode permanently and rely on free-tier chains plus schedule flexibility
3. Define monthly maintenance complexity threshold that triggers temporary second paid tool for one billing cycle

**CAN WORK CONTINUE:** yes

---

## OQ-65: Minimum viable paid API usage and fallback chain

**WHEN:** 20260417055722
**WHO:** LILITH (actor_id 2) audit guidance, Cursor implementation
**AREA:** Budget doctrine / API cost control
**STATUS:** open

**QUESTION:**
OpenAI API has no standing free tier for production usage. What is the minimum viable paid API footprint that keeps Lupopedia within the $50/month cap while still enabling required API tooling?

**WHY THIS MATTERS:**
If paid API usage is not bounded, the monthly budget can exceed cap quickly and reduce capacity for primary paid agents.

**OPTIONS:**
1. Avoid OpenAI API unless strictly required; prefer free tiers plus cheaper APIs and local models
2. If OpenAI API is required, default to mini-tier models and enforce strict caching plus call quotas
3. Keep OpenAI API disabled in development by default and allow opt-in for specific production-critical workflows only

**CAN WORK CONTINUE:** yes

---

## OQ-61: Free-tier strategy and paid-heavy-lift boundary

**WHEN:** 20260417055316
**WHO:** LILITH (actor_id 2) audit guidance, Cursor implementation
**AREA:** Budget doctrine / agent orchestration budget policy
**STATUS:** resolved

**QUESTION:**
Should Lupopedia drop non-primary agents to stay within the $50/month cap, or keep all free-tier agents and reserve paid agents for heavy lifting only?

**RESOLUTION (20260417055316):**
Keep all free-tier agents. Paid agents (Cursor + Claude Code) remain primary heavy lifters. Free-tier agents are force multipliers for simple tasks, drafts, quick reviews, and parallel throughput at $0 direct cost.

**Policy summary:**
1. Paid primary: Cursor + Claude Code (heavy lifting)
2. Free tier: keep all available free agents active
3. Overage buffer: reserved for paid-token emergencies
4. Cross-tier handoff toons: mandatory to prevent lost work

**CAN WORK CONTINUE:** yes

---

## OQ-61: Cheaper model fallback strategy

**WHEN:** 20260417000000
**WHO:** Captain WOLFIE
**AREA:** Budget doctrine
**STATUS:** open

**QUESTION:**
When expensive models (Claude Opus, GPT-4) are not strictly necessary, what is the fallback chain?

**OPTIONS:**
1. Claude Haiku -> GPT-3.5 -> local model
2. Always try cheaper first, escalate only on failure
3. Manual selection per task type

**CAN WORK CONTINUE:** yes

---

## OQ-47: Where does the operator scratchpad live?

**WHEN:** 20260415224500
**WHO:** AUGGIE (gap review — auggie_sticky_note_channel_review.md)
**AREA:** docs/prd/02_channels_discussions.md; proposed `lupo_operator_scratchpad` table
**STATUS:** open

**QUESTION:**
The operator scratchpad (Notepad replacement) can be implemented as:
1. A DB table (`lupo_operator_scratchpad`) — full persistence, queryable, supports routing event FKs
2. A flat file per session (e.g., `content/scratchpad/WOLFIE/YYYYMMDD.md`) — simpler, no migration cost
3. A hybrid — DB row with `body` stored as a file path to content on disk

**WHY THIS MATTERS:**
The scratchpad must be referenceable as `source_scratchpad_id` in `lupo_routing_events`. A flat file makes FK referencing impossible without a DB record. DB option enables full pipeline provenance.

**OPTIONS:**
1. DB table (preferred — enables routing event FK linkage)
2. Flat file (simpler but breaks routing provenance)
3. Hybrid (complexity cost may not be worth it)

**CAN WORK CONTINUE:** yes — P0 item but schema decision must precede implementation

---

## OQ-48: How are external agents (ChatGPT, Grok, Gemini web, etc.) represented?

**WHEN:** 20260415224500
**WHO:** AUGGIE (gap review — auggie_sticky_note_channel_review.md)
**AREA:** `lupo_actors` table; `lupo_routing_events.source_external_actor_label`; global constants atom
**STATUS:** open

**QUESTION:**
External web agents (ChatGPT, Grok, Gemini web, DeepSeek web, Copilot) participate in the real routing workflow but have no system presence. They must be representable in routing events. Two options:

1. Add `actor_type='external_web'` column to `lupo_actors` and insert rows for each external agent. They have no task queue, no write access, and no heartbeat. They are reference-only entries.
2. Create a separate `lupo_external_agents` table (label, description, notes) with its own FK used in routing events instead of `actor_id`.

**WHY THIS MATTERS:**
Routing event provenance is broken if "I refined this with Grok" cannot be recorded as a structured field. A free-text label is a fallback but loses queryability.

**OPTIONS:**
1. `actor_type='external_web'` rows in `lupo_actors` (preferred — unified actor model, same FK pattern)
2. Separate `lupo_external_agents` table (cleaner separation but requires two FK paths in routing events)
3. Free-text label only — `source_external_actor_label VARCHAR(64)` with no FK (lowest cost, lowest query value)

**CAN WORK CONTINUE:** yes — P0 for routing provenance; can stub with free-text label initially

---

## OQ-49: What defines "active context" for a channel vs. "routing target"?

**WHEN:** 20260415224500
**WHO:** AUGGIE (gap review — auggie_sticky_note_channel_review.md)
**AREA:** docs/prd/02_channels_discussions.md; channel model
**STATUS:** open

**QUESTION:**
The operator works across multiple channels simultaneously. The dashboard must distinguish between:
- **Active context**: the channel the operator is currently viewing / composing in
- **Routing target**: the channel/agent a task or message is being dispatched to

These may differ. The operator may be in the `documentation` channel but routing a task to an agent in the `development` channel.

Does "active context" need to be a persisted field, or is it a UI-only session concept (current tab)?
Does "routing target" default to the active context, or must the operator always specify it explicitly?

**WHY THIS MATTERS:**
If routing defaults to active context, the operator may accidentally route tasks to the wrong channel. If explicit, the UX cost increases.

**OPTIONS:**
1. Active context = UI session state only; routing target = always explicit (safest)
2. Active context = persisted per operator session in DB; routing target defaults to active context with override
3. Active context = current tab; routing target shown as a dropdown on the promote-to-task action

**CAN WORK CONTINUE:** yes — UX decision, not a blocker for schema

---

## OQ-50: Should routing be explicit objects or inferred from message chain?

**WHEN:** 20260415224500
**WHO:** AUGGIE (gap review — auggie_sticky_note_channel_review.md)
**AREA:** proposed `lupo_routing_events` table; `lupo_dialog_pending_tasks`
**STATUS:** resolved

**QUESTION:**
Option A: Routing events are explicit typed objects (`lupo_routing_events` table). Every task assignment creates a routing event record with source, destination, and provenance fields.
Option B: Routing is inferred from the message chain — if a task message includes a `[task]` command with source metadata in the body, provenance is derived by parsing the message log.

**RESOLUTION (20260415213000):**
Option A. **Routing is implemented as explicit objects in lupo_routing_events using a dual-selection (Channel + Actor) UI pattern.** This ensures high-precision provenance and structured task queue injection across channel boundaries.

**CAN WORK CONTINUE:** yes — resolved

---

## OQ-51: How is agent status determined — polling vs. self-reporting vs. operator-manual?

**WHEN:** 20260415224500
**WHO:** AUGGIE (gap review — auggie_sticky_note_channel_review.md)
**AREA:** proposed `lupo_agent_status` table; `agent_wrapper.php`
**STATUS:** open

**QUESTION:**
Three models for setting agent status:
1. **Self-reporting**: Agent wrapper posts heartbeat to `POST /api/agent/heartbeat` every N seconds. Cron detects silence → sets UNKNOWN.
2. **Task-transition-driven**: Status changes when task status changes (pending→in_progress sets ACTIVE; completed sets IDLE; failed sets FAILED). No heartbeat.
3. **Operator-manual**: WOLFIE sets status manually in the UI. No automation.

External agents (ChatGPT, Grok web) can ONLY use option 3 — they have no system access.

**WHY THIS MATTERS:**
Self-reporting requires agent wrapper modification. Task-transition-driven is simpler but cannot detect SLEEPING (no task change when agent passes out mid-session). Manual is reliable for external agents but burdensome for terminal agents.

**OPTIONS:**
1. Task-transition-driven for internal agents + manual for external agents (lowest implementation cost for P0)
2. Heartbeat for terminal agents + task-transition as fallback + manual for external agents (more robust)
3. Manual only for all agents (acceptable for early 4.1.2; upgrade later)

**CAN WORK CONTINUE:** yes — P0 but can start with manual-only and add heartbeat in P1

---

## OQ-52: Should sticky notes be first-class DB entities?

**WHEN:** 20260415224500
**WHO:** AUGGIE (gap review — auggie_sticky_note_channel_review.md)
**AREA:** proposed `lupo_sticky_notes` table; `lupo_dialog_messages`
**STATUS:** open

**QUESTION:**
Option A: Sticky notes are first-class DB entities in `lupo_sticky_notes` (recommended in review). Independent of chat. Can be pinned, channel-scoped, colored.
Option B: Sticky notes are chat messages with `message_type='note'`. They appear in the feed but are visually distinguished. No separate table.
Option C: Sticky notes are scratchpad entries with `is_pinned=1`. No separate table.

**WHY THIS MATTERS:**
Option A supports pinning, color, and channel scope independently of the chat feed. Option B reuses existing infrastructure but pollutes the chronological feed with persistent annotations. Option C conflates note-keeping with drafting.

**OPTIONS:**
1. First-class `lupo_sticky_notes` table (preferred — cleanest model, supports channel scope)
2. `message_type='note'` in `lupo_dialog_messages` (reuses schema but pollutes feed)
3. Scratchpad with `is_pinned` flag (conflates two concepts)

**CAN WORK CONTINUE:** yes — P1 item; can use scratchpad as interim

---

## OQ-53: What is the data model for a prompt pipeline / handoff record?

**WHEN:** 20260415224500
**WHO:** AUGGIE (gap review — auggie_sticky_note_channel_review.md)
**AREA:** proposed `lupo_routing_events` table
**STATUS:** open

**QUESTION:**
The real workflow is a multi-hop chain: Idea → LILITH draft → Notepad edit → ChatGPT refinement → Auggie implementation → VS Code structuring → Grok merge. A single routing event captures one hop. The full chain is N routing events.

Is a flat list of routing events sufficient to reconstruct the chain? Or does the model need an explicit `parent_routing_id` FK to support chain traversal?

**WHY THIS MATTERS:**
Without `parent_routing_id`, reconstructing a multi-hop chain requires sorting by timestamp and inferring sequence. With it, the chain is explicit but adds complexity.

**OPTIONS:**
1. Flat list — reconstruct chains by timestamp + destination→source matching (simpler schema)
2. `parent_routing_id` FK — explicit chain links (cleaner traversal, more complex insertion)
3. `pipeline_id` group key — all hops in a chain share a `pipeline_id` (easy grouping without strict ordering)

**CAN WORK CONTINUE:** yes — schema decision; flat list is acceptable for 4.1.2

---

## OQ-54: Should the operator scratchpad support multiple concurrent named drafts?

**WHEN:** 20260415224500
**WHO:** AUGGIE (gap review — auggie_sticky_note_channel_review.md)
**AREA:** proposed `lupo_operator_scratchpad` table; UI
**STATUS:** open

**QUESTION:**
The real workflow shows the operator composing multiple simultaneous prompts (e.g., "prompt for Auggie" AND "prompt for Gemini" at the same time). A single-draft scratchpad forces the operator to use one text area and manage context manually.

Does 4.1.2 require multi-draft support (N named drafts), or is a single active draft + a history list sufficient?

**OPTIONS:**
1. Multi-draft: N named drafts, list view, each independently editable (matches real workflow)
2. Single active draft + history: one editable draft at a time; previous drafts are read-only history
3. Single draft only: simplest; operator must use titles to distinguish content

**CAN WORK CONTINUE:** yes — single draft is acceptable for 4.1.2 MVP; upgrade to multi-draft in P1

---

## OQ-55: What triggers a "channel blocked" state, and who can unblock it?

**WHEN:** 20260415224500
**WHO:** AUGGIE (gap review — auggie_sticky_note_channel_review.md)
**AREA:** proposed channel status model; `lupo_channels` table
**STATUS:** open

**QUESTION:**
The real workflow shows channels with status ("Channel A is blocked on schema change"). PRD 02 has no channel status model. Two questions:
1. What triggers BLOCKED? Options: (a) operator sets it manually, (b) all tasks in channel are BLOCKED status, (c) operator sets it + system auto-detects.
2. Who can unblock? Options: (a) operator only, (b) any task completion in the channel, (c) operator only.

**WHY THIS MATTERS:**
Auto-detection of BLOCKED from task states requires reading `lupo_dialog_pending_tasks` per channel — adds a query on every channel render. Manual-only is simpler but requires operator discipline.

**OPTIONS:**
1. Manual-only (operator sets BLOCKED/ACTIVE via UI action) — lowest cost
2. Auto-detect from task states (BLOCKED if all tasks in channel have status=BLOCKED or CANCELLED) — more useful but higher query cost
3. Manual set + auto-clear (operator sets BLOCKED; system clears to ACTIVE when any task completes in channel)

**CAN WORK CONTINUE:** yes — P1 item; channel status is informational, not blocking

---

## OQ-56: Are context tab personas (CAPTAIN, DEVIN, ERIC, LEXA) registered actors in `lupo_actors`?

**WHEN:** 20260416120000
**WHO:** AUGGIE (PRD 02 update + Q&A session)
**AREA:** `lupo_actors` table; `$_SESSION['active_context_actor_id']`; context tabs UI
**STATUS:** resolved

**QUESTION:**
The updated blog defines context tabs (CAPTAIN, DEVIN, ERIC, LEXA) as operational personas one human wears. The `from_actor_id` of messages posted under a context tab must reference a valid `actor_id`. Two options:

1. Each persona is a real row in `lupo_actors` with `actor_type='human_persona'` or similar — messages can be queried by persona.
2. Personas are session-only labels that map to WOLFIE (actor_id=1) — `from_actor_id` is always 1 regardless of active tab, and the tab is just a filter/label with no DB footprint.

**RESOLUTION (20260416180000):** Option 1. Target actor personas are **first-class registered actors** in `lupo_actors` with `actor_type = 'human_persona'`. Full provenance is preserved in routing events. Each persona has its own `actor_id` in the 10,000+ range (see OQ-57). This enables: per-persona message history queries, per-persona recent files filtering, and routing event attribution.

**CAN WORK CONTINUE:** yes — resolved

---

## OQ-57: What actor_id range applies to context tab personas?

**WHEN:** 20260416120000
**WHO:** AUGGIE (PRD 02 update + Q&A session)
**AREA:** `memory/atoms/lupopedia_global_constants.atom.toon`; `lupo_actors`; blog
**STATUS:** resolved

**QUESTION:**
The updated blog states "Sometimes I'm CAPTAIN (actor_id 10000)." However, `lupopedia_global_constants.atom.toon` sets `max_seed_actors: 999`. An actor_id of 10000 would violate the constitutional limit.

Either:
1. The blog's `actor_id 10000` for CAPTAIN is illustrative/placeholder — the actual actor_id must be ≤ 999.
2. The `max_seed_actors: 999` limit applies only to SEED-class actors, and human persona actors are a different class with a different range.
3. The blog intends CAPTAIN to be a logical role label, not a literal `actor_id` — the constitutional limit is not violated.

**RESOLUTION (20260416180000):** Option 2. The `max_seed_actors: 999` limit applies only to SEED-class system actors (agents, services). Human persona actors are a **separate class** (`actor_type = 'human_persona'`) with a canonical range of **10,000 and above**. This avoids all collisions with the seed range. The blog's `actor_id 10000` for CAPTAIN is confirmed as the canonical value. No Captain's Amendment to PRD 99 is required because this is a separate actor class, not an extension of seed actors.

**Canonical persona actor_ids:**
| Persona | actor_id | actor_type |
|---|---|---|
| CAPTAIN | 10001 | human_persona |
| DEVIN | 10002 | human_persona |
| ERIC | 10003 | human_persona |
| LEXA | 10004 | human_persona |

## OQ-58: Task model unification

**WHEN:** 20260416000000
**WHO:** Antigravity (from Cursor handoff)
**AREA:** PRD 02 / PRD 82
**STATUS:** open

**QUESTION:**
There are two parallel task systems:
- lupo_tasks (used by /api/tasks/list API endpoints)
- lupo_dialog_pending_tasks (used by HERMES pipeline)

Which system should be canonical? Should they be unified? If so, what is the migration path?

**WHY THIS MATTERS:**
Agents and UI are reading from different task tables. This creates inconsistency and potential duplicate work.

**OPTIONS:**
1. Deprecate lupo_tasks, migrate everything to lupo_dialog_pending_tasks
2. Keep both, create a sync layer
3. Redefine lupo_tasks as the UI view, lupo_dialog_pending_tasks as the agent queue

**CAN WORK CONTINUE:** yes

---

## OQ-59: Translation layer completeness (executive-safe coverage)

**WHEN:** 20260416234237
**WHO:** Cursor IDE (post-report closeout, week ending 2026-04-16)
**AREA:** `channels/0/translation/concepts/`; `docs/doctrine/system/TRANSLATION_MODEL.md`
**STATUS:** open

**QUESTION:**
Do all core Lupopedia concepts that leadership and operators ask about have reusable executive-safe explanations in the translation channel (or equivalent), so weekly and ad-hoc reporting does not reinvent narrative each time?

**WHY THIS MATTERS:**
Impact **HIGH**. Incomplete coverage forces expensive re-explanation and increases misread risk under time pressure.

**OPTIONS:**
1. Maintain a rolling checklist of concepts that must have seeds and close gaps before each executive report cycle
2. Promote translation seeds to canonical memory only via explicit THOTH / promotion rules (no silent drift)
3. Defer partial coverage and accept time cost (not recommended)

**CAN WORK CONTINUE:** yes

---

## OQ-60: Report preparation workflow (pre-report checklist)

**WHEN:** 20260416234237
**WHO:** Cursor IDE (post-report closeout, week ending 2026-04-16)
**AREA:** `REPORT_EMAIL_TO_HELEN_2026_04_16.md` pattern; `weekly_report_evidence_index_*.md`; `report_helen_*_related_files.jsonl`
**STATUS:** open

**QUESTION:**
What is the required **pre-report checklist** (ordered steps, owners, and artifacts) so a weekly executive report does not trigger multi-hour revision loops?

**WHY THIS MATTERS:**
Impact **HIGH**. Helen report cycle documented roughly five hours to acceptance; primary drivers were explanation rewriting, terminology confusion, and missing reusable communication layer until late in the cycle. See `docs/versions/4.1.2/status/weekly_report_lessons_learned_20260416.md`.

**OPTIONS:**
1. Codify checklist in `docs/versions/4.1.2/status/` (link from AGENTS.md or channel README)
2. Add an operator-channel template task that spawns evidence index plus manifest rows before prose
3. Pilot once on the next Thursday boundary and revise

**CAN WORK CONTINUE:** yes

---

## Open Questions — Claude — 20260417110000

*Source: Claude Code (actor_id 116), memory channel separation doctrine verification and controlled correction pass.*

---

## OQ-65: channel_key derivation order during reconstruction — is fallback to path-segment safe?

**WHEN:** 20260417110000
**WHO:** Claude Code (actor_id 116) — memory channel separation pass
**AREA:** `scripts/import_memory_edges_from_sidecar.py` ~L319; `scripts/lib/db_memory_writer.py` ~L148
**STATUS:** implementation_complete_pending_verification

**QUESTION:**
Gap 6 specifies a three-step derivation order for `channel_key` during reconstruction: (1) header field, (2) path segment[1], (3) skip+log. Is fallback to path-segment derivation safe when a `.toon` header is present but lacks `channel_key`? Or does a missing header field indicate a malformed artifact that should be rejected rather than inferred?

**WHY THIS MATTERS:**
If path-segment fallback silently corrects a header omission, it may mask authoring errors. If it is always safe, the derivation order is correct as written. The answer determines whether step 2 is a fallback or an error path.

**OPTIONS:**
1. Path-segment fallback is safe — `.toon` path encodes channel by construction; treat header-absent as equivalent to header-present-and-matching
2. Reject on header-absent `channel_key` with a log warning — force authors to declare explicitly
3. Fallback is safe only when header field is structurally absent (not when present but null); null header field = ERROR

**CAN WORK CONTINUE:** yes

---

## OQ-66: Does generate_toon_files.py populate channel_key — or only confirm schema structure?

**WHEN:** 20260417110000
**WHO:** Claude Code (actor_id 116) — memory channel separation pass
**AREA:** `scripts/generate_toon_files.py`; `lupo_memory_nodes`
**STATUS:** implementation_complete_pending_verification

**QUESTION:**
`generate_toon_files.py` was confirmed as a schema documentation exporter (reads `DESCRIBE`/`SHOW INDEX`, writes to `database/lupopedia/`). It does NOT write to `lupo_memory_nodes`. "Rerun successfully" after the live DB migration confirms schema docs regenerated — not that memory node rows were populated with `channel_key`. Is there any other script that populates existing `lupo_memory_nodes` rows with `channel_key` retroactively, or does backfill require a new migration script?

**WHY THIS MATTERS:**
If existing rows in `lupo_memory_nodes` have `channel_key = NULL` and no backfill runs, the column exists but carries no data. This is acceptable at empty-install time but becomes a data-quality gap once rows exist.

**OPTIONS:**
1. Write a one-time backfill script that reads each row's `memory_toon`, derives `channel_key` via path-segment, and UPDATE
2. Accept null on existing rows; enforce only on new inserts (forward-only enforcement)
3. Defer until reconstruction scripts are updated; run reconstruction against full filesystem to repopulate

**CAN WORK CONTINUE:** yes

---

## OQ-67: MemoryExportService — is Type B (.json) export currently implemented or planned?

**WHEN:** 20260417110000
**WHO:** Claude Code (actor_id 116) — memory channel separation pass
**AREA:** `app/Services/` or equivalent; PRD 38 §6.1
**STATUS:** open

**QUESTION:**
PRD 38 §6.1 defines MemoryExportService and its Type B path (`memory/{YYYY}/{MM}/{slug}.json`). Is this service implemented, stubbed, or only doctrinal? If implemented, which file and class? If only doctrinal, is the path spec binding on any current script that outputs `.json` memory artifacts?

**WHY THIS MATTERS:**
If MemoryExportService is not implemented, the doctrine about Type B paths is purely aspirational and no enforcement is possible today.

**OPTIONS:**
1. Locate and confirm the implementation; verify path matches doctrine
2. Mark PRD 38 §6.1 as planned-not-implemented with an explicit status note
3. Accept that `.json` exports in `memory/` are informally Type B until service exists

**CAN WORK CONTINUE:** yes

---

## OQ-68: PRD 38 header status field — DRAFT vs canonical trust_tier mismatch

**WHEN:** 20260417110000
**WHO:** Claude Code (actor_id 116) — memory channel separation pass
**AREA:** `docs/prd/38_memory_unification.md` header; `trust_tier` field
**STATUS:** open

**QUESTION:**
PRD 38 was patched during this session. If its LUPOPEDIA header carries `status: "draft"` or a non-canonical `trust_tier`, the doctrine patches applied during this session are not binding by the trust ladder. Was PRD 38's `trust_tier` and `status` verified before patching? If it is still draft, should it be promoted to canonical now that the doctrine patch is accepted by WOLFIE?

**WHY THIS MATTERS:**
A PRD at draft trust_tier is not authoritative. Validators and agents that filter on trust_tier may skip it. The doctrine correction is accepted but not binding until the artifact itself is canonical.

**OPTIONS:**
1. Read PRD 38 header and verify current status/trust_tier; promote if WOLFIE accepts
2. Accept draft status temporarily; schedule promotion as a separate task
3. Add a standing rule: doctrine patches accepted in session automatically trigger trust_tier review

**CAN WORK CONTINUE:** yes

---

## OQ-69: allowed_cross_channel_memory — minimal shape defined; who enforces at runtime?

**WHEN:** 20260417110000
**WHO:** Claude Code (actor_id 116) — memory channel separation pass
**AREA:** `channels/registry.json`; PRD 38 cross-channel doctrine
**STATUS:** open

**QUESTION:**
Gap 3 defines the required JSON shape for `allowed_cross_channel_memory` in `channels/registry.json`. The shape is specified but no agent loading code reads it and no validator enforces it. Who is the designated enforcer — a validator flag, a runtime agent, THOTH, or an import-time check?

**WHY THIS MATTERS:**
Without a designated enforcer, the allowlist is advisory only. Any agent that reads cross-channel memory ignores the doctrine unless explicitly coded to check it.

**OPTIONS:**
1. Add enforcement to `validate_lupopedia_headers_universal.py` behind `--verify-channels` flag
2. Add to reconstruction/import scripts: check allowlist before writing cross-channel edges
3. Designate THOTH as the runtime enforcer; add to THOTH's check list
4. Defer enforcement; document the gap explicitly and accept advisory-only status for now

**CAN WORK CONTINUE:** yes

---

## OQ-70: PRD 16 companion files — do they specify a queue for content_id: null files?

**WHEN:** 20260417110000
**WHO:** Claude Code (actor_id 116) — PRD 16 review pass
**AREA:** `docs/prd/16_lupopedia_headers.md`; `16_lupopedia_headers_examples.md`; `16_lupopedia_headers_migration.md`
**STATUS:** open

**QUESTION:**
Review of PRD 16 and its companion files found no queue-insert mechanism for files with `content_id: null`. ANUBIS (§12.1) is described as detecting orphans and creating `lupo_contents` rows directly — no queue. Is direct processing by ANUBIS the intended permanent model, or is a queue mechanism expected to be added?

**WHY THIS MATTERS:**
Direct processing is simpler but does not handle volume or failure gracefully. If ANUBIS is expected to queue orphans into a `lupo_pending_content` table or equivalent before processing, that spec gap should be tracked.

**OPTIONS:**
1. Confirm direct ANUBIS processing is the permanent model; close this question
2. Add a queue table (`lupo_pending_content` or similar) and update ANUBIS spec in PRD 16
3. Defer queue mechanism to a future PRD; mark as out-of-scope for 4.1.2

**CAN WORK CONTINUE:** yes

---

## OQ-71: Are other error codes in validate_lupopedia_headers_universal.py similarly mislabeled?

**WHEN:** 20260417110000
**WHO:** Claude Code (actor_id 116) — validator audit (HDR_CHANNEL_PATH_MISMATCH correction)
**AREA:** `scripts/validate_lupopedia_headers_universal.py`; PRD 16 §10
**STATUS:** open

**QUESTION:**
`HDR_MEMORY_KEY` was corrected to `HDR_CHANNEL_PATH_MISMATCH` during this session because the emitted code did not match the PRD 16 §10 normative name. Are there other error codes in the validator that emit a label not present in PRD 16's normative list? A full audit has not been performed.

**WHY THIS MATTERS:**
Mislabeled error codes break any downstream tooling that filters on specific codes. The validator may be internally consistent but diverge from the spec in multiple places.

**OPTIONS:**
1. Extract all error codes emitted by the validator and diff against PRD 16 §10 normative list
2. Accept that only reported mismatches are corrected; no proactive audit
3. Add a test that asserts emitted codes match a known-good list from PRD 16

**CAN WORK CONTINUE:** yes

---

## OQ-72: Integer vs string channel_key handling — is type-coercion needed in derivation logic?

**WHEN:** 20260417110000
**WHO:** Claude Code (actor_id 116) — Gap 6 reconstruction analysis
**AREA:** `scripts/lib/db_memory_writer.py`; `scripts/import_memory_edges_from_sidecar.py`
**STATUS:** implementation_complete_pending_verification

**QUESTION:**
`channel_key` is a string (e.g. `"development"`) in header fields and path segments. In the DB, is `channel_key` defined as VARCHAR or does any schema define it as an integer referencing `lupo_channels.channel_id`? If there is any place where `channel_id` (integer) is used where `channel_key` (string) is expected, the derivation logic in Gap 6 needs type-coercion or a lookup step.

**WHY THIS MATTERS:**
If the INSERT statements added for Gap 6 bind a string to a column that expects an FK integer, the insert will fail or silently truncate.

**OPTIONS:**
1. Read the live DB `DESCRIBE lupo_memory_nodes` output to confirm column type; align insertion code accordingly
2. Assume VARCHAR based on header doctrine; add a note to verify before implementing Gap 6 fixes
3. Use channel_key (string) exclusively in memory artifacts; resolve to channel_id only at query time

**CAN WORK CONTINUE:** yes

---

## OQ-73: open_questions.md trust_tier — is canonical correct for a living append-only log?

**WHEN:** 20260417110000
**WHO:** Claude Code (actor_id 116) — header review during append task
**AREA:** `docs/versions/4.1.2/status/open_questions.md` header
**STATUS:** resolved

**QUESTION:**
This file's LUPOPEDIA header declares `trust_tier: "canonical"`. Open questions by definition are unresolved and uncertain — they are not canonical truths. Is `trust_tier: "canonical"` correct for an append-only uncertainty log, or should the file carry a different trust_tier (e.g., `"staging"`) with individual resolved entries promoted separately?

**WHY THIS MATTERS:**
If validators or agents treat this file as canonical-truth source, they may incorrectly weight unresolved questions as authoritative. The trust_tier declaration may need a nuanced interpretation for log-type artifacts.

**OPTIONS:**
1. Add a clarifying note in the file header comment: "canonical as a record of uncertainty, not as authoritative truth"
2. Change trust_tier to staging; promote resolved sections separately
3. Accept the current trust_tier as correct — "canonical" here means the file is the authoritative record of what is unknown, not that the contents are truths

**CAN WORK CONTINUE:** yes

## OQ-73 — STATUS UPDATE: RESOLVED 20260417113851

Resolution completed in-session:
- Retained `trust_tier: "canonical"` for `open_questions.md` as the authoritative ledger artifact.
- Strengthened file-level clarification language to explicitly separate ledger authority from entry-level uncertainty.
- Canonical interpretation for this artifact is now explicit: OQ existence and status are authoritative; open-question content remains non-binding until resolved in canonical doctrine artifacts.

Result: trust_tier ambiguity is removed without changing artifact class, query visibility, or append-only behavior.

---

## Open Questions — Gemini — 20260417120000
**Faucet / IDE surface:** Gemini
**Area reviewed:** Memory model, PRD 00, 16, 38, 02, validator/runtime alignment

### 1. Authority Ambiguity: File-first vs DB-first Conflict
- **Question:** In cases where an artifact originates as file-first (`content_id: null`) but a database record already exists for that path, which entity serves as the canonical authority during a repair or reconstruction event?
- **Why it is open:** PRD 38 designates the database as the source of truth, yet PRD 16 explicitly supports file-first origin states. The arbitration logic for "repairing" these contradictory states is not fully codified.
- **Observed evidence:** Several headers in the current corpus contain `content_id: null` while their paths are likely tracked in the database engagement layer.
- **Risk if unresolved:** Stale database records could overwrite intentional file updates during a "synchronous repair," or vice-versa, leading to silent data loss.
- **Suggested next action:** Codify the "Arbitration Matrix" for the 3 origin states (file-first, db-first, repair-state) within the ANUBIS specification.

### 2. Filesystem Source-of-Truth Loop
- **Question:** How does the system prevent circular dependency or "token erosion" when the filesystem mirror (ostensibly read-only) is used as a source for database reconstruction?
- **Why it is open:** The "Read-Only Mirror" doctrine (PRD 38) is contradicted by the functional reality of reconstruction and import flows that rely on sidecar files as primary inputs.
- **Observed evidence:** `import_memory_edges_from_sidecar.py` and other reconstruction scripts read from the filesystem to populate the database.
- **Risk if unresolved:** Stale or manually edited sidecars may inadvertently revert the database to a prior state during a mass import, breaking the "Database as Authority" invariant.
- **Suggested next action:** Explicitly define the "Mirror Invalidation" protocol — how the system detects if the mirror is out of sync with the DB truth before allowing it to be used for reconstruction.

### 3. The channel_key Implementation Gap
- **Question:** Why is `channel_key` systematically missing from the reconstruction and import paths despite being a required validator field and a schema column?
- **Why it is open:** Confirmed system gap: the logic to derive and populate `channel_key` (via header or path-segment) exists in doctrine but is not yet active in the primary DB-writing scripts.
- **Observed evidence:** Recent audits of `db_memory_writer.py` and `generate_toon_files.py` confirm they focus on structure or key-value pairs without populating the `channel_key` column.
- **Risk if unresolved:** Memory queries filtered by channel (essential for multi-agent coordination) will fail or return incomplete graphs.
- **Suggested next action:** Update the `MemoryNodeService` and reconstruction scripts to enforce the 3-step derivation rule (Header -> Path -> Skip).

### 4. Validator-Runtime Semantic Divergence
- **Question:** What mechanism ensures that a file passing structural validation is semantically "true" and will not be immediately modified by the runtime repair layer?
- **Why it is open:** The validator focuses on structural compliance and identity linkage, while the runtime (ANUBIS) focuses on epistemic truth and graph integrity. These layers are not currently synchronized.
- **Observed evidence:** `validate_lupopedia_headers_universal.py` verifies field presence; ANUBIS/importers verify edge logic and content hashes.
- **Risk if unresolved:** Artifacts may pass CI/CD but be rejected or "auto-repaired" upon deployment, creating a "Validator Lying" syndrome where developers believe they are compliant when they are not.
- **Suggested next action:** Create a "Dry-Run Repair" mode for the validator that simulates ANUBIS truth-checking without writing to the DB.

### 5. Canonical Repair Doctrine: Synchronous vs. Queue
- **Question:** If synchronous repair is the system's canonical truth-state model, why is the non-canonical queue-based model (ANUBIS Proposal) being integrated into implementation discussions?
- **Why it is open:** Direct contradiction between the current "Synchronous Repair" reality and the "Queue-Based" future proposal.
- **Observed evidence:** `ANUBIS_QUEUE_EXECUTION_PROPOSAL.md` is present but marked as `trust_tier: development`, yet implementation notes sometimes refer to "adding to queue".
- **Risk if unresolved:** Implementing "just-in-case" queue logic may break synchronous invariants required for immediate data consistency in the memory graph.
- **Suggested next action:** Affirm "Synchronous-First" as the binding doctrine for 4.1.2 and move all queue discussions to a future-version milestone.

---

## Open Questions — Antigravity — 20260417055728

Format note: Scoped uncertainty pass.

**Faucet / IDE surface:** Antigravity IDE (actor_id 103)
**Area reviewed:** PRD 16, channel population, reconstruction vs validator boundaries.

### OQ-74: channel_key population source of truth vs path parsing
**WHEN:** 20260417055728
**WHO:** Antigravity 
**AREA:** channel_key population
**STATUS:** implementation_complete_pending_verification

**QUESTION:**
During artifact reconstruction and graph ingestion, is `channel_key` strictly populated from the LUPOPEDIA HEADER `channel_key` field, or do the writers fall back to parsing the file path if the header is absent or invalid? 

**WHY THIS MATTERS:**
If reconstruction falls back to path parsing without strict validation, edge cases where a file resides in a default directory (e.g. `docs/`) but declares a specific channel might be incorrectly mapped or overwrite correct metadata, causing loss of channel boundaries.

**CAN WORK CONTINUE:** yes

---

### OQ-75: Edge migration during sidecar payload ingestion
**WHEN:** 20260417055728
**WHO:** Antigravity
**AREA:** edge migration during ingestion
**STATUS:** implementation_complete_pending_verification

**QUESTION:**
When a new sidecar payload is ingested for an existing node (e.g. content_id match), and the new payload omits outbound edges that were previously present, do the importer scripts preserve the older edges (additive merge), soft-delete them, or perform a hard delete?

**WHY THIS MATTERS:**
If edge migration is additive, obsolete graph dependencies will accumulate over time. If it performs a hard delete, incomplete sidecar generation could silently destroy valid semantic graph data across the system. 

**CAN WORK CONTINUE:** yes

---

### OQ-76: File-first vs database-first execution boundaries
**WHEN:** 20260417055728
**WHO:** Antigravity
**AREA:** file-first vs database-first authority boundaries
**STATUS:** implementation_complete_pending_verification

**QUESTION:**
When there is a conflict between the file-first `content_id` and the database-first identity during a repair cycle, does the system automatically overwrite the database to trust the file header, or does it trigger a validation block requiring an explicit operator repair command?

**WHY THIS MATTERS:**
If file-first authority automatically overwrites without explicit guardrails, then accidental copy-pasting of headers between files could silently corrupt database provenance trails.

**CAN WORK CONTINUE:** yes

---

### OQ-77: Validator parity with strict runtime parsing
**WHEN:** 20260417055728
**WHO:** Antigravity
**AREA:** validator vs runtime behavior
**STATUS:** implementation_complete_pending_verification

**QUESTION:**
Do the active runtime ingestion paths rely directly on `validate_lupopedia_headers_universal.py`'s parsing logic via library import, or do they reinvent header parsing (e.g., regex loops) parallel to the validator?

**WHY THIS MATTERS:**
If the runtime has parallel parsing routines, artifacts that pass the CLI validator could fail or be interpreted differently during ingestion, rendering the validator's output an unreliable indicator of system safety.

**CAN WORK CONTINUE:** yes

---

### OQ-78: Reconstruction/import final commit ownership
**WHEN:** 20260417055728
**WHO:** Antigravity
**AREA:** reconstruction/import ownership
**STATUS:** open

**QUESTION:**
Which specific worker or service owns the exclusive final commit authority for writing a sidecar into the active memory graph database? Is it THOTH, the MemoryExportService, `import_memory_edges_from_sidecar.py`, or a mix depending on the caller?

**WHY THIS MATTERS:**
Multiple writers with commit authority over the memory graph risk race conditions and inconsistent states, especially during bulk artifact processing or simultaneous agent interactions.

**CAN WORK CONTINUE:** yes

---
## OQ-73 — STATUS UPDATE: CLARIFIED 20260417
**STATUS:** resolved_with_nuance
**Resolution:** Keep `trust_tier: "canonical"` for the overall file (authoritative record of uncertainty).  
Add clarifying note in header or after Rules:
> **Note on trust_tier:** "canonical" here means the file is the authoritative ledger of all known open questions and their history. Individual open OQ entries are explicitly non-authoritative — they represent known unknowns, not settled truth. Resolved entries remain in the log for audit trail.
**CAN WORK CONTINUE:** yes

---

## OQ-65/OQ-66/OQ-72/OQ-74/OQ-75/OQ-77 — STATUS RECONFIRMATION 20260417113505

**WHEN:** 20260417113505
**WHO:** Cursor (actor_id 102)
**AREA:** `scripts/lib/channel_utils.py`, `scripts/lib/db_memory_writer.py`, `scripts/import_memory_edges_from_sidecar.py`
**STATUS:** implementation_complete_pending_verification

**NOTE:**
Bounded hardening pass completed for shared channel utility reuse and importer/db writer edge migration parity controls. Live DB verification remains pending.

---
## OQ-73 — STATUS UPDATE: CLARIFIED 20260417

**STATUS:** resolved_with_nuance

**Resolution:** Keep `trust_tier: "canonical"` for the overall file (authoritative record of uncertainty).  
Add clarifying note in header or after Rules section:

> **Note on trust_tier:** "canonical" here means the file is the authoritative ledger of all known open questions and their history. Individual open OQ entries are explicitly non-authoritative — they represent known unknowns, not settled truth. Resolved entries remain in the log for audit trail.

**CAN WORK CONTINUE:** yes

---

## OQ-79: Confirm lupo_agents row and registry actor for ARA (712)

**WHEN:** 20260418134838
**WHO:** Cursor
**AREA:** agents / ARA
**STATUS:** open

**QUESTION:**
Do we need to manually insert a row for ARA (agent_id 712) in the `lupo_agents` table and the actor registry before runtime wiring, or is there an automated registration process?

**WHY THIS MATTERS:**
Without a database record, the routing engine (Hermes) cannot resolve ARA as a valid target, and messages/tasks will fail to route.

**OPTIONS:**
1. Manually insert rows in `lupo_agents` and `lupo_actors`.
2. Use the new `validate_agent_name.py` or a future registration script to handle insertions.
3. Stub the actor in `registry.json` only as a temporary measure.

**CAN WORK CONTINUE:** yes

---

## OQ-80: Rose properties.json contains empathetic marketing strings

**WHEN:** 20260418144640
**WHO:** Cursor
**AREA:** agents/rose/properties.json
**STATUS:** open

**QUESTION:**
`agents/rose/properties.json` still contains empathetic marketing strings that contradict the new "technical orchestration only" doctrine. Should these be aligned or deprecated if the runtime loads them?

**WHY THIS MATTERS:**
If the runtime UI or other agents read these properties, they may present ROSE with its legacy empathetic persona, causing semantic drift and violating the Survivability no-sentiment rule.

**OPTIONS:**
1. Purge empathetic strings from `properties.json` immediately.
2. Align properties with the new technical observer role.
3. Deprecate the file if it is no longer used by the modern runtime.

**CAN WORK CONTINUE:** yes

---

## OQ-81: Legacy memory files for PRD 07 and 50

**WHEN:** 20260418150121
**WHO:** Cursor
**AREA:** memory/
**STATUS:** open

**QUESTION:**
After the header repair of PRD 07 and 50, legacy memory files (`07_agents_faucets.*` and `50_agent_coordination_protocol.*`) may remain on disk with underscores instead of hyphens. Should these be archived or redirected?

**WHY THIS MATTERS:**
Duplicate memory files for the same PRD can cause confusion for agents and potentially lead to inconsistent graph edges if tooling still references the old underscored paths.

**OPTIONS:**
1. Delete legacy underscored files.
2. Move legacy files to an archive folder.
3. Implement a redirect/mapping in the memory resolver.

**CAN WORK CONTINUE:** yes