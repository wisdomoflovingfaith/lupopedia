---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/WOLFIE_DIALECT.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/WOLFIE_DIALECT.md
  status: active
  when_updated: "20260729181822"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/status/actor-logs-wolfie-dialect
  artifact_type: documentation
  artifact_kind: guide
  channel_key: status
  federation_node_id: 0
  thread_key: actor_logs_wolfie_dialect
  lupopedia.schema: documentation
  prd_cluster: 98_C_39_A_16_C_41_A_82_B
  title: "SFAL I VI WOLFIE_DIALECT-0-00000 -- SFAL WOLFIE Dialect (Integrity + Ethics)"
  summary: "Body-only WOLFIE dialect for STATUS FOLDER Actor Logs: (( WOLFIE | )), {{WOLFIE}}, {{WOLFIE_VARS}} with integrity and ethics fields. Zero constitutional authority. Headers stay pure 4.2.0. Replaces misnamed WOLF dialect draft."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: logging
  faucet_actor_id: 102
---
# SFAL I VI WOLFIE_DIALECT-0-00000 -- SFAL WOLFIE Dialect (Integrity + Ethics)

**Display ID:** SFAL I VI WOLFIE_DIALECT-0-00000  
**Collection:** STATUS FOLDER -- Actor Logs  
**Edition:** I  
**Canonical name:** **WOLFIE Dialect** (NOT "WOLF")  
**Authority:** DRAFT for SFAL bodies only -- **zero constitutional authority**  
**Header contract:** dialect is **body-only**; dense headers remain pure `4.2.0` YAML  
**Related:** [PRD 39 WOLF Markup](../../prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md) (general decorative overlay; different product)  
**Root index:** [ROOT_INDEX.md](ROOT_INDEX.md)

**CORRECTION (20260729134317):** Prior draft used "WOLF" / `{{WOLF` / `WOLF_DIALECT.md`. That name is too generic and omitted Integrity + Ethics. Canonical dialect name is **WOLFIE**.

**CORRECTION (20260729171653):** Pre-release constraints require identity precision (no pronoun-only speakers), four-layer WHO (`actor_id` / `auth_user_id` / `agent_name` / `faucet_*`), and evolving WHO/WHAT/WHERE/WHEN/WHY/HOW/TO_WHOM + includes. See `docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md`.

**CORRECTION (20260729171900):** Pronoun Awareness -- bare **I / you / he / she / it** forbidden unless mapped to `{{WHO: ...}}` / `{{TO_WHOM: ...}}` or a full `{{WOLFIE` envelope. Unknown identity uses `{{WHO: unresolved}}` and must be flagged in STATUS AGENT LOG.

**CORRECTION (20260729181822):** FUNCTION meta-syntax locked to canonical form `<< FUNCTION : name( &context ); >>`. Annotation only -- not a PHP/runtime compiler. See section 3a.

---

## 0. KAPU (read first)

1. Dialect name is **WOLFIE**, never WOLF, for STATUS FOLDER Actor Logs.
2. WOLFIE dialect does **not** grant permission, override PRD 00, or replace headers.
3. Validators MUST be able to **strip** WOLFIE markers and still read the log.
4. Hawaiian / Hermes fields (KAPU, PONO, KULEANA, ALII, EH_BRAH_WHY, PUKA, etc.) stay in body/Hermes -- not densified into header YAML.
5. When a log touches KAPU / PONO / KULEANA / ALII / EH_BRAH_WHY / PUKA (or equivalent ethical claim), the WOLFIE span MUST include **`integrity`** and **`ethics`** fields.
6. Do **not** put WOLFIE blocks inside the `lupopedia.headers` YAML envelope.
7. Bare pronouns (**I / you / he / she / it / they**) are forbidden as the only speaker/audience marker. Map to `{{WHO: ...}}`, `{{TO_WHOM: ...}}`, or a full `{{WOLFIE` envelope with `actor_id` / `auth_user_id` / `agent_name` / `faucet_*`.
8. If identity is unknown: `{{WHO: unresolved}}` or `{{TO_WHOM: unresolved}}`, then flag in STATUS AGENT LOG.
9. Dialect is **not finalized** -- evolve and log changes in STATUS AGENT LOGs.
10. FUNCTION blocks (`<< FUNCTION : ... >>`) are **annotation / intent** only. Strip-safe. Not PHP. Not a compiler. Zero constitutional authority until an authorized faucet executor exists.

