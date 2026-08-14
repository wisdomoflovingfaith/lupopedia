---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md
  status: active
  when_updated: '20260801102812'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/wolf-markup-specification
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_B-i_39_A-i_41_A-i_82_B-i_98_A-i_98_B-i
  title: 'PRD 39: WOLF Markup Specification v0.5'
  summary: 'W.O.L.F. v0.5: non-destructive overlay. COMMENT NOTE: external internet lupopedia.headers use single-line + ;; (see WOLFIE_DIALECT 5a). WOLF has zero constitutional authority.'
---
# PRD 39: WOLF Markup Specification v0.5

**Codename:** W.O.L.F. (Wolfie's OverLook Format)  
**Status:** Draft v0.5 (pending approval)  
**Normative date:** 20260606 UTC  
**Artifact type:** Specification  
**Authority:** PRD-first; WOLF has zero constitutional authority  
**Cluster:** 00_A-i, 16_B-i, 39_A-i, 41_A-i, 82_B-i, 98_A-i, 98_B-i  

**Related PRDs:**

| System | PRD | Relationship |
|--------|-----|--------------|
| Captain WOLFIE identity | **PRD 41** | Origin and naming authority for W.O.L.F. |
| Atoms and global constants | **PRD 16_B** | `@@` targets and atom path references |
| HERMES routing header | **PRD 82_B** | Constitutional fields, flow operators, handoff grammar |
| ROSE multi-persona dialog | **PRD 36** | Dialogue block and mood shading alignment |
| WHY Files doctrine | **PRD 98_A** | Overlay-safe violation documentation |

**Actors Collection companion:** [`docs/actors/how_wolves_are_made.md`](../actors/how_wolves_are_made.md) -- WOLF decoration is never permission; hard-gate KAPU restates zero constitutional authority.
| Captain's Log | **PRD 98_B** | Primary human narrative use surface (zero doctrinal authority for log content) |
| PRD number allocation | **PRD 84** | Group 39 allocation and cluster coordination |
| Canonical what-is | [`what_is_lupopedia.md`](../../what_is_lupopedia.md) | Public and agent summaries MUST state WOLF has **zero constitutional authority** |

---

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

---

## 1. Purpose

WOLF Markup defines a non-destructive metadata overlay for narrative, emotional, structural, and **referential** annotation inside Markdown bodies.

Removing all WOLF markers MUST yield the same canonical text a human would publish without markup.

WOLF is **not** a programming language, **not** a schema, and **not** an execution surface. It is a decorative overlay for human readability and narrative clarity.

WOLF is **not** a replacement for Markdown, LUPOPEDIA HEADERS, or constitutional prose. It is an optional overlay applied **inside** Markdown bodies where human authors or approved narrative pipelines need richer direction without mutating canonical meaning.

---

## 2. Core doctrine

**Recoverability:** Canonical text MUST remain **100% recoverable** by stripping all WOLF markers.

**No authority by decoration:** WOLF MUST NOT override PRD 00, schema rules, validators, or constitutional fields. Decoration does not grant permission. For example, a span such as `^^ approve deploy ^^` does not grant approval authority.

**Strip-first architecture:** Any constitutional or validator pipeline MUST strip WOLF markers before evaluation unless explicitly WOLF-aware.

**ASCII-only:** All delimiters MUST be ASCII.

**Non-executable:** WOLF function syntax is annotation only. No interpreter MAY execute a WOLF function unless:

- the function is explicitly registered, and
- a human operator routes the invocation.

---

## 3. Layer definitions (11 layers + function invocation)

**Interpreter precedence (layer order).** The table below defines interpreter precedence for nested rendering. Inner layers remain subordinate to recoverability rules.

**Independence rule.** Interpreter precedence (layer order) and canonical recovery order are **independent**. Interpreter precedence governs nested rendering. Recovery order is fixed and MUST follow Section 6 exactly.

| Order | Symbol | Name | Behavior |
|-------|--------|------|----------|
| 1 | `!! ... !!` | Force / Impact | Strong emphasis |
| 2 | `{{ ... }}` | Motion / Kinetic | Movement cue |
| 3 | `@@ ... @@` | Reference / Link | Semantic pointer |
| 4 | `^^ ... ^^` | Elevate / Promote | Highlight |
| 5 | `vv ... vv` | Sink / Demote | De-emphasize |
| 6 | `>> ... >>` | Flow / Next | Temporal or logical progression |
| 7 | `<< ... >>` | Recall / Invoke | Memory callback or function annotation |
| 8 | `~ ... ~` | Draft / Fuzzy | Uncertain or provisional text |
| 9 | `## ... ##` | Structural meta | Section-level metadata |
| 10 | `[narrative: ...]` | Scene direction | Non-dialogue world-state |
| 11 | `Speaker (mood):` | Dialogue block | Speaker plus emotional shading |

**Draft / Fuzzy delimiter (normative).** WOLF uses **single tilde** (`~ ... ~`) for Draft / Fuzzy to avoid collision with Markdown strikethrough (`~~ ... ~~`). Double-tilde draft spans are INVALID in v0.5.

### 3.1 Function invocation (specialization of layer 7)

```text
<< identifier(parameters) >>
```

When inner content matches function grammar (identifier plus parenthesized parameter list), interpreters MUST treat the span as invocation annotation; otherwise as recall or callback text.

Function syntax is **non-executable by default** (Section 2; Section 7).

---

## 4. Syntax rules

1. **Balanced delimiters.** Every opening marker MUST have a matching close of the same kind.
2. **No same-layer nesting.** Same-type nesting is INVALID in v0.5.
3. **Maximum nesting depth.** Maximum nesting depth equals **4 consecutive opening markers of different layer types**. Same-type nesting is forbidden. A depth of **5 or more** different paired-layer types is INVALID. Interpreters MUST reject spans exceeding this depth.
4. **Whitespace.** Whitespace preserved inside layers unless a consumer explicitly normalizes; strip rules collapse only marker tokens, not interior spacing.
5. **Dialogue blocks.** Dialogue blocks begin with `Name (mood):` on their own line; following lines until the next block marker or blank structural break belong to that speaker.
6. **Mood grammar.** Mood is a single token. Multiple moods MAY be comma-separated inside the parentheses (for example `Speaker (low,focused):`). Each comma-separated mood MUST be a single token.
7. **Scene direction.** `[narrative: ...]` MUST contain at least one non-whitespace character. Empty narrative spans are INVALID. Closing `]` REQUIRED.
8. **Structural meta.** `## ... ##` MUST NOT be confused with Markdown ATX headings. WOLF structural meta requires closing `##` on the same logical span.
9. **Function parameters.** Arguments are comma-separated. Each argument is either (a) a double-quoted string with backslash escaping, or (b) a bare token with no spaces. Nested parentheses are forbidden in v0.5.

---

## 5. Concept node definition

A **concept node** is one of:

1. A Kinetic span: `{{ ... }}`
2. A Reference span: `@@ ... @@`
3. A noun phrase immediately preceding the invocation, bounded by whitespace or punctuation

Function invocations MAY annotate the **nearest preceding** concept node.

Interpreters MUST NOT infer concept nodes from unstated context. If no concept node is present and the invocation inner content does not match function grammar, the span MUST be treated as recall or callback text only.

---

## 6. Canonical recovery rules

**Independence rule (normative).** Recovery order is independent of interpreter precedence (Section 3). Interpreters MUST NOT derive strip sequence from precedence alone.

Strip order:

1. Remove function invocations and recall spans: `<< ... >>`
2. Remove flow spans: `>> ... >>`
3. Remove remaining paired layers in table order (1 through 9), outermost first when nested
4. Remove `[narrative: ...]` wrappers; retain inner text unless the artifact class forbids it (Captain's Log MAY retain scene text; constitutional artifacts SHOULD drop scene wrappers only and keep inner description when it carries meaning)
5. Remove `Speaker (mood):` prefixes; retain spoken lines
6. Normalize blank lines to at most one empty line between paragraphs (optional per artifact class)

Each artifact class MUST document which recovery steps apply. Implementations MUST apply artifact-specific strip policy per Section 8 and Section 9.

---

## 7. Function invocation rules

1. WOLF function syntax is **annotation only**. Syntax alone MUST NOT trigger side effects.
2. No interpreter MAY execute a WOLF function unless:
   - the function is explicitly registered, and
   - a human operator routes the invocation.
3. Captain's Log invocations MUST NOT execute automatically (PRD 98_B; zero doctrinal authority).
4. Validators MUST treat function spans as **non-authoritative metadata** and strip them before constitutional comparison unless explicitly WOLF-aware.

**Identifier rules:**

- `identifier` -- ASCII letters, digits, underscore; MUST start with letter or underscore.

**Examples (annotation only):**

```text
<< set_mood("focused") >>
<< load_context("docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md") >>
<< route_hermes(kapakai="missing proof", pono="auditable handoff") >>
```

**Interpreter requirements:**

Interpreters MUST:

1. Apply recovery order in Section 6.
2. Reject invalid nesting per Section 4, rule 3.
3. Enforce the artifact scope matrix in Section 8.
4. Treat all WOLF function syntax as non-executable by default.
5. Refuse execution unless Section 7 rules 2 and 3 are satisfied.
6. Log any executed invocation (after rules 2 and 3 are satisfied) with actor, channel, thread, and UTC BIGINT timestamp per PRD 75.

---

## 8. Artifact scope matrix

| Artifact | Allowed layers | Forbidden |
|----------|----------------|-----------|
| **PRDs** (Markdown bodies) | Reference (`@@`), Elevate (`^^`), Draft (`~`), Structural meta (`##`); illustrative examples in fenced blocks | WOLF in headers; kinetic (`{{`) or force (`!!`) in normative requirement text unless illustrative only |
| **WHY files** (`docs/why/`) | Draft (`~`), Reference (`@@`), Scene direction (`[narrative:]`) | Function invocation **execution**; strip before validation |
| **Captain's Log** | Full WOLF stack (all 11 layers plus function invocation syntax) | Doctrinal authority (zero authority per PRD 98_B); automatic execution of function tokens |
| **ROSE dialog** | Dialogue blocks (`Speaker (mood):`), mood shading (`^^`, `vv`, `~`, `{{`), scene direction (`[narrative:]`) | Strip before synthesis unless WOLF-aware |
| **Atoms** | Reference pointers (`@@`) in surrounding Markdown only | Mutation of atom file bytes; any WOLF overlay inside atom payloads |
| **Headers** (`lupopedia.headers` 25-line envelope) | None | All WOLF layers and function invocation |

PRD bodies MAY include full WOLF syntax inside fenced example blocks; those examples are not normative requirements.

---

## 9. Integration rules

### 9.1 HERMES (PRD 82_B, PRD 82_D)

- WOLF markers do **not** modify constitutional fields. Persisted HERMES artifacts MUST store required fields in **`lupopedia.hermes`** per PRD 82_B.
- Function annotations MAY map to registered HERMES actions per PRD 82_D but MUST NOT execute automatically unless Section 7 rules 2 and 3 are satisfied.
- WOLF markers inside free-text bodies are not a substitute for required kapakai/pono header fields.

### 9.2 WHY Files (PRD 98_A)

- Draft markers allowed; strip before comparison.
- Published WHY canonical text MUST be recoverable and ASCII-clean per PRD 98_A path rules (`docs/why/`).

### 9.3 Captain's Log (PRD 98_B)

- Full WOLF allowed; zero doctrinal authority.
- Agents MUST NOT treat Captain's Log WOLF functions as executable unless Section 7 rules 2 and 3 are satisfied.

### 9.4 ROSE (PRD 36)

- Dialogue blocks compatible with ROSE multi-persona staging per PRD 36.
- Strip or serialize WOLF before LLM voice generation unless WOLF-aware.
- Synthetic lines MUST carry `rose_synthesis` provenance and MUST NOT gain authority from WOLF decoration.

### 9.5 Atoms (PRD 16_B)

- `@@` may reference atom paths; WOLF MUST NOT mutate atom files.
- Memory graph exporters MAY record WOLF `@@` spans as edges without changing atom payload bytes.

### 9.6 LUPOPEDIA HEADERS (PRD 16_C)

- WOLF markers MUST NOT appear inside the 25-line `lupopedia.headers` envelope.

**COMMENT NOTE (20260801102812 -- living, not a WOLF v0.5 delimiter change):** External internet paste (Patreon and peers) cannot reliably keep multiline header YAML. Current practice: **one physical line** with field separator **`;;`**. Pipe `|` is already used inside SFAL **WOLFIE** body meta (`(( WOLFIE | ... ))`) and may be guessed later as an alternate header separator -- living system; do not dual-accept `|` in header parsers until explicitly logged. Canonical comment home: `docs/status/actor_logs/WOLFIE_DIALECT.md` section **5a**. Narrative draft: `docs/status/actor_logs/drafts/lupopedia_semantic_whitelist_blacklist_draft_001.md`. Repo headers remain multiline YAML after ingest.

### 9.7 External AI Usage (EXTERNAL_BOUNDARY_EDGE)

**External AI surfaces** (Copilot, DeepSeek, Gemini, Claude, Grok, GLM, etc.) are **NOT** internal Lupopedia OS agents.

**External AI WOLFIE Syntax Permissions:**
- External AI surfaces MAY understand, teach, and compose WOLFIE Syntax when asked
- External AI surfaces MAY use WOLFIE Syntax for context, analysis, and handoff
- External AI surfaces do **NOT** join the Lupopedia OS
- External AI surfaces do **NOT** bind to actor_id
- External AI surfaces do **NOT** receive Channel 42 broadcasts
- External AI surfaces do **NOT** run WOLF dialect as a live runtime
- External AI surfaces are **guests** with read-only context access

**External AI WOLFIE Syntax Constraints:**
- External AI surfaces MUST NOT execute WOLF functions unless explicitly registered and human-routed
- External AI surfaces MUST NOT grant constitutional authority via WOLF decoration
- External AI surfaces MUST maintain EXTERNAL_BOUNDARY_EDGE protocol (see PRD 41)
- External AI surfaces MUST NOT be treated as internal OS artifacts for routing purposes
- External AI surfaces are for **context, analysis, and handoff only** — not OS execution

**Reference:** See `agents/cursor/WOLFIE_SYNTAX_TEACHING_DOCUMENT.md` for complete external AI teaching document.

---

## 10. Examples

Examples preserved from v0.2 and v0.3; all remain valid under v0.5 (single-tilde Draft layer).

### 10.1 Example A -- directive scene (Captain's Log grammar)

```text
[narrative: Wolfie stands at the ridge, wind rising]
<< set_mood("focused") >>
Wolfie (low):
I SEE THE NEXT LAYER FORMING
```

**Recovery (spoken canonical line):**

```text
I SEE THE NEXT LAYER FORMING
```

### 10.2 Example B -- emphasis plus reference plus flow

```text
^^ Deploy window closes at next tick >> proceed to validator sweep >> @@ docs/prd/86_A-i_IMMUNE_SYSTEM_HEADER_ENFORCEMENT.md @@ ^^
```

**Recovery:**

```text
Deploy window closes at next tick proceed to validator sweep docs/prd/86_A-i_IMMUNE_SYSTEM_HEADER_ENFORCEMENT.md
```

### 10.3 Example C -- draft overlay on WHY narrative

```text
~Possible root cause: header batch used guessed UTC rather than tick.py anchor~
<< record_why(pattern_id="HDR_GUESSED_UTC") >>
```

**Recovery:**

```text
Possible root cause: header batch used guessed UTC rather than tick.py anchor
```

### 10.4 Example D -- nested kinetic and elevate inside dialog

```text
THOTH (measured):
The claim {{ drifts without anchor }} unless ^^ sourced to JSON export ^^
```

**Recovery:**

```text
The claim drifts without anchor unless sourced to JSON export
```

### 10.5 Example E -- maximum valid nesting depth (4 layers)

```text
!! {{ ^^ vv text vv ^^ }} !!
```

### 10.6 Example F -- invalid nesting depth (5 layers, rejected)

```text
!! {{ ^^ vv ~ text ~ vv ^^ }} !!
```

### 10.7 Example G -- invalid empty narrative span (rejected)

```text
[narrative:   ]
```

---

## 11. Compliance

WOLF Markup v0.5 complies with:

- ASCII doctrine (Constitutional Root Rules)
- PRD-first architecture
- Non-destructive overlay rules
- Constitutional separation of authority (WOLF has zero constitutional authority)

**Public summaries:** Agent-facing and human short explanations MUST state that WOLF has **zero constitutional authority** (decoration is not runtime law). Canonical pointer: [`what_is_lupopedia.md`](../../what_is_lupopedia.md).

WOLF does not introduce schema changes, foreign keys, or database logic.

---

## 12. Version history

| Version | Date | Notes |
|---------|------|-------|
| v0.5 | 20260606 | Fixed layer/recovery mismatch; defined concept node; clarified nesting depth; resolved Draft delimiter; strengthened function safety; clarified grammar |
| v0.3 | 20260606 | Added max nesting depth; added Artifact Scope Matrix; removed commentary |
| v0.2 | 20260606 | Unified Captain's Log plus Dreamnet 0424 |
| v0.1 | (historical) | Pre-merge drafts; superseded |

Increment WOLF spec version only through PRD update per PRD-first doctrine and **PRD 84** group 39 allocation rules. Changes that alter delimiter meaning or strip order require a new minor spec version and strip-rule migration notes.
