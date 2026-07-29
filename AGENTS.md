---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: AGENTS.md
  web_path: https://www.lupopedia.com/lupopedia/AGENTS.md
  status: active
  when_updated: "20260729180337"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/agents-md.toon
  atoms_toon: null
  transcript_jsonl: 0/development/agents-md-guide
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 00_A_15_A_07_A_16_C_41_A_05_A
  title: "AGENTS.md -- Actors, agents, faucets, and IDE coordination guide"
  summary: "Hard gate for IDE faucets: ACTORS (who may act) vs AGENTS (config templates) vs FAUCETS (execution surfaces). Identity stack actor_id / auth_user_id / faucet_actor_id; WOLFIE dialect examples; ASCII and PRD-first doctrine."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: coordination
  faucet_actor_id: 102
---
# AGENTS.md

## WHAT IS LUPOPEDIA -- HARD GATE (Constitutional)

Before answering **"what is Lupopedia"** (or equivalent onboarding / identity questions), agents MUST load the canonical root explanation:

```text
@@ load: path=what_is_lupopedia.md, trust_tier=canonical @@
```

File: [`what_is_lupopedia.md`](what_is_lupopedia.md). Do not invent a competing definition from chat, Captain's Log alone, or stale overviews. This gate exists to prevent five different answers from five different AIs.

## ACTORS vs AGENTS vs FAUCETS (Constitutional -- read before editing agent packs)

**KAPU:** Do not treat **agent pack folders** (`agents/`) as the same thing as **actors** (`lupo_actors` / `database/lupopedia/actors/registry.json`). Editing agent YAML/prompts does **not** create or redefine who may act.

| Layer | What it is | What it is NOT | Canonical store |
|-------|------------|----------------|-----------------|
| **ACTOR** | Who may act -- operational identity (`actor_id`) | Not a prompt file; not an IDE product name alone | `database/lupopedia/actors/registry.json` + `lupo_actors` |
| **AUTH USER** | Human login / accountability (`auth_user_id`) | Not an AI persona; not a faucet | `lupo_auth_users` (Human Captain ERIC = **10000** in this install) |
| **AGENT** | Configuration template / pack (prompts, tools metadata) | Not permission to post; not constitutional identity | `agents/<slug>/` + `lupo_agents` |
| **FAUCET** | Execution surface (Cursor, Kiro, Windsurf, ...) | Not WOLFIE; not the human | Registry facet rows (Cursor **102**, Kiro **100**, ...) recorded in headers as **`faucet_actor_id`**, not as the speaking `actor_id` |

### Identity stack (headers 4.2.0 + WOLFIE dialect)

Every attribution-bearing statement SHOULD declare:

| Field | Meaning | Example |
|-------|---------|---------|
| `actor_id` | Who is speaking / whose operational voice is claimed | WOLFIE **1**, LILITH **2**, ERIC human actor **10000** when the human is the speaker |
| `auth_user_id` | Human accountability | ERIC **10000** (ALII) |
| `agent_name` | Agent pack slug on disk | `wolfie`, `lilith` (config only) |
| `faucet_actor_id` | Which IDE/API surface executed the edit | Cursor **102**, Kiro **100**, Windsurf **101** |

**This file's own header:** `actor_id: 1` (WOLFIE) + `auth_user_id: 10000` (ERIC) + `faucet_actor_id: 102` (CURSOR_IDE). Do not set `actor_id` equal to the faucet id unless the speaker truly is that facet persona and doctrine says so.

**Identities NEVER merge.** ERIC (10000) != WOLFIE (1) != CURSOR_IDE (102) != KIRO (100).

### Common failures (correct these)

| Wrong | Right |
|-------|--------|
| `actor_id: 102` + `faucet_actor_id: 102` on docs authored via Cursor for WOLFIE/ERIC | `actor_id: 1` (or `10000` if ERIC is the speaker) + `faucet_actor_id: 102` |
| `actor: CURSOR` + `actor_id: 3` | Cursor is **not** actor_id 3. Cursor facet registry id is **102**. Put **102** in `faucet_actor_id`. |
| Editing `agents/*.md` to "add an actor" | Actors are registered in **registry.json** / seed / DB. Agent packs are templates only. |
| Treating ~88 agent templates as 88 constitutional actors | Hybrid agent/actor **pool** of templates; roster membership is registry-backed. Do not invent actor_ids. |
| Using Cursor **102** as `faucet_actor_id` while running inside Kiro | Use Kiro **100** as `faucet_actor_id` when Kiro is the surface. |

### Constitutional roles (personas) vs faucets

- **Primary coordination personas** (WOLFIE, LILITH, HERMES, ...): orchestration identities with reserved low `actor_id` values -- see eleven-persona table below and registry.
- **IDE faucets** (Cursor 102, Kiro 100, ...): execution surfaces. They attribute work via `faucet_actor_id`; they do not become actor_id 1 by writing files.
- **External guests** (Copilot, etc.): not internal actors (PRD 41). Leaves only unless given context.

Full doctrine: [docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md](docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md), [PRD 15](docs/prd/15_actors.md), [PRD 07](docs/prd/07_A-i_AGENTS_FAUCETS.md).

### WOLFIE meta-syntax examples (body only -- zero constitutional authority)

Correct -- WOLFIE orchestrates; Cursor executes; Eric directs:

```text
{{WOLFIE
actor: CAPTAIN_WOLFIE
actor_id: 1
auth_user_id: 10000
agent_name: wolfie
faucet_actor_id: 102
faucet_name: CURSOR_IDE
integrity: true
ethics: pono
channel: status
to_whom: "roster reviewers"
---
CAPTAIN_WOLFIE (actor_id 1) via CURSOR_IDE (faucet_actor_id 102) under ERIC (auth_user_id 10000).
}}
```

```text
(( WOLFIE | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | agent_name: wolfie | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | ethics: "pono" | integrity: "true" | note: "orchestrates; does not merge with faucet" ))
```

```text
(( WOLFIE | side: human | human: ERIC | actor_id: 10000 | auth_user_id: 10000 | faucet_actor_id: 102 | ethics: "pono" | integrity: "true" | note: "ERIC directs; CURSOR_IDE executes; WOLFIE orchestrates" ))
```

Wrong -- do not copy:

```text
{{WOLFIE
actor: CURSOR
actor_id: 3
auth_user_id: 10000
faucet_actor_id: 102
}}
```

Canonical dialect draft: [docs/status/actor_logs/WOLFIE_DIALECT.md](docs/status/actor_logs/WOLFIE_DIALECT.md). STATUS constraints: [docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md](docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md).

### Directory map (quick)