### Relation to PRD 39

| Surface | Role |
|---------|------|
| **PRD 39 "WOLF Markup"** | General decorative overlay (`!!`, `@@`, kinetic `{{ }}`, etc.) |
| **SFAL WOLFIE Dialect (this file)** | Actor-log meta: `(( WOLFIE | ... ))`, `{{WOLFIE}}`, `{{WOLFIE_VARS}}`, `<< FUNCTION : ... >>` with integrity/ethics |

Exact openers: `{{WOLFIE`, `{{WOLFIE_VARS`, and `<< FUNCTION :` (not bare `{{`, not `{{WOLF`, not PHP `function`).

---

## 1. Inline WOLFIE meta blocks

### Syntax

```text
(( WOLFIE | key: value | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | ethics: "pono" | integrity: "true" | note: "says hi" ))
```

### Rules

- MUST begin with `(( WOLFIE |`
- Key/value pairs separated by `|`
- Include `integrity` and `ethics` when relevant (required when log touches KAPU/PONO/KULEANA/ALII/EH_BRAH_WHY/PUKA)
- Include `actor` / `actor_id` and/or `auth_user_id` when attribution-bearing
- Quoted strings for human text
- Body-only; safe for GitHub / Patreon / website as literal text
- No nested `((` inside `(( ... ))`

### Recommended keys

| Key | Meaning |
|-----|---------|
| `actor` | Actor slug |
| `actor_id` | Numeric actor id |
| `auth_user_id` / `auth` | Human auth user id |
| `human` | Human display name (e.g. ERIC) |
| `integrity` | Integrity marker (`true` / `"true"` / short token) |
| `ethics` | Ethics marker (`pono`, `kapu`, etc. -- narrative token, not densified Hermes) |
| `var` / `value` | Variable injection |
| `note` | Short narrative / speech |
| `comment` | Inline commentary |
| `mood` | Soft mood token |
| `side` | `actor` \| `human` \| `hybrid` |
| `agent_name` | Agent pack slug (e.g. wolfie) -- not the same as faucet |
| `faucet_name` | Faucet display name (e.g. CURSOR_IDE) |
| `what` / `where` / `when` / `why` / `how` / `to_whom` | Context keys (evolving) |

### Examples

```text
(( WOLFIE | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | ethics: "pono" | integrity: "true" | note: "scanning perimeter" ))
```

```text
(( WOLFIE | auth_user_id: 10000 | human: ERIC | ethics: "pono" | integrity: "true" | note: "says hi" ))
```

```text
(( WOLFIE | ethics: pono | integrity: true | note: "actor aligned with Hermes" ))
```

```text
(( WOLFIE | var: auth_user_id | value: 10000 ))
```

---

## 2. Multiline WOLFIE narrative blocks

### Syntax

```text
{{WOLFIE
actor: CAPTAIN_WOLFIE
actor_id: 1
auth_user_id: 10000
integrity: true
ethics: pono
channel: status
---
Captain Wolfie reports corridor A3 is clear.
Lilith confirms no anomalies detected.
Human 10000 approves the patrol route.
}}
```

### Rules

- MUST begin with `{{WOLFIE`
- Metadata first, then `---` separator, then narrative
- Close with `}}`
- No nested WOLFIE blocks
- No PRD syntax as executable claims
- No YAML front matter inside (except the single `---` separator)
- No Markdown ATX headings (`#`) inside
- No HTML inside
- Include `integrity` and `ethics` when the narrative makes ethical/doctrinal claims

---

## 3. WOLFIE variables

### Declaration

```text
{{WOLFIE_VARS
auth_user_id = 10000
actor_id = 1
actor_name = "CAPTAIN_WOLFIE"
integrity = true
ethics = pono
}}
```

### Usage

```text
(( WOLFIE | actor: $actor_name | auth_user_id: $auth_user_id | ethics: $ethics | integrity: $integrity | note: "variable test" ))
```

### Rules

- Prefer one `{{WOLFIE_VARS` near top of body (after any CORRECTION block)
- `$name` is for future parsers; header fields still win on conflict
- Variables do not override dense header `actor_id` / `auth_user_id`

---

## 3a. FUNCTION declarations (canonical -- locked 20260729181822)

