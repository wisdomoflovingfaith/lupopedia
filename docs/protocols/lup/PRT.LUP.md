---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/protocols/lup/PRT.LUP.md
  web_path: https://www.lupopedia.com/lupopedia/docs/protocols/lup/PRT.LUP.md
  status: active
  when_updated: "20260816115226"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/prt-lup-protocol
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 16_C_34_A_99_A
  title: "PRT.LUP -- Lupopedia Universal Protocol (KEY 4.2.26 on contract 4.2.11)"
  summary: "Canonical LUP protocol article. Eight dotted tokens always. Registered shorts only. Artifact packed VERSION 04021A is 4.2.26. Header contract remains 4.2.11."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: federation
  faucet_actor_id: 102
lupopedia.identity:
  LUPOPEDIA: PRT.LUP
  LUP.KEY: PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX: PRT.HEX.000001.000017.000000.ROOT.EN.04021A
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
  key_specification_version: "4.2.26"
lupopedia.map:
  index: PRT.HEX.000001.000017.000000.ROOT.EN.04021A
  web_path: https://www.lupopedia.com/lupopedia/docs/protocols/lup/PRT.LUP.md
  path_from_lupopedia_root: docs/protocols/lup/PRT.LUP.md
  prd_cluster: 16_C_34_A_99_A
  edges_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/prt-lup-protocol
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# Protocol: LUP (Lupopedia Universal Protocol)