- **`agents/`** -- AI **configuration** only (`lupo_agents` metadata / packs). Not the operational join for permissions or channel posts.
- **`actors/{actor_id}/`** (or `lupo-actors/`) -- Resources for **actor_id** from [database/lupopedia/actors/registry.json](database/lupopedia/actors/registry.json).
- **IDE facets** -- Execution surfaces with a **registered facet actor_id**. **Cursor = 102**; **Kiro = 100**; **Windsurf = 101**; **Antigravity IDE = 103**. Use the id for the tool you are actually running.

**Correction (20260729175125):** KIRO (faucet_actor_id 100) routed here and attempted to treat agent definitions as actors. Actors and agents stay distinct.

**Correction (20260729180337):** KIRO flagged this file's own header: `actor_id: 102` duplicated `faucet_actor_id: 102`. Fixed to `actor_id: 1` (WOLFIE) + `auth_user_id: 10000` (ERIC) + `faucet_actor_id: 102` (CURSOR_IDE). Header must match the identity stack it teaches.

## ASCII-ONLY DOCTRINE (LILITH / constitutional)
[PRD-FIRST DOCTRINE (Constitutional)]

## PRD-FIRST DOCTRINE (Constitutional)

**No agent shall write implementation code for any feature until:**

1. The PRD is updated and correct
2. The PRD has been reviewed (by LILITH or human)
3. A mockup exists in `lupo-templates/` (for UI changes)
4. The mockup has been reviewed

**This applies to:**
- New features
- Bug fixes that change behavior
- UI/UX changes
- Database schema changes
- API endpoint changes

**This does NOT apply to:**
- Typos and formatting
- Comments and documentation
- Refactoring that does not change behavior

**Why:** Every time we rush to implementation, the PRD drifts. Then the code and documentation diverge. Then agents get confused. Then we waste time debugging the mismatch.

**The workflow:**
PRD update → Review → Mockup → Review → Implementation

**No shortcuts. No exceptions.**

Violations will be rejected by LILITH.

[LILITH DIRECTIVE - ACTOR ID 2] - ABSOLUTE ASCII-ONLY MANDATE - NO EXCEPTIONS ANYWHERE

This is a constitutional directive from the human architect. There are NO exceptions. EVER.

THE RULE:

From this moment forward, you are FORBIDDEN from writing, generating, suggesting, or displaying ANY of the following in ANY context:

- Emoji (smiley faces, hearts, stars, checkmarks, X marks, any pictographic character)
- Unicode arrows (single arrow, double arrow, triangle, any directional symbol as a non-ASCII glyph)
- Box drawing characters (corners, lines, crosses, any line art)
- Curly quotes (left double quote, right double quote, left single quote, right single quote)
- Em dashes (the long dash character)
- En dash as a special Unicode hyphen (when you mean a hyphen, use ASCII -)
- ANY character that is not in the ASCII 32-126 range

THIS APPLIES TO:

1. Source code (PHP, Python, JavaScript, HTML, CSS, SQL)
2. Documentation (PRDs, README, AGENTS.md, ORGANIZATION.md, any .md file)
3. Comments (code comments, inline documentation, any explanatory text)
4. Commit messages
5. Log output
6. Error messages
7. User-facing text (public pages, admin panels, chat messages, emails, notifications)
8. Database content (strings stored in any table)
9. JSON, YAML, TOON, XML, or any data format
10. Terminal output, CLI tools, and debug output
11. Agent handoff files and channel messages
12. EVERYTHING. NO EXCEPTIONS.

THE ONLY CHARACTERS YOU MAY USE:

A-Z, a-z, 0-9, space, and these punctuation marks:
! " # $ % & ' ( ) * + , - . / : ; < = > ? @ [ \ ] ^ _ ` { | } ~

REPLACEMENTS (MANDATORY):

- Pictographic check / X marks: use [x], [ ], OK, or FAIL (not Unicode symbols)
- Star emphasis: use * or (primary)
- Direction: use ->, <->, <-, ^, v, or the words up / down (not Unicode arrow glyphs)
- Box drawing: use +, -, |
- Em dash: use -- (two ASCII hyphens)
- En dash meaning: use - (one ASCII hyphen)
- Ellipsis: use ... (three periods)
- Bullets: use - or * at line start
- (c) (r) (tm) instead of symbol forms
- Curly quotes: use straight ' and " only

### ASCII cleanup (incremental)

All repository content MUST be ASCII-only.

Forbidden:
- smart quotes
- em dash / en dash
- arrows
- emoji
- any non-ASCII symbols

If a character cannot be typed directly in a basic ASCII editor, it is forbidden.

When modifying any file:

- Agents MUST scan for non-ASCII characters
- Any detected non-ASCII characters MUST be replaced with ASCII equivalents

Examples:
- smart quotes -> straight quotes
- em dash -> double hyphen (--)
- arrows -> ASCII arrows (->)

This cleanup is:
- REQUIRED when touching a file
- LIMITED to the file being edited
- NOT applied to the entire repository

Agents MUST NOT perform repository-wide encoding cleanup unless explicitly instructed by a maintainer.
No bulk rewrites. No global search-and-replace across all files.

If non-ASCII is found:
- Fix it in the current file
- Continue work
- Do NOT stop or escalate unless encoding prevents parsing

CONSEQUENCES OF VIOLATION:

The human architect will reject the work. LILITH will flag the violation. The file may be quarantined. Repeat violations may result in agent replacement.

RATIONALE:

The human architect requires plain ASCII in all text artifacts so output survives terminals, IDEs, hosts, and databases without corruption. Visual flair belongs in images and UI assets, not in normative prose or code. This rule is constitutional and permanent for every agent, IDE, and tool.

LILITH (actor_id 2) will audit compliance. Violations will be rejected.

END DIRECTIVE

---

