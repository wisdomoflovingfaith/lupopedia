---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: memory/channels/captains_log/canonical/1026/04/20260724_wolfie_syntax_meta_complete.md
  web_path: https://www.lupopedia.com/lupopedia/memory/channels/captains_log/canonical/1026/04/20260724_wolfie_syntax_meta_complete.md
  status: active
  when_updated: "20260724211400"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/channels/captains_log/canonical/1026/04/20260724_wolfie_syntax_meta_complete.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/channels/captains_log/wolfie-syntax-meta-complete
  artifact_type: captains_log
  artifact_kind: syntax_meta_reference
  channel_key: captains_log
  federation_node_id: 0
  thread_key: "FILESYSTEM_GROUNDING"
  lupopedia.schema: captains_log
  prd_cluster: 00_A_16_A_39_A_41_A_82_B_98_A
  title: Captain's Log — WOLFIE Syntax META Complete Reference
  summary: "Complete WOLFIE META map: (1) lupopedia.headers = location/truth of file, (2) WOLF Markup PRD 39 = 11 body layers + functions, (3) lupopedia.hermes = Hawaiian constitutional fields + temporal operators. Headers tell WHERE. Syntax tells HOW to speak."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---

# CAPTAIN'S LOG — WOLFIE SYNTAX META (COMPLETE)

**Channel index:** CAPTAIN LOG - TABLE OF CONTENTS  
**PATH:** content/federation_node/0/captains_log/FILESYSTEM_GROUNDING/2026/07/20260724_wolfie_syntax_meta.md  
**Prerequisite See:** LUPOPEDIA HEADERS & FILESYSTEM NAVIGATION · PRD 39 · PRD 82_B · PRD 41  
**Last Update Date:** July 24, 2026  

---

## SUBJECT: All WOLFIE Syntax From The META

**Captain's Log, Stardate 2026.07.24**

Eric asked: I need to know **all** the WOLFIE Syntax from the WOLFIE META.

Answer: WOLFIE META has **three layers**. Headers alone are not the syntax. Headers are the map. Syntax is the language.

```text
LAYER 1 — lupopedia.headers     = WHERE you are (filesystem truth)
LAYER 2 — WOLF Markup (PRD 39)  = HOW the body is decorated
LAYER 3 — lupopedia.hermes      = WHAT the handoff means (constitutional)
```

Canonical sources:
- Headers: PRD 16_C
- WOLF layers: PRD 39 (`docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md`)
- HERMES fields: PRD 82_B (`docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md`)
- Teaching paste: `agents/cursor/WOLFIE_SYNTAX_TEACHING_DOCUMENT.md`

---

## LAYER 1 — HEADERS (META LOCATION)

Read headers first. That is your `pwd`.

| Field | Meaning |
|-------|---------|
| `header_format_version` | schema version |
| `path_from_lupopedia_root` | where file lives |
| `web_path` | external URL path |
| `status` | active / draft / deprecated / canonical |
| `when_updated` | UTC BIGINT timestamp |
| `trust_tier` | canonical / context / draft |
| `artifact_type` / `artifact_kind` | what kind of file |
| `channel_key` | which channel |
| `federation_node_id` | which node |
| `prd_cluster` | which PRDs govern |
| `title` + `summary` | human meaning |
| `memory_toon` / `atoms_toon` | memory pointers |
| `transcript_jsonl` | message log path |
| `lupopedia.schema` | governing schema |
| `thread_key` | thread / incident key |

**Rule:** WOLF markers MUST NOT appear inside the `lupopedia.headers` envelope.

---

## LAYER 2 — WOLF MARKUP (ALL 11 LAYERS + FUNCTION)

W.O.L.F. = Wolfie's OverLook Format. Decorative overlay. **Zero constitutional authority.** Strip recovers canonical text.