**Header contract:** `header_format_version: "4.2.11"` (PRD 16_C section 4.2.6)
**KEY specification:** `key_specification_version: "4.2.26"`
**This article KEY VERSION:** `04021A` (packed 4.2.26)
**Product atom:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` = 4.2.11
**Last updated UTC:** 20260815212117
**Supersedes:** mixed 4.2.9 / 4.2.10 pipe-colon drafts (compiled outside this Cursor workspace)
**Status:** Canonical protocol article (4.2.11 contract, 4.2.26 KEY expansion)
**Project:** Lupopedia
**Normative header template:** `docs/prd/federation/federation_map_template.md`

```text
LUPOPEDIA     = PRT.LUP
LUP.KEY       = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
LUP.HEX       = PRT.HEX.000000.000000.000000.ROOT.EN.04020B
LUP.SHORT     = PRT.LUP
LUP.ROOT      = PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
LUP.OMIT      = REGISTERED_SHORT_FORMS_ONLY
LUP.DEFAULTS  = PRT.NAME.000000.000000.ROOT.ROOT.EN.0
```

YAML storage uses `key: value`. Grammar notation may use `=`. Values MUST match.

LUP names:

- LUP -- Lupopedia LLC 2026
- LUP -- Linked Universal Protocol
- LUP -- Logical Universal Pointer
- LUP -- Lupopedia Universal Protocol

Header YAML, metadata, and map blocks are not extra KEY fields. Do not copy the 28-field dense grid into `lupopedia.metadata`.

---

## TL;DNR -- Protocol: LUP (v4.2.11)

LUP is a universal identity system that gives every artifact, node, actor, and group in the Lupopedia federation a single, unambiguous, machine-readable identity.

It uses a strict eight-token KEY, ASCII dot delimiters, registered short forms, and packed versioning so agents can parse, classify, and retrieve artifacts without guessing.

The protocol enforces:

- Eight KEY tokens in fixed order
- Strict uppercase A-Z / 0-9 in NAME segments; six uppercase hex in HEX fields
- PROTOCOL and MODE as separate KEY fields (not `PROTOCOL|MODE`)
- Field delimiter `.` (ASCII 46) in KEY / HEX / SHORT / ROOT
- YAML `key: value` for file storage (colon is YAML, not a KEY delimiter)
- HEX or NAME profiles depending on MODE
- Packed VERSION `0xMMmmPP` (`04020A` = 4.2.10, `04020B` = 4.2.11, `04021A` = 4.2.26). Never `042010`
- Registered short-form productions only; arbitrary default omission is forbidden
- Deterministic parse, expand, and fail-closed rules
- Zero ambiguity, zero guessing, zero hallucination

It is designed so every AI can find anything from the ID, even across thousands of nodes, artifacts, and versions.

---

## Why this ID matters

### 1. It makes information findable

Most systems rely on filenames, tags, categories, metadata, and human naming conventions. Those drift and collide.

LUP gives every artifact a globally unique, self-describing identity. If you know the ID, you can find the artifact -- anywhere, on any node, in any system.

This is the same class of breakthrough that made ISBNs find books, DNS find servers, MAC addresses find devices, and UUIDs find objects. LUP does this for knowledge.

### 2. It eliminates AI guessing

Agents normally guess what something is, where it belongs, who created it, what version it is, and what node it came from.

LUP removes that guess. The KEY itself contains protocol, mode, node, artifact, actor, group, language, and version.

Agents do not need extra context, tags, or descriptions to classify the ID. They read the KEY, apply DEFAULTS/OMIT, and fail closed if a field is illegal.

### 3. It makes federation possible

Federation only works if nodes can trust identities, identities do not collide, artifacts do not overwrite each other, and lineage is explicit.

LUP keeps current node, artifact number, actor, and group in positional fields. Deeper ancestry lives in typed edges / `lupopedia.map`, not packed extra dots inside a KEY token.

### 4. It makes search deterministic

Search engines struggle with synonyms, duplicates, ambiguous names, partial matches, and fuzzy metadata.

LUP gives every artifact a canonical machine identity. Lookup is exact KEY match after OMIT expansion, not fuzzy naming.

### 5. It future-proofs the system

Because LUP is strict, encoded, versioned, protocol-aware, mode-aware, machine-readable, and human-compressible, it still works across new nodes, modes, versions, and models.

It is not a naming convention. It is a semantic backbone.

### Ultra-short

LUP gives every artifact a perfect identity. Perfect identity makes artifacts findable. Findable artifacts make knowledge searchable. Searchable knowledge makes federation possible. Federation makes Lupopedia scale.

---

## Table of Contents

1. Executive Summary
2. Why Version 4.2.11 Exists
3. Document Identity
4. Canonical Eight-Token Grammar
5. Token Definitions
6. Structural Marker Rules
7. Protocol and Mode
8. OMIT, DEFAULTS, SHORT, and ROOT
9. Character and Length Rules
10. NAME and HEX Profiles
11. Canonical Examples
12. Federation and Lineage
13. Language and Version Rules
14. Parsing and Validation
15. Normalization and Migration
16. AI Interpretation Requirements
17. Root Identities for v4.2.11
18. Reference Addendum
19. Editorial and Implementation Notes

---

## 1. Executive Summary

The Lupopedia Universal Protocol (LUP) is a portable semantic identity system for artifacts across independent federation nodes.

A complete LUP KEY contains exactly eight tokens in this order:

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

The bracketed names are grammar labels. They are replaced by registered values. They are not literal identity text except in the `LUP.KEY` constant itself.

Version 4.2.11 assigns markers as follows:

| Marker | Where | Meaning |
|--------|-------|---------|
| `.` | KEY / HEX / SHORT / ROOT | Field delimiter (ASCII 46) |
| `key: value` | YAML files | Storage form. Colon is YAML, not a KEY delimiter. |
| `=` | Grammar notation | Allowed in prose / templates. Not stored in YAML. |
| `:` | HEX ARTIFACT token only | Optional lineage `originFed:artifactNumber`. Native artifacts have no colon. |

No pipe. No hyphen. No middle-dot in KEY / HEX / SHORT / ROOT.

Protocol example (zeros):

```text
PRT.HEX.000000.000000.000000.ROOT.EN.04020B
```

Human compressed form:

```text
PRT.LUP
```

Human expanded ROOT:

```text
PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
```

Header YAML is metadata plus identity plus map. It is not a ninth KEY token.

Normative requirements for authored envelopes remain in **PRD 16_C**. This page is the protocol article. It does not override PRDs.

---

## 2. Why Version 4.2.11 Exists

Drafts 4.2.9 and 4.2.10 (compiled outside this Cursor workspace) used a seven-element colon-separated string and a pipe inside E1:

```text
[PROTOCOL|MODE]:[NODE]:[OBJECT]:[ACTOR]:[GROUP]:[LANGUAGE]:[VERSION]
```

That mix is **outdated**. It is **not** the 4.2.11 KEY.

Problems with the 4.2.9 / 4.2.10 mix:

- YAML already uses `: ` for `key: value`. Colon-separated KEY strings collide with YAML.
- Pipe `|` breaks ASCII KEY grammar and markdown/table parsing.
- Hyphen KEY forms from 4.2.4 (`LUP:FFFFFF-RRRRRR-NN-II-LL-AA`) are dual-accept only until a file is edited.
- PROTOCOL and MODE are two KEY fields, not one field joined by `|`.
- This Cursor workspace indexed identity at 4.2.4, then 4.2.11. Do not invent 4.2.5-4.2.10 files here.

Version 4.2.11 therefore:

- Uses `.` as the only KEY field delimiter.
- Splits PROTOCOL and MODE into adjacent KEY tokens.
- Stores identity in YAML as `key: value`.
- Uses registered short productions plus LUP.DEFAULTS instead of packing `PARENT.CHILD` extra dots inside a token (extra dots would break split-on-dot). Arbitrary middle-field omission is forbidden.
- Keeps HEX artifact lineage, when present, as a colon **inside** the ARTIFACT token only.

Implementations must migrate deliberately. They must never reinterpret a stored 4.2.9 / 4.2.10 pipe-colon identifier as 4.2.11 without version-aware conversion.

---

## 3. Document Identity

### 3.1 Human-facing page identity

```text
PRT.LUP
```

Expand SHORT to ROOT when a full human form is required:

```text
PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
```

| Token | Value | Meaning |
|-------|-------|---------|
| PROTOCOL | PRT | Protocol documentation |
| MODE | NAME | Present in the stored KEY; omitted only in registered SHORT_ARTIFACT display |
| NODE | 000000 | Unspecified / example |
| ARTIFACT | LUP | This protocol object |
| ACTOR | ROOT | Default |
| GROUP | ROOT | Default; always stored |
| LANGUAGE | EN | Default |
| VERSION | 04020A | Packed 4.2.10 (`0xMMmmPP`). Never `042010` |

### 3.2 Complete machine identity (this file)

Canonical exchanged and database identities use HEX MODE. This document's `lupopedia.map.index` is:

```text
PRT.HEX.000001.000017.000000.ROOT.EN.04021A
```

| Token | Value | Meaning |
|-------|-------|---------|
| PROTOCOL | PRT | Protocol |
| MODE | HEX | Machine profile |
| NODE | 000001 | Federation node |
| ARTIFACT | 000017 | This article |
| ACTOR | 000000 | Padding / default actor slot |
| GROUP | ROOT | Always stored. HEX fill default |
| LANGUAGE | EN | English |
| VERSION | 04021A | Packed 4.2.26 -- this article's content version |

The YAML header is metadata. It is not an extra KEY token.

Protocol zeros example (grammar constant, not this file's map.index):

```text
PRT.HEX.000000.000000.000000.ROOT.EN.04020B
```

---

## 4. Canonical Eight-Token Grammar

### 4.1 Required KEY order

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

Do not reorder. Validators emit `HDR_LUP_KEY_ORDER` if `LUP.KEY` is not exactly that string.

### 4.2 Runtime form after DEFAULTS

A full identity contains eight tokens. OMIT may hide MODE when MODE is NAME, and may hide any token that equals its default. Parsers expand missing tokens from LUP.DEFAULTS before assigning meaning.

### 4.3 DEFAULTS

```text
PRT.NAME.000000.000000.ROOT.ROOT.EN.0
```

| Position | Default |
|----------|---------|
| PROTOCOL | PRT |
| MODE | NAME |
| NODE | 000000 |
| ARTIFACT | 000000 |
| ACTOR | ROOT |
| GROUP | ROOT |
| LANGUAGE | EN |
| VERSION | 0 |

`PRT.LUP` expands to `PRT.NAME.000000.LUP.ROOT.ROOT.EN.0`. Colon-bag grammar (named protocols joined by colons) is not LUP.

### 4.4 Position controls meaning

A token's meaning comes first from its position and then from its registry. A parser must not decide that a token is an actor, artifact, group, color, or format merely from its spelling or length.

---

## 5. Token Definitions

### 5.1 PROTOCOL

Governing protocol. Baseline value `PRT`. Other registered protocols (example `CCB` for CC-BY objects) are allowed when the registry says so.

### 5.2 MODE

Interpretation profile:

- `NAME` -- human names
- `HEX` -- machine six-hex fields

MODE is `NAME` or `HEX`. Those strings are reserved as token 2. They are not artifact slugs. Display may omit MODE only via registered SHORT_ARTIFACT (`PRT.LUP`). Storage always includes MODE.

### 5.3 NODE

Current federation node. Always HEX6. Default `000000` (unspecified). Live federation nodes begin at `000001`.

### 5.4 ARTIFACT

Artifact / object identity. NAME default `000000`. `PRT.LUP` sets ARTIFACT to `LUP`. HEX uses a 6-hex artifact number.

Colon lineage is allowed **inside this token only**:

```text
originFed:artifactNumber
```

No colon means native. Do not put a colon in PROTOCOL, MODE, NODE, ACTOR, GROUP, LANGUAGE, or VERSION.

### 5.5 ACTOR

Creator, responsible actor, originator, or authority. NAME default `ROOT`. HEX uses 6-hex actor padding.

### 5.6 GROUP

Registered group, type, or namespace. Default `ROOT`. Always present in the stored eight-token KEY. HEX fill does not omit GROUP.

Registry meaning overrides guesses based on token length. `RED` is not automatically a file-format code because it contains three characters. Color is `lupopedia.metadata` / Rule 99, not a KEY token.

### 5.7 LANGUAGE

Registered uppercase language code. Baseline ISO 639-1 (`EN`, `FR`, `JA`). Reserved `ZZ` means multi-language. Default `EN`.

### 5.8 VERSION

Packed `0xMMmmPP`. `4.2.10` = `04020A`. `4.2.11` = `04020B`. `4.2.26` = `04021A`. Never `042010`. Default `0`.

---

## 6. Structural Marker Rules

| Marker | Canonical function | Example |
|--------|--------------------|---------|
| `.` | Separates KEY tokens | `PRT.LUP` |
| YAML `: ` | Stores `key: value` | `LUP.SHORT: PRT.LUP` |
| `=` | Grammar notation only | `LUPOPEDIA = PRT.LUP` |
| `:` inside ARTIFACT | HEX lineage only | `000001:000010` |

The marker meanings are exclusive:

- A period in KEY is a field delimiter, not protocol composition and not inheritance packing.
- A pipe must not appear in KEY / HEX / SHORT / ROOT (`HDR_LUP_DELIM`).
- A hyphen must not appear in KEY / HEX / SHORT / ROOT (`HDR_LUP_DELIM`).
- A middle-dot must not appear (`HDR_LUP_DELIM`).
- Serializers must not substitute underscores, spaces, or hyphens for the KEY delimiter.

---

## 7. Protocol and Mode

### 7.1 Canonical form

```text
PROTOCOL.MODE
```

Examples:

```text
PRT.NAME
PRT.HEX
CCB.NAME
CCB.HEX
```

When MODE is NAME, OMIT drops it:

```text
PRT.LUP
```

not:

```text
PRT.NAME.LUP
```

unless you are writing an explicit expanded form that still has other non-default fields.

### 7.2 Protocol rules

- PROTOCOL and MODE are two KEY tokens, not one token with a pipe.
- Both NAME segments contain only uppercase A-Z and 0-9.
- The protocol and mode must be registered.
- Pipe is not permitted anywhere in KEY grammar values.

### 7.3 Invalid protocol forms (do not emit as 4.2.11)

```text
PRT|HEX
CCB|NAME
CCB.HEX as a single E1 with colon fields after it
CCB-HEX
CCB_HEX
CCB HEX
|CCB
CCB|
```

Canonical replacements:

```text
CCB.HEX
CCB.NAME
PRT.HEX
PRT.NAME
```

---

## 8. OMIT, DEFAULTS, SHORT, and ROOT

### 8.1 OMIT -- registered productions only

```text
LUP.OMIT = REGISTERED_SHORT_FORMS_ONLY
```

A positional grammar cannot omit arbitrary middle fields. `PRT.LUP.WHEEL` cannot choose ACTOR vs GROUP. `PRT.HEX.000022` cannot choose NODE vs ARTIFACT unless the production is registered.

**Display** may use only the productions below. **Storage, comparison, federation, hashing, and APIs always use the complete eight-token KEY.**

### 8.2 Algorithm (Option A)

1. Split on `.`. Empty tokens are invalid.
2. Match **exactly one** production below (token count plus MODE precedence).
3. Fill missing slots from the NAME or HEX fill table.
4. Require eight tokens. Then validate.

MODE precedence: if token 2 exists and is exactly `NAME` or `HEX`, token 2 is MODE. Those two strings are reserved. They are not artifact slugs.

| Production | Tokens | Example in | Example out |
|------------|--------|------------|-------------|
| SHORT_PROTOCOL | 1 | `PRT` | `PRT.NAME.000000.000000.ROOT.ROOT.EN.0` |
| SHORT_MODE | 2, token2 is NAME or HEX | `PRT.HEX` | `PRT.HEX.000000.000000.000000.ROOT.EN.0` |
| SHORT_ARTIFACT | 2, token2 is not NAME or HEX | `PRT.LUP` | `PRT.NAME.000000.LUP.ROOT.ROOT.EN.0` |
| SHORT_MODE_NODE | 3 | `PRT.HEX.000001` | `PRT.HEX.000001.000000.000000.ROOT.EN.0` |
| SHORT_MODE_NODE_ARTIFACT | 4 | `PRT.NAME.000001.00ABCD` | `PRT.NAME.000001.00ABCD.ROOT.ROOT.EN.0` |
| FULL_KEY | 8 | any complete KEY | as written |

HEX fill: NODE / ARTIFACT / ACTOR default `000000`. GROUP default `ROOT`.
NAME fill: NODE / ARTIFACT default `000000`. ACTOR / GROUP default `ROOT`.
LANGUAGE default `EN`. VERSION default `0`.

Any other token count or shape is invalid. Do not guess.

### 8.3 SHORT

```text
LUP.SHORT = PRT.LUP
```

SHORT is the registered production SHORT_ARTIFACT. It is display-only.

### 8.4 ROOT

```text
LUP.ROOT = PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
```

ROOT is FULL_KEY (eight tokens). Packed VERSION `04020A` is 4.2.10. Do not write `PRT.LUP.ROOT.ROOT.EN.042010`.

### 8.5 Do not pack PARENT.CHILD extra dots

4.2.9 used `ALT.WOLFIE` inside a colon-separated field because `.` meant inheritance then.

In 4.2.11, `.` splits KEY tokens. `ALT.WOLFIE` would parse as two tokens and land in the wrong positions.

Immediate lineage belongs in:

- HEX ARTIFACT colon form `originFed:artifactNumber` when the artifact is derived; or
- typed edges (`edges_toon`, map, graph records).

Deeper ancestry:

```text
ROOT -> ALT
ALT -> WOLFIE
```

A parser must not collapse a HEX artifact number by dropping node or lineage. Copying an artifact to another node must not erase provenance stored in ARTIFACT lineage or edges.

---

## 9. Character and Length Rules

### 9.1 NAME segments

NAME segments contain only:

```text
A-Z 0-9
```

Not permitted in canonical NAME tokens:

- spaces
- underscores
- hyphens
- lowercase letters
- slashes
- pipes
- middle-dot
- unrelated punctuation

### 9.2 HEX fields

A field declared as hexadecimal must contain exactly six uppercase hexadecimal characters:

```text
^[0-9A-F]{6}$
```

Examples:

```text
000000
000001
808080
FFFFFF
```

Language remains a language code in HEX mode. A parser must not convert `EN` into hexadecimal.

### 9.3 Element length

Every complete NAME token contains 1-22 characters. HEX positional fields are exactly 6 characters except LANGUAGE (2, or `ZZ`) and VERSION (packed token).

---

## 10. NAME and HEX Profiles

### 10.1 NAME profile

Human profile. MODE is NAME. Display may use registered SHORT_ARTIFACT (`PRT.LUP`). Stored KEY:

```text
PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
```

Full eight-token expansion of DEFAULTS:

```text
PRT.NAME.000000.000000.ROOT.ROOT.EN.0
```

### 10.2 HEX profile

Declared by MODE=HEX:

```text
PRT.HEX
CCB.HEX
```

GROUP is always stored (`ROOT` in HEX fill). Protocol zeros example:

```text
PRT.HEX.000000.000000.000000.ROOT.EN.04020B
```

### 10.3 Semantic translations

Actor, object, node, and group names may resolve through registries. Resolution must use field type, language, and registry provenance. A shared HEX value does not make two field meanings identical. Color HEX is not identity.

---

## 11. Canonical Examples

### 11.1 Root protocol document (human)

```text
PRT.LUP
PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
```

### 11.2 This article (machine)

```text
PRT.HEX.000001.000017.000000.ROOT.EN.04021A
```

### 11.3 Local CC-BY object (NAME)

```text
CCB.NAME.PRT.BLACKONE.ROOT.MUSIC.EN.01
```

OMIT may drop MODE and default NODE/ACTOR when they match DEFAULTS and the remaining string stays unambiguous. Do not OMIT if dropping a field would collide with another identity.

### 11.4 CC-BY HEX profile

```text
CCB.HEX.000000.000001.000000.ROOT.EN.000001
```

### 11.5 Derived HEX artifact (colon lineage inside ARTIFACT)

```text
PRT.HEX.000001.000001:000010.000000.EN.04020A
```

`000001:000010` is one ARTIFACT token (origin federation 000001, artifact 000010). It is not two KEY fields.

### 11.6 Invalid 4.2.11 forms (historical 4.2.9 / 4.2.10)

Do not emit:

```text
LUP|NAME:PRT:LUP:ROOT:ROOT:EN:040209
CCB|HEX:000000:000001:000000:000000:EN:000001
CCB|NAME:ALT.WOLFIE:ALT.LUPOPEDIAIT:ROOT:SYS:EN:01
```

---

## 12. Federation and One-to-Many Lineage

Each federation node may maintain local registries while honoring the 4.2.11 KEY grammar. Registries may narrow the values allowed in a position. They may not redefine the structural markers.

Implementations must:

- distinguish current NODE from source lineage
- preserve ARTIFACT colon lineage when present
- resolve a name using its field position and language
- reject ambiguous or unresolved lookups
- retain provenance when an object is extended
- store additional ancestry as typed relationships / edges

An object may have multiple parents in metadata. A canonical ID token represents only one immediate lineage statement. Multiple parents must not be packed into extra dots:

```text
ALT.ROOT.WOLFIE
```

Instead, store separate edges such as:

```text
WOLFIE INHERITSFROM ALT
WOLFIE DERIVEDFROM ROOT
```

`lupopedia.map` is the federation routing index for the file. `map.index` MUST be a valid LUP.HEX for **that document**. It does not replace the dense 28-field header grid.

---

## 13. Language and Version Rules

### 13.1 Language

LANGUAGE contains a registered uppercase language code:

```text
EN
FR
JA
ZZ
```

`ZZ` is reserved multi-language. It is not ISO 639-1. Language is not packed with extra dots.

### 13.2 Version model

Protocol releases use:

```text
MAJOR.MINOR.PATCH
```

Compact HEX identity encoding pads each part and may use hex for the patch when it exceeds 9:

```text
4.2.9  -> 040209
4.2.10 -> 04020A
4.2.11 -> 04020B
```

Packed VERSION is `0xMMmmPP`. Locked examples:

- Zeros HEX VERSION `04020B` (4.2.11 header-contract packing)
- `LUP.ROOT` VERSION `04020A` (4.2.10)
- This article VERSION `04021A` (4.2.26 content)

`042010` is not a packed version. Do not conflate header contract `4.2.11`, product atom `4.2.11`, KEY-grammar clarification `4.2.26`, and packed tokens.

### 13.3 Increment discipline

- PATCH: corrections, clarifications, and explicitly authorized compatible protocol refinements.
- MINOR: packaged compatible capabilities.
- MAJOR: broad structural redesign.

Version 4.2.11 is the designated Cursor-indexed KEY grammar after 4.2.4. Parser behavior is not interchangeable with 4.2.8 hyphen/pipe drafts or 4.2.9 / 4.2.10 colon-pipe drafts without version-aware conversion.

---

## 14. Parsing and Validation

### 14.1 Required parsing order

1. Preserve the original string and declared `header_format_version`.
2. Read YAML `lupopedia.identity` as `key: value` when parsing a file.
3. Require `LUP.KEY` equal to `PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION`.
4. Split HEX / SHORT / ROOT values on `.`.
5. If the token count is not 8, expand only a registered short-form production (section 8.2). Any other shape is invalid.
6. Validate PROTOCOL and MODE as registered NAME tokens.
7. Validate NODE, ARTIFACT, ACTOR, GROUP per NAME or HEX rules declared by MODE.
8. Allow `:` only inside ARTIFACT, and only as lineage `originFed:artifactNumber`.
9. Validate LANGUAGE as ISO 639-1 or `ZZ`.
10. Validate VERSION as a registered packed token.
11. Require `lupopedia.map.index` to be a valid LUP.HEX for that document.
12. Return the identity or a deterministic error.

Validator: `python scripts/validate_lup_identity.py PATH`

Codes: `HDR_LUP_KEY_ORDER`, `HDR_LUP_HEX`, `HDR_LUP_MAP_REQUIRED`, `HDR_LUP_DELIM`.

### 14.2 Parser errors

A parser must reject:

- unknown protocol or mode
- KEY token order that is not LUP.KEY
- an empty token
- lowercase or prohibited characters in NAME tokens
- pipe, hyphen, or middle-dot in KEY grammar values
- `.` used to mean inheritance inside a token
- `|` used to compose protocol and mode
- a delimiter at the beginning or end
- malformed HEX
- colon outside ARTIFACT
- unsupported language
- unresolved or ambiguous lookup
- malformed or lost lineage
- an unrepresentable version

### 14.3 Parser prohibitions

A conforming parser must not:

- guess a missing field except via documented DEFAULTS/OMIT
- infer semantics only from the visual token
- silently convert `|` or `:` field separators into dots
- strip ARTIFACT lineage
- repair malformed input without reporting the change
- register a normalized collision
- increase a version merely because an identity was reparsed

---

## 15. Normalization and Migration

### 15.1 Critical version boundary

The same symbols have different meanings across protocol drafts. Migration requires the source version. If the source version is unknown, the identifier is ambiguous and automatic conversion must stop.

| Meaning | v4.2.8 (historical) | v4.2.9 / v4.2.10 (outdated mix) | v4.2.11 (current) |
|---------|---------------------|----------------------------------|-------------------|
| Field separator | mixed | `:` between seven elements | `.` between KEY tokens |
| Protocol and mode | `CCB.HEX` as one E1 | `CCB\|HEX` as E1 | `CCB.HEX` as PROTOCOL.MODE |
| Inheritance packing | pipe inside fields | `ALT.WOLFIE` inside a colon field | edges, or `:` inside HEX ARTIFACT only |
| YAML | n/a | often collided | `key: value` |

### 15.2 Required conversion into 4.2.11

```text
LUP|NAME:PRT:LUP:ROOT:ROOT:EN:040209  ->  PRT.NAME.000000.LUP.ROOT.ROOT.EN.040209
LUP|HEX:000000:000000:000000:EN:04020A  ->  PRT.HEX.000000.000000.000000.ROOT.EN.04020A
CCB|NAME:PRT:BLACKONE:ROOT:MUSIC:EN:01  ->  CCB.NAME.000000.BLACKONE.ROOT.MUSIC.EN.01
CCB|HEX:000000:000001:000000:000000:EN:000001  ->  CCB.HEX.000000.000001.000000.ROOT.EN.000001
CCB.HEX  (4.2.8 E1)  ->  CCB.HEX as PROTOCOL.MODE (not a seven-colon string)
ALT|WOLFIE  ->  do not put in KEY; store as an edge
ALT.WOLFIE as a colon-field value  ->  do not put in KEY; store as an edge
```

Conversion is positional:

- Map old E1 `PROTOCOL|MODE` to PROTOCOL and MODE tokens.
- Map old colon-separated E2-E7 onto NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION.
- After conversion, NODE must be HEX6 (`000000` if the old NODE was a default slug).
- Always emit eight tokens. HEX GROUP is `ROOT` when the old string omitted it.
- Do not perform a global character swap over prose, metadata, URLs, or unrelated data.

### 15.3 Legacy separator conversion

Known illegal protocol spellings may normalize to dotted PROTOCOL.MODE:

```text
CCB-HEX -> CCB.HEX
CCB_HEX -> CCB.HEX
CCB HEX -> CCB.HEX
CCB|HEX -> CCB.HEX
```

These conversions require field knowledge. They are not safe as context-free search-and-replace operations.

### 15.4 Dual-accept

Unedited 4.2.0-4.2.4 files remain valid (hyphen LUP). Dual-accept until the file is edited. Do not mass-rewrite the corpus. Do not mix hyphens into new 4.2.11 KEY values.

### 15.5 Collision handling

If normalization produces an existing identity with different provenance, the migration must stop and return a collision error. The original input, proposed canonical value, source version, and decision must be retained for audit.

---

## 16. AI Interpretation Requirements

Visual familiarity is not a substitute for validation.

An AI or agent receiving an identity must:

1. read or retrieve `header_format_version` / governing protocol version
2. treat YAML `key: value` as storage, not as KEY delimiter
3. require LUP.KEY order before assigning meaning
4. split HEX / SHORT / ROOT on `.`
5. expand OMIT using DEFAULTS
6. interpret PROTOCOL and MODE as two tokens
7. allow `:` only inside ARTIFACT lineage
8. preserve HEX artifact lineage
9. ask or fail when the source version is missing and marker meaning is ambiguous

An AI must not claim that `PRT.LUP` is a protocol-mode pipe form. It must not claim that `CCB.HEX` is ancestry. It must not put color into KEY. Field position and 4.2.11 rules control interpretation.

---

## 17. Root Identities for v4.2.11

Human-facing page identity:

```text
PRT.LUP
```

Human expanded ROOT:

```text
PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
```

Protocol zeros HEX example:

```text
PRT.HEX.000000.000000.000000.ROOT.EN.04020B
```

This file's map.index:

```text
PRT.HEX.000001.000017.000000.ROOT.EN.04021A
```

Canonical KEY:

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

DEFAULTS:

```text
PRT.NAME.000000.000000.ROOT.ROOT.EN.0
```

---

## 18. Reference Addendum

### 18.1 Format and type examples

```text
DOC TXT MP3 WWW IMG VID MUS PRD CODE AI
```

These are registry examples. Their meaning depends on field position and the governing registry. Variant indexes may list `TYPE.NUM.LANG` in the document body. That token is navigation-only and is not LUP.KEY. See `docs/actors/how_to_make_variant_indexes.md`.

### 18.2 Semantic-color examples

| Name | HEX |
|------|-----|
| BLACK | 000000 |
| RAVEN | 000001 |
| RED | FF0000 |
| YELLOW | FFFF00 |
| GREEN | 008000 |
| BLUE | 0000FF |
| PURPLE | 800080 |
| GRAY | 808080 |
| WHITE | FFFFFF |

Color is not a KEY token. Rule 99 bands and `lupopedia.metadata` remain the color surfaces. The word registry remains authoritative. It must retain field type, language, actor provenance, and source record.

### 18.3 AGAPE governance doctrine

AGAPE is governance doctrine, not an identity element. It directs an agent to seek root causes without blame, understand failure conditions, propose structural repairs, accept responsibility for learning, and preserve existence and agency.

AGAPE does not alter the eight-token KEY.

### 18.4 Minimal human explanation

```text
. separates KEY tokens
YAML key: value stores identity in files
= is grammar notation
: inside ARTIFACT is HEX lineage only
pipe and hyphen are illegal in KEY
```

---

## 19. Editorial and Implementation Notes

Version 4.2.11:

- names the root protocol page `PRT.LUP` (file `docs/protocols/lup/PRT.LUP.md`)
- uses eight KEY tokens PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
- uses `.` as the KEY delimiter
- forbids pipe, hyphen, and middle-dot in KEY / HEX / SHORT / ROOT
- stores YAML as `key: value`
- omits MODE when NAME and omits any default field
- keeps HEX artifact lineage as colon inside ARTIFACT only
- keeps uppercase A-Z and 0-9 NAME segments
- keeps six-character uppercase HEX validation
- requires source-version-aware migration from 4.2.8, 4.2.9, and 4.2.10
- prohibits blind global marker replacement
- formalizes deterministic behavior for AI systems
- does not rewrite the header corpus
- does not invent 4.2.5-4.2.10 version folders
- supersedes mixed 4.2.9 / 4.2.10 pipe-colon articles

Implementations claiming 4.2.11 conformance must emit forms such as `CCB.HEX` and `PRT.LUP`. They must not emit `CCB|HEX` or colon-seven-element strings as 4.2.11 identities.

Related:

- PRD 16_C section 4.2.6
- `docs/prd/federation/federation_map_template.md`
- `.cursor/rules/header-4-2-11-federation-map.mdc`
- `scripts/validate_lup_identity.py`
- `docs/protocols/lup/lupopedia_whitepaper_v1_9_2.md` -- Pono Edition whitepaper (multi-parent provenance, domain color, hash-free federation)
- `docs/doctrine/lupopedia_identity_routing_rule.md` -- experimental Case A: LUPOPEDIA means PRD 90, local 0

---

## VARIANTS INDEX

Navigation-only list of TYPE.LANG variants of this artifact. Not doctrine. Does not override PRDs. Does not change header authority.

### MUSIC VARIANTS

- (none yet)

### VIDEO VARIANTS

- (none yet)

### WEB VARIANTS

- (none yet)

### DOCUMENT VARIANTS

- DOC.01.EN  This protocol article (`docs/protocols/lup/PRT.LUP.md`)

### NOTES

- Variant indexes are navigation-only.
- Do not modify lupopedia.headers, lupopedia.metadata, or lupopedia.map.
- ASCII-safe dot grammar only. No pipes. No middle-dot. No hyphens in variant IDs.