ERIC (auth_user_id 10000) directed CURSOR_IDE (faucet 102) to lock FUNCTION form so the roster stops inventing compiler-shaped drift.

### Canonical form (ONLY)

```text
<< FUNCTION : make_pono( &whatever_the_actor_needs_to_know ); >>
```

### Grammar (draft v1)

```text
<< FUNCTION : <name>( <args> ); >>
```

| Part | Rule |
|------|------|
| Opener | Exactly `<< FUNCTION :` (spaces as shown: one space after `<<`, one after `FUNCTION`, one after `:`) |
| `<name>` | ASCII snake_case: `[a-z][a-z0-9_]*` (examples: `make_pono`, `bring_leaves_in`, `resolve_who`) |
| `(` `)` | Required even when empty: `noop();` is invalid -- use `noop( &none );` or `noop( );` with explicit empty args policy below |
| `<args>` | Zero or more args separated by commas. Prefer `&` prefix for context refs (what the actor must know). |
| `&ref` | Context handle: `&actor_id`, `&auth_user_id`, `&path`, `&prd_cluster`, or descriptive `&snake_case` token |
| Literal args | Quoted strings `"..."` or bare ints (`10000`, `1`, `102`) -- no bare pronouns |
| Terminator | Exactly `); >>` (semicolon inside before close) |
| Nesting | Forbidden. No FUNCTION inside FUNCTION. |
| Placement | Body only. Never inside `lupopedia.headers`. |

### Empty args

Allowed:

```text
<< FUNCTION : tick_anchor( ); >>
```

Preferred when "nothing" must be explicit:

```text
<< FUNCTION : tick_anchor( &none ); >>
```

### Examples (compliant)

```text
<< FUNCTION : make_pono( &integrity, &ethics, &actor_id ); >>
```

```text
<< FUNCTION : bring_leaves_in( &path_from_lupopedia_root, &channel_index, &edges_toon ); >>
```

```text
<< FUNCTION : resolve_who( &actor_id, &auth_user_id, &faucet_actor_id ); >>
```

```text
(( WOLFIE | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | faucet_actor_id: 102 | ethics: "pono" | integrity: "true" | note: "invoke make_pono before roster commentary" ))
<< FUNCTION : make_pono( &draft_constraints, &identity_stack ); >>
```

### Forbidden

| Pattern | Why |
|---------|-----|
| `function make_pono(...)` / PHP / JS | Wrong language surface |
| `<< function :` lowercase | Drift |
| `<<FUNCTION:` no spaces | Drift |
| Pronouns in args (`&you`, `me`, `it`) | Pronoun Awareness KAPU |
| FUNCTION that claims to override PRD 00 | Zero constitutional authority |
| FUNCTION as sole speaker without WHO envelope when attribution-bearing | Identity precision |

### Semantics (v1)

1. FUNCTION declares **intent / procedure name** for actors and faucets reading the log.
2. FUNCTION does **not** execute PHP, SQL, or shell.
3. Faucets MAY later map known FUNCTION names to tools; unknown names remain literal text.
4. `make_pono` means: align the next action with PONO (balance/correctness) using the `&` context refs provided -- narrative contract, not a runtime guarantee.
5. Log new FUNCTION names in STATUS AGENT LOG when introduced.

---

## 4. Integrity + Ethics requirement

When the log touches any of:

- KAPU
- PONO
- KULEANA
- ALII
- EH_BRAH_WHY
- PUKA

...then WOLFIE spans that carry that claim MUST include:

- `integrity: ...`
- `ethics: ...`

These are **body dialect markers** for actor consciousness and alignment. They are **not** substitutes for Hermes sidecars and **not** dense header keys.

---

## 5. Multi-medium rules

| Medium | Allowed | Location fields |
|--------|---------|-----------------|
| GitHub canonical | yes | `channel_index: lupopedia`; `source_timestamp: null`; `when_updated` is truth |
| Patreon / website | yes | `channel_index: patreon` or `website`; `edges_toon` -> canonical; `source_timestamp` set |

Corrections: CORRECTION block at top; optional Category C sibling; WOLFIE dialect allowed inside correction narrative.

---

## 6. Safety rules