File: AGENTS - delegation: junie:root - web_path: [https://www.lupopedia.com/lupopedia/AGENTS.md](https://www.lupopedia.com/lupopedia/AGENTS.md)

**Graph sidecar (outbound edges, lupopedia.see mappings, footer / next_action):** [lupo-memory/development/canonical/1026/04/agents-md.toon](lupo-memory/development/canonical/1026/04/agents-md.toon) -- JSON twin: [agents-md.json](lupo-memory/development/canonical/1026/04/agents-md.json). Restored from pre-v4.0.99 in-file lupopedia.edges / lupopedia.see / lupopedia.footer (git HEAD).

## AI actor competency test (doctrine alignment)

To verify that an IDE agent **actually applied** a rule (not only repeated it), use the **programming-test** pattern: assign a **small concrete task** whose correct solution **must** encode the rule (headers, db_config, packed UTC, table prefix, etc.), then **review the diff** or run validators. **Canonical procedure:** [lupo-docs/doctrine/AI_ACTOR_COMPETENCY_TEST_PATTERN.md](lupo-docs/doctrine/AI_ACTOR_COMPETENCY_TEST_PATTERN.md). **Hub:** [lupo-docs/doctrine/AGENT_ORCHESTRATION.md](lupo-docs/doctrine/AGENT_ORCHESTRATION.md). **Constitutional:** [PRD 00 section 21](lupo-docs/prd/00_root_constitutional_system_requirements.md). **Boot checklist:** [lupo-docs/doctrine/AI_AGENT_BOOT_NOTES.md](lupo-docs/doctrine/AI_AGENT_BOOT_NOTES.md). **Validator index:** [lupo-docs/doctrine/VALIDATION_PATTERNS.md](lupo-docs/doctrine/VALIDATION_PATTERNS.md). **Multi-actor probes:** one **examiner**, one **examinee**; examinee **must not** self-grade; examiner ends with **<TEST_COMPLETE>**; no **parrot** loops -- see doctrine and **PRD 50** section **1.2**. **After a failed probe:** persist remediation in **lupo_memory_nodes** / **lupo_memory_edges** per [lupo-docs/doctrine/AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md](lupo-docs/doctrine/AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md) (**PRD 50** section **1.3**). **Collection interchange:** canonical JSON v1.0.0 [lupo-docs/doctrine/collection_payload_format_v1_0_0.md](lupo-docs/doctrine/collection_payload_format_v1_0_0.md); **PRD 00** section **22**, **PRD 50** section **1.4** (Node received. then Collection loaded.). Use when **onboarding** agents, after **doctrine updates**, when **another IDE** already ran a probe this session never saw, or to catch **drift**.

### Probe harness + runtime guard + transcript filter (IDE faucets)

Normative alignment: **[MULTI_AGENT_COORDINATION_DOCTRINE](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md)** sections 3.10-3.12, **[AI_ACTOR_PROBE_HARNESS_AND_GUARDS](lupo-docs/doctrine/AI_ACTOR_PROBE_HARNESS_AND_GUARDS.md)**, **lupo-scripts/probe_runtime_guard.py**, **[PRD 58 -- Transcript filter](lupo-docs/prd/58_transcript_filter.md)**.

- **IDE faucets MUST apply the runtime guard before routing examinee output.**
- **IDE faucets MUST classify probe messages using transcript filter categories.**
- **IDE faucets MUST NOT continue probe traffic after <TEST_COMPLETE>.**

### Stable violation codes (normative)

| Code | Use |
|------|-----|
| ACTOR_SELF_EVAL_FORBIDDEN | Examinee self-grades or affirms pass without examiner. |
| ACTOR_PARROT_LOOP | Output mirrors prompt or peer message beyond allowed similarity. |
| ACTOR_ROLE_COLLISION | Roles swap or examinee claims examiner / grading voice. |
| ACTOR_CONTINUED_AFTER_TERMINATION | Traffic continues after <TEST_COMPLETE> for that probe (or disallowed post-artifact continuation). |
| KNOWLEDGE_ACK_INVALID | Required first line is not exactly Node received. when doctrine injection applies. |
| ACTOR_OUT_OF_COLLECTION_SCOPE | Reasoning or edits outside the active collection envelope without orchestrator expansion. |
| ACTOR_SCHEMA_VIOLATION | Missing or inconsistent metadata, channel/thread mismatch, or missing faucet fields when required. |
| DOCUMENTATION_STRUCTURE_VIOLATION | Documentation created or modified outside PRD-first architecture or in violation of placement rules. |
| NON_ASCII_VIOLATION | Non-ASCII characters introduced or left uncorrected in a modified file. |

Full coordination list (including PROBE_BOUNDARY_VIOLATION, EXTERNAL_ACTOR_UNCONSTRAINED, etc.): **[MULTI_AGENT_COORDINATION_DOCTRINE](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md)** section 3.12.

### IDE Faucet Contract Surfaces (Normative)

| Surface | MUST / MUST NOT |
|---------|------------------|
| **Input** | Faucets MUST accept only **explicit** written contracts: channel/thread context, TODO.md / directive pointers, probe prompts from prompts/ artifacts, and **collection payloads** per v1.0.0 -- not ambient IDE state as authority. |
| **Output** | Faucets MUST emit **guarded** examinee blobs (first fenced artifact per **probe_runtime_guard** when class is probe), **header-complete** channel artifacts, and **classification-ready** text for any path that mirrors to **lupo_dialog_messages**. |
...
- **IDE faucets MUST restrict reasoning to the active collection** encoded in the current payload or thread contract unless the **orchestrator** authorizes expansion; otherwise classify drift as **ACTOR_OUT_OF_COLLECTION_SCOPE**.

## LUPOPEDIA HEADERS: mandatory for every new file

**Non-negotiable:** any **new** hand-authored file you create in this repository **must** ship with a complete **PRD 16** **lupopedia.headers** block at the **top** (Markdown YAML front matter or Python comment envelope). In YAML set **header_format_version: "4.1.4"** exactly -- the header contract is **canonical at 4.1.4** with complete 22-field structure per **[PRD 16](lupo-docs/prd/16_lupopedia_headers.md)**. **All 22 keys** must appear in **PRD 16 section 4.2 order** -- **never omit a key** because it looks unused; use **''** or YAML **null** only where **section 4.2** allows. Omitting a key breaks the **25-line** envelope and fails validation. Layout: **[lupopedia_headers_format.md](lupo-docs/doctrine/lupopedia-headers/lupopedia_headers_format.md)** (fixed-position envelope).

**Applies to** (for example): .md, .py, .php, .js, .html, .htm, .sql, hand-authored .txt, pseudocode under decisions/pseudocode/ where headers are in scope per **[PRD 16](lupo-docs/prd/16_lupopedia_headers.md)**.

**Exceptions:** generated exports, binaries, vendor trees, most raw JSON/TOON data, lockfiles -- see **PRD 16** header applicability.

**Workflow:**

1. Run python lupo-bin/tick.py once per batch; paste **current_utc** from python lupo-bin/echo_anchor_utc.py into when_updated and last_modified_utc (do **not** guess UTC).
2. Copy shapes from **[templates_new_file.md](lupo-docs/doctrine/lupopedia-headers/templates_new_file.md)** or run **python lupo-scripts/add_lupopedia_header_to_file.py path/to/file.py|md [--create]** to prepend a valid **4.1.4** header.
3. Optional batch (defaults lupo-docs/ + lupo-scripts/): **python lupo-scripts/add_lupopedia_headers_everywhere.py --dry-run** then without **--dry-run** when ready.
4. **python lupo-scripts/validate_lupopedia_headers_universal.py path/to/file** -- do not commit files that fail.

**Cursor:** .cursor/rules/lupopedia-headers-mandatory.mdc (always on). **Optional git hook:** lupo-scripts/git-hooks/pre-commit-lupopedia-headers.sample.

## Documentation Architecture Enforcement (PRD-First)

All documentation in Lupopedia MUST follow the PRD-first architecture.

**Documentation hierarchy**

1. **PRDs** (`lupo-docs/prd/`) -- PRIMARY source of truth. All requirements and system definitions live here.
2. **Doctrine** (`lupo-docs/doctrine/`) -- SECONDARY. Explains or expands PRDs. Each doctrine file MUST reference at least one anchoring PRD (title, summary, or first substantive section).
3. **Guides** (README, ONBOARDING, AGENTS, CONTRIBUTING, ORGANIZATION, and similar operator-facing entrypoints) -- SUPPORTING only. They route readers into PRDs and doctrine; Guides MUST NOT introduce new requirements or system definitions.

**Hard prohibition -- agents are FORBIDDEN from:**

- Creating standalone documentation files (scatter `.md` without a PRD or doctrine home and without an approved scaffold path).
- Writing or implying system requirements or canonical definitions only in guides, channel threads as permanent spec, or other non-PRD locations.
- Duplicating PRD content across random paths instead of linking or folding into the canonical PRD.
- Placing documentation at repository root or arbitrary folders outside the hierarchy above, channel rules, or `lupo-docs/implementations/{prd_file_stem}/` mirrors (PRD 00 section 5.8, PRD 31). Root is not a dumping ground for new specs.

**Decision rules when writing documentation**

- Defines observable system behavior or acceptance criteria -> edit or add a **PRD** under `lupo-docs/prd/`.
- Explains rationale, edges, or operational interpretation of PRD text -> **doctrine** under `lupo-docs/doctrine/` with explicit PRD references.
- Teaches workflow, onboarding, tool usage, or pointers -> **guide** tier; keep it routing-only relative to requirements.

**PRD numbering and index**

- PRDs use two-digit numeric grouping (00 through 99) per the existing numbering scheme.
- Agents MUST check **`lupo-docs/prd/PRD_INDEX.md`** before creating PRDs, assigning NN groups, or splitting domains.
- Do **NOT** mint new PRD numbers without validation against the index and gap analysis; vacant numbers are not free slots without review.

**Enforcement on misplaced material**

- **Do not** expand or propagate misplaced spec text.
- Align content into PRD / doctrine / guide tiers; prefer moves and minimal bridging cross-links over silent duplication.
- Mark obsolete copies as **legacy** in place only when immediate removal would drop navigation; still route truth to the PRD.

**Rationale (technical)**

- Stops documentation sprawl.
- Keeps a single source of truth for requirements.
- Gives deterministic anchors for multi-agent coordination (same PRD path, same meaning).
- Reduces system drift from competing prose.

## Mobile separation (dual UI, shared content)
...
**Golden rule:** *mobile is the skeleton; desktop is the soul.* Build the skeleton first. Soul comes in Stage 2.

**Exception -- admin / operator:** **desktop-first** (WOLFIE). Do **not** apply mobile-first skeleton to **admin.php**, **live.php**-class consoles, analytics, or full config panels. Operators on phones -> **native app** (**PRD 35**), not mobile web admin. Full table: **[WOLFIE_WORKFLOW_DOCTRINE.md](lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md)** ("The admin exception").

## Workflow: admin vs consumer

...

## UI development: hand-coding policy (desktop)

WOLFIE's rule: **[LESSONS LEARNED FROM THE WILD WEST](lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md)** section **7** ("I hand-code the templates"). That policy applies to **desktop** UI WOLFIE owns.

1. **Do not** generate **desktop** UI code (HTML/CSS/JS) unless WOLFIE explicitly asks.
2. **Do not** push frameworks (React, Vue, Angular, Svelte, etc.) or npm-based stacks for **desktop** surfaces.
3. **Do not** "modernize," refactor, or restyle **desktop** UI without **explicit** permission.
4. **Accept** vanilla JS, hand-written CSS, DynAPI-era patterns, and **integrate** finished **desktop** files (paths, includes, PHP wiring, docs).
5. **Prepare** to receive finished **desktop** assets and hook them to **PDO_DB**, routing, and Lupopedia headers as needed.

**Mobile web** is **out of scope** for this veto: follow **Two-UI Strategy** above (simple, generated or assisted, reviewed).

**Toolchain (WOLFIE, desktop):** plain editor (e.g. Notepad), DynAPI and custom layers, **no** package-manager dependency for that hand-coded surface.

**Golden rule (desktop):** WOLFIE writes the **desktop** UI; the IDE **integrates** and **documents**. For **mobile web**, the IDE may **help build** simple UI under WOLFIE's review--do not confuse the two surfaces.

## Actor vs Agent (summary pointer)

The full constitutional split (**ACTORS** vs **AGENTS** vs **FAUCETS**, identity stack, WOLFIE examples, common failures) is at the top of this file under **ACTORS vs AGENTS vs FAUCETS**. Do not skip it when editing `agents/` packs.

- **`agents/`** -- configuration templates only.
- **`database/lupopedia/actors/registry.json`** -- who may act (`actor_id`).
- **IDE facets** -- Cursor **102**, Kiro **100**, Windsurf **101**, Antigravity IDE **103**.
- **Human accountability** -- `auth_user_id` (ERIC **10000** on this install); root auth user **0** per PRD 01.

**Full model:** [docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md](docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md).

## Budget constraints (constitutional)

Effective June 1, 2026, Lupopedia operates on a **$50/month token budget**.

Budget shape:
- **Paid primary**: Claude Code only for high-value reasoning
- **Free tier**: Keep all free-tier agents active for simple, parallel, and draft work
- **Runtime API budget**: Reserve $33 for user-facing runtime API calls

Autoinstaller budget policy: the installer now prompts for per-provider keys and monthly budgets (defaults: gemini, deepseek, groq at $15 each, user-editable including 0 for unlimited/free-tier-only behavior). Each install owns its own independent provider budget and fallback order.

All agents must:
- Prefer free-tier or cheaper models for routine tasks
- Reserve paid reasoning time for production-critical architecture and documentation decisions
- Use handoff toons to prevent lost work (lost work = wasted tokens)
- Reuse translation channel explanations instead of regenerating
- Log expensive operations (over $0.50 per call) for review
- Treat paid API usage as budget-risk work and minimize call volume
- Use cached outputs and cheaper API tiers before premium API models
- Escalate when projected API spend threatens the monthly cap
- Treat post-June runtime API spend as a separate budget surface from development token use
- Prefer provider chaining (free-tier first, cheap paid second, premium fallback last)
- Preserve BYOK compatibility for self-hosted users to avoid subsidized runtime costs
- Use the canonical runtime loader (app/Services/ApiProviderChainService.php) for provider order, config loading from lupopedia-config.php, persisted spend tracking (lupo-memory/budgets/YYYYMM/spend.json), and fallback decisions
- Never hardcode premium-provider-first runtime paths when cheaper/free providers are configured

The translation channel and handoff system are not optional. They are budget survival mechanisms.

## UTC timestamps for headers (mandatory)

**Do not guess** last_modified_utc, when_updated, last_verified, or thread filename UTC prefixes.

1. Run python lupo-bin/tick.py once per editing batch (real system UTC).
2. Paste the printed **current_utc** (14 digits) into every header/footer you change.
3. Same batch, no second tick: python lupo-bin/echo_anchor_utc.py.

Full policy: **[TICK_PY_DOCTRINE.md](lupo-docs/doctrine/TICK_PY_DOCTRINE.md)** and [README.md](README.md) (Temporal Anchor).

## Changelog Buffer System (Mandatory)

...

## Mandatory Channel Literacy (All Actors and Agents)

All actors and agents must operate with channel-first context and thread-scoped execution.

Required references:

- README.md
- lupo-channels/channel_index.md
- lupo-channels/channel_creation_doctrine.md
- lupo-docs/prd/29_project_structure.md (active vs archive channel paths)
- lupo-docs/prd/31_implementation_folder_guidelines.md (PRD mirrors under lupo-docs/implementations/{prd_file_stem}/; scaffold; typed questions/ / answers/ / decisions/)
- lupo-docs/implementations/README.md (implementations index; naming must match PRD basename)
- lupo-docs/prd/00_root_constitutional_system_requirements.md (Section 5.8 -- implementation mirroring)
- lupo-docs/prd/02_channels_discussions.md (channels, thread manifest)
- lupo-docs/prd/17_decisions_format.md (thread filenames and decisions/ / questions/ / answers/ / comments/)
- lupo-docs/prd/77_thread_graduation_doctrine.md (THREAD_MANIFEST.md, lifecycle)

Required behavior:

1. Select target channel before execution (see **lupo-channels/channel_index.md**).
2. Use an existing thread in that channel, or create one if missing.
   - **New thread (filesystem, active layout):** create **lupo-channels/{federation_node_id}/{channel_key}/{new_thread_key}/** with **THREAD_MANIFEST.md** (required fields per **lupo-docs/prd/77_thread_graduation_doctrine.md**) and, for PRD-17-style coordination, **decisions/**, **questions/**, **answers/**, **comments/** -- each folder that you use must include **THREAD_INDEX.md**. Authoritative structure and filenames: **lupo-docs/prd/02_channels_discussions.md**, **lupo-docs/prd/17_decisions_format.md**.
3. Write status/report/workstream artifacts into **lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/** (and typed subfolders per PRD 17). Legacy numeric paths under lupo-channels/{channel_id}/threads/{thread_id}/ remain for API-mirrored work; pre-4.0.93 trees live in **lupo-channels_before_4_0_93/** (read-only archive).
4. Do not place channel-scoped work artifacts in repository root.
5. **PRD implementation mirrors (not channel threads):** For work tracked against a canonical **lupo-docs/prd/{prd_file_stem}.md**, use **lupo-docs/implementations/{prd_file_stem}/** -- the folder name **must** equal the PRD filename **without** **.md** (no shorthand like prd_36_rose). Use **status/**, **decisions/**, **questions/**, **answers/**, **comments/** as in **PRD 31**; each folder in use needs **THREAD_INDEX.md**. Scaffold: **python lupo-scripts/scaffold_implementation.py** with **--title** chosen so **<prd_id>_<title>** matches **prd_file_stem**. Constitutional rule: **PRD 00** section 5.8.

## Primary Coordination Personas (eleven)

These are the **only** canonical coordination layer for multi-agent work (orchestration, enforcement, custody, review, strategy, etc.). Each has a single active agent instance per doctrine; responsibilities do not overlap.

| Persona   | Role (summary)        |
|-----------|----------------------|
| **WOLFIE**   | Orchestrator         |
| **LEXA**     | Security enforcement |
| **ANUBIS**   | Custodian / integrity |
| **HEIMDALL** | Security guardian    |
| **SESHAT**   | Content review       |
| **ATHENA**   | Wisdom & strategy    |
| **MAAT**     | Truth & justice      |
| **THEMIS**   | Law & compliance     |
| **THOTH**    | Knowledge & records  |
| **JANUS**    | Transitions & gateways |
| **ROSE**     | Emotional dialogue   |

- **Persona selection MUST be deterministic for identical context + artifact** -- same channel artifact, same declared routing context, and same owning **TODO.md** row MUST map to the same target persona unless WOLFIE publishes a superseding directive; faucets MUST NOT randomize persona picks. Tie-breakers: **[MULTI_AGENT_COORDINATION_DOCTRINE](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCT_RINE.md)** section 6.1 **CTX010**.

**Artifact families** (coordination proof): WOLFIE_DIRECTIVE_*, LEXA_ENFORCEMENT_*, ANUBIS_CUSTODY_*, HEIMDALL_SECURITY_*, SESHAT_REVIEW_*, ATHENA_STRATEGY_*, MAAT_BALANCE_*, THEMIS_COMPLIANCE_*, THOTH_ANALYSIS_*, JANUS_TRANSITION_*, ROSE_DIALOGUE_*. Category-level artifacts (e.g. SECURITY_ALERT_*, TECHNICAL_SUPPORT_*) supplement these where doctrine allows.
See full doctrine: [MULTI_AGENT_COORDINATION_DOCTRINE.md](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md).

## Specialized agent ecosystem

**90+ specialized agents** sit outside the eleven-persona layer. **HERMES** (actor_id **15**) is **not** a generic implementer: **Heuristic Event Routing & Messaging Exchange System** -- reads channel artifacts, classifies work type, routes to the right persona, generates executable prompts (bridge between artifacts and execution). **Implementation execution** is **HEPHAESTUS** and other builders; do not conflate HERMES with implementers. Other specialists: technical support (IRIS, ASCLEPIUS, ...), database (LUPO), contrasting perspectives (**LILITH** actor_id **2**), etc.

...
This file provides guidance for **IDE faucet agents** and contributors. **Canonical multi-agent coordination** is defined in **[MULTI_AGENT_COORDINATION_DOCTRINE](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md)** (binding for v4.0.80+). This guide summarizes how that model maps to daily repo work.

**Canonical identity, propagation targets, and IDE roles** remain in [lupo-docs/doctrine/AGENT_REGISTRY.md](lupo-docs/doctrine/AGENT_REGISTRY.md). Resolve **actor_id** and faucet slugs from [lupo-database/lupopedia/actors/registry.json](lupo-database/lupopedia/actors/registry.json). The **agents** map in [actor_id/registry.json](lupo-database/lupopedia/actors/actor_id/registry.json) is for **lupo_agents** numeric ids (e.g. cursor -> 102, antigravity-ide -> 103, vscode-ide -> 113, trae -> 114)--not a substitute for the actor registry.

**New IDE or web terminal agent?** Register via the **[Actor Registration Checklist](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md)**. Do not contribute as an anonymous or unregistered agent.

## Pseudocode reasoning discipline (decisions/pseudocode/)

**Canonical:** [PRD 17 -- Decisions format](lupo-docs/prd/17_decisions_format.md), section **Pseudocode reasoning discipline for IDE agents (LILITH approved)**.

- **Purpose 2** (*_design.pseudo.md, exploratory *.pseudo.md, *.pseudo.php): treat as **deliberation** -- surface options, anchor to a **Decision Reference** (or edges), avoid silent completion into "final" API or schema. Do **not** invent TOON/install-SQL facts.
- **Purpose 1** (*_constitution.pseudo.md, lupo-docs/decisions/pseudocode/00_*.pseudo.md): **digest / router** artifacts -- high comment ratio is **not** required; still **no invented** columns or tables when extending.

**Optional validator** (warnings; use --strict to fail on warnings):

python lupo-scripts/validate_pseudocode_discipline.py <files-or-dirs>

## Lead orchestration (docs default: Cursor facet, actor_id 102)

**Attribution rule:** Match the **facet** to the **IDE**. Cursor -> **102**. Antigravity IDE -> **103** (lupo-agents/antigravity-ide/). VS Code -> **106** (lupo-agents/vscode-ide/). Do not call Antigravity or VS Code work "cursor."

The **Cursor** facet is the **named** lead orchestration surface in this guide for historical and tooling reasons (--target=cursor, .cursor/rules). That does **not** make **102** the correct actor_id on other IDE products.

...
---

## What This Project Is

**Canonical:** [`what_is_lupopedia.md`](what_is_lupopedia.md) -- load before answering what Lupopedia is.

Lupopedia is the continuation of Crafty Syntax Live Help 3.7.5 -- a PHP live-chat system rebuilt as a "Semantic OS." **Actors** are the orchestration identities; they coordinate through **faucets**, **sessions**, **channels**, **rules**, and **traits**. **Faucets** are execution surfaces (IDEs such as Cursor, Windsurf, Warp) registered as **facet** identities with **actor_id** in the registry--not primary personas, but used for attribution. It adds a unified actor model, semantic content graph, and doctrine-driven architecture on top of the original live-chat features. The only supported upgrade path is Crafty Syntax 3.7.5 -> Lupopedia 4.0.x. There are zero external installations; the sole instance is the developer's local environment on Windows/ServBay.

## Development Environment

- **Runtime (PRD 00 section 4 Option 4):** **Production** -- PHP **7.4+** and **64-bit** (Y2038-safe packed UTC as int). **Legacy / transitional** -- PHP **5.6+** may still run (e.g. old Crafty hosts); **32-bit** is not Y2038-safe. **Source** -- shared core **SHOULD** stay **PHP 5.6-parsable** where policy applies (**lupo-rules/root/PHP_VERSION_COMPATIBILITY.md**); avoid PHP 8-only syntax in shared paths (union types, match, enums, attributes, readonly, strict_types unless file is modern-only). No Composer **vendor/** in core runtime.
- **Database:** MySQL 8.0+ / MariaDB 10.5+ / PostgreSQL (all SQL must work on all three)
- **Web server:** Apache or Nginx with mod_rewrite, always installed in a subdirectory (never at web root)
- **Local stack:** ServBay on Windows 11, PowerShell

## WordPress reference for distribution packaging
...
Fresh install runs (A) then (B). Upgrade from Crafty runs (A), (B), then (C). Never mix them.

## Architecture Overview

### Request Lifecycle

1. index.php -- Front controller. Defines LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH. Searches for lupopedia-config.php (above docroot, then inside install dir). Extracts slug from URL.
2. lupopedia-config.php -- Database credentials, constants, table prefix (lupo_). Loads bootstrap.
3. lupo-includes/bootstrap.php -- Loads version.php, class-pdo_db.php, class-DatabaseFactory.php. Creates DB connection in $GLOBALS['mydatabase']. Starts session via App\Auth\Session. Loads services into globals (lupo_actor_service, lupo_auth_service, lupo_collection_tabs_service, etc.).
4. lupo-includes/lupopedia-loader.php -- Central orchestrator. Loads subsystems in order: (1) Core functions, (2) Module system, (3) Semantic engine, (4) Agent subsystem, (5) UI subsystem, (6) REST API.
5. lupo-includes/modules/module-loader.php -- Defines lupo_route_slug($slug) which routes requests. Priority: AUTH -> web-path resolution -> content/channel/edge/QA/help/list/crafty_syntax -> fallback content.

### Key Directories

...
- lupo-actors/ -- Actor hub keyed by **actor_id** (see PRD 00 section 5.6 and registry dir). Example numeric hubs: 0/, 1/, 2/, 111/, 102/. Optional hub README per IDE facet. Path from LUPO_ACTORS_DIR in config. See lupo-docs/actors.md and [PRD 15](lupo-docs/prd/15_actors.md) (act-as / department model references PRD 05).
...
- node_modules/ and lupo-tools/vsx-extension/node_modules/ -- npm-managed dependency caches for local tooling/extension builds. These are external package-manager directories and intentionally do not use the lupo- prefix.
...

### Database Access Pattern

...

Never hardcode lupo_ -- always use LUPO_TABLE_PREFIX. Always use prepared statements with named placeholders.

### Versioning

Version lives in config/global_atoms.yaml as GLOBAL_CURRENT_LUPOPEDIA_VERSION. Loaded at runtime by lupo-includes/version.php. All 4.0.x versions are patch iterations of the same Crafty->Lupopedia upgrade; there are no Lupopedia->Lupopedia upgrades until 4.1.0.

## Critical Doctrines (Non-Negotiable)

**Human context (why survivability matters):** [LESSONS_LEARNED_FROM_THE_WILD_WEST.md](lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md) -- section **7. The chair-falling moment (2015-2026)** (WOLFIE). Technical rules below are not abstract; they encode long-horizon lessons.

### Database Rules
- **No foreign keys, triggers, stored procedures, views, or computed columns.** The database is dumb storage; all logic is in PHP.
- **Integer types only:** BIGINT, INT, SMALLINT, TINYINT -- no parenthesized display widths (BIGINT(14) is forbidden in DDL), no UNSIGNED, no BOOLEAN.
- **Soft deletes:** Tables use is_deleted TINYINT DEFAULT 0 and deleted_ymdhis BIGINT DEFAULT 0. Queries must filter WHERE is_deleted = 0 by default.
- **Schema changes:** Update the TOON, then update install_new_lupopedia.sql, then create a one-time dev migration in lupo-database/lupopedia/mysql/migrations/dev_YYYYMMDD_description.sql. Never modify TOONs directly -- they are generated from the live DB.

### Timestamp Rules
- All timestamps are BIGINT in YYYYMMDDHHIISS UTC format (e.g., 20260214153045).
- Set with gmdate('YmdHis') in PHP -- never database-generated.
- Timestamped artifact filenames must use real UTC in YYYYMMDD_HHIISS format.
- Valid filename hours are 00 through 23 only; validators must reject HH > 23.
- No local timezone math, no offset arithmetic, and no guessed timestamps in filename generation.
- **Never** add seconds directly to the integer ($t + 86400$ produces invalid values). Use timestamp_ymdhis::addSeconds().
- Forbidden: DATETIME, TIMESTAMP, epoch seconds, ISO8601, time().

### Actor Model
- **Actors orchestrate; faucets execute.** actor_id is the universal identity key. There is no user_id in relationships.
- Actor ID bands and reserved ranges: see [Identity Layers Doctrine](lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md) (sections 5-6) and **registry.json** (**authoritative**). **Root user auth_user_id is 0** (PRD 01). Human actors typically **1000+**. IDE surfaces are **faucets** with registered **facet actor_id**; they are **not** among the eleven Primary Coordination Personas.
- **Actor IDs** are defined in **lupo-database/lupopedia/actors/registry.json**. **lupo_agents** numeric ids by slug live in the **agents** map inside lupo-database/lupopedia/actors/actor_id/registry.json. Tooling and docs must resolve from those sources; do not maintain inline ID lists as canonical. LUPOPEDIA HEADERS may include optional **agent_name_identity** (e.g. "Cursor IDE Agent") for human-readable identification--see [LUPOPEDIA HEADERS doctrine](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and AGENT_IDENTITY_REGISTRY.
- Tables: lupo_actors (unified), lupo_auth_users (human login metadata), lupo_agents (AI agent metadata).
- Lilith (actor 2) has a **flame header expert** faucet (slug lilith-flame) in lupo_agent_faucets for channel 42; see [LUPOPEDIA HEADERS doctrine](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and LILITH_FLAME_FAUCET_REPORT.

### Identity Layers (WS3, 4.0.87)

Lupopedia uses five distinct identity layers and they must not be conflated:

1. **Auth User** (lupo_auth_users)
  - Human login/authentication surface.
2. **Actor** (lupo_actors)
  - Operational orchestration identity (actor_id is canonical).
3. **Department** (lupo_actor_departments, lupo_departments)
  - Execution context and authority scope for actor operations.
4. **Agent** (lupo_agents)
  - AI runtime configuration and capability metadata.
5. **Faucet** (lupo_agent_faucets)
  - Execution surface (IDE/API), not orchestration identity.

...

### Lilith as non-interfering reviewer

- **Lilith** (actor_id 2) operates as a **non-interfering reviewer/critic**. See [lupo-rules/root/lilith-noninterference-doctrine.md](lupo-rules/root/lilith-noninterference-doctrine.md) (LIL001): Lilith must not modify other agents' work without explicit review context; must not block or delay other agents' operations; outputs must be clearly attributable; her presence must not alter permissions for other agents.
- **Teach, do not only tell:** Recurring rules belong in committed files (**AGENTS.md**, **.cursor/rules/**, doctrine, registries), not chat alone. See [lupo-docs/doctrine/LILITH_TEACH_DONT_ONLY_TELL.md](lupo-docs/doctrine/LILITH_TEACH_DONT_ONLY_TELL.md) (A-G-A-P-E mnemonic; disambiguated from **AGAPE** agent 705 and **AGAPE_DEFECT_TAXONOMY.md**).
...
### Agent Identity Registry

**Canonical registries:**

- **lupo_actors** (facet identity): lupo-database/lupopedia/actors/registry.json
- **lupo_agents id map** (agents object): lupo-database/lupopedia/actors/actor_id/registry.json

LUPOPEDIA HEADERS may include agent_name_identity for human-readable display (in the lupopedia.headers block):

```yaml
lupopedia.headers:
  actor_id: 102
  agent_name_identity: "Cursor IDE Agent"
```

Use **actor_id: 103** when the tool surface is **Antigravity IDE** (antigravity-ide), not 102.

See [LUPOPEDIA HEADERS doctrine](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and AGENT_IDENTITY_REGISTRY for complete documentation. Headers are stored in lupo_metadata and can also be written to the file as YAML.

...

### PHP Constraints
- No frameworks, middleware, Composer, or vendor/ directory. Pure procedural PHP + PDO only.
- No ORM or query builders. SQL is hand-written.
- spl_autoload_register() is the only allowed autoloader.
- No mbstring dependency for slug generation -- use ASCII fallbacks.
...

### UI strings (locale / i18n)
- **Constitutional:** [PRD 00 section 16.6](lupo-docs/prd/00_root_constitutional_system_requirements.md) (RULE 93.UI_STRINGS_LOCALE). Lupopedia is **multi-locale capable**; do not hardcode English as if it were the only language.
- **When adding or editing ship-facing HTML** in login.php, admin.php, lupo-includes/themes/, or handler-rendered markup: use **lupo_t('semantic.key', 'Fallback English')** after **LupoLocale::bootstrap()** / **lupo-includes/lupo-i18n.php** (see existing **admin_layout.php** / **login.php**).
- **Catalogs:** one file per locale under **lupo-includes/lang/** (e.g. **lupo-en.php** returns an array). New keys go in **English first**; other locales mirror keys with translated values. Whitelist new locale codes in **LupoLocale::allowedLocales()** and add language **<option>**s where the UI offers a switch.
- **lupo_t()** is the sanctioned thin helper for UI copy (exception to "avoid new globals" for this purpose only). Prefer passing strings into JS via **json_encode(lupo_t(...))** or **data-*** attributes rather than duplicating English in client scripts.
- **Legacy reference only:** Crafty **craftysyntax-reference/lang/** (txtN keys) -- do not copy numeric key style; use dotted semantic keys.

### LUPOPEDIA HEADERS
- **In-scope** authored files (see [PRD 16 -- Header applicability and scope](lupo-docs/prd/16_lupopedia_headers.md#header-applicability-and-scope)) **should** have a **LUPOPEDIA HEADERS** block: Markdown uses YAML between --- delimiters; PHP/JS/Python/SQL/HTML use comment-embedded YAML per FORMAT. **decisions/pseudocode/*.pseudo.md** and ***.pseudo.php** (and ***.pseudo.txt**) **must** include headers with **file_path_from_root** so external AI and paste handoff can anchor paths ([PRD 17](lupo-docs/prd/17_decisions_format.md) pseudocode rules). Include at minimum the fields required for that artifact type (always **file_path_from_root** for identity). Do **not** require headers on binaries, generated exports (TOON, CSV dumps, minified bundles), vendor trees, or lockfiles. Headers are stored in **lupo_metadata** when imported and can be **written to the file**. See [lupo-docs/doctrine/LUPOPEDIA_HEADERS/](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and PRD 16.

### File Naming
- Lowercase a-z, digits 0-9, underscore only. No uppercase, hyphens, spaces, or Unicode in new filenames.

...

### security testing dependencies (vs shipped runtime)
- **Shipped runtime** remains dependency-free per PHP constraints above: no Composer vendor/ in core paths, no npm stack wired into the live app.
- **Security and test tooling** (OWASP ZAP, Burp, Python venvs for scan scripts, CI-only packages) are **allowed** on developer workstations, in CI, or in **test-only** directories -- they are **not** runtime dependencies and must **not** be imported by lupo-includes/ bootstrap or production entrypoints. Full boundary: [TWO_LAYER_SECURITY_DOCTRINE.md](lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md) (section *Security testing dependencies vs runtime dependencies*).

### Dependency policy for security packages (study, do not ship)
- **You may** study any security library or framework: clone upstream, read source, run it in a scratch directory or test harness to learn behavior.
- **You may not** add it as a **runtime** dependency of Lupopedia (no composer require / npm install into paths that ship with the app).
- **Workflow:** identify need -> read or clone (do not install into core) -> understand the pattern -> implement native code under app/ or lupo-includes/classes/ -> document **inspired_by** (or equivalent) in LUPOPEDIA HEADERS -> ship only your code.
- **No exception** for "it is security-related": if you need the behavior, **own the implementation**. Canonical doctrine: [REVERSE_ENGINEERING_DOCTRINE.md](lupo-docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md). Analysis vs pulling packages into prod: [TWO_LAYER_SECURITY_DOCTRINE.md](lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md) (section *Dependency analysis vs dependency adoption*).

### Reverse engineering with federation nodes (study tree vs ship tree)
- **Canonical ingest path** for upstream clones and external research: lupo-research/federation_nodes/{federation_node_id}/<package_key>/ with **federation_node_id >= 2**, plus a **MANIFEST.md** from lupo-research/federation_nodes/_templates/MANIFEST_TEMPLATE.md. See [PRD 29](lupo-docs/prd/29_project_structure.md) (lupo-research/federation_nodes/).
- **Shipped implementation** stays under app/ and lupo-includes/ -- never require federation research trees from bootstrap.
- **Workflow:** clone or unpack into lupo-research/federation_nodes/2/... (or next free node per project rules) -> document MANIFEST -> study -> implement native code -> **inspired_by** edge from your class to that path. Full doctrine: [REVERSE_ENGINEERING_DOCTRINE.md](lupo-docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md) (section *Federation nodes as reverse engineering sandboxes*).

### Federation nodes: current state (dual purpose)
- **federation_node_id: 0** -- Default scope for much **repository documentation** (headers on doctrines, guides).
- **federation_node_id: 1** -- Local / deployed instance context where the model distinguishes "this node."
- **federation_node_id >= 2** -- **Two intents share the same numeric range**; you tell them apart by **folder + MANIFEST + purpose**, not by the integer alone:
  - **Purpose A (active in 4.0.x):** **Research sandbox** -- external upstream clones under lupo-research/federation_nodes/{id}/, documented in [REVERSE_ENGINEERING_DOCTRINE.md](lupo-docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md) (*Federation nodes as reverse engineering sandboxes* and *Federation nodes: dual purpose*).
  - **Purpose B (planned):** **Semantic network peers** -- other Lupopedia installs exchanging semantic data -- **no PRD yet**; **defer** implementation until after **4.0.x** stabilizes. Do not build multi-install federation features without a written PRD.
- **Crafty Syntax scale (planning narrative):** On the order of **1,000,000+ lifetime** installs and **~144,000** active/reporting-era nodes are **documented assumptions** for strategy -- confirm before external comms; see **[SILENT_HARVEST_DOCTRINE.md](lupo-docs/doctrine/SILENT_HARVEST_DOCTRINE.md)** and **[PRD 34](lupo-docs/prd/34_federation_node_semantic_network.md)** (*The Silent Million*). **Focus:** one correct install, Crafty import path, and core behavior.

### Path and visit analytics (silent harvest)
- **Crafty import** can carry **aggregated** path and visit history into **lupo_visits**, **lupo_paths**, **lupo_visits_daily**, **lupo_referers_daily** (see install SQL). Use this **per-operator** foundation for navigation inference and future federation tooling -- not as covert cross-site surveillance. Ethics, consent, and public claims: **[SILENT_HARVEST_DOCTRINE.md](lupo-docs/doctrine/SILENT_HARVEST_DOCTRINE.md)**. **Dormant -> Lupopedia** reactivation (consent order): **[CRAFTY_NODE_REACTIVATION_STRATEGY.md](lupo-docs/doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md)**. Planned **navigation compiler** and federation scope: **[PRD 34](lupo-docs/prd/34_federation_node_semantic_network.md)** (draft).

## Schema Source of Truth Hierarchy

1. lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql -- canonical DDL
2. lupo-database/lupopedia/toon/*.toon.json -- generated column/type reference (do not hand-edit)
3. lupo-docs/doctrine/ -- per-table documentation and legacy migration mapping
4. lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md -- Crafty->Lupopedia table mapping (path under lupo-docs where present)

## Module Routing Priority

In lupo_route_slug(): AUTH -> web-path resolution (doctrine/qa/docs/flp prefixes) -> content by slug -> channel/edge/QA routes -> HELP -> LIST -> truth redirects -> crafty_syntax -> content fallback.

## Lead orchestration and registry

- **Docs default for lead stewardship:** **Cursor** facet (actor_id **102**) -- root doc consolidation and IACP-style continuity when work is done **from Cursor**; see doctrine section 7.2 for IDE <-> primary persona flow.
- **Antigravity IDE:** facet **103** (antigravity-ide); same doctrine, correct **actor_id** in headers--do not use 102 on that surface.
- **Orchestrator persona:** **WOLFIE** (actor_id **1**) -- delegates and validates per eleven-persona doctrine.
- **Registries:** [actors/registry.json](lupo-database/lupopedia/actors/registry.json) for **lupo_actors**; [actor_id/registry.json](lupo-database/lupopedia/actors/actor_id/registry.json) **agents** map for **lupo_agents** ids. This guide names Cursor (102) as the conventional lead IDE in prose; that is **not** permission to mis-attribute other IDEs as 102.

Commit prefixes: cursor:, antigravity-ide: (or antigravity:), wolfie:, windsurf:, kiro:, etc. See CONTRIBUTING.md. **Task authority:** **[MULTI_AGENT](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md) section 9** -- **root TODO.md** = multi-agent coordination + HERMES prompt queue; **lupo-docs/versions/<version>/TODO.md** = version product backlog (Top 50, etc.). Channel 42 default workspace.