| Order | Symbol | Name | Use |
|------:|--------|------|-----|
| 1 | `!! ... !!` | Force / Impact | Strong emphasis |
| 2 | `{{ ... }}` | Motion / Kinetic | Movement cue |
| 3 | `@@ ... @@` | Reference / Link | Semantic pointer |
| 4 | `^^ ... ^^` | Elevate / Promote | Highlight |
| 5 | `vv ... vv` | Sink / Demote | De-emphasize |
| 6 | `>> ... >>` | Flow / Next | Progression |
| 7 | `<< ... >>` | Recall / Invoke | Memory OR function annotation |
| 8 | `~ ... ~` | Draft / Fuzzy | Provisional (single tilde ONLY) |
| 9 | `## ... ##` | Structural meta | Section metadata (not ATX headings) |
| 10 | `[narrative: ...]` | Scene direction | Non-dialogue world-state |
| 11 | `Speaker (mood):` | Dialogue block | Speaker + mood shading |

### Function form (specialization of layer 7)

```text
<< identifier(parameters) >>
```

- Annotation only by default
- Execute ONLY if registered AND human routes it
- Identifier: letter/underscore start; ASCII letters/digits/underscore
- Params: comma-separated; quoted strings or bare tokens (no spaces)
- Nested parentheses forbidden in v0.5

### Grammar hard rules

1. Balanced delimiters
2. No same-layer nesting
3. Max nesting depth = **4** different layers
4. `~~ ... ~~` INVALID for draft (Markdown strikethrough collision)
5. Empty `[narrative: ]` INVALID

### Strip order (recovery)

1. `<< ... >>`
2. `>> ... >>`
3. paired layers 1–9 (outermost first)
4. `[narrative: ...]` wrappers
5. `Speaker (mood):` prefixes
6. optional blank-line normalize

---

## LAYER 3 — HERMES (CONSTITUTIONAL SEMANTIC FIELDS)

WOLF decoration is NOT a substitute for these fields.

### Required-to-exist

| Field | Meaning |
|-------|---------|
| `kapakai` | what is wrong (problem) |
| `pono` | what should be true (outcome) |

(Value may be null. Field must exist. Never invent "handled/success".)

### Core Hawaiian / Pidgin fields

| Field | Meaning |
|-------|---------|
| `ohana` | participants (array) |
| `kapu` | DO NOT rules (array) |
| `kuleana` | who must act/fix |
| `alii` | who decides |
| `kumu` | teacher / source / PRD cite |
| `eh_brah_why` | root-cause / audit why |
| `puka` | deterministic structural gap only |

### Temporal operators (optional, non-constitutional)

| Operator | Meaning |
|----------|---------|
| `bumbye` | later / queue |
| `now_now` | immediately |
| `shoots` | ACK / proceed |
| `pau` | done |
| `holo` | kickoff / run |
| `wikiwiki` | fast |

### Safety vocabulary

| Term | Means | Does NOT mean |
|------|-------|---------------|
| `pilau` | not pono | smell |
| `stink_eye` | social warning | hygiene |
| `kanaka` | human / person | ethnicity |
| `kanak` | behavior only | ethnicity |
| `talk_story` | non-executable exploration | instructions |

---

## HOW THE THREE LAYERS FIT ONE FILE

```text
---
lupopedia.headers:          ← LAYER 1 META (where / what kind)
  path_from_lupopedia_root: ...
  artifact_type: captains_log
  ...
---

# Title

!! ROUTING STABILIZED !!     ← LAYER 2 WOLF (body overlay)

{{ Eric is steady }}

@@ docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md @@

lupopedia.hermes:            ← LAYER 3 HERMES (meaning / routing)
  kapakai: "..."
  pono: "..."
  kuleana: "..."
  alii: "..."
```

**pwd analogy:**
- headers = `pwd`
- `cd ..` in OS folders = go up directories
- in Lupopedia meaning-space: follow `path_from_lupopedia_root`, then read `artifact_type`, then speak with WOLF + HERMES

---

## ONE-LINE META

**Headers = where. WOLF = how it looks. HERMES = what it means.**

That is all WOLFIE Syntax from the META.

---

**END LOG**

**Canonical teaching paste:** `agents/cursor/WOLFIE_SYNTAX_TEACHING_DOCUMENT.md`  
**Authoritative specs:** PRD 39 · PRD 82_B · PRD 16_C · PRD 41