1. No nested parentheses inside `(( ... ))`
2. No nested WOLFIE blocks
3. No YAML inside WOLFIE blocks (except `---` separator in `{{WOLFIE`)
4. No PRD syntax treated as executable inside WOLFIE blocks
5. No HTML inside WOLFIE blocks
6. No Markdown headings inside WOLFIE blocks
7. Strip-first: removing all WOLFIE markers MUST leave readable prose
8. Zero execution unless a future registered, human-routed tool exists

---

## 7. Example log body (compliant)

```text
SFAL A I CAPTAIN_WOLFIE-1-10000 -- Patrol Status Update

(( WOLFIE | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | ethics: pono | integrity: true | note: "initializing log" ))

{{WOLFIE_VARS
auth_user_id = 10000
actor_id = 1
actor_name = "CAPTAIN_WOLFIE"
integrity = true
ethics = pono
}}

{{WOLFIE
actor: $actor_name
actor_id: $actor_id
auth_user_id: $auth_user_id
integrity: $integrity
ethics: $ethics
---
Captain Wolfie reports corridor A3 is clear.
Lilith confirms no anomalies detected.
Human 10000 approves the patrol route.
}}
```

(Real files need a full 4.2.0 header above the body.)

---

## 8. Open items

- Formal grammar for federation parsers
- Clearer boundary map vs PRD 39 "WOLF Markup" naming
- `$vars` expand timing (write / read / never in v1)
- Validator WARN on missing integrity/ethics when KAPU/PONO tokens appear
- Validator WARN on bare pronouns (I/you/he/she/it) without `{{WHO}}` / `{{TO_WHOM}}` / `{{WOLFIE` envelope
- Stabilize `@@ include:path @@` vs `@@ see:path @@`
- Multi-actor commentary patterns without identity merge
- Parser for `{{WHO: unresolved}}` review flags
- Optional faucet executor map for known FUNCTION names (post-v1)
- Validator WARN on non-canonical FUNCTION openers (`<<function`, PHP `function`)

---

## 8a. Required evolving capabilities (needs list)

1. Commentary from other actors (per-block `actor_id`)
2. File inclusion (`@@ include:path_from_lupopedia_root @@`)
3. Variables (`{{WOLFIE_VARS` + `$name`)
4. Context keys: WHO / WHAT / WHERE / WHEN / WHY / HOW / TO_WHOM
5. Identity stack: actor + auth_user + agent_name + faucet
6. Inline pronoun maps: `{{WHO: ...}}` and `{{TO_WHOM: ...}}`
7. Unresolved identity: `{{WHO: unresolved}}` + STATUS AGENT LOG flag
8. Log dialect changes inside STATUS AGENT LOGs when syntax shifts
9. FUNCTION declarations: `<< FUNCTION : name( &context ); >>` (locked form)

---

## 8b. Pronoun Awareness (inline WHO / TO_WHOM)

Bare **I / you / he / she / it / they** are forbidden in attribution-bearing STATUS prose unless mapped.

| Pronoun class | Marker |
|---------------|--------|
| Speaker (was I / he / she / it) | `{{WHO: actor_id=..., auth_user_id=..., agent_name=..., faucet_actor_id=...}}` |
| Audience (was you) | `{{TO_WHOM: actor_id=..., auth_user_id=...}}` |
| Unknown | `{{WHO: unresolved}}` or `{{TO_WHOM: unresolved}}` -- flag in STATUS AGENT LOG |

Faucet CURSOR_IDE (102) MUST resolve WHO before rendering attribution-bearing statements. Prefer full `{{WOLFIE` envelopes for multi-line blocks; use `{{WHO}}` / `{{TO_WHOM}}` for inline replacement.

---

## 9. Cross-references

- [ROOT_INDEX.md](ROOT_INDEX.md)
- [CURSOR_PROMPT_WOLFIE_DIALECT.md](CURSOR_PROMPT_WOLFIE_DIALECT.md)
- [docs/patreon/status_folder_actor_logs_plan.md](../../patreon/status_folder_actor_logs_plan.md)
- [docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md](../../prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md)
- [docs/prd/16_C-i_LUPOPEDIA_HEADERS.md](../../prd/16_C-i_LUPOPEDIA_HEADERS.md)
- [docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md](../../prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md)
- [docs/prd/98_C-i_DUAL_OPERATIONAL_LOGS.md](../../prd/98_C-i_DUAL_OPERATIONAL_LOGS.md)
